<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
	'appId'  => '210803325784194',
	'secret' => '7c299fbd4098c1e1bfab5516579b9aa1',
));

$user_profile = $facebook->api('/me');
$accessToken = $facebook->getAccessToken();

# Get User ID
$user = $facebook->getUser();
$facebook->setAccessToken($accessToken);


if ($user) {
	try {
		echo $user;
		
		$albums = $facebook->api('/me/albums');
		$action = $_REQUEST['action'];
		$albumName = $_REQUEST['album'];
		$album_id = '';
    
		if(isset($action) && $action=='viewalbum'){ 
			$album_id = $_REQUEST['album_id'];
			$photos = $facebook->api("/{$album_id}/photos");
			?>
			<div id="picker" class="row-fluid">
				<div class="span8">
					<ul class="list filelist thumbnails"> 
					<?php
					foreach($photos['data'] as $photo){
					?>  
						<li unselectable="on">
							<a name="<?php echo $photo['name']; ?>" title= "<?php echo $photo['name']; ?>" class="thumbnail selectable" path="<?php echo $photo['original']; ?>" data-disabled="">
								<img src="<?php echo $photo['picture']; ?>" alt="<?php echo $photo['name']; ?>" id="<?php echo $i; ?>" />
							</a>
							<h5></h5>
						</li>    
    				<?
						$i++;
					}//foreach
					?>                
					</ul>
				</div>
				<div class="span3">&nbsp;</div>
			</div>

			<div id="bread" class="span8">
				<div class="row-fluid  breadcrumb" id="breadcrumbbar">
	    			<span class="breadcrumb_element ellipsize"><a href="#/">Upload Files</a></span><span class="breadcrumb_element divider">/</span>
			        <span class="breadcrumb_element ellipsize"><a href="api/facebook" class='directory'>Facebook</a></span><span class="breadcrumb_element divider">/</span>
			        <span class="breadcrumb_element ellipsize"><a href="#/"><?php echo $albumName; ?></a></span><span class="breadcrumb_element divider">/</span>
				</div>
			</div>

			<div id="preview" class="span3">
				<h3 id="helpMessage">Select files</h3>
				<p id="subMessage">Click to select multiple items.</p>
			    <ul class="multi-file-preview" id="mfp">
                	<div id="cont" class="clearboth"></div>
				</ul>
				<div id="action-group" class="span4">
					<a class="btn btn-large btn-primary btn-upload disabled">Upload</a>
				</div>
			</div>
		</div>
        <?php
    	}
		else
		{

    		$pageURL .= 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	    	echo '<div class="alb">';
		    if(strstr($pageURL,'.php?')){
    		    $and = '&';
		    }else{
    		    $and = '?';
	    	}
		?>
		<div id="picker" class="span8">
		    <ul class="picker_files_header noselect pointer">
		       <li>
        		<span>Album</span>
	            <i class="floatright icon-resize-vertical"></i>
    		   </li>
		    </ul>
		    <ul id="picker_files" class="filelist nav nav-list nav-files noselect">
		 <?
    	foreach($albums['data'] as $album)
	    {
    	    if($album_id == $album['id']){
        	    $name = '<b><u>'.$album['name'].'</u></b>';
	        }else{
    	        $name = $album['name'];
        	}
			echo '<li unselectable="on">';
			echo "<a name='".$name."' href=".$pageURL.$and."action=viewalbum&album_id=".$album['id']."&album=".$name." class='directory'>";
			echo "<i class='icon-folder-open'></i>".$name."<i class='icon-chevron-right icon-white floatright'></i></a>";
    	}

	?>          
    </ul>
    
</div>
<div id="bread" class="span8">
    <div class="row-fluid  breadcrumb" id="breadcrumbbar">
        
    <span class="breadcrumb_element ellipsize"><a href="#/">Uploader</a></span><span class="breadcrumb_element divider">/</span>

    <span class="breadcrumb_element ellipsize"><a href="api/facebook/" class='directory'>Facebook</a></span><span class="breadcrumb_element divider">/</span>


    </div>
</div>
 
<div id="preview" class="span3">
    <h3 class="filename"></h3>
    <ul class="multi-file-preview"></ul>
    <div id="action-group">
            <a class="btn-upload btn btn-large btn-primary">Upload</a>
    </div>
</div>
<? }        
	} catch (FacebookApiException $e) {
		error_log($e);
	}
} else {
	$loginUrl = $facebook->getLoginUrl( array(
		'scope' => 'publish_stream,photo_upload'
	));
	echo("<script>top.location.href = '" . $loginUrl . "';</script>");
}
    ?>