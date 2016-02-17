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
define('BX_Profile_PAGE', 1);
bx_import('BxDolProfile');
bx_import('BxDolProfileFields');
require_once(BX_DIRECTORY_PATH_MODULES . 'aqb/pcomposer/classes/AqbPCPageView.php');
bx_import('BxDolPaginate');
bx_import('BxDolSubscription');
bx_import('BxDolCmtsProfile');

bx_import('BxTemplVotingView');

class BxBaseProfileView extends AqbPCPageView {
	var $oProfileGen;
	
	var $aConfSite;
	var $aConfDir;

	function BxBaseProfileView(&$oPr, &$aSite, &$aDir) {
		
		$this->oProfileGen = &$oPr;
		$this->aConfSite = $aSite;
		$this->aConfDir  = $aDir;
		parent::AqbPCPageView('profile');
	}
	


	function genBlock( $iBlockID, $aBlock, $bStatic = true, $sDynamicType = 'tab' ) {
        //--- Privacy for Profile page ---//
    	$oPrivacy = new BxDolPrivacy('sys_page_compose_privacy', 'id', 'user_id');

        $iPrivacyId = (int)$GLOBALS['MySQL']->getOne("SELECT `id` FROM `sys_page_compose_privacy` WHERE `user_id`='" . $this->oProfileGen->_iProfileID . "' AND `block_id`='" . $iBlockID . "' LIMIT 1");
        if($iPrivacyId != 0 && !$oPrivacy->check('view_block', $iPrivacyId, $this->iMemberID))
			return false;
		//--- Privacy for Profile page ---//


  			$_profile=getProfileInfo($this->oProfileGen->_iProfileID);
       		$_couplePro=getProfileInfo($_profile['Couple']);
            
      			 if($aBlock['Func']=='DescriptionPerson1'){
                		$aBlock['Caption']= 'About '.$_profile['FirstName'];
                 		}
                 	 if($aBlock['Func']=='DescriptionPerson2'){
                		$aBlock['Caption']= 'About '.$_couplePro['FirstName'];
                		}
			if($aBlock['Content']=='118'){
                		 $aBlock['Caption'] = 'Interests of '.$_profile['FirstName'];
                 		}
                     if($aBlock['Content']=='125'){
                		 $aBlock['Caption'] = 'Interests of '.$_couplePro['FirstName'];
                 		}
                     if($aBlock['Content']=='119'){
                		 $aBlock['Caption'] = 'Hobbies of '.$_profile['FirstName'];
                 		}
                     if($aBlock['Content']=='126'){
                		 $aBlock['Caption'] = 'Hobbies of '.$_couplePro['FirstName'];
                 		}                
		
		return parent::genBlock($iBlockID, $aBlock, $bStatic, $sDynamicType);
	}

	function getBlockCode_ActionsMenu() {
		return $this->oProfileGen->showBlockActionsMenu('', true);
	}
	function getBlockCode_FriendRequest() {
		return $this->oProfileGen->showBlockFriendRequest('', $this, true);
	}
    function getBlockCode_PFBlock( $iBlockID, $sContent ) {
                if((int)$sContent==75)
                    return $this->oProfileGen->showBlockAboutUs($iBlockID, 'About Us', $sContent, true);
                else
		return $this->oProfileGen->showBlockPFBlock($iBlockID, '', $sContent, true);
	}
	function getBlockCode_RateProfile() {
		return $this->oProfileGen->showBlockRateProfile('', true);
	}
	function getBlockCode_Friends() {
		return $this->oProfileGen->showBlockFriends('', $this, true);
	}
	function getBlockCode_MutualFriends() {
		return $this->oProfileGen->showBlockMutualFriends('', true);
	}
	function getBlockCode_Comments() {
		return $this->oProfileGen->showBlockComments('', true);
    }

    function getBlockCode_Cmts () {
        return $this->oProfileGen->showBlockCmts();
    }

