<?php

require_once('inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'prof.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'utils.inc.php');

bx_import('BxTemplFormView');

class ctzIndexSearch extends Thing {
	var $aForm;

	var $aFieldsList;
	var $sFieldCurr;
	
	var $aSectionsList;
	var $sSectionCurr;
	
	function ctzIndexSearch ($sSection = '', $sField = '') {
		$this->aSectionsList = array(
			'Location' => array('Country', 'Region', 'State'),
			'General Info' => array('ProfileType'),
			'AdoptionAgency' => '',
			'Child_Pref' => array('ChildEthnicity', 'ChildGender', 'ChildAge', 'ChildSpecialNeeds'),
			'AboutMe' => array('Religion', 'Education', 'Ethnicity', 'Smoking'),
			'Home' => array('Residency', 'Pet', 'Neighborhood', 'FamilyStructure')
		);
		
		$sSection = strip_tags($sSection);
		if (mb_strlen($sSection) > 0 && array_key_exists($sSection, $this->aSectionsList))
			$this->sSectionCurr = $sSection;
		else {
			$aKeys = array_keys($this->aSectionsList);
			$this->sSectionCurr = $aKeys[0];
		}
		$this->aFieldsList = is_array($this->aSectionsList[$this->sSectionCurr]) ? $this->aSectionsList[$this->sSectionCurr] : array($this->sSectionCurr);
		$sField = strip_tags($sField);
		if (mb_strlen($sField) > 0 && in_array($sField, $this->aFieldsList))
			$this->sFieldCurr = $sField;
		else
			$this->sFieldCurr = $this->aFieldsList[0];
	}
	
	function getFormArray () {
		$this->aForm = array(
			'form_attrs' => array(
		        'id' => 'index_search',
		        'name' => 'search',
		        'action' => BX_DOL_URL_ROOT . 'search.php',
		        'method' => 'get',
		    ),
		    'inputs' => array()
	    );
	    $this->aForm['inputs']['sections'] = array(
			'type' => 'select',
			'name' => 'section',
			'caption' => _t('_Section'),
			'value' => $this->sSectionCurr,
			'attrs' => array(
				'onchange' => 'reloadForm(this.value, \'' . $this->sFieldCurr . '\');'
			)
	    );
	    foreach ($this->aSectionsList as $sKey => $mixedSection)
	    	$this->aForm['inputs']['sections']['values'][] = array('key' => $sKey, 'value' => _t('_FieldCaption_' . $sKey . '_View'));
	    if (is_array($this->aSectionsList[$this->sSectionCurr])) {
		    $this->aForm['inputs']['fields'] = array(
				'type' => 'select',
				'name' => 'field',
				'caption' => _t('_adm_fields_box_cpt_field'),
				'value' => $this->sFieldCurr,
				'attrs' => array(
					'onchange' => 'reloadForm(\'' . $this->sSectionCurr . '\', this.value);'
				)
		    );
    		$aFieldsValues = array();
			foreach ($this->aFieldsList as $sField) {
				$aFieldsValues[] = array(
					'key' => $sField,
					'value' => _t('_FieldCaption_' . $sField . '_View')
				);
			}
			$this->aForm['inputs']['fields']['values'] = $aFieldsValues;
	    }
	    $this->aForm['inputs']['params'] = array(
    		'type' => 'select',
    		'name' => 'param',
    		'caption' => _t('_Choose'),
    		'attrs' => array(
    			'onchange' => '$("#search_field").val(this.value)'
    		)
    	);
    	$this->aForm['inputs']['search_field'] = array(
    		'type' => 'hidden',
    		'name' => $this->sFieldCurr . '[]',
    		'value' => '',
    		'attrs' => array(
    			'id' => 'search_field'
    		)
    	);
    	$this->aForm['inputs']['search_mode'] = array(
    		'type' => 'hidden',
    		'name' => 'search_mode',
    		'value' => 'adv',
    	);
    	$this->aForm['inputs']['paginate'] = array(
    		'type' => 'select',
    		'name' => 'per_page',
    		'caption' => _t('_per_page'),
    		'values' => array(5, 10, 20, 50)
    	);
    	$this->aForm['inputs']['submit'] = array(
    		'type' => 'submit',
    		'value' => _t('_Search')
    	);
		$aFieldValues = $this->getFieldValues($this->sFieldCurr);
		$this->aForm['inputs']['params']['values'] = $aFieldValues;
		$this->aForm['inputs']['search_field']['value'] = $aFieldValues[0]['key'];
	}
	
	function getFieldValues ($sFieldName) {
		$sqlQuery = "SELECT `Values` FROM `sys_profile_fields` WHERE `Name`='$sFieldName' LIMIT 1";
		$aValues = array();
		$sValue = $GLOBALS['MySQL']->getOne($sqlQuery);
		$aFinal = array();
		if (strpos($sValue, '#!' . $sFieldName) === false) {
			$aValues = explode("\n", $sValue);
			foreach ($aValues as $iKey => $sValue) {
				$aFinal[] = array(
					'key' => $sValue,
					'value' => _t('_FieldValues_' . $sValue)
				);
			}
		}
		else {
			$aValues = $GLOBALS['aPreValues'][$sFieldName];
			foreach ($aValues as $sKey => $aValue) {
				$sValue = _t($aValue['LKey']);
				if (strpos($sValue, '__') === 0)
					$sValue = substr($sValue, 2);
				
				$aFinal[] = array(
					'key' => $sKey,
					'value' => $sValue
				);
			}
		}
		return $aFinal;
	}
	    
    function getForm () {
    	$oForm = new BxTemplFormView($this->aForm);
    	return $oForm->getCode();
    }
}

?>