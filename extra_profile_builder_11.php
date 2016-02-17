<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );

// --------------- page variables and login

// if (getLoggedId()) {
 // $this->displayAccessDenied();            
  // }
$profileDet = getProfileInfo(getLoggedId());
if($profileDet['ProfileType'] == '2') {
$_page['name_index'] 	= 104;
}
check_logged();
if(!isLogged()) {
header('Location:'.$site[url]);
exit;
}

if($profileDet['ProfileType'] == '2'){
$_page['header'] = _t( "Family profile builder" );
$_page['header_text'] = _t( "Family profile builder" );
}
else {

$_page['header_text'] = _t( '_Access denied');  
}


// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompMainCode();

// --------------- [END] page components

PageCode();

if($profileDet['ProfileType'] != '2') {
   print MsgBox(_t('_Access denied'));
            //return MsgBox(_t('_Access denied'));
}
// --------------- page components functions

/**
 * page code function
 */
function PageCompMainCode()
{
    if($profileDet['ProfileType'] == '2') {
    $sRet = str_replace( '<site_url>', $GLOBALS['site']['url'], _t( "Family profile builder" ));
    return DesignBoxContent( _t( "Family profile builder" ), $sRet, $GLOBALS['oTemplConfig'] -> PageCompThird_db_num);
    }
}
