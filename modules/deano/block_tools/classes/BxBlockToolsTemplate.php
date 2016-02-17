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

bx_import('BxDolModuleTemplate');

/*
 * Quotes module View
 */
class BxBlockToolsTemplate extends BxDolModuleTemplate {
	/**
	* Constructor
	*/
	function BxBlockToolsTemplate(&$oConfig, &$oDb) {
		parent::BxDolModuleTemplate($oConfig, $oDb);

		$this->_aTemplates = array('unit', 'adm_unit', 'pageblocks');
	}

	function loadTemplates() {
	    parent::loadTemplates();
	}

	function parseHtmlByName ($sName, &$aVars) {        
		return parent::parseHtmlByName ($sName.'.html', $aVars);
	}
}

?>