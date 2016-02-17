<?php
/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Listing
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
bx_import('BxTemplCategories');

class BxListingPageMain extends BxDolTwigPageMain {

    function BxListingPageMain(&$oMain) {

		$this->oDb = $oMain->_oDb;
        $this->oConfig = $oMain->_oConfig;
		$this->oMain = $oMain;

        $this->sSearchResultClassName = 'BxListingSearchResult';
        $this->sFilterName = 'modzzz_listing_filter';
		parent::BxDolTwigPageMain('modzzz_listing_main', $oMain);
	}
  
    function getBlockCode_ListingCategories() {
  
	    $aAllEntries = $this->oDb->getCategoryInfo(0, true);
     
        $aResult['bx_repeat:entries'] = array();        
 		foreach($aAllEntries as $aEntry)
		{	 
			$iNumCategory = $this->oDb->getParentCategoryCount($aEntry['id']);	 
			$sCatHref = $this->oDb->getCategoryUrl($aEntry['uri']);  
			$sCategory = $aEntry['name'];
 
	        $aResult['bx_repeat:entries'][] = array(
                'cat_url' => $sCatHref, 
                'cat_name' => $sCategory,
			    'num_items' => $iNumCategory, 
            );	        
	    } 
 
	    return $this->oTemplate->parseHtmlByName('listing_categories', $aResult);  
	}
 

    function getBlockCode_States() {
		$iProfileId = $_COOKIE['memberID'];

		if(!$iProfileId)
			return;

		$aProfile = getProfileInfo($iProfileId);
		$sCountry = ($aProfile['Country']) ? $aProfile['Country'] : 'US';
 
		$sCountryName = _t($GLOBALS['aPreValues']['Country'][$sCountry]['LKey']);

		$aStates = $this->oDb->getAll("SELECT `State`,`StateCode` FROM `States` WHERE CountryCode='{$sCountry}' ORDER BY `State`");
		 
		$aVars['bx_repeat:entries'] = array();        
  		foreach($aStates as $aEachState){
			 
			$sState = $aEachState['State'];
			$sStateCode = $aEachState['StateCode'];

			$sStateUrl = BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'local/' . $sCountry .'/'. $sStateCode;
  		
			$iNumCategory = $this->oDb->getStateCount($sStateCode);	 
			 
			$aVars['country_name'] = $sCountryName;

			$aVars['bx_repeat:entries'][] = array(
		 
				'bx_if:selstate' => array( 
					'condition' => ($sStateCode == $this->sState),
					'content' => array( 
						'state_url' => $sStateUrl, 
						'state_name' => $sState,
						'num_items' => $iNumCategory,  
					), 
				), 
				'bx_if:regstate' => array( 
					'condition' => ($sStateCode != $this->sState),
					'content' => array( 
						'state_url' => $sStateUrl, 
						'state_name' => $sState,
						'num_items' => $iNumCategory,   
					), 
				), 

			 ); 
	    } 
 
	    return $this->oTemplate->parseHtmlByName('listing_states', $aVars);   
	}
 

    function getBlockCode_LatestFeaturedListing() {

		if($this->oMain->isAllowedPaidListings()){
			$iNumListings = (int)getParam('modzzz_listing_perpage_main_featured');
		}else{
			$iNumListings = 1;
		}

	    return $this->ajaxBrowse('featured', $iNumListings); 
    }
 
    function getBlockCode_Recent() { 
        return $this->ajaxBrowse('recent', $this->oDb->getParam('modzzz_listing_perpage_main_recent'));
    }

	function getBlockCode_Popular() { 
        return $this->ajaxBrowse('popular', $this->oDb->getParam('modzzz_listing_perpage_main_recent'));
    }
    
	function getBlockCode_Top() { 
        return $this->ajaxBrowse('top', $this->oDb->getParam('modzzz_listing_perpage_main_recent'));
    }

	function getListingMain() {
        return BxDolModule::getInstance('BxListingModule');
    }


