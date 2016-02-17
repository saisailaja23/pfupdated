<?
bx_import('BxDolModule');

class HmAffProooFormInputs extends BxDolTwigTemplate {
    function __construct(){
        $this->_oMain = $this->getMain();
		$this->_oDb = $this->_oMain->_oDb;
		$this->mBase = BX_DOL_URL_ROOT.'m/changeagency/';

	}
    function getMain() {
        return BxDolModule::getInstance('HmAffProooModule');
    }
 		
	function getCreateCampaignInputs(){

            $sStateUrl = BX_DOL_URL_ROOT . $this->_oMain->_oConfig->getBaseUri() . 'agency/?ajax=state&country=';
            //echo $sStateUrl;

             if($_GET['ajax']=='state') {
			$sCountryCode = $_GET['country'];
			echo $this->_oDb->getStateOptions($sCountryCode);
			exit;
	     }
                         

              $itemvalue = $_POST['country'];
        
	      $aStates1 = $this->_oDb->getStateArrayy($itemvalue);

             

               $aInputs = array(
		
          	
             
            'country' => array(
                    'type' => 'select',
                    'name' => 'country',
					'listname' => 'Country',
                    'caption' => _t('Current Agency'),
                

                   'values' => $aStates1,
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
					'caption' => _t('Users'),
					'attrs' => array(
						'id' => 'substate',
					),
                                         
                                        'required' => true,
				'checker' => array (
                    'func' => 'length',
                    'params' => array(0,10000),
	                'error' => _t('Please select users'),
                ),
					'db' => array (
					'pass' => 'Preg',
					'params' => array('/([a-zA-Z]+)/'),
					),
					'display' => 'getStateName',
				), 
                                     'tcountry' => array(
                    'type' => 'select',
                    'name' => 'tcountry',
					'listname' => 'Country',
                    'caption' => _t('Target Agency'),
                 

                   'values' => $aStates1,
		   'value' => $itemvalue,

					
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

              'create_campaign' => array(
                'type' => 'submit',
                'name' => 'create_campaign',
                'value' => _t("Change"),
            ),
		);

		return $aInputs;
	}
 
     
	
	
}
?>