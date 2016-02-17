<?php

require_once(BX_DIRECTORY_PATH_ROOT . K_SYS_PATH . 'KBase.php');

class KHelperHtml extends KBase
{    
    function KHelperHtml ()
    {     
        parent::KBase();   
    }

    function url ($sName, $sUrl, $bTranslate = 1, $aAttr = array(), $bEcho = true, $sIconFile = '')
    {
        $sAttr = $this->_getAttributes($aAttr);
        $sName = $bTranslate ? $this->t($sName) : $sName;                

        if ($sIconFile)
            $sIcon = '<img class="k_icon" src="' . $GLOBALS['site']['url'] . K_APP_PATH . 'template/img/icons/' . $sIconFile . '.png" />';

        $s = $sIcon . '<a title="' . $sName . '" href="' . $this->_href($sUrl) .'" ' . $sAttr . '>' . $sName . '</a>';
        if ($bEcho)
            echo $s;
        else
            return $s;
    }

    function href ($sUrl, $bEcho = true)
    {
        if ($bEcho)
        echo $this->_href($sUrl);
        else
            return $this->_href($sUrl);
    }
    
    function _href ($sUrl)
    {
        $this->_load('Config', 'HelperHtml');
        
        if (0 != strncasecmp ($sUrl, 'http:', 5) && 0 != strncasecmp ($sUrl, 'https:', 6) && 0 != strncasecmp ($sUrl, 'javascript:', 11) && 0 != strncasecmp ($sUrl, 'mailto:', 7))
        {
            $sBaseUrl = $GLOBALS['site']['url'] . $this->config->get ('sBaseUri');
            if ($this->config->get ('iRewriteEngine'))
                $sUrl = $sBaseUrl . '/' . $sUrl;
            else
                $sUrl = $sBaseUrl . '.php/' . $sUrl;
        }
        return  $sUrl;
    }

    function tableBegin ($a)
    {
        $sAttr = $this->_getAttributes($a);
        echo "<table $sAttr>";
    }

    function tableEnd ()
    {
        echo '</table>';
    }

    // form fields
    
    function formBegin ($sName = '', $sAction = '', $sTarget = '', $a, $sMethod = 'post')
    {
        $sAttr = $this->_getAttributes($a);
        if ($sName) $sName = " name=\"$sName\" ";
        if ($sAction) $sAction = " action=\"$sAction\" ";
        if ($sMethod) $sMethod = " method=\"$sMethod\" ";
        if ($sTarget) $sTarget = " target=\"$sTarget\" ";
        echo "<form $sName $sAction $sMethod $sTarget $sAttr>";
    }

    function formEnd ()
    {
        echo "</form>";
    }

    function inputHidden (&$oForm, $sName, &$a)
    {
        $sAttr = $this->_getAttributes($a);
        $sVal = $this->_getValue($a);

        echo "<input type=\"hidden\" name=\"$sName\" value=\"$sVal\" $sAttr />";
    }

    function inputText (&$oForm, $sName, &$a)
    {
        $sAttr = $this->_getAttributes($a);
        $sVal = $this->_getValue($a);

        $this->_getFormRow ($a['label'], "<input type=\"text\" name=\"$sName\" value=\"$sVal\" $sAttr />");
    }

    function inputCheckbox (&$oForm, $sName, &$a)
    {
        $sAttr = $this->_getAttributes($a);
        $sVal = $this->_getValue($a);

        if ($sVal)
            $sAttr .= ' checked="checked" ';
        
        $this->_getFormRow ($a['label'], "<input type=\"checkbox\" name=\"$sName\" $sAttr />");
    }

    function inputTextarea (&$oForm, $sName, &$a)
    {
        $sAttr = $this->_getAttributes($a);
        $sVal = $this->_getValue($a);
        
        $this->_getFormRow ($a['label'], "<textarea name=\"$sName\" $sAttr>$sVal</textarea>");
    }

    function inputTinymce (&$oForm, $sName, &$a)
    {
        $sAttr = $this->_getAttributes($a);
        $sVal = $this->_getValue($a);
        
        $this->_getFormRow ($a['label'], "<textarea class=\"comment_textarea\" name=\"$sName\" $sAttr>$sVal</textarea>");
    }
    
    function inputSelect (&$oForm, $sName, &$a)
    {
        $sAttr = $this->_getAttributes($a);
        $sVal = $this->_getValue($a);

        $sControl = "<select name=\"$sName\" $sAttr>";
        $aValues = $oForm->getValuesArray($a);

        foreach ($aValues as $k => $v)
            if (isset($a['retranslate']) && $a['retranslate'])
                $aValues[$k] = $this->t('_'.$v);
            elseif (isset($a['translate']) && $a['translate'])
                $aValues[$k] = $this->t($v);

        if (isset($a['sort']) && $a['sort'])
            asort ($aValues);

        foreach ($aValues as $k => $v)
            $sControl .= '<option value="'.$k.'" ' . ($k == $a['val'] ? 'selected="selected"' : '' ) . '>' . $v . '</option>';
        
        $sControl .= "</select>";
        
        $this->_getFormRow ($a['label'], $sControl);
    }

