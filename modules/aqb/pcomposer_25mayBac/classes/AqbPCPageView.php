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

bx_import('BxDolPageView');
bx_import('BxDolPrivacy');
require_once('AqbPCBuilder.php');

class AqbPCPageView extends BxDolPageView {
    var $oBuilder = null, $iProfile, $iViewer, $iMemberGroup, $sPrefix, $bOnlyView;
	
	function AqbPCPageView($sPageName){
		parent::BxDolPageView($sPageName);
		$this -> oBuilder = new AqbPCBuilder();
		$this -> iProfile = $this -> oProfileGen -> _iProfileID;
		$this -> oBuilder -> _oMain -> _oDb -> checkForExisting($this -> iProfile);
		$this -> iViewer = $this -> oBuilder -> _oMain -> iUserId;
		$this -> iMemberGroup = $this -> oBuilder -> getMemberGroup($this -> iProfile);
		$this -> sPrefix = $this -> oBuilder -> _oMain -> _oConfig -> getCBPrefix();
	}	
	
	function initView($bOnlyView = false){
    	$this -> bOnlyView = $bOnlyView;
	}
	
	function genColumn( $iColumn ) {
		if (!is_null($this -> oBuilder)) $this -> aPage['Columns'][ $iColumn ]['Blocks'] = $this -> oBuilder -> getColumn($iColumn, $this -> iProfile, $this -> iMemberGroup);
		parent::genColumn( $iColumn );
	}
	
	function genBlock( $iBlockID, $aBlock, $bStatic = true, $sDynamicType = 'tab' ) {
		if (!is_null($this -> oBuilder) && strpos($iBlockID, $this -> sPrefix) !== false && (int)$aBlock['vm'] != 0 && $this -> iProfile != $this -> iViewer) 
		{
			//--- Privacy for custom blocks view ---//
			$oPrivacy = new BxDolPrivacy();
			if(!$oPrivacy -> _oDb -> isGroupMember((int)$aBlock['vm'], $this -> iProfile, $this -> iViewer)) return true;
			//--- Privacy for custom blocks view ---//
		}		
		if ((int)$aBlock['cd'] || $aBlock['Caption'] == '_crss_Custom_Feeds')
		{
			$sTmp = $this -> sCode;
			$this -> sCode = '';
		}

		parent::genBlock($iBlockID, $aBlock, $bStatic, $sDynamicType);
		
		if ($aBlock['Caption'] == '_crss_Custom_Feeds'){
			if (!(int)$aBlock['cd']) $sAddon = $sTmp; $this -> sCode =  $sAddon . preg_replace('/(<div class="dbTitle">).+(<\/div>)/','\\1 '.($this -> _getBlockCaptionCode($iBlockID, $aBlock, $aBlockCode, $bStatic, $sDynamicType)).' \\2', $this -> sCode);
		}
			
		if ((int)$aBlock['cd']){
		  $sBlockContent = preg_replace("/(boxContent)/",'\\1 aqb_clped', $this -> sCode);
	      $this -> sCode = $sTmp . $sBlockContent;
		}	
	}
		
	 function _getBlockCaptionCode($iBlockID, $aBlock, $aBlockCode, $bStatic = true, $sDynamicType = 'tab') {
    	if ($this -> bOnlyView) return parent::_getBlockCaptionCode($iBlockID, $aBlock, $aBlockCode, $bStatic, $sDynamicType); 
		
		//--- Privacy for Profile page ---//
        $sCode = "";
	
	    if($this -> iMemberID == $this -> oProfileGen -> _iProfileID || isAdmin()) {
    	    $sAlt = "";
    	    
			if (is_null($this -> oBuilder)) 
			{
				$sCode = $GLOBALS['oSysTemplate']->parseHtmlByName('ps_page_chooser.html', array(
					'alt' => $sAlt,
	    	    	'profile_id' => $this->oProfileGen->_iProfileID,
	    	    	'block_id' => $iBlockID
	    	    ));
			}
			else
			{			
				$sCode = $this -> oBuilder -> _oMain -> _oTemplate -> parseHtmlByName('menu_items', array(
					'alt' => $sAlt,
	    	    	'block_id' => $iBlockID,
					'add_classes' => $this -> oBuilder -> getClasses($aBlock)
	    	    ));
			}
			
		} 
		elseif((int)$aBlock['cd']) $sCode = $this -> oBuilder -> _oMain -> _oTemplate -> parseHtmlByName('menu_items_guest', array('block_id' => $iBlockID));
		
		return $sCode . parent::_getBlockCaptionCode($iBlockID, $aBlock, $aBlockCode, $bStatic, $sDynamicType);
    }
	
}
?>