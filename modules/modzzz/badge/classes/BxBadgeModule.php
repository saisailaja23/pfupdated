<?php
/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx 
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

function modzzz_badge_import ($sClassPostfix, $aModuleOverwright = array()) {
    global $aModule;
    $a = $aModuleOverwright ? $aModuleOverwright : $aModule;
    if (!$a || $a['uri'] != 'badge') {
        $oMain = BxDolModule::getInstance('BxBadgeModule');
        $a = $oMain->_aModule;
    }
    bx_import ($sClassPostfix, $a) ;
}

bx_import('BxDolPaginate');
bx_import('BxDolAlerts');
bx_import('BxDolTwigModule');
bx_import('BxTemplSearchResult');
 
/*
 * Badge module
 *
 * This module allow users to create user's badge, 
 * users can rate, comment and discuss badge.
 * Badge can have photos, videos, sounds and files, uploaded
 * by badge's admins.
 *
 * 
 
 *
 *
 *
 * Memberships/ACL:
 * badge view badge - BX_BADGE_VIEW_BADGE
 * badge browse - BX_BADGE_BROWSE
 * badge add badge - BX_BADGE_ADD_BADGE
 * badge edit any badge - BX_BADGE_EDIT_ANY_BADGE
 * badge delete any badge - BX_BADGE_DELETE_ANY_BADGE
 * badge approve badge - BX_BADGE_APPROVE_BADGE
 *
 * 
 *
 * Service methods:
 *
 * Homepage block with different badge
 * @see BxBadgeModule::serviceHomepageBlock
 * BxDolService::call('badge', 'homepage_block', array());
 *
 
 *
 * Member menu item for badge (for internal usage only)
 * @see BxBadgeModule::serviceGetMemberMenuItem
 * BxDolService::call('badge', 'get_member_menu_item', array());
 *
 * 

 *
 */
class BxBadgeModule extends BxDolTwigModule {
 
    var $_aQuickCache = array ();

    function BxBadgeModule(&$aModule) {

        parent::BxDolTwigModule($aModule);        
        $this->_sFilterName = 'modzzz_badge_filter';
        $this->_oDb->_sPrefix = 'modzzz_badge_';

	    $this->_oConfig->init($this->_oDb);
  
        $GLOBALS['oBxBadgeModule'] = &$this;
		  
		//check for AJAX call 
		if(isset($_GET['remove'])){
			$this->_oDb->removeBadge($this->_iProfileId, $_GET['remove']); 
		} 
		//end check
 
    }

	function serviceGetMemberLevel($iProfileId=0) {
 
		$iOwner = (!$iProfileId || ($iProfileId==$this->_iProfileId)) ? true : false;
 
		$iProfileId = ($iProfileId) ? $iProfileId : $this->_iProfileId;

	    $aUserLevel = getMemberMembershipInfo($iProfileId); 
		
	    return $this->_oTemplate->displayCurrentLevel($aUserLevel, $iOwner);
	}

  
    function getBadge ($iProfileId) { 

		$aMemberships = getMemberships();

		$aProfileMembership = getMemberMembershipInfo($iProfileId);
		$iMembershipId = (int)$aProfileMembership['ID']; 
		$sMembershipName = $aMemberships[$iMembershipId];

		$aBadgeInfo = $this->_oDb->getBadgeByMembership($iMembershipId);
		$iBadgeId = $aBadgeInfo['id'];
		$sBadgeIcon = $aBadgeInfo['icon']; 
 
		$sBadgeIconUrl = $this->_oDb->getBadgeIcon($iBadgeId, $sBadgeIcon, true);  
  	    
		$bShowLevel = (getParam('modzzz_badge_activated')=='on') ? true : false;
		$bShowLevel = ($sBadgeIconUrl) ? $bShowLevel : false;
	 
		$sPosition = array(
			'bottom_left'=>'left:0px;bottom:0px;','bottom_right'=>'right:0px;bottom:0px;','top_left'=>'left:0px;top:0px;','top_right'=>'right:0px;top:0px;'
		);
		$aVariables = array(
			  'condition' => $bShowLevel,
			  'content' => array(
				'level_icon_url' => $sBadgeIconUrl,
				'level_icon_title' => $sMembershipName,
				'level_icon_img_width'  => getParam('modzzz_badge_icon_width'), 
				'level_icon_img_height' => getParam('modzzz_badge_icon_height'), 
				'orientation' => $sPosition[getParam('modzzz_badge_orientation')]   
			  )
		);

		return $aVariables; 
	}
 
