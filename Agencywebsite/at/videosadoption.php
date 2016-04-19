<?php
session_start();
ini_set("display_errors",0);
//ini_set('max_execution_time', 300); //300 seconds = 5 minutes. Place this at the top of yo
$getaboutus = file_get_contents('http://localhost/parentfinders/getaboutus_exe.php?profile='.$_GET['profile']);
$getAboutUsArray = (explode(";-",$getaboutus));
array_pop($getAboutUsArray);
//print_r($getAboutUsArray);

$aDataBlock = file_get_contents('http://localhost/parentfinders/getblockshoa_exe.php?profile='.$_GET['profile'].'');
$aDataBlocks = (explode(";-",$aDataBlock));
//print_r($aDataBlocks);
array_pop($aDataBlocks);

$achildata = file_get_contents('http://localhost/parentfinders/getchild.php?profile='.$_GET['profile'].'');
$achildatas = (explode(";-",$achildata));
array_pop($achildatas);

$data = file_get_contents('http://localhost/parentfinders/test.php?profile='.$_GET['profile']);
$aData1 = (explode(";-",$data));
//print_r($aData);
$bpl = mb_substr($aData1[0], 0, 260);
$profile = $_GET['profile'];
?>
<?php

 //$data = file_get_contents('http://localhost/parentfinders/test.php?profile='.$_GET['profile'].'&deta=details');
 //$aData = (explode(";-",$data));
 $data = file_get_contents('http://localhost/parentfinders/test.php?profile='.$_GET['profile']);
 $aData = (explode(";-",$data));
$data = file_get_contents('http://localhost/parentfinders/getphotosvideos.php?profile='.$_GET['profile'].'&p=watch');
//$data = file_get_contents('http://localhost/parentfinders/getphotosvideos.php?profile=644&p=watch');
$photos = (explode(";-",$data));
array_pop($photos);

$profile = $_GET['profile'];
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

    $data = file_get_contents('http://localhost/parentfinders/badgewaitingfamilies.php?pstart='.$_GET[pstart].'&pend='.$_GET['pend']);
   }

else {

  $data = file_get_contents('http://localhost/parentfinders/badgewaitingfamilies.php?famreg='.$famregz.'&pareth='.$parethz.'&beth='.$bethz.'&family_num_children='.$_GET[family_num_children].'&family_state='.$_GET[family_state].'&pstart='.$_GET['pstart'].'&pend='.$_GET['pend']);

   }
}
elseif(isset($_GET['search']) && !isset($_GET['max_race_id'])){
$data = file_get_contents('http://localhost/parentfinders/badgewaitingfamilies.php?search='.$_GET[search]);

}
elseif(isset($_GET['max_race_id'])){

$data = file_get_contents('http://localhost/parentfinders/badgewaitingfamilies.php?famreg='.$famregz.'&pareth='.$parethz.'&beth='.$bethz.'&family_num_children='.$_GET[family_num_children].'&family_state='.$_GET[family_state]);

}
else {

    $data = file_get_contents('http://localhost/parentfinders/badgewaitingfamilies.php');

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
</title>
       <style type="text/css">
<!--
/**
 * Slideshow style rules.
 */
#slideshow {
	margin:0 auto;
	width:600px;
	height:263px;
	position:relative;
}
#slideshow #slidesContainer {
  margin:0 auto;
  width:560px;
  height:263px;
  overflow:auto; /* allow scrollbar */
  position:relative;
}
#slideshow #slidesContainer .slide {
  margin:0 auto;
  width:540px; /* reduce by 20 pixels of #slidesContainer to avoid horizontal scroll */
  height:240px;
}

/**
 * Slideshow controls style rules.
 */
.control {
  display:block;
  width:39px;
  height:263px;
  text-indent:-10000px;
  position:absolute;
  cursor: pointer;
}
#leftControl {
  top:0;
  left:0;
  background:transparent url(img/control_left.jpg) no-repeat 0 0;
}
#rightControl {
  top:0;
  right:0;
  background:transparent url(img/control_right.jpg) no-repeat 0 0;
}

/**
 * Style rules for Demo page
 */


#pageContainer {
   background-color: #FCEDCE;
    height: auto;
    margin: 0 auto;
    width: 692px;
}



.slide img {
  float: left;
  margin-left:135px;
}

-->
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  var currentPosition = 0;
  var slideWidth = 560;
  var slides = $('.slide');
  var numberOfSlides = slides.length;

  // Remove scrollbar in JS
  $('#slidesContainer').css('overflow', 'hidden');

  // Wrap all .slides with #slideInner div
  slides
    .wrapAll('<div id="slideInner"></div>')
    // Float left to display horizontally, readjust .slides width
	.css({
      'float' : 'left',
      'width' : slideWidth
    });

  // Set #slideInner width equal to total width of all slides
  $('#slideInner').css('width', slideWidth * numberOfSlides);

  // Insert controls in the DOM
  $('#slideshow')
    .prepend('<span class="control" id="leftControl">Clicking moves left</span>')
    .append('<span class="control" id="rightControl">Clicking moves right</span>');

  // Hide left arrow control on first load
  manageControls(currentPosition);

  // Create event listeners for .controls clicks
  $('.control')
    .bind('click', function(){
    // Determine new position
	currentPosition = ($(this).attr('id')=='rightControl') ? currentPosition+1 : currentPosition-1;

	// Hide / show controls
    manageControls(currentPosition);
    // Move slideInner using margin-left
    $('#slideInner').animate({
      'marginLeft' : slideWidth*(-currentPosition)
    });
  });

  // manageControls: Hides and Shows controls depending on currentPosition
  function manageControls(position){
    // Hide left arrow if position is first slide
	if(position==0){ $('#leftControl').hide() } else{ $('#leftControl').show() }
	// Hide right arrow if position is last slide
    if(position==numberOfSlides-1){ $('#rightControl').hide() } else{ $('#rightControl').show() }
  }
});
</script>
<!--Make sure page contains valid doctype at the very top!-->


