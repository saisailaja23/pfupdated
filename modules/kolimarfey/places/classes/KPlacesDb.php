<?

bx_import('BxDolModuleDb');

class KPlacesDb extends BxDolModuleDb {

	function KPlacesDb(&$oConfig) {
		parent::BxDolModuleDb();
        $this->_sPrefix = $oConfig->getDbPrefix();
    }
}

?>
