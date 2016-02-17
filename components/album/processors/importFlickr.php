<?php

require_once("phpFlickr.php");
// Create new phpFlickr object
$f = new phpFlickr("ef622e1495c7dfd1770527abce840565", "43d03d5cdb8b08dd");
 
$f->auth();
$token = $f->auth_checkToken();

	//echo "auth: ".$_SESSION['phpFlickr_auth_token'];
	//echo $token;


// Find the NSID of the authenticated user
$nsid = $token['user']['nsid'];
 
// Get the friendly URL of the user's photos
$photos_url = $f->urls_getUserPhotos($nsid);
 
// Get the user's public photos
$photos = $f->photos_search(array("user_id" => $nsid,"extras"=>"original_format,original_secret"));
 
$mySet = $f->photosets_getPhotos($nsid, 'original_format', NULL);
foreach ($mySet['photoset']['photo'] as $photo) {
    echo '<div><img src="'. $f->buildPhotoURL($photo, 'original') .'" alt="" /></div>';
}

$mySet2 = $f->photosets_getPhotos($nsid, 'url_o', NULL);
foreach ($mySet2['photoset']['photo'] as $photo) {
    echo '<div><img src="'. $photo['url_o'] .'" alt="" /></div>';
}
 
?>

<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Import from Flickr</title>
		<link rel="stylesheet" type="text/css" href="../css/image-picker.css">
        <link rel="stylesheet" type="text/css" href="../css/prettyPhoto.css">
		<script src="../js/jquery-1.9.1.js" type="text/javascript"></script>
		<script src="../js/image-picker.js" type="text/javascript"></script>
		<script src="../js/jquery.lazyloadxt.js" type="text/javascript"></script>
        <script src="../js/jquery.prettyPhoto.js" type="text/javascript"></script>
	</head>
	
    <body>

	<form>
		<div id="container">
        	<h1>Select the photos you want to import</h1>
      		<div class="picker">
				<select class="image-picker" multiple="multiple">
				<?php
				// Loop through the photos and output the html
					foreach ((array)$photos['photo'] as $photo) {
						//echo "<a href=$photos_url$photo[id]>";
						echo "<option data-img-src='" . $f->buildPhotoURL($photo, "square_2") . "' data-imgFull-src='" . $f->buildPhotoURL($photo, "original") . "' value='$i'>$photo[title]</option>";
						$i++;
					}
				?>                
		      </select>
		    </div>
		</div>
	
		<script type="text/javascript">

			jQuery("select.image-picker").imagepicker({
				hide_select:  true,
				//show_label:   true,
				changed: function(){
					$("select").data('picker').sync_picker_with_select();
//					alert('We are full')
					console.log($("select").data('picker'));
				}
    		});
			

			var container = jQuery("select.image-picker.masonry").next("ul.thumbnails");
			//	container.find("li").lazyLoadXT();
//				container.imagesLoaded(function(){
//					container.masonry({
//						itemSelector:   "li",
//					});
//				});
			$('a[rel^="prettyPhoto"]').prettyPhoto({
				social_tools: '',
				animation_speed:'normal',
				//theme:'light_square',
				slideshow:3000,
				show_title: false
			});
		</script>
	</form>

	</body>
 </html>
