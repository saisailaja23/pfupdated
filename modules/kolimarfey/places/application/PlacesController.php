<?php

define ('PLACES_ICONS_PATH', K_APP_PATH . 'icons/');
define ('PLACES_IMAGES_PATH', K_APP_PATH . 'photos/');
define ('PLACES_VIDEOS_PATH', K_APP_PATH . 'videos/');
define ('PLACES_KML_PATH', K_APP_PATH . 'kml/');
define ('PLACES_IMAGE_BIG', 'big/');
define ('PLACES_IMAGE_REAL', 'real/');
define ('PLACES_IMAGE_EXT', '.jpg');
    
require_once(BX_DIRECTORY_PATH_ROOT . K_SYS_PATH . 'KController.php');

class PlacesController extends KController
{    
    var $sPageTitle = '';
    var $iMemberId = 0;
    var $aSizes = array(
        'real' => array ('w' => 1024, 'h' => 768),
        'big' => array ('w' => 256, 'h' => 256),
        'thumb' => array ('w' => 64, 'h' => 64),
        );
    
    function PlacesController ($sUri)
    {
        $this->iMemberId = $GLOBALS['logged']['member'] || $GLOBALS['logged']['admin'] ? (int)$_COOKIE['memberID'] : 0;
        parent::KController($sUri);
    }

    function getPageTitle ()
    {
        return $this->sPageTitle;
    }
    
    function _setPageTitle ($s)
    {
        return ($this->sPageTitle = $s);
    }

    function _rss () {

    }

    function get_rss_form ($iPlaceId) 
    {
        if (!$this->isAddRssAllowed ())
            $this->_accessDenied();

        $this->_load('View');

        $iPlaceId = isset($_POST['pl_id']) ? (int)$_POST['pl_id'] : (int)$iPlaceId;
        if (!$iPlaceId)
            $this->view->show404();

        $this->_load('HelperHtml');
        $this->_load('Model');
        $aPlace = $this->model->getPlaceById($iPlaceId);
        if (!$aPlace || ($aPlace['pl_status'] != 'active' && $aPlace['pl_author_id'] != $this->iMemberId && !$this->isAdministrator()))    
            $this->view->show404();

        if ($_POST['pl_id']) {
            $iNum = (int)$_POST['pl_rss_num'];
            if (!$iNum) {
                echo $this->t('Places please specify number of RSS items to display');
                exit;
            }
            $sLink = $_POST['pl_rss'];
            $sRegex = "http\:\/\/"; // SCHEME
            $sRegex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass
            $sRegex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP
            $sRegex .= "(\:[0-9]{2,5})?"; // Port
            $sRegex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path
            $sRegex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
            
            if (!preg_match("/^$sRegex$/", $sLink)) {
                echo $this->t('Places please specify RSS link');
                exit;                
            }
            $sRss = $sLink . '#' . $iNum;
            if (!$this->model->saveRss($iPlaceId, $_COOKIE['memberID'], $sRss)) {
                echo $this->t('Places Error Occured');
                exit;
            }
            $this->isAddRssAllowed (true);
            echo 1;
            exit;   
        }

        $this->_load('FormRss');
        $a = explode('#', $aPlace['pl_rss']);
        $sLink = is_array ($a) && isset($a[0]) ? $a[0] : '';
        $iNum = is_array ($a) && isset($a[1]) && (int)$a[1] > 0 ? (int)$a[1] : 4;
        $this->formrss->init ($aPlace['pl_id'], $sLink, $iNum);
        echo '<div class="bx_sys_default_padding">';
        $this->formrss->display();
        echo '</div>';
        exit;
    }

