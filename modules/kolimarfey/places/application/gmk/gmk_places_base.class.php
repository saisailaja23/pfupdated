<?
/*******************************************************************************************
 Common Google Maps file v.2.2.drawing
 License: proprietary license product, please read license.txt before using this product.
 Author: Trofimov Alexander (kolimarfey@gmail.com)
*******************************************************************************************/

class GMKPlacesBase
{

    // profile field to use in template as {field_name}
    var $_aTableFields = array (
    	'ID', // mandatory
    );

    var $_aTmpls = array(); // html templates - defined in constructor

    var $_iMapWidth = '100%';  // map width
    var $_iMapHeight = 348; // map height
    var $_iMapControl = 0;  // map control 0, 1, 2
    var $_isTypeControl = 'on'; // display map type control
    var $_isScaleControl = 'on'; // display map scale control
    var $_isOverviewControl = ''; // display map overview control
    var $_isLocalSearch = ''; // display location search control
    var $_isDragable = 'on'; // is map dragable
    var $_sCustomType = ''; // custrom type to pass when displaying map points
    var $_iSaveLocationId = 1; // location id to load/save current map state, if 0 - read default loaction from Default values
    var $_isClickable = ''; // is map clickable
    var $_sSaveLocationAction = ''; // location saving action, to overrride default
    
    var $_fDefaultLat = 0; // default latitude
    var $_fDefaultLng = 0; // default longitude
    var $_iDefaultZoom = 3; // default zoom
    var $_iDefaultMapType = 0; // default map type
    
    var $_sCanvasId = 'gmk_canvas';
    var $_sLoadingId = 'gmk_canvas_loading';
    
    var $_sJsClass = 'GMKPlacesBase';
    var $_sJsClassInstance = 'glGMKPlacesBase';

    var $_aDrawing = false;


    function GMKPlacesBase ()
    {
        global $site;        
        
        $this->_aTmpls['popup'] = <<<EOS
<div id="{div_id}" style="display:{display}">
	<div style="margin-right:16px; border:1px solid #BBB; float:left; width:110px; height:110px; text-align:center; vertical-align:middle;">
		<img src="{thumb}" />
	</div>
	<a href="{$site['url']}{NickName}">{NickName}</a><br />
	{Headline}
</div>
EOS;
        $this->_aTmpls['popup_paginate'] .= '<div style="clear:both; float:none;"></div><div style="text-align:center; margin-top:5px;"> <a href="javascript:void(0);" onclick="gmk_move_prev(\'{id}\')">prev</a> <b><span id="{id}">1</span>/{count}</b> <a href="javascript:void(0);" onclick="gmk_move_next(\'{id}\')">next</a> </div>';

        $this->_aTmpls['canvas'] = '
        <div style="position:relative;">
        <div id="{canvas_id}" style="width:{width}; height:{height}; border:1px solid #CCCCCC; overflow:hidden;"></div>
            <div style="position:absolute; width:50%; top:0; left:0;">
                <div id="{loading_id}" style="position:absolute; right:-50px; background-color:red; color:white; padding:2px; width:100px; text-align:center; display:none; z-index:999;">' . _t('_Places loading ...') . '</div>
            </div>	
        </div>';

        $this->_aTmpls['save'] = '<div style="margin-top:10px; text-align:center;"><a href="javascript:void(0);" onclick="window.{js_class_instance}.saveLocation({location_id});">Save current location, zoom and map type</a></div>';

        $this->_aTmpls['js_files'] = '
    <script src="http://www.google.com/jsapi?key={google_key}" type="text/javascript"></script>
    <script src="' . $site['url'] . K_APP_PATH . 'gmk/gmk_places_base.js" type="text/javascript"></script>';

        $this->_aTmpls['js'] = <<<EOS
<script type="text/javascript">
{js}
</script>
EOS;

    }

    /********************************************* 
    * getters/setters 
    **********************************************/

