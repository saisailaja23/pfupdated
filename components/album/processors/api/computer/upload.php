<?php
/**
 * upload.php
 *
 * Copyright 2013, Moxiecode Systems AB
 * Released under GPL License.
 *
 * License: http://www.plupload.com/license
 * Contributing: http://www.plupload.com/contributing
 */

#!! IMPORTANT: 
#!! this file is just an example, it doesn't incorporate any security checks and 
#!! is not recommended to be used in production environment as it is. Be sure to 
#!! revise it and customize to your needs.

//ini_set('error_reporting',E_ALL);
//ini_set('display_errors', 'On');
defined('CONFIG_PATH') || define('CONFIG_PATH', realpath(dirname(__FILE__) . '/../../../../application'));
set_include_path(implode(PATH_SEPARATOR, array(realpath(CONFIG_PATH . '../../../../../../library'),get_include_path(),)));

require_once('Zend/Config/Ini.php');
require_once('Zend/Controller/Front.php');
require_once('Zend/Db/Table.php');
//require_once('Zend/Auth.php');
require_once("../../../../../log4php/logForCommon.php"); 
$ccconfig                                   = new Zend_Config_Ini(CONFIG_PATH . '../../../../../../application/configs/ccconfig.ini');

// Make sure file is not cached (as it happens for example on iOS devices)
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

/* 
// Support CORS
header("Access-Control-Allow-Origin: *");
// other CORS headers if any...
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	exit; // finish preflight CORS requests here
}
*/

// 5 minutes execution time
@set_time_limit(5 * 60);

// Uncomment this one to fake upload time
// usleep(5000);
// Settings
$serverPath     = $_SERVER['DOCUMENT_ROOT'];
$albumID        = $_POST['albumID'];
$connectionID   = $_POST['connectionID'];

$logClassObj->setModule("Lifebook"); //print_r($userinfo);
$logClassObj->setSubmodule("Files");
$logClassObj->setConnectionID('Upload');
$logClassObj->commonWriteLogInOne(" ************************ Upload Starts ************************","INFO");
$logClassObj->commonWriteLogInOne(" albumID: ".$albumID,"INFO");  
$logClassObj->commonWriteLogInOne(" connectionID: ".$connectionID,"INFO");
$logClassObj->commonWriteLogInOne(" serverPath: ".$serverPath,"INFO");
                
if(!($albumID) || !($connectionID))
{
    die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to save due to missing parameters."}, "id" : "id"}');
}
define('CONFIG_GALLERY_PATH',$serverPath."/swi/connections/".$connectionID."/album/".$albumID);
$galleryPath    = CONFIG_GALLERY_PATH;
//echo " albumID ".$albumID;
//echo " connectionID ".$connectionID;
//echo " galleryPath ".$galleryPath;
$targetDir      = $galleryPath;
//$thumbnailDir   = $galleryPath;
//$targetDir      = "arun";
$cleanupTargetDir = false; // Remove old files
$maxFileAge = 5 * 3600; // Temp file age in seconds

//$targetDir      = 'uploads';
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
$fileSize         = ($_FILES["file"]["size"])?$_FILES["file"]["size"]:0;
$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;   //--arun commented
//$filePath         = $targetDir. DIRECTORY_SEPARATOR .$main_fileName;

//$filePath         = 'D:/xampp/htdocs/www/childconnect/public/module/childconnect/processors/uploads'. DIRECTORY_SEPARATOR .$main_fileName;;
// Chunking might be enabled
$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;

//print_r($_REQUEST);
// Remove old temp files	
if ($cleanupTargetDir) {
	if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory.'.$targetDir.'}, "id" : "id"}');
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

//echo " $filePath  ".$filePath;
// Open temp file
if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
        $logClassObj->commonWriteLogInOne(" Failed to open output stream: 102 -1","INFO");
	die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
}

if (!empty($_FILES)) {
	if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                $logClassObj->commonWriteLogInOne(" Failed to move uploaded file: 103 - 2","INFO");
		die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
	}

	// Read binary input stream and append it to temp file
	if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
		$logClassObj->commonWriteLogInOne("  Failed to open input stream: 101 -1","INFO");
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
	}
} else {	
	if (!$in = @fopen("php://input", "rb")) {
                $logClassObj->commonWriteLogInOne("  Failed to open input stream: 101 - 2","INFO");
		die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
	}
}

while ($buff = fread($in, 4096)) {
	fwrite($out, $buff);
}

@fclose($out);
@fclose($in);

