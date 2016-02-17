<?php
session_start();
define('BX_PROFILE_PAGE', 1);
require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
bx_import('BxTemplProfileView');
bx_import('BxDolInstallerUtils');
ini_set('memory_limit','1024M');

require_once ('phpfunctions.php');
//require_once ('generatePDF.php');
//require_once('PDFreactor/wrappers/php/lib/PDFreactor.class.php');
require_once('resizeImage.php');
require_once('rotate_img.php');
ini_set('memory_limit','1024M');

echo "Start <br/><br/>";
$getparents     = "SELECT `ID` ,`ProfileType`, `NickName`, FirstName, LastName FROM `Profiles`  WHERE `Status` = 'Active'  AND `ProfileType` !=8";
$result_parents = mysql_query($getparents);
$createCounter    = 0;
$totalounter      = 0;
$existsounter     = 0;
while($row_parebts = mysql_fetch_assoc($result_parents))
{
    $totalounter      += 1;
    //print_r($row_parebts);
    //echo "<br/>";
    $parentID         = $row_parebts['ID'];
    $parenttype       = $row_parebts['ProfileType'];
    //echo "parent id ".$parentID."<br/>";
    //echo "parent type ".$parenttype."<br/>";
    //echo "parent name ".$row_parebts['NickName']."<br/>";
    $getuserPDFCount         = "SELECT template_user_id,isDeleted,isDefault FROM `pdf_template_user` WHERE `user_id` = $parentID AND isDeleted = 'N'";
    $tempPDFCOunt     = mysql_query($getuserPDFCount);

    /*if($parenttype == 2)
        continue;
    if($parenttype == 4)
        exit();
     */
    //echo "Create counter value ".$createCounter." ccc ".mysql_numrows($tempPDFCOunt)."<br/>";
    $userPDFCount     = 0;
    if(mysql_numrows($tempPDFCOunt) > 0)
    {

        $userPDFCount   = mysql_numrows($tempPDFCOunt);
    }
    if(!$userPDFCount)
    {
       print_r($row_parebts);
       echo "<br/>";
       echo "Pdf Creating counter ".$createCounter."<br/>";
       echo "parent id ".$parentID."<br/>";
       echo "parent type ".$parenttype."<br/>";
       echo "parent name ".$row_parebts['NickName']."<br/>";
       try
       {
           $log_file   = $GLOBALS['dir']['root']."PDFTemplates/parent_log_".date("m-d-Y").".txt";
           ob_start();
           if($createCounter < 300)
           {
           	    //echo "PDF CREATED for ******".$row_parebts['NickName']."<br/>";
                //$createCounter  += 1;
                if($parentID == 2699)
                {
                    $genObject      = new generatePDF($parentID,$parenttype);
                    $fileName       = $genObject->genWKHTML();

                    if($fileName == 'no data')
                    {

                            echo "counter ".$createCounter."\n";
                            echo "No Data for creating PDF for parent name ".$row_parebts['NickName']."\n";
                            echo "parent id ".$parentID."\n";
                            echo "There is No data\n";
                            echo "****************************************************************************\n";
                            $createCounter  += 1;
                    }
                    else
                    {
			  $parent_Name      = ($row_parebts['LastName'])?$row_parebts['FirstName']." ".$row_parebts['LastName']:$row_parebts['FirstName'];
                       $email_id        = "arun@mediaus.com";
                       $email_subject   = "PDF Generated for user - ".$parent_Name;
                       $email_body      = "Default Profile PDf has been created for the user '".$parent_Name."'";
                       sendMail("arun@mediaus.com", $email_subject, $email_body);
			   echo " parent name ".$parent_Name."\n";
                       echo "counter ".$createCounter."\n";
                       echo "PDF Ceated for parent name ".$row_parebts['NickName']."\n";
                       echo "parent id ".$parentID."\n";
                       echo "Profile PDF has been created \n";
                       echo "****************************************************************************\n";

                    }
                }
                else
                    continue;
               

           }
           else                 
              continue;
               
           
           $out1 = ob_get_contents();
           ob_end_clean();
           $log = fopen($log_file, 'a+') or die("can't open file");
           fwrite($log, $out1 );
           fclose($log);           
       }
       catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n<br/>";
       }
    }
    else
    {
        $existsounter       += 1;
        while($row_pdfcount = mysql_fetch_assoc($tempPDFCOunt))
        {
            $getDefaultPDF      = getuserDefaultPDF1($parentID);
            if(!$getDefaultPDF)
            {
                echo "makedefault set ".$row_pdfcount['template_user_id']."<br/>";
                if($row_pdfcount['isDeleted'] == 'N')
                {
                    $set_template_user_id   = $row_pdfcount['template_user_id'];
                    $update_setdefalutPDF   = "UPDATE pdf_template_user SET isDefault ='Y' WHERE template_user_id =$set_template_user_id";
                    mysql_query($update_setdefalutPDF);
                    break;
                }
            }
            else
            {
                echo "already default pdf set ".$getDefaultPDF."<br/>";
                break;
            }

        }
    }
    echo "<br/>";
}
echo "Total counter ".$totalounter." <br/>";
echo "Create counter ".$createCounter." <br/>";
echo "Exist PDF counter ".$existsounter." <br/>";
echo "End <br/>";
exit();

