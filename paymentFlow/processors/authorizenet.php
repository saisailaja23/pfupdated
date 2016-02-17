<?php
include 'settings.php';
require_once 'lib/Authorizenet/AuthorizeNet.php'; // Make sure this path is correct.

$card_number = $_POST['card_number'];
$card_expirationdate = $_POST['card_expirationdate'];
$card_securitycode = $_POST['card_securitycode'];
$invoice_id = $_POST['invoice_id'];
$invoice_totalpay = $_POST['invoice_totalpay'];
$pay_for_desc = $_POST['pay_for_desc'];
$customer_id = $_POST['customer_id'];
$billing_firstname = $_POST['billing_firstname'];
$billing_lastname = $_POST['billing_lastname'];
$billing_address1 = $_POST['billing_address1'];
$billing_city = $_POST['billing_city'];
$billing_state = $_POST["billing_state"];
$billing_zipcode = $_POST['billing_zipcode'];
$billing_country = $_POST['billing_country'];
$billing_phonenumber = $_POST['billing_phonenumber'];
$billing_companyname = $_POST['billing_companyname'];

// define("AUTHORIZENET_SANDBOX", false);
// defined('AUTHORIZENET_LOG_FILE', "paymentFlowlog.txt");

if ($mode == "test") {
	define("AUTHORIZENET_SANDBOX", true);
} else {
	define("AUTHORIZENET_SANDBOX", false);
}

define("AUTHORIZENET_API_LOGIN_ID", $Authorize[$mode]["client_id"]);
define("AUTHORIZENET_TRANSACTION_KEY", $Authorize[$mode]["secret"]);

$transaction = new AuthorizeNetAIM;

// $transaction = new AuthorizeNetAIM($Authorize[$mode]["client_id"], $Authorize[$mode]["secret"]);
// $transaction->setSandbox(true);

//card info
$transaction->card_num = $card_number;
$transaction->exp_date = $card_expirationdate;
$transaction->card_code = $card_securitycode;
//invoice info auto
$transaction->invoice_num = $invoice_id;
//invoice capture
$transaction->amount = $invoice_totalpay;
$transaction->description = $pay_for_desc;
//billing addrsss auto
$transaction->cust_id = $customer_id;
$transaction->customer_ip = $_SERVER['REMOTE_ADDR'];
//billing addrsss need to capture
$transaction->first_name = $billing_firstname;
$transaction->last_name = $billing_lastname;
$transaction->address = $billing_address1;
$transaction->city = $billing_city;
$transaction->state = $billing_state;
$transaction->zip = $billing_zipcode;
$transaction->country = $billing_country;
$transaction->phone = $billing_phonenumber;
//$transaction->fax="8019734742";
//$transaction->email="aravind.buddha@mediaus.com";
$transaction->company = $billing_companyname;

$response = $transaction->authorizeAndCapture();

$result = array();

$result['avs_code'] = $response->avs_response . ' - ' . get_avs_msg($response->avs_response);
$result['cavv_response'] = $response->cavv_response;
$result['cvv2_response'] = $response->card_code_response . ' - ' . get_cvv2_message($response->card_code_response);

if ($response->approved) {
	$result['status'] = 'success';
	$result['authorization'] = $response->authorization_code;
	$result['response'] = "Transaction Success";
	$result['data'] = date('Y-m-d');
	$result['time'] = date('G:i:s');
	echo json_encode($result);
} else {
	$result['status'] = 'error';
	$result['authorization'] = $response->authorization_code;
	$result['response'] = $response->error_message;
	$result['response_reason_text'] = $response->response_reason_text;
	echo json_encode($result);
}

function get_avs_msg($letter) {
	switch ($letter) {
		case "A":
			return "The street address matches, but the 5-digit ZIP code does not";
			break;
		case "B":
			return "Address information was not submitted in the transaction information, so AVS check could not be performed";
			break;
		case "E":
			return "The AVS data provided is invalid, or AVS is not allowed for the card type submitted";
			break;
		case "G":
			return "The credit card issuing bank is of non-U.S. origin and does not support AVS";
		case "N":
			return "Neither the street address nor the 5-digit ZIP code matches the address and ZIP code on file for the card";
		case "P":
			return "AVS is not applicable for this transaction";
		case "R":
			return "AVS was unavailable at the time the transaction was processed. Retry transaction";
		case "S":
			return "The U.S. card issuing bank does not support AVS";
		case "U":
			return "Address information is not available for the customer's credit card";
		case "W":
			return "The 9-digit ZIP code matches, but the street address does not match";
		case "Y":
			return "The street address and the first 5 digits of the ZIP code match perfectly";
		case "Z":
			return "The first 5 digits of the ZIP code matches, but the street address does not match";
	}

}

function get_cvv2_message($letter) {
	$msgs = array(
		" " => 'Check failed either because CVV2 value entered is incorrect or no CVV2 value was entered',
		"" => "Check failed either because CVV2 value entered is incorrect or no CVV2 value was entered",
		"M" => "Check failed either because CVV2 value entered is incorrect or no CVV2 value was entered",
		"N" => "No Match",
		"P" => "Not processed, CVV2 could not be verified",
		"S" => "Issuer indicates that CVV2 should be present on the card, but no CVV2 data was entered with transaction",
		"U" => "Issuer does not support CVV2",
	);
	return $msgs[$letter];
}

function get_cavv_message($letter) {
	$msg = array(
		"" => "",
		0 => 'CAVV not validated because erroneous data was submitted',
		1 => 'CAVV not validated because erroneous data was submitted',
		2 => 'CAVV passed validation',
		3 => 'CAVV validation could not be performed; issuer attempt incomplete',
		4 => 'CAVV validation could not be performed; issuer system error',
		5 => 'Reserved for future use',
		6 => 'Reserved for future use',
		7 => 'CAVV attempt – failed validation – issuer available (U.S.-issued card/non-U.S acquirer)',
		8 => 'CAVV attempt – passed validation – issuer available (U.S.-issued card/non-U.S. acquirer)',
		9 => 'CAVV attempt – failed validation – issuer unavailable (U.S.-issued card/non-U.S. acquirer)',
		"A" => 'CAVV attempt – passed validation – issuer unavailable (U.S.-issued card/non-U.S. acquirer)',
		"B" => 'CAVV attempt – passed validation – issuer unavailable (U.S.-issued card/non-U.S. acquirer)',
	);
	return $msg[$letter];
}
