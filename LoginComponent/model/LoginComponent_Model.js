/***********************************************
 * Name:    Prashanth A
 * Date:    07/11/2013
 * Purpose: Creating a user login form
 ************************************************/
var LoginComponent_Model = {
  "text_labels": {
    "main_window_title": ""
  },
  "globalSkin": "dhx_skyblue",
  "conf_window": {
    "image_path": "",
    "left": 510,
    "top": 200,
    "width": 550,
    "height": 300,
    "enableAutoViewport": true,
    "icon": "form.png",
    "icon_dis": "form.png"
  },

  "conf_layout": {
    "pattern": "1C"
  },
  "conf_form": {
    "template": [{
      type: "label",
      className: "popUpHeader LoginBox",
      name: "profilelabel_main",
      label: "LOGIN",
      labelWidth: 150,
      position: "label-left",
      offsetTop: "15"
    },{
      type: "label",
      name: "profilelabel_main1",
      label: "<span class='loginProfileLabel' style='color: #009D8C;font-weight: bold;'>PROFILE</span>",
      labelWidth: 50,offsetLeft: "88",
      position: "label-left",
      offsetTop: "5"
    }, {
      type: "input",
      name: "username",
      label: "USERNAME",
      required: true,
      labelWidth: 110,
      inputWidth: 213,
      offsetLeft: "90",
      labelHeight: 20,
      labelTop: 5,
      offsetTop: "5"
    }, {
      type: "password",
      name: "password",
      label: "PASSWORD",
      required: true,
      labelWidth: 110,
      inputWidth: 213,
      offsetLeft: "90",
      labelHeight: 20,
      labelTop: 5
    }, {
        type: "checkbox",
        name: "remember",
        label: "<a style='color: #76787b !important; cursor: pointer;font-weight: 500;'>REMEMBER ME</a>",
        labelWidth: 110,
        inputWidth: 213,
        offsetLeft: "90",
        labelHeight: 20,
        labelTop: 5
      }, 
    {
      type: "block",
      width: 500,
      list: [
    {
      type: "button",
      name: "login",
      value: "LOGIN",
      className: "pink_dhx_btn",
      offsetLeft: "201",
      width:'70'
    },
    {
        type: "newcolumn"
      }, {
        type: "button",
        className: "login-forget-link",
        name: "forgot",
        value: "<span class='forogt_pass'>FORGOT PASSWORD?</span>",
        offsetLeft: "18"
      },
     ]
    }, 
    {
      type: "block",
      width: "400",
      offsetTop:"30", 
      offsetLeft: "41",
      position:"absolute",
      list: [{
        type: "label",       
        name: "notamember",
        label: "<span class='not_member'>NOT A MEMBER YET?</span>",
        labelWidth: 140,
        position: "label-left",
        offsetTop: "15",
        offsetLeft: "156"
        }, {
          type: "newcolumn",
        }, {
          type: "button",
          className: "login-forget-link",
          name: "joinnow",
          value: "<span class='join_now'>JOIN NOW!</span>",
          offsetTop: "13",
          offsetLeft: "0"
      }]
    }
],
    "template_forgot": [{
      type: "label",
      className: "popUpHeader",
      name: "profilelabel_main",
      label: "FORGOT PASSWORD",
      labelWidth: 350,
      position: "label-left",
      offsetTop: "15"
    }, {
      type: "label",
      name: "profilelabel_main_info",
      label: "<span style='color: #77787A;font-weight: normal;'>Forgot your ID and/or password? No problem! please supply your email address below and you will be sent your Parentfinder account ID and password</span>",
      labelWidth: 450,
      position: "label-left",
      offsetLeft: "45",
      offsetTop: "15"
    }, {
      type: "block",
      width: 500,
      // offsetLeft: "75",
      list: [{
        type: "input",
        name: "email",
        label: "<span style='color: #009D8C;font-weight: bold;'>MY EMAIL</span>",
        required: true,
        labelWidth: 110,
        inputWidth: 213,
        offsetLeft: "50",
        labelHeight: 20,
        offsetTop: "20"
      }, {
        type: "newcolumn"
      }, {
        type: "button",
        name: "forgotpass",
        value: "<img src='templates/tmpl_par/images/btn_reset.png' />",
        offsetLeft: "15",
        offsetTop: "14"
      }]
    }]
  }
};