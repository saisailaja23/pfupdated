<?php
/***************************************************************************
Affiliates Pro v.1.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/
bx_import('BxDolAlerts');

class HmAffProPageHome{
  
	function HmAffProPageHome(){
		$this->_oMain = BxDolModule::getInstance("HmAffProModule");
		$this->_oDb = $this->_oMain->_oDb;
		$this->_oTemplate = $this->_oMain->_oTemplate;
		$this->mBase = BX_DOL_URL_ROOT.'m/affiliates/';
		$this->mInc = BX_DOL_URL_ROOT.'modules/harvest/affiliates/inc/';
		$this->imgs = BX_DOL_URL_MODULES.'harvest/affiliates/images/';
        $this->sBannersUrl = BX_DOL_URL_MODULES.'harvest/affiliates/images/banners/';
        $this->sBannersPath = BX_DIRECTORY_PATH_MODULES.'harvest/affiliates/images/banners/';
		$this->curUserId = getLoggedId();
		$this->sCur = getParam('currency_sign');
	}
	//----------- MAIN BLOCKS ------------//
	function getAvailableBannersBlock(){
		$aBanners = $this->_oDb->getBanners();
		$aApprovedCampaigns = $this->_oDb->getAffiliateCampaignsFromUserId($this->curUserId);
		$sMsg = (count($aBanners) > '0') ?  '' : MsgBox(_t('_dol_aff_front_no_banners'));
		$iStart = ($_GET['start']) ? $_GET['start'] : 0;
		$iPerPage = ($_GET['per_page']) ? $_GET['per_page'] : 10;
		$iCount = count($aBanners);
		foreach($aBanners as $aBanner){
			if(!in_array($aBanner['campaign_id'], $aApprovedCampaigns)) continue;
			$aCampaign = $this->_oDb->getCampaignInfo($aBanner['campaign_id']);
			$sMembershipComDisplay = '';
			if($aCampaign['membership_commission'] != 'disabled'){
				$sMembershipComDisplay = ($aCampaign['membership_commission'] == 'fixed') ? $this->sCur.number_format($aCampaign['membership_amount'],2).' per ' : $aCampaign['membership_amount'].'% of ';
			}
		   	$aItems[] = array(
		      	'id' => $aBanner['id'],
		      	'name' => $aBanner['name'],
		      	'type' => ucfirst($aBanner['type']),
				'get_code_button' => $this->_oTemplate->parseHtmlByName('back_button', array('url' => 'javascript:void();', 'button_text' => _t('_dol_aff_front_get_code'))),
				'code' => $this->displayBannerCode($aBanner['id']),
				'bx_if:click' => array(
					'condition' => ($aCampaign['click_commission'] == 'enabled'),
			        'content' => array(
			            'click_amount' => $this->sCur.number_format($aCampaign['click_amount'],2),
			        ),
				),
				'bx_if:join' => array(
					'condition' => ($aCampaign['join_commission'] == 'enabled'),
			        'content' => array(
			            'join_amount' => $this->sCur.number_format($aCampaign['join_amount'],2),
			        ),
				),
				'bx_if:membership' => array(
					'condition' => ($sMembershipComDisplay != ''),
			        'content' => array(
			            'membership_display' => $sMembershipComDisplay,
			        ),
				),
				'bx_if:text' => array(
		        	'condition' => ($aBanner['type'] == 'text'),
		          	'content' => array(
		              	'banner' => $this->_oTemplate->generateTextBannerPreview($aBanner['id']),
		           	),
		       	),	
				'bx_if:image' => array(
		        	'condition' => ($aBanner['type'] == 'image'),
		          	'content' => array(
		              	'banner' => $this->_oTemplate->generateImageBannerPreview($aBanner['id']),
		           	),
		       	),	
				'bx_if:flash' => array(
		        	'condition' => ($aBanner['type'] == 'flash'),
		          	'content' => array(
		              	'banner' => $this->_oTemplate->generateFlashBannerPreview($aBanner['id']),
		           	),
		       	),
		    );

		}
		$aVars = array(
			'bx_repeat:items' => $aItems,
			'message' => $sMsg
		);
	    $sCode = $this->_oTemplate->parseHtmlByName('avail_banners', $aVars);
		return $sCode;
	}
	function getAffiliateInfoBlock(){
		$aAffiliateData = $this->_oDb->getAffiliateDataFromUserId($this->curUserId);
      	$aForm = array(
            'affiliate_data' => array(
                'name'     => 'affiliate_data', 
                'method'   => 'post',
                'action'   => NULL,
            ),
			'params' => array (
            	'db' => array(
   					'submit_name' => 'register_aff', 
                ),
        	),
			'inputs' => array(
	            'imp' => array(
	                'type' => 'custom',
	                'caption' => _t('_dol_aff_front_impressions'),
	                'content' => $aAffiliateData['imp'],
	            ),
	            'clicks' => array(
	                'type' => 'custom',
	                'caption' => _t('_dol_aff_front_clicks'),
	                'content' => $aAffiliateData['clicks'],
	            ),
	            'paid' => array(
	                'type' => 'custom',
	                'caption' => _t('_dol_aff_front_paid'),
	                'content' => $this->sCur.number_format($aAffiliateData['paid'],2),
	            ),
	            'unpaid' => array(
	                'type' => 'custom',
	                'caption' => _t('_dol_aff_front_unpaid'),
	                'content' => $this->sCur.number_format($aAffiliateData['unpaid'],2),
	            ),
			),       
		);	
        $oForm = new BxTemplFormView ($aForm);
        $sCode = $oForm->getCode();
		$aVars = array(
			'code' => $sCode
		);  

		return $this->_oTemplate->parseHtmlByName('affiliate_data_block', $aVars);
	}
	/*--------------------------------------------------------	
		DISPLAY BANNER CODE
	--------------------------------------------------------*/
	function displayBannerCode($iBid){
		$aBannerInfo = $this->_oDb->getBannerInfo($iBid);
		$aAffiliateInfo = $this->_oDb->getAffiliateInfo($this->curUserId);
		$sFile = $this->sBannersPath.$aBannerInfo['filename'];
		$aInfo = getimagesize($sFile);
		list($iWidth, $iHeight) = $aInfo;
		$aVars = array(
			'bid' => $iBid,
			'aid' => $aAffiliateInfo['id'],
			'pipe_url' => $this->mBase.'affpro',		
		    'text_title' => $aBannerInfo['text_title'],
		    'text_details' => $aBannerInfo['text_details'],
			'banner_src' => $this->sBannersUrl.$aBannerInfo['filename'],
			'banner_swf' => $this->sBannersUrl.$aBannerInfo['filename'],
			'imp_src' => $this->mInc.'imp.php',
			'width' => $iWidth,
			'height' => $iHeight,
		);
		if($aBannerInfo['type'] == 'text'){
			$sCode = $this->_oTemplate->parseHtmlByName('banner_text_code', $aVars);
		}
		if($aBannerInfo['type'] == 'image'){
			$sCode = $this->_oTemplate->parseHtmlByName('banner_image_code', $aVars);
		}
		if($aBannerInfo['type'] == 'flash'){
			$sCode = $this->_oTemplate->parseHtmlByName('banner_flash_code', $aVars);
		}
    	return $sCode;		
	}
}
?>