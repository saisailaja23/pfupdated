<?php

require_once( 'inc/header.inc.php' );

$cid = $_POST['userid'];

if($_POST['printid'] == "Yes") {

//mysql_query("INSERT INTO global (globalval)
//VALUES ('Yes')");

mysql_query("UPDATE Profiles SET globalval = 'Yes' where id = $cid ");




}


if($_POST['printid'] == "No") {


mysql_query("UPDATE Profiles SET globalval = 'No' where id = $cid");


}


?>