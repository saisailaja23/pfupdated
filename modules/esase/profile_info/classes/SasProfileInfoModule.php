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

    class SasProfileInfoModule extends BxDolModule 
    {
        // contain some module information ;
        var $aModuleInfo;
        // contain path for current module;
        var $sPathToModule;

        var $sHomeUrl;
        // logged member's Id;
        var $iMemberId;

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
    	function SasProfileInfoModule(&$aModule) 
        {
            parent::BxDolModule($aModule);

            // prepare the location link ;
            $this -> sPathToModule  = BX_DOL_URL_ROOT . $this -> _oConfig -> getBaseUri();
            $this -> aModuleInfo    = $aModule;

            $this -> sHomeUrl       = $this ->_oConfig -> _sHomeUrl;

            // define member id;
            $this -> iMemberId = getLoggedId();
        }

        /**
         * Function will generate twitter's admin page;
         *
         * @return : (text) - html presentation data; 
         */
        function actionAdministration()
        {
            if( !isAdmin() ) {
                header('location: ' . BX_DOL_URL_ROOT);
            }

            $this -> _oTemplate -> addCss('forms_adv.css');
            $this -> _oTemplate-> pageCodeAdminStart();

            $sSettingsUrl = $this -> sPathToModule . 'administration/';

            //output content
            $this -> _oTemplate-> pageCodeAdminStart();
	            echo $this -> _oTemplate -> getAdminBlock($this 
	                -> _getAdmSettingsPage($sSettingsUrl), _t('_Settings'), '');
			$this -> _oTemplate -> pageCodeAdmin( _t('_sas_profile_info') );
        }

        /**
         * Get checker block
         * 
         * @return text
         */
   		function serviceGetCheckerBlock($iProfileId = 0)
        {
        	if($this -> iMemberId || $iProfileId) {
                $bViewLink = $iProfileId == $this -> iMemberId || !$iProfileId  ? true : false;

                $aFieldsInfo = $this -> _oDb -> getProfileFieldsInfo($iProfileId ? $iProfileId : $this -> iMemberId);
				$iPercent = round($aFieldsInfo['filled'] * 100 / $aFieldsInfo['count_fields']);

				return $this -> _oTemplate -> getFieldInfo($iPercent, $iProfileId ? $iProfileId : $this -> iMemberId, $bViewLink);
        	}
        }

		/**
         * Get admin settings page
         * 
         * @param $sPageUrl string
         * @return text
         */
        function _getAdmSettingsPage($sPageUrl)
        {
        	$sMessage = '';
			$sPageContent = '';

            //try to get Db category id
            $iCategId = $this -> _oDb -> getSettingsCategoryId($this -> _oConfig -> sOptionsPrefix 
                . 'percentage');

            //generate settings form	
            if($iCategId) {
            	$aSettings = $this -> _oDb -> getSettings($iCategId);
            	$aForm = array (
					'form_attrs' => array (
	                    'action' =>  $sPageUrl,
	                    'method' => 'post',
	                    'name' => 'settingsform'
                    ),
					'params' => array (
	               		'db' => array(
							'submit_name' => 'submit',
						),
					),
				);

				$aHiddenElements = array();
           		foreach($aSettings as $iKey => $aItems)
				{
					switch($aItems['Type']) {
						case 'digit' :
							$aForm['inputs'][] = array(
		                        'type' => 'text',
		                        'name' => $aItems['Name'],
		                        'value' => $aItems['VALUE'],
		                        'caption' => _t('_sas_profile_info_adm_' . $aItems['desc']),
								'checker' => array (
									'func' => 'length',
									'params' => array(1, 1000),
									'error' => _t('_sas_profile_info_adm_input_error'),
								),
								'required' => true,
							);
							break;

						case 'checkbox' :
							if( false != bx_get('submit') ) {
								$bElementChecked = false != bx_get($aItems['Name'])
									? true
									: false;
							}
							else {
								$bElementChecked = 'on' == $aItems['VALUE']
									? true
									: false;
							}

							$aForm['inputs'][] = array(
		                        'type' => 'checkbox',
		                        'name' => $aItems['Name'],
		                        'value' => 'on',
		                        'caption' => _t('_sas_profile_info_adm_' . $aItems['desc']),
								'checked' => $bElementChecked,
							);

							$aHiddenElements[] = $aItems['Name'];
							break;	
					}
				}

				$aForm['inputs'][] = array(
                    'type' => 'submit',
                    'name' => 'submit',
                	'value' => _t('_Save'),
				);

				$oForm = new BxTemplFormView($aForm);  
				$oForm -> initChecker();

            	if ($oForm->isSubmittedAndValid()) {
					//-- process and save post data --//
		            if( !empty($_POST) ) {
		                foreach($_POST as $sParamName => $vParamValue)
		                {
		                	//check param
		                	if( 0 === strncmp($sParamName, $this -> _oConfig -> sOptionsPrefix
		                		, strlen($this -> _oConfig -> sOptionsPrefix) ) ) {

		                		if( is_array($vParamValue) ) {
			                		$vParamValue = implode(',', $vParamValue);
			                	}

			                	// save values
			                    setParam($sParamName, $vParamValue);
		                	}
		                }

		                //-- process all hidden elements  --//
		                foreach($aHiddenElements as $iKey => $sElementName)
		                {
		                	if( !isset($_POST[$sElementName]) ) {
		                		setParam($sElementName, '');
		                	}	
		                }
						//--

		                $sMessage = MsgBox( _t('_adm_txt_settings_success'), 2 );
		            }
					//--
				}

				//get code
				$sPageContent = $oForm -> getCode();
			}
			else {
				$sPageContent = MsgBox( _t('_Empty') );
			}

			return $sMessage . $sPageContent;
        }
    }