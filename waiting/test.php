<?php
define('BX_PROFILE_PAGE', 1);

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );    

$id = $_GET['profile'];

bx_import('BxTemplProfileView');
bx_import('BxDolInstallerUtils');

$oProfile = new BxBaseProfileGenerator( $id );  
$aCopA = $oProfile->_aProfile;
$aCopB = $oProfile->_aCouple;
//print_r($oProfile);

$oPPV = new BxTemplProfileView($oProfile, $site, $dir);
//echo $oPPV->getCode(); exit;
$aData = ''; 
 
$copAdesc = $aCopA[DescriptionMe];
$aData .= $copAdesc = strip_tags($copAdesc, '<img>').";-";

$copBdesc = $aCopB[DescriptionMe];
$aData .= $copBdesc = strip_tags($copBdesc, '<img>').";-";

$copAdbp = $aCopA[DearBirthParent];
$aData .= $copAdbp = $copAdbp.";-";

$aData .= $aCopA[FirstName].";-";
$aData .= strip_tags($aCopB[FirstName]).";-";
$aData .= $aCopA[Occupation].";-";
$aData .= $aCopB[Occupation].";-";
$aData .= $aCopA[Education].";-";
$aData .= $aCopB[Education].";-";

if(isset($_GET['v'])){

$sql = "SELECT * FROM `RayVideoFiles` WHERE `Owner` = '$id'";
$result = mysql_query($sql);
$nos = mysql_num_rows($result);
if ($nos >= 1) {
while($row = mysql_fetch_array($result))
  { 
    if($row['Source'] == 'youtube' && $nos >= 1){
    $aData .= "<iframe title='YouTube video player' width='560' height='345' src='http://www.youtube.com/embed/".$row['Video']."' frameborder='0' allowfullscreen></iframe>;-";
    $aData .= $row['Description'].";-";
    }elseif($row['Source'] != 'youtube' && $nos >= 1){
    $aData .=  
    <<<EOF
	
	<a href='http://www.parentfinder.com/flash/modules/video/files/{$row[ID]}.flv' style='display:block;width:560px;height:345px' id='player'></a> 
        <script>
            flowplayer("player", "flowplayer-3.2.6.swf",{
             
	          width: '560',
	          height: '345'

               });
        </script>;-
EOF;
    $aData .= $row['Description'].";-";
    }
	/*else{
    $aData .= "Video Not Avalable;-";
    $aData .= " ;-";
    }*/
  }
  }
  else{
    $aData .= "Video Not Available;-";
    $aData .= " ;-";
    }

}
if(isset($_GET['deta']) && !isset($_GET['v'])){

$sql = "SELECT * FROM `bx_photos_main` WHERE `Owner` = '$id'";
$result = mysql_query($sql);

while($row = mysql_fetch_array($result))
  { 
    $filename = '/var/www/html/pf/modules/boonex/photos/data/files/'.$row['ID'].".".$row['Ext'];
    if (file_exists($filename)) {
    $aData .= "http://www.parentfinder.com/m/photos/get_image/file/".$row['Hash'].".".$row['Ext'].";-";
    $aData .= $row['Title'].";-";
    $aData .= $row['Desc'].";-";
    }
  }

}else{
$sql = "SELECT * FROM `bx_photos_main` WHERE `Owner` = '$id'";
$result = mysql_query($sql);

while($row = mysql_fetch_array($result))
  { 
    $filename = '/var/www/html/pf/modules/boonex/photos/data/files/'.$row['ID'].".".$row['Ext'];
    if (file_exists($filename)) {
    $aData .= "http://www.parentfinder.com/m/photos/get_image/file/".$row['Hash'].".".$row['Ext'].";-";
    }
  }
 }
 $aData .= $aCopA[FavoriteBooks].";-"; 
 $aData .= $aCopB[FavoriteBooks].";-";  
echo $aData;
?>