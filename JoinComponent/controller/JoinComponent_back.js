//================= SAVE IT 
//=================     as controller/JoinComponent.js 
var JoinComponent = {
  uid : null,
 window_manager : null,
  window : [],
  layout : [],
  toolbar : [],
   grid : []
    ,form : []
 
    ,status_bar : []
 
    ,configuration : []
 
    ,_window_manager : function()
    {
        var self = this;
        self.window_manager = new dhtmlXWindows();
        self.window_manager.setImagePath( self.model.conf_window.image_path );
    }
 
    ,_window : function( uid )
    {
        var self = this;
 
        if(self.window_manager === null)
            self._window_manager(  );
 
 
        if(self.window_manager.isWindow( "window_JoinComponent_" + uid ))
 
        {
            self.window[ uid ].show();
            self.window[ uid ].bringToTop();
            return;
        }
        self.window[ uid ] = self.window_manager.createWindow( "window_JoinComponent_" + uid, self.model.conf_window.left + 10, 
         self.model.conf_window.top + 10, self.model.conf_window.width, self.model.conf_window.height );
        self.window[ uid ].setText( self.model.text_labels.main_window_title );
        self.window[ uid ].setIcon( self.model.conf_window.icon, self.model.conf_window.icon_dis );
         
 
        self.window[ uid ].attachEvent("onClose", function(win)
        {
            return true;
        });
        self.status_bar[ uid ] = self.window[ uid ].attachStatusBar();
    }
 
    ,_layout : function( uid )
    {
        var self = this;
        self.layout[ uid ] = self.window[ uid ].attachLayout( self.model.conf_layout.pattern );
        self.layout[ uid ].cells("a").hideHeader();
        
      //  self.layout[ uid ] = self.window[ uid ].attachLayout( self.model.conf_layout1.pattern );
    //  self.layout[ uid ].cells("a").hideHeader();
    }
 
    ,_toolbar : function( uid )
    {
        var self = this;
 
        self.toolbar[ uid ] = self.layout[ uid ].cells("a").attachToolbar( self.model.conf_toolbar );
 
        self.toolbar[ uid ].attachEvent("onClick", function(id)
        {   
            if(id == "")
            {
            }
        });
    }
 
    ,_grid : function( uid )
    {
        var self = this;
        self.grid[ uid ] = self.layout[ uid ].cells("a").attachGrid( self.model.conf_grid );
        self.grid[ uid ].setHeader( self.model.conf_grid.headers );
        self.grid[ uid ].setInitWidths( self.model.conf_grid.widths );
        self.grid[ uid ].setColAlign( self.model.conf_grid.colaligns );
        self.grid[ uid ].setColTypes( self.model.conf_grid.coltypes );
        self.grid[ uid ].setColSorting( self.model.conf_grid.colsorting );
        self.grid[ uid ].selMultiRows = true;
        self.grid[ uid ].setDateFormat("%m-%d-%Y");
        self.grid[ uid ].init();
         
         
        self.grid[ uid ].attachEvent("onRowSelect", function( id, ind )
        {
             
        });
    }
 
 	/* BY Eduardo */
	,dataStore : []
 	,_loadData : function( uid, callBack )
    { 
		var self = this;
      	
		var postStr = "1=1";
        dhtmlxAjax.post( self.configuration[ uid ].application_path + "processors/get_data.php", postStr, function(loader)
		{ 
			try
			{				
				var json = JSON.parse( loader.xmlDoc.responseText );
			    if( json.status == "success" )	
		        {  
                                         self.dataStore["aqb_pts_profile_types"] = json.aqb_pts_profile_types;
					 self.dataStore["sys_pre_values"] = json.sys_pre_values;
					 self.dataStore["sys_pre_values_region"] = json.sys_pre_values_region;
					 self.dataStore["sys_acl_levels"] = json.sys_acl_levels;
					 self.dataStore["profiles"] = json.profiles;
					 self.dataStore["captcha_key"] = json.captcha_key;
					 
					 if(callBack)
					 	callBack();
          		}
				else
				{
					dhtmlx.message( {type : "error", text : json.response} );
				}
			}
			catch(e)
			{
				dhtmlx.message( {type : "error", text : "Fatal error on server side: "+loader.xmlDoc.responseText } );
				console.log(e.stack);
			}
		});	
       
	   //get_data.php
	   
    }
	
	/* BY Eduardo */
    , _form: function (uid, json) {
		var self = this;
		var conf_form = self.model.conf_form.template;
	
	
		self.form = self.layout[uid].cells("a").attachForm(conf_form);
	
	
		var profile_types = self.form.getSelect("profiletypes");
		profile_types.options.add(new Option("SELECT", "-1"));
	
		var json = self.dataStore;
	
		var profile_type = json.aqb_pts_profile_types.rows;
	
		for (var i in profile_type) {
			var profileid = profile_type[i].id[0];
			var profiletype = profile_type[i].data[1];
	
			profile_types.options.add(new Option(profiletype, profileid));
		}
	
	
		join_window = new dhtmlXWindows();
		self.form.attachEvent("onChange", function (name) {
			var selectname = self.form.getSelect(name);
			var Profilevalue = selectname.options[selectname.selectedIndex].value;
	
	
			if (Profilevalue != -1) {
	
				if (Profilevalue == 2) {
	
					var popwidth = 600;
					var popheight = 710;
	
				} else {
	
					popwidth = 600;
					popheight = 500;
				}
	
	
				var conf_form_join = self.model.conf_form.template_join;
				var win_join = join_window.createWindow("w1", 150, 10, popwidth, popheight);
				win_join.setText("JOIN");
				join_form = win_join.attachForm(conf_form_join);
	
				var ckey = json.captcha_key;
				// alert(t);
	
				// var test_id = document.getElementById(t);
	
	
				// join_form.getContainer("captcha1").appendChild(document.getElementById("ss"));      
	
	
	
				var agencies = join_form.getSelect("agency");
				var adoption_agency = json.sys_pre_values.rows;
				for (var i in adoption_agency) {
					var agencyid = adoption_agency[i].id;
					var agencytype = adoption_agency[i].data[1];
	
					agencies.options.add(new Option(agencytype, agencyid));
				}
	
	
				var regions = join_form.getSelect("region");
				var region = json.sys_pre_values_region.rows;
				for (i in region) {
					var regionid = region[i].id;
					var regionvalue = region[i].data[1];
	
					regions.options.add(new Option(regionvalue, regionid));
				}
	
				var memberships = join_form.getSelect("mtype");
	
				var membership = json.sys_acl_levels.rows;
				for (i in membership) {
					var membershipid = membership[i].id;
					var membershipname = membership[i].data[1];
	
					memberships.options.add(new Option(membershipname, membershipid));
				}
	
				var States = join_form.getSelect("state");
	
				var state = json.profiles.rows;
				for (i in state) {
					var stateid = state[i].id;
					// var statename = state[i].data[1];
	
					States.options.add(new Option(stateid, stateid));
				}
	
				selectname = self.form.getSelect(name);
				Profilevalue = selectname.options[selectname.selectedIndex].value;
	
				if (Profilevalue == 4) {
					join_form.hideItem("mtype");
					join_form.hideItem("profilestatus", "single");
					join_form.hideItem("profilestatus", "couple");;
					join_form.hideItem("aboutlabel");
					//  join_form.hideItem("firstname");
					join_form.hideItem("lastname");
					join_form.hideItem("gender", "man");
					join_form.hideItem("gender", "woman");
	
					join_form.hideItem("aboutlabel_sec");
					join_form.hideItem("firstname_sec");
					join_form.hideItem("lastname_sec");
					join_form.hideItem("gender_sec", "man");
					join_form.hideItem("gender_sec", "woman")
				} else if (Profilevalue == 8) {
					join_form.hideItem("mtype");
					join_form.hideItem("profilestatus", "single");
					join_form.hideItem("profilestatus", "couple");
					join_form.hideItem("aboutlabel");
					join_form.hideItem("firstname");
					join_form.hideItem("lastname");
					join_form.hideItem("gender", "man");
					join_form.hideItem("gender", "woman");
	
					join_form.hideItem("aboutlabel_sec");
					join_form.hideItem("firstname_sec");
					join_form.hideItem("lastname_sec");
					join_form.hideItem("gender_sec", "man");
					join_form.hideItem("gender_sec", "woman")
	
				} else {
	
					join_form.hideItem("aboutlabel_sec");
					join_form.hideItem("firstname_sec");
					join_form.hideItem("lastname_sec");
					join_form.hideItem("gender_sec", "man");
					join_form.hideItem("gender_sec", "woman")
	
	
					join_form.attachEvent("onChange", function (id, value) {
	
						if (value == 'couple') {
							join_form.showItem("aboutlabel_sec");
							join_form.showItem("firstname_sec");
							join_form.showItem("lastname_sec");
							join_form.showItem("gender_sec", "man");
							join_form.showItem("gender_sec", "woman")
	
						} else if (value == 'single') {
	
							join_form.hideItem("aboutlabel_sec");
							join_form.hideItem("firstname_sec");
							join_form.hideItem("lastname_sec");
							join_form.hideItem("gender_sec", "man");
							join_form.hideItem("gender_sec", "woman")
	
						} else {}
					});
	
				}
	
				join_form.attachEvent("onButtonClick", function (name, command) {
	
					self._join_save(uid, join_form, name, Profilevalue);
	
				});
				
				
				join_form.getContainer("captcha").innerHTML = self.dataStore.captcha_key;
				
			}
		});
	}
    
    
    ,_join_save : function(uid,join_form,name,Profilevalue )
    {
       var self = this;
          if(name=="join"){
                                
                       if(join_form.getItemValue("username") =='')
                       {
                           
                          dhtmlx.message( {type : "error", text : "You must enter username" } )   
                           return false;
                       }         
                      if(join_form.getItemValue("password") =='')
                       {
                           
                          dhtmlx.message( {type : "error", text : "You must enter password" } )   
                          return false; 
                       }  
                       if(join_form.getItemValue("cpassword") =='')
                       {
                           
                          dhtmlx.message( {type : "error", text : "You must enter confirm password" } )   
                          return false; 
                       }  
                       
                     if(join_form.getItemValue("email") =='')
                       {
                           
                          dhtmlx.message( {type : "error", text : "You must enter email" } )   
                          return false;                   
                                               
                        
                       }       
                       
                     if(join_form.getItemValue("email") != '' ){
                           
                         var email = join_form.getItemValue("email");
                         var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

                         if (!filter.test(email)) {
        
                         dhtmlx.message( {type : "error", text : "You must enter valid email" } )
                         return false;
                         } 
                               
                           
                       }
                    if(join_form.getItemValue("password") != join_form.getItemValue("cpassword")  ) {
                       
                       dhtmlx.message( {type : "error", text : "Password confirmation failed" } )
                         return false;  
                      
                     }                  
                     
                     
                       
                     if(join_form.getItemValue("firstname") =='' && Profilevalue != 8)
                       {
                           
                          dhtmlx.message( {type : "error", text : "Enter your first name" } )   
                          return false; 
                       }  
                      if(join_form.getItemValue("lastname") =='' && Profilevalue != 8 && Profilevalue != 4)
                       {
                           
                          dhtmlx.message( {type : "error", text : "Enter your last name" } )   
                          return false; 
                       }  
                       
                       if(join_form.getItemValue("firstname_sec") =='' && Profilevalue != 8 && Profilevalue != 4 && join_form.getItemValue("profilestatus","couple") =='couple')
                       {
                           
                          dhtmlx.message( {type : "error", text : "Enter your couple first name" } )   
                          return false;
                       }  
                       if(join_form.getItemValue("lastname_sec") =='' && Profilevalue != 8 && Profilevalue != 4 && join_form.getItemValue("profilestatus","couple")=='couple' )
                       {
                           
                          dhtmlx.message( {type : "error", text : "Enter your couple last name" } )   
                          return false; 
                       }  
                       if(join_form.getItemValue("termsofuse","terms") =='')
                       {
                           
                          dhtmlx.message( {type : "error", text : "You must agree with terms of use" } )   
                          return false; 
                       }  
                  
                         
                      var psingle = join_form.getItemValue("profilestatus","single");
                      var pouple = join_form.getItemValue("profilestatus","couple");
                      var memtype = join_form.getItemValue("mtype");
                      var uname = join_form.getItemValue("username");
                      var pass = join_form.getItemValue("password");
                      var cpass = join_form.getItemValue("cpassword");
                      var email_id = join_form.getItemValue("email");
                      var fname = join_form.getItemValue("firstname");
                      var lname = join_form.getItemValue("lastname");
                      var gmale = join_form.getItemValue("gender","man");
                      var gfemale = join_form.getItemValue("gender","woman");
                      var cfname = join_form.getItemValue("firstname_sec");
                      var clanme = join_form.getItemValue("lastname_sec");
                      var cgmale = join_form.getItemValue("gender_sec","MAN");
                      var cgfemale = join_form.getItemValue("gender_sec","WOMAN");                      
                      var uagency = join_form.getItemValue("agency");
                      var ustate = join_form.getItemValue("state");
                      var uregion= join_form.getItemValue("region");
                      var agreement = join_form.getItemValue("termsofuse");                      
                                            
                      var poststr = "ptypes=" + psingle + "&ptypec=" + pouple + "&mtype=" + memtype + "&usern=" + uname + "&passw=" + pass + "&cpassw=" + cpass + "&emailid=" + email_id + "&firstname=" + fname
                      + "&lastname=" + lname + "&gendermale=" + gmale + "&genderfemale=" + gfemale + "&couplfname=" + cfname + "&couplelname=" + clanme + "&cgenedermale=" + cgmale + "&cgenederfemale=" + cgfemale
                      + "&useragency=" + uagency + "&userstate=" + ustate + "&userregion=" + uregion + "&uagree=" + agreement;                                      
                                
                      dhtmlxAjax.post(self.configuration[ uid ].application_path + "processors/insert_data.php", poststr, function(loader){                                              
                          
                       var data_json = JSON.parse( loader.xmlDoc.responseText );
	              
                       if(data_json.username_error)
                       {
                         dhtmlx.message( {type : "error", text : "This Username is already in use by another member. Please select another Username." } )   
                          return false;                
                       }                       
                       if(data_json.email_error)
                       {
                         dhtmlx.message( {type : "error", text : "This Username is already in use by another member. Please select another Username." } )   
                          return false;                
                       }   
                          
                          
                        
                            });  
                         return true;    
                            
                         } 
        
    } 
   ,init : function( model )
    {
        var self = this;
        self.model = model;
    }
 
    ,start : function( configuration )
    {
        var self = this;
        self.uid = configuration.uid;
 
        if( (typeof configuration.uid === 'undefined') ||  (configuration.uid.length === 0))
        {
            dhtmlx.message( {type : "error", text : "uid is missing"} );
            return;
        }
 
        if( (typeof configuration.application_path === 'undefined') ||  (configuration.application_path.length === 0))
        {
            dhtmlx.message( {type : "error", text : "application_path is missing"} );
            return;
        }
 
        if( (typeof configuration.dhtmlx_codebase_path === 'undefined') ||  (configuration.dhtmlx_codebase_path.length === 0))
        {
            dhtmlx.message( {type : "error", text : "dhtmlx_codebase_path is missing"} );
            return;
        }
 
        window.dhx_globalImgPath = configuration.dhtmlx_codebase_path + "imgs/";
        dhtmlx.skin = self.model.globalSkin || "dhx_skyblue";
 
        configuration["icons_path"] = "icons/";
 
        self.configuration[ self.uid  ] = configuration;
 
 
        self.model.conf_window.image_path  = configuration.application_path + configuration.icons_path;
        self.model.conf_toolbar.icon_path =  configuration.application_path + configuration.icons_path;
 
        self._window( self.uid );
      	self._layout( self.uid );
		
		/* BY Eduardo */
		self.layout[  self.uid ].progressOn();
		self.window[  self.uid ].progressOn();
		
		self._loadData( self.uid, function()
		{
			self._form( self.uid );
			self.layout[  self.uid ].progressOff();
			self.window[  self.uid ].progressOff();
		});
    
      	
        //self._toolbar( self.uid );
       // self._grid( self.uid );
 
    }
 
}
 
 
JoinComponent.init( JoinComponent_Model );
//================= SAVE IT 
//=================     as controller/JoinComponent.js