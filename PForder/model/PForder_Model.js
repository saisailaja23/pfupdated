/***********************************************************************
 * Name:    Eswar N
 * Date:    17/07/2014
 * Purpose: Creating a order list page Model
 ***********************************************************************/
var PForder_Model = {
    "text_labels": {
        "main_window_title": ""
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
    "conf_grid": {
        "headers": "ID",
        "ids": "id",
        "widths": "90",
        "colaligns": "right",
        "coltypes": "ro",
        "colsorting": "int",
        "bind_library_field": "false"
    },
    "conf_toolbar": {
        "icon_path": "",
        "items": [
            {
                "type": "button",
                "id": "button",
                "text": "button",
                "img": "save.gif",
                "img_disabled": "save.gif"
            }
        ]
    },
    "conf_layout": {
        "pattern": "1C"
    },
    "conf_form": {
        "template": []
    }
};