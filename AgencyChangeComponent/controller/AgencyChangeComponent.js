/***********************************************************************
 * Name:    Prashanth A
 * Date:    19/11/2013
 * Purpose: Creating a agency public profile page
 ***********************************************************************/
var AgencyChangeComponent = {
  uid: null,
  form: [],
  configuration: [],
  dataStore: []
  ,change_pass: null
  ,conf_form_pass: null
  ,getUrlVars: function () {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
      vars[key] = value;
    });

    return vars;
  },
   _loadData: function (uid, callBack) {
    var self = this;
 

  },

  start: function (configuration) {
       var self = this;
       var inputname;
       self.c = configuration;
       self.conf_form_pass = self.model.conf_form.change_pass;
       self.uid = configuration.uid;
    self.siteurl = configuration.siteurl;
      self.configuration[self.uid] = configuration;
 
          self._loadData(self.uid, function () {
        self._form(self.uid);
        
      });
      
       self.change_pass = new dhtmlXForm("PwdFormone", self.conf_form_pass);
       self.changePasswordCheck();
       var json = self.dataStore;
          var postStr = "1-1";
     dhtmlxAjax.post(self.configuration[self.uid].application_path + "processors/get_data.php", postStr, function (loader) {
                var json = JSON.parse(loader.xmlDoc.responseText);
                   // Populating values to agency selection field      
        var agencies =  self.change_pass.getSelect("agency");
        agencies.options.add(new Option("SELECT AN AGENCY", ""));
        var adoption_agency = json.sys_pre_values.rows;;
        for (var i in adoption_agency) {
          var agencyid = adoption_agency[i].id;
          var agencytype = adoption_agency[i].data[1];
          agencies.options.add(new Option(agencytype, agencyid));
        }
       

    });
       

   $( "#Cpwdone" ).click(function () {
    if ( $( "#PwdFormone" ).is( ":hidden" ) ) {
//    	self.change_pass.setItemValue("old_pass",'');
		  self.change_pass.setItemValue("new_pass",'');
		  self.change_pass.setItemValue("confirm_pass",'');
        $( "#PwdFormone" ).slideDown( "slow" );
        
    } else {
    $( "#PwdFormone" ).slideUp( "slow" );    
    }
    });
    
    $( "#closeone" ).click(function () {   
    $( "#PwdFormone" ).slideUp( "slow" );    
    
    });
  }

  ,changePasswordCheck: function(){
     var self = this;
      self.change_pass.attachEvent("onButtonClick", function (name, command) {
        var new_agency = self.change_pass.getItemValue("agency");
        var description = self.change_pass.getItemValue("description");


              if(new_agency.trim() == ''){
              
              dhtmlx.message({
                  type: "alert-error",
                  text: "Please select an agency",
                  callback: function() {self.focusfields_values("new_pass");}
                }) ;
              return false;
              } 
          else {    
        	  var poststr = "agency_title=" + new_agency + "&description=" + description;
     
                              dhtmlxAjax.post(self.c.application_path + "processors/sendemail.php", poststr, function (loader) {
                                var json = JSON.parse(loader.xmlDoc.responseText);
                                if (json.status == "success") {
                               //   location.reload();
                               // } else {
                                  dhtmlx.message({
                                  type: "error",
                                    text: "The agency change request send successfully."
                                  });
                                  $( "#PwdFormone" ).slideUp( "slow" ); 
                                }
                              });

                        
 
              return true;
          }
            
       
        });

  }
  ,init: function (model) {
        var self = this;
        self.model = model;

    }

}
//var url = "ChangePassword/model/Changepasswordcomponent_Model.js";
//$.getScript( url, function() {
AgencyChangeComponent.init(AgencyChangeComponent_Model);
//});



