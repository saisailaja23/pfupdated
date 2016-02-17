<?php

/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

bx_import('BxDolMenu');

class BxBaseMenu extends BxDolMenu
{
    var $iElementsCntInLine;

    var $sSiteUrl;

    var $iJumpedMenuID;

    var $sCustomSubIconUrl;
    var $sCustomSubHeader;
    var $sCustomActions;

    var $sBreadCrumb;

    var $bDebugMode;

    var $sWidth;

    function BxBaseMenu()
    {
        BxDolMenu::BxDolMenu();
        $this->iElementsCntInLine = (int)getParam('nav_menu_elements_on_line_' . (isLogged() ? 'usr' : 'gst'));

        $this->sSiteUrl = BX_DOL_URL_ROOT;
        $this->iJumpedMenuID = 0;
        $this->sCustomSubIconUrl = '';
        $this->sCustomSubHeader = '';
        $this->sCustomActions = '';

        $this->sBreadCrumb = '';

        $this->bDebugMode = false;

        $this->sWidth = $GLOBALS['oSysTemplate']->getPageWidth();
    }

    function setCustomSubIconUrl($sCustomSubIconUrl)
    {
        $this->sCustomSubIconUrl = $sCustomSubIconUrl;
    }

    function setCustomSubHeader($sCustomSubHeader)
    {
        $this->sCustomSubHeader = $sCustomSubHeader;
    }

    /*
    * Generate actions in submenu place at right.
    */
    function setCustomSubActions(&$aKeys, $sActionsType, $bSubMenuMode = true)
    {
        $this->sCustomActions = '';
        if(!$sActionsType)
            return;

        // prepare all needed keys
        $aKeys['url']  			= $this->sSiteUrl;
        $aKeys['window_width'] 	= $this->oTemplConfig->popUpWindowWidth;
        $aKeys['window_height']	= $this->oTemplConfig->popUpWindowHeight;
        $aKeys['anonym_mode']	= $this->oTemplConfig->bAnonymousMode;

        // $aKeys['member_id']		= $iMemberID;
        // $aKeys['member_pass']	= getPassword($iMemberID);

        //$GLOBALS['oFunctions']->iDhtmlPopupMenu = 1;
        $this->sCustomActions = $GLOBALS['oFunctions']->genObjectsActions($aKeys, $sActionsType, $bSubMenuMode, 'actions_submenu', 'action_submenu');
    }

    /**
     * TODO: Looks like it isn't used anywhere and can be removed.
     */
    function setCustomSubActions2($aCustomActions)
    {
        if (is_array($aCustomActions) && count($aCustomActions) > 0) {
            $sActions = '';
            foreach ($aCustomActions as $iID => $aCustomAction) {
                $sTitle = $sLink = $sIcon = '';
                $sTitle = $aCustomAction['title'];
                $sLink = $aCustomAction['url'];
                $sIcon = $aCustomAction['icon'];

                $sActions .= <<<EOF
<div class="button_wrapper" style="width:48%;margin-right:1%;margin-left:1%;" onclick="window.open ('{$sLink}','_self');">
    <img alt="{$sTitle}" src="{$sIcon}" style="float:left;" />
    <input class="form_input_submit" type="submit" value="{$sTitle}" class="menuLink" />
    <div class="button_wrapper_close"></div>
</div>
EOF;
            }

            $this->sCustomActions = $sActions;
        }
    }

    /*
    * Generate navigation menu source
    */
    function getCode()
    {
        global $oSysTemplate;

        if(isset($GLOBALS['bx_profiler']))
            $GLOBALS['bx_profiler']->beginMenu('Main Menu');

        $this->getMenuInfo();

        //--- Main Menu ---//
        $this->genTopItems();
        $sMenuMain = $this->sCode;

        //--- Submenu Menu ---//
        $this->sCode = '';
        if(!defined('BX_INDEX_PAGE') && !defined('BX_JOIN_PAGE'))
            $this->genSubMenus();

        $sResult = $oSysTemplate->parseHtmlByName('navigation_menu.html', array(
            'main_menu' => $sMenuMain,
            'sub_menu' => $this->sCode
        ));

        if(isset($GLOBALS['bx_profiler']))
            $GLOBALS['bx_profiler']->endMenu('Main Menu');

        return $sResult;
    }

    /*
    * Generate top menu elements
    */
    function genTopItems()
    {
        $iCounter = 0;
        foreach( $this->aTopMenu as $iItemID => $aItem ) {
            if( $aItem['Type'] != 'top' )
                continue;
            if( !$this->checkToShow( $aItem ) )
                continue;
            if ($aItem['Caption'] == "{profileNick}" && $this->aMenuInfo['profileNick']=='') continue;

            $bActive = ( $iItemID == $this->aMenuInfo['currentTop'] );

            if ($bActive && $iCounter >= $this->iElementsCntInLine) {
                $this->iJumpedMenuID = $iItemID;
                break;
            }
            $iCounter++;
        }

        $iCounter = 0;
        foreach( $this->aTopMenu as $iItemID => $aItem ) {
            if( $aItem['Type'] != 'top' )
                continue;

            if( !$this->checkToShow( $aItem ) )
                continue;

            //generate
            list( $aItem['Link'] ) = explode( '|', $aItem['Link'] );

            $aItem['Caption'] = $this->replaceMetas( $aItem['Caption'] );
            $aItem['Link'] = $this->replaceMetas( $aItem['Link'] );
            $aItem['Onclick'] = $this->replaceMetas( $aItem['Onclick'] );

            $bActive = ( $iItemID == $this->aMenuInfo['currentTop'] );
            $bActive = ($aItem['Link']=='index.php' && $this->aMenuInfo['currentTop']==0) ? true : $bActive;

            if ($this->bDebugMode) print $iItemID . $aItem['Caption'] . '__' . $aItem['Link'] . '__' . $bActive . '<br />';

            $isBold = false;
            $sImage = ($aItem['Icon'] != '') ? $aItem['Icon'] : $aItem['Picture'];

            //Draw jumped element
            if ($this->iJumpedMenuID>0 && $this->iElementsCntInLine == $iCounter) {
                $aItemJmp = $this->aTopMenu[$this->iJumpedMenuID];
                list( $aItemJmp['Link'] ) = explode( '|', $aItemJmp['Link'] );
                $aItemJmp['Link']    = $this->replaceMetas( $aItemJmp['Link'] );
                $aItemJmp['Onclick'] = $this->replaceMetas( $aItemJmp['Onclick'] );

                $bJumpActive = ( $this->iJumpedMenuID == $this->aMenuInfo['currentTop'] );
                $bJumpActive = ($aItemJmp['Link']=='index.php' && $this->aMenuInfo['currentTop']==0) ? true : $bJumpActive;

                $this->genTopItem(_t($aItemJmp['Caption']), $aItemJmp['Link'], $aItemJmp['Target'], $aItemJmp['Onclick'], $bJumpActive, $this->iJumpedMenuID, $isBold);

                if ($this->bDebugMode) print '<br />pre_pop: ' . $this->iJumpedMenuID . $aItemJmp['Caption'] . '__' . $aItemJmp['Link'] . '__' . $bJumpActive . '<br /><br />';
            }

            if ($this->iElementsCntInLine == $iCounter) {
                $this->GenMoreElementBegin();

                if ($this->bDebugMode) print '<br />more begin here ' . '<br /><br />';
            }

            if ($this->iJumpedMenuID>0 && $iItemID == $this->iJumpedMenuID) {
                //continue;
                if ($this->bDebugMode) print '<br />was jump out here ' . '<br /><br />';
            } else {
                if ($this->iElementsCntInLine > $iCounter) {
                    $this->genTopItem(_t($aItem['Caption']), $aItem['Link'], $aItem['Target'], $aItem['Onclick'], $bActive, $iItemID, $isBold, $sImage);
                } else {
                    $this->genTopItemMore(_t($aItem['Caption']), $aItem['Link'], $aItem['Target'], $aItem['Onclick'], $bActive, $iItemID);
                }
            }

            $iCounter++;
        }

        if ($this->iElementsCntInLine < $iCounter) {
            $this->GenMoreElementEnd();
        }
    }

