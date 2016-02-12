/***********************************************************************
 * Name:    Prashanth A
 * Date:    19/11/2013
 * Purpose: Creating a agency public profile page
 ***********************************************************************/
var Agencyprofileviewcomponent = {
  uid: null,
  form: [],
  configuration: [],
  dataStore: [],
  getUrlVars: function() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) {
      vars[key] = value;
    });
    console.log(vars);
    return vars;
  },
  _loadData: function(uid, callBack) {
    var self = this;
    var postStr = "1-1";
    dhtmlxAjax.post(self.configuration[uid].application_path + "processors/agency_view_information.php?id=" + self.getUrlVars()["ID"], postStr, function(loader) {
      try {
        var json = JSON.parse(loader.xmlDoc.responseText);

        if (json.status == "success") {
          self.dataStore["Profiles_active"] = json.Profiles_active;
          self.dataStore["agency_address"] = json.agency_address;
          self.dataStore["inboxmess"] = json.inboxmess;
          self.dataStore["unreadmess"] = json.unreadmess;
          self.dataStore["sentmess"] = json.sentmess;
          self.dataStore["agencymembers"] = json.agencymembers;
          self.dataStore["logged"] = json.logged;
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
    var json = self.dataStore;
    var agency_address = json.agency_address.rows;
    var Profiles_last_active = json.Profiles_active.rows;
    var mail_inbox = json.inboxmess.rows;
    var mail_unread = json.unreadmess.rows;
    var mail_sent = json.sentmess.rows;
    var profile_image = json.agencymembers.rows;
    var logged = json.logged.rows;

    if (document.getElementById("agencylikes")) {
      //  var conf_form = self.model.conf_form.template_agencyview;
      //var Agency_view = new dhtmlXForm("agencylikes", conf_form);
    }
    if (document.getElementById("change_pass")) {
      var conf_form_pass = self.model.conf_form.change_pass;
      var change_pass = new dhtmlXForm("change_pass", conf_form_pass);
    }
    for (i in profile_image) {
      var profile_memberimage = profile_image[i].data[0];

      var Image = 'modules/boonex/avatar/data/slider/' + profile_memberimage + '.jpg';

      var photo = '<img  src="' + Image + '" >';
      if (document.getElementById("galleria")) {
        document.getElementById("galleria").innerHTML += photo;

      }
    }
    if (document.getElementById("galleria")) {
      $('#galleria').galleria();
    }
    // console.log(self.getUrlVars()["ID"]);debugger;
    if (self.getUrlVars()["ID"]) {
      for (i in agency_address) {
        var profile_agency_names = agency_address[i].data[0];
        var profile_agency_city = agency_address[i].data[1];
        var profile_agency_state = agency_address[i].data[2];
        var profile_agency_zip = agency_address[i].data[3];
        var profile_agency_country = agency_address[i].data[4];


        var profile_email = agency_address[i].data[6];
        var profile_website = agency_address[i].data[7];
        var profile_agency_desc = agency_address[i].data[9];
        var profile_facebook = agency_address[i].data[10];
        var profile_twitter = agency_address[i].data[11];
        var profile_google = agency_address[i].data[12];
        var profile_blogger = agency_address[i].data[13];
        var profile_pinerest = agency_address[i].data[14];
        var profile_id = agency_address[i].data[15];
        var profile_phnnum = agency_address[i].data[5];
        var agency_uri = agency_address[i].data[16];

        if (profile_phnnum != '' && profile_phnnum != '0'){
          /*var profile_phonenumber = agency_address[i].data[5][0] + agency_address[i].data[5][1] + agency_address[i].data[5][2] + '-' +
            agency_address[i].data[5][3] + agency_address[i].data[5][4] + agency_address[i].data[5][5] + '-' +
            agency_address[i].data[5][6] + agency_address[i].data[5][7] + agency_address[i].data[5][8] + agency_address[i].data[5][9];*/
            var profile_phonenumber = profile_phnnum;          
              }else{
          var profile_phonenumber = '';
          }
        if (document.getElementById("agencydesc"))
          document.getElementById("agencydesc").innerHTML = profile_agency_desc;

        if (profile_facebook == '') {
          $('.icoSmFb').removeClass('icoSmFb');
        } else {
          if (profile_facebook.substr(0, 7) != 'http://') {
            document.getElementById("facebookurl").href = '//' + profile_facebook;
          } else {
            document.getElementById("facebookurl").href = profile_facebook;

          }
        }
        if (profile_twitter == '') {
          $('.icoSmTw').removeClass('icoSmTw');
        } else {

          if (profile_twitter.substr(0, 7) != 'http://') {
            document.getElementById("twitterurl").href = '//' + profile_twitter;
          } else {
            document.getElementById("twitterurl").href = profile_twitter;
          }
        }
        if (profile_google == '') {
          $('.icoSmGo').removeClass('icoSmGo');
        } else {

          if (profile_google.substr(0, 7) != 'http://') {
            document.getElementById("googleurl").href = '//' + profile_google;
          } else {
            document.getElementById("googleurl").href = profile_google;
          }
        }

        //                if (profile_pinerest == '') {} else {
        //                    if (profile_pinerest.substr(0, 7) != 'http://') {} else {
        //                        document.getElementById("pineresturl").href = profile_pinerest;
        //                    }
        //                }


        if (profile_pinerest == '') {
          $('.icoSmPi').removeClass('icoSmPi');
        } else {
          if (profile_pinerest.substr(0, 7) != 'http://') {
            document.getElementById("pineresturl").href = '//' + profile_pinerest;
          } else {
            document.getElementById("pineresturl").href = profile_pinerest;
          }
        }





        if (profile_blogger == '') {
          $('.icoSmBi').removeClass('icoSmBi');
        } else {
          if (profile_blogger.substr(0, 7) != 'http://') {
            document.getElementById("bloggerurl").href = '//' + profile_blogger;
          } else {
            document.getElementById("bloggerurl").href = profile_blogger;
          }
        }
      }
      if (profile_agency_names.length > 33) {

        profile_agency_names_completestring = '<div class="tooltip" title="' + profile_agency_names + '" ><span  title= "" >' + profile_agency_names.substr(0, 30) + '...</span></div>';
      } else {
        profile_agency_names_completestring = profile_agency_names;
      }

      if (document.getElementById("lastactive"))
        document.getElementById("lastactive").innerHTML = Profiles_last_active;
      if (document.getElementById("agencyname"))
        document.getElementById("agencyname").innerHTML = profile_agency_names_completestring;
      if (document.getElementById("agencyemail"))
        document.getElementById("agencyemail").innerHTML =  '<a href="//'+profile_website+'">'+profile_website+'</a>'  ;

      if (document.getElementById("agencynames"))
        if (document.getElementById("pemail"))
          document.getElementById("pemail").innerHTML = '<a href="mailto:'+profile_email+'">'+profile_email+'</a>'  ;//profile_email;
      document.getElementById("phonenumber").innerHTML = profile_phonenumber;
      if (document.getElementById("agency_address"))
        if (profile_agency_city || profile_agency_zip) {
          pcity = profile_agency_city + ',';
          pzip = '-' + profile_agency_zip;
        } else {
          pcity = profile_agency_city;
          pzip = profile_agency_zip;
        }
      document.getElementById("agency_address").innerHTML = pcity + profile_agency_state + ',' + profile_agency_country + pzip;
    }
    //   if (document.getElementById("inbox"))
    //     document.getElementById("inbox").innerHTML = mail_inbox + ' messages;' + mail_unread + ' unread';
    //   if (document.getElementById("sent"))
    //     document.getElementById("sent").innerHTML = mail_sent + ' messages'
    //   if (document.getElementById('editprofile'))
    //      document.getElementById('editprofile').href = 'extra_agency_builder_26.php';
    //   if (document.getElementById('approvechange'))
    //      document.getElementById('approvechange').href = 'm/groups/browse_fans_list/' + agency_uri;

    $("#agencylikes").on('click', function() {
      LikeComp_controller.start({
        uid: (new Date()).getTime(),
        like: self.getUrlVars()["ID"],
        ptype:8,
        callback: null,
        extra: {}
      });
    });

    // if (Agency_view) {
    //   Agency_view.attachEvent("onButtonClick", function (name, command) {
    //     LikeComp_controller.start({
    //       uid: (new Date()).getTime(),
    //       callback: null,
    //       extra: {}
    //     });
    //     //            var poststr = "Agencyid=" + profile_id;
    //     //            dhtmlxAjax.post(self.configuration[uid].application_path + "processors/insert_like_agency.php", poststr, function (loader) {
    //     //  });

    //   });
    // }

    if (change_pass) {
      change_pass.attachEvent("onButtonClick", function(name, command) {

        var old_password = change_pass.getItemValue("old_pass");
        var new_password = change_pass.getItemValue("new_pass");
        var confirm_password = change_pass.getItemValue("confirm_pass");

        if (name == "changepassword") {



          if (old_password == '') {

            dhtmlx.message({
              type: "alert-error",
              text: "Please enter the old password"
            })
            return false;
          }


          if (change_pass.getItemValue("newpassword") != '') {

            if (new_password != confirm_password) {

              dhtmlx.message({
                type: "alert-error",
                text: "The new password and confirmation password do not match."
              })
              return false;
            }

          }


          var poststr = "new_pass=" + new_password + '&old_password=' + old_password;
          dhtmlx.confirm({
            type: "confirm",
            text: "If u change you password you have to re login<br/> press <b>OK</b> to continue ",
            callback: function(result) {
              if (result == true) {
                // Inserting values to database
                dhtmlxAjax.post(self.configuration[uid].application_path + "processors/change_password.php", poststr, function(loader) {
                  var json = JSON.parse(loader.xmlDoc.responseText);
                  if (json.status == "success") {
                    location.reload();
                  } else {
                    dhtmlx.message({
                      type: "error",
                      text: json.response
                    });
                  }
                });

              }
            }
          });

        }

      });
    }

    if (document.getElementById("data_container")) {
      var conf_form_list = self.model.conf_form.template_agencylist;
      var Agency_view_list = new dhtmlXForm("data_container", conf_form_list);


      self.view = new dhtmlXDataView({
        container: Agency_view_list.getContainer("data_container"),
        //  height: "auto",
        type: {
          template: "<div class='agencyMod'>\n\
          <div class='meida'>{obj.profile_image}</div>\n\
          <div class='agencyName'>{obj.profile_firstname}</div>\n\
          <div class='state'>\n\
             <label><input type='radio' value='{obj.profile_id}' name='{obj.selected_random}' class='active' {obj.selected_active} />ACTIVE</label>\n\
            <label><input type='radio' value='{obj.profile_id}' name='{obj.selected_random}' class='inactive' {obj.selected_inactive} />INACTIVE</label>\n\
            <label><input type='radio' value='{obj.profile_id}' name='{obj.selected_random}' class='match' {obj.selected_match} />MATCHED</label>\n\
            <label><input type='radio' value='{obj.profile_id}' name='{obj.selected_random}' class='place' {obj.selected_placed} />PLACED</label>\n\
            \n\ <label><input type='radio' value='{obj.profile_id}' name='{obj.selected_random}' class='deleted'  />DELETE</label>\n\
<label><div class='statusApprovedUser' id={obj.profile_id}>EDIT PROFILE</div></label>\n\
           \n\
             \n\
          </div>\n\
         </div>",
          width: 151, //width of single item
          height: 280, //height of single item
          padding: 0,

          margin: 8
        }


      });

      pager = self.view.define("pager", {
        container: "page_here_approval",
        size: 21
      });

      var count = self.view.dataCount();
      new_one = "#count# Results,{common.prev()} &nbsp; Page {common.page()} from #limit# {common.next()}";

      pager.define("template", new_one);

      self.view.define("select", false);

      pager.attachEvent('onafterpagechange', function() {
        var rands = new Date().getTime();
        self.view.load("Agencyprofileviewcomponent/processors/agency_members.php?randnumer=" + rands);

      });

      self.view.load("Agencyprofileviewcomponent/processors/agency_members.php");

      self.view.attachEvent("onXLE", function() {

        $(".statusApprovedUser").on("click", function() {

          var profile_id = $(this).attr('id')
          var poststr = "ProfileId=" + profile_id;

          dhtmlxAjax.post(self.configuration[uid].application_path + "processors/member_details.php", poststr, function(loader) {
            var returnval = JSON.parse(loader.xmlDoc.responseText);

            if (returnval.status == 'success') {
              window.location.href = siteurl + 'modules/deano/deanos_tools/logon_frame.php?m=' + profile_id + '&p=' + returnval.user_pass + '&am=' + returnval.agency_author_id + '&ap=' + returnval.agency_password + '&t=' + returnval.url_path;
            }
          });

        });
        $(".active").on("click", function() {

          var profile_id = $(this).val();
          var profile_status = "Active";
          var poststr = "ProfileId=" + profile_id + "&ProfileStatus=" + profile_status;
          dhtmlxAjax.post(self.configuration[uid].application_path + "processors/update_profile_status.php", poststr, function(loader) {
            dhtmlx.message({
              text: 'Status changed to active'
            })
            var rands = new Date().getTime();
            self.view.clearAll();
            self.view.load("Agencyprofileviewcomponent/processors/agency_members.php?randnumer=" + rands);

          });

          var poststr = "id=" + profile_id + "&status=none"
          dhtmlxAjax.post(self.configuration[uid].application_path + "processors/watermark_latest.php", poststr, function(loader) {
            var rands = new Date().getTime();
            self.view.clearAll();
            self.view.load("Agencyprofileviewcomponent/processors/agency_members.php?randnumer=" + rands);
          });
        });

        $(".inactive").on("click", function() {
          var profile_id = $(this).val();
          var profile_status = "Approval";
          var poststr = "ProfileId=" + profile_id + "&ProfileStatus=" + profile_status;
          dhtmlxAjax.post(self.configuration[uid].application_path + "processors/update_profile_status.php", poststr, function(loader) {
            dhtmlx.message({
              text: 'Status changed to inactive'
            })

            var rands = new Date().getTime();
            self.view.clearAll();
            self.view.load("Agencyprofileviewcomponent/processors/agency_members.php?randnumer=" + rands);
          });

          var poststr = "id=" + profile_id + "&status=none"
          dhtmlxAjax.post(self.configuration[uid].application_path + "processors/watermark_latest.php", poststr, function(loader) {
            var rands = new Date().getTime();
            self.view.clearAll();
            self.view.load("Agencyprofileviewcomponent/processors/agency_members.php?randnumer=" + rands);
          });
        });

        $(".match").on("click", function() {
          var profile_id = $(this).val();
          var profile_status = "Matched";
          var poststr = "id=" + profile_id + "&status=" + profile_status
          dhtmlxAjax.post(self.configuration[uid].application_path + "processors/watermark_latest.php", poststr, function(loader) {
            dhtmlx.message({
              text: 'Status changed to matched'
            })

            var rands = new Date().getTime();
            self.view.clearAll();
            self.view.load("Agencyprofileviewcomponent/processors/agency_members.php?randnumer=" + rands);
          });
        });

        $(".place").on("click", function() {
          var profile_id = $(this).val();
          var profile_status = "Placed";
          var poststr = "id=" + profile_id + "&status=" + profile_status

          dhtmlxAjax.post(self.configuration[uid].application_path + "processors/watermark_latest.php", poststr, function(loader) {
            dhtmlx.message({
              text: 'Status changed to placed'
            })
            //  document.location.reload(true);
            //  var rands = new Date().getTime();   
            var rands = new Date().getTime();
            self.view.clearAll();
            self.view.load("Agencyprofileviewcomponent/processors/agency_members.php?randnumer=" + rands);

          });
        });
        //  var timestamp = new Date().getUTCMilliseconds();  
        
        
         $(".deleted").on("click", function() {
          var profile_id = $(this).val();
          var profile_status = "Rejected";
          var poststr = "ProfileId=" + profile_id + "&ProfileStatus=" + profile_status;
          dhtmlxAjax.post(self.configuration[uid].application_path + "processors/delete_user.php", poststr, function(loader) {
            dhtmlx.message({
              text: 'The user has been removed'
            })

            var rands = new Date().getTime();
            self.view.clearAll();
            self.view.load("Agencyprofileviewcomponent/processors/agency_members.php?randnumer=" + rands);
          });

    
        });

      });


      self.view.attachEvent("onXLS", function() {
        $('.dhxform_container.dhx_dataview').html('<div class="containeralign loader">Loading...</div>');
      });

    }
  },
  _loadPhoto: function(uid) {
    if (document.getElementById("sliderFrame")) {
      var self = this;
      var count = 0;
      var flag = self.configuration[self.uid].slider
      var postStr = "approve=1&from=" + flag;
      dhtmlxAjax.post(self.configuration[uid].application_path + "processors/getphotosvideos.php?p=edit&id=" + self.getUrlVars()["ID"], postStr, function(loader) {
        var json = JSON.parse(loader.xmlDoc.responseText);
        photo = '',
        photoSub = '',
        slides = '<div id="photo_slider" class="flexslider"><ul class="slides"> ';
        thumbs = '<div id="photo_slider_thumb" class="flexslider"><ul class="slides">';


        if (json.status == "success") {
          if (json.data) {
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
            document.getElementById("sliderFrame").setAttribute("class", "mainPhotoBlock agencyslider");
            //document.getElementById("sliderFrame").innerHTML = '<div style="text-align: center;margin-top:30px;">' + json.Pmessage + '</div>';
            document.getElementById("sliderFrame").innerHTML = '<img src="templates/tmpl_par/images/NO-PHOTOS_icon.png" alt="ParentFinder"  />';

          }
        } else {
          document.getElementById("sliderFrame").innerHTML = '';
          document.getElementById("sliderFrame").style.background = '#e0e1e2';
          document.getElementById("sliderFrame").style.border = "1px solid #CCCCCC";
          document.getElementById("sliderFrame").style.padding = 0;
          document.getElementById("sliderFrame").setAttribute("class", "mainPhotoBlock agencyslider");
          //document.getElementById("sliderFrame").innerHTML = '<div style="text-align: center;margin-top:30px;">' + json.Pmessage + '</div>';
          document.getElementById("sliderFrame").innerHTML = '<img src="templates/tmpl_par/images/NO-PHOTOS_icon.png" alt="ParentFinder"  />';

        }
      });
    } else {
      // document.getElementById("wowslider-container1").style.border=0;
    }
  },
  init: function(model) {
    var self = this;
    self.model = model;

  }

  ,
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
    self._loadPhoto(self.uid);
    self._loadData(self.uid, function() {
      self._form(self.uid);
    });

  }

}
Agencyprofileviewcomponent.init(Agencyprofileviewcomponent_Model);