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

bx_import('BxDolModuleDb');

define('AQB_PC_OWNER',2);
define('AQB_PC_GUEST',1);
define('AQB_PC_NONMEMBER',0);

function sortByAsc($a, $b){
	if ((int)$a['row'] == (int)$b['row']) return 0;
	return ((int)$a['row'] < (int)$b['row']) ? -1 : 1;  	
}

class AqbPCDb extends BxDolModuleDb {	
	/*
	 * Constructor.
	 */
	
	function AqbPCDb(&$oConfig) {
		parent::BxDolModuleDb($oConfig);
		$this -> _oConfig = &$oConfig;
	}
		
	function showBlocksCount($sFilter = ''){
	  if (strlen($sFilter)) $sWhere = "WHERE `approved` = '".((int)$sFilter)."'";
	  return $this -> getOne("SELECT COUNT(*) FROM `{$this->_sPrefix}members_blocks` {$sWhere} LIMIT 1");
	}
	
	function isGroupAdmin($iProfileId){
		return (int)$this -> getOne("SELECT COUNT(*) FROM `bx_groups_main` WHERE `status` = 'approved' AND `author_id` = '{$iProfileId}' LIMIT 1") > 0;
	}
	
	function getGroupAdminMembers($iProfileId){
		if (!(int)$iProfileId) return false;
		
		$aAllGroupMembers = $this -> getAll("SELECT `id_profile` FROM `bx_groups_main` 
									   LEFT JOIN `bx_groups_fans` ON `bx_groups_main`.`id` = `bx_groups_fans`.`id_entry`
									   LEFT JOIN `aqb_pc_members_blocks` ON `bx_groups_fans`.`id_profile` = `aqb_pc_members_blocks`.`owner_id`									   
									   WHERE `bx_groups_main`.`author_id` = '{$iProfileId}' AND `bx_groups_main`.`fans_count` > 0 GROUP BY `id_profile`"); 
		$aMembers = array();
		
		foreach($aAllGroupMembers as $k => $v) $aMembers[] = $v['id_profile'];	
			
		return $aMembers; 
	}
	
	function getMembersBlocks($iPId, $sType = 'disapproved', $iPage, $iPerPage = false){
		
		$iPerPage = !$iPerPage ? (int)$this -> _oConfig -> getNumberOfBlocksOnBlocksPage() : $iPerPage;
	
		$iCount = 0;

		if ($sType == 'disapproved') $sApprove = 0; else $sApprove = 1;

		$aMembers = $this -> getGroupAdminMembers($iPId); 

		if (empty($aMembers)) return false;
		
		$sWhere = implode("','", $aMembers); $sWhere = "('".$sWhere."')";
		$iCount = $this -> getOne("SELECT COUNT(*)	FROM `{$this->_sPrefix}members_blocks` as `c` WHERE `c`.`approved` = '{$sApprove}' AND `c`.`owner_id` IN {$sWhere} ORDER BY `date` DESC");
		
		$iCount = !(int)$iCount ? 1 : $iCount;
			
		if( $iPage < 1 || (float)((int)$iCount/(int)$iPerPage) <= 1)
		$iPage = 1;

		$iLimitFrom = ( $iPage - 1 ) * $iPerPage;
				
		$aRes = $this -> getAll("SELECT *									   
									 FROM `{$this->_sPrefix}members_blocks` as `c`
									 WHERE `c`.`approved` = '{$sApprove}' AND `c`.`owner_id` IN {$sWhere} LIMIT {$iLimitFrom}, {$iPerPage}");
	
		return array('blocks' => $aRes, 'count' => $iCount);
	}
	
	function getAllAvailableBlocks($iPId, $sType = 'standard', $iPage, $iPerPage = false){
	   $aBlocks = $this -> getProfileBlocksAsArray($iPId);
     
	   $aB = array();
	   
	    foreach($aBlocks as $k => $v)
		  foreach($v as $key => $value)
			{  
			  if ($sType == 'standard' &&  $k == 's') $aB[] = $key;
			  if ($sType == 'shared' &&  $k == 'c') $aB[] = $key;
			}  
			
		if (count($aB)){    
			$sWhere = implode("','", $aB);
			$sWhere = "('".$sWhere."')";
		}	
		
		if ($sWhere) $sWhere = "AND `c`.`ID` NOT IN {$sWhere}";
	
		$iPerPage = !$iPerPage ? (int)$this -> _oConfig -> getNumberOfBlocksOnBlocksPage() : $iPerPage;
	
		$iCount = 0;
		if ($sType == 'standard')
		{
			$iCount = $this -> getOne("SELECT COUNT(*)
										FROM `sys_page_compose` as `c` 
										LEFT JOIN `sys_page_compose_privacy` as `p` ON `c`.`ID` = `p`.`block_id` AND `user_id` = '{$iPId}'
										LEFT JOIN `{$this->_sPrefix}standard_blocks` as `a` ON `a`.`id` = `c`.`ID`
										WHERE `c`.`Page` = 'profile' AND `c`.`Column` != 0 {$sWhere}");
			$iCount = !(int)$iCount ? 1 : $iCount;
			
			if( $iPage < 1 || (float)((int)$iCount/(int)$iPerPage) <= 1)
			$iPage = 1;

			$iLimitFrom = ( $iPage - 1 ) * $iPerPage;
	
			$aRes = $this -> getAll("SELECT `c`.`ID`
										FROM `sys_page_compose` as `c` 
										LEFT JOIN `sys_page_compose_privacy` as `p` ON `c`.`ID` = `p`.`block_id` AND `user_id` = '{$iPId}'
										LEFT JOIN `{$this->_sPrefix}standard_blocks` as `a` ON `a`.`id` = `c`.`ID`
										WHERE `c`.`Page` = 'profile' AND `c`.`Column` != 0 {$sWhere} ORDER BY `c`.`ID` LIMIT {$iLimitFrom}, {$iPerPage}");
		} 
		  else 
		{
			$iCount = $this -> getOne("SELECT COUNT(*)									   
									FROM `{$this->_sPrefix}members_blocks` as `c`
									WHERE `c`.`approved` = '1' AND `c`.`share` = '1' {$sWhere}");
			$iCount = !(int)$iCount ? 1 : $iCount;
			
			if( $iPage < 1 || (float)((int)$iCount/(int)$iPerPage) <= 1)
			$iPage = 1;

			$iLimitFrom = ( $iPage - 1 ) * $iPerPage;
				
			$aRes = $this -> getAll("SELECT `c`.`id` as `ID`									   
									 FROM `{$this->_sPrefix}members_blocks` as `c`
									 WHERE `c`.`approved` = '1' AND `c`.`share` = '1' {$sWhere} ORDER BY `c`.`id` LIMIT {$iLimitFrom}, {$iPerPage}");
		}
		
		$sPrefix = $this -> _oConfig -> getCBPrefix();
		$aResult = array();

		foreach($aRes as $k => $v) 
		{	
			$aResult[] = array(
								'id' => ($sType == 'standard' ? $v['ID'] : $sPrefix.$v['ID']) ,
		                        'params' => ($sType == 'standard' ? $this -> getStandardBlockInfo($v['ID'], array()) : $this -> getCustomBlockInfo($v['ID'], array())) 						
							  );
		}	
		
		return array('blocks' => $aResult, 'count' => $iCount);
	}
	
	function checkForExisting($iProfileID){
	    if ($this -> getOne("SELECT COUNT(*) FROM `{$this->_sPrefix}profiles_info` WHERE `member_id` = '{$iProfileID}' LIMIT 1") == 1) return true;
		
		if (!(int)$iProfileID) 
		{
			$aBlocks = array_merge($this -> getStandardAvailBlocks($iProfileID), $this -> getCustomAvailBlocks($iProfileID));
			if ($aBlocks === false) return false;
			return $this -> serializeProfileBlocks($aBlocks, $iProfileID);	
		}	
		$this -> updateMembersColumns(0);
		return $this -> query("INSERT INTO `{$this->_sPrefix}profiles_info` (`member_id`, `page`) SELECT '{$iProfileID}' as `pId`, `page` FROM `{$this->_sPrefix}profiles_info` WHERE `member_id` = '0'"); 
	}
	
	function checkBlockExist($iBlockId, $sType = 'c'){
	    if ($sType == 'c') return $this -> getOne("SELECT COUNT(*) FROM `{$this->_sPrefix}members_blocks` WHERE `id` = '{$iBlockId}' LIMIT 1") == 1;
		else return $this -> getOne("SELECT COUNT(*) FROM `{$this->_sPrefix}standard_blocks` WHERE `id` = '{$iBlockId}' LIMIT 1") == 1;
	}
	
	function serializeProfileBlocks(&$aBlocks, $iProfileID){
		if (empty($aBlocks)) return false;
		$sBlocks = addslashes(var_export($aBlocks, true));
		return $this -> query("REPLACE INTO `{$this->_sPrefix}profiles_info` SET `page` = '{$sBlocks}', `member_id` = '{$iProfileID}'");
	}
	
	function getCustomBlockPrivacy($iPId, $iBlockId){
		$aPBlocks = $this -> getProfileBlocksAsArray($iPId);

		if (isset($aPBlocks['c'][$iBlockId])) return (int)$aPBlocks['c'][$iBlockId]['vm'];

		$aProfile = getProfileInfo($iPId);
		return (int)$aProfile['PrivacyDefaultGroup'];
	}	
	
	function saveCutomPrivacy($iPId, $iBlockId, $iGroup){
		$aPBlocks = $this -> getProfileBlocksAsArray($iPId);
			
		if (isset($aPBlocks['c'][$iBlockId]) && (int)$this->getOne("SELECT COUNT(*) FROM `{$this->_sPrefix}members_blocks` WHERE `owner_id`='{$iPId}' AND `id`='{$iBlockId}' LIMIT 1"))
		{
				$aPBlocks['c'][$iBlockId]['vm'] = $iGroup;
				return $this -> serializeProfileBlocks($aPBlocks, $iPId); 		
		}
			
		return false;
	}	
	
	function makeStandardBlockAvailable($iBlockId, $iCol, $iRow){
	    return (int)$this -> query("UPDATE `sys_page_compose` SET `Column` = '{$iCol}', `Order` = '{$iRow}' WHERE `ID` = '{$iBlockId}' LIMIT 1");
	}
	
	function getDefaultStandardBlockInfo($iBlockId){
		$aBlocks = $this -> getProfileBlocksAsArray(0);
			
		if (isset($aBlocks['s'][$iBlockId])) return array('s' => array( $iBlockId => $aBlocks['s'][$iBlockId])); 
		else {
			$aBlock = $this -> getRow("SELECT 
											IF (`a`.`unmovable` IS NULL, '0',`a`.`unmovable`) as `unmovable`,
											IF (`a`.`irremovable` IS NULL, '0',`a`.`irremovable`) as `irremovable`,
											IF (`a`.`uncollapsable` IS NULL, '0',`a`.`uncollapsable`) as `uncollapsable`,
											IF (`a`.`collapsed_def` IS NULL, '0',`a`.`collapsed_def`) as `collapsed_def`,
										    IF (`a`.`visible_group` IS NULL, '1',`a`.`visible_group`) as `vm`,
											`c`.*									
									FROM `sys_page_compose` as `c` 
									LEFT JOIN `{$this->_sPrefix}standard_blocks` as `a` ON `a`.`id` = `c`.`ID`
									WHERE `c`.`Page` = 'profile' AND `c`.`Column` > 0 AND `c`.`ID` = '{$iBlockId}' LIMIT 1");
			$aResult = array();
			
			$aResult[$aBlock['ID']]['rw'] = $aBlock['Order']; //row
			$aResult[$aBlock['ID']]['cl'] = $aBlock['Column'];//col
			$aResult[$aBlock['ID']]['vm'] = $aBlock['vm'];//visible for members group			
			$aResult[$aBlock['ID']]['cp'] = $aBlock['uncollapsable'];//collapsable
			$aResult[$aBlock['ID']]['cd'] = $aBlock['collapsed_def'];//collapsable
			$aResult[$aBlock['ID']]['rm'] = $aBlock['irremovable'];//removable
			$aResult[$aBlock['ID']]['mv'] = $aBlock['unmovable'];//movable
			return $aResult;
		}		
		
		return false;
	}
	
	function getStandardAvailBlocks($iProfileID){
		$aBlocks = $this -> getAll("SELECT 
											IF (`a`.`unmovable` IS NULL, '0',`a`.`unmovable`) as `unmovable`,
											IF (`a`.`irremovable` IS NULL, '0',`a`.`irremovable`) as `irremovable`,
											IF (`a`.`uncollapsable` IS NULL, '0',`a`.`uncollapsable`) as `uncollapsable`,
											IF (`a`.`collapsed_def` IS NULL, '0',`a`.`collapsed_def`) as `collapsed_def`,
											IF (`p`.`allow_view_block_to` IS NULL, IF (`a`.`visible_group` IS NULL, '1',`a`.`visible_group`), `p`.`allow_view_block_to`) as `vm`,
											`c`.*											
									FROM `sys_page_compose` as `c` 
									LEFT JOIN `sys_page_compose_privacy` as `p` ON `c`.`ID` = `p`.`block_id` AND `user_id` = '{$iProfileID}'
									LEFT JOIN `{$this->_sPrefix}standard_blocks` as `a` ON `a`.`id` = `c`.`ID`
									WHERE `c`.`Page` = 'profile' AND `c`.`Column` > 0 ORDER BY `c`.`Column`");
								
	    $aResult = array();

		foreach($aBlocks as $key => $value){
			$aResult[$value['ID']]['rw'] = $value['Order']; //row
			$aResult[$value['ID']]['cl'] = $value['Column'];//col
			$aResult[$value['ID']]['vm'] = $value['vm'];//visible for members group			
			$aResult[$value['ID']]['cp'] = $value['uncollapsable'];//collapsable
			$aResult[$value['ID']]['cd'] = $value['collapsed_def'];//collapsable
			$aResult[$value['ID']]['rm'] = $value['irremovable'];//removable
			$aResult[$value['ID']]['mv'] = $value['unmovable'];//movable
		}		

		if (count($aResult) > 0) return array('s' => $aResult);
		return array();
	}
	
	function getCustomAvailBlocks($iProfleID){
		$aBlocks = $this -> getAll("SELECT 
										   `c`.`id`,
										   `c`.`order`,
		                                   `c`.`column`,
										   `c`.`unmovable`,
										   `c`.`irremovable`,
										   `c`.`uncollapsable`,
										   `c`.`collapsed_def`, 
										   `c`.`visible_group` as `vm`,
										   `c`.`share` as `sh`
									FROM `{$this->_sPrefix}members_blocks` as `c`
									WHERE `c`.`approved` = '1' AND (`c`.`share` = '1' OR `owner_id` = '{$iProfleID}')  ORDER BY `c`.`column` ASC, `c`.`order`");
		$aResult = array();

		foreach($aBlocks as $key => $value){
			$aResult[$value['id']]['rw'] = $value['order']; //row
			$aResult[$value['id']]['cl'] = $value['column'];//col
			$aResult[$value['id']]['vm'] = $value['vm'];//visible for members group			
			$aResult[$value['id']]['cp'] = $value['uncollapsable'];//collapsable
			$aResult[$value['id']]['cd'] = $value['collapsed_def'];//collapsable
			$aResult[$value['id']]['rm'] = $value['irremovable'];//removable
			$aResult[$value['id']]['mv'] = $value['unmovable'];//movable
		
		}		

		if (count($aResult) > 0) return array('c' => $aResult);
		return array();
	}
	
	function getProfileBlocksAsString($iPId){
		return $this -> getOne("SELECT `page` FROM `{$this->_sPrefix}profiles_info` WHERE `member_id` = '{$iPId}' LIMIT 1");
	}
	
	function applyDefaultStructure(){
		return $this -> query("DELETE FROM `{$this->_sPrefix}profiles_info` WHERE `member_id` <> '0'");
	}
	
	function getProfileBlocksAsArray($iPId){
		$aRes = array();
		eval('$aRes = ' . $this -> getProfileBlocksAsString($iPId) . ';');
		return $aRes; 
	}
	
	function getColumnBlocks($iCol, $iProfile, $iMGroup = AQB_PC_GUEST){
		$aBlocks = $this -> getProfileBlocksAsArray($iProfile);

	    $aCol = array();
		foreach($aBlocks as $k => $v)
			foreach($v as $key => $value)
				if ((int)$value['cl'] == (int)$iCol) $aCol[] = array('type' => $k, 'id' => $key, 'row' => $value['rw'], 'cd' => $value['cd'], 'vm' => $value['vm']); 				
		
		if (count($aCol) == 0) return array();

		usort($aCol, "sortByAsc");
				
		$aResult = array();
		$sPrefix = $this -> _oConfig -> getCBPrefix();

		foreach($aCol as $key => $value){
		    if ($value['type'] == 's') $aResult[$value['id']] = $this -> getStandardBlockInfo($value['id'], $value);
			else $aResult[$sPrefix.$value['id']] = $this -> getCustomBlockInfo($value['id'], $value);
		}		
		
		return $aResult;
	}
	
	function getBlockForView($iProfile, $iBlockId, $sType = 'c'){
	   	$aBlocks = $this -> getProfileBlocksAsArray($iProfile);

		$aValue = $aBlocks[$sType][$iBlockId];
	  	$aBlock = array('type' => $sType, 'id' => $iBlockId, 'row' => $aValue['rw'], 'cd' => $aValue['cd']); 				
			
		$aResult = array();
		$sPrefix = $this -> _oConfig -> getCBPrefix();

		if ($sType == 's') 
		{	
			$aResult['id'] = $iBlockId;
			$aResult['params'] = $this -> getStandardBlockInfo($iBlockId, $aBlock);
		}	
		else
		{
			$aResult['id'] = $sPrefix.$iBlockId;
			$aResult['params'] = $this -> getCustomBlockInfo($iBlockId, $aBlock);
		}			
		return $aResult;
	}	
	
	function getStandardBlockInfo($iBlockId, $aValue){
		$aBlocks = $this -> getRow("SELECT 
											IF (`a`.`unmovable` IS NULL, '0',`a`.`unmovable`) as `unmovable`,
											IF (`a`.`irremovable` IS NULL, '0',`a`.`irremovable`) as `irremovable`,
											IF (`a`.`uncollapsable` IS NULL, '0',`a`.`uncollapsable`) as `uncollapsable`,
											IF (`a`.`collapsed_def` IS NULL, '0',`a`.`collapsed_def`) as `collapsed_def`,
											IF (`a`.`visible_group` IS NULL, '1',`a`.`visible_group`) as `vm`,
											`c`.*											
									FROM `sys_page_compose` as `c` 
									LEFT JOIN `{$this->_sPrefix}standard_blocks` as `a` ON `a`.`id` = `c`.`ID`
									WHERE `c`.`Page` = 'profile' AND `c`.`Column` > 0 AND `c`.`ID` = '{$iBlockId}' LIMIT 1");	
									
	    if (empty($aBlocks)) return false;
		
		$aResult = array(
							'Func' =>   $aBlocks['Func'],
					        'Content' => $aBlocks['Content'],
					        'Caption' => $aBlocks['Caption'],
					        'Visible' => $aBlocks['Visible'],
					        'DesignBox' => $aBlocks['DesignBox'],
							'cp' => $aBlocks['uncollapsable'],
							'rm' => $aBlocks['irremovable'],
							'mv' => $aBlocks['unmovable'],
							'cd' => $aValue['cd'],
							'type' => 's'
						);
						
		return $aResult;
	}	
	
	function getCustomBlockInfo($iBlockId, $aValue){
		$aBlocks = $this -> getRow("SELECT 
										   `c`.`id`,
										   `c`.`order`,
		                                   `c`.`column`,
										   `c`.`content`,
										   `c`.`title`,
										   `c`.`unmovable`,
										   `c`.`irremovable`,
										   `c`.`uncollapsable`,
										   `c`.`collapsed_def`, 
										   `c`.`visible_group` as `vm`,
										   `c`.`visible`,
										   `c`.`owner_id`,
										   `c`.`share`,
										   `c`.`type`
									FROM `{$this->_sPrefix}members_blocks` as `c`
									WHERE `id` = '{$iBlockId}' LIMIT 1");
		if (empty($aBlocks)) return false;
		
		$aResult = array(
							'Func' =>   ($aBlocks['type'] == 'rss' ? 'RSS' : 'Echo'),
					        'Content' => ($aBlocks['type'] == 'rss' ? $aBlocks['content'].'#'.$this -> _oConfig -> getRssNumber() : $aBlocks['content']), 
					        'Caption' => $aBlocks['title'],
					        'Visible' => $aBlocks['visible'],
					        'DesignBox' => '1',
							'vm' => $aValue['vm'],
							'cp' => $aBlocks['uncollapsable'],
							'rm' => $aBlocks['irremovable'],
							'mv' => $aBlocks['unmovable'],
							'cd' => $aValue['cd'],
							'owner' => 	$aBlocks['owner_id'],
							'type' => 'c'
						);

		if ($this -> _oConfig -> isShareBlocksAllowed() && $aBlocks['owner_id'] == getLoggedId()) $aResult['sh'] = $aBlocks['share']; 
		
		return $aResult;
	}	
	
	function changeBlocksOrder($sValues, $iProfileId){
	  if (strlen($sValues) < 3 ) return false;
	  $aCols = split(',', $sValues);
	
	  $iCol = 1;
	  $aPBlocks = $this -> getProfileBlocksAsArray($iProfileId);
	  foreach($aCols as $k => $v){
		$aBlocks = preg_split('/[|]/', $v, -1, PREG_SPLIT_NO_EMPTY); 
		$iRow = 0;
		foreach($aBlocks as $i => $block){
		   $aT_Id = split('_', $block);
		   if (isset($aPBlocks[$aT_Id['0']][(int)$aT_Id['1']])) 
		   {
				$aPBlocks[$aT_Id[0]][(int)$aT_Id[1]]['rw'] = $iRow++;
				$aPBlocks[$aT_Id[0]][(int)$aT_Id[1]]['cl'] = $iCol;
		   }	
		}
		$iCol++;
	  }	  
	   return $this -> serializeProfileBlocks($aPBlocks, $iProfileId); 		
	}
	
	
	function changeShare($iPId, $sId){
		$aId = $this -> getBlockIdByPost($sId);
		
		if ($aId['type'] == 'c')
	    return $this -> query("UPDATE `{$this->_sPrefix}members_blocks` SET `share` = IF (`share` = '1', '0', '1') WHERE `id` = '{$aId['id']}' AND `owner_id` = '{$iPId}' LIMIT 1");
		
		return false;
	}
	
	function changeReflecting($iPId, $sId, $sAction = 'cd'){
		$aId = $this -> getBlockIdByPost($sId);		
		if ($aId['id'])
		{
			$aPBlocks = $this -> getProfileBlocksAsArray($iPId);
			
			if (isset($aPBlocks[$aId['type']][$aId['id']]))
			{
				if ((int)$aPBlocks[$aId['type']][$aId['id']][$sAction]) $aPBlocks[$aId['type']][$aId['id']][$sAction] = '0';
				else $aPBlocks[$aId['type']][$aId['id']][$sAction] = '1';
				return $this -> serializeProfileBlocks($aPBlocks, $iPId); 		
			}	
		}	
		return false;
	}
	
	function removeBlock($iPId, $sId){
		$aId = $this -> getBlockIdByPost($sId);		
		if ($aId['id'] && !($aId['type'] == 's' && !(int)$this -> _oConfig -> isStandardBlocksDeleteAllowed() && !isAdmin()))
		{
			$aPBlocks = $this -> getProfileBlocksAsArray($iPId);
			if (isset($aPBlocks[$aId['type']][$aId['id']]) && is_array($aPBlocks[$aId['type']][$aId['id']])) unset($aPBlocks[$aId['type']][$aId['id']]);
			return $this -> serializeProfileBlocks($aPBlocks, $iPId); 		
		}	
		return false;
	}
	
	function removeBlockFromTheSite($iPId, $iId){
		if ((int)$iId) return $this -> query("DELETE FROM `{$this->_sPrefix}members_blocks` WHERE `id` = '{$iId}' AND `owner_id` = '{$iPId}'"); 
		return false;
	}
	
	function addBlockToProfile($iProfileID, $iBlockId, $sType = 'c'){
		$aResult = $this -> getBlockInfoForAddToProfile($iProfileID, $iBlockId, $sType); 
		
		$aPBlocks = $this -> getProfileBlocksAsArray($iProfileID);

		if (!isset($aPBlocks[$sType][$iBlockId])) 
		{
			$aPBlocks[$sType][$iBlockId] = $aResult[$iBlockId];
			return $this -> serializeProfileBlocks($aPBlocks, $iProfileID); 		
		}	

	    return false;	
	}
	
	function getStandardBlockPrivacy($iPId, $iBlockId){
		return (int)$this -> getOne("SELECT `allow_view_block_to` FROM `sys_page_compose_privacy` WHERE `block_id` = '{$iBlockId}' AND `user_id` = '{$iPId}' LIMIT 1");
	}
	
	function getBlockInfoForAddToProfile($iProfileID, $iBlockId, $sType = 'c'){
		if ($sType == 'c')
		{
			$aBlocks = $this -> getRow("SELECT 
										   `c`.`id` as `ID`,
										   `c`.`order` as `Order`,
		                                   `c`.`column` as `Column`,
										   `c`.`unmovable`,
										   `c`.`irremovable`,
										   `c`.`uncollapsable`,
										   `c`.`collapsed_def`, 
										   `c`.`visible_group` as `vm`
									FROM `{$this->_sPrefix}members_blocks` as `c`
									WHERE `c`.`id` = '{$iBlockId}' LIMIT 1");
	    }
		elseif($sType == 's')
		{
			$aBlocks = $this -> getRow("SELECT 	
											IF (`a`.`unmovable` IS NULL, '0',`a`.`unmovable`) as `unmovable`,
											IF (`a`.`irremovable` IS NULL, '0',`a`.`irremovable`) as `irremovable`,
											IF (`a`.`uncollapsable` IS NULL, '0',`a`.`uncollapsable`) as `uncollapsable`,
											IF (`a`.`collapsed_def` IS NULL, '0',`a`.`collapsed_def`) as `collapsed_def`,
											IF (`p`.`allow_view_block_to` IS NULL, IF (`a`.`visible_group` IS NULL, '1',`a`.`visible_group`), `p`.`allow_view_block_to`) as `vm`,
											`c`.`ID` as `ID`, `c`.`Order`, `c`.`Column`		
									FROM `sys_page_compose` as `c` 
									LEFT JOIN `sys_page_compose_privacy` as `p` ON `c`.`ID` = `p`.`block_id` AND `user_id` = '{$iProfileID}'
									LEFT JOIN `{$this->_sPrefix}standard_blocks` as `a` ON `a`.`id` = `c`.`ID`
									WHERE `c`.`Page` = 'profile' AND `c`.`Column` > 0 AND `c`.`ID` = '{$iBlockId}' LIMIT 1");
									
		}
		if (!is_array($aBlocks)) return false;	
		
		$aResult = array();

		$aResult[$aBlocks['ID']]['rw'] = $aBlocks['Order']; //row
		$aResult[$aBlocks['ID']]['cl'] = $aBlocks['Column'];//col
		$aResult[$aBlocks['ID']]['vm'] = $aBlocks['vm'];//visible for members group			
		$aResult[$aBlocks['ID']]['cp'] = $aBlocks['uncollapsable'];//collapsable
		$aResult[$aBlocks['ID']]['cd'] = $aBlocks['collapsed_def'];//collapsable
		$aResult[$aBlocks['ID']]['rm'] = $aBlocks['irremovable'];//removable
		$aResult[$aBlocks['ID']]['mv'] = $aBlocks['unmovable'];//movable
		
		return $aResult;
	}
	
	function getBlockIdByPost($sId){
		if (strpos($sId, $this -> _oConfig -> getCBPrefix()) !== false) 
		{
			$sType = 'c';
			$iId = (int)str_replace($this -> _oConfig -> getCBPrefix(), '', $sId);
		}	
		else 
		{
			$sType = 's';
			$iId = (int)$sId;
		}
		
		return array('type' => $sType, 'id' => (int)$iId); 	
	}
	
	function getBlockInfo($iBlockId, $sType = 'c'){
		if ($sType == 'c')
			return $this -> getRow("SELECT * FROM `{$this->_sPrefix}members_blocks` WHERE `id` = '{$iBlockId}' LIMIT 1");
		else
			return $this -> getRow("SELECT * FROM `{$this->_sPrefix}standard_blocks` WHERE `id` = '{$iBlockId}' LIMIT 1");
	}
	
	function getProfileColumns(){
	   return $this -> getAll("SELECT `Column` FROM `sys_page_compose` WHERE `Page` = 'profile' AND `Column` > 0 GROUP BY `Column`");
	}	

	function updateMembersColumns($iPId){
		$aBlocks = $this -> getProfileBlocksAsArray($iPId);
		$iCounter = count($this -> getProfileColumns());
		
		foreach($aBlocks as $k => $v)
		      foreach($v as $key => $value)
				  if ((int)$value['cl'] > (int)$iCounter) unset($aBlocks[$k][$key]); 				
		
		$this -> serializeProfileBlocks($aBlocks, $iPId);
	}	
	
	function deleteBlock($iId){
		return $this -> query("DELETE FROM `{$this->_sPrefix}members_blocks` WHERE `id` = '{$iId}'");
	}
	
	function checkIfMembersBlocksLimitExceed($iPId){
		return (int)$this -> getOne("SELECT COUNT(*) FROM `{$this->_sPrefix}members_blocks` WHERE `owner_id` = '{$iPId}'") >= $this -> _oConfig -> getNumberOfAllowedBlocksForMember() ;
	}	
	
	function createCustomBlock($iPId, &$aValues, $iUBlockId = 0){
		if (call_user_func(array($this -> _oConfig, 'is' . strtoupper($aValues['type']) . 'BlockAllowed'))) 
		{
			$sTitle = substr(process_db_input(strip_tags($aValues['title']), BX_TAGS_SPECIAL_CHARS), 0, $this -> _oConfig -> getMaxCharsForTitle());
			
			if ($aValues['type'] == 'text')
				$sContent = process_db_input($aValues['body'], BX_TAGS_VALIDATE);
			else 
				$sContent = process_db_input($aValues['body']);
			
			$sContent = substr($sContent, 0, $this -> _oConfig -> getMaxCharsForBody());
			
			$sAutoApprove = '';
			if (call_user_func(array($this -> _oConfig, 'isAutoApprove' . strtoupper($aValues['type']) . 'Block'))) $sAutoApprove = ",`approved` = '1'"; else $sAutoApprove = ",`approved` = '0'";
						
			$aProfile = getProfileInfo($iPId);
			$sPrivacy = (int)$aProfile['PrivacyDefaultGroup'] ? $aProfile['PrivacyDefaultGroup'] : 3 ; 
			
			$sShare = '';
			if ($this -> _oConfig -> isShareBlocksAllowed()) $sShare = ", `share` = '1'";  
		
			if (!(int)$iUBlockId)
			{	
				$this -> query("INSERT INTO `{$this->_sPrefix}members_blocks` SET `visible_group` = '{$sPrivacy}', `type` = '{$aValues['type']}', `date` = UNIX_TIMESTAMP(), `owner_id` = '{$iPId}', `content` = '{$sContent}', `title` = '{$sTitle}' {$sAutoApprove} {$sShare}");
				$iBlockID =  $this -> lastId();
				if ($iBlockID && $this -> addBlockToProfile($iPId, $iBlockID)) return $iBlockID;
			}else
			{
				$bResult = $this -> query("UPDATE `{$this->_sPrefix}members_blocks` SET `date` = UNIX_TIMESTAMP(), `content` = '{$sContent}', `title` = '{$sTitle}' {$sAutoApprove} WHERE `id` = '{$iUBlockId}' AND `owner_id` = '{$iPId}'");
				if ($bResult) return $iUBlockId;
			}
		}	
	
		return false;
	}
	
	
	function updateDefaultStructure($iPId, $aInfo){
		$aBlocks = $this -> getProfileBlocksAsArray($iPId);
		if (isset($aBlocks[$aInfo['type']][$aInfo['id']])){
			$aResult = $this -> getBlockInfoForAddToProfile($iPId, $aInfo['id'], $aInfo['type']); 
			$aBlocks[$aInfo['type']][$aInfo['id']]['vm'] = $aResult[$aInfo['id']]['vm'];
			$aBlocks[$aInfo['type']][$aInfo['id']]['cp'] = $aResult[$aInfo['id']]['cp'];
			$aBlocks[$aInfo['type']][$aInfo['id']]['cd'] = $aResult[$aInfo['id']]['cd'];
			$aBlocks[$aInfo['type']][$aInfo['id']]['rm'] = $aResult[$aInfo['id']]['rm'];
			$aBlocks[$aInfo['type']][$aInfo['id']]['mv'] = $aResult[$aInfo['id']]['mv'];
			$this -> serializeProfileBlocks($aBlocks, $iPId);
		}
		return true;
	}
	
	function saveBlock(&$aValues){
		if (!isset($aValues['block_id']))
		  $aBlockInfo =array('type' => 'c', 'id' => 0);
		else
		  $aBlockInfo = $this -> getBlockIdByPost($aValues['block_id']);
		
		if ($aBlockInfo['type'] == 'c' || $aValues['block_id'] == 0)
			$sQuery = "`{$this->_sPrefix}members_blocks` SET ";
		elseif ($aBlockInfo['type'] != 'c')
			$sQuery = "`{$this->_sPrefix}standard_blocks` SET `id` = '{$aBlockInfo['id']}', ";
			
		if (!empty($aValues['block_title'])) $sQuery .= "`title` = '".process_db_input($aValues['block_title'])."', " ;
		if (!empty($aValues['block_body'])) $sQuery .= "`content` = '".process_db_input($aValues['block_body'])."', " ;
		
		$sQuery .= "`visible_group` = '".((int)$aValues['allow_view_block_to'])."', ";
		
		if (!empty($aValues['block_type'])) $sQuery .= "`type` = '".$aValues['block_type']."', " ;
		
		if (isset($aValues['block_attrs']) && is_array($aValues['block_attrs']))
		{		
		    if ($aBlockInfo['type'] == 'c' && in_array('share', $aValues['block_attrs'])) $sQuery .= "`share` = '1', " ; else if ($aBlockInfo['type'] == 'c') $sQuery .= "`share` = '0', ";
			if (in_array('irremovable', $aValues['block_attrs'])) $sQuery .= "`irremovable` = '1', " ; else $sQuery .= "`irremovable` = '0', "; 			
			if (in_array('uncollapsable', $aValues['block_attrs'])) $sQuery .= "`uncollapsable` = '1', " ; else $sQuery .= "`uncollapsable` = '0', ";
			if (in_array('collapsable_def', $aValues['block_attrs'])) $sQuery .= "`collapsed_def` = '1', " ; else $sQuery .= "`collapsed_def` = '0', ";
		} 
		else $sQuery .= ($aBlockInfo['type'] == 'c' ? "`share` = '0'," : '') . " `irremovable` = '0', `uncollapsable` = '0', `collapsed_def` = '0', ";
		
		if (!empty($aValues['unmovable'])) {
			$sQuery .= "`unmovable` = '1', " ;
			
			if ($aBlockInfo['type'] == 'c' ){
				
				$sQuery .= "`column` = '{$aValues['column_num']}', ";
				
				if ($aValues['column_pos'] == 'top')
					$sQuery .= "`order` = '1', ";
				else
					$sQuery .= "`order` = '1000', ";
			}	
		} else $sQuery .= "`unmovable` = '0', " ;
		
		$sQuery = substr($sQuery,0,-2);
		
		if ((int)$aBlockInfo['id']) 
		{
			if ($this -> checkBlockExist($aBlockInfo['id'], $aBlockInfo['type']))
					$sQuery = "UPDATE ". $sQuery . " WHERE `id` = '{$aBlockInfo['id']}'";
			else $sQuery = "INSERT INTO ". $sQuery;
		}else $sQuery = "INSERT INTO ". $sQuery . ( $aBlockInfo['type'] == 'c' ? ", date = UNIX_TIMESTAMP(), `owner_id` = '{$this -> _oConfig -> _isAdmin}'" : '');
	
		return $this -> query($sQuery) && $this -> updateDefaultStructure(0, $aBlockInfo);
	}
	
	function getSettingsCategory () {
       return (int)$this -> getOne("SELECT `ID` FROM `sys_options_cats` WHERE `name` = 'AQB Profile Composer' LIMIT 1");
    }
	
	function approveBlock($iId){
	  return (int)$this -> query("UPDATE `{$this->_sPrefix}members_blocks` SET `approved` = IF (`approved` = '1', '0', '1') WHERE `id` = '{$iId}' ");
	}
					   
   function getBlocks($aParams){
	$sSelectClause = $sJoinClause = $sWhereClause = $sOrderBy = $sHaving = '';
	
	if (empty($aParams['view_order'])) $sOrderBy = '`tp`.`ID` ASC'; 
		else $sOrderBy = "`{$aParams['view_order']}` {$aParams['view_type']}"; 
   
	if (!empty($aParams['filter_params'])) 
	{
	    $aFilterParams = preg_split('/[,]/', $aParams['filter_params'], -1, PREG_SPLIT_NO_EMPTY);
		
		if (is_array($aFilterParams))
		{
			$aFields = array();
			
			if (in_array('block_id', $aFilterParams)) $aFields[]= "`b`.`id` LIKE '%{$aParams['filter']}%'";	
			if (in_array('block_title', $aFilterParams)) $aFields[]= "`b`.`title` LIKE '%{$aParams['filter']}%'";
			if (in_array('block_body', $aFilterParams)) $aFields[]= "`b`.`content` LIKE '%{$aParams['filter']}%'";
			if (in_array('type', $aFilterParams)) $aFields[]= "`b`.`type` LIKE '%{$aParams['filter']}%'";
			if (in_array('nickname', $aFilterParams)) $aFields[]= "`p`.`NickName` LIKE '%{$aParams['filter']}%'";
			if (in_array('email', $aFilterParams)) $aFields[]= "`p`.`Email` LIKE '%{$aParams['filter']}%'";
			if (in_array('headline', $aFilterParams)) $aFields[]= "`p`.`Headline` LIKE '%{$aParams['filter']}%'";
			if (in_array('tags', $aFilterParams)) $aFields[]= "`p`.`tags` LIKE '%{$aParams['filter']}%'";
			
			if (in_array('approved', $aFilterParams)) $aFields[]= "`b`.`approved` = '{$aParams['filter']}'";
						
			if (count($aFields) > 0 ) $sWhereClause .= " AND (".implode(' OR ', $aFields).")"; 
		}
	}	
	
	$sQuery = " 
			SELECT
    		`b`.`id` as `id`,
    		`p`.`NickName` AS `username`,
    		`p`.`Headline` AS `headline`,
			`b`.`type` AS `type`,
			`b`.`title` AS `title`,
			`b`.`approved` AS `approved`,
			DATE_FORMAT(FROM_UNIXTIME(`b`.`date`),'" . $this -> _oConfig-> getDateFormat() . "' ) AS `date`,
			`b`.`share` AS `shared`,
		    `p`.`Email` AS `email`,
			DATE_FORMAT(`p`.`DateLastLogin`,  '" . $this -> _oConfig-> getDateFormat() . "' ) AS `last_login`
        	FROM `{$this -> _sPrefix}members_blocks` AS `b` LEFT JOIN `Profiles` AS `p` ON `b`.`owner_id` = `p`.`ID`
		   	WHERE  1 " . $sWhereClause . $sGroupClause . "
	    	ORDER BY  {$sOrderBy}
	    	LIMIT " . $aParams['view_start'] . ", " . $aParams['view_per_page'].'';
		
		return $this -> getAll($sQuery);
	}
	
	function withPointsIntegration(){
		$iId = (int)$this -> getOne("SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'aqb_points_assign_alert' LIMIT 1");
		
		if ($iId) {	
			$this -> query("REPLACE INTO `aqb_points_modules` VALUES ('aqb_pcomposer', 'true')");
			$this -> query("INSERT INTO `sys_alerts` VALUES
			(NULL, 'aqb_pcomposer', 'edit', '{$iId}'),
			(NULL, 'aqb_pcomposer', 'add', '{$iId}'),
			(NULL, 'aqb_pcomposer', 'remove', '{$iId}'),
			(NULL, 'aqb_pcomposer', 'approved', '{$iId}'),
			(NULL, 'aqb_pcomposer', 'disapproved', '{$iId}')");
			
			$this -> query("INSERT INTO `aqb_points_actions` VALUES
			(NULL, '_aqb_points_action_aqb_pcomposer_edit', '', 'edit', 'aqb_pcomposer', 1, 100, 'true', 'aqb_pcomposer', 'first', 'aqb_pcomposer'),
			(NULL, '_aqb_points_action_aqb_pcomposer_add', '', 'add', 'aqb_pcomposer', 1, 100, 'true', 'aqb_pcomposer', 'first', 'aqb_pcomposer'),
			(NULL, '_aqb_points_action_aqb_pcomposer_remove', '', 'remove', 'aqb_pcomposer', -1, 100, 'true', 'aqb_pcomposer', 'first', 'aqb_pcomposer'),
			(NULL, '_aqb_points_action_aqb_pcomposer_approved', '', 'approved', 'aqb_pcomposer', 1, 100, 'true', 'aqb_pcomposer', 'first', 'aqb_pcomposer'),
			(NULL, '_aqb_points_action_aqb_pcomposer_disapproved', '', 'disapproved', 'aqb_pcomposer', -1, 100, 'true', 'aqb_pcomposer', 'first', 'aqb_pcomposer')");

		}
	}
	
	function uninstallPointsIntegration(){
		$this -> query("DELETE FROM `sys_alerts` WHERE `unit` = 'aqb_pcomposer'");
		
		if ($this -> getOne("SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'aqb_points_assign_alert' LIMIT 1")) 
			$this -> query("DELETE FROM `aqb_points_actions` WHERE `module_uri` = 'aqb_pcomposer'");
	}	
}
?>