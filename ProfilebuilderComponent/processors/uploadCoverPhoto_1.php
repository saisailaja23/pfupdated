<?php
require_once '../../inc/header.inc.php';
require_once '../../inc/profiles.inc.php';
require_once '../../inc/utils.inc.php';
require_once '../../inc/db.inc.php';
require_once '../../inc/images.inc.php';
require_once 'imagetool.class.php';
require_once 'watermark.php';

$uploadPath = $dir['root'] . "tmp/coverphotos/";

$filename = $_FILES['file']['name'];
$details = pathinfo($filename);
$extension = $details['extension'];
$allowed = array("jpg", "png", "gif");

if (!in_array(strtolower($extension), $allowed)) {
	print_r('This file is not allowed');
} else {
	if (in_array(strtolower($extension), $allowed)) {
		if ($_FILES["file"]["size"] != 0) {
			if (@$_REQUEST["mode"] == "html4") {
				if (@$_REQUEST["action"] == "cancel") {
					print_r("{state:'cancelled'}");
				} else {
					$filename = $_FILES["file"]["name"];
					$filename = str_replace(" ", "_", $filename);
					move_uploaded_file($_FILES["file"]["tmp_name"], $uploadPath . $filename);
					list($width, $height, $type, $attr) = getimagesize($uploadPath . $filename);
					//echo $width . "-----" . $height;
					if ($width < 1000 || $height < 300) {
						echo '{state: false, extra: {code: 1, text: "your file is not suitable for ads"}}';
						unlink($uploadPath . $filename);
						exit;
					} else {
                        $newname=md5($uploadPath.$filename).".jpg";
                        $imgTObj = new ImageTool($uploadPath.$filename);
                        //*** 2) Resize image (options: exact, portrait, landscape, auto, crop)
                        $imgTObj -> resizeImage(1024, 378);
                        //*** 3) Save image
                        $imgTObj -> saveImage($uploadPath.$newname, 100);
                        $filename=$newname;					
					
					
						$newpath = $dir['root'] . "tmp/coverphotos/";
						//print_r("{state: true, name:'" . str_replace("'", "\\'", $filename) . "', size:" . $_FILES["file"]["size"] . "}");
						$logged = getLoggedId();
						
						$previousCoverSql = db_arr("SELECT id FROM coverPhotos WHERE `ownerId` = '$logged'");
						//echo $previousCover = $previousCoverSql['id'];

						$previousCover = $previousCoverSql['id'];
						
						if ($previousCover != null) {
							$previousfilename = $previousCover . ".jpg";
							unlink($newpath . $previousfilename);
							$sql1 = "DELETE FROM coverPhotos WHERE `ownerId` = '$logged'";
							$delete = mysql_query($sql1);
						}

						$sql = "INSERT INTO coverPhotos SET `ownerId` = '$logged'";
						$result = mysql_query($sql);
						$cover = mysql_insert_id();
						
						//move_uploaded_file($_FILES["file"]["tmp_name"], $uploadPath . $filename);
						
						rename($newpath . $filename, $newpath.$cover.".jpg");
						unlink($uploadPath.$_FILES["file"]["name"]);
						
						print_r("{state: true, name:'" . str_replace("'", "\\'", $cover.".jpg") . "', size:" . $_FILES["file"]["size"]/* filesize("uploaded/".$filename) */ . "}");
                exit();
					}
				}
			}
		}
	}
}
