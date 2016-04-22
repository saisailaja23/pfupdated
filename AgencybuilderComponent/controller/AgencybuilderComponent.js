/***********************************************************************
 * Name:    Prashanth A
 * Date:    11/11/2013
 * Purpose: Creating a family profile builder page
 ***********************************************************************/
function dhtmlxWidowCustomPostion(widowObj, centerOnScreen, yPosition, scrolltop) {
	if (centerOnScreen == true)
		widowObj.centerOnScreen();
	else
		widowObj.center();
	var position = widowObj.getPosition();
	var splitPosition = String(position).split(",");
	var xPosition = splitPosition[0];
	if (xPosition < 0)
		xPosition = 50;
	widowObj.setPosition(xPosition, yPosition);
	if (scrolltop == true)
		document.body.scrollTop = 0;
}

var AgencybuilderComponent = {
    uid: null,
    form: [],
    configuration: [],
    dataStore: [],
    _loadData: function(uid, callBack) {
        var self = this;
        var postStr = "1-1";
        dhtmlxAjax.post(self.configuration[uid].application_path + "processors/agency_information.php", postStr, function(loader) {
            try {
                var json = JSON.parse(loader.xmlDoc.responseText);
                if (json.status == "success") {
                    self.dataStore["agency_details"] = json.agency_details;
                    self.dataStore["Profiles_State"] = json.Profiles_State;
                    self.dataStore["Profiles_region"] = json.Profiles_region;
                    if (callBack)
                        callBack();

/**/						
                    $('.youtubeUploadCh').on('click', function() {
							//addYoutube: function(id, albumName) {
								var self = this;
								var addWinObj = new dhtmlXWindows();
								addWinObj.setImagePath('../dhtmlxfull3.5/imgs/dhtmlxtoolbar_imgs/');
								var addWin = addWinObj.createWindow('addYTWin', '0', '0', 500, 250);
								addWin.setModal(true);
								addWin.button('park').hide()
								addWin.button('minmax1').hide()
								addWin.setText('');
								addWin.denyMove(true);
								dhtmlxWidowCustomPostion(addWin, true, '', true);
								addWin.attachEvent("onClose", function(win) {
									delete addWin;
									return true;
								});
								var addLinkLayout = addWin.attachLayout('1C');
								addLinkLayout.cells('a').hideHeader();

								var buttonText = 'Login to your Youtube account';
								var offsetLeft = '230';
								//if (token_flag == 1) {
                        if (1 == 1) {
									buttonText = 'Select your Youtube videos';
									offsetLeft = '252';
								}

								var addLinkForm = addLinkLayout.cells('a').attachForm([{
									type: "label",
									name: "form_label_1",
									label: "YouTube video link information"
								}, {
									type: "input",
									name: "videoname",
									label: 'Video Name',
									labelWidth: '80',
									offsetLeft: "35",
									offsetTop: "30",
									inputWidth: 330
								}, {
									type: "input",
									name: "youtubelink",
									label: 'Youtube URL',
									labelWidth: '80',
									offsetLeft: "35",
									offsetTop: "15",
									inputWidth: 330
								}, {
									type: "label",
									name: "example",
									label: '<span style="color:#76787b;font-weight:lighter;">Ex: https://www.youtube.com/watch?v=GXge4Vf1yJM</span>',
									offsetLeft: "112",
									offsetTop: "4",
									inputWidth: 330
								}, {
									type: 'button',
									name: 'saveLink',
									value: 'Save',
									offsetLeft: "113",
									offsetTop: "15",
                        }]);

								addLinkForm.attachEvent('onButtonClick', function(name) {
									if (name == 'saveLink') {
										var url = addLinkForm.getItemValue("youtubelink");
										if (url != '') {
											var params = 'url=' + url + '&filename=' + addLinkForm.getItemValue("videoname");
											dhtmlxAjax.post(site_url + "components/album/processors/saveDefaultYoutubeLink.php?method=set", params, function(loader) {
												var data = loader.xmlDoc.responseText;
												console.log(data);
												addWin.close();
												showDefatulVideo();
											});
										}
									}
								});
							//}


                    });
                    /**/
					$('.youtubeDelete').on('click', function(){
						dhtmlx.confirm({
						  type: "confirm",
						  text: "Are you sure you want to remove the default video?",
						  ok: "Yes",
						  callback: function(result) {
							if (result == true) {
								/**/
								var params = 'url=';
								dhtmlxAjax.post(site_url + "components/album/processors/saveDefaultYoutubeLink.php?method=delete", params, function(loader) {
									var data = loader.xmlDoc.responseText;
									console.log(data);
									showDefatulVideo();
									
								});									
								/**/
							}
						  }
						});
					
					});
					
/**/


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
        var conf_form = self.model.Agencybuilder.template_Agencybuilder;
        var Agency_form = new dhtmlXForm("profilebuilder", conf_form);
        var agency_join_form;
        var agency_window = new dhtmlXWindows();
        agency_window.enableAutoViewport(false);
        agency_window.attachViewportTo(vp);
        
        
        if (self.dataStore.agency_details.rows[0].data[10] != '') {
            Agency_form.setItemLabel("fburl", "facebook", "<img src='templates/tmpl_par/images/splash/ico_fb_act.png' />");
        }
        if (self.dataStore.agency_details.rows[0].data[11] != '') {
            Agency_form.setItemLabel("turl", "twitter", "<img src='templates/tmpl_par/images/splash/ico_tw_act.png' />");
        }
        if (self.dataStore.agency_details.rows[0].data[12] != '') {
            Agency_form.setItemLabel("gurl", "google", "<img src='templates/tmpl_par/images/splash/ico_go_act.png' />");
        }
        if (self.dataStore.agency_details.rows[0].data[13] != '') {
            Agency_form.setItemLabel("burl", "blogger", "<img src='templates/tmpl_par/images/splash/ico_bi_act.png' />");
        }
        if (self.dataStore.agency_details.rows[0].data[14] != '') {
            Agency_form.setItemLabel("purl", "pinerest", "<img src='templates/tmpl_par/images/splash/ico_pi_act.png' />");
        }
        if (self.dataStore.agency_details.rows[0].data[19] != '') {
            Agency_form.setItemValue("unpubPwd", self.dataStore.agency_details.rows[0].data[19]);
            Agency_form.setItemValue("confirmunpubPwd", self.dataStore.agency_details.rows[0].data[19]);
        }
        Agency_form.attachEvent("onFileAdd", function(realName) {
            var myUploader = Agency_form.getUploader("Filedata");
            myUploader.upload();
});
        Agency_form.attachEvent("onUploadFile", function(realName, serverName, t) {
      var myUploader = Agency_form.getUploader("Filedata");
      var status = myUploader.getStatus(); 
      if (status == 1)
        Agency_form.disableItem("Filedata");
    });
        Agency_form.attachEvent("onUploadFail", function(realName) {
        Agency_form.getUploader("Filedata").clear();
    dhtmlx.message({
                                type: "alert-error",
                                text: "Please Upload images."
                            })
});
        
        Agency_form.attachEvent("onChange", function(id, value) {

            if (id == 'fburl' || id == 'turl' || id == 'gurl' || id == 'burl' || id == 'purl') {
                // creation of join form window
                var agency_conf_form_join = self.model.agencybuilder_join.template_Agencybuilder_join;
                var agency_win_join = agency_window.createWindow("w1", 500, 500, 425, 110);
                agency_win_join.centerOnScreen();
                var pos = agency_win_join.getPosition();
				console.log(pos);
                var offset = (document.body.scrollTop ? document.body.scrollTop : window.pageYOffset);
                agency_win_join.setPosition(pos[0], pos[1] + (offset))
                debugger;
                
                agency_win_join.button("park").hide();
                agency_win_join.button("minmax1").hide();
                agency_win_join.button("minmax2").hide();
                agency_win_join.setText("");
                agency_win_join.setModal(true);
                
                agency_join_form = agency_win_join.attachForm(agency_conf_form_join);
            
                agency_join_form.attachEvent("onButtonClick", function(name, command) {
				console.log(name);
				console.log(command);
                var facebook_url = agency_join_form.getItemValue("facebookurl");
                var twitter_url = agency_join_form.getItemValue("twitterurl");
                var google_url = agency_join_form.getItemValue("googleurl");
                var blogger_url = agency_join_form.getItemValue("bloggerurl");
                var pinerest_url = agency_join_form.getItemValue("pineresturl");
				var value;
				           if (id == 'fburl') {
                                value = agency_join_form.getItemValue("facebookurl");
                            } else if (id == 'turl') {
                                value = agency_join_form.getItemValue("twitterurl");
                            } else if (id == 'gurl') {
                                value = agency_join_form.getItemValue("googleurl");
                            } else if (id == 'burl') {
                                value = agency_join_form.getItemValue("bloggerurl");
                            } else if (id == 'purl') {
                                value = agency_join_form.getItemValue("pineresturl");
                            }
//debugger;				
                    var myRegExp = /((?:https?\:\/\/|www\.)(?:[-a-z0-9]+\.)*[-a-z0-9]+.*)/i;
                    if (!myRegExp.test(value)) {
				dhtmlx.message({
					type: "alert-error",
					text: "Please give Valid url and try again...."
				})
				return false;
			}
                if (name == "socialsubmit") {


		   
                        var poststr = "facebookurl=" + facebook_url + "&twitterurl=" + twitter_url + "&googleurl=" + google_url + "&bloggerurl=" + blogger_url + "&pineresturl=" + pinerest_url + "&id=" + id;;

                    // Inserting values to database
                        dhtmlxAjax.post(self.configuration[uid].application_path + "processors/update_soical.php", poststr, function(loader) {
                        var json = JSON.parse(loader.xmlDoc.responseText);
                        if (json.status == "success") {
                            if (id == 'fburl') {
                                self.dataStore.agency_details.rows[0].data[10] = facebook_url;
                                    Agency_form.setItemLabel("fburl", "facebook", "<img src='" + json.img + "' />");
                            } else if (id == 'turl') {
                                self.dataStore.agency_details.rows[0].data[11] = twitter_url;
                                    Agency_form.setItemLabel("turl", "twitter", "<img src='" + json.img + "' />");
                            } else if (id == 'gurl') {
                                self.dataStore.agency_details.rows[0].data[12] = google_url;
                                    Agency_form.setItemLabel("gurl", "google", "<img src='" + json.img + "' />");
                            } else if (id == 'burl') {
                                self.dataStore.agency_details.rows[0].data[13] = blogger_url;
                                    Agency_form.setItemLabel("burl", "blogger", "<img src='" + json.img + "' />");
                            } else if (id == 'purl') {
                                self.dataStore.agency_details.rows[0].data[14] = pinerest_url;
                                    Agency_form.setItemLabel("purl", "pinerest", "<img src='" + json.img + "' />");
                            }
                            agency_win_join.close();
                            } else {
                            console.log(json);
                            dhtmlx.message({
                                type: "alert-error",
                                text: "Please give Valid url and try again...."
                            })
                        }
                       

                    });

                }



            });
}


            if (id == 'fburl') {
                agency_win_join.attachEvent("onClose", function (win) {

                    Agency_form.uncheckItem("fburl", "facebook");

                    Agency_form.enableItem("turl", "twitter");
                    Agency_form.enableItem("gurl", "google");
                    Agency_form.enableItem("burl", "blogger");
                    Agency_form.enableItem("purl", "pinerest");
                    Agency_form.enableItem("fburl", "facebook");
                    return true;
                }); //alert(self.dataStore.agency_details.rows[0].data[10]+'df');
                 agency_join_form.setItemValue("facebookurl", self.dataStore.agency_details.rows[0].data[10]);
                Agency_form.disableItem("turl", "twitter");
                Agency_form.disableItem("gurl", "google");
                Agency_form.disableItem("burl", "blogger");
                Agency_form.disableItem("purl", "pinerest");
                agency_join_form.hideItem("twitterurl");
                agency_join_form.hideItem("googleurl");
                agency_join_form.hideItem("bloggerurl");
                agency_join_form.hideItem("pineresturl");
            }
            if (id == 'turl') {
                agency_win_join.attachEvent("onClose", function (win) {

                    Agency_form.uncheckItem("turl", "twitter");

                    Agency_form.uncheckItem("fburl", "facebook");

                    Agency_form.enableItem("turl", "twitter");
                    Agency_form.enableItem("gurl", "google");
                    Agency_form.enableItem("burl", "blogger");
                    Agency_form.enableItem("purl", "pinerest");
                    Agency_form.enableItem("fburl", "facebook");
                    return true;
                });
                agency_join_form.setItemValue("twitterurl", self.dataStore.agency_details.rows[0].data[11]);
                Agency_form.disableItem("fburl", "facebook");
                Agency_form.disableItem("gurl", "google");
                Agency_form.disableItem("burl", "blogger");
                Agency_form.disableItem("purl", "pinerest");
                agency_join_form.hideItem("facebookurl");
                agency_join_form.hideItem("googleurl");
                agency_join_form.hideItem("bloggerurl");
                agency_join_form.hideItem("pineresturl");
            }
            if (id == 'gurl') {
                agency_win_join.attachEvent("onClose", function (win) {

                    Agency_form.uncheckItem("gurl", "google");

                    Agency_form.uncheckItem("fburl", "facebook");
                    Agency_form.enableItem("turl", "twitter");
                    Agency_form.enableItem("gurl", "google");
                    Agency_form.enableItem("burl", "blogger");
                    Agency_form.enableItem("purl", "pinerest");
                    Agency_form.enableItem("fburl", "facebook");
                    return true;
                });
                agency_join_form.setItemValue("googleurl", self.dataStore.agency_details.rows[0].data[12]);
                Agency_form.disableItem("fburl", "facebook");
                Agency_form.disableItem("turl", "twitter");
                Agency_form.disableItem("burl", "blogger");
                Agency_form.disableItem("purl", "pinerest");
                agency_join_form.hideItem("twitterurl");
                agency_join_form.hideItem("facebookurl");
                agency_join_form.hideItem("bloggerurl");
                agency_join_form.hideItem("pineresturl");
            }
            if (id == 'burl') {
                agency_win_join.attachEvent("onClose", function (win) {

                    Agency_form.uncheckItem("burl", "blogger");

                    Agency_form.enableItem("turl", "twitter");
                    Agency_form.enableItem("gurl", "google");
                    Agency_form.enableItem("burl", "blogger");
                    Agency_form.enableItem("purl", "pinerest");
                    Agency_form.enableItem("fburl", "facebook");
                    return true;
                });
                agency_join_form.setItemValue("bloggerurl", self.dataStore.agency_details.rows[0].data[13]);
                Agency_form.disableItem("fburl", "facebook");
                Agency_form.disableItem("turl", "twitter");
                Agency_form.disableItem("gurl", "google");
                Agency_form.disableItem("purl", "pinerest");

                agency_join_form.hideItem("twitterurl");
                agency_join_form.hideItem("googleurl");
                agency_join_form.hideItem("facebookurl");
                agency_join_form.hideItem("pineresturl");
            }
            if (id == 'purl') {
                agency_win_join.attachEvent("onClose", function (win) {

                    Agency_form.uncheckItem("purl", "pinerest");

                    Agency_form.enableItem("turl", "twitter");
                    Agency_form.enableItem("gurl", "google");
                    Agency_form.enableItem("burl", "blogger");
                    Agency_form.enableItem("purl", "pinerest");
                    Agency_form.enableItem("fburl", "facebook");
                    return true;
                });
                agency_join_form.setItemValue("pineresturl", self.dataStore.agency_details.rows[0].data[14]);
                Agency_form.disableItem("fburl", "facebook");
                Agency_form.disableItem("turl", "twitter");
                Agency_form.disableItem("gurl", "google");
                Agency_form.disableItem("burl", "blogger");

                agency_join_form.hideItem("twitterurl");
                agency_join_form.hideItem("googleurl");
                agency_join_form.hideItem("bloggerurl");
                agency_join_form.hideItem("facebookurl");
            }

        });


//AgencyTitle,City,State,zip,Country,CONTACT_NUMBER,Email,WEB_URL,Avatar,AgencyDesc,Facebook,Twitter,Google,Blogger,Pinerest,Pid

        // Adding extra upload button for uploading photo on demand
        var json = self.dataStore;
        var agency_details = json.agency_details.rows;
       
        var profile_states = json.Profiles_State.rows;
        var states = Agency_form.getSelect("state");
        for (var i in profile_states) {
        var stateid = profile_states[i].id;      
        states.options.add(new Option(stateid, stateid));
        }
    
        var regions = Agency_form.getSelect("region");      
        var region = json.Profiles_region.rows;
        for (i in region) {
          var regionid = region[i].id;
          var regionvalue = region[i].data[1];
          regions.options.add(new Option(regionvalue, regionid));
        }
    

   // jQuery("[name='state']").val(Profile_state).attr('selected', true);
        for (var i in agency_details) {
            var agency_ID = agency_details[i].data[15];            
            var agency_description = agency_details[i].data[9];
            var agency_address = agency_details[i].data[4];
            var agency_phonenumber;
            if(agency_details[i].data[5] == 0)
                agency_phonenumber = '';
            else
                agency_phonenumber = agency_details[i].data[5];
            var agency_email = agency_details[i].data[6];
            var agency_title = agency_details[i].data[0];
            var agency_uri = agency_details[i].data[7];
            var mail_address = agency_details[i].data[17];
            
            var state = agency_details[i].data[2];
            var city = agency_details[i].data[1];
            var zip = agency_details[i].data[3];
            var region = agency_details[i].data[18];
     
            Agency_form.setItemValue("agency_email", agency_email);
            //Agency_form.setItemValue("agency_desc", agency_description);
            Agency_form.setItemValue("agency_addres", agency_address);
            Agency_form.setItemValue("agency_phonenumber", agency_phonenumber);
            Agency_form.setItemValue("agency_name", agency_title);
            Agency_form.setItemValue("your_url", agency_uri);
           // Agency_form.setItemValue("agency_desc", agency_description);
            Agency_form.setItemValue("agency_addres", mail_address);
            
            Agency_form.setItemValue("city", city);
            jQuery("[name='state']").val(state).attr('selected', true);
            jQuery("[name='region']").val(region).attr('selected', true);
          //  Agency_form.setItemValue("state", state);
            Agency_form.setItemValue("zip", zip);
            
        }


        Agency_form.attachEvent("onButtonClick", function (name, command) {
            self._agency_profile_save(uid, Agency_form, name, json, agency_ID);

        });
        
        
         Agency_form.attachEvent("onInputChange", function(name_input, value_input) {
      if (name_input == 'agency_phonenumber') {
        var v = value_input;
        v = v.replace(/\D/g, "");
        v = v.substring(0, 10);
        v = v.replace(/^(\d{3})(\d)/g, "$1-$2");
        v = v.replace(/^(\d{3})\-(\d{3})(\d)/g, "$1-$2-$3");
        v = v.replace(/(\d)\-(\d{3})$/, "$1-$2");
        Agency_form.setItemValue(name_input, v);
      }
    });
    },
setAgencyDescription : function(){
        var self = AgencybuilderComponent;
        //alert('ss');       
        var agency_description = self.dataStore.agency_details.rows[0].data[9];
        tinyMCE.get('agency_desc').setContent(agency_description);
    },
    validateURL: function(val) {
console.log(val);
}
    // Validating and saving data to database.  
    ,
    _agency_profile_save: function(uid, Agency_form, name, json, agency_ID) {
        var self = this;
        
        if (Agency_form.validateItem("agency_email") == false) {
            dhtmlx.message({
          type: "alert-error",
          text: "Please enter valid email address...."
            });
            return false;
        }
        /*if(Agency_form.validateItem("city")== false){
            return false;
        }
        if(Agency_form.validateItem("zip")== false || Agency_form.getItemValue("zip").length < 5 || Agency_form.getItemValue("zip").length > 5){        
     
            return false;      
        }*/
        var unpubpwd = Agency_form.getItemValue("unpubPwd");
        var unpubpwd1 = Agency_form.getItemValue("confirmunpubPwd");
        if (unpubpwd.length < 6) {
            msg = "Unpublish password should contain atleast 6 characters";
        } else if (unpubpwd.length > 50) {
            msg = "Unpublish password is too long";
        } else if (unpubpwd.search(/\d/) == -1) {
            msg = "Unpublish password should contain atleast one number";
        } else if (unpubpwd.search(/[a-zA-Z]/) == -1) {
            msg = "Unpublish password should contain atleast one letter";
        } else if (unpubpwd.search(/[^a-zA-Z0-9\!\@\#\$\%\^\&\*\(\)\_\+]/) != -1) {
            msg = "Unpublish password accepts only !@#$%^&*()_+ specail characters";
        }
        else if (unpubpwd != unpubpwd1){
            msg = "Unpublish passwords didn't match";
        }
        else {
            msg = "true";
        }
        if(msg != "true"){
            dhtmlx.message({
                type: "alert-error",
                text: msg
            })
            return false;
        }
        if (Agency_form.validateItem("agency_name") == false) { 
            dhtmlx.message({
                type: "alert-error",
                text: "Name should be maximum 100 characters only"
            })
            return false;
        }
        if (Agency_form.validateItem("city") == false) { 
            dhtmlx.message({
                type: "alert-error",
                text: "You must enter valid city"
            })
            return false;
        }
        if (Agency_form.validateItem("agency_phonenumber") == false) { 
            dhtmlx.message({
                type: "alert-error",
                text: "You must enter valid phone number <br/> (000-000-0000)"
            })
            return false;
        }
        if (Agency_form.validateItem("zip") == false) { 
            dhtmlx.message({
                type: "alert-error",
                text: "You must enter valid zip"
            })
            return false;
        }
		var agency_url = Agency_form.getItemValue("your_url");
		if(agency_url.trim()){
		
		
		var myRegExp1 =/((?:https?\:\/\/|www\.)(?:[-a-z0-9]+\.)*[-a-z0-9]+.*)/i;
			if(!myRegExp1.test(agency_url)){
				dhtmlx.message({
					type: "alert-error",
					text: "Please give Valid url and try again...."
				});
				//Agency_form.setValidateCss("your_url", false, "dhxform_item_label_left  validate_error");
			return false;
			}
		}

        var agency_email = Agency_form.getItemValue("agency_email");
        var agency_description = encodeURIComponent(Agency_form.getItemValue("agency_desc"));
        var agency_address = Agency_form.getItemValue("agency_addres");
        var agency_phonenumber = Agency_form.getItemValue("agency_phonenumber");
        var agency_name = Agency_form.getItemValue("agency_name");
        var city = Agency_form.getItemValue("city");
        var state = Agency_form.getItemValue("state");
        var region = Agency_form.getItemValue("region");
        var zip = Agency_form.getItemValue("zip");
        
        if (name == "submitagencydetails") {

            var poststr = "agency_ID=" + agency_ID + "&agency_email=" + agency_email + "&agency_description=" + agency_description + "&agency_address=" + agency_address + 
                "&agency_phonenumber=" + agency_phonenumber + "&agency_name=" + encodeURIComponent(agency_name) + "&agency_url=" + agency_url + "&city=" + city + "&zip=" + zip + "&state=" + state + "&region=" + region + "&Unpublish_Password=" + unpubpwd;
            
            // Inserting values to database
            dhtmlxAjax.post(self.configuration[uid].application_path + "processors/insert_agency_info.php", poststr, function(loader) {

                var returnval = JSON.parse(loader.xmlDoc.responseText);
                window.location.href = returnval.user_redirection;


            });

        }

    },

    init: function (model) {
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
       dhtmlx.image_path  = configuration.dhtmlx_codebase_path + "imgs/";
        

        configuration["icons_path"] = "icons/";
        self.configuration[self.uid] = configuration;

        self._loadData(self.uid, function () {
            self._form(self.uid);
        });



        //self._form(self.uid);
    }

}
AgencybuilderComponent.init(AgencybuilderComponent_Model);