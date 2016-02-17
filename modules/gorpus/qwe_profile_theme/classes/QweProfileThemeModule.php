<?php

require_once(BX_DIRECTORY_PATH_CLASSES . 'BxDolModule.php');
require_once('QweCssParser.php');

class QweProfileThemeModule extends BxDolModule
{
	function QweProfileThemeModule($aModule)
	{
        parent::BxDolModule($aModule);

		$this->aThemeTypes = array('myspace', 'other');

		$this->sDefaultThemeType = 'myspace';
	}

	function actionAdministration()
	{
		if (!$GLOBALS['logged']['admin']) {
			$this->_oTemplate->displayAccessDenied();
			return;
		}

		$this->_oTemplate->pageStart();

		if(@$_GET['qwe_message']) {
			echo MsgBox(_t($_GET['qwe_message']), 5);
		}

		$settings = DesignBoxAdmin (_t('qwe_profile_theme_adm_settings'), $this->_GetProfileThemeSettings());
		echo DesignBoxAdmin (_t('qwe_profile_theme_adm_add'), $this->_GetAddThemeCode());
		echo DesignBoxAdmin (_t('qwe_profile_theme_adm_manage'), $this->_GetManageItemsCode());
		echo $settings;
		echo DesignBoxAdmin (_t('qwe_profile_theme_themed_pages'), $this->_GetThemedPagesUris());
		echo DesignBoxAdmin (_t('qwe_profile_theme_add_new_page_uri'), $this->_AddNewThemedPageUri());
		$this->_oTemplate->pageCodeAdmin (_t('qwe_profile_theme_adm'));
	}
	
	function _GetThemedPagesUris()
	{
		$sSql = "SELECT * FROM `qwe_profile_theme_pages` ORDER BY `id` ASC ";
		$pages = db_res_assoc_arr($sSql);

		$sCode = '';
		foreach($pages as $k => $page) {
        	$sCode .= <<<EOF
<div style="">
	<div style="padding:5px 0px 0px 0px;">
		<input class="adm_check" id="ch{$page['id']}" type="checkbox" value="{$page['id']}" name="qwe_themed_page[]" />
		{$page['uri']}
	</div>
</div>
EOF;
		}

        $sAction = BX_DOL_URL_ROOT . 'modules/?r=qwe_profile_theme/AdminAction';
        bx_import('BxTemplSearchResult');
        $oSearchResult = new BxTemplSearchResult();
        $sAdmPanel = $oSearchResult->showAdminActionsPanel('qwe_themed_pages_list', array('action_delete_themed_page' => '_Delete'), 'qwe_themed_page');

        $sCode = <<<EOF
<form action="{$sAction}" method="post" name="quotes_moderation">
    <div class="adm-db-content-wrapper">
    	<div id="qwe_themed_pages_list" style="padding:10px 0px 0px 0px;">
    		{$sCode}
    		<div class="clear_both"></div>
    	</div>
    </div>
	{$sAdmPanel}
</form>
EOF;
		return $sCode;
	}
	
	function _AddNewThemedPageUri()
	{
		$sCode = '';
		$aForm = array(
			'form_attrs' => array(
				'name' => 'qwe_profile_theme_adm_new_page_uri',
				'id' => 'qwe_profile_theme_adm_new_page_uri',
				'action' => BX_DOL_URL_ROOT . "modules/gorpus/qwe_profile_theme/add_theme.php",
				'method' => 'post',
				'enctype' => "multipart/form-data",
			),
			'inputs' => array(
				'header1' => array(
					'type' => 'block_header',
					'caption' => _t('qwe_profile_theme_use_placeholder'),
				),			
				'header2' => array(
					'type' => 'block_header',
					'caption' => _t('qwe_profile_theme_use_placeholder_nickname'),
				),				
				'header3' => array(
					'type' => 'block_header',
					'caption' => _t('qwe_profile_theme_use_placeholder_id_regexp'),
				),				
				'header4' => array(
					'type' => 'block_header',
					'caption' => _t('qwe_profile_theme_use_placeholder_nickname_regexp'),
				),				
				'header5' => array(
					'type' => 'block_header',
					'caption' => _t('qwe_profile_theme_use_placeholder_my'),
				),				
				'header6' => array(
					'type' => 'block_header',
					'caption' => _t('qwe_profile_theme_use_placeholder_my_regexp'),
				),				
				'uri' => array(
					'type' => 'text',
					'name' => 'page_uri',
					'value' => '',
					'caption' => _t('qwe_profile_theme_new_page_uri'),
				),
				'add_new_page_uri' => array(
					'type' => 'submit',
					'name' => 'add_new_page_uri',
					'value' => _t('qwe_profile_theme_adm_add_new_page_uri'),
				),
			),
		);

		$oForm = new BxTemplFormView($aForm);

		$sCode = $oForm->getCode();

		return $sCode;
	}

	function _GetProfileThemeSettings()
	{
		$iId = (int)$GLOBALS['MySQL']->getOne("SELECT `ID` FROM `sys_options_cats` WHERE `name` = 'Gorpus Profile Themes' LIMIT 1");
		if(empty($iId))
			return MsgBox(_t('_sys_request_page_not_found_cpt'));

		bx_import('BxDolAdminSettings');

		$mixedResult = '';
		if(isset($_POST['save']) && isset($_POST['cat'])) {
			$oSettings = new BxDolAdminSettings($iId);
			$mixedResult = $oSettings->saveChanges($_POST);
		}

		$oSettings = new BxDolAdminSettings($iId);
		$sResult = $oSettings->getForm();

		if($mixedResult !== true && !empty($mixedResult))
			$sResult = $mixedResult . $sResult;

		return $sResult;
	}

