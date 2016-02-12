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
$member = getProfileInfo($logid);

$searchvalues = $_REQUEST['sortvalue'];
$searchitemvalue = $_REQUEST['searchvalue'];
//echo $searchitemvalue;
$from = ($_REQUEST['posStart'] == '')?0:$_REQUEST['posStart'];
$count = ($_REQUEST['count'] == '')?15:$_REQUEST['count'];

if($searchvalues == 'Active') {
 $Agencydetails = "SELECT SQL_CALC_FOUND_ROWS `p`.* , IF(ISNULL(`tl`.`Name`),'', `tl`.`Name`) AS `ml_name`,tlm.IDLevel, YEAR(tlm.DateStarts) as DateStarts as DateStarts FROM `Profiles` AS `p` INNER JOIN `bx_groups_fans` AS `f` ON (`f`.`id_entry` = '$member[AdoptionAgency]' AND `f`.`id_profile` = `p`.`ID` AND `p`.`ProfileType` != 8 AND `f`.`confirmed` = 1 AND `p`.`Status` = 'Active') LEFT JOIN `sys_acl_levels_members` AS `tlm` ON `p`.`ID`=`tlm`.`IDMember` AND `tlm`.`DateStarts` < NOW() AND (`tlm`.`DateExpires`>NOW() || ISNULL(`tlm`.`DateExpires`)) LEFT JOIN `sys_acl_levels` AS `tl` ON `tlm`.`IDLevel`=`tl`.`ID` GROUP BY `p`. `ID`  Order by DateLastLogin Desc limit $from , $count ";
}
else if($searchvalues == 'Inactive') {
 $Agencydetails = "SELECT SQL_CALC_FOUND_ROWS `p`.* , IF(ISNULL(`tl`.`Name`),'', `tl`.`Name`) AS `ml_name`,tlm.IDLevel, YEAR(tlm.DateStarts) as DateStarts FROM `Profiles` AS `p` INNER JOIN `bx_groups_fans` AS `f` ON (`f`.`id_entry` = '$member[AdoptionAgency]' AND `f`.`id_profile` = `p`.`ID` AND `p`.`ProfileType` != 8 AND `f`.`confirmed` = 1 AND `p`.`Status` = 'Inactive') LEFT JOIN `sys_acl_levels_members` AS `tlm` ON `p`.`ID`=`tlm`.`IDMember` AND `tlm`.`DateStarts` < NOW() AND (`tlm`.`DateExpires`>NOW() || ISNULL(`tlm`.`DateExpires`)) LEFT JOIN `sys_acl_levels` AS `tl` ON `tlm`.`IDLevel`=`tl`.`ID` GROUP BY `p`. `ID`  Order by DateLastLogin Desc limit $from , $count ";
}
else if($searchvalues == 'Approval') {
  $Agencydetails = "SELECT SQL_CALC_FOUND_ROWS `p`.* , IF(ISNULL(`tl`.`Name`),'', `tl`.`Name`) AS `ml_name`,tlm.IDLevel, YEAR(tlm.DateStarts) as DateStarts FROM `Profiles` AS `p` INNER JOIN `bx_groups_fans` AS `f` ON (`f`.`id_entry` = '$member[AdoptionAgency]' AND `f`.`id_profile` = `p`.`ID` AND `p`.`ProfileType` != 8 AND `f`.`confirmed` = 1 AND `p`.`Status` = 'Approval') LEFT JOIN `sys_acl_levels_members` AS `tlm` ON `p`.`ID`=`tlm`.`IDMember` AND `tlm`.`DateStarts` < NOW() AND (`tlm`.`DateExpires`>NOW() || ISNULL(`tlm`.`DateExpires`)) LEFT JOIN `sys_acl_levels` AS `tl` ON `tlm`.`IDLevel`=`tl`.`ID` GROUP BY `p`. `ID`  Order by DateLastLogin Desc limit $from , $count ";
}
else if($searchvalues == 'Recent') {    
$Agencydetails = "SELECT SQL_CALC_FOUND_ROWS `p`.* , IF(ISNULL(`tl`.`Name`),'', `tl`.`Name`) AS `ml_name`,tlm.IDLevel, YEAR(tlm.DateStarts) as DateStarts FROM `Profiles` AS `p` INNER JOIN `bx_groups_fans` AS `f` ON (`f`.`id_entry` = '$member[AdoptionAgency]' AND `f`.`id_profile` = `p`.`ID` AND `p`.`ProfileType` != 8 AND `f`.`confirmed` = 1 AND `p`.`Status` <> 'Rejected' AND `p`.`Status` <> 'Unconfirmed') LEFT JOIN `sys_acl_levels_members` AS `tlm` ON `p`.`ID`=`tlm`.`IDMember` AND `tlm`.`DateStarts` < NOW() AND (`tlm`.`DateExpires`>NOW() || ISNULL(`tlm`.`DateExpires`)) LEFT JOIN `sys_acl_levels` AS `tl` ON `tlm`.`IDLevel`=`tl`.`ID` GROUP BY `p`. `ID`  Order by DateReg Desc limit $from , $count ";
}
else if($searchvalues == 'Matched') {
$Agencydetails = "SELECT SQL_CALC_FOUND_ROWS `p`.* , IF(ISNULL(`tl`.`Name`),'', `tl`.`Name`) AS `ml_name`,tlm.IDLevel, YEAR(tlm.DateStarts) as DateStarts FROM `Profiles` AS `p` INNER JOIN `bx_groups_fans` AS `f` ON (`f`.`id_entry` = '$member[AdoptionAgency]' AND `f`.`id_profile` = `p`.`ID` AND `p`.`ProfileType` != 8 AND `f`.`confirmed` = 1 AND `p`.`Status` = 'Active') LEFT JOIN `sys_acl_levels_members` AS `tlm` ON `p`.`ID`=`tlm`.`IDMember` AND `tlm`.`DateStarts` < NOW() AND (`tlm`.`DateExpires`>NOW() || ISNULL(`tlm`.`DateExpires`)) INNER JOIN watermarkimages  ON watermarkimages.author_id = p.ID  and watermarkimages.status = 'Matched'
LEFT JOIN `sys_acl_levels` AS `tl` ON `tlm`.`IDLevel`=`tl`.`ID` GROUP BY `p`. `ID`  Order by DateLastLogin Desc limit $from , $count ";
}
else if($searchvalues == 'Placed') {
$Agencydetails = "SELECT SQL_CALC_FOUND_ROWS `p`.* , IF(ISNULL(`tl`.`Name`),'', `tl`.`Name`) AS `ml_name`,tlm.IDLevel, YEAR(tlm.DateStarts) as DateStarts FROM `Profiles` AS `p` INNER JOIN `bx_groups_fans` AS `f` ON (`f`.`id_entry` = '$member[AdoptionAgency]' AND `f`.`id_profile` = `p`.`ID` AND `p`.`ProfileType` != 8 AND `f`.`confirmed` = 1 AND `p`.`Status` = 'Active') LEFT JOIN `sys_acl_levels_members` AS `tlm` ON `p`.`ID`=`tlm`.`IDMember` AND `tlm`.`DateStarts` < NOW() AND (`tlm`.`DateExpires`>NOW() || ISNULL(`tlm`.`DateExpires`)) INNER JOIN watermarkimages  ON watermarkimages.author_id = p.ID  and watermarkimages.status = 'Placed'
LEFT JOIN `sys_acl_levels` AS `tl` ON `tlm`.`IDLevel`=`tl`.`ID` GROUP BY `p`. `ID`  Order by DateLastLogin Desc limit $from , $count ";
}
else if($searchvalues == 'Private') {
$Agencydetails = "SELECT SQL_CALC_FOUND_ROWS  `p` . * , IF( ISNULL(  `tl`.`Name` ) ,  '',  `tl`.`Name` ) AS  `ml_name` ,tlm.IDLevel, YEAR(tlm.DateStarts) as DateStarts 
FROM  `Profiles` AS  `p` 
INNER JOIN  `bx_groups_fans` AS  `f` ON (  `f`.`id_entry` =  '$member[AdoptionAgency]'
AND  `f`.`id_profile` =  `p`.`ID` 
AND  `p`.`ProfileType` !=8
AND  `f`.`confirmed` =1
AND  `p`.`publishStatus` =0 ) 
LEFT JOIN  `sys_acl_levels_members` AS  `tlm` ON  `p`.`ID` =  `tlm`.`IDMember` 
AND  `tlm`.`DateStarts` < NOW( ) 
AND (
`tlm`.`DateExpires` > NOW( ) || ISNULL(  `tlm`.`DateExpires` )
)
LEFT JOIN  `sys_acl_levels` AS  `tl` ON  `tlm`.`IDLevel` =  `tl`.`ID` 
GROUP BY  `p`.`ID` 
ORDER BY DateLastLogin DESC LIMIT $from , $count ";
}
else if($searchitemvalue){    
$Agencydetails = "
SELECT SQL_CALC_FOUND_ROWS p . * , IF( ISNULL( `tl`.`Name` ) , '', `tl`.`Name` ) AS `ml_name` ,tlm.IDLevel, YEAR(tlm.DateStarts) as DateStarts 
FROM `Profiles` AS `p`
INNER JOIN (SELECT CASE (pf.Couple =0 OR pf.couple > pf.ID) 
WHEN 1
THEN pf.ID
ELSE pf.Couple
END as ID FROM Profiles AS pf
WHERE `pf`.`FirstName` LIKE '%" . $searchitemvalue . "%'
OR `pf`.`LastName` LIKE '%" . $searchitemvalue . "%') T ON p.ID=T.ID
INNER JOIN `bx_groups_fans` AS `f` ON ( `f`.`id_entry` = '$member[AdoptionAgency]'
AND `f`.`id_profile` = `p`.`ID`
AND `p`.`ProfileType` !=8
AND `f`.`confirmed` =1
AND `p`.`Status` <> 'Rejected'
AND `p`.`Status` <> 'Unconfirmed' )
LEFT JOIN `sys_acl_levels_members` AS `tlm` ON `p`.`ID` = `tlm`.`IDMember`
AND `tlm`.`DateStarts` < NOW( )
AND (
`tlm`.`DateExpires` > NOW( ) || ISNULL( `tlm`.`DateExpires` )
)
LEFT JOIN `sys_acl_levels` AS `tl` ON `tlm`.`IDLevel` = `tl`.`ID`
WHERE (
p.Couple =0
OR p.couple > p.ID
)
GROUP BY `p`.`ID`
ORDER BY p.DateLastLogin DESC
limit $from , $count ";    
}
else {
$Agencydetails = "SELECT SQL_CALC_FOUND_ROWS `p`.* , IF(ISNULL(`tl`.`Name`),'', `tl`.`Name`) AS `ml_name`,tlm.IDLevel, YEAR(tlm.DateStarts) as DateStarts  FROM `Profiles` AS `p` INNER JOIN `bx_groups_fans` AS `f` ON (`f`.`id_entry` = '$member[AdoptionAgency]' AND `f`.`id_profile` = `p`.`ID` AND `p`.`ProfileType` != 8 AND `f`.`confirmed` = 1 AND `p`.`Status` <> 'Rejected' AND `p`.`Status` <> 'Unconfirmed') LEFT JOIN `sys_acl_levels_members` AS `tlm` ON `p`.`ID`=`tlm`.`IDMember` AND `tlm`.`DateStarts` < NOW() AND (`tlm`.`DateExpires`>NOW() || ISNULL(`tlm`.`DateExpires`)) LEFT JOIN `sys_acl_levels` AS `tl` ON `tlm`.`IDLevel`=`tl`.`ID` GROUP BY `p`. `ID`  Order by DateLastLogin Desc limit $from , $count ";
}

