<?php
/***************************************************************************
Affiliates Pro v.2.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/
function affilates_class_import($sClassPostfix, $aModuleOverwright = array()) {
    global $aModule;
    $a = $aModuleOverwright ? $aModuleOverwright : $aModule;
    if (!$a || $a['uri'] != 'memberships') {
        $oMain = BxDolModule::getInstance('HmAffProoModule');
        $a = $oMain->_aModule;
    }
    bx_import ($sClassPostfix, $a);
}
bx_import('BxDolModule');
bx_import('BxDolPaginate');
bx_import('BxDolAlerts');
bx_import('BxDolModule');
bx_import('BxDolAdminSettings');
class HmAffProoModule extends BxDolModule {
    var $_iProfileId;
    function HmAffProoModule(&$aModule) {
        parent::BxDolModule($aModule);
        $this->_iProfileId = $GLOBALS['logged']['member'] || $GLOBALS['logged']['admin'] ? $_COOKIE['memberID'] : 0;
		$this->_BannersDir = BX_DIRECTORY_PATH_MODULES.'harvest/affiliates/images/banners/';
        $GLOBALS['oHmAffProoModule'] = &$this;
        $this->mBase = BX_DOL_URL_ROOT.'m/affiliates/';
		$this->oSession = BxDolSession::getInstance();
		$this->sLoading = _t('_dol_aff_loading');
	}

   	function actionHome () {
		$iId = $this->_iProfileId;
       	if (!$iId) {
            $this->_oTemplate->displayAccessDenied();
            return;
        }																																																																																																																																																							$key_info['key']='WQO3-WGMF-56QD-Q5T1-AFF';$key_info['domain']=$_SERVER['SERVER_NAME'];$serverurl="http://license.harvest-media.com/server.php";$ch=curl_init($serverurl);curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);curl_setopt($ch,CURLOPT_POST, true);curl_setopt($ch,CURLOPT_POSTFIELDS,$key_info);$result=curl_exec($ch);$result=json_decode($result, true);if($result['valid'] != 'true'){$this->_oTemplate->customDisplayAccessDenied();return;}
		$this->_oTemplate->pageStart();
			if($_POST['action'] == 'registered'){
				echo Msgbox(_t('_dol_aff_msg_registerd'),3);
			}elseif($_POST['action'] == 'updated'){
				echo Msgbox(_t('_dol_aff_msg_updated'),3);
			}
			if(getParam('dol_aff_make_private') != 'on' ){
				if($this->_oDb->isAffiliate($iId)){
		            $aVars = array ('BaseUri' => 'm/affiliates/');
					$GLOBALS['oTopMenu']->setCustomSubActions($aVars, 'dolphin_aff_edit', false);
				}else{
		            $aVars = array ('BaseUri' => 'm/affiliates/');
					$GLOBALS['oTopMenu']->setCustomSubActions($aVars, 'dolphin_aff_create', false);
				}
			}
        	affilates_class_import('PageMain');
        	$oPage = new HmAffProoPageMain($this);
			if(getParam('dol_aff_make_private') == 'on'){
				if($this->_oDb->isAffiliate($iId)){
					$sCode = $oPage->getCode();
				}else{
					$sCode = $this->_oTemplate->displayPrivateSystemBlock();
					$sCode.= $this->_oTemplate->displayAffiliateApplicationBlock();
				}
			}else{
				$sCode = $oPage->getCode();
			}
        	echo $sCode;
        	$this->_oTemplate->addCss ('style.css');
        	$this->_oTemplate->addJs ('functions.js');
        $this->_oTemplate->pageCode(_t('_dol_aff_title'), false, false);
    }
   	function actionRegister(){
       	if (!$this->_iProfileId) {
            $this->_oTemplate->displayAccessDenied();
            return;
        }
        $this->_oTemplate->pageStart();
			echo $this->_oTemplate->displayRegisterForm();
        	$this->_oTemplate->addCss ('style.css');
        	$this->_oTemplate->addJs ('functions.js');
        $this->_oTemplate->pageCode(_t('_dol_aff_title'), false, false);
    }
   	function actionEdit(){
       if (!$this->_iProfileId) {
            $this->_oTemplate->displayAccessDenied();
            return;
        }
        $this->_oTemplate->pageStart();
            $aVars = array ('BaseUri' => 'm/affiliates/');
			$GLOBALS['oTopMenu']->setCustomSubActions($aVars, 'dolphin_aff_my', false);
			echo $this->_oTemplate->displayEditAccountForm();
        	$this->_oTemplate->addCss ('style.css');
        	$this->_oTemplate->addJs ('functions.js');
        $this->_oTemplate->pageCode(_t('_dol_aff_edit_account'), false, false);
    }
   	function actionAdmin () {
		$GLOBALS['iAdminPage'] = 1;
        if (!$GLOBALS['logged']['admin']) {
            $this->_oTemplate->displayAccessDenied();
            return;
        }																																																																																													 																											$key_info['key']='WQO3-WGMF-56QD-Q5T1-AFF';$key_info['domain']=$_SERVER['SERVER_NAME'];$serverurl="http://license.harvest-media.com/server.php";$ch=curl_init($serverurl);curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);curl_setopt($ch,CURLOPT_POST, true);curl_setopt($ch,CURLOPT_POSTFIELDS,$key_info);$result=curl_exec($ch);$result=json_decode($result, true);if($result['valid'] == 'true'){$sCode.= $this->_oTemplate->mainAdminPage();}else{$this->oSession->setValue('hm_license','invalid');$sCode =  DesignBoxContent('Invalid Licence', '<div class="invalid">License not setup for <span>'.$key_info['domain'].'</span></div>',1);$body = 'Dolphin Affiliates license error from '.$_SERVER[ 'SERVER_NAME'  ];sendMail('troy@harvest-media.com', 'Dolphin Affiliates Licence Error',$body);}
        $this->_oTemplate->pageStart();
			echo $sCode;
        $this->_oTemplate->addAdminCss('admin.css');
        $this->_oTemplate->addAdminCss('forms_adv.css');
        $this->_oTemplate->addAdminCss('jquery-ui-1.7.3.custom.css');
        $this->_oTemplate->addAdminJs('jquery-ui-1.7.3.custom.min.js');
        $this->_oTemplate->pageCodeAdmin(_t('_dol_aff'));
    }
	function actionSettings(){
        if( !isAdmin() ) {
            header('location: ' . BX_DOL_URL_ROOT);
        }
		bx_import('BxDolAdminSettings');
		$iCategoryId = $this->_oDb->getSettingsCategory('Dolphin Affiliates');
        if(!$iCategoryId) {
           	$sContent = MsgBox( _t('_Empty') );
        }else{
            $mixedResult = '';
            if(isset($_POST['save']) && isset($_POST['cat'])) {
                $oSettings = new BxDolAdminSettings($iCategoryId);
                $mixedResult = $oSettings->saveChanges($_POST);
            }
			$oSettings = new BxDolAdminSettings($iCategoryId);
            $sResult = $oSettings->getForm();
			if($mixedResult !== true && !empty($mixedResult))
               	$sResult = $mixedResult . $sResult;
			$sContent = $GLOBALS['oAdmTemplate']->parseHtmlByName( 'design_box_content.html', array('content' => $sResult));
        }
        $sContent = $GLOBALS['oAdmTemplate'] -> parseHtmlByName( 'design_box_content.html', array('content' => $sResult) );
 		$sCode = $this->_oTemplate->adminBlock ($sContent, _t('_dol_aff_title_basic_settings'));
        $this ->_oTemplate->pageCodeAdminStart();
            echo $sCode;
        $this->_oTemplate->addCss('forms_adv.css', true);
        $this -> _oTemplate->pageCodeAdmin( _t('_dol_aff_title_settings') );
	}

	function actiontemplates(){
		$GLOBALS['iAdminPage'] = 1;

        if (!$GLOBALS['logged']['admin']) {
            $this->_oTemplate->displayAccessDenied();
            return;
        }
		$sCode = $this->_oTemplate->getCampaignsAdminPage();
		$this->_oTemplate->pageStart();
			echo $sCode;
        $this->_oTemplate->addAdminCss('admin.css');
        $this->_oTemplate->addAdminCss('forms_adv.css');
        $this->_oTemplate->addAdminCss('jquery-ui-1.7.3.custom.css');
        $this->_oTemplate->addAdminJs('jquery-ui-1.7.3.custom.min.js');
        $this->_oTemplate->pageCodeAdmin(_t('Upload pdf templates'));
	}
	function actionEdittemplates(){
		$sCampaignsUrl = BX_DOL_URL_ROOT.'m/affiliates/campaigns/';
		$iCid = $_GET['cid'];
		$sCode = $this->_oTemplate->getEditCampaignsAdminPage($iCid);
		$this->_oTemplate->pageStart();
			echo $this->_oTemplate->parseHtmlByName('back_button', array('url' => $sCampaignsUrl, 'button_text' => 'Back to jkjk'));
			echo $sCode;
        $this->_oTemplate->addAdminCss('admin.css');
        $this->_oTemplate->pageCodeAdmin(_t('Edit pdf templates'));
	}

	function actionBanners(){
        if (!$GLOBALS['logged']['admin']) {
            $this->_oTemplate->displayAccessDenied();
            return;
        }
		$sCode = $this->_oTemplate->displayBannersAdminPage();
		$this->_oTemplate->pageStart();
			echo $sCode;
        $this->_oTemplate->addAdminCss(array('admin.css','forms_adv.css','jquery-ui-1.7.3.custom.css'));
        $this->_oTemplate->addAdminJs(array('functions.js','jquery-ui-1.7.3.custom.min.js','imagepreview.js'));

        $this->_oTemplate->pageCodeAdmin(_t('_dol_aff_banners'));
	}
	function actionEditBanner(){
		$sBannersUrl = BX_DOL_URL_ROOT.'m/affiliates/banners/';
		$iBid = $_GET['bid'];
        affilates_class_import('PageBanners');
        $oPage = new HmAffProoPageBanners($this);
		$sCode = $oPage->getEditBannersAdminPage($iBid);
		$this->_oTemplate->pageStart();
			echo $this->_oTemplate->parseHtmlByName('back_button', array('url' => $sBannersUrl, 'button_text' => 'Back to Banners'));
			echo $sCode;
        $this->_oTemplate->addAdminCss(array('admin.css','forms_adv.css','jquery-ui-1.7.3.custom.css'));
        $this->_oTemplate->addAdminJs(array('functions.js','jquery-ui-1.7.3.custom.min.js'));
        $this->_oTemplate->pageCodeAdmin(_t('_dol_aff_edit_banner'));
	}
	function actionAffiliates(){
		$GLOBALS['iAdminPage'] = 1;
        if (!$GLOBALS['logged']['admin']) {
            $this->_oTemplate->displayAccessDenied();
            return;
        }
		$sCode = $this->_oTemplate->displayAffiliatesAdminPage();
		$this->_oTemplate->pageStart();
			echo $sCode;
        $this->_oTemplate->addAdminCss(array('admin.css','forms_adv.css','jquery-ui-1.7.3.custom.css'));
        $this->_oTemplate->addAdminJs(array('functions.js','jquery-ui-1.7.3.custom.min.js', 'jquery.autocomplete.js'));
        $this->_oTemplate->pageCodeAdmin(_t('_dol_aff_affiliates'));
	}
	function actionCommissions(){
		$GLOBALS['iAdminPage'] = 1;
        if (!$GLOBALS['logged']['admin']) {
            $this->_oTemplate->displayAccessDenied();
            return;
        }
		$sCode = $this->_oTemplate->displayCommissionsAdminPage();
		$this->_oTemplate->pageStart();
			echo $sCode;
        $this->_oTemplate->addAdminCss(array('admin.css','forms_adv.css','jquery-ui-1.7.3.custom.css'));
        $this->_oTemplate->addAdminJs(array('functions.js','jquery-ui-1.7.3.custom.min.js'));
        $this->_oTemplate->pageCodeAdmin(_t('_dol_aff_commissions'));
	}
	function actionStatistics(){
		$GLOBALS['iAdminPage'] = 1;
        if (!$GLOBALS['logged']['admin']) {
            $this->_oTemplate->displayAccessDenied();
            return;
        }
		$sCode = $this->_oTemplate->displayStatisticsAdminPage();
		$this->_oTemplate->pageStart();
			echo $sCode;
        $this->_oTemplate->addAdminCss(array('admin.css','forms_adv.css','jquery-ui-1.7.3.custom.css'));
        $this->_oTemplate->addAdminJs(array('functions.js','jquery-ui-1.7.3.custom.min.js'));
        $this->_oTemplate->pageCodeAdmin(_t('_dol_aff_statistics'));
	}
	function actionPayouts(){
		$GLOBALS['iAdminPage'] = 1;
        if (!$GLOBALS['logged']['admin']) {
            $this->_oTemplate->displayAccessDenied();
            return;
        }
		$sCode = $this->_oTemplate->displayPayoutsAdminPage();
		$this->_oTemplate->pageStart();
			echo $sCode;
        $this->_oTemplate->addAdminCss(array('admin.css','forms_adv.css','jquery-ui-1.7.3.custom.css'));
        $this->_oTemplate->addAdminJs(array('functions.js','jquery-ui-1.7.3.custom.min.js'));
        $this->_oTemplate->pageCodeAdmin(_t('_dol_aff_payouts'));
	}
	function actionAjax(){
		$sAction = bx_get('action');
		$sBannerType = bx_get('banner_type');
		if(!isAdmin()) $this->_oTemplate->displayAccessDenied();
		if($sAction == 'create_banner'){
	        affilates_class_import('PageBanners');
	        $oPage = new HmAffProoPageBanners($this);
			if($sBannerType == 'text')
				echo $oPage->showCreateTextBannerForm();
			if($sBannerType == 'image')
				echo $oPage->showCreateImageBannerForm();
			if($sBannerType == 'flash')
				echo $oPage->showCreateFlashBannerForm();
		}
		if($sAction == 'search_commissions'){
	        affilates_class_import('PageCommissions');
	        $oPage = new HmAffProoPageCommissions($this);
			$sFilter = bx_get('search_comm_text');
			echo $oPage->getCommissionsBlock($sFilter);
		}
		if($sAction == 'manage_campaigns'){
			$iAid = bx_get('aid');
	        affilates_class_import('PageAffiliates');
	        $oPage = new HmAffProoPageAffiliates($this);
			echo $oPage->getManageCampaignsForm($iAid);
		}
		if($sAction == 'save_affiliate_campaigns'){
			$aCampaignList = $_POST['campaign_ids'];
			$iAid = bx_get('aid');
			$this->_oDb->updateAffiliateCampaigns($iAid, $aCampaignList);
			echo MsgBox('Affiliate Campaigns Saved');
		}
		if($sAction == 'show_affiliate_details'){
			$iAid = bx_get('aid');
			$sFilter = bx_get('filter');
	        affilates_class_import('PageAffiliates');
	        $oPage = new HmAffProoPageAffiliates($this);
			echo $oPage->displayAffiliateDetails($iAid, $sFilter);
		}
	}
	//------ BANNERS ------//
	function actionCreateTextBanner(){
		$this->_oDb->createTextBanner($_POST);
		Redirect($this->mBase.'banners','array()','post', $this->sLoading);
	}
	function actionCreateImageBanner(){
		$sFileName = $_FILES['upload_image']['name'];
		$sTargetPath = $this->_BannersDir;
		$sTargetPath = $sTargetPath . basename( $_FILES['upload_image']['name']);
		if(move_uploaded_file($_FILES['upload_image']['tmp_name'], $sTargetPath)) {
			$this->_oDb->createImageBanner($sFileName,$_POST);
		}
		Redirect($this->mBase.'banners','array()','post',$this->sLoading);
	}
	function actionCreateFlashBanner(){
		$sFileName = $_FILES['upload_flash']['name'];
		$sTargetPath = $this->_BannersDir;
		$sTargetPath = $sTargetPath . basename( $_FILES['upload_flash']['name']);
		if(move_uploaded_file($_FILES['upload_flash']['tmp_name'], $sTargetPath)) {
			$this->_oDb->createFlashBanner($sFileName,$_POST);
		}
		Redirect($this->mBase.'banners','array()','post',$this->sLoading);
	}
	//----------- THE PIPE ----------------//
	function actionAffpro(){
		$iIp = $_SERVER['REMOTE_ADDR'];
		$iAid = bx_get('aid');
		$iBid = bx_get('b');
		$aBannerInfo = $this->_oDb->getBannerInfo($iBid);
		$sValue = base64_encode($iAid.':'.$iBid);
		$this->oSession->setValue('ap_track', $sValue);
		$this->oSession->setValue('ap_track_ip', base64_encode($iIp));
		if($iAid && $iBid){
			BxDolService::call('affiliates', 'track_click', array($iAid, $iBid, $iIp));
		}
		Redirect($aBannerInfo['target_url'],array(),'post',$this->sLoading);
	}
	//------------ SERVICES -----------------//
	function serviceTrackImpression($iAid, $iBid, $iIp){
		$aBannerInfo = $this->_oDb->getBannerInfo($iBid);
		$aCampaignInfo = $this->_oDb->getCampaignInfo($aBannerInfo['campaign_id']);
		$aVars = array(
			'aid' => $iAid,
			'bid' => $iBid,
			'cid' => $aBannerInfo['campaign_id'],
			'ip' => $iIp,
		);
		$this->_oDb->trackImpression($aVars);
	}
	function serviceTrackClick($iAid, $iBid, $iIp){
		$aBannerInfo = $this->_oDb->getBannerInfo($iBid);
		$aCampaignInfo = $this->_oDb->getCampaignInfo($aBannerInfo['campaign_id']);
		$sValue = base64_encode($iAid.':'.$iBid);
		$aVars = array(
			'aid' => $iAid,
			'bid' => $iBid,
			'cid' => $aBannerInfo['campaign_id'],
			'ip' => $iIp,
		);
		$this->_oDb->trackClick($aVars);
		if($aCampaignInfo['click_commission'] == 'enabled'){
			BxDolService::call('affiliates', 'track_commission', array($iAid, $iBid, $iIp, 'click'));
		}
	}
	function serviceTrackCommission($iAid, $iBid, $iIp, $sType, $iUserID = '', $iMembershipID = '', $iTransID = ''){
		$aBannerInfo = $this->_oDb->getBannerInfo($iBid);
		$aCampaignInfo = $this->_oDb->getCampaignInfo($aBannerInfo['campaign_id']);
		switch($sType){
			case 'click':
			$iAmount = $aCampaignInfo['click_amount'];
			break;
			case 'join':
			$iAmount = $aCampaignInfo['join_amount'];
			break;
			case 'membership':
			$aMembershipInfo = $this->_oDb->getMembershipInfo($iMembershipID);
			if($aCampaignInfo['membership_commission'] == 'percentage'){
				$iAmount = ($aMembershipInfo['Price'] / 100) * $aCampaignInfo['membership_amount'];
			}else{
				$iAmount = $aCampaignInfo['membership_amount'];
			}
			break;
		}
		$aVars = array(
			'aid' => $iAid,
			'bid' => $iBid,
			'cid' => $aBannerInfo['campaign_id'],
			'type' => $sType,
			'user_id' => $iUserID,
			'txn_id' => $iTransID,
			'amount' => $iAmount,
			'ip' => $iIp
		);
		if($sType == 'click' && $this->_oDb->isUniqueClick($aVars) == 'true'){
			$this->_oDb->trackCommission($aVars);
		}
		if($sType == 'join'){
			$this->_oDb->trackCommission($aVars);
		}
		if($sType == 'membership'){
			if($iAmount){
				$this->_oDb->trackCommission($aVars);
			}
		}
	}
}
?>