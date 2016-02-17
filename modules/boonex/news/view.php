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
bx_import('BxTemplConfig');
bx_import('Module', $aModule);

class BxNewsPage extends BxDolPageView {
    var $_oNews;
    var $_sUri;
    
    function BxNewsPage($sUri, &$oNews) {
        parent::BxDolPageView('news_single');
        
        $this->_sUri = process_db_input($sUri, BX_TAGS_STRIP);
        $this->_oNews = &$oNews;
    }    
    function getBlockCode_Content() {
        return $this->_oNews->serviceViewBlock($this->_sUri);
    }
    function getBlockCode_Comment() {
        return $this->_oNews->serviceCommentBlock($this->_sUri);
    }
    function getBlockCode_Vote() {
        return $this->_oNews->serviceVoteBlock($this->_sUri);
    }
    function getBlockCode_Action() {
        return $this->_oNews->serviceActionBlock($this->_sUri);
    }
}

global $_page;
global $_page_cont;

$iIndex = 1;
$_page['name_index']	= $iIndex;
$_page['js_name']		= array('main.js');
$_page['css_name']		= array('view.css', 'cmts.css');

$oTemplConfig = new BxTemplConfig($GLOBALS['site']);
$_page['extra_js'] = $oTemplConfig -> sTinyMceEditorMiniJS;

check_logged();

$oNews = new BxNewsModule($aModule);
$oNews->_oTemplate->setPageTitle(_t('_news_pcaption_view'));

$oNewsPage = new BxNewsPage(array_shift($aRequest), $oNews);
$_page_cont[$iIndex]['page_main_code'] = $oNews->_oTemplate->getViewJs(true) . $oNewsPage->getCode();

PageCode($oNews->_oTemplate);
?>