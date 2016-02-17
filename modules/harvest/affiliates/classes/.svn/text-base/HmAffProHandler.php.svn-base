<?php
/***************************************************************************
Affiliates Pro v.1.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/
class HmAffProHandler{
  
	function HmAffProHandler(){
		$this->_oMain = BxDolModule::getInstance("HmAffProModule");
		$this->_oDb = $this->_oMain->_oDb;
		$this->_sPrefix = $this->_oDb->_sPrefix;
		$this->_sSiteUrl = BX_DOL_URL_ROOT;
		$this->oSession = BxDolSession::getInstance();
	}
	function approveAffiliate($aAlert){
		$sTemplateName = 't_'.$this->_sPrefix.'approved';
		foreach ($aAlert->aExtras as $iAffiliateId){
			$aAffiliate = $this->_oDb->getAffiliate($iAffiliateId);
			$rEmailTemplate = new BxDolEmailTemplates();
			$aTemplate = $rEmailTemplate->getTemplate($sTemplateName);
			$sRecipient = $aAffiliate['user_email'];
			$aPlus = array('AffiliateName' => $aAffiliate['first_name'], 'SiteUrl' => $this->_sSiteUrl);	
			sendMail($sRecipient, $aTemplate['Subject'], $aTemplate['Body'], '', $aPlus);
		}
	}
	function handleJoin($aAlert){
		$iId = $aAlert->iObject;
		$sApTrack = base64_decode($this->oSession->getValue('ap_track'));
		$iIp = base64_decode($this->oSession->getValue('ap_track_ip'));
		if($sApTrack != false){
			list($iAid, $iBid) = explode(':', $sApTrack);
			BxDolService::call('affiliates', 'track_commission', array($iAid, $iBid, $iIp, 'join'));
			$this->_oDb->setUserReferralData($iId,$sApTrack);
		}
	}
	function handleSetMembership($aAlert){
		$aProfile = getProfileInfo($aAlert->iSender);
		$iIp = '-1';
		if($aProfile['ap_data'] != ''){
			list($iAid, $iBid) = explode(':', $aProfile['ap_data']);
			BxDolService::call('affiliates', 'track_commission', array($iAid, $iBid, $iIp, 'membership', $aProfile['ID'], $aAlert->aExtras['mlevel'], $aAlert->aExtras['txn_id'], ));
		}
	}
}
?>
