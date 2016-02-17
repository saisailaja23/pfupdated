<?php
 
/*********************************************************************************
 * Name:    Prashanth A
 * Date:    02/11/2013
 * Purpose: Populating the values in family profile builder
 *********************************************************************************/

require_once('../../inc/header.inc.php');
require_once('../../inc/profiles.inc.php');
require_once('../../inc/design.inc.php');
require_once('../../templates/base/scripts/BxBaseMenu.php');

$logid = ($_GET['id']!='undefined')?$_GET['id']:getLoggedId();
$logged_id = getLoggedId();

$member = getProfileInfo($logid);
     //echo 'asdfsadfasdf';print_r($_REQUEST);exit;
$tablename = ($_REQUEST['approve']!='undefined' && $_REQUEST['approve']==1)?'Profiles_draft':'Profiles';
$columns = 'ID,Email,FirstName,State,Password,AdoptionAgency,Facebook,Twitter,Google,Blogger,Pinerest,Age,state,waiting,noofchildren,faith,childethnicity,childage,Adoptiontype,phonenumber,street_address,DescriptionMe,yardsize,noofbathrooms,noofbedrooms,housestyle,WEB_URL,Couple,About_our_home,NickName,address1,address2,city,zip,Status';
$stringSQL = "SELECT  " . $columns . " FROM " . $tablename . " where ID = " . $logid . "";
$query = db_res($stringSQL);
$cmdtuples = mysql_num_rows($query);
$arrColumns = explode(",", $columns);
$arrRows = array();

while (($row = mysql_fetch_array($query, MYSQL_BOTH)))
{
$arrValues = array();
foreach($arrColumns as $column_name)
{
//array_push($arrValues, $row[$column_name]);
  array_push($arrValues, str_replace("\n","<br/>",trim($row[$column_name])));  
}

array_push($arrRows, array(
'id' => $row[0],
'data' => $arrValues,
));
}

$tablename = ($_REQUEST['approve']!='undefined' && $_REQUEST['approve']==1)?'Profiles_draft':'Profiles';
$columns = 'ID,FirstName,Age,DescriptionMe';
$stringSQL_t = "SELECT  " . $columns . " FROM " . $tablename . " where Couple = " . $logid. "";
$query = db_res($stringSQL_t);
$cmdtuples = mysql_num_rows($query);
$arrColumns = explode(",", $columns);
$arrRows_couple = array();

while (($row = mysql_fetch_array($query, MYSQL_BOTH)))
{
$arrValues = array();
foreach($arrColumns as $column_name)
{
array_push($arrValues, $row[$column_name]);
}

array_push($arrRows_couple, array(
'id' => $row[0],
'data' => $arrValues,
));
}


$tablename = 'RayVideoFiles';
$columns = 'ID,Time';
$stringSQL_video = "SELECT  " . $columns . " FROM " . $tablename . " where Owner = " . $logid. " AND Status='approved' AND Time > 0 AND Categories != 'Home' AND Source = '' ORDER BY RAND() Limit 1 ";
$query = db_res($stringSQL_video);
$cmdtuples = mysql_num_rows($query);
$arrColumns = explode(",", $columns);
$arrRows_video= array();

while (($row = mysql_fetch_array($query, MYSQL_BOTH)))
{
$arrValues = array();
foreach($arrColumns as $column_name)
{
array_push($arrValues, $row[$column_name]);
}

array_push($arrRows_video, array(
'id' => $row[0],
'data' => $arrValues,
));
}

$tablename = 'RayVideoFiles';
$columns = 'ID,Time';
$stringSQL_home_video = "SELECT  " . $columns . " FROM " . $tablename . " where Owner = " . $logid. " AND Status='approved' AND Time > 0 AND Categories = 'Home' AND Source = '' ORDER BY RAND() Limit 1 ";
$query = db_res($stringSQL_home_video);
$cmdtuples = mysql_num_rows($query);
$arrColumns = explode(",", $columns);
$arrRows_homevideo= array();

