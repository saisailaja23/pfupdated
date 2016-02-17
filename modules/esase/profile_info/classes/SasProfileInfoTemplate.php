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

    bx_import('BxDolModuleTemplate');

    class SasProfileInfoTemplate extends BxDolModuleTemplate 
    {
    	/**
    	 * Class constructor
    	 */
    	function SasProfileInfoTemplate(&$oConfig, &$oDb) 
        {
            parent::BxDolModuleTemplate($oConfig, $oDb); 
    	}

        function pageCodeAdminStart()
        {
            ob_start();
        }
        
		/**
         * Generate admin block
         * 
         * @param $sContent text
         * @param $sTitle string
         * @param $aMenu array
         * @return text
         */    
        function getAdminBlock ($sContent, $sTitle, $aMenu = array()) 
        {
            return DesignBoxAdmin($sTitle, $sContent, $aMenu);
        }

        function pageCodeAdmin ($sTitle) 
        {
            global $_page;        
            global $_page_cont;

            $_page['name_index'] = 9; 

            $_page['header'] = $sTitle ? $sTitle : $GLOBALS['site']['title'];
            $_page['header_text'] = $sTitle;
            
            $_page_cont[$_page['name_index']]['page_main_code'] = ob_get_clean();

            PageCodeAdmin();
        }

        /**
         * Generate block with field info
         * 
         * @param $iPercent integer
         * @param $iProfileId integer
         * @return text
         */
        function getFieldInfo($iPercent, $iProfileId, $bViewLink = false)
        {
        	$aKeys = array(
        		'percent' 		=> $iPercent,
                'bx_if:view_link' => array (
                    'condition' =>  ($bViewLink),
                    'content'   => array (
                        'profile_id' =>$iProfileId,
                    ),
                ),
        	);
        	
        	$this -> addCss('sas_profile_info.css');
        	return  $this -> parseHtmlByName('field_info.html', $aKeys);
        }
    }