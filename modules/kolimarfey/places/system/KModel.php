<?php

require_once(BX_DIRECTORY_PATH_ROOT . K_SYS_PATH . 'KBase.php');

class KModel extends BxDolDb
{    
    function KModel ()
    {     
        parent::BxDolDb();           
    }    

	function getValueIndexed($query, $sIndex, $sValue)
	{
		if(!$query)
			return array();

		$res = mysql_query($query, $this->link) or $this->error('Cannot complete query [' . $query . '] (getAll) ');
		$arr_res = array();
		if($res)
		{
			while($row = mysql_fetch_array($res, MYSQL_ASSOC))
				$arr_res[$row[$sIndex]] = $row[$sValue];
			mysql_free_result($res);
		}
		return $arr_res;
    }

    // ------------------------------------------------------------------------------------
    
    function getConfigVar ($sName)
    {
        return $this->getOne ("SELECT `value` FROM `" . K_TABLE_PREFIX . "config` WHERE `name` = '$sName' LIMIT 1");
    }            

    function setConfigVar ($sName, $sValue)
    {
        return $this->query ("UPDATE `" . K_TABLE_PREFIX . "config` SET `value` = '$sValue' WHERE `name` = '$sName' LIMIT 1");
    }

    function generateBinaryIdForTable ($sTable, $sIdField, $isAddTablePrefix = true)
    {
        if ($isAddTablePrefix)
            $sTable = K_TABLE_PREFIX . $sTable;
        $i = 1;
        $iMax = pow(2, 63);
        while (1)
        {
            $ii = (int)$this->getOne ("SELECT `$sIdField` FROM `$sTable` WHERE `$sIdField` = '$i' LIMIT 1");
            if (!$ii)
                return $i;
            $i *= 2;
            if ($i > $iMax) return 0;
        }
        return 0;
    }
}

?>
