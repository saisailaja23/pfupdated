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

bx_import ('BxDolProfileFields');
bx_import ('BxDolFormMedia');

class BxListingFormAdd extends BxDolFormMedia {

    var $_oMain, $_oDb;

    function BxListingFormAdd ($oMain, $iProfileId, $iEntryId = 0, $iThumb = 0) {

		$this->_oMain = $oMain;
        $this->_oDb = $oMain->_oDb;
   
   		$bPaidListing = $this->_oMain->isAllowedPaidListings (); 

		$aCategory = $this->_oDb->getFormCategoryArray();
		$aSubCategory = array();

		$sDefaultTitle = process_db_input($_REQUEST['title']);

		if($iEntryId) {
			$aDataEntry = $this->_oDb->getEntryById($iEntryId);
			$iSelSubCategory = $aDataEntry['category_id']; 
			$iSelCategory = $this->_oDb->getParentCategoryById($iSelSubCategory); 
			$aSubCategory = $this->_oDb->getFormCategoryArray($iSelCategory);
  
			$sSelState = $aDataEntry['state']; 
			$sSelCountry = $aDataEntry['country'];  
			$aStates = $this->_oDb->getStateArray($sSelCountry);  
		}else {
			$aProfile = getProfileInfo($this->_oMain->_iProfileId);
			$sSelCountry = ($_POST['country']) ? $_POST['country'] : $aProfile['Country']; 
			$sSelState = ($_POST['state']) ? $_POST['state'] : ''; 
			$aStates = $this->_oDb->getStateArray($sSelCountry);  
  
			$aSubCategory = ($_POST['parent_categories']) ? $this->_oDb->getFormCategoryArray($_POST['parent_categories']) : array();
		  
		}
 
		$sStateUrl = BX_DOL_URL_ROOT . $this->_oMain->_oConfig->getBaseUri() . 'home/?ajax=state&country=' ; 
   
		$sCatUrl = BX_DOL_URL_ROOT . $this->_oMain->_oConfig->getBaseUri() . 'home/?ajax=cat&parent=' ;
  
		if($bPaidListing){
			$iPackageId = ($iEntryId) ? (int)$aDataEntry['package_id'] : (int)$_POST['package_id']; 
			$sPackageName = $this->_oDb->getPackageName($iPackageId);
		}

        $this->_aMedia = array (
            'images' => array (
                'post' => 'ready_images',
                'upload_func' => 'uploadPhotos',
                'tag' => BX_LISTING_PHOTOS_TAG,
                'cat' => BX_LISTING_PHOTOS_CAT,
                'thumb' => 'thumb',
                'module' => 'photos',
                'title_upload_post' => 'images_titles',
                'title_upload' => _t('_modzzz_listing_form_caption_file_title'),
				'service_method' => 'get_photo_array', 
            ),
            'videos' => array (
                'post' => 'ready_videos',
                'upload_func' => 'uploadVideos',
                'tag' => BX_LISTING_VIDEOS_TAG,
                'cat' => BX_LISTING_VIDEOS_CAT,
                'thumb' => false,
                'module' => 'videos',
                'title_upload_post' => 'videos_titles',
                'title_upload' => _t('_modzzz_listing_form_caption_file_title'),
                'service_method' => 'get_video_array',
            ),            
            'sounds' => array (
                'post' => 'ready_sounds',
                'upload_func' => 'uploadSounds',
                'tag' => BX_LISTING_SOUNDS_TAG,
                'cat' => BX_LISTING_SOUNDS_CAT,
                'thumb' => false,
                'module' => 'sounds',
                'title_upload_post' => 'sounds_titles',
                'title_upload' => _t('_modzzz_listing_form_caption_file_title'),
                'service_method' => 'get_music_array',
            ),                        
            'files' => array (
                'post' => 'ready_files',
                'upload_func' => 'uploadFiles',
                'tag' => BX_LISTING_FILES_TAG,
                'cat' => BX_LISTING_FILES_CAT,
                'thumb' => false,
                'module' => 'files',
                'title_upload_post' => 'files_titles',
                'title_upload' => _t('_modzzz_listing_form_caption_file_title'),
			    'service_method' => 'get_file_array', 
            ),                                    
        );
 
        $oProfileFields = new BxDolProfileFields(0);
        $aCountries = $oProfileFields->convertValues4Input('#!Country');
        asort($aCountries);
    
        // generate templates for custom form's elements
        $aCustomMediaTemplates = $this->generateCustomMediaTemplates ($oMain->_iProfileId, $iEntryId, $iThumb);

        // privacy

        $aInputPrivacyCustom = array ();
        $aInputPrivacyCustom[] = array ('key' => '', 'value' => '----');
        $aInputPrivacyCustom[] = array ('key' => 'f', 'value' => _t('_modzzz_listing_privacy_fans_only'));
        $aInputPrivacyCustomPass = array (
            'pass' => 'Preg', 
            'params' => array('/^([0-9f]+)$/'),
        );
 

        $aInputPrivacyCustom2 = array (
            array('key' => 'f', 'value' => _t('_modzzz_listing_privacy_fans')),
            array('key' => 'a', 'value' => _t('_modzzz_listing_privacy_admins_only'))
        );
        $aInputPrivacyCustom2Pass = array (
            'pass' => 'Preg', 
            'params' => array('/^([fa]+)$/'),
        );


        $aInputPrivacyViewFans = $this->_oMain->_oPrivacy->getGroupChooser($iProfileId, 'listing', 'view_fans');
        $aInputPrivacyViewFans['values'] = array_merge($aInputPrivacyViewFans['values'], $aInputPrivacyCustom);

        $aInputPrivacyComment = $this->_oMain->_oPrivacy->getGroupChooser($iProfileId, 'listing', 'comment');
        $aInputPrivacyComment['values'] = $aInputPrivacyComment['values'];
        $aInputPrivacyComment['db'] = $aInputPrivacyCustomPass;

        $aInputPrivacyRate = $this->_oMain->_oPrivacy->getGroupChooser($iProfileId, 'listing', 'rate');
        $aInputPrivacyRate['values'] = $aInputPrivacyRate['values'];
        $aInputPrivacyRate['db'] = $aInputPrivacyCustomPass;
  
        $aInputPrivacyForum = $this->_oMain->_oPrivacy->getGroupChooser($iProfileId, 'listing', 'post_in_forum');
        $aInputPrivacyForum['values'] = $aInputPrivacyForum['values'];
        $aInputPrivacyForum['db'] = $aInputPrivacyCustomPass;
  
        $aInputPrivacyUploadPhotos = $this->_oMain->_oPrivacy->getGroupChooser($iProfileId, 'listing', 'upload_photos');
        $aInputPrivacyUploadPhotos['values'] = $aInputPrivacyCustom2;
        $aInputPrivacyUploadPhotos['db'] = $aInputPrivacyCustom2Pass;

        $aInputPrivacyUploadVideos = $this->_oMain->_oPrivacy->getGroupChooser($iProfileId, 'listing', 'upload_videos');
        $aInputPrivacyUploadVideos['values'] = $aInputPrivacyCustom2;
        $aInputPrivacyUploadVideos['db'] = $aInputPrivacyCustom2Pass;        

        $aInputPrivacyUploadSounds = $this->_oMain->_oPrivacy->getGroupChooser($iProfileId, 'listing', 'upload_sounds');
        $aInputPrivacyUploadSounds['values'] = $aInputPrivacyCustom2;
        $aInputPrivacyUploadSounds['db'] = $aInputPrivacyCustom2Pass;

        $aInputPrivacyUploadFiles = $this->_oMain->_oPrivacy->getGroupChooser($iProfileId, 'listing', 'upload_files');
        $aInputPrivacyUploadFiles['values'] = $aInputPrivacyCustom2;
        $aInputPrivacyUploadFiles['db'] = $aInputPrivacyCustom2Pass;
  
        $aCustomForm = array(

            'form_attrs' => array(
                'name'     => 'form_listing',
                'action'   => '',
                'method'   => 'post',
                'enctype' => 'multipart/form-data',
            ),      

            'params' => array (
                'db' => array(
                    'table' => 'modzzz_listing_main',
                    'key' => 'id',
                    'uri' => 'uri',
                    'uri_title' => 'title',
                    'submit_name' => 'submit_form',
                ),
            ),
                  
            'inputs' => array(

                'header_info' => array(
                    'type' => 'block_header',
                    'caption' => _t('_modzzz_listing_form_header_info')
                ),                

                'package_id' => array(
                    'type' => 'hidden',
                    'name' => 'package_id',
                    'value' => $iPackageId,  
                ),  
  
				'package_name' => array( 
					'type' => 'custom',
                    'content' => $sPackageName,  
                    'name' => 'package_name',
                    'caption' => _t('_modzzz_listing_package'), 
                ),
 
                'title' => array(
                    'type' => 'text',
                    'name' => 'title',
                    'caption' => _t('_modzzz_listing_form_caption_title'),
                    'required' => true,
                    'checker' => array (
                        'func' => 'length',
                        'params' => array(3,100),
                        'error' => _t ('_modzzz_listing_form_err_title'),
                    ),
                    'db' => array (
                        'pass' => 'Xss', 
                    ),
                    'display' => true,
                ),                
                'desc' => array(
                    'type' => 'textarea',
                    'name' => 'desc',
                    'caption' => _t('_modzzz_listing_form_caption_desc'),
                    'required' => true,
                    'html' => 2,
                    'checker' => array (
                        'func' => 'length',
                        'params' => array(20,64000),
                        'error' => _t ('_modzzz_listing_form_err_desc'),
                    ),                    
                    'db' => array (
                        'pass' => 'XssHtml', 
                    ),                    
                ), 
				'country' => array(
                    'type' => 'select',
                    'name' => 'country',
					'listname' => 'Country',
                    'caption' => _t('_modzzz_listing_form_caption_country'),
                    'values' => $aCountries,
 					'value' => $sSelCountry,
					'attrs' => array(
						'onchange' => "getHtmlData('substate','$sStateUrl'+this.value)",
					),	 
                    'required' => true, 
                    'db' => array (
                        'pass' => 'Preg', 
                        'params' => array('/([a-zA-Z]{2})/'),
                    ),
					'display' => 'getPreListDisplay', 
                ),
				'state' => array(
					'type' => 'select',
					'name' => 'state',
					'value' => $sSelState,  
					'values'=> $aStates,
					'caption' => _t('_modzzz_listing_caption_state'),
					'attrs' => array(
						'id' => 'substate',
					), 
					'db' => array (
					'pass' => 'Preg', 
					'params' => array('/([a-zA-Z]+)/'),
					), 
					'display' => 'getStateName',  
				), 
                'city' => array(
                    'type' => 'text',
                    'name' => 'city',
                    'caption' => _t('_modzzz_listing_form_caption_city'),
                    'required' => true, 
                    'checker' => array (
                        'func' => 'length',
                        'params' => array(3,150),
                        'error' => _t ('_modzzz_listing_form_err_city'),
                    ),  
                    'db' => array (
                        'pass' => 'Xss', 
                    ),
                    'display' => true,
                ),    
                'address1' => array(
                    'type' => 'text',
                    'name' => 'address1',
                    'caption' => _t('_modzzz_listing_form_caption_address1'),
                    'required' => false, 
                    'db' => array (
                        'pass' => 'Xss', 
                    ),
                    'display' => true,
                ),   				
                'address2' => array(
                    'type' => 'text',
                    'name' => 'address2',
                    'caption' => _t('_modzzz_listing_form_caption_address2'),
                    'required' => false, 
                    'db' => array (
                        'pass' => 'Xss', 
                    ),
                    'display' => true,
                ),
                'zip' => array(
                    'type' => 'text',
                    'name' => 'zip',
                    'caption' => _t('_modzzz_listing_form_caption_zip'),
                    'required' => false, 
                    'db' => array (
                        'pass' => 'Xss', 
                    ),
                    'display' => true,
                ),    
                'businessname' => array(
                    'type' => 'text',
                    'name' => 'businessname',
                    'caption' => _t('_modzzz_listing_form_caption_businessname'),
                    'required' => false, 
                    'db' => array (
                        'pass' => 'Xss', 
                    ),
                    'display' => true,
                ),
                 'businesswebsite' => array(
                    'type' => 'text',
                    'name' => 'businesswebsite',
                    'caption' => _t('_modzzz_listing_form_caption_businesswebsite'),
                    'required' => false, 
                    'db' => array (
                        'pass' => 'Xss', 
                    ),
                    'display' => true,
                ),
                'businessemail' => array(
                    'type' => 'email',
                    'name' => 'businessemail',
                    'caption' => _t('_modzzz_listing_form_caption_businessemail'),
                    'required' => false,  
					'db' => array (
                        'pass' => 'Xss', 
                    ),
                    'display' => true,
                ),
                'businesstelephone' => array(
                    'type' => 'text',
                    'name' => 'businesstelephone',
                    'caption' => _t('_modzzz_listing_form_caption_businesstelephone'),
                    'required' => false, 
                    'db' => array (
                        'pass' => 'Xss', 
                    ),
                    'display' => true,
                ),
                'businessfax' => array(
                    'type' => 'text',
                    'name' => 'businessfax',
                    'caption' => _t('_modzzz_listing_form_caption_businessfax'),
                    'required' => false, 
                    'db' => array (
                        'pass' => 'Xss', 
                    ),
                    'display' => true,
                ),  
                'tags' => array(
                    'type' => 'text',
                    'name' => 'tags',
                    'caption' => _t('_Tags'),
                    'info' => _t('_sys_tags_note'),
                    'required' => true,
                    'checker' => array (
                        'func' => 'avail',
                        'error' => _t ('_modzzz_listing_form_err_tags'),
                    ),
                    'db' => array (
                        'pass' => 'Tags', 
                    ),
                ),                

                'parent_categories' => array(
                    'type' => 'select',
                    'name' => 'parent_categories',
					'values'=> $aCategory,
                    'value' => $iSelCategory,
                    'caption' => _t('_modzzz_listing_parent_categories'),
					'attrs' => array(
						'onchange' => "getHtmlData('subcat','$sCatUrl'+this.value)",
                        'id' => 'parentcat',
					),
                    'required' => true,
                    'checker' => array (
                        'func' => 'avail',
                        'error' => _t ('_modzzz_listing_form_err_category'),
                    ), 
                ),

                'category_id' => array(
                    'type' => 'select',
                    'name' => 'category_id',
					'values'=> $aSubCategory,
                    'value' => $iSelSubCategory, 
                    'caption' => _t('_Categories'),
					'attrs' => array(
                        'id' => 'subcat',
					),
                    'required' => true,
                    'checker' => array (
                        'func' => 'avail',
                        'error' => _t ('_modzzz_listing_form_err_category'),
                    ),
                    'db' => array (
                        'pass' => 'Int', 
                    ),
                ),
 

                // images

                'header_images' => array(
                    'type' => 'block_header',
                    'caption' => _t('_modzzz_listing_form_header_images'),
                    'collapsable' => true,
                    'collapsed' => false,
                ),
                'thumb' => array(
                    'type' => 'custom',
                    'content' => $aCustomMediaTemplates['images']['thumb_choice'],
                    'name' => 'thumb',
                    'caption' => _t('_modzzz_listing_form_caption_thumb_choice'),
                    'info' => _t('_modzzz_listing_form_info_thumb_choice'),
                    'required' => false,
                    'db' => array (
                        'pass' => 'Int',
                    ),
                ), 				 
                'images_choice' => array(
                    'type' => 'custom',
                    'content' => $aCustomMediaTemplates['images']['choice'],
                    'name' => 'images_choice[]',
                    'caption' => _t('_modzzz_listing_form_caption_images_choice'),
                    'info' => _t('_modzzz_listing_form_info_images_choice'),
                    'required' => false,
                ),  
                'images_upload' => array(
                    'type' => 'custom',
                    'content' => $aCustomMediaTemplates['images']['upload'],
                    'name' => 'images_upload[]',
                    'caption' => _t('_modzzz_listing_form_caption_images_upload'),
                    'info' => _t('_modzzz_listing_form_info_images_upload'),
                    'required' => false,
                ),

				// embed video
				'header_video_embed' => array(
					'type' => 'block_header',
					'caption' => _t('_modzzz_listing_form_header_video_embed'),
					'collapsable' => true,
					'collapsed' => false,
				),
				'video_embed' => array(
					'type' => 'text',
					'name' => 'video_embed',
					'caption' => _t('_modzzz_listing_caption_video_embed_code'),
					'attrs'     => array('onclick' => 'this.focus();this.select();'),
					'required' => false,
					'html' => 2,      
					'db' => array (
					'pass' => 'XssHtml', 
					),  
				 ),
 
                // videos

                'header_videos' => array(
                    'type' => 'block_header',
                    'caption' => _t('_modzzz_listing_form_header_videos'),
                    'collapsable' => true,
                    'collapsed' => false,
                ),
 
                'videos_choice' => array(
                    'type' => 'custom',
                    'content' => $aCustomMediaTemplates['videos']['choice'],
                    'name' => 'videos_choice[]',
                    'caption' => _t('_modzzz_listing_form_caption_videos_choice'),
                    'info' => _t('_modzzz_listing_form_info_videos_choice'),
                    'required' => false,
                ),
  
                'videos_upload' => array(
                    'type' => 'custom',
                    'content' => $aCustomMediaTemplates['videos']['upload'],
                    'name' => 'videos_upload[]',
                    'caption' => _t('_modzzz_listing_form_caption_videos_upload'),
                    'info' => _t('_modzzz_listing_form_info_videos_upload'),
                    'required' => false,
                ),

                // sounds

                'header_sounds' => array(
                    'type' => 'block_header',
                    'caption' => _t('_modzzz_listing_form_header_sounds'),
                    'collapsable' => true,
                    'collapsed' => false,
                ),
 
                'sounds_choice' => array(
                    'type' => 'custom',
                    'content' => $aCustomMediaTemplates['sounds']['choice'],
                    'name' => 'sounds_choice[]',
                    'caption' => _t('_modzzz_listing_form_caption_sounds_choice'),
                    'info' => _t('_modzzz_listing_form_info_sounds_choice'),
                    'required' => false,
                ),
 
                'sounds_upload' => array(
                    'type' => 'custom',
                    'content' => $aCustomMediaTemplates['sounds']['upload'],
                    'name' => 'sounds_upload[]',
                    'caption' => _t('_modzzz_listing_form_caption_sounds_upload'),
                    'info' => _t('_modzzz_listing_form_info_sounds_upload'),
                    'required' => false,
                ),

                // files

                'header_files' => array(
                    'type' => 'block_header',
                    'caption' => _t('_modzzz_listing_form_header_files'),
                    'collapsable' => true,
                    'collapsed' => false,
                ),
 
                'files_choice' => array(
                    'type' => 'custom',
                    'content' => $aCustomMediaTemplates['files']['choice'],
                    'name' => 'files_choice[]',
                    'caption' => _t('_modzzz_listing_form_caption_files_choice'),
                    'info' => _t('_modzzz_listing_form_info_files_choice'),
                    'required' => false,
                ),
 
                'files_upload' => array(
                    'type' => 'custom',
                    'content' => $aCustomMediaTemplates['files']['upload'],
                    'name' => 'files_upload[]',
                    'caption' => _t('_modzzz_listing_form_caption_files_upload'),
                    'info' => _t('_modzzz_listing_form_info_files_upload'),
                    'required' => false,
                ),

                // privacy
                
                'header_privacy' => array(
                    'type' => 'block_header',
                    'caption' => _t('_modzzz_listing_form_header_privacy'),
                ),
 
                'allow_upload_photos_to' => $aInputPrivacyUploadPhotos, 

                'allow_upload_videos_to' => $aInputPrivacyUploadVideos, 

                'allow_upload_sounds_to' => $aInputPrivacyUploadSounds, 

                'allow_upload_files_to' => $aInputPrivacyUploadFiles, 
 
                'allow_view_listing_to' => $this->_oMain->_oPrivacy->getGroupChooser($iProfileId, 'listing', 'view_listing'),
                
				'allow_view_fans_to' => $aInputPrivacyViewFans,
 
                'allow_comment_to' => $aInputPrivacyComment,

                'allow_rate_to' => $aInputPrivacyRate, 

                'allow_post_in_forum_to' => $aInputPrivacyForum,  
  
                'allow_join_to' => $this->_oMain->_oPrivacy->getGroupChooser($iProfileId, 'listing', 'join'),

                          
            ),            
        );

        if (!$aCustomForm['inputs']['images_choice']['content']) {
            unset ($aCustomForm['inputs']['thumb']);
            unset ($aCustomForm['inputs']['images_choice']);
        }

        if (!$aCustomForm['inputs']['videos_choice']['content'])
            unset ($aCustomForm['inputs']['videos_choice']);

        if (!$aCustomForm['inputs']['sounds_choice']['content'])
            unset ($aCustomForm['inputs']['sounds_choice']);

        if (!$aCustomForm['inputs']['files_choice']['content'])
            unset ($aCustomForm['inputs']['files_choice']);
 
		if (!$bPaidListing){
            unset ($aCustomForm['inputs']['package_name']);
            unset ($aCustomForm['inputs']['package_id']); 
		}

        $aFormInputsAdminPart = array ();
        if ($GLOBALS['oBxListingModule']->isAdmin()) {

            require_once(BX_DIRECTORY_PATH_INC . 'membership_levels.inc.php');
            $aMemberships = getMemberships ();
            unset ($aMemberships[MEMBERSHIP_ID_NON_MEMBER]); // unset Non-member
            $aMemberships = array('' => _t('_modzzz_listing_membership_filter_none')) + $aMemberships;
            $aFormInputsAdminPart = array (
 
				'membership_view_filter' => array(
					'type' => 'select',
					'name' => 'membership_view_filter',
					'caption' => _t('_modzzz_listing_caption_membership_view_filter'), 
					'info' => _t('_modzzz_listing_info_membership_view_filter'), 
					'values' => $aMemberships,
					'value' => '', 
					'checker' => array (
					'func' => 'preg',
					'params' => array('/^[0-9a-zA-Z]*$/'),
					'error' => _t ('_modzzz_listing_err_membership_view_filter'),
					),                                        
					'db' => array (
					'pass' => 'Preg', 
					'params' => array('/([0-9a-zA-Z]*)/'),
					),
					
				),
  
            );
        } 

        $aFormInputsSubmit = array (
            'Submit' => array (
                'type' => 'submit',
                'name' => 'submit_form',
                'value' => _t('_Submit'),
                'colspan' => true,
            ),            
        );

        $aCustomForm['inputs'] = array_merge($aCustomForm['inputs'], $aFormInputsAdminPart, $aFormInputsSubmit);
  

        $this->processMembershipChecksForMediaUploads ($aCustomForm['inputs']);

		if($bPaidListing){
			 $this->processPackageChecksForMediaUploads ($iPackageId, $aCustomForm['inputs']);
		}

        parent::BxDolFormMedia ($aCustomForm);
    }

