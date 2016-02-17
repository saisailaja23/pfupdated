<?php

require_once(BX_DIRECTORY_PATH_ROOT . K_SYS_PATH . 'KBase.php');

class KSecurity extends KBase
{    
    function KSecurity ()
    {     
        parent::KBase();   
    }
    
    function passInt ($v)
    {        
        return (int)$v;
    }

    function passFloat ($v)
    {        
        return (float)$v;
    }    

    function passText ($v)
    {        
        if (get_magic_quotes_gpc())
            return strip_tags($v);
        else
            return mysql_real_escape_string(strip_tags($v));
    }        

    function passXss ($v)
    {
        if (get_magic_quotes_gpc())
            return clear_xss ($v);
        else
            return mysql_real_escape_string(clear_xss ($v));
    }        
}

?>
