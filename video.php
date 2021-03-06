<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
$ID = getProfileID($_GET['nickname']);
define('BX_DOL_PROFILE_NickName', $_GET['nickname']);
define('BX_DOL_PROFILE_ID', $ID);
define('BX_DOL_PROFILE_BADGE', $_GET['loadFrom']);

// --------------- page variables and login

$_page['name_index'] 	= 211;

//check_logged();
//if(!isLogged()) {
//header('Location:'.$site[url]);
//exit;
//}
$_page['header'] = _t( "Profile view" );
$_page['header_text'] = _t( "Profile view" );

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
    $sRet = str_replace( '<site_url>', $GLOBALS['site']['url'], _t( "Profile view" ));
    return DesignBoxContent( _t( "Profile view" ), $sRet, $GLOBALS['oTemplConfig'] -> PageCompThird_db_num);
}
?>
<style>
    .sys_main_content {
        width: 1010px;
    }
    @media (min-width: 640px) and (max-width: 959px) {
        .sys_main_content {
            width: 100%;
        }
    }
    @media (max-width: 639px) {
        .sys_main_content {
            width: 100%;
        }
    }
</style>