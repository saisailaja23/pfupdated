<?php
/**************************************************************************************************

*     Name                :  Prashanth A S
*     Date                :  17/12/2013
*     Purpose             :  Update teh details of the agency.

***************************************************************************************************/
require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');
require_once ('../../inc/utils.inc.php');
require_once ('../../inc/db.inc.php');

$sSalt = genRndSalt();


// Getting the post values
//$agencyDescription      = strip_tags($_POST['agency_description']); // description about agency
$agencyPhonenumber      = $_POST['agency_phonenumber']; // agency phone number
$agencyEmail            = $_POST['agency_email']; // agency email
$agencyAddress          = $_POST['agency_address']; // agency address
$agencyName             = $_POST['agency_name']; // agency name 
$agencyUrl              = $_POST['agency_url']; // url of agency
$agencyId               = $_POST['agency_ID']; // agency id
$agency_desc              = addslashes($_POST['agency_description']); 
//$agency_title             = $_POST['agency_title']; 
$city              = $_POST['city'];
$zip               = $_POST['zip'];
$state             = $_POST['state'];
$region            = $_POST['region'];
$unpubPwd = $_POST['Unpublish_Password'];
//addslashes($str);

$agency_details = getProfileInfo($agencyId);

// Updating the values  in profiles table
  $agency_Profile_query       = "UPDATE   `Profiles` 
                                  SET      `Email` = '" . $agencyEmail . "',
                                   `WEB_URL` = '" . $agencyUrl . "',
                                     `CONTACT_NUMBER` = '" . $agencyPhonenumber . "',
                                  `street_address` = '" . $agencyAddress . "' ,
                                      `City` = '" . $city . "',
                                          `State` = '" . $state . "',
                                          `Region` = '" . $region . "',    
                                          `zip` = '" . $zip . "'
                                WHERE           ID = '" . $agencyId . "'";
mysql_query($agency_Profile_query);





// Updating the values  in profiles_draft table
$agency_Profile_draft_query = "   UPDATE   `Profiles_draft` 
                                     SET   `Email` = '" . $agencyEmail . "',
                                    
                                     `CONTACT_NUMBER` = '" . $agencyPhonenumber . "',
                                  `street_address` = '" . $agencyAddress . "', 
                                   `City` = '" . $city . "',
                                    `State` = '" . $state . "',
                                    `Region` = '" . $region . "',     
                                    `zip` = '" . $zip . "'
                                   WHERE        ID = '" . $agencyId . "'";
mysql_query($agency_Profile_draft_query);



$agency_desc_update      = "UPDATE   `bx_groups_main` 
                                  SET      `desc` = '" . $agency_desc . "',`title` = '" . $agencyName . "',`City` = '" . $city . "',
                                          `zip` = '" . $zip . "', `Unpublish_Password` = '" . $unpubPwd . "'
                                WHERE           ID = '" . $agency_details['AdoptionAgency'] . "'";
mysql_query($agency_desc_update);


//echo $agency_Profile_query;echo $agency_Profile_draft_query;exit();
$update = 1;
	$profile_sql = "SELECT ZOHO_ID,NickName FROM `Profiles` where ID = ".$agencyId."  limit 0 , 1";
	$profile_sql_res = mysql_query($profile_sql);
	$profile_sql_array = mysql_fetch_row($profile_sql_res);
	$zohoid = $profile_sql_array[0];
	
	if($zohoid != 0  && $zohoid != ''){ 
					$xml = '<Accounts>
				<row no="1">
				<FL val="Account Name">'.str_replace('&','' ,$agencyName).'</FL>
				<FL val="Billing State">'.$state.'</FL>
				<FL val="Billing Street">'.$region.'</FL>
				<FL val="Description"><![CDATA['. str_replace('&nbsp;',' ' ,strip_tags($agency_desc)).']]></FL>
				<FL val="ZipCode">'.$zip.'</FL>
				<FL val="Billing City">'.$city.'</FL>
				<FL val="Website"> '.$agencyUrl.'</FL>
				<FL val="Phone">'.$agencyPhonenumber.'</FL>
				</row>
				</Accounts>';
		$curl_url = $zoho_api['account_update'];
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
		
			
	}else{
	
	
		$xml = '<Accounts>
				<row no="1">
				<FL val="Account Name">'.str_replace('&','' ,$agencyName).'</FL>
				<FL val="Agency Contact">'.$profile_sql_array[1].'</FL>
				<FL val="CAIRS Products Used">ParentFinder</FL>
				<FL val="Billing State">'.$state.'</FL>
				<FL val="Billing Street">'.$region.'</FL>
				<FL val="Description"><![CDATA['. str_replace('&nbsp;',' ' ,strip_tags($agency_desc)).']]></FL>
				<FL val="ZipCode">'.$zip.'</FL>
				<FL val="Billing City">'.$city.'</FL>
				<FL val="Website"> '.$agencyUrl.'</FL>
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
		$result = curl_exec($ch);
		curl_close($ch);	
		$p = xml_parser_create();
		xml_parse_into_struct($p, $result, $vals, $index);
		xml_parser_free($p);
		 $sql	= "update Profiles set  ZOHO_ID ='".$vals[4]['value']."'  where ID ='".$agencyId."'";
		$result	=mysql_query($sql);	
		
		
	}
	

		
		
if($update > 0)
{   
$user_redirect              = $site['url']."extra_agency_view_28.php";    
echo json_encode(array(
'status' => 'success',
'response' => 'Response',
'user_redirection' => $user_redirect
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