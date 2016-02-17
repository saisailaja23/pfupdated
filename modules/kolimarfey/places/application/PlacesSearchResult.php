<?

bx_import('BxDolModule');
bx_import('BxTemplSearchResultText');

class PlacesSearchResult extends BxTemplSearchResultText {
	var $aCurrent = array(
		'name' => 'places',
		'title' => '_Places',
		'table' => 'places_places',
		'ownFields' => array('pl_uri', 'pl_name', 'pl_thumb', 'pl_created_f' => "pl_id`, DATE_FORMAT(`pl_created`, '%s') AS `pl_created_f`, `pl_id"),
        'searchFields' => array('pl_name', 'pl_desc', 'pl_city', 'pl_address'),
        'join' => array(
            'profile' => array(
                    'type' => 'left',
                    'table' => 'places_places_cat',
                    'mainField' => 'pl_cat',
                    'onField' => 'pl_cat_id',
                    'joinFields' => array('pl_cat_name'),
            ),
        ),        
		'restriction' => array(
		),
		'paginate' => array('perPage' => 4, 'page' => 1, 'totalNum' => 10, 'totalPages' => 1),
		'sorting' => 'last'
	);		
	var $aPermalinks;	
    var $_oModule;
	
    function PlacesSearchResult($oModule = null) {

        $this->aCurrent['ownFields']['pl_created_f'] = sprintf($this->aCurrent['ownFields']['pl_created_f'], getParam('short_date_format'));

        parent::BxTemplSearchResultText();
        
        if(!empty($oModule))
            $this->_oModule = $oModule;        
	}
	
    function displaySearchUnit($aData) {
        $this->_include();
        ob_start();
        $GLOBALS['glPlaceData'] = &$aData;
        new PlacesController ('include_display_unit');
        return ob_get_clean();
	}

	function displayResultBlock() {
	    $sResult = parent::displayResultBlock();
        if ($sResult)
            return '<div class="bx_sys_default_padding">' . $sResult . '</div>';
        return $sResult;
    }

    function getAlterOrder() {
		if ($this->aCurrent['sorting'] == 'last') 
			return array ('order' => " ORDER BY `pl_created` DESC");
	    return array();
    }    

	function addCustomParts() {
        parent::addCustomParts();
        $this->_include();
        $GLOBALS['oSysTemplate']->addCss(BX_DOL_URL_ROOT . K_APP_PATH . 'template/css/main.css');
    }    

	function _include() {
        if (!defined('K_NAME')) {
            include (BX_DIRECTORY_PATH_MODULES . 'kolimarfey/places/defines.php');
            require_once(BX_DIRECTORY_PATH_ROOT . K_APP_PATH . 'PlacesController.php');
        }
	}        
}

?>
