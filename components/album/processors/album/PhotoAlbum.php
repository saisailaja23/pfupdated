<?php
/**
 * C:\Users\msi1308\AppData\Roaming\Sublime Text 2\Packages/PhpTidy/phptidy-sublime-buffer.php
 *
 * @author Aravind Buddha <aravind.buddha@mediaus.com>
 * @package default
 */


include 'Album.php';
// header('Content-Type: application/json');


class PhotoAlbum extends Album{
	function __construct(){ 
	 	parent::__construct("bx_photos");
	}
	function showList(){
		/* $sql ="SELECT album.ID,album.Caption,album.AllowAlbumView,ph.Hash,ph.Ext,max(ph.Views) as Views
					FROM
						(SELECT * FROM sys_albums) album
						LEFT JOIN (SELECT * FROM sys_albums_objects) sab
									 ON album.ID=sab.id_album
						LEFT JOIN (SELECT * FROM bx_photos_main) ph
									 ON ph.ID=sab.id_object
					WHERE
						album.Type='bx_photos' AND
						album.Owner = $this->loggedId
						group by album.ID,album.Caption
						order by album.id"; */
						
		 $sql ="SELECT album.ID,album.Caption,album.AllowAlbumView,ph.Hash,ph.Ext,max(ph.Views) as Views
					FROM
						(SELECT ID,Caption,AllowAlbumView,Type,Owner  FROM sys_albums) album
						LEFT JOIN (SELECT id_album,id_object FROM sys_albums_objects) sab
									 ON album.ID=sab.id_album
						LEFT JOIN (SELECT ID,Hash,Ext,Views FROM bx_photos_main) ph
									 ON ph.ID=sab.id_object
					WHERE
						album.Type='bx_photos' AND
						album.Owner = $this->loggedId
						group by album.ID,album.Caption
						order by album.id";


		$result = mysql_query($sql);
		$nos = mysql_num_rows($result);

		if ($nos>0) {
			while ($row = mysql_fetch_assoc($result)) {
				$first_album=$this->getFirstPhoto($row['ID']);
				$row["Hash"]=$first_album["Hash"];
				$out[]=$row;
			}
			$this->result(array(
				"status" => "success",
				"data"  => $out,
 			));
		}else {
			$this->result(array(
					"status"=>"error",
					"msg"=>"No records in the database",
				));
		}
	}

	function albumList(){
		 $sql ="SELECT album.ID,album.Caption,album.AllowAlbumView
					FROM sys_albums as album
						LEFT JOIN sys_albums_objects sab ON album.ID = sab.id_album
						LEFT JOIN bx_photos_main ph ON ph.ID = sab.id_object
					WHERE
						album.Type='bx_photos' AND
						album.Owner = $this->loggedId
						group by album.ID,album.Caption
						order by album.id";
		$result = mysql_query($sql);

		if (mysql_num_rows($result) >0) {
			$i=0;
			while ($row = mysql_fetch_assoc($result)) {
				$Title = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $row['Caption']);
				$Title = str_replace("%20", " ", $Title);
				$out[$i]['ID'] = $row['ID'];
				$out[$i]['Title'] = $Title;
				$out[$i]['Photos'] = $this->album_list_photos($row['ID']);
				$i++;
			}
			$this->result(array(
				"status" => "success",
				"data"  => $out,
 			));

		}else {
			$this->result(array(
					"status"=>"error",
					"msg"=>"No records in the database",
				));
		}
		