 function __Clicktocall(){

      GLOBAL $site;
          GLOBAL $dir;
           defineMembershipActions( array('Click to Call') );
		$aCheck = checkAction((int)$_COOKIE['memberID'], BX_CLICK_TO_CALL, $isPerformAction);
        if(!$aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED)
			return false;
          if($_GET['Agency']){
            $sAgencyInfo=db_arr("SELECT bx_groups_main.author_id AS ID,bx_groups_main.title AS AgencyTitle, `Profiles`.*
                                FROM `Profiles`
                                JOIN bx_groups_main
                                WHERE Profiles.`ID` = bx_groups_main.author_id
                                AND bx_groups_main.uri = '".$_GET['Agency']."'");
          
        }else{
          $sAgencyInfo =db_arr("SELECT bx_groups_main.title AS AgencyTitle,  Profiles.*, Profiles.CONTACT_NUMBER AS ContactNumber, Profiles.CLICK_TO_CALL AS Clicktocall FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =".$_GET['ID']." AND Profiles.AdoptionAgency=bx_groups_main.id )");
        }
          $contactnumber = $sAgencyInfo['CONTACT_NUMBER'];
          $agencyname =$sAgencyInfo['title'] ;
          $clicktocall = $sAgencyInfo['CLICK_TO_CALL'];


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
                        if(request.responseText== "Call Connected")
                        {
                        document.getElementById("submitbtn").src="'.$site['base'].'/images/callMe_3.png";
                        }
                        else{
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
                         var url = "'.$site['url'].'clickto_proxy.php?app=ctc&id="+phonetocall+"&phone_to_call="+id+"&type=1&key=65687e8b8f39137da01defed01b2502c3faf3292&first_callerid="+2074532511+"&second_callerid="+2074532511+"&ref="+agencyname+"&page=ProfilePage";
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
'
       ;
        // return "hello";
       return $aVars;
 }

function getBlockCode_ProfileDail(){
return $this->oProfileGen->showProfileDail();

}
function getBlockCode_ProfileAgencyInfo(){
return $this->oProfileGen->showAgencyInfo();

}

    function __AgencyInfo () {
 
      //  bx_import($sClassName)
  $sAgencyInfo=db_arr("SELECT bx_groups_main.title AS AgencyTitle,  Profiles.* FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =".$_GET['ID']." AND Profiles.AdoptionAgency=bx_groups_main.id )");
        //$aAuthor = getProfileInfo($sAgencyInfo['Id']);

if($sAgencyInfo['City'] && $sAgencyInfo['State']){ $aaddress.= $sAgencyInfo['City'].",".$sAgencyInfo['State']  ; }
 if($sAgencyInfo['zip']){ $aaddress.= ','.$sAgencyInfo['zip']  ; }
          if($sAgencyInfo['City'] && $sAgencyInfo['State'] && $sAgencyInfo['Country']){ $aaddress.= ","._t($GLOBALS['aPreValues']['Country'][$sAgencyInfo['Country']]['LKey'])  ; }else{$aaddress.= _t($GLOBALS['aPreValues']['Country'][$sAgencyInfo['Country']]['LKey']);}
       
 if($sAgencyInfo['Fax_Number'])$_fax='Fax: '.format_phone($sAgencyInfo['Fax_Number']);
		 if($sAgencyInfo['Street_Address'])$street=$sAgencyInfo['Street_Address'];
		  //if($sAgencyInfo['Facebook'])$facebook='Facebook: '.$sAgencyInfo['Facebook'];
		  // if($sAgencyInfo['Twitter'])$twitter='Twitter: '.$sAgencyInfo['Twitter'];

if($sAgencyInfo['Facebook'])$facebook='<a href="'.$sAgencyInfo['Facebook'].'"> <div style="background-image:url(http://www.parentfinder.com/templates/base/images/face_book.png); width: 23px; height: 23px;margin-right:5px;float:left;"></div></a>';
if($sAgencyInfo['Twitter'])$twitter='<a href="'.$sAgencyInfo['Twitter'].'"> <div style="background-image:url(http://www.parentfinder.com/templates/base/images/twitter.png); width: 23px; height: 23px;margin-right:5px;float:left;"></div></a>';
		   
if(!$sAgencyInfo['WEB_URL'])$sAgencyInfo['WEB_URL']='';
         if($sAgencyInfo['CONTACT_NUMBER'])$phone='Tel: '.format_phone($sAgencyInfo['CONTACT_NUMBER']); else $phone='';

        // $aaddress = _t($GLOBALS['aPreValues']['Country'][$sAgencyInfo['Country']]['LKey']);
            $author_thumb = get_member_thumbnail($sAgencyInfo['ID'], 'none');



            $agencyname=$sAgencyInfo['AgencyTitle'];
            $fields = $sFields;
            $email=$sAgencyInfo['Email'];
            $phone= $phone;
            $address= $aaddress;
              $author_username = $sAgencyInfo['NickName'];
            $weburl = $sAgencyInfo['WEB_URL'];

       //return $oMain->_oTemplate->parseHtmlByName('entry_view_block_infoagency', $aVars);
       $code= '
        <div class="memberPic">
            <div class="thumbnail_block" style="float: none; width: 70px; height: 70px;">
	<div class="thumbnail_image" style="width: 68px; height: 68px;">
		'.$author_thumb.'
	</div>
        </div>

            <div class="thumb_username"><a href="'.$author_url.'">'.$author_username.'</a></div>
        </div>
        <div class="infoText">

            <div class="infoUnit" style="width:190px;padding-left:5px;">
                '.$agencyname.'
            </div>
			 <div class="infoUnit" style="width:190px;padding-left:5px;">
                '.$street.'
            </div>
            <div class="infoUnit" style="width:190px;padding-left:5px;">
                '.$address.'
            </div>
            <div class="infoUnit" style="width:190px;padding-left:5px;">
                '.$phone.'
            </div>
			 <div class="infoUnit" style="width:190px;padding-left:5px;">
                '.$_fax.'
            </div>
             <div class="infoUnit" style="width:190px; padding-left:5px;">

                '.$email.'
            </div>
            <div class="infoUnit" style="width:190px; padding-left:5px;">
                '.$weburl.'
            </div>
			<div class="infoUnit" style="width:190px;padding-left:5px;">
                '.$facebook.'
         </div>
			<div class="infoUnit" style="width:190px;padding-left:5px;">
                '.$twitter.'
            </div>
         </div>
';


       return $code;
    }
function getBlockCode_DescriptionPerson1() {
       return $this->oProfileGen->showDescriptionPerson1();
    }

function getBlockCode_DescriptionPerson2() {
       return $this->oProfileGen->showDescriptionPerson2();
    }

    






    function getBlockCode_Description() {
        global $oSysTemplate;

        $aProfileInfo = getProfileInfo($this -> oProfileGen -> _iProfileID);
        if(!$aProfileInfo['DescriptionMe']) {
            return;
        }

        $aTemplateKeys = array(
            'content' => $aProfileInfo['DescriptionMe'],
        );

        return $oSysTemplate -> parseHtmlByName('default_padding.html', $aTemplateKeys);
    }
    
    function _getBlockCaptionCode($iBlockID, $aBlock, $aBlockCode, $bStatic = true, $sDynamicType = 'tab') {
    	//--- Privacy for Profile page ---//
        $sCode = "";
	    /*if($this->iMemberID == $this->oProfileGen->_iProfileID) {
    	    $sAlt = "";
    	    $sCode = $GLOBALS['oSysTemplate']->parseHtmlByName('ps_page_chooser.html', array(
				'alt' => $sAlt,
    	    	'page_name' => $this->sPageName,
    	    	'profile_id' => $this->oProfileGen->_iProfileID,
    	    	'block_id' => $iBlockID
    	    ));
		}*/
		//--- Privacy for Profile page ---//
		
		return $sCode . parent::_getBlockCaptionCode($iBlockID, $aBlock, $aBlockCode, $bStatic, $sDynamicType);
    }
}

class BxBaseProfileGenerator extends BxDolProfile {
	var $oTemplConfig;
	//var $sColumnsOrder;
	var $oPF; // profile fields object
	var $aPFBlocks; //profile fields blocks
	var $aCoupleMutualItems;
    var $bPFEditable = false;
    
    var $iCountMutFriends;
    var $iFriendsPerPage;

	function BxBaseProfileGenerator( $ID ) {
		global $site;

		$this->aMutualFriends = array();

		BxDolProfile::BxDolProfile( $ID, 0 );

        $this->oVotingView = new BxTemplVotingView ('profile', (int)$ID);
        $this->oCmtsView = new BxDolCmtsProfile ('profile', (int)$ID);
		
		//$this->ID = $this->_iProfileID;
		
		$this->oTemplConfig = new BxTemplConfig( $site );
		//$this->sColumnsOrder = getParam( 'profile_view_cols' );
		//INSERT INTO `sys_options` VALUES('profile_view_cols', 'thin,thick', 0, 'Profile view columns order', 'digit', '', '', NULL, '');
		
		if( $this->_iProfileID ) {
			$this->getProfileData();
			
            if( $this->_aProfile ) {

                if( isMember() ) {
					$iMemberId = getLoggedId();
					if( $iMemberId == $this->_iProfileID ) {
						$this->owner = true;
                        
                        if ($_REQUEST['editable']) {
                            $this->bPFEditable = true;
                            $iPFArea = 2; // Edit Owner
                        } else
                            $iPFArea = isAdmin() ? 5 : 6; // View Owner
					}else {
                        $iPFArea = isAdmin() ? 5 : 6;
                    }
				} 
				elseif( isModerator() )
					$iPFArea = 7;
				else
					$iPFArea = 8;

                $this->oPF = new BxDolProfileFields( $iPFArea );
				if( !$this->oPF->aBlocks)
					return false;
//--- AQB Profile Types Splitter ---//
	if (BxDolRequest::serviceExists('aqb_pts', 'profile_view_filter')) {
		BxDolService::call('aqb_pts', 'profile_view_filter', array($this->_iProfileID, &$this -> oPF ->aBlocks, &$this->oPF->aArea));
	}
	//--- AQB Profile Types Splitter ---//
				$this->aPFBlocks = $this->oPF->aBlocks;
				
				if( $this->bCouple )
					$this->aCoupleMutualItems = $this->oPF->getCoupleMutualFields();
				
				$this->iFriendsPerPage = (int)getParam('friends_per_page');
				$this->FindMutualFriends($iMemberId, $_GET['page'], $_GET['per_page']);
				
			} else
				return false;
		} else
			return false;
	}

	function genColumns($sOldStyle = false) {
		ob_start();

		?>
		<div id="thin_column">
			<? $this->showColumnBlocks( 1, $sOldStyle ); ?>
		</div>

		<div id="thick_column">
			<? $this->showColumnBlocks( 2, $sOldStyle ); ?>
		</div>
		<?

		return ob_get_clean();
	}
	
	function showColumnBlocks( $column, $sOldStyle = false ) {
		$sVisible = ( $GLOBALS['logged']['member'] ) ? 'memb': 'non';

		$sAddSQL = ($sOldStyle == true) ? " AND `Func`='PFBlock' " : '';
		$rBlocks = db_res( "SELECT * FROM `sys_page_compose` WHERE `Page` = 'profile' AND `Column`=$column AND FIND_IN_SET( '$sVisible', `Visible` ) {$sAddSQL} ORDER BY `Order`" );
		while( $aBlock = mysql_fetch_assoc( $rBlocks ) ) {
			$func = 'showBlock' . $aBlock['Func'];
			$this->$func( $aBlock['Caption'], $aBlock['Content'] );
		}
	}

	function showBlockEcho( $sCaption, $sContent ) {
		echo DesignBoxContent( _t($sCaption), $sContent, 1 );
	}

function popup($title,$name)
{
 
GLOBAL $site;  
$block = <<<EOF
 
<style>
		.{$title}black_overlay{
			display: none;
			position: absolute;
			top: 0%;
			left: 0%;
			width: 720px;
			height: 360px;
			
			z-index:1001;
			-moz-opacity: 0.8;
			opacity:.80;
			filter: alpha(opacity=80);
		}
		.{$title}white_content {
			display: none;
			position: fixed;
			top: 25%;
			left: 25%;
			width: 750px;
			height: 450px;
			-moz-border-radius: 10px 10px 10px 10px;
			border: 18px solid #A1A1A1;
			background-color: white;
			z-index:1002;
			overflow: auto;
		}
                 input[type="stdtext"] {
                font-family: Verdana, Arial, Helvetica, sans-serif;
                font-size: 9px;
                border: 1px solid #BDBEC0;
                height: 20px;
                width: 200px;
                -moz-border-radius: 5px 5px 5px 5px;
                }
	</style>
<script>
    clearCache('all');
    clearCache('db');
    clearCache('pb');
    clearCache('template');
    clearCache('js_css');
    clearCache('users');
    </script>
              
 <a href = "javascript:void(0)" onclick = "document.getElementById('{$title}light').style.display='block';document.getElementById('{$title}fade').style.display='block';document.getElementById('{$title}_ifr').style.width='100%';document.getElementById('{$title}_tbl').style.width='100%'"><span style="width:100%; height:40px; background-color:#9FD6FD; border: solid; color:#02618E; font-family:Georgia; font-size:15px; padding:10px;">Please click Edit to enter data to this block.</span></a>
    <div id="{$title}light" class="{$title}white_content">
        <div id="{$title}fade" class="{$title}black_overlay">
              <div style="border: 1px solid #BDBEC0;  height: 25px; margin-bottom: 2px; font-size: 13px; width: 748px; background-image: url('{$site['url']}templates/base/images/buttonBackground.jpg'); border: 1px solid #BDBEC0; margin-bottom: 4px;"><div style="float:left;font-size: 15px; padding: 5px;" ><b> Standard Block Editor : </b></div><div style="float:right;"><a href = "javascript:void(0)" onclick = "document.getElementById('{$title}light').style.display='none';document.getElementById('{$title}fade').style.display='none';"><img src='{$site['url']}templates/base/images/close_popup.gif'/></a></div></div>
                     <form method="POST" action="blockupdate.php">
                                <table BORDER=1  RULES=ROWS FRAME=BOX  style="border: 1px solid #BDBEC0; height: 419px; width: 750px; padding=5px;"> 
                                        <tr>
                                            <td style="padding:5px;">
                                                Title : 
                                            </td>
                                            <td style="padding:8px;"><input type="stdtext" name="title"  maxlength="100" size="20" value="{$name}"></td>
                                        </tr>
                                        <tr>
                                            <td style="padding:5px;"> Content: </td>
                                            <td class="value">

                                                <div class="clear_both"></div>
                                                                        <div class="input_wrapper input_wrapper_textarea"  style="width:630px;">
                                                    <div class="input_border">
                                                                    <textarea  counter="true" class="form_input_textarea form_input_html" style="width: 630px; height: 250px; " name="{$title}"></textarea>
                                                    </div>

                                                </div>
                                                <div class="clear_both"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding:5px;">
                                            </td>
                                            <td style="padding:5px;">
						<input type="submit" id="draft"  name="draft" style="color: grey;cursor: pointer;font-weight:bold;" value="Draft">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="submit" id="Submit"  name="Submit" style="color: grey;cursor: pointer;font-weight:bold;padding:4px 15px;" value="Submit">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="submit"  value="Cancel"  name="Cancel" id="Cancel" style="color: grey;cursor: pointer;font-weight:bold;padding:4px 15px;">
                                            </td>
                                        </tr>
                                   </table>
                     </form>
        </div>
    </div>
              

EOF;
    
return $block;   
}

function editpopup($title,$name,$content)
{
 
GLOBAL $site;  
$block = <<<EOF
 
<style>
		.{$title}black_overlay{
			display: none;
			position: absolute;
			top: 0%;
			left: 0%;
			width: 720px;
			height: 360px;
			
			z-index:1001;
			-moz-opacity: 0.8;
			opacity:.80;
			filter: alpha(opacity=80);
		}
		.{$title}white_content {
			display: none;
			position: fixed;
			top: 25%;
			left: 25%;
			width: 750px;
			height: 450px;
			-moz-border-radius: 10px 10px 10px 10px;
			border: 18px solid #A1A1A1;
			background-color: white;
			z-index:1002;
			overflow: auto;
		}
                input[type="stdtext"] {
                font-family: Verdana, Arial, Helvetica, sans-serif;
                font-size: 9px;
                border: 1px solid #BDBEC0;
                height: 20px;
                width: 200px;
                -moz-border-radius: 5px 5px 5px 5px;
                }
	</style>
   <script>
    clearCache('all');
    clearCache('db');
    clearCache('pb');
    clearCache('template');
    clearCache('js_css');
    clearCache('users');
    </script>           
 <a href = "javascript:void(0)" onclick = "document.getElementById('{$title}light').style.display='block';document.getElementById('{$title}fade').style.display='block';document.getElementById('{$title}_ifr').style.width='100%';document.getElementById('{$title}_tbl').style.width='100%'"><span>Edit</span></a>
    <div id="{$title}light" class="{$title}white_content">
        <div id="{$title}fade" class="{$title}black_overlay">
              <div style="border: 1px solid #BDBEC0;  height: 25px; margin-bottom: 2px; font-size: 13px; width: 748px; background-image: url('{$site['url']}templates/base/images/buttonBackground.jpg'); border: 1px solid #BDBEC0; margin-bottom: 4px;"><div style="float:left;font-size: 15px; padding: 5px;" ><b> Standard Block Editor : </b></div><div style="float:right;"><a href = "javascript:void(0)" onclick = "document.getElementById('{$title}light').style.display='none';document.getElementById('{$title}fade').style.display='none';"><img src='{$site['url']}templates/base/images/close_popup.gif'/></a></div></div>
                     <form method="POST" action="blockupdate.php">
                                <table BORDER=1  RULES=ROWS FRAME=BOX  style="border: 1px solid #BDBEC0; height: 419px; width: 750px; padding=5px;"> 
                                        <tr>
                                            <td style="padding:5px;">
                                                Title : 
                                            </td>
                                            <td style="padding:8px;"><input type="stdtext" name="title"  maxlength="100" size="20" value="{$name}"></td>
                                        </tr>
                                        <tr>
                                            <td style="padding:5px;"> Content: </td>
                                            <td class="value">

                                                <div class="clear_both"></div>
                                                                        <div class="input_wrapper input_wrapper_textarea"  style="width:630px;">
                                                    <div class="input_border">
                                                                    <textarea  counter="true" class="form_input_textarea form_input_html" style="width: 630px; height: 250px; " name="{$title}">{$content}</textarea>
                                                    </div>

                                                </div>
                                                <div class="clear_both"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding:5px;">
                                            </td>
                                            <td style="padding:5px;">
						<input type="submit" id="draft"  name="draft" style="color: grey;cursor: pointer;font-weight:bold;" value="Draft">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="submit" id="Submit"  name="Submit" style="color: grey;cursor: pointer;font-weight:bold;padding:4px 15px;" value="Submit">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="submit"  value="Cancel"  name="Cancel" id="Cancel" style="color: grey;cursor: pointer;font-weight:bold;padding:4px 15px;">
                                            </td>
                                        </tr>
                                   </table>
                     </form>
        </div>
    </div>
              

EOF;
    
return $block;   
}

	function showDescriptionPerson1()
	{
		$bMayEdit = ((isMember() || isAdmin()) && ($this->_iProfileID == getLoggedId()));
		
		$field_catcher="DescriptionMe";
		$sRet=$this->_aProfile['DescriptionMe'];
$title = 'PFblockstd_description1';
$name = 'Parent1_description';
$content = $sRet;
$link = $this->editpopup($title, $name , $content);

if(!$this->_aProfile['DescriptionMe'] && ($this->_iProfileID == getLoggedId()||isAdmin()))
{
	//return '';
	//return '<div style="width:auto; height:40px; background-color:#9FD6FD; color:#02618E; font-family:Georgia; font-size:18px; padding:10px;">Please click <a href="BlockEdit.php?name=DescriptionMe">Edit</a> to enter data to this block.</div>';


return $this->popup($title,$name);
}              
if(!($this->_aProfile['DescriptionMe']))
	return '';

		 if($bMayEdit && $sRet)
			return array(
                    '<div class=" bx_sys_default_padding"><div style="float:right;">' . $link . '</div><div style="float:left;">' . $sRet . '</div></div>',
                    array(
                       
                        ),
                    
                    array(),
                    '',
                );
 else
			return array(
                    '<div class=" bx_sys_default_padding">' . $sRet . '</div>'
                );


		//return DesignBoxContent( _t($sCaption), $this->_aProfile['DescriptionMe'], 1 );

	}
	function showDescriptionPerson2()
	{	    
		$_cPro=''; 
		$bMayEdit = ((isMember() || isAdmin()) && ($this->_iProfileID == getLoggedId()||isAdmin()));
		if(!$this->_aProfile['Couple']) return '';
		$_cPro=getProfileInfo($this->_aProfile['Couple']);
		$field_catcher="DescriptionMe";	
		$sRet=$_cPro['DescriptionMe']; 
		$content = $sRet;
		$title = 'PFblockstd_description2';
		$name = 'Parent2_description';
		$link = $this->editpopup($title, $name , $content);

               
if(!$_cPro['DescriptionMe'] && ($this->_iProfileID == getLoggedId()))
{
	//return '';
	//return '<div style="width:auto; height:40px; background-color:#9FD6FD; color:#02618E; font-family:Georgia; font-size:18px; padding:10px;">Please click <a href="BlockEdit.php?namedes=DescriptionMe&id='.$_cPro['ID'].'">Edit</a> to enter data to this block.</div>';

return $this->popup($title,$name);


} 
    

 if(!$_cPro['DescriptionMe'])
                    return '';

		  if($bMayEdit && $sRet)
			return array(
                    '<div class=" bx_sys_default_padding"><div style="float:right;">' . $link . '</div><div style="float:left;">' . $sRet . '</div></div>',
                    array(
                       
                        ),
                    
                    array(),

                    '',
                );
		  else
			return array(
                    '<div class=" bx_sys_default_padding">' . $sRet . '</div>'
                );

		//$sCaption= "Description: ".$_cPro['FirstName']." ".$_cPro['LastName'];
		//echo DesignBoxContent( _t($sCaption), $sRet, 1 );

	}
	function calc_age($birth_date){
		if ( $birth_date == "0000-00-00" )
		return _t("_uknown");

	$bd = explode( "-", $birth_date );
	$age = date("Y") - $bd[0] - 1;

	$arr[1] = "m";
	$arr[2] = "d";

	for ( $i = 1; $arr[$i]; $i++ ) {
		$n = date( $arr[$i] );
		if ( $n < $bd[$i] )
			break;
		if ( $n > $bd[$i] ) {
			++$age;
			break;
		}
	}

	return $age;
	}
	function showBlockPFBlock( $iPageBlockID, $sCaption, $sContent, $bNoDB = false ) {		
        $iPFBlockID = (int)$sContent;
                
        $bMayEdit = ((isMember() || isAdmin()) && ($this->_iProfileID == getLoggedId()));
 	

	 $_cPro=getProfileInfo($this->_aProfile['Couple']);

               if($sContent==75){
            $sCaption='About Us';
      $sRet.= $this->_aProfile['DescriptionMe'];
        }
        $sRet .= $this->getViewValuesTable($iPageBlockID, $iPFBlockID);
        
        if($sContent == 109)
        {
           $field_catcher= 'DearBirthParent';
		 $sRet=$this->_aProfile['DearBirthParent'];
		if(!$this->_aProfile['DearBirthParent']&& ($this->_iProfileID == getLoggedId()||isAdmin()))
		{
			//return '';
			//return '<div style="width:auto; height:40px; background-color:#9FD6FD; color:#02618E; font-family:Georgia; font-size:18px; padding:10px;">Please click <a href="BlockEdit.php?name=DearBirthParent">Edit</a> to enter data to this block.</div>';
                         $title = 'PFblockstd_DearBirthParent';
                         $name = 'DearBirthParent';
                         return $this->popup($title,$name); 
		}

		if(!$this->_aProfile['DearBirthParent']){
             	$sRet='';	
		}
            
        }else if($sContent == 118)
        {
	    $field_catcher= 'Interests';

		if(!$this->_aProfile['Interests'] && ($this->_iProfileID == getLoggedId()||isAdmin()))
		{
			//return '';
			//return '<div style="width:auto; height:40px; background-color:#9FD6FD; color:#02618E; font-family:Georgia; font-size:18px; padding:10px;">Please click <a href="BlockEdit.php?name=Interests">Edit</a> to enter data to this block.</div>';
                    $title = 'PFblockstd_Interests1';
                    $name = 'Parent1_Interests';
                    return $this->popup($title,$name);
		} 

             $sRet=$this->_aProfile['Interests'];
        }

	else if($sContent == 125)
        {
             					
	if($this->_aProfile['Couple'] !=0  ){
		$field_catcher= 'Interests';
		if(!$_cPro['Interests'] && ($this->_iProfileID == getLoggedId()||isAdmin()))
		{
			//return '';
			//return '<div style="width:auto; height:40px; background-color:#9FD6FD; color:#02618E; font-family:Georgia; font-size:18px; padding:10px;">Please click <a href="BlockEdit.php?namedes=Interests&id='.$_cPro['ID'].'">Edit</a> to enter data to this block.</div>';
                    $title = 'PFblockstd_Interests2';
                    $name = 'Parent2_Interests';
                    return $this->popup($title,$name);
		} 

             $sRet=$_cPro['Interests'];
		}

        }
	else if($sContent == 126)
        {
		if($this->_aProfile['Couple'] !=0  ){
             						$field_catcher= 'Hobbies';
		if(!$_cPro['Hobbies'] && ($this->_iProfileID == getLoggedId()||isAdmin()))
		{
			//return '';
			//return '<div style="width:auto; height:40px; background-color:#9FD6FD; color:#02618E; font-family:Georgia; font-size:18px; padding:10px;">Please click <a href="BlockEdit.php?namedes=Hobbies&id='.$_cPro['ID'].'">Edit</a> to enter data to this block.</div>';
                    $title = 'PFblockstd_Hobbies2';
                    $name = 'Parent2_Hobbies';
                    return $this->popup($title,$name);	
		} 

             $sRet=$_cPro['Hobbies'];
		}
        }

	else if($sContent == 119)
        {
		 
					           $field_catcher= 'Hobbies';
		if(!$this->_aProfile['Hobbies'] && ($this->_iProfileID == getLoggedId()||isAdmin()))
		{
			//return '';
			//return '<div style="width:auto; height:40px; background-color:#9FD6FD; color:#02618E; font-family:Georgia; font-size:18px; padding:10px;">Please click <a href="BlockEdit.php?name=Hobbies">Edit</a> to enter data to this block.</div>';
		    $title = 'PFblockstd_Hobbies1';
                    $name = 'Parent1_Hobbies';
                    return $this->popup($title,$name);	
		} 
             $sRet=$this->_aProfile['Hobbies'];
							
        }else if($sContent == 117)
        {
		$field_catcher= 'About_our_home';
		if(!$this->_aProfile['About_our_home'] && ($this->_iProfileID == getLoggedId()||isAdmin()))
		{
			//return '';
			//return '<div style="width:auto; height:40px; background-color:#9FD6FD; color:#02618E; font-family:Georgia; font-size:18px; padding:10px;">Please click <a href="BlockEdit.php?name=About_our_home">Edit</a> to enter data to this block.</div>';

		    $title = 'PFblockstd_About_our_home';
                    $name = 'About_our_home';
                    return $this->popup($title,$name);

		} 
             $sRet=$this->_aProfile['About_our_home'];        
	  }else if($sContent == 121){
		if($this->_aProfile['Couple'])
		{$_coupleProf=getProfileInfo($this->_aProfile['Couple']);
        $sRet=<<<EOH
    <div style="width: auto;height: auto; background-color:#C2D3FF;padding:20px;">
          <div style="width: auto;height: auto; background-color:#EEF6FF;">
              <table width="100%" border="1" style="border: solid 0.2px #375778;" cellpadding="0" cellspacing="0">
              <tr style=" background-color: #375778; color: #EEF6FF; font-size: 16px; font-weight: bold;">
                  <td>
                  </td>
                  <td align="center">
                      &nbsp;   {$this->_aProfile['FirstName']}
                  </td>
                  <td align="center">
                    &nbsp;   {$_coupleProf['FirstName']}
                  </td>
              </tr>
EOH;
      if($this->_aProfile['DateOfBirth']!='0000-00-00'){
$sRet.=<<<EOH
              <tr>
                  <td align="right" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;width: 30%;">
                      &nbsp;Age:
                  </td>
                  <td align="center">
                       &nbsp; {$this->calc_age($this->_aProfile['DateOfBirth'])} Years
                  </td>
                  <td align="center">
                      &nbsp; {$this->calc_age($_coupleProf['DateOfBirth'])} Years

                  </td>
              </tr>
EOH;
      }    
$sRet.=<<<EOH
      
              <tr>
                  <td align="right" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Education:
                  </td>
                  <td align="center">
                    &nbsp; {$this->_aProfile['Education']}
                  </td>
                  <td align="center">
                     &nbsp; {$_coupleProf['Education']}
                  </td>
              </tr>
              <tr >
                  <td align="right" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Profession:
                  </td>
                  <td align="center">
                          &nbsp; {$this->_aProfile['Occupation']}
                  </td>
                  <td align="center">
                          &nbsp; {$_coupleProf['Occupation']}
                  </td>
              </tr>
              <tr >
                  <td align="right" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Ethnicity:
                  </td>
                  <td align="center">
                         &nbsp; {$this->_aProfile['Ethnicity']}
                  </td>
                  <td align="center">
                       &nbsp; {$_coupleProf['Ethnicity']}
                  </td>
              </tr>
              <tr >
                  <td align="right" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Religion:
                  </td>
                  <td align="center">
                          &nbsp; {$this->_aProfile['Religion']}
                  </td>
                  <td align="center">
                       &nbsp; {$_coupleProf['Religion']}
                  </td>
              </tr>
               <tr >
                  <td align="right" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Smoking:
                  </td>
                  <td align="center">
                         &nbsp; {$this->_aProfile['Smoking']}
                  </td>
                  <td align="center">
                       &nbsp; {$_coupleProf['Smoking']}
                  </td>
              </tr>
              
              <tr >

                  <td align="center" colspan="3">
                      &nbsp;
                  </td>
              </tr>
                 <tr >
                  <td align="right" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;State:
                  </td>
                  <td align="center" colspan="2">
                       &nbsp; {$this->_aProfile['State']}
                  </td>

              </tr>
              <tr >
                  <td align="right" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Married:
                  </td>
                  <td align="center" colspan="2">
                            &nbsp; {$this->_aProfile['YearsMarried']}
                  </td>

              </tr>
              <tr >
                  <td align="right" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Residency:
                  </td>
                  <td align="center" colspan="2">
                          &nbsp; {$this->_aProfile['Residency']}
                  </td>

              </tr>
              <tr >
                  <td align="right" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Neighborhood:
                  </td>
                  <td align="center" colspan="2">
                           &nbsp; {$this->_aProfile['Neighborhood']}
                  </td>

              </tr>
              <tr >
                  <td align="right" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Family Structure:
                  </td>
                  <td align="center" colspan="2">
                           &nbsp; {$this->_aProfile['FamilyStructure']}
                  </td>

              </tr>
              <tr >
                  <td align="right" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Pet(s):
                  </td>
                  <td align="center" colspan="2">
                        &nbsp; {$this->_aProfile['Pet']}
                  </td>

              </tr>
</table>
          </div>
      </div>
EOH;
 }else{
	        $sRet=<<<EOH
    <div style="width: auto;height: auto; background-color:#C2D3FF;padding:20px;">
          <div style="width: auto;height: auto; background-color:#EEF6FF;">
              <table width="100%" border="1" style="border: solid 0.2px #375778;" cellpadding="0" cellspacing="0">
              <tr style=" background-color: #375778; color: #EEF6FF; font-size: 16px; font-weight: bold;">
                  <td>
                  </td>
                  <td align="center">
                      &nbsp;   {$this->_aProfile['FirstName']}
                  </td>
                  <td align="center">
                    &nbsp;   {$_coupleProf['FirstName']}
                  </td>
              </tr>
EOH;
     if($this->_aProfile['DateOfBirth']!='0000-00-00'){
$sRet.=<<<EOH
              <tr>
                  <td align="right" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;width: 30%;">
                      &nbsp;Age:
                  </td>
                  <td align="center" colspan="2">
                       &nbsp; {$this->calc_age($this->_aProfile['DateOfBirth'])} Years
                  </td>
                
              </tr>
EOH;
      }    
$sRet.=<<<EOH
              <tr>
                  <td align="right" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Education:
                  </td>
                  <td align="center" colspan="2">
                    &nbsp; {$this->_aProfile['Education']}
                  </td>
                
              </tr>
              <tr >
                  <td align="right" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Profession:
                  </td>
                  <td align="center" colspan="2">
                          &nbsp; {$this->_aProfile['Occupation']}
                  </td>
                  
              </tr>
              <tr >
                  <td align="right" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Ethnicity:
                  </td>
                  <td align="center" colspan="2">
                         &nbsp; {$this->_aProfile['Ethnicity']}
                  </td>
                  
              </tr>
              <tr >
                  <td align="right" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Religion:
                  </td>
                  <td align="center" colspan="2">
                          &nbsp; {$this->_aProfile['Religion']}
                  </td>
                  
              </tr>
               <tr >
                  <td align="right" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Smoking:
                  </td>
                  <td align="center" colspan="2">
                         &nbsp; {$this->_aProfile['Smoking']}
                  </td>
                  
              </tr>
              
              <tr >

                  <td align="center" colspan="3">
                      &nbsp;
                  </td>
              </tr>
                 <tr >
                  <td align="right" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;State:
                  </td>
                  <td align="center" colspan="2">
                       &nbsp; {$this->_aProfile['State']}
                  </td>

              </tr>
              <tr >
                  <td align="right" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Married:
                  </td>
                  <td align="center" colspan="2">
                            &nbsp; {$this->_aProfile['YearsMarried']}
                  </td>

              </tr>
              <tr >
                  <td align="right" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Residency:
                  </td>
                  <td align="center" colspan="2">
                          &nbsp; {$this->_aProfile['Residency']}
                  </td>

              </tr>
              <tr >
                  <td align="right" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Neighborhood:
                  </td>
                  <td align="center" colspan="2">
                           &nbsp; {$this->_aProfile['Neighborhood']}
                  </td>

              </tr>
              <tr >
                  <td align="right" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Family Structure:
                  </td>
                  <td align="center" colspan="2">
                           &nbsp; {$this->_aProfile['FamilyStructure']}
                  </td>

              </tr>
              <tr >
                  <td align="right" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Pet(s):
                  </td>
                  <td align="center" colspan="2">
                        &nbsp; {$this->_aProfile['Pet']}
                  </td>

              </tr>
</table>
          </div>
      </div>
EOH;
}       }else{
              $sRet = $this->getViewValuesTable($iPageBlockID, $iPFBlockID);
        }
		
	 

	if ($bNoDB) {
            if($bMayEdit && $sRet)
                 if($sContent == 109){
						$title = 'PFblockstd_DearBirthParent';
                         			$name = 'DearBirthParent';
						$content = $sRet;
						$link = $this->editpopup($title, $name , $content);
 						
    			return array(
                    '<div class=" bx_sys_default_padding"><div style="float:right;">' . $link . '</div><div style="float:left;">' . $sRet . '</div></div>',
                    array(
                       
                        ),
                    
                    array(), 
                    '',
                );}elseif($sContent == 119){
						$title = 'PFblockstd_Hobbies1';
                    			$name = 'Parent1_Hobbies';
						$content = $sRet;
						$link = $this->editpopup($title, $name , $content);

    			return array(
                    '<div class=" bx_sys_default_padding"><div style="float:right;">' . $link . '</div><div style="float:left;">' . $sRet . '</div></div>',
                    array(
                       
                        ),
                    
                    array(),
                    '',
                );}else if($sContent == 117){
						$title = 'PFblockstd_About_our_home';
                    			$name = 'About_our_home';
						$content = $sRet;
						$link = $this->editpopup($title, $name , $content);

    			return array(
                    '<div class=" bx_sys_default_padding"><div style="float:right;">' . $link . '</div><div style="float:left;">' . $sRet . '</div></div>',
                    array(
                       
                        ),
                    
                    array(),
 
                    '',
                );}else if($sContent == 118){
						 $title = 'PFblockstd_Interests1';
                    			 $name = 'Parent1_Interests';
						 $content = $sRet;
						 $link = $this->editpopup($title, $name , $content);

    			return array(
                    '<div class=" bx_sys_default_padding"><div style="float:right;">' . $link . '</div><div style="float:left;">' . $sRet . '</div></div>',
                    array(
                       
                        ),
                    
                    array(), 
                    '',
                );}else if($sContent == 125){
						 $title = 'PFblockstd_Interests2';
                    			 $name = 'Parent2_Interests';
						 $content = $sRet;
						 $link = $this->editpopup($title, $name , $content);

    			return array(
                    '<div class=" bx_sys_default_padding"><div style="float:right;">' . $link . '</div><div style="float:left;">' . $sRet . '</div></div>',
                    array(
                       
                        ),
                    
                    array(), 
                    '',
                );}else if($sContent == 126){
						$title = 'PFblockstd_Hobbies2';
                    			$name = 'Parent2_Hobbies';
						 $content = $sRet;
						 $link = $this->editpopup($title, $name , $content);


    			return array(
                    '<div class=" bx_sys_default_padding"><div style="float:right;">' . $link . '</div><div style="float:left;">' . $sRet . '</div></div>',
                    array(
                       
                        ),
                    
                    array(), 
                    '',
                );}else{
                   return array(
                    '<div class=" bx_sys_default_padding">' . $sRet . '</div>',
                    array(
                        _t('_Edit') => array(
                            //'caption' => _t('_Edit'),
                            
                            'href' => 'pedit.php?ID=' . $this->_iProfileID,

                            'dynamicPopup' => false,
                            'active' => $this->bPFEditable,
                        ),
                    ),
                    array(),
                    '',
                );
                }
            else
                return empty($sRet) ? $sRet : array('<div class=" bx_sys_default_padding">' . $sRet . '</div>', array(), array(), '');
		} 
        else
            echo DesignBoxContent( _t($sCaption), $sRet, 1 );
	}
function createDescBlock($sDesc,$header=false){
    if($sDesc)
    {
        $sRet.=<<<EOH
        <div class="form_advanced_wrapper view_profile_form_wrapper">
            <table cellspacing="0" cellpadding="0" class="form_advanced_table">


                <tbody>
                <tr>

                    <td class="value" {$header}>
                        <div class="clear_both"></div>
                            <div class="input_wrapper input_wrapper_value">
EOH;
        $sRet.=$sDesc;
$sRet.=<<<EOH
                          <div class="input_close input_close_value"></div>
                        </div>




                        <div class="clear_both"></div>
                    </td>

                </tr>

                </tbody>

            </table>
            </div>
EOH;
return $sRet;
    }
}
function showBlockAboutUS( $iPageBlockID, $sCaption, $sContent, $bNoDB = false ) {
        $iPFBlockID = (int)$sContent;

        $bMayEdit = ((isMember() || isAdmin()) && ($this->_iProfileID == getLoggedId()));
        
      //  $sCaption='About Us';
		$sRet.=$this->createDescBlock('Description for '.$this->_aProfile['FirstName'],'style="background-color:#CCCCCC; font-size:12px;"');
        $sRet.=$this->createDescBlock($this->_aProfile['DescriptionMe']);
        $sRet .= $this->getABoutUsView($iPageBlockID, $iPFBlockID);
        if ($bNoDB) {
            if($bMayEdit && $sRet)
    			return array(
                    '<div class=" bx_sys_default_padding">' . $sRet . '</div>',
                    array(
                        _t('_Edit') => array(
                            //'caption' => _t('_Edit'),
                            'href' => 'pedit.php?ID=' . $this->_iProfileID,
                            'dynamicPopup' => false,
                            'active' => $this->bPFEditable,
                        ),
                    ),
                    array(),
                    '',
                );
            else
                return empty($sRet) ? $sRet : array('<div class=" bx_sys_default_padding">' . $sRet . '</div>', array(), array(), '');
		}
        else
            echo DesignBoxContent( $sCaption, $sRet, 1 );
}
function getABoutUsView($iPageBlockID, $iPFBlockID) {
		if( !isset( $this->aPFBlocks[$iPFBlockID] ) or empty( $this->aPFBlocks[$iPFBlockID]['Items'] ) )
			return '';
        
        // get parameters
        $bCouple        = $this->bCouple;
        $aItems         = $this->aPFBlocks[$iPFBlockID]['Items'];
        
        // collect inputs
        $aInputs = array();
        $aInputsSecond = array();
        
        foreach( $aItems as $aItem ) {
            $sItemName = $aItem['Name'];
            $sValue1   = $this->_aProfile[$sItemName];
            $sValue2   = $this->_aCouple[$sItemName];
            
            if($aItem['Name'] == 'DescriptionMe' ||$aItem['Name'] == 'Headline'||$aItem['Name'] == 'Sex')
                continue;
            if ($aItem['Name'] == 'Age') {
                $sValue1 = $this->_aProfile['DateOfBirth'];
                $sValue2 = $this->_aCouple['DateOfBirth'];
            }
            
            if ($this->bPFEditable) {
                $aParams = array(
                    'couple' => $this->bCouple,
                    'values' => array(
                        $sValue1,
                        $sValue2
                    ),
                    'profile_id' => $this->_iProfileID,
                );
                
                $aInputs[] = $this->oPF->convertEditField2Input($aItem, $aParams, 0);
                
                if ($aItem['Type'] == 'pass') {
                    $aItem_confirm = $aItem;
                    
                    $aItem_confirm['Name']    .= '_confirm';
                    $aItem_confirm['Caption']  = '_Confirm password';
                    $aItem_confirm['Desc']     = '_Confirm password descr';
                    
                    $aInputs[] = $this->oPF->convertEditField2Input($aItem_confirm, $aParams, 0);
                    
                    if ($this->bCouple and !in_array($sItemName, $this->aCoupleMutualItems))
                        $aInputsSecond[] = $this->oPF->convertEditField2Input($aItem_confirm, $aInputParams, 1);
                }
                
                if ($this->bCouple and !in_array($sItemName, $this->aCoupleMutualItems) and $sValue2) {
                    $aInputsSecond[] = $this->oPF->convertEditField2Input($aItem, $aParams, 1);
                }
            } else {
                if ($sValue1 || $aItem['Type'] == 'bool') { //if empty, do not draw
                    $aInputs[] = array(
                        'type'    => 'value',
                        'name'    => $aItem['Name'],
                        'caption' => _t($aItem['Caption']),
                        'value'   => $this->oPF->getViewableValue($aItem, $sValue1),
                    );
                }
                
                if ($this->bCouple and !in_array($sItemName, $this->aCoupleMutualItems) and ($sValue2 || $aItem['Type'] == 'bool')) {
                    $aInputsSecond[] = array(
                        'type'    => 'value',
                        'name'    => $aItem['Name'],
                        'caption' => _t($aItem['Caption']),
                        'value'   => $this->oPF->getViewableValue($aItem, $sValue2),
                    );
                }
            }
        }
        
        // merge with couple
        if (!empty($aInputsSecond)) {
            $aHeader1 = array( // wrapper for merging
                array( // input itself
                    'type' => 'block_header',
                    'caption' => _t('_First Person')
                )
            );
            
            $aHeader2 = array(
                array(
                    'type' => 'block_header',
                    'caption' => _t('_Second Person'),
                )
            );
            
           
           // $aInputs = array_merge($aHeader1, $aInputs, $aHeader2,$aInputsSecond);
            
        }
        
        if (empty($aInputs))
            return '';
        
        if ($this->bPFEditable) {
            // add submit button
            $aInputs[] = array(
                'type' => 'submit',
                'colspan' => 'true',
                'value' => _t('_Save'),
            );
            
            // add hidden inputs
            // profile id
            $aInputs[] = array(
                'type' => 'hidden',
                'name' => 'ID',
                'value' => $this->_iProfileID,
            );
            
            $aInputs[] = array(
                'type' => 'hidden',
                'name' => 'force_ajax_save',
                'value' => '1',
            );
            
            $aInputs[] = array(
                'type' => 'hidden',
                'name' => 'pf_block',
                'value' => $iPFBlockID,
            );
            
            $aInputs[] = array(
                'type' => 'hidden',
                'name' => 'do_submit',
                'value' => '1',
            );
            
            $aFormAttrs = array(
                'method' => 'post',
                'action' => BX_DOL_URL_ROOT . 'pedit.php',
                'onsubmit' => "submitViewEditForm(this, $iPageBlockID, " . bx_html_attribute($_SERVER['PHP_SELF']) . "'?ID={$this->_iProfileID}'); return false;",
                'name' => 'edit_profile_form',
            );
            
            $aFormParams = array();
        } else {
            $aFormAttrs = array(
                'name' => 'view_profile_form',
            );
            
            $aFormParams = array(
                'remove_form'    => true,
            );
        }
        
        // generate form array
        $aForm = array(
            'form_attrs' => $aFormAttrs,
            'params'     => $aFormParams,
            'inputs'     => $aInputs,
        );
        
        $oForm = new BxTemplFormView($aForm);
        if (!empty($aInputsSecond))
        {
            $aForm2 = array(
            'form_attrs' => $aFormAttrs,
            'params'     => $aFormParams,
            'inputs'     => $aInputsSecond,
             );
            $oForm2 = new BxTemplFormView($aForm2);
            return $oForm->getCode().$this->createDescBlock('Description for '.$this->_aCouple['FirstName'],'style="background-color:#CCCCCC; font-size:12px;"').$this->createDescBlock($this->_aCouple['DescriptionMe']).$oForm2->getCode();
        }else
        return $oForm->getCode();
    }
    function getViewValuesTable($iPageBlockID, $iPFBlockID) {
		if( !isset( $this->aPFBlocks[$iPFBlockID] ) or empty( $this->aPFBlocks[$iPFBlockID]['Items'] ) )
			return '';

        // get parameters
        $bCouple        = $this->bCouple;
        $aItems         = $this->aPFBlocks[$iPFBlockID]['Items'];

        // collect inputs
        $aInputs = array();
        $aInputsSecond = array();

        foreach( $aItems as $aItem ) {
		$rest = ($this->_iProfileID != getLoggedId());
             // View only to profile owner

             $sItemName = $aItem['Name'];
            $sValue1   = $this->_aProfile[$sItemName];
          $sValue2   = $this->_aCouple[$sItemName];

            if ($aItem['Name'] == 'Age') {
                $sValue1 = $this->_aProfile['DateOfBirth'];
                $sValue2 = $this->_aCouple['DateOfBirth'];
            }

            if ($this->bPFEditable) {
                $aParams = array(
                    'couple' => $this->bCouple,
                    'values' => array(
                        $sValue1,
                        $sValue2
                    ),
                    'profile_id' => $this->_iProfileID,
                );

                $aInputs[] = $this->oPF->convertEditField2Input($aItem, $aParams, 0);

                if ($aItem['Type'] == 'pass') {
                    $aItem_confirm = $aItem;

                    $aItem_confirm['Name']    .= '_confirm';
                    $aItem_confirm['Caption']  = '_Confirm password';
                    $aItem_confirm['Desc']     = '_Confirm password descr';

                    $aInputs[] = $this->oPF->convertEditField2Input($aItem_confirm, $aParams, 0);

                    if ($this->bCouple and !in_array($sItemName, $this->aCoupleMutualItems))
                        $aInputsSecond[] = $this->oPF->convertEditField2Input($aItem_confirm, $aInputParams, 1);
                }

                if ($this->bCouple and !in_array($sItemName, $this->aCoupleMutualItems) and $sValue2) {
                    $aInputsSecond[] = $this->oPF->convertEditField2Input($aItem, $aParams, 1);
                }
            } else {
                if ($sValue1 || $aItem['Type'] == 'bool') { //if empty, do not draw
                    $aInputs[] = array(
                        'type'    => 'value',
                        'name'    => $aItem['Name'],
                        'caption' => _t($aItem['Caption']),
                        'value'   => $this->oPF->getViewableValue($aItem, $sValue1),
                    );
                }

                if ($this->bCouple and !in_array($sItemName, $this->aCoupleMutualItems) and ($sValue2 || $aItem['Type'] == 'bool')) {
                    if($aItem['Name']=='Country') continue;
                    $aInputsSecond[] = array(
                        'type'    => 'value',
                        'name'    => $aItem['Name'],
                        'caption' => _t($aItem['Caption']),
                        'value'   => $this->oPF->getViewableValue($aItem, $sValue2),
                    );
                }
            }
        }

        // merge with couple
        if (!empty($aInputsSecond)) {
            $aHeader1 = array( // wrapper for merging
                array( // input itself
                    'type' => 'block_header',
                    'caption' => _t('_First Person')
                )
            );

            $aHeader2 = array(
                array(
                    'type' => 'block_header',
                    'caption' => _t('_Second Person'),
                )
            );


           $aInputs = array_merge($aHeader1, $aInputs, $aHeader2,$aInputsSecond);

        }

        if (empty($aInputs))
            return '';

	$agencyid = $this->_aProfile['AdoptionAgency'];
    	$agency = db_arr("SELECT author_id FROM bx_groups_main WHERE id=".$agencyid);
   	$notagency = (getLoggedId() != $agency['author_id'] );
    	 if ($rest){
        	 if($notagency && !isAdmin()){
                	$this->_aProfile['BirthFatherStatus'] = '';
                	$this->_aProfile['DrugsAlcohol'] = '';
               	$this->_aProfile['SmokingDuringPregnancy'] = '';
               	$this->_aProfile['BPFamilyHistory'] = ''; 
                } 
        }



        if ($this->bPFEditable) {
            // add submit button
            $aInputs[] = array(
                'type' => 'submit',
                'colspan' => 'true',
                'value' => _t('_Save'),
            );

            // add hidden inputs
            // profile id
            $aInputs[] = array(
                'type' => 'hidden',
                'name' => 'ID',
                'value' => $this->_iProfileID,
            );

            $aInputs[] = array(
                'type' => 'hidden',
                'name' => 'force_ajax_save',
                'value' => '1',
            );

            $aInputs[] = array(
                'type' => 'hidden',
                'name' => 'pf_block',
                'value' => $iPFBlockID,
            );

            $aInputs[] = array(
                'type' => 'hidden',
                'name' => 'do_submit',
                'value' => '1',
            );

            $aFormAttrs = array(
                'method' => 'post',
                'action' => BX_DOL_URL_ROOT . 'pedit.php',
                'onsubmit' => "submitViewEditForm(this, $iPageBlockID, " . bx_html_attribute($_SERVER['PHP_SELF']) . "'?ID={$this->_iProfileID}'); return false;",
                'name' => 'edit_profile_form',
            );

            $aFormParams = array();
        } else {
            $aFormAttrs = array(
                'name' => 'view_profile_form',
            );

            $aFormParams = array(
                'remove_form'    => true,
            );
        }

        // generate form array
        $aForm = array(
            'form_attrs' => $aFormAttrs,
            'params'     => $aFormParams,
            'inputs'     => $aInputs,
        );

        $oForm = new BxTemplFormView($aForm);
        
        return $oForm->getCode();
    }
    
	/**
	** @description : function will generate user's actions
	** @param  : $sCaption (string) caption of returned block
	** @param  : $bNoDB (boolean) if isset this param block will return with design box
	** @return : HTML presentation data
	*/
	function showBlockActionsMenu( $sCaption, $bNoDB = false ) {
        global $p_arr;

		// init some user's values
		$iMemberID = getLoggedId();
        $iViewedMemberID = (int)$p_arr['ID'];

        /*
        if( (!$iMemberID  or !$iViewedMemberID) or ($iMemberID == $iViewedMemberID) )
			return null;
        */
        
        // prepare all needed keys
        $p_arr['url']  			= BX_DOL_URL_ROOT;
		$p_arr['window_width'] 	= $this->oTemplConfig->popUpWindowWidth;
		$p_arr['window_height']	= $this->oTemplConfig->popUpWindowHeight;
		$p_arr['anonym_mode']	= $this->oTemplConfig->bAnonymousMode;

		$p_arr['member_id']		= $iMemberID;
		$p_arr['member_pass']	= getPassword( $iMemberID );
		
		//--- Subscription integration ---//		
		$oSubscription = new BxDolSubscription();
		$sAddon = $oSubscription->getData();
		
        $aButton = $oSubscription->getButton($iMemberID, 'profile', '', $iViewedMemberID);
        $p_arr['sbs_profile_title'] = $aButton['title'];
		$p_arr['sbs_profile_script'] = $aButton['script'];
        //--- Subscription integration ---//
         $aID= getProfileInfo($iMemberID);
        //--- Check for member/non-member ---//
        if(isMember()) {
        	$p_arr['cpt_edit'] = _t('_EditProfile');
            if (pre_checkAction($aID,$iViewedMemberID))
            $p_arr['cpt_send_letter'] = _t('_SendLetter');
            else {
              $p_arr['cpt_send_letter'] = '';
            }            $p_arr['cpt_fave'] = _t('_Fave');
            $p_arr['cpt_befriend'] = _t('_Befriend');
            $p_arr['cpt_remove_friend'] = _t('_Remove friend');
            $p_arr['cpt_greet'] = _t('_Greet');
            $p_arr['cpt_get_mail'] = _t('_Get E-mail');
            $p_arr['cpt_share'] = _t('_Share');
            $p_arr['cpt_report'] = _t('_Report Spam');
            $p_arr['cpt_block'] = _t('_Block');
        }
        else {
        	$p_arr['cpt_edit'] = '';
            $p_arr['cpt_send_letter'] = '';
            $p_arr['cpt_fave'] = '';
            $p_arr['cpt_befriend'] = '';
            $p_arr['cpt_remove_friend'] = '';
            $p_arr['cpt_greet'] = '';
            $p_arr['cpt_get_mail'] = '';
            $p_arr['cpt_share'] = '';
            $p_arr['cpt_report'] = '';
            $p_arr['cpt_block'] = '';
        }
        
        $sActions = $sAddon . $GLOBALS['oFunctions']->genObjectsActions($p_arr, 'Profile');

        if ($bNoDB) 
			return  $sActions;
		else 
			echo DesignBoxContent( _t( $sCaption ),  $sActions, 1 );
	}
	function showBlockFriendRequest($sCaption, $bNoDB = false){
	    if(!isMember()) 
            return "";

        $aViewer = getProfileInfo();
	    $mixedCheck = $GLOBALS['MySQL']->getOne("SELECT `Check` FROM `sys_friend_list` WHERE `ID`='" . $this -> _iProfileID . "' AND `Profile`='" . $aViewer['ID'] . "' LIMIT 1");
	    
	    if($mixedCheck !== false && (int)$mixedCheck == 0)
            return MsgBox(_t('_pending_friend_request_answer', BX_DOL_URL_ROOT . "communicator.php?person_switcher=to&communicator_mode=friends_requests"));
	    
        return "";	    
	}
	function showBlockRateProfile( $sCaption, $bNoDB = false ) {
	    $votes = getParam('votes');
		
        // Check if profile votes enabled
        if (!$votes || !$this->oVotingView->isEnabled() || isBlocked($this -> _iProfileID, getLoggedId())) return;

        $ret = $this->oVotingView->getBigVoting();

		if ($bNoDB) {
			return $ret;
		} else {
			echo DesignBoxContent( _t( $sCaption ), $ret, 1 );
		}
	}
function showProfileDail()
{
        GLOBAL $site;
          GLOBAL $dir;
           defineMembershipActions( array('Click to Call') );
		$aCheck = checkAction((int)$_COOKIE['memberID'], BX_CLICK_TO_CALL, $isPerformAction);
        if(!$aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED)
			return false;
          if($_GET['Agency']){
            $sAgencyInfo=db_arr("SELECT bx_groups_main.author_id AS ID,bx_groups_main.title AS AgencyTitle, `Profiles`.*
                                FROM `Profiles`
                                JOIN bx_groups_main
                                WHERE Profiles.`ID` = bx_groups_main.author_id
                                AND bx_groups_main.uri = '".$_GET['Agency']."'");

        }else{
          $sAgencyInfo =db_arr("SELECT bx_groups_main.title AS AgencyTitle,  Profiles.*, Profiles.CONTACT_NUMBER AS ContactNumber, Profiles.CLICK_TO_CALL AS Clicktocall FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =".$this->_iProfileID." AND Profiles.AdoptionAgency=bx_groups_main.id )");
        }
          $contactnumber = $sAgencyInfo['CONTACT_NUMBER'];
          $agencyname =$sAgencyInfo['title'] ;
          $clicktocall = $sAgencyInfo['CLICK_TO_CALL'];


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
                        if(request.responseText== "Call Connected")
                        {
                        document.getElementById("submitbtn").src="'.$site['base'].'/images/callMe_3.png";
                        }
                        else{
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
'
       ;
        // return "hello";
       return $aVars;
}
function showAgencyInfo()
{
    global $site;
      //  bx_import($sClassName)
  $sAgencyInfo=db_arr("SELECT bx_groups_main.title AS AgencyTitle,  Profiles.* FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =".$this->_iProfileID." AND Profiles.AdoptionAgency=bx_groups_main.id )");
        //$aAuthor = getProfileInfo($sAgencyInfo['Id']);

if($sAgencyInfo['City'] && $sAgencyInfo['State']){ $aaddress.= $sAgencyInfo['City'].", ".'<br/>'.$sAgencyInfo['State']  ; }
 if($sAgencyInfo['zip']){ $aaddress.= ' '.$sAgencyInfo['zip']  ; }
          if($sAgencyInfo['City'] && $sAgencyInfo['State'] && $sAgencyInfo['Country']){ $aaddress.= ", "._t($GLOBALS['aPreValues']['Country'][$sAgencyInfo['Country']]['LKey'])  ; }else{$aaddress.= _t($GLOBALS['aPreValues']['Country'][$sAgencyInfo['Country']]['LKey']);}

 if($sAgencyInfo['Fax_Number'])$_fax='Fax: '.format_phone($sAgencyInfo['Fax_Number']);
		 if($sAgencyInfo['Street_Address'])$street=$sAgencyInfo['Street_Address'];
		  if($sAgencyInfo['Facebook'])$facebook='<a href="'.$sAgencyInfo['Facebook'].'"> <div style="background-image:url('.$site['base'].'images/face_book.png); width: 23px; height: 23px;margin-right:5px;float:left;"></div></a>';
		   if($sAgencyInfo['Twitter'])$twitter='<a href="'.$sAgencyInfo['Twitter'].'"> <div style="background-image:url('.$site['base'].'images/twitter.png); width: 23px; height: 23px;margin-right:5px;float:left;"></div></a>';

if(!$sAgencyInfo['WEB_URL'])$sAgencyInfo['WEB_URL']='';
         if($sAgencyInfo['CONTACT_NUMBER'])$phone='Tel: '.format_phone($sAgencyInfo['CONTACT_NUMBER']); else $phone='';

        // $aaddress = _t($GLOBALS['aPreValues']['Country'][$sAgencyInfo['Country']]['LKey']);
            $author_thumb = get_member_thumbnail($sAgencyInfo['ID'], 'none');



            $agencyname=$sAgencyInfo['AgencyTitle'];
            $fields = $sFields;
            $email=$sAgencyInfo['Email'];
            $phone= $phone;
            $address= $aaddress;
              $author_username = $sAgencyInfo['NickName'];
            $weburl = '<a href="http://'.$sAgencyInfo['WEB_URL'].'">'.$sAgencyInfo['WEB_URL'].'</a>';

       //return $oMain->_oTemplate->parseHtmlByName('entry_view_block_infoagency', $aVars);
       $code= '
        <div class="memberPic">
            <div class="thumbnail_block" style="float: none; width: 70px; height: 70px;">
	<div class="thumbnail_image" style="width: 68px; height: 68px;">
		'.$author_thumb.'
	</div>
        </div>

            <div class="thumb_username"><a href="'.$author_url.'">'.$author_username.'</a></div>
        </div>
        <div class="infoText">

            <div class="infoUnit" style="width:190px;padding-left:5px;">
                '.$agencyname.'
            </div>
			 <div class="infoUnit" style="width:190px;padding-left:5px;">
                '.$street.'
            </div>
            <div class="infoUnit" style="width:190px;padding-left:5px;">
                '.$address.'
            </div>
            <div class="infoUnit" style="width:190px;padding-left:5px;">
                '.$phone.'
            </div>
			 <div class="infoUnit" style="width:190px;padding-left:5px;">
                '.$_fax.'
            </div>
             <div class="infoUnit" style="width:190px; padding-left:5px;">

                '.$email.'
            </div>
            <div class="infoUnit" style="width:190px; padding-left:5px;">
                '.$weburl.'
            </div>
			<div class="infoUnit"style="width:240px;padding-left:5px; ">
                '.$facebook.''.$twitter.'
         </div>

         </div>
';


       return $code;

}
    function showBlockCmts() {
        if (!$this->oCmtsView->isEnabled() || isBlocked($this -> _iProfileID, getLoggedId())) return '';
        return $this->oCmtsView->getCommentsFirst();
    }

	function showBlockFriends($sCaption, $oParent, $bNoDB = false) {
        $iLimit = $this->iFriendsPerPage;

        $sAllFriends    = 'viewFriends.php?iUser=' .  $this -> _iProfileID;
        $sProfileLink   = getProfileLink( $this -> _iProfileID );
        $sOutputHtml    = null;

        // count all friends ;
		$iCount = getFriendNumber($this->_iProfileID);

        $sPaginate = '';
        if ($iCount) {
            $iPages = ceil($iCount/ $iLimit);
            $iPage = ( isset($_GET['page']) ) ? (int) $_GET['page'] : 1;

            if ( $iPage < 1 ) {
                $iPage = 1;
            }
            if ( $iPage > $iPages ) {
                $iPage = $iPages;
            }    

            $sqlFrom = ($iPage - 1) * $iLimit;
			if ($sqlFrom < 1)
				$sqlFrom = 0;
            $sqlLimit = "LIMIT {$sqlFrom}, {$iLimit}";
        } else {
            return ;
        }

		$aAllFriends = getMyFriendsEx($this->_iProfileID, '', 'image', $sqlLimit);
        $iCurrCount = count($aAllFriends);
        foreach ($aAllFriends as $iFriendID => $aFriendsPrm) {            
            $sOutputHtml .= '<div class="member_block">';
            $sOutputHtml .= get_member_thumbnail( $iFriendID, 'none', true, 'visitor', array('is_online' => $aFriendsPrm[5]));
            $sOutputHtml .= '</div>';
        }

        $sOutputHtml = $GLOBALS['oFunctions']->centerContent($sOutputHtml, '.member_block');

        $oPaginate = new BxDolPaginate(array(
            'page_url' => BX_DOL_URL_ROOT . 'profile.php',
            'count' => $iCount,
            'per_page' => $iLimit,
            'page' => $iPage,
            'per_page_changer' => true,
            'page_reloader' => true,
            'on_change_page' => 'return !loadDynamicBlock({id}, \'' .  $sProfileLink. '?page={page}&per_page={per_page}\');',
            'on_change_per_page' => ''
        ));

        $sPaginate = $oPaginate->getSimplePaginate($sAllFriends);
        return array( $sOutputHtml, array(), $sPaginate);
	}

	function showBlockMutualFriends( $sCaption, $bNoDB = false ) {
		$iViewer = getLoggedId();
		if ($this->_iProfileID == $iViewer) return;
		if ($this->iCountMutFriends > 0) {
			$sCode = $sPaginate = '';
			
			$iPerPage = $this->iFriendsPerPage;
			$iPage = (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
			
        	$sProfileLink = getProfileLink($this->_iProfileID);			
        	foreach ($this->aMutualFriends as $iKey => $sValue)
				$sCode .= '<div class="member_block">' . get_member_thumbnail($iKey, 'none', true) . '</div>';
			
			if ($this->iCountMutFriends > $iPerPage) {
				$oPaginate = new BxDolPaginate(array(
		            'page_url' => BX_DOL_URL_ROOT . 'profile.php',
		            'count' => $this->iCountMutFriends,
		            'per_page' => $iPerPage,
		            'page' => $iPage,
		            'per_page_changer' => true,
		            'page_reloader' => true,
		            'on_change_page' => 'return !loadDynamicBlock({id}, \'' .  $sProfileLink. '?page={page}&per_page={per_page}\');',
		            'on_change_per_page' => ''
	        	));
        		$sPaginate = $oPaginate->getSimplePaginate($sAllFriends, -1, -1, false);
			}
			$sCode = $GLOBALS['oFunctions']->centerContent($sCode, '.member_block');
			if ($bNoDB) {
				return array($sCode, array(), $sPaginate);
			} else {
				return DesignBoxContent( _t( $sCaption ), $ret, 1, $sFriendInfo);
			}
		}
	}
	
	function CountMutualFriends ($iViewer) {
		$iViewer = (int)$iViewer;
        $sQuery = "
        	SELECT COUNT(*)
			FROM `Profiles` AS p
			INNER JOIN (SELECT IF( '{$this->_iProfileID}' = f.`ID` , f.`Profile` , f.`ID` ) AS `ID` 
			FROM `sys_friend_list` AS `f` 
			WHERE 1 AND (f.`Profile` = '{$this->_iProfileID}' OR f.`ID` = '{$this->_iProfileID}') AND `Check` = 1) AS `f1` ON (`f1`.`ID` = `p`.`ID`) 
			INNER JOIN (SELECT IF( '{$iViewer}' = f.`ID` , f.`Profile` , f.`ID` ) AS `ID` 
			FROM `sys_friend_list` AS `f` 
			WHERE 1 AND (f.`Profile` = '{$iViewer}' OR f.`ID` = '{$iViewer}') AND `Check` = 1) AS `f2` ON (`f2`.`ID` = `p`.`ID`) 
        ";
		return (int)db_value($sQuery);
	}

	function FindMutualFriends ($iViewer, $iPage = 1, $iPerPage = 14) {
		$iViewer = (int)$iViewer;
		$this->iCountMutFriends = $this->CountMutualFriends($iViewer);
		if ($this->iCountMutFriends > 0) {
			$iPage = $iPage > 0 ? (int)$iPage : 1;
			$iPerPage = $iPerPage > 0 ? (int)$iPerPage : $this->iFriendsPerPage;
			$sLimit = "LIMIT " . ($iPage - 1) * $iPerPage . ", $iPerPage";
            
            $sQuery = "
            SELECT p.ID AS `friendID` , p.NickName
			FROM `Profiles` AS p
			INNER JOIN (SELECT IF( '{$this->_iProfileID}' = f.`ID` , f.`Profile` , f.`ID` ) AS `ID` 
			FROM `sys_friend_list` AS `f` 
			WHERE 1 AND (f.`Profile` = '{$this->_iProfileID}' OR f.`ID` = '{$this->_iProfileID}') AND `Check` = 1) AS `f1` ON (`f1`.`ID` = `p`.`ID`) 
			INNER JOIN (SELECT IF( '{$iViewer}' = f.`ID` , f.`Profile` , f.`ID` ) AS `ID` 
			FROM `sys_friend_list` AS `f` 
			WHERE 1 AND (f.`Profile` = '{$iViewer}' OR f.`ID` = '{$iViewer}') AND `Check` = 1) AS `f2` ON (`f2`.`ID` = `p`.`ID`) 
			ORDER BY p.`Avatar` DESC
            $sLimit
            ";
	
			$vResult = db_res( $sQuery );
			while( $aRow = mysql_fetch_assoc( $vResult ) )
				$this->aMutualFriends[ $aRow['friendID'] ] = $aRow['NickName'];
		}
	}

    function GenSqlConditions(&$aSearchBlocks, &$aRequestParams, $aFilterSortSettings = array())  {
        $aWhere = array ();
        $sJoin = '';
        $sPossibleOrder = '';

//--- AQB Profile Types Splitter ---//
	$iPType = intval($_REQUEST['ProfileType']);
	if ($iPType && BxDolRequest::serviceExists('aqb_pts', 'filter_fields')) {
		$aWhere[] = "`Profiles`.`ProfileType` & {$iPType}";
		BxDolService::call('aqb_pts', 'filter_fields', array(&$aSearchBlocks, $iPType));
	}
	//--- AQB Profile Types Splitter ---//


        // --- cut 1
		//collect where request array
		foreach( $aSearchBlocks as $iBlockID => $aBlock ) {
			foreach( $aBlock['Items'] as $aItem ) {
				if( !isset( $aRequestParams[ $aItem['Name'] ] ) )
					continue;
				
				$sItemName = $aItem['Name'];
				$mValue    = $aRequestParams[$sItemName];
				
				switch( $aItem['Type'] ) {
					case 'text':
					case 'area':
						if( $sItemName == 'Tags' ) {
							$sJoin .= " INNER JOIN `sys_tags` ON (`sys_tags`.`Type` = 'profile' AND `sys_tags`.`ObjID` = `Profiles`.`ID`) ";
							$aWhere[] = "`sys_tags`.`Tag` = '" . process_db_input($mValue, BX_TAGS_STRIP) . "'";
						} else
							$aWhere[] = "`Profiles`.`$sItemName` LIKE '%" . process_db_input($mValue, BX_TAGS_STRIP) . "%'";
					break;
					
                    case 'num':
                        $mValue[0] = (int)$mValue[0];
                        $mValue[1] = (int)$mValue[1];
						$aWhere[] = "`Profiles`.`$sItemName` >= {$mValue[0]} AND `Profiles`.`$sItemName` <= {$mValue[1]}";
					break;
					
					case 'date':
						$iMin = floor( $mValue[0] * 365.25 ); //for leap years
						$iMax = floor( $mValue[1] * 365.25 );
						
						$aWhere[] = "DATEDIFF( NOW(), `Profiles`.`$sItemName` ) >= $iMin AND DATEDIFF( NOW(), `Profiles`.`$sItemName` ) <= $iMax"; // TODO: optimize it, move static sql part to the right part and leave db field only in the left part
						
						//$aWhere[] = "DATE_ADD( `$sItemName`, INTERVAL {$mValue[0]} YEAR ) <= NOW() AND DATE_ADD( `$sItemName`, INTERVAL {$mValue[1]} YEAR ) >= NOW()"; //is it correct statement?
					break;
					
					case 'select_one':
					    if (is_array($mValue)) {
    						$sValue = implode( ',', $mValue );
    						$aWhere[] = "FIND_IN_SET( `Profiles`.`$sItemName`, '" . process_db_input($sValue, BX_TAGS_STRIP) . "' )";
                        } else {
                            $aWhere[] = "`Profiles`.`$sItemName` = '" . process_db_input($mValue, BX_TAGS_STRIP) . "'";
                        }
					break;
					
					case 'select_set':
						$aSet = array();
						
						$aMyValues = is_array($mValue) ? $mValue : array($mValue);
						
                        foreach( $aMyValues as $sValue ) {
                            $sValue = process_db_input($sValue, BX_TAGS_STRIP);
							$aSet[] = "FIND_IN_SET( '$sValue', `Profiles`.`$sItemName` )";
						}
						
						$aWhere[] = '( ' . implode( ' OR ', $aSet ) . ' )';
					break;
					
					case 'range':
						//impl
					break;
					
					case 'bool':
						$aWhere[] = "`Profiles`.`$sItemName'";
					break;
					
					case 'system':
						switch( $aItem['Name'] ) {
							case 'Couple':
								if($mValue == '-1') {
								}
								elseif( $mValue )
									$aWhere[] = "`Profiles`.`Couple` > `Profiles`.`ID`";
								else
									$aWhere[] = "`Profiles`.`Couple` = 0";
							break;
							
							case 'Keyword':
							case 'Location':
								$aFields = explode( "\n", $aItem['Extra'] );
								$aKeyw = array();
								$sValue = process_db_input( $mValue, BX_TAGS_STRIP );
								
								foreach( $aFields as $sField )
									$aKeyw[] = "`Profiles`.`$sField` LIKE '%$sValue%'";
								
								$aWhere[] = '( ' . implode( ' OR ', $aKeyw ) . ')';
							break;
							
							case 'ID':
								$aWhere[] = "`ID` = $mValue";
							break;
						}
					break;
				}
			}
        }

        // --- cut 2

		$bEnZipSearch = getParam("enable_zip_loc") == "on" ? 1 : 0;
		if ( $bEnZipSearch )
			require_once( BX_DIRECTORY_PATH_INC . 'RadiusAssistant.inc.php' );

		if ($bEnZipSearch && $aRequestParams['distance'] > 0) {
			$sZip = process_db_input($_REQUEST['zip'], BX_TAGS_STRIP);
			$iDistance = (int)$aRequestParams['distance'];
			$sMetric = htmlspecialchars_adv($_REQUEST['metric'], BX_TAGS_STRIP);

			$zip = process_db_input( strtoupper( str_replace(' ', '', $zip) ), BX_TAGS_STRIP);
			$aZipInfo = db_arr("SELECT `Latitude`, `Longitude` FROM `sys_zip_codes` WHERE REPLACE(`ZIPCode`,' ','') = '{$sZip}'");
			//echoDbg($aZipInfo);
			if ( $aZipInfo ) {
				// ZIP code exists
				$miles2km = 0.7; // miles/kilometers ratio

				$Miles = $sMetric == "km" ? $iDistance * $miles2km : $iDistance;
				$Latitude = $aZipInfo["Latitude"];
				$Longitude = $aZipInfo["Longitude"];

				$zcdRadius = new RadiusAssistant( $Latitude, $Longitude, $Miles );
				//echoDbg($zcdRadius);
				$minLat = $zcdRadius->MinLatitude();
				$maxLat = $zcdRadius->MaxLatitude();
				$minLong = $zcdRadius->MinLongitude();
				$maxLong = $zcdRadius->MaxLongitude();

				$sJoin .= " LEFT JOIN `sys_zip_codes` ON UPPER( REPLACE(`Profiles`.`zip`, ' ', '') ) = REPLACE(`sys_zip_codes`.`ZIPCode`,' ', '') ";
				$aWhere[] = "`sys_zip_codes`.`ZIPCode` IS NOT NULL AND `sys_zip_codes`.`Latitude` >= {$minLat} AND `sys_zip_codes`.`Latitude` <= {$maxLat} AND `sys_zip_codes`.`Longitude` >= {$minLong} AND `sys_zip_codes`.`Longitude` <= {$maxLong} ";
			}
        }

        // --- cut 3

        // collect query string
		$aWhere[] = "`Profiles`.`Status` = 'Active'";
		
		// add online only
		if( $_REQUEST['online_only'] ) {
			$iOnlineTime = getParam( 'member_online_time' );
			$aWhere[] = "`DateLastNav` >= DATE_SUB(NOW(), INTERVAL $iOnlineTime MINUTE)";
		}

        // --- cut 4
        
		$sPossibleOrder = '';
		switch($_REQUEST['show']) {
			case 'featured':
				$aWhere[] = "`Profiles`.`Featured` = '1'";
				break;
			case 'birthdays':
				$aWhere[] = "MONTH(`DateOfBirth`) = MONTH(CURDATE()) AND DAY(`DateOfBirth`) = DAY(CURDATE())";
				break;
			case 'top_rated':
				$sPossibleOrder = ' ORDER BY `Profiles`.`Rate` DESC';
				break;
			case 'popular':
				$sPossibleOrder = ' ORDER BY `Profiles`.`Views` DESC';
				break;
			case 'moderators':
				$sJoin .= " INNER JOIN `" . DB_PREFIX . "ChatProfiles` ON `Profiles`.`ID`= `" . DB_PREFIX . "ChatProfiles`.`ID` ";
				$aWhere[] = "`" . DB_PREFIX . "ChatProfiles`.`Type`='moder'";
				break;	
		}

		switch ($aFilterSortSettings['sort']) {
			case 'activity':
				$sPossibleOrder = ' ORDER BY `Profiles`.`DateLastNav` DESC';
				break;
			case 'date_reg':
				$sPossibleOrder = ' ORDER BY `Profiles`.`DateReg` DESC';
				break;
			default:
				break;
        }

        // --- cut 5
		if( $_REQUEST['photos_only'] )
			$aWhere[] = "`Profiles`.`Avatar`";

 		$aWhere[] = "(`Profiles`.`ProfileType` != '8') AND (`Profiles`.`Status` != 'Hidden')";

        $aWhere[] = "(`Profiles`.`Couple`='0' OR `Profiles`.`Couple`>`Profiles`.`ID`)";

        return array ($aWhere, $sJoin, $sPossibleOrder);
    }

    function GenSearchResultBlock($aSearchBlocks, $aRequestParams, $aFilterSortSettings = array(), $sPgnRoot = 'profile.php') {
		if(empty($aSearchBlocks)) { // the request is empty. do not search.
			return array('', array(), '', '');
		}

		// status uptimization
		$iOnlineTime = (int)getParam( "member_online_time" );
		$sIsOnlineSQL = ", if(`DateLastNav` > SUBDATE(NOW(), INTERVAL {$iOnlineTime} MINUTE ), 1, 0) AS `is_online`";

		$sQuery = 'SELECT DISTINCT SQL_CALC_FOUND_ROWS IF( `Profiles`.`Couple`=0, `Profiles`.`ID`, IF( `Profiles`.`Couple`>`Profiles`.`ID`, `Profiles`.`ID`, `Profiles`.`Couple` ) ) AS `ID` ' . $sIsOnlineSQL . ' FROM `Profiles` ';
		$sQueryCnt = 'SELECT COUNT(DISTINCT IF( `Profiles`.`Couple`=0, `Profiles`.`ID`, IF( `Profiles`.`Couple`>`Profiles`.`ID`, `Profiles`.`ID`, `Profiles`.`Couple` ) )) AS "Cnt" FROM `Profiles` ';

		list ($aWhere, $sJoin, $sPossibleOrder) = $this->GenSqlConditions($aSearchBlocks, $aRequestParams, $aFilterSortSettings);

		$sWhere = ' WHERE ' . implode( ' AND ', $aWhere );

		//collect the whole query string
		$sQuery = $sQuery . $sJoin . $sWhere . $sPossibleOrder;
		$sQueryCnt = $sQueryCnt . $sJoin . $sWhere . $sPossibleOrder;

		//echo $sQuery;

		$iCountProfiles = (int)(db_value($sQueryCnt));

		$sResults = $sTopFilter = '';
		if ($iCountProfiles) {
			//collect pagination
			$iCurrentPage    = isset( $_GET['page']         ) ? (int)$_GET['page']         : 1;
			$iResultsPerPage = isset( $_GET['res_per_page'] ) ? (int)$_GET['res_per_page'] : 10;

			if( $iCurrentPage < 1 )
				$iCurrentPage = 1;
			if( $iResultsPerPage < 1 )
				$iResultsPerPage = 10;

			$iTotalPages = ceil( $iCountProfiles / $iResultsPerPage );

  			//$sQuery .= 'ORDER BY `Profiles`.`Avatar` DESC';
                    
                      //$sQuery .= ' , `Profiles`.`Avatar` DESC';

                       if($_GET['sort'] != '') {
  			
                            $sQuery .= ' , `Profiles`.`Avatar` DESC';

                        }
                        else {

                          $sQuery .= 'ORDER BY `Profiles`.`Avatar` DESC';
                          
                        }

			if( $iTotalPages > 1 ) {
				if( $iCurrentPage > $iTotalPages )
					$iCurrentPage = $iTotalPages;

				$sLimitFrom = ( $iCurrentPage - 1 ) * $iResultsPerPage;
				$sQuery .= " LIMIT {$sLimitFrom}, {$iResultsPerPage}";
                            //echo $sQuery;
				list($sPagination, $sTopFilter) = $this->genSearchPagination($iCountProfiles, $iCurrentPage, $iResultsPerPage, $aFilterSortSettings, $sPgnRoot);
			} else {
				$sPagination = '';
			}

			//make search
			$aProfiles = array();
			$aProfileStatuses = array();
			$rProfiles = db_res($sQuery);
			while ($aProfile = mysql_fetch_assoc($rProfiles)) {
				$aProfiles[] = $aProfile['ID'];
				$aProfileStatuses[$aProfile['ID']] = $aProfile['is_online'];
			}

			$sOutputMode = (isset ($_REQUEST['search_result_mode']) && $_REQUEST['search_result_mode']=='ext') ? 'ext' : 'sim';

			$aDBTopMenu = array();
			foreach( array( 'sim', 'ext' ) as $myMode ) {
				switch ( $myMode ) {
					case 'sim':
						$modeTitle = _t('_Simple');
					break;
					case 'ext':
						$modeTitle = _t('_Extended');
					break;
				}

				$aGetParams = $_GET;
				unset( $aGetParams['search_result_mode'] );
				$sRequestString = $this->collectRequestString( $aGetParams );
				$aDBTopMenu[$modeTitle] = array('href' => bx_html_attribute($_SERVER['PHP_SELF']) . "?search_result_mode={$myMode}{$sRequestString}", 'dynamic' => true, 'active' => ( $myMode == $sOutputMode ));
			}

			if ($sOutputMode == 'sim') {
				$sBlockWidthSQL = "SELECT `PageWidth`, `ColWidth` FROM `sys_page_compose` WHERE `Page`='profile' AND `Func`='ProfileSearch'";
				$aBlockWidthInfo = db_arr($sBlockWidthSQL);

				$iBlockWidth = (int)((int)$aBlockWidthInfo['PageWidth'] /* * (int)$aBlockWidthInfo['ColWidth'] / 100*/ ) - 20;

				$iMaxThumbWidth = getParam('max_thumb_width') + 6;

				$iDestWidth = $iCountProfiles * ($iMaxThumbWidth + 6);

				if ($iDestWidth > $iBlockWidth) {
					$iMaxAllowed = (int)floor($iBlockWidth / ($iMaxThumbWidth + 6));
					$iDestWidth = $iMaxAllowed * ($iMaxThumbWidth + 6);
				}
			}
			$sWidthCent = ($iDestWidth>0) ? "width:{$iDestWidth}px;" : '';

			$sResults .= <<<EOF
                <div class="block_rel_100">
                    <div id="ajaxy_popup_result_div" style="display: none;" ></div>
                <div class="centered_div" style="{$sWidthCent}">
EOF;

			//output search results
			require_once(BX_DIRECTORY_PATH_ROOT . 'templates/tmpl_'.$GLOBALS['tmpl'].'/scripts/BxTemplSearchProfile.php');
			$oBxTemplSearchProfile = new BxTemplSearchProfile();
			$iCounter = 0;

			foreach( $aProfiles as $iProfID ) {
				$aProfileInfo = getProfileInfo( $iProfID );

				$aExtendedCss = ($iCounter % 2 == 1) ? array( 'ext_css_class' => 'search_filled_block') : array();

				//attaching status value
	            $aProfileStatus = array(
					'is_online' => $aProfileStatuses[$iProfID]
	            );
	            $aProfileInfo = array_merge($aProfileStatus, $aProfileInfo);

				$sResults .= $oBxTemplSearchProfile->displaySearchUnit($aProfileInfo, $aExtendedCss);
				$iCounter++;
			}

			$sResults .= <<<EOF
                </div>
                    <div class="clear_both"></div>
                </div>
EOF;

            return array($sResults, $aDBTopMenu, $sPagination, $sTopFilter);
		} else {
			return array(MsgBox(_t('_Empty')), array(), '', '');
		}
	}

	function GenProfilesCalendarBlock() {
		bx_import ('BxDolProfilesCalendar');

		$aDateParams = array();
		$sDate = $_REQUEST['date'];
		if ($sDate) {
			$aDateParams = explode('/', $sDate);
		}
		$oCalendar = new BxDolProfilesCalendar((int)$aDateParams[0], (int)$aDateParams[1], $this);

		$sOutputMode = (isset ($_REQUEST['mode']) && $_REQUEST['mode']=='dob') ? 'dob' : 'dor';
		$aDBTopMenu = array();
		foreach( array( 'dob', 'dor' ) as $myMode ) {
			switch ( $myMode ) {
				case 'dob':
					if ($sOutputMode == $myMode)
						$oCalendar->setMode('dob');
					$modeTitle = _t('Date of birth');
				break;
				case 'dor':
					$modeTitle = _t('Date of registration');
				break;
			}
			
			$aGetParams = $_GET;
			unset( $aGetParams['mode'] );
			$sRequestString = $this->collectRequestString( $aGetParams );
			$aDBTopMenu[$modeTitle] = array('href' => bx_html_attribute($_SERVER['PHP_SELF']) . "?mode={$myMode}{$sRequestString}", 'dynamic' => true, 'active' => ( $myMode == $sOutputMode ));
		}

		//return $oCalendar->display();
		return array( $oCalendar->display(), $aDBTopMenu );
	}

	function genSearchPagination( $iCountProfiles, $iCurrentPage, $iResultsPerPage, $aFilterSortSettings = array(), $sPgnRoot = '') {
		$aGetParams = $_GET;
		unset( $aGetParams['page'] );
		unset( $aGetParams['res_per_page'] );
		unset( $aGetParams['sort'] );

		$sRequestString = $this->collectRequestString( $aGetParams );
		$sRequestString = BX_DOL_URL_ROOT . strip_tags($sPgnRoot) . '?' . substr( $sRequestString, 1 );

		$sPaginTmpl = $sRequestString . '&res_per_page={per_page}&page={page}&sort={sorting}';

		// gen pagination block ;

		$oPaginate = new BxDolPaginate
		(
			array
			(
				'page_url'	=> $sPaginTmpl,
				'count'		=> $iCountProfiles,
				'per_page'	=> $iResultsPerPage,
				'sorting'    => $aFilterSortSettings['sort'], // New param
				'page'		=> $iCurrentPage,
				'per_page_changer'	 => true,
				'page_reloader'		 => true,
				'on_change_page'	 => null,
				'on_change_per_page' => null,
			)
		);

		$sPagination = $oPaginate->getPaginate();

		// fill array with sorting params ;
		$aSortingParam = array (
			'none' => _t('_None'),
			'activity' => _t('_Latest activity'),
			'date_reg' => _t('_FieldCaption_DateReg_View'),
			//'rate'         => _t( '_Rate' ),
		);

		// gen sorting block ( type of : drop down ) ;
		$sSortBlock = $oPaginate->getSorting($aSortingParam);

		$sSortElement = <<<EOF
<div class="top_settings_block">
	<div class="ordered_block">
		{$sSortBlock}
	</div>
	<div class="clear_both"></div>
</div>
EOF;

		return array($sPagination, $sSortElement);
	}
	
	function collectRequestString( $aGetParams, $sKeyPref = '', $sKeyPostf = '' ) {
		if( !is_array( $aGetParams ) )
			return '';
		
		$sRet = '';
		foreach( $aGetParams as $sKey => $sValue ) {
			if( $sValue === '' )
				continue;
			
			if( !is_array($sValue) ) {
				$sRet .= '&' . urlencode( $sKeyPref . $sKey . $sKeyPostf ) . '=' . urlencode( process_pass_data( $sValue ) );
			} else {
				$sRet .= $this->collectRequestString( $sValue, "{$sKeyPref}{$sKey}{$sKeyPostf}[", "]" ); //recursive call
			}
		}
		
		return $sRet;
	}
 
	function GenActionsMenuBlock() {
		// init some user's values
		$p_arr = $this->_aProfile;

		$iMemberID = ( isset($_COOKIE['memberID']) ) ? (int) $_COOKIE['memberID'] : 0;

        $iViewedMemberID = (int)$p_arr['ID'];

        if( (!$iMemberID  or !$iViewedMemberID) or ($iMemberID == $iViewedMemberID) )
			return null;

        // prepare all nedded keys
        $p_arr['url']  			= BX_DOL_URL_ROOT;
		$p_arr['window_width'] 	= $this->oTemplConfig->popUpWindowWidth;
		$p_arr['window_height']	= $this->oTemplConfig->popUpWindowHeight;
		$p_arr['anonym_mode']	= $this->oTemplConfig->bAnonymousMode;
		$p_arr['member_id']		= $iMemberID;
		$p_arr['member_pass']	= getPassword( $iMemberID );

        $sActions = $GLOBALS['oFunctions']->genObjectsActions($p_arr, 'Profile', 'cellpadding="0" cellspacing="0"' );

		return  $sActions;
	}
}

?>
