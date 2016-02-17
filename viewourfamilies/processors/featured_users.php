<?php

/**
 * C:\Users\msi1308\AppData\Roaming\Sublime Text 2\Packages/PhpTidy/phptidy-sublime-buffer.php
 *
 * @author Aravind Buddha <aravind.buddha@mediaus.com>
 * @package default
 */
/*
 * Name: Prashanth A
 * Date:12/11/2013
 * Purpose: Retireving the values for displying and searching users
 */

require_once('../../inc/header.inc.php');
require_once('../../inc/profiles.inc.php');

$searchvalues = $_REQUEST['searchvalue'];

//$Avatar = "SELECT AV.`ID`,AV.`author_id`,P.FirstName,P.LastName, P.nickname  FROM `bx_avatar_images` as AV ,`Profiles` as P where  AV.`author_id` = P.`ID`
//            and AV.`ID` = P.`Avatar` AND profileType='2'  AND `P`.`Status` = 'Active' and `P`.`ProfileType` = 2 and `P`.`Avatar` != '0'
//            AND DATEDIFF( NOW(), P.DateLastLogin) < 180 AND (`P`.`Couple` = 0 or `P`.`Couple` > `P`.`ID`) ORDER BY  RAND() , `P`.`DateLastLogin` DESC LIMIT 4";
$Avatar = "SELECT AV.`ID`,AV.`author_id`,P.FirstName,P.LastName, P.nickname  FROM `bx_avatar_images` as AV ,`Profiles` as P, sys_acl_levels_members as S 
                WHERE  AV.`author_id` = P.`ID`
                AND S.`IDMember` = P.`ID`
                AND S.`IDLevel` = 24
                AND S.`DateExpires` > Now()
                AND AV.`ID` = P.`Avatar` 
                AND profileType='2'  
                AND `P`.`Status` = 'Active' 
                AND `P`.`ProfileType` = 2 
                AND `P`.`Avatar` != '0'
                AND DATEDIFF( NOW(), P.DateLastLogin) < 180 
                AND (`P`.`Couple` = 0 or `P`.`Couple` > `P`.`ID`) 
                ORDER BY  RAND() , `P`.`DateLastLogin` DESC LIMIT 4";
$query = mysql_query($Avatar);
$cmdtuples = mysql_num_rows($query);
$arrRows_Profiles = array();
while (($row = mysql_fetch_array($query, MYSQL_BOTH))) {

//    $NickName = $site['url'] . 'moreaboutus.php?id=' . $row[1];
    $NickName = $site['url'] . $row['nickname'].'/about';
    $filename = "" . $site['url'] . "modules/boonex/avatar/data/favourite/" . $row[0] . ".jpg";
    if (file_exists($filename)) {
        
    } else {
//  avatar_resize($row[1]);
    }


    $arrValues = array();
    array_push($arrRows_Profiles, array(
        'id' => $row[0],
        'data' => '<a href= "' . $NickName . "" . '" ><img width="174" height="134" src="' . $filename . '"></a>',
        'data_CFname' => $username,
        'response' => ''
    ));
}

if ($cmdtuples > 0) {
    echo json_encode(
            $arrRows_Profiles
    );
} else {
    echo json_encode(array('id' => ' ', 'response' => 'Could not find the searched item', 'data' => ' ', 'data_CFname' => ' '));
}

/**
 *
 *
 * @param unknown $iId
 */
