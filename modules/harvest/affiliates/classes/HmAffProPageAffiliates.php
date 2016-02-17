<?php
/***************************************************************************
Affiliates Pro v.1.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/

class HmAffProPageAffiliates{
  
	function HmAffProPageAffiliates(){
		$this->_oMain = BxDolModule::getInstance("HmAffProModule");
		$this->_oDb = $this->_oMain->_oDb;
		$this->_oTemplate = $this->_oMain->_oTemplate;
		$this->mBase = BX_DOL_URL_ROOT.'m/affiliates/';
		$this->imgs = BX_DOL_URL_MODULES.'harvest/affiliates/images/';
		$this->curUserId = getLoggedId();
		$this->ajax = $this->mBase.'ajax?';
		$this->sCurSign = getParam('currency_sign');
	}
	//----------- MAIN BLOCKS ------------//
	function getAffiliatesBlock(){
		if($_POST['create_affiliate']){
			$aUserNames = $this->_oDb->getUsernameList();
			if(in_array($_POST['username'],$aUserNames)){
				$iUserId = getID($_POST['username']);
				$this->_oDb->registerAffiliate($iUserId, $_POST);
				$sPostMsg = MsgBox('Affiliate Created',3);
			}else{
				$sPostMsg = MsgBox('Username Does Not Exist',3);
			}	
		}
		if($_POST['adm_affiliates_del'] && is_array($_POST['affiliates'])){
			$this->_oDb->deleteAffiliates($_POST['affiliates']);
			$sPostMsg = MsgBox('Affiliate Deleted',3);
		}
		if($_POST['adm_affiliates_approve'] && is_array($_POST['affiliates'])){
			$this->_oDb->approveAffiliate($_POST['affiliates']);
			$oZ = new BxDolAlerts('affiliates', 'approve', '',$this->curUserId, $_POST['affiliates']);
			$oZ->alert();
			$sPostMsg = MsgBox(_t('_dol_aff_msg_affiliate_approved'),3);
		}
		$aAffiliates = $this->_oDb->getAffiliates();
		$sMsg = (count($aAffiliates) > '0') ?  '' : MsgBox(_t('_dol_aff_msg_no_affiliates'));
		$iStart = ($_GET['start']) ? $_GET['start'] : 0;
		$iPerPage = ($_GET['per_page']) ? $_GET['per_page'] : 10;
		$sDateFormat = getLocaleFormat(BX_DOL_LOCALE_DATE, BX_DOL_LOCALE_DB);
		$iCount = count($aAffiliates);
		$iCounter = '0';
		$sCur = getParam('currency_sign');;
		$aRange = range($iStart,$iStart+($iPerPage-1));
		foreach($aAffiliates as $aAffiliate){
			if(!in_array($iCounter++,$aRange)) continue;
			$iCommissions = $this->_oDb->getAffiliateCommissions($aAffiliate['id']);
			$aProfile = getProfileInfo($aAffiliate['user_id']);
			$sDateFixed = $this->_oDb->getTimeStamp($aAffiliate['date_start']);	
		   	$aItems[] = array(
		      	'id' 		=> $aAffiliate['id'],
				'user_name'	=> ucfirst($aProfile['NickName']),
				'base'		=> $this->mBase,
		      	'date'    	=> date('m-d-Y',$sDateFixed),
				'email'   	=> $aAffiliate['user_email'],
		      	'status'	=> ucfirst($aAffiliate['status']),
		      	'commissions' => $this->sCurSign.number_format($iCommissions,2),
		    );
		}
		bx_import('BxDolPaginate');
	    $oPaginate = new BxDolPaginate(array(
	      	'start' => $iStart,
			'count' => $iCount,
			'per_page' => $iPerPage,
			'per_page_changer'   => true,
			'page_reloader'      => true,
		    'page_url' => $this->mBase.'affiliates?start={start}&per_page={per_page}',
	    ));
	    $sPaginate2 = $oPaginate->getPaginate();
		$aButtons = array(
	        'adm_affiliates_approve' => _t('_dol_aff_but_approve_affiliates'),
	        'adm_affiliates_del' =>  _t('_dol_aff_but_delete')
	    ); 
		$sControls = BxTemplSearchResult::showAdminActionsPanel('affiliates_form', $aButtons, 'affiliates', true ,false); 

		$aResult = array(
			'bx_repeat:items' => $aItems,
			'paginate' => $sPaginate2,
			'controls' => $sControls,
			'msg'	=> $sPostMsg,
			'message' => $sMsg
		);
	    $sCode = DesignBoxAdmin(_t('_dol_aff_affiliates'), $this->_oTemplate->parseHtmlByName('admin_affiliates', $aResult));
		return $sCode;
	}
	function getCreateAffiliateBlock(){	
		$aUserNames = $this->_oDb->getUsernameList();
		$sUserNames = implode(",", $aUserNames);
		affilates_class_import('FormInputs');
        $oInputs = new HmAffProFormInputs;
		$aInputs = $oInputs->getCreateAffiliateInputs();	
      	$aForm = array(
            'form_attrs' => array(
                'name'     => 'create_affiliate', 
                'method'   => 'post',
				'id' 	   => 'create_affiliate',
            	'enctype'  => 'multipart/form-data',
                'action'   => NULL 
            ),
			'inputs' => $aInputs,
		);
        $oForm = new BxTemplFormView ($aForm);
		$sForm = $oForm->getCode();
		$aVars = array(
			'form' => $sForm,
			'usernames' => $sUserNames,
		);
	    $sCode.= DesignBoxAdmin(_t('_dol_aff_create_affiliate'), $this->_oTemplate->parseHtmlByName('admin_create_affiliate', $aVars));
		return $sCode;
	}
	function getManageCampaignsForm($iAid){	
		$aAffiliate = $this->_oDb->getAffiliate($iAid);
		$sNickName = getNickName($aAffiliate['user_id']);	
		affilates_class_import('FormInputs');
        $oInputs = new HmAffProFormInputs;
		$aInputs = $oInputs->getManageCampaignsInputs($iAid);	
      	$aForm = array(
            'form_attrs' => array(
                'name'     => 'manage_campaigns', 
                'method'   => 'post',
				'id' 	   => 'manage_campaigns',
            	'enctype'  => 'multipart/form-data',
                'action'   => $this->mBase.'ajax?action=save_affiliate_campaigns',
            ),
			'inputs' => $aInputs,
		);
        $oForm = new BxTemplFormView ($aForm);
		$sForm = $oForm->getCode();		
		$aVars = array(
			'form' => $sForm,
			'aid' => $iAid
		);    		
    	return PopupBox('manage_campaigns', $sNickName, $this->_oTemplate->parseHtmlByName('popup_manage_campaigns',$aVars));
	}
	function displayAffiliateDetails($iAid, $sFilter=''){
		$aAffiliate = $this->_oDb->getAffiliate($iAid);
		$sNickName = getNickName($aAffiliate['user_id']);
		$aAffiliateDetails = $this->_oDb->getAffiliateDetails($iAid, $sFilter);
		$aVars = array(
			'aid' => $iAid,
			'imp_raw' => $aAffiliateDetails['imp_raw'],
			'imp_uni' => $aAffiliateDetails['imp_uni'],
			'cli_raw' => $aAffiliateDetails['cli_raw'],
			'cli_uni' => $aAffiliateDetails['cli_uni'],
			'com_trans' => $aAffiliateDetails['com_trans'],
			'com_earned' => $this->sCurSign.number_format($aAffiliateDetails['com_earned'],2),
			'ajax_url' => $this->ajax,
			'bx_if:total' => array(
				'condition' => ($sFilter == 'total'),
				'content' => array(
					'selected' => 'selected'
				),
			),
			'bx_if:last_seven_days' => array(
				'condition' => ($sFilter == 'last_seven_days'),
				'content' => array(
					'selected' => 'selected'
				),
			),
			'bx_if:last_thirty_days' => array(
				'condition' => ($sFilter == 'last_thirty_days'),
				'content' => array(
					'selected' => 'selected'
				),
			),
			'bx_if:this_month' => array(
				'condition' => ($sFilter == 'this_month'),
				'content' => array(
					'selected' => 'selected'
				),
			),
			'bx_if:last_month' => array(
				'condition' => ($sFilter == 'last_month'),
				'content' => array(
					'selected' => 'selected'
				),
			),
			'bx_if:this_year' => array(
				'condition' => ($sFilter == 'this_year'),
				'content' => array(
					'selected' => 'selected'
				),
			),
			'bx_if:last_year' => array(
				'condition' => ($sFilter == 'last_year'),
				'content' => array(
					'selected' => 'selected'
				),
			),
		);
		if($sFilter == ''){
    		return PopupBox('show_details', $sNickName, $this->_oTemplate->parseHtmlByName('popup_affiliate_details',$aVars));
		}else{
			return $this->_oTemplate->parseHtmlByName('popup_affiliate_details',$aVars);
		}
	}


}
?>