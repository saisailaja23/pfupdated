<?php

/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */
require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
$ID = getProfileID($_GET['nickname']);
define('BX_DOL_PROFILE_NickName', $_GET['nickname']);
define('BX_DOL_PROFILE_ID', $ID);
define('BX_DOL_PROFILE_BADGE', $_GET['loadFrom']);

// --------------- To get profile details

$profileDet = getProfileInfo($ID);

// --------------- page variables and login

if ($profileDet['Status'] == 'Active' || getLoggedId() == $ID) {
    if ($_GET['loadFrom'] == 'badge') {
	$_page['name_index'] = 2081;
} else {
	$_page['name_index'] = 208;
}
}

check_logged();

if ($profileDet['Status'] == 'Active' || getLoggedId() == $ID) {
    $_page['header'] = _t("Profile view");
    $_page['header_text'] = _t("Profile view");
} else {
    $_page['header_text'] = _t('_Access denied');
}

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompMainCode();

// --------------- [END] page components

PageCode();

if ($profileDet['Status'] != 'Active' && getLoggedId() != $ID) {
    echo MsgBox(_t('The user is in inactive state'));
}

// --------------- page components functions

/**
 * page code function
 */
function PageCompMainCode() {
    if ($profileDet['Status'] == 'Active' || getLoggedId() == $ID) {
        $sRet = str_replace('<site_url>', $GLOBALS['site']['url'], _t("Profile view"));
        return DesignBoxContent(_t("Profile view"), $sRet, $GLOBALS['oTemplConfig']->PageCompThird_db_num);
    }
}
