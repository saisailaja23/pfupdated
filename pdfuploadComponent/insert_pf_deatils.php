<?php
require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );


$parent_ID  =   getLoggedId();
$tempDescripton   = stripslashes(htmlentities($_POST['temp_description'], ENT_QUOTES));
$currentTime      =	date("Y-m-d H:i:s",time());
$currentDate      =	date("Y-m-d",time());

$update_defalutPDF      = "UPDATE pdf_template_user SET isDefault ='N' WHERE user_id =$parent_ID";
mysql_query($update_defalutPDF);
    
$insert_tempuser = "INSERT INTO pdf_template_user (user_id, template_id,template_file_path,template_description,isDeleted,isDefault,lastupdateddate)
                VALUES ($parent_ID,0,'','$tempDescripton','N','Y','$currentTime')";   
mysql_query($insert_tempuser);
$template_user_id = mysql_insert_id();
    
$templateFilepath = $GLOBALS['dir']['root']."PDFTemplates/user/".$parent_ID."_".$template_user_id."_".$currentDate.".pdf";

$update_filepath         = "UPDATE pdf_template_user SET template_file_path = '$templateFilepath' WHERE template_user_id = $template_user_id";
mysql_query($update_filepath);


 
?>
