<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'On');

require_once( 'inc/header.inc.php' );
require_once ('inc/profiles.inc.php');

$myvars = 'code=' . $_REQUEST['code'] . '&client_id=539428858218-eacddo0al0a564ommpcuk382uuketsj2.apps.googleusercontent.com&client_secret=vIuEfMLpgJ2FbqQ3kt5TewnV&redirect_uri=http://www.parentfinder.com/oauth2callback.php&grant_type=authorization_code';
//echo $myvars;
//exit;

$ch = curl_init('https://accounts.google.com/o/oauth2/token');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
$accessInfo = json_decode($response);

$access_token = $accessInfo->access_token;
$token_type = $accessInfo->token_type;
$expires_in = $accessInfo->expires_in;
$refresh_token = $accessInfo->refresh_token;

$id = getLoggedId();

echo $sqlquery = "INSERT INTO YoutubeToken (ID,access_token,token_type,expires_in,refresh_token) VALUES (".$id.",'".$access_token."','".$token_type."',".$expires_in.",'".$refresh_token."')";

mysql_query($sqlquery);

$_SESSION['access_token'] = $access_token;
$_SESSION['token_type'] = $token_type;
$_SESSION['expires_in'] = $expires_in;
$_SESSION['refresh_token'] = $refresh_token;

if($access_token)
    $_SESSION['token_flag'] = 1;
else
    $_SESSION['token_flag'] = 0;

header('Location:http://'.$_SERVER['HTTP_HOST'].'/extra_agency_view_28.php');

$ch1 = curl_init('https://www.googleapis.com/youtube/v3/channels?part=id&mine=true&access_token=' . $access_token);
curl_setopt($ch1, CURLOPT_HTTPGET, 1);
curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch1, CURLOPT_HEADER, 0);
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
$response1 = curl_exec($ch1);
$channelDetails = json_decode($response1);

$channelID = array();
foreach ($channelDetails->items as $key => $value) {
    if ($value->kind === 'youtube#channel') {
        $channelID[$key] = $value->id;
    }
}
//echo $channelID[0];
echo $updateChannelSql = "UPDATE  `YoutubeToken` SET  `channelId` =  '" . $channelID[0] . "' WHERE  `ID` = " . $id;
mysql_query($updateChannelSql);
//foreach ($channelID as $key => $value) {
//    $ch2 = curl_init('https://www.googleapis.com/youtube/v3/search?part=snippet&channelId=' . $value . '&access_token=' . $access_token);
//    curl_setopt($ch2, CURLOPT_HTTPGET, 1);
//    curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, 1);
//    curl_setopt($ch2, CURLOPT_HEADER, 0);
//    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
//    $response2 = curl_exec($ch2);
//    $videoDetails = json_decode($response2);
//        
//    $videoID = array();
//    $thumbnail = array();
//    foreach ($videoDetails->items as $key => $value) {
//        if ($value->id->kind === 'youtube#video') {
//            $videoID[$key] = $value->id->videoId;
//            $thumbnail[$videoID[$key]] = $value->snippet->thumbnails->default;
//        }
//    }
//    print_r($videoID);
//    print_r($thumbnail);
//}
?>