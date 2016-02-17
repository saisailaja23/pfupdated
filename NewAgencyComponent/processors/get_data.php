<?php
/*********************************************************************************

*     Name                :  Prashanth A
*     Date                :  Mon Nov 5 2013
*     Purpose             :  Retrieveing  the values to populate in select boxes

**********************************************************************************/
require_once ('../../inc/header.inc.php');

require_once ('../../inc/profiles.inc.php');

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
    'sql_statement_state' => $stringSQL_State,
    'profiles' => array(
    'rows' => $arrRows_State
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