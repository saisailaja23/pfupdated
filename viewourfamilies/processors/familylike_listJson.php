<?php

/* * *******************************************************************************
 * Name:    Prashanth A
 * Date:    12/12/2013
 * Purpose: Listing the families liked by birth mother
 * ******************************************************************************* */
require_once ('../../inc/header.inc.php');
//require_once ('../../inc/profiles.inc.php');
//require_once ('../../inc/classes/BxDolTemplate.php');

//$logid = getLoggedId();
$from = ($_REQUEST['posStart'] == '') ? 0 : $_REQUEST['posStart'];
$from = test_input($from);

$from = 0;

$count = ($_REQUEST['count'] == '') ? 6 : $_REQUEST['count'];
$count = test_input($count);

$count = 7;

$loadFrom = ($_REQUEST['loadFrom'] == '') ? '' : $_REQUEST['loadFrom'];
$loadFrom = test_input($loadFrom);

$agencyId = ($_REQUEST['agencyId'] == '' || $_REQUEST['agencyId'] == 'undefined') ? 0 : $_REQUEST['agencyId'];
$agencyId = test_input($agencyId);

//echo $_REQUEST['posStart'];
$recCount = $from;

//$log123 = fopen('searchFamiliesQueries' . date('Y-m-d') . '.html', 'a+');
//fwrite($log123, '<br><br>Passed Parameters :- <br><pre>');
//fwrite($log123, print_r($_REQUEST, true));

if ($loadFrom == 'badge' || ($agencyId)) {
    $sql_getAgencyFilter = 'AND `AdoptionAgency` = ' . $agencyId;
} else {
    $sql_getAgencyFilter = '';
}

if($agencyId != 47)
    $avatarFilter = "and `Profiles`.`Avatar` != '0'";
else
    $avatarFilter = '';

if (trim($_REQUEST['pid']) != '') {
    $sql_getAgencyFilter = $sql_getAgencyFilter . " AND `Profiles`. `ID` not in (" . $_REQUEST['pid'] . ")";
}

$sql_getAgencyFilter = $sql_getAgencyFilter . " AND `Profiles`.`publishStatus` = 1";

$searchvalues = $_REQUEST['sortvalue'];
$searchtype = $_REQUEST['type'];

$searchFilter = ($_REQUEST['searchFilter'] && $_REQUEST['searchFilter'] != 'undefined') ? $_REQUEST['searchFilter'] : '';

if (trim($searchFilter)) {
    $sql_getAgencyFilter .= ' AND (`Profiles`.`ID` in (select p1.ID from Profiles as p1 left join Profiles as p2  on p1.ID = p2.Couple where (p1.FirstName LIKE "%' . $searchFilter . '%" or  p2.FirstName LIKE "%' . $searchFilter . '%") or (p1.FirstName LIKE "%' . $searchFilter . '%" and p1.Status = \'Active\' and p1.Couple = 0) and p1.Status = \'Active\')) ';
}

$searchvalues = $_REQUEST['sortvalue'];
$searchvalues = test_input($searchvalues);

$searchtype = $_REQUEST['type'];
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
// '".$searchvalues."'
//if($searchvalues != '') {
//
//  echo $AgencyLike_List = "SELECT `Profiles`. `ID`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles`,sys_acl_levels_members WHERE sys_acl_levels_members.IDLevel IN('14','15','18','20','24','23') AND `Profiles`.`Status` = 'Active' and `Profiles`.`ProfileType` = 2 AND sys_acl_levels_members.IDMember = `Profiles`. `ID` AND (DateExpires >= NOW() OR DateExpires IS NULL) AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) Group By sys_acl_levels_members.IDMember ORDER BY '".$searchvaluess."' DESC";
//if($searchvalues == 'sortby') {
// $AgencyLike_List = "SELECT `Profiles`. `ID`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles`,sys_acl_levels_members WHERE sys_acl_levels_members.IDLevel IN('14','15','18','20','24','23') AND `Profiles`.`Status` = 'Active' and `Profiles`.`ProfileType` = 2 AND sys_acl_levels_members.IDMember = `Profiles`. `ID` AND (DateExpires >= NOW() OR DateExpires IS NULL) AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) Group By sys_acl_levels_members.IDMember ORDER BY `Profiles`.`DateLastLogin` DESC";
//}
// else {

