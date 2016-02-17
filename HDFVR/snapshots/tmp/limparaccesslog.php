<?php

// Zera arquivo de logs...
$buf = " ";
$arq = fopen("acessos/maquinas/access_log.txt", "w");
$grv = fwrite($arq, $buf);
fclose($arq);

header("Location: accesslog.php");
?>