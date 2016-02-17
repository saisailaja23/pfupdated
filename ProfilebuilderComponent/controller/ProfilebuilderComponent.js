/***********************************************************************
 * Name:    Prashanth A
 * Date:    11/11/2013
 * Purpose: Creating a family profile builder page
 ***********************************************************************/
var cnt = 0;
var cnt2 = 0;
var attachImages = {
	LoadImage: function(cnt){
		var self = this;
		$("#picDiv").dialog(
			{ title: 'PHOTO ALBUM', autoOpen: true, height: 600, width: 630, modal: true,},
			{ buttons: [{
				  text: "Select",
				  icons: { primary: "ui-icon-check" },
				  click: function(){
					 var ii = 0; var ID = '';
					 var albID = []; var imgAr = [];
					 $('.check').each(function(e){
						 if($(this).is(":checked")){
							 albID[ii] = $(this).val();
							 imgAr[ii] = $(this).data('img');
							 ID = ID + $(this).val()+"_"; 
							 ii++;
						 }
					 });

					 var albStr = JSON.stringify(albID);
					 var imgStr = JSON.stringify(imgAr);
					 //alert(imgStr); 
					 $(this).dialog('close');
					 $('input[name="imgID"]').val(albStr);
					 $('input[name="imgID"]').focus();
					 
					 self.showImages(imgStr, albStr);
					//$('.check').attr('checked', false);
				  }
				}
			  ]}
		);

		if(cnt2 == 0){ 
			//$('.alb_img').slideToggle(); 

			cnt2++;
		}

            $('.colapse').on('click', function(){
		if(cnt==0){
	                var ref = $(this).data('ref');
        	        $(ref).slideToggle();
                	if($(this).find('.fa').hasClass('fa-sort-desc')){
	                    $(this).find('.fa').removeClass('fa-sort-desc');
        	            $(this).find('.fa').addClass('fa-sort-asc');
			
                	}else{
	                    $(this).find('.fa').removeClass('fa-sort-asc');
        	            $(this).find('.fa').addClass('fa-sort-desc');
                	}
		}
            });
		
	},
	showImages: function(imgStr, albStr){
		$('.check').prop('checked', false);
//		console.log(imgStr);
//		console.log(albStr);
		
		imgStr = JSON.parse(imgStr);

		var con = '<div>';
	if(imgStr != ''){
		var divc = 1;
		var bg_url = '';
		for(i in imgStr){
			//var siteurl2 = "http://www.parentfinder.com/";
			//con = con + "<div class='imgClass'><img src='"+siteurl+"m/photos/get_image/thumb/"+imgStr[i]+"'/></div>";
			bg_url = siteurl+"m/photos/get_image/thumb/"+imgStr[i];
			con = con + "<div class='imgClass' style='background: url("+bg_url+") no-repeat center;' ></div>";
			if(divc % 5 == 0){ con = con + "</div><div>"; }
		}
	}

		con = con + "</div>";

		chkAr = JSON.parse(albStr);

	if(albStr != '' && albStr != "[]"){
		chkAr = chkAr.map(Number);
		var phid = ''; var a = '';
		
		
		$('.check').each(function(){
			phid = $(this).data('phid');
			var a = jQuery.inArray(phid, chkAr);
			if(a >= 0){ 
				//console.log($(this));
				$(this).prop('checked', true); 
			}else{ $(this).prop('checked', false); }
		});
	}
		
		$('#selected_images').html(con);


	}
}
var ProfilebuilderComponent = {
  uid: null,
  form: [],
  configuration: [],
  status: 0,
  dataStore: [],
  window_manager: null,
  getUrlVars: function() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) {
      vars[key] = value;
    });

    return vars;
  },
  _loadData: function(uid, callBack) {
    var self = this;
    self.uid = uid;
    var postStr = "1-1";
    dhtmlxAjax.post(self.configuration[uid].application_path + "processors/profile_information.php?id=" + self.getUrlVars()["id"], postStr, function(loader) {
      var json = JSON.parse(loader.xmlDoc.responseText);
      // console.log(json);
      if (json.status == "success") {
        self.approve_flag = json.auto_approve;
        self.dataStore["Profiles"] = json.Profiles;
        self.dataStore["Profiles_couple"] = json.Profiles_couple;
        self.dataStore["Profiles_agency"] = json.Profiles_agency;
        self.dataStore["Profiles_ChildDesired"] = json.Profiles_ChildDesired;
        self.dataStore["Profiles_BirthFatherStatus"] = json.Profiles_BirthFatherStatus;
        self.dataStore["Profiles_Ethnicityofcouple"] = json.Profiles_Ethnicityofcouple;
        self.dataStore["Profiles_educationofcouple"] = json.Profiles_educationofcouple;
        self.dataStore["Profiles_educationofcouple"] = json.Profiles_educationofcouple;
        self.dataStore["Profiles_ReligionCouple"] = json.Profiles_ReligionCouple;
        self.dataStore["Profiles_Region"] = json.Profiles_Region;
        self.dataStore["Profiles_Country"] = json.Profiles_Country;
        self.dataStore["Profiles_pet"] = json.Profiles_pet
        self.dataStore["Profiles_relationship"] = json.Profiles_relationship
        self.dataStore["Profiles_familystruct"] = json.Profiles_familystruct
        self.dataStore["Letter_Caption"] = json.Letter_Caption;
        self.dataStore["Profiles_Letters_Sort"] = json.Profiles_Letters_Sort;
        self.dataStore["flip_link"] = json.flip_link;
        self.dataStore["epub_link"] = json.epub_link;
        
        self.dataStore["Profiles_badge"] = json.Profiles_badge;
        self.dataStore["Profiles_Letters"] = json.Profiles_Letters;
        self.dataStore["Profiles_State"] = json.Profiles_State;
        self.dataStore["agency_flag"] = json.agency_flag;
        self.dataStore["membership_id"] = json.membership_id;
        self.dataStore["Profiles_Children"] = json.Profiles_Children;
	self.dataStore["featured"] = json.featured;
	self.dataStore["profilenumber"] = json.profilenumber;
        //  self.dataStore["sys_acl_levels"] = json.sys_acl_levels;
        //  self.dataStore["profiles"] = json.profiles;

        if (callBack)
          callBack();
      } else {
        dhtmlx.message({
          type: "error",
          text: json.response
        });
      }


      try {


      } catch (e) {
        dhtmlx.message({
          type: "error",
          text: "Fatal error on server side: " + loader.xmlDoc.responseText
        });
        // console.log(e.stack);
      }
    });
  },
  setAgencyDescription: function() {
    var self = ProfilebuilderComponent;
    //self.form[uid].setItemValue("Letter", self.letterValue);
    console.log(self.letterValue);
   
  },
  _window_manager: function() {
    var self = this;
    //console.log( self.vct_model );
    self.window_manager = new dhtmlXWindows();
    self.window_manager.enableAutoViewport(false);
    self.window_manager.attachViewportTo(vp);
  },
  _letter_window: function(uid, item, name) {

    var self = this;
	var ltr_name = '';
    item = unescape(item);

    if (self.window_manager === null) {
      self._window_manager();
    }

    self.window_manager[uid] = self.window_manager.createWindow('Win' + uid, "", "", 900, 600);

    //self.window_manager[uid].centerOnScreen();
    var pos = self.window_manager[uid].getPosition();


    self.window_manager[uid].setModal(true);

    self.window_manager[uid].button('park').hide();
    self.window_manager[uid].denyMove();
    self.window_manager[uid].denyResize();
    self.window_manager[uid].setText('');
    // self.window_manager[uid].centerOnScreen();
    var offset = (document.body.scrollTop ? document.body.scrollTop : window.pageYOffset);
    self.window_manager[uid].setPosition(((window.innerWidth) / 2 - 450), offset);

    self.form[uid] = self.window_manager[uid].attachForm(self.model.mothersletter);
    var poststr = "name=" + item + "&id=" + self.dataStore.Profiles.rows[0].data[0] + "&insertFalg=0";
	
	$('.ltr_dwnld').hide();
	$('.dwnld_ldng').hide();
	

	$('#dwnld_img').parent().removeClass('btn_txt'); 
	$('#dwnld_img').parent().parent().removeClass('btn_m'); 
	$('#dwnld_img').parent().parent().parent().parent().find('.btn_l').hide(); 	
	
    dhtmlxAjax.post(self.configuration[self.uid].application_path + "processors/letter_insert_fetch.php", poststr, function(loader) {
      var json = JSON.parse(loader.xmlDoc.responseText);
	  
      if (json.status == "success") {
        self.letterValue = (json.data[0] != null) ? json.data[0] : '';
         setTimeout(function() {
      tinyMCE.get('Letter').setContent('');
      tinyMCE.get('Letter').setContent(self.letterValue);
    }, 1000);
		
		if(json.data != '' && (json.data[0] != '' || json.data[1] != 'null')){
			$('.ltr_dwnld').show();
		}

/**/
if(json.data != ""){
	if(json.data[1] != ''){ imgStr = json.data[1]; }
	if(json.data[2] != ''){ albStr = json.data[2]; }
	if(json.data[3] != ''){ cusID = json.data[3]; }
}else{
	imgStr = "[]";
	albStr = "[]";
	cusID = "";
}
	attachImages.showImages(imgStr, albStr);
	
	$('input[name="imgID"]').val(albStr);
	$('input[name="imgID"]').focus();
	$('input[name="cusID"]').val(cusID);
	$('input[name="cusID"]').focus();	
/**/	

        //self.form[uid].setItemFocus('Letter');
      }
    });

    var json = self.dataStore;
    var Letternamer = self.form[uid].getSelect("Lettername");
    var Letternames = json.Letter_Caption.rows;

    for (var i in Letternames) {
      var Letterid = Letternames[i].id;
      var Letternameid = Letternames[i].data[1];
      Letternamer.options.add(new Option(Letternameid, Letternameid));
    }

    self.form[uid].hideItem("Letternamecustom");
    self.form[uid].attachEvent("onChange", function(id, value) {
      if (id == 'Lettername') {
        if (value == 'OTHER') {
          self.form[uid].showItem("Letternamecustom");
        } else {
          self.form[uid].hideItem("Letternamecustom");
        }
      }
    });
    // $('.btn_txt').css('padding', '0 0 0');
    // self.form[uid].hideItem("Lettername");

    self.form[uid].hideItem("letter_delet");
    self.form[uid].hideItem("Lettername");
    switch (item) {
      case 'MOTHER':
        self.form[uid].setItemLabel("LetterLabel", "<div style=\"color:#57B4A8;text-align:center; width :100%; font-size: 25px;font-weight: bold; text-align:center;\">EXPECTING MOTHER LETTER</div>");
		ltr_name = "Expecting mother letter";
        break;
      case 'AGENCY':
        self.form[uid].setItemLabel("LetterLabel", "<div style=\"color:#57B4A8;text-align:center; width :100%; font-size: 25px;font-weight: bold; text-align:center;\">AGENCY LETTER</div>");
        ltr_name = "Agnecy letter";
		break;
      case 'HIM':
        self.form[uid].setItemLabel("LetterLabel", "<div style=\"color:#57B4A8;text-align:center; width :100%; font-size: 25px;font-weight: bold; text-align:center;\">LETTER ABOUT " + name + "</div>");
        ltr_name = "Letter about " + name;
		break;
      case 'HER':
        self.form[uid].setItemLabel("LetterLabel", "<div style=\"color:#57B4A8;text-align:center; width :100%; font-size: 25px;font-weight: bold; text-align:center;\">LETTER ABOUT " + name + "</div>");
        ltr_name = "Letter about " + name;
		break;
      case 'THEM':
        self.form[uid].setItemLabel("LetterLabel", "<div style=\"color:#57B4A8;text-align:center; width :100%; font-size: 25px;font-weight: bold; text-align:center;\">LETTER ABOUT THEM </div>");
        ltr_name = "Letter about them";
		break;
      case 'OTHER':
        self.form[uid].setItemLabel("LetterLabel", "<div style=\"color:#57B4A8;text-align:center; width :100%; font-size: 20px;font-weight: bold; text-align:center;\"></div>");
		self.form[uid].showItem("Lettername");
        break;
      case 'HOME':
        self.form[uid].setItemLabel("LetterLabel", "<div style=\"color:#57B4A8;text-align:center; width :100%; font-size: 25px;font-weight: bold; text-align:center;\">OUR HOME</div>");
        ltr_name = "Our home";
		break;
      default:
		ltr_name = item;
        var heading = (item.length > 10) ? item.substring(0, 30).toUpperCase() + '...' : item.toUpperCase()
        self.form[uid].setItemLabel("LetterLabel", "<div style=\"color:#57B4A8;text-align:center; width :100%; font-size: 25px;font-weight: bold; text-align:center;\">" + heading + "</div>");
        self.form[uid].showItem("letter_delet");
        break;
    }
	self.window_manager[uid].setText('');
    var content = ''; // self.form[uid].getItemValue("Letter");

    self.form[uid].attachEvent("onButtonClick", function(name) {
      if (name == 'letter_save') {
        var filter = /^[a-zA-Z0-9 ]{0,30}$/;
        if (!filter.test(self.form[uid].getItemValue("Lettername"))) {
          dhtmlx.message({
            type: "alert-error",
            text: "Letter Label should be 30 characters maximum and alphanumeric"
          })
          return false;
        }
        if (self.form[uid].validateItem("Lettername") == false && item == 'OTHER') {

          return false;
        }

        if (self.form[uid].validateItem("Letternamecustom") == false && item == 'OTHER' && self.form[uid].getItemValue("Lettername") == 'OTHER') {

          return false;
        }
        if (item == 'OTHER') {
          if (self.form[uid].validateItem("Letter") == false) {
            dhtmlx.message({
              type: "alert-error",
              text: "Letter content cannot be blank"
            })
            return false;

          }
        }
        var content = self.form[uid].getItemValue("Letter");
	var imgID = self.form[uid].getItemValue("imgID");
	var cusID = self.form[uid].getItemValue("cusID");

        content = content.replace(/&nbsp;/g, ' ');
        // var res = content.split(" ");
        // if (res.length > 750) {
        //   var citrus = res.slice(0, 750);
        //   self.form[uid].setItemValue("Letter", citrus.join(' '));
        //   dhtmlx.alert('Letter content is maximum 750 words');
        //   return false;
        // }



        var otherLabelName = self.form[uid].getItemValue("Lettername");
        var otherLabelNamesec = self.form[uid].getItemValue("Letternamecustom");

        var poststr = "name=" + item + "&id=" + self.dataStore.Profiles.rows[0].data[0] + "&insertFalg=1" + "&content=" + encodeURIComponent(content) + "&label=" + otherLabelName + "&labelsec=" + otherLabelNamesec + "&imgID=" + imgID;



        dhtmlxAjax.post(self.configuration[self.uid].application_path + "processors/letter_insert_fetch.php", poststr, function(loader) {
          var json = JSON.parse(loader.xmlDoc.responseText);
          if (json.status == "success") {
            dhtmlx.message({
              text: "Saved successfully"
            });
            if (item == 'OTHER') {
              if (otherLabelNamesec) {
                letter_title = self.form[uid].getItemValue("Letternamecustom").toUpperCase();

              } else {
                letter_title = self.form[uid].getItemValue("Lettername").toUpperCase();

              }
              var itemData = {
                type: "label",
                className: "lbl ui-state-default letter_"+json.letter_id,
                //label: "<span  style='color: #009DBC;text-decoration: underline; cursor:pointer;'  onclick='ProfilebuilderComponent.lettar(\"" + self.form[uid].getItemValue("Lettername") + "\")'>" + self.form[uid].getItemValue("Lettername").toUpperCase() + "</span>",
                label: '<span style="color:#57B4A8; text-decoration: underline; cursor:pointer;"  onclick="ProfilebuilderComponent.lettar(\'' + letter_title + '\')">' + letter_title + '</span>',
                style: "font-weight:normal;",
                //name: self.form[uid].getItemValue("Lettername").toUpperCase()
                name: letter_title
              };
              
              var pos =$('.my-first-dev').parent().find('.ui-state-default').length ;

              self.Profile_form_g.addItem("LETTERS_block", itemData, pos);
              
              self._loadData(self.uid, function() {
                self._make_letter_sortable(self.uid);
              });

            }
            self.window_manager[uid].close();

          } else {
            dhtmlx.message({
              type: "error",
              text: json.message
            })
          }

        });
      } else if (name == 'letter_delet') {

        dhtmlx.confirm({
          type: "confirm",
          text: "Are you sure you want to delete this letter",
          ok: "Yes",
          callback: function(result) {
            if (result == true) {

              var otherLabelName = item.toUpperCase(); //self.form[uid].getItemValue("Lettername");             
              var content = self.form[uid].getItemValue("Letter");
              var poststr = "name=" + item + "&id=" + self.dataStore.Profiles.rows[0].data[0] + "&insertFalg=2" + "&content=" + encodeURIComponent(content) + "&label=" + otherLabelName;

              //ajax call to delte post
              dhtmlxAjax.post(self.configuration[self.uid].application_path + "processors/letter_insert_fetch.php", poststr, function(loader) {
                var json = JSON.parse(loader.xmlDoc.responseText);
                if (json.status == "success") {
                  dhtmlx.message({
                    text: "Letter Deleted"
                  });

                  self.Profile_form_g.removeItem(item.toUpperCase());
                  self.window_manager[uid].close();
                } else {
                  dhtmlx.message({
                    type: "error",
                    text: json.message
                  })
                }
              });
            }
          }
        });



	}else if(name == 'letter_download'){

		$('.ltr_dwnld').hide();
		$('.dwnld_ldng').show();	
	
		var albStr = $('input[name="imgID"]').val();
		var cusID = $('input[name="cusID"]').val();
		albStr = albStr.split('"').join('');
		albStr = albStr.split('[').join('');
		albStr = albStr.split(']').join('');
		albStr = albStr.split(',').join('_');

    item2 = item.replace(/[^a-zA-Z ]/g, "");
    ltr_name2 = ltr_name.replace(/[^a-zA-Z ]/g, "");
    
    var url = "https://www.parentfinder.com/ProfilebuilderComponent/processors/download/downloadLetters.php?name="+item2+"&id="+self.dataStore.Profiles.rows[0].data[0]+"&imgId="+albStr+"&ltr_name="+ltr_name2+"&cusID="+cusID;
		$.ajax({
			type: 'POST',
			url: url,
			success: function(res){
				window.location = url;
				$('.ltr_dwnld').show();
				$('.dwnld_ldng').hide();
			}
		});	
	}

else if(name == 'letter_images'){
  $('.alb_img').css('display', 'none');
  attachImages.LoadImage(cnt);
  $('.alb-fa').removeClass('fa-sort-desc');
  $('.alb-fa').addClass('fa-sort-asc');

  cnt++;
}

    });
  },
  lettar: function(item, name) {
    var self = this;
    tinyMCE.remove();
    switch (item) {
      case 'MOTHER':
        self._letter_window('about_mother', item)
        break;
      case 'AGENCY':
        self._letter_window('about_agency', item)
        break;
      case 'HIM':
        self._letter_window('about_him', item, name)
        break;
      case 'HER':
        self._letter_window('about_her', item, name)
        break;
      case 'THEM':
        self._letter_window('about_them', item)
        break;
      case 'OTHER':
        self._letter_window('about_other', item)
        break;
      case 'HOME':
        self._letter_window('our_home', item)
        break;
      default:
        self._letter_window('others', item)
        break;
    }
  },
  _form: function(uid) {
    var self = this;
    var conf_form = self.model.conf_form.template_profilebuilder;
    var Profile_form = new dhtmlXForm("profilebuilder", conf_form);
    self.Profile_form= Profile_form;

    // $('.btn_txt').css('padding', '0 0 0');

    $('.SpecialNeedText').parent().css('margin-top','2px');
    Profile_form.hideItem("specialneedoptions");
    self.Profile_form_g = Profile_form;


    Profile_form.hideItem("ctype");
    if(self.dataStore.Profiles.rows[0].data[53] == '0')
    Profile_form.disableItem('show_contact');
    if (self.dataStore.Profiles.rows[0].data[6] != '') {
      Profile_form.setItemLabel("fburl", "facebook", "<img src='templates/tmpl_par/images/splash/ico_fb_act.png' />");
    }
    if (self.dataStore.Profiles.rows[0].data[7] != '') {
      Profile_form.setItemLabel("turl", "twitter", "<img src='templates/tmpl_par/images/splash/ico_tw_act.png' />");
    }
    if (self.dataStore.Profiles.rows[0].data[8] != '') {
      Profile_form.setItemLabel("gurl", "google", "<img src='templates/tmpl_par/images/splash/ico_go_act.png' />");
    }
    if (self.dataStore.Profiles.rows[0].data[9] != '') {
      Profile_form.setItemLabel("burl", "blogger", "<img src='templates/tmpl_par/images/splash/ico_bi_act.png' />");
    }
    if (self.dataStore.Profiles.rows[0].data[10] != '') {
      Profile_form.setItemLabel("purl", "pinerest", "<img src='templates/tmpl_par/images/splash/ico_pi_act.png' />");
    }
	if (self.dataStore.Profiles.rows[0].data[54] != '') {
      Profile_form.setItemLabel("iurl", "instagram", "<img src='templates/tmpl_par/images/splash/ico_in_act.png' />");
    }
    Profile_form.forEachItem(function(id) {
      if (Profile_form.getItemType(id) == "upload") {
        var item = Profile_form._getItem(id);
        item._uploader._test = item.getForm(); //item.getForm();
        item._uploader._name = item._idd;
        item._uploader.callEvent = function(name, args) {
          args.push(this); // adding item name as last param
          return this._test.callEvent(name, args);
        }
        item = null;
      }
    });
    
    ageCalendarId = document.getElementsByName('age')[0].getAttribute( 'id' );
    ageCalendar =  new dhtmlXCalendarObject({input: ageCalendarId, button: "ageDateClndr"});  
    ageCalendar.hideTime();
    ageCalendar.attachEvent("onClick", function(date){
      ageDate = ageCalendar.getFormatedDate("%m-%d-%Y");  
      Profile_form.setItemValue("age",ageDate ); 
    });

    var coupleageCalendarId = document.getElementsByName('coupleage')[0].getAttribute( 'id' );
    var coupleageCalendar =  new dhtmlXCalendarObject({input: coupleageCalendarId, button: "coupleageDateClndr"});  
    coupleageCalendar.hideTime();
    coupleageCalendar.attachEvent("onClick", function(date){
      coupleageDate = coupleageCalendar.getFormatedDate("%m-%d-%Y"); 
      Profile_form.setItemValue("coupleage",coupleageDate ); 
    });

    join_window = new dhtmlXWindows();
    join_window.enableAutoViewport(false);
    join_window.attachViewportTo(vp);
    Profile_form.attachEvent("onFileAdd", function() {
      self.isUploading = true;
    });
    Profile_form.attachEvent("onUploadComplete", function() {
      self.isUploading = false;
    });
    $('a').on('click', function(e) {
      if (self.isUploading) {
        e.preventDefault();
        dhtmlx.message({
          type: "alert-error",
          text: "upload in progress. Please wait..."
        });
        return;
      }
    });
    //profile pic crop 
    var real, server;
    Profile_form.attachEvent("onUploadComplete", function(count, t) {
      if (t._name == "Avatarphoto") {
        server = "tmp/" + server;
        $.prettyPhoto.open('components/album/pcrop.php?server=' + server + '&img=' + siteurl + server + '&iframe=crop&width=100%&height=100%');
	}
	  
	if (t._name == "Bannerimage") {
        server = "tmp/coverphotos/" + server;

	}
	  
	  
    });
    Profile_form.attachEvent("onUploadFile", function(realName, serverName, t) {
      if (t._name == "Avatarphoto") {
        server = serverName;
        real = realName;
      }
      if (t._name == "Bannerimage") {
        server = serverName;
        real = realName;
      }
    });

    Profile_form.attachEvent("onBeforeFileRemove", function(realName, serverName, args) {
      for (var b in args._files) {
        if (realName == args._files[b].name && (args._files[b].state != "uploaded") || args._files[b].state == 'undefined')
          return true
        else if (realName == args._files[b].name && args._files[b].state == "uploaded") {
          switch (args._name) {
            case 'myFiles':
              break;
            case 'myEpubs':
              break;

            case 'Avatarphoto':
              return true;
              break;
            case 'Bannerimage':
              return true;
              break;

			  
            case 'Videos':
              dhtmlx.message({
                type: "alert-error",
                text: "You must upload only files in Video format"
              });
              break;
            default:
              dhtmlx.message({
                type: "alert",
                text: "The uploade files cannot be deleted from here. Please go to Photos and Videos section in the Dashboard.",
                /*ok: "Go to my Albums",
              callback: function (result) {
                if (result == true) {
                  window.location.href = self.configuration[self.uid].site + 'm/photos/albums/my/main';
                }
              }*/
              });
              break;
          }
        }
      }
    });
    Profile_form.attachEvent("onUploadFail", function(realName, t) {
      switch (t._name) {
        case 'myFiles':
          dhtmlx.message({
            type: "error",
            text: realName + " is not a PDF file."
          });
          break;
        case 'myEpubs':
          dhtmlx.message({
            type: "error",
            text: realName + " is not a PDF file."
          });
          break;

        case 'home_videos':
          dhtmlx.message({
            type: "error",
            text: realName + " is not a Video file."
          });
          break;
		  
        case 'Bannerimage':
          dhtmlx.message({
            type: "error",
            text: "Please upload image file of diamension greatter than 1000*300."
          });
          break;		  
        default:
          dhtmlx.message({
            type: "error",
            text: realName + " is not an Image file."
          });
          break;
      }
    });
    Profile_form.attachEvent("onUploadFile", function(realName, serverName, t) {

      var myUploader = Profile_form.getUploader("myFiles");
      var status = myUploader.getStatus();
      if (status == 1)
        Profile_form.disableItem("myFiles");

      var myUploaderepub = Profile_form.getUploader("myEpubs");
      var statusepub = myUploaderepub.getStatus();
      if (statusepub == 1)
        Profile_form.disableItem("myEpubs");

    });
	
	Profile_form.attachEvent("onUploadComplete", function(realName, t) {
      switch (t._name) {
        case 'Bannerimage':
          dhtmlx.message({
            type: "success",
            text: "Banner image has been uploaded successfully."
          });
          break;
		}
	
	
	});
	
    Profile_form.attachEvent("onChange", function(id, value) {
      if (id == 'specialneeds') {
        if (Profile_form.getCheckedValue('specialneeds') == 'no') {
          Profile_form.hideItem("specialneedoptions");
        } else {
          Profile_form.showItem("specialneedoptions");
        }
      }
	if(id =='profilenumber'){

	 if (Profile_form.validateItem("profilenumber") == false) {
		  dhtmlx.message({
			type: "alert-error",
			text: "Profile Number should be numeric (YY_XXXX)"
		  });    
    }else{
	  var profile_getstr = "?profile_number=" +value ;
      dhtmlxAjax.post(self.configuration[uid].application_path + "processors/profile_number.php" + profile_getstr, '', function(loader){
			var json = JSON.parse(loader.xmlDoc.responseText);
			if (json.status != "success") {
				dhtmlx.message({
				type: "alert-error",
				text: json.status
				}); 
				Profile_form.setItemValue(id, '');
			}
	  });
	}
		
	}
      if (id == 'noofchildren') {
        if (Profile_form.getItemValue("noofchildren") > 0) {
          Profile_form.showItem("ctype");
        } else {
          Profile_form.hideItem("ctype");
        }
      }
      if (id == 'specialneeds') {
        Profile_form.getCheckedValue('specialneeds');
      }
      if (id == 'fburl' || id == 'turl' || id == 'gurl' || id == 'burl' || id == 'purl' || id == 'iurl') {
        // creation of join form window
        var conf_form_join1 = self.model.conf_form1.template_profilebuilder1;
        var win_join = join_window.createWindow("w1", 500, 1340, 425, 110);
        win_join.button("park").hide();
        win_join.button("minmax1").hide();
        win_join.button("minmax2").hide();
        win_join.setText("");
        win_join.setModal(true);
        //  win_join.centerOnScreen();

        win_join.centerOnScreen();
        var pos = win_join.getPosition();
        var offset = (document.body.scrollTop ? document.body.scrollTop : window.pageYOffset);
        win_join.setPosition(pos[0], pos[1] + offset)


        var join_form = win_join.attachForm(conf_form_join1);
        // $('.btn_txt').css('padding', '0 0 0');

        // win_join.attachEvent("onClose", function(win){

        //  Profile_form.uncheckItem("fburl", "facebook");
        //  return true;
        // });    

        join_form.attachEvent("onButtonClick", function(name, command) {


          var facebook_url = join_form.getItemValue("facebookurl");
          var twitter_url = join_form.getItemValue("twitterurl");
          var google_url = join_form.getItemValue("googleurl");
          var blogger_url = join_form.getItemValue("bloggerurl");
          var pinerest_url = join_form.getItemValue("pineresturl");
		  var instagram_url = join_form.getItemValue("instagramurl");
          var actualURL_Value = '';
          switch (id) {
            case 'fburl':
              actualURL_Value = facebook_url;
              break;
            case 'turl':
              actualURL_Value = twitter_url;
              break;
            case 'gurl':
              actualURL_Value = google_url;
              break;
            case 'burl':
              actualURL_Value = blogger_url;
              break;
            case 'purl':
              actualURL_Value = pinerest_url;
              break;
			case 'iurl':
              actualURL_Value = instagram_url;
              break;
          }

          //url_validationFlag  = actualURL_Value.match(/(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/);
          //alert(url_validationFlag);
          //var pattern = /(ftp|http|https|www):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
          //var pattern = /((?:https?\:\/\/|www\.)(?:[-a-z0-9]+\.)*[-a-z0-9]+.*)/i;
          var pattern = /(http(s)?:\\)?([\w-]+\.)+[\w-]+[.com|.in|.org]+(\[\?%&=]*)?/;
          if (!pattern.test(actualURL_Value)) {
            dhtmlx.message({
              type: "alert-error",
              text: "Please enter Valid url and try again...."
            });
            return true;
          }
          if (name == "socialsubmit") {

            var poststr = "instagramurl=" + instagram_url+"&facebookurl=" + facebook_url + "&twitterurl=" + twitter_url + "&googleurl=" + google_url + "&bloggerurl=" + blogger_url + "&pineresturl=" + pinerest_url + "&id=" + id;

            // Inserting values to database
            dhtmlxAjax.post(self.configuration[uid].application_path + "processors/update_soical.php", poststr, function(loader) {
              var json = JSON.parse(loader.xmlDoc.responseText);
              if (json.status == "success") {
                if (id == 'fburl') {
                  self.dataStore.Profiles.rows[0].data[6] = facebook_url;
                  Profile_form.setItemLabel("fburl", "facebook", "<img src='" + json.img + "' />");
                } else if (id == 'turl') {
                  self.dataStore.Profiles.rows[0].data[7] = twitter_url;
                  Profile_form.setItemLabel("turl", "twitter", "<img src='" + json.img + "' />");
                } else if (id == 'gurl') {
                  self.dataStore.Profiles.rows[0].data[8] = google_url;
                  Profile_form.setItemLabel("gurl", "google", "<img src='" + json.img + "' />");
                } else if (id == 'burl') {
                  self.dataStore.Profiles.rows[0].data[9] = blogger_url;
                  Profile_form.setItemLabel("burl", "blogger", "<img src='" + json.img + "' />");
                } else if (id == 'purl') {
                  self.dataStore.Profiles.rows[0].data[10] = pinerest_url;
                  Profile_form.setItemLabel("purl", "pinerest", "<img src='" + json.img + "' />");
                }else if (id == 'iurl') {
                  self.dataStore.Profiles.rows[0].data[54] = instagram_url;
                  Profile_form.setItemLabel("iurl", "instagram", "<img src='" + json.img + "' />");
                }
                dhtmlx.alert("Your changes have been saved", function(result) {
                  if (result == true) {
                    win_join.close();
                  }
                });

              } else {
                dhtmlx.message({
                  type: "alert-error",
                  text: "Please enter Valid url and try again...."
                })
              }
            });

          }


        });

      }

      if (id == 'fburl') {
        win_join.attachEvent("onClose", function(win) {

          Profile_form.uncheckItem("fburl", "facebook");

          Profile_form.enableItem("turl", "twitter");
          Profile_form.enableItem("gurl", "google");
          Profile_form.enableItem("burl", "blogger");
          Profile_form.enableItem("purl", "pinerest");
          Profile_form.enableItem("fburl", "facebook");
		  Profile_form.enableItem("iurl", "instagram");
          return true;
        });
        join_form.setItemValue("facebookurl", self.dataStore.Profiles.rows[0].data[6]);
        Profile_form.disableItem("turl", "twitter");
        Profile_form.disableItem("gurl", "google");
        Profile_form.disableItem("burl", "blogger");
        Profile_form.disableItem("purl", "pinerest");
		Profile_form.disableItem("iurl", "instagram");
        join_form.hideItem("twitterurl");
        join_form.hideItem("googleurl");
        join_form.hideItem("bloggerurl");
        join_form.hideItem("pineresturl");
		join_form.hideItem("instagramurl");
      }
      if (id == 'turl') {
        win_join.attachEvent("onClose", function(win) {
          Profile_form.uncheckItem("turl", "twitter");
          Profile_form.uncheckItem("fburl", "facebook");
          Profile_form.enableItem("turl", "twitter");
          Profile_form.enableItem("gurl", "google");
          Profile_form.enableItem("burl", "blogger");
          Profile_form.enableItem("purl", "pinerest");
          Profile_form.enableItem("fburl", "facebook");
		  Profile_form.enableItem("iurl", "instagram");
          return true;
        });
        join_form.setItemValue("twitterurl", self.dataStore.Profiles.rows[0].data[7]);
        Profile_form.disableItem("fburl", "facebook");
        Profile_form.disableItem("gurl", "google");
        Profile_form.disableItem("burl", "blogger");
        Profile_form.disableItem("purl", "pinerest");
		Profile_form.disableItem("iurl", "instagram");
        join_form.hideItem("facebookurl");
        join_form.hideItem("googleurl");
        join_form.hideItem("bloggerurl");
        join_form.hideItem("pineresturl");
		join_form.hideItem("instagramurl");
      }
      if (id == 'gurl') {
        win_join.attachEvent("onClose", function(win) {
          Profile_form.uncheckItem("gurl", "google");
          Profile_form.uncheckItem("fburl", "facebook");
          Profile_form.enableItem("turl", "twitter");
          Profile_form.enableItem("gurl", "google");
          Profile_form.enableItem("burl", "blogger");
          Profile_form.enableItem("purl", "pinerest");
          Profile_form.enableItem("fburl", "facebook");
		  Profile_form.enableItem("iurl", "instagram");
          return true;
        });
        join_form.setItemValue("googleurl", self.dataStore.Profiles.rows[0].data[8]);
        Profile_form.disableItem("fburl", "facebook");
        Profile_form.disableItem("turl", "twitter");
        Profile_form.disableItem("burl", "blogger");
        Profile_form.disableItem("purl", "pinerest");
		Profile_form.disableItem("iurl", "instagram");
        join_form.hideItem("twitterurl");
        join_form.hideItem("facebookurl");
        join_form.hideItem("bloggerurl");
        join_form.hideItem("pineresturl");
		join_form.hideItem("instagramurl");
        join_form.a("pineresturl");
      }
      if (id == 'burl') {
        win_join.attachEvent("onClose", function(win) {
          Profile_form.uncheckItem("burl", "blogger");
          Profile_form.enableItem("turl", "twitter");
          Profile_form.enableItem("gurl", "google");
          Profile_form.enableItem("burl", "blogger");
          Profile_form.enableItem("purl", "pinerest");
          Profile_form.enableItem("fburl", "facebook");
		  Profile_form.enableItem("iurl", "instagram");
          return true;
        });
        join_form.setItemValue("bloggerurl", self.dataStore.Profiles.rows[0].data[9]);
        Profile_form.disableItem("fburl", "facebook");
        Profile_form.disableItem("turl", "twitter");
        Profile_form.disableItem("gurl", "google");
        Profile_form.disableItem("purl", "pinerest");
		Profile_form.disableItem("iurl", "instagram");

        join_form.hideItem("twitterurl");
        join_form.hideItem("googleurl");
        join_form.hideItem("facebookurl");
        join_form.hideItem("pineresturl");
		join_form.hideItem("instagramurl");
      }
      if (id == 'purl') {
        win_join.attachEvent("onClose", function(win) {
          Profile_form.uncheckItem("purl", "pinerest");
          Profile_form.enableItem("turl", "twitter");
          Profile_form.enableItem("gurl", "google");
          Profile_form.enableItem("burl", "blogger");
          Profile_form.enableItem("purl", "pinerest");
          Profile_form.enableItem("fburl", "facebook");
		  Profile_form.enableItem("iurl", "instagram");
          return true;
        });
        join_form.setItemValue("pineresturl", self.dataStore.Profiles.rows[0].data[10]);
        Profile_form.disableItem("fburl", "facebook");
        Profile_form.disableItem("turl", "twitter");
        Profile_form.disableItem("gurl", "google");
        Profile_form.disableItem("burl", "blogger");
		Profile_form.disableItem("iurl", "instagram");
        join_form.hideItem("twitterurl");
        join_form.hideItem("googleurl");
        join_form.hideItem("bloggerurl");
        join_form.hideItem("facebookurl");
		join_form.hideItem("instagramurl");
      }
	  if (id == 'iurl') {
        win_join.attachEvent("onClose", function(win) {
          Profile_form.uncheckItem("iurl", "instagram");
          Profile_form.enableItem("turl", "twitter");
          Profile_form.enableItem("gurl", "google");
          Profile_form.enableItem("burl", "blogger");
          Profile_form.enableItem("iurl", "instagram");
          Profile_form.enableItem("fburl", "facebook");
		  Profile_form.enableItem("purl", "pinerest");
          return true;
        });
        join_form.setItemValue("instagramurl", self.dataStore.Profiles.rows[0].data[54]);
        Profile_form.disableItem("fburl", "facebook");
        Profile_form.disableItem("turl", "twitter");
        Profile_form.disableItem("gurl", "google");
        Profile_form.disableItem("burl", "blogger");
		Profile_form.disableItem("purl", "pinerest");
        join_form.hideItem("twitterurl");
        join_form.hideItem("googleurl");
        join_form.hideItem("bloggerurl");
        join_form.hideItem("facebookurl");
		join_form.hideItem("pineresturl");
      }
    });
    // Adding extra upload button for uploading photo on demand      \
    Profile_form.attachEvent("onButtonClick", function(name) {
      if (name == "uploadphoto") {
        Profile_form.addItem("fileuploads", {
          type: "block",
          list: [{
            type: "file",
            name: "form_file_1",
            style: "width:180px;"
          }, {
            type: "newcolumn"
          }, {
            type: "button",
            name: "form_input_1",
            value: "UPLOAD"
          }]
        }, 7);
      }
      // Adding extra upload button for uploading video on demand 
      if (name == "uploadvideo") {
        Profile_form.addItem("uploadvideo", {
          type: "block",
          list: [{
            type: "file",
            name: "form_file_1",
            style: "width:180px;"
          }, {
            type: "newcolumn"
          }, {
            type: "button",
            name: "form_input_1",
            value: "UPLOAD"
          }]
        }, 8);
      }
    });

    var json = self.dataStore;
    var Profiles = json.Profiles.rows;
    var Profiles_couple = json.Profiles_couple.rows;
    var Profiles_letter = json.Profiles_Letters.rows;
    var agency_flag = json.agency_flag.rows;
    var membership_id = json.membership_id.rows;
	var profilenumber_id = json.profilenumber.rows;
      //Profile_form.hideItem("flipbook_block");

    //Start - Membership restriction based membership type 

    if (membership_id == 25) {
      Profile_form.hideItem("form_label_5");
      //  Profile_form.hideItem("PHOTOS_block");    
      // Profile_form.hideItem("VIDEOS_block");
      Profile_form.hideItem("form_label_6");
      Profile_form.hideItem("homephoto");
      Profile_form.hideItem("home_videos");
      Profile_form.hideItem("form_label_11");
	  Profile_form.hideItem("profilenumber");

      Profile_form.hideItem("flipbook_block");
      Profile_form.hideItem("epub_block");
      Profile_form.hideItem("SOCIALINFO_block");
      Profile_form.hideItem("PLACE_YOUR_PROFILE_block");

      //Profile_form.hideItem("letters");
      Profile_form.hideItem("about_other");
      //Profile_form.hideItem("about_mother");
      Profile_form.hideItem("about_agency");
      Profile_form.hideItem("about_him");
      Profile_form.hideItem("about_her");
      Profile_form.hideItem("about_them");

    }

    if (membership_id == 23 || membership_id == 20 || membership_id == 18) {
      Profile_form.hideItem("SOCIALINFO_block");
	  Profile_form.hideItem("profilenumber");
    }

    //End - Membership restriction based membership type 

    if (agency_flag == false) {
      Profile_form.hideItem("approved");
    } else {
      Profile_form.hideItem("approval");
    }
    for (i in Profiles_letter) {
      letter_title = escape(Profiles_letter[i].label);
      //letter_title = letter_title.replace(/"/g, '\\"');
      //letter_title = letter_title.replace(/'/g, "\\'");
      var itemData = {
        type: "label",
        className: "lbl ui-state-default letter_" + Profiles_letter[i].id,
        //label: "<span style='color:#009DBC; text-decoration: underline; cursor:pointer;'  onclick='ProfilebuilderComponent.lettar(\"hhh\'ssss dd\")' >" + Profiles_letter[i].label.toUpperCase() + "</span>",
        label: '<span data-id="ara" style="color:#57B4A8; text-decoration: underline; cursor:pointer;"  onclick="ProfilebuilderComponent.lettar(\'' + letter_title + '\')">' + Profiles_letter[i].label.toUpperCase() + '</span>',
        style: "font-weight:normal;",
        name: (Profiles_letter[i].label.toUpperCase())
      };
      self.Profile_form_g.addItem('LETTERS_block', itemData, 7);
    }
    for (i in Profiles) {
      var profile_ID = Profiles[i].data[0];
      var profile_email = Profiles[i].data[1];
      var profile_fname = Profiles[i].data[2].toUpperCase();
      var Profile_state = Profiles[i].data[3];
      //  var Profile_pass = Profiles[i].data[4];
      var Profile_agency = Profiles[i].data[5];
      var profile_dear_birthParent = Profiles[i].data[26];
      if(Profiles[i].data[11] != '0000-00-00')
        var profile_age = Profiles[i].data[11];
      else
        var profile_age = '';
      var profile_waiting = Profiles[i].data[13];
      var profile_children = Profiles[i].data[14];
      var profile_faith = Profiles[i].data[15];
      var profile_childethnicity = Profiles[i].data[16];
      var profile_childage = Profiles[i].data[17];
      var profile_adoptiontype = Profiles[i].data[18];
      var profile_phnnum = (Profiles[i].data[19] == '0') ? '' : Profiles[i].data[19];
      var profile_streetaddress = Profiles[i].data[20];
      var profile_housestyle = Profiles[i].data[21];
      //var profile_noofbedrooms = (Profiles[i].data[22] == '0') ? '' : Profiles[i].data[22];
      //  var profile_noofbathrooms = (Profiles[i].data[23] == '0') ? '' : Profiles[i].data[23];
      //      var profile_yardsize = Profiles[i].data[24];
      var profile_neighbourhoodlike = Profiles[i].data[25];
      var profile_about_him = Profiles[i].data[27];
      var website_url = Profiles[i].data[28];
      var profile_couple = Profiles[i].data[29];
      var profile_childType = Profiles[i].data[30];
      var profile_special_needs = (Profiles[i].data[37]) ? Profiles[i].data[37] : 'yes';
      if (profile_special_needs == 'yes') {
        Profile_form.showItem("specialneedoptions");
        var profile_specialneedoptions = (Profiles[i].data[38]) ? Profiles[i].data[38] : '';
        Profile_form.setItemValue("specialneedoptions", profile_specialneedoptions);
      }
      var address1 = Profiles[i].data[31];
      var address2 = Profiles[i].data[32];
      var city = Profiles[i].data[33];
      var zip = Profiles[i].data[34];
	  var show_contact = Profiles[i].data[52];
      var region = Profiles[i].data[35];
      // alert(region);
      var profile_ChildGender = Profiles[i].data[39];
      var profile_childdesired = Profiles[i].data[40];
      var profile_BirthFatherStatus = Profiles[i].data[41];
      var profile_sex = Profiles[i].data[42];
      var single_ethnicity = Profiles[i].data[43];
      var single_education = Profiles[i].data[44];
      var single_religion = Profiles[i].data[45];
      var single_occupation = Profiles[i].data[46];
      var profile_pets = Profiles[i].data[47];
      var profile_relation = Profiles[i].data[48];
      var profile_family_struct = Profiles[i].data[49];

      var profile_region = Profiles[i].data[50];
      var profile_country = Profiles[i].data[51];
      if (membership_id != 25) {
        Profile_form.setItemLabel("about_him", "<span style='color: #57B4A8;text-decoration: underline; cursor:pointer;' onclick='ProfilebuilderComponent.lettar(\"HIM\",\"" + profile_fname + "\")';>LETTER ABOUT " + profile_fname + "</span>");

        Profile_form.hideItem("about_her");
        Profile_form.hideItem("about_them");
      }
      Profile_form.setItemValue("email_address", profile_email);
      Profile_form.setItemValue("first_name", profile_fname);
      Profile_form.setItemValue("state", Profile_state);
      if (profile_phnnum != '' && profile_phnnum != null ) {
        var res = profile_phnnum.substr(0, 3);
        var res1 = profile_phnnum.substr(3, 3);
        var res2 = profile_phnnum.substr(6, 4);
        var profile_phnnum = res + '-' + res1 + '-' + res2
      }
      Profile_form.setItemValue("phonenumber", profile_phnnum);
      if(profile_age != '')
      {
        var tempyear = profile_age.substring(0, 4);
        var tempmonth = profile_age.substring(5,7);
        var tempday = profile_age.substring(8,10);
        var profile_age = tempmonth + "-" + tempday + "-" + tempyear;
      }
      Profile_form.setItemValue("age", profile_age);
      Profile_form.setItemValue("waiting", profile_waiting);
      //Profile_form.setItemValue("noofchildren", profile_children);

      Profile_form.setItemValue("address1", address1);
      Profile_form.setItemValue("address2", address2);
      Profile_form.setItemValue("city", city);
      Profile_form.setItemValue("zip", zip);
	  Profile_form.setItemValue("show_contact", show_contact);

      //      if (Profile_form.getItemValue("noofchildren") > 0) {
      //        Profile_form.setItemValue("ctype", profile_childType);
      //        Profile_form.showItem("ctype");
      //      }
      Profile_form.setItemValue("faith", profile_faith);

      Profile_form.setItemValue("ethnicty_preference", profile_childethnicity);
      Profile_form.setItemValue("age_preference", profile_childage);
      Profile_form.setItemValue("adoptiontype", profile_adoptiontype);
      Profile_form.setItemValue("mailing", profile_streetaddress);
      Profile_form.setItemValue("neighbourhoodlike", profile_neighbourhoodlike);

      Profile_form.setItemValue("housestyle", profile_housestyle);
      //  Profile_form.setItemValue("noofbedrooms", profile_noofbedrooms);
      //  Profile_form.setItemValue("noofbathrooms", profile_noofbathrooms);
      //      Profile_form.setItemValue("yardsize", profile_yardsize);

      Profile_form.setItemValue("dear_birthParent", profile_dear_birthParent);
      Profile_form.setItemValue("describe_about_him", profile_about_him);
      Profile_form.setItemValue("specialneeds", profile_special_needs);
      if (website_url == '') {
        Profile_form.setItemValue("weburls", "URL");
      } else {
        Profile_form.setItemValue("weburls", website_url);
      }

      Profile_form.setItemValue("gender", profile_ChildGender);
      Profile_form.setItemValue("genderoffirst", profile_sex);
      Profile_form.setItemValue("occupationofsingle", single_occupation);



    }
	Profile_form.setItemValue("profilenumber", profilenumber_id);
	var ftr = self.dataStore["featured"];
	if( ftr != 24 && ftr != 15 && ftr != 14){
		Profile_form.hideItem("Bannerimage");
		Profile_form.hideItem("banDiv1");
		Profile_form.hideItem("banDiv2");
	}
    if (profile_couple == 0) {
      Profile_form.setItemLabel("profileflipbook", "<span style='color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_flipbook.png'> MY FLIPBOOK</span><span style =\"float:right;padding-top:6%\" ><a class='tooltip' title-text='Upload PDF' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>");
      Profile_form.setItemLabel("aboutcouple", "<span style='color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/icon_single.png'> ABOUT ME</span><span style =\"float:right;padding-top:6%\"><a class='tooltip' title-text='Details about you' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>");
      Profile_form.setItemLabel("abouthome", "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_home.png' /> MY HOME</span><span  style =\"float:right;padding-top:6%\"><a class='tooltip' title-text='Details about your home' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>");
      Profile_form.setItemLabel("agency", "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_agency.png' /> MY AGENCY</span><span style =\"float:right;padding-top:6%\" ><a class='tooltip' title-text='AGENCY' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>");
      Profile_form.setItemLabel("contact", "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_contact.png' /> MY CONTACT INFO</span><span style =\"float:right;padding-top:6%\"><a class='tooltip' title-text='Contact details' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>");
      Profile_form.setItemLabel("letters", "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_letters.png' /> MY LETTERS</span><span style =\"float:right;padding-top:6%\"><a class='tooltip' title-text='LETTERS' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>");
      Profile_form.setItemLabel("websites", "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/icon_website_profile.png' /> MY WEBSITE</span><span style =\"float:right;padding-top:6%\"><a class='tooltip' title-text='WEBSITE' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>");
      $('.abouthomespan').html('ABOUT MY HOME');
      Profile_form.hideItem("nameofcouples_sec");
      Profile_form.hideItem("coupleages");
      Profile_form.hideItem("coupleage");
	Profile_form.hideItem("couplecalendarbutton");
      Profile_form.hideItem("first_name_couple");
      Profile_form.setItemWidth("first_name", 240);
      Profile_form.setItemWidth("age", 205);


      Profile_form.hideItem("genderofsec");
      Profile_form.setItemWidth("genderoffirst", 240);
      Profile_form.hideItem("ethnicityofcouple");
      Profile_form.setItemWidth("ethnicityofsingle", 240);
      Profile_form.hideItem("educationofcouple");
      Profile_form.setItemWidth("educationofsingle", 240);
      Profile_form.hideItem("religionofcouple");
      Profile_form.setItemWidth("religionofsingle", 240);
      Profile_form.hideItem("occupationofcouple");
      Profile_form.setItemLabel("occupationofsingle", "WHAT IS YOUR OCCUPATION?");
      Profile_form.setItemLabel("Genderoftheusers", "<span style='font-size:12px;'>WHAT GENDER ARE YOU?</span>");
      Profile_form.setItemLabel("userethnicity", "<span style='font-size:12px;'>WHAT IS YOUR ETHNICITY?</span>");
      Profile_form.setItemLabel("usereducation", "<span style='font-size:12px;'>WHAT IS YOUR EDUCATION?</span>");
      Profile_form.setItemLabel("userreligion", "<span style='font-size:12px;'>WHAT IS YOUR RELIGION?</span>");
      Profile_form.setItemLabel("person2label","<span class='label2' style='font-size:14px;'>PERSONAL DETAILS</span>");
      $('.label2').parent().css('width','166px');
      // Profile_form.hideItem("relationship_Status");
      Profile_form.hideItem("person1label");
      //Profile_form.hideItem("person2label");

    } else {
      Profile_form.setItemLabel("profileflipbook", "<span style='color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_flipbook.png'> OUR FLIPBOOK</span><span style =\"float:right;padding-top:6%\"><a class='tooltip' title-text='Upload PDF' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>");
      Profile_form.setItemLabel("aboutcouple", "<span style='color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_about.png'> ABOUT US</span><span style =\"float:right;padding-top:6%\"><a class='tooltip' title-text='Details about you' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>");
      Profile_form.setItemLabel("abouthome", "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_home.png' /> OUR HOME</span><span style =\"float:right;padding-top:6%\"><a class='tooltip' title-text='Details about your home' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>");
      Profile_form.setItemLabel("agency", "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_agency.png' /> OUR AGENCY</span><span style =\"float:right;padding-top:6%\"><a class='tooltip' title-text='AGENCY' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>");
      Profile_form.setItemLabel("contact", "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_contact.png' /> OUR CONTACT INFO</span><span style =\"float:right;padding-top:6%\"><a class='tooltip' title-text='Contact details' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>");
      Profile_form.setItemLabel("letters", "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_letters.png' /> OUR LETTERS</span><span style =\"float:right;padding-top:6%\"><a class='tooltip' title-text='LETTERS' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>");
      Profile_form.setItemLabel("websites", "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/icon_website_profile.png' /> OUR WEBSITE</span><span style =\"float:right;padding-top:6%\"><a class='tooltip' title-text='WEBSITE' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>");


      Profile_form.hideItem("nameofcouples");
      Profile_form.hideItem("coupleages_sec");

      //  Profile_form.hideItem("nameofcouples_sec");
      //   Profile_form.hideItem("coupleages");
      //  Profile_form.setItemLabel("coupleages","<div style='color: #77787A;font-size: 10px;font-weight: bold;margin-bottom: 10px;margin-top: 10px;overflow: hidden;white-space: normal;'>asdasdasd</div>")
    }
    Profile_form.attachEvent("onfocus", function() {
      var defaulttem = Profile_form.getItemValue("weburls");
      if (defaulttem == 'URL') {
        Profile_form.setItemValue("weburls", '');
      }

    });
    Profile_form.attachEvent("onInputChange", function(name_input, value_input) {
      if (name_input == 'phonenumber') {
        var v = value_input;
        v = v.replace(/\D/g, "");
        v = v.substring(0, 10);
        v = v.replace(/^(\d{3})(\d)/g, "$1-$2");
        v = v.replace(/^(\d{3})\-(\d{3})(\d)/g, "$1-$2-$3");
        v = v.replace(/(\d)\-(\d{3})$/, "$1-$2");
        Profile_form.setItemValue(name_input, v);
      }

    });


    for (i in Profiles_couple) {
      var profile_fname_couple = Profiles_couple[i].data[1].toUpperCase();
      //var profile_fname_age = Profiles_couple[i].data[2];
      if(Profiles_couple[i].data[2] != '0000-00-00')
        var profile_fname_age = Profiles_couple[i].data[2];
      else
        var profile_fname_age = '';

      var profile_about_her = Profiles_couple[i].data[3];
      var couple_gender = Profiles_couple[i].data[4];
      var couple_Ethnicity = Profiles_couple[i].data[5];
      var couple_education = Profiles_couple[i].data[6];
      var couple_religion = Profiles_couple[i].data[7];
      var couple_occupation = Profiles_couple[i].data[8];
      // alert(couple_gender);
      //          Profile_form.setItemLabel("about_her", "LETTER ABOUT "+profile_fname_couple);
      if (membership_id != 25) {
        Profile_form.showItem("about_her");
        Profile_form.showItem("about_them");
        Profile_form.setItemLabel("about_her", "<span style='color: #57B4A8;text-decoration: underline; cursor:pointer;' onclick='ProfilebuilderComponent.lettar(\"HER\",\"" + profile_fname_couple + "\")';>LETTER ABOUT " + profile_fname_couple + "</span>");



      }

      Profile_form.setItemValue("first_name_couple", profile_fname_couple);
      if(profile_fname_age != '')
      {
        var tempyear = profile_fname_age.substring(0, 4);
        var tempmonth = profile_fname_age.substring(5,7);
        var tempday = profile_fname_age.substring(8,10);
        var profile_fname_age = tempmonth + "-" + tempday + "-" + tempyear;
      }
      Profile_form.setItemValue("coupleage", profile_fname_age);

      Profile_form.setItemValue("describe_about_her", profile_about_her);
      Profile_form.setItemValue("genderofsec", couple_gender);
      Profile_form.setItemValue("occupationofcouple", couple_occupation);


    }

    var agencies = Profile_form.getSelect("profile_agency");
    //  agencies.options.add(new Option("SELECT AN AGENCY", ""));

    var adoption_agency = json.Profiles_agency.rows;
    for (var i in adoption_agency) {
      var agencyid = adoption_agency[i].id;
      var agencytype = adoption_agency[i].data[1];
      agencies.options.add(new Option(agencytype, agencyid));
    }

    jQuery("[name='profile_agency']").val(Profile_agency).attr('selected', true);


    var ChildDesired = Profile_form.getSelect("child_desired");

    var Profiles_ChildDesireds = json.Profiles_ChildDesired.rows;
    for (var i in Profiles_ChildDesireds) {
      var ChildDesiredid = Profiles_ChildDesireds[i].id;
      ChildDesired.options.add(new Option(ChildDesiredid, ChildDesiredid));
    }
    Profile_form.setItemValue("child_desired", profile_childdesired);



    var BirthFatherStatus = Profile_form.getSelect("BirthFatherStatus");

    var Profiles_BirthFatherStatuss = json.Profiles_BirthFatherStatus.rows;
    for (var i in Profiles_BirthFatherStatuss) {
      var BirthFatherStatusid = Profiles_BirthFatherStatuss[i].id;
      BirthFatherStatus.options.add(new Option(BirthFatherStatusid, BirthFatherStatusid));
    }
    Profile_form.setItemValue("BirthFatherStatus", profile_BirthFatherStatus);


    var ethnicityofsingle = Profile_form.getSelect("ethnicityofsingle");
    var Profiles_Ethnicityofsingles = json.Profiles_Ethnicityofcouple.rows;
    for (var i in Profiles_Ethnicityofsingles) {
      var Ethnicityofsingleid = Profiles_Ethnicityofsingles[i].id;
      ethnicityofsingle.options.add(new Option(Ethnicityofsingleid, Ethnicityofsingleid));
    }
    Profile_form.setItemValue("ethnicityofsingle", single_ethnicity);

    var ethnicityofcouple = Profile_form.getSelect("ethnicityofcouple");
    var Profiles_Ethnicityofcouples = json.Profiles_Ethnicityofcouple.rows;
    for (var i in Profiles_Ethnicityofcouples) {
      var Ethnicityofcouplesid = Profiles_Ethnicityofcouples[i].id;

      ethnicityofcouple.options.add(new Option(Ethnicityofcouplesid, Ethnicityofcouplesid));
    }

    Profile_form.setItemValue("ethnicityofcouple", couple_Ethnicity);

    var educationofsingle = Profile_form.getSelect("educationofsingle");

    var Profiles_educationofsingles = json.Profiles_educationofcouple.rows;
    for (var i in Profiles_educationofsingles) {
      var Educationofsinglesid = Profiles_educationofsingles[i].id;

      educationofsingle.options.add(new Option(Educationofsinglesid, Educationofsinglesid));
    }

    Profile_form.setItemValue("educationofsingle", single_education);

    var educationofcouple = Profile_form.getSelect("educationofcouple");


    var Profiles_educationofcouples = json.Profiles_educationofcouple.rows;
    for (var i in Profiles_educationofcouples) {
      var Educationofcouplesid = Profiles_educationofcouples[i].id;

      educationofcouple.options.add(new Option(Educationofcouplesid, Educationofcouplesid));
    }
    Profile_form.setItemValue("educationofcouple", couple_education);


    var Religionsingle = Profile_form.getSelect("religionofsingle");

    var Profiles_Religionsingles = json.Profiles_ReligionCouple.rows;
    for (var i in Profiles_Religionsingles) {
      var Religionsinglesid = Profiles_Religionsingles[i].id;

      Religionsingle.options.add(new Option(Religionsinglesid, Religionsinglesid));
    }

    Profile_form.setItemValue("religionofsingle", single_religion);


    var religionofcouple = Profile_form.getSelect("religionofcouple");

    var Profiles_ReligionCouples = json.Profiles_ReligionCouple.rows;
    for (var i in Profiles_ReligionCouples) {
      var ReligionCouplesid = Profiles_ReligionCouples[i].id;

      religionofcouple.options.add(new Option(ReligionCouplesid, ReligionCouplesid));
    }

    Profile_form.setItemValue("religionofcouple", couple_religion);


    var Profiles_Regions = json.Profiles_Region.rows;

    var Regions = Profile_form.getSelect("region_list");
    Regions.options.add(new Option("--Select--", ""));
    for (var i in Profiles_Regions) {
      var regionid = Profiles_Regions[i].id;

      Regions.options.add(new Option(regionid, regionid));
    }
    Profile_form.setItemValue("region_list", profile_region);

    var Profiles_Countrys = json.Profiles_Country.rows;

    var Countrys = Profile_form.getSelect("country_list");
    Countrys.options.add(new Option("--Select--", ""));
    for (var i in Profiles_Countrys) {
      var Countryskey = Profiles_Countrys[i].data[0];
      var Countrysvalue = Profiles_Countrys[i].data[1];
      var Countrysname = Countryskey.replace(/[^a-zA-Z ]/g, "");
      Countrys.options.add(new Option(Countrysname, Countrysvalue));
    }
    Profile_form.setItemValue("country_list", profile_country);

    var Profiles_pets = json.Profiles_pet.rows;

    var pets = Profile_form.getSelect("pets");

    for (var i in Profiles_pets) {
      var petsid = Profiles_pets[i].id;
      pets.options.add(new Option(petsid, petsid));
    }

    Profile_form.setItemValue("pets", profile_pets);

    var Profiles_relationship = json.Profiles_relationship.rows;

    var relationships = Profile_form.getSelect("relationship_Status");

    for (var i in Profiles_relationship) {
      var relsid = Profiles_relationship[i].id;
      relationships.options.add(new Option(relsid, relsid));
    }
    Profile_form.setItemValue("relationship_Status", profile_relation);



    var Profiles_familystruct = json.Profiles_familystruct.rows;

    var family_structures = Profile_form.getSelect("family_structure");

    for (var i in Profiles_familystruct) {
      var familyds = Profiles_familystruct[i].id;
      family_structures.options.add(new Option(familyds, familyds));
    }
    Profile_form.setItemValue("family_structure", profile_family_struct);


    var profile_states = json.Profiles_State.rows;
    var states = Profile_form.getSelect("state_list");

    for (var i in profile_states) {
      var stateid = profile_states[i].id;
      states.options.add(new Option(stateid, stateid));
    }

    jQuery("[name='state_list']").val(Profile_state).attr('selected', true);
    //populate no.of childrens
    var profiles_children = json.Profiles_Children.rows;
    var noofchildrens = Profile_form.getSelect("noofchildren");

    for (var cnt in profiles_children) {
      var childrensid = profiles_children[cnt].id;
      var childrensval = profiles_children[cnt].data[0];
      noofchildrens.options.add(new Option(childrensval, childrensid));
    }
    jQuery("[name='noofchildren']").val(profile_children).attr('selected', true);
    if (Profile_form.getItemValue("noofchildren") > 0) {
      Profile_form.setItemValue("ctype", profile_childType);
      Profile_form.showItem("ctype");
    }
    //populate no.of childrens



    if (region != 'Non US') {
      Profile_form.hideItem("state");

    } else {

      Profile_form.hideItem("state_list");
    }

    if (agency_flag == false) {
      dhtmlx.alert("When you have completed your changes press <img src=\"templates/tmpl_par/images/submitchanges.png\"> or the <img src=\"templates/tmpl_par/images/ico_home.png\"> to return without saving. All changes will be sent to your agency for review. Upon approval the changes will be live");
    } else {
		$('.chbx0').css('margin-top', '4px');
		$('.chbx1').css('margin-top', '4px');		
      dhtmlx.alert("When you have completed your changes press <img src=\"templates/tmpl_par/images/submitapproval.png\"> or the <img src=\"templates/tmpl_par/images/ico_home.png\"> to return without saving. Any changes you submit will be live immediately");
	  
	  var nickname = json.Profiles.rows[0].data[36];
	 // $('#fast_fact').html("<a target='blank' href='"+nickname+"/fastfact' >Fast Fact</a>");
	  
    }
    // creating a profile badge code 
    var Profiles_badge = json.Profiles_badge.rows;
    Profile_form.setItemValue("profilebadge", Profiles_badge);

    var flip_flag = json.flip_link.rows;    
    var epub_flag = json.epub_link.rows;
   
    
    $('.flip_status').click(function(){
      window.open(flip_flag,"_blank");
    })
    if(!flip_flag)
    {
      $('.flip_status').remove();
    }
    $('.epub_status').click(function(){
      window.open(epub_flag,"_blank");
    })
    if(!epub_flag)
    {
      $('.epub_status').remove();
    }
    Profile_form.attachEvent("onButtonClick", function(name, command) {
      //console.log(self.isUploading);
      if (self.isUploading) {
        dhtmlx.message({
          type: "alert-error",
          text: "Your file is being uploaded please wait...."
        })
        return;
      }
      self._profile_save(uid, Profile_form, name, json, profile_ID);

    });
    $('.ui-state-default').css("background", "white");
    $('.ui-state-default').css("border", "#8a8c8f");
    self._make_letter_sortable(uid, profile_ID);

  },
  _make_letter_sortable: function(uid, profile_ID) {
    var self = this;
    var main_div = $('.my-first-dev').parent();

    if (self.dataStore["Profiles_Letters_Sort"].rows.length) {
      var letters = main_div.find('.ui-state-default').remove();
      var sorted_letters = [];
      //loop throught all sorted lsetters
      $.each(self.dataStore["Profiles_Letters_Sort"].rows, function(index, obj) {
        //loop through all letters
        $.each(letters, function(i, letter_obj) {
          var letr = $(letter_obj);
          if (letr.hasClass(obj.label)) {
            sorted_letters.push(letter_obj);
          }
        });

      });

      main_div.append(sorted_letters);
      $('.my-first-dev').parent().find('.ui-state-default').find('span').css('color','#57B4A8');
    }

    // if (self.dataStore["Profiles_Letters_Sort"].rows.length) {
    //   var letters = main_div.find('.ui-state-default').remove();

    //   var sorted_letters = [];
    //    var flag = 0;

    //   $.each(self.dataStore["Profiles_Letters_Sort"].rows, function(index, obj) {

    //     if (flag == obj.order_by) {
    //       $.each(letters, function(i, letter_obj) {
    //         var letr = $(letter_obj);
    //         console.log(obj.label);

    //         if (letr.hasClass(obj.label)) {
    //           sorted_letters.push(letter_obj);
    //           delete letter_obj;
    //         }
    //       });
    //       delete obj;
    //       flag++;
    //     }
    //   });



    //   // console.log(sorted_letters);
    //    main_div.append(sorted_letters);
    // }


    main_div.sortable({
      items: ".ui-state-default"
    }).on("sortupdate", function(event, ui) {
      var obj = {};
	  var a = '';
      $(this).find('.ui-state-default').each(function(i) {
		a = $(this).attr('class').split(' ');
		//console.log(a[3]);
		obj[a[3]] = i;
      });
      /*
      $.ajax({
        type: 'GET',
        url: location.origin + "/ProfilebuilderComponent/processors/letter_sort.php",
        data: "profile_ID=" + profile_ID + "&sort_obj=" + JSON.stringify(obj)
      });
      */
      getstr = "?profile_ID=" + profile_ID + "&sort_obj=" + JSON.stringify(obj);
      dhtmlxAjax.post("https://www.parentfinder.com/ProfilebuilderComponent/processors/letter_sort.php" + getstr, '', function(loader){});

    });
    $('.my-first-dev').parent().find('.ui-state-default').css('border','none');
    $('.my-first-dev').parent().find('.ui-state-default').css('background','none');
  },
  _change_date_format: function(tempdate){
    var self = this;
    var resdate = tempdate.split("-");
          
    tempmonth = resdate[0];
    tempday = resdate[1];
    tempyear = resdate[2];
    tempdate = tempyear+"-"+tempmonth+"-"+tempday;
    return tempdate;
  },
  _profile_save: function(uid, Profile_form, name, json, profile_ID) {
    var self = this;
    var name_filter = /^[a-zA-Z]*[a-zA-Z]+[a-zA-Z ]*$/;
    if (Profile_form.getCheckedValue('specialneeds') == 'no') {
      Profile_form.setItemValue("specialneedoptions", '');
    }
    var profile_specialneedoptions = Profile_form.getItemValue("specialneedoptions");
    if (!name_filter.test(Profile_form.getItemValue("first_name"))) {
      dhtmlx.message({
        type: "alert-error",
        text: "Name field cannot be blank and must be alphabets only"
      })
      return false;
    }
    if (!(Profile_form.isItemHidden('first_name_couple'))) {
      if (!name_filter.test(Profile_form.getItemValue("first_name_couple"))) {
        dhtmlx.message({
          type: "alert-error",
          text: "Name field cannot be blank and must be alphabets only"
        })
        return false;
      }
    }

    //if (Profile_form.getItemValue("email_address") != '') {

    var email = Profile_form.getItemValue("email_address");
    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

    if (!filter.test(email)) {

      dhtmlx.message({
        type: "alert-error",
        text: "You must enter valid email"
      })
      return false;
    }

    //}
    /*if (Profile_form.getItemValue("phonenumber") != '') {

      var phonenumber = Profile_form.getItemValue("phonenumber");
      var filter = /^\d{10}$/;
      if (!filter.test(phonenumber)) {
        dhtmlx.message({
          type: "alert-error",
          text: "You must enter valid phonenumber"
        })
        return false;
      }

    }*/



    if (Profile_form.getItemValue("newpassword") != '') {

      var newpassword = Profile_form.getItemValue("newpassword");
      var confirmpassword = Profile_form.getItemValue("confirmpassword");
      if (newpassword != confirmpassword) {

        dhtmlx.message({
          type: "alert-error",
          text: "The new password and confirmation password do not match."
        })
        return false;
      }

    }
    if (Profile_form.getItemValue("weburls") == 'URL') {
      Profile_form.setItemValue("weburls", '');
    }
    if (Profile_form.getItemValue("weburls") != '') {

      var website = Profile_form.getItemValue("weburls");

      //   $('#txturl').val();
      var re = /(http(s)?:\\)?([\w-]+\.)+[\w-]+[.com|.in|.org]+(\[\?%&=]*)?/;
      if (!re.test(website)) {
        dhtmlx.message({
          type: "alert-error",
          text: "You must enter valid website url"
        })
        return false;
      }

    }

    if (Profile_form.getItemValue("age") != '') {

      var singleage = Profile_form.getItemValue("age");
      var res = singleage.split("-");
      dateformat = res.length;
      
      singleageyear = res[2];
      singleagemonth = res[0];
      singleageday = res[1];
      
      var d = new Date();
      var year = d.getFullYear();
      var tomonth = d.getMonth();
      var todate = d.getDate();
      
      if(singleageyear == year){
        if(singleagemonth >= tomonth){
          if(singleageday >= todate){
            dhtmlx.message({
              type: "alert-error",
              text: "Entered date of birth is greaterthan today's date"
            })
            return false; 
          }
        }
      }
      if(dateformat != 3){
        dhtmlx.message({
          type: "alert-error",
          text: "Date format is mm-dd-yyyy"
        })
        return false;
      }
      if (singleageyear == '' || singleagemonth == '' || singleageday == '' || singleageyear.length < 4 || singleageyear > year || singleageyear < 1900 || singleagemonth < 0 || singleagemonth > 12 || singleageday < 0 || singleageday > 31) {

        dhtmlx.message({
          type: "alert-error",
          text: "You must enter valid date"
        })
        return false;
      }
      if(singleagemonth == 1 || singleagemonth == 3 || singleagemonth == 5 || singleagemonth ==7 || singleagemonth == 8 || singleagemonth == 10 || singleagemonth == 12 ){
        if(singleageday > 31){
          dhtmlx.message({
            type: "alert-error",
            text: "You must enter valid date"
          })
        return false;
        }
      }
      if(singleagemonth == 4 || singleagemonth == 6 || singleagemonth == 9 || singleagemonth == 11 )
      {
        if(singleageday > 30){
            dhtmlx.message({
            type: "alert-error",
            text: "You must enter valid date"
          })
          return false;
        }
      }
      if(singleagemonth == 2){
        if((singleageyear%4) == 0){
          if(singleageday > 29)
          {
            dhtmlx.message({
              type: "alert-error",
              text: "You must enter valid date"
            })
            return false;
          }
        }
        else{
          if(singleageday > 28){
            dhtmlx.message({
              type: "alert-error",
              text: "You must enter valid date"
            })
            return false;
          }
        }
      }

    }


    if (Profile_form.getItemValue("coupleage") != '') {

      var tempcoupleage = Profile_form.getItemValue("coupleage");
      var tmpcoupleage = tempcoupleage.split("-");
      dateformat = tmpcoupleage.length;
      tempcoupleyear = tmpcoupleage[2];
      tempcouplemonth = tmpcoupleage[0];
      tempcoupleday = tmpcoupleage[1];
      // tempcouplemonth = tempcoupleage.substring(0,2);
      // tempcoupleday = tempcoupleage.substring(3,5);
      // tempcoupleyear = tempcoupleage.substring(6,10);

      if(dateformat != 3){
        dhtmlx.message({
          type: "alert-error",
          text: "Date format is mm-dd-yyyy"
        })
        return false;
      }
      var d = new Date();
      var year = d.getFullYear();
      var tomonth = d.getMonth();
      var todate = d.getDate();
      
      if(tempcoupleyear == year){
        if(tempcouplemonth >= tomonth){
          if(tempcoupleday >= todate){
            dhtmlx.message({
              type: "alert-error",
              text: "Entered date of birth is greaterthan today's date"
            })
            return false; 
          }
        }
      }

      if (tempcoupleyear == '' || tempcouplemonth == '' || tempcoupleday == '' || tempcoupleyear.length < 4 || tempcoupleyear > year || tempcoupleyear < 1900|| tempcouplemonth < 0 || tempcouplemonth > 12 || tempcoupleday < 0 || tempcoupleday > 31) {
        dhtmlx.message({
          type: "alert-error",
          text: "You must enter valid date"
        })
        return false;
      }
      if(tempcouplemonth == 1 || tempcouplemonth == 3 || tempcouplemonth == 5 || tempcouplemonth ==7 || tempcouplemonth == 8 || tempcouplemonth == 10 || tempcouplemonth == 12 ){
        if(tempcoupleday > 31){
          dhtmlx.message({
            type: "alert-error",
            text: "You must enter valid date"
          })
        return false;
        }
      }
      if(tempcouplemonth == 4 || tempcouplemonth == 6 || tempcouplemonth == 9 || tempcouplemonth == 11 )
      {
        if(tempcoupleday > 30){
          dhtmlx.message({
            type: "alert-error",
            text: "You must enter valid date"
          })
          return false;
        }
      }
      if(tempcouplemonth == 2){
        if((tempcoupleyear%4) == 0){
          if(tempcoupleday > 29){
            dhtmlx.message({
              type: "alert-error",
              text: "You must enter valid date"
            })
            return false;
          }
        }
        else{
          if(tempcoupleday > 28){
            dhtmlx.message({
              type: "alert-error",
              text: "You must enter valid date"
            })
            return false;
          }
        }
      }


    }


    if (Profile_form.getItemValue("zip") != '') {

      var zipcode = Profile_form.getItemValue("zip");

      if (/^[a-zA-Z0-9- ]*$/.test(zipcode) == false) {

        dhtmlx.message({
          type: "alert-error",
          text: "You must enter valid zip"
        })
        return false;
      }

    }
    if (Profile_form.getItemValue("address1") != '') {

      var address1 = Profile_form.getItemValue("address1");

      if (/^[a-zA-Z0-9- ]*$/.test(address1) == false) {

        dhtmlx.message({
          type: "alert-error",
          text: "You must enter valid address"
        })
        return false;
      }

    }
    if (Profile_form.getItemValue("address2") != '') {

      var address2 = Profile_form.getItemValue("address2");

      if (/^[a-zA-Z0-9- ]*$/.test(address2) == false) {

        dhtmlx.message({
          type: "alert-error",
          text: "You must enter valid address2"
        })
        return false;
      }

    }
    if (Profile_form.getItemValue("city") != '') {

      var city = Profile_form.getItemValue("city");

      if (/^[a-zA-Z0-9- ]*$/.test(city) == false) {

        dhtmlx.message({
          type: "alert-error",
          text: "You must enter valid address"
        })
        return false;
      }

    }
    if (Profile_form.getItemValue("phonenumber") != '') {

      var phonenumber = Profile_form.getItemValue("phonenumber");
      var filter = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;

      if (phonenumber.length != 12) {

        dhtmlx.message({
          type: "alert-error",
          text: "You must enter valid phone number <br/> (000-000-0000)"
        })
        return false;
      }

    }

    //    if (Profile_form.getItemValue("yardsize") != '') {
    //      var yardValue = Profile_form.getItemValue("yardsize");
    //      var filter = /^[a-zA-Z0-9 ]{0,11}$/;
    //      if (!filter.test(yardValue)) {
    //
    //        dhtmlx.message({
    //          type: "alert-error",
    //          text: "Yard size should be 11 characters maximum and alphanumeric"
    //        })
    //        return false;
    //      }
    //    }
    if (Profile_form.getItemValue("zip") != '') {
      var zipValue = Profile_form.getItemValue("zip");
      var filter = /^[0-9]{0,5}$/;
      if (!filter.test(zipValue)) {

        dhtmlx.message({
          type: "alert-error",
          text: "ZIP should be 5 characters maximum and numeric "
        })
        return false;
      }
    }
    //new code
    var Pro_ethnicty_preference = Profile_form.getItemValue("ethnicty_preference");
    var Pro_age_preference = Profile_form.getItemValue("age_preference");
    var pro_specialneeds = Profile_form.getCheckedValue("specialneeds");

    // Getting values entered in form 
    var Pro_email = Profile_form.getItemValue("email_address");
    var pro_name = Profile_form.getItemValue("first_name");
    var pro_state = Profile_form.getItemValue("state");
    var pro_couple_name = Profile_form.getItemValue("first_name_couple");
    var pro_agency = Profile_form.getItemValue("profile_agency");
    var pro_password = Profile_form.getItemValue("newpassword");
    var pro_state = Profile_form.getItemValue("state_list");
    var temppro_age = Profile_form.getItemValue("age");
    var pro_age = self._change_date_format(temppro_age);
    //console.log(pro_age);
    //var pro_age = Profile_form.getItemValue("age");
    var temppro_coupleage = Profile_form.getItemValue("coupleage"); 
    //console.log(temppro_coupleage);
    var pro_coupleage = self._change_date_format(temppro_coupleage);
    //console.log(pro_coupleage);
    var pro_waiting = Profile_form.getItemValue("waiting");
    var pro_noofchildren = Profile_form.getItemValue("noofchildren");
    var pro_childtype = Profile_form.getItemValue("ctype");
    var pro_faith = Profile_form.getItemValue("faith");

    var pro_housestyle = Profile_form.getItemValue("housestyle");
    //  var pro_bedrooms = Profile_form.getItemValue("noofbedrooms");
    // var pro_bathrooms = Profile_form.getItemValue("noofbathrooms");
    //    var pro_yardsize = Profile_form.getItemValue("yardsize");
    var pro_neighbourhood = Profile_form.getItemValue("neighbourhoodlike");

    var pro_adoptiontype = Profile_form.getItemValue("adoptiontype");
    // var pro_specialneeds_yes = Profile_form.getItemValue("specialneedsyes","YES");
    //var pro_specialneeds_no = Profile_form.getItemValue("specialneedsno","NO");
    var pro_phonenumber = Profile_form.getItemValue("phonenumber");
    var pro_mailing = Profile_form.getItemValue("mailing");

    //        var profile_dear_birthParent = Profile_form.getItemValue("dear_birthParent");
    //        var profile_about_him =  Profile_form.getItemValue("describe_about_him");
    //        var profile_about_her = Profile_form.getItemValue("describe_about_her");
    //        var agency_letter = Profile_form.getItemValue("agency_letter");
    //        var about_them = Profile_form.getItemValue("about_them");
    //        var other = Profile_form.getItemValue("other");

    var web_url = Profile_form.getItemValue("weburls");
    var web_url = Profile_form.getItemValue("weburls");

    var pro_child_desired = Profile_form.getItemValue("child_desired");
    var pro_birthfatherstatus = Profile_form.getItemValue("BirthFatherStatus");
    var pro_gender = Profile_form.getItemValue("gender");
    var pro_gender_first = Profile_form.getItemValue("genderoffirst");
    var pro_gender_sec = Profile_form.getItemValue("genderofsec");
    var pro_ethnicity_first = Profile_form.getItemValue("ethnicityofsingle");
    var pro_ethnicity_sec = Profile_form.getItemValue("ethnicityofcouple");
    var pro_education_first = Profile_form.getItemValue("educationofsingle");
    var pro_education_sec = Profile_form.getItemValue("educationofcouple");
    var pro_religion_first = Profile_form.getItemValue("religionofsingle");
    var pro_religion_sec = Profile_form.getItemValue("religionofcouple");
    var pro_occupation_first = Profile_form.getItemValue("occupationofsingle");
    var pro_occupation_sec = Profile_form.getItemValue("occupationofcouple");
    var pro_pets = Profile_form.getItemValue("pets");
    var pro_relationship_Status = Profile_form.getItemValue("relationship_Status");
    var pro_family_structure = Profile_form.getItemValue("family_structure");
    var pro_region = Profile_form.getItemValue("region_list");
    var pro_country = Profile_form.getItemValue("country_list");


    if (Profile_form.validateItem("age") == false) {
      return false;
    }
    if (Profile_form.validateItem("coupleage") == false) {
      return false;
    }
    if (Profile_form.validateItem("noofchildren") == false) {
      return false;
    }

    if (Profile_form.validateItem("occupationofsingle") == false) {
      dhtmlx.message({
        type: "alert-error",
        text: "Occupation should be maximum 50 characters only"
      })
      return false;
    }
    if (Profile_form.validateItem("occupationofcouple") == false) {
      dhtmlx.message({
        type: "alert-error",
        text: "Occupation should be maximum 50 characters only"
      })
      return false;
    }
    if (Profile_form.validateItem("profilenumber") == false) {
      dhtmlx.message({
        type: "alert-error",
        text: "Profile Number should be numeric (YY_XXXX)"
      })
      return false;
    }
    var address1 = Profile_form.getItemValue("address1");
    var address2 = Profile_form.getItemValue("address2");
    var city = Profile_form.getItemValue("city");
    var zip = Profile_form.getItemValue("zip");
	var show_contact = Profile_form.getItemValue("show_contact");
	var profilenumber = Profile_form.getItemValue("profilenumber");
    if (name == "approval") {

      var poststr = "profileID=" + profile_ID + "&profileemail=" + Pro_email + "&profilename=" + pro_name + "&profilestate=" + pro_state + "&profilecouplename=" + pro_couple_name + "&profileagency=" + pro_agency + "&profileage=" + pro_age + "&profilecoupleage=" + pro_coupleage + "&profilewaiting=" + pro_waiting + "&noofchildren=" + pro_noofchildren + "&ChildType=" + pro_childtype + "&profilefaith=" + pro_faith + "&profilehousestyle=" + pro_housestyle + "&profilebedrooms=" + '' + "&profilebathrooms=" + '' + "&profileyard=" + '' + "&profileneighbourhood=" + pro_neighbourhood + "&profileadoptiontype=" + pro_adoptiontype + "&pspecialneeds=" + pro_specialneeds //+ "&pspecialneeds_no="  + pro_specialneeds_no
        + "&profilephone=" + pro_phonenumber + "&parofileaddress=" + pro_mailing + "&ethnicty_preference=" + Pro_ethnicty_preference + "&age_preference=" + Pro_age_preference + "&weburls=" + web_url + "&address1=" + address1 + "&address2=" + address2 + "&city=" + city + "&zip=" + zip + "&show_contact=" + show_contact + "&profile_specialneedoption=" + profile_specialneedoptions + "&childdesired=" + pro_child_desired + "&childgender=" + pro_gender + "&birthfatherstatus=" + pro_birthfatherstatus + "&genderfirst=" + pro_gender_first + "&gendersec=" + pro_gender_sec + "&ethnicityfirst=" + pro_ethnicity_first + "&ethnicitysec=" + pro_ethnicity_sec + "&educationirst=" + pro_education_first + "&educationsec=" + pro_education_sec + "&religionfirst=" + pro_religion_first + "&religionsec=" + pro_religion_sec + "&occupationfirst=" + pro_occupation_first + "&occupationsec=" + pro_occupation_sec + "&pets=" + pro_pets + "&relationship_Status=" + pro_relationship_Status + "&family_structure=" + pro_family_structure + "&profile_region=" + pro_region + "&profile_country=" + pro_country + "&profilenumber=" + profilenumber;
      // Inserting values to database
      dhtmlxAjax.post(self.configuration[uid].application_path + "processors/insert_profile_info.php", poststr, function(loader) {

        var returnval = JSON.parse(loader.xmlDoc.responseText);
        /* dhtmlx.confirm({
          type: "confirm",
          text: "Your changes are being currently reviewed by your Agency",
          ok: "My profile",
          callback: function(result) {
            if (result == true) {
              window.location.href = returnval.user_redirection;
            }
          }
        });*/
        dhtmlx.alert("Your changes are being currently reviewed by your Agency", function(result) {
          if (result == true) {
            window.location.href = returnval.user_redirection;
          }
        });
        //   window.location.href = returnval.user_redirection;
        // dhtmlx.message("Your changes are sent for approval.");
        // window.location.href = returnval.user_redirection;


      });
      //
    }

    if (name == "approved") {

      var poststr = "profileID=" + profile_ID + "&profileemail=" + Pro_email + "&profilename=" + pro_name + "&profilestate=" + pro_state + "&profilecouplename=" + pro_couple_name + "&profileagency=" + pro_agency + "&profileage=" + pro_age + "&profilecoupleage=" + pro_coupleage + "&profilewaiting=" + pro_waiting + "&noofchildren=" + pro_noofchildren + "&ChildType=" + pro_childtype + "&profilefaith=" + pro_faith + "&profilehousestyle=" + pro_housestyle + "&profilebedrooms=" + '' + "&profilebathrooms=" + '' + "&profileyard=" + '' + "&profileneighbourhood=" + pro_neighbourhood + "&profileadoptiontype=" + pro_adoptiontype + "&pspecialneeds=" + pro_specialneeds //+ "&pspecialneeds_no="  + pro_specialneeds_no
        + "&profilephone=" + pro_phonenumber + "&parofileaddress=" + pro_mailing + "&ethnicty_preference=" + Pro_ethnicty_preference + "&age_preference=" + Pro_age_preference + "&weburls=" + web_url + "&address1=" + address1 + "&address2=" + address2 + "&city=" + city + "&zip=" + zip + "&show_contact=" + show_contact + "&profile_specialneedoption=" + profile_specialneedoptions + "&childdesired=" + pro_child_desired + "&childgender=" + pro_gender + "&birthfatherstatus=" + pro_birthfatherstatus + "&genderfirst=" + pro_gender_first + "&gendersec=" + pro_gender_sec + "&ethnicityfirst=" + pro_ethnicity_first + "&ethnicitysec=" + pro_ethnicity_sec + "&educationirst=" + pro_education_first + "&educationsec=" + pro_education_sec + "&religionfirst=" + pro_religion_first + "&religionsec=" + pro_religion_sec + "&occupationfirst=" + pro_occupation_first + "&occupationsec=" + pro_occupation_sec + "&pets=" + pro_pets + "&relationship_Status=" + pro_relationship_Status + "&family_structure=" + pro_family_structure + "&profile_region=" + pro_region + "&profile_country=" + pro_country + "&profilenumber=" + profilenumber;
      // Inserting values to database
      dhtmlxAjax.post(self.configuration[uid].application_path + "processors/insert_profile_info.php", poststr, function(loader) {

        var returnval = JSON.parse(loader.xmlDoc.responseText);
        /*dhtmlx.confirm({
          type: "confirm",
          text: "Your changes are live",
          ok: "My profile",
          callback: function(result) {
            if (result == true) {
              window.location.href = returnval.user_redirection;
            }
          }
        });*/
        dhtmlx.alert("Your changes are live", function(result) {
          if (result == true) {
            window.location.href = returnval.user_redirection;
          }
        });
        //  window.location.href = returnval.user_redirection;
        // dhtmlx.message("Your changes are sent for approval.");
        // window.location.href = returnval.user_redirection;


      });
      //
    }
  },
  init: function(model) {
    var self = this;
    self.model = model;
  },
  start: function(configuration) {
    var self = this;
    self.uid = configuration.uid;

    if ((typeof configuration.uid === 'undefined') || (configuration.uid.length === 0)) {
      dhtmlx.message({
        type: "error",
        text: "uid is missing"
      });
      return;
    }

    if ((typeof configuration.application_path === 'undefined') || (configuration.application_path.length === 0)) {
      dhtmlx.message({
        type: "error",
        text: "application_path is missing"
      });
      return;
    }

    if ((typeof configuration.dhtmlx_codebase_path === 'undefined') || (configuration.dhtmlx_codebase_path.length === 0)) {
      dhtmlx.message({
        type: "error",
        text: "dhtmlx_codebase_path is missing"
      });
      return;
    }

    window.dhx_globalImgPath = configuration.dhtmlx_codebase_path + "imgs/";
    dhtmlx.skin = self.model.globalSkin || "dhx_skyblue";

    configuration["icons_path"] = "icons/";
    self.configuration[self.uid] = configuration;

    self._loadData(self.uid, function() {
      self._form(self.uid);
    });



    //self._form(self.uid);
  }
}
ProfilebuilderComponent.init(ProfilebuilderComponent_Model);