<?php
/*********************************************************************************
 * Name:    Sailaja S
 * Date:    08/06/2015
 * Purpose: Update publish status and publish password in database
 *********************************************************************************/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../../inc/header.inc.php';
require_once '../../inc/profiles.inc.php';

$Profile_id = $_POST['ProfileId'];
$contact = $_POST['contact'];
$update = 0;

if (!empty($Profile_id)) {
	$AllowSetContact = "Update `Profiles` SET `allow_contact` = '" . $contact . "'  where ID = '" . $Profile_id . "'";
	db_res($AllowSetContact);
	$$AllowSetContact_draft = "Update `Profiles_draft` SET `allow_contact` = '" . $contact . "' where ID = '" . $Profile_id . "'";
	db_res($$AllowSetContact_draft);
	$update = 1;
}
if ($update > 0) {
	echo json_encode(array(
		'status' => 'success',
		'sql_statement' => $AllowSetContact,
	));
} else {
	echo json_encode(array(
		'status' => 'err',
		'response' => 'Could not read the data: ' . mssql_get_last_message(),
	));
}
