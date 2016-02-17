<?php

require_once(BX_DIRECTORY_PATH_ROOT . K_SYS_PATH . 'KModel.php');

define ('K_DEFAULT_WHERE', " AND `tpl`.`pl_status` = 'active' ");

class PlacesModel extends KModel
{    
    function PlacesModel ()
    {
        parent::KModel();

        $this->sDateFormat = getParam('short_date_format');

        $this->sPlaceFields = "
            `tpl`.`pl_id`, 
            `tpl`.`pl_thumb`, 
            `tpl`.`pl_name`,
            `tpl`.`pl_uri`,
            `tpl`.`pl_desc`,
            `tpl`.`pl_cat`,
            `tpl`.`pl_country`,
            `tpl`.`pl_city`,
            `tpl`.`pl_zip`,
            `tpl`.`pl_address`,
            `tpl`.`pl_created`,
            `tpl`.`pl_tags`,
            `tpl`.`pl_status`,
            `tpl`.`pl_map_lat`,
            `tpl`.`pl_map_lng`,
            `tpl`.`pl_map_zoom`,
            `tpl`.`pl_map_type`,
            `tpl`.`pl_author_id`,
            `tpl`.`pl_featured`,
            `tpl`.`pl_rss`,
            DATE_FORMAT(`tpl`.`pl_created`, '" . $this->sDateFormat . "') AS `pl_created_f`,
            `tplc`.`pl_cat_name`,            
            `tcn`.`Country` AS `pl_country_name`,
            `tp`.`NickName`
            ";

        $this->sPlaceJoin = "
            INNER JOIN `" . K_TABLE_PREFIX . "places_cat` AS `tplc` ON (`tplc`.`pl_cat_id` = `tpl`.`pl_cat`) 
            INNER JOIN `sys_countries` AS `tcn` ON (`tcn`.`ISO2` = `tpl`.`pl_country`) 
            LEFT JOIN `Profiles` AS `tp` ON (`tp`.`ID` = `tpl`.`pl_author_id` AND `tp`.`Status` = 'Active') 
            LEFT JOIN `" . K_TABLE_PREFIX . "photos` AS `tpli` ON (`tpli`.`pl_img_id` = `tpl`.`pl_thumb`) 
        ";        
    }

    // ------------------------------------------------------------------------------------

    function getPlaceById ($iId)
    {
        return $this->getRow ("SELECT " . $this->sPlaceFields . " FROM `" . K_TABLE_PREFIX . "places` AS `tpl` " . $this->sPlaceJoin . " WHERE `tpl`.`pl_id` = '$iId' LIMIT 1");
    }    

    function getPlaceByUri ($sUri)
    {
        return $this->getRow ("SELECT " . $this->sPlaceFields . " FROM `" . K_TABLE_PREFIX . "places` AS `tpl` " . $this->sPlaceJoin . " WHERE `tpl`.`pl_uri` = '$sUri' LIMIT 1");
    }    

    function getPlacesFeatured ($iStart, $Limit, &$aPlaces, $sOrderBy = ' ORDER BY `tpl`.`pl_created` DESC')
    {        
        $aPlaces = $this->getAll ("SELECT SQL_CALC_FOUND_ROWS " . $this->sPlaceFields . " FROM `" . K_TABLE_PREFIX . "places` AS `tpl` " . $this->sPlaceJoin . " WHERE 1 AND `pl_featured` = 1 " . K_DEFAULT_WHERE . " $sOrderBy LIMIT $iStart, $Limit");
        return $this->getOne("SELECT FOUND_ROWS()");
    }        

    function getPlacesLatest ($iStart, $Limit, &$aPlaces, $sOrderBy = ' ORDER BY `tpl`.`pl_created` DESC')
    {        
        $aPlaces = $this->getAll ("SELECT SQL_CALC_FOUND_ROWS " . $this->sPlaceFields . " FROM `" . K_TABLE_PREFIX . "places` AS `tpl` " . $this->sPlaceJoin . " WHERE 1 " . K_DEFAULT_WHERE . " $sOrderBy LIMIT $iStart, $Limit");
        return $this->getOne("SELECT FOUND_ROWS()");
    }        

