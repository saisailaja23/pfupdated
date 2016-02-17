// JavaScript Document

var documentViewer_Model = {
	globalImgPath : "../codebase3.5/imgs/" // not mandatory, default "../codebase3.5/imgs/"
	,globalSkin : "dhx_skyblue" // not mandatory, default "dhx_skyblue"
	
	,text_labels : {
		main_window_title : "Document listing"
		,main_layout_cell_a : "please hold on while the files are processed"
	}
	
	,conf_window : {
		image_path: ""
        ,viewport : "body"
		,left: 400
		,top: 5
		,width: 600
		,height: 450
		,enableAutoViewport : true
		,icon : "file_viewer.png"
		,icon_dis : "file_viewer_dis.png"
	}
	
	,conf_grid : { 
		headers : "Document name,Description,Read confirmation,Docid"
		,widths : "170,250,110,5"
		,colaligns : "left,left,center,center"
		,coltypes : "ro,ro,ch,ro"
		,colsorting : "str,str,int,int"
	},

	conf_toolbar : {
		icon_path: ""
		,items: [
			{
				type: "button",
				id: "view_file",
				text: "view file",
				img: "view.png",
				img_disabled: "view_dis.png"
				,disabled : true
			},{
				type: "button",
				id: "download_file",
				text: "download file",
				img: "download.png",
				img_disabled: "download_dis.png"
				,disabled : true
			},{
				type: "button",
				id: "read_confirmation",
				text: "read confirmation",
				img: "confirmation.png",
				img_disabled: "confirmation_dis.png"
				,disabled : true
			},{
				type: "button",
				id: "print_file",
				text: "print file",
				img: "print.gif",
				img_disabled: "print_dis.gif"
				,disabled : true
			},{
				type: "separator",
				id: "sep2"
			},{
				type: "button",
				id: "help",
				text: "help",
				img: "help.png",
				img_disabled: "help.png"
			}
		]
	}
}


