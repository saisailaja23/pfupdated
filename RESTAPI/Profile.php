<?php
/***********************************************************************
 * Name:    Sailaja S
 * Date:    2015/03/19
 * Purpose: RestAPI to get profile details
 ***********************************************************************/
require_once '../inc/header.inc.php';
require_once '../inc/profiles.inc.php';
require_once '../inc/utils.inc.php';
require_once '../inc/db.inc.php';

require_once 'API.php';

class Profile extends API {
	public function __construct($request) {
		parent::__construct($request);
	}

	public $data = array();
	function getProfileInfo() {

		$data = array();
		$id = $this->ID;
		$sql1 = "SELECT FirstName,Religion,Ethnicity,Education,FLOOR( DATEDIFF( CURDATE( ) ,  `DateOfBirth` ) /365 ) as DateOfBirth,Sex,waiting,RelationshipStatus,State
					FROM Profiles_draft
					WHERE id=" . $id;
		$profileInfo = mysql_query($sql1);

		if (mysql_num_rows($profileInfo) > 0) {
			while ($row = mysql_fetch_array($profileInfo, MYSQLI_ASSOC)) {
				$data['personDetails'] = array(
					'status' => 'success',
					'msg' => 'sueccess',
					'data' => array(
						'fname' => $row['FirstName'],
						'faith' => $row['Religion'],
						'Ethnicity' => $row['Ethnicity'],
						'education' => $row['Education'],
						'dateOfBirth' => $row['DateOfBirth'],
						'sex' => $row['Sex'],
						'waiting' => $row['waiting'],
						'relationshipStatus' => $row['RelationshipStatus'],
						'state' => $row['State'],
					),
				);
			}
		} else {
			$data['personDetails'] = array(
				'status' => 'failure',
				'msg' => 'Cant found person details',
			);
		}

		//echo json_encode($personDetails);

		$sql2 = "SELECT couple FROM Profiles_draft WHERE id=" . $id;
		$coupl = db_arr($sql2);
		$couple = $coupl[couple];

		if ($couple != 0) {
			$sql3 = "SELECT FirstName,Religion,Ethnicity,Education,FLOOR( DATEDIFF( CURDATE( ) ,  `DateOfBirth` ) /365 ) as DateOfBirth,Sex,waiting,RelationshipStatus,State FROM Profiles_draft WHERE id=" . $couple;
			$coupleInfo = mysql_query($sql3);
		} else {
			$coupleInfo = '';
		}

		if (mysql_num_rows($coupleInfo) > 0) {
			while ($row = mysql_fetch_array($coupleInfo, MYSQLI_ASSOC)) {
				$data['coupleDetails'] = array(
					'status' => 'success',
					'msg' => 'success',
					'data' => array(
						'fname' => $row['FirstName'],
						'faith' => $row['Religion'],
						'Ethnicity' => $row['Ethnicity'],
						'education' => $row['Education'],
						'dateOfBirth' => $row['DateOfBirth'],
						'sex' => $row['Sex'],
						'waiting' => $row['waiting'],
						'relationshipStatus' => $row['RelationshipStatus'],
						'state' => $row['State'],
					),
				);
			}
		} else {
			$data['coupleDetails'] = array(
				'status' => 'failure',
				'msg' => 'Cant found couple details',
			);
		}

		//echo json_encode($coupleDetails);

		$sql4 = "SELECT ChildAge,ChildGender,ChildEthnicity,AdoptionType FROM Profiles_draft WHERE id=" . $id;
		$child = mysql_query($sql4);

		if (mysql_num_rows($child) > 0) {
			while ($row = mysql_fetch_array($child, MYSQLI_ASSOC)) {
				$data['childDetails'] = array(
					'status' => 'success',
					'msg' => 'sueccess',
					'data' => array(
						'childAge' => $row['ChildAge'],
						'childGender' => $row['ChildGender'],
						'childEthnicity' => $row['ChildEthnicity'],
						'adoptionType' => $row['AdoptionType'],
					),
				);
			}
		} else {
			$data['childDetails'] = array(
				'status' => 'failure',
				'msg' => 'Cant found child details',
			);
		}

		//echo json_encode($childDetails);
		echo json_encode($data);
	}
}

$obj = new Profile('http://www.parentfinder.com/');
$obj->getProfileInfo();
