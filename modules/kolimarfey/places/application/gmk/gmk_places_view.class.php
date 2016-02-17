<?
/*******************************************************************************************
 Places v.1.0
 License: proprietary license product, please read license.txt before using this product.
 Author: Trofimov Alexander (kolimarfey@gmail.com)
*******************************************************************************************/

class GMKPlacesView extends GMKPlaces
{

    function GMKPlacesView ()
    {
        global $site;

        GMKPlaces::GMKPlaces();
        
        $this->setJsClass ('GMKPlacesView');
        $this->setJsClassInstance ('glGMKPlacesView');
        $this->setSaveLocationId (0);
        
        $this->setTemplate ('js_files', $this->getTemplate('js_files') .  '<script src="' . K_APP_PATH . 'gmk/gmk_'.K_NAME.'_view.js" type="text/javascript"></script>');
    }

    function display ($aPlace, $isDrawReadonly = true, $sImagesBaseUrl = '', $sLoadDataUrl = '', $sSaveDataUrl = '', $isDrawEnabled = true)
    {
        if (0 == $aPlace['pl_map_lat'] && 0 == $aPlace['pl_map_lng'])
        {
            return '';
        }
        
        $this->setDefaultLat ($aPlace['pl_map_lat']);
        $this->setDefaultLng ($aPlace['pl_map_lng']);
        $this->setDefaultZoom ($aPlace['pl_map_zoom']);        
        $this->setDefaultMapType ($aPlace['pl_map_type']);
        if ($isDrawEnabled)
            $this->setDrawing ($isDrawReadonly, $sImagesBaseUrl, $sLoadDataUrl, $sSaveDataUrl);

        return GMKPlacesBase::display ();
    }        

    function getReadyTemplateSaveLocation ()
    {
        return '';
    }        
}

?>