function getuserDefaultPDF1($userID)
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
class generatePDF
{
    public $baseurl;
    public $basepath;
    public $templateID;
    public $parentData;
    public $partnerData;
    public $parentID;
    public $phototalbumArray;
    public $phototcommentArray;
    public $coverTitle;
    public $blockTitle;
    public $pictureTitle;
    public $templateFilepath;
    public $template_user_id;
    public $tempDescripton;

    public function __construct($pID=NULL,$pType=NULL)
    {

        global $baseurl,$aCopA,$aCopB,$parentid,$basepath;
        //print_r($_POST);
        //echo date("Y-m-d")."<br/>";
        $getPhotos = getPhotoAlbums($pID);
        //print_r($getPhotos);
        $photo_list             = "";
        $phCounter              = 1;
        if($getPhotos)
        {
            foreach($getPhotos as $photos)
            {
                if($phCounter >6)
                    break;
                $filename = $GLOBALS['dir']['root'].'modules/boonex/photos/data/files/'.$photos['ID'].'.'.$photos['Ext'];
                $file_medium_name = $GLOBALS['dir']['root'].'modules/boonex/photos/data/files/'.$photos['ID'].'_m.'.$photos['Ext'];

                if (file_exists($filename) && file_exists($file_medium_name))
                {
                    if($phCounter == 1)
                        $cover_photo    = $photos['Hash'];
                    if($photo_list)
                        $photo_list     .= ",".$photos['Hash'];
                    else
                        $photo_list     = $photos['Hash'];
                    $phCounter          += 1;
                }
            }
        }
	 
        $this->_currentTime					=	date("Y-m-d H:i:s",time());
        $parent_ID              = $pID;
        $oProfile               = new BxBaseProfileGenerator($parent_ID);
        $aCopA                  = $oProfile->_aProfile;
        $aCopB                  = $oProfile->_aCouple;
        $this->templateID       = 2;//110;//2;
        $this->baseurl          = $GLOBALS['site']['url'];
        $this->basepath         = $GLOBALS['dir']['root'];
        $this->parentData       = $aCopA;
        $this->partnerData      = $aCopB;
        $this->parentID         = $parent_ID; //59
        $this->parentName       = ($this->partnerData['FirstName'])?ucfirst($this->parentData['FirstName'])." & ".ucfirst($this->partnerData['FirstName']):ucfirst($this->parentData['FirstName']);
        $this->tempDescripton   = stripslashes(htmlentities('Default Profile PDF', ENT_QUOTES));
        $this->coverTitle       = stripslashes(htmlentities($this->parentName, ENT_QUOTES));
        $this->blockTitle       = stripslashes(htmlentities($this->parentName, ENT_QUOTES));
        $this->pictureTitle     = stripslashes(htmlentities($this->parentName, ENT_QUOTES));
        $this->coverImage       = $cover_photo;
        if($pType == 2)
            $this->blockContPost    = 'BPletter_PRF_2';
        else if($pType == 4)
        {
            if($aCopA['DescriptionMe'])
                $this->blockContPost    = 'DescriptionMe_PRF_1';
            else
                $this->blockContPost    = 'Not available';
        }
        $this->pictureSelect    = $photo_list;
        $this->photodescrpton   = "";
        $this->blockContent     = "";
        $this->BlockArray       = getAllBlockData($this->parentID);

        $this->blockContent     = $this->blockContPost;
        //echo "baseurl ".$this->baseurl."<br/>";
        //echo "basepath ".$this->basepath."<br/>";
        //echo "parentID ".$this->parentID."<br/>";
        //echo "parentName ".$this->parentName."<br/>";
        //echo "tempDescripton ".$this->tempDescripton."<br/>";
        //echo "coverTitle ".$this->coverTitle."<br/>";
        //echo "blockTitle ".$this->blockTitle."<br/>";
        //echo "pictureTitle ".$this->pictureTitle."<br/>";
        echo "coverImage ".$this->coverImage."\n";
        echo "blockContPost ".$this->blockContPost."\n";
        echo "pictureSelect ".$this->pictureSelect."\n";
        //exit();
        //echo "defaultPDF ";



    }

