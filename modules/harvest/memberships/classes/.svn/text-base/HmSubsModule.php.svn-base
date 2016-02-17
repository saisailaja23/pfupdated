<?php
/***************************************************************************
Dolphin Subscriptions v.2.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/
function classImport($sClassPostfix, $aModuleOverwright = array()) {
    global $aModule;
    $a = $aModuleOverwright ? $aModuleOverwright : $aModule;
    if (!$a || $a['uri'] != 'memberships') {
        $oMain = BxDolModule::getInstance('HmSubsModule');
        $a = $oMain->_aModule;
    }
    bx_import ($sClassPostfix, $a);
}
bx_import('BxDolModule');
bx_import('BxDolPaginate');
bx_import('BxDolAlerts');
bx_import('BxDolModule');
bx_import('BxDolAdminSettings');
class HmSubsModule extends BxDolModule {
    var $_iProfileId;
    function HmSubsModule(&$aModule) {      
        parent::BxDolModule($aModule);
        $this->_iProfileId = $GLOBALS['logged']['member'] || $GLOBALS['logged']['admin'] ? $_COOKIE['memberID'] : 0;
        $GLOBALS['oHmSubsModule'] = &$this;
	}
   	function actionHome () {


       if (!$this->_iProfileId) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }
        $this->_oTemplate->pageStart();   

        if (isset($_POST['login_text']) && $_POST['login_text']) {
            echo MsgBox(_t(strip_tags($_POST['login_text'])));
        }

        classImport('PageMain');
        $oPage = new HmSubsPageMain ($this);
        echo $oPage->getCode();
        $this->_oTemplate->addCss ('dolphin_subs.css');
        $this->_oTemplate->addJs ('functions.js');
        $this->_oTemplate->pageCode(_t('_dol_subs_title'), false, false);	
    } 

	function actionSettings(){
		$GLOBALS['iAdminPage'] = 1;

        if (!$GLOBALS['logged']['admin']) { 
            $this->_oTemplate->displayAccessDenied ();
            return;
        }																																																																																																												
       // $key_info['key']='9CRM015HX5';$key_info['domain']='parentfinder.com';$serverurl="http://license.harvest-media.com/server.php";$ch=curl_init($serverurl);curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);curl_setopt($ch,CURLOPT_POST, true);curl_setopt($ch,CURLOPT_POSTFIELDS,$key_info);$result=curl_exec($ch);$result=json_decode($result, true);if($result['valid'] == 'true'){
            $sCode.= $this->_oTemplate->paymentProcessorSettings();$sCode.= $this->_oTemplate->dataForwardSettings();$sCode.= $this->_oTemplate->userManagementSettings();

         //   }else{$sCode =  DesignBoxContent('Invalid Licence', '<div class="invalid">License not setup for <span>'.$key_info['domain'].'</span></div>',1);$body = 'Someone tried to use Dolphin Subs from '.$_SERVER[ 'SERVER_NAME'  ];sendMail('troy@harvest-media.com', 'Dolphin Subs Licence Error',$body);}
        $this->_oTemplate->pageStart();
			echo $sCode;		
	    $this->_oTemplate->addAdminJs('functions.js');
	    $this->_oTemplate->addAdminCss('admin.css');
        $this->_oTemplate->pageCodeAdmin ('Membership Settings'); 
	}

	function actionMemberships(){
		$GLOBALS['iAdminPage'] = 1;

        if (!$GLOBALS['logged']['admin']) { 
            $this->_oTemplate->displayAccessDenied ();
            return;
        }

        $this->_oTemplate->pageStart();	
		
			$sCode = $this->_oTemplate->adminCurrentMemberships(); 
			if(!$_GET['adm_mlevels_edit'])
				$sCode.= $this->_oTemplate->adminCreateMembership(); 

			echo $sCode;

		$aJs = array('functions.js','profiles.js');
	    $this->_oTemplate->addAdminJs($aJs);
	    $this->_oTemplate->addAdminCss('admin.css');
        $this->_oTemplate->pageCodeAdmin ('Membership Setup'); 
	}

	function actionSubscriptions(){
		$GLOBALS['iAdminPage'] = 1;

        if (!$GLOBALS['logged']['admin']) { 
            $this->_oTemplate->displayAccessDenied ();
            return;
        }

        $this->_oTemplate->pageStart(); 
			
			$sCode = $this->_oTemplate->subscribersMainCode(); 

			echo $sCode;

	    $this->_oTemplate->addAdminJs('functions.js');
	    $this->_oTemplate->addAdminCss('admin.css');
        $this->_oTemplate->pageCodeAdmin ('Subscriptions'); 
	} 

	function actionContent(){
		$GLOBALS['iAdminPage'] = 1;
        if (!$GLOBALS['logged']['admin']) { 
            $this->_oTemplate->displayAccessDenied();
            return;
        }
        $this->_oTemplate->pageStart(); 
			$sCode = $this->_oTemplate->menuItemControl(); 
			echo $sCode;
	    $this->_oTemplate->addAdminJs('functions.js');
	    $this->_oTemplate->addAdminCss(array('admin.css','forms_adv.css'));
        $this->_oTemplate->pageCodeAdmin ('Content Control'); 
	}
	function actionUrl(){
		$GLOBALS['iAdminPage'] = 1;
        if (!$GLOBALS['logged']['admin']) { 
            $this->_oTemplate->displayAccessDenied();
            return;
        }
        $this->_oTemplate->pageStart(); 
			$sCode = $this->_oTemplate->urlControl(); 
			echo $sCode;
	    $this->_oTemplate->addAdminJs('functions.js');
	    $this->_oTemplate->addAdminCss(array('admin.css','forms_adv.css'));
        $this->_oTemplate->pageCodeAdmin ('Content Control'); 
	}
	function actionPayments(){
		$GLOBALS['iAdminPage'] = 1;
        if (!$GLOBALS['logged']['admin']) { 
            $this->_oTemplate->displayAccessDenied();
            return;
        }
        $this->_oTemplate->pageStart(); 
        	classImport('PagePayments');
        	$oPage = new HmSubsPagePayments($this);
        	$sCode = $oPage->getPaymentStats();
        	$sCode.= $oPage->getPaymentsPageCode();
			echo $sCode;
	    $this->_oTemplate->addAdminJs('functions.js');
	    $this->_oTemplate->addAdminCss(array('admin.css','forms_adv.css'));
        $this->_oTemplate->pageCodeAdmin ('Payments'); 
	}
	
	function actionGetProcessor(){
		$sSettingsUrl = BX_DOL_URL_ROOT.'m/memberships/settings/';
		$sProc = $_POST['processor'];
		$sBase = BX_DOL_URL_ROOT;

		$sCode = $this->_oTemplate->getProcessorForm($sProc);	

		$sCode .= 	"<script type=\"text/javascript\" language=\"javascript\">".
					"$(document).ready(function(){".
						"$('select[name=payment_proc]').change(function(){".
							"var sel = $('option:selected', this).val();".
							"var loading = $('.loading');". 	
							"var parent = $('form#settings');". 	
							"$('#button').css('disabled','disabled');".
							"loading.css('left', (parent.width() - loading.width())/2);".
					        "loading.css('top', (parent.height() - loading.height())/2);".
						    "loading.show();".
							"$.post('".$sBase."m/memberships/get_processor/', {".
								"processor: sel".
							"},".
					   		"function(data){".
								"loading.hide();".
								"$('form#settings').remove();".
								"$('#settings_form').html(data).fadeIn('slow');". 			  	
					  	 	"});".
							"return false;".
						"});".
					"});".
			    	"</script>";	
		echo $sCode;
		return;
	}
	function actionMainMenu(){
		$sAction = bx_get('action');
		$iMenuItemID = bx_get('menu_id');
		if(!isAdmin()) $this->_oTemplate->displayAccessDenied();
		if($sAction == 'edit'){
			echo $this->_oTemplate->showMenuAccessForm($sAction,$iMenuItemID);
		}
		if($sAction == 'save'){
			$this->_oDb->setMenuAccessLevels($_POST);
			echo PopupBox('menu_access', 'Edit Membership Access', MsgBox('Saved Menu Item'));
		}

	}
	function actionCallback(){

		if($this->_oDb->getSetting('data_forward_1')){
			$this->forwardResponseData($this->_oDb->getSetting('data_forward_1'),$_POST);
		}
		if($this->_oDb->getSetting('data_forward_2')){
			$this->forwardResponseData($this->_oDb->getSetting('data_forward_2'),$_POST);
		}

		$sProcessor = $this->_oConfig->checkResponse($_POST);
		switch($sProcessor){
			case 'paypal':
				classImport('PayPal');
				$oPayPal = new HmSubsPayPal;
				return $oPayPal->processPayment($_POST);
			break;
			case 'alertpay':
				classImport('AlertPay');
				$oAlertPay = new HmSubsAlertPay;
				return $oAlertPay->processPayment($_POST);
			break;
			case 'authorize':
				classImport('AuthorizeNet');
				$oAuthorizeNet = new HmSubsAuthorizeNet;
				return $oAuthorizeNet->processPayment($_POST);
			break;		
		}

	}
	function actionOrder(){
		if(bx_get('action') == 'an_order'){
			classImport('AuthorizeNet');
			$oAuthorizeNet = new HmSubsAuthorizeNet;
			$sCode = $oAuthorizeNet->showArbLargeForm(bx_get('mlevel'));

		}
        $this->_oTemplate->pageStart(); 
			echo $sCode;
	    $this->_oTemplate->addJs('functions.js');
	    $this->_oTemplate->addCss(array('admin.css','forms_adv.css'));
        $this->_oTemplate->pageCode('Order'); 

	}
	function actionAjax(){
		if(bx_get('action') == 'an_order'){
			classImport('AuthorizeNet');
			$oAuthorizeNet = new HmSubsAuthorizeNet;
			echo PopupBox('order_form', 'Order Form',$oAuthorizeNet->showArbForm(bx_get('mlevel')));
		}
		if($_POST['action'] == 'an_process'){
			classImport('AuthorizeNet');
			$oAuthorizeNet = new HmSubsAuthorizeNet;
			echo $oAuthorizeNet->processArbPayment($_POST);
		}

	}
	function forwardResponseData($fullurl,$aVars){
		global $status;
		$fullurl = rtrim($fullurl);
		$newurl = stristr($fullurl, "://");
		$newurl = ltrim($newurl, ':/');
		if ($newurl == '') $newurl = $fullurl;
		$url =  stristr($newurl, "/");
		$host = substr($newurl, 0, strpos($newurl, "/"));
		$port = 80;
		if (ereg("https", $fullurl)) $port = 443;	
		$query_return = '';
		foreach ($aVars as $key => $value) {
	        	$query_return .= "&$key=".urlencode(stripslashes($value));
		};
		$response = '';	
		$fp = @fsockopen( $host, $port, $errno, $errstr, 90); 	
		if (!$fp) { 
	   		echo "socketerr: $errstr ($errno)\n";
		} else {
			fputs( $fp, "POST $url HTTP/1.0\r\n" ); 
			fputs( $fp, "Host: $host\r\n" ); 
			fputs( $fp, "Content-type: application/x-www-form-urlencoded\r\n" ); 
			fputs( $fp, "Content-length: " . strlen($query_return) . "\r\n\n" ); 
			fputs( $fp, "$query_return\n\r" ); 
			fputs( $fp, "\r\n" );
			while (!feof($fp)) { 
				$response .= fgets( $fp, 1024 ); 
			}
			fclose( $fp );
		}
		if (ereg("200 OK", $response)) { //forward accepted
			return 1;
		} 	
		$status = 0;
		return 0;
	}
	function serviceCurrentMembership($iId){
		$aProfile = getProfileInfo($iId);
		$aMembershipInfo = getMemberMembershipInfo($iId);
		$sMembershipBadge = BxDolService::call('memberships', 'membership_badge', array($aMembershipInfo['ID']));
		$aForm = array(
	        'form_attrs' => array(
	            'id' => 'mem_details',
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
			'inputs' => array(
	            'membership_icon' => array(
	                'type' => 'custom',
	                'caption' => '',
	                'content' => $sMembershipBadge,
	            ),
	            'membership_name' => array(
	                'type' => 'custom',
	                'caption' => _t('_dol_subs_membership'),
	                'content' => $aMembershipInfo['Name'],
	            ),
	            'membership_start' => array(
	                'type' => 'custom',
	                'caption' => _t('_dol_subs_start_date'),
	                'content' => ($aMembershipInfo['DateStarts']) ? date('Y-m-d', $aMembershipInfo['DateStarts']) : date('Y-m-d', strtotime($aProfile['DateReg'])),
	            ),
	            'membership_end' => array(
	                'type' => 'custom',
	                'caption' => _t('_dol_subs_exp_date'),
	                'content' => ($aMembershipInfo['DateExpires']) ? date('Y-m-d', $aMembershipInfo['DateExpires']) : _t('_dol_subs_never'),
	            ),
			)
	    );
	    $oForm = new BxTemplFormView($aForm);
	    $oForm->initChecker();
		$sCode = $oForm->getCode(); 		
	    return $sCode;
	}
	function serviceMembershipBadge($iMembershipId, $sLarge = false){
		$sBadgeUrl = $this->_oConfig->getIconsUrl();	
		if($iMembershipId == MEMBERSHIP_ID_STANDARD || $iMembershipId == MEMBERSHIP_ID_PROMOTION){
			$sImageName = 'member.png';
		}else{
			$sImageName = $this->_oDb->getMembershipIcon($iMembershipId);
		}
		
		$aVars = array(
			'image' => $sBadgeUrl.$sImageName,
			'bx_if:large' => array(
				'condition' => ($sLarge == false),
				'content' => array(
					'height' => '60',
					'width' => '60',
				)
			)
		);
	    return $this->_oTemplate->parseHtmlByName('badge', $aVars);
	}

}
?>