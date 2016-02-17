<?php
if (extension_loaded('gd')) { // return true if the extension’s loaded.
	echo 'Installed.';
} else {
	if (dl('gd.so')) { // dl() loads php extensions on the fly.
		echo 'Installed.';
	} else {
		echo 'Not installed.';
	}
}
class ImgResizer {
	private $originalFile = '';
	public function __construct($originalFile = '') {
		$this -> originalFile = $originalFile;
	}
	public function resize($newWidth, $targetFile) {
		if (empty($newWidth) || empty($targetFile)) {
			return false;
		}
		$src = imagecreatefromjpeg($this -> originalFile);
		list($width, $height) = getimagesize($this -> originalFile);
		$newHeight = ($height / $width) * $newWidth;
		$tmp = imagecreatetruecolor($newWidth, $newHeight);
		imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
		if (file_exists($targetFile)) {
			unlink($targetFile);
		}
		imagejpeg($tmp, $targetFile, 85); // 85 is my choice, make it between 0 – 100 for output image quality with 100 being the most luxurious
	}
}

// To store resized image to a new file thus retaining the 800x600 version of me.jpg, go with this instead:
// $work -> resize(400, 'img/me_smaller.jpg');


?>