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

bx_import('BxDolModuleTemplate');

/*
 * Quotes module View
 */
class BxHeadInjectionsTemplate extends BxDolModuleTemplate {
	/**
	* Constructor
	*/
	function BxHeadInjectionsTemplate(&$oConfig, &$oDb) {
		parent::BxDolModuleTemplate($oConfig, $oDb);

		$this->_aTemplates = array('unit', 'adm_unit');
	}

	function loadTemplates() {
	    parent::loadTemplates();
	}

	function parseHtmlByName ($sName, &$aVars) {        
		return parent::parseHtmlByName ($sName.'.html', $aVars);
	}
}

?>