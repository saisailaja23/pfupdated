<?php
/***************************************************************************
* Date				: Sat Dec 19, 2009
* Copywrite			: (c) 2009 by Dean J. Bassett Jr.
* Website			: http://www.deanbassett.com
*
* Product Name		: Privacy Page Editor
* Product Version	: 1.1.0000
*
* IMPORTANT: This is a commercial product made by Dean Bassett Jr.
* and cannot be modified other than personal use.
*  
* This product cannot be redistributed for free or a fee without written
* permission from Dean Bassett Jr.
*
***************************************************************************/

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolModuleDb.php' );


/*
* Quotes module Data
*/
class BxBlockToolsDb extends BxDolModuleDb {	
	var $_oConfig;
	/*
	* Constructor.
	*/
	function BxBlockToolsDb(&$oConfig) {
		parent::BxDolModuleDb();

		$this->_oConfig = $oConfig;
	}

	function getDefaultLang() {
		$l = $this->getOne("SELECT `VALUE` FROM `sys_options` where `Name`='lang_default'");
		$id = $this->getOne("SELECT `ID` FROM `sys_localization_languages` where `Name`='$l'");
		return $id;
	}

	function getLangKeys() {
		return $this->getAll("SELECT * FROM `sys_localization_languages`");
	}

	function getPageData($sPage) {
		return $this->getRow("SELECT * FROM `bx_BlockTools_data` WHERE `pagename`='$sPage'");
	}


/* xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx */

	function getPages() {
		return $this->getAll("SELECT * FROM `sys_page_compose_pages`");
	}

	function getActiveBlocks($sPage) {
		return $this->getAll("SELECT * FROM `sys_page_compose` WHERE `Page`='$sPage' AND `Column`>0 ORDER BY `Column`,`Order`");
	}

	function getActiveBlocksForCol($sPage,$iCol) {
		return $this->getAll("SELECT * FROM `sys_page_compose` WHERE `Page`='$sPage' AND `Column`='$iCol' ORDER BY `Order`");
	}

	function getInActiveBlocks($sPage) {
		return $this->getAll("SELECT * FROM `sys_page_compose` WHERE `Page`='$sPage' AND `Column`=0 AND `Func`!='Sample' ORDER BY `Column`,`Order`");
	}

	function getPHPBlocks() {
		return $this->getAll("SELECT * FROM `sys_page_compose` WHERE `Func`='PHP' ORDER BY `Order`");
	}

	function getHTMLBlocks() {
		return $this->getAll("SELECT * FROM `sys_page_compose` WHERE `Func`='Echo' ORDER BY `Order`");
	}

	function getPHPBlockData($iBlockID) {
		return $this->getRow("SELECT * FROM `sys_page_compose` WHERE `ID`='$iBlockID'");
	}

	function getHTMLBlockData($iBlockID) {
		return $this->getRow("SELECT * FROM `sys_page_compose` WHERE `ID`='$iBlockID'");
	}

	function getAllBlocks() {
		return $this->getAll("SELECT * FROM `sys_page_compose` WHERE `Column`>0");
	}

	function saveDesignBox($iID,$iBox) {
		$sQuery = "UPDATE `sys_page_compose` SET `DesignBox`='$iBox' WHERE `ID`='$iID'";
		$this->query($sQuery);
	}

	function copyBlock($fromID,$toPage) {
		$sQuery = "INSERT INTO `sys_page_compose` (`Page`,`PageWidth`,`Desc`,`Caption`,`Column`,`Order`,`Func`,`Content`,`DesignBox`,`ColWidth`,`Visible`,`MinWidth`) SELECT '$toPage',`PageWidth`,`Desc`,`Caption`,'0','0',`Func`,`Content`,`DesignBox`,`ColWidth`,`Visible`,`MinWidth` FROM `sys_page_compose` WHERE `ID`='$fromID'";
		$this->query($sQuery);
	}

	function getLastID() {
		return $this->lastId();
	}

	function getTitle($sPage) {
		return $this->getOne("SELECT `Title` FROM `sys_page_compose_pages` where `Name`='$sPage'");
	}

	function getNumCols($sPage) {
		//get columns
		$sQuery = "SELECT `Column`,	`ColWidth` FROM `sys_page_compose` WHERE `Page` = '$sPage' AND `Column` > 0 GROUP BY `Column` ORDER BY `Column`";
		return $this->query($sQuery);
	}
	function insertPHPBlock($sPage,$sKey,$sText,$sCode) {
		$query = "INSERT INTO `sys_page_compose` (`Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`) VALUES ('$sPage', '998px', '$sText', '$sKey', 0, 0, 'PHP', '$sCode', 1, 66, 'non,memb', 0)";
		$this->query($query);
	}

	function updatePHPBlock($iBlockID,$sLkey,$sPHPCode) {
		$query = "UPDATE `sys_page_compose` SET `Caption`='$sLkey', `Content`='$sPHPCode' WHERE `ID`='$iBlockID'";
		$this->query($query);
	}
	function deleteBlock($iBlockID) {
		$query = "DELETE FROM `sys_page_compose` WHERE `ID`='$iBlockID'";
		$this->query($query);
	}

	function insertHTMLBlock($sPage,$sKey,$sText,$sCode) {
		$query = "INSERT INTO `sys_page_compose` (`Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`) VALUES ('$sPage', '998px', '$sText', '$sKey', 0, 0, 'Echo', '$sCode', 1, 66, 'non,memb', 0)";
		$this->query($query);
	}

	function updateHTMLBlock($iBlockID,$sLkey,$sHTMLCode) {
		$query = "UPDATE `sys_page_compose` SET `Caption`='$sLkey', `Content`='$sHTMLCode' WHERE `ID`='$iBlockID'";
		$this->query($query);
	}

	function keyExists($sLKey) {
		return $this->getOne("SELECT `ID` FROM `sys_localization_keys` where `Key`='$sLKey'");
	}

	function getLangCat() {
		return $this->getOne("SELECT `ID` FROM `sys_localization_categories` where `Name`='Deano'");
	}

	function getBlockName($iID) {
		return $this->getOne("SELECT `Caption` FROM `sys_page_compose` where `ID`='$iID'");
	}

}

?>