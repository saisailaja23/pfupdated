<?php
define('BX_PROFILE_PAGE', 1);

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );    

$aData = '';
$id = $_GET['profile'];
/*
bx_import('BxTemplProfileView');
bx_import('BxDolInstallerUtils');

$oProfile = new BxBaseProfileGenerator( $id );  
$aCopA = $oProfile->_aProfile;
$aCopB = $oProfile->_aCouple;
//print_r($oProfile);

$oPPV = new BxTemplProfileView($oProfile, $site, $dir); 
*/
if(isset($_GET['p']) && !isset($_GET['v'])){
	$sql = "SELECT * FROM `bx_photos_main` WHERE `Owner` = '$id'";
	$result = mysql_query($sql);
	$nos = mysql_num_rows($result);
	if ($nos >= 1) {
		while($row = mysql_fetch_array($result))
		{ 
			$filename = '/var/www/html/pf/modules/boonex/photos/data/files/'.$row['ID'].".".$row['Ext'];
			if (file_exists($filename)) {
				$aData .=  
				<<<EOF
				<a href='http://www.parentfinder.com/m/photos/get_image/file/{$row[Hash]}.{$row[Ext]}'>
				<img title="{$row['Title']}"
				alt="{$row['Desc']}" 
				src="http://www.parentfinder.com/m/photos/get_image/file/{$row[Hash]}.{$row[Ext]}">
				</a>;-
EOF;
			}
		}
	}
	else{
		$aData .= "Photo Not Available;-";
	}
}
elseif(isset($_GET['v']) && !isset($_GET['p'])){
	$sql = "SELECT * FROM `RayVideoFiles` WHERE `Owner` = '$id' AND Time > 0";
	$result = mysql_query($sql);
	$nos = mysql_num_rows($result);
	if ($nos >= 1) {
	$i = 1;
		while($row = mysql_fetch_array($result))
		{ 
			if($row['Source'] == 'youtube' && $nos >= 1){
				$aData .= "<div><iframe title='YouTube video player' width='560' height='345' src='http://www.youtube.com/embed/".$row['Video']."' frameborder='0' allowfullscreen></iframe></div>";
				$aData .= "<div>".$row['Description']."</div>;-";
			}elseif($row['Source'] != 'youtube' && $nos >= 1){
				$aData .=  
				<<<EOF
				<div><a href='http://www.parentfinder.com/flash/modules/video/files/{$row[ID]}.flv' style='display:block;width:560px;height:345px' id='player{$i}'></a> 
				<script>
				flowplayer("player{$i}", "flowplayer-3.2.6.swf",{
				width: '560',
				height: '345',
				clip: {
					autoPlay: false,
				  }
				});
				</script></div>
EOF;
				$aData .= "<div>".$row['Description']."</div>;-";
				$i ++;
			}
		}
	}
	else{
		$aData .= "Video Not Available;-";
	}
}
echo $aData;
?>