    function setMapWidth ($v) {
        $this->_iMapWidth = $v;
    }

    function getMapWidth () {
        return $this->_iMapWidth;
    }

    function setMapHeight ($v) {
        $this->_iMapHeight = $v;
    }

    function getMapHeight () {
        return $this->_iMapHeight;
    }

    function setMapControl ($v) {
        $this->_iMapControl = $v;
    }

    function getMapControl () {
        return $this->_iMapControl;
    }    

    function setTypeControl ($v) {
        $this->_isTypeControl = $v ? 'on' : '';
    }

    function isTypeControl () {
        return $this->_isTypeControl;
    }

    function setScaleControl ($v) {
        $this->_isScaleControl = $v ? 'on' : '';
    }

    function isScaleControl () {
        return $this->_isScaleControl;
    }    

    function setOverviewControl ($v) {
        $this->_isOverviewControl = $v ? 'on' : '';
    }

    function isOverviewControl () {
        return $this->_isOverviewControl;
    }    

    function setLocalSearchControl ($v) {
        $this->_isLocalSearch = $v ? 'on' : '';
    }

    function isLocalSearch () {
        return $this->_isLocalSearch;
    }    

    function setDragable ($v) {
        $this->_isDragable = $v ? 'on' : '';
    }

    function isDragable () {
        return $this->_isDragable;
    }    

    function setCustomType ($v) {
        $this->_sCustomType = $v;
    }

    function getCustomType () {
        return $this->_sCustomType;
    }    

    function setSaveLocationId ($v) {
        $this->_iSaveLocationId = $v;
    }

    function getSaveLocationId () {
        return $this->_iSaveLocationId;
    }    

    function setClickable ($v) {
        $this->_isClickable = $v ? 'on' : '';
    }

    function isClickable () {
        return $this->_isClickable;
    }    

    function setSaveLocationAction ($v) {
        $this->_sSaveLocationAction = $v;
    }

    function getSaveLocationAction () {
        return $this->_sSaveLocationAction;
    }    

    function setDefaultLat ($v) {
        $this->_fDefaultLat = $v;
    }

    function getDefaultLat () {
        return $this->_fDefaultLat;
    }    

    function setDefaultLng ($v) {
        $this->_fDefaultLng = $v;
    }

    function getDefaultLng () {
        return $this->_fDefaultLng;
    }    

    function setDefaultZoom ($v) {
        $this->_iDefaultZoom = $v;
    }

    function getDefaultZoom () {
        return $this->_iDefaultZoom;
    }        

    function setDefaultMapType ($v) {
        $this->_iDefaultMapType = $v;
    }

    function getDefaultMapType () {
        return $this->_iDefaultMapType;
    }        

    function setTemplate ($sName, $sHtml) {
        $this->_aTmpls[$sName] = $sHtml;
    }

    function getTemplate ($sName) {
        return $this->_aTmpls[$sName];
    }        

    function setCanvasId ($v) {
        $this->_sCanvasId = $v;
    }

    function getCanvasId () {
        return $this->_sCanvasId;
    }        

    function setLoadingId ($v) {
        $this->_sLoadingId = $v;
    }

    function getLoadingId () {
        return $this->_sLoadingId;
    }        

    function setJsClass ($v) {
        $this->_sJsClass = $v;
    }

    function getJsClass () {
        return $this->_sJsClass;
    }        

    function setJsClassInstance ($v) {
        $this->_sJsClassInstance = $v;
    }

    function getJsClassInstance () {
        return $this->_sJsClassInstance;
    }        

    function setDrawing ($isReadOnly, $sImagesBaseUrl, $sLoadDataUrl, $sSaveDataUrl) {
        $this->_aDrawing = array (
            'read_only' => $isReadOnly,
            'url_images_base' => $sImagesBaseUrl,
            'url_load_data' => $sLoadDataUrl,
            'url_save_data' => $sSaveDataUrl,
        );
    }

