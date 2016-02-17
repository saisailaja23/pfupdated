<?
/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Listing
*     website              : http://www.boonex.com
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License as published by the
* Free Software Foundation; either version 2 of the
* License, or  any later version.
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

bx_import('BxDolTwigTemplate');
bx_import('BxDolCategories');

/*
 * Listing module View
 */
class BxListingTemplate extends BxDolTwigTemplate {

    var $_iPageIndex = 500;      
    var $oDb;

	/**
	 * Constructor
	 */
	function BxListingTemplate(&$oConfig, &$oDb) {
        parent::BxDolTwigTemplate($oConfig, $oDb);

		$this->oDb = $oDb; 

    }
 

     function claim_unit ($aData, $sTemplateName, &$oVotingView) {

        if (null == $this->_oMain)
            $this->_oMain = BxDolModule::getInstance('BxListingModule');
 
 		$aCategory = $this->oDb->getCategoryInfo($aData['category_id']);
		$sCategoryUrl = $this->oDb->getSubCategoryUrl($aCategory['uri']);
 
        $aAuthor = getProfileInfo($aData['author_id']);
  
        $sImage = '';
        if ($aData['thumb']) {
            $a = array ('ID' => $aData['author_id'], 'Avatar' => $aData['thumb']);
            $aImage = BxDolService::call('photos', 'get_image', array($a, 'browse'), 'Search');
            $sImage = $aImage['no_image'] ? '' : $aImage['file'];
        } 
 
        modzzz_listing_import('Voting');
        $oRating = new BxListingVoting ('modzzz_listing', $aData['id']);
 
		$sOwnerThumb = $GLOBALS['oFunctions']->getMemberIcon($aAuthor['ID'], 'left'); 
	  
		$sDateTime = defineTimeInterval($aData['created']);
     
		$sPostText = $aData['desc'];
		$iLimitChars = (int)getParam('modzzz_listing_max_preview');
		if (strlen($sPostText) > $iLimitChars) {
 			$sLinkMore = '...';
			$sPostText = mb_substr(strip_tags($sPostText), 0, $iLimitChars) . $sLinkMore;
		}
 
        $aVars = array (
 		    'id' => $aData['id'],  
			'image_url' => !$aImage['no_image'] && $aImage['file'] ? $aImage['file'] : $this->getIconUrl('no-photo-110.png'),
            'image_title' => !$aImage['no_image'] && $aImage['title'] ? $aImage['title'] : '',        
			
            'thumb_url' => $sImage ? $sImage : $this->getIconUrl('no-photo.png'), 
    
            'listing_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aData['uri'],
            'listing_title' => $aData['title'],
            'listing_description' => $sPostText,
            'comments_count' => $aData['comments_count'],
            'post_date' => strtolower($sDateTime), 
            'post_uthumb' => $sOwnerThumb,
            'post_picture2' => $sPostPicture, 
            'all_categories' => '<a href="'.$sCategoryUrl.'">'.$aCategory['name'].'</a>',  
            'post_tags' => $this->_oMain->parseTags($aData['tags']), 
            'author_title' => _t('_From'),
            'author_username' => $sAuthorName,
            'author_url' => $sAuthorLink,
            'rating' => $oRating->isEnabled() ? $oRating->getJustVotingElement (true, $aData['id']) : '',
			'claim_message' => $aData['message'],
			'claimant_url' => getProfileLink($aData['member_id']),
			'claimant_name' => getNickName($aData['member_id']),
			'claim_date' => $this->filterDate($aData['claim_date'], true), 

        );
 
        return $this->parseHtmlByName($sTemplateName, $aVars);
    }

    function order_unit ($aData, $sTemplateName, &$oVotingView) {

        if (null == $this->_oMain)
            $this->_oMain = BxDolModule::getInstance('BxListingModule');
  
        $sAuthorName = getNickName($aData['author_id']); 
		$sAuthorLink = getProfileLink($aData['author_id']);  
 		$sCreateDate = $this->filterDate($aData['order_date']);
  		$sDueDate = $this->filterDate($aData['expiry_date']);
		$sPackageName = $this->_oMain->_oDb->getPackageName($aData['package_id']);
      
        $aVars = array (
 		    'id' => $aData['id'],  
            'listing_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aData['uri'],
            'listing_title' => $aData['title'],
            'create_date' => $sCreateDate,
            'due_date' => $sDueDate, 
            'author_username' => $sAuthorName,
            'author_url' => $sAuthorLink,
            'invoice_no' => $aData['invoice_no'],
            'order_no' => $aData['order_no'],
            'package' => $sPackageName, 
            'product_status' => $this->getStatus($aData['status']),
            'order_status' => $this->getStatus($aData['order_status']),
			'payment_method' => $aData['payment_method'],
            'invoice_no' => $aData['invoice_no'],
		

         );
 
        return $this->parseHtmlByName($sTemplateName, $aVars);
    }