		//print_r($this->result);	
		
	}

	function albumListLetters(){
		 $sql ="SELECT album.ID,album.Caption,album.AllowAlbumView
					FROM sys_albums as album
						LEFT JOIN sys_albums_objects sab ON album.ID = sab.id_album
						LEFT JOIN bx_photos_main ph ON ph.ID = sab.id_object
					WHERE
						album.Type='bx_photos' AND
						album.Owner = $this->loggedId
						group by album.ID,album.Caption
						order by album.id";
		$result = mysql_query($sql);

		if (mysql_num_rows($result) >0) {
			$i=0;
			while ($row = mysql_fetch_assoc($result)) {
				$Title = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $row['Caption']);
				$Title = str_replace("%20", " ", $Title);
				$out[$i]['ID'] = $row['ID'];
				$out[$i]['Title'] = $Title;
				$out[$i]['Photos'] = $this->album_list_photos_letters($row['ID']);
				$i++;
			}
			$this->result(array(
				"status" => "success",
				"data"  => $out,
 			));

		}else {
			$this->result(array(
					"status"=>"error",
					"msg"=>"No records in the database",
				));
		}
		
		//print_r($this->result);	
		
	}

	function getFirstPhoto($albumid){
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
                                        $url = '../../../../modules/boonex/photos/data/files/'.$row[ID].'.jpg';                                       
                                        $url_s = '../../../../modules/boonex/photos/data/files/'.$row[ID].'_m.jpg';                                       

                                        $data = getimagesize($url);
                                        $width = $data[0];
                                        $height = $data[1];
                                        $broken = 0;
                                        if(!$width || !$height)
                                        {
                                            $broken = 1;
                                            copy($url_s, $url);
                                        }
                                        $out[$i]['broken_img'] = $broken;
                                        $i++;
				}
				$this->result(array(
					"status" => "success",
					"data"  => $out,
					"sql" => $sql
	 			));
			}else{
				$this->result(array(
					"status" => "warning",
					"msg"  => "NO photos exits in this album",
	 			));
			}
	}

	function album_list_photos($albumid){
		$sql =	"SELECT ph.ID, ph.Uri, ph.Title,ph.Hash,ph.Ext FROM 
				bx_photos_main as ph, sys_albums_objects sab
				WHERE ph.ID=sab.id_object
				AND sab.id_album=$albumid
				ORDER BY sab.obj_order";
				
			$result = mysql_query($sql);
			$nos = mysql_num_rows($result);
                        $i = 0;
			if($nos>0){
				while ($row = mysql_fetch_assoc($result)) {
					$out[$i]['phID'] = $row['ID'];
					$out[$i]['phTitle'] = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $row['Title']);
					$out[$i]['phUri'] = $row['Uri'];
					$out[$i]['phHash'] = $row['Hash'];
					$out[$i]['phExt'] = $row['Ext'];

					$phPath2 = '../../../../modules/boonex/photos/data/files/'.$row['ID'].".".$row['Ext'];
					$phPath = '../../../../modules/boonex/photos/data/files/'.$row['Uri'];

					$phSize = filesize($phPath)/1024;
					if($phSize == 0){
						$phSize = filesize($phPath2)/1024;
					}
					
					//$out[$i]['phSize'] = floor($phSize + floor($phSize/35))." kb";
					$out[$i]['phSize'] = round($phSize, 2)." kb";
					/**/					
					$i++;
				}
				$res = array(
					"status" => "success",
					"data"  => $out,
	 			);
			}else{
				$res = array(
					"status" => "warning",
					"msg"  => "NO photos exits in this album",
	 			);
			}
			return $res;
	}

	function album_list_photos_letters($albumid){
		$sql =	"SELECT ph.ID, ph.Uri, ph.Title,ph.Hash,ph.Ext FROM 
				bx_photos_main as ph, sys_albums_objects sab
				WHERE ph.ID=sab.id_object
				AND sab.id_album=$albumid
				ORDER BY sab.obj_order";
				
			$result = mysql_query($sql);
			$nos = mysql_num_rows($result);
                        $i = 0;
			if($nos>0){
				while ($row = mysql_fetch_assoc($result)) {
					$out[$i]['phID'] = $row['ID'];
					$out[$i]['phTitle'] = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $row['Title']);
					$out[$i]['phHash'] = $row['Hash'];
					$out[$i]['phExt'] = $row['Ext'];

					$phPath2 = '../../../../modules/boonex/photos/data/files/'.$row['ID'].".".$row['Ext'];
					$phPath = '../../../../modules/boonex/photos/data/files/'.$row['Uri'];

					$phSize = filesize($phPath)/1024;
					if($phSize == 0){
						$phSize = filesize($phPath2)/1024;
					}
					
					//$out[$i]['phSize'] = floor($phSize + floor($phSize/35))." kb";
					$out[$i]['phSize'] = round($phSize, 2)." kb";
					/**/					
					$i++;
				}
				$res = array(
					"status" => "success",
					"data"  => $out,
	 			);
			}else{
				$res = array(
					"status" => "warning",
					"msg"  => "NO photos exits in this album",
	 			);
			}
			return $res;
	}


	function editPhoto($data){
		extract($data);
		$uri=$value;
		str_replace('   ',' ',$uri);
		str_replace('  ',' ',$uri);
		str_replace(' ','-',$uri);
//		str_replace("'",'\'',$uri);
		$sql='UPDATE bx_photos_main SET `Title` = "'.  addslashes($value).'" WHERE `ID` = "'.$id.'";';
		$result = mysql_query($sql);
		echo ($value);
	}
	function changeViewCount($id){
			$sql="UPDATE bx_photos_main SET Views = Views+1 WHERE `ID` = $id;";
			$result = mysql_query($sql);
			if($result){
				$this->result(array(
					"status" => "success",
					"data"  => $out,
	 			));
			}else{
					$this->result(array(
					"status" => "error",
					"msg"  => "Internal server error"
	 			));
			}
	}
}

