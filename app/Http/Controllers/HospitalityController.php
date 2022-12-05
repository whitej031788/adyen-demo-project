<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registrant;
use App\Models\LineItem;
use App\Http\Controllers\AdyenController;

class HospitalityController extends Controller
{
    public function addRegistrant(Request $request)
    {
        $registrant = new Registrant;
        $registrant->first_name = $request->firstName;
        $registrant->last_name = $request->lastName;
        $registrant->email = $request->email;
        $cardAcqResp = (new AdyenController)->terminalCloudCardAcquisitionRequest($request, true);
        // Is the additional response base64 encoded, and does it exist with the NFC UID
        $additionalResponse = $cardAcqResp['response']['SaleToPOIResponse']['CardAcquisitionResponse']['Response']['AdditionalResponse'];
        list($nfcUid, $store) = $this->findNfcUidAndStore($additionalResponse);

        $registrantExist = Registrant::where('nfc_uid', '=', $nfcUid)->orWhere('email', '=', $request->email)->get();

        // If it's empty, thats good, it means we don't have that NFC UID or email in the database and can add them
        if ($registrantExist->isEmpty()) {
            $registrant->nfc_uid = $nfcUid;
            if ($registrant->save()) {
                $params = array();
                $params['outputText'] = array(
                    array(
                        "Text" => "Thank you"
                    ),
                    array(
                        "Text" => "The customer is successfully registered"
                    )
                );
                $params['predefinedContent'] = "AcceptedAnimated";
                $displayResult = (new AdyenController)->terminalCloudCardAcquisitionAbortRequest($request, true, $params);
                return response()->json([
                    'data' => ['email' => $registrant->email, 'id' => $registrant->id, 'shopperReference' => $registrant->shopperReference(), 'nfcUid' => $nfcUid],
                    'message' => 'Registration Successful'
                ]);
            }
        } else {
            $registrantExist = $registrantExist[0];
            $params = array();
            $params['outputText'] = array(
                array(
                    "Text" => "Failure"
                ),
                array(
                    "Text" => "That email or device is already registered"
                )
            );
            $params['predefinedContent'] = "DeclinedAnimated";
            $displayResult = (new AdyenController)->terminalCloudCardAcquisitionAbortRequest($request, true, $params);
            return response()->json([
                'data' => ['email' => $registrantExist->email, 'id' => $registrantExist->id, 'shopperReference' => $registrantExist->shopperReference(), 'nfcUid' => $registrantExist->nfc_uid],
                'message' => 'Email or NFC device already registered'
            ], 422);
        }
    }

    public function removeRegistrant(Request $request)
    {
        $cardAcqResp = (new AdyenController)->terminalCloudCardAcquisitionRequest($request, true);
        $additionalResponse = $cardAcqResp['response']['SaleToPOIResponse']['CardAcquisitionResponse']['Response']['AdditionalResponse'];
        list($nfcUid, $store) = $this->findNfcUidAndStore($additionalResponse);

        $registrant = Registrant::where('nfc_uid', '=', $nfcUid)->orWhere('email', '=', $request->email)->get();
        if ($registrant->isEmpty()) {
            $params = array();
            $params['outputText'] = array(
                array(
                    "Text" => "Error"
                ),
                array(
                    "Text" => "We could not find that registrant record."
                )
            );
            $params['predefinedContent'] = "DeclinedAnimated";
            $displayResult = (new AdyenController)->terminalCloudCardAcquisitionAbortRequest($request, true, $params);
            return response()->json(['message' => 'Cannot find customer record'], 404); // Status code here
        } else {
            $registrant = $registrant[0];
            $registrant->delete();
            $params = array();
            $params['outputText'] = array(
                array(
                    "Text" => "Success"
                ),
                array(
                    "Text" => "Registrant removed from system"
                )
            );
            $params['predefinedContent'] = "AcceptedAnimated";
            $displayResult = (new AdyenController)->terminalCloudCardAcquisitionAbortRequest($request, true, $params);
            return response()->json([
                'data' => ['email' => $registrant->email, 'id' => $registrant->id, 'shopperReference' => $registrant->shopperReference(), 'nfcUid' => $registrant->nfc_uid],
                'message' => 'Registrant removed from system'
            ]); // Status code here
        }
    }

