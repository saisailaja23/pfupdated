<?php

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -----------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2006 BoonEx Group
*     website              : http://www.boonex.com/
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software. This work is licensed under a Creative Commons Attribution 3.0 License.
* http://creativecommons.org/licenses/by/3.0/
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the Creative Commons Attribution 3.0 License for more details.
* You should have received a copy of the Creative Commons Attribution 3.0 License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );

require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_PLUGINS . 'Services_JSON.php' );

// --------------- page variables and login

$_page['name_index']	= 11;
$_page['css_name']		= 'about_us.css';

check_logged();

$_page['header'] ="Block Edit Page";
$_page['header_text'] = "Block Edit Panel";




// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompMainCode();

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompMainCode() {
	global $oTemplConfig;
	
       $_memID=$_COOKIE['memberID'];
        if($_POST['Cancel']){
        if($_GET['name'])
        {

            $_fieldname=base64_decode($_GET['name']);

           $_NickName=mysql_fetch_assoc(mysql_query("SELECT NickName FROM Profiles WHERE ID={$_memID}"));
            header("Location:".$site['url'].$_NickName['NickName']);
        }else{
        header('Location:member.php');
        }
}

$member_username=mysql_fetch_assoc(mysql_query("SELECT NickName FROM Profiles WHERE ID={$_memID}"));


$_fieldname=base64_decode($_GET['name']);
if($_POST['draft']){
    db_res('UPDATE Profiles_draft SET '.$_fieldname.'="'.process_db_input($_POST['Block_text']).'" WHERE ID ='.$_memID);
    cleanALL();
    echo '<script language="javascript">alert("Draft saved successfully!");</script>';
}
if($_POST['Submit']){
 db_res('UPDATE Profiles_draft SET '.$_fieldname.'="'.process_db_input($_POST['Block_text']).'" WHERE ID ='.$_memID);
 db_res('UPDATE Profiles SET '.$_fieldname.'="'.process_db_input($_POST['Block_text']).'" WHERE ID ='.$_memID);
 cleanALL();
 echo '<script language="javascript">alert("Block saved and submitted successfully!");</script>';
}
$_fieldInfo=mysql_fetch_assoc(mysql_query("SELECT {$_fieldname} FROM Profiles_draft WHERE ID={$_memID}"));
if($_fieldname=='DearBirthParent')
{
	$_caption= "Birth Parent Letter";
}else if($_fieldname=='Hobbies'){
	$_caption= "Hobbies";

}else if($_fieldname=='Interests'){
	$_caption= "Interests";

}else if($_fieldname=='About_our_home'){
	$_caption= "About Our Home";

}else if($_fieldname=='DescriptionMe'){
	$_caption= "Description";

}


$sForm.= <<<EOH
<!-- tinyMCE gz -->
<!--//[START DeeEmm TinyBrowser MOD]-->
<script type="text/javascript" src="plugins/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php"></script>
<!--//[END DeeEmm TinyBrowser MOD]-->


<script type="text/javascript" src="plugins/tiny_mce/tiny_mce_gzip.js"></script>

<script type="text/javascript">
	tinyMCE_GZ.init({
            mode : "textareas",

		plugins : "imagemanager,filemanager,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,media,searchreplace,print,contextmenu,directionality,fullscreen",
		themes : "advanced",
		languages : "en",
		disk_cache : true,
		debug : false
	});

	if (window.attachEvent)
		window.attachEvent( "onload", InitTiny );
	else
		window.addEventListener( "load", InitTiny, false);

	function InitTiny() {
		// Notice: The simple theme does not use all options some of them are limited to the advanced theme
		tinyMCE.init({
			//[START DeeEmm TinyBrowser MOD]
			file_browser_callback : "tinyBrowser",
			//[END DeeEmm TinyBrowser MOD]
            convert_urls : false,
			mode : "specific_textareas",
			theme : "advanced",
	    	//[START DeeEmm TinyBrowser MOD]
			file_browser_callback : "imageManager",
			//[END DeeEmm TinyBrowser MOD]
			editor_selector : /(group_edit_html|story_edit_area|classfiedsTextArea|blogText|comment_textarea|form_input_html)/,

			plugins : "imagemanager,filemanager,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,directionality,fullscreen",

			theme_advanced_buttons1_add : "fontselect,fontsizeselect",
			theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,image,separator,search,replace,separator",
			theme_advanced_buttons2_add : "separator,insertdate,inserttime,separator,forecolor,backcolor",
			theme_advanced_buttons3_add : "emotions,insertfile,insertimage,fullscreen",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",

			plugi2n_insertdate_dateFormat : "%Y-%m-%d",
			plugi2n_insertdate_timeFormat : "%H:%M:%S",
			theme_advanced_resizing : true,
            theme_advanced_resize_horizontal : false,

            entity_encoding : "named",

            paste_use_dialog : false,
			paste_auto_cleanup_on_paste : true,
			paste_convert_headers_to_strong : false,
			paste_strip_class_attributes : "all",
			paste_remove_spans : false,
			paste_remove_styles : false
		});
	}
</script>
<!-- /tinyMCE -->
  </head>
  <body>
<form id="block_form" name="block_form" action="" method="post">
      <div style="width: auto;height: auto; background-color:#C2D3FF;padding:20px;">
          <div style="width: auto;height: auto; background-color:#EEF6FF;">
              <p style="width:630px; margin:0 auto; font-family:Georgia; font-size: larger;padding-top: 10px;"><b>{$_caption}:</b></p>
              
              <p style="width:630px; margin:0 auto; padding-top: 10px;"><textarea class="form_input_textarea form_input_html" name="Block_text" id="Block_text" style="width:630px;">{$_fieldInfo[$_fieldname]}</textarea></p>
              <p style="width:630px; margin:0 auto; padding-top: 10px;padding-bottom: 10px;"><input type="submit" id="draft"  name="draft" style="color: grey;cursor: pointer;font-weight:bold;padding:4px 15px;" value="Draft">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" id="Submit"  name="Submit" style="color: grey;cursor: pointer;font-weight:bold;padding:4px 15px;" value="Submit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit"  value="Cancel"  name="Cancel" id="Cancel" style="color: grey;cursor: pointer;font-weight:bold;padding:4px 15px;"></p>
          </div>
      </div>
</form>
EOH;
	return DesignBoxContent( "Block Edit Page", $sForm, $oTemplConfig -> PageCompThird_db_num );
}

