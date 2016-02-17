<?php
//============================================================+
// File name   : pdf.php
// Begin       : 2008-03-04
// Last Update : 2010-08-14
//
// Description : Example 001 for TCPDF class
//               Default Header and Footer
//
// Author: pranay kumar
//
// (c) Copyright:
//              pranay kumar
//               Tecnick.com s.r.l.
//               Via Della Pace, 11
//               09044 Quartucciu (CA)
//               ITALY
//               www.parenfinder.com
//               mediaus.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Default Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

require_once('config/lang/eng.php');
require_once('tcpdf.php');


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 001');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);
$pdf->SetDisplayMode(90);

$pdf->AddPage();

// Make a MySQL Connection
include( '../inc/header.inc.php' );
        $dbhost = $db['host'] ;
            $dbuser = $db['user'] ;
            $dbpass = $db['passwd'];

if($_GET['type'] == ''){
$_GET['type'] = 4;
}
// Get all the data from the "example" table
$members = mysql_query("SELECT * FROM `Profiles`  where `ProfileType`= ".$_GET['type']." AND `Status` = 'Active' AND `NickName` NOT LIKE '%(2)%'order by `Avatar` DESC");

if($_GET['type'] == 2){
 $title="Parent finder Waiting Families List";   
}
elseif($_GET['type'] == 4){
 $title="Parent finder Birth Parents List";      
}
else{
 $title="Parent finder Agencies List";      
}

            $filevalue = "<p style=\"color:#A1D8EF; text-align:center;\"><b>".$title."</b></p>";
            $filevalue .= "<table style=\"background-color:#A1D8EF; border:1px solid black; width:750px; font-size: 35px; \"><tr>
                                                                                            <td style=\"width:100px\"  align='left'>".'ID'."&nbsp;"."</td>
                                                                                            <td style=\"width:150px\"  align='left'>".'UserName'."</td>
                                                                                            <td style=\"width:200px\" align='left'>". 'Email'."</td>
                                                                                            <td style=\"width:100px\" align='left'>". 'Membership'."</td>
                                                                                            <td style=\"width:125px\" align='left'>". 'Agency'."</td></tr>";
            $filevalue .= " \n";
         
                while($row = mysql_fetch_array($members))//sys_acl_levels
                {     
                    $membership =  mysql_fetch_assoc(mysql_query("SELECT `Name` FROM `sys_acl_levels` AS sn INNER JOIN `sys_acl_levels_members` AS sid ON  sn.`ID`= sid .`IDLevel` WHERE sid .`IDMember`=".$row["ID"]));
                    $agency = mysql_fetch_assoc(mysql_query("SELECT `title` FROM `bx_groups_main` WHERE `id`=".$row["AdoptionAgency"]));
                if(!$membership["Name"]){                        
                       $membership["Name"] = 'Standard'; 
                    }
                    if($row['Avatar'] != 0 ){
                                                $image1 = $dir['root']."modules/boonex/avatar/data/images/".$row['Avatar'].".jpg";
                                                if(file_exists($image1))
                                                {
                                                 $image =  "<img src=\"".$site['url']."modules/boonex/avatar/data/images/".$row['Avatar'].".jpg\"/>"; 
                                                 $filevalue .= "<tr><td style=\"width:100px\" align='left'>".$row["ID"]."&nbsp;&nbsp;".$image."</td>
                                                                <td style=\"width:150px\" align='left'>".$row["NickName"]."</td>
                                                                <td style=\"width:200px\" align='left'>"."-- PRIVATE --"."</td>
                                                                <td style=\"width:100px\" align='left'>"."-- PRIVATE --"."</td>
                                                                <td style=\"width:125px\" align='left'>".$agency['title']."</td></tr>";
                                                
                                                 
                                                 
                                                }
                                                else{
                                                  $image = ""; 
                                                  $filevalue .= "<tr><td style=\"width:100px\" align='left'>".$row["ID"]."&nbsp;&nbsp;".$image."</td>
                                                                <td style=\"width:150px\" align='left'>".$row["NickName"]."</td>
                                                                <td style=\"width:200px\" align='left'>"."-- PRIVATE --"."</td>
                                                                <td style=\"width:100px\" align='left'>"."-- PRIVATE --"."</td>
                                                                <td style=\"width:125px\" align='left'>".$agency['title']."</td></tr>";
                                                }
                                            }
                     else{
                          $filevalue .= "<tr><td style=\"width:100px\" align='left'>".$row["ID"]."</td>
                                        <td style=\"width:150px\" align='left'>".$row["NickName"]."</td>
                                        <td style=\"width:200px\" align='left'>"."-- PRIVATE --"."</td>
                                        <td style=\"width:100px\" align='left'>"."-- PRIVATE --"."</td>
                                        <td style=\"width:125px\" align='left'>".$agency['title']."</td></tr>";
                                                }
                    
                } 

$filevalue .= "</table>";
  
$html1=utf8_decode($filevalue);
$html1= str_replace("border", " ", "$html1");
$html1=str_replace("pre","p","$html1");
$html1 = str_replace('/<p[^>]*><\\/p[^>]*>/', '', $html1); // Remove the start <p> or <p attr="">

$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', "$html1", $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
ob_clean();
$pdf->Output('pdf.pdf', 'D');
        
?>
