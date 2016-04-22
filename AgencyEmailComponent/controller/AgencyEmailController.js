/***********************************************************************
 * Name:    Sailaja S
 * Date:    2015/07/27
 * Purpose: Email configuration for Agency
 ***********************************************************************/
var AgencyEmailController = {
	uid: null,
    form: [],
    configuration: [],
    status: 0,    
    dataStore: [],
    window_manager: null,
    mail_form: null,

    init: function(model) {
        var self = this;
        self.model = model;
    },
    getUrlVars: function() {
        var vars = {};
        var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) {
            vars[key] = value;
        });

        return vars;
    },
    _loadData: function(uid, callBack) {
        var self = this;
        var postStr = "1-1";
        dhtmlxAjax.post(site_url + "AgencyEmailComponent/processors/getEmailSettings.php", postStr, function(loader) {
            try {
                var json = JSON.parse(loader.xmlDoc.responseText);
                // console.log(json);
                // debugger;
                if (json.status == "success") {
                    self.dataStore["BlogAdd"] = json.BlogAdd;
                    self.dataStore["BlogEdit"] = json.BlogEdit;
                    self.dataStore["BlogDelete"] = json.BlogDelete;
                    self.dataStore["PhotoUpload"] = json.PhotoUpload;
                    self.dataStore["VideoUpload"] = json.VideoUpload;
                    self.dataStore["EditProfile"] = json.EditProfile;                    
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
    start: function (configuration) {
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
        dhtmlx.skin = "dhx_skyblue";

        configuration["icons_path"] = "icons/";
        self.configuration[self.uid] = configuration;

        var conf_form = self.model.conf_form.MailConfig;
        var mail_form = new dhtmlXForm("mailExpand", conf_form);
        self.mail_form = mail_form;		             
       
        self._loadData(self.uid,function(){
            // debugger;
            if(self.dataStore["BlogAdd"] == "1")
                self.mail_form.checkItem("BlogAdd");
            if(self.dataStore["BlogEdit"] == "1")
                self.mail_form.checkItem("BlogEdit");
            if(self.dataStore["BlogDelete"] == "1")
                self.mail_form.checkItem("BlogDelete");
            if(self.dataStore["PhotoUpload"] == "1")
                self.mail_form.checkItem("Photo");
            if(self.dataStore["VideoUpload"] == "1")
                self.mail_form.checkItem("Video");
            if(self.dataStore["EditProfile"] == "1")
                self.mail_form.checkItem("Editprofile");
        })

	   	$( "#mail" ).click(function () {
		    if ($("#mailExpand").is(":hidden")) {
            	$("#mailExpand").slideDown("slow");
        	} else {
            	$("#mailExpand").slideUp("slow");
        	}
	    });
	    $('.mailclose').click(function () {   
	    	$( "#mailExpand" ).slideUp( "slow" );	    
	    }); 

	    self.mail_form.attachEvent('onButtonClick', function (name) {
	    	var settings = ['BlogAdd','BlogEdit','BlogDelete','Photo','Video','Editprofile'], postarr = [];
	    	if(name == "saveSettings"){    		
	    		for (i = 0; i < settings.length; i++) {	    			
	    			if(self.mail_form.isItemChecked(settings[i]))
	    				postarr[i] = 1;
	    			else
	    				postarr[i] = 0;	    			
	    		}	    		
	    		// alert(postr);
	    		var poststr = "settings=" + postarr;
	    		dhtmlxAjax.post(site_url + "AgencyEmailComponent/processors/saveEmailSettings.php",poststr, function(data) {
		            // debugger;
		            var json = JSON.parse(data.xmlDoc.responseText);
	                
	                if (json.status == "success") {
	                    dhtmlx.alert({		                    
	                    	text: "Your email settings are saved successfully"
	                	});
	                } else {
	                    dhtmlx.message({
	                        type: "error",
	                        text: json.msg
	                    });
	                }
		            
		        });
	    	}	    	
	    });  
	            
        // $('.mailLabel').click(function(){
        	
        // });	    
	}
}
AgencyEmailController.init(AgencyEmailModel);