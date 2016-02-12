<?php

/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */
define('BX_SECURITY_EXCEPTIONS', true);

require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
bx_import('BxDolDb');
bx_import('BxTemplSearchResult');
bx_import('BxDolCategories');
bx_import('BxDolAdminSettings');

$aBxSecurityExceptions = array();
if (bx_get('pathes') !== false) {
    $aPathes = bx_get('pathes');

    if (is_array($aPathes))
        for ($i = 0; $i < count($aPathes); ++$i) {
            $aBxSecurityExceptions[] = 'POST.pathes.' . $i;
            $aBxSecurityExceptions[] = 'REQUEST.pathes.' . $i;
        }
}

$logged['admin'] = member_auth(1, true, true);

function generateBadgeLink() {
    $oDb = new BxDolDb();

    if (isset($_GET['width']) && $_GET['width'] >= 20) {
        $width = (int) $_GET['width'];
    } else {
        $width = 100;
    }

    $embedd_div_id = date("mdGis") . 'm1' . 'g' . 'c' . substr(md5(uniqid(rand(), true)), 0, 5);
    $sortBy = $_REQUEST['sort_by'];
    $embedd_code = '<!--Badge Start --><div id="' . $embedd_div_id . '"><script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.min.js" ></script></div><script type="text/javascript" src="' . $GLOBALS['site'][url] . 'load_badge_widget.php?width=' . $width . '&order=' . $sortBy . '&display=featuredfamiliesbadge" onload="document.getElementById(\'' . $embedd_div_id . '\').innerHTML=\'\';"></script><!-- Badge End -->';

    if ($sortBy)
        $mixedTmplItems[] = array(
            'value' => $embedd_code
        );
    else
        $mixedTmplItems[] = array(
            'value' => ''
        );

    $sContent .= $GLOBALS['oAdmTemplate']->parseHtmlByName('featured_badgecode.html', array(
        'bx_repeat:items' => $mixedTmplItems,
    ));

    return $sContent;
}

function getHeadingForm() {
    $aForm = array(
        'form_attrs' => array(
            'name' => 'featured_badge',
            'action' => $_SERVER['REQUEST_URI'],
            'method' => 'post',
            'enctype' => 'multipart/form-data',
        ),
        'params' => array(
            'submit_name' => 'create_badge'
        ),
        'inputs' => array(
            'name' => array(
                'type' => 'select',
                'name' => 'sort_by',
                'values' => array(
                    'random' => 'Random',
                    'oldFirst' => 'Oldest Waiting',
                    'newFirst' => 'Newest First',
                    'FirstName' => 'Alphabetic',
                ),
                'caption' => 'Sort By',
                'required' => true,
                'checker' => array(
                    'func' => 'length',
                    'params' => array(3, 100),
                    'error' => _t('_categ_form_field_name_err'),
                ),
                'db' => array(
                    'pass' => 'Xss'
                ),
                'display' => true,
            ),
            'submit' => array(
                'type' => 'submit',
                'name' => 'create_badge',
                'value' => 'Create Badge',
                'colspan' => false,
            ),
        )
    );

    return new BxTemplFormView($aForm);
}

function getAddHeadingForm() {
    $oForm = getHeadingForm();
    $oForm->initChecker();
    $sResult = '';
    return (strlen($sResult) > 0 ? MsgBox($sResult) : '') .
            $GLOBALS['oAdmTemplate']->parseHtmlByName('design_box_content.html', array('content' => $oForm->getCode()));
}

$iNameIndex = 9;
$aMenu = array(
    'all' => array(
        'title' => 'Copy the Badge code',
        '_func' => array('name' => 'generateBadgeLink', 'params' => array()),
    ),
);
$sAction = bx_get('action') !== false ? bx_get('action') : 'all';
$aMenu[$sAction]['active'] = 1;
$sContent = call_user_func_array($aMenu[$sAction]['_func']['name'], $aMenu[$sAction]['_func']['params']);

$_page = array(
    'name_index' => $iNameIndex,
    'css_name' => array('forms_adv.css', 'settings.css', 'categories.css'), //custom css or default css?
    'header' => 'Featured Families Badge',
);

$_page_cont[$iNameIndex]['page_main_code'] = DesignBoxAdmin('Generate Featured Families Badge', getAddHeadingForm()) .
        DesignBoxAdmin($aMenu[$sAction]['title'], $sContent, $aMenu);

PageCodeAdmin();
