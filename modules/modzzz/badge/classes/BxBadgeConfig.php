<?
/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Confession
*     website              : http://www.boonex.com
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License as published by the
* Free Software Foundation; either version 2 of the
* License, or  any later version.
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/
  
bx_import('BxDolConfig');

class BxBadgeConfig extends BxDolConfig {
    var $_oDb; 
	var $_sIconsPath;
	var $_sIconsUrl;
	var $_sImagesFolder;

	/**
	 * Constructor
	 */
	function BxBadgeConfig($aModule) {
	    parent::BxDolConfig($aModule);
	    
	    $this->_oDb = null;
        $this->_sIconsPath = BX_DIRECTORY_PATH_MODULES . "modzzz/badge/media/images/"; 
		$this->_sIconsUrl =  BX_DOL_URL_MODULES . "modzzz/badge/media/images/";  
	    $this->_sImagesFolder = 'media/images/membership/';  

	}

	function init(&$oDb) {
	    $this->_oDb = &$oDb; 
	}
 

 	function getIconsUrl() {
	    return $this->_sIconsUrl;
	}

	function getIconsPath() {
	    return $this->_sIconsPath;
	}
 
	function getImagesUrl() {
	    return BX_DOL_URL_ROOT . $this->_sImagesFolder;
	}

	function getImagesPath() {
	    return BX_DIRECTORY_PATH_ROOT . $this->_sImagesFolder;
	}
  
}

?>