    /*
    * Generate sub menu elements
    */
    function genSubMenus()
    {
        foreach( $this->aTopMenu as $iTItemID => $aTItem ) {
            if( $aTItem['Type'] != 'top' && $aTItem['Type'] !='system')
                continue;

            if( !$this->checkToShow( $aTItem ) )
                continue;

            if( $this->aMenuInfo['currentTop'] == $iTItemID && $this->checkShowCurSub() )
                $sDisplay = 'block';
            else {
                $sDisplay = 'none';
                if ($aTItem['Caption']=='_Home' && $this->aMenuInfo['currentTop']==0)
                    $sDisplay = 'block';
            }

            $sCaption = _t( $aTItem['Caption'] );
            $sCaption = $this->replaceMetas($sCaption);

            //generate
            if ($sDisplay == 'block') {
                $sPicture = $aTItem['Picture'];

                $iFirstID = $this->genSubFirstItem( $iTItemID );
                $this->genSubHeader( $iTItemID, $iFirstID, $sCaption, $sDisplay, $sPicture );
            }
        }
    }

    /*
     * Generate sub items of sub menu elements
     */
    function genSubItems($iTItemID = 0)
    {
        if(!$iTItemID)
            $iTItemID = $this->aMenuInfo['currentTop'];

        $bFirst = true;
        $sSubItems = '';
        foreach( $this->aTopMenu as $iItemID => $aItem ) {
            if( $aItem['Type'] != 'custom' )
                continue;
            if( $aItem['Parent'] != $iTItemID )
                continue;
            if( !$this->checkToShow( $aItem ) )
                continue;

            //generate
            list( $aItem['Link'] ) = explode( '|', $aItem['Link'] );

            $aItem['Link']    = $this->replaceMetas( $aItem['Link'] );
            $aItem['Onclick'] = $this->replaceMetas( $aItem['Onclick'] );
            $sSubItems .= (!$bFirst ? '<div class="bullet">&#183;</div>' : '') . $this->genSubItem( _t( $aItem['Caption'] ), $aItem['Link'], $aItem['Target'], $aItem['Onclick'], $iItemID == $this->aMenuInfo['currentCustom']);

            $bFirst = false;
        }

        return !empty($sSubItems) ? '<div class="sys_page_submenu">' . $sSubItems . '<div class="clear_both">&nbsp;</div></div>' : '';
    }

    function genSubItem( $sCaption, $sLink, $sTarget, $sOnclick, $bActive )
    {
        if(!$bActive) {
            $sOnclick = $sOnclick ? ' onclick="' . $sOnclick . '"' : '';
            $sTarget = $sTarget  ? ' target="'  . $sTarget  . '"' : '';

            if(strpos( $sLink, 'http://' ) === false && strpos( $sLink, 'https://' ) === false && !strlen($sOnclick))
                $sLink = $this->sSiteUrl . $sLink;

            $sSubItems = '<div class="pas"><a class="sublinks" href="' . $sLink . '"' . $sTarget . $sOnclick . '>' . $sCaption . '</a></div>';
        } else
            $sSubItems = '<div class="act">' . $sCaption . '</div>';

        return $sSubItems;
    }

    /*
    * Generate top menu elements
    */
    function genTopItem($sText, $sLink, $sTarget, $sOnclick, $bActive, $iItemID, $isBold = false, $sPicture = '')
    {
        $sActiveStyle = ($bActive) ? ' id="tm_active"' : '';

        if (!$bActive) {
            $sOnclick = $sOnclick ? ( ' onclick="' . $sOnclick . '"' ) : '';
            $sTarget  = $sTarget  ? ( ' target="'  . $sTarget  . '"' ) : '';
        }

        $sLink = (strpos($sLink, 'http://') === false && strpos($sLink, 'https://') === false && !strlen($sOnclick)) ? $this->sSiteUrl . $sLink : $sLink;

        $sMoreIcon = getTemplateIcon('tm_sitem_down.gif');

        $sSubMenu = $this->getAllSubMenus($iItemID);

        $sBoldStyle = ($isBold) ? 'style="font-weight:bold;"' : '';

        $sImgTabStyle = $sPictureRep = '';
        if($sText == '' && $isBold && $sPicture != '') {
            $sPicturePath = getTemplateIcon($sPicture);
            $sPictureRep = '<img src="' . $sPicturePath . '" />';
        }

        $sMainSubs = ($sSubMenu=='') ? '' : <<<EOF
    <!--[if lte IE 6]><table id="mmm"><tr><td><![endif]-->
    <ul class="sub main_elements">{$sSubMenu}</ul>
    <!--[if lte IE 6]></td></tr></table></a><![endif]-->
EOF;

        $this->sCode .= <<<EOF
<td class="top" {$sActiveStyle} {$sImgTabStyle}>
    <a href="{$sLink}" {$sOnclick} {$sTarget} class="top_link"><span class="down bx-def-padding-sec-leftright" {$sBoldStyle}>{$sPictureRep}{$sText}</span>
    <!--[if gte IE 7]><!--></a><!--<![endif]-->
    <div class="sub">{$sMainSubs}</div>
</td>
EOF;
    }

