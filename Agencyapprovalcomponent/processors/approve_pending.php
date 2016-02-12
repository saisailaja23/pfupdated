<?php

/*********************************************************************************
* Name:    Prashanth A
* Date:    02/07/2014
* Purpose: Updating the status of photo,video and journal
*********************************************************************************/
require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');

$items_IDS = $_POST['Aid'];
$type= $_POST['type'];

switch ($type) {
    case 'photo':
        $update_photo_status  = mysql_query("Update bx_photos_main SET Status = 'Approved' where ID IN($items_IDS)");
        break;
    case 'video':
	//echo "Update rayvideofiles SET Status = 'approved' where  ID IN($items_IDS)";
       $update_video_status  = mysql_query("Update RayVideoFiles  SET Status = 'approved' where  ID IN($items_IDS)");        
        break;
    case 'blog':      
        $update_blog_status  = mysql_query("Update bx_blogs_posts SET PostStatus = 'approval' where PostID IN($items_IDS)"); 
        break;
}

if (mysql_affected_rows() > 0)
{
echo json_encode(array(
'status' => 'success',
));
}
  else
{
echo json_encode(array(
'status' => 'err',

));
}


?>
