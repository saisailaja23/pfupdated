<?
/***************************************************************************
Affiliates Pro v.1.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/
bx_import ('BxDolTwigTemplate');
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_PLUGINS . 'Services_JSON.php' );
bx_import('BxTemplSearchResult');
bx_import('BxTemplBrowse');
bx_import('BxTemplTags');
bx_import('BxTemplFunctions');
bx_import('BxDolAlerts');
bx_import('BxDolEmailTemplates');

class HmAffProTemplate extends BxDolTwigTemplate {
    
	function HmAffProTemplate(&$oConfig, &$oDb) {
	    parent::BxDolTwigTemplate($oConfig, $oDb);
		$this->mBase = BX_DOL_URL_ROOT.'m/affiliates/';
		$this->sCur = getParam('currency_sign');
        $GLOBALS['oHmAffProModule'] = &$this;
        $this->sBannersUrl = BX_DOL_URL_MODULES.'harvest/affiliates/images/banners/';
        $this->sBannersPath = BX_DIRECTORY_PATH_MODULES.'harvest/affiliates/images/banners/';
    }
    function pageCodeAdminStart(){
        ob_start();
    }
	function adminBlock ($sContent, $sTitle, $aMenu = array()){
      	return DesignBoxAdmin($sTitle, $sContent, $aMenu);
    }
	function pageCodeAdmin($sTitle){
       	global $_page;        
        global $_page_cont;
		$_page['name_index'] = 9; 
		$_page['header'] = $sTitle ? $sTitle : $GLOBALS['site']['title'];
        $_page['header_text'] = $sTitle;
		$_page_cont[$_page['name_index']]['page_main_code'] = ob_get_clean();
		PageCodeAdmin();
    }
	function addAdminCss ($sName){
     	parent::addAdminCss($sName);
    }
	function addAdminJs($sName){
     	parent::addAdminJs($sName);
    }
	
    function parseHtmlByName ($sName, $aVars) {
     	return parent::parseHtmlByName ($sName, $aVars);
    }

	/*--------------------------------------------------------	
		ADMIN MAIN METHODS
	--------------------------------------------------------*/

	function mainAdminPage(){
    	global $oAdmTemplate; 
		$aVars = array(
			'total_aff' => $this->_oDb->getAffiliatesCount('total'),
			'active_aff' =>  $this->_oDb->getAffiliatesCount('active'),
			'pending_aff' =>  $this->_oDb->getAffiliatesCount('pending'),
			'affiliates_url' => $this->mBase.'affiliates/',
			'total_imp' => $this->_oDb->getImpressionsCount('total'),
			'total_clicks' =>  $this->_oDb->getClicksCount('total'),
			'total_com' => $this->_oDb->getCommissionsCount('total'),	
			'mth_imp' => $this->_oDb->getImpressionsCount('mth'),
			'mth_clicks' =>  $this->_oDb->getClicksCount('mth'),
			'mth_com' => $this->_oDb->getCommissionsCount('mth')
		);
		$sAffContent = $this->parseHtmlByName('admin_aff_info',$aVars);
		return $this->adminBlock($sAffContent, _t('_dol_aff_adm_program_info'));
	}
	function getCampaignsAdminPage(){
		if($_POST['adm_campaign_del'] && is_array($_POST['campaigns'])){
			$this->_oDb->deleteCampaigns($_POST['campaigns']);
		}
		if($_POST['adm_campaign_edit'] && is_array($_POST['campaigns'])){
			Redirect($this->mBase.'edit_campaign?cid='.$_POST['campaigns']['0'],'',post, _t('_dol_aff_loading'));
		}
		$aCampaigns = $this->_oDb->getCampaigns();
		$sMsg = (count($aCampaigns) > '0') ?  '' : MsgBox(_t('_dol_aff_no_campaigns_created'));
		$iStart = ($_GET['start']) ? $_GET['start'] : 0;
		$iPerPage = ($_GET['per_page']) ? $_GET['per_page'] : 10;
		$sDateFormat = getLocaleFormat(BX_DOL_LOCALE_DATE, BX_DOL_LOCALE_DB);
		$iCount = count($aCampaigns);
		$iCounter = '0';
		$aRange = range($iStart,$iStart+($iPerPage-1));
		foreach($aCampaigns as $aCampaign){
			if(!in_array($iCounter++,$aRange)) continue;
			$aBanners = $this->_oDb->getBannersForCampaign($aCampaign['id']);
			$iBanners = count($aBanners);
			$sBannersContent = ($iBanners == '0') ? _t('_dol_aff_add_banners') : $iBanners;
			$sMembershipComDisplay = '';
			if($aCampaign['membership_commission'] != 'disabled'){
				$sMembershipComDisplay = ($aCampaign['membership_commission'] == 'fixed') ? $this->sCur.number_format($aCampaign['membership_amount'],2).' per ' : $aCampaign['membership_amount'].'% of ';
			}
		
		   	$aItems[] = array(
		      	'id' 		=> $aCampaign['id'],
				'name' 		=> $aCampaign['name'],
				'base'		=> $this->mBase,
				'banners' 	=> $sBannersContent,
				'bx_if:click' => array(
					'condition' => ($aCampaign['click_commission'] == 'enabled'),
			        'content' => array(
			            'click_amount' => $this->sCur.number_format($aCampaign['click_amount'],2),
			        ),
				),
				'bx_if:join' => array(
					'condition' => ($aCampaign['join_commission'] == 'enabled'),
			        'content' => array(
			            'join_amount' => $this->sCur.number_format($aCampaign['join_amount'],2),
			        ),
				),
				'bx_if:membership' => array(
					'condition' => ($sMembershipComDisplay != ''),
			        'content' => array(
			            'membership_display' => $sMembershipComDisplay,
			        ),
				),
		      	'status' 	=> $aCampaign['status'],
		    );
		}
	    $oPaginate = new BxDolPaginate(array(
	      	'start' => $iStart,
			'count' => $iCount,
			'per_page' => $iPerPage,
			'sorting'    => 'last',
			'per_page_changer'   => true,
			'page_reloader'      => true,
		    'page_url' => $this->mBase.'campaigns/?start={start}&per_page={per_page}',
	    ));
	    $sPaginate = $oPaginate->getPaginate();
		$aButtons = array(
	        'adm_campaign_edit' => _t('_dol_aff_edit_campaign'),
	        'adm_campaign_del' => _t('_dol_aff_but_delete')
	    ); 
		$sControls = BxTemplSearchResult::showAdminActionsPanel('campaigns_form', $aButtons, 'campaigns', true ,false); 

		$aResult = array(
			'bx_repeat:campaigns' => $aItems,
			'paginate' => $sPaginate,
			'controls' => $sControls,
			'message' => $sMsg
		);
	    $sCode = DesignBoxAdmin(_t('_dol_aff_campaigns'), $this->parseHtmlByName('admin_campaigns', $aResult));

		$aForm = $this->getCreateCampaignForm();
		$aVars = array(
			'create_campaign' => $aForm
		); 
	    $sCode.= DesignBoxAdmin(_t('_dol_aff_create_campaign'), $this->parseHtmlByName('admin_create_campaign', $aVars));
		return $sCode;
	}
	function getEditCampaignsAdminPage(){
		$iCid = $_GET['cid'];
		$aCampaignInfo = $this->_oDb->getCampaignInfo($iCid);		
		$aForm = $this->getEditCampaignForm($aCampaignInfo);
		$aVars = array(
			'edit_campaign' => $aForm
		); 
		$sTitle = _t('_dol_aff_edit_campaign').' "'.$aCampaignInfo['name'].'"';
	    $sCode.= DesignBoxAdmin($sTitle, $aForm);
		return $sCode;
	}
	function displayBannersAdminPage(){
        affilates_class_import('PageBanners');
        $oPage = new HmAffProPageBanners($this);
		$sCode.= $oPage->getCreateBannersBlock();
		$sCode.= $oPage->getBannersBlock();
		return $sCode;
	}
	function displayAffiliatesAdminPage(){
        affilates_class_import('PageAffiliates');
        $oPage = new HmAffProPageAffiliates($this);
		$sCode.= $oPage->getAffiliatesBlock();
		$sCode.= $oPage->getCreateAffiliateBlock();
		return $sCode;
	}
	function displayCommissionsAdminPage(){
        affilates_class_import('PageCommissions');
        $oPage = new HmAffProPageCommissions($this);
		$sCode.= $oPage->getSearchBlock();
		$sCode.= DesignBoxAdmin(_t('_dol_aff_commissions'), $oPage->getCommissionsBlock());
		return $sCode;
	}
	function displayStatisticsAdminPage(){
        affilates_class_import('PageStatistics');
        $oPage = new HmAffProPageStatistics($this);
		$sCode.= $oPage->getStatisticsBlock();
		return $sCode;
	}
	function displayPayoutsAdminPage(){
        affilates_class_import('PagePayouts');
        $oPage = new HmAffProPagePayouts($this);
		$sCode.= $oPage->getPayoutsBlock();
		$sCode.= $oPage->getPayoutsHistoryBlock();
		return $sCode;
	}

	/*--------------------------------------------------------	
		ADMIN SECONDARY METHODS
	--------------------------------------------------------*/

	function getCreateCampaignForm(){
        affilates_class_import('FormInputs');
        $oInputs = new HmAffProFormInputs;
		$aInputs = $oInputs->getCreateCampaignInputs();	
      	$aForm = array(
            'form_attrs' => array(
                'name'     => 'create_campaign', 
                'method'   => 'post',
                'action'   => NULL,
            ),
			'params' => array (
            	'db' => array(
   					'submit_name' => 'create_campaign', 
                ),
        	),
			'inputs' => $aInputs,       
		);	
        $oForm = new BxTemplFormView ($aForm);
        $oForm->initChecker();
		if ($oForm->isSubmittedAndValid ()) {
			$sValidate = $this->validateCreateCampaignForm($_POST);
			if($sValidate == 'true'){		
				$this->_oDb->createCampaign($_POST);
				Redirect($this->mBase.'campaigns', array('action'=>'created'), post, 'Creating Campaign');
			}else{
				$sValidateMsg = '<script>alert("'.$sValidate.'");</script>';
			}
       	}
        $sCode.= $oForm->getCode(); 		
    	return $sValidateMsg.$sCode;		
	}
	function validateCreateCampaignForm($aVars){
		if($aVars['click_commission'] == 'enabled' && $aVars['click_amount'] == '')
			return 'Click commission Enabled. Please enter a valid Click commission amount';
		if($aVars['join_commission'] == 'enabled' && $aVars['join_amount'] == '')
			return 'Join commission Enabled. Please enter a valid Join commission amount';
		if($aVars['membership_commission'] != 'disabled' && $aVars['membership_amount'] == '')
			return 'Membership commission Enabled. Please enter a valid Membership commission amount';
		if($aVars['membership_commission'] == 'disabled' && $aVars['click_commission'] == 'disabled' && $aVars['join_commission'] == 'disabled')
			return 'Please set at least one commission for the campaign';		
		return 'true';
	}
	function getEditCampaignForm($aCampaignInfo){
        affilates_class_import('FormInputs');
        $oInputs = new HmAffProFormInputs;
		$aInputs = $oInputs->getEditCampaignInputs($aCampaignInfo);	
      	$aForm = array(
            'form_attrs' => array(
                'name'     => 'edit_campaign', 
                'method'   => 'post',
                'action'   => NULL,
            ),
			'params' => array (
            	'db' => array(
   					'submit_name' => 'edit_campaign', 
                ),
        	),
			'inputs' => $aInputs,       
		);	
        $oForm = new BxTemplFormView ($aForm);
        $oForm->initChecker();
		if ($oForm->isSubmittedAndValid ()) {
			$sValidate = $this->validateCreateCampaignForm($_POST);
			if($sValidate == 'true'){		
				$this->_oDb->updateCampaign($aCampaignInfo['id'],$_POST);
				Redirect($this->mBase.'campaigns', array('action'=>'updated'), post, 'Updating Campaign');
			}else{
				$sValidateMsg = '<script>alert("'.$sValidate.'");</script>';
			}
       	}
        $sCode.= $oForm->getCode(); 		
    	return $sValidateMsg.$sCode;		
	}

	/*--------------------------------------------------------	
		FRONTEND METHODS
	--------------------------------------------------------*/
	function displayPrivateSystemBlock(){
		return MsgBox(_t('_dol_aff_private_system'));
	}
	function displayAffiliateApplicationBlock(){
		global $oTemplConfig, $site;
        affilates_class_import('FormInputs');
        $oInputs = new HmAffProFormInputs;
		$aInputs = $oInputs->getAffiliateApplicationInputs();
		$aForm = array(
			'form_attrs' => array(
				'id' => 'post_us_form',
				'action' => BX_DOL_URL_ROOT . 'contact.php',
				'method' => 'post',
			),
		    'params' => array (
		        'db' => array(
		            'submit_name' => 'do_submit',
		        ),
		    ),
			'inputs' => $aInputs,
		);
	
		$oForm = new BxTemplFormView($aForm);
		$oForm->initChecker();
		if ( $oForm->isSubmittedAndValid() ) {
			$sSenderName	= process_pass_data($_POST['name'], BX_TAGS_STRIP);
			$sSenderEmail	= process_pass_data($_POST['email'], BX_TAGS_STRIP);
			$sLetterSubject = process_pass_data($_POST['subject'], BX_TAGS_STRIP);
			$sLetterBody	= process_pass_data($_POST['body'], BX_TAGS_STRIP);
	
			$sLetterBody = $sLetterBody . "<br/>" . '============' . "<br/>" . _t('_from') . ' ' . $sSenderName . "\r\n" . 'with email ' .  $sSenderEmail;
	
			if (sendMail($site['email'], $sLetterSubject, $sLetterBody)) {
				$sActionKey = '_ADM_PROFILE_SEND_MSG';
			} else {
				$sActionKey = '_Email sent failed';
			}
			$sActionText = MsgBox(_t($sActionKey));
		}	
		$sForm = $sActionText . $oForm->getCode();
	    return DesignBoxContent(_t('_CONTACT_H1'), $sForm, $oTemplConfig->PageCompThird_db_num);
	}
	function displayRegisterForm(){
		$aForm = $this->getRegistrationForm();
		$aVars = array(
			'reg_form' => $aForm
		);   
   		return DesignBoxContent(_t('_dol_aff_register_acc'), $this->parseHtmlByName('register_form',$aVars),1);	
	}
	function displayEditAccountForm(){
		$aForm = $this->getEditAccountForm();
		$aVars = array(
			'edit_form' => $aForm
		);    		
    	return DesignBoxContent(_t('_dol_aff_edit_account'), $this->parseHtmlByName('edit_form',$aVars),1);	
	}
	function getRegistrationForm(){
		$iId = getLoggedId();
        affilates_class_import('FormInputs');
        $oInputs = new HmAffProFormInputs;
		$aInputs = $oInputs->getRegistrationInputs();	
      	$aForm = array(
            'form_attrs' => array(
                'name'     => 'register_aff', 
                'method'   => 'post',
                'action'   => NULL,
            ),
			'params' => array (
            	'db' => array(
   					'submit_name' => 'register_aff', 
                ),
        	),
			'inputs' => $aInputs,       
		);	
        $oForm = new BxTemplFormView ($aForm);
        $oForm->initChecker();
		if ($oForm->isSubmittedAndValid ()) {
			$this->_oDb->registerAffiliate($iId,$_POST);
			Redirect(BX_DOL_URL_ROOT.'m/affiliates/',array('action'=>'registered'),post);         
       	}else{
            $sCode = $oForm->getCode();  
     	}  		
    	return $sCode;
	}
	function getEditAccountForm(){
		$iId = getLoggedId();
        affilates_class_import('FormInputs');
        $oInputs = new HmAffProFormInputs;
		$aInputs = $oInputs->getEditAccountInputs();	
      	$aForm = array(
            'form_attrs' => array(
                'name'     => 'edit_aff', 
                'method'   => 'post',
                'action'   => NULL,
            ),
			'params' => array (
            	'db' => array(
   					'submit_name' => 'edit_aff', 
                ),
        	),
			'inputs' => $aInputs,       
		);	
        $oForm = new BxTemplFormView ($aForm);
        $oForm->initChecker();
		if ($oForm->isSubmittedAndValid ()) {
			$this->_oDb->updateAffiliate($iId,$_POST);
			Redirect(BX_DOL_URL_ROOT.'m/affiliates/',array('action'=>'updated'),post);             
       	}else{
            $sCode = $oForm->getCode();  
     	}  		
    	return $sCode;
	}
	/*--------------------------------------------------------	
		ADMIN PREVIEW BANNER GENERATION
	--------------------------------------------------------*/
	function generateTextBannerPreview($iBid){
		$aBannerInfo = $this->_oDb->getBannerInfo($iBid);
		$aVars = array(
			'id' => $aBannerInfo['id'],
		    'title' => $aBannerInfo['text_title'],
		    'details' => $aBannerInfo['text_details'],
		    'target' => $aBannerInfo['target_url'],
		);    		
    	return $this->parseHtmlByName('preview_text_banner',$aVars);			
	}
	function generateImageBannerPreview($iBid){
		$aBannerInfo = $this->_oDb->getBannerInfo($iBid);
		$aVars = array(
			'id' => $aBannerInfo['id'],
		    'title' => $aBannerInfo['text_title'],
		    'details' => $aBannerInfo['text_details'],
		    'target' => $aBannerInfo['target_url'],
		    'image' => $this->sBannersUrl.$aBannerInfo['filename'],
		);    		
    	return $this->parseHtmlByName('preview_image_banner',$aVars);			
	}
	function generateFlashBannerPreview($iBid){
		$aBannerInfo = $this->_oDb->getBannerInfo($iBid);
		$sFile = $this->sBannersPath.$aBannerInfo['filename'];
		$aInfo = getimagesize($sFile);
		list($iWidth, $iHeight) = $aInfo;
		$aVars = array(
			'id' => $aBannerInfo['id'],
		    'title' => $aBannerInfo['text_title'],
		    'details' => $aBannerInfo['text_details'],
		    'target' => $aBannerInfo['target_url'],
		    'flash' => $this->sBannersUrl.$aBannerInfo['filename'],
		    'width' => $iWidth,
		    'height' => $iHeight,
		);    		
    	return $this->parseHtmlByName('preview_flash_banner',$aVars);			
	}
	/*--------------------------------------------------------	
		MISC
	--------------------------------------------------------*/
    function customDisplayAccessDenied () {
        $sTitle = _t('_no_license_affiliates');	
        $GLOBALS['_page'] = array(
            'name_index' => 0,
            'header' => $sTitle,
            'header_text' => $sTitle
        );
		
		$aVars = array(
			'message' => MsgBox($sTitle),
		);
		
        $GLOBALS['_page_cont'][0]['page_main_code'] = $this->parseHtmlByName('no_license',$aVars);
       	$this->addCss('dolphin_subs.css');
        PageCode();
        exit;
    }

}
?>
