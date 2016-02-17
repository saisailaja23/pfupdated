<?php

require_once(BX_DIRECTORY_PATH_ROOT . K_SYS_PATH . 'KView.php');

class PlacesView extends KView
{    
    function PlacesView ()
    {
        parent::KView();
    }

    function display ($sTmplName, $data = array ())
    {            
        extract ($data);
        $this->_load ('HelperHtml');
        $html = &$this->helperhtml;
        include (BX_DIRECTORY_PATH_ROOT . K_APP_PATH . 'template/' . $sTmplName . '.php');
    }    
}

?>
