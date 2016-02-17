<?php
/***************************************************************************
*
*     copyright            : (C) 2009 AQB Soft
*     website              : http://www.aqbsoft.com
*
* IMPORTANT: This is a commercial product made by AQB Soft. It cannot be modified for other than personal usage.
* The "personal usage" means the product can be installed and set up for ONE domain name ONLY.
* To be able to use this product for another domain names you have to order another copy of this product (license).
*
* This product cannot be redistributed for free or a fee without written permission from AQB Soft.
*
* This notice may not be removed from the source code.
*
***************************************************************************/

require_once(BX_DIRECTORY_PATH_INC . 'admin_design.inc.php');

bx_import('Module', $aModule);

global $_page;
global $_page_cont;

$iIndex = 9;
$_page['name_index'] = $iIndex;
$_page['header'] = _t('_aqb_mls_caption_admin');

if(!@isAdmin()) {
    send_headers_page_changed();
	login_form("", 1);
	exit;
}

$oModule = new AqbMLSModule($aModule);

$oModule->_oTemplate->addAdminCss(array('admin.css', 'forms_adv.css'));
$oModule->_oTemplate->addAdminJs('main.js');
$_page_cont[$iIndex]['page_main_code'] = DesignBoxAdmin(_t('_aqb_mls_available_memlevels'), $oModule->displayAdminPage());


PageCodeAdmin();
?>