var documentViewer = {
	model : null
	,window_manager : null
	,window : []
	,layout : []
	,grid : []
	,toolbar : []
	,status_bar : []
	,window_help : []
	
	,window_browser : []
	,layout_browser : []
	,toolbar_browser : []
	,status_bar_browser : []
	
	,uid : null
	
	,configuration : []
	
	,_getSelector : function (id)
	{
		try
		{
			return document.getElementById(id);
		}
		catch(e)
		{
			return false;
		}
	}
	
	,_window_manager : function()
	{
		var self = this;
		//console.log( self.model );
		self.window_manager = new dhtmlXWindows();
		self.window_manager.enableAutoViewport( self.model.conf_window.enableAutoViewport );
		self.window_manager.attachViewportTo( document.body ); // self.model.viewport
		self.window_manager.setImagePath( self.model.conf_window.image_path );
	}
	
	
	,_window : function( uid )
	{
		var self = this;
		if(self.window_manager === null)
			self._window_manager(  );
		
		if(self.window_manager.isWindow( "document_viewer_" + uid ))
		{
			self.window[ uid ].show();
			self.window[ uid ].bringToTop();
			self.window[ uid ].center();
			return;
		}
		self.window[ uid ] = self.window_manager.createWindow( "document_viewer_" + uid, self.model.conf_window.left + 10, self.model.conf_window.top + 10, self.model.conf_window.width, self.model.conf_window.height );
		self.window[ uid ].setText( self.model.text_labels.main_window_title );
		self.window[ uid ].setIcon( self.model.conf_window.icon, self.model.conf_window.icon_dis );
		//self.window[ uid ].center();
		self.window[ uid ].denyPark();
		
		self.window[ uid ].attachEvent("onClose", function(win){
			self.layout[ uid ] = '';
			self.grid[ uid ] = '';
			self.toolbar[ uid ] = '';
			return true;
		});
		
		self.status_bar[ uid ] = self.window[ uid ].attachStatusBar();
		
		var templateToolbar_init = '<div class="status_barwindow">																										\
			<div class="status_text" id="status_text">processing files</div>																							\
			<div class="status_loading" id="status_loading"><img src="' + self.configuration[ uid ].application_url + '/imgs/processing.gif" width="180" /></div>		\
		</div>';
		
		self.status_bar[ uid ].setText( templateToolbar_init );
	}
	
	,_toolbar : function( uid )
	{
		var self = this;
		self.toolbar[ uid ] = self.window[ uid ].attachToolbar( self.model.conf_toolbar );
		
		self.toolbar[ uid ].attachEvent("onClick", function(id)
		{   			
			var printurl = window.location.href;
			var printarr = printurl.split("/");
			var printthis_web_path = printarr[0] + "//" + printarr[2];
			
			var printfile_path =  self.configuration[ uid ].pdf_storage;
			var printfull_file_path = printfile_path.split("/var/www/html");
			if( id == "view_file" )
			{
				self.viewFile( uid, self.getFile( uid, self.grid[ uid ].getSelectedRowId() ) );
			}
			else if( id == "download_file" )
			{
				var myWindow = window.open( printthis_web_path + "/users/filedownload.php?download=" + self.grid[ uid ].getSelectedRowId(), "Print window");
				//myWindow.focus();
    			//myWindow.print(); //DOES NOT WORK
				//myWindow.close();
			}
			else if( id == "read_confirmation" )
			{

				self.grid[ uid ].cells(self.grid[ uid ].getSelectedRowId(),2).setChecked(true);
				self.readConfirmation( uid,self.grid[ uid ].getSelectedRowId(),1);

			}
			else if( id == "print_file" )
			{
				

				var myWindow = window.open( printthis_web_path +printfull_file_path[1]+ escape( self.grid[ uid ].getSelectedRowId() ), "Print window");
				myWindow.focus();
    			
				
				myWindow.print();
				
				//myWindow.close();
			}
			else if( id == "help" )
			{
				self._showHelp( uid );
			}
		});
	}
	
	,_layout : function( uid )
	{
		var self = this;
		self.layout[ uid ] = self.window[ uid ].attachLayout( "1C" );
		self.layout[ uid ].cells("a").setText( self.model.text_labels.main_layout_cell_a );
		
		self.progressOn( uid );
	}
	
	,_grid : function( uid )
	{
		var self = this;
		
		self.grid[ uid ] = self.layout[ uid ].cells("a").attachGrid();
		
		self.grid[ uid ].selMultiRows = true;
		self.grid[ uid ].enableEditEvents(true,true,true);
		//self.grid[ uid ].enableLightMouseNavigation(true);
		
		self.grid[ uid ].setHeader( self.model.conf_grid.headers );
		self.grid[ uid ].setInitWidths( self.model.conf_grid.widths );
		self.grid[ uid ].setColAlign( self.model.conf_grid.colaligns );
		self.grid[ uid ].setColTypes( self.model.conf_grid.coltypes );
		self.grid[ uid ].setColSorting( self.model.conf_grid.colsorting );
		self.grid[ uid ].init();
		
		self.grid[ uid ].attachEvent("onRowSelect", function( id, ind )
		{
			self.enableButtons( uid );
		});
		
		self.grid[ uid ].attachEvent("onEditCell", function( stage, rowId, columnInd, newValue, oldValue )
		{
			if( stage === 2 )
			{

			}
			return true; // add this
		});
		
		self.grid[ uid ].attachEvent("onCheck", function( rowId, cellInd, state )
		{
			if(cellInd == "2")
			{

					self.grid[ uid ].selectRow(  self.grid[ uid ].getRowIndex(rowId), true, false, true);
					self.readConfirmation( uid ,rowId,state );	

			}
		});
		
		self.grid[ uid ].attachEvent("onEnter", function( rowId, cellInd )
		{
			self.viewFile( uid, self.getFile( uid, rowId ) );
		});
		
		self.grid[ uid ].attachEvent("onRowDblClicked", function( rowId, cellInd )
		{
			self.viewFile( uid, self.getFile( uid, rowId ) );
		});		
	}
	
	,getFile : function( uid, name )
	{
		var self = this;
		for(var x = 0; x < self.configuration[ uid ].files.length; x++)
		{
			var file = self.configuration[ uid ].files[x];
			if(file.name == name)
				return file;
		}
	}
	
	
	,readConfirmation : function( uid ,rowId,state )
	{
		var self = this;
		var file_name = self.grid[ uid ].getSelectedRowId();
		var cellObj = self.grid[ uid ].cells(file_name, 2);
		//cellObj.setChecked(true);
			
		if(self.configuration[ uid ].onReadFile)
		{
			var response = {
				state : state
				,phaseid : self.phase_id
				,stageid : self.stage_id
				,taskid : self.task_id
				,docid : self.grid[ uid ].cells(rowId,3).getValue()
				,userid : self.parent_id
				,agencycwid : self.parent_id
				,user_id : self.uid
				,case_id : self.clientcase_id				
			};
			self.configuration[ uid ].onReadFile(response);
		}
		
		if(self.configuration[ uid ].onReadAllFiles)
		{
			self.checkIfAllFilesWasRead( uid );
		}	
	}
	
	,checkIfAllFilesWasRead : function( uid )
	{
		var self = this;
		if(self.configuration[ uid ].onReadAllFiles)
		{
			if( self.configuration[ uid ].files.length == self.grid[ uid ].getCheckedRows(2).split(",").length )
			{
				var response = {
						 user_id : self.uid
						,phaseid : self.phase_id
						,stageid : self.stage_id
						,taskid : self.task_id	
						,agency_id : self.agency_id
						,case_id : self.clientcase_id
						,module  : self.module
						,Status  : "Done"
				};
				self.configuration[ uid ].onReadAllFiles(response);
				self.window[uid].close();
			}
			else
			{
								var response = {
						 user_id : self.uid
						,phaseid : self.phase_id
						,stageid : self.stage_id
						,taskid : self.task_id	
						,agency_id : self.agency_id
						,case_id : self.clientcase_id
						,module  : self.module
						,Status  : "New"
				};
				self.configuration[ uid ].onReadAllFiles(response);
				dhtmlx.message("The task will not be submitted until you check read on all documents");	
			}
		}
	}
	
	,viewFile : function( uid, file )
	{
		var self = this;
		
		var fileName = file.title;
		
		var ndots = (file.title.split(".").length - 1);
		
		//var type = file.title.split(".")[ndots].toLowerCase();
			
		//dhtmlx.message(type);
		
		if(file.name.indexOf(".pdf") != -1)
		{
			self.renderWithFlexpaper( uid, file );
		}
		else
		{
			self.renderWithBrowser( uid, file );
		}
	}
	
	,renderWithFlexpaper : function( uid, file )
	{
		var self = this;
	
		var splitMode = false;
		
		if(file.split_mode)
		{
			splitMode = file.split_mode
		}
		else
		{
			if(self.configuration[ uid ].split_mode)
			{
				splitMode = self.configuration[ uid ].split_mode;
			}
		}
		

			FlexPaperComponent.callFlexPaper(
			{
				//uid : file.name // mandatory
				icons_path : 'auxiliary/documentViewer/icons/32px/' // mandatory
				,application_url : self.configuration[ uid ].application_url // mandatory
				,application_path : self.configuration[ uid ].application_path // mandatory
				,pdf_storage : self.configuration[ uid ].pdf_storage // mandatory
				,pdf_name : file.name.replace('%40', '@') // mandatory
				,split_mode : splitMode // not mandatory
				,magnification : self.configuration[ uid ].magnification  // not mandatory, default 1.1
			});	

	}
	
	,renderWithBrowser : function( uid, file )
	{
		var self = this;
		
		if(self.window_manager.isWindow( "browser_window_" + uid ))
		{
				self.window_browser[ uid ].show();
				self.window_browser[ uid ].bringToTop();
				return;
		}
		
					var url = window.location.href;
			var arr = url.split("/");
			var this_web_path = arr[0] + "//" + arr[2];
			
			var file_path =  self.configuration[ uid ].pdf_storage;
			var full_file_path = file_path.split("/var/www/html");
		self.window_browser[ uid ] = self.window_manager.createWindow( "browser_window_" + uid, self.model.conf_window.left + 10, self.model.conf_window.top + 10, 700, 400 );
		self.window_browser[ uid ].setText( "Document Viewer" );
		self.window_browser[ uid ].setIcon( "help.png", "help_dis.png" );

		self.window_browser[ uid ].attachURL( this_web_path +full_file_path[1]+ file.name );
	
	}
	
	,enableButtons : function( uid )
	{
		var self = this;
		
		self.toolbar[ uid ].enableItem("view_file");
		self.toolbar[ uid ].enableItem("print_file");
		self.toolbar[ uid ].enableItem("download_file");
		if(self.approval_status != 1){
		self.toolbar[ uid ].enableItem("read_confirmation");
		}
	}
	
	,progressOn : function( uid )
	{
		var self = this;
		self.window[ uid ].progressOn();
		self.layout[ uid ].cells("a").progressOn();	
	}
	
	,progressOff : function( uid )
	{
		var self = this;
		self.sleep(3000);
		self.window[ uid ].progressOff();
		self.layout[ uid ].cells("a").progressOff();	
	}
	,sleep : function(milliseconds) {
	  var start = new Date().getTime();
	  for (var i = 0; i < 1e7; i++) {
		if ((new Date().getTime() - start) > milliseconds){
		  break;
		}
	  }
	}	
	,_addRow : function(files)
	{
		var self = this, uid = self.uid, processed_status = 0;
		if(! files)
		{
			return;
		}
		for(var x = 0; x < files.length; x++)
		{
			var file = files[x];			
			//(file.processed_status) ? processed_status = 'processed' : processed_status = '<img src="' + self.configuration[ uid ].application_url + '/imgs/processing.gif" width="95%" />';
			self.grid[ uid ].addRow( file.name, [ file.title, file.description, file.read,file.documentid ] ); // , file.ind	
			if(self.approval_status == 1){
				self.grid[ uid ].cells(file.name,2).setDisabled(true)
			}
			if( x == (files.length -1) )
			{
				self.grid[ uid ].setSizes(); // call setSizes to reset sizes of grid elements.		
				//self.sync_files( uid, files );
			}
		}
		self.grid[ uid ].setColumnHidden(3,true); 
		self.progressOff( uid );
		self.layout[ uid ].cells("a").setText("Please give double click or press enter over a file to view it");
		self._getSelector( "status_loading" ).style.display = "none";
		self._getSelector( "status_text" ).innerHTML = "Files processed";

	}
	
	
	,sync_files : function( uid, files )
	{
		var self = this;	
		
		var stringFiles = JSON.stringify(files);
		var params = "pdf_storage=" + encodeURI( self.configuration[ uid ].pdf_storage ) + "";
		params = params + "&files=" + encodeURI( stringFiles ) + "";
		dhtmlxAjax.post( self.configuration[ uid ].application_url + "processors/sync_files.pl", params, function(loader)
		{
			try
			{							
				var json = JSON.parse( loader.xmlDoc.responseText );
				if( json.status == "success" )	
				{
					//dhtmlx.message( {text : "Files processed" } );
					self.layout[ uid ].cells("a").setText("Please give double click or press enter over a file to view it");
					self.progressOff( uid );
					
					self._getSelector( "status_loading" ).style.display = "none";
					self._getSelector( "status_text" ).innerHTML = "Files processed";
					
					
				}
				else
				{
					dhtmlx.message( {type : "error", text : json.response} );
					self.layout[ uid ].cells("a").setText(json.response);
					self.progressOff( uid );
				}
			}
			catch(e)
			{
				dhtmlx.message( {type : "error", text : "Fatal error on server side: "+loader.xmlDoc.responseText } );
				self.layout[ uid ].cells("a").setText("Fatal error on server side");				
				self.progressOff( uid );
			}
		});	
	}
	
	,_showHelp : function( uid )
	{
		var self = this;
		
		if(self.window_manager.isWindow( "help_window_" + uid ))
		{
				self.window_help[ uid ].show();
				self.window_help[ uid ].bringToTop();
				return;
		}
		
		
		self.window_help[ uid ] = self.window_manager.createWindow( "help_window_" + uid, self.model.conf_window.left + 10, self.model.conf_window.top + 10, 700, 400 );
		self.window_help[ uid ].setText( "End user manual" );
		self.window_help[ uid ].setIcon( "help.png", "help_dis.png" );

		self.window_help[ uid ].attachURL( self.configuration[ uid ].application_url + "docs/end_user_manual/" );
	}
	
	,startViewer : function( configuration )
	{
		var self = this;
		
		if(! configuration )
		{
			alert("Configuration is missing ...");
			return;	
		}

		
		
		self.uid  = configuration.uid;
		self.parent_id  = configuration.parent_id;
		self.agency_id  = configuration.agency_id;
		self.phase_id  = configuration.phase_id;		
		self.stage_id  = configuration.stage_id;
		self.task_id  = configuration.task_id;
		self.clientcase_id  = configuration.clientcase_id;
		self.cclientConnId  = configuration.cclientConnId;
		self.agency_id = configuration.agency_id;
		self.approval_status = configuration.approval_status;
		self.module   = configuration.module;

		if( (typeof configuration.magnification === 'undefined') || (!configuration.magnification) )
		{
			configuration.magnification = 1.1;
		}
		
		
		self.configuration[ self.uid ] = configuration;
		self.model.conf_window.image_path  = configuration.icons_path;
		self.model.conf_toolbar.icon_path = configuration.icons_path;
		
		CAIRS.init();
		self.model.conf_window.top  = ((CAIRS.windowHeight / 2) - 183);
		self.model.conf_toolbar.left = ((CAIRS.windowWidth / 2) - 250);
		
		self._window( self.uid );
		self._toolbar( self.uid );
		self._layout( self.uid );
		self._grid( self.uid );
		self._addRow( configuration.files );
	}
	
	,init : function( model )
	{
		var self = this;
		self.model = model;
	}
};
documentViewer.init( documentViewer_Model );