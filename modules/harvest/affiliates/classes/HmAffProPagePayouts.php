<?php
/***************************************************************************
Affiliates Pro v.1.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/
class HmAffProPagePayouts{
  
	function HmAffProPagePayouts(){
		$this->_oMain = BxDolModule::getInstance("HmAffProModule");
		$this->_oDb = $this->_oMain->_oDb;
		$this->_oTemplate = $this->_oMain->_oTemplate;
		$this->mBase = BX_DOL_URL_ROOT.'m/affiliates/';
		$this->imgs = BX_DOL_URL_MODULES.'harvest/affiliates/images/';
	}
	//----------- MAIN BLOCKS ------------//
	function getPayoutsBlock(){
		if($_POST['adm_payouts_made'] && is_array($_POST['aid'])){
			$this->_oDb->processNewPayout($_POST);
			$sPostMsg = MsgBox(_t('_dol_aff_msg_payouts_proc'),3);		
		}
		$aPayouts = $this->_oDb->getAvailablePayouts();
		$sMsg = (count($aPayouts) > '0') ?  '' : MsgBox(_t('_dol_aff_msg_no_payouts'));
		$iStart = ($_GET['start']) ? $_GET['start'] : 0;
		$iPerPage = ($_GET['per_page']) ? $_GET['per_page'] : 10;
		$iCount = count($aPayouts);
		$iCounter = '0';
		$aRange = range($iStart,$iStart+($iPerPage-1));
		foreach($aPayouts as $aPayout){
			if(!in_array($iCounter++,$aRange)) continue;
			$aAffiliate =  $this->_oDb->getAffiliate($aPayout['affiliate_id']);
			$aProfile = getProfileInfo($aAffiliate['user_id']);
			$sCur = getParam('currency_sign');
			$iCommissions = $this->_oDb->getAffiliateCommissions($aAffiliate['id']);
		   	$aItems[] = array(
		      	'aid' 		=> $aAffiliate['id'],
				'user_name'	=> ucfirst($aProfile['NickName']),
				'base'		=> $this->mBase,
				'email'   	=> $aAffiliate['user_email'],
		      	'status'	=> ucfirst($aAffiliate['status']),
		      	'commissions' => $sCur.number_format($aPayout['total_amount'],2),
				'total_amount' => $aPayout['total_amount'],
				'payout_pref' => ucfirst($aAffiliate['payout_preference']),
		    );
		}
	    $oPaginate = new BxDolPaginate(array(
	      	'start' => $iStart,
			'count' => $iCount,
			'per_page' => $iPerPage,
			'sorting'    => 'last',
			'per_page_changer'   => true,
			'page_reloader'      => true,
		    'page_url' => $this->mBase.'payouts/?start={start}&per_page={per_page}',
	    ));
	    $sPaginate = $oPaginate->getPaginate();
		$aButtons = array(
	        'adm_payouts_made' => _t('_dol_aff_but_mark_comm_paid')
	    ); 
		$sControls = BxTemplSearchResult::showAdminActionsPanel('payouts_form', $aButtons, 'payouts', true ,false); 

		$aResult = array(
			'bx_repeat:items' => $aItems,
			'paginate' => $sPaginate,
			'controls' => $sControls,
			'post_msg' => $sPostMsg,
			'message' => $sMsg
		);
	    $sCode = DesignBoxAdmin(_t('_dol_aff_aff_min_balance'), $this->_oTemplate->parseHtmlByName('admin_payouts', $aResult));
		return $sCode;
	}
	function getPayoutsHistoryBlock(){
		$aPayouts = $this->_oDb->getPayoutsHistory();
		$sMsg = (count($aPayouts) > '0') ?  '' : MsgBox(_t('_dol_aff_no_payouts_made'));
		$iStart = ($_GET['start']) ? $_GET['start'] : 0;
		$iPerPage = ($_GET['per_page']) ? $_GET['per_page'] : 10;
		$iCount = count($aPayouts);
		$iCounter = '0';
		$aRange = range($iStart,$iStart+($iPerPage-1));
		foreach($aPayouts as $aPayout){
			if(!in_array($iCounter++,$aRange)) continue;
			$aAffiliate =  $this->_oDb->getAffiliate($aPayout['affiliate_id']);
			$aProfile = getProfileInfo($aPayout['affiliate_id']);
			$sCur = getParam('currency_sign');
			$iCommissions = $this->_oDb->getAffiliateCommissions($aAffiliate['id']);
		   	$aItems[] = array(
		      	'aid' 		=> $aAffiliate['id'],
				'user_name'	=> ucfirst($aProfile['NickName']),
				'base'		=> $this->mBase,
				'email'   	=> $aAffiliate['user_email'],
		      	'date'		=> $aPayout['date'],
		      	'total_amount' => $sCur.number_format($aPayout['amount'],2)
		    );
		}
	    $oPaginate = new BxDolPaginate(array(
	      	'start' => $iStart,
			'count' => $iCount,
			'per_page' => $iPerPage,
			'sorting'    => 'last',
			'per_page_changer'   => true,
			'page_reloader'      => true,
		    'page_url' => $this->mBase.'payouts/?start={start}&per_page={per_page}',
	    ));
	    $sPaginate = $oPaginate->getPaginate();
		$aButtons = array(
	        'adm_payouts_made' => 'Mark Commissions Paid'
	    ); 
		$sControls = BxTemplSearchResult::showAdminActionsPanel('payouts_form', $aButtons, 'payouts', true ,false); 

		$aResult = array(
			'bx_repeat:items' => $aItems,
			'paginate' => $sPaginate,
			'controls' => $sControls,
			'post_msg' => $sPostMsg,
			'message' => $sMsg
		);
		$sCode = DesignBoxAdmin(_t('_dol_aff_payout_history'), $this->_oTemplate->parseHtmlByName('admin_payout_history', $aResult));
		return $sCode;
	}

}
?>