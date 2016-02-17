<?php
require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolInstallerUtils.php' );

bx_import('BxTemplProfileView');

$_page['name_index']	= 500;

//$profileID = getID( $_GET['ID'] );

$profileID = getID( $_GET['profile'] );

$oProfile = new BxBaseProfileGenerator( $profileID );
$p_arr  = $oProfile -> _aProfile;


if($p_arr['Couple'])
{       
    $_NameStr2=getProfileInfo($p_arr['Couple']);
    $_NameStr=$p_arr['FirstName'].' & '.$_NameStr2['FirstName'];
}else{
    $_NameStr=$p_arr['FirstName'];
}


$_page['header']      = htmlspecialchars_adv( $p_arr['State'] ).' Hopeful Adoptive Parents - '.process_line_output( $_NameStr ) . ', ParentFinder Community';
$_ni = $_page['name_index'];
$oPPV = new BxTemplProfileView($oProfile, $site, $dir);


function get_agency($agency_id)
{
@$result = $GLOBALS['MySQL']->res("SELECT bx_groups_main.title, bx_photos_main.Ext, bx_photos_main.Hash FROM `bx_groups_main`, `bx_photos_main` WHERE bx_groups_main.ID='".$agency_id."' AND bx_groups_main.status='approved' AND bx_groups_main.author_id=bx_photos_main.Owner AND bx_photos_main.Desc='Avatar' LIMIT 1");	
if(!$result) exit (mysql_error());
 
 @$Title=mysql_result($result,0,'title');
 @$Avatar=mysql_result($result,0,'Hash');
 @$Ext=mysql_result($result,0,'Ext');
$logo_agency='<img src="http://'.$_SERVER['SERVER_NAME'].'/m/photos/get_image/file/'.$Avatar.'.'.$Ext.'"  style="height:85px;" alt="'.$Title.'" title="'.$Title.'">';
$AgencyHeader='<div style="float: left;">'.$logo_agency.'</div><div style="float: left; padding: 15px 0 0 20px;"><h1>'.$Title.'</h1></div><div style="float: right; padding-top: 5px; background: #FFF;><a href="#" id="closewin"><strong>Close</strong></a></div>';

return $AgencyHeader;
 
}




function get_pdf($profileID)
{
@$result = $GLOBALS['MySQL']->res("SELECT `template_user_id` FROM `pdf_template_user` WHERE `user_id`='".$profileID."' LIMIT 1");	
if(!$result) exit (mysql_error());
 
 @$template_user_id=mysql_result($result,0,'template_user_id');

return $template_user_id;
 
}




 
 $AgencyHeader=get_agency($p_arr[AdoptionAgency]);
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=8" >
	<title><?=$_page['header']?></title>
	<base href="http://www.parentfinder.com/" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
	<link href="http://www.parentfinder.com/templates/tmpl_ParentFinder/css/common.css" rel="stylesheet" type="text/css" />
	<link href="http://www.parentfinder.com/templates/tmpl_ParentFinder/css/general.css" rel="stylesheet" type="text/css" />
	<link href="http://www.parentfinder.com/templates/tmpl_ParentFinder/css/anchor.css" rel="stylesheet" type="text/css" />
	<link href="http://www.parentfinder.com/templates/tmpl_ParentFinder/css/forms_adv.css" rel="stylesheet" type="text/css" />
	<link href="http://www.parentfinder.com/templates/tmpl_ParentFinder/css/top_menu.css" rel="stylesheet" type="text/css" />
	<link href="http://www.parentfinder.com/modules/boonex/photos/templates/base/css/search.css" rel="stylesheet" type="text/css" />
	<script language="javascript" type="text/javascript" src="http://www.parentfinder.com/plugins/jquery/jquery.js"></script>
	
	
	
	
	
	<script language="javascript" type="text/javascript" src="http://www.parentfinder.com/flash/modules/global/js/swfobject.js"></script>
	
	
	
	
</head>
<script type="text/javascript">

$(function(){
    $('.bottomLinks').css("display", "none");
	$('.bottomCpr').css("display", "none");
	
	
	$('#closewin').mouseover(function(){
       $(this).css({"color": "red", "cursor": "pointer"});
});
	
		$('#closewin').mouseout(function(){
       $(this).css("color", "black");
});
	
	
	$('#closewin').click(function(){
    window.close();
});
	
	
	
 });
</script>


<body>
    

    <!-- start wrapper -->
<div id="wrapper">
<div id="gold_gradient" style="background:#FFF;">
    
    
    
    <div class="sys_main_logo" >
		<div class="sys_ml" style="width:988px;">
            <div class="sys_ml_wrapper">
			
<? print  $AgencyHeader;?>				

            </div>
		</div>
	</div>
	<br clear="all">	
	<div class="sys_top_menu">
	
	

        <table class="topMenu" cellpadding="0" cellspacing="0" style="width:988px"><tr>
		
		
			<td class="top">
	<a href="http://www.parentfinder.com/iframe.php?profile=<?=$_GET['profile']?>"   class="top_link"><span class="down">Profile</span>
	<!--[if gte IE 7]><!--></a><!--<![endif]-->
	<div style="position:relative;display:block;"></div>
