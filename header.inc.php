<?

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -----------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2006 BoonEx Group
*     website              : http://www.boonex.com/
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software. This work is licensed under a Creative Commons Attribution 3.0 License.
* http://creativecommons.org/licenses/by/3.0/
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the Creative Commons Attribution 3.0 License for more details.
* You should have received a copy of the Creative Commons Attribution 3.0 License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

define ('BX_PROFILER', true);
if (BX_PROFILER && !isset($GLOBALS['bx_profiler_start']))
    $GLOBALS['bx_profiler_start'] = microtime ();

//$site['ver']               = '7.0';
//$site['build']             = '9';
$site['ver']               = '7.1';
$site['build']             = '3';
$site['url']               = "http://ctpf01.parentfinder.com/";
$admin_dir                 = "administration";
$iAdminPage				= 0;
$site['url_admin']         = "{$site['url']}$admin_dir/";

$site['mediaImages']       = "{$site['url']}media/images/";
$site['gallery']           = "{$site['url']}media/images/gallery/";
$site['flags']             = "{$site['url']}media/images/flags/";
$site['banners']           = "{$site['url']}media/images/banners/";
$site['imagesPromo']       = "{$site['url']}media/images/promo/";
$site['tmp']               = "{$site['url']}tmp/";
$site['plugins']           = "{$site['url']}plugins/";
$site['base']              = "{$site['url']}templates/base/";

$site['bugReportMail']     = "prashanth.adkathbail1@mediaus.com";

$dir['root']               = "/var/www/html/pf/";
$dir['inc']                = "{$dir['root']}inc/";
$dir['profileImage']       = "{$dir['root']}media/images/profile/";

$dir['mediaImages']        = "{$dir['root']}media/images/";
$dir['gallery']            = "{$dir['root']}media/images/gallery/";
$dir['PDFTemplates']        = "{$dir['root']}PDFTemplates/";
$dir['flags']              = "{$dir['root']}media/images/flags/";
$dir['banners']            = "{$dir['root']}media/images/banners/";
$dir['imagesPromo']        = "{$dir['root']}media/images/promo/";
$dir['tmp']                = "{$dir['root']}tmp/";
$dir['cache']              = "{$dir['root']}cache/";
$dir['plugins']            = "{$dir['root']}plugins/";
$dir['base']               = "{$dir['root']}templates/base/";
$dir['classes']            = "{$dir['inc']}classes/";

$video_ext                 = 'avi';
$MOGRIFY                   = "/usr/bin/mogrify";
$CONVERT                   = "/usr/bin/convert";
$COMPOSITE                 = "/usr/bin/composite";
$PHPBIN                    = "/usr/bin/php";

$db['host']                = '192.168.1.50';
$db['sock']                = '';
$db['port']                = '';
$db['user']                = 'root';
$db['passwd']              = 'Q@pwd4PF';
$db['db']                  = 'pfcomm_test';
session_start();

$_SESSION['token_flag'] = 1;
define('BX_DOL_URL_ROOT', $site['url']);
define('BX_DOL_CACHE_CONTROL_NUMBER', '2016020212232');
define('GOOGLE_TOKEN_FLAG', $_SESSION['token_flag']);
define('BX_DOL_URL_ADMIN', $site['url_admin']);
define('BX_DOL_URL_PLUGINS', $site['plugins']);
define('BX_DOL_URL_MODULES', $site['url'] . 'modules/' );
define('BX_DOL_URL_CACHE_PUBLIC', $site['url'] . 'cache_public/');

define('BX_DIRECTORY_PATH_INC', $dir['inc']);
define('BX_DIRECTORY_PATH_ROOT', $dir['root']);
define('BX_DIRECTORY_PATH_BASE', $dir['base']);
define('BX_DIRECTORY_PATH_CACHE', $dir['cache']);
define('BX_DIRECTORY_PATH_CLASSES', $dir['classes']);
define('BX_DIRECTORY_PATH_PLUGINS', $dir['plugins']);
define('BX_DIRECTORY_PATH_DBCACHE', $dir['cache']);
define('BX_DIRECTORY_PATH_MODULES', $dir['root'] . 'modules/' );
define('BX_DIRECTORY_PATH_CACHE_PUBLIC', $dir['root'] . 'cache_public/' );

define('DATABASE_HOST', $db['host']);
define('DATABASE_SOCK', $db['sock']);
define('DATABASE_PORT', $db['port']);
define('DATABASE_USER', $db['user']);
define('DATABASE_PASS', $db['passwd']);
define('DATABASE_NAME', $db['db']);
define('BX_DOL_SPLASH_VIS_DISABLE', 'disable');
define('BX_DOL_SPLASH_VIS_INDEX', 'index');
define('BX_DOL_SPLASH_VIS_ALL', 'all');

