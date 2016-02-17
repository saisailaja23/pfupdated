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

$_page['name_index'] 	= 202;

check_logged();
if(!isLogged()) {
header('Location:'.$site[url]);
exit;
}

$_page['header'] = _t( "Family Dashboard" );
$_page['header_text'] = _t( "Family Dashboard" );



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

    $sRet = str_replace( '<site_url>', $GLOBALS['site']['url'], _t( "Family Dashboard" ));
    return DesignBoxContent( _t( "Family Dashboard" ), $sRet, $GLOBALS['oTemplConfig'] -> PageCompThird_db_num);
    
}
