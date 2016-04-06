<?php
session_start();
ini_set("display_errors",0);
require_once ('../../inc/header.inc.php');
//ini_set('max_execution_time', 300); //300 seconds = 5 minutes. Place this at the top of yo
$getaboutus = file_get_contents($site['url'].'getaboutus_exe.php?profile='.$_GET['profile']);
$getAboutUsArray = (explode(";-",$getaboutus));
array_pop($getAboutUsArray);
//print_r($getAboutUsArray);

$aDataBlock = file_get_contents($site['url'].'getblockshoa_exe.php?profile='.$_GET['profile'].'');
$aDataBlocks = (explode(";-",$aDataBlock));
//print_r($aDataBlocks);
array_pop($aDataBlocks);

$achildata = file_get_contents($site['url'].'getchild.php?profile='.$_GET['profile'].'');
$achildatas = (explode(";-",$achildata));
array_pop($achildatas);

$data = file_get_contents($site['url'].'test.php?profile='.$_GET['profile']);
$aData1 = (explode(";-",$data));
//print_r($aData);
$bpl = mb_substr($aData1[0], 0, 260);
$profile = $_GET['profile'];

$datanew = file_get_contents($site['url'].'badgewaitingfamiliesatmainprofile.php?profile='.$_GET['profile']);
$aDatanew1 = (explode(";-",$datanew));




?>

<?php

if(isset($_GET['famreg'])){
	$famregs = $_GET['famreg'];
	/*foreach($famregs as $famreg){
		$famregz .= ' %'.$famreg.'% ';
	}*/
}
$famregz = str_replace(" ", "<=>", implode(",", $famregs));


if(isset($_GET['pareth'])){
	$pareths = $_GET['pareth'];
	/*foreach($pareths as $pareth){
		$parethz .= ' %'.$pareth.'% ';
	}*/
	$parethz = str_replace(" ", "<=>", implode(",", $pareths));

}

if(isset($_GET['beth'])) {
	$beths = $_GET['beth'];
	/*foreach($beths as $beth){
		$bethz .= ' %'.$beth.'% ';
	}*/
	$bethz = str_replace(" ", "<=>", implode(",", $beths));

}

$i = 0;
$k = 0;
if(isset($_GET['pstart']) && isset($_GET['pend'])){

if(!isset($_GET['max_race_id'])){

    $data = file_get_contents($site['url'].'badgewaitingfamiliesat.php?pstart='.$_GET[pstart].'&pend='.$_GET['pend']);
   }

else {

  $data = file_get_contents($site['url'].'badgewaitingfamiliesat.php?famreg='.$famregz.'&pareth='.$parethz.'&beth='.$bethz.'&family_num_children='.$_GET[family_num_children].'&family_state='.$_GET[family_state].'&pstart='.$_GET['pstart'].'&pend='.$_GET['pend']);

   }
}
elseif(isset($_GET['search']) && !isset($_GET['max_race_id'])){
$data = file_get_contents($site['url'].'badgewaitingfamiliesat.php?search='.$_GET[search]);

}
elseif(isset($_GET['max_race_id'])){

$data = file_get_contents($site['url'].'badgewaitingfamiliesat.php?famreg='.$famregz.'&pareth='.$parethz.'&beth='.$bethz.'&family_num_children='.$_GET[family_num_children].'&family_state='.$_GET[family_state]);

}
else {

    $data = file_get_contents($site['url'].'badgewaitingfamiliesat.php');

}

$aData = (explode("#####",$data));
if(isset($_GET['max_race_id'])) {
$dividedBy = round(count($aData)/2);
}
else
{
$dividedBy = 6;
  }
?>

  <?php
  /*
$con = mysql_connect("localhost","root","I4GotIt");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("pfcomm", $con);

$result = mysql_query("Select * from Profiles where id = '".$_GET['profile']."'");




while($row = mysql_fetch_array($result))
  {




function dateDiff($dformat, $endDate, $beginDate)
{
    $date_parts1=explode($dformat, $beginDate);
    $date_parts2=explode($dformat, $endDate);
    $start_date=gregoriantojd($date_parts1[0], $date_parts1[1], $date_parts1[2]);
    $end_date=gregoriantojd($date_parts2[0], $date_parts2[1], $date_parts2[2]);
    return $end_date - $start_date;
}


$asd= $row['DateOfBirth'] ;
$rest = substr($asd,-10, 4);


$rest1 = substr($asd,-5,2);



$rest2 = substr($asd,-2, 2);

$a="/";
$k= $rest1.$a.$rest2.$a.$rest;

$dob=$k;

$da=round(dateDiff("/", date("m/d/Y", time()), $dob)/365, 0);

echo "<table  width='399' border=0 cellspacing=0 cellpading=0  class=white_headng_cntr >
<tr bgcolor=#993400> <td align=cente class=tdstyle>".$row['NickName'] ."</td></td>
 </table>";

echo "<table  width='399' border=0 cellspacing=0 cellpading=0>
<tr> <td class=bold_black_ctn ><font color=black>Age:</td><td class=tdstyle>$da</font></td></tr>
<tr> <td class=bold_black_ctn style=border:993400 solid 1px><font color=black>Education:</td> <td class=tdstyle>" . $row['Education'] . "</font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Profession:</td> <td class=tdstyle>" . $row['Occupation'] . "</font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Ethnicity:</td> <td class=tdstyle>" . $row['Ethnicity'] . "</font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Religion:</td> <td class=tdstyle>" . $row['Religion'] . "</font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Smoking:</td> <td class=tdstyle>" . $row['Smoking'] . "</font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>State:</td> <td class=tdstyle>" . $row['State'] . "</font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Married:</td> <td class=tdstyle>" . $row['YearsMarried'] . "</font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Residency:</td> <td class=tdstyle>" . $row['Residency'] . "</font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Neighborhood:</td> <td class=tdstyle>" . $row['Neighborhood'] . "</font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Family Structure:</td> <td class=tdstyle>" . $row['FamilyStructure'] . "</font></td></tr>
<tr> <td class=bold_black_ctnl><font color=black>Pet(s):</td> <td class=tdstyleb>" . $row['Pet'] ."</font></td></tr>
</table>";



  }


mysql_close($con);
*/


