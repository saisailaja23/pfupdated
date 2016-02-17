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
$AgencyLike_List = "SELECT favouredfamily  FROM `family_favourite` WHERE `favouredby` = " . $logid . "";
$agency_query = mysql_query($AgencyLike_List);
$cmdtuples = mysql_num_rows($agency_query);
$arrColumns = explode(",", $columns);
$arrRows_agency_list1 = array();

while (($row = mysql_fetch_array($agency_query, MYSQL_BOTH)))
{
$agency_id = $row['favouredfamily'];

//$Agencydetails = mysql_query("select bx_groups_main.author_id,bx_groups_main.title,Profiles.Country,Profiles.City,Profiles.WEB_URL,bx_groups_main.thumb from Profiles,bx_groups_main where Profiles.ID = " . $agency_id . " and  Profiles.ID =author_id");

//$columns = '';
$Agencydetails = db_res("SELECT  ID,FirstName,Age,State,waiting,noofchildren,faith,ChildEthnicity,ChildAge,Adoptiontype,Avatar,NickName from Profiles where ID = " . $agency_id . "");


while (($row_agency = mysql_fetch_array($Agencydetails, MYSQL_BOTH)))
{
    
//avatarimages($row_agency[0]);  

$sImage   =   $site['url'].'modules/boonex/avatar/data/favourite/'.$row_agency[10].'.jpg';

list($width, $height) = getimagesize("$sImage");
                if($width>100){
                    $per=(($width-100)/$width)*100;
                    $height =$height-(($height*$per)/100);
                    $width=100;
               }
                if($height>100){
                    $per=(($height-100)/$height)*100;
                    $width =$width-(($width*$per)/100);
                    $height=100;
                 }
                $margin_left = ($width < 100)?(100-$width)/2:0;
                $width = ($margin_left == 0)?100:$width;
                $margin_top = ($height < 100)?(100-$height)/2:0;
                $height = ($margin_top == 0)?100:$height;
        //$sImage   =   $site['url'].'templates/tmpl_par/images/thumb_sample.png';

        $photo = '<img style="background-color: #EDEDED;border: 0px solid #CCCCCC"; width:'.$width.'px; height:'.$height.'px;margin-left:'.$margin_left.'px;margin-top:'.$margin_top.'px;" src="' . $sImage . '">';




$Couplename = db_arr("SELECT `FirstName`,Age FROM `Profiles` WHERE `Couple` = '$agency_id' LIMIT 1");
$Couple_name = $Couplename[FirstName]; 
$Couple_age = $Couplename[Age];

if($row_agency[4] !='') {
    
  $waiting =  $row_agency[4].' years'; 
  
}
else {
  $waiting = '';  
    
}

if($Couple_name !='' ) {
    
 // $profilename = $row_agency[1].' and '. $Couple_name;  
 $profilenames[0] = $row_agency[FirstName];
 $profilenames[1] = $Couple_name;
 sort($profilenames);
 $profilename = $profilenames[0].' & '. $profilenames[1]; 
}
else {
  $profilename = $row_agency[1];  
    
}

if($Couple_age !='') {
    
  $profileages= $row_agency[2].'/'. $Couple_age;  
  
}
else {
  $profileages = $row_agency[2];  
    
}

$user_eth = str_replace(',',', ', $row_agency[7]);
$user_faith = str_replace(',',', ', $row_agency[6]);

$childethnicity_ary = explode(",",$user_eth);
$childethnicity_sub = array_slice($childethnicity_ary,0,6);
$childethnicity = sizeof($childethnicity_ary) >= 7  ? implode(", ",$childethnicity_sub). '<a class = \'tooltip\' title= "'.str_replace(',',', ',$user_eth) .'"  href="javascript:void(0)" ><span title=" ">....More</span></a>' :implode(", ",$childethnicity_ary);

$faiths_ary = explode(",",$user_faith);
$faiths_sub = array_slice($faiths_ary,0,5);
$faiths =  sizeof($faiths_ary) >= 7 ? implode(", ",$faiths_sub) . '<a class = \'tooltip\' title= "'.str_replace(',',', ',$row_agency[6]) .'"  href="javascript:void(0)" ><span title=" ">....More</span></a>' :implode(", ",$faiths_ary);

$Childages_ary = explode(",",$row_agency[8]);
$Childages_sub = array_slice($Childages_ary,0,6);
$Childages = sizeof($Childages_ary) >= 7 ? implode(", ",$Childages_sub) . '<a class = \'tooltip\' title= "'.str_replace(',',', ',$row_agency[8]) .'"  href="javascript:void(0)" ><span title=" ">....More</span></a>' :implode(", ",$Childages_ary);





$arrValues = array();
array_push($arrRows_agency_list1, array(
  'id' =>$row_agency[0],
'profile_id' => $row_agency[0],
'profile_firstname' => (strlen($profilename)>21)?substr($profilename, 0, 26)."...":$profilename,  
'profile_age' => $profileages,
 
'profile_state' => $row_agency[3], 
'profile_waiting' => $waiting,     
'profile_noofchilds' => $row_agency[5], 
'profile_faith' => $faiths, 
'profile_childage' => $Childages,
//'profile_childethnicity' => $row_agency[7],
'profile_childethnicity' =>$childethnicity,
'profile_adoptiontype' => $row_agency[9],  
'profile_image' => $photo,  
'BM_id' => $logid, 
'Profile_name' => $row_agency[11],     
));
}
}

