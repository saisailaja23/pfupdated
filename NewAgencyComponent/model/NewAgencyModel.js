/***********************************************
 * Name:    Satya
 * Date:    07/13/2014
 * Purpose: Creating a Agency Details Request form
 ************************************************/
//

function validateEmailaddress(data) {
    var x = data;// document.forms["myForm"]["email"].value;
    var atpos = x.indexOf("@");
    var dotpos = x.lastIndexOf(".");
    if (atpos< 1 || dotpos<atpos+2 || dotpos+2>=x.length) {
       return false;
    }return true;
}
function validateName(data) {
    var name_filter = /^[a-zA-Z ]{0,20}$/;
    var agency_name = data;
    if (!name_filter.test(agency_name))  
    {        
        return false;
    }
    return true; 
}


var NewAgency_Model = {
        "text_labels": {
            "main_window_title": ""
        },
        "globalSkin": "dhx_skyblue",
        "conf_window": {
            "image_path": "",
            "left": 510,
            "top": 200,
            "width": 550,
            "height": 410,
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
                    label: "Agency Details",
                    labelWidth: 450,
                    position: "label-left",
                    offsetTop: "15",
                    offsetLeft: "50"
                }, {
                    type:'label',
                    label: "<span class='loginProfileLabel' style='color: #009D8C;font-weight: bold;'>Please enter the following details to send a request to the agency to join Parent Finder.</span>",
                    labelHeight:50,
                    labelWidth: 410,
                    offsetLeft: 50
                },
                {
                    type: "label",
                    name: "aboutlabel",
                    label: "<span style='color: #009D8C;font-weight: bold;'>YOUR DETAILS</span>",
                    labelWidth: 150,
                    inputWidth: 213,
                    offsetLeft: "85",
                    inputLeft: 200,
                    position: "label-left",
                    labelHeight: 20,
                    offsetTop: 10
                },
                {
                    type: "input",
                    name: "fromName",
                    label: "YOUR NAME",
                    required: true,
                    labelWidth: 110,
                    inputWidth: 213,
                    offsetLeft: "90",
                    labelHeight: 20,
                    labelTop: 5,
                    offsetTop: "5",
                    validate: "validateName"
                },{
                    type: "input",
                    name: "yourEmail",
                    label: "YOUR EMAIL",
                    required: true,
                    labelWidth: 110,
                    inputWidth: 213,
                    offsetLeft: "90",
                    labelHeight: 20,
                    labelTop: 5,
                    validate: "validateEmailaddress"
                },
                {
                    type: "label",
                    name: "aboutlabel",
                    label: "<span style='color: #009D8C;font-weight: bold;'>AGENCY DETAILS</span>",
                    labelWidth: 150,
                    inputWidth: 213,
                    offsetLeft: "85",
                    inputLeft: 200,
                    position: "label-left",
                    labelHeight: 20,
                    offsetTop: 10
                },
                {
                    type: "input",
                    name: "agencyName",
                    label: "AGENCY NAME",
                    required: true,
                    labelWidth: 110,
                    inputWidth: 213,
                    offsetLeft: "90",
                    labelHeight: 20,
                    labelTop: 5,
                    offsetTop: "5",
                    validate: "validateName"
                }, {
                    type: "input",
                    name: "agencyEmail",
                    label: "AGENCY EMAIL",
                    required: true,
                    labelWidth: 110,
                    inputWidth: 213,
                    offsetLeft: "90",
                    labelHeight: 20,
                    labelTop: 5,
                    validate: "validateEmailaddress"
                }, {
                    type: "select",
                    name: "agencyState",
                    label: "AGENCY STATE",
                    required: true,
                    labelWidth: 110,
                    inputWidth: 213,
                    offsetLeft: "90",
                    labelHeight: 20,
                    labelTop: 5
                }, {
                    type: "block",
                    width: 500,
                    list: [{
                        type: "button",
                        name: "saveDet",
                        value: "SUBMIT&nbsp;",
                        className: "pink_dhx_btn",
                        offsetLeft: "200",
                        offsetTop: "10"
                    }]
                }]
            }
          };
