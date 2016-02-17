<?php
/***********************************************************************
 * Name:    Sailaja S
 * Date:    2015/03/19
 * Purpose: RestAPI to list all the letters
 ***********************************************************************/
require_once '../inc/header.inc.php';
require_once '../inc/profiles.inc.php';
require_once '../inc/utils.inc.php';
require_once '../inc/db.inc.php';

require_once 'API.php';

class Letters extends API {
	public $check;
	public $data = array();
	
	public function __construct($request) {
		parent::__construct($request);
		if ($this->valid) {
			$this->check = 'success';
		} else {
			$this->check = 'failure';
		}
	}

	public function getPic($picID){
		$img = json_decode($picID);
		$img = implode(',', $img);
		
		$q2 = "SELECT CONCAT(Hash,'.', Ext) as img FROM `bx_photos_main` WHERE ID IN($img);";
		$r2 = mysql_query($q2);
		
		while($rw = mysql_fetch_array($r2)){
			$img2[] = $rw['img'];
		}
		$ret = json_encode($img2);
		
		return $ret;

	}
	
	function get_letters() {
		if($this->check == 'success'){
			$data['status'] = 'success';
			$data['msg'] = 'letters fetched successfully';	
			
			$id = $this->ID;

			//-------------------Default Letters Block----------------------------

			$couple = $this->couple;
			if ($couple != 0) {
				$columns = 'DescriptionMe,agency_letter,letter_aboutThem,DearBirthParent,aboutp2,img_him,img_her,img_them,img_mother,img_agency';
				$defaultletterSql = "SELECT p1.DescriptionMe, p1.agency_letter, p1.letter_aboutThem, p1.DearBirthParent, p2.DescriptionMe aboutp2,p1.img_him,p2.img_her,p1.img_them,p1.img_mother,p1.img_agency
								FROM Profiles_draft p1, Profiles_draft p2
								WHERE p1.id = " . $id . "
								AND p1.couple = p2.id";
			} else {
				$columns = 'DescriptionMe,agency_letter,letter_aboutThem,DearBirthParent,aboutp2,img_him,img_her,img_them,img_mother,img_agency';
				$defaultletterSql = "SELECT DescriptionMe, agency_letter, letter_aboutThem, DearBirthParent,'No Couple' as aboutp2,img_him,'' as img_her,img_them,img_mother,img_agency
								FROM Profiles_draft
								WHERE id =" . $id;
			}
			
			// echo $defaultletterSql; exit;
			
			$default = db_arr($defaultletterSql);

			$defalutLetters[] = array(
				'EXPECTING MOTHER LETTER ' => $default['DearBirthParent'],
				'p1' => $default['DescriptionMe'],
				'p2' => $default['aboutp2'],
				'LETTER ABOUT AGENCY' => $default['agency_letter'],
				'LETTER ABOUT THEM' => $default['letter_aboutThem'],
			);
			$img = array(
				$this->getPic($default['img_mother']),
				$this->getPic($default['img_him']),
				$this->getPic($default['img_her']),
				$this->getPic($default['img_agency']),
				$this->getPic($default['img_them']),



			);
			foreach ($defalutLetters as $key => $value) {
				$value = str_replace("\n", "<br/>", trim($value));
			}
			//echo json_encode($defalutLetters);

			//------------------End Of Default Letters Block------------------------

			//---------------------Custom Letters Block-----------------------------

			$customletterSql = "SELECT id,label,description, img
							FROM letter
							WHERE profile_id =" . $id;

			$result = mysql_query($customletterSql);
			$nos = mysql_num_rows($result);

			if ($nos > 0) {
				while ($row = mysql_fetch_array($result, MYSQLI_ASSOC)) {
					$customLetters[] = array(
						'letterId' => $row['id'],
						'letterName' => strtoupper($row['label']),
						'letterContent' => str_replace("\n", "<br/>", trim($row['description'])),
						'imgId' => $row['img'],
						'img' => $this->getPic($row['img']),

					);
				}
			} else {
				$customLetters[] = null;
			}
			
			$data['data'] = array('defalutLetters'=>$defalutLetters[0], 'img'=>$img, 'customLetters'=>$customLetters);
			
			
			//------------------End Of Custom Letters Block------------------------
		}else{
			$data['status'] = 'failure';
			$data['msg'] = 'invalid request';
		}
		echo json_encode($data);
		//print_r($data); exit;
		
	}
	
