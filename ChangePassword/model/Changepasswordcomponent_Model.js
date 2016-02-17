/***********************************************************************
 * Name:    Vishnu R
 * Date:    28/05/2014
 * Purpose: Change Password
 ***********************************************************************/
var changePassword_Model = {
    // Defining layout pattern   
    "conf_layout": {
        "pattern": "1C"
    },
    // Creating agency like button
    "conf_form": {
        "change_pass": [
            {type: "settings", position: "label-left", labelWidth: 180, inputWidth: 120, labelAlign: "left"},
//            {
//               type: "password",
//               name: "old_pass",
//               className: "lbl",
//               inputWidth: 245,
//               inputHeight: 25,
//               label:"OLD PASSWORD",
//               offsetTop: "5"
//            },
            
             {
               type: "password",
               name: "new_pass",
               style: "font-weight:normal;",
               inputWidth: 245,
               inputHeight: 25,
               label: "NEW PASSWORD",
               offsetTop: "5"
            },
            
              {
               type: "password",
               name: "confirm_pass",
               style: "font-weight:normal;",
               label: "CONFIRM PASSWORD",
               inputWidth: 245,
               inputHeight: 25,
               offsetTop: "5"
            },
             {
                  type: "button",                  
                  value: "SAVE",
                  className: "SavePwd",
                  style: "font-weight:normal;",
                  name: "changepassword"
                },
        ]   
    }
};