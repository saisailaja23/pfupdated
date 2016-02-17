<?php
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolDb.php' );

class YnTourGuideDb extends BxDolModuleDb {
	var $_sTableTours, $_sTableStations, $_sTableViewed;
	
	
	/*
	 * Constructor.
	 */
	function YnTourGuideDb(&$oConfig) {
		parent::BxDolDb();
		$this->_oConfig = $oConfig;
		$this->_sTablePrefix = $this->_oConfig->getDbPrefix();
		$this->_sTableTours = $this->_sTablePrefix . 'tours';
		$this->_sTableStations = $this->_sTablePrefix . 'stations';
        $this->_sTableViewed = $this->_sTablePrefix . 'viewed';
	}
	
    function addViewedTour($iTourId, $iMemberId, $iHide)
    {
        if(!$this->getViewedTour($iTourId, $iMemberId))
        {
            $sInserSQL = "
                INSERT INTO `{$this->_sTableViewed}` 
                SET `mem_id`    = '{$iMemberId}',
                    `tour_id`   = '{$iTourId}',
                    `viewed`    = '1',
                    `hide`      = '{$iHide}'
            ";        
            return $this->query($sInserSQL);
        }
    }
    
    function getViewedTour($iTourId, $iMemberId)
    {
        $sSelect = "
            SELECT *
            FROM `{$this->_sTableViewed}`
            WHERE `mem_id`    = '{$iMemberId}'
            AND `tour_id`   = '{$iTourId}'
        ";
        return $this->getRow($sSelect);
    }
    
    function getSettingsCategory()
	{
		return (int) $this->getOne("SELECT `ID` FROM `sys_options_cats` WHERE `name` = 'Younet Tour Guide' LIMIT 1");
	}
    
	function getAllTours()
	{
		$sSelectSQL = "SELECT * FROM `{$this->_sTableTours}`";
		return $this->getAll($sSelectSQL);
	}
	
	function getTourById($iTourId)
	{
		$sSelectSQL = "SELECT * FROM `{$this->_sTableTours}` WHERE `id` = '{$iTourId}'";
		return $this->getRow($sSelectSQL);
	}
	
	function getActiveTourByPathname($sPathname, $iIsGuest, $iMemberId)
	{
		$sSelectSQL = "
            SELECT  `t`.*, `v`.`viewed`, `v`.`hide`
            FROM    `{$this->_sTableTours}` as `t` LEFT JOIN `{$this->_sTableViewed}` as `v` ON `t`.`id` = `v`.`tour_id` AND `v`.`mem_id` = '{$iMemberId}'
            WHERE   `path_name` = '{$sPathname}' 
                AND `t`.`is_guest` = '{$iIsGuest}' 
                AND `t`.`active` = 1
            ";
		return $this->getAll($sSelectSQL);
	}
    
    function getActiveTourNotHideByPathname($sPathname, $iIsGuest, $iMemberId)
    {
        $sSelectSQL = "
            SELECT  `t`.`id`
            FROM    `{$this->_sTableTours}` as `t` LEFT JOIN `{$this->_sTableViewed}` as `v` ON `t`.`id` = `v`.`tour_id` AND `v`.`mem_id` = '{$iMemberId}'
            WHERE   `t`.`path_name` = '{$sPathname}' 
                AND `t`.`is_guest` = '{$iIsGuest}' 
                AND `t`.`active` = 1
                AND `t`.`id` NOT IN (
                    SELECT  `tour_id`
                    FROM    `{$this->_sTableViewed}`, `{$this->_sTableTours}`
                    WHERE   `mem_id` = '{$iMemberId}'
                        AND `hide`   = '1'
                        AND `tour_id`= `id`
                        AND `path_name` = '{$sPathname}' 
                )
            ";
		return $this->getAll($sSelectSQL);
    }
    
    function getAllTourByPathname($sPathname, $iIsGuest)
	{
		$sSelectSQL = "SELECT * FROM `{$this->_sTableTours}` WHERE `path_name` = '{$sPathname}' AND `is_guest` = '{$iIsGuest}'";
		return $this->getAll($sSelectSQL);
	}
	
