/***********************************************************************
 * Name:    Prashanth A
 * Date:    11/11/2013
 * Purpose: Creating a container to display families
 ***********************************************************************/
var LikeComp_Modle = {

  "view_alert_temp": [{
    type: "label",
    name: "form_label_1",
    label: "Cannot save your favorities since you are not logged in.",
    offsetTop: "35",
    offsetLeft: "55"
  }, {
    type: "block",
    width: 400,
    offsetLeft: "55",
    list: [{
        type: "button",
        name: "createaccount",
        value: "Create Account",
        className: "likeModBtn"
      }, {
        type: "newcolumn"
      }, {
        type: "button",
        name: "login",
        value: "Log in",
        className: "likeModBtn",
        offsetLeft: "6"
      }, {
        type: "newcolumn"
      }, {
        type: "button",
        name: "cancel",
        value: "No Thanks",
        className: "likeModBtn",
        offsetLeft: "6"

      }

    ]
  }]
};