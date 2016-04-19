/***********************************************************************
 * Name:    Prashanth A
 * Date:    12/17/2013
 * Purpose: Listing families
 ***********************************************************************/
var Agencyapprovalcomponent = {
    uid: null,
    form: [],
    configuration: [],
    dataStore: [],
    getUrlVars: function () {
        var vars = {};
        var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
            vars[key] = value;
        });

        return vars;
    },

    _form: function (uid, siteurl, agencyurl) {

        var self = this;
        var type = self.getUrlVars()["type"];

        var conf_form = self.model.conf_form.template_profileview;
        var Profile_view = new dhtmlXForm("data_container_pending", conf_form);

        var conf_forms = self.model.conf_form.photo_pending;
        var Pending_approval = new dhtmlXForm("activate", conf_forms);


        if (type == 'video') {
            self.view = new dhtmlXDataView({
                container: Profile_view.getContainer("data_container"),
                //  height: "auto",
                type: {
                    template: "http->" + self.configuration[self.uid].application_path + "templ/video_display.html",

                    width: 150, //width of single item
                    height: 150

                }
            });
        } else if (type == 'photo') {
            self.view = new dhtmlXDataView({
                container: Profile_view.getContainer("data_container"),
                //  height: "auto",
                type: {
                    template: "http->" + self.configuration[self.uid].application_path + "templ/photo_display.html",

                    width: 150, //width of single item
                    height: 150

                }

            });
        } else {

            self.view = new dhtmlXDataView({
                container: Profile_view.getContainer("data_container"),
                //  height: "auto",
                type: {
                    template: "http->" + self.configuration[self.uid].application_path + "templ/blog_display.html",

                    width: 1010, //width of single item
                    height: 100,
                    className: "item-box",

                }
            });
        }
        
       if (type == 'blog') {
        pager = self.view.define("pager", {
            container: "page_here_approval",
            size: 4
        });
       }
       else {           
         pager = self.view.define("pager", {
            container: "page_here_approval",
            size: 18
        });     
       }

        var count = self.view.dataCount();
        new_one = "#count# Results,{common.prev()} &nbsp; Page {common.page()} from #limit# {common.next()}";

        pager.define("template", new_one);

        self.view.define("select", false);     
       
        self.view.load("Agencyapprovalcomponent/processors/approval_list.php?type=" + type+"&uistr="+(new Date()).getTime());
    //  if (self.view.dataCount() > 0) {
                
                $('.dhxform_container.dhx_dataview').html('<div class="containeralign loader"></div>');
        //    }
     
        self.view.attachEvent("onXLS", function () {
            $('.dhxform_container.dhx_dataview').html('<div class="containeralign loader">Loading...</div>');
        });


        
        self.view.attachEvent("onXLE", function () {
         
            if (self.view.dataCount() <= 0) {
             document.getElementById('error').innerHTML = "No pending items to display";
           $( "div" ).removeClass( "containeralign loader" )
              
            }
     
            Pending_approval.attachEvent("onChange", function (id, value, state) {
                if (state == true) {
                    $('input:checkbox').prop('checked', true);
                } else {
                    $('input:checkbox').prop('checked', false);

                }

            });

            Pending_approval.attachEvent("onButtonClick", function (name) {

                if (name == 'approve') {
                    var val = [];
                    $(':checkbox:checked').each(function (i) {
                        val.push($(this).val());

                    });

                } else {
                    window.location.href = self.siteurl + "m/groups/approval_settings/" + agencyurl;
                }
               var  itemcount = val.length;
                        if(type == 'photo'){ 
                       if(itemcount > 1){                  
                       var mes = 'Selected photos are approved.';   
                       }
                       else {
                        var mes = 'Selected photo approved.';   
                       }
                       }
                      if(type == 'video'){ 
                       if(itemcount > 1){                  
                       var mes = 'Selected videos are approved.';   
                       }
                       else {
                        var mes = 'Selected video approved.';   
                       }
                       }
                      if(type == 'blog'){ 
                       if(itemcount > 1){                  
                       var mes = 'Selected journals are approved.';   
                       }
                       else {
                        var mes = 'Selected journal approved.';   
                       }
                       }
                if (itemcount > 0) {
                    var poststr = "Aid=" + val + '&type=' + type;
                    dhtmlxAjax.post(self.configuration[self.uid].application_path + "processors/approve_pending.php", poststr, function (loader) {
                        self.view.clearAll();
                        self.view.load("Agencyapprovalcomponent/processors/approval_list.php?type=" + type+"&uistr="+(new Date()).getTime());
                       var n = JSON.parse(loader.xmlDoc.responseText);
                
                       if (n.status == "success") {
                          dhtmlx.message({
                          text: mes
                         })
                       } 
                        
                    });
                }
            });


            $('.dhx_dataview_item.dhx_dataview_default_item').css('padding-top', 0);
            //Aravind: Image size issue fix
            $('.dhx_dataview_item').css({
                'padding': 0
            });
            setTimeout(function () {
                $(".dhx_dataview_item.dhx_dataview_default_item:nth-child(2n)").css({
                    'margin-left': 10,
                    'margin-right': 10
                });
            }, 100);
            $(window).on('resize', function () {
                setTimeout(function () {
                    $(".dhx_dataview_item.dhx_dataview_default_item:nth-child(2n)").css({
                        'margin-left': 10,
                        'margin-right': 10
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


        setInterval(function () {
            $('.dhx_dataview_item').css({
                'padding': 0
            });
            $(".dhx_dataview_item.dhx_dataview_default_item:nth-child(2n)").css({
                'margin-left': 10,
                'margin-right': 10
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
                    'margin-left': 10,
                    'margin-right': 10
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
        self.siteurl = configuration.siteurl;
        self.agencyurl = configuration.agencyurl;


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


        self._form(self.uid, self.siteurl, agencyurl);



    }

}
Agencyapprovalcomponent.init(Agencyapprovalcomponent_Model);