function dateDiff($dformat, $endDate, $beginDate)
{
    $date_parts1=explode($dformat, $beginDate);
    $date_parts2=explode($dformat, $endDate);
    $start_date=gregoriantojd($date_parts1[0], $date_parts1[1], $date_parts1[2]);
    $end_date=gregoriantojd($date_parts2[0], $date_parts2[1], $date_parts2[2]);
    return $end_date - $start_date;
}
$asd= $getAboutUsArray[18] ;
$rest = substr($asd,-10, 4);
$rest1 = substr($asd,-5,2);
$rest2 = substr($asd,-2, 2);
$a="/";
$k= $rest1.$a.$rest2.$a.$rest;
$dob1=$k;
$da1=round(dateDiff("/", date("m/d/Y", time()), $dob1)/365, 0);

if ($getAboutUsArray[19] != '') {
$asd= $getAboutUsArray[19] ;
$rest = substr($asd,-10, 4);
$rest1 = substr($asd,-5,2);
$rest2 = substr($asd,-2, 2);
$a="/";
$k= $rest1.$a.$rest2.$a.$rest;
$dob2=$k;
$da2=round(dateDiff("/", date("m/d/Y", time()), $dob2)/365, 0);
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xml:lang="en-US" lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
<head id="Head"><meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <meta content="text/javascript" http-equiv="Content-Script-Type" />
    <meta content="text/css" http-equiv="Content-Style-Type" />
    <meta id="MetaKeywords" name="KEYWORDS" content="families waiting to adopt" />
    <title>Main Profile - <?=$aData1[3]?><?php if($aData1[4] != '') { ?> & <?php } ?><?=$aData1[4]?></title>
    <meta id="MetaCopyright" name="COPYRIGHT" content="Copyright 2010 by Adoptions Together" />
    <meta id="MetaAuthor" name="AUTHOR" content="Adoptions Together" />
    <meta name="RESOURCE-TYPE" content="DOCUMENT" />
    <meta name="DISTRIBUTION" content="GLOBAL" />
    <meta id="MetaRobots" name="ROBOTS" content="INDEX, FOLLOW" />
    <meta name="REVISIT-AFTER" content="1 DAYS" />
    <meta name="RATING" content="GENERAL" />
    <meta http-equiv="PAGE-ENTER" content="RevealTrans(Duration=0,Transition=1)" />
    <style id="StylePlaceholder" type="text/css"></style>
        <link id="APortals__default_" rel="stylesheet" type="text/css" href="Portals/_default/default.css" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link id="APortals_0_" rel="stylesheet" type="text/css" href="Portals/0/portal.css" />
        <link id="style" rel="stylesheet" type="text/css" href="Portals/_default/Skins/AdoptionsTogether/style.css" />
        <link id="styleEdit" rel="stylesheet" type="text/css" href="Portals/_default/Skins/AdoptionsTogether/style-edit.css" />
        <!--[if IE 6]><link id="styleIE6" rel="stylesheet" type="text/css" href="Portals/_default/Skins/AdoptionsTogether/style.ie6.css" />
                        <![endif]-->
        <!--[if IE 7]>
                    <link id="styleIE7" rel="stylesheet" type="text/css" href="Portals/_default/Skins/AdoptionsTogether/style.ie7.css" />
        <![endif]-->
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" ></script>
        <link href="Portals/_default/Skins/_default/WebControlSkin/Default/TabStrip.Default.css" rel="stylesheet" type="text/css" />
        <link rel="SHORTCUT ICON" href="Portals/0/favicon.ico" />

			    <script type="text/javascript">
			      var _gaq = _gaq || [];
			      _gaq.push(['_setAccount', 'UA-1319223-2']);
			      _gaq.push(['_trackPageview']);

			      (function() {
				    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			      })();
			    </script>

		  <title>
	Families Waiting to Adopt
</title></head>
<body id="Body">
    <form name="Form" method="post" action="/Pregnant/FamiliesWaitingtoAdopt.aspx" onsubmit="javascript:return WebForm_OnSubmit();" id="Form" enctype="multipart/form-data">
<div>
<input type="hidden" name="__EVENTTARGET" id="__EVENTTARGET" value="" />
<input type="hidden" name="__EVENTARGUMENT" id="__EVENTARGUMENT" value="" />
<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="/wEPDwULLTE2NDIyOTEzMjkPZBYGZg8WAh4EVGV4dAV5PCFET0NUWVBFIGh0bWwgUFVCTElDICItLy9XM0MvL0RURCBYSFRNTCAxLjAgVHJhbnNpdGlvbmFsLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL1RSL3hodG1sMS9EVEQveGh0bWwxLXRyYW5zaXRpb25hbC5kdGQiPmQCAQ9kFg4CBA8WAh4HVmlzaWJsZWhkAgUPFgQeB2NvbnRlbnRkHwFoZAIGDxYCHwIFGWZhbWlsaWVzIHdhaXRpbmcgdG8gYWRvcHRkAgcPFgIfAgUkQ29weXJpZ2h0IDIwMTAgYnkgQWRvcHRpb25zIFRvZ2V0aGVyZAIIDxYEHwJkHwFoZAIJDxYCHwIFEkFkb3B0aW9ucyBUb2dldGhlcmQCDA8WAh8CBQ1JTkRFWCwgRk9MTE9XZAICD2QWAgIBD2QWBAIED2QWAmYPZBYwZg9kFgICAQ9kFgJmDxYCHwFoFgQCAQ9kFggCAw8QZGQWAGQCBw8WAh8BaGQCCQ8WAh8BaGQCCw9kFgJmDw8WBh4ISW1hZ2VVcmwFFC9pbWFnZXMvY29sbGFwc2UuZ2lmHg1BbHRlcm5hdGVUZXh0BQhNaW5pbWl6ZR4HVG9vbFRpcAUITWluaW1pemUWCh4HdXNlcmN0cgUJVXNhYmlsaXR5Hgd1c2Vya2V5BRRDb250cm9sUGFuZWxWaXNpYmxlMB4Hb25jbGljawVQaWYgKF9fZG5uX1NlY3Rpb25NYXhNaW4odGhpcywgICdkbm5fUmliYm9uQmFyLmFzY3hfUkJfUmliYm9uQmFyJykpIHJldHVybiBmYWxzZTseCG1heF9pY29uBRIvaW1hZ2VzL2V4cGFuZC5naWYeCG1pbl9pY29uBRQvaW1hZ2VzL2NvbGxhcHNlLmdpZmQCAw9kFgQCAQ8UKwACFCsAAmQQFgRmAgECAgIDFgQUKwACZGQUKwACZGQUKwACDxYCHwFoZGQUKwACDxYCHwFoZGQPFgRmZmZmFgEFblRlbGVyaWsuV2ViLlVJLlJhZFRhYiwgVGVsZXJpay5XZWIuVUksIFZlcnNpb249MjAxMS4xLjUxOS4zNSwgQ3VsdHVyZT1uZXV0cmFsLCBQdWJsaWNLZXlUb2tlbj0xMjFmYWU3ODE2NWJhM2Q0ZBYEAgIPDxYCHwFoZGQCAw8PFgIfAWhkZAIDDxQrAAJkFQQIUGFnZUhvbWULUGFnZUN1cnJlbnQIUGFnZVNpdGUOUGFnZUhvc3RTeXN0ZW0WBAICDw8WAh8BaGRkAgMPDxYCHwFoZGQCAQ9kFgJmDw8WBB8FBRJBZG9wdGlvbnMgVG9nZXRoZXIeC05hdmlnYXRlVXJsBSpodHRwOi8vd3d3LmFkb3B0aW9uc3RvZ2V0aGVyLm9yZy9Ib21lLmFzcHhkZAICD2QWBGYPZBYGAgEPEA8WBh8ABQNXZWIfBQURR29vZ2xlIFdlYiBTZWFyY2gfAWhkZGRkAgMPEA8WBh8ABQRTaXRlHwUFC1NpdGUgU2VhcmNoHwFoZGRkZAIHDw8WAh8ABR48ZGl2IGNsYXNzPWJ1dHRvbj5TZWFyY2g8L2Rpdj5kZAICD2QWBGYPDxYEHwQFFlNlbGVjdCB0aGUgc2VhcmNoIHR5cGUfBQUWU2VsZWN0IHRoZSBzZWFyY2ggdHlwZWRkAgIPDxYCHwAFHjxkaXYgY2xhc3M9YnV0dG9uPlNlYXJjaDwvZGl2PmRkAgUPZBYCZg8PFgIfAAUCwqBkZAIHDxYCHgVjbGFzcwUMRE5ORW1wdHlQYW5lZAIIDxYCHwwFDEROTkVtcHR5UGFuZWQCCQ8WAh8MBQxETk5FbXB0eVBhbmVkAgoPFgIfDAUMRE5ORW1wdHlQYW5lZAILD2QWAmYPFgIfDAU5RG5uTW9kdWxlIERubk1vZHVsZS1BZG9wdGlvbnNUb2dldGhlckltYWdlIERubk1vZHVsZS0xMDQ2FgICAQ9kFgJmD2QWAgIBDw9kFgIfDAUrRE5OTW9kdWxlQ29udGVudCBNb2RBZG9wdGlvbnNUb2dldGhlckltYWdlQxYCAgEPZBYEZg8PFgIfAWhkFgJmDw8WAh8BaGRkAgIPDxYCHwMFPy9Qb3J0YWxzLzAvaW1hZ2VzL1BhZ2UgVGl0bGUgSW1hZ2VzL21lZXQgb3VyIGZhbWlsaWVzIHRpdGxlLnBuZ2RkAgwPZBYCZg8WAh8MBThEbm5Nb2R1bGUgRG5uTW9kdWxlLUFkb3B0aW9uc1RvZ2V0aGVySW1hZ2UgRG5uTW9kdWxlLTg5MhYCAgEPZBYCZg9kFgICAQ8PZBYCHwwFK0ROTk1vZHVsZUNvbnRlbnQgTW9kQWRvcHRpb25zVG9nZXRoZXJJbWFnZUMWAgIBD2QWBGYPDxYCHwFoZBYCZg8PFgIfAWhkZAICDw8WAh8DBS0vUG9ydGFscy8wL2ltYWdlcy9Zb3VuZ0ludGVycmFjaWFsQ291cGxlMi5qcGdkZAINDxYCHwwFDEROTkVtcHR5UGFuZWQCEQ8WAh8MBQxETk5FbXB0eVBhbmVkAhIPFgIfDAUMRE5ORW1wdHlQYW5lZAITDxYCHwwFDEROTkVtcHR5UGFuZWQCFA8WAh8MBQxETk5FbXB0eVBhbmVkAhUPZBYCAgEPFgIfDAUqRG5uTW9kdWxlIERubk1vZHVsZS1ETk5fSFRNTCBEbm5Nb2R1bGUtMzg2FgICAQ9kFgJmD2QWAgIBDw9kFgIfDAUcRE5OTW9kdWxlQ29udGVudCBNb2RETk5IVE1MQxYCAgEPZBYCAgIPFgIfAWhkAhYPFgIfDAUMRE5ORW1wdHlQYW5lZAIXDxYCHwwFDEROTkVtcHR5UGFuZWQCGA8WAh8MBQxETk5FbXB0eVBhbmVkAhkPFgIfDAUMRE5ORW1wdHlQYW5lZAIaDxYCHwwFDEROTkVtcHR5UGFuZWQCGw8WAh8MBQxETk5FbXB0eVBhbmVkAhwPFgIfDAUMRE5ORW1wdHlQYW5lZAIdDxYCHwwFDEROTkVtcHR5UGFuZWQCCg8UKwACFCsAA2RkZGRkGAEFHl9fQ29udHJvbHNSZXF1aXJlUG9zdEJhY2tLZXlfXxYCBRBkbm4kTkFWMSRjdGxOQVYxBRRkbm4kZG5uTkFWJGN0bGRubk5BVnkueHPfAxOuDSzRwnQzYS+bV4nf" />

</div>

 <div id="art-page-background-simple-gradient">
</div>
<div id="art-main">

    <div class="art-Sheet">
        <div class="art-Sheet-tl">
        </div>
        <div class="art-Sheet-tr">
        </div>
        <div class="art-Sheet-bl">
        </div>

        <div class="art-Sheet-br">
        </div>
        <div class="art-Sheet-tc">
        </div>
        <div class="art-Sheet-bc">
        </div>
        <div class="art-Sheet-cl">
        </div>
        <div class="art-Sheet-cr">

        </div>
        <div class="art-Sheet-cc">
        </div>
        <div class="art-Sheet-cc">
        </div>
        <div class="art-Sheet-cc">
        </div>
        <div class="art-Sheet-cc">
        </div>
        <div class="art-Sheet-body">
            <div class="art-Header">
                <div class="art-Logo">
                    <a id="dnn_dnnLOGO_hypLogo" title="Adoptions Together" href="http://www.adoptionstogether.org/Home.aspx"><img id="dnn_dnnLOGO_imgLogo" src="Portals/0/images/at_logo.png" alt="Adoptions Together" style="border-width:0px;" /></a>
                </div>
                <div class="phone-and-email">

                    <a href="mailto:info@adoptionstogether.org">
                        <img src="images/contact.png" />
                    </a>
                </div>
                <div class="header-buttons">
                    <a href="http://www.adoptionstogether.org/ContactUs.aspx" target="_blank"><div class="button">Contact Us</div></a>
                    <a href="http://www.adoptionstogether.org/InformationPackets.aspx" target="_blank"><div class="button">Info Packets</div></a>
                    <a href="http://www.adoptionstogether.org/Newsletter.aspx" target="_blank"><div class="button">Newsletter</div></a>
                    <a href="http://www.adoptionstogether.org/Donate.aspx" target="_blank"><div id="why-donate" class="button">DONATE NOW</div></a>
                </div>
                <div class="search-style">
                 
                    
                    <div class="header-social">
                        <div class="g-plusone" data-size="medium" data-annotation="inline" data-width="120"></div>
                        <img id="dnn_FacebookLogo" onclick="javascript:window.open('http://www.facebook.com/?sk=2361831622#!/pages/Adoptions-Together/179352644749?ref=ts', '_blank', 'width=1024,height=768,scrollbars=yes');" src="images/facebook-logo20.png" alt="Facebook" style="border-width:0px;" />
                        <img id="dnn_TwitterLogo" onclick="javascript:window.open('http://twitter.com/AdoptTogether', '_blank', 'width=1024,height=768,scrollbars=yes');" src="images/twitter-logo20.png" alt="Twitter" style="border-width:0px;" />
                    </div>
                </div>
                <div class="login-style">

                    <a id="dnn_dnnLOGIN_cmdLogin" class="SkinObject" href="javascript:__doPostBack('dnn$dnnLOGIN$cmdLogin','')"> </a>
                </div>
                <div class="cleared">
                </div>
            </div>
             <div class="menuWrapper">
                <span class="home">
                    <a href="http://www.adoptionstogether.org/"><span class="home-icon"></span></a>
                </span>
                <span>
                    <span id="dnn_NAV1_ctlNAV1" class="mainMenu" tabindex="0" style="-moz-user-select: none;">
                        <span id="dnn_NAV1_ctlNAV1ctr63" class=" mi mi0 id63 root first">
                            <span class="icn  "></span>
                           <a href="http://www.adoptionstogether.org/AboutUs.aspx" target="_blank" /><span id="dnn_NAV1_ctlNAV1t63" class="txt" style="cursor: pointer;">About Us</span></a>
                        </span>
                        <span id="dnn_NAV1_ctlNAV1ctr59" class=" mi mi1 id59 root">
                            <span class="icn  "></span>
                          <a href="http://www.adoptionstogether.org/Pregnant.aspx" target="_blank" /><span id="dnn_NAV1_ctlNAV1t59" class="txt" style="cursor: pointer;">Pregnant?</span></a>
                        </span>
                        <span id="dnn_NAV1_ctlNAV1ctr58" class=" mi mi2 id58 root">
                            <span class="icn  "></span>
                           <a href="http://www.adoptionstogether.org/Adopting.aspx" target="_blank" /><span id="dnn_NAV1_ctlNAV1t58" class="txt" style="cursor: pointer;">Adopting</span></a>
                        </span>
                        <span id="dnn_NAV1_ctlNAV1ctr130" class=" mi mi3 id130 root">
                            <span class="icn  "></span>
                        <a href="http://www.adoptionstogether.org/HomeStudy.aspx" target="_blank" /><span id="dnn_NAV1_ctlNAV1t130" class="txt" style="cursor: pointer;">Home Study</span></a>
                        </span>
                        <span id="dnn_NAV1_ctlNAV1ctr62" class=" mi mi4 id62 root">
                            <span class="icn  "></span>
                         <a href="http://www.adoptionstogether.org/Education.aspx" target="_blank" /><span id="dnn_NAV1_ctlNAV1t62" class="txt" style="cursor: pointer;">Education</span></a>
                        </span>
                        <span id="dnn_NAV1_ctlNAV1ctr60" class=" mi mi5 id60 root">
                            <span class="icn  "></span>
                         <a href="http://www.adoptionstogether.org/ResourcesandSupport.aspx" target="_blank" /><span id="dnn_NAV1_ctlNAV1t60" class="txt" style="cursor: pointer;">Resources and Support</span></a>
                        </span>
                        <span id="dnn_NAV1_ctlNAV1ctr121" class=" mi mi6 id121 root">
                            <span class="icn  "></span>
                         <a href="http://www.adoptionstogether.org/Events/EventsCalendar.aspx" target="_blank" /><span id="dnn_NAV1_ctlNAV1t121" class="txt" style="cursor: pointer;">Events</span></a>
                        </span>
                        <span id="dnn_NAV1_ctlNAV1ctr333" class=" mi mi7 id333 root last">
                            <span class="icn  "></span>
                          <a href="http://www.adoptionstogether.org/Blog.aspx#.TpW0sXJG3fI" target="_blank" /><span id="dnn_NAV1_ctlNAV1t333" class="txt" style="cursor: pointer;">Blog</span></a>
                        </span>
                    </span>
                </span>
             </div>
        </div>

        <table class="position" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr valign="top">
                <td id="dnn_Top1" width="33%" class="DNNEmptyPane">
                </td>

                <td id="dnn_Top2" width="33%" class="DNNEmptyPane">
                </td>

                <td id="dnn_Top3" class="DNNEmptyPane">
                </td>

            </tr>
        </table>
        <div class="art-contentLayout">
            <div id="dnn_PageTitle" class="page-title"><div class="DnnModule DnnModule-AdoptionsTogetherImage DnnModule-1046"><a name="1046"></a><div id="dnn_ctr1046_ContentPane"><!-- Start_Module_1046 --><div id="dnn_ctr1046_ModuleContent" class="DNNModuleContent ModAdoptionsTogetherImageC">

<img id="dnn_ctr1046_ViewImage_imgImage" src="Portals/0/images/Page%20Title%20Images/meet%20our%20families%20title.png" style="border-width:0px;" />

</div><!-- End_Module_1046 --></div>
</div></div>
<div style="margin-left:35px;"><span id="dnn_dnnTEXT_lblText" class="Normal">You are here &gt;</span>
<span id="dnn_dnnBREADCRUMB_lblBreadCrumb">
<a class="SkinObject" href="index.php">Pregnant?</a>
&gt;
<a class="SkinObject" href="index.php">Families Waiting to Adopt</a>
</span></div>
            <div class="art-sidebar1">
        
                <div id="dnn_sidebar3" class="DNNEmptyPane"></div>

                <div id="dnn_sidebar1" class="sidebarpane">
                    <div>

	<ul class="" id="dnn_dnnNAV_ctldnnNAV">
		<li id="dnn_dnnNAV_ctldnnNAVctr167"><a href="http://www.adoptionstogether.org/Pregnant/MakinganAdoptionPlan.aspx"><span>Making an Adoption Plan</span></a></li><li id="dnn_dnnNAV_ctldnnNAVctr173"><a href="http://www.adoptionstogether.org/Pregnant/RightsandResponsibilitiesoftheBirthFather.aspx"><span>Rights and Responsibilities of the Birth Father</span></a></li><li id="dnn_dnnNAV_ctldnnNAVctr174"><a href="http://www.adoptionstogether.org/Pregnant/WhatifmyFriendorFamilyMemberisPregnant.aspx"><span>What if my Friend or Family Member is Pregnant?</span></a></li><li id="dnn_dnnNAV_ctldnnNAVctr65"><a href="index.php"><span>Families Waiting to Adopt</span></a></li><li id="dnn_dnnNAV_ctldnnNAVctr170"><a href="http://www.adoptionstogether.org/Pregnant/Resources.aspx"><span>Resources</span></a></li><li id="dnn_dnnNAV_ctldnnNAVctr79"><a href="http://www.adoptionstogether.org/Pregnant/SpeaktoaCounselor.aspx"><span>Speak to a Counselor</span></a></li><li id="dnn_dnnNAV_ctldnnNAVctr175"><a href="http://www.adoptionstogether.org/Pregnant/BirthMotherStories.aspx"><span>Birth Mother Stories</span></a></li><li id="dnn_dnnNAV_ctldnnNAVctr273"><a href="http://www.birthparentblog.com/"><span>Birth Parent Blog</span></a></li>
	</ul>
</div>
  <?php
//echo "<pre>";
//print_r($aData);

$cnt  =   1;
$totalCnt   =    count($aData);
foreach($aData as $ffkey)
{
$familyData=explode(';-',$ffkey);

if($cnt == 1 || $cnt == 7) { ?>

<div style ="position:relative;float:left;width:70px;height:auto;margin-left: 30px;">
<?php }

$profile = $familyData[0];
//$datas = file_get_contents('http://ctstagepf.parentfinder.com/V1.0/achose1.php?profile='.$profile);
//$aDatas = (explode(";-",$datas));
//$datap = file_get_contents('http://ctstagepf.parentfinder.com/V1.0/test1.php?profile='.$profile);
//$aDatap = (explode(";-",$datap));

//echo "Test".$familyData[3];


if($profile != ''){

	if ($i < $dividedBy ) {

?>
			<?php if($familyData[3]!='') { ?>

     		<div class="oneside1">

                    <div class="oneside"><a href="mainProfile.php?profile=<?=$profile?>" class="photob"><img src="<?=$familyData[3]?>" width="64" height="64" border="0"/></a></div>
                    <div style="text-align: center;margin-top:10px;"><a href="mainProfile.php?profile=<?=$profile?>" class="black"><?=$familyData[1]?> <? if($familyData[2] != ''){ ?> &amp; <?=$familyData[2]?> <? } ?></a></div>

                </div>
		<?php }
			else { ?>

			<div class="oneside1">

                        <div class="oneside"><img src="images/noimage.jpg" width="64" height="64" border="0"/></div>
                        <div style="text-align: center;margin-top:10px;"><a href="mainProfile.php?profile=<?=$profile?>" class="black"><?=$familyData[1]?> <? if($familyData[2] != ''){ ?> &amp; <?=$familyData[2]?> <? } ?></a></div>

                        </div>

			<?php
			}
			?>

       <?php
        if($cnt == 6 && $totalCnt > 6){
            ?>
                </div>
            <?php
        }

	}



        else {

?>
			<?php if ($i == $dividedBy) {?>

			<?php } ?>

			<?php if($familyData[3]!='') { ?>

     		<div class="oneside1">

                <div class="oneside"><a href="mainProfile.php?profile=<?=$profile?>" class="photob"><img src="<?=$familyData[3]?>"   width="64" height="64" border="0"/></a></div>
                <div style="text-align: center;margin-top:10px;"><a href="mainProfile.php?profile=<?=$profile?>" class="black"><?=$familyData[1]?> <? if($familyData[2] != ''){ ?> &amp; <?=$familyData[2]?> <? }?></a></div>

                </div>
                	<?php }

			else { ?>

                     <div class="oneside1">
			<div class="oneside">
                            <img src="images/noimage.jpg" width="64" height="64" border="0"/></div>
                            <div style="text-align: center;margin-top:10px;"><a href="mainProfile.php?profile=<?=$profile?>" class="black"><?=$familyData[1]?> <? if($familyData[2] != ''){ ?> &amp; <?=$familyData[2]?> <? }?></a></div>

                        </div>

			<?php
			}
			?>



                                <?php

                                   if($cnt == 6){
            ?>
                
            <?php
        }

                        }


		$i++;
	}

	$k++;

$cnt++;
}
?>
     <?php  if(!isset($_GET['max_race_id'])){ ?>
<!-- -->
<?php
if(isset($_GET['pstart']) && $_GET['pstart'] != 0){
	$lpend = 12;//$_GET['pend'] - 5;
	//$lpstart = $lpend - 12;
       $lpstart = $_GET['pstart'] - 12;
	$spage = $_GET['page'] - 1;
	echo '<a href="mainProfile.php?pstart='.$lpstart.'&pend='.$lpend.'&page='.$spage.'&profile='.$_GET['profile'].'" class="more">Prev</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
}
if(isset($_GET['search'])){
	$data = file_get_contents($site['url'].'badgewaitingfamiliesat.php?getpa=true&search='.$_GET[search]);
}else{
	$data = file_get_contents($site['url'].'badgewaitingfamiliesat.php?getpa=true');
	$aData = (explode("#####",$data));
	//print_r($aData);
	$i=0;
	$p = 0;
	$pstart = 0;
	$pend = 12;
        //print_r($data);
        // echo "aaaa ".count($aData);
	foreach($aData as $key => $page)

	{
		if($aData[$p] != ''){
			$i++;
			if($_GET['page'] == $i){
				echo '<b>'.$i.'</b> &nbsp;';
				if($i == ''){
					$currentpage = 1;
				}else{
					$currentpage = $i;
				}
			}
			else{
				if(!isset($_GET['page']) && $i == 1)
				{
				echo '<div style=margin-left:30px;margin-top:10px;><b>'.$i.'</b></div> &nbsp;';
				}
				else
					//echo '<a href="index.php?pstart='.$pstart.'&pend='.$pend.'&page='.$i.'">'.$i.'</a> &nbsp;';
					echo '<a href="mainProfile.php?pstart='.$pstart.'&pend=12&page='.$i.'&profile='.$_GET['profile'].'">'.$i.'</a> &nbsp;';
			}
			$pstart = $pend + 0;
			$pend = $pend + 12;
		}
		$p++;
	}

	if(!isset($currentpage)){
	$currentpage = 1;
	}

	//if(($_GET['pend'] + 5) != $pend){
	if($currentpage != $i){
		if(isset($_GET['pend'])){
			$lpstart = $_GET['pstart'] + 12;//$_GET['pend'] + 1;
			$lpend = 12;
                //$_GET['pend'] + 5;
			$page = $_GET['page'] + 1;
		}else{
			$lpstart = 12;
			$lpend = 12;
			$page = 2;
		}

		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="mainProfile.php?pstart='.$lpstart.'&pend='.$lpend.'&page='.$page.'&profile='.$_GET['profile'].'" class="next">Next</a>';

	}
} // search if end

?></div>
<!-- <div style=" text-align:center; width:346px;">Displaying page <?=$currentpage?> of <?=$i?></div> -->

<!-- -->
<?php } ?>

<?php  if(isset($_GET['max_race_id'])) { ?>
<!-- -->
<?php

if(isset($_GET['pstart']) && $_GET['pstart'] != 0){
	$lpend = 12;//$_GET['pend'] - 5;
	//$lpstart = $lpend - 12;
       $lpstart = $_GET['pstart'] - 12;
	$spage = $_GET['page'] - 1;


        //echo '<a href="index.php?max_race_id=7&famreg='.$famregz.'&pareth='.$parethz.'&beth='.$bethz.'&family_num_children='.$_GET[family_num_children].'&family_state='.$_GET[family_state].'&pstart='.$lpstart.'&pend='.$lpend.'&page='.$spage.'" class="more">Prev</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';


  echo '<a href="mainProfile.php?max_race_id=7&beth[]='.$bethz.'&pareth[]='.$parethz.'&famreg[]='.$famregz.'&family_num_children='.$_GET[family_num_children].'&family_state='.$_GET[family_state].'&pstart='.$lpstart.'&pend='.$lpend.'&page='.$spage.'&button=Submit+Search" class="more">Prev</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';






//?famreg='.$famregz.'&pareth='.$parethz.'&beth='.$bethz.'&family_num_children='.$_GET[family_num_children].'&family_state='.$_GET[family_state]



}
if(isset($_GET['search'])){
	//$data = file_get_contents('http://ctstagepf.parentfinder.com/V1.0/badgewaitingfamiliesat.php?getpa=true&search='.$_GET[search]);
}else {
	//$data = file_get_contents('http://ctstagepf.parentfinder.com/V1.0/badgewaitingfamiliesat.php?getpa=true');

$data = file_get_contents($site['url'].'badgewaitingfamiliesat.php?getpa=true&max_race_id=7&famreg='.$famregz.'&pareth='.$parethz.'&beth='.$bethz.'&family_num_children='.$_GET[family_num_children].'&family_state='.$_GET[family_state]);

	$aData = (explode("#####",$data));
	//print_r($aData);
	$i=0;
	$p = 0;
	$pstart = 0;
	$pend = 12;

       // echo "counter ".count($aData);
        foreach($aData as $key => $page)

	//for($cnt = 0; $cnt <= 42; $cnt =$cnt+12)
	{
		if($aData[$p] != ''){
			$i++;
			if($_GET['page'] == $i){
				echo '<b>'.$i.'</b> &nbsp;';
				if($i == ''){
					$currentpage = 1;
				}else{
					$currentpage = $i;
				}
			}
			else{
				if(!isset($_GET['page']) && $i == 1)
				{
				echo '<b>'.$i.'</b> &nbsp;';
                                    
				}
				else
					//echo '<a href="index.php?pstart='.$pstart.'&pend='.$pend.'&page='.$i.'">'.$i.'</a> &nbsp;';echo '<a href="index.php?pstart='.$pstart.'&pend=12&page='.$i.'">'.$i.'</a> &nbsp;';echo '<a href="index.php?pstart='.$pstart.'&pend=12&page='.$i.'">'.$i.'</a> &nbsp;';
					//echo '<a href="index.php?max_race_id=7&beth[]='.$bethz.'&pareth='.$parethz.'&famreg='.$famregz.'&family_num_children='.$_GET[family_num_children].'&family_state='.$_GET[family_state].'&page=family_profile%2Fbrowse&button=Submit+Search">'.$i.'</a> &nbsp;'; pstart='.$pstart.'&pend=12&page='.$i.'"
                                   echo '<a href="mainProfile.php?max_race_id=7&beth[]='.$bethz.'&pareth[]='.$parethz.'&famreg[]='.$famregz.'&family_num_children='.$_GET[family_num_children].'&family_state='.$_GET[family_state].'&pstart='.$pstart.'&pend=12&page='.$i.'&button=Submit+Search">'.$i.'</a> &nbsp;';

			}
			$pstart = $pend + 0;
			$pend = $pend + 12;
		}
		$p++;
	}

	if(!isset($currentpage)){
	$currentpage = 1;
	}

	//if(($_GET['pend'] + 5) != $pend){
	if($currentpage != $i){
		if(isset($_GET['pend'])){
			$lpstart = $_GET['pstart'] + 12;//$_GET['pend'] + 1;
			$lpend = 12;
                //$_GET['pend'] + 5;
			$page = $_GET['page'] + 1;
		}else{
			$lpstart = 12;
			$lpend = 12;
			$page = 2;
		}
               if($i !=''){
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="mainProfile.php?max_race_id=7&beth[]='.$bethz.'&pareth[]='.$parethz.'&famreg[]='.$famregz.'&family_num_children='.$_GET[family_num_children].'&family_state='.$_GET[family_state].'&pstart='.$lpstart.'&pend='.$lpend.'&page='.$page.'&button=Submit+Search" class="next">Next</a>';
                   }
       // echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="index.php?max_race_id=7&famreg='.$famregz.'&pareth='.$parethz.'&beth='.$bethz.'&family_num_children='.$_GET[family_num_children].'&family_state='.$_GET[family_state].'&pstart='.$lpstart.'&pend='.$lpend.'&page='.$page.'" class="next">Next</a>';
	}
//} // search if end
}
?>

<!-- -->
<?php } ?>           </div>

            </div>
            
            <div class="art-content">
                <div style="margin-left:10px;margin-top:15px;"><a href="<?php echo $site['url']; ?>">
<img class="mainLogo" alt="logo" src="images/logo.png"></div>
</a><div>&nbsp;</div><div>&nbsp;</div>
<div class="left_body_box">
<div style="margin-left:-35px;">
    <div class="onesidemain1">
   <div class="onesidemain" ><img src="<?php if ($aDatanew1[3] == '') echo 'images/noimage.jpg'; else echo $aDatanew1[3];?>" width="150" height="150" title="<?=$aDatanew1[1]?><?php if($aDatanew1[2] != '') { ?> & <?php } ?><?=$aDatanew1[2]?>" width="100" height="75" alt="<?=$aDatanew1[1]?><?php if($aDatanew1[2] != '') { ?> & <?php } ?><?=$aDatanew1[2]?>"><!--<img src="<?=$aData1[9]?>" title="Adoptive Family Dave &amp; Krissy" width="300" height="422">--></div>
   <div style="text-align: center;margin-top:10px;"><a href="mainProfile.php?profile=<?=$_GET['profile']?>" class="black"><?php echo $getAboutUsArray[0]; ?> <?php if($getAboutUsArray[1] != ''){ ?> &amp; <?php echo $getAboutUsArray[1] ?> <?php } ?></a></div>

</div>
    </div>
 <div style="margin-top:10px;margin-left:15px"><a href="contact.php?profile=<?=$_GET['profile']?>" >  <img class="mainLogo" alt="logo" src="images/contactus_btn.png"></a></div>

    </div>
    <div class="menu_e">
   <ul class="internalul">
   <li class="internalli"><a href="mainProfile.php?profile=<?=$_GET['profile']?>" > <img class="mainLogo" alt="logo" src="images/mainprofile_btn.png"></a></li>
   <li class="internalli"><a href="photo.php?profile=<?=$_GET['profile']?>" >  <img class="mainLogo" alt="logo" src="images/photographs_btn.png"></a></li>
   <li class="internalli"><a href="video.php?profile=<?=$_GET['profile']?>" >  <img class="mainLogo" alt="logo" src="images/video_btn.png"></a></li>

   </ul>


  
   </div>


<?php if($getAboutUsArray[1] != '') { ?>
<table  width='399' border=0 cellspacing=0 cellpading=0  class=white_headng_cntr  style=margin-left:220px;>
<tr bgcolor=#4C328E> <td align=center class=tdstyle width="33%">&nbsp;</td><td align=center class=tdstyle width="33%"><?php echo $getAboutUsArray[0]; ?></td><td align=center class=tdstyle width="33%"><?php echo $getAboutUsArray[1]; ?></td></td>
</table>
<table  width='399' border=0 cellspacing=0 cellpading=0 style=margin-left:220px;>
<?php if ($getAboutUsArray[18] != '' && $getAboutUsArray[18] != '0000-00-00' && $getAboutUsArray[19] != '' && $getAboutUsArray[19] != '0000-00-00') { ?>
<tr> <td class=bold_black_ctn width="33%"><font color=black>Age:</td><td class=tdstyle width="33%" style="border-right:0px;"><?php echo calc_age($getAboutUsArray[18]) //echo $da1;?></font></td><td class=tdstyle width="33%"><?php echo calc_age($getAboutUsArray[19]) //echo $da2;?></font></td></tr>
<?php } ?>
<tr> <td class=bold_black_ctn style="border:993400 solid 1px" width="33%"><font color=black>Education:</td> <td class=tdstyle style="border-right:0px;"><?php echo $getAboutUsArray[2]; ?></font></td><td class=tdstyle><?php echo $getAboutUsArray[13]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Profession:</td> <td class=tdstyle style="border-right:0px;"><?php echo $getAboutUsArray[3]; ?></font></td><td class=tdstyle><?php echo $getAboutUsArray[14]; ?></font></td></tr>
<tr><td class=bold_black_ctn><font color=black>Ethnicity:</td> <td class=tdstyle style="border-right:0px;"><?php echo $getAboutUsArray[4]; ?></font></td><td class=tdstyle><?php echo $getAboutUsArray[15]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Religion:</td> <td class=tdstyle style="border-right:0px;"><?php echo $getAboutUsArray[5]; ?></font></td><td class=tdstyle><?php echo $getAboutUsArray[16]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Smoking:</td> <td class=tdstyle style="border-right:0px;"><?php echo $getAboutUsArray[6]; ?></font></td><td class=tdstyle><?php echo $getAboutUsArray[17]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>State:</td> <td class=tdstyle colspan="2"><?php echo $getAboutUsArray[7]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Married:</td> <td class=tdstyle colspan="2"><?php echo $getAboutUsArray[8]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Residency:</td> <td class=tdstyle colspan="2"><?php echo $getAboutUsArray[9]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Neighborhood:</td> <td class=tdstyle colspan="2"><?php echo $getAboutUsArray[10]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Family Structure:</td> <td class=tdstyle colspan="2"><?php echo $getAboutUsArray[11]; ?></font></td></tr>
<tr> <td class=bold_black_ctnl><font color=black>Pet(s):</td> <td class=tdstyleb colspan="2"><?php echo $getAboutUsArray[12]; ?></font></td></tr>
</table>
<?php }
else {
?>
<table  width='399' border=0 cellspacing=0 cellpading=0  class=white_headng_cntr style=margin-left:220px;>
<tr bgcolor=#4C328E> <td align=center class=tdstyle><?php echo $getAboutUsArray[0]; ?></td></td>
</table>
<table  width='399' border=0 cellspacing=0 cellpading=0 style=margin-left:220px;>
<?php if ($getAboutUsArray[18] != '' && $getAboutUsArray[18] != '0000-00-00') { ?>
<tr> <td class=bold_black_ctn ><font color=black>Age:</td><td class=tdstyle><?php echo $da1;?></font></td></tr>
<?php } ?>
<tr> <td class=bold_black_ctn style=border:993400 solid 1px><font color=black>Education:</td> <td class=tdstyle><?php echo $getAboutUsArray[2]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Profession:</td> <td class=tdstyle><?php echo $getAboutUsArray[3]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Ethnicity:</td> <td class=tdstyle><?php echo $getAboutUsArray[4]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Religion:</td> <td class=tdstyle><?php echo $getAboutUsArray[5]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Smoking:</td> <td class=tdstyle><?php echo $getAboutUsArray[6]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>State:</td> <td class=tdstyle><?php echo $getAboutUsArray[7]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Married:</td> <td class=tdstyle><?php echo $getAboutUsArray[8]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Residency:</td> <td class=tdstyle><?php echo $getAboutUsArray[9]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Neighborhood:</td> <td class=tdstyle><?php echo $getAboutUsArray[10]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Family Structure:</td> <td class=tdstyle><?php echo $getAboutUsArray[11]; ?></font></td></tr>
<tr> <td class=bold_black_ctnl><font color=black>Pet(s):</td> <td class=tdstyleb><?php echo $getAboutUsArray[12]; ?></font></td></tr>
</table>
<?php
}
?>

   
   <div class="row_three">
    <?php if ($aData1[2] != '') { ?>
   <div class="heading_content">Birth Parent Letter</div>

   <div class="body_content"><?=$aData1[2]?>

   </div>
   <?php } ?>
   <?php foreach ($aDataBlocks as $blockKey => $blockValue) :?>
		<?php if($blockKey >= 0 ) {?>
			<?php echo $blockValue;?>
		<?php } ?>
	<?php endforeach; ?>
<div class="heading_content">Child Preferences</div>
<div>&nbsp;</div>

<table  width='599' border=0 cellspacing=0 cellpading=0 style='margin-left:50px;'>
<tr> <td class=bold_black_ctn ><font color=black>Child Age:</td> <td class=tdstyle><?php echo $getAboutUsArray[25]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Child Ethnicity:</td> <td class=tdstyle><?php echo $getAboutUsArray[26]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Child Gender:</td> <td class=tdstyle><?php echo $getAboutUsArray[27]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Special Needs:</td> <td class=tdstyle><?php echo $getAboutUsArray[28]; ?></font></td></tr>
<tr> <td class=bold_black_ctnl><font color=black>Child Desired:</td> <td class=tdstyleb><?php echo $getAboutUsArray[29]; ?></font></td></tr>
</table>

<table  width='599' border=0 cellspacing=0 cellpading=0 style='margin-left:50px;'>


</table>
<div>&nbsp;</div>
   <?php if ($getAboutUsArray[20] != '') {?>
   <div class="heading_content">About <?php echo $getAboutUsArray[0]; ?> </div>

   <div class="body_content"><?=$getAboutUsArray[20]?>

   </div>
   <?php } ?>
   <?php if ($getAboutUsArray[21] != '') {?>
   <div class="heading_content">About <?php echo $getAboutUsArray[1]; ?></div>

   <div class="body_content"><?=$getAboutUsArray[21]?>

   </div>
   <?php } ?>
   <?php if ($getAboutUsArray[22] != '') {?>
   <div class="heading_content">Hobbies of  <?php echo $getAboutUsArray[0] ;?></div>

   <div class="body_content"><?=$getAboutUsArray[22]?>
       <?php } ?>
  <?php if ($getAboutUsArray[30] != '') {?>
   </div>
   <div class="heading_content">Hobbies of  <?php echo $getAboutUsArray[1] ;?></div>

   <div class="body_content"><?=$getAboutUsArray[30]?>

   </div>
   <?php } ?>
    <?php if ($getAboutUsArray[23] != '') {?>
   <div class="heading_content">Interests of  <?php echo $getAboutUsArray[0] ;?></div>

   <div class="body_content"><?=$getAboutUsArray[23]?>

   </div>
   <?php } ?>
    <?php if ($getAboutUsArray[31] != '') {?>
   <div class="heading_content">Interests of  <?php echo $getAboutUsArray[1];?></div>

   <div class="body_content"><?=$getAboutUsArray[31]?>

   </div>
   <?php } ?>
    <?php if ($getAboutUsArray[24] != '') {?>
   <div class="heading_content">Our Home</div>

  <div class="body_content">
   <?= $getAboutUsArray[24]?>

   </div>
   <?php } ?>

   </div>
        </div>
   </div>
        <div class="cleared">
        </div>
        <table class="position" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr valign="top">
                <td id="dnn_Bottom1" width="33%" class="DNNEmptyPane">

                </td>

                <td id="dnn_Bottom2" width="33%" class="DNNEmptyPane">
                </td>

                <td id="dnn_Bottom3" class="DNNEmptyPane">
                </td>

            </tr>
        </table>


        <div class="art-Footer">
            <div class="art-Footer-inner">
                <div class="art-Footer-text">
                    <p>
                        <a id="dnn_dnnTerms_hypTerms" class="SkinObject" rel="nofollow" href="http://www.adoptionstogether.org/terms.aspx">Terms Of Use</a>
                        |
                        <a id="dnn_dnnPrivacy_hypPrivacy" class="SkinObject" rel="nofollow" href="http://www.adoptionstogether.org/privacy.aspx">Privacy Statement</a>
                        <br />

                        <span id="dnn_dnnCopyright_lblCopyright" class="SkinObject">Copyright 2010 by Adoptions Together</span>

                    </p>
                </div>
                <div class="cleared">
                </div>
            </div>
            <div class="art-Footer-background">
            </div>

        </div>
        <div class="cleared">
        </div>
    </div>
</div>
<div class="cleared">
</div>
<p class="art-page-footer">
</p>
<input name="ScrollTop" type="hidden" id="ScrollTop" />
</body>
</html>









