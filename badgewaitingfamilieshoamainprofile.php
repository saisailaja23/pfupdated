<?php
define('BX_PROFILE_PAGE', 1);

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

bx_import('BxTemplProfileView');
bx_import('BxDolInstallerUtils');


if(isset($_GET['profile'])){

$ffprofile = $_GET['profile'];

   $oProfile = new BxBaseProfileGenerator($ffprofile);
      $aCopA = $oProfile->_aProfile;
      $aCopB = $oProfile->_aCouple;

      $aData = $ffprofile.";-".$aCopA[FirstName].";-";

      $aData .= $aCopB[FirstName].";-";

      //$copAdbp = mb_substr($aCopA[DearBirthParent], 0, 500);
      //$aData .= $copAdbp = strip_tags($copAdbp, '<p>').";-";

       //echo $aData;


      $sql = "SELECT AV.*  FROM `bx_avatar_images` as AV INNER JOIN `Profiles` as P ON AV.`author_id` = P.`ID` WHERE P.`ID` = '$ffprofile' ORDER BY AV.id DESC";
    //  echo $sql;
      $result = mysql_query($sql);
      $aData1='';
      while($row = mysql_fetch_array($result))
       {
       $filename = '/var/www/html/pf/modules/boonex/avatar/data/images/'.$row['id'].'.jpg';
      
        if (file_exists($filename)) {
        $filename12 = '/var/www/html/pf/modules/boonex/avatar/data/avatarphotos/'.$row[author_id].'.jpg';

         if (file_exists($filename12)) {

         $aData1 = $site['url'].'modules/boonex/avatar/data/avatarphotos/'.$row[author_id].'.jpg';

            }

         else {

         $photouri = db_arr("SELECT NickName FROM Profiles WHERE ID='$ffprofile'");

         $photourl = $photouri['NickName'];
         $photouris = $photourl.'-s-photos';

        $sqlQuery = "SELECT `bx_photos_main`.`ID` as `id`, `bx_photos_main`.`Title` as `title`, `bx_photos_main`.`Ext` as `ext`, `bx_photos_main`.`Ext` as `ext`, `bx_photos_main`.`Uri` as `uri`, `bx_photos_main`.`Date` as `date`, `bx_photos_main`.`Size` as `size`, `bx_photos_main`.`Views` as `view`, `bx_photos_main`.`Rate`, `bx_photos_main`.`RateCount`, `bx_photos_main`.`Hash`, `bx_photos_main`.`Owner` as `ownerId`, `bx_photos_main`.`ID`, `sys_albums_objects`.`id_album`, `Profiles`.`NickName` as `ownerName`, `sys_albums`.`AllowAlbumView` FROM `bx_photos_main` left JOIN `Profiles` ON `Profiles`.`ID`=`bx_photos_main`.`Owner` left JOIN `sys_albums_objects` ON `sys_albums_objects`.`id_object`=`bx_photos_main`.`ID` left JOIN `sys_albums` ON `sys_albums`.`ID`=`sys_albums_objects`.`id_album` WHERE 1 AND `bx_photos_main`.`Status` ='approved' AND `bx_photos_main`.`Owner` ='$ffprofile' AND `sys_albums`.`Status` ='active' AND `sys_albums`.`Type` ='bx_photos' AND `sys_albums`.`Uri` ='$photouris'  ORDER BY `obj_order` ASC LIMIT 1";


        $aFilesList = db_res_assoc_arr($sqlQuery);

        foreach ($aFilesList as $iKey => $aDatas) {

            $ext =  $aDatas['ext'] ;

            $sHash =  $aDatas['Hash'] ;

            $aData1 =  $GLOBALS['site']['url'] .'m/photos/'. 'get_image/' . 'file' .'/' . $sHash .'.'.$ext;

        }

                  

  	break;

    }
       }
}
      $fm[]= $aData.$aData1;

          }
     echo implode("#####", $fm);





?>