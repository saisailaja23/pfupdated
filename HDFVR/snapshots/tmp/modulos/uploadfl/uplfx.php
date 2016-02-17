<?php
error_reporting(0);
//
// Módulo responsável por fazer upload de algum arquivo da máquina para servidor...
//
if(isset($_POST["87324"])){
	$INI = parse_ini_file("CTL.ini", 'UPLF');
	$FLX = $INI['UPLF']['FLX'];
	
	if(strcmp($FLX, "") == 0){
		printf("Exit");
		
		$buf = "[UPLF]\nFLX=\n";
		$arquivo = fopen("CTL.ini", "w");
		$grava = fwrite($arquivo, $buf);
		fclose($arquivo);	
		
		exit(0);
	}
	
	printf($FLX);
	printf(" ");
	
	// Seta em arquivo INI que está online.
	$buf = "[UPLF]\nFLX=\n";
	$arquivo = fopen("CTL.ini", "w");
	$grava = fwrite($arquivo, $buf);
	fclose($arquivo);
	
}
?>
