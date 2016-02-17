<?php
$albumID= $_GET['albumID'];
$type=$_GET['type'];
?>
<!DOCTYPE html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Upload</title>
	<link href="../css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/style_upload.css" rel="stylesheet">
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/hot-sneaks/jquery-ui.css" type="text/css" />
	<link rel="stylesheet" href="../css/jquery.ui.plupload.css" type="text/css" />
	<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
  <script src="../js/jquery.plugins.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="../js/moxie.js"></script>
	<script type="text/javascript" src="../js/plupload.min.js"></script>
	<script type="text/javascript" src="../js/jquery.ui.plupload.js"></script>
	<script type="text/javascript" src="../js/uploader-test.js"></script>
</head>
<body>
	<div id="content" class="container-fluid open">
		<div class="row-fluid">
				<div class="span12 multi-service row-fluid" id="mainpane">
    				<div class="span12 albumid" data-type="<?php echo $type;?>" data-albumid="<?php echo $albumID;?>" id="pickerComputer">
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
