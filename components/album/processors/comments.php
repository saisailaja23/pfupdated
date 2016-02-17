<?php
/**
 * C:\Users\msi1308\AppData\Roaming\Sublime Text 2\Packages/PhpTidy/phptidy-sublime-buffer.php
 *
 * @author Aravind Buddha <aravind.buddha@mediaus.com>
 * @package default
 */


require_once( '../../../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

class Comment{
	var $type;
	function __construct($type){ 
	 	$this->type=$type;
	}
	function result($arr){
		echo json_encode($arr);
	}
	function get($id){
		$sql="SELECT
						p.NickName,p.Avatar,cmt.cmt_text,cmt.cmt_time
					FROM bx_".$this->type."_cmts as cmt,Profiles as p
					WHERE
						cmt.cmt_author_id=p.ID
					AND
						cmt.cmt_object_id = $id";
		$result=mysql_query($sql);
		$nos=mysql_num_rows($result);
		if ($nos>0) {
			while ($row = mysql_fetch_assoc($result)) {
				foreach ($row as $key => $value) {
					//echo $row[$key];
					//echo time("2014-06-02 16:42:47");
					if($key==="cmt_time"){
						// $row[$key]=date($row[$key]);
						$row['cmt_ago'] = timeAgo($row['cmt_time']);
					}
				}
				$out[]=$row;
			}
			$this->result(array(
					"status" => "success",
					"data" => $out

				));
		}else {
			$this->result(array(
					"status" => "warning",
					"data" => $out,
					"msg" => "No Comment, Be the first commenter!"
				));
		}
	}

	function insert($file, $msg, $author,$time){
		$sql="";
		if($this->type ==="photos"){
				$sql="UPDATE bx_photos_main 
			SET CommentsCount = CommentsCount+1 
			WHERE ID = $file";
		}else{
				$sql="UPDATE RayVideoFiles 
			SET CommentsCount = CommentsCount+1 
			WHERE ID = $file";
		}
		//echo $sql;
	  $result=mysql_query($sql);

    //date_default_timezone_set('UTC');

		

		$sql="INSERT INTO bx_".$this->type."_cmts
		(cmt_object_id,cmt_author_id, cmt_text,cmt_time)
		VALUES
		($file, $author, '$msg','".date("Y-m-d H:i:s",time())."')";
		$result=mysql_query($sql);
		if ($result) {
			$this->result(array(
					"status"=>"success"
				));
		}else{
			$this->result(array(
					"status"=>"error",
					"msg" =>"Internal error"
			));
		}
	}


	/**
	 *
	 */
	
}

//To get profile data
function getUserData(){
		$logged = getLoggedId();
		$profileSql = "select NickName,Avatar from Profiles where id = $logged";
		$result = mysql_query($profileSql);
		$row = mysql_fetch_assoc($result);
		$name=$row['NickName'];
		$avatar=$row['Avatar'];
		$fileID = $_POST['fileID'];
		echo json_encode(array(
				"id"=>$logged,
				"name"=>$name,
				"avatar"=>$avatar,
				"file"=>$fileID
			));
	}

function timeAgo($comment_time) {
	$current_dt = date('m/d/Y h:i A');
	$cmtTime_array = split(" ", $comment_time);
    $comment_time = $cmtTime_array[0] . " " . $cmtTime_array[1] . " " . $cmtTime_array[2];

    $diff = abs(strtotime($comment_time) - strtotime($current_dt));
    if ($diff > 31536000)
        $show_time = round($diff / 31536000, 0) . ' year ago';
    else if ($diff > 2419200)
        $show_time = round($diff / 2419200, 0) . ' month ago';
    else if ($diff > 604800)
        $show_time = round($diff / 604800, 0) . ' week ago';
    else if ($diff > 86400)
        $show_time = round($diff / 86400, 0) . ' day ago';
    else if ($diff > 3600)
        $show_time = round($diff / 3600, 0) . ' hour ago';
    else if ($diff > 60)
        $show_time = round($diff / 60, 0) . ' minute ago';
    else
        $show_time = 'less than a minute ago';
    return $show_time;
}


if($_GET['type']){
	$cmt=new Comment($_GET['type']);
}
if ($_GET['user']) {
	getUserData();
}

if($_GET['action']==="insert"){
	$cmt->insert($_REQUEST['fileID'], $_REQUEST['msg'], $_REQUEST['author'],$_REQUEST['time']);
}

if ($_GET['action']==="get") {
	$cmt->get($_GET['fileID']);
}
	
// 	
// if ($_GET['user']) {
// 	$cmt->getUserData();
// }
// //for type photo
// if ($_GET['type']=="photo") {
// 	if ($_GET['fileID']) {
// 		$cmt->get($_GET['fileID']);
// 	}
// 	if ($_GET['post']) {
// 		$cmt->insert($_POST['fileID'], $_POST['msg'], $_POST['author']);
// 	}
// }

// //for type video
// if ($_GET['type']==="video") {

// }
