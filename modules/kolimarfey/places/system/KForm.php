<?php

define ('KFORM_SUBMITTED', 1);
define ('KFORM_INVALID', 2);
define ('KFORM_SHOW', 4);

require_once(BX_DIRECTORY_PATH_ROOT . K_SYS_PATH . 'KBase.php');

class KForm extends KBase
{    
    var $aForm = array ();
    var $aFormErrors = array ();
    var $aValues = array ();

    function KForm ()
    {     
        parent::KBase();           
    }    

    function display ()
    {
        if (!$this->aForm) return;

        $this->_load('HelperHtml', 'Form');

        $this->helperhtml->formBegin(
            isset($this->aForm['name']) ? $this->aForm['name'] : '', 
            isset($this->aForm['action']) ? $this->aForm['action'] : '', 
            isset($this->aForm['target']) ? $this->aForm['target'] : '', 
            $this->aForm);
        
            $this->helperhtml->tableBegin (array('align' => 'center'));

        $submitted = $this->submitted();        
        foreach ( $this->aForm['fields'] as $k => $v)
        {        
            $r = &$this->aForm['fields'][$k];
            if (!isset($r['type']) || !$r['type']) continue;
            $sFunc = 'input' . ucfirst($r['type']);
            if ($submitted && $r['type'] != 'submit') 
                $this->setValue ($k, $r);
            $this->helperhtml->$sFunc($this, $k, $r);
        }
        
        $a = array ('val' => 1);
        $this->helperhtml->inputHidden ($this, 'submitted'.$this->aForm['name'], $a);
        
        $this->helperhtml->tableEnd ();
        
        $this->helperhtml->formEnd();
    }
  
    function addError ($s) 
    {
        $this->aFormErrors[] = $s;
    }

    function formErrors ()
    {     
        if ($this->aFormErrors)
        {
            echo "<div style=\"border:3px solid red; padding:10px;\">";
            for ( reset ($this->aFormErrors) ; list (, $sErr) = each ($this->aFormErrors) ; )
                echo "$sErr <br />";
            echo "</div>"; 
        }
    }

    function validated ()
    {
        $validated = 1;
        for ( reset ($this->aForm['fields']) ; list ($k, $r) = each ($this->aForm['fields']) ; )
        {        
            if (!$r['required']) continue;
            
            if (!isset($r['validator']) && !$this->get($k))
            {
                $validated = 0;
                $this->aFormErrors[$k] = isset($r['error']) ? $this->t($r['error']) : $this->t('Places Error In') . $r['label'];
            }
            elseif (isset($r['validator']) && !isset($r['validator_params']) && !$this->$r['validator']($this->get($k)))
            {
                $validated = 0;
                $this->aFormErrors[$k] = isset($r['error']) ? $this->t($r['error']) : $this->t('Places Error In') . $r['label'];
            }
            elseif (isset($r['validator']) && isset($r['validator_params']) && !$this->$r['validator']($this->get($k), $r['validator_params']))
            {
                $validated = 0;
                $this->aFormErrors[$k] = isset($r['error']) ? $this->t($r['error']) : $this->t('Places Error In') . $r['label'];
            }            
        }
        return $validated;
    }
    
    function submitted ()
    {
        return $this->get('submitted'.$this->aForm['name']) ? true : false;
    }
    
    function check ()   
    {    
        if ($this->submitted())
        {
            if ($this->validated ())
            {
                return KFORM_SUBMITTED;
            }
            else
            {
                return KFORM_INVALID;
            }
        }
        else
        {
            return KFORM_SHOW;
        }
    }

    // validators

    function checkMin ($v, $p)
    {
        return strlen ($v) >= $p['min'] ? true : false;
    }

    function checkMinMax ($v, $p)
    {
        return strlen ($v) >= $p['min'] && strlen ($v) <= $p['max'] ? true : false;
    }

    function checkMinNum ($v, $p)
    {
        return $v >= $p['min'] ? true : false;
    }

    function checkMinMaxNum ($v, $p)
    {
        return $v >= $p['min'] && $v <= $p['max'] ? true : false;
    }

