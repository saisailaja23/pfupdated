<?php

define('BX_SECURITY_EXCEPTIONS', true);
$aBxSecurityExceptions = array(
    'POST.css',
    'REQUEST.css',
);

require_once( '../../../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'images.inc.php' );

if (!$GLOBALS['logged']['admin']) {
	header("Location: " . BX_DOL_URL_ROOT . "modules/?r=qwe_profile_theme/administration");
	exit;
}

if(@$_POST['add_theme']) {

	$sCss = $_POST['css'];
	$sTitle = $_POST['title'];
	$sProfileThemeType = $_POST['theme_type'];
	
    if (get_magic_quotes_gpc()) {
		$sCss = stripslashes($sCss);
		$sTitle = stripslashes($sTitle);
		$sProfileThemeType = stripslashes($sProfileThemeType);
    }

	$sCss = strip_tags($sCss);

	$sCss = trim(mysql_real_escape_string($sCss));
	$sTitle = trim(mysql_real_escape_string($sTitle));
	$sProfileThemeType = trim(mysql_real_escape_string($sProfileThemeType));
	
	if(!$sCss || !$sTitle || !$sProfileThemeType || !$_FILES['thumb']['name']) {
		$sMessage = 'qwe_profile_theme_add_form_must_be_filled';
	}
	elseif($_FILES['thumb']['error']) {
		$sMessage = 'qwe_profile_theme_add_form_file_upload_error';
	}
	else {
		if($_FILES['thumb']) {
			$aPathInfo = pathinfo ($_FILES['thumb']['name']);
			$sFileName = time() . '.' . $aPathInfo['extension'];
			$sTmpFile = BX_DIRECTORY_PATH_ROOT . 'modules/gorpus/qwe_profile_theme/data/' . $sFileName;
			$sUploadedFile = $_FILES['thumb']['tmp_name'];
			if(move_uploaded_file($sUploadedFile,  $sTmpFile)) {
				$sSql = "INSERT INTO `qwe_profile_theme_base` SET `title` = '".$sTitle."', `css` = '".$sCss."', `type` = '".$sProfileThemeType."', `file`  = '".$sFileName."' ";
				db_res($sSql);

				$sizeX = 100;
				$sizeY = 80;
				$o =& BxDolImageResize::instance($sizeX, $sizeY);
				$o->removeCropOptions();
				$o->setJpegQuality(100);
				$o->setJpegOutput(false);
				$o->setSize($sizeX, $sizeY);
				$o->setSquareResize(false);
				$o->resize($sTmpFile, $sTmpFile);
			}
			else {
				$sMessage = 'qwe_profile_theme_add_check_dir_permissions';
			}
		}
	
		$sMessage = 'qwe_profile_theme_adm_added';
	}
}
elseif(@$_POST['add_new_page_uri']) {
	$page_uri = trim(process_db_input($_POST['page_uri']));
	
	if(!$page_uri) {
		$sMessage = 'qwe_profile_theme_uri_must_be_filled';
	}
	else {
		$sSql = "INSERT INTO `qwe_profile_theme_pages` SET `uri` = '".$page_uri."' ";
		db_res($sSql);
		$sMessage = 'qwe_profile_theme_adm_new_oage_uri_added';
	}
}


header("Location: " . BX_DOL_URL_ROOT . "modules/?r=qwe_profile_theme/Administration&qwe_message=" . $sMessage);
exit;

?>