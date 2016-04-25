<?php

/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

define ('BX_SECURITY_EXCEPTIONS', true);

require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
bx_import('BxDolDb');
bx_import('BxTemplSearchResult');
bx_import('BxDolCategories');
bx_import('BxDolAdminSettings');

$aBxSecurityExceptions = array ();
if (bx_get('pathes') !== false) {
    $aPathes = bx_get('pathes');

    if(is_array($aPathes))
        for ($i=0; $i<count($aPathes); ++$i) {
            $aBxSecurityExceptions[] = 'POST.pathes.'.$i;
            $aBxSecurityExceptions[] = 'REQUEST.pathes.'.$i;
        }
}

$logged['admin'] = member_auth( 1, true, true );

function actionAllCategories()
{
    $oDb = new BxDolDb();

    // check actions
    if(bx_get('pathes') !== false) {
        $aPathes = bx_get('pathes');

        if(is_array($aPathes) && !empty($aPathes))
            foreach($_POST['pathes'] as $sValue) {
                $sCategory = process_db_input($sValue, BX_TAGS_STRIP);
        
               if(bx_get('action_delete') !== false)  
               
                    $oDb->query("DELETE FROM `letter_caption` WHERE
                        `Caption_ID` = '$sCategory'");
            }
    }


    $oCategories = new BxDolCategories();
    $oCategories->getTagObjectConfig();

    if(empty($oCategories->aTagObjects))
        return MsgBox(_t('_Empty'));

    foreach($oCategories->aTagObjects as $sKey => $aValue) {
        if(!$sModule)
            $sModule = $sKey;
    }


    $aCategories = $oDb->getAll("SELECT * FROM `letter_caption`");
    if(!empty($aCategories)) {
        $mixedTmplItems = array();
        foreach($aCategories as $aCategory)
            $mixedTmplItems[] = array(             
                'value' => $aCategory['Caption_ID'],
                'title'=> $aCategory['Caption_Name'],
            );
    } else
        $mixedTmplItems = MsgBox(_t('_Empty'));

    $sFormName = 'categories_form';
    $sControls = $sControls = BxTemplSearchResult::showAdminActionsPanel($sFormName, array(
        'action_delete' => _t('_categ_btn_delete')
    ), 'pathes');

    $sContent .= $GLOBALS['oAdmTemplate']->parseHtmlByName('categories_list.html', array(
        'top_controls' => $sTopControls,
        'form_name' => $sFormName,
        'bx_repeat:items' => $mixedTmplItems,
        'controls' => $sControls
    ));

    return $sContent;
}

function getHeadingForm()
{
    $oCateg = new BxDolCategories();
    $aTypes = array();
    $oCateg->getTagObjectConfig();

    foreach ($oCateg->aTagObjects as $sKey => $aValue)
        $aTypes[$sKey] = _t($aValue[$oCateg->aObjFields['lang_key']]);

    $aForm = array(

        'form_attrs' => array(
            'name'     => 'category_form',
            'action'   => $_SERVER['REQUEST_URI'],
            'method'   => 'post',
            'enctype' => 'multipart/form-data',
        ),

        'params' => array (
            'db' => array(
                'table' => 'letter_caption',
                'submit_name' => 'submit_form'
            ),
        ),

        'inputs' => array(

            'name' => array(
                'type' => 'text',
                'name' => 'Caption_Name',
                'value' => isset($aUnit['name']) ? $aUnit['name'] : '',
                'caption' => 'Letter Heading',
                'required' => true,
                'checker' => array (
                    'func' => 'length',
                    'params' => array(3, 100),
                    'error' => _t('_categ_form_field_name_err'),
                ),
                'db' => array(
                    'pass' => 'Xss'
                ),
                'display' => true,
            ),
            'submit' => array (
                'type' => 'submit',
                'name' => 'submit_form',
                'value' => _t('_Submit'),
                'colspan' => false,
            ),
        )
    );

    return new BxTemplFormView($aForm);
}

function getAddHeadingForm()
{
    $oForm = getHeadingForm();
    $oForm->initChecker();
    $sResult = '';

    if ($oForm->isSubmittedAndValid()) {
        $oDb = new BxDolDb();
        if ($oDb->getOne("SELECT COUNT(*) FROM `letter_caption` WHERE `Caption_Name` = '" . $oForm->getCleanValue('Caption_Name') . "'") == 0) {

            $oForm->insert($aValsAdd);
            header('Location:' . BX_DOL_URL_ADMIN . 'letter_heading.php');
        } else
            $sResult = sprintf(_t('_letter_exist_err'), $oForm->getCleanValue('Caption_Name'));
    }

    return (strlen($sResult) > 0 ? MsgBox($sResult) : '') .
        $GLOBALS['oAdmTemplate']->parseHtmlByName('design_box_content.html', array('content' => $oForm->getCode()));
}

$iNameIndex = 9;
$aMenu = array(
    'all' => array(
        'title' => 'Custom Letter Headings',
        'href' => $GLOBALS['site']['url_admin'] . 'letter_heading.php.php?action=all',
        '_func' => array ('name' => 'actionAllCategories', 'params' => array()),
    ),

);
$sAction = bx_get('action') !== false ? bx_get('action') : 'all';
$aMenu[$sAction]['active'] = 1;
$sContent = call_user_func_array($aMenu[$sAction]['_func']['name'], $aMenu[$sAction]['_func']['params']);

$_page = array(
    'name_index' => $iNameIndex,
    'css_name' => array('forms_adv.css', 'settings.css', 'categories.css'),
    'header' => 'Add Letter Headings',
);

$_page_cont[$iNameIndex]['page_main_code'] = DesignBoxAdmin('Add Letter Headings', getAddHeadingForm()) .
    DesignBoxAdmin($aMenu[$sAction]['title'], $sContent, $aMenu);

PageCodeAdmin();
