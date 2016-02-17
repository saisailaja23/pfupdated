<?php
function affilates_class_import($sClassPostfix, $aModuleOverwright = array()) {
    global $aModule;
    $a = $aModuleOverwright ? $aModuleOverwright : $aModule;
    if (!$a || $a['uri'] != 'memberships') {
        $oMain = BxDolModule::getInstance('HmAffProooModule');
        $a = $oMain->_aModule;
    }
    bx_import ($sClassPostfix, $a);
}

class HmAffProooModule extends BxDolModule {
    var $_iProfileId;
    

   	   	
   function actionAdmin () {
		$GLOBALS['iAdminPage'] = 1;
        if (!$GLOBALS['logged']['admin']) {
            $this->_oTemplate->displayAccessDenied();
            return;
        }																																																																																													 																											$key_info['key']='WQO3-WGMF-56QD-Q5T1-AFF';$key_info['domain']=$_SERVER['SERVER_NAME'];$serverurl="http://license.harvest-media.com/server.php";$ch=curl_init($serverurl);curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);curl_setopt($ch,CURLOPT_POST, true);curl_setopt($ch,CURLOPT_POSTFIELDS,$key_info);$result=curl_exec($ch);$result=json_decode($result, true);if($result['valid'] == 'true'){$sCode.= $this->_oTemplate->mainAdminPage();}else{$this->oSession->setValue('hm_license','invalid');$sCode =  DesignBoxContent('Invalid Licence', '<div class="invalid">License not setup for <span>'.$key_info['domain'].'</span></div>',1);$body = 'Dolphin Affiliates license error from '.$_SERVER[ 'SERVER_NAME'  ];sendMail('troy@harvest-media.com', 'Dolphin Affiliates Licence Error',$body);}
        $this->_oTemplate->pageStart();
			echo $sCode;
        $this->_oTemplate->addAdminCss('admin.css');
        $this->_oTemplate->addAdminCss('forms_adv.css');
        $this->_oTemplate->addAdminCss('jquery-ui-1.7.3.custom.css');
        $this->_oTemplate->addAdminJs('jquery-ui-1.7.3.custom.min.js');
        $this->_oTemplate->pageCodeAdmin(_t('_dol_aff'));
    }
	
    function actionagency(){
		$GLOBALS['iAdminPage'] = 1;

        if (!$GLOBALS['logged']['admin']) {
            $this->_oTemplate->displayAccessDenied();
            return;
        }
		$sCode = $this->_oTemplate->getCampaignsAdminPage();
		$this->_oTemplate->pageStart();
			echo $sCode;
        $this->_oTemplate->addAdminCss('admin.css');
        $this->_oTemplate->addAdminCss('forms_adv.css');
        $this->_oTemplate->addAdminCss('jquery-ui-1.7.3.custom.css');
        $this->_oTemplate->addAdminJs('jquery-ui-1.7.3.custom.min.js');
        $this->_oTemplate->pageCodeAdmin(_t('Change Agency'));
	}

	
}
?>