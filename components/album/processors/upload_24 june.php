<?php

//!! IMPORTANT:
//!! this file is just an example, it doesn't incorporate any security checks and
//!! is not recommended to be used in production environment as it is. Be sure to
//!! revise it and customize to your needs.
// Make sure file is not cached (as it happens for example on iOS devices)


/*
  // Support CORS
  header("Access-Control-Allow-Origin: *");
  // other CORS headers if any...
  if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  exit; // finish preflight CORS requests here
  }
 */

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

define('BX_PROFILE_PAGE', 1);
require_once( '../../../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolAlbums.php');

// 5 minutes execution time
@set_time_limit(5 * 60);

// Uncomment this one to fake upload time
usleep(5000);
// Settings
$root = "{$dir['root']}";
$type = $_REQUEST['type'];
$albumid = $_REQUEST['albumID'];


if ($type == "photo") {
    $targetDir = "$root/modules/boonex/photos/data/files/";
} else {
    $targetDir = "$root/flash/modules/video/files/";
}
//$targetDir = './uploads';
$cleanupTargetDir = true; // Remove old files
$maxFileAge = 5 * 3600; // Temp file age in seconds
// Create target dir
if (!file_exists($targetDir)) {
    @mkdir($targetDir);
}

// Get a file name
if (isset($_REQUEST["name"])) {
    $fileName = $_REQUEST["name"];
} elseif (!empty($_FILES)) {
    $fileName = $_FILES["file"]["name"];
} else {
    $fileName = uniqid("file_");
}

$time = strtotime("now");

$fileName = htmlspecialchars(trim($fileName));
$fileName = str_replace(" ", "_", $fileName);
$fileName = str_replace(",", "_", $fileName);
$fileName = str_replace("-", "_", $fileName);
$fileName = str_replace("__", "_", $fileName);
$fileName = preg_replace('/[^a-z0-9.]/i', '_', $fileName);

$fileNameA = explode('.', $fileName);

$fileName = $fileNameA[0].'_'.$time.'.'.$fileNameA[1];

//print_r($fileName);

$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;


// Chunking might be enabled
$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;


// Remove old temp files
if ($cleanupTargetDir) {
    if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
    }

    while (($file = readdir($dir)) !== false) {
        $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

        // If temp file is current file proceed to the next
        if ($tmpfilePath == "{$filePath}.part") {
            continue;
        }

        // Remove temp file if it is older than the max age and is not the current file
        if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
            @unlink($tmpfilePath);
        }
    }
    closedir($dir);
}


// Open temp file
if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
    die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
}

if (!empty($_FILES)) {
    if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
    }

    // Read binary input stream and append it to temp file
    if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
    }
} else {
    if (!$in = @fopen("php://input", "rb")) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
    }
}

while ($buff = fread($in, 4096)) {
    fwrite($out, $buff);
}

@fclose($out);
@fclose($in);

// Check if file has been uploaded
if (!$chunks || $chunk == $chunks - 1) {
    // Strip the temp .part suffix off
    rename("{$filePath}.part", $filePath);
}

// Return Success JSON-RPC response
// die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');





if ($type == "photo") {
    processimage($targetDir, $fileName);
} else if ($type == "video") {
    if ($_REQUEST['convert']) {
        $albumid = $_GET['albumid'];
        $file = htmlspecialchars(trim($_GET['files']));
        $file = htmlspecialchars(trim($file));
        $file = str_replace(" ", "_", $file);
        $file = str_replace(",", "_", $file);
        $file = str_replace("-", "_", $file);
        $file = str_replace("__", "_", $file);
        $file = preg_replace('/[^a-z0-9.]/i', '_', $file);
        processvideo($root, $albumid, $file);
    }
}

/**
 *
 *
 * @param unknown $targetDir
 * @return unknown
 */
