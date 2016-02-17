<?php

    $albumID        = $_GET['albumID'];
    //$connectionID   = $_GET['connectionID'];
    
    //echo " albumID ".$albumID;
    //echo " connectionID ".$connectionID;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>

<title>Upload</title>

<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/base/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="../css/jquery.ui.plupload.css" type="text/css" />

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>

<!-- production -->
<script type="text/javascript" src="../js/plupload.full.min.js"></script>
<script type="text/javascript" src="../js/jquery.ui.plupload.js"></script>

<script type="text/javascript">
	// Initialize the widget when the DOM is ready
	$( document ).ready(function() {
	$("#uploader").plupload({
		// General settings
		runtimes : 'html5,flash,silverlight,html4',
		url : 'api/computer/upload.php',
                 multipart_params : {
                                            "albumID" : '<?=$albumID;?>',
                                            "connectionID" : '<?=$connectionID;?>'
                                    },
		//required_features: 'access_binary',
		// User can upload no more then 20 files in one go (sets multiple_queues to false)
		max_file_count: 50,
	
		chunk_size: '1mb',
		
		filters : {
			// Maximum file size
			max_file_size : '1000mb',
			// Specify what files to browse for
			mime_types: [
				{title : "Image files", extensions : "jpg,gif,png,tiff,jpeg"},
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
		silverlight_xap_url : '../js/Moxie.xap',
                
                 // Post init events, bound after the internal events
                                init : {                                   

                                    UploadComplete: function(up, files) {
                                        // Called when all files are either uploaded or failed
                                        /*var n1 = noty({dismissQueue: true, force: true, layout: 'center', theme: 'defaultTheme', text: 'Files Uploaded successfully', type: 'success'});
                                                setTimeout(function () {
                                        n1.close();
                                        window.parent.closeTheIFrameImDone();
                                        }, 1500);*/
        
                                        var n = noty({
                                            text        : 'Files Uploaded successfully. Click Ok to exit',
                                                type        : 'alert',
                                                dismissQueue: true,
                                                layout      : 'center',
                                                theme       : 'defaultTheme',
                                                modal	: true,
                                                timeout	: true,
                                                buttons     : [
                                                {addClass: 'btn btn-primary', text: 'Ok', onClick: function ($noty) {
                                                        $noty.close();
                                                         window.parent.closeTheIFrameImDone();

                                                    }
                                                }
                                            ]
                                         });
                                        
                                        

                                    },


                                    Error: function(up, args) {
                                        // Called when error occurs
                                        log('[Error] ', args);
                                    }
                                }
                
	});//upload

});
</script>

</head>
<body style="font: 13px Verdana; color: #333">
<div class="span12" id="pickerComputer"> 
	<div id="uploader">
		<!--<p>Request failed, please try again.</p>-->
	</div>
</div>
</body>
</html>
