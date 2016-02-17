<?
/***************************************************************************
Dolphin Subscriptions v.2.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/
bx_import('BxDolModule');
bx_import('BxDolProfilesController');

class HmAffProoFormInputs extends BxDolTwigTemplate {
    function __construct(){
        $this->_oMain = $this->getMain();
		$this->_oDb = $this->_oMain->_oDb;
		$this->mBase = BX_DOL_URL_ROOT.'m/pdftemplates/';

	}
    function getMain() {
        return BxDolModule::getInstance('HmAffProoModule');
    }
    function getPredefinedKeysArr( $sKey ) {
        global $aPreValues;
        
        if( substr( $sKey, 0, 2 ) == $this->sLinkPref )
            $sKey = substr( $sKey, 2 );
        
        return @array_keys( $aPreValues[$sKey] );
    }
	
	
	/*----------------------------------------------------------------------------------------------
																	ADMIN CREATE CAMPAIGN FORM	
	-----------------------------------------------------------------------------------------------*/	
	function getCreateCampaignInputs(){

            $sStateUrl = BX_DOL_URL_ROOT . $this->_oMain->_oConfig->getBaseUri() . 'templates/?ajax=state&country=';
              // echo $sStateUrl;

               
             

            if($_GET['ajax']=='state') {
			$sCountryCode = $_GET['country'];
			echo $this->_oDb->getStateOptions($sCountryCode);
			exit;
		}
                
              $oProfileFields = new BxDolProfileFields(0);
              $aCountries = $oProfileFields->convertValues4Input('#!Country');
              asort($aCountries);

	     // $sSelCountry = $_POST['country'];

              $itemvalue = $_POST['country'];
              //echo $sSelCountry;
             // exit();
           //   $sSelState = ($_POST['state']) ? $_POST['state'] : '';
	   //   $aStates = $this->_oDb->getStateArray($sSelCountry);






              $sCur = getParam('currency_sign');

                $aMbs = $this->_oDb->getMemberships();
		foreach($aMbs as $aMemb){
			$ignoreArray = array(1,3);
			if (!in_array($aMemb['id'],$ignoreArray)){
				$aMemberships[$aMemb['id']] = $aMemb['title'];
			}
		}


               // $aCategory = $this->_oDb->getFormCategoryArray();
		//$aSubCategory = array();

        $aInputs = array(
		'campaign_info' => array(
                'type' => 'block_header',
               // 'caption' => _t('_dol_aff_adm_campaign_info'),
                'collapsable' => false,
                'collapsed' => false
			),
          	'campaign_name' => array(
         		'type' => 'text',
        		'name' => 'campaign_name',
                'caption' => _t('Template Name'),
                'required' => true,
				'checker' => array (  
                    'func' => 'length', 
                    'params' => array(3,100),
	                'error' => _t('Please enter template name'),
                ), 
            ),			
             /*      'campaign_status1' => array(
         	   'type' => 'select',
                   'name' => 'campaign_status1',
                   'caption' => _t('Type'),
                   'values' => array('global'=> 'Global', 'agency'=> 'Agency'),
		   'value' => '',
	           'info' => _t('_dol_aff_adm_status_info'),
            ),
            'campaign_status' => array(
                'type' => 'select_multiple',
                'name' => 'campaign_status',
                'caption' => _t('Agency'),
                'values' => $aMemberships,
			//'value' => $this->_oDb->getSetting('default_memID'),
				'info' => _t('_dol_aff_adm_status_info'),
            ),
              */
            'country' => array(
                    'type' => 'select',
                    'name' => 'country',
					'listname' => 'Country',
                    'caption' => _t('Type'),
                  //  'values' => $aCountries,
 					//'value' => $sSelCountry,

                   'values' => array('global'=> 'Global', 'agency'=> 'Agency'),
		   'value' => $itemvalue,

					'attrs' => array(
						'onchange' => "getHtmlData('substate','$sStateUrl'+this.value)",
					),
                      'required' => true,
				'checker' => array (
                       	        'error' => _t('Please select type'),
                ),


                    'db' => array (
                        'pass' => 'Preg',
                        'params' => array('/([a-zA-Z]{2})/'),
                    ),
					'display' => 'getPreListDisplay',
                ),
				'state' => array(
					'type' => 'select_multiple',
					'name' => 'state',
					'value' => $sSelState,
					'values'=> $aStates,
					'caption' => _t('Agency'),
					'attrs' => array(
						'id' => 'substate',
					),
                                         
                                        
					'db' => array (
					'pass' => 'Preg',
					'params' => array('/([a-zA-Z]+)/'),
					),
					'display' => 'getStateName',
				), 

           'campaign_name1' => array(
         		'type' => 'textarea',
        		'name' => 'campaign_name1',
                'caption' => _t('Description'),
               
              //  'required' => true,
				//'checker' => array (
                   // 'func' => 'length',
                   // 'params' => array(3,100),
	            //    'error' => _t('sdfsdf'),
               // ),
            ),
                    'printmode' => array(
                    'type' => 'select',
                    'name' => 'printmode',
					'listname' => 'printmode',
                    'caption' => _t('Print mode'),
                  //  'values' => $aCountries,
 					//'value' => $sSelCountry,

                   'values' => array('P'=> 'Portrait', 'L'=> 'Landscape'),
		  // 'value' => $itemvalue,

					
                      'required' => true,
				'checker' => array (
                       	        'error' => _t('Please select print mode'),
                ),
                 
                ),
               'new_file' => array(
                'type' => 'file',
                'name' => 'new_file',
                'caption' => _t('Upload pdf template'),
                'value' => '',
                    //   'required' => true,
		    //   'checker' => array (
                   //    'func' => 'length',
                   //    'params' => array(3,100),
	           //    'error' => _t('Please upload pdf template'),
               // ),

                  
            ),
		/*	'click_header' => array(
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
			), */


            


            'create_campaign' => array(
                'type' => 'submit',
                'name' => 'create_campaign',
                'value' => _t("_Submit"),
            ),
		);

     







		return $aInputs;
	}
 
        /*----------------------------------------------------------------------------------------------
																	ADMIN EDIT CAMPAIGN FORM	
	-----------------------------------------------------------------------------------------------*/	
	function getEditCampaignInputs($aCampaignInfo){

         

             $tid = $aCampaignInfo['template_id'];
             $temtype = $aCampaignInfo['template_type'];
            

             $sStateUrl = BX_DOL_URL_ROOT . $this->_oMain->_oConfig->getBaseUri() . 'templates/?ajax=state&country=';
             //  echo $sStateUrl;exit();




           if($_GET['ajax']=='state') {
			$sCountryCode = $_GET['country'];
                       // echo $sCountryCode;exit();
			echo $this->_oDb->getStateOptions($sCountryCode);
			exit;
		}

             
       if($temtype == 'agency'){
	       $aStatess = $this->_oDb->getStateArray($temtype);

               $selects = $this->_oDb->getStateArrayy($tid);

       }
      

              $sCur = getParam('currency_sign');

                $aMbs = $this->_oDb->getMemberships();
		foreach($aMbs as $aMemb){
			$ignoreArray = array(1,3);
			if (!in_array($aMemb['id'],$ignoreArray)){
				$aMemberships[$aMemb['id']] = $aMemb['title'];
			}
		}


               // $aCategory = $this->_oDb->getFormCategoryArray();
		//$aSubCategory = array();

        $aInputs = array(
		'campaign_info' => array(
                'type' => 'block_header',
               // 'caption' => _t('_dol_aff_adm_campaign_info'),
                'collapsable' => false,
                'collapsed' => false
			),
          	
          	'campaign_name' => array(
         		'type' => 'text',
        		'name' => 'campaign_name',
                'caption' => _t('Template Name'),
				'value' => $aCampaignInfo['template_name'],
                'required' => true,
				'checker' => array (
                    'func' => 'length',
                    'params' => array(3,100),
	                'error' => _t('_dol_aff_err_min_chars'),
                ),
            ),
             /*      'campaign_status1' => array(
         	   'type' => 'select',
                   'name' => 'campaign_status1',
                   'caption' => _t('Type'),
                   'values' => array('global'=> 'Global', 'agency'=> 'Agency'),
		   'value' => '',
	           'info' => _t('_dol_aff_adm_status_info'),
            ),
            'campaign_status' => array(
                'type' => 'select_multiple',
                'name' => 'campaign_status',
                'caption' => _t('Agency'),
                'values' => $aMemberships,
			//'value' => $this->_oDb->getSetting('default_memID'),
				'info' => _t('_dol_aff_adm_status_info'),
            ),
              */
            'country' => array(
                    'type' => 'select',
                    'name' => 'country',
					'listname' => 'Country',
                    'caption' => _t('Type'),
                  //  'values' => $aCountries,
 					//'value' => $sSelCountry,

                   'values' => array('global'=> 'Global', 'agency'=> 'Agency'),
		   'value' => $aCampaignInfo['template_type'],

					'attrs' => array(
						'onchange' => "getHtmlData('substate','$sStateUrl'+this.value)",
					),
                    'required' => true,
                    'db' => array (
                        'pass' => 'Preg',
                        'params' => array('/([a-zA-Z]{2})/'),
                    ),
					'display' => 'getPreListDisplay',
                ),
				'state' => array(
					'type' => 'select_multiple',
					'name' => 'state',
					'value' => $selects,
					'values'=> $aStatess,
					'caption' => _t('Agency'),
					'attrs' => array(
						'id' => 'substate',
					),
					'db' => array (
					'pass' => 'Preg',
					'params' => array('/([a-zA-Z]+)/'),
					),
					'display' => 'getStateName',
				),


            'printmode' => array(
                    'type' => 'select',
                    'name' => 'printmode',
					'listname' => 'printmode',
                    'caption' => _t('Print mode'),
                  //  'values' => $aCountries,
 					//'value' => $sSelCountry,

                   'values' => array('P'=> 'Portrait', 'L'=> 'Landscape'),
		   'value' => $aCampaignInfo['printMode'],


                      'required' => true,
				'checker' => array (
                       	        'error' => _t('Please select print mode'),
                ),

                ),



           'campaign_name1' => array(
         		'type' => 'textarea',
        		'name' => 'campaign_name1',
                'caption' => _t('Description'),
               'value' => $aCampaignInfo['template_description'],
                'required' => true,
				'checker' => array (
                    'func' => 'length',
                    'params' => array(3,100),
	                'error' => _t('_dol_aff_err_min_chars'),
                ),
            ),

               'new_file' => array(
                'type' => 'file',
                'name' => 'new_file',
                'caption' => _t('Upload pdf template'),
                'value' => '',
            ),
		/*	'click_header' => array(
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
			), */





              'edit_campaign' => array(
                'type' => 'submit',
               'name' => 'edit_campaign',
                'value' => _t("_dol_aff_reg_update"),
           ),
		);








		return $aInputs;




	}
	
	
	
}
?>