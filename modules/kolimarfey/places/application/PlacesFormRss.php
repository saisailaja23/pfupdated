<?php

require_once(BX_DIRECTORY_PATH_ROOT . K_SYS_PATH . 'KForm.php');

class PlacesFormRss extends KForm
{    
    var $aForm = array (

        'name' => 'form_rss',

        'attributes' => array(
            'onsubmit' => "$.post('places.php/get_rss_form/', $('#form_rss').serialize(), function (s) { if (1 == parseInt(s)) { $('div.kRSSAggrCont').attr('rssnum', $('div.kRSSAggrCont input[name=pl_rss_num]').val()); $('div.kRSSAggrCont').kRSSFeed(); } else alert(s); }); return false;",
            'id' => 'form_rss',
        ),

        'fields' => array (
            
            'pl_rss' => array (
                'label' => 'Places RSS Link',
                'type' => 'text',
                                
                'required' => 1,
            ),

            'pl_rss_num' => array (
                'label' => 'Places RSS Num',
                'type' => 'text',

                'val' => 4,

                'required' => 1,
            ),

            'pl_id' => array (
                'type' => 'hidden',
            ),

            'submit' => array (
                'type' => 'submit',
                'required' => 0,
                'val' => 'Places Submit',
            ),        
        ),
        
    );

    function PlacesFormRss ()
    {
        parent::KForm();
    }

    function init ($iPlaceId, $sLink, $iRssNum)
    {
        $this->aForm['fields']['pl_id']['val'] = $iPlaceId;
        $this->aForm['fields']['pl_rss']['val'] = $sLink;
        $this->aForm['fields']['pl_rss_num']['val'] = $iRssNum;
    }    
}

?>