	function actionAdminAction()
	{
		if (!$GLOBALS['logged']['admin']) {
			$this->_oTemplate->displayAccessDenied();
			return;
		}

		if(@$_POST['action_delete']) {
			if($_POST['qwe_theme'] && is_array($_POST['qwe_theme']) && count($_POST['qwe_theme'])) {

				foreach($_POST['qwe_theme'] as $iIndex => $iId) {
					$iId = (int)$iId;
					$_POST['qwe_theme'][$iIndex] = $iId;
					$sSql = "SELECT `file` FROM `qwe_profile_theme_base` WHERE `id`  = '".$iId."'";
					$sFileName = db_value($sSql);
					@unlink(BX_DIRECTORY_PATH_ROOT . 'modules/gorpus/qwe_profile_theme/data/' . $sFileName);
				}

				$sSql = "DELETE FROM `qwe_profile_theme_base` WHERE `id` IN (".join(", ", $_POST['qwe_theme']).")";
				db_res($sSql);
			}
			
			$sMessage = _t('qwe_profile_theme_selected_themes_deleted');			
		}
		elseif(@$_POST['action_delete_themed_page']) {
			if($_POST['qwe_themed_page'] && is_array($_POST['qwe_themed_page']) && count($_POST['qwe_themed_page'])) {

				foreach($_POST['qwe_themed_page'] as $iIndex => $iId) {
					$iId = (int)$iId;
					$_POST['qwe_themed_page'][$iIndex] = $iId;
				}

				$sSql = "DELETE FROM `qwe_profile_theme_pages` WHERE `id` IN (".join(", ", $_POST['qwe_themed_page']).")";
				db_res($sSql);
			}
			
			$sMessage = _t('qwe_profile_theme_selected_uri_deleted');			
		}
		
		header("Location: " . BX_DOL_URL_ROOT . "modules/?r=qwe_profile_theme/administration&qwe_message=" . $sMessage);
	}

	function _GetAddThemeCode()
	{
		$sCode = '';
		$aForm = array(
			'form_attrs' => array(
				'name' => 'qwe_profile_theme_adm_add_form',
				'id' => 'qwe_profile_theme_adm_add_form',
				'action' => BX_DOL_URL_ROOT . "modules/gorpus/qwe_profile_theme/add_theme.php",
				'method' => 'post',
				'enctype' => "multipart/form-data",
			),
			'inputs' => array(
				'title' => array(
					'type' => 'text',
					'name' => 'title',
					'value' => '',
					'caption' => _t('qwe_profile_theme_adm_title'),
				),
				'css' => array(
					'type' => 'textarea',
					'html' => false,
					'name' => 'css',
					'value' => '',
					'caption' => _t('qwe_profile_theme_form_css_body'),
					'required' => false,
				),
				'theme_type' => array(
					'type' => 'radio_set',
					'name' => 'theme_type',
					'caption' => _t('qwe_profile_theme_form_theme_type'),
					'values' => array(
						'myspace' => _t('qwe_profile_theme_form_theme_type_myspace'),
						'other' => _t('qwe_profile_theme_form_theme_type_other'),
					),
					'value' => array_search(@$aThemeRecord['type'], $this->aThemeTypes) === FALSE ? $this->sDefaultThemeType : $aThemeRecord['type'],
				),
				'thumb' => array(
					'type' => 'file',
					'html' => false,
					'name' => 'thumb',
					'value' => '',
					'caption' => _t('qwe_profile_theme_adm_thumb'),
					'required' => false,
				),
				'add_theme' => array(
					'type' => 'submit',
					'name' => 'add_theme',
					'value' => _t('qwe_profile_theme_adm_add_theme'),
				),
			),
		);

		$oForm = new BxTemplFormView($aForm);

		$sCode = $oForm->getCode();

		return $sCode;
	}

	function _GetManageItemsCode()
	{
		$sSql = "SELECT COUNT(*) FROM `qwe_profile_theme_base`";
		$iThemesCount = db_value($sSql);

		$limit = (int)getParam('qwe_profile_theme_per_page');
		$offset = (int)@$_GET['offset'];

		$oPaginate = new BxDolPaginate(array(
			'start' => $offset,
			'count' => $iThemesCount,
			'per_page' => $limit,
			'page_url' => $_SERVER['PHP_SELF'] . '?r=qwe_profile_theme/administration/&offset={start}',
		));
		$sPagination = $oPaginate->getPaginate();

		$sSql = "SELECT * FROM `qwe_profile_theme_base` LIMIT " . $offset . ", " . $limit;
		$aThemes = db_res_assoc_arr($sSql);

		$sCode = '';
		foreach($aThemes as $iKey => $aTheme) {
			$aTheme['img_src'] = BX_DOL_URL_ROOT . 'modules/gorpus/qwe_profile_theme/data/' . $aTheme['file'];
			$sCode .= $this->_oTemplate->parseHtmlByName('adm_unit', $aTheme);
		}

        $sAction = BX_DOL_URL_ROOT . 'modules/?r=qwe_profile_theme/AdminAction';
        bx_import('BxTemplSearchResult');
        $oSearchResult = new BxTemplSearchResult();
        $sAdmPanel = $oSearchResult->showAdminActionsPanel('qwe_themes_list', array('action_delete' => '_Delete'), 'qwe_theme');

        $sCode = <<<EOF
<form action="{$sAction}" method="post" name="quotes_moderation">
    <div class="adm-db-content-wrapper">
    	<div id="qwe_themes_list" style="padding:10px 0px 0px 30px;">
    		{$sCode}
    		<div class="clear_both"></div>
    	</div>
		{$sPagination}
    </div>
	{$sAdmPanel}
</form>
EOF;

		return $sCode;
	}

	function actionGetThemeCode()
	{
		$sTheme = (int)@$_GET['theme'];
		$sCss = '';
		$sType = '';
		if($sTheme) {
			$sSql = "SELECT `css`, `type` FROM `qwe_profile_theme_base` WHERE `id` = '".(int)$sTheme."' ";
			$aThemeData = db_assoc_arr($sSql);
			if($aThemeData && is_array($aThemeData) && count($aThemeData) > 0) {
				$sCss = $aThemeData['css'];
				$sType = $aThemeData['type'];
			}
		}
		echo json_encode(array('code' => trim($sCss), 'type' => $sType));
	}

