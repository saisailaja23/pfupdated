<?php

/* * *******************************************************************************
 * Name:    Satya Kiran R
 * Date:    25/2/2015
 * Purpose: Journal List
 * ******************************************************************************* */
require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');
//require_once ('../../inc/classes/BxDolTemplate.php');

$logid = ($_GET['id'] != 'undefined') ? $_GET['id'] : getLoggedId();
$jorName = $_GET['jorID'] ? $_GET['jorID'] : 'jorList';

$tablename = 'bx_blogs_posts';
$columns = 'PostDate,PostText,PostCaption';

if ($jorName != 'jorList') {
    if (!isLogged()) {
        $stringSQL_Posts = "SELECT `PostText`, FROM_UNIXTIME(PostDate,'%M %d, %Y') as PostDate,FROM_UNIXTIME(PostDate,'%h %i %s') as Posttime, PostCaption, PostID FROM " . $tablename . " where OwnerID = " . $logid . " and PostStatus = 'approval'  and allowView = '3' and PostID = $jorName ORDER BY FROM_UNIXTIME(PostDate,'%Y %m, %d') DESC ,
 FROM_UNIXTIME(PostDate,'%h %i %s') DESC";
    } else {
        $stringSQL_Posts = "SELECT `PostText`, FROM_UNIXTIME(PostDate,'%M %d, %Y') as PostDate,FROM_UNIXTIME(PostDate,'%h %i %s') as Posttime, PostCaption, PostID FROM " . $tablename . " where OwnerID = " . $logid . " and PostStatus = 'approval'  and allowView IN ('4','3') and PostID = $jorName ORDER BY FROM_UNIXTIME(PostDate,'%Y %m, %d') DESC ,
 FROM_UNIXTIME(PostDate,'%h %i %s') DESC";
    }

    $query = db_res($stringSQL_Posts);
    $cmdtuples = mysql_num_rows($query);
    $arrColumns = explode(",", $columns);
    $arrRows_posts = array();

    while (($row = mysql_fetch_array($query, MYSQL_BOTH))) {
        $arrValues = array();
        foreach ($arrColumns as $column_name) {
            array_push($arrValues, $row[$column_name]);
        }
        array_push($arrValues, $row['PostID']);
        array_push($arrRows_posts, array(
            'id' => $row[0],
            'data' => $arrValues,
        ));
    }
} else {
    $tablename = 'bx_blogs_posts';
    $columns = 'PostDate,PostText,PostCaption';
    if (!isLogged()) {
        $stringSQL_Posts = "SELECT REPLACE(REPLACE(PostText,'<p>',''),'</p>','') as `PostText`, FROM_UNIXTIME(PostDate,'%M %d, %Y') as PostDate,FROM_UNIXTIME(PostDate,'%h %i %s') as Posttime, PostCaption, PostID FROM " . $tablename . " where OwnerID = " . $logid . " and PostStatus = 'approval'  and allowView = '3' ORDER BY FROM_UNIXTIME(PostDate,'%Y %m, %d') DESC ,
 FROM_UNIXTIME(PostDate,'%h %i %s') DESC";
    } else {
        $stringSQL_Posts = "SELECT REPLACE(REPLACE(PostText,'<p>',''),'</p>','') as `PostText`, FROM_UNIXTIME(PostDate,'%M %d, %Y') as PostDate,FROM_UNIXTIME(PostDate,'%h %i %s') as Posttime, PostCaption, PostID FROM " . $tablename . " where OwnerID = " . $logid . " and PostStatus = 'approval'  and allowView IN ('4','3') ORDER BY FROM_UNIXTIME(PostDate,'%Y %m, %d') DESC ,
 FROM_UNIXTIME(PostDate,'%h %i %s') DESC";
    }

    $query = db_res($stringSQL_Posts);
    $cmdtuples = mysql_num_rows($query);
    $arrColumns = explode(",", $columns);
    $arrRows_posts = array();

    while (($row = mysql_fetch_array($query, MYSQL_BOTH))) {
        $arrValues = array();
        foreach ($arrColumns as $column_name) {
            if ($column_name == 'PostText') {
                $trimText = preg_replace("/<img[^>]+\>/i", " ", $row[$column_name]);
                if (strlen($trimText) > 200) {
                    $trimText = substr($trimText, 0, 196);
                    $trimText .= '...';
                } else
                    $trimText = $trimText;
                array_push($arrValues, $trimText);
            }
            array_push($arrValues, $row[$column_name]);
        }
        array_push($arrValues, $row['PostID']);
        array_push($arrRows_posts, array(
            'id' => $row[0],
            'data' => $arrValues,
        ));
    }
}
if ($cmdtuples > 0) {
    echo json_encode(array(
        'status' => 'success',
        'blog_posts' => array(
            'rows' => $arrRows_posts
        )
    ));
} else {
    echo json_encode(array(
        'status' => 'err',
        'response' => 'Could not read the data'
    ));
}
?>
