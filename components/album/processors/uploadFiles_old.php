<?php
    $albumID        = $_GET['albumID'];
    $connectionID   = $_GET['connectionID'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"> 
     
        <title>Upload</title>
		<link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/style_upload.css" rel="stylesheet">
        <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/base/jquery-ui.css" type="text/css" />
		<link rel="stylesheet" href="../css/jquery.ui.plupload.css" type="text/css" />
        
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>

		<!-- production -->
		<script type="text/javascript" src="../js/plupload.full.min.js"></script>
		<script type="text/javascript" src="../js/jquery.ui.plupload.js"></script>
        
        <script>

		$( document ).ready(function() {

			var content = $('#mainpane');
			var loading = $("<div id='loading' class='spinner overlay'>Loading</div>");
			$( ".pane-link" ).on( "click" , function(e) {
				e.preventDefault();
				content.html(loading);
//				content.append(loading);
				try
				{
					$("#services li").removeClass("active");
					$(this).addClass("active");
				
					 var value = $(this).data('client');
				
					$.ajax({
						url: "api/"+value,
						//cache: false
					})
					.done(function( html ) {
						//console.log(html);
						content.html( html );
					})
					.fail(function(e) {
						//console.log( e );
					})
					.always(function() {
						//alert( "complete" );
					});
				}
				catch(e)
				{
					console.log(e.stack);
				}

			 });

			 $( ".directory" ).on( "click" , function(e) {
				e.preventDefault();
				content.html(loading);
				try
				{
					var url = $(this).attr("href");
				
					$.ajax({
						url: url,
						//cache: false
					})
					.done(function( html ) {
						content.html( html );
					});
				}
				catch(e)
				{
					console.log(e.stack);
				}

			 });
			 

			 //$("[data-client=computer]").click();
			 
// upload -=-=-=-=-=-=-=-=-
			$("#uploader").plupload({
				// General settings
				runtimes : 'html5,flash,silverlight,html4',
				url : 'api/computer/upload.php',
				multipart_params : {
                                            "albumID" : '<?=$albumID;?>',
                                            "connectionID" : '<?=$connectionID;?>'
                                    },
                                    // User can upload no more then 20 files in one go (sets multiple_queues to false)
				max_file_count: 50,
		
				chunk_size: '1mb',
		
				filters : {
					// Maximum file size
					max_file_size : '1000mb',
					// Specify what files to browse for
					mime_types: [
						{title : "Image files", extensions : "jpg,gif,png,tiff,jpeg"},
						{title : "Zip files", extensions : "zip,rar"},
						{title : "Video files", extensions : "mov,avi,wmv,mp4,mpeg,webm"},
						{title : "Documents files", extensions : "pdf,doc,xls,ppt,docx,xlsx,odt,pptx,txt"}				
					]
				},

				// Rename files by clicking on their titles
				rename: true,
		
				// Sort files
				//sortable: true,
				
				// Enable ability to drag'n'drop files onto the widget (currently only HTML5 supports that)
				dragdrop: true,
		
				autostart: true,

				// Views to activate
				views: {
					list: true,
					thumbs: true, // Show thumbs
					active: 'thumbs'
				},

				// Flash settings
				flash_swf_url : '../js/Moxie.swf',
				// Silverlight settings
				silverlight_xap_url : '../js/Moxie.xap'
			});//upload
			
		});// ready
			(function(window) {
				var finishAuth = (function(e) {
					$("[data-client="+e+"]").click();
    			})

   			   	window.finishAuth = finishAuth;
			})(window);
        </script>
        
        
        
    </head>
    <body>
		<div id="content" class="container-fluid open">      
			<div class="row-fluid">
				<div id="servicePaneSpacer" class="span3">&nbsp;</div>
    			<div id="servicePane" class="multi-service span3">
  					
                    <ul id="services" class="nav nav-list well">
            			
                        <li class="pane-link client" data-client="computer">
    		            	<a href="api/computer/"><i class="sbicon-home"></i>My Computer
            		        	<span class="label auth-label disabled" title="Unlink this account">x</span>
			                </a>
    	    	        </li>

    		            <li class="pane-link client" data-client="flickr">
        			        <a href="api/Flickr/"><i class="sbicon-flickr"></i>Flickr
		        	            <span class="label auth-label" title="Unlink this account">x</span>
        		    	    </a>
		                </li>
                                
    	        	    <li class="pane-link client" data-client="facebook">
        	    	    	<a href="api/Facebook/"><i class="sbicon-facebook"></i>Facebook
        		            	<span class="label auth-label disabled" title="Unlink this account">x</span>
	            		    </a>
    	            	</li>
            
		                <li class="pane-link client" data-client="picasa">
    			            <a href="api/Picasa/"><i class="sbicon-picasa"></i>Picasa
        	    		        <span class="label auth-label disabled" title="Unlink this account">x</span>
		    	            </a>
        	    	    </li>
            
	            	    <li class="pane-link client" data-client="instagram">	
			                <a href="api/Instagram/"><i class="sbicon-instagram"></i>Instagram
        			            <span class="label auth-label disabled" title="Unlink this account">x</span>
            	    		</a>
	            	    </li>
                       
    	            	<li class="pane-link client" data-client="url">
	        		        <a href="api/url/"><i class="sbicon-download"></i>Link (URL)
    	            		    <span class="label auth-label disabled" title="Unlink this account">x</span>
			                </a>
        		        </li>
            
            
	            	    <li class="pane-link client" data-client="webcam">
    		        	    <a href="api/webcam/"><i class="icon-camera"></i>Take Picture
            		    	    <span class="label auth-label disabled" title="Unlink this account">x</span>
			                </a>
    	    	        </li>
            
	    	            <li class="pane-link client" data-client="video">
    			            <a href="api/video/"><i class="icon-film"></i>Record Video
            			        <span class="label auth-label disabled" title="Unlink this account">x</span>
		            	    </a>
	        	        </li>
            
    		        </ul>         
        
				</div>
    
				<div class="span9 multi-service row-fluid" id="mainpane">
    				<div class="span12" id="pickerComputer"> 
						<div id="uploader">
							<p>Request failed, please try again.</p>
						</div>
					</div>
				</div>
    
				<div id="notify-container">
					<p id="notify"></p>
				</div>
	
    		</div>

		</div>
	</body>
</html>