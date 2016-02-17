<?php
/***************************************************************************
*
*     copyright            : (C) 2009 AQB Soft
*     website              : http://www.aqbsoft.com
*
* IMPORTANT: This is a commercial product made by AQB Soft. It cannot be modified for other than personal usage.
* The "personal usage" means the product can be installed and set up for ONE domain name ONLY.
* To be able to use this product for another domain names you have to order another copy of this product (license).
*
* This product cannot be redistributed for free or a fee without written permission from AQB Soft.
*
* This notice may not be removed from the source code.
*
***************************************************************************/

bx_import('BxDolModule');

require_once( BX_DIRECTORY_PATH_PLUGINS . 'Services_JSON.php' );
require_once(BX_DIRECTORY_PATH_INC . "prof.inc.php");

//a fix for stupid IE to prevent ajax requests being cached
if (strpos($_SERVER['REQUEST_URI'], 'aqb_smdf') !== false and isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) and $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) {
	send_headers_page_changed();
}
//a fix for stupid IE to prevent ajax requests being cached

class AqbSMDFModule extends BxDolModule {
	var $_aProcessedFields;
	/**
	 * Constructor
	 */
	function AqbSMDFModule($aModule) {
		global $aPreValues;
	    parent::BxDolModule($aModule);
	    $aPreValues['DFDependentLists'] = $this->_oDb->getPossibleListsForDependentField();
	}
	function displayDependencies($bAddWrapper = false) {
		$aDependentLists = $this->_oDb->getDependentLists();
		return $bAddWrapper ? '<div id="aqb_df_wrapper">'.$this->_oTemplate->getDependencies($aDependentLists).'</div>' : $this->_oTemplate->getDependencies($aDependentLists);
	}
	function displayNewValues($bAddWrapper = false) {
		global $aPreValues;

		$aNewValues = array();

		$aDependentLists = $this->_oDb->getDependentLists();
		foreach ($aDependentLists as $aLink) {
			foreach ($aLink as $sDepList) {
				$aList = $aPreValues[$sDepList];
				foreach ($aList as $sKey => $aListItem) {
					if ($aListItem['Extra3'] == 1) {
						$aNewValues[] = array(
							'ListName' => $sDepList,
							'ListItem' => $aListItem,
							'ListValue' => $sKey
						);
					}
				}
			}
		}
		return $bAddWrapper ? '<div id="aqb_df_values_wrapper">'.$this->_oTemplate->getNewValues($aNewValues).LoadingBox('formItemEditLoading2').'</div>' : $this->_oTemplate->getNewValues($aNewValues);
	}
	function actionPostmoderateCustomValues() {
		if(!isAdmin()) $this->_oTemplate->displayAccessDenied();

		if ($_POST['action'] == 'approve') {
			$this->_oDb->approveValues($_POST['values'], $_POST['values_edit']);
		} elseif($_POST['action'] == 'remove') {
			$this->_oDb->deleteValues($_POST['values']);
		}
		$this->_compilePreValues();
		return $this->displayNewValues();
	}
	function actionDependencies() {
		if(!isAdmin()) $this->_oTemplate->displayAccessDenied();
		return $this->displayDependencies();
	}
	function getTopMenu() {
		return array(
			'new_tl_list' => array(
				'href' => 'javascript: void(0);',
				'dynamic' => false,
				'active' => false,
				'title' => _t('_aqb_smdf_new_tl'),
				'onclick' => 'AqbSMDFCreateTopList(\''.BX_DOL_URL_ADMIN.'\')'
			),
			'new_dl_list' => array(
				'href' => 'javascript: void(0);',
				'dynamic' => false,
				'active' => false,
				'title' => _t('_aqb_smdf_new_dl'),
				'onclick' => 'AqbSMDFCreateDependentList(\''.BX_DOL_URL_ROOT.$this->_oConfig->getBaseUri().'action_new/\')'
			),
		);
	}

