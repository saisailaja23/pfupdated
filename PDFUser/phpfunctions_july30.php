<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php
$baseurl        = $site['url'];
$basepath       = $dir['root'];

//fetching all photo albums
function getPhotoAlbums($parentID)
{
        $photoQuery = "SELECT *
    FROM `bx_photos_main`
    WHERE `Owner` = $parentID And  Status = 'approved'";
    $photFetch  = mysql_query($photoQuery);
    if(mysql_numrows($photFetch) > 0)
    {
        while($row_photo = mysql_fetch_assoc($photFetch))
        {
            $photoArray[] = $row_photo;
        }
    }
    return $photoArray;
}

//selcetd cover image
function selectedCoverImage($parentID,$hashID)
{
        $photoQuery = "SELECT *
    FROM `bx_photos_main`
    WHERE `Owner` = $parentID And  Status = 'approved' And Hash = '$hashID'";
        
    $photFetch  = mysql_query($photoQuery);
    if(mysql_numrows($photFetch) > 0)
    {
        while($row_photo = mysql_fetch_assoc($photFetch))
        {
            $sel_cover = $row_photo;
        }
    }
    
    return $sel_cover;
}
//For image resolution set up in lifebook, upload photos, files etc....
function getUploadImageResolution($profileimage,$box_height,$box_width)
{
    $imgSizeArray                       = array();
    list($width, $height, $type, $attr) = getimagesize($profileimage);
    $imageWidth                         = $width;
    $imageHeight                        = $height;
    if ($imageWidth > $box_width || $imageHeight > $box_height )
    {
        if($imageHeight == $imageWidth)
        {
            $imgSizeArray['imageHeight'] 		= $box_height;
            $imgSizeArray['imageWidth'] 		= $box_height;
        }
        else if($box_height<$box_width)
        {
            $imgSizeArray['imageWidth']         = ($imageWidth * $box_height)/$imageHeight;
            $imgSizeArray['imageHeight'] 		= $box_height;
            if($imgSizeArray['imageWidth'] > $box_width)
            {
                 $imgSizeArray['imageWidth']    = $box_width;
                 $imgSizeArray['imageHeight'] 	= ($imageHeight * $box_width)/$imageWidth;                 
            }
            
        }
        else
        {
            $imgSizeArray['imageHeight'] 		= ($imageHeight * $box_width)/$imageWidth;
            $imgSizeArray['imageWidth'] 		= $box_width;
            if($imgSizeArray['imageHeight'] > $box_height)
            {
                 $imgSizeArray['imageWidth']    = ($imageWidth * $box_height)/$imageHeight;
                 $imgSizeArray['imageHeight'] 	= $box_height;                
            }
        }
    }
    else
    {
            $imgSizeArray['imageHeight'] 		= $height;
            $imgSizeArray['imageWidth'] 		= $width;
    }
    $imgSizeArray['margintop']  				= ($box_height-$imgSizeArray['imageHeight'])/2;
    $imgSizeArray['marginleft']  				= ($box_width-$imgSizeArray['imageWidth'])/2;
    $imgSizeArray['profimage']  				= $profileimage;



    return $imgSizeArray;
}
function getProfileImage($ffprofile)
{
    global $baseurl,$basepath;
    $sql_avt = "SELECT * , MAX(ID) FROM `bx_avatar_images` WHERE `author_id` = '$ffprofile'";
    $result_avt = mysql_query($sql_avt);
    //echo $sql_avt;
    $aData1='';

    while($row_avt = mysql_fetch_array($result_avt))
    {
        //echo "<pre>";print_r($row_avt);echo "</pre>";
        //$filename = '/var/www/html/pf/modules/boonex/avatar/data/images/'.$row[id].'.jpg';
        $filename = $basepath.'modules/boonex/avatar/data/images/'.$row_avt[id].'.jpg';
        //echo $filename;
        if (file_exists($filename)) {//echo "inside";
            //$filename12 = '/var/www/html/pf/modules/boonex/avatar/data/avatarphotos/'.$row[author_id].'.jpg';
            $filename12 = $basepath.'modules/boonex/avatar/data/avatarphotos/'.$row_avt[author_id].'.jpg';

            if (file_exists($filename12)) {
                //$aData1 = 'http://www.parentfinder.com/modules/boonex/avatar/data/avatarphotos/'.$row[author_id].'.jpg';
                $aData1['filepath']     = $basepath.'modules/boonex/avatar/data/avatarphotos/'.$row_avt[author_id].'.jpg';
                $aData1['imagepath']    = $baseurl.'modules/boonex/avatar/data/avatarphotos/'.$row_avt[author_id].'.jpg';
            }

            else {
                //$aData1 = 'http://www.parentfinder.com/modules/boonex/avatar/data/images/'.$row[id].'.jpg';
                $aData1['filepath']     = $basepath.'modules/boonex/avatar/data/images/'.$row_avt[id].'.jpg';
                $aData1['imagepath']    = $baseurl.'modules/boonex/avatar/data/images/'.$row_avt[id].'.jpg';
            }
            //$aData1 = 'modules/boonex/avatar/data/avatarphotos/'.$row_avt[author_id].'.jpg';
            break;
        }
    }
    return $aData1;
}

