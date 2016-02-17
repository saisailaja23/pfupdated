<?php

require_once(BX_DIRECTORY_PATH_ROOT . K_SYS_PATH . 'KHelperHtml.php');

class PlacesHelperHtml extends KHelperHtml
{    
    function PlacesHelperHtml ()
    {
        parent::KHelperHtml();
    }

    function _img ($sImgType, $iImgId)
    {        
        echo '<img src="' . $this->_imgHref($sImgType, $iImgId) . '" />';
    }

    function _imgHref ($sImgType, $iImgId)
    {
        global $site;
        return $site['url'] . K_URL . 'image/' . $sImgType . '/' . $iImgId . '.jpg';
    }

    function thumb ($iImgId)
    {
        $this->_img('thumb', $iImgId);
    }

    function thumb_href ($iImgId)
    {
        echo $this->_imgHref('thumb', $iImgId);
    }
    
    function big ($iImgId)
    {
        $this->_img('big', $iImgId);
    }

    function info(&$aPlace, &$aForm)
    {
        $this->tableBegin(array());
        foreach ($aForm['fields'] as $k => $a)
        {            
            if (!isset($a['view']) || !$a['view'] || empty($aPlace[$a['db']])) continue;

            switch (true)
            {
                case ('set' == $a['type']):
                    $this->_load('Form', 'HelperHtml');
                    $aVal = $this->form->getValuesArray ($a);
                    $sVal = '';
                    foreach ($aVal as $kk => $vv)
                        if ($kk & $aPlace[$a['db']])
                            $sVal .= (isset($a['translate']) ? $this->t($vv) : $vv) . ', ';
                    $sVal = substr ($sVal, 0, -2);
                    break;
                case (isset($a['translate']) && $a['translate']): 
                    $sVal = $this->t($aPlace[$a['db']]);
                    break;
                case (isset($a['retranslate']) && $a['retranslate']): 
                    $sVal = $this->t('_'.$aPlace[$a['db']]);
                    break;                    
                default:
                    $sVal = $aPlace[$a['db']];
            }

            switch ($k)
            {
            case 'pl_name':
                $sVal = "<b>$sVal</b>";
                break;                
            case 'pl_created':
                $sVal = $aPlace['pl_created_f'];
                break;
            }
            $this->row($a['label'], $sVal);
        }
        if ($aPlace['NickName'])
        $this->row('Places Posted By', $this->url($aPlace['NickName'], getProfileLink($aPlace['pl_author_id']), false, array(), false));
        if ($sTags = $this->placesTags($aPlace))
            $this->row('Places Tags', $sTags);
        $this->tableEnd();
    }

    function photos(&$aPlacePhotos)
    {
        echo '<div id="k_photo">';
        echo '<table cellpadding="0" cellspacing="0" height="256" width="256" align="center"><tr><td valign="middle">';
        echo '<a target="_blank" href="javascript:void(0);" onclick="return placesShowRealImage(this);">';
        $this->big($aPlacePhotos ? $aPlacePhotos[0]['pl_img_id'] : 0);
        echo '</a>';
        echo '</td></tr></table>';
        echo '</div>';
        
        if (!$aPlacePhotos) return;

        echo '<div class="k_thumb_list">';
        foreach ($aPlacePhotos as $a)
        {
            echo '<div class="k_thumb"><a href="javascript:void(0);" onclick="placesShowBigImage(\'' . $this->_imgHref('big', $a['pl_img_id']) . '\')">';
            $this->thumb($a['pl_img_id']);
            echo '</a></div>';
        }
        echo '</div>';
    }

    function videos(&$aPlaceVideos)
    {
        if (!$aPlaceVideos)
        {
            $this->t('Places No videos yet');
            return;
        }

        echo '<div id="k_video">';
        echo $aPlaceVideos[0]['pl_video_embed'];
        echo '</div>';

        echo '<div class="k_thumb_video_list">';
        foreach ($aPlaceVideos as $a)
        {
            echo '<div class="k_thumb_video"><a href="javascript:void(0);" onclick="placesShowBigVideo(\'' . rawurlencode($a['pl_video_embed']) . '\')">';
            echo '<img src="' . $a['pl_video_thumb'] . '" />';
            echo '</a></div>';
        }
        echo '</div>';
    }

    function inputLocation (&$oForm, $sName, &$a)
    {
        $sAttr = $this->_getAttributes($a);
        $sVal = $this->_getValue($a);        
        
        $this->_getFormRow ($a['label'], $this->mapEdit($a));
    }    

