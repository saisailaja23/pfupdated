<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php
$control_number         = mt_rand(100, 1000000);
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
//display_errors(1);
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
require_once('resizeImage.php');
require_once('rotate_img.php');
?>

<?php
check_logged();
if(!isLogged()) {
header('Location:'.$GLOBALS['site']['url']);
exit;
}
$parent_ID              =   getLoggedId();
$tempusrid              = $_GET['tempusrid'];

//$genObject              = new generatePDF();
//$fileName             = $genObject->generatePDF();
$tempuserDetails        = getusertempDetails($tempusrid);
//print_r($tempuserDetails);
$oProfile = new BxBaseProfileGenerator($parent_ID);
$aCopA = $oProfile->_aProfile;
$aCopB = $oProfile->_aCouple;

$BlockArray             = getAllBlockData($parent_ID);
//print_r($BlockArray);
$parentHeading          = ($aCopB['FirstName'])?$aCopA['FirstName']." and ".$aCopB['FirstName']."'s":$aCopA['FirstName'];

?>
<!--<link type="text/css" href="Matching/css/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript" src="Matching/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="Matching/js/jquery-ui-1.8.2.custom.min.js"></script>-->

<link rel="STYLESHEET" type="text/css" href="PDFUser/Processing/dialog.css">
<script type="text/javascript" src= "PDFUser/Processing/dialog.js"></script>
<link rel="STYLESHEET" type="text/css" href="PDFUser/css/style.css">
<script type="text/javascript" src="pdfuploadComponent/js/model.js"></script>
<script type="text/javascript" src="pdfuploadComponent/js/controller.js"></script>
<script type="text/javascript">
 function showProcessing()
{
    REDIPS.dialog.init();
    REDIPS.dialog.op_high = 60;
    REDIPS.dialog.fade_speed = 18;
    REDIPS.dialog.show(183, 17, '<img width="183" height="17" title="" alt="" src="data:image/gif;base64,R0lGODlhtwARAPcDAJu44EFpoOLq9f///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFCgADACwAAAAAtwARAAAI/wAFCBxIsKDBgwgTKlzIsKHDhxAjSpxIsaLFixgzaowYoKNHjwIAiBw5MiRJkiZPikypkuVJlyhVlpS5kiYAmDNp4qyp0+bOmz59DvxI9KfRoD2TyjyqtCXSpU+dNn0Z9eVQoiCrxpy6FSrXnF7DShVL9StPsl1bXsUagClasGPjln17Vm7auXbh4t17F+VarG7z1uWrt2/hw4MNJ0YM1Gxjujf/FtXKODDhxZgfC9Z8mbNiz5WFCmTb0fJn06Edo868GjRryislf2xNG7br27VV2869VHZW3cAh8948vHNxv6NJH08tfLfz4MSf907Odvlr6MalR8d+WrQA0m21ZyTnznx7c/LXp39XLr47etzty48/r36j/fv48+vfz7+///8MBQQAIfkEBQoAAwAsDAACAAkADQAACBQAAwgcSLCgwYMIEypcyLChQ4YBAQAh+QQFCgADACwWAAIACQANAAAIFAADCBxIsKDBgwgTKlzIsKFDhgEBACH5BAUKAAMALCAAAgAJAA0AAAgUAAMIHEiwoMGDCBMqXMiwoUOGAQEAIfkEBQoAAwAsKgACAAkADQAACBQAAwgcSLCgwYMIEypcyLChQ4YBAQAh+QQFCgADACw0AAIACQANAAAIFAADCBxIsKDBgwgTKlzIsKFDhgEBACH5BAUKAAMALD4AAgAJAA0AAAgUAAMIHEiwoMGDCBMqXMiwoUOGAQEAIfkEBQoAAwAsSAACAAkADQAACBQAAwgcSLCgwYMIEypcyLChQ4YBAQAh+QQFCgADACxSAAIACQANAAAIFAADCBxIsKDBgwgTKlzIsKFDhgEBACH5BAUKAAMALFwAAgAJAA0AAAgUAAMIHEiwoMGDCBMqXMiwoUOGAQEAIfkEBQoAAwAsZgACAAkADQAACBQAAwgcSLCgwYMIEypcyLChQ4YBAQAh+QQFCgADACxwAAIACQANAAAIFAADCBxIsKDBgwgTKlzIsKFDhgEBACH5BAUKAAMALHoAAgAJAA0AAAgUAAMIHEiwoMGDCBMqXMiwoUOGAQEAIfkEBQoAAwAshAACAAkADQAACBQAAwgcSLCgwYMIEypcyLChQ4YBAQAh+QQFCgADACyOAAIACQANAAAIFAADCBxIsKDBgwgTKlzIsKFDhgEBACH5BAUKAAMALJgAAgAJAA0AAAgUAAMIHEiwoMGDCBMqXMiwoUOGAQEAIfkEBQoAAwAsogACAAkADQAACBQAAwgcSLCgwYMIEypcyLChQ4YBAQAh+QQFCgADACysAAIACQANAAAIFAADCBxIsKDBgwgTKlzIsKFDhgEBADs=" />');
}
function HideBar()
 {
        REDIPS.dialog.hide('undefined');

 }
  function newpdfWin(obj)
            {
                uploadController.loadValues({
                window_id       : "uploadProfile", //mandatory
                win_title       : "",
                upload_formats  : "pdf",       //mandatory                        //formats as comma seprated
                uploadURL       : "upload_PDF.php",      //mandatory                        //the path to the server-side script relative to the index file 
                SWFURL          : "../../../../../upload_PDF.php", //mandatory       //the path to the server-side script relative to the client flash script file
                msgOrAlert      : false,                                               //true for alert and false for message(in poup alert willnot work)
                autoStart       : false,                                               //true or false(default false)
                numberOfFiles   : 1,                                                  //default 100
                clickObj        : obj,               
                popup           : false
                });
                uploadController.initComponent();
            }