    /*
    * Get parent of submenu element
    */
    function genSubFirstItem( $iTItemID = 0 )
    {
        if( !$iTItemID )
            $iTItemID = $this->aMenuInfo['currentTop'];

        foreach( $this->aTopMenu as $iItemID => $aItem ) {
            if( $aItem['Type'] != 'custom' )
                continue;

            if( $aItem['Parent'] != $iTItemID )
                continue;

            if( !$this->checkToShow( $aItem ) )
                continue;

            return $iItemID;
        }
    }

    /*
    * Generate header for sub items of sub menu elements
    */
    function genSubHeader( $iTItemID, $iFirstID, $sCaption, $sDisplay, $sPicture = '' )
    {
        $sLoginSection = $sSubElementCaption = $sProfStatusMessage = $sProfStatusMessageWhen = $sProfileActions = '';
        $sCaptionWL = $sProfStatusMessageEl = $sMiddleImg = '';

        if ($this->aMenuInfo['currentCustom'] == 0 && $iFirstID > 0) $this->aMenuInfo['currentCustom'] = $iFirstID;
        //comment need when take header for profile page
        if ($this->sCustomSubHeader == '' && $this->aMenuInfo['currentCustom'] > 0) {
            $sSubCapIcon = getTemplateIcon('_submenu_capt_right.gif');
            $sSubElementCaption = _t($this->aTopMenu[$this->aMenuInfo['currentCustom']]['Caption']);

            $sCustomPic = $this->aTopMenu[$this->aMenuInfo['currentCustom']]['Picture'];
            $sPicture = ($sCustomPic != '') ? $sCustomPic : $sPicture;

            $sMiddleImg = '<img src="'.$sSubCapIcon.'" />';
            $sSubElementCaption = <<<EOF
<font style="font-weight:normal;">{$sSubElementCaption}</font>
EOF;
        }	
	   if($_SERVER['PHP_SELF']=='/profile.php'){
	
            $saveaspdf = $this->generateprofilePDF();
           

            /*$saveaspdf='<br/><br/><br/>
            <a href="http://pdfcrowd.com/url_to_pdf/" style="height:20px; float:right; text-decoration:none;"><button class="form_input_submit" type="button" >
            <img src="'.$site['base'].'images/icons/print.jpeg" alt="Save as PDF">
            Save as PDF
           </button></a>';*/


        }
        else{
           
           $saveaspdf='<p style="display:none;">save as pdf</p>';
        }

		if(!isMember()){
			$sLoginSection = $GLOBALS['oSysTemplate']->ParseHtmlByName('login_join.html', array(
                            'p' => $saveaspdf
                        ));
			}
                else{ 	

	
                    if(BX_PROFILE_PAGE==1 )
                    {

   
$mem_id  = getMemberMembershipInfo(getLoggedId());

   if($mem_id['ID'] != 25 ) {


         $sLoginSection=<<<EOH
                    <div class="menu_user_actions"><div class="actions?ontainer">
    <div class="actionsBlock">
    <table cellspacing="0" cellpadding="3px" width="100%" style="margin-left:0px;">
    <tbody>
    <script type="text/javascript">
  $(document).ready(function(){

  $('#test').click(function(){
  $('#test').attr("disabled", true);
                       //alert("HI");

  setTimeout(function(){ $('#test').removeAttr("disabled");}, 3000);


    });


        });

  </script>

    <tr>
    <td width="50%">
    <div style="width: 100%; margin: 0pt;" class="button_wrapper" >
    <div class="button_wrapper_close"></div>
    <div class="button_input_wrapper" >
    <button id="test" class="form_input_submit" type="button" onclick="AqbPCMain.showPopup('modules/?r=aqb_pcomposer/get_create_block_form/{$this->aMenuInfo['profileID']}');" >
    <img   src="{$GLOBALS['site']['url']}modules/aqb/pcomposer/templates/base/images/icons/new_block.png" alt="Add my block">
    Add Section
    </button>
    </div>
    </div>
    </td>
    <td width="50%">
    <div onclick="$('#profile_customize_page').fadeIn('slow', function() {dbTopMenuLoad('profile_customizer');});" style="width: 100%; margin: 0pt;" class="button_wrapper" border="1">
    <div class="button_wrapper_close"></div>
    <div class="button_input_wrapper">
    <button class="form_input_submit" type="button">
    <img src="{$GLOBALS['site']['url']}modules/boonex/profile_customize/templates/base/images/icons/bx_action_customize.png" alt="Customize">
    Customize Profile
    </button>
    </div>
</div>

                </td>
                </tr>


		<tr>


                <td width="50%">

                 <script type="text/javascript" src="{$GLOBALS['site']['url']}/lightbox/thickbox-compressed.js"></script>
                 <style type="text/css">
                .dhtmlx-error{
                    font-weight:bold;
                    color:white;
                    background-color:red;
                    
                }
                 .dhtmlx-sucess{
                    font-weight:bold;
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    margin-top: -50px;
                    margin-left: -100px;
                 z-index:100;
                }
                </style>
                 <script type="text/javascript" src="uploadComponent/js/model.js"></script>
                <script type="text/javascript" src="uploadComponent/js/controller.js"></script>
                 <script>
                 
                 function onActionPerformed( response ){
                dhtmlx.message(response);
                 location.reload(1);
            }
            function newWin(obj)
            {
           

                uploadController.loadValues({
                    window_id       : "uploadProfile", //mandatory
                    win_title       : "upload Profile",
                    upload_formats  : "pdf",       //mandatory                        //formats as comma seprated
                    uploadURL       : "file_upload.php",      //mandatory                        //the path to the server-side script relative to the index file 
                    SWFURL          : "../../../../../file_upload.php", //mandatory       //the path to the server-side script relative to the client flash script file
                    msgOrAlert      : false,                                               //true for alert and false for message(in poup alert willnot work)
                    autoStart       : true,                                               //true or false(default false)
                    numberOfFiles   : 1,                                                  //default 100
                    clickObj        : obj,               
                    popup           : false,                                             //default window , give true for popup
                    onCallBack      : onActionPerformed //mandatory
                });
                uploadController.initComponent();
            }
                 
                 
                 /*var dhxuploadprofileWin,formData,uploadprofile;
                 dhxuploadprofileWin = new dhtmlXWindows();
                 function fileuploadWindow(){
                    if(dhxuploadprofileWin){if(!dhxuploadprofileWin.window("uploadprofile")){
                        
                        uploadprofile = dhxuploadprofileWin.createWindow("uploadprofile",0, 0, 400, 200);
                        var dhxuploadprofileWinlayout = dhxuploadprofileWin.window("uploadprofile").attachLayout("1C");
                        uploadprofile.button("park").hide();
                        dhxuploadprofileWin.window("uploadprofile").centerOnScreen();
                        dhxuploadprofileWin.window("uploadprofile").denyMove();
                        uploadprofile.button("minmax1").hide();
                        uploadprofile.button("minmax2").hide();
                        dhxuploadprofileWinlayout .cells("a").hideHeader();
                        uploadprofile.setText("Upload Profile");  
                        formData = [{ type:"label" , name:"form_label_1", label:"Upload profile in PDF format"  },{
                        type: "fieldset",
                        label: "Uploader",
                        list: [{
                            type: "upload",
                            name: "myFiles",
                            inputWidth: 330,
                            url: "file_upload.php",
                            _swfLogs: "enabled",
                            autoStart :true,
                            swfPath: "plugins/dhtmlx/dhtmlxForm/codebase/ext/uploader.swf",
                            swfUrl: "../../../../../file_upload.php"
                        }]
                        }];
                        uploadform = dhxuploadprofileWinlayout.cells("a").attachForm(formData);
                        uploadform.attachEvent("onFileAdd",function(realName){
                        var extnsn=realName.split(".");
                        if(extnsn[extnsn.length-1].toLowerCase() != 'pdf'){
                            var myUploader = uploadform.getUploader("myFiles");
                            myUploader.clear();
                            dhtmlx.message({ type:"error", text:"Upload pdf format of profile"});}
                        });
                        uploadform.attachEvent("onUploadComplete",function(realName){
                            uploadform.disableItem("myFiles");
                        dhtmlx.message({ type:"sucess", text:"Profile Uploaded Successfully..!"});
                        location.reload(1);
                        });
                        uploadform.attachEvent("onUploadFail",function(realName){
                        dhtmlx.message({ type:"error", text:"Network membership can create one Flipbook. Please upgrade to featured."});
                        });
                        //dhxSendMesgToClientlayout .cells("a").attachURL("file_upload.php?TB_iframe=true&height=140&width=400&modal=true",true);
                     }}
                 }*/
                 </script>
                 <link rel="stylesheet" href="{$GLOBALS['site']['url']}/lightbox/thickbox.css" type="text/css" media="screen"/>
                 <div  style="width: 100%; margin: 0pt;" class="button_wrapper" >
					<div class="button_wrapper_close" ></div>
    					<div class="button_input_wrapper" style="line-height:10px">
                                  <img src="{$GLOBALS['site']['url']}modules/aqb/pcomposer/templates/base/images/icons/new_block.png" alt="Upload Profile" style="vertical-align:top;margin-top:5px;margin-left:4px;"  >
    					<input onclick="newWin(this)" type='button' name='contact' value='Upload Profile' class='form_input_submit' style="padding-left:0px;"/>
    					</div>
				</div>
              </td>




			<!-- <td width="50%"></td> -->
                	<td width="50%">
                    		<div  style="width: 100%; margin: 0pt;" class="button_wrapper">
					<div class="button_wrapper_close"></div>
    					<div class="button_input_wrapper">
    						{$this->generateprofilePDF()}
    					</div>
				</div>
                	</td>
      		</tr>

                </tbody>
    	</table>
        <div style="display: none;" id="ajaxy_popup_result_div_{$this->aMenuInfo['profileID']}">
	&nbsp;
</div>
    </div>
</div>
</div>

EOH;
	
}
 if( !($this->aMenuInfo['profileID']==getLoggedId()) )
                {

        $sLoginSection=<<<EOH
            <div class="menu_user_actions"><div class="actions?ontainer">
    <div class="actionsBlock">
    	<table cellspacing="0" cellpadding="3px" width="100%">
            <tbody>
<tr><td width="50%"></td><td width="50%"></td></tr>
                <tr><td width="50%"></td>
                <td width="50%">
                    <div  style="width: 100%; margin: 0pt;" class="button_wrapper">
	<div class="button_wrapper_close"></div>
    <div class="button_input_wrapper">
    {$this->generateprofilePDF()}
    </div>
</div>
                </td>




                </tr>


                </tbody>
    	</table>

    </div>
</div>
</div>
EOH;
        }
        if(BX_ProfileBlocks_PAGE==1)
            $sLoginSection='';
                    }
        else{
            $sLoginSection='';
        }
                }

        /////Picture////////
        if ($this->sCustomSubHeader == '' && !empty($this->aMenuInfo['profileID'])) {
            $sPictureEl = get_member_icon($this->aMenuInfo['profileID'], 'left');

            $sSubCapIcon = getTemplateIcon('_submenu_capt_right.gif');
            $aProfInfo = getProfileInfo($this->aMenuInfo['profileID']);
            $sProfStatusMessage = process_line_output($aProfInfo['UserStatusMessage']);
            $sRealWhen = ($aProfInfo['UserStatusMessageWhen'] != 0) ? $aProfInfo['UserStatusMessageWhen'] : time();
            $sProfStatusMessageWhen = defineTimeInterval($sRealWhen);

            if($this->aMenuInfo['memberID'] == $this->aMenuInfo['profileID']) {
                $aTmplVars = array(
                    'bx_if:show_script' => array(
                        'condition' => true,
                        'content' => array()
                    ),
                    'bx_if:show_when' => array(
                        'condition' => false && $sProfStatusMessage != '',
                        'content' => array(
                            'when' => $sProfStatusMessageWhen
                        )
                    ),
                    'bx_if:show_update' => array(
                        'condition' => true,
                        'content' => array()
                    ),
                    'message' => $sProfStatusMessage != '' ? $sProfStatusMessage : _t('_sys_status_default')
                );
                $sProfStatusMessage = $GLOBALS['oSysTemplate']->parseHtmlByName('navigation_menu_status.html', $aTmplVars);
            } else if ($sProfStatusMessage != '') {
                $aTmplVars = array(
                    'bx_if:show_script' => array(
                        'condition' => false,
                        'content' => array()
                    ),
                    'bx_if:show_when' => array(
                        'condition' => false && $sProfStatusMessage != '',
                        'content' => array(
                            'when' => $sProfStatusMessageWhen
                        )
                    ),
                    'bx_if:show_update' => array(
                        'condition' => false,
                        'content' => array()
                    ),
                    'message' => $sProfStatusMessage
                );
                $sProfStatusMessage = $GLOBALS['oSysTemplate']->parseHtmlByName('navigation_menu_status.html', $aTmplVars);
                $sProfileActions = $this->getProfileActions($aProfInfo, $this->aMenuInfo['memberID']);
            }

        } else {
            $sPictureEl = '';
            if (!empty($sPicture) && false === strpos($sPicture, '.'))
                $sPictureEl = '<i class="img_submenu sys-icon ' . $sPicture . '"></i>';
            elseif (!empty($sPicture))
                $sPictureEl = '<img class="img_submenu" src="' . getTemplateIcon($sPicture) . '" alt="" />';
        }

        if ($this->sCustomSubIconUrl && false === strpos($this->sCustomSubIconUrl, '.'))
            $sPictureEl = '<i class="img_submenu sys-icon ' . $this->sCustomSubIconUrl . '"></i>';
        elseif ($this->sCustomSubIconUrl)
            $sPictureEl = '<img class="img_submenu" src="' . $this->sCustomSubIconUrl. ' " alt="" />';

        /////Picture end////////

        if (true) { // $sSubElementCaption != '') {
            $aAllSubMainLinks = array();
            $sSubMainLinks = '';
            if (!empty($this->aTopMenu[$iFirstID]['Link'])) {
                list($aAllSubMainLinks) = explode('|', $this->aTopMenu[$iFirstID]['Link']);
                $sSubMainLinks = $this->replaceMetas($aAllSubMainLinks);

                if (empty($sSubMainLinks)) {
                    //try define the parent menu's item url
                    $sSubMainLinks = $this -> sSiteUrl . $this -> aTopMenu[ $this -> aMenuInfo['currentTop'] ]['Link'];
                }

                $sSubMainOnclick = $this->replaceMetas($this->aTopMenu[$iFirstID]['Onclick']);
            }

            $sSubMainOnclick = !empty($sSubMainOnclick) ? ' onclick="' . $sSubMainOnclick . '"' : '';

            $sCaption = <<<EOF
<a href="{$sSubMainLinks}" {$sSubMainOnclick}>{$sCaption}</a>
EOF;
            $sCaptionWL = $sCaption;
        }

        if ($this->sCustomSubHeader != '') {
            $sCaptionWL = $this->sCustomSubHeader;
        }

        if ($this->sCustomActions != '') {
            $sProfileActions = $this->sCustomActions;
        }

        $sSubmenu = $this->genSubItems($iTItemID);

        // array of keys
        $aTemplateKeys = array (
            'submenu_id' => $iTItemID,
            'display_value' => $sDisplay,
            'picture' => $sPictureEl,
            'caption' => $sCaptionWL,
            'bx_if:show_status' => array(
                'condition' => $sProfStatusMessage != '',
                'content' => array(
                    'status' => $sProfStatusMessage
                )
            ),
            'bx_if:show_submenu' => array(
                'condition' => $sProfStatusMessage == '' && $sSubmenu != '',
                'content' => array(
                    'submenu' => $sSubmenu
                )
            ),
            'bx_if:show_empty' => array(
                'condition' => $sProfStatusMessage == '' && $sSubmenu == '',
                'content' => array(
                    'content' => ''
                )
            ),
            'bx_if:show_submenu_bottom' => array(
                'condition' => $sProfStatusMessage != '' && $sSubmenu != '',
                'content' => array(
                    'submenu' => $sSubmenu
                )
            ),
            'login_section'   => $sLoginSection,
            'profile_actions' => $sProfileActions,
            'injection_title_zone' => $sProfileActions
        );
        $this->sCode .= $GLOBALS['oSysTemplate']->parseHtmlByName('navigation_menu_sub_header.html', $aTemplateKeys);

        //--- BreadCrumb ---//
        $aBreadcrumb = array();
        if($iFirstID > 0 && $sCaption != '')
            $aBreadcrumb[] = $sCaption;
        if($sSubElementCaption != '')
            $aBreadcrumb[] = $sSubElementCaption;

        $this->sBreadCrumb = $this->genBreadcrumb($aBreadcrumb);
    }

