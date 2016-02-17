<?php

//require_once ('../../../inc/header.inc.php');
//require_once ('../../../inc/profiles.inc.php');

define('BX_PROFILE_PAGE', 1);
require_once( '../../../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolAlbums.php');

$link = $_POST['url'];
$albumID = trim($_POST['albumID']);
$title = trim($_POST['filename']);
$fileName = str_replace(' ', '-', $title);

$video_id = explode("?v=", $link);

if (empty($video_id[1]))
    $video_id = explode("/v/", $link);

$video_id = explode("&", $video_id[1]);
$video_id = $video_id[0];

$loggedID = mysql_fetch_array(mysql_query("select Owner from sys_albums where ID=$albumID"));

//echo "SELECT ID FROM  Profiles JOIN bx_groups_moderation WHERE Profiles.ID= " . $loggedID['Owner'] . " AND Profiles.AdoptionAgency = bx_groups_moderation.GroupId AND bx_groups_moderation.ApproveStatus= 'on' AND bx_groups_moderation.Type = 'video'";

//if (@mysql_num_rows(mysql_query("SELECT ID FROM  Profiles JOIN bx_groups_moderation WHERE Profiles.ID= " . $loggedID['Owner'] . " AND Profiles.AdoptionAgency = bx_groups_moderation.GroupId AND bx_groups_moderation.ApproveStatus= 'on' AND bx_groups_moderation.Type = 'video'"))) {
//    $status = "approved";
//}
//else
//    $status = "pending";

$sql = 'INSERT INTO RayVideoFiles SET
    Title= "'.$title.'" ,
    Uri = "'.$video_id.'",
    Tags= "'.$fileName .'",
    Description = "",
    Time=' . time() . ',
    Date = ' . time() . ',
    Owner= "' . $loggedID['Owner'] . '",
    Status = "approved",
    YoutubeLink = 1';

//echo "SELECT max(obj_order) FROM sys_albums_objects s WHERE  `id_album` =". $albumID;
$result = mysql_query($sql);
$lastId = mysql_insert_id();
//echo 'satya';

$album = new BxDolAlbums("bx_videos", $loggedID['Owner']);
$album->BxDolAlbums("bx_videos", $loggedID['Owner']);
$album->addObject($albumID, $lastId);
$new_order =  mysql_fetch_array(mysql_query("SELECT MAX(`obj_order`) as max_order FROM sys_albums_objects WHERE  `id_album` = $albumID"));
//print_r($new_order);
//echo "UPDATE 'sys_albums_objects' SET 'obj_order'= ".($new_order[0] + 1)." WHERE 'id_album'= $albumID AND 'id_object'= $lastId";
echo mysql_query("UPDATE sys_albums_objects SET obj_order= ".($new_order[0] + 1)." WHERE id_album= $albumID AND id_object= $lastId");


//$_getOwnerID = mysql_fetch_array(mysql_query("SELECT Uri,Owner FROM RayVideoFiles WHERE ID = " . $lastId));
//
//if (@mysql_num_rows(mysql_query("SELECT ID FROM  Profiles JOIN bx_groups_moderation WHERE Profiles.ID= " . $_getOwnerID['Owner'] . " AND Profiles.AdoptionAgency = bx_groups_moderation.GroupId AND bx_groups_moderation.ApproveStatus= 'on' AND bx_groups_moderation.Type = 'video'"))) {
//    $mFlag = true;
//} else {
//    $mFlag = false;
//}
//
//$sAutoApprove = (getSettingValue($sModule, "autoApprove") == TRUE_VAL || $mFlag) ? STATUS_APPROVED : STATUS_DISAPPROVED;
//
//if ($sAutoApprove == "disapproved") {
//    $rEmailTemplate = new BxDolEmailTemplates();
//    $aTemplate = $rEmailTemplate->getTemplate('Video_add_approval');
//    $aPlus = array('VideoUri' => $_getOwnerID['Uri']);
//    $aAgencyEmail = db_arr("SELECT Profiles.* FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =" . $_getOwnerID['Owner'] . " AND Profiles.AdoptionAgency=bx_groups_main.id )");
//    If (!(mysql_num_rows(mysql_query("SELECT id FROM bx_groups_main WHERE  author_id=" . $_getOwnerID['Owner'])))) {
//        
//    }
//    sendMail($aAgencyEmail['Email'], $aTemplate['Subject'], $aTemplate['Body'], $_getOwnerID['Owner'], $aPlus);
//} else {
//    $rEmailTemplate = new BxDolEmailTemplates();
//    $aTemplate = $rEmailTemplate->getTemplate('Video_add_notification');
//    $aPlus = array('VideoUri' => $_getOwnerID['Uri']);
//    $aAgencyEmail = db_arr("SELECT Profiles.* FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =" . $_getOwnerID['Owner'] . " AND Profiles.AdoptionAgency=bx_groups_main.id )");
//    If (!(mysql_num_rows(mysql_query("SELECT id FROM bx_groups_main WHERE  author_id=" . $_getOwnerID['Owner'])))) {
//        
//    }
//    sendMail($aAgencyEmail['Email'], $aTemplate['Subject'], $aTemplate['Body'], $_getOwnerID['Owner'], $aPlus);
//}