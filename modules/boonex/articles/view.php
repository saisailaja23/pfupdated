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

class BxArlPage extends BxDolPageView {
    var $_oArticles;
    var $_sUri;
    
    function BxArlPage($sUri, &$oArticles) {
        parent::BxDolPageView('articles_single');
        
        $this->_sUri = process_db_input($sUri, BX_TAGS_STRIP);
        $this->_oArticles = &$oArticles;
    }    
    function getBlockCode_Content() {
        return $this->_oArticles->serviceViewBlock($this->_sUri);
    }
    function getBlockCode_Comment() {
        return $this->_oArticles->serviceCommentBlock($this->_sUri);
    }
    function getBlockCode_Vote() {
        return $this->_oArticles->serviceVoteBlock($this->_sUri);
    }
    function getBlockCode_Action() {
        return $this->_oArticles->serviceActionBlock($this->_sUri);
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

$oArticles = new BxArlModule($aModule);
$oArticles->_oTemplate->setPageTitle(_t('_articles_pcaption_view'));

$oArticlesPage = new BxArlPage(array_shift($aRequest), $oArticles);
$_page_cont[$iIndex]['page_main_code'] = $oArticles->_oTemplate->getViewJs(true) . $oArticlesPage->getCode();

PageCode($oArticles->_oTemplate);
?>