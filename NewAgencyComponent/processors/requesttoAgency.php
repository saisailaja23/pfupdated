<?php
/***********************************************************************************************

*     Name                :  Prashanth A
*     Date                :  Mon Nov 5 2013
*     Purpose             :  Inserting values to the database  and assiging membership to users.

************************************************************************************************/
require_once ('../../inc/header.inc.php');

require_once ('../../inc/profiles.inc.php');

require_once ('../../inc/utils.inc.php');

require_once ('../../inc/db.inc.php');
bx_import( 'BxDolEmailTemplates' );
bx_import( 'BxTemplFormView' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolEmailTemplates.php' );
// Getting the post values

$agency_name    = $_POST['agency_name'];
$agency_email   = $_POST['agency_email'];
$agency_state   = $_POST['agency_state'];
$from_name      = $_POST['from_name'];
$from_email     = $_POST['from_email'];

$admin_details  = getProfileInfo(1);
$admin_email    = $admin_details['Email'];
//echo "admin email ".$admin_email;
try
{
    $from_details    = "<p> Requested Client Name : $from_name</p>";
    $oEmailTemplates = new BxDolEmailTemplates();
    $aTemplate = $oEmailTemplates->getTemplate('t_InformAgency');
    $agencyEmail_content  = "Hi $agency_name, <br/><p>".$aTemplate['Body']."</p>".$from_details;
    sendMail($agency_email, $aTemplate['Subject'], $agencyEmail_content, 0);
    
    
    $oEmailTemplate_admin = new BxDolEmailTemplates();
    $aTemplate_admin      = $oEmailTemplate_admin->getTemplate('t_InformAdmin');
    
    $adminEmail_content   = $aTemplate_admin['Body'].$from_details;
    sendMail($admin_email, $aTemplate_admin['Subject'], $adminEmail_content, 0);
    
    //$insert_details = "insert into `letter` (label, description,profile_id) VALUES ('$label', '$content',$id)";
    $insert_details = "INSERT INTO `agency_join_request`(`agencyName`, `agencyEmail`, `agencyState`, `fromName`,`fromEmail`,`createdDate`) VALUES ('".str_replace("'","''",$agency_name)."','".str_replace("'","''",$agency_email)."','".str_replace("'","''",$agency_state)."','".str_replace("'","''",$from_name)."','".str_replace("'","''",$from_email)."','".date("Y-m-d-H-i-s",time())."')";
    //echo $insert_details;
    if(mysql_query($insert_details)){
        echo json_encode(array(
        'status' => 'success',
        'response' => 'Response',
        'email_id' => $agency_email
        ));
    }
    else
    {
        echo json_encode(array(
        'status' => 'err',
        'response' => 'Database error : ' . mysql_get_last_message()
        ));
    }
}
catch(Exception $e)
{
    echo json_encode(array(
    'status' => 'err',
    'response' => 'Error Please contact Site Admin: '
    ));
}

?>