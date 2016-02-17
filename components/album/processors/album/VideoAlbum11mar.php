<?php

include 'Album.php';

class VideoAlbum extends Album {

    function __construct() {
        parent::__construct("bx_videos");
    }

    function showList() {
        $sql = "SELECT album.ID,album.Caption,album.AllowAlbumView,max(ph.Views) as Views, ph.ID as Hash, 'jpg' as Ext
					FROM
						(SELECT * FROM sys_albums) album
						LEFT JOIN (SELECT * FROM sys_albums_objects) sab
									 ON album.ID=sab.id_album
						LEFT JOIN (SELECT * FROM RayVideoFiles) ph
									 ON ph.ID=sab.id_object
					WHERE
						album.Type='bx_videos' AND
						album.Owner = $this->loggedId
						group by album.ID,album.Caption
						order by album.id";
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

        $sql = "SELECT ph.ID,ph.Title,ph.Views,ph.Source,ph.Status,ph.CommentsCount,ph.YoutubeLink,ph.Uri FROM RayVideoFiles as ph,sys_albums_objects sab
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
                $out[$i]['YoutubeLink'] = $row['YoutubeLink'];
                if ($row['YoutubeLink'] == '1') {
                    $out[$i]['videoURL'] = 'https://www.youtube.com/watch?v='.$row['Uri'];
                    $out[$i]['youtubeCode'] = $row['Uri'];
                } else {
                    if (file_exists('../../../../flash/modules/video/files/_' . $row[ID] . '.flv'))
                        $out[$i]['videoURL'] = 'http://' . $_SERVER[HTTP_HOST] . '/flash/modules/video/files/_' . $row[ID] . '.flv';
                    else if (file_exists('../../../../flash/modules/video/files/_' . $row[ID] . '.mp4'))
                        $out[$i]['videoURL'] = 'http://' . $_SERVER[HTTP_HOST] . '/flash/modules/video/files/_' . $row[ID] . '.mp4';
                }
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
        $sql = "UPDATE RayVideoFiles SET `Title` = '$value',`Uri`='$uri' WHERE `ID` = $id;";
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

    //START - Checking membership action - Added by prashanth      
    function isAllowedAdd($isPerformAction = false, $memactions, $memname) {
        //if (isAdmin()) return true;
        //if (isMember() == false) return false;
        // echo $memactions;
        $this->defineActions($memname);
        $user_pid = getLoggedId();
        $aCheck = checkAction($user_pid, $memactions, $isPerformAction);
        $memaction[] = $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;

        $this->result(array(
            "status" => "success",
            "memaction" => $memaction
        ));
    }

    function defineActions($memname) {
        defineMembershipActions(array('$memname'));
    }

    //END - Checking membership action - Added by prashanth          
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
//START - Checking membership action - Added by prashanth  
if ($_GET['action'] === "membership") {
    $memactions = $_GET['memact'];
    $memname = $_GET['memname'];

    $album->isAllowedAdd($isPerformAction = false, $memactions, $memname);
}
//END - Checking membership action - Added by prashanth  
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

