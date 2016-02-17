function searchCommissions(){
   	var sValue = $("input#search_comm_text").val(); 
	$.post(site_url+'m/affiliates/ajax/',{ action: 'search_commissions', search_comm_text: sValue }, function(oData){
     	$('.commissions_main').fadeOut('fast', function(){
			$('.commissions_main').html(oData).fadeIn('slow');
		});
    });

}
function showCreateBannerContent(sUrl) {
    var oPopupOptions = {
        fog: {color: '#fff', opacity: .7}
    };

	$('<div id="create_banner" style="display: none;"></div>').prependTo('body').load(
		site_url + sUrl,
		function() {
			$(this).dolPopup(oPopupOptions);
		}
	);
}
function createTextLink(event) {
	if (event.link_name.value.length == 0) {
		alert("Please enter Banner Name");
		event.preventDefault();
		event.stopPropagation();
	}
	if (event.link_target.value.length == 0) {
		alert("Please enter Desination URL");
		event.preventDefault();
		event.stopPropagation();
	}
	if (event.link_title.value.length == 0) {
		alert("Please enter Link Title");
		event.preventDefault();
		event.stopPropagation();
	}
}
function createImageBanner(oForm) {
	if (oForm.image_name.value.length == 0) {
		alert("Please enter Banner Name");
		return false;
	}
	if (oForm.image_target.value.length == 0) {
		alert("Please enter Desination URL");
		return false;
	}
	if (oForm.upload_image.value.length == 0) {
		alert("Please choose an image to upload");
		return false;
	}
	var sQueryString = $(oForm).formSerialize();
	$.post($(oForm).attr('action'), sQueryString, function(oData){
     	$('#create_banner').fadeOut('fast', function(){
			$('#create_banner').html(oData).fadeIn('slow');
	        setTimeout(function () {
	            $('#create_banner').dolPopupHide({});
	            window.location.href = site_url+'m/affiliates/banners';
	        }, 18000);

		});

    });
}
function showManageCampaignsContent(iAid) {
    var oPopupOptions = {
        fog: {color: '#fff', opacity: .7}
    };
	$('<div id="manage_campaigns" style="display: none;"></div>').prependTo('body').load(
		site_url + 'm/affiliates/ajax?action=manage_campaigns&aid=' + iAid,
		function() {
			$(this).dolPopup(oPopupOptions);
		}
	);
}
function showAffiliateDetailsContent(iAid) {
    var oPopupOptions = {
        fog: {color: '#fff', opacity: .7}
    };
	$('<div id="show_details" style="display: none;"></div>').prependTo('body').load(
		site_url + 'm/affiliates/ajax?action=show_affiliate_details&aid=' + iAid,
		function() {
			$(this).dolPopup(oPopupOptions);
		}
	);
}



