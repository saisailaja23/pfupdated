<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );

// --------------- page variables and login

$profileDet = getProfileInfo(getLoggedId());

if($profileDet['ProfileType'] == '4') {
$_page['name_index'] 	= 114;
}
check_logged();
if(!isLogged()) {
header('Location:'.$site[url]);
exit;
}

if($profileDet['ProfileType'] == '4') {
$_page['header'] = _t( "Families i like" );
$_page['header_text'] = _t( "Families i like" );
}
else {
$_page['header_text'] = _t( '_Access denied');  
}
// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompMainCode();

// --------------- [END] page components

PageCode();

if($profileDet['ProfileType'] != '4') {
   print MsgBox(_t('_Access denied'));
            //return MsgBox(_t('_Access denied'));
}
// --------------- page components functions

/**
 * page code function
 */
function PageCompMainCode()

{   if($profileDet['ProfileType'] == '4') {
    $sRet = str_replace( '<site_url>', $GLOBALS['site']['url'], _t( "Families i like" ));
    return DesignBoxContent( _t( "Families i like" ), $sRet, $GLOBALS['oTemplConfig'] -> PageCompThird_db_num);
    }
}
