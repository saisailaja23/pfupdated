<?php

$billing_name = $_POST["billing_name"];
$billing_address1 = $_POST["billing_address1"];
$billing_city = $_POST["billing_city"];
$billing_state = $_POST["billing_state"];
$billing_zipcode = $_POST["billing_zipcode"];
$billing_phonenumber = $_POST["billing_phonenumber"];
$billing_routingnumber = $_POST["billing_routingnumber"];
$billing_account_number = $_POST["billing_account_number"];
$billing_account_number_confirm = $_POST["billing_account_number_confirm"];
$signature_saved = $_POST["signature_saved"];
$signature_image_name_file = $_POST["signature_image_name_file"];
$invoice_id = $_POST["invoice_id"];
$timezone = $_POST["timezone"];
$currency = $_POST["currency"];
$invoice_totalpay = $_POST["invoice_totalpay"];
$customer_id = $_POST["customer_id"];


$payment_payload = $_POST["payment_payload"];
$application_url = $_POST["application_url"];
$save_on_database = $_POST["save_on_database"];
if($save_on_database = "1"){
	$cURL = curl_init( $application_url . 'processors/save_to_database.php');
	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
	$payload = array("gateway_vendor"	=> 'echeck');
	foreach($_POST as $key => $value) {
	  $payload[$key] = $value;
	}
    curl_setopt($cURL, CURLOPT_POST, true);
    curl_setopt($cURL, CURLOPT_POSTFIELDS, $payload);
	$result = curl_exec($cURL);
	$HTTPResponse = curl_getinfo($cURL, CURLINFO_HTTP_CODE);
	curl_close($cURL);
	if ($HTTPResponse == '404') {
		$arr = array('status' => 'err', 'response' => 'The PHP processor for saving on the database was not found');
		die( json_encode($arr) );
	}
	if ($result != 'saved') {
		$arr = array('status' => 'err', 'response' => 'There was an error when saving on the database');
		die( json_encode($arr) );
	}
}
// do something here
if (strlen($billing_name) > 0) {
	// do something here
	$arr = array('status' => 'success', 'response' => 'Paid by e-check');
	echo json_encode($arr);
}
else{
	// do something here
	$arr = array('status' => 'err', 'response' => 'Not paid');
	echo json_encode($arr);
}
?>