	function actionNew() {
		if(!isAdmin()) $this->_oTemplate->displayAccessDenied();
		header('Content-Type:text/javascript');

		if ($_REQUEST['Parent']) $_POST['save'] = $_GET['save'] = $_REQUEST['save'] = _t('_aqb_smdf_proceed');

		$aAvailableLists = $this->_oDb->getAvailableLists();
		$oForm = $this->_oTemplate->getNewListForm($aAvailableLists);

		$sResult = PopupBox('aqb_popup_edit_form', _t('_aqb_smdf_new_dl'), $GLOBALS['oAdmTemplate']->parseHtmlByName('design_box_content.html', array('content' => $oForm->getCode().LoadingBox('formItemEditLoading'))));
		if (!$oForm->isSubmitted()) return $sResult;

		if ($oForm->isSubmittedAndValid()) {
			$aResult = array(
				'result' => true,
				'return_code' => BX_DOL_URL_ROOT.$this->_oConfig->getBaseUri().'action_edit/'.urlencode($_REQUEST['Name']).'/'.urlencode($_REQUEST['Parent']).'/'
			);
		} else {
			$aResult = array(
				'result' => false,
				'return_code' => $sResult
			);
		}
		$oJson = new Services_JSON();
        return $oJson->encode($aResult);
	}
	function actionEdit($sListName, $sParent, $sParentValue = '') {
		header('Content-Type: text/html; charset=utf-8');
		if(!isAdmin()) $this->_oTemplate->displayAccessDenied();

		$sMessage = '';
		if ($_REQUEST['action'] == 'Save') {
			$sMessage = $this->saveList($sListName, $sParent, $sParentValue, $_REQUEST['PreList']);
		}

		return $this->_oTemplate->getListEditForm($sListName, $sParent, $sParentValue, $sMessage);
	}

	function actionGetValuesCache($sField, $bCustom = false) {
		global $aPreValues;

		send_headers_page_changed();

		header('Content-Type: text/javascript;');

		$aField = $this->_oDb->getFieldPropertiesByName($sField, $bCustom);
		if (!$aField || !isset($aPreValues[$aField['values']])) return;

		echo "aSMDFIsCustom['{$sField}'] = ".($bCustom ? '1' : '0').";\n";
		if (!$aField['ajax']) {
			echo "aSMDFValues['{$sField}'] = new Array();\n";
			$aAllValues = $aPreValues[$aField['values']];
			$aInitializedArrays = array();
			foreach ($aAllValues as $sFieldValue => $aFieldValue) {

				$sParentValue = addslashes($aFieldValue['Extra2']);
				if (!isset($aInitializedArrays[$sParentValue])) {
					$aInitializedArrays[$sParentValue] = true;
					echo "aSMDFValues['{$sField}']['{$sParentValue}'] = new Array();\n";
					echo "aSMDFValues['{$sField}']['{$sParentValue}']['name'] = new Array();\n";
					echo "aSMDFValues['{$sField}']['{$sParentValue}']['value'] = new Array();\n";
				}
				$value = $sFieldValue;
				$name = addslashes(_t($aFieldValue['LKey']));
				echo "aSMDFValues['{$sField}']['{$sParentValue}']['name'].push('{$name}');\n";
				echo "aSMDFValues['{$sField}']['{$sParentValue}']['value'].push('{$value}');\n";
			}
			echo "aSMDFAllowAjaxTo['{$sField}'] = false;";
		} else {
			echo "aSMDFAllowAjaxTo['{$sField}'] = true;";
		}
	}
	function actionGetValues($sFieldName, $sFieldPart, $bCustom = 0) {
		global $aPreValues;

		send_headers_page_changed();

		header('Content-Type: text/javascript;');

		if (!$sFieldName || !$sFieldPart) return;
		$aField = $this->_oDb->getFieldPropertiesByName($sFieldName, $bCustom);
		$sPreValuesListName = $aField['values'];
		if (!isset($aPreValues[$sPreValuesListName])) return;

		echo "if (aSMDFValues['{$sFieldName}'] == undefined) aSMDFValues['{$sFieldName}'] = new Array();\n";
		echo "aSMDFValues['{$sFieldName}']['{$sFieldPart}'] = new Array();\n";
		echo "aSMDFValues['{$sFieldName}']['{$sFieldPart}']['name'] = new Array();\n";
		echo "aSMDFValues['{$sFieldName}']['{$sFieldPart}']['value'] = new Array();\n";

		$aAllValues = $aPreValues[$sPreValuesListName];


		foreach ($aAllValues as $sFieldValue => $aFieldValue) {
			if ($aFieldValue['Extra2'] != $sFieldPart) continue;
			$value = addslashes($sFieldValue);
			$name = addslashes(_t($aFieldValue['LKey']));
			echo "aSMDFValues['{$sFieldName}']['{$sFieldPart}']['name'].push('{$name}');\n";
			echo "aSMDFValues['{$sFieldName}']['{$sFieldPart}']['value'].push('{$value}');\n";
		}
		echo "AqbSMDFUpdateDependentField('{$sFieldName}', '{$sFieldPart}');";
	}

