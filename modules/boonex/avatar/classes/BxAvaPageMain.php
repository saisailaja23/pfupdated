<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

bx_import('BxDolPageView');

class BxAvaPageMain extends BxDolPageView
{
    var $_oMain;
    var $_oTemplate;
    var $_oConfig;
    var $_oDb;

    function BxAvaPageMain(&$oMain)
    {
        $this->_oMain = &$oMain;
        $this->_oTemplate = $oMain->_oTemplate;
        $this->_oConfig = $oMain->_oConfig;
        $this->_oDb = $oMain->_oDb;
        if($_GET['ID']){

           if(!is_Agencyadmin($_COOKIE['memberID']) && !$GLOBALS['logged']['admin'])
            {
               $this -> _oTemplate-> pageStart();
            echo MsgBox("You are not Agency Administrator");
			$this -> _oTemplate -> pageCode("Avatar Administration", false, false);
			exit;
            }

            if(!(@mysql_num_rows(mysql_query("SELECT * FROM Profiles Join bx_groups_main WHERE Profiles.ID=".$_GET['ID']." AND Profiles.AdoptionAgency=bx_groups_main.id AND bx_groups_main.author_id =".$_COOKIE['memberID'] ))) && !$GLOBALS['logged']['admin'])
        {
                 $this -> _oTemplate-> pageStart();
            echo MsgBox("You are not allowed to set avatar for this Profile!");
			$this -> _oTemplate -> pageCode("Avatar Administration", false, false);
			exit;
            }
               $this->_oMain->_iProfileId=$_GET['ID'];
        }
        parent::BxDolPageView('bx_avatar_main');

        $GLOBALS['oTopMenu']->setCurrentProfileID($this->_oMain->_iProfileId);
    }

    function getBlockCode_Tight()
    {
        $aMyAvatars = array ();
        $agencyid =  mysql_fetch_assoc(mysql_query("SELECT * FROM bx_groups_main WHERE  author_id=".$_COOKIE['memberID']));
      
        If ($_GET['ID'] == ''){
        $aVars = array (
            'my_avatars' => $this->_oMain->serviceGetMyAvatars ($this->_oMain->_iProfileId),
             'back' => '<div style="visibility:hidden;"><input type="button" value="Back" onClick="history.go(-1);return true;"></div>',
            'bx_if:is_site_avatars_enabled' => array (
                'condition' => 'on' == getParam('bx_avatar_site_avatars'),
                'content' => array (
                    'site_avatars' => getParam('bx_avatar_site_avatars') ? $this->_oMain->serviceGetSiteAvatars (0) : _t('_Empty'),
                ),
            ),
        ); 
 }else{
            $aVars = array (
            'my_avatars' => $this->_oMain->serviceGetMyAvatars ($this->_oMain->_iProfileId),
                 'back' => '<input type="button" value="Back" onClick="history.go(-1);return true;">',
            'bx_if:is_site_avatars_enabled' => array (
                'condition' => 'on' == getParam('bx_avatar_site_avatars'),
                'content' => array (
                    'site_avatars' => getParam('bx_avatar_site_avatars') ? $this->_oMain->serviceGetSiteAvatars (0) : _t('_Empty'),
                    
                ),
            ),
        );  
        }
        return array($this->_oTemplate->parseHtmlByName('block_tight', $aVars), array(), array(), false);
    }

    function getBlockCode_Wide()
    {
        $sUploadErr = '';

        if (isset($_FILES['image'])) {
            $sUploadErr = $this->_oMain->_uploadImage () ? '' : _t('_bx_ava_upload_error');
            if (!$sUploadErr)
                send_headers_page_changed();
        }

        $aVars = array (
            'avatar' => $GLOBALS['oFunctions']->getMemberThumbnail ($this->_oMain->_iProfileId),
            'bx_if:allow_upload' => array (
                'condition' => $this->_oMain->isAllowedAdd(),
                'content' => array (
                    'action' => $this->_oConfig->getBaseUri(),
            'avatar' => $GLOBALS['oFunctions']->getMemberThumbnail ($this->_oMain->_iProfileId),
                    'upload_error' => $sUploadErr,
                ),
            ),
            'bx_if:allow_crop' => array (
                'condition' => $this->_oMain->isAllowedAdd(),
                'content' => array (
                    'crop_tool' => $this->_oMain->serviceCropTool (array (
                        'dir_image' => BX_AVA_DIR_TMP . $this->_oMain->_iProfileId . BX_AVA_EXT,
                        'url_image' => BX_AVA_URL_TMP . $this->_oMain->_iProfileId . BX_AVA_EXT . '?' . time(),
                    )),
                ),
            ),
            'bx_if:display_premoderation_notice' => array (
                'condition' => getParam('autoApproval_ifProfile') != 'on',
                'content' => array (),
            ),
        );
         if($_GET['ID']){
            $aVars['action']='modules/?r=avatar&ID='.$_GET['ID'];
             $aVars['avatar']=$GLOBALS['oFunctions']->getMemberThumbnail ($_GET['ID']);
              $aVars['bx_ava_current']='bx_ava_current1';
              $aVars['hidespan']='style="display: none;"';
         }
  //  echo "<pre>"; print_r($aVars); echo "</pre>";

        return array($this->_oTemplate->parseHtmlByName('block_wide', $aVars), array(), array(), false);
    }
}
