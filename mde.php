<?php

require_once('inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'utils.inc.php');

bx_import('BxTemplProfileView');

define('SYS_TBL_PORIFLE_FIELDS', 'sys_profile_fields');
define('SYS_VIEW_MEM_BLOCK', 17);

check_logged();

$sqlQuery = "SELECT `ID`, `Name` FROM `" . SYS_TBL_PORIFLE_FIELDS . "` WHERE `ViewMembBlock`=" . SYS_VIEW_MEM_BLOCK;
$aFields = $GLOBALS['MySQL']->getAll($sqlQuery);

foreach ($aFields as $aField) {
	// create block in sys_profile_fields
	$sqlQuery = "REPLACE `" . SYS_TBL_PORIFLE_FIELDS . "` SET `Name`='{$aField['Name']}_block', `Type`='block', `ViewMembOrder`='{$aField['ID']}'";
	echo "<br/>$sqlQuery;";
	$GLOBALS['MySQL']->res($sqlQuery);
	$iId = (int)$GLOBALS['MySQL']->lastId();
	// bound field to new block
	$sqlQuery = "UPDATE `" . SYS_TBL_PORIFLE_FIELDS . "` SET `ViewMembOrder`='1', `ViewMembBlock`=$iId WHERE `ID`={$aField['ID']} LIMIT 1";
	echo "<br/>$sqlQuery;";
	$GLOBALS['MySQL']->res($sqlQuery);
	// insert new block to sys_profile_pages
	$sqlQuery = "REPLACE `sys_page_compose` SET `Page`='profile', `PageWidth`='998px', `Desc`='Profile Fields Block {$aField['Name']}', `Caption`='_FieldCaption_{$aField['Name']}_View', `Func`='PFBlock', `Content`='$iId', `DesignBox`='1', `ColWidth`='66', `Visible`='non,memb'";
	echo "<br/>$sqlQuery;";
	$GLOBALS['MySQL']->res($sqlQuery);
}

?>