<?php

/**
* Copyright (c) 2012-2013 Andreas Pachler - http://www.paan-systems.com
* This is a commercial product made by Andreas Pachler and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from Andreas Pachler.
* This notice may not be removed from the source code.
*/

bx_import('BxDolEditor');

/**
 * TinyMCE editor representation.
 * @see BxDolEditor
 */
class BxBaseEditorTinyMCE4 extends BxDolEditor
{
    /**
     * Common initialization params
     */
    protected static $CONF_COMMON = "
                    jQuery('{bx_var_selector}').tinymce({
                        {bx_var_custom_init}
                        selector: '{bx_var_selector}',
                        script_url: '{bx_var_plugins_path}tiny_mce4/tiny_mce_gzip.php',
                        document_base_url: '{bx_url_root}',
                        remove_script_host: false,
                        relative_urls: false,
                        skin: '{bx_var_skin}',
                        language: '{bx_var_lang}',
                        content_css: '{bx_var_css_path}',
                        entity_encoding: 'raw',
                    });
    ";

    /**
     * Standard view initialization params
     */
    protected static $WIDTH_STANDARD = '630px';
    protected static $CONF_STANDARD = "
                        width: '100%',
                        height: '270',
                        theme: 'modern',
                        plugins: [
                            'advlist autolink link image lists charmap preview hr anchor spellchecker',
                            'wordcount visualblocks visualchars code fullscreen media nonbreaking',
                            'save contextmenu directionality emoticons template paste textcolor'
                        ],
                        toolbar: 'undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | preview media fullpage | forecolor backcolor emoticons | styleselect formatselect fontselect fontsizeselect',
    ";

    /**
     * Minimal view initialization params
     */
    protected static $WIDTH_MINI = '340px';
    protected static $CONF_MINI = "
                        width: '100%',
                        height: '150',
                        theme: 'modern',
                        menubar : false,
                        plugins: [
                            'autolink lists',
                            'fullscreen',
                            'paste'
                        ],
                        toolbar: 'bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist',
    ";

    /**
     * Full view initialization params
     */
    protected static $WIDTH_FULL = '650px';
    protected static $CONF_FULL = "
                        width: '100%',
                        height: '320',
                        theme: 'modern',
                        plugins: [
                            'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
                            'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
                            'save table contextmenu directionality emoticons template paste textcolor',
                            'image moxiemanager'
                        ],
                        toolbar: 'image insertimage | insertfile insertfile undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons  | styleselect formatselect fontselect fontsizeselect',
    ";

    /**
     * Available editor languages
     */
    protected static $CONF_LANGS = array('ar' => 1, 'be' => 1, 'bg' => 1, 'ca' => 1, 'cn' => 1, 'cs' => 1, 'cy' => 1, 'da' => 1, 'de' => 1, 'en' => 1, 'es' => 1, 'et' => 1, 'fa' => 1, 'fi' => 1, 'fr' => 1, 'gl' => 1, 'he' => 1, 'hu' => 1, 'it' => 1, 'ja' => 1, 'km' => 1, 'ko' => 1, 'lt' => 1, 'lv' => 1, 'mk' => 1, 'ml' => 1, 'nb' => 1, 'nl' => 1, 'no' => 1, 'pl' => 1, 'pt' => 1, 'ro' => 1, 'ru' => 1, 'sk' => 1, 'sl' => 1, 'sq' => 1, 'sv' => 1, 'tr' => 1, 'uk' => 1, 'zh' => 1);

    protected $_oTemplate;
    protected $_bJsCssAdded = false;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = $GLOBALS['oSysTemplate'];

        $this->_aObject['skin'] = 'lightgray';
    }

    /**
     * Get minimal width which is neede for editor for the provided view mode
     */
    public function getWidth ($iViewMode)
    {
        switch ($iViewMode) {
            case BX_EDITOR_MINI:
                return self::$WIDTH_MINI;
            case BX_EDITOR_FULL:
                return self::$WIDTH_FULL;
            break;
            case BX_EDITOR_STANDARD:
            default:
                return self::$WIDTH_STANDARD;
        }
    }

    /**
     * Attach editor to HTML element, in most cases - textarea.
     * @param $sSelector - jQuery selector to attach editor to.
     * @param $iViewMode - editor view mode: BX_EDITOR_STANDARD, BX_EDITOR_MINI, BX_EDITOR_FULL
     * @param $bDynamicMode - is AJAX mode or not, the HTML with editor area is loaded dynamically.
     */
    public function attachEditor ($sSelector, $iViewMode = BX_EDITOR_STANDARD, $bDynamicMode = false)
    {
        // always use dynamic mode to prevent problems with JS cache!!
        $bDynamicMode = true;
    
        // set visual mode
        switch ($iViewMode) {
            case BX_EDITOR_MINI:
                 $sToolsItems = self::$CONF_MINI;
                break;
            case BX_EDITOR_FULL:
                $sToolsItems = self::$CONF_FULL;
            break;
            case BX_EDITOR_STANDARD:
            default:
                 $sToolsItems = self::$CONF_STANDARD;
        }

        // detect language
        $sLang = (isset(self::$CONF_LANGS[$GLOBALS['sCurrentLanguage']]) ? $GLOBALS['sCurrentLanguage'] : 'en');

        // initialize editor
        $sInitEditor = $this->_replaceMarkers(self::$CONF_COMMON, array(
            'bx_var_custom_init' => $sToolsItems,
            'bx_var_plugins_path' => bx_js_string(BX_DOL_URL_PLUGINS, BX_ESCAPE_STR_APOS),
            'bx_var_css_path' => bx_js_string($this->_oTemplate->getCssUrl('editor.css'), BX_ESCAPE_STR_APOS),
            'bx_var_skin' => bx_js_string($this->_aObject['skin'], BX_ESCAPE_STR_APOS),
            'bx_var_lang' => 'en', //bx_js_string($sLang, BX_ESCAPE_STR_APOS),
            'bx_var_selector' => bx_js_string($sSelector, BX_ESCAPE_STR_APOS),
            'bx_url_root' => bx_js_string(BX_DOL_URL_ROOT, BX_ESCAPE_STR_APOS),
        ));

        if ($bDynamicMode) {

            $sScript = "
            <script>
                if ('undefined' == typeof(jQuery(document).tinymce)) {
                    $.getScript('" . bx_js_string(BX_DOL_URL_PLUGINS . 'tiny_mce4/jquery.tinymce.min.js', BX_ESCAPE_STR_APOS) . "', function(data, textStatus, jqxhr) {
                        $sInitEditor
                    });
                } else {
                    $sInitEditor
                }
            </script>";

        } else {

            $sScript = "
            <script>
                $(document).ready(function () {
                    $sInitEditor
                });
            </script>";

        }

        return $this->_addJsCss($bDynamicMode) . $sScript;
    }

    /**
     * Add css/js files which are needed for editor display and functionality.
     */
    protected function _addJsCss($bDynamicMode = false, $sInitEditor = '')
    {
        if ($bDynamicMode)
            return '';
        if ($this->_bJsCssAdded)
            return '';

        $aJs = array(BX_DOL_URL_PLUGINS . 'tiny_mce4/tinymce.min.js', BX_DOL_URL_PLUGINS . 'tiny_mce4/jquery.tinymce.min.js');

        $this->_oTemplate->addJs($aJs);

        if (isset($GLOBALS['oAdmTemplate']))
            $GLOBALS['oAdmTemplate']->addJs($aJs);

        $this->_bJsCssAdded = true;
        return '';
    }

}
