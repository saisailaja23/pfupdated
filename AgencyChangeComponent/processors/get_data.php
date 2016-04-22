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


// Getting values for agency select field

$columns = 'String,String'; //
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


if ($cmdtuples > 0)
{
echo json_encode(array(
'status' => 'success',

'sys_pre_values' => array(
'rows' => $arrRows_agency
) 
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