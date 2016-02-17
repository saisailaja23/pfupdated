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
$from = ($_REQUEST['posStart'] == '')?0:$_REQUEST['posStart'];
$count = ($_REQUEST['count'] == '')?9:$_REQUEST['count'];

$loadFrom   = ($_REQUEST['loadFrom'] == '')?'':$_REQUEST['loadFrom'];
$agencyId   = ($_REQUEST['agencyId'] == '')?'':$_REQUEST['agencyId'];
$adoptionAgency   = ($_REQUEST['adoptionAgency'] == '')?'':$_REQUEST['adoptionAgency'];

if($loadFrom == 'badge' && ($agencyId))
{
   $sql_getAgencyFilter =  'AND `AdoptionAgency` = '.$agencyId;
}
else
{
    $sql_getAgencyFilter  = '';
}

if($adoptionAgency)
{
   $sql_getAgencyFilter =  'AND `AdoptionAgency` = '.$adoptionAgency;
}
else
{
    $sql_getAgencyFilter  = '';
}

$searchvalues = $_REQUEST['sortvalue'];
$searchtype = $_REQUEST['type'];


//echo $searchvalues.$searchtype;

if($searchtype == 'Familysize' && $_REQUEST['sortvalue'] == '3 ') {
   $searchvalues == '3+';
}

       // '".$searchvalues."'
//if($searchvalues != '') {
//
//  echo $AgencyLike_List = "SELECT `Profiles`. `ID`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles`,sys_acl_levels_members WHERE sys_acl_levels_members.IDLevel IN('14','15','18','20','24','23') AND `Profiles`.`Status` = 'Active' and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0' AND sys_acl_levels_members.IDMember = `Profiles`. `ID` AND (DateExpires >= NOW() OR DateExpires IS NULL) AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) Group By sys_acl_levels_members.IDMember ORDER BY '".$searchvaluess."' DESC";
 
 //if($searchvalues == 'sortby') {
 // $AgencyLike_List = "SELECT `Profiles`. `ID`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles`,sys_acl_levels_members WHERE sys_acl_levels_members.IDLevel IN('14','15','18','20','24','23') AND `Profiles`.`Status` = 'Active' and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0' AND sys_acl_levels_members.IDMember = `Profiles`. `ID` AND (DateExpires >= NOW() OR DateExpires IS NULL) AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) Group By sys_acl_levels_members.IDMember ORDER BY `Profiles`.`DateLastLogin` DESC";
//}   
 // else {  

    switch ($searchtype) {
    case 'Region':
    $AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE `Profiles`.`Status` = 'Active' and Profiles.Region ='$searchvalues' and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'   AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter limit $from , $count";  
    if($from == 0){
        $totalCount = "select count(`Profiles`. `ID`) FROM `Profiles` WHERE `Profiles`.`Status` = 'Active' and Profiles.Region ='$searchvalues' and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'   AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter";
        $total_count = mysql_query($totalCount);
        $rowCount = mysql_fetch_array($total_count);
        $tCount = $rowCount[0];
    }
    break;
    case 'Religion':
    $AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and (Profiles.Religion LIKE '%$searchvalues%' OR Profiles.faith LIKE '%$searchvalues%') and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'   AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter limit $from , $count";  
    if($from == 0){
        $totalCount = "select count(`Profiles`. `ID`) FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and (Profiles.Religion LIKE '%$searchvalues%' OR Profiles.faith LIKE '%$searchvalues%') and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'   AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter";
        $total_count = mysql_query($totalCount);
        $rowCount = mysql_fetch_array($total_count);
        $tCount = $rowCount[0];
    }
    break;
    case 'Familysize':
    if($searchvalues !='3 ') {   
    $AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and Profiles.noofchildren=$searchvalues and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter limit $from , $count";  
    if($from == 0){
        $totalCount = "select count(`Profiles`. `ID`) FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and Profiles.noofchildren=$searchvalues and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter";
        $total_count = mysql_query($totalCount);
        $rowCount = mysql_fetch_array($total_count);
        $tCount = $rowCount[0];
    }
     }
    else {
    $AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and Profiles.noofchildren >= $searchvalues and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter limit $from , $count";  
    if($from == 0){
        $totalCount = "select count(`Profiles`. `ID`) FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and Profiles.noofchildren >= $searchvalues and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter";
        $total_count = mysql_query($totalCount);
        $rowCount = mysql_fetch_array($total_count);
        $tCount = $rowCount[0];
    }
     }
    break;
    case 'Sortby':
    $AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active'  and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter ORDER BY Profiles.DateReg DESC limit $from , $count";  
    if($from == 0){
        $totalCount = "select count(`Profiles`. `ID`)  FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active'  and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter ORDER BY Profiles.DateReg DESC";
        $total_count = mysql_query($totalCount);
        $rowCount = mysql_fetch_array($total_count);
        $tCount = $rowCount[0];
    }
    break;
    case 'ethnicity':
    if($from == 0){
        $totalCount = "select count(`Profiles`. `ID`) FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and FIND_IN_SET( '$searchvalues' ,`ChildEthnicity` ) and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter";
        $total_count = mysql_query($totalCount);
        $rowCount = mysql_fetch_array($total_count);
        $tCount = $rowCount[0];
    }
    $AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and FIND_IN_SET( '$searchvalues' ,`ChildEthnicity` ) and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter limit $from , $count";  
    break;
    default:
    if($from == 0){
        $totalCount = "select count(`Profiles`. `ID`)  FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0' AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter ORDER BY `Profiles`.`DateReg` DESC";
        $total_count = mysql_query($totalCount);
        $rowCount = mysql_fetch_array($total_count);
        $tCount = $rowCount[0];
       
    }
  // $AgencyLike_List = "SELECT `Profiles`. `ID`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0' AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter ORDER BY `Profiles`.`DateReg` , RAND() DESC limit $from , $count";
      $AgencyLike_List = "SELECT `Profiles`. `ID`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0' AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter ORDER BY RAND() limit $from , $count";

    break;

}


