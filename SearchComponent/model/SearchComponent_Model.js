/*******************************************************
 * Name:    Prashanth A
 * Date:    11/11/2013
 * Purpose: Creating a form to search and disply users
 *******************************************************/
var SearchComponent_Model = {
  "conf_form": {
    "template_search": [{
      type: "button",
      name: "search",
      label: "",
      value: "<img src='templates/tmpl_par/images/viewfamilies.png' />",
      style: "color: #FFF;border: 2px solid #FFF;font-weight: bold;font-size:12px;",
      labelWidth: 150,
      inputWidth: 198,
      inputHeight: 25,
      labelLeft: 5,
      labelTop: 5,
      inputLeft: 200,
      position: "label-left",
      labelHeight: 20
    }],
    "template": [{
      type: "container",
      name: "data_container",
      label: "",
      inputWidth: 200,
      inputHeight: 635,
      style: "-ms-overflow-y: auto;overflow-y: auto"
    }]
  }
};