while (($row = mysql_fetch_array($query, MYSQL_BOTH)))
{
$arrValues = array();
foreach($arrColumns as $column_name)
{
array_push($arrValues, $row[$column_name]);
}

array_push($arrRows_homevideo, array(
'id' => $row[0],
'data' => $arrValues,
));
}

$tablename = 'bx_blogs_posts';
$columns = 'PostDate,PostText';
if(!isLogged()) {
 $stringSQL_Posts = "SELECT REPLACE(REPLACE(PostText,'<p>',''),'</p>','') as `PostText`, FROM_UNIXTIME(PostDate,'%M %d, %Y') as PostDate,FROM_UNIXTIME(PostDate,'%h %i %s') as Posttime FROM " . $tablename . " where OwnerID = " . $logid . " and PostStatus = 'approval'  and allowView = '3' ORDER BY FROM_UNIXTIME(PostDate,'%Y %m, %d') DESC ,
 FROM_UNIXTIME(PostDate,'%h %i %s') DESC
  LIMIT 4";
}
else {
  $stringSQL_Posts = "SELECT REPLACE(REPLACE(PostText,'<p>',''),'</p>','') as `PostText`, FROM_UNIXTIME(PostDate,'%M %d, %Y') as PostDate,FROM_UNIXTIME(PostDate,'%h %i %s') as Posttime FROM " . $tablename . " where OwnerID = " . $logid . " and PostStatus = 'approval'  and allowView IN ('4','3') ORDER BY FROM_UNIXTIME(PostDate,'%Y %m, %d') DESC ,
 FROM_UNIXTIME(PostDate,'%h %i %s') DESC
  LIMIT 4";  
    
}

$query = db_res($stringSQL_Posts);
$cmdtuples = mysql_num_rows($query);
$arrColumns = explode(",", $columns);
$arrRows_posts = array();

while (($row = mysql_fetch_array($query, MYSQL_BOTH)))
{
$arrValues = array();
foreach($arrColumns as $column_name)
{    
array_push($arrValues, $row[$column_name]);
}

array_push($arrRows_posts, array(
'id' => $row[0],
'data' => $arrValues,
));
}

