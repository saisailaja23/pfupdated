/********************
 * By            :Aravind(MSI1308 <aravind.buddha@mediaus.com>)
 * Date          :2014-02-26 16:46
 * Company       :MSI
 * Description:
 ************************/

var MemberComponent_Model = {
  "text_labels": {
    "main_window_title": ""
  },
  // Configuring window  
  "conf_window": {

    "width": 580,
    "height": 280,
    "enableAutoViewport": true

  },

  // Defining layout pattern   
  "conf_layout": {
    "pattern": "1C"
  },
  // Form creation
  "conf_form": {
    //  Profile type selection form     
    "template": [{
      type: "label",
      className: "popUpHeader",
      name: "profilelabel_main",
      label: "JOIN",
      labelWidth: 150,
      position: "label-left",
      offsetTop: "20"
    }, {
      type: "select",
      label: "<span style='color: #009D8C;font-weight: bold;'>PROFILE TYPE</span>",
      id: "profiletypes",
      name: "profiletypes",
      labelWidth: 140,
      inputWidth: 213,
      offsetLeft: "80",
      position: "label-left",
      offsetTop: "50"
    }],
    //   User Joining form   
    "template_join": [{
        type: "label",
        className: "popUpHeader",
        name: "profilelabel_main",
        label: "JOIN",
        labelWidth: 150,
        position: "label-left",
        offsetTop: "20"
      }, {
        type: "label",
        name: "profilelabel",
        label: "<span style='color: #009D8C;font-weight: bold;'>PROFILE</span>",
        labelWidth: 100,
        offsetLeft: "75",
        position: "label-left",
        offsetTop: 15
      },

      {
        type: "block",
        width: 500,
        offsetLeft: "77",
        list: [

          {
            type: "radio",
            name: "profilestatus",
            label: "<a style='color: #76787b !important; cursor: pointer;font-weight: 500;'>SINGLE</a>",
            value: "single",
            position: "label-right",
            checked: "true"
          }, {
            type: "newcolumn"
          }, {
            type: "radio",
            name: "profilestatus",
            label: "<a style='color: #76787b !important; cursor: pointer;font-weight: 500;'>COUPLE</a>",
            value: "couple",
            offsetLeft: "15",
            position: "label-right"
          },


        ]
      }, 

    // {
    //   type: "select",
    //   name: "mtype",
    //   label: "MEMBERSHIP TYPE",
    //   required: true,
    //   labelWidth: 150,
    //   inputWidth: 213,
    //   offsetLeft: "80",
    //   inputLeft: 200,
    //   position: "label-left",
    //   labelHeight: 20
    // },

      {
        type: "input",
        name: "username",
        label: "USERNAME",
        required: true,
        labelWidth: 150,
        inputWidth: 213,
        offsetLeft: "80",
        inputLeft: 200,
        position: "label-left",
        labelHeight: 20
      }, {
        type: "password",
        name: "password",
        label: "PASSWORD",
        required: true,
        labelWidth: 150,
        inputWidth: 213,
        offsetLeft: "80",
        inputLeft: 200,
        position: "label-left",
        labelHeight: 20
      }, {
        type: "password",
        name: "cpassword",
        label: "CONFIRM PASSWORD",
        required: true,
        labelWidth: 150,
        inputWidth: 213,
        offsetLeft: "80",
        inputLeft: 200,
        position: "label-left",
        labelHeight: 20
      }, {
        type: "input",
        name: "email",
        label: "EMAIL",
        required: true,
        labelWidth: 150,
        inputWidth: 213,
        offsetLeft: "80",
        inputLeft: 200,
        position: "label-left",
        labelHeight: 20
      }, {
        type: "label",
        name: "aboutlabel",
        label: "<span style='color: #009D8C;font-weight: bold;'>ABOUT</span>",
        labelWidth: 150,
        inputWidth: 213,
        offsetLeft: "75",
        inputLeft: 200,
        position: "label-left",
        labelHeight: 20,
        offsetTop: 25
      }, {
        type: "input",
        name: "firstname",
        label: "FIRST NAME",
        required: true,
        labelWidth: 150,
        inputWidth: 213,
        offsetLeft: "80",
        inputLeft: 200,
        position: "label-left",
        labelHeight: 20
      }, {
        type: "input",
        name: "lastname",
        label: "LAST NAME",
        required: true,
        labelWidth: 150,
        inputWidth: 213,
        offsetLeft: "80",
        labelLeft: 5,
        labelTop: 5,
        inputLeft: 200,
        position: "label-left",
        labelHeight: 20
      },

      {
        type: "block",
        width: 500,
        offsetLeft: "77",
        list: [{
          type: "radio",
          name: "gender",
          label: "<a style='color: #76787b !important; cursor: pointer;font-weight: 500;'>MAN</a>",
          value: "male",

          position: "label-right",

          checked: "true"
        }, {
          type: "newcolumn"
        }, {
          type: "radio",
          name: "gender",
          label: "<a style='color: #76787b !important; cursor: pointer;font-weight: 500;'>WOMAN</a>",
          value: "female",
          offsetLeft: "15",
          position: "label-right"
        }, ]
      }, {
        type: "label",
        name: "aboutlabel_sec",
        label: "<span style='color: #009D8C;font-weight: bold;'>ABOUT - SECOND PERSON</span>",
        labelWidth: 250,
        offsetLeft: "75",
        inputLeft: 200,
        position: "label-left",
        labelHeight: 20,
        offsetTop: 25
      }, {
        type: "input",
        name: "firstname_sec",
        label: "FIRST NAME",
        required: true,
        labelWidth: 150,
        inputWidth: 213,
        offsetLeft: "80",
        inputLeft: 200,
        position: "label-left",
        labelHeight: 20
      }, {
        type: "input",
        name: "lastname_sec",
        label: "LAST NAME",
        required: true,
        labelWidth: 150,
        inputWidth: 213,
        offsetLeft: "80",
        inputLeft: 200,
        position: "label-left",
        labelHeight: 20
      }, {
        type: "block",
        width: 500,
        offsetLeft: "77",
        list: [

          {
            type: "radio",
            name: "gender_sec",
            label: "<a style='color: #76787b !important; cursor: pointer;font-weight: 500;'>MAN</a>",
            value: "male",

            position: "label-right",

            checked: "true"
          }, {
            type: "newcolumn"
          }, {
            type: "radio",
            name: "gender_sec",
            label: "<a style='color: #76787b !important; cursor: pointer;font-weight: 500;'>WOMAN</a>",
            value: "female",
            offsetLeft: "15",
            position: "label-right"

          },
        ]
      }, {
          type: "label",
          name: "form_label_1",
          label: "<span style='color: #009D8C;font-weight: bold;'>AGENCY INFORMATION</span>&nbsp;<span style='color: grey;font-size:9px;'>(IF YOUR AGENCY IS NOT LISTED, PLEASE CLICK <a id='NewAgency' class='join_now' href='javascript:void(0)'>HERE</a>)</span>",
          labelWidth: 420,
          inputWidth: 213,
          offsetLeft: "75",
          inputLeft: 200,
          position: "label-left",
          labelHeight: 28,
          offsetTop: 25
    }, {
        type: "select",
        name: "agency",
        label: "ADOPTION AGENCY",
        required: true,
        labelWidth: 150,
        inputWidth: 213,
        offsetLeft: "80",
        inputLeft: 200,
        position: "label-left",
        labelHeight: 20
      }, {
        type: "input",
        name: "agency_name",
        label: "ADOPTION AGENCY",
        required: true,
        labelWidth: 150,
        inputWidth: 213,
        offsetLeft: "80",
        inputLeft: 200,
        position: "label-left",
        labelHeight: 20
      }, {
        type: "select",
        name: "state",
        label: "STATE",
        required: true,		
        labelWidth: 150,
        inputWidth: 213,
        offsetLeft: "80",
        inputLeft: 200,
        position: "label-left",
        labelHeight: 20
      }, {
        type: "select",
        name: "region",
        label: "REGION",
        required: true,		
        labelWidth: 150,
        inputWidth: 213,
        offsetLeft: "80",
        inputLeft: 200,
        position: "label-left",
        labelHeight: 20
      }, {
        type: "label",
        name: "profilel_mess",
        label: "<span style='color: #009D8C;font-weight: bold;'>YOUR PERSONAL INFORMATION STAYS 100% ANONYMOUS <br/> AND YOUR ARE ONLY KNOW ON PARENTFINDER AS YOUR <br/> USERNAME</span> ",
        offsetLeft: "77",
        offsetTop:"20",
        labelHeight: 46
      }, {
        type: "block",
        width: 500,
        offsetLeft: "77",
        list: [ {        
        type: "checkbox",
        name: "termsofuse",
        label: "",
        value: "terms",
        position: "label-right",
        offsetLeft: "0",
        labelHeight: 20,
        offsetTop: 25
        },
        { type:"newcolumn"   },
        {
        type: "label",
        label: "<span style='color: #76787b !important; font-weight: 500;'>I HAVE READ AND AGREED WITH</span> <a href='terms_of_use.php' target='_blank' style='color:#009D8C'>TERMS OF USE</a>",
        offsetTop: 22
        }]
      }, {
        type: "block",
        width: 500,
        offsetLeft: "77",
        list: [{
          type: "button",
          value: "JOIN NOW",
          name: "join",
          width:"100",
          offsetTop: 25
        }, {
          type: "newcolumn"
        }, {
          type: "label",
          label: "<span style='color:#FF0000'>*</span><span style='color: #009D8C;'>REQUIRED</span>",
          offsetLeft: "200",
          offsetTop: 21
        }]
      }
    ]


  }


};