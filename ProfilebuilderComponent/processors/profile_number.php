<?php

require_once '../../inc/header.inc.php';
require_once '../../inc/profiles.inc.php';
header("Content-type:application/json");

$profile_number = $_GET['profile_number'];
$profilenumber_array = explode('_', $profile_number);
$logid =  getLoggedId();
 $status = 'success';
 	$result_profilenumber = mysql_query("select ID from Profiles where Profile_no = ".$profilenumber_array[1]." and ID != $logid LIMIT 0,1");
	while ($row_profilenumber = mysql_fetch_array($result_profilenumber)) {
                $profilenumber_id = $row_profilenumber[0];
				          $count = mysql_num_rows(mysql_query("SELECT * FROM Profiles where ID=$profilenumber_id"));
							if( $count != 0){
							 $status = "Profile Number Allready Exits";
							}
            }
			
    echo json_encode(array(
            'status' => $status
    ));

