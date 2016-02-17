<?php
bx_import('BxDolInstaller');

class YnTourGuideInstaller extends BxDolInstaller {

	function YnTourGuideInstaller($aConfig) {
		parent::BxDolInstaller($aConfig);
	}

	function install($aParams) {
		$aResult = parent::install($aParams);
		return $aResult;
	}
}