function processimage($targetDir, $fileName) {
    $albumid = $_REQUEST['albumID'];
    // $logged = getLoggedId();
    $Uri = str_replace(' ', '-', $_FILES['file']['name']);
    $sMediaDir = $targetDir;

    $profileinfomration = getprofileinfo();
    $profileinfomration['AdoptionAgency'];

    $rand = date('Y-m-d H:i:s');
    $status = "pending";
    $finaluri = $Uri . '-' . $rand;
    $loggedID = db_arr("select Owner from sys_albums where ID= " . $albumid);

    // echo mysql_query("SELECT ID FROM  Profiles JOIN bx_groups_moderation WHERE Profiles.ID= ".$_COOKIE['memberID']." AND Profiles.AdoptionAgency = bx_groups_moderation.GroupId AND bx_groups_moderation.ApproveStatus= 'on' AND bx_groups_moderation.Type = 'photo'";
    if (@mysql_num_rows(mysql_query("SELECT ID FROM  Profiles JOIN bx_groups_moderation WHERE Profiles.ID= " . $loggedID['Owner'] . " AND Profiles.AdoptionAgency = bx_groups_moderation.GroupId AND bx_groups_moderation.ApproveStatus= 'on' AND bx_groups_moderation.Type = 'photo'"))) {
        $status = "approved";
    }
    $sql = "INSERT INTO bx_photos_main
            (`Owner`, `Ext`, `Size`, `Title`, `Uri`, `Desc`, `Date`, `Status`, `Hash`)
        VALUES ('" . $loggedID['Owner'] . "', 'jpg', '" . $_FILES['file']['size'] . "', '', '$finaluri', '', '" . time() . "', '" . $status . "', '" . md5(microtime()) . "')";

    $result = mysql_query($sql);
    $lastId = mysql_insert_id();


    $album = new BxDolAlbums;
    $album->BxDolAlbums("bx_photos", $loggedID['Owner']);
    $album->addObject($albumid, $lastId);
    $new_order =  mysql_fetch_array(mysql_query("SELECT MAX(`obj_order`) as max_order FROM sys_albums_objects WHERE  `id_album` = $albumID"));
    //print_r($new_order);
    //echo "UPDATE 'sys_albums_objects' SET 'obj_order'= ".($new_order[0] + 1)." WHERE 'id_album'= $albumID AND 'id_object'= $lastId";
    echo mysql_query("UPDATE sys_albums_objects SET obj_order= ".($new_order[0] + 1)." WHERE id_album= $albumID AND id_object= $lastId");
	

    // if($status=="pending"){
    //     $rEmailTemplate = new BxDolEmailTemplates();
    //     $aTemplate = $rEmailTemplate -> getTemplate( 'Photo_add_approval' ) ;
    //     $aPlus = array ('AlbumTitle' => $albumCap['Caption']);
    //     $aAgencyEmail=db_arr("SELECT Profiles.* FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =".$_COOKIE['memberID']." AND Profiles.AdoptionAgency=bx_groups_main.id )");
    //     If (!(mysql_num_rows(mysql_query("SELECT id FROM bx_groups_main WHERE  author_id=".$_COOKIE['memberID'])))){}
    //     sendMail( $aAgencyEmail['Email'], $aTemplate['Subject'], $aTemplate['Body'], $_COOKIE['memberID'],$aPlus );
    // }else{
    //     $rEmailTemplate = new BxDolEmailTemplates();
    //     $aTemplate = $rEmailTemplate -> getTemplate( 'Photo_add_notification' ) ;
    //       $aPlus = array ('AlbumTitle' => $albumCap['Caption']);
    //     $aAgencyEmail=db_arr("SELECT Profiles.* FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =".$_COOKIE['memberID']." AND Profiles.AdoptionAgency=bx_groups_main.id )");
    //     If (!(mysql_num_rows(mysql_query("SELECT id FROM bx_groups_main WHERE  author_id=".$_COOKIE['memberID'])))){}
    //     sendMail( $aAgencyEmail['Email'], $aTemplate['Subject'], $aTemplate['Body'], $_COOKIE['memberID'],$aPlus );
    // }
    // $q1 = "select NickName from Profiles where id = $logged";
    // $result1 = mysql_query($q1);
    // $row1 = mysql_fetch_array($result1);
    // $albumname = $row1['NickName'] . "''s home photos";
    // $q2 = "select ID from sys_albums where Caption = '$albumname'";
    // $result2 = mysql_query($q2);
    // $row2 = mysql_fetch_array($result2);
    // $albumid = $row2['ID'];
    // $sql3 = "UPDATE `sys_albums_objects` SET `obj_order` = `obj_order` + 1 WHERE `id_album` = " . $albumid;
    // $result3 = mysql_query($sql3);
    // $sql4 = "INSERT INTO sys_albums_objects SET id_object = $lastId,
    // `obj_order` = 0,
    // id_album = $albumid";
    // $result4 = mysql_query($sql4);
    // $sql5 = "UPDATE `sys_albums` SET `ObjCount` = `ObjCount` + 1 WHERE `ID` = " . $albumid;
    // $result5 = mysql_query($sql5);

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
        $iRes = imageResize($_FILES["file"]["tmp_name"], $sNewFilePath, $iWidth, $iHeight, true);

        if ($iRes != 0)
            return false; //resizing was failed

        @chmod($sNewFilePath, 0644);
        $z++;
    }
