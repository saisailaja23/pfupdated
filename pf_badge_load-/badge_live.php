<?php
error_reporting(E_ALL);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');


require_once '../inc/profiles.inc.php';
global $site;

$tid=$_REQUEST['tid'];
$uid=$_REQUEST['uid'];
$id=$_POST['id']; 
$name=$_POST['name']; 

$logid =1;
//$logid = getLoggedId();

$from = ($_REQUEST['posStart'] == '') ? 0 : $_REQUEST['posStart'];

$from = test_input($from);


$from = 0;


$count = ($_REQUEST['count'] == '') ? 6 : $_REQUEST['count'];
$count = test_input($count);

$count = 7; 

$loadFrom = ($_REQUEST['loadFrom'] == '') ? '' : $_REQUEST['loadFrom'];
$loadFrom = test_input($loadFrom);

//$agencyId = ($_REQUEST['agencyId'] == '' || $_REQUEST['agencyId'] == 'undefined') ? 0 : $_REQUEST['agencyId'];
$agencyId =$uid;
$agencyId = test_input($agencyId);


//echo $_REQUEST['posStart'];
$recCount = $from;


if ($loadFrom == 'badge' || ($agencyId)) {
	$sql_getAgencyFilter = 'AND `AdoptionAgency` = ' . $agencyId;
} else {
	$sql_getAgencyFilter = '';
}
if (trim($_REQUEST['pid']) != '') {
	$sql_getAgencyFilter = $sql_getAgencyFilter . " AND `Profiles`. `ID` not in (" . $_REQUEST['pid'] . ")";
}


//$searchvalues = $_REQUEST['sortvalue'];
$sortvalue='random';
//$searchtype = $_REQUEST['type'];
$searchtype ='Sortby';

$searchFilter = ($_REQUEST['searchFilter'] && $_REQUEST['searchFilter'] != 'undefined') ? $_REQUEST['searchFilter'] : '';

if (trim($searchFilter)) {
	$sql_getAgencyFilter .= ' AND (`Profiles`.`ID` in (select p1.ID from Profiles as p1 left join Profiles as p2  on p1.ID = p2.Couple where (p1.FirstName LIKE "%' . $searchFilter . '%" or  p2.FirstName LIKE "%' . $searchFilter . '%") or (p1.FirstName LIKE "%' . $searchFilter . '%" and p1.Status = \'Active\' and p1.Couple = 0) and p1.Status = \'Active\')) ';
}

$searchvalues = $_REQUEST['sortvalue'];
$searchvalues = test_input($searchvalues);

//$searchtype = $_REQUEST['type'];
$searchtype ='Sortby';
$searchtype = test_input($searchtype);

//echo $searchvalues.$searchtype;

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
$searchtype; 

