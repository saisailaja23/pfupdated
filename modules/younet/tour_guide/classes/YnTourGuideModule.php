<?php
bx_import('BxDolModule');

class YnTourGuideModule extends BxDolModule
{
	var $_iVisitorID;
	var $_sModuleUri;

	function YnTourGuideModule($aModule)
	{
		parent::BxDolModule($aModule);
		$this->_iVisitorID = (isMember()) ? (int) $_COOKIE['memberID'] : 0;
		$this->_sModuleUri = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri();
	}

	function CheckLogged()
	{
		$iProfileId = (isset($_COOKIE['memberID']) && ($GLOBALS['logged']['member'] || $GLOBALS['logged']['admin']))
		? (int) $_COOKIE['memberID']
		: 0;
		if(!$iProfileId)
			member_auth(0);
	}

	function isAjaxMode()
	{
		return ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) and $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) ? true : false;
	}

	function isAdmin () {
        $session =  BxDolSession::getInstance();
		$aData = $session->getValue('yn_tour_admin');
		return isAdmin($this->_iVisitorID) || isModerator($this->_iVisitorID) || $aData;
	}
    
    function actionAjaxMode($sAction)
    {
        $sRetHtml = '';
		if(!$this->isAjaxMode())
			$aResponse = array('err' => 1);
		else
		{
			switch($sAction)
			{
				case 'order_station':
					$aResponse = $this->getOrderStation();
					break;
                case 'order_station_edit':
                    $aResponse = $this->getOrderStationEdit();
                    break;
                case 'get_form_station_admin':
                    $aResponse = $this->getFormStationAdmin();
                    break;
                case 'get_tour':
                    $aResponse = $this->getTour();
                    break;
                case 'get_tour_data':
                    $aResponse = $this->getTourData();
                    break;
                case 'add_tour':
                    $aResponse = $this->addTour();
                    break;
                case 'enable_tour':
                    $aResponse = $this->enableTour();
                    break;
                case 'get_tour_info':
                    $aResponse = $this->getTourInfo();
                    break;
                case 'edit_station':
                    $aResponse = $this->getEditStation();
                    break;
                case 'delete_station':
                    $aResponse = $this->getDeleteStation();
                    break;
                case 'add_station';
                    $aResponse = $this->getAddStation();
                    break;
                case 'get_test_tour_data':
                    $aResponse = $this->getTestTourData();
                    break;
                case 'check_pass_mode':
                    $aResponse = $this->getCheckPassMode();
                    break;
                case 'set_tour_viewed':
                    $aResponse = $this->setTourViewed();
                    break;
                case 'set_page_hide':
                    $aResponse = $this->setPageHide();
                    break;
				default:
					break;
			}
		}
		echo json_encode($aResponse);
    }
    
    function setPageHide()
    {
        if($this->_iVisitorID > 0)
        {
            
            $sPagename = process_db_input(trim($_REQUEST['pathname']));
            $iHide = trim($_REQUEST['hide']);
            $aTourIds = $this->_oDb->getTourViewed($sPagename, $this->_iVisitorID);
            if($aTourIds)
            {
                $this->_oDb->updateHideTour($aTourIds, $iHide);
                if($iHide == 1)
                {
                    if(count($aTourIds)  == count($this->_oDb->getAllTourByPathname($sPagename, $this->isGuest())))
                        return array('hide_all' => true);
                    return array('is_hide' => true, 'tour_id' => $aTourIds);
                }
                else
                {
                    if(count($aTourIds)  == count($this->_oDb->getAllTourByPathname($sPagename, $this->isGuest())))
                        return array('show_all' => true);
                    return array('is_hide' => false, 'tour_id' => $aTourIds);
                }
                    
            }
        }
    }
    
    function setTourViewed()
    {
        if($this->_iVisitorID > 0)
        {
            $iTourId = trim($_REQUEST['tour_id']);
            $iHideTour = trim($_REQUEST['tour_hide']);
            if($this->_oDb->addViewedTour($iTourId, $this->_iVisitorID, $iHideTour) == 1)
                return array(
                    'img_viewed' => $this->_oTemplate->getImageUrl('viewed.png'), 
                );
        }
    }
    
    function getCheckPassMode()
    {
        $sPass = process_db_input(trim($_REQUEST['pass']));
        $aResultObj['wrong_pass'] = true;
        if($sPass == getParam('management_mode_pass'))
        {
            bx_import('BxDolSession');
            $session =  BxDolSession::getInstance();
            $session->setValue('yn_tour_admin', true);
            $aResultObj['wrong_pass'] = false;
            $aResultObj['admin_script'] = BX_DOL_URL_MODULES . 'younet/tour_guide/js/tour_guide_core_admin.js';
        }
        return $aResultObj;
    }
    
    function getTestTourData()
    {
        $iTourId = trim($_REQUEST['tour_id']);
        $aStations = $this->_oDb->getAllStationByTourId($iTourId);
        if($aStations)
        {
            $aTour = $this->_oDb->getTourById($iTourId);
            $aJTour = array(
    			'tour_id' => $aTour['id'],
    			'auto_play' => $aTour['auto_play'] ? true : false,
    			'overlay_opacity' => (float) ($aTour['overlay_opacity'] / 100),
                'overlay_cancel' => $aTour['overlay_cancel'] ? true : false,
    			'pages' => array(
    				array(
    					'url' => $aTour['path_name'],
    					'stations' => array()
    				),
    			),
    		);
            foreach($aStations as $aStation)
    		{
    			$aJTour['pages'][0]['stations'][] = array(
    				'num' => $aStation['num'],
    				'sel' => $aStation['sel'],
    				'msg' => $aStation['msg'],
    				'delay' => (int)$aStation['delay'],
    				'pos' => $aStation['position'],
    			);
    		}
            return array('tour_data' => $aJTour);
        }
        return array('err' => 1, 'msg_err'=> 'You didn\'t add any stations for the tour');
    }
    
    function getAddStation()
    {
        $sDefaultMessage = 'Please enter a message';
        $iTourId = trim($_REQUEST['tour_id']);
        $sSel = process_db_input(trim($_REQUEST['sel']));
        $iStationId = $this->_oDb->addStation($iTourId, $this->_oDb->getMaxNumByTourId($iTourId) + 1, $sSel, $sDefaultMessage, 6000, 'bc');
        if($iStationId > 0)
        {
            $aStation = $this->_oDb->getStationByStationId($iStationId);
            $sPosition = '';
            foreach($this->_oConfig->_aPositions as $sCode => $sName)
            {
                $sSel = $aStation['position'] == $sCode ? 'selected="selected"' : '';
                $sPosition .= '<option ' . $sSel .  ' value="' . $sCode . '">' . $sName . '</option>';
            }
            $aVars = array(
				'station_id' => $aStation['id'],
				'station_msg' => $aStation['msg'],
                'station_option' => $sPosition
			);
			$sNewStation = $this->_oTemplate->parseHtmlByName('unit_station_admin_ajax', $aVars);
            return array('err' => 0, 'new_station' => $sNewStation, 'value' => $aStation);
        }
    }
    
    function getDeleteStation()
    {
        $iStationId = trim($_REQUEST['station_id']);
        if($this->_oDb->deleteStationByStationId($iStationId))
            return array('err' => 0);
        return array('err' => 1);
    }
    
    function getEditStation()
    {
        $iStationId = trim($_REQUEST['station_id']);
        $sMessage = process_db_input(trim($_REQUEST['new_message']));
        $sPosition = process_db_input(trim($_REQUEST['new_position']));
        $this->_oDb->updateStationByStationId($iStationId, $sMessage, $sPosition);
        return array('err' => 0);
    }
    
    function enableTour()
    {
        $iTourId = trim($_REQUEST['tour_id']);
        if($iTourId)
        {
            $aTour = $this->_oDb->getTourById($iTourId);
            $iNewStatus = 1 - $aTour['active'];
            $this->_oDb->setActiveTour($iTourId, $iNewStatus);
            return array('img_active' => $this->_oTemplate->getImageUrl('tour_enable_' . $iNewStatus . '.png'));
        }
    }
	
    function getOrderStation()
    {
   	    $aStations = bx_get('yn_tour_station');
		$this->_oDb->updateStationOrder($aStations);
    }
    
    function getOrderStationEdit()
    {
        $aStations = bx_get('yn_t_ad_station');
		$this->_oDb->updateStationOrder($aStations);
    }
    
	function actionAdministration($sUrl = '', $iVar1 = 0)
	{
		$this->_oTemplate->pageStart();
		if(!$this->isAdmin())
		{
			$this->_oTemplate->displayAccessDenied();
			return;
		}
		$this->_oTemplate->pageStart();
		$aMenu = array(
			'manage_tour' => array(
				'title' => _t('_yn_tour_title_manage_tour'),
				'href' => $this->_sModuleUri . 'administration/manage_tour',
				'_func' => array(
					'name' => 'getAdministrationManageTour', 
                    'params' => array($iVar1)
				),
			),
            'settings' => array(
				'title' => 'settings',
				'href' => $this->_sModuleUri . 'administration/settings',
				'_func' => array(
					'name' => 'getAdministrationSettings', 
                    'params' => array()
				),
			),
		);
		if($sUrl == 'edit_station' || $sUrl == 'delete_station' || $sUrl == 'edit_tour' || $sUrl == 'delete_tour' || $sUrl == 'add_station')
			$aMenu[$sUrl] = array(
				'title' => _t('_yn_tour_title_' . $sUrl),
				'_func' => array(
					'name' => 'getAdministration' . str_replace(' ', '', ucwords(str_replace('_', ' ', $sUrl))), 'params' => array($iVar1)
				),
			);
			
		if(empty($aMenu[$sUrl]))
			$sUrl = 'manage_tour';
		$aMenu[$sUrl]['active'] = 1;
		$sContent = call_user_func_array(array(
			$this, $aMenu[$sUrl]['_func']['name']
		), $aMenu[$sUrl]['_func']['params']);
		echo $this->_oTemplate->adminBlock($sContent, _t('_yn_tour_menu_admin'), $aMenu);
		$this->_oTemplate->pageCodeAdmin(_t('_yn_tour_menu_admin'), false, false);
	}
    
    function getAdministrationSettings()
    {
        $iId = $this->_oDb->getSettingsCategory();
		if(empty($iId))
		return MsgBox(_t('_sys_request_page_not_found_cpt'));

		bx_import('BxDolAdminSettings');

		$mixedResult = '';
		if(isset($_POST['save']) && isset($_POST['cat']))
		{
			$oSettings = new BxDolAdminSettings($iId);
			$mixedResult = $oSettings->saveChanges($_POST);
		}

		$oSettings = new BxDolAdminSettings($iId);
		$sResult = $oSettings->getForm();
        
		if($mixedResult !== true && !empty($mixedResult))
		$sResult = $mixedResult . $sResult;		
		return $sResult;
    }
    
   
	
	
	function getAdministrationDeleteStation($iStationId)
	{
		$aDelStation = $this->_oDb->getStationByStationId($iStationId);
		if($aDelStation)
		{
			$this->_oDb->deleteStationByStationId($iStationId);
			$sRedirectUrl = $this->_sModuleUri . 'administration/manage_tour/' . $aDelStation['tour_id']; 
			header("Location: $sRedirectUrl");
			exit;
		}
	}
	
	function getAdministrationDeleteTour($iTourId)
	{
		$aDelTour = $this->_oDb->getTourById($iTourId);
		if($aDelTour)
		{
			$this->_oDb->deleteTourByTourId($iTourId);
            $this->_oDb->deleteStationByTourId($iTourId);
			$sRedirectUrl = $this->_sModuleUri . 'administration'; 
			header("Location: $sRedirectUrl");
			exit;
		}
	}
    
    function getAdministrationAddStation($iTourId)
    {
        $aEditTour = $this->_oDb->getTourById($iTourId);
        $sHttp = (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') ? 'https://' : 'http://';
        if($aEditTour['is_guest'] != 0)
            bx_logout();
        bx_import('BxDolSession');
        $session =  BxDolSession::getInstance();
        $session->setValue('yn_tour_admin', true);
        Redirect($sHttp . $aEditTour['path_name'], $iTourId, 'post', 'redirect');
    }
	
	
	function getAdministrationEditTour($iTourId)
	{
		$aEditTour = $this->_oDb->getTourById($iTourId);
		if($aEditTour)
			return $this->getAdministrationAddTour($aEditTour);
	}
	
	function getAdministrationAddTour($aEditTour = '')
	{
		$aForm = array(
			'form_attrs' => array(
				'name' => 'add_tour',
				'action' => '',
				'method' => 'post',
			),
			'params' => array (
				'db' => array(
					'table' => $this->_oDb->_sTableTours,
					'key' => 'id',
					'submit_name' => 'submit_form',
				),
	        ),
			'inputs' => array(
				'tour_name' => array(
					'type' => 'text',
					'name' => 'tour_name',
					'caption' => _t('_yn_tour_caption_tour_name'),
                    'info' => _t('_yn_tour_info_tour_name'),
					'required' => true,
	        		'value' => $aEditTour['tour_name'],
					'checker' => array(
						'func' => 'length',
						'params' => array(3, 100),
						'error' => 'Invalid Tour Name',
					),
					'db' => array(
						'pass' => 'Xss',
					),
				),
                'auto_play' => array(
                    'type' => 'radio_set',
                    'name' => 'auto_play',
                    'caption' => _t('_yn_tour_caption_auto_play'),
                    'required' => true,
					'value' => isset($aEditTour['auto_play']) ? $aEditTour['auto_play'] : 0,
					'values' => array(
						0 => _t('_yn_tour_text_no'),
						1 => _t('_yn_tour_text_yes'),
					),
                    'db' => array (
                        'pass' => 'Int', 
                    ),
                ),
                'overlay_opacity' => array(
                    'type' => 'text',
                    'name' => 'overlay_opacity',
                    'caption' => _t('_yn_tour_caption_overlay_opacity'),
                    'info' => _t('_yn_tour_info_overlay_opacity'),
                    'required' => true,
					'value' => isset($aEditTour['overlay_opacity']) ? $aEditTour['overlay_opacity'] : 20,
                    'db' => array (
                        'pass' => 'Int', 
                    ),
                ),
                'overlay_cancel' => array(
                    'type' => 'select',
                    'name' => 'overlay_cancel',
                    'caption' => _t('_yn_tour_caption_overlay_cancel'),
                    'info' => _t('_yn_tour_info_overlay_cancel'),
                	'value' => isset($aEditTour['overlay_cancel']) ? $aEditTour['overlay_cancel'] : 1,
                	'required' => true,
					'values' => array(
						0 => _t('_yn_tour_text_no'),
						1 => _t('_yn_tour_text_yes'),
					),
                    'db' => array (
                        'pass' => 'Int', 
                    ),
                ),
                'active' => array(
                    'type' => 'select',
                    'name' => 'active',
                    'caption' => _t('_yn_tour_caption_active'),
                	'value' => isset($aEditTour['active']) ? $aEditTour['active'] : 1,
                	'required' => true,
					'values' => array(
						0 => _t('_yn_tour_text_no'),
						1 => _t('_yn_tour_text_yes'),
					),
                    'db' => array (
                        'pass' => 'Int', 
                    ),
                ),
				'add_button' => array(
					'type' => 'submit',
					'name' => 'submit_form',
					'value' => _t('_yn_tour_button_save'),
				),
			),
		);
		$oForm = new BxTemplFormView($aForm);
		$oForm->initChecker();
		if($oForm->isSubmittedAndValid())
		{
			if(!$aEditTour)
				$aEditTour['id'] = $oForm->insert();
			else
				$oForm->update($aEditTour['id']);
			$sRedirectUrl = $this->_sModuleUri . 'administration/manage_tour/' . $aEditTour['id'];
			header("Refresh: 1; url={$sRedirectUrl}");
			return MsgBox(_t('_yn_tour_text_save_sucessfully'));
		}
		else
			return $oForm->getCode();
	}
    
    function getAdministrationEditStation($iStationId)
	{
		$aEditStation = $this->_oDb->getStationByStationId($iStationId);
        $aTour = $this->_oDb->getTourById($aEditStation['tour_id']);
		if($aEditStation)
		{
			$aForm = array(
				'form_attrs' => array(
					'name' => 'edit_tour',
					'action' => '',
					'method' => 'post',
				),
				'params' => array (
					'db' => array(
						'table' => $this->_oDb->_sTableStations,
						'key' => 'id',
						'submit_name' => 'submit_form',
					),
		        ),
		        'inputs' => $this->getInputStation($aEditStation, $aTour['auto_play'])
			);
			$oForm = new BxTemplFormView($aForm);
			$oForm->initChecker();
			if($oForm->isSubmitted())
			{
				$oForm->update($iStationId);
				$sRedirectUrl = $this->_sModuleUri . 'administration/manage_tour/' . $aEditStation['tour_id']; 
				header("Location: $sRedirectUrl");
				exit;
			}
			return $oForm->getCode();
		}
	}
	
	function getInputStation($aEditStation = '', $iAutoPlay)
	{
		return array(
			'sel' => array(
				'type' => 'text',
				'name' => 'sel', 
				'caption' => _t('_yn_tour_caption_selector'),
				'value' => $aEditStation['sel'],
                'attrs' => array(
                    'disabled' => 'disabled'
                ),
			),
			'msg' => array(
				'type' => 'text',
				'name' => 'msg', 
				'caption' => _t('_yn_tour_caption_message'),
				'value' => $aEditStation['msg'],
				'db' => array (
					'pass' => 'Xss', 
				),
			),
			'delay' => array(
				'type' => $iAutoPlay == 1 ? 'text' : 'hidden',
				'name' => 'delay', 
				'caption' => _t('_yn_tour_caption_time_delay'),
				'value' => $aEditStation['delay'] ? $aEditStation['delay'] : 6000,
                'info' => _t('_yn_tour_info_station_delay'),
				'db' => array (
					'pass' => 'Int', 
				),
			),
            'position' => array(
                'type' => 'select',
                'name' => 'position',
                'caption' => _t('_yn_tour_caption_position'),
                'value' => $aEditStation['position'] ? $aEditStation['position'] : 'TL',
                'values' => $this->_oConfig->_aPositions,
                'db' => array (
					'pass' => 'Xss', 
				),
            ),
			'add_button' => array(
				'type' => 'submit',
				'name' => 'submit_form',
				'value' => _t('_yn_tour_button_save'),
			),
		);
	}
	
	function getAdministrationManageTour($iTourId)
	{
		$aTours = $this->_oDb->getAllTours();
		if(!$aTours)
		{
			return MsgBox(_t('_yn_tour_text_you_have_no_tours'));
		}
		$aListTours = array('0' => '--' . _t('_yn_tour_text_select_a_tour') . '--');
		foreach($aTours as $aTour)
		{
			$aListTours[$aTour['id']] = $aTour['tour_name'];
		}
		$aTourForm = array(
			'inputs' => array(
				'tour_header' => array(
					'type' => 'block_header',
					'caption' => _t('_yn_tour_caption_tour_manager'),
                    'collapsable' => false,
                    'collapsed' => false
				),
				'tour_select' => array(
					'type' => 'select',
					'name' => 'tour_select',
					'caption' => _t('_yn_tour_caption_select_tour'),
					'values' => $aListTours,
					'value' => $iTourId,
					'attrs' => array(
						'onchange' => 'location.href = \'' . $this->_sModuleUri . 'administration/manage_tour/\' + ' . 'this.value',
					)
				),
			)
		);
		if($iTourId > 0)
		{
			$aTourForm['form_attrs'] = array(
				'name' => 'add_slide',
				'action' => '',
				'method' => 'post',
			);
			$aTourForm['params'] = array (
				'db' => array(
					'table' => $this->_oDb->_sTableStations,
					'key' => 'id',
					'submit_name' => 'submit_form',
				),
	        );
            $sBtEdit = _t('_yn_tour_button_edit');
            $sBtDelete = _t('_yn_tour_button_delete');
            $sBtAddStation = _t('_yn_tour_button_add_station');
	        $aButton = <<<EOF
<input type="button" onclick="location.href='{$this->_sModuleUri}administration/edit_tour/{$iTourId}'" value="{$sBtEdit}"/>
<input type="button" onclick="if(confirm('Are you sure?')) location.href='{$this->_sModuleUri}administration/delete_tour/{$iTourId}'" value="{$sBtDelete}"/>
<input type="button" onclick="location.href='{$this->_sModuleUri}administration/add_station/{$iTourId}'" value="{$sBtAddStation}"/>
EOF;
			$aSelectedTour = $this->_oDb->getTourById($iTourId);
			$aTourForm['inputs'] = array_merge($aTourForm['inputs'], 
                array(
    				'path_name' => array(
    					'type' => 'value',
    					'caption' => _t('_yn_tour_caption_page'),
    					'value' => $aSelectedTour['path_name']
    				),
    				'is_guest' => array(
    					'type' => 'value',
    					'caption' => _t('_yn_tour_caption_login_status'),
    					'value' => $aSelectedTour['is_guest'] ? _t('_yn_tour_text_guest') : _t('_yn_tour_text_member')
    				),
    				'active' => array(
    					'type' => 'value',
    					'caption' => _t('_yn_tour_caption_active'),
    					'value' => $aSelectedTour['active'] ? _t('_yn_tour_text_yes') : _t('_yn_tour_text_no')
    				),
    				'edit_delete' => array(
    					'type' => 'custom',
    					'content' => $aButton
    				)
    			)
			);
		}
		$oTourForm = new BxTemplFormView($aTourForm);
		if($oTourForm->isSubmitted())
		{
		    $oTourForm->insert(array(
                'num' => $this->_oDb->getMaxNumByTourId($iTourId) + 1,
				'tour_id' => $iTourId,
			));
			echo MsgBox(_t('_yn_tour_text_save_sucessfully'), 5);
		}
		$aListStations = $this->_oDb->getAllStationByTourId($iTourId);
		if($aListStations)
		{
			$sAllStations = '';
			foreach($aListStations as $aStation)
			{
				$aVars = array(
					'station_id' => $aStation['id'],
					'station_msg' => $aStation['msg'],
				);
				$sAllStations .= $this->_oTemplate->parseHtmlByName('unit_station_admin', $aVars);
			}
            $aBlockVars = array(
                'unit_stations' => $sAllStations,
                'plugin_url' => BX_DOL_URL_PLUGINS,
                'module_uri' => $this->_sModuleUri
            );
            $sAllStations = $this->_oTemplate->parseHtmlByName('block_station_admin', $aBlockVars);
			
			$aTourForm['inputs'] = array_merge($aTourForm['inputs'], array(
				'manage_station_header' => array(
					'type' => 'block_header',
					'caption' => _t('_yn_tour_caption_manage_station'),
	                'collapsable' => false,
	                'collapsed' => false
				),
				'all_stations' => array(
					'type' => 'custom',
					'content' => $sAllStations,
					'colspan' => true,
					'attrs_wrapper' => array(
						'style' => 'width:100%;white-space: normal;'
					)
				)
			));
		}
		
		$oTourForm = new BxTemplFormView($aTourForm);
		return $oTourForm->getCode();
	}
	
	function isGuest()
	{
		return $this->_iVisitorID > 0 ? 0 : 1;
	}
    
    function getTourData()
    {
        $sPathname = $_REQUEST['pathname'];
		$aTours = $this->_oDb->getActiveTourByPathname($sPathname, $this->isGuest(), $this->_iVisitorID);
        $bHideCheck = false;
		if($aTours)
		{
			foreach($aTours as $aTour)
			{
				$aStations = $this->_oDb->getAllStationByTourId($aTour['id']);
                if($aStations)
                {
                    if($aTour['hide'] == 1) $bHideCheck = true;
                    $aJTour[$aTour['id']] = array(
    					'tour_id' => $aTour['id'],
    					'auto_play' => $aTour['auto_play'] ? true : false,
    					'overlay_opacity' => (float) ($aTour['overlay_opacity'] / 100),
                        'overlay_cancel' => $aTour['overlay_cancel'] ? true : false,
    					'pages' => array(
    						array(
    							'url' => $aTour['path_name'],
    							'stations' => array()
    						),
    					),
    				);
                    foreach($aStations as $aStation)
    				{
    					$aJTour[$aTour['id']]['pages'][0]['stations'][] = array(
    						'num' => $aStation['num'],
    						'sel' => $aStation['sel'],
    						'msg' => $aStation['msg'],
    						'delay' => (int)$aStation['delay'],
    						'pos' => $aStation['position'],
    					);
    				}
    				$aUnitVars = array(
    					'tour_id' => $aTour['id'],
    					'tour_title' => $aTour['tour_name'],
    					'tour_img_viewed' => $this->_iVisitorID  == 0 
                            ? $this->_oTemplate->getImageUrl('guest_view.png') 
                            : ($aTour['viewed'] == 1 
                                ? $this->_oTemplate->getImageUrl('viewed.png') 
                                : $this->_oTemplate->getImageUrl('not_viewed.png')),
                        'hide_status' => $aTour['hide'] == 1 ? 'display:none' : ''
    				);
    				$sUnitTour .= $this->_oTemplate->parseHtmlByName('unit_select_tour', $aUnitVars);
                }
			}
			$aVars = array(
				'unit_tours' => $sUnitTour,
                'hide_status' => $this->_iVisitorID > 0 ? '' : 'display:none;',
                'msg_viewed_all' => MsgBox(_t('_yn_tour_text_viewed_all_tours')),
                'viewed_all_status' => ($bHideCheck == true && count($this->_oDb->getTourViewed($sPathname, $this->_iVisitorID)) == count($this->_oDb->getAllTourByPathname($sPathname, $this->isGuest()))) 
                    ? '' : 'display:none;',
                'check_status' => $bHideCheck == true ? 'checked="checked"' : '',
			);
            $sCloseImageUrl = $this->_oTemplate->getImageUrl('close.gif');
            $sPopupSelectTour = $GLOBALS['oFunctions']->transBox(DesignBoxContent('Take a Tour you want to run on', $this->_oTemplate->parseHtmlByName('popup_select_tour', $aVars), 1, '<img id="yn_tour_bt_done" class="yn_tour_bt_close_popup" src="'. $sCloseImageUrl . '">'));
			$sPopupSelect = <<<EOF
<div id="yn_tour_popup_select" class="yn_tour_popup_select"> 
{$sPopupSelectTour}
</div>
EOF;
			return array('data_tour' => $aJTour, 'popup_tour' => $sPopupSelect);
		}
        else
            return array('err' => 1, 'msg_err' => 'This page has no tours');
    }
	
	function getTour()
	{
		$sPathname = $_REQUEST['pathname'];
        $aResultObj = array('has_tour' => false);
		$aTours = $this->_oDb->getActiveTourNotHideByPathname($sPathname, $this->isGuest(), $this->_iVisitorID);
		if($aTours)
		{
			foreach($aTours as $aTour)
			{
				if($this->_oDb->getAllStationByTourId($aTour['id']))
                    $aResultObj['has_tour'] = true;
			}	
		}
        $sMainCSSUrl = BX_DOL_URL_MODULES . 'younet/tour_guide/templates/base/css/tour_guide.css';
        $sLibCssJs = <<<EOF
<link href="{$sMainCSSUrl}" rel="stylesheet" type="text/css">
EOF;
        $aResultObj['lib_css_js'] = $sLibCssJs;
        
        $aVars = array(
            'pass_admin' => '',
        );
        $bIsAdmin = $this->isAdmin();
        if($bIsAdmin || getParam('management_mode_enable') == 'on')
        {
            $aResultObj['management_mode'] = true;
            $aVars['pass_admin'] = $bIsAdmin ? getParam('management_mode_pass') : '';
            $aResultObj['bt_ad_begin'] = $this->_oTemplate->parseHtmlByName('bt_begin_admin', $aVars);
        }
        return $aResultObj;
	}
    
    function getFormStationAdmin()
    {
        $sPathname = $_REQUEST['pathname'];
        $aTours = $this->_oDb->getAllTourByPathname($sPathname, $this->isGuest());
        $sUnitTours = '';
        if($aTours)
        {
            foreach($aTours as $aTour)
            {
                $aUnitVars = array(
                    'tour_title' => $aTour['tour_name'],
                    'tour_id' => $aTour['id'],
                    'tour_img_enable' => $this->_oTemplate->getImageUrl('tour_enable_' . $aTour['active'] . '.png') 
                ); 
                $sUnitTours .= $this->_oTemplate->parseHtmlByName('admin_unit_tour', $aUnitVars);   
            }
        }
        $aVars = array();
        $sUnitTours .= $this->_oTemplate->parseHtmlByName('block_add_tour_popup_admin', $aVars);
        $sUnitTours = '<div class="yn_t_ad_f_unit_tour">' . $sUnitTours . '</div>';
        $sCloseImageUrl = $this->_oTemplate->getImageUrl('close.gif');
         
        $aVars = array(
            'unit_tours' => $GLOBALS['oFunctions']->transBox(DesignBoxContent('Manage Tour', $sUnitTours, 1, '<img id="yn_t_ad_bt_done" class="yn_t_ad_bt_close_popup" src="'. $sCloseImageUrl . '">'))
        );
        $sPopupSelectTour = $this->_oTemplate->parseHtmlByName('admin_manage_tour', $aVars);
        
        return array(
            'form_admin_manage' => $sPopupSelectTour,
        );
    }
    
    function addTour()
    {
        $sTourName = process_db_input(trim($_REQUEST['tour_name']));
        if($sTourName == '')
            return array('err' => 1, 'msg_err' => 'Please fill tour name');
        $iAutoPlay = process_db_input(trim($_REQUEST['auto_play']));
        $sPathName = process_db_input(trim($_REQUEST['path_name']));
        $iIsGuest = $this->isGuest();
        $iNewTourID = $this->_oDb->addTour($sTourName, $sPathName, $iAutoPlay, $iIsGuest);
        if($iNewTourID > 0)
        {
            $aTour = $this->_oDb->getTourById($iNewTourID);
            $sUnitTours = '';
            if($aTour)
            {  
                $aUnitVars = array(
                    'tour_title' => $aTour['tour_name'],
                    'tour_id' => $aTour['id'],
                    'tour_img_enable' => $this->_oTemplate->getImageUrl('tour_enable_' . $aTour['active'] . '.png')  
                ); 
                $sUnitTours = $this->_oTemplate->parseHtmlByName('admin_unit_tour', $aUnitVars);   
                return array('unit_tour' => $sUnitTours);
            }
        }
    }
    
    function getTourInfo()
    {
        $aStations = $this->_oDb->getAllStationByTourId(trim($_REQUEST['tour_id']));
        foreach($aStations as $aStation)
		{
            $sPosition = '';
            foreach($this->_oConfig->_aPositions as $sCode => $sName)
            {
                $sSel = $aStation['position'] == $sCode ? 'selected="selected"' : '';
                $sPosition .= '<option ' . $sSel .  ' value="' . $sCode . '">' . $sName . '</option>';
            }
			$aVars = array(
				'station_id' => $aStation['id'],
				'station_msg' => $aStation['msg'],
                'station_option' => $sPosition
			);
			$sAllStations .= $this->_oTemplate->parseHtmlByName('unit_station_admin_ajax', $aVars);
		}
        $aVars = array(
            'module_uri' => $this->_sModuleUri,
            'unit_stations' => $sAllStations
        );
        return array(
            'tour_data' => $aStations,
            'all_stations' => $this->_oTemplate->parseHtmlByName('block_station_admin_ajax', $aVars)
        );
    }
}