<?php
/**
 * C:\Users\msi1308\AppData\Roaming\Sublime Text 2\Packages/PhpTidy/phptidy-sublime-buffer.php
 *
 * @author Aravind Buddha <aravind.buddha@mediaus.com>
 * @package default
 */


 $physical_path=$_REQUEST['physical_path'];
// $physical_path="E:/xampp/htdocs/www.parentfinder.com.PV.3.0/modules/boonex/avatar/data/avatarphotos";

$file_list=array();
$i=0;
$row;

if ($handle = opendir($physical_path)) {
	while (false !== ($entry = readdir($handle))) {
		if ($entry != "." && $entry != "..") {
			$entry_full=$entry;
			// $entry_full=$physical_path .'/'. $entry;
			$row=array(
				"id"=>substr($entry, 0, -4),
				"data"=>array(substr($entry, 0, -4), $entry_full),
			);
			array_push($file_list, $row);
		}
	}

	closedir($handle);
}


echo json_encode(array(
		rows=>$file_list )
);
