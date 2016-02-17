<?php
/**
 * C:\Users\msi1308\AppData\Roaming\Sublime Text 2\Packages/PhpTidy/phptidy-sublime-buffer.php
 *
 * @author Morgan Estes <morgan.estes@gmail.com>
 * @package default
 */

require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');
require_once ('../../inc/utils.inc.php');
require_once ('../../inc/db.inc.php');
require_once ('../../inc/images.inc.php');
require_once ('imagetool.class.php');
require_once ('watermark.php');

$uploadPath = $dir['root']."tmp/";

$filename = $_FILES['file']['name'];
$details = pathinfo($filename);
$extension = $details['extension'];
$allowed = array("jpg", "png", "gif", "jpeg");


if(!in_array(strtolower($extension) , $allowed)) {
    print_r('This file is not allowed');
}
else { 
  if (in_array(strtolower($extension) , $allowed)) {
    if ($_FILES["file"]["size"] != 0) {
        if (@$_REQUEST["mode"] == "html5" || @$_REQUEST["mode"] == "flash") {
            $filename = $_FILES["file"]["name"];
            $filename = str_replace(" ", "_", $filename);
            move_uploaded_file($_FILES["file"]["tmp_name"], $uploadPath.$filename);
            list($width, $height, $type, $attr) = getimagesize($uploadPath.$filename);
            if ($width>600 || $height>535) {
                $newname=md5($uploadPath.$filename).".jpg";
                $imgTObj = new ImageTool($uploadPath.$filename);
                //*** 2) Resize image (options: exact, portrait, landscape, auto, crop)
                $imgTObj -> resizeImage(600, 535);
                //*** 3) Save image
                $imgTObj -> saveImage($uploadPath.$newname, 100);
                $filename=$newname;
                //move_uploaded_file($_FILES["file"]["tmp_name"],$target_dirname."/".$filename);
                //print_r("{state: true, name:'" . getLoggedId() . "'}");
                
            }print_r("{state: true, name:'" . $filename . "'}");
        }

        if (@$_REQUEST["mode"] == "html4") {
            if (@$_REQUEST["action"] == "cancel") {
                print_r("{state:'cancelled'}");
            } else {
                $filename = $_FILES["file"]["name"];
                $filename = str_replace(" ", "_", $filename);
                 move_uploaded_file($_FILES["file"]["tmp_name"], $uploadPath.$filename);
                    list($width, $height, $type, $attr) = getimagesize($uploadPath.$filename);
                    if ($width>600 || $height>535) {
                        $newname=md5($uploadPath.$filename).".jpg";
                        $imgTObj = new ImageTool($uploadPath.$filename);
                        //*** 2) Resize image (options: exact, portrait, landscape, auto, crop)
                        $imgTObj -> resizeImage(600, 535);
                        //*** 3) Save image
                        $imgTObj -> saveImage($uploadPath.$newname, 100);
                        $filename=$newname;
                        //move_uploaded_file($_FILES["file"]["tmp_name"],$target_dirname."/".$filename);
                        //print_r("{state: true, name:'" . getLoggedId() . "'}");
                       
                    }
                //   move_uploaded_file($_FILES["file"]["tmp_name"], $target_dirname."/".$filename);
                //print_r("{state: true, name:'" . str_replace("'", "\\'", $filename) . "', size:" . $_FILES["file"]["size"]/* filesize("uploaded/".$filename) */ . "}");
                print_r("{state: true, name:'" . str_replace("'", "\\'", $filename) . "', size:" . $_FILES["file"]["size"]/* filesize("uploaded/".$filename) */ . "}");
                exit();
            }
        }
    }
}

if($_POST){
    //$realName=$_POST['real'];
    //$serverName=explode("/", $_POST['server']);
   // $serverName=array_pop($serverName);
    $serverName=  $_POST['server'];
    $x=$_POST['x1'];
    $y=$_POST['y1'];
    $w=$_POST['w'];
    $h=$_POST['h'];
    
    $logged = getLoggedId();
    $avatar_id=getAvatarId($logged);
    $id=$avatar_id.".jpg";
    $tmp=$uploadPath;
    //Families Search Page
    $FavDir    = $dir['root']."modules/boonex/avatar/data/favourite/";
    //This is for creating small images with $avataridi.jpg and $avtarid.jpg
    $ImgDir    = $dir['root']."modules/boonex/avatar/data/images/";

    $imgTObj = new ImageTool($uploadPath.$serverName);
    $imgTObj -> resizeImage($w, $h, 'crop',$x,$y);
    $imgTObj -> saveImage($temp.$id, 100);
    @chmod($temp.$id, 0644);
    $avatar_id=getAvatarId($logged);
    imageResize($temp.$id, $FavDir.$avatar_id.'.jpg', 300, 250, true);
    imageResize($temp.$id, $ImgDir.$avatar_id.'.jpg', 64, 64, true);
    imageResize($temp.$id, $ImgDir.$avatar_id.'i.jpg', 32, 32, true);
    $match_status= db_arr("SELECT `status` FROM `watermarkimages` WHERE `author_id` = '$logged'");
    $matchstatus = $match_status[status]; 
    
    if($matchstatus != '') {         
        watermark_apply($logged,$matchstatus);
    }
    @chmod($FavDir.$avatar_id.'.jpg', 0644);
    @chmod($ImgDir.$avatar_id.'.jpg', 0644);
    @chmod($ImgDir.$avatar_id.'i.jpg', 0644);


  //  @chmod($sNewFilePath, 0644);
    print_r('{"state": true, "name":"'.str_replace("'","\\'",$pic_name).'"}');
}
function getAvatarId($logged){ 
    //Insert into avatar_images and update Profiels table
    $sql= "INSERT INTO bx_avatar_images SET `author_id` = '$logged'";
  $result = mysql_query($sql);
  $iAvatar = mysql_insert_id();
  $q= "UPDATE `Profiles` SET `Avatar` = '$iAvatar' WHERE `ID` = " . $logged;
  mysql_query($q);  
  return $iAvatar;
}

}