	function serviceDepTab($oPFM, $aField, $iFieldID) {
		global $aPreValues;

		$aDependencyInfo = $this->_oDb->getRow("SELECT * FROM `aqb_smdf_dependencies` WHERE `Field` = {$iFieldID} LIMIT 1");
		$sValues = $this->_oDb->getOne("SELECT `Values` FROM `sys_profile_fields` WHERE `ID` = {$iFieldID} LIMIT 1");
		$aLists = array();
		foreach ($aPreValues['DFDependentLists'] as $sKey => $aVal) {
			$aLists[$sKey] = $aVal['LKey'];
		}
		if (strpos($sValues, '#!') === 0) $sValues = substr($sValues, 2);
		else $sValues = '';
		$aForm = array(
			'DependsOn' => array(
				'label' => _t('_aqb_smdf_depends_on'),
				'type'  => 'select',
				'info'  => addslashes(_t('_aqb_smdf_depends_on_info')),
				'value' => $aDependencyInfo['DependsOn'],
				'values' => array('' => _t('_aqb_smdf_no_dependency')) + $this->_oDb->getPossibleFieldsForDependentField($aField['Name'])
			),
			'ValuesList' => array(
				'label' => _t('_aqb_smdf_values_list'),
				'type'  => 'select',
				'info'  => addslashes(_t('_aqb_smdf_values_list_info')),
				'value' => $sValues,
				'values' => $aLists
			),
			'UseAjax' => array(
				'label' => _t('_aqb_smdf_use_ajax'),
				'type'  => 'checkbox',
				'info'  => addslashes(_t('_aqb_smdf_use_ajax_info')),
				'value' => $aDependencyInfo['UseAjax']
			),
			'SelfManageable' => array(
				'label' => _t('_aqb_smdf_self_manageable'),
				'type'  => 'checkbox',
				'info'  => addslashes(_t('_aqb_smdf_self_manageable_info')),
				'value' => $aDependencyInfo['SelfManageable']
			),
		);

		if (!empty($aForm['DependsOn']['value'])) {
			$sParentVal = $aForm['DependsOn']['value'];
		} else {
			$aKeys = array_keys($aForm['DependsOn']['values']);
			$sParentVal = $aKeys[0];
		}

		foreach ($aPreValues['DFDependentLists'] as $sKey => $aVal) {
			if ($aVal['Extra2'] != $sParentVal) unset($aForm['ValuesList']['values'][$sKey]);
		}
		$sDisabled = 'false';
		if (empty($aForm['ValuesList']['values'])) $sDisabled = 'true';

		$this->_aProcessedFields[] = 'ValuesList';
		echo $this->serviceGetCacheScripts(true);

		$oPFM->genTableEdit( $aForm, 'f5' );
?>
		<script language="javascript">
		$(document).ready(function(){
			var df_el = document.getElementsByName('DependsOn')[0];
			df_el.onchange = function(){
				AqbSMDFUpdateDependentField("ValuesList", this.value);
			}
			df_el = document.getElementsByName('ValuesList')[0];
			df_el.disabled = <?php echo $sDisabled?>;
		})
		</script>
<?php
	}
	function serviceDepTabCheck($oPFM, $aField, $sValues) {
		if (empty($aField['DependsOn'])) return array($sValues, false);

		if ($aField['DependsOn'] && ($aField['Type'] != 'select_one' && $aField['Type'] != 'select_set' || $aField['Control_one'] != 'select')) {
			$oPFM->genSaveItemError('Only fields of `Select (Dropdown box)` control type could be dependent', 'DependsOn');
			return array($sValues, true);
		}

		//paranoya
		if (empty($aField['ValuesList'])) {
			$oPFM->genSaveItemError('Values list can not be empty', 'ValuesList');
			return array($sValues, true);
		}
		//paranoya
		if (!$this->_oDb->checkDependency(trim($aField['ValuesList']), $aField['DependsOn'])) {
			$oPFM->genSaveItemError('Selected list doesn\'t belongs to dependency for the selected field.', 'ValuesList');
			return array($sValues, true);
		}

		return array('#!'.trim($aField['ValuesList']), false);
	}
	function serviceGetCacheScripts($bCustom = false) {
		if (!count($this->_aProcessedFields)) return;

		$sFromListCaption = addslashes(_t('_aqb_smdf_select_from_list'));
		$sTypeInCaption = addslashes(_t('_aqb_smdf_type_in'));

		$sModuleURL = BX_DOL_URL_ROOT.$this->_oConfig->getBaseUri();

		$sLoading = addslashes(_t('_aqb_smdf_loading'));
		$sRet = <<<EOF
			<script language="javascript" type="text/javascript">
				var sAqbSMDFHomeUrl = '{$sModuleURL}';
				var sAqbSMDFLoading = '{$sLoading}';
				var sAqbSMDFSelectFromList = '{$sFromListCaption}';
				var sAqbSMDFTypeIn = '{$sTypeInCaption}';
			</script>
EOF;
		$sRet .= $this->_oTemplate->addJs('df.js', true);
		foreach ($this->_aProcessedFields as $sField) {
			$sRet .= '<script language="javascript" type="text/javascript" src="'.$sModuleURL.'action_get_values_cache/'.urlencode($sField).'/'.($bCustom ? '1' : '0').'/"></script>';
		}
		return $sRet;
	}
	function serviceDepTabUpdate($iField, $aField) {
		if (empty($aField['DependsOn']) || $aField['Type'] != 'select_one' && $aField['Type'] != 'select_set' || $aField['Control_one'] != 'select') $this->_oDb->cleanDependency($iField);
		else $this->_oDb->updateDependency($iField, $aField['DependsOn'], $aField['UseAjax'], $aField['SelfManageable']);
	}
	function serviceDepTabDelete($iField) {
		$this->_oDb->query("DELETE FROM `aqb_smdf_dependencies` WHERE `Field` = {$iField} OR `DependsOn` = {$iField}");
	}
	function serviceSetDependency(&$aParentField, &$aDependentField, $sDepListName, $bSelfManageable = false, &$aInputs = array()) {
		global $aPreValues;

		if (!empty($aParentField['value'])) {
			$sParentVal = $aParentField['value'];
		} else {
			$aKeys = array_keys($aParentField['values']);
			$sParentVal = $aKeys[0];
		}

		$aParentField['attrs']['onchange'] .= 'AqbSMDFUpdateDependentField("'.addslashes($aDependentField['name']).($aDependentField['type'] == 'select_multiple' ? '[]' : '').'", this.value);';

		foreach ($aPreValues[$sDepListName] as $sKey => $aVal) {
			if ($aVal['Extra2'] != $sParentVal && !$aVal['temp']) unset($aDependentField['values'][$sKey]);
		}

		if (empty($aDependentField['values']) && $aDependentField['type'] == 'select') $aDependentField['attrs']['disabled'] = 'disabled';

		if ($bSelfManageable) {
			$this->setSMDFControls($aDependentField, $aInputs);
		}

		$this->_aProcessedFields[] = $aDependentField['type'] == 'select_multiple' ? $aDependentField['name'].'[]' : $aDependentField['name'];
	}
	function setSMDFControls(&$aField, &$aInputs) {
		$sRawFieldName = str_replace(array('[', ']'), array('', ''), $aField['name']);
		$aField['tr_attrs']['smdf'] = $sRawFieldName;
		$aField['tr_attrs']['id'] = 'id_df_'.$sRawFieldName.'_select';

		$mValue = $_REQUEST[$aField['name'].'_df'];
		if (is_array($mValue)) {
			preg_match('/\[.*\]$/', $aField['name'], $m);
			$iFieldIndex = $m[0];
			if (!empty($mValue[$iFieldIndex])) $aField['tr_attrs']['style'] = 'display:none';
		} elseif (!empty($mValue)) {
			$aField['tr_attrs']['style'] = 'display:none';
		}

		$aNewInputs = array();
		foreach ($aInputs as $sField => $aParams) {
			$aNewInputs[$sField] = $aParams;
			if ($aParams['name'] == $aField['name']) {
				$sTextFieldName = $aField['name'].'_df';
				if (preg_match('/\[.*\]$/', $aField['name'], $m)) {
					$sTextFieldName = str_replace('[', '_df[', $aField['name']);
				}

				$aNewInputs[$sField.'_df'] = $aParams;
				$aNewInputs[$sField.'_df']['type'] = 'text';
				$aNewInputs[$sField.'_df']['value'] = '';
	            $aNewInputs[$sField.'_df']['name'] = $sTextFieldName;
				$aNewInputs[$sField.'_df']['tr_attrs'] = array(
                    'id' => 'id_df_'.$sRawFieldName.'_text',
                    'smdf_text' => $sRawFieldName,
                );
                $aNewInputs[$sField.'_df']['attrs'] = array();


                if (is_array($mValue)) {
                	if (empty($mValue[$iFieldIndex])) $aNewInputs[$sField.'_df']['tr_attrs']['style'] = 'display:none';
                } elseif (empty($mValue)) $aNewInputs[$sField.'_df']['tr_attrs']['style'] = 'display:none';
			}
		}

		$aInputs = $aNewInputs;
	}
	function serviceSetDependencies(&$aInputs) {
		$aKeys = array();
		foreach ($aInputs as $iKey => $aInput) {
        	$aKeys[$aInput['name']] = $iKey;
		}

		$aDepFields = $this->_oDb->getFieldsForSettingDependency();
		foreach ($aDepFields as $aField) {
			//if (!isset($aKeys[$aField['Name']]) || !isset($aKeys[$aField['DependsOn']])) continue;
			$sValuesList = substr($aField['Values'], 2);

			$iDependsOnKey = $aKeys[$aField['DependsOn']]; $iFieldKey = $aKeys[$aField['Name']];
			if ($iDependsOnKey && $iFieldKey) $this->serviceSetDependency($aInputs[$iDependsOnKey], $aInputs[$iFieldKey], $sValuesList, $aField['SelfManageable'], $aInputs);

			$iDependsOnKey = $aKeys[$aField['DependsOn'].'[]']; $iFieldKey = $aKeys[$aField['Name'].'[]'];
			if ($iDependsOnKey && $iFieldKey) $this->serviceSetDependency($aInputs[$iDependsOnKey], $aInputs[$iFieldKey], $sValuesList, $aField['SelfManageable'], $aInputs);

			$iDependsOnKey = $aKeys[$aField['DependsOn'].'[0]']; $iFieldKey = $aKeys[$aField['Name'].'[0]'];
			if ($iDependsOnKey && $iFieldKey) $this->serviceSetDependency($aInputs[$iDependsOnKey], $aInputs[$iFieldKey], $sValuesList, $aField['SelfManageable'], $aInputs);

			$iDependsOnKey = $aKeys[$aField['DependsOn'].'[1]']; $iFieldKey = $aKeys[$aField['Name'].'[1]'];
			if ($iDependsOnKey && $iFieldKey) $this->serviceSetDependency($aInputs[$iDependsOnKey], $aInputs[$iFieldKey], $sValuesList, $aField['SelfManageable'], $aInputs);
		}
		return $this->serviceGetCacheScripts();
	}
	function serviceSetDependenciesOnSearch(&$aInputs) {
		$aKeys = array();
		foreach ($aInputs as $iKey => $aInput) {
        	$aKeys[$aInput['name']] = $iKey;
		}
		$aDepFields = $this->_oDb->getFieldsForSettingDependency();


		if (!$this->_oConfig->isMultiselectAllowed()) {
			foreach ($aDepFields as $aField) {
				$iDependsOnKey = $aKeys[$aField['DependsOn']];
				$iFieldKey = $aKeys[$aField['Name']];
				$aInputs[$iDependsOnKey]['type'] = 'select';
				$aInputs[$iFieldKey]['type'] = 'select';
			}
			return $this->serviceSetDependencies($aInputs);
		}

		$aDepFields = $this->_oDb->getFieldsForSettingDependency();
		foreach ($aDepFields as $aField) {
			if (!isset($aKeys[$aField['Name']]) || !isset($aKeys[$aField['DependsOn']])) continue;
			$sValuesList = substr($aField['Values'], 2);
			$iDependsOnKey = $aKeys[$aField['DependsOn']];
			$iFieldKey = $aKeys[$aField['Name']];
			if (!$iDependsOnKey || !$iFieldKey) continue;
			$s1 = $aInputs[$iDependsOnKey]['name'];
			$s2 = $aInputs[$iFieldKey]['name'];
			$aInputs[$iDependsOnKey]['name'] .= '[]';
			$aInputs[$iFieldKey]['name'] .= '[]';
			$this->serviceSetDependency($aInputs[$iDependsOnKey], $aInputs[$iFieldKey], $sValuesList);
			$aInputs[$iDependsOnKey]['name'] = $s1;
			$aInputs[$iFieldKey]['name'] = $s2;
		}
	}
	function serviceSubstCustomValues() {
		global $aPreValues;

		$aDepFields = $this->_oDb->getFieldsForSettingDependency();
		foreach ($aDepFields as $aField) {
			if (!is_array($_REQUEST[$aField['Name'].'_df']) && !empty($_REQUEST[$aField['Name'].'_df'])) {
				$sNewVal = strip_tags($_REQUEST[$aField['Name'].'_df']);
				$_REQUEST[$aField['Name']] = $_POST[$aField['Name']] = $_GET[$aField['Name']] = $sNewVal;
				$sValuesList = substr($aField['Values'], 2);
				if (!isset($aPreValues[$sValuesList][$sNewVal])) {
					foreach ($aPreValues[$sValuesList] as $sValue => $aValue) {
						if (strcasecmp(_t($aValue['LKey']), $sNewVal) == 0 || strcasecmp($aValue['LKey'], $sNewVal) == 0) {
							$_REQUEST[$aField['Name']] = $_POST[$aField['Name']] = $_GET[$aField['Name']] = $sValue;
							break;
						}
					}
					if ($_REQUEST[$aField['Name']] == $sNewVal) {
						$aParentField = $this->_oDb->getFieldPropertiesByName($aField['DependsOn']);
						$aPreValues[$sValuesList][$sNewVal] = array('LKey' => $sNewVal, 'Extra' => $aParentField['values'], 'Extra2' => strip_tags($_REQUEST[$aField['DependsOn']]), 'temp' => 1);
					}
				}
			}

			for ($i = 0; $i <= 1; $i++) {
				if (!empty($_REQUEST[$aField['Name'].'_df'][$i])) {
					$sNewVal = strip_tags($_REQUEST[$aField['Name'].'_df'][$i]);
					$_REQUEST[$aField['Name']][$i] = $_POST[$aField['Name']][$i] = $_GET[$aField['Name']][$i] = $sNewVal;
					$sValuesList = substr($aField['Values'], 2);
					if (!isset($aPreValues[$sValuesList][$sNewVal])) {
						foreach ($aPreValues[$sValuesList] as $sValue => $aValue) {
							if (strcasecmp(_t($aValue['LKey']), $sNewVal) == 0 || strcasecmp($aValue['LKey'], $sNewVal) == 0) {
								$_REQUEST[$aField['Name']][$i] = $_POST[$aField['Name']][$i] = $_GET[$aField['Name']][$i] = $sValue;
								break;
							}
						}
						if ($_REQUEST[$aField['Name']][$i] == $sNewVal) {
							$aParentField = $this->_oDb->getFieldPropertiesByName($aField['DependsOn']);
							$aPreValues[$sValuesList][$sNewVal] = array('LKey' => $sNewVal, 'Extra' => $aParentField['values'], 'Extra2' => strip_tags($_REQUEST[$aField['DependsOn']][$i]), 'temp' => 1);
						}
					}
				}
			}
		}
	}
	function serviceAddCustomValues() {
		global $aPreValues;

		$aDepFields = $this->_oDb->getFieldsForSettingDependency();
		$iAdded = 0;
		foreach ($aDepFields as $aField) {
			$sValuesList = substr($aField['Values'], 2);
			foreach ($aPreValues[$sValuesList] as $sValue => $aParams) {
				if ($aParams['temp']) {
					$this->_oDb->addValue($sValuesList, $sValue, $aParams['Extra'], $aParams['Extra2']);
					unset($aPreValues[$sValuesList][$sValue]['temp']);
					$iAdded++;
				}
			}
		}
		if ($iAdded > 0) $this->_compilePreValues();
	}
	function servicePrintErrors(&$aErrors) {
		$aDepFields = $this->_oDb->getFieldsForSettingDependency();

		foreach ($aErrors as $iPerson => $aErrorsPerson) {
			foreach ($aErrorsPerson as $sField => $sError) {
				foreach ($aDepFields as $aDepField) {
					if ($aDepField['Name'] == $sField)	{
						$aErrors[$iPerson][$sField.'_df'] = $sError;
					}
				}
			}
		}
	}
	function saveList( $sList, $sDependsOn, $sListPart, $aData ) {

		$sList_db = trim( process_db_input( $sList ) );
		$sListPart_db = trim( process_db_input( $sListPart ) );
		$sDependsOn_db = trim( process_db_input( $sDependsOn ) );

		if( $sList_db == '' || $sListPart_db == '' || $sDependsOn_db == '')
			return 'Some data is missing';

		$sQuery = "DELETE FROM `sys_pre_values` WHERE `Key` = '$sList_db' AND `Extra2` = '{$sListPart_db}' AND `Extra` = '{$sDependsOn_db}'";

		$this->_oDb->query( $sQuery );

		if (!empty($aData))
		foreach( $aData as $iInd => $aRow ) {
			$aRow['Value'] = str_replace( ',', '', trim( $aRow['Value'] ) );

			if( $aRow['Value'] == '' )
				continue;

			$sValue = trim( process_db_input( $aRow['Value'] ) );
			$sLKey = trim( process_db_input( $aRow['LKey'] ) );

			$sQuery = "INSERT INTO `sys_pre_values` ( `Key`, `Value`, `LKey`, `Order`, `Extra2`, `Extra` ) VALUES ( '$sList_db', '$sValue','$sLKey', $iInd, '$sListPart_db', '$sDependsOn_db' )";

			$this->_oDb->query( $sQuery );
		}

		$this->_compilePreValues();

		$ret_val = 'Saved';
		$aValues = array();
		$res = $this->_oDb->res("SELECT `Value` FROM `sys_pre_values` WHERE `Key` = '{$sList_db}'");
		while (($value = mysql_fetch_row($res))) {
			$aValues[$value[0]] += 1;
		}
		$aNotUniqueValues = array();
		foreach ($aValues as $sValue => $iCount) {
			if ($iCount > 1) $aNotUniqueValues[] = $sValue;
		}
		if (count($aNotUniqueValues)) {
			$wording = count($aNotUniqueValues) == 1 ? 'value is' : 'values are';
			$ret_val = "<span style=\"color: red;\">List is saved but the following {$wording} not unique <span style=\"font-size: 16pt;\">".implode(', ',$aNotUniqueValues)."</span>. Please fix it or it will not be displaying properly on a profile page.</span><br />";
		}

		return $ret_val;
	}
	function _compilePreValues() {
		if (function_exists('compilePreValues')) return compilePreValues();

		$sQuery = "SELECT DISTINCT `Key` FROM `sys_pre_values`";
		$rKeys = db_res( $sQuery );

		$rProf = @fopen( BX_DIRECTORY_PATH_INC . 'prof.inc.php', 'w' );
		if( !$rProf ) {
			echo '<b>Warning!</b> Couldn\'t compile prof.inc.php. Please check permissions.';
			return false;
		}

		fwrite( $rProf, "<?php\n\$aPreValues = array(\n" );

		while( $aKey = mysql_fetch_assoc( $rKeys ) ) {
			$sKey    = $aKey['Key'];
			$sKey_db = addslashes( $sKey );

			fwrite( $rProf, "  '$sKey_db' => array(\n" );

			$sQuery = "SELECT * FROM `sys_pre_values` WHERE `Key` = '$sKey_db' ORDER BY `Extra2`, `Order`";
			$rRows  = $this->_oDb->res( $sQuery );

			while( $aRow = mysql_fetch_assoc( $rRows ) ) {
				$sValue_db = addslashes( $aRow['Value'] );
				fwrite( $rProf, "    '{$sValue_db}' => array( " );

				foreach( $aRow as $sValKey => $sValue ) {
					if( $sValKey == 'Key' or $sValKey == 'Value' or $sValKey == 'Order' )
						continue; //skip key, value and order. they already used

					if( !strlen( $sValue ) )
						continue; //skip empty values

					fwrite( $rProf, "'$sValKey' => '" . addslashes( $sValue ) . "', " );
				}

				fwrite( $rProf, "),\n" );
			}

			fwrite( $rProf, "  ),\n" );
		}

		fwrite( $rProf, ");\n" );
		fwrite( $rProf, '
$aPreValues[\'Country\'] = sortArrByLang( $aPreValues[\'Country\'] );

function sortArrByLang( $aArr ) {
	if( !function_exists( \'_t\' ) )
		return $aArr;

	$aSortArr = array();
	foreach( $aArr as $sKey => $aValue )
		$aSortArr[$sKey] = _t( $aValue[\'LKey\'] );

	asort( $aSortArr );

	$aNewArr = array();
	foreach( $aSortArr as $sKey => $sVal )
		$aNewArr[$sKey] = $aArr[$sKey];

	return $aNewArr;
}
		' );

		fclose( $rProf );

		return true;
	}
}
?>