/***********************************************************************
 * Name:    Prashanth A
 * Date:    11/11/2013
 * Purpose: Creating a family profile builder form
 ***********************************************************************/
//custom validations

//custom validations

//function lessThan150(data) {
 // if (data < 150) {
 //   return true;
 // } else {
  //  dhtmlx.message({
  //    type: "alert-error",
  //    text: "You must enter valid Age..."
  //  });
  //  return false;
 // }
//}

function ValidAge(data) {
      
  if (!isNaN(data)) {
    return true;
  } else {
    dhtmlx.message({
      type: "alert-error",
      text: "You must enter valid Year..."
    });
    return false;
  }
}


function ValidNumber(data) {
  if (!isNaN(data)) {
    return true;
  } else {
    dhtmlx.message({
      type: "alert-error",
      text: "\"How many children do you have?\"<br>should be a number"
    });
    return false;
  }
}
function ValidOccupation(data) {

 var name_filter = /^[a-zA-Z ]{0,50}$/;
 if (!name_filter.test(data)) {
   return false;
 }
 return true;    
  }
  
 function Validprofilenumber(data){
	 var profile_filter = /^[0-9]{2}[\_][0-9]{4}$/;
	 if (!profile_filter.test(data)) {
   return false;
 }
 return true;
 }
function validName(data) {
  var r = /^[a-z0-9]+$/i;
  if (r.test(data)) {
    return true;
  } else {
    dhtmlx.message({
      type: "alert-error",
      text: "You must enter name..."
    });
    return false;
  }
  alert("hi");
}


