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
	<script type="text/javascript" src="../js/uploader.js"></script>
</head>
<body>
	<div id="content" class="container-fluid open">
		<div class="row-fluid">

			<!-- <div id="servicePaneSpacer" class="span3">&nbsp;</div>
    		<div id="servicePane" class="multi-service span3">
					<ul id="services" class="nav nav-list well">
						<li class="pane-link client albumid" data-albumid="<?php echo $albumID;?>" data-client="computer?albumID=<?php echo $albumID;?>">
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
				</div> -->
				<div class="span12 multi-service row-fluid" id="mainpane">
			<!-- 		<div class="albumid" data-albumid="<?php echo $albumID;?>" ></div> -->
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
