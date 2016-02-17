<?php
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by MChristiaan and cannot be modified for other than personal usage. 
* This product cannot be redistributed for free or a fee without written permission from MChristiaan. 
* This notice may not be removed from the source code.
*
***************************************************************************/

define('BX_SECURITY_EXCEPTIONS', true);

$aBxSecurityExceptions = array(
    'POST.request',
    'GET.request',
    'REQUEST.request',
);

bx_import('BxDolAlerts');
bx_import('BxDolModule');
bx_import('BxDolPaginate');
bx_import('BxDolPageView');

class MChrisMToolsModule extends BxDolModule {

    //Variables	
	var $mPageTmpl;
	
	function MChrisMToolsModule(&$aModule) {        
        parent::BxDolModule($aModule);
	    $this->_oConfig->init($this->_oDb);
	    $this->_oTemplate->init($this->_oDb);
		
        $mPageTmpl=array
            (
            'name_index' => 114,
            'header' => $GLOBALS['site']['title'],
            'header_text' => '',
            );		

        $GLOBALS['MChrisMToolsModule'] = &$this;
		
    }
	
	function actionAdministration () {
		
		if($this->admin_check() != 1)
		{			
			$this->_oTemplate->pageCode($this->mPageTmpl, array('page_main_code' => $this->admin_check()), false);
			return;
		}
		
		$JScript_DD = $this->jscript_DD();
		$pageCode .= $JScript_DD;
		
		$this->mPageTmpl['header'] = _t('_mchristiaan_mtoolsH');
        $this->mPageTmpl['header_text'] = _t('_mchristiaan_mtoolsH');
		$mtools_css = $this->_oTemplate->addCss('main.css', true);
		$mtools_js = $this->_oTemplate->addJs('main.js', true);		
		$mtools_jsjq = $this->_oTemplate->addJs(BX_DOL_URL_ROOT . '/plugins/jquery/jquery-ui.js', true);
		
		$iMLinks = $this->_oDb->getMenuLinks();
		
		$replace = array(
			'about_us.php' => '1',
			'privacy.php' => '3',
			'terms_of_use.php' => '4',
			'faq.php' => '5',
			'contact.php' => '2'
		);
		
		$basics = array(
			'about_us.php',
			'privacy.php',
			'terms_of_use.php',
			'faq.php',
			'contact.php'
		);
		
		$pageCode .= '
				<table border="0">
					<tr>
					<td colspan="5" ><table border="0"><tr>
					<td class="caption">
						<div id="tutorial">
							<div><div class="tbasics">&nbsp;</div>'. _t('_mchristiaan_mtools_tbasics') .' ' . _t('_mchristiaan_mtoolsT_caption') . '</div>
							<div><div class="tnormal">&nbsp;</div>'. _t('_mchristiaan_mtools_normal') .' ' . _t('_mchristiaan_mtoolsT_caption') . '</div>
							<div><div class="textra">&nbsp;</div>'. _t('_mchristiaan_mtools_textra') .' ' . _t('_mchristiaan_mtoolsT_caption') . '</div>
						</div>
					</td>
				  </tr></table></td>
				  </tr>
				  <tr>
					<td colspan="1" class="caption">'. _t('_mchristiaan_mtools_footer') .' ' . _t('_mchristiaan_mtoolsF_caption') . '</td>
					<td colspan="5" class="caption">'. _t('_mchristiaan_mtools_extra') .' ' . _t('_mchristiaan_mtoolsT_caption') . ':</td>
				  </tr>
				  <tr>
				  <td class="mpages">
				  <div class="contentWrap">
				  <div id="contentLeft">
				  <ul id="sortme">';				  
		$ImEdit = BX_DOL_URL_ROOT . 'modules/mchristiaan/mtools/templates/base/images/icons/page_edit.gif';
		$ImRem = BX_DOL_URL_ROOT . 'modules/mchristiaan/mtools/templates/base/images/icons/trashcan.gif';
		$ImPref = BX_DOL_URL_ROOT . 'modules/mchristiaan/mtools/templates/base/images/icons/settings.gif';
		foreach ($iMLinks as $iML => $iMLdata) {
			$bcontr = 0;
			$liMID = (int)$iMLdata['ID'];
			$liMName = $iMLdata['Name'];
			$liMOrder = $iMLdata['Order'];
			$liMUrl = $iMLdata['Link'];
			$liMScript = $iMLdata['Script'];
			$lURL = $this->_oDb->str_replace_assoc($replace,$liMUrl);
			
			foreach ($basics as $bdata) {
				if ($liMUrl == $bdata)
					$bcontr = 1;
			}
			
			
			if($liMScript == '' && $bcontr == 1){ $pageCode .= '<li title="'. _t('_mchristiaan_drag_text') .'" class="basics" id="menu_'.$liMID.'"><a title="'. _t('_mchristiaan_edit_text') .'" href="?r=mtools/peditor&mtools_prgr='.$lURL.'">'.$liMName.'</a>
												<span class="edits">
													<span class="edit_icon"><a title="'. _t('_mchristiaan_edit_text') .'" href="?r=mtools/peditor&mtools_prgr='.$lURL.'"><img class="settings" src="'.$ImEdit.'"/></a></span>
													<span class="pref_icon"><a title="'. _t('_mchristiaan_pref_text') .'" href="?r=mtools/editme&editme_id='.$liMID.'"><img class="settings" src="'.$ImPref.'"/></a></span>
													<span class=""><a title="'. _t('_mchristiaan_delete_text') .'" href="?r=mtools/removeme&remove_id='.$liMID.'">
													<img class="trash" src="'.$ImRem.'"/>
													</a></span>
												</span>'; }
			else{ $pageCode .= '<li title="'. _t('_mchristiaan_drag_text') .'" id="menu_'.$liMID.'">'.$liMName.'<span class="edits">
													<span class=""><a title="'. _t('_mchristiaan_pref_text') .'" href="?r=mtools/editme&editme_id='.$liMID.'"><img class="settings" src="'.$ImPref.'"/></a></span>
													<span class=""><a title="'. _t('_mchristiaan_delete_text') .'" href="?r=mtools/removeme&remove_id='.$liMID.'">
													<img class="trash" src="'.$ImRem.'"/>
													</a></span>
												</span>';}
			$pageCode .= '</li>';			
		}
		$pageCode .= '</ul><ul id="muladdme"><li title="'. _t('_mchristiaan_add_text') .'" class="maddme"><a href="?r=mtools/addme">'. _t('_mchristiaan_add_text') .'....</a></li>';
		$pageCode .= '
				  </ul></div></div></td>
				  <td class="mpages">
				  <div class="contentExtraWrap">
				  <div id="contentRight">
				  <ul id="sortme_extra">
					<li><a title="'. _t('_mchristiaan_edit_text') .'" href="?r=mtools/peditor&mtools_prgr=6">'. _t('_help') .'</a>
						<span class="edits">
									<span class="extra_icon"><a title="'. _t('_mchristiaan_edit_text') .'" href="?r=mtools/peditor&mtools_prgr=6"><img class="settings" src="'.$ImEdit.'"/></a></span></li>
					<li><a title="'. _t('_mchristiaan_edit_text') .'" href="?r=mtools/peditor&mtools_prgr=7">'. _t('_Advice') .'</a>
						<span class="edits">
									<span class="extra_icon"><a title="'. _t('_mchristiaan_edit_text') .'" href="?r=mtools/peditor&mtools_prgr=7"><img class="settings" src="'.$ImEdit.'"/></a></span></li>
				  </ul></div></div></td>
				  </tr>
			  </table>
			  <div class="mainwarning">' . _t('_mchristiaan_addme_file_warning') .'<span class="addme_warning">'.BX_DOL_URL_ROOT.'cache/sys_menu_bottom.inc</span></div>
			';
		
		$mPageTmpl['name_index']['page_main_code'] = $mtools_jsjq . $mtools_js . $mtools_css . $pageCode;
		
		$this->_oTemplate->pageCode($this->mPageTmpl, array('page_main_code' => $mPageTmpl['name_index']['page_main_code']), true);
  	}
	
	function actionPEditor () {
		
		if($this->admin_check() != 1)
		{			
			$this->_oTemplate->pageCode($this->mPageTmpl, array('page_main_code' => $this->admin_check()), false);
			return;
		}
				
		if (get_magic_quotes_gpc()) {
			function stripslashes_deep($value)
			{
				$value = is_array($value) ?
							array_map('stripslashes_deep', $value) :
							stripslashes($value);

				return $value;
			}

			$_POST = array_map('stripslashes_deep', $_POST);
			$_GET = array_map('stripslashes_deep', $_GET);
			$_COOKIE = array_map('stripslashes_deep', $_COOKIE);
			$_REQUEST = array_map('stripslashes_deep', $_REQUEST);
		}
				
        $this->mPageTmpl['header'] = _t('_mchristiaan_mtoolsH');
        $this->mPageTmpl['header_text'] = _t('_mchristiaan_mtoolsH');
		
		$mtools_prgr = $_GET['mtools_prgr'];
		$mtools_action = $_GET['mtools_action'];
		$mtools_lang = $_GET['mtools_lang'];

		if ($mtools_prgr == '')
			$TPRGR = 1;
		else
			$TPRGR = $mtools_prgr;
			
		if ($mtools_lang == '')
			$TLID = $this->_oDb->getfirstLang();
		else
			$TLID = $mtools_lang;
				
		$iTLangs = $this->_oDb->getLangs();
		
		if($TPRGR != 5){			
			$Editor = $this->editor();
			$pageCode .= $Editor;
		}
		
		switch ($TPRGR)
		{		
			default:
			case 1:		
					$mtools_pttext = $_POST['abus_pttext'];
					$mtools_bttext = $_POST['abus_bttext'];
					$mtools_text = $_POST['abus_text'];
					$mPageTmpl['name_index']['page_main_code'].=$this->ABUSCode($TLID,$iTLangs,$pageCode,$TPRGR,$mtools_action,$mtools_pttext,$mtools_bttext,$mtools_text);
					break;
			case 2:						
					$mtools_pttext = $_POST['conus_pttext'];
					$mtools_bttext = $_POST['conus_bttext'];
					$mtools_text = $_POST['conus_text'];
					$mPageTmpl['name_index']['page_main_code'].=$this->CONUSCode($TLID,$iTLangs,$pageCode,$TPRGR,$mtools_action,$mtools_pttext,$mtools_bttext);
					break;
			case 3:						
					$mtools_pttext = $_POST['priv_pttext'];
					$mtools_bttext = $_POST['priv_bttext'];
					$mtools_text = $_POST['priv_text'];
					$mPageTmpl['name_index']['page_main_code'].=$this->PRIVCode($TLID,$iTLangs,$pageCode,$TPRGR,$mtools_action,$mtools_pttext,$mtools_bttext,$mtools_text);
					break;
			case 4:						
					$mtools_pttext = $_POST['tpe_pttext'];
					$mtools_bttext = $_POST['tpe_bttext'];
					$mtools_text = $_POST['tpe_text'];
					$mPageTmpl['name_index']['page_main_code'].=$this->TPECode($TLID,$iTLangs,$pageCode,$TPRGR,$mtools_action,$mtools_pttext,$mtools_bttext,$mtools_text);
					break;
			case 5:						
					$mtools_pttext = $_POST['faq_pttext'];
					$mtools_bttext = $_POST['faq_bttext'];
					$mtools_text = $_POST['faq_text'];
					$mPageTmpl['name_index']['page_main_code'].=$this->FAQCode($TLID,$iTLangs,$pageCode,$TPRGR,$mtools_action,$mtools_pttext,$mtools_bttext,$mtools_text);
					break;
			case 6:						
					$mtools_pttext = $_POST['help_pttext'];
					$mtools_bttext = $_POST['help_bttext'];
					$mtools_text = $_POST['help_text'];
					$mPageTmpl['name_index']['page_main_code'].=$this->HELPCode($TLID,$iTLangs,$pageCode,$TPRGR,$mtools_action,$mtools_pttext,$mtools_bttext,$mtools_text);
					break;
			case 7:						
					$mtools_pttext = $_POST['adv_pttext'];
					$mtools_bttext = $_POST['adv_bttext'];
					$mtools_text = $_POST['adv_text'];
					$mPageTmpl['name_index']['page_main_code'].=$this->ADVCode($TLID,$iTLangs,$pageCode,$TPRGR,$mtools_action,$mtools_pttext,$mtools_bttext,$mtools_text);
					break;
		}			
		
	$mtools_js = $this->_oTemplate->addJs('main.js', true);
	$mtools_jstinymc = $this->_oTemplate->addJs(BX_DOL_URL_ROOT . 'plugins/tiny_mce/tiny_mce_gzip.js', true);
	$mtools_css = $this->_oTemplate->addCss('main.css', true);
	$mPageTmpl['name_index']['page_main_code'] = $mtools_js . $mtools_jstinymc . $mtools_css . $mPageTmpl['name_index']['page_main_code'];
	$this->_oTemplate->pageCode($this->mPageTmpl, array('page_main_code' => $mPageTmpl['name_index']['page_main_code']), true);	
	
	}
	
	function actionEditme () {
		
		if($this->admin_check() != 1)
		{			
			$this->_oTemplate->pageCode($this->mPageTmpl, array('page_main_code' => $this->admin_check()), false);
			return;
		}
		
		$mtools_css = $this->_oTemplate->addCss('main.css', true);
		$mtools_js = $this->_oTemplate->addJs('main.js', true);	
		
		if (get_magic_quotes_gpc()) {
			function stripslashes_deep($value)
			{
				$value = is_array($value) ?
							array_map('stripslashes_deep', $value) :
							stripslashes($value);

				return $value;
			}

			$_POST = array_map('stripslashes_deep', $_POST);
			$_GET = array_map('stripslashes_deep', $_GET);
			$_COOKIE = array_map('stripslashes_deep', $_COOKIE);
			$_REQUEST = array_map('stripslashes_deep', $_REQUEST);
		}

				
        $this->mPageTmpl['header'] = _t('_mchristiaan_mtoolsH');
        $this->mPageTmpl['header_text'] = _t('_mchristiaan_mtoolsH');
		
		$mtools_action = $_GET['mtools_action'];
		$editme_id = $_GET['editme_id'];
		$edit_me_id = $_POST['edit_me_id'];		
		$editme_caption_text = $_POST['editme_caption_text'];
		$editme_name_text = $_POST['editme_name_text'];
		$editme_link_text = $_POST['editme_link_text'];
		$editme_script_text = $_POST['editme_script_text'];
		
		$pageCode .= '<div class="goback"><a href="'.BX_DOL_URL_ROOT.'modules/?r=mtools/administration" target="_self">' . _t('_mchristiaan_mtools_go_back') . '</a></div>
						<form class="mtools_form" method="POST" action="?r=mtools/editme&mtools_action=save&editme_id='.$editme_id.'">
					  <table border="0">
					    <tr><td>						
					';
		$url = $this->_oDb->getMenuLink($editme_id);
		
		foreach ($url as $k => $iudata) {

			$liuID = $iudata['ID'];
			$liuCaption = $iudata['Caption'];
			$liuName = $iudata['Name'];
			$liuUrl = $iudata['Link'];
			$liuScript = $iudata['Script'];
		}
		
		if($editme_caption_text == '')
			$iEditCapText = $liuCaption;
		else			
			$iEditCapText = $editme_caption_text;
		if($editme_name_text == '')
			$iEditNameText = $liuName;
		else
			$iEditNameText = $editme_name_text;
		if($editme_link_text == '')
			$iEditLinkText = $liuUrl;
		else
			$iEditLinkText = $editme_link_text;
		if($editme_script_text == '')
			$iEditScriptText = $liuScript;
		else
			$iEditScriptText = $editme_script_text;
					
		if ($mtools_action == 'save'){
			if (($editme_caption_text != '' && $editme_name_text != '' && $editme_link_text != '')){
				$p = $this->_oDb->editMe($edit_me_id,$editme_caption_text,$editme_name_text,$editme_link_text,$editme_script_text);
				
				if ($p ==1)
				{
					$check_caption = $this->_oDb->getKeyID($iEditCapText);//Check caption in table keys
					if( empty($check_caption)){				//if not create in table keys and in string
						$sys_ID = $this->_oDb->getSystemID();
						$create_key = $this->_oDb->createKey($iEditCapText,$sys_ID);
						$check_caption2 = $this->_oDb->getKeyID($iEditCapText);
						
						$iTLangs = $this->_oDb->getLangs();
						
						foreach ($iTLangs as $iTLID => $iTLdata) {
							$tdLID = (int)$iTLdata['ID'];
							$this->_oDb->createString($check_caption2,$tdLID,$iEditNameText);						
						}
						
					}else{								//if so update the string
						$check_caption3 = $this->_oDb->getKeyID($iEditCapText);
						
						$iTLangs = $this->_oDb->getLangs();
						
						foreach ($iTLangs as $iTLID => $iTLdata) {
							$tdLID = (int)$iTLdata['ID'];
							$this->_oDb->updateKeyString($check_caption3,$tdLID,$iEditNameText);						
						}
					}
				}
				
				compileLanguage($TLID);
				if ($p == 1 || $create_key == 1){
					if ($p == 1 && $create_key == 1)//succes
						$pageCode .= $this->savemsg(1);
					if ($p == 1 && $check_caption != 0)//succes but caption already exist
						$pageCode .= $this->savemsg(3);
					if ($p == 1 && $check_caption == 0 && $create_key != 1)//error
						$pageCode .= $this->savemsg(0);
				}else{
					$pageCode .= $this->savemsg(0);
				}
			}else{
					$pageCode .= $this->savemsg(0);
				}
		}
		
		
		$pageCode .= '
				<table border="0">
				<tr id="editor_editme_cap_text">
				  <td class="caption">' . _t('_mchristiaan_addme_caption') . '<span class="star">*</span></td>
				  <td><input type="text" class="form_input_text form_input_html" name="editme_caption_text" value="'.$iEditCapText.'"/></td>
				  <td class="addme_warning">' . _t('_mchristiaan_addme_caption_warning') . '</td>
				</tr>
				<tr id="editor_editme_name_text">
				  <td class="caption">' . _t('_mchristiaan_addme_name') . '<span class="star">*</span></td>
				  <td><input type="text" class="form_input_text form_input_html" name="editme_name_text" value="'.$iEditNameText.'"/></td>
				  <td>&nbsp;</td>
				</tr>
				<tr id="editor_editme_link_text">
				  <td class="caption">' . _t('_mchristiaan_addme_link') . '<span class="star">*</span></td>
				  <td><input type="text" class="form_input_text form_input_html" name="editme_link_text" value="'.$iEditLinkText.'"/></td>
				  <td>&nbsp;</td>
				</tr>				
				<tr id="editor_editme_script_text">
				  <td class="caption">' . _t('_mchristiaan_addme_script') . '</td>
				  <td><input type="text" class="form_input_text form_input_html" name="editme_script_text" value="'.$iEditScriptText.'"/></td>
				  <td>&nbsp;</td>
				</tr>
				</table></td></tr>
				  <td>
					<input type="hidden" class="form_input_hidden form_input_html" name="edit_me_id" value="'.$liuID.'"/>
					<input type="submit" value="Save" name="B1">
				</td>
				</tr>				
			  </table>
			</form>
			<div class="mainwarning">' . _t('_mchristiaan_addme_file_warning') .'<span class="addme_warning">'.BX_DOL_URL_ROOT.'cache/sys_menu_bottom.inc</span></div>
			';
	
	$mPageTmpl['name_index']['page_main_code'] = DesignBoxContent(_t('_mchristiaan_editmeH_text'), $mtools_js . $mtools_css . $pageCode, 1); 	
	$this->_oTemplate->pageCode($this->mPageTmpl, array('page_main_code' => $mPageTmpl['name_index']['page_main_code']), true);	
	
	}
	
	function actionAddme () {
		
		if($this->admin_check() != 1)
		{			
			$this->_oTemplate->pageCode($this->mPageTmpl, array('page_main_code' => $this->admin_check()), false);
			return;
		}
		
		$mtools_css = $this->_oTemplate->addCss('main.css', true);
		$mtools_js = $this->_oTemplate->addJs('main.js', true);	
		
		if (get_magic_quotes_gpc()) {
			function stripslashes_deep($value)
			{
				$value = is_array($value) ?
							array_map('stripslashes_deep', $value) :
							stripslashes($value);

				return $value;
			}

			$_POST = array_map('stripslashes_deep', $_POST);
			$_GET = array_map('stripslashes_deep', $_GET);
			$_COOKIE = array_map('stripslashes_deep', $_COOKIE);
			$_REQUEST = array_map('stripslashes_deep', $_REQUEST);
		}

				
        $this->mPageTmpl['header'] = _t('_mchristiaan_mtoolsH');
        $this->mPageTmpl['header_text'] = _t('_mchristiaan_mtoolsH');
		
		$mtools_action = $_GET['mtools_action'];
		$addme_caption_text = $_POST['addme_caption_text'];
		$addme_name_text = $_POST['addme_name_text'];
		$addme_link_text = $_POST['addme_link_text'];
		$addme_script_text = $_POST['addme_script_text'];
		
		$pageCode .= '<div class="goback"><a href="'.BX_DOL_URL_ROOT.'modules/?r=mtools/administration" target="_self">' . _t('_mchristiaan_mtools_go_back') . '</a></div>
						<form class="mtools_form" method="POST" action="?r=mtools/addme&mtools_action=save">
					  <table border="0">
					    <tr><td>						
					';
					
		if ($mtools_action == 'save'){
			if (($addme_caption_text != '' && $addme_name_text != '' && $addme_link_text != '')){
				$p = $this->_oDb->addMe($addme_caption_text,$addme_name_text,$addme_link_text,$addme_script_text);
				if ($p == 1)
				{
					$check_caption = $this->_oDb->getKeyID($addme_caption_text);//Check caption in table keys
					if( empty($check_caption)){				//if not create in table keys and in string
						$sys_ID = $this->_oDb->getSystemID();
						$create_key = $this->_oDb->createKey($addme_caption_text,$sys_ID);
						$check_caption2 = $this->_oDb->getKeyID($addme_caption_text);
						
						$iTLangs = $this->_oDb->getLangs();
						
						foreach ($iTLangs as $iTLID => $iTLdata) {
							$tdLID = (int)$iTLdata['ID'];
							$this->_oDb->createString($check_caption2,$tdLID,$addme_name_text);						
						}
						
					}
				}
				compileLanguage($TLID);
				
				if ($p == 1 || $create_key == 1){
					if ($p == 1 && $create_key == 1)//succes
						$pageCode .= $this->savemsg(1);
					if ($p == 1 && $check_caption != 0)//succes but caption already exist
						$pageCode .= $this->savemsg(3);
					if ($p == 1 && $check_caption == 0 && $create_key != 1)//error
						$pageCode .= $this->savemsg(0);
				}else{
					$pageCode .= $this->savemsg(0);
				}
			}else{
					$pageCode .= $this->savemsg(0);
				}
		}
		
		if($addme_caption_text != '')
			$iAddCapText = $addme_caption_text;
		if($addme_name_text != '')
			$iAddNameText = $addme_name_text;
		if($addme_link_text != '')
			$iAddLinkText = $addme_link_text;
		if($addme_script_text != '')
			$iAddScriptText = $addme_script_text;
		
		$pageCode .= '
				<table border="0">
				<tr id="editor_addme_cap_text">
				  <td class="caption">' . _t('_mchristiaan_addme_caption') . '<span class="star">*</span></td>
				  <td><input type="text" class="form_input_text form_input_html" name="addme_caption_text" value="'.$iAddCapText.'"/></td>
				  <td class="addme_warning">' . _t('_mchristiaan_addme_caption_warning') . '</td>
				</tr>
				<tr id="editor_addme_name_text">
				  <td class="caption">' . _t('_mchristiaan_addme_name') . '<span class="star">*</span></td>
				  <td><input type="text" class="form_input_text form_input_html" name="addme_name_text" value="'.$iAddNameText.'"/></td>
				  <td>&nbsp;</td>
				</tr>
				<tr id="editor_addme_link_text">
				  <td class="caption">' . _t('_mchristiaan_addme_link') . '<span class="star">*</span></td>
				  <td><input type="text" class="form_input_text form_input_html" name="addme_link_text" value="'.$iAddLinkText.'"/></td>
				  <td>&nbsp;</td>
				</tr>				
				<tr id="editor_addme_script_text">
				  <td class="caption">' . _t('_mchristiaan_addme_script') . '</td>
				  <td><input type="text" class="form_input_text form_input_html" name="addme_script_text" value="'.$iAddScriptText.'"/></td>
				  <td>&nbsp;</td>
				</tr>
				</table></td></tr>
				  <td><input type="submit" value="Save" name="B1"></td>
				</tr>
			  </table>
			</form>
			<div class="mainwarning">' . _t('_mchristiaan_addme_file_warning') .'<span class="addme_warning">'.BX_DOL_URL_ROOT.'cache/sys_menu_bottom.inc</span></div>
			';
	
	$mPageTmpl['name_index']['page_main_code'] = DesignBoxContent(_t('_mchristiaan_addmeH_text'), $mtools_js . $mtools_css . $pageCode, 1); 	
	$this->_oTemplate->pageCode($this->mPageTmpl, array('page_main_code' => $mPageTmpl['name_index']['page_main_code']), true);	
	
	}
	
	function actionRemoveme () {
		
		if($this->admin_check() != 1)
		{			
			$this->_oTemplate->pageCode($this->mPageTmpl, array('page_main_code' => $this->admin_check()), false);
			return;
		}
		
		$mtools_css = $this->_oTemplate->addCss('main.css', true);
		$mtools_js = $this->_oTemplate->addJs('main.js', true);
		
		if (get_magic_quotes_gpc()) {
			function stripslashes_deep($value)
			{
				$value = is_array($value) ?
							array_map('stripslashes_deep', $value) :
							stripslashes($value);

				return $value;
			}

			$_POST = array_map('stripslashes_deep', $_POST);
			$_GET = array_map('stripslashes_deep', $_GET);
			$_COOKIE = array_map('stripslashes_deep', $_COOKIE);
			$_REQUEST = array_map('stripslashes_deep', $_REQUEST);
		}

				
        $this->mPageTmpl['header'] = _t('_mchristiaan_mtoolsH');
        $this->mPageTmpl['header_text'] = _t('_mchristiaan_mtoolsH');
		
		$mtools_action = $_GET['mtools_action'];
		$remove_id = $_GET['remove_id'];
		$removeme_id = $_POST['removeme_id'];
		
		$pageCode .= '<div class="goback"><a href="'.BX_DOL_URL_ROOT.'modules/?r=mtools/administration" target="_self">' . _t('_mchristiaan_mtools_go_back') . '</a></div>						
					';
					
		if ($mtools_action == 'remove' && $removeme_id != ''){
				$p = $this->_oDb->removeMe($removeme_id);
				compileLanguage($TLID);
				
				if ($p == 1){
				$pageCode .= $this->savemsg(2);
			}else{
				$pageCode .= $this->savemsg(0);
			}
		}
		
		$url = $this->_oDb->getMenuLink($remove_id);
		
		foreach ($url as $k => $iudata) {

			$liuID = $iudata['ID'];
			$liuName = $iudata['Name'];
		}
		
		if ($mtools_action != 'remove'){
			$pageCode .= '					
					<form class="mtools_form" method="POST" action="?r=mtools/removeme&mtools_action=remove">
					  <table border="0">
					<tr><td class="addme_warning">' . _t('_mchristiaan_removeme_warning').$liuName .'</td></tr>
					<tr>
					  <td>
						<input type="hidden" class="form_input_hidden form_input_html" name="removeme_id" value="'.$liuID.'"/>
						<input type="submit" value="' . _t('_mchristiaan_yes') .'" name="B1">
					</td>
					  </tr>
				  </table>
				</form>
				<div class="mainwarning">' . _t('_mchristiaan_addme_file_warning') .'<span class="addme_warning">'.BX_DOL_URL_ROOT.'cache/sys_menu_bottom.inc</span></div>
				';
		}
	
	$mPageTmpl['name_index']['page_main_code'] = DesignBoxContent(_t('_mchristiaan_removemeH_text'), $mtools_js . $mtools_css . $pageCode, 1); 	
	$this->_oTemplate->pageCode($this->mPageTmpl, array('page_main_code' => $mPageTmpl['name_index']['page_main_code']), true);	
	
	}
	
	function actionSortme(){
		
		if($this->admin_check() != 1)
		{			
			$this->_oTemplate->pageCode($this->mPageTmpl, array('page_main_code' => $this->admin_check()), false);
			return;
		}
		
		if (get_magic_quotes_gpc()) {
			function stripslashes_deep($value)
			{
				$value = is_array($value) ?
							array_map('stripslashes_deep', $value) :
							stripslashes($value);

				return $value;
			}

			$_POST = array_map('stripslashes_deep', $_POST);
			$_GET = array_map('stripslashes_deep', $_GET);
			$_COOKIE = array_map('stripslashes_deep', $_COOKIE);
			$_REQUEST = array_map('stripslashes_deep', $_REQUEST);
		}
		
		$menu = $_POST['menu'];		
		
		for ($i = 0; $i < count($menu); $i++) {
			$this->_oDb->updateRecords($i, $menu[$i]);
		}
		
	}
	
	function admin_check(){
		
		if (!$GLOBALS['logged']['admin']) { // check access to the page
			$this->mPageTmpl['header'] = 'Error';
			//$this->mPageTmpl['header_text'] = 'Error';
            return $mPageTmpl['name_index']['page_main_code'] = $this->_oTemplate->displayAccessDenied ();
        }else
			return 1;
	}
	
	function jscript_DD(){
		$dbSc1 = '
			<script type="text/javascript">
				$(document).ready(
					function() {
						$("#sortme").sortable({
						update : function () {
							serial = $(\'#sortme\').sortable(\'serialize\');
							$.ajax({
								url: "' . BX_DOL_URL_ROOT . 'modules/?r=mtools/sortme",
								type: "post",
								data: serial,
								error: function(){ alert("theres an error with AJAX");	}
							});
						}
						});
					}
				);

			</script>		
		';
		return $sCode = $dbSc1;
	}
	
	function editor(){
		$dbSc1 = '	
		<script type="text/javascript">
			tinyMCE_GZ.init({
				plugins : "table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,media,searchreplace,print,contextmenu,directionality,fullscreen",
				themes : "advanced",
				languages : "en",
				disk_cache : true,
				debug : false
			});
		</script>
		';
		return $sCode = $dbSc1;
	}

	function savemsg($i){
		if($i==1){
				return $pCode .= '	
					<div id="hidethisone" class="adm-db-content-wrapper">
						<div id="quotes_box">
							' . MsgBox(_t('_mchristiaan_mtoolsS_success')) . '</div>
							<div class="clear_both"></div>
						</div>
					</div>					
					';
		}elseif($i==0){
				return $pCode .= '	
					<div id="hidethisone" class="adm-db-content-wrapper">
						<div id="quotes_box">
							' . MsgBox(_t('_mchristiaan_mtoolsS_error')) . '</div>
							<div class="clear_both"></div>
						</div>
					</div>					
					';
		}elseif($i==2){
				return $pCode .= '	
					<div id="hidethisone" class="adm-db-content-wrapper">
						<div id="quotes_box">
							' . MsgBox(_t('_mchristiaan_mtoolsR_success')) . '</div>
							<div class="clear_both"></div>
						</div>
					</div>					
					';
		}elseif($i==3){
				return $pCode .= '	
					<div id="hidethisone" class="adm-db-content-wrapper">
						<div id="quotes_box">
							' . MsgBox(_t('_mchristiaan_mtoolsK_warning')) . '</div>
							<div class="clear_both"></div>
						</div>
					</div>					
					';
		}
	}
	
	function formheader($iTLangs,$TLID,$TPRGR){
		$pageCode .= '<div class="goback"><a href="'.BX_DOL_URL_ROOT.'modules/?r=mtools/administration" target="_self">' . _t('_mchristiaan_mtools_go_back') . '</a></div>
					<form class="mtools_form" method="POST" action="?r=mtools/peditor&mtools_action=save&mtools_prgr='.$TPRGR.'&mtools_lang=' . $TLID . '">
					  <table border="0">
						<tr>
						  <td><table border="0">
							  <tr>
								<td class="caption">' . _t('_mchristiaan_mtoolsL_caption') . '</td>
							  </tr>							  
					';
		$i = 0;			
		foreach ($iTLangs as $iTLID => $iTLdata) {
			
			if($i==0)
				$pageCode .= '<tr>';

			$tdLID = (int)$iTLdata['ID'];
			$tdLName = $iTLdata['Name'];
			$tdLTitle = $iTLdata['Title'];
			$tdLFlag = $iTLdata['Flag'];
			$tdImUrl = BX_DOL_URL_ROOT . 'media/images/flags/'. $tdLFlag .'.gif';
			if ($tdLID == $TLID) {
				$pageCode .= '<td id="selected"><img src="'. $tdImUrl .'"/>&nbsp;' . $tdLTitle . '</td>';
			} else {
				$pageCode .= '<td><img src="'. $tdImUrl .'"/>&nbsp;<a href="?r=mtools/peditor&mtools_prgr='.$TPRGR.'&mtools_lang=' . $tdLID . '">' . $tdLTitle . '</a></td>';
			}
			
			$i++;			
			if($i==4){
				$pageCode .= '</tr>';
				$i=0;
			}
		}
		
		if($i%4 !=0)
			$pageCode .= '</tr>';
		
		$pageCode .= '</table></td></tr>';
		
		return $pageCode;
	}
	
	function ABUSCode ($TLID,$iTLangs,$pageCode,$TPRGR,$mtools_action,$mtools_pttext,$mtools_bttext,$mtools_text) {
	
		$abus_action = $mtools_action;
		$abus_pttext = $mtools_pttext;
		$abus_bttext = $mtools_bttext;
		$abus_text = $mtools_text;
		
		$PageTitle = '_ABOUT_US_H';
		$BoxTitle = '_About';
		$StringText = '_ABOUT_US';
		$iTPTId = $this->_oDb->getId($PageTitle); // get our terms page title id
		$iTBTId = $this->_oDb->getId($BoxTitle); // get our terms box title id	
	    $iTId = $this->_oDb->getId($StringText); // get our terms id
		
	    if(empty($iTId) && empty($iTPTId) && empty($iTBTId)) { // if terms Id is not found display page not found
            $this->_oTemplate->displayIDNotFound ();
            return;
        }
		
        $iTPTString = $this->_oDb->getString($iTPTId,$TLID);
		$iTBTString = $this->_oDb->getString($iTBTId,$TLID);
		$iTString = $this->_oDb->getString($iTId,$TLID);		
        
		if ($abus_action == 'save' && ($abus_pttext != '' || $abus_bttext != '' || $abus_text != '')){
			$p = $this->_oDb->updateString($abus_pttext,$iTPTId,$TLID);
			$b = $this->_oDb->updateString($abus_bttext,$iTBTId,$TLID);
			$t = $this->_oDb->updateString($abus_text,$iTId,$TLID);
			compileLanguage($TLID);
			
			if ($t == 1 || $p == 1 || $b == 1){
				$pageCode .= $this->savemsg(1);
			}else{
				$pageCode .= $this->savemsg(0);
			}
		}
	
		$pageCode .= $this->formheader($iTLangs,$TLID,$TPRGR);
		
		if($abus_pttext == '')
			$iTPTtext = $iTPTString;
		else
			$iTPTtext = $abus_pttext;
			
		if($abus_bttext == '')
			$iTBTtext = $iTBTString;
		else
			$iTBTtext = $abus_bttext;
			
		if($abus_text == '')
			$iTtext = $iTString;
		else
			$iTtext = $abus_text;
		
		$pageCode .= '
				<tr>
				<td><table border="0">
				<tr id="editor_page_title_text">
				  <td class="caption">' . _t('_mchristiaan_abusP_caption') . '</td>
				  <td><input type="text" class="form_input_text form_input_html" name="abus_pttext" value="'.$iTPTtext.'"/></td>
				  <td>&nbsp;</td>
				</tr>
				<tr id="editor_box_title_text">
				  <td class="caption">' . _t('_mchristiaan_abusB_caption') . '</td>
				  <td><input type="text" class="form_input_text form_input_html" name="abus_bttext" value="'.$iTBTtext.'"/></td>
				  <td>&nbsp;</td>
				</tr>
				</table></td></tr>
				<tr id="editor_text">
				  <td><textarea class="form_input_textarea form_input_html" rows="20" name="abus_text" cols="64">' . $iTtext . '</textarea></td>
				</tr>
				  <td><input type="submit" value="Save" name="B1"></td>
				</tr>
			  </table>
			</form>
			';
			
            return DesignBoxContent(_t('_mchristiaan_abusH_text'), $pageCode, 1);
	
	}
	
	function CONUSCode ($TLID,$iTLangs,$pageCode,$TPRGR,$mtools_action,$mtools_pttext,$mtools_bttext) {
	
		$conus_action = $mtools_action;
		$conus_pttext = $mtools_pttext;
		$conus_bttext = $mtools_bttext;
		
		$PageTitle = '_CONTACT_H';
		$BoxTitle = '_CONTACT_H1';
		$iTPTId = $this->_oDb->getId($PageTitle); // get our terms page title id
		$iTBTId = $this->_oDb->getId($BoxTitle); // get our terms box title id
		
	    if(empty($iTPTId) && empty($iTBTId)) { // if terms Id is not found display page not found
            $this->_oTemplate->displayIDNotFound ();
            return;
        }
		
        $iTPTString = $this->_oDb->getString($iTPTId,$TLID);
		$iTBTString = $this->_oDb->getString($iTBTId,$TLID);
		
		if ($conus_action == 'save' && ($conus_pttext != '' || $conus_bttext != '')){
			$p = $this->_oDb->updateString($conus_pttext,$iTPTId,$TLID);
			$b = $this->_oDb->updateString($conus_bttext,$iTBTId,$TLID);
			compileLanguage($TLID);
			
			if ($p == 1 || $b == 1){
				$pageCode .= $this->savemsg(1);
			}else{
				$pageCode .= $this->savemsg(0);
			}
		}
	
		$pageCode .= $this->formheader($iTLangs,$TLID,$TPRGR);
		
		if($conus_pttext == '')
			$iTPTtext = $iTPTString;
		else
			$iTPTtext = $conus_pttext;
			
		if($conus_bttext == '')
			$iTBTtext = $iTBTString;
		else
			$iTBTtext = $conus_bttext;
			
		$homeurl = BX_DOL_URL_ROOT;
		
		$pageCode .= '
				<tr>
				<td><table border="0">
				<tr id="editor_page_title_text">
				  <td class="caption">' . _t('_mchristiaan_conusP_caption') . '</td>
				  <td><input type="text" class="form_input_text form_input_html" name="conus_pttext" value="'.$iTPTtext.'"/></td>
				  <td>&nbsp;</td>
				</tr>
				<tr id="editor_box_title_text">
				  <td class="caption">' . _t('_mchristiaan_conusB_caption') . '</td>
				  <td><input type="text" class="form_input_text form_input_html" name="conus_bttext" value="'.$iTBTtext.'"/></td>
				  <td>&nbsp;</td>
				</tr>
				</table></td></tr>
				<tr><td><table border="0">
				<tr>				
				  <td class="note">' . _t('_mchristiaan_conus_note_text') . '<br/> '.$homeurl.'contact.php</td>
				</tr>
				</table></td></tr>
				  <td><input type="submit" value="Save" name="B1"></td>
				</tr>
			  </table>
			</form>
			';
            
            return DesignBoxContent(_t('_mchristiaan_conusH_text'), $pageCode, 1);
	
	}
	
	function PRIVCode ($TLID,$iTLangs,$pageCode,$TPRGR,$mtools_action,$mtools_pttext,$mtools_bttext,$mtools_text) {
	
		$priv_action = $mtools_action;
		$priv_pttext = $mtools_pttext;
		$priv_bttext = $mtools_bttext;
		$priv_text = $mtools_text;
		
		$PageTitle = '_PRIVACY_H';
		$BoxTitle = '_PRIVACY_H1';
		$StringText = '_PRIVACY';
		$iTPTId = $this->_oDb->getId($PageTitle); // get our terms page title id
		$iTBTId = $this->_oDb->getId($BoxTitle); // get our terms box title id	
	    $iTId = $this->_oDb->getId($StringText); // get our terms id
		
	    if(empty($iTId) && empty($iTPTId) && empty($iTBTId)) { // if terms Id is not found display page not found
            $this->_oTemplate->displayIDNotFound ();
            return;
        }
		
        $iTPTString = $this->_oDb->getString($iTPTId,$TLID);
		$iTBTString = $this->_oDb->getString($iTBTId,$TLID);
		$iTString = $this->_oDb->getString($iTId,$TLID);		
        
		if ($priv_action == 'save' && ($priv_pttext != '' || $priv_bttext != '' || $priv_text != '')){
			$p = $this->_oDb->updateString($priv_pttext,$iTPTId,$TLID);
			$b = $this->_oDb->updateString($priv_bttext,$iTBTId,$TLID);
			$t = $this->_oDb->updateString($priv_text,$iTId,$TLID);
			compileLanguage($TLID);
			
			if ($t == 1 || $p == 1 || $b == 1){
				$pageCode .= $this->savemsg(1);
			}else{
				$pageCode .= $this->savemsg(0);
			}
		}
	
		$pageCode .= $this->formheader($iTLangs,$TLID,$TPRGR);
		
		if($priv_pttext == '')
			$iTPTtext = $iTPTString;
		else
			$iTPTtext = $priv_pttext;
			
		if($priv_bttext == '')
			$iTBTtext = $iTBTString;
		else
			$iTBTtext = $priv_bttext;
			
		if($priv_text == '')
			$iTtext = $iTString;
		else
			$iTtext = $priv_text;
		
		$pageCode .= '
				<tr>
				<td><table border="0">
				<tr id="editor_page_title_text">
				  <td class="caption">' . _t('_mchristiaan_privP_caption') . '</td>
				  <td><input type="text" class="form_input_text form_input_html" name="priv_pttext" value="'.$iTPTtext.'"/></td>
				  <td>&nbsp;</td>
				</tr>
				<tr id="editor_box_title_text">
				  <td class="caption">' . _t('_mchristiaan_privB_caption') . '</td>
				  <td><input type="text" class="form_input_text form_input_html" name="priv_bttext" value="'.$iTBTtext.'"/></td>
				  <td>&nbsp;</td>
				</tr>
				</table></td></tr>
				<tr id="editor_text">
				  <td><textarea class="form_input_textarea form_input_html" rows="20" name="priv_text" cols="64">' . $iTtext . '</textarea></td>
				</tr>
				  <td><input type="submit" value="Save" name="B1"></td>
				</tr>
			  </table>
			</form>
			';
            
            return DesignBoxContent(_t('_mchristiaan_privH_text'), $pageCode, 1);
	
	}
	
	function TPECode ($TLID,$iTLangs,$pageCode,$TPRGR,$mtools_action,$mtools_pttext,$mtools_bttext,$mtools_text) {
	
		$tpe_action = $mtools_action;
		$tpe_pttext = $mtools_pttext;
		$tpe_bttext = $mtools_bttext;
		$tpe_text = $mtools_text;
		
		$PageTitle = '_TERMS_OF_USE_H';
		$BoxTitle = '_TERMS_OF_USE_H1';
		$StringText = '_TERMS_OF_USE';
		$iTPTId = $this->_oDb->getId($PageTitle); // get our terms page title id
		$iTBTId = $this->_oDb->getId($BoxTitle); // get our terms box title id	
	    $iTId = $this->_oDb->getId($StringText); // get our terms id
		
	    if(empty($iTId) && empty($iTPTId) && empty($iTBTId)) { // if terms Id is not found display page not found
            $this->_oTemplate->displayIDNotFound ();
            return;
        }
		
        $iTPTString = $this->_oDb->getString($iTPTId,$TLID);
		$iTBTString = $this->_oDb->getString($iTBTId,$TLID);
		$iTString = $this->_oDb->getStringTPE($iTId,$TLID);		
        
		if ($tpe_action == 'save' && ($tpe_pttext != '' || $tpe_bttext != '' || $tpe_text != '')){
			$p = $this->_oDb->updateString($tpe_pttext,$iTPTId,$TLID);
			$b = $this->_oDb->updateString($tpe_bttext,$iTBTId,$TLID);
			$t = $this->_oDb->updateStringTPE($tpe_text,$iTId,$TLID);
			compileLanguage($TLID);
			
			if ($t == 1 || $p == 1 || $b == 1){
				$pageCode .= $this->savemsg(1);
			}else{
				$pageCode .= $this->savemsg(0);
			}
		}
	
		$pageCode .= $this->formheader($iTLangs,$TLID,$TPRGR);
		
		if($tpe_pttext == '')
			$iTPTtext = $iTPTString;
		else
			$iTPTtext = $tpe_pttext;
			
		if($tpe_bttext == '')
			$iTBTtext = $iTBTString;
		else
			$iTBTtext = $tpe_bttext;
			
		if($tpe_text == '')
			$iTtext = $iTString;
		else
			$iTtext = $tpe_text;
		
		$pageCode .= '
				<tr>
				<td><table border="0">
				<tr id="editor_page_title_text">
				  <td class="caption">' . _t('_mchristiaan_tpeP_caption') . '</td>
				  <td><input type="text" class="form_input_text form_input_html" name="tpe_pttext" value="'.$iTPTtext.'"/></td>
				  <td>&nbsp;</td>
				</tr>
				<tr id="editor_box_title_text">
				  <td class="caption">' . _t('_mchristiaan_tpeB_caption') . '</td>
				  <td><input type="text" class="form_input_text form_input_html" name="tpe_bttext" value="'.$iTBTtext.'"/></td>
				  <td>&nbsp;</td>
				</tr>
				</table></td></tr>
				<tr id="editor_text">
				  <td><textarea class="form_input_textarea form_input_html" rows="20" name="tpe_text" cols="64">' . $iTtext . '</textarea></td>
				</tr>
				  <td><input type="submit" value="Save" name="B1"></td>
				</tr>
			  </table>
			</form>
			';
			
            return DesignBoxContent(_t('_mchristiaan_tpeH_text'), $pageCode, 1);
	
	}

	function FAQCode ($TLID,$iTLangs,$pageCode,$TPRGR,$mtools_action,$mtools_pttext,$mtools_bttext,$mtools_text) {
	
		$faq_action = $mtools_action;
		$faq_pttext = $mtools_pttext;
		$faq_bttext = $mtools_bttext;
		$faq_text = $mtools_text;
		
		$PageTitle = '_FAQ_H';
		$BoxTitle = '_FAQ_H1';
		$StringText = '_FAQ_INFO';
		$iTPTId = $this->_oDb->getId($PageTitle); // get our terms page title id
		$iTBTId = $this->_oDb->getId($BoxTitle); // get our terms box title id	
	    $iTId = $this->_oDb->getId($StringText); // get our terms id
		
	    if(empty($iTId) && empty($iTPTId) && empty($iTBTId)) { // if terms Id is not found display page not found
            $this->_oTemplate->displayIDNotFound ();
            return;
        }
		
        $iTPTString = $this->_oDb->getString($iTPTId,$TLID);
		$iTBTString = $this->_oDb->getString($iTBTId,$TLID);
		$iTString = $this->_oDb->getStringFAQ($iTId,$TLID);		
        
		if ($faq_action == 'save' && ($faq_pttext != '' || $faq_bttext != '' || $faq_text != '')){
			$p = $this->_oDb->updateString($faq_pttext,$iTPTId,$TLID);
			$b = $this->_oDb->updateString($faq_bttext,$iTBTId,$TLID);
			$t = $this->_oDb->updateStringFAQ($faq_text,$iTId,$TLID);
			compileLanguage($TLID);
			
			if ($t == 1 || $p == 1 || $b == 1){
				$pageCode .= $this->savemsg(1);
			}else{
				$pageCode .= $this->savemsg(0);
			}
		}
	
		$pageCode .= $this->formheader($iTLangs,$TLID,$TPRGR);
		
		if($faq_pttext == '')
			$iTPTtext = $iTPTString;
		else
			$iTPTtext = $faq_pttext;
			
		if($faq_bttext == '')
			$iTBTtext = $iTBTString;
		else
			$iTBTtext = $faq_bttext;
			
		if($faq_text == '')
			$iTtext = $iTString;
		else
			$iTtext = $faq_text;
			
		$example = highlight_string('<faq_' . _t('_mchristiaan_faq_acontent') . '>
<faq_' . _t('_mchristiaan_faq_aheader') . '>Where can I get a free Dolphin license?</faq>
<faq_' . _t('_mchristiaan_faq_asnippet') . '>www.boonex.com</faq>
</faq>', true);
		
		$pageCode .= '
				<tr>
				<td><table border="0">
				<tr id="editor_page_title_text">
				  <td class="caption">' . _t('_mchristiaan_faqP_caption') . '</td>
				  <td><input type="text" class="form_input_text form_input_html" name="faq_pttext" value="'.$iTPTtext.'"/></td>
				  <td>&nbsp;</td>
				</tr>
				<tr id="editor_box_title_text">
				  <td class="caption">' . _t('_mchristiaan_faqB_caption') . '</td>
				  <td><input type="text" class="form_input_text form_input_html" name="faq_bttext" value="'.$iTBTtext.'"/></td>
				  <td>&nbsp;</td>
				</tr>
				</table></td></tr>
				<td>
				<table border="0">
						<tr>
						  <td class="caption">' . _t('_mchristiaan_faq_ex_caption') . '</td>
						</tr>
						<tr>
						  <td class="faq_note">';
							$pageCode .= $example;
							$pageCode .= '
						  </td>
						</tr>
				</table>
				</td>
				<tr id="editor_text">
				  <td><textarea class="form_input_textarea form_input_html" rows="20" name="faq_text" cols="64">' . $iTtext . '</textarea></td>
				</tr>
				  <td><input type="submit" value="Save" name="B1"></td>
				</tr>
			  </table>
			</form>			
			';
			
            return DesignBoxContent(_t('_mchristiaan_faqH_text'), $pageCode, 1);
	
	}
	
	function HELPCode ($TLID,$iTLangs,$pageCode,$TPRGR,$mtools_action,$mtools_pttext,$mtools_bttext,$mtools_text) {
	
		$help_action = $mtools_action;
		$help_pttext = $mtools_pttext;
		$help_bttext = $mtools_bttext;
		$help_text = $mtools_text;
		
		$PageTitle = '_HELP_H';
		$BoxTitle = '_HELP_H1';
		$StringText = '_HELP';
		$iTPTId = $this->_oDb->getId($PageTitle); // get our terms page title id
		$iTBTId = $this->_oDb->getId($BoxTitle); // get our terms box title id	
	    $iTId = $this->_oDb->getId($StringText); // get our terms id
		
	    if(empty($iTId) && empty($iTPTId) && empty($iTBTId)) { // if terms Id is not found display page not found
            $this->_oTemplate->displayIDNotFound ();
            return;
        }
		
        $iTPTString = $this->_oDb->getString($iTPTId,$TLID);
		$iTBTString = $this->_oDb->getString($iTBTId,$TLID);
		$iTString = $this->_oDb->getString($iTId,$TLID);		
        
		if ($help_action == 'save' && ($help_pttext != '' || $help_bttext != '' || $help_text != '')){
			$p = $this->_oDb->updateString($help_pttext,$iTPTId,$TLID);
			$b = $this->_oDb->updateString($help_bttext,$iTBTId,$TLID);
			$t = $this->_oDb->updateString($help_text,$iTId,$TLID);
			compileLanguage($TLID);
			
			if ($t == 1 || $p == 1 || $b == 1){
				$pageCode .= $this->savemsg(1);
			}else{
				$pageCode .= $this->savemsg(0);
			}
		}
	
		$pageCode .= $this->formheader($iTLangs,$TLID,$TPRGR);
		
		if($help_pttext == '')
			$iTPTtext = $iTPTString;
		else
			$iTPTtext = $help_pttext;
			
		if($help_bttext == '')
			$iTBTtext = $iTBTString;
		else
			$iTBTtext = $help_bttext;
			
		if($help_text == '')
			$iTtext = $iTString;
		else
			$iTtext = $help_text;
		
		$pageCode .= '
				<tr>
				<td><table border="0">
				<tr id="editor_page_title_text">
				  <td class="caption">' . _t('_mchristiaan_helpP_caption') . '</td>
				  <td><input type="text" class="form_input_text form_input_html" name="help_pttext" value="'.$iTPTtext.'"/></td>
				  <td>&nbsp;</td>
				</tr>
				<tr id="editor_box_title_text">
				  <td class="caption">' . _t('_mchristiaan_helpB_caption') . '</td>
				  <td><input type="text" class="form_input_text form_input_html" name="help_bttext" value="'.$iTBTtext.'"/></td>
				  <td>&nbsp;</td>
				</tr>
				</table></td></tr>
				<tr id="editor_text">
				  <td><textarea class="form_input_textarea form_input_html" rows="20" name="help_text" cols="64">' . $iTtext . '</textarea></td>
				</tr>
				  <td><input type="submit" value="Save" name="B1"></td>
				</tr>
			  </table>
			</form>
			';
			
            return DesignBoxContent(_t('_mchristiaan_helpH_text'), $pageCode, 1);
	
	}
	
	function ADVCode ($TLID,$iTLangs,$pageCode,$TPRGR,$mtools_action,$mtools_pttext,$mtools_bttext,$mtools_text) {
	
		$adv_action = $mtools_action;
		$adv_pttext = $mtools_pttext;
		$adv_bttext = $mtools_bttext;
		$adv_text = $mtools_text;
		
		$PageTitle = '_ADVICE_H';
		$BoxTitle = '_ADVICE_H1';
		$StringText = '_ADVICE';
		$iTPTId = $this->_oDb->getId($PageTitle); // get our terms page title id
		$iTBTId = $this->_oDb->getId($BoxTitle); // get our terms box title id	
	    $iTId = $this->_oDb->getId($StringText); // get our terms id
		
	    if(empty($iTId) && empty($iTPTId) && empty($iTBTId)) { // if terms Id is not found display page not found
            $this->_oTemplate->displayIDNotFound ();
            return;
        }
		
        $iTPTString = $this->_oDb->getString($iTPTId,$TLID);
		$iTBTString = $this->_oDb->getString($iTBTId,$TLID);
		$iTString = $this->_oDb->getString($iTId,$TLID);		
        
		if ($adv_action == 'save' && ($adv_pttext != '' || $adv_bttext != '' || $adv_text != '')){
			$p = $this->_oDb->updateString($adv_pttext,$iTPTId,$TLID);
			$b = $this->_oDb->updateString($adv_bttext,$iTBTId,$TLID);
			$t = $this->_oDb->updateString($adv_text,$iTId,$TLID);
			compileLanguage($TLID);
			
			if ($t == 1 || $p == 1 || $b == 1){
				$pageCode .= $this->savemsg(1);
			}else{
				$pageCode .= $this->savemsg(0);
			}
		}
	
		$pageCode .= $this->formheader($iTLangs,$TLID,$TPRGR);
		
		if($adv_pttext == '')
			$iTPTtext = $iTPTString;
		else
			$iTPTtext = $adv_pttext;
			
		if($adv_bttext == '')
			$iTBTtext = $iTBTString;
		else
			$iTBTtext = $adv_bttext;
			
		if($adv_text == '')
			$iTtext = $iTString;
		else
			$iTtext = $adv_text;
		
		$pageCode .= '
				<tr>
				<td><table border="0">
				<tr id="editor_page_title_text">
				  <td class="caption">' . _t('_mchristiaan_advP_caption') . '</td>
				  <td><input type="text" class="form_input_text form_input_html" name="adv_pttext" value="'.$iTPTtext.'"/></td>
				  <td>&nbsp;</td>
				</tr>
				<tr id="editor_box_title_text">
				  <td class="caption">' . _t('_mchristiaan_advB_caption') . '</td>
				  <td><input type="text" class="form_input_text form_input_html" name="adv_bttext" value="'.$iTBTtext.'"/></td>
				  <td>&nbsp;</td>
				</tr>
				</table></td></tr>
				<tr id="editor_text">
				  <td><textarea class="form_input_textarea form_input_html" rows="20" name="adv_text" cols="64">' . $iTtext . '</textarea></td>
				</tr>
				  <td><input type="submit" value="Save" name="B1"></td>
				</tr>
			  </table>
			</form>
			';
			
            return DesignBoxContent(_t('_mchristiaan_advH_text'), $pageCode, 1);
	
	}
	
}
?>
