/* 

Document name    : xpress.js
Created on       : 07/04/2014
Author           : Rekha Rajeev
Description      : This Document contains jquery code for the xpress webpage

*/

$(document).ready(function() { 

	/* html5 tags to support in ie8*/
    document.createElement('header');
    document.createElement('nav');
    document.createElement('section');
    document.createElement('article');
    document.createElement('aside');
    document.createElement('footer');

    /*features hover images*/

	$("#security").mouseover( function() {
		$('.xp_features_cl07').css('backgroundImage', 'url(images/xp_img_feature_1.png)');
	});
	$("#privacy").mouseover( function() {
		$('.xp_features_cl07').css('backgroundImage', 'url(images/xp_img_feature_2.png)');
	});
	$("#ephemeral").mouseover( function() {
		$('.xp_features_cl07').css('backgroundImage', 'url(images/xp_img_feature_3.png)');
	});
	$("#multi").mouseover( function() {
		$('.xp_features_cl07').css('backgroundImage', 'url(images/xp_img_feature_4.png)');
	});
	$("#group").mouseover( function() {
		$('.xp_features_cl07').css('backgroundImage', 'url(images/xp_img_feature_5.png)');
	});
	$("#web").mouseover( function() {
		$('.xp_features_cl07').css('backgroundImage', 'url(images/xp_img_feature_6.png)');
	});
	$("#theme").mouseover( function() {
		$('.xp_features_cl07').css('backgroundImage', 'url(images/xp_img_feature_7.png)');
	});
	$("#security,#privacy,#ephemeral,#multi,#group,#web,#theme").mouseleave(function(){
		$(".xp_features_cl07").css('backgroundImage', 'url(images/xp_img_feature_default.png)');
	});

	/*active class for header tabs*/
	$("nav a").click(function(){
		$("nav a").removeClass("active");
		$(this).addClass("active");
	});

	/*faq accordian*/
	$(".xp_faq_cl02").click(function () {
        if (false == $(this).next().is(':visible')) {
            $('.xp_faqsplit_cl04').slideUp("slow");                    
        }
        $(this).next().slideToggle("slow");
        $(this).toggleClass("active");
        //to enable scrolling when clicked on an item in faq
        var offset = parseInt($(this).offset().top);
        var bheight = $(window).height();
        percent = 0.5;
        var hpercent = bheight * percent;
       $('html,body').animate({ scrollTop: offset - hpercent}, 1000);
    });
	$(".xp_faq_cl04").click(function () {
        $(".xp_faqsplit_cl04").hide();
    });

	/*signintext page settings tab's accordian section*/
    $(function() {
       	$( "#accordion" ).accordion({
      		heightStyle: "content"
    	});
    });
});

/*
 *to show toast(success/failure) using akquinet-jquery-toastmessage-plugin
 *i/p parameters are
 *logout-to identify whether the toast is going to be displayed during session timeout or not
 *text-the toast message to be displayed
 *type-to determin whether to show success or error toast
 */
function showToast(logout, text, type){
    var toastMsgSettings = {
        text: text,
        sticky: false,
        position: 'middle-center',
        type: type
    };
    var toastLogoutMsgSettings = {
        text: 'Your session has timed out. Please login again.',
        sticky: false,
        position: 'middle-center',
        type: type,
        close: function () {window.location.href = 'index.php';}
    };
    if(logout == 'Y')
        $().toastmessage('showToast', toastLogoutMsgSettings);
    else
        $().toastmessage('showToast', toastMsgSettings);
}

/*to remove jquery validation prompt*/
function removeValidationPrompt(formName){
    $('.blrValidn').unbind('blur');
    $('#'+formName).validationEngine('hide');
}
