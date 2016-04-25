<?php

define('BX_PROFILE_PAGE', 1);
require_once( '../../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
$data = array();
$id = ($_GET['id']!='undefined')?$_GET['id']:getLoggedId();
$member = getProfileInfo($id);
$sql = "SELECT SQL_CALC_FOUND_ROWS  `p`.NickName, `p`.ID,`p`.Couple, `p`.FirstName,`p`.LastName,`p`.City,`p`.Avatar 
  FROM `Profiles` AS `p` 
  -- JOIN `bx_photos_main` AS `ph`
  -- ON (`ph`.Owner = `p`.Avatar)
  INNER JOIN `bx_groups_fans` AS `f` 
  ON (`f`.`id_entry` = '$member[AdoptionAgency]' 
    AND `f`.`id_profile` = `p`.`ID` 
    AND `f`.`confirmed` = 1 
    AND `p`.`Status` <> 'Rejected' AND `p`.`Avatar` != '0' ) 
  LEFT JOIN `sys_acl_levels_members` AS `tlm` 
  ON `p`.`ID`=`tlm`.`IDMember` 
    AND `tlm`.`DateStarts` < NOW() 
    AND (`tlm`.`DateExpires`>NOW() || ISNULL(`tlm`.`DateExpires`)) 
  LEFT JOIN `sys_acl_levels` AS `tl` 
  ON `tlm`.`IDLevel`=`tl`.`ID` 
  GROUP BY `p`. `ID`  
  Order by DateLastLogin Desc";
  $result = mysql_query($sql);
  $nos = mysql_num_rows($result);

  if ($nos >= 1) {
    while ($row = mysql_fetch_array($result)) {
        $FamilyName = $row['FirstName'];
        $coupleID = $row['Couple'];
        if ($coupleID) {
            $Couplename = db_arr("SELECT `FirstName`,Age FROM `Profiles` WHERE `ID` = '$coupleID' LIMIT 1");
            $Couple_name = $Couplename[FirstName];
            $FamilyName .= " and $Couple_name";
        }
        
        $waterMark = db_arr("SELECT * FROM `watermarkimages` WHERE `author_id` = '{$row[ID]}' LIMIT 1");
        switch($waterMark[status]) {
            case 'Matched' :
                $text = '<span style="float:right;width:auto;border: 1px solid white;background:#f195bf">MATCHED</span>';
                break;
            case 'Placed' :
                $text = '<span style="float:right;width:auto;border: 1px solid white;background:#7BC345">PLACED</span>';
                break;
            default :
                $text = '';
                break;
        }
        
      $filename = $dir['root'] . 'modules/boonex/avatar/data/favourite/' . $row['Avatar'] . ".jpg" ;
      if (file_exists($filename)) {
        array_push($data, array(
          "img" =>"{$site['url']}modules/boonex/avatar/data/avatarphotos/{$row[ID]}.jpg",
          "profile_id" =>$row[ID],
          "nickname" => $row[NickName],
                "fname" => $row[FirstName],
                "lname" => $row[LastName],
          "city" => $row[City],
          "thumb" =>"{$site['url']}modules/boonex/avatar/data/images/{$row[Avatar]}.jpg",
                "FamilyName" => "$FamilyName",
                "text" => "$text"
        ));
      }
    }
    $photostatus = 'success';
    $Pmessage = $sql; 
  } else {
      $photostatus = 'error';
      $Pmessage = 'No uploaded Photos in the Albums';
      $data[]='';
}

echo json_encode(array(
  'status' =>$photostatus,
  'data' => $data,
  'Pmessage'=>$Pmessage, 
));
?>