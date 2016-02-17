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

function modzzz_listing_import ($sClassPostfix, $aModuleOverwright = array()) {
    global $aModule;
    $a = $aModuleOverwright ? $aModuleOverwright : $aModule;
    if (!$a || $a['uri'] != 'listing') {
        $oMain = BxDolModule::getInstance('BxListingModule');
        $a = $oMain->_aModule;
    }
    bx_import ($sClassPostfix, $a) ;
}

bx_import('BxDolPaginate');
bx_import('BxDolAlerts');
bx_import('BxDolTwigModule');
bx_import('BxTemplSearchResult');
bx_import('BxDolCategories');


define ('BX_LISTING_PHOTOS_CAT', 'Listing');
define ('BX_LISTING_PHOTOS_TAG', 'listing');

define ('BX_LISTING_VIDEOS_CAT', 'Listing');
define ('BX_LISTING_VIDEOS_TAG', 'listing');

define ('BX_LISTING_SOUNDS_CAT', 'Listing');
define ('BX_LISTING_SOUNDS_TAG', 'listing');

define ('BX_LISTING_FILES_CAT', 'Listing');
define ('BX_LISTING_FILES_TAG', 'listing');

define ('BX_LISTING_MAX_FANS', 1000);

 
/*[begin] map integration*/
require_once( BX_DIRECTORY_PATH_PLUGINS . 'Services_JSON.php' );
 
define ('BX_LISTING_ZOOM_PROFILES', 10);
define ('BX_LISTING_ZOOM_CITIES', 5);
define ('BX_LISTING_ZOOM_COUNTRIES', 1);
define ('BX_LISTING_DEFAULT_PRIVACY', 3); 
/*[end] map integration*/

 
/*
 * Listing module
 *
 * This module allow users to create user's listing, 
 * users can rate, comment and discuss listing.
 * Listing can have photos, videos, sounds and files, uploaded
 * by listing's admins.
 *
 * 
 *
 * Profile's Wall:
 * 'add listing' event is displayed in profile's wall
 *
 *
 *
 * Spy:
 * The following qactivity is displayed for content_activity:
 * add - new listing was created
 * change - listing was chaned
 * rate - somebody rated listing
 * commentPost - somebody posted comment in listing
 *
 *
 *
 * Memberships/ACL:
 * listing view listing - BX_LISTING_VIEW_LISTING
 * listing browse - BX_LISTING_BROWSE
 * listing search - BX_LISTING_SEARCH
 * listing add listing - BX_LISTING_ADD_LISTING
 * listing comments delete and edit - BX_LISTING_COMMENTS_DELETE_AND_EDIT
 * listing edit any listing - BX_LISTING_EDIT_ANY_LISTING
 * listing delete any listing - BX_LISTING_DELETE_ANY_LISTING
 * listing mark as featured - BX_LISTING_MARK_AS_FEATURED
 * listing approve listing - BX_LISTING_APPROVE_LISTING
 *
 * 
 *
 * Service methods:
 *
 * Homepage block with different listing
 * @see BxListingModule::serviceHomepageBlock
 * BxDolService::call('listing', 'homepage_block', array());
 *
 * Profile block with user's listing
 * @see BxListingModule::serviceProfileBlock
 * BxDolService::call('listing', 'profile_block', array($iProfileId));
 *

 *
 * Member menu item for listing (for internal usage only)
 * @see BxListingModule::serviceGetMemberMenuItem
 * BxDolService::call('listing', 'get_member_menu_item', array());
 *
 *
 *
 * Alerts:
 * Alerts type/unit - 'modzzz_listing'
 * The following alerts are rised
 *
 
 *
 *  add - new listing was added
 *      $iObjectId - listing id
 *      $iSenderId - creator of a listing
 *      $aExtras['Status'] - status of added listing
 *
 *  change - listing's info was changed
 *      $iObjectId - listing id
 *      $iSenderId - editor user id
 *      $aExtras['Status'] - status of changed listing
 *
 *  delete - listing was deleted
 *      $iObjectId - listing id
 *      $iSenderId - deleter user id
 *
 *  mark_as_featured - listing was marked/unmarked as featured
 *      $iObjectId - listing id
 *      $iSenderId - performer id
 *      $aExtras['Featured'] - 1 - if listing was marked as featured and 0 - if listing was removed from featured 
 *
 */
class BxListingModule extends BxDolTwigModule {

    var $_oPrivacy;
    var $_oMapPrivacy;
    
	var $_aQuickCache = array ();

    function BxListingModule(&$aModule) {

        parent::BxDolTwigModule($aModule);        
        $this->_sFilterName = 'modzzz_listing_filter';
        $this->_sPrefix = 'modzzz_listing';

        bx_import ('Privacy', $aModule);
        $this->_oPrivacy = new BxListingPrivacy($this);

        bx_import ('MapPrivacy', $aModule);
        $this->_oMapPrivacy = new BxListingMapPrivacy($this);

	    $this->_oConfig->init($this->_oDb);

        $GLOBALS['oBxListingModule'] = &$this;

		//reloads subcategories on Add form
		if($_GET['ajax']=='cat') { 
			$iParentId = $_GET['parent'];
			echo $this->_oDb->getAjaxCategoryOptions($iParentId);
			exit;
		}  

		if($_GET['ajax']=='state') { 
			$sCountryCode = $_GET['country'];
			echo $this->_oDb->getStateOptions($sCountryCode);
			exit;
		}	


    }

    function actionHome () {
        parent::_actionHome(_t('_modzzz_listing_page_title_home'));
    }

    function actionFiles ($sUri) {
        parent::_actionFiles ($sUri, _t('_modzzz_listing_page_title_files'));
    }

    function actionSounds ($sUri) {
        parent::_actionSounds ($sUri, _t('_modzzz_listing_page_title_sounds'));
    }

    function actionVideos ($sUri) {
        parent::_actionVideos ($sUri, _t('_modzzz_listing_page_title_videos'));
    }

    function actionPhotos ($sUri) {
        parent::_actionPhotos ($sUri, _t('_modzzz_listing_page_title_photos'));
    }

    function actionComments ($sUri) {
        parent::_actionComments ($sUri, _t('_modzzz_listing_page_title_comments'));
    }

    function actionBrowseFans ($sUri) {
        parent::_actionBrowseFans ($sUri, 'isAllowedViewFans', 'getFansBrowse', $this->_oDb->getParam('modzzz_listing_perpage_browse_fans'), 'browse_fans/', _t('_modzzz_listing_page_title_fans'));
    }
  
    function actionView ($sUri) {
        parent::_actionView ($sUri, _t('_modzzz_listing_msg_pending_approval'));
    }

    function actionUploadPhotos ($sUri) {        
        parent::_actionUploadMedia ($sUri, 'isAllowedUploadPhotos', 'images', array ('images_choice', 'images_upload'), _t('_modzzz_listing_page_title_upload_photos'));
    }

    function actionUploadVideos ($sUri) {
        parent::_actionUploadMedia ($sUri, 'isAllowedUploadVideos', 'videos', array ('videos_choice', 'videos_upload'), _t('_modzzz_listing_page_title_upload_videos'));
    }

    function actionUploadSounds ($sUri) {
        parent::_actionUploadMedia ($sUri, 'isAllowedUploadSounds', 'sounds', array ('sounds_choice', 'sounds_upload'), _t('_modzzz_listing_page_title_upload_sounds')); 
    }

    function actionUploadFiles ($sUri) {
        parent::_actionUploadMedia ($sUri, 'isAllowedUploadFiles', 'files', array ('files_choice', 'files_upload'), _t('_modzzz_listing_page_title_upload_files')); 
    }
  
    function actionCalendar ($iYear = '', $iMonth = '') {
        parent::_actionCalendar ($iYear, $iMonth, _t('_modzzz_listing_page_title_calendar'));
    }

    function actionSearch ($sKeyword = '', $sCategory = '', $sCountry = '', $sState = '', $sCity = '') {

        if (!$this->isAllowedSearch()) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }

        $this->_oTemplate->pageStart();

        if ($sKeyword) 
            $_GET['Keyword'] = $sKeyword;
        if ($sCategory)
            $_GET['Category'] = $sCategory;
		if ($sCity) 
            $_GET['City'] = $sCity;
		if ($sState) 
            $_GET['State'] = $sState; 
         if ($sCountry)
            $_GET['Country'] = explode(',', $sCountry);


        if (is_array($_GET['Country']) && 1 == count($_GET['Country']) && !$_GET['Country'][0]) {
            unset($_GET['Country']);
            unset($sCountry);
        }
  
        if ($sCountry || $sCategory || $sKeyword || $sState || $sCity ) {
            $_GET['submit_form'] = 1;  
        }
        
        modzzz_listing_import ('FormSearch');
        $oForm = new BxListingFormSearch ();
        $oForm->initChecker();

        if ($oForm->isSubmittedAndValid ()) {
 
            modzzz_listing_import ('SearchResult');
            $o = new BxListingSearchResult('search', $oForm->getCleanValue('Keyword'), $oForm->getCleanValue('Category'), $oForm->getCleanValue('City'), $oForm->getCleanValue('State'), $oForm->getCleanValue('Country') );

            if ($o->isError) {
                $this->_oTemplate->displayPageNotFound ();
                return;
            }

            if ($s = $o->processing()) {
                echo $s;
            } else {
                $this->_oTemplate->displayNoData ();
                return;
            }

            $this->isAllowedSearch(true); // perform search action 

            $this->_oTemplate->addCss ('unit.css');
            $this->_oTemplate->addCss ('main.css');
            $this->_oTemplate->pageCode($o->aCurrent['title'], false, false);
            return;

        } 

