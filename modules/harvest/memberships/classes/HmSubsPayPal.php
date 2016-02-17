<?php
/***************************************************************************
Dolphin Subscriptions v.2.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/
bx_import('BxDolModule');
bx_import('BxDolProfilesController');
class HmSubsPayPal{

    function __construct(){
        $this->oMain = $this->getMain();
	}
    function getMain() {
        return BxDolModule::getInstance('HmSubsModule');
    }
    function getLink(){
          $sUrl = $this->getPaypal();
          $sLink = 'https://' . $sUrl . '/cgi-bin/webscr';
          return $sLink;
    }
	function showForm($iMembId){ 
		$iId = $_COOKIE['memberID'];
		$aPriceInfo	= $this->oMain->_oDb->getMembershipPriceInfo($iMembId);
		$aLevel = $this->oMain->_oDb->getMembershipById($iMembId);
		$aMemberInfo = getProfileInfo($iId);		

		$aVars = array(
			'action' => $this->getLink(),
			'business' => $this->oMain->_oDb->getSetting('paypal_id'),
			'auto' => $sAuto = ($aPriceInfo['Auto'] == '1') ? '_xclick-subscriptions' : '_xclick',
			'cur_code' => getParam('currency_code'),
			'item_name' => $aLevel['Name'].' '._t('_dol_subs_membership'),
			'item_number' => $_COOKIE['memberID'].'-'.$aLevel['ID'],
			'amount' => $aPriceInfo['Price'],
			'unit' => $sUnit = ($aPriceInfo['Unit'] == 'Days') ? 'D' : 'M',
			'length' => $aPriceInfo['Length'],
			'callback' => BX_DOL_URL_ROOT.'m/memberships/callback',
			'return' => BX_DOL_URL_ROOT.'m/memberships/',
			'custom' => $this->oMain->_oDb->getSetting('pp_custom_field'),
			'bx_if:trial' => array(
				'condition' => ($aLevel['Trial'] == '1'),
		        'content' => array(
		            't_length' => $aLevel['Trial_Length'],
		        ),
			),
			'bx_if:auto' => array(
				'condition' => ($aPriceInfo['Auto'] == '1'),
		        'content' => array(
		            'src' => '1',
		            'sr1' => '1',
		        ),
			),
			'bx_if:acl' => array(
				'condition' => ($this->oMain->_oDb->userAcl($iId) == '1'),
		        'content' => array(
		            'modify' => $this->modifyInput(),
		        ),
			),
		);
		return  $this->oMain->_oTemplate->parseHtmlByName('pp_payment_form',$aVars); 
    }
    function getPaypal(){
        if ($this->oMain->_oDb->getSetting('sandbox') == '1') {
            return 'www.sandbox.paypal.com';
        } else {
            return 'www.paypal.com';
        }
    }
	function processPayment($aVars){
 		list($iUserId, $iMembLevel) = explode('-', $aVars['item_number']);
		$aMembLevelInfo = $this->oMain->_oDb->getMembershipById($iMembLevel);
		$aMembLevelPriceInfo = $this->oMain->_oDb->getMembershipPriceInfo($iMembLevel);
		$iPrice = number_format($aMembLevelPriceInfo['Price'], 2);

		switch($aMembLevelPriceInfo['Unit']){
			case 'Days':
				$iMembershipDays = $aMembLevelPriceInfo['Length'];
			break;
			case 'Months':
				$iMembershipDays = $aMembLevelPriceInfo['Length']*30;
			break;
		}

		$sReq = 'cmd=_notify-validate';
      	foreach ($aVars as $key => $value) {
          	$value = urlencode(stripslashes($value));
          	$sReq .= '&' . $key . '=' . $value;
      	}
      	$sUrl = ($this->oMain->_oDb->getSetting('sandbox') == '1') ? 'www.sandbox.paypal.com' : 'www.paypal.com';      
      	$sHeader = "POST /cgi-bin/webscr HTTP/1.0\r\n";
      	$sHeader .= "Content-Type: application/x-www-form-urlencoded\r\n";
      	$sHeader .= "Content-Length: " . strlen($sReq) . "\r\n\r\n";
      	$fp = fsockopen($sUrl, 80, $iErrNo, $sErrStr, 30);
      	if (!$fp) {
          	echo $sErrStr . ' (' . $iErrNo . ')';
      	} else {
          	fputs($fp, $sHeader . $sReq);          
          	while (!feof($fp)) {
              	$sRes = fgets($fp, 1024);
              
              	if (strcmp($sRes, "VERIFIED") == 0) {
                  	if ($aVars['payment_status'] == 'Completed' && (($aVars['txn_type'] == 'subscr_payment') || ($aVars['txn_type'] == 'web_accept')) && $aVars['mc_gross'] == $iPrice) {				
						$rInsertPayment = db_res("INSERT INTO `dol_subs_payments` SET 
							`txn_id` 		= '{$aVars['txn_id']}',
							`membership_id` = '{$iMembLevel}',
							`user_id`		= '{$iUserId}',
							`amount` 		= '{$aVars['mc_gross']}',
							`date` 			= NOW(),
							`status` 		= '{$aVars['payment_status']}'
						");		
						$this->oMain->_oDb->setUserStatus($iId,'Active');			
						$this->oMain->_oDb->clearCache('user', BX_DIRECTORY_PATH_CACHE);																																																													
						$rResult = setMembership($iUserId, $iMembLevel, $iMembershipDays, true, $aVars['subscr_id']);				
					}
                  	if ($aVars['txn_type'] == 'subscr_signup' &&  $aVars['amount1'] == '0.00' && $aVars['mc_amount1'] == '0.00') {	
						$this->oMain->_oDb->setUserStatus($iId,'Active');			
						$this->oMain->_oDb->clearCache('user', BX_DIRECTORY_PATH_CACHE);																																																													
						$rResult = setMembership($iUserId, $iMembLevel, $aMembLevelInfo['Trial_Length'], true, $aVars['subscr_id']);				
					}
				}
			}
		}
	}
	function modifyInput(){
		$sCode = '<input type="hidden" name="modify" value="2" />';
		return $sCode;
	}


}

?>