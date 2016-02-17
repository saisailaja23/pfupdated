<?php

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
bx_import('BxDolPageView');


class BxContactAgencyPageView extends BxDolPageView {
function BxContactAgencyPageView() {
      parent::BxDolPageView('ContactAgency');
      // --------------- page variables and login
//-------------------Contact Agency Start------------------------------
$iViewedID   = ( isset($_GET['ID']) ) ? (int) $_GET['ID']	: 0;

if($_GET['Agency']){
        $agency_ID=db_arr("SELECT author_id FROM bx_groups_main WHERE uri='".$_GET['Agency']."'");
        $iViewedID=$agency_ID['author_id'];
}
    $sOutputHtml = MsgBox ( _t( '_Profile NA' ) );
    $GLOBALS['oTopMenu'] -> setCurrentProfileID($iViewedID);
//-------------------Contact Agency End------------------------------
  }

	function getBlockCode_Mail() {

        	global $oTemplConfig, $site;

	$sActionText = '';
        if($_GET['Agency'])
             $_actionUrl=   BX_DOL_URL_ROOT . 'ContactAgency.php?Agency='.$_GET['Agency'];
        else if($_GET['ID'])
             $_actionUrl=   BX_DOL_URL_ROOT . 'ContactAgency.php?ID='.$_GET['ID'];
        else
            $_actionUrl=   BX_DOL_URL_ROOT . 'ContactAgency.php';
	$aForm = array(
		'form_attrs' => array(
			'id' => 'post_us_form',
			'action' => $_actionUrl,
			'method' => 'post',
		),
	    'params' => array (
	        'db' => array(
	            'submit_name' => 'do_submit',
	        ),
	    ),
		'inputs' => array(
			'name' => array(
				'type' => 'text',
				'name' => 'name',
				'caption' => _t('_Your name'),
				'required' => true,
			),
			'email' => array(
				'type' => 'text',
				'name' => 'email',
				'caption' => _t('_Your email'),
	            'required' => true,
	            'checker' => array(
	                'func' => 'email',
	                'error' => _t( '_Incorrect Email' )
	            ),
			),
			'message_subject' => array(
				'type' => 'text',
				'name' => 'subject',
				'caption' => _t('_message_subject'),
				'required' => true,
			),
			'message_text' => array(
				'type' => 'textarea',
				'name' => 'body',
				'caption' => _t('_Message text'),
				'required' => true,
			),
			'captcha' => array(
				'type' => 'captcha',
				'caption' => _t('_Enter what you see'),
				'name' => 'securityImageValue',
	            'required' => true,
	            'checker' => array(
	                'func' => 'captcha',
	                'error' => _t( '_Incorrect Captcha' ),
	            ),
			),
			'submit' => array(
				'type' => 'submit',
				'name' => 'do_submit',
				'value' => _t('_Submit'),
			),
		),
	);
//-------------------Contact Agency Start------------------------------

if($_GET['ID']) {
    $_page['header_text']="Contact Us";
//echo "SELECT EMAIL FROM Profiles WHERE Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =".$_GET['ID']." AND Profiles.AdoptionAgency=bx_groups_main.id )";
    $aMemberInfo  = db_arr("SELECT Email FROM Profiles WHERE Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =".$_GET['ID']." AND Profiles.AdoptionAgency=bx_groups_main.id )");
  
    $aProfileInfo1 = getProfileInfo($_GET['ID']);
    if($aProfileInfo1['Couple'])
    {
        $aProfileInfo2 = getProfileInfo($aProfileInfo1['Couple']);
        $aForm['inputs']['message_text']['caption']="Message for user";//.$aProfileInfo1['FirstName']." & ".$aProfileInfo2['FirstName'];
    }else{
    $aForm['inputs']['message_text']['caption']="Message for user";//.$aProfileInfo1['FirstName'];
    }
     
   $toEmail = $aMemberInfo['Email'];
}elseif($_GET['Agency']){
    
       $aMemberInfo  = db_arr("SELECT *
                                FROM `Profiles`
                                JOIN bx_groups_main
                                WHERE Profiles.`ID` = bx_groups_main.author_id
                                AND bx_groups_main.uri = '".$_GET['Agency']."'");
       $aForm['inputs']['message_text']['caption']="Message for ".$aMemberInfo['title'];
    $toEmail = $aMemberInfo['Email'];

}else{
    $toEmail=$site['Email'];
}
//echo "<pre>"; print_r($aMemberInfo);echo "</pre>";
	$oForm = new BxTemplFormView($aForm);
	$oForm->initChecker();
	if ( $oForm->isSubmittedAndValid() ) {
		$sSenderName	= process_pass_data($_POST['name'], BX_TAGS_STRIP);
		$sSenderEmail	= process_pass_data($_POST['email'], BX_TAGS_STRIP);
		$sLetterSubject = process_pass_data($_POST['subject'], BX_TAGS_STRIP);
		$sLetterBody	= process_pass_data($_POST['body'], BX_TAGS_STRIP);

	  	if($_GET['ID'] != '') {
		$adoptiveparent =  db_arr("SELECT *
                                FROM `Profiles`                                
                                WHERE `ID` = ".$_GET['ID']);
                }
	  	 $sLetterBody ="<html><body><p style='font-family:georgia;'><b>Message For Adoptive Parent: <a href = '".$site['url'].$adoptiveparent['NickName']."'>".$adoptiveparent['NickName']."</a></b></p>". $sLetterBody . " " . '<p>Thanks,</p>' . "<p>" . $sSenderName . "&#44; </p><p>" . '&#40;' .  $sSenderEmail." &#41;</p></html></body>";

		if (sendMail($toEmail, $sLetterSubject, $sLetterBody)) {
			$sActionKey = '_ADM_PROFILE_SEND_MSG';
                        $sLetterSubject = "Thank You";
                        $sLetterBody = "<html><body>
                         <p><b>Dear " . $sSenderName . "</b></p>   
                        <p>
                        Thank you for your message. We will get back to you within couple of days.
                        For additional questions, please reach out to ".$toEmail.".</p>
                        <p><b>Thank you for using our services!</b></p>   
                        <p>--</p>
                        <p style='font: bold 10px Verdana; color:red'>ParentFinder Community mail delivery system!!!
                        <br />Auto-generated e-mail, please, do not reply!!!</p>
                        </html></body>";
                      sendMail($sSenderEmail, $sLetterSubject, $sLetterBody);  
                            
		} else {
			$sActionKey = '_Email sent failed';
		}
		$sActionText = MsgBox(_t($sActionKey));
	}

	$sForm = $sActionText . $oForm->getCode();
    return DesignBoxContent(_t('_CONTACT_H1'), $sForm, $oTemplConfig->PageCompThird_db_num);
    }

    
    
    function getBlockCode_Dail() {
      $mem_id  = getMemberMembershipInfo(getLoggedId());
      //START -- Dispalying click to call block based on membership
      if($mem_id != 25 && getLoggedId()) {
        bx_import('BxBaseProfileView');
        return BxBaseProfileView::__Clicktocall();
     }
     //END -- Dispalying click to call block based on membership
    }



	function getBlockCode_ContactDetails() { 
        if(!isset($_GET['ID']) && !isset($_GET['Agency']) )
            return Null;
       
   //   $sAgencyId=getProfileInfo($_GET['ID']);
	if($_GET['Agency']){
            $sAgencyCon=db_arr("SELECT bx_groups_main.author_id AS ID,bx_groups_main.title AS AgencyTitle, `Profiles`.*
                                FROM `Profiles`
                                JOIN bx_groups_main
                                WHERE Profiles.`ID` = bx_groups_main.author_id
                                AND bx_groups_main.uri = '".$_GET['Agency']."'");
            $sAgencyInfo['AgencyTitle']=$sAgencyCon['AgencyTitle'];
        }else{
$sAgencyInfo=db_arr("SELECT bx_groups_main.author_id AS ID,bx_groups_main.title AS AgencyTitle FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =".$_GET['ID']." AND Profiles.AdoptionAgency=bx_groups_main.id");
	
$sAgencyCon=getProfileInfo($sAgencyInfo['ID']);
        }
   /*    $sAgencyInfo=db_arr("SELECT bx_groups_main.title AS AgencyTitle, Profiles.CONTACT_NUMBER AS ContactNumber FROM Profiles JOIN bx_groups_main WHERE Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =".$_GET['ID']." AND Profiles.AdoptionAgency=bx_groups_main.id )");*/
      //  $sAgencyInfo=db_arr("SELECT Profiles.CONTACT_NUMBER AS ContactNumber FROM Profiles JOIN bx_groups_main WHERE Profiles.ID IN SELECT bx_groups_main.title AS AgencyTitle, Profiles.CONTACT_NUMBER AS ContactNumber FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =".$_GET['ID']." AND Profiles.AdoptionAgency=bx_groups_main.id");
       
       $sHtml=<<<EOH
   <div class="bx_sys_default_padding">
         <div class="form_advanced_wrapper view_profile_form_wrapper">
         <table class="form_advanced_table" cellspacing="0" cellpadding="0">
         <tbody>
<tr>
                    <td class="caption">

                        Adoption Agency:
                    </td>

                    <td class="value">
                        <div class="clear_both"></div>
                         <div class="input_wrapper input_wrapper_value">
EOH;
      $sHtml.=    $sAgencyInfo['AgencyTitle'];
$sHtml.=<<<EOH
                            <div class="input_close input_close_value"></div>

                        </div>
                              <div class="clear_both"></div>
                    </td>
                </tr> 
EOH;

if($sAgencyCon['CONTACT_NUMBER'])
{
    $sHtml.=<<<EOH
    <tr>
                    <td class="caption">

                        Contact Number:
                    </td>

                    <td class="value">
                        <div class="clear_both"></div>
                         <div class="input_wrapper input_wrapper_value">
EOH;
$sHtml.=    format_phone($sAgencyCon['CONTACT_NUMBER']);
$sHtml.=<<<EOH
                            <div class="input_close input_close_value"></div>

                        </div>
                              <div class="clear_both"></div>
                    </td>
                </tr>
EOH;
}
$sHtml.=<<<EOH

        <tr>
                    <td class="caption">

                        Email:
                    </td>

                    <td class="value">
                        <div class="clear_both"></div>
                         <div class="input_wrapper input_wrapper_value">
EOH;
$sHtml.=    $sAgencyCon['Email'];
$sHtml.=<<<EOH
                            <div class="input_close input_close_value"></div>

                        </div>
                              <div class="clear_both"></div>
                    </td>
                </tr>

                       

         </tbody>
         </table>
       </div>
       </div>
EOH;
       return $sHtml;
    }

 }

$_page['name_index']	= 7; // choose your own index of template or leave if in doubt
$_page['header'] = 'Contact Us';
$_ni = $_page['name_index'];
$_page['css_name'] = array('ue30_sitemap.css');

$oEPV = new BxContactAgencyPageView();
$_page_cont[$_ni]['page_main_code'] = $oEPV->getCode();

PageCode();

?>