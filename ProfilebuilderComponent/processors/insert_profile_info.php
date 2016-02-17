<?php
/**************************************************************************************************

*     Name                :  Prashanth A
*     Date                :  Mon Nov 5 2013
*     Purpose             :  Inserting values to the database  and assiging membership to users.

***************************************************************************************************/
require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');
require_once ('../../inc/utils.inc.php');
require_once ('../../inc/db.inc.php');

//$sSalt = genRndSalt();

// Getting the post values
$EmailID = $_POST['profileemail'];
$FirstName = $_POST['profilename'];
$State = $_POST['profilestate'];
$PFirstName  = trim($_POST['profilecouplename']);
$Agency  = $_POST['profileagency'];
$Pid = $_POST['profileID'];
//$newPassword = $_POST['newpassword'];
//if($newPassword!='')
//$Password = encryptUserPwd($newPassword, $sSalt);
$profilectype= ($_POST['noofchildren'] > 0)?$_POST['ChildType']:"Not Specified";

$profileage= $_POST['profileage']; //age
$profilecoupleage = $_POST['profilecoupleage'];//age
$profilewaiting = $_POST['profilewaiting'];//waiting
$noofchildren = $_POST['noofchildren'];// 	noofchildren
$profilefaith= $_POST['profilefaith'];// 	faith
$profilehousestyle= $_POST['profilehousestyle'];//housestyle
$profilebedrooms= ($_POST['profilebedrooms'] == '5 ')?'5+':$_POST['profilebedrooms'];//noofbedrooms
$profilebathrooms= ($_POST['profilebathrooms'] == '5 ')?'5+':$_POST['profilebathrooms'];//noofbathrooms
$profileyard = $_POST['profileyard'];// 	yardsize
$profileneighbourhood= $_POST['profileneighbourhood'];// 	neighbourhoodlike
$profileadoptiontype = $_POST['profileadoptiontype'];//Adoptiontype
$pspecialneeds= $_POST['pspecialneeds'];//Specialneeds
//$pspecialneeds_no = $_POST['pspecialneeds_no'];
$profilephone = str_replace('-','',trim($_POST['profilephone']));//phonenumber
//$parofileaddress= trim($_POST['parofileaddress']);// 	Street_Address
$ethnicty_preference= trim($_POST['ethnicty_preference']);//ChildEthnicity
$age_preference= trim($_POST['age_preference']);//ChildAge
//$excepting_birthParent= addslashes(trim($_POST['dear_birthParent']));//dear_birthParent
//
//$about_him= addslashes(trim($_POST['about_him']));//DescriptionMe
//$about_her= addslashes(trim($_POST['about_her']));//DescriptionMe 2end person
//$agency_letter=trim($_POST['agency_letter']);
//$about_them=trim($_POST['about_them']);
//$other_letter=trim($_POST['other']);
$website = remove_http(trim($_POST['weburls']));
$address1 = trim($_POST['address1']);
$address2 = trim($_POST['address2']);
$city = trim($_POST['city']);
$zip = trim($_POST['zip']);
$show_contact = trim($_POST['show_contact']);
$parofileaddress = $address1.','.$address2.','.$city.'-'.$zip;
$profile_specialneedoption = trim($_POST['profile_specialneedoption']);
$profilenumber = trim($_POST['profilenumber']);
// Password encryption
//$sSalt = genRndSalt();
//$passen = encryptUserPwd($Password, $sSalt);


//$profilevalue = getProfileInfo($Pid);

$childdesired = trim($_POST['childdesired']);//ChildAge
$childgender= trim($_POST['childgender']);
$birthfatherstatus= trim($_POST['birthfatherstatus']);
$genderfirst = trim($_POST['genderfirst']);
$gendersec = trim($_POST['gendersec']);
$ethnicityfirst = trim($_POST['ethnicityfirst']);
$ethnicitysec = trim($_POST['ethnicitysec']);
$educationfirst = trim($_POST['educationirst']);
$educationsec = trim($_POST['educationsec']);
$religionfirst = trim($_POST['religionfirst']);
$religionsec = trim($_POST['religionsec']);
$occupationfirst = trim($_POST['occupationfirst']);
$occupationsec = trim($_POST['occupationsec']);
$pets = trim($_POST['pets']);
$relationship_Status = trim($_POST['relationship_Status']);
$family_structure = trim($_POST['family_structure']);

