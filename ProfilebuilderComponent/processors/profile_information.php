<?php
/*********************************************************************************
 * Name:    Prashanth A
 * Date:    02/11/2013
 * Purpose: Populating the values in family profile builder
 *********************************************************************************/

require_once '../../inc/header.inc.php';
require_once '../../inc/profiles.inc.php';

header( "Content-type:application/json" );

$logid = ( $_GET['id']!='undefined' )?$_GET['id']:getLoggedId();
//$logid = getLoggedId();
$member = getProfileInfo( $logid );

$tablename = 'Profiles_draft';
$columns = 'ID,Email,FirstName,State,Password,AdoptionAgency,Facebook,Twitter,Google,Blogger,Pinerest,DateOfBirth,state,waiting,noofchildren,faith,childethnicity,childage,Adoptiontype,phonenumber,street_address,housestyle,noofbedrooms,noofbathrooms,yardsize,neighbourhoodlike,DearBirthParent,DescriptionMe,WEB_URL,Couple,childrenType,address1,address2,city,zip,Region,NickName,Specialneeds,SpecialNeedsOptions,ChildGender,ChildDesired,BirthFatherStatus,Sex,Ethnicity,Education,Religion,Occupation,Pet,RelationshipStatus,FamilyStructure,Region,Country,show_contact,allow_contact,Instagram';
$stringSQL = "SELECT  " . $columns . " FROM " . $tablename . " where ID = " . $logid . "";
$query = db_res( $stringSQL );
$cmdtuples = mysql_num_rows( $query );
$arrColumns = explode( ",", $columns );
$arrRows = array();

while ( ( $row = mysql_fetch_array( $query, MYSQL_BOTH ) ) ) {
	$Agency = $row['AdoptionAgency'];
	$arrValues = array();
	foreach ( $arrColumns as $column_name ) {
		array_push( $arrValues, $row[$column_name] );
	}

	array_push( $arrRows, array(
			'id' => $row[0],
			'data' => $arrValues,
		) );
}

 $profilenumber = db_arr("SELECT  p.Profile_no as profile_number , p.Profile_year as profile_year FROM  Profiles AS p WHERE  p.ID ='$logid'");
 if($profilenumber['profile_number'] != 0 ){
 $profile_number = $profilenumber['profile_year']."_".str_pad($profilenumber['profile_number'], 4, "0", STR_PAD_LEFT);
 }else{
 $profile_number = '';
 }
$detail_profile_number = $profile_number;

$columns = 'label'; //
//$stringSQL_Letter = "SELECT id,label FROM `letter` WHERE profile_id=$logid";
$stringSQL_Letter = "SELECT id,label FROM `letter` WHERE profile_id=$logid order by id desc";
$query = mysql_query( $stringSQL_Letter );
$cmdtuples = mysql_num_rows( $query );
$arrColumns = explode( ",", $columns );
$arrRows_letter = array();

while ( ( $row = mysql_fetch_array( $query, MYSQL_BOTH ) ) ) {
	$arrValues = array();
	foreach ( $arrColumns as $column_name ) {
		array_push( $arrValues, $row[$column_name] );
	}

	array_push( $arrRows_letter, array(
			'id'=> $row[0],
			'label' => $row[1]
		) );
}

//for letter_sort
$stringSQL_Letterssort = "SELECT * FROM `letters_sort` WHERE profile_id=$logid ORDER BY order_by";
$query = mysql_query($stringSQL_Letterssort);
$cmdtuples = mysql_num_rows($query);
$arrColumns = explode(",", $columns);
$arrRows_letters_sort = array();

while (($row = mysql_fetch_array($query, MYSQL_BOTH))) {
	$arrValues = array();
 	foreach ($arrColumns as $column_name) {
  		array_push($arrValues, $row[$column_name]);
 	}
	array_push($arrRows_letters_sort, array(
   		'id'=> $row[0],
   		'label' => $row[1],
   		'order_by' => $row[2]
  	));
}


$tablename = 'Profiles_draft';
$columns = 'ID,FirstName,DateOfBirth,DescriptionMe,Sex,Ethnicity,Education,Religion,Occupation';
$stringSQL_t = "SELECT  " . $columns . " FROM " . $tablename . " where Couple = " . $logid. "";
$query = db_res( $stringSQL_t );
$cmdtuples = 1;
$arrColumns = explode( ",", $columns );
$arrRows_couple = array();

while ( ( $row = mysql_fetch_array( $query, MYSQL_BOTH ) ) ) {
	$arrValues = array();
	foreach ( $arrColumns as $column_name ) {
		array_push( $arrValues, $row[$column_name] );
	}

	array_push( $arrRows_couple, array(
			'id' => $row[0],
			'data' => $arrValues,
		) );
}


