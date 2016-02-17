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
            label:"Upload PDF Profile",
        },{
            type: "fieldset",
            label: "Uploader",
            offsetLeft:"35",
            offsetTop:"30",
            list: [{
					type: "upload",
					name: "myFiles",
					inputWidth: "250",
					_swfLogs: "enabled",
					mode: "html4",  
					swfPath: "plugins/dhtmlx/dhtmlxForm/codebase/ext/uploader.swf"
									
				}]
        }, 
		/*
		{
          type: "input",
          name: "Description",
          label: "Description:",
          rows: 4,
          position: "label-top",
          offsetLeft: "35",
          offsetTop: "10",
          inputWidth:"350"
        },
        {
         type:"button", 
         name:"save", 
         width:80,
         offsetTop:10, 
          offsetLeft: "35",
         value:"SAVE"},
		 */
    ]
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