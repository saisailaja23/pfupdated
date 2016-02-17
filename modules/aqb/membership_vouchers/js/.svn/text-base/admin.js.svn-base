function AqbMembershipVouchers(oOptions) {
	this._sBaseURL = oOptions.sBaseURL;
}
AqbMembershipVouchers.prototype.showEditForm = function(iCodeID, iPriceID) {
	var $this = this;
	if ($('#aqb_vouchers_form').length) $('#aqb_vouchers_form').remove();
    $('<div id="aqb_vouchers_form" style="display:none;"></div>').prependTo('body');
    $('#aqb_vouchers_form').html('<h1>Loading...</h1>');
	$('#aqb_vouchers_form').dolPopup({fog: {color: '#444', opacity: .7}, closeOnOuterClick: false});
	var oDate = new Date();
	$.get(this._sBaseURL + 'action_edit/' + iCodeID, {time:oDate.getTime(), price_id:iPriceID}, function(sResponse) {
		$('#aqb_vouchers_form').html(sResponse);
		$('#aqb_vouchers_form div .dbClose a').click(function() {
			$('#aqb_vouchers_form').dolPopupHide();
		});
		$this._initializeDatepickers();
		$('#aqb_vouchers_form').setToCenter();
	}, 'html');
}
AqbMembershipVouchers.prototype.submitEditForm = function(oForm) {
	var $this = this;
	$('#formItemEditLoading').bx_loading();
	var sQueryString = $(oForm).formSerialize();
	$.post(this._sBaseURL + 'action_edit/', sQueryString, function(sData){
        $('#aqb_vouchers_form').html(sData);
        $('#aqb_vouchers_form div .dbClose a').click(function() {
			$('#aqb_vouchers_form').dolPopupHide();
		});
		$this._initializeDatepickers();
		$('#aqb_vouchers_form').setToCenter();
    }, 'html');
    return false;
}
AqbMembershipVouchers.prototype.deleteCode = function(iCode) {
	if (confirm(_t('_aqb_membership_sure'))) {
		var oDate = new Date();
		$.post(this._sBaseURL + 'action_delete/' + iCode, {time:oDate.getTime()}, function(sData){
			window.location.reload();
		});
	}
}
AqbMembershipVouchers.prototype._initializeDatepickers = function() {
	$('input[type=date]').each(function(i, el){
		$(el).datepicker({
    		changeYear: true,
    		dateFormat: 'yy-mm-dd',
    		defaultDate: 0,
    		yearRange: '2011:2020'
		});
	});
}