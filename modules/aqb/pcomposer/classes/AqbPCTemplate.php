<?php
/***************************************************************************
* 
*     copyright            : (C) 2009 AQB Soft
*     website              : http://www.aqbsoft.com
*      
* IMPORTANT: This is a commercial product made by AQB Soft. It cannot be modified for other than personal usage.
* The "personal usage" means the product can be installed and set up for ONE domain name ONLY. 
* To be able to use this product for another domain names you have to order another copy of this product (license).
* 
* This product cannot be redistributed for free or a fee without written permission from AQB Soft.
* 
* This notice may not be removed from the source code.
* 
***************************************************************************/

bx_import('BxTemplFormView');
bx_import('BxTemplSearchResult');
bx_import('BxTemplSearchProfile');
bx_import('BxDolTwigTemplate');
bx_import('BxDolParams');
bx_import('BxTemplProfileView');
bx_import('BxBaseProfileView');

require_once('AqbPCPageViewAdmin.php');

define('BX_DOL_ADM_MP_JS_NAME', 'Profile');
define('BX_DOL_ADM_MP_PER_PAGE', 32);
define('BX_DOL_ADM_MP_PER_PAGE_STEP', 16);

class AqbPCTemplate extends BxDolTwigTemplate {
   var $_ActiveMenuLink = '', $oSearchProfileTmpl;
	
	/**
	 * Constructor
	 */
	
	function AqbPCTemplate(&$oConfig, &$oDb) {
	    parent::BxDolTwigTemplate($oConfig, $oDb);
	    $this -> oSearchProfileTmpl = new BxTemplSearchProfile();
	}
	
	function addAdminCss ($sName) 
    {
    parent::addAdminCss($sName);
    }
	
    function parseHtmlByName ($sName, $aVars) {        
        return parent::parseHtmlByName ($sName.'.html', $aVars);
    }
    
    function genWrapperInput($aInput, $sContent) {
       $oForm = new BxTemplFormView(array());
       
       $sAttr = isset($aInput['attrs_wrapper']) && is_array($aInput['attrs_wrapper']) ? $oForm -> convertArray2Attrs($aInput['attrs_wrapper']) : '';
       switch ($aInput['type']) {
            case 'textarea':
                $sCode = <<<BLAH
                        <div class="input_wrapper input_wrapper_{$aInput['type']}" $sAttr>
                            <div class="input_border">
                                $sContent
                            </div>
                            <div class="input_close_{$aInput['type']} left top"></div>
                            <div class="input_close_{$aInput['type']} left bottom"></div>
                            <div class="input_close_{$aInput['type']} right top"></div>
                            <div class="input_close_{$aInput['type']} right bottom"></div>
                        </div>
BLAH;
            break;
            
            default:
                $sCode = <<<BLAH
                        <div class="input_wrapper input_wrapper_{$aInput['type']}" $sAttr>
                            $sContent
                            <div class="input_close input_close_{$aInput['type']}"></div>
                        </div>
BLAH;
        }
        
        return $sCode;
    }
    function getBlocksPanel(){
	    $aButtons = array(
	    	'aqb-pc-delete' => _t('_aqb_pc_block_delete'),
			'aqb-pc-approve' => _t('_aqb_pc_block_approve')
	    );    
    
		$sControls = BxTemplSearchResult::showAdminActionsPanel('pc-blocks-form', $aButtons, 'blocks');
	       
	    $oPaginate = new BxDolPaginate(array(
	        'per_page' => BX_DOL_ADM_MP_PER_PAGE,
	        'per_page_step' => BX_DOL_ADM_MP_PER_PAGE_STEP,
	        'on_change_per_page' => BX_DOL_ADM_MP_JS_NAME . '.changePerPage(this);'
	    ));    

		$sAllBlocks = _t('_aqb_pc_all_blocks', $this -> _oDb -> showBlocksCount());
		$sApprovedBlocks = _t('_aqb_pc_approved_blocks', $this -> _oDb -> showBlocksCount('1'));
		$sNotApprovedBlocks = _t('_aqb_pc_not_approved_blocks', $this -> _oDb -> showBlocksCount('0'));
	    
		$aResult = array(
	        'per_page' => $oPaginate->getPages(),
	        'control' => $sControls,	    
			'all' => '<span><a href="javascript:void(0);" onclick="javascript:Profile.showAll(\'\');">'.$sAllBlocks.'</a></span>',
			'not_approved' => '<span><a href="javascript:void(0);" onclick="javascript:Profile.showAll(\'1\');">'.$sApprovedBlocks.'</a></span>', 
			'approved' => '<span><a href="javascript:void(0);" onclick="javascript:Profile.showAll(\'0\');">'.$sNotApprovedBlocks.'</a></span>' 
		);

	    $aResult = array_merge($aResult, array('style_common' => '', 'content_common' => $this -> getBlocksTable()));
			
		return $this -> parseHtmlByName('admin_main', 
		array(
					'search' => $this -> getBlockSearch(), 
					'blocks' =>$this  -> parseHtmlByName('blocks', $aResult),
				    'obj_name' => BX_DOL_ADM_MP_JS_NAME,
					'actions_url' => BX_DOL_URL_ROOT . $this -> _oConfig -> getBaseUri(),
				    'per_page' => '32',
				    'order_by' => '',
					'loading' => LoadingBox('pc-blocks-loading')			
			));
	}
		

