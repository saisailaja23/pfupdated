<?php
if( isset($_REQUEST['gConf']) ) die; // globals hack prevention

$gConf['dir']['xsl'] = $gConf['dir']['layouts'] . 'base_ru/xsl/';	// xsl dir

$gConf['url']['icon'] = $gConf['url']['layouts'] . 'base_ru/icons/';	// icons url
$gConf['url']['img'] = $gConf['url']['layouts'] . 'base_ru/img/';	// img url
$gConf['url']['css'] = $gConf['url']['layouts']  . 'base_ru/css/';	// css url
$gConf['url']['xsl'] = $gConf['url']['layouts'] . 'base_ru/xsl/';	// xsl url