    // 
    
    function get ($sName, $isConvertArray = false)
    {
        $v = '';
        if (isset($this->aValues[$sName])) 
            $v = $this->aValues[$sName];
        elseif (isset($_POST[$sName])) 
            $v = $_POST[$sName];
        elseif (isset($_GET[$sName])) 
            $v = $_GET[$sName];
        elseif (isset($_FILES[$sName])) 
            $v = $_FILES[$sName];
        return $isConvertArray && is_array($v) ? array_sum($v) : $v;
    }

    function getEscaped ($sName)
    {
        $v = $this->get ($sName, true);
        if (!isset($this->aForm['fields'][$sName]['db'])) return $v;
        $this->_load('Security', 'Form');        
        if ('tinymce' == $this->aForm['fields'][$sName]['type'])
        {
            return $this->security->passXss($v);
        }
        else
        if (isset($this->aForm['fields'][$sName]['db_type']))
        {
            $sFunc = 'pass' . ucfirst($this->aForm['fields'][$sName]['db_type']);
            return $this->security->$sFunc($v);
        }
        else
        {
            return $this->security->passText($v);
        }
    }
    
    function setValue ($sName, &$r)
    {        
        $r['val'] = $this->get($sName, isset($r['convert_array']) && $r['convert_array'] ? true : false);
    }

    function getValuesArray (&$r)
    {
        
        if (!isset($r['values'])) return array ();        

        switch (isset($r['values_type']) ? $r['values_type'] : '')
        {
            case 'sql':                
                $this->_load('Model', 'Form');
                $aa = $this->model->getAll($r['values']);
                $aRet = array();                
                foreach ($aa as $aRow) $aRet[$aRow['key']] = $aRow['value'];
                return $aRet;
                break;
            default:
                return $r['values'];
        }
    }

    function setValues (&$a)
    {               
        $this->aValues = $a;
    }

    function set ($sName, $sVal)
    {
        $this->aValues[$sName] = $sVal;
    }

    function insert ($sTable = '')
    {
        if (!$sTable)
            $sTable = K_TABLE_PREFIX . $this->aForm['table'];

        $sSql = "INSERT INTO `$sTable` SET " . $this->_getSqlFields();
        $this->_load('Model', 'Form');
        if (!$this->model->query($sSql))
            return false;
        return $this->model->lastId();
    }

    function update ($sTable, $sWhere)
    {
        if (!$sTable)
            $sTable = K_TABLE_PREFIX . $this->aForm['table'];

        $sSql = "UPDATE `$sTable` SET " . $this->_getSqlFields() . " WHERE 1 " . $sWhere . " LIMIT 1";
        $this->_load('Model', 'Form');
        if (!$this->model->query($sSql))
            return false;
        return true;
    }    

    function autoUpdate ($mixedVal)
    {        
        $sTable = K_TABLE_PREFIX . $this->aForm['table'];

        $sSql = "UPDATE `$sTable` SET " . $this->_getSqlFields() . " WHERE `{$this->aForm['key']}` = '$mixedVal' LIMIT 1";
        $this->_load('Model', 'Form');
        if (!$this->model->query($sSql))
            return false;
        return true;
    }    

    function _getSqlFields ()
    {
        $sSql = '';
        for ( reset ($this->aForm['fields']) ; list ($k, $r) = each ($this->aForm['fields']) ; )
        {
            if (!isset($r['db']) || (isset($r['db_save']) && !$r['db_save'])) continue;
            $sSql .= "`{$r['db']}` = '".$this->getEscaped($k)."',";
        }
        
        if (isset($this->aForm['multiple_set_id']) && $this->aForm['multiple_set_id'])
        {
            $this->_load('Model', 'Form');
            $iId = $this->model->generateBinaryIdForTable ($this->aForm['table'], $this->aForm['key']);
            if ($iId)
                $sSql .= "`{$this->aForm['key']}` = '$iId',";
        }

        return substr($sSql, 0, -1);
    }    
}

?>
