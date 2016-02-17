<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

bx_import ('BxDolPageView');

/**
 * Base entry view class for modules like events/groups/store
 */
class BxDolTwigPageView extends BxDolPageView
{
    var $_oTemplate;
    var $_oMain;
    var $_oDb;
    var $_oConfig;
    var $aDataEntry;

    function BxDolTwigPageView($sName, &$oMain, &$aDataEntry)
    {
        parent::BxDolPageView($sName);
        $this->_oMain = $oMain;
        $this->_oTemplate = $oMain->_oTemplate;
        $this->_oDb = $oMain->_oDb;
        $this->_oConfig = $oMain->_oConfig;
        $this->aDataEntry = &$aDataEntry;
    }
   /*	
	  //commented by prashanth
    function getBlockCode_SocialSharing()
    {
    	if(!$this->_oMain->isAllowedShare($this->aDataEntry))
    		return '';

        $sUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $this->aDataEntry[$this->_oDb->_sFieldUri];
        $sTitle = $this->aDataEntry[$this->_oDb->_sFieldTitle];

        $aCustomParams = false;
        if ($this->aDataEntry[$this->_oDb->_sFieldThumb]) {
            $a = array('ID' => $this->aDataEntry[$this->_oDb->_sFieldAuthorId], 'Avatar' => $this->aDataEntry[$this->_oDb->_sFieldThumb]);
            $aImage = BxDolService::call('photos', 'get_image', array($a, 'browse'), 'Search');
            $sImgUrl = $aImage['no_image'] ? '' : $aImage['file'];
            if ($sImgUrl) {
                $aCustomParams = array (
                    'img_url' => $sImgUrl,
                    'img_url_encoded' => rawurlencode($sImgUrl),
                );
            }
        }

        bx_import('BxTemplSocialSharing');
        $sCode = BxTemplSocialSharing::getInstance()->getCode($sUrl, $sTitle, $aCustomParams);
        return array($sCode, array(), array(), false);
    }

    function getBlockCode_ForumFeed()
    {
        $sRssId = 'forum|' . $this->_oConfig->getUri() . '|' . rawurlencode($this->aDataEntry[$this->_oDb->_sFieldUri]);
        return '
            <div class="RSSAggrCont" rssid="' . $sRssId . '" rssnum="8" member="' . getLoggedId() . '">
                <div class="loading_rss">
                    <img src="' . getTemplateImage('loading.gif') . '" alt="Loading..." />
                </div>
            </div>';
    }
 */
    function _blockInfo ($aData, $sFields = '', $sLocation = '')
    {
        $aAuthor = getProfileInfo($aData['author_id']);

      /*  $aVars = array (
            'author_unit' => get_member_thumbnail($aAuthor['ID'], 'none', true),
            'date' => getLocaleDate($aData['created'], BX_DOL_LOCALE_DATE_SHORT),
            'date_ago' => defineTimeInterval($aData['created']),
            'cats' => $this->_oTemplate->parseCategories($aData['categories']),
            'tags' => $this->_oTemplate->parseTags($aData['tags']),
            'fields' => $sFields,
            'author_unit' => $GLOBALS['oFunctions']->getMemberThumbnail($aAuthor['ID'], 'none', true),
            'location' => $sLocation,
	  */
          if($aAuthor['City'] && $aAuthor['State']){ $aaddress.= $aAuthor['City'].", ".$aAuthor['State']  ; }
		  if($aAuthor['zip']){ $aaddress.= '  '.$aAuthor['zip']  ; }
          if($aAuthor['Country']){ $country= _t($GLOBALS['aPreValues']['Country'][$aAuthor['Country']]['LKey'])  ; }
		  
	  if(!$aAuthor['WEB_URL'])$aAuthor['WEB_URL']='';
         if($aAuthor['CONTACT_NUMBER'])$phone='Tel: '.format_phone($aAuthor['CONTACT_NUMBER']); else $phone='';
		  if($aAuthor['Fax_Number'])$_fax='Fax: '.format_phone($aAuthor['Fax_Number']);
		 if($aAuthor['Street_Address'])$street=$aAuthor['Street_Address'];
		  if($aAuthor['Facebook'])$facebook='<a href="'.$aAuthor['Facebook'].'"> <div style="background-image:url('.$site['base'].'images/face_book.png); width: 23px; height: 23px;margin-right:5px;float:left;"></div></a>';
		   if($aAuthor['Twitter'])$twitter='<a href="'.$aAuthor['Twitter'].'"> <div style="background-image:url('.$site['base'].'images/twitter.png); width: 23px; height: 23px;margin-right:5px;float:left;"></div></a>';
       
        $aVars = array (
            'author_thumb' => get_member_thumbnail($aAuthor['ID'], 'none'),
            'agencyname'=>$aData['title'],
            'fields' => $sFields,
            'email'=>$aAuthor['Email'],
            'phone'=> $phone,
			'fax'=>$_fax,
			'street'=>$street,
            'address'=> $aaddress,
            'country'=> $country,
            'author_username' => $aAuthor['NickName'],
            'weburl' => '<a href="http://'.$aAuthor['WEB_URL'].'">'.$aAuthor['WEB_URL'].'</a>',
	    'facebook'=>$facebook,
	    'twitter'=>$twitter,
            'author_url' => $aAuthor ? getProfileLink($aAuthor['ID']) : 'javascript:void(0)',
        );
        
       // return $this->_oTemplate->parseHtmlByName('entry_view_block_info', $aVars);
        return $this->_oTemplate->parseHtmlByName('entry_view_block_infoagency', $aVars);
    }
		   
