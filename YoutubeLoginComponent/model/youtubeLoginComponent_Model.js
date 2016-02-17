/***********************************************************************
 * Name:    Sailaja S
 * Date:    2015/05/19
 * Purpose: Youtube Login for Agency
 ***********************************************************************/
var youtubeLoginComponent_Model = {
    // Defining layout pattern   
    "conf_layout": {
        "pattern": "1C"
    },
    "conf_form": {
        // Creating Login button
        "LogintoYoutube": [
            {type: "Label", labelWidth: 400, inputWidth: 120, labelAlign: "left"},
            {
              type: "label",
              className: "loggedinText",
              name: "loggedinText",
              label: "<span style='font-size:14px;'>You are already Logged In</span>"
            },
            
            {
              type: "button",
              value: "YoutubeLogin",
              style: "font-weight:normal;",
              name: "youtubelogin",
              className: "youtubeloginbtn"
            }
        ]   
    }
};