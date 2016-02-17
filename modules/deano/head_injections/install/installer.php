<?php
/***************************************************************************
* Date				: Saturday November 24, 2012
* Copywrite			: (c) 2012 by Dean J. Bassett Jr.
* Website			: http://www.deanbassett.com
*
* Product Name		: Head Injections
* Product Version	: 2.0.1
*
* IMPORTANT: This is a commercial product made by Dean Bassett Jr.
* and cannot be modified other than personal use.
*  
* This product cannot be redistributed for free or a fee without written
* permission from Dean Bassett Jr.
*
***************************************************************************/

require_once(BX_DIRECTORY_PATH_CLASSES . "BxDolInstaller.php");

class BxHeadInjectionsInstaller extends BxDolInstaller {
	function BxHeadInjectionsInstaller($aConfig) {
		parent::BxDolInstaller($aConfig);
	}
	function install($aParams) {
        $aResult = parent::install($aParams);
        $this->addExceptionsFields(array('POST.dbcs_HI_Title', 'REQUEST.dbcs_HI_Title', 'POST.dbcs_HI_Injections', 'REQUEST.dbcs_HI_Injections'));
        return $aResult;
    }

    function uninstall($aParams) {
        $this->removeExceptionsFields(array('POST.dbcs_HI_Title', 'REQUEST.dbcs_HI_Title', 'POST.dbcs_HI_Injections', 'REQUEST.dbcs_HI_Injections'));
        return parent::uninstall($aParams);
    }

}
?>