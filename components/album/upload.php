<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>

<title>Upload</title>

<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/base/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="css/jquery.ui.plupload.css" type="text/css" />

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>

<!-- production -->
<script type="text/javascript" src="js/plupload.full.min.js"></script>
<script type="text/javascript" src="js/jquery.ui.plupload.js"></script>

<!-- debug 
<script type="text/javascript" src="../../js/moxie.js"></script>
<script type="text/javascript" src="../../js/plupload.dev.js"></script>
<script type="text/javascript" src="../../js/jquery.ui.plupload/jquery.ui.plupload.js"></script>
-->

</head>
<body style="font: 13px Verdana; color: #333">
	<div id="uploader">
		<p>Your browser doesn't have Flash, Silverlight or HTML5 support.</p>
	</div>
	<br />
<script type="text/javascript">
// Initialize the widget when the DOM is ready
$(function() {
	$("#uploader").plupload({
		// General settings
		runtimes : 'html5,flash,silverlight,html4',
		url : 'processors/upload.php',

		// User can upload no more then 20 files in one go (sets multiple_queues to false)
		max_file_count: 50,
		
		chunk_size: '1mb',

		// Resize images on clientside if we can
/*		resize : {
			width : 200, 
			height : 200, 
			quality : 90,
			crop: true // crop to exact dimensions
		},*/
		
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
		flash_swf_url : 'js/Moxie.swf',

		// Silverlight settings
		silverlight_xap_url : 'js/Moxie.xap'
	});

});
</script>
</body>
</html>
