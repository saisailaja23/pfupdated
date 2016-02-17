<?php

require_once(BX_DIRECTORY_PATH_ROOT . K_SYS_PATH . 'KForm.php');

class PlacesFormCatEdit extends KForm
{    
    var $aForm = array (

        'name' => 'form_cat',

        'attributes' => array(
            'enctype' => 'multipart/form-data',
        ),
        
        'table' => 'places_cat',
        'key' => 'pl_cat_id',

        'fields' => array (

            'pl_cat_id' => array (
                'type' => 'hidden',
                                
                'required' => 1,

                'db' => 'pl_cat_id',
            ),

            'pl_cat_name' => array (
                'label' => 'Places Category Name',
                'type' => 'text',
                                
                'required' => 1,
                'error' => 'Places please specify category name',

                'db' => 'pl_cat_name',
            ),

            'pl_cat_pic' => array (
                'label' => 'Places Category Icon',
                'type' => 'file',
            ),

            'submit' => array (
                'type' => 'submit',
                'required' => 0,
                'val' => 'Places Submit',
            ),        
        ),
        
    );

    function PlacesFormCat ()
    {
        parent::KForm();
    }
}

?>