	function actionHome ()
	{
		global $_page, $_page_cont;

		$_page['name_index'] = 0;
		$_page['header'] = _t('qwe_profile_theme_editing');
		$_page['header_text'] = _t('qwe_profile_theme_editing');
		$_ni = $_page['name_index'];

		$iProfileId = (int)getLoggedId();
		$sCurrentCss = '';
		$sSql = "SELECT * FROM `".$this->_oConfig->GetDbPrefix()."themes` WHERE `profile_id` = '".$iProfileId."'";
		$aThemeRecord = db_arr($sSql);

		if($aThemeRecord) {
			$sCurrentCss = $aThemeRecord['css'];
		}

		$this->_oTemplate->addCss('edit_theme.css');

		if(@$_GET['qwe_message']) {
			$_page_cont[$_ni]['page_main_code'] = MsgBox(_t($_GET['qwe_message']));

			ob_start();
			?>
			<br />
			<div class="qwe_profile_theme_saved_links">
				<a href="<?=BX_DOL_URL_ROOT?>modules/?r=qwe_profile_theme/"><?=_t('qwe_profile_theme_chenage_theme')?></a> | <a href="<?=getProfileLink($iProfileId)?>"><?=_t('qwe_profile_theme_got_to_profile')?></a>
			</div>
			<?
			$_page_cont[$_ni]['page_main_code'] .= ob_get_clean();

			PageCode();
			exit;
		}

		$aForm = array(
			'form_attrs' => array(
				'name' => 'formEditProfileTheme',
				'id' => 'formEditProfileTheme',
				'action' => BX_DOL_URL_ROOT . "modules/gorpus/qwe_profile_theme/save_css.php",
				'method' => 'post',
			),
			'inputs' => array(
				'profile_id' => array(
					'type' => 'hidden',
					'name' => 'profile_id',
					'value' => $iProfileId,
				),
				'header1' => array(
					'type' => 'block_header',
					'caption' => _t('qwe_profile_theme_form_explanation0'),
				),
				'header2' => array(
					'type' => 'block_header',
					'caption' => _t('qwe_profile_theme_form_explanation1'),
				),
				'header3' => array(
					'type' => 'block_header',
					'html' => true,
					'caption' => $this->_GetThemesGallery((int)@$aThemeRecord['id']),
				),
				'css' => array(
					'type' => 'textarea',
					'html' => false,
					'name' => 'qwe_css_body',
					'id' => 'qwe_css_body',
					'label' => '',
					'value' => $sCurrentCss,
					'caption' => _t('qwe_profile_theme_form_css_body'),
					'required' => false,
				),
				'theme_type' => array(
					'type' => 'hidden',
					'name' => 'theme_type',
					'value' => array_search(@$aThemeRecord['type'], $this->aThemeTypes) === FALSE ? $this->sDefaultThemeType : $aThemeRecord['type'],
				),
				/*
				'theme_type' => array(
					'type' => 'radio_set',
					'name' => 'qwe_theme_type',
					'id' => 'qwe_theme_type',
					'label' => '',
					'caption' => _t('qwe_profile_theme_form_theme_type'),
					'values' => array(
						'myspace' => _t('qwe_profile_theme_form_theme_type_myspace'),
						'other' => _t('qwe_profile_theme_form_theme_type_other'),
					),
					'value' => array_search(@$aThemeRecord['type'], $this->aThemeTypes) === FALSE ? $this->sDefaultThemeType : $aThemeRecord['type'],
				),
				*/
				'apply_code' => array(
					'type' => 'submit',
					'name' => 'apply_code',
					'value' => _t('qwe_profile_theme_form_apply_code'),
				),
				'preview_theme' => array(
					'type' => 'button',
					'name' => 'qwe_preview_theme',
					'id' => 'qwe_preview_theme',
					'label' => '',
					'value' => _t('qwe_profile_theme_form_preview_theme'),
				),
				'restore_current' => array(
					'type' => 'button',
					'name' => 'qwe_restore_current',
					'id' => 'qwe_restore_current',
					'label' => '',
					'value' => _t('qwe_profile_theme_form_restore_current'),
				),
				'reset_theme' => array(
					'type' => 'button',
					'name' => 'qwe_reset_theme',
					'id' => 'qwe_reset_theme',
					'label' => '',
					'value' => _t('qwe_profile_theme_form_reset_theme'),
				)
			),
		);

		$oForm = new BxTemplFormView($aForm);

		ob_start();

		?>
			<div class="qwe_profile_theme_css_form">
			<?=$oForm->getCode()?>
			<?=LoadingBox('qwe_profile_theme_loading')?>
			</div>			

			<form id="qwe_preview_profile_theme" method="post" action="<?=BX_DOL_URL_ROOT?>modules/gorpus/qwe_profile_theme/preview_profile.php" style="display:none;" target="_blank">
				<textarea name="profile_theme_css" id="profile_theme_css"></textarea>
				<input type="hidden" name="profile_theme_type" id="profile_theme_type" value="" />
			</form>

			<textarea style="display:none;" name="qwe_current_theme_css_body" id="qwe_current_theme_css_body"><?=@$aThemeRecord['css']?></textarea>			

			<script type="text/javascript">

				function QweGalleryThemeClick()
				{
					var selected_theme = $(this).attr('theme_id');

					if(!selected_theme) {
						alert('<?=_t("qwe_profile_theme_theme_not_selected")?>');
						return;
					}

					$('#qwe_profile_theme_loading').bx_loading();

					$.getJSON("<?=BX_DOL_URL_ROOT?>modules/?r=qwe_profile_theme/getThemeCode", {theme: selected_theme},
						function(data){
							$('#qwe_css_body').text(data.code);
							$("input[name='qwe_theme_type']").each(function(){
								if($(this).val() == data.type) {
									$(this).attr('checked', 'true');
								}
								else {
									$(this).removeAttr('checked');									
								}
							});
							$('#qwe_profile_theme_loading').bx_loading();
						}
					);

					return false;
				}

				$(document).ready(function(){
					$('.qwe_profile_theme_gallery_item').each(function(){
						$(this).click(QweGalleryThemeClick);
					});

					$('.qwe_profile_theme_gallery_item img').each(function(){
						$(this).click(QweGalleryThemeClick);
					});

					$('.qwe_profile_theme_gallery_title').each(function(){
						$(this).click(QweGalleryThemeClick);
					});

					$('#qwe_preview_theme').click(function(){
						$('#profile_theme_css').text($('#qwe_css_body').val());
						$('#profile_theme_type').val($("input[name='qwe_theme_type']:checked").val());
						$('#qwe_preview_profile_theme').submit();
					});

					$('#qwe_reset_theme').click(function(){
						if(confirm("<?=_t('qwe_profile_theme_sure_reset')?>")) {
							document.location = "<?=BX_DOL_URL_ROOT?>modules/gorpus/qwe_profile_theme/save_css.php?reset_profile_theme=1";
						}
					});

					$('#qwe_restore_current').click(function(){
						if(confirm("<?=_t('qwe_profile_theme_sure_restore_current')?>")) {
							$('#qwe_css_body').text($('#qwe_current_theme_css_body').val());
						}
					});					
				});

				<?if($_GET['message']):?>
					alert('<?=t($_GET['message'])?>');
				<?endif?>

			</script>

		<?

		$_page_cont[$_ni]['page_main_code'] = ob_get_clean();

		PageCode();
	}

