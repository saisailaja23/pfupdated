<?php
    define('BX_PROFILE_PAGE', 1);

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

bx_import('BxTemplProfileView');
bx_import('BxDolInstallerUtils');

if(isset($_GET['profile'])){

$id = $_GET['profile'];
$oProfile = new BxBaseProfileGenerator( $id );  
$aCopA = $oProfile->_aProfile;
$aCopB = $oProfile->_aCouple;
$aData = '';
$aData .= $aCopA[FirstName].";-";

$aData .= $aCopB[FirstName].";-";  

$copAdbp = mb_substr($aCopA[DearBirthParent], 0, 500);
$aData .= $copAdbp = strip_tags($copAdbp, '<p>').";-";
echo $aData;  

}elseif(isset($_GET['getpa'])){
    
    if(isset($_GET['search'])){
    $sql = "SELECT * FROM `Profiles` LEFT JOIN `sys_acl_levels_members` ON `Profiles`.`ID` = `sys_acl_levels_members`.IDMember WHERE (`DescriptionMe` LIKE '%$search%' or `FirstName` LIKE '%$search%' or`LastName` LIKE '%$search%' or`DearBirthParent` LIKE '%$search%' or `About_our_home` LIKE '%$search%') AND `AdoptionAgency` = '29' AND `ProfileType` != '8' AND `Status` = 'Active' AND `sys_acl_levels_members`.IDLevel =5 Limit 12";
    }else{
    //$sql = "SELECT *, CHAR_LENGTH(DearBirthParent), COUNT(*) FROM `Profiles` WHERE `AdoptionAgency` = '29' AND `ProfileType` != '8' AND `Status` = 'Active' GROUP BY CHAR_LENGTH(DearBirthParent) ORDER BY CHAR_LENGTH(DearBirthParent) DESC";
	//$sql = "SELECT *, CHAR_LENGTH(DearBirthParent), COUNT(*) FROM `Profiles` WHERE `AdoptionAgency` = '29' AND `ProfileType` != '8' AND `Status` = 'Active' GROUP BY ID) ORDER BY CHAR_LENGTH(DearBirthParent) DESC";
	
	$sql = "SELECT * , CHAR_LENGTH( DearBirthParent ) , COUNT( * ) FROM `Profiles` LEFT JOIN `sys_acl_levels_members` ON `Profiles`.`ID` = `sys_acl_levels_members`.IDMember WHERE `AdoptionAgency` = '29' AND `ProfileType` != '8' AND `Status` = 'Active' AND `sys_acl_levels_members`.IDLevel =5 GROUP BY ID";
    }
    $result = mysql_query($sql);
    $id = '';
    $i = 0;
    /*while($row = mysql_fetch_array($result))
  {
      $i++;
      if($i % 5 == 0){
       $id .= $row['ID'].";-";
      }
  }*/
while($row = mysql_fetch_array($result))
{
	if($i == 0){
	$id .= $row['ID'].";-";
	}
	$i++;
	if($i == 12){
	$i = 0;
	}
}
  echo $id;

}elseif(isset($_GET['search'])){

    $search = $_GET['search'];
    $sql = "SELECT * FROM `Profiles` LEFT JOIN `sys_acl_levels_members` ON `Profiles`.`ID` = `sys_acl_levels_members`.IDMember WHERE (`DescriptionMe` LIKE '%$search%' or `FirstName` LIKE '%$search%' or`LastName` LIKE '%$search%' or`DearBirthParent` LIKE '%$search%' or `About_our_home` LIKE '%$search%') AND `AdoptionAgency` = '29' AND `ProfileType` != '8' AND `Status` = 'Active' AND `sys_acl_levels_members`.IDLevel =5 Limit 12";
    $result = mysql_query($sql);
    $id = '';
    $i = 0;
    while($row = mysql_fetch_array($result))
  {
      $i++;
     
       $id .= $row['ID'].";-";
     
  }
  echo $id;

}elseif(isset($_GET['famreg'])){

   $where = '';

   if(isset($_GET['famreg'])){

   $where .= "`Religion` LIKE '%$_GET[famreg]%' or ";

    }
   if(isset($_GET['pareth'])){

   $where .= "`BMChildEthnicity` LIKE '$_GET[pareth]' or ";

    }
   if(isset($_GET['beth'])){

   $where .= "`ChildEthnicity` LIKE '%$_GET[beth]%' or ";

    }
   if(isset($_GET['family_num_children'])){

   $where .= "`ChildAge` LIKE '%$_GET[family_num_children]%' or ";

    }
   if(isset($_GET['family_state'])){

   $where .= "`State` LIKE '%$_GET[family_state]%' or ";

    }

   $where .= "`DescriptionMe` LIKE '1'";

    $search = $_GET['search'];
    $sql = "SELECT * FROM `Profiles` LEFT JOIN `sys_acl_levels_members` ON `Profiles`.`ID` = `sys_acl_levels_members`.IDMember WHERE ( ".$where." ) AND `AdoptionAgency` = '29' AND `ProfileType` != '8' AND `Status` = 'Active' AND `sys_acl_levels_members`.IDLevel =5 Limit 12";
    $result = mysql_query($sql);
    $id = '';
    $i = 0;
    while($row = mysql_fetch_array($result))
  {
      $i++;
     
       $id .= $row['ID'].";-";
     
  }
  echo $id;


}elseif(isset($_GET['pnfam'])){

   $sql = "SELECT * FROM `Profiles` LEFT JOIN `sys_acl_levels_members` ON `Profiles`.`ID` = `sys_acl_levels_members`.IDMember WHERE `AdoptionAgency` = '29' AND `ProfileType` != '8' AND `Status` = 'Active' AND `sys_acl_levels_members`.IDLevel =5";
    $result = mysql_query($sql);
    $id = '';
    while($row = mysql_fetch_array($result))
  {
     //$nknm = substr($aCopA[NickName], -3);  
     // if($nknm != '(2)'){
       $id .= $row['ID'].";-";
     // }
  }
  echo $id;

}else{

    if(isset($_GET['pstart']) && isset($_GET['pend'])){
    $limit = "LIMIT ".$_GET['pstart'].", ".$_GET['pend'];
    }else{
    $limit = "LIMIT 0, 12";
    }

    //$sql = "SELECT *, CHAR_LENGTH(DearBirthParent), COUNT(*) FROM `Profiles` WHERE `AdoptionAgency` = '29' AND `ProfileType` != '8' AND `Status` = 'Active' GROUP BY CHAR_LENGTH(DearBirthParent) $limit";
	$sql = "SELECT * , CHAR_LENGTH( DearBirthParent ) , COUNT( * ) FROM `Profiles` LEFT JOIN `sys_acl_levels_members` ON `Profiles`.`ID` = `sys_acl_levels_members`.IDMember WHERE `AdoptionAgency` = '29' AND `ProfileType` != '8' AND `Status` = 'Active' AND `sys_acl_levels_members`.IDLevel =5 GROUP BY ID $limit";
    $result = mysql_query($sql);
    $id = '';
    while($row = mysql_fetch_array($result))
  {
     $nknm = substr($aCopA[NickName], -3);  
      if($nknm != '(2)'){
       $id .= $row['ID'].";-";
      }
  }
  echo $id;
}
?>