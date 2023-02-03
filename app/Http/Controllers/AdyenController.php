<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdyenPayByLink;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class AdyenController extends Controller
{
    public function __construct()
    {
        $this->adyenClient = new \Adyen\Client();
        $this->adyenClient->setXApiKey(\Config::get('adyen.apiKey'));
        $this->apiKey = (\Config::get('adyen.apiKey'));
        $this->adyenClient->setEnvironment(\Adyen\Environment::TEST);
    }

    // Rest API endpoint, can also be called from other controllers using second parameter
    public function getPaymentMethods(Request $request)
    {
        $checkoutService = new \Adyen\Service\Checkout($this->adyenClient);
        $params = $request->all();
        $result = $this->makeAdyenRequest("paymentMethods", $params, false, $checkoutService);
        return response()->json($result);
    }

    //endpoint for the additional details for paypal
    public function submitAdditionalDetails(Request $request)
    {
        $checkoutService = new \Adyen\Service\Checkout($this->adyenClient);
        $params = $request->all();
        $result = $this->makeAdyenRequest("paymentsDetails", $params, false, $checkoutService);

        if ($result["response"]['resultCode'] == 'RedirectShopper') {
            $cache = Cache::put($request->reference, $result['paymentData'], now()->addMinutes(15));
        }
        return response()->json($result);
    }

    public function makePayment(Request $request)
    {
        $checkoutService = new \Adyen\Service\Checkout($this->adyenClient);
        $params = $request->all();

        $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';

        $cacheKeyRedirect = $protocol . $_SERVER['HTTP_HOST'] . "/return-url/" . $request->reference;

        $params["returnUrl"] = $cacheKeyRedirect;

        // For Klarna, add some extra placeholder data to the API call
        if (isset($params['paymentMethod']['type']) &&
            (strpos($params['paymentMethod']['type'], 'klarna') !== false)) {
            $this->addKlarnaData($params);
        }

        $this->addLoggedInDetails($params);

        $result = $this->makeAdyenRequest("payments", $params, false, $checkoutService);

        if ($result["response"]['resultCode'] == 'RedirectShopper' && isset($result["response"]['paymentData'])) {
            // Store the payment data for 15 minutes
            $cache = Cache::put($request->reference, $result['paymentData'], now()->addMinutes(15));
        }

        return response()->json($result);
    }

    public function makeCashPayment(Request $request, $isInternal = false)
    {
        $params = $request->all();
        $curlUrl = "https://pal-test.adyen.com/pal/servlet/CustomPayment/beginCustomPayment";

        $result = $this->makeAdyenRequest($curlUrl, $params, true, false);

        if (!$isInternal) {
            return response()->json($result);
        } else {
            return $result;
        }

        return response()->json($result);
    }

    public function sendQRToTerminal(Request $request, $isInternal = false)
    {
        $params = $request->all();
        $curlUrl = "https://checkout-test.adyen.com/" . \Config::get('adyen.checkoutApiVersion') . "/paymentLinks";

        $pblData = $params['data'];
        $result = $this->makeAdyenRequest($curlUrl, $this->sanitizePblParams($pblData), true, false);
        $urlToQrEncode = $result["response"]->url;

        $terminalService = new \Adyen\Service\PosPayment($this->adyenClient);
        
        if ($request->has('terminal')) {
            $requestTerminal = $request->terminal;
        } else {
            $requestTerminal = "terminalPooid";
        }

        $pooid = \Config::get('adyen.' . $requestTerminal);

        $extraParams = array('urlToQr' => $urlToQrEncode);

        $poiRequest = $this->terminalDisplayQRCodeObject($params['data'], $pooid, $extraParams);

        $result = $this->makeAdyenRequest("runTenderSync", $poiRequest, false, $terminalService);

        if (!$isInternal) {
            return response()->json($result);
        } else {
            return $result;
        }

        return response()->json($result);
    }

    public function tapToPaySession(Request $request) {
        $url = 'https://checkout-test.adyen.com/checkout/possdk/v68/sessions';
        $params = array('setupToken' => $request->setupToken, 'merchantAccount' => $request->merchantAccount, 'store' => $request->store);
        $result = $this->makeAdyenRequest($url, $params, true, false);
        return response()->json($result['response']);
      }

    public function generateAndSendPaymentLink(Request $request)
    {
        $type = $request->type;
        $params = $request->data;

        $demo = $request->session()->get('demo_session');
        $merchantName = json_decode($demo)->merchantName;

        $curlUrl = "https://checkout-test.adyen.com/" . \Config::get('adyen.checkoutApiVersion') . "/paymentLinks";

        $result = $this->makeAdyenRequest($curlUrl, $this->sanitizePblParams($params), true, false);

        // SMS will only work if you have setup Nexmo
        if ($type == 'sms') {
            \Nexmo::message()->send([
                'to' => $params['shopperPhone'],
                'from' => $merchantName,
                'text' => "Please click the below to link to pay for your order:\n\n" . $result["response"]->url . " ||| "
            ]);
        } elseif ($type == 'email') {
            // Mail will only work if you have setup AWS SES
            Mail::to($params['shopperEmail'])
                ->send(new AdyenPayByLink($result["response"]->url, $merchantName, $params['reference']));
        }

        // 'fetch' is also a $type but that is just if they want to get the link, not send it
        return response()->json($result);
    }

    public function getPaymentLinkQR(Request $request)
    {
        $params = $request->all();
        $curlUrl = "https://checkout-test.adyen.com/" . \Config::get('adyen.checkoutApiVersion') . "/paymentLinks";

        $result = $this->makeAdyenRequest($curlUrl, $this->sanitizePblParams($params), true, false);
        $urlToQrEncode = $result["response"]->url;
        $qrSvg = \QrCode::size(250)->generate($urlToQrEncode);
        $result["qrSvg"] = $qrSvg;

        return response()->json($result);
    }

    public function terminalCloudCardAcquisitionRequest(Request $request, $isInternal = false)
    {
        $terminalService = new \Adyen\Service\PosPayment($this->adyenClient);
        
        if ($request->has('terminal')) {
            $requestTerminal = $request->terminal;
        } else {
            $requestTerminal = "terminalPooid";
        }

        $pooid = \Config::get('adyen.' . $requestTerminal);

        $params = $request->all();

        $poiRequest = $this->cardAcquisitionNFCRequestObject($params['data'], $pooid);

        $result = $this->makeAdyenRequest("runTenderSync", $poiRequest, false, $terminalService);

        if (!$isInternal) {
            return response()->json($result);
        } else {
            return $result;
        }
    }

    public function terminalCloudDisplayRequest(Request $request, $isInternal = false, $extraParams = null)
    {
        $terminalService = new \Adyen\Service\PosPayment($this->adyenClient);
        
        if ($request->has('terminal')) {
            $requestTerminal = $request->terminal;
        } else {
            $requestTerminal = "terminalPooid";
        }

        $pooid = \Config::get('adyen.' . $requestTerminal);

        $params = $request->all();

        $poiRequest = $this->terminalDisplayRequestObject($params['data'], $pooid, $extraParams);

        $result = $this->makeAdyenRequest("runTenderSync", $poiRequest, false, $terminalService);

        if (!$isInternal) {
            return response()->json($result);
        } else {
            return $result;
        }
    }

    public function terminalCloudCardAcquisitionAbortRequest(Request $request, $isInternal = false, $extraParams = null)
    {
        $terminalService = new \Adyen\Service\PosPayment($this->adyenClient);
        
        if ($request->has('terminal')) {
            $requestTerminal = $request->terminal;
        } else {
            $requestTerminal = "terminalPooid";
        }

        $pooid = \Config::get('adyen.' . $requestTerminal);

        $params = $request->all();

        $poiRequest = $this->abortAcquisitionRequestObject($params['data'], $pooid, $extraParams);

        $result = $this->makeAdyenRequest("runTenderSync", $poiRequest, false, $terminalService);

        if (!$isInternal) {
            return response()->json($result);
        } else {
            return $result;
        }
    }

    public function terminalCloudSingleAnswerInput(Request $request, $isInternal = false, $extraParams = null)
    {
        $terminalService = new \Adyen\Service\PosPayment($this->adyenClient);
        
        if ($request->has('terminal')) {
            $requestTerminal = $request->terminal;
        } else {
            $requestTerminal = "terminalPooid";
        }

        $pooid = \Config::get('adyen.' . $requestTerminal);

        $params = $request->all();

        $poiRequest = $this->singleAnswerInputRequestObject($params['data'], $pooid, $extraParams);

        $result = $this->makeAdyenRequest("runTenderSync", $poiRequest, false, $terminalService);

        if (!$isInternal) {
            return response()->json($result);
        } else {
            return $result;
        }
    }

    public function terminalCloudApiRequest(Request $request, $isInternal = false, $overrideParams = null)
    {
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

        if (!is_null($overrideParams) && $overrideParams) {
            $requestData = $overrideParams;
        } else {
            $requestData = $params['data'];
        }

        $pooid = \Config::get('adyen.' . $requestTerminal);

        $saleToPoiRequest = array(
            'SaleToPOIRequest' =>
                array(
                    'MessageHeader' =>
                        array(
                            'ProtocolVersion' => '3.0',
                            'MessageClass' => 'Service',
                            'MessageCategory' => 'Payment',
                            'MessageType' => 'Request',
                            'ServiceID' => $this->generateRandomString(),
                            'SaleID' => 'DemoCashRegister', // could be sales agentID or iPad
                            'POIID' => $pooid,
                        ),
                    'PaymentRequest' =>
                        array(
                            'SaleData' =>
                                array(
                                    'SaleTransactionID' =>
                                        array(
                                            'TransactionID' => $requestData['reference'],
                                            'TimeStamp' => date("c"),
                                        ),
                                ),
                            'PaymentTransaction' =>
                                array(
                                    'AmountsReq' =>
                                        array(
                                            'Currency' => $requestData['amount']['currency'],
                                            'RequestedAmount' => (float)($requestData['amount']['value'] / 100),
                                        ),
                                ),
                        ),
                ),
        );

        $result = $this->makeAdyenRequest("runTenderSync", $saleToPoiRequest, false, $terminalService);

        if (!$isInternal) {
            return response()->json($result);
        } else {
            return $result;
        }
    }

    public function makeDonation(Request $request){
        $params = $request->all();
        $curlUrl = "https://checkout-test.adyen.com/" . \Config::get('adyen.checkoutApiVersion') . "/donations";
        $result = $this->makeAdyenRequest($curlUrl, $params, true, false);
        return response()->json($result);
    }


    public function redirPayDet($details, $paymentData)
    {
        $checkoutService = new \Adyen\Service\Checkout($this->adyenClient);

        $params = array(
            'paymentData' => $paymentData,
            'details' => $details
        );

        $result = $this->makeAdyenRequest("paymentsDetails", $params, false, $checkoutService);

        return $result;
    }

    public function adjustPayment(Request $request)
    {
        $params = $request->all();
        $curlUrl = "https://pal-test.adyen.com/pal/servlet/Payment/v64/adjustAuthorisation";
        $result = $this->makeAdyenRequest($curlUrl, $params, true, false);
        return response()->json($result);
    }

    public function capturePayment(Request $request)
    {
        $params = $request->all();
        $curlUrl = "https://pal-test.adyen.com/pal/servlet/Payment/v64/capture";
        $result = $this->makeAdyenRequest($curlUrl, $params, true, false);
        return response()->json($result);
    }

    public function getCostEstimate(Request $request){
        $params = $request->all();
        $url = "https://pal-test.adyen.com/pal/servlet/BinLookup/" . \Config::get('adyen.binLookup') . "/getCostEstimate";
        $result = $this->makeAdyenRequest($url, $params, true, false);
        return response()->json($result);
    }

    public function checkBalance(Request $request) {
        $params = $request->all();
        $url = "https://checkout-test.adyen.com/" . \Config::get('adyen.checkoutApiVersion') . "/paymentMethods/balance";
        $result = $this->makeAdyenRequest($url, $params, true, false);
        return response()->json($result);
    }

    public function createOrder(Request $request) {
        $params = $request->all();
        $url = "https://checkout-test.adyen.com/" . \Config::get('adyen.checkoutApiVersion') . "/orders";
        $result = $this->makeAdyenRequest($url, $params, true, false);
        return response()->json($result);
    }

    // HACK
    public function hackTime($voteFor) {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"https://live.adyentechevent.com/games/registerVote?votefor=" . $voteFor);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'referer: https://live.adyentechevent.com',
            'cookie: token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbWFpbCI6ImphbWllLndoaXRlQGFkeWVuLmNvbSIsInVzZXJJZCI6NjQ4LCJpYXQiOjE2NjU0MDQzMjMsImV4cCI6MTY3MDY2NDMyM30.AzWHhWIXo_OWLvawrMEH5YLdXU7UgRtKgIqgNu9BPRc'
        ));

        $server_output = curl_exec($ch);

        $info = curl_getinfo($ch);

        curl_close($ch);
        return response()->json([
            "message" => "Vote registered for " . $voteFor . "\n",
            "output" => $info
        ]);
    }

    private function sanitizePblParams($params)
    {
        $returnData = $params;

        // Remove any parameters not supported by the PBL endpoint, maybe app specific
        unset($returnData['shopperInteraction']);
        unset($returnData['shopperPhone']);
        unset($returnData['merchantName']);

        return $returnData;
    }
    private function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function addKlarnaData(&$params)
    {
        // Let's just add fake data, we only need to make sure the amount all add up
        $params['shopperEmail'] = 'testdemoemail+pend-accept-01@testdemo.com';
        $params['telephoneNumber'] = '+447711567890';
        $params['billingAddress'] = $this->fakeBillingAddressArray();
        $params['deliveryAddress'] = $this->fakeDeliveryAddressArray();
        $params['shopperName'] = $this->fakeShopperName();
        $params['lineItems'] = $this->fakeKlarnaLineItems($params['amount']);
    }

    private function fakeBillingAddressArray()
    {
        return [
            "city" => "London",
            "country" => "GB",
            "houseNumberOrName" => "",
            "postalCode" => "N1",
            "street" => "123 Main St"
        ];
    }

    private function fakeDeliveryAddressArray()
    {
        return [
            "city" => "London",
            "country" => "GB",
            "houseNumberOrName" => "",
            "postalCode" => "N1",
            "street" => "123 Main St"
        ];
    }

    private function fakeShopperName()
    {
        return [
            'firstName' => 'Test',
            'lastName' => 'Demo'
        ];
    }

    private function fakeKlarnaLineItems($amount)
    {
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

    private function singleAnswerInputRequestObject($params, $pooid)
    {
        $saleToPoiRequest = array(
            'SaleToPOIRequest' =>
                array(
                    'MessageHeader' =>
                        array(
                            'ProtocolVersion' => '3.0',
                            'MessageClass' => 'Device',
                            'MessageCategory' => 'Input',
                            'MessageType' => 'Request',
                            'ServiceID' => $this->generateRandomString(),
                            'SaleID' => 'DemoCashRegister', // could be sales agentID or iPad
                            'POIID' => $pooid,
                        ),
                    'InputRequest' =>
                        array(
                            'InputData' =>
                                array(
                                    "Device" => "CustomerInput",
                                    "InfoQualify" => "Input",
                                    "InputCommand" => "GetMenuEntry",
                                    "MaxInputTime" => 120
                                ),
                            'DisplayOutput' =>
                                array(
                                    'OutputContent' =>
                                        array(
                                            "PredefinedContent" => array("ReferenceID" => "MenuButtons"),
                                            'OutputFormat' => "Text",
                                            'OutputText' => 
                                                array(
                                                    array(
                                                        "Text" => "How would you like to pay?"
                                                    )
                                                ),
                                        ),
                                    'MenuEntry' => 
                                        array(
                                            array(
                                                'OutputFormat' => "Text",
                                                'OutputText' => 
                                                    array(
                                                        array(
                                                            "Text" => "Card On File"
                                                        )
                                                    ),
                                            ),
                                            array(
                                                'OutputFormat' => "Text",
                                                'OutputText' => 
                                                    array(
                                                        array(
                                                            "Text" => "New Card"
                                                        )
                                                    ),
                                            ),
                                            array(
                                                'OutputFormat' => "Text",
                                                'OutputText' => 
                                                    array(
                                                        array(
                                                            "Text" => "Cash"
                                                        )
                                                    ),
                                            ),
                                        ),
                                    'Device' => "CustomerDisplay",
                                    'InfoQualify' => "Display",
                                ),
                        ),
                ),
        );

        return $saleToPoiRequest;        
    }

    private function cardAcquisitionNFCRequestObject($params, $pooid)
    {
        $saleToPoiRequest = array(
            'SaleToPOIRequest' =>
                array(
                    'MessageHeader' =>
                        array(
                            'ProtocolVersion' => '3.0',
                            'MessageClass' => 'Service',
                            'MessageCategory' => 'CardAcquisition',
                            'MessageType' => 'Request',
                            'ServiceID' => $this->generateRandomString(),
                            'SaleID' => 'DemoCashRegister', // could be sales agentID or iPad
                            'POIID' => $pooid,
                        ),
                    'CardAcquisitionRequest' =>
                        array(
                            'SaleData' =>
                                array(
                                    'SaleTransactionID' =>
                                        array(
                                            'TransactionID' => $params['reference'],
                                            'TimeStamp' => date("c"),
                                        ),
                                    'SaleToPOIData' => base64_encode('{"Operation":[{"Type":"NFCReadUID"}]}')
                                ),
                            'CardAcquisitionTransaction' =>
                                array(
                                    'TotalAmount' => (float)($params['amount']['value'] / 100)
                                ),
                        ),
                ),
        );

        return $saleToPoiRequest;
    }

    private function cardAcquisitionRequestObject($params, $pooid)
    {
        $saleToPoiRequest = array(
            'SaleToPOIRequest' =>
                array(
                    'MessageHeader' =>
                        array(
                            'ProtocolVersion' => '3.0',
                            'MessageClass' => 'Service',
                            'MessageCategory' => 'CardAcquisition',
                            'MessageType' => 'Request',
                            'ServiceID' => $this->generateRandomString(),
                            'SaleID' => 'DemoCashRegister', // could be sales agentID or iPad
                            'POIID' => $pooid,
                        ),
                    'CardAcquisitionRequest' =>
                        array(
                            'SaleData' =>
                                array(
                                    'SaleTransactionID' =>
                                        array(
                                            'TransactionID' => $params['reference'],
                                            'TimeStamp' => date("c"),
                                        ),
                                    'TokenRequestedType' => "Customer"
                                ),
                            'CardAcquisitionTransaction' =>
                                array(
                                    'TotalAmount' => (float)($params['amount']['value'] / 100)
                                ),
                        ),
                ),
        );

        return $saleToPoiRequest;
    }

    private function terminalDisplayRequestObject($params, $pooid, $extraParams)
    {
        $saleToPoiRequest = array(
            'SaleToPOIRequest' =>
                array(
                    'MessageHeader' =>
                        array(
                            'ProtocolVersion' => '3.0',
                            'MessageClass' => 'Device',
                            'MessageCategory' => 'Display',
                            'MessageType' => 'Request',
                            'ServiceID' => $this->generateRandomString(),
                            'SaleID' => 'DemoCashRegister', // could be sales agentID or iPad
                            'POIID' => $pooid,
                        ),
                    'DisplayRequest' =>
                        array(
                            'DisplayOutput' =>
                                array(
                                    array(
                                        "Device" => "CustomerDisplay",
                                        "InfoQualify" => "Display",
                                        "OutputContent" => array("OutputFormat" => "XHTML", "OutputXHTML" => $extraParams['virtualReceipt'])
                                    )
                                )
                        ),
                ),
        );

        return $saleToPoiRequest;
    }

    private function terminalDisplayQRCodeObject($params, $pooid, $extraParams)
    {
        $saleToPoiRequest = array(
            'SaleToPOIRequest' =>
                array(
                    'MessageHeader' =>
                        array(
                            'ProtocolVersion' => '3.0',
                            'MessageClass' => 'Device',
                            'MessageCategory' => 'Display',
                            'MessageType' => 'Request',
                            'ServiceID' => $this->generateRandomString(),
                            'SaleID' => 'DemoCashRegister', // could be sales agentID or iPad
                            'POIID' => $pooid,
                        ),
                    'DisplayRequest' =>
                        array(
                            'DisplayOutput' =>
                                array(
                                    array(
                                        "Device" => "CustomerDisplay",
                                        "InfoQualify" => "Display",
                                        "MinimumDisplayTime" => 30,
                                        "OutputContent" => array(
                                            "OutputFormat" => "BarCode",
                                            "OutputBarcode" => array(
                                                "BarcodeType" => "QRCode",
                                                "BarcodeValue" => $extraParams['urlToQr']
                                            )
                                        )
                                    )
                                )
                        ),
                ),
        );

        return $saleToPoiRequest;
    }

    private function abortAcquisitionRequestObject($params, $pooid, $extraParams)
    {
        $outputText = $extraParams['outputText'];
        $predefContent = $extraParams['predefinedContent'];
        $saleToPoiRequest = array(
            'SaleToPOIRequest' =>
                array(
                    'MessageHeader' =>
                        array(
                            'ProtocolVersion' => '3.0',
                            'MessageClass' => 'Service',
                            'MessageCategory' => 'EnableService',
                            'MessageType' => 'Request',
                            'ServiceID' => $this->generateRandomString(),
                            'SaleID' => 'DemoCashRegister', // could be sales agentID or iPad
                            'POIID' => $pooid,
                        ),
                    'EnableServiceRequest' =>
                        array(
                            'TransactionAction' => "AbortTransaction",
                            'DisplayOutput' =>
                                array(
                                    'OutputContent' =>
                                        array(
                                            "PredefinedContent" => array("ReferenceID" => $predefContent),
                                            'OutputFormat' => "Text",
                                            'OutputText' => $outputText
                                        ),
                                    'Device' => "CustomerDisplay",
                                    'InfoQualify' => "Display",
                                ),
                        ),
                ),
        );

        return $saleToPoiRequest;
    }

    private function makeAdyenRequest($methodOrUrl, $params, $isClassic, $service)
    {
        if (!$isClassic) {
            $result = $service->$methodOrUrl($params);
        } else {
            //JSON-ify the data for the POST
            $fields_string = json_encode($params);
            //open connection
            $ch = curl_init();
            //set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $methodOrUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch, CURLOPT_VERBOSE, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'x-api-key: ' . \Config::get('adyen.apiKey')
            ));

            //execute post
            $result = json_decode(curl_exec($ch));
        }

        return array(
            "method" => $methodOrUrl,
            "request" => $params,
            "response" => $result,
        );
    }

    private function addLoggedInDetails(&$params) {
        $user = Auth::user();
        if (!$user) {
            return;
        } else {
            $theName = explode(" ", $user->name);
            $params['shopperEmail'] = $user->email;
            $params['shopperName'] = [
                'firstName' => $theName[0],
                'lastName' => $theName[1]
            ];
            $params['shopperReference'] = sprintf('%03d', $user->id);;
        }
    }
}