$agency_query = mysql_query($AgencyLike_List);array_rand($agency_query);
$cmdtuples = mysql_num_rows($agency_query);
$arrColumns = explode(",", $columns);
$arrRows_agency_list1 = array();
header ("content-type: text/xml");
$xml = '<?xml version="1.0" encoding="UTF-8" ?>';
if($from > 0){
    $xml .= '<data pos="'.$from.'">';
}
else{
$xml .= '<data total_count="'.$tCount.'">';
}
$count = $from;
while (($row = mysql_fetch_array($agency_query, MYSQL_BOTH)))
{
$agency_id = $row['ID'];

//$Agencydetails = mysql_query("select bx_groups_main.author_id,bx_groups_main.title,Profiles.Country,Profiles.City,Profiles.WEB_URL,bx_groups_main.thumb from Profiles,bx_groups_main where Profiles.ID = " . $agency_id . " and  Profiles.ID =author_id");

//$columns = '';
$Agencydetails = db_res("SELECT  ID,FirstName,Age,State,waiting,noofchildren,faith,ChildEthnicity,ChildAge,Adoptiontype,Avatar,NickName from Profiles where ID = " . $agency_id . "");


while (($row_agency = mysql_fetch_array($Agencydetails, MYSQL_BOTH)))
{
    
avatarimages($row_agency[0]);  

$sImage   =   $site['url'].'modules/boonex/avatar/data/favourite/'.$row_agency[10].'.jpg';



list($width, $height) = getimagesize("$sImage");
                if($width>300){
                    $per=(($width-300)/$width)*100;
                    $height =$height-(($height*$per)/100);
                    $width=300;
               }
                if($height>230){
                    $per=(($height-230)/$height)*100;
                    $width =$width-(($width*$per)/100);
                    $height=230;
                 }
                $margin_left = ($width < 300)?(300-$width)/2:0;
                $width = ($margin_left == 0)?300:$width;
                $margin_top = ($height < 230)?(230-$height)/2:0;
                $height = ($margin_top == 0)?230:$height;


$photo = '<img style="width:'.$width.'px; height:'.$height.'px;margin-left:'.$margin_left.'px;margin-top:'.$margin_top.'px; background-color: #EDEDED;" src="' . $sImage . '">';



$Couplename = db_arr("SELECT `FirstName`,Age FROM `Profiles` WHERE `Couple` = '$agency_id' LIMIT 1");
$Couple_name = $Couplename[FirstName]; 
$Couple_age = $Couplename[Age];

if($row_agency[4] !='') {
    
//  $waiting =  $row_agency[4].' years'; 
    $waiting =  $row_agency[4];
  
}
else {
  $waiting = 'N/A';  
    
}

if($Couple_name !='' ) {
 $Fname =  $row_agency[1];   
 //$Couple_names =   str_replace('&', '&amp;', $Couple_name);    
    
 $profilename = $Fname.' and '. $Couple_name;  
  
}
else {
  $Fnames = $row_agency[1];  
  $profilename = $Fnames;  
    
}

if($Couple_age !='') {
    
  $profileages= $row_agency[2].'/'. $Couple_age;  
  
}
else {
  $profileages = $row_agency[2];  
    
}
$profileages   = ($profileages)?$profileages:"N/A";
$childethnicity = str_replace(',',', ', $row_agency[7]);
$faiths = str_replace(',',', ', $row_agency[6]);
$Childages = str_replace(',',', ', $row_agency[8]);	
 
$state         = (trim($row_agency[3]))?$row_agency[3]:"N/A";

$children      = (trim($row_agency[5]))?$row_agency[5]:"N/A";

$adoptiontype  = (trim($row_agency[9]))?$row_agency[9]:"N/A";

$arrValues = array();

$childethnicity_ary = explode(",",$childethnicity);
$childethnicity_sub = array_slice($childethnicity_ary,0,6);
$childethnicity = sizeof($childethnicity_ary) >= 7  ? implode(", ",$childethnicity_sub). '<a class = \'tooltip\' title-text= "'.str_replace(',',', ',$row_agency[7]) .'"  href="javascript:void(0)" ><span>....More</span></a>' :implode(", ",$childethnicity_ary);
$childethnicity = htmlentities($childethnicity);
$childethnicity = ($childethnicity)?$childethnicity:"N/A";

$faiths_ary = explode(",",$faiths);
$faiths_sub = array_slice($faiths_ary,0,6);
$faiths =  sizeof($faiths_ary) >= 7 ? implode(", ",$faiths_sub) . '<a class = \'tooltip\' title-text= "'.str_replace(',',', ',$row_agency[6]) .'"  href="javascript:void(0)" ><span>....More</span></a>' :implode(", ",$faiths_ary);
$faiths = htmlentities($faiths);
$faiths = ($faiths)?$faiths:"N/A";

$Childages_ary = explode(",",$Childages);
$Childages_sub = array_slice($Childages_ary,0,6);
$Childages = sizeof($Childages_ary) >= 7 ? implode(", ",$Childages_sub) . '<a class = \'tooltip\' title-text= "'.str_replace(',',', ',$row_agency[8]) .'"  href="javascript:void(0)" ><span>....More</span></a>' :implode(", ",$Childages_ary);
$Childages = htmlentities($Childages);
$Childages = ($Childages)?$Childages:"N/A";

$photo = htmlentities($photo);

/*array_push($arrRows_agency_list1, array(
'profile_id' => $row_agency[0],
'profile_firstname' => $profilename ,  
'profile_age' => $profileages,
 
'profile_state' => $row_agency[3], 
'profile_waiting' => $waiting,     
'profile_noofchilds' => $row_agency[5], 
 * 
'profile_faith' => $row_agency[6], 
'profile_childage' => $row_agency[8],
//'profile_childethnicity' =>strlen($test) >= 170 ? substr($test, 0, 160) . ' <a href="extra_profile_view_17.php?id='.$row_agency[0].'">....More</a>' :$test,
'profile_childethnicity' =>strlen($test) >= 170 ? substr($test, 0, 160) . '<a class = \'tooltip\' title= "'.str_replace(',',', ',$row_agency[7]) .'"  href="javascript:void(0)" ><span title="">....More</span></a>' :$test,

'profile_adoptiontype' => $row_agency[9],  
'profile_image' => $photo,  
'BM_id' => $logid,
'profile_nickname' => $row_agency[11],     
));
*/
if(strlen($profilename) >= 25 ){
$profilename_string = '<div class = \'tooltip\' title-text= "'.$profilename .'" ><span>'.substr($profilename, 0, 22).'...</span></div>';
    
}else{
 
$profilename_string = $profilename;
}
++$count;
$xml .= '<item id="'.$count.'">
<profile_id>'.$row_agency[0].'</profile_id>
<profile_firstname>'.htmlentities($profilename_string).'</profile_firstname>
<profile_age>'.htmlentities($profileages).'</profile_age>

<profile_state>'.htmlentities($state).'</profile_state>
<profile_waiting>'.$waiting.'</profile_waiting>
<profile_noofchilds>'.$children.'</profile_noofchilds>

<profile_faith>'.$faiths.'</profile_faith>
<profile_childage>'.$Childages.'</profile_childage>
<profile_childethnicity>'.$childethnicity.'</profile_childethnicity>

<profile_adoptiontype>'.htmlentities($adoptiontype).'</profile_adoptiontype>
<profile_image>'.$photo.'</profile_image>
<BM_id>'.$logid.'</BM_id>

<profile_nickname>'.htmlentities($row_agency[11]).'</profile_nickname>
</item>';



}
}$xml .= '</data>';

if ($cmdtuples > 0)
{   
    echo $xml;
//echo json_encode($arrRows_agency_list1);
}
else
{
    echo $AgencyLike_List;
/*echo json_encode(array(
));*/
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
    imageResize_scroll_new($filename_new, $sNewFilePath, $iWidth = 301, $iHeight = 231, true);

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
        imageResize_scroll_new($filename_new1, $sNewFilePath1, $iWidth = 301, $iHeight = 231, true);

       }


} 


    }   
  
}
?>