dhtmlx.image_path = 'plugins/dhtmlx/imgs/';
var ProfilebuilderComponent_Model = {
  // Defining layout pattern   
  "conf_layout": {
    "pattern": "1C"
  },
  "conf_window": {
    "image_path": "",
    "width": 540,
    "height": 280,
    "enableAutoViewport": true,
    "icon": "form.png",
    "icon_dis": "form.png"
  },
  // Creating family profile builder form
  "conf_form": {
    "template_profilebuilder": [{
      type: "settings",
      position: "label-top",
      labelWidth: 240,
      inputWidth: 240
    }, {
      type: "block",
      name: "form_block_8",
      list: [{
        type: "block",
        name: "aboutus_block",
        list: [{
            type: "settings",
            inputHeight: 28
          }, {
            type: "label",
            className: "lbl",
            name: "aboutcouple",
            label: "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_about.png' /> ABOUT US</span><span><a class='tooltip' title-text='ABOUT US' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>",
          }, 
        
        {type: "block", list:[
        {type: "label", name: "person1label",label: "<span style='font-size:14px;'>PERSON1</span>",labelWidth: 118},
       
        {type: "newcolumn"},
        {type: "label", name: "person2label",label: "<span style='font-size:14px;'>PERSON2</span>",labelWidth: 110},
      
         ]},
       
          {
            type: "label",
            className: "lbl",
            name: "nameofcouples_sec",
            label: "<span style='font-size:12px;'>NAME</span>"
          }, {
            type: "label",
            className: "lbl",
            name: "nameofcouples",
            label: "<span style='font-size:12px;'>WHAT IS YOUR NAME?</span>"
          }, {
            type: "block",
            name: "form_block_1",
            position: "label-left",
            list: [{
                type: "input",
                name: "first_name",
                inputWidth: 120,
                validate: "validName"
              },

              {
                type: "newcolumn",
                offset: "5"
              }, {
                type: "input",
                name: "first_name_couple",
                inputWidth: 115,
                validate: "validName"
              }

            ]
          }, {
            type: "label",
            style: "font-weight:normal;",
            name: "coupleages",
            label: "<span style='font-size:12px;'>DATE OF BIRTH</span>"
          },

          {
            type: "label",
            label: "<span style='font-size:12px;'>WHAT IS YOUR DATE OF BIRTH?</span>",
            style: "font-weight:normal;",
            name: "coupleages_sec",
          },

           {
            type: "block",
            name: "form_block_2",
            list: [{
              type: "input",
              name: "age",
              inputWidth: 84,
              //labelAlign: "center",
              mask_to_use: "date",
              value: '',
              //validate: ValidAge
            },{
                type: "newcolumn",
                offset:2
            },{
              type: "label",
              position: "relative",
              labelWidth: 25,
              name: "calendarbutton",
              id: "ageDateClndr",
              label: "<span><img id='ageDateClndr' src='templates/tmpl_par/images/calendar.jpg' border='0' style = 'height:25px; width:25px; margin-top:-7px'></span>"
            },{
              type: "newcolumn",
              offset: 6
            },{
              type: "input",
              name: "coupleage",
              inputWidth: 84,
              mask_to_use: "date",
              value: '',
            },{
                type: "newcolumn",
                offset: 2
            },{
              type: "label",
              position: "relative",
              labelWidth: 25,
              name: "couplecalendarbutton",
              label: "<span><img id='coupleageDateClndr' src='templates/tmpl_par/images/calendar.jpg' border='0' style = 'height:25px; width:25px; margin-top:-7px'></span>"
            }]
          },{
            type: "label",
            className: "lbl",
            name: "dateformat",
            label: "<span style='font-size:10px; color:#57B4A8; padding-left:34px;'>(DATE FORMAT IS MM-DD-YYYY)</span>"
          },        
           {
            type: "label",
            style: "font-weight:normal;font-size:12px;",
            name: "Genderoftheusers",
            label: "<span style='font-size:12px;'>GENDER</span>",
            labelWidth: 250
          },
             {
            type: "block",
            name: "form_block_gen",
            offset: "54",
             list: [{
            type: "select",            
            name: "genderoffirst",
            inputWidth: 115, 
               options: [{
              text: "--Select--",
              value: "",
              selected: true
            }, {
              text: "MAN",
              value: "male",
            }, {
              text: "WOMAN",
              value: "female"
            },]              
              
            }, {
              type: "newcolumn",
              offset: "5"
            }, {
            type: "select",            
            name: "genderofsec",
            inputWidth: 115, 
               options: [{
              text: "--Select--",
              value: "",
              selected: true
            }, {
              text: "MAN",
              value: "male",
            }, {
              text: "WOMAN",
              value: "female"
            },]
              
              
            },]
          }, 
          
        
          {
            type: "label",
            style: "font-weight:normal;",
            name: "userethnicity",
            label: "<span style='font-size:12px;'>ETHNICITY</span>",
            labelWidth: 250
          },
             {
            type: "block",
            name: "form_block_eth",
            offset: "54",
            list: [{
              type: "select",
              name: "ethnicityofsingle",
              inputWidth: 115             
            }, {
              type: "newcolumn",
              offset: "5"
            }, {
              type: "select",
              name: "ethnicityofcouple",
              inputWidth: 115            
            }]
          },           
      
           {
            type: "label",
            style: "font-weight:normal;",
            name: "usereducation",
            label: "<span style='font-size:12px;'>EDUCATION</span>",
            labelWidth: 250
          },
           {
            type: "block",
            name: "form_block_edu",
            list: [{
              type: "select",
              name: "educationofsingle",
              inputWidth: 115
             // validate: "ValidAge"
            }, {
              type: "newcolumn",
              offset: "5"
            }, {
              type: "select",
              name: "educationofcouple",
              inputWidth: 115
            //  validate: "ValidAge"
            }]
          },
          
       
          {
            type: "label",
            style: "font-weight:normal;",
            name: "userreligion",
            label: "<span style='font-size:12px;'>RELIGION</span>",
          },
              {
            type: "block",
            name: "form_block_reg",
            list: [{
              type: "select",
              name: "religionofsingle",
              inputWidth: 115
             // validate: "ValidAge"
            }, {
              type: "newcolumn",
              offset: "5"
            }, {
              type: "select",
              name: "religionofcouple",
              inputWidth: 115
            //  validate: "ValidAge"
            }]
          },
            {
            type: "input",
            name: "occupationofsingle",
            label: "OCCUPATION OF FIRST PERSON?",
            labelAlign: "left",
            position: "label-top",
            validate: ValidOccupation
            
            
          },
           {
            type: "input",
            name: "occupationofcouple",
            label: "OCCUPATION OF SECOND PERSON?",
            labelAlign: "left",
            position: "label-top",
            validate: ValidOccupation
            
          },
          {
            type: "select",
            name: "waiting",
            label: "HOW LONG HAVE YOU BEEN WAITING?",
            position: "label-top",
            options: [{
              text: "--Select--",
              value: "",
              selected: true
            }, {
              text: "Less than 6 months",
              value: "Less than 6 months",
            }, {
              text: "Between 6 - 12 months",
              value: "Between 6 - 12 months"
            }, {
              text: "1 year",
              value: "1 year"
            }, {
              text: "2 years",
              value: "2 years"
            }, {
              text: "3 years",
              value: "3 years"
            }, {
              text: "more than 3 years",
              value: "more than 3 years"
            }]
          }, {
            type: "select",
            name: "noofchildren",
            label: "HOW MANY CHILDREN DO YOU HAVE?",
            labelAlign: "left",
            position: "label-top",
            validate: "ValidNumber"
          }, {
            type: "select",
            name: "ctype",
            label: "TYPE OF CHILDREN",
            position: "label-top",
            options: [{
              text: "--Select--",
              value: "",
              selected: true
            }, {
              text: "Adopted",
              value: "Adopted"
            }, {
              text: "Biological",
              value: "Biological"
            }, {
              text: "Biological and adopted",
              value: "Biological and adopted"
            }, {
              text: "Foster children",
              value: "Foster children"
            }]
          }, {
            type: "multiselect",
            name: "faith",
            inputHeight: 150,
            label: "WHAT FAITH(S) ARE YOU?",
            position: "label-top",
            options: [{
              text: "Not Specified",
              value: "Not Specified",
              selected: true
            }, {
              text: "Anglican",
              value: "Anglican"
            }, {
              text: "Bahai",
              value: "Bahai"
            }, {
              text: "Baptist",
              value: "Baptist"
            }, {
              text: "Buddhist",
              value: "Buddhist"
            }, {
              text: "Catholic",
              value: "Catholic"
            }, {
              text: "Christian",
              value: "Christian"
            }, {
              text: "Church of Christ",
              value: "Church of Christ"
            }, {
              text: "Episcopal",
              value: "Episcopal"
            }, {
              text: "Hindu",
              value: "Hindu"
            }, {
              text: "Jewish",
              value: "Jewish"
            }, {
              text: "Lutheran",
              value: "Lutheran"
            }, {
              text: "Methodist",
              value: "Methodist"
            }, {
              text: "Non-denominational",
              value: "Non-denominational"
            }, {
              text: "None",
              value: "None"
            }, {
              text: "Other",
              value: "Other"
            }, {
              text: "Presbyterian",
              value: "Presbyterian"
            }, {
              text: "Protestant",
              value: "Protestant"
            }, {
              text: "Unitarian",
              value: "Unitarian"
            }]
          },
		  {
            type: "input",
            name: "profilenumber",
            label: "PROFILE NUMBER",
            labelAlign: "left",
            position: "label-top",
            validate: Validprofilenumber
            
            
          }
		  
        ]
      }, 
	  /**/
	  
	  /**/
	  {
        type: "block",
        name: "ourHOME_block_8",
        list: [{
            type: "label",
            className: "lbl",
            name: "abouthome",
            label: "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_home.png' /> OUR HOME</span>"
          },

          {
            type: "label",
            className: "lbl ui-state-default",
            style: "font-weight:normal;",
            name: "form_label_5",
            label: "<span class='abouthomespan' style='color:#57B4A8;text-decoration: underline; cursor:pointer;' onclick='ProfilebuilderComponent.lettar(\"HOME\")';>ABOUT OUR HOME </span>"
          },

          {
            type: "select",
            inputHeight: 28,
            name: "housestyle",
            label: "WHAT STYLE OF HOUSE DO YOU OWN?",
            position: "label-top",
            options: [{
              text: "--Select--",
              value: "",
              selected: true
            }, {
              text: "Apartment",
              value: "Apartment"
            }, {
              text: "Condominium",
              value: "Condominium"
            }, {
              text: "Single Family Home",
              value: "Single Family Home"
            }, {
              text: "Townhouse",
              value: "Townhouse"
            }]
         }, 
//          {
//            type: "select",
//            inputHeight: 28,
//            name: "noofbedrooms",
//            label: "HOW MANY BEDROOMS?",
//            position: "label-top",
//            options: [{
//              text: "--Select--",
//              value: "",
//              selected: true
//            }, {
//              text: "1",
//              value: "1"
//            }, {
//              text: "2",
//              value: "2"
//            }, {
//              text: "3",
//              value: "3"
//            }, {
//              text: "4",
//              value: "4"
//            }, {
//              text: "5+",
//              value: "5+"
//            }]
//          }, 
//{
//            type: "select",
//            inputHeight: 28,
//            name: "noofbathrooms",
//            label: "HOW MANY BATHROOMS?",
//            position: "label-top",
//            options: [{
//              text: "--Select--",
//              value: "",
//              selected: true
//            }, {
//              text: "1",
//              value: "1"
//            }, {
//              text: "2",
//              value: "2"
//            }, {
//              text: "3",
//              value: "3"
//            }, {
//              text: "4",
//              value: "4"
//            }, {
//              text: "5+",
//              value: "5+"
//            }]
//          }
//         , {
//            type: "input",
//            inputHeight: 28,
//            name: "yardsize",
//            label: "WHAT IS THE LOT OR YARD SIZE?",
//            labelAlign: "left",
//            position: "label-top"
//          }, 
{
            type: "select",
            inputHeight: 28,
            name: "neighbourhoodlike",
            label: "WHAT IS YOUR NEIGHBORHOOD LIKE?",
            position: "label-top",
            options: [{
              text: "--Select--",
              value: "",
              selected: true
            }, {
              text: "Rural",
              value: "Rural"
            }, {
              text: "Suburban",
              value: "Suburban"
            }, {
              text: "Urban",
              value: "Urban"
            }]
          },
           { 
          type: "multiselect",
          name: "pets",
          label: "PET",
          position: "label-top",
          inputHeight: 150
        },
          {
          type: "select",
          name: "relationship_Status",
          label: "RELATIONSHIP STATUS",
          position: "label-top",
          inputHeight: 28
        },
        {
          type: "select",
          name: "family_structure",
          label: "FAMILY STRUCTURE",
          position: "label-top",
          inputHeight: 28
        },
          
        ]
      }, {
        type: "block",
        name: "PHOTOS_block",
        list: [{
          type: "label",
          className: "lbl",
          name: "form_label_8",
          label: "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_photos.png' /> PHOTOS</span><span  style =\"float:right;padding-top:6%;\" ><a class='tooltip' title-text='Profile photos' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>"
        }, {
          type: "label",
          className: "lbl",
          name: "form_label_9",
          label: "AVATAR PHOTO",
          style: "font-weight:normal;",
        }, {
          type: "upload",
          name: "Avatarphoto",
          inputWidth: 280,
          mode: "html4",
          _swfLogs: "enabled",
          autoStart: true,
          url: "ProfilebuilderComponent/processors/uploadProfilePhoto.php",
          swfPath: "../../plugins/dhtmlx/dhtmlxForm/codebase/ext/uploader.swf",
          swfUrl: "../../../../../../ProfilebuilderComponent/processors/uploadProfilePhoto.php"
        }, {
          type: "label",
          className: "lbl",
          style: "font-weight:normal;",
          name: "form_label_6",
          label: "HOME PICTURES"
        }, {
          type: "upload",
          name: "homephoto",
          inputWidth: 280,
          mode: "html5",
          _swfLogs: "enabled",
          autoStart: true,
          url: "ProfilebuilderComponent/processors/savePhoto.php",
          swfPath: "../../plugins/dhtmlx/dhtmlxForm/codebase/ext/uploader.swf",
          swfUrl: "../../../../../../AgencybuilderComponent/processors/savePhoto.php"
        },{
          type: "label",
          className: "lbl",
          name: "banDiv1",
          label: "BANNER IMAGE",
          style: "font-weight:normal;",
        }, {
          type: "upload",
          name: "Bannerimage",
          inputWidth: 280,
          mode: "html4",
          _swfLogs: "enabled",
          autoStart: true,
          url: "ProfilebuilderComponent/processors/uploadCoverPhoto.php",
          swfPath: "../../plugins/dhtmlx/dhtmlxForm/codebase/ext/uploader.swf",
          swfUrl: "../../../../../../ProfilebuilderComponent/processors/uploadCoverPhoto.php"
		  /*
          url: "ProfilebuilderComponent/processors/uploadCoverPhoto.php",
          swfPath: "../../plugins/dhtmlx/dhtmlxForm/codebase/ext/uploader.swf",
          swfUrl: "../../../../../../ProfilebuilderComponent/processors/uploadCoverPhoto.php"		  
		  /**/
        },
		{
          type: "label",
          className: "",
          name: "banDiv2",
          label: "Banner images greater than 1000*300 only",
          style: "font-weight:normal;",
        }
		
		]
      }, {
        type: "block",
        name: "VIDEOS_block",
        list: [{
          type: "label",
          className: "lbl",
          name: "form_label_11",
          label: "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_video.png' /> VIDEOS</span><span  style =\"float:right;padding-top:6%;\"><a class='tooltip' title-text='Profile videos' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>"
        }, {
          type: "label",
          className: "lbl",
          style: "font-weight:normal;",
          name: "form_label_6",
          label: "HOME VIDEOS"
        }, {
          type: "upload",
          name: "home_videos",
          inputWidth: 280,
          mode: "html4",
          _swfLogs: "enabled",
          autoStart: true,
          url: "ProfilebuilderComponent/processors/saveHomeVideo.php",
          swfPath: "../../plugins/dhtmlx/dhtmlxForm/codebase/ext/uploader.swf",
          swfUrl: "../../../../../../ProfilebuilderComponent/processors/saveHomeVideo.php"
        }, ]
      }, ]
    }, {
      type: "newcolumn",
      offset: 50
    }, {
      type: "block",
      name: "form_block_7",
      list: [{
        type: "block",
        name: "aboutChild_block",
        list: [{
          type: "settings",
          inputHeight: 28
        }, {
          type: "label",
          className: "lbl",
          name: "form_label_12",
          label: "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_about_child.png' /> ABOUT THE CHILD</span><span  style =\"float:right;padding-top:6%;\"><a class='tooltip' title-text='About the child' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>"
        }, {
          type: "multiselect",
          name: "ethnicty_preference",
          label: "ETHNICITY PREFERENCE",
          position: "label-top",
          inputHeight: 150,
          options: [{
            text: "Not Specified",
            value: "Not Specified",
            selected: true
          }, {
            text: "African American",
            value: "African American"
          }, {
            text: "African American/Asian",
            value: "African American/Asian"
          },{
            text: "Any",
            value: "Any"
          }, {
            text: "Asian",
            value: "Asian"
          }, {
            text: "Asian/Hispanic",
            value: "Asian/Hispanic"
          }, {
            text: "Bi-Racial",
            value: "Bi-Racial"
          }, {
            text: "Caucasian",
            value: "Caucasian"
          }, {
            text: "Caucasian/Asian",
            value: "Caucasian/Asian"
          }, {
            text: "Caucasian/African American",
            value: "Caucasian/African American"
          }, {
            text: "Caucasian/Hispanic",
            value: "Caucasian/Hispanic"
          }, {
            text: "Caucasian/Native American",
            value: "Caucasian/Native American"
          }, {
            text: "Eastern European/Slavic/Russian",
            value: "Eastern European/Slavic/Russian"
          }, {
            text: "European",
            value: "European"
          }, {
            text: "Hispanic/African American",
            value: "Hispanic/African American"
          }, {
            text: "Hispanic or South/Central American",
            value: "Hispanic or South/Central American"
          }, {
            text: "Jewish",
            value: "Jewish"
          }, {
            text: "Mediterranean",
            value: "Mediterranean"
          }, {
            text: "Middle Eastern",
            value: "Middle Eastern"
          }, {
            text: "Multi-Racial",
            value: "Multi-Racial"
          }, {
            text: "Native American (American Indian)",
            value: "Native American (American Indian)"
          }, {
            text: "Other",
            value: "Other"
          }, {
            text: "Pacific Islander",
            value: "Pacific Islander"
          }]
        }, {
          type: "multiselect",
          name: "age_preference",
          label: "AGE PREFERENCE",
          position: "label-top",
          inputHeight: 150,
          options: [{
            text: "Not Specified",
            value: "Not Specified",
            selected: true
          }
          , {
            text: "Newborn",
            value: "Newborn"
          }
          , {
            text: "Newborn - 3 months",
            value: "Newborn - 3 months"
          }
          , {
            text: "Newborn - 6 months",
            value: "Newborn - 6 months"
          }
          , {
            text: "Newborn - 9 months",
            value: "Newborn - 9 months"
          }
          , {
            text: "Newborn - 1 year",
            value: "Newborn - 1 year"
          }
          , {
            text: "Newborn - 2 years",
            value: "Newborn - 2 years"
          }, 
          {
            text: "Newborn - 3 years",
            value: "Newborn - 3 years"
          }, {
            text: "Newborn - 4 years",
            value: "Newborn - 4 years"
          }, {
            text: "Newborn - 5+ years",
            value: "Newborn - 5+ years"
          }, {
            text: "1-2 months",
            value: "1-2 months"
          }, {
            text: "3-4 months",
            value: "3-4 months"
          }, {
            text: "5-6 months",
            value: "5-6 months"
          }, {
            text: "7-8 months",
            value: "7-8 months"
          }, {
            text: "9-11 months",
            value: "9-11 months"
          }, {
            text: "1 year old",
            value: "1 year old"
          }, {
            text: "2 years old",
            value: "2 years old"
          }, {
            text: "3 years old",
            value: "3 years old"
          }, {
            text: "4 years old",
            value: "4 years old"
          }, {
            text: "5 years old",
            value: "5 years old"
          }, {
            text: "6 years old",
            value: "6 years old"
          }, {
            text: "7 years old",
            value: "7 years old"
          }, {
            text: "8 years old",
            value: "8 years old"
          }, {
            text: "Over 8 years old",
            value: "Over 8 years old"
          }]
        },
          {
          type: "select",
          name: "gender",
          label: "GENDER",
          position: "label-top",          
            options: [ {
              text: "--Select--",
              value: "",
              selected: true
            },{
              text: "Male",
              value: "Male"
            }, {
              text: "Female",
              value: "Female"
            },
           {
              text: "Either",
              value: "Either"
            },     

           ]
          },
           { 
          type: "multiselect",
          name: "child_desired",
          label: "CHILD DESIRED",
          position: "label-top",
          inputHeight: 100
        },
        { 
          type: "multiselect",
          name: "BirthFatherStatus",
          label: "BIRTH FATHER STATUS",
          position: "label-top",
          inputHeight: 150
        },
        
        {
          type: "multiselect",
          name: "adoptiontype",
          label: "ADOPTION TYPE",
          position: "label-top",
          inputHeight: 150,
          options: [{
            text: "Open",
            value: "Open",
            selected: true
          }, {
            text: "Semi-Open",
            value: "Semi-Open"
          }, {
            text: "Closed",
            value: "Closed"
          }]
        }, {
          type: "label",
          className: "lbl",
          name: "label85",
          label: "SPECIAL NEEDS",
        }, {
          type: "block",
          name: "form_block_4",
          list: [{
            type: "settings",
            position: "label-left",
            labelWidth: 30,
            inputWidth: 70,
            offsetLeft: "10"
          }, {
            type: "radio",
            name: "specialneeds",
            label: "<span class='SpecialNeedText'>YES</span>",
            value: "yes",
            offsetTop: "2"
          }, {
            type: "newcolumn"
          }, {
            type: "radio",
            name: "specialneeds",
            label: "<span class='SpecialNeedText'>NO</span>",
            value: "no"
          }]
        },{
            type: "multiselect",
            name: "specialneedoptions",
            inputHeight: 150,
            label: "SPECIAL NEED OPTIONS",
            position: "label-top",
            options: [{              
              text: "ADD/ADHD",
              value: "ADD/ADHD"
            }
        ,{              
              text: "Alcohol exposed(occasional)",
              value: "Alcohol exposed(occasional)"
            },{              
              text: "Autism/ Autism Spectrum Disorder",
              value: "Autism/ Autism Spectrum Disorder"
            },{              
              text: "Blindness",
              value: "Blindness"
            },{              
              text: "Club Foot",
              value: "Club Foot"
            },{              
              text: "Cleft Pallet or lip",
              value: "Cleft Pallet or lip"
            },{              
              text: "Conceived through rape",
              value: "Conceived through rape"
            },{              
              text: "Conceived through incest",
              value: "Conceived through incest"
            },{              
              text: "Deafness",
              value: "Deafness"
            },{              
              text: "Diabeties in Child",
              value: "Diabeties in Child"
            },{              
              text: "Diabeties in Family",
              value: "Diabeties in Family"
            },{              
              text: "Down Syndrome",
              value: "Down Syndrome"
            },{              
              text: "Drug Exposed (occasional)",
              value: "Drug Exposed (occasional)"
            },{              
              text: "Emotional/mental disorders  in family",
              value: "Emotional/mental disorders  in family"
            },{              
              text: "Epilepsy in Family",
              value: "Epilepsy in Family"
            },{              
              text: "Fetal Alcohol Effects",
              value: "Fetal Alcohol Effects"
            },{              
              text: "Family mental retardation",
              value: "Family mental retardation"
            },{              
              text: "HIV/AIDS",
              value: "HIV/AIDS"
            },{              
              text: "Limited life expectancy",
              value: "Limited life expectancy"
            },{              
              text: "Mental Retardation",
              value: "Mental Retardation"
            },{              
              text: "Mild or medically correctable",
              value: "Mild or medically correctable"
            },{              
              text: "Multiple Birth",
              value: "Multiple Birth"
            },{              
              text: "Nothing known about father",
              value: "Nothing known about father"
            },{              
              text: "Nothing known about mother",
              value: "Nothing known about mother"
            },{              
              text: "Smoking Exposed",
              value: "Smoking Exposed"
            },{              
              text: "Premature Birth",
              value: "Premature Birth"
            },{              
              text: "Requires specialized care",
              value: "Requires specialized care"
            },{              
              text: "Requires life long medical treatment",
              value: "Requires life long medical treatment"
            },{              
              text: "Sibling group",
              value: "Sibling group"
            },{              
              text: "Sickle Cell Anemia or Trait",
              value: "Sickle Cell Anemia or Trait"
            },{              
              text: "Terminally ill",
              value: "Terminally ill"
            },{              
              text: "Seizures",
              value: "Seizures"
            }]
          }]
      }, {
        type: "block",
        name: "CONTACTINFO_block",
        list: [{
          type: "settings",
          inputHeight: 28
        }, {
          type: "label",
          className: "lbl",
          name: "contact",
          label: "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_contact.png' /> OUR CONTACT INFO</span><span style =\"float:right;padding-top:6%;\"><a class='tooltip' title-text='CONTACT' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>"
        }, {
          type: "input",
          name: "phonenumber",
          label: "PHONE NUMBER",
          position: "label-top"
        }, {
          type: "input",
          name: "email_address",
          label: "EMAIL ADDRESS",
          position: "label-top",
          validate: "ValidEmail"
        }, {
          type: "input",
          name: "address1",
          label: "ADDRESS 1",
          position: "label-top",
        }, {
          type: "input",
          name: "address2",
          label: "ADDRESS 2",
          position: "label-top",
        }, {
          type: "input",
          name: "city",
          label: "CITY",
          position: "label-top"
        }, {
          type: "select",
          name: "state_list",
          label: "WHAT STATE DO YOU LIVE IN?",
          position: "label-top"
        },
        
        {
          type: "select",
          name: "region_list",
          label: "REGION",
          position: "label-top"
        },
        
        {
          type: "select",
          name: "country_list",
          label: "COUNTRY",
          position: "label-top"
        },
        {
          type: "input",
          name: "zip",
          label: "ZIP",
          position: "label-top"
        },
		/*
		{
          type: "checkbox",
          name: "show_contact",
          label: "SET AS DEFAULT CONTACT",
          position: "label-bottom"
        },
		/**/
        {
			type: "block", 
			list:[
				{
				  type: "checkbox",
				  name: "show_contact",
				  labelWidth: 20
				},
				{type: "newcolumn"},
				{
					type: "label", 
					name: "defaultcontactlabel",
					label: "<span style='font-size:12px;'>SET AS DEFAULT CONTACT</span><span style =\"float:right;\"><a class='tooltip' title-text='If checked, Family contact information will be displayed in contact page' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>",
					labelWidth: 214
				},
			]
		}		
		/**/
		]
      }, {
        type: "block",
        name: "AGENCYINFO_block",
        list: [{
          type: "settings",
          inputHeight: 28
        }, {
          type: "label",
          className: "lbl",
          name: "agency",
          label: "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_agency.png' /> OUR AGENCY</span>"
        }, {
          type: "select",
          name: "profile_agency",
          disabled: "true"
        }]
      }, {
        type: "block",
        name: "flipbook_block",
        list: [{
          type: "label",
          className: "lbl",
          name: "profileflipbook",
          label: "<span style='color: #57B4A8;font-size:15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_flipbook.png' /> OUR FLIPBOOK</span>"
        }, {
          type: "upload",
          name: "myFiles",
          mode: "html4",
          autoStart: true,
          inputWidth: 280,
          url: "file_upload.php",
          _swfLogs: "enabled",
          swfPath: "plugins/dhtmlx/dhtmlxForm/codebase/ext/uploader.swf",
          swfUrl: "file_upload.php"
        },
        {type: "label", className: "flip_status",label: "<span style='font-size:14px;font-weight:100;color:rgb(244, 151, 194);'>Click here to view your Flipbook</span>",labelWidth: 260}, ]
      },       
       {
        type: "block",
        name: "epub_block",
        list: [{
          type: "label",
          className: "lbl",
          name: "profileepub",
          label: "<span style='color: #57B4A8;font-size:15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_flipbook.png' /> E-Pub Profile</span>"
        }, {
          type: "upload",
          name: "myEpubs",
          mode: "html4",
          autoStart: true,
          inputWidth: 280,
          url: "epop_upload.php",
          _swfLogs: "enabled",
          swfPath: "plugins/dhtmlx/dhtmlxForm/codebase/ext/uploader.swf",
          swfUrl: "epop_upload.php"
        },
        {type: "label", className: "epub_status",label: "<span style='font-size:14px;font-weight:100;color:rgb(244, 151, 194);'>Click here to view your E-pub Profile</span>",labelWidth: 260}, ]
      },{
        type: "block",
        name: "SOCIALINFO_block",
        list: [{
            type: "settings",
            inputHeight: 28
          }, {
            type: "label",
            className: "lbl",
            name: "form_label_18",
            label: "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'> SOCIAL NETWORKS</span><span style =\"float:right;\"><a class='tooltip' title-text='Add the links to diffrent social networks like facebook, twitter, Blog and share it with others' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>"
          },

          {
            type: "block",
            name: "form_block_5",
            list: [{
              type: "settings",
              inputHeight: 28
            }, {
              type: "settings",
              position: "label-top",
              labelWidth: 37,
              inputWidth: 44
            }, {
              type: "radio",
              name: "fburl",
              label: "<img src='templates/tmpl_par/images/ico_build_fb.png' />",
              value: "facebook",
              position: "label-top"
            }, {
              type: "newcolumn"
            }, {
              type: "radio",
              name: "turl",
              label: "<img src='templates/tmpl_par/images/ico_build_tw.png' />",
              value: "twitter",
              position: "label-top"
            }, {
              type: "newcolumn"
            }, {
              type: "radio",
              name: "gurl",
              label: "<img src='templates/tmpl_par/images/ico_build_go.png' />",
              value: "google",
              labelAlign: "left",
              position: "label-top"
            }, {
              type: "newcolumn"
            }, {
              type: "radio",
              name: "burl",
              label: "<img src='templates/tmpl_par/images/ico_build_bi.png' />",
              value: "blogger",
              position: "label-top"
            }, {
              type: "newcolumn"
            }, {
              type: "radio",
              name: "purl",
              label: "<img src='templates/tmpl_par/images/ico_build_pi.png' />",
              value: "pinerest",
              position: "label-top"
            }, {
              type: "newcolumn"
            },{
              type: "radio",
              name: "iurl",
              label: "<img src='templates/tmpl_par/images/ico_build_in.png' />",
              value: "instagram",
              position: "label-top"
            }]
          },
        ]
      }, {
        type: "block",
        name: "PLACE_YOUR_PROFILE_block",
        list: [{
          type: "label",
          className: "lbl",
          name: "form_label_19",
          label: "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_change_pass.png' /> PLACE YOUR PROFILE</span><span style =\"float:right;padding-top:6%\"><a class='tooltip' title-text='PLACE YOUR PROFILE' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>"
        }, {
          type: "input",
          name: "profilebadge",
          label: "SELECT THIS CODE AND PASTE IT TO YOUR AGENCY’S PAGE",
          position: "label-top",
          inputHeight: 28
        }, ]
      }]
    }, {
      type: "newcolumn",
      offset: 50
    }, {
      type: "block",
      name: "form_block_6",
      list: [{
        type: "block",
        name: "LETTERS_block",
        list: [{
          type: "label",
          className: "lbl",
          name: "letters",
          label: "<span style='  color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_letters.png' /> OUR LETTERS</span>"
        }, {
          type: "label",
          className: "lbl",
          label: "<span style='color: #f5b3d1;text-decoration: none; cursor:pointer;' onclick='ProfilebuilderComponent.lettar(\"OTHER\")';>CREATE CUSTOM LETTER</span>",
          style: "font-weight:normal;",
          name: "about_other"
        }, {
          type: "label",
          className: "lbl ui-state-default letter_mother my-first-dev",
          name: "about_mother",
          label: "<span  style='color: #57B4A8;text-decoration: underline; cursor:pointer;'  onclick='ProfilebuilderComponent.lettar(\"MOTHER\")';>EXPECTING MOTHER LETTER</span>",
          style: "font-weight:normal;"
        }, {
          type: "label",
          className: "lbl ui-state-default letter_agency",
          label: "<span  style='color: #57B4A8;text-decoration: underline; cursor:pointer;'  onclick='ProfilebuilderComponent.lettar(\"AGENCY\")';>AGENCY LETTER</span>",
          style: "font-weight:normal;",
          name: "about_agency"
        }, {
          type: "label",
          className: "lbl ui-state-default letter_about_him",
          label: "<span  style='color: #57B4A8;text-decoration: underline; cursor:pointer;'  onclick='ProfilebuilderComponent.lettar(\"HIM\")';>LETTER ABOUT HIM</span>",
          style: "font-weight:normal;",
          name: "about_him"
        }, {
          type: "label",
          className: "lbl ui-state-default letter_about_her",
          label: "<span  style='color: #57B4A8;text-decoration: underline; cursor:pointer;'  onclick='ProfilebuilderComponent.lettar(\"HER\")';>LETTER ABOUT HER</span>",
          style: "font-weight:normal;",
          name: "about_her"
        }, {
          type: "label",
          className: "lbl ui-state-default letter_about_them",
          label: "<span  style='color: #57B4A8;text-decoration: underline; cursor:pointer;'  onclick='ProfilebuilderComponent.lettar(\"THEM\")';>LETTER ABOUT THEM</span>",
          style: "font-weight:normal;",
          name: "about_them"
        }, ]
      }, {
        type: "block",
        name: "WEBSITEINFO_block",
        list: [{
          type: "label",
          className: "lbl",
          name: "websites",
          label: "<span style='color: #57B4A8;font-size: 15px;font-weight: bold;'><img src='templates/tmpl_par/images/ico_build_agency.png' /> OUR WEBSITE</span><br/><p></p>"
        }, {
          type: "input",
          name: "weburls",
          inputHeight: 28
        }, ]
      }, {
        type: "button",
        offsetTop: "60",
        value: "SUBMIT FOR APPROVAL",
        style: "font-weight:normal;",
        name: "approval"
      }, {
        type: "button",
        offsetTop: "60",
        value: "SUBMIT CHANGES",
        style: "font-weight:normal;padding:0 10px",
        name: "approved"

      }]
    }]
  },
  "mothersletter": [
    {
      type: "label",
      className: "lbl",
      name: "LetterLabel",
      label: "<div><b>EXPECTING MOTHER LETTER</b></div>",
      labelWidth: 570,
      position: "relative"
    },
    {
      type: "select",
      name: "Lettername",
      label: "Label",
      labelAlign: "left",
      inputWidth: 387,
      labelWidth: 40,
      labelTop: 10,
      offsetLeft: "50",
      inputTop: 10,
      inputLeft: 130,
      required: true,
      labelWidth: 100,
      position: "relative"            
            },
      {
      type: "input",
      name: "Letternamecustom",
      label: "Label Name",
      labelAlign: "left",
      inputWidth: 388,
      labelWidth: 40,
      labelTop: 40,
      offsetLeft: "50",
      inputTop: 40,      
      offsetTOP: "50",
      inputLeft: 130,
      required: true,
      labelWidth: 100,
      position: "relative"            
            },            
{
      type: "tinyMCE",
      rows: 50,
      name: "Letter",
      //label: "",

      // label: "750 word Maximum",
      //labelWidth: 200,
      //labelAlign: "left",
      inputWidth: 470,
      inputHeight: 210,
      offsetLeft: "50",
      //labelTop: 65,
      //inputTop: 150,
      required: true,
	inputWidth: 330,
	//width: 330,  
	inputTop: 30,
	inputLeft: 50,
//      position: "absolute"
      position: "relative",
	className: "tinyDiv"

    },  
//    {
//      type: "input",
//      name: "Lettername",
//      label: "Label",
//      labelAlign: "left",
//      inputWidth: 387,
//      labelWidth: 40,
//      labelTop: 38,
//      offsetLeft: "50",
//      inputTop: 40,
//      inputLeft: 130,
//      required: true,
//      labelWidth: 100,
//      position: "absolute"
//    }, 
 

   

        {
		type: "block", 
		offsetLeft: "50",
		offsetTop: 20,
		list:[
			{
			      type: "button",
			      name: "letter_images",
  				value: "ASSOCIATE IMAGES",
			},
			{type: "newcolumn"},
			{
      				type: "button",
      				name: "letter_save",
      				value: "SAVE",
				offsetLeft: "10",
    			},
			{type: "newcolumn"},
			{
  				type: "button",
 			     	name: "letter_delet",
    		 		value: "DELETE",
				offsetLeft: "10",
			},
//			{type: "newcolumn"},
//			{
//  				type: "button",
//				name: "letter_download",
//				value: "<img id='dwnld_img' style='cursor:pointer;' src='templates/tmpl_par/images/ltr_download.png' />",
//				offsetLeft: "10",
//				className: "ltr_dwnld"
//			},
//			{type: "newcolumn"},
//			{
//				type: "label",
//				className: "dwnld_ldng",
//				label: "<img src='templates/base/images/loading.gif' />",
//			}

		]
	},    
/*
    {
      type: "button",
      name: "letter_images",
      value: "ASSOCIATE IMAGES",
      inputTop: 450,
      offsetLeft: 45,
      position: "relative",
      className: "imageButton"
    },   
    {
      type: "button",
      name: "letter_save",
      value: "SAVE",
      inputTop: 450,
      offsetLeft: "45",
      position: "relative",
	className: "save_btn",
    },
    {
      type: "button",
      name: "letter_delet",
      value: "DELETE",
      inputTop: 450,
      offsetLeft: 123,
      position: "relative",
	className: "delete_btn",
    }, 
*/
	{
	  type: "hidden",
	  name: "imgID",
	  className: "imgID"
	},{
	  type: "hidden",
	  name: "cusID",
	  className: "cusID"
	},
    {
      type: "label",
      className: "lbl selectedImg",
      name: "LetterLabel",
      label: "<div id='selected_images' style='margin-left:43px;'></div>",
      labelWidth: 850,
      position: "relative"
    },

 {
      type: "label",
      inputTop: 450,
	name: "zz",
      offsetLeft: 150,
      position: "relative",
	className: "emty",
    }, {
      type: "newcolumn"
    }, 
/*
	{
      type: "label",
      className: "lbl lbl_ltr",
      name: "asd",
      label: "<span><a class='tooltip' title-text='letter' href='javascript:void(0)' style='float:left'><span><b class='more-link'></b></span></a></span>",
      labelTop: 450,
      labelLeft: 767,
      position: "relative"
    }
*/
  ],
  "conf_form1": {
    "template_profilebuilder1": [{
        type: "settings",
        position: "label-top"
      }, {
        type: "block",
        list: [{
          type: "input",
          name: "facebookurl",
          inputWidth: 265,
          inputHeight: 30,
          offsetTop: 7,
          offsetLeft: 15,
          offsetRight: 10,
          label: "Facebook:"
        }]
      }, {
        type: "block",
        list: [{
          type: "input",
          name: "twitterurl",
          inputWidth: 265,
          inputHeight: 30,
          offsetTop: 7,
          offsetLeft: 15,
          offsetRight: 10,
          label: "Twitter:"
        }]
      },

      {
        type: "block",
        list: [{
          type: "input",
          name: "googleurl",
          inputWidth: 265,
          inputHeight: 30,
          offsetTop: 7,
          offsetLeft: 15,
          offsetRight: 10,
          label: "Google+:"
        }]
      },

      {
        type: "block",
        list: [{
          type: "input",
          name: "bloggerurl",
          inputWidth: 265,
          inputHeight: 30,
          offsetTop: 7,
          offsetLeft: 15,
          offsetRight: 10,
          label: "Blogger:"
        }]
      }, {
        type: "block",
        list: [{
          type: "input",
          name: "pineresturl",
          inputWidth: 265,
          inputHeight: 30,
          offsetTop: 7,
          offsetLeft: 15,
          offsetRight: 10,
          label: "Pinerest:"
        }]
      }, {
        type: "block",
        list: [{
          type: "input",
          name: "instagramurl",
          inputWidth: 265,
          inputHeight: 30,
          offsetTop: 7,
          offsetLeft: 15,
          offsetRight: 10,
          label: "Instagram:"
        }]
      }, {
        type: "newcolumn"
      }, {

        type: "button",
        value: "SUBMIT",
        style: "font-weight:normal;",
        offsetLeft: 10,
        name: "socialsubmit",
        // labelWidth: 215,=
        //  offsetLeft: "260",
        offsetTop: "22"


      }

    ]

  }

};
/*
 * ALTER TABLE `Profiles` CHANGE `ChildEthnicity` `ChildEthnicity` SET( 'Any', 'Bi-Racial', 'Caucasian', 'Caucasian/Asian', 'Caucasian/African American', 'African American', 'African American/Asian', 'Asian/Hispanic', 'European', 'Caucasian/Hispanic', 'Caucasian/Native American', 'Eastern European/Slavic/Russian', 'Hispanic', 'Hispanic/African American', 'Hispanic or South/Central American', 'Jewish', 'Mediterranean', 'Middle Eastern', 'Multi-Racial', 'Native American (American Indian)', 'Pacific Islander', 'Middle Eastern', 'Asian', 'Other' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
 * ALTER TABLE `Profiles` CHANGE `ChildAge` `ChildAge` SET( 'Newborn - 3 months', 'Newborn - 6 months', 'Newborn - 9 months', 'Newborn - 1 year', 'Newborn - 2 years', 'Newborn - 3 years', 'Newborn - 4 years', 'Newborn - 5+ years', 'Newborn', '1-2 months', '3-4 months', '5-6 months', '7-8 months', '9-11 months', '1 year old', '2 years old', '3 years old', '4 years old', '5 years old', '6 years old', '7 years old', '8 years old', 'Over 8 years old' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
 * ALTER TABLE `Profiles_draft` CHANGE `ChildEthnicity` `ChildEthnicity` SET( 'Any', 'Bi-Racial', 'Caucasian', 'Caucasian/Asian', 'Caucasian/African American', 'African American', 'African American/Asian', 'Asian/Hispanic', 'European', 'Caucasian/Hispanic', 'Caucasian/Native American', 'Eastern European/Slavic/Russian', 'Hispanic', 'Hispanic/African American', 'Hispanic or South/Central American', 'Jewish', 'Mediterranean', 'Middle Eastern', 'Multi-Racial', 'Native American (American Indian)', 'Pacific Islander', 'Middle Eastern', 'Asian', 'Other' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
 * ALTER TABLE `Profiles_draft` CHANGE `ChildAge` `ChildAge` SET( 'Newborn - 3 months', 'Newborn - 6 months', 'Newborn - 9 months', 'Newborn - 1 year', 'Newborn - 2 years', 'Newborn - 3 years', 'Newborn - 4 years', 'Newborn - 5+ years', 'Newborn', '1-2 months', '3-4 months', '5-6 months', '7-8 months', '9-11 months', '1 year old', '2 years old', '3 years old', '4 years old', '5 years old', '6 years old', '7 years old', '8 years old', 'Over 8 years old' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
 */

