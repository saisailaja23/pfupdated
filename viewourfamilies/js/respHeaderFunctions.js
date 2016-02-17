$(document).ready(function() {
  var loginFunctions = function() {
    $(login).click(function() {     
      $($('.pf_popup_login_cl03')[0]).find('label').addClass('loginText');
      $($('.pf_popup_login_cl03')[0]).find('label').removeClass('loginError');
      $($('.pf_popup_login_cl03')[1]).find('label').addClass('loginText');
      $($('.pf_popup_login_cl03')[1]).find('label').removeClass('loginError');
      $('input[name="password"]').removeClass('loginError');
      $('input[name="username"]').removeClass('loginError');
      $('input[name="password"]').val('');
      $('input[name="username"]').val('');
      $('.incorrectdata').css('display', 'none');
      $($('.pf_popup_login_cl09')[1]).attr('data-toggle', '');
      $($('.pf_popup_login_cl09')[1]).attr('href', '');
      $('.pf_popup_login_cl011').attr('data-toggle', '');
      $('.pf_popup_login_cl011').attr('href', '');
      $('input[name="username"]').on('keypress', function(ev) {
	if (ev.which == 13) {
	  ev.preventDefault();
	  $('.loginSubmit').trigger('click');
	}
      });
      $('input[name="password"]').on('keypress', function(ev) {
	if (ev.which == 13) {
	  ev.preventDefault();
	  $('.loginSubmit').trigger('click');
	}
      });
    });

    $(join).click(function() {
      $('#joinList').val('0');
      $('#joinList').attr('data-toggle', '');
      $('#joinList').attr('href', '');
      $(".pfForm label").removeClass("loginError");
      $(".pfForm input").removeClass("loginError");
      $(".pfForm select").removeClass("loginError");
      $(".pfForm input[type=text]").val('');
      $(".pfForm input[type=password]").val('');
      $(".pfForm select").val('0');
      $('.loginIncorrect').css('display', 'none');
      $('.aaList').html('').append($("<option>", {value: '0', text: 'Select an Agency'}))
      $('.stateList').html('').append($("<option>", {value: '0', text: 'Select a State'}));
      $('.regionList').html('').append($("<option>", {value: '0', text: 'Select a Region'}));
      if ($('#joinList').find('option').length == 1) {
	$.getJSON(siteurl + 'MemberComponent/processors/get_data.php', function(jd) {
	  //	var json = JSON.parse(jd);
	  if (jd.aqb_pts_profile_types.rows) {
	    for (var i in jd.aqb_pts_profile_types.rows)
	      $('#joinList').append($("<option>", {value: jd.aqb_pts_profile_types.rows[i].data[0], text: jd.aqb_pts_profile_types.rows[i].data[1]}));
	    joinListAction(jd);
	  }
	});
      }
    });
    $('.forgotPwd').click(function() {
      $('.closebtnLogin').trigger('click');
      $('input[name="forgotemail"]').val('');
      $('.forgotSucc').css('display', 'none');
      $('.forgotError').css('display', 'none');
      $('.forgotForm').css('display', 'block');
      $(this).attr('data-toggle', 'modal');
      $(this).attr('href', '#forgot');
    });

    $('.join_now').click(function() {
      $('.closebtnLogin').trigger('click');
      $('input[name="email"]').val('');
      $('.forgotSucc').css('display', 'none');
      $('.forgotError').css('display', 'none');
      $('.forgotForm').css('display', 'block');
      $(join).trigger('click');
    });

    $('.loginSubmit').click(function() {
      var username = $('input[name="username"]').val();
      var password = $('input[name="password"]').val();
      if (username != '' && username != ' ' && password != '' && password != ' ') {
	$('.incorrectdata').css('display', 'none');
	loginAction(username, password);
      }
      else {
	loginValidation(username, password);
      }
    });

    $('.forgotSubmit').click(function() {
      var email = $('input[name="forgotemail"]').val();
      var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
      if (pattern.test(email)) {
	$('.forgotText').addClass('loginText');
	$('.forgotText').removeClass('loginError');
	$('input[name="forgotemail"]').removeClass('loginError');
	$.ajax({
	  url: '' + siteurl + 'LoginComponent/processors/forogot.php',
	  type: "POST",
	  cache: false,
	  data: {Email: email},
	  datatype: "json",
	  success: function(data) {
	    var jd = JSON.parse(data);
	    if (jd.email_status > 0) {
	      $('.forgotSucc').css('display', 'block');
	      $('.forgotError').css('display', 'none');
	      $('.forgotForm').css('display', 'none');
	    } else {
	      $('.forgotError').css('display', 'block');
	      $('.forgotSucc').css('display', 'none');
	    }
	  }
	});
      } else {
	$('.forgotText').removeClass('loginText');
	$('.forgotText').addClass('loginError');
	$('input[name="forgotemail"]').addClass('loginError');
      }
    });
  };

  var loginAction = function(username, password) {
    var remember = $('input[name="rememberme"]:checked').length;
    $.ajax({
      url: '' + siteurl + 'LoginComponent/processors/check_login.php',
      type: "POST",
      cache: false,
      data: {username: username, password: password, remember: remember},
      datatype: "json", success: function(data) {
	var jd = JSON.parse(data);
	$($('.pf_popup_login_cl03')[0]).find('label').addClass('loginText');
	$($('.pf_popup_login_cl03')[0]).find('label').removeClass('loginError');
	$($('.pf_popup_login_cl03')[1]).find('label').addClass('loginText');
	$($('.pf_popup_login_cl03')[1]).find('label').removeClass('loginError');
	$('input[name="password"]').removeClass('loginError');
	$('input[name="username"]').removeClass('loginError');
	if (jd.response) {
	  $('.incorrectdata').css('display', 'none');
	  window.location.href = jd.redirect;
	} else {
	  $('.incorrectdata').css('display', 'block');
	}
      }
    });
  };

  var loginValidation = function(username, password) {
    $('.incorrectdata').css('display', 'none');
    if (username == '') {
      $($('.pf_popup_login_cl03')[0]).find('label').removeClass('loginText');
      $($('.pf_popup_login_cl03')[0]).find('label').addClass('loginError');
      $('input[name="username"]').addClass('loginError');
    }
    else {
      $($('.pf_popup_login_cl03')[0]).find('label').addClass('loginText');
      $($('.pf_popup_login_cl03')[0]).find('label').removeClass('loginError');
      $('input[name="username"]').removeClass('loginError');
    }
    if (password == '') {
      $($('.pf_popup_login_cl03')[1]).find('label').removeClass('loginText');
      $($('.pf_popup_login_cl03')[1]).find('label').addClass('loginError');
      $('input[name="password"]').addClass('loginError');
    }
    else {
      $($('.pf_popup_login_cl03')[1]).find('label').addClass('loginText');
      $($('.pf_popup_login_cl03')[1]).find('label').removeClass('loginError');
      $('input[name="password"]').removeClass('loginError');
    }
  };
  var joinListAction = function(jd) {
    var formType;
    $('#joinList').off("change");
    $('#joinList').on('change', function() {
      var usernameValid = 0, emailValid = 0, email, username;
      switch ($(this).val()) {
	case '2':
	  $('#join .closebtn').trigger('click');
//	    $(this).attr('data-toggle', 'modal');
//	    $(this).attr('href', '#APJoinForm');
	  $('#APJoinFormClick').trigger('click');
	  formType = '#APJoinForm';
	  break;
	case '4':
	  $('#join .closebtn').trigger('click');
//	    $(this).attr('data-toggle', 'modal');
//	    $(this).attr('href', '#BPJoinForm');
	  $('#BPJoinFormClick').trigger('click');
	  formType = '#BPJoinForm';
	  break;
	case '8':
	  $('#join .closebtn').trigger('click');
//	    $(this).attr('data-toggle', 'modal');
//	    $(this).attr('href', '#AAJoinForm');
	  $('#AAJoinFormClick').trigger('click');
	  formType = '#AAJoinForm';
	  break;
      }
      $('.loginIncorrect').css('display', 'none');
      if (jd.sys_pre_values.rows) {
	for (var i in jd.sys_pre_values.rows)
	  $(formType + ' .aaList').append($("<option>", {value: jd.sys_pre_values.rows[i].data[0], text: jd.sys_pre_values.rows[i].data[1]}));
      }
      if (jd.profiles.rows) {
	for (var i in jd.profiles.rows)
	  $(formType + ' .stateList').append($("<option>", {value: jd.profiles.rows[i].id, text: jd.profiles.rows[i].data[0]}));
      }
      if (jd.sys_pre_values_region.rows) {
	for (var i in jd.sys_pre_values_region.rows)
	  $(formType + ' .regionList').append($("<option>", {value: jd.sys_pre_values_region.rows[i].id, text: jd.sys_pre_values_region.rows[i].data[0]}));
      }
      $('input[name="AP_marital"]').click(function() {
	if ($(this).val() == 'couple')
	  $('.secondParent').css('display', 'block');
	else
	  $('.secondParent').css('display', 'none');
      });

      $('.addagency').click(function() {
	$(formType + ' .closebtn').trigger('click');
	$('#AddAAJoinForm .addaaForm').css('display', 'block');
	$('#AddAAJoinForm .addagsuccess').css('display', 'none');
	$('#AddAAJoinForm .addagfail').css('display', 'none');
	$(this).attr('data-toggle', 'modal');
	$(this).attr('href', '#AddAAJoinForm');
	formType = '#AddAAJoinForm';
	if (jd.profiles.rows) {
	  for (var i in jd.profiles.rows)
	    $(formType + ' .stateList').append($("<option>", {value: jd.profiles.rows[i].id, text: jd.profiles.rows[i].data[0]}));
	}
	$(formType + ' .joinSubmit').click(function() {
	  console.log(formType);
	  joinFormValidations(formType);
	});
      });

      $(formType + ' .closebtn').click(function() {
	$('#joinList').attr('data-toggle', '');
	$('#joinList').attr('href', '');
      });

      $(formType + ' .blurValidate').blur(function() {
	var blurField = $(this);
	email = $($(formType + ' .blurValidate')[1]).val();
	username = $($(formType + ' .blurValidate')[0]).val();

	$.ajax({
	  url: siteurl + "MemberComponent/processors/validate_user.php",
	  type: "POST",
	  cache: false,
	  data: {email: email, username: username},
	  datatype: "json",
	  success: function(data) {
	    var jd = JSON.parse(data);
	    if (email) {
	      if (jd.email_error != '' && jd.email_error != null) {
		$(formType + ' .emailAlreadyExist').css('display', 'block');
		emailValid = 0;
	      }
	      else {
		$(formType + ' .emailAlreadyExist').css('display', 'none');
		emailValid = 1;
	      }
	    }
	    else {
	      emailValid = 0;
	    }
	    if (username) {
	      if (jd.username_error != '' && jd.username_error != null) {
		$(formType + ' .usernameAlreadyExist').css('display', 'block');
		usernameValid = 0;
	      }
	      else {
		$(formType + ' .usernameAlreadyExist').css('display', 'none');
		usernameValid = 1;
	      }
	    }
	    else {
	      usernameValid = 0;
	    }
	    if (usernameValid == 1 && emailValid == 1) {
	      $(formType + ' .joinButton').removeClass('joinDisabled');
	      $(formType + ' .joinButton').addClass('joinSubmit');
	      $(formType + ' .joinSubmit').off('click');
	      $(formType + ' .joinSubmit').on('click', function() {
		joinFormValidations(formType);
	      });
	    }
	    else {
	      $(formType + ' .joinButton').addClass('joinDisabled');
	      $(formType + ' .joinButton').removeClass('joinSubmit');
	      $(formType + ' .joinSubmit').off('click');
	      $(formType + ' .joinButton').off('click');
	    }
	  }
	});
      })

//	$('.blurValidate').blur(function() {
//	  var blurField = $(this);
//	  if ($(this).attr('id').search('email') != -1) {
//	    var email = $(this).val();
//	    $.ajax({
//	      url: siteurl + "MemberComponent/processors/validate_user.php",
//	      type: "POST",
//	      cache: false,
//	      data: {email: email, username: username},
//	      datatype: "json",
//	      success: function(data) {
//		var jd = JSON.parse(data);
//		if (jd.email_error != '' && jd.email_error != null) {
//		  $(formType + ' .emailAlreadyExist').css('display', 'block');
//		  emailValid = 0;
//		}
//		else {
//		  $(formType + ' .emailAlreadyExist').css('display', 'none');
//		  emailValid = 1;
//		}
//		if (usernameValid == 1 && emailValid == 1) {
//		  $(' joinButton').removeClass('joinDisabled');
//		  $(' joinButton').addClass('joinSubmit');
//		}
//	      }
//	    });
//	  }
//	  else {
//	    var username = $(this).val();
//	    $.ajax({
//	      url: siteurl + "MemberComponent/processors/validate_user.php",
//	      type: "POST",
//	      cache: false,
//	      data: {username: username},
//	      datatype: "json",
//	      success: function(data) {
//		var jd = JSON.parse(data);
//		if (jd.username_error != '' && jd.username_error != null) {
//		  $(formType + ' .usernameAlreadyExist').css('display', 'block');
//		  usernameValid = 0;
//		}
//		else {
//		  $(formType + ' .usernameAlreadyExist').css('display', 'none');
//		  usernameValid = 1;
//		}
//		if (usernameValid == 1 && emailValid == 1) {
//		  $(' joinButton').removeClass('joinDisabled');
//		  $(' joinButton').addClass('joinSubmit');
//		}
//	      }
//	    });
//	  }
//	});

//	$(formType + ' .joinSubmit').click(function() {
//	  console.log(formType);
//	  joinFormValidations(formType);
//	});
    });
  };
  var joinListActionSep = function(jd, profileType) {
    var formType;
    var usernameValid = 0, emailValid = 0, email, username;
    switch (profileType) {
      case '2':
//	$('#join .closebtn').trigger('click');
	$('#APJoinFormClick').trigger('click');
	formType = '#APJoinForm';
	break;
      case '4':
//	$('#join .closebtn').trigger('click');
	$('#BPJoinFormClick').trigger('click');
	formType = '#BPJoinForm';
	break;
    }
//    $('.loginIncorrect').css('display', 'none');
    if (jd.sys_pre_values.rows) {
      for (var i in jd.sys_pre_values.rows)
	$(formType + ' .aaList').append($("<option>", {value: jd.sys_pre_values.rows[i].data[0], text: jd.sys_pre_values.rows[i].data[1]}));
    }
    if (jd.profiles.rows) {
      for (var i in jd.profiles.rows)
	$(formType + ' .stateList').append($("<option>", {value: jd.profiles.rows[i].id, text: jd.profiles.rows[i].data[0]}));
    }
    if (jd.sys_pre_values_region.rows) {
      for (var i in jd.sys_pre_values_region.rows)
	$(formType + ' .regionList').append($("<option>", {value: jd.sys_pre_values_region.rows[i].id, text: jd.sys_pre_values_region.rows[i].data[0]}));
    }
    $('input[name="AP_marital"]').click(function() {
      if ($(this).val() == 'couple')
	$('.secondParent').css('display', 'block');
      else
	$('.secondParent').css('display', 'none');
    });

    $(formType + ' .blurValidate').blur(function() {
      var blurField = $(this);
      email = $($(formType + ' .blurValidate')[1]).val();
      username = $($(formType + ' .blurValidate')[0]).val();
      $.ajax({
	url: siteurl + "MemberComponent/processors/validate_user.php",
	type: "POST",
	cache: false,
	data: {email: email, username: username},
	datatype: "json",
	success: function(data) {
	  var jd = JSON.parse(data);
	  if (email) {
	    if (jd.email_error != '' && jd.email_error != null) {
	      $(formType + ' .emailAlreadyExist').css('display', 'block');
	      emailValid = 0;
	    }
	    else {
	      $(formType + ' .emailAlreadyExist').css('display', 'none');
	      emailValid = 1;
	    }
	  }
	  else {
	    emailValid = 0;
	  }
	  if (username) {
	    if (jd.username_error != '' && jd.username_error != null) {
	      $(formType + ' .usernameAlreadyExist').css('display', 'block');
	      usernameValid = 0;
	    }
	    else {
	      $(formType + ' .usernameAlreadyExist').css('display', 'none');
	      usernameValid = 1;
	    }
	  }
	  else {
	    usernameValid = 0;
	  }
	  if (usernameValid == 1 && emailValid == 1) {
	    $(formType + ' .joinButton').removeClass('joinDisabled');
	    $(formType + ' .joinButton').addClass('joinSubmit');
	    $(formType + ' .joinSubmit').off('click');
	    $(formType + ' .joinSubmit').on('click', function() {
	      joinFormValidations(formType);
	    });
	  }
	  else {
	    $(formType + ' .joinButton').addClass('joinDisabled');
	    $(formType + ' .joinButton').removeClass('joinSubmit');
	    $(formType + ' .joinSubmit').off('click');
	    $(formType + ' .joinButton').off('click');
	  }
	}
      });
    });
  };

  var joinFormValidations = function(formType) {
    switch (formType) {
      case '#APJoinForm':
	APFormValidations(formType);
	break;
      case '#BPJoinForm':
	BPFormValidations(formType);
	break;
      case '#AAJoinForm':
	AAFormValidations(formType);
	break;
      case '#AddAAJoinForm':
	AddAAFormValidations(formType);
	break;
    }
  };

  var APFormValidations = function(formType) {
    var validFlag = 1;
    if ($('input[name="APusername"]').val().trim() == '') {
      $('input[name="APusername"]').addClass('loginError');
      $('#APusernamelabel').removeClass('loginText');
      $('#APusernamelabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      var filter = /^\d*[a-zA-Z][a-zA-Z0-9-_]*$/;
      if (!filter.test($('input[name="APusername"]').val().trim())) {
	$('input[name="APusername"]').addClass('loginError');
	$('#APusernamelabel').removeClass('loginText');
	$('#APusernamelabel').addClass('loginError');
	$(formType + ' .usernameIssue').css('display', 'block');
	validFlag = 0;
      }
      else {
	$('#APusernamelabel').addClass('loginText');
	$('#APusernamelabel').removeClass('loginError');
	$('input[name="APusername"]').removeClass('loginError');
      }
//	postData.usern = $('input[name="APusername"]').val().trim();
    }
    if ($('input[name="APpassword"]').val().trim() == '') {
      $('input[name="APpassword"]').addClass('loginError');
      $('#APpasswordlabel').removeClass('loginText');
      $('#APpasswordlabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      if ($('input[name="APpassword"]').val().length < 5) {
	$('input[name="APpassword"]').addClass('loginError');
	$('#APpasswordlabel').removeClass('loginText');
	$('#APpasswordlabel').addClass('loginError');
	$(formType + ' .pwdIssue').css('display', 'block');
	validFlag = 0;
      }
      else {
	$('#APpasswordlabel').addClass('loginText');
	$('#APpasswordlabel').removeClass('loginError');
	$('input[name="APpassword"]').removeClass('loginError');
      }
    }
    if ($('input[name="APConfPassword"]').val().trim() == '') {
      $('input[name="APConfPassword"]').addClass('loginError');
      $('#APConfPasswordlabel').removeClass('loginText');
      $('#APConfPasswordlabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      $('#APConfPasswordlabel').addClass('loginText');
      $('#APConfPasswordlabel').removeClass('loginError');
      $('input[name="APConfPassword"]').removeClass('loginError');
      if ($('input[name="APpassword"]').val().trim() !== $('input[name="APConfPassword"]').val().trim()){
	$('#APJoinForm .pwdsmismatch').css('display', 'block');validFlag = 0;
	  }else {
	$('#APJoinForm .pwdsmismatch').css('display', 'none');
//	  postData.passw = $('input[name="APConfPassword"]').val().trim();
      }
    }
    var email = $('input[name="APemail"]').val().trim();
    var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
    if ($('input[name="APemail"]').val().trim() == '') {
      $('input[name="APemail"]').addClass('loginError');
      $('#APemaillabel').removeClass('loginText');
      $('#APemaillabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      if (pattern.test(email)) {
	$('#APemaillabel').addClass('loginText');
	$('#APemaillabel').removeClass('loginError');
	$('input[name="APemail"]').removeClass('loginError');
//	  postData.emailid = $('input[name="APemail"]').val().trim();
      }
      else {
	$('input[name="APemail"]').addClass('loginError');
	$('#APemaillabel').removeClass('loginText');
	$('#APemaillabel').addClass('loginError');
	validFlag = 0;
      }
    }

    if ($('input[name="AP_p1_FN"]').val().trim() == '') {
      $('input[name="AP_p1_FN"]').addClass('loginError');
      $('#AP_p1_FNlabel').removeClass('loginText');
      $('#AP_p1_FNlabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      var filter_name = /^[a-zA-Z]*[a-zA-Z]+[a-zA-Z ]*$/;
      if (!filter_name.test($('input[name="AP_p1_FN"]').val().trim())) {
	$('input[name="AP_p1_FN"]').addClass('loginError');
	$('#AP_p1_FNlabel').removeClass('loginText');
	$('#AP_p1_FNlabel').addClass('loginError');
	$(formType + ' .nameIssue').css('display', 'block');
	validFlag = 0;
      }
      else {
	$('#AP_p1_FNlabel').addClass('loginText');
	$('#AP_p1_FNlabel').removeClass('loginError');
	$('input[name="AP_p1_FN"]').removeClass('loginError');
      }
//	postData.firstname = $('input[name="AP_p1_FN"]').val().trim();
    }
    if ($('input[name="AP_p1_LN"]').val().trim() == '') {
      $('input[name="AP_p1_LN"]').addClass('loginError');
      $('#AP_p1_LNlabel').removeClass('loginText');
      $('#AP_p1_LNlabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      var filter_name = /^[a-zA-Z]*[a-zA-Z]+[a-zA-Z ]*$/;
      if (!filter_name.test($('input[name="AP_p1_LN"]').val().trim())) {
	$('input[name="AP_p1_LN"]').addClass('loginError');
	$('#AP_p1_LNlabel').removeClass('loginText');
	$('#AP_p1_LNlabel').addClass('loginError');
	$(formType + ' .nameIssue').css('display', 'block');
	validFlag = 0;
      }
      else {
	$('#AP_p1_LNlabel').addClass('loginText');
	$('#AP_p1_LNlabel').removeClass('loginError');
	$('input[name="AP_p1_LN"]').removeClass('loginError');
      }
//	postData.lastname = $('input[name="AP_p1_LN"]').val().trim();
    }

    if ($('input[name="AP_marital"]:checked').val() == 'couple') {
      if ($('input[name="AP_p2_FN"]').val().trim() == '') {
	$('input[name="AP_p2_FN"]').addClass('loginError');
	$('#AP_p2_FNlabel').removeClass('loginText');
	$('#AP_p2_FNlabel').addClass('loginError');
	validFlag = 0;
      }
      else {
	var filter_name = /^[a-zA-Z]*[a-zA-Z]+[a-zA-Z ]*$/;
	if (!filter_name.test($('input[name="AP_p2_FN"]').val().trim())) {
	  $('input[name="AP_p2_FN"]').addClass('loginError');
	  $('#AP_p2_FNlabel').removeClass('loginText');
	  $('#AP_p2_FNlabel').addClass('loginError');
	  $(formType + ' .nameIssue').css('display', 'block');
	  validFlag = 0;
	}
	else {
	  $('#AP_p2_FNlabel').addClass('loginText');
	  $('#AP_p2_FNlabel').removeClass('loginError');
	  $('input[name="AP_p2_FN"]').removeClass('loginError');
	}
//	  postData.couplfname = $('input[name="AP_p2_FN"]').val().trim();
      }
      if ($('input[name="AP_p2_LN"]').val().trim() == '') {
	$('input[name="AP_p2_LN"]').addClass('loginError');
	$('#AP_p2_LNlabel').removeClass('loginText');
	$('#AP_p2_LNlabel').addClass('loginError');
	validFlag = 0;
      }
      else {
	var filter_name = /^[a-zA-Z]*[a-zA-Z]+[a-zA-Z ]*$/;
	if (!filter_name.test($('input[name="AP_p2_LN"]').val().trim())) {
	  $('input[name="AP_p2_LN"]').addClass('loginError');
	  $('#AP_p2_LNlabel').removeClass('loginText');
	  $('#AP_p2_LNlabel').addClass('loginError');
	  $(formType + ' .nameIssue').css('display', 'block');
	  validFlag = 0;
	}
	else {
	  $('#AP_p2_LNlabel').addClass('loginText');
	  $('#AP_p2_LNlabel').removeClass('loginError');
	  $('input[name="AP_p2_LN"]').removeClass('loginError');
	}
//	  postData.couplelname = $('input[name="AP_p2_LN"]').val().trim();
      }
    }
    if ($('#APJoinForm .aaList').val().trim() == '0') {
      $('#APJoinForm .aaList').addClass('loginError');
      $('#APJoinForm #aaListlabel').removeClass('loginText');
      $('#APJoinForm #aaListlabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      $('#APJoinForm #aaListlabel').addClass('loginText');
      $('#APJoinForm #aaListlabel').removeClass('loginError');
      $('#APJoinForm .aaList').removeClass('loginError');
//	postData.useragency = $('#APJoinForm .aaList').val().trim();
    }
    if ($('#APJoinForm .stateList').val().trim() == '0') {
      $('#APJoinForm .stateList').addClass('loginError');
      $('#APJoinForm #stateListlabel').removeClass('loginText');
      $('#APJoinForm #stateListlabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      $('#APJoinForm #stateListlabel').addClass('loginText');
      $('#APJoinForm #stateListlabel').removeClass('loginError');
      $('#APJoinForm .stateList').removeClass('loginError');
//	postData.userstate = $('#APJoinForm .stateList').val().trim();
    }
    if ($('#APJoinForm .regionList').val().trim() == '0') {
      $('#APJoinForm .regionList').addClass('loginError');
      $('#APJoinForm #regionListlabel').removeClass('loginText');
      $('#APJoinForm #regionListlabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      $('#APJoinForm #regionListlabel').addClass('loginText');
      $('#APJoinForm #regionListlabel').removeClass('loginError');
      $('#APJoinForm .regionList').removeClass('loginError');
//	postData.userregion = $('#APJoinForm .regionList').val().trim();
    }

    if (validFlag == 1) {
      if ($('input[name="ap_terms"]:checked').length == '0') {
	$('.agreement').css('display', 'block');
	validFlag = 0;
      }
      else {
	$('.agreement').css('display', 'none');
	var postData = {
	  usern: $('input[name="APusername"]').val().trim(),
	  passw: $('input[name="APConfPassword"]').val().trim(),
	  emailid: $('input[name="APemail"]').val().trim(),
	  firstname: $('input[name="AP_p1_FN"]').val().trim(),
	  lastname: $('input[name="AP_p1_LN"]').val().trim(),
	  couplfname: $('input[name="AP_p2_FN"]').val().trim(),
	  couplelname: $('input[name="AP_p2_LN"]').val().trim(),
	  useragency: $('#APJoinForm .aaList').val().trim(),
	  userstate: $('#APJoinForm .stateList').val().trim(),
	  userregion: $('#APJoinForm .regionList').val().trim(),
	  ptypec: $('input[name=AP_marital]:checked').val(),
	  gendermale: $('input[name=AP_p1_gender]:checked').val(),
	  cgenedermale: $('input[name=AP_p2_gender]:checked').val(),
	  Profiletype: $('#joinList').val()
	};
//	  postData.ptypec = $('input[name=AP_marital]:checked').val();
//	  postData.gendermale = $('input[name=AP_p1_gender]:checked').val();
//	  postData.cgenedermale = $('input[name=AP_p2_gender]:checked').val();
//	  postData.Profiletype = $('#joinList').val();
	joinSaveAction(postData, formType);
      }
    }
  };

  var BPFormValidations = function(formType) {
    var validFlag = 1;
    if ($('input[name="BPusername"]').val().trim() == '') {
      $('input[name="BPusername"]').addClass('loginError');
      $('#BPusernamelabel').removeClass('loginText');
      $('#BPusernamelabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      var filter = /^\d*[a-zA-Z][a-zA-Z0-9-_]*$/;
      if (!filter.test($('input[name="BPusername"]').val().trim())) {
	$('input[name="BPusername"]').addClass('loginError');
	$('#BPusernamelabel').removeClass('loginText');
	$('#BPusernamelabel').addClass('loginError');
	$(formType + ' .usernameIssue').css('display', 'block');
	validFlag = 0;
      }
      else {
	$('#BPusernamelabel').addClass('loginText');
	$('#BPusernamelabel').removeClass('loginError');
	$('input[name="BPusername"]').removeClass('loginError');
      }
    }
    if ($('input[name="BPpassword"]').val().trim() == '') {
      $('input[name="BPpassword"]').addClass('loginError');
      $('#BPpasswordlabel').removeClass('loginText');
      $('#BPpasswordlabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      if ($('input[name="BPpassword"]').val().length < 5) {
	$('input[name="BPpassword"]').addClass('loginError');
	$('#BPpasswordlabel').removeClass('loginText');
	$('#BPpasswordlabel').addClass('loginError');
	$(formType + ' .pwdIssue').css('display', 'block');
	validFlag = 0;
      }
      else {
	$('#BPpasswordlabel').addClass('loginText');
	$('#BPpasswordlabel').removeClass('loginError');
	$('input[name="BPpassword"]').removeClass('loginError');
      }
    }
    if ($('input[name="BPConfPassword"]').val().trim() == '') {
      $('input[name="BPConfPassword"]').addClass('loginError');
      $('#BPConfPasswordlabel').removeClass('loginText');
      $('#BPConfPasswordlabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      $('#BPConfPasswordlabel').addClass('loginText');
      $('#BPConfPasswordlabel').removeClass('loginError');
      $('input[name="BPConfPassword"]').removeClass('loginError');
      if ($('input[name="BPpassword"]').val().trim() !== $('input[name="BPConfPassword"]').val().trim()){
	$('#BPJoinForm .pwdsmismatch').css('display', 'block');validFlag = 0;
	  }else{
	$('#BPJoinForm .pwdsmismatch').css('display', 'none');
	  }
    }
    var email = $('input[name="BPemail"]').val().trim();
    var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
    if ($('input[name="BPemail"]').val().trim() == '') {
      $('input[name="BPemail"]').addClass('loginError');
      $('#BPemaillabel').removeClass('loginText');
      $('#BPemaillabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      if (pattern.test(email)) {
	$('#BPemaillabel').addClass('loginText');
	$('#BPemaillabel').removeClass('loginError');
	$('input[name="BPemail"]').removeClass('loginError');
      }
      else {
	$('input[name="BPemail"]').addClass('loginError');
	$('#BPemaillabel').removeClass('loginText');
	$('#BPemaillabel').addClass('loginError');
	validFlag = 0;
      }
    }

    if ($('input[name="BP_p1_FN"]').val().trim() == '') {
      $('input[name="BP_p1_FN"]').addClass('loginError');
      $('#BP_p1_FNlabel').removeClass('loginText');
      $('#BP_p1_FNlabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      var filter_name = /^[a-zA-Z]*[a-zA-Z]+[a-zA-Z ]*$/;
      if (!filter_name.test($('input[name="BP_p1_FN"]').val().trim())) {
	$('input[name="BP_p1_FN"]').addClass('loginError');
	$('#BP_p1_FNlabel').removeClass('loginText');
	$('#BP_p1_FNlabel').addClass('loginError');
	$(formType + ' .nameIssue').css('display', 'block');
	validFlag = 0;
      }
      else {
	$('#BP_p1_FNlabel').addClass('loginText');
	$('#BP_p1_FNlabel').removeClass('loginError');
	$('input[name="BP_p1_FN"]').removeClass('loginError');
      }
    }
    if ($('#BPJoinForm .aaList').val().trim() == '0') {
      $('#BPJoinForm .aaList').addClass('loginError');
      $('#BPJoinForm #aaListlabel').removeClass('loginText');
      $('#BPJoinForm #aaListlabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      $('#BPJoinForm #aaListlabel').addClass('loginText');
      $('#BPJoinForm #aaListlabel').removeClass('loginError');
      $('#BPJoinForm .aaList').removeClass('loginError');
    }
    if ($('#BPJoinForm .stateList').val().trim() == '0') {
      $('#BPJoinForm .stateList').addClass('loginError');
      $('#BPJoinForm #stateListlabel').removeClass('loginText');
      $('#BPJoinForm #stateListlabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      $('#BPJoinForm #stateListlabel').addClass('loginText');
      $('#BPJoinForm #stateListlabel').removeClass('loginError');
      $('#BPJoinForm .stateList').removeClass('loginError');
    }
    if ($('#BPJoinForm .regionList').val().trim() == '0') {
      $('#BPJoinForm .regionList').addClass('loginError');
      $('#BPJoinForm #regionListlabel').removeClass('loginText');
      $('#BPJoinForm #regionListlabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      $('#BPJoinForm #regionListlabel').addClass('loginText');
      $('#BPJoinForm #regionListlabel').removeClass('loginError');
      $('#BPJoinForm .regionList').removeClass('loginError');
    }

    if (validFlag == 1) {
      if ($('input[name="bp_terms"]:checked').length == '0') {
	$('.agreement').css('display', 'block');
	validFlag = 0;
      }
      else {
	$('.agreement').css('display', 'none');
	var postData = {
	  usern: $('input[name="BPusername"]').val().trim(),
	  passw: $('input[name="BPConfPassword"]').val().trim(),
	  emailid: $('input[name="BPemail"]').val().trim(),
	  firstname: $('input[name="BP_p1_FN"]').val().trim(),
	  useragency: $('#BPJoinForm .aaList').val().trim(),
	  userstate: $('#BPJoinForm .stateList').val().trim(),
	  userregion: $('#BPJoinForm .regionList').val().trim(),
	  Profiletype: $('#joinList').val(),
	  ptypec: 'single',
	  gendermale: 'male'
	};
	joinSaveAction(postData, formType);
      }
    }
  };

  var AAFormValidations = function(formType) {
    var validFlag = 1;
    if ($('input[name="AAusername"]').val().trim() == '') {
      $('input[name="AAusername"]').addClass('loginError');
      $('#AAusernamelabel').removeClass('loginText');
      $('#AAusernamelabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      var filter = /^\d*[a-zA-Z][a-zA-Z0-9-_]*$/;
      if (!filter.test($('input[name="AAusername"]').val().trim())) {
	$('input[name="AAusername"]').addClass('loginError');
	$('#AAusernamelabel').removeClass('loginText');
	$('#AAusernamelabel').addClass('loginError');
	$(formType + ' .usernameIssue').css('display', 'block');
	validFlag = 0;
      }
      else {
	$('#AAusernamelabel').addClass('loginText');
	$('#AAusernamelabel').removeClass('loginError');
	$('input[name="AAusername"]').removeClass('loginError');
      }
    }
    if ($('input[name="AApassword"]').val().trim() == '') {
      $('input[name="AApassword"]').addClass('loginError');
      $('#AApasswordlabel').removeClass('loginText');
      $('#AApasswordlabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      if ($('input[name="AApassword"]').val().length < 5) {
	$('input[name="AApassword"]').addClass('loginError');
	$('#AApasswordlabel').removeClass('loginText');
	$('#AApasswordlabel').addClass('loginError');
	$(formType + ' .pwdIssue').css('display', 'block');
	validFlag = 0;
      }
      else {
	$('#AApasswordlabel').addClass('loginText');
	$('#AApasswordlabel').removeClass('loginError');
	$('input[name="AApassword"]').removeClass('loginError');
      }
    }
    if ($('input[name="AAConfPassword"]').val().trim() == '') {
      $('input[name="AAConfPassword"]').addClass('loginError');
      $('#AAConfPasswordlabel').removeClass('loginText');
      $('#AAConfPasswordlabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      $('#AAConfPasswordlabel').addClass('loginText');
      $('#AAConfPasswordlabel').removeClass('loginError');
      $('input[name="AAConfPassword"]').removeClass('loginError');
      if ($('input[name="AApassword"]').val().trim() !== $('input[name="AAConfPassword"]').val().trim()){
	$('#AAJoinForm .pwdsmismatch').css('display', 'block');validFlag = 0;
	  }else{
	$('#AAJoinForm .pwdsmismatch').css('display', 'none');
	  }
    }
    var email = $('input[name="AAemail"]').val().trim();
    var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
    if ($('input[name="AAemail"]').val().trim() == '') {
      $('input[name="AAemail"]').addClass('loginError');
      $('#AAemaillabel').removeClass('loginText');
      $('#AAemaillabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      if (pattern.test(email)) {
	$('#AAemaillabel').addClass('loginText');
	$('#AAemaillabel').removeClass('loginError');
	$('input[name="AAemail"]').removeClass('loginError');
      }
      else {
	$('input[name="AAemail"]').addClass('loginError');
	$('#AAemaillabel').removeClass('loginText');
	$('#AAemaillabel').addClass('loginError');
	validFlag = 0;
      }
    }

    if ($('#AAJoinForm #AAName').val().trim() == '') {
      $('#AAJoinForm #AAName').addClass('loginError');
      $('#AAJoinForm #aaListlabel').removeClass('loginText');
      $('#AAJoinForm #aaListlabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      //var filter_name = /^[a-zA-Z]*[a-zA-Z]+[a-zA-Z ]*$/;
      var filter_name = /^[a-zA-Z&_-]*[a-zA-Z]+[a-zA-Z ]*$/;
      if (!filter_name.test($('input[name="AAName"]').val().trim())) {
	$('#AAJoinForm #AAName').addClass('loginError');
	$('#AAJoinForm #aaListlabel').removeClass('loginText');
	$('#AAJoinForm #aaListlabel').addClass('loginError');
	$(formType + '.nameIssue').css('display', 'block');
	validFlag = 0;
      }
      else {
	$('#AAJoinForm #aaListlabel').addClass('loginText');
	$('#AAJoinForm #aaListlabel').removeClass('loginError');
	$('#AAJoinForm #AAName').removeClass('loginError');
      }
    }
    if ($('#AAJoinForm .stateList').val().trim() == '0') {
      $('#AAJoinForm .stateList').addClass('loginError');
      $('#AAJoinForm #stateListlabel').removeClass('loginText');
      $('#AAJoinForm #stateListlabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      $('#AAJoinForm #stateListlabel').addClass('loginText');
      $('#AAJoinForm #stateListlabel').removeClass('loginError');
      $('#AAJoinForm .stateList').removeClass('loginError');
    }
    if ($('#AAJoinForm .regionList').val().trim() == '0') {
      $('#AAJoinForm .regionList').addClass('loginError');
      $('#AAJoinForm #regionListlabel').removeClass('loginText');
      $('#AAJoinForm #regionListlabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      $('#AAJoinForm #regionListlabel').addClass('loginText');
      $('#AAJoinForm #regionListlabel').removeClass('loginError');
      $('#AAJoinForm .regionList').removeClass('loginError');
    }

    if (validFlag == 1) {
      if ($('input[name="aa_terms"]:checked').length == '0') {
	$('.agreement').css('display', 'block');
	validFlag = 0;
      }
      else {
	$('.agreement').css('display', 'none');
	var postData = {
	  usern: $('input[name="AAusername"]').val().trim(),
	  passw: $('input[name="AAConfPassword"]').val().trim(),
	  emailid: $('input[name="AAemail"]').val().trim(),
	  newagency: $('input[name="AAName"]').val().trim(),
	  userstate: $('#AAJoinForm .stateList').val().trim(),
	  userregion: $('#AAJoinForm .regionList').val().trim(),
	  Profiletype: $('#joinList').val(),
	  ptypec: 'single',
	  gendermale: 'male'
	};
	joinSaveAction(postData, formType);
      }
    }
  };

  var AddAAFormValidations = function() {
    var validFlag = 1;
    if ($('input[name="AddAAusername"]').val().trim() == '') {
      $('input[name="AddAAusername"]').addClass('loginError');
      $('#AddAAusernamelabel').removeClass('loginText');
      $('#AddAAusernamelabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      $('#AddAAusernamelabel').addClass('loginText');
      $('#AddAAusernamelabel').removeClass('loginError');
      $('input[name="AddAAusername"]').removeClass('loginError');
    }

    var email = $('input[name="AddAAemail"]').val().trim();
    var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
    if ($('input[name="AddAAemail"]').val().trim() == '') {
      $('input[name="AddAAemail"]').addClass('loginError');
      $('#AddAAemaillabel').removeClass('loginText');
      $('#AddAAemaillabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      if (pattern.test(email)) {
	$('#AddAAemaillabel').addClass('loginText');
	$('#AddAAemaillabel').removeClass('loginError');
	$('input[name="AddAAemail"]').removeClass('loginError');
      }
      else {
	$('input[name="AddAAemail"]').addClass('loginError');
	$('#AddAAemaillabel').removeClass('loginText');
	$('#AddAAemaillabel').addClass('loginError');
	validFlag = 0;
      }
    }

    var email = $('input[name="Add_AAEmail"]').val().trim();
    var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
    if ($('input[name="Add_AAEmail"]').val().trim() == '') {
      $('input[name="Add_AAEmail"]').addClass('loginError');
      $('#Add_AAEmaillabel').removeClass('loginText');
      $('#Add_AAEmaillabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      if (pattern.test(email)) {
	$('#Add_AAEmaillabel').addClass('loginText');
	$('#Add_AAEmaillabel').removeClass('loginError');
	$('input[name="Add_AAEmail"]').removeClass('loginError');
      }
      else {
	$('input[name="Add_AAEmail"]').addClass('loginError');
	$('#Add_AAEmaillabel').removeClass('loginText');
	$('#Add_AAEmaillabel').addClass('loginError');
	validFlag = 0;
      }
    }

    if ($('#AddAAJoinForm #Add_AAName').val().trim() == '') {
      $('#AddAAJoinForm #Add_AAName').addClass('loginError');
      $('#AddAAJoinForm #Add_AANamelabel').removeClass('loginText');
      $('#AddAAJoinForm #Add_AANamelabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      $('#AddAAJoinForm #Add_AANamelabel').addClass('loginText');
      $('#AddAAJoinForm #Add_AANamelabel').removeClass('loginError');
      $('#AddAAJoinForm #Add_AAName').removeClass('loginError');
    }
    if ($('#AddAAJoinForm .stateList').val().trim() == '0') {
      $('#AddAAJoinForm .stateList').addClass('loginError');
      $('#AddAAJoinForm #stateListlabel').removeClass('loginText');
      $('#AddAAJoinForm #stateListlabel').addClass('loginError');
      validFlag = 0;
    }
    else {
      $('#AddAAJoinForm #stateListlabel').addClass('loginText');
      $('#AddAAJoinForm #stateListlabel').removeClass('loginError');
      $('#AddAAJoinForm .stateList').removeClass('loginError');
    }

    if (validFlag == 1) {
      var postData = {
	agency_name: $('input[name="Add_AAName"]').val().trim(),
	agency_email: $('input[name="Add_AAEmail"]').val().trim(),
	from_email: $('input[name="AddAAemail"]').val().trim(),
	from_name: $('input[name="AddAAusername"]').val().trim(),
	agency_state: $('#AddAAJoinForm .stateList').val().trim()
      };
      requestNeAAwAction(postData);
    }
  };

  var joinSaveAction = function(postData, formType) {
    $.ajax({
      url: siteurl + 'MemberComponent/processors/insert_data.php',
      type: "POST",
      cache: false,
      data: postData,
      datatype: "json",
      success: function(data) {
	var returnval = JSON.parse(data);
	var err = '', count = 0;
	if (returnval.username_error != '' && returnval.username_error != null) {
	  err = 'Username';
	  count++;
	}
	if (returnval.email_error != '' && returnval.email_error != null) {
	  if (err)
	    err += ', ';
	  err += 'Email';
	  count++;
	}
	if (returnval.agency_error != '' && returnval.agency_error != null) {
	  if (err)
	    err += ', ';
	  err += 'Agency Name';
	  count++;
	}
	if (err) {
	  if (count > 1)
	    err += ' are already in use';
	  else
	    err += ' is already in use';
	  $(formType + ' .alreadyExist').css('display', 'block');
	  $(formType + ' .alreadyExist').html(err);
	}
	else {
	  if (postData.Profiletype == 2) {
	    $(formType + ' .alreadyExist').css('display', 'none');
	    $(formType + ' .pfForm').css('display', 'none');
	    $(formType + ' .Joinsuccess').css('display', 'block');
	    window.location.href = siteurl + "extra_member.php";
	  }
	  else if (postData.Profiletype == 4) {
	    window.location.href = siteurl + "extra_profile_view_20.php";
	  }
	  else if (postData.Profiletype == 8) {
	    $(formType + ' .alreadyExist').css('display', 'none');
	    $(formType + ' .pfForm').css('display', 'none');
	    $(formType + ' .Joinsuccess').css('display', 'block');
	  }
	}
      }
    });
  };

  var requestNeAAwAction = function(postData) {
    $.ajax({
      url: siteurl + 'NewAgencyComponent/processors/requesttoAgency.php',
      type: "POST",
      cache: false,
      data: postData,
      datatype: "json", success: function(data) {
	var returnval = JSON.parse(data);
	if (returnval.status == 'success') {
	  $('#AddAAJoinForm .addaaForm').css('display', 'none');
	  $('#AddAAJoinForm .addagsuccess').css('display', 'block');
	}
	else {
	  $('#AddAAJoinForm .addaaForm').css('display', 'none');
	  $('#AddAAJoinForm .addagfail').css('display', 'block');
	}
      }
    });
  };

  var join, login;
  if ($('.topMenuJoinBlock').html() != undefined) {
    switch ($('.topMenuJoinBlock').html()) {
      case 'Log Out':
	break;
      default:
	join = $('.topMenuJoinBlock')[0];
	login = $('.topMenuJoinBlock')[1];
	$(login).attr('onclick', '');
	$(join).attr('onclick', '');
	$(login).attr('data-toggle', 'modal');
	$(login).attr('href', '#login');
	$(join).attr('data-toggle', 'modal');
	$(join).attr('href', '#join');
	loginFunctions();
	break;
    }
  }
  else {
    join = $('.splJoin');
    login = $('.splLogin');
    $('.splLogin').attr('onclick', '');
    $('.splJoin').attr('onclick', '');
    $('.splLogin').attr('data-toggle', 'modal');
    $('.splLogin').attr('href', '#login');
    $('.splJoin').attr('data-toggle', 'modal');
    $('.splJoin').attr('href', '#join');
    $('.splJoinResp').click(function() {
      $(join).trigger('click');
    })
    loginFunctions();
  }

  $('.join_now_click').click(function() {
    join = this;
//    $(join).attr('onclick', '');
//    $(join).attr('data-toggle', 'modal');
//    $(join).attr('href', '#join');
    $.getJSON(siteurl + 'MemberComponent/processors/get_data.php', function(jd) {
      if (jd.aqb_pts_profile_types.rows) {
	for (var i in jd.aqb_pts_profile_types.rows)
	  $('#joinList').append($("<option>", {value: jd.aqb_pts_profile_types.rows[i].data[0], text: jd.aqb_pts_profile_types.rows[i].data[1]}));
	joinListActionSep(jd, '2');
	$('#joinList').val('2');
	$('#joinList').trigger('change');
      }
    });
  });

  $('#SIGN').click(function() {
    join = this;
//    $(join).attr('onclick', '');
//    $(join).attr('data-toggle', 'modal');
//    $(join).attr('href', '#join');
    $.getJSON(siteurl + 'MemberComponent/processors/get_data.php', function(jd) {
      if (jd.aqb_pts_profile_types.rows) {
	for (var i in jd.aqb_pts_profile_types.rows)
	  $('#joinList').append($("<option>", {value: jd.aqb_pts_profile_types.rows[i].data[0], text: jd.aqb_pts_profile_types.rows[i].data[1]}));
	joinListActionSep(jd, '4');
//	$('#joinList').val('4');
//	$('#joinList').trigger('change');
      }
    });
  });
});