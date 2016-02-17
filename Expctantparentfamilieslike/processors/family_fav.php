<?php
/*********************************************************************************
* Name:    Prashanth A S
* Date:    19/12/2013
* Purpose: Deleting the families liked by birth mother
*********************************************************************************/
require_once ('../../inc/header.inc.php');

require_once ('../../inc/profiles.inc.php');

$agency_id = $_POST['Agencyid'];
$BM_id = getLoggedId();
$stringSQL="select favouredby from family_favourite where favouredby = $BM_id and favouredfamily = $agency_id";
$query = db_res($stringSQL);
$cmdtuples = mysql_num_rows($query);
if($cmdtuples <=0 ){
$AddToFavList = "INSERT INTO `family_favourite` (`favouredby`,`favouredfamily`) VALUES(" . $BM_id . "," . $agency_id . ")";
db_res($AddToFavList);


if (mysql_affected_rows() > 0)
{
echo json_encode(array(
'status' => 'success',
'sql_statement' => $AddToFavList
));
}
  else
{
echo json_encode(array(
'status' => 'err',
'response' => 'Could not read the data: ' . mssql_get_last_message()
));
}}
else{
    echo json_encode(array(
'status' => 'err',
'response' => 'You had favourited this before',
        'sql'=>$stringSQL
));
}

?>
