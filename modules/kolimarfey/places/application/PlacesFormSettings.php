<?php

require_once(BX_DIRECTORY_PATH_ROOT . K_SYS_PATH . 'KForm.php');

class PlacesFormSettings extends KForm
{    
    var $aForm = array (

        'name' => 'form_settings',

        'attributes' => array(
            'enctype' => 'multipart/form-data',
        ),

        'fields' => array (

            'sMapControl' => array (
                'label' => 'Places Map Control',
                'type' => 'select',

                'values' => array ('none' => 'none', 'small' => 'small', 'large' => 'large'),
                'required' => 1,
                'error' => 'Places Please select map control',
                'cat' => 14, // category bit codes 1 | 2 | 4 to show settings
            ),
/*
            'sDataType' => array (
                'label' => 'Map Data Type',
                'type' => 'select',

                'values' => array ('all' => 'all', 'online' => 'online', 'featured' => 'featured'),
                'required' => 1,
                'error' => 'Please select data type',
            ),
*/
            'isTypeControl' => array (
                'label' => 'Places Display Map Type Control',
                'type' => 'checkbox',
                'cat' => 14,
            ),

            'isScaleControl' => array (
                'label' => 'Places Display Map Scale Control',
                'type' => 'checkbox',
                'cat' => 14,
            ),

            'isOverviewControl' => array (
                'label' => 'Places Display Map Overview Control',
                'type' => 'checkbox',
                'cat' => 14,
            ),

            'isLocalSearchControl' => array (
                'label' => 'Places Display Map Local Search Control',
                'type' => 'checkbox',
                'cat' => 14,
            ),

            'isDragable' => array (
                'label' => 'Places Is Map Dragable?',
                'type' => 'checkbox',
                'cat' => 14,
            ),

            'sGoogleKey' => array (
                'label' => 'Places Google Maps Key',
                'type' => 'text',
                'cat' => 16,
            ),

            'iPerPage' => array (
                'label' => 'Places Search Result Per Page',
                'type' => 'text',
                'cat' => 16,
            ),

            'iPerWindow' => array (
                'label' => 'Places Search Result Per Window',
                'type' => 'text',
                'cat' => 16,
            ),

            'isNotifyAdmin' => array (
                'label' => 'Places Notify Admin',
                'type' => 'checkbox',
                'cat' => 16,
            ),
            'isAutoApproval' => array (
                'label' => 'Places Auto Approval',
                'type' => 'checkbox',
                'cat' => 16,
            ),
            'submit' => array (
                'type' => 'submit',
                'required' => 0,
                'val' => 'Places Submit',
            ),

            'iCat' => array (
                'label' => 'iCat',
                'type' => 'hidden',
                'val' => 0
            ),            
        ),
        
    );

    var $aFormMapDb = array (

        2 => array (
            'sMapControl' => 'place_view_map_control',
            'isTypeControl' => 'place_view_type_control',
            'isScaleControl' => 'place_view_scale_control',
            'isOverviewControl' => 'place_view_overview_control',
            'isLocalSearchControl' => 'place_view_localsearch_control',
            'isDragable' => 'place_view_dragable',
        ),
        
        4 => array (
            'sMapControl' => 'places_home_map_control',
            'isTypeControl' => 'places_home_type_control',
            'isScaleControl' => 'places_home_scale_control',
            'isOverviewControl' => 'places_home_overview_control',
            'isLocalSearchControl' => 'places_home_localsearch_control',
            'isDragable' => 'places_home_dragable',
        ),        

        8 => array (
            'sMapControl' => 'place_edit_map_control',
            'isTypeControl' => 'place_edit_type_control',
            'isScaleControl' => 'place_edit_scale_control',
            'isOverviewControl' => 'place_edit_overview_control',
            'isLocalSearchControl' => 'place_edit_localsearch_control',
            'isDragable' => 'place_edit_dragable',
        ),                

        16 => array (
            'sGoogleKey' => 'sGoogleKey',
            'iPerPage' => 'iPerPage',
            'iPerWindow' => 'iPerWindow',
            'isNotifyAdmin' => 'isNotifyAdmin',
            'isAutoApproval' => 'isAutoApproval',
        ),        
    );
    
    
    function PlacesFormAdd ()
    {
        parent::KForm();
    }

    function init ($iCat)
    {
        $this->_load('Config', 'FormSettings');        

        if (!$iCat || !isset($this->aFormMapDb[$iCat])) 
            return false;
        
        $this->set('iCat', $iCat);
        
        $a = array();
        foreach ($this->aForm['fields'] as $k => $r)
        {
            if (!isset($r['cat'])) continue;
            if (!($r['cat'] & $iCat))
                $a[] = $k;
            else
                $this->set($k, $this->config->get ($this->aFormMapDb[$iCat][$k]));
        }
        
        if (!$a) return;
        foreach ($a as $k)
            unset($this->aForm['fields'][$k]);
        
        return true;
    }

    function save ()
    {
        $this->_load('Config', 'FormSettings');
        
        $iCat = $_POST['iCat'];        
        if (!$iCat || !isset($this->aFormMapDb[$iCat])) 
            return false;
        $a = $this->aFormMapDb[$iCat];
        
        foreach ($a as $k => $s)        
            $this->config->set ($s, $_POST[$k]);


        return true;
    }
}

?>
