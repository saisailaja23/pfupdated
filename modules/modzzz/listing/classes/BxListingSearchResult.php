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

bx_import('BxDolTwigSearchResult');

class BxListingSearchResult extends BxDolTwigSearchResult {

    var $aCurrent = array(
        'name' => 'modzzz_listing',
        'title' => '_modzzz_listing_page_title_browse',
        'table' => 'modzzz_listing_main',
        'ownFields' => array('id', 'title', 'uri', 'created', 'author_id', 'thumb', 'rate', 'country', 'city', 'state', 'desc', 'category_id', 'tags', 'currency', 'comments_count','invoice_no', 'video_embed', 'expiry_date', 'membership_view_filter'),
        'searchFields' => array('title', 'tags', 'desc'),
        'join' => array(
            'profile' => array(
                    'type' => 'left',
                    'table' => 'Profiles',
                    'mainField' => 'author_id',
                    'onField' => 'ID',
                    'joinFields' => array('NickName'),
            ),
        ),
        'restriction' => array(
            'activeStatus' => array('value' => 'approved', 'field'=>'status', 'operator'=>'='),
            'owner' => array('value' => '', 'field' => 'author_id', 'operator' => '='),
            'tag' => array('value' => '', 'field' => 'tags', 'operator' => 'against'),
            'category' => array('value' => '', 'field' => 'Category', 'operator' => '=', 'table' => 'sys_categories'),
            'public' => array('value' => '', 'field' => 'allow_view_listing_to', 'operator' => '='),
        ),
        'paginate' => array('perPage' => 14, 'page' => 1, 'totalNum' => 0, 'totalPages' => 1),
        'sorting' => 'last',
        'rss' => array( 
            'title' => '',
            'link' => '',
            'image' => '',
            'profile' => 0,
            'fields' => array (
                'Link' => '',
                'Title' => 'title',
                'DateTimeUTS' => 'created',
                'Desc' => 'desc',
                'Photo' => '',
            ),
        ),
        'ident' => 'id'
    );
    
 
    function BxListingSearchResult($sMode = '', $sValue = '', $sValue2 = '', $sValue3 = '', $sValue4 = '', $sValue5 = '', $sValue6 = '') {
	 
		$sValue = process_db_input($sValue);
		$sValue2 = process_db_input($sValue2);
		$sValue3 = process_db_input($sValue3);
		$sValue4 = process_db_input($sValue4);
		$sValue5 = process_db_input($sValue5);
		$sValue6 = process_db_input($sValue6);

		$oMain = $this->getMain();
		 
		$aAllowedLevels = array();
		$aAllowedLevels[] = '';
 
		if(!$oMain->isAdmin()) {
			if($oMain->_iProfileId){
				$aProfile = getProfileInfo(); 
				require_once(BX_DIRECTORY_PATH_INC . 'membership_levels.inc.php');
				$aMembershipInfo = getMemberMembershipInfo($oMain->_iProfileId);
	   
				$aAllowedLevels[] = $aMembershipInfo['ID']; 
			}

			$this->aCurrent['restriction']['membership_filter'] = array( 'value' => $aAllowedLevels, 'field' => 'membership_view_filter', 'operator' => 'in');
		}

        switch ($sMode) {

            case 'pending':
                if (isset($_REQUEST['modzzz_listing_filter']))
                    $this->aCurrent['restriction']['keyword'] = array('value' => process_db_input($_REQUEST['modzzz_listing_filter'], BX_TAGS_STRIP), 'field' => '','operator' => 'against');
                $this->aCurrent['restriction']['activeStatus']['value'] = 'pending';
                $this->sBrowseUrl = "administration";
                $this->aCurrent['title'] = _t('_modzzz_listing_page_title_pending_approval');
                unset($this->aCurrent['rss']);
            break; 
            case 'my_pending':
                $oMain = $this->getMain();
                $this->aCurrent['restriction']['owner']['value'] = $oMain->_iProfileId;
                $this->aCurrent['restriction']['activeStatus']['value'] = 'pending';
                $this->sBrowseUrl = "browse/user/" . getNickName($oMain->_iProfileId);
                $this->aCurrent['title'] = _t('_modzzz_listing_page_title_pending_approval');
                unset($this->aCurrent['rss']);
            break;
             case 'order':  
                $oMain = $this->getMain();
                
				if (isset($_REQUEST['modzzz_listing_filter'])) 
 					$this->aCurrent['restriction']['owner']['value'] = $oMain->_iProfileId;

				$this->aCurrent['ownFields'] = array('title', 'uri', 'author_id', 'thumb', 'rate', 'country', 'city', 'desc', 'categories', 'tags', 'comments_count','status','expiry_date');
   
                $this->sBrowseUrl = "browse/order/";
                $this->aCurrent['title'] = _t('_modzzz_listing_page_title_orders');
                
				$this->aCurrent['join']['invoices'] = array(
					'type' => 'inner',
					'table' => 'modzzz_listing_invoices',
					'mainField' => 'invoice_no',
					'onField' => 'invoice_no',
					'joinFields' => array('listing_id', 'invoice_no','price','days','package_id','invoice_date','invoice_due_date','invoice_expiry_date','invoice_status'),   
				);	

				$this->aCurrent['join']['orders'] = array(
					'type' => 'inner',
					'table' => 'modzzz_listing_orders',
					'mainField' => 'invoice_no',
					'onField' => 'invoice_no',
					'joinFields' => array('id','order_no','buyer_id','payment_method','order_date','order_status'),   
				); 

				unset($this->aCurrent['restriction']['activeStatus']);  

				unset($this->aCurrent['rss']);  
            break;
            case 'invoice':  
                $oMain = $this->getMain();
 
                 if (isset($_REQUEST['modzzz_listing_filter'])) 
 					$this->aCurrent['restriction']['owner']['value'] = $oMain->_iProfileId;
 
				$this->aCurrent['ownFields'] = array('title', 'uri', 'author_id', 'thumb', 'rate', 'country', 'city', 'desc', 'categories', 'tags', 'comments_count' );
  
                //$this->aCurrent['restriction']['activeStatus']['value'] = 'approved';
                $this->sBrowseUrl = "browse/invoice/";
                $this->aCurrent['title'] = _t('_modzzz_listing_page_title_invoices');
                 
				$this->aCurrent['join']['invoices'] = array(
					'type' => 'inner',
					'table' => 'modzzz_listing_invoices',
					'mainField' => 'id',
					'onField' => 'listing_id',
					'joinFields' => array('id','listing_id', 'invoice_no','price','days','package_id','invoice_status','invoice_date','invoice_due_date','invoice_expiry_date'),   
				);	
				
				unset($this->aCurrent['restriction']['activeStatus']);  
 
				unset($this->aCurrent['rss']);  
            break;
   
            case 'claim':  
                $oMain = $this->getMain();
 
                 if (isset($_REQUEST['modzzz_listing_filter'])) 
 					$this->aCurrent['restriction']['owner']['value'] = $oMain->_iProfileId;
 
				$this->aCurrent['ownFields'] = array('title', 'uri', 'author_id', 'thumb', 'rate', 'country', 'city', 'desc', 'categories', 'tags', 'comments_count' );
  
                //$this->aCurrent['restriction']['activeStatus']['value'] = 'approved';
                $this->sBrowseUrl = "browse/claim/";
                $this->aCurrent['title'] = _t('_modzzz_listing_page_title_claims');
                
				$this->aCurrent['join']['claims'] = array(
					'type' => 'inner',
					'table' => 'modzzz_listing_claim',
					'mainField' => 'id',
					'onField' => 'listing_id',
					'joinFields' => array('id', 'listing_id', 'member_id', 'claim_date', 'assign_date', 'message', 'processed'),   
				);	 
				$this->aCurrent['restriction']['subcat'] = array( 'value' => 1,'field' => 'processed','operator' => '!=', 'table' =>'modzzz_listing_claim');

				unset($this->aCurrent['restriction']['activeStatus']);  

				unset($this->aCurrent['rss']);  
            break;
 
            case 'categories':
                $oMain = $this->getMain();
				$aCategory = $oMain->_oDb->getCategoryByUri($sValue); 
                $iCategoryId = $aCategory['id'];
                $sCategoryName = $aCategory['name']; 
                 $this->aCurrent['restriction']['activeStatus']['value'] = 'approved';
                $this->sBrowseUrl = "browse/categories/$sValue";
                $this->aCurrent['title'] = _t('_modzzz_listing_page_title_category').' - '.$sCategoryName;
				$this->aCurrent['restriction']['subcat'] = array( 'table' => 'modzzz_listing_categ', 'value' => $iCategoryId,'field' => 'parent','operator' => '=');
				$this->aCurrent['join']['cat'] = array(
					'type' => 'left',
					'table' => 'modzzz_listing_categ',
					'mainField' => 'category_id',
					'onField' => 'id',
					'joinFields' => '',
				);	        
 				 
				//unset($this->aCurrent['rss']);
            break;
            case 'subcategories': 
                $oMain = $this->getMain(); 
				$aCategory = $oMain->_oDb->getCategoryByUri($sValue); 
                $iCategoryId = $aCategory['id'];
                $sCategoryName = $aCategory['name'];
				$this->aCurrent['restriction']['activeStatus']['value'] = 'approved';
                $this->sBrowseUrl = "browse/subcategories/$sValue" ;
                $this->aCurrent['title'] = _t('_modzzz_listing_page_title_category').' - '.$sCategoryName;
				$this->aCurrent['restriction']['subcat'] = array( 'value' => $iCategoryId,'field' => 'category_id','operator' => '=');
        
				$this->aCurrent['join']['sub'] = array(
					'type' => 'inner',
					'table' => 'modzzz_listing_categ',
					'mainField' => 'category_id',
					'onField' => 'id',
					'joinFields' => '',
				);					

				//unset($this->aCurrent['rss']);
            break;
 
            case 'search':
 
                if ($sValue)
                    $this->aCurrent['restriction']['keyword'] = array('value' => $sValue,'field' => '','operator' => 'against');

                if ($sValue2) 
				   $this->aCurrent['restriction']['category'] = array('value' => $sValue2,'field' => 'category_id','operator' => 'against');

                if ($sValue3)  
                    $this->aCurrent['restriction']['city'] = array('value' => $sValue3,'field' => 'city','operator' => '=');
  
				if ($sValue4)  
                    $this->aCurrent['restriction']['state'] = array('value' => $sValue4,'field' => 'state','operator' => '=');

				if (is_array($sValue5) && count($sValue5)) { 
					foreach($sValue5 as $val){
						if(trim($val))
							$bSetCountry = true;
					}
					if($bSetCountry)
						$this->aCurrent['restriction']['country'] = array('value' => $sValue5, 'field' => 'country', 'operator' => 'in');
				}
				 
                $this->sBrowseUrl = "search/$sValue/" . (is_array($sValue2) ? implode(',',$sValue2) : $sValue2);

				$sCountrySrch = $this->getPreListDisplay("Country", $sValue4);
				$sCategorySrch = $GLOBALS['oBxListingModule']->_oDb->getCategoryName($sValue2);

                $this->aCurrent['title'] = _t('_modzzz_listing_page_title_search_results') . ($sCategorySrch ? ' - '.$sCategorySrch:'') . ($sCountrySrch ? ' - '.$sCountrySrch:'') . ($sValue3 ? ' - '.$sValue3:'') .  ($sValue4 ? ' - '.$sValue4:'') . ($sValue ? ' - '.$sValue:'');
                
				unset($this->aCurrent['rss']);

				$this->aCurrent['sorting'] = 'search';
 
                break; 

            case 'user':
                $iProfileId = $GLOBALS['oBxListingModule']->_oDb->getProfileIdByNickName ($sValue);
                $GLOBALS['oTopMenu']->setCurrentProfileID($iProfileId); // select profile subtab, instead of module tab                
                if (!$iProfileId)
                    $this->isError = true;
                else
                    $this->aCurrent['restriction']['owner']['value'] = $iProfileId;
                
				$this->sBrowseUrl = "browse/user/$sValue";
                $this->aCurrent['title'] = ucfirst(strtolower($sValue)) . _t('_modzzz_listing_page_title_browse_by_author');
                if (isset($_REQUEST['rss']) && $_REQUEST['rss']) {
                    $aData = getProfileInfo($iProfileId);
                    if ($aData['Avatar']) {
                        $a = array ('ID' => $aData['author_id'], 'Avatar' => $aData['thumb']);
                        $aImage = BxDolService::call('photos', 'get_image', array($a, 'browse'), 'Search');
                        if (!$aImage['no_image'])
                            $this->aCurrent['rss']['image'] = $aImage['file'];
                    } 
                }  
                break;

            case 'admin':
                $this->aCurrent['restriction']['owner']['value'] = 0;
                $this->sBrowseUrl = "browse/admin";
                $this->aCurrent['title'] = _t('_modzzz_listing_page_title_admin_listing');
                break;

            case 'category':
            	$sValue = uri2title($sValue);

                $this->aCurrent['join']['category'] = array(
                    'type' => 'inner',
                    'table' => 'sys_categories',
                    'mainField' => 'id',
                    'onField' => 'ID',
                    'joinFields' => '',
                );
                $this->aCurrent['restriction']['category']['value'] = $sValue;
                $this->sBrowseUrl = "browse/category/$sValue";
                $this->aCurrent['title'] = _t('_modzzz_listing_page_title_browse_by_category') . ' ' . $sValue;
                break;

            case 'tag':
            	$sValue = uri2title($sValue);

                $this->aCurrent['restriction']['tag']['value'] = $sValue;
                $this->sBrowseUrl = "browse/tag/$sValue";
                $this->aCurrent['title'] = _t('_modzzz_listing_page_title_browse_by_tag') . ' ' . $sValue;
                break;

		   //[begin] - local 
			case 'local_country':         
				$this->aCurrent['restriction']['local_country'] = array('value' => $sValue, 'field' => 'country', 'operator' => '='); 
				$this->sBrowseUrl = "browse/local_country/$sValue";
				$this->aCurrent['title'] = _t('_modzzz_listing_caption_browse_local', $sValue);
				break; 
			case 'local_state':             
				$this->aCurrent['restriction']['local_state'] = array('value' => $sValue, 'field' => 'state', 'operator' => '='); 
				$this->sBrowseUrl = "browse/local_state/$sValue/$sValue2";
				$this->aCurrent['title'] = _t('_modzzz_listing_caption_browse_local', $sValue);
				break; 
			case 'local': 
				$this->aCurrent['restriction']['local'] = array('value' => $sValue, 'field' => 'city', 'operator' => '='); 
				$this->aCurrent['restriction']['item'] = array('value' => $sValue2, 'field' => 'id', 'operator' => '!=');  

				$this->sBrowseUrl = "browse/local/$sValue/$sValue2";
				$this->aCurrent['title'] = _t('_modzzz_listing_caption_browse_local', $sValue);
				break;
			case 'other':
				$sNickName = getNickName($sValue);

				$this->aCurrent['restriction']['other'] = array('value' => $sValue, 'field' => 'author_id', 'operator' => '=');  
				$this->aCurrent['restriction']['item'] = array('value' => $sValue2, 'field' => 'id', 'operator' => '!=');  

				$this->sBrowseUrl = "browse/other/$sValue/$sValue2";
				$this->aCurrent['title'] = _t('_modzzz_listing_caption_browse_other', $sNickName);
				break;
  
            case 'recent':
                $this->sBrowseUrl = 'browse/recent';
                $this->aCurrent['title'] = _t('_modzzz_listing_page_title_browse_recent');
                break;

            case 'top':
                $this->sBrowseUrl = 'browse/top';
                $this->aCurrent['sorting'] = 'top';
                $this->aCurrent['title'] = _t('_modzzz_listing_page_title_browse_top_rated');
                break;

            case 'popular':
                $this->sBrowseUrl = 'browse/popular';
                $this->aCurrent['sorting'] = 'popular';
                $this->aCurrent['title'] = _t('_modzzz_listing_page_title_browse_popular');
                break;                

            case 'featured':
				$this->aCurrent['sorting'] = 'rand'; 
				$this->aCurrent['restriction']['featured'] = array('value' => 1, 'field' => 'featured', 'operator' => '=');
                $this->sBrowseUrl = 'browse/featured';
                $this->aCurrent['title'] = _t('_modzzz_listing_page_title_browse_featured');
                break;                                

            case 'calendar':
                $this->aCurrent['restriction']['calendar-min'] = array('value' => "UNIX_TIMESTAMP('{$sValue}-{$sValue2}-{$sValue3} 00:00:00')", 'field' => 'created', 'operator' => '>=', 'no_quote_value' => true);
                $this->aCurrent['restriction']['calendar-max'] = array('value' => "UNIX_TIMESTAMP('{$sValue}-{$sValue2}-{$sValue3} 23:59:59')", 'field' => 'created', 'operator' => '<=', 'no_quote_value' => true);
                $this->sEventsBrowseUrl = "browse/calendar/{$sValue}/{$sValue2}/{$sValue3}";
                $this->aCurrent['title'] = _t('_modzzz_listing_page_title_browse_by_day') . sprintf("%04u-%02u-%02u", $sValue, $sValue2, $sValue3);
                break;                                

            case '':
                $this->sBrowseUrl = 'browse/';
                $this->aCurrent['title'] = _t('_modzzz_listing');
                break;

            default:
                $this->isError = true;
        }

        $oMain = $this->getMain();

        $this->aCurrent['paginate']['perPage'] = $oMain->_oDb->getParam('modzzz_listing_perpage_browse');

        if (isset($this->aCurrent['rss']))
            $this->aCurrent['rss']['link'] = BX_DOL_URL_ROOT . $oMain->_oConfig->getBaseUri() . $this->sBrowseUrl;

        if (isset($_REQUEST['rss']) && $_REQUEST['rss']) {
            $this->aCurrent['ownFields'][] = 'desc';
            $this->aCurrent['ownFields'][] = 'created';
            $this->aCurrent['paginate']['perPage'] = $oMain->_oDb->getParam('modzzz_listing_max_rss_num');
        }

        bx_import('Voting', $oMain->_aModule);
        $oVotingView = new BxListingVoting ('modzzz_listing', 0);
        $this->oVotingView = $oVotingView->isEnabled() ? $oVotingView : null;

        $this->sFilterName = 'modzzz_listing_filter';

        parent::BxDolTwigSearchResult();
    }

