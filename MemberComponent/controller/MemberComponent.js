/********************
 * By            :Aravind(MSI1308 <aravind.buddha@mediaus.com>)
 * Date          :2014-02-26 16:42
 * Company       :MSI
 * Description:
 ************************/

var MemberComponent = {
  uid: null,
  window_manager: null,
  window: [],
  layout: [],
  form: [],
  status_bar: [],
  configuration: [],
  dataStore: [],
  _window_manager: function () {
    var self = this;
    self.window_manager = new dhtmlXWindows();
    self.window_manager.enableAutoViewport(false);
    self.window_manager.attachViewportTo(vp);
    self.window_manager.setImagePath(self.model.conf_window.image_path);
  },
  _window: function (uid) {
    var self = this;
    if (self.window_manager === null)
      self._window_manager();
    if (self.window_manager.isWindow("window_MemberComponent_" + uid)) {
      self.window[uid].show();
      self.window[uid].bringToTop();
      return;
    }
    self.window[uid] = self.window_manager.createWindow("window_MemberComponent_" + uid, self.model.conf_window.left + 10,
      self.model.conf_window.top + 10, self.model.conf_window.width, self.model.conf_window.height);
    self.window[uid].setText(self.model.text_labels.main_window_title);
    // self.window[ uid ].setIcon( self.model.conf_window.icon, self.model.conf_window.icon_dis );         
    self.window[uid].setModal(true);
    self.window[uid].button("park").hide();
    self.window[uid].button("minmax1").hide();
    self.window[uid].button("minmax2").hide();
    self.window[uid].centerOnScreen();
    var pos = self.window[uid].getPosition();
    var offset = (document.body.scrollTop ? document.body.scrollTop : window.pageYOffset);
    self.window[uid].setPosition(pos[0], pos[1] + offset)
    self.window[uid].attachEvent("onClose", function (win) {
      $("#vp").css("overflow", "visible");
      return true;
    });
    // self.status_bar[ uid ] = self.window[ uid ].attachStatusBar();
  },
  _layout: function (uid) {
    var self = this;
    self.layout[uid] = self.window[uid].attachLayout(self.model.conf_layout.pattern);
    self.layout[uid].cells("a").hideHeader();
  },
  // Getting data from database
  _loadData: function (uid, callBack) {
    var self = this;
    var postStr = "1-1";
    dhtmlxAjax.post(self.configuration[self.uid].application_path + "processors/get_data.php", postStr, function (loader) {
      try {
        var json = JSON.parse(loader.xmlDoc.responseText);
        if (json.status == "success") {
          self.dataStore["aqb_pts_profile_types"] = json.aqb_pts_profile_types;
          self.dataStore["sys_pre_values"] = json.sys_pre_values;
          self.dataStore["sys_pre_values_region"] = json.sys_pre_values_region;
          self.dataStore["sys_acl_levels"] = json.sys_acl_levels;
          self.dataStore["profiles"] = json.profiles;
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
          //  type: "error",
          //   text: "Fatal error on server side: " + loader.xmlDoc.responseText
        });
        console.log(e.stack);
      }
    });
  },
  // Displaying the form       
  _form: function (uid, json) {
    var self = this;
    var conf_form = self.model.conf_form.template;
    self.form = self.layout[uid].cells("a").attachForm(conf_form);

    var profile_types = self.form.getSelect("profiletypes");
    profile_types.options.add(new Option("SELECT", "-1"));

    // Getting values from datastore
    var json = self.dataStore;

    // Populating values to profile type selection 
    var profile_type = json.aqb_pts_profile_types.rows;

    for (var i in profile_type) {
      var profileid = profile_type[i].id[0];
      var profiletype = profile_type[i].data[1];
      profile_types.options.add(new Option(profiletype, profileid));
    }

    // Opening join window
    join_window = new dhtmlXWindows();
    join_window.enableAutoViewport(false);
    join_window.attachViewportTo(vp);
    self.form.attachEvent("onChange", function (name, id) {
      var selectname = self.form.getSelect(name);
      var Profilevalue = selectname.options[selectname.selectedIndex].value;

      /* Resizing window width and height based profile type selection
       * Profile type -1 represents default select
       */

      if (Profilevalue != -1) {

        if (Profilevalue == 2) {
          var popwidth = 600;
          var popheight = 680;
        } else if (Profilevalue == 4) {
          popwidth = 600;
          popheight = 608;
        } else {
          popwidth = 600;
          popheight = 540;
        }

        // creation of join form window
        var conf_form_join = self.model.conf_form.template_join;
        //  var win_join = join_window.createWindow("w1", 440, 100, popwidth, popheight);
        window.win_join = join_window.createWindow("w1", "", "", popwidth, popheight);
        self.window[uid].close();
        win_join.button("park").hide();
        win_join.button("minmax1").hide();
        win_join.centerOnScreen();
        win_join.button("minmax2").hide();
        win_join.setText("");
        win_join.setModal(true);
        var pos = win_join.getPosition();
        var offset = (document.body.scrollTop ? document.body.scrollTop : window.pageYOffset);
        win_join.setPosition(pos[0], pos[1] + offset)

        join_form = win_join.attachForm(conf_form_join);

        if (Profilevalue != 8)
          document.getElementById('NewAgency').addEventListener("click", self.addAgency, uid);
        else
          join_form.setItemLabel('form_label_1', "<span style='color: #009D8C;font-weight: bold;'>AGENCY INFORMATION</span>&nbsp;<span style='color: grey;font-size:9px;'></span>");

        // Populating values to agency selection field      
        var agencies = join_form.getSelect("agency");
        agencies.options.add(new Option("SELECT AN AGENCY", ""));
        var adoption_agency = json.sys_pre_values.rows;
        for (var i in adoption_agency) {
          var agencyid = adoption_agency[i].id;
          var agencytype = adoption_agency[i].data[1];
          agencies.options.add(new Option(agencytype, agencyid));
        }

        // Popualting values to region selection field
        var regions = join_form.getSelect("region");
        regions.options.add(new Option("SELECT A REGION", ""));
        var region = json.sys_pre_values_region.rows;
        for (i in region) {
          var regionid = region[i].id;
          var regionvalue = region[i].data[1];
          regions.options.add(new Option(regionvalue, regionid));
        }

        // Populating values to membership selection field
        // var memberships = join_form.getSelect("mtype");
        // memberships.options.add(new Option("SELECT A MEMBERSHIP", ""));
        // var membership = json.sys_acl_levels.rows;
        // for (i in membership) {
        //   var membershipid = membership[i].id;
        //   var membershipname = membership[i].data[1];
        //   memberships.options.add(new Option(membershipname, membershipid));
        // }
        // Populating values state selection field
        var States = join_form.getSelect("state");
        States.options.add(new Option("SELECT A STATE", ""));
        var state = json.profiles.rows;
        for (i in state) {
          var stateid = state[i].id;
          States.options.add(new Option(stateid, stateid));
        }

        selectname = self.form.getSelect(name);
        Profilevalue = selectname.options[selectname.selectedIndex].value;
        /*  
         *  Hidding fields based on profile type selection.
         *  Profile type 4 represents Birth mother
         *  Profile type 2 represents Adoptive family
         *  Profile type 8 represents Adoptive agency
         *  */
        if (Profilevalue == 4) {
          //join_form.hideItem("mtype");
          join_form.hideItem("profilestatus", "single");
          join_form.hideItem("profilestatus", "couple");
          join_form.hideItem("aboutlabel");
          join_form.hideItem("lastname");
          join_form.hideItem("gender", "male");
          join_form.hideItem("gender", "female");
          join_form.hideItem("aboutlabel_sec");
          join_form.hideItem("firstname_sec");
          join_form.hideItem("lastname_sec");
          join_form.hideItem("gender_sec", "male");
          join_form.hideItem("gender_sec", "female");
          join_form.hideItem("agency_name");

        } else if (Profilevalue == 8) {
          // join_form.hideItem("mtype");
          join_form.hideItem("profilestatus", "single");
          join_form.hideItem("profilestatus", "couple");
          join_form.hideItem("aboutlabel");
          join_form.hideItem("firstname");
          join_form.hideItem("lastname");
          join_form.hideItem("gender", "male");
          join_form.hideItem("gender", "female");
          join_form.hideItem("profilel_mess");

          join_form.hideItem("aboutlabel_sec");
          join_form.hideItem("firstname_sec");
          join_form.hideItem("lastname_sec");
          join_form.hideItem("gender_sec", "male");
          join_form.hideItem("gender_sec", "female");
          join_form.hideItem("agency");

        } else {

          join_form.hideItem("aboutlabel_sec");
          join_form.hideItem("firstname_sec");
          join_form.hideItem("lastname_sec");
          join_form.hideItem("gender_sec", "male");
          join_form.hideItem("gender_sec", "female");
          join_form.hideItem("profilel_mess");
          join_form.hideItem("agency_name");
          // Displaying and hidding fields based on Marital status
          join_form.attachEvent("onChange", function (id, value) {

            if (value == 'couple') {
              win_join.setDimension(600, 800);
              join_form.showItem("aboutlabel_sec");
              join_form.showItem("firstname_sec");
              join_form.showItem("lastname_sec");
              join_form.showItem("gender_sec", "male");
              join_form.showItem("gender_sec", "female");
            } else if (value == 'single') {
              win_join.setDimension(600, 680);
              join_form.hideItem("aboutlabel_sec");
              join_form.hideItem("firstname_sec");
              join_form.hideItem("lastname_sec");
              join_form.hideItem("gender_sec", "male");
              join_form.hideItem("gender_sec", "female");
            } else {}
          });
        }
        join_form.setItemFocus('username');
        // Calling the function to save the data    
        join_form.attachEvent("onButtonClick", function (name, command) {
          self._join_save(uid, join_form, name, Profilevalue, json);
        });
        join_form.attachEvent("onInputChange", function (name_input, value_input) {
          if (name_input == 'cpassword' || name_input == 'password') {
            if (value_input.match(/\s/g)) {
              dhtmlx.message({
                type: "alert-error",
                text: "Password contains space",
                callback: function () {
                  self.focusfields_values(name_input);
                }
              });
            }
          }
        });
      }
    });
  },
  addAgency: function (uid) {
    //var self_1 = this;
    window.win_join.close();
    NewAgencyComponent.start({
      uid: (new Date()).getTime(),
      application_path: self.siteurl + "NewAgencyComponent/",
      siteurl: self.siteurl,
      dhtmlx_codebase_path: self.siteurl + "plugins/dhtmlx/"
    });

  },
  focusfields_values: function (name) {
    var self = this;
    join_form.setItemValue("password", '');
    join_form.setItemValue("cpassword", '');
    join_form.setItemFocus("password");
  },
  // Directly dispalying the join form       
  _form_join: function (uid, json, Pid, memid) {
    var self = this;
    var json = self.dataStore;
    var Profilevalue = self.Pid;
    var Membervalue = self.memid;
    var logged = json.logged.rows;
    join_window = new dhtmlXWindows();
    join_window.enableAutoViewport(false);
    join_window.attachViewportTo(vp);
    if (Profilevalue == 2) {
      var popwidth = 600;
      var popheight = 680;
    } else if (Profilevalue == 4) {
      popwidth = 600;
      popheight = 608;
    } else {
      popwidth = 600;
      popheight = 540;
    }
    // creation of join form window
    var conf_form_join = self.model.conf_form.template_join;
    // var win_join = join_window.createWindow("w1", 530, 550, popwidth, popheight);

    var win_join = join_window.createWindow("w1", "", "", popwidth, popheight);
    self.window["win_join"] = win_join;
    win_join.button("park").hide();
    win_join.button("minmax1").hide();
    win_join.button("minmax2").hide();
    win_join.centerOnScreen();
    var pos = win_join.getPosition();
    var offset = (document.body.scrollTop ? document.body.scrollTop : window.pageYOffset);
    win_join.setPosition(pos[0], pos[1] + offset)
    //self.window[ uid ].close();
    win_join.setText("");
    win_join.setModal(true);

    self.window["win_join"].attachEvent("onClose", function (win) {
      isEnabled = true;
      return true;
    });

    join_form = win_join.attachForm(conf_form_join);
    // Populating values to agency selection field      
    var agencies = join_form.getSelect("agency");
    agencies.options.add(new Option("SELECT AN AGENCY", ""));
    var adoption_agency = json.sys_pre_values.rows;
    for (var i in adoption_agency) {
      var agencyid = adoption_agency[i].id;
      var agencytype = adoption_agency[i].data[1];
      agencies.options.add(new Option(agencytype, agencyid));
    }
    // Popualting values to region selection field
    var regions = join_form.getSelect("region");
    regions.options.add(new Option("SELECT A REGION", ""));
    var region = json.sys_pre_values_region.rows;
    for (i in region) {
      var regionid = region[i].id;
      var regionvalue = region[i].data[1];
      regions.options.add(new Option(regionvalue, regionid));
    }
    // Populating values to membership selection field
    // var memberships = join_form.getSelect("mtype");
    // memberships.options.add(new Option("SELECT A MEMBERSHIP", ""));
    // var membership = json.sys_acl_levels.rows;
    // for (i in membership) {
    //   var membershipid = membership[i].id;
    //   var membershipname = membership[i].data[1];
    //   memberships.options.add(new Option(membershipname, membershipid));
    // }
    // jQuery("[name='mtype']").val(Membervalue).attr('selected', true);
    // Populating values state selection field
    var States = join_form.getSelect("state");
    States.options.add(new Option("SELECT A STATE", ""));
    var state = json.profiles.rows;
    for (i in state) {
      var stateid = state[i].id;
      States.options.add(new Option(stateid, stateid));
    }

    //selectname = self.form.getSelect(name);
    //Profilevalue = selectname.options[selectname.selectedIndex].value;
    /*  
     *  Hidding fields based on profile type selection.
     *  Profile type 4 represents Birth mother
     *  Profile type 2 represents Adoptive family
     *  Profile type 8 represents Adoptive agency
     *  */
    if (Profilevalue == 4) {
      // join_form.hideItem("mtype");
      join_form.hideItem("profilestatus", "single");
      join_form.hideItem("profilestatus", "couple");
      join_form.hideItem("aboutlabel");
      join_form.hideItem("lastname");
      join_form.hideItem("gender", "male");
      join_form.hideItem("gender", "female");
      join_form.setItemLabel("profilelabel_main", "SIGN UP");
      join_form.hideItem("aboutlabel_sec");
      join_form.hideItem("firstname_sec");
      join_form.hideItem("lastname_sec");
      join_form.hideItem("gender_sec", "male");
      join_form.hideItem("gender_sec", "female");
      join_form.hideItem("agency_name");
    } else if (Profilevalue == 8) {
      //join_form.hideItem("mtype");
      join_form.hideItem("profilestatus", "single");
      join_form.hideItem("profilestatus", "couple");
      join_form.hideItem("aboutlabel");
      join_form.hideItem("firstname");
      join_form.hideItem("lastname");
      join_form.hideItem("gender", "male");
      join_form.hideItem("gender", "female");
      join_form.hideItem("aboutlabel_sec");
      join_form.hideItem("firstname_sec");
      join_form.hideItem("lastname_sec");
      join_form.hideItem("gender_sec", "male");
      join_form.hideItem("gender_sec", "female");
      join_form.hideItem("agency");

    } else {

      join_form.hideItem("aboutlabel_sec");
      join_form.hideItem("firstname_sec");
      join_form.hideItem("lastname_sec");
      join_form.hideItem("gender_sec", "male");
      join_form.hideItem("gender_sec", "female");
      join_form.hideItem("profilel_mess");
      join_form.hideItem("agency_name");
      // Displaying and hidding fields based on Marital status
      join_form.attachEvent("onChange", function (id, value) {

        if (value == 'couple') {
          win_join.setDimension(600, 800);
          join_form.showItem("aboutlabel_sec");
          join_form.showItem("firstname_sec");
          join_form.showItem("lastname_sec");
          join_form.showItem("gender_sec", "male");
          join_form.showItem("gender_sec", "female");

        } else if (value == 'single') {
          win_join.setDimension(600, 680);
          join_form.hideItem("aboutlabel_sec");
          join_form.hideItem("firstname_sec");
          join_form.hideItem("lastname_sec");
          join_form.hideItem("gender_sec", "male");
          join_form.hideItem("gender_sec", "female");
        } else {}
      });
    }
    // Calling the function to save the data    
    join_form.attachEvent("onButtonClick", function (name, command) {
      self._join_save(uid, join_form, name, Profilevalue, json);

    });
  },
  // Validating and saving data to database.  
  validateJoinData: function (psingle, pcouple, uname, pass, cpass, email, fname, lname, gmale, gfemale, cfname, clname, cgmale, cgfemale, uagency, ustate, uregion, agreement, toc, agency_name, Profilevalue, name) {
    var self = this;
    if (name == "join") {

      // Validating the form values   
      // if (memtype == '' && Profilevalue != 4 && Profilevalue != 8) {
      //   return self.Validate.setErrorMsg("Please select a membership");
      // }
      if (self.Validate.isEmpty(uname)) {
        self.Validate.setErrorMsg("You must enter username");
        return false;
      }
      if (self.Validate.isEmpty(pass)) {
        self.Validate.setErrorMsg("You must enter password");
        return false;
      }
      //password lenght validate
      if (pass.length < 5) {
        return self.Validate.setErrorMsg("Password length should be atleast six characters.");
      }
      if (self.Validate.isEmpty(cpass)) {
        self.Validate.setErrorMsg("You must enter confirm password");
        return false;
      }
      if (!self.Validate.isEqual(pass, cpass)) {
        self.Validate.setErrorMsg("Passwords Mismatch");
        return false;
      }
      if (self.Validate.isEmpty(email)) {
        self.Validate.setErrorMsg("You must enter email");
        return false;
      }
      if (!self.Validate.isEmail(email)) {
        self.Validate.setErrorMsg("You must enter valid email");
        return false;
      }


      if (fname == '' && Profilevalue != 8) {
        self.Validate.setErrorMsg("Enter your first name.");
        return false;
      }
      if (lname == '' && Profilevalue != 8 && Profilevalue != 4) {
        self.Validate.setErrorMsg("Enter your last name.");
        return false;
      }
      if (cfname == '' && Profilevalue != 8 && Profilevalue != 4 && pcouple == 'couple') {
        self.Validate.setErrorMsg("Enter your couple first name.");
        return false;
      }
      if (clname == '' && Profilevalue != 8 && Profilevalue != 4 && pcouple == 'couple') {
        self.Validate.setErrorMsg("Enter your couple last name.");
        return false;
      }
      if (uagency == '' && Profilevalue != 8) {
        self.Validate.setErrorMsg("Please select an agency.");
        return false;
      }
      if (agency_name == '' && Profilevalue == 8 && Profilevalue != 4 && Profilevalue != 2) {
        self.Validate.setErrorMsg("Please enter an agency name.");
        return false;
      }
      if (agency_name && Profilevalue == 8 && Profilevalue != 4 && Profilevalue != 2) {
        if (!self.Validate.isAgencyName(agency_name)) {
          self.Validate.setErrorMsg("Name should be 30 characters maximum only.");
          return false;
        }
      }

      if (ustate == '') {
        self.Validate.setErrorMsg("Please select a state.");
        return false;
      }
      if (uregion == '') {
        self.Validate.setErrorMsg("Please select a region.");
        return false;
      }
      if (!toc) {
        self.Validate.setErrorMsg("You must agree with terms of use.");
        return false;
      }
      if (!self.Validate.isName(uname)) {
        self.Validate.setErrorMsg("Username must contain atleast one Character and only latin symbols, numbers or underscore( _ ) or minus ( - ) signs.");
        return false;
      }

      if (!self.Validate.isClientName(fname)) {
        self.Validate.setErrorMsg("First Name field cannot be blank and must be alphabets only.");
        return false;
      }
      if (!self.Validate.isClientName(lname)) {
        self.Validate.setErrorMsg("Last Name field cannot be blank and must be alphabets only.");
        return false;
      }



      return true;
    }
  },
  _join_save: function (uid, join_form, name, Profilevalue, json) {
    var self = this;

    // Getting values entered in form      
    var psingle = join_form.getItemValue("profilestatus", "single");
    var pcouple = join_form.getItemValue("profilestatus", "couple");
    //var memtype = join_form.getItemValue("mtype");
    var uname = join_form.getItemValue("username");
    var pass = join_form.getItemValue("password").trim();
    var cpass = join_form.getItemValue("cpassword").trim();
    var email = join_form.getItemValue("email");
    var fname = join_form.getItemValue("firstname");
    var lname = join_form.getItemValue("lastname");
    var gmale = join_form.getItemValue("gender", "male");
    var gfemale = join_form.getItemValue("gender", "female");
    var cfname = join_form.getItemValue("firstname_sec");
    var clname = join_form.getItemValue("lastname_sec");
    var cgmale = join_form.getItemValue("gender_sec", "male");
    var cgfemale = join_form.getItemValue("gender_sec", "female");
    var uagency = join_form.getItemValue("agency");
    var ustate = join_form.getItemValue("state");
    var uregion = join_form.getItemValue("region");
    var agreement = join_form.getItemValue("termsofuse");
    var toc = join_form.isItemChecked('termsofuse');
    var agency_name = join_form.getItemValue("agency_name")
    var poststr = "ptypes=" + psingle + "&ptypec=" + pcouple + "&usern=" + uname + "&passw=" + pass + "&cpassw=" + cpass + "&emailid=" + email + "&firstname=" + fname + "&lastname=" + lname + "&gendermale=" + gmale + "&genderfemale=" + gfemale + "&couplfname=" + cfname + "&couplelname=" + clname + "&cgenedermale=" + cgmale + "&cgenederfemale=" + cgfemale + "&useragency=" + uagency + "&userstate=" + ustate + "&userregion=" + uregion + "&uagree=" + agreement + "&Profiletype=" + Profilevalue + "&newagency=" + encodeURIComponent(agency_name);

    if (self.validateJoinData(psingle, pcouple, uname, pass, cpass, email, fname, lname, gmale, gfemale, cfname, clname, cgmale, cgfemale, uagency, ustate, uregion, agreement, toc, agency_name, Profilevalue, name)) {
      self.saveToDB(poststr, function () {
        self.selectMember(Profilevalue);
      });
    }
  },
  selectMember: function (Profilevalue) {
    var self = this;
    if (Profilevalue == 8) {

      win_join.close();
      self._window("adminNotify");
      self.window["adminNotify"].attachForm([{
        "type": "label",
        "className": "adminNotifyMsg pinkfontApproval",
        "label": "PLEASE WAIT FOR ADMINISTRATIVE APPROVAL"
      }]);

      //dhtmlx.alert('Your signup request has been sent for approval to ParentFinder. You will be notified through email once your account is approved. If you have any further questions please contact ParentFinder at info@parentfinder.com.');
      // window.location.href = self.siteurl + "extra_profile_view_30.php";
      return 0;
    } else if (Profilevalue == 4) {
      window.location.href = self.siteurl + "extra_profile_view_20.php";
    } else {
    
     
        win_join.close();
      self._window("adminNotify");
      self.window["adminNotify"].attachForm([{
        "type": "label",
        "className": "MembershiNotifyMsg pinkfontApproval",
        "label": "Please select the subscription to complete the joining process."
      }]);
        window.location.href = self.siteurl + "extra_member.php";
      //dhtmlx.alert('Your signup request has been sent for approval to ParentFinder. You will be notified through email once your account is approved. If you have any further questions please contact ParentFinder at info@parentfinder.com.');
      // window.location.href = self.siteurl + "extra_profile_view_30.php";
     // return 0;
     
     
    }
  },
  saveToDB: function (poststr, callback) {
    var self = this;
    // Inserting values to database
    dhtmlxAjax.post(self.configuration[self.uid].application_path + "processors/insert_data.php", poststr, function (loader) {
      var returnval = JSON.parse(loader.xmlDoc.responseText);
      //    alert(returnval.user_redirection);
      // Checking username and email id uniqueness 
      if (returnval.username_error) {
        dhtmlx.message({
          type: "alert-error",
          text: returnval.username_error
        })
        return false
      } else if (returnval.email_error) {
        dhtmlx.message({
          type: "alert-error",
          text: returnval.email_error
        })
        return false
      } else if (returnval.agency_error) {
        dhtmlx.message({
          type: "alert-error",
          text: returnval.agency_error
        })
        return false
      } else {
        callback();
      }
    });
  },
  init: function (model) {
    var self = this;
    self.model = model;
  },
  start: function (configuration) {
    var self = this;
    self.uid = configuration.uid;
    self.siteurl = configuration.siteurl;
    self.Pid = configuration.profileId;
    self.memid = configuration.memberid;
    self.dhtmlx_codebase_path = configuration.dhtmlx_codebase_path;
    //validating Control call
    self.Validate.isMissing(configuration.uid, "uid is missing");
    self.Validate.isMissing(configuration.application_path, "application path is missing");
    self.Validate.isMissing(configuration.dhtmlx_codebase_path, "dthmlx code path missing");
    window.dhx_globalImgPath = configuration.dhtmlx_codebase_path + "imgs/";
    dhtmlx.skin = self.model.globalSkin || "dhx_skyblue";
    configuration["icons_path"] = "icons/";
    self.configuration[self.uid] = configuration;
    self.model.conf_window.image_path = configuration.application_path + configuration.icons_path;
    if (self.Pid) {
      self._loadData(self.uid, function () {
        self._form_join(self.uid, self.ids);
      });
    } else {
      self._window(self.uid);
      self._layout(self.uid);
      self._loadData(self.uid, function () {
        self._form(self.uid);
        self.layout[self.uid].progressOff();
        self.window[self.uid].progressOff();
      });
    }
  },
  Validate: {
    isMissing: function (a, msg) {
      msg = msg || "Something  is missing";
      if ((typeof a === 'undefined') || (a.length === 0)) {
        return this.setErrorMsg(msg);
      }
    },
    isEmpty: function (ctrl, msg) {
      if (ctrl == '') {
        return true;
        // msg = msg || "No Field should be blank.";
        // return this.setErrorMsg(msg);
      }
      return false;
    },
    isEmail: function (ctrl, msg) {
      msg = msg || "You must enter valid email";
      var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      if (!filter.test(ctrl)) {
        return false;
      }
      return true;
    },
    isEqual: function (a, b, msg) {
      msg = msg || "Values mismatch";
      if (a != b) {
        return false;
      }
      return true;
    },
    isName: function (ctrl, msg) {
      msg = msg || "Username must contain only latin symbols, numbers or underscore(_)orminus(-)signs";
      if (ctrl != '') {
        //var filter = /^[a-zA-Z0-9-_]+$/;
        var filter = /^\d*[a-zA-Z][a-zA-Z0-9-_]*$/;
        if (!filter.test(ctrl)) {
          return false;
        }
      }
      return true;
    },
    isClientName: function (ctrl, msg) {
      msg = msg || "Name must contain only latin symbols, numbers or underscore(_)or minus(-)signs";
      if (ctrl != '') {
        var filter_name = /^[a-zA-Z]*[a-zA-Z]+[a-zA-Z ]*$/;
        if (!filter_name.test(ctrl)) {
          return false;
        }
      }
      return true;
    },
    isAgencyName: function (ctrl, msg) {
      //var name_filter = /^[\s\S]{4,30}$/;     
      if (ctrl.length < 4  || ctrl.length > 30  ) {
        return false;
      }
      return true;
    },
    setErrorMsg: function (msg) {
      dhtmlx.message({
        type: "alert-error",
        text: msg
      });
      return false;
    }
  }
}
MemberComponent.init(MemberComponent_Model);