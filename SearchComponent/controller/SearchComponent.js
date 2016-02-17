/***********************************************************************
 * Name:    Prashanth A
 * Date:    11/11/2013
 * Purpose: Displying,filtering users and navigating to their profile
 ***********************************************************************/
var SearchComponent = {
  uid: null,
  form: [],
  configuration: [],
  _form: function (uid) {
    var self = this;

    var conf_form = self.model.conf_form.template;

    // Attaching the dhtmlx container to  div  
    var myForm = new dhtmlXForm("data_container", conf_form);
    document.getElementById('searchlink').href = 'viewourfamilies.php';
    // Attaching the search field to t div      
    //  var conf_search = self.model.conf_form.template_search;

    //  var myForm_search = new dhtmlXForm("data_search", conf_search);

    //   myForm_search.attachEvent("onKeyUp", function (id, ind) {

    //      var searchitem = myForm_search.getItemValue("search");
    //      searchitemvalue = searchitem.trim();
    //      if (searchitemvalue) { // the height of container when we search
    //         var ContainerHeight = 633;
    //     } else { // the height of container when we search field is empty
    //         var ContainerHeight = "auto";
    //     }
    //Filtering users
    //    var view = new dhtmlXDataView({
    //    container: myForm.getContainer("data_container"),
    //        height: ContainerHeight,
    //        type: {
    //        template: "{obj.response}{obj.data}<div style='text-align:center;width:160px;'>{obj.data_CFname}</div> ",
    //        width: 200, //width of single item
    //        height: 190 //height of single item
    //     }
    //  });
    //  if (searchitemvalue) {
    //      view.load("SearchComponent/processors/featured_users.php?searchvalue=" + searchitemvalue, "json");
    //  } else {
    //      view.load("SearchComponent/processors/featured_users.php", "json");
    //  }
    //     href='extra_profile_view_24.php'; 
    // });

    //Disaplying users
    var view = new dhtmlXDataView({
      container: myForm.getContainer("data_container"),
      height: "600",
      type: {

        //template: "{obj.data}<div style='text-align:center;width:160px;color: #77787A;font-weight: bold;'>{obj.data_CFname}</div> ",
        template: "{obj.data}",
        width: 180, //width of single item
        height: 140, //height of single item
        marginBottom: 10,
        padding: 3
      }

    });
    view.define("select", false);
    view.load("SearchComponent/processors/featured_users.php", "json", function () {
      $('.dhx_dataview_item').css({
        "margin-bottom": 10
      });
      $('.dhxform_container.dhx_dataview').css("overflow", "hidden");
      setTimeout(function () {
        $('.dhx_dataview_item').css({
          "margin-bottom": 10
        });
      }, 100);
      $(window).on('resize', function () {
        setTimeout(function () {
          $('.dhx_dataview_item').css({
            "margin-bottom": 10
          });
        }, 100);
      });
    });

    //   myForm_search.attachEvent("onfocus", function () {
    //    var searchitem = myForm_search.getItemValue("search");
    //    if (searchitem == 'Search Our Families') {
    //       myForm_search.setItemValue("search", '');
    //    }
    //  });

    //alert(document.getElementById('searchlink'));
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
    self._form(self.uid)

  }

}
SearchComponent.init(SearchComponent_Model);