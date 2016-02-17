<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
//header("content-type: application/json");
 header('Content-Type: application/json; charset=utf-8');

include('/var/www/html/pf/inc/header.inc.php');
global $site;

$page=$_REQUEST['page'];
switch($page){
case 'index':badgedata::indexPage();
break;
case 'About':badgedata::aboutPage();	
break;
case 'OurHome':badgedata::ourHomePage();	
break;
case 'Contact':badgedata::contactPage();	
break;
case 'Letter':badgedata::letterPage();	
break;
case 'Video':badgedata::videoPage();	
break;
case 'ContactSubmit':badgedata::sendMail();
break;
case 'GetPhotos':badgedata::GetPhotos();
break;
case 'GetVideos':badgedata::GetVideos();
break;
case 'GetVideosH':badgedata::GetVideosH();
break;
case 'PreLike':badgedata::PreLike();
break;
case 'userLike':badgedata::userLike();
break;
case 'MoreVideos':badgedata::MoreVideos();
break;
case 'MorePhotos':badgedata::MorePhotos();
break;
case 'topMenu':badgedata::topMenu();
break;
case 'Journalcontent':badgedata::journalcontent();
break;
default: badgedata::indexPage();
}


class badgedata{


function getSiteUrl()
{
 return $gsiteName='https://www.parentfinder.com';
}
function getnickname2($id){

	 $nick = mysql_query("SELECT NickName
                                FROM `Profiles`
                                WHERE `ID` = " . $id);
    $nickval = mysql_fetch_array($nick); 
    return $nickval['NickName'];
}
function getLoggedId()
{
    return isset($_COOKIE['memberID']) && (!empty($GLOBALS['logged']['member']) || !empty($GLOBALS['logged']['admin'])) ? (int)$_COOKIE['memberID'] : 0;
}

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

function age($birthday){
 list($year,$month,$day) = explode("-",$birthday);
 $year_diff  = date("Y") - $year;
 $month_diff = date("m") - $month;
 $day_diff   = date("d") - $day;
 if ($day_diff < 0 && $month_diff==0){$year_diff--;}
 if ( $month_diff < 0){$year_diff--;}
 return $year_diff;
}

function sendMail(){

$sSenderName = stripslashes($_POST['name']);
$sSenderEmail = stripslashes($_POST['email']);
$sLetterSubject = stripslashes($_POST['subject']);
$sLetterBody = stripslashes($_POST['body']);

if ($_POST['id']) {
   
	$ress = mysql_query("SELECT show_contact FROM `Profiles` WHERE `ID` = ".$_POST['id']);
	$res = mysql_fetch_array($ress); 
	if($res['show_contact'] == 1){
		$sub_qry = $_POST['id'];
	}else{
		$sub_qry = "SELECT bx_groups_main.author_id FROM Profiles 
					JOIN bx_groups_main WHERE Profiles.ID = ".$_POST['id']." 
					AND Profiles.AdoptionAgency=bx_groups_main.id";
	}
	
	$aMemberInfos = mysql_query("SELECT Email FROM Profiles 
							WHERE Profiles.ID  IN ($sub_qry)");
	$aMemberInfo = mysql_fetch_array($aMemberInfos); 						
    $toEmail = $aMemberInfo['Email'];
} elseif ($_GET['Agency']) {
    $aMemberInfos = mysql_query("SELECT Email
                                FROM `Profiles`
                                JOIN bx_groups_main
                                WHERE Profiles.`ID` = bx_groups_main.author_id
                                AND bx_groups_main.uri = '" . $_GET['Agency'] . "'");
    $aMemberInfo = mysql_fetch_array($aMemberInfos); 						
    $toEmail = $aMemberInfo['Email'];
} else {
    $toEmail = $site['Email'];
}

if ($_POST['id'] != '') {
    $adoptiveparents = mysql_query("SELECT NickName
                                FROM `Profiles`
                                WHERE `ID` = " . $_POST['id']);
    $adoptiveparent = mysql_fetch_array($adoptiveparents); 
}
$sLetterBody = "<html><body><p style='font-family:georgia;'><b>Message For Adoptive Parent: <a href = '" . $site['url'] . $adoptiveparent['NickName'] . "'>" . $adoptiveparent['NickName'] . "</a></b></p>" . $sLetterBody . " " . '<p>Thanks,</p>' . "<p>" . $sSenderName . "&#44; </p><p>" . '&#40;' . $sSenderEmail . " &#41;</p></html></body>";

if (mail($toEmail, $sLetterSubject, $sLetterBody)) {
    $sActionKey = '1';
    $sLetterSubject = "Thank You";
    $sLetterBody = "<html><body>
                         <p><b>Dear " . $sSenderName . "</b></p>
                        <p>
                        Thank you for your message. We will get back to you within couple of days.
                        For additional questions, please reach out to " . $toEmail . ".</p>
                        <p><b>Thank you for using our services!</b></p>
                        <p>--</p>
                        <p style='font: bold 10px Verdana; color:red'>ParentFinder Community mail delivery system!!!
                        <br />Auto-generated e-mail, please, do not reply!!!</p>
                        </html></body>";

    mail($sSenderEmail, $sLetterSubject, $sLetterBody);

} else {
    $sActionKey = '0';
 }
$sAction['message'] = $sActionKey;
echo json_encode($sAction); 
}


function indexPage(){
global $site;
$arrayid=array();
$start=mysql_real_escape_string($_REQUEST['start']);
$end=mysql_real_escape_string($_REQUEST['end']);
$sstat=mysql_real_escape_string($_REQUEST['sstat']);
$seed=mysql_real_escape_string($_REQUEST['seed']);
$uid=mysql_real_escape_string($_REQUEST['uid']);
$num=mysql_real_escape_string($_REQUEST['num']);
//$nuarry=explode(',', $num)
$agencyId =$uid;
$agencyId = badgedata::test_input($agencyId);


//=========================search query============================//
$searchvalues = mysql_real_escape_string($_REQUEST['sortvalue']);
$searchvalues = badgedata::test_input($searchvalues);

$searchtype = mysql_real_escape_string($_REQUEST['type']);
$searchtype = badgedata::test_input($searchtype);

if ($searchtype == 'Familysize' && $searchvalues == '3') {
	$searchvalues == '3+';
}
if ($searchtype == 'Familysize' && $searchvalues == '0') {
	$searchvalues == '3+';
}
if ($searchtype == 'State' && $_REQUEST['sortvalue'] == 'all') {
	$searchtype = '';
	$searchvalues = '';
}
//=========================search query============================//

if ($loadFrom == 'badge' || ($agencyId)) {
	$sql_getAgencyFilter = 'AND `AdoptionAgency` = ' . $agencyId;
} else {
	$sql_getAgencyFilter = '';
}
$orderBy = 'Profiles.DateReg DESC';
$sql_getAgencyFilter .= " AND `Profiles`.`publishStatus` = 1";
$searchFilter = ($_REQUEST['name'] && $_REQUEST['name'] != 'undefined') ? mysql_real_escape_string($_REQUEST['name']) : '';

if (trim($searchFilter)) {
	$sql_getAgencyFilter .= ' AND (`Profiles`.`ID` in (select p1.ID from Profiles as p1 left join Profiles as p2  on p1.ID = p2.Couple where (p1.FirstName LIKE "%' . $searchFilter . '%" or  p2.FirstName LIKE "%' . $searchFilter . '%") or (p1.FirstName LIKE "%' . $searchFilter . '%" and p1.Status = \'Active\' and p1.Couple = 0) and p1.Status = \'Active\')) ';
}


switch ($searchtype) {

	
	case 'Region':
		$AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE `Profiles`.`Status` = 'Active' and Profiles.Region ='$searchvalues' and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'   AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter GROUP BY ID limit $start,$end";
		break;
	case 'Religion':
		/*$AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and (Profiles.Religion LIKE '%$searchvalues%' OR Profiles.faith LIKE '%$searchvalues%') and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'   AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter limit $start,$end";*/
		 $AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and  Profiles.Religion LIKE '%$searchvalues%'  and `Profiles`.`ProfileType` = 2  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID` or `Profiles`.`Couple` < `Profiles`.`ID`) $sql_getAgencyFilter GROUP BY ID limit $start,$end";
		break;
	case 'Familysize':
		$searchOption = ((int) $searchvalues < 6) ? "Profiles.noofchildren=$searchvalues" : "Profiles.noofchildren >= $searchvalues";
		$AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and ($searchOption) and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter GROUP BY ID limit $start,$end";
		break;
	case 'State':
		$AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and FIND_IN_SET( '$searchvalues' ,`state` ) and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter GROUP BY ID limit $start , $end";
		break;

    case 'Sortby':
		switch ($searchvalues) {
		case 'oldFirst':
				$orderBy = 'Profiles.DateReg ASC';
				break;
		case 'random':
				$orderBy = 'RAND('.$seed.')';
				break;
		case 'FirstName':
				$orderBy = 'Profiles.FirstName ASC';
				break;
		default:
				$orderBy = 'Profiles.DateReg DESC';
				break;
		}
		$AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active'  and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter GROUP BY ID ORDER BY $orderBy limit $start , $end";
		break;
	    case 'ethnicity':
		$AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and (FIND_IN_SET( '$searchvalues' ,`ChildEthnicity`) or FIND_IN_SET( 'Any' ,`ChildEthnicity`)) and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter GROUP BY ID limit $start , $end";
		break;

	default:
        $AgencyLike_List = "SELECT `Profiles`. `ID`, `Profiles`. `NickName`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0' AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter GROUP BY ID ORDER BY $orderBy limit $start,$end"; 
    break;
}	
//echo $AgencyLike_List;
$agency_query = mysql_query($AgencyLike_List);
$i = 0;	
while (($row = mysql_fetch_array($agency_query, MYSQL_BOTH))) {


	        if( $i % 2 == 1){
	        	$class = 'pf_view_cl02 pf_view_cl021';
	        }     
             else{
                $class = 'pf_view_cl02'; 
             }

$agency_id = $row['ID'];



	$Agencydetails = mysql_query("SELECT  ID,FirstName,DateOfBirth,State,waiting,noofchildren,faith,ChildEthnicity,ChildAge,Adoptiontype,Avatar,NickName,Occupation,Religion,Education,Ethnicity,Couple from Profiles where ID = " . $agency_id . "");
	$gridData = array(); 
	while (($row_agency = mysql_fetch_array($Agencydetails, MYSQL_BOTH))) {

		$couplenamequery = mysql_query("SELECT `FirstName`,DateOfBirth FROM `Profiles` WHERE `Couple` = '$agency_id' LIMIT 1");
		$row_couplename = mysql_fetch_array($couplenamequery);
		$Couple_name=$row_couplename['FirstName'];
		$Couple_age = $row_couplename['DateOfBirth'];

			if ($Couple_name != '') {
				    $Fname = $row_agency[1];
		            $profilename = $Fname . ' and ' . $Couple_name;
			} else {
				$Fnames = $row_agency[1];
				$profilename = $Fnames;
			}

			if ($Couple_age != '') {
			if ($row_agency[2] != '0000-00-00') {
				$first = badgedata::age($row_agency[2]);
			}
			if ($Couple_age != '0000-00-00') {
				$second = badgedata::age($Couple_age);
			}
			$profileages = $first . '/' . $second;
			if ($row_agency[2] == '0000-00-00' || $Couple_age == '0000-00-00') {
				$profileages = "NA";
			}
			} else {

				if ($row_agency[2] != '0000-00-00') {
					$firstsingle = badgedata::age($row_agency[2]);
				}
				$profileages = $firstsingle;
			}

			$Childages = str_replace(',', ', ', $row_agency[8]);
			$Childages_ary = explode(",", $Childages);
			$Childages_sub = array_slice($Childages_ary, 0, 4);
			$Childagessmall = implode(", ", $Childages_sub);
			$Childageslarge = ($Childages) ? $Childages : "";


			$ProfileQ1 = mysql_query("SELECT template_file_path  FROM pdf_template_user WHERE user_id =$row_agency[0] AND isDeleted = 'N' AND isDefault ='Y' LIMIT 1");
			$ProfileQ=mysql_fetch_array($ProfileQ1,MYSQL_BOTH);          
            $Profile_pth = (trim(str_replace(badgedata::getSiteUrl(), '/', $ProfileQ[template_file_path])) != '') ? trim(str_replace(badgedata::getSiteUrl(), '/', $ProfileQ[template_file_path])) : 'javascript:void(0)';
             $pdf_new_url =badgedata::getSiteUrl().'/'."ProfilebuilderComponent/pdf.php?id=".$row_agency[0];
            $parsed = parse_url($Profile_pth);
				$path = $parsed['path'];
				$path_parts = explode('/', $path);
				if($path_parts[7]){
				$pdf_output = badgedata::getSiteUrl().'/'.$path_parts[5].'/'.$path_parts[6].'/'.$path_parts[7];
				}
				
            if($Profile_pth == 'javascript:void(0)'){
            }
            else{
            	$profile_path_status=1;            	              
            }
			
			$E_pub1 = mysql_query("SELECT content FROM `aqb_pc_members_blocks` WHERE `owner_id` = '$row_agency[0]' and title = 'E-PUB Profile'");
			$E_pub=mysql_fetch_array($E_pub1,MYSQL_BOTH);
			$E_pub_link = $E_pub[content];
			$epub_name_arr = explode('.', $E_pub_link);
			if (strtolower(end($epub_name_arr)) == 'epub') {
				$epubbook = $E_pub_link;
			} else {
				$epubbook = false;
			}

			$profileages = ($profileages) ? $profileages : "NA";
			$childethnicity = str_replace(',', ', ', $row_agency[7]);
			$faiths = str_replace(',', ', ', $row_agency[6]);
			$state = (trim($row_agency[3])) ? $row_agency[3] : "NA";
			$children = (trim($row_agency[5])) ? $row_agency[5] : "No Children";
			$adoptiontype = (trim($row_agency[9])) ? $row_agency[9] : "NA";
			$profile_pdf = $Profile_pth;
			$epub_link = $epubbook;

		 $id= $row_agency[0];
		 (strlen($profilename) >= 30)?$dot='..':$dot='';
		 if (strlen($profilename) > 25){
         $profilename = substr($profilename, 0, 28) .$dot;
         $name='<span style="font-size: 12px !important;">'.$profilename.'</span>';
         }else{
         $name='<span>'.$profilename.'</span>';	
         }
         	  if(strlen(trim($Childagessmall))>49){
         	$agesty='inline';
         }else{
         	$agesty='none';
         }
         if(strlen(trim($childethnicity))>49){
         	$cefv='inline';
         }else{
         	$cefv='none';
         }
         if($faiths == 'Not Specified'){

         	$faithval = $faiths;
         }
         else if($faiths == ''){
         		$faithval = $faiths;
         }
         else if(strlen($faiths) < 60){
         	$faithval = $faiths;

         }
         else{
         		$faithval = substr($faiths, 0, 60).'<a class="pf_view_cl017" style="display:'.$faiths.'"><span data-toggle="modal" href="#more" data="'.$faiths.'">....More</span></a>';
         }
         $sImage = badgedata::getSiteUrl().'/modules/boonex/avatar/data/favourite/' . $row_agency[10] . '.jpg';
         $json[]=array('Image'=>$sImage,'Title'=>$name,'Id'=>$id, 'ChildAgesL'=>$Childageslarge,'agesty'=>$agesty,'ChildAgesS'=>substr($Childagessmall, 0, 50), 'Age'=>$profileages, 'State'=>$state, 'CE'=>substr($childethnicity, 0, 50), 'CEF'=>$childethnicity, 'CEFV'=>$cefv,'Children'=>$children,'AdptType'=>$adoptiontype,'Faiths'=>substr($faiths, 0, 80),'fullFaiths'=>$faithval,'Class'=>$class,'Pdf_link'=>$pdf_output,'Epub_link'=>$epub_link,'pdf_new_url' => $pdf_new_url, 'profile_path_status' =>$profile_path_status, 'row_agency' => $row_agency[0]);
          $i++;
	}

}

if($json!=null && $searchFilter!=''){
	$rstat='SEARCH RESULTS FOR THE NAME "<span>'.$searchFilter.'</span>"';
}else{
	if($json==null){
	$rstat='NO FAMILIES FOUND';
    }
}
if($searchFilter || $searchtype){
$dispay='inline';	
}else{
$dispay='none';
}
if($sstat=='scroll'){
$dispay='none';
}
$obj=array('objects'=>$json,'display'=>$dispay,'searchMess'=>$rstat,'sql'=>$AgencyLike_List);
echo json_encode($obj);
}


function ourHomePage(){
	badgedata::aboutPage();
}



function aboutPage(){
$uid=mysql_real_escape_string($_GET['uid']);
$logid = ( $uid != 'undefined' ) ? $uid : 0;

$tablename = ( $_REQUEST['approve'] != 'undefined' && $_REQUEST['approve'] == 1 ) ? 'Profiles_draft' : 'Profiles';
$stringSQL = "SELECT  ID,Email,FirstName,State,Password,AdoptionAgency,Facebook,Twitter,Google,Blogger,Pinerest,FLOOR( DATEDIFF( CURDATE( ) ,  `DateOfBirth` ) /365 ) as Age,state,waiting,(SELECT config_description FROM sys_configuration WHERE config_value = noofchildren ) AS noofchildren,faith,childethnicity,childage,Adoptiontype,phonenumber,street_address,DescriptionMe,yardsize,noofbathrooms,noofbedrooms,housestyle,WEB_URL,Couple,About_our_home,NickName,address1,address2,city,zip,Status,childrenType,Region,Country FROM " . $tablename . " where ID = " . $logid . "";
$query = mysql_query($stringSQL);
$cmdtuples = mysql_num_rows($query);
$arrRows = array();


while (( $row = mysql_fetch_array($query, MYSQL_BOTH))) {
			$ProfileQ1 = mysql_query("SELECT template_file_path  FROM pdf_template_user WHERE user_id =$row[0] AND isDeleted = 'N' AND isDefault ='Y' LIMIT 1");
			$ProfileQ=mysql_fetch_array($ProfileQ1,MYSQL_BOTH);          
            $Profile_pth = (trim(str_replace(badgedata::getSiteUrl(), '/', $ProfileQ[template_file_path])) != '') ? trim(str_replace(badgedata::getSiteUrl(), '/', $ProfileQ[template_file_path])) : 'javascript:void(0)';
            //$Profile_pth = (trim(str_replace('/var/www/html/pf/', '/', $ProfileQ[template_file_path])) != '') ? trim(str_replace('/var/www/html/pf/', '/', $ProfileQ[template_file_path])) : 'javascript:void(0)';
            $pdf_new_url =badgedata::getSiteUrl().'/'."ProfilebuilderComponent/pdf.php?id=".$uid;
            $parsed = parse_url($Profile_pth);
				$path = $parsed['path'];
				$path_parts = explode('/', $path);
				if($path_parts[7]){
				$pdf_output = badgedata::getSiteUrl().'/'.$path_parts[5].'/'.$path_parts[6].'/'.$path_parts[7];
				}
				
            if($Profile_pth == 'javascript:void(0)'){
            	//$pdf_atag = '<a class="pf_more_cl09" href="'.$pdf_new_url.'">';
            }
            else{            	
                $profile_path_status=1;            
            }
            

				
			
			$E_pub1 = mysql_query("SELECT content FROM `aqb_pc_members_blocks` WHERE `owner_id` = '$row[0]' and title = 'E-PUB Profile'");
			$E_pub=mysql_fetch_array($E_pub1,MYSQL_BOTH);
			$E_pub_link = $E_pub[content];
			$epub_name_arr = explode('.', $E_pub_link);
			if (strtolower(end($epub_name_arr)) == 'epub') {
				$epubbook = $E_pub_link;
			} else {
				$epubbook = false;
			}




			$E_book1 = mysql_query("SELECT content FROM `aqb_pc_members_blocks` WHERE `owner_id` = '$logid' and title = 'E-book Profile'");
			$E_book=mysql_fetch_array($E_book1);
			$E_book_link = $E_book[content];

			$start = strpos($E_book_link, ".com/") + 5;
			$end = strpos($E_book_link, ".html") - $start + 5;
			$flipbook = substr($E_book_link, $start, $end);

			$E_pup1 = mysql_query("SELECT content FROM `aqb_pc_members_blocks` WHERE `owner_id` = '$logid' and title = 'E-PUB Profile'");
			$E_pup=mysql_fetch_array($E_pup1);
			$E_pup_link = $E_pup[content];

			$epub_name_arr = explode('.', $E_pub_link);
			if (strtolower(end($epub_name_arr)) == 'epub')
			    $epup = $E_pub_link;
			else
			    $epup = false;

			if($row[36] == 'Non US'){
				$state_type = 'COUNTRY: ';
				$state_val  = (trim($row[37]))?$row[37]:'NA';
			}
			else{
				$state_type = 'STATE: ';
				$state_val  =(trim($row[12]))?$row[12]:'NA';
			}
			if($row[26]){
			 $weblink = '<a target ="_blank" href="//'.trim($row[26]).'">'.trim($row[26]).'</a>';
			}
			else{
			 $weblink = '';
			}

    array_push($arrRows, array(
        'id' => $row[0],
        'nameOne' =>$row[2],
        'profileage' =>$row[11],
        'profilestate'=>(trim($row[12]))?$row[12]:'NA',
        'profilewaiting'=> (trim($row[13]))?$row[13]:'NA',
        'profilechildren'=>(trim($row[14]))?$row[14]:'NA',
        'profilefaith'=>(trim($row[15]))?str_replace(',', ', ', $row[15]):'NA',
        'profileethinicity'=>($row[16])?str_replace(',', ', ', $row[16]):'NA',
        'profilechildage'=>(trim($row[17]))?$row[17]:'NA',
        'profileadoptiontype'=>(trim($row[18]))?$row[18]:'NA',
        'aboutHome'=>$row[13],
        'profile1' =>(trim($row[21]))?'<b>ABOUT '. trim(strtoupper($row[2])). '</b> <span>'.trim($row[21]).'</span>':'',
        'website' =>$weblink,
        'Pdf_link'=>$pdf_output,
        'Epub_link'=>$epubbook,
        'ebook_link' =>  $flipbook,
        'epup_link' =>  $epup,
        'state_type' => $state_type,
        'state_val'  => $state_val,
        'pdf_new_url' =>$pdf_new_url,
        'profile_pth' => $Profile_pth,
        'profile_path_status'=>$profile_path_status
    ));

}

$tablename = ($_REQUEST['approve'] != 'undefined' && $_REQUEST['approve'] == 1) ? 'Profiles_draft' : 'Profiles';
$stringSQL_t = "SELECT  ID,FirstName,FLOOR( DATEDIFF( CURDATE( ) ,  `DateOfBirth` ) /365 ) as Age,DescriptionMe FROM " . $tablename . " where Couple = " . $logid . "";
$query = mysql_query($stringSQL_t);
$cmdtuples = mysql_num_rows($query);
$arrRows_couple = array();

while (( $row = mysql_fetch_array($query, MYSQL_BOTH))) {
    array_push($arrRows_couple, array(
        'id' => $row[0],
        'nameOne' =>$row[1],
        'profileage' =>$row[2],
        'profile2' =>(trim($row[3]))?'<b>ABOUT '. trim(strtoupper($row[1])). '</b> <span>'.trim($row[3]).'</span>':'',
        'description' => $row[28]
    ));
}


mysql_query('SET CHARACTER SET utf8');
$tablename = 'bx_blogs_posts';
$columns = 'PostDate,PostText';
if (!badgedata::getLoggedId()) {
    $stringSQL_Posts = "SELECT REPLACE(REPLACE(PostText,'<p>',''),'</p>','') as `PostText`, FROM_UNIXTIME(PostDate,'%M %d, %Y') as PostDate,FROM_UNIXTIME(PostDate,'%h %i %s') as Posttime, PostCaption, PostID FROM " . $tablename . " where OwnerID = " . $logid . " and PostStatus = 'approval'  and allowView = '3' ORDER BY FROM_UNIXTIME(PostDate,'%Y %m, %d') DESC ,
 FROM_UNIXTIME(PostDate,'%h %i %s') DESC";
} else {
    $stringSQL_Posts = "SELECT REPLACE(REPLACE(PostText,'<p>',''),'</p>','') as `PostText`, FROM_UNIXTIME(PostDate,'%M %d, %Y') as PostDate,FROM_UNIXTIME(PostDate,'%h %i %s') as Posttime, PostCaption, PostID FROM " . $tablename . " where OwnerID = " . $logid . " and PostStatus = 'approval'  and allowView IN ('4','3') ORDER BY FROM_UNIXTIME(PostDate,'%Y %m, %d') DESC ,
 FROM_UNIXTIME(PostDate,'%h %i %s') DESC";
}
$query = mysql_query($stringSQL_Posts);
$cmdtuples = mysql_num_rows($query);
$arrRows_posts = array();
$arrRows_posts_more = array();
$i=1;
//ini_set('display_errors', 'On');
//error_reporting(E_ALL);

while (( $row = mysql_fetch_array($query, MYSQL_BOTH))) {
	           $trimText = preg_replace("/<img[^>]+\>/i", " ", $row['PostText']);
	           $trimText = strip_tags(trim(preg_replace('/\s+/', ' ', $trimText)),'<span><p><a>');
	           //$trimText = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $trimText);​
	          // $trimText = ​trim($trimText, chr(0xC2).chr(0xA0));​
	            if (strlen($trimText) > 200) {
                $trimText = strip_tags($trimText);
                $trimText = substr($trimText, 0, 110);
                $trimText .= '...';
               }
               else if($trimText ==' ' || $trimText ==''){
               	$trimText = trim($trimText);
               }else{
               	$trimText = strip_tags($trimText);
               }
               //echo $trimText.'//end;';
               //$trimText = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $trimText);​
                $jLink = preg_replace('/[^a-zA-Z ]/i', '', $row[3]);
                //echo badgedata::getnickname2($logid);
               $postcaplink = badgedata::getSiteUrl().'/'.badgedata::getnickname2($logid).'/journal/' . $jLink .'/' . $row[4].'/badge/';
               $PostCaption =  $row[3];

if($i<=4){
	$trimText = htmlentities($trimText,ENT_QUOTES, 'UTF-8');
	$trimText = str_replace('&nbsp;', '', $trimText);
	if($trimText == ' '){
               	$trimText = str_replace(' ', '', $trimText);
               }
	//$trimText = str_replace(' ', '', $trimText);
    array_push($arrRows_posts, array(
        'id'          =>  $logid,
        'TrimText'    =>  $trimText,
        'PostCaption' =>  $PostCaption,
        'postcaplink' =>  $postcaplink, 
        'PostID'      =>  $row['PostID'],
        'PostDate'    =>  $row['PostDate']
    ));
}

    $i++;
}
if($cmdtuples >4){
	 //$morepost =badgedata::getSiteUrl().'/'.badgedata::getnickname2($logid).'/journals/badge/';
	 $morepost=$logid;
}
else{
	 $morepost ='';
}

$tablename = ( $_REQUEST['approve'] != 'undefined' && $_REQUEST['approve'] == 1 ) ? 'Profiles_draft' : 'Profiles';
$LastActivetime = mysql_fetch_array(mysql_query("SELECT `DateLastLogin` FROM `$tablename` WHERE `ID` = '{$logid}' LIMIT 1"));
$Activetime = $LastActivetime[DateLastLogin];

$date1 = time();
$date2 = strtotime($Activetime);
$getlogid = 0;

$dateDiff = $date1 - $date2;
$fullDays = floor($dateDiff / ( 60 * 60 * 24 ));
$fullHours = floor(( $dateDiff - ( $fullDays * 60 * 60 * 24 ) ) / ( 60 * 60 ));
$fullMinutes = floor(( $dateDiff - ( $fullDays * 60 * 60 * 24 ) - ( $fullHours * 60 * 60 ) ) / 60);

if ($Activetime == '0000-00-00 00:00:00') {
    $total = "";
} else {
    $total = "LAST ACTIVE " . $fullDays . " DAY(S) ago";
}

if ($cmdtuples >= 0) {

	if ($arrRows[0]['profileage']) {      
      $profile_age = $arrRows[0]['profileage'];
    }
    else {
      $profile_age = 'NA';
    }
    if ($arrRows_couple[0])
      if ($arrRows_couple[0]['profileage'])
      {
        $profile_age_couple = $arrRows_couple[0]['profileage'];
      }
      else
       $profile_age_couple = 'NA';

    if ($arrRows_couple[0])
      $pf_age = $profile_age . ' / ' . $profile_age_couple;
    else
      $pf_age = $profile_age;




$profile_tablename = ( $_REQUEST['approve'] != 'undefined' && $_REQUEST['approve'] == 1 ) ? 'Profiles_draft' : 'Profiles';
$agencyaddressSQL = "SELECT bx_groups_main.title AS AgencyTitle, bx_groups_main.author_id AS bxid,  $profile_tablename.* FROM $profile_tablename JOIN bx_groups_main WHERE  $profile_tablename.AdoptionAgency=bx_groups_main.id AND $profile_tablename.ID  IN (SELECT bx_groups_main.author_id FROM $profile_tablename JOIN bx_groups_main WHERE $profile_tablename.ID = $logid AND $profile_tablename.AdoptionAgency=bx_groups_main.id)";
$query = mysql_query($agencyaddressSQL);
$cmdtuples = mysql_num_rows($query);
$arrRows_agencyaddress = array();

while (( $row = mysql_fetch_array($query, MYSQL_BOTH))) {
    array_push($arrRows_agencyaddress, array(
        'id' => trim($row['AgencyTitle']),
        'city' => (trim($row['City']))?trim($row['City']).', ':trim($row['City']),
        'state' => (trim($row['State']))?trim($row['State']).', ':trim($row['State']),
        'zip' => (trim($row['zip']))?trim($row['zip']).', ':trim($row['zip']),
        'contact' => (trim($row['CONTACT_NUMBER']))?trim($row['CONTACT_NUMBER']).'</br>':'',
        'a1'     =>trim($row['Email']),
        'a2'     =>trim($row['WEB_URL'])
    ));
}

$sAgencyInfor = mysql_query("SELECT bx_groups_main.title AS AgencyTitle, bx_groups_main.author_id AS bxid,  $profile_tablename.* FROM $profile_tablename JOIN bx_groups_main WHERE  $profile_tablename.AdoptionAgency=bx_groups_main.id AND $profile_tablename.ID  IN (SELECT bx_groups_main.author_id FROM $profile_tablename JOIN bx_groups_main WHERE $profile_tablename.ID = $logid AND $profile_tablename.AdoptionAgency=bx_groups_main.id)");
$sAgencyInfo=mysql_fetch_array($sAgencyInfor);

$author_thumb = $sAgencyInfo['Avatar'];
if ($author_thumb)
    $avatar_img = '<img class="headIcon" src="badgedata::getSiteUrl()/modules/boonex/avatar/data/images/' . $author_thumb . ".jpg" . '">';
else
   $avatar_img = '<img class="headIcon" src="badgedata::getSiteUrl()/templates/tmpl_par/images/agency_thumb_small.png" alt="" title="" />'; 

$agency_opt_details=$arrRows_agencyaddress[0]['id'].'<br/>'.$arrRows_agencyaddress[0]['city'].$arrRows_agencyaddress[0]['state'].$arrRows_agencyaddress[0]['zip'].'<br/>';
$agency_opt_details.=$arrRows_agencyaddress[0]['contact'].'<a href="mailto:'. $arrRows_agencyaddress[0]['a1']. '">'. $arrRows_agencyaddress[0]['a1'].'</a><br/><a  target ="_blank" href="//'. $arrRows_agencyaddress[0]['a2']. '">' . $arrRows_agencyaddress[0]['a2'] . '</a>';
   // $weblink = '<a href="'.$arrRows[0]['website'].'">'.$arrRows[0]['website'].'</a>';
	/*if($row[26]){
    $weblink = '<a target ="_blank" href="//'.trim($row[26]).'">'.trim($row[26]).'</a>';
}
else{
	$weblink = '';
}*/
    $output = array(
        'status' => 'success',
        'uid'    =>  $uid,
        'tabtitle'=> ($arrRows_couple[0]['nameOne'])?$arrRows[0]['nameOne'].' & '.$arrRows_couple[0]['nameOne']:$arrRows[0]['nameOne'],
        'profileage'=>$pf_age,
        'profile1'  => $arrRows[0]['profile1'],
        'profile2'  => $arrRows_couple[0]['profile2'],
        'website'   => $weblink,
        'agency_address' => ($arrRows_agencyaddress[0]['id'])?$agency_opt_details:'',
        'agency_logo'  => $avatar_img,
        'Profiles' => $arrRows,
        'blog_posts' => $arrRows_posts,
        'blog_posts_more' =>$morepost,
        'Profiles_couple' => $arrRows_couple,
        'lastactivetime' =>  $total
    );

   //$output = badgedata::raw_json_encode($output);
   // print_r($output);exit;
        echo json_encode($output);
} else {
    echo json_encode(array(
        'status' => 'err',
        'response' => 'Could not read the data'
    ));
 }
}

function raw_json_encode($input) {

   return preg_replace("/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode($input));

}

function contactPage(){

$id=mysql_real_escape_string($_REQUEST['uid']);

$res = mysql_query("SELECT show_contact,phonenumber FROM `Profiles` WHERE `ID` = ".$id);
$row_c = mysql_fetch_array($res); 
$sc=$row_c['show_contact']; 

if($sc == 1){
	$nocontact = badgedata::format_phone2($row_c['phonenumber']);
}else{
	$sub_qry2 = mysql_query("SELECT CONTACT_NUMBER
FROM `Profiles` as pr
LEFT JOIN `bx_groups_main` AS g ON pr.AdoptionAgency = g.id
WHERE pr.`ID`IN (SELECT bx_groups_main.author_id FROM Profiles 
JOIN bx_groups_main WHERE Profiles.ID = $id AND Profiles.AdoptionAgency=bx_groups_main.id)");

$row_c12 = mysql_fetch_array($sub_qry2);
$nocontact = badgedata::format_phone2($row_c12['CONTACT_NUMBER']);	
}
$sub_qry = "SELECT bx_groups_main.author_id FROM Profiles 
				JOIN bx_groups_main WHERE Profiles.ID = ".$id." 
				AND Profiles.AdoptionAgency=bx_groups_main.id";
$contact = mysql_query("SELECT g.id, 
					IF($sc = 1, phonenumber, CONTACT_NUMBER) AS phonenumber,
					g.title,p.Email 
					FROM `Profiles` as p
					LEFT JOIN `bx_groups_main` AS g ON p.AdoptionAgency = g.id
					WHERE p.`ID`IN ($sub_qry)");

$row_c1 = mysql_fetch_array($contact);

if($row_c1['title']!='' && $sc!=1){
	$agencyInfo['AgencyTitle'] = $row_c1['title'];
}
$agencyInfo['ID'] = $row_c1['id'];
	$agencyInfo['CONTACT_NUMBER'] = $nocontact;

if($row_c1['Email']!=''){
	$agencyInfo['Email'] = $row_c1['Email'];
}

$agencyInfo['id'] = $id;
echo json_encode($agencyInfo);
}




function getPic($picID){
	$img = json_decode($picID);
	$img = implode(',', $img);	
	$q2 = "SELECT CONCAT(Hash,'.', Ext) as img, Uri FROM `bx_photos_main` WHERE ID IN($img);";
	$r2 = mysql_query($q2);	
	while($rw = mysql_fetch_array($r2)){
		$bImage=$rw["img"];
		$img2[] ='<img src=https://www.parentfinder.com/m/photos/get_image/thumb/'.$bImage.' />&nbsp;';	
	}
	$ret =$img2;
	return $ret;
}




function letterPage(){

$uid=mysql_real_escape_string($_GET['uid']);
$logid = ( $uid != 'undefined' ) ? $uid : 0;

$tablename = ( $_REQUEST['approve'] != 'undefined' && $_REQUEST['approve'] == 1 ) ? 'Profiles_draft' : 'Profiles';
$stringSQL = "SELECT  ID,Email,FirstName,State,Password,AdoptionAgency,Facebook,Twitter,Google,Blogger,Pinerest,FLOOR( DATEDIFF( CURDATE( ) ,  `DateOfBirth` ) /365 ) as Age,state,waiting,(SELECT config_description FROM sys_configuration WHERE config_value = noofchildren ) AS noofchildren,faith,childethnicity,childage,Adoptiontype,phonenumber,street_address,DescriptionMe,yardsize,noofbathrooms,noofbedrooms,housestyle,WEB_URL,Couple,About_our_home,NickName,address1,address2,city,zip,Status,childrenType FROM " . $tablename . " where ID = " . $logid . "";
$query = mysql_query($stringSQL);
$cmdtuples = mysql_num_rows($query);
$arrRows = array();


while (( $row = mysql_fetch_array($query, MYSQL_BOTH))) {

			$ProfileQ1 = mysql_query("SELECT template_file_path  FROM pdf_template_user WHERE user_id =$row[0] AND isDeleted = 'N' AND isDefault ='Y' LIMIT 1");
			$ProfileQ=mysql_fetch_array($ProfileQ1,MYSQL_BOTH);          
            $Profile_pth = (trim(str_replace(badgedata::getSiteUrl(), '/', $ProfileQ[template_file_path])) != '') ? trim(str_replace(badgedata::getSiteUrl(), '/', $ProfileQ[template_file_path])) : 'javascript:void(0)';
 			$pdf_new_url =badgedata::getSiteUrl().'/'."ProfilebuilderComponent/pdf.php?id=".$uid;
            $parsed = parse_url($Profile_pth);
				$path = $parsed['path'];
				$path_parts = explode('/', $path);
				if($path_parts[7]){
				$pdf_output = badgedata::getSiteUrl().'/'.$path_parts[5].'/'.$path_parts[6].'/'.$path_parts[7];
				}
				
            if($Profile_pth == 'javascript:void(0)'){

            	//$pdf_atag = '<a class="pf_more_cl09" href="'.$pdf_new_url.'">';
            }
            else{
            	$profile_path_status=1;             
            }
			
			$E_pub1 = mysql_query("SELECT content FROM `aqb_pc_members_blocks` WHERE `owner_id` = '$row[0]' and title = 'E-PUB Profile'");
			$E_pub=mysql_fetch_array($E_pub1,MYSQL_BOTH);
			$E_pub_link = $E_pub[content];
			$epub_name_arr = explode('.', $E_pub_link);
			if (strtolower(end($epub_name_arr)) == 'epub') {
				$epubbook = $E_pub_link;
			} else {
				$epubbook = false;
			}




			$E_book1 = mysql_query("SELECT content FROM `aqb_pc_members_blocks` WHERE `owner_id` = '$logid' and title = 'E-book Profile'");
			$E_book=mysql_fetch_array($E_book1);
			$E_book_link = $E_book[content];

			$start = strpos($E_book_link, ".com/") + 5;
			$end = strpos($E_book_link, ".html") - $start + 5;
			$flipbook = substr($E_book_link, $start, $end);

			$E_pup1 = mysql_query("SELECT content FROM `aqb_pc_members_blocks` WHERE `owner_id` = '$logid' and title = 'E-PUB Profile'");
			$E_pup=mysql_fetch_array($E_pup1);
			$E_pup_link = $E_pup[content];

			$epub_name_arr = explode('.', $E_pub_link);
			if (strtolower(end($epub_name_arr)) == 'epub')
			    $epup = $E_pub_link;
			else
			    $epup = false;

    array_push($arrRows, array(
        'id' => $row[0],
        'nameOne' =>$row[2],
        'profileage' =>$row[11],
        'profilestate'=>(trim($row[12]))?$row[12]:'NA',
        'profilewaiting'=> (trim($row[13]))?$row[13]:'NA',
        'profilechildren'=>(trim($row[14]))?$row[14]:'NA',
        'profilefaith'=>(trim($row[15]))?str_replace(',', ', ', $row[15]):'NA',
        'profileethinicity'=> ($row[16])?str_replace(',', ', ', $row[16]):'NA',
        'profilechildage'=>(trim($row[17]))?$row[17]:'NA',
        'profileadoptiontype'=>(trim($row[18]))?$row[18]:'NA',
        'aboutHome'=>$row[13],
        'Pdf_link'=>$pdf_output,
        'Epub_link'=>$epubbook,
        'ebook_link' =>  $flipbook,
        'epup_link' =>  $epup,
        'pdf_new_url' =>$pdf_new_url,
        'profile_pth' => $Profile_pth,
        'profile_path_status'=>$profile_path_status
    ));

}

$tablename = ($_REQUEST['approve'] != 'undefined' && $_REQUEST['approve'] == 1) ? 'Profiles_draft' : 'Profiles';
$stringSQL_t = "SELECT  ID,FirstName,FLOOR( DATEDIFF( CURDATE( ) ,  `DateOfBirth` ) /365 ) as Age,DescriptionMe FROM " . $tablename . " where Couple = " . $logid . "";
$query = mysql_query($stringSQL_t);
$cmdtuples = mysql_num_rows($query);
$arrRows_couple = array();
$newval=array();
while (( $row = mysql_fetch_array($query, MYSQL_BOTH))) {
    array_push($arrRows_couple, array(
        'id' => $row[0],
        'nameOne' =>$row[1],
        'profileage' =>$row[2],
        'description' => $row[28]
    ));
}




//------ LETTER Section START -----------------//

$stringSQL = " select couple from Profiles where id = '$logid'";
$result = mysql_query( $stringSQL );
$row = mysql_fetch_array( $result );
if ( $row['couple']>0 ) {
	$stringSQ11L = "SELECT p1.id,p1.DescriptionMe, p1.agency_letter, p1.letter_aboutThem, p1.DearBirthParent, p2.DescriptionMe aboutp2,p1.others,p1.FirstName fname, p2.FirstName coupleName,p1.img_him,p1.img_her,p1.img_agency,p1.img_them,p1.img_mother
FROM Profiles p1, Profiles p2
WHERE p1.id = '$logid'
AND p1.couple = p2.id";
}
else {
	$stringSQ11L = "SELECT id,DescriptionMe, agency_letter, letter_aboutThem, DearBirthParent, '' as aboutp2,others,FirstName fname, '' as coupleName,img_him,img_her,img_agency,img_them,img_mother
FROM Profiles
WHERE id = '$logid'";
}
$query = mysql_query($stringSQ11L);
$rowhh = mysql_fetch_array($query);
	if($rowhh['img_him']!=''){
		$ff = badgedata::getPic2($rowhh['img_him']);
		foreach ($ff as $hh => $value) {
			$jj .=$value;
		}
		$dataimg1 = $jj;
	}
	if($rowhh['img_her']!=''){
		$ff12 = badgedata::getPic2($rowhh['img_her']);
		foreach ($ff12 as $hh => $value) {
			$jj12 .=$value;
		}
		$dataimg12 = $jj12;
	}
	if($rowhh['img_agency']!=''){

		$ff1 = badgedata::getPic2($rowhh['img_agency']);
		foreach ($ff1 as $hh1 => $value) {
			$jj1 .=$value;
		}
		$dataimg_agency1 = $jj1;
	}
	if($rowhh['img_them']!=''){
		$ff2 = badgedata::getPic2($rowhh['img_them']);
		foreach ($ff2 as $hh2 => $value) {
			$jj2 .=$value;
		}
		$dataimg_them1 = $jj2;
	}
	if($rowhh['img_mother']!=''){
		 $moth = badgedata::getPic2($rowhh['img_mother']);
		foreach ($moth as $key => $value) {
			 $jeeej .=$value;
		}
		$dataimg_mother1 = $jeeej;
	}


$stringSQL_Letterssort = "SELECT id,label FROM `letters_sort` WHERE profile_id= '$logid' ORDER BY order_by";
$query = mysql_query( $stringSQL_Letterssort );
$countlett = mysql_num_rows( $query );
$arrRows_letters_sort = array();
if($countlett > 0)
{
while ( ( $rowgg = mysql_fetch_array( $query, MYSQL_BOTH ) ) ) {

    $header = '';
    	$desc_lee = '';
    	$lettimg = '';

    if($rowgg['label'] == 'letter_agency' && ($rowhh[2]!='' || $rowhh['img_agency']!='')){

    	$header = 'AGENCY LETTER';
    	$desc_lee =utf8_encode($rowhh[2]);
    	$lettimg = $dataimg_agency1;
    }
    elseif ($rowgg['label'] == 'letter_mother' && ($rowhh[4]!='' || $rowhh['img_mother']!='')) {

    	$header = 'EXPECTING MOTHER LETTER';
    	$desc_lee = utf8_encode($rowhh[4]);
    	$lettimg = $dataimg_mother1;
    }
    elseif ($rowgg['label'] == 'letter_about_them' && ($rowhh[3]!='' || $rowhh['img_them']!='')) {

    	$header = 'LETTER ABOUT THEM';
    	$desc_lee = utf8_encode($rowhh[3]);
    	$lettimg = $dataimg_them1;
    }
     elseif ($rowgg['label'] == 'letter_about_him' && ($rowhh[1]!='' || $rowhh['img_him']!='')) {

    	$header = 'LETTER ABOUT '.strtoupper($rowhh[7]);
    	$desc_lee = utf8_encode($rowhh[1]);
    	$lettimg = $dataimg1;
    }
    elseif ($rowgg['label'] == 'letter_about_her' && ($rowhh[5]!='' || $rowhh['img_her']!='')) {

    	$header = 'LETTER ABOUT '.strtoupper($rowhh[8]);
    	$desc_lee = utf8_encode($rowhh[5]);
    	$lettimg = $dataimg12;
    }
    else{
    	if( preg_match('([a-zA-Z].*[0-9]|[0-9].*[a-zA-Z])', $rowgg['label']) ) 
			{ 
				$noval = explode('_', $rowgg['label']);
				$stringletter = "SELECT img,label,description FROM `letter` WHERE profile_id= '$logid' and id =  '".$noval[1]."'";
				$query2 = mysql_query( $stringletter );
				$row2 = mysql_fetch_array( $query2, MYSQL_BOTH );
				$header = strtoupper($row2['label']);
				$desc_lee = utf8_encode($row2['description']);
				$jeeej1 = '';
				$moth1 = badgedata::getPic2($row2['img']);
		foreach ($moth1 as $key => $value) {
			 $jeeej1 .=$value;
		}
		$lettimg =$jeeej1;
			} 
				
    	

    }

    
 	array_push($newval, array(
        'Header'          =>  $header,
        'DescLee'    =>  $desc_lee,
        'LettImage' =>  $lettimg        
    ));           

}
}
else{

	if($rowhh['DearBirthParent']!='' || $rowhh['img_mother']!=''){
		$header = 'EXPECTING MOTHER LETTER';
    	$desc_lee = utf8_encode($rowhh[4]);
    	$lettimg = $dataimg_mother1;
    	array_push($newval, array(
        'Header'          =>  $header,
        'DescLee'    =>  $desc_lee,
        'LettImage' =>  $lettimg        
    ));  
	}
	if($rowhh['agency_letter']!='' || $rowhh['img_agency']!=''){
		$header = 'AGENCY LETTER';
    	$desc_lee =utf8_encode($rowhh[2]);
    	$lettimg = $dataimg_agency1;
    	array_push($newval, array(
        'Header'          =>  $header,
        'DescLee'    =>  $desc_lee,
        'LettImage' =>  $lettimg        
    ));  
	}
	if($rowhh[1]!='' || $rowhh[10]!=''){
		$header = 'LETTER ABOUT '.strtoupper($rowhh[7]);
    	$desc_lee = utf8_encode($rowhh[1]);
    	$lettimg = $dataimg1;
    	array_push($newval, array(
        'Header'          =>  $header,
        'DescLee'    =>  $desc_lee,
        'LettImage' =>  $lettimg        
    ));  
	}
	if($rowhh[5]!='' || $rowhh[12]!=''){
		$header = 'LETTER ABOUT '.strtoupper($rowhh[8]);
    	$desc_lee = utf8_encode($rowhh[5]);
    	$lettimg = $dataimg12;
    	array_push($newval, array(
        'Header'          =>  $header,
        'DescLee'    =>  $desc_lee,
        'LettImage' =>  $lettimg        
    ));  
	}
	if($rowhh['letter_aboutThem']!='' || $rowhh['img_them']!=''){
		
    	$header = 'LETTER ABOUT THEM';
    	$desc_lee = utf8_encode($rowhh[3]);
    	$lettimg = $dataimg_them1;
    	array_push($newval, array(
        'Header'          =>  $header,
        'DescLee'    =>  $desc_lee,
        'LettImage' =>  $lettimg        
    ));  
	}

	 $stringletterelse = "SELECT img,label,description FROM `letter` WHERE profile_id= '$logid'";
				$query2l = mysql_query( $stringletterelse );
				while($row2l = mysql_fetch_array( $query2l, MYSQL_BOTH )){
				$headerl = strtoupper($row2l['label']);
				$desc_leel = utf8_encode($row2l['description']);
				$jeeej1l = '';
				$moth1l = badgedata::getPic2($row2l['img']);
		foreach ($moth1l as $key => $value) {
			 $jeeej1l .=$value;
		}
		$lettimgl =$jeeej1l;	
				

		array_push($newval, array(
        'Header'          =>  $headerl,
        'DescLee'    =>  $desc_leel,
        'LettImage' =>  $lettimgl        
    ));  }
				

}

//------ LETTER Section END -----------------//

$tablename = ( $_REQUEST['approve'] != 'undefined' && $_REQUEST['approve'] == 1 ) ? 'Profiles_draft' : 'Profiles';
$LastActivetime = mysql_fetch_array(mysql_query("SELECT `DateLastLogin` FROM `$tablename` WHERE `ID` = '{$logid}' LIMIT 1"));
$Activetime = $LastActivetime[DateLastLogin];

$date1 = time();
$date2 = strtotime($Activetime);
$getlogid = 0;

$dateDiff = $date1 - $date2;
$fullDays = floor($dateDiff / ( 60 * 60 * 24 ));
$fullHours = floor(( $dateDiff - ( $fullDays * 60 * 60 * 24 ) ) / ( 60 * 60 ));
$fullMinutes = floor(( $dateDiff - ( $fullDays * 60 * 60 * 24 ) - ( $fullHours * 60 * 60 ) ) / 60);

if ($Activetime == '0000-00-00 00:00:00') {
    $total = "";
} else {
    $total = "LAST ACTIVE " . $fullDays . " DAY(S) ago";
}

if ($cmdtuples >= 0) {

	if ($arrRows[0]['profileage']) {      
      $profile_age = $arrRows[0]['profileage'];
    }
    else {
      $profile_age = 'NA';
    }
    if ($arrRows_couple[0])
      if ($arrRows_couple[0]['profileage'])
      {
        $profile_age_couple = $arrRows_couple[0]['profileage'];
      }
      else
       $profile_age_couple = 'NA';

    if ($arrRows_couple[0])
      $pf_age = $profile_age . ' / ' . $profile_age_couple;
    else
      $pf_age = $profile_age;

  $ClickText='';
  $NoLetter='';
if(empty($newval)){
	$NoLetter='No letters to Display';
}
else{
	$ClickText='Click on the links below to expand and read the letters.';
}


    echo json_encode(array(
        'status' => 'success',
        'tabtitle'=> ($arrRows_couple[0]['nameOne'])?$arrRows[0]['nameOne'].' & '.$arrRows_couple[0]['nameOne']:$arrRows[0]['nameOne'],
        'profileage'=>$pf_age,
        'Profiles' => $arrRows,
        'ProfilesL' => $arrRowsL,
        'Profiles_couple' => $arrRows_couple,
        'letters_sort'=>$result_letters_sort,
        'lastactivetime' =>  $total,
        'other'=> $arrValuesOther,
        'ClickText' => $ClickText,
        'NoLetter'  => $NoLetter,
        'usrid'     =>  $uid, 
        'newletterval' => $newval,

    ));
} else {
    echo json_encode(array(
        'status' => 'err',
        'response' => 'Could not read the data'
    ));
}

}




function videoPage(){
$uid=$_GET['uid'];
$obj=array('objects'=>'Video Page','uid'=>$uid);
echo json_encode($obj);
}


function GetPhotos(){
define('BX_PROFILE_PAGE', 1);
$aData = '';

$id = ($_GET['id'] != 'undefined') ? $_GET['id'] : $loginId;
if ($loginId== 0) {
    $sql_condition_home = " and AllowAlbumView  = 3 ";
} else {
    $sql_condition_home = " and AllowAlbumView  != 2 ";
}
if ($_GET['type'] == 'user') {
    $sql_condition_statue = " and bx_photos_main.Status = 'approved'";
} else {
    $sql_condition_statue = "";
    $sql_condition_home = "";
}

if ($_REQUEST['from'] == 'home') {
    $logged = ($_GET['id'] != 'undefined') ? $_GET['id'] : $loginId;
    $sql1 = "SELECT ID FROM `sys_albums` WHERE `Owner` = '$logged' and Caption = 'Home Pictures' and `Type` = 'bx_photos' " . $sql_condition_home;
    $result1 = mysql_query($sql1);
    $row1 = mysql_fetch_array($result1, MYSQL_BOTH);
    $albumid = $row1['ID'];

    $sql = "SELECT bx_photos_main . *
FROM bx_photos_main, sys_albums_objects
WHERE bx_photos_main.Owner =$logged
AND bx_photos_main.id = sys_albums_objects.id_object
AND sys_albums_objects.id_album =$albumid  " . $sql_condition_statue . " ORDER BY sys_albums_objects.obj_order";
    $result = mysql_query($sql);
    $nos = mysql_num_rows($result);
    if ($nos >= 1) {
        $count = 1;

        while ($row = mysql_fetch_array($result, MYSQL_BOTH)) {
            $filename = badgedata::getSiteUrl().'/modules/boonex/photos/data/files/' . $row['ID'] . "." . $row['Ext'];
            if ($filename) {
                $margin_left = 0;
                $margin_top = 0;
                list($width, $height) = getimagesize(badgedata::getSiteUrl()."/m/photos/get_image/file/".$row['Hash'].".".$row['Ext']); 
                if ($width > 425) {
                    $per = (($width - 425) / $width) * 100;
                    $height = $height - (($height * $per) / 100);
                    $width = 425;
                }
                if ($height > 300) {
                    $per = (($height - 300) / $height) * 100;
                    $width = $width - (($width * $per) / 100);
                    $height = 300;
                }
                $margin_left = ($width < 425) ? (420 - $width) / 2 : 0;
                $width = ($margin_left == 0) ? 425 : $width;
                $margin_top = ($height < 300) ? (300 - $height) / 2 : 0;
                $height = ($margin_top == 0) ? 300 : $height;
                $aData[] = badgedata::getSiteUrl()."/m/photos/get_image/file/{$row[Hash]}.{$row[Ext]}";
                $bData[] = badgedata::getSiteUrl()."/m/photos/get_image/thumb/{$row[Hash]}.{$row[Ext]}";
                $cData[] = $row['Title'];
                $count++;
            }
        }
        echo json_encode(array(
            'status' => 'success',
            'data' => $aData,
            'bData' => $bData,
            'cData' => $cData
        ));
    } else {

        echo json_encode(array(
            'status' => 'error',
            'Pmessage' => 'No uploaded photos in the home album'
        ));
    }
} else if ($_REQUEST['from'] == 'profile') {
    $sql1 = "SELECT ID FROM `sys_albums` WHERE `Owner` = '$id' and Caption != 'Home Pictures'  and `Type` = 'bx_photos' " . $sql_condition_home;
    $result1 = mysql_query($sql1);
    $albumid = "";
    while ($row1 = mysql_fetch_array($result1)) {
        $albumid .= ' ' . $row1['ID'] . ',';
    }

    $albumid = substr($albumid, 0, -1);

    $sql = "SELECT bx_photos_main . *
        FROM bx_photos_main, sys_albums_objects
        WHERE bx_photos_main.Owner =$id
        AND bx_photos_main.id = sys_albums_objects.id_object
        AND sys_albums_objects.id_album in($albumid) " . $sql_condition_statue . " ORDER BY sys_albums_objects.id_album,sys_albums_objects.obj_order";
    $result = mysql_query($sql);
    $nos = mysql_num_rows($result);
    if ($nos >= 1) {
        $count = 1;
        while ($row = mysql_fetch_array($result)) {
            $filename = badgedata::getSiteUrl().'/modules/boonex/photos/data/files/' . $row['ID'] . "." . $row['Ext'];
     
            if ($filename) {
                $margin_left = 0;
                $margin_top = 0;
                list($width, $height) = getimagesize(badgedata::getSiteUrl()."/m/photos/get_image/file/".$row['Hash'].".".$row['Ext']); 
                if ($width > 425) {
                    $per = (($width - 425) / $width) * 100;
                    $height = $height - (($height * $per) / 100);
                    $width = 425;
                }
                if ($height > 300) {
                    $per = (($height - 300) / $height) * 100;
                    $width = $width - (($width * $per) / 100);
                    $height = 300;
                }
                $margin_left = ($width < 425) ? (425 - $width) / 2 : 0;
                $width = ($margin_left == 0) ? 425 : $width;
                $margin_top = ($height < 300) ? (300 - $height) / 2 : 0;
                $height = ($margin_top == 0) ? 300 : $height;
                $aData[] = badgedata::getSiteUrl()."/m/photos/get_image/file/" . $row[Hash] . "." . $row[Ext];
                $bData[] = badgedata::getSiteUrl()."/m/photos/get_image/thumb/" . $row[Hash] . "." . $row[Ext];
                $cData[] = $row['Title'];
                $count++;
            }
        }
        $photostatus = 'success';
        $Pmessage = '';
    } else {
        $photostatus = 'error';
        $Pmessage = 'No uploaded photos in the albums';
        $bData[] = '';
        $aData[] = '';
    }

    $aDataVideo [] = <<<EOF
				<div><a href='badgedata::getSiteUrl()/flash/modules/video/files/108.flv' style='display:block;width:560px;height:345px' id='player1'></a> 
				<script>
				flowplayer("player{$i}", "flowplayer-3.2.6.swf",{
				width: '560',
				height: '345',
				clip: {
					autoPlay: false,
				  }
				});
				</script></div><div>{$row[Description]}</div>;-
EOF;

    $videostatus = 'success';
    $Vmessage = 'Photo Not Available';

    echo json_encode(array(
        'status' => $photostatus,
        'data' => $aData,
        'sql' => $sql . $sql1,
        'bData' => $bData,
        'cData' => $cData,
        'Pmessage' => $Pmessage,
        'Vmessage' => $Vmessage,
        'statusVideo' => $videostatus,
        'video' => $aDataVideo,
    ));
}
}

function GetVideos(){

    $logID=0;//getLoggedId(); 6807
    $logged = ($_GET['id'] != 'undefined') ? $_GET['id'] : $logID;

if ($logID == 0) {
    $sql_condition_home = " and AllowAlbumView  = 3 ";
} else {
    $sql_condition_home = " and AllowAlbumView  != 2 ";
}
if ($_GET['type'] != 'user') {
    $sql_condition_home = "";
} 
$sql_condition_home = " and AllowAlbumView  = 3 ";
    $sql1 = "SELECT ID FROM `sys_albums` WHERE `Owner` = '$logged' and `Type` = 'bx_videos' " . $sql_condition_home;
    $result1 = mysql_query($sql1);

    $albumid = "";
    while ($row1 = mysql_fetch_array($result1)) {
        $albumid .= ' ' . $row1['ID'] . ',';
    }

    $albumid = substr($albumid, 0, -1);
    $sql = "SELECT ph.* FROM RayVideoFiles as ph,sys_albums_objects sab WHERE ph.ID=sab.id_object AND sab.id_album in ($albumid) AND ph.Status = 'approved' AND ph.ytStatusCheck='processed'";
    $result = mysql_query($sql);
    $nos = mysql_num_rows($result);
    if ($nos >= 1) {
        $i = 1;
        $aData = array();
        $youtubeCode = array();
        while ($row = mysql_fetch_array($result)) {
           if ($row['Source'] == 'youtube' && $nos >= 1) {
                //  $aData [] = "<div><iframe title='YouTube video player' width='560' height='345' src='http://www.youtube.com/embed/" . $row['Video'] . "' frameborder='0' allowfullscreen></iframe></div>";
                // $aData[]= array('id' => $row[ID],'source' => 'youtube', 'srcid' => $row['Video']); 
            } elseif ($row['Source'] != 'youtube' && $nos >= 1) {
//                $aData[] = array('id' => $row[ID], 'source' => 'boonex', 'srcid' => $row[ID]);
                $videoURL = '';
                if ($row['YoutubeLink'] == '1') {
                    $videoURL = 'https://www.youtube.com/watch?v=' . $row['Uri'];
                    $youtubeCode[$row[ID]] = $row['Uri'];
                } else {
                if(file_exists('../../flash/modules/video/files/_'.$row[ID].'.flv'))
                    $videoURL = 'https://'.$_SERVER[HTTP_HOST].'/flash/modules/video/files/_'.$row[ID].'.flv';
                else if(file_exists('../../flash/modules/video/files/_'.$row[ID].'.mp4'))
                    $videoURL = 'https://'.$_SERVER[HTTP_HOST].'/flash/modules/video/files/_'.$row[ID].'.mp4';
                }
                $aData[] = array('id' => $row[ID], 'source' => 'boonex', 'srcid' => $row[ID], 'videoURL' => $videoURL, 'youTube' => $row['YoutubeLink']);
            }
            $i++;
        }
        echo json_encode(array(
            'status' => 'success',
            'data' => $aData,
            'youtube' => $youtubeCode
        ));
    } else {


        $rs = mysql_query("SELECT A.`videoName` , A.`videoUri` FROM  `bx_groups_main` A, Profiles P WHERE A.`id` = P.AdoptionAgency AND P.id =$logged");
	$res = mysql_fetch_array($rs, MYSQL_ASSOC);        
	if($res != null){
            $aData[] = array('id' => '0', 'source' => 'boonex', 'srcid' =>'0', 'videoURL' => 'https://www.youtube.com/watch?v=' . $res['videoUri'], 'youTube' => '1');
            $youtubeCode[0] = $res['videoUri'];
            echo json_encode(array(
                'status' => 'success',
                'data' => $aData,
                'youtube' => $youtubeCode
            ));
        }
        else {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'No uploaded videos',
                'sql' => $sql . $sql1 . $albumid
            ));
        }
    
    }
}

function GetVideosH(){
    $logID=0;//getLoggedId(); 6807
    $logged = ($_GET['id'] != 'undefined') ? $_GET['id'] : $logID;

if ($logID == 0) {
    $sql_condition_home = " and AllowAlbumView  = 3 ";
} else {
    $sql_condition_home = " and AllowAlbumView  != 2 ";
}
if ($_GET['type'] != 'user') {
    $sql_condition_home = "";
} 
$sql_condition_home = " and AllowAlbumView  = 3 ";
    $sql1 = "SELECT ID FROM `sys_albums` WHERE `Owner` = '$logged' and Caption = 'Home Videos' and `Type` = 'bx_videos' " . $sql_condition_home;
    $result1 = mysql_query($sql1);

    $albumid = "";
    while ($row1 = mysql_fetch_array($result1)) {
        $albumid .= ' ' . $row1['ID'] . ',';
    }

    $albumid = substr($albumid, 0, -1);
     $sql = "SELECT ph.* FROM RayVideoFiles as ph,sys_albums_objects sab WHERE ph.ID=sab.id_object AND sab.id_album in ($albumid) AND ph.Status = 'approved' AND ph.ytStatusCheck='processed'";
    $result = mysql_query($sql);
    $nos = mysql_num_rows($result);
    if ($nos >= 1) {
        $i = 1;
        $aData = array();
        $youtubeCode = array();
        while ($row = mysql_fetch_array($result)) {
            if ($row['Source'] == 'youtube' && $nos >= 1) {
            	 $aData[] = array('id' => $row[ID], 'source' => 'youtube', 'srcid' => $row['Video']);
            } elseif ($row['Source'] != 'youtube' && $nos >= 1) {
                $videoURL = '';
                if ($row['YoutubeLink'] == '1') {
                    $videoURL = 'https://www.youtube.com/watch?v=' . $row['Uri'];
                    $youtubeCode[$row[ID]] = $row['Uri'];
                } else {
                    if(file_exists('../../flash/modules/video/files/_'.$row[ID].'.flv'))
                        $videoURL = 'http://'.$_SERVER[HTTP_HOST].'/flash/modules/video/files/_'.$row[ID].'.flv';
                    else if(file_exists('../../flash/modules/video/files/_'.$row[ID].'.mp4'))
                        $videoURL = 'http://'.$_SERVER[HTTP_HOST].'/flash/modules/video/files/_'.$row[ID].'.mp4';
                }
                $aData[] = array('id' => $row[ID], 'source' => 'boonex', 'srcid' => $row[ID], 'videoURL' => $videoURL, 'youTube' => $row['YoutubeLink']);
            }
            $i++;
        }
        echo json_encode(array(
            'status' => 'success',
            'data' => $aData,
            'youtube' => $youtubeCode
        ));
    } else {
        $rs = mysql_query("SELECT A.`videoName` , A.`videoUri` FROM  `bx_groups_main` A, Profiles P WHERE A.`id` = P.AdoptionAgency AND P.id =$logged");
	$res = mysql_fetch_array($rs, MYSQL_ASSOC);        
	if($res['videoUri'] != null){
            $aData[] = array('id' => '0', 'source' => 'boonex', 'srcid' =>'0', 'videoURL' => 'https://www.youtube.com/watch?v=' . $res['videoUri'], 'youTube' => '1');
            $youtubeCode[0] = $res['videoUri'];
            echo json_encode(array(
                'status' => 'success',
                'data' => $aData,
                'youtube' => $youtubeCode
            ));
        }
        else {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'No uploaded Videos',
            ));
        }
    }
}

function getProfileInfo($iProfileID = 0, $checkActiveStatus = false, $forceCache = false)
{
    global $aUser;
    $iProfileID = !empty($iProfileID) ? (int)$iProfileID : $llid;
    if(!$iProfileID)
        return false;

    if(!isset( $aUser[$iProfileID]) || !is_array($aUser[$iProfileID]) || $forceCache) {
        $sCacheFile = BX_DIRECTORY_PATH_CACHE . 'user' . $iProfileID . '.php';
        if( !file_exists( $sCacheFile ) || $forceCache ) {
            if( !createUserDataFile( $iProfileID ) ) {
                return getProfileInfoDirect ($iProfileID);
            }
        }

        @include( $sCacheFile );
    }

    if( $checkActiveStatus and $aUser[$iProfileID]['Status'] != 'Active' )
        return false;

    return $aUser[$iProfileID];
}

function PreLike(){
$logid = badgedata::getLoggedId();
$profileDetails = badgedata::getProfileInfo($logid);
$cmdtuples = 1;

if ($cmdtuples > 0) {
	echo json_encode(array(
			'status' => 'success',
			'Profiles_value' => array(
				'rows' => $logid,
                            'profile_typoe'=>$profileDetails['ProfileType']
			)
		));
}
else {
	echo json_encode(array(
			'status' => 'err',
			'response' => 'Could not read the data: ' . mssql_get_last_message()
		));
}	
}

function userLike(){

$user_id = $_POST['userid'];
$BM_id = badgedata::getLoggedId();
$profileDet = badgedata::getProfileInfo($user_id);


if($profileDet['ProfileType'] == 8){
$stringSQL="select LikedBy from like_list where AgencyLike = $user_id and LikedBy = $BM_id";
$query = mysql_query($stringSQL);
$cmdtuples = mysql_num_rows($query);
if($cmdtuples <=0 ){
$DeleteLike_List = "INSERT INTO like_list(`LikedBy`, `AgencyLike`) VALUES ('" . $BM_id . "', '" . $user_id . "')";
mysql_query($DeleteLike_List);
if (mysql_affected_rows() > 0)
{
echo json_encode(array(
'status' => 'success',
'sql_statement' => $DeleteLike_List
));
}
  else
{
echo json_encode(array(
'status' => 'err',
'response' => 'Could not read the data: ',
        'sql'=>$stringSQL
));
}
}
else{
    echo json_encode(array(
'status' => 'err',
'response' => 'You had like this before',
        'sql'=>$stringSQL
));
}
}
else{

$stringSQL="select LikedBy from like_list_family where FamilyLiked = $user_id and LikedBy = $BM_id";
$query = mysql_query($stringSQL);
$cmdtuples = mysql_num_rows($query);
if($cmdtuples <=0 ){
$DeleteLike_List = "INSERT INTO like_list_family(`LikedBy`, `FamilyLiked`) VALUES ('" . $BM_id . "', '" . $user_id . "')";
mysql_query($DeleteLike_List);
if (mysql_affected_rows() > 0)
{
echo json_encode(array(
'status' => 'success',
'sql_statement' => $DeleteLike_List
));
}
  else
{
echo json_encode(array(
'status' => 'err',
'response' => 'Could not read the data: ',
        'sql'=>$stringSQL
));
}
}
else{
    echo json_encode(array(
'status' => 'err',
'response' => 'You had like this before',
        'sql'=>$stringSQL
));
}
}
}


function topMenu(){


$logid = badgedata::getLoggedId();
$cmdtuples = 1;

$stringSQL_Children = "SELECT config_value, config_description FROM sys_configuration WHERE config_key = 'noofchildren' ORDER BY config_order ASC ";
$query_children     = mysql_query($stringSQL_Children);

$arrColumns = explode(",", 'config_description');
$arrRows_children = array();
while (($row_children = mysql_fetch_array($query_children, MYSQL_BOTH))) {
	$arr_child_Values = array();
	foreach ($arrColumns as $column_name) {
		array_push($arr_child_Values, $row_children[$column_name]);
	}

	array_push($arrRows_children, array(
			'id' => $row_children[0],
			'data' => $arr_child_Values,
		));
}

$arrRows_state       = array();
$stringSQL_state = "SHOW COLUMNS FROM Profiles WHERE Field = 'state' ";
$query_state     = mysql_query($stringSQL_state);
while (($rows = mysql_fetch_array($query_state, MYSQL_BOTH))) {
    //print_r($rows);
    $valueType    = $rows['Type'];
}

preg_match('/^enum\((.*)\)$/', $valueType, $matches);
foreach( explode(',', $matches[1]) as $value )
{
    $arrRows_state[]['id'] = trim( $value, "'" );
}


$arrRows_ethnicity = array(array('id'=>'Middle Eastern','data'=>'Middle Eastern'),
			         array('id'=>'Asian','data'=>'Asian'),
			         array('id'=>'African American','data'=>'African American'),
			         array('id'=>'African American/Asian','data'=>'African American/Asian'),
			         array('id'=>'Asian/Hispanic','data'=>'Asian/Hispanic'),
			         array('id'=>'Bi-Racial','data'=>'Bi-Racial'),
			         array('id'=>'Caucasian','data'=>'Caucasian'),
			         array('id'=>'Caucasian/Asian','data'=>'Caucasian/Asian'),
			         array('id'=>'Caucasian/African American','data'=>'Caucasian/African American'),
			         array('id'=>'Caucasian/Hispanic','data'=>'Caucasian/Hispanic'),
			         array('id'=>'European','data'=>'European'),
			         array('id'=>'Caucasian/Native American','data'=>'Caucasian/Native American'),
			         array('id'=>'Eastern European/Slavic/Russian','data'=>'Eastern European/Slavic/Russian'),
			         array('id'=>'Hispanic','data'=>'Hispanic/African American'),
			         array('id'=>'Hispanic/African American','data'=>'Asian'),
			         array('id'=>'Jewish','data'=>'Jewish'),
			         array('id'=>'Mediterranean','data'=>'Mediterranean'),
			         array('id'=>'Multi-Racial','data'=>'Multi-Racial'),
			         array('id'=>'Native American (American Indian)','data'=>'Native American (American Indian)'),
			         array('id'=>'Pacific Islander','data'=>'Pacific Islander'),
			         array('id'=>'Other','data'=>'Other'));

$tablename = 'sys_pre_values';
$columns = 'Value,LKey';
$stringSQL_Region = "SELECT  " . $columns . " FROM " . $tablename . " WHERE `Key` = 'ReligionCouple' ORDER BY `sys_pre_values`.`Order` ASC";
$query = mysql_query($stringSQL_Region);
$cmdtuples = mysql_num_rows($query);
$arrColumns = explode(",", $columns);
$arrRows_ReligionCouple = array();

while (($row = mysql_fetch_array($query, MYSQL_BOTH))) {
	$arrValues = array();
	/*foreach ($arrColumns as $column_name) {
		array_push($arrValues, $row[$column_name]);
	}*/

	array_push($arrRows_ReligionCouple, array(
		'id' => $row[0],
		'data' => $row[1],
	));
}



$tablename = 'sys_pre_values';
$columns = 'Value,LKey';
$stringSQL_Region = "SELECT  " . $columns . " FROM " . $tablename . " WHERE `Key` = 'Region' ORDER BY `sys_pre_values`.`Order` ASC";
$query = mysql_query($stringSQL_Region);
$cmdtuples = mysql_num_rows($query);
$arrColumns = explode(",", 'Value');
$arrRows_Region_new = array();

while (($row = mysql_fetch_array($query, MYSQL_BOTH))) {
       $arrValues = array();
       foreach ($arrColumns as $column_name) {
               array_push($arrValues, $row[$column_name]);
       }

       array_push($arrRows_Region_new, array(
               'id' => $row[0],
               'data' => $row[1],
       ));
}

$arrRows_sort = array(array('id'=>'newFirst','data'=>'Newest First'),
	            array('id'=>'oldFirst','data'=>'Oldest First'),
	            array('id'=>'FirstName','data'=>'FirstName'),
	            array('id'=>'random','data'=>'Random'));

if ($cmdtuples > 0)
{
echo json_encode(array(
'status' => 'success',
'Profiles_value' => array(
'rows' => $logid
),
'children_value'    =>$arrRows_children,
'state_value'       =>$arrRows_state,
'ethnicity_value'   =>$arrRows_ethnicity,
'religion_value'    =>$arrRows_ReligionCouple,
'region_value'      =>$arrRows_Region_new,
'sort_value'        =>$arrRows_sort,
));
}
else
{
echo json_encode(array(
'status' => 'err',
'response' => 'Could not read the data: ' . mssql_get_last_message()
));
}

}

function MoreVideos(){
	badgedata::contactPage();
}


function MorePhotos(){
	badgedata::contactPage();
}

//Journal Pages
function journalcontent(){
	$uid=mysql_real_escape_string($_GET['uid']);
	$jod=mysql_real_escape_string($_GET['jod']);
	$logid = ( $uid != 'undefined' ) ? $uid : 0;
	$tablename ='bx_blogs_posts';
	if($jod && $jod!=0){
		$stringSQL_Posts = "SELECT REPLACE(REPLACE(PostText,'<p>',''),'</p>','') as `PostText`, FROM_UNIXTIME(PostDate,'%M %d, %Y') as PostDate,FROM_UNIXTIME(PostDate,'%h %i %s') as Posttime, PostCaption, PostID FROM " . $tablename . " where OwnerID = " . $logid . " and PostID =".$jod." and PostStatus = 'approval'  and allowView IN ('4','3') ORDER BY FROM_UNIXTIME(PostDate,'%Y %m, %d') DESC ,
 FROM_UNIXTIME(PostDate,'%h %i %s') DESC";
	}
	else{
 		$stringSQL_Posts = "SELECT REPLACE(REPLACE(PostText,'<p>',''),'</p>','') as `PostText`, FROM_UNIXTIME(PostDate,'%M %d, %Y') as PostDate,FROM_UNIXTIME(PostDate,'%h %i %s') as Posttime, PostCaption, PostID FROM " . $tablename . " where OwnerID = " . $logid . " and PostStatus = 'approval'  and allowView IN ('4','3') ORDER BY FROM_UNIXTIME(PostDate,'%Y %m, %d') DESC ,
 FROM_UNIXTIME(PostDate,'%h %i %s') DESC";
	}
	
	$query = mysql_query($stringSQL_Posts);
	$cmdtuples = mysql_num_rows($query);
	$arrRows = array();
	$arrRows_posts_blog = array();
	while (($row = mysql_fetch_array($query, MYSQL_BOTH))) {
        $arrValues = array();
                $trimText = preg_replace("/<img[^>]+\>/i", " ", $row['PostText']);
	           $trimText = strip_tags(trim(preg_replace('/\s+/', ' ', $trimText)),'<span><p><a>');
$trimText = htmlentities($trimText,ENT_QUOTES, 'UTF-8');
	$trimText = str_replace('&nbsp;', '', $trimText);
                
                array_push($arrValues, $trimText);
            array_push($arrValues, $row[$column_name]);
        //array_push($arrValues, $row['PostID']);
        //array_push($arrRows, $row[0]);
        //array_push($arrRows, $row['PostCaption']);
        $jLink = preg_replace('/[^a-zA-Z ]/i', '', $row[3]);
                //echo badgedata::getnickname2($logid);
              $postcaplink = badgedata::getSiteUrl().'/'.badgedata::getnickname2($logid).'/journal/' . $jLink .'/' . $row[4].'/badge/';
               $PostCaption =  $row[3];
               if($jod==0){
               	if (strlen($trimText) > 200) {
	                 $trimText = substr($trimText, 0, 196);
	                $trimText .= '...';
               }else{
               		$trimText=$trimText;
               }
               }else{
               		$trimText=$trimText;
               }
        if($trimText == ' '){
               	$trimText = str_replace(' ', '', $trimText);
               }         
        array_push($arrRows_posts_blog, array(
        'id'          =>  $logid,
        'TrimText'    =>  $trimText,
        'PostCaption' =>  $PostCaption,
        'postcaplink' =>  $postcaplink, 
        'PostID'      =>  $row['PostID'],
        'PostDate'    =>  $row['PostDate']
    ));
    }
    //print_r($arrRows_posts_blog);
if ($cmdtuples > 0) {
    echo json_encode(array(
        'status' => 'success',
        'journalcontent' =>  $arrRows_posts_blog
    ));
} else {
    echo json_encode(array(
        'status' => 'err',
        'response' => 'Could not read the data'
    ));
}
		
}

function getPic2($picID){
	$img = json_decode($picID);
	$img = implode(',', $img);	
	$q2 = "SELECT CONCAT(Hash,'.', Ext) as img, Uri FROM `bx_photos_main` WHERE ID IN($img);";
	$r2 = mysql_query($q2);	
	while($rw = mysql_fetch_array($r2)){
		$bImage=$rw["img"];
		 if(getimagesize('https://www.parentfinder.com/m/photos/get_image/thumb/'.$bImage)!= false)
		 {
		 	$dd = $bImage;
		 	$img2[] ='<img src="https://www.parentfinder.com/m/photos/get_image/thumb/'.$bImage.'" />&nbsp;';	
		 }
		
	}
	$ret =$img2;
	return $ret;

}

 function format_phone2($phone){
    	$phone = preg_replace("/[^0-9]/", "", $phone);
    	if(strlen($phone) == 7){
    		$ph = preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
    		}
    	elseif(strlen($phone) == 10){
    		$ph = preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
    		}
    	else{
    		$ph = $phone;
    	}
    		return $ph;
    }


}
?>
