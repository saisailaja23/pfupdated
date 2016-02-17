<?php
/*********************************************************************************
* Name:    Eswar N
* Date:    02/07/2014
* Purpose: Checking user password validation
*********************************************************************************/
require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');


$Profile_id = getLoggedId();

$sSalt = genRndSalt();

$Profile_pass = $_POST['new_pass'];
$Old_pass = $_POST['old_password'];
if($Profile_pass!='')
	$Password = encryptUserPwd($Profile_pass, $sSalt);

$q = "select Salt  from  Profiles where ID = '" . $Profile_id . "'";
$row = mysql_query($q);
$result = mysql_fetch_array($row);
$OldPassword = encryptUserPwd($Old_pass, $result['Salt']);

$q = "select id  from  Profiles where Password = '" . $OldPassword . "'";
$row = mysql_query($q);
if(mysql_num_rows($row)>0){
		echo json_encode(array(
				'status' => 'success'
		));
}
else
{
	echo json_encode(array(
			'status' => 'err',
			'response' => 'Your old password was incorrect.'));
}
