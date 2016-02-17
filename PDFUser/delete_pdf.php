<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php

define('BX_PROFILE_PAGE', 1);
require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
//bx_import('BxTemplProfileView');
//bx_import('BxDolInstallerUtils');
require_once ('phpfunctions.php');

$sel_pdf_id = $_POST['sel_pdf_ID'];
//echo $sel_pdf_id;
$pdf_ids     = split(",",$sel_pdf_id);
//print_r($pdf_ids);
foreach($pdf_ids AS $pdf_id)
{    
    if($pdf_id)
    {
        //echo " pdf id ".$pdf_id."<br/>";
        //$pdfuserDetails    = getusertempDetails($pdf_id);
        //print_r($pdfuserDetails);
        //$htmlFile               = $this->basepath."PDFTemplates/user/".$this->templateID."_preview_wkhtml.html";
        //echo "<br/><br/>";

        $delete_pdf = "UPDATE pdf_template_user SET isDeleted ='Y' WHERE template_user_id = $pdf_id";
        mysql_query($delete_pdf);
    }
}
?>