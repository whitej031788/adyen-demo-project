<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdyenPayByLink;

class AdyenController extends Controller
{
  public function __construct() {
    $this->adyenClient = new \Adyen\Client();
    $this->adyenClient->setXApiKey(\Config::get('adyen.apiKey'));
    $this->adyenClient->setEnvironment(\Adyen\Environment::TEST);
  }

  public function getPaymentMethods(Request $request) {
    $checkoutService = new \Adyen\Service\Checkout($this->adyenClient);
    $params = $request->all();

    $result = $this->makeAdyenRequest("paymentMethods", $params, false, $checkoutService);

    return response()->json($result);
  }

  public function makePayment(Request $request) {
    $checkoutService = new \Adyen\Service\Checkout($this->adyenClient);
    $params = $request->all();

    $cacheKeyRedirect = "http://localhost:8000/normal-redirect/" . "-" . $request->reference;

    $params["returnUrl"] = $cacheKeyRedirect;

    $result = $this->makeAdyenRequest("payments", $params, false, $checkoutService);

    return response()->json($result);
  }

  public function generateAndSendPaymentLink(Request $request) {
    $type = $request->type;
    $params = $request->data;

    $curlUrl = "https://checkout-test.adyen.com/v66/paymentLinks";

    $result = $this->makeAdyenRequest($curlUrl, $params, true, false);

    if ($type == 'sms') {
      \Nexmo::message()->send([
        'to' => $params['shopperPhone'],
        'from' => $params['merchantName'],
        'text' => "Please click the below to link to pay for your order:\n\n" . $result->url . " ||| "
      ]);
    } elseif ($type == 'email') {
      Mail::to($params['shopperEmail'])
        ->send(new AdyenPayByLink($result->url, $params['merchantName'], $params['reference']));
    }

    // 'fetch' is also a $type but that is just if they want to get the link, not send it

    return response()->json($result);
  }

  public function getPaymentLinkQR(Request $request) {
    $params = $request->all();
    $curlUrl = "https://checkout-test.adyen.com/v66/paymentLinks";

    $result = $this->makeAdyenRequest($curlUrl, $params, true, false);

    $urlToQrEncode = $result->url;
    $qrSvg = \QrCode::size(250)->generate($urlToQrEncode);

    return response()->json($qrSvg);
  }

  public function terminalCloudApiRequest(Request $request) {
    $terminalService = new \Adyen\Service\PosPayment($this->adyenClient);

    $params = $request->all();

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
            'POIID' => 'P400Plus-275384684',
          ),
          'PaymentRequest' =>
          array (
            'SaleData' =>
            array (
              'SaleTransactionID' =>
              array (
                'TransactionID' => $params['reference'],
                'TimeStamp' => date("c"),
              ),
            ),
            'PaymentTransaction' =>
            array (
              'AmountsReq' =>
              array (
                'Currency' => $params['amount']['currency'],
                'RequestedAmount' => (float)($params['amount']['value'] / 100),
              ),
            ),
          ),
        ),
      );

    $result = $this->makeAdyenRequest("runTenderSync", $saleToPoiRequest, false, $terminalService);

    return response()->json($result);
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
