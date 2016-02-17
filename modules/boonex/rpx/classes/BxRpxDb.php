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

    class BxRpxDb extends BxDolModuleDb 
    {
        var $_oConfig;
        var $sTablePrefix;

    	/**
    	 * Constructor.
    	 */
    	function BxRpxDb(&$oConfig)
        {
    		parent::BxDolModuleDb();		

            $this -> _oConfig = $oConfig;
    	    $this -> sTablePrefix = $oConfig -> getDbPrefix();
        }
        
        /**
         * get rpx profile id
         *
         * @param $mProfile mixed
         * @return integer
         */
        function getProfileId($mProfile)
        {
        	$mProfile = $this -> escape($mProfile);
        	$sQuery = "SELECT `ID` FROM `Profiles` WHERE `RpxProfile` = '{$mProfile}' LIMIT 1";
            return $this -> getOne($sQuery);
        }

        /**
         * Function will create new profile;
         *
         * @param  : (array) $aProfileFields    - `Profiles` table's fields;
         * @return : (integer)  - profile's Id;
         */
        function createProfile(&$aProfileFields) 
        {
            $sFields = null;

            // procces all recived fields;
            foreach($aProfileFields as $sKey => $mValue)
            {
            	$mValue = process_db_input($mValue, BX_TAGS_VALIDATE);
                $sFields .= "`{$sKey}` = '{$mValue}', ";
            }

            $sFields = preg_replace( '/,$/', '', trim($sFields) );

            $sQuery = "INSERT INTO `Profiles` SET {$sFields}";
            $this -> query($sQuery);

            $iProfileId = db_last_id();

            // set salt value ;
            $sQuery = 'UPDATE `Profiles` SET `Salt` = CONV(FLOOR(RAND() * 99999999999999), 10, 36) WHERE `ID` = ' . (int) $iProfileId;
            $this -> query($sQuery);

            // update password 
            $sQuery = 'UPDATE `Profiles` SET `Password` = SHA1( CONCAT(`Password`, `Salt`) ) WHERE `ID` = ' . (int) $iProfileId;
            $this -> query($sQuery);

            return $iProfileId;
        }

        /**
         * Function will return category's id;
         *
         * @param  : $sCatName (string) - catregory's name;
         * @return : (integer) - category's id;
         */
        function getSettingsCategoryId($sCatName)
        {
        	$sCatName = $this -> escape($sCatName);
            return $this -> getOne('SELECT `kateg` FROM `sys_options` WHERE `Name` = "' . $sCatName . '"');
        }

        /**
         * Function will update  profile's status;
         *
         * @param  : $iProfileId (integer) - profile's Id;
         * @param  : $sStatus    (string)  - profile's status;
         * @return : void;
         */
        function updateProfileStatus($iProfileId, $sStatus)
        {
			$iProfileId = (int)$iProfileId;
			$sStatus	= $this -> escape($sStatus);

            $sQuery = "UPDATE `Profiles` SET `Status` = '{$sStatus}' WHERE `ID` = {$iProfileId}";
            return $this -> query($sQuery);
        }

        /**
         * Function will check field name in 'Profiles` table;
         *   
         * @return : (boolean);   
         */
        function isFieldExist($sFieldName) 
        {
        	$sFieldName = $this -> escape($sFieldName);
            $sQuery = "SELECT `ID` FROM `sys_profile_fields` WHERE `Name` = '{$sFieldName}' LIMIT 1";
            return ( $this -> getOne($sQuery) ) ? true : false;
        }
    }