$columns = 'Value,String'; //
$stringSQL_Agency = "SELECT  sys_pre_values.Value,sys_localization_strings.String FROM sys_pre_values,sys_localization_keys,sys_localization_strings WHERE sys_localization_keys.Key = sys_pre_values.LKey and sys_localization_keys.ID = sys_localization_strings.IDKey and sys_pre_values.Key = 'AdoptionAgency' Order By sys_pre_values.LKey ";
$query = mysql_query( $stringSQL_Agency );
$cmdtuples = mysql_num_rows( $query );
$arrColumns = explode( ",", $columns );
$arrRows_agency = array();

while ( ( $row = mysql_fetch_array( $query, MYSQL_BOTH ) ) ) {
	$arrValues = array();
	foreach ( $arrColumns as $column_name ) {
		array_push( $arrValues, $row[$column_name] );
	}

	array_push( $arrRows_agency, array(
			'id' => $row[0],
			'data' => $arrValues,
		) );
}


if ( @mysql_num_rows( mysql_query( "SELECT id FROM bx_groups_main WHERE id=".$member['AdoptionAgency']." AND author_id=".$logid ) ) ) {
	$mFlag=false;
}else {
	if ( @mysql_num_rows( mysql_query( "SELECT GroupId FROM bx_groups_moderation WHERE GroupId =".$member['AdoptionAgency']."  AND ApproveStatus= 'on' AND Type = 'editedprofiles'" ) ) ) {
		$mFlag=true;
	}
	else {
		$mFlag=false;
	}
}


$tablename = 'Profiles_draft';
$columns = 'State';
$stringSQL_State = "SELECT  " . $columns . " FROM " . $tablename . " WHERE State != '' GROUP BY State ";
$query = mysql_query( $stringSQL_State );
$cmdtuples = mysql_num_rows( $query );
$arrColumns = explode( ",", $columns );
$arrRows_State = array();

while ( ( $row = mysql_fetch_array( $query, MYSQL_BOTH ) ) ) {
	$arrValues = array();
	foreach ( $arrColumns as $column_name ) {
		array_push( $arrValues, $row[$column_name] );
	}

	array_push( $arrRows_State, array(
			'id' => $row[0],
			'data' => $arrValues,
		) );
}

$stringSQL_Children = "SELECT config_value, config_description FROM sys_configuration WHERE config_key = 'noofchildren' ORDER BY config_order ASC ";
$query_children     = mysql_query( $stringSQL_Children );

$arrColumns = explode( ",", 'config_description' );
$arrRows_children = array();
$arr_child_Values = array();
array_push( $arr_child_Values, 'Please Select' );
array_push( $arrRows_children, array(
		'id' => '',
		'data' => $arr_child_Values,
	) );
while ( ( $row_children = mysql_fetch_array( $query_children, MYSQL_BOTH ) ) ) {
	$arr_child_Values = array();
	foreach ( $arrColumns as $column_name ) {
		array_push( $arr_child_Values, $row_children[$column_name] );
	}

	array_push( $arrRows_children, array(
			'id' => $row_children[0],
			'data' => $arr_child_Values,
		) );
}

if ( @mysql_num_rows( mysql_query( "SELECT id FROM bx_groups_main WHERE id=".$Agency." AND author_id=".$logid ) ) ) {
	$mFlag=false;
}else {
	if ( @mysql_num_rows( mysql_query( "SELECT GroupId FROM bx_groups_moderation WHERE GroupId =".$Agency."  AND ApproveStatus= 'on' AND Type = 'editedprofiles'" ) ) ) {
		$mFlag=true;
	}
	else {
		$mFlag=false;
	}
}


$tablename = 'sys_pre_values';
$columns = 'Value,LKey';
$stringSQL_Region = "SELECT  " . $columns . " FROM " . $tablename . " WHERE `Key` = 'ChildDesired' ORDER BY `sys_pre_values`.`Order` ASC";
$query = mysql_query( $stringSQL_Region );
$cmdtuples = mysql_num_rows( $query );
$arrColumns = explode( ",", $columns );
$arrRows_ChildDesired = array();

while ( ( $row = mysql_fetch_array( $query, MYSQL_BOTH ) ) ) {
	$arrValues = array();
	foreach ( $arrColumns as $column_name ) {
		array_push( $arrValues, $row[$column_name] );
	}

	array_push( $arrRows_ChildDesired, array(
			'id' => $row[0],
			'data' => $arrValues,
		) );
}