$profile_region = trim($_POST['profile_region']);
$profile_country = trim($_POST['profile_country']);

$today = date("Y-m-d");
$diff = abs(strtotime($today) - strtotime($profileage));
$singleyears = floor($diff / (365 * 60 * 60 * 24));

if ($profilecoupleage != '') {
	$diff = abs(strtotime($today) - strtotime($profilecoupleage));
	$coupleyears = floor($diff / (365 * 60 * 60 * 24));
}

if(@mysql_num_rows(mysql_query("SELECT id FROM bx_groups_main WHERE id=".$Agency." AND author_id=".$Pid))){
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

if ($mFlag) {
 $Profile_query_approved = "Update `Profiles` SET `Email` = '" . $EmailID . "',
    `Age` = '" . $singleyears . "',
    `DateOfBirth` = '" . $profileage . "',
    `waiting` = '" . $profilewaiting . "',
    `noofchildren` = '" . $noofchildren . "',
    `faith` = '" . $profilefaith . "',
    `housestyle` = '" . $profilehousestyle . "',
    `noofbedrooms` = '" . $profilebedrooms . "',
    `noofbathrooms` = '" . $profilebathrooms . "',
    `yardsize` = '" . $profileyard . "',
    `neighbourhoodlike` = '" . $profileneighbourhood . "',
    `Adoptiontype` = '" . $profileadoptiontype . "',
    `Specialneeds` = '" . $pspecialneeds . "',
    `phonenumber` = '" . $profilephone . "',
    `Street_Address` = '" . $parofileaddress . "',
    `ChildEthnicity` = '" . $ethnicty_preference . "',
    `ChildAge` = '" . $age_preference . "',

    `childrenType` = '" . $profilectype . "',
    `address1` = '" . $address1 . "',
    `address2` = '" . $address2 . "',
    `city` = '" . $city . "',
    `zip` = '" . $zip . "',
	`show_contact` = $show_contact,
    SpecialNeedsOptions = '" . $profile_specialneedoption . "',
    `FirstName` = '" . $FirstName . "', `AdoptionAgency` = '" . $Agency . "', `State` = '" . $State . "', `WEB_URL` = '" . $website . "' 
     ,`ChildDesired` = '" . $childdesired . "',`BirthFatherStatus` = '" . $birthfatherstatus . "',`ChildGender` = '" . $childgender . "'
     ,`Sex` = '" . $genderfirst . "',`Ethnicity` = '" . $ethnicityfirst . "',`Education` = '" . $educationfirst . "' ,`Religion` = '" . $religionfirst . "'
     ,`Occupation` = '" . $occupationfirst . "' ,`Pet` = '" . $pets . "' ,`RelationshipStatus` = '" . $relationship_Status . "'
     ,`FamilyStructure` = '" . $family_structure . "' ,`Region` = '" . $profile_region . "',`Country` = '" . $profile_country . "'    
     where ID = '" . $Pid . "'";

    $_profile=getProfileInfo($Pid);
 
    $Profile_query_approved_couple= "Update `Profiles` SET `FirstName` = '" . $PFirstName . "',`DateOfBirth` = '" . $profilecoupleage . "',
    `Age` = '" . $coupleyears . "',`Sex` = '" . $gendersec . "',`Ethnicity` = '" . $ethnicitysec . "',`Education` = '" . $educationsec . "',
     `Religion` = '" . $religionsec . "' ,`Region` = '" . $profile_region . "',`Country` = '" . $profile_country . "', `State` = '" . $State . "' 
    ,`Occupation` = '" . $occupationsec . "' where Couple = '" . $_profile['ID'] . "'";
 
 $Profile_draft_query= "Update `Profiles_draft` SET `Email` = '" . $EmailID . "',
    `Age` = '" . $singleyears . "',
    `DateOfBirth` = '" . $profileage . "',
    `waiting` = '" . $profilewaiting . "',
    `noofchildren` = '" . $noofchildren . "',
    `faith` = '" . $profilefaith . "',
    `housestyle` = '" . $profilehousestyle . "',
    `noofbedrooms` = '" . $profilebedrooms . "',
    `noofbathrooms` = '" . $profilebathrooms . "',
    `yardsize` = '" . $profileyard . "',
    `neighbourhoodlike` = '" . $profileneighbourhood . "',
    `Adoptiontype` = '" . $profileadoptiontype . "',
    `Specialneeds` = '" . $pspecialneeds . "',
    `phonenumber` = '" . $profilephone . "',
    `Street_Address` = '" . $parofileaddress . "',
    `ChildEthnicity` = '" . $ethnicty_preference . "',
    `ChildAge` = '" . $age_preference . "',
    SpecialNeedsOptions = '" . $profile_specialneedoption . "',

    `childrenType` = '" . $profilectype . "',
    `address1` = '" . $address1 . "',
    `address2` = '" . $address2 . "',
    `city` = '" . $city . "',
    `zip` = '" . $zip . "',	
	`show_contact` = $show_contact,
    `FirstName` = '" . $FirstName . "', `AdoptionAgency` = '" . $Agency . "', `State` = '" . $State . "', `WEB_URL` = '" . $website . "' 
     ,`ChildDesired` = '" . $childdesired . "',`BirthFatherStatus` = '" . $birthfatherstatus . "',`ChildGender` = '" . $childgender . "'
     ,`Sex` = '" . $genderfirst . "' ,`Ethnicity` = '" . $ethnicityfirst . "',`Education` = '" . $educationfirst . "',`Religion` = '" . $religionfirst . "'
     ,`Occupation` = '" . $occupationfirst . "',`Pet` = '" . $pets . "',`RelationshipStatus` = '" . $relationship_Status . "' 
     ,`FamilyStructure` = '" . $family_structure . "' ,`Region` = '" . $profile_region . "',`Country` = '" . $profile_country . "'     
     where ID = '" . $Pid . "'";
 
    $_profile=getProfileInfo($Pid);           
 
    $Profile_draft_query_couple= "Update `Profiles_draft` SET `FirstName` = '" . $PFirstName . "',`DateOfBirth` = '" . $profilecoupleage . "',
    `Age` = '" . $coupleyears . "',`Sex` = '" . $gendersec . "',`Ethnicity` = '" . $ethnicitysec . "',`Education` = '" . $educationsec . "' 
     ,`Religion` = '" . $religionsec . "' ,`Occupation` = '" . $occupationsec . "',`Region` = '" . $profile_region . "',
    `Country` = '" . $profile_country . "', `State` = '" . $State . "' where Couple = '" . $_profile['ID'] . "'";
 
}
else {
 $Profile_draft_query= "Update `Profiles_draft` SET `Email` = '" . $EmailID . "',
    `Age` = '" . $singleyears . "',
    `DateOfBirth` = '" .$profileage. "',
    `waiting` = '" . $profilewaiting . "',
    `noofchildren` = '" . $noofchildren . "',
    `faith` = '" . $profilefaith . "',
    `housestyle` = '" . $profilehousestyle . "',
    `noofbedrooms` = '" . $profilebedrooms . "',
    `noofbathrooms` = '" . $profilebathrooms . "',
    `yardsize` = '" . $profileyard . "',
    `neighbourhoodlike` = '" . $profileneighbourhood . "',
    `Adoptiontype` = '" . $profileadoptiontype . "',
    `Specialneeds` = '" . $pspecialneeds . "',
    `phonenumber` = '" . $profilephone . "',
    `Street_Address` = '" . $parofileaddress . "',
    `ChildEthnicity` = '" . $ethnicty_preference . "',
    `ChildAge` = '" . $age_preference . "',
    SpecialNeedsOptions = '" . $profile_specialneedoption . "',

    `childrenType` = '" . $profilectype . "',
    `address1` = '" . $address1 . "',
    `address2` = '" . $address2 . "',
    `city` = '" . $city . "',
    `zip` = '" . $zip . "',
    `FirstName` = '" . $FirstName . "', `AdoptionAgency` = '" . $Agency . "', `State` = '" . $State . "', `WEB_URL` = '" . $website . "' 
     ,`ChildDesired` = '" . $childdesired . "',`BirthFatherStatus` = '" . $birthfatherstatus . "' ,`ChildGender` = '" . $childgender . "'
     ,`Sex` = '" . $genderfirst . "',`Ethnicity` = '" . $ethnicityfirst . "' ,`Education` = '" . $educationfirst . "',`Religion` = '" . $religionfirst . "'
     ,`Occupation` = '" . $occupationfirst . "',`Pet` = '" . $pets . "',`RelationshipStatus` = '" . $relationship_Status . "',
    `FamilyStructure` = '" . $family_structure . "' ,`Region` = '" . $profile_region . "',`Country` = '" . $profile_country . "'       
     where ID = '" . $Pid . "'";
 
    $_profile=getProfileInfo($Pid);           
 
    $Profile_draft_query_couple= "Update `Profiles_draft` SET `FirstName` = '" . $PFirstName . "',`DateOfBirth` = '" . $profilecoupleage . "',
    `Age` = '" . $coupleyears . "',`Sex` = '" . $gendersec . "',`Ethnicity` = '" . $ethnicitysec . "',`Education` = '" . $educationsec . "',
        `Religion` = '" . $religionsec . "' ,`Region` = '" . $profile_region . "'
      , `State` = '" . $State . "' ,`Country` = '" . $profile_country . "' ,`Occupation` = '" . $occupationsec . "'  where Couple = '" . $_profile['ID'] . "'";
 
    $mailSettings = getMailSettings();
    if(!getParam('autoApproval_ifProfile')&& !$mFlag && $mailSettings['EditProfile'] == 0)
  {	
   $rEmailTemplate = new BxDolEmailTemplates();
   $aTemplate = $rEmailTemplate -> getTemplate( 't_profileUpdate' );
   $aAgencyEmail=db_arr("SELECT Profiles.* FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID =".$Pid." AND Profiles.AdoptionAgency=bx_groups_main.id )");
		
   sendMail( $aAgencyEmail['Email'], $aTemplate['Subject'], $aTemplate['Body'], $Pid );
   //Send acknowledge mail to user:   START
   $aTemplate='';
   $aTemplate = $rEmailTemplate -> getTemplate( 't_profileUpdateAcknowledge' ) ;
   sendMail( 'prashanth.adkathbail@mediaus.com', $aTemplate['Subject'], $aTemplate['Body'], $Pid );
   //Send acknowledge mail to user:   END
 
   }

 }

 $mem_id = getMemberMembershipInfo(getLoggedId());
if($mem_id['ID'] == 24){
	$profilenumber_array = explode('_', $profilenumber);
$profilenumber_sql	= "update Profiles set  Profile_year ='".$profilenumber_array[0]."' , Profile_no ='".$profilenumber_array[1]."' where ID ='".$Pid."'";
$result	=mysql_query($profilenumber_sql);	

	$profile_sql = "SELECT FirstName,LastName,address1,address2,city,State,zip,Country,Profile_no,Profile_year,NickName,Email,phonenumber,ZOHO_ID,`Couple`,ID,AdoptionAgency FROM `Profiles` where ID = ".$Pid."  limit 0 , 1";
	$profile_sql_res = mysql_query($profile_sql);
	$profile_sql_array = mysql_fetch_row($profile_sql_res);
	if($profile_sql_array[14] != 0 ){
		$cprofile_sql = "SELECT FirstName,LastName FROM `Profiles` where ID = ".$profile_sql_array[14]."  limit 0 , 1";
		$cprofile_sql_res = mysql_query($cprofile_sql);
		$cprofile_sql_array = mysql_fetch_row($cprofile_sql_res);
		$parent2_fn = $cprofile_sql_array[0];
		$parent2_ln = $cprofile_sql_array[1];
	}
	if($PFirstName != ''){
	$parent2_fn	= $PFirstName;
	}

	$parent1_fn =$FirstName;
	$parent1_ln =$profile_sql_array[1];	
	$add1 = $address1;
	$add2 = $address2;
	$city = $city;
	$state = $State;
	$zip = $zip;
	$country =$profile_country;
	$production_no = $profilenumber_array[0].'_'.$profilenumber_array[1];
	$username = $profile_sql_array[10];
	$email = $EmailID;
	$phone_no = $profilephone;
	$zohoid = $profile_sql_array[13];
$lastname = '';	
if($profile_sql_array[14] != 0 ){
		if(strtolower($parent2_ln)  == strtolower($parent1_ln)){
			$lastname = $parent1_fn . ' and ' .$parent2_fn. ' '.$parent1_ln;	
		}else{
			$lastname = $parent1_fn . ' ' . $parent1_ln . ' and ' .$parent2_fn. ' '.$parent2_ln;	
		}
	}else{
		$lastname = $parent1_fn. ' '.$parent1_ln;		
}

if($profile_sql_array[16] != 0  && $profile_sql_array[16] != ''){

		$agencyrofile_sql = "SELECT  `title`, `author_id`, `desc`, `city`, `zip` FROM `bx_groups_main` where id = ".$profile_sql_array[16]."  limit 0 , 1";
		$agencyrofile_sql_res = mysql_query($agencyrofile_sql);
		$agencyrofile_sql_array = mysql_fetch_row($agencyrofile_sql_res);
		$agency_name = str_replace('&','' ,$agencyrofile_sql_array[0]);
		$agency_desc = $agencyrofile_sql_array[2];
		$acity =  $agencyrofile_sql_array[3];
		$azip =  $agencyrofile_sql_array[4];
		$agency_profiledata = "select  Email,WEB_URL,CONTACT_NUMBER,NickName,State,Region from Profiles where ID =". $agencyrofile_sql_array[1];
		$agency_profiledata_res = mysql_query($agency_profiledata);
		$agency_profiledata_array = mysql_fetch_row($agency_profiledata_res);
		$aUserName = $agency_profiledata_array[3];
		$aState = $agency_profiledata_array[4];
		$aRegion = $agency_profiledata_array[5];
		$agencyPhonenumber = $agency_profiledata_array[2];
		$aagencyUrl = $agency_profiledata_array[1];
		$curl_url = $zoho_api['account_search'];
	    $param= "authtoken=".$zoho_api['api']."&scope=crmapi&criteria=(Account Name:".$agency_name.")";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $curl_url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		$result = curl_exec($ch);

		curl_close($ch);
		$p = xml_parser_create();
		xml_parse_into_struct($p, $result, $vals, $index);
		xml_parser_free($p);

		if($vals[2]['value'] == '4422' ){
			
			$xml = '<Accounts>
			<row no="1">
			<FL val="Account Name">'.$agency_name.'</FL>
			<FL val="Agency Contact">'.$aUserName.'</FL>
			<FL val="CAIRS Products Used">ParentFinder</FL>
			<FL val="Billing State">'.$aState.'</FL>
			<FL val="Billing Street">'.$aRegion.'</FL>
			<FL val="Description"><![CDATA['.str_replace('&nbsp;',' ' ,strip_tags($agency_desc)).']]></FL>
			<FL val="ZipCode">'.$azip.'</FL>
			<FL val="Billing City">'.$acity.'</FL>
			<FL val="Website"> '.$aagencyUrl.'</FL>
			<FL val="Phone">'.$agencyPhonenumber.'</FL>
			</row>
			</Accounts>';
			$curl_url = $zoho_api['account_insert'];
			$param= "authtoken=".$zoho_api['api']."&scope=crmapi&newFormat=1&xmlData=".$xml;
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $curl_url);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
			$result_add = curl_exec($ch);
			curl_close($ch);
				$p = xml_parser_create();
			xml_parse_into_struct($p, $result_add, $valsadd, $index);

			$sql	= "update Profiles set  ZOHO_ID ='".$valsadd[4]['value']."'  where ID ='".$agencyrofile_sql_array[1]."'";
			$result	=mysql_query($sql);	
			
		}
		
}
	
if($zohoid != '' &&  $zohoid != 0){	


		 $xml = '<Contacts>
							<row no="1">
						<FL val="Last Name">'.$lastname.'</FL>
						<FL val="Account Name">'.$agency_name.'</FL>
						<FL val="Parent 1 First Name">'.$parent1_fn.'</FL>
						<FL val="Parent 1 Last Name">'.$parent1_ln.'</FL>
						<FL val="Parent 2 First Name">'.$parent2_fn.'</FL>
						<FL val="Parent 2 last name">'.$parent2_ln.'</FL>
						<FL val="Print Production Number">'.$production_no.'</FL>
						<FL val="PF User Name">'.$username.'</FL>
						<FL val="Phone">'.$phone_no.'</FL>
						<FL val="Email">'.$email.'</FL>
						<FL val="Mailing Street">'.$add1.' '.$add2.'</FL>
						<FL val="Mailing City">'.$city.'</FL>
						<FL val="Mailing State">'.$state.'</FL>
						<FL val="Mailing Zip">'.$zip.'</FL>
						<FL val="Mailing Country">'.$country.'</FL>
			</row>
				</Contacts>';
		$curl_url = $zoho_api['contact_update'];
		$param= "authtoken=".$zoho_api['api']."&scope=crmapi&newFormat=1&id=".$zohoid."&xmlData=".$xml; 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $curl_url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		$result = curl_exec($ch);

		curl_close($ch);	
		
		
} else {

	 $xml = '<Contacts>
			<row no="1">
						<FL val="Last Name">'.$lastname.'</FL>
						<FL val="Account Name">'.$agency_name.'</FL>
						<FL val="Parent 1 First Name">'.$parent1_fn.'</FL>
						<FL val="Parent 1 Last Name">'.$parent1_ln.'</FL>
						<FL val="Parent 2 First Name">'.$parent2_fn.'</FL>
						<FL val="Parent 2 last name">'.$parent2_ln.'</FL>
						<FL val="Print Production Number">'.$production_no.'</FL>
						<FL val="PF User Name">'.$username.'</FL>
						<FL val="Phone">'.$phone_no.'</FL>
						<FL val="Email">'.$email.'</FL>
						<FL val="Mailing Street">'.$add1.' '.$add2.'</FL>
						<FL val="Mailing City">'.$city.'</FL>
						<FL val="Mailing State">'.$state.'</FL>
						<FL val="Mailing Zip">'.$zip.'</FL>
						<FL val="Mailing Country">'.$country.'</FL>
			</row>
			</Contacts>';
	$curl_url = $zoho_api['contact_insert'];
	$param= "authtoken=".$zoho_api['api']."&scope=crmapi&newFormat=1&xmlData=".$xml;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $curl_url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	$result = curl_exec($ch);

	curl_close($ch);	
	$p = xml_parser_create();
	xml_parse_into_struct($p, $result, $vals, $index);
	xml_parser_free($p);
	// print_r($vals[4]['value']);
	
	$sql	= "update Profiles set  ZOHO_ID ='".$vals[4]['value']."'  where ID ='".$Pid."'";
	$result	=mysql_query($sql);
}

}

