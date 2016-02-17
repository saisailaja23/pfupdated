<?php
/*********************************************************************************
* Name:    Prashanth A
* Date:    12/12/2013
* Purpose: Listing the families liked by birth mother
*********************************************************************************/
require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');
//require_once ('../../inc/classes/BxDolTemplate.php');

$logid = getLoggedId();
//$member = getProfileInfo($logid);

$Agencydetails = db_res("SELECT ID,Status,FirstName,`Avatar` FROM `Profiles` WHERE `ID` = '$logid'");
$cmdtuples = 1;
$arrRows_agency_list1 = array();
while (($row_agency = mysql_fetch_array($Agencydetails, MYSQL_BOTH)))
{
//  echo  $row_agency['ID'].'<br/>'; 
//avatarimages($row_agency[0]);  
$sImage   =   $site['url'].'modules/boonex/avatar/data/favourite/'.$row_agency['Avatar'].'.jpg?'.time();



//list($width, $height) = getimagesize("$sImage");
//                if($width>100){
//                    $per=(($width-100)/$width)*100;
//                    $height =$height-(($height*$per)/100);
//                    $width=100;
//               }
//                if($height>100){
//                    $per=(($height-100)/$height)*100;
//                    $width =$width-(($width*$per)/100);
//                    $height=100;
//                 }
//                $margin_left = ($width < 100)?(100-$width)/2:0;
//                $width = ($margin_left == 0)?100:$width;
//                $margin_top = ($height < 100)?(100-$height)/2:0;
//                $height = ($margin_top == 0)?100:$height;
//
//
//
//
//$profile_link = $site['url'].'extra_profile_view_12.php?id='.$row_agency['ID'].'&approve=1';

$photo = '<a href= "'.$profile_link.'" /><img style="width:100%; height:113px;margin-left:0;margin-top:0; background-color: #EDEDED;border: 1px solid #CCCCCC";" src="' . $sImage . '"></a>';

$profile_id = $row_agency['ID'];

$Couplename = db_arr("SELECT `FirstName`,Age,Status FROM `Profiles` WHERE `Couple` = '$profile_id'");
$Couple_name = $Couplename[FirstName]; 
$Profile_firstname = $row_agency[FirstName];
$Profile_status = $row_agency[Status];

$match_status= db_arr("SELECT `status` FROM `watermarkimages` WHERE `author_id` = '$profile_id'");
$matchstatus = $match_status[status]; 
$profilenames = array();
if($Couple_name !='' ) {
$profilenames[] = $Profile_firstname;
$profilenames[] = $Couple_name;
    
    
sort($profilenames);
$profilename = $profilenames[0].' & '. $profilenames[1];  
  
 
  
}
else {
  $profilename = $Profile_firstname;  
    
}

$test = "agency".$profile_id;    
$time = ($time == '') ? time() : (int)$time;  
  
$membership_status= db_arr("SELECT  `sys_acl_levels_members`.IDLevel as ID,
                `sys_acl_levels`.Name as Name,
                UNIX_TIMESTAMP(`sys_acl_levels_members`.DateStarts) as DateStarts,
                UNIX_TIMESTAMP(`sys_acl_levels_members`.DateExpires) as DateExpires,
                `sys_acl_levels_members`.`TransactionID` AS `TransactionID`
        FROM    `sys_acl_levels_members`
                RIGHT JOIN Profiles
                ON `sys_acl_levels_members`.IDMember = Profiles.ID
                    AND (`sys_acl_levels_members`.DateStarts IS NULL
                        OR `sys_acl_levels_members`.DateStarts <= FROM_UNIXTIME($time))
                    AND (`sys_acl_levels_members`.DateExpires IS NULL
                        OR `sys_acl_levels_members`.DateExpires > FROM_UNIXTIME($time))
                LEFT JOIN `sys_acl_levels`
                ON `sys_acl_levels_members`.IDLevel = `sys_acl_levels`.ID

        WHERE   Profiles.ID = $logid

        ORDER BY `sys_acl_levels_members`.DateStarts DESC

        LIMIT 0, 1");
$mem_status = $membership_status[Name]; 
    
$arrValues = array();
array_push($arrRows_agency_list1, array(
'profile_id' => $profile_id,
'profile_firstname' => $profilename, 
'selected_random' => $test,   
'selected_active' => $Profile_status == 'Active' ? 'checked=checked' : '',  
'selected_inactive' => $Profile_status == 'Approval' ? 'checked=checked' : '', 
'selected_match' => $matchstatus == 'Matched' ? 'checked=checked' : '', 
'selected_placed' => $matchstatus == 'Placed' ? 'checked=checked' : '',    
'profile_image' => $photo,
'membership_status' => $mem_status     
    
   
));
}

if ($cmdtuples > 0)
{
echo json_encode($arrRows_agency_list1);
}
else
{
echo json_encode(array(
'id' => ' ',
'response' => 'Could not find the searched item',
'data' => ' ',
'data_CFname' => ' '
));
}

/*
function avatarimages($iId) {
     global $dir;
     global $site;
   $sql_avt = "SELECT AV.*  FROM `bx_avatar_images` as AV INNER JOIN `Profiles` as P ON AV.`author_id` = P.`ID` AND AV.`ID` = P.`Avatar` WHERE P.`ID` = '$iId' ";
    $result_avt = mysql_query($sql_avt);
    echo $sql_avt;exit();
    $aData1='';
    $row_avt = mysql_fetch_array($result_avt);
 

     $filename = $dir['root'].'modules/boonex/avatar/data/favourite/'.$row_avt[id].'.jpg'; 
    

   if (file_exists($filename)) {
   $aData1   =   $site['url'].'modules/boonex/avatar/data/favourite/'.$row_avt[id].'.jpg';

     }
   else {

   $filename_new = $dir['root'].'modules/boonex/avatar/data/avatarphotos/'.$row_avt[author_id].'.jpg';
   $sNewFilePath = $dir['root'].'modules/boonex/avatar/data/favourite/' . $row_avt[id] . '.jpg';

    if ($sNewFilePath != '' && $filename_new != '') {
    imageResizee($filename_new, $sNewFilePath, $iWidth = 200, $iHeight = 200, true);
    imageResize_scroll($filename_new, $sNewFilePath, $iWidth = 300, $iHeight = 230, true);

   }



 if(!file_exists($filename_new)) {

        $photouri = db_arr("SELECT NickName FROM Profiles WHERE ID='$iId'");

        $photourl = $photouri['NickName'];
        $photouris = $photourl.'-s-photos';

        $sqlQuery = "SELECT `bx_photos_main`.`ID` as `id`, `bx_photos_main`.`Title` as `title`, `bx_photos_main`.`Ext` as `ext`, `bx_photos_main`.`Ext` as `ext`, `bx_photos_main`.`Uri` as `uri`, `bx_photos_main`.`Date` as `date`, `bx_photos_main`.`Size` as `size`, `bx_photos_main`.`Views` as `view`, `bx_photos_main`.`Rate`, `bx_photos_main`.`RateCount`, `bx_photos_main`.`Hash`, `bx_photos_main`.`Owner` as `ownerId`, `bx_photos_main`.`ID`, `sys_albums_objects`.`id_album`, `Profiles`.`NickName` as `ownerName`, `sys_albums`.`AllowAlbumView` FROM `bx_photos_main` left JOIN `Profiles` ON `Profiles`.`ID`=`bx_photos_main`.`Owner` left JOIN `sys_albums_objects` ON `sys_albums_objects`.`id_object`=`bx_photos_main`.`ID` left JOIN `sys_albums` ON `sys_albums`.`ID`=`sys_albums_objects`.`id_album` WHERE 1 AND `bx_photos_main`.`Status` ='approved' AND `bx_photos_main`.`Owner` ='$iId' AND `sys_albums`.`Status` ='active' AND `sys_albums`.`Type` ='bx_photos' AND `sys_albums`.`Uri` ='$photouris'  ORDER BY `obj_order` ASC LIMIT 1";//exit();
        $aFilesList = db_res_assoc_arr($sqlQuery);
       
        foreach ($aFilesList as $iKey => $aData) {
        $ext =  $aData['ext'] ;
        $sHash =  $aData['Hash'] ;
    
        }
       
      
      $sql_avt1 = "SELECT ID from bx_photos_main where Hash = '$sHash' ";
      $result_avt1 = mysql_query($sql_avt1);
      $num_ids = mysql_num_rows($result_avt);

      $row_avt1 = mysql_fetch_array($result_avt1);

      if($num_ids > 0) {
      $filename_new1 = $dir['root'].'modules/boonex/photos/data/files/'.$row_avt1[ID].'.'.$ext;
       }

      $sql_avt = "SELECT AV.*  FROM `bx_avatar_images` as AV INNER JOIN `Profiles` as P ON AV.`author_id` = P.`ID` AND AV.`ID` = P.`Avatar` WHERE P.`ID` = '$iId'";
      $result_avt = mysql_query($sql_avt);
      $num_rows = mysql_num_rows($result_avt);

      $row_avt = mysql_fetch_array($result_avt);
      if($num_rows > 0) {
    $sNewFilePath_s2 = '/var/www/html/pf/modules/boonex/avatar/data/slider1/' . $row_avt[id] . '.jpg';
     $sNewFilePath1 = $dir['root'].'modules/boonex/avatar/data/favourite/' . $row_avt[id] . '.jpg';
      } 

       if ($sNewFilePath1 != '' && $filename_new1 != '') {
        imageResizee($filename_new1, $sNewFilePath1, $iWidth = 200, $iHeight = 200, true);
        imageResize_scroll($filename_new1, $sNewFilePath1, $iWidth = 300, $iHeight = 230, true);

       }


} 


    }   
  
}
 
 */
?>

