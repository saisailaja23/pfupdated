<?php

/* * ************************************************************************************************

 *     Name                :  Prashanth A
 *     Date                :  Sat Dec 7 2013
 *     Purpose             :  Inserting values to the database  and assiging membership to users.

 * ************************************************************************************************* */

require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');
require_once ('../../inc/utils.inc.php');
require_once ('../../inc/db.inc.php');

// Getting the post values
$facebook_url = (trim($_POST['facebookurl']) != '') ? $_POST['facebookurl'] : '';
$twitter_url = (trim($_POST['twitterurl']) != '') ? $_POST['twitterurl'] : '';
$google_url = (trim($_POST['googleurl']) != '') ? $_POST['googleurl'] : '';
$blogger_url = (trim($_POST['bloggerurl']) != '') ? $_POST['bloggerurl'] : '';
$pinerest_url = (trim($_POST['pineresturl']) != '') ? $_POST['pineresturl'] : '';
$instagram_url = (trim($_POST['instagramurl']) != '') ? $_POST['instagramurl'] : '';
$id = $_POST['id'];
$Pid = getLoggedId();

switch ($id) {
    case 'fburl':
        $Profile_query = "Update `Profiles` SET `Facebook` = '" . $facebook_url . "' where ID = '" . $Pid . "'";
        db_res($Profile_query);
        $Profile_draft_query = "Update `Profiles_draft` SET `Facebook` = '" . $facebook_url . "' where ID = '" . $Pid . "'";
        db_res($Profile_draft_query);
        $img = (trim($_POST['facebookurl']) != '') ? 'templates/tmpl_par/images/splash/ico_fb_act.png' : 'templates/tmpl_par/images/ico_build_fb.png';
        break;
    
    case 'turl':
        $Profile_query = "Update `Profiles` SET `Twitter` = '" . $twitter_url . "' where ID = '" . $Pid . "'";
        db_res($Profile_query);
        $Profile_draft_query = "Update `Profiles_draft` SET `Twitter` = '" . $twitter_url . "' where ID = '" . $Pid . "'";
        db_res($Profile_draft_query);
        $img = (trim($_POST['twitterurl']) != '') ? 'templates/tmpl_par/images/splash/ico_tw_act.png' : 'templates/tmpl_par/images/ico_build_tw.png';
        break;
    
    case 'gurl':
        $Profile_query = "Update `Profiles` SET `Google` = '" . $google_url . "'  where ID = '" . $Pid . "'";
        db_res($Profile_query);
        $Profile_draft_query = "Update `Profiles_draft` SET `Google` = '" . $google_url . "'  where ID = '" . $Pid . "'";
        db_res($Profile_draft_query);
        $img = (trim($_POST['googleurl']) != '') ? 'templates/tmpl_par/images/splash/ico_go_act.png' : 'templates/tmpl_par/images/ico_build_go.png';
        break;
    
    case 'burl':
        $Profile_query = "Update `Profiles` SET `Blogger` = '" . $blogger_url . "' where ID = '" . $Pid . "'";
        db_res($Profile_query);

        $Profile_draft_query = "Update `Profiles_draft` SET `Blogger` = '" . $blogger_url . "' where ID = '" . $Pid . "'";
        db_res($Profile_draft_query);
        $img = (trim($_POST['bloggerurl']) != '') ? 'templates/tmpl_par/images/splash/ico_bi_act.png' : 'templates/tmpl_par/images/ico_build_bi.png';
        break;
    
    case 'purl':
        $Profile_query = "Update `Profiles` SET `Pinerest` = '" . $pinerest_url . "' where ID = '" . $Pid . "'";
        db_res($Profile_query);

        $Profile_draft_query = "Update `Profiles_draft` SET `Pinerest` = '" . $pinerest_url . "' where ID = '" . $Pid . "'";
        db_res($Profile_draft_query);
        $img = (trim($_POST['pineresturl']) != '') ? 'templates/tmpl_par/images/splash/ico_pi_act.png' : 'templates/tmpl_par/images/ico_build_pi.png';
        break;
	case 'iurl':
        $Profile_query = "Update `Profiles` SET `Instagram` = '" . $instagram_url . "' where ID = '" . $Pid . "'";
        db_res($Profile_query);

        $Profile_draft_query = "Update `Profiles_draft` SET `Instagram` = '" . $instagram_url . "' where ID = '" . $Pid . "'";
        db_res($Profile_draft_query);
        $img = (trim($_POST['instagramurl']) != '') ? 'templates/tmpl_par/images/splash/ico_in_act.png' : 'templates/tmpl_par/images/ico_build_in.png';
        break;
}
$update = mysql_affected_rows();

echo json_encode(array(
'status' => 'success',
    'img'=>$img,
    
));
//if same value updates its thrwoing error message
/*
if($update > 0)
{  
echo json_encode(array(
'status' => 'success',
    'img'=>$img,
    
));
}
else
{
echo json_encode(array(
'status' => 'err', 
'response' => 'Please give Valid url and try again',
    ));
}*/
/*
// Updating the social link  
if(!empty($facebook_url)){
$Profile_facebook= "Update `Profiles` SET `Facebook` = '" . $facebook_url . "' where ID = '" . $Pid . "'";
db_res($Profile_facebook);

$Profile_draft_facebook= "Update `Profiles_draft` SET `Facebook` = '" . $facebook_url . "' where ID = '" . $Pid . "'";
db_res($Profile_draft_facebook);
}
if(!empty($twitter_url)){
$Profile_twitter= "Update `Profiles` SET `Twitter` = '" . $twitter_url . "' where ID = '" . $Pid . "'";
db_res($Profile_twitter);

$Profile_draft_twitter= "Update `Profiles_draft` SET `Twitter` = '" . $twitter_url . "' where ID = '" . $Pid . "'";
db_res($Profile_draft_twitter);
}
if(!empty($google_url)){
$Profile_google = "Update `Profiles` SET `Google` = '" . $google_url . "'  where ID = '" . $Pid . "'";
db_res($Profile_google);

$Profile_draft_google = "Update `Profiles_draft` SET `Google` = '" . $google_url . "'  where ID = '" . $Pid . "'";
db_res($Profile_draft_google);
}
if(!empty($blogger_url)){
$Profile_blogger= "Update `Profiles` SET `Blogger` = '" . $blogger_url . "' where ID = '" . $Pid . "'";
db_res($Profile_blogger);

$Profile_draft_blogger= "Update `Profiles_draft` SET `Blogger` = '" . $blogger_url . "' where ID = '" . $Pid . "'";
db_res($Profile_draft_blogger);
}
if(!empty($pinerest_url)){
$Profile_pinerest= "Update `Profiles` SET `Pinerest` = '" . $pinerest_url . "' where ID = '" . $Pid . "'";
db_res($Profile_pinerest);

$Profile_draft_pinerest= "Update `Profiles_draft` SET `Pinerest` = '" . $pinerest_url . "' where ID = '" . $Pid . "'";
db_res($Profile_draft_pinerest);
}*/


?>