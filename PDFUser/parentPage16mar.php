<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php
$control_number         = mt_rand(100, 1000000);
error_reporting(1);
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

$genObject              = new generatePDF();
    //$fileName         = $genObject->generatePDF();
$tempuserDetails        = getusertempDetails($tempusrid);

//print_r($tempuserDetails);

?>
<!--<link type="text/css" href="Matching/css/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript" src="Matching/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="Matching/js/jquery-ui-1.8.2.custom.min.js"></script>-->
<script type="text/javascript" src="Matching/js/mootools-1.2.4-core-nc.js"></script>
<script type="text/javascript" src="Matching/js/mootools-1.2.4.4-more.js"></script>
<link rel="STYLESHEET" type="text/css" href="PDFUser/css/style.css">

<!--<link rel="STYLESHEET" type="text/css" href="Matching/dhtmlxGrid/dhtmlxgrid.css"/>
<link rel="STYLESHEET" type="text/css" href="Matching/dhtmlxGrid/skins/dhtmlxgrid_dhx_skyblue.css"/>
<link rel="STYLESHEET" type="text/css" href="Matching/dhtmlxToolbar/codebase/skins/dhtmlxtoolbar_dhx_skyblue.css">
<link type="text/css" href="Matching/css/jquery-ui-1.8.2.custom.css" rel="stylesheet" />-->

<link rel="STYLESHEET" type="text/css" href="PDFUser/Processing/dialog.css">
<script type="text/javascript" src= "PDFUser/Processing/dialog.js"></script>

<!--<script  src="Matching/dhtmlxGrid/dhtmlxcommon.js"></script>
<script  src="Matching/dhtmlxGrid/dhtmlxgrid.js"></script>
<script  src="Matching/dhtmlxGrid/ext/dhtmlxgrid_pgn.js"></script>
<script  src="Matching/dhtmlxGrid/dhtmlxgridcell.js"></script>
<script  src="Matching/dhtmlxToolbar/codebase/dhtmlxtoolbar.js"></script>
<script  src="Matching/dhtmlxGrid/ext/dhtmlxgrid_filter.js"></script>-->

<script type="text/javascript" src="lightbox/thickbox-compressed.js"></script>
<script type="text/javascript" src="documentViewer/controller/FlexPaperComponent.js"></script>
<link rel="stylesheet" href="lightbox/thickbox.css" type="text/css" media="screen"/>

<script type="text/javascript"> 

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

<div style="position:relative;height:auto;width:1010px;border:0px solid red;">
    <form name="pdf_list_form" action="" method="post" enctype="multipart/form-data" id="pdf_list" class="form_advanced">
    <input type="hidden" id="sel_pdf_ids" name="sel_pdf_ids" value=""/>
    <div class="chk_dummy" style="height:5px;"><!--dummy div--></div>
    <div style="position:relative;width:auto;border:0px solid red;height:55px;">
       
     
        
        <nav class="nav" >
            <ul>
            <li style='margin-left: 0px;' class="last"><a href="javascript:void(0);" id="del_PDF">Delete</a></li>
            <li style='margin-left: 508px;' class="last"><a href="javascript:void(0);" id="createpdf">Upload PDF Profile</a></li>
            <li style='margin-left: 10px;' class="last"><a href="<?php echo $GLOBALS['site']['url'].'page/pdfcreate';?>">Create PDF Profile</a></li>
            </ul>
        </nav>
    </div>
    
    <div style="position:relative;width:auto;border:0px solid red;m">
        <table width="1007"border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <div id="userPDF_container" style="width:auto; height:400px; background-color:white;overflow:hidden;"></div>
                </td>
            </tr>
            <tr>
                <td id="recinfoArea"></td>
            </tr>
        </table>
    </div>
    <div class="chk_dummy" style="height:10px;"><!--dummy div--></div>
    </form>
</div>
<script type="text/javascript" src="pdfuploadComponent/js/model.js"></script>
<script type="text/javascript" src="pdfuploadComponent/js/controller.js"></script>
 

            
 
 

<script type="text/javascript">       
        control_number      = '<?=$control_number;?>';
        var userPDFGrid;
        userPDFGrid = new dhtmlXGridObject('userPDF_container');
        userPDFGrid.setImagePath("Matching/dhtmlxGrid/imgs/");
        userPDFGrid.enableTooltips("false,true,true,true,true,false");
        userPDFGrid.setColSorting("na,str,str,na,na,na");
        userPDFGrid.setColAlign( 'center,center,center,center,center,center' );
        userPDFGrid.setColVAlign( 'middle,middle,middle,middle,middle,middle' );
        userPDFGrid.attachHeader("&nbsp;,#text_filter,#text_filter,&nbsp;,&nbsp;,&nbsp;,&nbsp");
        userPDFGrid.setSkin("dhx_skyblue");
        userPDFGrid.enableAutoHeight(true);
        userPDFGrid.enableMultiline(true);
        userPDFGrid.init();
        userPDFGrid.enablePaging(true, 10, 3, "recinfoArea");
        userPDFGrid.setPagingSkin("toolbar","dhx_skyblue");
        userPDFGrid.clearAndLoad('PDFUser/getUserPDF.php?apUser=<?php echo $parent_ID; ?>');
        userPDFGrid.attachEvent("onXLE", function(){
            $('.user_pdf_edit').click( function () {xProcessEdit($(this));});
            $('.user_pdf_view').click( function () {xProcessView($(this));});
            $('#del_PDF').click( function () {xProcessDelete();});
            $('.user_pdf_regen').click( function () {xProcessRegenerate($(this));});
            $('.user_pdf_mkdflt').click( function () {xProcessMakeDefault($(this));});
            $('#createpdf').click(function() { 
            newpdfWin();  
            });
  
            tooltips();
        });
 
 
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
                popup           : false,
                onCallBack      : onActionPerformedrefresh
                });
                uploadController.initComponent();
            }
        
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
function tooltips(){

        $$('img.tipz').each(function(element,index) {

                //element.store('tip:title', 'Adoption Portal');
              //  alert(element.id);
                element.store('tip:text',element.alt );
        });

        //create the tooltips
        var tipz = new Tips('.tipz',{
                className: 'tipz',
                fixed: true,
                hideDelay: 50,
                showDelay: 50,
                offset: {'x': 10, 'y': 25}


            });
        }

