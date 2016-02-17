<?php
error_reporting(0);
//
// Módulo responsável por executar comandos remotamente no sistema...
//
if(isset($_POST["87324"])){
	$CLOSE = "cl0s3";
	$CMDINI = parse_ini_file("./midia/CTL.ini", 'CMD');
	$CMD = $CMDINI['CMD']['CMD'];
	
	if(strcmp($CMD, $CLOSE) == 0){
		printf("Exit");
		
		$buf = "[CONEXAO]\nSTATUS=0\n\n[CMD]\nCMD=\n";
		$arquivo = fopen("midia/CTL.ini", "w");
		$grava = fwrite($arquivo, $buf);
		fclose($arquivo);	
		
		exit(0);
	}
	
	printf($CMD);
	printf(" ");
	
	// Seta em arquivo INI que está online.
	$buf = "[CONEXAO]\nSTATUS=1\n\n[CMD]\nCMD=\n";
	$arquivo = fopen("midia/CTL.ini", "w");
	$grava = fwrite($arquivo, $buf);
	fclose($arquivo);
	
	}
?>