	  function _blockDail ($aData) {
         GLOBAL $site;
          GLOBAL $dir;
           $aAuthor = getProfileInfo($aData['author_id']);
          $contactnumber = $aAuthor['CONTACT_NUMBER'];
          $clicktocall = $aAuthor['CLICK_TO_CALL'];
          $agencyname = $aAuthor['NickName'];

              defineMembershipActions( array('Click to Call') );
		$aCheck = checkAction((int)$_COOKIE['memberID'], BX_CLICK_TO_CALL, $isPerformAction);
        if(!$aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED)
			return false;



         $aVars = '<div style="padding: 10px 0px 10px 20px;" class="bx_sys_block_info">
                  <script type="text/javascript" src="plugins/jquery/jquery.autotab.js"></script>

                      <script language="javascript" type="text/javascript">

                                    var request = false;
                                    try {
                                      request = new XMLHttpRequest();
                                    } catch (trymicrosoft) {
                                      try {
                                        request = new ActiveXObject("Msxml2.XMLHTTP");
                                      } catch (othermicrosoft) {
                                        try {
                                          request = new ActiveXObject("Microsoft.XMLHTTP");
                                        } catch (failed) {
                                          request = false;
                                        }
                                      }
                                    }


// *Function that updates the status in the box for the call. This function is called as a result of a state change after making the AJAX request.
            function update_status_message(){

                if (request.readyState < 4) {

		    document.getElementById("submitbtn").src="'.$site['base'].'/images/callMe_2.png";

                } else if (request.readyState==4) {
		    alert(request.responseText);
                    if(request.responseText == "Call Connected"){
                      document.getElementById("submitbtn").src="'.$site['base'].'/images/callMe_3.png";
                          }
                     else {
                      document.getElementById("submitbtn").src="'.$site['base'].'/images/callMe_1.png";
                          }
                       
                }
            }

// *Function called by clicking on the "Click to Call" button in the form.
// *This function combines the three fields in the form into a 10 digit phone number field and if it is of a valid form, then call the proxy module
// *to perform the functions.

            function request_call_local(){


              if (!request) {
                    Alert ("sorry, click to call will not work with your browser");
                }
                else
                {
                                         var phonetocall ="'.$clicktocall.'";
                                         var contactnumber = "'.$contactnumber.'";
                                         var agencyname ="'.$agencyname.'";
                                         var first= document.getElementById("textfield").value;
                                         var second= document.getElementById("textfield2").value;
                                         var third= document.getElementById("textfield3").value;
                                         var enterednumber=first+second+third;
                                         var id = enterednumber;
                          
                    if (id.length == 10)
                      {
                        // insert your click to xyz building block ID where indicated in the next line or you will receive invalid account responses.
                        // get the click to xyz building block id from the Tools menu
                         var url = "'.$site['url'].'clickto_proxy.php?app=ctc&id="+phonetocall+"&phone_to_call="+id+"&type=1&key=65687e8b8f39137da01defed01b2502c3faf3292&first_callerid="+8132774272+"&second_callerid="+8132774272+"&ref="+agencyname+"&page=ProfilePage";
                        request.onreadystatechange = update_status_message;
                        request.open("GET", url, true);
                        request.send(null);
                     
                     }
                     else{
                     alert("Sorry, the phone number you entered does not have 10 digits! ");
                     }
                 }
                                                 
                                          }

                                          
                                         </script>
                                <table width="230" height="127" border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td align="left" valign="top" background="'.$site['base'].'/images/widget_bg.png"><table width="211" border="0" cellpadding="0" cellspacing="0">
                                  <tr>
                                    <td height="40" colspan="9" align="left" valign="top">&nbsp;</td>

                                  </tr>
                                  <tr>
                                    <td height="40" colspan="9" align="left" valign="top">&nbsp;</td>

                                  </tr>
                                  <tr>
                                    <td width="15" height="40" align="left" valign="top">&nbsp;</td>
                                    <td width="15" align="center" valign="middle" style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#556c7b;">(</td>
                                    <td width="34" align="center" valign="middle"><label>
                                      <input type="text"  name="textfield" maxlength="3" id="textfield" style="width:25px;" /></label>
                                    </td>
                                    <td width="15" align="center" valign="middle" style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#556c7b;">)</td>

                                    <td width="25" align="left" valign="middle">
                                    <input type="text"  maxlength="3" name="textfield2" id="textfield2" style="width:25px;" /></td>
                                    <td width="15" align="center" valign="middle" style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#556c7b;">-</td>
                                    <td width="25" align="left" valign="middle">
                                    <input type="text"  maxlength="4" name="textfield3" id="textfield3" style="width:55px;" /></td>
                                    <td width="15" height="30" align="left" valign="top">&nbsp;</td>
                                    <td width="25" align="left" valign="middle">
                                    <a href="javascript:request_call_local()">
                                    <img id="submitbtn" class="form-submit" value="Submit" alt="Call Me" src="'.$site['base'].'/images/callMe_1.png" width="33" height="33" /></a>
                                    </td>

                                    <td width="15" height="40" align="left" valign="top">&nbsp;</td>
                                  </tr>
                                </table></td>

                              </tr>
                            </table>

<script type="text/javascript" >

                                 $("#textfield").autotab({ target: "textfield2", format: "numeric" });
                                 $("#textfield2").autotab({ target: "textfield3", format: "numeric", previous: "textfield" });
                                 $("#textfield3").autotab({ previous: "textfield2", format: "numeric" });
</script>
</div>
';
       return  $aVars;
    }