</script>
<style>
.dhxform_obj_dhx_skyblue .dhx_file_uploader div.dhx_upload_controls div.dhx_file_uploader_button {
    -moz-user-select: none;
    background-image: url("imgs/dhxform_dhx_skyblue/dhxform_upload_buttons1.gif"); 
    background-repeat: no-repeat;
    cursor: pointer;
    font-size: 2px;
    height: 19px;
    overflow: hidden;
    position: absolute;
    top: 8px;
    width: 19px;
} 
.dhxform_obj_dhx_skyblue fieldset.dhxform_fs {
    border: 1px solid #a4bed4;
    clear: left;
    margin-top: 5px;
    padding: 5px;
    width: 350px;
}
.dhxform_obj_dhx_skyblue .dhx_file_uploader.dhx_file_uploader_title div.dhx_upload_controls div.dhx_file_uploader_button.button_info {
    background-image: none;
    color: #a0a0a0;
    cursor: default;
    display: inline;
    font-size: 13px;
    height: auto;
    left: 35px;
    line-height: 20px;
   padding-top: 0px; 
    top: 0;
    vertical-align: top;
}
</style>
<div id="vp_container" style="height: auto; width: 1010px;">
<div class="disignBoxFirst MOA">	
	<div class="boxContent">
    <form method="post" enctype="multipart/form-data" id="join_form" name="join_form">
       <input type="hidden" id="previewFlag" name="previewFlag" value="0" />
       <input type="hidden" id="template_user_id" name="template_user_id" value="<?php echo $tempuserDetails['template_user_id'];?>" />
        <div class="form_advanced_wrapper join_form_wrapper">
            <nav class="nav" >
                <ul>                                
                 <li style='margin-left: 508px;' class="last"><a href="javascript:void(0);" id="createpdf">Upload PDF Profile</a></li>
                <li class="last" style='margin-left: 10px;'><a href="<?php echo $GLOBALS['site']['url'].'page/userprofile';?>">Manage Print Profile</a></li>
                </ul>
            </nav>
            <table id="join_form_table" class="form_advanced_table"  cellpadding="0" cellspacing="0">
                 <thead class="">
                    <tr class="headers">
                        <th class="grayIcons" style="text-align:left; padding:8px 8px 8px 0px;" colspan="2">
                            User Profile
                                      
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="caption">                            
                            <span class="required_class">*</span>Select Template:                            
                        </td>                        
                    </tr>
                    <tr>
                        <td class="value">
                            <div class="clear_both"></div>
                                <div class="input_wrapper input_wrapper_select" style="width:250px;">
                                    <?php
                                    $getTemplates           = getavailableTemplates($parent_ID);
                                    //print_r($getTemplates);
                                    $selectedTemplate		=	$tempuserDetails['template_id'];
                                    ?>
                                    <select class="form_input_select" name="TemplateSel" id="TemplateSel" style="width:250px;">
                                        <option value="">Please Select</option>
                                        <?php
                                        foreach($getTemplates as $getTemplate)
                                        {
                                        ?>
                                        <option <?php echo($selectedTemplate==$getTemplate['template_id'])?'selected':''; ?> value="<?php echo $getTemplate['template_id']; ?>"><?php echo $getTemplate['template_name']; ?></option>
                                    <?php
                                    }
                                    ?>
                                    </select>

                                <div class="input_close input_close_select"></div>
                                <img src="PDFUser/icons/searchIcon.png"height="25" width="25" class = "user_pdf_preview" style="position:relative;left:10px;top:8px;color:#FD7800;cursor:pointer;"  title="Preview Template" alt="Preview Template" />
                                </div>
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
                                <div class="input_wrapper input_wrapper_textarea" style="width:470px;height:100px;">
                                    <textarea id="temp_description" name="temp_description" class="form_input_textarea" style="height:100px;" ><?php echo $tempuserDetails['template_description'];?></textarea>

                                <div class="input_close input_close_textarea"></div>
                                </div>
                            <div class="clear_both"></div>
                        </td>
                    </tr>
                    <tr class="headers">
                        <th class="block_header" style="color: #57B4A8;font-size: 15px;font-weight: bold;" colspan="2">
                            Cover Settings
                        </th>
                    </tr>
                    <tr>
                        <td class="caption">
                            
                            <span class="required_class">*</span>Cover Title:  <span style="position:relative;left:05px;color:red;font-weight: bold;">(Maximum 75 Characters Only)</span>
                        </td>                        
                    </tr>
                    <tr>
                        <td class="value">
                            <div class="clear_both"></div>
                                <div class="input_wrapper input_wrapper_text" style="width:470px;">
                                    <input min="4" maxlength="75" class="form_input_text" value="<?php echo ($tempuserDetails['cover_title'])?$tempuserDetails['cover_title']:$parentHeading;?>" id="CoverTitle" name="CoverTitle" type="text">
                                    <input name="coverselect" id="coverselect" type="hidden" value="<?php echo $tempuserDetails['cover_picture'];?>">
                                <div class="input_close input_close_text"></div>
                            </div>

                            <div class="clear_both"></div>
                        </td>
                    </tr>
                    
                    <tr class="headers">
                        <th class="block_header" style="color: #57B4A8;font-size: 15px;font-weight: bold;" colspan="2">
                           Block Settings
                        </th>
                    </tr>
                    <tr>
                        <td class="caption">                            
                            Select Blocks :
                        </td>                        
                    </tr>
                    <tr>
                        <td class="value" >
                            <div class="clear_both"></div>
                            <a href="javascript:void(0);" style="position:relative;top:2px;" id="remove_blocks">
                                  Remove
                            </a>
                            <a href="javascript:void(0);" style="position:relative;top:2px;left:10px;" id="refresh_blocks">
                                  Add Blocks
                            </a>
                            <input name="blockselect" id="blockselect" type="hidden" value="<?php echo $tempuserDetails['block_ids'];?>">
                            <table class="block_table_grid" style="height:auto;">
                                <tr>                                
                                 <td style="padding-left: 0px;padding-right: 2px;">
                                    <div style="background-color:#E3EFFF;position:relative;top:1px;width:665px;height:auto;overflow:hidden;" id="Show_selcted_block_grid_<?=$control_number;?>"></div>
                                 </td>
                                </tr>
                            </table>
                            <div class="clear_both"></div>

                        </td>
                    </tr>
                    <tr class="headers">
                        <th class="block_header" style="color: #57B4A8;font-size: 15px;font-weight: bold;" colspan="2">
                            Picture Settings
                        </th>
                    </tr>
                    <tr>
                        <td class="caption">
                            
                            <span class="required_class">*</span>Picture Title:  <span style="position:relative;left:05px;color:red;font-weight: bold;">(Maximum 75 Characters Only)</span>
                        </td>                        
                    </tr>
                    <tr>
                        <td class="value">
                            <div class="clear_both"></div>
                                <div class="input_wrapper input_wrapper_text" style="width:470px;">
                                    <input min="4" maxlength="75"  class="form_input_text" value="<?php echo ($tempuserDetails['photo_title'])?$tempuserDetails['photo_title']:$parentHeading;?>" id="PictureTitle" name="PictureTitle" type="text">
                                <div class="input_close input_close_text"></div>
                            </div>

                            <div class="clear_both"></div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="caption">                           
                            Select Pictures:
                            <span style="position:relative;left:05px;color:red;font-weight: bold;">(You can input maximum upto 150 characters for photo description)</span>
                            
                            </div>
                        </td>                        
                    </tr>
                    <tr>
                        <td class="value">
                            <div class="clear_both"></div>
                                <div>
                                   
                                </div>
                            <table class="photos_table_grid">
                                <tr>
                                <td style="padding-left: 0px;padding-right: 2px;">
                                     <a href="javascript:void(0);" style="position:relative;top:2px;" id="remove_photos">
                                         Remove                                         
                                     </a>
                                     <a href="javascript:void(0);" style="position:relative;top:2px;left:10px;" id="refresh_photos">
                                          Add Photos
                                     </a>
                                     <span style="position:relative;top:2px;left:317px;color:red;font-weight: bold;">First photo will be printed as cover photo</span>
                                     <input name="photoselect" id="photoselect" type="hidden" value="<?php echo $tempuserDetails['photo_ids'];?>">
                                     <input name="photodescription" id="photodescription" type="hidden" value="<?php echo $tempuserDetails['photo_description'];?>">
                                     <div style="position:relative;top:8px;width:665px;height:auto;overflow:hidden;" id="Show_photos_grid_<?=$control_number;?>"></div>
                                 </td>
                                </tr>
                            </table>

                            <div class="clear_both"></div>
                        </td>
                    </tr>

                    <tr>                        
                        <td class="value">
                            <div class="clear_both"></div>                            
                            <div class="input_wrapper input_wrapper_submit" style="left:0px;cursor:pointer;">
                                <div class="button_wrapper"  id="do_save" style="width:62px;">
                                    <!--<input type="submit" value="Preview" class="form_input_submit" >-->
                                    <span class="form_input_submit pink-btn" style="position:relative;top:5px;left:2px;font-size:bold">SAVE</span>
                                    <div class="button_wrapper_close"></div>
                                </div>
                                <div class="input_close input_close_submit"></div>
                            </div>
                            <div class="input_wrapper input_wrapper_submit" style="left:0px;cursor:pointer;">
                                <div class="button_wrapper"  id="do_preview" style="width:62px;">
                                    <!--<input type="submit" value="Preview" class="form_input_submit" >-->
                                    <span class="form_input_submit pink-btn" style="position:relative;top:5px;left:2px;">PREVIEW</span>
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
</div>

