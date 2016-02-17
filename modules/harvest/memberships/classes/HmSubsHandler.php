<?php
/***************************************************************************
Dolphin Subscriptions v.2.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/
class HmSubsHandler{
  var $_oMain, $iUserId = 0;
  
	function HmSubsHandler(){
		$this ->_oMain = BxDolModule::getInstance("HmSubsModule");
		if ($this ->_oMain->isLogged()) $this ->iUserId = (int)$this ->_oMain->getUserId();
		$this->iRole = $this->_oMain->_oDb->getUserRole($this->iUserId);
		$this->aProfile = $this->_oMain->_oDb->getProfileArr($this->iUserId);
		$this->aSettings = $this->_oMain->_oDb->getSettings();
		//$this->sMembPage = BX_DOL_URL_ROOT.'m/membership/index/';
               // $this->sMembPage = BX_DOL_URL_ROOT.'extra_member.php';
                
		$this->sJoinPage = BX_DOL_URL_ROOT.'join.php';
		$sBase = BX_DOL_URL_ROOT;  
		$this->sSecureBase = str_replace('http://', 'https://', $sBase);
	}
	
	function currentPage(&$oAlertInfo){
		$sPageCaption = $this->_oMain->_oDb->getPageCaption($oAlertInfo->iObject);
		$iId = (int)$this->iUserId;
		$sStatus = $this->aProfile['Status'];		
		$aSafe = $this->_oMain->_oConfig->safe_pages();
		$aSafeGuest = $this->_oMain->_oConfig->safe_pages_guest();
		$iIsAcl = $this->_oMain->_oDb->userAcl($iId);
		$iCurrentMemLevel = $this->_oMain->_oDb->getCurrentMembershipLevel($iId);
		$aMenuItemAccess = $this->_oMain->_oDb->getMenuAccessLevels($oAlertInfo->iObject);
		
		// Setup URL restriction
		$site_url = BX_DOL_URL_ROOT;
		$sCurrentURL = str_replace($site_url, '',$oAlertInfo->aExtras['current_url']);
		//$aRestrictedMemLevelsForURL = unserialize($this->_oMain->_oDb->getRestrictedMemLevelsForURL($sCurrentURL));

		// Handle Logged Members
		if($iId && $this->iRole != '3'){			
			if($this->aSettings['require_mem'] == '1'){
				if((($sStatus != 'Active') || ($iIsAcl == '0')) && (!strstr($_SERVER["REQUEST_URI"],'page/paymentresult') && !strstr($_SERVER["REQUEST_URI"],'m/membership') && !strstr($_SERVER["REQUEST_URI"],'m/payment'))) {
					if(!in_array($sPageCaption, $aSafe) && $oAlertInfo->iObject != '-1'){ 
                                                if($this->aProfile['ProfileType'] != '8') {
                                                Redirect($this->sMembPage, array('login_text' => '_login_txt_not_active'), 'post');
                                                }
                                                else {
                                                // header("Location:extra_agency_view_28.php");  
                                                    
                                                }
                                           }
				}
			      if((($sStatus == 'Suspended') && ($iIsAcl == '1'))){
					$sProfileCache = BX_DIRECTORY_PATH_CACHE . 'user' . $iId . '.php';
					$this->_oMain->_oDb->setUserStatus($iId,'Active');
                                  $this->_oMain->_oDb->setUserStatus_draft($iId,'Active');
					unlink($sProfileCache);
				}

                           if((($sStatus == 'Approval') && ($iIsAcl == '1'))){
					$sProfileCache = BX_DIRECTORY_PATH_CACHE . 'user' . $iId . '.php';
					$this->_oMain->_oDb->setUserStatus($iId,'Approval');
                                  $this->_oMain->_oDb->setUserStatus_draft($iId,'Approval');
					unlink($sProfileCache);
				}	
				
			}else{
				if(!in_array($sPageCaption, $aSafe) && $oAlertInfo->iObject != '-1'){						
					if(is_array($aMenuItemAccess)){	
						if(in_array($iCurrentMemLevel,$aMenuItemAccess)){
							$this->_oMain->_oTemplate->customDisplayAccessDenied();
							exit();
						}		
					}	
				}


                        


				/* If URL restriction applies
				if(!empty($aRestrictedMemLevelsForURL) && in_array($iCurrentMemLevel,$aRestrictedMemLevelsForURL)){	
					$this->_oMain->_oTemplate->customDisplayAccessDenied();
					exit();
				} */
			}

		}

		// Handle Guests
		if(!$iId && $this->iRole != '3' && !$_COOKIE['memberID']){			
			if($this->aSettings['redirect_guests'] == '1' ){                                            
                                            if( !strstr($_SERVER["REQUEST_URI"],'join.php') && !strstr($_SERVER["REQUEST_URI"],'m/payment') )
                                            {  
                                                if(!in_array($sPageCaption, $aSafeGuest)  && $oAlertInfo->iObject != '-1')
                                                Redirect($this->sJoinPage, array('login_text' => '_login_txt_not_active'), 'post');
                                            }

                        }else{
				$iCurrentMemLevel = '1';
				if(!in_array($sPageCaption, $aSafeGuest) && $oAlertInfo->iObject != '-1'){
					if(is_array($aMenuItemAccess)){	
						if(in_array($iCurrentMemLevel,$aMenuItemAccess)){
							$this->_oMain->_oTemplate->customDisplayAccessDenied();
							exit();
						}		
					}
				}

				/* If URL restriction applies
				if(!empty($aRestrictedMemLevelsForURL) && in_array($iCurrentMemLevel,$aRestrictedMemLevelsForURL)){	
					$this->_oMain->_oTemplate->customDisplayAccessDenied();
					exit();
				} */
			}
		}
	}

	function handleLogin(&$oAlertInfo){
		$iID = (int)$this ->iUserId;
	}

	function handleJoin(&$oAlertInfo){
		$iId = $oAlertInfo->iObject;
		if($this->aSettings['require_mem'] == '1'){
			$this->_oMain->_oDb->setUserStatus($iId,'Suspended');
			bx_login($id,true);
		}

		if($this->aSettings['default_memID'] != '2'){
            setMembership($iId, $this->aSettings['default_memID'], $iMemershipDays, true);
		}

	}
	
	

}
?>
