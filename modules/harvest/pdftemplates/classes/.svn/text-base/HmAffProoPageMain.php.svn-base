<?php
/***************************************************************************
Affiliates Pro v.1.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/
bx_import('BxDolPageView');

class HmAffProoPageMain extends BxDolPageView {
    var $_oMain;
    var $_oTemplate;
    var $_oConfig;
    var $_oDb;

    function HmAffProoPageMain(&$oMain){
        $this->_oMain = &$oMain;
        $this->_oTemplate = $oMain->_oTemplate;
        $this->_oConfig = $oMain->_oConfig;
        $this->_oDb = $oMain->_oDb;
		parent::BxDolPageView('dolphin_aff_main');
	}
    function getBlockCode_Tight(){
		$iId = getLoggedId();
		$aAffiliate = $this->_oDb->getAffiliateInfo($iId);
		if(!$this->_oDb->isAffiliate($iId)){
	        $aVars = array('msg' =>  MsgBox(_t('_dol_aff_account_not_set')));
        	return $this->_oTemplate->parseHtmlByName('account_info', $aVars);
		}elseif($this->_oDb->isAffiliate($iId) && $aAffiliate['status'] == 'pending'){
	        $aVars = array('msg' =>  MsgBox(_t('_dol_aff_account_pending')));
        	return $this->_oTemplate->parseHtmlByName('account_info', $aVars);
		}		
        affilates_class_import('PageHome');
        $oPage = new HmAffProoPageHome($this);
		$sCode.= $oPage->getAffiliateInfoBlock();
		return $sCode;
    }
    function getBlockCode_Wide(){
		$iId = getLoggedId();
		$aAffiliate = $this->_oDb->getAffiliateInfo($iId);
		if(!$this->_oDb->isAffiliate($iId)){
	        $aVars = array('msg' =>  MsgBox(_t('_dol_aff_account_not_set')));
        	return $this->_oTemplate->parseHtmlByName('account_info', $aVars);
		}elseif($this->_oDb->isAffiliate($iId) && $aAffiliate['status'] == 'pending'){
	        $aVars = array('msg' =>  MsgBox(_t('_dol_aff_account_pending')));
        	return $this->_oTemplate->parseHtmlByName('account_info', $aVars);
		}
        affilates_class_import('PageHome');
        $oPage = new HmAffProPageHome($this);
		$sCode.= $oPage->getAvailableBannersBlock();
		return $sCode;
	}
}
?>