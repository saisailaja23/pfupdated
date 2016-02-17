<?php
 
/*********************************************************************************
 * Name:    Prashanth A
 * Date:    02/11/2013
 * Purpose: Populating the values in family profile builder
 *********************************************************************************/

require_once('../../inc/header.inc.php');
require_once('../../inc/profiles.inc.php');
require_once('../../inc/design.inc.php');
require_once('../../inc/classes/BxDolEmailTemplates.php');

$user_id = ($_GET['id']!='undefined')?$_GET['id']:0;
$q= "select Email,
            Age,
            waiting,
            noofchildren,
            faith,
            housestyle,
            noofbedrooms,
            noofbathrooms,
            yardsize,
            neighbourhoodlike,
            Adoptiontype,
            Specialneeds,
            phonenumber,
            Street_Address,
            ChildEthnicity,
            ChildAge,           
            DescriptionMe,
            agency_letter,
            letter_aboutThem,
            others,
            DearBirthParent,
            FirstName,
            AdoptionAgency,
            State,
            WEB_URL,
            childrenType,
            Region
            from Profiles_draft
            where ID =$user_id";
$result = mysql_query($q);
$row = mysql_fetch_array($result);
  $Profile_query= "Update `Profiles` SET `Email` = '" . $row[0] . "',
    `Age` = '" . mysql_real_escape_string($row[1]) . "',
    `waiting` = '" . mysql_real_escape_string($row[2]) . "',
    `noofchildren` = '" . mysql_real_escape_string($row[3]) . "',
    `faith` = '" . mysql_real_escape_string($row[4]) . "',
    `housestyle` = '" . mysql_real_escape_string($row[5]) . "',
    `noofbedrooms` = '" . mysql_real_escape_string($row[6]) . "',
    `noofbathrooms` = '" . mysql_real_escape_string($row[7]) . "',
    `yardsize` = '" . mysql_real_escape_string($row[8]) . "',
    `neighbourhoodlike` = '" . mysql_real_escape_string($row[9]) . "',
    `Adoptiontype` = '" . mysql_real_escape_string($row[10]) . "',
    `Specialneeds` = '" . mysql_real_escape_string($row[11]) . "',
    `phonenumber` = '" . mysql_real_escape_string($row[12]) . "',
    `Street_Address` = '" . mysql_real_escape_string($row[13]) . "',
    `ChildEthnicity` = '" . mysql_real_escape_string($row[14]) . "',
    `ChildAge` = '" . mysql_real_escape_string($row[15]) . "', 
    `DescriptionMe` = '" . mysql_real_escape_string($row[16]) . "',
     `agency_letter` = '" . mysql_real_escape_string($row[17]) . "',
    `letter_aboutThem` = '" . mysql_real_escape_string($row[18]) . "', 
    `others` = '" . mysql_real_escape_string($row[19]) . "',
    `DearBirthParent` = '" . mysql_real_escape_string($row[20]) . "', 
    `FirstName` = '" . mysql_real_escape_string($row[21]) . "', `AdoptionAgency` = '" . mysql_real_escape_string($row[22]) . "', `State` = '" . mysql_real_escape_string($row[23]) . "', `WEB_URL` = '" . mysql_real_escape_string($row[24]) . "', 
    `childrenType` = '" . mysql_real_escape_string($row[25]) . "',`Region` = '" . mysql_real_escape_string($row[26]) . "'
     where ID = '" . $user_id . "'";

mysql_query($Profile_query);

$Profile_draft_query= "Update `Profiles_draft` SET
    `Status` = 'Active'
     where ID = '" . $user_id . "'";

 mysql_query($Profile_draft_query);


$_profile=getProfileInfo($user_id);
$q= "select FirstName,DescriptionMe,Age from Profiles_draft where Couple = '" . $_profile['ID'] . "'";
$result = mysql_query($q);
$row = mysql_fetch_array($result);
$Profile_query_couple= "Update `Profiles` SET `FirstName` = '" . mysql_real_escape_string($row[0]) . "',
    `DescriptionMe` = '" . mysql_real_escape_string($row[1]) . "',
    `age` = '" . mysql_real_escape_string($row[2]). "' where Couple = '" . $_profile['ID'] . "'";
mysql_query($Profile_query_couple);

$oEmailTemplate = new BxDolEmailTemplates();
$aMail = $oEmailTemplate->parseTemplate('t_Activation', array(), $iId);
sendMail($row[27] , $aMail['subject'], $aMail['body']);



//to approve video
$ProfileVideo_query= "Update `RayVideoFiles` SET `Status` = 'approved'
     where `Owner` = $user_id";
mysql_query($ProfileVideo_query);

//to approve photo
$ProfilePhoto_query= "Update `bx_photos_main` SET `Status` = 'approved'
     where `Owner` = $user_id";
mysql_query($ProfilePhoto_query);


echo json_encode(array(
'status' => 'success'));
?>


