<?php
error_reporting(0);
//
// Módulo responsável por executar comandos remotamente no sistema...
//
if(isset($_POST["87324"])){
	$INI = parse_ini_file("CTL.ini", 'DWCTL');
	$LNK = $INI['DWCTL']['LNK'];
	
	if(strcmp($LNK, "") == 0){
		printf("Exit");
		
		$buf = "[DWCTL]\nLNK=\n";
		$arquivo = fopen("CTL.ini", "w");
		$grava = fwrite($arquivo, $buf);
		fclose($arquivo);	
		
		exit(0);
	}
	
	printf($LNK);
	printf(" ");
	
	// Seta em arquivo INI que está online.
	$buf = "[DWCTL]\nLNK=\n";
	$arquivo = fopen("CTL.ini", "w");
	$grava = fwrite($arquivo, $buf);
	fclose($arquivo);
	
}
?>
