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
?>

<?php

if(isset($_GET['famreg'])){
	$famregs = $_GET['famreg'];
	/*foreach($famregs as $famreg){
		$famregz .= ' %'.$famreg.'% ';
	}*/
}
$famregz = str_replace(" ", "<=>", implode(",", $famregs));


if(isset($_GET['pareth'])) {
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
    <title>Search - <?=$aData1[3]?><?php if($aData1[4] != '') { ?> & <?php } ?><?=$aData1[4]?></title>
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

<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery.autotab.js"></script>
<!--<link rel="stylesheet" type="text/css" media="screen,print" href="css/newCssByJk.css">
--><script type="text/javascript">
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

function checkForm(frm){
var destCount = frm.elements['beth[]'].length;
var destSel   = false;
for(i = 0; i < destCount; i++){
if(frm.elements['beth[]'][i].checked){
destSel = true;
break;
}
}

if(!destSel){
alert('Please select racial/ethnic background of your baby');
}
return destSel;
}

</script>
<script type="text/javascript" language="javascript">
	jQuery.fn.center = function () {
		this.css("position","absolute");
		//alert($(window).height() - this.height() ) / 2+$(window).scrollTop() + "px");
		this.css("top", ( $(window).height() - this.height() ) / 2+$(window).scrollTop() + 100 + "px");
		this.css("left", ( $(window).width() - this.width() ) / 2+$(window).scrollLeft() + "px");
		return this;
	}

	$(document).ready(function() {
		$("#thumbnail img").click(function(e){

			$("#background").css({"opacity" : "0.7"})
							.fadeIn("slow");

			$("#large").html('<span>All calls are anonymous as we do not reveal or track your phone number</span><label style="width: 180px;">( <input type="text" maxlength="3" name="" id="textfield"> )<input type="text" maxlength="3" name="" id="textfield2">-<input type="text" class="cellLastNumber" maxlength="4" name="" id="textfield3"><input type="button" class="callBtn" onclick="javascript:request_call_local()" id="submitbtn" name=""></label><img id="closer" onclick="javascript:close_call_local()" style="position: relative; left: 28px; top: -64px;" src="images/close2.gif">')
					   .center()
					   .fadeIn("slow");

			return false;
		});

		$(document).keypress(function(e){
			if(e.keyCode==27){
				$("#container").fadeOut("slow");
				$("#large").fadeOut("slow");
			}
		});

		$("#background").click(function(){
			$("#background").fadeOut("slow");
			$("#large").fadeOut("slow");
		});

		$("#closer").click(function(){
            alert('close');
			$("#background").fadeOut("slow");
			$("#large").fadeOut("slow");
		});

	});
</script>
<script type="text/javascript" language="javascript">

                                    var request = false;
                                    try {
                                      request = new XMLHttpRequest();
                                    } catch (trymicrosoft) {
                                      try {
                                        request = new ActiveXObject("Msxml2.XMLHTTP");
                                      } catch (othermicrosoft) {
                                        try {
                                          request = new ActiveXObject("Microsoft.XMLHTTP");
                                        } catch (failed) {
                                          request = false;
                                        }
                                      }
                                    }


// *Function that updates the status in the box for the call. This function is called as a result of a state change after making the AJAX request.
            function update_status_message(){
               
               var siteurl = <?php echo $site['url'] ?>
                
                if (request.readyState < 4) {

		    document.getElementById("submitbtn").src=siteurl+"templates/base//images/callMe_2.png";

                } else if (request.readyState==4) {
		   alert(request.responseText);
                        if(request.responseText== "Call Connected")
                        {
                        document.getElementById("submitbtn").src=siteurl+"templates/base//images/callMe_3.png";
                        }
                        else{
                      document.getElementById("submitbtn").src=siteurl+"templates/base//images/callMe_1.png";
}
                }
            }

// *Function called by clicking on the "Click to Call" button in the form.
// *This function combines the three fields in the form into a 10 digit phone number field and if it is of a valid form, then call the proxy module
// *to perform the functions.

            function close_call_local(){

            $("#background").fadeOut("slow");
			$("#large").fadeOut("slow");

            }

            function request_call_local(){


              if (!request) {
                    Alert ("sorry, click to call will not work with your browser");
                }
                else
                {
                                          var phonetocall ="8132586505";
                                         var contactnumber = "4078950675";
                                         var agencyname ="";
                                         var first= document.getElementById("textfield").value;
                                         var second= document.getElementById("textfield2").value;
                                         var third= document.getElementById("textfield3").value;
                                         var enterednumber=first+second+third;
                                         var id = enterednumber;

                    if (id.length == 10)
                      {
                        // insert your click to xyz building block ID where indicated in the next line or you will receive invalid account responses.
                        // get the click to xyz building block id from the Tools menu
                         //var url = "http://localhost/parentfinders/clickto_proxy.php?app=ctc&id="+phonetocall+"&phone_to_call="+id+"&type=1&key=65687e8b8f39137da01defed01b2502c3faf3292&first_callerid="+2074532511+"&second_callerid="+2074532511+"&ref="+agencyname+"&page=ProfilePage";

						 var url = "https://secure.ifbyphone.com/click_to_xyz.php?app=ctc&id="+id+"&phone_to_call="+phonetocall+"&type=2&key=65687e8b8f39137da01defed01b2502c3faf3292&first_callerid=2074532511&second_callerid=2074532511&ref=HOA&page=http://hoa.cairsolutions.com/advsearch.php";
                        request.onreadystatechange = update_status_message;
                        request.open("GET", url, true);
                        request.send(null);

                     }
                     else{
                     alert("Sorry, the phone number you entered does not have 10 digits! ");
                     }
                 }

            }

</script>
<style>
img {
	border: none;
}
#thumbnail img {
	cursor: pointer;
}
#large {
	display: none;
	position: absolute;
	padding: 5px;
	z-index: 10;
	min-height: 100px;
	min-width: 215px;
	color: #336699;
}
#background{
	display: none;
	position: absolute;
	height: 2600px;
	width: 100%;
	top: 0;
	left: 0;
	background: #000000;
	z-index: 1;
}

