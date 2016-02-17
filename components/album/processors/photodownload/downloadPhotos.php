<?php
require_once( '../../../../inc/header.inc.php' );
require_once( '../../../../inc/profiles.inc.php' );
require_once(BX_DIRECTORY_PATH_ROOT . "log4php/logForCommon.php");

$user_id = getLoggedId();
//$ID = implode(',', $_POST['ID']); 
//$ID = '27610, 27619, 27620, 27621, 27622, 27623';
$ID = $_GET['ID'];
$ID = str_replace("_", ", ", $ID);
//echo $ID; exit;

$photodeatils = getProfileInfo($user_id);

$result = mysql_query("SELECT ph.ID,ph.Hash,ph.Ext,ph.Title FROM 
				bx_photos_main as ph,sys_albums_objects sab
			WHERE ph.ID=sab.id_object
			AND ph.ID IN($ID)
                        ORDER BY sab.obj_order");

$result123 = mysql_query("SELECT  ID, `Uri`, Ext FROM  `bx_photos_main` bm
JOIN sys_albums_objects so ON so.id_object = bm.`ID` 
WHERE bm.ID IN($ID) ORDER BY so.obj_order");

//27610, 27619, 27620, 27621, 27622, 27623



$files = null;
while ($row = mysql_fetch_array($result123)) {

	$fileName = "";
	$extension = "";
	$pngpos = strpos($row['Uri'], ".png");
	$jpgpos = strpos($row['Uri'], ".jpg");
//        echo 'fn--'.$row['Uri'];

	if ($jpgpos !== false) {
		$pos = $jpgpos + 4;
		$fileName = substr($row['Uri'], 0, $pos);
	} elseif ($pngpos !== false) {
		$pos = $pngpos + 4;
		$fileName = substr($row['Uri'], 0, $pos);
	} else {
		$fileName = $row['Uri'];
	}
//        echo $fileName;
	$fileName = htmlspecialchars(trim($fileName)); // ibcqQKlKGGztd1.png-2015-06-20 03:33:16
	$fileName = str_replace(" ", "_", $fileName);
	$fileName = str_replace(",", "_", $fileName);
	$fileName = str_replace("-", "_", $fileName);
//	$fileName = str_replace("__", "_", $fileName);
	$fileName = preg_replace('/[^a-z0-9.]/i', '_', $fileName);
	
//	echo $_SERVER['DOCUMENT_ROOT'] . '/modules/boonex/photos/data/files/' . $fileName;
	if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/modules/boonex/photos/data/files/' . $fileName)) {
		$files[] = $site['url'] . 'modules/boonex/photos/data/files/' . $fileName;
	} elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . '/modules/boonex/photos/data/files/' . $fileName . '.jpg')) {
		$files[] = $site['url'] . 'modules/boonex/photos/data/files/' . $fileName . '.jpg';
	} elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . '/modules/boonex/photos/data/files/' . $fileName . '.JPG')) {
		$files[] = $site['url'] . 'modules/boonex/photos/data/files/' . $fileName . '.JPG';
	}elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . '/modules/boonex/photos/data/files/' . $fileName . '.jpeg')) {
		$files[] = $site['url'] . 'modules/boonex/photos/data/files/' . $fileName . '.jpeg';
	}elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . '/modules/boonex/photos/data/files/' . $fileName . '.JPEG')) {
		$files[] = $site['url'] . 'modules/boonex/photos/data/files/' . $fileName . '.JPEG';
	} elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . '/modules/boonex/photos/data/files/' . $fileName . '.png')) {
		$files[] = $site['url'] . 'modules/boonex/photos/data/files/' . $fileName . '.png';
	}elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . '/modules/boonex/photos/data/files/' . $fileName . '.PNG')) {
		$files[] = $site['url'] . 'modules/boonex/photos/data/files/' . $fileName . '.PNG';
	} else {
		$files[] = $site['url'] . 'modules/boonex/photos/data/files/' . $row['ID'] . '.' . $row['Ext'];
	}

}


try {
	$zip = new ZipArchive();
	$tmp_file = tempnam('../photodownload/','');
	$zip->open($tmp_file, ZipArchive::CREATE);

	foreach($files as $file){
		$download_file = file_get_contents($file);
		$zip->addFromString(basename($file),$download_file);
	}

	$zip->close();
	header('Content-disposition: attachment; filename=Pictures.zip');
	header('Content-type: application/zip');
	readfile($tmp_file);
	unlink($tmp_file);
}
catch(Exception $e){
	$UserData = $e->getMessage();
	global $logClassObj;
	$logClassObj->setLevel(1);
	$logClassObj->setModule("photo");
	$logClassObj->setSubmodule("download");
	$logClassObj->commonWriteLogInOne("\n---------------------------start--------------------------", "INFO");
	$logClassObj->commonWriteLogInOne("---photo details-----", "INFO");
	$logClassObj->commonWriteLogInOne($UserData, "INFO");
	$logClassObj->commonWriteLogInOne("---------------------------------------------------END -----------------------------------------------", "INFO");
}

?>