// $Profile_draft_query= "Update `Profiles_draft` SET `Email` = '" . $EmailID . "',
//    `Age` = '" . $profileage . "',
//    `waiting` = '" . $profilewaiting . "',
//    `noofchildren` = '" . $noofchildren . "',
//    `faith` = '" . $profilefaith . "',
//    `housestyle` = '" . $profilehousestyle . "',
//    `noofbedrooms` = '" . $profilebedrooms . "',
//    `noofbathrooms` = '" . $profilebathrooms . "',
//    `yardsize` = '" . $profileyard . "',
//    `neighbourhoodlike` = '" . $profileneighbourhood . "',
//    `Adoptiontype` = '" . $profileadoptiontype . "',
//    `Specialneeds` = '" . $pspecialneeds . "',
//    `phonenumber` = '" . $profilephone . "',
//    `Street_Address` = '" . $parofileaddress . "',
//    `ChildEthnicity` = '" . $ethnicty_preference . "',
//    `ChildAge` = '" . $age_preference . "',
//    `Salt` = '" . $sSalt . "',
//    `Status` = 'Approval',
//    `childrenType` = '" . $profilectype . "',
//    `address1` = '" . $address1 . "',
//    `address2` = '" . $address2 . "',
//    `city` = '" . $city . "',
//    `zip` = '" . $zip . "',
//    `FirstName` = '" . $FirstName . "', `AdoptionAgency` = '" . $Agency . "', `State` = '" . $State . "', `WEB_URL` = '" . $website . "' where ID = '" . $Pid . "'";

 mysql_query($Profile_query_approved);
 mysql_query($Profile_query_approved_couple);
 mysql_query($Profile_draft_query);
 mysql_query($Profile_draft_query_couple);
//     $_profile=getProfileInfo($Pid);



// $Profile_draft_query_couple= "Update `Profiles_draft` SET `FirstName` = '" . $PFirstName . "',
//    `DescriptionMe` = '" . $about_her . "',
//    `Age` = '" . $profilecoupleage . "' where Couple = '" . $_profile['ID'] . "'";



$update = 1;
if($update > 0)
{   
$user_redirect = $site['url']."our-family-dashboard.php";    
echo json_encode(array(
'status' => 'success',
'response' => 'Response',
'user_redirection' => $user_redirect,
    'query'=>$Profile_draft_query
));
}
else
{
echo json_encode(array(
'status' => 'err', 
'response' => 'Could not read the data: ' . mssql_get_last_message()
));
}

// START - Added by prashanth tp remove htttp or https from a url 

 function remove_http($url) {
   $disallowed = array('http://', 'https://');
   foreach($disallowed as $d) {
      if(strpos($url, $d) === 0) {
         return str_replace($d, '', $url);
      }
   }
   return $url;
}

//END - Added by prashanth tp remove htttp or https from a url

?>