	function getBlocksTable($aParams = array()) {
		if (empty($aParams['view_type'])) $aParams['view_type'] = 'DESC';
		if(!isset($aParams['view_start']) || empty($aParams['view_start'])) $aParams['view_start'] = 0;
	    if(!isset($aParams['view_per_page']) || empty($aParams['view_per_page'])) $aParams['view_per_page'] = BX_DOL_ADM_MP_PER_PAGE;
		
	    $aParams['view_order_way'] = $aParams['view_type'];
	    
	    if(!isset($aParams['view_order']) || empty($aParams['view_order'])) $aParams['view_order'] = 'date';
	    
		$aBlocks = $this -> _oDb -> getBlocks($aParams);
	   	
	    $sBaseUrl = BX_DOL_URL_ROOT . $this -> _oConfig -> getBaseUri();
				
	    $aItems = array();

	    foreach($aBlocks as $aBlock){

			$aItems[$aBlock['id']] = array(
	            'id' => $aBlock['id'],
	            'username' => $aBlock['username'],
	            'block_title' => strlen($aBlock['title']) > 20 ? substr($aBlock['title'],0,20) . '...' : $aBlock['title'],    
				'edit_block' => _t('_aqb_pc_admin_edit'), 
				'edit_block_link' => "javascript:AqbPCMain.showPopup('{$sBaseUrl}edit_block_form/" . $this -> _oConfig -> getCBPrefix() . "{$aBlock['id']}');",
				'view_block' => _t('_aqb_pc_admin_view'), 
				'view_block_link' => "javascript:AqbPCMain.showPopup('{$sBaseUrl}preview_block_form/{$aBlock['id']}');",
				'last_login' => $aBlock['last_login'],    
				'block_type' => $aBlock['type'],    
				'block_created' => $aBlock['date'],    
				'block_approved' => (int)$aBlock['approved'] ? _t('_aqb_pc_admin_yes') : _t('_aqb_pc_admin_no'),
				'block_shared' => (int)$aBlock['shared'] ? _t('_aqb_pc_admin_yes') : _t('_aqb_pc_admin_no'),			
		    );
	    }
	  
		//--- Get Paginate ---//
	    $oPaginate = new BxDolPaginate(array(
	        'start' => $aParams['view_start'],
	        'count' => $this -> _oDb -> _iMembersCount,
	        'per_page' => $aParams['view_per_page'],
	        'page_url' => BX_DOL_URL_ROOT . $this -> _oConfig -> getBaseUri() . '?start={start}',
	        'on_change_page' => BX_DOL_ADM_MP_JS_NAME . '.changePage({start})'
	    ));
	    
		$sPaginate = $oPaginate -> getPaginate(); 
		    
	    return $this -> parseHtmlByName('blocks_table', array(
	        'bx_repeat:items' => array_values($aItems),
			'username_function' => $this -> getOrderParpoints('NickName', $aParams),
			'username_arrow' => $this -> getArrowImage('NickName', $aParams),
			'block_id_function'=> $this -> getOrderParpoints('id', $aParams),
			'block_id_arrow'   => $this -> getArrowImage('id', $aParams),
	     	'block_title_function'=> $this -> getOrderParpoints('title', $aParams),
			'block_title_arrow'   => $this -> getArrowImage('title', $aParams),
		    'block_type_function' => $this -> getOrderParpoints('type', $aParams),
			'block_type_arrow'    => $this -> getArrowImage('type', $aParams),
			'block_created_function'=> $this -> getOrderParpoints('date', $aParams),
			'block_date_arrow'   => $this -> getArrowImage('date', $aParams),
			'block_approved_function'=> $this -> getOrderParpoints('approved', $aParams),
			'block_approved_arrow'   => $this -> getArrowImage('approved', $aParams),
			'block_shared_function'=> $this -> getOrderParpoints('share', $aParams),
			'block_shared_arrow'   => $this -> getArrowImage('share', $aParams),
			'paginate' => $sPaginate
	    ));                                                                                                             
	
	}
 
	function getBlockContent($iBlockId){
	  $aBlock = $this -> _oDb -> getBlockInfo($iBlockId);
	  $aBlockInfo['params'] = $this -> _oDb -> getCustomBlockInfo($iBlockId, array());
	  $aBlockInfo['id'] = $this -> _oConfig -> getCBPrefix() . $iBlockId;
	  
	  $oBlockBuilder = new AqbPCPageView('profile');
	  $oBlockBuilder -> initView(true);
	  $oBlockBuilder -> sCode = '';
	  $aBlockInfo['params']['Content'] = "<center>{$aBlockInfo['params']['Content']}</center>";
	  $oBlockBuilder -> genBlock($aBlockInfo['id'], $aBlockInfo['params']);
		
	  header('Content-Type:text/html;charset=UTF-8');
	  return $oBlockBuilder -> sCode; 
	}
	
