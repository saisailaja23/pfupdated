<?php
/***************************************************************************
* Date				: Saturday November 24, 2012
* Copywrite			: (c) 2012 by Dean J. Bassett Jr.
* Website			: http://www.deanbassett.com
*
* Product Name		: Head Injections
* Product Version	: 2.0.1
*
* IMPORTANT: This is a commercial product made by Dean Bassett Jr.
* and cannot be modified other than personal use.
*  
* This product cannot be redistributed for free or a fee without written
* permission from Dean Bassett Jr.
*
***************************************************************************/

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolModuleDb.php' );


class BxHeadInjectionsDb extends BxDolModuleDb {	
	var $_oConfig;
	function BxHeadInjectionsDb(&$oConfig) {
		parent::BxDolModuleDb();

		$this->_oConfig = $oConfig;
	}


	function getInjections() {
		return $this->getAll("SELECT * FROM `dbcsHeadInjections` order by `id`");
	}

	function getInjection($iID) {
		return $this->getRow("SELECT * FROM `dbcsHeadInjections` where `id`='$iID'");
	}

	function getInjectionByTitle($sTitle) {
		return $this->getRow("SELECT * FROM `dbcsHeadInjections` where `page_title`='$sTitle'");
	}

	function insertInjection($sTitle,$sInjections) {
		$query = "INSERT INTO `dbcsHeadInjections` (`page_title`, `injection_text`, `active`) VALUES ('$sTitle', '$sInjections', '1')";
		$this->query($query);
	}

	function deleteInjection($iID) {
		$query = "DELETE FROM `dbcsHeadInjections` WHERE `id`='$iID'";
		$this->query($query);
	}

	function updateInjection($iID,$sTitle,$sInjections,$iActive) {
		$query = "UPDATE `dbcsHeadInjections` SET `page_title`='$sTitle', `injection_text`='$sInjections', `active`='$iActive' WHERE `id`='$iID'";
		$this->query($query);
	}

}

?>