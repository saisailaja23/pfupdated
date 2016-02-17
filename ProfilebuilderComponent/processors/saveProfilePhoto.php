<?php
require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');
require_once ('../../inc/utils.inc.php');
require_once ('../../inc/db.inc.php');
require_once ('../../inc/images.inc.php');
require_once ('imagetool.class.php');
require_once ('watermark.php');

  $root_dir=$dir['root'];
  $site_url =$site['url'];

  $tmp_dir = $root_dir."tmp/";
   

if($_POST){
    $serverName=  $_POST['server'];
    $img =$_POST['img'];
    $x=$_POST['x'];
    $y=$_POST['y'];
    $w=$_POST['w'];
    $h=$_POST['h'];
    $logged = getLoggedId();
    $avatar_id=getAvatarId($logged);
    
    $id=$avatar_id.".jpg";

    $FavDir    = $root_dir."modules/boonex/avatar/data/favourite/";
    $AvatarDir    = $root_dir."modules/boonex/avatar/data/avatarphotos/";
    $ImgDir    = $root_dir."modules/boonex/avatar/data/images/";
  
    $imgTObj = new ImageTool($img);
    $imgTObj -> resizeImage($w, $h, 'crop',$x,$y);
    $imgTObj -> saveImage($tmp_dir.$id, 100);
    @chmod($tmp_dir.$id, 0644);

  

    imageResize($tmp_dir.$id, $FavDir.$avatar_id.'.jpg', 300, 250, true);
    imageResize($tmp_dir.$id, $ImgDir.$avatar_id.'.jpg', 64, 64, true);
    imageResize($tmp_dir.$id, $ImgDir.$avatar_id.'i.jpg', 32, 32, true);

    @copy($root_dir.$serverName, $AvatarDir.$logged.'.jpg');
    @chmod($AvatarDir.$logged.'.jpg', 0644);

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