    function getBlockPreview($iBlockId){
	  $aBlock = $this -> _oDb -> getBlockInfo($iBlockId);
	  $aProfileInfo = getProfileInfo($aBlock['owner_id']); 
	  $sUri = $this -> _oConfig -> getHomeUrl() . 'get_block_content.php?id='.$iBlockId;

	  return PopupBox('aqb_popup', _t('_aqb_pc_owner_nickname', $aProfileInfo['NickName']), $this -> parseHtmlByName('iframe_block_content', array('URL' => $sUri)));
	}
	
	function getAdvancedBlockForm($aBlock){
		$aForm = array(
     	'form_attrs' => array(
            'id' => 'pc-html-form',
			'name' => 'pc-html-form',
            'method' => 'post',
		    'enctype' => 'multipart/form-data',
			'action' => '',
    		'onsubmit' => "javascript: AqbPC.submitBlock('html','"._t('_aqb_pc_empty_fields')."'); return false;"
        ),
		
		'inputs' => array(
        
		'title' => array(
                'type' => 'text',
                'name' => 'title',
                'value' => $aBlock['title'],
				'required' => true,
       			'caption' => _t('_aqb_pc_block_title_edit'),
				'info' => _t('_aqb_pc_block_title_edit_info'),
       		    'attrs' => array('id' => 'aqb_pc_html_block_title'),
    	),
		
		'body' => array(
                'type' => 'textarea',
                'name' => 'body',
                'value' => $aBlock['content'],
				'html' => '0',
				'required' => true,
       			'caption' => _t('_aqb_pc_block_body_edit'),
				'info' => _t('_aqb_pc_advanced_block_body_edit_info'),
       		    'attrs' => array('id' => 'aqb_pc_html_block_body','style' => 'width:350px;height:250px;'),
				'attrs_wrapper' => array('style' => 'width:350px;height:250px;')
    	),
        
		'save' => array(
                    'type' => 'submit',
                    'name' => 'aqb_pc_html_save',
                    'value' => _t('_aqb_pc_save_button'),
        			'attrs' => array('id' => 'aqb_pc_html_save')
    	)));
    
	if (!empty($aBlock)) 
	{
		$aForm['inputs']['hidden'] = array(
											'type' => 'hidden',
							                'value' => (int)$aBlock['id'],
							       		    'name' => 'block_id',
											'attrs' => array('id' => 'block_id')
										  );
	
	}
	
	$oForm = new BxTemplFormView($aForm);
	$aVars = array (
            'form' => $oForm -> getCode(),
			'tab_id' => 'html_tab'
	  );
	  
	   return  $this -> parseHtmlByName('tabs', $aVars);
	}

	function getTextBlockForm($aBlock){
	   $aForm = array(
     	'form_attrs' => array(
            'id' => 'pc-text-form',
			'name' => 'pc-text-form',
            'method' => 'post',
		    'enctype' => 'multipart/form-data',
			'action' => '',
    		'onsubmit' => "javascript: AqbPC.submitBlock('text','"._t('_aqb_pc_empty_fields')."'); return false;"
        ),
	
        'inputs' => array(
        
		'title' => array(
                'type' => 'text',
                'name' => 'title',
                'value' => $aBlock['title'],
				'required' => true,
       			'caption' => _t('_aqb_pc_block_title_edit'),
				'info' => _t('_aqb_pc_block_title_edit_info'),
       		    'attrs' => array('id' => 'aqb_pc_text_block_title'),
    	),
		
		'body' => array(
                'type' => 'textarea',
                'name' => 'body',
                'value' => $aBlock['content'],
				'html' => 2,
				'required' => true,
       			'caption' => _t('_aqb_pc_block_body_edit'),
				'info' => _t('_aqb_pc_text_block_body_edit_info', $this -> _oConfig -> getMaxCharsForBody()),
       		    'attrs' => array('id' => 'aqb_text_block_body','style' => 'width:630px;height:250px;'),
				'attrs_wrapper' => array('style' => 'height:250px;width:630px;')
    	),
        
		'save' => array(
                    'type' => 'submit',
                    'name' => 'aqb_pc_text_save',
                    'value' => _t('_aqb_pc_save_button'),
        			'attrs' => array('id' => 'aqb_pc_text_save')
    	)));
    
	if (!empty($aBlock)) 
	{
		$aForm['inputs']['hidden'] = array(
											'type' => 'hidden',
							                'value' => (int)$aBlock['id'],
							       		    'name' => 'block_id',
											'attrs' => array('id' => 'block_id')
										  );
	
	}
	
	$oForm = new BxTemplFormView($aForm);
	$aVars = array (
            'form' => $oForm -> getCode(),
			'tab_id' => 'text_tab'
	  );
	  
	   return  $this -> parseHtmlByName('tabs', $aVars);	
	}

