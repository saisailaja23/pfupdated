<?php

require_once(BX_DIRECTORY_PATH_ROOT . K_SYS_PATH . 'KForm.php');

class PlacesFormAdd extends KForm
{    
    var $aForm = array (

        'name' => 'form1',

        'attributes' => array(
            'enctype' => 'multipart/form-data',
        ),

        'fields' => array (

            'pl_name' => array (
                'label' => 'Places Name',
                'type' => 'text',
                'attributes' => array('size' => 30),
                
                'required' => 1,
                'validator' => 'checkMin',
                'validator_params' => array ('min' => 3),
                'error' => 'Places name length must be at least 3 characters',
                'db' => 'pl_name',
                'view' => 1,
            ),

            'pl_author' => array (
                'label' => 'Places Posted By',
                'type' => 'text',
                'attributes' => array('size' => 30),
                
                'required' => 1,
                'validator' => 'checkMin',
                'validator_params' => array ('min' => 3),
                'error' => 'Places name length must be at least 3 characters',
                'db_save' => 0,
                'view' => 1,                    
            ),

            'pl_desc' => array (
                'label' => 'Places Description',
                'type' => 'tinymce',
                'attributes' => array('cols' => 70, 'rows' => 20),
                
                'required' => 1,
                'validator' => 'checkMinMax',
                'validator_params' => array ('min' => 10, 'max' => 16000),
                'error' => 'Places description length must be 10-16000 characters',
                'db' => 'pl_desc',
                'view' => 0,
            ),

            'pl_cat' => array (
                'label' => 'Places Category',
                'type' => 'select',

                'values' => 'SELECT `pl_cat_id` AS `key`, `pl_cat_name` AS `value` FROM `places_places_cat` ORDER BY `pl_cat_id` ASC',
                'values_type' => 'sql',
                'required' => 1,
                'validator' => 'checkMinNum',
                'validator_params' => array ('min' => 2),
                'error' => 'Places please select a category',
                'db' => 'pl_cat',
                'db_type' => 'int',
                'translate' => 1,
            ),

            'pl_cat_name' => array (
                'label' => 'Places Category',
                'db' => 'pl_cat_name',
                'db_save' => 0,
                'view' => 1,
                'translate' => 1,
            ),            

            'pl_tags' => array (
                'label' => 'Places Tags',
                'type' => 'text',
                'attributes' => array('size' => 30),

                'db' => 'pl_tags',
            ),            

            'pl_country' => array (
                'label' => 'Places Country',
                'type' => 'select',
                'attributes' => array('id' => 'id_country'),

                'values' => 'SELECT `ISO2` AS `key`, `Country` AS `value` FROM `sys_countries` ORDER BY `Country` ASC',
                'values_type' => 'sql',
                'required' => 1,
                'error' => 'Places please select a country',
                'db' => 'pl_country',
                'retranslate' => 1,
                'sort' => 1,
            ),

            'pl_country_name' => array (
                'label' => 'Places Country',
                'db' => 'pl_country_name',
                'db_save' => 0,
                'view' => 1,
                'retranslate' => 1,
            ),            

            'pl_city' => array (
                'label' => 'Places City',
                'type' => 'text',
                'attributes' => array('size' => 30, 'id' => 'id_city'),
                
                'required' => 1,
                'validator' => 'checkMin',
                'validator_params' => array ('min' => 2),
                'error' => 'Places city length must be at least 2 characters',
                'db' => 'pl_city',
                'view' => 1,
            ),

            'pl_zip' => array (
                'label' => 'Places Zip',
                'type' => 'text',
                'attributes' => array('size' => 10, 'id' => 'id_zip'),
                
                'required' => 0,
                'validator' => 'checkMin',
                'validator_params' => array ('min' => 2),
                'error' => 'Places zip length must be at least 2 characters',
                'db' => 'pl_zip',
                'view' => 1,
            ),
            
            'pl_address' => array (
                'label' => 'Places Address',
                'type' => 'text',
                'attributes' => array('size' => 30, 'id' => 'id_address'),
                
                'required' => 0,
                'db' => 'pl_address',
                'view' => 1,
            ),
            
            'pl_image' => array (
                'label' => 'Places Image',
                'type' => 'file',
                                
                'required' => 1,
                'error' => 'Places please choose an image',
            ),

            'pl_location' => array (
                'label' => 'Places Location',
                'type' => 'location',
                'attributes' => array('style' => 'width:200px; height:200px; border:1px solid black;'),
                                
                'required' => 1,
                'validator' => 'checkLocation',
                'validator_params' => array ('lat' => 'gmk_lat', 'lng' => 'gmk_lng', 'zoom' => 'gmk_zoom', 'type' => 'gmk_type'),
                'error' => 'Places please choose location',
            ),

            'gmk_lat' => array (
                'label' => 'Lat',
                'type' => 'hidden',
                'attributes' => array('id' => 'gmk_lat'),
                
                'db' => 'pl_map_lat',
            ),

            'gmk_lng' => array (
                'label' => 'Lng',
                'type' => 'hidden',
                'attributes' => array('id' => 'gmk_lng'),
                
                'db' => 'pl_map_lng',
            ),
            

            'gmk_zoom' => array (
                'label' => 'Zoom',
                'type' => 'hidden',
                'attributes' => array('id' => 'gmk_zoom'),
                
                'db' => 'pl_map_zoom',
            ),

            'gmk_type' => array (
                'label' => 'Type',
                'type' => 'hidden',
                'attributes' => array('id' => 'gmk_type'),
                
                'db' => 'pl_map_type',
            ),
            
            'pl_author_id' => array (
                'label' => 'Places Posted By',
                'type' => 'hidden',
                
                'db' => 'pl_author_id',
            ),

            'pl_status' => array (
                'label' => 'Places Status',
                'type' => 'hidden',
                
                'db' => 'pl_status',
            ),                        

            'pl_created' => array (
                'label' => 'Places Created',
                'type' => 'hidden',
                
                'db' => 'pl_created',
                'view' => 1,
            ),            

            'pl_uri' => array (
                'type' => 'hidden',                
                'db' => 'pl_uri',
            ),

            'submit' => array (
                'type' => 'submit',
                'required' => 0,
                'val' => 'Places Submit',
            ),        
        ),
        
    );

