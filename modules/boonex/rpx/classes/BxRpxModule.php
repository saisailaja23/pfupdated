<?php

    /***************************************************************************
    *                            Dolphin Smart Community Builder
    *                              -------------------
    *     begin                : Mon Mar 23 2006
    *     copyright            : (C) 2007 BoonEx Group
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

    bx_import('BxDolModuleDb');
    bx_import('BxDolModule');
    bx_import('BxDolInstallerUtils');

    define('RPX_AUTH_INFO_URL', 'https://rpxnow.com/api/v2/auth_info');

    require_once( BX_DIRECTORY_PATH_PLUGINS . 'Services_JSON.php' );

    /*
     * RPX module by BoonEx
     *
     * Janrain Engage helps connect your site to the social web through a robust set 
     * of APIs and social widget interfaces. Janrain Engage allows your visitors 
     * to sign-in to your site with their existing accounts on Facebook, Google, Twitter, 
     * Yahoo!, LinkedIn, Windows Live, MySpace, AOL or other networks and then publish 
     * their comments, purchases, reviews or other activities from your site to multiple social networks.
     * 
     *
     *
     * Profile's Wall:
     * no wall events
     *
     *
     *
     * Spy:
     * no spy events
     *
     *
     *
     * Memberships/ACL:
     * no levels;
     *
     * 
     *
     * Service methods:
     *
     *
     * Alerts:
     * no alerts;
     *
     */
    class BxRpxModule extends BxDolModule 
    {
        // contain some module information ;
        var $aModuleInfo;

        // contain path for current module;
        var $sPathToModule;

        /**
    	 * Class constructor ;
         *
         * @param   : $aModule (array) - contain some information about this module;
         *                  [ id ]           - (integer) module's  id ;
         *                  [ title ]        - (string)  module's  title ;
         *                  [ vendor ]       - (string)  module's  vendor ;
         *                  [ path ]         - (string)  path to this module ;
         *                  [ uri ]          - (string)  this module's URI ;
         *                  [ class_prefix ] - (string)  this module's php classes file prefix ;
         *                  [ db_prefix ]    - (string)  this module's Db tables prefix ;
         *                  [ date ]         - (string)  this module's date installation ;
    	 */
    	function BxRpxModule(&$aModule) 
        {
            parent::BxDolModule($aModule);

            // prepare the location link ;
            $this -> sPathToModule  = BX_DOL_URL_ROOT . $this -> _oConfig -> getBaseUri();
            $this -> aModuleInfo    = $aModule;
        }

        /**
         * Function will generate rpx's admin page;
         *
         * @return : (text) - html presentation data; 
         */
        function actionAdministration()
        {
        	$GLOBALS['iAdminPage'] = 1;

            if( !isAdmin() ) {
                header('location: ' . BX_DOL_URL_ROOT);
            }

            // get sys_option's category id;
            $iCatId = $this-> _oDb -> getSettingsCategoryId('bx_rpx_api_key');
            if(!$iCatId) {
                $sOptions = MsgBox( _t('_Empty') );
            }
            else {
                bx_import('BxDolAdminSettings');

                $oSettings = new BxDolAdminSettings($iCatId);
                
                $mixedResult = '';
                if(isset($_POST['save']) && isset($_POST['cat'])) {
                    $mixedResult = $oSettings -> saveChanges($_POST);
                }

                // get option's form;
                $sOptions = $oSettings -> getForm();
                if($mixedResult !== true && !empty($mixedResult)) {
                    $sOptions = $mixedResult . $sOptions;
                }
            }

            $this -> _oTemplate-> pageCodeAdminStart();

                echo DesignBoxAdmin( _t('_bx_rpx_info')
                        , $GLOBALS['oSysTemplate'] -> parseHtmlByName('default_padding.html', array('content' => _t('_bx_rpx_info_desc'))) );
                echo DesignBoxAdmin( _t('_Settings')
                        , $GLOBALS['oSysTemplate'] -> parseHtmlByName('default_padding.html', array('content' => $sOptions) ));

            $this -> _oTemplate->pageCodeAdmin( _t('_bx_rpx_settings') );
        }

        /**
         * Get login form
         * 
         * @return text
         */
        function actionLoginForm()
        {
           $sCode = '';

			if($this -> _oConfig -> mApiKey && $this -> _oConfig -> sApllicationName) {
				$sCode = $this  -> _oTemplate 
              		  -> getLoginForm($this -> sPathToModule . 'AuthorizeMember', getCurrentLangName());
			}
			else {
				$sCode = MsgBox( _t('_bx_rpx_configure_error') );
			}

            $this -> _oTemplate -> getPage( _t('_bx_rpx_universal'), $sCode );
        }

        /**
         * authorize member
         *
         * @return void
         */
        function actionAuthorizeMember()
        {
            if( false != bx_get('token') ) {
                $aPostData = array('token' => bx_get('token'),
                    'apiKey' => $this -> _oConfig -> mApiKey,
                    'format' => 'json'); 

                $sJsonResult = $this -> _execRequest(RPX_AUTH_INFO_URL, $aPostData);
                $aProfileInfo = json_decode($sJsonResult, true);

                if ( isset($aProfileInfo['stat'], $aProfileInfo['profile']) && $aProfileInfo['stat'] == 'ok') {
                    // try define user id 
                    $iProfileId = $this -> _oDb -> getProfileId( md5($aProfileInfo['profile']['identifier']) );
                    if($iProfileId) {
                        //logged profile
                        $aDolphinProfileInfo = getProfileInfo($iProfileId);
                   		$this -> setLogged($iProfileId, $aDolphinProfileInfo['Password']);
                    }
                    else {
                        // check nick name
                        if(!$this -> _checkNickName($aProfileInfo['profile']['displayName']) ) {
                   			// create new profile;
                   			$iProfileId = $this -> _createProfile($aProfileInfo['profile']);
                   		}
                        else {
                            //check nickname again
                            $sAlternative = '_' . $aProfileInfo['profile']['providerName'];
                            if(!$this -> _checkNickName($aProfileInfo['profile']['displayName'], $sAlternative) ) {
                                //create new profile
                                $iProfileId = $this -> _createProfile($aProfileInfo['profile'], $sAlternative);
                            }
                            else {
                                $this -> _oTemplate 
                                    -> getPage( _t('_bx_rpx_extended'),  MsgBox( _t('_bx_rpx_profile_exist'
                                        , $aProfileInfo['profile']['displayName'] . $sAlternative) ) );
                            }

                           
                        }
                    }
                }
                else {
                    $this -> _oTemplate 
                        -> getPage( _t('_bx_rpx_extended'),  MsgBox( _t('_bx_rpx_error_occurred') ) );
                }
            }
            else {
                $this -> _oTemplate 
                            -> getPage( _t('_bx_rpx_extended'),  MsgBox( _t('_bx_rpx_error_occurred') ) );
            }
        }

        /**
         * Logged profile
         *
         * @param $iProfileId integer
         * @param $sPassword string
         * @param $sCallbackUrl
         * @return void
         */
        function setLogged($iProfileId, $sPassword, $sCallbackUrl = '')
        {
            bx_login($iProfileId);
            $sCallbackUrl = $sCallbackUrl ? $sCallbackUrl : BX_DOL_URL_ROOT;
            header('location: ' . $sCallbackUrl);
        }

        /**
         * Generate login form
         * 
         * @return text
         */
        function serviceGetLoginForm()
        {
        	if($this -> _oConfig -> mApiKey && $this -> _oConfig -> sApllicationName) {
				$this -> _oTemplate -> addJs('rpx.js');

				return $this -> _oTemplate 
					-> getLoginForm($this -> sPathToModule . 'AuthorizeMember'
						, getCurrentLangName(), 'login_box_index.html');
        	}
        }

        /**
         * Check nickname
         *
         * @param $sNickName string
         * @param $sAlternative string
         * @return integer
         */
        function _checkNickName($sNickName, $sAlternative = '')
        {
            $aName = explode(' ', $sNickName);
            return getID($aName[0] . $sAlternative);
        }

        /**
         * Create new profile
         *
         * @param $aProfileInfo array
         * @param $sAlternative string
         * @return integer
         */
        function _createProfile($aProfileInfo, $sAlternative = '')
        {
            // procces the date of birth;
            $aProfileInfo['birthday'] = isset($aProfileInfo['birthday'])
              	?  date('Y-m-d', strtotime($aProfileInfo['birthday']) )
               	:  '';

            // generate new password for profile;
            $sSalt = genRndSalt();
          	$aProfileInfo['password'] = encryptUserPwd(genRndPwd(), $sSalt);

            // define nick name;
          	$aNickName = explode(' ' , $aProfileInfo['displayName']);
            $sNickName = $aNickName[0] . $sAlternative;

            // fill array with all needed values;
            $aProfileFields = array(
                'NickName'      		=> $sNickName,
                'Email'         		=> isset($aProfileInfo['email']) ? $aProfileInfo['email'] : '',
                'Sex'           		=> isset($aProfileInfo['gender']) ? $aProfileInfo['gender'] : '',
                'DateOfBirth'   		=> $aProfileInfo['birthday'],

                'Password'      		=> $aProfileInfo['password'],

            	'FirstName'				=> $aNickName[0],
                'LastName'				=> isset($aNickName[1]) ? $aNickName[1] : '',

            	'DescriptionMe' 		=> isset($aProfileInfo['about']) ? $aProfileInfo['about'] : '',
                'Interests'     		=> isset($aProfileInfo['interests']) ? $aProfileInfo['interests'] : '',

                'Religion'      		=> isset($aProfileInfo['religion']) ? $aProfileInfo['religion'] : '',
            );

            // check fields existence;
            foreach($aProfileFields as $sKey => $mValue) {
                if( !$this -> _oDb -> isFieldExist($sKey)) {
                    // (field not existence) remove from array;
                    unset($aProfileFields[$sKey]);
                }
            }

            // add some system values;
            $aProfileFields['Role'] = 1;
            $aProfileFields['RpxProfile'] = md5($aProfileInfo['identifier']);
            $aProfileFields['DateReg'] 	  = date( 'Y-m-d H:i:s' ); // set current date;

            // create new profile;
            $iProfileId = $this -> _oDb -> createProfile($aProfileFields);

            // check profile status;
            if ( getParam('autoApproval_ifNoConfEmail') == 'on' ) {
                if ( getParam('autoApproval_ifJoin') == 'on' ) {
                    $sProfileStatus = 'Active';
                }    
                else {
                    $sProfileStatus = 'Approval';
                }
            } 
            else {
                $sProfileStatus = 'Unconfirmed';
            }

            // update profile's status;
            $this -> _oDb -> updateProfileStatus($iProfileId, $sProfileStatus);

            // create system event
            bx_import('BxDolAlerts');
            $oZ = new BxDolAlerts('profile', 'join', $iProfileId);
            $oZ -> alert();

            // check avatar module;
            if( BxDolInstallerUtils::isModuleInstalled('avatar') ) {
                // check profile's logo;
                if( isset($aProfileInfo['photo']) && $aProfileInfo['photo'] ) {
                    BxDolService::call('avatar', 'set_image_for_cropping', array ($iProfileId, $aProfileInfo['photo'])); 
                }

                if (BxDolService::call('avatar', 'join', array ($iProfileId, '_Join complete'))) {
                    exit;
                }
            }
            else {
                // set logged and redirect on home page;
                $aProfileInfo = getProfileInfo($iProfileId);
                $this -> setLogged($iProfileId, $aProfileInfo['Password']);
            }
        }

        /**
         * Exec request
         *
         * @param $sPageUrl string
         * @param $aParams array
         * @return text
         */
        function _execRequest($sPageUrl, $aParams = array() )
        {
            $oCurl = curl_init();
            curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($oCurl, CURLOPT_URL, $sPageUrl);
            curl_setopt($oCurl, CURLOPT_POST, true);
            curl_setopt($oCurl, CURLOPT_POSTFIELDS, $aParams);
            curl_setopt($oCurl, CURLOPT_HEADER, false);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, false);
            $sOutputCode = curl_exec($oCurl);
            curl_close($oCurl);

            return $sOutputCode;
        }
    }
