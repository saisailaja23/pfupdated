<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'On');

include 'Album.php';
include '../../../../YoutubeLoginComponent/processors/AccessToken.php';

class VideoAlbum extends Album{
	function __construct(){ 
	 	parent::__construct("bx_videos");
	}
	function showList(){ 
		$sql ="SELECT album.ID,album.Caption,album.AllowAlbumView,max(ph.Views) as Views, ph.ID as Hash, 'jpg' as Ext, ph.YoutubeLink, ph.Uri
                    FROM
                        (SELECT * FROM sys_albums) album
                        LEFT JOIN (SELECT * FROM sys_albums_objects order by obj_order) sab
                                     ON album.ID=sab.id_album                                                                         
                        LEFT JOIN (SELECT * FROM RayVideoFiles) ph
                                     ON ph.ID=sab.id_object
                    WHERE
                        album.Type='bx_videos' AND
                        album.Owner =$this->loggedId
                        group by album.ID,album.Caption
                        order by album.ID";
        $result = mysql_query($sql);
        $nos = mysql_num_rows($result);

        if ($nos > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                $out[] = $row;
            }
            $this->result(array(
                "status" => "success",
                "data" => $out
            ));
        } else {
            $this->result(array(
                "status" => "error",
                "msg" => "No records in the database",
                "sql" => $sql
            ));
        }
    }

    function getVideos($albumid) {
	if(@$_COOKIE['memberID']){
	$memberID = $_COOKIE['memberID'];
	$ftr = db_arr("SELECT tlm.IDLevel from `Profiles` p LEFT JOIN `sys_acl_levels_members` AS `tlm` ON `p`.`ID`=`tlm`.`IDMember` AND `tlm`.`DateStarts` < NOW() AND (`tlm`.`DateExpires`>NOW() || ISNULL(`tlm`.`DateExpires`)) WHERE p.ID = $memberID  GROUP BY p.ID");
	}
        $sql = "SELECT ph.ID,ph.Title,ph.Views,ph.Source,ph.Status,ph.CommentsCount,ph.YoutubeLink,ph.ytStatusCheck,ph.Uri,ph.home,(SELECT `Caption` FROM `sys_albums` WHERE `ID` = $albumid) AS caption
		FROM RayVideoFiles as ph,sys_albums_objects sab
			WHERE ph.ID=sab.id_object
			AND sab.id_album=$albumid
                        ORDER BY sab.obj_order";
        $result = mysql_query($sql);
        $nos = mysql_num_rows($result);
        if ($nos > 0) {
            $i = 0;
            while ($row = mysql_fetch_assoc($result)) {
                $out[] = $row;
                $out[$i]['videoURL'] = '';
		if(@$_COOKIE['memberID']){
		$out[$i]['featured'] = $ftr['IDLevel'];
		}
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
                if ($row['ytStatusCheck'] == 'uploaded') {
                    $ytStatus = $this->ytUploadStatusCheck($row['Uri']);
                    $updateStatusSQL = 'UPDATE RayVideoFiles SET  ytStatusCheck =  "'. $ytStatus .'" WHERE  ID = ' . $row[ID];
                    mysql_query($updateStatusSQL);
                    $out[$i]['ytStatusFlag'] = 0;
                }
                $out[$i]['ytStatus'] = $ytStatus;
                $i++;
            }
            $this->result(array(
                "status" => "success",
                "data" => $out,
                "sql" => $sql
            ));
        }else {
            $this->result(array(
                "status" => "warning",
                "msg" => "NO Videos exits in this album",
            ));
        }
    }

    function editVideo($data) {
        extract($data);
        $uri = $value;
        str_replace('   ', ' ', $uri);
        str_replace('  ', ' ', $uri);
        str_replace(' ', '-', $uri);
        $sql = "UPDATE RayVideoFiles SET `Title` = '" . addslashes($value) . "' WHERE `ID` = $id;";
        $result = mysql_query($sql);
        // echo mysql_insert_id();
        echo $value;
    }

    function changeViewCount($id) {
        $sql = "UPDATE RayVideoFiles SET Views = Views+1 WHERE `ID` = $id;";
        $result = mysql_query($sql);
        if ($result) {
            $this->result(array(
                "status" => "success",
                "data" => $out
            ));
        } else {
            $this->result(array(
                "status" => "error",
                "msg" => "Internal error."
            ));
        }
    }
    function ytUploadStatusCheck($videoId) {

        $obj = new AccessToken();
        $access_token = $obj->getAcessToken();
//        echo 'https://www.googleapis.com/youtube/v3/videos?part=status&access_token=' . $access_token . '&id=' . $videoId;
//        exit;
        $ch1 = curl_init('https://www.googleapis.com/youtube/v3/videos?part=status&access_token=' . $access_token . '&id=' . $videoId);
        curl_setopt($ch1, CURLOPT_HTTPGET, 1);
        curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch1, CURLOPT_HEADER, 0);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch1);
        $statusDetails = json_decode($response);
        if($statusDetails->items != ''){
            foreach ($statusDetails->items as $key => $value) {
                $uploadStatus = $value->status->uploadStatus;
                if($uploadStatus == 'rejected'){
                    $rejectionReason = $value->status->rejectionReason;
                    $uploadStatus = $uploadStatus . '/' . $rejectionReason;
}
            }            
        }
        else{
            $uploadStatus = 'removed';
        }
        return $uploadStatus;
    }

}

