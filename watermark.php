<?php
include( 'inc/header.inc.php' );
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

    imagecopymerge(
            $source_gd_image,
            $overlay_gd_image,
            2,
            $source_height - $overlay_height,
            0,
            0,
            $overlay_width,
            $overlay_height,
            WATERMARK_OVERLAY_OPACITY
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

if ($_GET['status'] == 'matched') {
// water mark to not matched profile Thumbails.
    $matcheduserthumbnail = mysql_fetch_assoc(mysql_query("SELECT `Avatar` FROM `Profiles` WHERE `ID` = " . $_GET['id']));
    mysql_query("INSERT INTO `watermarkimages` VALUES ('','" . $_GET['id'] . "', '" . $matcheduserthumbnail['Avatar'] . "', 'Matched')");


    $Source = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/slider/' . $matcheduserthumbnail['Avatar'] . '.jpg';
    $Destination = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/images/watermark_larger/' . $matcheduserthumbnail['Avatar'] . '.jpg';

       $overlayimage = BX_DIRECTORY_PATH_BASE . 'images/icons/Matched.png';

    //echo $Source ."sdfdfsf". $Destination."jljkl".$overlayimage;exit();



    if (file_exists($Source) && !file_exists($Destination)) {
        copy($Source, $Destination);
        ////taking the back up of original photo in dest and make change to the source by the original pic
    }
    if (file_exists($Source)) {
        process_image_upload($Destination, $Source, $overlayimage);
    }
    cleanALL();
} else if ($_GET['status'] == 'placed') {


    // water mark to  matched profile Thumbails to Placed profile Thumbnails.
    $placeduser = mysql_fetch_assoc(mysql_query("SELECT `ID` FROM `Profiles` where `NickName`='" . $_GET['name'] . "'"));
    $placededuserthumbnail = mysql_fetch_assoc(mysql_query("SELECT `Avatar` FROM `Profiles` WHERE `ID` = " . $placeduser['ID']));

    $sql = "UPDATE watermarkimages SET status='Placed' WHERE author_id = " . $placeduser['ID'];
    // $sql = "UPDATE watermarkimages SET status='Placed' WHERE author_id = '769'";
    //echo $sql;


    mysql_query($sql);

     $Source = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/slider/' . $placededuserthumbnail['Avatar'] . '.jpg';
     $Destination = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/images/watermark_larger/' . $placededuserthumbnail['Avatar'] . '.jpg';
    $overlayimage = BX_DIRECTORY_PATH_BASE . 'images/icons/placed.png';
 
    if (file_exists($Source) && !file_exists($Destination)) {
        //taking original photo from dest and make change to the source pic
        copy($Source, $Destination);
    }
    if (file_exists($Source)) {
        process_image_upload($Destination, $Source, $overlayimage);
    }

    cleanALL();
} else {

    // to revert the watermark to thumbnail

    $notplaceduser = mysql_fetch_assoc(mysql_query("SELECT `ID` FROM `Profiles` where `NickName`='" . $_GET['name'] . "'"));
    $notplacededuserthumbnail = mysql_fetch_assoc(mysql_query("SELECT `Avatar` FROM `Profiles` WHERE `ID` = " . $notplaceduser['ID']));

    mysql_query("DELETE FROM `watermarkimages` where `author_id`=" . $notplaceduser['ID']);

    $Source = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/slider/' . $notplacededuserthumbnail['Avatar'] . '.jpg';
    $Destination = BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/data/images/watermark_larger/' . $notplacededuserthumbnail['Avatar'] . '.jpg';
    if (file_exists($Destination)) {
        copy($Destination, $Source);
    }
    cleanALL();
}


$profileid = getLoggedId();

$currenturi = mysql_fetch_assoc(mysql_query("SELECT `uri` FROM  `bx_groups_main` WHERE  `author_id` = $profileid"));

$referer = $GLOBALS['site']['url'].'m/groups/browse_fans_list/'.$currenturi['uri'];
//$referer = 'http://www.parentfinder.com/m/groups/browse_fans_list/CAIRS-Agency';
//echo $referer;exit();
//$referer = $_SERVER['HTTP_REFERER'];
header("Location: " . $referer );
exit();
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