switch ($searchtype) {
    case 'Region':
        $AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE `Profiles`.`Status` = 'Active' and Profiles.Region ='$searchvalues' and `Profiles`.`ProfileType` = 2 $avatarFilter AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter limit $from , $count";
        if ($from == 0) {
            $totalCount = "select count(`Profiles`. `ID`) FROM `Profiles` WHERE `Profiles`.`Status` = 'Active' and Profiles.Region ='$searchvalues' and `Profiles`.`ProfileType` = 2 $avatarFilter AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter";
            $total_count = mysql_query($totalCount);
            $rowCount = mysql_fetch_array($total_count);
            $tCount = $rowCount[0];
        }
        break;
    case 'Religion':
        $AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and (Profiles.Religion LIKE '%$searchvalues%' OR Profiles.faith LIKE '%$searchvalues%') and `Profiles`.`ProfileType` = 2 $avatarFilter  AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter limit $from , $count";
        if ($from == 0) {
            $totalCount = "select count(`Profiles`. `ID`) FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and (Profiles.Religion LIKE '%$searchvalues%' OR Profiles.faith LIKE '%$searchvalues%') and `Profiles`.`ProfileType` = 2 $avatarFilter AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter";
            $total_count = mysql_query($totalCount);
            $rowCount = mysql_fetch_array($total_count);
            $tCount = $rowCount[0];
        }
        break;
    case 'Familysize':
        $searchOption = ((int) $searchvalues < 6) ? "Profiles.noofchildren=$searchvalues" : "Profiles.noofchildren >= $searchvalues";
        $AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and ($searchOption) and `Profiles`.`ProfileType` = 2 $avatarFilter AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter limit $from , $count";
        if ($from == 0) {
            $totalCount = "select count(`Profiles`. `ID`) FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and ($searchOption and Profiles.noofchildren is not null and Profiles.noofchildren <> '') and `Profiles`.`ProfileType` = 2 $avatarFilter AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter";
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
                if($agencyId == 220){
                   $orderBy = 'Profiles.DateReg ASC'; 
                }
                else{
                    $orderBy = 'RAND()';
                }
                break;
            case 'FirstName':
                $orderBy = 'Profiles.FirstName ASC';
                break;
            default:
                $orderBy = 'Profiles.DateReg DESC';
                break;
        }
        $AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active'  and `Profiles`.`ProfileType` = 2 $avatarFilter AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter ORDER BY $orderBy limit $from , $count";
        if ($from == 0) {
            $totalCount = "select count(`Profiles`. `ID`)  FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active'  and `Profiles`.`ProfileType` = 2 $avatarFilter AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter ORDER BY $orderBy";
            $total_count = mysql_query($totalCount);
            $rowCount = mysql_fetch_array($total_count);
            $tCount = $rowCount[0];
        }
        break;
    case 'ethnicity':
        if ($from == 0) {
            $totalCount = "select count(`Profiles`. `ID`) FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and FIND_IN_SET( '$searchvalues' ,`ChildEthnicity` ) and `Profiles`.`ProfileType` = 2 $avatarFilter AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter";
            $total_count = mysql_query($totalCount);
            $rowCount = mysql_fetch_array($total_count);
            $tCount = $rowCount[0];
        }
        $AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and (FIND_IN_SET( '$searchvalues' ,`ChildEthnicity`) or FIND_IN_SET( 'Any' ,`ChildEthnicity`)) and `Profiles`.`ProfileType` = 2 $avatarFilter AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter limit $from , $count";
        break;
    case 'State':
        if ($from == 0) {
            $totalCount = "select count(`Profiles`. `ID`) FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and FIND_IN_SET( '$searchvalues' ,`state` ) and `Profiles`.`ProfileType` = 2 $avatarFilter AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter";
            $total_count = mysql_query($totalCount);
            $rowCount = mysql_fetch_array($total_count);
            $tCount = $rowCount[0];
        }
        $AgencyLike_List = "SELECT `Profiles`. `ID`,`Profiles`. `Region`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and FIND_IN_SET( '$searchvalues' ,`state` ) and `Profiles`.`ProfileType` = 2 $avatarFilter AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter limit $from , $count";
        break;
    default:
        if ($from == 0) {
            $totalCount = "select count(`Profiles`. `ID`)  FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and `Profiles`.`ProfileType` = 2 $avatarFilter AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter ORDER BY `Profiles`.`DateReg` DESC";
            $total_count = mysql_query($totalCount);
            $rowCount = mysql_fetch_array($total_count);
            $tCount = $rowCount[0];
        }
        // $AgencyLike_List = "SELECT `Profiles`. `ID`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0' AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter ORDER BY `Profiles`.`DateReg` , RAND() DESC limit $from , $count";
        $AgencyLike_List = "SELECT `Profiles`. `ID`, `Profiles`. `NickName`, `Profiles`. `Couple`, `Profiles`. `Sex`, if(`DateLastNav` > SUBDATE(NOW(), INTERVAL 30 MINUTE ), 1, 0) AS `is_online`,Avatar FROM `Profiles` WHERE  `Profiles`.`Status` = 'Active' and `Profiles`.`ProfileType` = 2 $avatarFilter AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) $sql_getAgencyFilter ORDER BY RAND() limit $from , $count";
        break;
}
//fwrite($log123, '<br><br> SQL statement :- <br>');
//fwrite($log123, $AgencyLike_List);
//fclose($log123);

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
//$xml = '<?xml version="1.0" encoding="UTF-8" ?/>';
//if ($from > 0) {
//    $xml .= '<data pos="' . $from . '">';
//} else {
//    $xml .= '<data total_count="' . $tCount . '">';
//}$count = $from;
$count1 = 0;
$xml = array();
while (($row = mysql_fetch_array($agency_query, MYSQL_BOTH))) {
    $agency_id = $row['ID'];
    
//$Agencydetails = mysql_query("select bx_groups_main.author_id,bx_groups_main.title,Profiles.Country,Profiles.City,Profiles.WEB_URL,bx_groups_main.thumb from Profiles,bx_groups_main where Profiles.ID = " . $agency_id . " and  Profiles.ID =author_id");
//$columns = '';
	$Agencydetails = db_res("SELECT  ID,FirstName,DateOfBirth,State,waiting,noofchildren,faith,
                                    ChildEthnicity,ChildAge,Adoptiontype,Avatar,NickName,Occupation,
                                    Religion,Education,Ethnicity,Couple,
                                    (SELECT LKey
                                    FROM sys_pre_values
                                    WHERE  `Key` =  'Country'
                                    AND Value = (
                                    SELECT Country
                                    FROM Profiles
                                    WHERE ID = " . $agency_id . ")
                                    ) AS country from Profiles where ID = " . $agency_id . "");
    $gridData = array();
    while (($row_agency = mysql_fetch_array($Agencydetails, MYSQL_BOTH))) {
        $first_age = '';
        if ($row_agency['DateOfBirth'] != '0000-00-00') {
			$first_age = age($row_agency['DateOfBirth']);
		}

		$gridData[$row_agency['ID']]['DateOfBirth'] = $first_age ? $first_age : "N/A";
        $gridData[$row_agency['ID']]['Education'] = $row_agency['Education'] ? $row_agency['Education'] : "N/A";
        $gridData[$row_agency['ID']]['Occupation'] = $row_agency['Occupation'] ? $row_agency['Occupation'] : "N/A";
        $gridData[$row_agency['ID']]['Ethnicity'] = $row_agency['Ethnicity'] ? $row_agency['Ethnicity'] : "N/A";
        $gridData[$row_agency['ID']]['Religion'] = $row_agency['Religion'] ? $row_agency['Religion'] : "N/A";
        $gridData[$row_agency['ID']]['FirstName'] = $row_agency['FirstName'] ? $row_agency['FirstName'] : "N/A";

        if($row_agency['Couple'] != 0 && $row_agency['Couple'] != '') {
            $Agencydetails_Couple = db_res("SELECT  ID,FirstName,DateOfBirth,State,waiting,noofchildren,faith,ChildEthnicity,ChildAge,Adoptiontype,Avatar,NickName,Occupation,Religion,Education,Ethnicity,Couple from Profiles where ID = " . $row_agency['Couple'] . "");
            while (($row_agency_couple = mysql_fetch_array($Agencydetails_Couple, MYSQL_BOTH))) {
                $second_age = '';
                if ($row_agency_couple['DateOfBirth'] != '0000-00-00') {
                    $second_age = age($row_agency_couple['DateOfBirth']);
				}
				$gridData[$row_agency_couple['ID']]['DateOfBirth'] = $second_age ? $second_age : "N/A";
                $gridData[$row_agency_couple['ID']]['Education'] = $row_agency_couple['Education'] ? $row_agency_couple['Education'] : "N/A";
                $gridData[$row_agency_couple['ID']]['Occupation'] = $row_agency_couple['Occupation'] ? $row_agency_couple['Occupation'] : "N/A";
                $gridData[$row_agency_couple['ID']]['Ethnicity'] = $row_agency_couple['Ethnicity'] ? $row_agency_couple['Ethnicity'] : "N/A";
                $gridData[$row_agency_couple['ID']]['Religion'] = $row_agency_couple['Religion'] ? $row_agency_couple['Religion'] : "N/A";
                $gridData[$row_agency_couple['ID']]['FirstName'] = $row_agency_couple['FirstName'] ? $row_agency_couple['FirstName'] : "N/A";
            }
        }

        //   avatarimages($row_agency[0]);
//        echo file_exists('../../modules/boonex/avatar/data/favourite/'. $row_agency[10] . '.jpg').'__'.file_exists('../../templates/tmpl_par/images/NO-PHOTOS_icon.png').'--';;
        if (file_exists('../../modules/boonex/avatar/data/favourite/' . $row_agency[10] . '.jpg'))
            $sImage = '../../modules/boonex/avatar/data/favourite/' . $row_agency[10] . '.jpg';
        else
            $sImage = '../../templates/tmpl_par/images/NO-PHOTOS_icon.png';
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
            if ($row_agency[2] != '0000-00-00') {
				$first = age($row_agency[2]);
			}
			if ($Couple_age != '0000-00-00') {
				$second = age($Couple_age);
			}
			$profileages = $first . '/' . $second;
			if ($row_agency[2] == '0000-00-00' || $Couple_age == '0000-00-00') {
				$profileages = "N/A";
			}

		} else {
			if ($row_agency[2] != '0000-00-00') {
				$firstsingle = age($row_agency[2]);
			}
			$profileages = $firstsingle;
		}
        
        $profileages = ($profileages) ? $profileages : "N/A";
        $childethnicity = str_replace(',', ', ', $row_agency[7]);
        $faiths = str_replace(',', ', ', $row_agency[6]);
        $Childages = str_replace(',', ', ', $row_agency[8]);

        $country = (trim($row_agency[17])) ? $row_agency[17] : "N/A";
        if ($country == '__United States') {
            $state = (trim($row_agency[3])) ? $row_agency[3] : "N/A";
        } else {
            $state = 'Non US';
        }


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

//        $photo = htmlentities($photo);

        /* array_push($arrRows_agency_list1, array(
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
          'profile_childethnicity' =>strlen($test) >= 170 ? substr($test, 0, 160) . '<a class = \'toolhelp\' title= "'.str_replace(',',', ',$row_agency[7]) .'"  href="javascript:void(0)" ><span title="">....More</span></a>' :$test,

          'profile_adoptiontype' => $row_agency[9],
          'profile_image' => $photo,
          'BM_id' => $logid,
          'profile_nickname' => $row_agency[11],
          ));
         */
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
        if (strtolower(end($epub_name_arr)) == 'epub')
            $epubbook = $E_pub_link;
        else
            $epubbook = false;

          $E_book = db_arr("SELECT content FROM `aqb_pc_members_blocks` WHERE `owner_id` = '$row_agency[0]' and title = 'E-book Profile'");
        $E_book_link = $E_book[content];

        $start = strpos($E_book_link, ".com/") + 5;
	$end = strpos($E_book_link, ".html") - $start + 5;
	$flipbook = substr($E_book_link, $start, $end);


// $start = strpos($E_book_link, "<a href=") + 8;
// $end = strpos($E_book_link, "target= _blank") - $start;
// $flipbook = substr($E_book_link, $start, $end);
// $flipbook = substr($flipbook, strlen("http://www.parentfinder.com/"));

        $flipbook_mob_link = false;
        if ($flipbook != false) {
            $flipbook_mob = split('/', $flipbook);
            $flipbook_mob_link = $flipbook_mob[0] . "/" . $flipbook_mob[1] . "/mobile/index.html";

            $flipbook_mob_filename = $dir['root'] . $flipbook_mob_link;
            if (!(file_exists($flipbook_mob_filename))) {
                $flipbook_mob_link = false;
            }
        }



//        $start = strpos($E_pub_link, "<a href=") + 8;
//        $end = strpos($E_pub_link, "target= _blank") - $start;
//        $epubbook = substr($E_pub_link, $start, $end);
//        $epubbook_mob_link = false;
//        if ($epubbook != false) {
//            $epubbook_mob = split('/', $epubbook);
//            $epubbook_mob_link = $epubbook_mob[0] . "/" . $epubbook_mob[1] . "/mobile/index.html";
//            $epubbook_mob_filename = $dir['root'] . $epubbook_mob_link;
//            if (!(file_exists($epubbook_mob_filename))) {
//                $epubbook_mob_link = false;
//            }
//        }

        ++$count;

        $xml[$recCount]['count'] = $recCount;
        $xml[$recCount]['profile_id'] = $row_agency[0];
        $xml[$recCount]['profile_id_Couple'] = $row_agency['Couple'];
        $xml[$recCount]['profile_firstname'] = $profilename_string;
        $xml[$recCount]['profile_firstname_full'] = $profilename;
        $xml[$recCount]['profile_age'] = $profileages;
        $xml[$recCount]['profile_state'] = $state;
        $xml[$recCount]['profile_waiting'] = $waiting;
        $xml[$recCount]['profile_noofchilds'] = $children;
        $xml[$recCount]['profile_faith'] = $faiths;
        $xml[$recCount]['profile_childage'] = $Childages;
        $xml[$recCount]['profile_childethnicity'] = $childethnicity;
        $xml[$recCount]['profile_adoptiontype'] = $adoptiontype;
		$xml[$recCount]['profile_country'] = $country;
        $xml[$recCount]['profile_image'] = $photo;
        $xml[$recCount]['BM_id'] = $logid;
        $xml[$recCount]['profile_nickname'] = $row_agency[11];
        $xml[$recCount]['profile_pdf'] = $Profile_pth;
        $xml[$recCount]['epub_link'] = $epubbook;
//        $xml[$recCount]['epub_mob_link'] = $epubbook_mob_link;
        $xml[$recCount]['gridData'] = $gridData;
        $xml[$recCount]['ebook_link'] = $flipbook;
        $xml[$recCount]['ebook_mob_link'] = $flipbook_mob_link;
        $recCount++;
        ++$count1;
    }
}

