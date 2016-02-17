<?php
/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Answer
*     website              : http://www.boonex.com
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License as published by the
* Free Software Foundation; either version 2 of the
* License, or  any later version.
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

bx_import('BxDolTwigPageMain');
 
class BxListingPageSubCategory extends BxDolTwigPageMain {

	var $aCategoryInfo = array();
	var $sCategoryUri;

    function BxListingPageSubCategory(&$oMain, $sUri) {

		$this->_oDb = $oMain->_oDb;
        $this->_oConfig = $oMain->_oConfig;

        $this->sSearchResultClassName = 'BxListingSearchResult';
        $this->sFilterName = 'modzzz_listing_filter';
		parent::BxDolTwigPageMain('modzzz_listing_subcategory', $oMain);
		
		$this->sCategoryUri = $sUri;
		$this->aCategoryInfo = $this->_oDb->getCategoryInfoByUri($this->sCategoryUri); 
 
		if(!count($this->aCategoryInfo)){ 
			$sRedirectUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'home';
			header("location:{$sRedirectUrl}"); 
		}
 
	}
 
    function getBlockCode_SubCategories() {
  	
		$iParentId = $this->aCategoryInfo['parent']; 
		$iCategoryId = $this->aCategoryInfo['id']; 
 
		$this->aParentInfo = $this->_oDb->getCategoryInfo($iParentId);  
 	    $aAllEntries = $this->_oDb->getSubCategoryInfo($iParentId, 0, true);
 
        $aResult = array();        
 		foreach($aAllEntries as $aEntry)
		{	 
			$sCatHref = $this->_oDb->getSubCategoryUrl($aEntry['uri']);  
			$sCategory = $aEntry['name'];  
			$iNumCategory = $this->_oDb->getCategoryCount($aEntry['id']);	 
 
	        $aResult[] = array(
                'subcategory_url' => $sCatHref, 
                'subcategory' => $sCategory,
			    'subcategory_class' => ($iCategoryId==$aEntry['id']) ? "selected_subcat" : "subcat", 
			    'num_items' => $iNumCategory,  
            );	        
	    } 

		$aVars = array(
			'bx_repeat:entries' => $aResult,
			'all_categories_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'categories',
			'parent_category_url' => $this->_oDb->getCategoryUrl($this->aParentInfo['uri']),
			'parent_category' => $this->aParentInfo['name'], 
		);
 
	    return $this->oTemplate->parseHtmlByName('listing_subcategories', $aVars);  
	}
 
 
    function getBlockCode_SubCategoryListings() {

		$this->sUrlStart = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'subcategories?';

        return $this->ajaxBrowse('subcategories', $this->oDb->getParam('modzzz_listing_perpage_main_recent'),array(), $this->aCategoryInfo['uri']);  
	}
 
   

}

?>
