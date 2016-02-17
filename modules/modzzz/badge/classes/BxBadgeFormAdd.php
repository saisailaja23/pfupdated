<?php
/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Badge
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

class BxBadgeFormAdd extends BxDolFormMedia {

    var $_oMain, $_oDb;

    function BxBadgeFormAdd ($oMain, $iEntryId = 0 , $iThumb = 0 ) {

        $this->_oMain = $oMain;
        $this->_oDb = $oMain->_oDb;
  
		$aExistMemberships = $this->_oDb->getMembershipsWithIcon();

		$memberships_arr = getMemberships();
		foreach ( $memberships_arr as $membershipID => $membershipName ) {
			if ($membershipID == MEMBERSHIP_ID_NON_MEMBER) continue;

			if (in_array($membershipID, $aExistMemberships)) continue;

			$aMembershipOptions[$membershipID] = $membershipName;
		}

		if($iEntryId) {
			$aDataEntry = $this->_oDb->getEntryById($iEntryId);
 			$sIconName = $aDataEntry['icon'];
 			$sImageName = $aDataEntry['large_icon']; 
 			$iMembershipId = (int)$aDataEntry['membership_id']; 
 			$sMembershipName = $memberships_arr[$aDataEntry['membership_id']];  
 		}
 

        $aCustomForm = array(

            'form_attrs' => array(
                'name'     => 'form_badge',
                'action'   => '',
                'method'   => 'post',
                'enctype' => 'multipart/form-data',
            ),      

            'params' => array (
                'db' => array(
                    'table' => 'modzzz_badge_main',
                    'key' => 'id', 
					'uri' => '',
					'uri_title' => '', 
                    'submit_name' => 'submit_form',
                ),
            ),
                 
            'inputs' => array(

                'header_info' => array(
                    'type' => 'block_header',
                    'caption' => _t('_modzzz_badge_form_header_info')
                ),    
        		'membership_id' => array (
            		'type' => 'select',
            		'name' => 'membership_id',
            		'caption' => _t('_adm_mmi_membership_levels'),
            		'values' => $aMembershipOptions,
            		'value' => $iMembershipId, 
					'db' => array (
						'pass' => 'Xss', 
					),
            	),
				'membership_name' => array (
            		'type' => 'custom',
            		'name' => 'membership_name',
            		'caption' => _t('_modzzz_badge_membership'),
            		'content' => $sMembershipName  
            	),			

				'presenticon' => array(
					'type' => 'custom',
					'name' => "presenticon", 
					'caption' => _t('_modzzz_badge_present_icon'), 
					'content' =>  $this->_oDb->getBadgeIcon($iEntryId, $sIconName) 
				), 
				 
				'iconfile' => array(
					'type' => 'file',
					'name' => "iconfile",
					'caption' => _t('_modzzz_badge_icon'), 
 				), 
 
				'presentimage' => array(
					'type' => 'custom',
					'name' => "presentimage", 
					'caption' => _t('_modzzz_badge_present_image'), 
					'content' =>  $this->_oDb->getBadgeIcon($iEntryId, $sImageName, false, true) 
				), 
				 
				'imagefile' => array(
					'type' => 'file',
					'name' => "imagefile",
					'caption' => _t('_modzzz_badge_image'), 
				),  
  				'resize_icon' => array(
                    'type' => 'select',
                    'name' => 'resize_icon',
                    'caption' => _t('_modzzz_badge_form_caption_resize_icon'),
                    'values' => array(
									1=>_t('_modzzz_badge_yes'),  
									0=>_t('_modzzz_badge_no')
								),
                    'required' => false,   
				), 
  				'resize_image' => array(
                    'type' => 'select',
                    'name' => 'resize_image',
                    'caption' => _t('_modzzz_badge_form_caption_resize_image'),
                    'values' => array(
									1=>_t('_modzzz_badge_yes'),  
									0=>_t('_modzzz_badge_no')
								),
                    'required' => false,  
				),   
                'Submit' => array (
                    'type' => 'submit',
                    'name' => 'submit_form',
                    'value' => _t('_Save'),
                    'colspan' => false,
                ),  
					
                'Delete' => array (
                    'type' => 'submit',
                    'name' => 'delete_badge',
                    'value' => _t('_Delete'),
                    'colspan' => false,
 
                ), 

            ),            
        );
	  
	    if($iEntryId){ 
			$aCustomForm['inputs']['membership_id']['type']='hidden';  
	    }else{ 
			unset($aCustomForm['inputs']['presenticon']);
			unset($aCustomForm['inputs']['presentimage']); 
			unset($aCustomForm['inputs']['Delete']); 
			unset($aCustomForm['inputs']['membership_name']); 
		}

        //$this->processMembershipChecksForMediaUploads ($aCustomForm['inputs']);

        parent::BxDolFormMedia ($aCustomForm);
    }

}

?>
