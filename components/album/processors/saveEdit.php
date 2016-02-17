<?php
	$image_id = $_REQUEST['postdata'];

	$image_data = file_get_contents($_REQUEST['url']);

	file_put_contents("image_". $image_id .".jpg",$image_data); //SAVE IMAGE => file_put_contents("PATH_OF_IMAGE", $IMAGE_DATA)

?>