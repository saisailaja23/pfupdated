<?php
/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Badge
*     website              : http://www.boonex.com
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License as published by the
* Free Software Foundation; either version 2 of the
* License, or  any later version.
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

bx_import('BxDolTwigPageMain');

class BxBadgePageMain extends BxDolTwigPageMain {
 
    function BxBadgePageMain(&$oMain) {
		parent::BxDolTwigPageMain('modzzz_badge_main', $oMain); 

		$this->_oDb = $oMain->_oDb;
        $this->_oConfig = $oMain->_oConfig;
        $this->oMain = $oMain;
 
 
	}
 
	function getBlockCode_Badge() {
			 
		$iLimit = 20; 
  
        $iCount = (int)$this->_oDb->getOne("SELECT COUNT(`id`) FROM `" . $this->_oDb->_sPrefix . "main` WHERE `active`=1 ");
 
        $aData = array();
        $sPaginate = '';
        if ($iCount) {
 
          
			$aBadge = $this->_oDb->getAll("SELECT `id`, `title`, `icon` FROM `" . $this->_oDb->_sPrefix . "main` ORDER BY `created` DESC");
    
			$aBadgeCells = array();
			foreach($aBadge as $aEachBadge)
			{  
				$iBadgeId = (int)$aEachBadge['id']; 
				$sLargeViewUrl = $sBaseLargeViewUrl . $iBadgeId;

				$aBadgeCells[] = array ( 
					'id'=> $iBadgeId, 
					'title'=> $aEachBadge['title'], 
 					'icon' => $this->_oDb->getBadgeIcon($iBadgeId, $aEachBadge['icon'], true),  
   			     	'large_view_url' => $sLargeViewUrl   
  				); 
			}
 
			$sFormName = 'badge_form'; 
			$sPageUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'home/';
    
			if ($iPages > 1) {
				$oPaginate = new BxDolPaginate(array(
					'page_url' => $sPageUrl,
					'count' => $iCount,
					'per_page' => $iLimit,
					'page' => $iPage,
					'per_page_changer' => true,
					'page_reloader' => true, 
					'on_change_page' => 'getHtmlData(\'block_badge\', \''.$sPageUrl.'?&ajax=1&page={page}&per_page={per_page}\');return false',
					'on_change_per_page' => ''
				));
				$sPaginate = $oPaginate->getSimplePaginate('', -1, -1, false); 
			}else{
				$sPaginate = "";
			}
  
			$aVars = array(
				'bx_repeat:badge' => $aBadgeCells,  
 				'form_name' => $sFormName,  
				'pagination' => $sPaginate, 
			);

			$this->oTemplate->addCss('badge.css');  
			$sContent .= $this->oTemplate->parseHtmlByName('badge',$aVars);
		}else{
			$sContent .= MsgBox(_t("_modzzz_badge_no_badge")); 
		}

		if($_GET['ajax']){
			echo $sContent;
			exit;
		}

		$sCode = "<div id='block_badge'>{$sContent}</div>";
	 
        $sBaseUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . "my";
        $aMenu = array(
			_t('_modzzz_badge_block_submenu_my') => array('href' => $sBaseUrl, 'active' =>false) 
         );

        return array($sCode, $aMenu, '', ''); 
    }

 
}

?>