    function mapEdit ($a)
    {	                
	    $aLoc = array ('gmk_lat' => 0, 'gmk_lng' => 0, 'gmk_zoom' => 1, 'gmk_type' => 0);
	    if (isset($a['place']))
		    $aLoc = array ('gmk_lat' => $a['place']['pl_map_lat'], 'gmk_lng' => $a['place']['pl_map_lng'], 'gmk_zoom' => $a['place']['pl_map_zoom'], 'gmk_type' => $a['place']['pl_map_type']);

        $oGmk = new GMKPlacesEdit ();

        $this->_load('Config', 'HelperHtml');

        $sMapControl = $this->config->get ('place_edit_map_control');
        switch ($sMapControl)
        {
            case 'none': $oGmk->setMapControl(0); break;
            case 'small': $oGmk->setMapControl(1); break;
            case 'large': $oGmk->setMapControl(2); break;
        }        

        $oGmk->setTypeControl($this->config->get('place_edit_type_control'));
        $oGmk->setScaleControl($this->config->get('place_edit_scale_control'));
        $oGmk->setOverviewControl($this->config->get('place_edit_overview_control'));
        $oGmk->setLocalSearchControl($this->config->get('place_edit_localsearch_control'));
        $oGmk->setDragable($this->config->get('place_edit_dragable'));
        
        return '<table style="position:relative;"><td width="348">' . $oGmk->display($aLoc) . $this->t('Places map edit desc') . '</td><td valign="top"><a href="javascript:void(0);" onclick="window.glGMKPlacesEdit.findLocation(document.getElementById(\'id_address\').value.length ? document.getElementById(\'id_address\').value + \' \' + document.getElementById(\'id_city\').value + \' \' + document.getElementById(\'id_country\').value : document.getElementById(\'id_zip\').value + \' \' + document.getElementById(\'id_country\').value)">' . $this->t('Places Suggest') . '</a></td></table>';
    }

    function mapView (&$aPlace)
    {	
        $oGmk = new GMKPlacesView ($aPlace['pl_id'], $aPlace['pl_lat'], $aPlace['pl_lng'], $aPlace['pl_zoom'], $aPlace['pl_type']);

        $this->_load('Config', 'HelperHtml');
        
        $sMapControl = $this->config->get ('place_view_map_control');
        switch ($sMapControl)
        {
            case 'none': $oGmk->setMapControl(0); break;
            case 'small': $oGmk->setMapControl(1); break;
            case 'large': $oGmk->setMapControl(2); break;
        }        

        $oGmk->setTypeControl($this->config->get('place_view_type_control'));
        $oGmk->setScaleControl($this->config->get('place_view_scale_control'));
        $oGmk->setOverviewControl($this->config->get('place_view_overview_control'));
        $oGmk->setLocalSearchControl($this->config->get('place_view_localsearch_control'));
        $oGmk->setDragable($this->config->get('place_view_dragable'));
                

        global $site;
        $sLoadDataUrl = $site['url'] . K_URL . 'draw_load/' . $aPlace['pl_id'];
        $sMap = '<div class="k_place_title">' . $aPlace['pl_name'] . '</div>' . $oGmk->display($aPlace, true, '', $sLoadDataUrl, '');
        $sKmlLayers = $this->_mapKml($aPlace, $oGmk->getJsClassInstance());
        return $sMap . $sKmlLayers;
    }        

    function _mapKml($aPlace, $sJsInstanceName) 
    {
        global $site;

        $sDivId = 'k_places_kml_layers' . rand(0, 9999999);

        $this->_load('Model', 'HelperHtml');
        $aKmlFiles = $this->model->getPlaceKmlFiles ($aPlace['pl_id']);

        if ($aKmlFiles) 
        {
            $sRet = '<div class="k_place_kml_layers" id="' . $sDivId . '">'; 
            foreach ($aKmlFiles as $a)
                $sRet .= ' <div><input type="checkbox" value="' . $site['url'] . K_APP_PATH . 'kml/' . $a['pl_kml_id'] . $a['pl_kml_file_ext'] . '" id="places_kml'.$a['pl_kml_id'].'" checked="checked" /><label for="places_kml'.$a['pl_kml_id'].'">' . $a['pl_kml_name'] . '</label></div> ';
            $sRet .= '</div>';
        

            $sRet .= <<<EOF
<script>
    $(document).ready(function() {        

        $sJsInstanceName.onload = function () {
            glGeoXml = {};
            $('#$sDivId input:checked').each(function(i) {                
                glGeoXml[this.id] = {};
                glGeoXml[this.id]['xml'] = new GGeoXml(this.value);
                $sJsInstanceName._map.addOverlay(glGeoXml[this.id]['xml']);
                glGeoXml[this.id]['visible'] = true;
            });
        };

        $('#$sDivId input').bind("click", function (e) {
            $('#$sDivId input:checked').each(function(i) {
                if (!glGeoXml[this.id]['visible']) {
                    $sJsInstanceName._map.addOverlay(glGeoXml[this.id]['xml']);
                    glGeoXml[this.id]['visible'] = true;
                }
            }); 
            $('#$sDivId input:not(:checked)').each(function(i) {
                if (glGeoXml[this.id]['visible']) {
                    $sJsInstanceName._map.removeOverlay(glGeoXml[this.id]['xml']);
                    glGeoXml[this.id]['visible'] = false;
                }
            }); 
        });
    });
</script>
EOF;
        }
        return $sRet;
    }        

