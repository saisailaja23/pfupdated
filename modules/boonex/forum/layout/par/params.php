<?php
    if( isset($_REQUEST['gConf']) ) die; // globals hack prevention
    require_once ($gConf['dir']['layouts'] . 'base/params.php');

    $gConf['dir']['xsl'] = $gConf['dir']['layouts'] . 'par/xsl/';	// xsl dir

    $gConf['url']['css'] = $gConf['url']['layouts'] . 'par/css/';	// css url
    $gConf['url']['xsl'] = $gConf['url']['layouts'] . 'par/xsl/';	// xsl url
