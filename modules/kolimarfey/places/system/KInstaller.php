<?php

require_once(BX_DIRECTORY_PATH_ROOT . K_SYS_PATH . 'KBase.php');

class KInstaller extends KBase
{    
    function KInstaller ()
    {     
        parent::KBase();   
    }

    function installLangs($sFilename, $sCateg, $sRealLang = 'en')
    {
        // detect lang
        $a = db_arr ("SELECT `ID` FROM `sys_localization_languages` WHERE `Name` = '$sRealLang'");
        if (!$a)
        {
            $a = db_arr ("SELECT `ID` FROM `sys_localization_languages` LIMIT 1");
        }
        $iLangId = $a['ID'];

        
        // detect cat id
        $a = db_arr ("SELECT `ID` FROM `sys_localization_categories` WHERE `Name` = '$sCateg'");
        if ($a)
        {
            $iCatId = $a['ID'];
        }
        else
        {
            db_res ("INSERT INTO `sys_localization_categories` SET `Name` = '$sCateg'");
            $a = db_arr ("SELECT LAST_INSERT_ID()");
            $iCatId = $a[0];
        }
        if (!$iCatId)
            return 'Can not insert localization category';
        
        // process
        $s = file_get_contents($sFilename);
        $aLines = explode ("\n", $s);
        foreach ($aLines as $sLine)
        {
            list ($sKey, $sString) = explode('===>', $sLine);
            $sKey = trim ($sKey);
            $sString = trim ($sString);            	
            if (!$sKey || !$sString) continue;
            $a = db_arr ("SELECT `ID` FROM `sys_localization_keys` WHERE `IDCategory` = '$iCatId' AND `Key` = '$sKey'");
            if (!$a)
            {
                db_res("INSERT IGNORE INTO `sys_localization_keys` SET `IDCategory` = '$iCatId', `Key` = '$sKey'");
            $a = db_arr ("SELECT LAST_INSERT_ID()");
            }
            $sKeyId = $a[0];
            db_res("INSERT IGNORE INTO `sys_localization_strings` SET `IDKey` = '$sKeyId', `IDLanguage` = '$iLangId', `String` = '$sString'");
        }

        // recompile languages
        require_once (BX_DIRECTORY_PATH_INC . 'languages.inc.php');
        compileLanguage($iLangId);

        return '';
    }
    
    function uninstallLangs($sCateg)
    {        
        // detect cat id
        $iCatId = 0;
        $a = db_arr ("SELECT `ID` FROM `sys_localization_categories` WHERE `Name` = '$sCateg'");
        if ($a)
        {
            $iCatId = $a['ID'];
        }
        if (!$iCatId) 
            return 'Can not determine category ID';
    	
        db_res("DELETE FROM `sys_localization_keys`, `sys_localization_strings` USING `sys_localization_keys`, `sys_localization_strings` WHERE `sys_localization_keys`.`ID` = `sys_localization_strings`.`IDKey` AND `sys_localization_keys`.`IDCategory` = '$iCatId'");

        db_res ("DELETE FROM `sys_localization_categories` WHERE `Name` = '$sCateg'");

        // recompile languages
        require_once (BX_DIRECTORY_PATH_INC . 'languages.inc.php');
        compileLanguage($iLangId);

        return '';
    }
    

    function rebuildMenu()
    {
        $oMenu = new BxDolMenu();
        if (!$oMenu->compile())
            return "Menu compilation failed.";
        return '';
    }
    
    function rebuildPages()
    {
        require_once (BX_DIRECTORY_PATH_CLASSES . 'BxDolPageViewAdmin.php');
		$oCacher = new BxDolPageViewCacher ('sys_page_compose', 'sys_page_compose.inc');
		$oCacher->createCache();
    }
    
    function installSQL($filename)
    {
        $errorMes = '';
        
        if ( !($f = fopen ( $filename, "r" )) )
        {
            die( 'Could not open file with sql instructions:' . $filename  );
        }

        while ( $s = fgets ( $f, 10240) )
        {
            $s = trim ($s);
            if ( $s[0] == '#' ) continue;
            if ( $s[0] == '' ) continue;
            if ( $s[0].$s[1] == '--' ) continue;


            if ( $s[strlen($s)-1] == ';' )
            {
                $s_sql .= $s;
            }
            else
            {
                $s_sql .= $s;
                continue;
            }

            $res = db_res ($s_sql);
            if ( !$res )
            {
                $errorMes .= 'Error while executing: ' . $s_sql  . '<br />' . mysql_error() . '<br /><br />';
            }
            $s_sql = "";
        }

        fclose($f);

        return $errorMes;
    }        
}

?>
