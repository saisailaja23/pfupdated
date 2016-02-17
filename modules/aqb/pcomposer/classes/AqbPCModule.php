<?php
/***************************************************************************
* 
*     copyright            : (C) 2009 AQB Soft
*     website              : http://www.aqbsoft.com
*      
* IMPORTANT: This is a commercial product made by AQB Soft. It cannot be modified for other than personal usage.
* The "personal usage" means the product can be installed and set up for ONE domain name ONLY. 
* To be able to use this product for another domain names you have to order another copy of this product (license).
* 
* This product cannot be redistributed for free or a fee without written permission from AQB Soft.
* 
* This notice may not be removed from the source code.
* 
***************************************************************************/

bx_import('BxDolModule');
bx_import('BxDolPageView');
bx_import('BxDolAlerts');
require_once('AqbPCPageView.php');
require_once( BX_DIRECTORY_PATH_PLUGINS . 'Services_JSON.php' );

/*
 * Alerts:
 * Alerts type/unit - 'aqb_pcomposer'
 * The following alerts are rised
 *
 *  add - user created a block
 *      $iObjectId - user id
 *      $iSenderId - block is
 *      $aExtras['Type'] - block's type
 *
 *  remove - user removed a block
 *      $iObjectId - user id
 *      $iSenderId - block id
 *
 *  edit - user edited a block 
 *      $iObjectId - user id
 *      $iSenderId - block id
 *      $aExtras['Type'] - block's type
 *
 *  approved/disapproved - a block was approved
 *      $iObjectId - owner id
 *      $iSenderId - approver id
 *
 */

 
class AqbPCModule extends BxDolModule {
	
	/**
	 * Constructor
	 */
	function AqbPCModule($aModule) {
	    parent::BxDolModule($aModule);
		$this -> _oConfig -> init($this->_oDb);
		$this -> iUserId = $GLOBALS['logged']['member'] || $GLOBALS['logged']['admin'] ? $_COOKIE['memberID'] : 0;
	}
	
	function isAdmin(){
		return isAdmin($this->iUserId);
	}
    
	function actionAdministration ($sUrl = '') {
        if (!$this->isAdmin()) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }        
		
		if ($_POST['aqb-pc-delete'] && count($_POST['blocks']) > 0) 
			foreach($_POST['blocks'] as $iId)
				$this -> _oDb -> deleteBlock((int)$iId);
		
		if ($_POST['aqb-pc-approve'] && count($_POST['blocks']) > 0) 
			foreach($_POST['blocks'] as $iId)
				if ($this -> _oDb -> approveBlock((int)$iId)) $this -> onBlockApproved($this -> iUserId, $iId);
			
        $this->_oTemplate->pageStart();

