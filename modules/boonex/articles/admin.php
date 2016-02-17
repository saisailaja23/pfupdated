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
$_page['header'] = _t('_articles_pcaption_admin');
$_page['css_name'] = array('forms_adv.css');

if(!@isAdmin()) {
    send_headers_page_changed();
	login_form("", 1);
	exit;
}

$oArticles = new BxArlModule($aModule);

//--- Process actions ---//
$mixedResultSettings = '';
if(isset($_POST['save']) && isset($_POST['cat'])) {
    $mixedResultSettings = $oArticles->setSettings($_POST);
}

if(isset($_POST['articles-publish'])) 
    $oArticles->_actPublish($_POST['articles-ids'], true);
else if(isset($_POST['articles-unpublish'])) 
    $oArticles->_actPublish($_POST['articles-ids'], false);
else if(isset($_POST['articles-featured'])) 
    $oArticles->_actFeatured($_POST['articles-ids'], true);
else if(isset($_POST['articles-unfeatured'])) 
    $oArticles->_actFeatured($_POST['articles-ids'], false);
else if(isset($_POST['articles-delete'])) 
    $oArticles->_actDelete($_POST['articles-ids']);
//--- Process actions ---//

//--- Get New/Edit form ---//
$sPostForm = '';
if(isset($aRequest[0]) && !empty($aRequest[0]))
    $sPostForm = $oArticles->serviceEditBlock(process_db_input($aRequest[0], BX_TAGS_STRIP));
else if(isset($_POST['id']))
    $sPostForm = $oArticles->serviceEditBlock((int)$_POST['id']);
else
    $sPostForm = $oArticles->servicePostBlock();
//--- Get New/Edit form ---//

$sFilterValue = '';
if(isset($_GET['articles-filter']))
    $sFilterValue = process_db_input($_GET['articles-filter'], BX_TAGS_STRIP);

$_page_cont[$iIndex]['page_main_code'] = DesignBoxAdmin(_t('_articles_bcaption_settings'), $GLOBALS['oAdmTemplate']->parseHtmlByName('design_box_content.html', array('content' => $oArticles->getSettingsForm($mixedResultSettings))));
$_page_cont[$iIndex]['page_main_code'] .= DesignBoxAdmin(_t('_articles_bcaption_post'), $sPostForm);
$_page_cont[$iIndex]['page_main_code'] .= DesignBoxAdmin(_t('_articles_bcaption_all'), $oArticles->serviceAdminBlock(0, 0, $sFilterValue)) ;

PageCodeAdmin();
?>