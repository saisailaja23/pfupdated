var FlexPaper_Model = {
	//globalImgPath : "../../auxiliary/dhtmlxfull3.5/imgs/dhtmlxtoolbar_imgs/" // not mandatory, default "../codebase3.5/imgs/"
         globalImgPath : "../../plugins/dhtmlx/imgs/dhtmlxtoolbar_imgs/" // not mandatory, default "../codebase3.5/imgs/"

	,globalSkin : "dhx_skyblue" // not mandatory, default "dhx_skyblue"
	
	,text_labels : {
		main_window_title : "Document viewer"
		,main_layout_cell_a : "please hold on while the the pdf file is loading"
	}
	
	,conf_window : {
		image_path: ""
        ,viewport : vp
		,left: 300
		,top: 5
		,width: 700
		,height: 600
		,enableAutoViewport : false
		,icon : "file_viewer.png"
		,icon_dis : "file_viewer_dis.png"
	}

	,conf_toolbar : {
		icon_path: ""
		,items: [
			{
				type: "buttonSelect",
				id: "change_view",
				text: "change view",
				img: "change_view.png",
				img_disabled: "change_view.png",
				options: [
					{
						type: "obj",
						id: "view_html",
						text: "HTML view",
						//disabled: true,
						img: "html.png",
						img_disabled: "html.png"
					}
					,{
						type: "obj",
						id: "view_flash",
						text: "Flash view",
						//disabled: true,
						img: "swf.png",
						img_disabled: "swf.png"
					}
					,{
						type: "obj",
						id: "view_html5",
						text: "HTML5 view",
						//disabled: true,
						img: "html5.png",
						img_disabled: "html5.png"
					}
				]
			},{
				type: "button",
				id: "print_pdf",
				text: "print file",
				img: "print.png",
				img_disabled: "print.png"
				//,disabled : true
			},{
				type: "separator",
				id: "sep0"
			},{
				type: "slider",
				id: "slider_zoom",
				length: 70,
				value_min: 2,
				value_max: 50,
				value_now: 1.1,
				text_min: "zoom: min.",
				text_max: "max.",
				tip_template: "set zoom as %v"
			},{
				type: "separator",
				id: "sep1"
			}, {
				type: "text",
				id: "info",
				text: "search text:"
			},{
				type: "buttonInput",
				id: "text_to_search",
				text: "",
				width: 50
			},{
				type: "button",
				id: "search_text",
				text: "",
				img: "search.png",
				img_disabled: "search.png"
			},{
				type: "separator",
				id: "sep2"
			}
			,{
				type: "button",
				id: "close_viewer",
				text: "close window",
				img: "close.png",
				img_disabled: "close_dis.png"
			}
		]
	}
};



