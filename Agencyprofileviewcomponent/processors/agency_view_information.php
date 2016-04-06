<?php

/* * *******************************************************************************
 * Name:    Prashanth A
 * Date:    19/12/2013
 * Purpose: Getting the agency details
 * ******************************************************************************* */
require_once ('../../inc/header.inc.php');

require_once ('../../inc/profiles.inc.php');

require_once ('../../inc/design.inc.php');
$logid = ($_GET['id'] != 'undefined') ? $_GET['id'] : getLoggedId();
//$logid = getLoggedId();
$member = getProfileInfo($logid);

$logged = getLoggedId();

$tablename = 'Profiles,bx_groups_main';
$columns = "AgencyTitle,City,State,zip,Country,CONTACT_NUMBER,Email,WEB_URL,Avatar,AgencyDesc,Facebook,Twitter,Google,Blogger,Pinerest,Pid,agencyuri";
$agencyaddressSQL = "SELECT bx_groups_main.title AS AgencyTitle,bx_groups_main.desc AS AgencyDesc,Profiles.ID AS Pid , bx_groups_main.author_id AS bxid,bx_groups_main.uri AS agencyuri,  Profiles.* FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID = $logid AND Profiles.AdoptionAgency=bx_groups_main.id)";
$query = db_res($agencyaddressSQL);
$cmdtuples = mysql_num_rows($query);
$arrColumns = explode(",", $columns);
$arrRows_agencyaddress = array();

while (($row = mysql_fetch_array($query, MYSQL_BOTH))) {
    $arrValues = array();
    foreach ($arrColumns as $column_name) {
        array_push($arrValues, $row[$column_name]);
    }if (is_numeric($arrValues[5])) {
        $num = $arrValues[5];
        $num = preg_replace('/[^0-9]/', '', $num);
        $len = strlen($num);
        if ($len == 7)
            $num = preg_replace('/([0-9]{3})([0-9]{4})/', '$1-$2', $num);
        elseif ($len == 10)
            $num = preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})/', '$1- $2-$3', $num);
        $arrValues[5] = $num;
    }
    array_push($arrValues, $stateAbb[$row['State']]);
    array_push($arrRows_agencyaddress, array(
        'id' => $row[0],
        'data' => $arrValues,
    ));
}

// Getting last active time

$LastActivetime = db_arr("SELECT `DateLastLogin` FROM `Profiles` WHERE `ID` = $logid LIMIT 1");
$Activetime = $LastActivetime[DateLastLogin];
$date1 = time();
$date2 = strtotime($Activetime);
$getlogid = ($_GET['id'] != 'undefined') ? $_GET['id'] : getLoggedId();
$dateDiff = $date1 - $date2;
$fullDays = floor($dateDiff / (60 * 60 * 24));
$fullHours = floor(($dateDiff - ($fullDays * 60 * 60 * 24)) / (60 * 60));
$fullMinutes = floor(($dateDiff - ($fullDays * 60 * 60 * 24) - ($fullHours * 60 * 60)) / 60);

// $total =  "LAST ACTIVE ".$fullDays. " DAYS ". $fullHours. " HOURS AND ". $fullMinutes ." MINUTES ago";

if ($Activetime == '0000-00-00 00:00:00') {
    $total = "";
} else {
    $total = "LAST ACTIVE " . $fullDays . " DAYS " . $fullHours . " HOURS ago";
}

//$total = "LAST ACTIVE  " . $fullHours. " HOURS AGO";


$inbox_mess = db_arr("SELECT count(*) as inboxcount  FROM `sys_messages` WHERE `Recipient` = $logid and Type = 'letter' and Trash = ''");
$inboxmessages = $inbox_mess[inboxcount];


$sent_mess = db_arr("SELECT count(*) as sentcount  FROM `sys_messages` WHERE `Sender` = $logid");
$sentmessages = $sent_mess[sentcount];

