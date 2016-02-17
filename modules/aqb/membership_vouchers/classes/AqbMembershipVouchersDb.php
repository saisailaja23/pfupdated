<?php
/***************************************************************************
*
*     copyright            : (C) 2011 AQB Soft
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

class AqbMembershipVouchersDb extends BxDolModuleDb {
	/*
	 * Constructor.
	 */
	var $_oConfig;
	function AqbMembershipVouchersDb(&$oConfig) {
		parent::BxDolModuleDb($oConfig);
		$this->_oConfig = $oConfig;
	}
	function getMembershipsDetails() {
        $sSql = "SELECT
	                `tl`.`ID` AS `mem_id`,
	                `tl`.`Name` AS `mem_name`,
	                `tl`.`Icon` AS `mem_icon`,
	                `tl`.`Description` AS `mem_description`,
	                `tlp`.`id` AS `price_id`,
	                `tlp`.`Days` AS `price_days`,
	                `tlp`.`Price` AS `price_amount`
	            FROM `sys_acl_levels` AS `tl`
	            INNER JOIN `sys_acl_level_prices` AS `tlp` ON `tl`.`ID`=`tlp`.`IDLevel`
	            WHERE `tl`.`Active`='yes' AND `tl`.`Purchasable`='yes'
	            ORDER BY `mem_id`, `price_amount`";
	   return $this->getAll($sSql);
	}
	function getCodesArray() {
		$aAllCodes = $this->getAll("SELECT `ID`, `PriceID`, `Code`, `Discount`, UNIX_TIMESTAMP(`Start`) AS `Start`, UNIX_TIMESTAMP(`End`) AS `End`, `SingleUse`, `Used`, `Threshold` FROM `{$this->_sPrefix}codes` ORDER BY `Start` DESC");
		$aRetCodes = array();
		foreach ($aAllCodes as $aCode) {
			$aRetCodes[$aCode['PriceID']][$aCode['ID']] = $aCode;
		}
		return $aRetCodes;
	}
	function getCodeDetails($iCode) {
		return $this->getRow("SELECT * FROM `{$this->_sPrefix}codes` WHERE `ID` = {$iCode} LIMIT 1");
	}
	function isCodeExists($sCode) {
		return $this->getOne("SELECT COUNT(*) FROM `{$this->_sPrefix}codes` WHERE `Code` = '".process_db_input($sCode)."'");
	}
	function saveCode($aCode) {
		$sQuery = '';
		if ($aCode['ID'] > 0) {
			$sQuery = "
				UPDATE `{$this->_sPrefix}codes`
				SET `Code` = '{$aCode['Code']}', `Discount` = '{$aCode['Discount']}', `Start` = FROM_UNIXTIME({$aCode['Starts']}), `End` = FROM_UNIXTIME({$aCode['Ends']}), `SingleUse` = {$aCode['SingleUse']}, `Threshold` = {$aCode['Threshold']}
				WHERE `ID` = {$aCode['ID']}
				LIMIT 1";
		} else {
			$sQuery = "
				INSERT INTO `{$this->_sPrefix}codes`
				SET `PriceID` = {$aCode['PriceID']}, `Code` = '{$aCode['Code']}', `Discount` = '{$aCode['Discount']}', `Start` = FROM_UNIXTIME({$aCode['Starts']}), `End` = FROM_UNIXTIME({$aCode['Ends']}), `SingleUse` = {$aCode['SingleUse']}, `Threshold` = {$aCode['Threshold']}";
		}
		$this->query($sQuery);
	}
	function deleteCode($iCodeID) {
		$this->query("DELETE FROM `{$this->_sPrefix}codes` WHERE `ID` = {$iCodeID} LIMIT 1");
	}
	function getCodeDiscount($iPricingID, $sCode) {
		$sCode = addslashes($sCode);
		$aDiscount = $this->getRow("SELECT `Discount`, `SingleUse` FROM `{$this->_sPrefix}codes` WHERE `PriceID` = {$iPricingID} AND `Code` = '{$sCode}' AND NOW() > `Start` AND `End` + INTERVAL 1 DAY > NOW() AND (`Used` < `Threshold` OR `Threshold` = 0) LIMIT 1");
		if ($aDiscount['SingleUse']) {
			//check processed
			$bIsUsed = $this->getOne("SELECT COUNT(*) FROM `{$this->_sPrefix}transactions` WHERE `ProfileID` = ".getLoggedId()." AND `PriceID` = {$iPricingID} AND `Code` = '{$sCode}' AND `Status` = 'Processed'");
			if ($bIsUsed) return 0;

			//check pending (still in cart)
			$sCart = $this->getOne("SELECT `items` FROM `bx_pmt_cart` WHERE `client_id` = ".getLoggedId()." LIMIT 1");
			$aCartItems = explode(':', $sCart);
			$aOurItemsInCart = array();
			foreach ($aCartItems as $sItem) {
				$aItem = explode('_', $sItem);
				if ($aItem[0] == 0 && $aItem[1] == $this->_oConfig->getId()) {
					$aOurItemsInCart[$aItem[2]] = 1;
				}
			}

			$aTransactions = $this->getAll("SELECT `ID` FROM `{$this->_sPrefix}transactions` WHERE `ProfileID` = ".getLoggedId()." AND `PriceID` = {$iPricingID} AND `Code` = '{$sCode}' AND `Status` = 'Pending'");
			foreach ($aTransactions as $aTransaction) {
				if ($aOurItemsInCart[$aTransaction['ID']]) return 0; //this item is still in the cart
				$this->query("DELETE FROM `{$this->_sPrefix}transactions` WHERE `ID` = {$aTransaction['ID']} LIMIT 1");
			}
			return $aDiscount['Discount'];
		} else {
			return $aDiscount['Discount'];
		}
	}
	function isPricingOptionExists($iPricingOption) {
		return $this->getOne("SELECT COUNT(*) FROM `sys_acl_level_prices` WHERE `id` = {$iPricingOption} LIMIT 1");
	}

	function createCartItem($iProfile, $iPricingOption, $iDiscount, $sCode) {
		$this->query("INSERT INTO `{$this->_sPrefix}transactions` (`ProfileID`, `PriceID`, `Discount`, `Code`) VALUES ({$iProfile}, {$iPricingOption}, {$iDiscount}, '{$sCode}')");
		return $this->lastId();
	}
	function getInternalItem($iItemId, $iProfileId) {
		$iItemId = intval($iItemId);
		$iProfileId = intval($iProfileId);
		return $this->getRow("SELECT * FROM `{$this->_sPrefix}transactions` WHERE `ID` = {$iItemId} AND `ProfileID` = '{$iProfileId}' LIMIT 1");
	}
	function processItem($iItemId) {
		$this->query("UPDATE `{$this->_sPrefix}transactions` SET `Status` = 'Processed' WHERE `ID` = {$iItemId} LIMIT 1");
		$aCodeInfo = $this->getRow("SELECT `PriceID`, `Code` FROM `{$this->_sPrefix}transactions` WHERE `ID` = {$iItemId} LIMIT 1");
		$this->query("UPDATE `{$this->_sPrefix}codes` SET `Used` = `Used` + 1 WHERE `PriceID` = {$aCodeInfo['PriceID']} AND `Code` = '{$aCodeInfo['Code']}' LIMIT 1");
	}
	function cleanTransactions($iProfileID) {
		$this->query("DELETE FROM `{$this->_sPrefix}transactions` WHERE `ProfileID` = {$iProfileID}");
	}
}
?>