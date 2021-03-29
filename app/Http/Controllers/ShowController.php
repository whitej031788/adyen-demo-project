<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ShowController extends Controller
{
  public function index(Request $request) {
    return view('home', [
      'merchantAccount' => \Config::get('adyen.ecomMerchantAccount')
    ]);
  }

  public function customCallCenter(Request $request) {
    return view('custom-call-center', [
      'merchantAccount' => \Config::get('adyen.motoMerchantAccount'),
      'clientKey' => \Config::get('adyen.clientKey')
    ]);
  }

  public function standardEcom(Request $request) {
    return view('standard-ecom', [
      'merchantAccount' => \Config::get('adyen.ecomMerchantAccount'),
      'clientKey' => \Config::get('adyen.clientKey'),
      'terminalPooid' => \Config::get('adyen.terminalPooid'),
      'terminalPooidTwo' => \Config::get('adyen.terminalPooidTwo')
    ]);
  }

  public function returnUrl(Request $request, $payRef) {
    $postback = $request->all();
    // We are going to call paymentDetails
    $adyenController = new AdyenController();
    // We stored the payment data in the file storage, go get it
    $paymentData = Cache::store('file')->get($payRef);

    $response = $adyenController->redirPayDet($postback, $paymentData);

    return view('return-url', [
      'merchantAccount' => \Config::get('adyen.ecomMerchantAccount'),
      'clientKey' => \Config::get('adyen.clientKey'),
      'paymentResult' => $response
    ]);
  }

  public function afpOnboarding(Request $request) {
    return view('afp-onboarding');
  }
}
