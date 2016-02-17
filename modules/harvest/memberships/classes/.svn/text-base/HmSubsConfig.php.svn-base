<?
/***************************************************************************
Dolphin Subscriptions v.2.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/

bx_import('BxDolConfig');

class HmSubsConfig extends BxDolConfig {

	function HmSubsConfig($aModule) {
	    parent::BxDolConfig($aModule);    
		$this->_sIconsFolder = 'harvest/memberships/images/icons/';
	}
	function getIconsUrl() {		
	    return BX_DOL_URL_MODULES . $this->_sIconsFolder;
	}
	function getIconsPath() {
	    return BX_DIRECTORY_PATH_MODULES . $this->_sIconsFolder;
	}
	function getPaymentProcessors(){
		$aProcessors = array(
			'paypal' => 'Paypal',
			'alertpay' => 'Alertpay',
			'authorize' => 'Authorize.net',	
		
		);
	 	return $aProcessors;
	}
	function checkResponse($aVars){
		if(is_array($aVars)){
		    foreach ($aVars as $key => $value) {
				$aKeys[] = $key;
				//db_res("INSERT INTO `dol_subs_payments` SET `txn_id` = '{$key}'");
		    }
			if (in_array('txn_type', $aKeys)) {
				return 'paypal';
			}
			if (in_array('ap_transactiontype', $aKeys)){
				return 'alertpay';
			}
			if (in_array('x_response_code', $aKeys)){
				return 'authorize';
			}
		}

	}
	function safe_pages(){
		$aSafe = array('_Home','_Log Out','_Unregister','_About','_TERMS_OF_USE_H','_PRIVACY_H','_help','_FAQ','_Contact','_dol_subs_mem_info','_Account Home','_dol_subs_mem_info');
		return $aSafe;
	}
	function safe_pages_guest(){
		$aSafeGuest = array('_Home','_Log Out','_Unregister','_About','_TERMS_OF_USE_H','_PRIVACY_H','_help','_FAQ','_Contact','_Account');
		return $aSafeGuest;
	}
	function getMonths(){
		$aMonths = array(
			'01' => '01 - Jan',
			'02' => '02 - Feb',
			'03' => '03 - Mar', 
			'04' => '04 - Apr', 
			'05' => '05 - May',
			'06' => '06 - Jun',
			'07' => '07 - Jul', 
			'08' => '08 - Aug', 
			'09' => '09 - Sep',
			'10' => '10 - Oct',
			'11' => '11 - Nov', 
			'12' => '12 - Dec'
		);
		return $aMonths;
	}
}
?>
