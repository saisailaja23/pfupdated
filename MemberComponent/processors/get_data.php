<?php
/*********************************************************************************

*     Name                :  Prashanth A
*     Date                :  Mon Nov 5 2013
*     Purpose             :  Retrieveing  the values to populate in select boxes

**********************************************************************************/
require_once ('../../inc/header.inc.php');

require_once ('../../inc/profiles.inc.php');

// Getting values from profile types select field
$logged = getLoggedId();
$tablename = 'aqb_pts_profile_types';
$columns = 'ID,Name';
$stringSQL = "SELECT  " . $columns . " FROM " . $tablename . "";
$query = mysql_query($stringSQL);
$cmdtuples = mysql_num_rows($query);
$arrColumns = explode(",", $columns);
$arrRows = array();

while (($row = mysql_fetch_array($query, MYSQL_BOTH)))
{
$arrValues = array();
foreach($arrColumns as $column_name)
{
array_push($arrValues, $row[$column_name]);
}

array_push($arrRows, array(
'id' => $row[0],
'data' => $arrValues,
));
}

// Getting values for agency select field

$columns = 'Value,String'; //
$stringSQL_Agency = "SELECT  sys_pre_values.Value,sys_localization_strings.String FROM sys_pre_values,sys_localization_keys,sys_localization_strings WHERE sys_localization_keys.Key = sys_pre_values.LKey and sys_localization_keys.ID = sys_localization_strings.IDKey and sys_pre_values.Key = 'AdoptionAgency' Order By sys_pre_values.LKey ";
$query = mysql_query($stringSQL_Agency);
$cmdtuples = mysql_num_rows($query);
$arrColumns = explode(",", $columns);
$arrRows_agency = array();

while (($row = mysql_fetch_array($query, MYSQL_BOTH)))
{
$arrValues = array();
foreach($arrColumns as $column_name)
{
array_push($arrValues, $row[$column_name]);
}

array_push($arrRows_agency, array(
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

// Getting values for membership select field

$tablename = 'sys_acl_levels';
$columns = 'ID,Name';
$stringSQL_Membership = "SELECT  " . $columns . " FROM " . $tablename . " WHERE `ID` IN('23','24','25') ORDER BY Name";
$query = mysql_query($stringSQL_Membership);
$cmdtuples = mysql_num_rows($query);
$arrColumns = explode(",", $columns);
$arrRows_Membership = array();

while (($row = mysql_fetch_array($query, MYSQL_BOTH)))
{
$arrValues = array();
foreach($arrColumns as $column_name)
{
array_push($arrValues, $row[$column_name]);
}

array_push($arrRows_Membership, array(
'id' => $row[0],
'data' => $arrValues,
));
}

// Getting values for state select field

$tablename = 'Profiles';
$columns = 'State';
$stringSQL_State = "SELECT  " . $columns . " FROM " . $tablename . " WHERE State != '' GROUP BY State ";
$query = mysql_query($stringSQL_State);
$cmdtuples = mysql_num_rows($query);
$arrColumns = explode(",", $columns);
$arrRows_State = array();

while (($row = mysql_fetch_array($query, MYSQL_BOTH)))
{
$arrValues = array();
foreach($arrColumns as $column_name)
{
array_push($arrValues, $row[$column_name]);
}

array_push($arrRows_State, array(
'id' => $row[0],
'data' => $arrValues,
));
}

if ($cmdtuples > 0)
{
echo json_encode(array(
'status' => 'success',
'response' => 'data readed',
'sql_statement' => $stringSQL,
'sql_statement_agency' => $stringSQL_Agency,
'sql_statement_region' => $stringSQL_Region,
'sql_statement_membership' => $stringSQL_Membership,
'sql_statement_state' => $stringSQL_State,
'aqb_pts_profile_types' => array(
'rows' => $arrRows
) ,
'sys_pre_values' => array(
'rows' => $arrRows_agency
) ,
'sys_pre_values_region' => array(
'rows' => $arrRows_Region
) ,
'sys_acl_levels' => array(
'rows' => $arrRows_Membership
) ,
'profiles' => array(
'rows' => $arrRows_State
),
 'logged' => array(
'rows' => $logged
),   
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