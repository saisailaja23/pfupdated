<?php

require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');
require_once ('../../inc/utils.inc.php');
require_once ('../../inc/db.inc.php');
require_once ('../../inc/images.inc.php');

  $filename = $_FILES['file']['name'];
 $details = pathinfo($filename);
 $extension = $details['extension'];
  $allowed = array("jpg", "png", "gif");
if(in_array(strtolower($extension) , $allowed)) {
if ($_FILES["file"]["size"] != 0) {
    $target_dirname = '../../modules/boonex/photos/data/files/';

    if (@$_REQUEST["mode"] == "html5" || @$_REQUEST["mode"] == "flash") {
        $filename = $_FILES["file"]["name"];
        $filename = str_replace(" ", "_", $filename);
        processimage();
        print_r("{state: true, name:'" . str_replace("'", "\\'", $filename) . "', size:" . $_FILES["file"]["size"]/* filesize("uploaded/".$filename) */ . "}");
        
    }

    /*

      HTML4 MODE

      response format:

      to cancel uploading
      {state: 'cancelled'}

      if upload was good, you need to specify state=true, name - will passed in form.send() as serverName param, size - filesize to update in list
      {state: 'true', name: 'filename', size: 1234}

     */

    if (@$_REQUEST["mode"] == "html4") {
        if (@$_REQUEST["action"] == "cancel") {
            print_r("{state:'cancelled'}");
        } else {
            $filename = $_FILES["file"]["name"];
            $filename = str_replace(" ", "_", $filename);
            processimage();

            print_r("{state: true, name:'" . str_replace("'", "\\'", $filename) . "', size:" . $_FILES["file"]["size"]/* filesize("uploaded/".$filename) */ . "}");
        }
    }
}
}
function processimage() {
    $logged = getLoggedId();
    $sMediaDir = '../../modules/boonex/avatar/data/avatarphotos/';
    $sExtension = '.jpg';
      $iWidth = 400; 
        $iHeight = 400;
    $sNewFilePath = $sMediaDir .$logged. $sExtension;
    $iRes = imageResize($_FILES["file"]["tmp_name"], $sNewFilePath, $iWidth, $iHeight, true);
    @chmod($sNewFilePath, 0644);
    
     $sMediaDir = '../../modules/boonex/avatar/data/images/';
    $sql= " INSERT INTO bx_avatar_images SET `author_id` = '$logged'";
    $result = mysql_query($sql);
    $iAvatar = mysql_insert_id();
     
    $q= "UPDATE `Profiles` SET `Avatar` = '$iAvatar' WHERE `ID` = " . $logged;
  
    mysql_query($q);
    $aFileTypes = array(
        'icon' => array('postfix' => 'i', 'size_def' => 32),
        'thumb' => array('postfix' => '', 'size_def' => 64),
        
    );

    // force into JPG
    $sExtension = '.jpg';
    $w[0] = 32;
    $h[0] = 32;
    //$w[1] = 64;
    //$h[1] = 64;
    $z = 0;
	
/**/
list($wd,$ht) = getimagesize($_FILES['file']['tmp_name']);

if($wd > $ht){
	$r = $wd/$ht;
	$w[1] = 150;
	$h[1] = 150/$r;
}else{
	$r = $ht/$wd;
	$h[1] = 80;
	$w[1] = 80/$r;
}
/**/	
	
	
    // generate present pics
    foreach ($aFileTypes as $sKey => $aValue) {


        $iWidth = $w[$z]; //(int)$this->oModule->_oConfig->getGlParam($sKey . '_width');
        $iHeight = $h[$z]; //(int)$this->oModule->_oConfig->getGlParam($sKey . '_height');

        if ($iWidth == 0)
            $iWidth = $aValue['size_def'];
        if ($iHeight == 0)
            $iHeight = $aValue['size_def'];
        $sNewFilePath = $sMediaDir . $iAvatar .  $aValue['postfix'] . $sExtension;
        $iRes = imageResize($_FILES["file"]["tmp_name"], $sNewFilePath, $iWidth, $iHeight, true);
        if ($iRes != 0)
            return false; //resizing was failed

        @chmod($sNewFilePath, 0644);
        $z++;
    }
    
    $sMediaDir = '../../modules/boonex/avatar/data/slider/';
    $iWidth = 200; 
    $iHeight = 200;
    $sNewFilePath = $sMediaDir .$iAvatar. $sExtension;
    $iRes = imageResize($_FILES["file"]["tmp_name"], $sNewFilePath, $iWidth, $iHeight, true);
    @chmod($sNewFilePath, 0644);
  

    /************************************/
    
    
    
    $Uri = str_replace(' ', '-', $_FILES['file']['name']);
    $sMediaDir = '../../modules/boonex/photos/data/files/';
    $sql = " INSERT INTO bx_photos_main SET 
    Categories = 'Home photos',
    	Owner= '$logged',
    
    `Ext` = 'jpg',
    Size = '" . $_FILES['file']['size'] . "',
    	Title= '" . $_FILES['file']['name'] . "',
    
    `Uri` = '$Uri',
    bx_photos_main.Desc  = '" . $_FILES['file']['name'] . "',
    	Tags= 'Home photos',
    
    `Date` = NOW(),
    Views = 0,
    	Rate= 0,
    
    `RateCount` = 0,
    CommentsCount = 0,
    	Featured= 0,

    Status = 'approved',
    	Hash= '" . md5(microtime()) . "'";
    $result = mysql_query($sql);
    $lastId = mysql_insert_id();
    
    $sql0 = "UPDATE `bx_groups_main` SET `thumb` = $lastId WHERE `author_id` = " . $logged;
    $result0 = mysql_query($sql0);
    
    $q1 = "select NickName from profiles where id = $logged";
    $result1 = mysql_query($q1);
    $row1 = mysql_fetch_array($result1);
    $albumname = $row1['NickName'] . "''s photos";

    $q2 = "select ID from sys_albums where Caption = '$albumname'";
    $result2 = mysql_query($q2);
    $row2 = mysql_fetch_array($result2);
    $albumid = $row2['ID'];

    $sql3 = "UPDATE `sys_albums_objects` SET `obj_order` = `obj_order` + 1 WHERE `id_album` = " . $albumid;
    $result3 = mysql_query($sql3);


    $sql4 = "INSERT INTO sys_albums_objects SET id_object = $lastId,
    `obj_order` = 0,
    id_album = $albumid";
    $result4 = mysql_query($sql4);

    $sql5 = "UPDATE `sys_albums` SET `ObjCount` = `ObjCount` + 1 WHERE `ID` = " . $albumid;
    $result5 = mysql_query($sql5);
    
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
    
   
}
