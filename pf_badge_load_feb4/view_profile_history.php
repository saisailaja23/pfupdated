<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );

$profileDet = getProfileInfo(getLoggedId());

// --------------- page variables and login

if($profileDet['ProfileType'] == '8'){
$_page['name_index'] 	= 400;
}

check_logged();
if(!isLogged()) {
header('Location:'.$site[url]);
exit;
}

if($profileDet['ProfileType'] == '8'){
$_page['header'] = _t( "Agency profile" );
$_page['header_text'] = _t( "Agency profile" );
}
else {

$_page['header_text'] = _t( '_Access denied');  
}

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompMainCode();

// --------------- [END] page components

PageCode();

if($profileDet['ProfileType'] != '8'){
   print MsgBox(_t('_Access denied'));
            //return MsgBox(_t('_Access denied'));
}

// --------------- page components functions

/**
 * page code function
 */
function PageCompMainCode()
{
    if($profileDet['ProfileType'] == '8'){
    $sRet = str_replace( '<site_url>', $GLOBALS['site']['url'], _t( "Agency profile" ));
    return DesignBoxContent( _t( "Agency profile" ), $sRet, $GLOBALS['oTemplConfig'] -> PageCompThird_db_num);
    }
}