// function upload(){
//     move_uploaded_file ($_FILES["file"]["tmp_name"], $uploadPath.$filename);
//     list($width, $height, $type, $attr) = getimagesize($uploadPath.$filename);
//     if($width>600 || $height>535){
//         $newname=md5($uploadPath.$filename).".jpg";
//         $imgTObj = new ImageTool($uploadPath.$filename);
//         //*** 2) Resize image (options: exact, portrait, landscape, auto, crop)
//         $imgTObj -> resizeImage(600, 535);
//         //*** 3) Save image
//         $imgTObj -> saveImage($uploadPath.$newname, 100);
//         $filename=$newname;
//     }
// }


// function processimage() {
//     $logged = getLoggedId();
//     $sMediaDir = '../../modules/boonex/avatar/data/avatarphotos/';
//     $sExtension = '.jpg';
//       $iWidth = 400;
//         $iHeight = 400;
//     $sNewFilePath = $sMediaDir .$logged. $sExtension;
//     $iRes = imageResize($_FILES["file"]["tmp_name"], $sNewFilePath, $iWidth, $iHeight, true);
//     @chmod($sNewFilePath, 0644);

//      $sMediaDir = '../../modules/boonex/avatar/data/images/';
//     $sql= " INSERT INTO bx_avatar_images SET `author_id` = '$logged'";
//     $result = mysql_query($sql);
//     $iAvatar = mysql_insert_id();

//     $q= "UPDATE `Profiles` SET `Avatar` = '$iAvatar' WHERE `ID` = " . $logged;

//     mysql_query($q);
//     $aFileTypes = array(
//         'icon' => array('postfix' => 'i', 'size_def' => 32),
//         'thumb' => array('postfix' => '', 'size_def' => 64),

//     );

//     // force into JPG
//     $sExtension = '.jpg';
//     $w[0] = 32;
//     $h[0] = 32;
//     $w[1] = 64;
//     $h[1] = 64;
//     $z = 0;
//     // generate present pics
//     foreach ($aFileTypes as $sKey => $aValue) {


//         $iWidth = $w[$z]; //(int)$this->oModule->_oConfig->getGlParam($sKey . '_width');
//         $iHeight = $h[$z]; //(int)$this->oModule->_oConfig->getGlParam($sKey . '_height');

//         if ($iWidth == 0)
//             $iWidth = $aValue['size_def'];
//         if ($iHeight == 0)
//             $iHeight = $aValue['size_def'];
//         $sNewFilePath = $sMediaDir . $iAvatar .  $aValue['postfix'] . $sExtension;
//         $iRes = imageResize($_FILES["file"]["tmp_name"], $sNewFilePath, $iWidth, $iHeight, true);
//         if ($iRes != 0)
//             return false; //resizing was failed

//         @chmod($sNewFilePath, 0644);
//         $z++;
//     }

//     $sMediaDir = '../../modules/boonex/avatar/data/slider/';
//     $iWidth = 200;
//     $iHeight = 200;
//     $sNewFilePath = $sMediaDir .$iAvatar. $sExtension;
//     $iRes = imageResize($_FILES["file"]["tmp_name"], $sNewFilePath, $iWidth, $iHeight, true);
//     @chmod($sNewFilePath, 0644);

// }
