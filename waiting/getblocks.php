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
$sql = "SELECT * FROM `aqb_pc_members_blocks` WHERE `owner_id` = '$id'";
$result = mysql_query($sql);
$nos = mysql_num_rows($result);
if ($nos >= 1) {
	while($row = mysql_fetch_array($result))
	{ 
			$aData .=  
			<<<EOF
			<div class="storyHeaderBar" style="width:646px;">{$row['title']}</div>
			  <div class="storyBox" style="width:600px;overflow:hidden;">
				<p></p>
				<p>{$row['content']}</p>
				<p></p>
				<div class="clear"></div>
			  </div>;-
EOF;
	}
}
echo $aData;
?>