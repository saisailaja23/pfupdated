<?php

require_once(BX_DIRECTORY_PATH_ROOT . K_APP_PATH . 'PlacesFormAdd.php');

class PlacesFormImage extends PlacesFormAdd
{    
    var $aForm = array (

        'name' => 'form2',

        'attributes' => array(
            'enctype' => 'multipart/form-data',
        ),

        'fields' => array (
            
            'pl_image' => array (
                'label' => 'Places Image',
                'type' => 'file',
                                
                'required' => 1,
                'error' => 'Places please choose an image',
            ),

            'submit' => array (
                'type' => 'submit',
                'required' => 0,
                'val' => 'Places Submit',
            ),        
        ),
        
    );

    function PlacesFormImage ()
    {
        parent::PlacesFormAdd();
    }
}

?>
