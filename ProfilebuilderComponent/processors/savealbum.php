<?php

require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');
require_once ('../../inc/utils.inc.php');
require_once ('../../inc/db.inc.php');
require_once ('../../inc/images.inc.php');
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
bx_import( 'BxDolEmailTemplates' );
bx_import( 'BxTemplFormView' );

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
        //move_uploaded_file($_FILES["file"]["tmp_name"],$target_dirname."/".$filename);
        print_r("{state: true, name:'" . $target_dirname . "'}");
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
//		 move_uploaded_file($_FILES["file"]["tmp_name"], $target_dirname."/".$filename);
            print_r("{state: true, name:'" . str_replace("'", "\\'", $filename) . "', size:" . $_FILES["file"]["size"]/* filesize("uploaded/".$filename) */ . "}");
        }
    }
}
}
function processimage() {
    $logged = getLoggedId();
    $Uri = str_replace(' ', '-', $_FILES['file']['name']);
    $sMediaDir = '../../modules/boonex/photos/data/files/';
     $status="pending";
    if(@mysql_num_rows(mysql_query("SELECT ID FROM  Profiles JOIN bx_groups_moderation WHERE Profiles.ID= ".$_COOKIE['memberID']." AND Profiles.AdoptionAgency = bx_groups_moderation.GroupId AND bx_groups_moderation.ApproveStatus= 'on' AND bx_groups_moderation.Type = 'photo'"))){
        $status="approved";
    }
   $rand = date('Y-m-d H:i:s');
 
    $finaluri = $Uri.'-'.$rand;
    $today = time();
    $sql = " INSERT INTO bx_photos_main SET 
    Categories = 'Profile photos',
    	Owner= '$logged',
    
    `Ext` = 'jpg',
    Size = '" . $_FILES['file']['size'] . "',
    	Title= '" . $_FILES['file']['name'] . "',
    
    `Uri` = '$finaluri',
    bx_photos_main.Desc  = '" . $_FILES['file']['name'] . "',
    	Tags= 'Profile photos',
    
    `Date` = '$today',
    Views = 0,
    	Rate= 0,
    
    `RateCount` = 0,
    CommentsCount = 0,
    	Featured= 0,

    Status = '".$status."',
    	Hash= '" . md5(microtime()) . "'";
    $result = mysql_query($sql);
    $lastId = mysql_insert_id();
     if($status=="pending"){
            $rEmailTemplate = new BxDolEmailTemplates();
            $aTemplate = $rEmailTemplate -> getTemplate( 'Photo_add_approval' ) ;
            $aPlus = array ('AlbumTitle' => $albumCap['Caption']);
            $aAgencyEmail=db_arr("SELECT Profiles.* FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =".$_COOKIE['memberID']." AND Profiles.AdoptionAgency=bx_groups_main.id )");
            If (!(mysql_num_rows(mysql_query("SELECT id FROM bx_groups_main WHERE  author_id=".$_COOKIE['memberID'])))){}
            sendMail( $aAgencyEmail['Email'], $aTemplate['Subject'], $aTemplate['Body'], $_COOKIE['memberID'],$aPlus ); 
        }else{
            $rEmailTemplate = new BxDolEmailTemplates();
            $aTemplate = $rEmailTemplate -> getTemplate( 'Photo_add_notification' ) ;
              $aPlus = array ('AlbumTitle' => $albumCap['Caption']);
            $aAgencyEmail=db_arr("SELECT Profiles.* FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =".$_COOKIE['memberID']." AND Profiles.AdoptionAgency=bx_groups_main.id )");
            If (!(mysql_num_rows(mysql_query("SELECT id FROM bx_groups_main WHERE  author_id=".$_COOKIE['memberID'])))){}
            sendMail( $aAgencyEmail['Email'], $aTemplate['Subject'], $aTemplate['Body'], $_COOKIE['memberID'],$aPlus );
        } 
   // $q1 = "select NickName from Profiles where id = $logged";
  //  $result1 = mysql_query($q1);
  //  $row1 = mysql_fetch_array($result1);
  //  $albumname = $row1['NickName'] . "''s photos";
      $albumname = "Home photos";
    
    $q2 = "select ID from sys_albums where Caption = '$albumname' and `Owner` =$logged";
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
