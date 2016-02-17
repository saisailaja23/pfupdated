/***********************************************************************
 * Name:    Prashanth A
 * Date:    19/12/2013
 * Purpose: Creating agency like button
 ***********************************************************************/
var Familydashboardcomponent_Model = {

    // Defining layout pattern   
    "conf_layout": {
        "pattern": "1C"
    },
    // Creating agency like button
    "conf_form": {

        "template_agencyview": [{
                type: "button",
                name: "agencylike",
                value: "<div class=\"icoLike\" style=\"cursor:pointer; position: static;\"></div>",
                style: "font-weight:normal;",
                labelWidth: 215,
                labelHeight: 46
            }


        ],
    "template_agencylist": [


            {
                type: "container",
                name: "data_container",
                label: "",
                inputWidth: 660,
                inputHeight: 665
            },

        ],
      "change_pass": [

            {
               type: "password",
               name: "old_pass",
               inputWidth: 245,
               inputHeight: 25,
               offsetTop: "5"
            },
            {
                 type: "label",
                 label: "OLD PASSWORD",
                 labelWidth: 250

             },
             {
               type: "password",
               name: "new_pass",
               inputWidth: 245,
               inputHeight: 25,
               offsetTop: "5"
            },
            {
                 type: "label",
                 label: "NEW PASSWORD",
                 labelWidth: 250

             },
              {
               type: "password",
               name: "confirm_pass",
               inputWidth: 245,
               inputHeight: 25,
               offsetTop: "5"
            },
            {
                 type: "label",
                 label: "CONFIRM PASSWORD",
                 labelWidth: 250

             },
             {
                  type: "button",
                  value: "<img src='templates/tmpl_par/images/agency_save.png' />",
                  style: "font-weight:normal;",
                  name: "changepassword"
                },
        ]   
    }
};