function cleanALL()
{
    $aResult = array();
        //member menu
    	bx_import('BxDolMemberMenu');
        $oMemberMenu = new BxDolMemberMenu();
        $oMemberMenu -> deleteMemberMenuCaches();

        // page blocks
        bx_import('BxDolPageViewAdmin');
        $oPageViewCacher = new BxDolPageViewCacher ('', '');
        $oCachePb = $oPageViewCacher->getBlocksCacheObject ();
        $aResult = clearCacheObject ($oCachePb, 'pb_');
        if($aResult['code'] != 0)
            break;

        // users
        $aResult = clearCache('user', BX_DIRECTORY_PATH_CACHE);
        if($aResult['code'] != 0)
            break;

        // DB
        $oCacheDb = $GLOBALS['MySQL']->getDbCacheObject();
        $aResult = clearCacheObject ($oCacheDb, 'db_');
        if($aResult['code'] != 0)
            break;

        // templates
        $oCacheTemplates = $GLOBALS['oSysTemplate']->getTemplatesCacheObject();
        $aResult = clearCacheObject($oCacheTemplates, $GLOBALS['oSysTemplate']->_sCacheFilePrefix);
        if($aResult['code'] != 0)
            break;

        // CSS
        $aResult = clearCache($GLOBALS['oSysTemplate']->_sCssCachePrefix, BX_DIRECTORY_PATH_CACHE_PUBLIC);
        if($aResult['code'] != 0)
            break;

        // JS
        $aResult = clearCache($GLOBALS['oSysTemplate']->_sJsCachePrefix, BX_DIRECTORY_PATH_CACHE_PUBLIC);
        return $aResult;
}

function clearCacheObject($oCache, $sPrefix) {
    if (!$oCache->removeAllByPrefix ($sPrefix))
        return array('code' => 1, 'message' => _t('_adm_txt_dashboard_cache_clean_failed'));
    else
        return array('code' => 0, 'message' => _t('_adm_txt_dashboard_cache_clean_success'));
}

function clearCache($sPrefix, $sPath) {
    $aResult = array('code' => 0, 'message' => _t('_adm_txt_dashboard_cache_clean_success'));

    if($rHandler = opendir($sPath)) {
        while(($sFile = readdir($rHandler)) !== false)
            if(substr($sFile, 0, strlen($sPrefix)) == $sPrefix)
                @unlink($sPath . $sFile);
    }
    else
        $aResult = array('code' => 1, 'message' => _t('_adm_txt_dashboard_cache_clean_failed'));

    return $aResult;
}

?>

 