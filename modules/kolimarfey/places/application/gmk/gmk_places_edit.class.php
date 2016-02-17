<?
/*******************************************************************************************
 Places v.1.0
 License: proprietary license product, please read license.txt before using this product.
 Author: Trofimov Alexander (kolimarfey@gmail.com)
*******************************************************************************************/

class GMKPlacesEdit extends GMKPlacesBase
{

    function GMKPlacesEdit ()
    {
        global $site;

        GMKPlacesBase::GMKPlacesBase();
        
        $this->_aTableFields = array (
        	'ID', // mandatory
        	'Title',
        );

        $this->setJsClass ('GMKPlacesEdit');
        $this->setJsClassInstance ('glGMKPlacesEdit');
        $this->setSaveLocationId (0);
        
        $this->setTemplate ('js_files', $this->getTemplate('js_files') .  '<script src="' . $site['url'] . K_APP_PATH . 'gmk/gmk_'.K_NAME.'_edit.js" type="text/javascript"></script>');
    }

    function display ($aLoc)
    {
        $this->setDefaultLat ($aLoc['gmk_lat']);
        $this->setDefaultLng ($aLoc['gmk_lng']);
        $this->setDefaultZoom ($aLoc['gmk_zoom']);
        $this->setDefaultMapType ($aLoc['gmk_type']);

        $this->setClickable(true);

        return GMKPlacesBase::display ();
    }        

}

?>
