<script>

		$( document ).ready(function() {

			var content = $('#mainpane');
			var loading = $("<div id='loading' class='spinner overlay'>Loading</div>");
			$( ".directory" ).on( "click" , function(e) {
				e.preventDefault();
				content.html(loading);
				try
				{
					var url = $(this).attr("href");
				
					$.ajax({
						url: url
						//cache: false
					})
					.done(function( html ) {
						content.html( html );
					});
				}
				catch(e)
				{
					//console.log(e.stack);
				}

			 });
		 });
</script>
<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
require 'src/facebook.php';
// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '285330101633248',
  'secret' => 'c09f02a257824b0f1000485f284525f4',
));

// Get User ID
$accessToken = $facebook->getAccessToken();
$facebook->setAccessToken($accessToken);

$user = $facebook->getUser();
//echo $user;
// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
	  echo "/me 1 \n";
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
	echo "up " . $user_profile ."\n";
  } catch (FacebookApiException $e) {
	// echo "erro";
    error_log($e);
    $user = null;
	echo "ex ". $e;
  }
}else{
	$loginUrl = $facebook->getLoginUrl(array("scope" => "user_photos", "redirect_uri" => "auth.php"));
?>
<div class="row-fluid" style="padding-top:15%">
	<div class="span2">&nbsp;</div>
    <div class="span8 center authpane">
        <h1 class="auth_service_title">Upload files from Facebook</h1>
        <p>
            <a class="btn btn-large btn-primary button-block button-main" href="api/facebook/auth.php?login=<?php echo urlencode($loginUrl); ?>" target="facebook">Connect to Facebook</a>
        </p>
      <p style="margin-top: 10px">
      
        We'll open a new page to help you connect your Facebook account
      
      </p>
    </div>
	<div class="span2">&nbsp;</div>
</div>
<?php
	
	exit;
}

/* verifica se o usuário já autorizou a aplicação para publicação de fotos/acessar os dados de email e os dados sobre você  */
//$permissions = $facebook->api("/me/permissions");
//
//if(! (array_key_exists('user_photos', $permissions['data'][0])
//)) {
//    /* solicitar as permissões  */
//    header("Location: " . $facebook->getLoginUrl(array("scope" => "user_photos")));
//    exit;
//}

//$albums = $facebook->api('/me?fields=photos.fields(album,picture,link,images)');
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
		}
		?>                
		</ul>
	</div>
	<!--Spacer for the fixed preview pane div-->
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
    <ul class="multi-file-preview" id="mfp"><div id="cont" class="clearboth"></div></ul>
	<div id="action-group" class="span4">
		<a class="btn btn-large btn-primary btn-upload disabled">Upload</a>
	</div>
</div>
</div>
        <?php
    
	}
	else
	{

    	$pageURL .= 'https://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
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
            <span>Filename</span>
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
//	        echo '<p>'."<a href=".$pageURL.$and."action=viewalbum&album_id=".$album['id'].">".$name.'</a></p>';
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
<?
	}
	?> 

<script type="text/javascript">

		     $( ".thumbnail" ).on( "click" , function(e) {
				e.preventDefault();
				$("#cont").text("");
				var img = $(this).children( "img" );
				var id = img.attr("id");
				if ($(this).hasClass('selectedItem')) {
					$(this).removeClass('selectedItem');
					$("#"+id+"_t").remove();
				}else{
					$(this).addClass('selectedItem');
					var imgLen = $(".selected-preview").length;
					
        			var tpl_thumb = '<li class="selected-preview alert alert-info" id="'+id+'_t">\
			                    		<div class="tiny-thumbnail">\
					                        <img class="thumbnail-image" src="'+img.attr("src")+'" alt="'+img.attr("alt")+'" style="">\
                                        </div>\
					                    <span class="multi-filename ellipsize">'+img.attr("alt")+'</span>\
					                </li>'
					
					$(".multi-file-preview").prepend(tpl_thumb);
				}
				
				var imgLen = $(".selected-preview").length;
					
				if (imgLen == 0){
					$("#subMessage").show();
					$("#helpMessage").text("Select files")
					$(".btn-upload").addClass("disabled");
				}else{
					$("#subMessage").hide();
					$(".btn-upload").removeClass("disabled");
					$("#helpMessage").text(imgLen +"  selected files");
					
					if ( imgLen >= 5 ){
						
						$(".selected-preview").each(function(index, element) {
                            $(this).find(".tiny-thumbnail").hide();
							$(this).show();
							$(".multi-file-preview > .clearbooth").hide();
							if (index > 5){
								$(this).hide();
								var imgMore = parseInt(imgLen) - 6;
								$("#cont").text("and "+imgMore+" more");
							}
							//console.log(index +" - "+ element);
                        });
					}else{
						$(".selected-preview").each(function(index, element) {
                            $(this).find(".tiny-thumbnail").show();
							$(this).show();
                        });
					}
				}
				
			 });

</script>