//Model for add contact
var Model_upload = {
	

    model_globalImgPath : "../dhtmlxfull3.5/imgs/dhtmlxtoolbar_imgs/"
    ,
    dhtmlx_codebase_path: "../dhtmlxfull3.5/"
    ,
    model_globalSkin : "dhx_skyblue" 
    ,
    model_succesMessage : "<b><h4>Profile Uploaded Successfully..!</b> </h4>"
    ,
    model_failMessage   :  "<b>Upload Fail:<br><b>Reason : </b>"
    ,
    model_formdata : {
        template : [{
            type:"label" , 
            name:"form_label_1", 
            label:"Upload profile in PDF format"
        },{
            type: "fieldset",
            label: "Uploader",
            offsetLeft:"65",
            offsetTop:"30",
            list: [{
                type: "upload",
                name: "myFiles",
                inputWidth: 330,
                _swfLogs: "enabled",
                mode: "html4",  
                swfPath: "plugins/dhtmlx/dhtmlxForm/codebase/ext/uploader.swf"
                            
            }]
        }]
    }   
    ,
    model_Layout: {
        skin: '1C'
    }
    ,
    conf_layout : {
        pattern : "1C",
		
    }	
};