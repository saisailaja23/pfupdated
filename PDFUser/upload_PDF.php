<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
//display_errors(0);
define('BX_PROFILE_PAGE', 1);
//require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once(BX_DIRECTORY_PATH_CLASSES . 'BxDolFilesDb.php');
bx_import('BxTemplProfileView');
bx_import('BxDolInstallerUtils');
bx_import('BxDolFilesModule');

require_once ('PDFUser/phpfunctions.php');
require_once ('generatePDF.php');
//require_once('PDFreactor/wrappers/php/lib/PDFreactor.class.php');
//require_once('resizeImage.php');
//require_once('rotate_img.php');
?>

<?php
check_logged();
if(!isLogged()) {
header('Location:'.$GLOBALS['site']['url']);
exit;
}
$parent_ID              =   getLoggedId();
$tempusrid              = $_GET['tempusrid'];

$oProfile = new BxBaseProfileGenerator($parent_ID);
$aCopA = $oProfile->_aProfile;
$aCopB = $oProfile->_aCouple;

$parentHeading          = ($aCopB['FirstName'])?$aCopA['FirstName']." and ".$aCopB['FirstName']."'s":$aCopA['FirstName'];

if($_POST)
{

    $parent_ID                              =   getLoggedId();
    //$coverTitle       = stripslashes(htmlentities($_POST['CoverTitle'], ENT_QUOTES));
    $tempDescripton   = stripslashes(htmlentities($_POST['temp_description'], ENT_QUOTES));
    $currentTime      =	date("Y-m-d H:i:s",time());
    $currentDate      =	date("Y-m-d",time());

    $update_defalutPDF      = "UPDATE pdf_template_user SET isDefault ='N' WHERE user_id =$parent_ID";
    mysql_query($update_defalutPDF);
    
    $insert_tempuser         = "INSERT INTO pdf_template_user (user_id, template_id,template_file_path,template_description,isDeleted,isDefault,lastupdateddate)
                VALUES ($parent_ID,0,'','$tempDescripton','N','Y','$currentTime')";
    //echo "insertquery ".$insert_tempuser."<br/>";
    mysql_query($insert_tempuser);
    $template_user_id = mysql_insert_id();
    $templateFilepath = $GLOBALS['dir']['root']."PDFTemplates/user/".$parent_ID."_".$template_user_id."_".$currentDate.".pdf";

    /*$insert_tempuser_data    = "INSERT INTO pdf_template_data (template_user_id, cover_title,cover_picture,block_ids,photo_title,photo_ids,photo_description)
                VALUES ($template_user_id,'$coverTitle','','','','','')";
    mysql_query($insert_tempuser_data);
    */
    $update_filepath         = "UPDATE pdf_template_user SET template_file_path = '$templateFilepath' WHERE template_user_id = $template_user_id";
    //echo "update query  ".$update_filepath."<br/>";
    mysql_query($update_filepath);
    //$uploadfile								= 	$uploaddir . basename($_FILES['userfile']['name']);
    $uploadfile     = './PDFTemplates/user/'.$parent_ID.'_'.$template_user_id.'_'.$currentDate.'.pdf';
    //echo "upload file ".$uploadfile."<br/>";

    //print_r($_FILES['new_pdf']['tmp_name']);
    if (move_uploaded_file($_FILES['new_pdf']['tmp_name'], $uploadfile))
    {
     //echo "success";
       header( "Location: ".$GLOBALS['site']['url']."page/userprofile" ) ;
     }
    else
    {
        //echo "failed";
    }
}
?>
<!--<link type="text/css" href="Matching/css/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript" src="Matching/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="Matching/js/jquery-ui-1.8.2.custom.min.js"></script>-->

<link rel="STYLESHEET" type="text/css" href="PDFUser/Processing/dialog.css">
<script type="text/javascript" src= "PDFUser/Processing/dialog.js"></script>
<link rel="STYLESHEET" type="text/css" href="PDFUser/css/style.css">
<script type="text/javascript">
 function showProcessing()
{
    var progressDiv = $('#processingDIv').append('div');
    $(progressDiv).html('<img src="images/loader.gif" width="30px">');
}
function HideBar()
 {
    $( ".loader" ).remove();
 }
