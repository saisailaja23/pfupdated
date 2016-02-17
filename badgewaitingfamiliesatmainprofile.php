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


     // $sql = "SELECT AV.*  FROM `bx_avatar_images` as AV INNER JOIN `Profiles` as P ON AV.`author_id` = P.`ID` WHERE P.`ID` = '$ffprofile' ORDER BY AV.id DESC";
$sql = "SELECT Avatar from `Profiles` WHERE ID = '$ffprofile'";
    //  echo $sql;
      $result = mysql_query($sql);
      $aData1='';
     /* while($row = mysql_fetch_array($result))
       {
       $filename = '/var/www/html/pf/modules/boonex/avatar/data/images/'.$row['id'].'.jpg';
        //  $filename = 'D:/xampp/htdocs/www.parentfinder.com/modules/boonex/avatar/data/images/'.$row['id'].'.jpg';
        //  echo $filename;
        if (file_exists($filename)) {
        //  $aData1 = "http://localhost/www.parentfinder.com/modules/boonex/avatar/data/images/".$row['id'].".".'jpg';
            $aData1 = "http://www.parentfinder.com/modules/boonex/avatar/data/images/".$row['id'].".".'jpg';
        //  $aData1 = "http://www.parentfinder.com/modules/boonex/photos/data/files/".$row['ID']."_t.".$row['Ext'];
        //$aData1 .= $row['Title'].";-";
        //$aData1 .= $row['Desc'].";-";

  	break;

    }
       }  */


  while($row = mysql_fetch_array($result))
       {
//        $filename = '/var/www/html/pf/modules/boonex/avatar/data/images/'.$row['id'].'.jpg';
//        if (file_exists($filename)) {
//          //$aData1 = "http://www.parentfinder.com/modules/boonex/avatar/data/images/".$row['id'].".".'jpg';
//           $filename12 = '/var/www/html/pf/modules/boonex/avatar/data/avatarphotos/' . $row[author_id] . '.jpg';
//
//           if (file_exists($filename12)) {
//
//                    $fname = $row[author_id] . '.jpg';
//
//                    $filename12 = '/var/www/html/pf/modules/boonex/avatar/data/avatarphotos/' . $row[author_id] . '.jpg';
//
//                    // $aData1 = 'http://www.parentfinder.com/modules/boonex/avatar/data/avatarphotos/'.$row[author_id].'.jpg';
//                    // $aData1 = 'http://www.parentfinder.com/modules/boonex/avatar/data/avatarphotos/'.$row[author_id].'.jpg';
//
//                    $sNewFilePath = '/var/www/html/pf/modules/boonex/avatar/data/resizedphoto/' . $fname;
                    if($row[Avatar] != 0) {
                    $aData1 = $site['url'].'modules/boonex/avatar/data/favourite/' . $row[Avatar] . '.jpg';
                       }
//                    if ($sNewFilePath != '' && $filename12 != '') {
//                        imageResizee($filename12, $sNewFilePath, $iWidth = 200, $iHeight = 200, true);
//                    }
//                }
//
//           else {
//
//                $aData1 = $site['url']."modules/boonex/avatar/data/images/".$row['id'].".".'jpg';
//
//                       }
//
//
//  	break;
//
//    }
       }


      $fm[]= $aData.$aData1;

          }
     echo implode("#####", $fm);
?>