	function getAllStationByTourId($iTourId)
	{
		$sSelectSQL = "SELECT * FROM `{$this->_sTableStations}` WHERE `tour_id` = '{$iTourId}' ORDER BY `num` ASC";
		return $this->getAll($sSelectSQL);
	}
	
	function getStationByStationId($iStationId)
	{
		$sSelectSQL = "SELECT * FROM `{$this->_sTableStations}` WHERE `id` = '{$iStationId}'";
		return $this->getRow($sSelectSQL);
	}
	
	function deleteStationByStationId($iStationId)
	{
		$sDeleteSQL = "DELETE FROM `{$this->_sTableStations}` WHERE `id` = '{$iStationId}'";
		return $this->query($sDeleteSQL);
	}
    
    function deleteStationByTourId($iTourId)
    {
        $sDeleteSQL = "DELETE FROM `{$this->_sTableStations}` WHERE `tour_id` = '{$iTourId}'";
		return $this->query($sDeleteSQL);
    }
	
	function deleteTourByTourId($iTourId)
	{
		$sDeleteSQL = "DELETE FROM `{$this->_sTableTours}` WHERE `id` = '{$iTourId}'";
		return $this->query($sDeleteSQL);
	}
    
	function getMaxNumByTourId($iTourId)
    {
        $sSelectSQL = "SELECT MAX(`num`) FROM `{$this->_sTableStations}` WHERE `tour_id` = '{$iTourId}'";
        return $this->getOne($sSelectSQL);
    }
    
    function updateStationOrder($aStations)
	{
		$sWhenSQL = '';
		foreach($aStations as $i => $iStationId)
		{
			$sWhenSQL .= " WHEN '{$iStationId}' THEN {$i} ";
		}
		$sUpdateSQL = "
			UPDATE `{$this->_sTableStations}`
			SET `num` = CASE `id`
				$sWhenSQL
			END
		";
		$this->query($sUpdateSQL);
	}
    
    function updateStationByStationId($iStationId, $sMessage, $sPosition)
    {
        $sUpdateSQL = "
            UPDATE `{$this->_sTableStations}`
            SET `msg` = '{$sMessage}',
                `position` = '{$sPosition}'
            WHERE `id` = '{$iStationId}'
        ";
        $this->query($sUpdateSQL);
    }
    
    function addTour($sTourName, $sPathName, $iAutoPlay, $iIsGuest)
    {
        $sInsertSQL = "
            INSERT INTO `{$this->_sTableTours}` 
            SET `tour_name` = '{$sTourName}',
                `path_name` = '{$sPathName}',
                `is_guest`  = '{$iIsGuest}',
                `auto_play` = '{$iAutoPlay}'
        ";
        $this->query($sInsertSQL);
        return $this->lastId();
    }
    
    function addStation($iTourId, $iNum, $sSel, $sMsg, $iDelay, $sPos)
    {
        $sInsertSQL = "
            INSERT INTO `{$this->_sTableStations}` 
            SET `tour_id`   = '{$iTourId}',
                `num`       = '{$iNum}',
                `sel`       = '{$sSel}',
                `msg`       = '{$sMsg}',
                `delay`     = '{$iDelay}',
                `position`  = '{$sPos}'
        ";
        $this->query($sInsertSQL);
        return $this->lastId();
    }
    
    function setActiveTour($iTourId, $iStatus)
    {
        $sUpdateSQL = "
            UPDATE `{$this->_sTableTours}`
            SET `active` = '{$iStatus}'
            WHERE `id` = '{$iTourId}'
        ";
        $this->query($sUpdateSQL);
    }
    
    function updateHideTour($aTourIds, $iHide)
    {
        $sUpdateSQL = "
            UPDATE `{$this->_sTableViewed}`
            SET `hide` = '{$iHide}'
            WHERE `tour_id` IN (" . implode(',', $aTourIds) .  ')';
        $this->query($sUpdateSQL);
    }
    
    function getTourViewed($sPageName, $iMemberId)
    {
        $sSelectSQL = "
            SELECT `tour_id` 
            FROM `{$this->_sTableTours}`,  `{$this->_sTableViewed}`
            WHERE `id` = `tour_id`
            AND `mem_id` = '{$iMemberId}'
            AND `path_name` = '{$sPageName}'
            AND `is_guest` = 0
        ";
        return $this->getColumn($sSelectSQL);
    }
}