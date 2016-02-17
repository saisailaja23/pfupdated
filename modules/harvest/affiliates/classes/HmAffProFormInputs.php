<?
/***************************************************************************
Dolphin Subscriptions v.2.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/
bx_import('BxDolModule');
bx_import('BxDolProfilesController');

class HmAffProFormInputs extends BxDolTwigTemplate {    
    function __construct(){
        $this->_oMain = $this->getMain();
		$this->_oDb = $this->_oMain->_oDb;
		$this->mBase = BX_DOL_URL_ROOT.'m/affiliates/';

	}
    function getMain() {
        return BxDolModule::getInstance('HmAffProModule');
    }
    function getPredefinedKeysArr( $sKey ) {
        global $aPreValues;
        
        if( substr( $sKey, 0, 2 ) == $this->sLinkPref )
            $sKey = substr( $sKey, 2 );
        
        return @array_keys( $aPreValues[$sKey] );
    }
	/*----------------------------------------------------------------------------------------------
																				REGISTRATION FORM 	
	-----------------------------------------------------------------------------------------------*/
	function getRegistrationInputs(){
        $aInputs = array(
			'account_info' => array(
                'type' => 'block_header',
                'caption' => _t('_dol_aff_reg_acc_info'),
                'collapsable' => false,
                'collapsed' => false
			),
          	'first_name' => array(
         		'type' => 'text',
        		'name' => 'first_name',
                'caption' => _t('_dol_aff_reg_fname'),
                'value' => '',
                'required' => true,
				'checker' => array (  
                    'func' => 'length', 
                    'params' => array(3,100),
	                'error' => _t('_dol_aff_err_min_chars'),
                ), 
            ),
          	'last_name' => array(
         		'type' => 'text',
        		'name' => 'last_name',
                'caption' => _t('_dol_aff_reg_lname'),
                'value' => '',
                'required' => true,
	            'checker' => array (
                   	'func' => 'avail',
                    'error' => _t('_dol_aff_err_min_chars'),
	            ),
            ),
            'user_email' => array(
                'type' => 'email',
                'name' => 'user_email',
                'required' => true, 
                'caption' => _t('_dol_aff_reg_email'),
				'value' =>'',
				'info' => '',
                'checker' => array (
                   	'func' => 'email',
                    'error' => _t('_dol_aff_err_email'),
                ),                   
            ),
			'mailing_address' => array(
                'type' => 'block_header',
                'caption' =>  _t('_dol_aff_reg_mailing_add'),
                'collapsable' => false,
                'collapsed' => false
			),
          	'address1' => array(
         		'type' => 'text',
        		'name' => 'address1',
                'caption' => _t('_dol_aff_reg_add1'),
                'value' => '',
                'required' => true,
	            'checker' => array (
                   	'func' => 'avail',
                    'error' => _t('_dol_aff_err_min_chars'),
	            ),
            ),
          	'address2' => array(
         		'type' => 'text',
        		'name' => 'address2',
                'caption' => _t('_dol_aff_reg_add2'),
                'value' => '',
            ),
          	'city' => array(
         		'type' => 'text',
        		'name' => 'city',
                'caption' => _t('_dol_aff_reg_city'),
                'value' => '',
                'required' => true,
	            'checker' => array (
                   	'func' => 'avail',
                    'error' => _t('_dol_aff_err_min_chars'),
	            ),
            ),
          	'state' => array(
         		'type' => 'text',
        		'name' => 'state',
                'caption' => _t('_dol_aff_reg_state'),
                'value' => '',
                'required' => true,
	            'checker' => array (
                   	'func' => 'avail',
                    'error' => _t('_dol_aff_err_min_chars'),
	            ),
            ),
          	'country' => array(
         		'type' => 'select',
        		'name' => 'country',
                'caption' => _t('_dol_aff_reg_country'),
                'values' => $this->_oMain->_oDb->getCountriesArray(),
                'required' => true,
	            'checker' => array (
                   	'func' => 'avail',
                    'error' => _t('_dol_aff_err_min_chars'),
	            ),
            ),
          	'zip' => array(
         		'type' => 'text',
        		'name' => 'zip',
                'caption' => _t('_dol_aff_reg_zip'),
                'values' => '',
                'required' => true,
	            'checker' => array (
                   	'func' => 'avail',
                    'error' => _t('_dol_aff_err_min_chars'),
	            ),
            ),
			'account_preferences' => array(
                'type' => 'block_header',
                'caption' => _t('_dol_aff_reg_acc_pref'),
                'collapsable' => false,
                'collapsed' => false
			),
          	'payout_preference' => array(
         		'type' => 'select',
        		'name' => 'payout_preference',
                'caption' => _t('_dol_aff_reg_payout_pref'),
                'values' => array('cheque'=> _t('_dol_aff_reg_cheque'), 'paypal'=> _t('_dol_aff_reg_paypal')),
                'required' => true,
	            'checker' => array (
                   	'func' => 'avail',
                    'error' => _t('_dol_aff_err_min_chars'),
	            ),
            ),
          	'paypal_email' => array(
         		'type' => 'email',
        		'name' => 'paypal_email',
                'caption' => _t('_dol_aff_reg_paypal_email'),
                'values' => '',
            ),
            'register_aff' => array(
                'type' => 'submit',
                'name' => 'register_aff',
                'value' => _t("_dol_aff_reg_register"),
            )
	
		);
		return $aInputs;
	}
	/*----------------------------------------------------------------------------------------------
																	EDIT AFFILIATE ACCOUNT FORM	
	-----------------------------------------------------------------------------------------------*/
	function getEditAccountInputs(){
		$iId = getLoggedId();
		$aAffiliateInfo = $this->_oMain->_oDb->getAffiliateInfo($iId);
        $aInputs = array(
			'account_info' => array(
                'type' => 'block_header',
                'caption' => _t('_dol_aff_reg_acc_info'),
                'collapsable' => false,
                'collapsed' => false
			),
          	'first_name' => array(
         		'type' => 'text',
        		'name' => 'first_name',
                'caption' => _t('_dol_aff_reg_fname'),
                'value' => $aAffiliateInfo['first_name'],
                'required' => true,
				'checker' => array (  
                    'func' => 'length', 
                    'params' => array(3,100),
	                'error' => _t('_dol_aff_err_min_chars'),
                ), 
            ),
          	'last_name' => array(
         		'type' => 'text',
        		'name' => 'last_name',
                'caption' => _t('_dol_aff_reg_lname'),
                'value' => $aAffiliateInfo['last_name'],
                'required' => true,
	            'checker' => array (
                   	'func' => 'avail',
                    'error' => _t('_dol_aff_err_min_chars'),
	            ),
            ),
            'user_email' => array(
                'type' => 'email',
                'name' => 'user_email',
                'required' => true, 
                'caption' => _t('_dol_aff_reg_email'),
				'value' => $aAffiliateInfo['user_email'],
				'info' => '',
                'checker' => array (
                   	'func' => 'email',
                    'error' => _t('_dol_aff_err_email'),
                ),                   
            ),
			'mailing_address' => array(
                'type' => 'block_header',
                'caption' => _t('_dol_aff_reg_mailing_add'),
                'collapsable' => false,
                'collapsed' => false
			),
          	'address1' => array(
         		'type' => 'text',
        		'name' => 'address1',
                'caption' => _t('_dol_aff_reg_add1'),
                'value' => $aAffiliateInfo['address1'],
                'required' => true,
	            'checker' => array (
                   	'func' => 'avail',
                    'error' => _t('_dol_aff_err_min_chars'),
	            ),
            ),
          	'address2' => array(
         		'type' => 'text',
        		'name' => 'address2',
                'caption' => _t('_dol_aff_reg_add2'),
                'value' => $aAffiliateInfo['address2'],
            ),
          	'city' => array(
         		'type' => 'text',
        		'name' => 'city',
                'caption' => _t('_dol_aff_reg_city'),
                'value' => $aAffiliateInfo['city'],
                'required' => true,
	            'checker' => array (
                   	'func' => 'avail',
                    'error' => _t('_dol_aff_err_min_chars'),
	            ),
            ),
          	'state' => array(
         		'type' => 'text',
        		'name' => 'state',
                'caption' => _t('_dol_aff_reg_state'),
                'value' => $aAffiliateInfo['state'],
                'required' => true,
	            'checker' => array (
                   	'func' => 'avail',
                    'error' => _t('_dol_aff_err_min_chars'),
	            ),
            ),
          	'country' => array(
         		'type' => 'select',
        		'name' => 'country',
                'caption' => _t('_dol_aff_reg_country'),
                'values' => $this->_oMain->_oDb->getCountriesArray(),
				'value' => $aAffiliateInfo['country'],
                'required' => true,
	            'checker' => array (
                   	'func' => 'avail',
                    'error' => _t('_dol_aff_err_min_chars'),
	            ),
            ),
          	'zip' => array(
         		'type' => 'text',
        		'name' => 'zip',
                'caption' => _t('_dol_aff_reg_zip'),
                'value' => $aAffiliateInfo['zip'],
                'required' => true,
	            'checker' => array (
                   	'func' => 'avail',
                    'error' => _t('_dol_aff_err_min_chars'),
	            ),
            ),
			'account_preferences' => array(
                'type' => 'block_header',
                'caption' => _t('_dol_aff_reg_acc_pref'),
                'collapsable' => false,
                'collapsed' => false
			),
          	'payout_preference' => array(
         		'type' => 'select',
        		'name' => 'payout_preference',
                'caption' => _t('_dol_aff_reg_payout_pref'),
                'values' => array('cheque'=> _t('_dol_aff_reg_cheque'), 'paypal'=> _t('_dol_aff_reg_paypal')),
				'value' => $aAffiliateInfo['payout_preference'],
                'required' => true,
	            'checker' => array (
                   	'func' => 'avail',
                    'error' => _t('_dol_aff_err_min_chars'),
	            ),
            ),
          	'paypal_email' => array(
         		'type' => 'email',
        		'name' => 'paypal_email',
                'caption' => _t('_dol_aff_reg_paypal_email'),
                'value' => $aAffiliateInfo['paypal_email'],
            ),
            'edit_aff' => array(
                'type' => 'submit',
                'name' => 'edit_aff',
                'value' => _t("_dol_aff_reg_save"),
            )
	
		);
		return $aInputs;
	}
	/*----------------------------------------------------------------------------------------------
																	ADMIN CREATE AFFILIATE FORM	
	-----------------------------------------------------------------------------------------------*/	
	function getCreateAffiliateInputs(){
        $aInputs = array(
			'userinfo' => array(
                'type' => 'block_header',
                'caption' => _t('_dol_aff_reg_user_info'),
                'collapsable' => false,
                'collapsed' => false
			),
          	'username' => array(
         		'type' => 'text',
        		'name' => 'username',
                'caption' => _t('_dol_aff_reg_username'),
				'info' => _t('_dol_aff_reg_username_info'),
                'required' => true,
				'checker' => array (  
                    'func' => 'length', 
                    'params' => array(3,100),
	                'error' => _t('_dol_aff_err_min_chars'),
                ), 
				'attrs' => array('id' => 'username'),
            ),
			'account_info' => array(
                'type' => 'block_header',
                'caption' => _t('_dol_aff_reg_acc_info'),
                'collapsable' => false,
                'collapsed' => false
			),
          	'first_name' => array(
         		'type' => 'text',
        		'name' => 'first_name',
                'caption' => _t('_dol_aff_reg_fname'),
                'value' => $aAffiliateInfo['first_name'],
                'required' => true,
				'checker' => array (  
                    'func' => 'length', 
                    'params' => array(3,100),
	                'error' => _t('_dol_aff_err_min_chars'),
                ), 
            ),
          	'last_name' => array(
         		'type' => 'text',
        		'name' => 'last_name',
                'caption' => _t('_dol_aff_reg_lname'),
                'value' => $aAffiliateInfo['last_name'],
                'required' => true,
	            'checker' => array (
                   	'func' => 'avail',
                    'error' => _t('_dol_aff_err_min_chars'),
	            ),
            ),
            'user_email' => array(
                'type' => 'email',
                'name' => 'user_email',
                'required' => true, 
                'caption' => _t('_dol_aff_reg_email'),
				'value' => $aAffiliateInfo['user_email'],
				'info' => '',
                'checker' => array (
                   	'func' => 'email',
                    'error' => _t('_dol_aff_err_email'),
                ),                   
            ),
			'mailing_address' => array(
                'type' => 'block_header',
                'caption' => _t('_dol_aff_reg_mailing_add'),
                'collapsable' => false,
                'collapsed' => false
			),
          	'address1' => array(
         		'type' => 'text',
        		'name' => 'address1',
                'caption' => _t('_dol_aff_reg_add1'),
                'value' => $aAffiliateInfo['address1'],
                'required' => true,
	            'checker' => array (
                   	'func' => 'avail',
                    'error' => _t('_dol_aff_err_min_chars'),
	            ),
            ),
          	'address2' => array(
         		'type' => 'text',
        		'name' => 'address2',
                'caption' => _t('_dol_aff_reg_add2'),
                'value' => $aAffiliateInfo['address2'],
            ),
          	'city' => array(
         		'type' => 'text',
        		'name' => 'city',
                'caption' => _t('_dol_aff_reg_city'),
                'value' => $aAffiliateInfo['city'],
                'required' => true,
	            'checker' => array (
                   	'func' => 'avail',
                    'error' => _t('_dol_aff_err_min_chars'),
	            ),
            ),
          	'state' => array(
         		'type' => 'text',
        		'name' => 'state',
                'caption' => _t('_dol_aff_reg_state'),
                'value' => $aAffiliateInfo['state'],
                'required' => true,
	            'checker' => array (
                   	'func' => 'avail',
                    'error' => _t('_dol_aff_err_min_chars'),
	            ),
            ),
          	'country' => array(
         		'type' => 'select',
        		'name' => 'country',
                'caption' => _t('_dol_aff_reg_country'),
                'values' => $this->_oMain->_oDb->getCountriesArray(),
				'value' => $aAffiliateInfo['country'],
                'required' => true,
	            'checker' => array (
                   	'func' => 'avail',
                    'error' => _t('_dol_aff_err_min_chars'),
	            ),
            ),
          	'zip' => array(
         		'type' => 'text',
        		'name' => 'zip',
                'caption' => _t('_dol_aff_reg_zip'),
                'value' => $aAffiliateInfo['zip'],
                'required' => true,
	            'checker' => array (
                   	'func' => 'avail',
                    'error' => _t('_dol_aff_err_min_chars'),
	            ),
            ),
			'account_preferences' => array(
                'type' => 'block_header',
                'caption' => _t('_dol_aff_reg_acc_pref'),
                'collapsable' => false,
                'collapsed' => false
			),
          	'payout_preference' => array(
         		'type' => 'select',
        		'name' => 'payout_preference',
                'caption' => _t('_dol_aff_reg_payout_pref'),
                'values' => array('cheque'=> _t('_dol_aff_reg_cheque'), 'paypal'=> _t('_dol_aff_reg_paypal')),
				'value' => $aAffiliateInfo['payout_preference'],
                'required' => true,
	            'checker' => array (
                   	'func' => 'avail',
                    'error' => _t('_dol_aff_err_min_chars'),
	            ),
            ),
          	'paypal_email' => array(
         		'type' => 'email',
        		'name' => 'paypal_email',
                'caption' => _t('_dol_aff_reg_paypal_email'),
                'value' => $aAffiliateInfo['paypal_email'],
            ),
            'create_affiliate' => array(
                'type' => 'submit',
                'name' => 'create_affiliate',
                'value' => _t("_dol_aff_reg_create"),
            )
	
		);
		return $aInputs;
	}
	/*----------------------------------------------------------------------------------------------
																	ADMIN CREATE CAMPAIGN FORM	
	-----------------------------------------------------------------------------------------------*/	
	function getCreateCampaignInputs(){
		$sCur = getParam('currency_sign');
        $aInputs = array(
			'campaign_info' => array(
                'type' => 'block_header',
                'caption' => _t('_dol_aff_adm_campaign_info'),
                'collapsable' => false,
                'collapsed' => false
			),
          	'campaign_name' => array(
         		'type' => 'text',
        		'name' => 'campaign_name',
                'caption' => _t('_dol_aff_adm_campaign_name'),
                'required' => true,
				'checker' => array (  
                    'func' => 'length', 
                    'params' => array(3,100),
	                'error' => _t('_dol_aff_err_min_chars'),
                ), 
            ),			
          	'campaign_status' => array(
         		'type' => 'select',
        		'name' => 'campaign_status',
                'caption' => _t('_dol_aff_adm_status'),
                'values' => array('active'=> 'Active', 'inactive'=> 'Inactive'),
				'value' => '',
				'info' => _t('_dol_aff_adm_status_info'),
            ),
			'click_header' => array(
                'type' => 'block_header',
                'caption' => _t('_dol_aff_adm_click_commission'),
                'collapsable' => true,
                'collapsed' => true
			),
          	'click_commission' => array(
         		'type' => 'select',
        		'name' => 'click_commission',
                'caption' => _t('_dol_aff_adm_enable_click_commission'),
                'values' => array('disabled'=> 'Disabled','enabled'=> 'Enabled'),
				'value' => '',
				'info' => _t('_dol_aff_adm_click_info'),
            ),
          	'click_amount' => array(
         		'type' => 'text',
        		'name' => 'click_amount',
                'caption' => _t('_dol_aff_adm_per_click').' '.$sCur,
				'value' => '',
				'info' => _t('_dol_aff_adm_per_click_info'),
            ),
			'click_end' => array(
				'type' => 'block_end',
			),
			'join_header' => array(
                'type' => 'block_header',
                'caption' => _t('_dol_aff_adm_join_commission'),
                'collapsable' => true,
                'collapsed' => true
			),
          	'join_commission' => array(
         		'type' => 'select',
        		'name' => 'join_commission',
                'caption' => _t('_dol_aff_adm_enable_join_commission'),
                'values' => array('disabled'=> 'Disabled','enabled'=> 'Enabled'),
				'value' => '',
				'info' => _t('_dol_aff_adm_join_commission_info'),
            ),
          	'join_amount' => array(
         		'type' => 'text',
        		'name' => 'join_amount',
                'caption' => _t('_dol_aff_adm_join_referral').' '.$sCur,
				'value' => '',
				'info' => _t('_dol_aff_adm_join_referral_info'),
            ),
			'join_end' => array(
				'type' => 'block_end',
			),
			'membership_header' => array(
                'type' => 'block_header',
                'caption' => _t('_dol_aff_adm_mem_comm'),
                'collapsable' => true,
                'collapsed' => true
			),
          	'membership_commission' => array(
         		'type' => 'select',
        		'name' => 'membership_commission',
                'caption' => _t('_dol_aff_adm_mem_comm'),
                'values' => array('disabled'=> 'Disabled', 'fixed'=> 'Fixed Commission','percentage'=> 'Percentage Commission'),
				'value' => '',
				'info' =>  _t('_dol_aff_adm_mem_comm_info'),
            ),
          	'membership_amount' => array(
         		'type' => 'text',
        		'name' => 'membership_amount',
                'caption' => _t('_dol_aff_adm_mem_comm_ref'),
				'value' => '',
				'info' => _t('_dol_aff_adm_mem_comm_ref_info'),
            ),
			'membership_end' => array(
				'type' => 'block_end',
			),
            'create_campaign' => array(
                'type' => 'submit',
                'name' => 'create_campaign',
                'value' => _t("_dol_aff_reg_create"),
            ),
		);
		return $aInputs;
	}
	/*----------------------------------------------------------------------------------------------
																	ADMIN EDIT CAMPAIGN FORM	
	-----------------------------------------------------------------------------------------------*/	
	function getEditCampaignInputs($aCampaignInfo){
		$sCur = getParam('currency_sign');
        $aInputs = array(
			'campaign_info' => array(
                'type' => 'block_header',
                'caption' => _t('_dol_aff_adm_campaign_info'),
                'collapsable' => false,
                'collapsed' => false
			),
          	'campaign_name' => array(
         		'type' => 'text',
        		'name' => 'campaign_name',
                'caption' => _t('_dol_aff_adm_campaign_name'),
				'value' => $aCampaignInfo['name'],
                'required' => true,
				'checker' => array (  
                    'func' => 'length', 
                    'params' => array(3,100),
	                'error' => _t('_dol_aff_err_min_chars'),
                ), 
            ),			
          	'campaign_status' => array(
         		'type' => 'select',
        		'name' => 'campaign_status',
                'caption' => _t('_dol_aff_adm_status'),
                'values' => array('active'=> 'Active', 'inactive'=> 'Inactive'),
				'value' => $aCampaignInfo['status'],
				'info' => _t('_dol_aff_adm_status_info'),
            ),
			'click_header' => array(
                'type' => 'block_header',
                'caption' => _t('_dol_aff_adm_click_commission'),
                'collapsable' => true,
                'collapsed' => true
			),
          	'click_commission' => array(
         		'type' => 'select',
        		'name' => 'click_commission',
                'caption' => _t('_dol_aff_adm_enable_click_commission'),
                'values' => array('disabled'=> 'Disabled','enabled'=> 'Enabled'),
				'value' => $aCampaignInfo['click_commission'],
				'info' => _t('_dol_aff_adm_click_info'),
            ),
          	'click_amount' => array(
         		'type' => 'text',
        		'name' => 'click_amount',
                'caption' => _t('_dol_aff_adm_per_click').' '.$sCur,
				'value' => $aCampaignInfo['click_amount'],
				'info' => _t('_dol_aff_adm_per_click_info'),
            ),
			'click_end' => array(
				'type' => 'block_end',
			),
			'join_header' => array(
                'type' => 'block_header',
                'caption' => _t('_dol_aff_adm_join_commission'),
                'collapsable' => true,
                'collapsed' => true
			),
          	'join_commission' => array(
         		'type' => 'select',
        		'name' => 'join_commission',
                'caption' => _t('_dol_aff_adm_enable_join_commission'),
                'values' => array('disabled'=> 'Disabled','enabled'=> 'Enabled'),
				'value' => $aCampaignInfo['join_commission'],
				'info' => _t('_dol_aff_adm_join_commission_info'),
            ),
          	'join_amount' => array(
         		'type' => 'text',
        		'name' => 'join_amount',
                'caption' => _t('_dol_aff_adm_join_referral').' '.$sCur,
				'value' => $aCampaignInfo['join_amount'],
				'info' => _t('_dol_aff_adm_join_referral_info'),
            ),
			'join_end' => array(
				'type' => 'block_end',
			),
			'membership_header' => array(
                'type' => 'block_header',
                'caption' => _t('_dol_aff_adm_mem_comm'),
                'collapsable' => true,
                'collapsed' => true
			),
          	'membership_commission' => array(
         		'type' => 'select',
        		'name' => 'membership_commission',
                'caption' => _t('_dol_aff_adm_mem_comm'),
                'values' => array('disabled'=> 'Disabled', 'fixed'=> 'Fixed Commission','percentage'=> 'Percentage Commission'),
				'value' => $aCampaignInfo['membership_commission'],
				'info' =>  _t('_dol_aff_adm_mem_comm_info'),
            ),
          	'membership_amount' => array(
         		'type' => 'text',
        		'name' => 'membership_amount',
                'caption' => _t('_dol_aff_adm_mem_comm_ref'),
				'value' => $aCampaignInfo['membership_amount'],
				'info' => _t('_dol_aff_adm_mem_comm_ref_info'),
            ),
			'membership_end' => array(
				'type' => 'block_end',
			),
            'edit_campaign' => array(
                'type' => 'submit',
                'name' => 'edit_campaign',
                'value' => _t("_dol_aff_reg_update"),
            ),
		);
		return $aInputs;
	}
	/*----------------------------------------------------------------------------------------------
																	ADMIN CREATE TEXT LINK FORM	
	-----------------------------------------------------------------------------------------------*/
	function getCreateTextLinkInputs(){
		$aCampaigns = $this->_oMain->_oDb->getCampaignsArray();
		$sNoActiveCampaigns = 
        $aInputs = array(
          	'link_name' => array(
         		'type' => 'text',
        		'name' => 'link_name',
                'caption' => _t('_dol_aff_adm_name'),
                'required' => true,
				'attrs' => array('id' => 'link_name'),
            ),			
          	'link_hidden' => array(
         		'type' => 'checkbox',
        		'name' => 'link_hidden',
                'caption' =>  _t('_dol_aff_adm_hidden'),
				'value' => '1',
				'info' => _t('_dol_aff_adm_hidden_info'),
            ),
          	'link_campaign' => array(
         		'type' => 'select',
        		'name' => 'link_campaign',
                'caption' => _t('_dol_aff_adm_campaign'),
                'values' => $aCampaigns = (is_array($aCampaigns)) ? $aCampaigns : array('-1'=> _t('_dol_aff_adm_no_campaigns')),
				'value' => '',
				'info' => _t('_dol_aff_adm_campaign_info'),
            ),
          	'link_target' => array(
         		'type' => 'text',
        		'name' => 'link_target',
                'caption' => _t('_dol_aff_adm_dest_url'),
				'attrs' => array('id' => 'link_target'),
				'info' => _t('_dol_aff_adm_dest_url_info'),
                'required' => true,
            ),
          	'link_title' => array(
         		'type' => 'text',
        		'name' => 'link_title',
                'caption' => _t('_dol_aff_adm_title'),
				'attrs' => array('id' => 'link_title'),
                'required' => true,
				'value' => _t('_dol_aff_adm_preview_title'),
            ),
          	'link_details' => array(
         		'type' => 'text',
        		'name' => 'link_details',
                'caption' => _t('_dol_aff_adm_details'),
				'attrs' => array('id' => 'link_details'),
            ),
            'create_text_link' => array(
                'type' => 'submit',
                'name' => 'create_text_link',
                'value' => _t("_dol_aff_reg_create"),
            ),
		);
		return $aInputs;
	}
	/*----------------------------------------------------------------------------------------------
																	ADMIN CREATE IMAGE BANNER FORM	
	-----------------------------------------------------------------------------------------------*/
	function getCreateImageBannerInputs(){
		$aCampaigns = $this->_oMain->_oDb->getCampaignsArray();
		$sBannerPreviewHtml = '<img id="previewImg" src="'.BX_DOL_URL_MODULES.'harvest/affiliates/images/default.png" alt="" />';
        $aInputs = array(
          	'image_name' => array(
         		'type' => 'text',
        		'name' => 'image_name',
                'caption' => _t('_dol_aff_adm_name'),
                'required' => true,
				'attrs' => array('id' => 'image_name'),
            ),			
          	'image_hidden' => array(
         		'type' => 'checkbox',
        		'name' => 'image_hidden',
                'caption' => _t('_dol_aff_adm_hidden'),
				'value' => '1',
				'info' => _t('_dol_aff_adm_hidden_info'),
            ),
          	'image_campaign' => array(
         		'type' => 'select',
        		'name' => 'image_campaign',
                'caption' => _t('_dol_aff_adm_campaign'),
                'values' => $aCampaigns = (is_array($aCampaigns)) ? $aCampaigns : array('-1'=> _t('_dol_aff_adm_no_campaigns')),
				'value' => '',
				'info' => _t('_dol_aff_adm_campaign_info'),
            ),
          	'image_target' => array(
         		'type' => 'text',
        		'name' => 'image_target',
                'caption' => _t('_dol_aff_adm_dest_url'),
				'value' => '',
				'info' => _t('_dol_aff_adm_dest_url_info'),
                'required' => true,
				'attrs' => array('id' => 'image_target'),
            ),
            'upload_image' => array(
                'type' => 'file',
                'name' => 'upload_image',
                'caption' => _t('_dol_aff_adm_upload_banner'),
                'value' => '',
				'attrs' => array('onchange' => 'preview(this)', 'id'=> 'upload_image'),
            ),
            'content_text' => array(
                'type' => 'custom',
                'caption' => _t('_dol_aff_adm_upload_preview'),
                'content' => $sBannerPreviewHtml,
            ),
            'create_text_link' => array(
                'type' => 'submit',
                'name' => 'create_text_link',
                'value' => _t("_dol_aff_reg_create"),
            ),
		);
		return $aInputs;

	}
	/*----------------------------------------------------------------------------------------------
																	ADMIN CREATE FLASH BANNER FORM	
	-----------------------------------------------------------------------------------------------*/
	function getCreateFlashBannerInputs(){
		$aCampaigns = $this->_oMain->_oDb->getCampaignsArray();
        $aInputs = array(
          	'flash_name' => array(
         		'type' => 'text',
        		'name' => 'flash_name',
                'caption' => _t('_dol_aff_adm_name'),
                'required' => true,
				'attrs' => array('id' => 'flash_name'),
            ),			
          	'flash_hidden' => array(
         		'type' => 'checkbox',
        		'name' => 'flash_hidden',
                'caption' => _t('_dol_aff_adm_hidden'),
				'value' => '1',
				'info' => _t('_dol_aff_adm_hidden_info'),
            ),
          	'flash_campaign' => array(
         		'type' => 'select',
        		'name' => 'flash_campaign',
                'caption' => _t('_dol_aff_adm_campaign'),
                'values' => $aCampaigns = (is_array($aCampaigns)) ? $aCampaigns : array('-1'=> _t('_dol_aff_adm_no_campaigns')),
				'value' => '',
				'info' => _t('_dol_aff_adm_campaign_info'),
            ),
          	'flash_target' => array(
         		'type' => 'text',
        		'name' => 'flash_target',
                'caption' => _t('_dol_aff_adm_dest_url'),
				'attrs' => array('id' => 'flash_target'),
				'info' => _t('_dol_aff_adm_dest_url_info'),
                'required' => true,
            ),
            'upload_flash' => array(
                'type' => 'file',
                'name' => 'upload_flash',
                'caption' => _t('_dol_aff_adm_upload_swf_banner'),
				'attrs' => array('id' => 'upload_flash'),
            ),
            'create_flash_banner' => array(
                'type' => 'submit',
                'name' => 'create_flash_banner',
                'value' => _t("_dol_aff_reg_create"),
            ),
		);
		return $aInputs;
	}
	/*----------------------------------------------------------------------------------------------
																	ADMIN EDIT BANNER HANDLER	
	-----------------------------------------------------------------------------------------------*/
	function getEditBannerInputs($aBannerInfo){
		if($aBannerInfo['type'] == 'text'){
			return $this->getEditTextBannerInputs($aBannerInfo);
		}
		if($aBannerInfo['type'] == 'image'){
			return $this->getEditImageBannerInputs($aBannerInfo);
		}
		if($aBannerInfo['type'] == 'flash'){
			return $this->getEditFlashBannerInputs($aBannerInfo);
		}
	}
	/*----------------------------------------------------------------------------------------------
																	ADMIN EDIT TEXT BANNER INPUTS	
	-----------------------------------------------------------------------------------------------*/
	function getEditTextBannerInputs($aBannerInfo){
		$aCampaigns = $this->_oMain->_oDb->getCampaignsArray();
		$sChecked = ($aBannerInfo['hidden'] == '1') ? true : false;
        $aInputs = array(
          	'link_name' => array(
         		'type' => 'text',
        		'name' => 'link_name',
                'caption' => _t('_dol_aff_adm_name'),
                'required' => true,
				'value' => $aBannerInfo['name'],
				'attrs' => array('id' => 'link_name'),
				'checker' => array (  
                    'func' => 'length', 
                    'params' => array(3,100),
	                'error' => _t('_dol_aff_err_min_chars'),
                ), 
            ),			
          	'link_hidden' => array(
         		'type' => 'checkbox',
        		'name' => 'link_hidden',
                'caption' => _t('_dol_aff_adm_hidden'),
				'value' => '1',
				'checked' => $sChecked,
				'info' => _t('_dol_aff_adn_hidden_info'),
            ),
          	'link_campaign' => array(
         		'type' => 'select',
        		'name' => 'link_campaign',
                'caption' => _t('_dol_aff_adm_campaign'),
                'values' => $aCampaigns = (is_array($aCampaigns)) ? $aCampaigns : array('-1'=> _t('_dol_aff_adn_no_campaigns')),
				'value' => $aBannerInfo['campaign_id'],
				'info' =>  _t('_dol_aff_adn_campaign_info'),
            ),
          	'link_target' => array(
         		'type' => 'text',
        		'name' => 'link_target',
                'caption' => _t('_dol_aff_adm_dest_url'),
				'attrs' => array('id' => 'link_target'),
				'value' => $aBannerInfo['target_url'],
				'info' => _t('_dol_aff_adm_dest_url_info'),
                'required' => true,
				'checker' => array (  
                    'func' => 'length', 
                    'params' => array(3,100),
	                'error' => _t('_dol_aff_err_min_chars'),
                ), 				
            ),
          	'link_title' => array(
         		'type' => 'text',
        		'name' => 'link_title',
                'caption' => _t('_dol_aff_adm_title'),
				'attrs' => array('id' => 'link_title'),
                'required' => true,
				'value' => $aBannerInfo['text_title'],
				'checker' => array (  
                    'func' => 'length', 
                    'params' => array(3,100),
	                'error' => _t('_dol_aff_err_min_chars'),
                ), 
            ),
          	'link_details' => array(
         		'type' => 'text',
        		'name' => 'link_details',
                'caption' => _t('_dol_aff_adm_details'),
				'attrs' => array('id' => 'link_details'),
				'value' => $aBannerInfo['text_details'],
            ),
            'edit_banner' => array(
                'type' => 'submit',
                'name' => 'edit_banner',
                'value' => _t("_dol_aff_reg_update"),
            ),
		);
		return $aInputs;
	}
	/*----------------------------------------------------------------------------------------------
																	ADMIN EDIT IMAGE BANNER INPUTS	
	-----------------------------------------------------------------------------------------------*/
	function getEditImageBannerInputs($aBannerInfo){
		$aCampaigns = $this->_oMain->_oDb->getCampaignsArray();
		$sBannerPreviewHtml = '<img id="previewImg" src="'.BX_DOL_URL_MODULES.'harvest/affiliates/images/banners/'.$aBannerInfo['filename'].'" alt="" />';
		$sChecked = ($aBannerInfo['hidden'] == '1') ? true : false;
        $aInputs = array(
          	'image_name' => array(
         		'type' => 'text',
        		'name' => 'image_name',
                'caption' => _t('_dol_aff_adm_name'),
                'required' => true,
				'attrs' => array('id' => 'image_name'),
				'value' => $aBannerInfo['name'],
				'checker' => array (  
                    'func' => 'length', 
                    'params' => array(3,100),
	                'error' => _t('_dol_aff_err_min_chars'),
                ),				
            ),			
          	'image_hidden' => array(
         		'type' => 'checkbox',
        		'name' => 'image_hidden',
                'caption' => _t('_dol_aff_adm_hidden'),
				'value' => '1',
				'checked' => $sChecked,
				'info' => _t('_dol_aff_adm_hidden_info'),
            ),
          	'image_campaign' => array(
         		'type' => 'select',
        		'name' => 'image_campaign',
                'caption' => _t('_dol_aff_adm_campaign'),
                'values' => $aCampaigns = (is_array($aCampaigns)) ? $aCampaigns : array('-1'=> _t('_dol_aff_adm_no_campaigns')),
				'value' => $aBannerInfo['campaign_id'],
				'info' => _t('_dol_aff_adm_campaign_info'),
            ),
          	'image_target' => array(
         		'type' => 'text',
        		'name' => 'image_target',
                'caption' => _t('_dol_aff_adm_dest_url'),
				'value' => $aBannerInfo['target_url'],
				'info' => _t('_dol_aff_adm_dest_url_info'),
                'required' => true,
				'attrs' => array('id' => 'image_target'),
				'checker' => array (  
                    'func' => 'length', 
                    'params' => array(3,100),
	                'error' => _t('_dol_aff_err_min_chars'),
                ),
            ),
            'upload_image' => array(
                'type' => 'file',
                'name' => 'upload_image',
                'caption' => _t('_dol_aff_adm_upload_banner'),
                'value' => '',
				'attrs' => array('onchange' => 'preview(this)', 'id'=> 'upload_image'),
            ),
            'content_text' => array(
                'type' => 'custom',
                'caption' => _t('_dol_aff_adm_upload_preview'),
                'content' => $sBannerPreviewHtml,
            ),
            'edit_banner' => array(
                'type' => 'submit',
                'name' => 'edit_banner',
                'value' => _t("_dol_aff_reg_update"),
            ),
		);
		return $aInputs;
	}
	/*----------------------------------------------------------------------------------------------
																	ADMIN EDIT IMAGE BANNER INPUTS	
	-----------------------------------------------------------------------------------------------*/
	function getEditFlashBannerInputs($aBannerInfo){
		$aCampaigns = $this->_oMain->_oDb->getCampaignsArray();
		$sChecked = ($aBannerInfo['hidden'] == '1') ? true : false;
        $aInputs = array(
          	'flash_name' => array(
         		'type' => 'text',
        		'name' => 'flash_name',
                'caption' => _t('_dol_aff_adm_name'),
                'required' => true,
				'attrs' => array('id' => 'flash_name'),
				'value' => $aBannerInfo['name'],
				'checker' => array (  
                    'func' => 'length', 
                    'params' => array(3,100),
	                'error' => _t('_dol_aff_err_min_chars'),
                ),				
            ),			
          	'flash_hidden' => array(
         		'type' => 'checkbox',
        		'name' => 'flash_hidden',
                'caption' => _t('_dol_aff_adm_hidden'),
				'value' => '1',
				'checked' => $sChecked,
				'info' => _t('_dol_aff_adm_hidden_info'),
            ),
          	'flash_campaign' => array(
         		'type' => 'select',
        		'name' => 'flash_campaign',
                'caption' => _t('_dol_aff_adm_campaign'),
                'values' => $aCampaigns = (is_array($aCampaigns)) ? $aCampaigns : array('-1'=> _t('_dol_aff_adm_no_campaigns')),
				'value' => $aBannerInfo['campaign_id'],
				'info' => _t('_dol_aff_adm_campaign_info'),
            ),
          	'flash_target' => array(
         		'type' => 'text',
        		'name' => 'flash_target',
                'caption' => _t('_dol_aff_adm_dest_url'),
				'value' => $aBannerInfo['target_url'],
				'info' => _t('_dol_aff_adm_dest_url_info'),
                'required' => true,
				'attrs' => array('id' => 'flash_target'),
				'checker' => array (  
                    'func' => 'length', 
                    'params' => array(3,100),
	                'error' => _t('_dol_aff_err_min_chars'),
                ),
            ),
            'upload_flash' => array(
                'type' => 'file',
                'name' => 'upload_flash',
                'caption' => _t('_dol_aff_adm_upload_swf_banner'),
                'value' => '',
				'attrs' => array('onchange' => 'preview(this)', 'id'=> 'upload_flash'),
            ),
            'edit_banner' => array(
                'type' => 'submit',
                'name' => 'edit_banner',
                'value' => _t("_dol_aff_reg_update"),
            ),
		);
		return $aInputs;
	}
	/*----------------------------------------------------------------------------------------------
														ADMIN MANAGE AFFILIATE CAMPAIGNS INPUTS	
	-----------------------------------------------------------------------------------------------*/
	function getManageCampaignsInputs($iAid){
		$aAffiliateCampaigns = $this->_oDb->getAffiliateCampaigns($iAid);
		$aCampaigns = $this->_oDb->getCampaignsArray();
        $aInputs = array(
			'mc_info' => array(
                'type' => 'block_header',
                'caption' => _t('_dol_aff_check_to_enable'),
                'collapsable' => false,
                'collapsed' => false
			),
	        'campaign_list' => array(
                'type' => 'checkbox_set',
                'name' => 'campaign_ids',
                'caption' => '',
				'value' => $aAffiliateCampaigns,
				'values' => $aCampaigns,
			),
            'manage_campaign_btn' => array(
                'type' => 'submit',
                'name' => 'manage_campaign_btn',
                'value' => _t("_dol_aff_reg_update"),
            ),
		);
		return $aInputs;
	}
	/*----------------------------------------------------------------------------------------------
														FRONTEND AFFILIATE APPLICATION INPUTS	
	-----------------------------------------------------------------------------------------------*/
	function getAffiliateApplicationInputs(){
		     $aInputs = array(
				'name' => array(
					'type' => 'text',
					'name' => 'name',
					'caption' => _t('_Your name'),
					'required' => true,
		    	    'checker' => array(
		                'func' => 'length',
		    			'params' => array(1, 150),
		                'error' => _t( '_Name is required' )
		            ),
				),
				'email' => array(
					'type' => 'text',
					'name' => 'email',
					'caption' => _t('_Your email'),
		            'required' => true,
		            'checker' => array(
		                'func' => 'email',
		                'error' => _t( '_Incorrect Email' )
		            ),
				),
				'message_subject' => array(
					'type' => 'text',
					'name' => 'subject',
					'caption' => _t('_message_subject'),
					'required' => true,
					'checker' => array(
		                'func' => 'length',
		    			'params' => array(5, 300),
		                'error' => _t( '_ps_ferr_incorrect_length' )
		            ),
				),
				'message_text' => array(
					'type' => 'textarea',
					'name' => 'body',
					'caption' => _t('_Message text'),
					'required' => true,
					'checker' => array(
		                'func' => 'length',
		    			'params' => array(10, 5000),
		                'error' => _t( '_ps_ferr_incorrect_length' )
		            ),
				),
				'captcha' => array(
					'type' => 'captcha',
					'caption' => _t('_Enter what you see:'),
					'name' => 'securityImageValue',
		            'required' => true,
		            'checker' => array(
		                'func' => 'captcha',
		                'error' => _t( '_Incorrect Captcha' ),
		            ),
				),
				'submit' => array(
					'type' => 'submit',
					'name' => 'do_submit',
					'value' => _t('_Submit'),
				),
			);
		return $aInputs;
	}
}
?>