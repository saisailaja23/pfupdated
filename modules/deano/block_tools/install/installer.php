<?php
/***************************************************************************
* Date				: Sat Dec 19, 2009
* Copywrite			: (c) 2009 by Dean J. Bassett Jr.
* Website			: http://www.deanbassett.com
*
* Product Name		: Privacy Page Editor
* Product Version	: 1.1.0000
*
* IMPORTANT: This is a commercial product made by Dean Bassett Jr.
* and cannot be modified other than personal use.
*  
* This product cannot be redistributed for free or a fee without written
* permission from Dean Bassett Jr.
*
***************************************************************************/

require_once(BX_DIRECTORY_PATH_CLASSES . "BxDolInstaller.php");

class BxBlockToolsInstaller extends BxDolInstaller {
	function BxBlockToolsInstaller($aConfig) {
		parent::BxDolInstaller($aConfig);
	}
	function install($aParams) {
        $aResult = parent::install($aParams);
        $this->addExceptionsFields(array('POST.dbcsBTphpcode', 'REQUEST.dbcsBTphpcode', 'POST.dbcsBTHTMLcode', 'REQUEST.dbcsBTHTMLcode'));
        return $aResult;
    }

    function uninstall($aParams) {
        $this->removeExceptionsFields(array('POST.dbcsBTphpcode', 'REQUEST.dbcsBTphpcode', 'POST.dbcsBTHTMLcode', 'REQUEST.dbcsBTHTMLcode'));
        return parent::uninstall($aParams);
    }

}
?>