function Resize($tmpName, $ht, $wd,$temp_file_name)
{

    $uploadedFile                   = $tmpName;

    $imgSrc = "boy with pumpkin.jpg";

    //getting the image dimensions
    list($width, $height) = getimagesize($uploadedFile);

    //saving the image into memory (for manipulation with GD Library)
    $myImage = imagecreatefromjpeg($uploadedFile);

    // calculating the part of the image to use for thumbnail
    if ($width > $height) {
      $y = 0;
      $x = ($width - $height) / 2;
      $smallestSide = $height;
    } else {
      $x = 0;
      $y = ($height - $width) / 2;
      $smallestSide = $width;
    }

    // copying the part into thumbnail
    $thumbSize = 110;
    $thumbheight = $ht;
    $thumbwidth  = $wd;
    //echo $thumbheight;
    //echo $thumbwidth;
    $thumb = imagecreatetruecolor($thumbwidth, $thumbheight);
    imagecopyresampled($thumb, $myImage, 0, 0, $x, $y, $thumbwidth, $thumbheight, $smallestSide, $smallestSide);

    $img                            = "temp/$temp_file_name.jpg";
    imagejpeg($thumb, $img, 100);  //imagejpeg($resampled, $fileName, $quality);
    imagedestroy($myImage);
    imagedestroy($thumb);

    return $img;
}


function gettempuserDataID($template_user_id)
{
     $gettempuserDataID         = "SELECT template_data_id FROM pdf_template_data WHERE template_user_id = $template_user_id";
     $tempUSID  = mysql_query($gettempuserDataID);
     if(mysql_numrows($tempUSID) > 0)
     {
          while($row_tmpusid = mysql_fetch_assoc($tempUSID))
          {
                $tempUserID = $row_tmpusid['template_data_id'];
          }
     }
     return $tempUserID;
}

function getusertempDetails($tempusrid)
{
     $sql_tempuserDet         = "SELECT
                            ptu.template_user_id,
                            ptu.user_id,
                            ptu.template_id,
                            ptu.template_file_path,
                            ptu.template_description,
                            ptu.isDeleted,
                            ptd.template_data_id,
                            ptd.cover_title,
                            ptd.cover_picture,
                            ptd.block_ids,
                            ptd.photo_title,
                            ptd.photo_ids,
                            ptd.photo_description
                            FROM pdf_template_user ptu
                            LEFT JOIN pdf_template_data ptd ON(ptd.template_user_id =ptu.template_user_id)
                            WHERE ptu.template_user_id = $tempusrid";
     $tempUSDet             = mysql_query($sql_tempuserDet);
     if(mysql_numrows($tempUSDet) > 0)
     {
          while($row_tmpusdet = mysql_fetch_assoc($tempUSDet))
          {
                $tempUserDetails = $row_tmpusdet;
          }
     }
     return $tempUserDetails;
}

