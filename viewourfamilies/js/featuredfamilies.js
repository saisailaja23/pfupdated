$(document).ready(function() {

  /**
   * Author : Satya
   * Description : 
   * @type @exp;document@pro;referrer
   */
  var url = document.referrer;
  var hostname = $('<a>').prop('href', url).prop('hostname');
  $('.container').attr('style', 'margin-top: 10px !important');
  $('#vp').attr('style', 'overflow-x: hidden !important');
  var arrayprofileid = [];
  if (hostname === "kak-iin.firmsitepreview.com") {
    $('html').attr('style', 'background-color: #efefef !important');
    $('.container ').attr('style', 'background-color: #efefef !important');
  }

  var famDiv = $('#1'), famDivLatest, loadedRecords = 0, totalRecords = 0, famDivHTML = famDiv.html();

  var getUrlVars = function() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) {
      vars[key] = value;
    });
    return vars;
  };

  var agencyId = getUrlVars().agencyId;
  var loadFrom = getUrlVars().loadFrom;
  var sortvalue = getUrlVars().sortvalue;
  var type = getUrlVars().type;

  $('#sortyby').val(sortvalue);
  $('#type').val(type);

  $.getJSON(siteurl + 'viewourfamilies/processors/featuredfamilieslist.php?loadFrom=' + loadFrom + '&agencyId=' + agencyId + '&sortvalue=' + sortvalue + '&type=' + type, function(jd) {
    if (jd.count > 0) {
      arrayprofileid = [];
      createFamilyDiv(jd);
      $('.norecords').css('display', 'none');
    }
    else {
      $('.row').css('display', 'block');
      $('.loader').css('display', 'none');
      $('.pf_view_cl02').css('display', 'none');
      $('.loadmore_wrapper').css('display', 'none');
      $('.norecords').css('display', 'block');
    }
  });

  $.getJSON(siteurl + 'viewourfamilies/processors/profile_view_information.php?loadFrom=' + loadFrom + '&agencyId=' + agencyId + '&sortvalue=' + sortvalue + '&type=' + type, function(jd) {
    if (jd.state_value) {
      for (var i in jd.state_value) {
	var liSpan = $('<span></span>');
	liSpan.html(jd.state_value[i].id);
	liSpan.attr('data-val', 'State');
	liSpan.attr('data-option', jd.state_value[i].id);
	$('.State ul').append($('<li></li>').html(liSpan));
      }
    }
    else {
      $('.State').css('display', 'none');
    }
    if (jd.children_value) {
      for (var i in jd.children_value) {
	var liSpan1 = $('<span></span>');
	liSpan1.html(jd.children_value[i].data[0]);
	liSpan1.attr('data-val', 'Familysize');
	liSpan1.attr('data-option', jd.children_value[i].id);
	$('.Familysize ul').append($('<li></li>').html(liSpan1));
      }
    }
    else {
      $('.Familysize').css('display', 'none');
    }
    var t = $('.Sortby').find('ul').html();
    $('.selectedSub').html('Please Select');
    $('.pf_filter_cl02 .pf_content_menu ul').html(t);
    filterClick();
    if (sortvalue) {
      var selectSortBy = $('.pf_filter_cl02 .pf_content_menu ul li');
      for (i = 0; i < selectSortBy.length; i++) {
	if ($(selectSortBy[i]).find('span').attr('data-option') == sortvalue) {
	  $('.selectedSub').html($(selectSortBy[i]).find('span').html());
	  $(selectSortBy[i]).css('display', 'none');
	}
      }
    }
    filterClickSys();
  });

  var loadDivData = function(div, data, count, loadedRecords) {
    var pro_img = data[count].profile_image.replace('\"', '"');
    pro_img = pro_img.replace('\/', '/');
    if (pro_img.search('modules/boonex/avatar/data/favourite/0.jpg') != -1) {
      pro_img = '../../templates/tmpl_par/images/NO-PHOTOS_icon.png';
    }
    div.find('.pf_view_cl03').find('img').attr('src', pro_img);
//    div.find('.pf_view_cl03').find('img').attr('onclick', "window.location.href = '" + siteurl + "moreaboutus.php?id=" + data[count].profile_id + "&loadFrom=" + loadFrom + "'");
    div.find('.pf_view_cl03').find('img').css('cursor', "pointer");
//    div.find('.pf_view_cl04').find('span').html(data[count].profile_firstname);
//    if (div.find('.pf_view_cl04').find('div').length != 0) {
//      div.find('.pf_view_cl04').addClass('toolhelp');
//      div.find('.pf_view_cl04').attr('title-text', div.find('.pf_view_cl04').find('div').attr('title-text'))
//      $(div.find('.pf_view_cl04').find('span')[0]).html($(div.find('.pf_view_cl04').find('span')[1]).html());
//    }
    div.find('select.selectpicker').html('');
    var single, options;
    if (data[count].profile_id_Couple != 0) {
      options = '<option value="' + data[count].profile_firstname_full + '">' + data[count].profile_firstname_full + '</option>';
      options += '<option value="' + data[count].profile_id + '">' + data[count].gridData[data[count].profile_id].FirstName + '</option>';
      options += '<option value="' + data[count].profile_id_Couple + '">' + data[count].gridData[data[count].profile_id_Couple].FirstName + '</option>';
      single = 0;
    }
    else {
      options += '<option value="' + data[count].profile_id + '">' + data[count].gridData[data[count].profile_id].FirstName + '</option>';
      single = 1;
    }
    div.find('select.selectpicker').html(options);
    div.find('select.selectpicker').attr('count', count);
    div.find('.ind1').css('display', 'none');
    div.find('.ind2').css('display', 'none');
    div.find('.comb').css('display', 'block');
    nameSelect(data, count, div, single);

    div.find('.ind1').find('.ageGrid').html(data[count].gridData[data[count].profile_id].Age);
    if (hostname === "www.indianaadoption.com")
      div.find('.ind1').find('.ageGrid').parent().css('display', 'none');
    div.find('.ind1').find('.EducationGrid').html(data[count].gridData[data[count].profile_id].Education);
    div.find('.ind1').find('.ProfessionGrid').html(data[count].gridData[data[count].profile_id].Occupation);
    div.find('.ind1').find('.EthinicityGrid').html(data[count].gridData[data[count].profile_id].Ethnicity);
    div.find('.ind1').find('.ReligionGrid').html(data[count].gridData[data[count].profile_id].Religion);
    div.find('.ind1').addClass(data[count].profile_id);

    if (data[count].profile_id_Couple != 0) {
      div.find('.ind2').find('.ageGrid').html(data[count].gridData[data[count].profile_id_Couple].Age);
      if (hostname === "www.indianaadoption.com")
	div.find('.ind2').find('.ageGrid').parent().css('display', 'none');
      div.find('.ind2').find('.EducationGrid').html(data[count].gridData[data[count].profile_id_Couple].Education);
      div.find('.ind2').find('.ProfessionGrid').html(data[count].gridData[data[count].profile_id_Couple].Occupation);
      div.find('.ind2').find('.EthinicityGrid').html(data[count].gridData[data[count].profile_id_Couple].Ethnicity);
      div.find('.ind2').find('.ReligionGrid').html(data[count].gridData[data[count].profile_id_Couple].Religion);
      div.find('.ind2').addClass(data[count].profile_id_Couple);
    }

    div.find('.age').html(data[count].profile_age);
    if (hostname === "www.indianaadoption.com")
      div.find('.age').parent().css('display', 'none');
    div.find('.state').html(data[count].profile_state);
    div.find('.waiting').html(data[count].profile_waiting);
    div.find('.children').html(data[count].profile_noofchilds);
    div.find('.faith').html(data[count].profile_faith);

    div.find('.ethnicity').html(data[count].profile_childethnicity);
    div.find('.agechild').html(data[count].profile_childage);
    div.find('.adptype').html(data[count].profile_adoptiontype);
    div.find('.reccount').html(loadedRecords);

    var moreAbout = siteurl + data[count].profile_nickname + '/about';
    var ourHome = siteurl + data[count].profile_nickname + '/home';
    var ourLetters = siteurl + data[count].profile_nickname + '/letters';
    var contactUs = siteurl + data[count].profile_nickname + '/contact';
    var ourVideo = siteurl + data[count].profile_nickname + '/video';

    if (loadFrom != 'badge') {
      $('.pf_like_button').css('display', 'block');
      $('.pinkBar').css('display', 'none');
      div.find('.pf_like_button').attr('src', siteurl + 'templates/tmpl_par/images/ico_like_search.png');
      div.find('.pf_like_button').attr('value', data[count].profile_id);
      div.find('.pf_like_button').css('cursor', 'pointer');
      likeClick();
    }
    else {
      $('.pinkBar').html('');
      $('.pinkBar').css('display', 'block');
      $('.pf_like_button').css('display', 'none');

      moreAbout += '/badge';
      ourHome += '/badge';
      ourLetters += '/badge';
      contactUs += '/badge';
      ourVideo += '/badge';
    }
//    div.find('.pf_view_cl08').attr('href', siteurl + 'moreaboutus.php?id=' + data[count].profile_id + '&loadFrom=' + loadFrom);
    div.find('.pf_view_cl03').find('img').attr('onclick', "window.location.href = '" + moreAbout + "'");
    div.find('.pf_view_cl08').attr('href', moreAbout);
    div.find('.pf_view_cl09').attr('href', ourHome);
    div.find('.pf_view_cl010').attr('href', ourLetters);
    div.find('.pf_view_cl011').attr('href', contactUs);
    div.find('.pf_view_cl012').attr('href', ourVideo);
    div.find('.pf_view_cl013').attr('data-profileId', data[count].profile_id);
    arrayprofileid.push(data[count].profile_id);
    div.find('.pf_view_cl013').attr('href', data[count].profile_pdf);
    if (data[count].profile_pdf == 'javascript:void(0)')
      div.find('.pf_view_cl013').attr('onclick', 'printorder(' + data[count].profile_id + ')');
    else
      div.find('.pf_view_cl013').attr('target', '_blank');

    if ((data[count].epub_link != false)) {
      div.find('.pf_profile_cl013').attr('href', data[count].epub_link);
      div.find('.pf_profile_cl013').attr('target', '_blank');
      div.find('.pf_profile_cl013').off('click');
    }
    else {
      div.find('.pf_profile_cl013').attr('href', 'javascript:void(0);');
      div.find('.pf_profile_cl013').off('click');
      div.find('.pf_profile_cl013').on('click', function() {
	flipbookError('No e-book available');
      });
    }
    if (loadFrom == 'badge') {
      div.find('.pf_view_cl08').attr('target', '');
      div.find('.pf_view_cl09').attr('target', '');
      div.find('.pf_view_cl010').attr('target', '');
      div.find('.pf_view_cl011').attr('target', '');
      div.find('.pf_view_cl012').attr('target', '');
    }
  }
  window.posMid = 1;
  var createFamilyDiv = function(jd, ajaxFlag) {
    $('.pf_view_cl01').find('.clear').remove();
    for (var i = 0; i < jd.count; i++) {
      if (i > 0 || ajaxFlag == 1) {
	var famDiv2 = (window.posMid == 2) ? $("<div>", {class: "new pf_view_cl02 pf_view_cl021"}, {id: loadedRecords}) : $("<div>", {class: "new pf_view_cl02"}, {id: loadedRecords});
	famDiv2.html(famDivHTML);
	$('.loader').remove();
	famDiv2.fadeIn('slow');
	$('.pf_view_cl01').append(famDiv2);
	if (window.posMid !== 3)
	  window.posMid++;
	else {
	  window.posMid = 1;
	}
//	nameSelect();
	loadDivData(famDiv2, jd, i, loadedRecords);
	famDivLatest = famDiv2;
      }
      else {
	famDiv.find('.pf_view_cl03').find('img').attr('src', '');
	loadDivData(famDiv, jd, i, loadedRecords);
	window.posMid = 2;
	famDivLatest = famDiv;
	$('.loader').remove();
	$('.row').css('display', 'block');
	$('#1').css('display', 'block');
	filterClick();
      }
      loadedRecords++;
    }
    totalRecords += 6;
//    if (loadedRecords < totalRecords) {
//      $('.loadmore_wrapper').css('display', 'none');
//      $(window).off("scroll");
//    }
//    else {
    if (jd.needLoad == 1) {
      $('.pf_view_cl01').append($("<div>", {class: "clear"}));
      $(window).on("scroll");
      $('.loadmore_wrapper').css('display', 'block');
      scrollEvent();
      clickLoad();
    }
    else {
      $(window).off("scroll");
      $('.loadmore_wrapper').css('display', 'none');
    }
//    scrollEvent();
//    clickLoad();
//    $('.loadmore_wrapper').css('display', 'block');
//    }
    $('.dialog').off('click');
    $('.dialog').on('click', function() {
      $('.tooltipData').html($(this).parent().attr('title-text'));
    });
//    $('.pf_view_cl013').off('click');
//    $('.pf_view_cl013').on('click', function() {
//      var profileID = $(this).attr('data-profileId');
//      printPDF(profileID);
//    });
  };
  var filterClickSys = function() {
    $('.view nav .pf_content_menu ul li').off('click');
    $('.view nav .pf_content_menu ul li').on('click', function() {
//      $('.view nav .pf_content_menu ul').css('display', 'none');
      $('#searchedName').val('');
      $('.searchResult').css('display', 'none');
      $('#sortyby').val($(this).find('span').attr('data-option'));
      $('#type').val($(this).find('span').attr('data-val'));
      ajaxLoadFamilies($('#sortyby').val(), $('#type').val());
      return false;
    });
  }

  var scrollEvent = function() {
    $(window).off("scroll");
    $(window).on('scroll', function(event) {
      $('.loadmore_wrapper').css('display', 'none');
      var scroll = $(window).scrollTop();
      if (scroll >= ($(document).height() - $(window).height()) - 200) {
	$(window).off("scroll");
//	if (loadedRecords == totalRecords) {
	$('.pf_view_cl01').append($("<div>", {class: "loader"}, {id: 1}));
	$('.loader').attr('u', 'loading');
	$('.loader').html('Loading');
	var searchVal = $('#searchedName').val();
	$.getJSON(siteurl + 'viewourfamilies/processors/featuredfamilieslist.php?sortvalue=' + $('#sortyby').val() + "&type=" + $('#type').val() + '&loadFrom=' + loadFrom + '&agencyId=' + agencyId + '&posStart=' + loadedRecords + '&pid=' + arrayprofileid + "&searchFilter=" + searchVal, function(jd) {
	  if (jd.count > 0) {
	    createFamilyDiv(jd, 1);
	    $('.norecords').css('display', 'none');
	  }
	  else {
	    $('.loader').css('display', 'none');
//	      $('.pf_view_cl02').css('display', 'none');
	    $('.loadmore_wrapper').css('display', 'none');
//	      $('.norecords').css('display', 'block');
	    $(window).off("scroll");
	  }
	});
//	}
      }
    });
  };

  var clickLoad = function() {
    $('.loadmore_wrapper').off("click");
    $('.loadmore_wrapper').on('click', function(event) {
      $(window).off("scroll");
      $('.loadmore_wrapper').off("click");
      $('.pf_view_cl01').append($("<div>", {class: "loader"}, {id: 1}));
      $('.loader').html('Loading');
      var searchVal = $('#searchedName').val();
      $.getJSON(siteurl + 'viewourfamilies/processors/featuredfamilieslist.php?sortvalue=' + $('#sortyby').val() + "&type=" + $('#type').val() + '&loadFrom=' + loadFrom + '&agencyId=' + agencyId + '&posStart=' + loadedRecords + '&pid=' + arrayprofileid + "&searchFilter=" + searchVal, function(jd) {
	if (jd.count > 0) {
//	  arrayprofileid = [];
	  createFamilyDiv(jd, 1);
	  $('.norecords').css('display', 'none');
	}
	else {
	  $('.loader').css('display', 'none');
//	  $('.pf_view_cl02').css('display', 'none');
	  $('.loadmore_wrapper').css('display', 'none');
//	  $('.norecords').css('display', 'block');
	  $(window).off("scroll");
	}
      });
    });
  }

  $(".item").click(function() {
    if ($(window).width() < 960) {
      $('#filter').html($('<ul>'));
      $('#filter').find('ul').html($(this).find('ul').html());
    }
    $display = $('#filter').css('display');
    if ($display === 'block') {
      $('#filter').css('display', 'none');
      return;
    } else {
      $('#filter').css('display', 'block');
      $('.view .row #filter ul li').click(function() {
	$('#searchedName').val('');
	$('.searchResult').css('display', 'none');
	$('#sortyby').val($(this).find('span').attr('data-option'));
	$('#type').val($(this).find('span').attr('data-val'));
	ajaxLoadFamilies($('#sortyby').val(), $('#type').val());
	return false;
      });
    }
  });

  var ajaxLoadFamilies = function(sortVal, type, searchVal) {
    $('.pf_view_cl01').css('display', 'none');
    $('.loadmore_wrapper').css('display', 'none');
    $('.searchResult').css('display', 'none');
    loadedRecords = 0;
    totalRecords = 0;
    $('.pf_view_cl020').append($("<div>", {class: "loader"}, {id: 1}));
    $('.loader').html('Loading');
    $.getJSON(siteurl + 'viewourfamilies/processors/featuredfamilieslist.php?sortvalue=' + sortVal + "&type=" + type + "&loadFrom=" + loadFrom + "&agencyId=" + agencyId + "&searchFilter=" + searchVal, function(jd) {
      if (jd.count > 0) {
	arrayprofileid = [];
	$('.new').empty()
	$('.new').remove();
	createFamilyDiv(jd);
	$('.loader').css('display', 'none');
	$('.pf_view_cl01').css('display', 'block');
	$('.norecords').css('display', 'none');
	if (searchVal != '' && searchVal != undefined) {
	  $('.searchResult').css('display', 'block');
	  $('.searchResult span').html(searchVal);
	}
      }
      else {
	$('.searchResult').css('display', 'none');
	$('.loader').css('display', 'none');
	$('.pf_view_cl02').css('display', 'none');
	$('.loadmore_wrapper').css('display', 'none');
	$('.norecords').css('display', 'block');
      }
    });
  }

  $(".pf_filter_cl03").click(function() {
    if ($(this).parent().is('.pf_filter_cl05')) {
      $(this).parent().removeClass("pf_filter_cl05");
    } else {
      $(".pf_filter_cl03").parent().removeClass("pf_filter_cl05");
      $(this).parent().addClass("pf_filter_cl05");
    }
  });

  $('#reload').click(function() {
    $('#searchedName').val('');
    $('.searchResult').css('display', 'none');
    $($('#pf_filter a')[0]).trigger('click');
    $('#sortyby').val('random');
    $('#type').val('Sortby');
    $('.pf_view_cl01').css('display', 'none');
    ajaxLoadFamilies($('#sortyby').val(), $('#type').val());
  });