    //create cover page
    public function coverPage($fName=NULL)
    {
        $htmlFile   = $this->basepath."PDFTemplates/".$this->templateID."/cover.html";
        //echo "file name ".$htmlFile."<br/>";
        $file_handle = fopen($htmlFile, "r");
        $coverConetnt  = "";
        while (!feof($file_handle)) {
           $line = fgets($file_handle);
           if($line)
           {
            preg_match ("/##(.*)##/", $line, $img_sett);
            if($img_sett)
                $this->coverImageArray[] = $img_sett[1];
           }
           $coverConetnt .= (trim(htmlspecialchars($line)))?htmlspecialchars($line)."\n":"";
        }
        fclose($file_handle);
        $coverConetnt               = $this->removeSPHTML($coverConetnt);
        $resetimg_path              = $this->baseurl.'PDFTemplates/'.$this->templateID."/images/";
        $coverConetnt               = str_replace('images/', $resetimg_path, $coverConetnt);

        $coverConetnt               = $this->replaceCoverVar($coverConetnt);

        return $coverConetnt;
    }
    //crate block page
    public function blockPage($blockID=NULL)
    {
        $htmlFile   = $this->basepath."PDFTemplates/".$this->templateID."/block.html";
        //echo "file name ".$htmlFile."<br/>";
        $file_handle = fopen($htmlFile, "r");
        $blockContent  = "";
        while (!feof($file_handle)) {
           $line = fgets($file_handle);
           $blockContent .= (trim(htmlspecialchars($line)))?htmlspecialchars($line)."\n":"";
        }
        fclose($file_handle);
        //$issuFile                   = $this->templateID."/issue.html";
        //file_put_contents($issuFile, $blockContent);
        $blockContent               = $this->removeSPHTML($blockContent);
        $resetimg_path              = $this->baseurl.'PDFTemplates/'.$this->templateID."/images/";
        $blockContent               = str_replace('images/', $resetimg_path, $blockContent);

        $blockContent               = $this->replaceBlockVar($blockContent,$blockID);

        return $blockContent;
    }

    //create photo page
    public function photoPage()
    {
        $htmlFile   = $this->basepath."PDFTemplates/".$this->templateID."/photos.html";
        //echo "file name ".$htmlFile."<br/>";
        $file_handle = fopen($htmlFile, "r");
        $photoContent       = "";
        $this->phototalbumArray   = array();
        $this->phototcommentArray = array();
        while (!feof($file_handle)) {
           $line = fgets($file_handle);
           if($line)
           {
            preg_match ("/##image(.*)##/", $line, $img_sett);
            if($img_sett)
                $this->phototalbumArray[] = "image".$img_sett[1];
            preg_match ("/##photo(.*)##/", $line, $img_comment_sett);
            if($img_comment_sett)
                $this->phototcommentArray[] = "photo".$img_comment_sett[1];
           }
           $photoContent .= (trim(htmlspecialchars($line)))?htmlspecialchars($line)."\n":"";
        }
        fclose($file_handle);
        //print_r($this->phototalbumArray);
        //exit();
        //$issuFile                   = $this->templateID."/issue.html";
        //file_put_contents($issuFile, $blockContent);
        $photoContent               = $this->removeSPHTML($photoContent);
        $resetimg_path              = $this->baseurl.'PDFTemplates/'.$this->templateID."/images/";
        $photoContent               = str_replace('images/', $resetimg_path, $photoContent);
        //echo "photo ".$photoContent." aa ";
        return $photoContent;
    }