<?php
//display photos in grid
    $getPhotos = getPhotoAlbums($parent_ID);
    //echo $tempuserDetails['photo_ids']." aa<br/>";
    $str = '<xml version="1.0" encoding="UTF-8" id="showphotos_xml" style="display:none"><rows height="30" valign="middle">';
    if(($tempuserDetails['photo_ids']) || ($tempuserDetails['template_user_id'] && $tempuserDetails['template_file_path']))
    {       
        $sel_photos_ids     = split(",",$tempuserDetails['photo_ids']);
        $sel_photos_desc    = split("##,,,##",$tempuserDetails['photo_description']);       
        
        for($cnt=0;$cnt<count($sel_photos_ids);$cnt++)
        {            
            $sel_img_photo        = selectedCoverImage($parent_ID,$sel_photos_ids[$cnt]);
            $sel_photo_desc       = $sel_photos_desc[$cnt];
            if($sel_img_photo)
            {
                $img_photo_src    = $GLOBALS['site']['url']."m/photos/get_image/file/".$sel_img_photo['Hash'].".".$sel_img_photo['Ext'];
                $filename = 'modules/boonex/photos/data/files/'.$sel_img_photo['ID'].'.'.$sel_img_photo['Ext'];
                $file_medium_name = 'modules/boonex/photos/data/files/'.$sel_img_photo['ID'].'_m.'.$sel_img_photo['Ext'];
                //echo " aaaaaaaa ".$filename."<br/>";
                if (file_exists($filename) && file_exists($file_medium_name)) {
                    $img_path = $GLOBALS['site']['url']."m/photos/get_image/file/".$sel_img_photo['Hash'].".".$sel_img_photo['Ext'];
                    $str .= '<row id="'. $sel_img_photo['Hash'] .'">
                                <cell style ="vertical-align:middle;text-align: center;">1</cell>
                                <cell style ="vertical-align:middle;text-align: left;"><![CDATA[
                                <img src="'.$img_path.'" height="50" width="50"/>]]></cell>
                                <cell style ="vertical-align:middle;white-space:normal;"><![CDATA['.$sel_photo_desc.']]></cell>
                            </row>';
                }
            }
        }
    }
    else
    {
        foreach($getPhotos as $photFetch)
        {
            
            $filename = 'modules/boonex/photos/data/files/'.$photFetch['ID'].'.'.$photFetch['Ext'];
            //echo " aaaaaaaa ".$filename."<br/>";
            $file_medium_name = 'modules/boonex/photos/data/files/'.$photFetch['ID'].'_m.'.$photFetch['Ext'];
            if (file_exists($filename) && file_exists($file_medium_name)) {
                $img_path = $GLOBALS['site']['url']."m/photos/get_image/file/".$photFetch['Hash'].".".$photFetch['Ext'];
                $str .= '<row id="'. $photFetch['Hash'] .'">
                            <cell style ="vertical-align:middle;text-align: center;"></cell>
                            <cell style ="vertical-align:middle;text-align: left;"><![CDATA[
                            <img src="'.$img_path.'" height="50" width="50"/>]]></cell>
                            <cell style ="vertical-align:middle;white-space:normal;"><![CDATA['.$photFetch['Desc'].']]></cell>
                        </row>';
            }
        }
    }

    $str .= '</rows></xml>';
    $fname = createXMLFile_photo($str);
    $pdf_photo_url = $GLOBALS['site']['url']."PDFUser/pdf_xml/".$fname;

    //echo $str;