    function getPlacesByTag ($sTag, $iStart, $Limit, &$aPlaces, $sOrderBy = ' ORDER BY `tpl`.`pl_created` DESC')
    {        
        $aPlaces = $this->getAll ("SELECT SQL_CALC_FOUND_ROWS " . $this->sPlaceFields . " FROM `" . K_TABLE_PREFIX . "places` AS `tpl` " . $this->sPlaceJoin . " INNER JOIN `sys_tags` ON (`sys_tags`.`ObjID` = `tpl`.`pl_id` AND `Type` = 'places') WHERE 1 " . K_DEFAULT_WHERE . " AND `sys_tags`.`Tag` = '$sTag' $sOrderBy LIMIT $iStart, $Limit");
        return $this->getOne("SELECT FOUND_ROWS()");
    }        

    function getPlacesBest ($iStart, $Limit, &$aPlaces, $sOrderBy = '')
    {
		$oVotingView = new BxTemplVotingView (K_NAME, 0, 0);
        $aSql = $oVotingView->getSqlParts("`tpl`", '`pl_id`');
        $sOrderBy = ' ORDER BY `voting_rate` DESC ';
        
        $aPlaces = $this->getAll ("SELECT " . $this->sPlaceFields . $aSql['fields'] . " FROM `" . K_TABLE_PREFIX . "places` AS `tpl` " . $this->sPlaceJoin . $aSql['join'] . " WHERE `places_rating`.`places_rating_count` > 2 " . K_DEFAULT_WHERE . " $sOrderBy LIMIT $iStart, $Limit");
        
        return $this->getOne("SELECT COUNT(*) FROM `" . K_TABLE_PREFIX . "places` AS `tpl` " . $this->sPlaceJoin . $aSql['join'] . " WHERE `places_rating`.`places_rating_count` > 2 " . K_DEFAULT_WHERE);
    }
    
    function getPlacesByUser ($iUserId, $iStart, $Limit, &$aPlaces, $sOrderBy = '', $isViewerOwner = false)
    {	        
        if (!$sOrderBy)        
            $sOrderBy = ' ORDER BY `tpl`.`pl_created` DESC';

        $sWhere = '';
        if (!$isViewerOwner)
            $sWhere = K_DEFAULT_WHERE;

        $aPlaces = $this->getAll ("SELECT SQL_CALC_FOUND_ROWS " . $this->sPlaceFields . " FROM `" . K_TABLE_PREFIX . "places` AS `tpl` " . $this->sPlaceJoin . " WHERE `pl_author_id` = '$iUserId' $sWhere $sOrderBy LIMIT $iStart, $Limit");
        return $this->getOne("SELECT FOUND_ROWS()");
    }

    function getPlacesByStatus ($sStatus, $iStart, $Limit, &$aPlaces, $sOrderBy = '')
    {	
        if (!$sOrderBy)        
            $sOrderBy = ' ORDER BY `tpl`.`pl_created` DESC';

        $aPlaces = $this->getAll ("SELECT SQL_CALC_FOUND_ROWS " . $this->sPlaceFields . " FROM `" . K_TABLE_PREFIX . "places` AS `tpl` " . $this->sPlaceJoin . " WHERE `pl_status` = '$sStatus' $sOrderBy LIMIT $iStart, $Limit");
        return $this->getOne("SELECT FOUND_ROWS()");
    }

    function getPlacesByCat ($iCatId, $iStart, $Limit, &$aPlaces, $sOrderBy = ' ORDER BY `tpl`.`pl_created` DESC')
    {	        
        $aPlaces = $this->getAll ("SELECT SQL_CALC_FOUND_ROWS " . $this->sPlaceFields . " FROM `" . K_TABLE_PREFIX . "places` AS `tpl` " . $this->sPlaceJoin . " WHERE `pl_cat` = '$iCatId' " . K_DEFAULT_WHERE . " $sOrderBy LIMIT $iStart, $Limit");    
        return $this->getOne("SELECT FOUND_ROWS()");
    }

    function getPlacesByCountry ($sCode, $iStart, $Limit, &$aPlaces, $sOrderBy = ' ORDER BY `tpl`.`pl_created` DESC')
    {	        
        $aPlaces = $this->getAll ("SELECT SQL_CALC_FOUND_ROWS " . $this->sPlaceFields . " FROM `" . K_TABLE_PREFIX . "places` AS `tpl` " . $this->sPlaceJoin . " WHERE `pl_country` = '$sCode' " . K_DEFAULT_WHERE . " $sOrderBy LIMIT $iStart, $Limit");
        return $this->getOne("SELECT FOUND_ROWS()");
    }

