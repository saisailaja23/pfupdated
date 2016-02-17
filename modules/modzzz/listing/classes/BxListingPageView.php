<?php
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

bx_import('BxDolTwigPageView');

class BxListingPageView extends BxDolTwigPageView {	

	function BxListingPageView(&$oMain, &$aDataEntry) {
		parent::BxDolTwigPageView('modzzz_listing_view', $oMain, $aDataEntry);
	
        $this->sSearchResultClassName = 'BxListingSearchResult';
        $this->sFilterName = 'modzzz_listing_filter';

        $this->sUrlStart = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/'. $this->aDataEntry['uri'];
        $this->sUrlStart .= (false === strpos($this->sUrlStart, '?') ? '?' : '&');  
	}
		
	function getBlockCode_Info() {
        return $this->_blockInfo ($this->aDataEntry);
    }

	//override the similar mod in the parent class
    function _blockInfo ($aData) {

        $aAuthor = getProfileInfo($aData['author_id']);
 
		$sAuthorName =  $aAuthor['NickName'];
		$sAuthorLink = getProfileLink($aAuthor['ID']);	
		$icoThumb = get_member_thumbnail($aAuthor['ID'], 'none');
	 
        $aVars = array (
            'author_thumb' => $icoThumb,
            'date' => getLocaleDate($aData['created'], BX_DOL_LOCALE_DATE_SHORT),
            'date_ago' => defineTimeInterval($aData['created']),
             'cats' => $this->_oDb->getCategoryName($aData['category_id']),
			'tags' => $this->_oTemplate->parseTags($aData['tags']),
            'fields' => '',
            'author_username' => $sAuthorName,
            'author_url' => $sAuthorLink,
        );
        return $this->_oTemplate->parseHtmlByName('entry_view_block_info', $aVars);
    }

    /**
     * Profile block with user's location map
     * @param $iEntryId user's id which location is shown on the map
     * @return html with user's location map
     */         
  	function getBlockCode_Map() {

        $iEntryId = (int)$this->aDataEntry['id'];   
		$iAuthorId = (int)$this->aDataEntry['author_id'];   
	 
        $r = $this->_oDb->getProfileById($iEntryId);

        $sBoxContent = '';
        if ($r && $this->_oMain->isAllowedViewLocation ($iEntryId, $r)) {

            $aVars = array (
                'msg_incorrect_google_key' => _t('_modzzz_listing_msg_incorrect_google_key'),
                'loading' => _t('_loading ...'),
                'map_control' => getParam('modzzz_listing_profile_control_type'),
                'map_is_type_control' => getParam('modzzz_listing_profile_is_type_control') == 'on' ? 1 : 0,
                'map_is_scale_control' => getParam('modzzz_listing_profile_is_scale_control') == 'on' ? 1 : 0,
                'map_is_overview_control' => getParam('modzzz_listing_profile_is_overview_control') == 'on' ? 1 : 0,
                'map_is_dragable' => getParam('modzzz_listing_profile_is_map_dragable') == 'on' ? 1 : 0,
                'map_lat' => $r['lat'],
                'map_lng' => $r['lng'],
                'map_zoom' => -1 == $r['zoom'] ? getParam('modzzz_listing_profile_zoom') : $r['zoom'],
                'map_type' => !$r['type'] ? getParam('modzzz_listing_profile_map_type') : $r['type'],
                'suffix' => 'Profile',
                'subclass' => 'modzzz_listing_profile',
                'data_url' => BX_DOL_URL_MODULES . "' + '?r=listing/get_data_profile/" . $iEntryId . "/{instance}",
                'save_data_url' => '',
                'save_location_url' => '',
                'shadow_url' => $this->_oTemplate->getIconUrl ('profile_icon_shadow.png'),
            );
            $this->_oTemplate->addJs ('http://www.google.com/jsapi?key=' . getParam('modzzz_listing_key'));
            $this->_oTemplate->addJs ('BxMap.js');
            $this->_oTemplate->addCss ('main.css');

            $aVars2 = array (
                'text' => $r['address'] ? $r['address'] : _t('_modzzz_listing_the_same_address'), 
                'map' => $this->_oTemplate->parseHtmlByName('map', $aVars),
            );
            $sBoxContent = $this->_oTemplate->parseHtmlByName('user_location', $aVars2);
        }

        $sBoxFooter = '';
        if ($iAuthorId == $this->_oMain->_iProfileId) {
            $aVars = array (
                'icon' => $this->_oTemplate->getIconUrl('more.png'),
                'url' => $this->_oConfig->getBaseUri() . "map_edit/{$iEntryId}",
                'title' => _t('_modzzz_listing_box_footer_edit'),
            );
            $sBoxFooter = $this->_oTemplate->parseHtmlByName('box_footer', $aVars);
            if (!$sBoxContent)
                $sBoxContent = MsgBox(_t('_modzzz_listing_msg_locations_is_not_defined'));
        }

        if ($sBoxContent || $sBoxFooter)
            return array($sBoxContent, array(), $sBoxFooter);
        return '';
    }
 
