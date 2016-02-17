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

    class BxRpxTemplate extends BxDolModuleTemplate 
    {
    	/**
    	 * Class constructor
    	 */
    	function BxRpxTemplate(&$oConfig, &$oDb) 
        {
    	    parent::BxDolModuleTemplate($oConfig, $oDb);	    
    	}

        function pageCodeAdminStart()
        {
            ob_start();
        }

        function adminBlock ($sContent, $sTitle, $aMenu = array()) 
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
         * Function will generate default dolphin's page;
         *
         * @param  : $sPageCaption   (string) - page's title;
         * @param  : $sPageContent   (string) - page's content;
         * @param $iPageIndex integer
         * @param  : $sPageIcon      (string) - page's icon;
         * @return : (text) html presentation data;
         */
        function getPage($sPageCaption, $sPageContent, $iPageIndex = 54, $sPageIcon = '', $sCssFile = '')
        {
            global $_page;
            global $_page_cont;

            $_page['name_index']	= $iPageIndex;

            // set module's icon;
            if($sPageIcon) {
                $GLOBALS['oTopMenu'] -> setCustomSubIconUrl( $this -> getIconUrl($sPageIcon) ); 
            }

            //$GLOBALS['oTopMenu'] -> setCustomSubHeader($sPageCaption);

            $_page['header']        = $sPageCaption ;
            $_page['header_text']   = $sPageCaption ;
            
            if($sCssFile) {
                $_page['css_name']  = $sCssFile;
            }    

            $_page_cont[$iPageIndex]['page_main_code'] = $sPageContent;
            PageCode($this);
        }

        /**
         * Get login form
         *
         * @param $sCallbackUrl string
         * @param $sCurrentLangCode string
         * @param $sTemplate string
         * @return : text
         */
        function getLoginForm($sCallbackUrl, $sCurrentLangCode, $sTemplate = 'login_box.html')
        {
            $aKey = array(
                'callback_url' => urlencode($sCallbackUrl),
                'application_name' => $this -> _oConfig -> sApllicationName,
                'lang' => $sCurrentLangCode,
            );

            return $this -> parseHtmlByName($sTemplate, $aKey);
        }
    }