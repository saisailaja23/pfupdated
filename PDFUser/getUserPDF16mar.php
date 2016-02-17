<?php
header('Content-Type: text/xml');
//error_reporting(1);
define('BX_PROFILE_PAGE', 1);
require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once(BX_DIRECTORY_PATH_CLASSES . 'BxDolFilesDb.php');
bx_import('BxTemplProfileView');
bx_import('BxDolInstallerUtils');
bx_import('BxDolFilesModule');

require_once ('phpfunctions.php');


 $parent_ID     =  $_GET['apUser'];

$paymentString  =   '<?xml version="1.0" encoding="UTF-8"?>
                        <rows><head>
                        <column  width="30" type="ch" align="left"  sort="na" >#master_checkbox</column>
                        <column  width="150" type="ro" align="left"  sort="str">Template Name</column>
                        <column  width="*" type="ro" align="left"  sort="str">Template Description</column>
                        <column  width="150" type="ro" align="left"  sort="str">Cover Title</column>
                        <column  width="150" type="ro" align="left"  sort="str">Picture Title</column>
                        <column  width="110" type="ro" align="left"  sort="str">Last Updated Date</column>
                        <column  width="90" type="ro" align="left"  sort="na">Actions</column>
                        </head>';
$sql_pdfuserDet         = "SELECT
                            ptu.template_user_id,
                            ptu.user_id,
                            ptu.template_id,
                            ptu.template_file_path,
                            ptu.template_description,
                            ptu.isDeleted,
                            ptu.isDefault,
                            DATE_FORMAT(ptu.lastupdateddate, '%m/%d/%Y') AS lastupdateddate,
                            DATE_FORMAT(ptu.lastupdateddate, '%Y-%m-%d') AS pdfdate,
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
                            WHERE ptu.user_id = $parent_ID AND ptu.isDeleted = 'N' ORDER BY ptu.lastupdateddate DESC";
 //echo $sql_pdfuserDet;
 $pdfUserDet             = mysql_query($sql_pdfuserDet);
 //echo " query reslut ".$pdfUserDet;
 if(mysql_numrows($pdfUserDet) > 0)
 {
      while($row_pdfsdet = mysql_fetch_assoc($pdfUserDet))
      {
            $pdfUserDetails[] = $row_pdfsdet;
      }
 }