switch ($searchtype) {
	case 'Region':
		$AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE `Profiles`.`Status` = 'Active' and Profiles.Region ='$searchvalues' and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'   AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter limit $from , $count";
		if ($from == 0) {
			$totalCount = "select count(`Profiles`. `ID`) FROM `Profiles` WHERE `Profiles`.`Status` = 'Active' and Profiles.Region ='$searchvalues' and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'   AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter";
			$total_count = mysql_query($totalCount);
			$rowCount = mysql_fetch_array($total_count);
			$tCount = $rowCount[0];
		}
		break;
	case 'Religion':
		$AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and (Profiles.Religion LIKE '%$searchvalues%' OR Profiles.faith LIKE '%$searchvalues%') and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'   AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter limit $from , $count";
		if ($from == 0) {
			$totalCount = "select count(`Profiles`. `ID`) FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and (Profiles.Religion LIKE '%$searchvalues%' OR Profiles.faith LIKE '%$searchvalues%') and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'   AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter";
			$total_count = mysql_query($totalCount);
			$rowCount = mysql_fetch_array($total_count);
			$tCount = $rowCount[0];
		}
		break;
	case 'Familysize':
		$searchOption = ((int) $searchvalues < 6) ? "Profiles.noofchildren=$searchvalues" : "Profiles.noofchildren >= $searchvalues";
		$AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and ($searchOption) and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter limit $from , $count";
		if ($from == 0) {
			$totalCount = "select count(`Profiles`. `ID`) FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and ($searchOption and Profiles.noofchildren is not null and Profiles.noofchildren <> '') and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter";
			$total_count = mysql_query($totalCount);
			$rowCount = mysql_fetch_array($total_count);
			$tCount = $rowCount[0];
		}
		break;
	case 'Sortby':
		switch ($searchvalues) {
		case 'oldFirst':
				$orderBy = 'Profiles.DateReg ASC';
				break;
		case 'random':
				$orderBy = 'RAND()';
				break;
		case 'FirstName':
				$orderBy = 'Profiles.FirstName ASC';
				break;
		default:
				$orderBy = 'Profiles.DateReg DESC';
				break;
		}
		$AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active'  and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter ORDER BY $orderBy limit $from , $count";
		if ($from == 0) {
			$totalCount = "select count(`Profiles`. `ID`)  FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active'  and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter ORDER BY $orderBy";
			$total_count = mysql_query($totalCount);
			$rowCount = mysql_fetch_array($total_count);
			$tCount = $rowCount[0];
		}
		break;
	case 'ethnicity':
		if ($from == 0) {
			$totalCount = "select count(`Profiles`. `ID`) FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and FIND_IN_SET( '$searchvalues' ,`ChildEthnicity` ) and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter";
			$total_count = mysql_query($totalCount);
			$rowCount = mysql_fetch_array($total_count);
			$tCount = $rowCount[0];
		}
		$AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and (FIND_IN_SET( '$searchvalues' ,`ChildEthnicity`) or FIND_IN_SET( 'Any' ,`ChildEthnicity`)) and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter limit $from , $count";
		break;
	case 'State':
		if ($from == 0) {
			$totalCount = "select count(`Profiles`. `ID`) FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and FIND_IN_SET( '$searchvalues' ,`state` ) and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter";
			$total_count = mysql_query($totalCount);
			$rowCount = mysql_fetch_array($total_count);
			$tCount = $rowCount[0];
		}
		$AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and FIND_IN_SET( '$searchvalues' ,`state` ) and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter limit $from , $count";
		break;
	default:
		if ($from == 0) {
			$totalCount = "select count(`Profiles`. `ID`)  FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0' AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter ORDER BY `Profiles`.`DateReg` DESC";
			$total_count = mysql_query($totalCount);
			$rowCount = mysql_fetch_array($total_count);
			$tCount = $rowCount[0];
		}
		// $AgencyLike_List = "SELECT `Profiles`. `ID`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0' AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter ORDER BY `Profiles`.`DateReg` , RAND() DESC limit $from , $count";
		$AgencyLike_List = "SELECT `Profiles`. `ID`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0' AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter ORDER BY RAND() limit $from , $count";
		break;
}


$agency_query = mysql_query($AgencyLike_List);
 $cmdtuples = mysql_num_rows($agency_query); 
if ($cmdtuples == 7) {
	$needLoad = 1;
} else {
	$needLoad = 0;
}
$arrColumns = explode(",", $columns);
$arrRows_agency_list1 = array();
header("content-type: application/json");

$count1 = 0;
$xml = array();


