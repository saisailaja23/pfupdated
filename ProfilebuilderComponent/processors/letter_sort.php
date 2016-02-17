<?php

require_once '../../inc/header.inc.php';
require_once '../../inc/profiles.inc.php';
header( "Content-type:application/json" );

echo $logid = ( $_GET['id']!='undefined' )?$_GET['id']:getLoggedId();

if(isset($_REQUEST["sort_obj"])){
	$profile_ID =  $_REQUEST['profile_ID'];

    $sort_array = json_decode($_REQUEST["sort_obj"], true);
	foreach ($sort_array as $label => $order_by) {
	    $check = "SELECT * FROM `letters_sort` WHERE `label`= '$label' AND `profile_id`=$profile_ID";
	    
	    if(mysql_num_rows(mysql_query($check))){
			$sql = "UPDATE `letters_sort` SET `label`='$label',`order_by`=$order_by, `profile_id`=$profile_ID WHERE `label`= '$label' AND `profile_id`=$profile_ID";
	    }else{
			$sql = "INSERT INTO `letters_sort` (`label`, `order_by`, `profile_id`) VALUES ('$label', $order_by,$profile_ID)";

	    }
	    mysql_query($sql) or die(mysql_error());
	}
}
