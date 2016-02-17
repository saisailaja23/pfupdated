<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php
session_start();
//define('BX_PROFILE_PAGE', 1);
//require_once( './inc/header.inc.php' );
//require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
//require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
//require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
//bx_import('BxTemplProfileView');
//bx_import('BxDolInstallerUtils');
ini_set('memory_limit','1024M');
//error_reporting(1);
//require_once ($dir['root'].'PDFUser/phpfunctions.php');
//$baseurl            = $GLOBALS['site']['url'];
//$basepath           = $GLOBALS['dir']['root'];
//$parentid           = 59;

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
    
    public function __construct($templateUserID=NULL)
    {
        
        global $baseurl,$aCopA,$aCopB,$parentid,$basepath;
       // print_r($_POST);exit();
        //echo date("Y-m-d")."<br/>";
        $this->_currentTime	 =	date("Y-m-d H:i:s",time());
        $this->_PDFTime          =	date("Y-m-d-H-i-s",time());
        //echo $this->_currentTime;
        
        if(!$templateUserID)
        {  
            $oProfile = new BxBaseProfileGenerator($parentid);
            $aCopA = $oProfile->_aProfile;
            $aCopB = $oProfile->_aCouple;
            $this->templateID       = $_POST['TemplateSel'];
            $this->baseurl          = $GLOBALS['site']['url'];
            $this->basepath         = $GLOBALS['dir']['root'];
            $this->parentData       = $aCopA;
            $this->partnerData      = $aCopB;
            $this->parentID         = getLoggedId(); //59
            $this->_previewFlag     = $_POST['previewFlag'];
            $this->tempDescripton   = stripslashes(htmlentities($_POST['temp_description'], ENT_QUOTES));
            $this->coverTitle       = stripslashes(htmlentities($_POST['CoverTitle'], ENT_QUOTES));
            $this->blockTitle       = stripslashes(htmlentities($_POST['BlockTitle'], ENT_QUOTES));
            $this->pictureTitle     = stripslashes(htmlentities($_POST['PictureTitle'], ENT_QUOTES));
            $this->coverImage       = $_POST['coverselect'];
            $this->blockContPost    = $_POST['blockselect'];
            $this->pictureSelect    = $_POST['photoselect'];
            $this->photodescrpton   = stripslashes(htmlentities($_POST['photodescription'], ENT_QUOTES));
            $this->template_user_id = ($_POST['template_user_id'])?$_POST['template_user_id']:"";
            $this->regenrt_temp_id  = "";
            $this->blockContent     = "";
            $this->BlockArray       = getAllBlockData($this->parentID);
            $this->saveFlag         = $_POST['do_save'];
        }
        else
        {           
           $this->regenrt_temp_id = ($templateUserID)?$templateUserID:"";
           $tempuserDetails        = getusertempDetails($this->regenrt_temp_id);
           //print_r($tempuserDetails);
           $parentid               = $tempuserDetails['user_id'];
           $oProfile               = new BxBaseProfileGenerator($parentid);
           $aCopA                  = $oProfile->_aProfile;
           $aCopB                  = $oProfile->_aCouple;
           $this->templateID       = $tempuserDetails['template_id'];
           $this->baseurl          = $GLOBALS['site']['url'];
           $this->basepath         = $GLOBALS['dir']['root'];
           $this->parentData       = $aCopA;
           $this->partnerData      = $aCopB;
           $this->parentID         = $tempuserDetails['user_id']; //59
           $this->_previewFlag     = "";
           $this->tempDescripton   = $tempuserDetails['template_description'];
           $this->coverTitle       = $tempuserDetails['cover_title'];
           $this->blockTitle       = $tempuserDetails['block_ids'];
           $this->pictureTitle     = $tempuserDetails['photo_title'];
           $this->coverImage       = $tempuserDetails['cover_picture'];
           $this->blockContPost    = $tempuserDetails['block_ids'];
           $this->pictureSelect    = $tempuserDetails['photo_ids'];
           $this->photodescrpton   = $tempuserDetails['photo_description'];
           $this->template_user_id = ($templateUserID)?$templateUserID:"";
           $this->blockContent     = "";
           $this->BlockArray       = getAllBlockData($this->parentID);
           $this->saveFlag         = $_POST['do_save'];
        }
        /*if($this->blockContPost)
        {
            //echo $this->blockContPost;
            if(is_array($this->blockContPost))
            {
                foreach($this->blockContPost as $blckval)
                {
                    $this->blockContent .= ($this->blockContent)?",".$blckval:$blckval;
                }
            }
            else
                $this->blockContent      = $this->blockContPost;
        }*/
        $this->blockContent      = $this->blockContPost;
        
        
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

        $sel_cover_photo        = selectedCoverImage($this->parentID,$this->coverImage);
        //print_r($sel_cover_photo);
        if($sel_cover_photo)
             $imagepath     = $GLOBALS['site']['url']."m/photos/get_image/file/".$sel_cover_photo['Hash'].".".$sel_cover_photo['Ext'];  
        else
             $imagepath     = '';
        
        if($this->coverImageArray)
        {
            list($tempvar,$containreWidth,$containerHeight,$rotateAngle,$specialeffects) = split('_',$this->coverImageArray[0]);
            $sessionID      = session_ID()."_cover";
            $rotateAngle    = ($rotateAngle)?$rotateAngle:0;
            if($imagepath &&  $this->checkurlFileexists($imagepath))
            {
                    //$imgName      = Rotate_img($imagepath,$rotateAngle,$sessionID);
                    //$resizeImg    = Resize_temp($imagepath,$containreWidth,$containerHeight,$sessionID);
                        $imageResolution    = getUploadImageResolution($imagepath,$containerHeight,$containreWidth);
            }
            else
            {
                
                if($this->saveFlag == 'Save' || ($this->regenrt_temp_id))
                        return 101;                
            }
           
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
        //print_r($blockID);
        if($this->_previewFlag && (!$blockID))
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
                    $photo_cont             = str_replace("##".$this->phototalbumArray[$phtcnt]."##", $selImage, $photo_cont);
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
                             <td>'.$agency_details[AgencyTitle].'</td>
                             <td width="10"></td>
                             <td width="66">Address</td>
                             <td width="10">:</td>
                             <td>'.$agency_details[Street_Address].', '.$agency_details[City].', '.$agency_details[State].' '.$agency_details[zip].', '._t($GLOBALS['aPreValues']['Country'][$agency_details[Country]]['LKey']).'</td>
                             <td width="10"></td>
                             <td width="50">Phone</td>
                             <td width="10">:</td>
                             <td>'.$agency_details[CLICK_TO_CALL].'</td>
                             <td width="10"></td>
                             <td width="43">email</td>
                             <td width="10">:</td>
                             <td>'.$agency_details[Email].'</td>
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
            //echo $photo_cont;
            //echo "<br/><br/> aaa <br/><br/>";

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
                $createdhtml .= '<div id="block_full_content" style="position:relative;height:auto;">';
                $blockseleIDS       = split(",",$this->blockContent);
                for($blck_cnt=0;$blck_cnt<count($blockseleIDS);$blck_cnt++)
                {
                    $createdhtml           .= $this->blockpage($blockseleIDS[$blck_cnt]);
                    //$createdhtml           .= $this->nextPage();
                }
                $createdhtml .= '</div>
                <script type="text/javascript"> 
                var offsetHeight    = document.getElementById("block_full_content").offsetHeight;
                var blockdivHeight  = (offsetHeight/1650);
                var actualHeight     = 1650 * Math.ceil(blockdivHeight); 
                document.getElementById("block_full_content").style.height = actualHeight+"px";
                </script>';
                //$createdhtml           .= $this->nextPage();
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
        if(!$this->_previewFlag && (!$this->regenrt_temp_id))
        {
            $savedFile              = $this->saveTemplateDetails();
        }
        if(!$this->_previewFlag)
        {
            $this->templateFilepath = $this->basepath."PDFTemplates/user/".$this->templateID."_".$this->parentID."_".$this->template_user_id."_".$this->_PDFTime.".pdf";
            $htmlFile               = $this->basepath."PDFTemplates/user/".$this->templateID."_".$this->parentID."_".$this->template_user_id.".html";
            $html_File              = $this->baseurl."PDFTemplates/user/".$this->templateID."_".$this->parentID."_".$this->template_user_id.".html";
            $pdfFile                = $this->templateFilepath;
            $pdf_urlFile            = $this->baseurl."PDFTemplates/user/".$this->templateID."_".$this->parentID."_".$this->template_user_id."_".$this->_PDFTime.".pdf";
        }
        else {
            $htmlFile               = $this->basepath."PDFTemplates/user/".$this->parentID.'_'.$this->templateID."_preview_wkhtml.html";
            $html_File              = $this->baseurl."PDFTemplates/user/".$this->parentID.'_'.$this->templateID."_preview_wkhtml.html";
            $pdfFile                = $this->basepath."PDFTemplates/user/".$this->parentID.'_'.$this->templateID."_preview_wkhtml_".$this->_PDFTime.".pdf";
            $pdf_urlFile            = $this->baseurl."PDFTemplates/user/".$this->parentID.'_'.$this->templateID."_preview_wkhtml_".$this->_PDFTime.".pdf";

        }
        file_put_contents($htmlFile, $createdhtml);
        chmod($htmlFile, 0777);    
        $tempDetails                = gettemplateDetails($this->templateID);
        $tempMode                   = ($tempDetails['printMode'] == "P")?'Portrait':'Landscape';
        
        if($this->_previewFlag || ($this->regenrt_temp_id))
        {
            $file           = $pdfFile;
            $fileName       = $this->parentID.'_'.$this->templateID."_preview_wkhtml_".$this->_PDFTime.".pdf";
            if (stristr (PHP_OS, 'WIN'))
                $pdf_command            = "..\\wkhtmltopdf\\wkhtmltopdf -L 0 -B 0 -T 0 -R 0 -O $tempMode -s Letter $html_File $pdfFile";
            else
                $pdf_command            = "/usr/local/bin/wkhtmltopdf -L 0 -B 0 -T 0 -R 0 -O $tempMode -s Letter $html_File $pdfFile";
            exec($pdf_command);
            unlink($htmlFile);
            if(($this->regenrt_temp_id))
                $updateFilePath              = $this->updateFilePath();
            
        }
        else
        {
            if (stristr (PHP_OS, 'WIN'))
                $pdf_command            = "..\\wkhtmltopdf\\wkhtmltopdf -L 0 -B 0 -T 0 -R 0 -O $tempMode -s Letter $html_File $pdfFile";
            else
                $pdf_command            = "/usr/local/bin/wkhtmltopdf -L 0 -B 0 -T 0 -R 0 -O $tempMode -s Letter $html_File $pdfFile";
            exec($pdf_command);
            if((!$this->regenrt_temp_id))
                $updateFilePath              = $this->updateFilePath();
            //echo $html_File."<br/>";
            unlink($htmlFile);
            //echo "hai";          
        }
        
        $d = dir($this->basepath."PDFTemplates/temp");
        //echo "Handle: " . $d->handle . "\n<br/>";
        //echo "Path: " . $d->path . "\n<br/>";
        while (false !== ($entry = $d->read())) {           
            if (strpos($entry, session_ID()) === 0){
               unlink($this->basepath."PDFTemplates/temp/".$entry);
            }
        }
        $d->close(); 
        /*$html_File1              = $this->baseurl."PDFTemplates/user/a/a.html";
        $pdfFile1                = $this->basepath."PDFTemplates/user/a/a.pdf";
        if (stristr (PHP_OS, 'WIN'))
            $pdf_command1            = "wkhtmltopdf\\wkhtmltopdf -L 0 -B 0 -T 0 -R 0 -O Landscape -s Letter $html_File1 $pdfFile1";
        else
            $pdf_command1            = "/usr/local/bin/wkhtmltopdf -L 0 -B 0 -T 0 -R 0 -O Landscape -s Letter $html_File1 $pdfFile1";
        exec($pdf_command1);*/
        
        //echo $createdhtml;
        //exit();
        return $pdf_urlFile;
    }

    public function saveTemplateDetails()
    {
        if($this->template_user_id)
        {
            //echo "update <br/>";
            $update_tempuser         = "UPDATE pdf_template_user SET template_description='$this->tempDescripton',lastupdateddate='$this->_currentTime',template_id = $this->templateID WHERE template_user_id =$this->template_user_id";

            mysql_query($update_tempuser);


            $templat_user_id    = gettempuserDataID($this->template_user_id);
            //echo "tempuser id ".$templat_user_id."<br/>";
            $update_tempuser_data    = "UPDATE pdf_template_data SET  cover_title ='$this->coverTitle',cover_picture='$this->coverImage',
            block_ids='$this->blockContent',photo_title='$this->pictureTitle',photo_ids='$this->pictureSelect',photo_description='$this->photodescrpton' WHERE template_user_id = $this->template_user_id";
            mysql_query($update_tempuser_data);
        }
        else
        {
            $getDefaultPDF      = getuserDefaultPDF($this->parentID);
            if(!$getDefaultPDF)
                $isDefaultFlag  = 'Y';
            else
                $isDefaultFlag  = 'N';

            $insert_tempuser         = "INSERT INTO pdf_template_user (user_id, template_id,template_file_path,template_description,isDeleted,isDefault,lastupdateddate)
            VALUES ($this->parentID,$this->templateID,'','$this->tempDescripton','N','$isDefaultFlag','$this->_currentTime')";

            mysql_query($insert_tempuser);

            $this->template_user_id = mysql_insert_id();

            $insert_tempuser_data    = "INSERT INTO pdf_template_data (template_user_id, cover_title,cover_picture,block_ids,photo_title,photo_ids,photo_description)
            VALUES ($this->template_user_id,'$this->coverTitle','$this->coverImage','$this->blockContent','$this->pictureTitle','$this->pictureSelect','$this->photodescrpton')";

            mysql_query($insert_tempuser_data);
        }
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

    public function addExtra()
    {
        $rtn    = '<html>
<head>
<link type="text/css" href="http://192.168.40.11:8090/parentfinders/PDFTemplates/107/parentfinder.css" rel="stylesheet">
<meta content="text/html" http-equiv="content-type">
<title>Sample site</title>
<style type="text/css">

</style>

</head>
<body style="page-break-after: auto; page:auto; border:0px solid red;background-image:url(\'http://192.168.40.11:8090/parentfinders/PDFTemplates/107/images/screen02.jpg\');height:auto;background-repeat:repeat;">
            <div id="temp1_infotitle_id01" style="height:auto;border:0px solid red;">
               <div id="temp1_infotitle_id02" style="height:auto;top:38px;">
                   <table >
                       <tr>
                           <td><span class="font_cl03">test</span></td>
                       </tr>
                       <tr>
                           <td><p>Arun</p><p>Arun</p><p>Arun</p><p>Arun</p><p>Arun</p><p>Arun</p><p>Arun</p><p>Arun</p>
                           <p>Dennis</p><p>Dennis</p><p>Dennis</p><p>Dennis</p><p>Dennis</p><p>Dennis</p><p>Dennis</p><p>Dennis</p>
                           <p>Shino</p><p>Shino</p><p>Shino</p><p>Shino</p><p>Shino</p><p>Shino</p><p>Shino</p><p>Shino</p><p>Shino</p>
                           <p>Hari</p><p>Hari</p><p>Hari</p><p>Hari</p><p>Hari</p><p>Hari</p><p>Hari</p><p>Hari</p><p>Hari</p><p>Hari</p>
                           <p>Sumi</p><p>Sumi</p><p>Sumi</p><p>Sumi</p><p>Sumi</p><p>Sumi</p><p>Sumi</p><p>Sumi</p><p>Sumi</p><p>Sumi</p>
                           <p>Renju</p><p>Renju</p><p>Renju</p><p>Renju</p><p>Renju</p><p>Renju</p><p>Renju</p><p>Renju</p><p>Renju</p>
                           <p>Lindo</p><p>Lindo</p><p>Lindo</p><p>Lindo</p><p>Lindo</p><p>Lindo</p><p>Lindo</p><p>Lindo</p><p>Lindo</p>
                           <p>Rinto</p><p>Rinto</p><p>Rinto</p><p>Rinto</p><p>Rinto</p><p>Rinto</p><p>Rinto</p><p>Rinto</p><p>Rinto</p>
                           <p>Rajesh</p><p>Rajesh</p><p>Rajesh</p><p>Rajesh</p><p>Rajesh</p><p>Rajesh</p><p>Rajesh</p><p>Rajesh</p></td>
                       </tr>
                   </table>
                </div>
                <div id="dummy_div" style="position:relative;border:0px solid blue;"><!--Dummy div--></div>
            </div>
<script type="text/javascript">
    bd_ht   = document.documentElement.clientHeight;   
    var remHt   = 1275-(bd_ht%1275)
    document.getElementById(\'dummy_div\').style.height = remHt+"px";
</script>
</body>
</html>';
        return $rtn;
    }
    
}