    function PlacesFormAdd ()
    {
        parent::KForm();

        if (!$GLOBALS['logged']['admin'])
            unset($this->aForm['fields']['pl_author']);
    }

    function insertResizedImage($sPostName, $sTable, $sId, &$aSizes, $sImagesPath)
    {
        // create temp file
        $sTmpFilename = $sImagesPath . 'tmp_' . rand (1, 99999);
        $sExt = moveUploadedImage ($_FILES, $sPostName, $sTmpFilename, '', false);
        if ($sExt == IMAGE_ERROR_WRONG_TYPE && !$sExt)
        {
            return false;
        }
        
        $sTmpFilename = $sTmpFilename . $sExt;

        // update db
        $this->_load('Model', 'Form');
        if (!($sImgId = $this->model->insertImage($sId)))
        {
            @unlink ($sTmpFilename);
            return false;
        }

        // create small thumb of image
        $sThumbFilename = $sImagesPath . $sImgId . PLACES_IMAGE_EXT;        
        $ret = imageResize ($sTmpFilename, $sThumbFilename, $aSizes['thumb']['w'], $aSizes['thumb']['h'], true);
        if (IMAGE_ERROR_SUCCESS != $ret)
        {
            $this->model->deletePhoto($sImgId, $_COOKIE['memberID']);
            @unlink ($sTmpFilename);
            return false;
        }

        // create big image
        $sBigFilename = $sImagesPath . PLACES_IMAGE_BIG . $sImgId . PLACES_IMAGE_EXT;
        $ret = imageResize ($sTmpFilename, $sBigFilename, $aSizes['big']['w'], $aSizes['big']['h'], true);
        if (IMAGE_ERROR_SUCCESS != $ret)
        {
            $this->model->deletePhoto($sImgId, $_COOKIE['memberID']);
            @unlink ($sThumbFilename);
            @unlink ($sTmpFilename);
            return false;
        }

        // create real image
        $sRealFilename = $sImagesPath . PLACES_IMAGE_REAL . $sImgId . PLACES_IMAGE_EXT;
        $ret = imageResize ($sTmpFilename, $sRealFilename, $aSizes['real']['w'], $aSizes['real']['h'], true);
        if (IMAGE_ERROR_SUCCESS != $ret)
        {
            $this->model->deletePhoto($sImgId, $_COOKIE['memberID']);
            @unlink ($sBigFilename);
            @unlink ($sThumbFilename);
            @unlink ($sTmpFilename);
            return false;
        }

        @unlink ($sTmpFilename);
        return $sImgId;
    }    

    function checkLocation ($v, $p)
    {
        return (float)$this->get($p['lat']) || (float)$this->get($p['lng']) ? true : false;
    }    
}

?>
