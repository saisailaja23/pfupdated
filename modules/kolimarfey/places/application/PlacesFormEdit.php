<?php

require_once(BX_DIRECTORY_PATH_ROOT . K_APP_PATH . 'PlacesFormAdd.php');

class PlacesFormEdit extends PlacesFormAdd
{    
    function PlacesFormEdit ()
    {
        parent::PlacesFormAdd();
        unset($this->aForm['fields']['pl_image']);
    }
}

?>