    function getPlacesByKeyword ($sKeyword, $sCity, $sCountryCode, $iStart, $Limit, &$aPlaces, $sOrderBy = ' ORDER BY `tpl`.`pl_created` DESC')
    {	        
        $sWhere = '';
        if ($sCountryCode)
            $sWhere .= " AND `pl_country` = '$sCountryCode' ";
        if ($sCity)
            $sWhere .= " AND `pl_city` LIKE '$sCity' ";
        if ($sKeyword)
            $sWhere .= " AND MATCH (`pl_name`,`pl_desc`,`pl_city`, `pl_address`) AGAINST ('$sKeyword') ";

        $aPlaces = $this->getAll ("SELECT SQL_CALC_FOUND_ROWS " . $this->sPlaceFields . " FROM `" . K_TABLE_PREFIX . "places` AS `tpl` " . $this->sPlaceJoin . " WHERE 1 " . K_DEFAULT_WHERE . $sWhere . " $sOrderBy LIMIT $iStart, $Limit");

        return $this->getOne("SELECT FOUND_ROWS()");
    }

    function deletePlaceById ($iId)
    {
        return $this->query ("DELETE FROM `" . K_TABLE_PREFIX . "places` WHERE `pl_id` = '$iId' LIMIT 1");
    }    

    function markAsFeaturedPlaceById ($iId, $isMarkAsFeatured) 
    {
        return $this->query ("UPDATE `" . K_TABLE_PREFIX . "places` SET `pl_featured` = '$isMarkAsFeatured' WHERE `pl_id` = '$iId' LIMIT 1");
    }

    function activatePlaceById ($iId)
    {
        return $this->query ("UPDATE `" . K_TABLE_PREFIX . "places` SET `pl_status` = 'active' WHERE `pl_id` = '$iId' LIMIT 1");
    }    

    function deactivatePlaceById ($iId)
    {
        return $this->query ("UPDATE `" . K_TABLE_PREFIX . "places` SET `pl_status` = 'approval' WHERE `pl_id` = '$iId' LIMIT 1");
    }    

    function getPlaceVideos($iPlaceId)
    {
        return $this->getAll("SELECT `pl_video_id`, `pl_video_thumb`, `pl_video_embed` FROM `" . K_TABLE_PREFIX . "videos` WHERE `pl_id` = '$iPlaceId'");
    }

    function insertVideo($iPlaceId, $sThumb, $sEmbed)
    {
        $ret = $this->query("INSERT INTO `" . K_TABLE_PREFIX . "videos` SET `pl_id` = '$iPlaceId', `pl_video_thumb` = '$sThumb', `pl_video_embed` = '$sEmbed', `pl_video_added` = UNIX_TIMESTAMP()");
        if (!$ret) 
            return false;
        return $this->lastId();
    }

    function deleteVideo ($iVideoId, $iAuthorId, $isAdmin = false)
    {    
        if (!$isAdmin)
        {
            $iPlaceId = $this->getOne("SELECT `t1`.`pl_id` FROM `" . K_TABLE_PREFIX . "places` AS `t1` INNER JOIN `" . K_TABLE_PREFIX . "videos` AS `t2` ON (`t1`.`pl_id` = `t2`.`pl_id`) WHERE `t2`.`pl_video_id` = '$iVideoId' AND `t1`.`pl_author_id` = '$iAuthorId' LIMIT 1");
            if (!$iPlaceId) 
                return false;
        }
            
        $ret = $this->query("DELETE FROM `" . K_TABLE_PREFIX . "videos` WHERE `pl_video_id` = '$iVideoId'");
        if (!$ret) 
            return false;

        $this->_deleteVideoFiles ($iVideoId);

        return true;
    }

    function _deleteVideoFiles ($iVideoId) {
        @unlink (BX_DIRECTORY_PATH_ROOT . PLACES_VIDEOS_PATH . $iVideoId . '.flv');
        @unlink (BX_DIRECTORY_PATH_ROOT . PLACES_VIDEOS_PATH . $iVideoId . '.jpg');
        @unlink (BX_DIRECTORY_PATH_ROOT . PLACES_VIDEOS_PATH . 't' . $iVideoId . '.jpg');
    }