function getuserPDF($userID)
{
     $sql_pdfuserDet         = "SELECT
                            ptu.template_user_id,
                            ptu.user_id,
                            ptu.template_id,
                            ptu.template_file_path,
                            ptu.template_description,
                            ptu.isDeleted,
                            ptd.template_data_id,
                            ptd.cover_title,
                            ptd.cover_picture,
                            ptd.block_ids,
                            ptd.photo_title,
                            ptd.photo_ids,
                            ptm.template_name
                            FROM pdf_template_user ptu
                            LEFT JOIN pdf_template_data ptd ON(ptd.template_user_id =ptu.template_user_id)
                            LEFT JOIN pdf_template_master ptm ON(ptm.template_id = ptu.template_id)
                            WHERE ptu.user_id = $userID AND ptu.isDeleted = 'N'";
     //echo $sql_pdfuserDet;
     $pdfUserDet             = mysql_query($sql_pdfuserDet);
     if(mysql_numrows($pdfUserDet) > 0)
     {
          while($row_pdfsdet = mysql_fetch_assoc($pdfUserDet))
          {
                $pdfUserDetails[] = $row_pdfsdet;
          }
     }
     return $pdfUserDetails;
}

function getavailableTemplates($pid)
{
    $parentuserDetails      = getuserDetails($pid);
    $agency_ID              = $parentuserDetails['agencyID'];
    $sql_avilTempDet         = "SELECT  ptm.template_id,
	ptm.template_name,
	ptm.template_description,
	ptm.template_type
    FROM `pdf_template_master` ptm
    WHERE ptm.isDeleted ='N'
    AND ptm.template_disbale_status = 'N'
    AND (ptm.template_type = 'agency' AND
    (SELECT count(*) FROM pdf_template_agency pta WHERE pta.template_id = ptm.template_id AND pta.agency_id =26 )
    OR ptm.template_type = 'global')";
     //echo $sql_pdfuserDet;
     $aviltempDet             = mysql_query($sql_avilTempDet);
     if(mysql_numrows($aviltempDet) > 0)
     {
          while($row_avtempdet = mysql_fetch_assoc($aviltempDet))
          {
                $AvaibaleTemplate[] = $row_avtempdet;
          }
     }
     return $AvaibaleTemplate;

}

function getuserDetails($profileID)
{
    $sqlQuery   = "SELECT p.Status,
    p.Role,
    p.FirstName,
    p.LastName,
    p.Avatar,
    TIMESTAMPDIFF(DAY, p.DateReg, CURDATE()) AS daysWaiting,
    p.AdoptionAgency AS agencyID,
    p.globalval AS globalFlag,
    ag.title AS Agency
    FROM Profiles p
    LEFT JOIN bx_groups_main ag ON (ag.id  = p.AdoptionAgency)
    WHERE p.ID= $profileID";
    //echo "query ".$sqlQuery."<br/>";
    $result     = mysql_query($sqlQuery);
    if(mysql_numrows($result) > 0)
    {
        while($row = mysql_fetch_assoc($result))
        {
            //print_r($row);
            $userVal     = $row;

        }
    }
    mysql_free_result($result);
    return $userVal;
    //echo "<br/>";
}

function getuserDefaultPDF($userID)
{
     $pdfUserDefault          = "";
     $sql_pdfuserDet         = "SELECT
                            ptu.template_user_id,
                            ptu.user_id,
                            ptu.template_id
                            FROM pdf_template_user ptu
                            WHERE ptu.user_id = $userID AND ptu.isDeleted = 'N' AND ptu.isDefault ='Y'";
     //echo $sql_pdfuserDet;
     $pdfUserDet             = mysql_query($sql_pdfuserDet);
     if(mysql_numrows($pdfUserDet) > 0)
     {
          while($row_pdfsdet = mysql_fetch_assoc($pdfUserDet))
          {
                $pdfUserDefault = $row_pdfsdet['template_user_id'];
          }
     }
     return $pdfUserDefault;
}

