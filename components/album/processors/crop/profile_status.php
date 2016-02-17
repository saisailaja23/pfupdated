 <?php
/*********************************************************************************
* Name:    Prashanth A
* Date:    06/05/2014
* Purpose: Gettting the agency information
*********************************************************************************/
 
require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');

$logid = getLoggedId();

$match_status= db_arr("SELECT `status` FROM `watermarkimages` WHERE `author_id` = '$logid'");
$matchstatus = $match_status[status]; 
$cmdtuples = 1;

if ($cmdtuples > 0)
{
echo json_encode(array(
'status' => 'success',
'Profiles_matchstatus' => array(
'rows' => $matchstatus
),
'Profiles_logid' => array(
'rows' => $logid
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
