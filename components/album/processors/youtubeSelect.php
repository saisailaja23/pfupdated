<?php

define('BX_PROFILE_PAGE', 1);
require_once( '../../../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolAlbums.php');

//echo '<pre>';
//print_r($_POST);

$url = explode(",", $_POST['url']);
$albumID = trim($_POST['albumID']);
$title = explode(",", $_POST['filename']);
$file = ($_POST['files']);

//print_r($url);
//print_r($title);
if($_POST['upload'] == 1){
    $uploadcheck = 'uploaded';
}else{
    $uploadcheck = 'processed';    
}
$loggedID = mysql_fetch_array(mysql_query("select Owner from sys_albums where ID=$albumID"));
//
if (count($url) != 0) {
    $youtubeLink = '';
    for($i = 0; $i < count($url); $i++) {
        $fileName = $title[$i];
//        $fileName = str_replace(' ', '-', $title[$i]);
        $youtubeLink = '<p>The link for the video is https://www.youtube.com/watch?v=' . $url[$i] . '</p>'; 
        $sql = 'INSERT INTO RayVideoFiles SET
			    Title= "' . $title[$i] . '",
			    Uri = "' . $url[$i] . '",
			    Tags= "' . $fileName . '",
			    Description = "' . $url[$i] . '",
			    Time=' . time() . ',
			    Date = ' . time() . ',
			    Owner= "' . $loggedID['Owner'] . '",
			    Status = "Approved",
                            ytStatusCheck = "' . $uploadcheck . '",
			    YoutubeLink = 1,
			    ytChannelId = "' . $_POST['ytchannel'] . '"';
//        echo $sql;
        $result = mysql_query($sql);
        echo $lastId = mysql_insert_id();

        $album = new BxDolAlbums("bx_videos", $loggedID['Owner']);
        $album->BxDolAlbums("bx_videos", $loggedID['Owner']);
        $album->addObject($albumID, $lastId);
        $new_order = mysql_fetch_array(mysql_query("SELECT MAX(`obj_order`) as max_order FROM sys_albums_objects WHERE  `id_album` = $albumID"));
        mysql_query("UPDATE sys_albums_objects SET obj_order= " . ($new_order[0] + 1) . " WHERE id_album= $albumID AND id_object= $lastId");

        $_getOwnerID = mysql_fetch_array(mysql_query("SELECT Uri,Owner FROM RayVideoFiles WHERE ID = " . $lastId));

        if (@mysql_num_rows(mysql_query("SELECT ID FROM  Profiles JOIN bx_groups_moderation WHERE Profiles.ID= " . $_getOwnerID['Owner'] . " AND Profiles.AdoptionAgency = bx_groups_moderation.GroupId AND bx_groups_moderation.ApproveStatus= 'on' AND bx_groups_moderation.Type = 'video'"))) {
            $mFlag = true;
        } else {
            $mFlag = false;
        }

//        if($_POST['cairs']){
//            
//        }
        $sAutoApprove = (getSettingValue($sModule, "autoApprove") == TRUE_VAL || $mFlag) ? STATUS_APPROVED : STATUS_DISAPPROVED;
        $mailSettings = getMailSettings();
        if ($sAutoApprove == "disapproved") {
            $rEmailTemplate = new BxDolEmailTemplates();
            $aTemplate = $rEmailTemplate->getTemplate('Video_add_approval');
            $aPlus = array('VideoUri' => $_getOwnerID['Uri']);
            $aAgencyEmail = db_arr("SELECT Profiles.* FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =" . $_getOwnerID['Owner'] . " AND Profiles.AdoptionAgency=bx_groups_main.id )");
            If (!(mysql_num_rows(mysql_query("SELECT id FROM bx_groups_main WHERE  author_id=" . $_getOwnerID['Owner'])))) {
                
            }
            sendMail($aAgencyEmail['Email'], $aTemplate['Subject'], $aTemplate['Body'], $_getOwnerID['Owner'], $aPlus);
        } elseif ($mailSettings['VideoUpload'] == 0) {
            $rEmailTemplate = new BxDolEmailTemplates();
            $aTemplate = $rEmailTemplate->getTemplate('Video_add_notification');
            $aPlus = array('VideoUri' => $_getOwnerID['Uri']);
            $aAgencyEmail = db_arr("SELECT Profiles.* FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =" . $_getOwnerID['Owner'] . " AND Profiles.AdoptionAgency=bx_groups_main.id )");
            If (!(mysql_num_rows(mysql_query("SELECT id FROM bx_groups_main WHERE  author_id=" . $_getOwnerID['Owner'])))) {
                
            }
//            print_r($aTemplate);
            
            
            
            $aTemplate['Body'] = '<html><head></head><body style="font: 12px Verdana; color:#000000">
                                        <p><b>Dear Agency Admin</b>,</p>

                                        <p>User <b><RealName></b> has uploaded new video in their album.</p>
                                        ' . $youtubeLink . '
                                        <p><b>User email id:  </b> <Email> </p>

                                        <p>To review this video login <a href="<Domain>">here</a> and click Settings under Action Block</p>
                                        <p><b>Thank you for using our services!</b></p>

                                        <p>--</p>
                                        <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!!
                                        <br />Auto-generated e-mail, please, do not reply!!!</p></body>
                                    </html>';
            sendMail($aAgencyEmail['Email'], $aTemplate['Subject'], $aTemplate['Body'], $_getOwnerID['Owner'], $aPlus);
        } else{            
        }
//
////        $i = $i + 1;
    }
}