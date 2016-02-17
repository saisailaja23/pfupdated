<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php
//error_reporting(1);
define('BX_PROFILE_PAGE', 1);
require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once(BX_DIRECTORY_PATH_CLASSES . 'BxDolFilesDb.php');
bx_import('BxTemplProfileView');
bx_import('BxDolInstallerUtils');
bx_import('BxDolFilesModule');

require_once ('phpfunctions.php');
require_once ('generatePDF.php');
//require_once('PDFreactor/wrappers/php/lib/PDFreactor.class.php');
require_once('resizeImage.php');
require_once('rotate_img.php');
//echo "hai";

$set_template_user_id   = $_POST['sel_tmpuser_ID'];
$set_user_id            = $_POST['sel_user_id'];

$update_defalutPDF      = "UPDATE pdf_template_user SET isDefault ='N' WHERE user_id =$set_user_id";
mysql_query($update_defalutPDF);

$update_setdefalutPDF   = "UPDATE pdf_template_user SET isDefault ='Y' WHERE template_user_id =$set_template_user_id";
mysql_query($update_setdefalutPDF);

$return             = array("template_user_id" => $set_template_user_id,"user_id" => $set_user_id);
header("Content-Type: application/json", true);
echo  json_encode($return);
exit();

?>