</td>
        
		
		
		
		<td class="top">
	<a href="http://www.parentfinder.com/iframe.php?blogs=yes&profile=<?=$_GET['profile']?>"   class="top_link"><span class="down">Journal</span>
	<!--[if gte IE 7]><!--></a><!--<![endif]-->
	<div style="position:relative;display:block;"></div>
</td>


<td class="top">
	<a href="http://www.parentfinder.com/iframe.php?photos=yes&profile=<?=$_GET['profile']?>"   class="top_link"><span class="down">Photos</span>
	<!--[if gte IE 7]><!--></a><!--<![endif]-->
	<div style="position:relative;display:block;"></div>
</td>
<td class="top">	
    <a href="http://www.parentfinder.com/iframe.php?videos=yes&profile=<?=$_GET['profile']?>"  class="top_link"><span class="down">Videos</span></a>
	<div style="position:relative;display:block;"></div>
</td>
<td class="top">	
<a href="http://www.parentfinder.com/iframe.php?contact=yes&profile=<?=$_GET['profile']?>" class="top_link"><span class="down">Contact Us</span></a>
	<div style="position:relative;display:block;"></div>
</td>
<td class="top">
	<a href="http://www.parentfinder.com/iframe.php?store=yes&profile=<?=$_GET['profile']?>"   class="top_link"><span class="down" >Store</span>
	<!--[if gte IE 7]><!--></a><!--<![endif]-->
	<div style="position:relative;display:block;"></div>
</td>


<td width="20" style="background: #FFF;">




                <link rel="STYLESHEET" type="text/css" href="PDFUser/Processing/dialog.css">
                <script type="text/javascript" src= "PDFUser/Processing/dialog.js"></script>
                <a href="javascript:void(0);" class="printdefaultpdf"  id="<?=get_pdf($profileID);?>" style="height:31px;">	<button class="form_input_submit" Style="margin-top:1px;" type="button" >
                <img src="modules/aqb/pcomposer/templates/base/images/icons/print.jpeg" alt="Save to PDF">Print&nbsp;Profile
            </button></a>
            <script type="text/javascript">
                function showProcessing()
                {
                    REDIPS.dialog.init();
                    REDIPS.dialog.op_high = 60;
                    REDIPS.dialog.fade_speed = 18;
                    REDIPS.dialog.show(183, 17, '<img width="183" height="17" title="" alt="" src="data:image/gif;base64,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" />');
                }
                function HideBar()
                 {
                        REDIPS.dialog.hide("undefined");

                 }
                $(".printdefaultpdf").click( function () {xProcessPrintPDF12($(this));});
                function xProcessPrintPDF12(item)
                {
                    if(item.attr("id"))
                    {

                         showProcessing();
                         $.ajax({
                                     url: "PDFUser/regenearate_pdf.php",
                                     type: "POST",
                                     cache: false,
                                     data: {sel_tmpuser_ID : item.attr("id")},
                                     datatype : "json",
                                     success: function(data){
                                         //alert(data.filename);
                                         HideBar();
                                         window.open(data.filename);
                                         return false;

                                    }
                         });
                     }
                     else
                        alert("There is no Deault PDF set");
                }
            </script>
          
	</div>






<!--	<a href="http://www.parentfinder.com/print.php?profile=<?=$_GET['profile']?>"   class="top_link" style="padding-top: 5px;"><img src="http://parentfinder.com/templates/base/images/print_icon.gif" width="31"></a>-->
	<div style="position:relative;display:block;"></div>
</td>

	

	<!--[if lte IE 6]></td></tr></table></a><![endif]-->
</tr></table>
				
				
        		
		<div class="clear_both">&nbsp;</div>
	</div>
	
	<!-- end of top -->
	
	
	<div class="main" style="border:0px; width:988px;">


<?
 
 
 
