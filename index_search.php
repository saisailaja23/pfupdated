<?php

require_once('inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_CLASSES . 'ctzIndexSearch.php');

$sCode = '';
if (isset($_GET['action'])) {	
	$sAction = strip_tags($_GET['action']);
	if ($sAction == 'get_values') {
		$oSearch = new ctzIndexSearch($_GET['section'], $_GET['field']);
		$oSearch->getFormArray();
		$sCode = $oSearch->getForm();
	}
}
echo $sCode;

?>