	function _GetThemesGallery($iCurrentThemeId)
	{
		$sSql = "SELECT COUNT(*) FROM `qwe_profile_theme_base`";
		$iThemesCount = db_value($sSql);

		$limit = (int)getParam('qwe_profile_theme_per_page');
		$offset = (int)@$_GET['offset'];

		$oPaginate = new BxDolPaginate(array(
			'start' => $offset,
			'count' => $iThemesCount,
			'per_page' => $limit,
			'page_url' => $_SERVER['PHP_SELF'] . '?r=qwe_profile_theme/&offset={start}',
		));
		$sPagination = $oPaginate->getPaginate();

		$sSql = "SELECT * FROM `qwe_profile_theme_base` LIMIT " . $offset . ", " . $limit;
		$aThemes = db_res_assoc_arr($sSql);

		ob_start();
?>
		<div class="qwe_profile_theme_gallery">
			<div class="qwe_profile_theme_gallery_sub">

		<?foreach($aThemes as $iIndex => $aTheme):?>
			<div class="qwe_profile_theme_gallery_item" title="<?=$aTheme['title']?>" theme_id="<?=$aTheme['id']?>">
				<div class="qwe_profile_theme_gallery_title" theme_id="<?=$aTheme['id']?>"><?=$aTheme['title']?></div>
				<img src="<?=BX_DOL_URL_ROOT . 'modules/gorpus/qwe_profile_theme/data/' . $aTheme['file']?>" theme_id="<?=$aTheme['id']?>"/>
			</div>
		<?endforeach?>

			<div style="float:none;clear:both;"></div>
			</div>
			<?=$sPagination?>
		</div>		
<?
		$sGallery = ob_get_clean();
		return $sGallery;
	}

#################################################################
#		PARSING AND INTEGRATING 3rd party CSS					#
#################################################################

	function serviceGetInjectionHeader()
	{
		global $_page;
		global $oSysTemplate;

		$iProfileId = 0;

		if(!$GLOBALS['QWE_VARS']['PROFILE_THEME']['IS_CUSTOM_CSS']) {
			return '';
		}

		$oParserDefault = new QweCssParser();
		$oParserDefault->Parse($this->_oConfig->GetHomePath() . 'resource/myspace_default.css');

		$oParser = new QweCssParser();

		$sCurrentCss = '';
		$sThemeType = $this->sDefaultThemeType;

		$sCurrentCss = @$GLOBALS['QWE_VARS']['PROFILE_THEME']['CURRENT_CSS'];
		$sThemeType = @$GLOBALS['QWE_VARS']['PROFILE_THEME']['THEME_TYPE'];

		$sThemeType = array_search($sThemeType, $this->aThemeTypes) === FALSE ? $this->sDefaultThemeType : $sThemeType;
		$oParser->ParseStr($sCurrentCss);

		$myspace_exclude_props = array('margin', 'padding', 'position', 'display', 'float', 'left', 'top', 'width', 'height');

		$DOLSTYLE = array(
			'block_bg_color' => '',
			'block_border_color' => '',
			'block_border_style' => '',
			'block_border_width' => '',
			'block_color' => '',
			'link_color' => '',
			'block_link_color' => '',
		);
		
		foreach($oParser->css as $el => $style) {
			if(!$DOLSTYLE['block_bg_color'] && $style['background-color'] && $style['background-color'] != 'transparent' && strpos($el, 'table') !== FALSE) {
				$DOLSTYLE['block_bg_color'] = qwe_css_color($style['background-color']);
			}
			
			if($style['border-color'] && strpos($el, 'table') !== FALSE) {
				$DOLSTYLE['block_border_color'] = qwe_css_color($style['border-color']);
			}
			
			if($style['border-style'] && strpos($el, 'table') !== FALSE) {
				$DOLSTYLE['block_border_style'] = $style['border-style'];
			}

			if($style['border-width'] && strpos($el, 'table') !== FALSE) {
				$DOLSTYLE['block_border_width'] = qwe_match('/(\d+)/', $style['border-width']);
			}

			if($style['color'] && $style['color'] != 'transparent' && (strpos($el, 'table') !== FALSE || strpos($el, 'span') !== FALSE || strpos($el, 'div') !== FALSE)) {
				$DOLSTYLE['block_color'] = qwe_css_color($style['color']);
			}			
			
			if($style['color'] && $style['color'] != 'transparent' && preg_match('/(\W+|^)a(\W+|$)/', $el)) {
				$DOLSTYLE['link_color'] = qwe_css_color($style['color']);
				$DOLSTYLE['block_link_color'] = qwe_css_color($style['color']);
			}			
			
			if(($style['background-color'] || $style['background']) && strpos($el, 'moduleTop') !== FALSE) {
				$bg = @$style['background-color'] ? @$style['background-color'] : $style['background'];
				$bgc = qwe_css_extract_background_color($oParser->css['body']);
				if($bgc) {
					$DOLSTYLE['block_bg_color'] = $bgc;
				}
			}
		}
		
		#	detect body background color
		$body_style = $oParser->css['body'];
		$bg_color = qwe_css_extract_background_color($oParser->css['body']);
		
		if(!$bg_color) {
			$bg_color = qwe_css_extract_background_color($oParser->css['html']);
		}
		
		#print_r($DOLSTYLE);		
		#var_dump($bg_color);
		#exit;
		
		if($bg_color) {
			
			$CALCULATED_DOLSTYLE = array(
				'block_bg_color' => qwe_color_dark($bg_color, 20),
				'block_table_bg_color' => qwe_color_light($bg_color, 10),
				'block_table_border_color' => $bg_color,
				'block_table_color' => $DOLSTYLE['block_color'] ? qwe_color_dark($DOLSTYLE['block_color'], 10) : qwe_color_dark($bg_color, 80),			
			);			
			
			foreach($CALCULATED_DOLSTYLE as $key => $val) {
				if(!$DOLSTYLE[$key]) {
					$DOLSTYLE[$key] = $val;
				}
			}
		}
		
		if($DOLSTYLE['block_bg_color']) {
			$DOLSTYLE['block_border_color'] = qwe_color_dark($DOLSTYLE['block_bg_color'], 40);
		}

		#var_dump($oParser->css);
		#exit;
		
		if(qwe_is_colors_same($DOLSTYLE['block_table_border_color'], $DOLSTYLE['block_table_bg_color'])) {
			$DOLSTYLE['block_table_border_color'] = qwe_small_difference_color($DOLSTYLE['block_table_bg_color']);
		}
				
		if(qwe_is_colors_same($DOLSTYLE['block_bg_color'], $bg_color)) {
			$DOLSTYLE['block_bg_color'] = qwe_small_difference_color($DOLSTYLE['block_bg_color']);
		}
		
		if(qwe_is_colors_same($DOLSTYLE['block_bg_color'], $DOLSTYLE['block_color'])) {
			$DOLSTYLE['block_color'] = qwe_huge_difference_color($DOLSTYLE['block_bg_color']);
			$DOLSTYLE['block_table_color'] = qwe_color_dark($DOLSTYLE['block_color'], 10);
		}
		
		if($DOLSTYLE['block_table_color'] && qwe_is_colors_same($DOLSTYLE['block_table_color'], $DOLSTYLE['block_table_bg_color'])) {
			$DOLSTYLE['block_table_color'] = qwe_huge_difference_color($DOLSTYLE['block_table_bg_color']);
		}
		
		$block_header_bg = qwe_css_extract_background_color($oParser->css['h3.moduleHead']);
		if($block_header_bg) {
			$DOLSTYLE['block_title_color'] = qwe_huge_difference_color($block_header_bg);
		}
		else {
			$DOLSTYLE['block_title_color'] = $DOLSTYLE['block_color'];
		}
		
		if($bg_color) {
			$DOLSTYLE['quote_color'] = qwe_huge_difference_color($bg_color);
		}
		
		if(qwe_is_colors_same($DOLSTYLE['block_bg_color'], $DOLSTYLE['block_link_color'])) {
			$DOLSTYLE['block_link_color'] = qwe_huge_difference_color($DOLSTYLE['block_bg_color']);
		}
		
		#exit;
		
		$DOLSTYLE['top_menu_bg_color_hover'] = qwe_css_color($oParser->GetRaw('#topnav ul li a:hover', 'background-color'));
		if(!trim($DOLSTYLE['top_menu_bg_color_hover'])) {
			$DOLSTYLE['top_menu_bg_color_hover'] = qwe_css_color($DOLSTYLE['block_bg_color']);
		}
		
		$DOLSTYLE['top_menu_bg_color'] = qwe_css_color($oParser->GetRaw('#topnav', 'background-color'));
		$DOLSTYLE['top_menu_color_hover'] = qwe_css_color($oParser->GetRaw('#topnav ul a:hover', 'color'));
		$DOLSTYLE['top_menu_color'] = qwe_css_color($oParser->Get('#topnav a', 'color'));
		$DOLSTYLE['top_bc_color'] = $DOLSTYLE['quote_color'];
		
		if(qwe_is_colors_same($DOLSTYLE['top_menu_color'], $DOLSTYLE['top_menu_bg_color'])
		 || (!$DOLSTYLE['top_menu_color'] && $DOLSTYLE['top_menu_bg_color'])) {
			$DOLSTYLE['top_menu_color'] = qwe_huge_difference_color($DOLSTYLE['top_menu_bg_color']);
		}		
		
		if(qwe_is_colors_same($DOLSTYLE['top_menu_color_hover'], $DOLSTYLE['top_menu_bg_color_hover'])
		 || (!$DOLSTYLE['top_menu_color_hover'] && $DOLSTYLE['top_menu_bg_color_hover'])) {
			$DOLSTYLE['top_menu_color_hover'] = qwe_huge_difference_color($DOLSTYLE['top_menu_bg_color_hover']);
		}
		
		$DOLSTYLE['block_head_bg_color'] = $bg_color ? qwe_css_color($bg_color) : $DOLSTYLE['block_table_bg_color'];		
		
		$sContent = "";
		ob_start();

		?>

		<style type="text/css">

			<?if($sThemeType == 'myspace'):?>

			.topMenu td.top:hover a span.down, .topMenu td.top:hover b span.down {
				<?if(@$DOLSTYLE['top_menu_color_hover']):?>color:<?=$DOLSTYLE['top_menu_color_hover']?> !important;<?endif?> 
			}

			.topMenu td.top:hover {
				<?if(@$DOLSTYLE['top_menu_bg_color_hover']):?>background-color:<?=$DOLSTYLE['top_menu_bg_color_hover']?> !important;<?endif?>
			}

			.topMenu td#tm_active {
				<?if(@$DOLSTYLE['top_menu_bg_color_hover']):?>background-color:<?=$DOLSTYLE['top_menu_bg_color_hover']?> !important;<?endif?>
			}

