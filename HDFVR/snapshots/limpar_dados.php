<?php
	
	$arquivo = fopen("controles/maquinas_cn.txt", "w");
	fwrite($arquivo, "0"); fclose($arquivo);
	
	$arquivo = fopen("controles/plugins_abn.txt", "w");
	fwrite($arquivo, "0"); fclose($arquivo);
	
	$arquivo = fopen("controles/plugins_bbx.txt", "w");
	fwrite($arquivo, "0"); fclose($arquivo);
	
	$arquivo = fopen("controles/plugins_cef.txt", "w");
	fwrite($arquivo, "0"); fclose($arquivo);
	
	$arquivo = fopen("controles/plugins_scp.txt", "w");
	fwrite($arquivo, "0"); fclose($arquivo);
	
	$arquivo = fopen("controles/plugins_uni.txt", "w");
	fwrite($arquivo, "0"); fclose($arquivo);
	
	header("Location: 74568723457.php");
?>
