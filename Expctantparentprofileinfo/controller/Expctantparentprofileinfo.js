/***********************************************************************
 * Name:    Prashanth A
 * Date:    11/11/2013
 * Purpose: Getting the birth mother details
 ***********************************************************************/
var Expctantparentprofileinfo = {
    uid: null,
    form: [],
    configuration: [],
    dataStore: [],
    _loadData: function (uid, callBack) {
        var self = this;
        var postStr = "1-1";
        dhtmlxAjax.post(self.configuration[uid].application_path + "processors/profile_view_information.php", postStr, function (loader) {
            try {
                var json = JSON.parse(loader.xmlDoc.responseText);

                if (json.status == "success") {


                    self.dataStore["Profiles"] = json.Profiles;
                    self.dataStore["Profile_active"] = json.Profile_active;
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
            } catch (e) {
                dhtmlx.message({
                    type: "error",
                    text: "Fatal error on server side: " + loader.xmlDoc.responseText
                });
                console.log(e.stack);
            }
        });


    },

    _form: function (uid) {

        var self = this;

    
        var json = self.dataStore;
        
        
        var Profiles = json.Profiles.rows;
        var Profile_active = json.Profile_active.rows;

        for (i in Profiles) {
            var BMprofile_fname = Profiles[i].data[1];
            var BMprofile_lname = Profiles[i].data[2];
           // var agency_id = Profiles[i].data[3];

            BMid = Profiles[i].data[0];

            document.getElementById("Bmprofilename").innerHTML = BMprofile_fname + ' ' + BMprofile_lname;
            document.getElementById("lastactive").innerHTML = Profile_active;
        }

        document.getElementById('profilelink').href = 'extra_profile_view_20.php';
        document.getElementById('forumlink').href = 'forum/';
        document.getElementById('familylink').href = 'extra_profile_view_25.php';
        document.getElementById('searchlink').href = 'extra_profile_view_24.php';
        document.getElementById('resourcelink').href = 'extra_profile_view_23.php';
        document.getElementById('agencylink').href = 'extra_profile_view_21.php';
        document.getElementById('favouritelink').href = 'extra_profile_view_22.php';
        

    },
    init: function (model) {
        var self = this;
        self.model = model;

    }

    ,
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
        dhtmlx.skin = self.model.globalSkin || "dhx_skyblue";

        configuration["icons_path"] = "icons/";
        self.configuration[self.uid] = configuration;

        self._loadData(self.uid, function () {
            self._form(self.uid);
        });


    }

}
Expctantparentprofileinfo.init(Expctantparentprofileinfo_Model);