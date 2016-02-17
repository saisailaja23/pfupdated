<?php

bx_import('BxDolInstaller');

class KPlacesInstaller extends BxDolInstaller {

    function KPlacesInstaller($aConfig) {
        parent::BxDolInstaller($aConfig);
    }

    function install($aParams) {

        $aResult = parent::install($aParams);

        if($aResult['result'] && BxDolRequest::serviceExists('wall', 'update_handlers'))
            BxDolService::call('wall', 'update_handlers', array($this->_aConfig['home_uri'], true));

        if($aResult['result'] && BxDolRequest::serviceExists('spy', 'update_handlers'))
            BxDolService::call('spy', 'update_handlers', array($this->_aConfig['home_uri'], true));

        $this->addHtmlFields (array ('POST.pl_desc', 'REQUEST.pl_desc'));
        $this->addExceptionsFields (array ('POST.sGoogleKey', 'REQUEST.sGoogleKey'));

        return $aResult;
    }

    function uninstall($aParams) {

        if(BxDolRequest::serviceExists('wall', 'update_handlers'))
            BxDolService::call('wall', 'update_handlers', array($this->_aConfig['home_uri'], false));

        if(BxDolRequest::serviceExists('spy', 'update_handlers'))
            BxDolService::call('spy', 'update_handlers', array($this->_aConfig['home_uri'], false));

        $this->removeHtmlFields ();
        $this->removeExceptionsFields ();

        return parent::uninstall($aParams);
    }    
}

?>
