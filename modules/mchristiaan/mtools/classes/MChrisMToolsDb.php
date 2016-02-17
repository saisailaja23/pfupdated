<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by MChristiaan and cannot be modified for other than personal usage. 
* This product cannot be redistributed for free or a fee without written permission from MChristiaan. 
* This notice may not be removed from the source code.
*
***************************************************************************/

require_once( BX_DIRECTORY_PATH_INC . 'header.inc.php' );
bx_import('BxDolModuleDb');

class MChrisMToolsDb extends BxDolModuleDb {
	var $_oConfig;
	
	function MChrisMToolsDb(&$oConfig) {
		parent::BxDolModuleDb();
        $this->_sPrefix = $oConfig->getDbPrefix();
    }	
	
	function str_replace_assoc($array,$string){
		$from_array = array();
		$to_array = array();
	   
		foreach ($array as $k => $v){
			$from_array[] = $k;
			$to_array[] = $v;
		}
	   
		return str_replace($from_array,$to_array,$string);
	}
	
	function getString ($iTId,$iTLId) {
		global $site;
        $s = $this->getOne("SELECT `String` FROM `" . $this->_sPrefix . "keys`, `". $this->_sPrefix . "strings` WHERE `ID` = '$iTId' AND `ID` = `IDKey` AND `IDLanguage` = '$iTLId'");
		return $s;
    }

	function updateString ($Text,$iTId,$iTLId) {
		$Text = mysql_real_escape_string($Text);
		return $this->query("UPDATE `" . $this->_sPrefix . "strings` SET `String`='$Text' where `IDKey`='$iTId' and `IDLanguage`='$iTLId'");
	}
	
	function getStringTPE ($iTId,$iTLId) {
		global $site;
        $s = $this->getOne("SELECT `String` FROM `" . $this->_sPrefix . "keys`, `". $this->_sPrefix . "strings` WHERE `ID` = '$iTId' AND `ID` = `IDKey` AND `IDLanguage` = '$iTLId'");
		$s = str_replace( '<site>', $site['title'], $s);
		return $s;
    }    
	
	function updateStringTPE ($Text,$iTId,$iTLId) {
        global $site;
		$Text = str_replace( $site['title'], '<site>', $Text);
		$Text = mysql_real_escape_string($Text);
		return $this->query("UPDATE `" . $this->_sPrefix . "strings` SET `String`='$Text' where `IDKey`='$iTId' and `IDLanguage`='$iTLId'");
    }
	
	function getStringFAQ ($iTId,$iTLId) {
		global $site;
		$replace = array(
			'<div class="faq_cont">' => '<faq_'. _t('_mchristiaan_faq_acontent') .'>',
			'<div class="faq_header">' => '<faq_'. _t('_mchristiaan_faq_aheader') .'>',
			'<div class="faq_snippet">' => '<faq_'. _t('_mchristiaan_faq_asnippet') .'>',
			'</div>' => '</faq>'
		);
        $s = $this->getOne("SELECT `String` FROM `" . $this->_sPrefix . "keys`, `". $this->_sPrefix . "strings` WHERE `ID` = '$iTId' AND `ID` = `IDKey` AND `IDLanguage` = '$iTLId'");
		$s = $this->str_replace_assoc($replace,$s);
		return $s;
    }
	
	function updateStringFAQ ($Text,$iTId,$iTLId) {
        global $site;
		$replace = array(
			'<faq_'. _t('_mchristiaan_faq_acontent') .'>' => '<div class="faq_cont">',
			'<faq_'. _t('_mchristiaan_faq_aheader') .'>' => '<div class="faq_header">',
			'<faq_'. _t('_mchristiaan_faq_asnippet') .'>' => '<div class="faq_snippet">',
			'</faq>' => '</div>'
		);
		$Text = $this->str_replace_assoc($replace,$Text);
		$Text = mysql_real_escape_string($Text);
		return $this->query("UPDATE `" . $this->_sPrefix . "strings` SET `String`='$Text' where `IDKey`='$iTId' and `IDLanguage`='$iTLId'");
    }
	
	function getfirstLang() {
		return $this->getOne("SELECT `ID` FROM `" . $this->_sPrefix . "languages` LIMIT 1");
    }
	
	function getLangs() {
		return $this->getAll("SELECT * FROM `" . $this->_sPrefix . "languages`");
    }
	
	function getId($Key) {
		return $this->getOne("SELECT `ID` FROM `" . $this->_sPrefix . "keys` WHERE `Key` = '$Key'");
    }
	
	function getMenuLinks() {
		return $this->getAll("SELECT * FROM `sys_menu_bottom` ORDER BY `Order` ASC");
    }
	
	function getMenuLink($linkid) {
		return $this->getAll("SELECT * FROM `sys_menu_bottom` WHERE `ID` = '$linkid' LIMIT 1");
    }
	
	function getMaxOrder() {
		return $this->getOne("SELECT MAX(`Order`) FROM `sys_menu_bottom`");
    }
	
	function getSystemID() {
		return $this->getOne("SELECT `ID` FROM `" . $this->_sPrefix . "categories` WHERE `Name` = 'System'");
    }
	
	function updateRecords($i,$mi) {
		return $this->query("UPDATE `sys_menu_bottom` SET `Order` = '$i' WHERE `ID` = '$mi'");
    }
	
	function getKeyID($addme_caption_text) {
		return $this->getOne("SELECT `ID` FROM `" . $this->_sPrefix . "keys` WHERE `Key` = '$addme_caption_text'");
    }
	
	function createKey($addme_caption_text,$sys_ID) {
		return $this->query("INSERT INTO `" . $this->_sPrefix . "keys` VALUES ('',$sys_ID,'$addme_caption_text')");
    }
	
	function createString($check_caption2,$tdLID,$addme_name_text) {
		return $this->query("INSERT INTO `" . $this->_sPrefix . "strings` VALUES ('$check_caption2',$tdLID,'$addme_name_text')");
    }
	
	function updateKeyString($check_caption,$tdLID,$addme_name_text) {
		return $this->query("UPDATE `" . $this->_sPrefix . "strings` SET `String` = '$addme_name_text' WHERE `IDKey` = '$check_caption' AND `IDLanguage` = '$tdLID'");
    }
	
	function addMe($addme_caption_text,$addme_name_text,$addme_link_text,$addme_script_text) {
		$Max = $this->getMaxOrder();
		$Max = $Max + 1;
		return $this->query("INSERT INTO `sys_menu_bottom` VALUES ('','$addme_caption_text','$addme_name_text','','$addme_link_text','$addme_script_text',$Max,'')");
    }
	
	function editMe($edit_me_id,$editme_caption_text,$editme_name_text,$editme_link_text,$editme_script_text) {
		return $this->query("UPDATE `sys_menu_bottom` SET `Caption` = '$editme_caption_text', `Name` = '$editme_name_text', `Link` = '$editme_link_text', `Script` = '$editme_script_text' WHERE `ID` = '$edit_me_id'");
    }
	
	function removeMe($id) {
		return $this->query("DELETE FROM `sys_menu_bottom` WHERE `ID` = '$id'");
    }
	
}

?>
