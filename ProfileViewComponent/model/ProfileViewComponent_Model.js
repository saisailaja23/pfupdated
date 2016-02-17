/***********************************************************************
 * Name:    Prashanth A
 * Date:    11/11/2013
 * Purpose: Creating a family profile builder form
 ***********************************************************************/
var ProfileViewComponent_Model = {
  // Defining layout pattern   
  "conf_layout": {
    "pattern": "1C"
  },
  // Creating family profile builder form
  "conf_form": {
    "template_profileview": [{
        type: "input",
        name: "journalentry",
        cols: 60,
        rows: 8,
        style: "width:350px"
      }, {
        type: "button",
        name: "journalsubmit",
        value: "SUBMIT",
        style: "font-weight:normal;",
        labelWidth: 215
      }


    ],
    "letter_like": [{
      type: "button",
      name: "agencylike",
      value: "<img src='templates/tmpl_par/images/ico_like.png' />",
      style: "font-weight:normal;",
      labelWidth: 215
    }],


    "change_pass": [{
        type: "label",
        className: "popUpHeader",
        name: "profilelabel_main",
        label: "CHANGE PASSWORD",
        labelWidth: 350,
        position: "label-left",
        offsetTop: "20"
      }, {

        type: "password",
        name: "old_pass",
        label: "OLD PASSWORD",
        inputHeight: 20,
        required: true,
        labelWidth: 150,
        inputWidth: 213,
        offsetLeft: "60",
        labelHeight: 20,
        offsetTop: "10"

      },

      {


        type: "password",
        name: "new_pass",
        label: "NEW PASSWORD",
        required: true,
        inputHeight: 20,
        labelWidth: 150,
        inputWidth: 213,
        offsetLeft: "60",
        labelHeight: 20,
        labelTop: 5,
        offsetTop: "10"


      },

      {

        type: "password",
        name: "confirm_pass",
        label: "CONFIRM PASSWORD",
        required: true,
        labelWidth: 150,
        inputHeight: 20,
        inputWidth: 213,
        offsetLeft: "60",
        labelHeight: 20,
        labelTop: 5,
        offsetTop: "10"

      },

      {
        type: "button",
        value: "Save",
        style: "font-weight:normal;",
        name: "changepassword",
        offsetLeft: "210",
        offsetTop: "5"

      },
    ]

  }
};