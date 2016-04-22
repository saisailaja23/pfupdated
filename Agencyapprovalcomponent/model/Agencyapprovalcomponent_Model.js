/***********************************************************************
 * Name:    Prashanth A
 * Date:    04/07/2014
 * Purpose: Creating a container to display families
 ***********************************************************************/
var Agencyapprovalcomponent_Model = {

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
        inputWidth: 1050,
        inputHeight: 500,
        offsetTop:30
      }
    ],

    "photo_pending": [ {
      type: "block",
      width: 500,
     
      list: [
  {type: "checkbox",
      name: "selectall", 
     label: "Select all",
     position:"label-right"
      
  },
   {
              type: "newcolumn"
            },
        {
          type: "button",
          name: "approve",
          value: "Approve",
           offsetLeft: 5
       
        },
        {
              type: "newcolumn"
            },
       {
          type: "button",
          name: "settings ",
          value: "Back to settings",
          offsetLeft: 5
       
        },

      ]
    }]


  }
};