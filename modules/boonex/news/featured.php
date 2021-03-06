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

bx_import('Module', $aModule);

global $_page;
global $_page_cont;

$iIndex = 0;
$_page['name_index']	= $iIndex;
$_page['css_name']		= '';

check_logged();

$oNews = new BxNewsModule($aModule);
$_page_cont[$iIndex]['page_main_code'] = $oNews->serviceFeaturedBlock();

$oNews->_oTemplate->setPageTitle(_t('_news_pcaption_featured'));
$oNews->_oTemplate->setPageMainBoxTitle(_t('_news_bcaption_featured'));
PageCode($oNews->_oTemplate);
?>