$zoho_api['api'] = 'ddb52b726cadc88482f9db0367330303';
$zoho_api['contact_insert'] = 'https://crm.zoho.com/crm/private/xml/Contacts/insertRecords';
$zoho_api['contact_update'] = 'https://crm.zoho.com/crm/private/xml/Contacts/updateRecords';
$zoho_api['account_insert'] = 'https://crm.zoho.com/crm/private/xml/Accounts/insertRecords';
$zoho_api['account_update'] = 'https://crm.zoho.com/crm/private/xml/Accounts/updateRecords';
$zoho_api['account_getid']  = 'https://crm.zoho.com/crm/private/xml/Accounts/getRecordById';
$zoho_api['account_search'] = "https://crm.zoho.com/crm/private/xml/Accounts/searchRecords";
define('CHECK_DOLPHIN_REQUIREMENTS', 1);
if (defined('CHECK_DOLPHIN_REQUIREMENTS')) {
	//check requirements
	$aErrors = array();

	$aErrors[] = (ini_get('register_globals') == 0) ? '' : '<font color="red">register_globals is On (warning, you should have this param in Off state, or your site will unsafe)</font>';
	$aErrors[] = (ini_get('safe_mode') == 0) ? '' : '<font color="red">safe_mode is On, disable it</font>';
	//$aErrors[] = (ini_get('allow_url_fopen') == 0) ? 'Off (warning, better keep this parameter in On to able register Dolphin' : '';
	$aErrors[] = (version_compare(PHP_VERSION, '5.2.0', '<')) ? '<font color="red">PHP version too old, please update to PHP 5.2.0 at least</font>' : '';
	$aErrors[] = (! extension_loaded( 'mbstring')) ? '<font color="red">mbstring extension not installed. <b>Warning!</b> Dolphin cannot work without <b>mbstring</b> extension.</font>' : '';

	if (version_compare(phpversion(), "5.2", ">") == 1) {
		$aErrors[] = (ini_get('allow_url_include') == 0) ? '' : '<font color="red">allow_url_include is On (warning, you should have this param in Off state, or your site will unsafe)</font>';
	};

	$aErrors = array_diff($aErrors, array('')); //delete empty
	if (count($aErrors)) {
		$sErrors = implode(" <br /> ", $aErrors);
		echo <<<EOF
{$sErrors} <br />
Please go to the <br />
<a href="http://www.boonex.com/trac/dolphin/wiki/GenDolTShooter">Dolphin Troubleshooter</a> <br />
and solve the problem.
EOF;
		exit;
	}
}

//check correct hostname
$aUrl = parse_url( $site['url'] );
if( isset($_SERVER['HTTP_HOST']) and 0 != strcasecmp($_SERVER['HTTP_HOST'], $aUrl['host']) and 0 != strcasecmp($_SERVER['HTTP_HOST'], $aUrl['host'] . ':80') )
{
	header( "Location:http://{$aUrl['host']}{$_SERVER['REQUEST_URI']}" );
	exit;
}

// check if install folder exists
if ( !defined ('BX_SKIP_INSTALL_CHECK') && file_exists( $dir['root'] . 'install' ) )
{
	$ret = <<<EOJ
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
		<head>
			<title>Dolphin Smart Community Builder Installed</title>
			<link href="install/general.css" rel="stylesheet" type="text/css" />
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		</head>
		<body>
			<div id="main">
			<div id="header">
				<img src="install/images/boonex-logo.png" alt="" /></div>
			<div id="content">
				<div class="installed_pic">
					<img alt="Dolphin Installed" src="install/images/dolphin_installed.jpg" />
			</div>

			<div class="installed_text">
				Please, remove INSTALL directory from your server and reload this page to activate your community site.
			</div>
			<div class="installed_text">
				NOTE: Once you remove this page you can safely <a href="administration/modules.php">install modules via Admin Panel</a>.
			</div>
		</body>
	</html>
EOJ;
	echo $ret;
	exit();
}

// set error reporting level
if (version_compare(phpversion(), "5.3.0", ">=") == 1)
 // error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
else
  error_reporting(E_ALL & ~E_NOTICE);
set_magic_quotes_runtime(0);
ini_set('magic_quotes_sybase', 0);

// set default encoding for multibyte functions
mb_internal_encoding('UTF-8');
mb_regex_encoding('UTF-8');
ini_set('error_reporting',E_ALL);
ini_set('display_errors', 'On');

 error_reporting(1);
require_once(BX_DIRECTORY_PATH_INC . "security.inc.php");
require_once(BX_DIRECTORY_PATH_ROOT . "flash/modules/global/inc/header.inc.php");
require_once(BX_DIRECTORY_PATH_ROOT . "flash/modules/global/inc/content.inc.php");
require_once(BX_DIRECTORY_PATH_CLASSES . "BxDolService.php");
require_once(BX_DIRECTORY_PATH_CLASSES . 'BxDolAlerts.php');
require_once(BX_DIRECTORY_PATH_ROOT . "log4php/logForCommon.php"); 
$oZ = new BxDolAlerts('system', 'begin', 0);
$oZ->alert();
?>
