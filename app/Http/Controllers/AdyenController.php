<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdyenPayByLink;
use Illuminate\Support\Facades\Cache;

class AdyenController extends Controller
{
  public function __construct() {
    $this->adyenClient = new \Adyen\Client();
    $this->adyenClient->setXApiKey(\Config::get('adyen.apiKey'));
    $this->adyenClient->setEnvironment(\Adyen\Environment::TEST);
  }

  // Rest API endpoint, can also be called from other controllers using second parameter
  public function getPaymentMethods(Request $request) {
    $checkoutService = new \Adyen\Service\Checkout($this->adyenClient);

    $params = $request->all();

    $result = $this->makeAdyenRequest("paymentMethods", $params, false, $checkoutService);

    return response()->json($result);
  }

  public function makePayment(Request $request) {
    $checkoutService = new \Adyen\Service\Checkout($this->adyenClient);
    $params = $request->all();

    $cacheKeyRedirect = "http://" . $_SERVER['HTTP_HOST'] . "/return-url/" . $request->reference;

    $params["returnUrl"] = $cacheKeyRedirect;

    // For Klarna, add some extra placeholder data to the API call
    if (isset($params['paymentMethod']['type']) &&
        (strpos($params['paymentMethod']['type'], 'klarna') !== false)) {
      $this->addKlarnaData($params);
    }

    $result = $this->makeAdyenRequest("payments", $params, false, $checkoutService);

    if ($result['resultCode'] == 'RedirectShopper') {
      // Store the payment data for 15 minutes
      $cache = Cache::put($request->reference, $result['paymentData'], now()->addMinutes(15));
    }

    return response()->json($result);
  }

  public function generateAndSendPaymentLink(Request $request) {
    $type = $request->type;
    $params = $request->data;

    $curlUrl = "https://checkout-test.adyen.com/" . \Config::get('adyen.checkoutApiVersion') . "/paymentLinks";

    $result = $this->makeAdyenRequest($curlUrl, $this->sanitizePblParams($params), true, false);

    // SMS will only work if you have setup Nexmo
    if ($type == 'sms') {
      \Nexmo::message()->send([
        'to' => $params['shopperPhone'],
        'from' => $params['merchantName'],
        'text' => "Please click the below to link to pay for your order:\n\n" . $result->url . " ||| "
      ]);
    } elseif ($type == 'email') {
      // Mail will only work if you have setup AWS SES
      Mail::to($params['shopperEmail'])
        ->send(new AdyenPayByLink($result->url, $params['merchantName'], $params['reference']));
    }

    // 'fetch' is also a $type but that is just if they want to get the link, not send it

    return response()->json($result);
  }

  public function getPaymentLinkQR(Request $request) {
    $params = $request->all();
    $curlUrl = "https://checkout-test.adyen.com/" . \Config::get('adyen.checkoutApiVersion') . "/paymentLinks";


    $result = $this->makeAdyenRequest($curlUrl, $this->sanitizePblParams($params), true, false);

    $urlToQrEncode = $result->url;
    $qrSvg = \QrCode::size(250)->generate($urlToQrEncode);

    return response()->json($qrSvg);
  }