if($from == 0) {
    
if($searchvalues == 'Active') {
 $Agencycount = mysql_query("SELECT SQL_CALC_FOUND_ROWS `p`.* , IF(ISNULL(`tl`.`Name`),'', `tl`.`Name`) AS `ml_name`,tlm.IDLevel, YEAR(tlm.DateStarts) as DateStarts  FROM `Profiles` AS `p` INNER JOIN `bx_groups_fans` AS `f` ON (`f`.`id_entry` = '$member[AdoptionAgency]' AND `f`.`id_profile` = `p`.`ID` AND `p`.`ProfileType` != 8 AND `f`.`confirmed` = 1 AND `p`.`Status` = 'Active') LEFT JOIN `sys_acl_levels_members` AS `tlm` ON `p`.`ID`=`tlm`.`IDMember` AND `tlm`.`DateStarts` < NOW() AND (`tlm`.`DateExpires`>NOW() || ISNULL(`tlm`.`DateExpires`)) LEFT JOIN `sys_acl_levels` AS `tl` ON `tlm`.`IDLevel`=`tl`.`ID` GROUP BY `p`. `ID`  Order by DateLastLogin Desc");
}
else if($searchvalues == 'Inactive') {
 $Agencycount = mysql_query("SELECT SQL_CALC_FOUND_ROWS `p`.* , IF(ISNULL(`tl`.`Name`),'', `tl`.`Name`) AS `ml_name`,tlm.IDLevel, YEAR(tlm.DateStarts) as DateStarts  FROM `Profiles` AS `p` INNER JOIN `bx_groups_fans` AS `f` ON (`f`.`id_entry` = '$member[AdoptionAgency]' AND `f`.`id_profile` = `p`.`ID` AND `p`.`ProfileType` != 8 AND `f`.`confirmed` = 1 AND `p`.`Status` = 'Inactive') LEFT JOIN `sys_acl_levels_members` AS `tlm` ON `p`.`ID`=`tlm`.`IDMember` AND `tlm`.`DateStarts` < NOW() AND (`tlm`.`DateExpires`>NOW() || ISNULL(`tlm`.`DateExpires`)) LEFT JOIN `sys_acl_levels` AS `tl` ON `tlm`.`IDLevel`=`tl`.`ID` GROUP BY `p`. `ID`  Order by DateLastLogin Desc");
}
else if($searchvalues == 'Approval') {
 $Agencycount = mysql_query("SELECT SQL_CALC_FOUND_ROWS `p`.* , IF(ISNULL(`tl`.`Name`),'', `tl`.`Name`) AS `ml_name`,tlm.IDLevel, YEAR(tlm.DateStarts) as DateStarts  FROM `Profiles` AS `p` INNER JOIN `bx_groups_fans` AS `f` ON (`f`.`id_entry` = '$member[AdoptionAgency]' AND `f`.`id_profile` = `p`.`ID` AND `p`.`ProfileType` != 8 AND `f`.`confirmed` = 1 AND `p`.`Status` = 'Approval') LEFT JOIN `sys_acl_levels_members` AS `tlm` ON `p`.`ID`=`tlm`.`IDMember` AND `tlm`.`DateStarts` < NOW() AND (`tlm`.`DateExpires`>NOW() || ISNULL(`tlm`.`DateExpires`)) LEFT JOIN `sys_acl_levels` AS `tl` ON `tlm`.`IDLevel`=`tl`.`ID` GROUP BY `p`. `ID`  Order by DateLastLogin Desc");
} 
else if($searchvalues == 'Recent') {
$Agencycount = mysql_query("SELECT SQL_CALC_FOUND_ROWS `p`.* , IF(ISNULL(`tl`.`Name`),'', `tl`.`Name`) AS `ml_name`,tlm.IDLevel, YEAR(tlm.DateStarts) as DateStarts  FROM `Profiles` AS `p` INNER JOIN `bx_groups_fans` AS `f` ON (`f`.`id_entry` = '$member[AdoptionAgency]' AND `f`.`id_profile` = `p`.`ID` AND `p`.`ProfileType` != 8 AND `f`.`confirmed` = 1 AND `p`.`Status` <> 'Rejected' AND `p`.`Status` <> 'Unconfirmed') LEFT JOIN `sys_acl_levels_members` AS `tlm` ON `p`.`ID`=`tlm`.`IDMember` AND `tlm`.`DateStarts` < NOW() AND (`tlm`.`DateExpires`>NOW() || ISNULL(`tlm`.`DateExpires`)) LEFT JOIN `sys_acl_levels` AS `tl` ON `tlm`.`IDLevel`=`tl`.`ID` GROUP BY `p`. `ID`  Order by DateReg Desc");
}
else if($searchvalues == 'Matched') {
$Agencydetails = "SELECT SQL_CALC_FOUND_ROWS `p`.* , IF(ISNULL(`tl`.`Name`),'', `tl`.`Name`) AS `ml_name`,tlm.IDLevel, YEAR(tlm.DateStarts) as DateStarts  FROM `Profiles` AS `p` INNER JOIN `bx_groups_fans` AS `f` ON (`f`.`id_entry` = '$member[AdoptionAgency]' AND `f`.`id_profile` = `p`.`ID` AND `p`.`ProfileType` != 8 AND `f`.`confirmed` = 1 AND `p`.`Status` = 'Active') LEFT JOIN `sys_acl_levels_members` AS `tlm` ON `p`.`ID`=`tlm`.`IDMember` AND `tlm`.`DateStarts` < NOW() AND (`tlm`.`DateExpires`>NOW() || ISNULL(`tlm`.`DateExpires`)) INNER JOIN watermarkimages  ON watermarkimages.author_id = p.ID  and watermarkimages.status = 'Matched'
LEFT JOIN `sys_acl_levels` AS `tl` ON `tlm`.`IDLevel`=`tl`.`ID` GROUP BY `p`. `ID`  Order by DateLastLogin Desc";
}
else if($searchvalues == 'Placed') {
$Agencydetails = "SELECT SQL_CALC_FOUND_ROWS `p`.* , IF(ISNULL(`tl`.`Name`),'', `tl`.`Name`) AS `ml_name`,tlm.IDLevel, YEAR(tlm.DateStarts) as DateStarts  FROM `Profiles` AS `p` INNER JOIN `bx_groups_fans` AS `f` ON (`f`.`id_entry` = '$member[AdoptionAgency]' AND `f`.`id_profile` = `p`.`ID` AND `p`.`ProfileType` != 8 AND `f`.`confirmed` = 1 AND `p`.`Status` = 'Active') LEFT JOIN `sys_acl_levels_members` AS `tlm` ON `p`.`ID`=`tlm`.`IDMember` AND `tlm`.`DateStarts` < NOW() AND (`tlm`.`DateExpires`>NOW() || ISNULL(`tlm`.`DateExpires`)) INNER JOIN watermarkimages  ON watermarkimages.author_id = p.ID  and watermarkimages.status = 'Placed'
LEFT JOIN `sys_acl_levels` AS `tl` ON `tlm`.`IDLevel`=`tl`.`ID` GROUP BY `p`. `ID`  Order by DateLastLogin Desc";
}
else if($searchitemvalue){
$Agencycount = mysql_query("
SELECT SQL_CALC_FOUND_ROWS p . * , IF( ISNULL( `tl`.`Name` ) , '', `tl`.`Name` ) AS `ml_name`,tlm.IDLevel, YEAR(tlm.DateStarts) as DateStarts 
FROM `Profiles` AS `p`
INNER JOIN (SELECT CASE (pf.Couple =0 OR pf.couple > pf.ID) 
WHEN 1
THEN pf.ID
ELSE pf.Couple
END as ID FROM Profiles AS pf
WHERE `pf`.`FirstName` LIKE '%" . $searchitemvalue . "%'
OR `pf`.`LastName` LIKE '%" . $searchitemvalue . "%') T ON p.ID=T.ID
INNER JOIN `bx_groups_fans` AS `f` ON ( `f`.`id_entry` = '$member[AdoptionAgency]'
AND `f`.`id_profile` = `p`.`ID`
AND `p`.`ProfileType` !=8
AND `f`.`confirmed` =1
AND `p`.`Status` <> 'Rejected'
AND `p`.`Status` <> 'Unconfirmed' )
LEFT JOIN `sys_acl_levels_members` AS `tlm` ON `p`.`ID` = `tlm`.`IDMember`
AND `tlm`.`DateStarts` < NOW( )
AND (
`tlm`.`DateExpires` > NOW( ) || ISNULL( `tlm`.`DateExpires` )
)
LEFT JOIN `sys_acl_levels` AS `tl` ON `tlm`.`IDLevel` = `tl`.`ID`
WHERE (
p.Couple =0
OR p.couple > p.ID
)
GROUP BY `p`.`ID`
ORDER BY p.DateLastLogin DESC");   
}
else {    
$Agencycount = mysql_query("SELECT SQL_CALC_FOUND_ROWS `p`.* , IF(ISNULL(`tl`.`Name`),'', `tl`.`Name`) AS `ml_name`,tlm.IDLevel, YEAR(tlm.DateStarts) as DateStarts  FROM `Profiles` AS `p` INNER JOIN `bx_groups_fans` AS `f` ON (`f`.`id_entry` = '$member[AdoptionAgency]' AND `f`.`id_profile` = `p`.`ID` AND `p`.`ProfileType` != 8 AND `f`.`confirmed` = 1 AND `p`.`Status` <> 'Rejected' AND `p`.`Status` <> 'Unconfirmed') LEFT JOIN `sys_acl_levels_members` AS `tlm` ON `p`.`ID`=`tlm`.`IDMember` AND `tlm`.`DateStarts` < NOW() AND (`tlm`.`DateExpires`>NOW() || ISNULL(`tlm`.`DateExpires`)) LEFT JOIN `sys_acl_levels` AS `tl` ON `tlm`.`IDLevel`=`tl`.`ID` GROUP BY `p`. `ID`  Order by DateLastLogin Desc");
}
$tount = mysql_num_rows($Agencycount);
}
 
$arrRows_agency_list1 = array();
header ("content-type: text/xml");
$xml = '<?xml version="1.0" encoding="UTF-8" ?>';
if($from > 0){
    $xml .= '<data pos="'.$from.'">';
}
else{
$xml .= '<data total_count="'.$tount.'">';
}
$count = $from;
$Agencydetailsquery= mysql_query($Agencydetails);
$cmdtuples = mysql_num_rows($Agencydetailsquery);
while (($row_agency = mysql_fetch_array($Agencydetailsquery, MYSQL_BOTH)))
{
 $rand = time() + rand(0,9);
 
$filename =  $dir['root'].'modules/boonex/avatar/data/favourite/'.$row_agency['Avatar'].'.jpg';
if($row_agency['Avatar'] != 0 && file_exists($filename)) {  
  
$sImage   =   $site['url'].'modules/boonex/avatar/data/favourite/'.$row_agency['Avatar'].'.jpg?tt'.$rand;
}
else {
$sImage   =   $site['url'].'templates/tmpl_par/images/pf-blank.jpg';  
}
/*
list($width, $height) = getimagesize("$sImage");
                if($width>100){
                    $per=(($width-100)/$width)*100;
                    $height =$height-(($height*$per)/100);
                    $width=100;
               }
                if($height>100){
                    $per=(($height-100)/$height)*100;
                    $width =$width-(($width*$per)/100);
                    $height=100;
                 }
                $margin_left = ($width < 100)?(100-$width)/2:0;
                $width = ($margin_left == 0)?100:$width;
                $margin_top = ($height < 100)?(100-$height)/2:0;
                $height = ($margin_top == 0)?100:$height;
*/
$profile_link = $site['url'].$row_agency['NickName'].'/about';

$photo = '<a href= "'.$profile_link.'" /><img style="width:151px; height:113px;margin-left:0;margin-top:0; background-color: #EDEDED;" src="' . $sImage . '"></a>';

$profile_id = $row_agency['ID'];
////$profile_Name = $row_agency['NickName'];
//
//if($profile_Name == $profile_Name.'(2)') {  
//$profile_ids = $row_agency['Couple'];  
//}
//else {
// $profile_ids = $row_agency['ID']; 
//    
//}

$Couplename = db_arr("SELECT `FirstName`,Age,Status FROM `Profiles` WHERE `Couple` = '$profile_id'");
$Couple_name = $Couplename[FirstName]; 
$Profile_firstname = $row_agency[FirstName];
$Profile_status = $row_agency[Status];

$match_status= db_arr("SELECT `status` FROM `watermarkimages` WHERE `author_id` = '$profile_id'");
$matchstatus = $match_status[status]; 
$profilenames = array();
if($Couple_name !='' ) {
$profilenames[] = $Profile_firstname;
$profilenames[] = $Couple_name;

sort($profilenames);
$profilename = $profilenames[0].' & '. $profilenames[1];  

}
else {
$profilename = $Profile_firstname;  
    
}

$test = "agency".$profile_id;

if($matchstatus == '')
{
 $matchstatus = $Profile_status;
}
else {
$Profile_stat = ($matchstatus == 'Matched') ? 'Matched' : '';
$Profile_stat = ($matchstatus == 'Placed') ? 'Placed' : ''; 
}

	$publishSQL = db_arr("SELECT publishStatus,allow_contact FROM `Profiles` WHERE `ID` = '$profile_id'");
	$pubStatus = $publishSQL['publishStatus'];	
        $allowCont = $publishSQL['allow_contact'];

	if (strlen($profilename) >= 15) {
		$profile_t = substr($profilename, 0, 18);
//$profile_firstnames = $profile_t.'<span title="'.$profilename.'">...</span>';
		$profile_firstnames = '<span title="' . $profilename . '">' . $profile_t . '...' . '.</span>';
	} else {
$profile_firstnames = $profilename;    
}

$profile_tslid  = $row_agency['IDLevel'];
$selected_random = $test;
$selected_active = $Profile_status == 'Active' ? 'checked=checked' : '';  
$selected_inactive = $Profile_status == 'Approval' ? 'checked=checked' : '';
$selected_inactive_user = $Profile_status == 'Inactive' ? 'checked=checked' : '';
$selected_match = $matchstatus == 'Matched' ? 'checked=checked' : ''; 
$selected_placed = $matchstatus == 'Placed' ? 'checked=checked' : '';     
$publishStatus = $pubStatus == '0' ? 'checked' : '';
$allowContact = $allowCont == '1' ? 'checked' : '';
$profile_image = htmlentities($photo);
++$count;
$profile_nummber = '';
$profile_levelid = db_arr("SELECT IDLevel FROM `sys_acl_levels_members` WHERE `IDMember` = '$profile_id' order by DateStarts  desc limit 0 ,1");
$profile_tslid1  = $profile_levelid['IDLevel'];

if($profile_tslid1 == 24){
	
		$profile_nummber = 'Profile Number: '.$row_agency['Profile_year'].'_'.str_pad($row_agency['Profile_no'], 4, "0", STR_PAD_LEFT);
}else{
	$profile_nummber = '&lt;br&gt;';
}
	$xml .= '<item id="' . $count . '">
<profile_id>' . $profile_id . '</profile_id>
<profile_firstname>' . htmlentities($profile_firstnames) . '</profile_firstname>
<profile_tslid>' . $profile_nummber . '</profile_tslid>
<selected_random>' . $selected_random . '</selected_random>
<selected_active>' . $selected_active . '</selected_active>
<selected_inactive>' . $selected_inactive . '</selected_inactive>
<selected_match>' . $selected_match . '</selected_match>
<selected_placed>' . $selected_placed . '</selected_placed>
<profile_image>' . $profile_image . '</profile_image>
<selected_inactive_user>' . $selected_inactive_user . '</selected_inactive_user>
<publish_status>' . $publishStatus . '</publish_status>
<allow_contact>' . $allowContact . '</allow_contact>
</item>';

}
$xml .= '</data>';    
if ($cmdtuples > 0)
{
 echo $xml;
}
else
{
echo $Agencydetails;
}

?>

