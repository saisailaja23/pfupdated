<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );

// --------------- page variables and login

if ($_GET['loadFrom'] == 'badge') {
	$_page['name_index'] = 2071;
} else {
	$_page['name_index'] = 207;
}

//check_logged();
//if(!isLogged()) {
//header('Location:'.$site[url]);
//exit;
//}
$_page['header'] = _t( "Search" );
$_page['header_text'] = _t( "Search" );

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
    $sRet = str_replace( '<site_url>', $GLOBALS['site']['url'], _t( "Search" ));
    return DesignBoxContent( _t( "Search" ), $sRet, $GLOBALS['oTemplConfig'] -> PageCompThird_db_num);
}
