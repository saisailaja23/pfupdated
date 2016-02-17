<?php

require_once( '../../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
bx_import('BxDolPageView');
/*
if (!isset($_GET['ID']) && !isset($_GET['Agency']))
    return Null;

if ($_GET['Agency'] && $_GET['Agency'] != 'undefined') {
    echo "SELECT bx_groups_main.author_id AS ID,bx_groups_main.title AS AgencyTitle, `Profiles`.*
                                FROM `Profiles`
                                JOIN bx_groups_main
                                WHERE Profiles.`ID` = bx_groups_main.author_id
                                AND bx_groups_main.uri = '" . $_GET['Agency'] . "'";
    $sAgencyCon = db_arr("SELECT bx_groups_main.author_id AS ID,bx_groups_main.title AS AgencyTitle, `Profiles`.*
                                FROM `Profiles`
                                JOIN bx_groups_main
                                WHERE Profiles.`ID` = bx_groups_main.author_id
                                AND bx_groups_main.uri = '" . $_GET['Agency'] . "'");
    $sAgencyInfo['AgencyTitle'] = $sAgencyCon['AgencyTitle'];
} else {
//    echo "SELECT bx_groups_main.author_id AS ID,bx_groups_main.title AS AgencyTitle FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =" . $_GET['ID'] . " AND Profiles.AdoptionAgency=bx_groups_main.id";
    $sAgencyInfo = db_arr("SELECT bx_groups_main.author_id AS ID,bx_groups_main.title AS AgencyTitle FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =" . $_GET['ID'] . " AND Profiles.AdoptionAgency=bx_groups_main.id");

    $sAgencyCon = getProfileInfo($sAgencyInfo['ID']);
}

$agencyInfo['AgencyTitle'] = $sAgencyInfo['AgencyTitle'];
$agencyInfo['ID'] = $sAgencyInfo['ID'];


if ($sAgencyCon['CONTACT_NUMBER']) {

    $agencyInfo['CONTACT_NUMBER'] = format_phone($sAgencyCon['CONTACT_NUMBER']);
}

$agencyInfo['Email'] = $sAgencyCon['Email'];
*/
$res = db_arr("SELECT show_contact FROM `Profiles` WHERE `ID` = ".$_GET['ID']);
$sc = $res['show_contact'];

if($sc == 1){
        $sub_qry = $_GET['ID'];
}else{
        $sub_qry = "SELECT bx_groups_main.author_id FROM Profiles
                                JOIN bx_groups_main WHERE Profiles.ID = ".$_GET['ID']."
                                AND Profiles.AdoptionAgency=bx_groups_main.id";
}

$contact = db_arr("SELECT g.id,
                                        IF($sc = 1, phonenumber, CONTACT_NUMBER) AS phonenumber,
                                        g.title,p.Email
                                        FROM `Profiles` as p
                                        LEFT JOIN `bx_groups_main` AS g ON p.AdoptionAgency = g.id
                                        WHERE p.`ID`IN ($sub_qry)");

$agencyInfo['AgencyTitle'] = $contact['title'];
$agencyInfo['ID'] = $contact['id'];
$agencyInfo['CONTACT_NUMBER'] = format_phone($contact['phonenumber']);
$agencyInfo['Email'] = $contact['Email'];

echo json_encode($agencyInfo);
?>
