<?php
/*********************************************************************************
 * Name:    Prashanth A
 * Date:    02/11/2013
 * Purpose: Populating the values in family profile builder
 *********************************************************************************/

require_once('../../inc/header.inc.php');
require_once('../../inc/profiles.inc.php');


$logid = getLoggedId();
$member = getProfileInfo($logid);
        
$tablename = 'Profiles_draft';
$columns = 'ID,Email,FirstName,State,Password,AdoptionAgency,Facebook,Twitter,Google,Blogger,Pinerest,Age,state,waiting,noofchildren,faith,childethnicity,childage,Adoptiontype,phonenumber,street_address,housestyle,noofbedrooms,noofbathrooms,yardsize,neighbourhoodlike,DearBirthParent,DescriptionMe,WEB_URL,Couple,childrenType,address1,address2,city,zip,Region,NickName';
 $stringSQL = "SELECT  " . $columns . " FROM " . $tablename . " where ID = " . $logid . "";
$query = db_res($stringSQL);
$cmdtuples = mysql_num_rows($query);
$arrColumns = explode(",", $columns);
$arrRows = array();

while (($row = mysql_fetch_array($query, MYSQL_BOTH)))
{
     $Agency = $row['AdoptionAgency'];
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


$columns = 'label';
$stringSQL_Letter = "SELECT label FROM `letter` WHERE profile_id=$logid";
$query = mysql_query($stringSQL_Letter);
$cmdtuples = mysql_num_rows($query);
$arrColumns = explode(",", $columns);
$arrRows_letter = array();

while (($row = mysql_fetch_array($query, MYSQL_BOTH)))
{
$arrValues = array();
foreach($arrColumns as $column_name)
{
array_push($arrValues, $row[$column_name]);
}

array_push($arrRows_letter, array(
'label' => $row[0]
));
}


$tablename = 'Profiles_draft';
$columns = 'ID,FirstName,age,DescriptionMe';
$stringSQL_t = "SELECT  " . $columns . " FROM " . $tablename . " where Couple = " . $logid. "";
$query = db_res($stringSQL_t);
$cmdtuples = 1;
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


$columns = 'Value,String'; //
$stringSQL_Agency = "SELECT  sys_pre_values.Value,sys_localization_strings.String FROM sys_pre_values,sys_localization_keys,sys_localization_strings WHERE sys_localization_keys.Key = sys_pre_values.LKey and sys_localization_keys.ID = sys_localization_strings.IDKey and sys_pre_values.Key = 'AdoptionAgency' Order By sys_pre_values.LKey ";
$query = mysql_query($stringSQL_Agency);
$cmdtuples = mysql_num_rows($query);
$arrColumns = explode(",", $columns);
$arrRows_agency = array();

while (($row = mysql_fetch_array($query, MYSQL_BOTH)))
{
$arrValues = array();
foreach($arrColumns as $column_name)
{
array_push($arrValues, $row[$column_name]);
}

array_push($arrRows_agency, array(
'id' => $row[0],
'data' => $arrValues,
));
}


if(@mysql_num_rows(mysql_query("SELECT id FROM bx_groups_main WHERE id=".$member['AdoptionAgency']." AND author_id=".$logid))){
$mFlag=false;
}else
{
if(@mysql_num_rows(mysql_query("SELECT GroupId FROM bx_groups_moderation WHERE GroupId =".$member['AdoptionAgency']."  AND ApproveStatus= 'on' AND Type = 'editedprofiles'"))){
$mFlag=true;
 }
else {
$mFlag=false;
}
}


$tablename = 'Profiles_draft';
$columns = 'State';
$stringSQL_State = "SELECT  " . $columns . " FROM " . $tablename . " WHERE State != '' GROUP BY State ";
$query = mysql_query($stringSQL_State);
$cmdtuples = mysql_num_rows($query);
$arrColumns = explode(",", $columns);
$arrRows_State = array();

while (($row = mysql_fetch_array($query, MYSQL_BOTH)))
{
$arrValues = array();
foreach($arrColumns as $column_name)
{
array_push($arrValues, $row[$column_name]);
}

array_push($arrRows_State, array(
'id' => $row[0],
'data' => $arrValues,
));
}



if(@mysql_num_rows(mysql_query("SELECT id FROM bx_groups_main WHERE id=".$Agency." AND author_id=".$logid))){
$mFlag=false;
}else
{
if(@mysql_num_rows(mysql_query("SELECT GroupId FROM bx_groups_moderation WHERE GroupId =".$Agency."  AND ApproveStatus= 'on' AND Type = 'editedprofiles'"))){
$mFlag=true;
 }
else {
$mFlag=false;
}
}




//Creating a badge code

$GID = $member['AdoptionAgency'];
$width=100;
$embedd_div_id = date("mdGis").'m'.$member['ID'].'g'.$GID.'c'.substr(md5(uniqid(rand(), true)), 0, 5);
$embedd_validator_code = generateGroupConf($member['ID'],$GID);        

$embedd_code = '<!--Badge Start --><div id="'.$embedd_div_id.'">
    
<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.min.js" ></script><div style="width:350px;text-align:center">
<font style="font-size:10px;font-family: verdana;font-weight: none;"><a href="'.$site['url'].'">'.$site['title'].' '._t("_gkc_bw_Groups").'</a></font>
</div></div><script type="text/javascript" src="'.$site['url'].'load_badge_widget.php?GID='.$GID.'&MID='.$member['ID'].'
&display=groupownersingle&conf='.$embedd_validator_code.'&width='.$width.'" onload="document.getElementById(\''.$embedd_div_id.'\').innerHTML=\'\';"></script>
<!-- Badge End -->';

function generateGroupConf($MID=0,$GID=0)
{
    $out = ($MID+7*($GID))*($MID)+9186;
    return $out;
}




if ($cmdtuples > 0)
{
echo json_encode(array(
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
'Profiles_State' => array(
'rows' => $arrRows_State
),
'agency_flag' => array(
'rows' => $mFlag
),
'sql_statement' => $stringSQL,
));
}
  else
{
echo json_encode(array(
'status' => 'err', 
'response' => 'Could not read the data: ' 
    
));
}   



?>