	function getRssBlockForm($aBlock){
	   $aForm = array(
     	'form_attrs' => array(
            'id' => 'pc-rss-form',
			'name' => 'pc-rss-form',
            'method' => 'post',
		    'enctype' => 'multipart/form-data',
			'action' => '',
    		'onsubmit' => "javascript: AqbPC.submitBlock('rss','"._t('_aqb_pc_empty_fields')."'); return false;"
        ),
		
		'inputs' => array(
        
		'title' => array(
                'type' => 'text',
                'name' => 'title',
                'value' => $aBlock['title'],
				'required' => true,
       			'caption' => _t('_aqb_pc_block_title_edit'),
				'info' => _t('_aqb_pc_block_title_edit_info'),
       		    'attrs' => array('id' => 'aqb_pc_rss_block_title'),
    	),
		
		'body' => array(
                'type' => 'text',
                'name' => 'body',
                'value' => $aBlock['content'],
				'required' => true,
       			'caption' => _t('_aqb_pc_block_body_edit'),
				'info' => _t('_aqb_pc_rss_block_body_edit_info'),
       		    'attrs' => array('id' => 'aqb_pc_rss_block_body')
    	),
        
		'save' => array(
                    'type' => 'submit',
                    'name' => 'aqb_pc_rss_save',
                    'value' => _t('_aqb_pc_save_button'),
        			'attrs' => array('id' => 'aqb_pc_rss_save')
    	)));
    
	if (!empty($aBlock)) 
	{
		$aForm['inputs']['hidden'] = array(
											'type' => 'hidden',
							                'value' => (int)$aBlock['id'],
							       		    'name' => 'block_id',
											'attrs' => array('id' => 'block_id')
										  );
	
	}
	
	$oForm = new BxTemplFormView($aForm);
	$aVars = array (
            'form' => $oForm -> getCode(),
			'tab_id' => 'rss_tab'
	  );
	  
	   return  $this -> parseHtmlByName('tabs', $aVars);	
	}
	
	function getCreateBlockForm($iProfileID, $aBlockInfo = array()){
		if (!$this -> _oConfig -> isCreateBlocksAllowed()) return MsgBox(_t('_aqb_create_blocks_is_not_allowed'));
		
		$aProfileInfo = getProfileInfo($iProfileID); 
		
	//	$sScriptUrl = $this -> _oConfig -> getHomeUrl() . 'js/tabs.js';
		$sWrongPointsMessage = addslashes(_t('_aqb_points_wrong_present_points'));
		$sConfirm = addslashes(_t('_aqb_points_confirm_present', '{0}'));
   	
	//	 $sUrlQuery = BX_DOL_URL_PLUGINS . 'jquery/ui.tabs.js';
	
	    $sTitle = '';
	   
	   if ($this -> _oConfig -> isHTMLBlockAllowed())
	   {
		 if (!empty($aBlockInfo) && $aBlockInfo['type'] == 'html') 
		 {
			$sAdvBlock = $this -> getAdvancedBlockForm($aBlockInfo);
    		$sTitle .= '<li><a href="#html_tab">'._t('_aqb_pc_html_block').'</a></li>';
		 }	
		 elseif (empty($aBlockInfo)) 
		 {	
			$sAdvBlock = $this -> getAdvancedBlockForm(array());
			$sTitle .= '<li><a href="#html_tab">'._t('_aqb_pc_html_block').'</a></li>';
		 }	
		 
	   }
	  
	  if ($this -> _oConfig -> isTextBlockAllowed())
	   {
	     if (!empty($aBlockInfo) && $aBlockInfo['type'] == 'text') 
		 {
			$sTextBlock = $this -> getTextBlockForm($aBlockInfo);
    		$sTitle .= '<li><a href="#text_tab">'._t('_aqb_pc_text_block').'</a></li>';
		 }	
		 elseif (empty($aBlockInfo)) 
		 {	
			$sTextBlock = $this -> getTextBlockForm(array());
			$sTitle .= '<li><a href="#text_tab">'._t('_aqb_pc_text_block').'</a></li>';
		 }	
	 }
		
	/*  if ($this -> _oConfig -> isRssBlockAllowed())
	   {
		 if (!empty($aBlockInfo) && $aBlockInfo['type'] == 'rss') 
		 {
			$sRssBlock = $this -> getRssBlockForm($aBlockInfo);
    		$sTitle .= '<li><a href="#rss_tab">'._t('_aqb_pc_rss_block').'</a></li>';
		 }	
		 elseif (empty($aBlockInfo)) 
		 {	
			$sRssBlock = $this -> getRssBlockForm(array());
			$sTitle .= '<li><a href="#rss_tab">'._t('_aqb_pc_rss_block').'</a></li>';
		 }	
	   }	   
	*/
	  if ($sTitle) $sTabsTitles ="<ul>{$sTitle}</ul>"; 
             
      return PopupBox('aqb_popup', _t('_aqb_pc_add_new_block'), $this -> parseHtmlByName('new_block_form', array('titles' => $sTabsTitles, 'content' => $sAdvBlock . $sTextBlock . $sRssBlock)));
 // return PopupBox('aqb_popup', _t('_aqb_pc_add_new_block'), $this -> parseHtmlByName('new_block_form', array('titles' => $sTabsTitles, 'content' => $sAdvBlock . $sTextBlock . $sRssBlock, 'jquery_tab' => $sUrlQuery, 'js_script' => $sScriptUrl)));


    }
	
	function getDefaultPage(){
	   $oPVAdm = new AqbPCPageViewAdmin( 'sys_page_compose', 'sys_page_compose.inc', $this);
	   return $oPVAdm -> sPageBulder;	
	}
	
