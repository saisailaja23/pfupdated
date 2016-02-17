<?php
/***************************************************************************
Dolphin Subscriptions v.2.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/
bx_import('BxDolModule');

class HmSubsAlertPay{

    function __construct(){
        $this->oMain = $this->getMain();
	}

    function getMain() {
        return BxDolModule::getInstance('HmSubsModule');
    }

	function showForm($iMembId){ 
		$iId = $_COOKIE['memberID'];
		$aPriceInfo	= $this->oMain->_oDb->getMembershipPriceInfo($iMembId);
		$aLevel = $this->oMain->_oDb->getMembershipById($iMembId);
		$aMemberInfo = getProfileInfo($iId);		

		$aVars = array(
			'action' => $this->getLink(),
			'ap_merchant' => $this->oMain->_oDb->getSetting('alertpay_id'),
			'auto' => $sAuto = ($aPriceInfo['Auto'] == '1') ? 'subscription' : 'item',
			'currency' => getParam('currency_code'),
			'item_name' => $aLevel['Name'].' '._t('_dol_subs_membership'),
			'item_number' => $_COOKIE['memberID'].'-'.$aLevel['ID'],
			'amount' => $aPriceInfo['Price'],
			'callback' => BX_DOL_URL_ROOT.'m/memberships/callback',
			'cancel' => BX_DOL_URL_ROOT.'m/memberships/',
			'return' => BX_DOL_URL_ROOT.'m/memberships/',
			'bx_if:trial' => array(
				'condition' => ($aLevel['Trial'] == '1'),
		        'content' => array(
		            't_length' => $aLevel['Trial_Length'],
		        ),
			),
			'bx_if:auto' => array(
				'condition' => ($aPriceInfo['Auto'] == '1'),
		        'content' => array(
		            'unit' => $sUnit = ($aPriceInfo['Unit'] == 'Days') ? 'Day' : 'Month',
		            'length' =>$aPriceInfo['Length'],
		        ),
			),
			'bx_if:acl' => array(
				'condition' => ($this->oMain->_oDb->userAcl($iId) == '1'),
		        'content' => array(
		            'modify' => $this->modifyInput(),
		        ),
			),
		);
		return  $this->oMain->_oTemplate->parseHtmlByName('ap_form',$aVars);    
    }
	function processPayment($aVars){
 		list($iUserId, $iMembLevel) = explode('-', urldecode($aVars['ap_itemcode']));
		$aMembLevelInfo = $this->oMain->_oDb->getMembershipById($iMembLevel);
		$aMembLevelPriceInfo = $this->oMain->_oDb->getMembershipPriceInfo($iMembLevel);
		$iPrice = number_format($aMembLevelPriceInfo['Price'], 2);
		$sAlertPayId = $this->oMain->_oDb->getSetting('alertpay_id');
		$sAlertSecCode = $this->oMain->_oDb->getSetting('ap_securitycode');
		$sMerchant = urldecode($aVars['ap_merchant']);	
		$sSecCode = urldecode($aVars['ap_securitycode']);
		$sPurchaseType = urldecode($aVars['ap_purchasetype']);
		$sStatus = urldecode($aVars['ap_status']);
		$sSubId = urldecode($aVars['ap_subscriptionreferencenumber']);
		$sTrialAmount = urldecode($aVars['ap_trialamount']);
		$sTrialUnit = urldecode($aVars['ap_trialtimeunit']);
		$sTrialLength = urldecode($aVars['ap_trialperiodlength']);
		switch($aMembLevelPriceInfo['Unit']){
			case 'Days':
				$iMembershipDays = $aMembLevelPriceInfo['Length'];
			break;
			case 'Months':
				$iMembershipDays = $aMembLevelPriceInfo['Length']*30;
			break;
		}
		if ($sMerchant == $sAlertPayId && $sSecCode == $sAlertSecCode){
			if ($sPurchaseType == 'subscription' && $sStatus == 'Subscription-Payment-Success'){
				if(urldecode($aVars['ap_referencenumber']) != "TEST TRANSACTION"){
					$rInsertPayment = db_res("INSERT INTO `dol_subs_payments` SET 
						`txn_id` 		= '{$aVars['ap_referencenumber']}',
						`membership_id` = '{$iMembLevel}',
						`user_id`		= '{$iUserId}',
						`amount` 		= '{$aVars['ap_totalamount']}',
						`date` 			= NOW(),
						`status` 		= '{$sStatus}'
					");	
				}	
				$this->oMain->_oDb->setUserStatus($iId,'Active');			
				$this->oMain->_oDb->clearCache('user', BX_DIRECTORY_PATH_CACHE);																																																													
				setMembership($iUserId, $iMembLevel, $iMembershipDays, true, $sSubId );

				if ($sTrialAmount != '' && $sTrialUnit != '' && $sTrialLength != '') {																																																										
					//setMembership($iUserId, $iMembLevel, $sTrialLength, true, $sSubId );	
				}
			}
 			if ($sPurchaseType == 'item' && $sStatus == 'Success'){	
				if(urldecode($aVars['ap_referencenumber']) != "TEST TRANSACTION"){
					$rInsertPayment = db_res("INSERT INTO `dol_subs_payments` SET 
						`txn_id` 		= '{$aVars['ap_referencenumber']}',
						`membership_id` = '{$iMembLevel}',
						`user_id`		= '{$iUserId}',
						`amount` 		= '{$aVars['ap_totalamount']}',
						`date` 			= NOW(),
						`status` 		= '{$sStatus}'
					");	
				}
				$this->oMain->_oDb->setUserStatus($iId,'Active');			
				$this->oMain->_oDb->clearCache('user', BX_DIRECTORY_PATH_CACHE);
				setMembership($iUserId, $iMembLevel, $iMembershipDays, true, $sSubId );
			}

		}
	}
	function getLink(){
		return 'https://www.alertpay.com/PayProcess.aspx';
	}
	function modifyInput(){
		$sCode = '<input type="hidden" name="modify" value="2" />';
		return $sCode;
	}
}

?>