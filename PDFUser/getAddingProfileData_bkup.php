<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php
define('BX_PROFILE_PAGE', 1);
require_once( '../inc/header.inc.php' );
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once(BX_DIRECTORY_PATH_CLASSES . 'BxDolFilesDb.php');

bx_import('BxTemplProfileView');
bx_import('BxDolInstallerUtils');
bx_import('BxDolFilesModule');

require_once ('phpfunctions.php');
//require_once ('generatePDF.php');

$add_section     = $_GET['section'];
$sel_ods         = $_GET['selblockids'];
$parent_ID       =   getLoggedId();
switch($add_section)
{
    case "blocks":
        addblock($sel_ods,$parent_ID);
        break;
    case "photos":
        addphotos($sel_ods,$parent_ID);
        break;
}

//for abilty to add blocks by user showing all the blocks in grid
function addblock($sel_ods,$parentID)
{
    $BlockArray             = getAllBlockData($parentID);
    //print_r($BlockArray);
?>
<div style="font-family: Tahoma; font-size: 10px; height: 100%; overflow: auto;">
    <table class="block_table_grid">
        <tr>
         <td style="padding-left: 0px;padding-right: 2px;">
            <div style="background-color:#E3EFFF;position:relative;top:5px;width:512px;;height:auto;overflow:hidden;margin-left:4px;" id="Show_block_grid"></div>
         </td>
        </tr>
    </table>
    <div style="position:relative;width:auto;height:40px;margin-top: 10px;">
<!--        <input type="Button" id="savenewblock" name="savenewblock" value="Add" style="position:absolute;left:455px;top:10px;cursor:pointer;">
        <input type="Button" id="cancelblockadd" name="cancelblockadd" value="Cancel" style="position:relative;left:382px;top:10px;cursor:pointer;">-->
        <span class="form_input_submit pink-btn" id="cancelblockadd" style="position:relative;top:5px;left:405px;font-size:bold">CANCEL</span>
        <span class="form_input_submit pink-btn" id="savenewblock" style="position:relative;top:5px;left:410px;font-size:bold">ADD</span>
    </div>
    </div>
<?php
    $selectd_blocks  = split(',',$sel_ods);
    $blck_str_select = '<xml version="1.0" encoding="UTF-8" id="show_blocks_xml" style="display:none"><rows height="30" valign="middle">';
    $block_Flag      = 0;
    foreach($BlockArray as $blck)
    {
        if(!(in_array($blck['blockname'], $selectd_blocks)))
        {
            $block_Flag       = 1;
            $blck_str_select .= '<row id="'. $blck['blockname'] .'">
                    <cell style ="vertical-align:middle;text-align: center;"></cell>
                    <cell style ="vertical-align:middle;text-align: left;">'.$blck['blockheading'].'</cell>
                </row>';
        }
    }
    if(!$block_Flag)
    {
        $blck_str_select .= '<row id="No">
                    <cell style ="vertical-align:middle;text-align: center;"></cell>
                    <cell style ="vertical-align:middle;text-align: left;" rowspan ="2">No Block Exists</cell>
                </row>';
    }
    $blck_str_select .= '</rows></xml>';

    $fname = createXMLFile_add_block($blck_str_select);
    $pdf_add_block_url = $GLOBALS['site']['url']."PDFUser/add_pdf_xml/".$fname;

    echo $blck_str_select;
?>
    <script type="text/javascript">
        var blockSelectFlag  = '<?=$block_Flag?>';
        //block display selected blocks
        var userAddBlockGrid;
        userAddBlockGrid = new dhtmlXGridObject('Show_block_grid');
        userAddBlockGrid.setImagePath("Matching/dhtmlxGrid/imgs/");
        userAddBlockGrid.setHeader("#master_checkbox,Block Title");
        userAddBlockGrid.setInitWidths("50,*");
        userAddBlockGrid.setColAlign("center,center");
        userAddBlockGrid.enableTooltips("false,false");
        userAddBlockGrid.setColSorting("false,false");
        userAddBlockGrid.setColTypes("ch,ro");
        userAddBlockGrid.enableDragAndDrop(true);
        userAddBlockGrid.setSkin("dhx_skyblue");
        userAddBlockGrid.enableAutoHeight(true);
        userAddBlockGrid.init();
      //  userAddBlockGrid.parse(document.getElementById('show_blocks_xml'));
      userAddBlockGrid.loadXML("<?php echo $pdf_add_block_url ?>");
      userAddBlockGrid.attachEvent("onXLE", function(grid_obj,count){
          if(blockSelectFlag == 0)
              {
                  userAddBlockGrid.deleteColumn(0);
                  //userAddBlockGrid.addRow('No',"No Block Exists");
                  //$('#savenewblock').css("display","none");
              }

      });
    </script>
<?php
}