    function get_rss_feed ($iPlaceId) 
    {
        if (!$this->isViewAllowed ())
            $this->_accessDenied();

        $iPlaceId = (int)$iPlaceId;
        if (!$iPlaceId)
            $this->view->show404();

        $this->_load('HelperHtml');
        $this->_load('Model');
        $aPlace = $this->model->getPlaceById($iPlaceId);
        if (!$aPlace || !$aPlace['pl_rss'] || ($aPlace['pl_status'] != 'active' && $aPlace['pl_author_id'] != $this->iMemberId && !$this->isAdministrator()))
        {
            $this->_load('View');
            $this->view->show404();
        }

        header( 'Content-Type: text/xml' );

        if (function_exists('curl_init'))
        {
                $curl = curl_init();

                curl_setopt($curl, CURLOPT_URL, $aPlace['pl_rss']);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HEADER, false);

                $s = curl_exec($curl);

                curl_close($curl);

                if (true === $s) $s = '';
        }	
        else
        {	
            $s = file_get_contents($aPlace['pl_rss']);
        }        
        echo $s;
        exit;
    }

    function include_get_wall_post ()
    {
        $aEvent = $GLOBALS['glPlaceEventData'];

        $sTextWallObject = $this->t('Places wall object');
        $sTextAddedNew = $this->t('Places added new');

        if (!($aProfile = getProfileInfo($aEvent['owner_id'])))
            return '';

        $this->_load('HelperHtml');
        $this->_load('Model');
        if (!($aDataEntry = $this->model->getPlaceById($aEvent['object_id'])))
            return '';

        $GLOBALS['glPlaceEventVars'] = array(
            'cpt_user_name' => $aProfile['NickName'],
            'cpt_added_new' => $sTextAddedNew,
            'cpt_object' => $sTextWallObject,
            'cpt_item_url' => $this->helperhtml->href('view/' . $aDataEntry['pl_uri'], false),
            'post_id' => $aEvent['id'],
        );        
        $GLOBALS['glPlaceEventReturnData'] = array(
            'title' => $aProfile['username'] . ' ' . $sTextAddedNew . ' ' . $sTextWallObject,
            'description' => $aDataEntry['pl_desc'],
            'content' => '',
        );
    }

    function include_get_spy_post ()
    {
        $aSpyData = $GLOBALS['glPlaceSpyData'];

        $aLangKeys = array(
            'add' => '_Places spy add',
            'change' => '_Places spy change',
            'add_photo' => '_Places spy add_photo',
            'add_video' => '_Places spy add_video',
            'add_kml' => '_Places spy add_kml',
            'rate' => '_Places spy rate',
            'commentPost' => '_Places spy comment',
        );

        $aProfile = getProfileInfo($aSpyData['iSenderId']);
        $this->_load('HelperHtml');
        $this->_load('Model');
        if (!($aDataEntry = $this->model->getPlaceById($aSpyData['iObjectId'])))
            return '';
        if (empty($aLangKeys[$aSpyData['sAction']]))
            return array();

        $GLOBALS['glPlaceSpyReturnData'] = array(
            'lang_key' => $aLangKeys[$aSpyData['sAction']],
            'params' => array(
                'profile_link' => $aProfile ? getProfileLink($aSpyData['iSenderId']) : 'javascript:void(0)', 
                'profile_nick' => $aProfile ? $aProfile['NickName'] : _t('_Guest'),
                'entry_url' => $this->helperhtml->href('view/' . $aDataEntry['pl_uri'], false),
                'entry_title' => $aDataEntry['pl_name'],
            ),
            'recipient_id' => $aDataEntry['pl_author_id'],
            'spy_type' => 'content_activity',
        );
    }

    function include_display_unit ()
    {
        $this->_load('View');
        $this->_load('HelperHtml');
        $html = $this->helperhtml;
        $aPlace = &$GLOBALS['glPlaceData'];
        $this->view->display('unit', compact ('aPlace', 'html'));
    }

    function include_members_places ()
    {
        global $oProfile;
        global $profileID;

        $this->_load('View');
        $this->_load('Model');
        $this->_load('Config');
        $this->_load('HelperHtml');
        $aPlaces = array();
        $iCount = $this->model->getPlacesByUser($profileID, 0, $this->config->get ('iPerWindow'), $aPlaces);

        $GLOBALS['oSysTemplate']->addCss(BX_DOL_URL_ROOT . K_APP_PATH . 'template/css/main.css');

        $isDisplayAuthor = false;
        $sLinkAll = $this->helperhtml->href('user/'.$oProfile->_aProfile['NickName'], false);
        $sStyle = "width:280px;";
        $sClass = "bx_sys_default_padding";        
        $this->view->display('window', compact ('aPlaces', 'iCount', 'isDisplayAuthor', 'sLinkAll', 'sStyle', 'sClass'));        
    }
    
    function include_latest_places ()
    {
        $this->_include_x_places ('Latest', 'latest');
    }

    function include_best_places ()
    {
        $this->_include_x_places ('Best', 'best');
    }
        
    function include_featured_places ()
    {
        $this->_include_x_places ('Featured', 'featured');
    }

    function _include_x_places ($sFunxSuffix, $sUrlPosfix) 
    {
        global $profileID;

        $this->_load('View');
        $this->_load('Model');
        $this->_load('Config');
        $this->_load('HelperHtml');
        $aPlaces = array();
        $sFunc = 'getPlaces' . $sFunxSuffix;
        $iCount = $this->model->$sFunc(0, $this->config->get ('iPerWindow'), $aPlaces);

        $GLOBALS['oSysTemplate']->addCss(BX_DOL_URL_ROOT . K_APP_PATH . 'template/css/main.css');

        $isDisplayAuthor = true;
        $sLinkAll = $this->helperhtml->href('browse/'.$sUrlPosfix, false);
        $sStyle = "width:280px;";
        $sClass = "bx_sys_default_padding";
        $this->view->display('window', compact ('aPlaces', 'iCount', 'isDisplayAuthor', 'sLinkAll', 'sStyle', 'sClass'));
    }

    function include_map_index ()
    {
        $this->_load('HelperHtml');
        echo '<link href="http://www.google.com/uds/solutions/localsearch/gmlocalsearch.css" rel="stylesheet" type="text/css" />';
        echo '<div class="bx_sys_default_padding">' . $this->helperhtml->mapIndex() . '</div>';
    }

    function include_search_index ()
    {
        $this->_load('View');
        $this->_load('Model');

        $isDesignBox = false;
        $aCountries = $this->model->getValueIndexed('SELECT `ISO2` AS `key`, `Country` AS `value` FROM `sys_countries` ORDER BY `Country` ASC', 'key', 'value');
        $this->view->display('search', compact('aCountries', 'isDesignBox'));
    }

    function add_lang ($sLangFile, $sLangReal)
    {

        if (!$this->isAdministrator ())
            $this->_accessDenied();

        $this->_load('Installer');

        $this->installer->add_lang ($sLangFile, $sLangReal);

    }

    function upgrade ($sVerFrom = 0, $sVerTo = 0)
    {        
        if (!$this->isAdministrator ())
            $this->_accessDenied();

        $this->_load('Installer');

        $this->installer->upgrade ($sVerFrom, $sVerTo);
    }

    function install ()
    {
        if (!$this->isAdministrator ())
             $this->_accessDenied();

        $this->_load('Installer');
             
        $this->installer->install ();
    }
    
    function uninstall ()
    {
        if (!$this->isAdministrator ())
            $this->_accessDenied();

        $this->_load('Installer');

        $this->installer->uninstall ();
    }
    
    function index ()
    {        
        $this->_load('HelperHtml');
        require_once(BX_DIRECTORY_PATH_ROOT . K_APP_PATH . 'BxDolPlacesIndex.php');
        $o = new BxDolPlacesIndex($this->helperhtml, $this);
        echo $o->getCode();
    }

    function administration ()
    {   
        if (!$this->isAdministrator ())
            $this->_accessDenied();

        $this->_load('View');
        $this->_load('Model');

        $aStat['all'] = $this->model->getPlacesCount('');
        $aStat['24h'] = $this->model->getPlacesCount('1 DAY');
        $aStat['month'] = $this->model->getPlacesCount('1 MONTH');
        $aStat['pending'] = $this->model->getPlacesPendingCount();
        

        $this->_load('HelperHtml');
        $this->_load('FormSettings');
        $this->_load('Model');
        
        $form = &$this->formsettings;
        $form->init(16);

        switch ($form->check ())
        {
            case KFORM_SUBMITTED:                
                $form->save ();
                $this->_redirect ('administration/');
                break;
            case KFORM_INVALID:            
                echo 'invalid'; exit;
                $this->_load('View');
                $this->view->display('admin', compact ('form', 'aStat'));
                break;
            default:                
                $form->setValues (array_merge(array('submitted'.$form->aForm['name'] => 1)));                
                $form->init(16);
                $this->_load('View');                
                $this->view->display('admin', compact ('form', 'aStat'));
        }        
    }    

    function admin_categories_delete ($iId)
    {
        if (!$this->isEditAllowedAll())
            $this->_accessDenied();
        
        $this->_load('Model');
        echo $this->model->deleteCat($iId) ? '1' : '0';
        exit;
    }

    function admin_categories ()
    {
        if (!$this->isEditAllowedAll())
            $this->_accessDenied();
        
        $this->_load('Model');
        $this->_load('View');
        $this->_load('HelperHtml');
        $this->_load('FormCat');

        $aData = $this->model->getCats();
        $form = &$this->formcat;
        $html = &$this->helperhtml;

        switch ($form->check ())
        {
            case KFORM_SUBMITTED:
                $iId = $form->insert();
                if ($sIcon = $this->_admin_categories_save_icon($iId, 'pl_cat_pic'))
                    $this->model->updateCatIconById ($iId, $sIcon);
                $this->_redirect ('admin_categories');
                break;
            case KFORM_INVALID:
            default:                
                $iCount = count($aData);
                $sActionDelete = 'admin_categories_delete';
                $sActionEdit = 'admin_categories_edit';
                $sLocation = 'admin_categories';
                $sNameField = 'pl_cat_name';
                $sIconField = 'pl_cat_icon';
                $sIdField = 'pl_cat_id';
                $sListBoxName = $this->t('Places Categories');
                $sAddBoxName = $this->t('Places Category Add');
                $isTranslateName = true;
                $sFormHint = $this->t('Places Category Icon Info') . '<br />' . $this->t('Places Category Name Info');
                $this->view->display('admin_data', compact ('aData', 'iCount', 'form', 'html', 'sActionDelete', 'sActionEdit', 'sLocation', 'sNameField', 'sIconField', 'sIdField', 'sListBoxName', 'sAddBoxName', 'isTranslateName', 'sFormHint'));
                $this->_setPageTitle ($sAddBoxName);
        }
    }

    function admin_categories_edit ($iId)
    {
        if (!$this->isEditAllowedAll())
            $this->_accessDenied();

        $this->_load('Model');
        $this->_load('View');
        $this->_load('HelperHtml');
        $this->_load('FormCatEdit');

        $form = &$this->formcatedit;
        $aRow = $this->model->getCatById ($iId);
        if (!$aRow)
            $this->view->show404();

        $sTitle = $this->t('Places Edit General');

        switch ($form->check ())
        {
            case KFORM_SUBMITTED:
                $ret = $form->autoUpdate($iId);
                if ($sIcon = $this->_admin_categories_save_icon($iId, 'pl_cat_pic'))
                    $this->model->updateCatIconById ($iId, $sIcon);
                $this->_redirect ('admin_categories');
                break;
            case KFORM_INVALID:                                
                $this->view->display('edit', compact ('form', 'sTitle'));
                break;
            default:  
                $form->setValues (array_merge($aRow, array('submitted'.$form->aForm['name'] => 1)));
                $this->view->display('edit', compact ('form', 'sTitle'));
        }

        $this->_setPageTitle ($sTitle);
    }

    function _admin_categories_save_icon ($sImgId, $sPostName) {
        $sTmpFilename = BX_DIRECTORY_PATH_ROOT . PLACES_ICONS_PATH . 'tmp_' . rand (1, 99999);
        $sExt = moveUploadedImage ($_FILES, $sPostName, $sTmpFilename, '', false);
        if ($sExt == IMAGE_ERROR_WRONG_TYPE && !$sExt) {
            return false;
        }

        $sRealFilename = BX_DIRECTORY_PATH_ROOT . PLACES_ICONS_PATH . $sImgId . $sExt;
        $aSize = getimagesize($sTmpFilename . $sExt);
        if ($aSize[0] <= 24 && $aSize[1] <= 24) {
            @unlink ($sRealFilename);
            if (!rename ($sTmpFilename . $sExt, $sRealFilename)) {
                @unlink ($sTmpFilename . $sExt);
                return false;
            }
        } else {        
            $ret = imageResize ($sTmpFilename . $sExt, $sRealFilename, 24, 24, false);
            if (IMAGE_ERROR_SUCCESS != $ret) {
                @unlink ($sTmpFilename . $sExt);
                return false;
            }
            @unlink ($sTmpFilename . $sExt);
        }
        return $sImgId . $sExt;
    }

    function settings ($iCat = 0)
    {   
        $a = array (
            2 => array (
                'title' => 'Places View Place Map Settings',
                'func' => 'mapView',
                'args' => array ('pl_id' => 0, 'pl_map_lat' => 0.1, 'pl_lat' => 0.1, 'pl_map_lng' => 0.1, 'pl_lng' => 0.1, 'pl_zoom' => 1, 'pl_type' => '0'),
            ),
                
            4 => array (
                'title' => 'Places Home Map Settings',
                'func' => 'mapIndex',
                'args' => null,
            ),            

            8 => array (
                'title' => 'Places Edit Place Map Settings',
                'func' => 'mapEdit',
                'args' => null,
            ),                        
        );
        
        if (!$this->isAdministrator ())
            $this->_accessDenied();

        $aDesc = $a[$iCat];
            
        $this->_load('HelperHtml');
        $this->_load('FormSettings');
        $this->_load('Model');
        $form = &$this->formsettings;

        switch ($form->check ())
        {
            case KFORM_SUBMITTED:
                $form->save ();
                $this->_redirect ('settings/'.$iCat);                
                break;
            case KFORM_INVALID:            
                echo 'invalid'; exit;
                $form->init($iCat);
                $this->_load('View');
                $this->view->display('settings', compact ('form', 'aDesc'));
                break;
            default:                
                $form->setValues (array_merge(array('submitted'.$form->aForm['name'] => 1)));
                $form->init($iCat);
                $this->_load('View');                
                $this->view->display('settings', compact ('form', 'aDesc'));
        }
    }    

    function search ($iStart = 0)
    {   
        if (!$this->isBrowseAllowed ())
            $this->_accessDenied();

        $this->_load('Form');
        $this->_load('View');
        $this->_load('Model');
        $this->_load('Config');        
        $this->_load('Security');

        $sKeyword = trim($this->form->get('q'));
        $sCity = trim($this->form->get('c'));
        $sCountryCode = trim($this->form->get('r'));
        if (!$sKeyword && !$sCity && !$sCountryCode)
        {
            $isDesignBox = true;
            $aCountries = $this->model->getValueIndexed('SELECT `ISO2` AS `key`, `Country` AS `value` FROM `sys_countries` ORDER BY `Country` ASC', 'key', 'value');
            $this->view->display('search', compact('aCountries', 'isDesignBox'));
        }
        else
        {
            $iCount = $this->model->getPlacesByKeyword($this->security->passText($sKeyword), $this->security->passText($sCity), $this->security->passText($sCountryCode), $iStart, $this->config->get ('iPerPage'), $aPlaces);
            $sBrowseUrl = K_URL . 'search/';
            $sBrowseTitle = $this->t('Places Search By Keyword:') . ' ' . $sKeyword . ' ' . $sCity . ' ' . $sCountryCode;
            $aGetParams = array ('q' => $sQuery);
            $this->view->display('browse', compact ('aPlaces', 'iStart', 'iCount', 'sBrowseUrl', 'sBrowseTitle', 'aGetParams'));
            $this->_setPageTitle ($sBrowseTitle);
        }
        $this->_setPageTitle ($this->t('Places Search'));
    }    

    function category ($iCatId, $iStart = 0)
    {   
        if (!$this->isBrowseAllowed ())
            $this->_accessDenied();

        $this->_load('View');
        $this->_load('Model');
        $this->_load('Security');
        $this->_load('Form');
        $this->_load('Config');

        $iCatId = (int)$iCatId;
        $aCat = $this->model->getCatById ($iCatId);
        if (!$iCatId || !$aCat)
        {
            $this->drilldown();
            return;
        }
        else
        {
            $iCount = $this->model->getPlacesByCat($iCatId, $iStart, $this->config->get ('iPerPage'), $aPlaces);
            $sBrowseUrl = K_URL . 'category/' . $iCatId . '/';
            $sBrowseTitle = $this->t('Places By Category:') . ' ' . $this->t($aCat['pl_cat_name']);
            $aGetParams = array ();
            $this->view->display('browse', compact ('aPlaces', 'iStart', 'iCount', 'sBrowseUrl', 'sBrowseTitle', 'aGetParams'));
            $this->_setPageTitle ($sBrowseTitle);
        }        
    }    

    function country ($sCode, $iStart = 0)
    {   
        if (!$this->isBrowseAllowed ())
            $this->_accessDenied();

        $this->_load('View');
        $this->_load('Model');
        $this->_load('Security');
        $this->_load('Form');
        $this->_load('Config');
                
        if (!preg_match('/^[A-Za-z]{2}$/', $sCode))
        {
            $this->drilldown();
            return;
        }
        else
        {
            $iCount = $this->model->getPlacesByCountry($sCode, $iStart, $this->config->get ('iPerPage'), $aPlaces);
            $sBrowseUrl = K_URL . 'country/' . $sCode . '/';
            $sBrowseTitle = $this->t('Places By Country:') . ' ' . $sCode;
            $aGetParams = array ();
            $this->view->display('browse', compact ('aPlaces', 'iStart', 'iCount', 'sBrowseUrl', 'sBrowseTitle', 'aGetParams'));
            $this->_setPageTitle ($sBrowseTitle);
        }        
    }    

    function drilldown ()
    {
        if (!$this->isBrowseAllowed ())
            $this->_accessDenied();

        $this->_load('View');
        $this->_load('Model');
        $this->_load('Config');        
        $this->_load('HelperHtml');
        $html = $this->helperhtml;

        $aCountries = $this->model->getCountriesForDrillDown();
        $aCats = $this->model->getCatsForDrillDown();

        $iLevels = 5;

        $iFontMin = 10;
        $iFontMax = 30;
		$iFontDiff = $iFontMax - $iFontMin;

        $this->view->display('drilldown', compact ('aCountries', 'aCats', 'iFontMin', 'iFontMax', 'iFontDiff', 'html'));
        $this->_setPageTitle ($this->t('Places Drilldown'));
    }

    function tags ()
    {
        bx_import('BxTemplTagsModule');
        $aParam = array(
            'type' => 'places',
            'orderby' => 'popular'
        );
        $oTags = new BxTemplTagsModule($aParam, $this->t('Places All Tags'), BX_DOL_URL_ROOT . 'places/tags');
        echo $oTags->getCode();
        $this->_setPageTitle ($this->t('Places Tags'));
    }

    function browse ($sType, $iStart = 0)
    {   
        $iStart = (int)$iStart;

        if (!$this->isBrowseAllowed ())
            $this->_accessDenied();

        $this->_load('View');
        $this->_load('Model');
        $this->_load('Config');
        $aPlaces = array();
        $f = 'getPlaces';
        switch (strtolower($sType))
        {
            case 'featured': $sType = 'featured'; $f .= 'Featured'; break;
            case 'best': $sType = 'best'; $f .= 'Best'; break;
            default: $sType = 'latest'; $f .= 'Latest'; break;
        }
        $iCount = $this->model->$f((int)$iStart, $this->config->get ('iPerPage'), $aPlaces);
    
        $sBrowseUrl = K_URL . 'browse/' . $sType;
        $sBrowseTitle = $this->t('Places Browse ' . $sType);
        $aGetParams = array ();
        $this->view->display('browse', compact ('aPlaces', 'iStart', 'iCount', 'sBrowseUrl', 'sBrowseTitle', 'aGetParams'));
        $this->_setPageTitle ($sBrowseTitle);
    }    

    function browse_by_tag ($sTag, $iStart = 0)
    {   
        if (!$this->isBrowseAllowed ())
            $this->_accessDenied();

        $sTag = urldecode ($sTag);

        $this->_load('View');
        $this->_load('Model');
        $this->_load('Config');
        $this->_load('Security');
        $aPlaces = array();
        $iCount = $this->model->getPlacesByTag($this->security->passText($sTag), (int)$iStart, $this->config->get ('iPerPage'), $aPlaces);
    
        $sBrowseUrl = K_URL . 'browse_by_tag/' . $sTag;
        $sBrowseTitle = $this->t('Places Browse By Tag:') . ' ' . $sTag;
        $aGetParams = array ();
        $this->view->display('browse', compact ('aPlaces', 'iStart', 'iCount', 'sBrowseUrl', 'sBrowseTitle', 'aGetParams'));
        $this->_setPageTitle ($sBrowseTitle);
    }    

    function user ($sNickName, $iStart = 0)
    {   
        if (!$this->isBrowseAllowed ())
            $this->_accessDenied();

        $this->_load('View');
        $this->_load('Model');
        $this->_load('Config');
        $aPlaces = array();

        $iUserId = (int)$this->model->getUserIdByNickname($sNickName);
        $iCount = $this->model->getPlacesByUser($iUserId, $iStart, $this->config->get ('iPerPage'), $aPlaces, '', $this->iMemberId && $iUserId == $this->iMemberId);
        $sBrowseUrl = K_URL . 'user/' . $sNickName;
        $sBrowseTitle = $sNickName . ' ' . $this->t('Places');
        $aGetParams = array ();
        $this->view->display('browse', compact ('aPlaces', 'iStart', 'iCount', 'sBrowseUrl', 'sBrowseTitle', 'aGetParams'));
        $this->_setPageTitle ($sBrowseTitle);
    }    
    
    function pending ($iStart = 0)
    {   
        if (!$this->isAdministrator ())
            $this->_accessDenied();

        $this->_load('View');
        $this->_load('Model');
        $this->_load('Config');
        $aPlaces = array();

        $iCount = $this->model->getPlacesByStatus('approval', $iStart, $this->config->get ('iPerPage'), $aPlaces, '');
        $sBrowseUrl = K_URL . 'pending/';
        $sBrowseTitle = $this->t('Places Pending Approval');
        $aGetParams = array ();
        $this->view->display('browse', compact ('aPlaces', 'iStart', 'iCount', 'sBrowseUrl', 'sBrowseTitle', 'aGetParams'));
        $this->_setPageTitle ($sBrowseTitle);
    }    

    function view ($sPlaceUri)
    {        
        if (!$this->isViewAllowed ())
            $this->_accessDenied();

        if (!preg_match('/^[A-Za-z0-9_-]+$/', $sPlaceUri))
            $this->view->show404();

        $this->_load('HelperHtml');
        $this->_load('Model');
        $this->_load('FormAdd');
        $aPlace = $this->model->getPlaceByUri($sPlaceUri);
        if (!$aPlace || ($aPlace['pl_status'] != 'active' && $aPlace['pl_author_id'] != $this->iMemberId && !$this->isAdministrator()))
        {
            $this->_load('View');
            $this->view->show404();
        }
        
        $aPlacePhotos = $this->model->getPlacePhotos($aPlace['pl_id']);
        $aPlaceVideos = $this->model->getPlaceVideos($aPlace['pl_id']);

        require_once(BX_DIRECTORY_PATH_ROOT . K_APP_PATH . 'BxDolPlacesView.php');
        $o = new BxDolPlacesView($aPlace, $aPlacePhotos, $aPlaceVideos, $this->helperhtml, $this->formadd->aForm, $this);
        if ($aPlace['pl_status'] != 'active')
            echo '<div class="k_pending_approval">' . $this->t('Places Pending Approval Msg') . '</div>';
        echo $o->getCode();
        $this->_setPageTitle ($aPlace['pl_name']);
    }        

    function videos ($iPlaceId)
    {             
        if (!$this->isEditAllowedOwn() && !$this->isEditAllowedAll())
            $this->_accessDenied();
        
        $this->_load('Model');
        $this->_load('View');
        $this->_load('Config');
        $this->_load('HelperHtml');
        $this->_load('FormVideo');
        $this->_load('FormVideoUpload');

        $aPlace = $this->model->getPlaceById($iPlaceId);
        if (!$aPlace || ($aPlace['pl_author_id'] != $_COOKIE['memberID'] && !$this->isEditAllowedAll()))
            $this->view->show404();

        $form = &$this->formvideo;
        $form_upload = &$this->formvideoupload;

        switch ($form_upload->check ())
        {
            case KFORM_SUBMITTED:

                if ($iVideoId = $form_upload->insertUploadedVideo('pl_video', $iPlaceId, $aPlace['pl_author_id']))
                {
                if (!$this->config->get ('isAutoApproval')) {
                    $this->model->deactivatePlaceById($iPlaceId);
                    $this->_onPlaceDeactivate($iPlaceId);
                }
                $this->_onPlaceVideoAdd($iPlaceId, $iVideoId);
                }

                $this->_redirect ('videos/' . $iPlaceId);
                break;

            case KFORM_INVALID:
            default:                    
        }

        switch ($form->check ())
        {
            case KFORM_SUBMITTED:

                if ($iVideoId = $form->insertEmbedVideo($iPlaceId)) 
                {
                if (!$this->config->get ('isAutoApproval')) {                    
                    $this->model->deactivatePlaceById($iPlaceId);                    
                    $this->_onPlaceDeactivate($iPlaceId);
                }
                $this->_onPlaceVideoAdd($iPlaceId, $iVideoId);
                }

                $this->_redirect ('videos/' . $iPlaceId);
                break;

            case KFORM_INVALID:
            default:
                $aPlaceVideos = $this->model->getPlaceVideos($iPlaceId);
                $iCount = count($aPlacePhotos);
                $this->view->display('videos', compact ('aPlace', 'aPlaceVideos', 'iCount', 'form', 'form_upload'));
        }
    }        

    function video_delete ($iId)
    {
        if (!$this->isEditAllowedOwn() && !$this->isEditAllowedAll())
            $this->_accessDenied();
        
        $this->_load('Model');
        echo $this->model->deleteVideo($iId, $_COOKIE['memberID'], $this->isEditAllowedAll()) ? '1' : '0';
        exit;
    }

    function kml ($iPlaceId)
    {             
        if (!$this->isEditAllowedOwn() && !$this->isEditAllowedAll())
            $this->_accessDenied();
        
        $this->_load('Model');
        $this->_load('View');
        $this->_load('Config');
        $this->_load('HelperHtml');
        $this->_load('FormKml');

        $aPlace = $this->model->getPlaceById($iPlaceId);
        if (!$aPlace || ($aPlace['pl_author_id'] != $_COOKIE['memberID'] && !$this->isEditAllowedAll()))
            $this->view->show404();

        $form = &$this->formkml;

        $iCheckResult = $form->check ();
        $sTmpFile = BX_DIRECTORY_PATH_ROOT . PLACES_KML_PATH . $_COOKIE['memberID'] . '.tmp';
        $sFileExt = '';
        if (KFORM_SUBMITTED == $iCheckResult) 
        {
            $a = pathinfo($_FILES['pl_kml_file']['name']);
            if ($a['extension'] == 'kml' || $a['extension'] == 'kmz') 
            {
                if (move_uploaded_file($_FILES['pl_kml_file']['tmp_name'], $sTmpFile)) 
                {
                    $sFileExt = '.' . $a['extension'];
                }
                else
                {
                    $iCheckResult = KFORM_INVALID;
                    $form->addError($this->t('Places file upload error'));
                }
            } 
            else 
            {
                $iCheckResult = KFORM_INVALID;
                $form->addError($this->t('Places please upload kml or kmz file only'));
            }
        }

        switch ($iCheckResult)
        {
            case KFORM_SUBMITTED:

                $form->set ('pl_kml_file_ext', $sFileExt);
                $form->set ('pl_id', $iPlaceId);
                $form->set ('pl_kml_added', time());

                if ($iKmlId = $form->insert('places_kml_files')) 
                {
                    rename ($sTmpFile, BX_DIRECTORY_PATH_ROOT . PLACES_KML_PATH . $iKmlId . $sFileExt);
                    if (!$this->config->get ('isAutoApproval')) {                    
                        $this->model->deactivatePlaceById($iPlaceId);                    
                        $this->_onPlaceDeactivate($iPlaceId);
                    }
                    $this->_onPlaceKmlAdd($iPlaceId, $iKmlId);
                } else {
                    @unlink ($sTmpFile);
                }

                $this->_redirect ('kml/' . $iPlaceId);
                break;

            case KFORM_INVALID:
            default:
                $aPlaceKmlFiles = $this->model->getPlaceKmlFiles($iPlaceId);
                $iCount = count($aPlaceKmlFiles);
                $this->view->display('kml', compact ('aPlace', 'aPlaceKmlFiles', 'iCount', 'form'));
        }
    }        

    function kml_delete ($iId)
    {
        if (!$this->isEditAllowedOwn() && !$this->isEditAllowedAll())
            $this->_accessDenied();
        
        $this->_load('Model');
        echo $this->model->deleteKml($iId, $_COOKIE['memberID'], $this->isEditAllowedAll()) ? '1' : '0';
        exit;
    }

    function photos ($iPlaceId)
    {             
        if (!$this->isEditAllowedOwn() && !$this->isEditAllowedAll())
            $this->_accessDenied();
        
        $this->_load('Model');
        $aPlace = $this->model->getPlaceById($iPlaceId);
        if (!$aPlace || ($aPlace['pl_author_id'] != $_COOKIE['memberID'] && !$this->isEditAllowedAll()))
        {
            $this->_load('View');
            $this->view->show404();
        }
        $this->_load('View');
        $this->_load('HelperHtml');
        $this->_load('FormImage');
        $this->_load('Config');
        $form = &$this->formimage;

        switch ($form->check ())
        {
            case KFORM_SUBMITTED:

                if ($iImgId = $form->insertResizedImage('pl_image', 'places_photos', $iPlaceId, $this->aSizes, BX_DIRECTORY_PATH_ROOT . PLACES_IMAGES_PATH))
                {
                if (!$this->config->get ('isAutoApproval')) {                    
                    $this->model->deactivatePlaceById($iPlaceId);                    
                    $this->_onPlaceDeactivate($iPlaceId);
                }
                $this->_onPlacePhotoAdd($iPlaceId, $iImgId);
                }
                $this->_redirect ('photos/' . $iPlaceId);
                break;

            case KFORM_INVALID:
            default:
                $aPlacePhotos = $this->model->getPlacePhotos($iPlaceId);
                $iCount = count($aPlacePhotos);

                $this->view->display('photos', compact ('aPlace', 'aPlacePhotos', 'iCount', 'form'));
        }
    }        

    function draw_load ($iPlaceId) 
    {
        require_once( BX_DIRECTORY_PATH_PLUGINS . 'Services_JSON.php' );
        $oParser = new Services_JSON();
        $this->_load('Model');
        $s = $this->model->loadPlaceDrawings($iPlaceId);
        if ($s)
            echo $oParser->encode(unserialize($s));
        exit;
    }

    function draw_save ($iPlaceId) 
    {
        if (isset($_REQUEST['figures'])) {
            $this->_load('Model');
            $aPlace = $this->model->getPlaceById($iPlaceId);
            if (!$aPlace || ($aPlace['pl_author_id'] != $_COOKIE['memberID'] && !$this->isEditAllowedAll()))
            {
                $this->_load('View');
                $this->view->show404();
            }            
            echo $this->model->savePlaceDrawings($iPlaceId, serialize($_REQUEST['figures']));
            echo 'saved ok';
        }
        exit;
    }

    function draw ($iPlaceId)
    {             
        if (!$this->isEditAllowedOwn() && !$this->isEditAllowedAll())
            $this->_accessDenied();
        
        $this->_load('Model');
        $aPlace = $this->model->getPlaceById($iPlaceId);
        if (!$aPlace || ($aPlace['pl_author_id'] != $_COOKIE['memberID'] && !$this->isEditAllowedAll()))
        {
            $this->_load('View');
            $this->view->show404();
        }

        $this->_load('HelperHtml');
        $this->_load('View');
        $sMap = $this->helperhtml->mapDraw($aPlace);
        $this->view->display('draw', compact ('aPlace', 'sMap'));
    }    
    function photo_make_primary ($iPhotoId)
    {
        if (!$this->isEditAllowedOwn() && !$this->isEditAllowedAll())
            $this->_accessDenied();
        
        $this->_load('Model');
        echo $this->model->makePrimary($iPhotoId, $_COOKIE['memberID']) ? '1' : '0';
        exit;
    }
    
    function photo_delete ($iPhotoId)
    {
        if (!$this->isEditAllowedOwn() && !$this->isEditAllowedAll())
            $this->_accessDenied();
        
        $this->_load('Model');
        echo $this->model->deletePhoto($iPhotoId, $_COOKIE['memberID'], $this->isEditAllowedAll()) ? '1' : '0';
        exit;
    }

    function activate ($iPlaceId)
    {
        if (!$this->isAdministrator())
            $this->_accessDenied();

        $this->_load('Model');
        $this->_load('Security');
        $this->_load('View');            

        $aPlace = $this->model->getPlaceById($iPlaceId);
        if (!$aPlace)
            $this->view->show404();

        $ret = $this->model->activatePlaceById ($iPlaceId);
        if ($ret)             
            $this->_onPlaceActivate($iPlaceId);
        $this->_redirect ('view/' . $aPlace['pl_uri']);
    }

    function mark_as_featured ($isMarkAsFeatured, $iPlaceId) 
    {
        if (!$this->isAdministrator())
            $this->_accessDenied();

        $this->_load('Model');
        $this->_load('Security');
        $this->_load('View');            

        $isMarkAsFeatured = $isMarkAsFeatured ? 1 : 0;
        $aPlace = $this->model->getPlaceById((int)$iPlaceId);
        if (!$aPlace)
            $this->view->show404();

        $ret = $this->model->markAsFeaturedPlaceById ($iPlaceId, $isMarkAsFeatured);
        if ($ret)             
            $this->_onPlaceMarkAsFeatured($iPlaceId, $isMarkAsFeatured);
        $this->_redirect ('view/' . $aPlace['pl_uri']);        
    }

    function delete ($iPlaceId)
    {
        if (!$this->isEditAllowedOwn() && !$this->isEditAllowedAll())
            $this->_accessDenied();

        $this->_load('Model');
        $this->_load('Security');
        $this->_load('View');            

        $aPlace = $this->model->getPlaceById($iPlaceId);
        if (!$aPlace || ($aPlace['pl_author_id'] != $_COOKIE['memberID'] && !$this->isEditAllowedAll()))
            $this->view->show404();

        $ret = $this->model->deletePlaceById ($iPlaceId);
        if ($ret)             
        {
            $this->_onPlaceDelete($iPlaceId);
        }
        $this->view->display('delete', compact ('ret'));
    }

    function edit ($iPlaceId)
    {
        if (!$this->isEditAllowedOwn() && !$this->isEditAllowedAll())
            $this->_accessDenied();

        $this->_load('FormEdit');
        $this->_load('Model');
        $this->_load('Security');
        $this->_load('View');
        $this->_load('Config');
        $form = &$this->formedit;
        $aPlace = $this->model->getPlaceById($iPlaceId);
        if (!$aPlace || ($aPlace['pl_author_id'] != $_COOKIE['memberID'] && !$this->isEditAllowedAll()))
            $this->view->show404();

        $sTitle = $this->t('Places Edit Place');

        switch ($form->check ())
        {
            case KFORM_SUBMITTED:
                $iAuthorId = $this->model->getUserIdByNickname ($form->get('pl_author'));
                if ($iAuthorId)
                    $form->set('pl_author_id', $iAuthorId);
                $form->set ('pl_status', $this->config->get ('isAutoApproval') ? 'active' : 'approval');
                $ret = $form->update('places_places', " AND `pl_id` = '$iPlaceId' ");                
                $this->_onPlaceUpdate($iPlaceId);
                if ($this->config->get ('isAutoApproval'))
                    $this->_onPlaceActivate($iPlaceId);
                else
                    $this->_onPlaceDeactivate($iPlaceId);
                $this->_redirect ('view/' . $aPlace['pl_uri']);
                break;
            case KFORM_INVALID:                                
                $this->view->display('edit', compact ('form', 'sTitle')); 
                break;
            default:  
                $form->aForm['fields']['pl_location']['place'] = $aPlace;
                $form->setValues (array_merge($aPlace, array('submitted'.$form->aForm['name'] => 1)));
                if ($GLOBALS['logged']['admin'])
                    $form->set ('pl_author', $this->model->getUserNicknameById ($aPlace['pl_author_id']));
                $form->set ('gmk_lat', $aPlace['pl_map_lat']);
                $form->set ('gmk_lng', $aPlace['pl_map_lng']);
                $form->set ('gmk_zoom', $aPlace['pl_map_zoom']);
                $form->set ('gmk_type', $aPlace['pl_map_type']);
                $form->set ('pl_tags', $aPlace['pl_tags']);
                $this->view->display('edit', compact ('form', 'sTitle'));
        }

        $this->_setPageTitle ($sTitle);
    }

    function add ()
    {
        if (!$this->isAddAllowed()) 
        {   
            $this->_accessDenied();
        }

        $this->_load('Form');
        $this->_load('Model');
        $this->_load('Security');
        $this->_load('FormAdd');
        $this->_load('Config');
        $form = &$this->formadd;

        switch ($form->check ())
        {
            case KFORM_SUBMITTED:                
                $form->set ('pl_author_id', $_COOKIE['memberID']);
                $form->set ('pl_created', date('Y-m-d H:i:s'));
                $form->set ('pl_status', $this->config->get ('isAutoApproval') ? 'active' : 'approval');
                $sUri = uriGenerate($form->get('pl_name'), 'places_places', 'pl_uri');
                $form->set ('pl_uri', $sUri);
                $sId = $form->insert('places_places');
                if (!$sId)
                {
                    echo 'handle error here';
                    exit;
                }
                $form->insertResizedImage('pl_image', 'places_photos', $sId, $this->aSizes, BX_DIRECTORY_PATH_ROOT . PLACES_IMAGES_PATH);
                $this->_onPlaceAdd($sId, $sUri);
                if ($this->config->get ('isAutoApproval'))
                    $this->_onPlaceActivate($sId);
                $this->_redirect ('view/' . $sUri);
                break;
            case KFORM_INVALID:
            default:
                $this->_load('View');
                $this->view->display('add', compact ('form'));
        }
    }    

    function image ($sUrl, $iImgId)
    {   
        if (!$this->isViewAllowed ())
            $this->_accessDenied();

        $iImgId = (int)$iImgId;
        switch ($sUrl)
        {
            case 'thumb':
                header('Content-type: image/jpeg');
                readfile(BX_DIRECTORY_PATH_ROOT . K_APP_PATH . 'photos/' . $iImgId . '.jpg');
                exit;
            case 'big':
                header('Content-type: image/jpeg');
                readfile(BX_DIRECTORY_PATH_ROOT . K_APP_PATH . 'photos/big/' . $iImgId . '.jpg');
                exit;
            case 'real':
                header('Content-type: image/jpeg');
                readfile(BX_DIRECTORY_PATH_ROOT . K_APP_PATH . 'photos/real/' . $iImgId . '.jpg');
                exit;                
        }
        $this->_load('View');
        $this->view->show404();
    }    

    function video ($sUrl, $iId)
    {   
        if (!$this->isViewAllowed ())
            $this->_accessDenied();

        $iId = (int)$iId;
        switch ($sUrl)
        {
            case 'thumb':
                header('Content-type: image/jpeg');
                readfile(BX_DIRECTORY_PATH_ROOT . K_APP_PATH . 'videos/t' . $iId . '.jpg');
                exit;
            case 'preview':
                header('Content-type: image/jpeg');
                readfile(BX_DIRECTORY_PATH_ROOT . K_APP_PATH . 'videos/' . $iId . '.jpg');
                exit;
            case 'file':
                header('Content-type: video/x-flv');
                readfile(BX_DIRECTORY_PATH_ROOT . K_APP_PATH . 'videos/' . $iId . '.flv');
                exit;                
        }
        $this->_load('View');
        $this->view->show404();
    }    

    function gmk_actions ()
    {
        switch ($_GET['action'])
        {
            case 'get_places':
                $o = new GMKPlaces();
                echo $o->getLocations ((float)$_GET['minLat'], (float)$_GET['maxLat'], (float)$_GET['minLng'], (float)$_GET['maxLng'], $_GET['type']);
                exit;
            case 'save_location':
                $o = new GMKPlaces();
                echo $o->saveLocation ((int)$_GET['id'], (float)$_GET['lat'], (float)$_GET['lng'], (float)$_GET['zoom'], (int)$_GET['type']);
                break;
        }
        exit;
    }

    function isAdministrator()
    {
        if ($GLOBALS['logged']['admin'])
            return true;
        if (!$GLOBALS['logged']['member']) 
            return false;
        if ('on' == getParam('free_mode'))
            return false;
        $this->_defineMemActions ();
		$aCheck = checkAction($_COOKIE['memberID'], K_PLACES_ADMINISTRATION, $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }
    
    function isAddAllowed()
    {
        if ($GLOBALS['logged']['admin']) 
            return true;
        if (!$GLOBALS['logged']['member']) 
            return false;
        $a = getProfileInfo($_COOKIE['memberID']);
        if ('Active' != $a['Status'])
            return false;
        $this->_defineMemActions ();
		$aCheck = checkAction($_COOKIE['memberID'], K_PLACES_ADD, $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }
    
    function isBrowseAllowed ()
    {
        if ($GLOBALS['logged']['admin'])
            return true;        
        $this->_defineMemActions ();
		$aCheck = checkAction($_COOKIE['memberID'], K_PLACES_BROWSE, $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }
    
    function isViewAllowed ()
    {
        if ($GLOBALS['logged']['admin'])
            return true;        
        $this->_defineMemActions ();
		$aCheck = checkAction($_COOKIE['memberID'], K_PLACES_VIEW, $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }

    function isAddRssAllowed ($isPerformAction = false)
    {
        if ($GLOBALS['logged']['admin'])
            return true;                
        if (!$GLOBALS['logged']['member']) 
            return false;
        $this->_defineMemActions ();
        $aCheck = checkAction($_COOKIE['memberID'], K_PLACES_ADD_RSS, $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }

    function isEditAllowedOwn ()
    {
        if ($GLOBALS['logged']['admin'])
            return true;
        if (!$GLOBALS['logged']['member']) 
            return false;
        $a = getProfileInfo($_COOKIE['memberID']);
        return 'Active' == $a['Status'] ? true : false;
    }    

    function isEditAllowedAll ()
    {
        if ($GLOBALS['logged']['admin'])
            return true;        
        if (!$GLOBALS['logged']['member']) 
            return false;
        if ('on' == getParam('free_mode'))
            return false;
        $this->_defineMemActions ();
		$aCheck = checkAction($_COOKIE['memberID'], K_PLACES_ADMINISTRATION, $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;        
    }   

    function _browseLatest ()
    {
        $this->_browseX ('Latest');
    }   

    function _browseBest ()
    {
        $this->_browseX ('Best');        
    }   
        
    function _browseFeatured ()
    {
        $this->_browseX ('Featured');
    }   

    function _browseX ($sFuncSuffix)
    {
        $this->_load('View');
        $this->_load('Model');
        $this->_load('Config');
                
        $aPlaces = array();
        $sFunc = 'getPlaces' . $sFuncSuffix;
        $iCount = $this->model->$sFunc(0, $this->config->get ('iPerWindow'), $aPlaces);
        
        $this->view->display('window', compact ('aPlaces', 'iStart', 'iCount'));
    }   
    
    function _memberAccessOnly()
    {
        if (!$GLOBALS['logged']['member'])
            $this->_accessDenied();
    }    

    function _accessDenied()
    {
        $this->_load('View');
        $this->view->showAccessDenied();
    }        

    function _onPlaceDelete ($iPlaceId)
    {
        $aPlace = $this->model->getPlaceById($iPlaceId);
        if (!$aPlace)
            return;     

        $iEntryId = (int)$iPlaceId;
        bx_import('BxDolTags');
        $o = new BxDolTags ();
        $o->reparseObjTags('places', $iEntryId);

		$oAlert = new BxDolAlerts('places', 'delete', $iPlaceId, $this->iMemberId);
		$oAlert->alert();        
    }

    function _onPlaceVideoAdd ($iPlaceId, $iVideoId)
    {
		$oAlert = new BxDolAlerts('places', 'add_video', $iPlaceId, $this->iMemberId, array ('video_id' => $iVideoId));
		$oAlert->alert();
    }

    function _onPlaceKmlAdd($iPlaceId, $iKmlId)
    {
		$oAlert = new BxDolAlerts('places', 'add_kml', $iPlaceId, $this->iMemberId, array ('kml_id' => $iKmlId));
		$oAlert->alert();
    }

    function _onPlacePhotoAdd ($iPlaceId, $iImageId)
    {
		$oAlert = new BxDolAlerts('places', 'add_photo', $iPlaceId, $this->iMemberId, array ('img_id' => $iImageId));
		$oAlert->alert();
    }

    function _onPlaceMarkAsFeatured ($iPlaceId, $isMarkedAsFeatured)
    {
		$oAlert = new BxDolAlerts('places', 'mark_as_featured', $iPlaceId, $this->iMemberId, array ('featured' => $isMarkedAsFeatured));
		$oAlert->alert();
    }

    function _onPlaceUpdate ($iPlaceId)
    {
		$oAlert = new BxDolAlerts('places', 'change', $iPlaceId, $this->iMemberId);
		$oAlert->alert();
    }

    function _onPlaceDeactivate ($iPlaceId)
    {
        $aPlace = $this->model->getPlaceById($iPlaceId);
        if (!$aPlace)
            return;       

        $iEntryId = (int)$iPlaceId;
        bx_import('BxDolTags');
        $o = new BxDolTags ();
        $o->reparseObjTags('places', $iEntryId);
    }

    function _onPlaceActivate ($iPlaceId)
    {
        $aPlace = $this->model->getPlaceById($iPlaceId);
        if (!$aPlace)
            return;        

        $iEntryId = (int)$iPlaceId;
        bx_import('BxDolTags');
        $o = new BxDolTags ();
        $o->reparseObjTags('places', $iEntryId);
    }

    function _onPlaceAdd ($iPlaceId, $sUri)
    {
        $this->_load('Config');

        // send letter to admin
        if ($this->config->get ('isNotifyAdmin'))
        {
            $this->_load('HelperHtml');
            global $site;
            $sSubj = $this->t('Places Mail New Place Subj');
            $sBody = $this->t('Places Mail New Place Body');
            $sBody = str_replace ('{link}', $this->helperhtml->href('view/' . $sUri, false), $sBody);
            sendMail ($site['email_notify'], $sSubj, $sBody);
        }    

		$oAlert = new BxDolAlerts('places', 'add', $iPlaceId, $this->iMemberId);
		$oAlert->alert();
    }

    function _defineMemActions () {
        parent::_defineActions (array('places view', 'places browse', 'places add', 'places administration', 'places add rss'));
    }   
}






?>
