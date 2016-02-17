<?php

require_once(BX_DIRECTORY_PATH_ROOT . K_SYS_PATH . 'KForm.php');

class PlacesFormKml extends KForm
{    
    var $aForm = array (

        'name' => 'form_kml',

        'attributes' => array(
            'enctype' => 'multipart/form-data',
        ),
        
        'table' => 'places_kml_files',
        'key' => 'pl_kml_id',

        'fields' => array (
            
            'pl_kml_name' => array (
                'label' => 'Places KML Name',
                'type' => 'text',
                                
                'required' => 1,
                'error' => 'Places please specify KML file title',

                'db' => 'pl_kml_name',
            ),

            'pl_kml_file' => array (
                'label' => 'Places KML file',
                'type' => 'file',
            ),

            'pl_kml_file_ext' => array (
                'type' => 'hidden',                
                'db' => 'pl_kml_file_ext',
            ),

            'pl_id' => array (
                'type' => 'hidden',                
                'db' => 'pl_id',
            ),

            'pl_kml_added' => array (
                'type' => 'hidden',                
                'db' => 'pl_kml_added',
            ),

            'submit' => array (
                'type' => 'submit',
                'required' => 0,
                'val' => 'Places Submit',
            ),        
        ),
        
    );

    function PlacesFormKml ()
    {
        parent::KForm();
    }
}

?>