    function getProfileActions($p_arr, $iMemberID)
    {
        $iViewedMemberID = (int)$p_arr['ID'];

        if( (!$iMemberID  or !$iViewedMemberID) or ($iMemberID == $iViewedMemberID) )
            return null;

        // prepare all needed keys
        $p_arr['url']  			= $this->sSiteUrl;
        $p_arr['window_width'] 	= $this->oTemplConfig->popUpWindowWidth;
        $p_arr['window_height']	= $this->oTemplConfig->popUpWindowHeight;
        $p_arr['anonym_mode']	= $this->oTemplConfig->bAnonymousMode;

        $p_arr['member_id']		= $iMemberID;
        $p_arr['member_pass']	= getPassword( $iMemberID );

        $GLOBALS['oFunctions']->iDhtmlPopupMenu = 1;
        return $GLOBALS['oFunctions']->genObjectsActions($p_arr, 'Profile', true);
    }

    function getAllSubMenus($iItemID, $bActive = false)
    {
        $aMenuInfo = $this->aMenuInfo;

        $ret = '';

        $aTTopMenu = $this->aTopMenu;

        foreach( $aTTopMenu as $iTItemID => $aTItem ) {

            if( !$this->checkToShow( $aTItem ) )
                continue;

            if ($iItemID == $aTItem['Parent']) {
                //generate
                list( $aTItem['Link'] ) = explode( '|', $aTItem['Link'] );

                $aTItem['Link'] = str_replace( "{memberID}",    isset($aMenuInfo['memberID']) ? $aMenuInfo['memberID'] : '',    $aTItem['Link'] );
                $aTItem['Link'] = str_replace( "{memberNick}",  isset($aMenuInfo['memberNick']) ? $aMenuInfo['memberNick'] : '',  $aTItem['Link'] );
                $aTItem['Link'] = str_replace( "{memberLink}",  isset($aMenuInfo['memberLink']) ? $aMenuInfo['memberLink'] : '',  $aTItem['Link'] );

                $aTItem['Link'] = str_replace( "{profileID}",   isset($aMenuInfo['profileID']) ? $aMenuInfo['profileID'] : '',   $aTItem['Link'] );
                $aTItem['Onclick'] = str_replace( "{profileID}", isset($aMenuInfo['profileID']) ? $aMenuInfo['profileID'] : '',   $aTItem['Onclick'] );

                $aTItem['Link'] = str_replace( "{profileNick}", isset($aMenuInfo['profileNick']) ? $aMenuInfo['profileNick'] : '', $aTItem['Link'] );
                $aTItem['Onclick'] = str_replace( "{profileNick}", isset($aMenuInfo['profileNick']) ? $aMenuInfo['profileNick'] : '', $aTItem['Onclick'] );

                $aTItem['Link'] = str_replace( "{profileLink}", isset($aMenuInfo['profileLink']) ? $aMenuInfo['profileLink'] : '', $aTItem['Link'] );

                $aTItem['Onclick'] = str_replace( "{memberID}", isset($aMenuInfo['memberID']) ? $aMenuInfo['memberID'] : '',    $aTItem['Onclick'] );
                $aTItem['Onclick'] = str_replace( "{memberNick}",  isset($aMenuInfo['memberNick']) ? $aMenuInfo['memberNick'] : '',  $aTItem['Onclick'] );
                $aTItem['Onclick'] = str_replace( "{memberPass}",  getPassword( isset($aMenuInfo['memberID']) ? $aMenuInfo['memberID'] : ''),  $aTItem['Onclick'] );

                $sElement = $this->getCustomMenuItem( _t( $aTItem['Caption'] ), $aTItem['Link'], $aTItem['Target'], $aTItem['Onclick'], ( $iTItemID == $aMenuInfo['currentCustom'] ) );

                $ret .= $sElement;
            }
        }

        return $ret;
    }

