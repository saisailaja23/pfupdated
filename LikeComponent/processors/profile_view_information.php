<?php
/**
 * C:\Users\msi1308\AppData\Roaming\Sublime Text 2\Packages/PhpTidy/phptidy-sublime-buffer.php
 *
 * @author Aravind Buddha <aravind.buddha@mediaus.com>
 * @package default
 */


?>
 <?php
/*********************************************************************************
* Name:    Prashanth A
* Date:    02/11/2013
* Purpose: Gettting the family information
*********************************************************************************/
require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');

$logid = getLoggedId();
$cmdtuples = 1;
$profileDetails = getProfileInfo($logid);

if ($cmdtuples > 0) {
	echo json_encode(array(
			'status' => 'success',
			'Profiles_value' => array(
				'rows' => $logid,
                            'profile_typoe'=>$profileDetails['ProfileType']
			)
		));
}
else {
	echo json_encode(array(
			'status' => 'err',
			'response' => 'Could not read the data: ' . mssql_get_last_message()
		));
}

?>
