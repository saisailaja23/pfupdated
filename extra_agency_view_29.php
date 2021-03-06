<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
$ID = getProfileID($_GET['nickname']);
define('BX_DOL_PROFILE_NickName', $_GET['nickname']);
define('BX_DOL_PROFILE_ID', $ID);
define('BX_DOL_PROFILE_BADGE', $_GET['loadFrom']);

// --------------- page variables and login

$_page['name_index'] 	= 117;

//check_logged();
//if(!isLogged()) {
//header('Location:'.$site[url]);
//exit;
//}
$_page['header'] = _t( "Our agency profile" );
$_page['header_text'] = _t( "Our agency profile" );

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompMainCode();

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompMainCode()
{
    $sRet = str_replace( '<site_url>', $GLOBALS['site']['url'], _t( "Our agency profile" ));
    return DesignBoxContent( _t( "Our agency profile" ), $sRet, $GLOBALS['oTemplConfig'] -> PageCompThird_db_num);
}
