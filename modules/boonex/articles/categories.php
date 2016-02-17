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

$iIndex = 1;
$_page['name_index']	= $iIndex;
$_page['css_name']		= '';
$_page['header'] = _t('_articles_pcaption_categories');

check_logged();

$oArticles = new BxArlModule($aModule);

$aParam = array(
	'type' => 'bx_' . $oArticles->_oConfig->getUri(),
);
$oCateg = new BxTemplCategoriesModule($aParam, _t('_articles_bcaption_all_categories'), BX_DOL_URL_ROOT . $oArticles->_oConfig->getBaseUri() . 'categories');

$_page_cont[$iIndex]['page_main_code'] = $oCateg->getCode();
PageCode($oArticles->_oTemplate);
?>