$tablename = 'letter_caption';
$columns = 'Caption_ID,Caption_Name';
$stringSQL_Caption= "SELECT  " . $columns . " FROM " . $tablename . "";
$query = mysql_query( $stringSQL_Caption );
$cmdtuples = mysql_num_rows( $query );
$arrColumns = explode( ",", $columns );
$arrRows_Caption= array();

while ( ( $row = mysql_fetch_array( $query, MYSQL_BOTH ) ) ) {
	$arrValues = array();
	foreach ( $arrColumns as $column_name ) {
		array_push( $arrValues, $row[$column_name] );
	}

	array_push( $arrRows_Caption, array(
			'id' => $row[0],
			'data' => $arrValues,
		) );
}


$tablename = 'sys_pre_values';
$columns = 'Value,LKey';
$stringSQL_Region = "SELECT  " . $columns . " FROM " . $tablename . " WHERE `Key` = 'BirthFatherStatus' ORDER BY `sys_pre_values`.`Order` ASC";
$query = mysql_query( $stringSQL_Region );
$cmdtuples = mysql_num_rows( $query );
$arrColumns = explode( ",", $columns );
$arrRows_BirthFatherStatus = array();

while ( ( $row = mysql_fetch_array( $query, MYSQL_BOTH ) ) ) {
	$arrValues = array();
	foreach ( $arrColumns as $column_name ) {
		array_push( $arrValues, $row[$column_name] );
	}

	array_push( $arrRows_BirthFatherStatus, array(
			'id' => $row[0],
			'data' => $arrValues,
		) );
}


$tablename = 'sys_pre_values';
$columns = 'Value,LKey';
$stringSQL_Region = "SELECT  " . $columns . " FROM " . $tablename . " WHERE `Key` = 'Ethnicityofcouple' ORDER BY `sys_pre_values`.`Order` ASC";
$query = mysql_query( $stringSQL_Region );
$cmdtuples = mysql_num_rows( $query );
$arrColumns = explode( ",", $columns );
$arrRows_Ethnicityofcouple = array();

while ( ( $row = mysql_fetch_array( $query, MYSQL_BOTH ) ) ) {
	$arrValues = array();
	foreach ( $arrColumns as $column_name ) {
		array_push( $arrValues, $row[$column_name] );
	}

	array_push( $arrRows_Ethnicityofcouple, array(
			'id' => $row[0],
			'data' => $arrValues,
		) );
}

$tablename = 'sys_pre_values';
$columns = 'Value,LKey';
$stringSQL_Region = "SELECT  " . $columns . " FROM " . $tablename . " WHERE `Key` = 'educationofcouple' ORDER BY `sys_pre_values`.`Order` ASC";
$query = mysql_query( $stringSQL_Region );
$cmdtuples = mysql_num_rows( $query );
$arrColumns = explode( ",", $columns );
$arrRows_educationofcouple = array();

while ( ( $row = mysql_fetch_array( $query, MYSQL_BOTH ) ) ) {
	$arrValues = array();
	foreach ( $arrColumns as $column_name ) {
		array_push( $arrValues, $row[$column_name] );
	}

	array_push( $arrRows_educationofcouple, array(
			'id' => $row[0],
			'data' => $arrValues,
		) );
}

$tablename = 'sys_pre_values';
$columns = 'Value,LKey';
$stringSQL_Region = "SELECT  " . $columns . " FROM " . $tablename . " WHERE `Key` = 'ReligionCouple' ORDER BY `sys_pre_values`.`Order` ASC";
$query = mysql_query( $stringSQL_Region );
$cmdtuples = mysql_num_rows( $query );
$arrColumns = explode( ",", $columns );
$arrRows_ReligionCouple = array();

while ( ( $row = mysql_fetch_array( $query, MYSQL_BOTH ) ) ) {
	$arrValues = array();
	foreach ( $arrColumns as $column_name ) {
		array_push( $arrValues, $row[$column_name] );
	}

	array_push( $arrRows_ReligionCouple, array(
			'id' => $row[0],
			'data' => $arrValues,
		) );
}

$tablename = 'sys_pre_values';
$columns = 'Value,LKey';
$stringSQL_Region = "SELECT  " . $columns . " FROM " . $tablename . " WHERE `Key` = 'Region' ORDER BY `sys_pre_values`.`Order` ASC";
$query = mysql_query( $stringSQL_Region );
$cmdtuples = mysql_num_rows( $query );
$arrColumns = explode( ",", $columns );
$arrRows_Region = array();

