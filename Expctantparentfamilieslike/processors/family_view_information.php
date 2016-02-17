 <?php
/*********************************************************************************
* Name:    Prashanth A
* Date:    02/11/2013
* Purpose: Gettting the family information
*********************************************************************************/
require_once ('../../inc/header.inc.php');

require_once ('../../inc/profiles.inc.php');

$logid = getLoggedId();
$tablename = 'Profiles';
$columns = 'ID,FirstName,LastName,AdoptionAgency';
$stringSQL = "SELECT  " . $columns . " FROM " . $tablename . " where ID = " . $logid . "";
$query = db_res($stringSQL);
$cmdtuples = mysql_num_rows($query);
$arrColumns = explode(",", $columns);
$arrRows = array();

while (($row = mysql_fetch_array($query, MYSQL_BOTH)))
{
$arrValues = array();
foreach($arrColumns as $column_name)
{
array_push($arrValues, $row[$column_name]);
}

array_push($arrRows, array(
'id' => $row[0],
'data' => $arrValues,
));
}

$LastActivetime = db_arr("SELECT `DateLastLogin` FROM `Profiles` WHERE `ID` = '$logid' LIMIT 1");
$Activetime = $LastActivetime['DateLastLogin'];
$date1 = time();
$date2 = strtotime($Activetime);
$getlogid = getLoggedId();
$dateDiff = $date1 - $date2;
$fullDays = floor($dateDiff / (60 * 60 * 24));
$fullHours = floor(($dateDiff - ($fullDays * 60 * 60 * 24)) / (60 * 60));
$fullMinutes = floor(($dateDiff - ($fullDays * 60 * 60 * 24) - ($fullHours * 60 * 60)) / 60);
$total = "LAST ACTIVE " . $fullDays . " DAYS " . $fullHours . " HOURS AND " . $fullMinutes . " MINUTES ago";

$AgencyLike_List = "SELECT favouredfamily  FROM `family_favourite` WHERE `favouredby` = " . $logid . "";
$agency_query = mysql_query($AgencyLike_List);
$favourd_family = mysql_num_rows($agency_query);

$familiesLike_List = "SELECT FamilyLiked  FROM `like_list_family` WHERE `LikedBy` = " . $logid . "";
$family_query = mysql_query($familiesLike_List);
$family_liked = mysql_num_rows($family_query);



if ($cmdtuples > 0)
{
echo json_encode(array(
'status' => 'success',
'Profiles' => array(
'rows' => $arrRows
) ,
'Profile_active' => array(
'rows' => $total
) ,

'favourd_family' => array(
'rows' => $favourd_family
) ,
    
'family_liked' => array(
'rows' => $family_liked
) ,
));
}
else
{
echo json_encode(array(
'status' => 'err',
'response' => 'Could not read the data: ' . mssql_get_last_message()
));
}

?>
