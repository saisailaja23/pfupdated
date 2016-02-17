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
$parofileaddress = $address1.','.$address2.','.$city.'-'.$zip;
$profile_specialneedoption = trim($_POST['profile_specialneedoption']);
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
    SpecialNeedsOptions = '" . $profile_specialneedoption . "',
    `FirstName` = '" . $FirstName . "', `AdoptionAgency` = '" . $Agency . "', `State` = '" . $State . "', `WEB_URL` = '" . $website . "' 
     ,`ChildDesired` = '" . $childdesired . "',`BirthFatherStatus` = '" . $birthfatherstatus . "',`ChildGender` = '" . $childgender . "'
     ,`Sex` = '" . $genderfirst . "',`Ethnicity` = '" . $ethnicityfirst . "',`Education` = '" . $educationfirst . "' ,`Religion` = '" . $religionfirst . "'
     ,`Occupation` = '" . $occupationfirst . "' ,`Pet` = '" . $pets . "' ,`RelationshipStatus` = '" . $relationship_Status . "'
     ,`FamilyStructure` = '" . $family_structure . "' ,`Region` = '" . $profile_region . "',`Country` = '" . $profile_country . "'    
     where ID = '" . $Pid . "'";

    $_profile=getProfileInfo($Pid);
 
    $Profile_query_approved_couple= "Update `Profiles` SET `FirstName` = '" . $PFirstName . "',`DateOfBirth` = '" . $profilecoupleage . "',
    `Age` = '" . $coupleyears ."',`Sex` = '" . $gendersec . "',`Ethnicity` = '" . $ethnicitysec . "',`Education` = '" . $educationsec . "',
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
    `FirstName` = '" . $FirstName . "', `AdoptionAgency` = '" . $Agency . "', `State` = '" . $State . "', `WEB_URL` = '" . $website . "' 
     ,`ChildDesired` = '" . $childdesired . "',`BirthFatherStatus` = '" . $birthfatherstatus . "' ,`ChildGender` = '" . $childgender . "'
     ,`Sex` = '" . $genderfirst . "',`Ethnicity` = '" . $ethnicityfirst . "' ,`Education` = '" . $educationfirst . "',`Religion` = '" . $religionfirst . "'
     ,`Occupation` = '" . $occupationfirst . "',`Pet` = '" . $pets . "',`RelationshipStatus` = '" . $relationship_Status . "',
    `FamilyStructure` = '" . $family_structure . "' ,`Region` = '" . $profile_region . "',`Country` = '" . $profile_country . "'       
     where ID = '" . $Pid . "'";
 
    $_profile=getProfileInfo($Pid);           
 
    $Profile_draft_query_couple= "Update `Profiles_draft` SET `FirstName` = '" . $PFirstName . "', `DateOfBirth` = '" . $profilecoupleage . "',
    `Age` = '" . $coupleyears . "',`Sex` = '" . $gendersec . "',`Ethnicity` = '" . $ethnicitysec . "',`Education` = '" . $educationsec . "',
        `Religion` = '" . $religionsec . "' ,`Region` = '" . $profile_region . "'
      , `State` = '" . $State . "' ,`Country` = '" . $profile_country . "' ,`Occupation` = '" . $occupationsec . "'  where Couple = '" . $_profile['ID'] . "'";
 
 
  if(!getParam('autoApproval_ifProfile')&& !$mFlag )
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

