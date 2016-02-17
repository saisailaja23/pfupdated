/***********************************************************************
 * Name:    Prashanth A
 * Date:    11/11/2013
 * Purpose: Creating a container to display families liked by birth mother
 ***********************************************************************/
var Expctantparentfamilieslike_Model = {
  // Defining layout pattern   
  "conf_layout": {
    "pattern": "1C"
  },
  // Creating a container
  "conf_form_family": {

    "template_familyview": [{
      type: "container",
      name: "family_container",
      label: "",
      inputWidth: 1050,
      inputHeight: 1800
    }],
    "template_familiesbutton": [{
      type: "block",
      width: 780,
      list: [{
        type: "button",
        name: "sortby",
        value: "LIKES",
        className: "green-btn small-btn btn-style remove-color",
      }, {
        type: "newcolumn"
      }, {
        type: "button",
        name: "favourites",
        className: "pink-btn small-btn btn-style remove-color",
        value: "FAVORITES"
      }]
    }]
  }
};