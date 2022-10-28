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
        if ($registrant->save()) {
            return response()->json(['id' => $registrant->id, 'shopperReference' => $registrant->shopperReference()]);
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
        $acqResult = (new AdyenController)->terminalCloudCardAcquisitionRequest($request, true);
        $params = $request->all();
        $lineItemData = $params['data'];
        // Let's spoof this until I can get a test card
        // Assume we have gotten a shopper reference from the result
        $shopperReference = "001";
        $registrant = Registrant::findOrFail(ltrim($shopperReference, "0"));
        $lineItem = new LineItem;
        $lineItem->registrant_id = $registrant->id;
        $lineItem->item_name = $lineItemData['itemName'];
        $lineItem->item_sku = $lineItemData['itemSku'];
        $lineItem->unit_price = $lineItemData['unitPrice'] * 100; // We save as cents
        $lineItem->quantity = $lineItemData['quantity'];

        if ($lineItem->save()) {
            $runningTotal = 0;
            // We have persisted the line item, let's now get the running total for this shopper
            foreach($registrant->lineItemsUnpaid as $lineItem) {
                $runningTotal += ($lineItem->unit_price * $lineItem->quantity) / 100;
            }

            $params = array(
                "customerName" => $registrant->first_name . " " . $registrant->last_name,
                "runningTotal" => '£' . strval(number_format($runningTotal, 2))
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
}
