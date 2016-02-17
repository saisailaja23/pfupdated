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

class AqbPCBuilder{
  
	function AqbPCBuilder(){
		$this -> _oMain = BxDolModule::getInstance("AqbPCModule");
	}
    
	function init($iProfile, $iViewer){
		global $_page, $_page_cont, $_ni;
		
		$this -> _oMain -> _oDb -> updateMembersColumns($iProfile);
		$sBaseUrl = BX_DOL_URL_ROOT . $this -> _oMain -> _oConfig -> getBaseUri();
		$sRemoveBlock = $this -> _oMain -> _oConfig -> isAllowedToRemoveOwnBlock() ? _t('_aqb_pc_remove_block_from_site') : _t('_aqb_pc_request_remove_block');
		
		$sMenuItems = '{	
						 rollup:"'.$this -> processMenuItems(_t('_aqb_pc_roll_up')).'",
						 unwrap:"'.$this -> processMenuItems(_t('_aqb_pc_unwrap')).'",
						 change_privacy:"'.$this -> processMenuItems(_t('_aqb_pc_change_block_privacy')).'",
						 avail_for_nonmembers:"'.$this -> processMenuItems(_t('_aqb_pc_avail_for_nonmembers')).'",
						 unavail_for_nonmembers:"'.$this -> processMenuItems(_t('_aqb_pc_unavail_for_nonmembers')).'",
						 edit:"'.$this -> processMenuItems(_t('_aqb_pc_edit_block_content')).'",
						 share:"'.$this -> processMenuItems(_t('_aqb_pc_share_block')).'",
						 unshare:"'.$this -> processMenuItems(_t('_aqb_pc_unshare_block')).'",
						 remove:"'.$this -> processMenuItems(_t('_aqb_pc_remove_block')).'",
						 remove_request:"'.$this -> processMenuItems($sRemoveBlock).'",
					   }';

	   $sCustomPrefix = $this -> _oMain -> _oConfig -> getCBPrefix();
	   $isAdmin = (int)$this -> _oMain -> isAdmin(); 	
	   $isAllowedToChangePrivacy = $this -> _oMain -> _oConfig -> isAllowedToChangePrivacy();
	   
	   $this -> _oMain -> _oTemplate -> addJs(array('main.js','composer.js'));
	   $this -> _oMain -> _oTemplate -> addCss(array('composer.css'));
	   
	   if ((int)$iProfile == (int)$iViewer || $isAdmin)
	   {
	   
	   $this -> _oMain -> _oTemplate -> addJs(array('ui.sortable.js', 'jquery-ui.js'));
	   
	   $_page['extra_js'] .=<<<EOF
		  <script type="text/javascript" language="javascript">
		   var AqbPC = new AqbComposer({
									     url:'{$sBaseUrl}',
										 owner:'{$iProfile}',
										 viewer:'{$iViewer}',
										 admin:{$isAdmin},
										 menu_items:{$sMenuItems},
										 change_privacy:{$isAllowedToChangePrivacy},
										 custom_prefix: '{$sCustomPrefix}'
							    	   });
		$(document).ready(AqbPC.init);	
		</script>

EOF;
	}else
	{
	$_page['extra_js'] .=<<<EOF
		  <script type="text/javascript" language="javascript">
		   var AqbPC = new AqbComposer({});
		  </script>
EOF;
	}
		
	}
	
	function getColumn($iCol, $iProfile, $iGroup){
		 return $this -> _oMain -> _oDb -> getColumnBlocks($iCol, $iProfile, $iGroup);
	}
	
	function getMemberGroup($iPOwner){
		if ((int)$this -> _oMain -> iUserId  == (int)$iPOwner || $this -> _oMain -> isAdmin()) return AQB_PC_OWNER;
		if ((int)$this -> _oMain -> iUserId) return AQB_PC_GUEST;
		return AQB_PC_NONMEMBER;
	}
	
	function processMenuItems(&$sString)
	{
		$sString = preg_replace('{\r}',"", $sString);
		$sString = preg_replace('{\n}',"", $sString);
		$sString = addslashes($sString);
		return $sString;
	}
	
	function getClasses(&$aBlock){
		$sClass = '';
			
		if ((int)$aBlock['rm'] || ($aBlock['type'] == 's' && !(int)$this -> _oMain -> _oConfig -> isStandardBlocksDeleteAllowed())) $sClass .= ' aqb_rmv';
		if ((int)$aBlock['cp']) $sClass .= ' aqb_cpb';
		if ((int)$aBlock['mv']) $sClass .= ' aqb_mvb';
		
		if ($aBlock['type'] == 'c' && (int)$aBlock['owner'] && ((int)$aBlock['owner'] == (int)$this -> _oMain -> iUserId)) 
		{
			$sClass .= ' aqb_my';
			if (isset($aBlock['sh']) && (int)$aBlock['sh']) $sClass .= ' aqb_shared'; else if(isset($aBlock['sh']) && !(int)$aBlock['sh']) $sClass .= ' aqb_unshared';
		}		
		
		return $sClass;
	}
	
	function getRssBlockContent($sID){
		$aTypeId = $this -> _oMain -> _oDb -> getBlockIdByPost($sID);
		$aInfo = $this -> _oMain -> _oDb -> getBlockInfo($aTypeId['id']);
		if (strlen($aInfo['content'])) return $aInfo['content'];
		return false;
	}
}
?>