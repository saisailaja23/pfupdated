<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

require_once( 'inc/header.inc.php' );
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:GET, POST');
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );

$profileDet = getProfileInfo(getLoggedId());
$MembershipID = getMembershipID(getLoggedId());
//echo $MembershipID;
// --------------- page variables and login

$_page['name_index'] 	= 216;

 
 
check_logged();
if(!isLogged()) {
	header('Location:'.$site[url]);
	exit;
}

if($profileDet['ProfileType'] == '2' && ($profileDet['Status'] == 'Active' || $profileDet['Status'] == 'Inactive')) {
	$_page['header'] = _t( "Album List" );
	$_page['header_text'] = _t( "Album List" );
}else {
	$_page['header'] = _t( '_Access denied');
}
// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompMainCode();

// --------------- [END] page components

PageCode();

if($profileDet['ProfileType'] != '2' && ($profileDet['Status'] == 'Active' || $profileDet['Status'] == 'Inactive')) {
   print MsgBox(_t('_Access denied'));
}

// --------------- page components functions

/**
 * page code function
 */
function PageCompMainCode(){
    if($profileDet['ProfileType'] == '2' && $profileDet['Status'] == 'Active') {
		$sRet = str_replace( '<site_url>', $GLOBALS['site']['url'], _t( "Album List" ));
		return DesignBoxContent( _t( "Album List" ), $sRet, $GLOBALS['oTemplConfig'] -> PageCompThird_db_num);
    }
}
