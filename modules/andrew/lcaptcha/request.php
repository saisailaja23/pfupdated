<?php

require_once(BX_DIRECTORY_PATH_INC . 'profiles.inc.php');
check_logged();
require_once(BX_DIRECTORY_PATH_CLASSES . 'BxDolRequest.php');
BxDolRequest::processAsAction($GLOBALS['aModule'], $GLOBALS['aRequest']);