    /********************************************* 
    * functionality
    **********************************************/

    /**
     * print the map
     */
    function display ()
    {
        global $site;

        // get canvas with map
        $ret = $this->getReadyTemplateCanvas ();

        // get save location section
        $ret .= $this->getReadyTemplateSaveLocation ();        
        
        // get default location ad zoom
        $aLoc = $this->getLocation ('array');

        // get js files list
        $ret .= $this->getReadyTemplateJsFiles ();
        
        // generate js code
        $sJsClassInstance = $this->getJsClassInstance();
        $sJs = "        
        window.$sJsClassInstance = new " . $this->getJsClass() . " ('" . $this->getCanvasId() . "', '{$aLoc['gmk_lat']}', '{$aLoc['gmk_lng']}', '{$aLoc['gmk_zoom']}', '{$aLoc['gmk_type']}');
        
        window.$sJsClassInstance.sLangPositionSaved = '" . trim(_t('_Places Location has been succesfully saved')) . "';
        window.$sJsClassInstance.sLangPositionSaveFailed = '" . trim(_t('_Places Location saving failed')) . "';
        window.$sJsClassInstance.sLangGeocodeFailed = '" . trim(_t('_Places Can not geocode following location:')) . "';
        window.$sJsClassInstance.sLangSelectLocation = '" . trim(_t('_Places Please select location first')) . "';
        
        window.$sJsClassInstance.setLoadingId ('" . $this->getLoadingId() . "'); 
        window.$sJsClassInstance.setMapControl ('" . $this->getMapControl() . "');
        window.$sJsClassInstance.setTypeControl ('" . $this->isTypeControl() . "');
        window.$sJsClassInstance.setScaleControl ('" . $this->isScaleControl() . "');
        window.$sJsClassInstance.setOverviewControl ('" . $this->isOverviewControl() . "');
        window.$sJsClassInstance.setLocalSearchControl ('" . $this->isLocalSearch() . "');
        window.$sJsClassInstance.setDragable ('" . $this->isDragable() ."');
        window.$sJsClassInstance.setCustomType ('" . $this->getCustomType() . "');
        window.$sJsClassInstance.setLocationId('" . $this->getSaveLocationId() . "');
        if ('on' == '" . $this->isClickable () . "')
            window.$sJsClassInstance.setClickable();
        ";
        
        if ($this->_aDrawing)
            $sJs .= "
        window.$sJsClassInstance.setDrawingOptions(" . ($this->_aDrawing['read_only'] ? 'true' : 'false') . ", '".$this->_aDrawing['url_images_base']."', '".$this->_aDrawing['url_load_data']."', '".$this->_aDrawing['url_save_data']."');
            ";
            
        $sJs .= "
        window.$sJsClassInstance.init ();
        ";

        $ret .= str_replace ('{js}', $sJs, $this->getTemplate ('js'));

        return $ret;
    }

    function getReadyTemplateJsFiles ()
    {
        $sGoogleKey = $this->getGoogleKey ();        

        $sRet = str_replace ('{google_key}', $sGoogleKey, $this->getTemplate ('js_files'));
        if ($this->_aDrawing) {
            $sRet .= '<script src="' . $site['url'] . K_APP_PATH . 'gmk/gmk_drawing.js" type="text/javascript"></script>';
            $sRet .= '<script src="' . $site['url'] . K_APP_PATH . 'gmk/PolylineEncoder.js" type="text/javascript"></script>';
        }        
        return $sRet;
    }

    function getReadyTemplateCanvas ()
    {
        $sTmplCanvas = $this->getTemplate ('canvas');

        $w = $this->getMapWidth();
        $h = $this->getMapHeight();
        $sTmplCanvas = str_replace ('{width}', is_numeric($w) ? $w . 'px' : $w, $sTmplCanvas);
        $sTmplCanvas = str_replace ('{height}', is_numeric($h) ? $h . 'px' : $h, $sTmplCanvas);
        
        $sTmplCanvas = str_replace ('{canvas_id}', $this->getCanvasId(), $sTmplCanvas);
        $sTmplCanvas = str_replace ('{loading_id}', $this->getLoadingId(), $sTmplCanvas);
        return $sTmplCanvas;
    }

