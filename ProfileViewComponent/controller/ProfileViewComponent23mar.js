/***********************************************************************
 * Name:    Prashanth A
 * Date:    11/11/2013
 * Purpose: Creating a family profile builder page
 ***********************************************************************/
var ProfileViewComponent = {
  uid: null,
  form: [],
  configuration: [],
  dataStore: [],
  getUrlVars: function() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) {
      vars[key] = value;
    });
    return vars;
  },
  loadjscssfile: function(filename, filetype) {
    var self = this;
    if (filetype == "js") { //if filename is a external JavaScript file
      var fileref = document.createElement('script')
      fileref.setAttribute("type", "text/javascript")
      fileref.setAttribute("src", filename)
    } else if (filetype == "css") { //if filename is an external CSS file
      var fileref = document.createElement("link")
      fileref.setAttribute("rel", "stylesheet")
      fileref.setAttribute("type", "text/css")
      fileref.setAttribute("href", filename)
    }
    if (typeof fileref != "undefined")
      document.getElementsByTagName("head")[0].appendChild(fileref)
  },
  _loadPhoto: function(uid) {
    if (document.getElementById("sliderFrame")) {
      var self = this,
        count = 0,
        flag = self.configuration[self.uid].slider,
        postStr = "approve=1&from=" + flag;


      dhtmlxAjax.post(self.configuration[uid].application_path + "processors/getphotosvideos.php?p=edit&id=" + self.getUrlVars()["id"] + "&type=" + self.configuration[uid].type, postStr, function(loader) {
        var json = JSON.parse(loader.xmlDoc.responseText),
          photo = '',
          photoSub = '',
          slides = '<div id="photo_slider" class="flexslider"><ul class="slides"> ';
        thumbs = '<div id="photo_slider_thumb" class="flexslider"><ul class="slides">';

        if (json.status == "success") {
          for (i in json.data) {
            var profile_memberimage = json.data[i];
            slides += '<li><img  style="max-height:418px" src="' + json.data[i] + '" alt="ParentFinder"/></li>';
            thumbs += '<li><img  src="' + json.bData[i] + '" alt="parentFinder"/></li>'
            // document.getElementById("slider").innerHTML += json.data[i];
            // document.getElementById("thumbs").innerHTML += json.bData[i];
            // document.getElementById("slider").innerHTML += '<div><img u="image" src="' + json.data[i] + '" /><img u="thumb" src="' + json.bData[i] + '" /></div>'
          }
          slides += '</ul></div>';
          thumbs += '</ul></div>';

          var html = slides + thumbs;

          document.getElementById('sliderFrame').innerHTML = html;
          $('#photo_slider_thumb').flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: false,
            slideshow: false,
            itemWidth: 116,
            itemMargin: 10,
            asNavFor: '#photo_slider',
            before: function() {
              // $("#video_player .flex-active-slide").html($("#video_player .flex-active-slide").html())
              //console.log($("#video_player .flex-active-slide").html($("#video_player .flex-active-slide").html()));
            }
          });

          $('#photo_slider').flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: false,
            slideshow: false,
            sync: "#photo_slider_thumb"
          });



        } else {

          document.getElementById("sliderFrame").innerHTML = '';
          document.getElementById("sliderFrame").style.background = '#e0e1e2';
          document.getElementById("sliderFrame").style.border = "1px solid #CCCCCC";
          document.getElementById("sliderFrame").style.padding = 0;
          document.getElementById("sliderFrame").setAttribute("class", "mainPhotoBlock");
          document.getElementById("sliderFrame").innerHTML = '<img src="templates/tmpl_par/images/NO-PHOTOS_icon.png" alt="ParentFinder"  />';
        }
      });
    } else {
      // document.getElementById("wowslider-container1").style.border=0;
    }
  },

  /*Aravind: */
  _loadVideo: function(uid) {
    var self = this;
    var count = 0;
    var postStr = "";

    // jwplayer_settings = {
    //     flashplayer: siteurl + 'components/album/js/jwplayer/player.swf',
    //     controlbar: 'bottom',
    //     //skin: 'js/jwplayer/jwplayer_skin.zip', 
    //     autostart: false,
    //     id: 'jwplayer1'
    // };
    // jwplayer_settings.width = 622;
    // jwplayer_settings.height = pp_dimensions['height'];
    // jwplayer_settings.file = pp_images[set_position];
    /* include jwplayer JS embedder */

    // $.getScript('components/album/js/jwplayer/jwplayer.js', function() {
    //     jwplayer("pp_full_res").setup(jwplayer_settings);
    // });


    var url = self.configuration[uid].application_path + "processors/getphotosvideos.php?videos=true&type=" + self.configuration[uid].type;
    if (location.href.split("=")[1]) {
      url = self.configuration[uid].application_path + "processors/getphotosvideos.php?videos=true&type=" + self.configuration[uid].type + "&id=" + location.href.split("=")[1];
    }
    if (self.configuration[uid].slider == 'home') {
      url = self.configuration[uid].application_path + "processors/getphotosvideos.php?homevideos=true&type=" + self.configuration[uid].type;
      if (location.href.split("=")[1]) {
        url = self.configuration[uid].application_path + "processors/getphotosvideos.php?homevideos=true&type=" + self.configuration[uid].type + "&id=" + location.href.split("=")[1];
      }
    }

    dhtmlxAjax.get(url, function(loader) {
      var vdos = " ",
        html = "";
      thumbs = " ",
      json = JSON.parse(loader.xmlDoc.responseText);
      // if (json.status == "error") {
      //   document.getElementById('video_player_thumb').style.display = "none";
      //   return;
      // }
      vdos = '<div id="video_player" class="flexslider"><ul class="slides">';
      thumbs = '<div id="video_palyer_thumb" class="flexslider"><ul class="slides">'
      vdos = '<div id="jw_video_player"></div>';
      jw_play_list = [];
      if (json.status == "success") {
        for (i in json.data) {
          if (json.data[i].source == "boonex") {
            jw_play_list.push({
              image: self.site + 'flash/modules/video/files/_' + json.data[i].id + '.jpg',
              sources: [{
                file: self.site + 'flash/modules/video/files/_' + json.data[i].id + '.flv'
              }]
            });
            //vdos = '<div id="jw_video_player"></div>';
            // vdos += '<li><object id="video_' + count + '" width="100%" height="410" type="application/x-shockwave-flash" id="ray_flash_video_player_object" name="ray_flash_video_player_embed" style="display:block;" data="' + self.site + 'flash/modules/global/app/holder_as3.swf"><param name="allowScriptAccess" value="always"><param name="allowFullScreen" value="true"><param name="base" value="' + self.site + 'flash/modules/video/files/_' + json.data[i].id + '.flv"><param name="bgcolor" value="#FFFFFF"><param name="wmode" value="opaque"><param name="flashvars" value="url=' + self.site + 'flash/XML.php&module=video&app=player&id=' + json.data[i].id + '&user=&password=' + Math.random(11111, 99999) + '"></object></li>';
            thumbs += '<li><img style="width:100%; height:113px" src="' + self.site + 'flash/modules/video/files/_' + json.data[i].id + '_small.jpg" /></li>'
            //tumbs += '<div class="thumb" id="thumb_' + count + '" data-video="' + json.data[i].id + '"><img style="width:100px; height:90px;" src="' + self.site + 'flash/modules/video/files/' + json.data[i].id + '_small.jpg"></div>';
          }
          count++;
        }
        vdos += '</ul></div>';
        thumbs += '</ul></div>';
        html = vdos + thumbs;
        if (self.isHomeVideo()) {
          document.getElementById('VideoShow').innerHTML = html;
        } else {
          document.getElementById('VideoShow').innerHTML = html;
        }


        var jwp = jwplayer("jw_video_player").setup({
          file: "http://example.com/myVideo.mp4",
          height: 418,
          width: 622,
          flashplayer: siteurl + 'components/album/js/jwplayer/player.swf',
          controlbar: 'bottom',
          //skin: 'js/jwplayer/jwplayer_skin.zip', 
          //autostart: false,
          playlist: jw_play_list
          //file: jw_play_list[0].sources[0].file,
          //image: jw_play_list[0].image
        });
        $('li').first().find('img').css('opacity', 1);
        $('#video_palyer_thumb').flexslider({
          animation: "slide",
          controlNav: false,
          animationLoop: false,
          slideshow: false,
          itemWidth: 116,
          itemMargin: 10,
          //asNavFor: '#jw_video_player',
          before: function(e) {
            // e.preventDefault();
            //alert("hi");
          }
        }).find('li').on('click', function(e) {
          e.stopPropagation();
          var i = $(this).index();
          jwp.playlistItem(i);
          jwp.stop();
          $('#video_palyer_thumb').find('li').find('img').css('opacity', 0.5);
          $(this).find('img').css('opacity', 1);
          //.flexslider img:hover
        });

        // $('#video_player').flexslider({
        //     animation: "slide",
        //     controlNav: false,
        //     animationLoop: false,
        //     slideshow: false,
        //     sync: "#video_palyer_thumb",
        //     before: function() {
        //         $("#video_player .flex-active-slide").html($("#video_player .flex-active-slide").html())
        //     }
        // });
      } else {
        document.getElementById("VideoShow").innerHTML = '';
        document.getElementById("VideoShow").style.background = '#e0e1e2';
        document.getElementById("VideoShow").style.border = "1px solid #CCCCCC";
        document.getElementById("VideoShow").style.padding = 0;
        document.getElementById("VideoShow").setAttribute("class", "mainPhotoBlock");
        var html = '<img src="templates/tmpl_par/images/NO-VIDEOS_icon.png" alt="ParentFinder"  />';
        document.getElementById("VideoShow").innerHTML = html;
      }
    });
  },

  _loadData: function(uid, callBack) {
    var self = this;
    var postStr = "approve=" + self.getUrlVars()["approve"];
    dhtmlxAjax.post(self.configuration[uid].application_path + "processors/profile_view_information.php?id=" + self.getUrlVars()["id"], postStr, function(loader) {
      try {
        var json = JSON.parse(loader.xmlDoc.responseText);

        if (json.status == "success") {
          self.dataStore["Profiles"] = json.Profiles;
          self.dataStore["Profiles_couple"] = json.Profiles_couple;
          self.dataStore["lastactivetime"] = json.lastactivetime;
          self.dataStore["inboxmess"] = json.inboxmess;
          self.dataStore["unreadmess"] = json.unreadmess;
          self.dataStore["sentmess"] = json.sentmess;
          self.dataStore["blog_posts"] = json.blog_posts;
          self.dataStore["current_date"] = json.current_date;
          self.dataStore["agency_address"] = json.agency_address;
          self.dataStore["agency_logo"] = json.agency_logo;
          self.dataStore["ebook_link"] = json.ebook_link;
          self.dataStore["epup_link"] = json.epup_link;          
          self.dataStore["logged"] = json.logged;
          self.dataStore["printprofile"] = json.printprofile;
          self.dataStore["deafulttempid"] = json.deafulttempid;
          self.dataStore["Profiles_video"] = json.Profiles_video;
          self.dataStore["Profiles_homevideo"] = json.Profiles_homevideo;
          self.dataStore["photocount"] = json.photocount;
          self.dataStore["videocount"] = json.videocount;

          if (callBack)
            callBack();
        } else {
          dhtmlx.message({
            type: "error",
            text: json.response
          });
        }
      } catch (e) {
        dhtmlx.message({
          type: "error",
          text: "Fatal error on server side: " + loader.xmlDoc.responseText
        });
        console.log(e.stack);
      }
    });
  },
  _form: function(uid) {
    var self = this;
    $(".icoLike").click(function() {
      LikeComp_controller.start({
        uid: (new Date()).getTime(),
        like: self.getUrlVars()["id"],
        callback: null,
        extra: {
          test: ''
        }
      });

    });
    //alert(document.getElementById("addjournal"));exit();
    var conf_form = self.model.conf_form.template_profileview;
    if (document.getElementById("addjournal"))
      var Profile_view = new dhtmlXForm("addjournal", conf_form);
    if (document.getElementById("letterlikes")) {
      var conf_form_letter = self.model.conf_form.letter_like;
      var letter_view = new dhtmlXForm("letterlikes", conf_form_letter);
    }

    //  if(document.getElementById("change_password")) {
    //    var conf_form_pass = self.model.conf_form.change_password;
    //    var change_pass_word = new dhtmlXForm("change_password", conf_form_pass);
    // }

    $("#change_pass").click(function() {
      changepassword();
    });

    function changepassword() {
      change_pass_window = new dhtmlXWindows();
      change_pass_window.enableAutoViewport(false);
      change_pass_window.attachViewportTo(vp);
      var conf_change_pass = self.model.conf_form.change_pass;
      var win_change_pass = change_pass_window.createWindow("w1", 500, 1340, 525, 280);
      win_change_pass.button("park").hide();
      win_change_pass.button("minmax1").hide();
      win_change_pass.button("minmax2").hide();
      win_change_pass.setText("");
      win_change_pass.setModal(true);
      win_change_pass.denyResize(true);
      //  win_change_pass.centerOnScreen();
      win_change_pass.centerOnScreen();
      var pos = win_change_pass.getPosition();
      var offset = (document.body.scrollTop ? document.body.scrollTop : window.pageYOffset);
      win_change_pass.setPosition(pos[0], pos[1] + offset)
      var change_pass = win_change_pass.attachForm(conf_change_pass);
      change_pass.attachEvent("onButtonClick", function(name, command) {
        var old_password = change_pass.getItemValue("old_pass");
        var new_password = change_pass.getItemValue("new_pass");
        var confirm_password = change_pass.getItemValue("confirm_pass");

        if (name == "changepassword") {
          if (old_password == '') {
            dhtmlx.message({
              type: "error",
              text: "Please enter the old password"
            })
            return false;
          }
          if (new_password == '') {

            dhtmlx.message({
              type: "error",
              text: "Please enter the new password"
            })
            return false;
          }
          if (confirm_password == '') {

            dhtmlx.message({
              type: "error",
              text: "Please enter the confirm password"
            })
            return false;
          }
          if (change_pass.getItemValue("newpassword") != '') {

            if (new_password != confirm_password) {

              dhtmlx.message({
                type: "error",
                text: "The new password and confirmation password do not match."
              })
              return false;
            }

          }


          var poststr = "new_pass=" + new_password + '&old_password=' + old_password;

          dhtmlxAjax.post(siteurl + "Agencyprofileviewcomponent/processors/change_password.php", poststr, function(loader) {
            var returnval = JSON.parse(loader.xmlDoc.responseText);

            if (returnval.status == 'success') {

              dhtmlx.message({
                type: "error",
                text: "The password changed successsfully."
              })
              win_change_pass.close();
              return false


            } else {

              dhtmlx.message({
                type: "error",
                text: "The password changed failed."
              })
              return false

            }
          });
        }


      });
    }

    if (logged == '') {
      document.getElementById("letterlikes").style.display = "none";
    }

    var json = self.dataStore;
    var Profiles = json.Profiles.rows;
    var Profiles_couple = json.Profiles_couple.rows;
    var Profiles_active = json.lastactivetime.rows;
    var mail_inbox = json.inboxmess.rows;
    var mail_unread = json.unreadmess.rows;
    var mail_sent = json.sentmess.rows;
    var blog_postss = json.blog_posts.rows;
    var agency_address = json.agency_address.rows;
    var agency_logo = json.agency_logo.rows;
    var ebook_link = json.ebook_link.rows;
    var epup_link = json.epup_link.rows;    
    var logged = json.logged.rows;
    var printprofile = json.printprofile.rows;
    var deafulttempid = json.deafulttempid.rows;
    var Profiles_video = json.Profiles_video.rows;
    var Profiles_homevideo = json.Profiles_homevideo.rows;
    var photocount = json.photocount.rows;
    var videocount = json.videocount.rows;

     

    var today_date = json.current_date.rows;
    if (document.getElementById("addjournal")) {
      Profile_view.setItemValue("journalentry", today_date);
      Profile_view.attachEvent("onfocus", function(name, value) {
        if (name == 'journalentry') {
          Profile_view.setItemValue("journalentry", '');
        }
      });
    }

    for (i in Profiles) {
      var profile_email = Profiles[i].data[1];
      var profile_fname = Profiles[i].data[2];
      var profile_id = Profiles[i].data[0];
      var profile_facebook = Profiles[i].data[6];
      var profile_twitter = Profiles[i].data[7];
      var profile_google = Profiles[i].data[8];
      var profile_blogger = Profiles[i].data[9];
      var profile_pinerest = Profiles[i].data[10];
    //  var profile_age = (Profiles[i].data[11].trim()) ? Profiles[i].data[11] : 'N/A';
    
      var d = new Date();
      var year = d.getFullYear()     
      if(Profiles[i].data[11]){          
      var profile_age =  year - Profiles[i].data[11];
        }
      else {
          var profile_age ='N/A';
       }
        
      var profile_state = (Profiles[i].data[12].trim()) ? Profiles[i].data[12] : 'N/A';;
      var profile_waiting = (Profiles[i].data[13].trim()) ? Profiles[i].data[13] : 'N/A';
      var profile_children = (Profiles[i].data[14].trim()) ? Profiles[i].data[14] : 'N/A';
      var profile_children_type = Profiles[i].data[35];
      var profile_faith = (Profiles[i].data[15] != 'null' && (Profiles[i].data[15].trim())) ? Profiles[i].data[15].replace(/,/g, ", ") : 'N/A';
      var profile_childethnicity = (Profiles[i].data[16].trim()) ? Profiles[i].data[16].replace(/,/g, ", ") : 'N/A';;
      var profile_childage = (Profiles[i].data[17].trim()) ? Profiles[i].data[17].replace(/,/g, ", ") : 'N/A';
      var profile_adoptiontype = (Profiles[i].data[18].trim()) ? Profiles[i].data[18].replace(/,/g, ", ") : 'Open to discussion';
      var profile_phnnum = Profiles[i].data[19];

      var profile_aboutme = Profiles[i].data[21];
      var profile_bathrooms = Profiles[i].data[23];
      var profile_bedrooms = Profiles[i].data[24];
      var profile_yard = Profiles[i].data[22];
      var profile_housetyle = (Profiles[i].data[25] != 'Not Specified') ? Profiles[i].data[25] : '';
      var profile_weburl = Profiles[i].data[26];
      var profile_couple = Profiles[i].data[27];
      var profile_home = Profiles[i].data[28];
      var nikName = Profiles[i].data[29];
      var pass = Profiles[i].data[4];

      var address1 = Profiles[i].data[30];
      var address2 = Profiles[i].data[31];
      var city = Profiles[i].data[32];
      var zip = Profiles[i].data[33];
      var profile_hobbies = Profiles[i].data[35];
      var profile_interests = Profiles[i].data[36];
      
      var address1 = (address1 != '') ? address1 + ', ' : address1;
      var address2 = (address2 != '') ? address2 + ', ' : address2;
      var city = (city != '') ? city + ' - ' : city;
            
      var profile_Education = Profiles[i].data[36];
      var profile_Occupation = Profiles[i].data[37];
      var profile_Ethnicity = Profiles[i].data[38];
      var profile_Religion = Profiles[i].data[39];
      var profile_RelationshipStatus = Profiles[i].data[40];
      var profile_FamilyStructure = Profiles[i].data[41];


      if (document.getElementById("photo_label")) {
        document.getElementById("photo_label").innerHTML = '<a style="color: #57B4A8" href="m/photos/albums/my/main">   MANAGE PHOTOS</a>';
        //  document.getElementById("photo_label").innerHTML = '<a class="tealfont" href="m/photos/browse/album/' + nikName + '-s-photos/owner/' + nikName + '">   MANAGE PHOTOS</a>';
      }
      if (document.getElementById("videos_label")) {
        document.getElementById("videos_label").innerHTML = '<a style="color: #57B4A8" href="m/videos/albums/my/main">  MANAGE VIDEOS</a>';
        //  document.getElementById("videos_label").innerHTML = '<a class="tealfont" href="m/videos/browse/album/' + nikName + '-s-videos/owner/' + nikName + '">   MANAGE VIDEOS</a>';

      }
      if (profile_phnnum != '' && profile_phnnum != '0')
        var profile_phonenumber = Profiles[i].data[19][0] + Profiles[i].data[19][1] + Profiles[i].data[19][2] + '-' +
          Profiles[i].data[19][3] + Profiles[i].data[19][4] + Profiles[i].data[19][5] + '-' +
          Profiles[i].data[19][6] + Profiles[i].data[19][7] + Profiles[i].data[19][8] + Profiles[i].data[19][9];
      else
        profile_phonenumber = '';

      // var profile_streetaddress = Profiles[i].data[20].replace(/,/g, ', ');
      var profile_streetaddress = address1 + address2 + city + zip;

      document.getElementById("profileid").innerHTML = profile_fname;
      if (document.getElementById("profileemail"))
        document.getElementById("profileemail").innerHTML = profile_email;

      document.getElementById("profilestate").innerHTML = profile_state;
      document.getElementById("profilewaiting").innerHTML = profile_waiting;
      document.getElementById("profilechildren").innerHTML = profile_children;
     
      if(profile_children !=  'No Children') {
      
      if(profile_children == '1 Child')
      {
      if(profile_children_type != '')
      {      
      if (document.getElementById("profilechildrentype"))
      document.getElementById("profilechildrentype").innerHTML =  '<span class="profileInfoCaption">TYPE OF CHILD: </span>'+ profile_children_type +'<br />';       
      }
      else {
      if (document.getElementById("profilechildrentype"))
      document.getElementById("profilechildrentype").innerHTML =  '<span class="profileInfoCaption">TYPE OF CHILD: </span>'+ 'Not given' +'<br />';     
          
      }
      }
      else {
      if(profile_children_type != '')
      { 
      if (document.getElementById("profilechildrentype"))
      document.getElementById("profilechildrentype").innerHTML =  '<span class="profileInfoCaption">TYPE OF CHILDREN: </span>'+ profile_children_type +'<br />';    
      }
      else {
      if (document.getElementById("profilechildrentype"))
      document.getElementById("profilechildrentype").innerHTML =  '<span class="profileInfoCaption">TYPE OF CHILDREN: </span>'+ 'Not given' +'<br />';     
          
      } 
      }
      }
     else 
      {
       if (document.getElementById("profilechildrentype"))   
       document.getElementById("profilechildrentype").innerHTML = '';       
              
      }     
      document.getElementById("profilefaith").innerHTML = profile_faith;
      document.getElementById("profileethinicity").innerHTML = profile_childethnicity;
      document.getElementById("profilechildage").innerHTML = profile_childage;
      document.getElementById("profileadoptiontype").innerHTML = profile_adoptiontype;
      if (document.getElementById("profilephonenumber"))
        document.getElementById("profilephonenumber").innerHTML = profile_phonenumber;
      if (document.getElementById("profilestreetaddress"))
        document.getElementById("profilestreetaddress").innerHTML = profile_streetaddress;
      document.getElementById("profileage").innerHTML = profile_age;

      if (document.getElementById("aboutme") && profile_aboutme.trim() != '') {
        document.getElementById("aboutme").innerHTML = '<div style="text-transform:uppercase;float:left;font-weight:bold;">ABOUT ' + profile_fname + ': </div> ' + profile_aboutme;
      }
      
      if (document.getElementById("abouthobbies") && profile_hobbies.trim() != '') {
        document.getElementById("abouthobbies").innerHTML = '<div style="text-transform:uppercase;float:left;font-weight:bold;">HOBBIES OF ' + profile_fname + ': </div> ' + profile_hobbies;
      }
            
      if (document.getElementById("aboutinterests") && profile_interests.trim() != '') {
        document.getElementById("aboutinterests").innerHTML = '<div style="text-transform:uppercase;float:left;font-weight:bold;">INTERESTS OF ' + profile_fname + ': </div> ' + profile_interests;
      }
      
      
      if (document.getElementById("HOME") && profile_home.trim() != '') {
        document.getElementById("HOME").innerHTML = ' <div style="text-transform:uppercase;float:left;font-weight:bold;">ABOUT OUR HOME:&nbsp;&nbsp;</div>' + profile_home;
      }


      if (document.getElementById("BED") && profile_bedrooms.trim() != '')
        document.getElementById("BED").innerHTML = "BED ROOM :" + profile_bedrooms;

      if (document.getElementById("BATH") && profile_bedrooms.trim() != '')
        document.getElementById("BATH").innerHTML = "BATH ROOM :" + profile_bedrooms;

      if (document.getElementById("houseStyle") && profile_housetyle.trim() != '')
        document.getElementById("houseStyle").innerHTML = profile_housetyle;

      if (document.getElementById("LOT") && profile_yard.trim() != '') {
        document.getElementById("LOT").innerHTML = "YARD SIZE :" + profile_yard;
      }

      if (document.getElementById("websiteurl") && profile_weburl.trim() != '')
        document.getElementById("websiteurl").innerHTML = '<a target="_blank" href="//' + profile_weburl + '">' + profile_weburl + '</a>';

      if (document.getElementById("journals")) {
        if (profile_couple.trim() != 0) {
          document.getElementById("journals").innerHTML = "OUR JOURNAL";
        } else {
          document.getElementById("journals").innerHTML = "MY JOURNAL";
        }
      }
      if (document.getElementById("websites")) {
        if (profile_couple.trim() != 0) {
          document.getElementById("websites").innerHTML = "OUR WEBSITE";
        } else {
          document.getElementById("websites").innerHTML = "MY WEBSITE";
        }
      }
      if (document.getElementById("agencies")) {
        if (profile_couple.trim() != 0) {
          document.getElementById("agencies").innerHTML = "OUR AGENCY";
        } else {
          document.getElementById("agencies").innerHTML = "MY AGENCY";
        }
      }
      if (document.getElementById("letters")) {
        if (profile_couple.trim() != 0) {
          document.getElementById("letters").innerHTML = "OUR LETTERS";
        } else {
          document.getElementById("letters").innerHTML = "MY LETTERS";
        }
      }
      if (document.getElementById("contacts")) {
        if (profile_couple.trim() != 0) {
          document.getElementById("contacts").innerHTML = "OUR CONTACT INFO";
        } else {
          document.getElementById("contacts").innerHTML = "MY CONTACT INFO";
        }
      }
      if (document.getElementById("mail")) {
        if (profile_couple.trim() != 0) {
          document.getElementById("mail").innerHTML = "OUR MAIL";
        } else {
          document.getElementById("mail").innerHTML = "MY MAIL";
        }
      }

      if (document.getElementById("ownhome")) {
        if (profile_couple.trim() != 0) {
          document.getElementById("ownhome").innerHTML = "OUR HOME";
        } else {
          document.getElementById("ownhome").innerHTML = "MY HOME";
        }
      }
      if (document.getElementById("flipbookowndetails")) {
        if (profile_couple.trim() != 0) {
          document.getElementById("flips").innerHTML = "OUR FLIPBOOK";
        } else {
          document.getElementById("flips").innerHTML = "MY FLIPBOOK";
        }
      }
      if (document.getElementById("forum")) {
        if (profile_couple.trim() != 0) {
          document.getElementById("forum").innerHTML = "OUR FORUMS";
        } else {
          document.getElementById("forum").innerHTML = "MY FORUMS";
        }
      }
      if (document.getElementById("pdfprofile")) {
        if (profile_couple.trim() != 0) {
          document.getElementById("pdfprofile").innerHTML = "OUR PDF PROFILE";
        } else {
          document.getElementById("pdfprofile").innerHTML = "MY PDF PROFILE";
        }
      }


      if (document.getElementById("test")) {
        if (deafulttempid == 0) {
          $('.printdefaultpdf').attr("id", printprofile);

        } else {
          $('.printdefaultpdf_print').attr("id", printprofile);
        }

      }

      if (document.getElementById("profilepublic")) {
        if (deafulttempid == 0) {
          document.getElementById("profilepublic").innerHTML = '<a href="javascript:void(0);" class="printpublicsec"><div class="icoOurPrint" ></div></a>'
          $('.printpublicsec').attr("id", printprofile);
        } else {
          $('.printpublic').attr("id", printprofile);
        }
      }

      function showProcessing() {
        REDIPS.dialog.init();
        REDIPS.dialog.op_high = 60;
        REDIPS.dialog.fade_speed = 18;
        REDIPS.dialog.show(183, 17, '<img width="183" height="17" title="" alt="" src="data:image/gif;base64,R0lGODlhtwARAPcDAJu44EFpoOLq9f///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFCgADACwAAAAAtwARAAAI/wAFCBxIsKDBgwgTKlzIsKHDhxAjSpxIsaLFixgzaowYoKNHjwIAiBw5MiRJkiZPikypkuVJlyhVlpS5kiYAmDNp4qyp0+bOmz59DvxI9KfRoD2TyjyqtCXSpU+dNn0Z9eVQoiCrxpy6FSrXnF7DShVL9StPsl1bXsUagClasGPjln17Vm7auXbh4t17F+VarG7z1uWrt2/hw4MNJ0YM1Gxjujf/FtXKODDhxZgfC9Z8mbNiz5WFCmTb0fJn06Edo868GjRryislf2xNG7br27VV2869VHZW3cAh8948vHNxv6NJH08tfLfz4MSf907Odvlr6MalR8d+WrQA0m21ZyTnznx7c/LXp39XLr47etzty48/r36j/fv48+vfz7+///8MBQQAIfkEBQoAAwAsDAACAAkADQAACBQAAwgcSLCgwYMIEypcyLChQ4YBAQAh+QQFCgADACwWAAIACQANAAAIFAADCBxIsKDBgwgTKlzIsKFDhgEBACH5BAUKAAMALCAAAgAJAA0AAAgUAAMIHEiwoMGDCBMqXMiwoUOGAQEAIfkEBQoAAwAsKgACAAkADQAACBQAAwgcSLCgwYMIEypcyLChQ4YBAQAh+QQFCgADACw0AAIACQANAAAIFAADCBxIsKDBgwgTKlzIsKFDhgEBACH5BAUKAAMALD4AAgAJAA0AAAgUAAMIHEiwoMGDCBMqXMiwoUOGAQEAIfkEBQoAAwAsSAACAAkADQAACBQAAwgcSLCgwYMIEypcyLChQ4YBAQAh+QQFCgADACxSAAIACQANAAAIFAADCBxIsKDBgwgTKlzIsKFDhgEBACH5BAUKAAMALFwAAgAJAA0AAAgUAAMIHEiwoMGDCBMqXMiwoUOGAQEAIfkEBQoAAwAsZgACAAkADQAACBQAAwgcSLCgwYMIEypcyLChQ4YBAQAh+QQFCgADACxwAAIACQANAAAIFAADCBxIsKDBgwgTKlzIsKFDhgEBACH5BAUKAAMALHoAAgAJAA0AAAgUAAMIHEiwoMGDCBMqXMiwoUOGAQEAIfkEBQoAAwAshAACAAkADQAACBQAAwgcSLCgwYMIEypcyLChQ4YBAQAh+QQFCgADACyOAAIACQANAAAIFAADCBxIsKDBgwgTKlzIsKFDhgEBACH5BAUKAAMALJgAAgAJAA0AAAgUAAMIHEiwoMGDCBMqXMiwoUOGAQEAIfkEBQoAAwAsogACAAkADQAACBQAAwgcSLCgwYMIEypcyLChQ4YBAQAh+QQFCgADACysAAIACQANAAAIFAADCBxIsKDBgwgTKlzIsKFDhgEBADs=" />');
      }

      function HideBar() {
        REDIPS.dialog.hide("undefined");
      }

      $(".printpublicsec").click(function() {
        xProcessPrintPDF123($(this));
      });

      function xProcessPrintPDF123(item) {
        if (item.attr("id"))
          window.open(item.attr("id"));
        else
          dhtmlx.confirm({
          type: "confirm",
          text: "There is no printed profile available.Would you like to send the family a request ?  ( Yes / No ) answer",
          ok: "Yes",
          cancel: "No",
          callback: function(result) {
          if (result == true) {
              
           var poststr = "Profileid=" + profile_id;            
            dhtmlxAjax.post(self.configuration+"Expctantparentsearchfamilies/processors/pdf_profile_request.php", poststr, function(loader) {
                var json = JSON.parse(loader.xmlDoc.responseText);
                if (json.status == "success") {
                  dhtmlx.alert({
                    text: "Your request has been sent to the family please check back soon"
                  });
          
                } else {
                  dhtmlx.message({
                    type: "error",
                    text: json.message
                  })
                }
              });
                  
          }
          else {
                  
                  
            }

          }
        });
      }

      $(".printpublic").click(function() {
        xProcessPrintPDF12($(this));
      });

      function xProcessPrintPDF12(item) {
        if (item.attr("id")){
          //window.open(item.attr("id"));
		  		var printprofile = item.attr("id");
				var printprofile_path = printprofile.split("PDFTemplates/user/"); 
		  		var main_URL           = $(location).attr('protocol')+"//"+$(location).attr('host')+"/"; 
				var documentViewer_application_url    = main_URL+'documentViewer/';

				var flexvalues = {
					icons_path: 'documentViewer/icons/32px/' // mandatory
					,application_url: documentViewer_application_url // mandatory
					,application_path: '/var/www/html/pf/PDFTemplates/user/' // mandatory
					,pdf_name: printprofile_path[1] // mandatory 
					,split_mode: false // not mandatory
					,magnification: 1.3  // not mandatory, default 1.1
				}
				FlexPaperComponent.callFlexPaper(flexvalues); 
        }else{
          dhtmlx.confirm({
          type: "confirm",
          text: "There is no printed profile available.Would you like to send the family a request ?",
          ok: "Yes",
          cancel: "No",
          callback: function(result) {
          if (result == true) {
              
           var poststr = "Profileid=" + profile_id;            
            dhtmlxAjax.post(self.configuration+"Expctantparentsearchfamilies/processors/pdf_profile_request.php", poststr, function(loader) {
                var json = JSON.parse(loader.xmlDoc.responseText);
                if (json.status == "success") {
                  dhtmlx.alert({
                    text: "Your request has been sent to the family please check back soon"
                  });
          
                } else {
                  dhtmlx.message({
                    type: "error",
                    text: json.message
                  })
                }
              });
                  
          }
          else {
                  
                  
            }

          }
        });
		}
      }

      if (ebook_link == false) {
        if (document.getElementById("flipbookdetails")) {
          document.getElementById("flipbookdetails").innerHTML = '<a href="javascript:ProfileViewComponent.errorAlert(\'There is no ebook uploaded\');" ><div class="icoOurFlip"></div></a>';
        }
        // if(document.getElementById("flipbookowndetails")){  
        // document.getElementById("flipbookowndetails").innerHTML = '<a href="javascript:alert(\'There is no ebook uploaded\');"  class="fieldTitleGreen icoBuildFlipbook">                        vnfghfh                     </a>';
        //  }
        if (profile_couple.trim() != 0) {
          if (document.getElementById("flipbookowndetails")) {
            document.getElementById("flipbookowndetails").innerHTML = '<a href="javascript:ProfileViewComponent.errorAlert(\'There is no ebook uploaded\');"  class="fieldTitleGreen icoBuildFlipbook"> OUR FLIPBOOK</a>';
          }
        } else {
          if (document.getElementById("flipbookowndetails")) {
            document.getElementById("flipbookowndetails").innerHTML = '<a href="javascript:ProfileViewComponent.errorAlert(\'There is no ebook uploaded\');"  class="fieldTitleGreen icoBuildFlipbook">MY FLIPBOOK    </a>';
          }

        }

      }



      if (profile_facebook == '') {
        $('.icoSmFb').removeClass('icoSmFb');
      } else {
        if (profile_facebook.substr(0, 7) != 'http://') {
          profile_facebook = 'http://' + profile_facebook;
        }

        document.getElementById('face').href = profile_facebook;

      }
      if (profile_twitter == '') {
        $('.icoSmTw').removeClass('icoSmTw');
      } else {
        if (profile_twitter.substr(0, 7) != 'http://') {
          profile_twitter = 'http://' + profile_twitter;
        }

        document.getElementById('twit').href = profile_twitter;

      }
      if (profile_google == '') {
        $('.icoSmGo').removeClass('icoSmGo');
      } else {
        if (profile_google.substr(0, 7) != 'http://') {
          profile_google = 'http://' + profile_google;
        }
        document.getElementById('goog').href = profile_google;

      }
      if (profile_pinerest == '') {
        $('.icoSmPi').removeClass('icoSmPi');
      } else {

        if (profile_pinerest.substr(0, 7) != 'http://') {
          profile_pinerest = 'http://' + profile_pinerest;
        }
        document.getElementById('pine').href = profile_pinerest;

      }
      if (profile_blogger == '') {
        $('.icoSmBi').removeClass('icoSmBi');
      } else {
        var profile_blogger_link = profile_blogger;
        if (profile_blogger.substr(0, 7) != 'http://') {
          profile_blogger_link = 'http://' + profile_blogger;
        }
        document.getElementById('blog').href = profile_blogger_link;

      }

      if (document.getElementById('flip'))
        document.getElementById('flip').href = ebook_link;

      if (document.getElementById('homes'))
        document.getElementById('homes').href = 'extra_profile_view_18.php?id=' + profile_id;
      if (document.getElementById('ownhome'))
        document.getElementById('ownhome').href = 'extra_profile_view_18.php?id=' + profile_id;
      if (document.getElementById('letter'))
        document.getElementById('letter').href = 'extra_profile_view_19.php?id=' + profile_id;
      if (document.getElementById('contact'))
        document.getElementById('contact').href = 'ContactAgency.php?ID=' + profile_id;
      // if(document.getElementById('print'))
      //    document.getElementById('print').href='#'; 

    }


    if (profile_couple.trim() != 0) {
      if (document.getElementById('profilechange'))
        document.getElementById('profilechange').innerHTML = 'OUR FAMILY PROFILE';
    } else {
      if (document.getElementById('profilechange'))
        document.getElementById('profilechange').innerHTML = 'MY FAMILY PROFILE';
    }


    if (document.getElementById('editprofile'))
      document.getElementById('editprofile').href = 'extra_profile_builder_11.php';
    if (document.getElementById('home'))
      document.getElementById('home').href = 'extra_profile_view_18.php';
    // if(document.getElementById('flip'))
    //    document.getElementById('flip').href=ebook_link;
    // else 
    //    if(document.getElementById('remove'))
    //    document.getElementById("remove").style.display = "none";


    if (document.getElementById('forum'))
      document.getElementById('forum').href = 'forum/';
    if (document.getElementById('pdftool'))
      document.getElementById('pdftool').href = 'page/pdfcreate';
    if (document.getElementById('resource'))
      document.getElementById('resource').href = 'extra_profile_view_26.php';

    if(photocount <= 0)
    {   
    $( "#morephotos" ).remove();
    $( "#morehomephotos" ).remove();
    $(".morePhotosTab").css('background-image', 'url("")');    
    }
    if(videocount <= 0)
    {
    $( "#morevideos" ).remove();
    $( "#morehomevideos" ).remove();
    $(".moreVideosTab").css('background-image', 'url("")'); 
    
    }
    
    if (document.getElementById('morephotos'))
    //  document.getElementById('morephotos').href = 'm/photos/browse/album/' + nikName + '-s-photos/owner/' + nikName;
      // document.getElementById('morephotos').href = 'm/photos/albums/browse/owner/' + nikName;
    document.getElementById('morephotos').href = '/more-photos.php?id='+self.getUrlVars()["id"];

    if (document.getElementById('morevideos'))

    // document.getElementById('morevideos').href = 'm/videos/browse/album/' + nikName + '-s-videos/owner/' + nikName;
      // document.getElementById('morevideos').href = 'm/videos/albums/browse/owner/' + nikName;

    document.getElementById('morevideos').href = '/more-videos.php?id='+self.getUrlVars()["id"];
    
    if (document.getElementById('morehomephotos'))
    // document.getElementById('morehomephotos').href = 'm/photos/browse/album/' + nikName + '-s-home-photos/owner/' + nikName;
      // document.getElementById('morehomephotos').href = 'm/photos/albums/browse/owner/' + nikName;
      document.getElementById('morehomephotos').href = '/more-photos.php?id='+self.getUrlVars()["id"];


    if (document.getElementById('morehomevideos'))
    //  document.getElementById('morehomevideos').href = 'm/videos/browse/album/' + nikName + '-s-home-videos/owner/' + nikName;
      // document.getElementById('morehomevideos').href = 'm/videos/albums/browse/owner/' + nikName;
      document.getElementById('morehomevideos').href = '/more-videos.php?id='+self.getUrlVars()["id"];

    var profile_vid = "";
    for (i in Profiles_video) {
      profile_vid = Profiles_video[i].data[0];
      //  var filename = Profiles_video[i].rows[0]; 
      //  alert(filename);
    }
    var site = self.site;
    if (document.getElementById('video_player')) {
      if (profile_vid) {
        // document.getElementById('video_player').innerHTML = '<object width="100%" height="100%" type="application/x-shockwave-flash"id="ray_flash_video_player_object" name="ray_flash_video_player_embed"style="display:block;" data="' + site + 'flash/modules/global/app/holder_as3.swf"><param name="allowScriptAccess" value="always"><param name="allowFullScreen" value="true"><param name="base" value="' + site + 'flash/modules/video/"><param name="bgcolor" value="#FFFFFF"><param name="wmode" value="opaque"><param name="flashvars" value="url=' + site + 'flash/XML.php&module=video&app=player&id=' + profile_vid + '&user=&password=' + pass + '"></object>';
        // document.getElementById('video_player_thumb').innerHTML='aravind';
      } else {
        var
          html = '<img src="templates/tmpl_par/images/NO-VIDEOS_icon.png" alt="ParentFinder" style="width:100%;height:100%" />';
        // var html = '<div style="text-align: center;margin-top:30px;">No uploaded vidoes.</div>';
        // if (self.getUrlVars()["id"] == self.dataStore['logger']) {
        //   html += '<div style="text-align:center"><a href="' + siteurl + 'extra_profile_builder_11.php">Click here to add videos</a></div>';
        // }
        document.getElementById('video_player').innerHTML = html;
      }
    }


    for (i in Profiles_homevideo) {
      var profilehome_vid = Profiles_homevideo[i].data[0];
      //  var filename = Profiles_video[i].rows[0]; 
      //  alert(filename);
    }

    if (document.getElementById('video_home_player')) {
      if (profilehome_vid) {
        document.getElementById('video_home_player').innerHTML = '<object width="100%" height="100%" type="application/x-shockwave-flash"id="ray_flash_video_player_object" name="ray_flash_video_player_embed"style="display:block;" data="' + site + 'flash/modules/global/app/holder_as3.swf"><param name="allowScriptAccess" value="always"><param name="allowFullScreen" value="true"><param name="base" value="' + site + 'flash/modules/video/"><param name="bgcolor" value="#FFFFFF"><param name="wmode" value="opaque"><param name="flashvars" value="url=' + site + 'flash/XML.php&module=video&app=player&id=' + profilehome_vid + '&user=&password=' + pass + '"></object>';
      } else {
        document.getElementById('video_home_player').innerHTML = '<img src="templates/tmpl_par/images/NO-VIDEOS_icon.png" alt="ParentFinder" style="width:100%;height:100%" />';
      }
    }

    var profile_fname_couple = '';
    for (i in Profiles_couple) {
      profile_fname_couple = Profiles_couple[i].data[1];
      
      var d = new Date();
      var year = d.getFullYear();  
      if(Profiles_couple[i].data[2] > 0) 
      var profile_age_couple = year - Profiles_couple[i].data[2];
      else 
      var profile_age_couple ='N/A';    
     //  var profile_age_couple = year - Profiles_couple[i].data[2];   
      var profile_aboutme_couple = Profiles_couple[i].data[3];
      
      var profile_hobbies_couple = Profiles_couple[i].data[4];
      var profile_interests_couple = Profiles_couple[i].data[5];
      
      
      document.getElementById("profileid").innerHTML = profile_fname + ' & ' + profile_fname_couple;

      if (document.getElementById("aboutme") && profile_aboutme_couple.trim() != '')
        document.getElementById("aboutme").innerHTML = document.getElementById("aboutme").innerHTML
        + '<br/><br/><br/>' + '<div style="text-transform:uppercase;float:left;font-weight:bold;">ABOUT ' 
        + profile_fname_couple + ': </div> ' + profile_aboutme_couple+ '<br/><br/><br/>';
    

  if (document.getElementById("abouthobbies") && profile_hobbies_couple.trim() != '')      document.getElementById("abouthobbies").innerHTML = document.getElementById("abouthobbies").innerHTML
      + '<br/><br/><br/>' + '<div style="text-transform:uppercase;float:left;font-weight:bold;">HOBBIES OF ' + profile_fname_couple + ': </div> ' + profile_hobbies_couple + '<br/><br/><br/>';
  
   if (document.getElementById("aboutinterests") && profile_hobbies_couple.trim() != '')      document.getElementById("aboutinterests").innerHTML = document.getElementById("aboutinterests").innerHTML
      + '<br/><br/><br/>' + '<div style="text-transform:uppercase;float:left;font-weight:bold;">INTERESTS OF ' + profile_fname_couple + ': </div> ' + profile_interests_couple ;
  

}

      




    if (profile_age_couple == '' || profile_age_couple == undefined) {
      profile_age_couple = '';
    } else
      profile_age_couple = ' / ' + profile_age_couple;
    document.getElementById("profileage").innerHTML = profile_age + profile_age_couple;
    document.getElementById("lastactive").innerHTML = Profiles_active;
    if (document.getElementById("inbox"))
      document.getElementById("inbox").innerHTML = mail_inbox + ' messages;' + mail_unread + ' unread';
    if (document.getElementById("sent"))
      document.getElementById("sent").innerHTML = mail_sent + ' messages'
    self.journlcount = 0;
    for (i in blog_postss) {

      var profile_blog_date = blog_postss[i].data[0];
      var profile_blog = blog_postss[i].data[1];

      //  var profile_blogs = blog_postss[i].data[1];  
      // alert(profile_blog);
      if (document.getElementById("blogposts_limited")) {

        if (self.journlcount > 5) {
          break;
        }
        document.getElementById("blogposts_limited").innerHTML += '<span>' + profile_blog_date + '</span>  ' + profile_blog + '</br/></br/>';
      }
      if (self.journlcount < 0) {
        document.getElementById('seemore').style.display = 'none'
      }
      if (document.getElementById("seemore")) {
        document.getElementById("seemore").innerHTML = '<a href="modules/boonex/blogs/blogs.php?action=show_member_blog&ownerID=' + profile_id + '">SEE MORE ENTRIES</a>';
      }

      if (document.getElementById("blogposts")) {
        document.getElementById("blogposts").innerHTML += '<span>' + profile_blog_date + '</span> ' + profile_blog;
        document.getElementById("blogposts").innerHTML += '<div class="shareIcons" id="shareImg' + self.journlcount + '">';
        document.getElementById("shareImg" + self.journlcount).innerHTML += '<span class="shareTitle">SHARE:</span>';
        //document.getElementById("shareImg"+self.journlcount).innerHTML += '<a href="//https://plus.google.com/share?url=http%3A%2F%2Fexample.com&t=sdfsdf"><img class="headIcon" width="13px;" height="13px;" src="templates/tmpl_par/images/ico_go_sm_act.png" alt="" title="" /></a>';
        document.getElementById("shareImg" + self.journlcount).innerHTML += '<a class="twshare" href="//twitter.com/share?text=' + profile_blog + '" target="_blank"></a>';
        document.getElementById("shareImg" + self.journlcount).innerHTML += '<a class="fbshare" onclick="alert(\'need fb details\')" target="_blank"></a>';

        document.getElementById("blogposts").innerHTML += '</br/></br/>';
      }
      self.journlcount++

    }
	
    for (i in agency_address) {
      var profile_agency_names = agency_address[i].data[0];
      var profile_agency_city =  agency_address[i].data[1];
      var agency_state = agency_address[i].data[2];
      var agency_zip =  agency_address[i].data[3];
      var agency_country = agency_address[i].data[4];
      var agency_contactnumber = agency_address[i].data[5];
      var agency_email = agency_address[i].data[6];
      var agency_url = agency_address[i].data[7];
      var agency_avatar = agency_address[i].data[8];

      var agency_state_abbr = (agency_address[i].data[9] != '' && profile_agency_city != '') ? ', '+agency_address[i].data[9]  : agency_address[i].data[9];

      var profile_agency_city = (profile_agency_city != ''  ) ?  profile_agency_city  : profile_agency_city;

      var agency_zip = ((agency_zip != '' && agency_address[i].data[9] != '')|| (agency_zip != '' && profile_agency_city != '')) ? ', '+agency_zip  : agency_zip;


      if (agency_contactnumber != '' && agency_contactnumber != '0')
        var agency_contact = agency_address[i].data[5];
      else
        agency_contact = '';

      document.getElementById("profileagencyaddress").innerHTML =
        profile_agency_names + '<br/>' + profile_agency_city + agency_state_abbr + agency_zip + '<br/>' +
        agency_contact + '<br/><a href="mailto:' + agency_email + '">' + agency_email + '</a><br/><a  target ="_blank" href="//' + agency_url + '">' + agency_url + '</a>';
    }
    var agency_logo = agency_logo[i].data;
    document.getElementById("profile_agency_logo").innerHTML = agency_logo;


    if (letter_view) {
      letter_view.attachEvent("onButtonClick", function(name, command) {
        LikeComp_controller.start({
          uid: (new Date()).getTime(),
          callback: null,
          extra: {}
        });
        //            var poststr = "UserID=" + profile_id;
        //            dhtmlxAjax.post(self.configuration[uid].application_path + "processors/insert_like_agency.php", poststr, function (loader) {
        //            });

      });
    }



    // Calling the function to save the data    
    if (Profile_view) {
      Profile_view.attachEvent("onButtonClick", function(name, command) {


        if (name == "journalsubmit") {

          var posttextvalue = Profile_view.getItemValue("journalentry");
          var poststr = "postext=" + posttextvalue;
          //alert("sdfsdf");
          // Inserting values to database
          dhtmlxAjax.post(self.configuration[uid].application_path + "processors/insert_profile_info.php", poststr, function(loader) {
            var returnval = JSON.parse(loader.xmlDoc.responseText);
            var blog_postsss = returnval.blog_posts.rows;
            document.getElementById("blogposts").innerHTML = '';
            for (i in blog_postsss) {
              var profile_blog_date1 = blog_postsss[i].data[0];
              var profile_blog1 = blog_postsss[i].data[1];
              // var profile_blogs1 = blog_postsss[i].data[1];  
              //document.getElementById("blogposts").innerHTML += '<b>'+profile_blog_date1+'</b>  '+profile_blog1+'</br/></br/>';
              document.getElementById("blogposts").innerHTML += '<span>' + profile_blog_date1 + '</span> ' + profile_blog1;
              document.getElementById("blogposts").innerHTML += '<div class="clear_both"></div><div class="shareIcons" id="shareImg' + self.journlcount + '">';
              //document.getElementById("shareImg"+count).innerHTML += '<a href="//https://plus.google.com/share?url=http%3A%2F%2Fexample.com&t=sdfsdf"><img class="headIcon" width="13px;" height="13px;" src="templates/tmpl_par/images/ico_go_sm_act.png" alt="" title="" /></a>';
              document.getElementById("shareImg" + self.journlcount).innerHTML += '<a href="//twitter.com/share?text=' + profile_blog1 + '" target="_blank"><img style=" left: 1px;" class="headIcon" src="templates/tmpl_par/images/ico_tw_sm_act.png" alt="" title="" /></a>';
              document.getElementById("shareImg" + self.journlcount).innerHTML += '<a onclick="alert(\'need fb details\')" target="_blank"><img class="headIcon" style=" left: 1px;" src="templates/tmpl_par/images/ico_fb_sm_act.png" alt="" title="" /></a>';
              document.getElementById("shareImg" + self.journlcount).innerHTML += '<span class="shareTitle">SHARE:</span>';
              document.getElementById("blogposts").innerHTML += '</br/></br/>';
              self.journlcount++;
            }
            Profile_view.setItemValue("journalentry", '');
          });

        }
      });
    }

  },
  isHomeVideo: function() {
    if (document.getElementById('video_home_player')) {
      return true;
    }
    return false;
  },
  _approve: function(uid) {
    var self = this;
    dhtmlxAjax.get(self.configuration[uid].application_path + "processors/profile_Approve_save.php?id=" + self.getUrlVars()["id"], function(loader) {
      try {
        var json = JSON.parse(loader.xmlDoc.responseText);

        if (json.status == "success") {
          dhtmlx.message({
            text: "Changes approved"
          });
        }
      } catch (e) {
        dhtmlx.message({
          type: "error",
          text: "Fatal error on server side: " + loader.xmlDoc.responseText
        });
        console.log(e.stack);
      }
    });
  },
  _improve: function(uid) {
    var self = this;
    if (!$("#comments").val()) {
      self.errorAlert("Please enter valid comments")
    } else {
      commenttext = $("#comments").val();
      var poststr = "profileemail=" + self.getUrlVars()["id"] + "&profilecomment=" + commenttext;
      // sending an email
      dhtmlxAjax.post(self.configuration[uid].application_path + "processors/sendemail.php", poststr, function(loader) {
        var returnval = JSON.parse(loader.xmlDoc.responseText);
        console.log(returnval);
        if (returnval.status == "success") {
          dhtmlx.message({
            type: "alert",
            text: "The mail has been sent successfully"
          });
          $("#comments").val('');
        }
        //  window.location.href = returnval.user_redirection;
        $("#Improvement").click(function() {
          $("#comments").val('');
        });
        if (returnval.status = 'success') {
          dhtmlx.message({
            type: "error",
            text: "Comments send to the user successfully."
          });
        }
      });
    }


    /* dhtmlxAjax.get(self.configuration[uid].application_path + "processors/profile_Approve_save.php?id="+self.getUrlVars()["id"],function (loader) {
        try {    var json = JSON.parse(loader.xmlDoc.responseText);
               
                if (json.status == "success") {}
        }catch(e){
            dhtmlx.message({
                    type: "error",
                    text: "Fatal error on server side: " + loader.xmlDoc.responseText
                });
                console.log(e.stack);
        }
        });  
         */

  },
  changeAccordionSize: function(height, id) {
    var self = this;
    switch (id) {
      case "a1":
        document.getElementById("accordObj").style.height = self.height_mother + "px";
        break;
      case "a2":
        document.getElementById("accordObj").style.height = self.height_HER + "px";
        break;
      case "a3":
        document.getElementById("accordObj").style.height = self.height_HIM + "px";
        break;
      case "a4":
        document.getElementById("accordObj").style.height = self.height_THEM + "px";
        break;
      case "a4":
        document.getElementById("accordObj").style.height = self.height_THEM + "px";
        break;
      case "a5":
        document.getElementById("accordObj").style.height = self.height_AGENCY + "px";
        break;
      default:
        var str = id.replace("a", "t");
        document.getElementById('accordObj').style.height = self.height_other[str] + "px";
        break
    }

    self.dhxAccord.setSizes();
  },
  _createAccordion: function(uid) {
    var self = this;
    if (document.getElementById("accordObj")) {
      self.dhxAccord = new dhtmlXAccordion("accordObj");
      self.dhxAccord.addItem("a1", "EXPECTING MOTHER LETTER");
      self.dhxAccord.addItem("a2", "");
      self.dhxAccord.addItem("a3", "");
      self.dhxAccord.addItem("a4", "LETTER ABOUT THEM");
      self.dhxAccord.addItem("a5", "AGENCY LETTER");
      self.dhxAccord.attachEvent("onBeforeActive", function(itemId) {
        self.changeAccordionSize(uid, itemId);
        return true;
      });
      var rands = new Date().getTime();
      dhtmlxAjax.get(self.configuration[uid].application_path + "processors/profile_view_Accordioninformation.php?id=" + self.getUrlVars()["id"] + '&rand=' +rands, function(loader) {
        try {
          var json = JSON.parse(loader.xmlDoc.responseText);
          var count = 5;
          if (json.status == "success") {
            //alert(document.getElementById("mother").innerHTML+'h');



            // document.getElementById("accordObj").offsetHeight=898;                  
            //var height =document.getElementById("mother").offsetHeight+150;
            //document.getElementById("accordObj").setAttribute("style","height:"+height+"px");
self.dhxAccord.cells("a2").setText("LETTER ABOUT "+json.Profiles.data[8]);
self.dhxAccord.cells("a3").setText("LETTER ABOUT "+json.Profiles.data[7]);   


            if ((json.Profiles.data[4]) == '') {
              self.dhxAccord.cells("a1").hide();
              count--;
            }
            if ((json.Profiles.data[5]) == '') {
              self.dhxAccord.cells("a2").hide();
              count--;
            }
            if ((json.Profiles.data[1]) == '') {
              self.dhxAccord.cells("a3").hide();
              count--;
            }
            if ((json.Profiles.data[3]) == '') {
              self.dhxAccord.cells("a4").hide();
              count--;
            }
            if ((json.Profiles.data[2]) == '') {
              self.dhxAccord.cells("a5").hide();
              count--;
            }

          }
          if (json.other.count > 0) {
            count = count + json.other.count;
            var width = count * 30;
            self.height_other = new Array();
            for (var i = 6; i < json.other.count + 6; i++) {
              self.dhxAccord.addItem("a" + i, json.other[i - 6].label);
              document.getElementById("other").innerHTML += '<div class="letterDivBody" id="t' + i + '" style="height: auto;overflow: auto">' + json.other[i - 6].description + '</div>';
              self.height_other['t' + i] = document.getElementById('t' + i).offsetHeight + width;
              self.dhxAccord.cells("a" + i).attachObject("t" + i);
            }

          }
          var width = count * 30;
          document.getElementById("mother").innerHTML = json.Profiles.data[4];
          self.height_mother = document.getElementById("mother").offsetHeight + width;

          document.getElementById("HER").innerHTML += json.Profiles.data[5];
          self.height_HER = document.getElementById("HER").offsetHeight + width;

          document.getElementById("HIM").innerHTML += json.Profiles.data[1];
          self.height_HIM = document.getElementById("HIM").offsetHeight + width;

          document.getElementById("THEM").innerHTML += json.Profiles.data[3];
          self.height_THEM = document.getElementById("THEM").offsetHeight + width;

          document.getElementById("AGENCY").innerHTML += json.Profiles.data[2];
          self.height_AGENCY = document.getElementById("AGENCY").offsetHeight + width;

          self.dhxAccord.cells("a1").attachObject("mother");
          self.dhxAccord.cells("a2").attachObject("HER");
          self.dhxAccord.cells("a3").attachObject("HIM");
          self.dhxAccord.cells("a4").attachObject("THEM");
          self.dhxAccord.cells("a5").attachObject("AGENCY");
          var firstOpenCell = ((json.Profiles.data[4]) != '') ? "a1" : ((json.Profiles.data[5]) != '') ? "a2" : ((json.Profiles.data[1]) != '') ? "a3" : ((json.Profiles.data[3]) != '') ? "a4" : "a5";
          self.dhxAccord.cells(firstOpenCell).open();
          self.changeAccordionSize(uid, firstOpenCell);
          if (count == 0 && json.other.count == 0) {
            document.getElementById("letter_text").innerHTML = 'No letters to Display';
            document.getElementById('letter_text').className += ' errormessage';
            document.getElementById("letter_text").style.marginTop = '200px';
            document.getElementById("letter_text").style.marginLeft = '150px';
          }
        } catch (e) {
          dhtmlx.message({
            type: "error",
            text: "Fatal error on server side: " + loader.xmlDoc.responseText
          });
          console.log(e.stack);
        }
      });
    }
  },
  init: function(model) {
    var self = this;
    self.model = model;
    // self.site = site;
  },
  start: function(configuration) {
    var self = this;
    self.uid = configuration.uid;
    self.site = configuration.site;

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
    self._loadPhoto(self.uid);
    self._loadVideo(self.uid);
    self._createAccordion(self.uid);

    $("#Approved").click(function() {
      self._approve(self.uid);
    });
    $("#Improvement").click(function() {
      self._improve(self.uid);
    });
  },
  errorAlert: function(msg) {
    dhtmlx.message({
      type: "alert-error",
      text: msg,
    });
  }

}


ProfileViewComponent.init(ProfileViewComponent_Model);