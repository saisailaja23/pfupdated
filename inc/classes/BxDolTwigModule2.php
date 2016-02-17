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

bx_import ('BxDolModule');
bx_import('BxDolPageView');
/*
 * Module Controller
 */
class BxDolTwigModule extends BxDolModule {

    var $_iProfileId;
    var $_sPrefix;
    var $_sFilterName;

    function BxDolTwigModule(&$aModule) {
        parent::BxDolModule($aModule);
        $this->_iProfileId = $GLOBALS['logged']['member'] || $GLOBALS['logged']['admin'] ? $_COOKIE['memberID'] : 0;
    }

    function _actionHome ($sTitle) {
        $this->_oTemplate->pageStart();
        bx_import ('PageMain', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'PageMain';
        $oPage = new $sClass ($this);
        echo $oPage->getCode();
        $this->_oTemplate->addCss ('main.css');
        $this->_oTemplate->addCss ('unit.css');
        $this->_oTemplate->pageCode($sTitle, false, false);
    }

    function _actionFiles ($sUri, $sTitle) {

        if (!($aDataEntry = $this->_preProductTabs($sUri, $sTitle)))
            return;

        $aRestriction = array (
            'id' => $this->_oDb->getMediaIds($aDataEntry[$this->_oDb->_sFieldId], 'files'),
        );
        if (!$aRestriction['id']) {
            $this->_oTemplate->displayNoData ();
            return;
        }       

        $this->_oTemplate->pageStart();

        echo BxDolService::call ('files', 'get_browse_block', array($aRestriction, $this->_oConfig->getBaseUri() . 'files/' . $sUri), 'Search');

        $this->_oTemplate->pageCode($sTitle);
    }

    function _actionVideos ($sUri, $sTitle) {

        if (!($aDataEntry = $this->_preProductTabs($sUri, $sTitle)))
            return;

        $aRestriction = array (
            'id' => $this->_oDb->getMediaIds($aDataEntry[$this->_oDb->_sFieldId], 'videos'),
        );
        if (!$aRestriction['id']) {
            $this->_oTemplate->displayNoData ();
            return;
        }        

        $this->_oTemplate->pageStart();

        echo BxDolService::call ('videos', 'get_browse_block', array($aRestriction, $this->_oConfig->getBaseUri() . 'videos/' . $sUri), 'Search');

        $this->_oTemplate->pageCode($sTitle);
    }

    function _actionSounds ($sUri, $sTitle) {

        if (!($aDataEntry = $this->_preProductTabs($sUri, $sTitle)))
            return;

        $aRestriction = array (
            'id' => $this->_oDb->getMediaIds($aDataEntry[$this->_oDb->_sFieldId], 'sounds'),
        );
        if (!$aRestriction['id']) {
            $this->_oTemplate->displayNoData ();
            return;
        }        

        $this->_oTemplate->pageStart();

        echo BxDolService::call ('sounds', 'get_browse_block', array($aRestriction, $this->_oConfig->getBaseUri() . 'sounds/' . $sUri), 'Search');

        $this->_oTemplate->pageCode($sTitle);
    }

    function _actionPhotos ($sUri, $sTitle) {

        if (!($aDataEntry = $this->_preProductTabs($sUri, $sTitle)))
            return;

        $aRestriction = array (
            'id' => $this->_oDb->getMediaIds($aDataEntry[$this->_oDb->_sFieldId], 'images'),
        );
        if (!$aRestriction['id']) {
            $this->_oTemplate->displayNoData ();
            return;
        }        

        $this->_oTemplate->pageStart();

        echo BxDolService::call ('photos', 'get_browse_block', array($aRestriction, $this->_oConfig->getBaseUri() . 'photos/' . $sUri), 'Search');

        $this->_oTemplate->pageCode($sTitle);
    }

    function _actionComments ($sUri, $sTitle) {

        if (!($aDataEntry = $this->_preProductTabs($sUri, $sTitle)))
            return;

        bx_import('Cmts', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'Cmts';
        $o = new $sClass ($this->_sPrefix, (int)$aDataEntry[$this->_oDb->_sFieldId]);
        if (!$o->isEnabled()) {
            $this->_oTemplate->displayPageNotFound ();
            return;
        }

        $sRet = $o->getCommentsFirst ();

        $this->_oTemplate->pageStart();

        echo DesignBoxContent ($sTitle, $sRet, 1);

        $this->_oTemplate->pageCode($sTitle, 0, 0);
    }

function generate_paginationUrl($OrderBy){
      if(isset($OrderBy)){
          return "OrderBy={$OrderBy}&";
      }
  }


function _actionBrowseTemplateList($sUri, $sFuncAllowed, $sFuncDbGetFans, $iPerPage, $sUrlBrowse, $sTitle) {
        GLOBAL $site;

        if (!($aDataEntry = $this->_preProductTabs($sUri, $sTitle))) {
            return;
        }
        if (!$this->$sFuncAllowed($aDataEntry)) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }
        
       $iPerPage= '15';
       $iPage = (int)$_GET['page'];
        if( $iPage < 1)
            $iPage = 1;
        $iStart = ($iPage - 1) * $iPerPage;

        $aProfiles = array ();
 $agency = db_arr("SELECT id FROM bx_groups_main WHERE uri='".$sUri."' AND author_id=".$_COOKIE['memberID']);
  $agencyid = $agency['id'];

        $tcount = db_arr("SELECT  ptm.template_id,
  ptm.template_name,
  count(*) as itemcount,
  ptm.template_description,
  ptm.template_type
  FROM `pdf_template_master` ptm
  WHERE ptm.isDeleted ='N'
  AND ptm.template_disbale_status = 'N'
  AND (ptm.template_type = 'agency' AND
  (SELECT count(*) as temcount FROM pdf_template_agency pta WHERE pta.template_id = ptm.template_id AND pta.agency_id ='$agencyid')
    OR ptm.template_type = 'global')");
        $iNum = $tcount['itemcount'];
//echo $iNum;
//exit();
       
        if (!$iNum) {
            $this->_oTemplate->displayNoData ();
            return;
        }
        $iPages = ceil($iNum / $iPerPage);
       $sUrlBrowse = "browse_template_list/";

        bx_import('BxDolPaginate');
        $sUrlStart = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . $sUrlBrowse . $aDataEntry[$this->_oDb->_sFieldUri];

        $sUrlStart .= (false === strpos($sUrlStart, '?') ? '?' : '&');



        $oPaginate = new BxDolPaginate(array(
            'page_url' => $sUrlStart .$this->generate_paginationUrl($_GET['OrderBy']). 'page={page}&per_page={per_page}' . (false !== bx_get($this->sFilterName) ? '&' . $this->sFilterName . '=' . bx_get($this->sFilterName) : ''),
            'count' => $iNum,
            'per_page' => $iPerPage,
            'page' => $iPage,
            'per_page_changer' => false,
            'page_reloader' => true,
            'on_change_page' => '',
            'on_change_per_page' => "document.location='" . $sUrlStart . "page=1&per_page=' + this.value + '" . (false !== bx_get($this->sFilterName) ? '&' . $this->sFilterName . '=' . bx_get($this->sFilterName) ."';": "';"),
        ));

 		session_start();
              $_SESSION['$order']=$_GET['OrderBy'];


          $aResult = array(

    	'actions_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . $sUrlBrowse . $aDataEntry[$this->_oDb->_sFieldUri],
        'action_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . $sUrlBrowse . $aDataEntry[$this->_oDb->_sFieldUri],
        'ctl_type' => $sDefaultCtl,
        'orderby' =>  ' <script type="text/javascript"> function showSelected(val){
                               if(val)
                                  window.location="'.$site['url']."m/groups/browse_fans_list/".$aDataEntry[$this->_oDb->_sFieldUri].'?OrderBy="+val;
                               else
                                  window.location="'.$site['url']."m/groups/browse_fans_list/".$aDataEntry[$this->_oDb->_sFieldUri].'";

                           }
                                </script>
                       ',
        'view_type' => $sDefaultView,

              'order_by' => 'OrderBySel',

       //'excelsheet' =>'<p style="float:right; margin-right:10px;"><a href ='.$site['url'].'Agencymemberlist/Agencymemberlist.php><b>Export to Excel</b></a></p>',
        'per_page' => $oPaginate->getPages(),
        'control' => $sControls,
        'loading' => LoadingBox('adm-mp-members-loading')
    );

    
  $agency = db_arr("SELECT id FROM bx_groups_main WHERE uri='".$sUri."' AND author_id=".$_COOKIE['memberID']);
  $agencyid = $agency['id'];

 $sqlQuery = "SELECT  ptm.template_id,
  ptm.template_name,
  ptm.template_description,
  ptm.template_type
  FROM `pdf_template_master` ptm
  WHERE ptm.isDeleted ='N'
  AND ptm.template_disbale_status = 'N'
  AND (ptm.template_type = 'agency' AND
  (SELECT count(*) FROM pdf_template_agency pta WHERE pta.template_id = ptm.template_id AND pta.agency_id ='$agencyid')
    OR ptm.template_type = 'global')";

//$sqlQuery = "SELECT `template_id`,`template_name`,`template_description`,`template_type` FROM `pdf_template_master`";
$templates = $this->_oDb->getAll($sqlQuery);



$sRet .='<form id ="adm-mp-members-form" target="" class=""  method = "post" enctype = "multipart/form-data" >';
       $iEmailLength = 20;
         $aItems = array();
         foreach($templates as $template){

        $aItems[] = array(
            'id' => $template['template_id'],
            'tname' => $template['template_name'],
            'tdes' => $template['template_description'],
            'type' => $template['template_type'],
	    'preview'=>'<a href=#>Preview</a>',
         
            );
  

    }
        

        $sRet .= $GLOBALS['oSysTemplate']->parseHtmlByName('members_geekyold.html', array('bx_repeat:items' => array_values($aItems)));    
        $sRet .= $oPaginate->getPaginate();
        $this->_oTemplate->pageStart();
        echo DesignBoxContent ($sTitle, $sRet, 1);
        $this->_oTemplate->pageCode($sTitle, false, false);
    }





  function _actionBrowseFansList ($sUri, $sFuncAllowed, $sFuncDbGetFans, $iPerPage, $sUrlBrowse, $sTitle) {
       GLOBAL $site;
   
        if (!($aDataEntry = $this->_preProductTabs($sUri, $sTitle))) {
            return;
        }
        if (!$this->$sFuncAllowed($aDataEntry)) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }        
    $iPerPage= '5000';
       $iPage = (int)$_GET['page'];
        if( $iPage < 1)
            $iPage = 1;
        $iStart = ($iPage - 1) * $iPerPage;

        $aProfiles = array ();
        $iNum = $this->_oDb->$sFuncDbGetFans($aProfiles, $aDataEntry[$this->_oDb->_sFieldId], $iStart, $iPerPage);
        if (!$iNum || !$aProfiles) {
            $this->_oTemplate->displayNoData ();
            return;
        }
        $iPages = ceil($iNum / $iPerPage);

        bx_import('BxDolPaginate');
        $sUrlStart = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . $sUrlBrowse . $aDataEntry[$this->_oDb->_sFieldUri];
        $sUrlStart .= (false === strpos($sUrlStart, '?') ? '?' : '&');



        $oPaginate = new BxDolPaginate(array(
            'page_url' => $sUrlStart .$this->generate_paginationUrl($_GET['OrderBy']). 'page={page}&per_page={per_page}' . (false !== bx_get($this->sFilterName) ? '&' . $this->sFilterName . '=' . bx_get($this->sFilterName) : ''),
            'count' => $iNum,
            'per_page' => $iPerPage,
            'page' => $iPage,
            'per_page_changer' => false,
            'page_reloader' => true,
            'on_change_page' => '',
            'on_change_per_page' => "document.location='" . $sUrlStart . "page=1&per_page=' + this.value + '" . (false !== bx_get($this->sFilterName) ? '&' . $this->sFilterName . '=' . bx_get($this->sFilterName) ."';": "';"),
        ));

 		session_start();
              $_SESSION['$order']=$_GET['OrderBy'];


          $aResult = array(

    	'actions_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . $sUrlBrowse . $aDataEntry[$this->_oDb->_sFieldUri],
        'action_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . $sUrlBrowse . $aDataEntry[$this->_oDb->_sFieldUri],
        'ctl_type' => $sDefaultCtl,
        'orderby' =>  ' <script type="text/javascript"> function showSelected(val){
                               if(val)
                                  window.location="'.$site['url']."m/groups/browse_fans_list/".$aDataEntry[$this->_oDb->_sFieldUri].'?OrderBy="+val;
                               else
                                  window.location="'.$site['url']."m/groups/browse_fans_list/".$aDataEntry[$this->_oDb->_sFieldUri].'";
                            
                           }
                                </script>
                       ',
        'view_type' => $sDefaultView,
             
              'order_by' => 'OrderBySel',
        
       //'excelsheet' =>'<p style="float:right; margin-right:10px;"><a href ='.$site['url'].'Agencymemberlist/Agencymemberlist.php><b>Export to Excel</b></a></p>',
        'per_page' => $oPaginate->getPages(),
        'control' => $sControls,
        'loading' => LoadingBox('adm-mp-members-loading')
    );
   /*  if(isset($_GET['OrderBy'])){
        if($_GET['OrderBy']=='NickName')$aResult['NickNameselected']='selected';else $aResult['NickNameselected']='';
        if($_GET['OrderBy']=='DateReg')$aResult['DateRegselected']='selected';else$aResult['DateRegselected']='';
         if($_GET['OrderBy']=='DateLastNav')$aResult['DateLastNavselected']='selected';else$aResult['DateLastNavselected']='';
    }
*/