    function getReadyTemplateSaveLocation ()
    {
        global $logged;

        if ($logged['admin'] && $this->getSaveLocationId() > 0)
        {
            $s = str_replace ('{location_id}', $this->getSaveLocationId(), $this->getTemplate ('save'));
            return str_replace ('{js_class_instance}', $this->getJsClassInstance(), $s);
        }
        return '';
    }

    /**
     * save location and return xml return value
     */    
    function saveLocation ($iId, $fLat, $fLng, $fZoom, $iType)
    {
        header ('Content-Type: application/xml');
        return db_res ("INSERT INTO `places_locations` SET `gmk_lat` = '$fLat', `gmk_lng` = '$fLng', `gmk_zoom` = '$fZoom', `gmk_type` = '$iType', `gmk_id` = '$iId' ON DUPLICATE KEY UPDATE `gmk_lat` = '$fLat', `gmk_lng` = '$fLng', `gmk_zoom` = '$fZoom', `gmk_type` = '$iType'") ? '<ret>1</ret>' : '<ret>0</ret>';
    }

    /**
     * get location and return in xml or array format
     */            
    function getLocation ($sFormat)
    {		
        $iId = $this->getSaveLocationId ();

        if ($iId)
        {
            $a = db_arr ("SELECT * FROM `places_locations` WHERE `gmk_id` = '$iId' LIMIT 1");
        }
        else
        {
            $a = array (
                'gmk_id' => 0,
                'gmk_lat' => $this->getDefaultLat(),
                'gmk_lng' => $this->getDefaultLng(),
                'gmk_zoom' => $this->getDefaultZoom(),
                'gmk_type' => $this->getDefaultMapType(),
            );
        }

        if ('array' == $sFormat)
            return $a; 

        header ('Content-Type: application/xml');

        $ret = '<' . '?xml version="1.0" encoding="UTF-8"?' . '>';

        $ret .= '<loc>';
    
        $ret .= "<id><![CDATA[" . $a['gmk_id'] . "]]></id>";
        $ret .= "<lat><![CDATA[" . $a['gmk_lat'] . "]]></lat>";
        $ret .= "<lng><![CDATA[" . $a['gmk_lng'] . "]]></lng>";
        $ret .= "<zoom><![CDATA[" . $a['gmk_zoom'] . "]]></zoom>";
        $ret .= "<type><![CDATA[" . $a['gmk_type'] . "]]></type>";

        $ret .= '</loc>';	

        return $ret;	
    }

    function getLocations ($fMinLat, $fMaxLat, $fMinLng, $fMaxLng, $sCustomType)
    {	
        // overrride this
        return '';
    }

    function onJoin (&$aData)
    {
        $this->geocode ($aData);
    }

    function onEdit (&$aData)
    {
        $this->geocode ($aData);
    }

    function updateLatLng ($iId, $fLat, $fLng)
    {
        return db_res ("UPDATE `Profiles` SET `GmkLat` = '$fLat', `GmkLng` = '$fLng' WHERE `ID` = '$iId' LIMIT 1");
    }

    function updateLatLngFailed ($iId)
    {
        db_res ("CREATE TABLE IF NOT EXISTS `gmk_profiles_without_location` (`gmk_wl_id` INT UNSIGNED NOT NULL,PRIMARY KEY ( `gmk_wl_id` ))");
        return db_res ("INSERT IGNORE INTO `gmk_profiles_without_location` SET `gmk_wl_id` = '$iId'");
    }

