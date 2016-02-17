<?php
/*********************************************************************************
* Name:    Prashanth A
* Date:    12/12/2013
* Purpose: Listing the families liked by birth mother
*********************************************************************************/
require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');
//require_once ('../../inc/classes/BxDolTemplate.php');

$logid = getLoggedId();

$searchvalues = $_REQUEST['sortvalue'];
$searchtype = $_REQUEST['type'];

//echo $searchvalues.$searchtype;

if($searchtype == 'Familysize' && $_REQUEST['sortvalue'] == '3 ') {
   $searchvalues == '3+';
}

    switch ($searchtype) {
    case 'Region':
    $AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE `Profiles`.`Status` = 'Active' and Profiles.Region ='$searchvalues' and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'   AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`)";  
    break;
    case 'Religion':
    $AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and (Profiles.Religion ='$searchvalues' OR Profiles.faith ='$searchvalues') and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'   AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`)";  
    break;
    case 'Familysize':
   if($_REQUEST['sortvalue'] == '3 ') {  
    $AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and Profiles.noofchildren >= $searchvalues and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`)";  
 
    }
    else {
    $AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and Profiles.noofchildren=$searchvalues and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`)";  

    }
    break;
    case 'Sortby':
    $AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active'  and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) ORDER BY Profiles.DateReg DESC";  
    break;
    case 'ethnicity':
    $AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and FIND_IN_SET( '$searchvalues' ,`ChildEthnicity` ) and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) ";  
    break;
    default:
    $AgencyLike_List = "SELECT `Profiles`. `ID`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0' AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) ORDER BY `Profiles`.`DateReg` DESC  ";
    break;

   // }  

 // }
}
$agency_query = mysql_query($AgencyLike_List);
$cmdtuples = mysql_num_rows($agency_query);
{
echo json_encode(array(
'status' => 'There are no results to display.',
'family_count' => $cmdtuples
)); 

}

?>