while (($row = mysql_fetch_array($agency_query, MYSQL_BOTH))) {

	$agency_id = $row['ID'];
	//$agency_id = 52;

//$Agencydetails = mysql_query("select bx_groups_main.author_id,bx_groups_main.title,Profiles.Country,Profiles.City,Profiles.WEB_URL,bx_groups_main.thumb from Profiles,bx_groups_main where Profiles.ID = " . $agency_id . " and  Profiles.ID =author_id");
	//$columns = '';
	$Agencydetails = mysql_query("SELECT  ID,FirstName,DateOfBirth,State,waiting,noofchildren,faith,ChildEthnicity,ChildAge,Adoptiontype,Avatar,NickName,Occupation,Religion,Education,Ethnicity,Couple from Profiles where ID = " . $agency_id . "");
	$gridData = array();
	//echo "SELECT  ID,FirstName,DateOfBirth,State,waiting,noofchildren,faith,ChildEthnicity,ChildAge,Adoptiontype,Avatar,NickName,Occupation,Religion,Education,Ethnicity,Couple from Profiles where ID = " . $agency_id . ""; exit;
	while (($row_agency = mysql_fetch_array($Agencydetails, MYSQL_BOTH))) {
		$first_age = '';
		if ($row_agency['DateOfBirth'] != '0000-00-00') {
			$first_age = date("Y") - $row_agency['DateOfBirth'] . substr(0, 4);
		}

		$gridData[$row_agency['ID']]['DateOfBirth'] = $first_age ? $first_age : "N/A";
		$gridData[$row_agency['ID']]['Education'] = $row_agency['Education'] ? $row_agency['Education'] : "N/A";
		$gridData[$row_agency['ID']]['Occupation'] = $row_agency['Occupation'] ? $row_agency['Occupation'] : "N/A";
		$gridData[$row_agency['ID']]['Ethnicity'] = $row_agency['Ethnicity'] ? $row_agency['Ethnicity'] : "N/A";
		$gridData[$row_agency['ID']]['Religion'] = $row_agency['Religion'] ? $row_agency['Religion'] : "N/A";
		$gridData[$row_agency['ID']]['FirstName'] = $row_agency['FirstName'] ? $row_agency['FirstName'] : "N/A";

		if ($row_agency['Couple'] != 0 && $row_agency['Couple'] != '') {
			$Agencydetails_Couple = mysql_query("SELECT  ID,FirstName,DateOfBirth,State,waiting,noofchildren,faith,ChildEthnicity,ChildAge,Adoptiontype,Avatar,NickName,Occupation,Religion,Education,Ethnicity,Couple from Profiles where ID = " . $row_agency['Couple'] . "");
			while (($row_agency_couple = mysql_fetch_array($Agencydetails_Couple, MYSQL_BOTH))) {
				$second_age = '';
				if ($row_agency_couple['DateOfBirth'] != '0000-00-00') {
					$second_age = date("Y") - $row_agency_couple['DateOfBirth'] . substr(0, 4);
				}

				$gridData[$row_agency_couple['ID']]['DateOfBirth'] = $second_age ? $second_age : "N/A";
				$gridData[$row_agency_couple['ID']]['Education'] = $row_agency_couple['Education'] ? $row_agency_couple['Education'] : "N/A";
				$gridData[$row_agency_couple['ID']]['Occupation'] = $row_agency_couple['Occupation'] ? $row_agency_couple['Occupation'] : "N/A";
				$gridData[$row_agency_couple['ID']]['Ethnicity'] = $row_agency_couple['Ethnicity'] ? $row_agency_couple['Ethnicity'] : "N/A";
				$gridData[$row_agency_couple['ID']]['Religion'] = $row_agency_couple['Religion'] ? $row_agency_couple['Religion'] : "N/A";
				$gridData[$row_agency_couple['ID']]['FirstName'] = $row_agency_couple['FirstName'] ? $row_agency_couple['FirstName'] : "N/A";
			}
		}
        

		$sImage = $site['url'].'modules/boonex/avatar/data/favourite/' . $row_agency[10] . '.jpg';

//        $photo = '<img style="width:' . $width . 'px; height:' . $height . 'px;margin-left:' . $margin_left . 'px;margin-top:' . $margin_top . 'px; background-color: #EDEDED;" src="' . $sImage . '">';
		$photo = $sImage;
		$Couplename = db_arr("SELECT `FirstName`,DateOfBirth FROM `Profiles` WHERE `Couple` = '$agency_id' LIMIT 1");
		$Couple_name = $Couplename[FirstName];
		$Couple_age = $Couplename[DateOfBirth];

		if ($row_agency[4] != '') {
//  $waiting =  $row_agency[4].' years';
			$waiting = $row_agency[4];
		} else {
			$waiting = 'N/A';
		}
		if ($Couple_name != '') {
			$Fname = $row_agency[1];
			//$Couple_names =   str_replace('&', '&amp;', $Couple_name);

			$profilename = $Fname . ' and ' . $Couple_name;
		} else {
			$Fnames = $row_agency[1];
			$profilename = $Fnames;
		}
		if ($Couple_age != '') {
			//echo "row_agency[2]" . $row_agency[2];
			if ($row_agency[2] != '0000-00-00') {
				$first = date("Y") - $row_agency[2] . substr(0, 4);
			}
			if ($Couple_age != '0000-00-00') {
				$second = date("Y") - $Couple_age . substr(0, 4);
			}
			$profileages = $first . '/' . $second;
			if ($row_agency[2] == '0000-00-00' || $Couple_age == '0000-00-00') {
				$profileages = "N/A";
			}

		} else {
			if ($row_agency[2] != '0000-00-00') {
				$firstsingle = date("Y") - $row_agency[2] . substr(0, 4);
			}
			$profileages = $firstsingle;
		}

		$profileages = ($profileages) ? $profileages : "N/A";
		$childethnicity = str_replace(',', ', ', $row_agency[7]);
		$faiths = str_replace(',', ', ', $row_agency[6]);
		$Childages = str_replace(',', ', ', $row_agency[8]);

		$state = (trim($row_agency[3])) ? $row_agency[3] : "N/A";

		$children = (trim($row_agency[5])) ? $row_agency[5] : "No Children";

		$adoptiontype = (trim($row_agency[9])) ? $row_agency[9] : "N/A";

		$arrValues = array();

		$childethnicity_ary = explode(",", $childethnicity);
		$childethnicity_sub = array_slice($childethnicity_ary, 0, 4);
		$childethnicity = sizeof($childethnicity_ary) >= 4 ? implode(", ", $childethnicity_sub) . ' <a class = \'toolhelp\' title-text= "' . str_replace(',', ', ', $row_agency[7]) . '"  href="javascript:void(0)" ><span class="dialog" data-toggle="modal" href="#more">....More</span><span class="nodialog">....More</span></a>' : implode(", ", $childethnicity_ary);
//        $childethnicity = htmlentities($childethnicity);
		$childethnicity = ($childethnicity) ? $childethnicity : "N/A";

		$faiths_ary = explode(",", $faiths);
		$faiths_sub = array_slice($faiths_ary, 0, 4);
		$faiths = sizeof($faiths_ary) >= 4 ? implode(", ", $faiths_sub) . ' <a class = \'toolhelp\' title-text= "' . str_replace(',', ', ', $row_agency[6]) . '"  href="javascript:void(0)" ><span class="dialog" data-toggle="modal" href="#more">....More</span><span class="nodialog">....More</span></a>' : implode(", ", $faiths_ary);
//        $faiths = htmlentities($faiths);
		$faiths = ($faiths) ? $faiths : "N/A";

		$Childages_ary = explode(",", $Childages);
		$Childages_sub = array_slice($Childages_ary, 0, 4);
		$Childages = sizeof($Childages_ary) >= 4 ? implode(", ", $Childages_sub) . ' <a class = \'toolhelp\' title-text= "' . str_replace(',', ', ', $row_agency[8]) . '"  href="javascript:void(0)" ><span class="dialog" data-toggle="modal" href="#more">....More</span><span class="nodialog">....More</span></a>' : implode(", ", $Childages_ary);
//        $Childages = htmlentities($Childages);
		$Childages = ($Childages) ? $Childages : "N/A";


		$ProfileQ = db_arr("SELECT template_file_path  FROM pdf_template_user WHERE user_id =$row_agency[0] AND isDeleted = 'N' AND isDefault ='Y' LIMIT 1");
		$Profile_pth = (trim(str_replace('/var/www/html/pf/', '/', $ProfileQ[template_file_path])) != '') ? trim(str_replace('/var/www/html/pf/', '/', $ProfileQ[template_file_path])) : 'javascript:void(0)';
		if (strlen($profilename) >= 25) {
			$profilename_string = '<div class = \'toolhelp\' title-text= "' . $profilename . '" ><span>' . substr($profilename, 0, 22) . '...</span></div>';
		} else {

			$profilename_string = $profilename;
		}

		$E_pub = db_arr("SELECT content FROM `aqb_pc_members_blocks` WHERE `owner_id` = '$row_agency[0]' and title = 'E-PUB Profile'");
		$E_pub_link = $E_pub[content];
		$epub_name_arr = explode('.', $E_pub_link);
		if (strtolower(end($epub_name_arr)) == 'epub') {
			$epubbook = $E_pub_link;
		} else {
			$epubbook = false;
		}

		$E_book = db_arr("SELECT content FROM `aqb_pc_members_blocks` WHERE `owner_id` = '$row_agency[0]' and title = 'E-book Profile'");
		$E_book_link = $E_book[content];

		$start = strpos($E_book_link, "<a href=") + 8;
		$end = strpos($E_book_link, "target= _blank") - $start;
		$flipbook = substr($E_book_link, $start, $end);
		$flipbook_mob_link = false;
		if ($flipbook != false) {
			$flipbook_mob = split('/', $flipbook);
			$flipbook_mob_link = $flipbook_mob[0] . "/" . $flipbook_mob[1] . "/mobile/index.html";

			$flipbook_mob_filename = $dir['root'] . $flipbook_mob_link;
			if (!(file_exists($flipbook_mob_filename))) {
				$flipbook_mob_link = false;
			}
		}


		++$count;

			  $id =       $row_agency[0];
              $image =    $photo;
	          $name =     $profilename;
              $age =      $profileages;
              $state =    $state;
              $children = $children;
              $faith =    $faiths;
              $ethncity = $childethnicity;
              $adp_type = $adoptiontype;

	
             $html="$name";
             $json[]=array('Image'=>$image ,'Title'=>$name ,'Id'=>$id);


		$recCount++;
		++$count1;
	}
}

if ($needLoad == 1) {
	$xml['count'] = $count1 - 1;
} else {
	$xml['count'] = $count1;
}

$xml['needLoad'] = $needLoad;

$obj=array('objects'=>$json,'title'=>'Syam Nath');
echo json_encode($obj);




function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
?>