// Check if file has been uploaded
if (!$chunks || $chunk == $chunks - 1) { //echo " hai ".$filePath;
	
        try
        {
            $logClassObj->commonWriteLogInOne("  In chunk case orginal file created filePath: ".$filePath,"INFO");
            //Strip the temp .part suffix off 
            rename("{$filePath}.part", $filePath);
        
           $profileImageID  =  setupFiles($fileName,$fileSize,$filePath,$targetDir);
        }
       catch (Exception $e) 
       {
           $logClassObj->commonWriteLogInOne("  Failed to move uploaded file: 103 - 1","INFO");
          die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}'); 
       }
}
//exit();
// Return Success JSON-RPC response
//$rand_value = rand(1000,9000);
die('{"jsonrpc" : "2.0", "result" : "sucess", "id" : "'.$profileImageID.'"}');
//die (json_encode('{"jsonrpc" : "2.0", "result" : sucess, "id" : "id"}'));
// *#*#*#*#*#*#*#*#*##

//to get name and extension
function getnameandextension($fileName)
{
    $file_array = array();
    $exts      = split("[/\\.]",$fileName);
    $num       = count($exts)-1;
    $exts      = $exts[$num];
    
    $file_namedet  = split(".".$exts, $fileName);
    $file_array[0] = $file_namedet[0];
    $file_array[1] = $exts;
    return $file_array;
}
//Save the DB details
function saveFileDetails($fileName,$fileSize)
{
    global $ccconfig,$albumID,$connectionID;
    
    $link = mysql_connect($ccconfig->db->host, $ccconfig->db->username, $ccconfig->db->password);
        if (!$link) {
        die('Could not connect: ' . mysql_error());
        }
    mysql_select_db($ccconfig->db->dbname);
    
    $file_details  = getnameandextension($fileName);
    $exts          = $file_details[1];
    $file_name     = $file_details[0];
    
    $fileType      = casecondition($exts);
    if($fileType == 'photo')
        $upimageExt	= 'jpg';
    else if($fileType=='video')
        $upimageExt	= 'flv';
    else
        $upimageExt = $exts;
    
    $imgDescription  = '';
    $imgTitle        = '';
    $totPhotoCount						= 0;
    if(!empty($albumID))
    {

        $sqlphcont          = "SELECT
                COUNT(ph.idFileObject)              AS photoCount
        FROM
                album a
                LEFT OUTER JOIN albumcontent ac ON (a.idAlbum = ac.idAlbum)
                LEFT OUTER JOIN fileobject ph ON (ac.idFileObject = ph.idFileObject AND ph.isDeleted = 'N')
        WHERE
                a.idAlbum = $albumID
        AND ph.objectCategory != 'PD'
        AND ph.objectCategory != 'TX'";
        $resultphcnt     = mysql_query($sqlphcont);
        while ($row = mysql_fetch_array($resultphcnt, MYSQL_ASSOC)) {
            $totPhotoCount   = $row["photoCount"];
        }
        mysql_free_result($resultphcnt);
        $totPhotoCount					= $totPhotoCount + 1;
    }
      
    $objectCategory       = ($fileType=='photo')?"PH":($fileType=='file'?"FL":($fileType=='video'?"VD":""));
    $objectType           =  $upimageExt;
    $objectPath           = 'swi/connections/'.$connectionID.'/album/'. $albumID.'/';
    $objectDescription    = (strlen($imgDescription) > 254)?substr($imgDescription, 0,254):$imgDescription;
    $objectSize           = $fileSize;
    $createddttm          = date("Y-m-d H:i:s",time());
    $idConnection         = $connectionID;
    $objectTitle          = ($imgTitle)?$imgTitle:NULL;
    if($objectTitle=='')$objectTitle=$objectDescription;
    $sqlFOinsert = "INSERT INTO fileobject (objectCategory, objectType, objectPath, objectDescription,  objectSize, createddttm,
        isDeleted, idConnection, objectTitle, seqNumber)
        VALUES ('$objectCategory','$objectType','$objectPath','$objectDescription',$objectSize,'$createddttm','N',$idConnection,'$objectTitle',$totPhotoCount)";
    mysql_query($sqlFOinsert);
    //echo $sqlFOinsert;
    $profileImageID       = mysql_insert_id();
    if (!$profileImageID) {
        //writeLog("profile image id missing. ".mysql_error($link));
        throw new Exception('profile image id missing.'.mysql_error($link));
        }

    //echo "\n\n ".$profileImageID;
    $cvr = "N";
    if($objectCategory == 'PH')
    {
        $sqlcvrcont          = "SELECT count(*) AS coverCount
                            FROM albumcontent a
                                WHERE a.idAlbum = $albumID
                                AND isCoverPage = 'Y'";
        $resultcvrcnt     = mysql_query($sqlcvrcont);
        while ($row = mysql_fetch_array($resultcvrcnt, MYSQL_ASSOC)) {
                $albumCover   = $row["coverCount"];
        }
        ($albumCover == 0)? $cvr = "Y": 'N';
        mysql_free_result($resultcvrcnt);
    }

