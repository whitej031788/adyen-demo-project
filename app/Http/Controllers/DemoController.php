<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class DemoController extends Controller {
  public function view() {
    return view('create-demo.index', [
      'editMode' => 'false'
    ]);
  }

  public function viewManual() {
    return view('create-demo', [
      'editMode' => 'false'
    ]);
  }

  public function edit(Request $request) {
    if (!$request->session()->get('demo_session')) {
      return redirect('/create-demo');
    }

    return view('create-demo', [
      'editMode' => 'true'
    ]);
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
        'merchantLogoUrl' => 'nullable|url'
      ]);

      $params = $request->all();

      // Check if they uploaded a screenshot
      if ($request->hasFile('checkoutScreenshot')) {
        if ($request->file('checkoutScreenshot')->isValid()) {
          $validatedScreenshot = $request->validate([
            'checkoutScreenshot' => 'mimes:jpeg,png|max:1014',
          ]);
          $storeAsName = $request->checkoutScreenshot->hashName();
          Storage::disk('webpublic')->put("/screenshots/", $request->checkoutScreenshot);
          $screenshotUrl = "/uploads/screenshots/" . $storeAsName;
          $params['screenshotUrl'] = $screenshotUrl;
          unset($params['checkoutScreenshot']);
        }
      }
      // Once we have validated all required fields, lets just put the demo settings into a JSON object
      // this will be easy to manage both server side and client side
    }

    // This token is not a part of the demo session, Laravel CSFR token, don't store it
    if (isset($params["_token"])) {
      unset($params["_token"]);
    }

    // See if there is already a session, to get the screenshot from it
    if ($request->session()->get('demo_session')) {
      if (property_exists(json_decode($request->session()->get('demo_session')), 'screenshotUrl')) {
        $params['screenshotUrl'] = json_decode($request->session()->get('demo_session'))->screenshotUrl;
      }
    }

    // Add a random shopperReference if tokenization is enabled, as people might not know to log in
    if (isset($params['enableEcom_enableTokenization']) && $params['enableEcom_enableTokenization'] === "on") {
      $params['shopperReference'] = $this->generateRandomString(12);
    }

    $request->session()->put('demo_session', json_encode($params));

    $configJson = json_encode($params);
    Storage::disk('local')->put('public/demos/' . $params['merchantName'] . '.json', $configJson);

    if ($request->has("configFile")) {
      return redirect('/edit-demo');
    } else {
      return redirect('/');
    }
  }

  public function delete(Request $request) {
    $request->session()->forget('demo_session');
    return redirect('/create-demo');
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
}
