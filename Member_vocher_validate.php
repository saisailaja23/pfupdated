<?php
require_once('inc/header.inc.php');
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );

class MemberComponent{
  public $con="";
  public $priceID="";
  public function connect_Db(){
    $this->con = mysqli_connect(DATABASE_HOST,DATABASE_USER,DATABASE_PASS,DATABASE_NAME) or die("Failed to connect to MySQL: " . mysqli_connect_error());
    if (mysqli_connect_errno()){
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
  }
  public function validateVocherCode(){
    $vCode=$_POST['vcode'];
    $memType=$_POST['memberType'];
    $price=$_POST['price'];
      $sql="SELECT * FROM `sys_acl_level_prices` AS `lp`,`aqb_membership_vouchers_codes` AS `mvc` WHERE `lp`.`id`= `mvc`.`PriceID` AND `lp`.`IDLevel` = 23 AND `mvc`.`Code`='$vCode'";
    if($memType =="featured"){
      $sql="SELECT * FROM `sys_acl_level_prices` AS `lp`,`aqb_membership_vouchers_codes` AS `mvc` WHERE `lp`.`id`= `mvc`.`PriceID` AND `lp`.`IDLevel` = 24 AND `mvc`.`Code`='$vCode'";
    }
    //$sql="SELECT * FROM `aqb_membership_vouchers_transactions` WHERE `Code` = 'AT3F1D8046'";
    $result=mysqli_query($this->con, $sql);
    $fieldcount=mysqli_affected_rows($this->con);
    if($fieldcount){
      $row=mysqli_fetch_assoc($result);
      $singleUse=$row['SingleUse'];
      $discount=$row['Discount'];
      $priceID=$row['PriceID'];
      $Code=$row['Code'];
      $sDate=$row['Start'];
      $sEnd=$row['End'];
      $maxUse=$row['Threshold'];
      $totalUsed=$row['Used'];

      $sql="SELECT COUNT(*) FROM `aqb_membership_vouchers_transactions` WHERE `ProfileID` = ".getLoggedId()." AND `PriceID` = ".$priceID." AND `Code` = '$Code' AND `Status` = 'Processed'";
      $result=mysqli_query($this->con, $sql);
      $IsUsed=mysqli_fetch_row($result);
      $IsUsed=$IsUsed[0];
      if($IsUsed){
        return '{"result":false,"msg":"The code has been applied "}';
      }
      if($sdate > date("Y-m-d") || $sEnd < date("Y-m-d") ){
        return '{"result":false,"msg":"Code Expired."}';
      }
      if($totalUsed >= $maxUse || ($singleUse == 1 && $totalUsed == 1)) {
        return '{"result":false,"msg":"Code Expired."}';
      }
      return '{"result":true,"memtype":"'.$memType.'","discount":'.$discount.',"priceId":'.$priceID.'}';
      
    }else{
        return '{"result":false,"msg":"Invalid Code."}';
    }
  }
  public function getUserDetails(){
    $logid = getLoggedId();
    $sql="SELECT * FROM `Profiles` WHERE `ID` = ".$logid;
    $result=mysqli_query($this->con, $sql);
    $fieldcount=mysqli_affected_rows($this->con);
    if($fieldcount){
      $row=mysqli_fetch_array($result);
      return'{"id":'.$logid.',"name":"'.$row['NickName'].'","email":"'.$row['Email'].'","country":"'.$row['Country'].'","city":"'.$row['City'].'","fname":"'.$row['FirstName'].'","lname":"'.$row['LastName'].'"}';
    }else{
      return '{"result":false}';
    }
  }

  public function successTransaction(){
    $code = $_GET['code'];
    $discount=$_GET['discount'];
    $priceid=$_GET['priceid'];
    $sql="INSERT INTO `aqb_membership_vouchers_transactions` (`ProfileID`, `PriceID`, `Discount`, `Code`,`Status`) VALUES (".getLoggedId().", '$priceid', $discount, '$code','Processed')";
   
    $result=mysqli_query($this->con, $sql);
     $fieldcount=mysqli_affected_rows($this->con);
    if($fieldcount){
      return'{"status":"success","msg":"Success"}';
    }else{
      return '{"status":"err"}';
    }
  }
  public function getSiteUrl(){
    return $site['url'];
  }
  public function closeDb(){
    mysqli_close($this->con);
  }
}
 
$mobj= new MemberComponent();

if($_GET['date']){
  if('2013-03-25' < date("Y-m-d") )
  echo  date("Y-m-d");
}

if($_GET['code']){
  $mobj->connect_Db();
  echo $mobj->successTransaction();
}


if($_POST['vcode']){
  $mobj->connect_Db();
  echo $mobj->validateVocherCode();
}

if($_GET['siteurl']){
  echo $mobj->getSiteUrl();
}

if($_GET['user']){
   $mobj->connect_Db();
   echo $mobj->getUserDetails();
}
$mobj->closeDb();
?>