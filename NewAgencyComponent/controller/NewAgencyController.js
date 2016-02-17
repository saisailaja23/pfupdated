/******************************************************************
 * Name:    Satya
 * Date:    07/13/2014
 * Purpose: For receiving new agency information
 *******************************************************************/
var NewAgencyComponent = {
    uid: null,
    window_manager: null,
    window: [],
    layout: [],
    form: [],
    status_bar: [],
    configuration: [],
    State : [],
    _window_manager: function() {
        var self = this;
        self.window_manager = new dhtmlXWindows();
        self.window_manager.setImagePath(self.model.conf_window.image_path);
        self.window_manager.enableAutoViewport(false);
        self.window_manager.attachViewportTo(vp);
    },
    _window: function(uid) {
        var self = this;
        if (self.window_manager === null)
            self._window_manager();
        if (self.window_manager.isWindow("window_NewAgency_" + uid)) {
            self.window[uid].show();
            self.window[uid].bringToTop();
            return;
        }
        self.window[uid] = self.window_manager.createWindow(
            "window_NewAgency_" + uid,
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
        self.window[uid].attachEvent("onClose", function(win) {
            //self.window[uid].close();
            return true;
        });
    },
    _layout: function(uid) {
        var self = this;
        self.layout[uid] = self.window[uid].attachLayout(self.model.conf_layout.pattern);
        self.layout[uid].cells("a").hideHeader();
    },
    _form: function(uid) {
        var self = this;
        var conf_form = self.model.conf_form.template;
        self.form = self.layout[uid].cells("a").attachForm(conf_form);
        
        // Populating values state selection field
        var States_element = self.form.getSelect("agencyState");
        States_element.options.add(new Option("SELECT A STATE", ""));

        var stateValue = self.State.rows;

        for (i in stateValue) {
        var stateid = stateValue[i].id;
        States_element.options.add(new Option(stateid, stateid));
        }
    
       
                
        self.form.attachEvent("onButtonClick", function (name, command) {
            self.submitRequest(uid);

        });
    },
    _loadData : function(uid,callBack){
        var self = this;
        var postStr = "1-1";
        dhtmlxAjax.post(self.application_path  + "processors/get_data.php", postStr, function (loader) {
            try {
                var json = JSON.parse(loader.xmlDoc.responseText);

                if (json.status == "success") {

                    self.State = json.profiles;
                    if (callBack)
                        callBack();

                }
            } catch (e) {
                dhtmlx.message({
                type: "error",
                text: "Fatal error on server side: " + loader.xmlDoc.responseText
                });
                //console.log(e.stack);
            }
            });
    },
    submitRequest: function(uid) {
        var self = this;
        var agency_name  = self.form.getItemValue("agencyName");
        var agency_email = self.form.getItemValue("agencyEmail");
        var agency_state = self.form.getItemValue("agencyState");
        var from_name    = self.form.getItemValue("fromName");
        var from_email   = self.form.getItemValue("yourEmail");
        //var name_filter = /^[a-zA-Z ]{0,20}$/;
        if (self.form.validateItem("fromName") == false) {        
            dhtmlx.message({
            type: "alert-error",
            text: "Name should be 20 characters maximum and alphabets only"
            });
            return false;
        }
        if (self.form.validateItem("yourEmail") == false) {
            dhtmlx.message({
            type: "alert-error",
            text: "Please enter valid email address"
            });
            return false;
        }
        
        if (self.form.validateItem("agencyName") == false) {        
            dhtmlx.message({
            type: "alert-error",
            text: "Name should be 20 characters maximum and alphabets only"
            });
            return false;
        }
        if (self.form.validateItem("agencyEmail") == false) {
            dhtmlx.message({
            type: "alert-error",
            text: "Please enter valid email address"
            });
            return false;
        }
        if (self.form.validateItem("agencyState") == false) {
            dhtmlx.message({
            type: "alert-error",
            text: "You must enter State"
            });
            return false;
        }
        var poststr = "agency_name=" + agency_name + "&agency_email=" + agency_email + "&agency_state=" + agency_state+ "&from_name=" + from_name+ "&from_email=" + from_email;
        dhtmlxAjax.post(self.application_path + "processors/requesttoAgency.php", poststr, function (loader) {
            var returnval = JSON.parse(loader.xmlDoc.responseText);
            //    alert(returnval.user_redirection);
            if (returnval.status =='success') {
                dhtmlx.alert("Your request has been sent to the Agency", function (result) {
                if (result == true) {
                    self.window[uid].close();
                }
                });
            } else {
                dhtmlx.message({
                type: "alert-error",
                text: "Error While Sending Notification to Agency"
                })
                return false
            } 
        });
    },

    init: function(model) {
        var self = this;
        self.model = model;
    },

    start: function(configuration) {
        var self = this;
        self.uid = configuration.uid;
        self.siteurl = configuration.siteurl;
        self.application_path = configuration.application_path;
        window.dhx_globalImgPath = configuration.dhtmlx_codebase_path + "imgs/";
        dhtmlx.skin = self.model.globalSkin || "dhx_skyblue";

        configuration["icons_path"] = "icons/";
        self.configuration[self.uid] = configuration;

        self.model.conf_window.image_path = configuration.application_path + configuration.icons_path;

        self._window(self.uid);
        self._layout(self.uid);
        self._loadData(self.uid, function () {
            self._form(self.uid);
        });
    }

}
NewAgencyComponent.init(NewAgency_Model);
