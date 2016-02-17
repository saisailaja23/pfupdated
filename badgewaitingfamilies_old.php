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
  //echo $id;

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
  //echo $id;

}elseif(isset($_GET['famreg'])){

   $where = '';
	
   if(isset($_GET['famreg']) && $_GET['famreg'] != ''){
	
   $famregs = str_replace("<=>", " ", explode(",", $_GET['famreg']));
	foreach($famregs as $famreg){
		$where .= "`Religion` LIKE '%$famreg%' or ";
	}

    }
   if(isset($_GET['pareth']) && $_GET['pareth'] != ''){

   //$where .= "`BMChildEthnicity` LIKE '$_GET[pareth]' or ";
   $pareths = str_replace("<=>", " ", explode(",", $_GET['pareth']));
	foreach($pareths as $pareth){
		$where .= "`BMChildEthnicity` LIKE '%$pareth%' or ";
	}

    }
   if(isset($_GET['beth']) && $_GET['beth'] != '') {

   //$where .= "`ChildEthnicity` LIKE '%$_GET[beth]%' or ";
   $beths = str_replace("<=>", " ", explode(",", $_GET['beth']));
	foreach($beths as $beth){
		$where .= "`ChildEthnicity` LIKE '%$beth%' or ";
	}

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
	//echo $sql; die;
    $result = mysql_query($sql);
    $id = '';
    $i = 0;
    while($row = mysql_fetch_array($result))
  {
      $i++;
     
       $id .= $row['ID'].";-";
     
  }
  //echo $id;


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
  //echo $id;

}
elseif(isset($_GET['featuredFamily'])){

	$sql = "SELECT * , CHAR_LENGTH( DearBirthParent ) , COUNT( * ) FROM `Profiles` LEFT JOIN `sys_acl_levels_members` ON `Profiles`.`ID` = `sys_acl_levels_members`.IDMember WHERE `AdoptionAgency` = '29' AND `ProfileType` != '8' AND `Status` = 'Active' AND `sys_acl_levels_members`.IDLevel =5 GROUP BY ID ORDER BY `Profiles`.`Avatar`";
	
     
    $result = mysql_query($sql);
	$id = '';
	while($row = mysql_fetch_array($result))
	{
		$nknm = substr($aCopA[NickName], -3);  
		if($nknm != '(2)'){
			$id .= $row['ID'].";-";
		}
	}
	//echo $id;

}
else{

    if(isset($_GET['pstart']) && isset($_GET['pend'])){
    $limit = "LIMIT ".$_GET['pstart'].", ".$_GET['pend'];
    }else{
    $limit = "LIMIT 0, 12";
    }

    //$sql = "SELECT *, CHAR_LENGTH(DearBirthParent), COUNT(*) FROM `Profiles` WHERE `AdoptionAgency` = '29' AND `ProfileType` != '8' AND `Status` = 'Active' GROUP BY CHAR_LENGTH(DearBirthParent) $limit";
	//$sql = "SELECT * , CHAR_LENGTH( DearBirthParent ) , COUNT( * ) FROM `Profiles` LEFT JOIN `sys_acl_levels_members` ON `Profiles`.`ID` = `sys_acl_levels_members`.IDMember WHERE `AdoptionAgency` = '29' AND `ProfileType` != '8' AND `Status` = 'Active' AND `sys_acl_levels_members`.IDLevel =5 GROUP BY ID ORDER BY `Profiles`.`Avatar` $limit";
	
	$sql = "SELECT `Profiles`.* , CHAR_LENGTH( DearBirthParent ) , COUNT( * ), `bx_photos_main`.Hash FROM `Profiles` LEFT JOIN `sys_acl_levels_members` ON `Profiles`.`ID` = `sys_acl_levels_members`.IDMember LEFT JOIN 
	`bx_photos_main` ON `Profiles`.`ID` = `bx_photos_main`.Owner WHERE `AdoptionAgency` = '29' AND `ProfileType` != '8' AND `Profiles`.`Status` = 'Active' AND 
	`sys_acl_levels_members`.IDLevel =5 GROUP BY `Profiles`.ID ORDER BY `Profiles`.`Avatar` DESC $limit";

    $result = mysql_query($sql);
    $id = '';
    while($row = mysql_fetch_array($result))
  {
     $nknm = substr($aCopA[NickName], -3);  
      if($nknm != '(2)'){
       $id .= $row['ID'].";-";
      }
  }
  //echo $id;

$featuredFamilyData = (explode(";-",$id));

     //Print_r($featuredFamilyData);

      foreach($featuredFamilyData as $ffkey => $ffprofile)
	 {


      $oProfile = new BxBaseProfileGenerator($ffprofile);
    $aCopA = $oProfile->_aProfile;
     $aCopB = $oProfile->_aCouple;

    $aData = $ffprofile.";-".$aCopA[FirstName].";-";

   $aData .= $aCopB[FirstName].";-";

      //$copAdbp = mb_substr($aCopA[DearBirthParent], 0, 500);
      //$aData .= $copAdbp = strip_tags($copAdbp, '<p>').";-";

       //echo $aData;


      $sql = "SELECT * FROM `bx_photos_main` WHERE `Owner` = '$ffprofile'";
      $result = mysql_query($sql);
$aData1='';
      while($row = mysql_fetch_array($result))
       {
        $filename = '/var/www/html/pf/modules/boonex/photos/data/files/'.$row['ID'].".".$row['Ext'];
        if (file_exists($filename)) {
          $aData1 = $site['url']."m/photos/get_image/file/".$row['Hash'].".".$row['Ext'];
        //$aData1 .= $row['Title'].";-";
        //$aData1 .= $row['Desc'].";-";

  	break;

    }
       }

      $fm[]= $aData.$aData1;

          }
echo implode("#####", $fm);
      


}
  
?>