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

    bx_import('BxDolCron');

    require_once('SasProfileInfoModule.php');
    require_once(BX_DIRECTORY_PATH_CLASSES . 'BxDolEmailTemplates.php');

    class SasProfileInfoCron extends BxDolCron 
    {
        var $oModule;

        /** 
         * Class constructor;
         */
        function SasProfileInfoCron()
        {
            $this -> oModule = BxDolModule::getInstance('SasProfileInfoModule');   
        }

        function processing() 
        {
            if($this -> oModule -> _oConfig -> bNotifyEnabled) {
                $aProfiles = $this -> oModule 
                    -> _oDb -> getProfiles($this -> oModule -> _oConfig -> iLastProfileId
                        , $this -> oModule -> _oConfig -> iMsgCount
                        , $this -> oModule -> _oConfig -> iUpdateTime);

                //process profiles
                if($aProfiles) {
                    $oAvatar = BxDolModule::getInstance('BxAvaModule');
                    $oEmailTemplate = new BxDolEmailTemplates();

                    $aTemplateInfo = $oEmailTemplate -> getTemplate('esase_ProfileChecker') ;
                    $aTemplateThumbnail = $oEmailTemplate 
                        -> getTemplate('esase_ProfileCheckerEmptyThumbnail') ;

                    $iLastProfileId = 0;
                    foreach($aProfiles as $iKey => $aItems) {
                        $iLastProfileId = $aItems['ID'];
                        $bNotificationSend = false;

                        //-- check thumbnail --//
                        if($this -> oModule -> _oConfig -> bSendMsgEmptyThumbnail) {
                            if( isset($aItems['Avatar']) && !$aItems['Avatar']) {
                                // send notification
                                $aPlus = array(
                                    'avatarUrl' => BX_DOL_URL_ROOT . $oAvatar -> _oConfig -> getBaseUri(),
                                );

                              //  if( sendMail($aItems['Email'], $aTemplateThumbnail['Subject']
                              //      , $aTemplateThumbnail['Body'], '', $aPlus) ) {
    
                              //    $bNotificationSend = true;
                             //}

                            }
                        }
                        //--

                        //-- check percentage --//
                        $aFieldsInfo = $this -> oModule -> _oDb 
                            -> getProfileFieldsInfo($aItems['ID']);

                        $iPercent = round($aFieldsInfo['filled'] * 100 / $aFieldsInfo['count_fields']); 
                        if($iPercent < $this -> oModule -> _oConfig -> iPercentage) {
                            // send notification
		
		GLOBAL $site;
                            $aPlus = array(
                               //'editLink'     => BX_DOL_URL_ROOT . 'pedit.php?ID=' . $aItems['ID'],
					'editLink'     => $site['url'],

                            );

                           //if( sendMail($aItems['Email'], $aTemplateInfo['Subject']
                              //  , $aTemplateInfo['Body'], '', $aPlus) ) {

                              //  $bNotificationSend = true;
                           // }

                        }
                        //--

                        if($bNotificationSend) {
                            //update notify time
                            $this -> oModule -> _oDb -> updateNotifyTime($aItems['ID']);
                        }
                    }

                    //update last processed profile id
                    $this -> oModule -> _oDb -> setParam( $this 
                        -> oModule -> _oConfig -> sOptionsPrefix . 'last_id', $iLastProfileId);
                }
                else {
                    //flush last id
                    $this -> oModule -> _oDb -> setParam($this -> oModule -> _oConfig 
                        -> sOptionsPrefix . 'last_id', 0);
                }
            }
        }
    }