$pdfuserDetails        = $pdfUserDetails;
if($pdfuserDetails)
{
    foreach ($pdfuserDetails as $row)
    {
        if($row['template_id'] == 0)
        {
    if($row['isDefault'] == 'Y')
        $defaultvar = '&lt;img src="PDFUser/icons/setdefault.png"height="15" width="15" id= "' . $row['template_user_id'] . '" class = "tipz" style="position:relative;left:47px;color:#FD7800;cursor:pointer;"  alt="Default PDF" &gt;&lt;/img&gt;';
    else
        $defaultvar = '&lt;img src="PDFUser/icons/Default.png"height="15" width="15" id= "' . $row['template_user_id'] . '" class = "user_pdf_mkdflt tipz" style="position:relative;left:47px;color:#FD7800;cursor:pointer;"  alt="Make Default" &gt;&lt;/img&gt;';

    //$lastupdatedtime =  date("m/d/Y g:i A", $row['lastupdateddate']);
    $lastupdatedtime =   $row['lastupdateddate'];
         //   $view_url   = $GLOBALS['site']['url']."PDFTemplates/user/".$parent_ID."_".$row['template_user_id']."_".$row['pdfdate'].".pdf";
              $view_url   = $parent_ID."_".$row['template_user_id']."_".$row['pdfdate'].".pdf";
            $paymentString .= '<row id="'. $row['template_user_id'] .'">
                                <cell></cell>
                                <cell style ="height:30px;vertical-align:middle;">Uploaded PDF</cell>
                                <cell style ="height:30px;vertical-align:middle;">' . $row['template_description'] . '</cell>
                                <cell style ="height:30px;vertical-align:middle;">' . $row['cover_title'] . '</cell>
                                <cell style ="height:30px;vertical-align:middle;">' . $row['photo_title'] . '</cell>
                                <cell style ="height:30px;vertical-align:middle;">' . $lastupdatedtime . '</cell>
                                <cell style ="height:30px;vertical-align:middle;" >
                                 &lt;img src="PDFUser/icons/view.png"height="15" width="15" id= "' . $row['template_user_id'] . '" url="'.$view_url.'" class = "user_pdf_view tipz" style="position:relative;left:44px;color:#FD7800;cursor:pointer;" alt="view" &gt;&lt;/img&gt;
                                 '.$defaultvar.'
                                </cell>
                            </row>';
        }
        else
        {

            if($row['isDefault'] == 'Y')
                $defaultvar = '&lt;img src="PDFUser/icons/setdefault.png"height="15" width="15" id= "' . $row['template_user_id'] . '" class = "tipz" style="position:relative;left:10px;color:#FD7800;cursor:pointer;"  alt="Default PDF" &gt;&lt;/img&gt;';
            else
                $defaultvar = '&lt;img src="PDFUser/icons/Default.png"height="15" width="15" id= "' . $row['template_user_id'] . '" class = "user_pdf_mkdflt tipz" style="position:relative;left:10px;color:#FD7800;cursor:pointer;"  alt="Make Default" &gt;&lt;/img&gt;';

            //$lastupdatedtime =  date("m/d/Y g:i A", $row['lastupdateddate']);
            $lastupdatedtime =   $row['lastupdateddate'];
    $edit_url   = $GLOBALS['site']['url']."page/pdfcreate?tempusrid=".$row['template_user_id'];
    //$view_url   = $GLOBALS['site']['url']."PDFTemplates/user/".$row['template_id']."_".$parent_ID."_".$row['template_user_id'].".pdf";
     //$view_url   = $row['template_id']."_".$parent_ID."_".$row['template_user_id'].".pdf";
    $file_Path         = $row['template_file_path'];
    $actual_fileName   = split("user/",$file_Path); 
    $view_url          =  $actual_fileName[1];
    $paymentString .= '<row id="'. $row['template_user_id'] .'">
                        <cell></cell>
                        <cell style ="height:30px;vertical-align:middle;">' . $row['template_name'] . '</cell>
                        <cell style ="height:30px;vertical-align:middle;">' . $row['template_description'] . '</cell>
                        <cell style ="height:30px;vertical-align:middle;">' . $row['cover_title'] . '</cell>
                        <cell style ="height:30px;vertical-align:middle;">' . $row['photo_title'] . '</cell>
                        <cell style ="height:30px;vertical-align:middle;">' . $lastupdatedtime . '</cell>
                        <cell style ="height:30px;vertical-align:middle;" >
                        &lt;img src="PDFUser/icons/edit.png"height="15" width="15" id= "' . $row['template_user_id'] . '" url="'.$edit_url.'" class = "user_pdf_edit tipz" style="position:relative;left:5px;color:#FD7800;cursor:pointer;" alt="Edit" &gt;&lt;/img&gt;
                        &lt;img src="PDFUser/icons/view.png"height="15" width="15" id= "' . $row['template_user_id'] . '" url="'.$view_url.'" class = "user_pdf_view tipz" style="position:relative;left:10px;color:#FD7800;cursor:pointer;" alt="view" &gt;&lt;/img&gt;
                        &lt;img src="PDFUser/icons/regenerate.png"height="15" width="15" id= "' . $row['template_user_id'] . '" class = "user_pdf_regen tipz" style="position:relative;left:10px;color:#FD7800;cursor:pointer;"  alt="re-genarate" &gt;&lt;/img&gt;
                        '.$defaultvar.'
                        </cell>
                    </row>';
    }
    }
    $paymentString .= '</rows>';
}
 else {
    //$paymentString  .= '<row id="no"><cell></cell><cell colspan="5">No PDF available</cell></row></rows>';
     $paymentString  .= 'No PDF available</rows>';

}

echo $paymentString;
?>