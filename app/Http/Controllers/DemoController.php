<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DemoController extends Controller
{
  public function view()
  {
    return view('create-demo');
  }

  public function create(Request $request)
  {
    $validatedData = $request->validate([
      'merchantName' => 'required',
      'merchantLogoUrl' => 'required|url'
    ]);

    // Once we have validated all required fields, lets just put the demo settings into a JSON object
    // this will be easy to manage both server side and client side
    $params = $request->all();
    $request->session()->put('demo_session', $params);

    return redirect('/');
  }

  public function delete(Request $request)
  {
    $request->session()->forget('demo_session');
    return redirect('/create-demo');
  }
}
