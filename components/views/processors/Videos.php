<?php
require_once( '../../../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );

	

// echo getLoggedId();


header('Content-Type: application/json');
if($_REQUEST["act"] == "get_albums"){
	$owner_id =$_REQUEST['owner_id'];
	get_album_list($owner_id);
}


if($_REQUEST["act"] == "get_videos"){
	$album_id =$_REQUEST['album_id'];
	get_all_videos($album_id);
}


if($_REQUEST["act"] == "change_view_count"){
	$video_id =$_REQUEST['video_id'];
	change_view_count($video_id);
}

if($_REQUEST["act"] == "get_logged_id"){
	$user_id=$_REQUEST['user_id'];
	echo json_encode(array(
		"logged_id" => getLoggedId(),
		"user_name" => getUsername($user_id)
	));
}


function get_album_list($owner_id){
	// $sql = "SELECT album.ID,album.Caption,album.AllowAlbumView,ph.Hash,ph.Ext,max(ph.Views) as Views
	// 		FROM
	// 				(SELECT ID,Caption,AllowAlbumView,Type,Owner  FROM sys_albums) album
	// 				LEFT JOIN (SELECT id_album,id_object FROM sys_albums_objects) sab
	// 							 ON album.ID=sab.id_album
	// 				LEFT JOIN (SELECT ID,Hash,Ext,Status,Views FROM RayVideoFiles) ph
	// 							 ON ph.ID=sab.id_object
	// 			WHERE
	// 				album.Type='bx_videos' AND
	// 				album.Owner = $owner_id AND
	// 				ph.Status ='approved'
	// 				group by album.ID,album.Caption
	// 				order by album.id";

	// $sql ="SELECT album.ID,album.Caption,album.AllowAlbumView,max(ph.Views) as Views, ph.ID as Hash, 'jpg' as Ext
	// 		FROM
	// 			(SELECT * FROM sys_albums) album
	// 			LEFT JOIN (SELECT * FROM sys_albums_objects) sab
	// 						 ON album.ID=sab.id_album
	// 			LEFT JOIN (SELECT * FROM RayVideoFiles) ph
	// 						 ON ph.ID=sab.id_object
	// 		WHERE
	// 			album.Type='bx_videos' AND
	// 			album.Owner = $owner_id AND
	// 			ph.Status ='approved'
	// 			group by album.ID,album.Caption
	// 			order by album.id";

	$sql="SELECT album.ID,album.Caption,album.AllowAlbumView,max(ph.Views) as Views, ph.ID as Hash, 'jpg' as Ext,  ph.YoutubeLink, ph.Uri
			FROM sys_albums album 
			LEFT JOIN (SELECT * FROM sys_albums_objects order by obj_order) sab
                                     ON album.ID=sab.id_album 
			LEFT JOIN RayVideoFiles ph ON ph.ID=sab.id_object 
			WHERE album.Type='bx_videos' AND 
			album.Owner = $owner_id AND 
			ph.Status ='approved' 
			group by album.ID,album.Caption order by album.id";
	
	$result = mysql_query($sql);
	$nos = mysql_num_rows($result);

	if ($nos>0) {
		while ($row = mysql_fetch_assoc($result)) {
			$first_album=get_first_video($row['ID']);
			$row["Hash"]=$first_album["Hash"];
			$out[]=$row;
		}
	echo	json_encode(array(
			"status" => "success",
			"data"  => $out,
			"dev_sql" => preg_replace('/(\v|\s)+/', ' ', $sql)
		
		));
	}else {
	echo	json_encode(array(
			"status"=>"error",
			"msg"=>"No records in the database",
			"dev_sql" => preg_replace('/(\v|\s)+/', ' ', $sql)
		));
	}
}
function get_first_video($albumid){
    $sql="SELECT ph.ID as Hash FROM 
			RayVideoFiles as ph,sys_albums_objects sab
		WHERE ph.ID=sab.id_object
                AND ph.ytStatusCheck='processed'
		AND sab.id_album=$albumid
        ORDER BY sab.obj_order ASC
        LIMIT 1";

    $result = mysql_query($sql);
    $row = mysql_fetch_assoc($result);
	return $row;
}
function get_all_videos($albumid){
		$sql="SELECT ph.ID,ph.Title,ph.Views,ph.Source,ph.Status,ph.CommentsCount, ph.YoutubeLink, ph.Uri FROM RayVideoFiles as ph,sys_albums_objects sab
			WHERE ph.ID=sab.id_object
			AND sab.id_album=$albumid
                        AND ph.ytStatusCheck='processed'
                        ORDER BY sab.obj_order";
		$result = mysql_query($sql);
		$nos = mysql_num_rows($result);
		if($nos>0){
			while ($row = mysql_fetch_assoc($result)) {
				$out[]=$row;
			}
			echo json_encode(array(
				"status" => "success",
				"data"  => $out,
				"dev_sql" => preg_replace('/(\v|\s)+/', ' ', $sql)
 			));
		}else{
			echo json_encode(array(
				"status" => "warning",
				"msg"  => "NO videos exits in this album",
 			));
		}
}
function change_view_count($video_id){
	$sql="UPDATE RayVideoFiles SET Views = Views+1 WHERE `ID` = $video_id;";
	$result = mysql_query($sql);
	if($result){
		json_encode(array(
			"status" => "success",
			"data"  => $out,
			"dev_sql" => preg_replace('/(\v|\s)+/', ' ', $sql)
		));
	} else {
		json_encode(array(
			"status" => "error",
			"msg"  => "Internal server error",
			"dev_sql" => preg_replace('/(\v|\s)+/', ' ', $sql)
		));
	}
}


?>