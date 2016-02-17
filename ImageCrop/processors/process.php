<?php
/**
 * C:\Users\msi1308\AppData\Roaming\Sublime Text 2\Packages/PhpTidy/phptidy-sublime-buffer.php
 *
 * @author Aravind Buddha <aravind.buddha@mediaus.com>
 * @package default
 */


require_once( '../../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'images.inc.php');
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolAlbums.php');
require_once( BX_DIRECTORY_PATH_ROOT. 'ProfilebuilderComponent/processors/watermark.php');

include 'imagetool.class.php';


	
	$dir_root =BX_DIRECTORY_PATH_ROOT;
	$site_url=BX_DOL_URL_ROOT;	
	$avatar_dir = $site_url."modules/boonex/avatar/data/avatarphotos/";
    $temp=$dir_root."ImageCrop/temp/";
	

	$uploadPath=$avatar_dir;
	$avatar_id=getAvatarId($profile_id);
	$pic_300= $avatar_id.".jpg";
 	$fav_dir    = $dir_root."modules/boonex/avatar/data/favourite_sample/";
 	$img_dir    = $dir_root."modules/boonex/avatar/data/images_sample/";


 	extract($_REQUEST);
    // $profile_id=$_REQUEST['profile_id'];
	// $x=$_REQUEST['x'];
	// $y=$_REQUEST['y'];
	// $w=$_REQUEST['w'];
	// $h=$_REQUEST['h'];

	$profile_img= $profile_id.".jpg";
	echo $site_url."ImageCrop/temp/".$img;
	//Creating Temp image with new height,widht in tmp folder
	$imgTObj = new ImageTool($site_url."ImageCrop/temp/".$img);
	$imgTObj -> resizeImage($w, $h, 'crop', $x, $y);
	$imgTObj -> saveImage($temp.$profile_img, 100);
	@chmod($temp.$profile_img, 0644);

	//creating img for
 //    @copy($uploadPath.$profile_img, $AvatarDir.$profile_id.'.jpg');
	// @chmod($AvatarDir.$profile_id.'.jpg', 0644);
 	
 	$avatar_id=getAvatarId($profile_id);
 	imageResize($temp.$profile_id.".jpg", $fav_dir.$avatar_id.'.jpg', 300, 250, true);
 	imageResize($temp.$profile_id.".jpg", $img_dir.$avatar_id.'.jpg', 64, 64, true);
 	imageResize($temp.$profile_id.".jpg", $img_dir.$avatar_id.'i.jpg', 32, 32, true);
 	
    // $match_status= getMatchStatu();
   
    $matchstatus =getMatchStatus($profile_id);
 
	echo $avatar_id;  
    if($matchstatus != '') {     
	 	echo $profile_id;  
	    echo $matchstatus;  
        watermark_apply($profile_id,$matchstatus);
    }

 	@chmod($fav_dir.$avatar_id.'.jpg', 0644);
	@chmod($img_dir.$avatar_id.'.jpg', 0644);
	@chmod($img_dir.$avatar_id.'i.jpg', 0644);
 
  // @copy($img_dir.$pic_32, $fav_dir.$iAvatar.'.jpg');
	
	//Adding the cropped orginal photo to the Profile Picture album
	addToProfiles($profile_id);

	 // print_r('{"state": true, "name":"'.str_replace("'", "\\'", $profile_id).'"}');

function getMatchStatus($profile_id){
   $q="SELECT status FROM watermarkimages WHERE author_id =". $profile_id;
	$r=mysql_query($q);  
	while ($_row=mysql_fetch_array($r)) {

	  	return $_row[0];
	}
	return "";
}
//get New Avatar Id
function getAvatarId($profile_id){
	//Insert into avatar_images and update Profiels table
  $q= "SELECT Avatar from Profiles where ID=".$profile_id;
  $r=mysql_query($q);  
  while ($_row=mysql_fetch_array($r)) {
  	return $_row[0];
  }
  // return $iAvatar;
}

function addToProfiles($profile_id){
	$album=new BxDolAlbums;
	$objlist=$album->getAlbumObjList(getProfilePicAlbumId($profile_id));
	//check if photo exits or not
	if(in_array($profile_id, $objlist)){
		return ;
	}
	else{
	$albumid=getProfilePicAlbumId($profile_id);
	  $album->BxDolAlbums("bx_photos", $profile_id);
	  $album->addObject($albumid, $profile_id);
	}
	return; 
}
function getProfilePicAlbumId($profile_id){
	$sql=
  	"SELECT ID 
  		FROM sys_albums 
  		WHERE  Caption =  'Profile Pictures'
  		AND `Type` LIKE 'bx_photos' 
  		AND `Owner` = $profile_id";
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
