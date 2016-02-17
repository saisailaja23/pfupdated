<?
/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                :  FEB 21 2010
*     copyright            : (C) 2010 8Mail / Ingo Kaps
*     website              : http://www.8Mail.de
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

require_once("BxPmtProvider.php");

define('au_MODE_LIVE', 1);

class BxPmtAuthorizeNet extends BxPmtProvider {
    var $_sName;
    var $_sCaption;
    var $_sPrefix;
    var $_sDataReturnUrl;
    var $_aOptions;    
    
	/**
	 * Constructor
	 */
	function BxPmtAuthorizeNet($oDb, $oConfig, $aConfig = array()) {
	    parent::BxPmtProvider($oDb, $oConfig);

	    $this->_bRedirectOnResult = false;

	    if(!empty($aConfig)) {
    	    $this->_sName = $aConfig['name'];
    	    $this->_sCaption = $aConfig['caption'];
    	    $this->_sPrefix = $aConfig['option_prefix'];
    	    $this->_sDataReturnUrl = $this->_oConfig->getDataReturnUrl() . $this->_sName . '/';
    	    $this->_aOptions = $aConfig['options'];
	    }	    
	}
 
	function needRedirect() {
	    return $this->_bRedirectOnResult;
	}
 
	function initializeCheckout($iPendingId, $aCartInfo, $bRecurring = false, $iRecurringDays = 0) {

        $iMode = (int)$this->_getOption('mode');
     
   		$aFormData = array('cmd' => '_xclick','amount' => sprintf( "%.2f", (float)$aCartInfo['items_price']));

        $returnurl1    = $this->_sDataReturnUrl;
     //   $returnurl1    = $this->_oConfig->getReturnUrl();
    	//$returnurl    = "http://www.hellovideo.tv/modules/boonex/payment/classes/return.php";
    //    $this->_sDataReturnUrl = $returnurl;
    
        if ($iMode == au_MODE_LIVE)
          {
            $testMode		= "false";
            $sActionURL = 'https://secure.authorize.net/gateway/transact.dll';
          }
        else
          {
            $testMode		= "true";
            $sActionURL = 'https://test.authorize.net/gateway/transact.dll';
          }
        
        
        $price        = sprintf( "%.2f", (float)$aCartInfo['items_price']);
        
        $referenceid  = rand(10000,99999);
        $referenceid  = $iPendingId;
        $waehrung     = $aCartInfo['vendor_currency_code'];
        
        $description  = md5($aCartInfo['vendor_id'] . $iPendingId);
        $description  = 'Test: ' . $aCartInfo['vendor_id'] . '/'.  $iPendingId;
        $description  = $returnurl1;
        $description  = $referenceid;
        $description  = '';
        
        

        $loginID		= $this->_getOption('pspid');;
        $transactionKey = $this->_getOption('transactionkey');
        
    //    echo "LoginID: $loginID <br />";
    //    echo "TransactionKey: $transactionKey <br />";
        $amount 		= $price;
        $description 	= _t('_payment_txt_payment_to') . ' ' . $aCartInfo['vendor_username'];
        $label 			= "Submit Payment"; // The is the label on the 'submit' button
        

        // an invoice is generated using the date and time
        $invoice	= date(YmdHis);
        // a sequence number is randomly generated
        $sequence	= rand(1, 1000);
        // a timestamp is generated
        $timeStamp	= time ();


        // The following lines generate the SIM fingerprint.  PHP versions 5.1.2 and
        // newer have the necessary hmac function built in.  For older versions, it
        // will try to use the mhash library.
        if( phpversion() >= '5.1.2' )
          {	$fingerprint = hash_hmac("md5", $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^", $transactionKey); }
        else
          { $fingerprint = bin2hex(mhash(MHASH_MD5, $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^", $transactionKey)); }

         // Print the Amount and Description to the screen.
     //    echo "Amount: $amount <br />";
     //    echo "Description: $description <br />";



        $aFormData = array_merge($aFormData, array(
           'x_login' => $loginID,
           "x_amount" => $amount,
           "x_description" => $description,
           'x_invoice_num' => $invoice,
           'x_fp_sequence' => $sequence,
           'x_fp_timestamp' => $timeStamp,
           'x_fp_hash' => $fingerprint,
           'x_po_num' => $iPendingId,
           'x_ipendingid' => $iPendingId,

           "x_method" => "CC",
           'x_version' => "3.1",
           'x_test_request' => $testMode,

           'x_receipt_link_method' => 'POST',
           'x_receipt_link_text' => 'Press here',
           'x_receipt_link_url' => $returnurl1,
           
           'x_relay_response' => 'TRUE',
           'x_relay_url' => $returnurl1,

           'x_show_form' => 'PAYMENT_FORM',
           "method" => "post"
        ));
    

         $this->logData('Posting Data to Authorize.net Payment', $aFormData,$sActionURL);
    	 Redirect($sActionURL, $aFormData, 'post', $this->_sCaption);
  //  print_r($aFormData);
  
 //      echo $sctionURL;
  
    	exit();
	}
   
 
	function finalizeCheckout(&$aData) {

   //      echo 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
   //      print_r($aData);
   //      echo '<br>';
   //      print_r($_POST);

       //  Redirect('http://8mail.de', $aFormData, 'post', $this->_sCaption);
  //       exit();
         

          
   //      $aResult = $this->_registerCheckout($aData);
   
   

         $this->logData('Posting Data from Authorize.net Payment', $aData,$this->_oConfig->getReturnUrl());
        // Redirect($this->_oConfig->getReturnUrl(),$aData, 'post', $this->_sCaption);
         
        // return $this->_registerCheckout($aData);
         if($aData['x_response_code'] == 1)
    		return $this->_registerCheckout($aData);

    	return array('code' => 2, 'message' => _t('_payment_err_wrong_data'));
         
   //       exit();
          

	} 
 
        function logData($Action, $aFormData,$ActionURL='')
        {
            global $dir;
            $qb_log_file = $dir['root']."log/paymentlogAuthorizeNet_".date('Y-m-d').".txt";
            ob_start();
            echo "\n  ============================= Payment Log =================\n ";
            echo " Action :".$Action."  \n";
            echo " Action URL:".$ActionURL."  \n";
            echo " Action Data  \n";
            print_r($aFormData);
            echo "\n";
            $out1 = ob_get_contents();
            ob_end_clean();
            $log = fopen($qb_log_file, 'a+') ;
            fwrite($log, $out1 );
            fclose($log);
        }
 
	
	function _registerCheckout(&$aData, $bSubscription = false, $iPendingId = 0) {

		$aResult = $this->_validateCheckout($aData);

        $iPendingId = $aData['x_ipendingid'];

        //--- Update pending transaction ---//
	    $this->_oDb->updatePending($iPendingId, array(
            'order' => $aData['x_po_num'],
            'error_code' => 0,
            'error_msg' => $aData['x_response_reason_text']
	    ));

	    $aResult['pending_id'] = $iPendingId;
		return $aResult;
    }
    
    
    function _validateCheckout(&$aData) {

        return array('code' => 1, 'message' => 'Successfully verified');
    }
    
    
	
	function _getOption($sName) {
	    return $this->_aOptions[$this->_sPrefix . $sName]['value'];
	}
}


?>