 if(isset($_GET['OrderBy'])){
        if($_GET['OrderBy']=='NickName')$aResult['NickNameselected']='selected';else $aResult['NickNameselected']='';
        if($_GET['OrderBy']=='DateReg')$aResult['DateRegselected']='selected';else$aResult['DateRegselected']='';
        if($_GET['OrderBy']=='DateLastNav')$aResult['DateLastNavselected']='selected';else$aResult['DateLastNavselected']='';
        if($_GET['OrderBy']=='2')$aResult['DateLastNavselectedd']='selected';else$aResult['DateLastNavselectedd']='';
         if($_GET['OrderBy']=='4')$aResult['DateLastNavselecteddd']='selected';else$aResult['DateLastNavselecteddd']='';

    }

    $sRet .=  $GLOBALS['oSysTemplate']->parseHtmlByName('orderbyperpage.html', $aResult) ;



$sRet .='<form id ="adm-mp-members-form" target="" class=""  method = "post" enctype = "multipart/form-data" >';
       $iEmailLength = 20;
         $aItems = array();
         foreach($aProfiles as $aProfile){
          
           $pid = $aProfile['ID'];
           //$pid = $aProfile['NickName'];
 
            $sEmail = ( mb_strlen($aProfile['email']) > $iEmailLength ) ? mb_substr($aProfile['email'], 0, $iEmailLength) . '...' : $aProfile['email'];
	     $profiletype = db_arr("SELECT `Name` as profilename FROM `aqb_pts_profile_types` WHERE ID =".$aProfile['ProfileType']);
           // $dateofupgrades = db_arr("SELECT MAX(`DateStarts`) as dateofupgrade FROM `sys_acl_levels_members` WHERE IDMember =".$aProfile['ID']);

           $userlevel = db_arr("SELECT sys_acl_levels_members.*, DATEDIFF(DateExpires, DateStarts) AS days FROM sys_acl_levels_members  where
                             sys_acl_levels_members.IDMember = '{$pid}' ORDER BY `sys_acl_levels_members`.`DateStarts` Desc");

           //$day = $userlevel['days'];

         //  $dateofupgrades = db_arr("SELECT `Upgrade` as dateofupgrade FROM sys_acl_levels_members WHERE DATEDIFF(DateExpires, DateStarts ) >=365 AND IDMember ='$pid' ORDER BY `sys_acl_levels_members`.`Upgrade` DESC");

        /*
           switch ($aProfile['ml_name'])
             {
                 case "Adoptive Family - Advanced":
                     $memname = $aProfile['ml_name']." ". $day. " days";
                     break;
                 case "Adoption Agency - GOLD":
                     $memname = "Adoption Agency - GOLD";
                     break;
                 case "Birth Mother - GOLD":
                     $memname = "Birth Mother - GOLD";
                     break;
                 case "Adoptive Family - Standard":
                     $memname = "Adoptive Family - Standard";
                     break;
                 default:
                     $memname = "";
                     break;

             }
                   */

	     $glbSearch_img  = ($aProfile['globalval'] == 'Yes')?$GLOBALS['site']['url']."templates/base/images/icons/grey_ic_on.png":$GLOBALS['site']['url']."templates/base/images/icons/grey_ic_off.png";
	     switch ($profiletype['profilename'])
             {
                 case "Adoptive Family":
                     $prf_type = "AF";
                     break;
                 case "Birth Mother":
                     $prf_type = "BM";
                     break;
                 case "Adoption Agency":
                     $prf_type = "AA";
                     break;
                 default:
                     $prf_type = "";
                     break;

             }
             $glbSearch_alt  = ($aProfile['globalval'] == 'Yes')?"Enabled Global Search":"Disabled Global Search";
              $title  = ($aProfile['globalval'] == 'Yes')?"en":"Dis";

  			mysql_query("CREATE TABLE IF NOT EXISTS `watermarkimages`(
                      `id` int(10) unsigned NOT NULL auto_increment,
                      `author_id` int(10) unsigned NOT NULL default '0',
                      `watermarkimage_id` int(10) unsigned NOT NULL default '0',
                      `status` varchar(20) default NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");// <img src= "'.$GLOBALS['site']['url'].'templates/base/images/icons/bx_people.png" width="20" height="20" title="Matched" />           

            $watermark = db_arr("Select `status` from `watermarkimages` where `author_id`=".$aProfile['ID']);
            $cfname= db_arr("Select `FirstName` from `Profiles` where `ID`=".$aProfile['Couple']);
            if($cfname['FirstName']  != '') {

                $cfname['FirstName'] = '& '.$cfname['FirstName'];

            }

            if($watermark['status'] == 'Matched')
            {
                       
             $matched = '<img  class="tipz" src= "'.$GLOBALS['site']['url'].'templates/base/images/icons/fr_lst.gif" width="18" height="18" onClick="openbox(\''.$aProfile['NickName'].'\', 1)"  alt="Matched" id="Matched"/>';

            }
            else if($watermark['status'] == 'Placed') {//here we took profile name since we have another button in the above condition which have name, so to be common.....
                $matched = '<img  class="tipz"   src= "'.$GLOBALS['site']['url'].'templates/base/images/icons/placed_icon.png" width="18" height="18"   alt="Placed"  onClick=\'window.location = "'.$site['url'].'watermark.php?status=notplaced&name='.$aProfile['NickName'].'"\' />';
              }
            else {
                $matched = '<img  class="tipz"  src= "'.$GLOBALS['site']['url'].'templates/base/images/icons/notmatched.png"  alt="Not Matched"  onClick=\'window.location = "'.$site['url'].'watermark.php?status=matched&id='.$aProfile['ID'].'"\' />';
              }
            

        if($watermark['status'] != 'Matched' && $watermark['status'] != 'Placed') {

         $watermark['status'] = "Not Matched";
             }

        if($aProfile['globalval'] == 'Yes') {

          $gstatus = "Disable Global Search";
              }

        else {
           $gstatus = "Enable Global Search";
               }



        $aItems[$aProfile['ID']] = array(
            'id' => $aProfile['ID'],
            'username' => $aProfile['NickName'],
            'email' => $aProfile['Email'],
            'full_email' => $aProfile['Email'],
	    'lastname' => $aProfile['LastName'],
	    'firstname' => $aProfile['FirstName'],
            'status' => $aProfile['Status'],
            'match' => $matched,
            'watermarkstatus' => $watermark['status'],
            'globalstatus' => $gstatus,
            'cname' => $cfname['FirstName'],
            'agency' => $aProfile['AdoptionAgency'],
            'globalvalue' => $aProfile['globalval'],
            'matchrecords' => $aProfile['matchrecords'],
            'maxmatch' => $aProfile['maxmatch'],

	    'profile_icon'=>'<a href='.$GLOBALS['site']['url'] .$aProfile['NickName'].'><img src= "'.$GLOBALS['site']['url'].'templates/base/images/icons/user.png" alt="visit profile"  class="tipz" /> </a>',
            'changepass_icon'=>'<a href='.$GLOBALS['site']['url'] .'changepassword.php?ID='.$aProfile['ID'].' ><img src= "'.$GLOBALS['site']['url'].'templates/base/images/icons/icon_member_pass.gif" alt="Change Password" class="tipz"/> </a>',
            'global_icon'=>'<img src= "'.$glbSearch_img.'"   id= "'.$pid.'"    alt="'.$glbSearch_alt.'"   class="tipz" class="img-swap" onclick="popup(this);"/>',
            'match_icon'=>'<a href="'.$GLOBALS['site']['url'] .'page/matches?parentID='.$aProfile['ID'].'"  /><img src= "'.$GLOBALS['site']['url'].'templates/base/images/icons/match_ic.png" alt="matching information" class="tipz"  /></a>',

            'profiletype' => $prf_type,
           // 'dateofupgrades' => date("m-d-Y", strtotime($dateofupgrades['dateofupgrade'])),
            'dateofupgrades' => !empty($dateofupgrades['dateofupgrade']) ?date("m-d-Y", strtotime($dateofupgrades['dateofupgrade'])) : '',
	    'avatar_icon'=>'<a href='.$GLOBALS['site']['url'] .'modules/?r=avatar&ID='.$aProfile['ID'].' ><img src= "'.$GLOBALS['site']['url'].'templates/base/images/icons/cmt-male.gif" class="tipz" alt="change profile photo" /> </a>',
            'edit_link' => $GLOBALS['site']['url'] . 'pedit.php?ID=' . $aProfile['ID'],
            'edit_class' => ($aProfile['Status'] == 'Active' ? 'blue' : 'grey'),
            'registration' => date("m/d/Y", strtotime($aProfile['DateReg'])),
            'last_login' => date("m/d/Y", strtotime($aProfile['DateLastLogin'])),
 	     'match' => $matched ,
            'last_activity'=> $aProfile['DateLastEdit'],
            'status' => $aProfile['Status'],
            'ml_id' => !empty($aProfile['ml_id']) ? (int)$aProfile['ml_id'] : 2,
            //'ml_name' => !empty($aProfile['ml_name']) ? $aProfile['ml_name'] : 'Standard',
          //  'ml_name' => !empty($aProfile['ml_name']) ? Advanced : 'Basic',

          //   'ml_name' => !empty($memname) ? $memname : 'Standard',
              'ml_name' => !empty($aProfile['ml_name']) ? $aProfile['ml_name'] : 'Standard',


            );
        $_profileDraft=db_arr("SELECT Status FROM Profiles_draft WHERE ID=".$aProfile['ID']);
        if($_profileDraft['Status']=='Approval' && $aProfile['Status']=='Active')  $aItems[$aProfile['ID']]['status']='ActiveApproval' ;
        

    }
        $sRet .='<style>#Approval{color: gray;} #ActiveApproval{color: green;}</style>';
	  $min_matchValue   = ($aProfile['maxmatch'])?$aProfile['maxmatch']:50;
         $min_matchRecords = ($aProfile['matchrecords'])?$aProfile['matchrecords']:50;
         $b_tItems = array();
          $b_tItems[$aProfile['ID']] = array(
            'matchrecords' => $min_matchRecords,
            'maxmatch' => $min_matchValue
            );

        $sRet .= $GLOBALS['oSysTemplate']->parseHtmlByName('members_geeky.html', array('bx_repeat:items' => array_values($aItems),'bx_repeat:aaa' =>array_values($b_tItems)));
        

        $sRet .= '<div class="clear_both"></div>';

        $aButtons = array(
        'adm-mp-activate' => _t('_adm_btn_mp_activate'),
        'adm-mp-deactivate' => _t('_adm_btn_mp_deactivate'),
        'adm-mp-delete' => _t('_adm_btn_mp_delete'),
         );


        $sWrapperId='adm-mp-members-form ';
        $sCheckboxName = 'members';
        $bSelectAll = true;
        $bSelectAllChecked = false;
        $sCustomHtml = '';

     
        $agency =db_arr("SELECT `uri` FROM `bx_groups_main` WHERE `id` =".$aProfile['AdoptionAgency']);

        if(isset($_POST['adm-mp-activate']) && (bool)$_POST['members']) {
            $GLOBALS['MySQL']->query("UPDATE `Profiles_draft` SET `Status`='Active' WHERE `ID` IN ('" . implode("','", $_POST['members']) . "')");
	     $GLOBALS['MySQL']->query("UPDATE Profiles p LEFT JOIN Profiles_draft pp ON p.ID = pp.ID SET 
							p.NickName = pp.NickName , p.Email = pp.Email , p.Password = pp.Password ,
							p.Salt = pp.Salt , p.Status = pp.Status , p.Role = pp.Role , p.Couple = pp.Couple ,
							p.Sex = pp.Sex , p.LookingFor = pp.LookingFor , p.Headline = pp.Headline ,
             						p.DescriptionMe = pp.DescriptionMe , p.Country = pp.Country , p.City = pp.City ,
                                                 p.DateOfBirth = pp.DateOfBirth , p.Featured = pp.Featured , p.DateReg = pp.DateReg ,
							p.DateLastEdit = pp.DateLastEdit , p.DateLastLogin = pp.DateLastLogin ,
							p.DateLastNav = pp.DateLastNav , p.aff_num = pp.aff_num , p.Tags = pp.Tags , 
							p.zip = pp.zip , p.EmailNotify = pp.EmailNotify , p.LangID = pp.LangID ,
           						p.Views = pp.Views , p.UpdateMatch = pp.UpdateMatch , p.Rate = pp.Rate ,
							p.RateCount = pp.RateCount , p.CommentsCount = pp.CommentsCount ,
							p.PrivacyDefaultGroup = pp.PrivacyDefaultGroup , p.allow_view_to = pp.allow_view_to ,
							p.UserStatus = pp.UserStatus , p.UserStatusMessage = pp.UserStatusMessage , 
							p.UserStatusMessageWhen   = pp.UserStatusMessageWhen , 
							p.Height = pp.Height  , p.Weight = pp.Weight  , p.Income = pp.Income  ,
							p.Occupation = pp.Occupation , p.Religion  = pp.Religion  , p.Education = pp.Education  ,
							p.RelationshipStatus = pp.RelationshipStatus  , p.Hobbies = pp.Hobbies, p.Interests = pp.Interests,
							p.Ethnicity = pp.Ethnicity, p.FavoriteSites = pp.FavoriteSites, p.FavoriteMusic = pp.FavoriteMusic,
							p.FavoriteFilms = pp.FavoriteFilms, p.FavoriteBooks = pp.FavoriteBooks, p.FirstName = pp.FirstName,
							p.LastName = pp.LastName, p.gkcBadgeWidgetConfCode = pp.gkcBadgeWidgetConfCode, p.ProfileType = pp.ProfileType,
							p.AdoptionAgency = pp.AdoptionAgency, p.PromoCode = pp.PromoCode, p.Region = pp.Region,
							p.ChildAge = pp.ChildAge, p.FamilyStructure = pp.FamilyStructure, p.FavoriteMarkStuff = pp.FavoriteMarkStuff,
							p.ChildSpecialNeeds = pp.ChildSpecialNeeds, p.ChildGender = pp.ChildGender, p.ChildEthnicity = pp.ChildEthnicity,
							p.Pet = pp.Pet,p.Neighborhood = pp.Neighborhood, p.Residency = pp.Residency, p.State = pp.State,
							p.Facebook = pp.Facebook, p.Twitter = pp.Twitter, p.MySpace = pp.MySpace, p.Smoking = pp.Smoking,
							p.DueDate = pp.DueDate, p.BMPhone = pp.BMPhone,p.BMTimetoReach = pp.BMTimetoReach,
							p.BMChildEthnicity = pp.BMChildEthnicity, p.BMChildDOB = pp.BMChildDOB,p.BMAddress = pp.BMAddress,
							p.YearsMarried = pp.YearsMarried, p.BMChildSex = pp.BMChildSex, p.RpxProfile = pp.RpxProfile,
							p.DearBirthParent = pp.DearBirthParent, p.WEB_URL = pp.WEB_URL, p.CLICK_TO_CALL = pp.CLICK_TO_CALL,
							p.CONTACT_NUMBER = pp.CONTACT_NUMBER , p.Fax_Number = pp.Fax_Number , p.Street_Address = pp.Street_Address ,
 							p.About_our_home = pp.About_our_home , p.Save_Option = pp.Save_Option , p.ChildDesired = pp.ChildDesired, 
							p.BirthFatherStatus = pp.BirthFatherStatus, p.Openness = pp.Openness, p.BPFamilyHistory = pp.BPFamilyHistory, 
							p.SmokingDuringPregnancy = pp.SmokingDuringPregnancy, p.DrugsAlcohol = pp.DrugsAlcohol, p.SpecialNeedsOptions = pp.SpecialNeedsOptions  WHERE pp.ID IN ('" . implode("','", $_POST['members']) . "')");


            $oEmailTemplate = new BxDolEmailTemplates();
            foreach($_POST['members'] as $iId) {
                createUserDataFile((int)$iId);
                reparseObjTags('profile', (int)$iId);

                $aProfile = getProfileInfo($iId);
                        $aMail = $oEmailTemplate->parseTemplate('t_Activation', array(), $iId);
                        //sendMail($aProfile['Email'], $aMail['subject'], $aMail['body']);

                $oAlert = new BxDolAlerts('profile', 'change_status', (int)$iId, 0, array('status' => 'Active'));
                $oAlert->alert();
            }
          header("Location:".$site['url']."m/groups/browse_fans_list/".$agency['uri']);
        }
        else if(isset($_POST['adm-mp-deactivate']) && (bool)$_POST['members']) {

            $GLOBALS['MySQL']->query("UPDATE `Profiles` SET `Status`='Approval' WHERE `ID` IN ('" . implode("','", $_POST['members']) . "')");
             $GLOBALS['MySQL']->query("UPDATE `Profiles_draft` SET `Status`='Approval' WHERE `ID` IN ('" . implode("','", $_POST['members']) . "')");
            foreach($_POST['members'] as $iId) {
                createUserDataFile((int)$iId);
                reparseObjTags('profile', (int)$iId);
                $oAlert = new BxDolAlerts('profile', 'change_status', (int)$iId, 0, array('status' => 'Approval'));
                $oAlert->alert();
            }
            header("Location:".$site['url']."m/groups/browse_fans_list/".$agency['uri']);
        }
        elseif(isset($_POST['adm-mp-delete']) && (bool)$_POST['members']) {

            $GLOBALS['MySQL']->query("UPDATE `Profiles` SET `Status`='Rejected' WHERE `ID` IN ('" . implode("','", $_POST['members']) . "')");
            $GLOBALS['MySQL']->query("UPDATE `Profiles_draft` SET `Status`='Rejected' WHERE `ID` IN ('" . implode("','", $_POST['members']) . "')");
            foreach($_POST['members'] as $iId) {
                createUserDataFile((int)$iId);
                reparseObjTags('profile', (int)$iId);
                $oAlert = new BxDolAlerts('profile', 'change_status', (int)$iId, 0, array('status' => 'Rejected'));
                $oAlert->alert();
            }
            header("Location:".$site['url']."m/groups/browse_fans_list/".$agency['uri']);

        }
       $aBtns = array();
         foreach ($aButtons as $k => $v) {
            if(is_array($v)) {
                $aBtns[] = $v;
                continue;
            }

            $aBtns[] = array(
                'type' => 'submit',
                'name' => $k,
                'value' => '_' == $v[0] ? _t($v) : $v,
                'onclick' => 'blue.className=grey ',
            );

        }

        $aUnit = array(
            'bx_repeat:buttons' => $aBtns,
            'bx_if:customHTML' => array(
                'condition' => strlen($sCustomHtml) > 0,
                'content' => array(
                    'custom_HTML' => $sCustomHtml,
                )
            ),
            'bx_if:selectAll' => array(
                'condition' => $bSelectAll,
                'content' => array(
                    'wrapperId' => $sWrapperId,
                    'checkboxName' => $sCheckboxName,
                    'checked' => ($bSelectAll && $bSelectAllChecked ? 'checked="checked"' : '')
                )
            ),
        );


        $sRet .= $GLOBALS['oSysTemplate']->parseHtmlByName('adminActionsPanel.html', $aUnit,array('{','}'));

       //$sRet .= $oPaginate->getPaginate();
        $this->_oTemplate->pageStart();

        echo DesignBoxContent ($sTitle, $sRet, 1);

        $this->_oTemplate->pageCode($sTitle, false, false);
    }
    function _actionBrowseFans ($sUri, $sFuncAllowed, $sFuncDbGetFans, $iPerPage, $sUrlBrowse, $sTitle) {

        if (!($aDataEntry = $this->_preProductTabs($sUri, $sTitle))) {
            return;
        }
	 if(!$sTitle=='Participants ')
        if (!$this->$sFuncAllowed($aDataEntry)) {            
            $this->_oTemplate->displayAccessDenied ();
            return;
        }        

        $iPage = (int)$_GET['page'];
        if( $iPage < 1)
            $iPage = 1;
        $iStart = ($iPage - 1) * $iPerPage;

        $aProfiles = array ();
        $iNum = $this->_oDb->$sFuncDbGetFans($aProfiles, $aDataEntry[$this->_oDb->_sFieldId], $iStart, $iPerPage);
        if (!$iNum || !$aProfiles) {
            $this->_oTemplate->displayNoData ();
            return;
        }
        $iPages = ceil($iNum / $iPerPage);

        bx_import('BxTemplSearchProfile');
        $oBxTemplSearchProfile = new BxTemplSearchProfile();
        $sMainContent = '';
        foreach ($aProfiles as $aProfile) {
            $sMainContent .= $oBxTemplSearchProfile->displaySearchUnit($aProfile);
        }
        $sRet  = $GLOBALS['oFunctions']->centerContent($sMainContent, '.searchrow_block_simple');
        $sRet .= '<div class="clear_both"></div>';        

        bx_import('BxDolPaginate');
        $sUrlStart = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . $sUrlBrowse . $aDataEntry[$this->_oDb->_sFieldUri];
        $sUrlStart .= (false === strpos($sUrlStart, '?') ? '?' : '&');

        $oPaginate = new BxDolPaginate(array(
            'page_url' => $sUrlStart . 'page={page}&per_page={per_page}' . (false !== bx_get($this->sFilterName) ? '&' . $this->sFilterName . '=' . bx_get($this->sFilterName) : ''),
            'count' => $iNum,
            'per_page' => $iPerPage,
            'page' => $iPage,
            'per_page_changer' => false,
            'page_reloader' => true,
            'on_change_page' => '',
            'on_change_per_page' => "document.location='" . $sUrlStart . "page=1&per_page=' + this.value + '" . (false !== bx_get($this->sFilterName) ? '&' . $this->sFilterName . '=' . bx_get($this->sFilterName) ."';": "';"),
        ));

        $sRet .= $oPaginate->getPaginate();

        $this->_oTemplate->pageStart();

        echo DesignBoxContent ($sTitle, $sRet, 1);

        $this->_oTemplate->pageCode($sTitle, false, false);
    }

    function _actionView ($sUri, $sMsgPendingApproval) {

        if (!($aDataEntry = $this->_preProductTabs($sUri)))
            return;

        $this->_oTemplate->pageStart();

        bx_import ('PageView', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'PageView';
        $oPage = new $sClass ($this, $aDataEntry);

        if ($aDataEntry[$this->_oDb->_sFieldStatus] == 'pending') {
            $aVars = array ('msg' => $sMsgPendingApproval); // this product is pending approval, please wait until it will be activated
            echo $this->_oTemplate->parseHtmlByName ('pending_approval_plank', $aVars);
        }

        echo $oPage->getCode();

        bx_import('Cmts', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'Cmts';
        $oCmts = new $sClass ($this->_sPrefix, 0);

        $this->_oTemplate->setPageDescription (substr(strip_tags($aDataEntry[$this->_oDb->_sFieldDescription]), 0, 255));
        $this->_oTemplate->addPageKeywords ($aDataEntry[$this->_oDb->_sFieldTags]);

        $this->_oTemplate->addCss ('view.css');
        $this->_oTemplate->addCss ('main.css');
        $this->_oTemplate->addCss ('entry_view.css');
        $this->_oTemplate->addCss ('unit_fan.css');
        $this->_oTemplate->pageCode($aDataEntry[$this->_oDb->_sFieldTitle], false, false);

        bx_import ('BxDolViews');
        new BxDolViews($this->_sPrefix, $aDataEntry[$this->_oDb->_sFieldId]);
    }

    function _actionUploadMedia ($sUri, $sIsAllowedFuncName, $sMedia, $aMediaFields, $sTitle) {

        if (!($aDataEntry = $this->_preProductTabs($sUri, $sTitle)))
            return;

        if (!$this->$sIsAllowedFuncName($aDataEntry)) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }

        $this->_oTemplate->pageStart();

        $iEntryId = $aDataEntry[$this->_oDb->_sFieldId];

        bx_import ('FormUploadMedia', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'FormUploadMedia';
        $oForm = new $sClass ($this, $aDataEntry[$this->_oDb->_sFieldAuthorId], $iEntryId, $aDataEntry, $sMedia, $aMediaFields);
        $oForm->initChecker($aDataEntry);

        if ($oForm->isSubmittedAndValid ()) {

            $oForm->processMedia($iEntryId, $this->_iProfileId);

            $this->$sIsAllowedFuncName($aDataEntry, true); // perform action

            header ('Location:' . BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aDataEntry[$this->_oDb->_sFieldUri]);
            exit;

         } else {

            echo $oForm->getCode ();

        }

        $this->_oTemplate->addCss ('main.css');
        $this->_oTemplate->addCss ('forms_extra.css');            
        $this->_oTemplate->pageCode($sTitle);
    }

    function _actionBroadcast ($iEntryId, $sTitle, $sMsgNoRecipients, $sMsgSent) {

        $iEntryId = (int)$iEntryId;
        if (!($aDataEntry = $this->_oDb->getEntryById($iEntryId))) {
            $this->_oTemplate->displayPageNotFound ();
            return;
        }

        if (!$this->isAllowedBroadcast($aDataEntry)) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }

        $this->_oTemplate->pageStart();

        $GLOBALS['oTopMenu']->setCustomSubHeader($aDataEntry[$this->_oDb->_sFieldTitle]);
        $GLOBALS['oTopMenu']->setCustomVar($this->_sPrefix.'_view_uri', $aDataEntry[$this->_oDb->_sFieldUri]);
        $GLOBALS['oTopMenu']->setCustomBreadcrumbs(array(
            _t('_'.$this->_sPrefix) => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'home/',
            $aDataEntry[$this->_oDb->_sFieldTitle] => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aDataEntry[$this->_oDb->_sFieldUri],
            $sTitle => '',
        ));

        $aRecipients = $this->_oDb->getBroadcastRecipients ($iEntryId);
        if (!$aRecipients) {
            echo MsgBox ($sMsgNoRecipients);
            $this->_oTemplate->pageCode($sMsgNoRecipients, true, true);
            return;
        }

        bx_import ('FormBroadcast', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'FormBroadcast';
        $oForm = new $sClass ();
        $oForm->initChecker();

        if ($oForm->isSubmittedAndValid ()) {
            
            $oEmailTemplate = new BxDolEmailTemplates();
            if (!$oEmailTemplate) {
                $this->_oTemplate->displayErrorOccured();
                return;
            }
            $aTemplate = $oEmailTemplate->getTemplate($this->_sPrefix . '_broadcast'); 
            $aTemplateVars = array (
                'BroadcastTitle' => $oForm->getCleanValue ('title'),
                'BroadcastMessage' => $oForm->getCleanValue ('message'),
                'EntryTitle' => $aDataEntry[$this->_oDb->_sFieldTitle],
                'EntryUrl' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aDataEntry[$this->_oDb->_sFieldUri],                
            );
            $iSentMailsCounter = 0;            
            foreach ($aRecipients as $aProfile) {		        
       	        $iSentMailsCounter += sendMail($aProfile['Email'], $aTemplate['Subject'], $aTemplate['Body'], $aProfile['ID'], $aTemplateVars);
            }
            if (!$iSentMailsCounter) {
                $this->_oTemplate->displayErrorOccured();
                return;
            }

            echo MsgBox ($sMsgSent);

            $this->isAllowedBroadcast($aDataEntry, true); // perform send broadcast message action             
            $this->_oTemplate->addCss ('main.css');
            $this->_oTemplate->pageCode($sMsgSent, true, true);
            return;
        } 

        echo $oForm->getCode ();

        $this->_oTemplate->addCss ('main.css');
        $this->_oTemplate->pageCode($sTitle);
    }

    function _getInviteParams ($aDataEntry, $aInviter) {
        // override this
        return array ();
    }

    function _actionInvite ($iEntryId, $sEmailTemplate, $iMaxEmailInvitations, $sMsgInvitationSent, $sMsgNoUsers, $sTitle) {

        $iEntryId = (int)$iEntryId;
        if (!($aDataEntry = $this->_oDb->getEntryByIdAndOwner($iEntryId, $this->_iProfileId, $this->isAdmin()))) {
            $this->_oTemplate->displayPageNotFound ();
            return;
        }

        $this->_oTemplate->pageStart();

        $GLOBALS['oTopMenu']->setCustomSubHeader($aDataEntry[$this->_oDb->_sFieldTitle]);
        $GLOBALS['oTopMenu']->setCustomVar($this->_sPrefix.'_view_uri', $aDataEntry[$this->_oDb->_sFieldUri]);
        $GLOBALS['oTopMenu']->setCustomBreadcrumbs(array(
            _t('_'.$this->_sPrefix) => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'home/',
            $aDataEntry[$this->_oDb->_sFieldTitle] => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aDataEntry[$this->_oDb->_sFieldUri],
            $sTitle . $aDataEntry[$this->_oDb->_sFieldTitle] => '',
        ));

        bx_import('BxDolTwigFormInviter');
        $oForm = new BxDolTwigFormInviter ($this, $sMsgNoUsers);
        $oForm->initChecker();

        if ($oForm->isSubmittedAndValid ()) {        

            $aInviter = getProfileInfo($this->_iProfileId);
            $aPlusOriginal = $this->_getInviteParams ($aDataEntry, $aInviter);
            
            $oEmailTemplate = new BxDolEmailTemplates();
            $aTemplate = $oEmailTemplate->getTemplate($sEmailTemplate);
            $iSuccess = 0;

            // send invitation to registered members
            if (false !== bx_get('inviter_users') && is_array(bx_get('inviter_users'))) {
				$aInviteUsers = bx_get('inviter_users');
                foreach ($aInviteUsers as $iRecipient) {
                    $aRecipient = getProfileInfo($iRecipient);
                    $aPlus = array_merge (array ('NickName' => ' ' . $aRecipient['NickName']), $aPlusOriginal);
                    $iSuccess += sendMail(trim($aRecipient['Email']), $aTemplate['Subject'], $aTemplate['Body'], '', $aPlus) ? 1 : 0;
                }
            }

            // send invitation to additional emails
            $iMaxCount = $iMaxEmailInvitations;
            $aEmails = preg_split ("#[,\s\\b]+#", bx_get('inviter_emails'));
            $aPlus = array_merge (array ('NickName' => ''), $aPlusOriginal);
            if ($aEmails && is_array($aEmails)) {
                foreach ($aEmails as $sEmail) {
                    if (strlen($sEmail) < 5) 
                        continue;
                    $iRet = sendMail(trim($sEmail), $aTemplate['Subject'], $aTemplate['Body'], '', $aPlus) ? 1 : 0;
                    $iSuccess += $iRet;
                    if ($iRet && 0 == --$iMaxCount) 
                        break;
                }             
            }

            $sMsg = sprintf($sMsgInvitationSent, $iSuccess);
            echo MsgBox($sMsg);
            $this->_oTemplate->addCss ('main.css');
            $this->_oTemplate->pageCode ($sMsg, true, false);
            return;
        } 

        echo $oForm->getCode ();
        $this->_oTemplate->addCss ('main.css');
        $this->_oTemplate->addCss ('inviter.css');
        $this->_oTemplate->pageCode($sTitle . $aDataEntry[$this->_oDb->_sFieldTitle]);
    }

    function _actionCalendar ($iYear, $iMonth, $sTitle) {

        $iYear = (int)$iYear;
        $iMonth = (int)$iMonth;

        if (!$this->isAllowedBrowse()) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }

        $this->_oTemplate->pageStart();

        bx_import ('Calendar', $this->_aModule);
        $oCalendar = bx_instance ($this->_aModule['class_prefix'] . 'Calendar', array ($iYear, $iMonth, $this->_oDb, $this->_oTemplate, $this->_oConfig));

        echo $oCalendar->display();
        $this->_oTemplate->addCss ('main.css');
        $this->_oTemplate->pageCode($sTitle . $oCalendar->getTitle(), true, false);
    }

    function actionBrowse ($sMode = '', $sValue = '', $sValue2 = '', $sValue3 = '') {

        if ('user' == $sMode || 'my' == $sMode) {
            $aProfile = getProfileInfo ($this->_iProfileId);
            if (0 == strcasecmp($sValue, $aProfile['NickName']) || 'my' == $sMode) {
                $this->_browseMy ($aProfile);
                return;
            } 
        }

        if (!$this->isAllowedBrowse()) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }
        if ('tag' == $sMode || 'category' == $sMode)
            $sValue = uri2title($sValue);

        bx_import ('SearchResult', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'SearchResult';
        $o = new $sClass(process_db_input($sMode, BX_TAGS_STRIP), process_db_input($sValue, BX_TAGS_STRIP), process_db_input($sValue2, BX_TAGS_STRIP), process_db_input($sValue3, BX_TAGS_STRIP));

        if ($o->isError) {
            $this->_oTemplate->displayPageNotFound ();
            return;
        }

        if (bx_get('rss')) {
            echo $o->rss();
            exit;
        }

        $this->_oTemplate->pageStart();

        if ($s = $o->processing()) {
            echo $s;
        } else {
            $this->_oTemplate->displayNoData ();
            return;
        }

        $this->_oTemplate->addCss ('unit.css');
        $this->_oTemplate->addCss ('main.css');
        $this->_oTemplate->pageCode($o->aCurrent['title'], false, false);
    }

    function _actionSearch ($sKeyword, $sCategory, $sTitle) {

        if (!$this->isAllowedSearch()) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }

        $this->_oTemplate->pageStart();

        if ($sKeyword) 
            $_GET['Keyword'] = $sKeyword;
        if ($sCategory)
            $_GET['Category'] = explode(',', $sCategory);

        if (is_array($_GET['Category']) && 1 == count($_GET['Category']) && !$_GET['Category'][0]) {
            unset($_GET['Category']);
            unset($sCategory);
        }

        if ($sCategory || $sKeyword) {
            $_GET['submit_form'] = 1;
        }
        
        bx_import ('FormSearch', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'FormSearch';
        $oForm = new $sClass ();
        $oForm->initChecker();        

        if ($oForm->isSubmittedAndValid ()) {

            bx_import ('SearchResult', $this->_aModule);
            $sClass = $this->_aModule['class_prefix'] . 'SearchResult';
            $o = new $sClass('search', $oForm->getCleanValue('Keyword'), $oForm->getCleanValue('Category'));

            if ($o->isError) {
                $this->_oTemplate->displayPageNotFound ();
                return;
            }

            if ($s = $o->processing()) {
                
                echo $s;
                
            } else {
                $this->_oTemplate->displayNoData ();
                return;
            }

            $this->isAllowedSearch(true); // perform search action 

            $this->_oTemplate->addCss ('unit.css');
            $this->_oTemplate->addCss ('main.css');
            $this->_oTemplate->pageCode($o->aCurrent['title'], false, false);

        } else {

            echo $oForm->getCode ();
            $this->_oTemplate->addCss ('main.css');
            $this->_oTemplate->pageCode($sTitle);

        }
    }

    function _actionAdd ($sTitle) {

        if (!$this->isAllowedAdd()) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }

        $this->_oTemplate->pageStart();

        $this->_addForm(false);

        $this->_oTemplate->addJs ('main.js');
        $this->_oTemplate->addCss ('main.css');
        $this->_oTemplate->addCss ('forms_extra.css');
        $this->_oTemplate->pageCode($sTitle);
    }

    function _actionEdit ($iEntryId, $sTitle) { 

        $iEntryId = (int)$iEntryId;
        if (!($aDataEntry = $this->_oDb->getEntryById($iEntryId))) {
            $this->_oTemplate->displayPageNotFound ();
            return;
        }

        $GLOBALS['oTopMenu']->setCustomSubHeader($aDataEntry[$this->_oDb->_sFieldTitle]);
        $GLOBALS['oTopMenu']->setCustomVar($this->_sPrefix.'_view_uri', $aDataEntry[$this->_oDb->_sFieldUri]);
        $GLOBALS['oTopMenu']->setCustomBreadcrumbs(array(
            _t('_'.$this->_sPrefix) => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'home/',
            $aDataEntry[$this->_oDb->_sFieldTitle] => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aDataEntry[$this->_oDb->_sFieldUri],
            $sTitle => '',
        ));

        if (!$this->isAllowedEdit($aDataEntry)) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }

        $this->_oTemplate->pageStart();

        bx_import ('FormEdit', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'FormEdit';
        $oForm = new $sClass ($this, $aDataEntry[$this->_oDb->_sFieldAuthorId], $iEntryId, $aDataEntry);
        if (isset($aDataEntry[$this->_oDb->_sFieldJoinConfirmation]))
            $aDataEntry[$this->_oDb->_sFieldJoinConfirmation] = (int)$aDataEntry[$this->_oDb->_sFieldJoinConfirmation];
        
        $oForm->initChecker($aDataEntry);

        if ($oForm->isSubmittedAndValid ()) {

            $sStatus = $this->_oDb->getParam($this->_sPrefix . '_autoapproval') == 'on' || $this->isAdmin() ? 'approved' : 'pending';
            $aValsAdd = array ($this->_oDb->_sFieldStatus => $sStatus);
            if ($oForm->update ($iEntryId, $aValsAdd)) {

                $oForm->processMedia($iEntryId, $this->_iProfileId);

                $this->isAllowedEdit($aDataEntry, true); // perform action

                $this->onEventChanged ($iEntryId, $sStatus);
                header ('Location:' . BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aDataEntry[$this->_oDb->_sFieldUri]);
                exit;

            } else {

                echo MsgBox(_t('_Error Occured'));
                
            }            

        } else {

            echo $oForm->getCode ();

        }

        $this->_oTemplate->addJs ('main.js');
        $this->_oTemplate->addCss ('main.css');
        $this->_oTemplate->addCss ('forms_extra.css');
        $this->_oTemplate->pageCode($sTitle);
    }

    function _actionDelete ($iEntryId, $sMsgSuccess) {

        $iEntryId = (int)$iEntryId;
        if (!($aDataEntry = $this->_oDb->getEntryByIdAndOwner($iEntryId, $this->_iProfileId, $this->isAdmin()))) {
            echo MsgBox(_t('_sys_request_page_not_found_cpt')) . genAjaxyPopupJS($iEntryId, 'ajaxy_popup_result_div');
            exit;
        }

        if (!$this->isAllowedDelete($aDataEntry) || 0 != strcasecmp($_SERVER['REQUEST_METHOD'], 'POST')) {
            echo MsgBox(_t('_Access denied')) . genAjaxyPopupJS($iEntryId, 'ajaxy_popup_result_div');
            exit;
        }

        if ($this->_oDb->deleteEntryByIdAndOwner($iEntryId, $this->_iProfileId, $this->isAdmin())) {
            $this->isAllowedDelete($aDataEntry, true); // perform action
            $this->onEventDeleted ($iEntryId, $aDataEntry);            
            $sRedirect = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'browse/' . ($this->_iProfileId ? 'user/' . $this->_oDb->getProfileNickNameById($this->_iProfileId) : '');
            $sJQueryJS = genAjaxyPopupJS($iEntryId, 'ajaxy_popup_result_div', $sRedirect);
            echo MsgBox(_t($sMsgSuccess)) . $sJQueryJS;
            exit;
        }

        echo MsgBox(_t('_Error Occured')) . genAjaxyPopupJS($iEntryId, 'ajaxy_popup_result_div');
        exit;
    }

    function _actionMarkFeatured ($iEntryId, $sMsgSuccessAdd, $sMsgSuccessRemove) {

        $iEntryId = (int)$iEntryId;
        if (!($aDataEntry = $this->_oDb->getEntryByIdAndOwner($iEntryId, $this->_iProfileId, $this->isAdmin()))) {
            echo MsgBox(_t('_sys_request_page_not_found_cpt')) . genAjaxyPopupJS($iEntryId, 'ajaxy_popup_result_div');
            exit;
        }

        if (!$this->isAllowedMarkAsFeatured($aDataEntry) || 0 != strcasecmp($_SERVER['REQUEST_METHOD'], 'POST')) {
            echo MsgBox(_t('_Access denied')) . genAjaxyPopupJS($iEntryId, 'ajaxy_popup_result_div');
            exit;
        }

        if ($this->_oDb->markAsFeatured($iEntryId)) {
            $this->isAllowedMarkAsFeatured($aDataEntry, true); // perform action
            $this->onEventMarkAsFeatured ($iEntryId, $aDataEntry);
            $sRedirect = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aDataEntry[$this->_oDb->_sFieldUri];
            $sJQueryJS = genAjaxyPopupJS($iEntryId, 'ajaxy_popup_result_div', $sRedirect);
            echo MsgBox($aDataEntry[$this->_oDb->_sFieldFeatured] ? $sMsgSuccessRemove : $sMsgSuccessAdd) . $sJQueryJS;
            exit;
        }        

        echo MsgBox(_t('_Error Occured')) . genAjaxyPopupJS($iEntryId, 'ajaxy_popup_result_div');
        exit;        
    }

    function _actionJoin ($iEntryId, $iProfileId, $sMsgAlreadyJoined, $sMsgAlreadyJoinedPending, $sMsgJoinSuccess, $sMsgJoinSuccessPending, $sMsgLeaveSuccess) {

        $iEntryId = (int)$iEntryId;
        if (!($aDataEntry = $this->_oDb->getEntryByIdAndOwner($iEntryId, 0, true))) {
            echo MsgBox(_t('_sys_request_page_not_found_cpt')) . genAjaxyPopupJS($iEntryId, 'ajaxy_popup_result_div');
            exit;
        }

        if (!$this->isAllowedJoin($aDataEntry) || 0 != strcasecmp($_SERVER['REQUEST_METHOD'], 'POST')) {
            echo MsgBox(_t('_Access denied')) . genAjaxyPopupJS($iEntryId, 'ajaxy_popup_result_div');
            exit;
        }

		$isFan = $this->_oDb->isFan ($iEntryId, $this->_iProfileId, true) || $this->_oDb->isFan ($iEntryId, $this->_iProfileId, false);

		if ($isFan) {

			if ($this->_oDb->leaveEntry($iEntryId, $this->_iProfileId)) {
				$sRedirect = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aDataEntry[$this->_oDb->_sFieldUri];
				echo MsgBox($sMsgLeaveSuccess) . genAjaxyPopupJS($iEntryId, 'ajaxy_popup_result_div', $sRedirect);
				exit;
			}

		} else {
		
			$isConfirmed = ($this->isEntryAdmin($aDataEntry) || !$aDataEntry[$this->_oDb->_sFieldJoinConfirmation] ? true : false);

			if ($this->_oDb->joinEntry($iEntryId, $this->_iProfileId, $isConfirmed)) {
				if ($isConfirmed) {
					$this->onEventJoin ($iEntryId, $this->_iProfileId, $aDataEntry);
					$sRedirect = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aDataEntry[$this->_oDb->_sFieldUri];
				} else {
					$this->onEventJoinRequest ($iEntryId, $this->_iProfileId, $aDataEntry);
					$sRedirect = '';
				}            
				echo MsgBox($isConfirmed ? $sMsgJoinSuccess : $sMsgJoinSuccessPending) . genAjaxyPopupJS($iEntryId, 'ajaxy_popup_result_div', $sRedirect);
				exit;
			}
		}

        echo MsgBox(_t('_Error Occured')) . genAjaxyPopupJS($iEntryId, 'ajaxy_popup_result_div');
        exit;
    }    

    function _actionManageFansPopup ($iEntryId, $sTitle, $sFuncGetFans = 'getFans', $sFuncIsAllowedManageFans = 'isAllowedManageFans', $sFuncIsAllowedManageAdmins = 'isAllowedManageAdmins', $iMaxFans = 1000) {

        $iEntryId = (int)$iEntryId;
        if (!($aDataEntry = $this->_oDb->getEntryByIdAndOwner ($iEntryId, 0, true))) {
            echo $GLOBALS['oFunctions']->transBox(MsgBox(_t('_Empty')));
            exit;
        }

        if (!$this->$sFuncIsAllowedManageFans($aDataEntry)) {
            echo $GLOBALS['oFunctions']->transBox(MsgBox(_t('_Access denied')));
            exit;
        }

        $aProfiles = array ();
        $iNum = $this->_oDb->$sFuncGetFans($aProfiles, $iEntryId, true, 0, $iMaxFans);
        if (!$iNum) {
            echo $GLOBALS['oFunctions']->transBox(MsgBox(_t('_Empty')));
            exit;
        }

        $sActionsUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . "view/" . $aDataEntry[$this->_oDb->_sFieldUri] . '?ajax_action=';
        $aButtons = array (
            array (
                'type' => 'submit',
                'name' => 'fans_remove',
                'value' => _t('_sys_btn_fans_remove'),
                'onclick' => "onclick=\"getHtmlData('sys_manage_items_manage_fans_content', '{$sActionsUrl}remove&ids=' + sys_manage_items_get_manage_fans_ids(), false, 'post'); return false;\"",
            ),
        );

        if ($this->$sFuncIsAllowedManageAdmins($aDataEntry)) {

            $aButtons = array_merge($aButtons, array (
                array (
                    'type' => 'submit',
                    'name' => 'fans_add_to_admins',
                    'value' => _t('_sys_btn_fans_add_to_admins'),
                    'onclick' => "onclick=\"getHtmlData('sys_manage_items_manage_fans_content', '{$sActionsUrl}add_to_admins&ids=' + sys_manage_items_get_manage_fans_ids(), false, 'post'); return false;\"",
                ),
                array (
                    'type' => 'submit',
                    'name' => 'fans_move_admins_to_fans',
                    'value' => _t('_sys_btn_fans_move_admins_to_fans'),
                    'onclick' => "onclick=\"getHtmlData('sys_manage_items_manage_fans_content', '{$sActionsUrl}admins_to_fans&ids=' + sys_manage_items_get_manage_fans_ids(), false, 'post'); return false;\"",
                ),            
            ));
        };
        bx_import ('BxTemplSearchResult');
        $sControl = BxTemplSearchResult::showAdminActionsPanel('sys_manage_items_manage_fans', $aButtons, 'sys_fan_unit');

        $aVarsContent = array (            
            'suffix' => 'manage_fans',
            'content' => $this->_profilesEdit($aProfiles, false, $aDataEntry),
            'control' => $sControl,
        );
        $aVarsPopup = array (
            'title' => $sTitle,
            'content' => $this->_oTemplate->parseHtmlByName('manage_items_form', $aVarsContent),
        );        
        echo $GLOBALS['oFunctions']->transBox($this->_oTemplate->parseHtmlByName('popup', $aVarsPopup), true);
        exit;
    }

    function _actionSharePopup ($iEntryId, $sTitle) {

        $iEntryId = (int)$iEntryId;
        if (!($aDataEntry = $this->_oDb->getEntryByIdAndOwner ($iEntryId, 0, true))) {
            echo MsgBox(_t('_Empty'));
            exit;
        }

        require_once (BX_DIRECTORY_PATH_INC . "shared_sites.inc.php");
        $sEntryUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aDataEntry[$this->_oDb->_sFieldUri];
        $aSitesPrepare = getSitesArray ($sEntryUrl);        
        $sIconsUrl = getTemplateIcon('digg.png');        
        $sIconsUrl = str_replace('digg.png', '', $sIconsUrl);
        $aSites = array ();
        foreach ($aSitesPrepare as $k => $r) {
            $aSites[] = array (
                'icon' => $sIconsUrl . $r['icon'],
                'name' => $k,
                'url' => $r['url'],
            );
        }

        $aVarsContent = array (
            'bx_repeat:sites' => $aSites,
        );
        $aVarsPopup = array (
            'title' => $sTitle,
            'content' => $this->_oTemplate->parseHtmlByName('popup_share', $aVarsContent),
        );        
        echo $GLOBALS['oFunctions']->transBox($this->_oTemplate->parseHtmlByName('popup', $aVarsPopup), true);
        exit;
    }

    function _actionTags($sTitle, $sTitleAllTags = '')
    {
        bx_import('BxTemplTagsModule');
        $aParam = array(
            'type' => $this->_sPrefix,
            'orderby' => 'popular'
        );
        $oTags = new BxTemplTagsModule($aParam, $sTitleAllTags, BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'tags');
        $this->_oTemplate->pageStart();
        echo $oTags->getCode();
        $this->_oTemplate->pageCode($sTitle, false, false);
    }

    function _actionCategories($sTitle)
    {
        bx_import('BxTemplCategoriesModule');
        $aParam = array(
            'type' => $this->_sPrefix
        );
        $oCateg = new BxTemplCategoriesModule($aParam, _t('_categ_users'), BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'categories');
        $this->_oTemplate->pageStart();
        echo $oCateg->getCode();
        $this->_oTemplate->pageCode($sTitle, false, false);
    }

    function _actionDownload ($aFileInfo, $sFieldMediaId) {
    
        $aFile = BxDolService::call('files', 'get_file_array', array($aFileInfo[$sFieldMediaId]), 'Search');
        if (!$aFile['date']) {
            $this->_oTemplate->displayPageNotFound ();
            exit;
        }
		$aFile['full_name'] = uriFilter($aFile['title']) . '.' . $aFile['extension'];
        $aPathInfo = pathinfo ($aFile['path']);
        header ("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header ("Content-type: " . $aFile['mime_type']);
        header ("Content-Length: " . filesize ($aFile['path']));
        header ("Content-Disposition: attachment; filename={$aFile['full_name']}");
        readfile ($aFile['path']);
        exit;
    }

    // ================================== external actions

    function serviceGetForumPermission($iMemberId, $iForumId) {

        $iMemberId = (int)$iMemberId;
        $iForumId = (int)$iForumId;

        $aFalse = array (
            'admin' => 0,
            'read' => 0,
            'post' => 0,
        );

        if (!($aForum = $this->_oDb->getForumById ($iForumId)))
            return $aFalse;

        if (!($aDataEntry = $this->_oDb->getEntryById ($aForum['entry_id'])))
            return $aFalse;

        $aTrue = array (
            'admin' => $aDataEntry[$this->_oDb->_sFieldAuthorId] == $iMemberId || $this->isAdmin() ? 1 : 0, // author is admin
            'read' => $this->isAllowedPostInForum ($aDataEntry, $iMemberId) ? 1 : 0,
            'post' => $this->isAllowedPostInForum ($aDataEntry, $iMemberId) ? 1 : 0,
        );

        return $aTrue;
    }

    function serviceDeleteProfileData ($iProfileId) {

        $iProfileId = (int)$iProfileId;

        if (!$iProfileId)
            return false;

        $aDataEntries = $this->_oDb->getEntriesByAuthor ($iProfileId);
        foreach ($aDataEntries as $iEntryId) {
            if ($this->_oDb->deleteEntryByIdAndOwner($iEntryId, $iProfileId, false))
                $this->onEventDeleted ($iEntryId);
        }
    }

    function serviceResponseProfileDelete ($oAlert) {

        if (!($iProfileId = (int)$oAlert->iObject))
            return false;

        $this->serviceDeleteProfileData ($iProfileId);
        
        return true;
    }

    function serviceResponseMediaDelete ($oAlert) {
        
        $iMediaId = (int)$oAlert->iObject;
        if (!$iMediaId)
            return false;

        switch ($oAlert->sUnit) {
        case 'bx_videos':
            $sMediaType = 'videos';
            break;
        case 'bx_sounds':
            $sMediaType = 'sounds';
            break;            
        case 'bx_photos':
            $sMediaType = 'images';
            break;
        case 'bx_files':
            $sMediaType = 'files';
            break;
        default:
            return false;
        }

        return $this->_oDb->deleteMediaFile ($iMediaId, $sMediaType);
    }

    function _serviceGetMemberMenuItem ($sTitle, $sAlt, $sIcon) {

        if (!$this->_iProfileId) 
            return '';

        $oMemberMenu = bx_instance('BxDolMemberMenu');

        $aLinkInfo = array(
            'item_img_src'  => $this->_oTemplate->getIconUrl ($sIcon),
            'item_img_alt'  => $sAlt,
            'item_link'     => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'browse/my/',
            'item_onclick'  => null,
            'item_title'    => $sTitle,
            'extra_info'    => $this->_oDb->getCountByAuthorAndStatus($this->_iProfileId, 'approved') + $this->_oDb->getCountByAuthorAndStatus($this->_iProfileId, 'pending'),
        );

        return $oMemberMenu -> getGetExtraMenuLink($aLinkInfo);
    }

    function _serviceGetWallPost ($aEvent, $sTextWallObject, $sTextAddedNew) {

        if (!($aProfile = getProfileInfo($aEvent['owner_id'])))
            return '';
	    if (!($aDataEntry = $this->_oDb->getEntryByIdAndOwner ($aEvent['object_id'], $aEvent['owner_id'], 0))) 
            return '';

        $sCss = '';        
        if($aEvent['js_mode'])
            $sCss = $this->_oTemplate->addCss('wall_post.css', true);
        else 
            $this->_oTemplate->addCss('wall_post.css');

        $aVars = array(
                'cpt_user_name' => $aProfile['NickName'],
                'cpt_added_new' => $sTextAddedNew,
                'cpt_object' => $sTextWallObject,
                'cpt_item_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aDataEntry[$this->_oDb->_sFieldUri],
                'post_id' => $aEvent['id'],
        );
        return array(
            'title' => $aProfile['username'] . ' ' . $sTextAddedNew . ' ' . $sTextWallObject,
            'description' => $aDataEntry[$this->_oDb->_sFieldDesc],
            'content' => $sCss . $this->_oTemplate->parseHtmlByName('wall_post', $aVars)
        );

    }

	function serviceGetWallData () {
	    return array(
            'handlers' => array(
                array('alert_unit' => $this->_sPrefix, 'alert_action' => 'add', 'module_uri' => $this->_aModule['uri'], 'module_class' => 'Module', 'module_method' => 'get_wall_post')
            ),
            'alerts' => array(
                array('unit' => $this->_sPrefix, 'action' => 'add')
            )
	    );
    }

    function _serviceGetSpyPost($sAction, $iObjectId, $iSenderId, $aExtraParams, $aLangKeys)
    {
        $aProfile = getProfileInfo($iSenderId);
        if (!($aDataEntry = $this->_oDb->getEntryByIdAndOwner ($iObjectId, 0, true)))
            return array();
        if (empty($aLangKeys[$sAction]))
            return array();

        return array(
            'lang_key' => $aLangKeys[$sAction],
            'params' => array(
                'profile_link' => $aProfile ? getProfileLink($iSenderId) : 'javascript:void(0)', 
                'profile_nick' => $aProfile ? $aProfile['NickName'] : _t('_Guest'),
                'entry_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aDataEntry[$this->_oDb->_sFieldUri],
                'entry_title' => $aDataEntry[$this->_oDb->_sFieldTitle],
            ),
            'recipient_id' => $aDataEntry[$this->_oDb->_sFieldAuthorId],
            'spy_type' => 'content_activity',
        );
    }
    

    function serviceGetSpyData () {
        return array(
            'handlers' => array(
                array('alert_unit' => $this->_sPrefix, 'alert_action' => 'add', 'module_uri' => $this->_aModule['uri'], 'module_class' => 'Module', 'module_method' => 'get_spy_post'),
                array('alert_unit' => $this->_sPrefix, 'alert_action' => 'change', 'module_uri' => $this->_aModule['uri'], 'module_class' => 'Module', 'module_method' => 'get_spy_post'),
                array('alert_unit' => $this->_sPrefix, 'alert_action' => 'join', 'module_uri' => $this->_aModule['uri'], 'module_class' => 'Module', 'module_method' => 'get_spy_post'),
                array('alert_unit' => $this->_sPrefix, 'alert_action' => 'rate', 'module_uri' => $this->_aModule['uri'], 'module_class' => 'Module', 'module_method' => 'get_spy_post'),
                array('alert_unit' => $this->_sPrefix, 'alert_action' => 'commentPost', 'module_uri' => $this->_aModule['uri'], 'module_class' => 'Module', 'module_method' => 'get_spy_post'),
            ),
            'alerts' => array(
                array('unit' => $this->_sPrefix, 'action' => 'add'),
                array('unit' => $this->_sPrefix, 'action' => 'change'),
                array('unit' => $this->_sPrefix, 'action' => 'join'),
                array('unit' => $this->_sPrefix, 'action' => 'rate'),
                array('unit' => $this->_sPrefix, 'action' => 'commentPost'),
            )
        );
    }
    

    function _serviceGetSubscriptionParams ($sAction, $iEntryId, $aAction2Name) {

        if (!($aDataEntry = $this->_oDb->getEntryByIdAndOwner ($iEntryId, 0, true)) || $aDataEntry[$this->_oDb->_sFieldStatus] != 'approved') {
            return array('skip' => true);
        }

        if (isset($aAction2Name[$sAction]))
            $sActionName = $aAction2Name[$sAction];
        else
            $sActionName = '';

        return array (
            'skip' => false,
            'template' => array (
                'Subscription' => $aDataEntry[$this->_oDb->_sFieldTitle] . ($sActionName ? ' (' . $sActionName . ')' : ''),
                'EntryTitle' => $aDataEntry[$this->_oDb->_sFieldTitle],
                'ActionName' => $sActionName,
                'ViewLink' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aDataEntry[$this->_oDb->_sFieldUri],
            ),
        );
    }

    // ================================== admin actions


    function _actionAdministrationSettings ($sSettingsCatName) {         

        if (!preg_match('/^[A-Za-z0-9_-]+$/', $sSettingsCatName))
            return MsgBox(_t('_sys_request_page_not_found_cpt'));

	    $iId = $this->_oDb->getSettingsCategory($sSettingsCatName);
	    if(empty($iId))
	       return MsgBox(_t('_sys_request_page_not_found_cpt'));

        bx_import('BxDolAdminSettings');

        $mixedResult = '';
        if(isset($_POST['save']) && isset($_POST['cat'])) {
	        $oSettings = new BxDolAdminSettings($iId);
            $mixedResult = $oSettings->saveChanges($_POST);
        }
        
        $oSettings = new BxDolAdminSettings($iId);
        $sResult = $oSettings->getForm();
        	       
        if($mixedResult !== true && !empty($mixedResult))
            $sResult = $mixedResult . $sResult;

        $aVars = array (
            'content' => $sResult,
        );
        return $this->_oTemplate->parseHtmlByName('default_padding', $aVars);
    }

    function _actionAdministrationManage ($isAdminEntries, $sKeyBtnDelete, $sKeyBtnActivate) {

        if ($_POST['action_activate'] && is_array($_POST['entry'])) {

            foreach ($_POST['entry'] as $iId) {
                if ($this->_oDb->activateEntry($iId)) {
                    $this->onEventChanged ($iId, 'approved');
                }
            }

        } elseif ($_POST['action_delete'] && is_array($_POST['entry'])) {

            foreach ($_POST['entry'] as $iId) {

                $aDataEntry = $this->_oDb->getEntryById($iId);
                if (!$this->isAllowedDelete($aDataEntry)) 
                    continue;

                if ($this->_oDb->deleteEntryByIdAndOwner($iId, 0, $this->isAdmin())) {
                    $this->onEventDeleted ($iId);
                }
            }
        }

        if ($isAdminEntries) {
            $sContent = $this->_manageEntries ('admin', '', true, 'bx_twig_admin_form', array(
                'action_delete' => $sKeyBtnDelete,
            ));
        } else {
            $sContent = $this->_manageEntries ('pending', '', true, 'bx_twig_admin_form', array(
                'action_activate' => $sKeyBtnActivate,
                'action_delete' => $sKeyBtnDelete,
            ));
        }
            
        return $sContent;
    }

    function actionAdministrationCreateEntry () {

        if (!$this->isAllowedAdd()) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }

        ob_start();        
        $this->_addForm(BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/admin_entries');
        $aVars = array (
            'content' => ob_get_clean(),
        );        
        return $this->_oTemplate->parseHtmlByName('default_padding', $aVars);
    }

    // ================================== tags/cats reparse functions

    function reparseTags ($iEntryId) {
        $iEntryId = (int)$iEntryId;
        bx_import('BxDolTags');
        $o = new BxDolTags ();
        $o->reparseObjTags($this->_sPrefix, $iEntryId);
    }

    function reparseCategories ($iEntryId) {
        $iEntryId = (int)$iEntryId;
        bx_import('BxDolCategories');
        $o = new BxDolCategories ();
        $o->reparseObjTags($this->_sPrefix, $iEntryId);
    }

    // ================================== events

    function onEventCreate ($iEntryId, $sStatus, $aDataEntry = array()) {
        if ('approved' == $sStatus) {
            $this->reparseTags ($iEntryId);
            $this->reparseCategories ($iEntryId);
        }
        $this->_oDb->createForum ($aDataEntry, $this->_oDb->getProfileNickNameById($this->_iProfileId));
		$oAlert = new BxDolAlerts($this->_sPrefix, 'add', $iEntryId, $this->_iProfileId, array('Status' => $sStatus));
		$oAlert->alert();
    }

    function onEventChanged ($iEntryId, $sStatus) {
        $this->reparseTags ($iEntryId);
        $this->reparseCategories ($iEntryId);

		$oAlert = new BxDolAlerts($this->_sPrefix, 'change', $iEntryId, $this->_iProfileId, array('Status' => $sStatus));
		$oAlert->alert();
    }    

    function onEventDeleted ($iEntryId, $aDataEntry = array()) {

        // delete associated tags and categories 
        $this->reparseTags ($iEntryId);
        $this->reparseCategories ($iEntryId);

        // delete votings
        bx_import('Voting', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'Voting';
        $oVoting = new $sClass ($this->_sPrefix, 0, 0);
        $oVoting->deleteVotings ($iEntryId);

        // delete comments 
        bx_import('Cmts', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'Cmts';
        $oCmts = new $sClass ($this->_sPrefix, $iEntryId);
        $oCmts->onObjectDelete ();

        // delete views
        bx_import ('BxDolViews');
        $oViews = new BxDolViews($this->_sPrefix, $iEntryId, false);
        $oViews->onObjectDelete();

        // delete forum
        $this->_oDb->deleteForum ($iEntryId);

        // arise alert
		$oAlert = new BxDolAlerts($this->_sPrefix, 'delete', $iEntryId, $this->_iProfileId);
		$oAlert->alert();
    }        

    function onEventMarkAsFeatured ($iEntryId, $aDataEntry) {

        // arise alert
		$oAlert = new BxDolAlerts($this->_sPrefix, 'mark_as_featured', $iEntryId, $this->_iProfileId, array('Featured' => $aDataEntry[$this->_oDb->_sFieldFeatured]));
		$oAlert->alert();
    }        

    function onEventJoin ($iEntryId, $iProfileId, $aDataEntry) {
        // we do not need to send any notofication mail here because it will be part of standard subscription process 
		$oAlert = new BxDolAlerts($this->_sPrefix, 'join', $iEntryId, $iProfileId);
		$oAlert->alert();
    }            

    function _onEventJoinRequest ($iEntryId, $iProfileId, $aDataEntry, $sEmailTemplate, $iMaxFans = 1000) {

        $iNum = $this->_oDb->getAdmins($aGroupAdmins, $iEntryId, 0, $iMaxFans);
        foreach ($aGroupAdmins as $aProfile)
            $this->_notifyEmail ($sEmailTemplate, $aProfile['ID'], $aDataEntry);
         
		$oAlert = new BxDolAlerts($this->_sPrefix, 'join_request', $iEntryId, $iProfileId);
		$oAlert->alert();        
    }

    function _onEventJoinReject ($iEntryId, $iProfileId, $aDataEntry, $sEmailTemplate) {
        $this->_notifyEmail ($sEmailTemplate, $iProfileId, $aDataEntry);
		$oAlert = new BxDolAlerts($this->_sPrefix, 'join_reject', $iEntryId, $iProfileId);
		$oAlert->alert();        
    }

    function _onEventFanRemove ($iEntryId, $iProfileId, $aDataEntry, $sEmailTemplate) {
        $this->_notifyEmail ($sEmailTemplate, $iProfileId, $aDataEntry);
		$oAlert = new BxDolAlerts($this->_sPrefix, 'fan_remove', $iEntryId, $iProfileId);
		$oAlert->alert();        
    }

    function _onEventFanBecomeAdmin ($iEntryId, $iProfileId, $aDataEntry, $sEmailTemplate) {
        $this->_notifyEmail ($sEmailTemplate, $iProfileId, $aDataEntry);
		$oAlert = new BxDolAlerts($this->_sPrefix, 'fan_become_admin', $iEntryId, $iProfileId);
		$oAlert->alert();        
    }

    function _onEventAdminBecomeFan ($iEntryId, $iProfileId, $aDataEntry, $sEmailTemplate) {
        $this->_notifyEmail ($sEmailTemplate, $iProfileId, $aDataEntry);
		$oAlert = new BxDolAlerts($this->_sPrefix, 'admin_become_fan', $iEntryId, $iProfileId);
		$oAlert->alert();        
    }

    function _onEventJoinConfirm ($iEntryId, $iProfileId, $aDataEntry, $sEmailTemplate) {
        $this->_notifyEmail ($sEmailTemplate, $iProfileId, $aDataEntry);
		$oAlert = new BxDolAlerts($this->_sPrefix, 'join_confirm', $iEntryId, $iProfileId);
		$oAlert->alert();        
    }

    // ================================== other function 

    function isAdmin () {
        return $GLOBALS['logged']['admin'] && isProfileActive($this->_iProfileId);
    }             

    function _addForm ($sRedirectUrl) {

        bx_import ('FormAdd', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'FormAdd';
        $oForm = new $sClass ($this, $this->_iProfileId);
        $oForm->initChecker();

        if ($oForm->isSubmittedAndValid ()) {

            $sStatus = $this->_oDb->getParam($this->_sPrefix.'_autoapproval') == 'on' ? 'approved' : 'pending';
            $aValsAdd = array (
                $this->_oDb->_sFieldCreated => time(),
                $this->_oDb->_sFieldUri => $oForm->generateUri(),
                $this->_oDb->_sFieldStatus => $sStatus,
            );                        
            $aValsAdd[$this->_oDb->_sFieldAuthorId] = $this->_iProfileId;

            $iEntryId = $oForm->insert ($aValsAdd);

            if ($iEntryId) {

                $this->isAllowedAdd(true); // perform action                 

                $oForm->processMedia($iEntryId, $this->_iProfileId);

                $aDataEntry = $this->_oDb->getEntryByIdAndOwner($iEntryId, $this->_iProfileId, $this->isAdmin());
                $this->onEventCreate($iEntryId, $sStatus, $aDataEntry);
                if (!$sRedirectUrl)
                    $sRedirectUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aDataEntry[$this->_oDb->_sFieldUri];
                header ('Location:' . $sRedirectUrl);
                exit;

            } else {

                MsgBox(_t('_Error Occured'));
            }
                         
        } else {
            
            echo $oForm->getCode ();

        }
    }

    function _manageEntries ($sMode, $sValue, $isFilter, $sFormName, $aButtons, $sAjaxPaginationBlockId = '', $isMsgBoxIfEmpty = true, $iPerPage = 0) {

        bx_import ('SearchResult', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'SearchResult';
        $o = new $sClass($sMode, $sValue);
        $o->sUnitTemplate = 'unit_admin';

        if ($iPerPage)
            $o->aCurrent['paginate']['perPage'] = $iPerPage;

        $sPagination = $sActionsPanel = '';
        if ($o->isError) {
            $sContent = MsgBox(_t('_Error Occured'));
        } elseif (!($sContent = $o->displayResultBlock())) {
            if ($isMsgBoxIfEmpty)
                $sContent = MsgBox(_t('_Empty'));
            else
                return '';
        } else {
            $sPagination = $sAjaxPaginationBlockId ? $o->showPaginationAjax($sAjaxPaginationBlockId) : $o->showPagination();
            $sActionsPanel = $o->showAdminActionsPanel ($sFormName, $aButtons);
        }

        $aVars = array (
            'form_name' => $sFormName,
            'content' => $sContent,
            'pagination' => $sPagination,
            'filter_panel' => $isFilter ? $o->showAdminFilterPanel(false !== bx_get($this->_sFilterName) ? bx_get($this->_sFilterName) : '', 'filter_input_id', 'filter_checkbox_id', $this->_sFilterName) : '',
            'actions_panel' => $sActionsPanel,
        );        
        return  $this->_oTemplate->parseHtmlByName ('manage', $aVars);
    }

    function _preProductTabs ($sUri, $sSubTab = '') {

        if ($GLOBALS['oTemplConfig']->bAllowUnicodeInPreg)
            $sReg = '/^[\pL\pN\-_]+$/u'; // unicode characters
        else
            $sReg = '/^[\d\w\-_]+$/u'; // latin characters only

        if (!preg_match($sReg, $sUri)) {
            $this->_oTemplate->displayPageNotFound ();
            return false;
        }

        if (!($aDataEntry = $this->_oDb->getEntryByUri($sUri))) {
            $this->_oTemplate->displayPageNotFound ();
            return false;
        }        

        if ($aDataEntry[$this->_oDb->_sFieldStatus] == 'pending' && !$this->isAdmin() && !($aDataEntry[$this->_oDb->_sFieldAuthorId] == $this->_iProfileId && $aDataEntry[$this->_oDb->_sFieldAuthorId]))  {
            $this->_oTemplate->displayPageNotFound ();
            return false;
        }        

        $GLOBALS['oTopMenu']->setCustomSubHeader($aDataEntry[$this->_oDb->_sFieldTitle]);
        $GLOBALS['oTopMenu']->setCustomVar($this->_sPrefix.'_view_uri', $aDataEntry[$this->_oDb->_sFieldUri]);        
        $GLOBALS['oTopMenu']->setCustomBreadcrumbs(array(
            _t('_'.$this->_sPrefix) => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'home/',
            $aDataEntry[$this->_oDb->_sFieldTitle] => $sSubTab ? BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aDataEntry[$this->_oDb->_sFieldUri] : '',
            $sSubTab => '',
        ));

        if ((!$this->_iProfileId || $aDataEntry[$this->_oDb->_sFieldAuthorId] != $this->_iProfileId) && !$this->isAllowedView($aDataEntry, true)) {
            $this->_oTemplate->displayAccessDenied ();
            return false;
        }        

        return $aDataEntry;
    }


    function _processFansActions ($aDataEntry, $iMaxFans = 1000) {

        if (false !== bx_get('ajax_action') && $this->isAllowedManageFans($aDataEntry) && 0 == strcasecmp($_SERVER['REQUEST_METHOD'], 'POST')) {

            $iEntryId = $aDataEntry[$this->_oDb->_sFieldId];
            $aIds = array ();
            if (false !== bx_get('ids'))
                $aIds = $this->_getCleanIdsArray (bx_get('ids'));

            $isShowConfirmedFansOnly = false;
            switch (bx_get('ajax_action')) {
                case 'remove':
                    $isShowConfirmedFansOnly = true;
                    if ($this->_oDb->removeFans($iEntryId, $aIds)) {
                        foreach ($aIds as $iProfileId)
                            $this->onEventFanRemove ($iEntryId, $iProfileId, $aDataEntry);
                    }
                    break;
                case 'add_to_admins':
                    $isShowConfirmedFansOnly = true;
                    if ($this->isAllowedManageAdmins($aDataEntry) && $this->_oDb->addGroupAdmin($iEntryId, $aIds)) {
                        $aProfiles = array (); 
                        $iNum = $this->_oDb->getAdmins($aProfiles, $iEntryId, 0, $iMaxFans, $aIds);
                        foreach ($aProfiles as $aProfile)
                            $this->onEventFanBecomeAdmin ($iEntryId, $aProfile['ID'], $aDataEntry);
                    }
                    break;
                case 'admins_to_fans':
                    $isShowConfirmedFansOnly = true;
                    $iNum = $this->_oDb->getAdmins($aGroupAdmins, $iEntryId, 0, $iMaxFans);
                    if ($this->isAllowedManageAdmins($aDataEntry) && $this->_oDb->removeGroupAdmin($iEntryId, $aIds)) {
                        foreach ($aGroupAdmins as $aProfile) {
                            if (in_array($aProfile['ID'], $aIds))
                                $this->onEventAdminBecomeFan ($iEntryId, $aProfile['ID'], $aDataEntry);
                        }
                    }
                    break;                    
                case 'confirm':                                        
                    if ($this->_oDb->confirmFans($iEntryId, $aIds)) {
                        echo '<script type="text/javascript" language="javascript">
                            document.location = "' . BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . "view/" . $aDataEntry[$this->_oDb->_sFieldUri] . '";
                        </script>';                        
                        $aProfiles = array (); 
                        $iNum = $this->_oDb->getFans($aProfiles, $iEntryId, true, 0, $iMaxFans, $aIds);
                        foreach ($aProfiles as $aProfile) {
                            $this->onEventJoin ($iEntryId, $aProfile['ID'], $aDataEntry);
                            $this->onEventJoinConfirm ($iEntryId, $aProfile['ID'], $aDataEntry);
                        }
                    }
                    break;
                case 'reject':
                    if ($this->_oDb->rejectFans($iEntryId, $aIds)) {
                        foreach ($aIds as $iProfileId)
                            $this->onEventJoinReject ($iEntryId, $iProfileId, $aDataEntry);
                    }
                    break;
                case 'list':
                    break;                    
            }

            $aProfiles = array ();
            $iNum = $this->_oDb->getFans($aProfiles, $iEntryId, $isShowConfirmedFansOnly, 0, $iMaxFans);
            if (!$iNum) {
                echo MsgBox(_t('_Empty'));                
            } else {
                echo $this->_profilesEdit ($aProfiles, true, $aDataEntry);
            }
            exit;
        }
    }    

    function _getCleanIdsArray ($sIds, $sDivider = ',') {
        $a = explode($sDivider, $sIds);
        $aRet = array();
        foreach ($a as $iId) {
            if (!(int)$iId)
                continue;
            $aRet[] = (int)$iId;
        }
        return $aRet;
    }

    function _profilesEdit(&$aProfiles, $isCenterContent = true, $aDataEntry = array()) {
	    $sResult = "";
        foreach($aProfiles as $aProfile) {
            $aVars = array(                
                'id' => $aProfile['ID'],
                'thumb' => get_member_thumbnail($aProfile['ID'], 'none', true),
                'bx_if:admin' => array (
                    'condition' => $aDataEntry && $this->isEntryAdmin ($aDataEntry, $aProfile['ID']) ? true : false,
                    'content' => array (),                    
                ),
            );
            $sResult .= $this->_oTemplate->parseHtmlByName('unit_fan', $aVars);
        }
	    return $isCenterContent ? $GLOBALS['oFunctions']->centerContent ($sResult, '.sys_fan_unit') : $sResult;
    }        

    function _notifyEmail ($sEmailTemplateName, $iRecipient, $aDataEntry) {

        if (!($aProfile = getProfileInfo ($iRecipient)))
            return false;

        bx_import ('BxDolEmailTemplates');
        $oEmailTemplate = new BxDolEmailTemplates();
        if (!$oEmailTemplate)            
            return false;

        $aTemplate = $oEmailTemplate->getTemplate($sEmailTemplateName); 
        $aTemplateVars = array (
            'EntryTitle' => $aDataEntry[$this->_oDb->_sFieldTitle],
            'EntryUrl' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aDataEntry[$this->_oDb->_sFieldUri],
        );        

        return sendMail($aProfile['Email'], $aTemplate['Subject'], $aTemplate['Body'], $aProfile['ID'], $aTemplateVars);
    }    

    function _browseMy (&$aProfile, $sTitle) {

        // check access
        if (!$this->_iProfileId) {
            $this->_oTemplate->displayAccessDenied();
            return;
        } 

        $bAjaxMode = isset($_SERVER['HTTP_X_REQUESTED_WITH']) and $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ? true : false;

        // process delete action 
        if (bx_get('action_delete') && is_array(bx_get('entry'))) {
			$aEntries = bx_get('entry');
            foreach ($aEntries as $iEntryId) {
                $iEntryId = (int)$iEntryId;
                $aDataEntry = $this->_oDb->getEntryById($iEntryId);
                if (!$this->isAllowedDelete($aDataEntry)) 
                    continue;
                if ($this->_oDb->deleteEntryByIdAndOwner($iEntryId, $this->_iProfileId, 0)) {
                    $this->onEventDeleted ($iEntryId);
                }
            }
        }

        bx_import ('PageMy', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'PageMy';
        $oPage = new $sClass ($this, $aProfile);

        // manage my data entries
        if ($bAjaxMode && ($_sPrefix . '_my_active') == bx_get('block')) {
            echo $oPage->getBlockCode_My();
            exit;
        }

        // manage my pending data entries 
        if ($bAjaxMode && ($_sPrefix . '_my_pending') == bx_get('block')) {
            echo $oPage->getBlockCode_Pending();
            exit;
        }

        $this->_oTemplate->pageStart();

        // display whole page
        if (!$bAjaxMode)
            echo $oPage->getCode();

        $this->_oTemplate->addJs ('main.js');
        $this->_oTemplate->addCss ('main.css');
        $this->_oTemplate->addCss ('form.css');
        $this->_oTemplate->addCss ('admin.css');
        $this->_oTemplate->addCss ('forms_extra.css');
        $this->_oTemplate->pageCode($sTitle, false, false);
    }    

    function isMembershipEnabledForImages () {
        return $this->_isMembershipEnabledFor ('BX_PHOTOS_ADD');
    }

    function isMembershipEnabledForVideos () {
        return $this->_isMembershipEnabledFor ('BX_VIDEOS_ADD');
    }

    function isMembershipEnabledForSounds () {
        return $this->_isMembershipEnabledFor ('BX_SOUNDS_ADD');
    }

    function isMembershipEnabledForFiles () {
        return $this->_isMembershipEnabledFor ('BX_FILES_ADD');
    }

    function _isMembershipEnabledFor ($sMembershipActionConstant) {
        defineMembershipActions (array('photos add', 'sounds add', 'videos add', 'files add'));
		if (!defined($sMembershipActionConstant))
			return false;
		$aCheck = checkAction($_COOKIE['memberID'], constant($sMembershipActionConstant));
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }    
    function _actionApprovalSettings ($sUri, $sIsAllowedFuncName, $sMedia, $aMediaFields, $sTitle) {

       if (!($aDataEntry = $this->_preProductTabs($sUri, $sTitle)))
            return;

        if (!$this->$sIsAllowedFuncName($aDataEntry)) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }

        $this->_oTemplate->pageStart();

        $iEntryId = $aDataEntry[$this->_oDb->_sFieldId];

          $precheckStatusRes=mysql_query("SELECT Type FROM bx_groups_moderation WHERE GroupId = ".$iEntryId." AND ApproveStatus= 'on'");
          if($precheckStatusRes)
          {    while($precheckStatus=mysql_fetch_assoc($precheckStatusRes))
               {
                    foreach($precheckStatus as $val)
                    {
                        $precheck[$val] = 'true';
                    }
               }
          }else{
              $precheck = array('photo'=>'false','video'=>'false','journal'=>'false','profiles'=>'false','editedprofiles'=>'false','sections'=>'false');
          }
       
          bx_import('BxTemplFormView');
		$aForm = array(
			'form_attrs' => array(
                'id' => 'approve_data',
                'name' => 'approve_data',
                'action' => BX_DOL_URL_ROOT . 'approve_setting.php',
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            ),
            'inputs' => array (
                'caption_hidden' => array(
                    'type' => 'hidden',
                    'name' => 'agencyId',
                    'caption' => _t("Agency"),
                    'value' =>  $iEntryId,
                	'required' => 0,
                ),
               'caption_hidden1' => array(
                    'type' => 'hidden',
                    'name' => 'agencycallback',
                    'caption' => _t("Agency1"),
                    'value' =>  BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'approval_settings/' . $aDataEntry[$this->_oDb->_sFieldUri],
                	'required' => 0,
                ),
                'caption_photo' => array(
                    'type' => 'checkbox',
                    'name' => 'approve_media[]',
                    'caption' => _t("_td_approve_photo"),
                    'value' =>  'photo',
                	'required' => 0,
                    'checked'=>$precheck['photo'],
                ),
                'caption_video' => array(
                    'type' => 'checkbox',
                    'name' => 'approve_media[]',
                    'caption' => _t("_td_approve_video"),
                    'value' => 'video',
                	'required' => 0,
                    'checked'=>$precheck['video'],
                ),
                'caption_journals' => array(
                    'type' => 'checkbox',
                    'name' => 'approve_media[]',
                    'caption' => _t("_td_approve_journal"),
                    'value' => 'journal',
                    'required' => 0,
                    'checked'=>$precheck['journal'],
                ),
                'caption_profiles' => array(
                    'type' => 'checkbox',
                    'name' => 'approve_media[]',
                    'caption' => _t("_td_approve_profiles"),
                    'value' => 'profiles',
                    'required' => 0,
                    'checked'=>$precheck['profiles'],
                ),
		  'caption_editedprofiles' => array(
                    'type' => 'checkbox',
                    'name' => 'approve_media[]',
                    'caption' => _t("_td_approve_editedprofiles"),
                    'value' => 'editedprofiles',
                    'required' => 0,
                    'checked'=>$precheck['editedprofiles'],
                ),
		  'caption_sections' => array(
                    'type' => 'checkbox',
                    'name' => 'approve_media[]',
                    'caption' => _t("_td_approve_sections"),
                    'value' => 'sections',
                    'required' => 0,
                    'checked'=>$precheck['sections'],
                ),

                'save' => array(
                    'type' => 'submit',
                    'name' => 'save',
                    'value' => _t("_td_approve_save"),

                ),

            )
		);

		$oForm = new BxTemplFormView($aForm);
               
		$sCode .= $oForm->getCode();
                if(isset($_GET['msg']) && $_GET['msg']== 1)
                        echo MsgBox(_t('_Saved'));

        $sLinks = BxDolPageView::getBlockCaptionMenu(mktime(), array(
            'photos' => array('href' => BX_DOL_URL_ROOT . 'm/photos/albums/my/manage_objects/'. $aDataEntry[$this->_oDb->_sFieldUri].'/Agencyadmin', 'title' => _t('_moderate_photos'), 'onclick' => '', 'active' => $bSimAct),
            'videos' => array('href' => BX_DOL_URL_ROOT . 'm/videos/albums/my/manage_objects/'. $aDataEntry[$this->_oDb->_sFieldUri].'/Agencyadmin', 'title' => _t('_moderate_videos'), 'onclick' => '', 'active' => $bAdvAct),
             'journals' => array('href' => $sUrl . 'modules/boonex/blogs/blogs.php?action=agency_admin&mode=manage', 'title' => _t('_moderate_journals'), 'onclick' => '', 'active' => $bQuiAct),
		'manage_blocks'=> array('href' => BX_DOL_URL_ROOT  . 'm/aqb_pcomposer/approve_blocks','title' => _t('_manage_blocks'))
          
        ));
        echo DesignBoxContent ($sTitle, $sCode,1, $sLinks);
        $this->_oTemplate->addCss ('main.css');
        $this->_oTemplate->addCss ('forms_extra.css');
        $this->_oTemplate->pageCode($sTitle,false);
  
    
    }
}

?>