    function invoice_unit ($aData, $sTemplateName, &$oVotingView) {

        if (null == $this->_oMain)
            $this->_oMain = BxDolModule::getInstance('BxListingModule');
 
        $sAuthorName = getNickName($aData['author_id']); 
		$sAuthorLink = getProfileLink($aData['author_id']);  
 		$sCreateDate = $this->filterDate($aData['invoice_date']);
  		$sDueDate = $this->filterDate($aData['invoice_due_date']);
  		$sExpiryDate = $this->filterDate($aData['invoice_expiry_date']);
 
		$sPackageName = $this->_oMain->_oDb->getPackageName($aData['package_id']);

        $aVars = array (
 		    'id' => $aData['listing_id'],  
            'listing_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aData['uri'],
            'listing_title' => $aData['title'],
            'create_date' => $sCreateDate,
            'due_date' => $sDueDate, 
            'expiry_date' => $sExpiryDate, 
            'author_username' => $sAuthorName,
            'author_url' => $sAuthorLink,
            'invoice_id' => $aData['id'], 
            'invoice_no' => $aData['invoice_no'],
            'package' => $sPackageName, 
            'invoice_status' => $this->getStatus($aData['invoice_status']),
            'total' => number_format($aData['price']),

         );
 
        return $this->parseHtmlByName($sTemplateName, $aVars);
    }


    function unit ($aData, $sTemplateName, &$oVotingView) {

        if (null == $this->_oMain)
            $this->_oMain = BxDolModule::getInstance('BxListingModule');

        if (!$this->_oMain->isAllowedView ($aData)) {            
            $aVars = array ('extra_css_class' => 'modzzz_listing_unit');
            return $this->parseHtmlByName('browse_unit_private', $aVars);
        }
 
 		$aCategory = $this->oDb->getCategoryInfo($aData['category_id']);
		$sCategoryUrl = $this->oDb->getSubCategoryUrl($aCategory['uri']);
 
        $aAuthor = getProfileInfo($aData['author_id']);
/*
        $sImageUrl = ''; 
        $sImageTitle = ''; 
        $a = array ('ID' => $aData['author_id'], 'Avatar' => $aData['thumb']);
        $aImage = BxDolService::call('photos', 'get_image', array($a, 'file'), 'Search');
*/

        $sImage = '';
        if ($aData['thumb']) {
            $a = array ('ID' => $aData['author_id'], 'Avatar' => $aData['thumb']);
            $aImage = BxDolService::call('photos', 'get_image', array($a, 'browse'), 'Search');
            $sImage = $aImage['no_image'] ? '' : $aImage['file'];
        } 
 
        modzzz_listing_import('Voting');
        $oRating = new BxListingVoting ('modzzz_listing', $aData['id']);
 
		$sOwnerThumb = $GLOBALS['oFunctions']->getMemberIcon($aAuthor['ID'], 'left'); 
	  
		$sDateTime = defineTimeInterval($aData['created']);
     
		$sPostText = $aData['desc'];
		$iLimitChars = (int)getParam('modzzz_listing_max_preview');
		if (strlen($sPostText) > $iLimitChars) {
 			$sLinkMore = '...';
			$sPostText = mb_substr(strip_tags($sPostText), 0, $iLimitChars) . $sLinkMore;
		}
 
        $aVars = array (
 		    'id' => $aData['id'],  
			'image_url' => !$aImage['no_image'] && $aImage['file'] ? $aImage['file'] : $this->getIconUrl('no-photo-110.png'),
            'image_title' => !$aImage['no_image'] && $aImage['title'] ? $aImage['title'] : '',        
			
            'thumb_url' => $sImage ? $sImage : $this->getIconUrl('no-photo.png'), 
    
            'listing_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aData['uri'],
            'listing_title' => $aData['title'],
            'listing_description' => $sPostText,
            'comments_count' => $aData['comments_count'],
            'post_date' => strtolower($sDateTime), 
            'post_uthumb' => $sOwnerThumb,
            'post_picture2' => $sPostPicture, 
            'all_categories' => '<a href="'.$sCategoryUrl.'">'.$aCategory['name'].'</a>',  
            'post_tags' => $this->_oMain->parseTags($aData['tags']), 
            'author_title' => _t('_From'),
            'author_username' => $sAuthorName,
            'author_url' => $sAuthorLink,
            'rating' => $oRating->isEnabled() ? $oRating->getJustVotingElement (true, $aData['id']) : '',
        );
 
        return $this->parseHtmlByName($sTemplateName, $aVars);
    }

    // ======================= ppage compose block functions 

    function blockDesc (&$aDataEntry) {
        $aVars = array (
             'description' => $aDataEntry['desc'],  
        );
        return $this->parseHtmlByName('block_description', $aVars);
    }

