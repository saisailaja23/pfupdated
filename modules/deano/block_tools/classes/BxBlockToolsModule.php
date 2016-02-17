<?php
/***************************************************************************
* Date				: Sat Dec 19, 2009
* Copywrite			: (c) 2009 by Dean J. Bassett Jr.
* Website			: http://www.deanbassett.com
*
* Product Name		: Privacy Page Editor
* Product Version	: 1.1.0000
*
* IMPORTANT: This is a commercial product made by Dean Bassett Jr.
* and cannot be modified other than personal use.
*  
* This product cannot be redistributed for free or a fee without written
* permission from Dean Bassett Jr.
*
***************************************************************************/

require_once(BX_DIRECTORY_PATH_CLASSES . 'BxDolModule.php');

class BxBlockToolsModule
    extends BxDolModule
    {

	    function BxBlockToolsModule(&$aModule) {
			parent::BxDolModule($aModule);
		}

	    function actionAdministration () {
	        global $_page, $_page_cont;
	        require_once(BX_DIRECTORY_PATH_INC . 'admin_design.inc.php');
	        $logged['admin']=member_auth(1, true, true);
	        $iNameIndex=9;
	        $_page=array
	            (
	            'name_index' => $iNameIndex,
	            'css_name' => array('forms_adv.css'),
	            'js_name' => array(),
	            'header' => _t('_dbcs_BT_BlockToolsHeader'),
	            'header_text' => _t('_dbcs_BT_BlockToolsHeaderText')
	            );
	        $_page_cont[$iNameIndex]['page_main_code'] .= $this->DeanoMainCode();
	        PageCodeAdmin();
		}  
		
		function saveCopy($iID) {
			$dbcsFN = BX_DIRECTORY_PATH_MODULES . 'deano/block_tools/backup/copies.txt';
			if (file_exists($dbcsFN)) {
				$a = file_get_contents(BX_DIRECTORY_PATH_MODULES . 'deano/block_tools/backup/copies.txt');
				$a .= "," . $iID;
				file_put_contents(BX_DIRECTORY_PATH_MODULES . 'deano/block_tools/backup/copies.txt',$a);
			} else {
				 file_put_contents(BX_DIRECTORY_PATH_MODULES . 'deano/block_tools/backup/copies.txt',$iID);
			}
		}

		function DeanoMainCode()
		    {
			$sExistedC=_t('_dbcs_BT_BlockToolsBoxHeader');
	        $sCss = $this->_oTemplate->addCss('unit.css', true);
			$sJs=$this->_oTemplate->addJs('btfunctions.js', true);
	        $sAction = BX_DOL_URL_ROOT . 'modules/?r=deano_meta/administration/';

			$sTab = $_GET['tab'];
			if ($sTab == '') $sTab = 'cb';

			$sCode = $this->GetUnit($sTab);

			bx_import('BxDolPageView');
	        //$sActions = BxDolPageView::getBlockCaptionMenu(mktime(), array(
	        //    'add_unit' => array('href' => $sAction, 'title' => _t('_dbcs_BT_BlockToolsGoBack'), 'onclick' => '', 'active' => 0),
	        //));
            return DesignBoxContent($sExistedC, $sCss . $sJs . $sCode, 1, $sActions);

        }

	    function GetUnit($sTab) {
			$tab1='';$tab2='';$tab3='';$tab4='';$tab5='';
			switch ($sTab) {
				case 'cb':
					$dbcsFN = BX_DIRECTORY_PATH_MODULES . 'deano/block_tools/backup/copies.txt';
					$iDB = intval($_GET['deletecopy']);
					if ($iDB > 0) {
						// delete copy from system
						$this->_oDb->deleteBlock($iDB);
						clearCacheFile(BX_DIRECTORY_PATH_DBCACHE . 'sys_page_compose.inc');
						// now remove id from backup file.
						$sS = file_get_contents($dbcsFN);
						$aID = explode(",",$sS);
						foreach($aID as $key => $value) {
							if ($value == $iDB) unset($aID[$key]);
						}
						if (count($aID) > 0) {
							$sS = implode(",",$aID);
							file_put_contents($dbcsFN,$sS);
						} else {
							unlink($dbcsFN);
						}
					}
					if ($_POST['copy'] != '') {
						$sPageFrom = $_POST['pagefrom'];
						$sPageTo = $_POST['pageto'];
						$iPageBlock = intval($_POST['pageblock']);
						if ($sPageFrom == '') {
							$sMsg = MsgBox('<font color="#FF0000">' . _t("_dbcs_BT_PageFromError") . '</font>',4);
						} elseif ($sPageTo == ''){
							$sMsg = MsgBox('<font color="#FF0000">' . _t("_dbcs_BT_PageToError") . '</font>',4);
						} elseif ($iPageBlock == 0){
							$sMsg = MsgBox('<font color="#FF0000">' . _t("_dbcs_BT_NoBlockError") . '</font>',4);
						} else {
							// copy the block.
							$this->_oDb->copyBlock($iPageBlock,$sPageTo);
							$iLID = $this->_oDb->getLastID();
							$this->saveCopy($iLID);
							$sMsg = MsgBox($iPageBlock . " - " . _t("_Block Copied"),4);
						}
					}
					$tab1=' class="active"';
					$sectiondesc = _t('_dbcs_BT_section1_desc');
					$lKeys = $this->_oDb->getPages();
					$sectioncontent = '
					<form method="POST" action="?r=block_tools/administration/&tab=cb">
						<table>
							<tr>
								<td class="boldright">' . _t('_dbcs_PB_PageFrom') . '</td>
								<td><select size="1" name="pagefrom" onchange="javascript: LoadBlocks(this.value)">
								<option value="">--- Select Copy From Page ---</option>';
					foreach ($lKeys as $iID => $dbData) {
						$sName = $dbData['Name'];
						$sTitle = $dbData['Title'];
						if ($_COOKIE['dbblocka'] == $sName) {
							$sectioncontent .= '<option selected="selected" value="' . $sName . '">' . $sTitle . '</option>';
						} else {
							$sectioncontent .= '<option value="' . $sName . '">' . $sTitle . '</option>';
						}
					}
					$sectioncontent .= '
								</select> </td>
							</tr>
							<tr>
								<td class="boldright">' . _t('_dbcs_PB_PageTo') . '</td>
								<td><select size="1" name="pageto">
								<option value="">--- Select Copy To Page ---</option>';
					foreach ($lKeys as $iID => $dbData) {
						$sName = $dbData['Name'];
						$sTitle = $dbData['Title'];
						$sectioncontent .= '<option value="' . $sName . '">' . $sTitle . '</option>';
					}
					$sectioncontent .= '
								</select></td>
							</tr>
						</table>
					';
					// if the cookie is set, load the page blocks and delete the cookie.
					if($_COOKIE['dbblocka'] != '') {
						$sectioncontent .= $this->LoadBlocks($_COOKIE['dbblocka']);
						setcookie ("dbblocka", "", time() - 3600);
						$sectioncontent .= '
						<div>&nbsp;</div>
						<div class="input_wrapper input_wrapper_submit">
							<div class="button_wrapper">
								<input type="submit" value="' . _t('_dbcs_BT_Copy') . '" name="copy" class="form_input_submit">
								<div class="button_wrapper_close">
								</div>
							</div>
							<div class="input_close input_close_submit">
							</div>
						</div>
						<div class="clear_both">
						</div>
						';
					}

						$sectioncontent .= '
						</form>
						<div class="dblineL"></div>
						<div class="sectiondesc">' . _t('_dbcs_BT_CopiesMsg') . '</div>
						';
						// Delete copys section.
						$dbcsFN = BX_DIRECTORY_PATH_MODULES . 'deano/block_tools/backup/copies.txt';
						if (file_exists($dbcsFN)) {
							$sS = file_get_contents($dbcsFN);
							$aID = explode(",",$sS);
							$sectioncontent .= '
								<table border="1" cellpadding="4" cellspacing="0" bordercolor="#9FB1BC">
									<tr>
										<td><b>Block ID</b></td>
										<td><b>Block Caption</b></td>
										<td>&nbsp;</td>
									</tr>
							';
							foreach ($aID as $v) {
							$sectioncontent .= '
									<tr>
										<td>' . $v . '</td>
										<td>' . _t($this->_oDb->getBlockName($v)) . '</td>
										<td><a href="?r=block_tools/administration/&tab=cb&deletecopy=' . $v . '">Delete</a></td>
									</tr>
							';
								
							}
							$sectioncontent .= '
								</table>
							';
						} else {
							$sectioncontent .= '<div>' . MsgBox(_t("_dbcs_BT_NoCopiesFound")) . '</div>';
						}
				break;
				case 'db':
					$aBlocks = $this->_oDb->getAllBlocks();
					$sectioncontent = '';
					$iUpdated = 0;
					if($_POST['save'] != '') {
						foreach($_POST as $name => $value) {
							$pos = strpos($name,"dbox_");
							if ($pos !== false) {
								$dbid = intval(str_replace('dbox_', '', $name));
								// loop through aBlocks looking for this ID and if the value is different, replace it.
								foreach ($aBlocks as $iID => $dbData) {
									if ($dbData['ID'] == $dbid) {
										if ($dbData['DesignBox'] != $value) {
											// value is different. Save it.
											$this->_oDb->saveDesignBox($dbid,$value);
											$iUpdated = 1;
											//$sectioncontent .= "$name : $value<br>";
										}
									}
								}
							}
						}
						if ($iUpdated == 1) {
							$sectioncontent .= MsgBox(_t("_dbcs_BT_BoxChangesSaved"),4);
							clearCacheFile(BX_DIRECTORY_PATH_DBCACHE . 'sys_page_compose.inc');
							// Blocks have been saved, so pull refresh array of data.
							$aBlocks = $this->_oDb->getAllBlocks();
						}
					}
					$tab2=' class="active"';
					$sectiondesc = _t('_dbcs_BT_section2_desc');
					$sectioncontent .= '
						<form method="POST" action="?r=block_tools/administration/&tab=db">
						<table border="0">
							<tr>
								<td><b>Page</b></td>
								<td><b>Block Caption</b></td>
								<td><b>Design Box</b></td>
							</tr>
					';
					foreach ($aBlocks as $iID => $dbData) {
						$sectioncontent .= '	<tr>';
						$sPage = $this->_oDb->getTitle($dbData['Page']);
						if ($sPage == '') $sPage = $dbData['Page'];
						$sectioncontent .= '		<td>' . $sPage . '</td>';
						$sectioncontent .= '		<td>' . _t($dbData['Caption']) . '</td>';
						//$sectioncontent .= '		<td>' . $dbData['Caption'] . '</td>';
						$sectioncontent .= '		<td><input type="text" name="dbox_' . $dbData['ID'] . '" size="6" value="' . $dbData['DesignBox'] . '"></td>';
						$sectioncontent .= '	</tr>';
						//$sectioncontent .= '<div>' . _t($dbData['Caption']) . '</div>';
					}
					$sectioncontent .= '
						</table>
						<div>&nbsp;</div>
						<div class="input_wrapper input_wrapper_submit">
							<div class="button_wrapper">
								<input type="submit" value="' . _t('_dbcs_BT_Save') . '" name="save" class="form_input_submit">
								<div class="button_wrapper_close">
								</div>
							</div>
							<div class="input_close input_close_submit">
							</div>
						</div>
						<div class="clear_both">
						</div>
						</form>
					';

				break;
				case 'pb':
					$sectioncontent = '';
					if ($_POST['B1'] != '') {
						// Insert PHP Block.
						$s1 = $_POST['phppage'];
						$s2 = $_POST['phplkey'];
						$s3 = $_POST['phpltext'];
						$s4 = process_db_input($_POST['dbcsBTphpcode']);
						if (!$this->_oDb->keyExists($s2)) {
							// key does not exist. Add it to all installed languages under my catagory.
							addStringToLanguage($s2, $s3, -1, $this->_oDb->getLangCat());
						}
						// now insert the php block on specified page.
						$this->_oDb->insertPHPBlock($s1,$s2,$s3,$s4);
				
						$sectioncontent .= '	<div id="quotes_box">' . MsgBox(_t('_dbcs_BT_PHPBlockSaved'),4) . '</div>';
						$sectioncontent .= '	<div class="clear_both"></div>';
						clearCacheFile(BX_DIRECTORY_PATH_DBCACHE . 'sys_page_compose.inc');
						clearCacheFile(BX_DIRECTORY_PATH_DBCACHE . 'sys_options.php');

					}

					$PHPid = intval($_GET['phpbid']);
					if (isset($_POST['B2']) && $PHPid > 0) {
						// save button clicked.
						$sLkey = $_POST['phplkey'];
						$sPHPCode = process_db_input($_POST['dbcsBTphpcode']);
						$this->_oDb->updatePHPBlock($PHPid,$sLkey,$sPHPCode);
						$sectioncontent .= '	<div id="quotes_box">' . MsgBox(_t('_dbcs_BT_PHPBlockSavedE'),4) . '</div>';
						$sectioncontent .= '	<div class="clear_both"></div>';
						$_GET['phpbid'] = 0;
						clearCacheFile(BX_DIRECTORY_PATH_DBCACHE . 'sys_page_compose.inc');
						clearCacheFile(BX_DIRECTORY_PATH_DBCACHE . 'sys_options.php'); 
					}

					if (isset($_POST['B3']) && $PHPid > 0) {
						// delete button clicked.
						$this->_oDb->deleteBlock($PHPid);
						$sectioncontent .= '	<div id="quotes_box">' . MsgBox(_t('_dbcs_BT_PHPBlockDeletedE'),4) . '</div>';
						$sectioncontent .= '	<div class="clear_both"></div>';
						$_GET['phpbid'] = 0;
						clearCacheFile(BX_DIRECTORY_PATH_DBCACHE . 'sys_page_compose.inc');
						clearCacheFile(BX_DIRECTORY_PATH_DBCACHE . 'sys_options.php'); 
					}


					$tab3=' class="active"';
					$sectiondesc = _t('_dbcs_BT_section3_desc');
					$emode = intval($_GET['emode']);
					$e0 = "checked ";
					$e1 = "";
					if ($emode == 1) {
						$e0 = "";
						$e1 = "checked ";
					}
$sectioncontent .= '
<table border="0">
	<tr>
		<td>New PHP Block</td>
		<td width="50"><input type="radio" value="0" ' . $e0 . 'name="emode" onclick="switchMode(\'pb\',\'0\')"></td>
		<td>Edit/Delete PHP Block</td>
		<td><input type="radio" value="1" ' . $e1 . 'name="emode" onclick="switchMode(\'pb\',\'1\')"></td>
	</tr>
</table>
';
if($emode == 0) {
$sectioncontent .= '<div>';
} else {
$sectioncontent .= '<div style="display:none;">';
}
$sectioncontent .= '
<form method="POST" action="?r=block_tools/administration/&tab=pb&emode=' . $emode . '">
  <table cellspacing="0" cellpadding="0" class="form_advanced_table">
    <tr>
      <td class="caption">' . _t('_dbcs_BT_PHPBlock_P') . '</td>
      <td class="value"><div class="clear_both"></div>
        <div class="input_wrapper input_wrapper_select_box">
          <select name="phppage" class="form_input_select">
          ';
$lKeys = $this->_oDb->getPages();
foreach ($lKeys as $iID => $dbData) {
	$sLName = $dbData['Name'];
	$sLTitle = $dbData['Title'];
	$sectioncontent .= '<option value="' . $sLName . '">' . $sLTitle . '</option>';
}
$sectioncontent .= '
</select>
</div>
<div class="clear_both"></td>
	</tr>

    <tr>
      <td class="caption">' . _t('_dbcs_BT_PHPBlock_LKey') . '</td>
      <td class="value"><div class="clear_both"></div>
        <div class="input_wrapper input_wrapper_text">
          <input type="text" name="phplkey" value="_New PHP Block" class="form_input_text">
          <div class="input_close input_close_text"></div>
        </div>
        <div class="clear_both"></div></td>
	</tr>
    <tr>
      <td class="caption">' . _t('_dbcs_BT_PHPBlock_LText') . '</td>
      <td class="value"><div class="clear_both"></div>
        <div class="input_wrapper input_wrapper_text">
          <input type="text" name="phpltext" value="New PHP Block" class="form_input_text">
          <div class="input_close input_close_text"></div>
        </div>
        <div class="clear_both"></div></td>
	</tr>
    <tr>
      <td class="caption">' . _t('_dbcs_BT_PHPBlock_Code') . '</td>
      <td class="value"><div class="input_wrapper input_wrapper_textarea" style="width: 100%">
          <div class="input_border">
            <textarea name="dbcsBTphpcode" class="form_input_textarea" rows="1" cols="20">echo \'Hello World!\';</textarea>
          </div>
          <div class="input_close_textarea left top"></div>
          <div class="input_close_textarea left bottom"></div>
          <div class="input_close_textarea right top"></div>
          <div class="input_close_textarea right bottom"></div>
        </div></td>
	</tr>

    <tr>
      <td class="caption"></td>
      <td class="value"><input type="submit" value="' . _t('_dbcs_BT_PHPBlock_Insert') . '" name="B1"></td>
    </tr>
    
  </table>
</form>
</div>
';
// *******************************************************************************************************************
$PHPid = intval($_GET['phpbid']);
if($emode == 1) {
$sectioncontent .= '<div>';
} else {
$sectioncontent .= '<div style="display:none;">';
}
$sectioncontent .= '
<form method="POST" action="?r=block_tools/administration/&tab=pb&emode=' . $emode . '&phpbid=' . $PHPid . '">
  <table cellspacing="0" cellpadding="0" class="form_advanced_table">
    <tr>
      <td class="caption">' . _t('_dbcs_BT_PHPBlock_PE') . '</td>
      <td class="value"><div class="clear_both"></div>
        <div class="input_wrapper input_wrapper_select_box">
          <select name="phppage" class="form_input_select" onChange="javascript: LoadPHP(this.value,' . $emode . ')">
          ';
if ($PHPid == 0) {
	$sectioncontent .= '<option selected value="0">Select PHP Block to Edit or Delete</option>';
} else {
	$sectioncontent .= '<option value="0">Select PHP Block to Edit or Delete</option>';
}
$lKeys = $this->_oDb->getPHPBlocks();
foreach ($lKeys as $iID => $dbData) {
	$iID = (int)$dbData['ID'];
	$sDesc = $dbData['Desc'];
	$sPage = $dbData['Page'];
	$sSelected = '';
	if ($PHPid == $iID) {
		$sSelected = 'selected ';
	}
	$sectioncontent .= '<option ' . $sSelected . 'value="' . $iID . '">Page: ' . $sPage . ' - Desc: ' . $sDesc . '</option>';
}
if ($PHPid > 0) {
	$aPHPData = $this->_oDb->getPHPBlockData($PHPid);
}
$sectioncontent .= '
</select>
</div>
<div class="clear_both"></td>
	</tr>

    <tr>
      <td class="caption">' . _t('_dbcs_BT_PHPBlock_LKeyE') . '</td>
      <td class="value"><div class="clear_both"></div>
        <div class="input_wrapper input_wrapper_text">
          <input type="text" name="phplkey" value="' . $aPHPData['Caption'] . '" class="form_input_text">
          <div class="input_close input_close_text"></div>
        </div>
        <div class="clear_both"></div></td>
	</tr>
    <tr>
      <td class="caption">' . _t('_dbcs_BT_PHPBlock_CodeE') . '</td>
      <td class="value"><div class="input_wrapper input_wrapper_textarea" style="width: 100%">
          <div class="input_border">
            <textarea name="dbcsBTphpcode" class="form_input_textarea" rows="1" cols="20">' . $aPHPData['Content'] . '</textarea>
          </div>
          <div class="input_close_textarea left top"></div>
          <div class="input_close_textarea left bottom"></div>
          <div class="input_close_textarea right top"></div>
          <div class="input_close_textarea right bottom"></div>
        </div></td>
	</tr>

    <tr>
      <td class="caption"></td>
      <td class="value"><input type="submit" value="' . _t('_dbcs_BT_PHPBlock_SaveE') . '" name="B2">&nbsp;<input type="submit" value="' . _t('_dbcs_BT_PHPBlock_DeleteE') . '" name="B3"></td>
    </tr>
    
  </table>
</form>
</div>
';

				break;
				case 'hb':
					$sectioncontent = '';
					if ($_POST['B1'] != '') {
						// Insert HTML Block.
						$s1 = $_POST['htmlpage'];
						$s2 = $_POST['htmllkey'];
						$s3 = $_POST['htmlltext'];
						$s4 = process_db_input($_POST['dbcsBTHTMLcode']);
						if (!$this->_oDb->keyExists($s2)) {
							// key does not exist. Add it to all installed languages under my catagory.
							addStringToLanguage($s2, $s3, -1, $this->_oDb->getLangCat());
						}

						// now insert the HTML block on specified page.
						$this->_oDb->insertHTMLBlock($s1,$s2,$s3,$s4);
				
						$sectioncontent .= '	<div id="quotes_box">' . MsgBox(_t('_dbcs_BT_HTMLBlockSaved'),4) . '</div>';
						$sectioncontent .= '	<div class="clear_both"></div>';
						clearCacheFile(BX_DIRECTORY_PATH_DBCACHE . 'sys_page_compose.inc');
						clearCacheFile(BX_DIRECTORY_PATH_DBCACHE . 'sys_options.php');

					}

					$HTMLid = intval($_GET['htmlbid']);
					if (isset($_POST['B2']) && $HTMLid > 0) {
						// save button clicked.
						$sLkey = $_POST['htmllkey'];
						$sHTMLCode = process_db_input($_POST['dbcsBTHTMLcode']);
						$this->_oDb->updateHTMLBlock($HTMLid,$sLkey,$sHTMLCode);
						$sectioncontent .= '	<div id="quotes_box">' . MsgBox(_t('_dbcs_BT_HTMLBlockSavedE'),4) . '</div>';
						$sectioncontent .= '	<div class="clear_both"></div>';
						$_GET['htmlbid'] = 0;
						clearCacheFile(BX_DIRECTORY_PATH_DBCACHE . 'sys_page_compose.inc');
						clearCacheFile(BX_DIRECTORY_PATH_DBCACHE . 'sys_options.php'); 
					}

					if (isset($_POST['B3']) && $HTMLid > 0) {
						// delete button clicked.
						$this->_oDb->deleteBlock($HTMLid);
						$sectioncontent .= '	<div id="quotes_box">' . MsgBox(_t('_dbcs_BT_HTMLBlockDeletedE'),4) . '</div>';
						$sectioncontent .= '	<div class="clear_both"></div>';
						$_GET['htmlbid'] = 0;
						clearCacheFile(BX_DIRECTORY_PATH_DBCACHE . 'sys_page_compose.inc');
						clearCacheFile(BX_DIRECTORY_PATH_DBCACHE . 'sys_options.php'); 
					}


					$tab4=' class="active"';
					$sectiondesc = _t('_dbcs_BT_section4_desc');
					$emode = intval($_GET['emode']);
					$e0 = "checked ";
					$e1 = "";
					if ($emode == 1) {
						$e0 = "";
						$e1 = "checked ";
					}
$sectioncontent .= '
<table border="0">
	<tr>
		<td>New HTML Block</td>
		<td width="50"><input type="radio" value="0" ' . $e0 . 'name="emode" onclick="switchMode(\'hb\',\'0\')"></td>
		<td>Edit/Delete HTML Block</td>
		<td><input type="radio" value="1" ' . $e1 . 'name="emode" onclick="switchMode(\'hb\',\'1\')"></td>
	</tr>
</table>
';
if($emode == 0) {
$sectioncontent .= '<div>';
} else {
$sectioncontent .= '<div style="display:none;">';
}
$sectioncontent .= '
<form method="POST" action="?r=block_tools/administration/&tab=hb&emode=' . $emode . '">
  <table cellspacing="0" cellpadding="0" class="form_advanced_table">
    <tr>
      <td class="caption">' . _t('_dbcs_BT_HTMLBlock_P') . '</td>
      <td class="value"><div class="clear_both"></div>
        <div class="input_wrapper input_wrapper_select_box">
          <select name="htmlpage" class="form_input_select">
          ';
$lKeys = $this->_oDb->getPages();
foreach ($lKeys as $iID => $dbData) {
	$sLName = $dbData['Name'];
	$sLTitle = $dbData['Title'];
	$sectioncontent .= '<option value="' . $sLName . '">' . $sLTitle . '</option>';
}
$sectioncontent .= '
</select>
</div>
<div class="clear_both"></td>
	</tr>

    <tr>
      <td class="caption">' . _t('_dbcs_BT_HTMLBlock_LKey') . '</td>
      <td class="value"><div class="clear_both"></div>
        <div class="input_wrapper input_wrapper_text">
          <input type="text" name="htmllkey" value="_New HTML Block" class="form_input_text">
          <div class="input_close input_close_text"></div>
        </div>
        <div class="clear_both"></div></td>
	</tr>
    <tr>
      <td class="caption">' . _t('_dbcs_BT_HTMLBlock_LText') . '</td>
      <td class="value"><div class="clear_both"></div>
        <div class="input_wrapper input_wrapper_text">
          <input type="text" name="htmlltext" value="New HTML Block" class="form_input_text">
          <div class="input_close input_close_text"></div>
        </div>
        <div class="clear_both"></div></td>
	</tr>
    <tr>
      <td class="caption">' . _t('_dbcs_BT_HTMLBlock_Code') . '</td>
      <td class="value"><div class="input_wrapper input_wrapper_textarea" style="width: 100%">
          <div class="input_border">
            <textarea name="dbcsBTHTMLcode" class="form_input_textarea" rows="1" cols="20">HTML Content Area</textarea>
          </div>
          <div class="input_close_textarea left top"></div>
          <div class="input_close_textarea left bottom"></div>
          <div class="input_close_textarea right top"></div>
          <div class="input_close_textarea right bottom"></div>
        </div></td>
	</tr>

    <tr>
      <td class="caption"></td>
      <td class="value"><input type="submit" value="' . _t('_dbcs_BT_HTMLBlock_Insert') . '" name="B1"></td>
    </tr>
    
  </table>
</form>
</div>
';
// *******************************************************************************************************************
$HTMLid = intval($_GET['htmlbid']);
if($emode == 1) {
$sectioncontent .= '<div>';
} else {
$sectioncontent .= '<div style="display:none;">';
}
$sectioncontent .= '
<form method="POST" action="?r=block_tools/administration/&tab=hb&emode=' . $emode . '&htmlbid=' . $HTMLid . '">
  <table cellspacing="0" cellpadding="0" class="form_advanced_table">
    <tr>
      <td class="caption">' . _t('_dbcs_BT_HTMLBlock_PE') . '</td>
      <td class="value"><div class="clear_both"></div>
        <div class="input_wrapper input_wrapper_select_box">
          <select name="htmlpage" class="form_input_select" onChange="javascript: LoadHTML(this.value,' . $emode . ')">
          ';
if ($HTMLid == 0) {
	$sectioncontent .= '<option selected value="0">Select HTML Block to Edit or Delete</option>';
} else {
	$sectioncontent .= '<option value="0">Select HTML Block to Edit or Delete</option>';
}
$lKeys = $this->_oDb->getHTMLBlocks();
foreach ($lKeys as $iID => $dbData) {
	$iID = (int)$dbData['ID'];
	$sCaption = $dbData['Caption'];
	$sPage = $dbData['Page'];
	$sSelected = '';
	if ($HTMLid == $iID) {
		$sSelected = 'selected ';
	}
	$sectioncontent .= '<option ' . $sSelected . 'value="' . $iID . '">Page: ' . $sPage . ' - Caption: ' . _t($sCaption) . '</option>';
}
if ($HTMLid > 0) {
	$aHTMLData = $this->_oDb->getHTMLBlockData($HTMLid);
}
$sectioncontent .= '
</select>
</div>
<div class="clear_both"></td>
	</tr>

    <tr>
      <td class="caption">' . _t('_dbcs_BT_HTMLBlock_LKeyE') . '</td>
      <td class="value"><div class="clear_both"></div>
        <div class="input_wrapper input_wrapper_text">
          <input type="text" name="htmllkey" value="' . $aHTMLData['Caption'] . '" class="form_input_text">
          <div class="input_close input_close_text"></div>
        </div>
        <div class="clear_both"></div></td>
	</tr>
    <tr>
      <td class="caption">' . _t('_dbcs_BT_HTMLBlock_CodeE') . '</td>
      <td class="value"><div class="input_wrapper input_wrapper_textarea" style="width: 100%">
          <div class="input_border">
            <textarea name="dbcsBTHTMLcode" class="form_input_textarea" rows="1" cols="20">' . $aHTMLData['Content'] . '</textarea>
          </div>
          <div class="input_close_textarea left top"></div>
          <div class="input_close_textarea left bottom"></div>
          <div class="input_close_textarea right top"></div>
          <div class="input_close_textarea right bottom"></div>
        </div></td>
	</tr>

    <tr>
      <td class="caption"></td>
      <td class="value"><input type="submit" value="' . _t('_dbcs_BT_HTMLBlock_SaveE') . '" name="B2">&nbsp;<input type="submit" value="' . _t('_dbcs_BT_HTMLBlock_DeleteE') . '" name="B3"></td>
    </tr>
    
  </table>
</form>
</div>
';



				break;
			}

			$sCode = '
				<div class="container">' . $sMsg . '
					<ul id="navPyra">
						<!-- CSS Tabs -->
						<li><a' . $tab1 . ' href="?r=block_tools/administration/&tab=cb">Copy Blocks</a></li>
						<li><a' . $tab2 . ' href="?r=block_tools/administration/&tab=db">Blocks Design 
						Box</a></li>
						<li><a' . $tab3 . ' href="?r=block_tools/administration/&tab=pb">Create/Edit PHP Blocks</a></li>
						<li><a' . $tab4 . ' href="?r=block_tools/administration/&tab=hb">Create/Edit HTML Blocks</a></li>
					</ul>
				<div class="sectiondesc">' . $sectiondesc . '</div>
				<div class="sectioncontent">' . $sectioncontent . '</div>
				</div>
			';
	        return $sCode;
	    }

		function LoadBlocks($sPage) {
			//$aBlocks = $this->_oDb->getActiveBlocks($sPage);
			$numcol = $this->_oDb->getNumCols($sPage);
			$colwidth = intval(100 / $numcol);
			$r = '
				<table border="0" width="100%">
					<tr>
						<td colspan="' . $numcol . '">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="' . $numcol . '"><b>' . _t('_dbcs_BT_Active Page Blocks') . '</b></td>
					</tr>
					<tr>
			';
			for($x = 1; $x <= $numcol; $x++) {
				$r .= '<td width="' . $colwidth . '%" align="center" valign="top" style="border: 1px solid #CCCCCC">';
				$aBlocks = $this->_oDb->getActiveBlocksForCol($sPage,$x);
				foreach ($aBlocks as $iID => $dbData) {
					switch($dbData['Func']) {
						case 'RSS':
							$r .= '<div class="PageBlockGreen"><input name="pageblock" type="radio" value="' . $dbData['ID'] . '" />' . _t($dbData['Caption']) . '</div>';
						break;
						case 'PHP':
							$r .= '<div class="PageBlockGreen"><input name="pageblock" type="radio" value="' . $dbData['ID'] . '" />' . _t($dbData['Caption']) . '</div>';
						break;
						case 'Echo':
							$r .= '<div class="PageBlockGreen"><input name="pageblock" type="radio" value="' . $dbData['ID'] . '" />' . _t($dbData['Caption']) . '</div>';
						break;
						default:
							$r .= '<div class="PageBlockRed"><input name="pageblock" type="radio" value="' . $dbData['ID'] . '" />' . _t($dbData['Caption']) . '</div>';
					}
				}
				$r .= '</td>';
			}
			$r .= '
					</tr>
				</table>
				<table border="0" width="100%">
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td><b>' . _t('_dbcs_BT_Inactive Page Blocks') . '</b></td>
					</tr>
					<tr>
						<td valign="top" style="border: 1px solid #CCCCCC">
			';
			$aBlocks = $this->_oDb->getInActiveBlocks($sPage);
				foreach ($aBlocks as $iID => $dbData) {
					switch($dbData['Func']) {
						case 'RSS':
							$r .= '<div class="PageBlockGreenI"><input name="pageblock" type="radio" value="' . $dbData['ID'] . '" />' . _t($dbData['Caption']) . '</div>';
						break;
						case 'PHP':
							$r .= '<div class="PageBlockGreenI"><input name="pageblock" type="radio" value="' . $dbData['ID'] . '" />' . _t($dbData['Caption']) . '</div>';
						break;
						case 'Echo':
							$r .= '<div class="PageBlockGreenI"><input name="pageblock" type="radio" value="' . $dbData['ID'] . '" />' . _t($dbData['Caption']) . '</div>';
						break;
						default:
							$r .= '<div class="PageBlockRedI"><input name="pageblock" type="radio" value="' . $dbData['ID'] . '" />' . _t($dbData['Caption']) . '</div>';
					}
				}
			$r .= '
						</td>
					</tr>
				</table>
			';
			return $r;
		}
    }
?>
