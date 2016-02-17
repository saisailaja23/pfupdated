<?php
/**
 * C:\Users\msi1308\AppData\Roaming\Sublime Text 2\Packages/PhpTidy/phptidy-sublime-buffer.php
 *
 * @author Aravind Buddha <aravind.buddha@mediaus.com>
 * @package default
 */
error_reporting(E_ALL);
require_once( '../../../../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'images.inc.php');
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolAlbums.php');
require_once( '../../../../ProfilebuilderComponent/processors/watermark.php' );
include 'imagetool.class.php';

	$root ="{$dir['root']}";
	$souceDir = "$root/modules/boonex/photos/data/files/";
	$temp="$root/tmp/";
	$uploadPath=$souceDir;

	$logged = getLoggedId();
        
        $avatar_id=getAvatarId($logged);
        
	$pic_300= $avatar_id.".jpg";

	//Families Search Page
 	$FavDir    = "$root/modules/boonex/avatar/data/favourite/";
 	//This is for saving actual image in avatar image with profile ID
    $AvatarDir    = $dir['root']."modules/boonex/avatar/data/avatarphotos/";
 	//This is for creating small images with $avataridi.jpg and $avtarid.jpg
 	$ImgDir    = "$root/modules/boonex/avatar/data/images/";


 	//Depricate: We are no more using avatarphotos and slider folders
 	// 	//not required
 	// 	$AvatarDir = "$root/modules/boonex/avatar/data/avatarphotos/";
	// // Avatarid 	
	// //Seach family with logged in id
 	// 	$SliderDir = "$root/modules/boonex/avatar/data/slider/";

if ($_GET) {
	$id=$_GET['id'];
	$x=$_GET['x'];
	$y=$_GET['y'];
	$w=$_GET['w'];
	$h=$_GET['h'];

$serverName=$id.'.jpg';
	//Creating Temp image with new height,widht in tmp folder
	$imgTObj = new ImageTool($souceDir.$id.'.jpg');
	$imgTObj -> resizeImage($w, $h, 'crop', $x, $y);
	$imgTObj -> saveImage($temp.$id.".jpg", 100);
	@chmod($temp.$id.".jpg", 0644);

	//creating img for
copy($uploadPath.$serverName, $AvatarDir.$logged.'.jpg');
	@chmod($AvatarDir.$logged.'.jpg', 0644);
 	
 	//$avatar_id=getAvatarId($logged);
 	imageResize($temp.$id.".jpg", $FavDir.$avatar_id.'.jpg', 300, 250, true);
 	imageResize($temp.$id.".jpg", $ImgDir.$avatar_id.'.jpg', 64, 64, true);
 	imageResize($temp.$id.".jpg", $ImgDir.$avatar_id.'i.jpg', 32, 32, true);
 	
        $match_status= db_arr("SELECT `status` FROM `watermarkimages` WHERE `author_id` = '$logged'");
        $matchstatus = $match_status[status]; 
    
        if($matchstatus != '') {         
         watermark_apply($logged,$matchstatus);
        }

 	@chmod($FavDir.$pic_300, 0644);
	@chmod($ImgDir.$avatar_id.'.jpg', 0644);
	@chmod($ImgDir.$avatar_id.'i.jpg', 0644);
 
  // @copy($ImgDir.$pic_32, $FavDir.$iAvatar.'.jpg');
	
	//Adding the cropped orginal photo to the Profile Picture album
	addToProfiles($id,$logged);

	print_r('{"state": true, "name":"'.str_replace("'", "\\'", $id).'"}');
}

//get New Avatar Id
function getAvatarId($logged){
	//Insert into avatar_images and update Profiels table
	$sql= "INSERT INTO bx_avatar_images SET `author_id` = '$logged'";
  $result = mysql_query($sql);
  $iAvatar = mysql_insert_id();
  $q= "UPDATE `Profiles` SET `Avatar` = '$iAvatar' WHERE `ID` = " . $logged;
  mysql_query($q);  
  return $iAvatar;
}

function addToProfiles($id,$logged){
	$album=new BxDolAlbums;
	$objlist=$album->getAlbumObjList(getProfilePicAlbumId($logged));
	//check if photo exits or not
	if(in_array($id, $objlist)){
		return ;
	}
	else{
		$albumid=getProfilePicAlbumId($logged);
	  $album->BxDolAlbums("bx_photos", $logged);
	  $album->addObject($albumid, $id);
	}
	return; 
}
function getProfilePicAlbumId($logged){
	$sql=
  	"SELECT ID 
  		FROM sys_albums 
  		WHERE  Caption =  'Profile Pictures'
  		AND `Type` LIKE 'bx_photos' 
  		AND `Owner` = $logged";
   $result = mysql_query($sql);	
 	$row= mysql_fetch_row($result);
 	return $row[0];
}
function createAllimgs($filename, $sMediaDir, $lastId){
	$aFileTypes = array(
		'icon' => array('postfix' => '_ri', 'size_def' => 32),
		'thumb' => array('postfix' => '_rt', 'size_def' => 64),
		'browse' => array('postfix' => '_t', 'size_def' => 140),
		'file' => array('postfix' => '_m', 'size_def' => 600),
		'org' => array('postfix' => '', 'size_def' => 1024)
	);

	// force into JPG
	$sExtension = '.jpg';
	$w[0] = 32;
	$h[0] = 32;
	$w[1] = 64;
	$h[1] = 64;
	$w[2] = 140;
	$h[2] = 140;
	$w[3] = 750;
	$h[3] = 750;
	list($widthh, $heightt) = getimagesize($filename);
	$w[4] = $widthh;
	$h[4] = $heightt;
	$z = 0;
	// generate present pics
	foreach ($aFileTypes as $sKey => $aValue) {
		$iWidth = $w[$z]; //(int)$this->oModule->_oConfig->getGlParam($sKey . '_width');
		$iHeight = $h[$z]; //(int)$this->oModule->_oConfig->getGlParam($sKey . '_height');

		if ($iWidth == 0)
			$iWidth = $aValue['size_def'];
		if ($iHeight == 0)
			$iHeight = $aValue['size_def'];
		$sNewFilePath = $sMediaDir . $lastId . $aValue['postfix'] . $sExtension;
	  $iRes = imageResize($sMediaDir.$filename, $sNewFilePath, $iWidth, $iHeight, true);

		if ($iRes != 0)
			return false; //resizing was failed

		@chmod($sNewFilePath, 0644);
		$z++;
	}

}