	function getBlockSettingsPanel($iBlockId = 0, $bPopUp = true, $sType = 'c', $iPos = 0){
		$aBlock = $this -> _oDb -> getBlockInfo($iBlockId,$sType);
		
		$oForm = new BxTemplFormView(array());
		$aCheckBox = array('type' => 'checkbox', 'name' => 'unmovable' , 'checked' => (int)$aBlock['unmovable'] == 1);
		$sCheckBox = $this -> genWrapperInput($aCheckBox, $oForm -> genInput($aCheckBox));
		//$sFixPosElement = $sCheckBox  . '<br/>' . (!(int)$iPos ? $this -> getPosElement($aBlock) : '');
		
		$aSaveButton = array(
		                    'type' => 'submit',
		                    'name' => 'save_block',
		                    'value' => _t('_aqb_pc_save_block'),
		        			'attrs' => array('id' => 'save_block'),
							'attrs_wrapper' => array('style' => 'margin-left:25%;')
						);
		$sSaveButton = $this -> genWrapperInput($aSaveButton, $oForm -> genInput($aSaveButton));
			
		$oPrivacy = new BxDolPrivacy('sys_page_compose_privacy', 'id', 'user_id');
	    $aSelect = $oPrivacy -> getGroupChooser('', 'profile', 'view_block');
		
		$sBaseUrl = BX_DOL_URL_ROOT . $this -> _oConfig -> getBaseUri() . "edit_block_form/";
		
		if ((int)$iBlockId && $sType == 'c')
			 $sBaseUrl .= $this -> _oConfig -> getCBPrefix() . $iBlockId;
		else 
			 $sBaseUrl .= $iBlockId;
			
		
		$aAttrsValues = array();
		$aAttrsValue = array('irremovable' => _t('_aqb_block_attrs_irremovable'),'uncollapsable' => _t('_aqb_block_attrs_collapsible'), 'collapsable_def' => _t('_aqb_block_attrs_collapsable_def'));
		
		if ($sType == 'c')
		$aAttrsValue['share'] = _t('_aqb_block_attrs_share');
		
		if (!$bPopUp) $sReload = BX_DOL_URL_ROOT . $this -> _oConfig -> getBaseUri() . 'administration/create';
		
		if ((int)$iBlockId) 
		{
			if ((int)$aBlock['share'] && $sType == 'c') $aAttrsValues[] = 'share';
			if ((int)$aBlock['irremovable']) $aAttrsValues[] = 'irremovable';			
			if ((int)$aBlock['uncollapsable']) $aAttrsValues[] = 'uncollapsable';
			if ((int)$aBlock['collapsed_def']) $aAttrsValues[] = 'collapsable_def';
			
			$aSelect['value'] = $aBlock['visible_group'];
		} else $aAttrsValues[] = 'share';	
		
		if ($sType == 'c')
			$sSubmitFunction = "javascript: Profile.onSubmitBlock(this,'".addslashes(_t('_aqb_pc_successfully_saved'))."','".addslashes(_t('_aqb_pc_error_title', $this -> _oConfig -> getMaxCharsForTitle()))."','".addslashes(_t('_aqb_pc_error_body', $this -> _oConfig -> getMaxCharsForBody()))."','{$sReload}'); return false;";
		else
			$sSubmitFunction = "javascript: Profile.onSubmitStandardBlock(this,'".addslashes(_t('_aqb_pc_successfully_saved'))."'); return false;";
			
		$aForm = array(
     	'form_attrs' => array(
            'id' => 'edit-block-form',
			'name' => 'edit-block-form',
            'method' => 'post',
		    'enctype' => 'multipart/form-data',
			'action' => $sBaseUrl,
			'onsubmit' => $sSubmitFunction
        ),   		    
		
		'params' => array (
                'db' => array(
                    'submit_name' => 'save_button',
                )
		),			
        
		
        'table_attrs' => array(
			'cellpadding' => 0,
        	'cellspacing' => 0,
        	'style' => $bPopUp ? 'width:520px;' : '100%'
        ));
		
		$aForm['inputs'] = array();
		
		if ($sType == 'c')
		$aForm['inputs'] = array(
        
		'block_title' => array(
                'type' => 'text',
                'name' => 'block_title',
                'value' => $aBlock['title'],
				'required' => true,
       			'caption' => _t('_aqb_pc_block_title_edit'),
				'info' => _t('_aqb_pc_block_title_edit_info'),
       		    'attrs' => array('id' => 'aqb_block_title'),
    	),
		
		'block_body' => array(
                'type' => 'textarea',
                'name' => 'block_body',
                'value' => $aBlock['content'],
				'html' => '0',
				'required' => true,
       			'caption' => _t('_aqb_pc_block_body_edit'),
				'info' => _t('_aqb_pc_block_body_edit_info'),
       		    'attrs' => array('id' => 'aqb_block_body','style' => 'width:350px;height:250px;'),
				'attrs_wrapper' => array('style' => 'width:350px;height:250px;')
    	));
		
		$aForm['inputs'] = array_merge($aForm['inputs'], array($aSelect));
		
		if ($sType == 'c')
		$aForm['inputs'] = array_merge($aForm['inputs'], array(
				'block_types' => array(
                'type' => 'radio_set',
                'name' => 'block_type',
                'value' => $aBlock['type'] ? $aBlock['type'] : 'html',
				'values' => array('text' => _t('_aqb_block_type_text'), 'html' => _t('_aqb_block_type_html'), 'rss' => _t('_aqb_block_type_rss')),
       			'caption' => _t('_aqb_pc_block_types'),
				'info' => _t('_aqb_pc_block_types_info'),
				'dv' => '<br/>'
     	)));
		
		$aForm['inputs'] = array_merge($aForm['inputs'], array(
		'block_attrs' => array(
                'type' => 'checkbox_set',
                'name' => 'block_attrs',
                'value' => $aAttrsValues,
				'values' => $aAttrsValue,
				'caption' => _t('_aqb_pc_block_types_attrs'),
				'dv' => '<br/>'
     	),
		
		'fix_input' => array(
                'type' => 'custom',
       		    'content' => $sFixPosElement,
       			'caption' => _t('_aqb_block_attrs_unmovable') . (!(int)$iPos ? _t('_aqb_block_attrs_unmovable_addon') : '') ,
       		    'colspan' => false,
    		),
		      	
        'save' => array(
						'type' => 'custom',
						'colspan' => true,
						'content' => $sSaveButton
						),
		
		'save_p' => array(
						'type' => 'hidden',
						'value' => 'on',
						'name' => 'save_button'
						)
			));
    
	if ((int)$iBlockId) $aForm['inputs']['hidden'] = array(
											                'type' => 'hidden',
											                'value' => ($sType == 'c' ? $this -> _oConfig -> getCBPrefix() . $iBlockId : $iBlockId),
											       		    'name' => 'block_id',
															'attrs' => array('id' => 'block_id')
															);
												
	$oForm = new BxTemplFormView($aForm);
	
	if ($oForm->isSubmitted()) {
		return $this -> _oDb -> saveBlock($_POST) ? 1 : 0;
	} 
	
	if ((int)$aBlock['owner_id'])
	 {
		$aProfileInfo = getProfileInfo($aBlock['owner_id']);
		$sTitle = _t('_aqb_pc_owner_nickname', $aProfileInfo['NickName']);
	 } elseif((int)$iBlockId) $sTitle = _t('_aqb_pc_admin_edit'); 
	   else $sTitle = _t('_aqb_pc_create_block');
	 
	   $sEditForm = $this -> parseHtmlByName('create_form', array('content' => $oForm -> getCode(), 'title_length' => $this -> _oConfig -> getMaxCharsForTitle(), 'body_length' => $this -> _oConfig -> getMaxCharsForBody()));
	   
	   if ($bPopUp) $sRet = PopupBox('aqb_popup', $sTitle, $sEditForm);
	   else $sRet = $sEditForm;
	   
	   return $sRet; 
	}
	