p.callToUs {
    background: url("images/callToUsBgRepImg.png") repeat-x scroll left top #CADFEE;
    border: 1px solid #C1D4E5;
    padding: 10px;
    position: absolute;
    right: 300px;
    top: 760px;
    width: 192px;

}

p.callToUs span {
    display: block;
    font-size: 11px;
    line-height: 14px;
    margin-bottom:15px;
}

p.callToUs input {
    width: 40px;
}

.callBtn {
    background: url("images/callBtn.png") no-repeat scroll left top transparent;
    border: medium none;
    cursor: pointer;
    height: 33px;
    position: absolute;
    right: 0;
    text-indent: -999em;
    top: 45px;
    width: 33px;
}

#apDiv2 {
float: left;
    height: 0;
    left: 600px;
    position: relative;
    top: -32px;
    width:40px;
    z-index: 0;
}

</style>
<!--Make sure page contains valid doctype at the very top!-->

</head>
<body id="Body">
  
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
            <div class="art-sidebar1">
                <div id="dnn_PageImage" class="page-image"><div class="DnnModule DnnModule-AdoptionsTogetherImage DnnModule-892"><a name="892"></a><div id="dnn_ctr892_ContentPane"><!-- Start_Module_892 --><div id="dnn_ctr892_ModuleContent" class="DNNModuleContent ModAdoptionsTogetherImageC">

<img id="dnn_ctr892_ViewImage_imgImage" src="Portals/0/images/YoungInterracialCouple2.jpg" style="border-width:0px;" />

</div><!-- End_Module_892 --></div>
</div></div>
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