<link rel="stylesheet" href="css/galleria.classic.css" id="galleria-theme">
<link rel="stylesheet" type="text/css" media="screen,print" href="css/newCssByJk.css">

 <style>

            /* Demo styles */

            body{border-top:4px solid #000;}
            .content{color:#777;font:12px/1.4 "helvetica neue",arial,sans-serif;width:620px;margin:20px auto;}
            h1{font-size:12px;font-weight:normal;color:#ddd;margin:0;}
            p{margin:0 0 20px}
           /* a {color:#22BCB9;text-decoration:none;} */
            .cred{margin-top:20px;font-size:11px;}

            /* This rule is read by Galleria to define the gallery height: */
            #galleria{height:620px;}

        </style>

        <!-- load jQuery -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
        <!-- load Galleria -->
<script src="js/galleria-1.2.2.min.js"></script>
<script src="js/galleria.classic.min.js"></script>
<script type="text/javascript">
function setFPsCookie(c_name,value,exdays)
{
var exdate=new Date();
exdate.setDate(exdate.getDate() + exdays);
var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
document.cookie=c_name + "=" + c_value;
if(exdays > 0){
alert('Family added to favorites');
}else{
alert('Family removed from favorites');
}
location.reload(true);
}
</script>
<head>
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
                        <img src="Portals/_default/skins/AdoptionsTogether/images/contact.png" />
                    </a>
                </div>
                <div class="header-buttons">
                    <a href="/ContactUs.aspx"><div class="button">Contact Us</div></a>
                    <a href="/InformationPackets.aspx"><div class="button">Info Packets</div></a>
                    <a href="/Newsletter.aspx"><div class="button">Newsletter</div></a>

                    <a href="/Donate.aspx"><div id="why-donate" class="button">DONATE NOW</div></a>
                </div>
                <div class="search-style">
                    <div><span id="dnn_dnnSEARCH_ClassicSearch">


  <input name="dnn$dnnSEARCH$txtSearch" type="text" maxlength="255" size="20" id="dnn_dnnSEARCH_txtSearch" class="NormalTextBox" onkeydown="return __dnn_KeyDown('13', 'javascript:__doPostBack(%27dnn$dnnSEARCH$cmdSearch%27,%27%27)', event);" />&nbsp;
  <a id="dnn_dnnSEARCH_cmdSearch" class="SkinObject" href="javascript:__doPostBack('dnn$dnnSEARCH$cmdSearch','')"><div class=button>Search</div></a>
</span>


</div>
                    </br>
                    <div class="header-social">
                        <div class="g-plusone" data-size="medium" data-annotation="inline" data-width="120"></div>
                        <img id="dnn_FacebookLogo" onclick="javascript:window.open('http://www.facebook.com/?sk=2361831622#!/pages/Adoptions-Together/179352644749?ref=ts', '_blank', 'width=1024,height=768,scrollbars=yes');" src="../Portals/_default/Skins/AdoptionsTogether/images/facebook-logo20.png" alt="Facebook" style="border-width:0px;" />
                        <img id="dnn_TwitterLogo" onclick="javascript:window.open('http://twitter.com/AdoptTogether', '_blank', 'width=1024,height=768,scrollbars=yes');" src="../Portals/_default/Skins/AdoptionsTogether/images/twitter-logo20.png" alt="Twitter" style="border-width:0px;" />
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
                    <a href="/"><span class="home-icon"></span></a>
                </span>
                <span>
                    <span id="dnn_NAV1_ctlNAV1" class="mainMenu" tabindex="0" style="-moz-user-select: none;">
                        <span id="dnn_NAV1_ctlNAV1ctr63" class=" mi mi0 id63 root first">
                            <span class="icn  "></span>
                            <span id="dnn_NAV1_ctlNAV1t63" class="txt" style="cursor: pointer;">About Us</span>
                        </span>
                        <span id="dnn_NAV1_ctlNAV1ctr59" class=" mi mi1 id59 root">
                            <span class="icn  "></span>
                            <span id="dnn_NAV1_ctlNAV1t59" class="txt" style="cursor: pointer;">Pregnant?</span>
                        </span>
                        <span id="dnn_NAV1_ctlNAV1ctr58" class=" mi mi2 id58 root">
                            <span class="icn  "></span>
                            <span id="dnn_NAV1_ctlNAV1t58" class="txt" style="cursor: pointer;">Adopting</span>
                        </span>
                        <span id="dnn_NAV1_ctlNAV1ctr130" class=" mi mi3 id130 root">
                            <span class="icn  "></span>
                            <span id="dnn_NAV1_ctlNAV1t130" class="txt" style="cursor: pointer;">Home Study</span>
                        </span>
                        <span id="dnn_NAV1_ctlNAV1ctr62" class=" mi mi4 id62 root">
                            <span class="icn  "></span>
                            <span id="dnn_NAV1_ctlNAV1t62" class="txt" style="cursor: pointer;">Education</span>
                        </span>
                        <span id="dnn_NAV1_ctlNAV1ctr60" class=" mi mi5 id60 root">
                            <span class="icn  "></span>
                            <span id="dnn_NAV1_ctlNAV1t60" class="txt" style="cursor: pointer;">Resources and Support</span>
                        </span>
                        <span id="dnn_NAV1_ctlNAV1ctr121" class=" mi mi6 id121 root">
                            <span class="icn  "></span>
                            <span id="dnn_NAV1_ctlNAV1t121" class="txt" style="cursor: pointer;">Events</span>
                        </span>
                        <span id="dnn_NAV1_ctlNAV1ctr333" class=" mi mi7 id333 root last">
                            <span class="icn  "></span>
                            <span id="dnn_NAV1_ctlNAV1t333" class="txt" style="cursor: pointer;">Blog</span>
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
            <div class="art-sidebar1">
                <div id="dnn_PageImage" class="page-image"><div class="DnnModule DnnModule-AdoptionsTogetherImage DnnModule-892"><a name="892"></a><div id="dnn_ctr892_ContentPane"><!-- Start_Module_892 --><div id="dnn_ctr892_ModuleContent" class="DNNModuleContent ModAdoptionsTogetherImageC">

<img id="dnn_ctr892_ViewImage_imgImage" src="Portals/0/images/YoungInterracialCouple2.jpg" style="border-width:0px;" />

</div><!-- End_Module_892 --></div>
</div></div>
                <div id="dnn_sidebar3" class="DNNEmptyPane"></div>
                <div id="dnn_sidebar1" class="sidebarpane">
                    <div>

	<ul class="" id="dnn_dnnNAV_ctldnnNAV">
		<li id="dnn_dnnNAV_ctldnnNAVctr167"><a href="http://www.adoptionstogether.org/Pregnant/MakinganAdoptionPlan.aspx"><span>Making an Adoption Plan</span></a></li><li id="dnn_dnnNAV_ctldnnNAVctr173"><a href="http://www.adoptionstogether.org/Pregnant/RightsandResponsibilitiesoftheBirthFather.aspx"><span>Rights and Responsibilities of the Birth Father</span></a></li><li id="dnn_dnnNAV_ctldnnNAVctr174"><a href="http://www.adoptionstogether.org/Pregnant/WhatifmyFriendorFamilyMemberisPregnant.aspx"><span>What if my Friend or Family Member is Pregnant?</span></a></li><li id="dnn_dnnNAV_ctldnnNAVctr65"><a href="http://www.adoptionstogether.org/Pregnant/FamiliesWaitingtoAdopt.aspx"><span>Families Waiting to Adopt</span></a></li><li id="dnn_dnnNAV_ctldnnNAVctr170"><a href="http://www.adoptionstogether.org/Pregnant/Resources.aspx"><span>Resources</span></a></li><li id="dnn_dnnNAV_ctldnnNAVctr79"><a href="http://www.adoptionstogether.org/Pregnant/SpeaktoaCounselor.aspx"><span>Speak to a Counselor</span></a></li><li id="dnn_dnnNAV_ctldnnNAVctr175"><a href="http://www.adoptionstogether.org/Pregnant/BirthMotherStories.aspx"><span>Birth Mother Stories</span></a></li><li id="dnn_dnnNAV_ctldnnNAVctr273"><a href="http://www.birthparentblog.com/"><span>Birth Parent Blog</span></a></li>
	</ul>
</div>
  <?php
//echo "<pre>";
//print_r($aData);

$cnt  =   1;
foreach($aData as $ffkey)
{
$familyData=explode(';-',$ffkey);

if($cnt == 1 || $cnt == 7) { ?>

<div style =" position:relative;float:left;width:130px;height:auto;">
<?php }

$profile = $familyData[0];
//$datas = file_get_contents('http://ctstagepf.parentfinder.com/achose1.php?profile='.$profile);
//$aDatas = (explode(";-",$datas));
//$datap = file_get_contents('http://ctstagepf.parentfinder.com/test1.php?profile='.$profile);
//$aDatap = (explode(";-",$datap));

//echo "Test".$familyData[3];


if($profile != ''){

	if ($i < $dividedBy ) {

?>
			<?php if($familyData[3]!='') { ?>

     		<div class="one1">

                    <div class="one"><a href="mainProfile.php?profile=<?=$profile?>" class="photob"><img src="<?=$familyData[3]?>" width="92" height="" border="0"/></a></div>
                    <div style="margin-left:10px;margin-top:15px;width:125px;"><a href="mainProfile.php?profile=<?=$profile?>" class="black"><?=$familyData[1]?> <? if($familyData[2] != ''){ ?> &amp; <?=$familyData[2]?> <? } ?></a></div>

                </div>
		<?php }
			else { ?>

			<div class="one1">

                        <div class="one"><img src="images/noimage.jpg" width="92"  border="0"/></div>
                        <div style="margin-left:10px;margin-top:15px;width:125px;"><a href="mainProfile.php?profile=<?=$profile?>" class="black"><?=$familyData[1]?> <? if($familyData[2] != ''){ ?> &amp; <?=$familyData[2]?> <? } ?></a></div>

                        </div>

			<?php
			}
			?>

       <?php
        if($cnt == 6){
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

     		<div class="one1">

                <div class="one"><a href="mainProfile.php?profile=<?=$profile?>" class="photob"><img src="<?=$familyData[3]?>"   width="92" height="" border="0"/></a></div>
                <div style="margin-left:10px;margin-top:15px;width:125px;"><a href="mainProfile.php?profile=<?=$profile?>" class="black"><?=$familyData[1]?> <? if($familyData[2] != ''){ ?> &amp; <?=$familyData[2]?> <? }?></a></div>

                </div>
                	<?php }

			else { ?>

                     <div class="one1">
			<div class="one">
                            <img src="images/noimage.jpg" width="92" height="70" border="0"/></div>
                            <div style="margin-left:10px;margin-top:15px;width:125px;"><a href="mainProfile.php?profile=<?=$profile?>" class="black"><?=$familyData[1]?> <? if($familyData[2] != ''){ ?> &amp; <?=$familyData[2]?> <? }?></a></div>

                        </div>

			<?php
			}
			?>



                                <?php

                                   if($cnt == 6){
            ?>
                </div>
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
	echo '<a href="index.php?pstart='.$lpstart.'&pend='.$lpend.'&page='.$spage.'" class="more">Prev</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
}
if(isset($_GET['search'])){
	$data = file_get_contents('http://localhost/parentfinders/badgewaitingfamilies.php?getpa=true&search='.$_GET[search]);
}else{
	$data = file_get_contents('http://localhost/parentfinders/badgewaitingfamilies.php?getpa=true');
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
				//echo '<b>'.$i.'</b> &nbsp;';
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
					echo '<a href="index.php?pstart='.$pstart.'&pend=12&page='.$i.'">'.$i.'</a> &nbsp;';
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

		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="index.php?pstart='.$lpstart.'&pend='.$lpend.'&page='.$page.'" class="next">Next</a>';

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


  echo '<a href="index.php?max_race_id=7&beth[]='.$bethz.'&pareth[]='.$parethz.'&famreg[]='.$famregz.'&family_num_children='.$_GET[family_num_children].'&family_state='.$_GET[family_state].'&pstart='.$lpstart.'&pend='.$lpend.'&page='.$spage.'&button=Submit+Search" class="more">Prev</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';






//?famreg='.$famregz.'&pareth='.$parethz.'&beth='.$bethz.'&family_num_children='.$_GET[family_num_children].'&family_state='.$_GET[family_state]



}
if(isset($_GET['search'])){
	//$data = file_get_contents('http://ctstagepf.parentfinder.com/badgewaitingfamilies.php?getpa=true&search='.$_GET[search]);
}else {
	//$data = file_get_contents('http://ctstagepf.parentfinder.com/badgewaitingfamilies.php?getpa=true');

$data = file_get_contents('http://localhost/parentfinders/badgewaitingfamilies.php?getpa=true&max_race_id=7&famreg='.$famregz.'&pareth='.$parethz.'&beth='.$bethz.'&family_num_children='.$_GET[family_num_children].'&family_state='.$_GET[family_state]);

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
                                   echo '<a href="index.php?max_race_id=7&beth[]='.$bethz.'&pareth[]='.$parethz.'&famreg[]='.$famregz.'&family_num_children='.$_GET[family_num_children].'&family_state='.$_GET[family_state].'&pstart='.$pstart.'&pend=12&page='.$i.'&button=Submit+Search">'.$i.'</a> &nbsp;';

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
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php?max_race_id=7&beth[]='.$bethz.'&pareth[]='.$parethz.'&famreg[]='.$famregz.'&family_num_children='.$_GET[family_num_children].'&family_state='.$_GET[family_state].'&pstart='.$lpstart.'&pend='.$lpend.'&page='.$page.'&button=Submit+Search" class="next">Next</a>';
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
                <div class="art-Post">
                    <span id="dnn_dnnTEXT_lblText" class="Normal">You are here ></span>

                    <span id="dnn_dnnBREADCRUMB_lblBreadCrumb"><a href="http://www.adoptionstogether.org/Pregnant.aspx" class="SkinObject">Pregnant?</a> > <a href="http://www.adoptionstogether.org/Pregnant/FamiliesWaitingtoAdopt.aspx" class="SkinObject">Families Waiting to Adopt</a></span>

                    <div class="art-Post-body">

                        <div class="art-Post-inner art-article">
                            <div class="art-PostContent">

                                <table class="position" width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr valign="top">
                                        <td id="dnn_User1" width="50%" class="DNNEmptyPane">
                                        </td>

                                        <td id="dnn_User2" class="DNNEmptyPane">
                                        </td>

                                    </tr>
                                </table>



                                <table class="position" width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr valign="top">
                                        <td id="dnn_User3" width="50%" class="DNNEmptyPane">
                                        </td>

                                        <td id="dnn_User4" class="DNNEmptyPane">
                                        </td>

                                    </tr>
                                </table>

                            </div>
                            <div class="cleared">
                            </div>
                        </div>
                        <div class="cleared">
                        </div>

                    </div>
                </div><div>&nbsp;</div><div>&nbsp;</div><a href="http://ctstagepf.parentfinder.com/">
<img class="mainLogo" alt="logo" src="images/logo.png">
</a><div>&nbsp;</div><div>&nbsp;</div>
<div class="left_body_box">

    <div class="one1">
   <div class="one" ><img src="<?php if ($aData1[9] == '') echo 'images/noimage.jpg'; else echo $aData1[9];?>" title="Adoptive Family <?=$aData1[3]?><?php if($aData1[4] != '') { ?> & <?php } ?><?=$aData1[4]?>" width="92" alt="<?=$aData1[3]?><?php if($aData1[4] != '') { ?> & <?php } ?><?=$aData1[4]?>"><!--<img src="<?=$aData1[9]?>" title="Adoptive Family Dave &amp; Krissy" width="300" height="422">--></div>
   <div style="margin-left:10px;margin-top:5px;"><a href="mainProfile.php?profile=<?=$profile?>" class="black"><?php echo $getAboutUsArray[0]; ?> <?php if($getAboutUsArray[1] != ''){ ?> &amp; <?php echo $getAboutUsArray[1] ?> <?php } ?></a></div>

</div>
           <div style="margin-top:10px;"><a href="contactat.php?profile=<?=$_GET['profile']?>&v=watch" >  <img class="mainLogo" alt="logo" src="images/contactus_btn.png"></a></div>

    </div>
    <div class="menu_e">
   <ul class="internalul">
   <li class="internalli"><a href="mainProfilead.php?profile=<?=$_GET['profile']?>" > <img class="mainLogo" alt="logo" src="images/mainprofile_btn.png"></a></li>
   <li class="internalli"><a href="photographs.php?profile=<?=$_GET['profile']?>" >  <img class="mainLogo" alt="logo" src="images/photographs_btn.png"></a></li>
   <li class="internalli"><a href="videos.php?profile=<?=$_GET['profile']?>&v=watch" >  <img class="mainLogo" alt="logo" src="images/video_btn.png"></a></li>

   </ul>


  
   </div>


<?php if($getAboutUsArray[1] != '') { ?>
<table  width='399' border=0 cellspacing=0 cellpading=0  class=white_headng_cntr style=margin-left:220px;>
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
<table  width='399' border=2px cellspacing=0 cellpading=0 style=margin-left:220px; >
<?php if ($getAboutUsArray[18] != '' && $getAboutUsArray[18] != '0000-00-00') { ?>
<tr> <td class=bold_black_ctn ><font color=black>Age:</td><td class=tdstyle><?php echo $da1;?></font></td></tr>
<?php } ?>
<tr> <td class=bold_black_ctn style=border:993400 solid 1px><font color=black>Education:</td>0 <td class=tdstyle><?php echo $getAboutUsArray[2]; ?></font></td></tr>
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

   
 <div class="about_uswidth_photo">Photo Gallery</div>
<div class="photo_page">
<div id="pageContainer">

<div id="galleria">
		<?php foreach ($photos as $key=>$val) :?>
		<?php echo $val;?>
		<?php endforeach; ?>
		</div>
  <!-- Slideshow HTML -->
  <!--<div id="slideshow">
    <div id="slidesContainer">
      <div class="slide">
       <img src="img/1.jpg" />

      </div>
      <div class="slide">

         <img src="img/2.jpg" />

      </div>
      <div class="slide">
         <img src="img/3.jpg" />


      </div>
      <div class="slide">
       <img src="img/4.jpg" />
      </div>
    </div>
  </div>-->
  <!-- Slideshow HTML -->
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
<input name="__dnnVariable" type="hidden" id="__dnnVariable" value="`{`__scdoff`:`1`,`dnn_NAV1_ctlNAV1_json`:`{nodes:[{id:\`63\`,key:\`63\`,txt:\`About Us\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/AboutUs.aspx\`,img:\`\`,cssIcon:\` \`,nodes:[{id:\`317\`,key:\`317\`,txt:\`Mission and Vision\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/AboutUs/MissionandVision.aspx\`,img:\`\`,nodes:[{id:\`318\`,key:\`318\`,txt:\`Guiding Principles\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/AboutUs/MissionandVision/GuidingPrinciples.aspx\`,img:\`\`,nodes:[]}]},{id:\`339\`,key:\`339\`,txt:\`Annual Report\`,ca:\`3\`,url:\`http://www.issuu.com/adoptionstogether/docs/annual_report_2010\`,img:\`\`,nodes:[]},{id:\`151\`,key:\`151\`,txt:\`Locations\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/AboutUs/Locations.aspx\`,img:\`\`,nodes:[]},{id:\`165\`,key:\`165\`,txt:\`Our Staff\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/AboutUs/OurStaff.aspx\`,img:\`\`,nodes:[{id:\`166\`,key:\`166\`,txt:\`Board of Directors\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/AboutUs/OurStaff/BoardofDirectors.aspx\`,img:\`\`,nodes:[]}]},{id:\`152\`,key:\`152\`,txt:\`Employment Opportunities\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/AboutUs/EmploymentOpportunities.aspx\`,img:\`\`,nodes:[]},{id:\`163\`,key:\`163\`,txt:\`Volunteer and Internship Opportunities\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/AboutUs/VolunteerandInternshipOpportunities.aspx\`,img:\`\`,nodes:[]},{id:\`61\`,key:\`61\`,txt:\`News\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/AboutUs/News.aspx\`,img:\`\`,nodes:[]},{id:\`164\`,key:\`164\`,txt:\`Accreditations\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/AboutUs/Accreditations.aspx\`,img:\`\`,nodes:[]}]},{bcrumb:\`1\`,id:\`59\`,key:\`59\`,txt:\`Pregnant?\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Pregnant.aspx\`,img:\`\`,cssIcon:\` \`,nodes:[{id:\`167\`,key:\`167\`,txt:\`Making an Adoption Plan\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Pregnant/MakinganAdoptionPlan.aspx\`,img:\`\`,nodes:[]},{id:\`173\`,key:\`173\`,txt:\`Rights and Responsibilities of the Birth Father\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Pregnant/RightsandResponsibilitiesoftheBirthFather.aspx\`,img:\`\`,nodes:[]},{id:\`174\`,key:\`174\`,txt:\`What if my Friend or Family Member is Pregnant?\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Pregnant/WhatifmyFriendorFamilyMemberisPregnant.aspx\`,img:\`\`,nodes:[]},{bcrumb:\`1\`,selected:\`1\`,id:\`65\`,key:\`65\`,txt:\`Families Waiting to Adopt\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Pregnant/FamiliesWaitingtoAdopt.aspx\`,img:\`\`,nodes:[]},{id:\`170\`,key:\`170\`,txt:\`Resources\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Pregnant/Resources.aspx\`,img:\`\`,nodes:[]},{id:\`79\`,key:\`79\`,txt:\`Speak to a Counselor\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Pregnant/SpeaktoaCounselor.aspx\`,img:\`\`,nodes:[]},{id:\`175\`,key:\`175\`,txt:\`Birth Mother Stories\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Pregnant/BirthMotherStories.aspx\`,img:\`\`,nodes:[]},{id:\`273\`,key:\`273\`,txt:\`Birth Parent Blog\`,ca:\`3\`,url:\`http://www.birthparentblog.com/\`,img:\`\`,nodes:[]}]},{id:\`58\`,key:\`58\`,txt:\`Adopting\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting.aspx\`,img:\`\`,cssIcon:\` \`,nodes:[{id:\`66\`,key:\`66\`,txt:\`Considering Options\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/ConsideringOptions.aspx\`,img:\`\`,nodes:[]},{id:\`134\`,key:\`134\`,txt:\`Adopt an Infant\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptanInfant.aspx\`,img:\`\`,nodes:[{id:\`269\`,key:\`269\`,txt:\`Domestic Adoption Process\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptanInfant/DomesticAdoptionProcess.aspx\`,img:\`\`,nodes:[]},{id:\`155\`,key:\`155\`,txt:\`Domestic Adoption Fees\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptanInfant/DomesticAdoptionFees.aspx\`,img:\`\`,nodes:[]},{id:\`313\`,key:\`313\`,txt:\`Legal Questions About Adoption\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptanInfant/LegalQuestionsAboutAdoption.aspx\`,img:\`\`,nodes:[{id:\`314\`,key:\`314\`,txt:\`Adopt a Child in Maryland\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptanInfant/LegalQuestionsAboutAdoption/AdoptaChildinMaryland.aspx\`,img:\`\`,nodes:[]},{id:\`315\`,key:\`315\`,txt:\`Adopt a Child in Virginia\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptanInfant/LegalQuestionsAboutAdoption/AdoptaChildinVirginia.aspx\`,img:\`\`,nodes:[]},{id:\`316\`,key:\`316\`,txt:\`Adopt a Child in the District of Columbia\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptanInfant/LegalQuestionsAboutAdoption/AdoptaChildintheDistrictofColumbia.aspx\`,img:\`\`,nodes:[]}]},{id:\`178\`,key:\`178\`,txt:\`Adoption Stories\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptanInfant/AdoptionStories.aspx\`,img:\`\`,nodes:[]},{id:\`177\`,key:\`177\`,txt:\`Resources\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptanInfant/Resources.aspx\`,img:\`\`,nodes:[]}]},{id:\`325\`,key:\`325\`,txt:\`Adopt an Older Child\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptanOlderChild.aspx\`,img:\`\`,nodes:[{id:\`326\`,key:\`326\`,txt:\`Permanency Opportunities Project\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptanOlderChild/PermanencyOpportunitiesProject.aspx\`,img:\`\`,nodes:[{id:\`334\`,key:\`334\`,txt:\`Permanency Opportunities Project Events\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Events/SpecialEvents/OlderChildAdoptionInformationNight.aspx\`,img:\`\`,nodes:[]}]},{id:\`146\`,key:\`146\`,txt:\`AdoptionWorks\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptanOlderChild/AdoptionWorks.aspx\`,img:\`\`,nodes:[{id:\`149\`,key:\`149\`,txt:\`The Children\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptanOlderChild/AdoptionWorks/TheChildren.aspx\`,img:\`\`,nodes:[]},{id:\`147\`,key:\`147\`,txt:\`Steps in the Process to Adopt a Foster Child\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptanOlderChild/AdoptionWorks/StepsintheProcesstoAdoptaFosterChild.aspx\`,img:\`\`,nodes:[{id:\`157\`,key:\`157\`,txt:\`Training\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptanOlderChild/AdoptionWorks/StepsintheProcesstoAdoptaFosterChild/Training.aspx\`,img:\`\`,nodes:[]},{id:\`148\`,key:\`148\`,txt:\`Adoption Works Fees\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptanOlderChild/AdoptionWorks/StepsintheProcesstoAdoptaFosterChild/AdoptionWorksFees.aspx\`,img:\`\`,nodes:[]}]},{id:\`192\`,key:\`192\`,txt:\`FAQ\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptanOlderChild/AdoptionWorks/FAQ.aspx\`,img:\`\`,nodes:[]},{id:\`194\`,key:\`194\`,txt:\`Adoption Stories\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptanOlderChild/AdoptionWorks/AdoptionStories.aspx\`,img:\`\`,nodes:[]},{id:\`193\`,key:\`193\`,txt:\`Resources\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/OlderChildAdoption/AdoptanOlderChild/Resources.aspx\`,img:\`\`,nodes:[]}]}]},{id:\`335\`,key:\`335\`,txt:\`Adopt Internationally\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptInternationally/InternationalAdoption.aspx\`,img:\`\`,nodes:[{enabled:\`0\`,id:\`183\`,key:\`183\`,txt:\`Country Programs\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptInternationally/CountryPrograms.aspx\`,img:\`\`,nodes:[{id:\`137\`,key:\`137\`,txt:\`China\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptInternationally/InternationalAdoption/CountryPrograms/China.aspx\`,img:\`\`,nodes:[{id:\`138\`,key:\`138\`,txt:\`China Program Process\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptInternationally/InternationalAdoption/CountryPrograms/China/ChinaProgramProcess.aspx\`,img:\`\`,nodes:[]}]},{id:\`141\`,key:\`141\`,txt:\`Bulgaria\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptInternationally/InternationalAdoption/CountryPrograms/Bulgaria.aspx\`,img:\`\`,nodes:[]},{id:\`142\`,key:\`142\`,txt:\`Colombia\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptInternationally/InternationalAdoption/CountryPrograms/Colombia.aspx\`,img:\`\`,nodes:[]},{id:\`264\`,key:\`264\`,txt:\`Haiti\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptInternationally/InternationalAdoption/CountryPrograms/Haiti.aspx\`,img:\`\`,nodes:[]},{id:\`143\`,key:\`143\`,txt:\`Moldova\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptInternationally/InternationalAdoption/CountryPrograms/Moldova.aspx\`,img:\`\`,nodes:[]},{id:\`144\`,key:\`144\`,txt:\`South Korea\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptInternationally/InternationalAdoption/CountryPrograms/SouthKorea.aspx\`,img:\`\`,nodes:[]}]},{id:\`128\`,key:\`128\`,txt:\`China and Vietnam Birthland Tours\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptInternationally/ChinaandVietnamBirthlandTours.aspx\`,img:\`\`,nodes:[]},{id:\`294\`,key:\`294\`,txt:\`Children in Common\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptInternationally/ChildreninCommon.aspx\`,img:\`\`,nodes:[]},{id:\`182\`,key:\`182\`,txt:\`FAQ\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptInternationally/FAQ.aspx\`,img:\`\`,nodes:[]},{id:\`186\`,key:\`186\`,txt:\`Adoption Stories\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptInternationally/AdoptionStories.aspx\`,img:\`\`,nodes:[]},{id:\`184\`,key:\`184\`,txt:\`Resources\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/AdoptInternationally/Resources.aspx\`,img:\`\`,nodes:[]}]},{id:\`310\`,key:\`310\`,txt:\`Application\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/Application.aspx\`,img:\`\`,nodes:[]}]},{id:\`130\`,key:\`130\`,txt:\`Home Study\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/HomeStudy.aspx\`,img:\`\`,cssIcon:\` \`,nodes:[{id:\`131\`,key:\`131\`,txt:\`Home Study Topics\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/HomeStudy/HomeStudyTopics.aspx\`,img:\`\`,nodes:[]},{id:\`158\`,key:\`158\`,txt:\`Home Study Plus\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/HomeStudy/HomeStudyPlus.aspx\`,img:\`\`,nodes:[{id:\`199\`,key:\`199\`,txt:\`Home Study Plus Services \`,ca:\`3\`,url:\`http://www.adoptionstogether.org/HomeStudy/HomeStudyPlus/HomeStudyPlusServices.aspx\`,img:\`\`,nodes:[]},{id:\`197\`,key:\`197\`,txt:\`Parental Placement Services for Virginia\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/HomeStudy/HomeStudyPlus/ParentalPlacementServicesforVirginia.aspx\`,img:\`\`,nodes:[]}]},{id:\`132\`,key:\`132\`,txt:\`Steps in the Home Study Process\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/HomeStudy/StepsintheHomeStudyProcess.aspx\`,img:\`\`,nodes:[]},{id:\`133\`,key:\`133\`,txt:\`Home Study Service Fees\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/HomeStudy/HomeStudyServiceFees.aspx\`,img:\`\`,nodes:[]},{id:\`283\`,key:\`283\`,txt:\`Home Study Testimonials\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/HomeStudy/HomeStudyTestimonials.aspx\`,img:\`\`,nodes:[]},{id:\`200\`,key:\`200\`,txt:\`FAQ\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/HomeStudy/FAQ.aspx\`,img:\`\`,nodes:[]},{id:\`259\`,key:\`259\`,txt:\`Application\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Adopting/Application.aspx\`,img:\`\`,nodes:[]}]},{id:\`62\`,key:\`62\`,txt:\`Education\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Education.aspx\`,img:\`\`,cssIcon:\` \`,nodes:[{id:\`202\`,key:\`202\`,txt:\`Training for Adoptive Parents\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Education/TrainingforAdoptiveParents.aspx\`,img:\`\`,nodes:[{id:\`246\`,key:\`246\`,txt:\`Adoption Preparation\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Education/TrainingforAdoptiveParents/AdoptionPreparation.aspx\`,img:\`\`,nodes:[{id:\`247\`,key:\`247\`,txt:\`International Adoption Preparation\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Education/TrainingforAdoptiveParents/AdoptionPreparation/InternationalAdoptionPreparation.aspx\`,img:\`\`,nodes:[]},{id:\`248\`,key:\`248\`,txt:\`Domestic Infant Adoption Preparation\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Education/TrainingforAdoptiveParents/AdoptionPreparation/DomesticInfantAdoptionPreparation.aspx\`,img:\`\`,nodes:[]},{id:\`361\`,key:\`361\`,txt:\`Older Child Adoption Preparation\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Education/TrainingforAdoptiveParents/AdoptionPreparation/OlderChildAdoptionPreparation.aspx\`,img:\`\`,nodes:[]},{id:\`249\`,key:\`249\`,txt:\`Finalization\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Education/TrainingforAdoptiveParents/AdoptionPreparation/Finalization.aspx\`,img:\`\`,nodes:[]}]},{enabled:\`0\`,id:\`250\`,key:\`250\`,txt:\`Parenting\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Education/TrainingforAdoptiveParents/Parenting.aspx\`,img:\`\`,nodes:[{id:\`251\`,key:\`251\`,txt:\`Online Parenting Classes\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Education/TrainingforAdoptiveParents/Parenting/OnlineParentingClasses.aspx\`,img:\`\`,nodes:[]},{id:\`252\`,key:\`252\`,txt:\`On-Demand Education\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Education/TrainingforAdoptiveParents/Parenting/OnDemandEducation.aspx\`,img:\`\`,nodes:[]},{id:\`253\`,key:\`253\`,txt:\`Classroom Training\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Education/TrainingforAdoptiveParents/Parenting/ClassroomTraining.aspx\`,img:\`\`,nodes:[]}]}]},{id:\`204\`,key:\`204\`,txt:\`Training for Professionals\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Education/TrainingforProfessionals.aspx\`,img:\`\`,nodes:[]},{id:\`203\`,key:\`203\`,txt:\`Calendar\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Events/EventsCalendar.aspx\`,img:\`\`,nodes:[]},{id:\`276\`,key:\`276\`,txt:\`Resources\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Education/Resources.aspx\`,img:\`\`,nodes:[]}]},{id:\`60\`,key:\`60\`,txt:\`Resources and Support\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/ResourcesandSupport.aspx\`,img:\`\`,cssIcon:\` \`,nodes:[{id:\`279\`,key:\`279\`,txt:\`Ask the Adoption Expert\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/ResourcesandSupport/AsktheAdoptionExpert.aspx\`,img:\`\`,nodes:[]},{id:\`150\`,key:\`150\`,txt:\`Counseling\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/ResourcesandSupport/Counseling.aspx\`,img:\`\`,nodes:[]},{id:\`73\`,key:\`73\`,txt:\`Support Groups\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/ResourcesandSupport/SupportGroups.aspx\`,img:\`\`,nodes:[]},{id:\`290\`,key:\`290\`,txt:\`LGBT Adoption\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/ResourcesandSupport/LGBTAdoption.aspx\`,img:\`\`,nodes:[]},{id:\`291\`,key:\`291\`,txt:\`Single Parent Adoption\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/ResourcesandSupport/SingleParentAdoption.aspx\`,img:\`\`,nodes:[]},{id:\`210\`,key:\`210\`,txt:\`Family Services in DC\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/ResourcesandSupport/FamilyServicesinDC.aspx\`,img:\`\`,nodes:[{id:\`254\`,key:\`254\`,txt:\`Services\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/ResourcesandSupport/FamilyServicesinDC/Services.aspx\`,img:\`\`,nodes:[{id:\`215\`,key:\`215\`,txt:\`Support Groups\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/ResourcesandSupport/FamilyServicesinDC/Services/SupportGroups.aspx\`,img:\`\`,nodes:[]},{id:\`218\`,key:\`218\`,txt:\`Respite\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/ResourcesandSupport/FamilyServicesinDC/Services/Respite.aspx\`,img:\`\`,nodes:[]},{id:\`216\`,key:\`216\`,txt:\`Training\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/ResourcesandSupport/FamilyServicesinDC/Services/Training.aspx\`,img:\`\`,nodes:[]},{id:\`212\`,key:\`212\`,txt:\`Crisis Intervention\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/ResourcesandSupport/FamilyServicesinDC/Services/CrisisIntervention.aspx\`,img:\`\`,nodes:[]},{id:\`213\`,key:\`213\`,txt:\`Counseling\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/ResourcesandSupport/FamilyServicesinDC/Services/Counseling.aspx\`,img:\`\`,nodes:[]},{id:\`217\`,key:\`217\`,txt:\`Case Management and Advocacy\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/ResourcesandSupport/FamilyServicesinDC/Services/CaseManagementandAdvocacy.aspx\`,img:\`\`,nodes:[]}]},{id:\`219\`,key:\`219\`,txt:\`DC Resources\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/ResourcesandSupport/FamilyServicesinDC/DCResources.aspx\`,img:\`\`,nodes:[{id:\`220\`,key:\`220\`,txt:\`Community and Government Resources\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/ResourcesandSupport/FamilyServicesinDC/DCResources/CommunityandGovernmentResources.aspx\`,img:\`\`,nodes:[]}]},{id:\`346\`,key:\`346\`,txt:\`Parenting Classes\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Education/TrainingforAdoptiveParents/Parenting/OnDemandEducation.aspx\`,iIdx:\`0\`,nodes:[{id:\`347\`,key:\`347\`,txt:\`Online Parenting Classes\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Education/TrainingforAdoptiveParents/Parenting/OnlineParentingClasses.aspx\`,img:\`\`,nodes:[]},{id:\`348\`,key:\`348\`,txt:\`On-Demand Education\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Education/TrainingforAdoptiveParents/Parenting/OnDemandEducation.aspx\`,img:\`\`,nodes:[]}]},{id:\`222\`,key:\`222\`,txt:\`Newsletter\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/ResourcesandSupport/FamilyServicesinDC/Newsletter.aspx\`,img:\`\`,nodes:[]},{id:\`223\`,key:\`223\`,txt:\`Testimonials\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/ResourcesandSupport/FamilyServicesinDC/Testimonials.aspx\`,img:\`\`,nodes:[]},{id:\`224\`,key:\`224\`,txt:\`Events\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Events/EventsCalendar.aspx\`,img:\`\`,nodes:[]},{id:\`211\`,key:\`211\`,txt:\`PPFC Staff\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/ResourcesandSupport/FamilyServicesinDC/PPFCStaff.aspx\`,img:\`\`,nodes:[]},{id:\`221\`,key:\`221\`,txt:\`Volunteer and Internship Opportunities in DC\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/AboutUs/VolunteerandInternshipOpportunities.aspx\`,img:\`\`,nodes:[]}]},{id:\`71\`,key:\`71\`,txt:\`Work of Heart Respite Program\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/ResourcesandSupport/WorkofHeartRespiteProgram.aspx\`,img:\`\`,nodes:[]},{id:\`237\`,key:\`237\`,txt:\`Parent Advocate Project\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/ResourcesandSupport/ParentAdvocateProject.aspx\`,img:\`\`,nodes:[]}]},{id:\`121\`,key:\`121\`,txt:\`Events\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Events/EventsCalendar.aspx\`,img:\`\`,cssIcon:\` \`,nodes:[{id:\`274\`,key:\`274\`,txt:\`Events Calendar\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Events/EventsCalendar.aspx\`,img:\`\`,nodes:[]},{id:\`255\`,key:\`255\`,txt:\`Special Events\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Events/SpecialEvents.aspx\`,img:\`\`,nodes:[{id:\`319\`,key:\`319\`,txt:\`Older Child Adoption Information Night\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Events/SpecialEvents/OlderChildAdoptionInformationNight.aspx\`,img:\`\`,nodes:[]},{id:\`381\`,key:\`381\`,txt:\`Fall Family Day 2011\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Events/SpecialEvents/FallFamilyDay2011.aspx\`,img:\`\`,nodes:[]}]},{id:\`243\`,key:\`243\`,txt:\`Conferences\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Events/Conferences.aspx\`,img:\`\`,nodes:[]},{id:\`242\`,key:\`242\`,txt:\`Summer Programs\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Events/SummerPrograms.aspx\`,img:\`\`,nodes:[]}]},{id:\`333\`,key:\`333\`,txt:\`Blog\`,ca:\`3\`,url:\`http://www.adoptionstogether.org/Blog.aspx\`,img:\`\`,cssIcon:\` \`,nodes:[]}]}`}" />
<script type="text/javascript" src="/Resources/Shared/scripts/initWidgets.js" ></script>
<script type="text/javascript">
//<![CDATA[
Sys.Application.initialize();
//]]>
</script>
<script type="text/javascript">dnn.setVar('dnn_NAV1_ctlNAV1_p', '{imagepaths:\'/images/\',anim:\'0\',suborient:\'1\',postback:\'__doPostBack(\\\'dnn$NAV1$ctlNAV1\\\',\\\'[NODEID]~|~Click\\\')\',sysimgpath:\'/images/\',easeDir:\'0\',mbcss:\'mainMenu\',rmode:\'0\',easeType:\'3\',imagelist:\'[0]1x1.GIF\',orient:\'0\',callback:\'dnn.xmlhttp.doCallBack(\\\'ctlNAV1 dnn_NAV1_ctlNAV1\\\',\\\'[NODEXML]\\\',this.callBackSuccess,mNode,this.callBackFail,this.callBackStatus,null,null,0);\'}');dnn.controls.initMenu($get('dnn_NAV1_ctlNAV1'));</script></form>
</body>
</html>