    function _blockPhoto (&$aReadyMedia, $iAuthorId, $sPrefix = false)
    {
        if (!$aReadyMedia)
            return '';

        $aImages = array ();

        foreach ($aReadyMedia as $iMediaId) {

            $a = array ('ID' => $iAuthorId, 'Avatar' => $iMediaId);

            $aImageFile = BxDolService::call('photos', 'get_image', array($a, 'file'), 'Search');
            if ($aImageFile['no_image'])
                continue;

            $aImageIcon = BxDolService::call('photos', 'get_image', array($a, 'icon'), 'Search');
            if ($aImageIcon['no_image'])
                continue;

            $aImages[] = array (
                'icon_url' => $aImageIcon['file'],
                'image_url' => $aImageFile['file'],
                'title' => $aImageIcon['title'],
            );
        }

        if (!$aImages)
            return '';

        return $GLOBALS['oFunctions']->genGalleryImages($aImages);
    }

    function _blockVideo ($aReadyMedia, $iAuthorId, $sPrefix = false)
    {
        if (!$aReadyMedia)
            return '';

        $aVars = array (
            'title' => false,
            'prefix' => $sPrefix ? $sPrefix : 'id'.time().'_'.rand(1, 999999),
            'default_height' => getSettingValue('video', 'player_height'),
            'bx_repeat:videos' => array (),
            'bx_repeat:icons' => array (),
        );

        foreach ($aReadyMedia as $iMediaId) {

            $a = BxDolService::call('videos', 'get_video_array', array($iMediaId), 'Search');
            $a['ID'] = $iMediaId;

            $aVars['bx_repeat:videos'][] = array (
                'style' => false === $aVars['title'] ? '' : 'display:none;',
                'id' => $iMediaId,
                'video' => BxDolService::call('videos', 'get_video_concept', array($a), 'Search'),
            );
            $aVars['bx_repeat:icons'][] = array (
                'id' => $iMediaId,
                'icon_url' => $a['file'],
                'title' => $a['title'],
            );
            if (false === $aVars['title'])
                $aVars['title'] = $a['title'];
        }

        if (!$aVars['bx_repeat:icons'])
            return '';

        return $this->_oTemplate->parseHtmlByName('entry_view_block_videos', $aVars);
    }

