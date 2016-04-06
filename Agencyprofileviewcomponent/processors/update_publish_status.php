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
$Pub_status = $_POST['pubStatus'];
$updatePubpwd = $_POST['pubStatus'] ? $_POST['pubPwd'] : '';

if (!empty($Profile_id)) {
	$Profile_publishStatus_Change = "Update `Profiles` SET `publishStatus` = '" . $Pub_status . "'  where ID = '" . $Profile_id . "'";
	db_res($Profile_publishStatus_Change);
	$Profile_publishStatus_Change_draft = "Update `Profiles_draft` SET `publishStatus` = '" . $Pub_status . "' where ID = '" . $Profile_id . "'";
	db_res($Profile_publishStatus_Change_draft);

	$update = 1;
}
if ($update > 0) {
	echo json_encode(array(
		'status' => 'success',
		'sql_statement' => $Profile_publishStatus_Change,
	));
} else {
	echo json_encode(array(
		'status' => 'err',
		'response' => 'Could not read the data: ' . mssql_get_last_message(),
	));
}
