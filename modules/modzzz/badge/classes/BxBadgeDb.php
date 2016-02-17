<?
/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Confession
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

bx_import('BxDolTwigModuleDb');
 
 
/*
 * Badge module Data
 */
class BxBadgeDb extends BxDolTwigModuleDb {	

	var $_oConfig;
	var $month_arr;

	/*
	 * Constructor.
	 */
	function BxBadgeDb(&$oConfig) {
        parent::BxDolTwigModuleDb($oConfig);
		$this->_oConfig = $oConfig;
 
        $this->_sTableMain = 'main';
        $this->_sTableMediaPrefix = '';
        $this->_sFieldId = 'id';
        $this->_sFieldIcon = 'icon'; 
        $this->_sFieldImage = 'large_icon';  
        $this->_sFieldCreated = 'created';
  
	}
 
	function getBadge() {
		$aMemberships = getMemberships();

		$aBadge = array();
		$aDbBadge = $this->getAll("SELECT `id`, `membership_id` FROM `" . $this->_sPrefix . "main` ");
	  
		foreach ($aDbBadge as $aEachBadge){
			$iKey = (int)$aEachBadge['id'];
			$aBadge[$iKey] = $aMemberships[$aEachBadge['membership_id']];
 		} 
 
		return $aBadge;
	}

	function getMembershipsWithIcon() {
 
		$aMembership = array();
		$aDbMembership = $this->getAll("SELECT distinct `membership_id` FROM `" . $this->_sPrefix . "main` ");
	  
		foreach ($aDbMembership as $aEachMembership){
			$iKey = (int)$aEachMembership['membership_id'];
			$aMembership[$iKey] = $iKey;
 		} 
 
		return $aMembership;
	}
 
	function getBadgeByMembership($iMembershipId) {
		$iMembershipId = (int)$iMembershipId;
		
		$aBadge = $this->getRow("SELECT * FROM `" . $this->_sPrefix . "main` WHERE `membership_id`=$iMembershipId LIMIT 1");
 
		return $aBadge;
	}
  
	function getBadgeIcon($iId, $sName, $bUrlOnly=false, $bLarge=false )
	{ 
 
		$iWidth = getParam("modzzz_badge_icon_width");
		$iHeight = getParam("modzzz_badge_icon_height");  
 
		if(!$sName){
			return;
		}

		if($bLarge){  
			$sUrl = $this->_oConfig->getImagesUrl() . $sName ;
		}else{
			$sUrl = $this->_oConfig->getIconsUrl(). $sName ;
		}

		if($bUrlOnly){
			return $sUrl;
		}

		$sIcon = "<img id='img_{$iId}' src='{$sUrl}' >";  
	  
		return $sIcon;
	}
   
	function updateMembershipImg($oForm, $aValsAdd){

		$this->query("UPDATE `sys_acl_levels` SET `Icon` = '".$aValsAdd[$this->_sFieldImage]."' WHERE `ID` = ".(int)$oForm->getCleanValue('membership_id') ); 
	}

	function UpdateMedia($iEntryId, $sIcon, $sImage) {
 
		if($sIcon)
			$this->query("UPDATE `" . $this->_sPrefix . "main` SET `icon`='$sIcon' WHERE `id`='$iEntryId'"); 
	 
		if($sImage) 
			$this->query("UPDATE `" . $this->_sPrefix . "main` SET `large_icon`='$sImage' WHERE `id`='$iEntryId'");  
	}
  
	function deleteBadgeIcon ($iId) {
		$aRow = $this->getRow("SELECT `icon`, `large_icon` FROM `" . $this->_sPrefix . "main` WHERE   `id`='$iId'"); 
 		$sIcon = $aRow['icon'];
		$sLargeIcon = $aRow['large_icon']; 
  
		if(trim($sIcon)){
			$sIconURL = $this->_oConfig->getIconsPath().$sIcon; 
			if(file_exists($sIconURL)){ 
 				unlink($sIconURL);
			}
		}

		$aDefaultIcons = array('non-member.png','member.png','promotion.png');

		if(trim($sLargeIcon) && (!in_array($sLargeIcon, $aDefaultIcons))){
			$sLargeIconURL = $this->_oConfig->getImagesPath().$sLargeIcon; 
			if(file_exists($sLargeIconURL)){ 
 				unlink($sLargeIconURL);
			}	 
		} 

		if(isset($aDefaultIcons[$iId])){
			$this->query("UPDATE `sys_acl_levels` SET `Icon`='".$aDefaultIcons[$iId]."' WHERE `ID`='$iId'"); 
		}

		return $this->query("DELETE FROM `" . $this->_sPrefix . "main` WHERE `id`='$iId'"); 
	}

	function removeBadge($iProfileId, $iRemoveId) {
		$this->query("DELETE FROM `" . $this->_sPrefix . "main` WHERE `member_id`='$iProfileId' AND `id`='$iRemoveId'"); 
	}
 
 

	function getMembershipsBy($aParams = array()) {
	    $sMethod = "getAll";
	    $sSelectClause = $sJoinClause = $sWhereClause = "";
        if(isset($aParams['type']))
            switch($aParams['type']) {                
                case 'price_id':
                    $sMethod = "getRow";
                    $sSelectClause .= ", `tlp`.`id` AS `price_id`, `tlp`.`Days` AS `price_days`, `tlp`.`Price` AS `price_amount`";
                    $sJoinClause .= "LEFT JOIN `sys_acl_level_prices` AS `tlp` ON `tl`.`ID`=`tlp`.`IDLevel`";
                    $sWhereClause .= " AND `tl`.`Active`='yes' AND `tl`.`Purchasable`='yes' AND `tlp`.`id`='" . $aParams['id'] . "'";
                    break;
                case 'price_all':
                    $sSelectClause .= ", `tlp`.`id` AS `price_id`, `tlp`.`Days` AS `price_days`, `tlp`.`Price` AS `price_amount`";
                    $sJoinClause .= "INNER JOIN `sys_acl_level_prices` AS `tlp` ON `tl`.`ID`=`tlp`.`IDLevel`";
                    $sWhereClause = " AND `tl`.`Active`='yes' AND `tl`.`Purchasable`='yes'";
                    break;
                case 'level_id':
                    $sMethod = "getRow";
                    $sWhereClause .= " AND `tl`.`ID`='" . $aParams['id'] . "'";
                    break;
            }
        
        $sSql = "SELECT
                `tl`.`ID` AS `mem_id`,
                `tl`.`Name` AS `mem_name`,
                `tl`.`Icon` AS `mem_icon`,
                `tl`.`Description` AS `mem_description` " . $sSelectClause . "
            FROM `sys_acl_levels` AS `tl` " . $sJoinClause . "
            WHERE 1" . $sWhereClause;
	   return $this->$sMethod($sSql);
	}
  
  
}

?>