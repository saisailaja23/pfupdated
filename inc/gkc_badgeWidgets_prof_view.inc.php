<?php

require_once('inc/header.inc.php');
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

function draw_profile ($aData = array()) {
    define('PF_DESC_LENGHT', 500);
	$oTmpl = &$GLOBALS['oSysTemplate'];
    $id = $aData['ID'];
    $sQuery = "SELECT * FROM `RayVideoFiles` WHERE `Owner` = '$id'";
    $rRes = mysql_query( $sQuery );
    $numrow = mysql_num_rows($rRes); 
	$aInfo = array(
		'name' => $aData['FirstName'],
		'desc' => trim(strip_tags($aData['DearBirthParent'])),
		'pic_url' => get_avatar_Url($aData['Avatar'],$aData['Sex']),
		'link' => getProfileLink($aData['ID']),
		'read_more_key' => _t('_Read more'),
        'video' => $numrow == 0 ? '':'<img src="http://www.parentfinder.com/video.jpg">',
	);
	
	if (mb_strlen($aInfo['desc']) > PF_DESC_LENGHT)
		$aInfo['desc'] = mb_substr($aInfo['desc'], 0, PF_DESC_LENGHT) . '...';

	if ($aData['Couple'] > 0) {
		$aCoupleInfo = getProfileInfo($aData['Couple']);
		$aInfo['name'] .= ' &amp; ' . $aCoupleInfo['FirstName'];
	}
	return $oTmpl->parseHtmlByName('badge_profile_view.html', $aInfo);
}

function draw_profile2 ($aData = array()) {
    define('PF_DESC_LENGHT', 210);
	$oTmpl = &$GLOBALS['oSysTemplate'];
    $id = $aData['ID'];
    $sQuery = "SELECT * FROM `RayVideoFiles` WHERE `Owner` = '$id'";
    $rRes = mysql_query( $sQuery );
    $numrow = mysql_num_rows($rRes);

    $sQuery2 = "SELECT * FROM `bx_photos_main` WHERE `Owner` = '$id'";
    $rRes2 = mysql_query( $sQuery2 );
    $numrow2 = mysql_num_rows($rRes2);


    $sQuery3 = "SELECT * FROM `bx_blogs_main` WHERE `OwnerID` = '$id'";
    $rRes3 = mysql_query( $sQuery3 );
    $numrow3 = mysql_num_rows($rRes3);
  
	$aInfo = array(
		'name' => $aData['FirstName'],
		'desc' => trim(strip_tags($aData['DearBirthParent'])),
		'pic_url' => get_avatar_Url($aData['Avatar'],$aData['Sex']),
		//'link' => 'http://www.parentfinder.com/iframe.php?profile='.$aData['NickName'],
                'link' => BX_DOL_URL_ROOT.'extra_profile_view_17.php?id='.$id,
		'read_more_key' => _t('_Read more'),
        'video' => $numrow == 0 ? '':'<img src="http://www.parentfinder.com/video.jpg">',
        'photo' => $numrow2 == 0 ? '':'<img src="http://www.parentfinder.com/photo.jpg">',
        'blog' => $numrow3 == 0 ? '':'<img src="http://www.parentfinder.com/bx_blogs.png">',
	);
	
	if (mb_strlen($aInfo['desc']) > PF_DESC_LENGHT)
		$aInfo['desc'] = mb_substr($aInfo['desc'], 0, PF_DESC_LENGHT) . '...';

	if ($aData['Couple'] > 0) {
		$aCoupleInfo = getProfileInfo($aData['Couple']);
		$aInfo['name'] .= ' &amp; ' . $aCoupleInfo['FirstName'];
	}
	return $oTmpl->parseHtmlByName('badge_profile_view2.html', $aInfo);
}

