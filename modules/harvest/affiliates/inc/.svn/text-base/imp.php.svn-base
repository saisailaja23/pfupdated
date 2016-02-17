<?php 
header('Content-Type: image/gif');
$sFile = realpath(__FILE__);
$sSearch = 'modules/harvest/affiliates/inc/imp.php';	
$sIncDir= str_replace($sSearch, '', $sFile);
define(BX_ROOT_DIR, $sIncDir);
require_once(BX_ROOT_DIR."inc/header.inc.php");
require_once(BX_ROOT_DIR."inc/design.inc.php");
$iAid = bx_get('aid');
$iBid = bx_get('b');
$iIp = $_SERVER['REMOTE_ADDR'];
if($iAid && $iBid){
	BxDolService::call('affiliates', 'track_impression', array($iAid, $iBid, $iIp));
}
readfile('tracker.gif');
exit;
?>