        $aMenu = array(
            'blocks' => array(
                'title' => _t('_aqb_pcomposer_block_title'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/blocks', 
                '_func' => array ('name' => 'getBlocksPanel', 'params' => array(false)),
            ),
            
			'create' => array(
                'title' => _t('_aqb_pc_create_new_block'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/create',
                '_func' => array ('name' => 'getBlockSettingsPanel', 'params' => array(0, false)),
            ),  
			
			'page' => array(
                'title' => _t('_aqb_pc_profile_page'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/page',
                '_func' => array ('name' => 'getDefaultPage', 'params' => array(false)),
            ),  			
            
			'settings' => array(
                'title' => _t('_aqb_pc_admin_settings'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/settings',
                '_func' => array ('name' => 'getSettingsPanel', 'params' => array()),
            )          
        );

        if (empty($aMenu[$sUrl]))
            $sUrl = 'blocks';

        $aMenu[$sUrl]['active'] = 1;
        
        $sContent = call_user_func_array (array($this -> _oTemplate, $aMenu[$sUrl]['_func']['name']), $aMenu[$sUrl]['_func']['params']);
	    
        echo $this->_oTemplate->adminBlock ($sContent, $aMenu[$sUrl]['title'], $aMenu);
	    
	$this->_oTemplate->addAdminCss(array('admin.css', 'forms_extra.css','forms_adv.css', 'general.css'));
        $this->_oTemplate->addAdminJs(array('admin.js','main.js', 'jquery.dolPopup.js','AqbPageBuilder.js'));
        $this->_oTemplate->pageCodeAdmin(_t('_aqb_pc_administration'));
    }
    
	function actionReorder($iPId){
		if (!(($this->isLogged() && (int)$iPId == (int)$this -> iUserId) || $this -> isAdmin())) exit;
		$this -> _oDb -> changeBlocksOrder($_REQUEST['data'], $iPId);
	}
    
	function actionApply(){
		if (!$this -> isAdmin()) exit;
		
		$aResult = array('code' => 0, 'message' => _t('_aqb_pc_structure_was_applied'));	
		if ($this -> _oDb -> applyDefaultStructure() === false) $aResult = array('code' => 1, 'message' => _t('_aqb_pc_structure_was_not_applied'));	
		
		$oJson = new Services_JSON();
	    header('Content-Type:text/javascript');
	    echo $oJson->encode($aResult);
	    exit;

	}
	
	function actionCollapse($iPId, $sID){
		if (!(($this->isLogged() && (int)$iPId == (int)$this -> iUserId) || $this -> isAdmin())) exit;
		$this -> _oDb -> changeReflecting($iPId, $sID);
	}
	
	function actionRemoveBlock($iPId, $sID){
		if (!(($this->isLogged() && (int)$iPId == (int)$this -> iUserId) || $this -> isAdmin())) exit;
		$this -> _oDb -> removeBlock($iPId, $sID);
	}
	
	function actionShare($iPId, $sID){
		if (!(($this->isLogged() && (int)$iPId == (int)$this -> iUserId)) || !$this -> _oConfig -> isShareBlocksAllowed()) exit;
		$this -> _oDb -> changeShare($iPId, $sID);
	}
	
	function actionGetCreateBlockForm($iPId){
		if (!(($this -> isLogged() && (int)$iPId == (int)$this -> iUserId) || $this -> isAdmin())) exit;
       	
		echo $this -> _oTemplate -> getCreateBlockForm($iProfileID);
        exit;
	}
	
	function actionSavePrivacy($iPId, $sID, $iGroup){
		if (!($sID && (int)$iGroup && (($this->isLogged() && (int)$iPId == (int)$this -> iUserId) || $this -> isAdmin())) || (!(int)$this -> _oConfig -> isAllowedToChangePrivacy() && !$this -> isAdmin())) exit;
				
		$aBlockId = $this -> _oDb -> getBlockIdByPost($sID);
		$iBlockId = $aBlockId['id'];
        echo $this -> _oDb -> saveCutomPrivacy($iPId, $iBlockId, $iGroup) ? 0 : 1;
    }
	
	function actionRemoveRequest($iPId, $sID){
		if (!($sID && (($this->isLogged() && (int)$iPId == (int)$this -> iUserId) || $this -> isAdmin()))) exit;
	    $aBlock = $this -> _oDb -> getBlockIdByPost($sID);
		
		$aResult = array();
		if (!$this -> _oConfig -> isAllowedToRemoveOwnBlock())  
		{
			if ($aBlock['type'] == 'c')
			{
				$aBlockInfo = $this -> _oDb -> getCustomBlockInfo($aBlock['id'],array());
				$aProfileInfo = getProfileInfo($aBlockInfo['owner']);
				$sSubject = _t('_aqb_pc_remove_request_subject', $aProfileInfo['NickName'], $aBlockInfo['Caption']);
				$sBody = _t('_aqb_pc_remove_request_body', $aBlock['id'], $aBlockInfo['Caption']);
				if (sendMail( $this -> _oConfig -> getSiteEmail(), $sSubject, $sBody)) 
				{	
					$this -> _oDb -> removeBlock($iPId, $sID);
					$aResult = array('code' => 0, 'message' => _t('_aqb_pc_remove_block_request_sent'));
				}	
			} else $aResult = array('code' => -1, 'message' => _t('_aqb_pc_remove_block_request_can_not_be_sent'));
		}
		  else
		{
		   if ($this -> _oDb -> removeBlockFromTheSite($iPId, $aBlock['id']))
		   {	
			$this -> _oDb -> removeBlock($iPId, $sID);
			$this -> onBlockRemoved ($this -> iUserId, $aBlock['id']);
			$aResult = array('code' => 1, 'message' => _t('_aqb_pc_has_been_successfully_removed_from_the_site'));
		   }
		   else $aResult = array('code' => -2, 'message' => _t('_aqb_pc_can_not_be_removed_from_the_site'));
		}		
			
		$oJson = new Services_JSON();
	    header('Content-Type:text/javascript');
	    echo $oJson->encode($aResult);
	    exit;
	}
	
	function actionGetPrivacy($iPId, $sID){
		if (!(($this->isLogged() && (int)$iPId == (int)$this -> iUserId) || $this -> isAdmin()) || (!(int)$this -> _oConfig -> isAllowedToChangePrivacy() && !$this -> isAdmin())) exit;

		$aBlockId = $this -> _oDb -> getBlockIdByPost($sID);
		$iBlockId = $aBlockId['id'];
		
		$oPrivacy = new BxDolPrivacy('sys_page_compose_privacy', 'id', 'user_id');

		$aSelect = $oPrivacy -> getGroupChooser($iPId, 'profile', 'view_block');
	    
	    $iCurGroupId = $this -> _oDb -> getCustomBlockPrivacy($iPId, $iBlockId); 
	    
		if($iCurGroupId == 0)
				$iCurGroupId = (int)$aSelect['value'];
	    
	        $aItems = array();
	        foreach($aSelect['values'] as $aValue) {
	            if($aValue['key'] == $iCurGroupId)
					$sAlt = $aValue['value'];
				$aItems[] = array(
					'block_id' => $sID,
					'group_id' => $aValue['key'],
					'class' => $aValue['key'] == $iCurGroupId ? 'dbPrivacyGroupActive' : 'dbPrivacyGroup',
					'title' => $aValue['value']
				);
	        }

	    $sCode = $this -> _oTemplate -> parseHtmlByName('privacy_items', array('bx_repeat:items' => $aItems));
	    $sCode = PopupBox('dbPrivacyMenu' . $sID, _t('_ps_bcpt_block_privacy'), $sCode);
			
		$oJson = new Services_JSON();
	    header('Content-Type:text/javascript');
	    echo $oJson->encode(array(
	        'code' => !empty($sCode) ? 0 : 1,
	        'data' => $sCode,
	    ));
	    exit;
    }
	
	function actionSaveBlock($iPId, $iId = 0){
		if (!(($this->isLogged() && (int)$iPId == (int)$this -> iUserId) || $this -> isAdmin())) exit;
		
		$aResult = array('code' => 0, 'message' => _t('_aqb_pc_block_saved'));		
		if (isset($_POST['block_id']) && (int)$_POST['block_id']) {
			$aBlockInfo = $this -> _oDb -> getBlockInfo((int)$_POST['block_id']);
			if ((int)$aBlockInfo['owner_id'] != (int)$this -> iUserId) $aResult = array('code' => 5, 'message' => _t('_aqb_block_was_not_saved'));
			$mixResult = $this -> _oDb -> createCustomBlock($iPId, $_POST, (int)$_POST['block_id']);
			
			$iResult = (int)$_POST['block_id']; 
			if ($mixResult === false) {
				$aResult = array('code' => 2, 'message' => _t('_aqb_block_was_not_saved'));
			    $iResult = 0;
			}
		}elseif ($this -> _oDb -> checkIfMembersBlocksLimitExceed($iPId)) $aResult = array('code' => 1, 'message' => _t('_aqb_pc_blocks_limit_exceed'));
		 elseif (!($iResult = $this -> _oDb -> createCustomBlock($iPId, $_POST))) $aResult = array('code' => 2, 'message' => _t('_aqb_block_was_not_saved'));
		$aResult['id'] = $iResult;
		
	//	if ((int)$iResult && !call_user_func(array($this -> _oConfig, 'isAutoApprove' . strtoupper($_POST['type']) . 'Block'))) 
   // Section: agency moderation start
                $AgencyID = getProfileInfo($iPId);//$aProfileInfo['AdoptionAgency'];

                if(@mysql_num_rows(mysql_query("SELECT id FROM bx_groups_main WHERE author_id=".$iPId))){
                              $mFlag=true;
                          }else{
                               if(@mysql_num_rows(mysql_query("SELECT GroupId FROM bx_groups_moderation WHERE GroupId =".$AgencyID['AdoptionAgency']."  AND ApproveStatus= 'on' AND Type = 'sections'"))){
                                $mFlag=true;
                            }else{
                                $mFlag=false;
                            }
                          }
                
                // Scetion: agency moderation end
		//if ((int)$iResult && (!call_user_func(array($this -> _oConfig, 'isAutoApprove' . strtoupper($_POST['type']) . 'Block'))&& !$mFlag)) 

		if ((int)$iResult &&(!$mFlag)) 
		{
			if ((int)$_POST['block_id']) 
			{	
				$this -> _oDb -> removeBlock($iPId, $this -> _oConfig -> getCBPrefix().$_POST['block_id']);	
				$this -> onBlockEdit($this -> iUserId, $_POST['block_id']);
			}else 
			{
				$this -> onBlockCreate($this -> iUserId, $iResult, $_POST['type']);		
			}
			
			$aResult = array('code' => 3, 'message' => _t('_aqb_pc_block_has_to_be_approved'));
		}	
		 
	
		header('Content-Type:text/javascript');
        $oJson = new Services_JSON();
        echo $oJson->encode($aResult);
		exit;
	}
	
	function actionGetBlock($iBlockId, $sType = 'c'){
	    if (!(($this->isLogged()) || $this -> isAdmin())) exit;
		
		$aBlockInfo = $this -> _oDb -> getBlockForView($this -> iUserId, $iBlockId, $sType);
		$oBlockBuilder = new AqbPCPageView('profile');
		$oBlockBuilder -> oProfileGen -> _iProfileID = $this -> iUserId;
		$oBlockBuilder -> sCode = '';
		$oBlockBuilder -> genBlock($aBlockInfo['id'], $aBlockInfo['params']);
		
		header('Content-Type:text/html;charset=UTF-8');
		echo $oBlockBuilder -> sCode; 
		exit;
	}
	
	function actionCheckBlockEdit($sId){
	   if (!$this -> _oConfig -> isAllowedToEditBlock()) $aResult = array('code' => -1, 'message' => _t('_aqb_pc_sorry_edit_not_allowed'));
	   
	   $aBlockInfo = array();
	   if ($sId) {
			$aB = $this -> _oDb -> getBlockIdByPost($sId); 
			$aBlockInfo = $this -> _oDb -> getBlockInfo($aB['id']);
			if ((int)$aBlockInfo['owner_id'] != (int)$this -> iUserId) $aResult = array('code' => -1, 'message' => _t('_aqb_pc_sorry_edit_not_allowed'));
		}	
	   
	   $bAllowed = call_user_func(array($this -> _oConfig, 'isAutoApprove' . strtoupper($aBlockInfo['type']) . 'Block'));
	   if (!$bAllowed) $aResult = array('code' => -1, 'message' => _t('_aqb_pc_block_has_to_be_approved'));
		
		
	   $aResult = array('code' => 0, 'type' => $aBlockInfo['type'], 'autoapprove' => ($bAllowed ? 1 : 0), 'message' => (!$bAllowed ? _t('_aqb_pc_block_has_to_be_approved') : ''));
	   
	   header('Content-Type:text/javascript');
       $oJson = new Services_JSON();
       echo $oJson->encode($aResult);
	   exit;
	}
	
	function actionEditBlock($sId){
	    if (!$this->isLogged()) exit;
    	
		$aBlockInfo = array(); 
		
		if ($sId) {
			$aB = $this -> _oDb -> getBlockIdByPost($sId); 
			$aBlockInfo = $this -> _oDb -> getBlockInfo($aB['id']);
			if ((int)$aBlockInfo['owner_id'] != (int)$this -> iUserId) exit;
		}	
	    
		header('Content-Type:text/html;charset=UTF-8');
		echo $this -> _oTemplate -> getCreateBlockForm($this -> iUserId, $aBlockInfo);
		exit;
	}
	
	function actionAddBlockToProfile($sBlockId){
	    if (!(($this->isLogged()) || $this -> isAdmin())) exit;
	    $aBlock = $this -> _oDb -> getBlockIdByPost($sBlockId);
		$aResult = array('code' => 1, 'message' => _t('_aqb_pc_was_not_added_to_profile'));
		if ($this -> _oDb -> addBlockToProfile($this -> iUserId, $aBlock['id'], $aBlock['type'])) $aResult = array('code' => 0, 'message' => _t('_aqb_pc_was_added_to_profile'));
		
		header('Content-Type:text/javascript');
        $oJson = new Services_JSON();
        echo $oJson->encode($aResult);
		exit;
	}
	
 function actionApproveBlocks($sAction = 'disapproved', $iPage = 1){
  define('BX_ProfileBlocks_PAGE', 1);
		if (!$this->isLogged()) {
			$this -> _oTemplate-> pageStart();
            echo MsgBox(_t('_aqb_pc_have_to_login'));
			$this -> _oTemplate -> pageCode(_t('_aqb_pc_approve_blocks'), false, false);
			exit;
        }
		
		if (!$this -> _oDb -> isGroupAdmin($this->iUserId)) {
			$this -> _oTemplate-> pageStart();
            echo MsgBox(_t('_aqb_pc_you_are_not_groups_owner'));
			$this -> _oTemplate -> pageCode(_t('_aqb_pc_approve_blocks'), false, false);
			exit;
        }

   		if ($_POST['aqb-pc-delete'] && count($_POST['blocks']) > 0) 
			foreach($_POST['blocks'] as $iId)
				$this -> _oDb -> deleteBlock((int)$iId);
		
		if ($_POST['aqb-pc-approve'] && count($_POST['blocks']) > 0) 
			foreach($_POST['blocks'] as $iId) $this -> _oDb -> approveBlock((int)$iId);

				
		if (!$this -> _oConfig -> isPermalinkEnabled()){
			
			$sAction = $_REQUEST['type'] ? $_REQUEST['type'] : $sAction ;
			$iPage = (int)$_REQUEST['page'];
		}
		
        $this -> _oTemplate-> pageStart();
	
		echo $this -> _oTemplate -> getManageBlocks($sAction, $this->iUserId, (int)$iPage);

        $this -> _oTemplate -> addCss(array('admin.css','main.css'));
        $this -> _oTemplate -> addJs('main.js');
        $this -> _oTemplate -> pageCode(_t('_aqb_pc_approve_blocks'), false, false);
    }
    function actionProfileBlocks($sAction = 'standard', $iPage = 1){
			  define('BX_ProfileBlocks_PAGE', 1);
    	if (!$this->isLogged()) {
			$this -> _oTemplate-> pageStart();
            echo MsgBox(_t('_aqb_pc_have_to_login'));
			$this -> _oTemplate -> pageCode(_t('_aqb_pc_profile_blocks'), false, false);
			exit;
        }
                
		if (!$this -> _oConfig -> isPermalinkEnabled()){
			
			$sAction = $_REQUEST['type'] ? $_REQUEST['type'] : $sAction ;
			$iPage = (int)$_REQUEST['page'];
		}
		
		$this -> _oDb -> checkForExisting($this->iUserId);		
        $this -> _oTemplate-> pageStart();
	
		echo $this -> _oTemplate -> getBlocksPage($sAction, $this->iUserId, (int)$iPage);

        $this -> _oTemplate -> addCss('main.css');
        $this -> _oTemplate -> addJs('main.js');
        $this -> _oTemplate -> pageCode(_t('_aqb_pc_profile_blocks'), false, false);
    }
	
	function actionBlocks(){
		if (!$this->isAdmin()) {
            return '';
        }
   
		echo $this->_oTemplate-> getBlocksTable($_REQUEST);
		exit;	
	}

	function actionPreviewBlockForm($iBlockId){
	//	if (!$this->isAdmin() || !(int)$iBlockId) {		  
if (!(($this->isAdmin() || $this -> _oDb -> isGroupAdmin($this->iUserId)) && (int)$iBlockId)) {
            return '';
        }

		echo $this -> _oTemplate -> getBlockPreview($iBlockId);
        exit;
	}
	
	function actionEditBlockForm($sBlockId, $iPos = 0){
		if (!$this->isAdmin()) exit;
		
		header('Content-Type:text/html;charset=UTF-8');	
		$aBlockInfo = $this -> _oDb -> getBlockIdByPost($sBlockId);
		echo $this -> _oTemplate -> getBlockSettingsPanel($aBlockInfo['id'],true, $aBlockInfo['type'], $iPos);
        exit;
	}
	
	//-------- services 
	
	function serviceCreateFirst(){
		$this -> _oDb -> withPointsIntegration();
		return $this -> _oDb -> checkForExisting(0);
	}

	function serviceUninstallIntegration(){
		$this -> _oDb -> uninstallPointsIntegration();
	}

	function serviceIsCreateBlocksAllowed(){
	//	return $this -> _oConfig -> isCreateBlocksAllowed() && (strpos($_SERVER['PHP_SELF'],'profile.php') !== false);
	return $this -> _oConfig -> isCreateBlocksAllowed();
    }
	
	// ================================== alerts

    function onBlockCreate ($iUserId, $iBlockId, $sType = 'html') {
     	$oAlert = new BxDolAlerts('aqb_pcomposer', 'add', $iUserId, $iBlockId, array('Type' => $sType));
		$oAlert->alert();
    }

    function onBlockEdit ($iUserId, $iBlockId) {
		$oAlert = new BxDolAlerts('aqb_pcomposer', 'edit', $iUserId, $iBlockId);
		$oAlert->alert();
    }   

	function onBlockApproved ($iApproverId, $iBlockId) {
		$aData = $this -> _oDb -> getBlockInfo($iBlockId);
		
		if ((int)$aData['approved'])
			$oAlert = new BxDolAlerts('aqb_pcomposer', 'approved', (int)$aData['owner_id'], $iApproverId);
		else
			$oAlert = new BxDolAlerts('aqb_pcomposer', 'disapproved', (int)$aData['owner_id'], $iApproverId);
			
		$oAlert->alert();
    }   	

    function onBlockRemoved ($iUserId, $iBlockId) {
		$oAlert = new BxDolAlerts('aqb_pcomposer', 'remove', $iUserId, $iBlockId);
		$oAlert->alert();
    }        
	
	
}
?>