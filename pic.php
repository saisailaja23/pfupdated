<?php 
require_once ('inc/header.inc.php');
require_once ('inc/profiles.inc.php');
require_once ('inc/utils.inc.php');
require_once ('inc/db.inc.php');
$logid = getLoggedId();
$queryString = "SELECT *
FROM `Profiles`
WHERE id =$logid";
$query = mysql_query($queryString);
$row = mysql_fetch_array($query);
$pic = $row['Avatar'];
header('Content-Type: application/json');
if($pic == 0) {
    echo json_encode(array(
     "pic" => "templates/tmpl_par/images/pf-blank.jpg",
     "id" =>$logid
     ));
    }
else if(file_exists("modules/boonex/avatar/data/favourite/$pic.jpg") ){
    // echo "modules/boonex/avatar/data/favourite/$pic.jpg";
	
    echo json_encode(array(
     "pic" => "modules/boonex/avatar/data/favourite/$pic.jpg",
     "id" =>$logid
     ));

}
?>
