<?php

require_once(BX_DIRECTORY_PATH_ROOT . K_SYS_PATH . 'KBase.php');

class KController extends KBase
{    
    function KController ($sRequest)
    {                
        parent::KBase();
        
        if (false === strpos($sRequest, '?'))
            $sUri = $sRequest;            
        else
            list($sUri, ) = split ('\?', $sRequest);
            
        if ('?' == $sUri[0])
            $sUri = substr($sUri, 1);
        
        if ('/' == $sUri[0]) 
            $sUri = substr($sUri,1);

        $a = split('/', $sUri);

        $aUrl = parse_url ($GLOBALS['site']['url']);
        $sPath = $aUrl['path'];
        $aPath = explode ('/', $sPath);
        foreach ($aPath as $k => $v) {
            if (!$v) continue;
            if ($v == $a[0])
                array_shift ($a);            
        }

        if ('places' == $a[0] || 'places.php' == $a[0])
            array_shift ($a);
        
        if (preg_match('/^[A-Za-z_]+$/', $a[0]))
        {
            $this->_call ($a[0], array_slice($a, 1));
        }
        else
        {
            $this->index();
        }        
    }
    
    function _call ($sMethod, $aParams)
    {
        if (!method_exists($this, $sMethod))
        {
            $this->_load('View');
            $this->view->show404();
        }
        else
        {
            if ($aParams)
                call_user_func_array(array($this, $sMethod), $aParams);
            else
                call_user_func(array($this, $sMethod));
        }
    }
    
    function _load ($sClass)
    {
        parent::_load ($sClass, 'Controller');
    }

    function _defineActions ($aActions, $sPrefix = 'K_')
    {
        if (defined($sPrefix . strtoupper(str_replace(' ', '_', $aActions[0]))))
            return;
        $sActions = implode("','", $aActions);        
        $res = db_res("SELECT `ID`, `Name` FROM `sys_acl_actions` WHERE `Name` IN('$sActions')");
        while ($r = mysql_fetch_array($res)) {
            define ($sPrefix . strtoupper(str_replace(' ', '_', $r['Name'])), $r['ID']);
        }
    }		
    
}

?>