function xProcessEdit(item)
{
    window.location.href = item.attr('url');
}
//reset the vp div height in which Window will be attached.
function resetFlexpapaerDivHeight()
{
    var body = document.body,
    html = document.documentElement;
    var height = Math.max( body.scrollHeight, body.offsetHeight, 
                       html.clientHeight, html.scrollHeight, html.offsetHeight );
    var offsetHeight = document.getElementById('vp').offsetHeight;    
    if(offsetHeight <= height)
    {        
        document.getElementById('vp').style.height = height+"px";
    }    
}
function xProcessView(item)
{  
    resetFlexpapaerDivHeight();
  // window.open (item.attr('url'), "PDF","location=1,status=1,scrollbars=1, width=1100,height=900");
    var main_URL           = $(location).attr('protocol')+"//"+$(location).attr('host')+"/"; 
    var application_url    = main_URL+'documentViewer/';
    //alert(application_url);
    //alert('http://ctpf01.parentfinder.com/documentViewer/');
    var flexvalues = {
           icons_path: 'documentViewer/icons/32px/' // mandatory
          ,application_url: application_url // mandatory
          ,application_path: '/var/www/html/pf/PDFTemplates/user/' // mandatory
         // ,pdf_name: "2_1_136.pdf" // mandatory
          ,pdf_name: item.attr('url') // mandatory 
          ,split_mode: false // not mandatory
          ,magnification: 1.3  // not mandatory, default 1.1
             }
         FlexPaperComponent.callFlexPaper(flexvalues);   
   
}

function xProcessDelete()
{
    var checkIds = userPDFGrid.getCheckedRows(0);
    if(checkIds)
    {
    dhtmlx.message({
                type: "confirm",
                text: 'Are you sure you want to delete this PDF?"',
                callback: function(result) {
                    if(result == true){
                    
                    var mySplitResult = checkIds.split(",");
                    sel_col     = "";
                    for(i = 0; i < mySplitResult.length; i++){
                        //alert(mySplitResult[i]);
                        column_sel_val      = userPDFGrid.cells(mySplitResult[i],0).getValue();
                        if(column_sel_val == '1')
                            sel_col  += mySplitResult[i]+",";
                    }
                    //alert(sel_col);
                
                    $.ajax({
                                url: 'PDFUser/delete_pdf.php',
                                type: "POST",
                                cache: false,
                                data: {sel_pdf_ID : sel_col},
                                success: function(data) {
                                                //alert(data);
                                                userPDFGrid.clearAndLoad('PDFUser/getUserPDF.php?apUser=<?php echo $parent_ID; ?>');
                                                }
               });
           }
    }
    });
    }
    else
    {
        dhtmlx.message( {type : "error", text : "Please Select atleast one PDF to delete" } );
    }
    
}

 function onActionPerformedrefresh() 
 {
  userPDFGrid.clearAndLoad('PDFUser/getUserPDF.php?apUser=<?php echo $parent_ID; ?>');
 }
 
function xProcessRegenerate(item)
{
    showProcessing();
     $.ajax({
                 url: 'PDFUser/regenearate_pdf.php',
                 type: "POST",
                 cache: false,
                 data: {sel_tmpuser_ID : item.attr('id')},
                 datatype : "json",
                 success: function(data){
                   //  alert(data.filename);
                     HideBar();
                  //   window.open(data.filename);
            resetFlexpapaerDivHeight();
            var main_URL           = $(location).attr('protocol')+"//"+$(location).attr('host')+"/"; 
            var application_url    = main_URL+'documentViewer/';      
            var flexvalues = {
           icons_path: 'documentViewer/icons/32px/' // mandatory
          ,application_url: application_url // mandatory
          ,application_path: '/var/www/html/pf/PDFTemplates/user/' // mandatory
         // ,pdf_name: "2_1_136.pdf" // mandatory
          ,pdf_name: data.pdfFilename // mandatory 
          ,split_mode: false // not mandatory
          ,magnification: 1.3  // not mandatory, default 1.1
             }
         FlexPaperComponent.callFlexPaper(flexvalues);    
              
                  
                  
                  
                     return false;

                }
     });

}

function xProcessMakeDefault(item)
{
    
    dhtmlx.message({
    //title: "Default PDF",
    type:"confirm",
    text: "Are you sure you want to make this PDF as Default?",
    callback: function(result) {
        if(result == true){
        $.ajax({
                    url: 'PDFUser/makedefault.php',
                    type: "POST",
                    cache: false,
                    data: {sel_tmpuser_ID : item.attr('id'),sel_user_id:'<?php echo $parent_ID;?>'},
                    datatype : "json",
                    success: function(data){
                        //alert('Successfully changed the default PDF');
                        dhtmlx.message( {text : "Successfully changed the default PDF" } );
                        //userPDFGrid.clearAndLoad('PDFUser/getUserPDF.php?apUser=<?php echo $parent_ID; ?>');
                        window.location.href = '<?php echo $GLOBALS['site']['url']."page/userprofile";?>';
                        return false;

                    }
        });
        }
        else{
            return false;
	}
    }
});  
    
}
</script>


