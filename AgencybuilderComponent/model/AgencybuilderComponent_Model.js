/***********************************************************************
 * Name:    Prashanth A  S
 * Date:    13/12/2013
 * Purpose: Creating a Extra agency builder form
 ***********************************************************************/
function ValidCity(data) {
    if (/^[a-z ]+$/i.test(data) != true) {
                
            return false;
}
    return true;
}
            
function ValidZip(data) {
    if (isNaN(data) || data.length != 5) {
            return false;
}
    return true;
}
function validateEmailaddress(data) {
    //var x = data;// document.forms["myForm"]["email"].value;
    //var atpos = x.indexOf("@");
    //var dotpos = x.lastIndexOf(".");
    //if (atpos< 1 || dotpos<atpos+2 || dotpos+2>=x.length) {
    //   return false;
    //}
    var filter_email = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!filter_email.test(data)) {
        return false;
    }
    return true;
}
function ValidPhoneNumber(data){

     if(data.length != 12) {
            
            return false;
        }return true;
}
function ValidAgencyName(data) {
   //  var name_filter = /^[a-zA-Z0-9&.@, ]{0,30}$/;
    // if (!name_filter.test(data)) {
    //      return false;
   //   }return true;    
 
      if (data.length < 4  || data.length > 100  ) {
        return false;
      }
      return true;  
   
   
}
var AgencybuilderComponent_Model = {

    // Defining layout pattern   
    "conf_layout": {
        "pattern": "1C"
    },
    "conf_window": {
        "image_path": "",
        "left": 510,
        "top": 200,
        "width": 540,
        "height": 280,
        "enableAutoViewport": true,
        "icon": "form.png",
        "icon_dis": "form.png"
    },
    // Creating  agency  profile builder form
    "Agencybuilder": {
        "template_Agencybuilder": [{
                type: "block",
            list: [{
                        type: "block",
                        list: [{
                        type: "label",
                        name: "form_label_1",
                        label: "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_about.png' /> ABOUT US</span><span><a class='tooltip' title-text='ABOUT AGENCY' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>",
                        offsetTop: "35"
                }, {
                        type: "label",
                        label: "WHAT IS YOUR AGENCY&rsquo;S NAME?",
                        name: "form_input_1",
                        labelWidth: 250
                }, {
                                type: "input",
                                name: "agency_name",
                                inputWidth: 220,
                                inputHeight: 25,
                                required: true,	
                              //  disabled: "true",
                                offsetTop: "5",
                                validate: "ValidAgencyName"
                }, {
                        type: "label",
                        label: "WHAT IS YOUR WEBSITE URL?",                        
                        style: "font-weight:normal;",
                        name: "form_input_1",
                        labelWidth: 250
                }, {
                    type: "input",
                                name: "your_url",
                               // disabled: "true",
							   validate: "AgencybuilderComponent.validateURL",
                                inputWidth: 220,
                                inputHeight: 25,
                                offsetTop: "5"
                }, {
                        type: "label",
                        name: "form_label_1",
                        label: "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_video.png' /> DEFAULT VIDEO</span><span><a class='tooltip' title-text='ABOUT AGENCY' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>",
                        offsetTop: "35"
                    },
                    /*
                    {
                        type: "label",
                        name: "form_label_1",
                        label: '<span style="float:left;margin-left:25px; cusrsor:pointer;" class="pink-btn youtubeUploadCh" data-id="" title="Upload videos to your Youtube channel"><i class="fa fa-youtube-play"></i> Upload Files</span>',
                    },
                    */
                    {
                        type: "block", 
                        list:[
                            {
                                type: "label",
                                name: "form_label_1",
                                label: '<span style="float:left;margin-left:25px; cusrsor:pointer;" class="pink-btn youtubeUploadCh" data-id="" title="Upload videos to your Youtube channel"><i class="fa fa-youtube-play"></i> Upload Files</span>',
                            },
                            {type: "newcolumn"},
                            {
                                type: "label",
                                name: "form_label_2",
                                label: '<span style="float:left;margin-left:25px; cusrsor:pointer;" class="pink-btn youtubeDelete" data-id="" title="Delete uploaded video"><i class="fa fa-youtube-play"></i> Delete Files</span>',
                            },
                        ]
                    },
                    				
                    {
                        type: "label",
                        name: "form_label_1",
                        label: '<div id="default_div"><img style="width:450px;" src="http://www.parentfinder.com/templates/tmpl_par/images/NO-VIDEOS_icon.png"/></div>',
                }, {
                        type: "newcolumn"
                    }, {
                        type: "block",
                        list: [{
                                type: "label",
                                name: "form_label_1",
                                label: "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_photos.png' /> PHOTOS</span><span><a class='tooltip' title-text='PHOTOS' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>",
                                offsetTop: "35",
                                offsetLeft: 20
                    }, {
                        type: "upload",
                            name: "Filedata",
                            mode: "html4",          
                            offsetLeft:"1",
                            inputWidth:  300,
                            offsetTop: 2,
                            _swfLogs: "enabled",
                            autoStart: true,
                            url: "AgencybuilderComponent/processors/savePhoto.php",
                            swfPath: "../../plugins/dhtmlx/dhtmlxForm/codebase/ext/uploader.swf",
                            swfUrl: "../../../../../../AgencybuilderComponent/processors/savePhoto.php"
                    }, {
                        type: "block",
                        list: [{
                                type: "label",
                                name: "form_label_1",
                                label: "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'> SOCIAL NETWORKS</span><span><a class='tooltip' title-text='SOCIAL NETWORKS' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>",
                                offsetTop: "2",
                                offsetLeft: 20
                            }, {
                                type: "block",
                            list: [{
                                        type: "radio",
                                        name: "fburl",
                                        label: "<img src='templates/tmpl_par/images/ico_build_fb.png' />",
                                        offsetLeft: "20",
                                        position: "label-top",
                                        value: "facebook"
                                }, {
                                        type: "newcolumn"
                                    }, {
                                        type: "radio",
                                        name: "turl",
                                         label: "<img src='templates/tmpl_par/images/ico_build_tw.png' />",
                                        offsetLeft: "20",
                                        position: "label-top",
                                        value: "twitter"
                                    }, {
                                        type: "newcolumn"
                                }, {
                                        type: "radio",
                                        name: "gurl",
                                        label: "<img src='templates/tmpl_par/images/ico_build_go.png' />",
                                        offsetLeft: "20",
                                        position: "label-top",
                                        value: "google"

                                }, {
                                        type: "newcolumn"
                                }, {
                                        type: "radio",
                                        name: "burl",
                                        label: "<img src='templates/tmpl_par/images/ico_build_bi.png' />",
                                        offsetLeft: "20",
                                        position: "label-top",
                                        value: "blogger"
                                    }, {
                                        type: "newcolumn"
                                    },

                                    {
                                        type: "radio",
                                        name: "purl",
                                        label: "<img src='templates/tmpl_par/images/ico_build_pi.png' />",
                                        offsetLeft: "20",
                                        position: "label-top",
                                        value: "pinerest"
                                    },
                                ]
                        }]
                    }, {
                        type: "block",
                        list: [{
                            type: "label",
                            name: "form_label_1",
                            label: "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'> PASSWORD PROTECT PROFILES</span><span><a class='tooltip' title-text='FOR UNPUBLISHED FAMILIES' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>",
                            offsetTop: "35",
                            offsetLeft: 20
                            },{
                                type: "label",
                                label: "SET PASSWORD HERE",
                                name: "form_input_1",
                                labelWidth: 200,
                                offsetLeft: 30
                            }, {
                                type: "password",
                                name: "unpubPwd",
                                inputWidth: 180,
                                inputHeight: 25,
                                required: true,
                                offsetLeft: 30,
                                //  disabled: "true",
                                offsetTop: "5",                                
                            },{
                                type: "label",
                                label: "CONFIRM PASSWORD",
                                name: "form_input_1",
                                labelWidth: 200,
                                offsetLeft: 30
                            }, {
                                type: "password",
                                name: "confirmunpubPwd",
                                inputWidth: 180,
                                inputHeight: 25,
                                required: true,
                                offsetLeft: 30,                                
                                offsetTop: "5",                                
                        }]
                    }]
                }]
            }, {
                        type: "newcolumn"
                    }, {
                        type: "block",
                        list: [{
                    type: "settings"
                }, {
                            type: "block",
                            list: [{
                                type: "label",
                                name: "form_label_1",
                                label: "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_about_child.png' /> OUR CONTACT INFO</span><span><a class='tooltip' title-text='CONTACT DETAILS' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>",
                                offsetTop: "35"
                            }]
                }, {
                            type: "input",
                            name: "agency_phonenumber",
                            inputWidth: "220",
                            inputHeight: 25,
                            offsetTop: "5",
                            label: "PHONE NUMBER",
                    position: "label-top",
                            validate: "ValidPhoneNumber"
                }, {
                            type: "input",
                            name: "agency_email",
                    label: "",
                    required: "true",
                            validate: "validateEmailaddress",
                            inputWidth: "220",
                            inputHeight: 25,
                            offsetTop: "5",
                            label: "EMAIL ADDRESS",
                    position: "label-top"
                }, {
                            type: "input",
                            name: "city",
                            label: "",
                            inputWidth: "220",
                            inputHeight: 25,
                            offsetTop: "5",
                            validate: "ValidCity",
                            label: "CITY",
                    position: "label-top"
                }, {
                            type: "select",
                            name: "state",
                            label: "",
                            inputWidth: "220",
                            inputHeight: 30,
                            offsetTop: "5",
                            label: "STATE",
                    position: "label-top"
                }, {
                            type: "select",
                            name: "region",
                            label: "",
                            inputWidth: "220",
                            inputHeight: 30,
                            offsetTop: "5",
                            label: "REGION",
                    position: "label-top"
                }, {
                            type: "input",
                            name: "zip",
                            label: "",
                            validate: "ValidZip",
                            inputWidth: "220",
                            inputHeight: 25,
                            offsetTop: "5",
                            label: "ZIP",
                    position: "label-top"
                }]
            }]
        }, {
                            type: "block",
                            list: [{
                                type: "label",
                                label: "DESCRIBE YOUR AGENCY?",
                                style: "font-weight:normal;",
                                name: "form_input_1",
                                labelWidth: 250
                            }]
        }, {
                        type: "block",
            list: [{
                                type: "tinyMCE",
                                name: "agency_desc",
                                rows: 3,
                                inputWidth: 330,
                                width: 330,
                                inputHeight: 150                               
            }]
        }, {
                type: "block",
                list: [{
                    type: "button",
                    value: "<img src='templates/tmpl_par/images/agency_save.png' />",
                    style: "font-weight:normal;",
                    name: "submitagencydetails",
                    labelWidth: 215,
                    offsetLeft: "673",
                    offsetTop: "20"
                }]
        }]
    },

    "agencybuilder_join": { //Templates for the social sites

        "template_Agencybuilder_join": [{
                type: "block",
                list: [{
                    type: "input",
                    name: "facebookurl",
                    inputWidth: 200,
                    inputHeight: 28,
                    offsetTop: "25",
                    offsetLeft: "10",
                    label: "Facebook url:"

                }]
            }, {
                type: "block",
                list: [{
                    type: "input",
                    name: "twitterurl",
                    inputWidth: 200,
                    inputHeight: 28,
                    offsetTop: "25",
                    offsetLeft: "10",
                    label: "Twitter url:"
                }]
        }, {
                type: "block",
                list: [{
                    type: "input",
                    name: "googleurl",
                    inputWidth: 200,
                    inputHeight: 28,
                    offsetTop: "25",
                    offsetLeft: "10",
                    label: "Google+ url:"
                }]
        }, {
                type: "block",
                list: [{
                    type: "input",
                    name: "bloggerurl",
                    inputWidth: 200,
                    inputHeight: 28,
                    offsetTop: "25",
                    offsetLeft: "10",
                    label: "Blogger url:"
                }]
            }, {
                type: "block",
                list: [{
                    type: "input",
                    name: "pineresturl",
                    inputWidth: 200,
                    inputHeight: 28,
                    offsetTop: "25",
                    offsetLeft: "10",
                    label: "Pinerest url:"
                }]
            }, {
                type: "newcolumn"
            }, {

                type: "button",
                value: "SUBMIT",
                style: "font-weight:normal;",
                name: "socialsubmit",
                offsetLeft: "5",
                // labelWidth: 215,=
                //  offsetLeft: "260",
                offsetTop: "24"
        }]
            }
};