<?php
/*********************************************************************************
* Name:    Prashanth A
* Date:    15/05/2014
* Purpose: Retriiving member details
*********************************************************************************/

require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');

$Profile_id = $_POST['ProfileId'];

$User_pass = "select Password,AdoptionAgency from Profiles where ID = '" . $Profile_id . "'";
$row_pass = mysql_query($User_pass);
$result_pass = mysql_fetch_array($row_pass);
  

$Agency_author_id= "SELECT author_id FROM bx_groups_main WHERE id = '" . $result_pass['AdoptionAgency'] . "'";
$row_agency_author_id = mysql_query($Agency_author_id);
$result_agency_author_id = mysql_fetch_array($row_agency_author_id);

$Agency_pass = "SELECT Password FROM Profiles WHERE id = '" . $result_agency_author_id['author_id'] . "'";
$row_agency_pass = mysql_query($Agency_pass);
$results_agency_pass = mysql_fetch_array($row_agency_pass);

// $aUrl = parse_url($GLOBALS['site']['url'].'extra_profile_view_12.php');
// $sPath = isset($aUrl['path']) && !empty($aUrl['path']) ? $aUrl['path'] : '/';
$sPath = '/';

$cmd = 1;

if($cmd > 0) 
{
echo json_encode(array(
'status' => 'success',
'user_pass' => $result_pass['Password'],
'agency_author_id' => $result_agency_author_id['author_id'],
'agency_password' => $results_agency_pass['Password'],
'url_path' => $sPath,   
    
   
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
