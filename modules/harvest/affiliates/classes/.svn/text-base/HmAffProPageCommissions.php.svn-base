<?php
/***************************************************************************
Affiliates Pro v.1.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/
class HmAffProPageCommissions{
  
	function HmAffProPageCommissions(){
		$this->_oMain = BxDolModule::getInstance("HmAffProModule");
		$this->_oDb = $this->_oMain->_oDb;
		$this->_oTemplate = $this->_oMain->_oTemplate;
		$this->mBase = BX_DOL_URL_ROOT.'m/affiliates/';
		$this->imgs = BX_DOL_URL_MODULES.'harvest/affiliates/images/';
		$this->sCur = getParam('currency_sign');
	}
	//----------- MAIN BLOCKS ------------//
	function getSearchBlock(){
		$aForm = $this->getSearchCommissionsForm();
		$aVars = array(
			'search_commissions' => $aForm
		); 
	    $sCode.= DesignBoxAdmin(_t('_dol_aff_search'), $this->_oTemplate->parseHtmlByName('admin_commissions_search', $aVars));
		return $sCode;
	}
	function getCommissionsBlock($sFilter = ''){		
		if($_POST['adm_commissions_approve'] && is_array($_POST['commissions'])){
			$this->_oDb->approveCommissions($_POST['commissions']);
			$sPostMsg = MsgBox(_t('_dol_aff_msg_comm_app'),3);
		}
		if($_POST['adm_commissions_decline'] && is_array($_POST['commissions'])){
			$this->_oDb->declineCommissions($_POST['commissions']);
			$sPostMsg = MsgBox(_t('_dol_aff_msg_comm_dec'),3);
		}
		$aCommissions = $this->_oDb->getCommissions($sFilter);
		$sMsg = (count($aCommissions) > '0') ?  '' : MsgBox(_t('_dol_aff_msg_no_comm'));
		$iStart = ($_GET['start']) ? $_GET['start'] : 0;
		$iPerPage = ($_GET['per_page']) ? $_GET['per_page'] : 10;
		$sDateFormat = getLocaleFormat(BX_DOL_LOCALE_DATE, BX_DOL_LOCALE_DB);
		$iCount = count($aCommissions);
		$iCounter = '0';
		$aRange = range($iStart,$iStart+($iPerPage-1));
		foreach($aCommissions as $aCommission){
			if(!in_array($iCounter++,$aRange)) continue;
			$aAffiliate = $this->_oDb->getAffiliate($aCommission['affiliate_id']);
			$aProfile = getProfileInfo($aAffiliate['user_id']);
			$sDateFixed = $this->_oDb->getTimeStamp($aCommission['date']);	
			$sCur = getParam('currency_sign');
		   	$aItems[] = array(
		      	'id' 		=> $aCommission['id'],
				'user_name'	=> ucfirst($aProfile['NickName']),
				'base'		=> $this->mBase,
		      	'date'    	=> date('m-d-Y',$sDateFixed),
				'type'   	=> ucfirst($aCommission['type']),
		      	'approved'	=> ucfirst($aCommission['approved']),
		      	'amount' 	=> $this->sCur.number_format($aCommission['amount'],2),
		      	'status' 	=> ucfirst($aCommission['status']),
		    );
		}
	    $oPaginate = new BxDolPaginate(array(
	      	'start' => $iStart,
			'count' => $iCount,
			'per_page' => $iPerPage,
			'sorting'    => 'last',
			'per_page_changer'   => true,
			'page_reloader'      => true,
		    'page_url' => $this->mBase.'commissions/?start={start}&per_page={per_page}',
	    ));
	    $sPaginate = $oPaginate->getPaginate();
		$aButtons = array(
	        'adm_commissions_approve' => _t('_dol_aff_but_app_comm'),
			'adm_commissions_decline' => _t('_dol_aff_but_dec_comm'),
	    ); 
		$sControls = BxTemplSearchResult::showAdminActionsPanel('commissions_form', $aButtons, 'commissions', true ,false); 

		$aResult = array(
			'bx_repeat:items' => $aItems,
			'paginate' => $sPaginate,
			'controls' => $sControls,
			'msg'	=> $sPostMsg,
			'message' => $sMsg
		);
	    $sCode = $this->_oTemplate->parseHtmlByName('admin_commissions', $aResult);
		return $sCode;
	}
	function getSearchCommissionsForm(){
      	$aForm = array(
            'form_attrs' => array(
                'name'     => 'search_comm', 
                'method'   => 'post',
                'action'   => NULL,
				'onsubmit' => 'searchCommissions(); return false;',
            ),
			'params' => array (
            	'db' => array(
   					'submit_name' => 'search_comm', 
                ),
        	),
			'inputs' =>  array(
	          	'search_comm_text' => array(
	         		'type' => 'text',
	        		'name' => 'search_comm_text',
	                'caption' => _t('_dol_aff_adm_username_filter'),
					'attrs' => array('id' => 'search_comm_text'),	
					'info' => _t('_dol_aff_adm_search_by_username'),	
	            ),
	            'search_comm' => array(
	                'type' => 'submit',
	                'name' => 'search_comm',
	                'value' => _t('_dol_aff_reg_search'),
	            ),
			),     
		);	
        $oForm = new BxTemplFormView ($aForm);
        $sCode.= $oForm->getCode(); 		
    	return $sValidateMsg.$sCode;
	}

}
?>