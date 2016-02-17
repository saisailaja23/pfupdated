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
        parent::BxDolPageView('agency');
    }

 function getBlockCode_Listagencies() {     
        
 $sqlQuery = "SELECT * FROM `sys_pre_values` WHERE `Key` LIKE 'Region'";
 $result = db_res($sqlQuery);    
 $c = 0;
 while($row = mysql_fetch_array($result))   
 {
       
  $region = $row['Value'];  
    
  $sqlQuery1 = "select bx_groups_main.title as agencytitle,bx_groups_main.uri as agencyurl,Profiles.Region as regions,bx_groups_main.author_id as authorid from bx_groups_main inner join Profiles where bx_groups_main.author_id = Profiles.ID and Profiles.Status = 'Active' 
  and Profiles.Region !='' and Profiles.Region = '$region' ";
   
  $c++;    
  
  if($c == 1)
  {
  $sResult .= <<<EOF
  <div style="float:left;">
EOF;
  }

 $result1 = db_res($sqlQuery1);  
 
 $num_rows = mysql_num_rows($result1);
  if($num_rows > 0 ) {
      
     $sResult .= <<<EOF
  <div class="headerGreen2">{$region}</div>  
EOF;
  
  }
  
 while($row1 = mysql_fetch_array($result1))  
 {     
       
     $agencytitle = $row1['agencytitle'];  
     $agencyurl= "extra_agency_view_29.php?id=".$row1['authorid']; 
     
     
     
          
     $sResult .= <<<EOF
    <div><a href = {$agencyurl} style="color:#77787A; 
     text-decoration:none; "/>{$agencytitle}</a></div>
EOF;
   
 }    
  if($c == 1)
  {
  $sResult .= <<<EOF
  </div>
EOF;
  }
 
  }   
     
 $sResult = $GLOBALS['oSysTemplate']->parseHtmlByName('agency_listing.html', array('content' => $sResult));
 return  $sResult;   
    /* $aItems = array();
   foreach($AgencyList as $agency) {

   $aItems[] = array( 
   'title' => $agency['title'],      
   'region' => $agency['Region']          

            );
    }
              
   return $GLOBALS['oSysTemplate']->parseHtmlByName('agency_listing.html', array('bx_repeat:items' => array_values($aItems)));        
  */                 
   }
   
}

//-------------------------------------------------------------------------------------------------------//

// --------------- page variables and login
$_page['name_index'] = 81;

$_page['header'] = _t( "_Agency" );

$_ni = $_page['name_index'];

$oAgency = new BxDolAgency();
$_page_cont[$_ni]['page_main_code'] = $oAgency ->getCode();

PageCode();  
    
 
   
  
 
 

   
   