    function inputSet (&$oForm, $sName, &$a)
    {
        $sAttr = $this->_getAttributes($a);
        $mixedVal = $this->_getValue($a);

        $aValues = $oForm->getValuesArray($a);
        
        if (isset($a['retranslate']) && $a['retranslate'])
            $v = $this->t('_'.$v);
        elseif (isset($a['translate']) && $a['translate'])
            $v = $this->t($v);

        foreach ($aValues as $k => $v) {

            $isSelected = false;
            if (is_array($mixedVal))
                $isSelected = in_array($k, $mixedVal);
            else
                $isSelected = ($k == $mixedVal);

            $sControl .= '<input type="checkbox" name="' . $sName . '[]" id="'.$sName.$k.'" value="'.$k.'" ' . ($isSelected ? 'checked="checked"' : '' ) . '/><label for="'.$sName.$k.'">' . $v . '</label><br />';
        }
        
        $this->_getFormRow ($a['label'], '<div style="max-height:200px; overflow-y:auto;">' . $sControl . '</div>');
    }

    function inputFile (&$oForm, $sName, &$a)
    {
        $sAttr = $this->_getAttributes($a);
        $sVal = $this->_getValue($a);

        $this->_getFormRow ($a['label'], "<input type=\"file\" name=\"$sName\" value=\"$sVal\" $sAttr />");
    }
    
    function inputSubmit (&$oForm, $sName, &$a)
    {
        $sAttr = $this->_getAttributes($a);
        $sVal = $this->_getValue($a);

        $this->_getFormRow ($a['label'], "<input type=\"submit\" name=\"$sName\" value=\"".$this->t($sVal)."\" $sAttr />");
    }

    function paginate ($iStart, $iNum, $sLink, $aGetParams =  array())
    {        
        $this->_load('Config', 'HelperHtml');        
        $iPerPage = $this->config->get ('iPerPage');
        
        $sRet = '';
        $iBorders = 3;        
        $iCurPage = (int)($iStart / $iPerPage);
        $iMaxPage = (int)($iNum / $iPerPage);
        $sGetParams = $this->_getPaginateGetParams ($aGetParams);
             
        if ('/' != substr($sLink, -1))
            $sLink .= '/';

        $sRet = '<a href="'.$sLink."0{$sGetParams}\">".$this->t('Places Pagination First').'</a>';
        if ($iCurPage - $iBorders >= 1) $sRet .= '<u>...</u>';
        for ($iPage = $iCurPage - $iBorders ; $iPage <=  $iCurPage + $iBorders ; ++$iPage)
        {
            if ($iPage < 0 || $iPage > ($iMaxPage)) continue;
            if ($iCurPage != $iPage)
                $sRet .= '<a href="' . $sLink . ($iPage * $iPerPage) . $sGetParams . '">' . ($iPage + 1) . '</a>';
            else
            $sRet .= '<b>'. ($iPage + 1) . '</b>';
        }
        if ($iMaxPage - $iCurPage - $iBorders >= 1) $sRet .= '<u>...</u>';
        return $sRet;
    }
    
    function _getPaginateGetParams ($aGetParams)
    {    
        $sRet = '';
        foreach ($aGetParams as $k => $v)
            $sRet .= $k . '=' . urlencode($v) . '&';
        return $sRet ? '?'.substr($sRet, 0, -1) : ''; 
    }

    function _getAttributes (&$a)
    {
        $s = '';
        if (isset($a['attributes']))
        {
            for ( reset($a['attributes']) ; list ($k, $v) = each ($a['attributes']) ; )
            $s .= " $k=\"$v\" ";
        }
        elseif (is_array($a))
        {
            for ( reset($a) ; list ($k, $v) = each ($a) ; )
            $s .= " $k=\"$v\" ";
        }
        return $s;
    }

    function _getValue (&$a)
    {
        if (isset($a['val']))
            return $a['val'];
        return '';
    }    

    function _getFormRow ($sLabel, $sControl)
    {
        $sColSpan = ' colspan="2" ';
        $sTD = '';        
        $sStyle = 'style="text-align:center;"';
        if (!empty($sLabel))
        {
            $sTD = '<td valign="top" style="width:100px; text-align:right; white-space:nowrap;">'.$this->t($sLabel).':</td>';
            $sColSpan = '';
            $sStyle = 'style="width:400px; text-align:left;"';
        }
        
        echo "
        <tr>
            $sTD
            <td $sColSpan $sStyle>$sControl</td>
        </tr>";
    }        

    function row ($sLabel, $sValue)
    {
        $this->_getFormRow ($sLabel, $sValue);
    }            
}

?>
