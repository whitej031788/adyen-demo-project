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

  public function paymentLinks(Request $request) {
    return view('payment-links', [
      'merchantAccount' => \Config::get('adyen.ecomMerchantAccount'),
      'clientKey' => \Config::get('adyen.clientKey')
    ]);
  }

  public function unifiedCommerce(Request $request) {
    return view('unified-commerce', [
      'merchantAccount' => \Config::get('adyen.ecomMerchantAccount'),
      'merchantAccountPos' => \Config::get('adyen.posMerchantAccount'),
      'clientKey' => \Config::get('adyen.clientKey'),
      'terminalPooid' => \Config::get('adyen.terminalPooid'),
      'terminalPooidTwo' => \Config::get('adyen.terminalPooidTwo'),
      'paypalID' => \Config::get('adyen.paypalID')
    ]);
  }

  public function paygRegistration(Request $request) {
    return view('payg-registration', [
      'merchantAccount' => \Config::get('adyen.posMerchantAccount'),
      'clientKey' => \Config::get('adyen.clientKey')
    ]);
  }

  public function paygInterface(Request $request) {
    return view('payg-interface', [
      'merchantAccount' => \Config::get('adyen.posMerchantAccount'),
      'clientKey' => \Config::get('adyen.clientKey')
    ]);
  }

  public function hotelCheckin(Request $request) {
    return view('hotel-checkin', [
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

  public function saasSubscriptions(Request $request) {
    return view('saas-subscriptions', [
      'merchantAccount' => \Config::get('adyen.ecomMerchantAccount'),
      'clientKey' => \Config::get('adyen.clientKey'),
      'paypalID' => \Config::get('adyen.paypalID')
    ]);
  }

  public function afpOnboarding(Request $request) {
    return view('afp-onboarding');
  }
}
