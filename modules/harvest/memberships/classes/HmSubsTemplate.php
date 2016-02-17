<?
/***************************************************************************
Dolphin Subscriptions v.2.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/
bx_import ('BxDolTwigTemplate');
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_PLUGINS . 'Services_JSON.php' );
bx_import('BxTemplSearchResult');
bx_import('BxTemplBrowse');
bx_import('BxTemplTags');
bx_import('BxTemplFunctions');
bx_import('BxDolAlerts');
bx_import('BxDolEmailTemplates');

class HmSubsTemplate extends BxDolTwigTemplate {
    
	function HmSubsTemplate(&$oConfig, &$oDb) {
	    parent::BxDolTwigTemplate($oConfig, $oDb);
		$this->postSaveProc = BX_DOL_URL_ROOT.'m/memberships/post_save_processor/';
		$this->sSettingsUrl = BX_DOL_URL_ROOT.'m/memberships/settings/';
		$this->sBase 		= BX_DOL_URL_ROOT.'m/memberships/';
		$this->aProcessors = $this->_oConfig->getPaymentProcessors();
		$sBase = BX_DOL_URL_ROOT; 
		$this->sSecureBase = str_replace('http://', 'https://', $sBase);
		$this->_sIconsFolder = 'harvest/memberships/images/icons/';
		if($this->_oDb->getSetting('an_ssl') == '1'){
			$this->iconsUrl = $this->sSecureBase . 'modules/'. $this->_sIconsFolder;
	  	}else{
		 	$this->iconsUrl = BX_DOL_URL_MODULES . $this->_sIconsFolder;
		}
    }

	function addAdminCss ($sName) 
    {
     	parent::addAdminCss($sName);
    }
	
    function parseHtmlByName ($sName, $aVars) {
     	return parent::parseHtmlByName ($sName, $aVars);
    }

	function availMemLevels(){
		$iId = getLoggedId();
		if (isset($_POST['free_form']) && $_POST['join_free_mem'] == 1) {
			$this->_oDb->setFreeMembership($iId,$_POST['IDLevel']);	
			$this->_oDb->setUserStatus($iId,'Active');	
			$this->_oDb->clearCache('user', BX_DIRECTORY_PATH_CACHE);
			Redirect($this->sBase,array('joined_free' => 'success'),post);
		}
		$sUrl		 = BX_DOL_URL_ROOT;
		$sUrlModules = BX_DOL_URL_MODULES;		
		$aLevels = $this->_oDb->getMemberships();
		$aLastItem = end($aLevels);

		if(is_array($aLevels)){
			foreach($aLevels as $aLevel){
				
				$sCurSign		= getParam('currency_sign');
				$iMembId 		= $aLevel['ID'];
				$aPriceInfo		= $this->_oDb->getMembershipPriceInfo($iMembId);				
				if($aPriceInfo['Length'] > '1'){
					$sPriceUnit	= ($aPriceInfo['Unit'] == 'Days') ? _t('_dol_subs_days') : _t('_dol_subs_months');
				}else{
					$sPriceUnit	= ($aPriceInfo['Unit'] == 'Days') ? _t('_dol_subs_day') : _t('_dol_subs_month');
				}
				$aSettings 		= $this->_oDb->getSettings();
				$iDefaultMembId = $aSettings['default_memID'];
				$aExcludeMembs 	= array(1,2,3,$iDefaultMembId);

				$iUserAcl = $this->_oDb->userAcl($iId);
				$iAclMembId = $this->_oDb->getMembershipLevelId($iId);
				$aAclPriceInfo	= $this->_oDb->getMembershipPriceInfo($iAclMembId);
				$iDowngrade = (int)$this->_oDb->getSetting('disable_downgrade');
		 		$iUpgrade = (int)$this->_oDb->getSetting('disable_upgrade');
				if($iUserAcl == '1' && $iDowngrade == '1'){
					if($aAclPriceInfo['Price'] > $aPriceInfo['Price']){
						$aExcludeMembs[] = $iMembId;// Add downgrade jQuery
					}
				}
				if($iUserAcl == '1' && $iUpgrade == '1'){
					if($aAclPriceInfo['Price'] < $aPriceInfo['Price']){
						$aExcludeMembs[] = $iMembId;// Add upgrade jQuery
					}
				}

				$sTrialText		= ' - '.$aSettings['trial_length'].' Day Trial';
				$sTrial 		= ($aSettings['trial'] == '1' && $aSettings['payment_proc'] != 'authorize') ? $sTrialText : '';
				if (!in_array($iMembId, $aExcludeMembs) && $aLevel['Active'] != 'no' && $aLevel['Purchasable'] == 'yes') {

					if ($aLevel['Free'] == '1'){ 
						$aVars = array(
							'mlevel_id' => $iMembId,
						);
						$sFormCode = $this->parseHtmlByName('free_form',$aVars);			
					}else{ 
						// Use alertpay 
						if($aSettings['payment_proc'] == 'alertpay'){
							classImport('AlertPay');
							$oAlertPay = new HmSubsAlertPay;
							$sFormCode = $oAlertPay->showForm($iMembId);
						}
						// Use Authorize.net 
						if($aSettings['payment_proc'] == 'authorize'){ 
							classImport('AuthorizeNet');
							$oAuthorizeNet = new HmSubsAuthorizeNet;
							$sFormCode = $oAuthorizeNet->showForm($iMembId);
    					}
    
						// Use Paypal 
						if($aSettings['payment_proc'] == 'paypal'){
							classImport('PayPal');
							$oPayPal = new HmSubsPayPal;
							$sFormCode = $oPayPal->showForm($iMembId);
						}

						// Use MoneyBookers
						if($aSettings['payment_proc'] == 'moneybookers'){
							classImport('MoneyBookers');
							$oMoneyBookers = new HmSubsMoneyBookers;
							$sFormCode = $oMoneyBookers->showForm($iMembId);
						}				
				
						if(empty($aSettings['payment_proc']))
							$sFormCode = "<h5 style='font-family:arial;background-color:#FBAF34;padding:5px;margin:0px;'>Payment processor not configured</h5>";
					}
					$aItems[] = array(
						'explain_url' => BX_DOL_URL_ROOT.'explanation.php?explain=membership&amp;type='.$aLevel['ID'],
						'mlevel_id' => $aLevel['ID'],
						'mlevel_icon' => $this->iconsUrl.$aLevel['Icon'],
						'mlevel_desc' => $aLevel['Description'],		
						'mlevel_name' => $aLevel['Name'],
						'mlevel_price' => $sPrice = ($aLevel['Free'] == '1') ? 'Free' : $sCurSign.number_format($aPriceInfo['Price'], 2),
						'mlevel_length' => $aPriceInfo['Length'].' '.$sPriceUnit,
						'form_code' => $sFormCode,
						'bx_if:last' => array(
		                    'condition' => ($aLevel['ID'] == $aLastItem['ID']),
		                    'content' => array(
		                        'last_tr' => 'last_tr'
		                    ),
		                ),
					
					);				
				}
			}
		}
		$aVars = array(
			'bx_repeat:memberships' => $aItems,
		);
		return $this->parseHtmlByName('avail_memberships',$aVars);	
	}
	/*--- Main Admin Methods
	*************************************************************************************************************************************************************/
    function paymentProcessorSettings(){
        if($_POST['save_proc']){
			$sCode = msgBox('Settings Successfully Saved',2);
			$this->_oDb->updatePaymentProcessor($_POST);
		}

		$sProc = $this->_oDb->getSetting('payment_proc');
		$aForm = $this->getProcessorForm($sProc);

		$aVars = array(
			'settings_form' => $aForm,
			'callback_url' => $this->sBase.'callback',
			'message' => $sCode
		);    		
    	return DesignBoxContent('Payment Processor', $this->parseHtmlByName('admin_proc_settings',$aVars),1);	
    }
	function dataForwardSettings(){
        if($_POST['save_data_forward']){
			$sCode = msgBox('Settings Successfully Saved',2);
			$this->_oDb->updateDataForwarding($_POST);
		}

		$sProc = $this->_oDb->getSetting('payment_proc');
		$aForm = $this->dataForwardForm();

		$aVars = array(
			'data_forward_form' => $aForm,
			'callback_url' => $this->sBase.'callback',
			'message' => $sCode
		);    		
    	return DesignBoxContent('Data Forwarding', $this->parseHtmlByName('admin_data_forward',$aVars),1);		

	}
    function userManagementSettings(){
       	if($_POST['save_user_settings']){
			$sMsg = MsgBox('Settings Successfully Saved',2);
			$this->_oDb->updateUserManagementSettings($_POST);
			
		}
		$aInputs = $this->getUserManagementInputs();
      	$aForm = array(
            'form_attrs' => array(
                'name'     => 'user_settings', 
                'method'   => 'post',
                'action'   => NULL,
            ),
			'inputs' => $aInputs,       
		);
	
        $oForm = new BxTemplFormView ($aForm);
        $oForm->initChecker();
		$sCode = $oForm->getCode();


		$aVars = array(
			'user_settings_form' => $sCode,
			'message' => $sMsg
		);    		
    	return DesignBoxContent('User Management', $this->parseHtmlByName('admin_user_settings',$aVars),1);	
    }	
	function adminCurrentMemberships(){
		if($_GET['adm_mlevels_activate'] && $_GET['membership']){
			$this->_oDb->activateMembershipLevel($_GET['membership']);
			Redirect($this->sBase.'memberships/');
		}
		if($_GET['adm_mlevels_deactivate'] && $_GET['membership']){
			$this->_oDb->deactivateMembershipLevel($_GET['membership']);
			Redirect($this->sBase.'memberships/');
		}
		if($_GET['adm_mlevels_delete'] && $_GET['membership']){
			if(in_array($_GET['membership'],array(1,2,3))){
				$sAlert = "alert('Cannot delete Membership Level');";
			}else{
				$this->_oDb->deleteMembershipLevel($_GET['membership']);
				Redirect($this->sBase.'memberships/');
			}
		}
		$aLevel = $this->_oDb->getMembershipById($_GET['membership']);

		if($_GET['adm_mlevels_edit']){
			$aVars = array('mem_url' => $this->sBase.'memberships');
			$sEditBoxes.= $this->parseHtmlByName('back_button',$aVars);			
			if($_GET['membership'] != '2'){
	    		$sEditBoxes.= DesignBoxAdmin('Settings for "'.$aLevel['Name'].'" Membership', $this->editMembership($_GET['membership']));
			}
	    	$sEditBoxes.= DesignBoxAdmin('Actions for "'.$aLevel['Name'].'" Membership', $this->editMembershipActions($_GET['membership']));
			$sEditBoxes.= $this->parseHtmlByName('back_button',$aVars);
			return $sEditBoxes;
		}

	    $aItems = array();
		$i=0;
        $aColors = array('light', 'dark');
		$aExcludeMembs 	= array(1,3);
		$aLevels = $this->_oDb->getMembershipsAdmin();
		$sCurSign = getParam('currency_sign');
	    foreach($aLevels as $aLevel){
			$aData = $this->_oDb->getMembershipPriceInfo($aLevel['ID']);
			if (!in_array($aLevel['ID'], $aExcludeMembs)){
		        $aItems[] = array(
		            'ID' 			=> $aLevel['ID'],
		            'Name' 			=> $aLevel['Name'],
		            'Icon' 			=> $this->iconsUrl . $aLevel['Icon'],
		            'Description' 	=> $aLevel['Description'],
		            'Active' 		=> $sActive = ($aLevel['Active'] == 'yes') ? 'Active' : 'Not-Active',
		            'Free' 			=> $aLevel['Free'],
					'tr_class'		=> $aColors[$i++ % 2],
					'Price'			=> $sPrice = (!$aData['Price']) ? 'Free' : $sCurSign.number_format($aData['Price'], 2),
					'Length'		=> $aData['Length'].' '.$aData['Unit'],
					'Trial'			=> $sTrial = ($aLevel['Trial']=='1') ? 'Yes' : 'No',
					'Auto'			=> $sAuto = ($aData['Auto'] == '1') ? 'Auto' : 'One-Time',
					'Display'		=> $sRemovable = ($aLevel['Removable'] == 'yes') ? 'block' : 'none' 
		        );
			}
   		}
	    $aButtons = array(
	        'adm_mlevels_edit' 			=> 'Edit Membership/Actions',
	        'adm_mlevels_activate' 		=> 'Activate',
	        'adm_mlevels_deactivate' 	=> 'Deactivate',
	        'adm_mlevels_delete' 		=> 'Delete'
	    );
		$sControls = BxTemplSearchResult::showAdminActionsPanel('mem_levels', $aButtons, 'membership', false,false); 

		$aVars = array(
			'bx_repeat:items' => $aItems,
			'controls' => $sControls,
			'alert' => $sAlert
	    );
	    $sResult = $this->parseHtmlByName('admin_mem_setup', $aVars);	 
		   
	    return DesignBoxAdmin('Membership Levels', $sResult);
	}
	function adminCreateMembership(){	
	    $aForm = array(
	        'form_attrs' => array(
	            'id' => 'create_mlevel',
	            'action' => NULL,
	            'method' => 'post',
	            'enctype' => 'multipart/form-data',
	        ),
	        'params' => array (
	            'db' => array(
	                'table' => 'sys_acl_levels',
	                'key' => 'ID',
	                'uri' => '',
	                'uri_title' => '',
	                'submit_name' => 'create_mlevel'
	            ),
	        ),
			'inputs' => $this->getCreateMembershipInputs(),
	    );
	    $oForm = new BxTemplFormView($aForm);
	    $oForm->initChecker();	
	    $bFile = true;
	    $sFilePath = $this->_oConfig->getIconsPath();
	    $sFileName = time();
	    $sFileExt = '';    

	    if($oForm->isSubmittedAndValid()) {
			if($this->isImage($_FILES['Icon']['type'], $sFileExt) && !empty($_FILES['Icon']['tmp_name'])){
				move_uploaded_file($_FILES['Icon']['tmp_name'],  $sFilePath . $sFileName . '.' . $sFileExt);
			}else if(!$this->isImage($_FILES['Icon']['type'], $sFileExt) && !empty($_FILES['Icon']['tmp_name'])){
	    		$oForm->aInputs['Icon']['error'] = $oForm->aInputs['Icon']['checker']['error'];
			}
			$sPath = $sFilePath . $sFileName . '.' . $sFileExt;
		    imageResize($sPath, $sPath, 110, 110);
			$sIcon = $sFileName . '.' . $sFileExt;
			$this->_oDb->createMembershipLevel($_POST,$sPath); 
		   	Redirect($this->sBase.'memberships/');  
	    } else {
			$sCode = $oForm->getCode(); 		
	        return DesignBoxAdmin('Create Membership Level', $sCode);
	    }


	}
	function subscribersMainCode(){

		if($_POST['adm_subs_del'] && is_array($_POST['subscribers'])){
			$this->_oDb->removeSubscribers($_POST['subscribers']);
			$sMsg = MsgBox('Successfully Removed',2);			
		}
	
		$aSubs = $this->_oDb->getSubscriptions();// Input optional filter arg to retrieve subs
		$sMsg = (count($aSubs) > '0') ?  '' : MsgBox('No Active Subscriptions');


		if($_POST['adm_subs_del'] && !$_POST['subscribers'])
			$sMsg = MsgBox('Nothing Selected');

		$iStart = ($_GET['start']) ? $_GET['start'] : 0;
		$iPerPage = ($_GET['per_page']) ? $_GET['per_page'] : 10;
		$sDateFormat = getLocaleFormat(BX_DOL_LOCALE_DATE, BX_DOL_LOCALE_DB);
		$iCount = count($aSubs);
		$iCounter = '0';
		$aRange = range($iStart,$iStart+($iPerPage-1));
		foreach($aSubs as $aSub){			

			if(!in_array($iCounter++,$aRange)) continue;	

			$sDateStartFixed = $this->getTimeStamp($aSub['DateStarts']);
			$sDateExpFixed = $this->getTimeStamp($aSub['DateExpires']);
			$sEditLink = BX_DOL_URL_ROOT.'pedit.php?ID='.$aSub['ID'];			

		   	$aItems[] = array(
		      	'id' 			=> $aSub['ID'],
		        'nickname' 		=> $aSub['NickName'],
		        'email' 		=> $this->trimString($aSub['Email'],20),
		        'status' 		=> $aSub['Status'],
		        'date_start' 	=> $sDateStart = $sDateStartFixed ? date('m-d-Y',$sDateStartFixed) : 'N/A',
		        'date_exp' 		=> $sDateExp = $sDateExpFixed ? date('m-d-Y',$sDateExpFixed) : 'N/A',
		        'txn_id' 		=> $sSubId = (empty($aSub['TransactionID']) || $aSub['TransactionID'] == 'NULL') ? 'One-Time' : $aSub['TransactionID'],
		        'mem_name' 		=> $sMemName = $this->trimString($aSub['Name'],20),
				'edit_link'     => $sEditLink,
				'action_url'    => $this->sBase.'process_controls',
		    );
		}

	    $oPaginate = new BxDolPaginate(array(
	      	'start' => $iStart,
			'count' => $iCount,
			'per_page' => $iPerPage,
			'sorting'    => 'last',
			'per_page_changer'   => true,
			'page_reloader'      => true,
		    'page_url' => $this->sBase.'subscriptions/?start={start}&per_page={per_page}',
	    ));
	    $sPaginate = $oPaginate->getPaginate();   

		$aButtons = array(
	        'adm_subs_del' 		=> 'Delete'
	    ); 
		$sControls = BxTemplSearchResult::showAdminActionsPanel('subscribers_form', $aButtons, 'subscribers', true ,false); 

		$aResult = array(
			'bx_repeat:items' => $aItems,
			'paginate' => $sPaginate,
			'controls' => $sControls,
			'message' => $sMsg
		);
	    return DesignBoxAdmin('Subscribed Members', $this->parseHtmlByName('admin_subs', $aResult));
	}

	function menuItemControl(){
		$rTopItems = $this->_oDb->getTopMenuItems();
		$rSysItems = $this->_oDb->getSysMenuItems();
		$aTopItems = array();
		$aCustomItems = array();
		$aSystemItems = array();
		while( $aTopItem = mysql_fetch_assoc( $rTopItems ) ) {
			$aTopItems[$aTopItem['ID']] = $aTopItem['Name'];
			$aCustomItems[$aTopItem['ID']] = array();
			$sQuery = "SELECT `ID`, `Name` FROM `sys_menu_top` WHERE `Active`=1 AND `Type`='custom' AND `Parent`={$aTopItem['ID']} ORDER BY `Order`";

			$rCustomItems = db_res( $sQuery );
			while( $aCustomItem = mysql_fetch_assoc( $rCustomItems ) ) {
				$aCustomItems[$aTopItem['ID']][$aCustomItem['ID']] = $aCustomItem['Name'];
			}
		}
		while( $aSystemItem = mysql_fetch_assoc( $rSysItems ) ) {
			$aSystemItems[$aSystemItem['ID']] = $aSystemItem['Name'];
			$aCustomItems[$aSystemItem['ID']] = array();
			$sQuery = "SELECT `ID`, `Name` FROM `sys_menu_top` WHERE `Active`=1 AND `Type`='custom' AND `Parent`={$aSystemItem['ID']} ORDER BY `Order`";
			$rCustomItems = db_res( $sQuery );
			while( $aCustomItem = mysql_fetch_assoc( $rCustomItems ) ) {
				$aCustomItems[$aSystemItem['ID']][$aCustomItem['ID']] = $aCustomItem['Name'];
			}
		}

		$sCode = '<table><tr>';
		foreach ($aTopItems as $iItemID => $sItemName) {
			$sCode .= '<td valign="top">';
			$sCode .= "<div class=\"top_menu_item\"><a href=\"javascript:showMenuAccess('m/memberships/main_menu&action=edit&menu_id=".$iItemID."')\">".$sItemName."</a></div>";
			foreach ($aCustomItems[$iItemID] as $iCustomItemID => $sCustomItemName) {
				$sCode .= "<div class=\"custom_menu_item\"><a href=\"javascript:showMenuAccess('m/memberships/main_menu&action=edit&menu_id=".$iCustomItemID."')\">".$sCustomItemName."</a></div>";
			}
			$sCode .= '</td>';
		}

		foreach ($aSystemItems as $iItemID => $sItemName) {
			$sCode .= '<td valign="top">';
			$sCode .= '<div class="system_menu_item">'.$sItemName.'</div>';
			foreach ($aCustomItems[$iItemID] as $iCustomItemID => $sCustomItemName) {
				$sCode .= "<div class=\"custom_menu_item\"><a href=\"javascript:showMenuAccess('m/memberships/main_menu&action=edit&menu_id=".$iCustomItemID."')\">".$sCustomItemName."</a></div>";
			}
			$sCode .= '</td>';
		}
		$sCode .= '</tr></table>';
		$sCode .= '<script type="text/javascript">'.
				"$(document).ready(function(){".
					"var width = $('table.adm-middle').width();".
					"$('body').css('width',width);".
				"});".
				'</script>';

		return DesignBoxAdmin('Main Menu Control', $sCode);
	}
	function urlControl(){
		
		if(bx_get('add_new_rule')){
			if(empty($_POST['mlevels'])){
				$sAddCode = MsgBox('Please choose a membership level',3);
			}else{
				$this->_oDb->addNewURLRule($_POST);
				$sAddCode = MsgBox('Rule Successfully Added',3);
			}
		}
		$sAddCode.= $this->getAddUrlRuleForm();

		$sRulesCode = $this->displayUrlRules();

		$sContent = DesignBoxAdmin('Add new URL restriction rule', $sAddCode);

		$sContent.= DesignBoxAdmin('Current Restriction Rules', $sRulesCode);
		
		return $sContent;		
	}

	/*--- Sub Admin Methods
	--------------------------------------------------------------------------------------------------------------------------*/
	function getAddUrlRuleForm(){
		$aMemLevels = $this->_oDb->getMembershipsAdmin();		
		foreach ($aMemLevels as $aMemLevel) {			
			if (in_array($aMemLevel['ID'],array(3))) continue;
	        $aLevels[$aMemLevel['ID']] = $aMemLevel['Name'];
		}
		$aVars = array(
			'bx_repeat:mlevels' => $aItems,
			'action_url' => $this->sBase.'main_menu',
			'menu_item_id' => $iMenuItemID,
			'menu_item_name' => $sMenuItemName,
		);
      	$aForm = array(
            'form_attrs' => array(
                'name'     => 'add_url_rule', 
                'method'   => 'post',
                'action'   => NULL,
            ),
			'inputs' => array(
				'levels_header_Beg' => array(
                    'type' => 'block_header',
                    'caption' => 'Choose membership levels rule will apply to:',
                    'collapsable' => false,
                    'collapsed' => false
				),
				'mem_levels' => array(
				    'type' => 'checkbox_set',
				    'name' => 'mlevels',
				    'values' => $aLevels,
				    'value' => ''
				),
				'levels_header_End' => array(
                        'type' => 'block_end'
                ),
				'rules_setting_Beg' => array(
                    'type' => 'block_header',
                    'caption' => 'Enter url to restrict *(The root url is already provided)',
                    'collapsable' => false,
                    'collapsed' => false
				),
				'rule_url' => array(
	                'type' => 'text',
	                'name' => 'rule_url',
	                'caption' => BX_DOL_URL_ROOT,
	                'info' => 'IMPORTANT: Leave out the root url and the "/"',
	            ), 
	            'submit' => array(
	                'type' => 'submit',
	                'name' => 'add_new_rule',
	                'value' => 'Add new rule',
	            ),
				'rules_setting_End' => array(
                        'type' => 'block_end'
                ),
			),
		);

        $oForm = new BxTemplFormView ($aForm);
        $oForm->initChecker();
		$sCode = $oForm->getCode();    		
    	return $sCode;
	}
	function getFreeForm(){
      	$aForm = array(
            'form_attrs' => array(
                'name'     => 'settings', 
                'method'   => 'post',
                'action'   => NULL,
            ),
			'inputs' => $aInputs,       
		);
	
        $oForm = new BxTemplFormView ($aForm);
        $oForm->initChecker();
		$sCode = $oForm->getCode();    		
    	return $sCode;
	}
	function getProcessorForm($sProc){
		switch($sProc){
			case 'paypal':
				$aInputs = $this->getPaypalInputs();
			break;
			case 'alertpay':
				$aInputs = $this->getAlertpayInputs();
			break;
			case 'authorize':
				$aInputs = $this->getAuthorizeInputs();
			break;
			case 'moneybookers':
				$aInputs = $this->getMoneyBookersInputs();
			break;
			default: $aInputs = $this->getDefaultInputs();
		}

      	$aForm = array(
            'form_attrs' => array(
                'name'     => 'settings', 
                'method'   => 'post',
                'action'   => NULL,
            ),
			'inputs' => $aInputs,       
		);
	
        $oForm = new BxTemplFormView ($aForm);
        $oForm->initChecker();
		$sCode = $oForm->getCode();    		
    	return $sCode;
	}
	function dataForwardForm(){
      	$aForm = array(
            'form_attrs' => array(
                'name'     => 'data_forward', 
                'method'   => 'post',
                'action'   => NULL,
            ),
			'inputs' => array(
				'block_header' => array(
                    'type' => 'block_header',
                    'caption' => 'Optional Payment Response Data Forwarding',
                    'collapsable' => 'true',
                    'collapsed' => 'true'
				),
				'data_forward_1' => array(
	                'type' => 'text',
	                'name' => 'data_forward_1',
	                'caption' => 'Data Forward URL 1',
	                'value' => $this->_oDb->getSetting('data_forward_1'),
	                'info' => 'Example: http://www.domain.com/other/ipn/',
	                'db' => array (
	                    'pass' => 'Xss',
	                ),
	            ), 
				'data_forward_2' => array(
	                'type' => 'text',
	                'name' => 'data_forward_2',
	                'caption' => 'Data Forward URL 2',
	                'value' => $this->_oDb->getSetting('data_forward_2'),
	                'info' => 'Example: http://www.domain.com/other/ipn/',
	                'db' => array (
	                    'pass' => 'Xss',
	                ),
	            ), 

	            'submit' => array(
	                'type' => 'submit',
	                'name' => 'save_data_forward',
	                'value' => 'Save',
	            ),
				'block_end' => array(
                        'type' => 'block_end'
                )
			)   
		);
	
        $oForm = new BxTemplFormView ($aForm);
        $oForm->initChecker();
		$sCode = $oForm->getCode();    		
    	return $sCode;
	}


	function getDefaultInputs(){
		$aDefaultProcessors = array('na' => '-- Select One --', 'paypal' => 'Paypal','alertpay' => 'Alertpay', 'authorize' => 'Authorize.net');
        $aInputs = array(
          	'payment_proc' => array(
         		'type' => 'select',
        		'name' => 'payment_proc',
           		'values' => $aDefaultProcessors,
                'caption' => _t('_dol_subs_adm_payment_proc'),
                'value' => $this->_oDb->getSetting('payment_proc'),
                'required' => true,
	            'checker' => array (
                   	'func' => 'avail',
                    'error' => _t('_dol_subs_adm_payment_proc_err'),
	            ),
            )
		);
		return $aInputs;
	}
	function getCreateMembershipInputs(){
			$aUnits = array('Days' => 'Days', 'Months' => 'Months');
	        $aInputs =  array(          
	            'Name' => array(
	                'type' => 'text',
	                'name' => 'Name',
	                'caption' => _t('_adm_txt_mlevels_name'),
	                'value' => '',
	                'db' => array (
	                    'pass' => 'Xss',
	                ),
	                'checker' => array (
						'func' => 'length',
						'params' => array(3,100),
						'error' => _t('_adm_txt_mlevels_name_err'),
					),
	            ),
	            'Icon' => array(
	                'type' => 'file',
	                'name' => 'Icon',
	                'caption' => _t('_dol_subs_adm_upload_image'),
	            	'value' => '',
					'label' => $sIcon,
	                'checker' => array (
						'error' => _t('_adm_txt_mlevels_icon_err'),
					),
	            ),
	            'Description' => array(
	                'type' => 'textarea',
	                'name' => 'Description',
	                'caption' => _t('_adm_txt_mlevels_description'),
	                'value' => '',
					'attrs' => array(
						'id' => 'mlevel_desc',
					),
	                'db' => array (
	                    'pass' => 'XssHtml',
	                ),
	            ),
	            'Purchasable' => array(
	                'type' => 'checkbox',
	                'name' => 'Purchasable',
	                'value' => 'yes',
					'caption' => _t('_dol_subs_adm_mlevel_purchasable'),
					'checked' => $sChecked = ($aLevel['Purchasable'] == 'yes') ? '1' : '0',
					'label' => _t('_dol_subs_adm_mlevel_purchasable_label'),
	                'db' => array (
	                    'pass' => 'Xss',
	                ),
	            ),
	            'Free' => array(
	                'type' => 'checkbox',
	                'name' => 'Free',
	                'value' => '1',
					'caption' => _t('_dol_subs_adm_mlevel_free'),
					'checked' => $aLevel['Free'],
					'label' => _t('_dol_subs_adm_mlevel_free_label'),
	                'db' => array (
	                    'pass' => 'Xss',
	                ),
	            ),	
	            'Price' => array(
	                'type' => 'text',
	                'name' => 'Price',
	                'value' => $aPriceInfo['Price'],
					'caption' => _t('_dol_subs_adm_mlevel_price').' ('.getParam('currency_sign').')',
	                'db' => array (
	                    'pass' => 'Xss',
	                ),
	            ),
                'Unit' => array(
                    'type' => 'select',
                    'name' => 'Unit',
                	'values' => $aUnits,
                    'caption' => 'Membership Interval',
					'info'	=> 'If over 90 days, Choose months',
                ),
	            'Length' => array(
	                'type' => 'text',
	                'name' => 'Length',
	                'value' => '',
					'caption' => 'Interval Length',
					'info'	=> 'The length of the selected interval between payments',
	                'db' => array (
	                    'pass' => 'Xss',
	                ),
	            ),
	            'Auto' => array(
	                'type' => 'checkbox',
	                'name' => 'Auto',
	                'value' => '1',
					'caption' => _t('_dol_subs_adm_mlevel_auto'),
					'checked' => $aPriceInfo['Auto'],
	                'db' => array (
	                    'pass' => 'Xss',
	                ),
	            ),
	            'submit' => array(
	                'type' => 'submit',
	                'name' => 'create_mlevel',
	                'value' => 'Create',
	            ),
			);
		return $aInputs;
	}
