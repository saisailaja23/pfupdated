<?php

require_once(BX_DIRECTORY_PATH_ROOT . K_SYS_PATH . 'KConfig.php');

class KConfig extends KBase
{    
    function KConfig ()
    {     
        parent::KBase();   
    }

    function get ($sName)
    {        
        if (!$sName) return '';
        if (isset($this->$sName)) return $this->$sName;
        $this->_load ('Model', 'Config');
        $sVal =  $this->model->getConfigVar ($sName);
        $this->$sName = $sVal;
        return $sVal;
    }

    function set ($sName, $sValue)
    {
        $this->$sName = $sValue;
        $this->_load ('Model', 'Config');
        return $this->model->setConfigVar ($sName, $sValue);
    }    
}

?>