	function  getPosElement(&$aBlock){
	  $aCols = $this -> _oDb -> getProfileColumns();

	  $sSelectP = $sSelectC = '';
	  $sSelectC ='<select name="column_num">';
	  foreach($aCols as $k => $v){
		if ((int)$aBlock['column'] == (int)$v['Column']) $sSel = 'selected="selected"'; else $sSel = ''; 
		$sSelectC .= "<option value=\"{$v['Column']}\" {$sSel}>{$v['Column']}</option>";
	  }
	  
	  $sSelectC .= "</select>";
	  $sSelectP ='<select name="column_pos">';
	  
	  if ((int)$aBlock['order'] < 1) $sB = 'selected="selected"'; else $sT = 'selected="selected"';  
	  
	  $sSelectP .= "<option value=\"top\" {$sT}>"._t('_aqb_block_pos_top')."</option>";
	  $sSelectP .= "<option value=\"bottom\" {$sB}>"._t('_aqb_block_pos_bottom')."</option>";
	  $sSelectP .='</select>';
	  return _t('_aqb_block_edit_column') . $sSelectC . _t('_aqb_block_edit_position') . $sSelectP;
	}
	
	function getSettingsPanel() {
        $iId = $this -> _oDb -> getSettingsCategory();
        if(empty($iId))
           return MsgBox(_t('_aqb_pc_nothing_found'));
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
        function getManageBlocks($sAction, $iPId, $iPage = 1){
	global $site;
	global $dir;
	global $p_arr;
	
	$aBlocksInfo = $this ->_oDb -> getMembersBlocks($iPId, $sAction, $iPage);

	$aBlocks = $aBlocksInfo['blocks'];
	
	if (!empty($aBlocks)){
		$aResult = array();
		$i = 1;
		
		
		 foreach($aBlocks as $k => $aBlock){
			
			$aItems[$aBlock['id']] = array(
	            'id' => $aBlock['id'],
	            'username' => getNickName($aBlock['owner_id']),
	            'block_title' => strlen($aBlock['title']) > 20 ? substr($aBlock['title'],0,20) . '...' : $aBlock['title'],    
				'block_created' => date(getLocaleFormat(BX_DOL_LOCALE_DATE), $aBlock['date']),
				'profile_link' => getProfileLink($aBlock['owner_id']),
				'action' => "javascript:AqbPCMain.showPopup('" . BX_DOL_URL_ROOT . $this -> _oConfig -> getBaseUri() . 'preview_block_form/' . $aBlock['id'] . "');"
		    );
	    }
		
		if ($this -> _oConfig -> isPermalinkEnabled())
		{	
			$sRequest = BX_DOL_URL_ROOT . $this -> _oConfig -> getBaseUri() . "approve_blocks/{$sAction}/"; 
			$sRequest = $sRequest . '{page}';
		}	
		else 
		{	
			$sRequest = BX_DOL_URL_ROOT . $this -> _oConfig -> getBaseUri() . "approve_blocks&type={$sAction}"; 
			$sRequest = $sRequest . '&page={page}';
		}	
		
		$iPerPage = (int)$this -> _oConfig -> getNumberOfBlocksOnBlocksPage();
		
		if( $iPage < 1 || (float)((int)$aBlocksInfo['count']/(int)$iPerPage) < 1)
			$iPage = 1;


		
		// gen pagination block ;
		$oPaginate = new BxDolPaginate
		(
			array
			(
				'page_url'   => $sRequest,
				'count'      => $aBlocksInfo['count'],
				'per_page'   => $iPerPage,
				'page'               => $iPage,
				'per_page_changer'   => false,
				'page_reloader'      => true,
				'on_change_page'     => null,
				'on_change_per_page' => null
			)
		);
		
		$aResult['paginate'] = $oPaginate -> getPaginate();
		
		$aButtons = array(
	    	'aqb-pc-delete' => _t('_aqb_pc_block_delete'),
			'aqb-pc-approve' => ($sAction == 'disapproved' ? _t('_aqb_pc_block_manage_approve') : _t('_aqb_pc_block_manage_disapprove'))
	    );    
    
		$sControls = BxTemplSearchResult::showAdminActionsPanel('pc-blocks-form', $aButtons, 'blocks');
	
		$sContent = $this -> parseHtmlByName('blocks_manage_table', array('controls' => $sControls, 'paginate' => $aResult['paginate'], 'bx_repeat:items' => array_values($aItems))); 
		
	}else $sContent = MsgBox(_t('_aqb_pc_nothing_found'));
	
	$aTopMenu = array(            
	    'disapproved' => array('href' => BX_DOL_URL_ROOT . $this -> _oConfig -> getBaseUri() . 'approve_blocks',  'title' => _t('_aqb_pc_disapproved_blocks'), 'active' => '1'),
		'approved' => $this -> _oConfig -> isShareBlocksAllowed() ? array('href' => BX_DOL_URL_ROOT . $this -> _oConfig -> getBaseUri() . 'approve_blocks/approved',  'title' => _t('_aqb_pc_approved_blocks'), 'active' => '0') : array()
	);	    
		
	return DesignBoxContent('', $sContent, 1, $this -> getTopMenuHtml($aTopMenu, $sAction));	
   }	 
    function getBlocksPage($sAction, $iPId, $iPage = 1){
	global $site;
	global $dir;
	global $p_arr;
	
	$aBlocksInfo = $this ->_oDb -> getAllAvailableBlocks($iPId, $sAction, $iPage);
	$aBlocks = $aBlocksInfo['blocks'];
	
	if (!empty($aBlocks))
	{
		$p_arr = getProfileInfo($iPId);
		$oProfile = new BxBaseProfileGenerator($iPId);
		$oBlockBuilder = new BxTemplProfileView($oProfile, $site, $dir);
		$oBlockBuilder -> oProfileGen->_iProfileID = $iPId;
		$oBlockBuilder -> initView(true);
		
		$aResult = array();
		$i = 1;
		
		
		foreach($aBlocks as $k => $v){
		  $oBlockBuilder -> sCode = '';
		  $oBlockBuilder -> genBlock($v['id'], $v['params']);
		  
		  if ($i % 2) $sAdd = 1; else $sAdd = 2; 
		  if ((int)$v['params']['owner']) 
		  { 	
			$aProfileInfo = getProfileInfo($v['params']['owner']);
			$sOwner = _t('_aqb_pc_blocks_owner', '<a href="'.BX_DOL_URL_ROOT . $aProfileInfo['NickName'].'">' . $aProfileInfo['NickName'] . '</a>'); 
		  }
		   else
            $sOwner = ''; 		  
		  
		  if (!strlen($oBlockBuilder -> sCode)) $oBlockBuilder -> sCode = DesignBoxContent( _t($v['params']['Caption']), MsgBox(_t('_aqb_pc_no_info')), 1);
		  $aResult['content_'.$sAdd] .= $this -> parseHtmlByName('block', array('owner' => $sOwner, 'content' => $oBlockBuilder -> sCode, 'block_id' => $v['id'], 'title' => _t('_aqb_pc_place_to_my_page'), 'url' => BX_DOL_URL_ROOT . $this -> _oConfig -> getBaseUri() . 'add_block_to_profile'));
		  
		  $i++;
		}
			
		if ($this -> _oConfig -> isPermalinkEnabled())
		{	
			$sRequest = BX_DOL_URL_ROOT . $this -> _oConfig -> getBaseUri() . "profile_blocks/{$sAction}/"; 
			$sRequest = $sRequest . '{page}';
		}	
		else 
		{	
			$sRequest = BX_DOL_URL_ROOT . $this -> _oConfig -> getBaseUri() . "profile_blocks&type={$sAction}"; 
			$sRequest = $sRequest . '&page={page}';
		}	
		
		$iPerPage = (int)$this -> _oConfig -> getNumberOfBlocksOnBlocksPage();
		
		if( $iPage < 1 || (float)((int)$aBlocksInfo['count']/(int)$iPerPage) < 1)
			$iPage = 1;


		if (!isset($aResult['content_2'])) $aResult['content_2'] = '';
		
		// gen pagination block ;
		$oPaginate = new BxDolPaginate
		(
			array
			(
				'page_url'   => $sRequest,
				'count'      => $aBlocksInfo['count'],
				'per_page'   => $iPerPage,
				'page'               => $iPage,
				'per_page_changer'   => false,
				'page_reloader'      => true,
				'on_change_page'     => null,
				'on_change_per_page' => null
			)
		);
		
		$aResult['paginate'] = $oPaginate -> getPaginate();
		$sContent = $this -> parseHtmlByName('block_page', $aResult); 
		
	}else $sContent = MsgBox(_t('_aqb_pc_nothing_found'));
	
	$aTopMenu = array(            
	    'standard' => array('href' => BX_DOL_URL_ROOT . $this -> _oConfig -> getBaseUri() . 'profile_blocks/standard',  'title' => _t('_aqb_pc_standard_blocks'), 'active' => '1'),
	    'shared' => $this -> _oConfig -> isShareBlocksAllowed() ? array('href' => BX_DOL_URL_ROOT . $this -> _oConfig -> getBaseUri() . 'profile_blocks/shared',  'title' => _t('_aqb_pc_shared_blocks'), 'active' => '0') : array()
	);	    
		
	return DesignBoxContent('', $sContent, 1, $this -> getTopMenuHtml($aTopMenu, $sAction));	
   }
    
       
    function getTopMenuHtml($aTopMenu, $sAction = '') //$aParam, $iBoxId = 0, $sAction = ''
    {
        $aItems = array();
        foreach ($aTopMenu as $sName => $aItem)
        {
           	$aItems[$aItem['title']] = array(                
                'dynamic' => $aItem['dynamic'] ? $aItem['dynamic'] : false,
                'active' => ($sName == $sAction ? 1 : 0),
                'href' => $aItem['href']
            );
        }        
        return BxDolPageView::getBlockCaptionItemCode(0, $aItems);
    }
    	
	function getBlockSearch() {
    
	$aForm = array(
        'form_attrs' => array(
            'id' => 'pc-search-form',
            'action' => $_SERVER['PHP_SELF'],
            'method' => 'post',
            'enctype' => 'multipart/form-data',
        ),
           'inputs' => array (
            
			'fields' => array(
    				 'type' => 'checkbox_set',
					 'name' => 'filter_params',
					 'caption' => _t('_aqb_select_criteria'),
					 'value' => array('block_id','block_title','block_body','nickname','email','headline','tags','type'),
					 'values' => array(
						'block_id' => _t('_aqb_pc_filter_block_id'),
                        'block_title' => _t('_aqb_pc_filter_block_title'),
						'block_body' => _t('_aqb_pc_filter_block_body'),
						'type' => _t('_aqb_pc_filter_block_type'),
						'nickname' => _t('_aqb_pc_filter_block_nickname'),
						'email' => _t('_aqb_pc_filter_block_email'),
                        'headline' => _t('_aqb_pc_filter_block_headline'),
						'tags' => _t('_aqb_pc_filter_block_tags')			
			          ),
            ),
				   
			
			'pc-filter-input' => array(
                'type' => 'text',
                'name' => 'pc-filter-input',
                'caption' => _t('_aqb_pc_filter'),
                'value' => '',
				'info' =>  _t('_aqb_pc_filter_info')
 			),
			
            'search' => array(
                'type' => 'button',
                'name' => 'search',
                'value' => _t('_aqb_pc_search_button'),
                'attrs' => array(
                    'onclick' => 'javascript:' . BX_DOL_ADM_MP_JS_NAME . '.changeFilterSearch()'
                )
            ), 
       )
    );

    $oForm = new BxTemplFormView($aForm);
    return $oForm->getCode();
}
	
	function getOrderParpoints($sFName, &$aParams){
		return 'onclick="'.BX_DOL_ADM_MP_JS_NAME.'.orderByField(\'' . (($aParams['view_order'] == $sFName && 'asc' == strtolower($aParams['view_type'])) ? 'desc' : 
			   ($aParams['view_order'] == $sFName && 'desc' == strtolower($aParams['view_type']) ? 'asc' : '')) . '\',\''.$sFName.'\')"';
    }
	
	function getArrowImage($sFName, &$aParams){
	    if ($aParams['view_order'] == $sFName && 'asc' == strtolower($aParams['view_type'])) return '<img class="pc-sort-arrow" src="' . $this->getIconUrl('arrow_up.png') . '" />'; 
		if ($aParams['view_order'] == $sFName) return '<img class="pc-sort-arrow" src="' .  $this->getIconUrl('arrow_down.png'). '" />';
		
		return '';			
	}	
}
?>