//----------------------------------------------------- Payment Processor Inputs------------------------------------------------//
    function getPaypalInputs(){
		$aInputs =  array(
                'payment_proc' => array(
                    'type' => 'select',
                    'name' => 'payment_proc',
                	'values' => $this->aProcessors,
                    'caption' => _t('_dol_subs_adm_payment_proc'),
                	'value' => 'paypal',
                    'required' => true,
	                'checker' => array (
                        'func' => 'avail',
                        'error' => _t('_dol_subs_adm_payment_proc_err'),
	                ),
                ),
                'paypal_id' => array(
                    'type' => 'email',
                    'name' => 'paypal_id', 
                    'caption' => _t('_dol_subs_adm_pp_id'),
					'value' => $this->_oDb->getSetting('paypal_id'),
					'info' => 'Business or Sandbox account Email',
					'attrs' => array(
						'id' => 'pp',
					),
                    'checker' => array (
                        'func' => 'email',
                        'error' => _t('_dol_subs_adm_pp_id_err'),
                    ),                   
                ),
                'sandbox' => array(
                    'type' => 'checkbox',
                    'name' => 'sandbox', 
					'value' => '1',   
                    'caption' => _t('_dol_subs_adm_pp_sandbox'),
                    'checked' => $this->_oDb->getSetting('sandbox'),
                    'label' => _t('_dol_subs_adm_pp_sandbox_label'),
					'attrs' => array(
						'id' => 'pp',
					),
                ),
                /*'pp_custom_field' => array(
                    'type' => 'text',
                    'name' => 'pp_custom_field', 
                    'caption' => 'Custom Field Data',
					'value' => $this->_oDb->getSetting('pp_custom_field'),
					'info' => 'Optional: Will be sent with payment as the "custom" field. Used for affiliate scripts, etc',
					'attrs' => array(
						'id' => 'pp',
					),                
                ),*/
				'add_button' => array(
					'type' => 'submit',
					'name' => 'save_proc',
					'value' => 'Save',
				),

           	);		
		return $aInputs;	
    }

	function getAlertpayInputs(){              
        $aInputs = array(
                'payment_proc' => array(
                    'type' => 'select',
                    'name' => 'payment_proc',
                	'values' => $this->aProcessors,
                    'caption' => _t('_dol_subs_adm_payment_proc'),
                	'value' => 'alertpay',
                    'required' => true,
	                'checker' => array (
                        'func' => 'avail',
                        'error' => _t('_dol_subs_adm_payment_proc_err'),
	                ),
                ),
                'alertpay_id' => array(
                    'type' => 'email',
                    'name' => 'alertpay_id', 
                    'caption' => _t('_dol_subs_adm_ap_id'),
					'value' => $this->_oDb->getSetting('alertpay_id'),
                    'checker' => array (
                        'func' => 'email',
                        'error' => _t('_dol_subs_adm_ap_id_err'),
                    ),                    
                 
                ),
				'ap_securitycode' => array(
                    'type' => 'text',
                    'name' => 'ap_securitycode', 
                    'caption' => _t('_dol_subs_adm_ap_code'),
					'value' => $this->_oDb->getSetting('ap_securitycode'),
                    'checker' => array (
                        'func' => 'length',
						'params' => array(3,56),
                        'error' => _t('_dol_subs_adm_ap_code_err'),
                    ),
                ),
				'add_button' => array(
					'type' => 'submit',
					'name' => 'save_proc',
					'value' => 'Save',
				),
           	);
		
    	return $aInputs;	
	}
	
	function getAuthorizeInputs(){    
        $aInputs = array(
                'payment_proc' => array(
                    'type' => 'select',
                    'name' => 'payment_proc',
                	'values' => $this->aProcessors,
                    'caption' => _t('_dol_subs_adm_payment_proc'),
                	'value' => 'authorize',
                    'required' => true,
	                'checker' => array (
                        'func' => 'avail',
                        'error' => _t('_dol_subs_adm_payment_proc_err'),
	                ),
                ),
                'an_login' => array(
                    'type' => 'text',
                    'name' => 'an_login', 
                    'caption' => _t('_dol_subs_adm_an_login'),
					'value' => $this->_oDb->getSetting('an_login'),
					'info' => 'Found in your Authorize.net account',
                    'checker' => array (
                        'func' => 'length',
						'params' => array(3,56),
                        'error' => _t('_dol_subs_adm_an_login_err'),
                    ),                  
                ),
				'an_transkey' => array(
                    'type' => 'text',
                    'name' => 'an_transkey', 
                    'caption' => _t('_dol_subs_adm_an_key'),
					'value' => $this->_oDb->getSetting('an_transkey'),
					'info' => 'Found in your Authorize.net account',
                    'checker' => array (
                        'func' => 'length',
						'params' => array(3,56),
                        'error' => _t('_dol_subs_adm_an_key_err'),
                    ),
                ),
                'an_test' => array(
                    'type' => 'checkbox',
                    'name' => 'an_test', 
					'value' => '1',   
                    'caption' => _t('_dol_subs_adm_an_test'),
                    'checked' => $this->_oDb->getSetting('an_test'),
                    'label' => _t('_dol_subs_adm_an_test_label'),
					'info' => 'For Test/Developer Accounts',
					'attrs' => array(
						'id' => 'pp',
					),
                ),
                'an_api' => array(
                    'type' => 'select',
                    'name' => 'an_api',
                	'values' => array(
						'sim' => 'Server Integration Method (SIM)',
						'arb' => 'Automatic Recurring Billing (ARB)',
					),
                    'caption' => 'Choose Integration Method',
                	'value' => $this->_oDb->getSetting('an_api'),
                    'required' => true,
                    'info' => 'SIM uses single payments only.  To use ARB you must be subscribed to it and have an SSL certificate',
	                'checker' => array (
                        'func' => 'avail',
                        'error' => _t('_dol_subs_adm_payment_proc_err'),
	                ),
                ),
                /*'an_ssl' => array(
                    'type' => 'checkbox',
                    'name' => 'an_ssl', 
					'value' => '1',   
                    'caption' => 'Use SSL',
                    'checked' => $this->_oDb->getSetting('an_ssl'),
                    'label' => '(switch to https)',
                    'info' => 'You must have an SSL certificate to use this.',
					'attrs' => array(
						'id' => 'pp',
					),
                ),*/
				'add_button' => array(
					'type' => 'submit',
					'name' => 'save_proc',
					'value' => 'Save',
				),

           	);		
    	return $aInputs;	
	}
	function getMoneyBookersInputs(){    
        $aInputs = array(
                'payment_proc' => array(
                    'type' => 'select',
                    'name' => 'payment_proc',
                	'values' => $this->aProcessors,
                    'caption' => _t('_dol_subs_adm_payment_proc'),
                	'value' => 'moneybookers',
                    'required' => true,
	                'checker' => array (
                        'func' => 'avail',
                        'error' => _t('_dol_subs_adm_payment_proc_err'),
	                ),
                ),
                'moneybookers_id' => array(
                    'type' => 'email',
                    'name' => 'moneybookers_id', 
                    'caption' => 'Merchant ID',
					'value' => $this->_oDb->getSetting('moneybookers_id'),
					'info' => 'MoneyBookers Account Email',
					'attrs' => array(
						'id' => 'mb',
					),
                    'checker' => array (
                        'func' => 'email',
                        'error' => _t('_dol_subs_adm_pp_id_err'),
                    ),                   
                ),
				'add_button' => array(
					'type' => 'submit',
					'name' => 'save_proc',
					'value' => 'Save',
				),

           	);		
    	return $aInputs;	
	}

	function getUserManagementInputs(){
		$aMbs = $this->_oDb->getMemberships();
		foreach($aMbs as $aMemb){
			$ignoreArray = array(1,3);
			if (!in_array($aMemb['ID'],$ignoreArray)){ 
				$aMemberships[$aMemb['ID']] = $aMemb['Name'];
			}
		}

        $aInputs = array(
                'default_memID' => array(
                    'type' => 'select',
                    'name' => 'default_memID',
                	'values' => $aMemberships,
                    'caption' => _t('_dol_subs_adm_dft_mem'),
                	'value' => $this->_oDb->getSetting('default_memID'),
                ),
				'require_mem' => array(
                    'type' => 'checkbox',
                    'name' => 'require_mem', 
					'value' => '1',
                    'caption' => _t('_dol_subs_adm_require_mem'),
					'checked' => $this->_oDb->getSetting('require_mem'),
					'info' => 'Force users to choose membership when joining',
                ),
				'redirect_guests' => array(
                    'type' => 'checkbox',
                    'name' => 'redirect_guests',
					'value' => '1', 
                    'caption' => _t('_dol_subs_adm_redirect_guests'),
					'checked' => $this->_oDb->getSetting('redirect_guests'),
					'info' => 'Force guests to join before using site.',
                ),
				'disable_downgrade' => array(
                    'type' => 'checkbox',
                    'name' => 'disable_downgrade',
					'value' => '1', 
                    'caption' => 'Disable Membership Downgrade',
					'checked' => $this->_oDb->getSetting('disable_downgrade'),
					'info' =>'Check to hide LESS expensive memberships from subscribed users',
                ),
				'disable_upgrade' => array(
                    'type' => 'checkbox',
                    'name' => 'disable_upgrade',
					'value' => '1', 
                    'caption' => 'Disable Membership Upgrade',
					'checked' => $this->_oDb->getSetting('disable_upgrade'),
					'info' =>'Check to hide MORE expensive memberships from subscribed users',
                ),
				'add_button' => array(
					'type' => 'submit',
					'name' => 'save_user_settings',
					'value' => 'Save',
				),

           	);		
    	return $aInputs;
	}

	function editMembership($iMembId){
		$aUnits = array('Days' => 'Days', 'Months' => 'Months');
		$aLevel = $this->_oDb->getMembershipById($iMembId);
		$aPriceInfo = $this->_oDb->getMembershipPriceInfo($iMembId);
		$sIcon = '<img src="'.$this->iconsUrl.$aLevel['Icon'].'" alt="noimage" title="'.$aLevel['Name'].'" height="40" width="40" />';
	    $aForm = array(
	        'form_attrs' => array(
	            'id' => 'edit_mlevel',
	            'action' => NULL,
	            'method' => 'post',
	            'enctype' => 'multipart/form-data',
	        ),
	        'params' => array (
	            'db' => array(
	                'table' => 'sys_acl_levels',
	                'key' => 'ID',
	                'uri' => '',
	                'uri_title' => '',
	                'submit_name' => 'update_mlevel'
	            ),
	        ),
	        'inputs' => array (  
				'Membership' => array(
					'type' => 'hidden',
					'name' => 'Membership',
					'value' => $iMembId,
					'db' => array (
						'pass' => 'Xss',
					),
				), 
				'settings_header' => array(
                    'type' => 'block_header',
                    'caption' => 'Settings'
				),         
	            'Name' => array(
	                'type' => 'text',
	                'name' => 'Name',
	                'caption' => _t('_adm_txt_mlevels_name'),
	                'value' => $aLevel['Name'],
	                'db' => array (
	                    'pass' => 'Xss',
	                ),
	                'checker' => array (
						'func' => 'length',
						'params' => array(3,100),
						'error' => _t('_adm_txt_mlevels_name_err'),
					),
	            ),
	            'Icon' => array(
	                'type' => 'file',
	                'name' => 'Icon',
	                'caption' => _t('_dol_subs_adm_chg_image'),
	            	'value' => '',
					'label' => $sIcon,
	                'checker' => array (
						'error' => _t('_adm_txt_mlevels_icon_err'),
					),
	            ),
	            'Description' => array(
	                'type' => 'textarea',
	                'name' => 'Description',
	                'caption' => _t('_adm_txt_mlevels_description'),
	                'value' => $aLevel['Description'],
					'attrs' => array(
						'id' => 'mlevel_desc',
					),
	                'db' => array (
	                    'pass' => 'XssHtml',
	                ),
	            ),

	            'Purchasable' => array(
	                'type' => 'checkbox',
	                'name' => 'Purchasable',
	                'value' => 'yes',
					'caption' => _t('_dol_subs_adm_mlevel_purchasable'),
					'checked' => $sChecked = ($aLevel['Purchasable'] == 'yes') ? '1' : '0',
					'label' => _t('_dol_subs_adm_mlevel_purchasable_label'),
	                'db' => array (
	                    'pass' => 'Xss',
	                ),
	            ),
	            'Free' => array(
	                'type' => 'checkbox',
	                'name' => 'Free',
	                'value' => '1',
					'caption' => _t('_dol_subs_adm_mlevel_free'),
					'checked' => $aLevel['Free'],
					'label' => _t('_dol_subs_adm_mlevel_free_label'),
					'info' => 'Price settings will not apply',
	                'db' => array (
	                    'pass' => 'Xss',
	                ),
	            ),
	            'Order' => array(
	                'type' => 'text',
	                'name' => 'Order',
	                'caption' => _t('_dol_subs_adm_txt_mlevels_order'),
	                'value' => $aLevel['Order'],
	                'info' => 'In Ascending Order (0,1,2,3)',
	                'db' => array (
	                    'pass' => 'Xss',
	                ),
	            ),
				'settings_end' => array(
                        'type' => 'block_end'
                ),	
				'pricing_header' => array(
                    'type' => 'block_header',
                    'caption' => 'Pricing Settings'
				),

	            'Price' => array(
	                'type' => 'text',
	                'name' => 'Price',
	                'value' => $aPriceInfo['Price'],
					'caption' => 'Recurring Price ('.getParam('currency_sign').')',
	                'info' => 'The price of every recurrance',
	                'db' => array (
	                    'pass' => 'Xss',
	                ),
	            ),
				
	            'FirstPaymentPrice' => array(
	                'type' => 'text',
	                'name' => 'FirstPaymentPrice',
	                'value' => $aPriceInfo['FirstPaymentPrice'],
					'caption' => 'First Payment ('.getParam('currency_sign').')',
	                'info' => 'The price of the first payment',
	                'db' => array (
	                    'pass' => 'Xss',
	                ),
	            ),
                'Unit' => array(
                    'type' => 'select',
                    'name' => 'Unit',
                	'values' => $aUnits,
                    'caption' => 'Membership Interval',
					'value'	=> $aPriceInfo['Unit'],
					'info'	=> 'If over 90 days, Choose months',
                ),
	            'Length' => array(
	                'type' => 'text',
	                'name' => 'Length',
	                'value' => $aPriceInfo['Length'],
					'caption' => 'Interval Length',
					'info'	=> 'The length of the selected interval between payments',
	                'db' => array (
	                    'pass' => 'Xss',
	                ),
	            ),
	            'Auto' => array(
	                'type' => 'checkbox',
	                'name' => 'Auto',
	                'value' => '1',
					'caption' => _t('_dol_subs_adm_mlevel_auto'),
					'checked' => $aPriceInfo['Auto'],
					'info' => 'Enables subscription creation. Disable for non-recurring memberships',
	                'db' => array (
	                    'pass' => 'Xss',
	                ),
	            ),
                'Trial' => array(
                    'type' => 'checkbox',
                    'name' => 'Trial', 
					'value' => '1',
                    'caption' => _t('_dol_subs_adm_trial'),
					'checked' => $aLevel['Trial'],
					'label' => _t('_dol_subs_adm_trial_label'), 
					'info' => 'Subscription will be created and will be free for the lenth of the trial.  Requires Auto-Recurring to be Enabled',                   
                ),
				'Trial_Length' => array(
                    'type' => 'text',
                    'name' => 'Trial_Length', 
                    'caption' => _t('_dol_subs_adm_trial_length'),
					'value' => $aLevel['Trial_Length'],
                ),
				'prcing_end' => array(
                        'type' => 'block_end'
                ),
	            'submit' => array(
	                'type' => 'submit',
	                'name' => 'update_mlevel',
	                'value' => 'Update',
	            ),                
	        )
	    );
		
		if($this->_oDb->getSetting('payment_proc') == 'authorize'){
			unset($aForm['inputs']['Trial_Length']);
			$aForm['inputs']['Trial']['info'] = 'First membership period will be a trial';
		}
	    $oForm = new BxTemplFormView($aForm);
	    $oForm->initChecker();
	
	    $bFile = true;
	    $sFilePath = $this->_oConfig->getIconsPath();
	    $sFileName = time();
	    $sFileExt = '';    

	    if($oForm->isSubmittedAndValid()) {
			if($this->isImage($_FILES['Icon']['type'], $sFileExt) && !empty($_FILES['Icon']['tmp_name'])){
				move_uploaded_file($_FILES['Icon']['tmp_name'],  $sFilePath . $sFileName . '.' . $sFileExt);
			}else if(!$this->isImage($_FILES['Icon']['type'], $sFileExt) && !empty($_FILES['Icon']['tmp_name'])){
	    		$oForm->aInputs['Icon']['error'] = $oForm->aInputs['Icon']['checker']['error'];
			}

			$sPath = $sFilePath . $sFileName . '.' . $sFileExt;
		    imageResize($sPath, $sPath, 110, 110);
			$sIcon = $sFileName . '.' . $sFileExt;
			$this->_oDb->updateMembershipInfo($_POST,$sPath); 

			// $_GET['membership'] == '2' && ($_GET['adm_mlevels_edit']
		   	Redirect($this->sBase.'memberships/',array('membership' => $iMembId, 'adm_mlevels_edit' => 'saved_edit'), get);
  
	    } else {
			$sCode = $oForm->getCode();
			if($_GET['adm_mlevels_edit'] == 'saved_edit')
				$sCode.= MsgBox('Successfully Updated Membership',3); 

			if($_GET['adm_mlevels_edit'] == 'saved_actions')
				$sCode.= MsgBox('Actions Successfully Updated',3);
 		
	        return $sCode;
	    }
	}
	function isImage($sMimeType, &$sFileExtension) {
		$bResult = true;
		switch($sMimeType) {
			case 'image/jpeg':
			case 'image/pjpeg':
				$sFileExtension = 'jpg';
				break;
			case 'image/png':
			case 'image/x-png':
				$sFileExtension = 'png';
				break;
			case 'image/gif':
				$sFileExtension = 'gif';
				break;
			default:
				$bResult = false;
		}
		return $bResult;
	}
	function editMembershipActions($iMembId){

	    $aItems = array();	
	    $aActions = $this->_oDb->getActions();
	    $aActionsActive = $this->_oDb->getActiveActions($iMembId);


	    foreach($aActions as $aAction) {
	        $bEnabled = array_key_exists($aAction['id'], $aActionsActive);

			if($bEnabled){
				$iNumAllowed = $aActionsActive[$aAction['id']]['AllowedCount'];
				$iResetHours = $aActionsActive[$aAction['id']]['AllowedPeriodLen'];
			}	
	        $aItems[] = array(
	            'action_name' => ucwords($aAction['title']),
	            'action_id' => $aAction['id'],
	            'title' => $aAction['title'],
	            'checked' => $bEnabled ? 'checked="checked"' : '',
                'level_id' => $iMembId,
				'num_allowed' => $bEnabled ? $iNumAllowed : '',
				'reset_hours' => $bEnabled ? $iResetHours : '',
	        );
	    }   	
		$aButtons = array(
	        'adm_mlevels_update' 		=> 'Update Actions',
	        'adm_mlevels_reset' 		=> 'Reset'
	    ); 
		$sControls = BxTemplSearchResult::showAdminActionsPanel('mem_actions', $aButtons, 'actions', true ,false);
	
		$aVars = array(
	        'id' => $iMembId,
	        'bx_repeat:items' => $aItems,
	        'url_admin' => $GLOBALS['site']['url_admin'],
			'controls' => $sControls
	    );

	    $sResult = $this->parseHtmlByName('admin_mem_actions', $aVars);
	    
		if($_POST['adm_mlevels_update'] == 'Update Actions'){
			$this->_oDb->updateMembershipActions($_POST,$iMembId);
			Redirect($this->sBase.'memberships/',array('membership' => $iMembId, 'adm_mlevels_edit' => 'saved_actions'), get);
		}	
	    return $sResult;
	}
	function getTimeStamp($sDateTime){
		if($sDateTime){
			list($sDate, $sTime) = explode(' ', $sDateTime);
			list($iYear, $iMonth, $iDay) = explode('-', $sDate);
			list($iHour, $iMin, $iSec) = explode(':', $sTime);	
			$sResult = mktime($iHour, $iMin, $iSec, $iMonth, $iDay, $iYear);	
		}		
		return $sResult;
	}
	function trimString($sString,$iChars){
		if(mb_strlen($sString) > $iChars){
			mb_substr($sString, 0, $iChars) . '...';
		}
		return $sString;
	}
	function showMenuAccessForm($sAction,$iMenuItemID){
		$sMenuItemName = $this->_oDb->getMenuItemName($iMenuItemID);
		$sCode = ($sAction == 'save') ? MsgBox('Saved Menu Access Options',2) : '' ;
		$aMemLevels = $this->_oDb->getMembershipsAdmin();		
		$aMenuAccessLevels = $this->_oDb->getMenuAccessLevels($iMenuItemID);
		foreach ($aMemLevels as $aMemLevel) {
			
			if (in_array($aMemLevel['ID'],array(3))) continue;
	        $aItems[] = array(
	            'mlevel_id' => $aMemLevel['ID'],
	            'mlevel_name' => $aMemLevel['Name'],
				'checked' => $sChecked = (is_array($aMenuAccessLevels) && in_array($aMemLevel['ID'],$aMenuAccessLevels)) ? 'checked' : ''
	        );
		}
		$aVars = array(
			'bx_repeat:mlevels' => $aItems,
			'action_url' => $this->sBase.'main_menu',
			'menu_item_id' => $iMenuItemID,
			'menu_item_name' => $sMenuItemName,
		);    		
    	return PopupBox('menu_access', 'Edit Membership Access', $this->parseHtmlByName('admin_menu_item_form',$aVars));
	}
	
    function customDisplayAccessDenied () {
        $sTitle = _t('_Access denied');	
 		if($_COOKIE['memberID']){
			$sRedirect = $this->sBase;
			$sText1 = _t('_dol_subs_denied_upgrade1');
			$sText2 = _t('_dol_subs_denied_upgrade2');
		}else{
			$sRedirect = BX_DOL_URL_ROOT.'join.php';
			$sText1 = _t('_dol_subs_denied_join1');
			$sText2 = _t('_dol_subs_denied_join2');
		}

        $GLOBALS['_page'] = array(
            'name_index' => 0,
            'header' => $sTitle,
            'header_text' => $sTitle
        );
		
		$aVars = array(
			'message' => MsgBox($sTitle),
			'redirect_url' => $sRedirect,
			'text_link'	=> $sText1,
			'text_after' => $sText2,
		);
		
        $GLOBALS['_page_cont'][0]['page_main_code'] = $this->parseHtmlByName('access_denied',$aVars);
       	$this->addCss('dolphin_subs.css');
        PageCode();
        exit;
    }

	function displayUrlRules(){

		if($_POST['adm_rules_del'] && is_array($_POST['rules'])){
			$this->_oDb->removeURLRules($_POST['rules']);
			$sMsg = MsgBox('Successfully Removed',2);			
		}
	
		$aRules = $this->_oDb->getURLRules();// Input optional filter arg to retrieve subs
		$sMsg = (count($aRules) > '0') ?  '' : MsgBox('No Restriction Rules');


		if($_POST['adm_rules_del'] && !$_POST['rules'])
			$sMsg = MsgBox('Nothing Selected');

		$iStart = ($_GET['start']) ? $_GET['start'] : 0;
		$iPerPage = ($_GET['per_page']) ? $_GET['per_page'] : 10;
		$sDateFormat = getLocaleFormat(BX_DOL_LOCALE_DATE, BX_DOL_LOCALE_DB);
		$iCount = count($aRules);
		$iCounter = '0';
		$aRange = range($iStart,$iStart+($iPerPage-1));
		foreach($aRules as $aRule){			

			if(!in_array($iCounter++,$aRange)) continue;	

			$aMemLevels = unserialize($aRule['mlevels']);
			$sMembLevelsCode = "<ul>";
			foreach($aMemLevels as $aMemLevel){
				$aMemLevelInfo = getMembershipInfo($aMemLevel);
				$sMembLevelsCode.= "<li>".$aMemLevelInfo['Name']."</li>";
			}
			$sMembLevelsCode.= "</ul>";

		   	$aItems[] = array(
		      	'id' 			=> $aRule['id'],
				'message'		=> $sMsg,
		        'mlevels' 		=> $sMembLevelsCode,
		        'rule_url' 		=> '.../ <b>'.$aRule['url'].'</b>',
		    );
		}

	    $oPaginate = new BxDolPaginate(array(
	      	'start' => $iStart,
			'count' => $iCount,
			'per_page' => $iPerPage,
			'sorting'    => 'last',
			'per_page_changer'   => true,
			'page_reloader'      => true,
		    'page_url' => $this->sBase.'subscriptions/?start={start}&per_page={per_page}',
	    ));
	    $sPaginate = $oPaginate->getPaginate();   

		$aButtons = array(
	        'adm_rules_del' 		=> 'Delete'
	    ); 
		$sControls = BxTemplSearchResult::showAdminActionsPanel('rules_form', $aButtons, 'rules', true ,false); 

		$aResult = array(
			'bx_repeat:items' => $aItems,
			'paginate' => $sPaginate,
			'controls' => $sControls,
			'message' => $sMsg
		);
	    return $this->parseHtmlByName('admin_rules', $aResult);
	}

	
}
?>