function gettemplateDetails($templateID)
{
     $sql_tempDet         = "SELECT ptm.template_id,
                                        ptm.template_name,
                                        ptm.template_description,
                                        ptm.template_type,
                                        ptm.isDeleted,
                                        ptm.printMode
                            FROM pdf_template_master ptm
                            WHERE ptm.template_id = $templateID";
     $tempDet             = mysql_query($sql_tempDet);
     if(mysql_numrows($tempDet) > 0)
     {
          while($row_tmpdet = mysql_fetch_assoc($tempDet))
          {
                $tempDetails = $row_tmpdet;
          }
     }
     return $tempDetails;
}

function getAgencyDetails($parentID)
{
     $sql_agDet         = "SELECT bx_groups_main.title AS AgencyTitle,
                            Profiles.* FROM Profiles
                            JOIN bx_groups_main WHERE
                            Profiles.AdoptionAgency=bx_groups_main.id
                            AND Profiles.ID  IN
                            (SELECT bx_groups_main.author_id FROM Profiles JOIN
                            bx_groups_main WHERE Profiles.ID =$parentID AND Profiles.AdoptionAgency=bx_groups_main.id )";
     $agDet             = mysql_query($sql_agDet);
     if(mysql_numrows($agDet) > 0)
     {
          $row_agency = mysql_fetch_array($agDet);
     }
     return $row_agency;

}

