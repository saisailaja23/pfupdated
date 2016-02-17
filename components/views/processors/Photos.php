<?php
require_once( '../../../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );

	

// echo getLoggedId();


header('Content-Type: application/json');
if($_REQUEST["act"] == "get_albums"){
	$owner_id =$_REQUEST['owner_id'];
	get_album_list($owner_id);
}


if($_REQUEST["act"] == "get_photos"){
	$album_id =$_REQUEST['album_id'];
	get_all_photos($album_id);
}


if($_REQUEST["act"] == "change_view_count"){
	$photo_id =$_REQUEST['photo_id'];
	change_view_count($photo_id);
}

if($_REQUEST["act"] == "get_logged_id"){
	$user_id=$_REQUEST['user_id'];
	echo json_encode(array(
		"logged_id" => getLoggedId(),
		"user_name" => getUsername($user_id)
	));
}


function get_album_list($owner_id){
	$sql = "SELECT album.ID,album.Caption,album.AllowAlbumView,ph.Hash,ph.Ext,max(ph.Views) as Views
			FROM
					(SELECT ID,Caption,AllowAlbumView,Type,Owner  FROM sys_albums) album
					LEFT JOIN (SELECT id_album,id_object FROM sys_albums_objects) sab
								 ON album.ID=sab.id_album
					LEFT JOIN (SELECT ID,Hash,Ext,Status,Views FROM bx_photos_main) ph
								 ON ph.ID=sab.id_object
				WHERE
					album.Type='bx_photos' AND
					album.Owner = $owner_id AND
					ph.Status ='approved'
					group by album.ID,album.Caption
					order by album.id";
	$result = mysql_query($sql);
	$nos = mysql_num_rows($result);

	if ($nos>0) {
		while ($row = mysql_fetch_assoc($result)) {
			$first_album=get_first_photo($row['ID']);
			$row["Hash"]=$first_album["Hash"];
			$out[]=$row;
		}
	echo	json_encode(array(
			"status" => "success",
			"data"  => $out,
			"dev_sql" => $sql,
		
		));
	}else {
	echo	json_encode(array(
			"status"=>"error",
			"msg"=>"No records in the database",
			"dev_sql" => $sql
		));
	}
}
function get_first_photo($albumid){
    $sql="SELECT ph.Hash,ph.Ext FROM 
			bx_photos_main as ph,sys_albums_objects sab
		WHERE ph.ID=sab.id_object
		AND sab.id_album=$albumid
                    ORDER BY sab.obj_order ASC
                    LIMIT 1";
    $result = mysql_query($sql);
    $row = mysql_fetch_assoc($result);
	return $row;
}
function get_all_photos($albumid){
	$sql="SELECT ph.ID,ph.Title,ph.Hash,ph.Ext,ph.Views,ph.Status,ph.CommentsCount FROM 
			bx_photos_main as ph,sys_albums_objects sab
		WHERE ph.ID=sab.id_object
		AND sab.id_album=$albumid
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
				"sql" => $sql
 			));
		}else{
			echo json_encode(array(
				"status" => "warning",
				"msg"  => "NO photos exits in this album",
 			));
		}
}
function change_view_count($photo_id){
	$sql="UPDATE bx_photos_main SET Views = Views+1 WHERE `ID` = $photo_id;";
	$result = mysql_query($sql);
	if($result){
		json_encode(array(
			"status" => "success",
			"data"  => $out,
		));
	} else {
		json_encode(array(
			"status" => "error",
			"msg"  => "Internal server error"
		));
	}
}


?>