$tablename = ($_REQUEST['approve']!='undefined' && $_REQUEST['approve']==1)?'Profiles_draft':'Profiles';
$LastActivetime = db_arr("SELECT `DateLastLogin` FROM `$tablename` WHERE `ID` = '{$logid}' LIMIT 1");
$Activetime = $LastActivetime[DateLastLogin];

  $date1 = time();
  $date2 =  strtotime($Activetime);
  $getlogid = getLoggedId();              
       
  $dateDiff = $date1 - $date2;
  $fullDays = floor($dateDiff/(60*60*24));
  $fullHours = floor(($dateDiff-($fullDays*60*60*24))/(60*60));
  $fullMinutes = floor(($dateDiff-($fullDays*60*60*24)-($fullHours*60*60))/60);

  if($Activetime == '0000-00-00 00:00:00') {
  $total =  "";     
  }
  else {     
  $total =  "LAST ACTIVE ".$fullDays. " DAYS"; 
 }
 
 $inbox_mess= db_arr("SELECT count(*) as inboxcount  FROM `sys_messages` WHERE `Recipient` = '$logid' and Type = 'letter' and Trash = ''");
 $inboxmessages = $inbox_mess[inboxcount];
 
 
 $sent_mess= db_arr("SELECT count(*) as sentcount  FROM `sys_messages` WHERE `Sender` = $logid");
 $sentmessages = $sent_mess[sentcount];
 
 $unread_mess= db_arr("SELECT count(*) as unreadcount  FROM `sys_messages` WHERE `Recipient` = $logid AND `New` = '1' AND `Type` = 'letter' and Trash =''");
 $unreadmessages = $unread_mess[unreadcount];
 
 $current_date = date("F j, Y");
 
 //echo "SELECT content FROM `aqb_pc_members_blocks` WHERE `owner_id` = '$logid' and title = 'E-book Profile'";
 $E_book = db_arr("SELECT content FROM `aqb_pc_members_blocks` WHERE `owner_id` = '$logid' and title = 'E-book Profile'");
 $E_book_link = $E_book[content];
 
 //$html = '<a href="http://www.mydomain.com/page.html" class="myclass" rel="myrel">URL</a>';
 //$url = preg_match('/href=["\']?([^"\'>]+)["\']?/', $E_book_link, $match);

  $start = strpos($E_book_link,"<a href=")+8;
  $end = strpos($E_book_link,"target= _blank")-$start;
  $flipbook =   substr($E_book_link,$start,$end);
  $flipbook_mob_link = false; 
  if($flipbook != false)
  {
    $flipbook_mob         = split('/',$flipbook);
    $flipbook_mob_link    = $flipbook_mob[0]."/".$flipbook_mob[1]."/mobile/index.html";

    $flipbook_mob_filename = $dir['root'].$flipbook_mob_link;
    if(!(file_exists($flipbook_mob_filename)))
    {
       $flipbook_mob_link = false; 
    }
  }


 $profile_tablename = ($_REQUEST['approve']!='undefined' && $_REQUEST['approve']==1)?'Profiles_draft':'Profiles';
 $tablename = $profile_tablename.',bx_groups_main';
 $columns = "AgencyTitle,City,State,zip,Country,CONTACT_NUMBER,Email,WEB_URL,Avatar";
 $agencyaddressSQL = "SELECT bx_groups_main.title AS AgencyTitle, bx_groups_main.author_id AS bxid,  $profile_tablename.* FROM $profile_tablename JOIN bx_groups_main WHERE  $profile_tablename.AdoptionAgency=bx_groups_main.id AND $profile_tablename.ID  IN (SELECT bx_groups_main.author_id FROM $profile_tablename JOIN bx_groups_main WHERE $profile_tablename.ID = $logid AND $profile_tablename.AdoptionAgency=bx_groups_main.id)";
 $query = db_res($agencyaddressSQL);
 $cmdtuples = mysql_num_rows($query);
 $arrColumns = explode(",", $columns);
 $arrRows_agencyaddress = array();
 
 while (($row = mysql_fetch_array($query, MYSQL_BOTH)))
    {
    $arrValues = array();
    foreach($arrColumns as $column_name)
    {    
    array_push($arrValues, $row[$column_name]);
    }
    if(is_numeric($arrValues[5])){
    $num = $arrValues[5];
    $num = preg_replace('/[^0-9]/', '', $num); 
$len = strlen($num);
if($len == 7)
$num = preg_replace('/([0-9]{3})([0-9]{4})/', '$1-$2', $num);
elseif($len == 10)
$num = preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})/', '$1- $2-$3', $num);
$arrValues[5] = $num;
}
    array_push($arrValues, $stateAbb[$row['State']]);
    array_push($arrRows_agencyaddress, array(
    'id' => $row[0],
    'data' => $arrValues
    ));
    }
    
    $sAgencyInfo = db_arr("SELECT bx_groups_main.title AS AgencyTitle, bx_groups_main.author_id AS bxid,  $profile_tablename.* FROM $profile_tablename JOIN bx_groups_main WHERE  $profile_tablename.AdoptionAgency=bx_groups_main.id AND $profile_tablename.ID  IN (SELECT bx_groups_main.author_id FROM $profile_tablename JOIN bx_groups_main WHERE $profile_tablename.ID = $logid AND $profile_tablename.AdoptionAgency=bx_groups_main.id)");
    $author_thumb =$sAgencyInfo['Avatar'];
    if($author_thumb)
        $avatar_img = '<img class="headIcon"src="'.$site['url'].'modules/boonex/avatar/data/images/'.$author_thumb.".jpg".'">';
    else
        $avatar_img = '<img class="headIcon" src="templates/tmpl_par/images/agency_thumb_small.png" alt="" title="" />';
    $agency_logo = array();
    array_push($agency_logo , array(
   'id' => $row[0],
    'data' =>$avatar_img
   ));
    
// $test = new BxBaseMenu();
$test = generateprofilePDF($logid);

 
 $getDefaultDetails       = getuserDefaultPDF($logid);      
  
 $getDefaulTempID         = $getDefaultDetails['template_user_id'];
 $getorgTempID            = $getDefaultDetails['template_id'];
      
 
