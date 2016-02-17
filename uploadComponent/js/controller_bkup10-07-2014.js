var uploadController = {
    /* properties */
    window_manager : null
    ,
    windows : []
    ,
    popup_manager : null
    ,
    popup : []
    ,
    layout: []
    ,
    form: []
    ,
    id: null
    ,
    autoStart:false
    ,
    serverresponse: null
    ,
    formFields : []
    ,
    formFields_tofill : []
    ,
    formFields_filled : []
    ,
    configuration : []
    ,
    formats : []
    ,
    Message :  []
    ,
    SWFURL      :   []
    ,
    NumFiles      :   []
    ,
    uploadURL  :   []
    ,
    errorType  :   []
    ,
    events  :   []
    ,
    flag:   []
    ,
    myUploader: null
    ,
    filename : null
    ,
    fileCount : null



    /* methods */
    ,
    _popup_manager : function()
    {
        var self = this,uid = self.uid;
        self.windows[uid] = new dhtmlXPopup();
    }
    ,
    _popup :  function(obj) {

        var self = this,
        uid = self.uid;
        try{
            if(self.popup_manager === null) {
                self._popup_manager();
            }
            if(self.popup[uid] == 'undefined')
                self.popup[uid] = [];
            var x = getAbsoluteLeft(obj);
            var y = getAbsoluteTop(obj);
            var w = obj.offsetWidth;
            var h = obj.offsetHeight;
            self.windows[uid].show(x, y, w, h);
            self.windows[uid].attachEvent("onHide",function(){
                delete self.windows[uid];
//                self.callEvent('onClose')
                return true;
            })

        }catch(e)
        {
            self.Message[1]   =   'error';
            self.Message[2]   =  "error on create popup" + "\n" + e.stack ;
            self._MessageDisplay();
        }

    }
    ,
    _window_manager : function()
    {
        var self = this;
        self.window_manager = new dhtmlXWindows();
        self.window_manager.setImagePath( self.model.model_globalImgPath);
    }
    ,
    _window :  function(uid,winWidth,winHeight,winTop,winTitle) {

        var self = this;
        uid = self.uid;
        try{
            if(self.window_manager === null) {
                self._window_manager();
            }
            if(self.windows[uid] == 'undefined')
                self.windows[uid] = [];
            self.windows[uid] = self.window_manager.createWindow( 'Win'+uid, '0', '0', 500, 250);
            var settittelcontent = winTitle.replace("^","&");
            self.windows[uid].setText(settittelcontent);
            self.windows[uid].setModal(true);
            self.windows[uid].button('park').hide()
            self.windows[uid].button('minmax1').hide()
            self.windows[uid].center();
            self.windows[ uid ].setIcon( self.model.model_conf_window, self.model.model_conf_window );
            self.windows[ uid ].centerOnScreen();
              self.windows[ uid ].attachEvent("onClose", function(win){
               delete self.windows[uid];
               return true;
            });

        }catch(e)
        {
            self.Message[1]   =   'error';
            self.Message[2]   =  "error on create window" + "\n" + e.stack ;
            self._MessageDisplay();
        }

    }

    // layout
    ,
    _layout: function(){
        var self = this,uid = self.uid;
        try{
            if(self.WinOrPopup == true){
                self.layout[uid] = self.windows[uid].attachLayout(400, 200, "1C");
            }else
                self.layout[uid] = self.windows[ uid ].attachLayout(self.model.model_Layout.skin);
            self.layout[uid].cells("a").hideHeader();
        }catch(e)
        {
            self.Message[1]   =   'error';
            self.Message[2]   =  "error on create layout" + "\n" + e.stack ;
            self._MessageDisplay();
        }
    }
    ,
    _form: function()
    {
        var self = this,  uid = self.uid;

        try{

            self.form[ uid ] = self.layout[uid].cells("a").attachForm(self.model.model_formdata.template);

            //setting upload path
            self.myUploader = self.form[ uid ].getUploader("myFiles");
            self.myUploader.setURL(self.uploadURL);
            self.myUploader.setSWFURL(self.SWFURL);
            self.myUploader.setAutoStart(self.autoStart);
            self.fileCount =    0;
            self. _progressbarOff();
            self._uploadEvents();
        }catch(e)
        {
            self.Message[1]   =   'error';
            self.Message[2]   =  "error on create form" + "\n" + e.stack ;
            self._MessageDisplay();
        }
    }
    ,
    _progressbarOn: function(id)
    {
        var self = this, uid = self.uid;
        self.layout[ uid ].cells("a").progressOn();

    }
    ,
    _progressbarOff: function(id)
    {
        var self = this, uid = self.uid;
        self.layout[ uid ].cells("a").progressOff();

    }
    ,
    _uploadEvents: function()
    {
        var self = this,  uid = self.uid;
        self.myUploader = self.form[ uid ].getUploader("myFiles");

        //validate type of file and ceck num of files
        self.form[ uid ].attachEvent("onFileAdd",function(realName){
            self. _progressbarOn();
//            self.fileCount = self.fileCount +   1;
//            if(self.fileCount > self.NumFiles)
//            {
//                self.form[ uid ].disableItem("myFiles");
//                self.myUploader.abortManually();
//            }
            var extnsn  =   realName.split(".");
            for(var i=0; i<self.formats.length; i++) {
                if(extnsn[extnsn.length-1].toLowerCase() != self.formats[i].toLowerCase()){
                    self.flag[realName] = false;
                }else{
                    self.flag[realName] = true;
                    break;
                }
            }
            if(self.flag[realName] == false){
//                self.fileCount = self.fileCount -   1;
//                self.myUploader.abortManually();
                self.Message[1]   =   'error';
               // self.Message[2]   =   "<h4>Upload "+self.formats+" formats of profile</h4>";
                 dhtmlx.message({
                type: "alert-error",
                text:"<h4>Upload "+self.formats+" formats of profile</h4>"
            });
              //  self._MessageDisplay();
                self. _progressbarOff();
                return false;
            }
            self. _progressbarOff();
        });

        //show erroe on fail upload
        self.form[ uid ].attachEvent("onUploadFail",function(realName,response){
          /*  self. _progressbarOn();
            self.flag[realName] = false;
//            self.fileCount = self.fileCount -   1;
//            self.myUploader.abortManually();
            var serverresponseObj = eval(response);
            if(typeof serverresponseObj == "undefined" || typeof serverresponseObj.state == "undefined"){
                self.Message[1]     =   'error';
                self.Message[2]     =   "invalid response from server";
            }else{
                self.serverresponse =  response;
                self.Message[1]     =   'error';
                self.Message[2]     =   self.model.model_failMessage+serverresponseObj['responseFromServer'];               
            }
            self.form[ uid ].enableItem("myFiles");
            self._MessageDisplay();
           
            self. _progressbarOff();*/
//            self.callEvent('onuploadFail',[response,realName]);
        });

        //check number of files
        self.form[ uid ].attachEvent("onUploadComplete",function(count){
            self. _progressbarOn();
            if(self.fileCount < self.NumFiles)
            {
                self.Message[1]   =   'sucess';
                self.Message[2]   =   self.model.model_succesMessage;
                self._MessageDisplay();
                self. _progressbarOff();
                self.windows[uid].close();
            }else{
                self.form[ uid ].disableItem("myFiles");
                self.onCallBack (self.model.model_succesMessage);
                self. _progressbarOff();
                self.windows[uid].close();

            }
//            self.callEvent('onuploadComplete',[self.model.model_succesMessage,self.fileCount]);
        });


       /* self.form[ uid ].attachEvent("onFileRemove",function(realName,serverName){
            if(self.flag[realName] == true){
                self. _progressbarOn();
                dhtmlxAjax.post("test.php","fileName="+encodeURI(realName), function(loader){
                    self.fileCount = self.fileCount -   1;
                    self. _progressbarOff();
                }); 
            }
        });*/

    },

    _MessageDisplay: function()
    {
        var self = this,  uid = self.uid;

        if(self.errorType != '' && self.errorType == true && self.WinOrPopup != true){
            dhtmlx.alert(self.Message[2]);
        }else if(self.Message[1] == 'sucess'){
            dhtmlx.message({type:"alert-error",text:self.Message[2]});
        }else{
            dhtmlx.message({
                type:"alert-error",
                text:self.Message[2]
            });
//            self.callEvent('onError',[self.Message[2]]);
        }
    },
    loadValues : function(c) {
        var self = this;
        self.window_id          =     c.window_id;
        self.win_title          =     c.win_title;
        self.onCallBack         =     c.onCallBack;
        self.formats            =     c.upload_formats.split(",");//split(c.upload_formats);
        self.errorType          =     (c.msgOrAlert == true)?true:false;
        self.SWFURL             =     c.SWFURL;
        self.NumFiles           =     (isNaN(c.numberOfFiles) == true)?100:c.numberOfFiles;
        self.uploadURL          =     c.uploadURL;
        self.WinOrPopup         =     (c.popup == true)?c.popup:false;
        self.object             =     c.clickObj;
        self.autoStart          =     (c.autoStart == true)?c.autoStart:false;

    }

    ,
    initComponent : function() {
        var self = this,uid = self.uid;
        if(typeof self.windows[uid] =='object')
        {
            if(self.WinOrPopup == true){
                self.windows[uid].hide();
            }
            return
        }
        if(self.WinOrPopup == true){
            self._popup(self.object);
        }else
            self._window(self.window_id,700,500,100,self.win_title);
        self._layout();
        self. _progressbarOn();
        self._form();
    }

    ,
    Modelinit : function( Model_upload )
    {
        var self = this;
        self.model = Model_upload;

    },
    attachEvent : function( a,b )
    {
        var self = this, uid = self.uid;
        self.events[a] = b;
        

    },
    detachEvent : function( a,b )
    {
        var self = this, uid = self.uid;
        delete self.events[a];
        if (typeof b != 'undefined') {
            b();
        }
        

    },
    callEvent : function (a,b){
        var self = this, uid = self.uid;
        if (typeof self.events[a] != 'undefined') {
            self.events[a](b);
        }
    }

};
uploadController.Modelinit( Model_upload );