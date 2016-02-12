<?php

/* * *******************************************************************************
 * Name:    Prashanth A
 * Date:    12/12/2013
 * Purpose: Listing the families liked by birth mother
 * ******************************************************************************* */
require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');
//require_once ('../../inc/classes/BxDolTemplate.php');

$sqlQuery = "SELECT * FROM `sys_pre_values` WHERE `Key` LIKE 'Region'";
$result = db_res($sqlQuery);
$arrRows_agencyaddress = array();

$totalCount=0;
while ($row = mysql_fetch_array($result)) {
    $arrValuesId = array();
    $region = $row['Value'];

    $tablename = 'Profiles,bx_groups_main';
    $columns = "agencytitle";
    $agencyaddressSQL = "select bx_groups_main.title as agencytitle,bx_groups_main.uri as agencyurl,Profiles.Region as regions, Profiles.nickname as nickname, Profiles.ID from bx_groups_main inner join Profiles where bx_groups_main.author_id = Profiles.ID and Profiles.Status = 'Active' 
  and Profiles.Region !='' and Profiles.Region = '$region'";
    $query = db_res($agencyaddressSQL);
    $cmdtuples = mysql_num_rows($query);
    $arrColumns = explode(",", $columns);
    $arrValues = array();
    $arrValuesNickName = array();
    $totalCount++;
    while (($row = mysql_fetch_array($query, MYSQL_BOTH))) {
        $totalCount++;
        foreach ($arrColumns as $column_name) {
            array_push($arrValues, $row[$column_name]);
        }
        array_push($arrValuesId, $row['ID']);
        array_push($arrValuesNickName, $row['nickname']);
        
    }
    array_push($arrRows_agencyaddress, array(
        'id' => $region,
        'data' => $arrValues,
        'profileId' => $arrValuesId,
        'nickname' => $arrValuesNickName,
    ));
}

// print_r($arrRows_agencyaddress);
if ($totalCount > 0) {
    echo json_encode(array(
        'count' => $totalCount,
        'status' => 'success',
        'agency_address' => array(
        'rows' => $arrRows_agencyaddress
        )
    ));
} else {
    echo json_encode(array(
        'status' => 'err',
        'response' => 'Could not read the data'
    ));
}
?>

