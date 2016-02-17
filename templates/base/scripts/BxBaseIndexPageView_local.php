<?php

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -----------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2006 BoonEx Group
*     website              : http://www.boonex.com/
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software. This work is licensed under a Creative Commons Attribution 3.0 License. 
* http://creativecommons.org/licenses/by/3.0/
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the Creative Commons Attribution 3.0 License for more details. 
* You should have received a copy of the Creative Commons Attribution 3.0 License along with Dolphin, 
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/



include_once (BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/include.php');

bx_import('BxDolSubscription');
bx_import('BxTemplTags');
bx_import('BxTemplCategories');

class BxBaseIndexPageView extends BxDolPageView {
	function BxBaseIndexPageView() {
		BxDolPageView::BxDolPageView( 'index' );
	}

	/**
	 * Top Rated Profiles block (Leaders)
	 */
	function getBlockCode_Leaders() {
		$sLeaders = $sLeaderValues = '';

		$aPreferSex = array();

		$sPeriodTypesMale = getParam('leaders_male_types');
		$sPeriodTypesFemale = getParam('leaders_female_types');

		if ($sPeriodTypesMale == '' || $sPeriodTypesFemale == '') return;

		$sCurrentMode = ($_GET['leaders_mode'] == 'all' || $_GET['leaders_mode'] == 'male' || $_GET['leaders_mode'] == 'female') ? $_GET['leaders_mode'] : 'all';
		$aDBTopMenu = array();
		foreach (array('all', 'male', 'female') as $sEachMode) {
			switch ($sEachMode) {
				case 'all':
					if ($sCurrentMode == $sEachMode) {
						$aPreferSex[] = 'male';
						$aPreferSex[] = 'female';
					}
					$sModeTitle = _t('_All');
				break;
				case 'male':
					if ($sCurrentMode == $sEachMode) {
						$aPreferSex[] = 'male';
					}
					$sModeTitle = _t('_By Male');
				break;
				case 'female':
					if ($sCurrentMode == $sEachMode) {
						$aPreferSex[] = 'female';
					}
					$sModeTitle = _t('_By Female');
				break;
			}
			$aDBTopMenu[$sModeTitle] = array('href' => BX_DOL_URL_ROOT . "index.php?leaders_mode={$sEachMode}", 'dynamic' => true, 'active' => ( $sEachMode == $sCurrentMode ));
		}

		$aPeriodTypesMale = explode(',', $sPeriodTypesMale);
		$aPeriodTypesFemale = explode(',', $sPeriodTypesFemale);

		$oVoting = new BxTemplVotingView ('profile', 0, 0);

		$aLeadersList = array();
		$aPeriodTypes = array();

		$iIDYear = $iIDMonth = $iIDWeek = $iIDDay = 0;
		foreach ($aPreferSex as $sSex) {
			$aPeriodTypes[$sSex] = ($sSex=='male') ? $aPeriodTypesMale : $aPeriodTypesFemale;
			foreach ($aPeriodTypes[$sSex] as $sPeriodType) {
				switch($sPeriodType) {
					case 'year':
						$iIDYear = $oVoting->getTopVotedItem(365, '`Profiles`', '`ID`', "AND `Profiles`.`Status` = 'Active' AND `Profiles`.`Sex` = '{$sSex}'");
						$aLeadersList[$sSex]['year'] = $iIDYear;
						break;
					case 'month':
						$sYearAddon = ($iIDYear) ? "AND `ID`<>'{$iIDYear}'" : '';

						$iIDMonth = $oVoting->getTopVotedItem(30, '`Profiles`', '`ID`', "AND `Profiles`.`Status` = 'Active' {$sYearAddon} AND `Profiles`.`Sex` = '{$sSex}'");
						$aLeadersList[$sSex]['month'] = $iIDMonth;
						break;
					case 'week':
						$sYearAddon = ($iIDYear) ? "AND `ID`<>'{$iIDYear}'" : '';
						$sMonthAddon = ($iIDMonth) ? "AND `ID`<>'{$iIDMonth}'" : '';

						$iIDWeek = $oVoting->getTopVotedItem(7, '`Profiles`', '`ID`', "AND `Profiles`.`Status` = 'Active' {$sYearAddon} {$sMonthAddon} AND `Profiles`.`Sex` = '{$sSex}'");
						$aLeadersList[$sSex]['week'] = $iIDWeek;
						break;
					case 'day':
						$sYearAddon = ($iIDYear) ? "AND `ID`<>'{$iIDYear}'" : '';
						$sMonthAddon = ($iIDMonth) ? "AND `ID`<>'{$iIDMonth}'" : '';
						$sWeekAddon = ($iIDWeek) ? "AND `ID`<>'{$iIDWeek}'" : '';

						$iIDDay = $oVoting->getTopVotedItem(1, '`Profiles`', '`ID`', "AND `Profiles`.`Status` = 'Active' {$sYearAddon} {$sMonthAddon} {$sWeekAddon} AND `Profiles`.`Sex` = '{$sSex}'");
						$aLeadersList[$sSex]['day'] = $iIDDay;
						break;
				}
			}

			$sLeaderValues .= $this->genLeaderPack($aLeadersList[$sSex], $sSex);
		}

		$sLeaders = <<<EOF
<div class="clear_both"></div>
	{$sLeaderValues}
<div class="clear_both"></div>
EOF;

        $sLeaderMembers = $GLOBALS['oFunctions']->centerContent($sLeaders, '.featured_block_1');
		return array($sLeaderMembers, $aDBTopMenu);
	}

    function genLeaderPack($aMTypes, $sSex = 'male') {        
		if (is_array($aMTypes) and count($aMTypes) > 0) {
			$sVacantC = _t('_Vacant');
			$sOfflineC = _t('_Offline');
			$sMaleIcon = getTemplateIcon('male.png');
			$sFemaleIcon = getTemplateIcon('female.png');

			$sSpacerIcon = getTemplateImage('spacer.gif');
			$sOfflineIcon = getTemplateIcon('sys_status_offline.png');

			$sSexIcon = $sThumbType = '';
			switch($sSex) {
				case 'male':
					$sSexIcon = $sMaleIcon;
					$sThumbType = 'man';
					break;
				case 'female':
					$sSexIcon = $sFemaleIcon;
					$sThumbType = 'woman';
					break;
			}
            $sSexThumbIcon = getTemplateIcon($sThumbType . '_medium.gif');

			foreach ($aMTypes as $sTopType => $iMembID) {
				$sTypeName = $sTopType;
				$sTypeNameUcf = ucfirst($sTopType);
				$sLabel = _t('_' . $sTypeNameUcf);
				$oMembVoting = new BxTemplVotingView('profile', $iMembID);
				$sVotingVal = '<div class="rate_block_position">' . $oMembVoting->getJustVotingElement(0) . '</div>';
				$iVotesCnt = $oMembVoting->getVoteCount();
				$sMembThumb = $sProfileLink = '';
				if ($iMembID>0) {
					$sMembThumb = get_member_thumbnail($iMembID, 'none', false);
					$sMemberName = getNickname($iMembID);
					$sMemberLink = getProfileLink($iMembID);
					$sProfileLink = <<<EOF
<a href="{$sMemberLink}">{$sMemberName}</a>
EOF;
				} else {
					$sMembThumb = <<<EOF
<div class="thumbnail_image" style="width: 68px; height: 68px;">
    <a href="javascript:void(0)">
        <img title="{$sVacantC}" alt="{$sVacantC}" style="background-image: url({$sSexThumbIcon}); width: 64px; height: 64px;" src="{$sSpacerIcon}" />
        <img class="sys-online-offline" title="{$sOfflineC}" alt="{$sOfflineC}" src="{$sOfflineIcon}" />
    </a>
</div>
EOF;
					$sProfileLink = <<<EOF
<a class="vacant" href="javascript: void(0);">{$sVacantC}</a>
EOF;
				}

                $sVotes = _t('_{0} votes', $iVotesCnt);
				$sSLeaders .= <<<EOF
<div id="prof_of_{$sTypeName}" class="featured_block_1">
	<div class="top_rated_head">
		<div class="sex_icon" style="background:url({$sSexIcon}) center 2px no-repeat;">&nbsp;</div>
		<div class="type_vote">
			{$sLabel}<br />
			<font style="font-size:9px;color:#999;">$sVotes</font>
		</div>
		<div class="clear_both"></div>
	</div>
	{$sMembThumb}
	{$sVotingVal}
	<div class="thumb_username">{$sProfileLink}</div>
	<div class="clear_both"></div>
</div>
EOF;
			}

			return $sSLeaders;
		}
	}
	
	/**
	 * News Letters block
	 */
	function getBlockCode_Subscribe() {
		global $site;
		
		$oSubscription = new BxDolSubscription();
        $aButton = $oSubscription->getButton(0, 'system', 'mass_mailer');

		return $oSubscription->getData() . $GLOBALS['oSysTemplate']->parseHtmlByName('home_page_subscribe.html', array(
            'message' => _t('_SUBSCRIBE_TEXT', $site['title']),
            'button_title' => $aButton['title'],
            'button_script' => $aButton['script']
		));
	}
	
	/**
	 * Featured members block
	 */
	function getBlockCode_Featured() {
		$iFeatureNum = getParam('featured_num');
	    $aCode = $this->getMembers('Featured', array('Featured' => 1), $iFeatureNum);
        return $aCode;
	}


	function getBlockCode_Members() {
	 $iMaxNum = (int) getParam( "top_members_max_num" ); // number of profiles
	 $aCode = $this->getMembers('Members');

              //  foreach($temp_array as $aData){
                   // $sCode .= '<div class="featured_block_1" style="width:'.$iNewWidth.'px;">';
             //   $aOnline['is_online'] = $aData['is_online'];
            //    $sCode .= get_member_thumbnail($aData['ID'], 'none', true, 'visitor', $aOnline);
               // $sCode .= '</div>';
           // }
        // print_r($aCode);
         $aTmpl = array();
         for($i=0; $i <= (count($aCode)/4); $i+=4){
         {
         $aTmpl[] = array(
          'url' => get_member_thumbnail($aCode[$i]['ID'], 'none', true, 'visitor', $aData['is_online']),
          'url1' => get_member_thumbnail($aCode[$i+1]['ID'], 'none', true, 'visitor', $aData['is_online']),
          'url2' => get_member_thumbnail($aCode[$i+2]['ID'], 'none', true, 'visitor', $aData['is_online']),
          'url3' => get_member_thumbnail($aCode[$i+3]['ID'], 'none', true, 'visitor', $aData['is_online']),
         );
         }

           }                

    return $GLOBALS['oSysTemplate']->parseHtmlByName('members_scroll.html', array('bx_repeat:items' => array_values($aTmpl)));
        
	}
	
	function getBlockCode_Tags($iBlockId) {
	    $oTags = new BxTemplTags();
        $oTags->getTagObjectConfig(array('type' => ''));
        
        if (!empty($oTags->aTagObjects))
        {
            $aParam = array(
                'type' => isset($_REQUEST['tags_mode']) ? $_REQUEST['tags_mode'] : $oTags->getFirstObject(),
                'popular' => true,
                'limit' => getParam('tags_perpage_browse')
            );
            
            return array(
                $oTags->display($aParam, $iBlockId),
                $oTags->getTagsTopMenu($aParam),
                array(),
                ''
            );
        }
        else 
            return '';
    }
    
	function getBlockCode_Categories($iBlockId) {
        $oCategories = new BxTemplCategories();
        $oCategories->getTagObjectConfig(array('status' => 'active'));
        
        if (!empty($oCategories->aTagObjects))
        {
            $aParam = array(
                'type' => isset($_REQUEST['tags_mode']) ? $_REQUEST['tags_mode'] : $oCategories->getFirstObject(),
                'limit' => getParam('categ_perpage_browse'),
                'orderby' => 'popular'
            );
                
            return array(
                $oCategories->display($aParam, $iBlockId, '', false, getParam('categ_show_columns')),
                $oCategories->getCategTopMenu($aParam),
                array(),
                ''
            );
        }
        else
            return '';
    }


    function getBlockCode_QuickSearch() {
		$aProfile = isLogged() ? getProfileInfo() : array();

		// default params for search form
		$aDefaultParams = array(
			'LookingFor'  => $aProfile['Sex']        ? $aProfile['Sex']           : 'male',
			'Sex'         => $aProfile['LookingFor'] ? $aProfile['LookingFor']    : 'female',
			'Country'     => $aProfile['Country']    ? $aProfile['Country']       : getParam('default_country'),
			'DateOfBirth' => getParam('search_start_age') . '-' . getParam('search_end_age'),
		);

		//echoDbg($aDefaultParams);

		$oPF = new BxDolProfileFields(10);
		return $oPF->getFormCode(array('default_params' => $aDefaultParams));
    }
    	
	function getBlockCode_SiteStats() {
        return '<div class="dbContent">'.getSiteStatUser().'</div>';
    }
    
    // ----- non-block functions ----- //
    function getMembers ($sBlockName, $aParams = array(), $iLimit = 16, $sMode = 'last') {
        $aDefFields = array(
            'ID', 'NickName', 'Couple', 'Sex' 
        );
        
        $iOnlineTime = (int)getParam( "member_online_time" );
        
        //main fields
        $sqlMainFields = "";
        foreach ($aDefFields as $iKey => $sValue)
             $sqlMainFields .= "`Profiles`. `$sValue`, ";
             
        $sqlMainFields .= "if(`DateLastNav` > SUBDATE(NOW(), INTERVAL $iOnlineTime MINUTE ), 1, 0) AS `is_online`";
        
        // possible conditions
        $sqlCondition = "WHERE `Profiles`.`Status` = 'Active' and `Profiles`.`ProfileType` = 2  and (`Profiles`.`Couple` = 0 or `Profiles`.`Couple` > `Profiles`.`ID`)";
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
        $iCount = (int)db_value("SELECT COUNT(`Profiles`.`ID`) FROM `Profiles` $sqlLJoin $sqlCondition");
        
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
            //$sqlLimit = "LIMIT $sqlFrom, 9";
            
            $sqlQuery = "SELECT " . $sqlMainFields . ",Avatar FROM `Profiles` $sqlLJoin $sqlCondition $sqlOrder $sqlLimit";
          //  echo $sqlQuery;exit();

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

    function getBlockCode_Download() {    
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

        return $s;
    } 
    
	function getBlockCode_IndexSearch () {
		require_once(BX_DIRECTORY_PATH_CLASSES . 'ctzIndexSearch.php');
		$oSearch = new ctzIndexSearch();
		$oSearch->getFormArray();
		$aTmpl = array(
			'url' => BX_DOL_URL_ROOT,
			'search_href' => BX_DOL_URL_ROOT . 'search.php?search_mode=adv',
			'form' => $oSearch->getForm()
		);
		return $GLOBALS['oSysTemplate']->parseHtmlByName('index_search.html', $aTmpl);
	}   
}

?>