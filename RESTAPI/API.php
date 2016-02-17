<?php
/***********************************************************************
 * Name:    Sailaja S
 * Date:    2015/03/19
 * Purpose: RestAPI to maintain routing
 ***********************************************************************/
require_once '../inc/header.inc.php';
require_once '../inc/profiles.inc.php';
require_once '../inc/utils.inc.php';
require_once '../inc/db.inc.php';

header("Access-Control-Allow-Orgin: *");
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json");
abstract class API {

	protected $method = '';

	protected $endpoint = '';

	protected $verb = '';

	protected $args = Array();

	protected $file = Null;

	protected $valid;

	protected $ID = 0;

	protected $couple = 0;

	public function __construct($request) {
		if ($request == 'https://www.parentfinder.com/') {
			$this->valid = TRUE;
		} else {
			$this->valid = FALSE;
		}

		$sql = "SELECT ID FROM Profiles_draft WHERE NickName = '" . $_GET['nickname'] . "'";
		$profileId = db_arr($sql);
		$this->ID = $profileId[ID];

		$sql2 = "SELECT couple FROM Profiles_draft WHERE id=" . $profileId[ID];
		$cpl = db_arr($sql2);
		$this->couple = $cpl[couple];
	}
	public function processAPI() {
		if ((int) method_exists($this, $this->endpoint) > 0) {
			return $this->_response($this->{$this->endpoint}($this->args));
		}
		return $this->_response("No Endpoint: $this->endpoint", 404);
	}

	private function _response($data, $status = 200) {
		header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
		echo json_encode($data);
	}

	private function _cleanInputs($data) {
		$clean_input = Array();
		if (is_array($data)) {
			foreach ($data as $k => $v) {
				$clean_input[$k] = $this->_cleanInputs($v);
			}
		} else {
			$clean_input = trim(strip_tags($data));
		}
		return $clean_input;
	}

	private function _requestStatus($code) {
		$status = array(
			200 => 'OK',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			500 => 'Internal Server Error',
		);
		return ($status[$code]) ? $status[$code] : $status[500];
	}
}

?>