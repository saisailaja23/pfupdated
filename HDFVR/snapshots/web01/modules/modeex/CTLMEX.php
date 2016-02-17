<?php
// Arquivo responsável por controlar os modos de execução...

if (isset($_GET["MDX"])) {
	if (isset($_GET["UID"])) {
		$MDX = $_GET["MDX"];
		
		if ($MDX == 1) {
			// Envia comando Speed mode
			$buf = "[CTLCTR]\nUID=". $_GET["UID"] ."\nCMD=893898442\n";
			
			$arq = fopen("../../databases/CTLCentral.ini", "w");
			if ($arq) { fwrite($arq, $buf); fclose($arq); }
			
			echo "<html><script language=\"javascript\">
				  alert('Modo de execu\u00e7\u00e3o \'Speed Mode\' ativado para est\u00e1 m\u00e1quina!');
				  window.location.href = \"../../home/8884588745827235.php?CTL=". $_GET["UID"] ."\";
				  </script></html>";
		}
		
		if ($MDX == 2) {
			// Envia comando Silent mode
			$buf = "[CTLCTR]\nUID=". $_GET["UID"] ."\nCMD=349598348\n";
			
			$arq = fopen("../../databases/CTLCentral.ini", "w");
			if ($arq) { fwrite($arq, $buf); fclose($arq); }
			
			echo "<html><script language=\"javascript\">
				  alert('Modo de execu\u00e7\u00e3o \'Silent Mode\' ativado para est\u00e1 m\u00e1quina!');
				  window.location.href = \"../../home/8884588745827235.php?CTL=". $_GET["UID"] ."\";
				  </script></html>";
		}
	}
}

?>
