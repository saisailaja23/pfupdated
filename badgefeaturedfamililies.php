<?php
define('BX_PROFILE_PAGE', 1);
require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

bx_import('BxTemplProfileView');
bx_import('BxDolInstallerUtils');


$_GET['featuredFamily'] = '43';
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
    $sql = "SELECT * FROM `Profiles` WHERE (`DescriptionMe` LIKE '%$search%' or `FirstName` LIKE '%$search%' or`LastName` LIKE '%$search%' or`DearBirthParent` LIKE '%$search%' or `About_our_home` LIKE '%$search%' or `State` LIKE '%$search%' or `Religion` LIKE '%$search%' or `ChildEthnicity` LIKE '%$search%' or `Ethnicity` LIKE '%$search%') AND `AdoptionAgency` = '29' AND `ProfileType` != '8' AND `Status` = 'Active' ";
    }else{
    //$sql = "SELECT *, CHAR_LENGTH(DearBirthParent), COUNT(*) FROM `Profiles` WHERE `AdoptionAgency` = '29' AND `ProfileType` != '8' AND `Status` = 'Active' GROUP BY CHAR_LENGTH(DearBirthParent) ORDER BY CHAR_LENGTH(DearBirthParent) DESC";
	//$sql = "SELECT *, CHAR_LENGTH(DearBirthParent), COUNT(*) FROM `Profiles` WHERE `AdoptionAgency` = '29' AND `ProfileType` != '8' AND `Status` = 'Active' GROUP BY ID) ORDER BY CHAR_LENGTH(DearBirthParent) DESC";

	$sql = "SELECT * , CHAR_LENGTH( DearBirthParent ) , COUNT( * ) FROM `Profiles` WHERE `AdoptionAgency` = '29' AND `ProfileType` != '8' AND `Status` = 'Active' GROUP BY ID";
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
    $sql = "SELECT * FROM `Profiles` WHERE (`DescriptionMe` LIKE '%$search%' or `FirstName` LIKE '%$search%' or`LastName` LIKE '%$search%' or`DearBirthParent` LIKE '%$search%' or `About_our_home` LIKE '%$search%' or `State` LIKE '%$search%' or `Religion` LIKE '%$search%' or `ChildEthnicity` LIKE '%$search%' or `Ethnicity` LIKE '%$search%') AND `AdoptionAgency` = '29' AND `ProfileType` != '8' AND `Status` = 'Active' ";
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
   if(isset($_GET['beth']) && $_GET['beth'] != ''){

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
    $sql = "SELECT * FROM `Profiles` WHERE ( ".$where." ) AND `AdoptionAgency` = '29' AND `ProfileType` != '8' AND `Status` = 'Active' Limit 12";
	//echo $sql; die;
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

   $sql = "SELECT * FROM `Profiles` WHERE `AdoptionAgency` = '29' AND `ProfileType` != '8' AND `Status` = 'Active' ";
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

}
elseif(isset($_GET['featuredFamily'])){
	$sql = "SELECT * , CHAR_LENGTH( DearBirthParent ) , COUNT( * ) FROM `Profiles` WHERE `AdoptionAgency` = '29' AND `ProfileType` != '8' AND `Status` = 'Active' and `Avatar` <> '0' GROUP BY ID ORDER BY rand()";// Limit 10
      // echo $sql;
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

    
   $aData = $aCopA[FirstName].";-";

   $aData .= $aCopB[FirstName].";-";

      //$copAdbp = mb_substr($aCopA[DearBirthParent], 0, 500);
      //$aData .= $copAdbp = strip_tags($copAdbp, '<p>').";-";

       //echo $aData;


      $sql = "SELECT * , MAX(ID) FROM `bx_avatar_images` WHERE `author_id` = '$ffprofile'";
      $result = mysql_query($sql);

$aData1='';
$aData2 = $aCopA[ID].";-";





      while($row = mysql_fetch_array($result))
       {
//echo "<pre>";print_r($row);echo "</pre>";
           $filename = '/var/www/html/pf/modules/boonex/avatar/data/images/'.$row[id].'.jpg';

        if (file_exists($filename)) {

        $filename12 = '/var/www/html/pf/modules/boonex/avatar/data/avatarphotos/'.$row[author_id].'.jpg';

        if (file_exists($filename12)) {

         $aData1 = 'http://www.parentfinder.com/modules/boonex/avatar/data/avatarphotos/'.$row[author_id].'.jpg';

            }
          
         else {
                   $aData1 = 'http://www.parentfinder.com/modules/boonex/avatar/data/images/'.$row[id].'.jpg';

                  }
          

              

        //$aData1 .= $row['Title'].";-";
        //$aData1 .= $row['Desc'].";-";

  	break;

    }
  
       }

      $fm[]= $aData2.$aData.$aData1;
   
          }
            echo implode("#####", $fm);

}
else{

    if(isset($_GET['pstart']) && isset($_GET['pend'])){
    $limit = "LIMIT ".$_GET['pstart'].", ".$_GET['pend'];
    }else{
    $limit = "LIMIT 0, 12";
    }

    //$sql = "SELECT *, CHAR_LENGTH(DearBirthParent), COUNT(*) FROM `Profiles` WHERE `AdoptionAgency` = '29' AND `ProfileType` != '8' AND `Status` = 'Active' GROUP BY CHAR_LENGTH(DearBirthParent) $limit";
	//$sql = "SELECT * , CHAR_LENGTH( DearBirthParent ) , COUNT( * ) FROM `Profiles` LEFT JOIN `sys_acl_levels_members` ON `Profiles`.`ID` = `sys_acl_levels_members`.IDMember WHERE `AdoptionAgency` = '29' AND `ProfileType` != '8' AND `Status` = 'Active'  GROUP BY ID ORDER BY `Profiles`.`Avatar` $limit";

	$sql = "SELECT `Profiles`.* , CHAR_LENGTH( DearBirthParent ) , COUNT( * ), `bx_photos_main`.Hash FROM `Profiles` LEFT JOIN
	`bx_photos_main` ON `Profiles`.`ID` = `bx_photos_main`.Owner WHERE `AdoptionAgency` = '29' AND `ProfileType` != '8' AND `Profiles`.`Status` = 'Active' GROUP BY `Profiles`.ID ORDER BY `Profiles`.`Avatar` DESC $limit";

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