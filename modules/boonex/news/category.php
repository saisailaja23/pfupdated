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

bx_import('BxTemplCategoriesModule');
bx_import('Module', $aModule);

global $_page;
global $_page_cont;

$iIndex = 0;
$_page['name_index']	= $iIndex;

check_logged();

$oNews = new BxNewsModule($aModule);

$sCategoryDisplay = "";
$sPageCode = MsgBox(_t('_news_msg_no_results'));
if(!empty($aRequest) && !empty($aRequest[0])) 
	$sCategoryDisplay = $oNews->getCategoryContent($aRequest, $sPageCode);

$sBaseUri = BX_DOL_URL_ROOT . $oNews->_oConfig->getBaseUri();
$GLOBALS['oTopMenu']->setCustomBreadcrumbs(array(
	_t('_news_top_menu_item') => $sBaseUri . 'home/',
	_t('_news_categories_top_menu_sitem') => $sBaseUri . 'categories/',
	$sCategoryDisplay => '')
);

$_page_cont[$iIndex]['page_main_code'] = $sPageCode;

$oNews->_oTemplate->setPageTitle(_t('_news_pcaption_category', $sCategoryDisplay));
$oNews->_oTemplate->setPageMainBoxTitle(_t('_news_bcaption_category', $sCategoryDisplay));
PageCode($oNews->_oTemplate);
?>