<?php
/***************************************************************************
* Date				: Saturday November 24, 2012
* Copywrite			: (c) 2012 by Dean J. Bassett Jr.
* Website			: http://www.deanbassett.com
*
* Product Name		: Head Injections
* Product Version	: 2.0.1
*
* IMPORTANT: This is a commercial product made by Dean Bassett Jr.
* and cannot be modified other than personal use.
*  
* This product cannot be redistributed for free or a fee without written
* permission from Dean Bassett Jr.
*
***************************************************************************/

require_once(BX_DIRECTORY_PATH_CLASSES . 'BxDolModule.php');

class BxHeadInjectionsModule
    extends BxDolModule
    {

	    function BxHeadInjectionsModule(&$aModule) {
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
	            'header' => _t('_dbcs_HI_HeadInjectionsHeader'),
	            'header_text' => _t('_dbcs_HI_HeadInjectionsHeaderText')
	            );
	        $_page_cont[$iNameIndex]['page_main_code'] .= $this->DeanoMainCode();
	        PageCodeAdmin();
		}  
		

		function DeanoMainCode()
		    {
			$sExistedC=_t('_dbcs_HI_HeadInjectionsBoxHeader');
	        $sCss = $this->_oTemplate->addCss('unit.css', true);
			$sJs=$this->_oTemplate->addJs('dbcsfunctions.js', true);
	        $sAction = BX_DOL_URL_ROOT . 'modules/?r=head_injections/administration/';
			$sSection = $_GET['section'];
			$sCode ='';
			$sCode .= '<div class="container">';

			switch ($sSection) {
				case "add":
					// displays new injection form.
			$sCode .= '
<div class="headtext">' . _t('_dbcs_HI_NewInjection') . '</div>
<form action="?r=head_injections/administration/&section=save" method="POST">
  <table cellspacing="0" cellpadding="0" class="form_advanced_table">
    <tr>
      <td class="caption">' . _t('_dbcs_HI_Title') . '</td>
      <td class="value"><div class="clear_both"></div>
        <div class="input_wrapper input_wrapper_text bx-def-round-corners-with-border">
          <input type="text" value="" name="dbcs_HI_Title" class="form_input_text bx-def-font">
        </div>
        <div class="clear_both"></div></td>
    </tr>
    <tr>
      <td class="caption">' . _t('_dbcs_HI_Injections') . '</td>
      <td class="value"><div class="clear_both"></div>
        <div class="input_wrapper input_wrapper_textarea bx-def-round-corners-with-border" style="width: 100%;">
          <textarea name="dbcs_HI_Injections" class="form_input_textarea bx-def-font" id="text_data_input_snippet"></textarea>
        </div>
        <div class="clear_both"></div></td>
    </tr>
    <tr>
      <td class="caption"></td>
      <td class="value"><div class="clear_both"></div>
        <div class="input_wrapper input_wrapper_submit ">
          <div class="button_wrapper">
            <input type="submit" value="' . _t('_dbcs_HI_Insert') . '" name="B1" class="form_input_submit bx-btn">
          </div>
        </div>
        <div class="clear_both"></div></td>
    </tr>
  </table>
</form>
';

					break;
				case "delete":
					// delete existing injection from database
					$iID = $_GET['id'];
					$this->_oDb->deleteInjection($iID);
					$sCode .= '<div class="dbcsMsgBox ">' . MsgBox(_t('_dbcs_HI_Deleted')) . '</div>';
					$sCode .= '<div class="choice1"><a href="?r=head_injections/administration/">' . _t('_dbcs_HI_BackList') . '</a></div>';
					break;
				case "edit":
					// displays existing injection form for editing.
					$iID = $_GET['id'];
					$aInjection = $this->_oDb->getInjection($iID);
			$sCode .= '
<div class="headtext">' . _t('_dbcs_HI_EditInjection') . '</div>
<form action="?r=head_injections/administration/&section=update&id=' . $iID . '" method="POST">
  <table cellspacing="0" cellpadding="0" class="form_advanced_table">
    <tr>
      <td class="caption">' . _t('_dbcs_HI_Title') . '</td>
      <td class="value"><div class="clear_both"></div>
        <div class="input_wrapper input_wrapper_text bx-def-round-corners-with-border">
          <input type="text" value="' . $aInjection['page_title'] . '" name="dbcs_HI_Title" class="form_input_text bx-def-font">
        </div>
        <div class="clear_both"></div></td>
    </tr>
    <tr>
      <td class="caption">' . _t('_dbcs_HI_Injections') . '</td>
      <td class="value"><div class="clear_both"></div>
        <div class="input_wrapper input_wrapper_textarea bx-def-round-corners-with-border" style="width: 100%;">
          <textarea name="dbcs_HI_Injections" class="form_input_textarea bx-def-font" id="text_data_input_snippet">' . $aInjection['injection_text'] . '</textarea>
        </div>
        <div class="clear_both"></div></td>
    </tr>
    <tr>
      <td class="caption">' . _t('_dbcs_HI_Active') . '</td>
      <td class="value"><div class="clear_both"></div>
        <div class="input_wrapper input_wrapper_text bx-def-round-corners-with-border">
          <input type="text" value="' . $aInjection['active'] . '" name="dbcs_HI_Active" class="form_input_text bx-def-font">
        </div>
        <div class="clear_both"></div></td>
    </tr>
    <tr>
      <td class="caption"></td>
      <td class="value"><div class="clear_both"></div>
        <div class="input_wrapper input_wrapper_submit ">
          <div class="button_wrapper">
            <input type="submit" value="' . _t('_dbcs_HI_Save') . '" name="B1" class="form_input_submit bx-btn">
          </div>
        </div>
        <div class="clear_both"></div></td>
    </tr>
  </table>
</form>
';
					break;
				case "update":
					// updates existing injection in database.
					$iID = $_GET['id'];
					$sTitle = process_db_input($_POST['dbcs_HI_Title']);
					$sInjections = process_db_input($_POST['dbcs_HI_Injections']);
					$iActive = $_POST['dbcs_HI_Active'];
					$this->_oDb->updateInjection($iID, $sTitle, $sInjections, $iActive);
					$sCode .= '<div class="dbcsMsgBox ">' . MsgBox(_t('_dbcs_HI_Updated')) . '</div>';
					$sCode .= '<div class="choice1"><a href="?r=head_injections/administration/">' . _t('_dbcs_HI_BackList') . '</a></div>';
					break;
				case "save":
					// saves new injection in database
					$sTitle = process_db_input($_POST['dbcs_HI_Title']);
					$sInjections = process_db_input($_POST['dbcs_HI_Injections']);
					$this->_oDb->insertInjection($sTitle, $sInjections);
					$sCode .= '<div class="dbcsMsgBox ">' . MsgBox(_t('_dbcs_HI_NewSaved')) . '</div>';
					$sCode .= '<div class="choice1"><a href="?r=head_injections/administration/&section=add">' . _t('_dbcs_HI_InsertAnother') . '</a> | <a href="?r=head_injections/administration/">' . _t('_dbcs_HI_BackList') . '</a></div>';
					break;
				default :
					// show list of current injections
					$sCode .= '<div class="sectiondesc">' . _t('_dbcs_HI_Message1') . '</div>';
					$aInjections = $this->_oDb->getInjections();
					if (count($aInjections) > 0) {
						$sCode .= '
							<table class="form_advanced_table" width="100%" cellspacing="0" cellpadding="0" border="0">
								<tr class="db1">
									<td>ID</th>
									<td style="white-space: nowrap">Page Match Title</th>
									<td>Injections</th>
									<td>Active</th>
									<td>Actions</th>
								</tr>
						';
						foreach($aInjections as $dbdata) {
							$sCode .= '
								<tr class="db2">
									<td>' . $dbdata['id'] . '</td>
									<td style="white-space: normal;">' . nl2br(htmlspecialchars($dbdata['page_title'], ENT_QUOTES)) . '</td>
									<td style="white-space: normal;">' . nl2br(htmlspecialchars($dbdata['injection_text'], ENT_QUOTES)) . '</td>
									<td>' . $dbdata['active'] . '</td>
									<td style="white-space: nowrap"><a href="?r=head_injections/administration/&section=edit&id=' . $dbdata['id'] . '">' . _t('_dbcs_HI_Edit') . '</a> | <a href="?r=head_injections/administration/&section=delete&id=' . $dbdata['id'] . '">' . _t('_dbcs_HI_Delete') . '</a></td>
								</tr>
							';
						}
						$sCode .= '</table>';
					} else {
						$sCode .= '<div class="dbcsMsgBox ">' . MsgBox(_t('_dbcs_HI_NoInjections')) . '</div>';
					}
			}

			$sCode .= '</div>';

			$sCode = '<div class="bx-def-bc-margin">' . $sCode . '</div>';

			bx_import('BxDolPageView');
	        $sActions = BxDolPageView::getBlockCaptionMenu(mktime(), array(
	            'add_unit' => array('href' => $sAction . '&section=add', 'title' => _t('_dbcs_HI_AddNew'), 'onclick' => '', 'active' => 0),
	            'show_list' => array('href' => $sAction, 'title' => _t('_dbcs_HI_List'), 'onclick' => '', 'active' => 0),
	        ));

            return DesignBoxContent($sExistedC, $sCss . $sJs . $sCode, 1, $sActions);

        }


		function serviceGetInjection($sTitle) {
			// called like BxDolService::call('head_injections', 'get_injection_title',array('pagetitle'));
			$aInjection = $this->_oDb->getInjectionByTitle(process_db_input($sTitle));
			if (intval($aInjection['active'] == 1)) {
				$sInjectionData = $this -> parseContent($aInjection['injection_text']);
				// if title exists then extract it and store it in array as title.
				// if not then set title to null.
				$aData['title'] = $this -> extractTitle($sInjectionData);
				$aData['content'] = $this -> extractContent($sInjectionData);
			} else {
				$aData['title'] = '';
				$aData['content'] = '';
			}
			return $aData;
		}

		function parseContent($sContent) {
			// Replace any keys with proper values.
			$iMemID = getLoggedId();
			$sNickName = getNickName();
			$sContent = str_replace('{memberID}', $iMemID, $sContent);
			$sContent = str_replace('{nickName}', $sNickName, $sContent);
			if (!isLogged()) {
				// If guest, remove the <ifMember> lines.
				$bDone = false;
				do {
					$iP1 = strpos($sContent,"<ifMember>");
					if($iP1 !== false) {
						$iP2 = strpos($sContent,"</ifMember>",$iP1);
						$iStart = $iP1;
						$iLength = $iP2 - $iStart + 11;
						$sContent = substr_replace($sContent, '', $iStart, $iLength);
					} else {
						$bDone = true;
					}
				} while (!$bDone);
				// Remove the guest marker tags.
				$sContent = str_replace("<ifGuest>",'',$sContent);
				$sContent = str_replace("</ifGuest>",'',$sContent);
			} else {
				// If member, remove the <ifGuest> lines.
				$bDone = false;
				do {
					$iP1 = strpos($sContent,"<ifGuest>");
					if($iP1 !== false) {
						$iP2 = strpos($sContent,"</ifGuest>",$iP1);
						$iStart = $iP1;
						$iLength = $iP2 - $iStart + 10;
						$sContent = substr_replace($sContent, '', $iStart, $iLength);
					} else {
						$bDone = true;
					}
				} while (!$bDone);
				// Remove the member marker tags.
				$sContent = str_replace("<ifMember>",'',$sContent);
				$sContent = str_replace("</ifMember>",'',$sContent);
			}
			// Replace double \r\n with singles.
			$sContent = str_replace("\r\n\r\n","\r\n",$sContent);
			$sContent = str_replace("\r\r","\r",$sContent);
			$sContent = str_replace("\n\n","\n",$sContent);
			return $sContent;
		}

		function extractTitle($sContent) {
			$iP1 = strpos($sContent,"<title>");
			if($iP1 === false) {
				$sContent = '';
			} else {
				$iP2 = strpos($sContent,"</title>",$iP1);
				$iStart = $iP1+7;
				$iLength = $iP2 - $iStart;
				$sTitle = substr($sContent,$iStart,$iLength);
				$sContent = $sTitle;
			}
			return $sContent;
		}

		function extractContent($sContent) {
			$iP1 = strpos($sContent,"<title>");
			if($iP1 !== false) {
				$iP2 = strpos($sContent,"</title>",$iP1);
				$iStart = $iP1;
				$iLength = $iP2 - $iStart + 8;
				$sContent = substr_replace($sContent, '', $iStart, $iLength);
			}
			return $sContent;
		}


/****************************************************************************/
    }
?>