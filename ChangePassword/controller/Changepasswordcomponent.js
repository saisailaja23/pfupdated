/***********************************************************************
 * Name:    Prashanth A
 * Date:    19/11/2013
 * Purpose: Creating a agency public profile page
 ***********************************************************************/
var changePasswordcomponent = {
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
  firstToUpperCase :function ( str ) {
	    return str.substr(0, 1).toUpperCase() + str.substr(1);
	},
  start: function (configuration) {
       var self = this;
       var inputname;
       self.c = configuration;
       self.conf_form_pass = self.model.conf_form.change_pass;
      
       self.change_pass = new dhtmlXForm("PwdForm", self.conf_form_pass);
       self.changePasswordCheck();
   
       $('.SavePwd').find('.dhxform_btn').css("margin-bottom","20px");
       self.change_pass.attachEvent("onInputChange", function (name_input, value_input) {
           if(value_input.match(/\s/g)){ 
        	   inputname = name_input.split("_");
               dhtmlx.message({
                   type: "alert-error",
                   text: self.firstToUpperCase(inputname[0])+" Password contains space",
                   callback: function() {self.focusfields_values(name_input);}
                 }) ;    	   
           }
           });
   $( "#Cpwd" ).click(function () {
    if ( $( "#PwdForm" ).is( ":hidden" ) ) {
//    	self.change_pass.setItemValue("old_pass",'');
		  self.change_pass.setItemValue("new_pass",'');
		  self.change_pass.setItemValue("confirm_pass",'');
        $( "#PwdForm" ).slideDown( "slow" );
        
    } else {
    $( "#PwdForm" ).slideUp( "slow" );    
    }
    });
    
    $( "#close" ).click(function () {   
    $( "#PwdForm" ).slideUp( "slow" );    
    
    });
  }
  ,focusfields_values : function(name){
	  var self = this;
	  
	  if(name == 'new_pass'){
		  self.change_pass.setItemValue("new_pass",'');
		  self.change_pass.setItemValue("confirm_pass",'');
		  self.change_pass.setItemFocus("new_pass");
	  }
//          else if(name == 'old_pass'){
//		  self.change_pass.setItemValue("old_pass",'');
//		  self.change_pass.setItemFocus("old_pass");  
//	  }
          
          
          else if(name == 'confirm_pass'){
		  self.change_pass.setItemValue("confirm_pass",''); 
		  self.change_pass.setItemFocus("confirm_pass")
	  }
	  
  }
  ,changePasswordCheck: function(){
     var self = this;
      self.change_pass.attachEvent("onButtonClick", function (name, command) {
       // var old_password = self.change_pass.getItemValue("old_pass");
        var new_password = self.change_pass.getItemValue("new_pass");
        var confirm_password = self.change_pass.getItemValue("confirm_pass");

        if (name == "changepassword") {
//            if (old_password.trim() == '') {
//            dhtmlx.message({
//              type: "alert-error",
//              text: "Please enter the old password",
//              callback: function() {self.focusfields_values("old_pass");}
//            });
//            
//            return false;
//          } else 
              
              if( new_password.trim() == ''){
              
              dhtmlx.message({
                  type: "alert-error",
                  text: "Please enter the new password",
                  callback: function() {self.focusfields_values("new_pass");}
                }) ;
              return false;
          }else if (new_password.length < 5) {
              dhtmlx.message({
                  type: "alert-error",
                  text: "Password length should be atleast five characters.",
                  callback: function() {self.focusfields_values("new_pass");}
                }) ;
              return false;           
          }else if(confirm_password.trim() ==''){
              dhtmlx.message({
                  type: "alert-error",
                  text: "Please enter the confirmation password",
                  callback: function() {self.focusfields_values("confirm_pass");}
                });
              return false;
          }else if(new_password.trim() != confirm_password.trim()){
              dhtmlx.message({
                  type: "alert-error",
                  text: "The new password and confirmation password do not match.",
                  callback: function() {self.focusfields_values("new_pass");}
                });  
              return false;
          }else{
        	  //var poststr = "new_pass=" + new_password + '&old_password=' + old_password;
        	  var poststr = "new_pass=" + new_password;
        	  
             // dhtmlxAjax.post(self.c.application_path + "processors/check_password.php", poststr, function (loader) {
              //    var json = JSON.parse(loader.xmlDoc.responseText);
                 // if (json.status == "success") {
                      dhtmlx.confirm({
                          type: "confirm",
                          text: "If you change your password you have to re-login<br/> press <b>OK</b> to continue",
                          callback: function (result) {
                            if (result == true) {
                              // Inserting values to database
                              dhtmlxAjax.post(self.c.application_path + "processors/change_password.php", poststr, function (loader) {
                                var json = JSON.parse(loader.xmlDoc.responseText);
                                if (json.status == "success") {
                                  location.reload();
                                } else {
                                  dhtmlx.message({
                                    type: "error",
                                    text: json.response
                                  });
                                }
                              });

                            }
                          }
                        }); 
                 // } 
                  
                 // else {
                 //   dhtmlx.message({
                 //     type: "error",
                 //     text: json.response
                //    });
                 // }
               // });
              
              
        	  
        	  

              
              
              return true;
          }
            
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
changePasswordcomponent.init(changePassword_Model);
//});



