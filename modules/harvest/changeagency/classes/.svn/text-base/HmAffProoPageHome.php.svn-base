<?php
/***************************************************************************
Affiliates Pro v.1.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/
bx_import('BxDolAlerts');

class HmAffProoPageHome{
  
	function HmAffProoPageHome(){
		$this->_oMain = BxDolModule::getInstance("HmAffProoModule");
		$this->_oDb = $this->_oMain->_oDb;
		$this->_oTemplate = $this->_oMain->_oTemplate;
		$this->mBase = BX_DOL_URL_ROOT.'m/pdftemplates/';
		$this->mInc = BX_DOL_URL_ROOT.'modules/harvest/pdftemplates/inc/';
		$this->imgs = BX_DOL_URL_MODULES.'harvest/pdftemplates/images/';
        $this->sBannersUrl = BX_DOL_URL_MODULES.'harvest/pdftemplates/images/banners/';
        $this->sBannersPath = BX_DIRECTORY_PATH_MODULES.'harvest/pdftemplates/images/banners/';
		$this->curUserId = getLoggedId();
		$this->sCur = getParam('currency_sign');
	}
	
}
?>