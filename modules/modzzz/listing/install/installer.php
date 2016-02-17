<?php

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Listing
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

bx_import('BxDolInstaller');

class BxListingInstaller extends BxDolInstaller {

    function BxListingInstaller($aConfig) {
        parent::BxDolInstaller($aConfig);
    }

    function modifyCategoryTable() {
		global $db;

		$fields = mysql_list_fields($db['db'], "sys_categories"); 
		$columns = mysql_num_fields($fields);
			   
		for ($i = 0; $i < $columns; $i++) {
			$field_array[] = mysql_field_name($fields, $i);
		}
			   
		if (!in_array('Parent', $field_array)) {
			db_res("ALTER TABLE `sys_categories` ADD `Parent` int( 11 ) NOT NULL DEFAULT '1';");
		}
	}

    function install($aParams) {
 
		$this->modifyCategoryTable();
 
        $aResult = parent::install($aParams);

        if($aResult['result'] && BxDolRequest::serviceExists('wall', 'update_handlers'))
            BxDolService::call('wall', 'update_handlers', array($this->_aConfig['home_uri'], true));

        if($aResult['result'] && BxDolRequest::serviceExists('spy', 'update_handlers'))
            BxDolService::call('spy', 'update_handlers', array($this->_aConfig['home_uri'], true));

        $this->addHtmlFields (array ('POST.desc', 'REQUEST.desc'));
		$this->updateEmailTemplatesExceptions ();

        return $aResult;
    }

    function uninstall($aParams) {

        if(BxDolRequest::serviceExists('wall', 'update_handlers'))
            BxDolService::call('wall', 'update_handlers', array($this->_aConfig['home_uri'], false));

        if(BxDolRequest::serviceExists('spy', 'update_handlers'))
            BxDolService::call('spy', 'update_handlers', array($this->_aConfig['home_uri'], false));

        $this->removeHtmlFields ();
		$this->updateEmailTemplatesExceptions ();

        return parent::uninstall($aParams);
    }    
}

?>
