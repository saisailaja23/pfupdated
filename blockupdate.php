<?php
include( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_PLUGINS . 'Services_JSON.php' );
        $dbhost = $db['host'] ;
            $dbuser = $db['user'] ;
            $dbpass = $db['passwd'];
            $pid       = $_COOKIE['memberID'];

            $conObject =  mysql_connect($db['host'], $db['user'], $db['passwd'],true) or die   ('Error connecting to mysql');
            mysql_select_db($db['db'],$conObject);
$msdata.= $sqlQuery ="SELECT `Couple` FROM `Profiles` WHERE ID = '".$_COOKIE['memberID']."'";
$couple = mysql_fetch_assoc(mysql_query($sqlQuery));
if($_POST['title'] == 'Parent1_description'){
    if($_POST['Submit'] == 'Save'){
             $msdata.=  "\n". $sqlQuery ="UPDATE `Profiles` SET `DescriptionMe` = '".process_db_input($_POST['PFblockstd_description1'])."' WHERE ID = '".$_COOKIE['memberID']."'";
             mysql_query($sqlQuery);
             $msdata.= "\n".  $sqlQuery ="UPDATE `Profiles_draft` SET `DescriptionMe` = '".process_db_input($_POST['PFblockstd_description1'])."' WHERE ID = '".$_COOKIE['memberID']."'";
             mysql_query($sqlQuery);

    }
    else{    $msdata.= "\n".  $sqlQuery ="UPDATE `Profiles_draft` SET `DescriptionMe` = '".process_db_input($_POST['PFblockstd_description1'])."' WHERE ID = '".$_COOKIE['memberID']."'";
             mysql_query($sqlQuery);

    }
}
if($_POST['title'] == 'Parent2_description'){
  if($_POST['Submit'] == 'Save'){
            $msdata.= "\n".  $sqlQuery ="UPDATE `Profiles` SET `DescriptionMe` = '".process_db_input($_POST['PFblockstd_description22'])."' WHERE ID = '".$couple['Couple']."'";
            mysql_query($sqlQuery);
            $msdata.=  "\n". $sqlQuery ="UPDATE `Profiles_draft` SET `DescriptionMe` = '".process_db_input($_POST['PFblockstd_description22'])."' WHERE ID = '".$couple['Couple']."'";
            mysql_query($sqlQuery);

    }
    else{   $msdata.= "\n".  $sqlQuery ="UPDATE `Profiles_draft` SET `DescriptionMe` = '".process_db_input($_POST['PFblockstd_description22'])."' WHERE ID = '".$couple['Couple']."'";
             mysql_query($sqlQuery);

    }
}
if($_POST['title'] == 'DearBirthParent'){
  if($_POST['Submit'] == 'Save'){
            $msdata.= "\n".  $sqlQuery ="UPDATE `Profiles` SET `DearBirthParent` = '".process_db_input($_POST['PFblockstd_DearBirthParent'])."' WHERE ID = '".$_COOKIE['memberID']."'";
            mysql_query($sqlQuery);
            $msdata.= "\n".  $sqlQuery ="UPDATE `Profiles_draft` SET `DearBirthParent` = '".process_db_input($_POST['PFblockstd_DearBirthParent'])."' WHERE ID = '".$_COOKIE['memberID']."'";
            mysql_query($sqlQuery);

    }
    else{   $msdata.= "\n".  $sqlQuery ="UPDATE `Profiles_draft` SET `DearBirthParent` = '".process_db_input($_POST['PFblockstd_DearBirthParent'])."' WHERE ID = '".$_COOKIE['memberID']."'";
             mysql_query($sqlQuery);

    }
   
}
if($_POST['title'] == 'Parent1_Interests'){
  if($_POST['Submit'] == 'Save'){
            $msdata.= "\n".  $sqlQuery ="UPDATE `Profiles` SET `Interests` = '".process_db_input($_POST['PFblockstd_Interests1'])."' WHERE ID = '".$_COOKIE['memberID']."'";
             mysql_query($sqlQuery);
            $msdata.= "\n".  $sqlQuery ="UPDATE `Profiles_draft` SET `Interests` = '".process_db_input($_POST['PFblockstd_Interests1'])."' WHERE ID = '".$_COOKIE['memberID']."'";
             mysql_query($sqlQuery);

    }
    else{
            $msdata.= $sqlQuery ="UPDATE `Profiles_draft` SET `Interests` = '".process_db_input($_POST['PFblockstd_Interests1'])."' WHERE ID = '".$_COOKIE['memberID']."'";
            mysql_query($sqlQuery);

    }
}
if($_POST['title'] == 'Parent2_Interests'){
  if($_POST['Submit'] == 'Save'){
            $msdata.=  "\n". $sqlQuery ="UPDATE `Profiles` SET `Interests` = '".process_db_input($_POST['PFblockstd_Interests2'])."' WHERE ID = '".$couple['Couple']."'";
          mysql_query($sqlQuery);
            $msdata.=  "\n". $sqlQuery ="UPDATE `Profiles_draft` SET `Interests` = '".process_db_input($_POST['PFblockstd_Interests2'])."' WHERE ID = '".$couple['Couple']."'";
            mysql_query($sqlQuery);

    }
    else{
        $msdata.= "\n".  $sqlQuery =    "UPDATE `Profiles_draft` SET `Interests` = '".process_db_input($_POST['PFblockstd_Interests2'])."' WHERE ID = '".$couple['Couple']."'";
        mysql_query($sqlQuery);

    }
}
if($_POST['title'] == 'Parent1_Hobbies'){
  if($_POST['Submit'] == 'Save'){
            $msdata.=  "\n". $sqlQuery ="UPDATE `Profiles` SET `Hobbies` = '".process_db_input($_POST['PFblockstd_Hobbies1'])."' WHERE ID = '".$_COOKIE['memberID']."'";
             mysql_query($sqlQuery);
            $msdata.=  "\n". $sqlQuery ="UPDATE `Profiles_draft` SET `Hobbies` = '".process_db_input($_POST['PFblockstd_Hobbies1'])."' WHERE ID = '".$_COOKIE['memberID']."'";
             mysql_query($sqlQuery);
             
    }
    else{
            $msdata.= "\n".  $sqlQuery ="UPDATE `Profiles_draft` SET `Hobbies` = '".process_db_input($_POST['PFblockstd_Hobbies1'])."' WHERE ID = '".$_COOKIE['memberID']."'";
            mysql_query($sqlQuery);

    }
}
if($_POST['title'] == 'Parent2_Hobbies'){
  if($_POST['Submit'] === 'Save'){
            $msdata.= "\n".  $sqlQuery ="UPDATE `Profiles` SET `Hobbies` = '".process_db_input($_POST['PFblockstd_Hobbies2'])."' WHERE ID = '".$couple['Couple']."'";
           mysql_query($sqlQuery);
            $msdata.=  "\n". $sqlQuery ="UPDATE `Profiles_draft` SET `Hobbies` = '".process_db_input($_POST['PFblockstd_Hobbies2'])."' WHERE ID = '".$couple['Couple']."'";
          mysql_query($sqlQuery);

    }
    else{
            $msdata.=  "\n". $sqlQuery ="UPDATE `Profiles_draft` SET `Hobbies` = '".process_db_input($_POST['PFblockstd_Hobbies2'])."' WHERE ID = '".$couple['Couple']."'";
            mysql_query($sqlQuery);

    }
}
if($_POST['title'] == 'About_our_home'){
  if($_POST['Submit'] == 'Save'){
            $msdata.=  "\n". $sqlQuery = "UPDATE `Profiles` SET `About_our_home` = '".process_db_input($_POST['PFblockstd_About_our_home'])."' WHERE ID = '".$_COOKIE['memberID']."'";
            mysql_query($sqlQuery);
            $msdata.= "\n".  $sqlQuery ="UPDATE `Profiles_draft` SET `About_our_home` = '".process_db_input($_POST['PFblockstd_About_our_home'])."' WHERE ID = '".$_COOKIE['memberID']."'";
            mysql_query($sqlQuery);

    }
    else{
            $msdata.= "\n". $sqlQuery ="UPDATE `Profiles_draft` SET `About_our_home` = '".process_db_input($_POST['PFblockstd_About_our_home'])."' WHERE ID = '".$_COOKIE['memberID']."'";
            mysql_query($sqlQuery);

    }
}

createProfileCache($pid);
createProfileCache($pid+1);

$log = fopen($dir['root']."log/blockupdatelog".date('Y-m-d').".txt", 'a+');
fwrite($log, "\n /* =============================".'memberID : '.$_COOKIE['memberID'] .   ' Couple: ' .$couple['Couple']." */\n".$msdata."\n /* ========================== */ \n");
fclose($log);

function createProfileCache( $iMemID ) {
		createUserDataFile( $iMemID );
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
//cleanALL();
//send_headers_page_changed();
$referer = $_SERVER['HTTP_REFERER'];
header("Location: ".$_SERVER['HTTP_REFERER']);
?>