function filter($input){
 return htmlspecialchars(mysql_real_escape_string($input));
}
$album= new PhotoAlbum;
if($_GET['action'] === "editPhoto"){
	$album->editPhoto($_POST);	
}
elseif ($_GET['action']==="list") {
	$album->showList();
}
elseif($_GET['action']=='album_list'){
	$album->albumList();
}
elseif($_GET['action']=='album_list_letters'){
	$album->albumListLetters();
}

elseif($_GET['action']==="add"){
	$arr=array(
		"Caption"=>filter($_GET['name']),
		"Description"=>filter($_GET['desc']),
		"type" =>"bx_photos",
		"AllowAlbumView" => $_GET['privacy']
		);
	$album->create($arr);
}
elseif($_GET['action']==="edit"){
	$album->update($_POST);
}
elseif($_GET['action'] ==="changeViewCount"){

	$album->changeViewCount($_POST['photoid']);
}
elseif($_GET['action'] === "editAlbum"){
	if($_POST['privacy']){
		$arr=array(	"AllowAlbumView" => $_POST['privacy']);
	  $album->updateAlbumById($_POST['id'],$arr);
	}
}

elseif($_GET['action']==="listphotos"){
	$album->getPhotos($_GET['albumid']);
}

elseif($_GET['action']==="removeAlbum"){
	$album->removeAlbum($_GET['albumid']);
	$album->result(array(
		"status"=>"success"
	));
}

elseif($_GET['action']==="removePhoto"){
		$phid=$_GET['photoid'];
	$mediaPath=$dir['root']."/modules/boonex/photos/data/files/";
	@chdir($mediaPath);
	@array_map('unlink', glob( "*$phid*"));

	$album->removeObject($_GET['albumid'],$_GET['photoid']);
	$album->result(array(
		"status"=>"success"
	));
}

elseif($_GET['action']==="sortAlbum"){
    $sort_array  = $_POST['order'];
    //print_r($sort_array);echo "******";
    foreach($sort_array as $ind_phot)
    {
        $id_object  = $ind_phot['idFileObject'];
        $obj_order  = $ind_phot['seqNumber'];
        $sql_updateOrder  = "UPDATE sys_albums_objects SET obj_order = $obj_order WHERE id_object = $id_object";
        $result = mysql_query($sql_updateOrder);
        
    }
    
}