if ($cmdtuples > 0)
{
echo json_encode($arrRows_agency_list1);
}
  else
{
//echo json_encode(array(
//'id' => ' ',
//'response' => 'Could not find the searched item',
//'data' => ' ',
//'data_CFname' => ' '
//));
}
function avatarimages($iId) {
     global $dir;
     global $site;
   $sql_avt = "SELECT AV.*  FROM `bx_avatar_images` as AV INNER JOIN `Profiles` as P ON AV.`author_id` = P.`ID` AND AV.`ID` = P.`Avatar` WHERE P.`ID` = '$iId' ";
    $result_avt = mysql_query($sql_avt);
 //   echo $sql_avt;exit();
   // $aData1='';
    $row_avt = mysql_fetch_array($result_avt);
 

     $filename = $dir['root'].'modules/boonex/avatar/data/favourite/'.$row_avt[id].'.jpg'; 
    

   if (file_exists($filename)) {
   $aData1   =   $site['url'].'modules/boonex/avatar/data/favourite/'.$row_avt[id].'.jpg';

     }
   else {

   $filename_new = $dir['root'].'modules/boonex/avatar/data/avatarphotos/'.$row_avt[author_id].'.jpg';
   $sNewFilePath = $dir['root'].'modules/boonex/avatar/data/favourite/' . $row_avt[id] . '.jpg';

    if ($sNewFilePath != '' && $filename_new != '') {
   // imageResizee($filename_new, $sNewFilePath, $iWidth = 200, $iHeight = 200, true);
    imageResize_scroll_new($filename_new, $sNewFilePath, $iWidth = 300, $iHeight = 230, true);

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
    //$sNewFilePath_s2 = '/var/www/html/pf/modules/boonex/avatar/data/slider1/' . $row_avt[id] . '.jpg';
     $sNewFilePath1 = $dir['root'].'modules/boonex/avatar/data/favourite/' . $row_avt[id] . '.jpg';
      } 

       if ($sNewFilePath1 != '' && $filename_new1 != '') {
      //  imageResizee($filename_new1, $sNewFilePath1, $iWidth = 200, $iHeight = 200, true);
        imageResize_scroll_new($filename_new1, $sNewFilePath1, $iWidth = 300, $iHeight = 230, true);

       }


} 


    }   
  
}
?>
