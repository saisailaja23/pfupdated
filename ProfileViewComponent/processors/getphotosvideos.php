<?php

define('BX_PROFILE_PAGE', 1);
require_once( '../../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

$aData = '';
$id = ($_GET['id'] != 'undefined') ? $_GET['id'] : getLoggedId();
if (getLoggedId() == 0) {
    $sql_condition_home = " and AllowAlbumView  = 3 ";
} else {
    $sql_condition_home = " and AllowAlbumView  != 2 ";
}
if ($_GET['type'] == 'user') {
    $sql_condition_statue = " and bx_photos_main.Status = 'approved'";
} else {
    $sql_condition_statue = "";
    $sql_condition_home = "";
}

if ($_POST['from'] == 'home') {
    $logged = ($_GET['id'] != 'undefined') ? $_GET['id'] : getLoggedId();
    $sql1 = "SELECT ID FROM `sys_albums` WHERE `Owner` = '$logged' and Caption = 'Home Pictures' and `Type` = 'bx_photos' " . $sql_condition_home;
    $result1 = mysql_query($sql1);
    $row1 = mysql_fetch_array($result1);
    $albumid = $row1['ID'];

    $sql = "SELECT bx_photos_main . *
FROM bx_photos_main, sys_albums_objects
WHERE bx_photos_main.Owner =$logged
AND bx_photos_main.id = sys_albums_objects.id_object
AND sys_albums_objects.id_album =$albumid  " . $sql_condition_statue . " ORDER BY sys_albums_objects.obj_order";
    $result = mysql_query($sql);
    $nos = mysql_num_rows($result);
    if ($nos >= 1) {
        $count = 1;

        while ($row = mysql_fetch_array($result)) {
            $filename = $dir['root'] . 'modules/boonex/photos/data/files/' . $row['ID'] . "." . $row['Ext'];
            if (file_exists($filename)) {
                $margin_left = 0;
                $margin_top = 0;
                list($width, $height) = getimagesize("../../m/photos/get_image/file/{$row[Hash]}.{$row[Ext]}");
                if ($width > 425) {
                    $per = (($width - 425) / $width) * 100;
                    $height = $height - (($height * $per) / 100);
                    $width = 425;
                }
                if ($height > 300) {
                    $per = (($height - 300) / $height) * 100;
                    $width = $width - (($width * $per) / 100);
                    $height = 300;
                }
                $margin_left = ($width < 425) ? (420 - $width) / 2 : 0;
                $width = ($margin_left == 0) ? 425 : $width;
                $margin_top = ($height < 300) ? (300 - $height) / 2 : 0;
                $height = ($margin_top == 0) ? 300 : $height;
                $aData[] = "../../m/photos/get_image/file/{$row[Hash]}.{$row[Ext]}";
                $bData[] = "../../m/photos/get_image/thumb/{$row[Hash]}.{$row[Ext]}";
                $cData[] = $row['Title'];
                $count++;
            }
        }
        echo json_encode(array(
            'status' => 'success',
            'data' => $aData,
            'bData' => $bData,
            'cData' => $cData
        ));
    } else {

        echo json_encode(array(
            'status' => 'error',
            'Pmessage' => 'No uploaded photos in the home album'
        ));
    }
} else if ($_POST['from'] == 'profile') {
    $sql1 = "SELECT ID FROM `sys_albums` WHERE `Owner` = '$id' and Caption != 'Home Pictures'  and `Type` = 'bx_photos' " . $sql_condition_home;
    $result1 = mysql_query($sql1);
    $albumid = "";
    while ($row1 = mysql_fetch_array($result1)) {
        $albumid .= ' ' . $row1['ID'] . ',';
    }

    $albumid = substr($albumid, 0, -1);

    $sql = "SELECT bx_photos_main . *
        FROM bx_photos_main, sys_albums_objects
        WHERE bx_photos_main.Owner =$id
        AND bx_photos_main.id = sys_albums_objects.id_object
        AND sys_albums_objects.id_album in($albumid) " . $sql_condition_statue . " ORDER BY sys_albums_objects.id_album,sys_albums_objects.obj_order";
    //$sql = "SELECT * FROM `bx_photos_main` WHERE `Owner` = '$id' ";
    $result = mysql_query($sql);
    $nos = mysql_num_rows($result);
    if ($nos >= 1) {
        $count = 1;
        while ($row = mysql_fetch_array($result)) {
            $filename = $dir['root'] . 'modules/boonex/photos/data/files/' . $row['ID'] . "." . $row['Ext'];
            if (file_exists($filename)) {
                $margin_left = 0;
                $margin_top = 0;
                list($width, $height) = getimagesize("../../m/photos/get_image/file/{$row[Hash]}.{$row[Ext]}");
                if ($width > 425) {
                    $per = (($width - 425) / $width) * 100;
                    $height = $height - (($height * $per) / 100);
                    $width = 425;
                }
                if ($height > 300) {
                    $per = (($height - 300) / $height) * 100;
                    $width = $width - (($width * $per) / 100);
                    $height = 300;
                }
                $margin_left = ($width < 425) ? (425 - $width) / 2 : 0;
                $width = ($margin_left == 0) ? 425 : $width;
                $margin_top = ($height < 300) ? (300 - $height) / 2 : 0;
                $height = ($margin_top == 0) ? 300 : $height;
                $aData[] = "../../m/photos/get_image/file/" . $row[Hash] . "." . $row[Ext];
                $bData[] = "../../m/photos/get_image/thumb/" . $row[Hash] . "." . $row[Ext];
                $cData[] = $row['Title'];
                $count++;
            }
        }
        $photostatus = 'success';
        $Pmessage = '';
    } else {
        $photostatus = 'error';
        $Pmessage = 'No uploaded photos in the albums';
        $bData[] = '';
        $aData[] = '';
    }

    $aDataVideo [] = <<<EOF
				<div><a href='flash/modules/video/files/108.flv' style='display:block;width:560px;height:345px' id='player1'></a> 
				<script>
				flowplayer("player{$i}", "flowplayer-3.2.6.swf",{
				width: '560',
				height: '345',
				clip: {
					autoPlay: false,
				  }
				});
				</script></div><div>{$row[Description]}</div>;-
EOF;

    $videostatus = 'success';
    $Vmessage = 'Photo Not Available';

    echo json_encode(array(
        'status' => $photostatus,
        'data' => $aData,
        'sql' => $sql . $sql1,
        'bData' => $bData,
        'cData' => $cData,
        'Pmessage' => $Pmessage,
        'Vmessage' => $Vmessage,
        'statusVideo' => $videostatus,
        'video' => $aDataVideo,
    ));
}

if ($_GET['videos']) {

    $logged = ($_GET['id'] != 'undefined') ? $_GET['id'] : getLoggedId();
    $sql1 = "SELECT ID FROM `sys_albums` WHERE `Owner` = '$logged' and `Type` = 'bx_videos' " . $sql_condition_home;
    $result1 = mysql_query($sql1);


    $albumid = "";
    while ($row1 = mysql_fetch_array($result1)) {
        $albumid .= ' ' . $row1['ID'] . ',';
    }

    $albumid = substr($albumid, 0, -1);

    //$sql = "SELECT * FROM `RayVideoFiles` WHERE `Owner` = ".$logged." AND Time > 0  ORDER BY ID";
    $sql = "SELECT ph.* FROM RayVideoFiles as ph,sys_albums_objects sab WHERE ph.ID=sab.id_object AND sab.id_album in ($albumid) AND ph.Status = 'approved' AND ph.ytStatusCheck='processed'";
    $result = mysql_query($sql);
    $nos = mysql_num_rows($result);
    if ($nos >= 1) {
        $i = 1;
        $aData = array();
        $youtubeCode = array();
        while ($row = mysql_fetch_array($result)) {
            if ($row['Source'] == 'youtube' && $nos >= 1) {
                //  $aData [] = "<div><iframe title='YouTube video player' width='560' height='345' src='http://www.youtube.com/embed/" . $row['Video'] . "' frameborder='0' allowfullscreen></iframe></div>";
                // $aData[]= array('id' => $row[ID],'source' => 'youtube', 'srcid' => $row['Video']); 
            } elseif ($row['Source'] != 'youtube' && $nos >= 1) {
//                $aData[] = array('id' => $row[ID], 'source' => 'boonex', 'srcid' => $row[ID]);
                $videoURL = '';
                if ($row['YoutubeLink'] == '1') {
                    $videoURL = 'https://www.youtube.com/watch?v=' . $row['Uri'];
                    $youtubeCode[$row[ID]] = $row['Uri'];
                } else {
                if(file_exists('../../flash/modules/video/files/_'.$row[ID].'.flv'))
                    $videoURL = 'http://'.$_SERVER[HTTP_HOST].'/flash/modules/video/files/_'.$row[ID].'.flv';
                else if(file_exists('../../flash/modules/video/files/_'.$row[ID].'.mp4'))
                    $videoURL = 'http://'.$_SERVER[HTTP_HOST].'/flash/modules/video/files/_'.$row[ID].'.mp4';
                }
//                echo $videoURL;
                $aData[] = array('id' => $row[ID], 'source' => 'boonex', 'srcid' => $row[ID], 'videoURL' => $videoURL, 'youTube' => $row['YoutubeLink']);
            }
            $i++;
        }
        echo json_encode(array(
            'status' => 'success',
            'data' => $aData,
            'youtube' => $youtubeCode,
            'sql' => $sql . $sql1 . $albumid
        ));
    } else {
        $rs = mysql_query("SELECT A.`videoName` , A.`videoUri` FROM  `bx_groups_main` A, Profiles P WHERE A.`id` = P.AdoptionAgency AND P.id =$logged");
	$res = mysql_fetch_array($rs, MYSQL_ASSOC);        
	if($res['videoUri'] != null){
            $aData[] = array('id' => '0', 'source' => 'boonex', 'srcid' =>'0', 'videoURL' => 'https://www.youtube.com/watch?v=' . $res['videoUri'], 'youTube' => '1');
            $youtubeCode[0] = $res['videoUri'];
            echo json_encode(array(
                'status' => 'success',
                'data' => $aData,
                'youtube' => $youtubeCode
            ));
            }
            else {
                echo json_encode(array(
                'status' => 'error',
                'message' => 'No uploaded videos',
                'sql' => $sql . $sql1 . $albumid
            ));
        }
    }
}

if ($_GET['homevideos']) {

    $logged = ($_GET['id'] != 'undefined') ? $_GET['id'] : getLoggedId();
    $sql1 = "SELECT ID FROM `sys_albums` WHERE `Owner` = '$logged' and Caption = 'Home Videos' and `Type` = 'bx_videos' " . $sql_condition_home;
    $result1 = mysql_query($sql1);


    $albumid = "";
    while ($row1 = mysql_fetch_array($result1)) {
        $albumid .= ' ' . $row1['ID'] . ',';
    }

    $albumid = substr($albumid, 0, -1);

    //$sql = "SELECT * FROM `RayVideoFiles` WHERE `Owner` = ".$logged." AND Time > 0  ORDER BY ID";
    $sql = "SELECT ph.* FROM RayVideoFiles as ph,sys_albums_objects sab WHERE ph.ID=sab.id_object AND sab.id_album in ($albumid) AND ph.Status = 'approved' AND ph.ytStatusCheck='processed'";
    //$sql = "SELECT * FROM `RayVideoFiles` WHERE `Owner` = ".getLoggedId()." AND Time > 0 ORDER BY ID";
    $result = mysql_query($sql);
    $nos = mysql_num_rows($result);
    if ($nos >= 1) {
        $i = 1;
        $aData = array();
        $youtubeCode = array();
        while ($row = mysql_fetch_array($result)) {
            if ($row['Source'] == 'youtube' && $nos >= 1) {
                //  $aData [] = "<div><iframe title='YouTube video player' width='560' height='345' src='http://www.youtube.com/embed/" . $row['Video'] . "' frameborder='0' allowfullscreen></iframe></div>";
                $aData[] = array('id' => $row[ID], 'source' => 'youtube', 'srcid' => $row['Video']);
            } elseif ($row['Source'] != 'youtube' && $nos >= 1) {
//                $aData[] = array('id' => $row[ID], 'source' => 'boonex', 'srcid' => $row[ID]);
                // $aData[]= $row[ID];//array('id' => $row[ID],'source' => 'boonex', 'srcid' => $row[ID]);
                $videoURL = '';
                if ($row['YoutubeLink'] == '1') {
                    $videoURL = 'https://www.youtube.com/watch?v=' . $row['Uri'];
                    $youtubeCode[$row[ID]] = $row['Uri'];
                } else {
//                echo $_SERVER[HTTP_HOST].'/flash/modules/video/files/_'.$row[ID].'.flv';
                    if(file_exists('../../flash/modules/video/files/_'.$row[ID].'.flv'))
                        $videoURL = 'http://'.$_SERVER[HTTP_HOST].'/flash/modules/video/files/_'.$row[ID].'.flv';
                    else if(file_exists('../../flash/modules/video/files/_'.$row[ID].'.mp4'))
                        $videoURL = 'http://'.$_SERVER[HTTP_HOST].'/flash/modules/video/files/_'.$row[ID].'.mp4';
                }
                $aData[] = array('id' => $row[ID], 'source' => 'boonex', 'srcid' => $row[ID], 'videoURL' => $videoURL, 'youTube' => $row['YoutubeLink']);
            }
            $i++;
        }
        echo json_encode(array(
            'status' => 'success',
            'data' => $aData,
            'youtube' => $youtubeCode
        ));
    } else {
        $rs = mysql_query("SELECT A.`videoName` , A.`videoUri` FROM  `bx_groups_main` A, Profiles P WHERE A.`id` = P.AdoptionAgency AND P.id =$logged");
	$res = mysql_fetch_array($rs, MYSQL_ASSOC);         
	if($res['videoUri'] != null){
            $aData[] = array('id' => '0', 'source' => 'boonex', 'srcid' =>'0', 'videoURL' => 'https://www.youtube.com/watch?v=' . $res['videoUri'], 'youTube' => '1');
            $youtubeCode[0] = $res['videoUri'];
            echo json_encode(array(
                'status' => 'success',
                'data' => $aData,
                'youtube' => $youtubeCode
            ));
        }
        else {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'No uploaded Videos',
            ));
        }
    }
}
?>