$unread_mess = db_arr("SELECT count(*) as unreadcount  FROM `sys_messages` WHERE `Recipient` = $logid AND `New` = '1' AND `Type` = 'letter' and Trash =''");
$unreadmessages = $unread_mess[unreadcount];


$tablename = 'Profiles,bx_groups_fans,sys_acl_levels_members';
$columns = "Avatar";
$Agency_members = "SELECT SQL_CALC_FOUND_ROWS `p`.* , IF(ISNULL(`tl`.`Name`),'', `tl`.`Name`) AS `ml_name` FROM `Profiles` AS `p` INNER JOIN `bx_groups_fans` AS `f` ON (`f`.`id_entry` = '$member[AdoptionAgency]' AND `f`.`id_profile` = `p`.`ID` AND `f`.`confirmed` = 1 AND `p`.`Status` <> 'Rejected' AND `p`.`Avatar` != '0' ) LEFT JOIN `sys_acl_levels_members` AS `tlm` ON `p`.`ID`=`tlm`.`IDMember` AND `tlm`.`DateStarts` < NOW() AND (`tlm`.`DateExpires`>NOW() || ISNULL(`tlm`.`DateExpires`)) LEFT JOIN `sys_acl_levels` AS `tl` ON `tlm`.`IDLevel`=`tl`.`ID` GROUP BY `p`. `ID`  Order by DateLastLogin Desc";
$query = db_res($Agency_members);
$cmdtuples = 1;

$arrColumns = explode(",", $columns);
$arrRows_agencymembers = array();

while (($row = mysql_fetch_array($query, MYSQL_BOTH))) {
    $arrValues = array();
    foreach ($arrColumns as $column_name) {
        array_push($arrValues, $row[$column_name]);
    }

    array_push($arrRows_agencymembers, array(
        'id' => $row[0],
        'data' => $arrValues,
    ));
}
/* * --------- Satya - To show Agency thumb ----------* */

//$agencyLogo = db_arr("SELECT avatar FROM `profiles` where `ProfileType` = '8' and ID=$logid");
$agencyLogoSql = mysql_query("SELECT avatar FROM Profiles where `ProfileType` = '8' and ID=$logid");
$agencyLogo = mysql_fetch_array($agencyLogoSql);

if ($agencyLogo['avatar']) {
    $avatar_img = '<img class="headIcon"src="' . $site['url'] . 'modules/boonex/avatar/data/images/' . $agencyLogo['avatar'] . ".jpg" . '"><div style="clear:both;"></div>';
} else
    $avatar_img = '<img class="headIcon" src="templates/tmpl_par/images/agency_thumb_small.png" alt="" title="" /><div style="clear:both;"></div>';

/* * --------- Satya - End of code to show Agency thumb ----------* */

$aData = getAgencyInfo($logid);


if ($aData['thumb']) {
    $a = array('ID' => $aData['author_id'], 'Avatar' => $aData['thumb']);
    $aImage = BxDolService::call('photos', 'get_image', array($a, 'browse'), 'Search');

    $sImage = $aImage['no_image'] ? '' : $aImage['file'];
}
//  echo  "sdfsdfSDF"$sImage;  
$sImages = '<div class="bx-twig-unit-thumb-cont bx-def-margin-sec-right">
      <img src="' . $sImage . '" class="bx-twig-unit-thumb bx-def-round-corners bx-def-shadow"></div>';

if ($cmdtuples > 0) {
    echo json_encode(array(
        'status' => 'success',
        'agency_address' => array(
            'rows' => $arrRows_agencyaddress
        ),
        'Profiles_active' => array(
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
        'agencymembers' => array(
            'rows' => $arrRows_agencymembers
        ),
        'logged' => array(
            'rows' => $logged
        ),
        'Q' => array(
            'rows' => $Agency_members
        ),
        'Agency_logo' => array(
            'rows' => $sImages
        ),
        'Agency_logo_path' => array(
            'rows' => $avatar_img
        )
    ));
} else {
    echo json_encode(array(
        'status' => 'err',
        'response' => 'Could not read the data: ' . mssql_get_last_message()
    ));
}
?>
