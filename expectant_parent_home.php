<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );


bx_import('BxDolPageView');

//--------------------------------------- agency  listing class ------------------------------------------//

class BxDolAgency extends BxDolPageView
{ 
    function BxDolAgency()
    {
        parent::BxDolPageView('expectant_parent_home');
    }

 function getBlockCode_ExpectantMenu() 
 {     
     
   $Bm_id = $_GET['ID'];  
   $Profile = getProfileInfo($Bm_id);
     
   $agencyLogins = db_arr("SELECT `DateLastLogin` FROM `Profiles` WHERE `ID` = '{$Bm_id}' LIMIT 1");
      // print_r($agencyLogin);exit();
  $agencyLogin = $agencyLogins[DateLastLogin];
  
  $date1 = time();
  $date2 =  strtotime($agencyLogin);
  $getlogid = getLoggedId();              
       
  $dateDiff = $date1 - $date2;
  $fullDays = floor($dateDiff/(60*60*24));
  $fullHours = floor(($dateDiff-($fullDays*60*60*24))/(60*60));
  $fullMinutes = floor(($dateDiff-($fullDays*60*60*24)-($fullHours*60*60))/60);
  $total =  "LAST ACTIVE ".$fullDays. " DAYS ". $fullHours. " HOURS AND ". $fullMinutes ." MINUTES ago";     
  $profilehome = $GLOBALS['site']['url'] .'expectant_parent_home.php?ID='.$Bm_id; 
  
  $aVars = array (         
      'fname'=>  $Profile['FirstName'] . ' and ' . $Profile['LastName'],
      'lastactive'=> $total, 
      'profilehome'=> $profilehome
   );
        
   return $GLOBALS['oSysTemplate']->parseHtmlByName('expectant_parent_home.html', $aVars);  

 }       
    
}

//-------------------------------------------------------------------------------------------------------//

// --------------- page variables and login
$_page['name_index'] = 81;

$_page['header'] = _t( "My Expectant Parent Profile" );

$_ni = $_page['name_index'];

$oAgency = new BxDolAgency();
$_page_cont[$_ni]['page_main_code'] = $oAgency ->getCode();

PageCode();  
    
 
   
  
 
 

   
   

