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

$GLOBALS['iAdminPage'] = 1;

require_once(BX_DIRECTORY_PATH_INC . 'admin_design.inc.php');

bx_import('Module', $aModule);

global $_page;
global $_page_cont;
global $logged;

check_logged();

$iIndex = 9;
$_page['name_index'] = $iIndex;
$_page['header'] = _t('_news_pcaption_admin');
$_page['css_name'] = array('forms_adv.css');

if(!@isAdmin()) {
    send_headers_page_changed();
	login_form("", 1);
	exit;
}

$oNews = new BxNewsModule($aModule);

//--- Process actions ---//
$mixedResultSettings = '';
if(isset($_POST['save']) && isset($_POST['cat'])) {
    $mixedResultSettings = $oNews->setSettings($_POST);
}

if(isset($_POST['news-publish'])) 
    $oNews->_actPublish($_POST['news-ids'], true);
else if(isset($_POST['news-unpublish'])) 
    $oNews->_actPublish($_POST['news-ids'], false);
else if(isset($_POST['news-featured'])) 
    $oNews->_actFeatured($_POST['news-ids'], true);
else if(isset($_POST['news-unfeatured'])) 
    $oNews->_actFeatured($_POST['news-ids'], false);
else if(isset($_POST['news-delete'])) 
    $oNews->_actDelete($_POST['news-ids']);
//--- Process actions ---//

//--- Get New/Edit form ---//
$sPostForm = '';
if(isset($aRequest[0]) && !empty($aRequest[0]))
    $sPostForm = $oNews->serviceEditBlock(process_db_input($aRequest[0], BX_TAGS_STRIP));
else if(isset($_POST['id']))
    $sPostForm = $oNews->serviceEditBlock((int)$_POST['id']);
else
    $sPostForm = $oNews->servicePostBlock();
//--- Get New/Edit form ---//

$sFilterValue = '';
if(isset($_GET['news-filter']))
    $sFilterValue = process_db_input($_GET['news-filter'], BX_TAGS_STRIP);

$_page_cont[$iIndex]['page_main_code'] = DesignBoxAdmin(_t('_news_bcaption_settings'), $GLOBALS['oAdmTemplate']->parseHtmlByName('design_box_content.html', array('content' => $oNews->getSettingsForm($mixedResultSettings))));
$_page_cont[$iIndex]['page_main_code'] .= DesignBoxAdmin(_t('_news_bcaption_post'), $sPostForm);
$_page_cont[$iIndex]['page_main_code'] .= DesignBoxAdmin(_t('_news_bcaption_all'), $oNews->serviceAdminBlock(0, 0, $sFilterValue)) ;

PageCodeAdmin();
?>