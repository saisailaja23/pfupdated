<?php
require_once '../inc/header.inc.php';
require_once '../inc/profiles.inc.php';
require_once '../inc/utils.inc.php';
require_once '../inc/db.inc.php';

require_once 'API.php';

class PhotoAlbums extends API {
	public function __construct($request) {
		parent::__construct($request);
	}

	function get_album_list() {
		$data = array();
		$id = $this->ID;
		$albumsql = "SELECT album.ID,album.Caption,ph.Hash,ph.Ext
			FROM
					(SELECT ID,Caption,Type,Owner  FROM sys_albums WHERE AllowAlbumView=3) album
					LEFT JOIN (SELECT id_album,id_object FROM sys_albums_objects) sab
								 ON album.ID=sab.id_album
					LEFT JOIN (SELECT ID,Hash,Ext,Status,Views FROM bx_photos_main) ph
								 ON ph.ID=sab.id_object
				WHERE
					album.Type='bx_photos' AND
					album.Owner = " . $id . " AND
					ph.Status ='approved'
					group by album.ID,album.Caption
					order by album.id";
		$result = mysql_query($albumsql);
		$nos = mysql_num_rows($result);

		if ($nos > 0) {
			while ($row = mysql_fetch_array($result, MYSQLI_ASSOC)) {
				$first_album = $this->get_first_photo($row['ID']);
				$albumlist[] = array(
					'albumId' => $row['ID'],
					'firstPhoto' => "http://www.parentfinder.com/m/photos/get_image/file/" . $first_album . "." . $row['Ext'],
					'albumName' => $row['Caption'],
				);
				$data['status'] = 'success';
				$data['msg'] = 'albums fetched successfully';
				$data['data'] = $albumlist;
			}
		} else {
			$data['status'] = 'failure';
			$data['msg'] = 'invalid request';
		}
		echo json_encode($data);
	}
	function get_first_photo($albumid) {
		$sql = "SELECT ph.Hash,ph.Ext FROM
			bx_photos_main as ph,sys_albums_objects sab
			WHERE ph.ID=sab.id_object
			AND sab.id_album=$albumid
		          ORDER BY sab.obj_order ASC
		          LIMIT 1";
		$result = mysql_fetch_array(mysql_query($sql));
		$hash = $result[Hash];
		//+ $result[Ext];
		return $hash;
	}
	function get_all_photos($albumid) {
		$sql="SELECT ph.ID,ph.Hash,ph.Ext FROM 
				bx_photos_main as ph,sys_albums_objects sab
			WHERE ph.ID=sab.id_object
			AND sab.id_album=$albumid
                        ORDER BY sab.obj_order";
			$result = mysql_query($sql);
			$nos = mysql_num_rows($result);
                        $i = 0;
			if($nos>0){
				while ($row = mysql_fetch_assoc($result)) {
					$out[$i]=$row;
                                        $out[$i]['titleEnc'] = htmlspecialchars($row['Title']);
                                        $i++;
				}
				$data = array(
					"status" => "success",
					"data"  => $out,
	 			);
			}else{
				$data = array(
					"status" => "warning",
					"msg"  => "NO photos exits in this album",
	 			);
			}
		echo json_encode($data);
	}
	function getPhotos($albumid){
		$sql="SELECT ph.ID,ph.Title,ph.Hash,ph.Ext,ph.Views,ph.Status,ph.CommentsCount FROM 
				bx_photos_main as ph,sys_albums_objects sab
			WHERE ph.ID=sab.id_object
			AND sab.id_album=$albumid
                        ORDER BY sab.obj_order";
			$result = mysql_query($sql);
			$nos = mysql_num_rows($result);
                        $i = 0;
			if($nos>0){
				while ($row = mysql_fetch_assoc($result)) {
					$out[$i]=$row;
                                        $out[$i]['titleEnc'] = htmlspecialchars($row['Title']);
                                        $i++;
				}
				$data = array(
					"status" => "success",
					"data"  => $out,
					"sql" => $sql
	 			);
			}else{
				$data = array(
					"status" => "warning",
					"msg"  => "NO photos exits in this album",
	 			);
			}
		echo json_encode($data);
	}

	function getUserData(){
		$id = $this->ID;
		$logged = getLoggedId();
		$profileSql = "select NickName,Avatar from Profiles where id = $id";
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

	function getComments($id){

		$sql="SELECT p.NickName,p.Avatar,cmt.cmt_text,cmt.cmt_time
					FROM bx_photos_cmts as cmt,Profiles as p
					WHERE
						cmt.cmt_author_id=p.ID
					AND
						cmt.cmt_object_id = $id";
		$result=mysql_query($sql);
		$nos=mysql_num_rows($result);
		if ($nos>0) {
			while ($row = mysql_fetch_assoc($result)) {
				foreach ($row as $key => $value) {
					if($key==="cmt_time"){
						$row['cmt_ago'] = $this->timeAgo($row['cmt_time']);
					}
				}
				$out[]=$row;
			}
			$data = array(
					"status" => "success",
					"data" => $out

				);
		}else {
			$data = array(
					"status" => "warning",
					"data" => "null",
					"msg" => "No Comment, Be the first commenter!"
				);
		}

		echo "jsonCallbackComment(".json_encode($data).")"; 
	}

	

}
$obj = new PhotoAlbums('http://www.parentfinder.com/');
$method = $_GET['method'];
$id = $_GET['id'];

if ($method == 'get_album_list' || $method == 'getUserData') {
	$obj->$method();
} elseif($method == 'getComments'){
	$obj->getComments($id);
	
}else{
	$obj->get_all_photos($method);
}

?>