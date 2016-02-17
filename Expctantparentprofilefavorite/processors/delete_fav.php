<?php
/*********************************************************************************
* Name:    Prashanth A
* Date:    12/12/2013
* Purpose: Deleting the families liked by birth mother
*********************************************************************************/
require_once ('../../inc/header.inc.php');

require_once ('../../inc/profiles.inc.php');

$agency_id = $_POST['Agencyid'];
$BM_id = getLoggedId();
$DeleteLike_List = "Delete FROM `family_favourite` WHERE `favouredby` = " . $BM_id . " and `favouredfamily` = " . $agency_id . "";
db_res($DeleteLike_List);

if (mysql_affected_rows() > 0)
{
echo json_encode(array(
'status' => 'success',
'sql_statement' => $DeleteLike_List
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
