<?php
include( '../../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_PLUGINS . 'Services_JSON.php' );

$dbhost = $db['host'];
$dbuser = $db['user'];
$dbpass = $db['passwd'];

$conObject = mysql_connect($db['host'], $db['user'], $db['passwd'], true) or die('Error connecting to mysql');
mysql_select_db($db['db'], $conObject);

// CREATE WATERMARK FUNCTION

define('WATERMARK_OVERLAY_OPACITY', 80);
define('WATERMARK_OUTPUT_QUALITY', 90);

function create_watermark($source_file_path, $output_file_path, $overlayimage) {
    list( $source_width, $source_height, $source_type ) = getimagesize($source_file_path);

    if ($source_type === NULL) {
        return false;

    }

    switch ($source_type) {
        case IMAGETYPE_GIF:
            $source_gd_image = imagecreatefromgif($source_file_path);
            break;
        case IMAGETYPE_JPEG:
            $source_gd_image = imagecreatefromjpeg($source_file_path);
            break;
        case IMAGETYPE_PNG:
            $source_gd_image = imagecreatefrompng($source_file_path);
            break;
        default:
            return false;
    }

    $overlay_gd_image = imagecreatefrompng($overlayimage);
    $overlay_width = imagesx($overlay_gd_image);
    $overlay_height = imagesy($overlay_gd_image);
    
$marge_left = 0;
$marge_right = 0;
$marge_bottom = 0;

    imagecopy(
            $source_gd_image,
            $overlay_gd_image,
            $source_width - $overlay_width - $marge_bottom,
            $source_height - $overlay_height - $marge_right,
            0,
            0,
            $overlay_width,
            $overlay_height
          
    );

    imagejpeg($source_gd_image, $output_file_path, WATERMARK_OUTPUT_QUALITY);

    imagedestroy($source_gd_image);
    imagedestroy($overlay_gd_image);
}

// FILE PROCESSING FUNCTION
define('PROCESSED_IMAGE_DESTINATION', 'images/');

function process_image_upload($Source, $Destination, $overlayimage) {

    list(,, $temp_type ) = getimagesize($Source);

    if ($temp_type === NULL) {
        return false;

    }

    switch ($temp_type) {
        case IMAGETYPE_GIF:
            break;
        case IMAGETYPE_JPEG:
            break;
        case IMAGETYPE_PNG:
            break;
        default:
            return false;
    }

    create_watermark($Source, $Destination, $overlayimage);
}

function watermark_apply($Id,$Status) {

$User_name= mysql_fetch_assoc(mysql_query("SELECT `NickName` FROM `Profiles` where `ID`='" . $Id. "'"));
 //   $placededuserthumbnail = mysql_fetch_assoc(mysql_query("SELECT `Avatar` FROM `Profiles` WHERE `ID` = " . $User_name['NickName']));

//echo $User_name;

if ($Status == 'Matched') {

     $placeduser = mysql_fetch_assoc(mysql_query("SELECT `author_id` FROM `watermarkimages` where `author_id`='" . $Id . "'")); 
//echo $placeduser['author_id'];
    if($placeduser['author_id'] == '') {
        
// water mark to not matched profile Thumbails.
    $matcheduserthumbnail = mysql_fetch_assoc(mysql_query("SELECT `Avatar` FROM `Profiles` WHERE `ID` = " . $Id));
    mysql_query("INSERT INTO `watermarkimages` VALUES ('','" . $Id . "', '" . $matcheduserthumbnail['Avatar'] . "', 'Matched')");

    $matcheduserthumbnail_agency = mysql_fetch_assoc(mysql_query("SELECT `ID` FROM `Profiles` WHERE `ID` = " . $Id));
    mysql_query("INSERT INTO `watermarkimages` VALUES ('','" . $Id . "', '" . $matcheduserthumbnail['Avatar'] . "', 'Matched')");
    
    }
   else {
       
       
    $notplaceduser = mysql_fetch_assoc(mysql_query("SELECT `ID` FROM `Profiles` where `NickName`='" . $User_name['NickName'] . "'"));
   $notplacededuserthumbnail = mysql_fetch_assoc(mysql_query("SELECT `Avatar` FROM `Profiles` WHERE `ID` = " . $notplaceduser['ID']));

    mysql_query("DELETE FROM `watermarkimages` where `author_id`=" . $notplaceduser['ID']);


    $notplaceduser_agency = mysql_fetch_assoc(mysql_query("SELECT `ID` FROM `Profiles` where `NickName`='" . $User_name['NickName'] . "'"));
    $notplacededuserthumbnail_agency = mysql_fetch_assoc(mysql_query("SELECT `ID` FROM `Profiles` WHERE `ID` = " . $notplaceduser['ID']));

    mysql_query("DELETE FROM `watermarkimages` where `author_id`=" . $notplaceduser_agency['ID']." and watermarkimage_id = " .$notplaceduser_agency['ID']);
 
       
      // water mark to not matched profile Thumbails.
    $matcheduserthumbnail = mysql_fetch_assoc(mysql_query("SELECT `Avatar` FROM `Profiles` WHERE `ID` = " . $Id));
    mysql_query("INSERT INTO `watermarkimages` VALUES ('','" . $Id . "', '" . $matcheduserthumbnail['Avatar'] . "', 'Matched')");

    $matcheduserthumbnail_agency = mysql_fetch_assoc(mysql_query("SELECT `ID` FROM `Profiles` WHERE `ID` = " . $Id));
    mysql_query("INSERT INTO `watermarkimages` VALUES ('','" . $Id . "', '" . $matcheduserthumbnail['Avatar'] . "', 'Matched')"); 

     } 

    $Source = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/images/' . $matcheduserthumbnail['Avatar'] . '.jpg';
    $Destination = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/images/watermark/' . $matcheduserthumbnail['Avatar'] . '.jpg';
   
    $Source_medium = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/images/' . $matcheduserthumbnail['Avatar'] . 'i.jpg';
    $Destination_medium  = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/images/watermark_medium/' . $matcheduserthumbnail['Avatar'] . 'i.jpg';
  
    $overlayimage = BX_DIRECTORY_PATH_BASE . 'images/icons/Matched_Feb.png';

    //echo $Source ."sdfdfsf". $Destination."jljkl".$overlayimage;exit();


    $Source_large = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/slider/' . $matcheduserthumbnail['Avatar'] . '.jpg';
    $Destination_large = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/images/watermark_larger/' . $matcheduserthumbnail['Avatar'] . '.jpg';

    $overlayimage_large  = BX_DIRECTORY_PATH_BASE . 'images/icons/Matched.png';


    $Source_agency = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/resizedphoto/' . $matcheduserthumbnail_agency['ID'] . '.jpg';
    $Destination_agency = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/images/watermark_agency/' . $matcheduserthumbnail_agency['ID'] . '.jpg';

    $overlayimage_agency  = BX_DIRECTORY_PATH_BASE . 'images/icons/Matched.png';
    
    
    $Source_favourite = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/favourite/' . $matcheduserthumbnail['Avatar'] . '.jpg';
    $Destination_favourite = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/images/watermark_favourite/' . $matcheduserthumbnail['Avatar'] . '.jpg';

    $overlayimage_favourite  = BX_DIRECTORY_PATH_BASE . 'images/icons/Matched.png';

    if (file_exists($Source) && !file_exists($Destination)) {
        copy($Source, $Destination);
        ////taking the back up of original photo in dest and make change to the source by the original pic
    }
    if (file_exists($Source)) {
        process_image_upload($Destination, $Source, $overlayimage);
    }
    
    if (file_exists($Source_medium) && !file_exists($Destination_medium)) {
        copy($Source_medium, $Destination_medium);
        ////taking the back up of original photo in dest and make change to the source by the original pic
    }
    if (file_exists($Source_medium)) {
        process_image_upload($Destination_medium, $Source_medium, $overlayimage);
    }
    
    if (file_exists($Source_large) && !file_exists($Destination_large)) {
	        copy($Source_large, $Destination_large);
	        ////taking the back up of original photo in dest and make change to the source by the original pic
	    }
    if (file_exists($Source_large)) {
	        process_image_upload($Destination_large, $Source_large, $overlayimage_large);
    }

     if (file_exists($Source_favourite) && !file_exists($Destination_favourite)) {
	        copy($Source_favourite, $Destination_favourite);
	        ////taking the back up of original photo in dest and make change to the source by the original pic
	    }
    if (file_exists($Source_favourite)) {
	        process_image_upload($Destination_favourite, $Source_favourite, $overlayimage_favourite);
    }
    
    
    
  if (file_exists($Source_agency) && !file_exists($Destination_agency)) {
	        copy($Source_agency, $Destination_agency);
	        ////taking the back up of original photo in dest and make change to the source by the original pic
	    }
    if (file_exists($Source_agency)) {
	        process_image_upload($Destination_agency, $Source_agency, $overlayimage_agency);
    }



    cleanALL();
} else if ($Status== 'Placed') {

   // water mark to  matched profile Thumbails to Placed profile Thumbnails.
    $placeduser = mysql_fetch_assoc(mysql_query("SELECT `ID` FROM `Profiles` where `NickName`='" . $User_name['NickName'] . "'"));
    $placededuserthumbnail = mysql_fetch_assoc(mysql_query("SELECT `Avatar` FROM `Profiles` WHERE `ID` = " . $placeduser['ID']));
 
     $placeduser = mysql_fetch_assoc(mysql_query("SELECT `author_id` FROM `watermarkimages` where `author_id`='" . $Id . "'"));
     
    if($placeduser['author_id'] != '') {
    $sql = "UPDATE watermarkimages SET status='Placed' WHERE author_id = " . $placeduser['author_id'];
    // $sql = "UPDATE watermarkimages SET status='Placed' WHERE author_id = '769'";
   // echo $sql;

    mysql_query($sql);

    // water mark to  matched profile Thumbails to Placed profile Thumbnails.
    $placeduser_agency = mysql_fetch_assoc(mysql_query("SELECT `ID` FROM `Profiles` where `NickName`='" . $User_name['NickName'] . "'"));
    $placededuserthumbnail_agency = mysql_fetch_assoc(mysql_query("SELECT `Avatar` FROM `Profiles` WHERE `ID` = " . $placeduser_agency['ID']));

    $sql_agency = "UPDATE watermarkimages SET status='Placed' WHERE author_id = " . $placeduser_agency['ID']." and watermarkimage_id = " .$placededuserthumbnail_agency['Avatar'];
    // $sql = "UPDATE watermarkimages SET status='Placed' WHERE author_id = '769'";
   // echo $sql;exit();

    mysql_query($sql_agency);
    }
    
    else {
        
    //     $sql = "UPDATE watermarkimages SET status='Placed' WHERE author_id = " . $placeduser['ID'];
    // $sql = "UPDATE watermarkimages SET status='Placed' WHERE author_id = '769'";
   // echo $sql;
     $placeduserthumbnail = mysql_fetch_assoc(mysql_query("SELECT `Avatar` FROM `Profiles` WHERE `ID` = " . $Id));   
      mysql_query("INSERT INTO `watermarkimages` VALUES ('','" . $Id . "', '" . $placeduserthumbnail['Avatar'] . "', 'Placed')");
  //  mysql_query($sql);

    // water mark to  matched profile Thumbails to Placed profile Thumbnails.
    $placeduser_agency = mysql_fetch_assoc(mysql_query("SELECT `ID` FROM `Profiles` where `NickName`='" . $User_name['NickName'] . "'"));
    $placededuserthumbnail_agency = mysql_fetch_assoc(mysql_query("SELECT `Avatar` FROM `Profiles` WHERE `ID` = " . $placeduser_agency['ID']));

 //   $sql_agency = "UPDATE watermarkimages SET status='placed' WHERE author_id = " . $placeduser_agency['ID']." and watermarkimage_id = " .$placeduser_agency['ID'];
    // $sql = "UPDATE watermarkimages SET status='Placed' WHERE author_id = '769'";
   // echo $sql;exit();

    
   mysql_query("INSERT INTO `watermarkimages` VALUES ('','" . $Id . "', '" . $placeduserthumbnail['Avatar'] . "', 'Placed')"); 
 //   mysql_query($sql_agency);
        
    }

    $Source = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/images/' . $placededuserthumbnail['Avatar'] . '.jpg';
    $Destination = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/images/watermark/' . $placededuserthumbnail['Avatar'] . '.jpg';
    
   $Source_medium = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/images/' . $placededuserthumbnail['Avatar'] . 'i.jpg';
   $Destination_medium  = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/images/watermark_medium/' . $placededuserthumbnail['Avatar'] . 'i.jpg';
        
    
    $overlayimage = BX_DIRECTORY_PATH_BASE . 'images/icons/placed_Feb.png';


    $Source_large = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/slider/' . $placededuserthumbnail['Avatar'] . '.jpg';
    $Destination_large = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/images/watermark_larger/' . $placededuserthumbnail['Avatar'] . '.jpg';
    $overlayimage_large = BX_DIRECTORY_PATH_BASE . 'images/icons/placed.png';

    $Source_agency = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/resizedphoto/' . $placededuserthumbnail_agency['ID'] . '.jpg';
    $Destination_agency = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/images/watermark_agency/' . $placededuserthumbnail_agency['ID'] . '.jpg';
    $overlayimage_agency = BX_DIRECTORY_PATH_BASE . 'images/icons/placed.png';


    $Source_favourite = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/favourite/' . $placededuserthumbnail['Avatar'] . '.jpg';
    $Destination_favourite = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/images/watermark_favourite/' . $placededuserthumbnail['Avatar'] . '.jpg';

    $overlayimage_favourite  = BX_DIRECTORY_PATH_BASE . 'images/icons/placed.png';
    
    
    if (file_exists($Source) && !file_exists($Destination)) {
        //taking original photo from dest and make change to the source pic
        copy($Source, $Destination);
    }
    if (file_exists($Source)) {
        process_image_upload($Destination, $Source, $overlayimage);
    }

   if (file_exists($Source_medium) && !file_exists($Destination_medium)) {
        //taking original photo from dest and make change to the source pic
        copy($Source_medium, $Destination_medium);
    }
    if (file_exists($Source_medium)) {
        process_image_upload($Destination_medium, $Source_medium, $overlayimage);
    }
    
    
    
   if (file_exists($Source_large) && !file_exists($Destination_large)) {
	        copy($Source_large, $Destination_large);
	        ////taking the back up of original photo in dest and make change to the source by the original pic
	    }
    if (file_exists($Source_large)) {
	        process_image_upload($Destination_large, $Source_large, $overlayimage_large);
    }

   if (file_exists($Source_agency) && !file_exists($Destination_agency)) {
	        copy($Source_agency, $Destination_agency);
	        ////taking the back up of original photo in dest and make change to the source by the original pic
	    }
    if (file_exists($Source_agency)) {
	        process_image_upload($Destination_agency, $Source_agency, $overlayimage_agency);
    }

  if (file_exists($Source_favourite) && !file_exists($Destination_favourite)) {
	        copy($Source_favourite, $Destination_favourite);
	        ////taking the back up of original photo in dest and make change to the source by the original pic
	    }
    if (file_exists($Source_favourite)) {
	        process_image_upload($Destination_favourite, $Source_favourite, $overlayimage_favourite);
    }
    
    cleanALL();
} else {

    // to revert the watermark to thumbnail

    $notplaceduser = mysql_fetch_assoc(mysql_query("SELECT `ID` FROM `Profiles` where `NickName`='" . $User_name['NickName'] . "'"));
    $notplacededuserthumbnail = mysql_fetch_assoc(mysql_query("SELECT `Avatar` FROM `Profiles` WHERE `ID` = " . $notplaceduser['ID']));

    mysql_query("DELETE FROM `watermarkimages` where `author_id`=" . $notplaceduser['ID']);


    $notplaceduser_agency = mysql_fetch_assoc(mysql_query("SELECT `ID` FROM `Profiles` where `NickName`='" . $User_name['NickName'] . "'"));
    $notplacededuserthumbnail_agency = mysql_fetch_assoc(mysql_query("SELECT `Avatar` FROM `Profiles` WHERE `ID` = " . $notplaceduser['ID']));

    mysql_query("DELETE FROM `watermarkimages` where `author_id`=" . $notplaceduser_agency['ID']." and watermarkimage_id = " .$notplacededuserthumbnail_agency['Avatar']);



    $Source = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/images/' . $notplacededuserthumbnail['Avatar'] . '.jpg';
    $Destination = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/images/watermark/' . $notplacededuserthumbnail['Avatar'] . '.jpg';
    
    $Source_medium = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/images/' . $notplacededuserthumbnail['Avatar'] . 'i.jpg';
    $Destination_medium  = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/images/watermark_medium/' . $notplacededuserthumbnail['Avatar'] . 'i.jpg';
        

    $Source_large = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/slider/' . $notplacededuserthumbnail['Avatar'] . '.jpg';
    $Destination_large = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/images/watermark_larger/' . $notplacededuserthumbnail['Avatar'] . '.jpg';

    $Source_agency = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/resizedphoto/' . $notplaceduser_agency['ID'] . '.jpg';
    $Destination_agency = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/images/watermark_agency/' . $notplaceduser_agency['ID'] . '.jpg';

    $Source_favourite = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/favourite/' . $notplacededuserthumbnail['Avatar'] . '.jpg';
    $Destination_favourite = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/images/watermark_favourite/' . $notplacededuserthumbnail['Avatar'] . '.jpg';

    if (file_exists($Destination)) {
        copy($Destination, $Source);
    }
        
    if (file_exists($Destination_medium)) {
        copy($Destination_medium, $Source_medium);
    }

    if (file_exists($Destination_large)) {
	        copy($Destination_large, $Source_large);
    }
      if (file_exists($Destination_favourite)) {
	        copy($Destination_favourite, $Source_favourite);
    }

    if (file_exists($Destination_agency)) {
	        copy($Destination_agency, $Source_agency);
    }

    cleanALL();
}
}

$profileid = getLoggedId();

//$currenturi = mysql_fetch_assoc(mysql_query("SELECT `uri` FROM  `bx_groups_main` WHERE  `author_id` = $profileid"));

//$referer = $GLOBALS['site']['url'].'m/groups/browse_fans_list/'.$currenturi['uri'];
//$referer = 'http://www.parentfinder.com/m/groups/browse_fans_list/CAIRS-Agency';
//echo $referer;exit();
//$referer = $_SERVER['HTTP_REFERER'];
//header("Location: " . $referer );
//exit();
function cleanALL() {
    $aResult = array();
    //member menu
    bx_import('BxDolMemberMenu');
    $oMemberMenu = new BxDolMemberMenu();
    $oMemberMenu->deleteMemberMenuCaches();

    // page blocks
    bx_import('BxDolPageViewAdmin');
    $oPageViewCacher = new BxDolPageViewCacher('', '');
    $oCachePb = $oPageViewCacher->getBlocksCacheObject();
    $aResult = clearCacheObject($oCachePb, 'pb_');
    if ($aResult['code'] != 0)
        break;

    // users
    $aResult = clearCache('user', BX_DIRECTORY_PATH_CACHE);
    if ($aResult['code'] != 0)
        break;

    // DB
    $oCacheDb = $GLOBALS['MySQL']->getDbCacheObject();
    $aResult = clearCacheObject($oCacheDb, 'db_');
    if ($aResult['code'] != 0)
        break;

    // templates
    $oCacheTemplates = $GLOBALS['oSysTemplate']->getTemplatesCacheObject();
    $aResult = clearCacheObject($oCacheTemplates, $GLOBALS['oSysTemplate']->_sCacheFilePrefix);
    if ($aResult['code'] != 0)
        break;

    // CSS
    $aResult = clearCache($GLOBALS['oSysTemplate']->_sCssCachePrefix, BX_DIRECTORY_PATH_CACHE_PUBLIC);
    if ($aResult['code'] != 0)
        break;

    // JS
    $aResult = clearCache($GLOBALS['oSysTemplate']->_sJsCachePrefix, BX_DIRECTORY_PATH_CACHE_PUBLIC);
    return $aResult;
}

function clearCacheObject($oCache, $sPrefix) {
    if (!$oCache->removeAllByPrefix($sPrefix))
        return array('code' => 1, 'message' => _t('_adm_txt_dashboard_cache_clean_failed'));
    else
        return array('code' => 0, 'message' => _t('_adm_txt_dashboard_cache_clean_success'));
}

function clearCache($sPrefix, $sPath) {
    $aResult = array('code' => 0, 'message' => _t('_adm_txt_dashboard_cache_clean_success'));

    if ($rHandler = opendir($sPath)) {
        while (($sFile = readdir($rHandler)) !== false)
            if (substr($sFile, 0, strlen($sPrefix)) == $sPrefix)
                @unlink($sPath . $sFile);
    }
    else
        $aResult = array('code' => 1, 'message' => _t('_adm_txt_dashboard_cache_clean_failed'));

    return $aResult;
}
?>