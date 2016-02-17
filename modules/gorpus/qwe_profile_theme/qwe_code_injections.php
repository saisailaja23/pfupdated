<?

function qwe_profile_theme_change_template()
{
	#__qwe_profile_theme_change_template();
	eval("__qwe_profile_theme_change_template();");
}

function __qwe_profile_theme_change_template()
{
	$GLOBALS['QWE_VARS']['PROFILE_THEME']['IS_CUSTOM_CSS'] = 1;

	$iProfileId = 0;

	if(preg_match("/^qwe_profile_theme\/?$/", @$_REQUEST['r'])) {
		$iProfileId = (int)qwe_profile_theme_get_logged_id();
	}
	else {
		$iProfileID = 0;
		$selfFile = basename( $_SERVER['PHP_SELF'] );
		if ($selfFile == 'index.php' && preg_match("/^files\//", $_REQUEST['r']))
			$iProfileID = qwe_get_profile_id_from_module_path();
		elseif ($selfFile == 'index.php' && preg_match("/^photos\//", $_REQUEST['r']))
			$iProfileID = qwe_get_profile_id_from_module_path();
		elseif ($selfFile == 'index.php' && preg_match("/^sounds\//", $_REQUEST['r']))
			$iProfileID = qwe_get_profile_id_from_module_path();
		elseif ($selfFile == 'index.php' && preg_match("/^videos\//", $_REQUEST['r']))
			$iProfileID = qwe_get_profile_id_from_module_path();
		elseif ($selfFile == 'index.php' && preg_match("/^store\//", $_REQUEST['r']))
			$iProfileID = qwe_get_profile_id_from_module_path('user');
		elseif ($selfFile == 'profile_info.php') {
			$iProfileID = $_REQUEST['ID'] ? (int)getID($_REQUEST['ID']) : (int)qwe_profile_theme_get_logged_id();
		}
		elseif ($selfFile == 'profile.php')
			$iProfileID = (int)getID($_REQUEST['ID']);
		elseif ($selfFile == 'preview_profile.php')
			$iProfileID = (int)qwe_profile_theme_get_logged_id();
		elseif ($selfFile == 'browseMedia.php')
			$iProfileID = (int)$_GET['userID'];
		elseif ($selfFile == 'viewFriends.php')
			$iProfileID = (int)$_GET['iUser'];
		elseif (isset($_REQUEST['iUser']))
			$iProfileID = (int)$_REQUEST['iUser'];
		elseif (isset($_REQUEST['userID']))
			$iProfileID = (int)$_REQUEST['userID'];
		elseif (isset($_REQUEST['profileID']))
			$iProfileID = (int)$_REQUEST['profileID'];
		elseif (isset($_REQUEST['ownerID']))
			$iProfileID = (int)$_REQUEST['ownerID'];
		else
			$iProfileID = 0;

		$iProfileId = $iProfileID;
	}
	
	if(!$iProfileId) {
		$sSql = "SELECT * FROM `qwe_profile_theme_pages` ";
		$pages = db_res_assoc_arr($sSql);
		foreach($pages as $k => $page) {
			if(strpos($page['uri'], '{PROFILE_NAME}') !== FALSE) {
				
				$parts = preg_split('/\{PROFILE_NAME\}/', $page['uri']);
				$uri = $_SERVER['REQUEST_URI'];
				$is_part_not_found = FALSE;
				
				foreach($parts as $part) {
					if(!$part) continue;
					
					if(strpos($uri, $part) === FALSE) {
						$is_part_not_found = TRUE;
						break;
					}
					$uri = str_replace($part, '', $uri);
				}
				
				if($is_part_not_found) {
					continue;
				}
				
				if(preg_match_all('/(\w+)/', $uri, $matches)) {
					$pname = $matches[1][0];					
					$sNickname = process_db_input($pname, BX_TAGS_STRIP);
					$iProfileId = (int) db_value("SELECT `ID` FROM " . BX_DOL_TABLE_PROFILES . " WHERE `NickName` = '$sNickname'");
					break;
				}
			}
			elseif(strpos($page['uri'], '{PROFILE_ID}') !== FALSE) {
				
				$parts = preg_split('/\{PROFILE_ID\}/', $page['uri']);
				$uri = $_SERVER['REQUEST_URI'];
				$is_part_not_found = FALSE;
				
				foreach($parts as $part) {
					if(!$part) continue;
					
					if(strpos($uri, $part) === FALSE) {
						$is_part_not_found = TRUE;
						break;
					}
					$uri = str_replace($part, '', $uri);
				}
				
				if($is_part_not_found) {
					continue;
				}				
				
				if(preg_match_all('/(\d+)/', $uri, $matches)) {
					$iProfileId = (int)$matches[1][0];					
					break;
				}
			}			
			elseif(strpos($page['uri'], '{REGEXP_PROFILE_ID}') !== FALSE) {
				$regexp = trim(str_replace('{REGEXP_PROFILE_ID}', '(\d+)', $page['uri']));
				$uri = $_SERVER['REQUEST_URI'];				
				if(preg_match_all('/'.$regexp.'/', $uri, $matches)) {
					$iProfileId = (int)$matches[1][0];					
					break;
				}
			}
			elseif(strpos($page['uri'], '{REGEXP_PROFILE_NAME}') !== FALSE) {
				$regexp = trim(str_replace('{REGEXP_PROFILE_NAME}', '(\w+)', $page['uri']));
				$uri = $_SERVER['REQUEST_URI'];
				if(preg_match_all('/'.$regexp.'/', $uri, $matches)) {
					$pname = $matches[1][0];					
					$sNickname = process_db_input($pname, BX_TAGS_STRIP);
					$iProfileId = (int) db_value("SELECT `ID` FROM " . BX_DOL_TABLE_PROFILES . " WHERE `NickName` = '$sNickname'");
					break;
				}
			}			
			elseif(strpos($page['uri'], '{MY_PROFILE}') !== FALSE) {
				$part = trim(str_replace('{MY_PROFILE}', '', $page['uri']));
				$uri = $_SERVER['REQUEST_URI'];
				if(strpos($uri, $part) !== FALSE) {
					$iProfileId = (int)qwe_profile_theme_get_logged_id();
					break;
				}
			}
			elseif(strpos($page['uri'], '{REGEXP_MY_PROFILE}') !== FALSE) {
				$regexp = trim(str_replace('{REGEXP_MY_PROFILE}', '', $page['uri']));
				$uri = $_SERVER['REQUEST_URI'];
				if(preg_match('/'.$regexp.'/', $uri)) {
					$iProfileId = (int)qwe_profile_theme_get_logged_id();
					break;
				}
			}
		}

	}

	$iProfileId = (int)$iProfileId;

	if(!$iProfileId) {
		$GLOBALS['QWE_VARS']['PROFILE_THEME']['IS_CUSTOM_CSS'] = 0;
	}

	$sThemeType = '';
	$sCurrentCss = '';

	if(@$_POST['profile_theme_css']) {
		$sCurrentCss = $_POST['profile_theme_css'];
		$sThemeType = @$_POST['profile_theme_type'];
	}
	else {
		$sSql = "SELECT * FROM `qwe_profile_theme_themes` WHERE `profile_id` = '".$iProfileId."'";
		$aThemeRecord = db_arr($sSql);
		if($aThemeRecord) {
			$sCurrentCss = $aThemeRecord['css'];
		}

		$sThemeType = @$aThemeRecord['type'];
	}

	$GLOBALS['QWE_VARS']['PROFILE_THEME']['THEME_TYPE'] = $sThemeType;
	$GLOBALS['QWE_VARS']['PROFILE_THEME']['CURRENT_CSS'] = $sCurrentCss;

	if(!trim($sCurrentCss)) {
		$GLOBALS['QWE_VARS']['PROFILE_THEME']['IS_CUSTOM_CSS'] = 0;
	}

	if($GLOBALS['QWE_VARS']['PROFILE_THEME']['IS_CUSTOM_CSS']) {
		$GLOBALS['QWE_VARS']['PROFILE_THEME']['DEFAULT_SKIN'] = 'unibaseqwe';		
	}

}


function qwe_profile_theme_get_logged_id()
{
	return isset($_COOKIE['memberID']) ? (int)$_COOKIE['memberID'] : 0;
}

function qwe_get_profile_id_from_module_path($identifier = 'owner')
{
	$path = $_REQUEST['r'];
	$iProfileID = 0;

	if(preg_match("/\/".$identifier."\/([^\/]+)/", $path, $result)) {
		$sNickname = @$result[1];
		$sNickname = process_db_input($sNickname, BX_TAGS_STRIP);
		$iProfileID = (int) db_value("SELECT `ID` FROM " . BX_DOL_TABLE_PROFILES . " WHERE `NickName` = '$sNickname'");
	}

	if(!$iProfileID && preg_match("/\/my\/main/", $path)) {
		$iProfileID = (int)getID($_REQUEST['memberID']);
	}

	return $iProfileID;
}

?>