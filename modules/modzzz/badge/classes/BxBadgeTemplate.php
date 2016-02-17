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

bx_import('BxDolTwigTemplate');
bx_import('BxDolCategories');

/*
 * Badge module View
 */
class BxBadgeTemplate extends BxDolTwigTemplate {

    var $_iPageIndex = 500;      
    
	/**
	 * Constructor
	 */
	function BxBadgeTemplate(&$oConfig, &$oDb) {
        parent::BxDolTwigTemplate($oConfig, $oDb);

		$this->_oConfig = $oConfig;
		$this->_oDb = $oDb; 
    }

	function displayCurrentLevel($aUserLevel, $bOwner) {
		
	    $aLevelInfo = $this->_oDb->getMembershipsBy(array('type' => 'level_id', 'id' => $aUserLevel['ID']));
	    if(isset($aUserLevel['DateExpires']))
            $sTxtExpiresIn = _t('_modzzz_badge_expires_in', floor(($aUserLevel['DateExpires'] - time())/86400));
        else
            $sTxtExpiresIn = _t('_modzzz_badge_expires_never');
 
        $this->addCss('badge.css');
	    return $this->parseHtmlByName('current', $aVars = array(
				'id' => $aLevelInfo['mem_id'],
				'title' => $aLevelInfo['mem_name'],
				'icon' =>  $this->_oConfig->getImagesUrl() . $aLevelInfo['mem_icon'],
				'description' => str_replace("\$", "&#36;", $aLevelInfo['mem_description']),

				'bx_if:owner' => array( 
					'condition' =>  $bOwner,
					'content' => array(
						'expires' => $sTxtExpiresIn 
					) 
				) 
            ) 
        );
	}

 
    function showAdminActionsPanel($sWrapperId, $aButtons, $sCheckboxName = 'entry', $bSelectAll = true, $bSelectAllChecked = false, $sCustomHtml = '') {
        $aBtns = array();
        foreach ($aButtons as $k => $v) {
            if(is_array($v)) {
                $aBtns[] = $v;
                continue;
            }
            $aBtns[] = array(
                'type' => 'submit',
                'name' => $k,
                'value' => '_' == $v[0] ? _t($v) : $v,
                'onclick' => '',
            );            
        }
        $aUnit = array(
            'bx_repeat:buttons' => $aBtns,
            'bx_if:customHTML' => array(
                'condition' => strlen($sCustomHtml) > 0,
                'content' => array(
                    'custom_HTML' => $sCustomHtml,
                )
            ),
            'bx_if:selectAll' => array(
                'condition' => $bSelectAll,
                'content' => array(
                    'wrapperId' => $sWrapperId,
                    'checkboxName' => $sCheckboxName,
                    'checked' => ($bSelectAll && $bSelectAllChecked ? 'checked="checked"' : '')
                )
            ),
        );
        return $GLOBALS['oSysTemplate']->parseHtmlByName('adminActionsPanel.html', $aUnit, array('{','}'));
    }




    // ======================= ppage compose block functions 

}

?>