while ( ( $row = mysql_fetch_array( $query, MYSQL_BOTH ) ) ) {
	$arrValues = array();
	foreach ( $arrColumns as $column_name ) {
		array_push( $arrValues, $row[$column_name] );
	}

	array_push( $arrRows_Region, array(
			'id' => $row[0],
			'data' => $arrValues,
		) );
}
$tablename = 'sys_pre_values';
$columns = 'LKey,value';
$stringSQL_Region = "SELECT  " . $columns . " FROM " . $tablename . " WHERE `Key` = 'Country' ORDER BY `sys_pre_values`.`Order` ASC";
$query = mysql_query( $stringSQL_Region );
$cmdtuples = mysql_num_rows( $query );
$arrColumns = explode( ",", $columns );
$arrRows_Country = array();

while ( ( $row = mysql_fetch_array( $query, MYSQL_BOTH ) ) ) {
	$arrValues = array();
	foreach ( $arrColumns as $column_name ) {
		array_push( $arrValues, $row[$column_name] );
	}

	array_push( $arrRows_Country, array(
			'id' => $row[0],
			'data' => $arrValues,
		) );
}

$tablename = 'sys_pre_values';
$columns = 'Value,LKey';
$stringSQL_Region = "SELECT  " . $columns . " FROM " . $tablename . " WHERE `Key` = 'pet' ORDER BY `sys_pre_values`.`Order` ASC";
$query = mysql_query( $stringSQL_Region );
$cmdtuples = mysql_num_rows( $query );
$arrColumns = explode( ",", $columns );
$arrRows_pet = array();

while ( ( $row = mysql_fetch_array( $query, MYSQL_BOTH ) ) ) {
	$arrValues = array();
	foreach ( $arrColumns as $column_name ) {
		array_push( $arrValues, $row[$column_name] );
	}

	array_push( $arrRows_pet, array(
			'id' => $row[0],
			'data' => $arrValues,
		) );
}

$tablename = 'sys_pre_values';
$columns = 'Value,LKey';
$stringSQL_Region = "SELECT  " . $columns . " FROM " . $tablename . " WHERE `Key` = 'RelationshipType' ORDER BY `sys_pre_values`.`Order` ASC";
$query = mysql_query( $stringSQL_Region );
$cmdtuples = mysql_num_rows( $query );
$arrColumns = explode( ",", $columns );
$arrRows_relationship = array();

while ( ( $row = mysql_fetch_array( $query, MYSQL_BOTH ) ) ) {
	$arrValues = array();
	foreach ( $arrColumns as $column_name ) {
		array_push( $arrValues, $row[$column_name] );
	}

	array_push( $arrRows_relationship, array(
			'id' => $row[0],
			'data' => $arrValues,
		) );
}
$tablename = 'sys_pre_values';
$columns = 'Value,LKey';
$stringSQL_Region = "SELECT  " . $columns . " FROM " . $tablename . " WHERE `Key` = 'structureoffamily' ORDER BY `sys_pre_values`.`Order` ASC";
$query = mysql_query( $stringSQL_Region );
$cmdtuples = mysql_num_rows( $query );
$arrColumns = explode( ",", $columns );
$arrRows_family = array();

while ( ( $row = mysql_fetch_array( $query, MYSQL_BOTH ) ) ) {
	$arrValues = array();
	foreach ( $arrColumns as $column_name ) {
		array_push( $arrValues, $row[$column_name] );
	}

	array_push( $arrRows_family, array(
			'id' => $row[0],
			'data' => $arrValues,
		) );
}
//Sailaja- to check if flipbook and e-pub are already uploaded
$E_book = db_arr("SELECT content FROM `aqb_pc_members_blocks` WHERE `owner_id` = '$logid' and title = 'E-book Profile'");
$E_book_link = $E_book[content];
$start = strpos($E_book_link, ".com/") + 5;
$end = strpos($E_book_link, ".html") - $start + 5;
$flipbook = substr($E_book_link, $start, $end);

$flipbook_mob_link = false;
if ($flipbook != false) {
	$flipbook_mob = split('/', $flipbook);
	$flipbook_mob_link = $flipbook_mob[0] . "/" . $flipbook_mob[1] . "/mobile/index.html";

	$flipbook_mob_filename = $dir['root'] . $flipbook_mob_link;
	if (!(file_exists($flipbook_mob_filename))) {
		$flipbook_mob_link = false;
	}
}

