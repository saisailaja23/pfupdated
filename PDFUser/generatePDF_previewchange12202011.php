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
    public $coverTitle;
    public $blockTitle;
    public $pictureTitle;

    public function __construct($previewVar=NULL)
    {

        global $baseurl,$aCopA,$aCopB,$parentid,$basepath;
        //print_r($_POST);
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
        $this->coverTitle       = $_POST['CoverTitle'];
        $this->blockTitle       = $_POST['BlockTitle'];
        $this->pictureTitle     = $_POST['PictureTitle'];
        $this->coverImage       = $_POST['coverselect'];
        $this->blockContent     = $_POST['blockselect'];
        $this->pictureSelect    = $_POST['photoselect'];
        //echo $this->parentID;
        //echo " construct ".$this->_previewFlag."<br/>";
        //print_r($this->pictureArray);
        //exit();
    }

    public function generatePDF()
    {

        $createdhtml            = "";
        $createdhtml           .= $this->coverPage();
        //$createdhtml           .= $this->nextPage();
        $createdhtml           .= $this->blockpage();
        //$createdhtml           .= $this->nextPage();
        //$createdhtml           .= $this->photoPage();

        $createdhtml           .= $this->replacePhotoVar();

        //$imgName      = rotateImage($this->baseurl."PDFTemplates/realobject/testimg/59.jpg",20,session_id());
        //echo session_ID();
        //$imgName      = Rotate_img($this->baseurl."PDFTemplates/realobject/testimg/59.jpg",-20,session_id());
        //echo $createdhtml;

        //exit();

        //echo $createdhtml;
        //$htmlFile               = $this->templateID."/".$this->templateID.".html";
        //$pdfFile                = $this->templateID."/".$this->templateID.".pdf";
        $pdfreactor = new PDFreactor();

        //LicenseKey
        /*$pdfreactor->setLicenseKey("<license><licensee>"
                    ."<name>CAIRS</name></licensee>"
                    ."<product>PDFreactor</product><majorversion>5</majorversion>"
                    ."<minorversion>0</minorversion>"
                    ."<licensetype>Evaluation</licensetype>"
                    ."<expirationdate>2011-10-26</expirationdate>"
                    ."<signatureinformation><signdate>2011-09-26 16:51</signdate>"
                    ."<signature>302c021455d7ba909afab8146df4145eaddbdbc571f923530214169e5720a01bc521add950ab6cd9ef1e7d758148</signature>"
                    ."<checksum>1186</checksum></signatureinformation></license>");*/
        // Do some configuration
        // Sets the author of the created PDF
        $pdfreactor->setAuthor("Myself");

        // Enables links in the PDF document.
        //$pdfreactor->setAddLinks(true);

        // Enables bookmarks in the PDF document.
        //$pdfreactor->setAddBookmarks(true);

        // Set some Viewerpreferences
        //$pdfreactor->setViewerPreferences(VIEWER_PREFERENCES_FIT_WINDOW | VIEWER_PREFERENCES_PAGE_MODE_USE_THUMBS);

        // Add userstylesheets
        // e.g. format landscape
        $pdfreactor->addUserStyleSheet("@page { size: Letter landscape;padding: 0mm;margin: 0mm;border:0mm" .
                "@bottom-center {text-align: center;" .
                "font: 50px Arial, Helvetica, sans-serif;" .
                "color: #7F7F7F;" .
                "content: 'Created on ".date("D M j Y G:i:s")."';" .
                "margin-bottom: 0cm;margin-top: 0cm;margin-lfet: 0cm;margin-right: 0cm;}}","","","");
        //$pdfreactor->addUserStyleSheet("h3 { color: red; }","","","");

        $pdfreactor->addUserStyleSheet("", "", "", $this->baseurl."PDFTemplates/".$this->templateID."/parentfinder.css");
        //$pdfreactor->addUserStyleSheet("", "", "", "http://192.168.40.11:8090/testmenu/PDFCreation/freactor/parentfinder.css");

        // Render document and save result to $result
        $result = $pdfreactor->renderDocumentFromContent($createdhtml);
        $createdPDF= $this->basepath."PDFTemplates/user/".$this->parentID."_".$this->templateID.".pdf";
        //echo "pdf name ".$createdPDF."<br/>";
        // Check if successfull
        if ($pdfreactor->getError() != "") {
            // Not successful->print error and log
            echo "Error during rendering: <br/>".$pdfreactor->getError()."<br/><br/>".$pdfreactor->getLog();
        } else {
            // Set the correct header for PDF output and echo PDF content
            //header("Content-Type: application/pdf");
            //echo $result;
            file_put_contents($createdPDF, $result);
        }

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
    public function blockPage($fName=NULL)
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

        $blockContent               = $this->replaceBlockVar($blockContent);

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
        while (!feof($file_handle)) {
           $line = fgets($file_handle);
           if($line)
           {
            preg_match ("/##(.*)##/", $line, $img_sett);
            if($img_sett)
                $this->phototalbumArray[] = $img_sett[1];
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
        //$img                        = getProfileImage($this->parentID);
        //echo " image details ".$img;
        //print_r($img);
        //$img['filepath']            = $this->basepath."PDFTemplates/testimg/59.jpg";
        //$img['imagepath']           = $this->baseurl."PDFTemplates/testimg/59.jpg";
        //$imagepath                  = file_exists($img['filepath'])?$img['imagepath']:$this->baseurl.'templates/base/images/icons/man_medium.gif';
        //echo " to rotatet ".$imagepath."<br/>";

        $sel_cover_photo        = selectedCoverImage($this->parentID,$this->coverImage);
        //print_r($sel_cover_photo);
        if($sel_cover_photo)
             $imagepath = $GLOBALS['site']['url']."m/photos/get_image/file/".$sel_cover_photo['Hash'].".".$sel_cover_photo['Ext'];
        else
             $imagepath     = $this->baseurl.'templates/base/images/icons/man_medium.gif';
        //echo $imagepath;
        if(!$this->_previewFlag)
        {
            if($this->coverImageArray)
            {
                list($tempvar,$containreWidth,$containerHeight,$rotateAngle) = split('_',$this->coverImageArray[0]);
                $sessionID      = session_ID()."_cover";
                $rotateAngle    = ($rotateAngle)?$rotateAngle:0;
                //$coverimage                 = '<img alt="'.$parentname.'" src ="'.$imagepath.'" height="270" width ="355"/>';
                //echo "paret name ".$parentname."<br/>";
                //echo "coverimage ".$coverimage."<br/>";
                $imgName      = Rotate_img($imagepath,$rotateAngle,$sessionID);
                $resizeImg    = Resize_temp($imgName,$containreWidth,$containerHeight,$sessionID);
                $coverimage   = $this->baseurl.$resizeImg;
                if(!$this->_previewFlag)
                {

                    if($coverimage)
                        $cvrCnt           = str_replace('##'.$this->coverImageArray[0].'##', $coverimage, $cvrCnt);
                    $cvrCnt               = str_replace('#*#coverTitle#*#', $this->coverTitle, $cvrCnt);
                }
                else
                {
                    $cvrCnt               = str_replace('#*#coverTitle#*#', $this->coverTitle, $cvrCnt);
                    $cvrCnt               = str_replace('##'.$this->coverImageArray[0].'##', "", $cvrCnt);
                }
            }
        }
        else
        {
                $cvrCnt               = str_replace('#*#coverTitle#*#', "", $cvrCnt);
        }
        return $cvrCnt;
    }
    //replace block variables
    public function replaceBlockVar($blckCnt)
    {
        $block_heading              = $this->blockTitle;
        $block_value                = ' <p style="margin: 0px 0px 1em; color: rgb(102, 102, 102); font-family: \'Trebuchet MS\',Tahoma,Arial,Verdana,monospace; font-size: 13px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 18px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px;">dhtmlxMenu is flexible, powerful, lightweight and easy to use. It can be easily configured as a<span class="Apple-converted-space">&nbsp;</span><strong>drop down menu or a context menu</strong>. You can freely define visual menu appearance and structure by simply changing its parameters. Menu items can be aligned either on the left or on the right side of the menu panel. Since v2.5, dhtmlxMenu has supported RTL mode (might be incompatible with some IE versions).</p>
<p style="margin: 0px 0px 1em; color: rgb(102, 102, 102); font-family: \'Trebuchet MS\',Tahoma,Arial,Verdana,monospace; font-size: 13px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 18px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px;">Like other DHTMLX components, our JavaScript menu provides<span class="Apple-converted-space">&nbsp;</span><strong>cross-browser/multi-platform compatibility</strong>, a powerful client-side API, and a wide range of customization options (through JavaScript or XML).<span class="Apple-converted-space">&nbsp;</span><strong>Dynamic loading from XML</strong><span class="Apple-converted-space">&nbsp;</span>offers the ability to change the navigation menu completely without reloading.</p>
<p style="margin: 0px 0px 1em; color: rgb(102, 102, 102); font-family: \'Trebuchet MS\',Tahoma,Arial,Verdana,monospace; font-size: 13px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 18px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px;">This<span class="Apple-converted-space">&nbsp;</span><strong>Ajax-powered dynamic menu</strong><span class="Apple-converted-space">&nbsp;</span>allows you to create fast-loading navigation systems in a matter of minutes. Starting with v.2.0, dhtmlxMenu has provided easy ways to integrate with other components of the DHTMLX product line. It can easily be included in<span class="Apple-converted-space">&nbsp;</span><a style="text-decoration: underline; color: rgb(3, 121, 161);" href="http://dhtmlx.com/docs/products/dhtmlxWindows/index.shtml">dhtmlxWindows</a>,<span class="Apple-converted-space">&nbsp;</span><a style="text-decoration: underline; color: rgb(3, 121, 161);" href="http://dhtmlx.com/docs/products/dhtmlxLayout/index.shtml">dhtmlxLayout</a>, and<span class="Apple-converted-space">&nbsp;</span><a style="text-decoration: underline; color: rgb(3, 121, 161);" href="http://dhtmlx.com/docs/products/dhtmlxAccordion/index.shtml">dhtmlxAccordion</a>. It can also be used as a context menu for<span class="Apple-converted-space">&nbsp;</span><a style="text-decoration: underline; color: rgb(3, 121, 161);" href="http://dhtmlx.com/docs/products/dhtmlxGrid/index.shtml">dhtmlxGrid</a>,<span class="Apple-converted-space">&nbsp;</span><a style="text-decoration: underline; color: rgb(3, 121, 161);" href="http://dhtmlx.com/docs/products/dhtmlxTreeGrid/index.shtml">TreeGrid</a>, and<a style="text-decoration: underline; color: rgb(3, 121, 161);" href="http://dhtmlx.com/docs/products/dhtmlxTree/index.shtml">dhtmlxTree</a>. With a new skin, introduced in version 2.5, all the components took on a slick and clean look which can perfectly match the design of any modern website or application.</p>

<p style="margin: 0px 0px 1em; color: rgb(102, 102, 102); font-family: \'Trebuchet MS\',Tahoma,Arial,Verdana,monospace; font-size: 13px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 18px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px;">dhtmlxMenu is available in the<span class="Apple-converted-space">&nbsp;</span><strong>Standard Edition</strong><span class="Apple-converted-space">&nbsp;</span>only. Download and use dhtmlxMenu for free under GNU GPL, or buy a Commercial or Enterprise License to use the component in a non-GPL application/website and get official support.</p>
<p style="margin: 0px 0px 1em; color: rgb(102, 102, 102); font-family: \'Trebuchet MS\',Tahoma,Arial,Verdana,monospace; font-size: 13px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 18px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px;">&nbsp;</p>
<p style="margin: 0px 0px 1em; color: rgb(102, 102, 102); font-family: \'Trebuchet MS\',Tahoma,Arial,Verdana,monospace; font-size: 13px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 18px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px;"><img src="http://localhost/js/fckeditor/editor/images/smiley/msn/angry_smile.gif" alt="" />&nbsp; <img src="http://localhost/js/fckeditor/editor/images/smiley/msn/cry_smile.gif" alt="" /></p>
<ul style="margin: 0px; display: block; list-style-type: circle; padding-left: 40px; color: rgb(102, 102, 102); font-family: \'Trebuchet MS\',Tahoma,Arial,Verdana,monospace; font-size: 13px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 18px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px;">
    <li>Cross-browser/cross-platform support</li>
    <li>Full controll with JavaScript API</li>
    <li>Dropdown or context modes</li>
    <li>Multiple ways of loading data (Ajax, HTML, script object, and script API)</li>
    <li>Dynamic loading</li>
    <li>Hotkeys support</li>
    <li>Tooltips support</li>
    <li>RTL support</li>
    <li>Different skins available</li>
</ul>';
        if(!$this->_previewFlag)
        {
            if($block_heading)
                $blckCnt           = str_replace('##blockheading##', $block_heading, $blckCnt);
            if($block_value)
                $blckCnt           = str_replace('##blockcontent##', $block_value, $blckCnt);
        }
        else
        {

            $blckCnt               = str_replace('##blockheading##', "", $blckCnt);

            $blckCnt               = str_replace('##blockcontent##', "", $blckCnt);
        }
        return $blckCnt;
    }
    //replace photo variables
    public function replacePhotoVar($mpdf=NULL,$pagenum_1=NULL)
    {
        if(!$this->_previewFlag)
        {
            $photoArray         = array();
            /*$photoArray[0]      = "PDFTemplates/testimg/59.jpg";
            $photoArray[1]      = "PDFTemplates/testimg/1019.jpg";
            $photoArray[2]      = "PDFTemplates/testimg/1026.jpg";
            $photoArray[3]      = "PDFTemplates/testimg/1.jpg";
            $photoArray[4]      = "PDFTemplates/testimg/2.jpg";
            $photoArray[5]      = "PDFTemplates/testimg/3.jpg";
            $photoArray[6]      = "PDFTemplates/testimg/4.jpg";
            */
            $photo_content      = "";
            $pictureArray       = split(',',$this->pictureSelect);
            foreach($pictureArray as $phArray)
            {
                $sel_pic_photo        = selectedCoverImage($this->parentID,$phArray);
                if($sel_pic_photo)
                    $photoArray[] = $GLOBALS['site']['url']."m/photos/get_image/file/".$sel_pic_photo['Hash'].".".$sel_pic_photo['Ext'];
                else
                    $photoArray[]     = $this->baseurl.'templates/base/images/icons/man_medium.gif';
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
                        //echo "<br/>photo array<br/>";
                        list($tempvar,$containreWidth,$containerHeight,$rotateAngle) = split('_',$this->phototalbumArray[$phtcnt]);
                        //echo "temp var        ".$tempvar."<br/>";
                        //echo "containreWidth  ".$containreWidth."<br/>";
                        //echo "containerHeight ".$containerHeight."<br/>";
                        //echo "rotateAngle     ".$rotateAngle."<br/>";
                        //echo " replace var ".$this->phototalbumArray[$phtcnt]."<br/>";
                        //echo "file name ".$this->basepath.$photoArray[$photocount + $phtcnt]."<br/>";
                        //echo "file counter ".($photocount + $phtcnt)."<br/>";
                        //echo "<br/><br/>";
                        $imgName      = Rotate_img($photoArray[$photocount + $phtcnt],$rotateAngle,$sessionID);
                        //echo " fname after rotate ".$imgName;
                        $resizeImg    = Resize_temp($imgName,$containreWidth,$containerHeight,$sessionID);
                        $imgVar       = $this->baseurl.$resizeImg;
                        //$selImage     = '<img alt="'.$parentname.'" src ="'.$imgVar.'" />';
                        $selImage     = $imgVar;
                    }
                    else
                        $selImage     = "";
                    if($photo_cont)
                    {
                        $photo_cont             = str_replace("##".$this->phototalbumArray[$phtcnt]."##", $selImage, $photo_cont);
                        //$photo_cont             = str_replace('##image2##', $coverimage, $photo_cont);
                        //$photo_cont             = str_replace('##image3##', $coverimage, $photo_cont);
                        //$photo_cont             = str_replace('##image4##', $coverimage, $photo_cont);
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
                $photo_content           .= $photo_cont;
                $photo_content           .= $this->nextPage();
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
        }
        else
        {
            $photo_cont                 = $this->photoPage();
            $photo_cont         = str_replace('#*#pictureTitle#*#', "", $photo_cont);
            //$photo_cont                 = str_replace("##".$this->phototalbumArray[$phtcnt]."##", "", $photo_cont);
            //$photo_cont                 = str_replace("##".$this->phototalbumArray[$phtcnt]."##", "", $photo_cont);
            //$photo_cont                 = str_replace("##".$this->phototalbumArray[$phtcnt]."##", "", $photo_cont);
            //$photo_cont                 = str_replace("##".$this->phototalbumArray[$phtcnt]."##", "", $photo_cont);

            $photo_content      .= $photo_cont;
        }
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

    public function generatempdf()
    {
        $cover_cont     = $this->coverPage();
        echo $cover_cont;
        $block_cont     = $this->blockPage();
        echo $block_cont;
        $stylesheet     = file_get_contents($this->baseurl."PDFTemplates/".$this->templateID."/parentfinder.css");
        //echo $stylesheet;
        $pagenum_1      = '
            <div style="position:relative;width:1056px;">
                <div style="position:relative;float:left;margin-left:60px;width:300px;">{DATE m/j/y}</div>
                <div style="position:relative;float:right;margin-right:50px;width:300px;text-align:right;">{PAGENO}</div>
            </div>
                          ';
        $pagenum_2      = '
            <div style="position:relative;width:980px;padding:0px;">
                <div style="position:relative;float:left;margin-left:0px;width:300px;">{DATE m/j/y}</div>
                <div style="position:relative;float:right;width:300px;text-align:right;margin-right:0px;">{PAGENO}</div>
                <div style="clear:both;width:5px;"></div>
            </div>
            ';
        $mpdf1           = new mPDF('','Letter-L','','',0,0,0,0,20,8);
        $mpdf1->WriteHTML($stylesheet,1);
        $mpdf1->SetHTMLFooter($pagenum_1);
        $mpdf1->WriteHTML($cover_cont);
        for($cnt_blck=1;$cnt_blck<2;$cnt_blck++)
        {
            $mpdf1->AddPage('','',0,'','',15,15,20,30);
            $mpdf1->SetHTMLFooter($pagenum_2);
            $mpdf1->pagenumPrefix = 'Page ';
            //$mpdf1->WriteHTML($stylesheet,1);
            $mpdf1->WriteHTML($block_cont);

        }
        //echo "<br/>before for loop<br/>";

       $this->replacePhotoVar($mpdf1,$pagenum_1);
       $mpdf1->Output($this->templateID."_mpdf.pdf");
       //$this->genWKHTML();
        //echo $cover_cont;
        //echo $block_cont;
        //echo $photo_cont;
    }

    public function genWKHTML()
    {
        $createdhtml            = "";
        $createdhtml            .= '<link rel="stylesheet" href="'.$this->baseurl.'PDFTemplates/'.$this->templateID.'/parentfinder.css" type="text/css"/>';
        $createdhtml           .= $this->coverPage();
        //$createdhtml           .= $this->nextPage();
        if(!$this->_previewFlag)
        {
            for($blck_cnt=0;$blck_cnt<2;$blck_cnt++)
            {
                $createdhtml           .= $this->blockpage();
                //$createdhtml           .= $this->nextPage();
                //
            }
        }
        else
        {
            $createdhtml           .= $this->blockpage();
            //$createdhtml           .= $this->nextPage();
        }
        //$createdhtml           .= $this->nextPage();
        //$createdhtml           .= $this->photoPage();

        $createdhtml           .= $this->replacePhotoVar();

        //$createdhtml           .= $this->addExtra();
        echo $createdhtml;
        //exit();
        if(!$this->_previewFlag)
        {
            $htmlFile               = $this->basepath."PDFTemplates/user/".$this->templateID."_wkhtml.html";
            $html_File              = $this->baseurl."PDFTemplates/user/".$this->templateID."_wkhtml.html";
            $pdfFile                = "PDFTemplates/user/".$this->templateID."_wkhtml.pdf";
        }
        else {
            $htmlFile               = $this->basepath."PDFTemplates/user/".$this->templateID."_preview_wkhtml.html";
            $html_File              = $this->baseurl."PDFTemplates/user/".$this->templateID."_preview_wkhtml.html";
            $pdfFile                = "PDFTemplates/user/".$this->templateID."_preview_wkhtml.pdf";

        }
        file_put_contents($htmlFile, $createdhtml);

        $pdf_command            = "wkhtmltopdf\\wkhtmltopdf -L 0 -B 0 -T 0 -R 0 -O Landscape -s Letter $html_File $pdfFile";
        exec($pdf_command);
        exit();
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