function getAllBlockData($parent_ID)
{
    $profileblockArray      = array();
    $oProfile               = new BxBaseProfileGenerator($parent_ID);
    $aCopA                  = $oProfile->_aProfile;
    $aCopB                  = $oProfile->_aCouple;
    //1
    if($aCopA['DescriptionMe'])
    {
        $profileblock                           = array();
        $profileblock['blockname']              = "DescriptionMe_PRF_1";
        $profileblock['blockcontent']           = $aCopA['DescriptionMe'];
        $profileblock['blockheading']           = "About ".$aCopA['FirstName'];
        $profileblockArray[]                    = $profileblock;
    }
    //2
    if($aCopA['Hobbies'])
    {
        $profileblock                           = array();
        $profileblock['blockname']              = "Hobbies_PRF_1";
        $profileblock['blockcontent']           = $aCopA['Hobbies'];
        $profileblock['blockheading']           = "Hobbies Of ".$aCopA['FirstName'];
        $profileblockArray[]                    = $profileblock;
    }
    //3
    if($aCopA['Interests'])
    {
        $profileblock                           = array();
        $profileblock['blockname']              = "Interests_PRF_1";
        $profileblock['blockcontent']           = $aCopA['Interests'];
        $profileblock['blockheading']           = "Interests Of ".$aCopA['FirstName'];
        $profileblockArray[]                    = $profileblock;
    }
    //4
    if($aCopA['About_our_home'])
    {
        $profileblock                           = array();
        $profileblock['blockname']              = "About_our_home_PRF_1";
        $profileblock['blockcontent']           = $aCopA['About_our_home'];
        $profileblockArray['blockheading']      = "About Our Home ";
        $profileblockArray[]                    = $profileblock;
    }
    //5
    if($aCopB['DescriptionMe'])
    {
        $profileblock                           = array();
        $profileblock['blockname']              = "DescriptionMe_PRF_2";
        $profileblock['blockcontent']           = $aCopB['DescriptionMe'];
        $profileblock['blockheading']           = "About ".$aCopB['FirstName'];
        $profileblockArray[]                    = $profileblock;
    }
    //6
    if($aCopB['Hobbies'])
    {
        $profileblock                           = array();
        $profileblock['blockname']              = "Hobbies_PRF_2";
        $profileblock['blockcontent']           = $aCopB['Hobbies'];
        $profileblock['blockheading']           = "Hobbies Of ".$aCopB['FirstName'];
        $profileblockArray[]                    = $profileblock;
    }
    //7
     if($aCopB['Hobbies'])
    {
        $profileblock                           = array();
        $profileblock['blockname']              = "Interests_PRF_2";
        $profileblock['blockcontent']           = $aCopB['Interests'];
        $profileblock['blockheading']           = "Interests Of ".$aCopB['FirstName'];
        $profileblockArray[]                    = $profileblock;
    }

    if($aCopA['DearBirthParent'])
    {
        $profileblock                           = array();
        $profileblock['blockname']              = "BPletter_PRF_2";
        $profileblock['blockcontent']           = $aCopA['DearBirthParent'];
        $profileblock['blockheading']           = "Birth Parent Letter";
        $profileblockArray[]                    = $profileblock;
    }
    
    $sql = "SELECT * FROM `aqb_pc_profiles_info` WHERE `member_id` = '$parent_ID'";
    $result = mysql_query($sql);
    while($row = mysql_fetch_array($result))
    {
        eval('$aPBlocks = ' . $row['page'] . ';');
        foreach($aPBlocks as $k => $v)
            foreach($v as $key => $value)
            {
                $aB[] = $key;
                if (count($key))
                 {
                    $sWhere = implode("','", $aB);
                    $sWhere = "('".$sWhere."')";
                 }
                if ($sWhere) $sWhere = "AND `aqb_pc_members_blocks`.`id` IN {$sWhere}";                            }
                $sql = "SELECT * FROM `aqb_pc_members_blocks` WHERE `owner_id` = '$parent_ID' ".$sWhere;
                //echo $sql."<br/>";
                $result = mysql_query($sql);
                $nos = mysql_num_rows($result);
                if ($nos >= 1)
                {
                    while($row = mysql_fetch_array($result))
                    {
                        $aqbblock                           = array();
                        $aqbblock['blockname']              = $row['title']."_AQB_1";
                        $aqbblock['blockcontent']           = $row['content'];
                        $aqbblock['blockheading']           = $row['title'];
                        $profileblockArray[]                = $aqbblock;
                    }
                }
     }
     
     $getAboutus                           = getAboutUS($aCopA);
     $Aboutusblock                         = array();
     $Aboutusblock['blockname']            = "aboutUS_OTH_1";
     $Aboutusblock['blockcontent']         = $getAboutus;
     $Aboutusblock['blockheading']         = "About Us";
     $profileblockArray[]                  = $Aboutusblock;

     if($aCopA['ProfileType'] == 2)
     {
         $getchildpref                         = getChildPreferences($aCopA);
         $childPrefblock                       = array();
         $childPrefblock['blockname']          = "childPerf_OTH_1";
         $childPrefblock['blockcontent']       = $getchildpref;
         $childPrefblock['blockheading']       = "Child Preferences";
     $profileblockArray[]                  = $childPrefblock;
     }
     return $profileblockArray;
    
}

