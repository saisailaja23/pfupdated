<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'On');
/*********************************************************************************
 * Name:    Sailaja S
 * Date:    2015/06/12
 * Purpose: To get the AccessToken
 *********************************************************************************/

require_once '../../inc/header.inc.php';
require_once '../../inc/profiles.inc.php';
require_once '../../inc/db.inc.php';
require_once '../../inc/utils.inc.php';
require_once 'AccessToken.php';

$obj = new AccessToken();
//$accessToken = $obj->withID(52);
$accessToken = $obj->getAcessToken();

if ($accessToken) {
	echo json_encode(array(
		'status' => 'success',
		'response' => $accessToken,
	));
} else {
	echo json_encode(array(
		'status' => 'err',
		'response' => '',
	));
}
