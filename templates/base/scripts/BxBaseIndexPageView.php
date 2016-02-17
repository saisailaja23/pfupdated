<?php

/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

include_once (BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/include.php');

bx_import('BxDolSubscription');
bx_import('BxTemplTags');
bx_import('BxTemplCategories');

class BxBaseIndexPageView extends BxDolPageView
{
    function BxBaseIndexPageView()
    {
        BxDolPageView::BxDolPageView( 'index' );
    }

    /**
     * News Letters block
     */
    function getBlockCode_Subscribe()
    {
        global $site;

        $iUserId = isLogged() ? getLoggedId() : 0;

        $oSubscription = new BxDolSubscription();
        $aButton = $oSubscription->getButton($iUserId, 'system', '');
        $sContent = $oSubscription->getData() . $GLOBALS['oSysTemplate']->parseHtmlByName('home_page_subscribe.html', array(
            'message' => _t('_SUBSCRIBE_TEXT', $site['title']),
            'button_title' => $aButton['title'],
            'button_script' => $aButton['script']
        ));

        return array($sContent, array(), array(), false);
    }

    /**
     * Featured members block
     */
    function getBlockCode_Featured()
    {
        $iFeatureNum = getParam('featured_num');
        $aCode = $this->getMembers('Featured', array('Featured' => 1), $iFeatureNum);
        return $aCode;
    }

   	
	/* function getBlockCode_Members() {
        $iMaxNum = (int) getParam( "top_members_max_num" ); // number of profiles
        $aCode = $this->getMembers('Members', array(), $iMaxNum);
        return $aCode;
    }
	*/
     function getBlockCode_Members() {
	 $iMaxNum = (int) getParam( "top_members_max_num" ); // number of profiles
	 $aCode = $this->getMembers('Members');
//print_r($aCode);exit();
              //  foreach($temp_array as $aData){
                   // $sCode .= '<div class="featured_block_1" style="width:'.$iNewWidth.'px;">';
             //   $aOnline['is_online'] = $aData['is_online'];
            //    $sCode .= get_member_thumbnail($aData['ID'], 'none', true, 'visitor', $aOnline);
               // $sCode .= '</div>';
           // }
//echo count($aCode);
        // print_r($aCode);exit();

         $aTmpl = array();
        for($i=0; $i <= (count($aCode)); $i+=5){
         {
         $aTmpl[] = array(
          'url' => get_member_thumbnail_larger($aCode[$i]['ID'], 'none', true, 'visitor', $aCode['is_online']),
          'url1' => get_member_thumbnail_larger($aCode[$i+1]['ID'], 'none', true, 'visitor', $aCode['is_online']),
          'url2' => get_member_thumbnail_larger($aCode[$i+2]['ID'], 'none', true, 'visitor', $aCode['is_online']),
          'url3' => get_member_thumbnail_larger($aCode[$i+3]['ID'], 'none', true, 'visitor', $aCode['is_online']),
          'url4' => get_member_thumbnail_larger($aCode[$i+4]['ID'], 'none', true, 'visitor', $aCode['is_online'])
        //  'url5' => get_member_thumbnail($aCode[$i+5]['ID'], 'none', true, 'visitor', $aCode['is_online']),
        //  'url6' => get_member_thumbnail($aCode[$i+6]['ID'], 'none', true, 'visitor', $aCode['is_online'])
         );
         }

           }                

    return $GLOBALS['oSysTemplate']->parseHtmlByName('members_scroll.html', array('bx_repeat:items' => array_values($aTmpl)));
        
	}



	function getBlockCode_Tags($iBlockId) {
        $oTags = new BxTemplTags();
        $oTags->getTagObjectConfig(array('type' => ''));

        if(empty($oTags->aTagObjects))
            return '';

        $aParam = array(
            'type' => isset($_REQUEST['tags_mode']) ? $_REQUEST['tags_mode'] : $oTags->getFirstObject(),
            'orderby' => 'popular',
            'limit' => getParam('tags_perpage_browse')
        );

        $sMenu = $oTags->getTagsTopMenu($aParam);
        $sContent = $oTags->display($aParam, $iBlockId);
        return array($sContent, $sMenu, array(), false);
    }

    function getBlockCode_Categories($iBlockId)
    {
        $oCategories = new BxTemplCategories();
        $oCategories->getTagObjectConfig(array('status' => 'active'));

        if(empty($oCategories->aTagObjects))
            return '';

        $aParam = array(
            'type' => isset($_REQUEST['tags_mode']) ? $_REQUEST['tags_mode'] : $oCategories->getFirstObject(),
            'limit' => getParam('categ_perpage_browse'),
            'orderby' => 'popular'
        );

        $sMenu = $oCategories->getCategTopMenu($aParam);
        $sContent = $oCategories->display($aParam, $iBlockId, '', false, getParam('categ_show_columns'));
        return array($sContent, $sMenu, array(), false);
    }

    function getBlockCode_QuickSearch()
    {
        $aProfile = isLogged() ? getProfileInfo() : array();

        // default params for search form
        $aDefaultParams = array(
            'LookingFor'  => !empty($aProfile['Sex'])        ? $aProfile['Sex']           : 'male',
            'Sex'         => !empty($aProfile['LookingFor']) ? $aProfile['LookingFor']    : 'female',
            'Country'     => !empty($aProfile['Country'])    ? $aProfile['Country']       : getParam('default_country'),
            'DateOfBirth' => getParam('search_start_age') . '-' . getParam('search_end_age'),
        );

        $oPF = new BxDolProfileFields(10);
        return array($oPF->getFormCode(array('default_params' => $aDefaultParams)), array(), array(), false);
    }

    function getBlockCode_SiteStats()
    {
        return array(getSiteStatUser(), array(), array(), false);
    }

    function getBlockCode_Download()
    {
        $a = $GLOBALS['MySQL']->fromCache('sys_box_download', 'getAll', 'SELECT * FROM `sys_box_download` WHERE `disabled` = 0 ORDER BY `order`');
        $s = '';

        foreach ($a as $r) {
            if ('_' == $r['title'][0])
                $r['title'] = _t($r['title']);
            if ('_' == $r['desc'][0])
                $r['desc'] = _t($r['desc']);

            if (0 == strncmp('php:', $r['url'], 4))
                $r['url'] = eval(substr($r['url'], 4));
            if (!$r['url'])
                continue;

            $r['icon'] = $GLOBALS['oSysTemplate']->getIconUrl($r['icon']);
            $s .= $GLOBALS['oSysTemplate']->parseHtmlByName('download_box_unit.html', $r);
        }

        return array($s, array(), array(), false);
    }
	   /**
   * Returns the promo code.
   *   
   * @todo rewrite this code for more wide using of template engine
   */
    
     function getPromoCode() {
	$sSiteUrl = BX_DOL_URL_ROOT;

	if( getParam( 'enable_flash_promo' ) != 'on' ) {
		$sCustomPromoCode = getParam( 'custom_promo_code' );
		$sCode = $sCustomPromoCode ? '<div class="promo_code_wrapper">' . $sCustomPromoCode . '</div>' : '';
	} else {
		$aImages = getPromoImagesArray();
		$iImages = count($aImages);

		$sImagesEls = '';
		foreach ($aImages as $sImg)
			$sImagesEls .= '<img src="'.$GLOBALS['site']['imagesPromo'].$sImg.'" />';

		$sPromoLink = $sSiteUrl;
		$sPromoRelocationVisitor = getParam('promo_relocation_link_visitor');
		$sPromoRelocationMember = getParam('promo_relocation_link_member');

		$sWelcomeElement = '';
		if(!isMember()) {
			//$sWelcomeC = _t('_Welcome_to_the_community');
			//$sWelcomeElement = '<div class="sys_title">' . $sWelcomeC . '</div>';
        $sWelcomeElement = '';

			$sPromoLink .= ($sPromoRelocationVisitor!='') ? $sPromoRelocationVisitor : 'join.php';

            $sLoginSection = '<div class="sys_promo"><div class="subMenuOvr">';
            $sLoginSection .= $GLOBALS['oSysTemplate']->parseHtmlByName('login_join_index.html', array());
            $sLoginSection .= '</div></div>';
		}
		else
		{
			$sLoginSection = '';
			$aInfo = getProfileInfo();
			$sWelcomeElement = '<div class="label_thumb">' . _t('_Hello member', $aInfo['NickName']) . '</div><div class="clear_both"></div>';
		}

		$sCode = '';
		if($iImages > 1) {
            $GLOBALS['oSysTemplate']->addJs('jquery.dolPromo.js');
			$sCode .= <<<EOF
				<script type="text/javascript">
					$(document).ready( function() {
						$('#indexPhoto').dolPromo(8000, 1500);
					} );
				</script>
EOF;
        }



		$sCode .= <<<EOF
            <div id="indexPhotoBorder">
		<div style="position:relative; top: 0px; left: 0px;">
                  <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
         codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0"
          width="662" height="290" id="CC4805992" align="middle">
        <param name="movie" value="MastheadVideos.swf"/>
        <param name="wmode" value="transparent"></param>
        <param name="FlashVars" VALUE="xmlfile=MastheadVideos.xml"/>
                <embed src="MastheadVideos.swf" width="662" height="290" name="CC4805992"  align="top" type="application/x-shockwave-flash" pluginspage=" pluginspage="http://www.macromedia.com/go/getflashplayer"  wmode="transparent" allowscriptaccess="sameDomain"></embed>
                </object>

            </div>


            </div>


				<div style="position: relative; top: 0px; left: 300px; z-index: 100" width: 200px;>{$sLoginSection}</div>
<div style="position: relative; top: -50px; left: 700px; z-index: 99" width: 200px;>{$sWelcomeElement}</div>

EOF;

/*
		$sCode .= <<<EOF
            <div id="indexPhotoBorder">
				<div id="indexPhotoLabel">
					{$sWelcomeElement}
					{$sLoginSection}
				</div>
    			<div id="indexPhoto" onclick="location='{$sPromoLink}'">
    				{$sImagesEls}
    			</div>
            </div>
EOF;

*/
	    }
	    return $sCode;
    }
// ----- non-block functions ----- //
    function getMembers ($sBlockName, $aParams = array(), $iLimit = 16, $sMode = 'last') {
        $aDefFields = array(
            'ID', 'NickName', 'Couple', 'Sex' , 'AdoptionAgency',
        );
        
        $iOnlineTime = (int)getParam( "member_online_time" );
        
        //main fields
        $sqlMainFields = "";
        foreach ($aDefFields as $iKey => $sValue)
             $sqlMainFields .= "`Profiles`. `$sValue`, ";
             
        $sqlMainFields .= "if(`DateLastNav` > SUBDATE(NOW(), INTERVAL $iOnlineTime MINUTE ), 1, 0) AS `is_online`";
        
        // possible conditions
       // $sqlCondition = "WHERE sys_acl_levels_members.IDLevel IN('14','15','18','20','24','23') AND `Profiles`.`Status` = 'Active' and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND sys_acl_levels_members.IDMember = `Profiles`. `ID` AND (DateExpires >= NOW() OR DateExpires IS NULL) AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) Group By sys_acl_levels_members.IDMember";
         $sqlCondition = "WHERE (sys_acl_levels_members.IDLevel IN('24') OR (sys_acl_levels_members.IDLevel IN('18','20','23') AND `Profiles`. `AdoptionAgency` = 47)) AND `Profiles`.`Status` = 'Active' and `Profiles`.`ProfileType` = 2 and `Profiles`.`Avatar` != '0'  AND sys_acl_levels_members.IDMember = `Profiles`. `ID` AND (DateExpires >= NOW() OR DateExpires IS NULL) AND (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`) Group By sys_acl_levels_members.IDMember";

        if (is_array($aParams)) {
             foreach ($aParams as $sField => $sValue)
                 $sqlCondition .= " AND `Profiles`.`$sField` = '$sValue'";
        }
       
        // top menu and sorting
        $aModes = array('last', 'top', 'online');
        $aDBTopMenu = array();
        
        $sMode = (in_array($_GET[$sBlockName . 'Mode'], $aModes)) ? $_GET[$sBlockName . 'Mode'] : $sMode = 'last';
        $sqlOrder = "";
        foreach( $aModes as $sMyMode ) {
            switch ($sMyMode) {
                case 'online':
                    if ($sMode == $sMyMode) {
                        $sqlCondition .= " AND `Profiles`.`DateLastNav` > SUBDATE(NOW(), INTERVAL ".$iOnlineTime." MINUTE)";
                       // $sqlOrder = " ORDER BY `Profiles`.`Couple` ASC";
				$sqlOrder = " ORDER BY `Profiles`.`Avatar` DESC";

                    }
                    $sModeTitle = _t('_Online');
                break;
                case 'last':
                    if ($sMode == $sMyMode)
	                        //$sqlOrder = " ORDER BY `Profiles`.`Couple` ASC, `Profiles`.`DateReg` DESC";
				//$sqlOrder = " ORDER BY `Profiles`.`Avatar` DESC";
                                $sqlOrder = " ORDER BY `Profiles`.`DateLastLogin` DESC";
                    $sModeTitle = _t('_Latest');
                break;
                case 'top':
                    if ($sMode == $sMyMode) {
                        $oVotingView = new BxTemplVotingView ('profile', 0, 0);
                        $aSql        = $oVotingView->getSqlParts('`Profiles`', '`ID`');
                        //$sqlOrder    = $oVotingView->isEnabled() ? " ORDER BY `Profiles`.`Couple` ASC, (`pr_rating_sum`/`pr_rating_count`) DESC, `pr_rating_count` DESC, `Profiles`.`DateReg` DESC" : $sqlOrder;
                        $sqlOrder    = $oVotingView->isEnabled() ? " ORDER BY rand()" : $sqlOrder;
			   $sqlMainFields .= $aSql['fields'];
                        $sqlLJoin    = $aSql['join'];
                        $sqlCondition .= " AND `pr_rating_count` > 1";
                    }   
                    $sModeTitle = _t('_Top');
                break;
            }
            //$aDBTopMenu[$sModeTitle] = array('href' => BX_DOL_URL_ROOT . "index.php?{$sBlockName}Mode=$sMyMode", 'dynamic' => true, 'active' => ( $sMyMode == $sMode ));
        }
       $iCount = (int)db_value("SELECT COUNT(`Profiles`.`ID`) FROM `Profiles`,sys_acl_levels_members $sqlLJoin $sqlCondition");
        
        $aData = array();
        $sPaginate = '';
        if ($iCount) {
            $iNewWidth = BX_AVA_W + 6;
            $iPages = ceil($iCount/ $iLimit);
            $iPage = (int)$_GET['page'];
            if ($iPage < 1)
                $iPage = 1;
            if ($iPage > $iPages)
                $iPage = $iPages;

            $sqlFrom = ($iPage - 1) * $iLimit;
          //  $sqlLimit = "LIMIT 50";
            
            $sqlQuery = "SELECT " . $sqlMainFields . ",Avatar FROM `Profiles`,sys_acl_levels_members $sqlLJoin $sqlCondition $sqlOrder $sqlLimit";
           // echo $sqlQuery;
	//$fp=fopen("/var/www/html/pf/pfLog.txt","a+");
	//fwrite($fp,$sqlQuery .";");
//	fclose($fp);
            $rData = db_res($sqlQuery);
            $iCurrCount = mysql_num_rows($rData);
            
            $aOnline = array();

            $temp_array= array();
$_avFlag=0;
            while ($aData = mysql_fetch_assoc($rData)) {

     
if($aData['Avatar']==0)
                    $_avFlag=1;
                $temp_array[]=$aData;
           /*     $sCode .= '<div class="featured_block_1" style="width:'.$iNewWidth.'px;">';
                $aOnline['is_online'] = $aData['is_online'];
                $sCode .= get_member_thumbnail($aData['ID'], 'none', true, 'visitor', $aOnline);
                $sCode .= '</div>';*/
            }
           // print_r($temp_array);
		if(!$_avFlag)
            shuffle($temp_array);
          //  if($iCurrCount){
          //  foreach($temp_array as $aData){
               // $sCode .= '<div class="featured_block_1" style="width:'.$iNewWidth.'px;">';
             //   $aOnline['is_online'] = $aData['is_online'];
            //    $sCode .= get_member_thumbnail($aData['ID'], 'none', true, 'visitor', $aOnline);
               // $sCode .= '</div>';
           // }
          //  }

		
			//$sCode = $GLOBALS['oFunctions']->centerContent($sCode, '.featured_block_1');
            
         
        } else {
            $sCode = MsgBox(_t("_Empty"));
        }
        return $temp_array;
    } 
    // ----- non-block functions ----- //


       
}



