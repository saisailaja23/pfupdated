<?php
/***************************************************************************
Dolphin Subscriptions v.2.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/
bx_import('BxDolPageView');

class HmSubsPageMain extends BxDolPageView {
    var $_oMain;
    var $_oTemplate;
    var $_oConfig;
    var $_oDb;

    function HmSubsPageMain(&$oMain) {
        $this->_oMain = &$oMain;
        $this->_oTemplate = $oMain->_oTemplate;
        $this->_oConfig = $oMain->_oConfig;
        $this->_oDb = $oMain->_oDb;
		parent::BxDolPageView('dolphin_subs_main');
	}

    function getBlockCode_Tight() {
		$iId = getLoggedId();
		return BxDolService::call('memberships', 'current_membership', array($iId));
    }

    function getBlockCode_Wide() {
		if($_POST['joined_free'] == 'success'){
			$aAvailLevels = MsgBox(_t('_dol_subs_joined_free'),3);
		}
		$aLevels = $this->_oDb->getMemberships();
		global $site;
        if (is_array($aLevels) && count($aLevels) > 0) { 
    		$aAvailLevels.= $this->_oTemplate->availMemLevels(); 
 		}else{
			$aAvailLevels = MsgBox('_dol_subs_no_levels');
		}
        return DesignBoxContent( '', $aAvailLevels);
	}
}
?>