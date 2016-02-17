<?php
/***********************************************************************
 * Name:    Sailaja S
 * Date:    2015/03/19
 * Purpose: RestAPI to get Agency the common details using
 *               in all pages of parentportal sites
 ***********************************************************************/
require_once '../inc/header.inc.php';
require_once '../inc/profiles.inc.php';
require_once '../inc/utils.inc.php';
require_once '../inc/db.inc.php';

require_once 'API.php';

class Photos extends API {
	public function __construct($request) {
		parent::__construct($request);
		// if ($this->valid) {
		// 	echo 'success';
		// } else {
		// 	echo 'failure';
		// }
	}

	function get_data() {

		$id = $this->ID;
		$sql = db_arr("SELECT FirstName,Avatar,couple
					FROM Profiles
					WHERE id=" . $id);
		$couple = $sql['couple'];
		$sql1 = db_arr("SELECT FirstName
					FROM Profiles
					WHERE id=" . $couple);

		$avatar = "http://www.parentfinder.com/modules/boonex/avatar/data/favourite/" . $sql['Avatar'] . ".jpg";
		$p1_name = $sql['FirstName'];
		$p2_name = $sql1['FirstName'];

		$E_book = db_arr("SELECT content FROM `aqb_pc_members_blocks` WHERE `owner_id` = " . $id . " and title = 'E-book Profile'");
		$E_book_link = $E_book[content];
		$start = strpos($E_book_link, ".com/") + 5;
		$end = strpos($E_book_link, ".html") - $start + 5;
		$flipbook = substr($E_book_link, $start, $end);

		$E_pup = db_arr("SELECT content FROM `aqb_pc_members_blocks` WHERE `owner_id` = " . $id . " and title = 'E-PUB Profile'");
		$E_pup_link = $E_pup[content];
		$pos = strpos($E_pup_link, "Please");
		if ($E_pup_link != false) {
			if ($pos === false) {
				$epup = $E_pup_link;
			} else {
				$start = strpos($E_pup_link, "http");
				$end = strpos($E_pup_link, ".html") - $start + 5;
				$epup = substr($E_pup_link, $start, $end);
			}
		} else {
			$epup = false;
		}

		$ProfileQ = db_arr("SELECT template_file_path  FROM pdf_template_user WHERE user_id =" . $id . " AND isDeleted = 'N' AND isDefault ='Y' LIMIT 1");
		$Pdf = (trim(str_replace('/var/www/html/pf/', '/', $ProfileQ[template_file_path])) != '') ? trim(str_replace('/var/www/html/pf/', '/', $ProfileQ[template_file_path])) : 'javascript:void(0)';

		$data['link'] = array(
			'status' => 'success',
			'msg' => 'sueccess',
			'data' => array(
				'p1_name' => $p1_name,
				'p2_name' => $p2_name,
				'profilePicture' => $avatar,
				'ebook_link' => $flipbook,
				'epup_link' => $epup,
				'pdf_link' => $Pdf,
			),
		);

		echo json_encode($data);
	}
}
$obj = new Photos('http://www.parentfinder.com/');
$obj->get_data();