			.topMenu a, 
			.topMenu a:link, 
			.topMenu a:visited, 
			.topMenu b {
				<?if(@$DOLSTYLE['top_menu_color']):?>color:<?=$DOLSTYLE['top_menu_color']?>;<?endif?> 
			}

			.topMenu td.top {
				border-left:<?=$oParser->Get('#topnav ul li', 'border-left')?>;
			}

			.topMenu td#tm_active a span.down,
			.topMenu td#tm_active b span.down { 
				<?if(@$DOLSTYLE['top_menu_color_hover']):?>color:<?=$DOLSTYLE['top_menu_color_hover']?>;<?endif?> 
			}

			<?=$oParser->GetSectionCss('html')?>
			<?=$oParser->GetSectionCss('body')?>
			.boxFirstHeader <?=$oParser->GetSectionStyle('h3.moduleHead', $myspace_exclude_props)?>
			
			<?
				$bhs = $oParser->css['h3.moduleHead']['background-image'] ? $oParser->css['h3.moduleHead']['background-image'] : $oParser->css['h3.moduleHead']['background'];
				$bhsc = qwe_css_extract_background_color($oParser->css['h3.moduleHead']);
				
				if(!preg_match('/url/', $bhs) && !$bhsc) {
			?>
				.boxFirstHeader {
					<?if(@$DOLSTYLE['block_head_bg_color']):?>background-color:<?=$DOLSTYLE['block_head_bg_color']?> !important;<?endif?>
				}
			<?
				}
			?>
			
			.boxFirstHeader .dbTitle, .boxFirstHeader {
				<?if(@$DOLSTYLE['block_title_color']):?>color:<?=$DOLSTYLE['block_title_color']?>;<?endif?>
			}
			
			table.topMenu <?=$oParser->GetSectionStyle('#topnav', $myspace_exclude_props)?>
			table.topMenu a.top_link <?=$oParserDefault->GetSectionStyle('#topnav a', $myspace_exclude_props)?>
			
			table.topMenu a.top_link:hover <?=$oParserDefault->GetSectionStyle('#topnav ul a:hover', $myspace_exclude_props)?>
			
			table.topMenu a.top_link:hover {
				<?if(@$DOLSTYLE['top_menu_color_hover']):?>color:<?=$DOLSTYLE['top_menu_color']?> !important;<?endif?>
			}

			table.topMenu a.top_link {
				<?if(@$DOLSTYLE['top_menu_color']):?>color:<?=$DOLSTYLE['top_menu_color']?> !important;<?endif?>
				<?if(@$DOLSTYLE['top_menu_bg_color']):?>background-color:<?=$DOLSTYLE['top_menu_bg_color']?> !important;<?endif?>				
			}

			.subMenu .subMenuContainer {
				<?if(@$DOLSTYLE['top_menu_bg_color']):?>background-color:<?=$DOLSTYLE['top_menu_bg_color']?> !important;<?endif?>
			}

			.subMenu table a.sublinks:hover, 
			.subMenu table a.sublinks:active { 
				<?if(@$DOLSTYLE['top_menu_color_hover']):?>color:<?=$DOLSTYLE['top_menu_color_hover']?>;<?endif?>
				<?if(@$DOLSTYLE['top_menu_bg_color']):?>background-color:<?=$DOLSTYLE['top_menu_bg_color']?> !important;<?endif?>
			}

			.subMenu table td.tabbed {
				background-color: <?=$DOLSTYLE['top_menu_bg_color_hover']?>;
				border-left: <?=$oParser->Get('#topnav ul li', 'border-left')?>;
			}

			.subMenu table td.usual {
				border-left: <?=$oParser->Get('#topnav ul li', 'border-left')?>;
			}

			.subMenu table td.usual a.sublinks {
				<?if(@$DOLSTYLE['top_menu_color']):?>color:<?=$DOLSTYLE['top_menu_color']?> !important;<?endif?>
			}

			.subMenu table td.usual a.sublinks:hover {
				<?if(@$DOLSTYLE['top_menu_bg_color_hover']):?>background-color:<?=$DOLSTYLE['top_menu_bg_color_hover']?> !important;<?endif?>
			}

			.subMenu table td.usual:hover {
				<?if(@$DOLSTYLE['top_menu_bg_color_hover']):?>background-color:<?=$DOLSTYLE['top_menu_bg_color_hover']?> !important;<?endif?>
			}

			.form_advanced_table td {
				<?if(@$DOLSTYLE['block_bg_color']):?>background-color:<?=$DOLSTYLE['block_bg_color']?>;<?endif?>
				<?if(@$DOLSTYLE['block_color']):?>color:<?=$DOLSTYLE['block_color']?>;<?endif?>
				<?if($oParser->GetRaw('div.detailsModule li', 'background-color')):?>background-color:<?=$oParser->GetRaw('div.detailsModule li', 'background-color');?>;<?endif?>
			}
			
			.breadcrumb {
				<?if(@$DOLSTYLE['top_bc_color']):?>color:<?=$DOLSTYLE['top_bc_color']?>;<?endif?>
				font-size:12px;
			}

			.breadcrumb a, .breadcrumb a:link, .breadcrumb a:visited, .breadcrumb a:hover, .breadcrumb a:active {
				<?if(@$DOLSTYLE['top_bc_color']):?>color:<?=$DOLSTYLE['top_bc_color']?>;<?endif?>
				font-size:12px;
				font-weight:bold;
			}

			.dbTopMenu div a, .dbTopMenu div a:link, .dbTopMenu div a:hover, .dbTopMenu div a:active, .dbTopMenu div a:visited {
				<?if(@$DOLSTYLE['top_menu_color']):?>color:<?=$DOLSTYLE['top_menu_color']?> !important;<?endif?>
			}

			.dbTopMenu .active span {
				<?if(@$DOLSTYLE['top_menu_color']):?>color:<?=$DOLSTYLE['top_menu_color']?> !important;<?endif?>
			}

			.subMenuOvr .subMenuInfoKeeper a {
				<?if(@$DOLSTYLE['top_menu_color']):?>color:<?=$DOLSTYLE['top_menu_color']?> !important;<?endif?>
			}
			<?endif?>

			div.sys_main_logo { background:none; }
			div.sys_top_menu { background:none; }

			.topMenu td.top:hover a span.down, .topMenu td.top:hover b span.down { 
				background:none; 
			}

			.topMenu td.top:hover a.top_link, 
			.topMenu td.top:hover a.top_link:link, 
			.topMenu td.top:hover a.top_link:visited, 
			.topMenu td.top:hover a.top_link:active
			{ background:none; }

			.topMenu td#tm_active a.top_link, 
			.topMenu td#tm_active a.top_link:link, 
			.topMenu td#tm_active a.top_link:visited, 
			.topMenu td#tm_active a.top_link:active	{
				background-image:none;
			}

			.topMenu td#tm_active {
				background-image:none;
			}

			.topMenu a, 
			.topMenu a:link, 
			.topMenu a:visited, 
			.topMenu b {
				background:none;
			}

			.topMenu td#tm_active a span.down,
			.topMenu td#tm_active b span.down { 
				background-image:none; 
			}

			.topMenu a span.down, 
			.topMenu b span.down { background: none; }

			table.topMenu {
				background:none;
			}

			table.topMenu td.top {
				border-right:none;
			}

			table.topMenu a.top_link {
				opacity:0.88;
				filter:alpha(opacity=88);
			}

			.subMenu .subMenuContainer {
				background-image:none;
				height:auto;
				opacity:0.88;
				filter:alpha(opacity=88);
			}

			.subMenu table td.tabbed {
				background-image:none;
			}

			.subMenu table td.usual {
				background-image:none;
			}

			.breadcrumb {
				background:none;
			}

			.breadcrumb div.bc_open, .breadcrumb div.bc_close {
				display:none;
			}

			.breadcrumb img.bc_divider {
				height:10px;
				margin-top:6px;
			}

			.breadcrumb a, .breadcrumb a:link, .breadcrumb a:visited, .breadcrumb a:hover, .breadcrumb a:active {
				text-decoration:underline;
			}

			.boxContent {
				opacity:0.95;
				filter:alpha(opacity=95);
			}

			.notify_message, .notify_message .notify_wrapper_close {
				opacity:0.91;
				filter:alpha(opacity=91);
			}

			div.main_footer_block, .bottomCopyright {
				opacity:0.91;
				filter:alpha(opacity=91);
			}

			.dbTopMenu div a, .dbTopMenu div a:link, .dbTopMenu div a:hover, .dbTopMenu div a:active, .dbTopMenu div a:visited {
				text-decoration:underline;
			}

			.dbTopMenu div.active {
				background:none;
			}

			.dbTopMenu .active span {
				background:none;
				font-weight:bold;
			}

			.subMenu.subMenuContainerEmpty {
				display:none;
			}
			
			.boxContent {
				<?if(@$DOLSTYLE['block_bg_color']):?>background-color:<?=$DOLSTYLE['block_bg_color']?>;<?endif?>
				<?if(@$DOLSTYLE['block_color']):?>color:<?=$DOLSTYLE['block_color']?>;<?endif?>
			}
			
			.boxContent a, .boxContent a:link, .boxContent a:hover, .boxContent a:visited, .boxContent a:active {
				<?if(@$DOLSTYLE['block_link_color']):?>color:<?=$DOLSTYLE['block_link_color']?>;<?endif?>
				text-decoration:underline;
			}			
			
			.disignBoxFirst {
				<?if(@$DOLSTYLE['block_border_color']):?>border-color:<?=$DOLSTYLE['block_border_color']?>;<?endif?>
				<?if(@$DOLSTYLE['block_border_style']):?>border-style:<?=$DOLSTYLE['block_border_style']?>;<?endif?>
				<?if(@$DOLSTYLE['block_border_width']):?>border-width:<?=$DOLSTYLE['block_border_width']?>px;<?endif?>
			}
			
			a, a:link, a:hover, a:visited, a:active {
				<?if(@$DOLSTYLE['link_color']):?>color:<?=$DOLSTYLE['link_color']?>;<?endif?>
				text-decoration:underline;
			}
			
			.form_advanced_table {
				<?if(@$DOLSTYLE['block_table_border_color']):?>
					border-top:1px solid <?=$DOLSTYLE['block_table_border_color']?>;
					border-left:1px solid <?=$DOLSTYLE['block_table_border_color']?>;
					border-right:1px solid <?=$DOLSTYLE['block_table_border_color']?>;
				<?endif?>
				<?if(@$DOLSTYLE['block_border_width']):?>border-width:<?=$DOLSTYLE['block_border_width']?>px;<?endif?>
			}

			.form_advanced_table td, .form_advanced_table th, .form_advanced_table th.block_header  {
				<?if(@$DOLSTYLE['block_table_bg_color']):?>background-color:<?=$DOLSTYLE['block_table_bg_color']?> !important;<?endif?>
				<?if(@$DOLSTYLE['block_table_color']):?>color:<?=$DOLSTYLE['block_table_color']?> !important;<?endif?>
				<?if(@$DOLSTYLE['block_table_border_color']):?>border-bottom:1px solid <?=$DOLSTYLE['block_table_border_color']?> !important;<?endif?>
				<?if(@$DOLSTYLE['block_border_width']):?>border-width:<?=$DOLSTYLE['block_border_width']?>px !important;<?endif?>
			}
			
			.qwe_profile_theme_gallery_item {
				<?if(@$DOLSTYLE['block_table_bg_color']):?>background-color:<?=$DOLSTYLE['block_table_bg_color']?> !important;<?endif?>
				<?if(@$DOLSTYLE['block_table_color']):?>color:<?=$DOLSTYLE['block_table_color']?> !important;<?endif?>
				<?if(@$DOLSTYLE['block_table_border_color']):?>border:1px solid <?=$DOLSTYLE['block_table_border_color']?> !important;<?endif?>
				<?if(@$DOLSTYLE['block_border_width']):?>border-width:<?=$DOLSTYLE['block_border_width']?>px !important;<?endif?>
			}
			
			.pollQuestionBlock {
				color:#333333;
			}
			
			.sys_page_header, 
			.sys_page_header a,  
			.sys_page_header a:link,
			.sys_page_header a:hover,
			.sys_page_header a:visited,
			.sys_page_header a:active {
				<?if(@$DOLSTYLE['quote_color']):?>color:<?=$DOLSTYLE['quote_color']?>;<?endif?>
			}
			
			.bottomCopyright {
				<?if(@$DOLSTYLE['block_bg_color']):?>background-color:<?=$DOLSTYLE['block_bg_color']?>;<?endif?>
				<?if(@$DOLSTYLE['block_color']):?>color:<?=$DOLSTYLE['block_color']?>;<?endif?>
			}
			
			.bottomCopyright .bottomCpr {
				<?if(@$DOLSTYLE['block_color']):?>color:<?=$DOLSTYLE['block_color']?>;<?endif?>
			}
			
			div.main_footer_block {
				background-image: none !important;
				<?if(@$DOLSTYLE['block_bg_color']):?>background-color:<?=$DOLSTYLE['block_bg_color']?>;<?endif?>
			}
			
			div.main_footer_block .powered_section {
				<?if(@$DOLSTYLE['block_color']):?>color:<?=$DOLSTYLE['block_color']?>;<?endif?>
			}
			
			div.main_footer_block .powered_section a, 
			div.main_footer_block .powered_section a:link, 
			div.main_footer_block .powered_section a:visited,
			div.main_footer_block .powered_section a:active,
			div.main_footer_block .license_section a:hover {
				<?if(@$DOLSTYLE['block_link_color']):?>color:<?=$DOLSTYLE['block_link_color']?>;<?endif?>
				text-decoration:underline;
			}

			div.main_footer_block .license_section {
				<?if(@$DOLSTYLE['block_color']):?>color:<?=$DOLSTYLE['block_color']?>;<?endif?>
			}

			div.main_footer_block .license_section a, 
			div.main_footer_block .license_section a:link, 
			div.main_footer_block .license_section a:visited, 
			div.main_footer_block .license_section a:active, 
			div.main_footer_block .license_section a:hover {
				<?if(@$DOLSTYLE['block_link_color']):?>color:<?=$DOLSTYLE['block_link_color']?>;<?endif?>			
				text-decoration:underline;
			}
			
			div.wall-divider {
				background-color:transparent !important;
				border-width:1px;
				<?if(@$DOLSTYLE['block_border_color']):?>border-color:<?=$DOLSTYLE['block_border_color']?>;<?endif?>
				font-weight:bold;
			}
			
			div.wall-divider-today {
				font-weight:bold;
				background-color:transparent !important;
				border-width:1px;
				<?if(@$DOLSTYLE['block_border_color']):?>border-color:<?=$DOLSTYLE['block_border_color']?>;<?endif?>
			}		
			
			.tabbed div {
				background-image:none !important;
				<?if(@$DOLSTYLE['top_menu_color']):?>color:<?=$DOLSTYLE['top_menu_color']?> !important;<?endif?>
			}
			
			.subMenuContainerEmpty {
				background: none !important;
			}
			
			.sys_file_search_when {
				<?if(@$DOLSTYLE['block_color']):?>color:<?=$DOLSTYLE['block_color']?>;<?endif?>
			}
			
			div.owner_info .date_add {
				<?if(@$DOLSTYLE['block_color']):?>color:<?=$DOLSTYLE['block_color']?> !important;<?endif?>
			}
			
			.owner_info {
				<?if(@$DOLSTYLE['block_color']):?>color:<?=$DOLSTYLE['block_color']?> !important;<?endif?>
			}

			div.owner_info a.small, 
			div.owner_info a.small:link, 
			div.owner_info a.small:active, 
			div.owner_info a.small:hover, 
			div.owner_info a.small:visited {
				<?if(@$DOLSTYLE['block_link_color']):?>color:<?=$DOLSTYLE['block_link_color']?> !important;<?endif?>
				text-decoration:underline;
			} 
			
			.ads_When {
				<?if(@$DOLSTYLE['block_color']):?>color:<?=$DOLSTYLE['block_color']?> !important;<?endif?>
			}
			
			.quote_div {
				<?if(@$DOLSTYLE['quote_color']):?>color:<?=$DOLSTYLE['quote_color']?> !important;<?endif?>
			}
			
			.quote_div .author {
				<?if(@$DOLSTYLE['quote_color']):?>color:<?=$DOLSTYLE['quote_color']?> !important;<?endif?>
			}
			
			<?if($sThemeType != 'myspace'):?>
				<?=$sCurrentCss?>
			<?endif?>			

		</style>

		<?

		$sContent = ob_get_clean();
		return "\n\n\n<!-- Gorpus Profile Theme Begin -->\n\n\n".$sContent."\n\n\n<!-- Gorpus Profile Theme End -->\n\n\n";
	}

}