    //replace cover variables
    public function replaceCoverVar($cvrCnt)
    {
        $parentname                 = ($this->partnerData['FirstName'])?ucfirst($this->parentData['FirstName'])." & ".ucfirst($this->partnerData['FirstName']):ucfirst($this->parentData['FirstName']);

        $sel_cover_photo            = selectedCoverImage($this->parentID,$this->coverImage);

        if($sel_cover_photo)
             $imagepath     = $GLOBALS['site']['url']."m/photos/get_image/file/".$sel_cover_photo['Hash'].".".$sel_cover_photo['Ext'];
        else
             $imagepath     = '';
        //echo "image path ".$imagepath."<br/>";

        if($this->coverImageArray)
        {
            list($tempvar,$containreWidth,$containerHeight,$rotateAngle,$imgtag,$specialeffects) = split('_',$this->coverImageArray[0]);
            $sessionID      = session_ID()."_cover";
            $rotateAngle    = ($rotateAngle)?$rotateAngle:0;
            if($imagepath &&  $this->checkurlFileexists($imagepath))
            {
                    $imageResolution    = getUploadImageResolution($imagepath,$containerHeight,$containreWidth);   
            }
            else
            {
                        return 101;
            }

            /*if($coverimage)
                $cvrCnt           = str_replace('##'.$this->coverImageArray[0].'##', $coverimage, $cvrCnt);
            else
                $cvrCnt           = str_replace('##'.$this->coverImageArray[0].'##', "", $cvrCnt);
            $cvrCnt               = str_replace('#*#coverTitle#*#', $this->coverTitle, $cvrCnt);*/
            if($coverimage || ($imageResolution))
            {
                if($specialeffects)
                {
                    $sp_var       = "-moz-border-radius: ".($imageResolution['imageWidth']/2)."px / ".($imageResolution['imageHeight']/2)."px;border-radius: ".($imageResolution['imageWidth']/2)."px / ".($imageResolution['imageHeight']/2)."px";
                    $coverimage   = '<img src="'.$imageResolution['profimage'].'" height="'.$imageResolution['imageHeight'].'" width ="'.$imageResolution['imageWidth'].'" style="border:8px solid #85B5D9;margin-top:'.$imageResolution['margintop'].'px;margin-left:'.$imageResolution['marginleft'].'px;'.$sp_var.'"/>';
                }
                else
                    $coverimage   = '<img src="'.$imageResolution['profimage'].'" height="'.$imageResolution['imageHeight'].'" width ="'.$imageResolution['imageWidth'].'" style="border:8px solid #85B5D9;margin-top:'.$imageResolution['margintop'].'px;margin-left:'.$imageResolution['marginleft'].'px;"/>';
                //echo "cover image ".$coverimage;
                $cvrCnt           = str_replace('##'.$this->coverImageArray[0].'##', $coverimage, $cvrCnt);
            }
            else
                $cvrCnt           = str_replace('##'.$this->coverImageArray[0].'##', "", $cvrCnt);
            $cvrCnt               = str_replace('#*#coverTitle#*#', $this->coverTitle, $cvrCnt);

        }

        return $cvrCnt;
    }
    //replace block variables
    public function replaceBlockVar($blckCnt,$blockID=NULL)
    {
        if($blockID == 'Not available')
        {
            $block_heading   = "Not available";
            $block_value     = "Not available";
        }
        else
        {
            if($this->BlockArray)
            {
                foreach($this->BlockArray as $blockdet)
                {
                    if($blockdet['blockname'] == $blockID)
                    {
                        $block_heading  = $blockdet['blockheading'];
                        $block_value    = $blockdet['blockcontent'];
                        break;
                    }
                    else
                    {
                       $block_heading   = "";
                       $block_value     = "";
                    }
                }
            }
            else
            {
                $block_heading   = "";
                $block_value     = "";
            }
        }
        //echo "<br/>";
        //print_r($blockID);
        //echo "<br/>";
        //echo "block contnet ".$block_heading."<br/>";
        //echo "block value ".$block_value."<br/>";
        if((!$blockID))
        {
            $blckCnt           = str_replace('##blockheading##', "", $blckCnt);
            $blckCnt           = str_replace('##blockcontent##', "", $blckCnt);
        }
        else
        {
            //if($block_heading)
                $blckCnt           = str_replace('##blockheading##', $block_heading, $blckCnt);
            //if($block_value)
                $blckCnt           = str_replace('##blockcontent##', $block_value, $blckCnt);
        }

        return $blckCnt;
    }
    //replace photo variables
    public function replacePhotoVar($mpdf=NULL,$pagenum_1=NULL)
    {

        $photoArray         = array();

        $photo_content      = "";
        $pictureArray       = split(',',$this->pictureSelect);
        $img_descrArray     = split('##,,,##',$this->photodescrpton);
        foreach($pictureArray as $phArray)
        {
            $sel_pic_photo        = selectedCoverImage($this->parentID,$phArray);
            if($sel_pic_photo)
            {
                $photoArray[] = $GLOBALS['site']['url']."m/photos/get_image/file/".$sel_pic_photo['Hash'].".".$sel_pic_photo['Ext'];
            }
            else
                $photoArray[]     = "";
        }
        $photo_cont                 = $this->photoPage();
        //print_r($photoArray);
        //exit();
        $TotalSEtCountArray =($this->phototalbumArray)?count($this->phototalbumArray):0;
        for($photocount=0;$photocount<count($photoArray);($photocount=($photocount+$TotalSEtCountArray)))
        {
            //echo "<br/>hai<br/>";
            $photo_cont                 = $this->photoPage();
            $numphotoCount              = count($this->phototalbumArray);
            $parentname                 = ($this->partnerData['FirstName'])?ucfirst($this->parentData['FirstName'])." & ".ucfirst($this->partnerData['FirstName']):ucfirst($this->parentData['FirstName']);

            for($phtcnt=0;$phtcnt<$numphotoCount;$phtcnt++)
            {
                $replace_var    = $phtcnt+1;
                $sessionID      = session_ID()."_".($photocount + $phtcnt);
                $rotateAngle    = ($rotateAngle)?$rotateAngle:0;
                //echo "session id ".$sessionID."<br/>";

                //echo $photoArray[$photocount + $phtcnt];
                if ($photoArray[$photocount + $phtcnt])
                {
                    //print_r($this->phototalbumArray);
                    if(($this->saveFlag == 'Save'|| ($this->regenrt_temp_id)) && (!$this->checkurlFileexists($photoArray[$photocount + $phtcnt])))
                        return 102;
                    list($tempvar,$containreWidth,$containerHeight,$rotateAngle) = split('_',$this->phototalbumArray[$phtcnt]);

                    $imagepath          = $photoArray[$photocount + $phtcnt];
                    $imageResolution    = getUploadImageResolution($imagepath,$containerHeight,$containreWidth);

                }
                else
                {
                    /*$no_image     = $GLOBALS['site']['url']."templates/base/images/icons/man_large.jpg";
                    list($tempvar,$containreWidth,$containerHeight,$rotateAngle) = split('_',$this->phototalbumArray[$phtcnt]);

                    $imagepath          = $no_image;
                    $imageResolution    = getUploadImageResolution($imagepath,$containerHeight,$containreWidth);
                    */
                    $imageResolution     = array();

                }
                if($photo_cont)
                {
                    if($imageResolution)
                        $selImage          = '<img src="'.$imageResolution['profimage'].'" height="'.$imageResolution['imageHeight'].'" width ="'.$imageResolution['imageWidth'].'" style="border:8px solid #85B5D9;margin-top:'.$imageResolution['margintop'].'px;margin-left:'.$imageResolution['marginleft'].'px;"/>';
                    else
                        $selImage          = "";
                    $photo_cont            = str_replace("##".$this->phototalbumArray[$phtcnt]."##", $selImage, $photo_cont);
                    //$photo_cont             = str_replace('##image2##', $coverimage, $photo_cont);
                    //$photo_cont             = str_replace('##image3##', $coverimage, $photo_cont);
                    //$photo_cont             = str_replace('##image4##', $coverimage, $photo_cont);
                    $sel_descrption        = ($img_descrArray[$photocount + $phtcnt])?$img_descrArray[$photocount + $phtcnt]:"";
                    $photo_cont            = str_replace("##".$this->phototcommentArray[$phtcnt]."##", $sel_descrption, $photo_cont);
                }
                if($photocount == 0)
                {
                    if($this->pictureTitle)
                        $photo_cont         = str_replace('#*#pictureTitle#*#', $this->pictureTitle, $photo_cont);
                    else
                        $photo_cont         = str_replace('#*#pictureTitle#*#', "", $photo_cont);
                }
                else
                    $photo_cont         = str_replace('#*#pictureTitle#*#', "", $photo_cont);
            }
            if($photocount == (count($photoArray) - $TotalSEtCountArray) || ($photocount - 1) == (count($photoArray) - $TotalSEtCountArray) || ($photocount - 2) == (count($photoArray) - $TotalSEtCountArray))
            {
                $agency_details     = getAgencyDetails($this->parentID);
                $footer = '<table>
                         <tr>
                             <td width="103">Agency Name</td>
                             <td width="10">:</td>
                             <td>'.$agency_details[0].'</td>
                             <td width="10"></td>
                             <td width="66">Address</td>
                             <td width="10">:</td>
                             <td>'.$agency_details[85].', '.$agency_details[14].', '.$agency_details[67].' '.$agency_details[23].', '._t($GLOBALS['aPreValues']['Country'][$agency_details[13]]['LKey']).'</td>
                             <td width="10"></td>
                             <td width="50">Phone</td>
                             <td width="10">:</td>
                             <td>'.$agency_details[82].'</td>
                             <td width="10"></td>
                             <td width="43">email</td>
                             <td width="10">:</td>
                             <td>'.$agency_details[3].'</td>
                         </tr>
                        </table>';
                //echo $footer;
                $photo_cont         = str_replace('#*#footervar#*#', $footer, $photo_cont);
            }
            else
                $photo_cont         = str_replace('#*#footervar#*#', "", $photo_cont);
            $photo_content           .= $photo_cont;
            //$photo_content           .= $this->nextPage();
            if($mpdf)
            {
                $mpdf->AddPage('','',0,'','',0,0,0,0);
                $mpdf->SetHTMLFooter($pagenum_1);
                $mpdf->pagenumPrefix = 'Page ';
                //$mpdf1->WriteHTML($stylesheet,1);
                $mpdf->WriteHTML($photo_cont);
            }

        }
            //exit();

        return $photo_content;
    }

