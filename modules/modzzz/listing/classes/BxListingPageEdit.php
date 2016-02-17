<?php
/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Group
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

bx_import('BxDolPageView');

class BxListingPageEdit extends BxDolPageView {	

    var $oMain;
    var $oTemplate;
    var $oConfig;
    var $oDb;
    var $sUrlStart;
    var $aLocation;

    function BxListingPageEdit(&$oModule, &$aLocation) {
        $this->oMain = &$oModule;
        $this->oTemplate = $oModule->_oTemplate;
        $this->oConfig = $oModule->_oConfig;
        $this->oDb = $oModule->_oDb;
        $this->aLocation = $aLocation;
		parent::BxDolPageView('modzzz_listing_edit');
	}

    function getBlockCode_Map() {
        $aVars = array (
            'text' => $this->aLocation['address'] ? $this->aLocation['address'] : _t('_modzzz_listing_the_same_address'),
            'map' => $this->oMain->serviceEditLocation ($this->aLocation['id']),
        );
        return $this->oTemplate->parseHtmlByName('user_location', $aVars);
    }

    function getBlockCode_Info() {
 
        modzzz_listing_import ('FormPrivacy');
        $oForm = new BxListingFormPrivacy ($this->oMain,$this->aLocation['id']);
        $oForm->initChecker($this->aLocation);

        $sMsg = '';
        if ($oForm->isSubmittedAndValid ()) {
            if ($oForm->update ($this->oMain->_iProfileId))
                $sMsg = MsgBox(_t('_modzzz_listing_privacy_update_ok'));
            else
                $sMsg = MsgBox(_t('_modzzz_listing_privacy_update_fail'));
        } 

        return $sMsg . $oForm->getCode ();
    }
}

?>
