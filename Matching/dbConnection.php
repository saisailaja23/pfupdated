<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//dbConnection.php
?>
<?php
$con = mysql_connect("192.168.50.64","arun","media");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("test", $con);
//echo "connection ".$con;
//print_r($con);

/*
require_once( '../inc/header.inc.php' );
session_start();
define('BX_PROFILE_PAGE', 1);
require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

bx_import('BxTemplProfileView');
bx_import('BxDolInstallerUtils');
*/
?>