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

$status = '';

$sql = "SELECT `BlogAdd`,`BlogEdit`,`BlogDelete`,`PhotoUpload`,`VideoUpload`,`EditProfile` FROM `agencyemailsettings` WHERE `AgencyID` =" . $Profile_id;
$email_settings = mysql_query($sql);

if (mysql_num_rows($email_settings) > 0) {
	while ($row = mysql_fetch_array($email_settings, MYSQLI_ASSOC)) {
		$data = array(
			'status' => 'success',
			'msg' => 'Found the agency settings',
			'BlogAdd' => $row['BlogAdd'],
			'BlogEdit' => $row['BlogEdit'],
			'BlogDelete' => $row['BlogDelete'],
			'PhotoUpload' => $row['PhotoUpload'],
			'VideoUpload' => $row['VideoUpload'],
			'EditProfile' => $row['EditProfile'],
		);
	}
} else {
	$data = array(
		'status' => 'failure',
		'msg' => 'No results found',
	);
}

echo json_encode($data);
