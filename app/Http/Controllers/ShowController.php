<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShowController extends Controller
{
  public function index(Request $request) {
    return view('home', ['merchantAccount' => \Config::get('adyen.ecomMerchantAccount')]);
  }

  public function customCallCenter(Request $request) {
    return view('custom-call-center', ['merchantAccount' => \Config::get('adyen.motoMerchantAccount')]);
  }

  public function standardEcom(Request $request) {
    return view('standard-ecom', ['merchantAccount' => \Config::get('adyen.ecomMerchantAccount')]);
  }
}
