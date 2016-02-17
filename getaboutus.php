<?php
define('BX_PROFILE_PAGE', 1);

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );    

$id = $_GET['profile'];


bx_import('BxTemplProfileView');
bx_import('BxDolInstallerUtils');

$oProfile = new BxBaseProfileGenerator( $id );  
$aCopA = $oProfile->_aProfile;
$aCopB = $oProfile->_aCouple;
//print_r($oProfile);

$oPPV = new BxTemplProfileView($oProfile, $site, $dir);
//echo $oPPV->getCode(); exit;
$aData = ''; 
 /*
$copAdesc = $aCopA[DescriptionMe];
$aData .= $copAdesc = strip_tags($copAdesc, '<img>').";-";

$copBdesc = $aCopB[DescriptionMe];
$aData .= $copBdesc = strip_tags($copBdesc, '<img>').";-";

$copAdbp = $aCopA[DearBirthParent];
$aData .= $copAdbp = $copAdbp.";-";
*/
$aData .= $aCopA[FirstName].";-";
$aData .= strip_tags($aCopB[FirstName]).";-";
$aData .= $aCopA[Education].";-";
$aData .= $aCopA[Occupation].";-";
$aData .= $aCopA[Ethnicity].";-";
$aData .= $aCopA[Religion].";-";
$aData .= $aCopA[Smoking].";-";
$aData .= $aCopA[State].";-";
$aData .= $aCopA[YearsMarried].";-";
$aData .= $aCopA[Residency].";-";
$aData .= $aCopA[Neighborhood].";-";
$aData .= $aCopA[FamilyStructure].";-";
$aData .= $aCopA[Pet].";-";
$aData .= $aCopB[Education].";-";
$aData .= $aCopB[Occupation].";-";
$aData .= $aCopB[Ethnicity].";-";
$aData .= $aCopB[Religion].";-";
$aData .= $aCopB[Smoking].";-";
$aData .= $aCopA[DateOfBirth].";-";
$aData .= $aCopB[DateOfBirth].";-";
$aData .= $aCopA[DescriptionMe].";-";
$aData .= $aCopB[DescriptionMe].";-";
$aData .= $aCopA[Hobbies].";-";
$aData .= $aCopA[Interests].";-";
$aData .= $aCopB[Hobbies].";-";
$aData .= $aCopB[Interests].";-";

$aData .= $aCopA[About_our_home].";-";

$aData .= $aCopA[ChildAge].";-";
$aData .= $aCopA[ChildEthnicity].";-";
$aData .= $aCopA[ChildGender].";-";
$aData .= $aCopA[ChildSpecialNeeds].";-";
$aData .= $aCopA[ChildDesired].";-";

echo $aData;
?>