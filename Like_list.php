<?php

require_once( 'inc/header.inc.php' );

$uid = $_POST['userid'];
$Agenid = $_POST['Agencyid'];

mysql_query("INSERT INTO Like_list (LikedBy, AgencyLike)
VALUES ($Agenid, $uid)");



?>