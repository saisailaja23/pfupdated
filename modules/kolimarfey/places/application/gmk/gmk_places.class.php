<?
/*******************************************************************************************
 Places v.1.0
 License: proprietary license product, please read license.txt before using this product.
 Author: Trofimov Alexander (kolimarfey@gmail.com)
*******************************************************************************************/

class GMKPlaces extends GMKPlacesBase
{

    function GMKPlaces ()
    {
        global $site;

        GMKPlacesBase::GMKPlacesBase();
        
        $this->_aTableFields = array (
        	'pl_id', // mandatory
            'pl_uri',
        	'pl_name',
            'pl_desc',
            'pl_thumb',
            'pl_cat',
        );

        $this->setJsClass ('GMKPlaces');
        $this->setJsClassInstance ('glGMKPlaces');
        $this->setSaveLocationId (100);
        $this->setCanvasId('gmk_canvas_' . K_NAME);
        $this->setLoadingId('gmk_canvas_' . K_NAME . '_loading');
    
        $this->setTemplate ('js_files', $this->getTemplate('js_files') .  '<script src="' . $site['url'] . K_APP_PATH . 'gmk/gmk_'.K_NAME.'.js" type="text/javascript"></script>');

        $sUrl = $site['url'] . K_URL;
        $this->_aTmpls['popup'] = <<<EOS
<div id="{div_id}" style="display:{display};">
    <div style="margin-right:16px; border:1px solid #CBCBCB; text-align:center; vertical-align:middle; width:64px; height:64px; float:left;"><img src="{$sUrl}image/thumb/{pl_thumb}" /></div>
    <a href="{$sUrl}view/{pl_uri}">{pl_name}</a><br />
    {pl_desc}
</div>
EOS;

    }


    function getLocations ($fMinLat, $fMaxLat, $fMinLng, $fMaxLng, $sCustomType)
    {	
        global $site, $dir, $date_format;	

        header ('Content-Type: application/xml');

        $ret = '<' . '?xml version="1.0" encoding="UTF-8"?' . '>';

        $ret .= '<prs>';

        $sFields = join('`,`', $this->_aTableFields);

        
        $iLimit = 1000;

    	$sWhere = '';
        if ($sCustomType) {
            $a = explode (',', $sCustomType);
            $aCats = array ();
            foreach ($a as $i) {
                $i = (int)$i;
                if ($i < 2) continue;
                $aCats[$i] = $i;
            }

            if (count($aCats))
                $sWhere = ' AND (`pl_cat` = ' . implode (' OR `pl_cat` = ', $aCats) . ')';
        }

	    $res = db_res ("SELECT DISTINCT `$sFields`, `pl_cat_icon`, `pl_map_lat` AS `Latitude`, `pl_map_lng` AS `Longitude`
		FROM `" . K_NAME . "_places`
        LEFT JOIN `" . K_NAME . "_places_cat` ON (`pl_cat_id` = `pl_cat`)
		WHERE `pl_status` = 'active' AND `pl_map_lat` != 0 AND `pl_map_lng` != 0 AND `pl_map_lat` > '$fMinLat' AND `pl_map_lat` < '$fMaxLat' AND `pl_map_lng` > '$fMinLng' AND `pl_map_lng` < '$fMaxLng' $sWhere LIMIT $iLimit");

        $sIdPrev = '';
        $aRet = array ();
        while ($a = mysql_fetch_array($res))
        {
            if ($sIdPrev == $a['pl_id']) continue;

            $sHtml = $this->getTemplate('popup');
            
            $a['pl_desc'] = strip_tags ($a['pl_desc']);
            if (strlen($a['pl_desc']) > 200) 
                $a['pl_desc'] = substr($a['pl_desc'], 0, 200) . '...';
            
            foreach ($this->_aTableFields as $sField)			
                $sHtml = str_replace('{'.$sField.'}', $a[$sField], $sHtml);
            
            if (!$a['pl_cat_icon'])
                $a['pl_cat_icon'] = '0.png';
            $sKeyId = str_replace (array('.','-'), '_', $a['Latitude'] . '_' . $a['Longitude']);
            if (isset($aRet[$sKeyId]))
            {
                ++$aRet[$sKeyId]['count'];
                $sHtml = str_replace("{div_id}", 'gmk_marker_' . $sKeyId . '_' . $aRet[$sKeyId]['count'], $sHtml);
                $sHtml = str_replace("{display}", 'none', $sHtml);
                $aRet[$sKeyId]['html'] .= $sHtml;
                if ($aRet[$sKeyId]['icon'] != $a['pl_cat_icon'])
                    $aRet[$sKeyId]['icon'] = '00.png';
            }
            else
            {									
                $sHtml = str_replace("{div_id}", 'gmk_marker_' . $sKeyId . '_1', $sHtml);
                $sHtml = str_replace("{display}", 'block', $sHtml);
                $aRet[$sKeyId] = array (
                    'lat' => $a['Latitude'],
                    'lng' => $a['Longitude'],
                    'html' => $sHtml,
                    'count' => 1,
                    'id' => 'gmk_marker_' . $sKeyId . '_',
                    'icon' => $a['pl_cat_icon'],
                );
            }
            $sIdPrev = $a['ID'];
        }

        for ( reset($aRet) ; list($sKeyId, $a) = each ($aRet) ; )
        {		
            if ($a['count'] > 1)
            {
                $sTmplPopupPaginate =  str_replace ('{id}', $a['id'], $this->getTemplate('popup_paginate'));
                $sTmplPopupPaginate =  str_replace ('{count}', $a['count'], $sTmplPopupPaginate);
                $aRet[$sKeyId]['html'] .= $sTmplPopupPaginate;
            }
        }	

        $sRet = '<locations>';
        for ( reset($aRet) ; list($sKeyId, $a) = each ($aRet) ; )
        {
            $sRet .= <<<EOF
    <loc lat="{$a['lat']}" lng="{$a['lng']}" icon="{$a['icon']}"><![CDATA[{$a['html']}]]></loc>
EOF;
        }
        $sRet .= '</locations>';
        
        return $sRet;
    }

}



?>