	function getSortLetters(){
		$id = $this->ID;
		$vv['id'] = $id; 
		$ltr_srt = "SELECT REPLACE(label,'letter_','' ) as name FROM `letters_sort` WHERE profile_id=$id ORDER BY order_by";
		$res = mysql_query($ltr_srt);
		
		$nos = mysql_num_rows($res);

		if($nos > 0){
			$vv['sort'] = '1'; 
			$vv['sort_qry'] = $ltr_srt;
			$srtAr = array();
			
			while($row = mysql_fetch_assoc($res)){
				$srtAr[] = $row['name'];
			}
			
			$couple = $this->couple;
			//$couple = 0;
			if ($couple != 0) { 
				$ltrDef = "SELECT p1.DescriptionMe, p2.DescriptionMe aboutp2, p1.letter_aboutThem,  p1.DearBirthParent, p1.agency_letter, p1.img_him,p2.img_her,p1.img_them,p1.img_mother,p1.img_agency
								FROM Profiles_draft p1, Profiles_draft p2
								WHERE p1.id = " . $id . "
								AND p1.couple = p2.id";
			} else {
				$ltrDef = "SELECT DescriptionMe,'No Couple' as aboutp2,letter_aboutThem, DearBirthParent,agency_letter,img_him,'' as img_her,img_them,img_mother,img_agency
								FROM Profiles_draft
								WHERE id =" . $id;
			}
			
			$ltrDef_qry = mysql_query($ltrDef);
			while($row2 = mysql_fetch_assoc($ltrDef_qry)){
				$row2['img_him'] = $this->getPic($row2['img_him']);
				$row2['img_her'] = $this->getPic($row2['img_her']);
				$row2['img_them'] = $this->getPic($row2['img_them']);
				$row2['img_mother'] = $this->getPic($row2['img_mother']);
				$row2['img_agency'] = $this->getPic($row2['img_agency']);
				$default = $row2;
			}
			
			//print_r($default);
			
			/**/

			$customletterSql = "SELECT id,label,description, img
							FROM letter
							WHERE profile_id =" . $id;

			$result = mysql_query($customletterSql);
			$nos = mysql_num_rows($result);

			if ($nos > 0) {
				while ($row = mysql_fetch_array($result, MYSQLI_ASSOC)) {
					$customLetters[$row['id']] = array(
						'letterName' => strtoupper($row['label']),
						'letterContent' => str_replace("\n", "<br/>", trim($row['description'])),
						'img' => $this->getPic($row['img']),
					);
				}
			} else {
				$customLetters[] = null;
			}
			
			//print_r($customLetters);
			
			$res = array();
			foreach($srtAr as $val){
				$ret = '';
				switch($val){
					case 'about_him':
						if($default['DescriptionMe'] != '' || $default['img_him'] != "null"){
							$ret = array(
										'letterName' => 'p1',
										'letterContent' => $default['DescriptionMe'],
										'img' => $default['img_him']
										);
						}
						break;
					case 'about_her':
						if(($default['aboutp2'] != '' || $default['img_her'] != "null")&&$couple!=0){
							$ret = array(
										'letterName' => 'p2',
										'letterContent' => $default['aboutp2'],
										'img' => $default['img_her']
										);
						}
						break;
					case 'about_them':
						if($default['letter_aboutThem'] != '' || $default['img_them'] != "null"){
							$ret = array(
										'letterName' => 'LETTER ABOUT THEM',
										'letterContent' => $default['letter_aboutThem'],
										'img' => $default['img_them']
										);
						}
						break;
					case 'mother':
						$ret = array(
									'letterName' => 'EXPECTING MOTHER LETTER',
									'letterContent' => $default['DearBirthParent'],
									'img' => $default['img_mother']
									);
						break;
					case 'agency':
						if($default['agency_letter'] != '' || $default['img_agency'] != "null"){
							$ret = array(
										'letterName' => 'AGENCY LETTER',
										'letterContent' => $default['agency_letter'],
										'img' => $default['img_agency']
										);
						}
						break;
					default:
						if($customLetters[$val] != ''){
							$tem = $customLetters[$val];
							if($tem['letterContent'] != '' || $tem['img'] != "null"){
								$ret = $tem;
							}
							
						}
						break;
				}
			
				if($ret!=''){ $res[] = $ret; }
				
				
				
			}
			
			//echo '<pre>'; print_r($res); exit;
			$data = array('status'=>'success', 'msg'=> 'letters fectched successfully', 'defaultLetters' => '', 'customLetters' => $res, 'vv'=>$vv);
			
		}else{
			$vv['sort'] = 0;
			if($this->check == 'success'){
				$id = $this->ID;

				$couple = $this->couple;
				$vv['couple'] = $couple;
				if ($couple != 0) {
					$columns = 'DescriptionMe,agency_letter,letter_aboutThem,DearBirthParent,aboutp2,img_him,img_her,img_them,img_mother,img_agency';
					$defaultletterSql = "SELECT p1.DescriptionMe, p1.agency_letter, p1.letter_aboutThem, p1.DearBirthParent, p2.DescriptionMe aboutp2,p1.img_him,p2.img_her,p1.img_them,p1.img_mother,p1.img_agency
									FROM Profiles_draft p1, Profiles_draft p2
									WHERE p1.id = " . $id . "
									AND p1.couple = p2.id";
				} else {
					$columns = 'DescriptionMe,agency_letter,letter_aboutThem,DearBirthParent,aboutp2,img_him,img_her,img_them,img_mother,img_agency';
					$defaultletterSql = "SELECT DescriptionMe, agency_letter, letter_aboutThem, DearBirthParent,'No Couple' as aboutp2,img_him,'' as img_her,img_them,img_mother,img_agency
									FROM Profiles_draft
									WHERE id =" . $id;
				}
				
				//echo $defaultletterSql; exit;
				
				$qry = mysql_query($defaultletterSql);
				$nos = mysql_num_rows($qry);

				if ($nos > 0) {
					$def = array();
					while ($row = mysql_fetch_array($qry, MYSQLI_ASSOC)) {
						$def = $row;
					}
					$def['img_him'] = $this->getPic($def['img_him']);
					$def['img_her'] = $this->getPic($def['img_her']);
					$def['img_them'] = $this->getPic($def['img_them']);
					$def['img_mother'] = $this->getPic($def['img_mother']);
					$def['img_agency'] = $this->getPic($def['img_agency']);	
						
					$ress = array();

					if($def['DearBirthParent']!='' || $def['img_mother']!="null"){
						$ress[] =  array('letterName'=>'EXPECTING MOTHER LETTER',
											'letterContent'=>$def['DearBirthParent'],
											'img'=>$def['img_mother']
									);
					}

					if($def['agency_letter']!='' || $def['img_agency']!="null"){
						$ress[] =  array('letterName'=>'AGENCY LETTER',
											'letterContent'=>$def['agency_letter'],
											'img'=>$def['img_agency']
									);
					}
										
					if($def['DescriptionMe']!='' || $def['img_him']!="null"){
						$ress[] =  array('letterName'=>'p1',
											'letterContent'=>$def['DescriptionMe'],
											'img'=>$def['img_him']
									);
					}
					
					if($couple != 0){
						if($def['aboutp2']!='' || $def['img_her']!="null"){
							$ress[] =  array('letterName'=>'p2',
												'letterContent'=>$def['aboutp2'],
												'img'=>$def['img_her']
										);
						}
					}
					
					if($def['letter_aboutThem']!='' || $def['img_them']!="null"){
						$ress[] =  array('letterName'=>'LETTER ABOUT THEM',
											'letterContent'=>$def['letter_aboutThem'],
											'img'=>$def['img_them']
									);
					}
					

					

					
					$customletterSql = "SELECT id,label,description, img
									FROM letter
									WHERE profile_id = $id";

					$result = mysql_query($customletterSql);
					$nos2 = mysql_num_rows($result);

					if ($nos2 > 0) {
						while ($row = mysql_fetch_array($result, MYSQLI_ASSOC)) {
							if($row['description']!='' || $row['img']!="null"){
								$ress[] = array(
									'letterName' => strtoupper($row['label']),
									'letterContent' => str_replace("\n", "<br/>", trim($row['description'])),
									'img' => $this->getPic($row['img']),

								);
							}
						}
					} 
//$vv['ltrs_noSort'] = $ress;					
					$data = array('status'=>'success', 'msg'=> 'letters fectched successfully', 'defaultLetters' => '', 'customLetters' => $ress, 'vv'=>$vv);
				}else{
					$data = array('status' => 'failure', 'msg'=>'No letters to list', 'vv'=>$vv);
				}
			}else{
					$data = array('status' => 'failure', 'msg'=>'No letters to list', 'vv'=>$vv);
			}
			
		}
		echo json_encode($data);
	}
	
	
}

//$obj = new Letters('http://www.parentfinder.com/');
//$obj->get_letters();

$obj = new Letters('https://www.parentfinder.com/');
$method = $_GET['method'];
// echo $method;

if ($method == 'get_letters') {
	$obj->$method();
} elseif($method == 'getSortLetters') {
	$obj->getSortLetters();
}