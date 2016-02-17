<?php

class KBase
{    
    var $_iPhpVer;

    function KBase ()
    {        
        $this->_iPhpVer = (int)phpversion();
    }
    
    function _load ($sClass, $sSearch)
    {
        $sProp = strtolower ($sClass);
        if (isset ($this->$sProp)) return;

        $sController = get_class($this);
        $i = strpos ($sController, $this->_iPhpVer < 5 ? strtolower($sSearch) : $sSearch);
        $sName = $this->_iPhpVer < 5 ? ucfirst (substr ($sController, 0, $i)) : substr ($sController, 0, $i);
        $sNewClassName = $sName.$sClass;                    

        $sFileApp = BX_DIRECTORY_PATH_ROOT . K_APP_PATH . $sNewClassName . '.php';
        
        if (!class_exists ($sNewClassName))
        {
            if (file_exists($sFileApp))
            {                
                require_once ($sFileApp);
            }
            else
            {
                $this->_loadSystem($sClass, $sSearch);
                return;
            }
        }    

        $this->$sProp = new $sNewClassName();
    }

    function _loadSystem ($sClass, $sSearch)
    {
        $sProp = strtolower ($sClass);
        if (isset ($this->$sProp)) return;

        $sNewClassName = 'K'.$sClass;
        
        if (!class_exists ($sNewClassName))
        {
            require_once (BX_DIRECTORY_PATH_ROOT . K_SYS_PATH . $sNewClassName . '.php');
        }    

        $this->$sProp = new $sNewClassName();
    }

    function _redirect ($sUrl)
    {
        global $site;

        $this->_load('Config', 'Base');
        
        if (0 != strncasecmp ($sUrl, 'http:', 5) && 0 != strncasecmp ($sUrl, 'https:', 6))
        {
            $sBaseUrl = $this->config->get ('sBaseUri');
            if ($this->config->get ('iRewriteEngine'))
                $sUrl = $sBaseUrl . '/' . $sUrl;
            else
                $sUrl = $sBaseUrl . '.php/' . $sUrl;
            $sUrl = $site['url'] . $sUrl;
        }

        header('Location: ' . $sUrl);
        exit;
    }

    function t ($s)
    {
        return _t('_'.$s);
    }
}

?>