        echo $oForm->getCode ();
        $this->_oTemplate->addCss ('main.css');
        $this->_oTemplate->pageCode(_t('_modzzz_listing_caption_search'));
    } 


    function actionSearchOLD ($sKeyword = '', $sCategory = '', $sCity = '', $sCountry = '') {
 
        if (!$this->isAllowedSearch()) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }

        $this->_oTemplate->pageStart();

        if ($sKeyword) 
            $_REQUEST['Keyword'] = $sKeyword;
        if ($sKeyword) 
            $_REQUEST['City'] = $sCity;
        if ($sCategory)
            $_REQUEST['Category'] = explode(',', $sCategory); 
        if ($sCountry)
            $_REQUEST['Country'] = explode(',', $sCountry);

        if (is_array($_REQUEST['Country']) && 1 == count($_REQUEST['Country']) && !$_REQUEST['Country'][0]) {
            unset ($_REQUEST['Country']);
            unset($sCountry);
        }

        if (is_array($_REQUEST['Category']) && 1 == count($_REQUEST['Category']) && !$_REQUEST['Category'][0]) {
            unset ($_REQUEST['Category']);
            unset($sCategory);
        }
 
        if ($sCategory || $sKeyword || $sCity || $sCountry) {
            $_REQUEST['submit_form'] = 1;
        }
        
        bx_import ('FormSearch', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'FormSearch';
        $oForm = new $sClass ();
        $oForm->initChecker();        

        if ($oForm->isSubmittedAndValid ()) {
 
            bx_import ('SearchResult', $this->_aModule);
            $sClass = $this->_aModule['class_prefix'] . 'SearchResult';
            $o = new $sClass('search', $oForm->getCleanValue('Keyword'), $oForm->getCleanValue('Category'), $oForm->getCleanValue('City'), $oForm->getCleanValue('Country'));
 
            if ($o->isError) {
                $this->_oTemplate->displayPageNotFound ();
                return;
            }

            if ($s = $o->processing()) {
                
                echo $s;
                
            } else {
                $this->_oTemplate->displayNoData ();
                return;
            }

            $this->isAllowedSearch(true); // perform search action 

            $this->_oTemplate->addCss ('unit.css');
            $this->_oTemplate->addCss ('main.css');
            $this->_oTemplate->pageCode($o->aCurrent['title'], false, false); 
			return;
		} 

        echo $oForm->getCode ();
        $this->_oTemplate->addCss ('main.css');
        $this->_oTemplate->pageCode(_t('_modzzz_listing_page_title_search'));
    }

    function actionAdd () {
        parent::_actionAdd (_t('_modzzz_listing_page_title_add'));
    }
  
    function _addForm ($sRedirectUrl) {
  
		$bPaidListing = $this->isAllowedPaidListings (); 
		if( $bPaidListing && (!isset($_POST['submit_form'])) ){
			return $this->showPackageSelectForm();
		}else{
			$this->showAddForm($sRedirectUrl);
		}
	}
 
    function showPackageSelectForm() {
   
		$aPackage = $this->_oDb->getPackageList();

		$aForm = array(
            'form_attrs' => array(
                'name' => 'package_form',
                'method' => 'post', 
                'action' => '',
            ),
            'params' => array (
                'db' => array(
                    'submit_name' => 'submit_package',
                ),
            ),
            'inputs' => array( 
  
                'package_id' => array( 
                    'type' => 'select',
                    'name' => 'package_id',
					'values'=> $aPackage,
                    'caption' => _t('_modzzz_listing_form_caption_package'), 
                    'required' => true,
                    'checker' => array (
                        'func' => 'avail',
                        'error' => _t ('_modzzz_listing_form_err_package'),
                    ),   
                ),  
 
                'submit' => array(
                    'type'  => 'submit',
                    'value' => _t('_modzzz_listing_continue'),
                    'name'  => 'submit_package',
                ),
            ),
        );
        $oForm = new BxTemplFormView($aForm);
        $oForm->initChecker();  
 
        if ($oForm->isSubmittedAndValid () && $oForm->getCleanValue('package_id')) {
			$this->showAddForm(false);
		}else{ 
			echo $oForm->getCode(); 
		}
    }
 
    function showAddForm($sRedirectUrl) {

		$bPaidListing = $this->isAllowedPaidListings (); 

        bx_import ('FormAdd', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'FormAdd';
        $oForm = new $sClass ($this, $this->_iProfileId);
        $oForm->initChecker();

        if ($oForm->isSubmittedAndValid ()) {
 
			if($bPaidListing)
				$sStatus = 'pending';
			else
				$sStatus =  ((getParam($this->_sPrefix.'_autoapproval') == 'on') || $this->isAdmin()) ? 'approved' : 'pending';

            $aValsAdd = array (
                $this->_oDb->_sFieldCreated => time(),
                $this->_oDb->_sFieldUri => $oForm->generateUri(),
                $this->_oDb->_sFieldStatus => $sStatus,
            );                        
            $aValsAdd[$this->_oDb->_sFieldAuthorId] = $this->_iProfileId;

            $iEntryId = $oForm->insert ($aValsAdd);
 
            if ($iEntryId) {

                $this->isAllowedAdd(true); // perform action                 

                $oForm->processMedia($iEntryId, $this->_iProfileId);

                $aDataEntry = $this->_oDb->getEntryByIdAndOwner($iEntryId, $this->_iProfileId, $this->isAdmin());
                $this->onEventCreate($iEntryId, $sStatus, $aDataEntry);
				if($bPaidListing){ 
					$iPackageId = $oForm->getCleanValue('package_id');
					$aPackage = $this->_oDb->getPackageById($iPackageId);
					$fPrice = $aPackage['price'];
					$iDays = $aPackage['days'];
 
					$sInvoiceNo = $this->_oDb->createInvoice($iEntryId, $iPackageId, $fPrice, $iDays);
						
					$this->_oDb->updateEntryInvoice($iEntryId, $sInvoiceNo); 
						 
					$sRedirectUrl = $this->_oDb->generatePaymentUrl($iEntryId, $fPrice);
				}else{
					$iNumActiveDays = (int)getParam("modzzz_listing_free_expired");
					if($iNumActiveDays)
						$this->_oDb->updateEntryExpiration($iEntryId, $iNumActiveDays); 
 
					if (!$sRedirectUrl)  
						$sRedirectUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aDataEntry[$this->_oDb->_sFieldUri];
				} 
				 
                header ('Location:' . $sRedirectUrl);
                exit;

            } else {

                MsgBox(_t('_Error Occured'));
            }
                         
        } else {
            
            echo $oForm->getCode ();

        }
    }
 

    function actionEdit ($iEntryId) {
        parent::_actionEdit ($iEntryId, _t('_modzzz_listing_page_title_edit'));
    }

    function actionDelete ($iEntryId) {
        parent::_actionDelete ($iEntryId, _t('_modzzz_listing_msg_listing_was_deleted'));
    }

    function actionMarkFeatured ($iEntryId) {
        parent::_actionMarkFeatured ($iEntryId, _t('_modzzz_listing_msg_added_to_featured'), _t('_modzzz_listing_msg_removed_from_featured'));
    }
 
    function actionSharePopup ($iEntryId) {
        parent::_actionSharePopup ($iEntryId, _t('_modzzz_listing_caption_share_listing'));
    }
 
   function actionJoin ($iEntryId, $iProfileId) {

        parent::_actionJoin ($iEntryId, $iProfileId, _t('_modzzz_listing_msg_joined_already'), _t('_modzzz_listing_msg_joined_request_pending'), _t('_modzzz_listing_msg_join_success'), _t('_modzzz_listing_msg_join_success_pending'), _t('_modzzz_listing_msg_leave_success'));
    }    
 
    function actionManageFansPopup ($iEntryId) {
        parent::_actionManageFansPopup ($iEntryId, _t('_modzzz_listing_caption_manage_fans'), 'getFans', 'isAllowedManageFans', 'isAllowedManageAdmins', BX_LISTING_MAX_FANS);
    }

    function actionTags() {
        parent::_actionTags (_t('_modzzz_listing_page_title_tags'));
    }    
 
    function actionPackages () { 
        $this->_oTemplate->pageStart();
        bx_import ('PagePackages', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'PagePackages';
        $oPage = new $sClass ($this,$sUri);
        echo $oPage->getCode();
        $this->_oTemplate->addCss ('main.css');
        $this->_oTemplate->addCss ('unit.css');
        $this->_oTemplate->pageCode(_t('_modzzz_listing_page_title_packages'), false, false);
    }

    function actionCategories ($sUri='') { 
        $this->_oTemplate->pageStart();
        bx_import ('PageCategory', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'PageCategory';
        $oPage = new $sClass ($this,$sUri);
        echo $oPage->getCode();
        $this->_oTemplate->addCss ('main.css');
        $this->_oTemplate->addCss ('unit.css');
         $this->_oTemplate->pageCode(_t('_modzzz_listing_page_title_categories'), false, false);
    }

    function actionSubCategories ($sUri='') {
        $this->_oTemplate->pageStart();
        bx_import ('PageSubCategory', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'PageSubCategory';
        $oPage = new $sClass ($this,$sUri);
        echo $oPage->getCode();
        $this->_oTemplate->addCss ('main.css');
        $this->_oTemplate->addCss ('unit.css');
         $this->_oTemplate->pageCode(_t('_modzzz_listing_page_title_subcategories'), false, false);
    }
 
    function actionDownload ($iEntryId, $iMediaId) {

        $aFileInfo = $this->_oDb->getMedia ((int)$iEntryId, (int)$iMediaId, 'files');

        if (!$aFileInfo || !($aDataEntry = $this->_oDb->getEntryByIdAndOwner((int)$iEntryId, 0, true))) {
            $this->_oTemplate->displayPageNotFound ();
            exit;
        }

        if (!$this->isAllowedView ($aDataEntry)) {
            $this->_oTemplate->displayAccessDenied ();
            exit;
        }

        parent::_actionDownload($aFileInfo, 'media_id');
    }

    function actionMakeClaimPopup ($iEntryId) {
        parent::_actionMakeClaimPopup ($iEntryId, _t('_modzzz_listing_caption_manage_fans'), 'getFans', 'isAllowedManageFans', 'isAllowedManageAdmins', modzzz_listing_MAX_FANS);
    }

    function _actionMakeClaimPopup ($iEntryId, $sTitle, $sFuncGetFans = 'getFans', $sFuncIsAllowedManageFans = 'isAllowedManageFans', $sFuncIsAllowedManageAdmins = 'isAllowedManageAdmins', $iMaxFans = 1000) {

        $iEntryId = (int)$iEntryId;
        if (!($aDataEntry = $this->_oDb->getEntryById ($iEntryId))) {
            echo $GLOBALS['oFunctions']->transBox(MsgBox(_t('_Empty')));
            exit;
        }

        if (!$this->$sFuncIsAllowedManageFans($aDataEntry)) {
            echo $GLOBALS['oFunctions']->transBox(MsgBox(_t('_Access denied')));
            exit;
        }

        $aProfiles = array ();
        $iNum = $this->_oDb->$sFuncGetFans($aProfiles, $iEntryId, true, 0, $iMaxFans);
        if (!$iNum) {
            echo $GLOBALS['oFunctions']->transBox(MsgBox(_t('_Empty')));
            exit;
        }

        $sActionsUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . "view/" . $aDataEntry[$this->_oDb->_sFieldUri] . '?ajax_action=';
        $aButtons = array (
            array (
                'type' => 'submit',
                'name' => 'fans_remove',
                'value' => _t('_sys_btn_fans_remove'),
                'onclick' => "onclick=\"getHtmlData('sys_manage_items_manage_fans_content', '{$sActionsUrl}remove&ids=' + sys_manage_items_get_manage_fans_ids()); return false;\"",
            ),
        );

        if ($this->$sFuncIsAllowedManageAdmins($aDataEntry)) {

            $aButtons = array_merge($aButtons, array (
                array (
                    'type' => 'submit',
                    'name' => 'fans_add_to_admins',
                    'value' => _t('_sys_btn_fans_add_to_admins'),
                    'onclick' => "onclick=\"getHtmlData('sys_manage_items_manage_fans_content', '{$sActionsUrl}add_to_admins&ids=' + sys_manage_items_get_manage_fans_ids()); return false;\"",
                ),
                array (
                    'type' => 'submit',
                    'name' => 'fans_move_admins_to_fans',
                    'value' => _t('_sys_btn_fans_move_admins_to_fans'),
                    'onclick' => "onclick=\"getHtmlData('sys_manage_items_manage_fans_content', '{$sActionsUrl}admins_to_fans&ids=' + sys_manage_items_get_manage_fans_ids()); return false;\"",
                ),            
            ));
        };
        bx_import ('BxTemplSearchResult');
        $sControl = BxTemplSearchResult::showAdminActionsPanel('sys_manage_items_manage_fans', $aButtons, 'sys_fan_unit');

        $aVarsContent = array (            
            'suffix' => 'manage_fans',
            'content' => $this->_profilesEdit($aProfiles, false, $aDataEntry),
            'control' => $sControl,
        );
        $aVarsPopup = array (
            'title' => $sTitle,
            'content' => $this->_oTemplate->parseHtmlByName('manage_items_form', $aVarsContent),
        );        
        echo $GLOBALS['oFunctions']->transBox($this->_oTemplate->parseHtmlByName('popup', $aVarsPopup), true);
        exit;
    }
 
    function actionInvite ($iEntryId) {
        $this->_actionInvite ($iEntryId, 'modzzz_listing_invitation', $this->_oDb->getParam('modzzz_listing_max_email_invitations'), _t('_modzzz_listing_invitation_sent'), _t('_modzzz_listing_no_users_msg'), _t('_modzzz_listing_caption_invite'));
    }

    function _actionInvite ($iEntryId, $sEmailTemplate, $iMaxEmailInvitations, $sMsgInvitationSent, $sMsgNoUsers, $sTitle) {

        $iEntryId = (int)$iEntryId;
        if (!($aDataEntry = $this->_oDb->getEntryById($iEntryId))) {
            $this->_oTemplate->displayPageNotFound ();
            return;
        }

        $this->_oTemplate->pageStart();

        $GLOBALS['oTopMenu']->setCustomSubHeader($aDataEntry[$this->_oDb->_sFieldTitle]);
        $GLOBALS['oTopMenu']->setCustomVar($this->_sPrefix.'_view_uri', $aDataEntry[$this->_oDb->_sFieldUri]);

        bx_import('BxDolTwigFormInviter');
        $oForm = new BxDolTwigFormInviter ($this, $sMsgNoUsers);
        $oForm->initChecker();

        if ($oForm->isSubmittedAndValid ()) {        

            $aInviter = getProfileInfo($this->_iProfileId);
            $aPlusOriginal = $this->_getInviteParams ($aDataEntry, $aInviter);
            
            $oEmailTemplate = new BxDolEmailTemplates();
            $aTemplate = $oEmailTemplate->getTemplate($sEmailTemplate);
            $iSuccess = 0;

            // send invitation to registered members
            if (isset($_REQUEST['inviter_users']) && is_array($_REQUEST['inviter_users'])) {
                foreach ($_REQUEST['inviter_users'] as $iRecipient) {
                    $aRecipient = getProfileInfo($iRecipient);
                    $aPlus = array_merge (array ('NickName' => ' ' . $aRecipient['NickName']), $aPlusOriginal);
                    $iSuccess += sendMail(trim($aRecipient['Email']), $aTemplate['Subject'], $aTemplate['Body'], '', $aPlus) ? 1 : 0;
                }
            }

            // send invitation to additional emails
            $iMaxCount = $iMaxEmailInvitations;
            $aEmails = preg_split ("#[,\s\\b]+#", $_REQUEST['inviter_emails']);
            $aPlus = array_merge (array ('NickName' => ''), $aPlusOriginal);
            if ($aEmails && is_array($aEmails)) {
                foreach ($aEmails as $sEmail) {
                    if (strlen($sEmail) < 5) 
                        continue;
                    $iRet = sendMail(trim($sEmail), $aTemplate['Subject'], $aTemplate['Body'], '', $aPlus) ? 1 : 0;
                    $iSuccess += $iRet;
                    if ($iRet && 0 == --$iMaxCount) 
                        break;
                }             
            }

            $sMsg = sprintf($sMsgInvitationSent, $iSuccess);
            echo MsgBox($sMsg);
            $this->_oTemplate->addCss ('main.css');
            $this->_oTemplate->pageCode ($sMsg, true, false);
            return;
        } 

        echo $oForm->getCode ();
        $this->_oTemplate->addCss ('main.css');
        $this->_oTemplate->addCss ('inviter.css');
        $this->_oTemplate->pageCode($sTitle . $aDataEntry[$this->_oDb->_sFieldTitle]);
    }
  
    function _getInviteParams ($aDataEntry, $aInviter) {
        return array (
                'ListingName' => $aDataEntry['title'],
                'ListingLocation' => _t($GLOBALS['aPreValues']['country'][$aDataEntry['Country']]['LKey']) . (trim($aDataEntry['city']) ? ', '.$aDataEntry['city'] : ''),
                'ListingUrl' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aDataEntry['uri'],
                'InviterUrl' => $aInviter ? getProfileLink($aInviter['ID']) : 'javascript:void(0);',
                'InviterNickName' => $aInviter ? $aInviter['NickName'] : _t('_modzzz_listing_user_unknown'),
                'InvitationText' => stripslashes(strip_tags($_REQUEST['inviter_text'])),
            );        
    }

 
    function actionInquire ($iEntryId) {
        $this->_actionInquire ($iEntryId, 'modzzz_listing_inquiry', _t('_modzzz_listing_caption_make_inquiry'), _t('_modzzz_listing_inquiry_sent'), _t('_modzzz_listing_inquiry_not_sent'));
    }

    function _actionInquire ($iEntryId, $sEmailTemplate, $sTitle, $sMsgSuccess, $sMsgFail) {

        $iEntryId = (int)$iEntryId;
        if (!($aDataEntry = $this->_oDb->getEntryById($iEntryId))) {
            $this->_oTemplate->displayPageNotFound ();
            return;
        }

        $this->_oTemplate->pageStart();

        $GLOBALS['oTopMenu']->setCustomSubHeader($aDataEntry[$this->_oDb->_sFieldTitle]);
        $GLOBALS['oTopMenu']->setCustomVar($this->_sPrefix.'_view_uri', $aDataEntry[$this->_oDb->_sFieldUri]);

        bx_import ('InquireForm', $this->_aModule);
		$oForm = new BxListingInquireForm ($this);
        $oForm->initChecker();

        if ($oForm->isSubmittedAndValid ()) {        
 
			$aInquirer = getProfileInfo($this->_iProfileId);
            $aPlusOriginal = $this->_getInquireParams ($aDataEntry, $aInquirer);
		  
			$iRecipient = $aDataEntry['author_id'];
 
            $oEmailTemplate = new BxDolEmailTemplates();
            $aTemplate = $oEmailTemplate->getTemplate($sEmailTemplate);
            $iSuccess = 0;
  
            // send message to listing owner
            if (isset($_REQUEST['inquire_text'])) { 
				 $aRecipient = getProfileInfo($iRecipient); 

				 $sContactEmail = trim($aDataEntry['selleremail']) ? trim($aDataEntry['selleremail']) : trim($aRecipient['Email']);
 
				 $sSubject = str_replace("<NickName>",$aInquirer['NickName'], $aTemplate['Subject']);
				 $sSubject = str_replace("<SiteName>", $GLOBALS['site']['title'], $sSubject);

				 $aPlus = array_merge (array ('RecipientName' => ' ' . $aRecipient['NickName']), $aPlusOriginal);

                 $iSuccess = sendMail($sContactEmail, $sSubject, $aTemplate['Body'], '', $aPlus) ? 1 : 0;  
			}
			
            $sMsg = ($iSuccess) ? $sMsgSuccess : $sMsgFail;
            echo MsgBox($sMsg);
            $this->_oTemplate->addCss ('main.css');
            $this->_oTemplate->pageCode ($sMsg, true, false);
            return;
        } 

        echo $oForm->getCode ();
        $this->_oTemplate->addCss ('main.css');
        $this->_oTemplate->addCss ('inviter.css');
        $this->_oTemplate->addJsAdmin ('http://www.google.com/jsapi?key=' . getParam('modzzz_listing_key'));
        $this->_oTemplate->addJsAdmin (BX_DOL_URL_MODULES . $this->_aModule['path'] . 'js/BxMap.js');
        $this->_oTemplate->pageCode($sTitle . $aDataEntry[$this->_oDb->_sFieldTitle]);
    }

    function _getInquireParams ($aDataEntry, $aInquirer) {
        return array (
                'ListTitle' => $aDataEntry['title'], 
                'ListUrl' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aDataEntry['uri'],
                'SenderLink' => $aInquirer ? getProfileLink($aInquirer['ID']) : 'javascript:void(0);',
                'SenderName' => $aInquirer ? $aInquirer['NickName'] : _t('_modzzz_listing_user_unknown'),
                'Message' => stripslashes(strip_tags($_REQUEST['inquire_text'])),
            );        
    }


/*[begin] claim*/
    function actionClaim ($iEntryId) {
        $this->_actionClaim ($iEntryId, 'modzzz_listing_claim', _t('_modzzz_listing_caption_make_claim'), _t('_modzzz_listing_claim_sent'), _t('_modzzz_listing_claim_not_sent'));
    }

    function _actionClaim ($iEntryId, $sEmailTemplate, $sTitle, $sMsgSuccess, $sMsgFail) {

        $iEntryId = (int)$iEntryId;
        if (!($aDataEntry = $this->_oDb->getEntryById($iEntryId))) {
            $this->_oTemplate->displayPageNotFound ();
            return;
        }

        $this->_oTemplate->pageStart();

        $GLOBALS['oTopMenu']->setCustomSubHeader($aDataEntry[$this->_oDb->_sFieldTitle]);
        $GLOBALS['oTopMenu']->setCustomVar($this->_sPrefix.'_view_uri', $aDataEntry[$this->_oDb->_sFieldUri]);

        bx_import ('ClaimForm', $this->_aModule);
		$oForm = new BxListingClaimForm ($this);
        $oForm->initChecker();

        if ($oForm->isSubmittedAndValid ()) {        
 
			$aClaimer = getProfileInfo($this->_iProfileId);
            $aPlusOriginal = $this->_getClaimParams ($aDataEntry, $aClaimer);
		  
			$iRecipient = $aDataEntry['author_id'];
 
            $oEmailTemplate = new BxDolEmailTemplates();
            $aTemplate = $oEmailTemplate->getTemplate($sEmailTemplate);
            $iSuccess = 0;
  
			$arrAdmins = $this->_oDb->saveClaimRequest($iEntryId, $this->_iProfileId,$_REQUEST['claim_text']);

            // send message to administrator
            if (isset($_REQUEST['claim_text'])) { 
				 
				$arrAdmins = $this->_oDb->getAdmins();

				foreach($arrAdmins as $iRecipient) { 
					$aRecipient = getProfileInfo($iRecipient); 

					$sSubject = str_replace("<NickName>",$aClaimer['NickName'], $aTemplate['Subject']);
					$sSubject = str_replace("<SiteName>", $GLOBALS['site']['title'], $sSubject);

					$aPlus = array_merge (array ('RecipientName' => ' ' . $aRecipient['NickName']), $aPlusOriginal);

					$iSuccess += sendMail(trim($aRecipient['Email']), $sSubject, $aTemplate['Body'], '', $aPlus) ? 1 : 0;  
				}
			}
			
            $sMsg = ($iSuccess) ? $sMsgSuccess : $sMsgFail;
            echo MsgBox($sMsg);
            $this->_oTemplate->addCss ('main.css');
            $this->_oTemplate->pageCode ($sMsg, true, false);
            return;
        } 

        echo $oForm->getCode ();
        $this->_oTemplate->addCss ('main.css');
        $this->_oTemplate->addCss ('inviter.css');
        $this->_oTemplate->pageCode($sTitle . $aDataEntry[$this->_oDb->_sFieldTitle]);
    }

    function _getClaimParams ($aDataEntry, $aClaimer) {
        return array (
                'ListTitle' => $aDataEntry['title'], 
                'ListUrl' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aDataEntry['uri'],
                'SenderLink' => $aClaimer ? getProfileLink($aClaimer['ID']) : 'javascript:void(0);',
                'SenderName' => $aClaimer ? $aClaimer['NickName'] : _t('_modzzz_listing_user_unknown'),
                'Message' => stripslashes(strip_tags($_REQUEST['claim_text'])),
            );        
    }
/*[end] claim*/



    // ================================== external actions

    /**
     * Homepage block with different listing
     * @return html to display on homepage in a block
     */     
    function serviceHomepageBlock () {

        if (!$this->_oDb->isAnyPublicContent()){ 
			return '';
        } 
        bx_import ('PageMain', $this->_aModule);
        $o = new BxListingPageMain ($this);
        $o->sUrlStart = BX_DOL_URL_ROOT . '?';

        $this->_oTemplate->addCss('unit.css');
  
        $sDefaultHomepageTab = $this->_oDb->getParam('modzzz_listing_homepage_default_tab');
        $sBrowseMode = $sDefaultHomepageTab;
        switch ($_GET['modzzz_listing_filter']) {            
            case 'featured':
            case 'recent':
            case 'top':
            case 'popular':
            case $sDefaultHomepageTab:            
                $sBrowseMode = $_GET['modzzz_listing_filter'];
                break;
        }

        return $o->ajaxBrowse(
            $sBrowseMode,
            $this->_oDb->getParam('modzzz_listing_perpage_homepage'), 
            array(
                _t('_modzzz_listing_tab_featured') => array('href' => BX_DOL_URL_ROOT . '?modzzz_listing_filter=featured', 'active' => 'featured' == $sBrowseMode, 'dynamic' => true),
                _t('_modzzz_listing_tab_recent') => array('href' => BX_DOL_URL_ROOT . '?modzzz_listing_filter=recent', 'active' => 'recent' == $sBrowseMode, 'dynamic' => true),
                _t('_modzzz_listing_tab_top') => array('href' => BX_DOL_URL_ROOT . '?modzzz_listing_filter=top', 'active' => 'top' == $sBrowseMode, 'dynamic' => true),
                _t('_modzzz_listing_tab_popular') => array('href' => BX_DOL_URL_ROOT . '?modzzz_listing_filter=popular', 'active' => 'popular' == $sBrowseMode, 'dynamic' => true),
            )
        );
    }

    /**
     * Profile block with user's listing
     * @param $iProfileId profile id 
     * @return html to display on homepage in a block
     */     
    function serviceProfileBlock ($iProfileId) {
        $iProfileId = (int)$iProfileId;
        $aProfile = getProfileInfo($iProfileId);
        bx_import ('PageMain', $this->_aModule);
        $o = new BxListingPageMain ($this);        
        $o->sUrlStart = getProfileLink($aProfile['ID']) . '?';
        
		$this->_oTemplate->addCss('unit.css');

        return $o->ajaxBrowse(
            'user', 
            $this->_oDb->getParam('modzzz_listing_perpage_profile'), 
            array(),
            $aProfile['NickName'],
            true,
            false 
        );
    }

     /**
     * Account block with different events
     * @return html to display area listings in account page a block
     */  
    function serviceAccountAreaBlock () {

        if (!$this->_oDb->isAnyPublicContent())
            return '';

		$aProfileInfo = getProfileInfo($this->_iProfileId);
		$sCity = $aProfileInfo['City'];

		if(!$sCity)
			return;

        bx_import ('PageMain', $this->_aModule);
        $o = new BxListingPageMain ($this);        
        $o->sUrlStart = BX_DOL_URL_ROOT . '?';
 
        return $o->ajaxBrowse(
            'local',
            $this->_oDb->getParam('modzzz_listing_perpage_accountpage'),
			array(),
			$sCity
        );
    }

    /**
     * Account block with different events
     * @return html to display member listings in account page a block
     */ 
    function serviceAccountPageBlock () {
  
        $aProfile = getProfileInfo($this->_iProfileId);
        bx_import ('PageMain', $this->_aModule);
        $o = new BxListingPageMain ($this);        
        $o->sUrlStart = $GLOBALS['site']['url'] . 'member.php?';
        
		$this->_oTemplate->addCss('unit.css');

        return $o->ajaxBrowse(
            'user', 
            $this->_oDb->getParam('modzzz_listing_perpage_profile'), 
            array(),
            $aProfile['NickName'],
            true,
            false 
        );
    }


    function serviceGetMemberMenuItem () {
        parent::_serviceGetMemberMenuItem (_t('_modzzz_listing'), _t('_modzzz_listing'), 'listing.png');
    }

    function serviceGetWallPost ($aEvent) {
         return parent::_serviceGetWallPost ($aEvent, _t('_modzzz_listing_wall_object'), _t('_modzzz_listing_wall_added_new'));
    }

    function serviceGetSpyPost($sAction, $iObjectId = 0, $iSenderId = 0, $aExtraParams = array()) {
        return parent::_serviceGetSpyPost($sAction, $iObjectId, $iSenderId, $aExtraParams, array(
            'add' => '_modzzz_listing_spy_post',
            'change' => '_modzzz_listing_spy_post_change', 
            'join' => '_modzzz_listing_spy_join',
            'rate' => '_modzzz_listing_spy_rate',
            'commentPost' => '_modzzz_listing_spy_comment',
        ));
    }

    function serviceGetSubscriptionParams ($sAction, $iEntryId) {

        $a = array (
            'change' => _t('_modzzz_listing_sbs_change'),
            'commentPost' => _t('_modzzz_listing_sbs_comment'),
            'rate' => _t('_modzzz_listing_sbs_rate'), 
            'join' => _t('_modzzz_listing_sbs_join'), 
        );

        return parent::_serviceGetSubscriptionParams ($sAction, $iEntryId, $a);
    }

    // ================================== admin actions

    function actionAdministrationPackages ($sParam1='') {
 		$sMessage = "";
  		$iPackage = (int)process_db_input($sParam1);
 
		// check actions
		if(is_array($_POST)){
		
			if(isset($_POST['action_save']) && !empty($_POST['action_save']))
			{  
 				$this->_oDb->SavePackage();
				$sMessage = _t("Successfully Saved Package");
 			} 
			if(isset($_POST['action_edit']) && !empty($_POST['action_edit']))
			{   
 				$this->_oDb->UpdatePackage();
				$sMessage = _t("Successfully Updated Package");
  			} 
			if(isset($_POST['action_delete']) && !empty($_POST['action_delete']))
			{  
 				$this->_oDb->DeletePackage();
				$sMessage = _t("Successfully Removed Package");
			} 
			if(isset($_POST['action_add']) && !empty($_POST['action_add']))
			{  
				$iPackage = 0;  
			} 
 
		}
 
		$aPackages = $this->_oDb->getPackages();
		$aPackage[] = array(
			'value' => '',
			'caption' => ''  
		);
		foreach ($aPackages as $oPackage)
		{
			$sKey = $oPackage['id'];
			$sValue = $oPackage['name'];
 
			$aPackage[] = array(
				'value' => $sKey,
				'caption' => $sValue ,
				'selected' => ($sKey == $iPackage) ? 'selected="selected"' : ''
			);
		}
		
		$sContent = $GLOBALS['oAdmTemplate']->parseHtmlByName('top_block_select.html', array(
			'name' => _t('_modzzz_listing_packages'),
			'bx_repeat:items' => $aPackage,
			'location_href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/packages/'
		));


		$aPackage = $this->_oDb->getRow("SELECT * FROM `" . $this->_oDb->_sPrefix . "packages` WHERE  `id` = '$iPackage'");
		  
		$sFormName = 'packages_form';
  
	    if($iPackage){
			$sControls = BxTemplSearchResult::showAdminActionsPanel($sFormName, array(
				'action_edit' => _t('_modzzz_listing_categ_btn_edit'),
				'action_delete' => _t('_modzzz_listing_categ_btn_delete'), 
				'action_add' => _t('_modzzz_listing_categ_btn_add')  
			), 'pathes', false);
	    }else{
			$sControls = BxTemplSearchResult::showAdminActionsPanel($sFormName, array(
				'action_save' => _t('_modzzz_listing_categ_btn_save')
			), 'pathes', false);	 
	    }
 
		$aVars = array(
 			'id'=> $aPackage['id'],  
			'name' => $aPackage['name'], 
   			'price' => $aPackage['price'],
			'days' => $aPackage['days'],
			'featured' => $aPackage['featured'],
			'description' => $aPackage['description'], 
			'photo_no_select' => $aPackage['photos'] ? '' : "selected='selected'",
			'photo_yes_select' => $aPackage['photos'] ? "selected='selected'" : '',
			'video_no_select' => $aPackage['videos'] ? '' : "selected='selected'",
			'video_yes_select' => $aPackage['videos'] ? "selected='selected'" : '',
			'file_no_select' => $aPackage['files'] ? '' : "selected='selected'",
			'file_yes_select' => $aPackage['files'] ? "selected='selected'" : '',
			
			'sound_no_select' => $aPackage['sounds'] ? '' : "selected='selected'", 
			'sound_yes_select' => $aPackage['sounds'] ? "selected='selected'" : '',

			'featured_no_select' => $aPackage['featured'] ? '' : "selected='selected'", 
			'featured_yes_select' => $aPackage['featured'] ? "selected='selected'" : '',

 			'form_name' => $sFormName, 
			'controls' => $sControls,   
		);

		if($sMessage){
 			$sContent .= MsgBox($sMessage) ;
			$sContent .= "<form method=post>";
			$sContent .= BxTemplSearchResult::showAdminActionsPanel($sFormName, array(
 				'action_add' => _t('_modzzz_listing_categ_btn_add'),  
			), 'pathes', false);  
			$sContent .= "</form>";
		}else{
			$sContent .= $this->_oTemplate->parseHtmlByName('admin_packages',$aVars);
		}

		return $sContent;
	}
 
    function actionAdministration ($sUrl = '',$sParam1='',$sParam2='') {

        if (!$this->isAdmin()) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }        

        $this->_oTemplate->pageStart();

        $aVars = array (
            'module_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri(),
        );
        $sContent = $this->_oTemplate->parseHtmlByName ('admin_links', $aVars);
        echo $this->_oTemplate->adminBlock ($sContent, _t('_modzzz_listing_admin_links'));


        $aMenu = array(
            'pending_approval' => array(
                'title' => _t('_modzzz_listing_menu_admin_pending_approval'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/pending_approval', 
                '_func' => array ('name' => 'actionAdministrationManage', 'params' => array(false)),
            ),
            'admin_entries' => array(
                'title' => _t('_modzzz_listing_menu_admin_entries'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/admin_entries',
                '_func' => array ('name' => 'actionAdministrationManage', 'params' => array(true)),
            ),   
           'categories' => array(
                'title' => _t('_modzzz_listing_menu_admin_manage_categories'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/categories',
                '_func' => array ('name' => 'actionAdministrationCategories', 'params' => array($sParam1)),
            ),
            'subcategories' => array(
                'title' => _t('_modzzz_listing_menu_admin_manage_subcategories'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/subcategories',
                '_func' => array ('name' => 'actionAdministrationSubCategories', 'params' => array($sParam1,$sParam2)),
            ), 
			'invoices' => array(
                'title' => _t('_modzzz_listing_menu_manage_invoices'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/invoices',
                '_func' => array ('name' => 'actionAdministrationInvoices', 'params' => array($sParam1)),
            ), 			
			'orders' => array(
                'title' => _t('_modzzz_listing_menu_manage_orders'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/orders',
                '_func' => array ('name' => 'actionAdministrationOrders', 'params' => array($sParam1)),
            ),
			'packages' => array(
                'title' => _t('_modzzz_listing_menu_manage_packages'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/packages',
                '_func' => array ('name' => 'actionAdministrationPackages', 'params' => array($sParam1)),
            ),  
			'claims' => array(
                'title' => _t('_modzzz_listing_menu_manage_claims'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/claims',
                '_func' => array ('name' => 'actionAdministrationClaims', 'params' => array($sParam1)),
            ), 
            'create' => array(
                'title' => _t('_modzzz_listing_menu_admin_add_entry'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/create',
                '_func' => array ('name' => 'actionAdministrationCreateEntry', 'params' => array()),
            ),
            'settings' => array(
                'title' => _t('_modzzz_listing_menu_admin_settings'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/settings',
                '_func' => array ('name' => 'actionAdministrationSettings', 'params' => array()),
            ),
        );

        if (empty($aMenu[$sUrl]))
            $sUrl = 'pending_approval';

        $aMenu[$sUrl]['active'] = 1;
        $sContent = call_user_func_array (array($this, $aMenu[$sUrl]['_func']['name']), $aMenu[$sUrl]['_func']['params']);

        echo $this->_oTemplate->adminBlock ($sContent, _t(''), $aMenu);
        $this->_oTemplate->addCssAdmin ('admin.css');
        $this->_oTemplate->addCssAdmin ('unit.css');
        $this->_oTemplate->addCssAdmin ('main.css');
        $this->_oTemplate->addCssAdmin ('forms_extra.css'); 
        $this->_oTemplate->addCssAdmin ('forms_adv.css');        
        $this->_oTemplate->pageCodeAdmin (_t('_modzzz_listing_page_title_administration'));
    }

    function actionAdministrationSettings () {
        return parent::_actionAdministrationSettings ('Listing');
    }
 
    function actionAdministrationCategories ($sParam1='') {
 		$sMessage = "";
  		$iCategory = (int)process_db_input($sParam1);
 
		// check actions
		if(is_array($_POST))
		{
			if(isset($_POST['action_save']) && !empty($_POST['action_save']))
			{  
 				$this->_oDb->SaveCategory();
				$sMessage = _t("Successfully Saved Category");
 			} 
			if(isset($_POST['action_edit']) && !empty($_POST['action_edit']))
			{   
 				$this->_oDb->UpdateCategory();
				$sMessage = _t("Successfully Updated Category");
  			} 
			if(isset($_POST['action_delete']) && !empty($_POST['action_delete']))
			{  
 				$this->_oDb->DeleteCategory();
				$sMessage = _t("Successfully Removed Category");
			} 
			if(isset($_POST['action_add']) && !empty($_POST['action_add']))
			{  
				$iCategory = 0;  
			} 
			if(isset($_POST['action_sub']) && !empty($_POST['action_sub']))
			{  
				$sRedirUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/subcategories/'.$iCategory;
				
				header("Location: " . $sRedirUrl);
			} 		

		}
 
		$aCategories = $this->_oDb->getParentCategories();
		$aCategory[] = array(
			'value' => '',
			'caption' => ''  
		);
		foreach ($aCategories as $oCategory)
		{
			$sKey = $oCategory['id'];
			$sValue = $oCategory['name'];
   
			$aCategory[] = array(
				'value' => $sKey,
				'caption' => $sValue ,
				'selected' => ($sKey == $iCategory) ? 'selected="selected"' : ''
			);
		}
		
		$sContent = $GLOBALS['oAdmTemplate']->parseHtmlByName('top_block_select.html', array(
			'name' => _t('_modzzz_listing_categories'),
			'bx_repeat:items' => $aCategory,
			'location_href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/categories/'
		));


		$aCategory = $this->_oDb->getRow("SELECT * FROM `" . $this->_oDb->_sPrefix . "categ` WHERE  `id` = '$iCategory'");
		 
		$sFormName = 'categories_form';
  
	    if($iCategory){
			$sControls = BxTemplSearchResult::showAdminActionsPanel($sFormName, array(
				'action_edit' => _t('_modzzz_listing_categ_btn_edit'),
				'action_delete' => _t('_modzzz_listing_categ_btn_delete'), 
				'action_add' => _t('_modzzz_listing_categ_btn_add'),
				'action_sub' => _t('_modzzz_listing_categ_btn_subcategories'), 
			), 'pathes', false);
	    }else{
			$sControls = BxTemplSearchResult::showAdminActionsPanel($sFormName, array(
				'action_save' => _t('_modzzz_listing_categ_btn_save')
			), 'pathes', false);	 
	    }
  
		$aVars = array(
			'name' => $aCategory['name'],
			'id'=> $aCategory['id'],  
 			'form_name' => $sFormName, 
			'controls' => $sControls,   
		);

		if($sMessage){
 			$sContent .= MsgBox($sMessage) ;
			$sContent .= "<form method=post>";
			$sContent .= BxTemplSearchResult::showAdminActionsPanel($sFormName, array(
 				'action_add' => _t('_modzzz_listing_categ_btn_add'),  
			), 'pathes', false);  
			$sContent .= "</form>";
		}else{
			$sContent .= $this->_oTemplate->parseHtmlByName('admin_categories',$aVars);
		}

		return $sContent;
	}

    function actionAdministrationSubCategories ($sParam1='', $sParam2='') {
 		$sMessage = "";
  		$iCategory = (int)process_db_input($sParam1);
   		$iSubCategory = (int)process_db_input($sParam2);
		$sCategoryName = $this->_oDb->getCategoryName($iCategory);
		
		if(!$iCategory){
			$sContent = MsgBox(_t('_modzzz_listing_manage_subcategories_msg')); 

			return $sContent; 
		}

		// check actions
		if(is_array($_POST))
		{
			if(isset($_POST['action_save']) && !empty($_POST['action_save']))
			{  
 				$this->_oDb->SaveCategory($iCategory);
				$sMessage = _t("Successfully Saved Category");
 			} 
			if(isset($_POST['action_edit']) && !empty($_POST['action_edit']))
			{   
 				$this->_oDb->UpdateCategory();
				$sMessage = _t("Successfully Updated Category");
  			} 
			if(isset($_POST['action_delete']) && !empty($_POST['action_delete']))
			{  
 				$this->_oDb->DeleteCategory();
				$sMessage = _t("Successfully Removed Category");
			} 
			if(isset($_POST['action_add']) && !empty($_POST['action_add']))
			{  
				$iSubCategory = 0;  
			}  
		}
 
		$aSubCategories = $this->_oDb->getSubCategories($iCategory);

		foreach ($aSubCategories as $oSubCategory)
		{
			$sKey = $oSubCategory['id'];
			$sValue = $oSubCategory['name'];
   
			$aSubCategory[] = array(
				'value' => $sKey,
				'caption' => $sValue ,
				'selected' => ($sKey == $iSubCategory) ? 'selected="selected"' : ''
			);
		}
		
		$sContent = $GLOBALS['oAdmTemplate']->parseHtmlByName('top_block_select.html', array(
			'name' => $sCategoryName .': '. _t('_modzzz_listing_subcategories'),
			'bx_repeat:items' => $aSubCategory,
			'location_href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/subcategories/'.$iCategory.'/'
		));


		$aCategory = $this->_oDb->getRow("SELECT * FROM `" . $this->_oDb->_sPrefix . "categ` WHERE  `id` = '$iSubCategory'");
		 
		$sFormName = 'categories_form';
 
	    if($iSubCategory){
			$sControls = BxTemplSearchResult::showAdminActionsPanel($sFormName, array(
				'action_edit' => _t('_modzzz_listing_categ_btn_edit'),
				'action_delete' => _t('_modzzz_listing_categ_btn_delete'), 
				'action_add' => _t('_modzzz_listing_categ_btn_add'),
			), 'pathes', false);
	    }else{
			$sControls = BxTemplSearchResult::showAdminActionsPanel($sFormName, array(
				'action_save' => _t('_modzzz_listing_categ_btn_save')
			), 'pathes', false);	 
	    }
  
		$aVars = array(
			'name' => $aCategory['name'],
			'id'=> $aCategory['id'],  
 			'form_name' => $sFormName, 
			'controls' => $sControls,   
		);

		if($sMessage){
 			$sContent .= MsgBox($sMessage) ;
			$sContent .= "<form method=post>";
			$sContent .= BxTemplSearchResult::showAdminActionsPanel($sFormName, array(
 				'action_add' => _t('_modzzz_listing_categ_btn_add'),  
			), 'pathes', false);  
			$sContent .= "</form>";
		}else{
			$sContent .= $this->_oTemplate->parseHtmlByName('admin_categories',$aVars);
		}

		return $sContent;
	} 
  
    function actionAdministrationManage ($isAdminEntries = false) {
        return parent::_actionAdministrationManage ($isAdminEntries, '_modzzz_listing_admin_delete', '_modzzz_listing_admin_activate');
    }
  
    function actionAdministrationOrders () {

        if ($_POST['action_activate'] && is_array($_POST['entry'])) {

            foreach ($_POST['entry'] as $iId) {
                $this->_oDb->activateOrder($iId, $this->isAdmin()); 
            }

        } elseif ($_POST['action_delete'] && is_array($_POST['entry'])) {
  
            foreach ($_POST['entry'] as $iId) { 
                $this->_oDb->deleteOrder($iId, $this->isAdmin()); 
            }
        }
 
		$sContent = $this->_manageOrders ('order', '', true, 'bx_twig_admin_form', array(
			'action_activate' => '_modzzz_listing_admin_activate',
			'action_delete' => '_modzzz_listing_admin_delete',
		));
     
        return $sContent;
    }

    function actionAdministrationInvoices () {

        if ($_POST['action_delete'] && is_array($_POST['entry'])) {
  
            foreach ($_POST['entry'] as $iId) { 
                $this->_oDb->deleteInvoice($iId, $this->isAdmin()); 
            }
        }
 
		$sContent = $this->_manageOrders ('invoice', '', true, 'bx_twig_admin_form', array(
 			'action_delete' => '_modzzz_listing_admin_delete',
		));
     
        return $sContent;
    }

    function _manageOrders ($sMode, $sValue, $isFilter, $sFormName, $aButtons, $sAjaxPaginationBlockId = '', $isMsgBoxIfEmpty = true, $iPerPage = 14, $bActionsPanel = true) {

        bx_import ('SearchResult', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'SearchResult';
        $o = new $sClass($sMode, $sValue);
        $o->sUnitTemplate = $sMode . '_admin';

        if ($iPerPage)
            $o->aCurrent['paginate']['perPage'] = $iPerPage;

        $sPagination = $sActionsPanel = '';
        if ($o->isError) {
            $sContent = MsgBox(_t('_Error Occured'));
        } elseif (!($sContent = $o->displayOrdersResultBlock($sMode))) {
            if ($isMsgBoxIfEmpty)
                $sContent = MsgBox(_t('_Empty'));
            else
                return '';
        } else {
            $sPagination = $sAjaxPaginationBlockId ? $o->showPaginationAjax($sAjaxPaginationBlockId) : $o->showPagination();
			if($bActionsPanel)
				$sActionsPanel = $o->showAdminActionsPanel ($sFormName, $aButtons);
        }

        $aVars = array (
            'form_name' => $sFormName,
            'content' => $sContent,
            'pagination' => $sPagination,
            'filter_panel' => $isFilter ? $o->showAdminFilterPanel(false !== bx_get($this->_sFilterName) ? bx_get($this->_sFilterName) : '', 'filter_input_id', 'filter_checkbox_id', $this->_sFilterName) : '',
            'actions_panel' => $sActionsPanel,
        );        
        return  $this->_oTemplate->parseHtmlByName ('manage', $aVars);
    }
 
	function actionAdministrationClaims () {

        if ($_POST['action_assign'] && is_array($_POST['entry'])) {
 
            foreach ($_POST['entry'] as $iId) {  
                $this->_oDb->assignClaim($iId, $this->isAdmin()); 
            } 
        }

        if ($_POST['action_delete'] && is_array($_POST['entry'])) {
  
            foreach ($_POST['entry'] as $iId) { 
                $this->_oDb->deleteClaim($iId, $this->isAdmin()); 
            }
        }
 
		$sContent = $this->_manageClaims ('claim', '', true, 'bx_twig_admin_form', array(
 			'action_assign' => '_modzzz_listing_admin_assign',
 			'action_delete' => '_modzzz_listing_admin_delete',
		));
     
        return $sContent;
    }

    function _manageClaims ($sMode, $sValue, $isFilter, $sFormName, $aButtons, $sAjaxPaginationBlockId = '', $isMsgBoxIfEmpty = true, $iPerPage = 14, $bActionsPanel = true) {

        bx_import ('SearchResult', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'SearchResult';
        $o = new $sClass($sMode, $sValue);
        $o->sUnitTemplate = $sMode . '_admin';
 

        if ($iPerPage)
            $o->aCurrent['paginate']['perPage'] = $iPerPage;

        $sPagination = $sActionsPanel = '';
        if ($o->isError) {
            $sContent = MsgBox(_t('_Error Occured'));
        } elseif (!($sContent = $o->displayClaimResultBlock($sMode))) { 
            if ($isMsgBoxIfEmpty)
                $sContent = MsgBox(_t('_Empty'));
            else
                return '';
        } else { 
            $sPagination = $sAjaxPaginationBlockId ? $o->showPaginationAjax($sAjaxPaginationBlockId) : $o->showPagination();
			if($bActionsPanel)
				$sActionsPanel = $o->showAdminActionsPanel ($sFormName, $aButtons);
        }

        $aVars = array (
            'form_name' => $sFormName,
            'content' => $sContent,
            'pagination' => $sPagination,
            'filter_panel' => $isFilter ? $o->showAdminFilterPanel(false !== bx_get($this->_sFilterName) ? bx_get($this->_sFilterName) : '', 'filter_input_id', 'filter_checkbox_id', $this->_sFilterName) : '',
            'actions_panel' => $sActionsPanel,
        );        
        return  $this->_oTemplate->parseHtmlByName ('manage', $aVars);
    }


    // ================================== events
 

 
    // ================================== permissions
    
    function isAllowedPaidListings () {

		if($this->isAdmin())
			return false;

        // admin always have access  
        if (getParam('modzzz_listing_paid_active')=='on') 
            return true;	
            
		return false;
	}


    function onEventJoinRequest ($iEntryId, $iProfileId, $aDataEntry) {
        parent::_onEventJoinRequest ($iEntryId, $iProfileId, $aDataEntry, 'modzzz_listing_join_request', BX_LISTING_MAX_FANS);
    }

    function onEventJoinReject ($iEntryId, $iProfileId, $aDataEntry) {
        parent::_onEventJoinReject ($iEntryId, $iProfileId, $aDataEntry, 'modzzz_listing_join_reject');
    }

    function onEventFanRemove ($iEntryId, $iProfileId, $aDataEntry) {        
        parent::_onEventFanRemove ($iEntryId, $iProfileId, $aDataEntry, 'modzzz_listing_fan_remove');
    }

    function onEventFanBecomeAdmin ($iEntryId, $iProfileId, $aDataEntry) {        
        parent::_onEventFanBecomeAdmin ($iEntryId, $iProfileId, $aDataEntry, 'modzzz_listing_fan_become_admin');
    }

    function onEventAdminBecomeFan ($iEntryId, $iProfileId, $aDataEntry) {        
        parent::_onEventAdminBecomeFan ($iEntryId, $iProfileId, $aDataEntry, 'modzzz_listing_admin_become_fan');
    }

    function onEventJoinConfirm ($iEntryId, $iProfileId, $aDataEntry) {
        parent::_onEventJoinConfirm ($iEntryId, $iProfileId, $aDataEntry, 'modzzz_listing_join_confirm');
    }

    function isAllowedJoin (&$aDataEntry) {
        if (!$this->_iProfileId) 
            return false;
        return $this->_oPrivacy->check('join', $aDataEntry['id'], $this->_iProfileId);
    }

    function isAllowedView ($aDataEntry, $isPerformAction = false) {

        // admin and owner always have access
        if ($this->isAdmin() || $aDataEntry['author_id'] == $this->_iProfileId) 
            return true;

        // check admin acl
        $this->_defineActions();
		$aCheck = checkAction($this->_iProfileId, BX_LISTING_VIEW_LISTING, $isPerformAction);
        if ($aCheck[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED)
            return false;
 
        // check user group  
        $isAllowed =  $this->_oPrivacy->check('view_listing', $aDataEntry['id'], $this->_iProfileId);   
		return $isAllowed && $this->_isAllowedViewByMembership ($aDataEntry); 

    }

    function _isAllowedViewByMembership (&$aDataEntry) { 
        if (!$aDataEntry['membership_view_filter']) return true;
        require_once(BX_DIRECTORY_PATH_INC . 'membership_levels.inc.php');
        $aMembershipInfo = getMemberMembershipInfo($this->_iProfileId);
 
		if($aMembershipInfo['DateExpires']) 
			return $aDataEntry['membership_view_filter'] == $aMembershipInfo['ID'] && $aMembershipInfo['DateStarts'] < time() && $aMembershipInfo['DateExpires'] > time() ? true : false;
		else
			return $aDataEntry['membership_view_filter'] == $aMembershipInfo['ID'] && $aMembershipInfo['DateStarts'] < time() ? true : false; 
    }
  
    function isAllowedBrowse ($isPerformAction = false) {
        if ($this->isAdmin()) 
            return true;
        $this->_defineActions();
		$aCheck = checkAction($this->_iProfileId, BX_LISTING_BROWSE, $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }

    function isAllowedSearch ($isPerformAction = false) {
        if ($this->isAdmin()) 
            return true;
        $this->_defineActions();
		$aCheck = checkAction($this->_iProfileId, BX_LISTING_SEARCH, $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }

    function isAllowedAdd ($isPerformAction = false) {
        if ($this->isAdmin()) 
            return true;
        if (!$GLOBALS['logged']['member']) 
            return false;
        $this->_defineActions();
		$aCheck = checkAction($this->_iProfileId, BX_LISTING_ADD_LISTING, $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    } 

    function isAllowedEdit ($aDataEntry, $isPerformAction = false) {

        if ($this->isAdmin() || ($GLOBALS['logged']['member'] && $aDataEntry['author_id'] == $this->_iProfileId && isProfileActive($this->_iProfileId))) 
            return true;

        // check acl
        $this->_defineActions();
		$aCheck = checkAction($this->_iProfileId, BX_LISTING_EDIT_ANY_LISTING, $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    } 

    function isAllowedMarkAsFeatured ($aDataEntry, $isPerformAction = false) {
        if ($this->isAdmin()) 
            return true;
        $this->_defineActions();
        $aCheck = checkAction($this->_iProfileId, BX_LISTING_MARK_AS_FEATURED, $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;        
    }
  
    function isAllowedDelete (&$aDataEntry, $isPerformAction = false) {
        if ($this->isAdmin() || ($GLOBALS['logged']['member'] && $aDataEntry['author_id'] == $this->_iProfileId && isProfileActive($this->_iProfileId))) 
            return true;
        $this->_defineActions();
		$aCheck = checkAction($this->_iProfileId, BX_LISTING_DELETE_ANY_LISTING, $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }     
  
    function isAllowedInquire (&$aDataEntry, $isPerformAction = false) {
        if ($this->isAdmin()) 
            return true;
        $this->_defineActions();
        $aCheck = checkAction($this->_iProfileId, BX_LISTING_MAKE_INQUIRY, $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;     
    } 
	
    function isAllowedClaim (&$aDataEntry, $isPerformAction = false) {
		if (!$this->_oDb->isOwnerAdmin($aDataEntry['author_id']))
            return false;
  
        $this->_defineActions();
        $aCheck = checkAction($this->_iProfileId, BX_LISTING_MAKE_CLAIM, $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;     
    } 

    function isAllowedSendInvitation (&$aDataEntry) {
        return true;
    }
 
    function isAllowedShare (&$aDataEntry) {
        return true;
    }
 

    function isAllowedRate(&$aDataEntry) {        
        if ($this->isAdmin())
            return true;
        return $this->_oPrivacy->check('rate', $aDataEntry['id'], $this->_iProfileId);        
    }

    function isAllowedComments(&$aDataEntry) {
        if (!$this->_iProfileId) 
            return false;        
        if ($this->isAdmin())
            return true;
        return $this->_oPrivacy->check('comment', $aDataEntry['id'], $this->_iProfileId);
    }
    
	function isAllowedViewFans(&$aDataEntry) {
        if ($this->isAdmin())
            return true;
        return $this->_oPrivacy->check('view_fans', $aDataEntry['id'], $this->_iProfileId);
    }

    function isAllowedUploadPhotos(&$aDataEntry) {
       
		if (!$this->_iProfileId) 
            return false;  
	 
        if ($this->isAdmin())
            return true; 
		 
		if (!$this->_oDb->isPackageAllowedPhotos($aDataEntry['invoice_no']))
            return false;    

        if (!$this->isMembershipEnabledForImages())
            return false;
 
        return $this->_oPrivacy->check('upload_photos', $aDataEntry['id'], $this->_iProfileId);
    }

    function isAllowedUploadVideos(&$aDataEntry) {
        if (!$this->_iProfileId) 
            return false;        
        if ($this->isAdmin())
            return true;
		if (!$this->_oDb->isPackageAllowedVideos($aDataEntry['invoice_no']))
            return false;  
        if (!$this->isMembershipEnabledForVideos())
            return false;                
        return $this->_oPrivacy->check('upload_videos', $aDataEntry['id'], $this->_iProfileId);
    }

    function isAllowedUploadSounds(&$aDataEntry) {
        if (!$this->_iProfileId) 
            return false;        
        if ($this->isAdmin())
            return true;
		if (!$this->_oDb->isPackageAllowedSounds($aDataEntry['invoice_no']))
            return false;    
        if (!$this->isMembershipEnabledForSounds())
            return false;                        
        return $this->_oPrivacy->check('upload_sounds', $aDataEntry['id'], $this->_iProfileId);
    }

    function isAllowedUploadFiles(&$aDataEntry) {
        if (!$this->_iProfileId) 
            return false;        
        if ($this->isAdmin())
            return true;
		if (!$this->_oDb->isPackageAllowedFiles($aDataEntry['invoice_no']))
            return false;    
        if (!$this->isMembershipEnabledForFiles())
            return false;                        
        return $this->_oPrivacy->check('upload_files', $aDataEntry['id'], $this->_iProfileId);
    }
	
    function isAllowedCreatorCommentsDeleteAndEdit (&$aDataEntry, $isPerformAction = false) {
        if ($this->isAdmin()) return true;        
        if (!$GLOBALS['logged']['member'] || $aDataEntry['author_id'] != $this->_iProfileId)
            return false;
        $this->_defineActions();
		$aCheck = checkAction($this->_iProfileId, BX_LISTING_COMMENTS_DELETE_AND_EDIT, $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }

    function isAllowedManageAdmins($aDataEntry) {
        if (($GLOBALS['logged']['member'] || $GLOBALS['logged']['admin']) && $aDataEntry['author_id'] == $this->_iProfileId && isProfileActive($this->_iProfileId))
            return true;
        return false;
    }
   
	function isPermalinkEnabled() {
		$bEnabled = isset($this->_isPermalinkEnabled) ? $this->_isPermalinkEnabled : ($this->_isPermalinkEnabled = (getParam('permalinks_listing') == 'on'));
		 
        return $bEnabled;
    }


    function _defineActions () {
        defineMembershipActions(array('listing purchase featured', 'listing view listing', 'listing browse', 'listing search', 'listing add listing', 'listing comments delete and edit', 'listing edit any listing', 'listing delete any listing', 'listing mark as featured', 'listing approve listing', 'listing make claim', 'listing make inquiry' ));
    }

 
    function _browseMy (&$aProfile) {        
        parent::_browseMy ($aProfile, _t('_modzzz_listing_page_title_my_listing'));
    } 
	
    function onEventCreate ($iEntryId, $sStatus, $aDataEntry = array()) {
  
		$this->serviceUpdateProfileLocation ($iEntryId);

		if ('approved' == $sStatus) {
            $this->reparseTags ($iEntryId);
            $this->reparseCategories ($iEntryId);
        }

        $this->_oDb->createForum ($aDataEntry, $this->_oDb->getProfileNickNameById($this->_iProfileId));

 		$oAlert = new BxDolAlerts($this->_sPrefix, 'add', $iEntryId, $this->_iProfileId, array('Status' => $sStatus));
		$oAlert->alert();
    }

    function getTagLinks($sTagList, $sType = 'tag', $sDivider = ' ') {
        if (strlen($sTagList)) {
            $aTags = explode($sDivider, $sTagList);
            foreach ($aTags as $iKey => $sValue) {
                $sValue   = trim($sValue, ','); 
                $aRes[$sValue] = $sValue;
            }
        }
        return $aRes;
    }

   function parseTags($s)
    {
        return $this->_parseAnything($s, ',', BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'browse/tag/');
    }

    function parseCategories($s)
    {
        return $this->_parseAnything($s, CATEGORIES_DIVIDER, BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'browse/category/');
    }

    function _parseAnything($s, $sDiv, $sLinkStart, $sClassName = '')
    {
        $sRet = '';
        $a = explode ($sDiv, $s);
        $sClass = $sClassName ? 'class="'.$sClassName.'"' : '';
        
        foreach ($a as $sName)
            $sRet .= '<a '.$sClass.' href="' . $sLinkStart . urlencode(title2uri($sName)) . '">'.$sName.'</a>&#160';
        
        return $sRet;
    }

	/*[begin] map integration*/


    function actionMapEdit ($iEntryID) {

		$aDataEntry = $this->_oDb->getEntryById($iEntryID);
       
		$GLOBALS['oTopMenu']->setCustomSubHeader($aDataEntry[$this->_oDb->_sFieldTitle]);
        $GLOBALS['oTopMenu']->setCustomVar($this->_sPrefix.'_view_uri', $aDataEntry[$this->_oDb->_sFieldUri]);


        $aLocation = $iEntryID ? $this->_oDb->getProfileById($iEntryID) : false;
        if (!$iEntryID || !$this->isAllowedEditOwnLocation($iEntryID, $aLocation)) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        } 
  
        if (!($aDataEntry = $this->_oDb->getEntryByIdAndOwner($iEntryID, $this->_iProfileId, $this->isAdmin()))) {
            $this->_oTemplate->displayPageNotFound ();
            return;
        }

        $this->_oTemplate->pageStart();
 
        modzzz_listing_import ('PageEdit');
        $oPage = new BxListingPageEdit ($this, $aLocation);
        echo $oPage->getCode();

        $this->_oTemplate->addJs ('http://www.google.com/jsapi?key=' . getParam('modzzz_listing_key'));
        $this->_oTemplate->addJs ('BxMap.js');
        $this->_oTemplate->addCss ('main.css');
        $this->_oTemplate->addCss ('search.css');
        $this->_oTemplate->pageCode(_t('_modzzz_listing_edit'), false, false);
    }  


    function actionSaveDataProfile ($iEntryID, $iZoom, $sMapType, $fLat, $fLng, $sMapClassInstanceName, $sAddress, $sCountry) {

        $aLocation = $iEntryID ? $this->_oDb->getProfileById($iEntryID) : false;
        if (!$iEntryID || !$this->isAllowedEditOwnLocation($iEntryID, $aLocation))
            return;


        if (!$aLocation && ('null' == $fLat || 'null' == $fLng))
            return;
        
        $fLat = 'null' != $fLat ? (float)$fLat : $aLocation['lat'];
        $fLng = 'null' != $fLng ? (float)$fLng : $aLocation['lng'];
        $iZoom = 'null' != $iZoom ? (int)$iZoom : ($aLocation ? $aLocation['zoom'] : -1);
        $sMapType = 'null' != $sMapType ? $sMapType : ($aLocation ? $aLocation['type'] : '');
        $sAddress = 'null' != $sAddress ? process_db_input($sAddress, BX_TAGS_STRIP) : '';
        $sCountry = 'null' != $sCountry ? process_db_input($sCountry, BX_TAGS_STRIP) : '';

        switch ($sMapType) {
            case 'normal':
            case 'satellite':
            case 'hybrid':
                break;
            default:
                $sMapType = 'normal';
        }

        if ($this->_oDb->insertProfileLocation ($this->_iProfileId,$iEntryID, $fLat, $fLng, $iZoom, $sMapType, $sAddress, $sCountry)) {
            $this->onEventProfileLocationManuallyUpdated ($iEntryID, array ('lat' => $fLat, 'lng' => $fLng, 'zoom' => $iZoom, 'map_type' => $sMapType, 'address' => $sAddress, 'country' => $sCountry));
            echo 1;
        }
    }
  
    function actionGetDataProfile ($iId, $sMapClassInstanceName) {
        
        $iProfileId = $iId;
        $r = $this->_oDb->getProfileById($iProfileId);
        if (!$r || !$this->isAllowedViewLocation ($iProfileId, $r))
            return;        

        if (!$iId) {
            $aRet = array ();
            $aRet[] = array (
                    'lat' => $r['lat'],
                    'lng' => $r['lng'],
                    'data' => '',
                    'icon' => array ('w' => 0, 'h' => 0, 'sw' => 0, 'sh' => 0, 'sd' => '', 'url' => ''),
                );

            $oParser = new Services_JSON();
            echo $oParser->encode($aRet);
            return;
        }

        $sImage = '';
        if ($r['thumb']) {
            $a = array ('ID' => $this->_oDb->getAuthorById($iId), 'Avatar' => $r['thumb']);
            $aImage = BxDolService::call('photos', 'get_image', array($a, 'browse'), 'Search');
            $sImage = $aImage['no_image'] ? '' : $aImage['file'];
        } 


        $aVars = array (
			 
			'bx_if:address1' => array( 
				'condition' =>  strlen(trim($r['address1'])),
				'content' => array(
					'address1' => trim($r['address1']), 
				) 
			), 
			'bx_if:address2' => array( 
				'condition' =>  strlen(trim($r['address2'])),
				'content' => array(
					'address2' => trim($r['address2']), 
				) 
			),  
			'thumb' => $sImage ? $sImage : $this->_oTemplate->getIconUrl('no-photo.png'), 
			'url_flag' => genFlagUrl($r['country']),
			'country' => _t($GLOBALS['aPreValues']['Country'][$r['country']]['LKey']),
			'city' => trim($r['city']) ? $r['city'] : _t('_modzzz_listing_unknown_city'), 
		);
        $sHtml = $this->_oTemplate->parseHtmlByName ('popup_profile', $aVars);

        $aIconJSON = false;
        /*
		if ($r['thumb']) {

			include_once (BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/include.php');
	        $sAvatarImg = BX_AVA_URL_USER_AVATARS . $r['Avatar'] . BX_AVA_EXT;
            $aIconJSON = array ('w' => 32, 'h' => 32, 'sw' => 34, 'sh' => 34, 'sd' => '1', 'url' => $sAvatarImg);
        }
		*/
        if (!$aIconJSON) {
            $sIconUser = $this->_oTemplate->getIconUrl ('user.png');
            $aIconJSON = array ('w' => 16, 'h' => 16, 'sw' => 24, 'sh' => 16, 'sd' => '', 'url' => $sIconUser);
        }

        $aRet = array ();
        $aRet[] = array (
                'lat' => $r['lat'],
                'lng' => $r['lng'],
                'data' => $sHtml,
                'icon' => $aIconJSON, 
            );

        $oParser = new Services_JSON();
        echo $oParser->encode($aRet);        
    }

    function actionGetHtmlProfiles ($sFilter) {        

        $aProfiles = $this->_getProfilesByFilter ($sFilter);
        if (!$aProfiles) {
            echo MsgBox(_t('_Empty'));
            return;
        }

        if (!$aProfiles) {
            echo MsgBox(_t('_Empty'));
            return;
        }

        bx_import ('BxTemplSearchProfile');
        $oBxTemplSearchProfile = new BxTemplSearchProfile();
        foreach ($aProfiles as $r) {
            $aProfileInfo = $this->_oDb->getProfileInfo($r['id']);
            $sProfiles .= $oBxTemplSearchProfile->displaySearchUnit($aProfileInfo);
        }

        echo $GLOBALS['oFunctions']->centerContent($sProfiles, '.searchrow_block_simple');
    }

    function actionGetData ($iZoom, $fLatMin, $fLatMax, $fLngMin, $fLngMax, $sMapClassInstanceName, $sFilter = '') {

        $fLatMin = (float)$fLatMin;
        $fLatMax = (float)$fLatMax;
        $fLngMin = (float)$fLngMin;
        $fLngMax = (float)$fLngMax;

        if ($sFilter) {
            echo $this->_getProfilesData('filter', $sFilter, 0, 0);
            return;
        }

        $iZoom = (int)$iZoom;
        if ($iZoom >= BX_LISTING_ZOOM_COUNTRIES && $iZoom < BX_LISTING_ZOOM_CITIES) {
            echo $this->_getCountriesData($fLatMin, $fLatMax, $fLngMin, $fLngMax, $sMapClassInstanceName);
        } elseif ($iZoom >= BX_LISTING_ZOOM_CITIES && $iZoom < BX_LISTING_ZOOM_PROFILES) {
            echo $this->_getCitiesData($fLatMin, $fLatMax, $fLngMin, $fLngMax, $sMapClassInstanceName);
        } elseif ($iZoom >= BX_LISTING_ZOOM_PROFILES) {
            echo $this->_getProfilesData($fLatMin, $fLatMax, $fLngMin, $fLngMax);
        }
    }

    function _getProfilesData ($fLatMin, $fLatMax, $fLngMin, $fLngMax) {

        $sIconUser = $this->_oTemplate->getIconUrl ('user.png');
        $sIconGroup = $this->_oTemplate->getIconUrl ('group.png');

        if ('filter' == $fLatMin) {
            $a = $this->_getProfilesByFilter ($fLatMax); 
        } else {
            $a = $this->_oDb->getProfilesByBounds((float)$fLatMin, (float)$fLatMax, (float)$fLngMin, (float)$fLngMax);
        }

        $aa = array ();
        foreach ($a as $r) {
            $sKey = $r['lat'].'x'.$r['lng'];
            $aVars = array ('thumb' => get_member_thumbnail($r['id'], 'none', true));
            $aa[$sKey][] = array (
                'lat' => $r['lat'],
                'lng' => $r['lng'],
                'username' => $r['NickName'],
                'html' => $this->_oTemplate->parseHtmlByName ('popup_profile', $aVars),
            );
        }

        $aRet = array();
        foreach ($aa as $k => $a) {
            $sHtml = '';
            $aUsernames = array ();
            foreach ($a as $r) {
                $sHtml .= $r['html'];
                $aUsernames[] = $r['username'];
            }
            $aVars = array ('content' => $sHtml);
            $aRet[] = array (
                'lat' => $r['lat'],
                'lng' => $r['lng'],                
                'usernames' => $aUsernames,
                'data' => $this->_oTemplate->parseHtmlByName ('popup_profiles', $aVars),                
                'icon' => array ('w' => 16, 'h' => 16, 'sw' => 24, 'sh' => 16, 'sd' => '', 'url' => (count($a) > 1 ? $sIconGroup : $sIconUser)),
            );
        }
        $oParser = new Services_JSON();
        return $oParser->encode($aRet);
    }

    function _getCitiesData ($fLatMin, $fLatMax, $fLngMin, $fLngMax, $sMapClassInstanceName) {

        $sIconUrl = $this->_oTemplate->getIconUrl ('city.png');

        $a = $this->_oDb->getCitiesByBounds((float)$fLatMin, (float)$fLatMax, (float)$fLngMin, (float)$fLngMax);

        if (!preg_match('/^[A-Za-z0-9]+$/', $sMapClassInstanceName))
            return '';        

        $aRet = array();
        foreach ($a as $r) {
            $aVars = array (
                'url_flag' => genFlagUrl($r['country']),
                'country' => _t($GLOBALS['aPreValues']['Country'][$r['country']]['LKey']),
                'city' => trim($r['city']) ? $r['city'] : _t('_modzzz_listing_unknown_city'),
                'num' => sprintf(_t('_modzzz_listing_%d_members'), $r['num']),
                'lat' => $r['lat'],
                'lng' => $r['lng'],
                'zoom' => BX_LISTING_ZOOM_PROFILES,
                'map_instance_name' => $sMapClassInstanceName,
            );
            $sHtml = $this->_oTemplate->parseHtmlByName ('popup_city', $aVars);
            $aRet[] = array (
                'lat' => $r['lat'],
                'lng' => $r['lng'],
                'data' => $sHtml,
                'icon' => array ('w' => 48, 'h' => 28, 'sw' => 72, 'sh' => 48, 'sd' => '', 'url' => $sIconUrl),
            );
        }
        $oParser = new Services_JSON();
        return $oParser->encode($aRet);
    }

    function _getCountriesData ($fLatMin, $fLatMax, $fLngMin, $fLngMax, $sMapClassInstanceName) {

        $a = $this->_oDb->getCountriesByBounds((float)$fLatMin, (float)$fLatMax, (float)$fLngMin, (float)$fLngMax);

        if (!preg_match('/^[A-Za-z0-9]+$/', $sMapClassInstanceName))
            return '';

        $aRet = array();
        foreach ($a as $r) {
            $sFlagUrl = genFlagUrl($r['country']);
            $aVars = array (
                'url_flag' => $sFlagUrl, 
                'country' => _t($GLOBALS['aPreValues']['Country'][$r['country']]['LKey']), 
                'num' => sprintf(_t('_modzzz_listing_%d_members'), $r['num']),
                'lat' => $r['lat'],
                'lng' => $r['lng'],
                'zoom' => BX_LISTING_ZOOM_CITIES,
                'map_instance_name' => $sMapClassInstanceName,
            );
            $sHtml = $this->_oTemplate->parseHtmlByName ('popup_country', $aVars);
            $aRet[] = array (
                'lat' => $r['lat'],
                'lng' => $r['lng'],
                'data' => $sHtml,
                'icon' => array ('w' => 18, 'h' => 12, 'sw' => 20, 'sh' => 14, 'sd' => 1, 'url' => $sFlagUrl),
            );
        }
        $oParser = new Services_JSON();
        return $oParser->encode($aRet);
    }

    // ================================== admin actions

    function actionClearTable ($sTable,  $isClearFailedOnly = true) {

        if (!$this->isAdmin()) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }

        switch ($sTable) {
            case 'countries':
                $this->_oDb->clearCountries ($isClearFailedOnly ? true : false);
                $this->_oTemplate->displayMsg('Countries locations has been cleared');
                break;
            case 'cities':
                $this->_oDb->clearCities ($isClearFailedOnly ? true : false);
                $this->_oTemplate->displayMsg('Cities locations has been cleared');
                break;
            default:
                $this->_oTemplate->displayPageNotFound ();
        }
    }

    function actionUpdateCountries ($iLimit = 4, $iDelay = 6) { 

        if (!$this->isAdmin()) {
            $this->_oTemplate->displayAccessDenied ();
            return;            
        }

        $iLimit = (int)$iLimit;
        $iDelay = (int)$iDelay;

        $a = $this->_oDb->getUndefinedCountries ($iLimit);
        if ($a) {
            foreach ($a as $sCountryCode => $sCountryName) {
                $a = $this->_geocode ($sCountryName, $sCountryCode);                                
                if ($a) {                                        
                    $this->_oDb->insertCountryLocation ($sCountryCode, $a[0], $a[1]);
                } else {
                    $this->_oDb->insertCountryLocation ($sCountryCode, 0, 0, 1);
                }
                $this->onEventGeolocateCountry ($sCountryCode, array ('lat' => (isset($a[0]) ? $a[0] : false), 'lng' => (isset($a[1]) ? $a[1] : false)));
                if ($iDelay)
                    sleep ($iDelay);
            }

            $aVars = array (
                'refresh' => 1,
                'msg' => 'Countries update is in progress, please wait...',
            );
            echo $this->_oTemplate->parseHtmlByName ('updating', $aVars);
        } else {
            $this->_oTemplate->displayMsg('Countries locations update has been completed');
        }
    }

    function actionUpdateCities ($iLimit = 4, $iDelay = 6) {

        if (!$this->isAdmin()) {
            $this->_oTemplate->displayAccessDenied ();
            return;            
        }

        $iLimit = (int)$iLimit;
        $iDelay = (int)$iDelay;

        $a = $this->_oDb->getUndefinedCities ($iLimit);
        if ($a) {

            foreach ($a as $sCountryCode => $sCity) {
                $this->_updateCityLocation($iDelay, $sCity, $sCountryCode);
            }

            $aVars = array (
                'refresh' => 1,
                'msg' => 'Cities update is in progress, please wait...',
            );
            echo $this->_oTemplate->parseHtmlByName ('updating', $aVars);
        } else {
            $this->_oTemplate->displayMsg('Cities locations update has been completed');
        }
    }

    function actionUpdateProfiles ($iLimit = 4, $iDelay = 6) {

        if (!$this->isAdmin()) {
            $this->_oTemplate->displayAccessDenied ();
            return;            
        }

        $iLimit = (int)$iLimit;
        $iDelay = (int)$iDelay;

        $a = $this->_oDb->getUndefinedProfiles ($iLimit);
        if ($a) {
            foreach ($a as $iId => $r) {
                $this->_updateProfileLocation ($iDelay, $r);
            }

            $aVars = array (
                'refresh' => 1,
                'msg' => 'Listings update is in progress, please wait...',
            );
            echo $this->_oTemplate->parseHtmlByName ('updating', $aVars);
        } else {
            $this->_oTemplate->displayMsg('Listings locations update has been completed');
        }
    }
  
    function onEventGeolocateProfile ($iProfileId, $aLocation) {
		//
    }

    function onEventGeolocateCity ($sCity, $aLocation) {
		//
    }

    function onEventGeolocateCountry ($sCountryCode, $aLocation) {
		//
    }

    function onEventProfileLocationManuallyUpdated ($iProfileId, $aLocation) {
		//
    }
 
    // ================================== service actions

    /**
     * Get profile location by user id
     * @param $iProfileId user's id 
     * @param $iViewerProfileId viewer profile id
     * @param $isClearFailedOnly check privacy settings before returning location
     * @return array with location info on success, false on error, -1 if access denied
     */ 
    function serviceGetLocation ($iProfileId, $iViewerProfileId = 0, $isCheckPrivacy = true) {
        $iProfileId = (int)$iProfileId;
        $iViewerProfileId = (int)$iViewerProfileId;
        if ($isCheckPrivacy) {
            if (!$iViewerProfileId)
                $iViewerProfileId = $this->_iProfileId; 
            if (!$this->_oPrivacy->check('view_location', $iProfileId, $iViewerProfileId))
                return -1;
        }

        $aLocation = $iProfileId ? $this->_oDb->getProfileById($iProfileId) : false;
        if (!$aLocation)
            return false;

        if (-1 == $aLocation['zoom'])
            $aLocation['zoom'] = getParam('modzzz_listing_profile_zoom');

        if (!$aLocation['type'])
            $aLocation['type'] = getParam('modzzz_listing_profile_map_type');

        return $aLocation;
    }

    /**
     * Edit location block
     * @param $iEntryId user's id which location is edited
     * @return html with clickable map
     */ 
    function serviceEditLocation ($iEntryId) {

        $iEntryId = (int)$iEntryId;

        $aLocation = $iEntryId ? $this->_oDb->getProfileById($iEntryId) : false;
        if (!$aLocation)
            return false;

        $fLat = false;
        $fLng = false;
        $iZoom = false;
        $sMapType = false;

        if ($aLocation) {
            $fLat = $aLocation['lat'];
            $fLng = $aLocation['lng'];
            $iZoom = $aLocation['zoom'];
            $sMapType = $aLocation['type'];
        }

        if (false === $fLat || false === $fLng) {
            $aProfile = $this->_oDb->getProfileInfo($iEntryId);
            $aLocationCountry = $this->_oDb->getCountryByCode($aProfile['country'], false);
            $fLat = $aLocationCountry['lat'];
            $fLng = $aLocationCountry['lng'];
            $iZoom = BX_LISTING_ZOOM_CITIES;            
        }

        if (false === $iZoom || -1 == $iZoom)
            $iZoom = getParam('modzzz_listing_profile_zoom');

        if (!$sMapType)
            $sMapType = getParam('modzzz_listing_profile_map_type');

        $aVars = array (
            'msg_incorrect_google_key' => _t('_modzzz_listing_msg_incorrect_google_key'),
            'loading' => _t('_loading ...'),
            'map_control' => getParam('modzzz_listing_profile_control_type'),
            'map_is_type_control' => getParam('modzzz_listing_profile_is_type_control') == 'on' ? 1 : 0,
            'map_is_scale_control' => getParam('modzzz_listing_profile_is_scale_control') == 'on' ? 1 : 0,
            'map_is_overview_control' => getParam('modzzz_listing_profile_is_overview_control') == 'on' ? 1 : 0,
            'map_is_dragable' => getParam('modzzz_listing_profile_is_map_dragable') == 'on' ? 1 : 0,
            'map_type' => $sMapType,
            'map_lat' => $fLat,
            'map_lng' => $fLng,
            'map_zoom' => $iZoom,
            'suffix' => 'Edit',
            'subclass' => 'modzzz_listing_profile',
            'data_url' => BX_DOL_URL_MODULES . "?r=listing/get_data_profile/0/{instance}/{ts}",
            'save_data_url' => BX_DOL_URL_MODULES . "?r=listing/save_data_profile/{$iEntryId}/{zoom}/{map_type}/{lat}/{lng}/{instance}/{address}/{country}/{ts}",
            'save_location_url' => '',
            'shadow_url' => '',
        );
        return  $this->_oTemplate->parseHtmlByName('map', $aVars);
    }
 
 /*  
    function serviceMapBlock ($iProfileId) {
 
        $iProfileId = 1;//(int)$iProfileId;        
        $r = $this->_oDb->getProfileById($iProfileId);

        $sBoxContent = '';
        if ($r && $this->isAllowedViewLocation ($iProfileId, $r)) {

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
                'data_url' => BX_DOL_URL_MODULES . "' + '?r=listing/get_data_profile/" . $iProfileId . "/{instance}",
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
        if ($iProfileId == $this->_iProfileId) {
            $aVars = array (
                'icon' => $this->_oTemplate->getIconUrl('more.png'),
                'url' => $this->_oConfig->getBaseUri() . 'edit',
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
*/



    /**
     * Removes any geocoding information associated with profile
     * @param $iProfileId user's id which location going to be removed
     * @return true if location existed and was deleted, false on error or location didn't exist
     */ 
    function serviceDeleteProfileLocation ($iProfileId) {
        return $this->_oDb->deleteLocation((int)$iProfileId);
    }

    /**
     * Update profile's location
     * @param $iProfileId user's id which location going to be removed
     * @return true if position was successfully geocoded, and false if doesn't
     */     
    function serviceUpdateProfileLocation ($iProfileId) {
        $iProfileId = (int)$iProfileId;
        $a = $this->_oDb->getProfileInfo($iProfileId);
        if ($this->_updateProfileLocation(0, $a)) {
            if (!$this->_oDb->isCityLocationExists($a['country'], process_db_input($a['city'], BX_TAGS_NO_ACTION, BX_SLASHES_NO_ACTION)))
                $this->_updateCityLocation(0, $a['city'], $a['country']);
            return true;
        }
        return false;
    }

    /**
     * Update profile's location manually
     * @param $iProfileId user's id which location going to be updated 
     * @param $fLat latitude
     * @param $gLng longitude
     * @param $iZoom zoom level
     * @param $sMapType map type (hybrid, satellite or standard)
     * @param $sCountry 2 letters country code
     * @param $sAddress address string
     * @return true if position was successfully updated, and false if doesn't
     */     
    function serviceUpdateProfileLocationManually ($iEntryId, $fLat, $fLng, $iZoom, $sMapType, $sCountry, $sAddress) {
        $a = $this->_oDb->getProfileInfo((int)$iEntryId);
        $aLocationOld = $this->_oDb->getProfileById((int)$iEntryId);

        if (!$iZoom)
            $iZoom = $aLocationOld && $aLocationOld['zoom'] ? $aLocationOld['zoom'] : -1;
        switch ($sMapType) {
        case 'hybrid':
        case 'satellite':
        case 'standard':
        break;
        default:
            $sMapType = 'standard'; 
        }

        $fLat = (float)$fLat;
        $fLng = (float)$fLng;
        $iZoom = (int)$iZoom;

        $iRet = $this->_oDb->insertProfileLocation (
			$this->_iProfileId,    
			$iEntryId, 
            $fLat, 
            $fLng, 
            $iZoom, 
            $sMapType, 
            '', // address 
            '', // country 
            $aLocationOld && $aLocationOld['allow_view_location_to'] ? $aLocationOld['allow_view_location_to'] : BX_LISTING_DEFAULT_PRIVACY);

        if ($iRet) {
            $this->onEventProfileLocationManuallyUpdated ($iEntryId, array ('lat' => $fLat, 'lng' => $fLng, 'zoom' => $iZoom, 'map_type' => $sMapType, 'address' => '', 'country' => ''));
            return true;
        }

        return false;
    }
 
 
    // ================================== permissions  

    function isAllowedEditOwnLocation ($iEntryID, &$aLocation) {
        if(!$iEntryID) 
            return false;
        if (!$aLocation || $aLocation['id'] == $iEntryID) {
            return true;
        }        
        return false;
    }

    function isAllowedViewLocation ($iEntryID, &$aLocation) {
        if (!$aLocation) 
            return false;
		
		$aDataEntry = $this->_oDb->getEntryById($iEntryID);
 
        if ($aDataEntry['author_id'] == $this->_iProfileId || $this->isAdmin())
            return true;
 
        return $this->_oMapPrivacy->check('view_location', $iEntryID, $this->_iProfileId);  
    }
  
    function isAdmin () {
        return $GLOBALS['logged']['admin'] || $GLOBALS['logged']['moderator'];
    }             

    // ================================== other 

    function _geocode ($sAddress, $sCountryCode = '') {

        $iRet = 404;

        $sAddress = rawurlencode($sAddress);
        $sUrl = "http://maps.google.com/maps/geo";

        $s = bx_file_get_contents($sUrl, array(
            'q' => $sAddress,
            'output' => 'xml',
            'key' => getParam('modzzz_listing_key')
        ));
        
        if (preg_match ('/<code>(\d+)<\/code>/', $s, $m))
        {
            $iRet = $m[1];
            if (200 != $iRet) return false;

            if (preg_match_all ('/<CountryNameCode>([A-Za-z]+)<\/CountryNameCode>/', $s, $mCountry) &&
                preg_match_all ('/<coordinates>([0-9,\.-]+)<\/coordinates>/', $s, $mCoord))
            {
                if (isset($mCountry[1]) && is_array($mCountry[1]))
                    $mCountry = $mCountry[1];

                foreach ($mCountry AS $k => $v)
                {
                    if (!$sCountryCode || ($sCountryCode && 0 == strcasecmp($sCountryCode, $v))) 
                    {
                        // Parse coordinate string
                        list ($fLongitude, $fLatitude, $fAltitude) = explode(",", $mCoord[1][$k]);
                        return array ($fLatitude, $fLongitude, $v);
                    }
                }
            }
        }
        return false;
    }

    function _updateCityLocation($iDelay, &$sCity, $sCountryCode) {

        $iDelay = (int)$iDelay;
        if ($iDelay) sleep ($iDelay);
        $a = $this->_geocode ($sCity . ' ' . $sCountryCode, $sCountryCode);        
        if ($a) {
            $this->_oDb->insertCityLocation ($sCountryCode, process_db_input($sCity, BX_TAGS_NO_ACTION, BX_SLASHES_NO_ACTION), $a[0], $a[1]);
            $bRet = true;
        } else {
            $this->_oDb->insertCityLocation ($sCountryCode, process_db_input($sCity, BX_TAGS_NO_ACTION, BX_SLASHES_NO_ACTION), 0, 0, 1);
            $bRet = false;
        }
        $this->onEventGeolocateCity ($sCity, array ('lat' => (isset($a[0]) ? $a[0] : false), 'lng' => (isset($a[1]) ? $a[1] : false), 'country' => $sCountryCode));
        return $bRet;
    }

    function _updateProfileLocation($iDelay, &$r) {

        $iDelay = (int)$iDelay;

        $iId = (int)$r['id'];
        $a = false;

        $sAddressField = getParam('modzzz_listing_address_field');
        if ($sAddressField && isset ($r[$sAddressField])) {
            if ($iDelay) sleep ($iDelay);
			//echo  $sAddressField .":".$r[$sAddressField];exit;
            $a = $this->_geocode ($r[$sAddressField] . ' ' . $r['city'] . ' ' . $r['country'], $r['country']);
        }

        if (!$a) {
            if ($iDelay) sleep ($iDelay);
            $a = $this->_geocode ($r['zip'] . ' ' . $r['country'], $r['country']);
        }

        if (!$a) {
            if ($iDelay) sleep ($iDelay);
            $a = $this->_geocode ($r['city'] . ' ' . $r['country'], $r['country']);
        }
        
        if ($a) {
            $this->_oDb->insertProfileLocation ($r['author_id'], $iId, $a[0], $a[1], -1, '', process_db_input($r['city'] . ', ' . $r['country'], BX_TAGS_NO_ACTION, BX_SLASHES_NO_ACTION), process_db_input($r['country'], BX_TAGS_NO_ACTION, BX_SLASHES_NO_ACTION), BX_LISTING_DEFAULT_PRIVACY);
            $bRet = true;
        } else {
            $this->_oDb->insertProfileLocation ($r['author_id'], $iId, 0, 0, -1, '', '', '', BX_LISTING_DEFAULT_PRIVACY, 1);
            $bRet = false;
        }          
        $this->onEventGeolocateProfile ($iId, array ('lat' => (isset($a[0]) ? $a[0] : false), 'lng' => (isset($a[1]) ? $a[1] : false), 'country' => $sCountryCode));
        return $bRet;
    }

    function _saveLocationByPrefix ($sPrefix, $iZoom, $sMapType, $fLat, $fLng) {

        if (!$this->isAdmin()) {
            echo 'Access denied';
            return;
        }

        if ($iZoom = (int)$iZoom)
            setParam($sPrefix . '_zoom', $iZoom);

        switch ($sMapType) {
            case 'normal':
            case 'satellite':
            case 'hybrid':
                setParam($sPrefix . '_map_type', $sMapType);
        }                

        if ($fLat = (float)$fLat)
            setParam($sPrefix . '_lat', $fLat);

        if ($fLng = (float)$fLng)
            setParam($sPrefix . '_lng', $fLng);

        echo 'ok';
    }    

    function _saveLocationForm ($sSuffix, $sMap) {

        if (!preg_match('/^[A-Za-z0-9]+$/', $sSuffix))
            return '';

        $aCustomForm = array(
            
            'form_attrs' => array(
                'name'     => "modzzz_listing_save_location_{$sSuffix}",
                'onsubmit'   => "return glBxMap{$sSuffix}.saveLocation();",
                'method'   => 'post',
            ),

            'inputs' => array(

                'Map' => array (
                    'type' => 'custom',
                    'content' => "<div class=\"modzzz_listing_form_map\">$sMap</div>",
                    'name' => 'Map',
                    'caption' => _t('_modzzz_listing_admin_map'),
                ),

                'Submit' => array (
                    'type' => 'submit',
                    'name' => 'submit_form',
                    'value' => _t('_modzzz_listing_admin_save_location'),
                    'colspan' => true,
                ),                            
            ),
        );

        $f = new BxTemplFormView ($aCustomForm);
        return $f->getCode();
    }

    function _getProfilesByFilter ($sFilter) {

        $aGetParams = unserialize(base64_decode($sFilter));            
        if ($aGetParams && is_array($aGetParams))
            $aGetParams = $_REQUEST = $_GET = array_merge ($_REQUEST, $aGetParams);

        bx_import('BxDolProfileFields');
        $oPF = new BxDolProfileFields(9);
        $aRequestParams = $oPF->collectSearchRequestParams();

        bx_import ('BxTemplProfileView');
        $oProfileGenerator = new BxBaseProfileGenerator((int)$_COOKIE['memberID']);
        list ($aWhere, $sJoin, $sPossibleOrder) = $oProfileGenerator->GenSqlConditions($oPF->aBlocks, $aRequestParams);

        $iPage = isset($aGetParams['page']) && (int)$aGetParams['page'] > 0 ? (int)$aGetParams['page'] : 1;
        $iPerPage = isset($aGetParams['per_page']) && (int)$aGetParams['per_page'] > 0 ? (int)$aGetParams['per_page'] : getParam('modzzz_listing_per_page'); 
        $iStart = ($iPage - 1) * $iPerPage;            
        
        $sQuery = 'SELECT DISTINCT `' . $this->_oDb->_sPrefix . 'main`.`id`, `' . $this->_oDb->_sPrefix . 'main`.`thumb`, `' . $this->_oDb->_sPrefix . 'main`.`title` as `NickName`, `m`.`id`, `m`.`lat`, `m`.`lng`
            FROM `' . $this->_oDb->_sPrefix . 'main` 
            INNER JOIN `' . $this->_oDb->_sPrefix . 'profiles` AS `m` ON (`' . $this->_oDb->_sPrefix . 'main` .`id` = `m`.`id`) 
            ' . $sJoin . ' 
            WHERE ' . implode( ' AND ', $aWhere ) . ' AND `m`.`failed` = 0 ' . $sPossibleOrder . " 
            LIMIT $iStart, $iPerPage";

        // ID is ambiguous
        $sQuery = str_replace (' `id`', ' `' . $this->_oDb->_sPrefix . 'main`.`id`', $sQuery);
        
        $aProfiles = $GLOBALS['MySQL']->getAll($sQuery);

        return $aProfiles;
    } 
	/*[end] map integration*/


    function actionPaypalProcess($iListingId) {
        $sPostData = '';
        $sPageContent = '';

		$aData = &$_REQUEST;

        if($aData) {
            foreach ($aData as $sKey => $sValue) {
                $sPostData .= $sKey . '=' . urlencode($sValue) . '&'; 
            } 

            $sPostData .= 'cmd=_notify-validate';
            $oCurl = curl_init($this -> _oConfig -> getPurchaseBaseUrl());

            curl_setopt($oCurl, CURLOPT_HEADER, 0); 
            curl_setopt($oCurl, CURLOPT_POST, 1);
            curl_setopt($oCurl, CURLOPT_POSTFIELDS, $sPostData);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, 0); 
            curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 1);

            $sReturnVal = curl_exec ($oCurl);
            curl_close($oCurl);
 
            if ($sReturnVal != 'VERIFIED') {
 				$this->actionPostPurchase(_t('_modzzz_listing_purchase_failed'));
            } else {
                
				$aDataEntry = $this->_oDb->getEntryById ($_POST['item_number']);
				$sRedirectUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aDataEntry[$this->_oDb->_sFieldUri];

                //if (($_POST['receiver_email'] != trim(getParam('modzzz_listing_paypal_email'))) || ($_POST['txn_type'] != 'web_accept')) {

	            if($_POST['txn_type'] != 'web_accept') {
					$this->actionPostPurchase(_t('_modzzz_listing_purchase_failed'));
                }else{ 
                    if($this->_oDb->isExistPaypalTransaction($this->_iProfileId, $_POST['txn_id'])) { 
                        $this -> actionPostPurchase(_t('_modzzz_listing_transaction_completed_already', $sRedirectUrl)); 
                    } else {
                        if( $this->_oDb->saveTransactionRecord($this->_iProfileId, $_POST['item_number'], $_POST['txn_id'], 'Paypal Purchase')) { 
							
							$this->_oDb->setItemStatus($_POST['item_number'], 'approved'); 
							
							$this->_oDb->setInvoiceStatus($_POST['item_number'], 'paid');

                            $this->actionPostPurchase(_t('_modzzz_listing_purchase_success', $sRedirectUrl));
                        } else {
                            $this -> actionPostPurchase(_t('_modzzz_listing_trans_save_failed'));
                        }
                    }
                }
            }
        }
    }


    function actionPostPurchase($sTransMessage = '') {
 
        if(! $this->_iProfileId) {
            header('location: ' . BX_DOL_URL_ROOT . 'member.php');
        }
 
		$sMessageOutput = MsgBox($sTransMessage);  
	  
        $this->_oTemplate->pageStart();
    
	    echo $sMessageOutput;
    
        $this->_oTemplate->addCss ('main.css'); 
        $this->_oTemplate->pageCode(_t('_modzzz_listing_post_purchase_header')); 
    }
 

	//modzzz.com
    function actionPurchaseFeatured($iListingId, $sTransMessage = '') {
 
        if(! $this->_iProfileId) {
            header('location: ' . BX_DOL_URL_ROOT . 'member.php');
        }

	    if($sTransMessage){
			$sMessageOutput = MsgBox($sTransMessage);  
		}
 
		$iPerDayCost = getParam('modzzz_listing_featured_cost');

		$aDataEntry = $this->_oDb->getEntryById($iListingId);
		$sTitle = $aDataEntry['title'];

        $this->_oTemplate->pageStart();
 
		$aForm = array(
            'form_attrs' => array(
                'name' => 'buy_featured_form',
                'method' => 'post', 
                'action' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'purchase_featured/'.$iListingId,
            ),
            'params' => array (
                'db' => array(
                    'submit_name' => 'submit_purchase',
                ),
            ),
            'inputs' => array( 
  
                'title' => array(
                    'type' => 'custom',
                    'name' => 'title',
					'caption'  => _t('_modzzz_listing_form_caption_title'),
                    'content' => $sTitle,
                ), 
                'cost' => array(
                    'type' => 'custom',
                    'name' => 'cost',
					'caption'  => _t('_modzzz_listing_featured_cost_per_day'),
                    'content' => $iPerDayCost .' '. $this->_oConfig->getPurchaseCurrency(),
                ), 
                'status' => array(
                    'type' => 'custom',
                    'name' => 'status',
					'caption'  => _t('_modzzz_listing_featured_status'),
                    'content' => ($aDataEntry['featured']) ? _t('_modzzz_listing_featured_until', $this->_oTemplate->filterDate($aDataEntry['featured_expiry_date'])) : _t('_modzzz_listing_not_featured'), 
                ), 
                'quantity' => array(
                    'caption'  => _t('_modzzz_listing_caption_num_featured_days'),
                    'type'   => 'text',
                    'name' => 'quantity',
                    'required' => true,
                    'checker' => array (  
                        'func'   => 'Preg',
                        'params' => array('/^[0-9]+$/'),
                        'error'  => _t('_modzzz_listing_caption_err_featured_days'),
                    ),
                ),
                'submit' => array(
                    'type'  => 'submit',
                    'value' => ($aDataEntry['featured']) ? _t('_modzzz_listing_extend_featured') : _t('_modzzz_listing_get_featured'),
                    'name'  => 'submit_purchase',
                ),
            ),
        );
        $oForm = new BxTemplFormView($aForm);
        $oForm->initChecker();  

        if ($oForm->isSubmittedAndValid() && $oForm->getCleanValue('quantity')) { 

			$fCost = floatval($oForm->getCleanValue('quantity')) * floatval($iPerDayCost);

            header('location:' . $this->_oDb->generateFeaturedPaymentUrl($iListingId, $oForm->getCleanValue('quantity'), $fCost));
        } else {
             echo $sMessageOutput . $oForm->getCode();
        }

        $this->_oTemplate->addCss ('main.css'); 
        $this->_oTemplate->pageCode(_t('_modzzz_listing_purchase_featured'));
  
    }


    function actionPaypalFeaturedProcess($iListingId) {
        $sPostData = '';
        $sPageContent = '';

        if($_POST) {
            foreach ($_POST as $sKey => $sValue) {
                $sPostData .= $sKey . '=' . urlencode($sValue) . '&'; 
            } 

            $sPostData .= 'cmd=_notify-validate';
            $oCurl = curl_init($this -> _oConfig -> getPurchaseBaseUrl());

            curl_setopt($oCurl, CURLOPT_HEADER, 0); 
            curl_setopt($oCurl, CURLOPT_POST, 1);
            curl_setopt($oCurl, CURLOPT_POSTFIELDS, $sPostData);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, 0); 
            curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 1);

            $sReturnVal = curl_exec ($oCurl);
            curl_close($oCurl);

			$iQuantity = $_POST['item_number'];
 
            if ($sReturnVal != 'VERIFIED') {
 				$this->actionPurchaseFeatured($iListingId, _t('_modzzz_listing_purchase_failed'));
            } else {
                
                if($_POST['txn_type'] != 'web_accept') {
					$this->actionPurchaseFeatured($iListingId, _t('_modzzz_listing_purchase_failed'));
                }else { 
                    
					if($this->_oDb->isExistFeaturedTransaction($this->_iProfileId, $_POST['txn_id'])) {
                        $this -> actionPurchaseFeatured($iListingId, _t('_modzzz_listing_transaction_completed_already')); 
                    } else {
                        if( $this->_oDb->saveFeaturedTransactionRecord($this->_iProfileId, $iListingId,  $iQuantity, $_POST['mc_gross'], $_POST['txn_id'], 'Paypal Purchase')) {
 
							$this->_oDb->updateFeaturedEntryExpiration($iListingId, $iQuantity); 
				   
                            $this->actionPurchaseFeatured($iListingId, _t('_modzzz_listing_purchase_success',  $iQuantity));
                        } else {
                            $this -> actionPurchaseFeatured($iListingId, _t('_modzzz_listing_purchase_fail'));
                        }
                    }
                }
            }
        }
    }
 
    function isAllowedPurchaseFeatured ($aDataEntry, $isPerformAction = false) {
  
		if(getParam("modzzz_listing_buy_featured")!='on')
			return false;
 
		if($aDataEntry['author_id'] != $this->_iProfileId)
			return false;
 
        $this->_defineActions();
        $aCheck = checkAction($this->_iProfileId, BX_LISTING_PURCHASE_FEATURED, $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;        
    } 

    function actionLocal ($sCountry='', $sState='') { 
        $this->_oTemplate->pageStart();
        bx_import ('PageLocal', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'PageLocal';
        $oPage = new $sClass ($this, $sCountry, $sState);
        echo $oPage->getCode();
        $this->_oTemplate->addCss ('main.css');
        $this->_oTemplate->addCss ('unit.css');
         $this->_oTemplate->pageCode(_t('_modzzz_listing_page_title_local'), false, false);
    } 



    function isMembershipEnabledForImages () {
        return ($this->_isMembershipEnabledFor ('BX_PHOTOS_ADD') && $this->_isMembershipEnabledFor ('BX_LISTING_PHOTOS_ADD'));
    }

    function isMembershipEnabledForVideos () {
        return $this->_isMembershipEnabledFor ('BX_VIDEOS_ADD');
        return ($this->_isMembershipEnabledFor ('BX_VIDEOS_ADD') && $this->_isMembershipEnabledFor ('BX_LISTING_VIDEOS_ADD')); 
    }

    function isMembershipEnabledForSounds () {
        return ($this->_isMembershipEnabledFor ('BX_SOUNDS_ADD') && $this->_isMembershipEnabledFor ('BX_LISTING_SOUNDS_ADD'));
    }

    function isMembershipEnabledForFiles () {
        return ($this->_isMembershipEnabledFor ('BX_FILES_ADD') && $this->_isMembershipEnabledFor ('BX_LISTING_FILES_ADD'));
    }
 
    function _isMembershipEnabledFor ($sMembershipActionConstant) { 
        defineMembershipActions (array('photos add', 'sounds add', 'videos add', 'files add', 'listing photos add', 'listing sounds add', 'listing videos add', 'listing files add'));
		if (!defined($sMembershipActionConstant))
			return false;
		$aCheck = checkAction($_COOKIE['memberID'], constant($sMembershipActionConstant));
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }  

     /**
     * forum permissions
     * @param $iMemberId profile id
     * @param $iForumId forum id
     * @return array with permissions
     */ 
    function serviceGetForumPermission($iMemberId, $iForumId) {

        $iMemberId = (int)$iMemberId;
        $iForumId = (int)$iForumId;

        $aFalse = array ( // default permissions, for visitors for example
            'admin' => 0,
            'read' => 1,
            'post' => 0,
        );

        if (!($aForum = $this->_oDb->getForumById ($iForumId))) {    
			return $aFalse;
        }
  
        if (!($aDataEntry = $this->_oDb->getEntryById ($aForum['entry_id']))){
 			return $aFalse;
		}
 
        $aTrue = array (
            'admin' => $aDataEntry[$this->_oDb->_sFieldAuthorId] == $iMemberId || $this->isAdmin() ? 1 : 0, // author is admin
            'read' => $this->isAllowedPostInForum ($aDataEntry, $iMemberId) ? 1 : 0,
            'post' => $this->isAllowedPostInForum ($aDataEntry, $iMemberId) ? 1 : 0,
        );
  
        return $aTrue;
    }
 
     function isAllowedPostInForum(&$aDataEntry, $iProfileId = -1) {
        if (-1 == $iProfileId)
            $iProfileId = $this->_iProfileId;
        return $this->isAdmin() || $this->isEntryAdmin($aDataEntry) || $this->_oPrivacy->check('post_in_forum', $aDataEntry['id'], $iProfileId);
    }

    function isAllowedManageFans($aDataEntry) {
        return $this->isEntryAdmin($aDataEntry);
    }

    function isFan($aDataEntry, $iProfileId = 0, $isConfirmed = true) {
        if (!$iProfileId)
            $iProfileId = $this->_iProfileId;
        return $this->_oDb->isFan ($aDataEntry['id'], $iProfileId, $isConfirmed) ? true : false;
    }

    function isEntryAdmin($aDataEntry, $iIdProfile = 0) {
        if (!$iIdProfile)
            $iIdProfile = $this->_iProfileId;
        return ($this->isAdmin() || $aDataEntry['author_id'] == $iIdProfile);
    }

}

?>
