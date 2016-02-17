<?php

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -----------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2006 BoonEx Group
*     website              : http://www.boonex.com/
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software. This work is licensed under a Creative Commons Attribution 3.0 License. 
* http://creativecommons.org/licenses/by/3.0/
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the Creative Commons Attribution 3.0 License for more details. 
* You should have received a copy of the Creative Commons Attribution 3.0 License along with Dolphin, 
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

bx_import('BxDolPageView');
bx_import('Module', $aModule);

class BxNewsPage extends BxDolPageView {    
    var $_oNews;
    
    function BxNewsPage(&$oNews) {
        parent::BxDolPageView('news_home');
                
        $this->_oNews = &$oNews;
    }
    function getBlockCode_Featured() {
        return $this->_oNews->serviceFeaturedBlock();
    }
    function getBlockCode_Latest() {
        return array($this->_oNews->serviceArchiveBlock(), array(
            'get-rss' => array('href' => BX_DOL_URL_ROOT . $this->_oNews->_oConfig->getBaseUri() . 'act_rss/', 'target' => '_blank', 'title' => _t('_news_get_rss'), 'icon' => $GLOBALS['oSysTemplate']->getIconUrl('rss.png')),
        ), array(), true, 'getBlockCaptionMenu');
    }
}

global $_page;
global $_page_cont;

$iIndex = 1;
$_page['name_index']	= $iIndex;
$_page['css_name']		= array('view.css', 'cmts.css');

check_logged();

$oNews = new BxNewsModule($aModule);
$oNewsPage = new BxNewsPage($oNews);
$_page_cont[$iIndex]['page_main_code'] = $oNewsPage->getCode();

$oNews->_oTemplate->setPageTitle(_t('_news_pcaption_home'));
PageCode($oNews->_oTemplate);
?>