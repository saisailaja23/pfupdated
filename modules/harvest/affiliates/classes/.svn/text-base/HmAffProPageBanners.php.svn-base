<?php
/***************************************************************************
Affiliates Pro v.1.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/
bx_import('HmAffProTemplate');
class HmAffProPageBanners extends HmAffProTemplate{
  
	function HmAffProPageBanners(){
		$this->_oMain = BxDolModule::getInstance("HmAffProModule");
		$this->mBase = BX_DOL_URL_ROOT.'m/affiliates/';
		$this->_oDb = $this->_oMain->_oDb;
		$this->_oTemplate = $this->_oMain->_oTemplate;
		$this->imgs = BX_DOL_URL_MODULES.'harvest/affiliates/images/';
        $this->sBannersUrl = BX_DOL_URL_MODULES.'harvest/affiliates/images/banners/';
        $this->sBannersPath = BX_DIRECTORY_PATH_MODULES.'harvest/affiliates/images/banners/';
	}
	//----------- MAIN BLOCKS ------------//
	function getBannersBlock(){
		if($_POST['adm_banners_del'] && is_array($_POST['banners'])){
			$this->_oDb->deleteBanners($_POST['banners']);
			$sPostMsg = MsgBox(_t('_dol_aff_msg_banners_del'),3);
		}
		if($_POST['adm_banners_edit'] && is_array($_POST['banners'])){
			Redirect($this->mBase.'edit_banner?bid='.$_POST['banners']['0'],'',post, _t('_dol_aff_loading'));
		}
		$aBanners = $this->_oDb->getBanners();
		$sMsg = (count($aBanners) > '0') ?  '' : MsgBox(_t('_dol_aff_msg_no_banners'));
		$iStart = ($_GET['start']) ? $_GET['start'] : 0;
		$iPerPage = ($_GET['per_page']) ? $_GET['per_page'] : 10;
		$sDateFormat = getLocaleFormat(BX_DOL_LOCALE_DATE, BX_DOL_LOCALE_DB);
		$iCount = count($aBanners);
		$iCounter = '0';
		$aRange = range($iStart,$iStart+($iPerPage-1));
		foreach($aBanners as $aBanner){
			if(!in_array($iCounter++,$aRange)) continue;
			$sIcon = $this->getBannerIcon($aBanner['type']);
		   	$aItems[] = array(
		      	'id' 		=> $aBanner['id'],
				'name' 		=> $aBanner['name'],
				'base'		=> $this->mBase,
		      	'hidden'    => ($aBanner['hidden'] == '1') ? 'Yes' : 'No',
				'img_url'   => $this->imgs,
		      	'type' 	    => $sIcon,
				'dest_url'  => $aBanner['target_url'],
				'campaign'  => $this->_oDb->getCampaignNameById($aBanner['campaign_id']),
				'bx_if:text' => array(
		        	'condition' => ($aBanner['type'] == 'text'),
		          	'content' => array(
		              	'banner' => $this->_oTemplate->generateTextBannerPreview($aBanner['id']),
		           	),
		       	),	
				'bx_if:image' => array(
		        	'condition' => ($aBanner['type'] == 'image'),
		          	'content' => array(
		              	'banner' => $this->_oTemplate->generateImageBannerPreview($aBanner['id']),
		           	),
		       	),	
				'bx_if:flash' => array(
		        	'condition' => ($aBanner['type'] == 'flash'),
		          	'content' => array(
		              	'banner' => $this->_oTemplate->generateFlashBannerPreview($aBanner['id']),
		           	),
		       	),
		    );
		}
	    $oPaginate = new BxDolPaginate(array(
	      	'start' => $iStart,
			'count' => $iCount,
			'per_page' => $iPerPage,
			'sorting'    => 'last',
			'per_page_changer'   => true,
			'page_reloader'      => true,
		    'page_url' => $this->mBase.'banners?start={start}&per_page={per_page}'
	    ));
	    $sPaginate = $oPaginate->getPaginate();
		$aButtons = array(
	        'adm_banners_edit' => 'Edit Banner',
	        'adm_banners_del' => 'Delete'
	    ); 
		$sControls = BxTemplSearchResult::showAdminActionsPanel('banners_form', $aButtons, 'banners', true ,false); 

		$aResult = array(
			'bx_repeat:banners' => $aItems,
			'paginate' => $sPaginate,
			'controls' => $sControls,
			'msg' => $sPostMsg,
			'message' => $sMsg
		);
	    $sCode = DesignBoxAdmin(_t('_dol_aff_banners'), $this->_oTemplate->parseHtmlByName('admin_banners', $aResult));
		return $sCode;
	}
	function getCreateBannersBlock(){
		$aVars = array(
			'img_url' => BX_DOL_URL_MODULES.'harvest/affiliates/images/',
		);
		$sCode = DesignBoxAdmin(_t('_dol_aff_create_banner'), $this->_oTemplate->parseHtmlByName('admin_create_banner', $aVars));
		return $sCode;
	}
	//---------------- BANNERS EDIT --------------- //
	function getEditBannersAdminPage($iBid){
		$aBannerInfo = $this->_oDb->getBannerInfo($iBid);		
		$aForm = $this->getEditBannerForm($aBannerInfo);
		$aVars = array(
			'edit_banner' => $aForm
		); 
		$sTitle = 'Edit Banner "'.$aBannerInfo['name'].'"';
	    $sCode.= DesignBoxAdmin($sTitle, $aForm);
		return $sCode;
	}
	function getEditBannerForm($aBannerInfo){
        affilates_class_import('FormInputs');
        $oInputs = new HmAffProFormInputs;
		$aInputs = $oInputs->getEditBannerInputs($aBannerInfo);	
      	$aForm = array(
            'form_attrs' => array(
                'name'     => 'edit_banner', 
                'method'   => 'post',
            	'enctype'  => 'multipart/form-data',
                'action'   => NULL,
            ),
			'params' => array (
            	'db' => array(
   					'submit_name' => 'edit_banner', 
                ),
        	),
			'inputs' => $aInputs,       
		);	
        $oForm = new BxTemplFormView ($aForm);
        $oForm->initChecker();
		if ($oForm->isSubmittedAndValid ()) {
			if($aBannerInfo['type'] == 'text'){
				$this->_oDb->updateTextBanner($aBannerInfo['id'],$_POST);
			}elseif($aBannerInfo['type'] == 'image'){
				$sFileName = $_FILES['upload_image']['name']; 
				$sTargetPath = $this->sBannersPath;			
				$sTargetPath = $sTargetPath . basename( $_FILES['upload_image']['name']);				
				move_uploaded_file($_FILES['upload_image']['tmp_name'], $sTargetPath);
				$this->_oDb->updateImageBanner($aBannerInfo['id'],$sFileName,$_POST);
			}elseif($aBannerInfo['type'] == 'flash'){
				$sFileName = $_FILES['upload_flash']['name']; 
				$sTargetPath = $this->sBannersPath;			
				$sTargetPath = $sTargetPath . basename( $_FILES['upload_flash']['name']);				
				move_uploaded_file($_FILES['upload_flash']['tmp_name'], $sTargetPath);
				$this->_oDb->updateFlashBanner($aBannerInfo['id'],$sFileName,$_POST);
			}
			Redirect($this->mBase.'banners', array('action'=>'updated'), post, 'Loading...');
		}

        $sCode = $oForm->getCode(); 		
    	return $sCode;		
	}
	function validateEditBannerForm($aVars){
		if($aVars['link_name'] == '')
			return 'Please enter a banner name';
		if($aVars['link_target'] == '')
			return 'Please enter a destination URL';
		return 'true';
	}
	//------------------ CREATE BANNERS ---------------//
	function showCreateTextBannerForm(){
		affilates_class_import('FormInputs');
        $oInputs = new HmAffProFormInputs;
		$aInputs = $oInputs->getCreateTextLinkInputs();	
      	$aForm = array(
            'form_attrs' => array(
                'name'     => 'create_text_link', 
                'method'   => 'post',
				'id' 	   => 'create_text_link',
                'action'   => $this->mBase.'create_text_banner',
            ),
			'inputs' => $aInputs,
		);	
        $oForm = new BxTemplFormView ($aForm);
        $oForm->initChecker();
		if ($oForm->isSubmittedAndValid ()) {
			$sValidate = $this->validateCreateBannerForm($_POST);
			if($sValidate == 'true'){		
				$this->_oDb->createBanner($_POST);
				Redirect($this->mBase.'campaigns', array('action'=>'updated'), post, 'Updating Campaign');
			}else{
				$sValidateMsg = '<script>alert("'.$sValidate.'");</script>';
			}
       	}
        $sCode.= $oForm->getCode(); 
		$aVars = array(
			'form' => $sCode
		);
   		return PopupBox('create_banner', 'Create Text Banner', $this->_oTemplate->parseHtmlByName('popup_create_banner',$aVars));
	}
	function showCreateImageBannerForm(){
		affilates_class_import('FormInputs');
        $oInputs = new HmAffProFormInputs;
		$aInputs = $oInputs->getCreateImageBannerInputs();	
      	$aForm = array(
            'form_attrs' => array(
                'name'     => 'create_image_banner', 
                'method'   => 'post',
				'id' 	   => 'create_image_banner',
            	'enctype'  => 'multipart/form-data',
                'action'   => $this->mBase.'create_image_banner' 
            ),
			'inputs' => $aInputs,
		);	
        $oForm = new BxTemplFormView ($aForm);
        $oForm->initChecker();
		if ($oForm->isSubmittedAndValid ()) {
			$sValidate = $this->validateCreateBannerForm($_POST);
			if($sValidate == 'true'){		
				$this->_oDb->createBanner($_POST);
				Redirect($this->mBase.'campaigns', array('action'=>'updated'), post, _t('_dol_aff_loading'));
			}else{
				$sValidateMsg = '<script>alert("'.$sValidate.'");</script>';
			}
       	}
        $sCode.= $oForm->getCode(); 
		$aVars = array(
			'form' => $sCode
		);
   		return PopupBox('create_banner', _t('_dol_aff_create_img_banner'), $this->_oTemplate->parseHtmlByName('popup_create_image_banner',$aVars));
	}
	function showCreateFlashBannerForm(){
		affilates_class_import('FormInputs');
        $oInputs = new HmAffProFormInputs;
		$aInputs = $oInputs->getCreateFlashBannerInputs();	
      	$aForm = array(
            'form_attrs' => array(
                'name'     => 'create_flash_banner', 
                'method'   => 'post',
				'id' 	   => 'create_flash_banner',
            	'enctype'  => 'multipart/form-data',
                'action'   => $this->mBase.'create_flash_banner' 
            ),
			'inputs' => $aInputs,
		);	
        $oForm = new BxTemplFormView ($aForm);
        $oForm->initChecker();
		if ($oForm->isSubmittedAndValid ()) {
			$sValidate = $this->validateCreateBannerForm($_POST);
			if($sValidate == 'true'){		
				$this->_oDb->createBanner($_POST);
				Redirect($this->mBase.'campaigns', array('action'=>'updated'), post, _t('_dol_aff_loading'));
			}else{
				$sValidateMsg = '<script>alert("'.$sValidate.'");</script>';
			}
       	}
        $sCode.= $oForm->getCode(); 
		$aVars = array(
			'form' => $sCode
		);
   		return PopupBox('create_banner', _t('_dol_aff_create_fl_banner'), $this->_oTemplate->parseHtmlByName('popup_create_flash_banner',$aVars));
	}	
	function getBannerIcon($sType){
		if($sType == 'text') return 'text-banner-icon.png';
		if($sType == 'image') return 'image-banner-icon.png';
		if($sType == 'flash') return 'flash-banner-icon.png';
	}

}
?>