    function processPackageChecksForMediaUploads ($iPackageId, &$aInputs) {

        $isAdmin = $GLOBALS['logged']['admin'] && isProfileActive($this->_iProfileId);

		if($isAdmin)
		   return;

		$aPackage = $this->_oDb->getPackageById($iPackageId);

        $a = array ('images', 'videos', 'sounds', 'files' );
        foreach ($a as $k ) {
			$isAllowedMedia = $aPackage[$k];
            if ( !$isAllowedMedia ) {
                unset($this->_aMedia[$k]);
                unset($aInputs['header_'.$k]);
                unset($aInputs[$k.'_choice']);
                unset($aInputs[$k.'_upload']);
 
				if(($k=='videos') && (getParam("modzzz_listing_allow_embed")!="on")){
					unset($aInputs['header_video_embed']);
					unset($aInputs['video_embed']); 
				} 
            }        
        }  
    }

    function processMembershipChecksForMediaUploads (&$aInputs) {

        $isAdmin = $GLOBALS['logged']['admin'] && isProfileActive($this->_iProfileId);

        defineMembershipActions (array('photos add', 'sounds add', 'videos add', 'files add', 'listing photos add', 'listing sounds add', 'listing videos add', 'listing files add'));

		$aCheck = checkAction($_COOKIE['memberID'], BX_PHOTOS_ADD);
        if ($aCheck[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED && !$isAdmin) {
            unset($aInputs['thumb']);
        }

        $a = array ('images' => 'PHOTOS', 'videos' => 'VIDEOS', 'sounds' => 'SOUNDS', 'files' => 'FILES');
        foreach ($a as $k => $v) {
			if (defined("BX_{$v}_ADD"))
				$aCheck = checkAction($_COOKIE['memberID'], constant("BX_{$v}_ADD"));
            if ((!defined("BX_{$v}_ADD") || $aCheck[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED) && !$isAdmin) {
                unset($this->_aMedia[$k]);
                unset($aInputs['header_'.$k]);
                unset($aInputs[$k.'_choice']);
                unset($aInputs[$k.'_upload']);

				if(($k=='videos') && (getParam("modzzz_listing_allow_embed")!="on")){
					unset($aInputs['header_video_embed']);
					unset($aInputs['video_embed']); 
				}  
            }        
        }

        $a = array ('images' => 'PHOTOS', 'videos' => 'VIDEOS', 'sounds' => 'SOUNDS', 'files' => 'FILES');
        foreach ($a as $k => $v) {
			if (defined("BX_LISTING_{$v}_ADD"))
				$aCheck = checkAction($_COOKIE['memberID'], constant("BX_LISTING_{$v}_ADD"));
            if ((!defined("BX_LISTING_{$v}_ADD") || $aCheck[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED) && !$isAdmin) {
                unset($this->_aMedia[$k]);
                unset($aInputs['header_'.$k]);
                unset($aInputs[$k.'_choice']);
                unset($aInputs[$k.'_upload']);

				if(($k=='videos') && (getParam("modzzz_listing_allow_embed")!="on")){
					unset($aInputs['header_video_embed']);
					unset($aInputs['video_embed']); 
				}  
            }        
        } 
    }



}

?>
