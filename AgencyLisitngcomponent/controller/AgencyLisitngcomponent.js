/***********************************************************************
 * Name:    Prashanth A
 * Date:    19/11/2013
 * Purpose: Creating a agency public profile page
 ***********************************************************************/
var AgencyLisitngcomponent = {
    uid: null,
    form: [],
    configuration: [],
    dataStore: [],
    _loadData: function (uid, callBack) {
        var self = this;
        var postStr = "1-1";
        dhtmlxAjax.post(self.configuration[uid].application_path + "processors/agency_listing.php", postStr, function (loader) {
            try {
                var json = JSON.parse(loader.xmlDoc.responseText);

                if (json.status == "success") {
                   
                    self.dataStore["agency_address"] = json.agency_address;
                     self.dataStore["count"] = json.count;
                      

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
    _createDiv: function (id){
  
        var selc=document.getElementById("container-element");
        divBlue=document.createElement("div");
        divBlue.className = "agenciesCol"; 
        divBlue.id = "agenciesCol_"+id;
        divBlue.innerHTML = '<br/>'
        selc.appendChild(divBlue);
        return "agenciesCol_"+id;
         
    }
    ,
    _form: function (uid) {


        var self = this;
        
        //var conf_form = self.model.conf_form.template_agencylist;
        // var Agency_view = new dhtmlXForm("agencylikes", conf_form);
       
        var json = self.dataStore;
        var breakCount = Math.round(json.count / 4)+1;
        var agency_address = json.agency_address.rows;
        var outerCount=0;
        var DivId;
        for (i in agency_address) {   
            if(outerCount % breakCount === 0 ){                
                DivId = self._createDiv(outerCount/breakCount)                
                }
            outerCount++
            var profile_agency_name  = agency_address[i].data[0];
            var profile_region = agency_address[i].id;      
            var selc=document.getElementById(DivId);
            divBlue=document.createElement("div");
            divBlue.className = "headerGreen2";
            divBlue.innerHTML += profile_region;
            selc.appendChild(divBlue);
            for (j in agency_address[i].data) {
                if(outerCount % breakCount === 0 ){                     
                DivId = self._createDiv(outerCount/breakCount);
                
                }var selc=document.getElementById(DivId);
//                selc.innerHTML += '<a href="'+self.configuration.webUrl+'extra_agency_view_29.php?ID='+agency_address[i].profileId[j]+'">'+agency_address[i].data[j]+'</a><br/>';
                selc.innerHTML += '<a href="'+self.configuration.webUrl+'agency/'+agency_address[i].nickname[j]+'">'+agency_address[i].data[j]+'</a><br/>';
                outerCount++
            }
            
        //document.getElementById("agencyregion").innerHTML += profile_region;
        // document.getElementById("agencylisting").innerHTML +=  profile_agency_name+'</br>';
        }
        
    
         
    },
    init: function (model) {
        var self = this;
        self.model = model;

    }

    ,
    start: function (configuration) {
        var self = this;
        self.uid = configuration.uid;
        self.configuration = configuration;
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
AgencyLisitngcomponent.init(AgencyLisitngcomponent_Model);