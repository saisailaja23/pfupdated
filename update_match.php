<html>
<head>
</head>
<body>
<?php
define('BX_PROFILE_PAGE', 1);
require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
bx_import('BxTemplProfileView');
bx_import('BxDolInstallerUtils');
?>
<?php

$loggedid = getLoggedId();

$agencysql = "SELECT id from `bx_groups_main`  WHERE `author_id` = '$loggedid'";
$agencyresult = mysql_query($agencysql);
$agencyrow = mysql_fetch_row($agencyresult);
$uid = $agencyrow[0];

echo $uid;
//if($_POST['submit']){
$records = $_POST['record'];
$matches = $_POST['match'];
//$match = $_POST['match'];

//$uid = $_POST['userid'];

if($records != '') {

$sql ="UPDATE Profiles SET matchrecords='$records' WHERE AdoptionAgency = ".$uid;
 
mysql_query($sql);
}

if($matches != '') {

$sql1 ="UPDATE Profiles SET maxmatch='$matches' WHERE AdoptionAgency = ".$uid;

  mysql_query($sql1);
}

?>
</body>
</html>

