/***********************************************************************
 * Name:    Sailaja S
 * Date:    2015/05/19
 * Purpose: Youtube Login for Agency
 ***********************************************************************/
var youtubeLoginComponent = {
	uid: null,
    form: [],
    configuration: [],
    status: 0,
    accessToken:null,
    dataStore: [],
    window_manager: null,
    youtube_form: null,

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
        self.uid = uid;        
        var poststr = "i=1";
        dhtmlxAjax.post(self.configuration[uid].application_path + "processors/getAccessToken.php",poststr,function(loader) {
            var json = JSON.parse(loader.xmlDoc.responseText);
            console.log(json);
            if (json.status == "success") {               
                self.accessToken = json.response;
            } 
                if (callBack)
                    callBack();

            try {
            } catch (e) {
                dhtmlx.message({
                    type: "error",
                    text: "Fatal error on server side: " + loader.xmlDoc.responseText
                });
//                 console.log(e.stack);
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

        var conf_form = self.model.conf_form.LogintoYoutube;
        var youtube_form = new dhtmlXForm("youtubeExpand", conf_form);
        self.youtube_form = youtube_form;		             
       
	   	$( "#youtube" ).click(function () {
		    if ($("#youtubeExpand").is(":hidden")) {
            	$("#youtubeExpand").slideDown("slow");
        	} else {
            	$("#youtubeExpand").slideUp("slow");
        	}
	    });
	    $('.youtubeclose').click(function () {   
	    	$( "#youtubeExpand" ).slideUp( "slow" );	    
	    }); 

	    self.youtube_form.attachEvent('onButtonClick', function (name) {
	    	if(name == "youtubelogin"){
//                    console.log("https://accounts.google.com/o/oauth2/auth?client_id=539428858218-eacddo0al0a564ommpcuk382uuketsj2.apps.googleusercontent.com&approval_prompt=auto&redirect_uri="+siteurl+"oauth2callback.php&scope=https://www.googleapis.com/auth/youtube&response_type=code&access_type=offline");
                    window.location.assign("https://accounts.google.com/o/oauth2/auth?client_id=539428858218-eacddo0al0a564ommpcuk382uuketsj2.apps.googleusercontent.com&approval_prompt=auto&redirect_uri=http://www.parentfinder.com/oauth2callback.php&scope=https://www.googleapis.com/auth/youtube&response_type=code&access_type=offline");
	    	}
	    });
            self._loadData(self.uid,function() {
                console.log(self.accessToken);
                console.log(self.accessToken != null);
                if(!self.accessToken && self.accessToken == null) {                   
                    $(".loggedinText").hide(); 
                    $('.youtubeloginbtn').show();
                }
                else {
                    $('.youtubeloginbtn').hide();
                    $(".loggedinText").show();
                }
            });
            if(!self.accessToken && self.accessToken == null){
                $(".loggedinText").hide(); 
                $('.youtubeloginbtn').show();
            }
            

	}
}
youtubeLoginComponent.init(youtubeLoginComponent_Model);