//display seletced blocks in grid
    $blck_str_select = '<xml version="1.0" encoding="UTF-8" id="show_select_blocks_xml" style="display:none"><rows height="30" valign="middle">';

    if($tempuserDetails['block_ids'] || ($tempuserDetails['template_user_id'] && $tempuserDetails['template_file_path']))
    {
        $selected_blckIDS    = split(",",$tempuserDetails['block_ids']);
        foreach($selected_blckIDS as $sel_blckID)
        {

            foreach($BlockArray as $blck)
            {
            //  $blockheading = str_replace(',','', $blck['blockheading']);
                if($sel_blckID == $blck['blockname'])
                {
                $blck_name        = str_replace('"','&quot;',$blck['blockname']);
                $blck_str_select .= '<row id="'. $blck_name .'">
                            <cell style ="vertical-align:middle;text-align: center;">1</cell>
                            <cell style ="vertical-align:middle;text-align: left;"><![CDATA['.$blck['blockheading'].']]></cell>
                        </row>';
                }
            }
        }
    }
    else
    {
        foreach($BlockArray as $blck)
        {            
       //  $blockheading = str_replace(',','', $blck['blockheading']);
         //  $blockheading =  $blck['blockheading'];
            $blck_name        = str_replace('"','&quot;',$blck['blockname']);
            $blck_str_select .= '<row id="'. $blck_name .'">
                        <cell style ="vertical-align:middle;text-align: center;height: 35px;"></cell>
                        <cell style ="vertical-align:middle;text-align: left;height: 35px;"><![CDATA['.$blck['blockheading'].']]></cell>
                    </row>';
        }

    }

    $blck_str_select .= '</rows></xml>';
     $fname = createXMLFile_block($blck_str_select);
    $pdf_block_url = $GLOBALS['site']['url']."PDFUser/pdf_xml/".$fname;

    //echo $blck_str_select;

?>

<script type="text/javascript" src="documentViewer/controller/FlexPaperComponent.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
control_number      = '<?=$control_number;?>';
redirect_path       = '<?php echo $GLOBALS['site']['url']."page/userprofile";?>';
globalURL           = '<?php echo $GLOBALS['site']['url']?>';

dhxWins = new dhtmlXWindows();
dhxWins.enableAutoViewport(false);
dhxWins.attachViewportTo("vp");
//dhxWins.setImagePath("PDFUser/dhtmlxWindows/codebase/imgs/");