function draw_profile3 ($aData = array()) {

    define('PF_DESC_LENGHT', 500);

	$oTmpl = &$GLOBALS['oSysTemplate'];
    $id = $aData['ID'];
    $sQuery = "SELECT * FROM `RayVideoFiles` WHERE `Owner` = '$id'";
    $rRes = mysql_query( $sQuery );
    $numrow = mysql_num_rows($rRes);

    $sQuery2 = "SELECT * FROM `bx_photos_main` WHERE `Owner` = '$id'";
    $rRes2 = mysql_query( $sQuery2 );
    $numrow2 = mysql_num_rows($rRes2);


    $sQuery3 = "SELECT * FROM `bx_blogs_main` WHERE `OwnerID` = '$id'";
    $rRes3 = mysql_query( $sQuery3 );
    $numrow3 = mysql_num_rows($rRes3);
  
	$aInfo = array(
		'name' => $aData['FirstName'],
		'desc' => trim(strip_tags($aData['DearBirthParent'])),
		'pic_url' => get_avatar_Url($aData['Avatar'],$aData['Sex']),
		'link' => 'http://www.parentfinder.com/' . $aData['NickName'] . '/about/badge',
		//'link' => 'http://www.parentfinder.com/iframe.php?profile='.$aData['NickName'],
		'read_more_key' => _t('_Read more'),
        'video' => $numrow == 0 ? '':'<img src="http://www.parentfinder.com/video.jpg">',
        'photo' => $numrow2 == 0 ? '':'<img src="http://www.parentfinder.com/photo.jpg">',
        'blog' => $numrow3 == 0 ? '':'<img src="http://www.parentfinder.com/bx_blogs.png">',
	);
	
	if (mb_strlen($aInfo['desc']) > PF_DESC_LENGHT)
		$aInfo['desc'] = mb_substr($aInfo['desc'], 0, PF_DESC_LENGHT) . '...';

	if ($aData['Couple'] > 0) {
		$aCoupleInfo = getProfileInfo($aData['Couple']);
		$aInfo['name'] .= ' &amp; ' . $aCoupleInfo['FirstName'];
	}
	return $oTmpl->parseHtmlByName('badge_profile_view2.html', $aInfo);
}
function draw_profile4 ($aData = array()) {

    define('PF_DESC_LENGHT', 500);

	$oTmpl = &$GLOBALS['oSysTemplate'];
    $id = $aData['ID'];
    $sQuery = "SELECT * FROM `RayVideoFiles` WHERE `Owner` = '$id'";
    $rRes = mysql_query( $sQuery );
    $numrow = mysql_num_rows($rRes);

    $sQuery2 = "SELECT * FROM `bx_photos_main` WHERE `Owner` = '$id'";
    $rRes2 = mysql_query( $sQuery2 );
    $numrow2 = mysql_num_rows($rRes2);


    $sQuery3 = "SELECT * FROM `bx_blogs_main` WHERE `OwnerID` = '$id'";
    $rRes3 = mysql_query( $sQuery3 );
    $numrow3 = mysql_num_rows($rRes3);
  
	$aInfo = array(
		'name' => $aData['FirstName'],
		'desc' => trim(strip_tags($aData['DearBirthParent'])),
		'pic_url' => get_avatar_Url($aData['Avatar'],$aData['Sex']),
		'link' => 'http://www.parentfinder.com/' . $aData['NickName'] . '/about/badge',
		//'link' => 'http://www.parentfinder.com/iframe.php?profile='.$aData['NickName'],
		'read_more_key' => _t('_Read more'),
        'video' => $numrow == 0 ? '':'<img src="http://www.parentfinder.com/video.jpg">',
        'photo' => $numrow2 == 0 ? '':'<img src="http://www.parentfinder.com/photo.jpg">',
        'blog' => $numrow3 == 0 ? '':'<img src="http://www.parentfinder.com/bx_blogs.png">',
	);
	
	if (mb_strlen($aInfo['desc']) > PF_DESC_LENGHT)
		$aInfo['desc'] = mb_substr($aInfo['desc'], 0, PF_DESC_LENGHT) . '...';

	if ($aData['Couple'] > 0) {
		$aCoupleInfo = getProfileInfo($aData['Couple']);
		$aInfo['name'] .= ' &amp; ' . $aCoupleInfo['FirstName'];
	}
	return $oTmpl->parseHtmlByName('badge_profile_view5.html', $aInfo);
}
function get_avatar_Url($iAvatarId,$sSex){
    global $site;
    if($iAvatarId==0){
      if($sSex=='male'){
          $rUrl=$site['url']."templates/base/images/icons/man_medium.gif";
      }else{
          $rUrl=$site['url']."templates/base/images/icons/woman_medium.gif";
      }
    }else{
        $rUrl=$site['url']."modules/boonex/avatar/data/images/".$iAvatarId.".jpg";
    }
    return $rUrl;
}

/*function get_profile_image_url ($iUserId, $sSex) {
	$iUserId = (int)$iUserId;
	$aPhotos = BxDolService::call('photos', 'get_profile_album_files', array($iUserId, 'browse'), 'Search');
	if (!is_array($aPhotos)) {
		$aDefPic = array(
			'female' => 'badge_female.png',
			'male' => 'badge_male.png'
		);
		if ($sSex == 'female')
			$sSex = $sSex;
		else
			$sSex = 'male';
		$sPic = $GLOBALS['oSysTemplate']->getImageUrl($aDefPic[$sSex]);
	}
	else	
		$sPic = $aPhotos[0]['file'];
	return $sPic;
}*/

?>