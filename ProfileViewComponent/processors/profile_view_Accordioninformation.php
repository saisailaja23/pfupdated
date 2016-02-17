<?php

/*********************************************************************************
 * Name:    Prashanth A
 * Date:    02/11/2013
 * Purpose: Populating the values in family profile builder
 *********************************************************************************/

require_once '../../inc/header.inc.php';
require_once '../../inc/profiles.inc.php';
require_once '../../inc/design.inc.php';

$logid = ( $_GET['id']!='undefined' )?$_GET['id']:getLoggedId();
$member = getProfileInfo( $logid );
$mem_id = getMemberMembershipInfo($logid);
$stringSQL = " select couple from Profiles where id = '$logid'";
$result = mysql_query( $stringSQL );
$row = mysql_fetch_array( $result );
if ( $row['couple']>0 ) {

	$columns = 'id,DescriptionMe,agency_letter,letter_aboutThem,DearBirthParent,aboutp2,others,fname,coupleName,img_him,img_her,img_them,img_mother,img_agency';
	$stringSQL = "SELECT p1.id,p1.DescriptionMe, p1.agency_letter, p1.letter_aboutThem, p1.DearBirthParent, p2.DescriptionMe aboutp2,p1.others,p1.FirstName fname, p2.FirstName coupleName,p1.img_him,p2.img_her,p1.img_them,p1.img_mother,p1.img_agency
FROM Profiles_draft p1, Profiles_draft p2
WHERE p1.id = '$logid'
AND p1.couple = p2.id";
}
else {
	$columns = 'id,DescriptionMe,agency_letter,letter_aboutThem,DearBirthParent,aboutp2,others,fname,coupleName,img_him,img_her,img_them,img_mother,img_agency';
	$stringSQL = "SELECT id,DescriptionMe, agency_letter, letter_aboutThem, DearBirthParent, '' as aboutp2,others,FirstName fname, '' as coupleName,img_him,img_her,img_them,img_mother,img_agency
FROM Profiles
WHERE id = '$logid'";
}
$query = db_res( $stringSQL );
$cmdtuples = 1;
$arrColumns = explode( ",", $columns );
$arrRows = array();

$row = mysql_fetch_array( $query, MYSQL_BOTH );

$arrValues = array();
foreach ( $arrColumns as $column_name ) {
	array_push( $arrValues, str_replace( "\n", "<br/>", trim( $row[$column_name] ) ) );
	switch($column_name){
		case 'img_him':
			$pic = getPic($row[$column_name]);
			array_push($arrValues, $pic);
			break;
		case 'img_her':
			$pic = getPic($row[$column_name]);
			array_push($arrValues, $pic);
			break;
		case 'img_them':
			$pic = getPic($row[$column_name]);
			array_push($arrValues, $pic);
			break;
		case 'img_mother':
			$pic = getPic($row[$column_name]);
			array_push($arrValues, $pic);
			break;
		case 'img_agency':
			$pic = getPic($row[$column_name]);
			array_push($arrValues, $pic);
			break;
	}
}

function getPic($picID){
	$img = json_decode($picID);
	$img = implode(',', $img);
	
	$q2 = "SELECT CONCAT(Hash,'.', Ext) as img FROM `bx_photos_main` WHERE ID IN($img);";
	$r2 = mysql_query($q2);
	
	while($rw = mysql_fetch_array($r2)){
//            echo $path = "http://www.parentfinder.com//modules//boonex//photos//data//files//".$rw['Uri'];
//            $data = getimagesize($path);
//            $width = $data[0];
//            $height = $data[1];
//            if( $width != 0 && $height != 0 ){
		$img2[] = $rw['img'];
//            }
	}
	$ret = json_encode($img2);
	
	return $ret;

}

$columns = 'id,label,description';
$stringSQL = "SELECT id,label,description,img FROM letter WHERE profile_id = '$logid'";
$arrValuesOther= array();
$arrValuesOther['count'] = 0;//'success';
$query = db_res( $stringSQL );
$i=0;

while ( $row = mysql_fetch_array( $query, MYSQL_BOTH ) ) {

	$arrValuesOther['count']     =  mysql_numrows( $query );//'success';
	$arrValuesOther[$i]['id']    =  $row['id'];
	$arrValuesOther[$i]['label'] =  $row['label'];
	$arrValuesOther[$i]['description']=$row['description'];
	$arrValuesOther[$i]['img'] = getPic($row['img']);
	$i++;
}

array_push( $arrRows, array(
		'id' => $row[0],
		'data' => $arrValues,
	) );

//for letter_sort
$string_sql_Letterssort = "SELECT * FROM `letters_sort` WHERE profile_id=$logid ORDER BY order_by";
$query = mysql_query($string_sql_Letterssort);
// $cmdtuples = mysql_num_rows($query);


$result_letters_sort = array();

while ($row = mysql_fetch_object($query)) {
	array_push($result_letters_sort, $row);
}


if ( $cmdtuples > 0 ) {
	echo json_encode( array(
			'status' => 'success',
			'q'=>$stringSQL,
			'Profiles' => array(
				'id' => $row[0],
				'data' => $arrValues,
			),
                        'membership_id' => array(
			'rows' => $mem_id['ID'],
		),
			'letters_sort'=>$result_letters_sort,
			'other'=>$arrValuesOther
		) ); }
else {
	echo json_encode( array(
			'status' => 'err',
			'response' => 'Could not read the data: ' . mssql_get_last_message()
		) );
}
?>
