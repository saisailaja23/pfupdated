function showMenuAccess(sUrl) {
    var oPopupOptions = {
        fog: {color: '#fff', opacity: .7}
    };

	$('<div id="menu_access" style="display: none;"></div>').prependTo('body').load(
		site_url + sUrl,
		function() {
			$(this).dolPopup(oPopupOptions);
		}
	);
}
function saveMenuItem(oForm) {
	$('#formItemEditLoading').bx_loading();
	var sQueryString = $(oForm).formSerialize();
	$.post($(oForm).attr('action'), sQueryString, function(oData){
        $('#formItemEditLoading').bx_loading();
     	$('#menu_access').fadeOut('fast', function(){
			$('#menu_access').html(oData).fadeIn('slow');
	        setTimeout(function () {
	            $('#menu_access').dolPopupHide({});
	        }, 1000);

		});

    });
}
function loadPopUp(sUrl) {
    var oPopupOptions = {
        fog: {color: '#fff', opacity: .7}
    };
	$('#menu_access').load(
		site_url + sUrl,
		function() {
			$(this).dolPopup(oPopupOptions);
	        setTimeout(function () {
	            $('#menu_access').dolPopupHide({});
	        }, 3000);

		}
	);
}
function showOrderForm(sUrl) {
    var oPopupOptions = {
        fog: {color: '#000', opacity: .7}
    };
	$('<div id="order_form" style="display: none;"></div>').prependTo('body').load(
		site_url + sUrl,
		function() {
			$(this).dolPopup(oPopupOptions);
		}
	);
}
