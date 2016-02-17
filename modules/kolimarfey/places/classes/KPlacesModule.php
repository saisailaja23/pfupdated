<?php

bx_import('BxDolModule');
bx_import('BxDolPaginate');
bx_import('BxDolAlerts');

class KPlacesModule extends BxDolModule {

    function KPlacesModule(&$aModule) {        
        parent::BxDolModule($aModule);
    }

    function actionHome () {
    }


    function serviceGetWallPost ($aEvent) {

        $this->_include();

        ob_start();
        $GLOBALS['glPlaceEventData'] = &$aEvent;
        new PlacesController ('include_get_wall_post');
        ob_get_clean();

        $sCss = '';
        if($aEvent['js_mode'])
            $sCss = $this->_oTemplate->addCss('wall_post.css', true);
        else 
            $this->_oTemplate->addCss('wall_post.css');

        $GLOBALS['glPlaceEventReturnData']['content'] = $sCss . $this->_oTemplate->parseHtmlByName('wall_post', $GLOBALS['glPlaceEventVars']);

        return $GLOBALS['glPlaceEventReturnData'];
    }

	function serviceGetWallData () {
	    return array(
            'handlers' => array(
                array('alert_unit' => 'places', 'alert_action' => 'add', 'module_uri' => $this->_aModule['uri'], 'module_class' => 'Module', 'module_method' => 'get_wall_post')
            ),
            'alerts' => array(
                array('unit' => 'places', 'action' => 'add')
            )
	    );
    }

    function serviceGetSpyPost($sAction, $iObjectId = 0, $iSenderId = 0, $aExtraParams = array()) {

        $this->_include();

        ob_start();
        $GLOBALS['glPlaceSpyData'] = array (
            'sAction' => $sAction, 
            'iObjectId' => $iObjectId, 
            'iSenderId' => $iSenderId, 
            'aExtraParams' => $aExtraParams,
        );
        new PlacesController ('include_get_spy_post');
        ob_get_clean();

        return $GLOBALS['glPlaceSpyReturnData'];
    }
    
    function serviceGetSpyData () {
        return array(
            'handlers' => array(
                array('alert_unit' => 'places', 'alert_action' => 'add', 'module_uri' => $this->_aModule['uri'], 'module_class' => 'Module', 'module_method' => 'get_spy_post'),
                array('alert_unit' => 'places', 'alert_action' => 'change', 'module_uri' => $this->_aModule['uri'], 'module_class' => 'Module', 'module_method' => 'get_spy_post'),
                array('alert_unit' => 'places', 'alert_action' => 'add_photo', 'module_uri' => $this->_aModule['uri'], 'module_class' => 'Module', 'module_method' => 'get_spy_post'),
                array('alert_unit' => 'places', 'alert_action' => 'add_video', 'module_uri' => $this->_aModule['uri'], 'module_class' => 'Module', 'module_method' => 'get_spy_post'),
                array('alert_unit' => 'places', 'alert_action' => 'add_kml', 'module_uri' => $this->_aModule['uri'], 'module_class' => 'Module', 'module_method' => 'get_spy_post'),
                array('alert_unit' => 'places', 'alert_action' => 'rate', 'module_uri' => $this->_aModule['uri'], 'module_class' => 'Module', 'module_method' => 'get_spy_post'),
                array('alert_unit' => 'places', 'alert_action' => 'commentPost', 'module_uri' => $this->_aModule['uri'], 'module_class' => 'Module', 'module_method' => 'get_spy_post'),
            ),
            'alerts' => array(
                array('unit' => 'places', 'action' => 'add'),
                array('unit' => 'places', 'action' => 'change'),
                array('unit' => 'places', 'action' => 'add_photo'),
                array('unit' => 'places', 'action' => 'add_video'),
                array('unit' => 'places', 'action' => 'add_kml'),
                array('unit' => 'places', 'action' => 'rate'),
                array('unit' => 'places', 'action' => 'commentPost'),
            )
        );
    }

	function _include() {
        if (!defined('K_NAME')) {
            include (BX_DIRECTORY_PATH_MODULES . 'kolimarfey/places/defines.php');
            require_once(BX_DIRECTORY_PATH_ROOT . K_APP_PATH . 'PlacesController.php');
        }
	}
}

?>
