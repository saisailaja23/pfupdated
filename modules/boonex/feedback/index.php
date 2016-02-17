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

global $_page;
global $_page_cont;

$iIndex = 2;
$_page['name_index']	= $iIndex;
$_page['css_name']		= array('view.css', 'cmts.css');

check_logged();

$oFeedback = new BxFdbModule($aModule);

if(isMember()) {
    $sLink = BX_DOL_URL_ROOT . $oFeedback->_oConfig->getBaseUri() . 'post/';
    $sCaption = _t('_feedback_lcaption_post');
    
    $_page_cont[$iIndex]['page_code_link'] = BxDolPageView::getBlockCaptionMenu(mktime(), array(
        'fdb_post' => array('href' => $sLink, 'title' => $sCaption)
    ));
}
else
    $_page_cont[$iIndex]['page_code_link'] = '';
$_page_cont[$iIndex]['page_main_code'] = $oFeedback->serviceArchiveBlock();

$oFeedback->_oTemplate->setPageTitle(_t('_feedback_pcaption_all'));
$oFeedback->_oTemplate->setPageMainBoxTitle(_t('_feedback_bcaption_view_all'));
PageCode($oFeedback->_oTemplate);
?>