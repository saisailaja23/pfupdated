<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
//header("content-type: application/json");
header('Content-Type: application/json; charset=utf-8');

$servername = "localhost";
$username = "root";
$password = "I4GotIt";
$conn = mysql_connect($servername, $username, $password);
if (!$conn) {
    die("Connection failed: " . mysql_connect_error());
}
mysql_select_db('pfcomm',$conn); 
global $site;

$page=$_REQUEST['page'];
switch($page){
case 'index':foo::indexPage();
break;
case 'About':foo::aboutPage();	
break;
case 'OurHome':foo::ourHomePage();	
break;
case 'Contact':foo::contactPage();	
break;
case 'Letter':foo::letterPage();	
break;
case 'Video':foo::videoPage();	
break;
case 'ContactSubmit':foo::sendMail();
break;
case 'GetPhotos':foo::GetPhotos();
break;
case 'GetVideos':foo::GetVideos();
break;
case 'GetVideosH':foo::GetVideosH();
break;
case 'PreLike':foo::PreLike();
break;
case 'userLike':foo::userLike();
break;
case 'MoreVideos':foo::MoreVideos();
break;
case 'MorePhotos':foo::MorePhotos();
break;
case 'topMenu':foo::topMenu();
break;
default: foo::indexPage();
}


class foo{


function getSiteUrl()
{
 return $gsiteName='http://www.parentfinder.com';
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
    $aMemberInfos = mysql_query("SELECT *
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
    $adoptiveparents = mysql_query("SELECT *
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
	//echo $_REQUEST['uid']; exit;
$start=$_REQUEST['start'];
$end=$_REQUEST['end'];
//$searchKey=$_REQUEST['search'];
$sstat=$_REQUEST['sstat'];
$seed=$_REQUEST['seed'];
$uid=$_REQUEST['uid'];
$agencyId =$uid;
$agencyId = foo::test_input($agencyId);


//=========================search query============================//
$searchvalues = $_REQUEST['sortvalue'];
$searchvalues = foo::test_input($searchvalues);

$searchtype = $_REQUEST['type'];
$searchtype = foo::test_input($searchtype);

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

$searchFilter = ($_REQUEST['name'] && $_REQUEST['name'] != 'undefined') ? $_REQUEST['name'] : '';

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

//echo $AgencyLike_List;exit;

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
				$first = foo::age($row_agency[2]);
				//$first = date("Y") - foo::age($row_agency[2]) . substr(0, 4);
			}
			if ($Couple_age != '0000-00-00') {
				$second = foo::age($Couple_age);
				//$second = date("Y") - foo::age($Couple_age) . substr(0, 4);
			}
			$profileages = $first . '/' . $second;
			if ($row_agency[2] == '0000-00-00' || $Couple_age == '0000-00-00') {
				$profileages = "NA";
			}
			} else {

				if ($row_agency[2] != '0000-00-00') {
					$firstsingle = foo::age($row_agency[2]);
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
            $Profile_pth = (trim(str_replace(foo::getSiteUrl(), '/', $ProfileQ[template_file_path])) != '') ? trim(str_replace(foo::getSiteUrl(), '/', $ProfileQ[template_file_path])) : 'javascript:void(0)';

				$parsed = parse_url($Profile_pth);
				$path = $parsed['path'];
				$path_parts = explode('/', $path);
				if($path_parts[7]){
				$pdf_output = foo::getSiteUrl().'/'.$path_parts[5].'/'.$path_parts[6].'/'.$path_parts[7];
				}else{
				$pdf_output ='javascript:void(0)';	
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
         $name='<span style="font-size: 14px !important;">'.$profilename.'</span>';	
         }

         if(strlen(trim($childethnicity))>79){
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
         else if(strlen($faiths) < 80){
         	$faithval = $faiths;

         }
         else{
         		$faithval = substr($faiths, 0, 80).'<a class="pf_view_cl017" style="display:'.$faiths.'"><span data-toggle="modal" href="#more" data="'.$faiths.'">....More</span></a>';
         }
         $sImage = foo::getSiteUrl().'/modules/boonex/avatar/data/favourite/' . $row_agency[10] . '.jpg';
         $json[]=array('Image'=>$sImage,'Title'=>$name,'Id'=>$id, 'ChildAgesL'=>$Childageslarge, 'ChildAgesS'=>$Childagessmall, 'Age'=>$profileages, 'State'=>$state, 'CE'=>substr($childethnicity, 0, 80), 'CEF'=>$childethnicity, 'CEFV'=>$cefv,'Children'=>$children,'AdptType'=>$adoptiontype,'Faiths'=>substr($faiths, 0, 80),'fullFaiths'=>$faithval,'Class'=>$class,'Pdf_link'=>$pdf_output,'Epub_link'=>$epub_link);
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
$obj=array('objects'=>$json,'display'=>$dispay,'searchMess'=>$rstat);
echo json_encode($obj);
}


function ourHomePage(){
	foo::aboutPage();
}



function aboutPage(){
$uid=$_GET['uid'];
//$uid=3946;
//$uid=3000;
$logid = ( $uid != 'undefined' ) ? $uid : 0;
$logged_id = foo::getLoggedId();

$tablename = ( $_REQUEST['approve'] != 'undefined' && $_REQUEST['approve'] == 1 ) ? 'Profiles_draft' : 'Profiles';
$stringSQL = "SELECT  ID,Email,FirstName,State,Password,AdoptionAgency,Facebook,Twitter,Google,Blogger,Pinerest,FLOOR( DATEDIFF( CURDATE( ) ,  `DateOfBirth` ) /365 ) as Age,state,waiting,(SELECT config_description FROM sys_configuration WHERE config_value = noofchildren ) AS noofchildren,faith,childethnicity,childage,Adoptiontype,phonenumber,street_address,DescriptionMe,yardsize,noofbathrooms,noofbedrooms,housestyle,WEB_URL,Couple,About_our_home,NickName,address1,address2,city,zip,Status,childrenType,Region,Country FROM " . $tablename . " where ID = " . $logid . "";
$query = mysql_query($stringSQL);
$cmdtuples = mysql_num_rows($query);
$arrRows = array();


while (( $row = mysql_fetch_array($query, MYSQL_BOTH))) {
			$ProfileQ1 = mysql_query("SELECT template_file_path  FROM pdf_template_user WHERE user_id =$row[0] AND isDeleted = 'N' AND isDefault ='Y' LIMIT 1");
			$ProfileQ=mysql_fetch_array($ProfileQ1,MYSQL_BOTH);          
            $Profile_pth = (trim(str_replace(foo::getSiteUrl(), '/', $ProfileQ[template_file_path])) != '') ? trim(str_replace(foo::getSiteUrl(), '/', $ProfileQ[template_file_path])) : 'javascript:void(0)';

				$parsed = parse_url($Profile_pth);
				$path = $parsed['path'];
				$path_parts = explode('/', $path);
				if($path_parts[7]){
				$pdf_output = foo::getSiteUrl().'/'.$path_parts[5].'/'.$path_parts[6].'/'.$path_parts[7];
				}else{
				$pdf_output ='javascript:void(0)';	
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


    array_push($arrRows, array(
        'id' => $row[0],
        'nameOne' =>$row[2],
        'profileage' =>$row[11],
        'profilestate'=>(trim($row[12]))?$row[12]:'NA',
        'profilewaiting'=> (trim($row[13]))?$row[13]:'NA',
        'profilechildren'=>(trim($row[14]))?$row[14]:'NA',
        'profilefaith'=>(trim($row[15]))?$row[15]:'NA',
        'profileethinicity'=>($row[16])?str_replace(',', ', ', $row[16]):'NA',
        'profilechildage'=>(trim($row[17]))?$row[17]:'NA',
        'profileadoptiontype'=>(trim($row[18]))?$row[18]:'NA',
        'aboutHome'=>$row[13],
        'profile1' =>(trim($row[21]))?'<b>ABOUT '. trim(strtoupper($row[2])). '</b> <span>'.trim($row[21]).'</span>':'',
        'website' =>(trim($row[26]))?trim($row[26]):'',
        'Pdf_link'=>$pdf_output,
        'Epub_link'=>$epubbook,
        'ebook_link' =>  $flipbook,
        'epup_link' =>  $epup,
        'state_type' => $state_type,
        'state_val'  => $state_val,
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
if (!foo::getLoggedId()) {
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
                //echo foo::getnickname2($logid);
               $postcaplink = foo::getSiteUrl().'/'.foo::getnickname2($logid).'/journal/' . $jLink .'/' . $row[4].'/badge/';
               $PostCaption =  $row[3];

if($i<=4){
	$trimText = htmlentities($trimText,ENT_QUOTES, 'UTF-8');
	$trimText = str_replace('&nbsp;', '', $trimText);
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
	 $morepost =foo::getSiteUrl().'/'.foo::getnickname2($logid).'/journals/badge';
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
    $avatar_img = '<img class="headIcon" src="foo::getSiteUrl()/modules/boonex/avatar/data/images/' . $author_thumb . ".jpg" . '">';
else
   $avatar_img = '<img class="headIcon" src="foo::getSiteUrl()/templates/tmpl_par/images/agency_thumb_small.png" alt="" title="" />'; 

$agency_opt_details=$arrRows_agencyaddress[0]['id'].'<br/>'.$arrRows_agencyaddress[0]['city'].$arrRows_agencyaddress[0]['state'].$arrRows_agencyaddress[0]['zip'].'<br/>';
$agency_opt_details.=$arrRows_agencyaddress[0]['contact'].'<a href="mailto:'. $arrRows_agencyaddress[0]['a1']. '">'. $arrRows_agencyaddress[0]['a1'].'</a><br/><a  target ="_blank" href="//'. $arrRows_agencyaddress[0]['a2']. '">' . $arrRows_agencyaddress[0]['a2'] . '</a>';
    $output = array(
        'status' => 'success',
        'uid'    =>  $uid,
        'tabtitle'=> ($arrRows_couple[0]['nameOne'])?$arrRows[0]['nameOne'].' & '.$arrRows_couple[0]['nameOne']:$arrRows[0]['nameOne'],
        'profileage'=>$pf_age,
        'profile1'  => $arrRows[0]['profile1'],
        'profile2'  => $arrRows_couple[0]['profile2'],
        'website'   => $arrRows[0]['website'],
        'agency_address' => ($arrRows_agencyaddress[0]['id'])?$agency_opt_details:'',
        'agency_logo'  => $avatar_img,
        'Profiles' => $arrRows,
        'blog_posts' => $arrRows_posts,
        'blog_posts_more' =>$morepost,
        'Profiles_couple' => $arrRows_couple,
        'lastactivetime' =>  $total
    );

   //$output = foo::raw_json_encode($output);
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

$id=$_REQUEST['uid'];

$res = mysql_query("SELECT show_contact,phonenumber FROM `Profiles` WHERE `ID` = ".$id);
$row_c = mysql_fetch_array($res); 
$sc=$row_c['show_contact']; 

if($sc == 1){
	$nocontact = foo::format_phone2($row_c['phonenumber']);
}else{
	$sub_qry2 = mysql_query("SELECT CONTACT_NUMBER
FROM `Profiles` as pr
LEFT JOIN `bx_groups_main` AS g ON pr.AdoptionAgency = g.id
WHERE pr.`ID`IN (SELECT bx_groups_main.author_id FROM Profiles 
JOIN bx_groups_main WHERE Profiles.ID = $id AND Profiles.AdoptionAgency=bx_groups_main.id)");

$row_c12 = mysql_fetch_array($sub_qry2);
$nocontact = foo::format_phone2($row_c12['CONTACT_NUMBER']);	
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
		$img2[] ='<img src=http://www.parentfinder.com/m/photos/get_image/thumb/'.$bImage.' />';	
	}
	$ret =$img2;
	return $ret;
}




function letterPage(){
//require_once '../inc/design.inc.php';

$uid=$_GET['uid'];
//$uid=3946;
//$uid=3000;
$logid = ( $uid != 'undefined' ) ? $uid : 0;
$logged_id = foo::getLoggedId();

$tablename = ( $_REQUEST['approve'] != 'undefined' && $_REQUEST['approve'] == 1 ) ? 'Profiles_draft' : 'Profiles';
$stringSQL = "SELECT  ID,Email,FirstName,State,Password,AdoptionAgency,Facebook,Twitter,Google,Blogger,Pinerest,FLOOR( DATEDIFF( CURDATE( ) ,  `DateOfBirth` ) /365 ) as Age,state,waiting,(SELECT config_description FROM sys_configuration WHERE config_value = noofchildren ) AS noofchildren,faith,childethnicity,childage,Adoptiontype,phonenumber,street_address,DescriptionMe,yardsize,noofbathrooms,noofbedrooms,housestyle,WEB_URL,Couple,About_our_home,NickName,address1,address2,city,zip,Status,childrenType FROM " . $tablename . " where ID = " . $logid . "";
$query = mysql_query($stringSQL);
$cmdtuples = mysql_num_rows($query);
$arrRows = array();


while (( $row = mysql_fetch_array($query, MYSQL_BOTH))) {

			$ProfileQ1 = mysql_query("SELECT template_file_path  FROM pdf_template_user WHERE user_id =$row[0] AND isDeleted = 'N' AND isDefault ='Y' LIMIT 1");
			$ProfileQ=mysql_fetch_array($ProfileQ1,MYSQL_BOTH);          
            $Profile_pth = (trim(str_replace(foo::getSiteUrl(), '/', $ProfileQ[template_file_path])) != '') ? trim(str_replace(foo::getSiteUrl(), '/', $ProfileQ[template_file_path])) : 'javascript:void(0)';

				$parsed = parse_url($Profile_pth);
				$path = $parsed['path'];
				$path_parts = explode('/', $path);
				if($path_parts[7]){
				$pdf_output = foo::getSiteUrl().'/'.$path_parts[5].'/'.$path_parts[6].'/'.$path_parts[7];
				}else{
				$pdf_output ='javascript:void(0)';	
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
        'profilefaith'=>(trim($row[15]))?$row[15]:'NA',
        'profileethinicity'=>($row[16])?str_replace(',', ', ', $row[16]):'NA',
        'profilechildage'=>(trim($row[17]))?$row[17]:'NA',
        'profileadoptiontype'=>(trim($row[18]))?$row[18]:'NA',
        'aboutHome'=>$row[13],
        'Pdf_link'=>$pdf_output,
        'Epub_link'=>$epubbook,
        'ebook_link' =>  $flipbook,
        'epup_link' =>  $epup
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
        'description' => $row[28]
    ));
}




//letter sort and display section ------------------------------------


//$mem_id = getMemberMembershipInfo($logid);
$stringSQL = " select couple from Profiles where id = '$logid'";
$result = mysql_query( $stringSQL );
$row = mysql_fetch_array( $result );
if ( $row['couple']>0 ) {
	$stringSQL = "SELECT p1.id,p1.DescriptionMe, p1.agency_letter, p1.letter_aboutThem, p1.DearBirthParent, p2.DescriptionMe aboutp2,p1.others,p1.FirstName fname, p2.FirstName coupleName
FROM Profiles p1, Profiles p2
WHERE p1.id = '$logid'
AND p1.couple = p2.id";
}
else {
	$stringSQL = "SELECT id,DescriptionMe, agency_letter, letter_aboutThem, DearBirthParent, '' as aboutp2,others,FirstName fname, '' as coupleName,img_him,img_agency,img_them,img_mother
FROM Profiles
WHERE id = '$logid'";
}
$query = mysql_query( $stringSQL );
$cmdtuples = 1;
$arrRowsL = array();

while ( $row = mysql_fetch_array( $query, MYSQL_BOTH ) ) {
	if($row['img_him']!=''){
		$dataimg = foo::getPic($row['img_him']);
	}
	if($row['img_agency']!=''){
		$dataimg_agency = foo::getPic($row['img_agency']);
	}
	if($row['img_them']!=''){
		$dataimg_them = foo::getPic($row['img_them']);
	}
	if($row['img_mother']!=''){
		$dataimg_mother = foo::getPic($row['img_mother']);
	}
	array_push( $arrRowsL, array(
	'data1'     =>  $row[1],
	'data1_him' =>  $dataimg,
	'data2'     =>  $row[2],
	'dataimg_agency'     =>  $dataimg_agency,
	'data3'     =>  $row[3],
	'data4'     =>  $row[4],
	'dataimg_mother'     =>  $dataimg_mother,
	'data5'     =>  $row[5],
	'dataimg_them'     =>  $dataimg_them,
	'data7'     =>  strtoupper($row[7]),
	'data8'     =>  strtoupper($row[8])
	//'membership_id' =>$mem_id['ID']
	));
}



//OTHER LETTER SECTION //
$stringSQL = "SELECT id,label,description,img FROM letter WHERE profile_id = '$logid'";
$arrValuesOther= array();
$query = mysql_query( $stringSQL );

while ( $row = mysql_fetch_array( $query, MYSQL_BOTH ) ) {
	array_push( $arrValuesOther, array(
	'id'     =>  $row['id'],
	'label'     =>  strtoupper($row['label']),
	'description'     =>  $row['description'],
	'img' =>  foo::getPic($row['img'])
	));
}



//OTHER LETTER SECTION //


$string_sql_Letterssort = "SELECT * FROM `letters_sort` WHERE profile_id=$logid ORDER BY order_by";
$query = mysql_query($string_sql_Letterssort);
$result_letters_sort = array();
while ($row = mysql_fetch_object($query)) {
	array_push($result_letters_sort, $row);
}

//letter sort and display section ends------------------------------------



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

  (!empty($arrValuesOther) || !empty($arrRowsL[0]['data4']))?$ClickText='Click on the links below to expand and read the letters.':$NoLetter='No letters to Display';


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
$loginId=foo::getLoggedId();
//$loginId=0; //getLoggedId();
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
            $filename = foo::getSiteUrl().'/modules/boonex/photos/data/files/' . $row['ID'] . "." . $row['Ext'];
            if ($filename) {
                $margin_left = 0;
                $margin_top = 0;
                list($width, $height) = getimagesize(foo::getSiteUrl()."/m/photos/get_image/file/".$row['Hash'].".".$row['Ext']); 
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
                $aData[] = foo::getSiteUrl()."/m/photos/get_image/file/{$row[Hash]}.{$row[Ext]}";
                $bData[] = foo::getSiteUrl()."/m/photos/get_image/thumb/{$row[Hash]}.{$row[Ext]}";
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
    //$sql = "SELECT * FROM `bx_photos_main` WHERE `Owner` = '$id' ";
    $result = mysql_query($sql);
    $nos = mysql_num_rows($result);
    if ($nos >= 1) {
        $count = 1;
        while ($row = mysql_fetch_array($result)) {
            $filename = foo::getSiteUrl().'/modules/boonex/photos/data/files/' . $row['ID'] . "." . $row['Ext'];
     
            if ($filename) {
                $margin_left = 0;
                $margin_top = 0;
                list($width, $height) = getimagesize(foo::getSiteUrl()."/m/photos/get_image/file/".$row['Hash'].".".$row['Ext']); 
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
                $aData[] = foo::getSiteUrl()."/m/photos/get_image/file/" . $row[Hash] . "." . $row[Ext];
                $bData[] = foo::getSiteUrl()."/m/photos/get_image/thumb/" . $row[Hash] . "." . $row[Ext];
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
				<div><a href='foo::getSiteUrl()/flash/modules/video/files/108.flv' style='display:block;width:560px;height:345px' id='player1'></a> 
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

    $sql1 = "SELECT ID FROM `sys_albums` WHERE `Owner` = '$logged' and `Type` = 'bx_videos' " . $sql_condition_home;
    $result1 = mysql_query($sql1);

    $albumid = "";
    while ($row1 = mysql_fetch_array($result1)) {
        $albumid .= ' ' . $row1['ID'] . ',';
    }

    $albumid = substr($albumid, 0, -1);
    $sql = "SELECT ph.* FROM RayVideoFiles as ph,sys_albums_objects sab WHERE ph.ID=sab.id_object AND sab.id_album in ($albumid) AND ph.Status = 'approved'";
    $result = mysql_query($sql);
    $nos = mysql_num_rows($result);
    if ($nos >= 1) {
        $i = 1;
        $aData = array();
        $youtubeCode = array();
        while ($row = mysql_fetch_array($result)) {
            if ($row['Source'] == 'youtube' && $nos >= 1) {

            } elseif ($row['Source'] != 'youtube' && $nos >= 1) {
                $videoURL = '';
                if ($row['YoutubeLink'] == '1') {
                    $videoURL = 'https://www.youtube.com/watch?v=' . $row['Uri'];
                    $youtubeCode[$row[ID]] = $row['Uri'];
                } else {
                    if(file_exists('../flash/modules/video/files/_'.$row[ID].'.flv'))
                        $videoURL = 'http://'.$_SERVER[HTTP_HOST].'/flash/modules/video/files/_'.$row[ID].'.flv';
                    else if(file_exists('../flash/modules/video/files/_'.$row[ID].'.mp4'))
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
        echo json_encode(array(
            'status' => 'error',
            'message' => 'No uploaded videos'
        ));
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

    $sql1 = "SELECT ID FROM `sys_albums` WHERE `Owner` = '$logged' and Caption = 'Home Videos' and `Type` = 'bx_videos' " . $sql_condition_home;
    $result1 = mysql_query($sql1);

    $albumid = "";
    while ($row1 = mysql_fetch_array($result1)) {
        $albumid .= ' ' . $row1['ID'] . ',';
    }

    $albumid = substr($albumid, 0, -1);
    $sql = "SELECT ph.* FROM RayVideoFiles as ph,sys_albums_objects sab WHERE ph.ID=sab.id_object AND sab.id_album in ($albumid) AND ph.Status = 'approved'";
    $result = mysql_query($sql);
    $nos = mysql_num_rows($result);
    if ($nos >= 1) {
        $i = 1;
        $aData = array();
        $youtubeCode = array();
        while ($row = mysql_fetch_array($result)) {
            if ($row['Source'] == 'youtube' && $nos >= 1) {

            } elseif ($row['Source'] != 'youtube' && $nos >= 1) {
                $videoURL = '';
                if ($row['YoutubeLink'] == '1') {
                    $videoURL = 'https://www.youtube.com/watch?v=' . $row['Uri'];
                    $youtubeCode[$row[ID]] = $row['Uri'];
                } else {
                    if(file_exists('../flash/modules/video/files/_'.$row[ID].'.flv'))
                        $videoURL = 'http://'.$_SERVER[HTTP_HOST].'/flash/modules/video/files/_'.$row[ID].'.flv';
                    else if(file_exists('../flash/modules/video/files/_'.$row[ID].'.mp4'))
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
        echo json_encode(array(
            'status' => 'error',
            'message' => 'No uploaded videos'
        ));
    }
}

function getProfileInfo($iProfileID = 0, $checkActiveStatus = false, $forceCache = false)
{
    global $aUser;
    $llid=foo::getLoggedId();
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
$logid = foo::getLoggedId();
//$profileDetails = getProfileInfo($logid);
$profileDetails = foo::getProfileInfo($logid);
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
$BM_id = foo::getLoggedId();
$profileDet = foo::getProfileInfo($user_id);
//$profileDet = getProfileInfo($user_id);


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


$logid = foo::getLoggedId();
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
/*$arrRows_religion = array(array('id'=>'Anglican','data'=>'Anglican'),
			        array('id'=>'Bahai','data'=>'Bahail'),
			        array('id'=>'Baptist','data'=>'Baptist'),
			        array('id'=>'Buddhist','data'=>'Buddhist'),
			        array('id'=>'Catholic','data'=>'Catholic'),
			        array('id'=>'Christian','data'=>'Christian'),
			        array('id'=>'Church of Christ','data'=>'Church of Christ'),			        
			        array('id'=>'Episcopal','data'=>'Episcopal'),
			        array('id'=>'Hindu','data'=>'Hindu'),
			        array('id'=>'Jewish','data'=>'Jewish'),
			        array('id'=>'Lutheran','data'=>'Lutheran'),
			        array('id'=>'Methodist','data'=>'Methodist'),
			        array('id'=>'Non-denominational','data'=>'Non-denominational'),
			        array('id'=>'None','data'=>'None'),
			        array('id'=>'Other','data'=>'Other'),
			        array('id'=>'Presbyterian','data'=>'Presbyterian'),
			        array('id'=>'Protestant','data'=>'Protestant'),
			        array('id'=>'Unitarian','data'=>'Unitarian'));*/


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
//print_r($arrRows_Region);
/*$arrRows_regiona = array(array('id'=>'Non US','data'=>'Non US'),
		          array('id'=>'North-central','data'=>'North-central'),
		          array('id'=>'Northeast','data'=>'Northeast'),
		          array('id'=>'Northwest','data'=>'Northwest'),
		          array('id'=>'South-central','data'=>'South-central'),
		          array('id'=>'Southeast','data'=>'Southeast'),
		          array('id'=>'Southwest','data'=>'Southwest'));
print_r($arrRows_Region);*/
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
	foo::contactPage();
}


function MorePhotos(){
	foo::contactPage();
}



function getPic2($picID){
	$img = json_decode($picID);
	$img = implode(',', $img);
	
	$q2 = "SELECT CONCAT(Hash,'.', Ext) as img, Uri FROM `bx_photos_main` WHERE ID IN($img);";
	$r2 = mysql_query($q2);
	
	while($rw = mysql_fetch_array($r2)){

		//$path = "http://www.parentfinder.com//modules//boonex//photos//data//files//".$rw['img'];
		$path ='<img src=http://www.parentfinder.com/m/photos/get_image/thumb/'.$rw['img'].' />';	
		//if(getimagesize($path) !== false){
			$img2[] = $path;
			
		//}
		
	}
	$ret = json_encode($img2);
	
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
