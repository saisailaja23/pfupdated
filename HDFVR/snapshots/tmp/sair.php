<?php
error_reporting(0);
session_start(); 
session_destroy();

if(!session_is_registered('nome')){ 
	header("Location: 31337F0R3V3RUND3RGR0UNDZ.php"); 
}
?>
