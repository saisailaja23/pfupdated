<?php
/***************************************************************************
* Date				: Sun August 1, 2010
* Copywrite			: (c) 2009, 2010 by Dean J. Bassett Jr.
* Website			: http://www.deanbassett.com
*
* Product Name		: Deanos Tools
* Product Version	: 1.8
*
* IMPORTANT: This is a commercial product made by Dean Bassett Jr.
* and cannot be modified other than personal use.
*  
* This product cannot be redistributed for free or a fee without written
* permission from Dean Bassett Jr.
*
***************************************************************************/
require_once ('../../../inc/header.inc.php');
require_once ('../../../inc/profiles.inc.php');

$m=$_GET['m'];
$p=$_GET['p'];
$t=$_GET['t'];

$profile_Role = getProfileInfo($m);

// logout current member
setcookie('memberID', '', time() - 96 * 3600, $t);
setcookie('memberPassword', '', time() - 96 * 3600, $t);
unset($_COOKIE['memberID']);
unset($_COOKIE['memberPassword']);

// switch back to origional admin
$sHost = '';
$iCookieTime = $bRememberMe ? time() + 24*60*60*30 : 0;
setcookie("memberID", $m, $iCookieTime, $t, $sHost);
$_COOKIE['memberID'] = $m;
setcookie("memberPassword", $p, $iCookieTime, $t, $sHost, false, true /* http only */);
$_COOKIE['memberPassword'] = $p;

// redirect back to deanos_tools based on admin or agency.

if($profile_Role['Role'] == 3) {
header( "Location:../../?r=deanos_tools/administration/&se=sa" );
}
else {
 header("Location:".$site['url']."extra_agency_view_27.php");   
}
?>
