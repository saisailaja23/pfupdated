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

bx_import('Module',  $aModule);
bx_import('SearchResult', $aModule);

global $_page;
global $_page_cont;

$iIndex = 0;
$_page['name_index']	= $iIndex;
$_page['css_name']		= '';

check_logged();

$iYear = $iMonth = $iDay = 0;
$oArticles = new BxArlModule($aModule);

$iRequest = count($aRequest);
if(empty($aRequest) || empty($aRequest[0]) || $iRequest == 2) {
	if($iRequest == 2) {
		$iYear = (int)array_shift($aRequest);
    	$iMonth = (int)array_shift($aRequest);
	}

    $oArticlesCalendar = $oArticles->getCalendar($iYear, $iMonth);
    $_page_cont[$iIndex]['page_main_code'] = $oArticlesCalendar->display();
}
else if($iRequest == 3 || $iRequest == 5) {
	$sPageCode = "";
	$oArticles->getCalendarContent($aRequest, $sPageCode);
	$_page_cont[$iIndex]['page_main_code'] = strlen($sPageCode) > 0 ? $sPageCode : _t('_articles_msg_no_results');
}

$oArticles->_oTemplate->setPageTitle(_t('_articles_pcaption_calendar'));
$oArticles->_oTemplate->setPageMainBoxTitle(_t('_articles_bcaption_calendar'));
PageCode($oArticles->_oTemplate);
?>