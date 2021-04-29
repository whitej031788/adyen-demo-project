<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdyenAFPController extends Controller
{
  public function __construct() {
    $this->platformsApiKey = (\Config::get('adyen.platformsApiKey'));
    $this->platformApiVersion = (\Config::get('adyen.platformApiVersion'));
    $this->root_domain = "https://cal-test.adyen.com/cal/services";
  }

  public function createAccountHolder(Request $request) {
    $params = $request->all();
    $result = $this->makeAFPRequest("{$this->root_domain}/Account/{$this->platformApiVersion}/createAccountHolder", $params);
    return response()->json($result);
  }

  public function updateAccountHolder(Request $request) {
    $params = $request->all();
    $result = $this->makeAFPRequest("{$this->root_domain}/Account/{$this->platformApiVersion}/updateAccountHolder", $params);
    return response()->json($result);
  }

  public function getOnboardingUrl(Request $request) {
    $params = $request->all();
    $result = $this->makeAFPRequest("{$this->root_domain}/Hop/{$this->platformApiVersion}/getOnboardingUrl", $params);
    return response()->json($result);
  }

  private function makeAFPRequest($url, $params) {
      $fields_string = json_encode($params);
      $options = array(
          'http' => array(
              'header'  => "Content-type: application/json\r\nX-API-Key: {$this->platformsApiKey}",
              'method'  => 'POST',
              'content' => $fields_string
          )
      );
      $context  = stream_context_create($options);
      $result = file_get_contents($url, false, $context);
      return json_decode($result);
  }
}
