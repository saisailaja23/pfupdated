<?php
/*********************************************************************************
* Name:    Prashanth A
* Date:    30/01/2014
* Purpose: Sending comments to the user
*********************************************************************************/
require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');

$user_id = $_POST['profileemail'];
$comments = $_POST['profilecomment'];

$profileinfo = getProfileInfo($user_id);

//$oEmailTemplate = new BxDolEmailTemplates();
//$aMail = $oEmailTemplate->parseTemplate('t_Activation', array(), $iId);

$to = "prashanth.adkathbail@mediaus.com";  
$subject = "Profile Approval"; 
$from = "prashanth.adkathbail@mediaus.com";  
$headers = "From: $from"; 

//$message = "Hi ".$profileinfo['FirstName'].','.'<br/><br/>'.$comments;


$message = '<html><head></head><body style="font: 12px Verdana; color:#000000">
<p><b>Dear'.$profileinfo['FirstName'].','.'<br/><br/>'.$comments.'</b>,</p><p><b>Thank you for using our services!</b></p>
<a href="http://help.cairsolutions.com/index.php?a=add&catid=4">Click here if you need further assistance</a>
<p>--</p>
<p style="font: bold 15px Verdana; color:blue"><SiteName> mail delivery system!!!
<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>';




sendMail($to,$subject,$message,$headers);
$emailcount = 1;

if ($emailcount > 0)
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