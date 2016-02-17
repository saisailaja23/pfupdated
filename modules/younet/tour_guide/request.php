<?php
bx_import('BxDolRequest');

class YnTourGuideRequest extends BxDolRequest {

    function YnTourGuideRequest() {
        parent::BxDolRequest();
    }

    function processAsAction($aModule, &$aRequest, $sClass = "Module") {
        return BxDolRequest::processAsAction($aModule, $aRequest, $sClass);
    }
}

YnTourGuideRequest::processAsAction($GLOBALS['aModule'], $GLOBALS['aRequest']);