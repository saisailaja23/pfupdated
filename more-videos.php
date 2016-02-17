<?php
require_once( 'inc/header.inc.php' );
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:GET, POST');
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );

// --------------- page variables and login

$_page['name_index'] 	= 215;


$_page['header'] = _t( "More Videos" );
$_page['header_text'] = _t( "More Videos" );

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
    $sRet = str_replace( '<site_url>', $GLOBALS['site']['url'], _t( "More Videos" ));
    return DesignBoxContent( _t( "More Videos" ), $sRet, $GLOBALS['oTemplConfig'] -> PageCompThird_db_num);
}