if ($needLoad == 1)
    $xml['count'] = $count1 - 1;
else
$xml['count'] = $count1;
$xml['needLoad'] = $needLoad;
echo json_encode($xml);

function avatarimages($iId) {
    global $dir;
    global $site;
    $sql_avt = "SELECT AV.*  FROM `bx_avatar_images` as AV INNER JOIN `Profiles` as P ON AV.`author_id` = P.`ID` AND AV.`ID` = P.`Avatar` WHERE P.`ID` = '$iId' ";
    $result_avt = mysql_query($sql_avt);
    //   echo $sql_avt;exit();
    // $aData1='';
    $row_avt = mysql_fetch_array($result_avt);
    $filename = $dir['root'] . 'modules/boonex/avatar/data/favourite/' . $row_avt[id] . '.jpg';
    if (file_exists($filename)) {
        $aData1 = $site['url'] . 'modules/boonex/avatar/data/favourite/' . $row_avt[id] . '.jpg';
    } else {
        $filename_new = $dir['root'] . 'modules/boonex/avatar/data/avatarphotos/' . $row_avt[author_id] . '.jpg';
        $sNewFilePath = $dir['root'] . 'modules/boonex/avatar/data/favourite/' . $row_avt[id] . '.jpg';
        if ($sNewFilePath != '' && $filename_new != '') {
            // imageResizee($filename_new, $sNewFilePath, $iWidth = 200, $iHeight = 200, true);
            imageResize_scroll_new($filename_new, $sNewFilePath, $iWidth = 301, $iHeight = 231, true);
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
                $filename_new1 = $dir['root'] . 'modules/boonex/photos/data/files/' . $row_avt1[ID] . '.' . $ext;
            }

            $sql_avt = "SELECT AV.*  FROM `bx_avatar_images` as AV INNER JOIN `Profiles` as P ON AV.`author_id` = P.`ID` AND AV.`ID` = P.`Avatar` WHERE P.`ID` = '$iId'";
            $result_avt = mysql_query($sql_avt);
            $num_rows = mysql_num_rows($result_avt);
            $row_avt = mysql_fetch_array($result_avt);
            if ($num_rows > 0) {
                //$sNewFilePath_s2 = '/var/www/html/pf/modules/boonex/avatar/data/slider1/' . $row_avt[id] . '.jpg';
                $sNewFilePath1 = $dir['root'] . 'modules/boonex/avatar/data/favourite/' . $row_avt[id] . '.jpg';
            }
            if ($sNewFilePath1 != '' && $filename_new1 != '') {
                //  imageResizee($filename_new1, $sNewFilePath1, $iWidth = 200, $iHeight = 200, true);
                imageResize_scroll_new($filename_new1, $sNewFilePath1, $iWidth = 301, $iHeight = 231, true);
            }
        }
    }
}
function age($birthday){
 list($year,$month,$day) = explode("-",$birthday);
 $year_diff  = date("Y") - $year;
 $month_diff = date("m") - $month;
 $day_diff   = date("d") - $day;
 if ($day_diff < 0 && $month_diff==0){$year_diff--;}
 if ($month_diff < 0){$year_diff--;}
// echo $year_diff;
 return $year_diff;

}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>
