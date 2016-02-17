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

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );

require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_PLUGINS . 'Services_JSON.php' );
// --------------- page variables and login

$_page['name_index']	= 11;
$_page['css_name']		= 'about_us.css';

check_logged();

$_page['header'] ="Change Password";
$_page['header_text'] = "Password Panel";




// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompMainCode();

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompMainCode() {
	global $oTemplConfig;
	$sForm.= <<<EOH
    
EOH;
        if($_POST['Cancel']){
    if($_GET['ID'])
    {
        $_Agency_uri=mysql_fetch_assoc(mysql_query("SELECT uri FROM bx_groups_main WHERE author_id={$_COOKIE['memberID']}"));
       // header("Location:{$site['url']}m/groups/browse_fans_list/{$_Agency_uri['uri']}");
        header("Location:{$site['url']}administration/profiles.php");
    }else{
   // header('Location:member.php');
        header("Location:{$site['url']}administration/profiles.php");
    }
}

        $msg="";
        if($_GET['ID'])
            $_memID=$_GET['ID'];
        else
            $_memID=$_COOKIE['memberID'];
$member_username=mysql_fetch_assoc(mysql_query("SELECT NickName FROM Profiles WHERE ID={$_memID}"));

if($_POST['submit']&& $_memID){
    if($_POST['pass']=="" ||$_POST['vpass']==""){
        
        $msg="Password cannot be left empty!";
       }else{
          if($_POST['pass']==$_POST['vpass']){
            $sSalt = genRndSalt();
            $_pass=encryptUserPwd($_POST['pass'], $sSalt);
            mysql_query("UPDATE Profiles SET Password= '{$_pass}' , Salt = '{$sSalt}' WHERE ID = '{$_memID}'");
            mysql_query("UPDATE Profiles_draft SET Password= '{$_pass}' , Salt = '{$sSalt}' WHERE ID = '{$_memID}'");
              $msg="Password changed successfully!";
              $_result=cleanALL();
             
            }else{
                $msg="Verify password did not match!";
            }
     }
}

$sForm.= <<<EOH

<form name="frmEmail" method="post">
<table width="400" border="0" style="margin:0 auto;" cellpadding="0" cellspacing="5">
<tr><td colspan="2" style="margin:0 auto;"><h3>Change Password for user "{$member_username['NickName']}"</h3></td></tr>
<tr><td colspan="2" style="margin:0 auto;"><div style="color:Blue">{$msg}</div></td></tr>
<tr><td colspan="2" style="margin:0 auto;"></td></tr>
<tr><td>New Password:</td><td><input name="pass" size="20" type="password" /></td></tr>
<tr><td>Verify Password:</td><td><input name="vpass" size="20" type="password" /></td></tr>
<tr><td colspan="2" style="margin:0 auto;"><hr /></td></tr>
<tr><td align="right"><input name="submit" type="submit" value="Save" /></td><td><input name="Cancel" type="submit" value="Back" /></td></tr>
</table>
</form>

EOH;
	return DesignBoxContent( "Change Password", $sForm, $oTemplConfig -> PageCompThird_db_num );
}

function cleanALL()
{
    $aResult = array();
        //member menu
    	bx_import('BxDolMemberMenu');
        $oMemberMenu = new BxDolMemberMenu();
        $oMemberMenu -> deleteMemberMenuCaches();

        // page blocks
        bx_import('BxDolPageViewAdmin');
        $oPageViewCacher = new BxDolPageViewCacher ('', '');
        $oCachePb = $oPageViewCacher->getBlocksCacheObject ();
        $aResult = clearCacheObject ($oCachePb, 'pb_');
        if($aResult['code'] != 0)
            break;

        // users
        $aResult = clearCache('user', BX_DIRECTORY_PATH_CACHE);
        if($aResult['code'] != 0)
            break;

        // DB
        $oCacheDb = $GLOBALS['MySQL']->getDbCacheObject();
        $aResult = clearCacheObject ($oCacheDb, 'db_');
        if($aResult['code'] != 0)
            break;

        // templates
        $oCacheTemplates = $GLOBALS['oSysTemplate']->getTemplatesCacheObject();
        $aResult = clearCacheObject($oCacheTemplates, $GLOBALS['oSysTemplate']->_sCacheFilePrefix);
        if($aResult['code'] != 0)
            break;

        // CSS
        $aResult = clearCache($GLOBALS['oSysTemplate']->_sCssCachePrefix, BX_DIRECTORY_PATH_CACHE_PUBLIC);
        if($aResult['code'] != 0)
            break;

        // JS
        $aResult = clearCache($GLOBALS['oSysTemplate']->_sJsCachePrefix, BX_DIRECTORY_PATH_CACHE_PUBLIC);
        return $aResult;
}

function clearCacheObject($oCache, $sPrefix) {
    if (!$oCache->removeAllByPrefix ($sPrefix))
        return array('code' => 1, 'message' => _t('_adm_txt_dashboard_cache_clean_failed'));
    else
        return array('code' => 0, 'message' => _t('_adm_txt_dashboard_cache_clean_success'));
}

function clearCache($sPrefix, $sPath) {
    $aResult = array('code' => 0, 'message' => _t('_adm_txt_dashboard_cache_clean_success'));

    if($rHandler = opendir($sPath)) {
        while(($sFile = readdir($rHandler)) !== false)
            if(substr($sFile, 0, strlen($sPrefix)) == $sPrefix)
                @unlink($sPath . $sFile);
    }
    else
        $aResult = array('code' => 1, 'message' => _t('_adm_txt_dashboard_cache_clean_failed'));

    return $aResult;
}

?>
