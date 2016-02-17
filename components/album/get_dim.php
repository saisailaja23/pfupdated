<?php
header('Content-Type: application/json');

$img = $_REQUEST['img'];
list($width, $height, $type, $attr) = getimagesize($img);


echo json_encode(array(
		"width" => $width,
		"height" => $height
	));