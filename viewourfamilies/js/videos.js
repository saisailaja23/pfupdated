$(document).ready(function() {

  var getUrlVars = function() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) {
      vars[key] = value;
    });
    return vars;
  };

  //var loadFrom = getUrlVars().loadFrom;
  if (loadFrom == 'badge') {
    $('.back').css('display', 'block');
    $('.back').click(function() {
      window.history.back();
    });
  }
  else {
    $('.back').css('display', 'none');
  }

//  var Id = getUrlVars().id, approve = getUrlVars().approve ,  rands = new Date().getTime();
  var approve = getUrlVars().approve, rands = new Date().getTime();

  $.getJSON(siteurl + "ProfileViewComponent/processors/getphotosvideos.php?videos=true&type=user&id=" + Id, function(jsonData) {
    var jw_play_list = [];
    if (jsonData.status == "success") {
      for (i in jsonData.data) {
	if (jsonData.data[i].source == "boonex" && jsonData.data[i].videoURL != '') {
//	  console.log(jsonData.data[i].videoURL);
	  jw_play_list.push({
	    image: siteurl + 'flash/modules/video/files/_' + jsonData.data[i].id + '.jpg',
	    sources: [{
		file: jsonData.data[i].videoURL
//		file: siteurl + 'flash/modules/video/files/_' + jsonData.data[i].id + '.mp4'
	      }]
	  });
	  $('#carouselV ul').append($('<li></li>').html($('<img></img>').attr('src', siteurl + 'flash/modules/video/files/_' + jsonData.data[i].id + '_small.jpg')));
	}
      }
      $.getScript('components/album/js/jwplayer/jwplayer.js', function() {
	jwplayer.key = "TFY8XJrqkQZEPwil4aTGwJmtN3tA+5VcSuvtE7lSeWI=";
//	console.log(jw_play_list);
	var jwp = jwplayer("sliderV").setup({
//	  file: 'http://example.com/myVideo.mp4',
	  height: 'auto',
	  width: '100%',
//	  type: 'html5',
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
	  jwp.stop();
	  $('#carouselV').find('li').find('img').css('opacity', 0.5);
	  $(this).find('img').css('opacity', 1);
	})
      });
//      var jwp = jwplayer("sliderV").setup({
//	file: "http://example.com/myVideo.mp4",
//	height: 'auto',
//	width: '100%',
//	flashplayer: siteurl + 'components/album/js/jwplayer/player.swf',
//	controlbar: 'bottom',
//	playlist: jw_play_list
//      });
//      jwp.onComplete(function() {
//	jwp.stop();
//      });
//      $('#carouselV').flexslider({
//	animation: "slide",
//	controlNav: false,
//	animationLoop: false,
//	slideshow: false,
//	itemWidth: 116,
//	itemMargin: 5
//      }).find('li').on('click', function(e) {
//	e.stopPropagation();
//	var i = $(this).index();
//	jwp.playlistItem(i);
//	jwp.stop();
//	$('#carouselV').find('li').find('img').css('opacity', 0.5);
//	$(this).find('img').css('opacity', 1);
//      });
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
      $('#sliderV').css('display', 'none');
      $('#carouselV').css('display', 'none');
      $('.pf_more_videos').css('display', 'none');
    }
  });

  $.ajax({
    url: '' + siteurl + 'ProfileViewComponent/processors/profile_view_information.php?id=' + Id,
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

  var loadData = function(jsonData) {    
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

  };
//  $.ajax({
//    url: '' + siteurl + 'viewourfamilies/processors/familyvideos_listJson.php?id=' + Id,
//    type: "POST",
//    cache: false,
//    data: {
//      approve: approve
//    },
//    datatype: "json",
//    success: function(data) {
//      loadData(data);
//    }
//  });

//  var loadData = function(jsonData) {
//    console.log(jsonData);
//    var profilename, content = '', album_count, album_name, album_contentid;
//    if (jsonData.count > 0 && jsonData.status == 'success') {
//      profilename = (jsonData.Profiles_couple.rows[0]) ? jsonData.Profiles.rows[0].data[2].trim() + '&' + jsonData.Profiles_couple.rows[0].data[1].trim() : jsonData.Profiles.rows[0].data[2].trim();
//      for (var i = 1; i <= jsonData.count; i++) {
//	album_count = jsonData['album'][i]['albumlist']['count'];
//	album_name = jsonData['album'][i]['Caption'];
//	content += '<div class="pf_video_cl01" ><div class="pf_video_cl02"><div class="pf_video_cl04">';
//	if (album_count > 0) {
//	  for (var j = 1; j <= 13; j++) {
//	    if (album_count >= j) {
//	      album_contentid = jsonData['album'][i]['albumlist'][j]['ID'];
//	      content += '<div><img  src="' + siteurl + 'flash/modules/video/files/_' + album_contentid + '_small.jpg"></div>';
//	    }
//	  }
//
//	} else {
//	  content += ' <div> No Videos</div>';
//	}
//	content += '</div></div><div class="pf_video_cl03"><div class=""><a title="' + album_name + '" href="javascript:void(0)">' + album_name + '</a></div>';
//	content += '<div class="">' + album_count + ' Videos By <a href="javascript:void(0)">' + profilename + '</a></div>';
//	content += '</div></div>';
//	$('#pf_video_id01_id').html(content)
//      }
//    } else {
//      $('#pf_video_id01_id').html(jsonData.status);
//    }
//
//  };

});