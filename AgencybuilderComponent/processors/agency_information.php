<?php
/*********************************************************************************
 * Name:    Prashanth A 
 * Date:    13/12/2013
 * Purpose: Populating the values in agency profile builder
 *********************************************************************************/

require_once('../../inc/header.inc.php');
require_once('../../inc/profiles.inc.php');


$logid          = getLoggedId();
$member         = getProfileInfo($logid);
/*        
$tablename      = 'Profiles_draft';
//$columns        = 'ID,FirstName,State,AdoptionAgency,Age,state,waiting,noofchildren,faith,childethnicity,childage,adoptiontype,CONTACT_NUMBER,street_address';
 $columns        = 'ID,FirstName,State,AdoptionAgency,Age,state,waiting,noofchildren,faith,childethnicity,childage,adoptiontype,CONTACT_NUMBER,street_address';
$stringSQL      = "SELECT  " . $columns . " FROM " . $tablename . " where ID = " . $logid . "";
$query          = db_res($stringSQL);
$cmdtuples      = mysql_num_rows($query);
$arrColumns     = explode(",", $columns);
$arrRows        = array();

while (($row    = mysql_fetch_array($query, MYSQL_BOTH)))
{
    $arrValues  = array();
    foreach($arrColumns as $column_name)
    {
        array_push($arrValues, $row[$column_name]);
    }

    array_push($arrRows, array(
    'id' => $row[0],
    'data' => $arrValues,
    ));
}

*/

$logid = getLoggedId();
$member = getProfileInfo($logid);
$tablename = 'Profiles,bx_groups_main';
$columns = "AgencyTitle,City,State,zip,Country,CONTACT_NUMBER,Email,WEB_URL,Avatar,AgencyDesc,Facebook,Twitter,Google,Blogger,Pinerest,Pid,Agencyuri,Street_Address,Region,unpubPwd";
$agencyaddressSQL = "SELECT bx_groups_main.title AS AgencyTitle,bx_groups_main.Unpublish_Password AS unpubPwd,bx_groups_main.desc AS AgencyDesc,Profiles.ID AS Pid, bx_groups_main.author_id AS bxid,bx_groups_main.uri AS Agencyuri,Profiles.* FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID = $logid AND Profiles.AdoptionAgency=bx_groups_main.id)";
$query = db_res($agencyaddressSQL);
$cmdtuples = mysql_num_rows($query);
$arrColumns = explode(",", $columns);
$arrRows_agencyaddress = array();

while (($row = mysql_fetch_array($query, MYSQL_BOTH)))
{
$arrValues = array();
foreach($arrColumns as $column_name)
{
 
  //$row[AgencyDesc] =  strip_tags($row[AgencyDesc]);  
    $row[AgencyDesc] =  $row[AgencyDesc];  

    
array_push($arrValues, $row[$column_name]);
}

array_push($arrRows_agencyaddress, array(
'id' => $row[0],
'data' => $arrValues,
));
}

$tablename = 'Profiles_draft';
$columns = 'State';
$stringSQL_State = "SELECT  " . $columns . " FROM " . $tablename . " WHERE State != '' GROUP BY State ";
$query = mysql_query($stringSQL_State);
$cmdtuples = mysql_num_rows($query);
$arrColumns = explode(",", $columns);
$arrRows_State = array();

while (($row = mysql_fetch_array($query, MYSQL_BOTH))) {
	$arrValues = array();
	foreach ($arrColumns as $column_name) {
		array_push($arrValues, $row[$column_name]);
	}

	array_push($arrRows_State, array(
			'id' => $row[0],
			'data' => $arrValues,
		));
}

// Getting values for region select field

$tablename = 'sys_pre_values';
$columns = 'Value,LKey';
$stringSQL_Region = "SELECT  " . $columns . " FROM " . $tablename . " WHERE `Key` = 'Region' ORDER BY LKey";
$query = mysql_query($stringSQL_Region);
$cmdtuples = mysql_num_rows($query);
$arrColumns = explode(",", $columns);
$arrRows_Region = array();

while (($row = mysql_fetch_array($query, MYSQL_BOTH)))
{
$arrValues = array();
foreach($arrColumns as $column_name)
{
array_push($arrValues, $row[$column_name]);
}

array_push($arrRows_Region, array(
'id' => $row[0],
'data' => $arrValues,
));
}
//print_r($arrValuess);
if ($cmdtuples > 0)
{
echo json_encode(array(
'status' => 'success',

'agency_details' => array(
'rows' => $arrRows_agencyaddress
), 
'Profiles_State' => array(
'rows' => $arrRows_State
			),
 
'Profiles_region' => array(
'rows' => $arrRows_Region
),   
'sql_statement' => $stringSQL,
));
}
  else
{
echo json_encode(array(
'status' => 'err', 
'response' => 'Could not read the data: ' . mssql_get_last_message()
    
));
}   



?>