$sqlALCONTinsert = "INSERT INTO albumcontent (idAlbum, idFileObject, addeddttm, isCoverPage)
        VALUES ($albumID,$profileImageID,'$createddttm','$cvr')";
mysql_query($sqlALCONTinsert);
//echo "\n\n ".$sqlALCONTinsert;
$sqlalbumupdate         = "UPDATE album SET modifieddttm = '$createddttm' WHERE idAlbum = $albumID";
mysql_query($sqlalbumupdate);

return $profileImageID; 
    
    
}



//To get the file type
function casecondition($extension){
		switch ($extension){
		
		case "jpg":
			return 'photo';
			break;
		case "png":
			return 'photo';
			break;
		case "jpeg":
			return 'photo';
			break;
		case "gif":
			return 'photo';
			break;
		case "pdf":
			return 'file';
			break;
		case "doc":
			return 'file';
			break;
		case "xls":
			return 'file';
			break;
		case "ppt":
			return 'file';
			break;
		case "docx":
			return 'file';
			break;
		case "xlsx":
			return 'file';
			break;
		case "txt":
			return 'file';
			break;
		case "avi":
			return 'video';
			break;
		case "swf":
			return 'video';
			break;
		case "mpeg":
			return 'video';
			break;
		case "mp4":
			return 'video';
			break;
		case "wmv":
			return 'video';
			break;
		case "flv":
			return 'video';
			break;
		case "mov":
			return 'video';
			break;
		case "mpg":
			return 'video';
			break;
			case "JPG":
			return 'photo';
			break;
		case "PNG":
			return 'photo';
			break;
		case "JPEG":
			return 'photo';
			break;
		case "GIF":
			return 'photo';
			break;
		case "PDF":
			return 'file';
			break;
		case "DOC":
			return 'file';
			break;
		case "XLS":
			return 'file';
			break;
		case "PPT":
			return 'file';
			break;
		case "DOCX":
			return 'file';
			break;
		case "XLSX":
			return 'file';
			break;
		case "TXT":
			return 'file';
			break;
		case "AVI":
			return 'video';
			break;
		case "SWF":
			return 'video';
			break;
		case "MPEG":
			return 'video';
			break;
		case "MP4":
			return 'video';
			break;
		case "WMV":
			return 'video';
			break;
		case "FLV":
			return 'video';
			break;
		case "MOV":
			return 'video';
			break;
		case "MPG":
			return 'video';
			break;
		}
}

// crops proportionally based on width and height
function resize($img, $w, $h, $newfilename,$crop_prortion = 1) {
    //Check if GD extension is loaded
    if (!extension_loaded('gd') && !extension_loaded('gd2')) {
        trigger_error("GD is not loaded", E_USER_WARNING);
        return false;
    }
      
    //Get Image size info
    $imgInfo = getimagesize($img);
    //print_r($imgInfo);
    switch ($imgInfo[2]) {
     
        case 1: $im = imagecreatefromgif($img); break;
        case 2: $im = imagecreatefromjpeg($img);  break;
        case 3: $im = imagecreatefrompng($img); break;
        default:  trigger_error('Unsupported filetype!', E_USER_WARNING);  break;
    }
    if($crop_prortion)
    {
        //If image dimension is smaller, do not resize
        if ($imgInfo[0] <= $w && $imgInfo[1] <= $h) {
            $nHeight = $imgInfo[1];
            $nWidth = $imgInfo[0];
        }
        else{
        // yeah, resize it, but keep it proportional
            if ($w/$imgInfo[0] > $h/$imgInfo[1]) {
                $nWidth = $imgInfo[0]*($h/$imgInfo[1]);
                $nHeight = $h;           
            }
            else{
                $nWidth = $w;
                $nHeight = $imgInfo[1]*($w/$imgInfo[0]);
            }
        }

        $nWidth = round($nWidth);

        $nHeight = round($nHeight);
    }
    else
    {
        if(($w)&&($h))
        {
            if ($imgInfo[0] <= $w && $imgInfo[1] <= $h) {
                $nHeight = $imgInfo[1];
                $nWidth = $imgInfo[0];
            }
            else
            {
                $nWidth = round($w);
                $nHeight = round($h);
            }
        }
        else
        {
            $nHeight = $imgInfo[1];
            $nWidth  = $imgInfo[0];
        }
    }
    $newImg = imagecreatetruecolor($nWidth, $nHeight);
      
    /* Check if this image is PNG or GIF, then set if Transparent*/ 
     
    if(($imgInfo[2] == 1) OR ($imgInfo[2]==3)){
        imagealphablending($newImg, false);
        imagesavealpha($newImg,true);
        $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
        imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
    }
      
    imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);
      
    //Generate the file, and rename it to $newfilename - commented 04/08/2014
    /*switch ($imgInfo[2]) {
        case 1: imagegif($newImg,$newfilename); break;
        case 2: imagejpeg($newImg,$newfilename);  break;
        case 3: imagepng($newImg,$newfilename); break;
        default:  trigger_error('Failed resize image!', E_USER_WARNING);  break;
    }*/
    
    //image created as JPG only
    imagejpeg($newImg,$newfilename,100); 
      
    return $newfilename;
}


