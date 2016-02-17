/***********************************************************************
 * Name:    Prashanth A
 * Date:    11/11/2013
 * Purpose: Lising families liked by birth mother
 ***********************************************************************/
var Expctantparentprofilefavorite = {
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
          self.dataStore["favour_family"] = json.favour_family;

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

    var self = this;


    var json = self.dataStore;


    var Profiles = json.Profiles.rows;
    var Profile_active = json.Profile_active.rows;
    var Favour_family = json.favour_family.rows;


    for (i in Profiles) {
      var BMprofile_fname = Profiles[i].data[1];
      var BMprofile_lname = Profiles[i].data[2];
      var agency_id = Profiles[i].data[3];

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

    var conf_form = self.model.conf_form.template_profileview;
    var Profile_view = new dhtmlXForm("data_container_fav", conf_form);
    if (Profile_view != '') {
      self.view = new dhtmlXDataView({
        container: Profile_view.getContainer("data_container"),
        type: {
          template: "http->" + self.configuration[self.uid].application_path + "/templ/families_fav_tpl.html",
          width: 300, //width of single item
          height: 550, //height of single item
          margin: 0,
          padding: 0,
          
          css: "bm_like",
        }
      });

      pager = self.view.define("pager", {
        container: "page_here",
        size: 9
      });

      var count = self.view.dataCount();
      new_one = "&nbsp #count# Results,{common.prev()} &nbsp; Page {common.page()} from #limit# {common.next()}";
      pager.define("template", new_one);
      
         pager.attachEvent("onafterpagechange", function () {
          self.iteamAdjust();
          self.familyFavViewEvents();
        });
      
      
      self.view.define("select", false);
      self.view.define("", false);
      if (Favour_family > 0) {
        var timestamp = new Date().getUTCMilliseconds();    
        self.view.load("Expctantparentprofilefavorite/processors/familylike_list.php?random="+timestamp, "json", function () {
          self.iteamAdjust();
//          $(".print_profile").on('click', function (e) {
//            e.preventDefault();
//            var prof_id = $(this).attr('data-profile-id');
//            self.printpdf(prof_id);
//          });
//
//          $(".data_del_fav").on("click", function () {
//              alert("dfg");
//            var agencyid = $(this).val();
//            self.deleteFavourate();
//          });

            self.iteamAdjust();
            self.familyFavViewEvents();

        });
      }
     else {
         document.getElementById('data_container_fav').style.height="100px"; 
        document.getElementById('error').innerHTML = "No items to display";
      }
    }
  },
  
  familyFavViewEvents: function () {
    var self = this;
    $(".data_del").on("click", function (e) {
 
      e.preventDefault();
      var agencyid =$(this).attr('data-value');
      self.deleteFavourate(agencyid, self.view);
    });
    $(".print_profile").on('click', function (e) {
      e.preventDefault();
      var prof_id = $(this).attr('data-profile-id');
      self.printpdf(prof_id);
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
      //$('.dhx_dataview_item.').css('padding-top', 0);
      $(window).on('resize', function () {
        $('.imagecontaier img').css({
          'width': 300,
          'max-height': 230,
          'height': 'auto',
          'margin': 0
        });
        //$('.dhx_dataview_item').css('margin-top', 20);
      });
    });
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
          dhtmlxAjax.post(self.configuration[self.uid].application_path + "processors/delete_fav.php", poststr, function (loader) {
            self.view.remove(agencyid);
            self.view.clearAll()
            var rand = new Date().getTime();
            self.view.load("Expctantparentprofilefavorite/processors/familylike_list.php?rand="+rand, "json", function () {
            self.iteamAdjust();
            self.familyFavViewEvents();
          });
            
          });
        }
      }
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
Expctantparentprofilefavorite.init(Expctantparentprofilefavorite_Model);