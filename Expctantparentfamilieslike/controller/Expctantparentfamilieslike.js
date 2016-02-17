/***********************************************************************
 * Name:    Prashanth A
 * Date:    11/11/2013
 * Purpose: Lising families liked by birth mother
 ***********************************************************************/
var Expctantparentfamilieslike = {
  uid: null,
  form: [],
  configuration: [],
  dataStore: [],
  template: '',
  likeView: '',
  favView: '',
  family_liked :'',
  favourd_family : '',
  _loadData: function (uid, callBack) {
    var self = this;
    var postStr = "1-1";
    dhtmlxAjax.post(self.configuration[uid].application_path + "processors/family_view_information.php", postStr, function (loader) {
      try {
        var json = JSON.parse(loader.xmlDoc.responseText);
        if (json.status == "success") {
          self.dataStore["Profiles"] = json.Profiles;
          self.dataStore["Profile_active"] = json.Profile_active;
          self.dataStore["favourd_family"] = json.favourd_family;
          self.dataStore["family_liked"] = json.family_liked;
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
  printpdf: function (id) {
    var self = this;
    var uid = self.uid;
    var poststr = "Profileid=" + id;
    dhtmlxAjax.post(self.configuration[uid].application_path + "processors/pdf_profile_view.php", poststr, function (loader) {
      var json = JSON.parse(loader.xmlDoc.responseText);
      if (json.status == 'success') {
        if (json.printprofile.rows) {
          if (json.deafulttempid.rows == 0) {
            window.open(json.printprofile.rows);
          } else {
            $.ajax({
              url: siteurl + "PDFUser/regenearate_pdf.php",
              type: "POST",
              cache: false,
              data: {
                sel_tmpuser_ID: json.printprofile.rows
              },
              datatype: "json",
              success: function (data) {
                window.open(data.filename);
                return false;

              }
            });
          }
        } else {
          dhtmlx.alert("There is no Deault PDF set");
        }
      } else {
        dhtmlx.alert("There is no Deault PDF set");
      }
    });
  },
  _form: function (uid) {
    var self = this,
      json = self.dataStore;
      Profiles = json.Profiles.rows;
      Profile_active = json.Profile_active.rows;
      self.favourd_family = json.favourd_family.rows;
      self.family_liked = json.family_liked.rows;
    for (var i in Profiles) {
      var BMprofile_fname = Profiles[i].data[1];
      var BMprofile_lname = Profiles[i].data[2];
      var agency_id = Profiles[i].data[3];
      BMid = Profiles[i].data[0];
    }
    var conf_form_button = self.model.conf_form_family.template_familiesbutton;
    var Profile_view_button = new dhtmlXForm("button_div", conf_form_button);
    Profile_view_button.attachEvent("onButtonClick", function (name, command) {
      if (name == "sortby") {
	  	  var postStr = "1-1";
	   dhtmlxAjax.post(self.configuration[uid].application_path + "processors/family_view_information.php", postStr, function (loader) {
	    var json = JSON.parse(loader.xmlDoc.responseText);
        if (json.status == "success") {
          self.dataStore["Profiles"] = json.Profiles;
          self.dataStore["Profile_active"] = json.Profile_active;
          self.dataStore["favourd_family"] = json.favourd_family;
          self.dataStore["family_liked"] = json.family_liked;
		  self.family_liked = json.family_liked.rows;
		  self.favourd_family = json.favourd_family.rows;
        } else {
          dhtmlx.message({
            type: "error",
            text: json.response
          });
        }
	   });
        self.likeView = new dhtmlXDataView({
          container: Profile_view.getContainer("family_container"),
          //  height: "auto",
          
          type: {
            template: "http->" + self.configuration[self.uid].application_path + "/templ/familiesilike_templ.html",
            width: 300, //width of single item
            height: 560, //height of single item
            margin: 0,
            padding: 0,
            
            css: "bm_like",
          }
        });
        pager = self.likeView.define("pager", {
          container: "page_here",
          size: 9
        });
        var count = self.likeView.dataCount();
        new_ones = "&nbsp; #count# Results,{common.prev()} &nbsp; Page {common.page()} from #limit# {common.next()}";
        pager.define("template", new_ones);
         pager.attachEvent('onafterpagechange', function () {
           self.iteamAdjust();
           self.likeView.refresh();
         });
        
        self.likeView.define("select", false);
       // if (family_liked > 0) {
          var rand = new Date().getTime(); 
       self.likeView.load("Expctantparentfamilieslike/processors/familylike_list.php?sortvalue=" + name+"&randnum="+rand, "json", function () {
            self.iteamAdjust();
           // self.familyLikeViewEvents();
          });
      //  }
      if (self.family_liked <= 0) {         
        //document.getElementById('family_container_div').style.height="100px";
         document.getElementById('error').style.display = 'block';
        document.getElementById('error').innerHTML = "No items to display";    
        }else{
		 document.getElementById('error').style.display = 'none';
		}
      }
      if (name == "favourites") {
	  var postStr = "1-1";
	   dhtmlxAjax.post(self.configuration[uid].application_path + "processors/family_view_information.php", postStr, function (loader) {
	    var json = JSON.parse(loader.xmlDoc.responseText);
        if (json.status == "success") {
          self.dataStore["Profiles"] = json.Profiles;
          self.dataStore["Profile_active"] = json.Profile_active;
          self.dataStore["favourd_family"] = json.favourd_family;
          self.dataStore["family_liked"] = json.family_liked;
		  self.family_liked = json.family_liked.rows;
		  self.favourd_family = json.favourd_family.rows;
        } else {
          dhtmlx.message({
            type: "error",
            text: json.response
          });
        }
	   });
	  
        self.favView = new dhtmlXDataView({
          container: Profile_view.getContainer("family_container"),
          type: {
            template: "http->" + self.configuration[self.uid].application_path + "/templ/families_fav_tpl.html",
            width: 300, //width of single item
            height: 560, //height of single item
            margin: 0,
            padding: 0,
            css: "bm_like",
          }
        });
        pager1 = self.favView.define("pager", {
          container: "page_here",
          size: 9
        });

        var count = self.favView.dataCount();
        new_one = "&nbsp; #count# Results,{common.prev()} &nbsp; Page {common.page()} from #limit# {common.next()}";
        pager1.define("template", new_one);

         pager1.attachEvent("onafterpagechange", function () {
           self.iteamAdjust();
          // self.familyFavViewEvents();
           self.favView.refresh();
         });
        self.favView.define("select", false);

     //   if (favourd_family > 0) {
          var randfavs = new Date().getTime();  
          self.favView.load("Expctantparentfamilieslike/processors/familyfavourite_list.php?randfav="+randfavs, "json", function () {
            self.iteamAdjust();
            //self.familyFavViewEvents();
          });
      //  }
        if (self.favourd_family <= 0) {          
        //document.getElementById('family_container_div').style.height="100px";
        document.getElementById('error').style.display = 'block';
        document.getElementById('error').innerHTML = "No items to display";    
        }else{
		document.getElementById('error').style.display = 'none';
		}
        self.favView.define("select", false);
      }
    });

    var conf_form = self.model.conf_form_family.template_familyview;
    var Profile_view = new dhtmlXForm("family_container_div", conf_form);
    if (Profile_view != '') {
      self.likeView = new dhtmlXDataView({
        container: Profile_view.getContainer("family_container"),
        type: {
          template: "http->" + self.configuration[self.uid].application_path + "/templ/familiesilike_templ.html",
          width: 300, //width of single item
          height: 560, //height of single item
          marginTop: 10,
          padding: 0,
          css: "bm_like",
        }
      });
      pager = self.likeView.define("pager", {
        container: "page_here",
        size: 9
      });

      var count = self.likeView.dataCount();
      new_oness = "&nbsp; #count# Results,{common.prev()} &nbsp; Page {common.page()} from #limit# {common.next()}";

      pager.define("template", new_oness);

       pager.attachEvent("onafterpagechange", function () {
         self.iteamAdjust();
         //self.familyLikeViewEvents();
       });
      self.likeView.define("select", false);

     // if (family_liked < 0) {
        var rands = new Date().getTime();   
        self.likeView.load("Expctantparentfamilieslike/processors/familylike_list.php?randnumer="+rands,
          "json",
          function () {
            self.iteamAdjust();
            //self.familyLikeViewEvents();
          });
        self.likeView.define("select", false);
     // }
      if (self.family_liked <= 0) {        
      //document.getElementById('family_container_div').style.height="100px"; 
      document.getElementById('error').style.display = 'block';
      document.getElementById('error').innerHTML = "No items to display";    
      }else{
	  document.getElementById('error').style.display = 'none';
	  }
    }
  },
  familyFavViewEvents: function () {
    var self = this;
    $(".data_del_fav").on("click", function (e) {
      e.preventDefault();
      var agencyid =$(this).attr('data-value');
      self.deleteFavourate(agencyid, self.favView);
    });
    $(".print_profile").on('click', function (e) {
      e.preventDefault();
      var prof_id = $(this).attr('data-profile-id');
      self.printpdf(prof_id);
    });
  },
  familyLikeViewEvents: function () {
    var self = this;
  //  setTimeout(function () {
      $(".data_del").on("click", function (e) {
        e.preventDefault();
        var agencyid = $(this).attr('data-value');
        self.deleteFamily(agencyid);
      });
      $(".data_fav").on("click", function (e) {
        e.preventDefault();
        var agencyid = $(this).attr('data-value');
        self.addToFav(agencyid);
      });
      $(".print_profile").on('click', function (e) {
        e.preventDefault();
        var prof_id = $(this).attr('data-profile-id');
        self.printpdf(prof_id);
      });
    //}, 100)
  },
  deleteFavourate: function (agencyid, view) {
    var self = this;
    dhtmlx.confirm({
      //title: "Close",
      type: "confirm-warning",
      text: "Are you sure you want delete it?",
      ok: "Yes",
      cancel: "No",
      callback: function (response) {
        if (response) {
          var poststr = "Agencyid=" + agencyid + "&Birthmotherid=obj.profile_id";
          dhtmlxAjax.post(self.configuration[self.uid].application_path + "processors/delete_favourite.php", poststr, function (loader) {
            self.favView.remove(agencyid);
            /*setTimeout(function () {
              self.familyFavViewEvents();
            }, 100);*/
			self.favView.clearAll();
			self.iteamAdjust();
            var randfavsdel = new Date().getTime(); 

			  	  var postStr = "1-1";
	   dhtmlxAjax.post(self.configuration[self.uid].application_path + "processors/family_view_information.php", postStr, function (loader) {
	    var json = JSON.parse(loader.xmlDoc.responseText);
        if (json.status == "success") {
          self.dataStore["Profiles"] = json.Profiles;
          self.dataStore["Profile_active"] = json.Profile_active;
          self.dataStore["favourd_family"] = json.favourd_family;
          self.dataStore["family_liked"] = json.family_liked;
		  self.family_liked = json.family_liked.rows;
		  self.favourd_family = json.favourd_family.rows;
		if (self.favourd_family <= 0) {          
        //document.getElementById('family_container_div').style.height="100px";
        document.getElementById('error').style.display = 'block';
        document.getElementById('error').innerHTML = "No items to display";    
        }else{
		document.getElementById('error').style.display = 'none';
		}
          self.favView.load("Expctantparentfamilieslike/processors/familyfavourite_list.php?randfavsdel"+randfavsdel, "json", function () {
            self.iteamAdjust();
            //self.familyFavViewEvents();
          });
        } else {
          dhtmlx.message({
            type: "error",
            text: json.response
          });
        }
	   });			  

          });
        }
      }
    });
  },
  addToFav: function (agencyid) {
    var self = this;
    dhtmlx.confirm({
      //title: "Close",
      type: "confirm-warning",
      text: "Are you sure you want add this family to your favourite list?",
      ok: "Yes",
      cancel: "No",
      callback: function (response) {
        if (response) {
          var poststr = "Agencyid=" + agencyid + "&Birthmotherid=obj.profile_id";
          dhtmlxAjax.post(self.configuration[self.uid].application_path + "processors/family_fav.php", poststr, function (loader) {
            var n = JSON.parse(loader.xmlDoc.responseText);
            if (n.status == "success") {
              dhtmlx.message({
                text: 'Added to favourites.'
              })
						  	  var postStr = "1-1";
	   dhtmlxAjax.post(self.configuration[self.uid].application_path + "processors/family_view_information.php", postStr, function (loader) {
	    var json = JSON.parse(loader.xmlDoc.responseText);
        if (json.status == "success") {
		          self.dataStore["Profiles"] = json.Profiles;
          self.dataStore["Profile_active"] = json.Profile_active;
          self.dataStore["favourd_family"] = json.favourd_family;
          self.dataStore["family_liked"] = json.family_liked;
		  self.family_liked = json.family_liked.rows;
		  self.favourd_family = json.favourd_family.rows;
		if (self.favourd_family <= 0) {          
        //document.getElementById('family_container_div').style.height="100px";
        document.getElementById('error').style.display = 'block';
        document.getElementById('error').innerHTML = "No items to display";    
        }else{
		document.getElementById('error').style.display = 'none';
		}
		}
		});		
			  
			  
			  
            } else {
              dhtmlx.message({
                type: "error",
                text: n.response
              })
            }
          });
        }
      }
    });
  },
  deleteFamily: function (agencyid) {
    var self = this;
    dhtmlx.confirm({
      type: "confirm-warning",
      text: "Are you sure you want delete it?",
      ok: "Yes",
      cancel: "No",
      callback: function (response) {
        if (response) {
          var poststr = "Agencyid=" + agencyid;
          dhtmlxAjax.post(self.configuration[self.uid].application_path + "processors/delete_family.php", poststr, function (loader) {
              self.likeView.clearAll();
              var timestamp = new Date().getUTCMilliseconds(); 
			  
			  	  var postStr = "1-1";
	   dhtmlxAjax.post(self.configuration[self.uid].application_path + "processors/family_view_information.php", postStr, function (loader) {
	    var json = JSON.parse(loader.xmlDoc.responseText);
        if (json.status == "success") {
          self.dataStore["Profiles"] = json.Profiles;
          self.dataStore["Profile_active"] = json.Profile_active;
          self.dataStore["favourd_family"] = json.favourd_family;
          self.dataStore["family_liked"] = json.family_liked;
		  self.family_liked = json.family_liked.rows;
		  self.favourd_family = json.favourd_family.rows;
		 if (self.family_liked <= 0) {         
        //document.getElementById('family_container_div').style.height="100px";
         document.getElementById('error').style.display = 'block';
        document.getElementById('error').innerHTML = "No items to display";    
        }else{
		 document.getElementById('error').style.display = 'none';
		}
		  self.likeView.load("Expctantparentfamilieslike/processors/familylike_list.php?sortvalue=" + name+"&randnums="+timestamp, "json", function () {
            self.iteamAdjust();
            //self.familyLikeViewEvents();
          });
        } else {
          dhtmlx.message({
            type: "error",
            text: json.response
          });
        }
	   });
			self.iteamAdjust();
            /*view.remove(agencyid);
            self.familyLikeViewEvents();*/
          });
        }
      }
    });
  },
  iteamAdjust: function () {
    setTimeout(function () {
      $(".dhx_dataview_item:nth-child(2n)").css({
        'margin-left': 55,
        'margin-right': 55
      });
    }, 100);
    $(window).on('resize', function () {
      setTimeout(function () {
        $(".dhx_dataview_item:nth-child(2n)").css({
          'margin-left': 55,
          'margin-right': 55
        });
      }, 100);
      $('.dhx_dataview_item').css({
        'padding': 0
      });
    });

    $('.imagecontaier img').on('load', function () {
      $(this).css({
        'width': 300,
        'height': 'auto',
        'margin': 0
      });
      setTimeout(function () {
        $(this).css({
          'width': 300,
          'height': 'auto',
          'margin': 0
        });
      }, 100)

      $(window).on('resize', function () {
        $('.imagecontaier img').css({
          'width': 300,
          'max-height': 230,
          'height': 'auto',
          'margin': 0
        });
        $('.dhx_dataview_item').css('margin-top', 20);
      });
    });
  },
  init: function (model) {
    var self = this;
    self.model = model;
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
    dhtmlx.skin = self.model.globalSkin || "dhx_skyblue";
    configuration["icons_path"] = "icons/";
    self.configuration[self.uid] = configuration;
    self._loadData(self.uid, function () {
      self._form(self.uid);
    });
  }
}
Expctantparentfamilieslike.init(Expctantparentfamilieslike_Model);