</script>

<div class="disignBoxFirst MOA">
	<div class="boxContent">
    <form method="post" enctype="multipart/form-data" id="upload_form" name="upload_form">
        <div class="form_advanced_wrapper join_form_wrapper">
            <nav class="nav" >
                <ul>                                
                <li class="last" style='margin-left: 672px;'><a href="<?php echo $GLOBALS['site']['url'].'page/pdfcreate';?>">Create PDF Profile</a></li>
                <li class="last" style='margin-left: 10px;'><a href="<?php echo $GLOBALS['site']['url'].'page/userprofile';?>">Manage Print Profile</a></li>
                </ul>
            </nav>
            <table id="join_form_table" class="form_advanced_table"  cellpadding="0" cellspacing="0">
                 <thead class="">
                    <tr class="headers">
                        <th class="grayIcons" style="text-align:left;padding-left:0px;" colspan="2">
                            User Profile
                                                    
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="caption">
                            Upload PDF:
                            <span class="required_class">*</span>
                        </td>                        
                    </tr>
                    <tr>
                        <td class="value">
                            <div class="clear_both"></div>
                                <div class="input_wrapper input_wrapper_select" style="width:250px;">
                                    <input class="form_input_file" type="file" name="new_pdf" id="new_pdf" >
                                    <div class="input_close input_close_file"></div>
                                </div><div class="boxContent" id="processingDIv"></div>
                            <div class="clear_both"></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="caption">
                            Template Description:
                        </td>                        
                    </tr>
                    <tr>
                        <td class="value">
                            <div class="clear_both"></div>
                                <div class="input_wrapper input_wrapper_textarea" style="width:663px;height:100px;">
                                    <textarea id="temp_description" name="temp_description" class="form_input_textarea" style="height:100px;" ><?php echo $tempuserDetails['template_description'];?></textarea>

                                <div class="input_close input_close_textarea"></div>
                                </div>
                            <div class="clear_both"></div>
                        </td>
                    </tr>
                    <tr>                        
                        <td class="value">
                            <div class="clear_both"></div>
                            <div class="input_wrapper input_wrapper_submit" style="left:0px;cursor:pointer;">
                                <div class="button_wrapper"  id="do_upload_save" style="width:62px;">
                                    <!--<input type="submit" value="Preview" class="form_input_submit" >-->
                                     <span class="form_input_submit pink-btn" style="position:relative;top:0px;left:2px;font-size:bold">SAVE</span>
                                    <div class="button_wrapper_close"></div>
                                </div>
                                <div class="input_close input_close_submit"></div>
                            </div>                           
                            <div class="clear_both"></div>
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>
      </form>
    </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function() {
 
 $("#do_upload_save").bind('click',function() {XpreocessSave($(this));});
 
 $("#temp_description").keydown(function(event){
        var key = event.which;
        //all keys including return.
        if(key == 8)
        {
            var length = this.value.length;
        }
        if((key >= 32 || key == 13) && key !=  46 && key !=  37 && key !=  38 && key !=  39 && key !=  40) {
            var maxLength = 255;
            var length = this.value.length;
            if(length >= maxLength) {
                event.preventDefault();
                $(this).attr('value',$(this).attr('value').substring(0,maxLength));                
                dhtmlx.message( {type : "error", text : "Number of character can not exeeds 255 letters"} );
            }
        }
        });


function XpreocessSave(item)
{
    var val = $("#new_pdf").val();
    switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
        case 'pdf': case 'PDF':
            showProcessing();
            $('#upload_form').submit();
            break;
        default:
            dhtmlx.message( {type : "error", text : "Please upload a PDF"} );
            return false;
            break;
    }
}
/*
dhtmlx.message({
    type: "alert",
    text: 'Your profile has been created and saved ! Please select "Manage Print Profile" in the Account quick links area to manage your profile options. Press the "OK" button below to return to account home.',
    callback: function() {window.location = redirect_path;}
});
*/
$("#new_pdf").change(function() {

    var val = $(this).val();
    switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
        case 'pdf': case 'PDF':            
            break;
        default:
            dhtmlx.message( {type : "error", text : "Please upload a PDF"} );
            return false;
            break;
    }
    });
});
</script>