	function getBlockCode_Search() {
   
        $this->oTemplate->pageStart();
 
        bx_import('BxDolCategories');
        bx_import('BxDolProfileFields'); 
  
        $oProfileFields = new BxDolProfileFields(0);
        $aCountries = $oProfileFields->convertValues4Input('#!Country');
		$aCountries[''] = "";  
		asort($aCountries);
		$aProfile = getProfileInfo((int)$iProfileId);
		$sDefaultCountry = $aProfile['Country'];
   
		$sStateUrl = BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'home/?ajax=state&country=' ;
 
		$sCatUrl = BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'home/?ajax=cat&parent=' ;

		$aCategories = $this->oDb->getFormCategoryArray();
 
        $aForm = array(

            'form_attrs' => array(
                'name'     => 'form_search_listing',
                'action'   => '',
                'method'   => 'post',
            ),      

            'params' => array (
                'db' => array(
                    'submit_name' => 'submit_form',
                ),
            ),
                  
            'inputs' => array(
                'Keyword' => array(
                    'type' => 'text',
                    'name' => 'Keyword',
                    'caption' => _t('_modzzz_listing_form_caption_keyword'),
                    'required' => false, 
                    'db' => array (
                        'pass' => 'Xss', 
                    ),
                ),                
                'parent' => array(
                    'type' => 'select',
                    'name' => 'parent',
					'values'=> $aCategories,
                    'caption' => _t('_modzzz_listing_parent_categories'),
					'attrs' => array(
						'onchange' => "getHtmlData('subcat','$sCatUrl'+this.value)",
                        'id' => 'parentcat',
					),
                    'required' => false, 
                ), 
                'Category' => array(
                    'type' => 'select',
                    'name' => 'Category',
					'values'=> array(),
                    'caption' => _t('_Categories'),
					'attrs' => array(
                        'id' => 'subcat',
					),
                    'required' => false, 
                    'db' => array (
                        'pass' => 'Int', 
                    ),
                ),
                'Country' => array(
					'type' => 'select',
                    'name' => 'Country',
                    'caption' => _t('_modzzz_listing_form_caption_country'),
                    'values' => $aCountries,
					'attrs' => array(
						'onchange' => "getHtmlData('substate','$sStateUrl'+this.value)",
					), 
					'default' => $sDefaultCountry, 
                    'required' => false, 
                    'db' => array (
                        'pass' => 'Preg', 
                        'params' => array('/([a-zA-Z]{0,2})/'),
                    ),  
                ),
				'State' => array(
					'type' => 'select',
					'name' => 'State', 
					'caption' => _t('_modzzz_listing_caption_state'),
					'attrs' => array(
						'id' => 'substate',
					), 
				    'db' => array (
						'pass' => 'Preg', 
						'params' => array('/([a-zA-Z]+)/'),
					), 
				), 
                'City' => array(
                    'type' => 'text',
                    'name' => 'City',
                    'caption' => _t('_modzzz_listing_form_caption_city'),
                    'required' => false,  
                    'db' => array (
                        'pass' => 'Xss', 
                    ),
                    'display' => true,
                ),    
                'Submit' => array (
                    'type' => 'submit',
                    'name' => 'submit_form',
                    'value' => _t('_modzzz_listing_continue'),
                    'colspan' => true,
                ),
            ),            
        );


 
        $oForm = new BxTemplFormView($aForm);
        $oForm->initChecker(); 
  
        if ($oForm->isSubmittedAndValid ()) {
 
            modzzz_listing_import ('SearchResult');
            $o = new BxListingSearchResult('search', $oForm->getCleanValue('Keyword'), $oForm->getCleanValue('Category'), $oForm->getCleanValue('City'), $oForm->getCleanValue('State'), $oForm->getCleanValue('Country') );
 
            if ($o->isError) {
                $this->oTemplate->displayPageNotFound ();
                exit;
            }

            if ($s = $o->processing()) {
                echo $s;
            } else {
                $this->oTemplate->displayNoData ();
                exit;
            }

            $this->oMain->isAllowedSearch(true); // perform search action 

            $this->oTemplate->addCss ('unit.css');
            $this->oTemplate->addCss ('main.css');
            $this->oTemplate->pageCode($o->aCurrent['title'], false, false);
			exit;

        } 
 
        return $oForm->getCode(); 
    } 
  

}

?>
