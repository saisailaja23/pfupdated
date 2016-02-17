<?php

require_once(BX_DIRECTORY_PATH_ROOT . K_APP_PATH . 'PlacesFormAdd.php');

class PlacesFormVideo extends PlacesFormAdd
{    
    var $aForm = array (

        'name' => 'form3',

        'attributes' => array(
            'enctype' => 'multipart/form-data',
        ),

        'fields' => array (
            
            'pl_video' => array (
                'label' => 'Places YouTube/Dolphin video embed',
                'type' => 'text',
                                
                'required' => 1,
                'error' => 'Places please insert video embed',
            ),

            'submit' => array (
                'type' => 'submit',
                'required' => 0,
                'val' => 'Places Submit',
            ),        
        ),
        
    );

    function PlacesFormVideo ()
    {
        parent::PlacesFormAdd();
    }

    function insertEmbedVideo($iPlaceId)
    {
        $s = trim($this->get ('pl_video'));
        $sThumb = '';

        if ($this->_isDolphinVideoEmbed ($s))
            $sThumb = $this->_parseDolphinVideoEmbed ($s);
        else
        {
            $sThumb = $this->_parseYoutubeVideoEmbed ($s);
            $s = preg_replace ('/width=\\\"(\d+)\\\"/', 'width=\"100%\"', $s);
            $s = preg_replace ('/width=\"(\d+)\"/', 'width="100%"', $s);
        }

        if (!$sThumb)
            return false;

        $this->_load('Model', 'Form');
        if (!($iVideoId = $this->model->insertVideo($iPlaceId, $sThumb, $s)))
            return false; 

        return $iVideoId;
    }

    function _isDolphinVideoEmbed ($s)
    {
        if (preg_match('#ray/modules/global#', $s))
            return true;
        return false;
    }

    function _parseDolphinVideoEmbed ($s)
    {
        // get file id
        if (!preg_match ('#file=(\d+)#', $s, $m))
            return false;
        $iId = $m[1];

        // get site url
        if (!preg_match ('#url=(.*?)ray/XML.php#', $s, $m))
            return false;
        $sSiteUrl = $m[1];

        return $sSiteUrl . "ray/modules/movie/files/{$iId}_small.jpg";
    }    

    function _parseYoutubeVideoEmbed ($s)
    {
        if (!preg_match ('/v\/([A-Za-z0-9_-]+)/', $s, $m))
            return false;

        return 'http://i.ytimg.com/vi/'.$m[1].'/default.jpg';
    }        
}

?>