    //crate next page break
    public function nextPage()
    {
        $pageBreakiDiv = '<div style="page-break-before: always;"></div>';
        return $pageBreakiDiv;
    }

    //remove special characters from read HTML
    public function removeSPHTML($readConetnt)
    {
        $specialelements      = array("&lt;", "&gt;", "&quot;");
        $specialelements_rep  = array("<", ">", '"');
        for($spcount =0; $spcount < count($specialelements) ;$spcount++)
        {
            $readConetnt      = str_replace($specialelements[$spcount], $specialelements_rep[$spcount], $readConetnt);
        }
        return $readConetnt;
    }


    public function genWKHTML()
    {
	if(!($this->pictureSelect))
            return "no data";
	//else
	//     return 1;
	
        $createdhtml            = "";
        try {
            $createdhtml            .= '<link rel="stylesheet" href="'.$this->baseurl.'PDFTemplates/'.$this->templateID.'/parentfinder.css" type="text/css"/>';
            if($this->coverImage)
            {
                $covePage                = $this->coverPage();
                //echo "<br/>aaaaaaaaa ".$covePage;
                if($covePage == 101)
                {
                    return 101;
                }
                else
                    $createdhtml           .= $covePage;
            }
            //$createdhtml           .= $this->nextPage();
            if($this->blockContent)
            {
                $blockseleIDS       = split(",",$this->blockContent);
                echo "aaa aa ";print_r($blockseleIDS);echo "<br/>";
                for($blck_cnt=0;$blck_cnt<count($blockseleIDS);$blck_cnt++)
                {
                    echo "inside   <br/>";
                    $createdhtml           .= $this->blockpage($blockseleIDS[$blck_cnt]);
                    //$createdhtml           .= $this->nextPage();
                }

                $createdhtml           .= $this->nextPage();
                //$createdhtml           .= $this->photoPage();
            }
            if($this->pictureSelect)
            {
                $photoPage                = $this->replacePhotoVar();
                if($photoPage == 102)
                {
                    return 102;
                }
                else
                    $createdhtml           .= $photoPage;
            }
            //$createdhtml           .= $this->addExtra();
            //echo $createdhtml;
            //exit();
        }
        catch (Exception $e) {
            //echo 'Caught exception: ',  $e->getMessage(), "\n";
            return 100;
        }
        //echo "aaa ".$this->_previewFlag;


        $savedFile                  = $this->saveTemplateDetails();

        $this->templateFilepath     = $this->basepath."PDFTemplates/user/".$this->templateID."_".$this->parentID."_".$this->template_user_id.".pdf";
        $htmlFile                   = $this->basepath."PDFTemplates/user/".$this->templateID."_".$this->parentID."_".$this->template_user_id.".html";
        $html_File                  = $this->basepath."PDFTemplates/user/".$this->templateID."_".$this->parentID."_".$this->template_user_id.".html";
        $pdfFile                    = $this->templateFilepath;
        $pdf_urlFile                = $this->baseurl."PDFTemplates/user/".$this->templateID."_".$this->parentID."_".$this->template_user_id.".pdf";


        file_put_contents($htmlFile, $createdhtml);
        chmod($htmlFile, 0777);

        $file           = $pdfFile;
        //$fileName       = $this->parentID.'_'.$this->templateID."_preview_wkhtml.pdf";
        $tempDetails                = gettemplateDetails($this->templateID);
	 //print_r($tempDetails);
	 //echo "<br/> template id ".$this->templateID."<br/>";
        $tempMode                   = ($tempDetails['printMode'] == "P")?'Portrait':'Landscape';
        echo "template mode ".$tempMode;
        if (stristr (PHP_OS, 'WIN'))
            $pdf_command            = "..\\wkhtmltopdf\\wkhtmltopdf -L 0 -B 0 -T 0 -R 0 -O $tempMode -s Letter $html_File $pdfFile";
        else
            $pdf_command            = "/usr/local/bin/wkhtmltopdf -L 0 -B 0 -T 0 -R 0 -O $tempMode -s Letter $html_File $pdfFile";
        echo " command aaa ".$pdf_command."\n";
        exec($pdf_command);
        unlink($htmlFile);
        $updateFilePath              = $this->updateFilePath();


        $d = dir($this->basepath."PDFTemplates/temp");
        while (false !== ($entry = $d->read())) {
            if (strpos($entry, session_ID()) === 0){
               unlink($this->basepath."PDFTemplates/temp/".$entry);
            }
        }
        $d->close();

        /*$log_file   = $this->basepath."PDFTemplates/batch_log.txt";
        ob_start();
        echo "parent name ".$this->parentName."\n";
        echo "parent id ".$this->parentID."\n";
        echo "file name ".$pdfFile."\n";
        echo "template user id  ".$this->template_user_id."\n";
        //echo "pdf template user data id ".$this->parentName."\n";
        echo "****************************************************************************\n";
        $out1 = ob_get_contents();
        ob_end_clean();
        $log = fopen($log_file, 'a+') or die("can't open file");
        fwrite($log, $out1 );
        fclose($log);*/
         echo " exit \n";
        return $pdf_urlFile;
    }

    public function saveTemplateDetails()
    {

        $insert_tempuser         = "INSERT INTO pdf_template_user (user_id, template_id,template_file_path,template_description,isDeleted,isDefault,lastupdateddate)
        VALUES ($this->parentID,$this->templateID,'','$this->tempDescripton','N','Y','$this->_currentTime')";

        mysql_query($insert_tempuser);

        $this->template_user_id = mysql_insert_id();

        $insert_tempuser_data    = "INSERT INTO pdf_template_data (template_user_id, cover_title,cover_picture,block_ids,photo_title,photo_ids,photo_description)
        VALUES ($this->template_user_id,'$this->coverTitle','$this->coverImage','$this->blockContent','$this->pictureTitle','$this->pictureSelect','$this->photodescrpton')";

        mysql_query($insert_tempuser_data);

        return $this->template_user_id;

    }
    public function updateFilePath()
    {
         $update_filepath         = "UPDATE pdf_template_user SET template_file_path = '$this->templateFilepath' WHERE template_user_id = $this->template_user_id";
         mysql_query($update_filepath);
    }

    public function checkurlFileexists($furlName)
    {
       if (fopen($furlName, "r"))
       {
            return true;
       }
       else
       {
           return false;
       }

    }

}



?>