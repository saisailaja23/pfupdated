<?php
require_once( '../../../inc/header.inc.php' );
require_once( '../../../inc/profiles.inc.php' );

$user_id = getLoggedId();
$name = @$_GET['name'];

if($name){
	$ltr_name = $_GET['ltr_name'];
	$cusID = $_GET['cusID'];

	/**/
	$custLtr = '';
	switch ( $name ) {
		case 'MOTHER':
			$q = "SELECT DearBirthParent as ltr FROM `Profiles_draft` WHERE ID=$user_id";
			break;
		case 'AGENCY':
			$q = "SELECT agency_letter as ltr FROM `Profiles_draft` WHERE ID=$user_id";
			break;
		case 'HIM':
			$q = "SELECT DescriptionMe as ltr  FROM `Profiles_draft` WHERE ID=$user_id";
			break;
		case 'HER':
			$_profile = getProfileInfo( $user_id );
			$q = "SELECT DescriptionMe as ltr FROM `Profiles_draft` WHERE ID= ".$_profile['Couple'];
			break;
		case 'THEM':
			$q = "SELECT letter_aboutThem as ltr FROM `Profiles_draft` WHERE ID=$user_id";
			break;
		case 'OTHER':
			break;
		case 'HOME':
			$q = "SELECT About_our_home as ltr FROM `Profiles_draft` WHERE ID=$user_id";
			break;
		default:
			$custLtr = 1;
			$name  = str_replace( "'", "''", $name );
			$q = "SELECT description as ltr, label FROM `letter` WHERE profile_id=$user_id and id ='$cusID'";
			break;
	}
	$result = mysql_query($q);
	while($ltrCon = mysql_fetch_assoc($result)){
		//print_r($ltrCon);
		$ltr = $ltrCon['ltr'];
		if($custLtr != ''){ $label = $ltrCon['label']; }
	}

	/**/

	$ID = $_GET['imgId'];
	$ID = str_replace("_", ", ", $ID);

	$photodeatils = getProfileInfo($user_id);

	$result123 = mysql_query("SELECT  ID, `Uri`, Ext FROM  `bx_photos_main` bm
	JOIN sys_albums_objects so ON so.id_object = bm.`ID` 
	WHERE bm.ID IN($ID) ORDER BY so.obj_order");


	$files = null;
	while ($row = mysql_fetch_array($result123)) {

		$fileName = "";
		$extension = "";
		$pngpos = strpos($row['Uri'], ".png");
		$jpgpos = strpos($row['Uri'], ".jpg");

		if ($jpgpos !== false) {
			$pos = $jpgpos + 4;
			$fileName = substr($row['Uri'], 0, $pos);
		} elseif ($pngpos !== false) {
			$pos = $pngpos + 4;
			$fileName = substr($row['Uri'], 0, $pos);
		} else {
			$fileName = $row['Uri'];
		}

		$fileName = htmlspecialchars(trim($fileName)); // ibcqQKlKGGztd1.png-2015-06-20 03:33:16
		$fileName = str_replace(" ", "_", $fileName);
		$fileName = str_replace(",", "_", $fileName);
		$fileName = str_replace("-", "_", $fileName);
		$fileName = preg_replace('/[^a-z0-9.]/i', '_', $fileName);
		
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
		$tmp_file = tempnam('../download/','');
		$zip->open($tmp_file, ZipArchive::CREATE);
		/**/
		if($files != ''){
			//$zip->addEmptyDir('Images'); 
		}
		/**/
		
		//if($custLtr != ''){ $ltr_name = $label; }
		
		$inner = $ltr_name.'/';
		if($ltr != ''){
			if(empty($files)){
				$zip->addFromString($inner.$ltr_name.'.html', $ltr);
			}else{
				$zip->addFromString($ltr_name.'.html', $ltr);
			}
			
			$inner = 'Images/';
		}else{
			$inner = $inner.'Images/';
		}	
		foreach($files as $file){
			$download_file = file_get_contents($file);
			$zip->addFromString($inner.basename($file),$download_file);
		}

		$zip->close();
		/*
		$ltr_name = $ltr_name.'.zip';
		//header('Content-disposition: attachment; filename='.$ltr_name.'.zip');
		header('Content-disposition: attachment; filename='.$ltr_name);
		header('Content-type: application/zip');
		/**/
	/**/
	header('Content-Type: application/zip, application/octet-stream');
	$ltr_name2 = $ltr_name.'.zip';
	// $ltr_name2 = 'test.zip';

	header("Content-Disposition: attachment; filename=\"".$ltr_name2."\"");		
	/**/	
		readfile($tmp_file);
		unlink($tmp_file);
	}
	catch(Exception $e){
		
	}
}else{

}

?>