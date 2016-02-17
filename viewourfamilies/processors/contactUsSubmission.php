<?php

require_once( '../../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

global $site;

$sSenderName = process_pass_data($_POST['name'], BX_TAGS_STRIP);
$sSenderEmail = process_pass_data($_POST['email'], BX_TAGS_STRIP);
$sLetterSubject = process_pass_data($_POST['subject'], BX_TAGS_STRIP);
$sLetterBody = process_pass_data($_POST['body'], BX_TAGS_STRIP);

//echo '<pre>';
//print_r($_POST);

if ($_GET['ID']) {
    $_page['header_text'] = "Contact Us";
//echo "SELECT EMAIL FROM Profiles WHERE Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =".$_GET['ID']." AND Profiles.AdoptionAgency=bx_groups_main.id )";
    
	$res = db_arr("SELECT show_contact FROM `Profiles` WHERE `ID` = ".$_GET['ID']);
	if($res['show_contact'] == 1){
		$sub_qry = $_GET['ID'];
	}else{
		$sub_qry = "SELECT bx_groups_main.author_id FROM Profiles 
					JOIN bx_groups_main WHERE Profiles.ID = ".$_GET['ID']." 
					AND Profiles.AdoptionAgency=bx_groups_main.id";
	}
	
	$aMemberInfo = db_arr("SELECT Email FROM Profiles 
							WHERE Profiles.ID  IN ($sub_qry)");
							
    $toEmail = $aMemberInfo['Email'];
} elseif ($_GET['Agency']) {
    $aMemberInfo = db_arr("SELECT *
                                FROM `Profiles`
                                JOIN bx_groups_main
                                WHERE Profiles.`ID` = bx_groups_main.author_id
                                AND bx_groups_main.uri = '" . $_GET['Agency'] . "'");
    $toEmail = $aMemberInfo['Email'];
} else {
    $toEmail = $site['Email'];
}

if ($_GET['ID'] != '') {
    $adoptiveparent = db_arr("SELECT *
                                FROM `Profiles`
                                WHERE `ID` = " . $_GET['ID']);
}
$sLetterBody = "<html><body><p style='font-family:georgia;'><b>Message For Adoptive Parent: <a href = '" . $site['url'] . $adoptiveparent['NickName'] . "'>" . $adoptiveparent['NickName'] . "</a></b></p>" . $sLetterBody . " " . '<p>Thanks,</p>' . "<p>" . $sSenderName . "&#44; </p><p>" . '&#40;' . $sSenderEmail . " &#41;</p></html></body>";

if (sendMail($toEmail, $sLetterSubject, $sLetterBody)) {
    $sActionKey = '_ADM_PROFILE_SEND_MSG';
    $sLetterSubject = "Thank You";
    $sLetterBody = "<html><body>
                         <p><b>Dear " . $sSenderName . "</b></p>
                        <p>
                        Thank you for your message. We will get back to you within couple of days.
                        For additional questions, please reach out to " . $toEmail . ".</p>
                        <p><b>Thank you for using our services!</b></p>
                        <p>--</p>
                        <p style='font: bold 10px Verdana; color:red'>ParentFinder Community mail delivery system!!!
                        <br />Auto-generated e-mail, please, do not reply!!!</p>
                        </html></body>";
    sendMail($sSenderEmail, $sLetterSubject, $sLetterBody);
} else {
    $sActionKey = '_Email sent failed';
}
echo $sAction['text'] = MsgBox(_t($sActionKey));
//echo json_encode($sAction);
?>