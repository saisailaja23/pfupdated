<?
bx_import ('BxDolTwigTemplate');
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_PLUGINS . 'Services_JSON.php' );



class HmAffProooTemplate extends BxDolTwigTemplate {
    
	function HmAffProooTemplate(&$oConfig, &$oDb) {
	    parent::BxDolTwigTemplate($oConfig, $oDb);
		$this->mBase = BX_DOL_URL_ROOT.'m/changeagency/';
		$this->sCur = getParam('currency_sign');
        $GLOBALS['oHmAffProooModule'] = &$this;
        $this->sBannersUrl = BX_DOL_URL_MODULES.'harvest/changeagency/images/banners/';
        $this->sBannersPath = BX_DIRECTORY_PATH_MODULES.'harvest/changeagency/images/banners/';
    }
    function pageCodeAdminStart(){
        ob_start();
    }

 function getCampaignsAdminPage(){
	   
	$aForm = $this->getCreateCampaignForm();
	$aVars = array(
	'create_campaign' => $aForm
		); 
	 $sCode.= DesignBoxAdmin(_t('Change agency'), $this->parseHtmlByName('admin_create_campaign', $aVars));
         return $sCode;
	}
	
	function getCreateCampaignForm(){
        affilates_class_import('FormInputs');
        $oInputs = new HmAffProooFormInputs;
		$aInputs = $oInputs->getCreateCampaignInputs();	
      	$aForm = array(
            'form_attrs' => array(
                'name'     => 'create_campaign', 
                'method'   => 'post',
                'action'   => NULL,
                'enctype' => 'multipart/form-data'
            ),
			'params' => array (
            	'db' => array(
   					'submit_name' => 'create_campaign', 
                ),
        	),
			'inputs' => $aInputs,       
		);	
        $oForm = new BxTemplFormView ($aForm);
        $oForm->initChecker();
		if ($oForm->isSubmittedAndValid ()) {
			$sValidate = $this->validateCreateCampaignForm($_POST);
                       
			if($sValidate == 'true'){
                        
                                $multi = $_POST['state'];
                               
                                $counter = count($multi);
                             
				$this->_oDb->createCampaign($_POST);
                            
                                
				Redirect($this->mBase.'agency', array('action'=>'created'), post, 'Creating Campaign');
			}else{
                                
				$sValidateMsg = '<script>alert("'.$sValidate.'");</script>';
			}
       	}
        $sCode.= $oForm->getCode(); 		
    	return $sValidateMsg.$sCode;		
	}
         function validateCreateCampaignForm($aVars){

         return 'true';
            
	    
         }
  
}
?>