//block display selected blocks
    var userBlockSelectGrid;
    userBlockSelectGrid = new dhtmlXGridObject('Show_selcted_block_grid_'+control_number);
    userBlockSelectGrid.setImagePath("Matching/dhtmlxGrid/imgs/");
    userBlockSelectGrid.setHeader("#master_checkbox,Block Title");
    userBlockSelectGrid.setInitWidths("50,*");
    userBlockSelectGrid.setColAlign("center,left");
    userBlockSelectGrid.enableTooltips("false,false");
    userBlockSelectGrid.setColSorting("false,false");
    userBlockSelectGrid.setColTypes("ch,ro");
    //userBlockSelectGrid.enableColumnMove(true);
    //userBlockSelectGrid.enableMercyDrag(true);
    //userBlockSelectGrid.enableDragAndDrop(true);
    userBlockSelectGrid.setSkin("dhx_skyblue");
    userBlockSelectGrid.enableAutoHeight(true);
    userBlockSelectGrid.init();
    userBlockSelectGrid.attachEvent("onXLE", function(grid_obj,count){
        /*if(userBlockSelectGrid.findCell("Birth Parent Letter") != "")
        {
            userBlockSelectGrid.setCellExcellType('BPletter_PRF_2',0,"ro");
            userBlockSelectGrid.cellById('BPletter_PRF_2',0).setValue("");
        }*/
    });
    //userBlockSelectGrid.parse(document.getElementById('show_select_blocks_xml'));
    userBlockSelectGrid.loadXML("<?php echo $pdf_block_url ?>");
 $('#createpdf').click(function() { 
            newpdfWin();  
            });
        
//block display photos
    var userPhotoGrid;
    userPhotoGrid = new dhtmlXGridObject('Show_photos_grid_'+control_number);
    userPhotoGrid.setImagePath("Matching/dhtmlxGrid/imgs/");
    userPhotoGrid.setHeader("#master_checkbox,Images,Description");
    userPhotoGrid.setInitWidths("50,120,*");
    userPhotoGrid.setColAlign("center,center,center");
    userPhotoGrid.enableTooltips("false,false,false");    
    userPhotoGrid.setColSorting("na,str,str");
    userPhotoGrid.setColTypes("ch,ro,ed");
    userPhotoGrid.enableDragAndDrop(true);
    userPhotoGrid.setSkin("dhx_skyblue");
    userPhotoGrid.enableAutoHeight(true);
    userPhotoGrid.enableMultiline(true);
    userPhotoGrid.init();
    userPhotoGrid.attachEvent("onEditCell", function(stage,rId,cInd,nValue,oValue){
        if(cInd == 2)
        {
            if(nValue)
            {
                var desc_value = nValue.toString();
                if((desc_value.length) >150)
               {
                   dhtmlx.message( {type : "error", text : "Number of characters in the Description cannot exceed 150"} );
                   return true;
               }
               else
                   return true;
            }
            else
                return true;
        }
        else
            return true;
    });
    //userPhotoGrid.parse(document.getElementById('showphotos_xml'));
    userPhotoGrid.loadXML("<?php echo $pdf_photo_url ?>");
   
 $("#do_preview").bind('click',function() {XpreocessPreview($(this));});

 $("#do_save").bind('click',function() {XpreocessSave($(this));});

 $(".user_pdf_preview").bind('click',function() {XpreocessPreview_org($(this));});

 $("#remove_photos").bind('click',function() {XpreocessremovePhotos();});

 $("#remove_blocks").bind('click',function() {XpreocessremoveBlocks();});
 
 $("#refresh_blocks").bind('click',function() {XpreocessrefreshBlocks();});

$("#refresh_photos").bind('click',function() {XpreocessrefreshPhotots();});


 //$('#cancelblockadd').live('click',function() {block_window.close();});

 //$('#savenewblock').live('click',function() {XpreocessSaveBlockSelection();});

 //$('#cancelphotoadd').live('click',function() {photos_window.close();});

 //$('#savenewphoto').live('click',function() {XpreocessSavePhotoSelection();});

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
                dhtmlx.message( {type : "error", text : "Number of characters cannot exceeds 255 letters"} );
            }
        }
        });
        