function avatar_resize($iId) {


    $sql_avt = "SELECT AV.*  FROM `bx_avatar_images` as AV INNER JOIN `Profiles` as P ON AV.`author_id` = P.`ID` AND AV.`ID` = P.`Avatar` WHERE P.`ID` = '$iId'";
    $result_avt = mysql_query($sql_avt);
    //   echo $sql_avt;exit();
    // $aData1='';
    $row_avt = mysql_fetch_array($result_avt);
    $filename = $site['url'] . 'modules/boonex/avatar/data/favourite/' . $row_avt[id] . '.jpg';
    if (file_exists($filename)) {
        $aData1 = $site['url'] . 'modules/boonex/avatar/data/favourite/' . $row_avt[id] . '.jpg';
    } else {

        $filename_new = $site['url'] . 'modules/boonex/avatar/data/avatarphotos/' . $row_avt[author_id] . '.jpg';
        $sNewFilePath = $site['url'] . 'modules/boonex/avatar/data/favourite/' . $row_avt[id] . '.jpg';

        if ($sNewFilePath != '' && $filename_new != '') {
            // imageResizee($filename_new, $sNewFilePath, $iWidth = 200, $iHeight = 200, true);
            imageResize_scroll($filename_new, $sNewFilePath, $iWidth = 145, $iHeight = 200, true);
        }



        if (!file_exists($filename_new)) {

            $photouri = db_arr("SELECT NickName FROM Profiles WHERE ID='$iId'");

            $photourl = $photouri['NickName'];
            $photouris = $photourl . '-s-photos';

            $sqlQuery = "SELECT `bx_photos_main`.`ID` as `id`, `bx_photos_main`.`Title` as `title`, `bx_photos_main`.`Ext` as `ext`, `bx_photos_main`.`Ext` as `ext`, `bx_photos_main`.`Uri` as `uri`, `bx_photos_main`.`Date` as `date`, `bx_photos_main`.`Size` as `size`, `bx_photos_main`.`Views` as `view`, `bx_photos_main`.`Rate`, `bx_photos_main`.`RateCount`, `bx_photos_main`.`Hash`, `bx_photos_main`.`Owner` as `ownerId`, `bx_photos_main`.`ID`, `sys_albums_objects`.`id_album`, `Profiles`.`NickName` as `ownerName`, `sys_albums`.`AllowAlbumView` FROM `bx_photos_main` left JOIN `Profiles` ON `Profiles`.`ID`=`bx_photos_main`.`Owner` left JOIN `sys_albums_objects` ON `sys_albums_objects`.`id_object`=`bx_photos_main`.`ID` left JOIN `sys_albums` ON `sys_albums`.`ID`=`sys_albums_objects`.`id_album` WHERE 1 AND `bx_photos_main`.`Status` ='approved' AND `bx_photos_main`.`Owner` ='$iId' AND `sys_albums`.`Status` ='active' AND `sys_albums`.`Type` ='bx_photos' AND `sys_albums`.`Uri` ='$photouris'  ORDER BY `obj_order` ASC LIMIT 1"; //exit();
            $aFilesList = db_res_assoc_arr($sqlQuery);

            foreach ($aFilesList as $iKey => $aData) {
                $ext = $aData['ext'];
                $sHash = $aData['Hash'];
            }
            $sql_avt1 = "SELECT ID from bx_photos_main where Hash = '$sHash' ";
            $result_avt1 = mysql_query($sql_avt1);
            $num_ids = mysql_num_rows($result_avt);

            $row_avt1 = mysql_fetch_array($result_avt1);

            if ($num_ids > 0) {
                $filename_new1 = '/var/www/html/pf/modules/boonex/photos/data/files/' . $row_avt1[ID] . '.' . $ext;
            }

            $sql_avt = "SELECT AV.*  FROM `bx_avatar_images` as AV INNER JOIN `Profiles` as P ON AV.`author_id` = P.`ID` AND AV.`ID` = P.`Avatar` WHERE P.`ID` = '$iId'";
            $result_avt = mysql_query($sql_avt);
            $num_rows = mysql_num_rows($result_avt);

            $row_avt = mysql_fetch_array($result_avt);
            if ($num_rows > 0) {
                //$sNewFilePath_s2 = '/var/www/html/pf/modules/boonex/avatar/data/slider1/' . $row_avt[id] . '.jpg';
                $sNewFilePath1 = '/var/www/html/pf/modules/boonex/avatar/data/favourite/' . $row_avt[id] . '.jpg';
            }

            if ($sNewFilePath1 != '' && $filename_new1 != '') {
                //  imageResizee($filename_new1, $sNewFilePath1, $iWidth = 200, $iHeight = 200, true);
                imageResize_scroll($filename_new1, $sNewFilePath1, $iWidth = 145, $iHeight = 200, true);
            }
        }
    }
}

?>
