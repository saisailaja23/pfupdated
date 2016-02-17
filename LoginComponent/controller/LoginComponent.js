/******************************************************************
 * Name:    Prashanth A
 * Date:    07/11/2013
 * Purpose: For validating and authenicating users
 *******************************************************************/
var LoginComponent = {
  uid: null,
  window_manager: null,
  window: [],
  layout: [],
  form: [],
  status_bar: [],
  configuration: [],
  _window_manager: function () {
    var self = this;
    self.window_manager = new dhtmlXWindows();
    self.window_manager.setImagePath(self.model.conf_window.image_path);
    self.window_manager.enableAutoViewport(false);
    self.window_manager.attachViewportTo(vp);
  },
  _window: function (uid) {
    var self = this;
    if (self.window_manager === null)
      self._window_manager();
    if (self.window_manager.isWindow("window_LoginComponent_" + uid)) {
      self.window[uid].show();
      self.window[uid].bringToTop();
      return;
    }
    self.window[uid] = self.window_manager.createWindow(
      "window_LoginComponent_" + uid,
      self.model.conf_window.left + 10,
      self.model.conf_window.top + 10,
      self.model.conf_window.width,
      self.model.conf_window.height);
    self.window[uid].setText(self.model.text_labels.main_window_title);
    self.window[uid].setIcon(self.model.conf_window.icon, self.model.conf_window.icon_dis);
    self.window[uid].button("park").hide();
    self.window[uid].button("minmax1").hide();
    self.window[uid].button("minmax2").hide();
    self.window[uid].setModal(true);
    self.window[uid].centerOnScreen();
    var pos = self.window[uid].getPosition();
    var offset = (document.body.scrollTop ? document.body.scrollTop : window.pageYOffset);
    self.window[uid].setPosition(pos[0], pos[1] + offset)
    self.window[uid].attachEvent("onClose", function (win) {
      $("#vp").css("overflow", "visible");
      //self.window[uid].close();
      return true;
    });
  },
  _layout: function (uid) {
    var self = this;
    self.layout[uid] = self.window[uid].attachLayout(self.model.conf_layout.pattern);
    self.layout[uid].cells("a").hideHeader();
  },
  _form: function (uid) {
    var self = this;
    var conf_form = self.model.conf_form.template;
    self.form = self.layout[uid].cells("a").attachForm(conf_form);
    self.form.attachEvent("onButtonClick", function (name, command) {
      if (name == 'login') {
        return self.submifForm(uid);
      }
      if (name == 'joinnow') {
        self.joinComp();
        self.window[uid].close();
      }
    });
    // $('body').on('keypress', function (e) { // Code commented by Satya to solve the Invalid login alerts issue.
    //   if (e.which === 13 && $('.LoginBox').length) {
    //     // if ($('.dhtmlx_popup_button').length) {

    //     //   $('.dhtmlx_popup_button').click();
    //     //   return;
    //     // }
    //     return self.submifForm(uid);
    //   }
    // });



    //        self.form.attachEvent("onButtonClick", function (name, command) {
    //           if(name == 'login') 
    //           {
    //           return self.submifForm(uid);            
    //           }
    //        });

    // Event chagned by Satya from onKeyUp to onEnter
    self.form.attachEvent("onEnter", function (inp, ev, id, value) {
      // if (self.form.getItemValue("username") != '' && self.form.getItemValue("password") != '' && ev.which == 13) // Condition commented by Satya for empty fields validaton.

      // if(name == 'login') 
      //  { 
      return self.submifForm(uid);
      //  }
    })

    self.form.attachEvent("onButtonClick", function (name, command) {
      if (name == 'forgot') {
        self.submifForm_forgot(uid);
      }

    });

    self.form.setItemFocus("username");
  },
  joinComp: function () {
    var e = this;
    MemberComponent.start({
      uid: (new Date()).getTime(),
      siteurl: siteurl,
      application_path: siteurl + "/MemberComponent/",
      dhtmlx_codebase_path: siteurl + "/plugins/dhtmlx/"
    });

    // JoinComponent.start({
    //   uid: (new Date).getTime(),
    //   application_path: siteurl + "JoinComponent/",
    //   dhtmlx_codebase_path: siteurl + "/plugins/dhtmlx/"
    // })
  },
  submifForm: function (uid) {
    var self = this;
    if (self.form.getItemValue("username") == '') {
      dhtmlx.message({
        type: "alert-error",
        text: "You must enter username"
      })
      return false;
    }
    if (self.form.getItemValue("password") == '') {
      dhtmlx.message({
        type: "alert-error",
        text: "You must enter password"
      })
      return false;
    }

    var uname = self.form.getItemValue("username");
    var pass = self.form.getItemValue("password");
    var remem = self.form.getItemValue("remember");

    var poststr = "username=" + uname + "&password=" + pass + "&remember=" + remem;
    // Validating the username and password
    dhtmlxAjax.post(self.configuration[uid].application_path + "processors/check_login.php", poststr, function (loader) {

      var returnval = JSON.parse(loader.xmlDoc.responseText);
      if (returnval.response) {
        window.location.href = returnval.redirect;
      } else {
        dhtmlx.message({
          type: "alert-error",
          text: "Your username or password was incorrect. Please try again."
        })

      }

    });
    return true;
  },

  submifForm_forgot: function (uid) {
    var self = this;
    join_window = new dhtmlXWindows();
    // creation of join form window
    var conf_form_forgot = self.model.conf_form.template_forgot;
    var win_join = join_window.createWindow("w1", 510, 200, 540, 280);
    //   var win_join = join_window.createWindow("w1", "", "", popwidth, popheight);
    win_join.button("park").hide();
    win_join.button("minmax1").hide();
    win_join.button("minmax2").hide();
    win_join.centerOnScreen();
    var pos = win_join.getPosition();
    var offset = (document.body.scrollTop ? document.body.scrollTop : window.pageYOffset);
    win_join.setPosition(pos[0], pos[1] + offset)
    //self.window[ uid ].close();
    self.window[uid].close();
    win_join.setText("");
    win_join.setModal(true);
    forgot_form = win_join.attachForm(conf_form_forgot);


    forgot_form.attachEvent("onButtonClick", function (name, command) {
      //  self._join_save(uid, join_form, name, Profilevalue, json);


      var email = forgot_form.getItemValue("email");


      if (email != '' || email == '') {


        var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

        if (!filter.test(email)) {

          dhtmlx.message({
            type: "alert-error",
            text: "You must enter valid email"
          })
          return false;
        }

      }



      var poststr = "Email=" + email;

      dhtmlxAjax.post(self.configuration[uid].application_path + "processors/forogot.php", poststr, function (loader) {

        //   var returnval = JSON.parse(loader.xmlDoc.responseText);
        //if (returnval.response) {
        //   window.location.href = returnval.redirect;
        //  } 

        var returnval = JSON.parse(loader.xmlDoc.responseText);

        if (returnval.email_status > 0) {
          dhtmlx.message({
            type: "alert-error",
            text: "A new password has been sent to the email entered."
          })
          win_join.close();
        } else {
          dhtmlx.message({
            type: "alert-error",
            text: returnval.response
          })

        }



      });

    });







  },
  init: function (model) {
    var self = this;
    self.model = model;
  }

  ,
  start: function (configuration) {
    var self = this;
    self.uid = configuration.uid;
    self.siteurl = configuration.siteurl;

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

    self.model.conf_window.image_path = configuration.application_path + configuration.icons_path;

    self._window(self.uid);
    self._layout(self.uid);
    self._form(self.uid)

  }

}
LoginComponent.init(LoginComponent_Model);