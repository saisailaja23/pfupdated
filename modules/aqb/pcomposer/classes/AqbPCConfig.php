<?php
/***************************************************************************
* 
*     copyright            : (C) 2009 AQB Soft
*     website              : http://www.aqbsoft.com
*      
* IMPORTANT: This is a commercial product made by AQB Soft. It cannot be modified for other than personal usage.
* The "personal usage" means the product can be installed and set up for ONE domain name ONLY. 
* To be able to use this product for another domain names you have to order another copy of this product (license).
* 
* This product cannot be redistributed for free or a fee without written permission from AQB Soft.
* 
* This notice may not be removed from the source code.
* 
***************************************************************************/

bx_import('BxDolConfig');

class AqbPCConfig extends BxDolConfig {
    var $_oDb;
	var $_sDateFormat;
	var $_aCurrency;
	var $_isAdmin;
	var $_NotModules;

	/**
	 * Constructor
	 */

    function AqbPCConfig($aModule) {
	    parent::BxDolConfig($aModule);
		$this -> _sDateFormat = '%d/%m/%y %H:%i';
		$this -> _sCustomBlockPrefix = 'aqb_c_';
		$this -> _isAdmin = isAdmin($GLOBALS['logged']['member'] || $GLOBALS['logged']['admin'] ? $_COOKIE['memberID'] : 0);
	}
	
	function init(&$oDb) {
		$this->_oDb = &$oDb;
	}
	
	function getCBPrefix(){
	   return $this -> _sCustomBlockPrefix; 
	}	
	
	function getDateFormat(){
		return $this -> _sDateFormat;
	}
	
	function isPermalinkEnabled(){
	  return $this -> _oDb -> getParam('permalinks_module_aqb_pcomposer') == 'on';	
	}
	
	function getMaxCharsForBody(){
	  return (int)$this -> _oDb -> getParam('aqb_pc_max_sym_num') ? (int)$this -> _oDb -> getParam('aqb_pc_max_sym_num') : 2000 ;	
	}
	
	function getMaxCharsForTitle(){
	  return (int)$this -> _oDb -> getParam('aqb_pc_max_sym_num_title') ? (int)$this -> _oDb -> getParam('aqb_pc_max_sym_num_title') : 100 ;	
	}
	
	function isTEXTBlockAllowed(){
	  return $this -> _oDb -> getParam('aqb_pc_allow_to_create_text') == 'on';	
	}
	
	function isHTMLBlockAllowed(){
	  return $this -> _oDb -> getParam('aqb_pc_allow_create_html') == 'on';	
	}
	
	function isRSSBlockAllowed(){
	  return $this -> _oDb -> getParam('aqb_pc_allow_create_rss') == 'on';	
	}
	
	function isAutoApproveTEXTBlock(){
	  return $this -> _oDb -> getParam('aqb_pc_autoapp_text') == 'on';	
	}
	
	function isAutoApproveHTMLBlock(){
	  return $this -> _oDb -> getParam('aqb_pc_autoapp_html') == 'on';	
	}
	
	function isAutoApproveRSSBlock(){
	  return $this -> _oDb -> getParam('aqb_pc_autoapp_rss') == 'on';	
	}
    
	function isCreateBlocksAllowed(){
	  return !(!$this -> isTEXTBlockAllowed() && !$this -> isHTMLBlockAllowed() && !$this -> isRssBlockAllowed());
	}	
	
	function getNumberOfAllowedBlocksForMember(){
	  $i = (int)$this -> _oDb -> getParam('aqb_pc_allowed_blocks_num');
	  return  $i == 0 ? 4 : $i ;	
	}
	
	function isShareBlocksAllowed(){
	  return $this -> _oDb -> getParam('aqb_pc_allow_share') == 'on';	
	}
	
	function getRssNumber(){
		return (int)$this -> _oDb -> getParam('aqb_pc_num_rss_rec') ? (int)$this -> _oDb -> getParam('aqb_pc_num_rss_rec') : 10;
	}
	
	function isStandardBlocksDeleteAllowed(){
	  return $this -> _oDb -> getParam('aqb_pc_allow_to_remove_stand') == 'on' ? 1 : 0;	
	}
	
	function isAllowedToChangePrivacy(){
	  return $this -> _oDb -> getParam('aqb_pc_allow_to_change_privacy') == 'on' ? 1 : 0;	
	}
	
	function isAllowedToEditBlock(){
	  return $this -> _oDb -> getParam('aqb_pc_allow_to_edit_block') == 'on';	
	}
	
	function isAutoApproveEditBlock(){
	  return $this -> _oDb -> getParam('aqb_pc_allow_to_approve_after_edit') == 'on' ? 1 : 0;	
	}
	
	function isAllowedToRemoveOwnBlock(){
	  return $this -> _oDb -> getParam('aqb_pc_allow_to_remove_own_blocks') == 'on' ? 1 : 0;	
	}
	
	function getNumberOfBlocksOnBlocksPage(){
	  $i = (int)$this -> _oDb -> getParam('aqb_pc_num_blocks_on_page');
	  return  $i == 0 ? 10 : $i;	
	}
	
	function getSiteEmail(){
	  return $this -> _oDb -> getParam('site_email');	
	}
}
?>