function qwe_css_color($c)
{
	if(preg_match_all('/rgb\(\s*(\d+\s*,\s*\d+\s*,\s*\d+)\s*\)/', $c, $matches)) {		
		$c = split(',', @$matches[1][0]);
		$c = '#'.qwe_css_color_channel(dechex(trim($c[0]))).qwe_css_color_channel(dechex(trim($c[1]))).qwe_css_color_channel(dechex(trim($c[2])));
		return $c;		
	}
	elseif(preg_match_all('/#(\w+)/', $c, $matches)) {
		$c = @$matches[1][0];
		if(strlen($c) < 6) {
			$c = $c[0] . $c[0] . $c[1] . $c[1] . $c[2] . $c[2];
		}
		
		return '#'.$c;
	}
	else {
		return $c;
	}
}

function qwe_match($regexp, $subject)
{
	if(preg_match_all($regexp, $subject, $matches)) {
		return isset($matches[1][0]) ? $matches[1][0] : FALSE;
	}
	return FALSE;
}

function qwe_color_dark($color, $percent)
{
	$color = str_replace('#', '', $color);
	
	$r = hexdec(substr($color,0,2));
	$g = hexdec(substr($color,2,2));
	$b = hexdec(substr($color,4,2));
	
	$_r = qwe_tune_color($r, $percent, 0);
	$_g = qwe_tune_color($g, $percent, 0);
	$_b = qwe_tune_color($b, $percent, 0);
	
	return '#'. qwe_css_color_channel(dechex($_r)). qwe_css_color_channel(dechex($_g)). qwe_css_color_channel(dechex($_b));
}