function getChildPreferences($aProfile)
{
   //print_r($aProfile);
   
   $chparray['Child Age']                           = $aProfile['ChildAge'];
   $chparray['Child Ethnicity']                     = $aProfile['ChildEthnicity'];
   $chparray['Child Gender']                        = $aProfile['ChildGender'];
   //$chparray['Chld Desired']                        = $aProfile['ChildDesired'];
   $chparray['Special Needs']                       = $aProfile['ChildSpecialNeeds'];
   //$chparray['Special Need Options']                = $aProfile['SpecialNeedsOptions'];
   //$chparray['BirthFatherStatus']                   = $aProfile['BirthFatherStatus'];
   //$chparray['Drugs & Alcohol during pregnancy']    = $aProfile['DrugsAlcohol'];
   //$chparray['Drinking']                            = $aProfile['BPDrinking'];
   //$chparray['SmokingDuringPregnancy']              = $aProfile['SmokingDuringPregnancy'];
   //$chparray['BPFamilyHistory']                     = $aProfile['BPFamilyHistory'];
   $chparray['Openness']                            = $aProfile['Openness'];

   //print_r($chparray);
   $childPref   = '<style type="text/css">
        .form_advanced_table {
            font-size:11px;
            position:relative;
            width:100%;
            border:1px solid #DADADA;
            font-size :15px;
            color:#333333;
            }
            .caption {
            padding-left:10px;
            padding-top:11px;
            text-align:left;
            width:200px;
            border:1px solid #DADADA;
            height:35px;
            }
            .value {
            padding-left:10px;
            padding-top:11px;
            border:1px solid #DADADA;
            height:35px;
            font-family:Trebuchet,Trebuchet MS,Helvetica,sans-serif;
            font-style:normal;
            font-weight:bold;
            }
       </style>
       <table cellspacing="0" cellpadding="0" class="form_advanced_table">
                <tbody>';
        foreach($chparray as $key=>$value)
        {
            
            if($value)
            {               
                $childPref .= '<tr>
                    <td class="caption">
                        '.$key.':
                    </td>
                    <td class="value">
                        <div class="clear_both"></div>
                        <div class="input_wrapper input_wrapper_value">
                            '.$value.'
                            <div class="input_close input_close_value"></div>
                        </div>
                        <div class="clear_both"></div>
                    </td>
                </tr>';
            }
        }
                     
        $childPref .= '</tbody></table>';        
   return $childPref;
}