function dhxWinCustomPostion(windowObj,yPosition) {
        var position,splitPosition,xPosition;
        
        //windowObj.center();
        windowObj.centerOnScreen();
        position            =   windowObj.getPosition();
        splitPosition       =   String(position).split(",");
        xPosition           =   splitPosition[0];
        
        if(xPosition < 0)
            xPosition=50;
        //windowObj.setPosition(xPosition,yPosition);
        //window.scrollTo(xPosition,0);
        var offset = (document.body.scrollTop ? document.body.scrollTop : window.pageYOffset);
        windowObj.setPosition(position[0], position[1] + offset);
}
function XpreocessrefreshBlocks()
{
    var blockids = userBlockSelectGrid.getAllRowIds();
    cn_num     = Math.floor((Math.random() * 10000) );
    block_window = dhxWins.createWindow("window_block_"+cn_num, 0, 0, 560, 400);
    block_window.setText("Add Blocks");
    block_window.setModal(true);
    block_window.button("park").hide();
    block_window.button("minmax1").hide();
    dhxWinCustomPostion(block_window,300);

    block_toolbar_style  = { add_toolbar: {
            "icon_path": globalURL+"PDFUser/icons/",
                    items: [
                            {
                                type: "button",
                                id: "add",
                                text: "Add",
                                img: "add.png"
                            },
                            {
                                type: "button",
                                id: "close",
                                text: "Close",
                                img: "delete.png"
                            }

                    ]
        }
    };

    block_layout                  =   block_window.attachLayout( '1C' );
    block_layout.cells("a").hideHeader();
    block_layout.progressOn();
    block_toolbar                 =   block_layout.cells("a").attachToolbar( block_toolbar_style.add_toolbar );

    block_toolbar.attachEvent("onClick", function(toolbarid)
    { 
    switch(toolbarid){
        case 'add': 
            XpreocessSaveBlockSelection();                                       
            break;
        case 'close':
            block_window.close();
            break;
    }            

    });

    userAddBlockGrid               =  block_layout.cells("a").attachGrid( );     
    //userAddBlockGrid.setImagePath("Matching/dhtmlxGrid/imgs/");
    userAddBlockGrid.setHeader("#master_checkbox,Block Title");
    userAddBlockGrid.setInitWidths("50,*");
    userAddBlockGrid.setColAlign('center,left');
    userAddBlockGrid.setColVAlign('middle,middle');
    userAddBlockGrid.enableTooltips("false,false");
    userAddBlockGrid.setColSorting("false,false");
    userAddBlockGrid.setColTypes("ch,ro");
    userAddBlockGrid.enableDragAndDrop(true);
    //userAddBlockGrid.setSkin("dhx_skyblue");
    userAddBlockGrid.setAwaitedRowHeight(30);
    //userAddBlockGrid.enableAutoHeight(true,400);
    userAddBlockGrid.init();
    URLParams = "section=blocks&selblockids="+blockids;
    /*dhtmlxAjax.post( "PDFUser/getAddingProfileData.php", URLParams, function(loader)
    {
        try
        {				
                var json = JSON.parse( loader.xmlDoc.responseText );
                //console.log(json);
                if( json.status == "success" )	
                {     
                        userAddBlockGrid.clearAll();

                        //dhtmlx.message( {text : "Data store 100% loaded" } );

                        userAddBlockGrid.parse( eval(json.block_list), "json");

                        //self.progressOff( uid );
                }
                else
                {
                        dhtmlx.message( {type : "error", text : json.response} );
                        block_window.close();
                }
        }
        catch(e)
        {
                dhtmlx.message( {type : "error", text : "Fatal error on server side: "+loader.xmlDoc.responseText } );
                block_window.close();
                console.log(e.stack);
        }
    });*/
            
    userAddBlockGrid.load("PDFUser/getProfileData.php?"+URLParams, function(grid_obj,count)
    {   
        var first_rowID = userAddBlockGrid.getRowId(0);
        if(first_rowID == 'No')
        {
            userAddBlockGrid.deleteColumn(0);   
            block_toolbar.removeItem('add');
        }
        block_layout.progressOff();
                    
    });        
}

function XpreocessSaveBlockSelection()
{
    var checkIds = userAddBlockGrid.getCheckedRows(0);
    //alert(checkIds);
    var mySplitResult = checkIds.split(",");
    sel_col     = "";
    for(i = 0; i < mySplitResult.length; i++){
        //alert(mySplitResult[i]);
        column_sel_val      = userAddBlockGrid.cells(mySplitResult[i],0).getValue();
       // if(column_sel_val == 'yes')
          if(column_sel_val == '1')
        {
            userBlockSelectGrid.addRow(mySplitResult[i],[1, userAddBlockGrid.cells(mySplitResult[i],1).getValue()]);
            userBlockSelectGrid.setCellTextStyle(mySplitResult[i],0,"vertical-align:middle;text-align: center;");
            userBlockSelectGrid.setCellTextStyle(mySplitResult[i],1,"vertical-align:middle;text-align: left;");
            
        }
    }
    block_window.close();
    //jQuery("#showAddBlockDialog").dialog('close');
}

