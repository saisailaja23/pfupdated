<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php

define('BX_PROFILE_PAGE', 1);
require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

$iMemberId          = getLoggedId();
$sel_parent_ID      = $_POST['sel_parent_ID'];
$sel_match_ID       = $_POST['sel_match_ID'];
$sel_pageid         = $_POST['pageid'];
$currentTime		=	date("YmdHi",time());

if($sel_pageid == 'first_1')
{
    //$printURL            = $GLOBALS['site']['url'] .'page/matches?parentIDprintFlag='.$sel_parent_ID.'__1';
    $printURL           = $GLOBALS['site']['url'] .'page/matches?parentID='.$sel_parent_ID."&printflag=1";
    $pdfFile            = $GLOBALS['dir']['root']."Matching/PrintPDF/".$sel_parent_ID.'_'.$iMemberId.".pdf";
    $htmlFile           = $GLOBALS['dir']['root']."Matching/PrintPDF/".$sel_parent_ID.'_'.$iMemberId.".html";
    $pdfFile_url        = $GLOBALS['site']['url']."Matching/PrintPDF/".$sel_parent_ID.'_'.$iMemberId.".pdf";
}
else if($sel_pageid == 'bp_2')
{
    $printURL           = $GLOBALS['site']['url'] .'page/bpmatch?parentID='.$sel_match_ID.'&userID='.$sel_parent_ID."&printflag=1";
    $pdfFile            = $GLOBALS['dir']['root']."Matching/PrintPDF/".$sel_parent_ID.'_'.$iMemberId."_".$sel_match_ID.".pdf";
    $htmlFile           = $GLOBALS['dir']['root']."Matching/PrintPDF/".$sel_parent_ID.'_'.$iMemberId."_".$sel_match_ID.".html";
    $pdfFile_url        = $GLOBALS['site']['url']."Matching/PrintPDF/".$sel_parent_ID.'_'.$iMemberId."_".$sel_match_ID.".pdf";
}
else if($sel_pageid == 'ap_3')
{
    $printURL           = $GLOBALS['site']['url'] .'page/apmatch?parentID='.$sel_match_ID.'&userID='.$sel_parent_ID."&printflag=1";
    $pdfFile            = $GLOBALS['dir']['root']."Matching/PrintPDF/".$sel_parent_ID.'_'.$iMemberId."_".$sel_match_ID.".pdf";
    $htmlFile           = $GLOBALS['dir']['root']."Matching/PrintPDF/".$sel_parent_ID.'_'.$iMemberId."_".$sel_match_ID.".html";
    $pdfFile_url        = $GLOBALS['site']['url']."Matching/PrintPDF/".$sel_parent_ID.'_'.$iMemberId."_".$sel_match_ID.".pdf";
}

$contents=file_get_contents($printURL); //fetch RSS feed
$fp=fopen($htmlFile, "a+");
fwrite($fp, $contents); //write contents of feed to cache file
fclose($fp);
chmod($htmlFile, 0777);

if (stristr (PHP_OS, 'WIN'))
    $pdf_command            = "..\\wkhtmltopdf\\wkhtmltopdf -L 0 -B 0 -T 0 -R 0 $htmlFile $pdfFile";
else
    $pdf_command            = "/usr/local/bin/wkhtmltopdf -L 0 -B 0 -T 0 -R 0 $htmlFile $pdfFile";

exec($pdf_command);
unlink($htmlFile);
echo $pdfFile_url;

?>