<?php
/*********************************************************************************
* Name:    Prashanth A
* Date:    30/12/2013
* Purpose: Changing user profile status
*********************************************************************************/
require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');
require_once ('../../inc/utils.inc.php');

$Profile_id = $_POST['ProfileId'];
$Profile_status = $_POST['ProfileStatus'];
$profileDet = getProfileInfo($Profile_id);

if(!empty($Profile_id)) {
$Profile_Status_Change = "Update `Profiles` SET `Status` = '" . $Profile_status . "' where ID = '" . $Profile_id . "'";
db_res($Profile_Status_Change);
$Profile_Status_Change_draft = "Update `Profiles_draft` SET `Status` = '" . $Profile_status . "' where ID = '" . $Profile_id . "'";
db_res($Profile_Status_Change_draft);

//$rEmailTemplate = new BxDolEmailTemplates();
//$aTemplate = $rEmailTemplate -> getTemplate( 't_Activation' );
//sendMail($profileDet['Email'], $aTemplate['Subject'], $aTemplate['Body'], $Profile_id );
$update =1;
}
if($update > 0)
{
echo json_encode(array(
'status' => 'success',
'sql_statement' => $Profile_Status_Change
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