	function getPreListDisplay($sField, $sValue){ 
 		
		if(is_array($sValue)) {
			foreach($sValue as $sEachKey=>$sEachVal){
				$sValue[$sEachKey] = htmlspecialchars_adv( _t($GLOBALS['aPreValues'][$sField][$sEachVal]['LKey']) );
			}
			$sList = implode(', ',$sValue);
			return $sList;
		}else{ 
			return htmlspecialchars_adv( _t($GLOBALS['aPreValues'][$sField][$sValue]['LKey']) );
		}
	}

    function getAlterOrder() {
		if ($this->aCurrent['sorting'] == 'last') {
			$aSql = array();
			$aSql['order'] = " ORDER BY `modzzz_listing_main`.`created` DESC";
			return $aSql;
		} elseif ($this->aCurrent['sorting'] == 'top') {
			$aSql = array();
			$aSql['order'] = " ORDER BY `modzzz_listing_main`.`rate` DESC, `modzzz_listing_main`.`rate_count` DESC";
			return $aSql;
		} elseif ($this->aCurrent['sorting'] == 'popular') {
			$aSql = array();
			$aSql['order'] = " ORDER BY `modzzz_listing_main`.`views` DESC";
			return $aSql; 
		} elseif ($this->aCurrent['sorting'] == 'search') {
 
			//for search results, return featured listings first while randomizing all results
			$aSql = array();
			$aSql['order'] = " ORDER BY `modzzz_listing_main`.`featured` DESC, rand()";
			return $aSql; 
		}
 
	    return array();
    }