//	echo "UPDATE bx_photos_main SET Uri= '".$fileName."' WHERE ID=$lastId";
	echo mysql_query("UPDATE bx_photos_main SET Uri= '".$fileName."' WHERE ID=$lastId");

    print_r("{state: true}");

    if (@mysql_num_rows(mysql_query("SELECT ID FROM  Profiles JOIN bx_groups_moderation WHERE Profiles.ID= " . $loggedID['Owner'] . " AND Profiles.AdoptionAgency = bx_groups_moderation.GroupId AND bx_groups_moderation.ApproveStatus= 'on' AND bx_groups_moderation.Type = 'photo'"))) {
        $mFlag = true;
    } else {
        $mFlag = false;
    }

    if (getParam('bx_photos_activation') == 'on' || $mFlag) {
        $bAutoActivate = true;
        $sStatus = 'approved';
    } else {
        $bAutoActivate = false;
        $sStatus = 'pending';
    }

    $albumCap = db_arr("SELECT Caption FROM sys_albums WHERE ID = '" . $albumid . "'");
    if ($sStatus == "pending") {
        $rEmailTemplate = new BxDolEmailTemplates();
        $aTemplate = $rEmailTemplate->getTemplate('Photo_add_approval');
        $aPlus = array('AlbumTitle' => $albumCap['Caption']);
        $aAgencyEmail = db_arr("SELECT Profiles.* FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =" . $_COOKIE['memberID'] . " AND Profiles.AdoptionAgency=bx_groups_main.id )");
        If (!(mysql_num_rows(mysql_query("SELECT id FROM bx_groups_main WHERE  author_id=" . $loggedID['Owner'])))) {
            
        }
        sendMail($aAgencyEmail['Email'], $aTemplate['Subject'], $aTemplate['Body'], $loggedID['Owner'], $aPlus);
    } else {
        $rEmailTemplate = new BxDolEmailTemplates();
        $aTemplate = $rEmailTemplate->getTemplate('Photo_add_notification');
        $aPlus = array('AlbumTitle' => $albumCap['Caption']);
        $aAgencyEmail = db_arr("SELECT Profiles.* FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =" . $_COOKIE['memberID'] . " AND Profiles.AdoptionAgency=bx_groups_main.id )");
        If (!(mysql_num_rows(mysql_query("SELECT id FROM bx_groups_main WHERE  author_id=" . $loggedID['Owner'])))) {
            
        }
        sendMail($aAgencyEmail['Email'], $aTemplate['Subject'], $aTemplate['Body'], $loggedID['Owner'], $aPlus);
    }
}

function getOS() {

    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $os_platform = "Unknown OS Platform";

    $os_array = array(
        '/windows nt 6.3/i' => 'Windows 8.1',
        '/windows nt 6.2/i' => 'Windows 8',
        '/windows nt 6.1/i' => 'Windows 7',
        '/windows nt 6.0/i' => 'Windows Vista',
        '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i' => 'Windows XP',
        '/windows xp/i' => 'Windows XP',
        '/windows nt 5.0/i' => 'Windows 2000',
        '/windows me/i' => 'Windows ME',
        '/win98/i' => 'Windows 98',
        '/win95/i' => 'Windows 95',
        '/win16/i' => 'Windows 3.11',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i' => 'Mac OS 9',
        '/linux/i' => 'Linux',
        '/ubuntu/i' => 'Ubuntu',
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile'
    );

    foreach ($os_array as $regex => $value) {

        if (preg_match($regex, $user_agent)) {
            $os_platform = $value;
        }
    }

    return $os_platform;
}

/**
 *
 *
 * @param unknown $root
 */
