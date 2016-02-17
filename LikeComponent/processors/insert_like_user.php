<?php
/*********************************************************************************
* Name:    Prashanth A
* Date:    12/12/2013
* Purpose: Inserting the families liked by birth mother
*********************************************************************************/
require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');
$user_id = $_POST['userid'];
$BM_id = getLoggedId();
$profileDet = getProfileInfo($user_id);
if ($profileDet['ProfileType'] == 8) {
	$stringSQL="select LikedBy from like_list where AgencyLike = $user_id and LikedBy = $BM_id";
	$query = db_res($stringSQL);
	$cmdtuples = mysql_num_rows($query);
	if ($cmdtuples <=0 ) {
		$DeleteLike_List = "INSERT INTO like_list(`LikedBy`, `AgencyLike`) VALUES ('" . $BM_id . "', '" . $user_id . "')";
		db_res($DeleteLike_List);
		if (mysql_affected_rows() > 0) {
			echo json_encode(array(
					'status' => 'success',
					'sql_statement' => $DeleteLike_List
				));
		}
		else {
			echo json_encode(array(
					'status' => 'err',
					'response' => 'Could not read the data: ',
					'sql'=>$stringSQL
				));
		}
	}
	else {
		echo json_encode(array(
				'status' => 'err',
				'response' => 'You had liked this',
				'sql'=>$stringSQL
			));
	}
}
else {
	$stringSQL="select LikedBy from like_list_family where FamilyLiked = $user_id and LikedBy = $BM_id";
	$query = db_res($stringSQL);
	$cmdtuples = mysql_num_rows($query);
	if ($cmdtuples <=0 ) {
		$DeleteLike_List = "INSERT INTO like_list_family(`LikedBy`, `FamilyLiked`) VALUES ('" . $BM_id . "', '" . $user_id . "')";
		db_res($DeleteLike_List);
		if (mysql_affected_rows() > 0) {
			echo json_encode(array(
					'status' => 'success',
					'sql_statement' => $DeleteLike_List
				));
		}
		else {
			echo json_encode(array(
					'status' => 'err',
					'response' => 'Could not read the data: ',
					'sql'=>$stringSQL
				));
		}
	}
	else {
		echo json_encode(array(
				'status' => 'err',
				'response' => 'You had liked this',
				'sql'=>$stringSQL
			));
	}
}
?>
