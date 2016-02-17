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

var Profile = new Profiles({_sActionsUrl: '',_sObjName: '',_sViewType: '', _sCtlType: '', _sFilter: '', _oCtlValue:'',_iAttemptId: '', _iStart: '',_iPerPage:'',_sOrderBy:'',_sAnimationEffect:'',_iAnimationSpeed:'', _iTitleLen:'', _iBodyLen:''});                                                                                                                                                                                                                      

function Profiles(oOptions) {                                                                                                                                                                      
    this._sActionsUrl = oOptions.sActionUrl == undefined ? '' : oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oAMS' : oOptions.sObjName;
    this._sViewType = oOptions.sViewType == undefined ? 'geeky' : oOptions.sViewType;
    this._sCtlType = oOptions.sCtlType == undefined ? 'qlinks' : oOptions.sCtlType;
	this._sFilter = '';
	this._sFilterParams = '';
    this._oCtlValue = '';
	this._iAttemptId = 0;
    this._iStart = oOptions.iStart == undefined ? 0 : parseInt(oOptions.iStart);
    this._iPerPage = oOptions.iPerPage == undefined ? 30 : parseInt(oOptions.iPerPage);
    this._sOrderBy = oOptions.sOrderBy == undefined ? '' : oOptions.sOrderBy;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
	this._iTitleLen = 0;
	this._iBodyLen = 0;
}

Profiles.prototype.applyBlocksSettings = function(sUrl, sConfirm){
	if (!confirm(sConfirm)) return;
	
	var oDate = new Date();
	$.post(sUrl,
		{
			_t:oDate.getTime()		
		},
		function(oData){
			alert(oData.message); 
		}, 'json');	
}


Profiles.prototype.changeFilterSearch = function () {
    var sValue = $("[name='pc-filter-input']").val();    
   	var params = '';
	var counter = 0;
	
	$("#pc-search input[type='checkbox']:checked").each(function(){
		params = params + $(this).val() + ','; 
		counter++;
	});

	if(sValue.length <= 0 || counter == 0)
        return;
		
	this._sFilter = sValue;
	this._iStart = ''; 
    this._iPerPage = ''; 
	
	
	this._sFilterParams = params;
    
	this.getBlocks(function() {
        $('#pc-search > .pc-blocks-wrapper:hidden').html('');
    });
}
/*--- Paginate Functions ---*/
Profiles.prototype.changePage = function(iStart) {
    this._iStart = iStart;
    this.getBlocks();
}
Profiles.prototype.changeOrder = function(oSelect) {
    this._sOrderBy = oSelect.value;
    this.getBlocks();
}
Profiles.prototype.changePerPage = function(oSelect) {
    this._iPerPage = parseInt(oSelect.value);
    this.getBlocks();
}

Profiles.prototype.orderByField = function(sOrderBy, sFieldName) {
    this._sViewType = sOrderBy;
	this._sOrderBy = sFieldName;
    this.getBlocks();
}

Profiles.prototype.getBlocks = function(onSuccess) {
    if ($('#pc-blocks-loading').length == 0) return;
	var $this = this;
    		
    if(onSuccess == undefined)
        onSuccess = function(){}

    $('#pc-blocks-loading').bx_loading();

    var oOptions = {
        action: 'blocks', 
        view_type: this._sViewType, 
        view_start: this._iStart, 
        view_per_page: this._iPerPage, 
        view_order: this._sOrderBy, 
        ctl_type: this._sCtlType,
		filter: this._sFilter,
		filter_params: this._sFilterParams,
		ctl_value: this._oCtlValue
    }

   $.post(
        this._sActionsUrl + 'blocks/',
        oOptions,
        function(oResult) {
			
			$('#pc-blocks-loading').bx_loading();
  		
            $('div.pc-control-panel').css('display','block');
			$('div.admin_actions_panel').css('display','block');

			
			$('#pc-blocks-common').bx_anim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
                $('#pc-blocks-common').html(oResult).bx_anim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
            });

            onSuccess();
        });
}


    
Profiles.prototype.onSubmitBlock = function(eForm, sMess, sTitle, sBody, reload){
	if ($('#aqb_block_title').val().length == 0 || $('#aqb_block_title').val().length > this._iTitleLen) 
	{	
		alert(sTitle);
		return;
	}	

	if ($('#aqb_block_body').val().length == 0 || $('#aqb_block_body').val().length > this._iBodyLen) 
	{	
		alert(sBody);
		return;
	}	
	
	if( !eForm )
        return false;
    
    $(eForm).ajaxSubmit( {
        iframe: false, // force no iframe mode
        success: function(iVal) {
			if (parseInt(iVal) == 1) 
			{	
				alert(sMess);
				if (reload.length == 0) 
				{	
					Profile.getBlocks(); 
					$('#buildBlock_' + $('#block_id').val() + ' > a').text($('#aqb_block_title').val());
					$('#aqb_popup').dolPopupHide({});
				}else window.location.href = reload;
			}	
		}
    } );
   
    return false;
}

Profiles.prototype.onSubmitStandardBlock = function(eForm, sMess){
	if( !eForm )
        return false;
    
    $(eForm).ajaxSubmit( {
        iframe: false, // force no iframe mode
        success: function(iVal) {
				alert(sMess);
				$('#aqb_popup').dolPopupHide({});
		}
    } );
   
    return false;
}

Profiles.prototype.showAll = function(sFilter){
	this._sViewType = ''; 
    this._iStart = '';
    this._iPerPage = '';
    this._sOrderBy = '' 
	this._sFilterParams = ( sFilter.length > 0 ? 'approved' : ''); 
    this._sCtlType = '';
	this._sFilter = sFilter;
	this._oCtlValue  = '';
	Profile.getBlocks();
}