//  $(".pf_filter_cl03").blur(function() {
//    debugger;
//      $(this).parent().removeClass("pf_filter_cl05");
//  });

  $('.main').click(function() {
    $(".main").css('display', 'block');
    $(this).css('display', 'none');
    $('.selectedMain').html($(this).html());
    $(".pf_filter_cl03").parent().removeClass("pf_filter_cl05");
    var t = $('.' + $(this).attr('data-val')).find('ul').html();
    $('.selectedSub').html('Please Select');
    $('.pf_filter_cl02 .pf_content_menu ul').html(t);
    filterClick();
  });

  var filterClick = function() {
    $('.pf_filter_cl02 .pf_content_menu ul li').off('click');
    $('.pf_filter_cl02 .pf_content_menu ul li').on('click', function() {
      $(".pf_content_menu ul li").css('display', 'block');
      $(this).css('display', 'none');
      $(".pf_filter_cl03").parent().removeClass("pf_filter_cl05");
      $('.selectedSub').html($(this).find('span').html());
      $('#sortyby').val($(this).find('span').attr('data-option'));
      $('#type').val($(this).find('span').attr('data-val'));
      $('#searchedName').val('');
      $('.searchResult').css('display', 'none');
      ajaxLoadFamilies($('#sortyby').val(), $('#type').val());
    });
  }