	function getBlockCode_Desc() {
        return $this->_oTemplate->blockDesc ($this->aDataEntry);
    }

	function getBlockCode_BusinessContact() {
        return $this->_blockCustomDisplay ($this->aDataEntry, 'businesscontact');
    }

	function getBlockCode_Location() {
        return $this->_blockCustomDisplay ($this->aDataEntry, 'location');
    }
 
 
	function _blockCustomDisplay($aDataEntry, $sType) {
		
		switch($sType)
		{  
			case "businesscontact":
				$aAllow = array('businessname','businesswebsite','businessemail','businesstelephone','businessfax');
			break;
			case "location":
				$aAllow = array('address1','address2','city','state','country','zip');
			break;  
		}
  
		$sFields = $this->_oTemplate->blockCustomFields($aDataEntry,$aAllow);

		$aVars = array ( 
            'fields' => $sFields, 
        );

        return $this->_oTemplate->parseHtmlByName('custom_block_info', $aVars);   
    }

	function getBlockCode_VideoEmbed() { 
		return trim($this->aDataEntry['video_embed']); 
	}

	function getBlockCode_Photo() {
        return $this->_blockPhoto ($this->_oDb->getMediaIds($this->aDataEntry['id'], 'images'), $this->aDataEntry['author_id']);
    }    

    function getBlockCode_Video() {
        return $this->_blockVideo ($this->_oDb->getMediaIds($this->aDataEntry['id'], 'videos'), $this->aDataEntry['author_id']);
    }    

    function getBlockCode_Sound() {
        return $this->_blockSound ($this->_oDb->getMediaIds($this->aDataEntry['id'], 'sounds'), $this->aDataEntry['author_id']);
    }    

    function getBlockCode_Files() {
        return $this->_blockFiles ($this->_oDb->getMediaIds($this->aDataEntry['id'], 'files'), $this->aDataEntry['author_id']);
    }    

    function getBlockCode_Rate() {
        modzzz_listing_import('Voting');
        $o = new BxListingVoting ('modzzz_listing', (int)$this->aDataEntry['id']);
        if (!$o->isEnabled()) return '';
        return $o->getBigVoting ($this->_oMain->isAllowedRate($this->aDataEntry));
    }        

    function getBlockCode_Comments() {    
        modzzz_listing_import('Cmts');
        $o = new BxListingCmts ('modzzz_listing', (int)$this->aDataEntry['id']);
        if (!$o->isEnabled()) return '';
        return $o->getCommentsFirst ();
    }            

    function getBlockCode_Actions() {
        global $oFunctions;

        if ($this->_oMain->_iProfileId || $this->_oMain->isAdmin()) {

            $oSubscription = new BxDolSubscription();
            $aSubscribeButton = $oSubscription->getButton($this->_oMain->_iProfileId, 'modzzz_listing', '', (int)$this->aDataEntry['id']);
			
			$isFan = $this->_oDb->isFan((int)$this->aDataEntry['id'], $this->_oMain->_iProfileId, 0) || $this->_oDb->isFan((int)$this->aDataEntry['id'], $this->_oMain->_iProfileId, 1);

            $aInfo = array (
                'BaseUri' => $this->_oMain->_oConfig->getBaseUri(),
                'iViewer' => $this->_oMain->_iProfileId,
                'ownerID' => (int)$this->aDataEntry['author_id'],
                'ID' => (int)$this->aDataEntry['id'],
                'URI' => $this->aDataEntry['uri'],
                'ScriptSubscribe' => $aSubscribeButton['script'],
                'TitleSubscribe' => $aSubscribeButton['title'], 
                'TitleEdit' => $this->_oMain->isAllowedEdit($this->aDataEntry) ? _t('_modzzz_listing_action_title_edit') : '',
                'TitleDelete' => $this->_oMain->isAllowedDelete($this->aDataEntry) ? _t('_modzzz_listing_action_title_delete') : '',
                'TitleShare' => $this->_oMain->isAllowedShare($this->aDataEntry) ? _t('_modzzz_listing_action_title_share') : '',
                'TitleJoin' => $this->_oMain->isAllowedJoin($this->aDataEntry) ? ($isFan ? _t('_modzzz_listing_action_title_leave') : _t('_modzzz_listing_action_title_join')) : '',
				'TitleInvite' => $this->_oMain->isAllowedSendInvitation($this->aDataEntry) ? _t('_modzzz_listing_action_title_promote') : '',  
				'TitleClaim' => $this->_oMain->isAllowedClaim($this->aDataEntry) ? _t('_modzzz_listing_action_title_claim') : '',
                'TitleInquire' => $this->_oMain->isAllowedInquire($this->aDataEntry) ? _t('_modzzz_listing_action_title_inquire') : '',
				'AddToFeatured' => $this->_oMain->isAllowedMarkAsFeatured($this->aDataEntry) ? ($this->aDataEntry['featured'] ? _t('_modzzz_listing_action_remove_from_featured') : _t('_modzzz_listing_action_add_to_featured')) : '',
                'TitleManageFans' => $this->_oMain->isAllowedManageFans($this->aDataEntry) ? _t('_modzzz_listing_action_manage_fans') : '',
				'TitlePurchaseFeatured' => $this->_oMain->isAllowedPurchaseFeatured($this->aDataEntry) ? ($this->aDataEntry['featured'] ? _t('_modzzz_listing_action_title_extend_featured') : _t('_modzzz_listing_action_title_purchase_featured')) : '',
		 
				'TitleUploadPhotos' => $this->_oMain->isAllowedUploadPhotos($this->aDataEntry) ? _t('_modzzz_listing_action_upload_photos') : '',
                'TitleUploadVideos' => $this->_oMain->isAllowedUploadVideos($this->aDataEntry) ? _t('_modzzz_listing_action_upload_videos') : '',
                'TitleUploadSounds' => $this->_oMain->isAllowedUploadSounds($this->aDataEntry) ? _t('_modzzz_listing_action_upload_sounds') : '',
                'TitleUploadFiles' => $this->_oMain->isAllowedUploadFiles($this->aDataEntry) ? _t('_modzzz_listing_action_upload_files') : '',
            );

            if (!$aInfo['TitleEdit'] && !$aInfo['TitleDelete'] && !$aInfo['TitleShare'] && !$aInfo['AddToFeatured'] && !$aInfo['TitleUploadPhotos'] && !$aInfo['TitleUploadVideos'] && !$aInfo['TitleUploadSounds'] && !$aInfo['TitleUploadFiles'] && !$aInfo['TitleClaim'] && !$aInfo['TitleInquire']) 
                return '';

            return $oSubscription->getData() . $oFunctions->genObjectsActions($aInfo, 'modzzz_listing');
        } 

        return '';
    }    
 