    public function updateRegistrant(Request $request, $id)
    {
        // TO DO: Make this able to update the entire registrant, not just the saved card
        $registrant = Registrant::findOrFail($id);
        $registrant->psp_card_token = $request->psp_card_token;
        $registrant->psp_card_type = $request->psp_card_type;
        $registrant->psp_last_four = $request->psp_last_four;
        if ($registrant->save()) {
            return response()->json([
                'id' => $registrant->id, 
                'shopperReference' => $registrant->shopperReference(),
                'psp_card_token' => $registrant->psp_card_token,
                'psp_card_type' => $registrant->psp_card_type,
                'psp_last_four' => $registrant->psp_last_four
            ]);
        }
    }

    public function addLineItem(Request $request)
    {
        // This is when the cash register attendant has typed in what the person wants to buy
        // We then do a card acquisiton request to the terminal with the amount
        // The customer then taps their NFC wearable, which should return to us the shopper reference
        // We check if we have that shopperReference in our registrant table, and if so, add a LineItem with what they are buying
        // We then send a terminal display request summing up everything they have bought to date
        $cardAcqResp = (new AdyenController)->terminalCloudCardAcquisitionRequest($request, true);
        $params = $request->all();
        // Is the additional response base64 encoded, and does it exist with the NFC UID
        $additionalResponse = $cardAcqResp['response']['SaleToPOIResponse']['CardAcquisitionResponse']['Response']['AdditionalResponse'];
        list($nfcUid, $store) = $this->findNfcUidAndStore($additionalResponse);
        $lineItemData = $params['data'];
        // Let's spoof this until I can get a test card
        // Assume we have gotten a shopper reference from the result
        $registrant = Registrant::where('nfc_uid', '=', $nfcUid)->get();
        if ($registrant->isEmpty()) {
            $params = array();
            $params['outputText'] = array(
                array(
                    "Text" => "Error"
                ),
                array(
                    "Text" => "We could not find your customer record."
                )
            );
            $params['predefinedContent'] = "DeclinedAnimated";
            $displayResult = (new AdyenController)->terminalCloudCardAcquisitionAbortRequest($request, true, $params);
            return response()->json(['message' => 'Cannot find customer record'], 404); // Status code here
        } else {
            $registrant = $registrant[0];
        }

        $lineItem = new LineItem;
        $lineItem->registrant_id = $registrant->id;
        $lineItem->item_name = $lineItemData['itemName'];
        $lineItem->item_sku = $lineItemData['itemSku'];
        $lineItem->unit_price = $lineItemData['unitPrice'] * 100; // We save as cents
        $lineItem->quantity = $lineItemData['quantity'];
        $lineItem->store = $store;

        if ($lineItem->save()) {
            $runningTotal = 0;
            // We have persisted the line item, let's now get the running total for this shopper
            foreach($registrant->lineItemsUnpaid as $lineItem) {
                $runningTotal += ($lineItem->unit_price * $lineItem->quantity) / 100;
            }

            $runningTotal = '£' . strval(number_format($runningTotal, 2));
            $params = array(
                "outputText" => array(
                    array(
                        "Text" => $runningTotal
                    ),
                    array(
                        "Text" => "Thank you " . $registrant->first_name . " " . $registrant->last_name . ". Your room bill currently is:"
                    )
                ),
                "predefinedContent" => "AcceptedAnimated"
            );

            $displayResult = (new AdyenController)->terminalCloudCardAcquisitionAbortRequest($request, true, $params);

            return response()->json([
                'id' => $registrant->id, 
                'shopperReference' => $registrant->shopperReference(),
                'psp_card_token' => $registrant->psp_card_token,
                'runningTotal' => $runningTotal,
                'customerName' => $registrant->first_name . " " . $registrant->last_name,
                'lineItems' => $registrant->lineItemsUnpaid
            ]);
        }
    }

