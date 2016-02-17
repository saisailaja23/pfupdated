<?php

/* * *******************************************************************************
 * Name:    Eswar N
 * Date:    12/12/2014
 * Purpose: Listing the families videos 
 * ******************************************************************************* */
require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');
header("content-type: application/json");
$json = array();
$logid = $videos_userid = $_REQUEST['id'];

$sql = "SELECT album.ID,album.Caption,album.AllowAlbumView,max(ph.Views) as Views, ph.ID as Hash, 'jpg' as Ext FROM	(SELECT * FROM sys_albums) album LEFT JOIN (SELECT * FROM sys_albums_objects) sab  ON album.ID=sab.id_album	LEFT JOIN (SELECT * FROM RayVideoFiles) ph ON ph.ID=sab.id_object WHERE	album.Type='bx_videos' AND album.Owner = $videos_userid group by album.ID,album.Caption order by album.id desc";

$alubem_query = mysql_query($sql);

$nos = mysql_num_rows($alubem_query);
$i =1;
if($nos > 0){
	while (($row = mysql_fetch_array($alubem_query, MYSQL_BOTH))) {

		$json['status'] ='success';
		$json['count'] = $nos;
		$json['album'][$i] = $row;
		$albumlist_sql="SELECT ph.ID,ph.Title,ph.Views,ph.Source,ph.Status,ph.CommentsCount FROM RayVideoFiles as ph,sys_albums_objects sab WHERE ph.ID=sab.id_object	AND sab.id_album=".$row['ID']."  ORDER BY ph.ID desc";
		$albumlist_query = mysql_query($albumlist_sql);
		$albumlist_nos = mysql_num_rows($albumlist_query);
		$j=1;
		$json['album'][$i]['albumlist']['count'] = $albumlist_nos;
			while (($albumlist_row = mysql_fetch_array($albumlist_query, MYSQL_BOTH))) {
				$json['album'][$i]['albumlist'][$j] = $albumlist_row;
				$j++;
			}

	$i++;
	}
}else{
	$json['status'] = 'No Albums';
}

$tablename = ($_REQUEST['approve']!='undefined' && $_REQUEST['approve']==1)?'Profiles_draft':'Profiles';
$columns = 'ID,FirstName,Age,DescriptionMe';
$stringSQL_t = "SELECT  " . $columns . " FROM " . $tablename . " where Couple = " . $logid. "";
$query = db_res($stringSQL_t);
$cmdtuples = mysql_num_rows($query);
$arrColumns = explode(",", $columns);
$arrRows_couple = array();

while (($row = mysql_fetch_array($query, MYSQL_BOTH)))
{
$arrValues = array();
foreach($arrColumns as $column_name)
{
array_push($arrValues, $row[$column_name]);
}

array_push($arrRows_couple, array(
'id' => $row[0],
'data' => $arrValues,
));
}

$tablename = ($_REQUEST['approve']!='undefined' && $_REQUEST['approve']==1)?'Profiles_draft':'Profiles';
$columns = 'ID,Email,FirstName,State,Password,AdoptionAgency,Facebook,Twitter,Google,Blogger,Pinerest,Age,state,waiting,noofchildren,faith,childethnicity,childage,Adoptiontype,phonenumber,street_address,DescriptionMe,yardsize,noofbathrooms,noofbedrooms,housestyle,WEB_URL,Couple,About_our_home,NickName,address1,address2,city,zip,Status';
$stringSQL = "SELECT  " . $columns . " FROM " . $tablename . " where ID = " . $logid . " ";
$query = db_res($stringSQL);
$cmdtuples = mysql_num_rows($query);
$arrColumns = explode(",", $columns);
$arrRows = array();

while (($row = mysql_fetch_array($query, MYSQL_BOTH)))
{
$arrValues = array();
foreach($arrColumns as $column_name)
{
//array_push($arrValues, $row[$column_name]);
  array_push($arrValues, str_replace("\n","<br/>",trim($row[$column_name])));  
}

array_push($arrRows, array(
'id' => $row[0],
'data' => $arrValues,
));
}

$json['Profiles']['rows'] = $arrRows;
$json['Profiles_couple']['rows'] = $arrRows_couple;





echo json_encode($json);
?>
