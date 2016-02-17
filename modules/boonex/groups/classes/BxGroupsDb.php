<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

bx_import('BxDolTwigModuleDb');

/*
 * Groups module Data
 */
class BxGroupsDb extends BxDolTwigModuleDb
{
    /*
     * Constructor.
     */
    function BxGroupsDb(&$oConfig)
    {
        parent::BxDolTwigModuleDb($oConfig);

        $this->_sTableMain = 'main';
        $this->_sTableMediaPrefix = '';
        $this->_sFieldId = 'id';
        $this->_sFieldAuthorId = 'author_id';
        $this->_sFieldUri = 'uri';
        $this->_sFieldTitle = 'title';
        $this->_sFieldDescription = 'desc';
        $this->_sFieldTags = 'tags';
        $this->_sFieldThumb = 'thumb';
        $this->_sFieldStatus = 'status';
        $this->_sFieldFeatured = 'featured';
        $this->_sFieldCreated = 'created';
        $this->_sFieldJoinConfirmation = 'join_confirmation';
        $this->_sFieldFansCount = 'fans_count';
        $this->_sTableFans = 'fans';
        $this->_sTableAdmins = 'admins';
        $this->_sFieldAllowViewTo = 'allow_view_group_to';
        
        $this->_sTableCodes = 'codes';
    }

    function deleteEntryByIdAndOwner ($iId, $iOwner, $isAdmin)
    {
        if ($iRet = parent::deleteEntryByIdAndOwner ($iId, $iOwner, $isAdmin)) {
            $this->query ("DELETE FROM `" . $this->_sPrefix . "fans` WHERE `id_entry` = $iId");
            $this->query ("DELETE FROM `" . $this->_sPrefix . "admins` WHERE `id_entry` = $iId");
            $this->deleteEntryMediaAll ($iId, 'images');
            $this->deleteEntryMediaAll ($iId, 'videos');
            $this->deleteEntryMediaAll ($iId, 'sounds');
            $this->deleteEntryMediaAll ($iId, 'files');
        }
        return $iRet;
    }

    function getCodesList ($sStatus = 'approved', $aPaginate = array()) { 
    	$sStatus = process_db_input($sStatus, BX_TAGS_STRIP);    	
    	$iNum = $this->getEntriesCountByStatus($sStatus);
    	$mixedList = false;
    	if ($iNum > 0) {
	    	$aLimit = array(
	    		'page' => isset($aPaginate['page']) && (int)$aPaginate['page'] > 0 ? (int)$aPaginate['page'] : 1,
	    		'per_page' => isset($aPaginate['per_page']) ? (int)$aPaginate['per_page'] : 10
	    	);
	    	
	    	$sqlLimit = "LIMIT " . ($aLimit['page'] - 1) * $aLimit['per_page'] . ", " . $aLimit['per_page'];    	
	    	$sqlQuery = "SELECT a.`" . $this->_sFieldId . "`, a.`" . $this->_sFieldTitle . "`,
	    				b.`code`
	    				FROM `" . $this->_sPrefix . $this->_sTableMain . "` as a
	    				LEFT JOIN `" . $this->_sPrefix . $this->_sTableCodes . "` as b
	    				ON b.`id_entry` = a.`" . $this->_sFieldId . "`
	    				WHERE a.author_id <= 2
	    				ORDER BY a.`" . $this->_sFieldCreated . "` DESC
	    				$sqlLimit"; 

	    	$mixedList = $this->getAll($sqlQuery);
    	}    		
    	return $mixedList;
    }
    
    function getEntriesCountByStatus ($sStatus = 'pending') {
    	$sStatus = process_db_input($sStatus, BX_TAGS_STRIP);
    	$sqlQuery = "SELECT COUNT(*)
    				FROM `" . $this->_sPrefix . $this->_sTableMain . "`
    				WHERE `" . $this->_sFieldStatus . "` = '$sStatus'";
    	return $this->getOne($sqlQuery);
    }
    
    function generateEntryCode ($iEntryId) {
    	$iEntryId = (int)$iEntryId;
    	$sCode = md5(microtime());
    	return $this->res("REPLACE `" . $this->_sPrefix . $this->_sTableCodes . "` SET `code`='$sCode', `id_entry`='$iEntryId'");
    }
    
    function checkCode ($sCode, $iEntryId) {
    	$iEntryId = (int)$iEntryId;
    	$sCode = process_db_input($sCode, BX_TAGS_STRIP);
    	$sqlQuery = "SELECT COUNT(*) FROM `" . $this->_sPrefix . $this->_sTableCodes . "` WHERE `code`='$sCode' AND `id_entry`='$iEntryId'";    	
    	$bRes = $this->getOne($sqlQuery) == 1 ? true : false;
    	return $bRes;
    }
    
    function checkControl ($iMaster, $iDepend) {
    	$iMaster = (int)$iMaster;
    	$iDepend = (int)$iDepend;
    	$sqlQuery = "SELECT COUNT(*)
    				FROM `" . $this->_sPrefix . $this->_sTableFans . "` as a
    				LEFT JOIN `" . $this->_sPrefix . $this->_sTableMain . "` as b
    				ON a.`id_entry`=b.`id`
    				WHERE a.`id_profile`=$iDepend AND b.`{$this->_sFieldAuthorId}`=$iMaster
    				";
    	$bRes = $this->getOne($sqlQuery) == 1 ? true : false;
    	return $bRes;
    }
    
    function changeGroupOwner ($iEntryId, $iNewOwnerId) {
    	$iEntryId = (int)$iEntryId;
    	$iNewOwnerId = (int)$iNewOwnerId;
    	$sqlQuery = "UPDATE `" . $this->_sPrefix . $this->_sTableMain . "` SET `{$this->_sFieldAuthorId}`='$iNewOwnerId' WHERE `{$this->_sFieldId}`=$iEntryId";
    	return $this->query($sqlQuery);
    }
    
    function addEntryToPre (&$aEntryData) {
    	$iId = (int)$aEntryData['id'];
    	$sName = process_db_input($aEntryData['title'], BX_TAGS_STRIP);
    	$sqlQuery = "INSERT INTO `sys_pre_values` (`Key`, `Value`, `Order`, `LKey`) VALUES('AdoptionAgency', '$iId', '$iId', '__$sName')";
    	return $this->query($sqlQuery);
    }
	
    function removedEntryToPre ($iId) {
    	$iId = (int)$iId;
    	$sqlQuery = "DELETE FROM `sys_pre_values` WHERE `Key` = 'AdoptionAgency' AND `Value`='$iId' LIMIT 1";
    	return $this->res($sqlQuery);
    }
    
    function resetEntryOwner ($iEntryId) {
    	// define admin
    	$sqlQuery = "SELECT `ID` FROM `Profiles` WHERE `Role`='3' LIMIT 1";
    	$iNewOwnerId = (int)$this->getRow($sqlQuery);
    	return $this->changeGroupOwner($iEntryId, $iNewOwnerId);
    }
    
    function deactivateEntry ($iId) {
        $iId = (int)$iId;
    	return $this->query ("UPDATE `" . $this->_sPrefix . $this->_sTableMain . "` SET `{$this->_sFieldStatus}` = 'pending' WHERE `{$this->_sFieldId}` = $iId LIMIT 1");
    }
}

?>