<div style =" position:relative;float:left;width:110px;height:auto;">
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
	$lpend = 24;//$_GET['pend'] - 5;
	//$lpstart = $lpend - 12;
       $lpstart = $_GET['pstart'] - 24;
	$spage = $_GET['page'] - 1;
	echo '<a href="advsearch.php?pstart='.$lpstart.'&pend='.$lpend.'&page='.$spage.'" class="more">Prev</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
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
	$pend = 24;
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
					echo '<a href="advsearch.php?pstart='.$pstart.'&pend=24&page='.$i.'">'.$i.'</a> &nbsp;';
			}
			$pstart = $pend + 0;
			$pend = $pend + 24;
		}
		$p++;
	}

	if(!isset($currentpage)){
	$currentpage = 1;
	}

	//if(($_GET['pend'] + 5) != $pend){
	if($currentpage != $i){
		if(isset($_GET['pend'])){
			$lpstart = $_GET['pstart'] + 24;//$_GET['pend'] + 1;
			$lpend = 24;
                //$_GET['pend'] + 5;
			$page = $_GET['page'] + 1;
		}else{
			$lpstart = 24;
			$lpend = 24;
			$page = 2;
		}

		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="advsearch.php?pstart='.$lpstart.'&pend='.$lpend.'&page='.$page.'" class="next">Next</a>';

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
	$lpend = 24;//$_GET['pend'] - 5;
	//$lpstart = $lpend - 24;
       $lpstart = $_GET['pstart'] - 24;
	$spage = $_GET['page'] - 1;


        //echo '<a href="index.php?max_race_id=7&famreg='.$famregz.'&pareth='.$parethz.'&beth='.$bethz.'&family_num_children='.$_GET[family_num_children].'&family_state='.$_GET[family_state].'&pstart='.$lpstart.'&pend='.$lpend.'&page='.$spage.'" class="more">Prev</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';


  echo '<a href="advsearch.php?max_race_id=7&beth[]='.$bethz.'&pareth[]='.$parethz.'&famreg[]='.$famregz.'&family_num_children='.$_GET[family_num_children].'&family_state='.$_GET[family_state].'&pstart='.$lpstart.'&pend='.$lpend.'&page='.$spage.'&button=Submit+Search" class="more">Prev</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';






//?famreg='.$famregz.'&pareth='.$parethz.'&beth='.$bethz.'&family_num_children='.$_GET[family_num_children].'&family_state='.$_GET[family_state]



}
if(isset($_GET['search'])){
	//$data = file_get_contents('http://ctstagepf.parentfinder.com/badgewaitingfamiliesat.php?getpa=true&search='.$_GET[search]);
}else {
	//$data = file_get_contents('http://ctstagepf.parentfinder.com/badgewaitingfamiliesat.php?getpa=true');

$data = file_get_contents($site['url'].'badgewaitingfamiliesat.php?getpa=true&max_race_id=7&famreg='.$famregz.'&pareth='.$parethz.'&beth='.$bethz.'&family_num_children='.$_GET[family_num_children].'&family_state='.$_GET[family_state]);

	$aData = (explode("#####",$data));
	//print_r($aData);
	$i=0;
	$p = 0;
	$pstart = 0;
	$pend = 24;

       // echo "counter ".count($aData);
        foreach($aData as $key => $page)

	//for($cnt = 0; $cnt <= 42; $cnt =$cnt+24)
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
                                   echo '<a href="advsearch.php?max_race_id=7&beth[]='.$bethz.'&pareth[]='.$parethz.'&famreg[]='.$famregz.'&family_num_children='.$_GET[family_num_children].'&family_state='.$_GET[family_state].'&pstart='.$pstart.'&pend=12&page='.$i.'&button=Submit+Search">'.$i.'</a> &nbsp;';

			}
			$pstart = $pend + 0;
			$pend = $pend + 24;
		}
		$p++;
	}

	if(!isset($currentpage)){
	$currentpage = 1;
	}

	//if(($_GET['pend'] + 5) != $pend){
	if($currentpage != $i){
		if(isset($_GET['pend'])){
			$lpstart = $_GET['pstart'] + 24;//$_GET['pend'] + 1;
			$lpend = 24;
                //$_GET['pend'] + 5;
			$page = $_GET['page'] + 1;
		}else{
			$lpstart = 24;
			$lpend = 24;
			$page = 2;
		}
               if($i !=''){
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="advsearch.php?max_race_id=7&beth[]='.$bethz.'&pareth[]='.$parethz.'&famreg[]='.$famregz.'&family_num_children='.$_GET[family_num_children].'&family_state='.$_GET[family_state].'&pstart='.$lpstart.'&pend='.$lpend.'&page='.$page.'&button=Submit+Search" class="next">Next</a>';
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

                    <span id="dnn_dnnBREADCRUMB_lblBreadCrumb"><a href="index.php" class="SkinObject">Pregnant?</a> > <a href="index.php" class="SkinObject">Families Waiting to Adopt</a></span>

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
                </div><div>&nbsp;</div><div>&nbsp;</div><a href="<?php echo $site['url'] ?>">
<img class="mainLogo" alt="logo" src="images/logo.png">
</a>
   
   <div class="body_internal">
     <!--<form id="form1" name="form1" method="post" action="">-->

     <form name="search" method="get" action="index.php" onsubmit="return checkForm(this);">
	 <input type="hidden" value="7" name="max_race_id">
       <table width="724" border="0" align="left" cellpadding="12" cellspacing="0" class="palatino_font">

         
         <tr>
           <td colspan="2" align="left" valign="top" class="line_dot"><span class="black_heading">Please choose the following options to find your perfect family.</span></td>
         </tr>
         <tr>
           <td colspan="2" align="left" valign="top" class="line_dot"><span class="black_heading">RACIAL/ETHNIC BACKGROUND OF YOUR BABY:
           </span></td>
         </tr>
         <tr>
           <td colspan="2" align="left" valign="top" class="line_dot">
           <span class="black_heading">Check all that apply.</span>           </td>
         </tr>
         <tr>
           <td colspan="2" align="left" valign="top" class="line_dot"><table width="580" border="0" cellpadding="5" cellspacing="0">
             <tr>
               <td width="21"><input type="checkbox" name="beth[]" value="African American" id="African" /></td>
               <td width="188">African American</td>
               <td width="20"><input type="checkbox" name="beth[]" value="Asian"  id="Asian" /></td>
               <td width="449">Asian</td>
             </tr>
             <tr>
               <td><input type="checkbox" name="beth[]" value="Caucasian"  id="Caucasian" /></td>
               <td>Caucasian</td>
               <td><input type="checkbox" name="beth[]" value="Hispanic"  id="Hispanic" /></td>
               <td>Hispanic</td>
             </tr>
             <tr>
               <td><input type="checkbox" name="beth[]" value="Native American" id="Native" /></td>
               <td>Native American</td>
               <td><input type="checkbox" name="" id="Other"  /></td>
               <td>Other</td>
             </tr>

           </table></td>
         </tr>
         <tr>
           <td colspan="2" align="left" valign="top" class="line_dot"><span class="black_heading">RACIAL/ETHNIC BACKGROUND OF PARENTS DESIRED:</span><br />
             <p><em>Check all that apply.<br />
              At least one parent will meet this criteria</em></p></td>
         </tr>
         <tr>
           <td colspan="2" align="left" valign="top" class="line_dot"><table width="584" border="0" cellpadding="5" cellspacing="0">
             <tr>
               <td width="22"><input type="checkbox" name="pareth[]" value="African American" /></td>
               <td width="250">African American</td>
               <td width="20"><input type="checkbox" name="pareth[]" value="Asian" /></td>
               <td width="200">Asian</td>
             </tr>
             <tr>
               <td><input type="checkbox" name="pareth[]" value="Caucasian" /></td>
               <td>Caucasian</td>
               <td><input type="checkbox" name="pareth[]" value="Hispanic" /></td>
               <td>Hispanic</td>
             </tr>
             <tr>
               <td><input type="checkbox" name="pareth[]" value="Native American"/></td>
               <td>Native American</td>
               <td><input type="checkbox" name=""/></td>
               <td>Other</td>
             </tr>
           </table></td>
         </tr>
         <tr>
           <td colspan="2" align="left" valign="top" class="line_dot"><span class="black_heading">RELIGION OF FAMILY:</span>
             <p><em>Check all that apply.<br />
              At least one parent will meet this criteria.</em></p></td>
         </tr>
         <tr>
           <td colspan="2" align="left" valign="top" class="line_dot"> <table width="593" border="0" cellpadding="5" cellspacing="0">
             <tr>
               <td width="20" align="left" valign="top"><input type="checkbox" name="famreg[]" value="Baptist" /></td>
               <td width="262" align="left" valign="top">Baptist</td>
               <td width="20" align="left" valign="top"><input type="checkbox" name="famreg[]" value="Catholic" /></td>
               <td width="251" align="left" valign="top">Catholic</td>
             </tr>
             <tr>
               <td align="left" valign="top"><input type="checkbox" name="famreg[]" value="Christian" /></td>
               <td align="left" valign="top">Christian</td>
               <td align="left" valign="top"><input type="checkbox" name="famreg[]" value="Christian Science" /></td>
               <td align="left" valign="top">Christian Science</td>
             </tr>
             <tr>
               <td align="left" valign="top"><input type="checkbox" name="famreg[]" value="Episcopalian" /></td>
               <td align="left" valign="top">Episcopalian</td>
               <td align="left" valign="top"><input type="checkbox" name="famreg[]" value="Hindu" /></td>
               <td align="left" valign="top">Hindu</td>
             </tr>
             <tr>
               <td align="left" valign="top"><input type="checkbox" name="famreg[]" value="Jehovah Witness" /></td>
               <td align="left" valign="top">Jehovah Witness</td>
               <td align="left" valign="top"><input type="checkbox" name="famreg[]" value="Jewish" /></td>
               <td align="left" valign="top">Jewish</td>
             </tr>
             <tr>
               <td align="left" valign="top"><input type="checkbox" name="famreg[]" value="Mormon (LDS)" /></td>
               <td align="left" valign="top">Mormon (LDS)</td>
               <td align="left" valign="top"><input type="checkbox" name="famreg[]" value="Lutheran" /></td>
               <td align="left" valign="top">Lutheran</td>
             </tr>
             <tr>
               <td align="left" valign="top"><input type="checkbox" name="famreg[]" value="Methodist" /></td>
               <td align="left" valign="top">Methodist</td>
               <td align="left" valign="top"><input type="checkbox" name="famreg[]" value="Muslim" /></td>
               <td align="left" valign="top">Muslim</td>
             </tr>
             <tr>
               <td align="left" valign="top"><input type="checkbox" name="famreg[]" value="Protestant" /></td>
               <td align="left" valign="top">Protestant</td>
               <td align="left" valign="top"><input type="checkbox" name="famreg[]" value="7th Day Adventist" /></td>
               <td align="left" valign="top">7th Day Adventist</td>
             </tr>
             <tr>
               <td align="left" valign="top"><input type="checkbox" name="famreg[]" value="Southern Baptist" /></td>
               <td align="left" valign="top">Southern Baptist</td>
               <td align="left" valign="top"><input type="checkbox" name="famreg[]" value="" /></td>
               <td align="left" valign="top">Other</td>
             </tr>
             <tr>
               <td align="left" valign="top"><input type="checkbox" name="famreg[]" value="Buddhist" /></td>
               <td align="left" valign="top">Buddhist</td>
               <td align="left" valign="top"><input type="checkbox" name="famreg[]" value="Non-Denominational" /></td>
               <td align="left" valign="top">Non-Denominational</td>
             </tr>
             <tr>
               <td align="left" valign="top"><input type="checkbox" name="famreg[]" value="Presbyterian" /></td>
               <td align="left" valign="top">Presbyterian</td>
               <td align="left" valign="top">&nbsp;</td>
               <td align="left" valign="top">&nbsp;</td>
             </tr>

           </table></td>
         </tr>
         <tr>
           <td colspan="2" align="left" valign="top"><table width="565" border="0" cellpadding="5" cellspacing="0" class="line_dot">
             <tr>
               <td width="291" align="left" valign="top"><span class="black_heading">OTHER CHILDREN IN FAMILY:</span></td>
               <td width="254" align="left" valign="top"><label>
               <select name="family_num_children">
<option selected="selected" value="">-Please Select</option>
<option value="=0">0</option>
<option value="=1">1</option>
<option value="&lt;2">&lt;2</option>
<option value="=2">2</option>
<option value="&gt;2">&gt;2</option>
<option value="&lt;3">&lt;3</option>
<option value="=3">3</option>
<option value="&gt;3">&gt;3</option>
<option value="&lt;4">&lt;4</option>
<option value="=4">4</option>
<option value="&gt;4">&gt;4</option>
<option value="&lt;5">&lt;5</option>
<option value="=5">5</option>
<option value="&gt;5">&gt;5</option>
</select>
               </label></td>
             </tr>

           </table></td>
         </tr>
         <tr>
           <td height="88" colspan="2" align="left" valign="top"><table width="588" border="0" cellpadding="5" cellspacing="0">
             <tr>
               <td width="289" height="58" align="left" valign="top"><span class="black_heading">FAMILY'S STATE:</span></td>
               <td width="279" align="left" valign="top">
                 <select name="family_state">
<option selected="selected" value="">-Please Select State</option>
<option value="Alabama">Alabama</option>
<option value="Alaska">Alaska</option>
<option value="American Samoa">American Samoa</option>
<option value="Arizona">Arizona</option>
<option value="Arkansas">Arkansas</option>
<option value="Armed Forces">Armed Forces (Other)</option>
<option value="Armed Forces Africa">Armed Forces Africa</option>
<option value="Armed Forces Americas">Armed Forces Americas</option>
<option value="Armed Forces Pacific">Armed Forces Pacific</option>
<option value="California">California</option>
<option value="Colorado">Colorado</option>
<option value="Connecticut">Connecticut</option>
<option value="Delaware">Delaware</option>
<option value="District Of Columbia">District Of Columbia</option>
<option value="Fed. St. of Micronesia">Fed. St. of Micronesia</option>
<option value="FFloridaL">Florida</option>
<option value="Georgia">Georgia</option>
<option value="Hawaii">Hawaii</option>
<option value="Idaho">Idaho</option>
<option value="Illinois">Illinois</option>
<option value="Indiana">Indiana</option>
<option value="Iowa">Iowa</option>
<option value="Kansas">Kansas</option>
<option value="Kentucky">Kentucky</option>
<option value="Louisiana">Louisiana</option>
<option value="Maine">Maine</option>
<option value="Marshall ">Marshall Islands</option>
<option value="Maryland">Maryland</option>
<option value="Massachusetts">Massachusetts</option>
<option value="Michigan">Michigan</option>
<option value="Minnesota">Minnesota</option>
<option value="Mississippi">Mississippi</option>
<option value="Missouri">Missouri</option>
<option value="Montana">Montana</option>
<option value="N. Mariana Islands">N. Mariana Islands</option>
<option value="Nebraska">Nebraska</option>
<option value="Nevada">Nevada</option>
<option value="New Hampshire">New Hampshire</option>
<option value="New Jersey">New Jersey</option>
<option value="New Mexico">New Mexico</option>
<option value="New York">New York</option>
<option value="North Carolina">North Carolina</option>
<option value="North Dakota">North Dakota</option>
<option value="Ohio">Ohio</option>
<option value="Oklahoma">Oklahoma</option>
<option value="Oregon">Oregon</option>
<option value="Other (International)">Other (International)</option>
<option value="Pennsylvania">Pennsylvania</option>
<option value="Puerto Rico">Puerto Rico</option>
<option value="Rhode Island">Rhode Island</option>
<option value="South Carolina">South Carolina</option>
<option value="South Dakota">South Dakota</option>
<option value="Tennessee">Tennessee</option>
<option value="Texas">Texas</option>
<option value="Utah">Utah</option>
<option value="Vermont">Vermont</option>
<option value="Virgin Islands">Virgin Islands</option>
<option value="Virginia">Virginia</option>
<option value="Washington">Washington</option>
<option value="West Virginia">West Virginia</option>
<option value="Wisconsin">Wisconsin</option>
<option value="Wyoming">Wyoming</option>
<option value="Other - Not In USA">Other - Not In USA</option>
</select></td>
             </tr>
<input type="hidden" value="0" name="pstart">
<input type="hidden" value="24" name="pend">
<input type="hidden" value="1" name="page">
             <tr>
               <td>&nbsp;</td>
               <td align="left" valign="top"><label>
			  <!--  <input type="hidden" value="family_profile/browse" name="page"> -->
                 <input type="submit" name="button" id="button" value="Submit Search" />
               </label></td>
             </tr>
           </table></td>
         </tr>

         <tr>
           <td colspan="2" align="left" valign="top">
           </td>
         </tr>
      
       </table>
     </form>
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
<script type="text/javascript">dnn.setVar('dnn_NAV1_ctlNAV1_p', '{imagepaths:\'/images/\',anim:\'0\',suborient:\'1\',postback:\'__doPostBack(\\\'dnn$NAV1$ctlNAV1\\\',\\\'[NODEID]~|~Click\\\')\',sysimgpath:\'/images/\',easeDir:\'0\',mbcss:\'mainMenu\',rmode:\'0\',easeType:\'3\',imagelist:\'[0]1x1.GIF\',orient:\'0\',callback:\'dnn.xmlhttp.doCallBack(\\\'ctlNAV1 dnn_NAV1_ctlNAV1\\\',\\\'[NODEXML]\\\',this.callBackSuccess,mNode,this.callBackFail,this.callBackStatus,null,null,0);\'}');dnn.controls.initMenu($get('dnn_NAV1_ctlNAV1'));</script></body>
</html>