    function getCustomMenuItem($sText, $sLink, $sTarget, $sOnclick, $bActive, $bSub = false)
    {
        $sIActiveClass = ($bActive) ? ' active' : '';
        $sITarget = (strlen($sTarget)) ? $sTarget : '_self';
        $sILink = (strpos($sLink, 'http://') === false && strpos($sLink, 'https://') === false && !strlen($sOnclick)) ? $this->sSiteUrl . $sLink : $sLink;
        $sIOnclick = (strlen($sOnclick)) ? 'onclick="'.$sOnclick.'"' : '';

        return <<<EOF
<li>
    <a href="{$sILink}" target="{$sITarget}" {$sIOnclick} class="button more_ntop_element{$sIActiveClass}">{$sText}</a>
</li>
EOF;
    }

    function GenMoreElementBegin()
    {
        $sMoreIcon = getTemplateIcon("tm_sitem_down.gif");

        $sMoreMainCaption = _t('_sys_top_menu_more');

        $this->sCode .= <<<EOF
<td class="top">
    <a href="javascript: void(0);" onclick="void(0);" class="top_link">
        <span class="down bx-def-padding-sec-leftright">{$sMoreMainCaption}</span>
        <!--[if gte IE 7]><!--></a><!--<![endif]-->
        <!--[if lte IE 6]><table id="mmm"><tr><td><![endif]-->
        <div style="position:relative;display:block;">
        <ul class="sub">
EOF;
    }

