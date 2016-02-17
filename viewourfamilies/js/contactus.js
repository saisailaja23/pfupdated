$(document).ready(function() {
    $('.page212').hide();

  var getUrlVars = function() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) {
      vars[key] = value;
    });
    return vars;
  };
  var execstart = function() {
      $('.page212').show();
  if (loadFrom == 'badge') {
    $('.back').css('display', 'block');
    $('.back').click(function() {
      window.history.back();
    });
  }
  else {
    $('.back').css('display', 'none');
  }

  var validations = function() {
    var validFlag = 1, pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
    if ($('input[name="name"]').val() != '' && $('input[name="name"]').val() != ' ') {
      $($('input[name="name"]').parent().find('span')[1]).css('display', 'none');
    }
    else {
      validFlag = 0;
      $($('input[name="name"]').parent().find('span')[1]).css('display', 'inline');
    }
    if (pattern.test($('input[name="email"]').val())) {
      $($('input[name="email"]').parent().find('span')[1]).css('display', 'none');
    }
    else {
      validFlag = 0;
      $($('input[name="email"]').parent().find('span')[1]).css('display', 'inline');
    }
    if ($('input[name="subject"]').val() != '' && $('input[name="subject"]').val() != ' ') {
      $($('input[name="subject"]').parent().find('span')[1]).css('display', 'none');
    }
    else {
      validFlag = 0;
      $($('input[name="subject"]').parent().find('span')[1]).css('display', 'inline');
    }
    if ($('textarea[name="body"]').val() != '' && $('textarea[name="body"]').val() != ' ') {
      $($('textarea[name="body"]').parent().find('span')[1]).css('display', 'none');
    }
    else {
      validFlag = 0;
      $($('textarea[name="body"]').parent().find('span')[1]).css('display', 'inline');
    }
    if ($('input[name="check"]').val() == '' && validFlag != 0) {
      validFlag = 1;
    }
    else {
      validFlag = 0;
    }
    return validFlag;
  };

  var agencyId = getUrlVars().agencyId;
//  var loadFrom = getUrlVars().loadFrom;
//  var id = getUrlVars().ID;

  $.getJSON(siteurl + 'viewourfamilies/processors/getAgencyInfo.php?loadFrom=' + loadFrom + '&Agency=' + agencyId + '&ID=' + id, function(jd) {
    $('#agencytitle').html(jd.AgencyTitle);
	
	var phno = jd.CONTACT_NUMBER;
	if(phno ==0 || phno == ''){
		$('.phno_div').hide();
	}else{
		$('#agencycontact').html(phno);
	}
    
	$('a#agencyemail').attr('href','mailto:'+jd.Email);
    $('a#agencyemail').html(jd.Email);
    if (loadFrom == 'badge') {
        if(jd.AgencyTitle.trim()== 'Heart of Adoptions Inc.'){
            $( ".pf_contact_cl01" ).css( 'background','rgba(135, 214, 231, 1)' );
            $( ".pf_contact_cl07 > #submit" ).css( 'background','#d5594f' );
            $( '.pf_contact_cl02 > a').css( 'color','#d5594f)' );
            $( '.pf_contact_cl02 > a').hover(function() {
                $( this ).css('color','#77787a');
              });
        }
    }
  });

  $('#submit').click(function() {
    if (validations()) {
      $.ajax({
	url: siteurl + 'viewourfamilies/processors/contactUsSubmission.php?ID=' + id,
	type: "POST",
	cache: false,
	data: {
	  name: $('input[name="name"]').val(),
	  email: $('input[name="email"]').val(),
	  subject: $('input[name="subject"]').val(),
	  body: $('textarea[name="body"]').val()
	},
	datatype: "json",
	success: function(data) {
	  $('#ajaxMessage').html(data);
	}
      });
    }
  });
  
  if (loadFrom == 'badge') {
    $('.back').css('display', 'block');
    $('.back').click(function() {
      window.history.back();
    });
  }
  else {
    $('.back').css('display', 'none');
  }
  
  $.ajax({
    url: '' + siteurl + 'ProfileViewComponent/processors/profile_view_information.php?id=' + id,
    type: "POST",
    cache: false,
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

  $.getJSON(siteurl + "viewourfamilies/processors/check_default.php?id=" + id, function(res) {
	if(res.data == 1){
		$('.agency_div').hide();			
	}
  }); 
  };
  if(!$.cookie(nickName.toUpperCase())) {
    dhtmlxAjax.get(site_url + "viewourfamilies/processors/getProfilePublishStatus.php?id="+id, function(loader) {      
        var data = JSON.parse(loader.xmlDoc.responseText);

//        console.log(data);
        if(data.PublishStatus.rows[0].data[0] == '1')
        {  
            var date = new Date();
            var minutes = 30;
            var cookie_var = nickName.toUpperCase();
            date.setTime(date.getTime() + (minutes * 60 * 1000));
            $.cookie(cookie_var, id , { expires: date });
//            $.cookie("PUBSTATUS"+id, id , { expires: date });
            execstart();
        }
        else{
            $('.page212').hide();
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
                    $.cookie(cookie_var, id , { expires: date });
                    console.log($.cookie());
//                    $.cookie("PUBSTATUS"+id, id , { expires: date });
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
        $.cookie(cookie_var, id , { expires: date });
        execstart();        
  }
});