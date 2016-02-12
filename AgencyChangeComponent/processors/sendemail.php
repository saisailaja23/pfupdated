<?php
/*********************************************************************************
* Name:    Prashanth A
* Date:    30/01/2014
* Purpose: Sending comments to the user
*********************************************************************************/
require_once( '../../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

require_once ('../../inc/utils.inc.php');

require_once ('../../inc/db.inc.php');
bx_import( 'BxDolEmailTemplates' );
bx_import( 'BxTemplFormView' );
bx_import ('BxDolForm');


$id = getLoggedId();
$aProfile = getProfileInfo($id);
$agency_ID= $_POST['agency_title'];
$description = $_POST['description'];


$current_agency = db_arr("SELECT bx_groups_main.title AS AgencyTitle,Profiles.Email as Email FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID = '$id' AND Profiles.AdoptionAgency=bx_groups_main.id)");

$change_agency = db_arr("SELECT P.Email as Email, b.title as AgencyTitle FROM Profiles P, bx_groups_main b WHERE P.id = b.author_id AND b.id ='$agency_ID'");

$currentdate = date("m/d/Y");
$memid = getMembershipID(); 

$current_membership = db_arr("SELECT Name FROM `sys_acl_levels` WHERE ID = '$memid'");


$rEmailTemplate = new BxDolEmailTemplates();
$aTemplate = $rEmailTemplate -> getTemplate( 't_ChangeAgency' ) ;
             
$sMessageBody = str_replace("<changeagency>",$change_agency['AgencyTitle'], $aTemplate['Body']);

$sMessageBody = str_replace("<currentagency>",$current_agency['AgencyTitle'], $sMessageBody);
$sMessageBody = str_replace("<username>",$aProfile['NickName'], $sMessageBody);
$sMessageBody = str_replace("<firstname>",$aProfile['FirstName'], $sMessageBody);
$sMessageBody = str_replace("<lastname>",$aProfile['LastName'], $sMessageBody);
$sMessageBody = str_replace("<email>",$aProfile['Email'], $sMessageBody);
$sMessageBody = str_replace("<currentmembership>",$current_membership['Name'], $sMessageBody);
$sMessageBody = str_replace("<currentdate>",$currentdate, $sMessageBody);
$sMessageBody = str_replace("<description>",$description, $sMessageBody);


$sMessageSubject = str_replace("<Firstname>",$aProfile['FirstName'], $aTemplate['Subject']);
$sMessageSubject = str_replace("<Lastname>",$aProfile['LastName'], $sMessageSubject);
 
$emailcount = sendMail($aProfile['Email'], $sMessageSubject,$sMessageBody, $aProfile['ID']);
$targetAgencyEmail = sendMail($change_agency['Email'], $sMessageSubject,$sMessageBody, $aProfile['ID']);
$currentAgencyEmail = sendMail($current_agency['Email'], $sMessageSubject,$sMessageBody, $aProfile['ID']);
$adminEmail = sendMail('info@parentfinder.com', $sMessageSubject,$sMessageBody, $aProfile['ID']);


if ($emailcount)
{
echo json_encode(array(
'status' => 'success'
));
} else
{
echo json_encode(array(
'status' => 'err'
));
}

?>