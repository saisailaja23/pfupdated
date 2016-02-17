<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php
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
<link type="text/css" href="Matching/css/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript" src="Matching/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="Matching/js/jquery-ui-1.8.2.custom.min.js"></script>

<link rel="STYLESHEET" type="text/css" href="PDFUser/Processing/dialog.css">
<script type="text/javascript" src= "PDFUser/Processing/dialog.js"></script>


<link rel="STYLESHEET" type="text/css" href="Matching/dhtmlxGrid/dhtmlxgrid.css"/>
<link rel="STYLESHEET" type="text/css" href="Matching/dhtmlxGrid/skins/dhtmlxgrid_dhx_skyblue.css"/>
<link rel="STYLESHEET" type="text/css" href="Matching/dhtmlxToolbar/codebase/skins/dhtmlxtoolbar_dhx_skyblue.css">

<link rel="stylesheet" type="text/css" href="PDFUser/dhtmlxWindows/codebase/dhtmlxwindows.css">
<link rel="stylesheet" type="text/css" href="PDFUser/dhtmlxWindows/codebase/skins/dhtmlxwindows_dhx_skyblue.css">

<script  src="Matching/dhtmlxGrid/dhtmlxcommon.js"></script>
<script  src="Matching/dhtmlxGrid/dhtmlxgrid.js"></script>
<script  src="Matching/dhtmlxGrid/ext/dhtmlxgrid_pgn.js"></script>
<script  src="Matching/dhtmlxGrid/ext/dhtmlxgrid_drag.js"></script>
<script  src="Matching/dhtmlxGrid/ext/dhtmlxgrid_mcol.js"></script>
<script  src="Matching/dhtmlxGrid/dhtmlxgridcell1.js"></script>
<script  src="Matching/dhtmlxToolbar/codebase/dhtmlxtoolbar.js"></script>
<script  src="Matching/dhtmlxGrid/ext/dhtmlxgrid_filter.js"></script>



<script src="PDFUser/dhtmlxWindows/codebase/dhtmlxwindows.js"></script>
<script src="PDFUser/dhtmlxWindows/codebase/dhtmlxcontainer.js"></script>

<style type="text/css">
.chk_block {
        position:relative;width:auto;
}
.chk_block_span {
        position:relative;top:2px;
}
.chk_dummy{
    position:relative;width:auto;border:1px solid white;
}

.block_table_grid .odd_dhx_skyblue td {
background-color:#E3EFFF;
}

.block_table_grid .odd_dhx_skyblue{
height: 35px;

}
.block_table_grid .ev_dhx_skyblue{
height: 35px;
}

.photos_table_grid .odd_dhx_skyblue td {
background-color:#E3EFFF;
}

.photos_table_grid .odd_dhx_skyblue{
height: 60px;

}
.photos_table_grid .ev_dhx_skyblue{
height: 60px;
}
.button_style{
    position:relative;
    top:3px;
    font-family:Verdana,Arial;
    font-size:11px;
    background-image:none;
    width:auto;
    cursor:pointer;
    color:black;
    padding-bottom:20px;
    padding-left:6px;
    padding-right:6px;
    padding-top:15px;
}
.form_advanced_table td.caption {
width:328px;
}
</style>
<script type="text/javascript">
 function showProcessing()
{
    REDIPS.dialog.init();
    REDIPS.dialog.op_high = 60;
    REDIPS.dialog.fade_speed = 18;
    REDIPS.dialog.show(200, 200, 'animation.gif');
}
function HideBar()
 {
        REDIPS.dialog.hide('undefined');

 }

