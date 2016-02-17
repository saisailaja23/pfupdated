/***********************************************************************
 * Name:    Prashanth A
 * Date:    19/11/2013
 * Purpose: Creating a agency public profile page
 ***********************************************************************/
var Familydashboardcomponent = {
  uid: null,
  form: [],
  configuration: [],
  dataStore: [],
  getUrlVars: function () {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
      vars[key] = value;
    });

    return vars;
  },
  //_loadData: function (uid, callBack) {
//    var self = this;
//    var postStr = "1-1";
//    dhtmlxAjax.post(self.configuration[uid].application_path + "processors/agency_view_information.php?id=" + self.getUrlVars()["id"], postStr, function (loader) {
//      try {
//        var json = JSON.parse(loader.xmlDoc.responseText);
//
//        if (json.status == "success") {
//          self.dataStore["Profiles_active"] = json.Profiles_active;
//          self.dataStore["agency_address"] = json.agency_address;
//          self.dataStore["inboxmess"] = json.inboxmess;
//          self.dataStore["unreadmess"] = json.unreadmess;
//          self.dataStore["sentmess"] = json.sentmess;
//          self.dataStore["agencymembers"] = json.agencymembers;
//          self.dataStore["logged"] = json.logged;
//          if (callBack)
//            callBack();
//        } else {
//          dhtmlx.message({
//            type: "error",
//            text: json.response
//          });
//        }
//      } catch (e) {
//        dhtmlx.message({
//          type: "error",
//          text: "Fatal error on server side: " + loader.xmlDoc.responseText
//        });
//        console.log(e.stack);
//      }
//    });
//
//
 //},

  _form: function (uid,Deano) {
  var self = this;
//    var json = self.dataStore;
//    var agency_address = json.agency_address.rows;
//    var Profiles_last_active = json.Profiles_active.rows;
//    var mail_inbox = json.inboxmess.rows;
//    var mail_unread = json.unreadmess.rows;
//    var mail_sent = json.sentmess.rows;
//    var profile_image = json.agencymembers.rows;
//    var logged = json.logged.rows;
//
//    if (document.getElementById("agencylikes")) {
//      //  var conf_form = self.model.conf_form.template_agencyview;
//      //var Agency_view = new dhtmlXForm("agencylikes", conf_form);
//    }
//    if (document.getElementById("change_pass")) {
//      var conf_form_pass = self.model.conf_form.change_pass;
//      var change_pass = new dhtmlXForm("change_pass", conf_form_pass);
//    }
//    for (i in profile_image) {
//      var profile_memberimage = profile_image[i].data[0];
//
//      var Image = 'modules/boonex/avatar/data/slider/' + profile_memberimage + '.jpg';
//
//      var photo = '<img  src="' + Image + '" >';
//      if (document.getElementById("galleria")) {
//        document.getElementById("galleria").innerHTML += photo;
//
//      }
//    }
//    if (document.getElementById("galleria")) {
//      $('#galleria').galleria();
//    }
//
//    for (i in agency_address) {
//      var profile_agency_names = agency_address[i].data[0];
//      var profile_agency_city = agency_address[i].data[1];
//      var profile_agency_state = agency_address[i].data[2];
//      var profile_agency_zip = agency_address[i].data[3];
//      var profile_agency_country = agency_address[i].data[4];
//
//
//      var profile_email = agency_address[i].data[6];
//      var profile_website = agency_address[i].data[7];
//      var profile_agency_desc = agency_address[i].data[9];
//      var profile_facebook = agency_address[i].data[10];
//      var profile_twitter = agency_address[i].data[11];
//      var profile_google = agency_address[i].data[12];
//      var profile_blogger = agency_address[i].data[13];
//      var profile_pinerest = agency_address[i].data[14];
//      var profile_id = agency_address[i].data[15];
//      var profile_phnnum = agency_address[i].data[5];
//      var agency_uri = agency_address[i].data[16];
//
//      if (profile_phnnum != '' && profile_phnnum != '0')
//        var profile_phonenumber = agency_address[i].data[5][0] + agency_address[i].data[5][1] + agency_address[i].data[5][2] + '-' +
//          agency_address[i].data[5][3] + agency_address[i].data[5][4] + agency_address[i].data[5][5] + '-' +
//          agency_address[i].data[5][6] + agency_address[i].data[5][7] + agency_address[i].data[5][8] + agency_address[i].data[5][9];
//      else
//        profile_phonenumber = '';
//
//      if (document.getElementById("agencydesc"))
//        document.getElementById("agencydesc").innerHTML = profile_agency_desc;
//
//      if (profile_facebook == '') {
//        $('.icoSmFb').removeClass('icoSmFb');
//      } else {
//        if (profile_facebook.substr(0, 7) != 'http://') {
//          document.getElementById("facebookurl").href = '//' + profile_facebook;
//        } else {
//          document.getElementById("facebookurl").href = profile_facebook;
//
//        }
//      }
//      if (profile_twitter == '') {
//        $('.icoSmTw').removeClass('icoSmTw');
//      } else {
//
//        if (profile_twitter.substr(0, 7) != 'http://') {
//          document.getElementById("twitterurl").href = '//' + profile_twitter;
//        } else {
//          document.getElementById("twitterurl").href = profile_twitter;
//        }
//      }
//      if (profile_google == '') {
//        $('.icoSmGo').removeClass('icoSmGo');
//      } else {
//
//        if (profile_google.substr(0, 7) != 'http://') {
//          document.getElementById("googleurl").href = '//' + profile_google;
//        } else {
//          document.getElementById("googleurl").href = profile_google;
//        }
//      }
//      if (profile_pinerest == '') {
//        $('.icoSmPi').removeClass('icoSmPi');
//      } else {
//
//
//
//        if (profile_pinerest.substr(0, 7) != 'http://') {
//          document.getElementById("pineresturl").href = '//' + profile_pinerest;
//        } else {
//          document.getElementById("pineresturl").href = profile_pinerest;
//        }
//      }
//      if (profile_blogger == '') {
//        $('.icoSmBi').removeClass('icoSmBi');
//      } else {
//
//
//        if (profile_blogger.substr(0, 7) != 'http://') {
//          document.getElementById("bloggerurl").href = '//' + profile_blogger;
//        } else {
//
//          document.getElementById("bloggerurl").href = profile_blogger;
//        }
//      }
//    }
//
//    if (document.getElementById("lastactive"))
//      document.getElementById("lastactive").innerHTML = Profiles_last_active;
//    if (document.getElementById("agencyname"))
//      document.getElementById("agencyname").innerHTML = profile_agency_names;
//    if (document.getElementById("agencyemail"))
//      document.getElementById("agencyemail").innerHTML = profile_website;
//
//    if (document.getElementById("agencynames"))
//      document.getElementById("agencynames").innerHTML = 'ABOUT ' + profile_agency_names;
//    if (document.getElementById("pemail"))
//      document.getElementById("pemail").innerHTML = profile_email;
//    if (document.getElementById("phonenumber"))
//      document.getElementById("phonenumber").innerHTML = profile_phonenumber;
//    if (document.getElementById("agency_address"))
//      if (profile_agency_city || profile_agency_zip) {
//        pcity = profile_agency_city + ',';
//        pzip = '-' + profile_agency_zip;
//      } else {
//        pcity = profile_agency_city;
//        pzip = profile_agency_zip;
//      }
//    document.getElementById("agency_address").innerHTML = pcity + profile_agency_state + ',' + profile_agency_country + pzip;
//    if (document.getElementById("inbox"))
//      document.getElementById("inbox").innerHTML = mail_inbox + ' messages;' + mail_unread + ' unread';
//    if (document.getElementById("sent"))
//      document.getElementById("sent").innerHTML = mail_sent + ' messages'
//    if (document.getElementById('editprofile'))
//      document.getElementById('editprofile').href = 'extra_agency_builder_26.php';
//    if (document.getElementById('approvechange'))
//      document.getElementById('approvechange').href = 'm/groups/browse_fans_list/' + agency_uri;
//
//    $("#agencylikes").on('click', function () {
//      LikeComp_controller.start({
//        uid: (new Date()).getTime(),
//        callback: null,
//        extra: {}
//      });
//    });

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

//    if (change_pass) {
//      change_pass.attachEvent("onButtonClick", function (name, command) {
//
//        var old_password = change_pass.getItemValue("old_pass");
//        var new_password = change_pass.getItemValue("new_pass");
//        var confirm_password = change_pass.getItemValue("confirm_pass");
//
//        if (name == "changepassword") {
//
//
//
//          if (old_password == '') {
//
//            dhtmlx.message({
//              type: "alert-error",
//              text: "Please enter the old password"
//            })
//            return false;
//          }
//
//
//          if (change_pass.getItemValue("newpassword") != '') {
//
//            if (new_password != confirm_password) {
//
//              dhtmlx.message({
//                type: "alert-error",
//                text: "The new password and confirmation password do not match."
//              })
//              return false;
//            }
//
//          }
//
//
//          var poststr = "new_pass=" + new_password + '&old_password=' + old_password;
//          dhtmlx.confirm({
//            type: "confirm",
//            text: "If u change you password you have to re login<br/> press <b>OK</b> to continue ",
//            callback: function (result) {
//              if (result == true) {
//                // Inserting values to database
//                dhtmlxAjax.post(self.configuration[uid].application_path + "processors/change_password.php", poststr, function (loader) {
//                  var json = JSON.parse(loader.xmlDoc.responseText);
//                  if (json.status == "success") {
//                    location.reload();
//                  } else {
//                    dhtmlx.message({
//                      type: "error",
//                      text: json.response
//                    });
//                  }
//                });
//
//              }
//            }
//          });
//
//        }
//
//      });
//    }

    if(deano)
    if (document.getElementById("data_container")) {
      var conf_form_list = self.model.conf_form.template_agencylist;
      var Agency_view_list = new dhtmlXForm("data_container", conf_form_list);


      var view = new dhtmlXDataView({
        container: Agency_view_list.getContainer("data_container"),
        height: "auto",
        type: {
         template: "http->" + self.configuration[self.uid].application_path + "/templ/adotive_family_info.html",
          width: 127, //width of single item
          height: 250, //height of single item
          padding: 0
          // margin: 5
        }


      });
      view.define("select", false);
      $('.dhxform_container').html("<div class='loader'>loading...</div>")
      view.load("Familydashboardcomponent/processors/agency_members.php", "json", function () {
        $(".active").on("click", function () {
          var profile_id = $(this).val();
          var profile_status = "Active";
          var poststr = "ProfileId=" + profile_id + "&ProfileStatus=" + profile_status;
          dhtmlxAjax.post(self.configuration[uid].application_path + "processors/update_profile_status.php", poststr, function (loader) {
            dhtmlx.message({
              text: 'Status changed to active'
            })
          });

          var poststr = "id=" + profile_id + "&status=none"
          dhtmlxAjax.post(self.configuration[uid].application_path + "processors/watermark_latest.php", poststr, function (loader) {
            document.location.reload(true);
          });
        });

        $(".inactive").on("click", function () {
          var profile_id = $(this).val();
          var profile_status = "Approval";
          var poststr = "ProfileId=" + profile_id + "&ProfileStatus=" + profile_status;
          dhtmlxAjax.post(self.configuration[uid].application_path + "processors/update_profile_status.php", poststr, function (loader) {
            dhtmlx.message({
              text: 'Status changed to inactive'
            })
          });

          var poststr = "id=" + profile_id + "&status=none"
          dhtmlxAjax.post(self.configuration[uid].application_path + "processors/watermark_latest.php", poststr, function (loader) {
            document.location.reload(true);
          });
        });

        $(".match").on("click", function () {
          var profile_id = $(this).val();
          var profile_status = "matched";
          var poststr = "id=" + profile_id + "&status=" + profile_status
          dhtmlxAjax.post(self.configuration[uid].application_path + "processors/watermark_latest.php", poststr, function (loader) {
            dhtmlx.message({
              text: 'Status changed to matched'
            })
            document.location.reload(true);
            // view.load("Familydashboardcomponent/processors/agency_members.php", "json");
          });
        });

        $(".place").on("click", function () {
          var profile_id = $(this).val();
          var profile_status = "placed";
          var poststr = "id=" + profile_id + "&status=" + profile_status
          dhtmlxAjax.post(self.configuration[uid].application_path + "processors/watermark_latest.php", poststr, function (loader) {
            dhtmlx.message({
              text: 'Status changed to placed'
            })
            document.location.reload(true);


          });
        });

      });
      // view.refresh();

    }
    
  },
