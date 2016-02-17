<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php

define('BX_PROFILE_PAGE', 1);
require_once( '../inc/header.inc.php' );
//error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
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

$sel_tmpuser_ID     = $_POST['sel_tmpuser_ID'];

if($sel_tmpuser_ID)
{
    $genObject          = new generatePDF($sel_tmpuser_ID);
    $fileName           = $genObject->genWKHTML();
     
    $expld_filenmame  = explode('/', $fileName);
    $sizeofArray = sizeof($expld_filenmame)-1;
    $pdfName = $expld_filenmame[$sizeofArray];

    //echo "file name %%%%%%%%%%%%%%%".$fileName;
  //  $return             = array("filename" => $fileName);
     $return = array("filename" => $fileName,"pdfFilename"=>$pdfName);
    header("Content-Type: application/json", true);
    echo  json_encode($return);
    exit();
}
else
{
    $genObject          = new generatePDF();
    $fileName           = $genObject->genWKHTML();

    $expld_filenmame  = explode('/', $fileName);
    $sizeofArray = sizeof($expld_filenmame)-1;
    $pdfName = $expld_filenmame[$sizeofArray];


    //echo "file name".$fileName;
  //  $return             = array("filename" => $fileName);
    $return = array("filename" => $fileName,"pdfFilename"=>$pdfName);
    header("Content-Type: application/json", true);
    echo  json_encode($return);
    exit();
}


?>