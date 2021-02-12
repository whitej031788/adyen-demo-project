<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class DemoController extends Controller {
  public function view() {
    return view('create-demo');
  }

  public function create(Request $request) {
    // If they upload a file, just use that; otherwise they created a new demo with form data
    if ($request->has("configFile")) {
      $jsonContent = $request->configFile->get();
      // Need to typecast to array, as that is what $request->all() returns
      $params = (array) json_decode($jsonContent);
    } else {
      $validatedData = $request->validate([
        'merchantName' => 'required',
        'merchantLogoUrl' => 'required|url'
      ]);
      // Once we have validated all required fields, lets just put the demo settings into a JSON object
      // this will be easy to manage both server side and client side
      $params = $request->all();
    }

    $request->session()->put('demo_session', $params);

    // This token is not a part of the demo session, Laravel CSFR token, don't store it
    if (isset($params["_token"])) {
      unset($params["_token"]);
    }

    $configJson = json_encode($params);
    Storage::disk('local')->put('demos/' . $params['merchantName'] . '.json', $configJson);

    return redirect('/');
  }

  public function delete(Request $request) {
    $request->session()->forget('demo_session');
    return redirect('/create-demo');
  }
}