    function mapDraw (&$aPlace)
    {	
        $oGmk = new GMKPlacesView ($aPlace['pl_id'], $aPlace['pl_lat'], $aPlace['pl_lng'], $aPlace['pl_zoom'], $aPlace['pl_type']);

        $this->_load('Config', 'HelperHtml');
        
        $sMapControl = $this->config->get ('place_view_map_control');
        switch ($sMapControl)
        {
            case 'none': $oGmk->setMapControl(0); break;
            case 'small': $oGmk->setMapControl(1); break;
            case 'large': $oGmk->setMapControl(2); break;
        }        

        $oGmk->setTypeControl($this->config->get('place_view_type_control'));
        $oGmk->setScaleControl($this->config->get('place_view_scale_control'));
        $oGmk->setOverviewControl($this->config->get('place_view_overview_control'));
        $oGmk->setLocalSearchControl($this->config->get('place_view_localsearch_control'));
        $oGmk->setDragable($this->config->get('place_view_dragable'));

        global $site;
        $sImagesBaseUrl = $site['url'] . K_APP_PATH . 'gmk/icons/';
        $sLoadDataUrl = $site['url'] . K_URL . 'draw_load/' . $aPlace['pl_id'];
        $sSaveDataUrl = $site['url'] . K_URL . 'draw_save/' . $aPlace['pl_id'];
        return $oGmk->display ($aPlace, false, $sImagesBaseUrl, $sLoadDataUrl, $sSaveDataUrl);
    }        

    function mapIndex()
    {
        $oGmk = new GMKPlaces();

        $this->_load('Config', 'HelperHtml');
        
        $sMapControl = $this->config->get ('places_home_map_control');
        switch ($sMapControl)
        {
            case 'none': $oGmk->setMapControl(0); break;
            case 'small': $oGmk->setMapControl(1); break;
            case 'large': $oGmk->setMapControl(2); break;
        }        

        $oGmk->setTypeControl($this->config->get('places_home_type_control'));
        $oGmk->setScaleControl($this->config->get('places_home_scale_control'));
        $oGmk->setOverviewControl($this->config->get('places_home_overview_control'));
        $oGmk->setLocalSearchControl($this->config->get('places_home_localsearch_control'));
        $oGmk->setDragable($this->config->get('places_home_dragable'));

        $this->_load('Model', 'HelperHtml');
        $sMap = $oGmk->display();
        $sFilter = $this->_mapFilter($oGmk->getJsClassInstance());
        return $sFilter . $sMap;
    }

    function _mapFilter($sJsInstanceName)
    {
        $sDivId = 'k_places_filter' . rand(0, 9999999);
        $this->_load('Model', 'HelperHtml');
        $aCats = $this->model->getCats (true);
        $sRet = '<style>' . file_get_contents(BX_DIRECTORY_PATH_ROOT . K_APP_PATH . 'template/css/cat_filter.css') . '</style>';

        $sRet .= '<div id="' . $sDivId . '">';
        foreach ($aCats AS $a)
            $sRet .= ' <div class="k_cat_filter_row"><img src="' . PLACES_ICONS_PATH . ($a['pl_cat_icon'] ? $a['pl_cat_icon'] : '0.png') . '" /><input type="checkbox" value="'.$a['pl_cat_id'].'" id="places_cat'.$a['pl_cat_id'].'" checked="checked" /><label for="places_cat'.$a['pl_cat_id'].'">' . $this->t($a['pl_cat_name']) . '</label></div> ';
        $sRet .= '<div class="clear_both"></div>';
        $sRet .= '</div>';
        $sRet .= <<<EOF
<script>
    $(document).ready(function() {        
        $('#$sDivId input').bind("click", function (e) {
            var sVal = '';
            $('#$sDivId input:checked').each(function(i) { 
                sVal += this.value + ',';
            }); 
            $sJsInstanceName.setCustomType(sVal);
            $sJsInstanceName.showLocations();
        });
    });
</script>
EOF;
        return $sRet;
    }

    function placesTags($aPlace, $sDiv= ',')
    {
        $sRet = '';
        $a = explode ($sDiv, $aPlace['pl_tags']);
        foreach ($a as $sName)
            if (strlen($sName) > 2)
                $sRet .= '<a href="' . $this->href('browse_by_tag/' . title2uri(trim($sName)), false) . '">' . $sName . '</a> ';
        return $sRet;
    }

    function rss_edit($iPlaceId) 
    {
        $this->_rss_action('kRSSForm', $iPlaceId, $iRssNum);
    }

    function rss_view($iPlaceId, $iRssNum) 
    {
        $this->_rss_action('kRSSFeed', $iPlaceId, $iRssNum);
    }    

    function _rss_action($sAction, $iPlaceId, $iRssNum) 
    {
        $sLoading = _t( '_loading ...' );
        echo <<<EOS
    		<div class="kRSSAggrCont" rssid="$iPlaceId" rssnum="$iRssNum">
	    		<div class="loading_rss">
		    		<img src="templates/tmpl_{$GLOBALS['tmpl']}/images/loading.gif" alt="$sLoading" />
			    </div>
            </div>
            <script language="javascript">
        		$(document).ready( function() { //onload
	        	    $('div.kRSSAggrCont').$sAction();
                } );    
            </script>
EOS;
    }        
}

?>
