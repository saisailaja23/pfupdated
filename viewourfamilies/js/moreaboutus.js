$(document).ready(function() {  
  $('#noVideo').css('display', 'none');
  $('#sliderV').css('display', 'none');
  $('#carouselV').css('display', 'none');
  $('#noPhoto').css('display', 'none');
  $('#slider').css('display', 'none');
  $('#carousel').css('display', 'none');
  $('#noPhoto').css('display', 'none');
  $('#noVideo').css('display', 'none');
  $('.page118').hide();  
  $('.image .loader').css('display', 'block');
  $('.videos .loader').css('display', 'block');

  var getUrlVars = function() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) {
      vars[key] = value;
    });
  };
  
   var execstart = function() {
       
//  var Id = '__profile_id__', approve = getUrlVars().approve, loadFrom = '__profile_badge__';
    $('.page118').show(); 
    console.log(screen.width);
    var approve = '';
//    var approve = getUrlVars().approve;
  if (loadFrom == 'badge') {
    $('.back').css('display', 'block');
    $('.ouragency').hide();
    $('.back').click(function() {
      window.history.back();
    });


  }
  else {
    $('.back').css('display', 'none');
    $('.pf_more_cl012').css('background-image', "url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAvCAIAAAATh2/FAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6QjMyMzBENTMyRUVBMTFFM0FCODZFNTNBQ0MxQzJBRkQiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6QjMyMzBENTQyRUVBMTFFM0FCODZFNTNBQ0MxQzJBRkQiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpCMzIzMEQ1MTJFRUExMUUzQUI4NkU1M0FDQzFDMkFGRCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpCMzIzMEQ1MjJFRUExMUUzQUI4NkU1M0FDQzFDMkFGRCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pl3a5voAAAIESURBVHja7FhdS8JQGN53bsVCDDSwiwK9CbLbLuoX9G/ruttuvDEowiARQxISQcrpjps9x1N2WtamTDdzY4w53Pb4vu/zcVTeLipCPDZJiM2WQEmgrCAUJawHyZmtjZODEXH6V/c4RlkVZX8HR1GV5ZwZZYN4BKKuRQmFlST6sRUNjYfidq3IoGilPBrEQelHA0Ur7YE7fElGPXtRZJZMXT3c/fhAXLvSYFxFX1APHge9qMigtOcJox4hd01fhvtDATX490k1XTRUDAcgTp0b2fjBoAyt1rD2En6DQNepOP5meyyE32m/+pZkWR5E3CBWsE7ODG6TaiseIUGVA+rvCjYILJhPRiGJIUOxy3Xy0JqPPiGnuNRpQfiUqeFThwaUfDqIcIXpQc5zV8ps0jOL/kRqeMSxb5titUU9IbstqL+W1m2/obNBFTlZqCZQ/uOSDGlI0jWeCCysgEqIVK5lM+lj8Qpf8+S64Azyh6Lk02ox27u8+XKVcb4cXD8iO8LqsNOEmzNxBdc9gZK/ceEL1QmOie0xfMteMyPkQnPxYt5+IYkoJE0Ilj1sdJY3tpBjvHjWtBt+VRBasafMgnacR49YcITeL7BBxvkRO2Gz+S2nEWdQrqfOCqgNvInOdTHLGjTT2Pp7ECMzv/5j/xV4yIwGiePA5ulUYocJlATK2kF5F2AAyrzn5zhoamwAAAAASUVORK5CYII=')");
    $('.pf_more_cl012').attr('value', Id);

    var profileId;
    $('.pf_more_cl012').off('click');
    $('.pf_more_cl012').on('click', function(ev) {
      ev.preventDefault();
      profileId = Id;
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

  var ourHome = siteurl + nickName + '/home';
  var ourLetters = siteurl + nickName + '/letters';
  var contactUs = siteurl + nickName + '/contact';

  if (loadFrom == 'badge') {
    ourHome += '/badge';
    ourLetters += '/badge';
    contactUs += '/badge';
  }

  $('.pf_more_cl06').attr('href', ourHome)
  $('.pf_more_cl07').attr('href', ourLetters)
  $('.pf_more_cl08').attr('href', contactUs)
  $('.pf_more_cl09').attr('data-profileId', Id);
  //$('.pf_more_cl09').attr('onclick','printorder('+Id+')');
  $.ajax({
    url: siteurl + 'ProfileViewComponent/processors/profile_view_information.php?id=' + Id,
    type: "POST",
    cache: false,
    data: {
      approve: approve
    },
    datatype: "json",
    success: function(data) {
      var jsonData = JSON.parse(data);
      loadData(jsonData);
    }
  });

  $.ajax({
    url: siteurl + "ProfileViewComponent/processors/getphotosvideos.php?p=edit&id=" + Id + "&type=user",
    type: "POST",
    cache: true,
    data: {approve: 1, from: 'profile'},
    datatype: "json",
    success: function(data) {
      var jsonData = JSON.parse(data), imgLi = [], thmbLi = [], img = [], thmb = [];
      if (jsonData.status === "success" && jsonData.bData !== null && jsonData.data !== null) {
	for (var i in jsonData.data) {
	  if(jsonData.cData[i] != '') {
	    var imageCaption = '<span style="width:100%;">'+jsonData.cData[i]+'</span>';
	    $('.pgwSlideshow').append($('<li></li>').html($('<img></img>').attr('src', jsonData.bData[i]).attr('data-large-src', jsonData.data[i]).attr('data-description', imageCaption)));
	  }
	  else
	  $('.pgwSlideshow').append($('<li></li>').html($('<img></img>').attr('src', jsonData.bData[i]).attr('data-large-src', jsonData.data[i])));
	}
	setTimeout(function() {
	  photoSlider();
	  $('.image .loader').css('display', 'none');
	  $('#noPhoto').css('display', 'none');
	  $('#slider').css('display', 'block');
	  $('#carousel').css('display', 'block');
	  $('.pf_more_fotos a').attr('href', siteurl + 'more-photos.php?id=' + Id + '&loadFrom=' + loadFrom);
	  if (loadFrom == 'badge') {
	    $('.pf_more_fotos').css('display', 'none');
	  }
	  else
	    $('.pf_more_fotos').css('display', 'block');
	}, 1);

      }
      else {
	$('.image .loader').css('display', 'none');
	$('#noPhoto').css('display', 'block');
	$('#slider').css('display', 'none');
	$('#carousel').css('display', 'none');
	$('.pf_more_fotos').css('display', 'none');
      }
    }
  });
  $.getJSON(siteurl + "ProfileViewComponent/processors/getphotosvideos.php?videos=true&type=user&id=" + Id, function(jsonData) {
    var jw_play_list = [], imgUrlL, imgUrlS;
    if (jsonData.status == "success") {
      for (i in jsonData.data) {
	if (jsonData.data[i].source == "boonex" && jsonData.data[i].videoURL != '') {
//	  console.log(jsonData.data[i].videoURL);
          if (jsonData.data[i].youTube == 1) {
            imgUrlS = imgUrlL = 'http://img.youtube.com/vi/' + jsonData.youtube[jsonData.data[i].id] + '/0.jpg';
          }
          else {
            imgUrlL = siteurl + 'flash/modules/video/files/_' + jsonData.data[i].id + '.jpg';
            imgUrlS = siteurl + 'flash/modules/video/files/_' + jsonData.data[i].id + '_small.jpg';
          }
	  jw_play_list.push({
            image: imgUrlL,
	    sources: [{
		file: jsonData.data[i].videoURL
//		file: siteurl + 'flash/modules/video/files/_' + jsonData.data[i].id + '.flv'
	      }]
	  });
          $('#carouselV ul').append($('<li></li>').html($('<img></img>').attr('src', imgUrlS)));
	}
      }
      $.getScript('components/album/js/jwplayer/jwplayer.js', function() {
	jwplayer.key = "TFY8XJrqkQZEPwil4aTGwJmtN3tA+5VcSuvtE7lSeWI=";
	var jwp = jwplayer("sliderV").setup({
//	file: "http://example.com/myVideo.mp4",
	  height: 'auto',
	  width: '100%',
//	type: 'html5',
//	flashplayer: siteurl + 'components/album/js/jwplayer/player.swf',
	  modes: [{
	      type: 'html5'
	    }, {
	      type: 'flash',
	      src: siteurl + 'components/album/js/jwplayer/player.swf'
	    }],
	  aspectratio: '16:9',
	  playlist: jw_play_list
	});
	jwp.onComplete(function() {
	  jwp.stop();
	});
	$('#carouselV').flexslider({
	  animation: "slide",
	  controlNav: false,
	  animationLoop: false,
	  slideshow: false,
	  itemWidth: 116,
	  itemMargin: 5
	}).find('li').on('click', function(e) {
	  e.stopPropagation();
	  var i = $(this).index();
	  jwp.playlistItem(i);
//	  jwp.stop();
	  $('#carouselV').find('li').find('img').css('opacity', 0.5);
	  $(this).find('img').css('opacity', 1);
	})
      });
      $('#carouselV li').first().find('img').css('opacity', 1);
      $('.videos .loader').css('display', 'none');
      $('#noVideo').css('display', 'none');
      $('#sliderV').css('display', 'block');
      $('#carouselV').css('display', 'block');
      $('.pf_more_videos a').attr('href', siteurl + 'more-videos.php?id=' + Id + '&loadFrom=' + loadFrom);
      if (loadFrom == 'badge') {
	$('.pf_more_videos').css('display', 'none');
      }
      else {
	$('.pf_more_videos').css('display', 'block');
      }
    } else {
      $('.videos .loader').css('display', 'none');
      $('#noVideo').css('display', 'block');
	/**/
	dhtmlxAjax.get(site_url + "components/album/processors/saveDefaultYoutubeLink.php?method=get&id="+Id, function(loader) {
		var data = JSON.parse(loader.xmlDoc.responseText);
//                data = null;
		if(data.videoUri){
			var con = '<iframe width="638" height="300" src="http://www.youtube.com/embed/'+data.videoUri+'"></iframe>';
			$('#noVideo').html(con);		
		}
	});	  
	/**/

      $('#sliderV').css('display', 'none');
      $('#carouselV').css('display', 'none');
      $('.pf_more_videos').css('display', 'none');
    }
  });

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
  };
  
  if(!$.cookie(nickName.toUpperCase())) {
  
  dhtmlxAjax.get(site_url + "viewourfamilies/processors/getProfilePublishStatus.php?id="+Id, function(loader) {      
        var data = JSON.parse(loader.xmlDoc.responseText);        
        if(data.PublishStatus.rows[0].data[0] == '1')
        {  
            var date = new Date();
            var minutes = 30;
            var cookie_var = nickName.toUpperCase();
            date.setTime(date.getTime() + (minutes * 60 * 1000));
            $.cookie(cookie_var, Id , { expires: date });
//            $.cookie("PUBSTATUS"+Id, Id , { expires: date });
            execstart();
        }
        else{
            $('.page118').hide();
            $('.social-icons').hide();
            $('.publishpwd').show();
            $('#pubpwd').focus(function() {
                $('.incorrect-publabel').hide();
            }); 
            $('#pubpwd').bind('keypress',function (event){
                $('.incorrect-publabel').hide();
                if (event.keyCode === 13){
                  $('#pubproceed').trigger('click');
                }
            });
            $('#pubproceed').click(function() {                
                if($('#pubpwd').val() == data.PublishStatus.rows[0].data[1] ) {                    
                    $('.publishpwd').hide();
                    var date = new Date();
                    var minutes = 30;
                    var cookie_var = nickName.toUpperCase();
                    date.setTime(date.getTime() + (minutes * 60 * 1000));
                    $.cookie(cookie_var, Id , { expires: date });                    
//                    $.cookie("PUBSTATUS"+Id, Id , { expires: date });
                    execstart();                    
                }
                else{                   
                    $('.incorrect-publabel').show();                    
                }
            });
        }                      
    });
  }
  else {
        var date = new Date();
        var minutes = 30;
        var cookie_var = nickName.toUpperCase();
        date.setTime(date.getTime() + (minutes * 60 * 1000));
        $.cookie(cookie_var, Id , { expires: date });
        execstart();
  }
 
  var loadData = function(jsonData) {
    //Sailaja - Changing the styles for HOA agency
    if (loadFrom == 'badge') {  
      if(jsonData.agency_address.rows[0].data[0].trim()== 'Heart of Adoptions Inc.'){
           $( ".pf_more_cl011" ).css( 'background','rgba(135, 214, 231, 1)' );
           $( ".pf_more_cl012" ).css( 'background','#d5594f' );
           //icons change
//           $( ".pf_more_cl05" ).css('background','url("/viewourfamilies/images/HOA/icon_ebook_active.png")');
//           $( ".pf_more_cl06" ).css('background-image','url("/viewourfamilies/images/HOA/icon_our_home.png")');
//           $( ".pf_more_cl07" ).css('background-image','url("/viewourfamilies/images/HOA/icon_letters.png")');
//           $( ".pf_more_cl08" ).css('background-image','url("/viewourfamilies/images/HOA/icon_contact.png")');
//           $( ".pf_more_cl09" ).css('background-image','url("/viewourfamilies/images/HOA/icon_profile_print.png")');           
      } 
      if(jsonData.agency_address.rows[0].data[0].trim()== 'Colorado Christian Services'){
          $( ".pf_more_cl011" ).css( 'background','rgb(125, 223, 222)' );
          $( ".pf_more_cl012" ).css( 'background','rgb(235, 240, 218)' );
          //icons change
//           $( ".pf_more_cl05" ).css('background','url("/viewourfamilies/images/CCS/icon_aboutus.png")');
//           $( ".pf_more_cl06" ).css('background-image','url("/viewourfamilies/images/CCS/icon_ourhome.png")');
//           $( ".pf_more_cl07" ).css('background-image','url("/viewourfamilies/images/CCS/icon_ourletters.png")');
//           $( ".pf_more_cl08" ).css('background-image','url("/viewourfamilies/images/CCS/icon_contact.png")');
//           $( ".pf_more_cl09" ).css('background-image','url("/viewourfamilies/images/CCS/icon_print.png")'); 
      }
    }
    $('.profileName').html((jsonData.Profiles_couple.rows[0]) ? jsonData.Profiles.rows[0].data[2].trim() + ' & ' + jsonData.Profiles_couple.rows[0].data[1].trim() : jsonData.Profiles.rows[0].data[2].trim());
    $('.lastactive').html(jsonData.lastactivetime.rows);
    var d = new Date();
    var year = d.getFullYear();
    if (jsonData.Profiles.rows[0].data[0]==7310) {
      $('.ouragency ').hide();
    }
    //console.log(jsonData.Profiles.rows[0].data[11]);
        //Sailaja - Single parent logo changes
        if (jsonData.Profiles.rows[0].data[27] == 0) {
            $('.pf_more_cl06').css('margin-left', '-9px');
            $('.pf_more_cl06').css('padding-right', '55px');
            // $('.pf_more_cl05').css('background-image', 'url("../viewourfamilies/images/singleparent/icon_my_flipbook.png")');
            $('.pf_more_cl06').css('background-image', 'url("../viewourfamilies/images/singleparent/icon_my_home.png")');
            $('.pf_more_cl07').css('background-image', 'url("../viewourfamilies/images/singleparent/icon_my_letters.png")');
            // $('.pf_more_cl05').hover(function() {
            //     $(this).css('background-image', 'url("../viewourfamilies/images/singleparent/icon_my_flipbook_hover.png")');
            // }, function() {
            //     $(this).css('background-image', 'url("../viewourfamilies/images/singleparent/icon_my_flipbook.png")');
            // });
            $('.pf_more_cl06').hover(function() {
                $(this).css('background-image', 'url("../viewourfamilies/images/singleparent/icon_my_home_hover.png")');
            }, function() {
                $(this).css('background-image', 'url("../viewourfamilies/images/singleparent/icon_my_home.png")');
            });
            $('.pf_more_cl07').hover(function() {
                $(this).css('background-image', 'url("../viewourfamilies/images/singleparent/icon_my_letters_hover.png")');
            }, function() {
                $(this).css('background-image', 'url("../viewourfamilies/images/singleparent/icon_my_letters.png")');
            });
            $('#website').html('MY WEBSITE');
            $('#journal').html('MY JOURNAL');
            $('#agency').html('MY AGENCY');
            $('.pf_view_cl016').find('span').html('MY CHILD');
        }
    if (jsonData.Profiles.rows[0].data[11]) {
      var profile_age = jsonData.Profiles.rows[0].data[11];
    }
    else {
      var profile_age = 'N/A';
    }
    if (jsonData.Profiles_couple.rows[0])
      if (jsonData.Profiles_couple.rows[0].data[2])
      {
        var profile_age_couple = jsonData.Profiles_couple.rows[0].data[2];
      }
      else
	var profile_age_couple = 'N/A';
    if (jsonData.Profiles_couple.rows[0])
      var pf_age = profile_age + ' / ' + profile_age_couple;
    else
      var pf_age = profile_age;
    $('.profileage').html(pf_age);    
    $('.profilestate').html((jsonData.Profiles.rows[0].data[12].trim()) ? jsonData.Profiles.rows[0].data[12] : 'N/A');
    // res = jsonData.Profiles.rows[0].data[12].split("(");
        // if (jsonData.Profiles.rows[0].data[36].trim() != 'US' && jsonData.agency_address.rows[0].data[0].trim() == 'Heart of Adoptions Inc.') {
        if (jsonData.Profiles.rows[0].data[39] != '__United States'){
            // console.log(res[0]);
            // result = res[1].split(")");

            $('.profilestate').html(jsonData.Profiles.rows[0].data[39].slice(2));
            $('.profilestate').prev().text("COUNTRY:");
        }
    $('.profilewaiting').html((jsonData.Profiles.rows[0].data[13].trim()) ? jsonData.Profiles.rows[0].data[13] : 'N/A');
    $('.profilechildren').html((jsonData.Profiles.rows[0].data[14].trim()) ? jsonData.Profiles.rows[0].data[14] : 'N/A');
    $('.profilefaith').html((jsonData.Profiles.rows[0].data[15] != 'null' && (jsonData.Profiles.rows[0].data[15].trim())) ? jsonData.Profiles.rows[0].data[15].trim().replace(/,/g, ", ") : 'N/A');
    $('.profileethinicity').html((jsonData.Profiles.rows[0].data[16].trim()) ? jsonData.Profiles.rows[0].data[16].replace(/,/g, ", ") : 'N/A');
    $('.profilechildage').html((jsonData.Profiles.rows[0].data[17].trim()) ? jsonData.Profiles.rows[0].data[17].replace(/,/g, ", ") : 'N/A');
    $('.profileadoptiontype').html((jsonData.Profiles.rows[0].data[18].trim()) ? jsonData.Profiles.rows[0].data[18].replace(/,/g, ", ") : 'N/A');
    if(jsonData.Profiles.rows[0].data[6].trim() != '')
        $('a.fb').attr('href', (jsonData.Profiles.rows[0].data[6].trim().search('http') == 0) ? jsonData.Profiles.rows[0].data[6].trim() : 'http://'+jsonData.Profiles.rows[0].data[6].trim());
    else
        $('a.fb').css('display', 'none');
    
    if(jsonData.Profiles.rows[0].data[7].trim() != '')
        $('a.tw').attr('href', (jsonData.Profiles.rows[0].data[7].trim().search('http') == 0) ? jsonData.Profiles.rows[0].data[7].trim() : 'http://'+jsonData.Profiles.rows[0].data[7].trim());
    else
        $('a.tw').css('display', 'none');
    
    if(jsonData.Profiles.rows[0].data[8].trim() != '')
        $('a.gp').attr('href', (jsonData.Profiles.rows[0].data[8].trim().search('http') == 0) ? jsonData.Profiles.rows[0].data[8].trim() : 'http://'+jsonData.Profiles.rows[0].data[8].trim());
    else
        $('a.gp').css('display', 'none');
    
    if(jsonData.Profiles.rows[0].data[9].trim() != '')
        $('a.bg').attr('href', (jsonData.Profiles.rows[0].data[9].trim().search('http') == 0) ? jsonData.Profiles.rows[0].data[9].trim() : 'http://'+jsonData.Profiles.rows[0].data[9].trim());
    else
        $('a.bg').css('display', 'none');
    
    if(jsonData.Profiles.rows[0].data[10].trim() != '')
        $('a.pi').attr('href', (jsonData.Profiles.rows[0].data[10].trim().search('http') == 0) ? jsonData.Profiles.rows[0].data[10].trim() : 'http://'+jsonData.Profiles.rows[0].data[10].trim());
    else
        $('a.pi').css('display', 'none');
	if (jsonData.Profiles.rows[0].data[38].trim() != '')
            $('a.in').attr('href', (jsonData.Profiles.rows[0].data[38].trim().search('http') == 0) ? jsonData.Profiles.rows[0].data[38].trim() : 'http://' + jsonData.Profiles.rows[0].data[38].trim());
        else
            $('a.in').css('display', 'none');

    if (jsonData.Profiles.rows[0].data[21].trim() != "") {
      $('.profile1').css('display', 'block');
      $('.profile1').css('padding-right', '15px');      
      $('.profile1').html('<b>ABOUT ' + jsonData.Profiles.rows[0].data[2].trim() + '</b>: <span></span>');
    }
    if (jsonData.Profiles_couple.rows[0]) {
      if (jsonData.Profiles_couple.rows[0].data[3].trim() != "") {
	$('.profile2').css('display', 'block');
        $('.profile2').css('padding-right', '15px');        
	$('.profile2').html('<b>ABOUT ' + jsonData.Profiles_couple.rows[0].data[1].trim() + '</b>: <span></span>');
      }
    }
    $('.profile1 span').html(jsonData.Profiles.rows[0].data[21].trim());
    $('.profile2 span').html((jsonData.Profiles_couple.rows[0]) ? jsonData.Profiles_couple.rows[0].data[3].trim() : '');
    $('.profile1').find('img').css('max-width','340px');
    $('.profile2').find('img').css('max-width','340px');
    $('.profile1').find('img').css('height','auto');
    $('.profile2').find('img').css('height','auto');
    
    if (jsonData.blog_posts.rows[0]) {
       
      for (i in jsonData.blog_posts.rows) {
       
        jouHead = $("<div>", {class: "jorHead"});
        if (i < 4) {
         jLink = jsonData.blog_posts.rows[i].data[3].replace(/[^a-zA-Z1-9 ]/g, "");          
          jLink = encodeURIComponent(jLink);
          $(jouHead).html('<a href="' + siteurl + nickName + '/journal/' + jLink + '/' + jsonData.blog_posts.rows[i].data[4] + '/'+ loadFrom + '">' + jsonData.blog_posts.rows[i].data[3] + '</a>');
          jouBody = $("<div>", {class: "jorBody"});
          if(jsonData.blog_posts.rows[i].data[1].trim() != '')
            $(jouBody).html(jsonData.blog_posts.rows[i].data[1] + '<br><br>' + '<span>' + jsonData.blog_posts.rows[i].data[0] + '</span> ' + '<br><br>');
          else
            $(jouBody).html('<span>' + jsonData.blog_posts.rows[i].data[0] + '</span> ' + '<br><br>');
          $('.ourjournal').append(jouHead);
          $('.ourjournal').append(jouBody);
        }  else
          break;
      }
                      jouHead = $("<div>", {
                    class: "jorHead"
                });
                $(jouHead).html('<a class="moreJor" href="' + siteurl + nickName + '/journals/' + loadFrom + '">CLICK HERE FOR MORE JOURNALS</a>');
                    $('.ourjournal').append(jouHead);       
           
//      $('#seemore a').attr('href', siteurl + 'modules/boonex/blogs/blogs.php?action=show_member_blog&ownerID=' + id);
        } else {
      $('.ourjournal1').css('display', 'none');
    }
    if (jsonData.Profiles.rows[0].data[26].trim()) {
     $('.website').html('<a target ="_blank" href="http://' + jsonData.Profiles.rows[0].data[26].trim()+ '">' + jsonData.Profiles.rows[0].data[26].trim() + '</a>');
    }
    else {
      $('.ourwebsite1').css('display', 'none');
    }
    if (jsonData.Profiles.rows[0]) {
      $('.pf_more_cl019').html('<a target ="_blank" href="http://' + jsonData.agency_address.rows[0].data[7].trim() + '">'+jsonData.agency_logo.rows[0].data.trim()+'</a>');
    }
    if (jsonData.Profiles.rows[0]) {
      var profile_agency_city = (jsonData.agency_address.rows[0].data[1].trim() != '') ? jsonData.agency_address.rows[0].data[1].trim() + ', ' : jsonData.agency_address.rows[0].data[1].trim();
      try
      {
	var profile_agency_state = (jsonData.agency_address.rows[0].data[9].trim() != '') ? jsonData.agency_address.rows[0].data[9].trim() + ', ' : jsonData.agency_address.rows[0].data[9].trim();
      } catch (err) {
      }
      var agency_zip = (jsonData.agency_address.rows[0].data[3].trim() != '') ? jsonData.agency_address.rows[0].data[3].trim() + ', ' : jsonData.agency_address.rows[0].data[3].trim();
      if (jsonData.agency_address.rows[0].data[5].trim() != '' && jsonData.agency_address.rows[0].data[5].trim() != '0')
		var agency_contact = jsonData.agency_address.rows[0].data[5].trim()+'<br/>';

      else
		agency_contact = '';
      $('.pf_more_cl020').html(jsonData.agency_address.rows[0].data[0].trim() + '<br/>' + profile_agency_city + profile_agency_state + agency_zip + '<br/>' + agency_contact + '<a href="mailto:' + jsonData.agency_address.rows[0].data[6].trim() + '">' + jsonData.agency_address.rows[0].data[6].trim() + '</a><br/><a  target ="_blank" href="http://' + jsonData.agency_address.rows[0].data[7].trim() + '">' + jsonData.agency_address.rows[0].data[7].trim() + '</a>');
	  /**/
	  $.getJSON(siteurl + "viewourfamilies/processors/check_default.php?id=" + Id, function(res) {
		if(res.data == 1){
			$('.ouragency').hide();			
		}else{
			$('.pf_more_cl019').find('img').removeClass('headIcon');		
		}
	  });
	  /**/
	  
    }

    ebook_link = jsonData.ebook_link.rows;
    ebook_mobile_link = jsonData.ebook_mob_link.rows;
     epub_link = jsonData.epup_link.rows;
         if ((epub_link != false)) {
            $('.pf_more_cl08_ebok').attr('href', epub_link);
            $('.pf_more_cl08_ebok').attr('target', '_blank');
            $('.pf_more_cl08_ebok').off('click');
        } else if ((ebook_link != false) || (ebook_mobile_link != false)) {
            if (head.mobile && ebook_mobile_link) {
                //console.log('mobile link false');
                //console.log(ebook_mobile_link);
                $('.pf_more_cl08_ebok').attr('href', ebook_mobile_link);
            } else
               $('.pf_more_cl08_ebok').attr('href', ebook_link);
            $('.pf_more_cl08_ebok').attr('target', '_blank');
            $('.pf_more_cl05').off('click');
        } else {
            $('.pf_more_cl08_ebokpf_more_cl08_ebok').attr('href', 'javascript:void(0);');
            //div.find('.pf_more_cl05').off('click');
             $('.pf_more_cl08_ebok').click(function() {
                flipbookError('No Flip book available');
            });
        }


        if ((ebook_link != false) || (ebook_mobile_link != false)) {
            if (head.mobile) {
                ebook_mobile_link ? $('.pf_more_cl05').attr('href', siteurl + ebook_mobile_link) : $('.pf_more_cl05').attr('href', siteurl + ebook_link);
            } else {
                $('.pf_more_cl05').attr('href', siteurl + ebook_link);
            }

            $('.pf_more_cl05').attr('target', '_blank');
        } else {
            $('.pf_more_cl05').attr('href', 'javascript:void(0);');
            $('.pf_more_cl05').click(function() {
                flipbookError('There is no ebook uploaded');
            });
        }
        /*$('.pf_more_cl09').attr('href', jsonData.profile_pdf);
    if (jsonData.profile_pdf == 'javascript:void(0)')
      $('.pf_more_cl09').attr('onclick', 'printorder(' + Id + ')');
    else
      $('.pf_more_cl09').attr('target', '_blank');*/

        //  download profiles
        var pdf_url = site_url + "ProfilebuilderComponent/pdf.php?id=" + Id;

        $('#viewHtml').attr('href', pdf_url);
        if (jsonData.profile_pdf == 'javascript:void(0)') {
            $('.pf_more_cl09').attr('href', pdf_url);
        } else {
            $('.pf_more_cl09').click(function() {

                $('.printClick').show();
                $('.print_more').toggle();
                $('#viewPdf').attr('href', jsonData.profile_pdf);
                $('#viewPdf').attr('download', nickName.toUpperCase());


            });
        }
        $('.printClick').click(function() {
            $('.print_more').toggle();
            $('.printClick').hide();
        });
        $('.print_more a').click(function() {
            $('.print_more').toggle();
            $('.printClick').hide();
        });
                

    if (jsonData.Profiles.rows[0].data[5].trim() == 188) {
      $('.profileage').parent().css('display', 'none');
      $('.profilewaiting').parent().css('display', 'none');
      $('.pf_view_cl016').find('span').html('OPEN TO ADOPTING');
    }
  };
  var flipbookError = function(msg) {
    dhtmlx.message({
      type: "alert-error",
      text: msg,
    });
  };
  var photoSlider = function() {

    var pgwSlideshow = $('.pgwSlideshow').pgwSlideshow();
    var newConfig = {};
    newConfig.mainClassName = 'pgwSlideshowLight';
    pgwSlideshow.reload(newConfig);
  };
});