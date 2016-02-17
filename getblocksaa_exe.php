<?php
define('BX_PROFILE_PAGE', 1);

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );    

$aData = '';
$id = $_GET['profile'];
/*
bx_import('BxTemplProfileView');
bx_import('BxDolInstallerUtils');

$oProfile = new BxBaseProfileGenerator( $id );  
$aCopA = $oProfile->_aProfile;
$aCopB = $oProfile->_aCouple;
//print_r($oProfile);

$oPPV = new BxTemplProfileView($oProfile, $site, $dir); 
*/
/* $sql = "SELECT * FROM `aqb_pc_members_blocks` WHERE `owner_id` = '$id'";
$result = mysql_query($sql);
$nos = mysql_num_rows($result);
if ($nos >= 1) {
	while($row = mysql_fetch_array($result))
	{ 
			$aData .=  
			<<<EOF
			<div class="heading_content" style="width:646px;">{$row['title']}</div>
			  <div class="body_content" style="width:600px;overflow:hidden;">
				{$row['content']}
			  </div>;-
EOF;
	}
}
echo $aData;
  */


//$sql1 = "SELECT * FROM `aqb_pc_members_blocks` WHERE `owner_id` = '$id'";
//echo $sql;
//$result1 = mysql_query($sql1);

//while($row1 = mysql_fetch_array($result1))
//{

//$blockid = $row1['id'];
//$aResult = array();

//echo $blockid ."\n";



$sql = "SELECT * FROM `aqb_pc_profiles_info` WHERE `member_id` = '$id'";
//echo $sql;
$result = mysql_query($sql);

while($row = mysql_fetch_array($result))
{ 
			
eval('$aPBlocks = ' . $row['page'] . ';');
//print_r($aPBlocks);

foreach($aPBlocks as $k => $v)
		  foreach($v as $key => $value)
			{

                          //print_r($key);
                        // print_r($value);
                          //if ($sType == 'standard' &&  $k == 's')
                              //$aB[] = $key;
                            // print_r($key);
			  //if ($sType == 'shared' &&  $k == 'c')
                             $aB[] = $key;

                      if (count($key)){
			$sWhere = implode("','", $aB);
			$sWhere = "('".$sWhere."')";
                        
		}

if ($sWhere) $sWhere = "AND `aqb_pc_members_blocks`.`id` IN {$sWhere}";
                        }
$sql = "SELECT * FROM `aqb_pc_members_blocks` WHERE `owner_id` = '$id' ".$sWhere;
//echo $sql ;
$result = mysql_query($sql);
$nos = mysql_num_rows($result);
if ($nos >= 1) {
	while($row = mysql_fetch_array($result))
	{
			$aData 	    = array();
			$aData['title']   = $row['title'];
			$aData['content'] =  $row['content'];
			$fm[]		    = $aData;


                                
	}

}
print_r($fm);
//echo implode("#####", $fm);
			//}

                       //if (count($aB)){
			//$sWhere = implode("','", $aB);
			//$sWhere = "('".$sWhere."')";
                        //print_r($sWhere);
                        //echo $sWhere;
		           }


//$test = (($aPBlocks['c']));
//print_r($test);
//print_r(array_keys($test));

//print_r($aPBlocks);



//}
?>