function processvideo($root, $albumid, $file) {
    // $logged = getLoggedId();
    $Uri = str_replace(' ', '-', $file);
    $Uri = str_replace('_', '-', $file);
    $Uri = str_replace('.', '-', $file);
    // $today = strtotime('today UTC');
    $today = time();
    $rand = date('Y-m-d H:i:s');
    $status = "pending";
    $finaluri = $Uri . '-' . $rand;
    $loggedID = db_arr("select Owner from sys_albums where ID= " . $albumid);

    $fileName = $file;
    if (@mysql_num_rows(mysql_query("SELECT ID FROM  Profiles JOIN bx_groups_moderation WHERE Profiles.ID= " . $loggedID['Owner'] . " AND Profiles.AdoptionAgency = bx_groups_moderation.GroupId AND bx_groups_moderation.ApproveStatus= 'on' AND bx_groups_moderation.Type = 'video'"))) {
        $status = "approved";
    }


    $sql = "INSERT INTO RayVideoFiles SET
    Title= '' ,
    Uri = '$finaluri',
    Tags= ' $fileName ',
    Description = '',
    Time=" . time() . ",
    Date = $today,
    Owner= '" . $loggedID['Owner'] . "',
    Status = '$status'";

    $result = mysql_query($sql);
    $lastId = mysql_insert_id();
    $filename = $lastId;

    /*     * ****************************************************** */
    /* File format conversion */
    $inputFile = $file;
    $outputFile = "_" . $lastId;
    $appRoot = $root;
    $mediaPath = $appRoot . "flash/modules/video/files/";
    $vFormat = "mp4";
//    $vFormat = "flv";
    //aravind:finding os and loading ffmpeg accordingly
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $FfmpegPath = $appRoot . 'ProfilebuilderComponent/ffmpeg/ffmpeg';
        $ffprobe = $appRoot . 'ProfilebuilderComponent/ffmpeg/ffprobe';
    } else {
        $FfmpegPath = 'ffmpeg';
        $ffprobe = "ffprobe";
    }

    $OutputVideo = $outputFile . "." . $vFormat;
