
<?php

require_once __DIR__ . '/Config.php';

try{
   $authentication = Config::getAuthentication();
  //$authentication = array('adyenConfig'=>'apiKey',);
  $url = Config::getPaymentLinks();
  //$url = '/api/adyen/generateAndSendPaymentLink'
  $merchantName = 'Test';
  $date = new DateTime();
  $today = $date->format("Y-m-d");
  $dt2 = new DateTime("+1 month");
  $futureDate = $dt2->format("Y-m-d");
  $request = array (
    'reference' => 'PayByLink_Payment' . (string)$date->getTimestamp(),
    //'merchantAccount'=> array('adyenConfig'=>'merchantAccount',),
    'merchantAccount' => $authentication['merchantAccount'],
    //'themeId' => "73a795a3-ad75-4c52-8e53-a97909c8f5ad",
    'amount' =>
    array (
      'value' => 0,
      'currency' => 'GBP',
    ),
    'shopperName' =>
    array (
      'firstName' => 'Test',
      'lastName' => 'Name',
    ),
    'description'=> 'Item Description',
    'countryCode' =>  'GB',
    'shopperLocale' => 'en-GB',
    'shopperReference' => $_POST['email'],
    'shopperEmail' => $_POST['email'],
    'storePaymentMethod' =>'true',
    'storePaymentMethodMode' => 'askForConsent',
    'recurringProcessingModel' =>'UnscheduledCardOnFile',
    'returnUrl'=>'http://localhost:8000',
    'reusable' =>'false',
    //'expiresAt'=>$futureDate,
    //'expiresAt'=>'2022-09-01T00:00:00Z',

    'billingAddress'=> array (
    'houseNumberOrName'=> '1',
    'street'=> 'N/A',
    'city'=> 'Test',
    'stateOrProvince'=> '',
    'postalCode'=> '12345',
    'country'=> 'GB'
    ),

    'deliveryAddress'=> array (
    'houseNumberOrName'=> '1',
    'street'=> 'N/A',
    'city'=> 'Test',
    'stateOrProvince'=> '',
    'postalCode'=> '12345',
    'country'=> 'GB'
    ),

    'lineItems'=> array (
     array (
    'amountExcludingTax'=>18000,
    'description'=>'Item Description',
    'id'=>'Item ID',
    'quantity'=>1,
    'taxAmount'=>0,
    'taxCategory'=>'None',
    'taxPercentage'=>0,
          )
        )
  );

  $request['paymentMethod']['storeDetails'] = $_POST['storePaymentMethod'];
  $data = json_encode($request);

  $curlAPICall = curl_init();
  curl_setopt($curlAPICall, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($curlAPICall, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curlAPICall, CURLOPT_POSTFIELDS, $data);
  curl_setopt($curlAPICall, CURLOPT_URL, $url);
  curl_setopt($curlAPICall, CURLOPT_HTTPHEADER,
  array(
    'X-Api-Key: ' . $authentication['checkoutAPIkey'],
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data)
    )
  );
  $result = curl_exec($curlAPICall);
  if ($result === false){
    throw new Exception(curl_error($curlAPICall), curl_errno($curlAPICall));
  }

  $payment = json_decode($result, true);
  curl_close($curlAPICall);
} catch (Exception $e) {
  trigger_error(sprintf(
    'API call failed with error #%d, %s', $e->getCode(), $e->getMessage()
  ), E_USER_ERROR);
}

$array = json_decode($result, true);
$pbl = $array['url'];

    $url = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ9RzgnLyYkcXI0gU2Rx1ZqM2WBJWtCyPBaKQ&usqp=CAU";
    $returnpage = "http://127.0.0.1:8000";
    $to = $_POST["email"];
    $cc = "test";
    $bcc = "";
	$subject = $merchantName." Pay By Link";

	$message = "<html>
		<head>
			<title>Pay By Link</title>
		</head>
		<body>
			<b>This email contains a link to pay ... </b>
			<p>Please use the following link to complete the payment : </p>
			   <p><b>$pbl</b></p>
			      <table>
				     <tr>
					<td><br></td>
				</tr>
			</table>
		</body>
	</html>
	";

	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= 'From: paybylink@'.$merchantName.'.com' . "\r\n";
    //$header("Location: $returnpage" );
	mail($to,$subject,$message,$headers);
   //"Location: $returnpage";

?>
Email Sent!