    function updateVideoData ($iVideoId, $sThumb, $sEmbed, $isDeleteFilesIfFail = true) 
    {
        $sEmbed = mysql_real_escape_string ($sEmbed);
        $ret = $this->query("UPDATE `" . K_TABLE_PREFIX . "videos` SET `pl_video_thumb` = '$sThumb', `pl_video_embed` = '$sEmbed' WHERE `pl_video_id` = '$iVideoId'");
        if (!$ret && $isDeleteFilesIfFail)
            $this->_deleteVideoFiles ($iVideoId);

        return $ret;        
    }

    function insertImage ($iPlaceId)
    {
        $ret = $this->query("INSERT INTO `" . K_TABLE_PREFIX . "photos` SET `pl_id` = '$iPlaceId'");
        if (!$ret) 
            return false;
        $iImgId = $this->lastId();
        $this->query("UPDATE `" . K_TABLE_PREFIX . "places` SET `pl_thumb` = '$iImgId' WHERE `pl_id` = '$iPlaceId' AND `pl_thumb` = 0");
        return $iImgId;
    }

    function deletePhoto ($iPhotoId, $iAuthorId, $isAdmin = false)
    {        
        if (!$isAdmin)
        {
            $iPlaceId = $this->getOne("SELECT `t1`.`pl_id` FROM `" . K_TABLE_PREFIX . "places` AS `t1` INNER JOIN `" . K_TABLE_PREFIX . "photos` AS `t2` ON (`t1`.`pl_id` = `t2`.`pl_id`) WHERE `t2`.`pl_img_id` = '$iPhotoId' AND `t1`.`pl_author_id` = '$iAuthorId' LIMIT 1");
            if (!$iPlaceId) 
                return false;        
        }
            
        $ret = $this->query("DELETE FROM `" . K_TABLE_PREFIX . "photos` WHERE `pl_img_id` = '$iPhotoId'");
        if (!$ret) 
            return false;

        $iNewPhotoId = (int)$this->getOne("SELECT `pl_img_id` FROM `" . K_TABLE_PREFIX . "photos` WHERE `pl_id` = '$iPlaceId' LIMIT 1");            
        $this->query("UPDATE `" . K_TABLE_PREFIX . "places` SET `pl_thumb` = '$iNewPhotoId' WHERE `pl_id` = '$iPlaceId' AND `pl_thumb` = '$iPhotoId'");

        return true;
    }

    function getPlacePhotos($iPlaceId)
    {
        return $this->getAll("SELECT `pl_img_id` FROM `" . K_TABLE_PREFIX . "photos` WHERE `pl_id` = '$iPlaceId'");
    }

    function makePrimary($iPhotoId, $iAuthorId)
    {
        $iPlaceId = $this->getOne("SELECT `pl_id` FROM `" . K_TABLE_PREFIX . "photos` WHERE `pl_img_id` = '$iPhotoId'");
        return $this->query("UPDATE `" . K_TABLE_PREFIX . "places` SET `pl_thumb` = '$iPhotoId' WHERE `pl_id` = '$iPlaceId' AND `pl_author_id` = '$iAuthorId' LIMIT 1");
    }

    function getPlacesCount ($sExp)
    {
        $sWhere = '1';
        if ($sExp)
        $sWhere = " `pl_created` > DATE_SUB(NOW(), INTERVAL $sExp) ";        
        return $this->getOne("SELECT COUNT(*) FROM `" . K_TABLE_PREFIX . "places` WHERE $sWhere");
    }

    function getPlacesPendingCount () 
    {
        return $this->getOne("SELECT COUNT(*) FROM `" . K_TABLE_PREFIX . "places` WHERE `pl_status` = 'approval'");
    }

    function getUserIdByNickname ($s)
    {
        return $this->getOne("SELECT `ID` FROM `Profiles` WHERE `NickName` = '$s' LIMIT 1");
    }

    function getUserNicknameById ($iId)
    {
        return $this->getOne("SELECT `NickName` FROM `Profiles` WHERE `ID` = '$iId' LIMIT 1");
    }

    function getCatsForDrillDown ()
    {
        return $this->getAll ("SELECT `tc`.`pl_cat_id`, `tc`.`pl_cat_name`, `tc`.`pl_cat_icon`, COUNT(`tp`.`pl_id`) AS `num` FROM `" . K_TABLE_PREFIX . "places_cat` AS `tc` INNER JOIN `" . K_TABLE_PREFIX . "places` AS `tp` ON (`tp`.`pl_cat` = `tc`.`pl_cat_id`) GROUP BY `tc`.`pl_cat_id` ORDER BY `tc`.`pl_cat_name`");
    }