//    $Input = ' -y -i "' . $inputFile . '"';
    $Input = ' -i "' . $inputFile . '"';
    //$Options = " -ab 56 -ar 44100 -b 512k -r 15 -s 320x240 -f " . $vFormat . " "; // -vcodec flv
    // to solve the iphone orientation
    $array_phones = array('iPhone', 'iPod', 'iPad');
    $getOS = getOS();
    // to solve the iphone orientation
    chdir($mediaPath);
    @set_time_limit(1000);

    exec($ffprobe . " -show_streams $inputFile", $outData);
    foreach ($outData as $key => $value) {
        if (strpos($value, 'width=') !== false) {
            $inputWidth = str_replace('width=', '', $value);
        }
        if (strpos($value, 'height=') !== false) {
            $inputHeight = str_replace('height=', '', $value);
        }
    }
    $aspRatioVal = getAspectRatio($inputWidth, $inputHeight);
    $aspRatioWidth = $inputWidth / $aspRatioVal;
    $aspRatioHeight = $inputHeight / $aspRatioVal;
    $outputResPra = 480 / $aspRatioWidth;
    $outputResPra = round($outputResPra);
    $outputWidth = $aspRatioWidth * $outputResPra;
    $outputHeight = $aspRatioHeight * $outputResPra;

    if (in_array($getOS, $array_phones)) {
        $Options = " -vfilters rotate=90 -ab 56 -ar 44100 -b 512k -r 15 -s 320x240 -f " . $vFormat . " "; // -vcodec flv
        $CommandLimg = $FfmpegPath . " -itsoffset 10 -i " . $OutputVideo . "  -vfilters rotate=90 -an -ss 00:00:03 -an -r 1 -vframes 1 -y " . $outputFile . ".jpg";
        $CommandSimg = $FfmpegPath . " -itsoffset 10 -i " . $OutputVideo . " -vfilters rotate=90 -an -ss 00:00:03 -an -r 1 -vframes 1 -s 220x170 -y " . $outputFile . "_small.jpg";
    } else {
//        $Options = " -ab 56 -ar 44100 -b 512k -r 15 -s " . $outputWidth . "x$outputHeight -f " . $vFormat . " "; // -vcodec flv
//        $Options = " -vcodec copy -acodec copy -s " . $outputWidth . "x$outputHeight "; // -vcodec flv
        $Options = " -c:v libx264 -preset slow -crf 18 -c:a libvorbis -q:a 5 -pix_fmt yuv420p "; // -vcodec flv
//        $Options = " -c:v libx264 -c:a libfaac -r 30 ";
        $CommandLimg = $FfmpegPath . " -i " . $OutputVideo . " -ss 00:00:03 -f image2 -vframes 1 " . $outputFile . ".jpg";
        $CommandSimg = $FfmpegPath . " -i " . $OutputVideo . " -ss 00:00:03 -f image2 -vframes 1 -s 220x170 -y " . $outputFile . "_small.jpg";
    }

    $Command = $FfmpegPath . " " . $Input . $Options . $OutputVideo;
    @exec($Command, $outData, $return_var);
    if ($return_var != 0) {
        @unlink($inputFile);
        echo 'fail';
        exit;
    } else {
        @exec($CommandLimg, $outData);
        @exec($CommandSimg, $outData);

        @unlink($inputFile);
        array_map('unlink', glob("file_*"));

        /* End File format conversion */
        $album = new BxDolAlbums("bx_videos", $loggedID['Owner']);
        $album->BxDolAlbums("bx_videos", $loggedID['Owner']);
        $album->addObject($albumid, $lastId);
	$new_order =  mysql_fetch_array(mysql_query("SELECT MAX(`obj_order`) as max_order FROM sys_albums_objects WHERE  `id_album` = $albumid"));
        echo "UPDATE sys_albums_objects SET obj_order= ".($new_order[0] + 1)." WHERE id_album= $albumid AND id_object= $lastId";
	echo mysql_query("UPDATE sys_albums_objects SET obj_order= ".($new_order[0] + 1)." WHERE id_album= $albumid AND id_object= $lastId");

        // START - Emailing  to agency   


        $_getOwnerID = db_arr("SELECT Uri,Owner FROM RayVideoFiles WHERE ID = " . $lastId);

        if (@mysql_num_rows(mysql_query("SELECT ID FROM  Profiles JOIN bx_groups_moderation WHERE Profiles.ID= " . $_getOwnerID['Owner'] . " AND Profiles.AdoptionAgency = bx_groups_moderation.GroupId AND bx_groups_moderation.ApproveStatus= 'on' AND bx_groups_moderation.Type = 'video'"))) {
            $mFlag = true;
        } else {
            $mFlag = false;
        }

        $sAutoApprove = (getSettingValue($sModule, "autoApprove") == TRUE_VAL || $mFlag) ? STATUS_APPROVED : STATUS_DISAPPROVED;

        //  $sAutoApprove = getSettingValue($sModule, "autoApprove") == true ? STATUS_APPROVED : STATUS_DISAPPROVED;

        /* Notification Start */
        if ($sAutoApprove == "disapproved") {
            $rEmailTemplate = new BxDolEmailTemplates();
            $aTemplate = $rEmailTemplate->getTemplate('Video_add_approval');
            $aPlus = array('VideoUri' => $_getOwnerID['Uri']);
            $aAgencyEmail = db_arr("SELECT Profiles.* FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =" . $_getOwnerID['Owner'] . " AND Profiles.AdoptionAgency=bx_groups_main.id )");
            If (!(mysql_num_rows(mysql_query("SELECT id FROM bx_groups_main WHERE  author_id=" . $_getOwnerID['Owner'])))) {
                
            }
            sendMail($aAgencyEmail['Email'], $aTemplate['Subject'], $aTemplate['Body'], $_getOwnerID['Owner'], $aPlus);
        } else {
            $rEmailTemplate = new BxDolEmailTemplates();
            $aTemplate = $rEmailTemplate->getTemplate('Video_add_notification');
            $aPlus = array('VideoUri' => $_getOwnerID['Uri']);
            $aAgencyEmail = db_arr("SELECT Profiles.* FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =" . $_getOwnerID['Owner'] . " AND Profiles.AdoptionAgency=bx_groups_main.id )");
            If (!(mysql_num_rows(mysql_query("SELECT id FROM bx_groups_main WHERE  author_id=" . $_getOwnerID['Owner'])))) {
                
            }
            sendMail($aAgencyEmail['Email'], $aTemplate['Subject'], $aTemplate['Body'], $_getOwnerID['Owner'], $aPlus);
        }

        /* Notification End */

        // START - Emailing  to agency  

        print_r("{state: true, name:'" . str_replace("'", "\\'", $filename) . "', size:" . $_FILES["file"]["size"] . "}");
        exit;
    }

    //print_r("{state: true, name:$lastId,  sql: $sql, lastId:$lastId,  q1: $q1, albumname:$albumname,  q2: $q2, albumid:$albumid,  sql3: $sql3, sql4:$sql4,sql5:$sql5  }");
    // exit;
}

/* -- written by Satya -- */

function getAspectRatio($width, $height) {
    $rem = $width % $height;
    if ($rem == 0)
        return $height;
    return getAspectRatio($height, $rem);
}