    function genTopItemMore($sText, $sLink, $sTarget, $sOnclick, $bActive, $iItemID)
    {
        $sIActiveClass = ($bActive) ? ' active' : '';
        $sITarget = (strlen($sTarget)) ? $sTarget : '_self';
        $sILink = (strpos($sLink, 'http://') === false && strpos($sLink, 'https://') === false && !strlen($sOnclick)) ? $this->sSiteUrl . $sLink : $sLink;
        $sIOnclick = (strlen($sOnclick)) ? 'onclick="'.$sOnclick.'"' : '';

        $sSubMenu = $this->getAllSubMenus($iItemID);

        $sActiveStyle = ($bActive) ? 'active' : '';
        $sSpacerIcon = getTemplateIcon( 'spacer.gif' );

        $this->iJSTempCnt++;

        if ($sSubMenu == '') {
            $this->sCode .= <<<EOF
<li class="{$sActiveStyle}">
    <a href="{$sILink}" {$sIOnclick} value="{$sText}" class="button more_ntop_element{$sIActiveClass}">{$sText}</a>
</li>
EOF;
        } else {
            $this->sCode .= <<<EOF
<li class="{$sActiveStyle}">
    <div class="more_sub">
        <ul id="ul{$this->iJSTempCnt}" class="more_sub">{$sSubMenu}</ul>
    </div>
    <a href="{$sILink}" {$sIOnclick} value="{$sText}" class="button more_top_element{$sIActiveClass}" style="margin-left:0px;">{$sText}</a>
    <div class="clear_both"></div>

</li>
EOF;
        }
        $this->iJSTempCnt++;
    }

    function GenMoreElementEnd()
    {
        $this->sCode .= <<<EOF
            <li class="li_last_round">&nbsp;</li>
        </ul>
    </div>
    <div class="clear_both"></div>
    <!--[if lte IE 6]></td></tr></table></a><![endif]-->
</td>
EOF;
    }