function addphotos($sel_ods,$parentID)
{
    $getPhotos = getPhotoAlbums($parentID);
?>
<div style="font-family: Tahoma; font-size: 10px; height: 100%; overflow: auto;">
    <table class="photos_table_grid">
        <tr>
        <td style="padding-left: 0px;padding-right: 2px;">
             <div style="position:relative;top:5px;width:245px;;height:auto;overflow:hidden;margin-left:4px;" id="Show_add_photos_grid"></div>
         </td>
        </tr>
    </table>
    <div style="position:relative;width:auto;height:40px;margin-top:10px;">
<!--        <input type="Button" id="savenewphoto" name="savenewphoto" value="Add" style="position:absolute;left:185px;top:10px;cursor:pointer;">
        <input type="Button" id="cancelphotoadd" name="cancelphotoadd" value="Cancel" style="position:relative;left:110px;top:10px;cursor:pointer;">-->
        <span class="form_input_submit pink-btn" id="cancelphotoadd" style="position:relative;top:5px;left:136px;font-size:bold">CANCEL</span>
        <span class="form_input_submit pink-btn" id="savenewphoto" style="position:relative;top:5px;left:141px;font-size:bold">ADD</span>
    </div>
    </div>
<?php
    $selectd_photos  = split(',',$sel_ods);
    $photo_str_select = '<xml version="1.0" encoding="UTF-8" id="show_photos_xml" style="display:none"><rows height="30" valign="middle">';
    $photo_Flag      = 0;
    foreach($getPhotos as $photFetch)
    {
        if(!(in_array($photFetch['Hash'], $selectd_photos)))
        {
            $filename = $GLOBALS['dir']['root'].'modules/boonex/photos/data/files/'.$photFetch['ID'].'.'.$photFetch['Ext'];
            $file_medium_name = $GLOBALS['dir']['root'].'modules/boonex/photos/data/files/'.$photFetch['ID'].'_m.'.$photFetch['Ext'];
            if (file_exists($filename) && file_exists($file_medium_name)) {
                $photo_Flag = 1;
                $img_path = $GLOBALS['site']['url']."m/photos/get_image/file/".$photFetch['Hash'].".".$photFetch['Ext'];
                $photo_str_select .= '<row id="'. $photFetch['Hash'] .'">
                            <cell style ="vertical-align:middle;text-align: center;"></cell>
                            <cell style ="vertical-align:middle;text-align: left;">
                            &lt;img src="'.$img_path.'" height="50" width="50"/&gt;</cell>
                        </row>';
            }
        }
    }
    if(!$photo_Flag)
    {
        $photo_str_select .= '<row id="No">
                    <cell style ="vertical-align:middle;text-align: center;"></cell>
                    <cell style ="vertical-align:middle;text-align: left;" rowspan ="2">No Photo Exists</cell>
                </row>';
    }


    $photo_str_select .= '</rows></xml>';
    $fname = createXMLFile_add_photo($photo_str_select);
    $pdf_add_photo_url = $GLOBALS['site']['url']."PDFUser/add_pdf_xml/".$fname;
    echo $photo_str_select;
?>
<script type="text/javascript">
//block display photos
    var photoSelectFlag  = '<?=$photo_Flag?>';
    var userAddPhotoGrid;
    userAddPhotoGrid = new dhtmlXGridObject('Show_add_photos_grid');
    userAddPhotoGrid.setImagePath("Matching/dhtmlxGrid/imgs/");
    userAddPhotoGrid.setHeader("#master_checkbox,Images");
    userAddPhotoGrid.setInitWidths("50,*");
    userAddPhotoGrid.setColAlign("center,center");
    userAddPhotoGrid.enableTooltips("false,false");
    userAddPhotoGrid.setColSorting("na,str");
    userAddPhotoGrid.setColTypes("ch,ro");
    userAddPhotoGrid.enableDragAndDrop(true);
    userAddPhotoGrid.setSkin("dhx_skyblue");
    userAddPhotoGrid.enableAutoHeight(true);
    userAddPhotoGrid.init();
  //  userAddPhotoGrid.parse(document.getElementById('show_photos_xml'));
    userAddPhotoGrid.loadXML("<?php echo $pdf_add_photo_url ?>");
    userAddPhotoGrid.attachEvent("onXLE", function(grid_obj,count){
          if(photoSelectFlag == 0)
              {
                  userAddPhotoGrid.deleteColumn(0);
                  //userAddBlockGrid.addRow('No',"No Block Exists");
                  //$('#savenewblock').css("display","none");
              }

      });
</script>
<?php
}
?>