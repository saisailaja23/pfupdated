var aSMDFSelfManageableFields = new Array();
var aSMDFAllowAjaxTo = new Array();
var aSMDFValues = new Array();
var aSMDFRequestedFieldValues = new Array();
var aSMDFIsCustom =  new Array();

function AqbSMDFUpdateDependentField(sFieldName, sParentValue, sParentFieldName) {
	try {
		/*if (sParentFieldName != undefined && sParentFieldName.length) {
			var aParentElements = document.getElementsByName(sParentFieldName);
			for (var i =0; i < aParentElements.length; i++) {
				if (aParentElements[i].type == 'select-one') {
					aParentElements[i].value = sParentValue;
				}
			}
		}*/
		var elDependentFields = new Array();
		var aElements = document.getElementsByName(sFieldName);
		for (var i =0; i < aElements.length; i++) {
			if (aElements[i].type == 'select-one' || aElements[i].type == 'select-multiple') {
				elDependentFields.push(aElements[i]);
			}
		}
		if (elDependentFields.length == 0) return;
		if (sParentValue == '') {
			AqbSMDFResetFields(elDependentFields);
			return;
		}
		var aDependentFieldValues = AqbSMDFGetFieldValues(sFieldName, sParentValue);
		AqbSMDFSetFieldsValues(elDependentFields, aDependentFieldValues);
		for (var i =0; i < elDependentFields.length; i++) {
			elDependentFields[i].disabled = false;
			if (elDependentFields[i].onchange != undefined) elDependentFields[i].onchange(elDependentFields[i].value);
		}
	}catch(err) {
		if (err == 1 && aSMDFAllowAjaxTo[sFieldName]) { //try AJAX in first time
			AqbSMDFResetFields(elDependentFields);
			for (var i =0; i < elDependentFields.length; i++) elDependentFields[i].options.add(new Option(sAqbSMDFLoading, '', true, false));
			AqbSMDFLoadFieldValues(sFieldName, sParentValue);
			return;
		} else if (err == 2 && aSMDFAllowAjaxTo[sFieldName] && !aSMDFRequestedFieldValues[sFieldName+sParentValue]) { //try AJAX again
			AqbSMDFResetFields(elDependentFields);
			for (var i =0; i < elDependentFields.length; i++) elDependentFields[i].options.add(new Option(sAqbSMDFLoading, '', true, false));
			AqbSMDFLoadFieldValues(sFieldName, sParentValue);
			return;
		}
		AqbSMDFResetFields(elDependentFields);
		return;
	}
}
function AqbSMDFGetFieldValues(sFieldName, sPart) {
	if (aSMDFValues[sFieldName] == undefined) throw 1; //wasn't loaded at all
	if (aSMDFValues[sFieldName][sPart] == undefined) throw 2; //was already loading something
	if (aSMDFValues[sFieldName][sPart]['value'].length == 0) throw 3; //loaded zero set

	return aSMDFValues[sFieldName][sPart];
}
function AqbSMDFSetFieldsValues(elFields, aFieldValues) {
	for (var i =0; i < elFields.length; i++) {
		elFields[i].innerHTML = '';
		for (var j = 0; j < aFieldValues['value'].length; j++)
			elFields[i].options.add(new Option(aFieldValues['name'][j], aFieldValues['value'][j], true, false));
	}
}
function AqbSMDFResetFields(elFields){
	for (var i =0; i < elFields.length; i++) {
		elFields[i].innerHTML = '';
		if (elFields[i].onchange != undefined) elFields[i].onchange('');
		elFields[i].disabled = true;
	}
}
function AqbSMDFLoadFieldValues(sFieldName, sParentValue) {
	jQuery.getScript(sAqbSMDFHomeUrl+"action_get_values/"+sFieldName+'/'+sParentValue+'/'+aSMDFIsCustom[sFieldName]+'/');
	aSMDFRequestedFieldValues[sFieldName+sParentValue] = true;
}

function AqbSMDFShowCMControl(sControlsName) {
	$('#id_df_'+sControlsName+'_select').hide();
	$('#id_df_'+sControlsName+'_text').show();
	$(new Option('custom', '', true, false)).appendTo('#id_df_'+sControlsName+'_select select');
	$('#id_df_'+sControlsName+'_select select').val('');
	$('#id_df_'+sControlsName+'_select select').change();
}
function AqbSMDFShowMainControl(sControlsName) {
	$('#id_df_'+sControlsName+'_text input').val('');
	$('#id_df_'+sControlsName+'_text').hide();
	$('#id_df_'+sControlsName+'_select').show();
	if ($('#id_df_'+sControlsName+'_select select option:last').val() == '') $('#id_df_'+sControlsName+'_select select option:last').remove();
	$('#id_df_'+sControlsName+'_select select').val($('#id_df_'+sControlsName+'_select select option:first').val());
	$('#id_df_'+sControlsName+'_select select').change();
}
$(document).ready(function(){
	$('tr[smdf]').each(function(iIndex, elRow){
		var sControlsName = $(elRow).attr('smdf');
		if ($('#id_df_'+sControlsName+'_select td:last a:last').length == 0)
			$('#id_df_'+sControlsName+'_select td:last').append('<a href="#" onclick="javascript:AqbSMDFShowCMControl(\''+sControlsName+'\'); return false;">'+sAqbSMDFTypeIn+'</a>');
	})
	$('tr[smdf_text]').each(function(iIndex, elRow){
		var sControlsName = $(elRow).attr('smdf_text');
		if ($('#id_df_'+sControlsName+'_text td:last a:last').length == 0)
			$('#id_df_'+sControlsName+'_text td:last').append('<a href="#" onclick="javascript:AqbSMDFShowMainControl(\''+sControlsName+'\'); return false;">'+sAqbSMDFSelectFromList+'</a>');
	})
})