var ProfileImageCrop_Model = {
  "text_labels": {
    "main_window_title": "ProfileImageCrop"
  },
  "globalSkin": "dhx_skyblue",
  "conf_window": {
    "image_path": "",
    "viewport": "body",
    "left": 400,
    "top": 5,
    "width": 880,
    "height": 550,
    "enableAutoViewport": true,
    "icon": "form.png",
    "icon_dis": "form.png"
  },

  "conf_layout": {
    "pattern": "3L"
  },
  "conf_form": {
    "template": [{
      "uplader_form": [{
        type: "fieldset",
        name: "uplader_form",
        label: "Upload Your Image here",
        inputWidth: "auto",
        list: [{
          type: "upload",
          name: 'file',
          inputWidth: 350,
          inputHeight: 'auto',
          autoStart: true,
          label: 'Select a Picture',
          url: "processors/process.php?upload=ture",
          swfPath: "plugins/dhtmlx/dhtmlxForm/codebase/ext/uploader.swf",
        }]
      }],
      "img_crop_form_b": [{
        type: "fieldset",
        name: "img_crop_form",
        label: "Preview",
        inputWidth: "auto",
        list: [{
          type: "label",
          label: "Preview",
          name: "preview"
        }, {
          type: "hidden",
          label: "x-Axis:",
          name: 'x1',
          value: 0
        }, {
          type: "hidden",
          label: "y-Axis:",
          name: 'y1',
          value: 0
        }, {
          type: "hidden",
          label: "Width:",
          name: 'w',
          value: 300
        }, {
          type: "hidden",
          label: "Height:",
          name: 'h',
          value: 200
        }, {
          type: "hidden",
          label: "Name:",
          name: 'pic_name',
          value: "pic name"
        }, {
          type: "hidden",
          name: 'server'
        }, {
          type: "hidden",
          name: 'real'
        }, {
          type: "button",
          className: "crop_btn",
          name: 'crop',
          value: "crop"
        }]
      }]
    }]
  },
  "img_crop_window": {
    "width": 1000,
    "height": 600
  },
  "img_crop_layout": {
    "pattern": "3L"
  },
  "img_crop_layout_right": {
    "pattern": "2E"
  },
  "progress_window": {
    "width": 300,
    "height": 300,
    "window_title": "Loading...."
  },
  "progress_layout": {
    "pattern": "1C"
  }

};