function XpreocessrefreshPhotots()
{
     var photoids = userPhotoGrid.getAllRowIds();
    
     photos_window = dhxWins.createWindow("window_photos", 0, 0, 295, 400);
     photos_window.setText("Add Photos");
     photos_window.setModal(true);
     photos_window.button("park").hide();
     photos_window.button("minmax1").hide();
     //photos_window.bringToTop();
     dhxWinCustomPostion(photos_window,500);
    // photos_window.center();
     //URL = "PDFUser/getAddingProfileData.php?section=photos&selblockids="+photoids;
     //photos_window.attachURL(URL, true);
     
     photos_toolbar_style  = { add_toolbar: {
            "icon_path": globalURL+"PDFUser/icons/",
                    items: [
                            {
                                type: "button",
                                id: "add",
                                text: "Add",
                                img: "add.png"
                            },
                            {
                                type: "button",
                                id: "close",
                                text: "Close",
                                img: "delete.png"
                            }

                    ]
        }
    };

    photos_layout                  =   photos_window.attachLayout( '1C' );
    photos_layout.cells("a").hideHeader();
    photos_layout.progressOn();

    photos_toolbar                 =   photos_layout.cells("a").attachToolbar( photos_toolbar_style.add_toolbar );

    photos_toolbar.attachEvent("onClick", function(toolbarid)
    { 
    switch(toolbarid){
        case 'add': 
            XpreocessSavePhotoSelection();                                       
            break;
        case 'close':
            photos_window.close();
            break;
    }            

    });

    userAddPhotoGrid               =  photos_layout.cells("a").attachGrid( );     
    //userAddPhotoGrid.setImagePath("Matching/dhtmlxGrid/imgs/");
    userAddPhotoGrid.setHeader("#master_checkbox,Images");
    userAddPhotoGrid.setInitWidths("50,*");
    userAddPhotoGrid.setColVAlign('middle,middle');
    userAddPhotoGrid.setColAlign("center,center");
    userAddPhotoGrid.enableTooltips("false,false");
    userAddPhotoGrid.setColSorting("false,false");
    userAddPhotoGrid.setColTypes("ch,ro");
    //userAddPhotoGrid.enableDragAndDrop(true);
    //userAddPhotoGrid.setSkin("dhx_skyblue");
    //userAddPhotoGrid.enableAutoHeight(true,400);
    userAddPhotoGrid.init();
        
    URLParams = "section=photos&selblockids="+photoids;
    /*dhtmlxAjax.post( "PDFUser/getAddingProfileData.php", URLParams, function(loader)
    {
        try
        {				
                var json = JSON.parse( loader.xmlDoc.responseText );
                //console.log(json);
                if( json.status == "success" )	
                {     
                        userAddPhotoGrid.clearAll();

                        //dhtmlx.message( {text : "Data store 100% loaded" } );

                        userAddPhotoGrid.parse( eval(json.photos_list), "json");

                        //self.progressOff( uid );
                }
                else
                {
                        dhtmlx.message( {type : "error", text : json.response} );
                        photos_window.close();
                }
        }
        catch(e)
        {
                dhtmlx.message( {type : "error", text : "Fatal error on server side: "+loader.xmlDoc.responseText } );
                photos_window.close();
                console.log(e.stack);
        }
    });*/
    userAddPhotoGrid.load("PDFUser/getProfileData.php?"+URLParams, function(grid_obj,count)
    { 
        var first_rowID = userAddPhotoGrid.getRowId(0);
        if(first_rowID == 'No')
        {
            userAddPhotoGrid.deleteColumn(0); 
            photos_toolbar.removeItem('add');
        }
         photos_layout.progressOff();           
    });
}

function XpreocessSavePhotoSelection()
{
     var checkIds = userAddPhotoGrid.getCheckedRows(0);
    //alert(checkIds);
    var mySplitResult = checkIds.split(",");
    sel_col     = "";
    for(i = 0; i < mySplitResult.length; i++){
        //alert(mySplitResult[i]);
        column_sel_val      = userAddPhotoGrid.cells(mySplitResult[i],0).getValue();
       // if(column_sel_val == 'yes')
          if(column_sel_val == '1')
        {
            userPhotoGrid.addRow(mySplitResult[i],[1, userAddPhotoGrid.cells(mySplitResult[i],1).getValue(), ""]);
            userPhotoGrid.setCellTextStyle(mySplitResult[i],0,"vertical-align:middle;text-align: center;");
            userPhotoGrid.setCellTextStyle(mySplitResult[i],1,"vertical-align:middle;text-align: left;");
            userPhotoGrid.setCellTextStyle(mySplitResult[i],2,"vertical-align:middle;text-align: center;");
        }
    }
    //jQuery("#showAddPhotoDialog").dialog('close');
    photos_window.close();
}

function XpreocessremovePhotos()
{
    var checkIds = userPhotoGrid.getCheckedRows(0);
    //alert(checkIds);
    if(checkIds)
    {
        var mySplitResult = checkIds.split(",");
        sel_col     = "";
        for(i = 0; i < mySplitResult.length; i++){
            //alert(mySplitResult[i]);
            column_sel_val      = userPhotoGrid.cells(mySplitResult[i],0).getValue();
        // if(column_sel_val == 'yes')
            if(column_sel_val == '1')
                userPhotoGrid.deleteRow(mySplitResult[i]);
        }
        $("div#Show_photos_grid_"+control_number).find("input[type=checkbox]").attr('checked','');
    }
    else
    {
        dhtmlx.message( {type : "error", text : "Select one or more items from the Photo List by clicking on the check boxes to delete"} );
    }
}

function XpreocessremoveBlocks()
{
    var checkIds = userBlockSelectGrid.getCheckedRows(0);
    //alert(checkIds);
    if(checkIds)
    {
        var mySplitResult = checkIds.split(",");
        sel_col     = "";
        for(i = 0; i < mySplitResult.length; i++){
            //alert(mySplitResult[i]);
            column_sel_val      = userBlockSelectGrid.cells(mySplitResult[i],0).getValue();
            //if(column_sel_val == 'yes')
            if(column_sel_val == '1')
                userBlockSelectGrid.deleteRow(mySplitResult[i]);
        }
        $("div#Show_selcted_block_grid_"+control_number).find("input[type=checkbox]").attr('checked','');
    }
    else
    {
        dhtmlx.message( {type : "error", text : "Select one or more items from the Block List by clicking on the check boxes to delete"} );
    }
}

