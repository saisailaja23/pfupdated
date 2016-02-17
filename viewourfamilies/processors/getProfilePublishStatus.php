<?php

require_once '../../inc/header.inc.php';
require_once '../../inc/profiles.inc.php';

$logid = $_GET['id'];
$loggedid = getLoggedId();
$columns = 'publishStatus,publishPwd';
if($logid){
    $stringSQL = "SELECT nickname FROM Profiles where ID = " . $logid;
    $query = db_arr($stringSQL);
    $nickname = $query['nickname'];
}
$nickname = strtoupper($nickname);

//echo $_SESSION[$nickname];

//$cookie_name = 'PUBSTATUS'.$logid;
//echo $_COOKIE[$nickname];

//print_r($_COOKIE);exit;
//echo !isset($_COOKIE[$nickname]).'!!!!!';
//echo ($_COOKIE[$nickname] == $logid || $logid == $loggedid).'@@@@';

if(!isset($_SESSION[$nickname])) {
//    echo 'in';
    $_SESSION[$nickname] = -1;
} 
//echo $_COOKIE[$nickname];
//echo $_COOKIE[MARKLETTY];
//echo $_COOKIE['MARKLETTY'];
//echo !isset($_COOKIE[$nickname]).'###';
//echo ($_COOKIE[$nickname] == $logid || $logid == $loggedid).'^^^^^';exit;
$arrRows = array();
//echo $nickname;
//echo $_COOKIE[$nickname] . "\n";
//echo $logid . "\n";
//echo $loggedid . "\n";
if($_SESSION[$nickname] == $logid || $logid == $loggedid){
    $arrValues = array("1","");
    array_push($arrRows, array(
                    'id' => 0,
                    'data' => $arrValues,
            ));
    echo json_encode(array(
                'status' => 'success',
                'PublishStatus' => array(
                        'rows' => $arrRows,
                ),
        ));
}
else {
//    echo 'else';
    //$stringSQL = "SELECT  " . $columns . " FROM " . $tablename . " where ID = " . $logid . "";
    $stringSQL = "SELECT publishStatus,(SELECT Unpublish_Password FROM bx_groups_main WHERE id = AdoptionAgency) AS publishPwd  FROM Profiles where ID = " . $logid;
    $query = db_res($stringSQL);
    $cmdtuples = mysql_num_rows($query);
    $arrColumns = explode(",", $columns);
    

    while (($row = mysql_fetch_array($query, MYSQL_BOTH))) {
            $arrValues = array();
            foreach ($arrColumns as $column_name) {
                    array_push($arrValues, str_replace("\n", "<br/>", trim($row[$column_name])));
            }

            array_push($arrRows, array(
                    'id' => $row[0],
                    'data' => $arrValues,
            ));
    }   
    if ($cmdtuples > 0) {
//        $_SESSION['PUBSTATUS'.$logid] = $logid;
//        setcookie('PUBSTATUS'.$logid, $logid, time() + (3600), "/");
        echo json_encode(array(
                'status' => 'success',
                'PublishStatus' => array(
                        'rows' => $arrRows,
                ),
        ));
    } else {
        echo json_encode(array(
                'status' => 'err',
                'response' => 'Could not read the data',
        ));
    }
}