    public function removeLineItem(Request $request)
    {
        $lineItem = LineItem::where('registrant_id', intval($request->registrantId))->where('id', intval($request->lineItemId))->first();
        $res = $lineItem->delete();
        return response()->json($res);
    }

    public function showVirtualReceipt(Request $request)
    {
        $registrant = Registrant::findOrFail(intval($request->registrantId));
        $runningTotal = 0;

        foreach($registrant->lineItemsUnpaid as $lineItem) {
            $runningTotal += ($lineItem->unit_price * $lineItem->quantity);
        }

        $params = array();

        // TESTING VIRTUAL RECEIPT
        $params['virtualReceipt'] = $this->generateVirtualReceiptData($registrant->lineItemsUnpaid, $runningTotal);
        $displayResult = (new AdyenController)->terminalCloudDisplayRequest($request, true, $params);

        return response()->json($displayResult['response']['SaleToPOIResponse']);
    }

    public function payFinalBill(Request $request)
    {
        $registrant = Registrant::findOrFail(intval($request->registrantId));
        $runningTotal = 0;

        foreach($registrant->lineItemsUnpaid as $lineItem) {
            $runningTotal += ($lineItem->unit_price * $lineItem->quantity);
        }

        $overrideParams = array(
            'reference' => $request->data['reference'],
            'amount' => array(
                'currency' => 'GBP', 
                'value' => $runningTotal
            )
        );

        // Here we can check if they have a card on file by looking at the Registrant record
        // If they do, we can send an input request to see if they want to use the saved card or a new card
        // If not, just send a payment request
        $inputResult = (new AdyenController)->terminalCloudSingleAnswerInput($request, true, $overrideParams);
        $newPaymentRequest = $inputResult['response']['SaleToPOIResponse']['InputResponse']['InputResult']['Input']['MenuEntryNumber'][1] == 1;

        if ($newPaymentRequest) {
            $paymentResult = (new AdyenController)->terminalCloudApiRequest($request, true, $overrideParams);
            $isSuccess = $paymentResult['response']['SaleToPOIResponse']['PaymentResponse']['Response']['Result'];

            if ($isSuccess) {
                $registrant->lineItemsUnpaid()->update(['is_paid' => 1]);
            }

            return response()->json($paymentResult['response']['SaleToPOIResponse']);
        } else {
            // Here we would need to charge the card on file
            // TO DO
            return response()->json($newPaymentRequest);
        }
    }

    private function mockCardAcqResponse()
    {
        $jayParsedAry = [
        "response" => ["SaleToPOIResponse" => [
                "CardAcquisitionResponse" => [
                    "POIData" => [
                        "POIReconciliationID" => "1000", 
                        "POITransactionID" => [
                            "TimeStamp" => "2022-11-06T22:11:11.000Z", 
                            "TransactionID" => "BqgO001667772671009" 
                        ] 
                    ], 
                    "PaymentInstrumentData" => [
                            "CardData" => [
                            ], 
                            "PaymentInstrumentType" => "Card" 
                        ], 
                    "Response" => [
                                "AdditionalResponse" => "tid=47153132&transactionType=GOODS_SERVICES&posadditionalamounts.originalAmountValue=1740&giftcardIndicator=false&posAmountGratuityValue=0&store=UKEvent_bar01&iso8601TxDate=2022-11-06T22%3a11%3a11.0000000%2b0000&posOriginalAmountValue=1740&txtime=22%3a11%3a11&NFC.uid=7683F826&txdate=06-11-2022&NFC.data=&merchantReference=b19a5b9b-ef05-4792-a868-590a96dc4583&posadditionalamounts.originalAmountCurrency=GBP&NFC.ref=mifareCard&posAuthAmountCurrency=GBP&message=CARD_ACQ_COMPLETED&posAmountCashbackValue=0&posEntryMode=CLESS_SWIPE&posAuthAmountValue=1740", 
                                "Result" => "Success" 
                                ], 
                    "SaleData" => [
                                    "SaleTransactionID" => [
                                        "TimeStamp" => "2022-11-06T22:11:10.959Z", 
                                        "TransactionID" => "b19a5b9b-ef05-4792-a868-590a96dc4583" 
                                    ] 
                                ] 
                ], 
                "MessageHeader" => [
                    "MessageCategory" => "CardAcquisition", 
                    "MessageClass" => "Service", 
                    "MessageType" => "Response", 
                    "POIID" => "V400m-347153132", 
                    "ProtocolVersion" => "3.0", 
                    "SaleID" => "PostmanTestPOS", 
                    "ServiceID" => "1667772671" 
                ] 
            ] 
        ]]; 
 
        return $jayParsedAry;
    }

