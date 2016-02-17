<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 
// 3553654543454556.php / Logs.php ~~
//    Arquivo responsável por gerenciar os logs.
//
// Descrição abaixo.
//    * Exibir quantidade de acessos na página de login.
//    * Quantidade de logins bem e mal sucessidos.
//    * Acessos feitos nas páginas do painel.
//    * Exibir log de ultimas conexões.
//    
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	error_reporting(0);
	session_start();
	
	include("../modules/extras/global.php");	// Dados globais.
	include("../config.php");					// Configurações.
	include("0923840923949455.php");			// Itens globais do site.
	
	// Verifica login...
	if (!isset($_SESSION["LNG"])) { die($errorPage); }
	
	$Path = "../modules/access_log/LNGACS/ALLY.txt"; $Abrir = fopen($Path, "r"); $Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
	$ValorA = strlen($Valor); $ValorAB = "";
	if ($ValorA == 1) { $ValorAB = " ". $Valor ."  "; }
	if ($ValorA == 2) { $ValorAB = " ". $Valor ." "; }
	if ($ValorA == 3) { $ValorAB = "". $Valor ." "; }
	if ($ValorA == 4) { $ValorAB = "". $Valor .""; }
	
	
	$Path = "../modules/access_log/LNGACS/ALLN.txt"; $Abrir = fopen($Path, "r"); $Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
	$ValorA = strlen($Valor); $ValorBA = "";
	if ($ValorA == 1) { $ValorBA = " ". $Valor ."  "; }
	if ($ValorA == 2) { $ValorBA = " ". $Valor ." "; }
	if ($ValorA == 3) { $ValorBA = "". $Valor ." "; }
	if ($ValorA == 4) { $ValorBA = "". $Valor .""; }

?>
<html><head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
<title> Cogito V&iacute;rus :: Logs</title>
<link rel="shortcut icon" href="<?php echo $faviconGL; ?>" type="image/x-icon" />
<link href="../midia/SiteFL/style.css" rel="stylesheet" type="text/css" />

</head><body>

<center><pre><font style="color: white;"><?php echo $TopoMenu; ?><!-- Exibe topo e menu -->
|                                                                                                                                 |
| <font style="color: #d6ef39;"><b>Login bem sucedido:</b></font> <?php echo $ValorAB; ?> <font style="color: #008000;">(<a href="../modules/access_log/LNGACS/ALLX.txt" target="_blank" >Abrir Log</a>)</font>                                                                                            |
| <font style="color: #d6ef39;"><b>Login mal sucedido:</b></font> <?php echo $ValorBA; ?> <font style="color: #008000;">(<a href="../modules/access_log/LNGACS/ALLC.txt" target="_blank" >Abrir Log</a>)</font>                                                                                            |
|                                                                                                                                 |
| <font style="color: #F28500;"><b>>></b></font> <font style="color: #F28500;"><b>Ultimas conexões</b></font>                                                                                                             |
<textarea class="textareax" id="textareax" cols="129" rows="25">
<?php

// Exibe log...
$LogFile = "../modules/access_log/38459030495663598.txt"; $Ler = fopen($LogFile, "r"); 
$BufLog = fread($Ler, filesize($LogFile)); fclose($Ler); echo $BufLog;

?>
</textarea>
<script type="text/javascript">
var elem = document.getElementById('textareax'); elem.scrollTop = elem.scrollHeight;
</script>|                                                                                                                                 |
|                                                                                                                                 |
|                                                                                                             <font style="color: #990000;">,---------------.</font>   |
|                                                                                                            <font style="color: #990000;">(</font>   <a href="9348834858324853.php">LIMPAR LOGS</a>   <font style="color: #990000;">|::</font>|
|                                                                                                             <font style="color: #990000;">`---------------´</font>   |
|                                                                                                                                 |<?php echo $RodapeCV; ?> <!-- Exibe rodapé -->
</font></pre>


</center></form></body></html>
<?php
// EOF  ~~ 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
