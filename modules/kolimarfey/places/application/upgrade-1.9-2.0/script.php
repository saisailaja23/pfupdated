<?php
    bx_import('BxDolTags');
    $oTags = new BxDolTags ();
    $aAllPlaces = $model->getAll("SELECT * FROM `places_places`");
    foreach ($aAllPlaces as $aRow)
    {        
        $oTags->reparseObjTags('places', $aRow['pl_id']);
    }
    return '';
?>