</script>
<div id="vp_container" style="height: auto; width: auto;">
<div class="disignBoxFirst MOA">	
	<div class="boxContent">
    <form method="post" enctype="multipart/form-data" id="join_form" name="join_form">
       <input type="hidden" id="previewFlag" name="previewFlag" value="0" />
       <input type="hidden" id="template_user_id" name="template_user_id" value="<?php echo $tempuserDetails['template_user_id'];?>" />
        <div class="form_advanced_wrapper join_form_wrapper">
            <table id="join_form_table" class="form_advanced_table"  cellpadding="0" cellspacing="0">
                 <thead class="">
                    <tr class="headers">
                        <th class="block_header" colspan="2">
                            User Profile
                            <!--<a href="<?php echo $GLOBALS['site']['url']."page/userprofile";?>" style="position:relative;left:760px;">Back to Listing</a>-->
                           <div class="button_wrapper" style="margin-left:495px;margin-top:0px;">
                                <a href="<?php echo $GLOBALS['site']['url'].'page/uploadpdf';?>">
                                    <span class="form_input_submit button_style">Upload PDF Profile</span>
                                </a>
                                <div class="button_wrapper_close"></div>
                            </div>
                            <div class="button_wrapper" style="margin-left:10px;">
                                <a href="<?php echo $GLOBALS['site']['url']."page/userprofile";?>">
                                     <span class="form_input_submit button_style">Manage Print Profile</span>
                                </a>
                                <div class="button_wrapper_close"></div>
                            </div>
                            <div class="button_wrapper" style="margin-left:10px;">
                                <a href="<?php echo $GLOBALS['site']['url']."member.php";?>">
                                    <span class="form_input_submit button_style">Back to Account</span>
                                </a>
                                <div class="button_wrapper_close"></div>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="caption">
                            <span class="required">*</span>
                            Select Template:
                        </td>
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
                                <img src="PDFUser/icons/preview.jpg"height="25" width="25" class = "user_pdf_preview" style="position:relative;left:10px;top:8px;color:#FD7800;cursor:pointer;"  title="Preview Template" alt="Preview Template" />
                                </div>
                            <div class="clear_both"></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="caption">                            
                            Template Description:
                        </td>
                        <td class="value">
                            <div class="clear_both"></div>
                                <div class="input_wrapper input_wrapper_textarea" style="width:620px;height:100px;">
                                    <textarea id="temp_description" name="temp_description" class="form_input_textarea" style="height:100px;" ><?php echo $tempuserDetails['template_description'];?></textarea>

                                <div class="input_close input_close_textarea"></div>
                                </div>
                            <div class="clear_both"></div>
                        </td>
                    </tr>
                    <tr class="headers">
                        <th class="block_header" colspan="2">
                            Cover Settings
                        </th>
                    </tr>
                    <tr>
                        <td class="caption">
                            <span class="required">*</span>
                            Cover Title: <span style="position:relative;left:05px;color:red;font-weight: bold;">(Maximum 75 Characters Only)</span>
                        </td>
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
                        <th class="block_header" colspan="2">
                           Block Settings
                        </th>
                    </tr>
                    <tr>
                        <td class="caption">                            
                            Select Blocks :
                        </td>
                        <td class="value" >
                            <div class="clear_both"></div>
                            <span style="position:relative;top:2px;color:#FD7800;cursor:pointer;" id="remove_blocks">
                                  Remove
                            </span>
                            <span style="position:relative;top:2px;left:10px;color:#FD7800;cursor:pointer;" id="refresh_blocks">
                                  Add Blocks
                            </span>
                            <input name="blockselect" id="blockselect" type="hidden" value="<?php echo $tempuserDetails['block_ids'];?>">
                            <table class="block_table_grid" style="height:auto;">
                                <tr>                                
                                 <td style="padding-left: 0px;padding-right: 2px;">
                                    <div style="background-color:#E3EFFF;position:relative;top:1px;width:621px;;height:auto;overflow:hidden;" id="Show_selcted_block_grid"></div>
                                 </td>
                                </tr>
                            </table>
                            <div class="clear_both"></div>

                        </td>
                    </tr>
                    <tr class="headers">
                        <th class="block_header" colspan="2">
                            Picture Settings
                        </th>
                    </tr>
                    <tr>
                        <td class="caption">
                            <span class="required">*</span>
                            Picture Title:<span style="position:relative;left:05px;color:red;font-weight: bold;">(Maximum 75 Characters Only)</span>
                        </td>
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
                            <div style="position:relative;margin-left:00px;margin-top:20px;width:300px;height:50px;">
                                <span style="color:red;font-weight: bold;white-space: normal;">(You can input maximum upto 150 characters for photo description)
                            </span>
                            </div>
                        </td>
                        <td class="value">
                            <div class="clear_both"></div>
                                <div>
                                   
                                </div>
                            <table class="photos_table_grid">
                                <tr>
                                <td style="padding-left: 0px;padding-right: 2px;">
                                     <span style="position:relative;top:2px;color:#FD7800;cursor:pointer;" id="remove_photos">
                                         Remove                                         
                                     </span>
                                     <span style="position:relative;top:2px;left:10px;color:#FD7800;cursor:pointer;" id="refresh_photos">
                                          Add Photos
                                     </span>
                                     <span style="position:relative;top:2px;left:185px;color:red;font-weight: bold;">First photo will be printed as cover photo</span>
                                     <input name="photoselect" id="photoselect" type="hidden" value="<?php echo $tempuserDetails['photo_ids'];?>">
                                     <input name="photodescription" id="photodescription" type="hidden" value="<?php echo $tempuserDetails['photo_description'];?>">
                                     <div style="position:relative;top:8px;width:621px;;height:auto;overflow:hidden;" id="Show_photos_grid"></div>
                                 </td>
                                </tr>
                            </table>

                            <div class="clear_both"></div>
                        </td>
                    </tr>

                    <tr>
                        <td class="caption">
                        &nbsp;
                        </td>
                        <td class="value">
                            <div class="clear_both"></div>                            
                            <div class="input_wrapper input_wrapper_submit" style="left:10px;cursor:pointer;">
                                <div class="button_wrapper"  id="do_save" style="width:62px;">
                                    <!--<input type="submit" value="Preview" class="form_input_submit" >-->
                                    <span class="form_input_submit" style="position:relative;top:5px;left:2px;">Save</span>
                                    <div class="button_wrapper_close"></div>
                                </div>
                                <div class="input_close input_close_submit"></div>
                            </div>
                            <div class="input_wrapper input_wrapper_submit" style="left:20px;cursor:pointer;">
                                <div class="button_wrapper"  id="do_preview" style="width:62px;">
                                    <!--<input type="submit" value="Preview" class="form_input_submit" >-->
                                    <span class="form_input_submit" style="position:relative;top:5px;left:2px;">Preview</span>
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
<div id="PDFsuccessdialog" style="display:none;">
    <div style="position:relative;color:black;font-family:Verdana,Arial;line-height: 18px;font-size:11px;height:75px;width:100%">
        Your profile has been created and saved ! Please select "Manage Print Profile" in the Account quick links area to manage your profile options. Press the "OK" button below to return to account home.
    </div>
    <div style="position:relative;height:30px;width:100%;">
        <!--<input type="submit" id="button_ok" value="Ok" style="width:30px;position: relative;right:10px;"/>-->
        <div class="input_wrapper input_wrapper_submit" style="left:370px;">
            <div class="button_wrapper">
            <input type="submit" value="OK" id="button_ok" class="form_input_submit">
            <div class="button_wrapper_close"></div>
            </div>
            <div class="input_close input_close_submit"></div>
         </div>
    </div>