    private function findNfcUidAndStore($additionalResponse)
    {
        if (base64_encode(base64_decode($additionalResponse, true)) === $additionalResponse) {
            $jsonString = base64_decode($additionalResponse, true);
            $data = json_decode($jsonString, TRUE);
            $nfcUid = $data['additionalData']['NFC.uid'];
            $store = $data['store'];
        } else {
            // It's just key value pairs
            parse_str($additionalResponse, $output);
            $nfcUid = $output['NFC_uid'];
            $store = $output['store'];
        }

        return array($nfcUid, $store);
    }

    private function generateVirtualReceiptData($arrayOfItems, $runningTotal)
    {
        $xml = htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>');
        
        $xml .= htmlspecialchars('<screen name="virtual-receipt-with-qr-code.xslt">');
        $xml .= htmlspecialchars('<receipt>');
        $xml .= htmlspecialchars('
        <qrcodeblock>
            <qrheader>
                <description>Scan to access member card</description>
            </qrheader>
            <call-to-action>Scan</call-to-action>
            <qrcodedata>https%3A%2F%2Fwww%2Eadyen%2Ecom%2Fsignup%2F%3Flocation%3Damsterdam%26store%3DStore42%26POSID%3DREG0042%26hash%3DAAhbcdfjkbckjwbnadsjkn4%3D</qrcodedata>
            <qrfooter>
                <description>Don"t have the app? Scan to download</description>
            </qrfooter>
        </qrcodeblock>
        ');
        $xml .= htmlspecialchars("<list-header>Your Bill</list-header>");
        $xml .= htmlspecialchars('<lines>');

        foreach($arrayOfItems as $lineItem) {
            $xml .= htmlspecialchars('<lineitem>');
            $xml .= htmlspecialchars('<count>' . $lineItem->quantity . '</count>');
            $xml .= htmlspecialchars('<description>' . $lineItem->item_name . '</description>');
            $xml .= htmlspecialchars('<amount>');
            $xml .= htmlspecialchars('<currency>£</currency>');
            $xml .= htmlspecialchars('<value>' . number_format((float)($lineItem->unit_price / 100), 2, '.', '') . '</value>');
            $xml .= htmlspecialchars('</amount>');
            $xml .= htmlspecialchars('</lineitem>');
        }

        $xml .= htmlspecialchars('</lines>');
        $xml .= htmlspecialchars('<total>');
        $xml .= htmlspecialchars('<description>Total amount:</description>
        <amount>
            <currency>£</currency>
            <value>' . number_format((float)($runningTotal / 100), 2, '.', '') . '</value>
        </amount>
        ');
        $xml .= htmlspecialchars('</total>');
        $xml .= htmlspecialchars('</receipt>');
        $xml .= htmlspecialchars('</screen>');

        return base64_encode(htmlspecialchars_decode($xml));
    }
}