function qwe_color_light($color, $percent)
{
	$color = str_replace('#', '', $color);
	
	$r = hexdec(substr($color,0,2));
	$g = hexdec(substr($color,2,2));
	$b = hexdec(substr($color,4,2));
	
	$_r = qwe_tune_color($r, $percent, 1);
	$_g = qwe_tune_color($g, $percent, 1);
	$_b = qwe_tune_color($b, $percent, 1);
	
	return '#'. qwe_css_color_channel(dechex($_r)). qwe_css_color_channel(dechex($_g)). qwe_css_color_channel(dechex($_b));
}

function qwe_color_inverse($color)
{
	$color = str_replace('#', '', $color);
	
	$r = hexdec(substr($color,0,2));
	$g = hexdec(substr($color,2,2));
	$b = hexdec(substr($color,4,2));
	
	$_r = 255 - $r;
	$_g = 255 - $g;
	$_b = 255 - $b;
	
	return '#'. qwe_css_color_channel(dechex($_r)). qwe_css_color_channel(dechex($_g)). qwe_css_color_channel(dechex($_b));
}

function qwe_tune_color($path, $percent, $inc)
{
	if($inc) {
		$raz = 255 - $path;  if ($raz < 0){ $raz=0; }	
		$point = $raz/100;
		$path += floor($point * $percent);
	}
	else {
		$point = $path/100;
		$path -= floor($point * $percent);
	}
	
	if($path < 16) {
		$path = 16;
	}
	
	if($path > 255) {
		$path = 255;
	}
	
	return $path;
}