function resizeimagetoCC($actualfilePath,$sourcefile, $dest_x, $dest_y,$crop_prortion = 1)
{
    $targetfile = $sourcefile;
    $copy = copy($actualfilePath, $sourcefile);
    resize($sourcefile, $dest_x, $dest_y, $targetfile,$crop_prortion);
}
// Create target dir
//if (!file_exists($thumbnailDir))
//    @mkdir($thumbnailDir);
//$rand_value = rand(1000,9000);
// function added by me for creating thumbnails
//$thumbnailPath = $thumbnailDir . DIRECTORY_SEPARATOR . $fileName; // -- commented by Arun


//$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;   //--arun commented

function setupFiles($fileName,$fileSize,$filePath,$targetDir)
{    
    // Arun //
    //echo " fileName ".$fileName;
    $file_details  = getnameandextension($fileName);
    $exts          = $file_details[1];

    $fileType      = casecondition($exts);

    if($fileType == 'photo')
        $upimageExt	= 'jpg';
    else if($fileType == 'video')
        $upimageExt = 'flv';
    else
        $upimageExt = $exts;
    //$profileImageID   = rand(1000,9000);
    
    $profileImageID   = saveFileDetails($fileName,$fileSize);
    //Arun //
    if($fileType == 'photo')
    {
        //$finalfilePath         = $targetDir.DIRECTORY_SEPARATOR. $main_fileName;
        
        try
        {
            $main_fileName    = $profileImageID."_main.".$upimageExt;
            $thumbnailPath    = $targetDir . DIRECTORY_SEPARATOR . $main_fileName;
            resizeimagetoCC($filePath,$thumbnailPath, 0, 0, 0);

            $main_fileName    = $profileImageID."_thumb.".$upimageExt;
            $thumbnailPath    = $targetDir . DIRECTORY_SEPARATOR . $main_fileName;
            resizeimagetoCC($filePath,$thumbnailPath, 200, 150,1);

            $main_fileName    = $profileImageID.".".$upimageExt;
            $thumbnailPath    = $targetDir . DIRECTORY_SEPARATOR . $main_fileName;
            resizeimagetoCC($filePath,$thumbnailPath, 500, 500);

            @unlink($filePath);
            return $profileImageID;
        }
        catch (Exception $e) 
        {
            die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to uploade file."}, "id" : "id"}');
        }
    }
    else if($fileType == 'video')
    {
        $sourceVideo      = $filePath;
        $main_fileName    = $profileImageID.".".$upimageExt;
        $destVideo        = $targetDir.'/'.$main_fileName;
        if (stristr (PHP_OS, 'WIN')) {
            $ffmpegPath			 = $_SERVER['DOCUMENT_ROOT']."/ffmpeg/bin/ffmpeg";
            //"ffmpeg\\bin\\ffmpeg""
            $ffmpegPath          = str_replace("/", "\\", $ffmpegPath);

        }
        else {
            $ffmpegPath          = "ffmpeg";
        }
        try{
            exec($ffmpegPath." -i \"".$sourceVideo."\"  -ab 56 -ar 44100 -b 512k -r 15 -s 640x480 -f flv ".$destVideo);
            @unlink($sourceVideo);
        }
        catch (Exception $e) 
        {
            die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}'); 
        }

       
    }
    else if($fileType == 'file')
    {
        $sourceFile       = $filePath;
        $main_fileName    = $profileImageID.".".$upimageExt;
        $destFile         = $targetDir.'/'.$main_fileName;
        rename($sourceFile, $destFile);
    }
    
}



 
// Return JSON-RPC response
//die('{"jsonrpc" : "2.0", "result" : return, "id" : "id"}');
die('{"jsonrpc" : "2.0", "result" : "sucess", "id" : "'.$rand_value.'"}');