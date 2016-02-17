<?php

require_once("phpFlickr.php");
// Create new phpFlickr object
$i = 1;
$f = new phpFlickr("ef622e1495c7dfd1770527abce840565", "43d03d5cdb8b08dd");
 
if (empty($_SESSION['phpFlickr_auth_token'])) {
	//echo "não logado - <a href='auth.php'>clique aqui para logar!</a>";
?>
<div class="row-fluid" style="padding-top:15%">
	<div class="span2">&nbsp;</div>
    <div class="span8 center authpane">
        <h1 class="auth_service_title">Upload files from Flickr</h1>
        <p>
            <a class="btn btn-large btn-primary button-block button-main" href="api/flickr/auth.php" target="flickr">Connect to Flickr</a>
        </p>
      <p style="margin-top: 10px">
      
        We'll open a new page to help you connect your Flickr account
      
      </p>
    </div>
	<div class="span2">&nbsp;</div>
</div>
<script type="text/javascript">

		     $( ".thumbnail" ).on( "click" , function(e) {
				e.preventDefault();
				var img = $(this).children( "img" );
				var id = img.attr("id");
				if ($(this).hasClass('selectedItem')) {
					$(this).removeClass('selectedItem');
				}else{
					$(this).addClass('selectedItem');
					
					
        			var tpl_thumb = '<li class="selected-preview alert alert-info" id"'+id+'_t">\
			                    		<div class="tiny-thumbnail">\
					                        <img class="thumbnail-image" src="'+img.attr("src")+'" alt="'+img.attr("alt")+'" style="">\
                                        </div>\
					                    <span class="multi-filename ellipsize">'+img.attr("alt")+'</span>\
					                </li>'
					
					$("#multi-file-preview").insertBefore(tpl_thumb);

				}
				
			 });
			 

</script>
<?
	
	exit;
}
$f->auth();
$token = $f->auth_checkToken();
 
// Find the NSID of the authenticated user
$nsid = $token['user']['nsid'];
 
// Get the friendly URL of the user's photos
$photos_url = $f->urls_getUserPhotos($nsid);
 
// Get the user's public photos
$photos = $f->photos_search(array("user_id" => $nsid,"extras"=>"original_format,original_secret"));
 
?>

<div id="picker" class="row-fluid">
	<div class="span8">
		<ul class="list filelist thumbnails">

<?php
// Loop through the photos and output the html
foreach ((array)$photos['photo'] as $photo) {
	//echo "<a href=$photos_url$photo[id]>";
?>  
			<li unselectable="on">
				<a name="<?php echo $photo['title']; ?>" title= "<?php echo $photo['title']; ?>" class="thumbnail selectable" path="<?php echo $f->buildPhotoURL($photo, "original"); ?>" data-disabled="">
					<img src="<?php echo $f->buildPhotoURL($photo, "thumbnail"); ?>" alt="<?php echo $photo['title']; ?>" id="<?php echo $i; ?>" />
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
	    <span class="breadcrumb_element ellipsize"><a href="/">Upload Files</a></span><span class="breadcrumb_element divider">/</span>
        <span class="breadcrumb_element ellipsize"><a href="Flickr/">Flickr</a></span><span class="breadcrumb_element divider">/</span>
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
