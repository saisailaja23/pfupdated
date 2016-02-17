<?php
/*********************************************************************************
* Name:    Prashanth A
* Date:    12/12/2013
* Purpose: Deleting the agencies liked by birth mother
*********************************************************************************/
require_once ('../../inc/header.inc.php');

require_once ('../../inc/profiles.inc.php');

$agency_id = $_POST['Agencyid'];
$BM_id = $_POST['Birthmotherid'];
$DeleteLike_List = "Delete FROM `like_list` WHERE `LikedBy` = " . $BM_id . " and `AgencyLike` = " . $agency_id . "";
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
