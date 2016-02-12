<?php
/*********************************************************************************
* Name:    Prashanth A
* Date:    01/07/2014
* Purpose: Listing the pending photos,videos and journals for approval
*********************************************************************************/
require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');
require_once ('../../inc/utils.inc.php');

$logid = getLoggedId();
$getagency = getProfileInfo($logid);
$from = ($_REQUEST['posStart'] == '')?0:$_REQUEST['posStart'];
$count = ($_REQUEST['count'] == '')?18:$_REQUEST['count'];

$searchtype = $_REQUEST['type'];

switch ($searchtype) {
case 'photo':
$Agency_members = "SELECT SQL_CALC_FOUND_ROWS `p`.* , IF(ISNULL(`tl`.`Name`),'', `tl`.`Name`) AS `ml_name` FROM `Profiles` AS `p` INNER JOIN `bx_groups_fans` AS `f` ON (`f`.`id_entry` = '$getagency[AdoptionAgency]' AND `f`.`id_profile` = `p`.`ID` AND `p`.`ProfileType` != 8 AND `f`.`confirmed` = 1 AND `p`.`Status` <> 'Rejected') LEFT JOIN `sys_acl_levels_members` AS `tlm` ON `p`.`ID`=`tlm`.`IDMember` AND `tlm`.`DateStarts` < NOW() AND (`tlm`.`DateExpires`>NOW() || ISNULL(`tlm`.`DateExpires`)) LEFT JOIN `sys_acl_levels` AS `tl` ON `tlm`.`IDLevel`=`tl`.`ID` GROUP BY `p`. `ID` order by p.NickName";
if($from == 0){
$members_query = mysql_query($Agency_members);    
while (($mem_rows = mysql_fetch_array($members_query, MYSQL_BOTH)))
{
 $mem_ids = $mem_rows['ID'];
 $photocounts = mysql_query("SELECT  count(`ID`) from `bx_photos_main` where `Owner` = " . $mem_ids . " AND `Status` = 'pending'");     
 $photoCount = mysql_fetch_array($photocounts);
 $tCount[] = $photoCount[0].",";

}    
}   
$agency_query = mysql_query($Agency_members);
$cmdtuples = mysql_num_rows($agency_query);
$arrColumns = explode(",", $columns);
$arrRows_agency_list = array();
header ("content-type: text/xml");
$xml = '<?xml version="1.0" encoding="UTF-8" ?>';
if($from > 0){
    $xml .= '<data pos="'.$from.'">';
}
else{
$xml .= '<data total_count="'.$tCount.'">';
}
$count = $from;
while (($mem_row = mysql_fetch_array($agency_query, MYSQL_BOTH)))
{
$mem_id = $mem_row['ID'];
$profileDet = getProfileInfo($mem_id);
$user_link = 'extra_profile_view_17.php?id='.$profileDet['ID'];
$photodetails = db_res("SELECT  * from `bx_photos_main` where `Owner` = " . $mem_id . " AND `Status` = 'pending'");

while (($row_photo = mysql_fetch_array($photodetails, MYSQL_BOTH)))
{    
$photos   =   $site['url'].'m/photos/get_image/browse/'.$row_photo['Hash'].'.'.$row_photo['Ext'];
$user_photos = htmlspecialchars($photos, ENT_QUOTES, 'UTF-8');
$image_title =  htmlspecialchars($row_photo['Title'], ENT_QUOTES, 'UTF-8');

$image_link = $site['url'].'m/photos/view/'.htmlspecialchars($row_photo['Uri'],ENT_QUOTES, 'UTF-8');
$img_tag  = $site['url'].'templates/base/images/icons/spacer.gif';
++$count;
$xml .= '<item id="'.$count.'">
<image_id>'.$row_photo[0].'</image_id>
<profile_image>'.$user_photos.'</profile_image>
<image_title>'.$image_title.'</image_title>
<image_url>'.$image_link.'</image_url>   
<user_name>'.htmlspecialchars($profileDet[NickName], ENT_QUOTES, 'UTF-8').'</user_name>
<user_link>'.$user_link.'</user_link>  
 <image_tag>'.$img_tag.'</image_tag>    
</item>';

}
}$xml .= '</data>';    
break;
case 'video':
 $Agency_members = "SELECT SQL_CALC_FOUND_ROWS `p`.* , IF(ISNULL(`tl`.`Name`),'', `tl`.`Name`) AS `ml_name` FROM `Profiles` AS `p` INNER JOIN `bx_groups_fans` AS `f` ON (`f`.`id_entry` = '$getagency[AdoptionAgency]' AND `f`.`id_profile` = `p`.`ID` AND `p`.`ProfileType` != 8 AND `f`.`confirmed` = 1 AND `p`.`Status` <> 'Rejected') LEFT JOIN `sys_acl_levels_members` AS `tlm` ON `p`.`ID`=`tlm`.`IDMember` AND `tlm`.`DateStarts` < NOW() AND (`tlm`.`DateExpires`>NOW() || ISNULL(`tlm`.`DateExpires`)) LEFT JOIN `sys_acl_levels` AS `tl` ON `tlm`.`IDLevel`=`tl`.`ID` GROUP BY `p`. `ID`  order by p.NickName";
if($from == 0){
$members_query = mysql_query($Agency_members);    
while (($mem_rows = mysql_fetch_array($members_query, MYSQL_BOTH)))
{
 $mem_ids = $mem_rows['ID'];
 $videocounts = mysql_query("SELECT  count(`ID`) from `RayVideoFiles` where `Owner` = " . $mem_ids . " AND `Status` = 'pending'");     
 $videoCount = mysql_fetch_array($videocounts);
 $tCount = $videoCount[0].",";
 break;
}    
}         
    
$agency_query = mysql_query($Agency_members);
$cmdtuples = mysql_num_rows($agency_query);
$arrColumns = explode(",", $columns);
$arrRows_agency_list = array();
header ("content-type: text/xml");
$xml = '<?xml version="1.0" encoding="UTF-8" ?>';
if($from > 0){
    $xml .= '<data pos="'.$from.'">';
}
else{
$xml .= '<data total_count="'.$tCount.'">';
}$count = $from;
while (($mem_row = mysql_fetch_array($agency_query, MYSQL_BOTH)))
{
$mem_id = $mem_row['ID'];
$profileDet = getprofileinfo($mem_id);
$videodetails = db_res("SELECT * FROM `RayVideoFiles` WHERE `Owner` = ".$mem_id." AND Time > 0 AND Status = 'pending'");

$getPass = getProfileInfo($mem_id);
while (($row_video = mysql_fetch_array($videodetails, MYSQL_BOTH)))
{
    
//$videos= $site['url'].'flash/modules/video/files/'.htmlentities($row_video[ID]).'_small.jpg';

$filename =  $dir['root'].'flash/modules/video/files/'.$row_video[ID].'_small.jpg';
if(file_exists($filename)) { 
 $videos= $site['url'].'flash/modules/video/files/'.htmlentities($row_video[ID]).'_small.jpg';

}
else {
$videos= $site['url'].'flash/modules/video/files/'.'_'.$row_video[ID].'_small.jpg';
}





$user_url = 'extra_profile_view_17.php?id='.htmlentities($profileDet['ID']);
$img_tag  = $site['url'].'templates/base/images/icons/spacer.gif';

$user_video = htmlentities($videos);
++$count;
$xml .= '<item id="'.$count.'">
<video_id>'.$row_video[0].'</video_id>
<video_image>'.$user_video.'</video_image>
<profile_Username>'.htmlspecialchars($profileDet['NickName'], ENT_QUOTES, 'UTF-8').'</profile_Username>
<video_url>'.$siteurl.'m/videos/view/'.htmlspecialchars($row_video[Uri], ENT_QUOTES, 'UTF-8').'</video_url> 
<video_tile>'.htmlspecialchars($row_video['Title'], ENT_QUOTES, 'UTF-8').'</video_tile> 
<user_url>'.$user_url.'</user_url>     
 <image_tag>'.$img_tag.'</image_tag>   
</item>';

}
}$xml .= '</data>';   

break;
case 'blog':
$Agency_members = "SELECT SQL_CALC_FOUND_ROWS `p`.* , IF(ISNULL(`tl`.`Name`),'', `tl`.`Name`) AS `ml_name` FROM `Profiles` AS `p` INNER JOIN `bx_groups_fans` AS `f` ON (`f`.`id_entry` = '$getagency[AdoptionAgency]' AND `f`.`id_profile` = `p`.`ID` AND `p`.`ProfileType` != 8 AND `f`.`confirmed` = 1 AND `p`.`Status` <> 'Rejected') LEFT JOIN `sys_acl_levels_members` AS `tlm` ON `p`.`ID`=`tlm`.`IDMember` AND `tlm`.`DateStarts` < NOW() AND (`tlm`.`DateExpires`>NOW() || ISNULL(`tlm`.`DateExpires`)) LEFT JOIN `sys_acl_levels` AS `tl` ON `tlm`.`IDLevel`=`tl`.`ID` GROUP BY `p`. `ID`";

if($from == 0){
$members_query = mysql_query($Agency_members);    
while (($mem_rows = mysql_fetch_array($members_query, MYSQL_BOTH)))
{
 $mem_ids = $mem_rows['ID'];
 $blogcounts = mysql_query("SELECT  count(`ID`) from `bx_blogs_posts` where `OwnerID` = " . $mem_ids . " AND `Status` = 'pending'");     
 $blogCount = mysql_fetch_array($photocounts);
 $tCount = $blogCount[0].",";
 break;
}    
}      
 
$agency_query = mysql_query($Agency_members);
$cmdtuples = mysql_num_rows($agency_query);
$arrColumns = explode(",", $columns);
$arrRows_agency_list = array();
header ("content-type: text/xml");
$xml = '<?xml version="1.0" encoding="UTF-8" ?>';
if($from > 0){
    $xml .= '<data pos="'.$from.'">';
}
else{
$xml .= '<data total_count="'.$tCount.'">';
}$count = $from;
while (($mem_row = mysql_fetch_array($agency_query, MYSQL_BOTH)))
{
$mem_id = $mem_row['ID'];
$getDet = getprofileinfo($mem_id);
$Blogetails = db_res("SELECT PostID,PostText,PostCaption,PostUri,`PostDate` AS 'UnitDateTimeUTS' FROM `bx_blogs_posts` WHERE `PostStatus` = 'disapproval' AND `OwnerID` = '$mem_id'");

$getPass = getProfileInfo($agency_id);
while (($row_post = mysql_fetch_array($Blogetails, MYSQL_BOTH)))
{
    
$post_id= $row_post['PostID'];    
$post_caption = htmlspecialchars($row_post['PostCaption'], ENT_QUOTES, 'UTF-8');
$post_uri = htmlspecialchars($row_post['PostUri'], ENT_QUOTES, 'UTF-8');

$sPostText = trim(strip_tags($row_post['PostText']));

$user_name = htmlspecialchars($getDet['NickName'], ENT_QUOTES, 'UTF-8');
$user_url = 'extra_profile_view_17.php?id='.$getDet['ID'];

$sDateTime = defineTimeInterval($row_post['UnitDateTimeUTS']);

++$count;
$xml .= '<item id="'.$count.'">
<post_id>'.$post_id.'</post_id>
<post_caption>'.$post_caption.'</post_caption>
<post_uri>'.'blogs/entry/'.$post_uri.'</post_uri>
<post_desc>'.$sPostText.'</post_desc>
<post_username>'.$user_name.'</post_username>
<post_userurl>'.$user_url.'</post_userurl>
<post_date>'.$sDateTime.'</post_date>
</item>';
}
}$xml .= '</data>';      

 break;
}
if ($cmdtuples > 0)
{
 echo $xml;
}
else
{
echo $AgencyLike_List;
}

?>
