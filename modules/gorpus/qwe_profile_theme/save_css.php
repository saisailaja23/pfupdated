<?php

define('BX_SECURITY_EXCEPTIONS', true);
$aBxSecurityExceptions = array(
    'POST.qwe_css_body',
    'REQUEST.qwe_css_body',
);

require_once( '../../../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );

$iProfileId = (int)getLoggedId();

if(!$iProfileId) {
	header("Location: " . BX_DOL_URL_ROOT . "modules/?r=qwe_profile_theme/");
	exit;
}

if($_GET['reset_profile_theme']) {
	$sSql = "REPLACE INTO `qwe_profile_theme_themes` SET `profile_id` = '".$iProfileId."', `css` = '', `type` = '' ";
	db_res($sSql);
	$sMessage = 'qwe_profile_theme_is_empty';
	header("Location: " . BX_DOL_URL_ROOT . "modules/?r=qwe_profile_theme/&qwe_message=" . $sMessage);
	exit;
}

$sCss = $_POST['qwe_css_body'];
$sProfileTheme = $_POST['qwe_theme_type'];

if (get_magic_quotes_gpc()) {
	$sCss = stripslashes($sCss);
	$sProfileThemeType = stripslashes($sProfileThemeType);
}

$sCss = strip_tags($sCss);

$sCss = trim(mysql_real_escape_string($sCss));
$sProfileTheme = trim(mysql_real_escape_string($sProfileTheme));

$sSql = "REPLACE INTO `qwe_profile_theme_themes` SET `profile_id` = '".$iProfileId."', `css` = '".$sCss."', `type` = '".$sProfileTheme."' ";
db_res($sSql);

if($sCss) {
	$sMessage = 'qwe_profile_theme_saved';
}
else {
	$sMessage = 'qwe_profile_theme_is_empty';
}

header("Location: " . BX_DOL_URL_ROOT . "modules/?r=qwe_profile_theme/&qwe_message=" . $sMessage);
exit;

?>