//  _loadPhoto: function (uid) {
//    if (document.getElementById("sliderFrame")) {
//      var self = this;
//      var count = 0;
//      var flag = self.configuration[self.uid].slider
//      var postStr = "approve=1&from=" + flag;
//      dhtmlxAjax.post(self.configuration[uid].application_path + "processors/getphotosvideos.php?p=edit&id=" + self.getUrlVars()["id"], postStr, function (loader) {
//        var json = JSON.parse(loader.xmlDoc.responseText);
//        var photo = '';
//        var photoSub = ''
//        if (json.status == "success") {
//          for (i in json.data) {
//            var profile_memberimage = json.data[i];
//            document.getElementById("slider").innerHTML += json.data[i];
//            document.getElementById("thumbs").innerHTML += json.bData[i];
//
//          }
//          var lastimagePosstion = (json.data.length > 10) ? 10 : json.data.length;
//          slider.init({
//            toatalimages: json.data.length,
//            lastimage: lastimagePosstion
//          });
//        } else {
//          document.getElementById("sliderFrame").innerHTML = '';
//          document.getElementById("sliderFrame").style.background = '#FFFFFF';
//          document.getElementById("sliderFrame").style.border = "1px solid #CCCCCC";
//          document.getElementById("sliderFrame").style.padding = 0;
//          document.getElementById("sliderFrame").setAttribute("class", "mainPhotoBlock");
//          document.getElementById("sliderFrame").innerHTML = '<div style="text-align: center;margin-top:30px;">' + json.Pmessage + '</div>';
//
//        }
//      });
//    } else {
//      // document.getElementById("wowslider-container1").style.border=0;
//    }
//  },
  init: function (model) {
    var self = this;
    self.model = model;

  }

  ,
  start: function (configuration) {
    var self = this;
    self.uid = configuration.uid;

    self.Deano = configuration.deano_tool;
    
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
 //   self._loadPhoto(self.uid);
 //   self._loadData(self.uid, function () {
      self._form(self.uid,self.Deano);
  //  });

  }

}
Familydashboardcomponent.init(Familydashboardcomponent_Model);