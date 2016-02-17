<?php

    /***************************************************************************
    *                            Dolphin Smart Community Builder
    *                              -------------------
    *     begin                : Mon Mar 23 2006
    *     copyright            : (C) 2007 BoonEx Group
    *     website              : http://www.boonex.com
    * This file is part of Dolphin - Smart Community Builder
    *
    * Dolphin is free software; you can redistribute it and/or modify it under
    * the terms of the GNU General Public License as published by the
    * Free Software Foundation; either version 2 of the
    * License, or  any later version.
    *
    * Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
    * without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
    * See the GNU General Public License for more details.
    * You should have received a copy of the GNU General Public License along with Dolphin,
    * see license.txt file; if not, write to marketing@boonex.com
    ***************************************************************************/

    require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolModuleDb.php' );

    class SasProfileInfoDb extends BxDolModuleDb 
    {
        var $_oConfig;
        var $sTablePrefix;

    	/**
    	 * Constructor.
    	 */
    	function SasProfileInfoDb(&$oConfig)
        {
    		parent::BxDolModuleDb();		

            $this -> _oConfig = $oConfig;
    	    $this -> sTablePrefix = $oConfig -> getDbPrefix();
        }

		/**
         * Get sys options settings;
         *
         * @param $iCatId integer;
         * @return array;
         */
        function getSettings($iCatId)
        {
        	$iCatId = (int) $iCatId;

            return $this -> getAll("SELECT * FROM 
                `sys_options` WHERE `kateg` = {$iCatId} ORDER BY `order_in_kateg`");
        }

        /**
         * Get profiles
         *
         * @param $iLastProfileId integer
         * @param $iLimit integer
         * @param $iUpdateTime integer
         * @return array
         */
        function getProfiles($iLastProfileId, $iLimit, $iUpdateTime)
        {
            $iLastProfileId = (int) $iLastProfileId;
            $iLimit = (int) $iLimit;
            $iUpdateTime = (int) $iUpdateTime;

            $sQuery = "SELECT `ID`, `Email`, `Avatar` FROM `Profiles` WHERE 
                `Status` = 'Active' AND `ID` > {$iLastProfileId} 
                AND (UNIX_TIMESTAMP() - `esase_profileinfo_notify`) >= {$iUpdateTime} LIMIT {$iLimit}";

            return $this -> getAll($sQuery);
        }

        /**
         * Update notify time
         *
         * @param $iProfileId integer
         * @return void
         */
        function updateNotifyTime($iProfileId)
        {
            $iProfileId = (int) $iProfileId;
            $sQuery = "UPDATE `Profiles` SET `esase_profileinfo_notify` = UNIX_TIMESTAMP() WHERE `ID` = {$iProfileId}";
            $this -> query($sQuery);
        }

        /**
         * Get profile fields info
         * 
         * @param $iProfileId integer
         * @return array
         */
        function getProfileFieldsInfo($iProfileId)
        {
        	$sQuery = 
        	"
        		 SELECT `Name`
                     FROM `sys_profile_fields`
                     WHERE `ViewMembBlock` <>0
                     AND `Type` <> 'system'
                     AND NAME NOT
                     IN (
                     'Facebook', 'MySpace', 'Twitter', 'Headline', 'BMChildSex', 'BMAddress', 'BMChildEthnicity', 'BMPhone','BMTimetoReach'
                       )	
         	";
        	$aFields = $this -> getAll($sQuery);

        	$iFilled = 0;
        	if($aFields) {
	        	foreach($aFields as $sKey => $aFieldName) 
	        	{
	        		$sValue = $this -> _getFieldValue($iProfileId, $aFieldName['Name']);
	        		if($sValue) {
		        		$iFilled++;
	        		}
	        	}
        	}

        	$aRet = array(
        		'count_fields' => count($aFields),
        		'filled'   => $iFilled,
        	);
        	
        	return $aRet;
        }

        /**
         * Function will return category's id;
         *
         * @param  : $sCatName (string) - catregory's name;
         * @return : (integer) - category's id;
         */
        function getSettingsCategoryId($sCatName)
        {
        	$sCatName	= $this -> escape($sCatName); 
            return $this -> getOne('SELECT `kateg` FROM `sys_options` WHERE `Name` = "' . $sCatName . '"');
        }

    	/**
         * Get field value
         * 
         * @param $iProfileId integer
         * @param $sField string
         * @return variant
         */
        function _getFieldValue($iProfileId, $sField)
        {
        	$sField = process_db_input($sField, BX_TAGS_NO_ACTION, BX_SLASHES_NO_ACTION);
        	$iProfileId = (int) $iProfileId;

        	$sQuery = "SELECT `{$sField}` FROM `Profiles` WHERE `ID` = {$iProfileId}";
        	return $this -> getOne($sQuery);
        }
    }