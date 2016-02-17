/***********************************************************************
 * Name:    Prashanth A
 * Date:    12/17/2013
 * Purpose: Listing families
 ***********************************************************************/
var Expctantparentsearchfamilies = {
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
          self.dataStore["Profiles_value"] = json.Profiles_value;
		  self.dataStore["children_value"] = json.children_value;
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
    var uid = self.uid
    $('.printdefaultpdf_print').removeAttr('id');
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
                    dhtmlx.confirm({
          type: "confirm",
          text: "There is no printed profile available.Would you like to send the family a request ?",
          ok: "Yes",
          cancel: "No",
          callback: function(result) {
          if (result == true) {
              
           var poststr = "Profileid=" + id;            
            dhtmlxAjax.post(self.configuration[self.uid].application_path + "processors/pdf_profile_request.php", poststr, function(loader) {
                var json = JSON.parse(loader.xmlDoc.responseText);
                if (json.status == "success") {
                  dhtmlx.alert({
                    text: "Your request has been sent to the family please check back soon"
                  });
          
                } else {
                  dhtmlx.message({
                    type: "error",
                    text: json.message
                  })
                }
              });
                  
          }
          else {
                  
                  
            }

          }
        });
        }
      } else {
                    dhtmlx.confirm({
          type: "confirm",
          text: "There is no printed profile available.Would you like to send the family a request ?",
          ok: "Yes",
          cancel: "No",
          callback: function(result) {
          if (result == true) {
              
           var poststr = "Profileid=" + id;            
            dhtmlxAjax.post(self.configuration[self.uid].application_path + "processors/pdf_profile_request.php", poststr, function(loader) {
                var json = JSON.parse(loader.xmlDoc.responseText);
                if (json.status == "success") {
                  dhtmlx.alert({
                    text: "Your request has been sent to the family please check back soon"
                  });
          
                } else {
                  dhtmlx.message({
                    type: "error",
                    text: json.message
                  })
                }
              });
                  
          }
          else {
                  
                  
            }

          }
        });
      }
    });
  },
  likecomponentcall : function(row_id){
          LikeComp_controller.start({
          uid: (new Date()).getTime(),
          like: row_id,
          callback: null,
          extra: {
            test: ''
          }
        });
  },
  _form: function (uid) {
    var self = this;
    var json = self.dataStore;
    var Profiles = json.Profiles_value.rows;
    var conf_form = self.model.conf_form.template_profileview;
    var Profile_view = new dhtmlXForm("data_container_test", conf_form);

    //  var conf_form1 = self.model.conf_form.template_profileview1;
    //  var Profile_view1 = new dhtmlXForm("searchoptions", conf_form1);

    var menu = new dhtmlXMenuObject("menuObj");
    //document.getElementById('menuObj').style.width="600px";
    //$("#page_here").removeClass("page_here");
   // $('#page_here').addClass('green');

    if (document.getElementById("data_search")) {
        var conf_search = self.model.searchform.template_search;
        var myForm_search = new dhtmlXForm("data_search", conf_search);
       }
    
    
    
    menu.setIconsPath("../common/imgs/");

    menu.addNewSibling(null, "Sortby", "<div class='green search-menu'>SORT BY</div>", false);
    menu.addNewChild("Sortby", 0, "Recently Joined", "Recently Joined", false);


    menu.addNewSibling(null, "Region", "<div class='teal search-menu'>REGION</div>", false);
    menu.addNewChild("Region", 0, "Non US", "Non US", false);
    menu.addNewChild("Region", 1, "North-central", "North-central", false);
    menu.addNewChild("Region", 2, "Northeast", "Northeast", false);
    menu.addNewChild("Region", 3, "Northwest", "Northwest", false);
    menu.addNewChild("Region", 4, "South-central", "South-central", false);
    menu.addNewChild("Region", 5, "Southeast", "Southeast", false);
    menu.addNewChild("Region", 6, "Southwest", "Southwest", false);


    menu.addNewSibling(null, "Religion", "<div class='teal search-menu'>RELIGION</div>", false);
    menu.addNewChild("Religion", 0, "Anglican", "Anglican", false);
    menu.addNewChild("Religion", 1, "Bahai", "Bahai", false);
    menu.addNewChild("Religion", 2, "Baptist", "Baptist", false);
    menu.addNewChild("Religion", 3, "Buddhist", "Buddhist", false);
    menu.addNewChild("Religion", 4, "Catholic", "Catholic", false);
    menu.addNewChild("Religion", 5, "Christian", "Christian", false);
    menu.addNewChild("Religion", 6, "Church of Christ", "Church of Christ", false);
    menu.addNewChild("Religion", 7, "Episcopal", "Episcopal", false);
    menu.addNewChild("Religion", 8, "Hindu", "Hindu", false);
    menu.addNewChild("Religion", 9, "Jewish", "Jewish", false);
    menu.addNewChild("Religion", 10, "Lutheran", "Lutheran", false);
    menu.addNewChild("Religion", 11, "Methodist", "Methodist", false);
    menu.addNewChild("Religion", 12, "Non-denominational", "Non-denominational", false);
    menu.addNewChild("Religion", 13, "None", "None", false);
    menu.addNewChild("Religion", 14, "Other", "Other", false);
    menu.addNewChild("Religion", 15, "Presbyterian", "Presbyterian", false);
    menu.addNewChild("Religion", 16, "Protestant", "Protestant", false);
    menu.addNewChild("Religion", 17, "Unitarian", "Unitarian", false);


    menu.addNewSibling(null, "ethnicity", "<div class='teal search-menu'>ETHNICITY</div>", false);
    menu.addNewChild("ethnicity", 0, "Middle Eastern", "Middle Eastern", false);
    menu.addNewChild("ethnicity", 1, "Asian", "Asian", false);
    menu.addNewChild("ethnicity", 2, "African American", "African American", false);
    menu.addNewChild("ethnicity", 3, "African American/Asian", "African American/Asian", false);
    menu.addNewChild("ethnicity", 4, "Asian/Hispanic", "Asian/Hispanic", false);
    menu.addNewChild("ethnicity", 5, "Caucasian", "Caucasian", false);
    menu.addNewChild("ethnicity", 6, "Caucasian/Asian", "Caucasian/Asian", false);
    menu.addNewChild("ethnicity", 7, "Caucasian/African American", "Caucasian/African American", false);
    menu.addNewChild("ethnicity", 9, "Caucasian/Hispanic", "Caucasian/Hispanic", false);
    menu.addNewChild("ethnicity", 10, "European", "European", false);
    menu.addNewChild("ethnicity", 11, "Caucasian/Native American", "Caucasian/Native American", false);
    menu.addNewChild("ethnicity", 12, "Eastern European/Slavic/Russian", "Eastern European/Slavic/Russian", false);
    menu.addNewChild("ethnicity", 13, "Hispanic/African American", "Hispanic/African American", false);
    menu.addNewChild("ethnicity", 14, "Hispanic or South/Central American", "Hispanic or South/Central American", false);
    menu.addNewChild("ethnicity", 15, "jewish", "Jewish", false);
    menu.addNewChild("ethnicity", 16, "Mediterranean", "Mediterranean", false);
    menu.addNewChild("ethnicity", 17, "Multi-Racial", "Multi-Racial", false);
    menu.addNewChild("ethnicity", 18, "Native American (American Indian)", "Native American (American Indian)", false);
    menu.addNewChild("ethnicity", 19, "Pacific Islander", "Pacific Islander", false);
    menu.addNewChild("ethnicity", 20, "other", "Other", false);


    menu.addNewSibling(null, "Familysize", "<div class='teal search-menu'>FAMILY SIZE</div>", false);
    /*menu.addNewChild("Familysize", 0, "1", "1 Children", false);
    menu.addNewChild("Familysize", 1, "2", "2 Childrens", false);
    menu.addNewChild("Familysize", 2, "3+", "3 Childrens", false);*/
    profiles_children       = self.dataStore["children_value"];
    for (var cnt in profiles_children) { 
      var childrensid   = profiles_children[cnt].id;
      var childrensval  = profiles_children[cnt].data[0];
      //console.log(childrensid);
      //console.log(childrensval);        
      menu.addNewChild("Familysize", cnt, childrensid, childrensval, false);
    }

    menu.attachEvent("onClick", menuClick);

    if ((typeof self.loaded_from === 'undefined') || (self.loaded_from === 0) || self.loaded_from != 'badge')
        window_target = 'target=_self';
    else
        window_target = 'target=_blank';

    self.view = new dhtmlXDataView({
      container: Profile_view.getContainer("data_container"),
      //  height: "auto",
      type: {
        template: "<div class='imagecontaier'>{obj.profile_image}</div>\n\
<div class='uniBottomBar_family'>{obj.profile_firstname} <input type='image' alt='Submit' id='data'  src='templates/tmpl_par/images/ico_like_search.png' value='{obj.profile_id}' onClick='Expctantparentsearchfamilies.likecomponentcall({obj.profile_id});' class='inputbuttonalign'></div><div class='linkicons'>\n\
\n\
<div class='imaegicons'><a "+window_target+" href=extra_profile_view_17.php?id={obj.profile_id}><span>\n\
<img src='templates/tmpl_par/images/ico_fam_about.png' ></span></a></div>\n\
<div class='imaegicons'><a "+window_target+" href=extra_profile_view_18.php?id={obj.profile_id}><span>\n\
<img src='templates/tmpl_par/images/ico_fam_home.png'></span></a></div>\n\
<div class='imaegicons'><a "+window_target+" href=extra_profile_view_19.php?id={obj.profile_id}>\n\
<span><img src='templates/tmpl_par/images/ico_fam_letters.png' ></span></a></div>\n\
<div class='imaegicons'><a "+window_target+" href=ContactAgency.php?ID={obj.profile_id}><span>\n\
<img src='templates/tmpl_par/images/ico_fam_cont.png' ></span></a></div>\n\
<div class='imaegicons'><a "+window_target+" href='m/videos/albums/browse/owner/{obj.profile_nickname}'><span >\n\
<img src='templates/tmpl_par/images/ico_fam_video.png'></span></a></div>\n\
<div class='imaegicons'><a "+window_target+" href='javascript:void(0);' class='printdefaultpdf_print'><span>\n\
<img src='templates/tmpl_par/images/ico_fam_print.png' onclick='Expctantparentsearchfamilies.printpdf({obj.profile_id})' ></span></a></div></div>\n\
<div class='fieldmarginTop'><span class='fieldcolor'>AGE:</span> \n\
<span class='fieldvaluecolor'>{obj.profile_age}</span></div><div class='fieldmargin'><span class='fieldcolor'>STATE:</span> \n\
<span class='fieldvaluecolor'>{obj.profile_state}</span></div><div class='fieldmargin'><span  class='fieldcolor' >WAITING:</span> \n\
<span class='fieldvaluecolor'>{obj.profile_waiting}</span></div>\n\
<div class='fieldmargin'><span  class='fieldcolor' >NUMBER OF KIDS IN FAMILY:</span> \n\
<span class='fieldvaluecolor'>{obj.profile_noofchilds}</span></div>\n\
<div class='fieldmargin'><span  class='fieldcolor' >{obj.profile_fieldlabel}</span> \n\
<span class='fieldvaluecolor'>{obj.profile_children_type}</span></div>\n\
<div class='fieldmargin'><span class='fieldcolor' >FAITH: </span><span class='fieldvaluecolor'>{obj.profile_faith.replace(\"Not Specified,\",'').replace(/,/g,', ')}</span></div>\n\
<div>&nbsp;</div><div class='fieldhead'>OUR CHILD</div><div><span class='fieldcolor'>ETHNICITY: </span><span class='fieldvaluecolor'>{obj.profile_childethnicity}</span></div>\n\
<div class='fieldmargin'><span class='fieldcolor'>AGE: </span><span class='fieldvaluecolor'>{obj.profile_childage}</span>\n\
</div>\n\
<div class='fieldmargin'><span class='fieldcolor'>ADOPTION TYPE: </span><span class='fieldvaluecolor'>{obj.profile_adoptiontype}</span></div> ",

        width: 300, //width of single item
        height: 570,
        className: "item-box",
        //height of single item
        // margin: 10,
        //border: 0
      }


    });

    pager = self.view.define("pager", {
      container: "page_here",
      size: self.noofitmes
    });

    var count = self.view.dataCount();
    new_one = "&nbsp;#count# Results,{common.prev()} &nbsp;Page {common.page()} from #limit# {common.next()}";

    pager.define("template", new_one);

    self.view.define("select", false);


    //                var agencyid = $(this).val();
    //
    //
    //                if (Profiles != 0) {
    //                    var poststr = "Agencyid=" + agencyid + "&Birthmotherid=obj.profile_id";
    //                    dhtmlxAjax.post(self.configuration[uid].application_path + "processors/insert_like_user.php", poststr, function (loader) {
    //
    //                    });
    //
    //                } else {
    //                    join_window = new dhtmlXWindows();
    //                    popwidth = 500;
    //                    popheight = 200;
    //                    var conf_form_alert = self.model.conf_form.view_alert_temp;
    //                    win_join = join_window.createWindow("w1", "", "", popwidth, popheight);
    //                    //  win_join.window[id].close();
    //                    //    win_join.close();     
    //                    win_join.button("park").hide();
    //                    //  join_window.park();
    //                    win_join.button("minmax1").hide();
    //                    win_join.button("minmax2").hide();
    //                    win_join.setText("");
    //                    win_join.centerOnScreen();
    //                    win_join.setModal(true);
    //                    view_alert = win_join.attachForm(conf_form_alert);
    //                    //  view_alert = win_join.attachForm("test");
    //                    view_alert.attachEvent("onButtonClick", function (name, command) {
    //
    //                        if (name == 'createaccount') {
    //                            JoinComponent.start({
    //                                uid: (new Date()).getTime(),
    //                                application_path: siteurl + "JoinComponent/",
    //                                dhtmlx_codebase_path: siteurl + "/plugins/dhtmlx/"
    //                            });
    //                            //win_join.button("close");
    //                            win_join.close();
    //                        }
    //                        if (name == 'login') {
    //                            LoginComponent.start({
    //                                uid: (new Date()).getTime(),
    //                                application_path: siteurl + "LoginComponent/",
    //                                dhtmlx_codebase_path: siteurl + "plugins/dhtmlx/"
    //                            });
    //                            win_join.close();
    //                        }
    //                        if (name == 'cancel') {
    //                            win_join.close();
    //                        }
    //
    //                    });
    //
    //                }

    if (document.getElementById("data_search")) {  

       myForm_search.attachEvent("onButtonClick", function (name, command) {
        var searchitem = myForm_search.getItemValue("search");
        searchitemvalue = searchitem.trim();
        self.view.clearAll();
        $('.dhxform_container.dhx_dataview').html('<div class="containeralign loader">Loading...</div>');
        parentId = '';
        self.view.load(siteurl +"Expctantparentsearchfamilies/processors/familylike_list.php?loadFrom="+self.loaded_from+"&agencyId="+self.load_agencyid+"&adoptionAgency="+self.adoption_agencyid+"&searchFilter="+searchitemvalue);
      });



      myForm_search.attachEvent("onEnter", function (inp, ev, id, value) {
        var searchitem = myForm_search.getItemValue("search");
        searchitemvalue = searchitem.trim();
        self.view.clearAll();
        $('.dhxform_container.dhx_dataview').html('<div class="containeralign loader">Loading...</div>');
        parentId = '';
        self.view.load(siteurl +"Expctantparentsearchfamilies/processors/familylike_list.php?loadFrom="+self.loaded_from+"&agencyId="+self.load_agencyid+"&adoptionAgency="+self.adoption_agencyid+"&searchFilter="+searchitemvalue);
      });



      myForm_search.attachEvent("onfocus", function () {
        var searchitem = myForm_search.getItemValue("search");
          if (searchitem == 'Search by name') {
               myForm_search.setItemValue("search", '');
            }
          });   

          myForm_search.attachEvent("onblur", function () {
          var searchitem = myForm_search.getItemValue("search");       
          if (searchitem == '') {
               myForm_search.setItemValue("search", 'Search by name');
           }
          });

       }

    function menuClick(id) {
      //self.view.filter("#profile_id#","6030");
      self.view.clearAll();
      myForm_search.setItemValue("search",'');
      $('.dhxform_container.dhx_dataview').html('<div class="containeralign loader">Loading...</div>');
      var parentId = menu.getParentId(id);
      self.view.load(siteurl +"Expctantparentsearchfamilies/processors/familylike_list.php?sortvalue=" + id + "&type=" + parentId+"&loadFrom="+self.loaded_from+"&agencyId="+self.load_agencyid+"&adoptionAgency="+self.adoption_agencyid);
      //alert('te');
      /*
      var parentId = menu.getParentId(id);

      if (id) {
        var view = new dhtmlXDataView({
          container: Profile_view.getContainer("data_container"),
          //  height: "auto",
          type: {
            template: "<div class='imagecontaier'>{obj.profile_image}</div>\n\
<div class='uniBottomBar_family'>{obj.profile_firstname}</div><div class='linkicons'>\n\
<input type='image' alt='Submit' id='data'  src='templates/tmpl_par/images/ico_like_search.png' value='{obj.profile_id}' class='inputbuttonalign'>\n\
<div ><a href=extra_profile_view_17.php?id={obj.profile_id}><span>\n\
<img src='templates/tmpl_par/images/ico_fam_about.png' class='imaegiconstop'></span></a></div>\n\
<div class='imaegicons'><a href=extra_profile_view_18.php?id={obj.profile_id}><span>\n\
<img src='templates/tmpl_par/images/ico_fam_home.png' ></span></a></div><div class='imaegicons'>\n\
<a href=extra_profile_view_19.php?id={obj.profile_id}><span><img src='templates/tmpl_par/images/ico_fam_letters.png' ></span></a></div>\n\
<div class='imaegicons'><a href=ContactAgency.php?ID={obj.profile_id}><span>\n\
<img src='templates/tmpl_par/images/ico_fam_cont.png' ></span></a></div><div class='imaegicons'>\n\
<a href='m/videos/albums/browse/owner/{obj.profile_nickname}'><span ><img src='templates/tmpl_par/images/ico_fam_video.png'></span></a></div>\n\
<div class='imaegicons'><a href='javascript:void(0);' class='printdefaultpdf_print'><span>\n\
<img src='templates/tmpl_par/images/ico_fam_print.png' onclick='Expctantparentsearchfamilies.printpdf({obj.profile_id})' ></span></a></div></div>\n\
<div class='fieldmarginTop'><span  class='fieldcolor' >AGE:</span> \n\
<span class='fieldvaluecolor'>{obj.profile_age}</span></div><div class='fieldmargin'><span  class='fieldcolor' >STATE:</span> \n\
<span class='fieldvaluecolor'>{obj.profile_state}</span></div><div class='fieldmargin'><span class='fieldcolor'  >WAITING:</span> \n\
<span class='fieldvaluecolor'>{obj.profile_waiting}</span></div>\n\
<div class='fieldmargin'><span  class='fieldcolor' >CHILDREN:</span> \n\
<span class='fieldvaluecolor'>{obj.profile_noofchilds}</span></div>\n\
<div class='fieldmargin'><span class='fieldcolor' >FAITH: </span><span class='fieldvaluecolor'>{obj.profile_faith}</span></div>\n\
<div>&nbsp;</div><div class='fieldhead'>OUR CHILD</div><div class='fieldmargin'>\n\
<span class='fieldcolor'>ETHNICITY: </span><span class='fieldvaluecolor'>{obj.profile_childethnicity}</span></div>\n\
<div class='fieldmargin'><span class='fieldcolor'>AGE: </span><span class='fieldvaluecolor'>{obj.profile_childage}</span>\n\
</div>\n\
<div class='fieldmargin'><span class='fieldcolor'>ADOPTION TYPE: </span><span class='fieldvaluecolor'>{obj.profile_adoptiontype}</span></div> ",

            width: 300, //width of single item
            height: 550,
            padding: 21
            //height of single item
            // margin: 10,
            //  border: 0,
          }

        });
        var count = view.dataCount();


        pager = view.define("pager", {
          container: "page_here",
          size: 9
        });



        new_one = "#count# Results,{common.prev()} &nbsp;Page {common.page()} from #limit# {common.next()}";

        pager.define("template", new_one);

        view.define("select", false);

        view.load("Expctantparentsearchfamilies/processors/familylike_list.php?sortvalue=" + id + "&type=" + parentId,  function() {
          console.log('hi');
          $('.dhx_dataview_item.dhx_dataview_default_item').css('padding-top', 0);
          console.log($('.dhx_dataview_item.dhx_dataview_default_item'));
          //Aravind: Image size issue fix
          $('.imagecontaier img').on('load', function() {
            $(this).css({
              'width': 300,
              'height': 'auto',
              'margin': 0
            });

            $(window).on('resize', function() {
              $('.imagecontaier img').css({
                'width': 300,
                'max-height': 230,
                'height': 'auto',
                'margin': 0
              });
              setTimeout(function() {
                $('.dhx_dataview_item.dhx_dataview_default_item').css('padding-top', 0);
              }, 10);
            });
          });
          //Aravind:End Image size issue fix
        });

      }
      var poststr = "sortvalue=" + id + '&type=' + parentId;

      dhtmlxAjax.post(siteurl + "Expctantparentsearchfamilies/processors/list_family_count.php", poststr, function(loader) {
        var returnval = JSON.parse(loader.xmlDoc.responseText);

        if (returnval.family_count <= 0) {
          document.getElementById("error").innerHTML = returnval.status;
        } else {
          document.getElementById("error").innerHTML = '';

        }

      });*/


    }

    $('.dhxform_container.dhx_dataview').html('<div class="containeralign loader">Loading...</div>');

    self.view.load(siteurl +"Expctantparentsearchfamilies/processors/familylike_list.php?loadFrom="+self.loaded_from+"&agencyId="+self.load_agencyid+"&adoptionAgency="+self.adoption_agencyid );



    self.view.attachEvent("onXLS", function () {
      $('.dhxform_container.dhx_dataview').html('<div class="containeralign loader">Loading...</div>');
    });
    self.view.attachEvent("onXLE", function () {

     //For displaying message if there is results 
    
      var count = self.view.dataCount();
  
      if(count <= 0)
       {            
       $('#error').css({"margin-top":"100px"});          
       document.getElementById('error').innerHTML = "No items to display"; 
       }
       else {           
       $('#error').css({"margin-top":"0px"});
       document.getElementById('error').innerHTML = "";    
       }
  
    // For displaying message if there is results 

      $('.dhx_dataview_item.dhx_dataview_default_item').css('padding-top', 0);
      //Aravind: Image size issue fix
      $('.dhx_dataview_item').css({
        'padding': 0
      });
      setTimeout(function () {
        $(".dhx_dataview_item.dhx_dataview_default_item:nth-child(2n)").css({
          'margin-left': 55,
          'margin-right': 55
        });
      }, 100);
      $(window).on('resize', function () {
        setTimeout(function () {
          $(".dhx_dataview_item.dhx_dataview_default_item:nth-child(2n)").css({
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
        $('.dhx_dataview_item.dhx_dataview_default_item').css('padding-top', 0);
        $(window).on('resize', function () {
          $('.imagecontaier img').css({
            'width': 300,
            'max-height': 230,
            'height': 'auto',
            'margin': 0
          });
          $('.dhx_dataview_item.dhx_dataview_default_item').css('padding-top', 0);
        });
      });
      //Aravind:End Image size issue fix
    });

    //
    setInterval(function () {
      $('.dhx_dataview_item').css({
        'padding': 0
      });
      $(".dhx_dataview_item.dhx_dataview_default_item:nth-child(2n)").css({
        'margin-left': 55,
        'margin-right': 55
      });
      $('.dhx_dataview_item.dhx_dataview_default_item').css('padding-top', 0);
      $('.imagecontaier img').css({
        'width': 300,
        'height': 'auto',
        'margin': 0
      });
    }, 1000);
    pager.attachEvent('onafterpagechange', function () {
      setTimeout(function () {
        $('.dhx_dataview_item').css({
          'padding': 0
        });
        $(".dhx_dataview_item.dhx_dataview_default_item:nth-child(2n)").css({
          'margin-left': 55,
          'margin-right': 55
        });
      }, 1000);

      $('.dhx_dataview_item.dhx_dataview_default_item').css('padding-top', 0);
      $('.imagecontaier img').on('load', function () {
        $(this).css({
          'width': 300,
          'height': 'auto',
          'margin': 0
        });
      });
    });
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
    
    self.loaded_from    = (configuration.loaded_from)?(configuration.loaded_from):'';
    self.load_rows      = (configuration.load_rows)?(configuration.load_rows):3;
    self.load_columns   = (configuration.load_columns)?(configuration.load_columns):3;
    self.load_agencyid  = (configuration.load_agencyid)?(configuration.load_agencyid):'';
    self.adoption_agencyid  = (configuration.adoption_agency)?(configuration.adoption_agency):'';
    
    //alert(self.loaded_from+" "+self.load_rows+" "+self.load_columns +" "+self.load_agencyid);
    switch(parseInt(self.load_rows))
    {
        case 1 : 
            self.contHeght  = 633;            
            break;
        case 2 : 
            self.contHeght  = 1200;            
            break;
        case 3 : 
            self.contHeght  = 1830;            
            break;
    }
    switch(parseInt(self.load_columns))
    {
        case 1 : 
            self.contWidth  = 336;
            self.menuWidth  = "336px";
            self.pageWidth  = "221px";
            self.searchtab  = "336px";
            self.FloatValue = 'left';
            self.TopValue = '5px';
            self.syswidth = '350px';
            break;
        case 2 : 
            self.contWidth  = 710;
            self.menuWidth  = "675px";
            self.pageWidth  = "221px";
            self.searchtab  = "675px";
            self.FloatValue = 'left';
            self.TopValue = '5px';
            self.syswidth = '700px';
            break;
        case 3 : 
            self.contWidth  = 1010;
            self.menuWidth  = "813px";
            self.pageWidth  = "221px";
            self.searchtab  = "1010px";
            self.FloatValue = 'right';
            break;
    }    
    
    self.model.conf_form.template_profileview[0].inputWidth    = self.contWidth;
    self.model.conf_form.template_profileview[0].inputHeight   = self.contHeght;
    self.model.conf_form.template_profileview1[0].width        = self.contWidth;
    self.noofitmes                                             = parseInt(self.load_rows) * parseInt(self.load_columns);
    document.getElementById('menuObj').style.width             = self.menuWidth;
    document.getElementById('page_here').style.width           = self.pageWidth;
    document.getElementById('searchmenutab').style.width       = self.searchtab;
   
    document.getElementById('page_here').style.cssFloat        = self.FloatValue;
    document.getElementById("page_here").style.marginTop = self.TopValue;
        
    var cols = document.getElementsByClassName('sys_main_content');
          for(i=0; i<cols.length; i++) {        
            cols[i].style.width = self.syswidth;       
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
Expctantparentsearchfamilies.init(Expctantparentsearchfamilies_Model);