<?php
/***************************************************************************
Affiliates Pro v.1.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/
class HmAffProPageStatistics{
  
	function HmAffProPageStatistics(){
		$this->_oMain = BxDolModule::getInstance("HmAffProModule");
		$this->_oDb = $this->_oMain->_oDb;
		$this->_oTemplate = $this->_oMain->_oTemplate;
		$this->mBase = BX_DOL_URL_ROOT.'m/affiliates/';
		$this->imgs = BX_DOL_URL_MODULES.'harvest/affiliates/images/';
	}
	//----------- MAIN BLOCKS ------------//
	function getStatisticsBlock(){		
	    $aVars = array(
			'impressions' => '',
			'clicks' 	  => '',
		);
		return DesignBoxAdmin('Statistics', $this->_oTemplate->parseHtmlByName('admin_statistics', $aVars));
	}
	function getBannerIcon($sType){
		if($sType == 'text') return 'text-banner-icon.png';
		if($sType == 'image') return 'image-banner-icon.png';
		if($sType == 'flash') return 'flash-banner-icon.png';
	}

}
?>