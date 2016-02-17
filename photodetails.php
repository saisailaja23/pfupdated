<?php
define('BX_PROFILE_PAGE', 1);

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );    


$photouri = db_arr("SELECT NickName FROM Profiles WHERE ID='62'");

 $photourl = $photouri['NickName'];
 $photouris = $photourl.'-s-photos';
$sqlQuery = "SELECT `bx_photos_main`.`ID` as `id`, `bx_photos_main`.`Title` as `title`, `bx_photos_main`.`Ext` as `ext`, `bx_photos_main`.`Ext` as `ext`, `bx_photos_main`.`Uri` as `uri`, `bx_photos_main`.`Date` as `date`, `bx_photos_main`.`Size` as `size`, `bx_photos_main`.`Views` as `view`, `bx_photos_main`.`Rate`, `bx_photos_main`.`RateCount`, `bx_photos_main`.`Hash`, `bx_photos_main`.`Owner` as `ownerId`, `bx_photos_main`.`ID`, `sys_albums_objects`.`id_album`, `Profiles`.`NickName` as `ownerName`, `sys_albums`.`AllowAlbumView` FROM `bx_photos_main` left JOIN `Profiles` ON `Profiles`.`ID`=`bx_photos_main`.`Owner` left JOIN `sys_albums_objects` ON `sys_albums_objects`.`id_object`=`bx_photos_main`.`ID` left JOIN `sys_albums` ON `sys_albums`.`ID`=`sys_albums_objects`.`id_album` WHERE 1 AND `bx_photos_main`.`Status` ='approved' AND `bx_photos_main`.`Owner` ='62' AND `sys_albums`.`Status` ='active' AND `sys_albums`.`Type` ='bx_photos' AND `sys_albums`.`Uri` ='$photouris' ORDER BY `obj_order` ASC LIMIT 1";
echo $sqlQuery;
$aFilesList = db_res_assoc_arr($sqlQuery);


foreach ($aFilesList as $iKey => $aData) { 
             
  
       


$ext =  $aData['ext'] ;
 

$sHash =  $aData['Hash'] ;

echo  $GLOBALS['site']['url'] .'m/photos/'. 'get_image/' . 'file' .'/' . $sHash .'.'.$ext;


}

     
?>