    function blockFields (&$aDataEntry) {
        
		$bHasValues = false;

		$sRet = '<table class="modzzz_listing_fields">';
        modzzz_listing_import ('FormAdd');        
        $oForm = new BxListingFormAdd ($GLOBALS['oBxListingModule'], $_COOKIE['memberID']);
        foreach ($oForm->aInputs as $k => $a) {
            if (!isset($a['display'])) continue;
 
            $sRet .= '<tr><td class="modzzz_listing_field_name" valign="top">'. $a['caption'] . '<td><td class="modzzz_listing_field_value">';
            if (is_string($a['display']) && is_callable(array($this, $a['display'])))
                $sRet .= call_user_func_array(array($this, $a['display']), array($aDataEntry[$k]));
            else
                $sRet .= $aDataEntry[$k];
            $sRet .= '<td></tr>';

			$bHasValues = true; 
        }

		if(!$bHasValues)
			return;

        $sRet .= '</table>';

        return $sRet;
    }

	function getPreListDisplay($sField, $sVal){ 
 		return htmlspecialchars_adv( _t($GLOBALS['aPreValues'][$sField][$sVal]['LKey']) );
	}
  
	function getStateName($sField, $sVal){ 
		return $this->oDb->getStateName($sVal);
	}
   
    function blockCustomFields (&$aDataEntry, $aShow=array()) {
        
		$bHasValues = false;
	
		$sRet = '<table class="modzzz_listing_fields">';
        modzzz_listing_import ('FormAdd');        
        $oForm = new BxListingFormAdd ($GLOBALS['oBxListingModule'], $_COOKIE['memberID']);
        foreach ($oForm->aInputs as $k => $a) {
            if (!isset($a['display'])) continue;
 
            if (!in_array($a['name'],$aShow)) continue;
            
			if (!trim($aDataEntry[$k])) continue;

            $sRet .= '<tr><td class="modzzz_listing_field_name" valign="top">'. $a['caption'] . '<td><td class="modzzz_listing_field_value">';
            if (is_string($a['display']) && is_callable(array($this, $a['display']))){
                $sRet .= call_user_func_array(array($this, $a['display']), array($a['listname'],$aDataEntry[$k]));
			}else{
				if($a['name'] == 'businesswebsite')
					$sRet .= "<a target=_blank href='".((substr($aDataEntry[$k],0,3)=="www") ? "http://".$aDataEntry[$k] : $aDataEntry[$k])."'>".$aDataEntry[$k]."</a>";
				else
					$sRet .= $aDataEntry[$k];
			}
            $sRet .= '<td></tr>';

			$bHasValues = true; 
        }

		if(!$bHasValues)
			return;

        $sRet .= '</table>';
        return $sRet;
    }
 
	function getOptionDisplay($sField='',$sVal='')
	{ 
		return ucwords($sVal);
	}
  
	function getStatus($sStatus){
		switch($sStatus){
			case "pending":
				$sLangStatus = _t("_modzzz_listing_pending");
			break;
			case "paid":
				$sLangStatus = _t("_modzzz_listing_paid");
			break;
			case "active":
				$sLangStatus = _t("_modzzz_listing_active");
			break;
			case "inactive":
				$sLangStatus = _t("_modzzz_listing_inactive");
			break;
			case "approved":
				$sLangStatus = _t("_modzzz_listing_approved");
			break;
			case "expired":
				$sLangStatus = _t("_modzzz_listing_expired");
			break;

		}

		return $sLangStatus;
	}

    function filterDate ($i, $bLongFormat = false) {
		if($bLongFormat)
			return getLocaleDate($i, BX_DOL_LOCALE_DATE) . ' ('.defineTimeInterval($i) . ')'; 
		else
			return getLocaleDate($i, BX_DOL_LOCALE_DATE);
	}


	function displayAvailableLevels($aValues) {
	    $sCurrencyCode = strtolower($this->_oConfig->getCurrencyCode());
	    $sCurrencySign = $this->_oConfig->getCurrencySign();
 
	    $aMemberships = array();
	    foreach($aValues as $aValue) { 
  
            $aMemberships[] = array(
                'url_root' => BX_DOL_URL_ROOT,
                'id' => $aValue['id'],
                'title' => $aValue['name'],
                'description' => str_replace("\$", "&#36;", $aValue['description']),
                'days' => $aValue['days'] > 0 ?  $aValue['days'] . ' ' . _t('_membership_txt_days') : _t('_membership_txt_expires_never') ,
                'price' => $aValue['price'],
                'currency_icon' => $this->getIconUrl($sCurrencyCode . '.png'),
 	        );
	    }

		$aVars = array('bx_repeat:levels' => $aMemberships);

	    $this->addCss('levels.css');
	    return $this->parseHtmlByName('available_packages', $aVars);
	}


}

?>