</div>

<!--<div id="showAddBlockDialog" style="display:none;">

    <div style="position:relative;" id="addblocksection"></div>
</div>

<div id="showAddPhotoDialog" style="display:none;">

    <div style="position:relative;" id="addphotossection"></div>
</div>
-->
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
                                <cell style ="vertical-align:middle;text-align: center;"></cell>
                                <cell style ="vertical-align:middle;text-align: left;">
                                &lt;img src="'.$img_path.'" height="50" width="50"/&gt;</cell>
                                <cell style ="vertical-align:middle;white-space:normal;">'.$sel_photo_desc.'</cell>
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
                            <cell style ="vertical-align:middle;text-align: left;">
                            &lt;img src="'.$img_path.'" height="50" width="50"/&gt;</cell>
                            <cell style ="vertical-align:middle;white-space:normal;">'.$photFetch['Desc'].'</cell>
                        </row>';
            }
        }
    }

    $str .= '</rows></xml>';
    $fname = createXMLFile_photo($str);
    $pdf_photo_url = $GLOBALS['site']['url']."PDFUser/pdf_xml/".$fname;
    echo $str;

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

                $blck_str_select .= '<row id="'. $blck['blockname'] .'">
                            <cell style ="vertical-align:middle;text-align: center;"></cell>
                            <cell style ="vertical-align:middle;text-align: left;">'.$blck['blockheading'].'</cell>
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
            $blck_str_select .= '<row id="'. $blck['blockname'] .'">
                        <cell style ="vertical-align:middle;text-align: center;height: 35px;"></cell>
                        <cell style ="vertical-align:middle;text-align: left;height: 35px;">'.$blck['blockheading'].'</cell>
                    </row>';
        }

    }

    $blck_str_select .= '</rows></xml>';
    $fname = createXMLFile_block($blck_str_select);
    $pdf_block_url = $GLOBALS['site']['url']."PDFUser/pdf_xml/".$fname;



    echo $blck_str_select;




function createXMLFile_photo($str) 
{

$pid   = getLoggedId();       
$myFile = $GLOBALS['dir']['root'].'PDFUser/pdf_xml/'."pdfphoto_".$pid.".xml"; 
$myFile_Name = "pdfphoto_".$pid.".xml";                       
$fh = fopen($myFile, 'w') or die("can't open file");                  
fwrite($fh, $str);
fclose($fh);
chmod($myFile, 0777);
return $myFile_Name;
}