function getAboutUS($aProfile)
{

    if($aProfile['Couple'])
		{$_coupleProf=getProfileInfo($aProfile['Couple']);
        $sRet='  <style type="text/css">
            .trstyle{
            border:1px solid #375778;
            border-top:none;
            height:35px;
            }
            .tdstyle{
            padding-top:6px;
            width:400px;
            }
            </style><div style="width: auto;height: auto; background-color:#C2D3FF;padding:20px;">
          <div style="width: auto;height: auto; background-color:#EEF6FF;">
              <table width="100%" border="0" style="border: solid 0.2px #375778;" cellpadding="0" cellspacing="0">
              <tr style=" background-color: #375778; color: #EEF6FF; font-size: 16px; font-weight: bold;height:35px;">
                  <td align="left" style="width:400px;" valign="middle">
                  </td>
                  <td align="left" valign="middle" class="tdstyle">
                      &nbsp;   '.$aProfile["FirstName"].'
                  </td>
                  <td align="left" valign="middle" class="tdstyle">
                    &nbsp;   '.$_coupleProf["FirstName"].'
                  </td>
              </tr>
';
      if($aProfile['DateOfBirth']!='0000-00-00'){
$sRet.='
              <tr class="trstyle">
                  <td  align="left" style="background-color: #E3E9F1;width:400px; color: #375778;font-size: 14px; font-weight: bold;width: 30%;" class="tdstyle">
                      &nbsp;Age:
                  </td>
                  <td  align="left" class="tdstyle">
                       &nbsp;  '.calc_age($aProfile['DateOfBirth']).' Years
                  </td>
                  <td align="left" class="tdstyle">
                      &nbsp;  '.calc_age($_coupleProf['DateOfBirth']).' Years

                  </td>
              </tr>
';
      }
$sRet.='

              <tr class="trstyle">
                  <td align="left" style="padding-top:6px;background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;" class="tdstyle">
                      &nbsp;Education:
                  </td>
                  <td align="left" class="tdstyle">
                    &nbsp; '.$aProfile['Education'].'
                  </td>
                  <td align="left" class="tdstyle">
                     &nbsp; '.$_coupleProf['Education'].'
                  </td>
              </tr>
              <tr class="trstyle">
                  <td align="left" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;" class="tdstyle">
                      &nbsp;Profession:
                  </td>
                  <td align="left" class="tdstyle">
                          &nbsp; '.$aProfile['Occupation'].'
                  </td>
                  <td align="left" class="tdstyle">
                          &nbsp; '.$_coupleProf['Occupation'].'
                  </td>
              </tr>
              <tr class="trstyle">
                  <td align="left" class="tdstyle" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Ethnicity:
                  </td>
                  <td align="left" class="tdstyle">
                         &nbsp; '.$aProfile['Ethnicity'].'
                  </td>
                  <td align="left" class="tdstyle">
                       &nbsp; '.$_coupleProf['Ethnicity'].'
                  </td>
              </tr>
              <tr class="trstyle">
                  <td align="left" class="tdstyle" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Religion:
                  </td>
                  <td align="left" class="tdstyle">
                          &nbsp; '.$aProfile['Religion'].'
                  </td>
                  <td align="left" class="tdstyle">
                       &nbsp; '.$_coupleProf['Religion'].'
                  </td>
              </tr>
               <tr class="trstyle">
                  <td align="left" class="tdstyle" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Smoking:
                  </td>
                  <td align="left" class="tdstyle">
                         &nbsp; '.$aProfile['Smoking'].'
                  </td>
                  <td align="left" class="tdstyle">
                       &nbsp; '.$_coupleProf['Smoking'].'
                  </td>
              </tr>

              <tr class="trstyle">

                  <td align="left" colspan="3">
                      &nbsp;
                  </td>
              </tr>
                 <tr class="trstyle">
                  <td align="left" class="tdstyle" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;State:
                  </td>
                  <td align="left" colspan="2" class="tdstyle">
                       &nbsp; '.$aProfile['State'].'
                  </td>

              </tr>
              <tr class="trstyle">
                  <td align="left" class="tdstyle" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Married:
                  </td>
                  <td align="left" colspan="2" class="tdstyle">
                            &nbsp; '.$aProfile['YearsMarried'].'
                  </td>

              </tr>
              <tr class="trstyle">
                  <td align="left" class="tdstyle" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Residency:
                  </td>
                  <td align="left" colspan="2" class="tdstyle">
                          &nbsp; '.$aProfile['Residency'].'
                  </td>

              </tr>
              <tr class="trstyle">
                  <td align="left" class="tdstyle" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Neighborhood:
                  </td>
                  <td align="left" colspan="2" class="tdstyle">
                           &nbsp; '.$aProfile['Neighborhood'].'
                  </td>

              </tr>
              <tr class="trstyle">
                  <td align="left" class="tdstyle" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Family Structure:
                  </td>
                  <td align="left" colspan="2" class="tdstyle">
                           &nbsp; '.$aProfile['FamilyStructure'].'
                  </td>

              </tr>
              <tr class="trstyle">
                  <td align="left" class="tdstyle" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Pet(s):
                  </td>
                  <td align="left" colspan="2" class="tdstyle">
                        &nbsp; '.$aProfile['Pet'].'
                  </td>

              </tr>
</table>
          </div>
      </div>
';
 }else{
	        $sRet='<style type="text/css">
            .trstyle{
            border:1px solid #375778;
            border-top:none;
            height:35px;
            }
            .tdstyle{
            padding-top:6px;
            width:400px;
            }
            </style>
    <div style="width: auto;height: auto; background-color:#C2D3FF;padding:20px;">
          <div style="width: auto;height: auto; background-color:#EEF6FF;">
              <table width="100%" border="0" style="border: solid 0.2px #375778;" cellpadding="0" cellspacing="0">
              <tr style=" background-color: #375778; color: #EEF6FF; font-size: 16px; font-weight: bold;">
                  <td>
                  </td>
                  <td align="left">
                      &nbsp;   '.$aProfile['FirstName'].'
                  </td>
                  <td align="left">
                    &nbsp;   '.$_coupleProf['FirstName'].'
                  </td>
              </tr>
';
     if($aProfile['DateOfBirth']!='0000-00-00'){
$sRet.='
              <tr class="trstyle">
                  <td align="left" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;width: 30%;" class="tdstyle">
                      &nbsp;Age:
                  </td>
                  <td class="tdstyle" align="left" colspan="2">
                       &nbsp;  '.calc_age($aProfile['DateOfBirth']).' Years
                  </td>

              </tr>
';
      }
$sRet.='
              <tr class="trstyle">
                  <td class="tdstyle" align="left" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Education:
                  </td>
                  <td class="tdstyle" align="left" colspan="2">
                    &nbsp; '.$aProfile['Education'].'
                  </td>

              </tr>
              <tr class="trstyle">
                  <td class="tdstyle" align="left" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Profession:
                  </td>
                  <td class="tdstyle" align="left" colspan="2">
                          &nbsp; '.$aProfile['Occupation'].'
                  </td>

              </tr>
              <tr class="trstyle">
                  <td class="tdstyle" align="left" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Ethnicity:
                  </td>
                  <td class="tdstyle" align="left" colspan="2">
                         &nbsp; '.$aProfile['Ethnicity'].'
                  </td>

              </tr>
              <tr class="trstyle">
                  <td class="tdstyle" align="left" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Religion:
                  </td>
                  <td class="tdstyle" align="left" colspan="2">
                          &nbsp; '.$aProfile['Religion'].'
                  </td>

              </tr>
               <tr class="trstyle">
                  <td class="tdstyle" align="left" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Smoking:
                  </td>
                  <td class="tdstyle" align="left" colspan="2">
                         &nbsp; '.$aProfile['Smoking'].'
                  </td>

              </tr>

              <tr class="trstyle">

                  <td class="tdstyle" align="left" colspan="3">
                      &nbsp;
                  </td>
              </tr>
                 <tr class="trstyle">
                  <td class="tdstyle" align="left" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;State:
                  </td>
                  <td class="tdstyle" align="left" colspan="2">
                       &nbsp; '.$aProfile['State'].'
                  </td>

              </tr>
              <tr class="trstyle">
                  <td class="tdstyle" align="left" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Married:
                  </td>
                  <td class="tdstyle" align="left" colspan="2">
                            &nbsp; '.$aProfile['YearsMarried'].'
                  </td>

              </tr>
              <tr class="trstyle">
                  <td class="tdstyle" align="left" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Residency:
                  </td>
                  <td class="tdstyle" align="left" colspan="2">
                          &nbsp; '.$aProfile['Residency'].'
                  </td>

              </tr>
              <tr class="trstyle">
                  <td class="tdstyle" align="left" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Neighborhood:
                  </td>
                  <td class="tdstyle" align="left" colspan="2">
                           &nbsp; '.$aProfile['Neighborhood'].'
                  </td>

              </tr>
              <tr class="trstyle">
                  <td class="tdstyle" align="left" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Family Structure:
                  </td>
                  <td class="tdstyle" align="left" colspan="2">
                           &nbsp; '.$aProfile['FamilyStructure'].'
                  </td>

              </tr>
              <tr class="trstyle">
                  <td class="tdstyle" align="left" style="background-color: #E3E9F1; color: #375778;font-size: 14px; font-weight: bold;">
                      &nbsp;Pet(s):
                  </td>
                  <td class="tdstyle" align="left" colspan="2">
                        &nbsp; '.$aProfile['Pet'].'
                  </td>

              </tr>
</table>
          </div>
      </div>
';
}
return $sRet;
}

function calc_age($birth_date){
		if ( $birth_date == "0000-00-00" )
		return _t("_uknown");

	$bd = explode( "-", $birth_date );
	$age = date("Y") - $bd[0] - 1;

	$arr[1] = "m";
	$arr[2] = "d";

	for ( $i = 1; $arr[$i]; $i++ ) {
		$n = date( $arr[$i] );
		if ( $n < $bd[$i] )
			break;
		if ( $n > $bd[$i] ) {
			++$age;
			break;
		}
	}

	return $age;
}

?>