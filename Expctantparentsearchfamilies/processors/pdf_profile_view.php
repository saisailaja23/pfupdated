<?php

require_once('../../inc/header.inc.php');
require_once('../../inc/profiles.inc.php');
require_once('../../inc/design.inc.php');
require_once('../../templates/base/scripts/BxBaseMenu.php');
$logid = $_POST['Profileid'];

$test = generateprofilePDF($logid);


$getDefaultDetails = getuserDefaultPDF($logid);

$getDefaulTempID = $getDefaultDetails['template_user_id'];
$getorgTempID = $getDefaultDetails['template_id'];

if (trim($test) != '') {
    echo json_encode(array(
        'status' => 'success',
        'printprofile' => array(
            'rows' => $test
        ),
        'deafulttempid' => array(
            'rows' => $getorgTempID
        )
    ));
} else {
    echo json_encode(array(
        'status' => 'err',
        'response' => 'Could not read the data:'
    ));
}

function generateprofilePDF($logged) {

    $getDefaultDetails = getuserDefaultPDF($logged);


    $getDefaulTempID = $getDefaultDetails['template_user_id'];
    $getorgTempID = $getDefaultDetails['template_id'];

    if ($getorgTempID == 0) {


        if ($getDefaulTempID)
            $view_url = $GLOBALS['site']['url'] . "PDFTemplates/user/" . $logged . "_" . $getDefaulTempID . "_" . $getDefaultDetails['pdfdate'] . ".pdf";

        else
            $view_url = "";
    }
    else {
        //  $view_url  = $getDefaulTempID;

        $view_url = $getDefaulTempID;

    }
    return $view_url;
}

function getuserDefaultPDF($userID) {
    $pdfUserDefault = "";
    $sql_pdfuserDet = "SELECT
								ptu.template_user_id,
								ptu.user_id,
								ptu.template_id,
                                DATE_FORMAT(ptu.lastupdateddate, '%Y-%m-%d') AS pdfdate
								FROM pdf_template_user ptu
								WHERE ptu.user_id = $userID AND ptu.isDeleted = 'N' AND ptu.isDefault ='Y'";
    
    
    $pdfUserDet = mysql_query($sql_pdfuserDet);
    if (mysql_numrows($pdfUserDet) > 0) {
        while ($row_pdfsdet = mysql_fetch_assoc($pdfUserDet)) {
            $pdfUserDefault = $row_pdfsdet;
        }
    }
    return $pdfUserDefault;
}

function getuserProfileType($userID) {
    $userProfileType = "";
    $sql_userProfileType = "SELECT	ProfileType FROM Profiles WHERE ID = $userID";
    // echo $sql_userProfileType;exit();

    $userProfileTypedet = mysql_query($sql_userProfileType);
    if (mysql_numrows($userProfileTypedet) > 0) {
        while ($row_Profiledet = mysql_fetch_assoc($userProfileTypedet)) {
            $userProfileType = $row_Profiledet;
        }
    }
    return $userProfileType;
}

?>
