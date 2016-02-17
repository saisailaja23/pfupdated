<?php
/**
 * C:\Users\msi1308\AppData\Roaming\Sublime Text 2\Packages/PhpTidy/phptidy-sublime-buffer.php
 *
 * @author Aravind Buddha <aravind.buddha@mediaus.com>
 * @package default
 */


/*********************************************************************************
* Name:    Prashanth A
* Date:    12/12/2013
* Purpose: Listing the agencies liked by birth mother
*********************************************************************************/
require_once ('../../inc/header.inc.php');

require_once ('../../inc/profiles.inc.php');

require_once ('../../inc/classes/BxDolTemplate.php');

$logid = getLoggedId();
$AgencyLike_List = "SELECT AgencyLike  FROM `like_list` WHERE `LikedBy` = " . $logid . "";
$agency_query = mysql_query($AgencyLike_List);
$cmdtuples = mysql_num_rows($agency_query);
$arrColumns = explode(",", $columns);
$arrRows_agency_list = array();

while (($row = mysql_fetch_array($agency_query, MYSQL_BOTH))) {
  $agency_id = $row['AgencyLike'];
  $Agencydetails = mysql_query("select bx_groups_main.author_id,bx_groups_main.title,Profiles.Country,Profiles.City,Profiles.WEB_URL,bx_groups_main.thumb from Profiles,bx_groups_main where Profiles.ID = " . $agency_id . " and  Profiles.ID =author_id");
  while (($row_agency = mysql_fetch_array($Agencydetails, MYSQL_BOTH))) {
    $sImage = '';
    if ($row_agency[5]) {
      $a = array(
        'ID' => $row_agency[0],
        'Avatar' => $row_agency[5]
      );
      $aImage = BxDolService::call('photos', 'get_image', array(
          $a,
          'browse'
        ) , 'Search');
      $sImage = $aImage['no_image'] ? '' : $aImage['file'];
    }
    if ($sImage == '') {
      $sImage = $site['url'] . 'modules/boonex/groups/templates/base/images/no-image-thumb.png';
    }

    list($width, $height) = getimagesize("$sImage");
    if ($width>144) {
      $per=(($width-144)/$width)*100;
      $height =$height-(($height*$per)/100);
      $width=144;
    }
    if ($height>144) {
      $per=(($height-144)/$height)*100;
      $width =$width-(($width*$per)/100);
      $height=144;
    }
    $margin_left = ($width < 144)?(144-$width)/2:0;
    $width = ($margin_left == 0)?144:$width;
    $margin_top = ($height < 144)?(144-$height)/2:0;
    $height = ($margin_top == 0)?144:$height;
    //$sImage   =   $site['url'].'templates/tmpl_par/images/thumb_sample.png';

    $photo = $sImage;

    if (isset($row_agency[2]) && $row_agency[3]) {
      $address = $row_agency[3] . ", " . $row_agency[2];
    }
    elseif ($row_agency[2]) {
      $address = $row_agency[2];
    }
    else {
      $address = $row_agency[3];
    }

    $arrValues = array();
    array_push($arrRows_agency_list, array(
        'id'=> $row_agency[0],
        'Agency_id' => $row_agency[0],
        'Agency_name' => (strlen($row_agency[1])>31)?substr($row_agency[1], 0, 30)."...":$row_agency[1],
        'Agency_address' => $address,
        'Agency_Weburl' => $row_agency[4],
        'Agency_thumb' => $photo
      ));
  }
}

if ($cmdtuples > 0) {
  echo json_encode($arrRows_agency_list);
}
else {
  //echo json_encode(array(
  //'id' => ' ',
  //'response' => 'Could not find the searched item',
  //'data' => ' ',
  //'data_CFname' => ' '
  //));
}

?>