$E_pup = db_arr("SELECT content FROM `aqb_pc_members_blocks` WHERE `owner_id` = '$logid' and title = 'E-PUB Profile'");
$E_pup_link = $E_pup[content];
$pos = strpos($E_pup_link, "Please");
if ($E_pup_link != false) {
	if ($pos === false) {
		$epup = $E_pup_link;
	} else {
		$start = strpos($E_pup_link, "http");
		$end = strpos($E_pup_link, ".html") - $start + 5;
		$epup = substr($E_pup_link, $start, $end);
	}
} else {
	$epup = false;
}
//Creating a badge code

$GID = $member['AdoptionAgency'];
$width=100;
$embedd_div_id = date( "mdGis" ).'m'.$member['ID'].'g'.$GID.'c'.substr( md5( uniqid( rand(), true ) ), 0, 5 );
$embedd_validator_code = generateGroupConf( $member['ID'], $GID );

$embedd_code = '<!--Badge Start --><div id="'.$embedd_div_id.'">

<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.min.js" ></script><div style="width:350px;text-align:center">
<font style="font-size:10px;font-family: verdana;font-weight: none;"><a href="'.$site['url'].'">'.$site['title'].' '._t( "_gkc_bw_Groups" ).'</a></font>
</div></div><script type="text/javascript" src="'.$site['url'].'load_badge_widget.php?GID='.$GID.'&MID='.$member['ID'].'&logid=' . $logid . '
&display=groupownersingle&conf='.$embedd_validator_code.'&width='.$width.'" onload="document.getElementById(\''.$embedd_div_id.'\').innerHTML=\'\';"></script>
<!-- Badge End -->';


/**
 *
 *
 * @param unknown $MID (optional)
 * @param unknown $GID (optional)
 * @return unknown
 */
function generateGroupConf( $MID=0, $GID=0 ) {
	$out = ( $MID+7*( $GID ) )*( $MID )+9186;
	return $out;
}

$mem_id  = getMemberMembershipInfo( getLoggedId() );

$memberID = $_COOKIE['memberID'];
$ftr = db_arr("SELECT tlm.IDLevel from `Profiles` p LEFT JOIN `sys_acl_levels_members` AS `tlm` ON `p`.`ID`=`tlm`.`IDMember` AND `tlm`.`DateStarts` < NOW() AND (`tlm`.`DateExpires`>NOW() || ISNULL(`tlm`.`DateExpires`)) WHERE p.ID = $memberID  GROUP BY p.ID");

if ( $cmdtuples > 0 ) {
	echo json_encode( array(
			'status' => 'success',
			'auto_approve' => $mFlag,
			'Profiles' => array(
				'rows' => $arrRows
			),
			'Profiles_couple' => array(
				'rows' => $arrRows_couple
			),
			'Profiles_agency' => array(
				'rows' => $arrRows_agency
			),

			'Profiles_badge' => array(
				'rows' => $embedd_code
			),
			'Profiles_Letters' => array(
				'rows' => $arrRows_letter
			),
			'Profiles_Letters_Sort' => array(
				'rows' => $arrRows_letters_sort
			),
                        'flip_link' => array(
			'rows' => $flipbook,
                        ),
                        'epub_link' => array(
                                'rows' => $epup,
                        ),
			'Profiles_State' => array(
				'rows' => $arrRows_State
			),
			'Profiles_Children' => array(
				'rows' => $arrRows_children
			),
			'Profiles_ChildDesired' => array(
				'rows' => $arrRows_ChildDesired
			),
			'Profiles_BirthFatherStatus' => array(
				'rows' => $arrRows_BirthFatherStatus
			),
			'Profiles_Ethnicityofcouple' => array(
				'rows' => $arrRows_Ethnicityofcouple
			),
			'Profiles_educationofcouple' => array(
				'rows' => $arrRows_educationofcouple
			),
			'Profiles_ReligionCouple' => array(
				'rows' => $arrRows_ReligionCouple
			),
			'Profiles_Region' => array(
				'rows' => $arrRows_Region
			),
			'Profiles_Country' => array(
				'rows' => $arrRows_Country
			),
			'Profiles_pet' => array(
				'rows' => $arrRows_pet
			),
			'Profiles_relationship' => array(
				'rows' => $arrRows_relationship
			),
			'Profiles_familystruct' => array(
				'rows' => $arrRows_family
			),
			'Letter_Caption' => array(
				'rows' => $arrRows_Caption
			),


			'agency_flag' => array(
				'rows' => $mFlag
			),
			'membership_id' => array(
				'rows' => $mem_id['ID']
			),
		'profilenumber' => array(
			'rows' => $detail_profile_number
		),
			'sql_statement' => $stringSQL,
			'featured' => $ftr['IDLevel']
		) );
}
else {
	echo json_encode( array(
			'status' => 'err',
			'response' => 'Could not read the data: '

		) );
}



?>
