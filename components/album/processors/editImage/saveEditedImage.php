<?php

require_once ('../../../../inc/header.inc.php');
require_once ('../../../../inc/profiles.inc.php');
require_once ('../../../../inc/utils.inc.php');
require_once ('../../../../inc/db.inc.php');
require_once ('../../../../inc/images.inc.php');

require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
bx_import( 'BxDolEmailTemplates' );
bx_import( 'BxTemplFormView' );

$targetDir = '/var/www/html/pf/modules/boonex/photos/data/files/';

            
            $postData      = $_REQUEST['postdata'];            
            $postData_val  = explode('_',$postData);          
            $filename        = $postData_val[0].'.jpg';
			$file = $postData_val[0];
			
$image_data = file_get_contents($_REQUEST['url']);


$actualPath    = $targetDir."/".$filename."";
@chmod($actualPath , 0777);
file_put_contents($actualPath,$image_data);


    $aFileTypes = array(
        'icon' => array('postfix' => '_ri', 'size_def' => 32),
        'thumb' => array('postfix' => '_rt', 'size_def' => 64),
        'browse' => array('postfix' => '_t', 'size_def' => 140),
        'file' => array('postfix' => '_m', 'size_def' => 600)
    );

    // force into JPG
    $sExtension = '.jpg';
    $w[0] = 32;
    $h[0] = 32;
    $w[1] = 64;
    $h[1] = 64;
    $w[2] = 140;
    $h[2] = 140;
    $w[3] = 750;
    $h[3] = 750;


	$z = 0;
    foreach ($aFileTypes as $sKey => $aValue) {

        $iWidth = $w[$z]; //(int)$this->oModule->_oConfig->getGlParam($sKey . '_width');
        $iHeight = $h[$z]; //(int)$this->oModule->_oConfig->getGlParam($sKey . '_height');

        if ($iWidth == 0)
            $iWidth = $aValue['size_def'];
        if ($iHeight == 0)
            $iHeight = $aValue['size_def'];
        $sNewFilePath =$targetDir . $file . $aValue['postfix'] . $sExtension;
		@chmod($sNewFilePath, 0777);
        $iRes = imageResize($actualPath, $sNewFilePath, $iWidth, $iHeight, true);

        if ($iRes != 0)
            return false; //resizing was failed

        @chmod($sNewFilePath, 0777);
        $z++;
    }



?>
