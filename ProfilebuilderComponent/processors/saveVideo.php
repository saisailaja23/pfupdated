<?php
/**
 * C:\Users\msi1308\AppData\Roaming\Sublime Text 2\Packages/PhpTidy/phptidy-sublime-buffer.php
 *
 * @author Aravind Buddha <aravind.buddha@mediaus.com>
 * @package default
 */


require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');
require_once ('../../inc/utils.inc.php');
require_once ('../../inc/db.inc.php');
require_once ('../../inc/images.inc.php');
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
bx_import( 'BxDolEmailTemplates' );
bx_import( 'BxTemplFormView' );




if ($_FILES["file"]["size"] != 0) {

  if (@$_REQUEST["mode"] == "html5" || @$_REQUEST["mode"] == "flash") {
    processvideo($dir['root']);
    print_r("{state: true, name:'" . str_replace("'", "\\'", $filename) . "', size:" . $_FILES["file"]["size"]/* filesize("uploaded/".$filename) */ . "}");

  }
  if (@$_REQUEST["mode"] == "html4") {
    if (@$_REQUEST["action"] == "cancel") {
      print_r("{state:'cancelled'}");
    }
    else {
      $filename = $_FILES["file"]["name"];
       $filename=htmlspecialchars(trim($filename));
      $filename = str_replace(" ", "_", $filename);
      processvideo($dir['root']);
      print_r("{state: true, name:'" . str_replace("'", "\\'", $filename) . "', size:" . $_FILES["file"]["size"]/* filesize("uploaded/".$filename) */ . "}");
    }
  }
}


 */


/**
 *
 *
 * @param unknown $root
 */
