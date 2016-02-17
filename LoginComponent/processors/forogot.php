<?php

require_once( '../../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
bx_import( 'BxDolEmailTemplates' );
bx_import( 'BxTemplFormView' );

$forgotemail = $_POST['Email'];

  $iID = (int)db_value( "SELECT `ID` FROM `Profiles` WHERE `Email` = '$forgotemail'" );
   if ($iID)
   {       

    $sEmail = process_db_input($_POST['Email'], BX_TAGS_STRIP);
    $memb_arr = db_arr( "SELECT `ID` FROM `Profiles` WHERE `Email` = '$sEmail'" );

    $recipient = $sEmail;

    $rEmailTemplate = new BxDolEmailTemplates();
    $aTemplate = $rEmailTemplate -> getTemplate( 't_Forgot', $memb_arr['ID'] ) ;

    $aPlus['Password'] = generateUserNewPwd($memb_arr['ID']);
    $aProfile = getProfileInfo($memb_arr['ID']);    
    $mail_ret = sendMail( $recipient, $aTemplate['Subject'], $aTemplate['Body'], $memb_arr['ID'], $aPlus, 'html', false, true );
    

    // create system event
    require_once(BX_DIRECTORY_PATH_CLASSES . 'BxDolAlerts.php');
    $oZ = new BxDolAlerts('profile', 'password_restore',  $memb_arr['ID']);
      $oZ->alert();

    $_page['header'] = _t( "_Recognized" );
    $_page['header_text'] = _t( "_RECOGNIZED", $site['title'] );
    if ($mail_ret)
        $action_result = _t( "_MEMBER_RECOGNIZED_MAIL_SENT", $site['url'], $site['title'] );
      //  $action_result = 'Emailsent';
    else
        $action_result = _t( "_MEMBER_RECOGNIZED_MAIL_NOT_SENT", $site['title'] );
       // $action_result = 'Emailnotsent';
   }
//   print_r($action_result);
   

if ($iID)
{
echo json_encode(array(
'status' => 'success',
'response' => 'Response',
'email_status' => $iID
//'username_error' => $username_error,
//'agency_error' => $agency_error,
//'user_redirection' => $user_redirect,
));
}
else
{
echo json_encode(array(
'status' => 'err',
'response' => 'The email address you have entered does not exist'
));
}
function generateUserNewPwd($ID)
{
    $sPwd = genRndPwd();
    $sSalt = genRndSalt();

   $sQuery = "
        UPDATE `Profiles`
        SET
            `Password` = '" . encryptUserPwd($sPwd, $sSalt) . "',
            `Salt` = '$sSalt'
        WHERE
            `ID`='$ID'
    ";

    $sQuery2 = "
        UPDATE `Profiles_draft`
        SET
            `Password` = '" . encryptUserPwd($sPwd, $sSalt) . "',
            `Salt` = '$sSalt'
        WHERE
            `ID`='$ID'
    ";
	
    db_res($sQuery);
    db_res($sQuery2);
    createUserDataFile($ID);

    require_once(BX_DIRECTORY_PATH_CLASSES . 'BxDolAlerts.php');
    $oZ = new BxDolAlerts('profile', 'edit', $ID);
    $oZ->alert();
    return $sPwd;
}
?>