    function _blockFiles ($aReadyMedia, $iAuthorId = 0)
    {
        if (!$aReadyMedia)
            return '';

        $aVars = array (
            'bx_repeat:files' => array (),
        );

        foreach ($aReadyMedia as $iMediaId) {

            $a = BxDolService::call('files', 'get_file_array', array($iMediaId), 'Search');
            if (!$a['date'])
                continue;

            bx_import('BxTemplFormView');
            $oForm = new BxTemplFormView(array());

            $aInputBtnDownload = array (
                'type' => 'submit',
                'name' => 'download',
                'value' => _t ('_download'),
                'attrs' => array(
                    'class' => 'bx-btn-small',
                    'onclick' => "window.open ('" . BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . "download/".$this->aDataEntry[$this->_oDb->_sFieldId]."/{$iMediaId}','_self');",
                ),
            );

            $aVars['bx_repeat:files'][] = array (
                'id' => $iMediaId,
                'title' => $a['title'],
                'icon' => $a['file'],
                'date' => defineTimeInterval($a['date']),
                'btn_download' => $oForm->genInputButton ($aInputBtnDownload),
            );
        }

        if (!$aVars['bx_repeat:files'])
            return '';

        return $this->_oTemplate->parseHtmlByName('entry_view_block_files', $aVars);
    }

    function _blockSound ($aReadyMedia, $iAuthorId, $sPrefix = false)
    {
        if (!$aReadyMedia)
            return '';

        $aVars = array (
            'title' => false,
            'prefix' => $sPrefix ? $sPrefix : 'id'.time().'_'.rand(1, 999999),
            'default_height' => 350,
            'bx_repeat:sounds' => array (),
            'bx_repeat:icons' => array (),
        );

        foreach ($aReadyMedia as $iMediaId) {

            $a = BxDolService::call('sounds', 'get_music_array', array($iMediaId, 'browse'), 'Search');
            $a['ID'] = $iMediaId;

            $aVars['bx_repeat:sounds'][] = array (
                'style' => false === $aVars['title'] ? '' : 'display:none;',
                'id' => $iMediaId,
                'sound' => BxDolService::call('sounds', 'get_sound_concept', array($a), 'Search'),
            );
            $aVars['bx_repeat:icons'][] = array (
                'id' => $iMediaId,
                'icon_url' => $a['file'],
                'title' => $a['title'],
            );
            if (false === $aVars['title'])
                $aVars['title'] = $a['title'];
        }

        if (!$aVars['bx_repeat:icons'])
            return '';

        return $this->_oTemplate->parseHtmlByName('entry_view_block_sounds', $aVars);
    }

