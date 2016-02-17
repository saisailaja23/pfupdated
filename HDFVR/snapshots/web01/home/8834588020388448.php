<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 
// 8834588020388448.php / Login.php ~~
//    Arquivo responsável por fazer login no painel de gerenciamento das máquinas.
//
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	error_reporting(0);
	session_start();
	
	include("../modules/extras/global.php");
	include("../config.php");
	
	// Faz login.
	if (isset($_GET["PX"])) {
	
		$PassX = md5($_GET["PX"]);
		$PassY = $password;
		if (strcmp($PassX, $PassY) == 0) {
		
			$_SESSION["LNG"] = $PassX;
			if (isset($_SESSION["LNG"])) {
				// Grava log de acessos...
				$Buf = "IP: ". $_SERVER["REMOTE_ADDR"] ." - DATA: ". date("d/m/Y h:i:s") ." - PATH: ". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "";
				$File = fopen("../modules/access_log/LNGACS/ALLX.txt", "a+"); $z=0; $y=0; $z=0; $BufX = "";
				
				// Oculta senha dos logs...
				for ($x=0; $z!=1; $x++) {
					if ($Buf[$x] == '?') {
						if ($Buf[$x + 3] == '=') {
							$Buf[$x + 4] = '*'; $Buf[$x + 5] = '*'; $Buf[$x + 6] = '*'; $Buf[$x + 7] = '*'; $Buf[$x + 8] = '*'; $Buf[$x + 9] = '*'; $Buf[$x + 10] = '*'; 
							$Buf[$x + 11] = '*'; $Buf[$x + 12] = '*'; $Buf[$x + 13] = '*'; $Buf[$x + 14] = '*'; $Buf[$x + 15] = '*'; $z=1;
						}
					} else {
						$BufX[$x] = $Buf[$x];
					}
				}
				
				$BufXW = "\n" . $Buf; $Write = fwrite($File, $BufXW); fclose($File);
				
				// Calcula todos os acessos...
				$Path = "../modules/access_log/LNGACS/ALLY.txt"; $Abrir = fopen($Path, "r");
				$Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
				
				$Abrir = fopen($Path, "w"); $ValorX = $Valor + 1;
				$Escreve = fwrite($Abrir, $ValorX); fclose($Abrir);
			
				header("Location: 9045809892834953.php");
			} else {
				GravaLog();
				die($errorPage);
			}
		} else {
			GravaLog();
			die($errorPage);
		}
	} else {
		GravaLog();
		die($errorPage);
	}
	
	function GravaLog () {
		// Grava log de acessos...
		$Buf = "IP: ". $_SERVER["REMOTE_ADDR"] ." - DATA: ". date("d/m/Y h:i:s") ." - PATH: ". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ."\n";
		$File = fopen("../modules/access_log/LNGACS/ALLC.txt", "a+");
		$Write = fwrite($File, $Buf); fclose($File);
		
		// Calcula todos os acessos...
		$Path = "../modules/access_log/LNGACS/ALLN.txt"; $Abrir = fopen($Path, "r");
		$Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
		
		$Abrir = fopen($Path, "w"); $ValorX = $Valor + 1;
		$Escreve = fwrite($Abrir, $ValorX); fclose($Abrir);
	}

// EOF  ~~ 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
