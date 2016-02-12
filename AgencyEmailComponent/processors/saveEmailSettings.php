<?php
/***********************************************************************
 * Name:    Sailaja S
 * Date:    2015/07/28
 * Purpose: Email configuration for Agency
 ***********************************************************************/
require_once '../../inc/header.inc.php';
require_once '../../inc/profiles.inc.php';
require_once '../../inc/utils.inc.php';

$Profile_id = getLoggedId();
$email_settings = $_POST['settings'];
$status = '';

$sql = "INSERT INTO agencyemailsettings (`AgencyID`,`BlogAdd`,`BlogEdit`,`BlogDelete`,`PhotoUpload`,`VideoUpload`,`EditProfile`) VALUES ('" . $Profile_id . "','" . $$email_settings[0] . "','" . $$email_settings[2] . "','" . $email_settings[4] . "','" . $email_settings[6] . "','" . $email_settings[8] . "','" . $email_settings[10] . "')
  ON DUPLICATE KEY UPDATE `BlogAdd`='" . $email_settings[0] . "',`BlogEdit`='" . $email_settings[2] . "',`BlogDelete`='" . $email_settings[4] . "',`PhotoUpload`='" . $email_settings[6] . "',`VideoUpload`='" . $email_settings[8] . "',`EditProfile`='" . $email_settings[10] . "'";

if (mysql_query($sql)) {
	$data = array(
		'status' => 'success',
		'msg' => 'Successfully saved the settings');
} else {
	$data = array(
		'status' => 'fail',
		'msg' => 'Could not save the settings');
}

echo json_encode($data);