    function _blockFans($iPerPage, $sFuncIsAllowed = 'isAllowedViewFans', $sFuncGetFans = 'getFans')
    {
        if (!$this->_oMain->$sFuncIsAllowed($this->aDataEntry))
            return '';

        $iPage = (int)$_GET['page'];
        if( $iPage < 1)
            $iPage = 1;
        $iStart = ($iPage - 1) * $iPerPage;

        $aProfiles = array ();
        $iNum = $this->_oDb->$sFuncGetFans($aProfiles, $this->aDataEntry[$this->_oDb->_sFieldId], true, $iStart, $iPerPage);
        if (!$iNum || !$aProfiles)
            return MsgBox(_t("_Empty"));
        $iPages = ceil($iNum / $iPerPage);

        bx_import('BxTemplSearchProfile');
        $oBxTemplSearchProfile = new BxTemplSearchProfile();
        $sMainContent = '';
        foreach ($aProfiles as $aProfile) {
        $sMainContent .= $oBxTemplSearchProfile->displaySearchUnit($aProfile);
        }
        $ret .= $GLOBALS['oFunctions']->centerContent($sMainContent, '.searchrow_block_simple');

        $aDBBottomMenu = array();
        if ($iPages > 1) {
            $sUrlStart = BX_DOL_URL_ROOT . $this->_oMain->_oConfig->getBaseUri() . "view/".$this->aDataEntry[$this->_oDb->_sFieldUri];
            $sUrlStart .= (false === strpos($sUrlStart, '?') ? '?' : '&');            
            if ($iPage > 1)
                $aDBBottomMenu[_t('_Back')] = array('href' => $sUrlStart . "page=" . ($iPage - 1), 'dynamic' => true, 'class' => 'backMembers', 'icon' => getTemplateIcon('sys_back.png'), 'icon_class' => 'left', 'static' => false);
            if ($iPage < $iPages) {                                
                $aDBBottomMenu[_t('_Next')] = array('href' => $sUrlStart . "page=" . ($iPage + 1), 'dynamic' => true, 'class' => 'moreMembers', 'icon' => getTemplateIcon('sys_next.png'), 'static' => false);
            }
        }
        //$aDBBottomMenu[_t('_View All')] = array('href' => BX_DOL_URL_ROOT . $this->_oMain->_oConfig->getBaseUri() . "fans/".$this->aDataEntry['uri'], 'class' => 'view_all', 'static' => true);

        $ret .= '<div class="clear_both"></div>';

        return array($ret, array(), $aDBBottomMenu);
    }

    function _blockFansUnconfirmed($iFansLimit = 1000)
    {
        if (!$this->_oMain->isEntryAdmin($this->aDataEntry))
            return '';

        $aProfiles = array ();
        $iNum = $this->_oDb->getFans($aProfiles, $this->aDataEntry[$this->_oDb->_sFieldId], false, 0, $iFansLimit);
        if (!$iNum)
            return MsgBox(_t('_Empty'));

        $sActionsUrl = BX_DOL_URL_ROOT . $this->_oMain->_oConfig->getBaseUri() . "view/" . $this->aDataEntry[$this->_oDb->_sFieldUri] . '?ajax_action=';
        $aButtons = array (
            array (
                'type' => 'submit',
                'name' => 'fans_reject',
                'value' => _t('_sys_btn_fans_reject'),
                'onclick' => "onclick=\"getHtmlData('sys_manage_items_unconfirmed_fans_content', '{$sActionsUrl}reject&ids=' + sys_manage_items_get_unconfirmed_fans_ids(), false, 'post'); return false;\"",
            ),
            array (
                'type' => 'submit',
                'name' => 'fans_confirm',
                'value' => _t('_sys_btn_fans_confirm'),
                'onclick' => "onclick=\"getHtmlData('sys_manage_items_unconfirmed_fans_content', '{$sActionsUrl}confirm&ids=' + sys_manage_items_get_unconfirmed_fans_ids(), false, 'post'); return false;\"",
            ),
        );
        bx_import ('BxTemplSearchResult');
        $sControl = BxTemplSearchResult::showAdminActionsPanel('sys_manage_items_unconfirmed_fans', $aButtons, 'sys_fan_unit');
        $aVars = array(
            'suffix' => 'unconfirmed_fans',
            'content' => $this->_oMain->_profilesEdit($aProfiles),
            'control' => $sControl,
        );
        return $this->_oMain->_oTemplate->parseHtmlByName('manage_items_form', $aVars);
    }
}