    function displayResultBlock () {
        global $oFunctions;
        $s = parent::displayResultBlock ();
        if ($s) {
            $oMain = $this->getMain();
            $GLOBALS['oSysTemplate']->addDynamicLocation($oMain->_oConfig->getHomePath(), $oMain->_oConfig->getHomeUrl());
            $GLOBALS['oSysTemplate']->addCss('unit.css');
            return $oFunctions->centerContent ($s, '.modzzz_listing_unit');
        }
        return '';
    }

    function getMain() {
        return BxDolModule::getInstance('BxListingModule');
    }

    function getRssUnitLink (&$a) {
        $oMain = $this->getMain();
        return BX_DOL_URL_ROOT . $oMain->_oConfig->getBaseUri() . 'view/' . $a['uri'];
    }
    
    function _getPseud () {
        return array(    
            'id' => 'id',
            'title' => 'title',
            'uri' => 'uri',
            'created' => 'created',
            'author_id' => 'author_id',
            'NickName' => 'NickName',
            'thumb' => 'thumb', 
        );
    }

	//orders and invoices
    function displayOrdersResultBlock ($sType) { 
        $aData = $this->getSearchData();
        if ($this->aCurrent['paginate']['totalNum'] > 0) {
            $sCode .= $this->addCustomParts();
            foreach ($aData as $aValue) {
                $sCode .= $this->displayOrdersSearchUnit($sType, $aValue);
            }
            $sCode = '<div class="result_block">' . $sCode . '<div class="clear_both"></div></div>';
        }
        return $sCode;
    }

    function displayOrdersSearchUnit ($sType, $aData) {
        $oMain = $this->getMain();
		 
		if($sType=='order')
			return $oMain->_oTemplate->order_unit($aData, $this->sUnitTemplate, $this->oVotingView);
  		elseif($sType=='invoice')
			return $oMain->_oTemplate->invoice_unit($aData, $this->sUnitTemplate, $this->oVotingView);
	}
  
	//claim
     function displayClaimResultBlock ($sType) { 
        $aData = $this->getSearchData();
        if ($this->aCurrent['paginate']['totalNum'] > 0) {
            $sCode .= $this->addCustomParts();
            foreach ($aData as $aValue) {
                $sCode .= $this->displayClaimSearchUnit($sType, $aValue);
            }
            $sCode = '<div class="result_block">' . $sCode . '<div class="clear_both"></div></div>';
        }
        return $sCode;
    }

    function displayClaimSearchUnit ($sType, $aData) {
        $oMain = $this->getMain();
	 
		return $oMain->_oTemplate->claim_unit($aData, $this->sUnitTemplate, $this->oVotingView); 
	}

}

?>