    function actionHome ($sAction='', $iRedeemerId=0) { 
 /*
        $this->_oTemplate->pageStart();
        bx_import ('PageMain', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'PageMain';
        $oPage = new $sClass ($this);
        echo $oPage->getCode();
        $this->_oTemplate->addCss ('badge_main.css');
        $this->_oTemplate->addCss ('badge_unit.css'); 
        $this->_oTemplate->addCss ('badge_select.css'); 
  		$this->_oTemplate->addJs ('badge.js');  
 
        $this->_oTemplate->pageCode(_t('_modzzz_badge_page_title_home'), false, false);  
 */   
	}
 
	function actionAdd () {
        parent::_actionAdd (_t('_modzzz_badge_page_title_add'));
    }
 
    function actionEdit ($iEntryId) {
        parent::_actionEdit ($iEntryId, _t('_modzzz_badge_page_title_edit'));
    }
 
  
    function actionSearch ($sKeyword = '', $sCategory = '') {
        parent::_actionSearch ($sKeyword, $sCategory, _t('_modzzz_badge_page_title_search'));
    }
  
    // ================================== external actions
   
    function serviceGetMemberMenuItem () {
        parent::_serviceGetMemberMenuItem (_t('_modzzz_badge'), _t('_modzzz_badge'), 'badge.png');
    }
 
    // ================================== admin actions

    function actionAdministration ($sUrl = '', $sParam1 = '') {

        if (!$this->isAdmin()) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }        
  
		//perform delete
		if($sParam1 && isset($_POST['delete_badge'])) {
			$this->_oDb->deleteBadgeIcon($sParam1);
			$sUrl = 'badge'; 
			$sMessage = _t("_modzzz_badge_badge_delete_success"); 
		}
  
        $this->_oTemplate->pageStart();