    function geocode (&$aData)
    {
        $fLongitude = 0;
        $fLatitude = 0;
        $iId = (int)$aData['ID'];
        $sCountryCode = $aData['Country'];
        $sZip = $aData['zip'];
        $sCity = $aData['City'];

        if (!$iId || empty($sCountryCode))
            return false;

        // try to geocode using Country, City, ZIPCode
        if ($sZip)
        {
            $sAddress = "$sZip $sCountryCode";
            $iRes = $this->geocodeGoogle ($sAddress, $fLongitude, $fLatitude, $sCountryCode);
            if (200 == $iRes)
                return $this->updateLatLng ($iId, $fLongitude, $fLatitude);
        }

        // try to geocode using Country, City
        if ($sCity)
        {
            $sAddress = "$sCity $sCountryCode";
            $iRes = $this->geocodeGoogle ($sAddress, $fLongitude, $fLatitude, $sCountryCode);
            if (200 == $iRes)
                return $this->updateLatLng ($iId, $fLongitude, $fLatitude);
            else
                return $this->updateLatLngFailed ($iId);
        }	
    }

    function geocodeGoogle (&$sAddress, &$fLongitude, &$fLatitude, &$sCountryCode)
    {
        $iRet = 404;

        $sAddress = rawurlencode($sAddress);
        $sUrl = "http://maps.google.com/maps/geo?q=$sAddress&output=xml&key=".$this->getGoogleKey();

        if (function_exists('curl_init'))
        {
                $curl = curl_init();

                curl_setopt($curl, CURLOPT_URL, $sUrl);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HEADER, false);

                $s = curl_exec($curl);

                curl_close($curl);

                if (true === $s) $s = '';
        }	
        else
        {	
            $s = file_get_contents($sUrl);
        }

        if (preg_match ('/<code>(\d+)<\/code>/', $s, $m))
        {
            $iRet = $m[1];
            if (200 != $iRet) return $iRet;

            if (preg_match_all ('/<coordinates>([0-9,\.-]+)<\/coordinates>/', $s, $mCoord))
            {
                if (preg_match_all ('/<CountryNameCode>([A-Za-z]+)<\/CountryNameCode>/', $s, $mCountry))                    
                {
                    foreach ($mCountry AS $k => $v)
                    {
                        if ($sCountryCode == $v[0]) 
                        {
                            // Parse coordinate string
                            list ($fLatitude, $fLongitude, $fAltitude) = explode(",", $mCoord[$k][0]);
                            return $iRet;
                        }
                    }
                }
                else
                {
                    list ($fLatitude, $fLongitude, $fAltitude) = explode(",", $mCoord[1][0]);
                    return $iRet;
                }
            }
        }
        
        return 404;
    }

    function getGoogleKey ()
    {
        $c = new KConfig();
        return $c->get('sGoogleKey');
    }

    function updateExistingLocations ()    
    {
        db_res ("CREATE TABLE IF NOT EXISTS `gmk_profiles_without_location` (`gmk_wl_id` INT UNSIGNED NOT NULL,PRIMARY KEY ( `gmk_wl_id` ))");

        $res = db_res ("SELECT * FROM `Profiles` LEFT JOIN `gmk_profiles_without_location` ON (`ID` = `gmk_wl_id`) WHERE ISNULL(`gmk_wl_id`) AND `GmkLat` = 0 AND `GmkLng` = 0 LIMIT 50");
        if ($res && mysql_num_rows($res))
        {
    ?>
    <html>
    <head>
        <title>Update Profiles Locations</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="refresh" content="300" />
    </head>
    <body>
    <h1>Profiles' Locations Update Is In Progress...</h1>
    <?	
            while ($a = mysql_fetch_array($res))
            {
                $this->geocode ($a);
                echo ".";
                sleep(6);		
            }	
        
        }
        else
        {
    ?>
    <html>
    <head>
        <title>Profiles' Locations Update Completed</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    <body>
    <h1>Profiles' Locations Update Completed</h1>
    <?php
            
        }		
    ?>
    </body>
    </html>
    <?
    }

}

?>