function XpreocessPreview(item)
{
    var photoids = userPhotoGrid.getCheckedRows(0);
    if(photoids)
    {
        photo_ID    = photoids.split(",");
        $('#coverselect').val(photo_ID[0]);
        $('#photoselect').val(photoids);
        var photo_desc_value    = "";
        for(i=0;i<photo_ID.length;i++)
        {
           desc_val    =  userPhotoGrid.cells(photo_ID[i],2).getValue();
           if((desc_val.toString().length) > 150)
           {
               dhtmlx.message( {type : "error", text : "Number of characters in the Description cannot exceed 150"} );
               return false;
           }
           if(photo_desc_value)
               photo_desc_value  += "##,,,##"+desc_val;
           else
               photo_desc_value  = (desc_val)?desc_val:" ";
        }
        $('#photodescription').val(photo_desc_value);
    }
    var blockids = userBlockSelectGrid.getCheckedRows(0);
    if(blockids)
    {
        $('#blockselect').val(blockids);
    }

    if($('#TemplateSel').val())
    {       
        if(blockids)
        {
            if(photoids)
        {
            if($('#CoverTitle').val())
            {
                if($('#PictureTitle').val())
                {
                    showProcessing();
                    $('#previewFlag').val(1);
                    $.ajax({
                         type     : "POST",
                         url      : 'PDFUser/regenearate_pdf.php',
                         cache    : false,
                         data     : $("form#join_form").serialize(),
                         datatype : "json",
                         success: function(data){
                             HideBar();
                         //    window.open(data.filename);
                          //   alert(data.filename);
                       var main_URL           = $(location).attr('protocol')+"//"+$(location).attr('host')+"/"; 
                       var application_url    = main_URL+'documentViewer/';      
                       var flexvalues = {
                       icons_path: 'documentViewer/icons/32px/' // mandatory
                       ,application_url: application_url // mandatory
                       ,application_path: '/var/www/html/pf/PDFTemplates/user/' // mandatory
                       // ,pdf_name: "2_1_136.pdf" // mandatory
                       ,pdf_name: data.pdfFilename  // mandatory
                       ,split_mode: false // not mandatory
                       ,magnification: 1.3  // not mandatory, default 1.1
                          }
                       FlexPaperComponent.callFlexPaper(flexvalues);    
                          
                       return false;

                            }
                     });
                }
                else
                {                  
                    dhtmlx.message( {type : "error", text : "Please enter picture title"} );
                }
            }
            else
            {
                dhtmlx.message( {type : "error", text : "Please enter cover title"} );
            }
            
        }
        else
        {
              
                dhtmlx.message( {type : "error", text : "Please select at least one Photo"} );
            }
        }
        else
        {
            dhtmlx.message( {type : "error", text : "Please select at least one Block"} );
        }

    }
    else
    {
        dhtmlx.message( {type : "error", text : "Please select at least one template"} );
        return false;
    }
}

function XpreocessSave(item)
{
    var photoids = userPhotoGrid.getCheckedRows(0);
    var blockids = userBlockSelectGrid.getCheckedRows(0);


    if(photoids)
    {       
        photo_ID    = photoids.split(",");
        $('#coverselect').val(photo_ID[0]);
        $('#photoselect').val(photoids);
        var photo_desc_value    = "";
        for(i=0;i<photo_ID.length;i++)
        {           
           desc_val    =  userPhotoGrid.cells(photo_ID[i],2).getValue();
           if((desc_val.toString().length) > 150)
               return false;
           if(photo_desc_value)
               photo_desc_value  += "##,,,##"+desc_val;
           else
               photo_desc_value  = (desc_val)?desc_val:" ";
        }
        $('#photodescription').val(photo_desc_value);        
    }
    else
    {
       $('#photoselect').val(photoids);
       $('#coverselect').val("");
    }
    
    
    if(blockids)
    {
        $('#blockselect').val(blockids);
    }
    else
       $('#blockselect').val(blockids);
   
    if($('#TemplateSel').val())
    {        
        if(blockids)
        {
            if(photoids)
        {
            if($('#CoverTitle').val())
            {
                if($('#PictureTitle').val())
                {
                    $('#previewFlag').val(0);
                    //$('#join_form').submit();
                    showProcessing();
                    //$('#previewFlag').val(1);
                    $.ajax({
                         type     : "POST",
                         url      : 'PDFUser/regenearate_pdf.php',
                         cache    : false,
                         data     : $("form#join_form").serialize(),
                         datatype : "json",
                         success: function(data){
                             HideBar();
                             //window.open(data.filename);                           
                             
                            dhtmlx.message({
                                type: "alert",
                                text: 'Your profile has been created and saved !  Press the "OK" button below to return to "Manage PDF Profile".',
                                callback: function() {window.location = redirect_path;}
                            });
                            return false;

                            }

                     });
                }
                else
                {
                    dhtmlx.message( {type : "error", text : "Please enter picture title"} );

                }
            }
            else
            {
                dhtmlx.message( {type : "error", text : "Please enter cover title"} );
            }
        }
        else
        {
                dhtmlx.message( {type : "error", text : "Please select at least one Photo"} );
            }
        }
        else
        {
            dhtmlx.message( {type : "error", text : "Please select at least one Block"} );
        }

    }
    else
    {
        dhtmlx.message( {type : "error", text : "Please select at least one template"} );
        return false;
    }
}

function XpreocessPreview_org(item)
{
    if($('#TemplateSel').val())
    {
        var temp_org_path   = '<?php echo $GLOBALS['site']['url']."PDFTemplates/"?>'+$('#TemplateSel').val()+'/preview.pdf';
        //alert(temp_org_path);
        $.ajax({
            url:temp_org_path,
            type:'HEAD',
            error:
                function(){                    
                    dhtmlx.message( {type : "error", text : "Preview file not exists"} );
                },
            success:
                function(){
                    window.open(temp_org_path);
                }
        });
       
    }
    else
    {        
        dhtmlx.message( {type : "error", text : "Please select at least one template"} );
        return false;
    }
}
});


window.onload=function(){
$("#vp").css("overflow","visible");
};
</script>