	function getBlockCode_Local() {    
		return $this->ajaxBrowse('local', $this->_oDb->getParam('modzzz_listing_perpage_main_recent'),array(),$this->aDataEntry['city'],$this->aDataEntry['id']); 
	}

	function getBlockCode_Other() {    
		return $this->ajaxBrowse('other', $this->_oDb->getParam('modzzz_listing_perpage_main_recent'),array(),$this->aDataEntry['author_id'],$this->aDataEntry['id']); 
	}

    function ajaxBrowse($sMode, $iPerPage, $aMenu = array(), $sValue = '', $sValue2 = '', $isDisableRss = false, $isPublicOnly = true) {
        $oMain = BxDolModule::getInstance('BxListingModule');

        bx_import ('SearchResult', $oMain->_aModule);
        $sClassName = $this->sSearchResultClassName;
        $o = new $sClassName($sMode, $sValue, $sValue2);
        $o->aCurrent['paginate']['perPage'] = $iPerPage; 
        $o->setPublicUnitsOnly($isPublicOnly);

        if (!$aMenu)
            $aMenu = ($isDisableRss ? '' : array(_t('RSS') => array('href' => $o->aCurrent['rss']['link'] . (false === strpos($o->aCurrent['rss']['link'], '?') ? '?' : '&') . 'rss=1', 'icon' => getTemplateIcon('rss.png'))));

        if ($o->isError)
            return array(MsgBox(_t('_Error Occured')), $aMenu);

        if (!($s = $o->displayResultBlock())) 
            return $isPublicOnly ? array(MsgBox(_t('_Empty')), $aMenu) : '';


        $sFilter = (false !== bx_get($this->sFilterName)) ? $this->sFilterName . '=' . bx_get($this->sFilterName) . '&' : '';
        $oPaginate = new BxDolPaginate(array(
            'page_url' => 'javascript:void(0);',
            'count' => $o->aCurrent['paginate']['totalNum'],
            'per_page' => $o->aCurrent['paginate']['perPage'],
            'page' => $o->aCurrent['paginate']['page'],
            'on_change_page' => 'return !loadDynamicBlock({id}, \'' . $this->sUrlStart . $sFilter . 'page={page}&per_page={per_page}\');',
        ));
        $sAjaxPaginate = $oPaginate->getSimplePaginate($this->_oConfig->getBaseUri() . $o->sBrowseUrl);

        return array(
            $s, 
            $aMenu,
            $sAjaxPaginate,
            '');
    }   

    function getBlockCode_Fans() {
        return parent::_blockFans ($this->_oDb->getParam('modzzz_listing_perpage_view_fans'), 'isAllowedViewFans', 'getFans');
    }            

    function getBlockCode_FansUnconfirmed() {
        return parent::_blockFansUnconfirmed (BX_LISTING_MAX_FANS);
    }

    function getCode() {
 
        $this->_oMain->_processFansActions ($this->aDataEntry, BX_LISTING_MAX_FANS);

        return parent::getCode();
    }

}

?>
