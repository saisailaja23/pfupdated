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

// --------------- page variables and login

if($profileDet['ProfileType'] == '2' && ($profileDet['Status'] == 'Active' || $profileDet['Status'] == 'Inactive' || $profileDet['Status'] == 'Approval')) {
	$_page['name_index'] 	= 201;
}
 else {
	$_page['name_index'] 	= 206;
 }

if(!isset($MembershipID)) {    
$_page['name_index'] 	= 301;    
}
 
 
check_logged();
if(!isLogged()) {
header('Location:'.$site[url]);
exit;
}

if($profileDet['ProfileType'] == '2' && ($profileDet['Status'] == 'Active' || $profileDet['Status'] == 'Inactive' || $profileDet['Status'] == 'Approval')) {
$_page['header'] = _t( "Profile view" );
$_page['header_text'] = _t( "Profile view" );
}
else {
	$_page['header'] = _t( '_Access denied');
}

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompMainCode();

// --------------- [END] page components

PageCode();

if($profileDet['ProfileType'] != '2' && ($profileDet['Status'] == 'Active' || $profileDet['Status'] == 'Inactive' || $profileDet['Status'] == 'Approval')) {
   print MsgBox(_t('_Access denied'));
}

// --------------- page components functions

/**
 * page code function
 */
function PageCompMainCode()
{
    if($profileDet['ProfileType'] == '2' && $profileDet['Status'] == 'Active') {
    $sRet = str_replace( '<site_url>', $GLOBALS['site']['url'], _t( "Profile view" ));
    return DesignBoxContent( _t( "Profile view" ), $sRet, $GLOBALS['oTemplConfig'] -> PageCompThird_db_num);
    }
}