        $aMenu = array(
 			'badge' => array(
                'title' => _t('_modzzz_badge_menu_admin_manage_badge'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/badge',
                '_func' => array ('name' => 'actionAdministrationBadge', 'params' => array($sMessage)),
            ),	
			'create' => array(
                'title' => _t('_modzzz_badge_menu_admin_create_badge'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/create',
                '_func' => array ('name' => 'actionAdministrationCreate', 'params' => array($sUrl, $sParam1)),
            ),   
            'settings' => array(
                'title' => _t('_modzzz_badge_menu_admin_settings'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/settings',
                '_func' => array ('name' => 'actionAdministrationSettings', 'params' => array()),
            ),
        );

		if (empty($aMenu[$sUrl])){
            $sUrl = 'settings';
		}
 

        $aMenu[$sUrl]['active'] = 1;
        $sContent = call_user_func_array (array($this, $aMenu[$sUrl]['_func']['name']), $aMenu[$sUrl]['_func']['params']);

        echo $this->_oTemplate->adminBlock ($sContent, _t('_modzzz_badge_page_title_administration'), $aMenu);
        $this->_oTemplate->addCssAdmin ('badge_admin.css');
        $this->_oTemplate->addCssAdmin ('badge_unit.css');
        $this->_oTemplate->addCssAdmin ('badge_main.css');
        $this->_oTemplate->addCssAdmin ('forms_extra.css'); 
        $this->_oTemplate->addCssAdmin ('forms_adv.css');        
        $this->_oTemplate->pageCodeAdmin (_t('_modzzz_badge_page_title_administration'));
    }

    function actionAdministrationSettings () {
        return parent::_actionAdministrationSettings ('Badge');
    }
 
    function actionAdministrationCreate($sUrl='', $iEntryId=0) {

        $iEntryId = (int)$iEntryId;
  
		if($iEntryId) {
				
			 if (!($aDataEntry = $this->_oDb->getEntryById($iEntryId))) {
				$this->_oTemplate->displayPageNotFound ();
				exit;
			 }
	  
			if (!$this->isAllowedEdit($aDataEntry)) {
				$this->_oTemplate->displayAccessDenied ();
				exit;
			}			
	  
			ob_start();        
			$this->_editForm($iEntryId);
			$aVars = array (
				'content' => ob_get_clean(),
			); 
		}else{
			if (!$this->isAllowedAdd()) {
				$this->_oTemplate->displayAccessDenied ();
				return;
			}

			ob_start();        
			$this->_addForm($iEntryId);
			$aVars = array (
				'content' => ob_get_clean(),
			);  
		}
        return $this->_oTemplate->parseHtmlByName('default_padding', $aVars);
    }
 
    function _editForm ($iEntryId) { 

        $iEntryId = (int)$iEntryId;
		$aDataEntry = $this->_oDb->getEntryById($iEntryId);
	   
        bx_import ('FormEdit', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'FormEdit';
        $oForm = new $sClass ($this, $iEntryId, $aDataEntry);
 
        $oForm->initChecker($aDataEntry);

        if ($oForm->isSubmittedAndValid ()) {
 
            if ($iEntryId) {
  
				$aValsAdd = $this->processMedia($iEntryId,$oForm->getCleanValue('resize_icon'),$oForm->getCleanValue('resize_image'));
  
				if($aValsAdd[$this->_oDb->_sFieldImage])
					$this->_oDb->updateMembershipImg($oForm, $aValsAdd);
  
				$oForm->update ($iEntryId, $aValsAdd);

                header ('Location:' . BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . "administration/badge" );
                exit;

            } else { 
                echo MsgBox(_t('_Error Occured')); 
            }            

        } else { 
            echo $oForm->getCode (); 
        }  
 
    }
 
    function _addForm ($sRedirectUrl) {

        bx_import ('FormAdd', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'FormAdd';
        $oForm = new $sClass ($this);
        $oForm->initChecker();

        if ($oForm->isSubmittedAndValid ()) {
 
            $aValsAdd = array (
                $this->_oDb->_sFieldCreated => time() 
            );                        
  
			$aMediaAdd = $this->processMedia($iEntryId,$oForm->getCleanValue('resize_icon'),$oForm->getCleanValue('resize_image'));
			$aValsAdd = array_merge($aValsAdd, $aMediaAdd);

			if($aValsAdd[$this->_oDb->_sFieldImage])
				$this->_oDb->updateMembershipImg($oForm, $aValsAdd);

            $iEntryId = $oForm->insert ($aValsAdd);

            if ($iEntryId) { 

				$sRedirectUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . "administration/badge";
                header ('Location:' . $sRedirectUrl);
                exit;

            } else {

                echo MsgBox(_t('_Error Occured'));
            }
                         
        } else {
            
            echo $oForm->getCode ();

        }
    }

    function processMedia ($iEntryId, $bResizeIcon, $bResizeImage) {
 
 		$aIcons = $this->_actionAdminUploadIcon($iEntryId, $bResizeIcon, $bResizeImage);
		$sIcon = $aIcons[0];
		$sImage = $aIcons[1];

		$aValsAdd = array ();

		if($sIcon)
			$aValsAdd[$this->_oDb->_sFieldIcon] = $sIcon; 

		if($sImage)
			$aValsAdd[$this->_oDb->_sFieldImage] = $sImage; 

		return $aValsAdd; 
	}

    function actionAdministrationBadge ($sMessage='') {
  
		$iLimit = 20;//(int)getParam( "modzzz_badge_perpage_browse" );
  
        $iCount = (int)$this->_oDb->getOne("SELECT COUNT(`id`) FROM `" . $this->_oDb->_sPrefix . "main`");
 
        $aData = array();
        $sPaginate = '';
        if ($iCount) {
         
            $iPages = ceil($iCount/ $iLimit);
            $iPage = (int)$_GET['page'];
            if ($iPage < 1)
                $iPage = 1;
            if ($iPage > $iPages)
                $iPage = $iPages;

            $sqlFrom = ($iPage - 1) * $iLimit;
            $sqlLimit = "LIMIT $sqlFrom, $iLimit";
          
			$aBadge = $this->_oDb->getAll("SELECT * FROM `" . $this->_oDb->_sPrefix . "main`   ORDER BY `membership_id` ASC $sqlLimit");
 			
			$sEditPageUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/create/';
 
			$aMemberships = getMemberships();
  
			$aBadgeCells = array();
			foreach($aBadge as $aEachBadge)
			{  

				$sIconUrl = $this->_oDb->getBadgeIcon($aEachBadge['id'], $aEachBadge['large_icon'], false, true );
				if(!$sIconUrl)
					$sIconUrl = $this->_oDb->getBadgeIcon($aEachBadge['id'], $aEachBadge['icon']);

				$iEachId = $aEachBadge['id']; 
				$aBadgeCells[] = array ( 
					'id'=> $iEachId, 
					'title'=> $aMemberships[$aEachBadge['membership_id']], 
					'thumb' => $sIconUrl,  
  					'editurl' => $sEditPageUrl.$iEachId,
				); 
			}
   
			$sFormName = 'badge_form'; 
			$sPageUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/badge/'; 
			$sAddPageUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/create/';
			$sAddC = _t("_modzzz_badge_categ_btn_add");
			$sControls = BxTemplSearchResult::showAdminActionsPanel($sFormName, array(), 'pathes', false, false, "<input type=button value='{$sAddC}' onclick=\"window.location='{$sAddPageUrl}'\">");	
			
			if ($iPages > 1) {
				$oPaginate = new BxDolPaginate(array(
					'page_url' => $sPageUrl,
					'count' => $iCount,
					'per_page' => $iLimit,
					'page' => $iPage,
					'per_page_changer' => true,
					'page_reloader' => true, 
					'on_change_page' => 'getHtmlData(\'admin_badge\', \''.$sPageUrl.'?&ajax=1&page={page}&per_page={per_page}\');return false',
					'on_change_per_page' => ''
				));
				$sPaginate = $oPaginate->getSimplePaginate('', -1, -1, false); 
			}else{
				$sPaginate = "";
			}
  
			$aVars = array(
				'bx_repeat:items' => $aBadgeCells,  
 				'form_name' => $sFormName,  
				'pagination' => $sPaginate,
				'controls' => $sControls, 
			);

			if($sMessage)
				$sContent .= MsgBox($sMessage);

			$sContent .= $this->_oTemplate->parseHtmlByName('admin_badge',$aVars);
		}else{
			$sContent .= MsgBox(_t("_modzzz_badge_no_badge")); 
		}

		if($_GET['ajax']){
			echo $sContent;
			exit;
		}

		$sCode = "<div id='admin_badge'>{$sContent}</div>";
		
		return $sCode;
	}
 
 
    function _manageEntries ($sMode, $sValue, $isFilter, $sFormName, $aButtons, $sAjaxPaginationBlockId = '', $isMsgBoxIfEmpty = true, $iPerPage = 0) {

        bx_import ('SearchResult', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'SearchResult';
        $o = new $sClass($sMode, $sValue);
        $o->sUnitTemplate = 'unit_admin';

        if ($iPerPage)
            $o->aCurrent['paginate']['perPage'] = $iPerPage;

        $sPagination = $sActionsPanel = '';
        if ($o->isError) {
            $sContent = MsgBox(_t('_Error Occured'));
        } elseif (!($sContent = $o->displayResultBlock())) {
            if ($isMsgBoxIfEmpty)
                $sContent = MsgBox(_t('_Empty'));
            else
                return '';
        } else {
            $sPagination = $sAjaxPaginationBlockId ? $o->showPaginationAjax($sAjaxPaginationBlockId) : $o->showPagination();
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
 
    function _actionAdminUploadIcon ( $iBadgeId=0, $bResizeIcon, $bResizeImage) {
	 
		$sIconFile = "iconfile";
  		$sImageFile = "imagefile"; 
		$sImage = ''; 
		$sIcon = '';

		$sIconPath = $this->_oConfig->getIconsPath();	 
		$sImagePath = $this->_oConfig->getImagesPath();  
			  
		$iIconWidth = getParam("modzzz_badge_icon_width");
		$iIconHeight = getParam("modzzz_badge_icon_height");  
  
		if ( 0 < $_FILES[$sIconFile]['size'] && 0 < strlen( $_FILES[$sIconFile]['name'] ) ) {
			 
			$sIconFileName = time() . rand(1000, 9999); 
  
			$sExt = moveUploadedImage( $_FILES, $sIconFile, $sIconPath . $sIconFileName, '', false );
			if( strlen( $sExt ) && !(int)$sExt ) {
			 
				if($iBadgeId){
					$this->_actionAdminRemoveIcon($iBadgeId, 'icon');
				}

				$sIconPath = $sIconPath.$sIconFileName.$sExt;
 			 
				if($bResizeIcon)
					imageResize( $sIconPath, $sIconPath, $iIconWidth, $iIconHeight);
			
				chmod( $sIconPath, 0644 );
 				 
				if ($sExt != ''){
					$sIcon = $sIconFileName.$sExt;
 				}
			} 
		}

		if ( 0 < $_FILES[$sImageFile]['size'] && 0 < strlen( $_FILES[$sImageFile]['name'] ) ) {
			 
			$sImageFileName = time() ; 
  
			$sExt = moveUploadedImage( $_FILES, $sImageFile, $sImagePath . $sImageFileName, '', false );
			if( strlen( $sExt ) && !(int)$sExt ) {
			 
				if($iBadgeId){
					$this->_actionAdminRemoveIcon($iBadgeId, 'image');
				}
 
				$sImagePath = $sImagePath.$sImageFileName.$sExt;
		
				if($bResizeImage)
					imageResize( $sImagePath, $sImagePath, 110, 110);
			
				chmod( $sImagePath, 0644 );
 				 
				if ($sExt != ''){
 					$sImage = $sImageFileName.$sExt;
				}
			} 

		}
		  
		return array($sIcon,$sImage);  
	}

	function _actionAdminRemoveIcon($iId, $sType='icon') {
	  
		$aIcon = $this->_oDb->getRow("SELECT `icon`, `large_icon` FROM `" . $this->_oDb->_sPrefix . "main` WHERE `id` = '$iId'");

		if($sType=='icon'){
			$sIconName = $aIcon['icon'];  
			$sIconObject = $this->_oConfig->getIconsUrl(). $sIconName;

			if (file_exists($sIconObject) && !is_dir($sIconObject)) {
				unlink( $sIconObject ); 
			} 
		}elseif($sType=='image'){
			$sImageName = $aIcon['large_icon'];
			$sImageObject = $this->_oConfig->getImagesUrl(). $sImageName;
	 
			if (file_exists($sImageObject) && !is_dir($sImageObject)) {
				unlink( $sImageObject ); 
			} 
		}

		return;
	} 
    
    // ================================== permissions
 
    function isAllowedAdd ($isPerformAction = false) {
        if ($this->isAdmin()) 
            return true;
 
        return false;
    } 

    function isAllowedEdit ($aDataEntry) {
        if ($this->isAdmin()) 
            return true;
        
		return false;
    } 
 
     function isAllowedDelete ($aDataEntry) {
        if ($this->isAdmin()) 
            return true;
        
		return false;
    } 
  
	function isPermalinkEnabled() {
		$bEnabled = isset($this->_isPermalinkEnabled) ? $this->_isPermalinkEnabled : ($this->_isPermalinkEnabled = (getParam('permalinks_badge') == 'on'));
		 
        return $bEnabled;
    }


    function _defineActions () {
        defineMembershipActions(array('badge view badge', 'badge comments delete and edit', 'badge edit any badge', 'badge delete any badge',  'badge approve badge' ));
    }

    function _browseMy ($aProfile) {        
        parent::_browseMy ($aProfile, _t('_modzzz_badge_page_title_my_badge'));
    } 
    
}

?>