function createXMLFile_block($str) 
{

$pid   = getLoggedId();       
$myFile = $GLOBALS['dir']['root'].'PDFUser/pdf_xml/'."pdfblock_".$pid.".xml"; 
$myFile_Name = "pdfblock_".$pid.".xml";                       
$fh = fopen($myFile, 'w') or die("can't open file");                  
fwrite($fh, $str);
fclose($fh);
chmod($myFile, 0777);
return $myFile_Name;
}



?>


<script type="text/javascript">
jQuery(document).ready(function() {

jQuery("#PDFsuccessdialog").dialog(
            {
                bgiframe: true,
                width: 430,
                minHeight: 150,
                modal: true,
                autoOpen: false,
                draggable: true,
                resizable: false,
                title: 'Success',
                open: function(event, ui) {
                },
                close: function(event, ui) {window.location.href = '<?php echo $GLOBALS['site']['url']."member.php";?>';  }
            }
        );

 /*jQuery("#showAddBlockDialog").dialog(
            {
                bgiframe: true,
                width: 550,
                minHeight: 150,
                modal: true,
                autoOpen: false,
                draggable: true,
                resizable: false,
                title: 'Add Blocks',
                open: function(event, ui) {
                },
                close: function(event, ui) { }
            }
        );
  jQuery("#showAddPhotoDialog").dialog(
            {
                bgiframe: true,
                width: 450,
                minHeight: 150,
                modal: true,
                autoOpen: false,
                draggable: true,
                resizable: false,
                title: 'Add Photos',
                open: function(event, ui) {
                },
                close: function(event, ui) { }
            }
        );
*/

dhxWins = new dhtmlXWindows();
dhxWins.enableAutoViewport(false);
dhxWins.attachViewportTo("vp_container");
//dhxWins.setImagePath("PDFUser/dhtmlxWindows/codebase/imgs/");

//block display selected blocks
    var userBlockSelectGrid;
    userBlockSelectGrid = new dhtmlXGridObject('Show_selcted_block_grid');
    userBlockSelectGrid.setImagePath("Matching/dhtmlxGrid/imgs/");
    userBlockSelectGrid.setHeader("#master_checkbox,Block Title");
    userBlockSelectGrid.setInitWidths("50,570");
    userBlockSelectGrid.setColAlign("center,center");
    userBlockSelectGrid.enableTooltips("false,false");
    userBlockSelectGrid.setColSorting("false,false");
    userBlockSelectGrid.setColTypes("ch,ro");
    //userBlockSelectGrid.enableColumnMove(true);
    //userBlockSelectGrid.enableMercyDrag(true);
    userBlockSelectGrid.enableDragAndDrop(true);
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
   // userBlockSelectGrid.parse(document.getElementById('show_select_blocks_xml'));
         userBlockSelectGrid.loadXML("<?php echo $pdf_block_url ?>");
        
//block display photos
    var userPhotoGrid;
    userPhotoGrid = new dhtmlXGridObject('Show_photos_grid');
    userPhotoGrid.setImagePath("Matching/dhtmlxGrid/imgs/");
    userPhotoGrid.setHeader("#master_checkbox,Images,Description");
    userPhotoGrid.setInitWidths("50,120,450");
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
                   alert('Number of characters cannot exceeds 150');
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
   // userPhotoGrid.parse(document.getElementById('showphotos_xml'));
userPhotoGrid.loadXML("<?php echo $pdf_photo_url ?>");
   
 $("#do_preview").bind('click',function() {XpreocessPreview($(this));});

 $("#do_save").bind('click',function() {XpreocessSave($(this));});

 $(".user_pdf_preview").bind('click',function() {XpreocessPreview_org($(this));});

 $("#remove_photos").bind('click',function() {XpreocessremovePhotos();});

 $("#remove_blocks").bind('click',function() {XpreocessremoveBlocks();});
 
 $("#refresh_blocks").bind('click',function() {XpreocessrefreshBlocks();});

$("#refresh_photos").bind('click',function() {XpreocessrefreshPhotots();});

$('#button_ok').click( function () {jQuery("#PDFsuccessdialog").dialog('close');;});
 
 $('#cancelblockadd').live('click',function() {block_window.close();});

 $('#savenewblock').live('click',function() {XpreocessSaveBlockSelection();});

 $('#cancelphotoadd').live('click',function() {photos_window.close();});

 $('#savenewphoto').live('click',function() {XpreocessSavePhotoSelection();});

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
                alert('Number of characters cannot exceeds 255 letters');
            }
        }
        });
