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

bx_import('Module', $aModule);

global $_page;
global $_page_cont;

$iIndex = 3;
$_page['name_index']	= $iIndex;
$_page['css_name']		= array('forms_adv.css', 'post.css', 'view.css');

check_logged();

$oFeedback = new BxFdbModule($aModule);
$_page_cont[$iIndex]['page_code_view'] = DesignBoxContent(_t('_feedback_bcaption_view_my'), $oFeedback->serviceMyBlock(), 1);

if(isset($aRequest[0]) && !empty($aRequest[0]))
    $_page_cont[$iIndex]['page_code_form'] = $oFeedback->serviceEditBlock(process_db_input($aRequest[0], BX_TAGS_STRIP));
else if(isset($_POST['id']))
    $_page_cont[$iIndex]['page_code_form'] = $oFeedback->serviceEditBlock((int)$_POST['id']);
else
    $_page_cont[$iIndex]['page_code_form'] = $oFeedback->servicePostBlock();            
$_page_cont[$iIndex]['page_code_form'] = DesignBoxContent(_t('_feedback_bcaption_post'), $_page_cont[$iIndex]['page_code_form'], 1);

$oFeedback->_oTemplate->setPageTitle(_t('_feedback_pcaption_post'));
PageCode($oFeedback->_oTemplate);
?>