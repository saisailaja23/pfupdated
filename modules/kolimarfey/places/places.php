<?php

$aPathInfo = pathinfo(__FILE__);

require_once($aPathInfo['dirname'] . '/defines.php');

ob_start();

if (isset($_REQUEST['figures']) && is_array($_REQUEST['figures'])) {
    define ('BX_SECURITY_EXCEPTIONS', true);
    $aBxSecurityExceptions = array ();
    foreach ($_REQUEST['figures'] as $k => $v) {
        $aBxSecurityExceptions[] = 'POST.figures.'.$k;
        $aBxSecurityExceptions[] = 'REQUEST.figures.'.$k;
    }
}

if (isset($_REQUEST['figures'])) {
    define ('BX_SECURITY_HTML', true);
    $aBxSecurityHTML = array ('POST.pl_desc', 'REQUEST.pl_desc');
}

require_once($aPathInfo['dirname'] . '/../../../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'admin.inc.php');
require_once(BX_DIRECTORY_PATH_CLASSES . 'BxDolPageView.php');
require_once(BX_DIRECTORY_PATH_ROOT . K_APP_PATH . 'PlacesController.php');

require_once(BX_DIRECTORY_PATH_ROOT . K_APP_PATH . 'gmk/gmk_places_base.class.php');
require_once(BX_DIRECTORY_PATH_ROOT . K_APP_PATH . 'gmk/gmk_places.class.php');
require_once(BX_DIRECTORY_PATH_ROOT . K_APP_PATH . 'gmk/gmk_places_view.class.php');
require_once(BX_DIRECTORY_PATH_ROOT . K_APP_PATH . 'gmk/gmk_places_edit.class.php');

ob_end_clean();

if (isset($sKAction) && $sKAction)
{
    new PlacesController ($sKAction);
}
else
{
    check_logged();

    bx_import('BxTemplVotingView');
    bx_import('BxTemplCmtsView');

    $oVotingView = new BxTemplVotingView('', 0, 0);
    $oCmtsView = new BxTemplCmtsView('', 0, 0);

    $_ni = 81;
    $_page['name_index'] = $_ni;

    $_page['header'] = _t( "_Places" );
    $_page['header_text'] = _t("_Places");

    $_page['extra_js']  = $oVotingView->getExtraJs();
    $_page['extra_js'] .= $oCmtsView->getExtraJs();
    $_page['extra_js'] .= '<script src="' . $site['url'] . K_APP_PATH . 'template/js/main.js" type="text/javascript" language="javascript"></script>';
    $_page['extra_js'] .= '<script src="' . $site['url'] . K_APP_PATH . 'template/js/jquery.kRSSFeed.js" type="text/javascript" language="javascript"></script>';
    $_page['extra_js'] .= $oTemplConfig->sTinyMceEditorMiniJS;

        
    //$_page['extra_css']  = $oCmtsView->getExtraCss();
    $GLOBALS['oSysTemplate']->addCss($site['url'] . K_APP_PATH . 'template/css/main.css');
    $GLOBALS['oSysTemplate']->addCss('http://www.google.com/uds/solutions/localsearch/gmlocalsearch.css');

    ob_start();
        $oPlaces = new PlacesController (isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : ($_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : $_SERVER['QUERY_STRING']));
    $s = ob_get_clean();

    $_page_cont[$_ni]['page_main_code'] = $s;

    $sPageTitle = $oPlaces->getPageTitle();
    if ($sPageTitle)
        $_page['header'] = $sPageTitle;

    if ($GLOBALS['logged']['member'] || $GLOBALS['logged']['admin'])
    {
        $aVars = array (
            'BaseUri' => $GLOBALS['site']['url'] . K_URL,
            'Username' => getNickName($_COOKIE['memberID']),
        );
        $GLOBALS['oTopMenu']->setCustomSubActions($aVars, 'places_title', false);
    }

    PageCode();
}

?>