function qwe_css_extract_background_color($body_style)
{
	$body_background = '';
	if($body_style) {
		$body_background = $body_style['background'];
		if(!$body_background) {
			$body_background = $body_style['background-color'];
		}
	}
	
	$bg_color = '';
	if($body_background && preg_match_all('/#(\w+)/', $body_background, $matches)) {
		$bg_color = qwe_css_color(@$matches[1][0]);
	}
	elseif($body_background && preg_match_all('/rgb\(\s*(\d+\s*,\s*\d+\s*,\s*\d+)\s*\)/', $body_background, $matches)) {
		$bg_color = split(',', @$matches[1][0]);
		$bg_color = '#'.qwe_css_color_channel(dechex(trim($bg_color[0]))).qwe_css_color_channel(dechex(trim($bg_color[1]))).qwe_css_color_channel(dechex(trim($bg_color[2])));
	}
	
	return $bg_color;
}

function qwe_is_colors_same($color1, $color2)
{
	$color1 = str_replace('#', '', $color1);
	$color2 = str_replace('#', '', $color2);
	
	$r1 = hexdec(substr($color1,0,2));
	$g1 = hexdec(substr($color1,2,2));
	$b1 = hexdec(substr($color1,4,2));
	
	$r2 = hexdec(substr($color2,0,2));
	$g2 = hexdec(substr($color2,2,2));
	$b2 = hexdec(substr($color2,4,2));
	
	$e = 40;
	
	return abs($r1 - $r2) < $e && abs($g1 - $g2) < $e && abs($b1 - $b2) < $e;
}

function qwe_small_difference_color($color)
{
	return qwe_make_different_color($color, 20);
}

function qwe_huge_difference_color($color)
{
	return qwe_make_different_color($color, 80);
}

function qwe_make_different_color($color, $dif)	#	$dif is percentage
{
	$color = str_replace('#', '', $color);
	
	$r = hexdec(substr($color,0,2));
	$g = hexdec(substr($color,2,2));
	$b = hexdec(substr($color,4,2));
	
	if($r + $g + $b > 255 * 3 / 2) {
		return qwe_color_dark($color, $dif);
	}
	else {
		return qwe_color_light($color, $dif);
	}
}

function qwe_css_color_channel($code)
{
	if(!strlen($code)) {
		return '00';
	}
	elseif(strlen($code) == 1) {
		return '0'.$code;
	}
	else {
		return substr($code, 0, 2);
	}
}

?>
