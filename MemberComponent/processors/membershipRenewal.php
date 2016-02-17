<?php
require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');
require_once ('../../inc/utils.inc.php');
require_once ('../../inc/db.inc.php');
require_once ('../../modules/boonex/payment/classes/BxPmtDb.php');


$user_id =  $_POST['userid'];
$mem_type =  $_POST['memtype'];
$privider=isset($_POST['provider'])?$_POST['provider']:null;
$amount=isset($_POST['amount'])?$_POST['provider']:null;



//Aravind:
//Usering  Boonex Payment Module 
//We need to insert the transation in two tables 
//bx_pmt_transactions and bx_pmt_pendingtransactions
if(isset($privider)){
		$obj=new BxPmtDb();
		$obj->_sPrefix="bx_pmt_";

		$aCartInfo=array(
			'vendor_id'=>0,//by default
			'module_id'=>19,
			'id'=>$mem_type,
			'quantity'=>1
			);
		$pendingId=$obj->insertPending($user_id,$privider,$aCartInfo);


		$aInfo = array(
			'pending_id' =>$pendingId,
			'order_id'=>strtoupper(substr(md5(rand(1,1000)),0,16)),
			'client_id'=>$user_id,
			'module_id'=>19,
			'item_id'=>$mem_type,
			'item_count'=>1,
			'amount'=>$amount
			);
		$obj->insertTransaction($aInfo);
}

$sql		="SELECT * FROM aqb_membership_vouchers_codes WHERE Code ='".$_POST['vCode']."'";
$result		=mysql_query($sql);
$field		=mysql_fetch_array($result);

$usedCount = $field['Used'] + 1;

$sql	= "update aqb_membership_vouchers_codes set Used = ".$usedCount." where Code ='".$_POST['vCode']."'";
$result	=mysql_query($sql);

//updating membership type
//changing basic membership details
$exist_basic_member=0;
if($mem_type == 25){
	//Users cannot re-apply to a basic membership 

	$membership=mysql_query("SELECT IDMember FROM `sys_acl_levels_members` WHERE IDMember=".$user_id."
                            AND IDLevel=25 ");
	if(mysql_num_rows($membership)>0){
		$exist_basic_member=1;
	}
	$membership_history=mysql_query("SELECT IDMember FROM `sys_acl_levels_members_history` WHERE IDMember=".$user_id."
                            AND IDLevel=25 ");
	if(mysql_num_rows($membership_history)>0){
		$exist_basic_member=1;
	}

		
	
}

if($mem_type==25)
	$iDays = 30;

if($exist_basic_member!=1)
	setMembership($user_id, $mem_type, $iDays , $bStartsNow = false, $sTransactionId = '', $isSendMail = true);
else 
{echo 'exist';exit;}
if($mem_type == 24){
	$profileyear =  date("y");

	$sql_query   = "select MAX(Profile_no) as profilecount from  Profiles";
	$sql_relsult = mysql_query($sql_query);
	$sql_array = mysql_fetch_row($sql_relsult);
	if($sql_array[0] < 1000  ){
		$profilecount =  1000;
	}else{
		$profilecount = $sql_array[0]+1;
	}
	$sql	= "update Profiles set Profile_no = '".$profilecount."', Profile_year ='".$profileyear."'  where ID ='".$user_id."'";
	$result	=mysql_query($sql);
	$parent2_fn ='';
	$parent2_ln ='';
	$profile_sql = "SELECT FirstName,LastName,address1,address2,city,State,zip,Country,Profile_no,Profile_year,NickName,Email,phonenumber,ZOHO_ID,`Couple`,ID,AdoptionAgency FROM `Profiles` where ID = ".$user_id."  limit 0 , 1";
	$profile_sql_res = mysql_query($profile_sql);
	$profile_sql_array = mysql_fetch_row($profile_sql_res);
	if($profile_sql_array[14] != 0 ){
		$cprofile_sql = "SELECT FirstName,LastName FROM `Profiles` where ID = ".$profile_sql_array[14]."  limit 0 , 1";
		$cprofile_sql_res = mysql_query($cprofile_sql);
		$cprofile_sql_array = mysql_fetch_row($cprofile_sql_res);
		$parent2_fn = $cprofile_sql_array[0];
		$parent2_ln = $cprofile_sql_array[1];
	}
	$parent1_fn =$profile_sql_array[0];
	$parent1_ln =$profile_sql_array[1];	
	$add1 = $profile_sql_array[2];
	$add2 = $profile_sql_array[3];
	$city = $profile_sql_array[4];
	$state = $profile_sql_array[5];
	$zip = $profile_sql_array[6];
	$country =$profile_sql_array[7];
	$production_no = $profile_sql_array[9].'_'.$profile_sql_array[8];
	$username = $profile_sql_array[10];
	$email = $profile_sql_array[11];
	$phone_no = $profile_sql_array[12];
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
		$agency_name =str_replace('&','' , $agencyrofile_sql_array[0]);
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
		if($vals[7]['value'] != '' && $vals[7]['value'] != NULL){	
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
		xml_parser_free($p);
		$sql	= "update Profiles set  ZOHO_ID ='".$valsadd[4]['value']."'  where ID ='".$agencyrofile_sql_array[1]."'";
		$result	=mysql_query($sql);	
		}
		
}


$url = $zoho_api['contact_insert'];
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
$param= "authtoken=".$zoho_api['api']."&scope=crmapi&newFormat=1&xmlData=".$xml;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
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
	
	$sql	= "update Profiles set  ZOHO_ID ='".$vals[4]['value']."'  where ID ='".$user_id."'";
	$result	=mysql_query($sql);	
}
?>
