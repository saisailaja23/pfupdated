<?php

require_once(BX_DIRECTORY_PATH_ROOT . K_SYS_PATH . 'KInstaller.php');

class PlacesInstaller extends KInstaller
{    
    function PlacesInstaller ()
    {
        parent::KInstaller();
    }

    function upgrade ($sVerFrom = 0, $sVerTo = 0)
    {        
        $sFontStartGood = '<font color="green">';
        $sFontStartBad = '<font color="red">';
        $sFontEnd = '</font>';        
        $sUpgradeDir = "upgrade-{$sVerFrom}-{$sVerTo}/";

        if (!file_exists(BX_DIRECTORY_PATH_ROOT . K_APP_PATH . $sUpgradeDir))
        {
            echo $sFontStartBad, "No such upgrade available", $sFontEnd;
            return;
        }

        $this->_load('Model', 'Installer');
        $this->_load('Config', 'Installer');        

        if ((include BX_DIRECTORY_PATH_ROOT . K_APP_PATH . $sUpgradeDir . 'check.php') == 'INSTALLED')
        {
            echo $sFontStartBad, "This upgrade is already applied", $sFontEnd;
            return;
        }

        // run sql        
        if ($s = $this->installSQL (BX_DIRECTORY_PATH_ROOT . K_APP_PATH . $sUpgradeDir . 'upgrade.sql'))
        {
            echo $sFontStartBad, $s, $sFontEnd;
            return;
        }        

        // add lang strings
        if ($s = $this->installLangs (BX_DIRECTORY_PATH_ROOT . K_APP_PATH . $sUpgradeDir . 'langs.txt', 'Places'))
        {
            echo $sFontStartBad, $s, $sFontEnd;
            return;
        }

        $model = $this->model;
        if ($s = (include BX_DIRECTORY_PATH_ROOT . K_APP_PATH . $sUpgradeDir . 'script.php'))
        {
            echo $sFontStartBad, $s, $sFontEnd;
            return;
        }

        // rebuild menu
        if ($s = $this->rebuildMenu())
        {
            echo $sFontStartBad,$s,$sFontEnd;
            return;
        }

        // rebuild pages
        $this->rebuildPages();

        echo $sFontStartGood, "Upgrade from {$sVerFrom} to {$sVerTo} has been successfully applied.", $sFontEnd; 
    }

    function add_lang ($sLangFile, $sLangReal)
    {
        // add lang strings
        if ($s = $this->installLangs (BX_DIRECTORY_PATH_ROOT . K_APP_PATH . 'install/langs_'.$sLangFile.'.txt', 'Places', $sLangReal))
        {
            echo 'Error: ' . $s;
            return;
        }
        echo "Language file was successfully added";
    }


    function install ()
    {             
        $sFontStartGood = '<font color="green">';
        $sFontStartBad = '<font color="red">';
        $sFontEnd = '</font>';
             
        $a = db_arr ("SELECT `name` FROM `sys_menu_admin` WHERE `name` = 'Places'");
        if ($a && $a['name'] == 'Places')
        {
            echo $sFontStartBad,'The mod is already installed',$sFontEnd;
            return;
        }
        
        // run sql        
        if ($s = $this->installSQL (BX_DIRECTORY_PATH_ROOT . K_APP_PATH . 'install/install.sql'))
        {
            echo $sFontStartBad,$s,$sFontEnd;
            return;
        }
        
        // add lang strings
        if ($s = $this->installLangs (BX_DIRECTORY_PATH_ROOT . K_APP_PATH . 'install/langs.txt', 'Places'))
        {
            echo $sFontStartBad,$s,$sFontEnd;
            return;
        }
        
        // rebuild menu
        if ($s = $this->rebuildMenu())
        {
            echo $sFontStartBad,$s,$sFontEnd;
            return;
        }

        // rebuild pages
        $this->rebuildPages();
            
        echo $sFontStartGood,"Installation has been successfully completed.",$sFontEnd; 
    }
    
    function uninstall ()
    {
            
        $sFontStartGood = '<font color="green">';
        $sFontStartBad = '<font color="red">';
        $sFontEnd = '</font>';

        // check if already uninstalled
        $a = db_arr ("SELECT `ID` FROM `sys_localization_categories` WHERE `Name` = 'Places'");
        if (!$a || !count($a) || !$a['ID'])
        {
            echo $sFontStartBad,'This mod is not installed',$sFontEnd;
            return;
        }        
        
        // run sql
        if ($s = $this->installSQL (BX_DIRECTORY_PATH_ROOT . K_APP_PATH . 'install/uninstall.sql'))
        {
            echo $sFontStartBad,$s,$sFontEnd;
            return;
        }

        // delete languages
        if ($s = $this->uninstallLangs ('Places'))
        {
            echo $sFontStartBad,$s,$sFontEnd;
            return;
        }
        
        // rebuild menu
        if ($s = $this->rebuildMenu())
        {
            echo $sFontStartBad,$s,$sFontEnd;
            return;
        }

        // rebuild pages
        $this->rebuildPages();

        echo $sFontStartGood,"Uninstallation has been successfully completed.",$sFontEnd;
    }

}

?>
