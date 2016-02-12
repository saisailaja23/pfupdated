<?php

define('BX_PROFILE_PAGE', 1);
require_once( '../../../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolAlbums.php');

if(@$_GET['id']){
	$id = $_GET['id'];
}else{
	$id = $_COOKIE['memberID'];
}

$ida = db_arr("SELECT `AdoptionAgency` FROM `Profiles` WHERE `ID` = $id");
$idd = $ida[0];


if($_GET['method']=='set'){
	$link = $_POST['url'];
	$title = trim($_POST['filename']);
	$fileName = str_replace(' ', '-', $title);

	$video_id = explode("?v=", $link);

	if (empty($video_id[1])){ $video_id = explode("/v/", $link); }
	$video_id = explode("&", $video_id[1]);
	$video_id = $video_id[0];

	if($idd!=0){
		$sql = "UPDATE `pfcomm`.`bx_groups_main` 
			SET `videoName` = '$title', `videoUri` = '$video_id' 
			WHERE `bx_groups_main`.`id` = $idd;";
		$rs = mysql_query($sql);
		$dat = $idd;
	}else{
		$dat = 0;
	}
	echo $dat;
}elseif($_GET['method']=='get'){
	$rs = mysql_query("SELECT `videoName`,`videoUri` FROM `bx_groups_main` WHERE `id`=$idd");
	$res = mysql_fetch_array($rs, MYSQL_ASSOC);
	echo json_encode($res);
}elseif($_GET['method']=='delete'){
	if($idd!=0){
		$sql = "UPDATE `pfcomm`.`bx_groups_main` 
			SET `videoName` = '', `videoUri` = '' 
			WHERE `bx_groups_main`.`id` = $idd;";
		$rs = mysql_query($sql);
		$dat = $idd;
	}else{
		$dat = 0;
	}
	echo $dat;
}
?>