$photocount = db_arr("SELECT count(*) as photocounts FROM `bx_photos_main` WHERE owner = '{$logid}'");
$countphoto = $photocount[photocounts];
  
$videocount = db_arr("SELECT count(*) as videocounts FROM `RayVideoFiles` WHERE owner = '{$logid}'");
$countvideo = $videocount[videocounts];      

if ($cmdtuples > 0)
{
echo json_encode(array(
'status' => 'success',

'Profiles' => array(
'rows' => $arrRows
),
 
'Profiles_couple' => array(
'rows' => $arrRows_couple
),      
'Profiles_video' => array(
'rows' => $arrRows_video
),  
 'Profiles_homevideo' => array(
'rows' => $arrRows_homevideo
),   

'lastactivetime' => array(
'rows' => $total
),  
'inboxmess' => array(
'rows' => $inboxmessages
),   
'sentmess' => array(
'rows' => $sentmessages
), 
'unreadmess' => array(
'rows' => $unreadmessages
), 

'blog_posts' => array(
'rows' => $arrRows_posts
), 

'current_date' => array(
'rows' => $current_date

),
    
'agency_address' => array(
'rows' => $arrRows_agencyaddress
),  
    
'agency_logo' => array(
'rows' => $agency_logo
), 
 'ebook_link' => array(
'rows' => $flipbook
),
 'ebook_mob_link' => array(
'rows' => $flipbook_mob_link
),    
'logged' => array(
'rows' => $logged_id
), 
'printprofile' => array(
'rows' => $test
),  
'deafulttempid' => array(
'rows' => $getorgTempID
), 
 'photocount' => array(
'rows' => $countphoto
),    
'videocount' => array(
'rows' => $countvideo
),      
    
'sql_statement' => $stringSQL
)); }
  else
{
echo json_encode(array(
'status' => 'err', 
'response' => 'Could not read the data' 
));
}
function generateprofilePDF($logged)
{ 

  $getDefaultDetails       = getuserDefaultPDF($logged);
       
  
      $getDefaulTempID         = $getDefaultDetails['template_user_id'];
      $getorgTempID            = $getDefaultDetails['template_id'];

        if($getorgTempID == 0)
        {
       
            
             if($getDefaulTempID)
             $view_url   = $GLOBALS['site']['url']."PDFTemplates/user/".$logged."_".$getDefaulTempID."_".$getDefaultDetails['pdfdate'].".pdf";
              
             else
             $view_url  = "";
           		   
	

        }
       else
        {
      //  $view_url  = $getDefaulTempID;

          $view_url  = $getDefaulTempID;

  
        }
		return $view_url;
	}



	function getuserDefaultPDF($userID)
	{
		 $pdfUserDefault          = "";
		 $sql_pdfuserDet         = "SELECT
								ptu.template_user_id,
								ptu.user_id,
								ptu.template_id,
                                DATE_FORMAT(ptu.lastupdateddate, '%Y-%m-%d') AS pdfdate
								FROM pdf_template_user ptu
								WHERE ptu.user_id = $userID AND ptu.isDeleted = 'N' AND ptu.isDefault ='Y'";
		 //echo $sql_pdfuserDet;
		 $pdfUserDet             = mysql_query($sql_pdfuserDet);
		 if(mysql_numrows($pdfUserDet) > 0)
		 {
			  while($row_pdfsdet = mysql_fetch_assoc($pdfUserDet))
			  {
					$pdfUserDefault = $row_pdfsdet;
			  }
		 }
		 return $pdfUserDefault;
	}

     function getuserProfileType($userID)
	{
		 $userProfileType          = "";
		 $sql_userProfileType         = "SELECT	ProfileType FROM Profiles WHERE ID = $userID";
		// echo $sql_userProfileType;exit();

		 $userProfileTypedet            = mysql_query($sql_userProfileType);
		 if(mysql_numrows($userProfileTypedet) > 0)
		 {
			  while($row_Profiledet = mysql_fetch_assoc($userProfileTypedet))
			  {
					$userProfileType = $row_Profiledet;


			  }
		 }
		 return $userProfileType;
	}







?>