    /*
     * param is array of Path like
     * $aPath[0] = '<a href="">XXX</a>'
     * $aPath[1] = '<a href="">XXX1</a>'
     * $aPath[2] = 'XXX2'
     */
    function genBreadcrumb($aPath = array())
    {
        $sRootItem = '<a href="' . $this->sSiteUrl . '">' . _t('_Home') . '</a>';

        if (!empty($this->aCustomBreadcrumbs)) {
            $a = array();
            foreach ($this->aCustomBreadcrumbs as $sTitle => $sLink)
                if ($sTitle)
                    $a[] = $sLink ? '<a href="' . $sLink . '">' . $sTitle . '</a>' : $sTitle;
            $aPath = array_merge(array($sRootItem), $a);
        } elseif(!is_array($aPath) || empty($aPath)) {
            $aPath = array($sRootItem);
        } else {
            $aPath = array_merge(array($sRootItem), $aPath);
        }

        //define current url for single page (not contain any child pages)
        if( $this -> aMenuInfo['currentTop'] != -1 && count($aPath) == 1) {
            $aPath[] =  _t($this -> aTopMenu[ $this -> aMenuInfo['currentTop'] ]['Caption']);
        }

        //--- Get breadcrumb path(left side) ---//
        $sDivider = '<div class="bc_divider bx-def-margin-sec-left">&#8250;</div>';
        $aPathLinks = array();
        foreach($aPath as $sLink)
            $aPathLinks[] = '<div class="bc_unit bx-def-margin-sec-left">' . $sLink . '</div>';
        $sPathLinks = implode($sDivider, $aPathLinks);

        //--- Get additional links(right side) ---//
        $sAddons = "";
        return '<div class="sys_bc bx-def-margin-leftright">' . $sPathLinks . '<div class="bc_addons">' . $sAddons . '</div></div>';
    }
	function generateprofilePDF()
	{

       $getTypeDetails  = $this->getuserProfileType(getLoggedId());
       $ptype   = $getTypeDetails['ProfileType'];
       $getloggedID = getLoggedId();

        $test =  $this->curPageURL();
        $urlparts = explode("/",$test);
        // print_r($urlparts);echo'sdf';exit();
        $nick = $urlparts[3];
        //echo "asdasd".$nick;exit();
       // $getuserIDs = $this->getguestID($nick);
    //echo $nick;exit();
        $guestid  = $this->getguestID($nick);



        $ptype         = $getTypeDetails['ProfileType'];


        $uid = $_GET['ID'];
        $parentID = $_GET['parentID'];
        $userID = $_GET['userID'];




       if($ptype == 8) {

         $getDefaultDetails       = $this->getuserDefaultPDF($guestid);

         if($parentID != '') {

         $getDefaultDetails       = $this->getuserDefaultPDF($parentID);
               }

          if($parentID != '' && $userID != '') {

         $getDefaultDetails       = $this->getuserDefaultPDF($userID);
               }



              }
       else {

         if($getloggedID != '') {
         $getDefaultDetails       = $this->getuserDefaultPDF(getLoggedId());
          }

         else {

         $getDefaultDetails       = $this->getuserDefaultPDF($guestid);
                      }

               }





        //$getDefaultDetails       = $this->getuserDefaultPDF(getLoggedId());

        $getDefaulTempID         = $getDefaultDetails['template_user_id'];
        $getorgTempID            = $getDefaultDetails['template_id'];

       if($getorgTempID == 0)
        {
           // $view_url   = $GLOBALS['site']['url']."PDFTemplates/user/".getLoggedId()."_".$getDefaulTempID."_".$getDefaultDetails['pdfdate'].".pdf";

          if($ptype == 8) {
             if($getDefaulTempID)
                $view_url   = $GLOBALS['site']['url']."PDFTemplates/user/".$guestid."_".$getDefaulTempID."_".$getDefaultDetails['pdfdate'].".pdf";
             else
                 $view_url  = "";

                 }

             else {

             if($getloggedID != '') {

            
             if($getDefaulTempID)
               $view_url   = $GLOBALS['site']['url']."PDFTemplates/user/".getLoggedId()."_".$getDefaulTempID."_".$getDefaultDetails['pdfdate'].".pdf";
              
             else
                 $view_url  = "";
           		   
			}
			
             else {
                 if($getDefaulTempID)
                 $view_url   = $GLOBALS['site']['url']."PDFTemplates/user/".$guestid."_".$getDefaulTempID."_".$getDefaultDetails['pdfdate'].".pdf";
                 else
                 $view_url  = "";

             }

              }



            $genPRFPDF          = '<link rel="STYLESHEET" type="text/css" href="PDFUser/Processing/dialog.css">
                <script type="text/javascript" src= "PDFUser/Processing/dialog.js"></script>
                <a href="javascript:void(0);" class="printdefaultpdf"  id="'.$view_url.'" style="height:25px;">	<button class="form_input_submit" type="button" Style="margin-top:0px;">
                <img src="modules/aqb/pcomposer/templates/base/images/icons/print.jpeg" alt="Save to PDF">
                Print Profile
            </button></a>
            <script type="text/javascript">
                function showProcessing()
                {
                    REDIPS.dialog.init();
                    REDIPS.dialog.op_high = 60;
                    REDIPS.dialog.fade_speed = 18;
                    REDIPS.dialog.show(183, 17, \'<img width="183" height="17" title="" alt="" src="data:image/gif;base64,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" />\');
                }
                function HideBar()
                 {
                        REDIPS.dialog.hide("undefined");

                 }
                $(".printdefaultpdf").click( function () {xProcessPrintPDF12($(this));});
                function xProcessPrintPDF12(item)
                {
                     if(item.attr("id"))
                        window.open(item.attr("id"));
                     else
                        alert("There is no Deault PDF set");
                }
            </script>';
        }
        else
        {


          if(!$getloggedID)
            {
                $printStyle = 'Style="margin-top:25px;"';
            }
            else
            {
               $printStyle = '';

            }

        $genPRFPDF = '
                <link rel="STYLESHEET" type="text/css" href="PDFUser/Processing/dialog.css">
                <script type="text/javascript" src= "PDFUser/Processing/dialog.js"></script>
                <script type="text/javascript" src="documentViewer/controller/FlexPaperComponent.js"></script>
                <a href="javascript:void(0);" class="printdefaultpdf"  id="'.$getDefaulTempID.'" style="height:25px;">	<button class="form_input_submit" '.$printStyle.' type="button" >
                <img src="modules/aqb/pcomposer/templates/base/images/icons/print.jpeg" alt="Save to PDF">
                Print Profile
            </button></a>
            <script type="text/javascript">
                function showProcessing()
                {
                    REDIPS.dialog.init();
                    REDIPS.dialog.op_high = 60;
                    REDIPS.dialog.fade_speed = 18;
                    REDIPS.dialog.show(183, 17, \'<img width="183" height="17" title="" alt="" src="data:image/gif;base64,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" />\');
                }
                function HideBar()
                 {
                        REDIPS.dialog.hide("undefined");

                 }
                $(".printdefaultpdf").click( function () {xProcessPrintPDF12($(this));});
                function xProcessPrintPDF12(item)
                {  
                    if(item.attr("id"))
                    {

                         showProcessing();
                         $.ajax({
                                     url: "PDFUser/regenearate_pdf.php",
                                     type: "POST",
                                     cache: false,
                                     data: {sel_tmpuser_ID : item.attr("id")},
                                     datatype : "json",
                                     success: function(data){
                                         //alert(data.filename);
										 // console.log(data);
                                         HideBar();
                                       //  window.open(data.filename);
                                       
         var flexvalues = {
           icons_path: \'documentViewer/icons/32px/\' // mandatory
          ,application_url: \'http://ctpf01.parentfinder.com/documentViewer/\' // mandatory
          ,application_path: \'/var/www/html/pf/PDFTemplates/user/\' // mandatory
           ,pdf_name: data.pdfFilename  // mandatory
          ,split_mode: false // not mandatory
          ,magnification: 1.3  // not mandatory, default 1.1
          }
         FlexPaperComponent.callFlexPaper(flexvalues); 





                                         return false;

                                    }
                         });
                     }
                     else
                        alert("There is no Deault PDF set");
                }
            </script>
            ';
        }
		return $genPRFPDF;
	}



	function getuserDefaultPDF($userID)
	{
		 $pdfUserDefault          = "";
		 $sql_pdfuserDet         = "SELECT
								ptu.template_user_id,
								ptu.user_id,
								ptu.template_id,
                                DATE_FORMAT(ptu.lastupdateddate, '%Y-%m-%d') AS pdfdate
								FROM pdf_template_user ptu
								WHERE ptu.user_id = $userID AND ptu.isDeleted = 'N' AND ptu.isDefault ='Y'";
		 //echo $sql_pdfuserDet;
		 $pdfUserDet             = mysql_query($sql_pdfuserDet);
		 if(mysql_numrows($pdfUserDet) > 0)
		 {
			  while($row_pdfsdet = mysql_fetch_assoc($pdfUserDet))
			  {
					$pdfUserDefault = $row_pdfsdet;
			  }
		 }
		 return $pdfUserDefault;
	}

     function getuserProfileType($userID)
	{
		 $userProfileType          = "";
		 $sql_userProfileType         = "SELECT	ProfileType FROM Profiles WHERE ID = $userID";
		// echo $sql_userProfileType;exit();

		 $userProfileTypedet            = mysql_query($sql_userProfileType);
		 if(mysql_numrows($userProfileTypedet) > 0)
		 {
			  while($row_Profiledet = mysql_fetch_assoc($userProfileTypedet))
			  {
					$userProfileType = $row_Profiledet;


			  }
		 }
		 return $userProfileType;
	}


function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}



function getguestID($name)
	{
                $pdfUserID  = "";
		 $sql_pdfuserIDDet         = "SELECT
								ID
								FROM Profiles
								WHERE NickName = '$name'";
		// echo $sql_pdfuserIDDet;exit();
		 $pdfUserIDDet             = mysql_query($sql_pdfuserIDDet);
		 if(mysql_numrows($pdfUserIDDet) > 0)
		 {
			  $row_pdfsIDdet = mysql_fetch_assoc($pdfUserIDDet);
			  
			  $pdfUserID = $row_pdfsIDdet['ID'];

                                       
              			  }

		 return $pdfUserID;                      

}
    function getScriptFriendAdd($iId, $iMemberId, $bShowResult = true)
    {
        if(!isLogged() || $iId == $iMemberId || is_friends($iId, $iMemberId))
            return;

        $sOnResult = $bShowResult ? "$('#ajaxy_popup_result_div_" . $iId . "').html(sData);" : "document.location.href=document.location.href;";
        return "$.post('list_pop.php?action=friend', {ID: " . $iId . "}, function(sData){" . $sOnResult . "}); return false;";
    }
    function getScriptFriendAccept($iId, $iMemberId, $bShowResult = true)
    {
        if(!isLogged() || $iId == $iMemberId || !isFriendRequest($iId, $iMemberId))
            return;

        $sOnResult = $bShowResult ? "$('#ajaxy_popup_result_div_" . $iId . "').html(sData);" : "document.location.href=document.location.href;";
        return "$.post('list_pop.php?action=friend', {ID: " . $iId . "}, function(sData){" . $sOnResult . "}); return false;";
    }
    function getScriptFriendCancel($iId, $iMemberId, $bShowResult = true)
    {
        if(!isLogged() || $iId == $iMemberId || !is_friends($iId, $iMemberId))
            return;

        $sOnResult = $bShowResult ? "$('#ajaxy_popup_result_div_" . $iId . "').html(sData);" : "document.location.href=document.location.href;";
        return "$.post('list_pop.php?action=remove_friend', {ID: " . $iId . "}, function(sData){" . $sOnResult . "}); return false;";
    }

    function getScriptFaveAdd($iId, $iMemberId, $bShowResult = true)
    {
        if(!isLogged() || $iId == $iMemberId || isFaved($iMemberId, $iId))
            return;

        $sOnResult = $bShowResult ? "$('#ajaxy_popup_result_div_" . $iId . "').html(sData);" : "document.location.href=document.location.href;";
        return "$.post('list_pop.php?action=hot', {ID: " . $iId . "}, function(sData){" . $sOnResult . "}); return false;";
    }
    function getScriptFaveCancel($iId, $iMemberId, $bShowResult = true)
    {
        if(!isLogged() || $iId == $iMemberId || !isFaved($iMemberId, $iId))
            return;

        $sOnResult = $bShowResult ? "$('#ajaxy_popup_result_div_" . $iId . "').html(sData);" : "document.location.href=document.location.href;";
        return "$.post('list_pop.php?action=remove_hot', {ID: " . $iId . "}, function(sData){" . $sOnResult . "}); return false;";
    }

    function getUrlProfileMessage($iId)
    {
        if(!isLogged() || $iId == getLoggedId())
            return;

        return BX_DOL_URL_ROOT . 'mail.php?mode=compose&recipient_id=' . $iId;
    }
    function getUrlProfilePage($iId)
    {
        if(!isLogged() || $iId != getLoggedId())
            return;

        return getProfileLink($iId);
    }
    function getUrlAccountPage($iId)
    {
        if(!isLogged() || $iId != getLoggedId())
            return;

        return BX_DOL_URL_ROOT . 'member.php';
    }
}
