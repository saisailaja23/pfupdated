/***********************************************************************
 * Name:    Prashanth A
 * Date:    11/11/2013
 * Purpose: Lising agencies liked by birth mother
 ***********************************************************************/
var Expctantparentprofile = {
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
          self.dataStore["agency_num"] = json.agency_num;
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
    Agency_num = json.agency_num.rows;
    for (i in Profiles) {
      var BMprofile_fname = Profiles[i].data[1];
      var BMprofile_lname = Profiles[i].data[2];
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
    document.getElementById('profilelink').href = 'extra_profile_view_20.php';

    var conf_form = self.model.conf_form.template_profileview;
    var Profile_view = new dhtmlXForm("data_containers", conf_form);
    if (Profile_view != '') {
      window.view = new dhtmlXDataView({
        container: Profile_view.getContainer("data_container"),
       // height: "auto",
        type: {
          template: "http->" + self.configuration[self.uid].application_path + "/templ/agency_like_view.html",
          width: 300, //width of single item
          height: 360, //height of single item
          margin: 0,
          padding: 10,
          css: "bm_like",
        }
      });

      pager = view.define("pager", {
        container: "page_here",
        size: 9
      });
      pager.attachEvent('onBeforePageChange', function (next,prev) {
        if((next=="0"&&prev=="0")||(next==this.config.limit-1&&prev==this.config.limit-1)){
        return false
        }
        else return true;
      });
      pager.attachEvent('onafterpagechange', function () {
        self.iteamAdjust();
        self.familyFavViewEvents();
      });
      
      var count = view.dataCount();
      // changed to default pagination due to Onpage change event issues
      new_one = "&nbsp; #count# Results,{common.prev()} &nbsp; Page {common.page()} from #limit# {common.next()}";
      view.define("select", false);
      pager.define("template", new_one);


      if (Agency_num > 0) {   
        var timestamp = new Date().getUTCMilliseconds();          
        view.load("Expctantparentprofile/processors/agencylike_list.php?time="+timestamp, "json", function () {
          self.iteamAdjust();
          self.familyFavViewEvents();
        });
      }
      else {
       document.getElementById('data_containers').style.height="100px"; 
       document.getElementById('error').innerHTML = "No items to display";     
          
      }
   
    }
  },
  familyFavViewEvents: function () {
    var self = this;    
    $('.data_del').on('click', function (e) {
        e.preventDefault();
        var agencyid = $(this).attr('data-value');
        self.delFavAgency(agencyid);
      });
   
  },
  delFavAgency: function (agencyid) {      
    var self = this;
    dhtmlx.confirm({
      //title: "Close",
      type: "confirm-warning",
      text: "Are you sure you want delete it?",
      ok: "Yes",
      cancel: "No",
      callback: function (response) {
        if (response) {
          var poststr = "Agencyid=" + agencyid + "&Birthmotherid=" + BMid;
          dhtmlxAjax.post(self.configuration[self.uid].application_path + "processors/delete_agency.php", poststr, function (loader) {
          view.clearAll();
          var rand = new Date().getTime();    
          view.load("Expctantparentprofile/processors/agencylike_list.php?randnum="+rand, "json", function () {
          self.iteamAdjust();
          self.familyFavViewEvents();
        }); 
           /* view.remove(agencyid);
            self.iteamAdjust();
            self.familyFavViewEvents();*/
          });      
            
      }   
    
      }
      
    });
     
  },
  
  iteamAdjust: function () {
    setTimeout(function () {
      $(".dhx_dataview_item:nth-child(2n)").css({
        'margin-left': 55,
        'margin-right': 55,
        
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
      $('.dhx_dataview_bm_like_item').css('padding', 0);
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
Expctantparentprofile.init(Expctantparentprofile_Model);