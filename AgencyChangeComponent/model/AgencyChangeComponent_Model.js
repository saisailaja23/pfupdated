/***********************************************************************
 * Name:    Vishnu R
 * Date:    28/05/2014
 * Purpose: Change Password
 ***********************************************************************/
var AgencyChangeComponent_Model = {
    
    "conf_layout": {
        "pattern": "1C"
    },

    "conf_form": {
        "change_pass": [
            {type: "settings", position: "label-left", labelWidth: 180, inputWidth: 120, labelAlign: "left"},
       {
        type: "select",
        name: "agency",
        label: "ADOPTION AGENCY",
        required: true,
        labelWidth: 150,
        inputWidth: 213,       
        inputLeft: 200,
        position: "label-left",
        labelHeight: 30
      },
     {
      type: "input",
      rows: 50,
      name: "description",
      label: "REASON FOR CHANGE",
      labelWidth: 150, 
      labelLeft: 10,
      inputHeight: 50,
      inputWidth: 330,
      inputTop: 150
     },
    {
       type: "button",
       value: "SEND",
       style: "font-weight:normal;",
       name: "changepassword"
    },
        ]   
    }
};