function filter($input) {
    return htmlspecialchars(mysql_real_escape_string($input));
}

$album = new VideoAlbum;
if ($_GET['action'] === "add") {
    $arr = array(
        "Caption" => filter($_GET['name']),
        "Description" => filter($_GET['desc']),
        "type" => "bx_videos",
        "AllowAlbumView" => $_GET['privacy']
    );
    $album->create($arr);
}


if ($_GET['action'] === "editVideo") {
    $album->editVideo($_POST);
}
if ($_GET['action'] === "list") {
    $album->showList();
}

if ($_GET['action'] === "edit") {
    $album->update($_POST);
}
if ($_GET['action'] === "changeViewCount") {
    $album->changeViewCount($_POST['videoid']);
}
if ($_GET['action'] === "editAlbum") {
    if ($_POST['privacy']) {
        $arr = array("AllowAlbumView" => $_POST['privacy']);
        $album->updateAlbumById($_POST['id'], $arr);
    }

    // if($_POST['action'] ==="unPublish"){
    // 	$arr=array(	"AllowAlbumView" => 2);
    // 	echo $album->updateAlbumById($_POST['id'],$arr);
    // }else{
    // 	$arr=array(	"AllowAlbumView" => 3);
    // 	echo $album->updateAlbumById($_POST['id'],$arr);
    // }
}

if ($_GET['action'] === "listvideos") {
    $album->getVideos($_GET['albumid']);
}

if ($_GET['action'] === "removeAlbum") {
    $album->removeAlbum($_GET['albumid']);
    $album->result(array(
        "status" => "success"
    ));
}

if ($_GET['action'] === "removeVideo") {
    $vdoid = $_GET['videoid'];
    $mediaPath = $dir['root'] . "/flash/modules/video/files/";
    @chdir($mediaPath);
    @array_map('unlink', glob("*$vdoid*"));
    $album->removeObject($_GET['albumid'], $_GET['videoid']);
    $album->result(array(
        "status" => "success"
    ));
//    echo "DELETE FROM RayVideoFiles WHERE ID = $vdoid"; 
    $uriSQL = "SELECT Uri,YoutubeLink FROM RayVideoFiles WHERE ID = $vdoid";
    $uri = db_arr($uriSQL);
    $ytId = $uri['Uri'];
    $flag = $uri['YoutubeLink'];
    mysql_query("DELETE FROM RayVideoFiles WHERE ID= $vdoid");
    if($flag == 1){
        
        $obj = new AccessToken();
        $access_token = $obj->getAcessToken();        
        $ch1 = curl_init('https://www.googleapis.com/youtube/v3/videos?part=snippet&access_token=' . $access_token . '&id=' . $ytId);
        curl_setopt($ch1, CURLOPT_HTTPGET, 1);        
        curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch1, CURLOPT_HEADER, 0);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);        
        $response = curl_exec($ch1);
        $statusDetails = json_decode($response);
        $accessToken = '';
        if($statusDetails->items != ''){
            foreach ($statusDetails->items as $key => $value) {
                $ytChannel = $value->snippet->channelId;                
            }
            $tokenSQL = "SELECT access_token FROM `YoutubeToken` WHERE channelId = '" . $ytChannel . "'";
            $token = db_arr($tokenSQL);
            $accessToken = $token['access_token'];
            if($accessToken != ''){                
                $url = "https://www.googleapis.com/youtube/v3/videos?id=".$ytId;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-type: application/json','Authorization : Bearer '.$accessToken));

                curl_exec ($ch);

                curl_close ($ch);
                
//                
//                $ch1 = curl_init('https://www.googleapis.com/youtube/v3/videos&access_token=' . $accessToken . '&id=' . $videoId);
//                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
//                curl_setopt($curl, CURLOPT_HEADER, false);
//                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//                $response = curl_exec($ch1);
            }
        }        
        
    }
}

if ($_GET['action'] === "sortAlbum") {
    $sort_array = $_POST['order'];
    //print_r($sort_array);echo "******";
    foreach ($sort_array as $ind_phot) {
        $id_object = $ind_phot['idFileObject'];
        $obj_order = $ind_phot['seqNumber'];
        $sql_updateOrder = "UPDATE sys_albums_objects SET obj_order = $obj_order WHERE id_object = $id_object";
        $result = mysql_query($sql_updateOrder);
    }
}
if($_GET['action']==='homeVideo'){
	$owner = $_COOKIE['memberID'];
	$videoId = $_GET['videoid'];
	mysql_query("UPDATE RayVideoFiles SET `home` = 0 WHERE `Owner` = $owner;");
	$sql = "UPDATE RayVideoFiles SET `home` = 1 WHERE `ID` = $videoId;";
	$result = mysql_query($sql);
	echo $aftd_rws = mysql_affected_rows();
}