if (BxDolInstallerUtils::isModuleInstalled("profile_customize"))
{
    $_page_cont[$_ni]['custom_block'] = '<div id="profile_customize_page" style="display: none;">' .
        BxDolService::call('profile_customize', 'get_customize_block', array()) . '</div>';
    $_page_cont[$_ni]['page_main_css'] = '<style type="text/css">' . 
        BxDolService::call('profile_customize', 'get_profile_style', array($profileID)) . '</style>';
}
else
{
    $_page_cont[$_ni]['custom_block'] = '';
    $_page_cont[$_ni]['page_main_css'] = '';
}


 
if ((isset($_GET['photos'])) AND (empty($_GET['album']))) {

include('simple_html_dom.php');
$html = file_get_html("http://www.parentfinder.com/m/photos/albums/browse/owner/".$_GET['profile']);
foreach($html->find('div.disignBoxFirst') as $e)
//$s = '<div class="sys_file_search_from">' . $e->outertext . '</div>';
//$s = '';
$str=$e->outertext;

$str2=str_replace("m/photos/browse/album/","iframe.php?photos=yes&album=",$str);
$str3= str_replace("/owner/","&profile=",$str2);
echo str_replace('<div class="sys_file_search_from">','<div style="display: none;">',$str3);





?>
<!-- end of body -->
	</div>
    <!-- End of Gold Gradient -->
    </div>
        <!-- close wrapper -->
    </div>
    <div id="wrapper_foot"></div>	   
    </body>
</html>  
<?



} elseif (isset($_GET['album'])) {

include('simple_html_dom.php');
if (isset($_GET['photos'])) {
$module="photos";
} elseif (isset($_GET['videos'])) {
$module="videos";
}

$html = file_get_html("http://www.parentfinder.com/m/".$module."/browse/album/".$_GET['album']."/owner/".$_GET['profile']);
foreach($html->find('div.disignBoxFirst') as $e)
$str=$e->outertext;
$str2=str_replace("m/".$module."/view/","iframe.php?".$module."=yes&profile=".$_GET['profile']."&view=",$str);


$str3=str_replace('<div class="sys_file_search_from">','<div style="display: none;">',$str2);


echo $str3;
//echo $e->outertext;

?>
<!-- end of body -->
	</div>
    <!-- End of Gold Gradient -->
    </div>
        <!-- close wrapper -->
    </div>
    <div id="wrapper_foot"></div>	   
    </body>
</html>  
<?


} elseif (isset($_GET['view'])) {

if (isset($_GET['photos'])) {
$module="photos";
$div="div.disignBoxFirst";
} elseif (isset($_GET['videos'])) {
$module="videos";
$div="div.viewFile";
}

include('simple_html_dom.php');
$html = file_get_html("http://www.parentfinder.com/m/".$module."/view/".$_GET['view']);

foreach($html->find($div) as $e)
$str=$e->outertext;
$str2=str_replace("m/".$module."/view/","iframe.php?".$module."=yes&view=",$str);

echo $str2;

//echo str_replace("/owner/","&profile=",$str2);

?>
<!-- end of body -->
	</div>
    <!-- End of Gold Gradient -->
    </div>
        <!-- close wrapper -->
    </div>
    <div id="wrapper_foot"></div>	   
    </body>
</html>  
<?


} elseif ((isset($_GET['videos'])) AND (empty($_GET['album']))) {


include('simple_html_dom.php');
$html = file_get_html("http://www.parentfinder.com/m/videos/albums/browse/owner/".$_GET['profile']);
foreach($html->find('div.disignBoxFirst') as $e)

$str=$e->outertext;
$str2=str_replace("m/videos/browse/album/","iframe.php?videos=yes&album=",$str);
$str3= str_replace("/owner/","&profile=",$str2);
echo str_replace('<div class="sys_file_search_from">','<div style="display: none;">',$str3);

?>
<!-- end of body -->
	</div>
    <!-- End of Gold Gradient -->
    </div>
        <!-- close wrapper -->
    </div>
    <div id="wrapper_foot"></div>	   
    </body>
</html>  
<?

} elseif (isset($_GET['blogs'])) {


include('simple_html_dom.php');

$html = file_get_html("http://www.parentfinder.com/modules/boonex/blogs/blogs.php?action=show_member_blog&ownerID=".$profileID);
foreach($html->find('div.disignBoxFirst') as $e)
echo $e->outertext;

?>
<!-- end of body -->
	</div>
    <!-- End of Gold Gradient -->
    </div>
        <!-- close wrapper -->
    </div>
    <div id="wrapper_foot"></div>	   
    </body>
</html>  
<?
} elseif (isset($_GET['contact'])) {

include('simple_html_dom.php');
$html = file_get_html("http://www.parentfinder.com/ContactAgency.php?ID=".$profileID);
foreach($html->find('div.disignBoxFirst', 0) as $e)
echo $e->outertext;

?>
<!-- end of body -->
	</div>
    <!-- End of Gold Gradient -->
    </div>
        <!-- close wrapper -->
    </div>
    <div id="wrapper_foot"></div>	   
    </body>
</html>  
<?

} elseif (isset($_GET['store'])) {


include('simple_html_dom.php');
$html = file_get_html("http://www.parentfinder.com/m/store/browse/user/".$_GET['profile']);
foreach($html->find('div.disignBoxFirst') as $e)
echo $e->outertext;

?>
<!-- end of body -->
	</div>
    <!-- End of Gold Gradient -->
    </div>
        <!-- close wrapper -->
    </div>
    <div id="wrapper_foot"></div>	   
    </body>
</html>  
<?

} else {

$str=$oPPV->getCode();
$str2=str_replace("m/photos/browse/album/","iframe.php?photos=yes&album=",$str);
$str3=str_replace("m/videos/browse/album/","iframe.php?videos=yes&album=",$str2);
$str4=str_replace("/owner/","&profile=",$str3);
$_page_cont[$_ni]['page_main_code'] = str_replace('<div class="sys_file_search_from">','<div style="display: none;">',$str4);
PageCode();


}





?>



  