  public function terminalCloudApiRequest(Request $request) {
    if ($request->has('terminal')) {
      $requestTerminal = $request->terminal;
    } else {
      $requestTerminal = "terminalPooid";
    }
    // If there is a second pooid setup, and a second api key, AND the request is for the second pooid, then we need a new client
    if ($requestTerminal == "terminalPooidTwo" && !empty(\Config::get('adyen.apiKeyTwo'))) {
      $newAdyenClient = new \Adyen\Client();
      $newAdyenClient->setXApiKey(\Config::get('adyen.apiKeyTwo'));
      $newAdyenClient->setEnvironment(\Adyen\Environment::TEST);
      $terminalService = new \Adyen\Service\PosPayment($newAdyenClient);
    } else {
      $terminalService = new \Adyen\Service\PosPayment($this->adyenClient);
    }

    $params = $request->all();

    $requestData = $params['data'];
    $pooid = \Config::get('adyen.' . $requestTerminal);

    $saleToPoiRequest = array (
      'SaleToPOIRequest' =>
        array (
          'MessageHeader' =>
          array (
            'ProtocolVersion' => '3.0',
            'MessageClass' => 'Service',
            'MessageCategory' => 'Payment',
            'MessageType' => 'Request',
            'ServiceID' => $this->generateRandomString(),
            'SaleID' => 'DemoCashRegister', // could be sales agentID or iPad
            'POIID' => $pooid,
          ),
          'PaymentRequest' =>
          array (
            'SaleData' =>
            array (
              'SaleTransactionID' =>
              array (
                'TransactionID' => $requestData['reference'],
                'TimeStamp' => date("c"),
              ),
            ),
            'PaymentTransaction' =>
            array (
              'AmountsReq' =>
              array (
                'Currency' => $requestData['amount']['currency'],
                'RequestedAmount' => (float)($requestData['amount']['value'] / 100),
              ),
            ),
          ),
        ),
      );

    $result = $this->makeAdyenRequest("runTenderSync", $saleToPoiRequest, false, $terminalService);

    return response()->json($result);
  }

  public function redirPayDet($details, $paymentData) {
    $checkoutService = new \Adyen\Service\Checkout($this->adyenClient);

    $params = array(
      'paymentData' => $paymentData,
      'details' => $details
    );

    $result = $this->makeAdyenRequest("paymentsDetails", $params, false, $checkoutService);

    return $result;
  }

  private function sanitizePblParams($params) {
    $returnData = $params;

    // Remove any parameters not supported by the PBL endpoint, maybe app specific
    unset($returnData['shopperInteraction']);
    unset($returnData['shopperPhone']);
    unset($returnData['merchantName']);

    return $returnData;
  }

  private function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  private function addKlarnaData(&$params) {
    // Let's just add fake data, we only need to make sure the amount all add up
    $params['shopperEmail'] = 'testdemoemail+pend-accept-01@testdemo.com';
    $params['telephoneNumber'] = '+447711567890';
    $params['billingAddress'] = $this->fakeBillingAddressArray();
    $params['deliveryAddress'] = $this->fakeDeliveryAddressArray();
    $params['shopperName'] = $this->fakeShopperName();
    $params['lineItems'] = $this->fakeKlarnaLineItems($params['amount']);
  }

  private function fakeBillingAddressArray() {
    return [
      "city" => "London",
      "country" => "GB",
      "houseNumberOrName" => "",
      "postalCode" => "N1",
      "street" => "123 Main St"
    ];
  }

  private function fakeDeliveryAddressArray() {
    return [
      "city" => "London",
      "country" => "GB",
      "houseNumberOrName" => "",
      "postalCode" => "N1",
      "street" => "123 Main St"
    ];
  }

  private function fakeShopperName() {
    return [
      'firstName' => 'Test',
      'lastName' => 'Demo'
    ];
  }

  private function fakeKlarnaLineItems($amount) {
    $retArr = array();

    $tmpArr = array(
      'quantity' => 1,
      'amountExcludingTax' => $amount['value'],
      'taxPercentage' => 0,
      'description' => 'Demo Checkout Item',
      'id' => 100,
      'taxAmount' => 0,
      'amountIncludingTax' => $amount['value']
    );

    array_push($retArr, $tmpArr);

    return $retArr;
  }

  private function makeAdyenRequest($methodOrUrl, $params, $isClassic, $service) {
    if (!$isClassic) {
      $result = $service->$methodOrUrl($params);
    } else {
      //JSON-ify the data for the POST
      $fields_string = json_encode($params);
      //Basic auth user
      $username = \Config::get('adyen.username');
      $password = \Config::get('adyen.password');

      //open connection
      $ch = curl_init();
      //set the url, number of POST vars, POST data
      curl_setopt($ch, CURLOPT_URL, $methodOrUrl);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
      curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json'
      ));

      //execute post
      $result = json_decode(curl_exec($ch));
    }

    return $result;
  }
}
