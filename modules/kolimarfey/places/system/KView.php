<?php

require_once(BX_DIRECTORY_PATH_ROOT . K_SYS_PATH . 'KBase.php');

class KView extends KBase
{    
    function KView ()
    {     
        parent::KBase();   
    }
    
    function show404 ()
    {
        header("HTTP/1.1 404 Not Found");
        echo "404. page Not Found";
        exit;
    }

    function showAccessDenied ()
    {
        echo 'Access denied';
        exit;
    }
    
    function _load ($sClass)
    {
        parent::_load ($sClass, 'View');
    }    
    
    function display ($sTmplName, $data = array ())
    {        
        $html = $this->_load ('HelperHtml');
        extract ($data);
        include (BX_DIRECTORY_PATH_ROOT . K_APP_PATH . 'template/' . $sTmplName . '.php');
    }

}

?>