//  $('.pf_filter_cl01').click(function() {
//    debugger;
//    $(this).find('.pf_content_menu').html($($('.pf_content_menu')[0]).find('a'));
//    if ($(this).is('.pf_filter_cl05')) {
//      $(this).removeClass("pf_filter_cl05");
//    } else {
//      $(this).addClass("pf_filter_cl05");
//    }
//    
//  });

  $('.search').click(function() {
    var searchVal = $('#searchInput').val();
    $('.loadmore_wrapper').css('display', 'none');
    if (searchVal != '') {
      $('#searchedName').val(searchVal);
      $('#searchInput').val('');
      $(window).off("scroll");
      $('#sortyby').val('');
      $('#type').val('');
      ajaxLoadFamilies($('#sortyby').val(), $('#type').val(), searchVal);
    }
  });

  $('#searchInput').on('keypress', function(ev) {
    if (ev.which == 13) {
      var searchVal = $('#searchInput').val();
      if (searchVal != '') {
	ev.preventDefault();
	$('.loadmore_wrapper').css('display', 'none');
	$(window).off("scroll");
	$('#sortyby').val('');
	$('#type').val('');
	$('#searchedName').val(searchVal);
	$('#searchInput').val('');
	$('#searchInput').blur();
	ajaxLoadFamilies($('#sortyby').val(), $('#type').val(), searchVal);
      }
    }
  });

  var likeClick = function() {
    var profileId;
    $('.pf_like_button').off('click');
    $('.pf_like_button').on('click', function(ev) {
      ev.preventDefault();
      profileId = $(this).attr('value');
      $.ajax({
	url: siteurl + "LikeComponent/processors/profile_view_information.php",
	type: "POST",
	cache: false,
	data: {Profileid: profileId},
	datatype: "json",
	success: function(data) {
	  var n = JSON.parse(data);
	  if (n.status == "success") {
	    if (n.Profiles_value.rows != 0 && n.Profiles_value.profile_typoe == 4) {
	      $.ajax({
		url: siteurl + "LikeComponent/processors/insert_like_user.php",
		type: "POST",
		cache: false,
		data: {userid: profileId, Birthmotherid: "obj.profile_id"},
		datatype: "json",
		success: function(dataLike) {
		  var n = JSON.parse(dataLike);
		  if (n.status == "success") {
		    $('#likeAdded .data').html('Added to "Families I Like" list');
		    $('.likeClick').trigger('click');
		  }
		  else {
		    $('#likeAdded .data').html(n.response);
		    $('.likeClick').trigger('click');
		  }
		}
	      });
	    } else if (n.Profiles_value.rows != 0 && n.Profiles_value.profile_typoe != 4) {
	      $('#likeAdded .data').html("Birth mothers can only like this page");
	      $('.likeClick').trigger('click');
	    } else {
	      $('.likeDialog').trigger('click');
	    }
	  }
	  else {
	    $('#likeAdded .data').html(n.response);
	    $('.likeClick').trigger('click');
	  }
	}
      });
    });
  }

  $('.createAcc').click(function() {
    $('#likeLogin .closebtn').trigger('click');
    $($('.topMenuJoinBlock')[0]).trigger('click');
  });
  $('.loginAcc').click(function() {
    $('#likeLogin .closebtn').trigger('click');
    $($('.topMenuJoinBlock')[1]).trigger('click');
  });
  $('.NoAcc').click(function() {
    $('#likeLogin .closebtn').trigger('click');
  });

  var flipbookError = function(msg) {
    dhtmlx.message({
      type: "alert-error",
      text: msg,
    });
  };

  var nameSelect = function(data, count, div, single) {
    //expand collapse function for journal
//    $(".pf_filter_cl03").off('click');
//    $(".pf_filter_cl03").on('click', function() {
//      if ($(this).parent().is('.pf_filter_cl05')) {
//	$(this).parent().removeClass("pf_filter_cl05");
//      } else {
//	$(this).parent().addClass("pf_filter_cl05");
//      }
//    });

    //expand collapse function for journal
    $(".pf_profile_filter_cl03").off('click');
    $(".pf_profile_filter_cl03").on('click', function() {
      if ($(this).parent().is('.pf_profile_filter_cl05')) {
	$(this).parent().removeClass("pf_profile_filter_cl05");
      } else {
	$(this).parent().addClass("pf_profile_filter_cl05");
      }
    });
    $('.selectpicker').selectpicker();

    div.find('.selectpicker').selectpicker('refresh');

    if (single == 1) {
      div.find('.open').css('display', 'none');
      div.find('.selectpicker').selectpicker('val', data[count].profile_id);
      div.find('.caret').css('display', 'none');
      div.find('.btn').css('cursor', 'auto');
    }
    else {
      div.find('.selectpicker').selectpicker('val', data[count].profile_firstname_full);
    }

    $('select.selectpicker').off('change');
    $('select.selectpicker').on('change', function() {
      $(this).selectpicker('render');
      var selected = $(this).selectpicker('val');
      var count = $(this).attr('count');
      console.log(selected);

      $(this).parent().parent().parent().find('.ind1').css('display', 'none');
      $(this).parent().parent().parent().find('.ind2').css('display', 'none');
      $(this).parent().parent().parent().find('.comb').css('display', 'none');

      if (!isNaN(selected)) {
//	$(this).parent().parent().parent().find('.ind').css('display', 'block');
	$(this).parent().parent().parent().find('.' + selected).css('display', 'block');
//	$(this).parent().parent().parent().find('.ageGrid').html(data[count].gridData[selected].Age);
//	$(this).parent().parent().parent().find('.EducationGrid').html(data[count].gridData[selected].Education);
//	$(this).parent().parent().parent().find('.ProfessionGrid').html(data[count].gridData[selected].Occupation);
//	$(this).parent().parent().parent().find('.EthinicityGrid').html(data[count].gridData[selected].Ethnicity);
//	$(this).parent().parent().parent().find('.ReligionGrid').html(data[count].gridData[selected].Religion);
      }
      else {
//	$(this).parent().parent().parent().find('.ind1').css('display', 'none');
//	$(this).parent().parent().parent().find('.ind2').css('display', 'none');
	$(this).parent().parent().parent().find('.comb').css('display', 'block');
      }
    });
  }


  var printPDF = function(profileID) {
    $.ajax({
      url: siteurl + 'Expctantparentsearchfamilies/processors/pdf_profile_view.php',
      type: "POST",
      cache: false,
      data: {Profileid: profileID},
      datatype: "json",
      success: function(data) {
	if (data.status == 'success') {
	  if (data.printprofile.rows) {
	    if (data.deafulttempid.rows == 0) {
	      window.open(data.printprofile.rows);
	    } else {
	      $.ajax({
		url: siteurl + "/PDFUser/regenearate_pdf.php",
		type: "POST",
		cache: false,
		data: {
		  sel_tmpuser_ID: data.printprofile.rows
		},
		datatype: "json",
		success: function(data1) {
		  window.open(data1.filename);
		  return false;
		}
	      });
	    }
	  } else {
	    dhtmlx.confirm({
	      type: "confirm",
	      text: "There is no printed profile available.Would you like to send the family a request ?  ( Yes / No ) answer",
	      ok: "Yes",
	      cancel: "No",
	      callback: function(result) {
		if (result == true) {
		  $.ajax({
		    url: siteurl + "Expctantparentsearchfamilies/processors/pdf_profile_request.php",
		    type: "POST",
		    cache: false,
		    data: {
		      Profileid: profileID
		    },
		    datatype: "json",
		    success: function(data) {
		      if (data.status == "success") {
			dhtmlx.alert({
			  text: "Your request has been sent to the family please check back soon"
			});
		      } else {
			dhtmlx.message({
			  type: "error",
			  text: data.message
			})
		      }
		    }
		  });
		}
	      }
	    });
	  }
	} else {
	  dhtmlx.confirm({
	    type: "confirm",
	    text: "There is no printed profile available.Would you like to send the family a request ?  ( Yes / No ) answer",
	    ok: "Yes",
	    cancel: "No",
	    callback: function(result) {
	      if (result == true) {
		$.ajax({
		  url: siteurl + "Expctantparentsearchfamilies/processors/pdf_profile_request.php",
		  type: "POST",
		  cache: false,
		  data: {
		    Profileid: profileID
		  },
		  datatype: "json",
		  success: function(data) {
		    if (data.status == "success") {
		      dhtmlx.alert({
			text: "Your request has been sent to the family please check back soon"
		      });
		    } else {
		      dhtmlx.message({
			type: "error",
			text: data.message
		      })
		    }
		  }
		});
	      }
	    }
	  });
	}
      }
    });
  };
});