    function getCats ($isExcludePleaseSelect = false)
    {
        $sWhere = '';
        if ($isExcludePleaseSelect)
            $sWhere = ' AND `pl_cat_id` != 1';
        return $this->getAll ("SELECT * FROM `" . K_TABLE_PREFIX . "places_cat` WHERE 1 $sWhere");
    }

    function deleteCat($iId)
    {
        return $this->query ("DELETE FROM `" . K_TABLE_PREFIX . "places_cat` WHERE `pl_cat_id` = '$iId' LIMIT 1");
    }

    function updateCatIconById($iId, $sIcon)
    {
        return $this->query ("UPDATE `" . K_TABLE_PREFIX . "places_cat` SET `pl_cat_icon` = '$sIcon' WHERE `pl_cat_id` = '$iId' LIMIT 1");
    }

    function getCatById ($iCatId)
    {
        return $this->getRow ("SELECT * FROM `" . K_TABLE_PREFIX . "places_cat` WHERE `pl_cat_id` = '$iCatId' LIMIT 1");
    }

    function getCountriesForDrillDown ()
    {
        return $this->getAll ("SELECT `tc`.`ISO2` AS `code`, `tc`.`Country` AS `name`, COUNT(`tp`.`pl_id`) AS `num` FROM `sys_countries` AS `tc` INNER JOIN `" . K_TABLE_PREFIX . "places` AS `tp` ON (`tp`.`pl_country` = `tc`.`ISO2`) GROUP BY `tc`.`ISO2` ORDER BY `tc`.`Country`");
    }

    function savePlaceDrawings($iPlaceId, $sData) {
        $sData = mysql_real_escape_string($sData);
        return $this->query("INSERT INTO `" . K_TABLE_PREFIX . "drawings` SET `pl_id` = '$iPlaceId', `data` = '$sData', `updated` = UNIX_TIMESTAMP(), `created` = UNIX_TIMESTAMP() ON DUPLICATE KEY UPDATE `data` = '$sData', `updated` = UNIX_TIMESTAMP()");
    }

    function loadPlaceDrawings($iPlaceId) {
        return $this->getOne("SELECT `data` FROM `" . K_TABLE_PREFIX . "drawings` WHERE `pl_id` = '$iPlaceId'");
    }

    function saveRss ($iPlaceId, $iMemberId, $sRss) {
        return false === $this->query("UPDATE `" . K_TABLE_PREFIX . "places` SET `pl_rss` = '$sRss' WHERE `pl_id` = '$iPlaceId' AND `pl_author_id` = '$iMemberId' LIMIT 1") ? false : true;
    }

    function getPlaceKmlFiles ($iPlaceId) {
        return $this->getAll("SELECT `pl_kml_id`, `pl_id`, `pl_kml_name`, `pl_kml_file_ext`, `pl_kml_added` FROM `" . K_TABLE_PREFIX . "kml_files` WHERE `pl_id` = '$iPlaceId'");
    }

    function deleteKml ($iKmlId, $iAuthorId, $isAdmin = false)
    {    
        if (!$isAdmin)
        {
            $iPlaceId = $this->getOne("SELECT `t1`.`pl_id` FROM `" . K_TABLE_PREFIX . "places` AS `t1` INNER JOIN `" . K_TABLE_PREFIX . "kml_files` AS `t2` ON (`t1`.`pl_id` = `t2`.`pl_id`) WHERE `t2`.`pl_kml_id` = '$iKmlId' AND `t1`.`pl_author_id` = '$iAuthorId' LIMIT 1");
            if (!$iPlaceId) 
                return false;
        }

        $aKml = $this->getRow("SELECT * FROM `" . K_TABLE_PREFIX . "kml_files` WHERE `pl_kml_id` = '$iKmlId'");
        $ret = $this->query("DELETE FROM `" . K_TABLE_PREFIX . "kml_files` WHERE `pl_kml_id` = '$iKmlId'");
        if (!$ret) 
            return false;

        $this->_deleteKmlFiles ($aKml);

        return true;
    }    

    function _deleteKmlFiles ($aKml) {
        @unlink (BX_DIRECTORY_PATH_ROOT . PLACES_KML_PATH . $aKml['pl_kml_id'] . $aKml['pl_kml_file_ext']);
    }    
}

?>
