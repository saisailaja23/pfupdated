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

$Profile_Id = getProfileInfo($m);

?>
<!DOCTYPE html>
<html lang="en">
<html>

<head>
  <meta name="revisit-after" content="15 days">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=9">
</head>
<body bgcolor="#000000" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF" text="#FFFFFF" topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<?php 

if($Profile_Id['Role'] == 3) { 

   
 ?>    
<div style="padding-left: 14px; padding-right: 4px; padding-top: 4px; padding-bottom: 4px"><a target="_top" href="break_frame.php?m=<?php echo $m;?>&p=<?php echo $p;?>&t=<?php echo $t;?>">Click here to go back to the admin console.</a></div>
<?php } else {
    ?>
<div style="padding-left: 14px; padding-right: 4px; padding-top: 4px; padding-bottom: 4px"><a target="_top" href="break_frame.php?m=<?php echo $m;?>&p=<?php echo $p;?>&t=<?php echo $t;?>">Click here to go back to the admin console.</a></div>

<?php 
     
} 

?>
</body>
</html>

