<?php
/*********************************************************************************
* Name:    Prashanth A
* Date:    30/12/2013
* Purpose: Changing user profile status
*********************************************************************************/
require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');


$Profile_id = getLoggedId();

$sSalt = genRndSalt();

$Profile_pass = $_POST['new_pass'];
$Old_pass = $_POST['old_password'];
if($Profile_pass!='')
    $Password = encryptUserPwd($Profile_pass, $sSalt);

$q = "select Salt  from  Profiles where ID = '" . $Profile_id . "'";
$row = mysql_query($q);
$result = mysql_fetch_array($row);
$OldPassword = encryptUserPwd($Old_pass, $result['Salt']);

$q = "select id  from  Profiles where Password = '" . $OldPassword . "'";
$row = mysql_query($q);
if(mysql_num_rows($row)>0){
$Profile_query= "Update `Profiles` SET    
    `Password` = '" . $Password . "',
    `Salt` = '" . $sSalt . "'
    where ID = '" . $Profile_id . "'";


$Profile_draft_query= "Update `Profiles_draft` SET    
    `Password` = '" . $Password . "',
    `Salt` = '" . $sSalt . "'
    where ID = '" . $Profile_id . "'";

if(mysql_query($Profile_query) && mysql_query($Profile_draft_query))
{
echo json_encode(array(
'status' => 'success'
));
}
else
{
echo json_encode(array(
'status' => 'err',
'response' => 'Could not read the data: ' . mssql_get_last_message()
));
}
}
else
{
echo json_encode(array(
'status' => 'err',
'response' => 'invalid password'));
}

?>