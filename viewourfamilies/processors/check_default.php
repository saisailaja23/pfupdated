<?php

require_once( '../../inc/header.inc.php' );

$id = $_GET['id'];
if($id==''){
	$id = $_COOKIE['memberID'];
}

$res = db_arr("SELECT show_contact FROM `Profiles` WHERE `ID` = $id");

$ret = array('data'=>$res['show_contact']);
echo json_encode($ret);

?>