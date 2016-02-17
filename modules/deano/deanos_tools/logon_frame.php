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
$am=$_GET['am'];
$ap=$_GET['ap'];

$logged = getloggedID();
$profile_Role = getProfileInfo($logged);
if($logged !=  $am  || $profile_Role['ProfileType'] != 8) {
    header( "Location:../../../splash.php" );
}
else {
// logout current member
setcookie('memberID', '', time() - 96 * 3600, $t);
setcookie('memberPassword', '', time() - 96 * 3600, $t);
unset($_COOKIE['memberID']);
unset($_COOKIE['memberPassword']);

// switch to specified member
$sHost = '';
$bRememberMe = 0;
$iCookieTime = $bRememberMe ? time() + 24*60*60*30 : 0;
setcookie("memberID", $m, $iCookieTime, $t, $sHost);
$_COOKIE['memberID'] = $m;
setcookie("memberPassword", $p, $iCookieTime, $t, $sHost, false, true /* http only */);
$_COOKIE['memberPassword'] = $p;

setcookie("Deano", 'Deanotools', $iCookieTime, $t, $sHost);
$_COOKIE['Deano'] = 'Deanotools'; 
}
?>
<!DOCTYPE html>
<html lang="en">
<html>

<head>
  <meta name="revisit-after" content="15 days">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=9">
</head>

<frameset rows="34,*" framespacing="0" border="0" frameborder="0">
	<frame name="header" scrolling="no" noresize target="main" src="header_frame.php?m=<?php echo $am;?>&p=<?php echo $ap;?>&t=<?php echo $t;?>" marginwidth="1" marginheight="1">
	<frame name="main" src="../../../" marginwidth="1" marginheight="1" scrolling="auto" noresize>
	<noframes>
	<body>

	<p>This page uses frames, but your browser doesn't support them.</p>

	</body>
	</noframes>
</frameset>

</html>
<?php 

  

?>