function processvideo($root){

  $logged = getLoggedId();
  $Uri = str_replace(' ', '-', $_FILES['file']['name']);
  $Uri = str_replace('_', '-', $_FILES['file']['name']);
  $Uri = str_replace('.', '-', $_FILES['file']['name']);
  $time =strtotime("now");
  //$today = strtotime('today UTC');
$today = time();
  $status="pending";

  if (@mysql_num_rows(mysql_query("SELECT ID FROM  Profiles JOIN bx_groups_moderation WHERE Profiles.ID= ".$_COOKIE['memberID']." AND Profiles.AdoptionAgency = bx_groups_moderation.GroupId AND bx_groups_moderation.ApproveStatus= 'on' AND bx_groups_moderation.Type = 'video'"))) {
    $status="approved";
  }


    $sql = " INSERT INTO RayVideoFiles SET
    Categories = 'Profile Videos',
    Title= '" . $_FILES['file']['name'] . "',
    Uri = '$Uri',
    Tags= '" . $_FILES['file']['name'] . "',
    `Description` = '" . $_FILES['file']['name'] . "',
       `Time`=$time,
    Date = $today,
       Owner= '$logged',
    Views = 0,
    Rate= 0,
    `RateCount` = 0,
    CommentsCount = 0,
    Featured= 0,
    Status = '$status',
    Source = '',
    Video= ''";
  $result = mysql_query($sql);
  $lastId = mysql_insert_id();
  $mailSettings = getMailSettings();
  if ($status=="pending") {
    $rEmailTemplate = new BxDolEmailTemplates();
    $aTemplate = $rEmailTemplate -> getTemplate( 'Video_add_approval' ) ;
    $aPlus = array ('AlbumTitle' => $albumCap['Caption']);
    $aAgencyEmail=db_arr("SELECT Profiles.* FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =".$_COOKIE['memberID']." AND Profiles.AdoptionAgency=bx_groups_main.id )");
    if (!(mysql_num_rows(mysql_query("SELECT id FROM bx_groups_main WHERE  author_id=".$_COOKIE['memberID'])))) {}
    sendMail( $aAgencyEmail['Email'], $aTemplate['Subject'], $aTemplate['Body'], $_COOKIE['memberID'], $aPlus );
  }elseif ($mailSettings['VideoUpload'] == 0) {
    $rEmailTemplate = new BxDolEmailTemplates();
    $aTemplate = $rEmailTemplate -> getTemplate( 'Video_add_notification' ) ;
    $aPlus = array ('AlbumTitle' => $albumCap['Caption']);
    $aAgencyEmail=db_arr("SELECT Profiles.* FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =".$_COOKIE['memberID']." AND Profiles.AdoptionAgency=bx_groups_main.id )");
    if (!(mysql_num_rows(mysql_query("SELECT id FROM bx_groups_main WHERE  author_id=".$_COOKIE['memberID'])))) {}
    sendMail( $aAgencyEmail['Email'], $aTemplate['Subject'], $aTemplate['Body'], $_COOKIE['memberID'], $aPlus );
  } else {

  }




  $albumname = "Home Videos";

  $q2 = "select ID from sys_albums where Caption = '$albumname' and `Owner` =$logged";
  $result2 = mysql_query($q2);
  $row2 = mysql_fetch_array($result2);
  $albumid = $row2['ID'];

  // $sql3 = "UPDATE `sys_albums_objects` SET `obj_order` = `obj_order` + 1 WHERE `id_album` = " . $albumid;
  // $result3 = mysql_query($sql3);


  // $sql4 = "INSERT INTO sys_albums_objects SET id_object = $lastId,
  // `obj_order` = 0,
  // id_album = $albumid";
  // $result4 = mysql_query($sql4);

  $new_order =  mysql_fetch_array(mysql_query("SELECT MAX(`obj_order`) as max_order FROM sys_albums_objects WHERE  `id_album` = $albumid"));
  $new_index = $new_order[0] + 1;
  //print_r($new_order);
  //echo "INSERT INTO 'sys_albums_objects' ( id_album, id_object, obj_order) VALUES ( $albumid, $lastId, $new_index )";    
  mysql_query("INSERT INTO 'sys_albums_objects' ( id_album, id_object, obj_order) VALUES ( $albumid, $lastId, $new_index )");

  $sql5 = "UPDATE `sys_albums` SET `ObjCount` = `ObjCount` + 1 WHERE `ID` = " . $albumid;
  $result5 = mysql_query($sql5);
  move_uploaded_file($_FILES["file"]["tmp_name"], "../../flash/modules/video/files/".$_FILES["file"]["name"]);
  $filename = $lastId;


  /*********************************************************/
  /*File format conversion*/
  $inputFile=htmlspecialchars(stripslashes(trim($_FILES["file"]["name"])));
  $outputFile='_'.$lastId;
  $appRoot= $root;
  $mediaPath=$appRoot."flash/modules/video/files/";
  $vFormat="flv";
  //local
  // $FfmpegPath = $appRoot.'ProfilebuilderComponent/ffmpeg/ffmpeg';

   if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
         $FfmpegPath = $appRoot.'ProfilebuilderComponent/ffmpeg/ffmpeg';
    } else {
         $FfmpegPath = 'ffmpeg';
    }



  //server
  //$FfmpegPath = 'ffmpeg';

  $OutputVideo=$outputFile.".".$vFormat;
  $Input = ' -y -i "' . $inputFile . '"';
  $Options = " -ab 56 -ar 44100 -b 512k -r 15 -s 320x240 -f " . $vFormat . " "; // -vcodec flv
  chdir($mediaPath);
  @set_time_limit(1000);
  $CommandLimg=$FfmpegPath ." -itsoffset 10 -i ". $inputFile ." -an -ss 00:00:03 -an -r 1 -vframes 1 -s 640x360 -y ". $outputFile.".jpg";
  $CommandSimg=$FfmpegPath ." -itsoffset 10 -i ". $inputFile ." -an -ss 00:00:03 -an -r 1 -vframes 1 -s 140x102 -y ". $outputFile."_small.jpg";

  $Command = $FfmpegPath ." " . $Input . $Options . $OutputVideo;
  @exec($CommandLimg);
  @exec($CommandSimg);
  @exec($Command);
  @unlink($inputFile);
  /*End File format conversion*/
  print_r("{state: true, name:'" . str_replace("'", "\\'", $filename) . "', size:" . $_FILES["file"]["size"] . "}");
  exit;


  //print_r("{state: true, name:$lastId,  sql: $sql, lastId:$lastId,  q1: $q1, albumname:$albumname,  q2: $q2, albumid:$albumid,  sql3: $sql3, sql4:$sql4,sql5:$sql5  }");
  // exit;

}