function XpreocessrefreshBlocks()
{
    var blockids = userBlockSelectGrid.getAllRowIds();
    /*$.ajax({
             type     : "GET",
             url      : 'PDFUser/getAddingProfileData.php',
             cache    : false,
             data     : {section:'blocks',selblockids:blockids},
             datatype : "html",
             success: function(data){
                 //alert(data);
                 jQuery("#addblocksection").html(data);
                 //jQuery("#showAddBlockDialog").dialog('open');
                 //return flase;
                }
    });*/
     block_window = dhxWins.createWindow("window_block", 300, 150, 570, 400);
     block_window.setText("Add Blocks");
     block_window.setModal(true);
     block_window.button("park").hide();
     block_window.button("minmax1").hide();

//block_window.centerOnScreen();
    block_window.bringToTop();
    //jQuery(window).scrollTop(o);


     //block_window.center();
     URL = "PDFUser/getAddingProfileData.php?section=blocks&selblockids="+blockids;
     block_window.attachURL(URL, true);

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
            userBlockSelectGrid.addRow(mySplitResult[i],[0, userAddBlockGrid.cells(mySplitResult[i],1).getValue()]);
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
    /*$.ajax({
             type     : "POST",
             url      : 'PDFUser/getAddingProfileData.php',
             cache    : false,
             data     : {section:'photos',selblockids:photoids},
             datatype : "html",
             success: function(data){
                 //alert(data);
                 //jQuery("#addphotossection").html(data);
                 //jQuery("#showAddPhotoDialog").dialog('open');
                 //return flase;

                }
    });*/
     photos_window = dhxWins.createWindow("window_photos", 300, 550, 295, 400);
     photos_window.setText("Add Photos");
     photos_window.setModal(true);
     photos_window.button("park").hide();
     photos_window.button("minmax1").hide();
     photos_window.bringToTop();
    // photos_window.center();
     URL = "PDFUser/getAddingProfileData.php?section=photos&selblockids="+photoids;
     photos_window.attachURL(URL, true);
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
            userPhotoGrid.addRow(mySplitResult[i],[0, userAddPhotoGrid.cells(mySplitResult[i],1).getValue(), ""]);
            userPhotoGrid.setCellTextStyle(mySplitResult[i],0,"vertical-align:middle;text-align: center;");
            userPhotoGrid.setCellTextStyle(mySplitResult[i],1,"vertical-align:middle;text-align: center;height: 60px;");
        }
    }
    //jQuery("#showAddPhotoDialog").dialog('close');
    photos_window.close();
}

function XpreocessremovePhotos()
{
    var checkIds = userPhotoGrid.getCheckedRows(0);
    //alert(checkIds);
    var mySplitResult = checkIds.split(",");
    sel_col     = "";
    for(i = 0; i < mySplitResult.length; i++){
        //alert(mySplitResult[i]);
        column_sel_val      = userPhotoGrid.cells(mySplitResult[i],0).getValue();
       // if(column_sel_val == 'yes')
          if(column_sel_val == '1')
            userPhotoGrid.deleteRow(mySplitResult[i]);
    }
    $("div#Show_photos_grid").find("input[type=checkbox]").attr('checked','');
}

function XpreocessremoveBlocks()
{
    var checkIds = userBlockSelectGrid.getCheckedRows(0);
    //alert(checkIds);
    var mySplitResult = checkIds.split(",");
    sel_col     = "";
    for(i = 0; i < mySplitResult.length; i++){
        //alert(mySplitResult[i]);
        column_sel_val      = userBlockSelectGrid.cells(mySplitResult[i],0).getValue();
        //if(column_sel_val == 'yes')
          if(column_sel_val == '1')
            userBlockSelectGrid.deleteRow(mySplitResult[i]);
    }
    $("div#Show_selcted_block_grid").find("input[type=checkbox]").attr('checked','');
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
               return false;
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
                             window.open(data.filename);
                             return flase;

                            }
                     });
                }
                else
                {
                    alert("Please enter picture title");
                }
            }
            else
            {
                alert("Please enter cover title");
            }
            
        }
        else
        {
                alert('Please select at least one Photo');
            }
        }
        else
        {
            alert('Please select at least one Block');
        }

    }
    else
    {
        alert('Please select at least one template');
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
                             jQuery("#PDFsuccessdialog").dialog('open');
                             return flase;

                            }

                     });
                }
                else
                {
                    alert("Please enter picture title");

                }
            }
            else
            {
                alert("Please enter Cover title");
            }
        }
        else
        {
                alert('Please select at least one Photo');
            }
        }
        else
        {
            alert('Please select at least one Block');
        }

    }
    else
    {
        alert('Please select at least one template');
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
                    alert("Preview file not exists");
                },
            success:
                function(){
                    window.open(temp_org_path);
                }
        });
       
    }
    else
    {
        alert('Please select at least one template');
        return false;
    }
}
});
</script>
