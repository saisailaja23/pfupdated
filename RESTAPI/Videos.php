<?php
require_once '../inc/header.inc.php';
require_once '../inc/profiles.inc.php';
require_once '../inc/utils.inc.php';
require_once '../inc/db.inc.php';

require_once 'API.php';

class Videos extends API {
	public function __construct($request) {
		parent::__construct($request);
	}

	function get_video_album_list() {

		$data = array();
		$id = $this->ID;

		$videoalbumsql = "SELECT album.ID,album.Caption, ph.ID as Hash, 'jpg' as Ext,  ph.YoutubeLink, ph.Uri
			FROM sys_albums album
			LEFT JOIN (SELECT * FROM sys_albums_objects order by obj_order) sab
                                     ON album.ID=sab.id_album
			LEFT JOIN RayVideoFiles ph ON ph.ID=sab.id_object
			WHERE album.Type='bx_videos' AND
			album.Owner = " . $id . " AND
			ph.Status ='approved' AND
			album.AllowAlbumView=3
			group by album.ID,album.Caption order by album.id";

		$result = mysql_query($videoalbumsql);
		$nos = mysql_num_rows($result);

		if ($nos > 0) {
			while ($row = mysql_fetch_array($result, MYSQLI_ASSOC)) {
				$first_video_album = $this->get_first_video($row['ID']);
				$videoalbumlist[] = array(
					// if($row['YoutubeLink'] == 1)
					// 	'videoURL' => 'https://www.youtube.com/watch?v='. $row['Uri'];
					// else
					// 	'videoURL' => 'http://devlocal.parentfinder.com/flash/modules/video/files/_' . $first_video_album . '_small.jpg';
					'albumId' => $row['ID'],
					'firstVideo' => $first_video_album,
					'albumName' => $row['Caption'],
					'ext' => $row['Ext'],
					'youtubeLink' => $row['YoutubeLink'],
					'youtubeUri' => $row['Uri'],
				);
				$data['status'] = 'success';
				$data['msg'] = 'albums fetched successfully';
				$data['data'] = $videoalbumlist;
			}
		} else {
			$data['status'] = 'failure';
			$data['msg'] = 'invalid request';
		}
		echo json_encode($data);
	}
	function get_first_video($albumid) {
		$sql = "SELECT ph.ID as Hash FROM
			RayVideoFiles as ph,sys_albums_objects sab
		WHERE ph.ID=sab.id_object
		AND sab.id_album=$albumid
        ORDER BY sab.obj_order ASC
        LIMIT 1";

		$result = mysql_fetch_array(mysql_query($sql));
		$hash = $result[Hash];
		//+ $result[Ext];
		return $hash;
	}
	function get_all_videos($albumid) {

		$data = array();
		$albumName = db_arr("SELECT Caption FROM sys_albums WHERE ID = " . $albumid);
		$data['albumName'] = $albumName['Caption'];

		$sql = "SELECT ph.ID,ph.Title,ph.Views,ph.Source, ph.YoutubeLink, ph.Uri 
				FROM RayVideoFiles as ph,sys_albums_objects sab
				WHERE ph.ID=sab.id_object
                                AND ph.ytStatusCheck='processed'
				AND sab.id_album=$albumid
                ORDER BY sab.obj_order";
		$result = mysql_query($sql);
		$nos = mysql_num_rows($result);
		if ($nos > 0) {
			while ($row = mysql_fetch_array($result, MYSQLI_ASSOC)) {
				$comments = array();
				/*
				$sql2 = "SELECT p.NickName,p.Avatar,cmt.cmt_text,cmt.cmt_time
					FROM bx_videos_cmts as cmt,Profiles as p
					WHERE
						cmt.cmt_author_id=p.ID
					AND
						cmt.cmt_object_id = ".$row['ID'];
				$result2 = mysql_query($sql2);
				$nos2 = mysql_num_rows($result2);
				if($nos2 > 0){
					while($row2 = mysql_fetch_array($result2, MYSQLI_ASSOC)) {
						$comments[] =  $row2;
					}
				}else{
					$comments[] = '';
				}
				/**/
			
				$videolist[] = array(
					'albumId' => $row['ID'],
					'video' => $row['Hash'],
					'videoName' => $row['Title'],
					'ext' => $row['Ext'],
					'youtubeLink' => $row['YoutubeLink'],
					'youtubeUri' => $row['Uri'],
					//'comments' => $comments
				);
				$data['status'] = 'success';
				$data['msg'] = 'albums fetched successfully';
				$data['data'] = $videolist;
			}
		} else {
			$data['status'] = 'failure';
			$data['msg'] = 'invalid request';
		}
		echo json_encode($data);
	}
	function getVideos($albumid) {
			$memberID = $id = $this->ID;
			$ftr = db_arr("SELECT tlm.IDLevel from `Profiles` p LEFT JOIN `sys_acl_levels_members` AS `tlm` ON `p`.`ID`=`tlm`.`IDMember` AND `tlm`.`DateStarts` < NOW() AND (`tlm`.`DateExpires`>NOW() || ISNULL(`tlm`.`DateExpires`)) WHERE p.ID = $memberID  GROUP BY p.ID");

			$sql = "SELECT ph.ID,ph.Title,ph.Views,ph.Source,ph.Status,ph.CommentsCount,ph.YoutubeLink,ph.ytStatusCheck,ph.Uri,ph.home,(SELECT `Caption` FROM `sys_albums` WHERE `ID` = $albumid) AS caption
			FROM RayVideoFiles as ph,sys_albums_objects sab
				WHERE ph.ID=sab.id_object
                                AND ph.ytStatusCheck='processed'
				AND sab.id_album=$albumid
				ORDER BY sab.obj_order";
			$result = mysql_query($sql);
			$nos = mysql_num_rows($result);
			if ($nos > 0) {
				$i = 0;
				while ($row = mysql_fetch_assoc($result)) {
					$out[] = $row;
					$out[$i]['videoURL'] = '';
			//if(@$_COOKIE['memberID']){
				$out[$i]['featured'] = $ftr['IDLevel'];
			//}
					$out[$i]['YoutubeLink'] = $row['YoutubeLink'];
					if ($row['YoutubeLink'] == '1') {
						$out[$i]['videoURL'] = 'https://www.youtube.com/watch?v=' . $row['Uri'];
						$out[$i]['youtubeCode'] = $row['Uri'];
					} else {
						if (file_exists('../../../../flash/modules/video/files/_' . $row[ID] . '.flv'))
							$out[$i]['videoURL'] = 'http://' . $_SERVER[HTTP_HOST] . '/flash/modules/video/files/_' . $row[ID] . '.flv';
						else if (file_exists('../../../../flash/modules/video/files/_' . $row[ID] . '.mp4'))
							$out[$i]['videoURL'] = 'http://' . $_SERVER[HTTP_HOST] . '/flash/modules/video/files/_' . $row[ID] . '.mp4';
					}
					$out[$i]['caption'] = $row['caption'];
					$ytStatus = $row['ytStatusCheck'];
					$out[$i]['ytStatusFlag'] = 1;
					/*
					if ($row['ytStatusCheck'] == 'uploaded') {
						$ytStatus = $this->ytUploadStatusCheck($row['Uri']);
						$updateStatusSQL = 'UPDATE RayVideoFiles SET  ytStatusCheck =  "'. $ytStatus .'" WHERE  ID = ' . $row[ID];
						mysql_query($updateStatusSQL);
						$out[$i]['ytStatusFlag'] = 0;
					}
					*/
					$out[$i]['ytStatus'] = $ytStatus;
					$i++;
				}
				$data = array(
					"status" => "success",
					"data" => $out,
				);
			}else {
				$data = array(
					"status" => "warning",
					"msg" => "NO Videos exits in this album",
				);
			}
		echo json_encode($data);
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

$obj = new Videos('http://www.parentfinder.com/');
// $obj->get_video_album_list();
$method = $_GET['method'];
$id = $_GET['id'];
if ($method == 'get_video_album_list') {
	$obj->$method();
}elseif($method == 'getVideos'){ 
	$obj->$method($id);
}elseif($method == 'getComments'){
	$obj->getComments($id);
	
}else {
	$obj->get_all_videos($method);
}