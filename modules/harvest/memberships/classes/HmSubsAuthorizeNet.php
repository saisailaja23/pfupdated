<?php
/***************************************************************************
Dolphin Subscriptions v.2.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/
bx_import('BxDolModule');

class HmSubsAuthorizeNet{

    function __construct(){
        $this->oMain = $this->getMain();
		$this->aCreditCards = array('visa' => 'Visa','mastercard' => 'Mastercard','amex' => 'American Express', 'other' => 'Other');
	}

    function getMain() {
        return BxDolModule::getInstance('HmSubsModule');
    }

	function showForm($iMembId){ 
		$aSettings = $this->oMain->_oDb->getSettings();
		$sUrl = $this->getSimUrl($aSettings);
		$sAnLogin = $aSettings['an_login'];
		$aMembInfo = $this->oMain->_oDb->getMembershipById($iMembId);
		$aMembPriceInfo = $this->oMain->_oDb->getMembershipPriceInfo($iMembId);
		$sTransactionKey = $aSettings['an_transkey'];
		$iSequence	= rand(1, 1000);
		$sTimeStamp	= time ();
		$sFingerPrint = $this->getFingerprint($sAnLogin,$sTransactionKey,$aMembPriceInfo['Price'],$iSequence,$sTimeStamp);
		$iInvoice	= date(YmdHis);
 		if($this->oMain->_oDb->getSetting('an_api') == 'sim'){
			return $this->getSimForm($iMembId);
		}
		if($this->oMain->_oDb->getSetting('an_api') == 'arb'){
			if($aMembPriceInfo['Auto'] == '1'){
				return $this->getArbForm($iMembId);
			}else{
				return $this->getSimForm($iMembId);
			}
		}
    }
	function getFingerprint($sAnLogin,$sTransactionKey,$iAmount,$iSequence,$sTimeStamp){
		if( phpversion() >= '5.1.2' ){	
			$sFingerPrint = hash_hmac("md5", $sAnLogin . "^" . $iSequence . "^" . $sTimeStamp . "^" . $iAmount . "^", $sTransactionKey);
		}else{ 
			$sFingerPrint = bin2hex(mhash(MHASH_MD5, $sAnLogin . "^" . $iSequence . "^" . $sTimeStamp . "^" . $iAmount . "^", $sTransactionKey)); 
		}
		return $sFingerPrint;
	}
	function processPayment($aVars){
		
 		list($iUserId, $iMembLevel) = explode('_', $aVars['x_cust_id']);
		$aMembLevelInfo = $this->oMain->_oDb->getMembershipById($iMembLevel);
		$aMembLevelPriceInfo = $this->oMain->_oDb->getMembershipPriceInfo($iMembLevel);
		$iPrice = number_format($aMembLevelPriceInfo['Price'], 2);

		$sResponseText = urldecode($aVars['x_response_reason_text']);
		switch($aMembLevelPriceInfo['Unit']){
			case 'Days':
				$iMembershipDays = $aMembLevelPriceInfo['Length'];
			break;
			case 'Months':
				$iMembershipDays = $aMembLevelPriceInfo['Length']*30;
			break;
		}        

        if($aVars['x_response_code'] == '1'){			
			$rInsertPayment = db_res("INSERT INTO `dol_subs_payments` SET 
				`txn_id` 		= '{$aVars['x_trans_id']}',
				`membership_id` = '{$iMembLevel}',
				`user_id`		= '{$iUserId}',
				`amount` 		= '{$aVars['x_amount']}',
				`date` 			= NOW(),
				`status` 		= 'Completed'
			");			
			$this->oMain->_oDb->setUserStatus($iId,'Active');			
			$this->oMain->_oDb->clearCache('user', BX_DIRECTORY_PATH_CACHE);
                        setMembership($iUserId, $iMembLevel, $iMembershipDays, true, $aVars['x_trans_id']);
		}
	}
	function showArbLargeForm($iMembId){
		$aProfile = getProfileInfo($_COOKIE['memberID']);
      	$aForm = array(
            'form_attrs' => array(
                'name'     => 'an_payment_form', 
                'method'   => 'post',
				'action' => NULL,
				'onsubmit' => 'return false;',
            ),
			'inputs' => array(
				'cust_id' => array(
					'type' => 'hidden',
					'name' => 'cust_id',
					'value' => $_COOKIE['memberID'].'_'.$iMembId,
					'attrs' => array(
						'id' => 'cust_id',
					),
				),

                'fname' => array(
                    'type' => 'text',
                    'name' => 'fname', 
                    'caption' => 'First Name',
                    'required' => true,
					'value' => $aProfile['FirstName'],
					'attrs' => array(
						'id' => 'fname',
					),                  
                ),
                'lname' => array(
                    'type' => 'text',
                    'name' => 'lname', 
                    'caption' => 'Last Name',
                    'required' => true,
					'value' => $aProfile['LastName'],
					'attrs' => array(
						'id' => 'lname',
					),                  
                ),
                'credit_card' => array(
                    'type' => 'select',
                    'name' => 'credit_card',
                	'values' => $this->aCreditCards,
                    'caption' => 'Choose Credit Card',
                    'required' => true,
					'attrs' => array(
						'id' => 'credit_card',
					), 
                ),
                'cc_num' => array(
                    'type' => 'text',
                    'name' => 'cc_num', 
                    'caption' => 'Credit Card Number',
                    'required' => true,
                    'info' => 'No dashes or spaces',
					'attrs' => array(
						'id' => 'cc_num',
					),                  
                ),
				'cc_expm' => array(
                    'type' => 'select',
                    'name' => 'cc_expm', 
                    'caption' => 'Expiry Month',
                    'required' => true,
					'values' => $this->oMain->_oConfig->getMonths(),
					'attrs' => array(
						'id' => 'cc_expm',
					),
                ),
                'cc_expy' => array(
                    'type' => 'text',
                    'name' => 'cc_expy', 
                    'caption' => 'Expiry Year (YYYY)',
                    'required' => true,
					'attrs' => array(
						'id' => 'cc_expy',
					), 
                ),
                'cc_csv' => array(
                    'type' => 'text',
                    'name' => 'cc_csv',
                    'caption' => 'Card Code',
                    'required' => true,
                    'info' => 'The last three numbers on the back of the card.',
					'attrs' => array(
						'id' => 'cc_csv',
					), 
                ),
				'email' => array(
					'type' => 'text',
					'name' => 'email',
                    'caption' => 'Email',
                    'required' => true,
					'value' => $aProfile['Email'],
					'attrs' => array(
						'id' => 'email',
					),
				),
                'cc_address1' => array(
                    'type' => 'text',
                    'name' => 'cc_address1', 
                    'caption' => 'Address 1',
                    'required' => true,
					'attrs' => array(
						'id' => 'cc_address1',
					), 
                ),
                'cc_city' => array(
                    'type' => 'text',
                    'name' => 'cc_city', 
                    'caption' => 'City',
                    'required' => true,
					'attrs' => array(
						'id' => 'cc_city',
					), 
                ),
                'cc_state' => array(
                    'type' => 'text',
                    'name' => 'cc_state', 
                    'caption' => 'State/Province',
                    'required' => true,
					'attrs' => array(
						'id' => 'cc_state',
					), 
                ),
                'cc_country' => array(
                    'type' => 'text',
                    'name' => 'cc_country', 
                    'caption' => 'Country',
					'attrs' => array(
						'id' => 'cc_country',
					), 
                ),
                'cc_zip' => array(
                    'type' => 'text',
                    'name' => 'cc_zip', 
                    'caption' => 'Zip Code',
                    'required' => true,
					'attrs' => array(
						'id' => 'cc_zip',
					), 
                ),
                'phone' => array(
                    'type' => 'text',
                    'name' => 'phone', 
                    'caption' => 'Phone Number',
					'attrs' => array(
						'id' => 'phone',
					), 
                ),
				'add_button' => array(
					'type' => 'submit',
					'name' => 'an_payment_btn',
					'value' => 'Make Payment',
					'attrs' => array(
						'id' => 'an_payment_btn',
					),
				),
           	),	      
		);	
        $oForm = new BxTemplFormView ($aForm);
        $oForm->initChecker();
		$sCode.= $oForm->getCode(); 
		$aVars = array(
			'code' => $sCode,
			'action_url' => BX_DOL_URL_ROOT.'m/memberships/ajax',
		);   		
    	return $this->oMain->_oTemplate->parseHtmlByName('authorize_arb_payment',$aVars);
     }
    function showArbForm($iMembId){
		$aProfile = getProfileInfo($_COOKIE['memberID']);
      	$aForm = array(
            'form_attrs' => array(
                'name'     => 'an_payment_form', 
                'method'   => 'post',
				'action' => NULL,
				'onsubmit' => 'return false;',
            ),
			'inputs' => array(
				'cust_id' => array(
					'type' => 'hidden',
					'name' => 'cust_id',
					'value' => $_COOKIE['memberID'].'_'.$iMembId,
					'attrs' => array(
						'id' => 'cust_id',
					),
				),
				'email' => array(
					'type' => 'hidden',
					'name' => 'email',
					'value' => $aProfile['Email'],
					'attrs' => array(
						'id' => 'email',
					),
				),
                'fname' => array(
                    'type' => 'text',
                    'name' => 'fname', 
                    'caption' => 'First Name',
                    'required' => true,
					'value' => $aProfile['FirstName'],
					'attrs' => array(
						'id' => 'fname',
					),                  
                ),
                'lname' => array(
                    'type' => 'text',
                    'name' => 'lname', 
                    'caption' => 'Last Name',
                    'required' => true,
					'value' => $aProfile['LastName'],
					'attrs' => array(
						'id' => 'lname',
					),                  
                ),
                'credit_card' => array(
                    'type' => 'select',
                    'name' => 'credit_card',
                	'values' => $this->aCreditCards,
                    'caption' => 'Choose Credit Card',
                    'required' => true,
					'attrs' => array(
						'id' => 'credit_card',
					), 
                ),
                'cc_num' => array(
                    'type' => 'text',
                    'name' => 'cc_num', 
                    'caption' => 'Credit Card Number',
                    'required' => true,
                    'info' => 'No dashes or spaces',
					'attrs' => array(
						'id' => 'cc_num',
					),                  
                ),
				'cc_expm' => array(
                    'type' => 'select',
                    'name' => 'cc_expm', 
                    'caption' => 'Expiry Month',
                    'required' => true,
					'values' => $this->oMain->_oConfig->getMonths(),
					'attrs' => array(
						'id' => 'cc_expm',
					),
                ),
                'cc_expy' => array(
                    'type' => 'text',
                    'name' => 'cc_expy', 
                    'caption' => 'Expiry Year (YYYY)',
                    'required' => true,
					'attrs' => array(
						'id' => 'cc_expy',
					), 
                ),
                'cc_csv' => array(
                    'type' => 'text',
                    'name' => 'cc_csv',
                    'caption' => 'Card Code',
                    'required' => true,
                    'info' => 'The last three numbers on the back of the card.',
					'attrs' => array(
						'id' => 'cc_csv',
					), 
                ),
				'add_button' => array(
					'type' => 'submit',
					'name' => 'an_payment_btn',
					'value' => 'Make Payment',
					'attrs' => array(
						'id' => 'an_payment_btn',
					),
				),
           	),	      
		);	
        $oForm = new BxTemplFormView ($aForm);
        $oForm->initChecker();
		$sCode.= $oForm->getCode(); 
		$aVars = array(
			'code' => $sCode,
			'action_url' => BX_DOL_URL_ROOT.'m/memberships/ajax',
		);   		
    	return $this->oMain->_oTemplate->parseHtmlByName('authorize_arb_payment',$aVars);
     }
	function processArbPayment($aVars){
		$sHost = $this->getArbUrl();
		list($iUserId, $iMembLevel) = explode('_', $aVars['cust_id']);
		$aProfile = getProfileInfo($iUserId);
		$aMembInfo = $this->oMain->_oDb->getMembershipById($iMembLevel);
		$aMembPriceInfo = $this->oMain->_oDb->getMembershipPriceInfo($iMembLevel);
		$aMembership = array_merge($aMembInfo, $aMembPriceInfo);

		// set startdate after first membership interval
		if($aMembership['Unit'] == 'Days'){
			$time = mktime(0,0,0,date("m"),date("d")+$aMembership['Length'],date("Y"));
		}
		if($aMembership['Unit'] == 'Months'){
			$time = mktime(0,0,0,date("m")+$aMembership['Length'],date("d"),date("Y"));
		}
		$sStartDate = date('Y-m-d', $time);

		// setup ARB api call
		$sContent =
		        "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
		        "<ARBCreateSubscriptionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
		        "<merchantAuthentication>".
		        "<name>" . $this->oMain->_oDb->getSetting('an_login') . "</name>".
		        "<transactionKey>" . $this->oMain->_oDb->getSetting('an_transkey') . "</transactionKey>".
		        "</merchantAuthentication>".
				"<refId>" . $aVars['cust_id'] . "</refId>".
		        "<subscription>".
		        "<name>" . $aMembership['Name'] . " " . _t('_dol_subs_membership') . "</name>".
		        "<paymentSchedule>".
		        "<interval>".
		        "<length>". $aMembership['Length'] ."</length>".
		        "<unit>". strtolower($aMembership['Unit']) ."</unit>".
		        "</interval>".
		        "<startDate>" . $sStartDate . "</startDate>".
		        "<totalOccurrences>9999</totalOccurrences>";

		if($aMembership['Trial'] == '1'){
			$sContent.=	"<trialOccurrences>1</trialOccurrences>";
		}
		$sContent.=
		        "</paymentSchedule>".
		        "<amount>". $aMembership['Price'] ."</amount>";
		if($aMembership['Trial'] == '1'){
			$sContent.=	"<trialAmount>0</trialAmount>";
		}
		$sContent.=
		        "<payment>".
		        "<creditCard>".
		        "<cardNumber>" . $aVars['cc_num'] . "</cardNumber>".
		        "<expirationDate>" . $aVars['cc_expy']."-".$aVars['cc_expm'] . "</expirationDate>".
		        "<cardCode>" . $aVars['cc_csv'] . "</cardCode>".
		        "</creditCard>".
		        "</payment>".
		        "<order>".
		        "<invoiceNumber>" . time() . "</invoiceNumber>".
				"<description>Full access to blogs, forums, chat, video chat.</description>".
		        "</order>".
		        "<customer>".
		        "<id>" . $iUserId . "</id>".
				"<email>" . $aVars['email'] . "</email>".
				"<phoneNumber>" . $aVars['phone'] . "</phoneNumber>".		
		        "</customer>".
		        "<billTo>".
			        "<firstName>". $aVars['fname'] . "</firstName>".
			        "<lastName>" . $aVars['lname']. "</lastName>".
			        "<address>" . $aVars['cc_address1']. "</address>".
			        "<city>" . $aVars['cc_city']. "</city>".
			        "<state>" . $aVars['cc_state']. "</state>".
			        "<zip>" . $aVars['cc_zip']. "</zip>".
			        "<country>" . $aVars['cc_country']. "</country>".
		        "</billTo>".
		        "</subscription>".
		        "</ARBCreateSubscriptionRequest>";

		// Run AIM initial payment before setting up subscription
		$initial_payment = $this->runInitialPayment($aVars);

		// if AIM payment successful
		if($initial_payment[0] == '1'){

			// setup ARB only if transaction is successful
			$sResponse = $this->send_request_via_curl($sHost,$sContent);
			if($sResponse){
				list ($sRefId, $iResultCode, $sResponseCode, $sResponseText, $sSubscriptionId) = $this->parse_return($sResponse);
				if($sResponseCode == 'I00001'){
					$aVars = array(
						'x_cust_id' => $sRefId,
						'x_trans_id' => $sSubscriptionId,
						'x_response_code' => '1',			
					);
					$this->processPayment($aVars);
					$sMsgBox = MsgBox('Payment Successful');
				}else{
					$sMsgBox = MsgBox($sResponseText);
				}
			}else{
				$sMsgBox = MsgBox("Transaction Failed.");
			}
		}else{
			$sMsgBox = MsgBox($initial_payment[3]);	
		}

			$aVars = array(
				'message' => $sMsgBox,
				/*'ref_id' => $sRefId,
				'res_code' => $iResultCode,
				'res_code_long' => $sResponseCode,
				'res_text' => $sResponseText,
				'sub_id' => $sSubscriptionId,*/
			);

		return $this->oMain->_oTemplate->parseHtmlByName('an_payment_response',$aVars);
	}

	/**
	 * Runs AIM api call to setup initial payment
	 * before creating an ARB
	 */
	function runInitialPayment($aVars){
		list($iUserId, $iMembLevel) = explode('_', $aVars['cust_id']);
		$aProfile = getProfileInfo($iUserId);
		$aMembInfo = $this->oMain->_oDb->getMembershipById($iMembLevel);
		$aMembPriceInfo = $this->oMain->_oDb->getMembershipPriceInfo($iMembLevel);
		$aMembership = array_merge($aMembInfo, $aMembPriceInfo);
		$sStartDate = date('Y-m-d', time());
		$cc_exp = $aVars['cc_expm'].trim($aVars['cc_expy'], '20');

		$post_url = "https://secure.authorize.net/gateway/transact.dll";
		
		$post_values = array(
			
			// the API Login ID and Transaction Key must be replaced with valid values
			"x_login"			=> $this->oMain->_oDb->getSetting('an_login'),
			"x_tran_key"		=> $this->oMain->_oDb->getSetting('an_transkey'),
		
			"x_version"			=> "3.1",
			"x_delim_data"		=> "TRUE",
			"x_delim_char"		=> "|",
			"x_relay_response"	=> "FALSE",
		
			"x_type"			=> "AUTH_CAPTURE",
			"x_method"			=> "CC",
			"x_card_num"		=> $aVars['cc_num'],
			"x_exp_date"		=> $cc_exp ,
		
			"x_amount"			=> ($aMembership['FirstPaymentPrice'] > 0) ? $aMembership['FirstPaymentPrice'] : $aMembership['Price'],
			"x_description"		=> "Membership Payment",
			"x_invoice_num"		=> time(),
		
			"x_cust_id"			=> $iUserId,

			"x_first_name"		=> $aVars['fname'],
			"x_last_name"		=> $aVars['lname'],
			"x_phone"			=> $aVars['phone'],
			"x_email"			=> $aVars['email'],
			"x_address"			=> $aVars['cc_address1'],
			"x_city"			=> $aVars['cc_city'],
			"x_state"			=> $aVars['cc_state'],
			"x_zip"				=> $aVars['cc_zip']
		);
		
		$post_string = "";
		foreach( $post_values as $key => $value )
			{ $post_string .= "$key=" . urlencode( $value ) . "&"; }
		$post_string = rtrim( $post_string, "& " );
		
		//echo $post_string;exit;
	
		$request = curl_init($post_url); 
			curl_setopt($request, CURLOPT_HEADER, 0); 
			curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); 
			curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); 
			$post_response = curl_exec($request); 
		curl_close ($request); 

		$response_array = explode($post_values["x_delim_char"],$post_response);
		
		return $response_array;

	}


	function getSimForm($iMembId){
		$aSettings = $this->oMain->_oDb->getSettings();
		$sUrl = $this->getSimUrl($aSettings);
		$sAnLogin = $aSettings['an_login'];
		$aMembInfo = $this->oMain->_oDb->getMembershipById($iMembId);
		$aMembPriceInfo = $this->oMain->_oDb->getMembershipPriceInfo($iMembId);
		$sTransactionKey = $aSettings['an_transkey'];
		$iSequence	= rand(1, 1000);
		$sTimeStamp	= time ();
		$sFingerPrint = $this->getFingerprint($sAnLogin,$sTransactionKey,$aMembPriceInfo['Price'],$iSequence,$sTimeStamp);
		$iInvoice	= date(YmdHis);
		$sForm = '<div id="auth_sim_button"><form method="post" action="' . $sUrl . '" />' . "\n"; 
		$sForm .= '<input type="hidden" name="x_login" value="' . $sAnLogin . '" />' . "\n"; 
	    $sForm .= '<input type="hidden" name="x_amount" value="' . $aMembPriceInfo['Price'] . '" />' . "\n";
		$sForm .= '<input type="hidden" name="x_description" value="' . $aMembInfo['Description'] . '" />' . "\n";
		$sForm .= '<input type="hidden" name="x_invoice_num" value="' . $iInvoice . '" />' . "\n";
		$sForm .= '<input type="hidden" name="x_method" value="CC" />' . "\n";
		$sForm .= '<input type="hidden" name="x_receipt_link_url" value="'.$this->oMain->_oTemplate->sBase.'" />' . "\n";
		$sForm .= '<input type="hidden" name="x_receipt_link_text" value="Return to '.getParam('site_title').'" />' . "\n";
		$sForm .= '<input type="hidden" name="x_receipt_link_method" value="LINK" />' . "\n";
  		if ($aSettings['an_test'] == '1')
			$sForm .= '<input type="hidden" name="x_test_request" value="TRUE" />' . "\n";
		$sForm .= '<input type="hidden" name="x_fp_sequence" value="' . $iSequence . '" />' . "\n";
		$sForm .= '<input type="hidden" name="x_fp_timestamp" value="' . $sTimeStamp . '" />' . "\n";
		$sForm .= '<input type="hidden" name="x_fp_hash" value="' . $sFingerPrint . '" />' . "\n";
		$sForm .= '<input type="hidden" name="x_cust_id" value="' . $_COOKIE['memberID'] . '_' . $iMembId . '" />' . "\n";
		$sForm .= '<input type="hidden" name="x_show_form" value="PAYMENT_FORM" />' . "\n";
		$sForm .= '<input type="submit" id="'.$iMembId.'" name="submit" value="Checkout"/>' . "\n"; 
        $sForm .= '</form></div>'; 

		$aVars = array(
			'code' => $sForm,
		);		
		return $this->oMain->_oTemplate->parseHtmlByName('authorize_sim',$aVars);	
	}
	function getArbForm($iMembId){
		$aSettings = $this->oMain->_oDb->getSettings();
		$sUrl = $this->getSimUrl($aSettings);
		$https = str_replace('http://','https://', BX_DOL_URL_ROOT);
		$sAnLogin = $aSettings['an_login'];
		$aMembInfo = $this->oMain->_oDb->getMembershipById($iMembId);
		$aMembPriceInfo = $this->oMain->_oDb->getMembershipPriceInfo($iMembId);
	    //$sForm = "<div id='auth_arb_button' onclick=\"javascript:showOrderForm('m/memberships/ajax/?action=an_order&mlevel=".$iMembId."');\">" . "\n"; 
	    $sForm = "<div id='auth_arb_button'>" . "\n";
		$sForm.= '<form id="'.$iMembId.'" method="post" action="" ><input type="submit" id="'.$iMembId.'" name="submit" value="Upgrade"';
		$sForm.= "onclick=\"javascript:location.href='".$https."m/memberships/order/?action=an_order&mlevel=".$iMembId."';return false\");\"";
		$sForm.= ' /></form></div>' . "\n"; 
		$aVars = array(
			'code' => $sForm,
			'mem_level' => $iMembId,
			'ajax_url' => $this->oMain->_oTemplate->sBase.'ajax/',
		);		
		return $this->oMain->_oTemplate->parseHtmlByName('authorize_arb',$aVars);
	}

	/* Curl/Request Functions
	----------------------------------------------------------------------------------*/
	function send_request_via_curl($host,$content){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $host);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($ch);
		return $response;
	}
	function parse_return($content){
		$refId = $this->substring_between($content,'<refId>','</refId>');
		$resultCode = $this->substring_between($content,'<resultCode>','</resultCode>');
		$code = $this->substring_between($content,'<code>','</code>');
		$text = $this->substring_between($content,'<text>','</text>');
		$subscriptionId = $this->substring_between($content,'<subscriptionId>','</subscriptionId>');
		return array ($refId, $resultCode, $code, $text, $subscriptionId);
	}
	function substring_between($haystack,$start,$end){
		if (strpos($haystack,$start) === false || strpos($haystack,$end) === false){
			return false;
		}else{
			$start_position = strpos($haystack,$start)+strlen($start);
			$end_position = strpos($haystack,$end);
			return substr($haystack,$start_position,$end_position-$start_position);
		}
	}	
	function getSimUrl($aSettings){
		$sTest = $aSettings['an_test'];
		switch ($sTest) {
		    case '1':
		        return 'https://test.authorize.net/gateway/transact.dll';
		        break;
		    case '0':
		        return 'https://secure.authorize.net/gateway/transact.dll';
		        break;
		}
	}
	function getArbUrl(){
		$sTest = $this->oMain->_oDb->getSetting('an_test');
		switch ($sTest) {
		    case '1':
		        return 'https://apitest.authorize.net/xml/v1/request.api';
		        break;
		    case '0':
		        return 'https://api.authorize.net/xml/v1/request.api';
		        break;
		}
	}
}

?>