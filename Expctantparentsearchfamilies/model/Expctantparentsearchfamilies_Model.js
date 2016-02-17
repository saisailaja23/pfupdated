/***********************************************************************
 * Name:    Prashanth A
 * Date:    11/11/2013
 * Purpose: Creating a container to display families
 ***********************************************************************/
var Expctantparentsearchfamilies_Model = {

  // Defining layout pattern   
  "conf_layout": {
    "pattern": "1C"
  },
  // Creating a container
  "conf_form": {

    "template_profileview": [

      {
        type: "container",
        name: "data_container",
        label: "",
        inputWidth: 1010,
        inputHeight: 1900
      }
    ],

    "template_profileview1": [

      {
        type: "block",
        width: 900,
        list: [

          {
            type: "button",
            name: "Region",
            value: "<img src='templates/tmpl_par/images/btn_region.jpg' />",
            offsetLeft: "10"
          }, {
            type: "newcolumn"
          }, {
            type: "button",
            name: "noofchildren",
            value: "<img src='templates/tmpl_par/images/btn_family.jpg' />"
          }, {
            type: "newcolumn"
          }, {
            type: "button",
            name: "ChildEthnicity",
            value: "<img src='templates/tmpl_par/images/btn_ethnicity.jpg' />"
          }, {
            type: "newcolumn"
          }, {
            type: "button",
            name: "Religion",
            value: "<img src='templates/tmpl_par/images/btn_religion.jpg' />"
          }, {
            type: "newcolumn"
          }, {
            type: "button",
            name: "sortby",
            value: "<img src='templates/tmpl_par/images/btn_sort_click.jpg' />"
          }
        ]
      }
    ],

    "view_alert_temp": [{
      type: "label",
      name: "form_label_1",
      label: "Cannot save your favorities since you are not logged in.",
      offsetTop: "35",
      offsetLeft: "50"
    }, {
      type: "block",
      width: 500,
      offsetLeft: "100",
      list: [

        {
          type: "button",
          name: "createaccount",
          value: "Create Account",
          offsetLeft: "10"
        }, {
          type: "newcolumn"
        }, {
          type: "button",
          name: "login",
          value: "Login",
          offsetLeft: "10"
        }, {
          type: "newcolumn"
        }, {
          type: "button",
          name: "cancel",
          value: "No Thanks",
          offsetLeft: "10"
        }

      ]
    }]
  },
  "searchform": {
    "template_search": [{        
     type: "input",
          name: "search",
        //  label: "ADDRESS 2",
        value: "Search by name",
          position: "label-top",
        },
        {
              type: "newcolumn"
            },
        {
    type: "button",
              value: "Search",
              name: "search",
              className:"serachbutton" 
        }
    ]
    }
};