var FlexPaperComponent = {
	
	model : null
	,window_manager : null
	,window : []
	,layout : []
	,grid : []
	,toolbar : []
	,status_bar : []
	
	,configuration : []
	
	,selectedViewMode : []
	
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
		self.window_manager.attachViewportTo( self.model.conf_window.viewport ); // self.model.viewport
		self.window_manager.setImagePath( self.model.conf_window.image_path );
	}
	
	
	,_window : function( uid )
	{
		var self = this;
		if(self.window_manager === null)
			self._window_manager(  );
		
		if(self.window_manager.isWindow( "Flexpaper_wrapper_" + uid ))
		{
			self.window[ uid ].show();
			self.window[ uid ].bringToTop();
			self.window[ uid ].center();
			return;
		}
		
		self.window[ uid ] = self.window_manager.createWindow( "Flexpaper_wrapper_" + uid, 0, 0, (window.innerWidth - 10), (window.innerHeight - 10) );
		self.window[ uid ].setText( self.model.text_labels.main_window_title );
		self.window[ uid ].setIcon( self.model.conf_window.icon, self.model.conf_window.icon_dis );
		//self.window[ uid ].center();
		self.window[ uid ].denyPark();
                self.window[ uid ].setModal(true);
                self.window[ uid ].centerOnScreen();
                var pos = self.window[ uid ].getPosition();
                var offset = (document.body.scrollTop ? document.body.scrollTop : window.pageYOffset);
                self.window[ uid ].setPosition(pos[0], pos[1] + offset);
		self.window[ uid ].attachEvent("onClose", function(win){
			return true;
		});
		
		self.status_bar[ uid ] = self.window[ uid ].attachStatusBar();
		
		var templateToolbar_init = '<div class="status_barwindow">																										\
			<div class="status_text" id="Flexpaper_status_text_'+uid+'">loading pdf</div>																							\
			<div class="status_loading" id="Flexpaper_status_loading_'+uid+'"><img src="' + self.configuration[ uid ].application_url + '/imgs/processing.gif" width="180" /></div>		\
		</div>';

		self.status_bar[ uid ].setText( templateToolbar_init );
		self._getSelector( "Flexpaper_status_loading_"+uid+"" ).style.display = "block";
	}
	
	,_toolbar : function( uid )
	{
		var self = this;
		self.toolbar[ uid ] = self.window[ uid ].attachToolbar( self.model.conf_toolbar );
		self.toolbar[ uid ].setIconSize(32);
		
		self.toolbar[ uid ].setValue( "slider_zoom", ((new Number(self.configuration[ uid ].magnification)) * 10) );
		
		self.toolbar[ uid ].attachEvent("onValueChange", function(id, value)
		{
			self.layout[ uid ].cells("a").getFrame().contentWindow.getDocViewer("documentViewer").setZoom( value / 10 );
		});
		
		self.toolbar[ uid ].attachEvent("onClick", function(id)
		{   
			var templateToolbar_init = '<div class="status_barwindow">																										\
				<div class="status_text" id="Flexpaper_status_text_'+uid+'">loading pdf</div>	\
				<div class="status_loading" id="Flexpaper_status_loading_'+uid+'"><img src="' + self.configuration[ uid ].application_url + '/imgs/processing.gif" width="180" /></div>		\
			</div>';
			if( id == "view_html" )
			{
				self.status_bar[ uid ].setText( templateToolbar_init );
				self.selectedViewMode[ uid ] = 'html,flash';
				self.layout[ uid ].cells("a").attachURL( self.getFlexpaperURL( uid ) + self.configuration[ uid ].pdf_name );
				self.toolbar[ uid ].setValue( "slider_zoom", ((new Number(self.configuration[ uid ].magnification)) * 10) );
			}
			else if( id == "view_html5" )
			{
				self.status_bar[ uid ].setText( templateToolbar_init );
				self.selectedViewMode[ uid ] = 'html5,html,flash';
				self.layout[ uid ].cells("a").attachURL( self.getFlexpaperURL( uid ) + self.configuration[ uid ].pdf_name );
				self.toolbar[ uid ].setValue( "slider_zoom", ((new Number(self.configuration[ uid ].magnification)) * 10) );
			}
			else if( id == "view_flash" )
			{
				self.status_bar[ uid ].setText( templateToolbar_init );
				self.selectedViewMode[ uid ] = 'flash,html';
				self.layout[ uid ].cells("a").attachURL( self.getFlexpaperURL( uid ) + self.configuration[ uid ].pdf_name );
				self.toolbar[ uid ].setValue( "slider_zoom", ((new Number(self.configuration[ uid ].magnification)) * 10) );
			}
			else if( id == "search_text" )
			{
				self.layout[ uid ].cells("a").getFrame().contentWindow.getDocViewer("documentViewer").searchText( self.toolbar[ uid ].getValue("text_to_search") );
			}
			else if( id == "print_pdf" )
			{
				self.layout[ uid ].cells("a").getFrame().contentWindow.getDocViewer("documentViewer").printPaper();
			}
			else if( id == "close_viewer" )
			{
				//self.window[ uid ].hide();
                                self.window[ uid ].close();
				
			}
		});
	}
	
	,_layout : function( uid )
	{
		var self = this;
		self.layout[ uid ] = self.window[ uid ].attachLayout( "1C" );
		self.layout[ uid ].cells("a").setText( self.model.text_labels.main_layout_cell_a );
		
		self.layout[ uid ].cells("a").hideHeader();
		//console.log(self.configuration[ uid ].flexPaper_url);
		
		self.layout[ uid ].cells("a").attachURL( self.getFlexpaperURL( uid ) + self.configuration[ uid ].pdf_name );
		
		self.progressOn( uid );
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
		self.window[ uid ].progressOff();
		self.layout[ uid ].cells("a").progressOff();
		
		//dhtmlx.message( {text : "PDF loaded" } );
		self.layout[ uid ].cells("a").setText("");
		
		self._getSelector( "Flexpaper_status_loading_"+uid+"" ).style.display = "none";
		self._getSelector( "Flexpaper_status_text_"+uid+"" ).innerHTML = "PDF loaded";	
	}
	
	,getFlexpaperURL :function( uid )
	{
		var self = this, url, configuration = this.configuration[ uid ];
		
		if(configuration.split_mode)
		{
			url = configuration.application_url + 'php/split_document.php?pdf_storage='+ configuration.pdf_storage +'&ro='+self.selectedViewMode[ uid ]+'&scale='+configuration.magnification+'&application_path='+ configuration.application_path +'&uid='+ uid +'&doc='
		}
		else
		{
			url = configuration.application_url + 'php/simple_document.php?pdf_storage='+ configuration.pdf_storage +'&ro='+self.selectedViewMode[ uid ]+'&scale='+configuration.magnification+'&application_path='+ configuration.application_path +'&uid='+ uid +'&doc='
		}
		
		return url;
	}
	
	,callFlexPaper : function( configuration )
	{
		var self = this;
		
		if(! configuration )
		{
			alert("Configuration is missing ...");
			return;	
		}
		
		self.uid  = configuration.pdf_name;
		
		//console.log(self.uid);
		
		(typeof configuration.split_mode === 'undefined') ? configuration.split_mode = false: "";
		( (typeof configuration.magnification === 'undefined') || (!configuration.magnification) ) ? configuration.magnification = 1.1 : "";
		
		
		// configuration.magnification
		
		self.selectedViewMode[ self.uid ] = 'html,flash';
		
		//configuration.flexPaper_url = self.getFlexpaperURL( self.uid );
		
		self.configuration[ self.uid ] = configuration;	
		
		self.model.conf_window.image_path  = configuration.icons_path;
		self.model.conf_toolbar.icon_path = configuration.icons_path;
		
		window.dhx_globalImgPath = self.model.globalImgPath || "../codebase3.5/imgs/";
		dhtmlx.skin = self.model.globalSkin || "dhx_skyblue";
		
		
		self._window( self.uid );
		
		self._toolbar( self.uid );
		
		self._layout( self.uid );
		
		self._onResize( self.uid );
	}
	
	,_onResize : function( uid )
	{
		var self = this;
		window.onresize = function(event)
		{
			self.window[ uid ].setDimension( (window.innerWidth - 10), (window.innerHeight - 10) );
		}	
	}
	
	,init : function( model )
	{
		var self = this;
		self.model = model;
	}
};
FlexPaperComponent.init( FlexPaper_Model );