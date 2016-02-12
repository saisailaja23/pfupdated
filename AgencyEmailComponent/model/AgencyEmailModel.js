/***********************************************************************
 * Name:    Sailaja S
 * Date:    2015/07/27
 * Purpose: Email configuration for Agency
 ***********************************************************************/
var AgencyEmailModel = {
    // Defining layout pattern   
    "conf_layout": {
        "pattern": "1C"
    },
    "conf_form": {        
        "MailConfig": [
            {type: "label", label: "please check the message boxes you dont want to receive", className: "topLabel"},
            {type: "checkbox", label: "When adoptive parent posts a new journal", checked: false, className: "mailLabel", value: "1", name: "BlogAdd" },
            {type: "checkbox", label: "When adoptive parent Edits a journal", checked: false, className: "mailLabel", value: "1", name: "BlogEdit" },
            {type: "checkbox", label: "When adoptive parent Deletes a journal", checked: false, className: "mailLabel", value: "1", name: "BlogDelete" },
            {type: "checkbox", label: "When adoptive parent adds a new photo", checked: false, className: "mailLabel", value: "2", name: "Photo" },
            {type: "checkbox", label: "When adoptive parent adds a new video", checked: false, className: "mailLabel", value: "3", name: "Video"  },
            {type: "checkbox", label: "When adoptive parent edit their profile", checked: false, className: "mailLabel", value: "4", name: "Editprofile"  },
                       
            {
              type: "button",
              value: "Save",
              style: "font-weight:normal;",
              name: "saveSettings",
              className: "saveSettings"
            }
        ]   
    }
};