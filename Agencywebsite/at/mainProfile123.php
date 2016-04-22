<?php 
session_start();

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
$aData = (explode(";-",$data));
//print_r($aData);
$bpl = mb_substr($aData[0], 0, 260);
$profile = $_GET['profile'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Main Profile - <?=$aData[3]?><?php if($aData[4] != '') { ?> & <?php } ?><?=$aData[4]?></title>
<!--Make sure page contains valid doctype at the very top!-->
<link href="css/style.css" rel="stylesheet" type="text/css" />



</head>

<body>
<div class="main_contatiner"> 

<div class="title_field"><div class="title"><img src="images/title_02.jpg" width="400" height="88" /> </div> 
</div>
<div class="colom_left">
<div class="menues">
<ul class="ul_style">
<li class="left_menu_style"><a href="http://www.myadoptionplan.com" class="selected">Home</a></li>
<li class="left_menu_style"><a href="http://www.myadoptionplan.com/GetStarted.html" class="menus">Getting Started</a></li>
<li class="left_menu_style"><a href="http://www.myadoptionplan.com/LiveChat.html" class="menus">Live Chat</a></li>
<li class="left_menu_style"><a href="http://hoa.cairsolutions.com/" class="menus">Adoptive Families</a></li>
<li class="left_menu_style"><a href="http://www.myadoptionplan.com/Questions.html" class="menus">Questions</a></li>
<li class="left_menu_style"><a href="http://www.myadoptionplan.com/Resources.html" class="menus">Resources</a></li>
<li class="left_menu_style"><a href="http://www.myadoptionplan.com/Testimonials.html" class="menus">Testimonials</a></li>
<li class="left_menu_style"><a href="http://www.myadoptionplan.com/ContactUs.html" class="menus">Contact us</a></li>
<li class="left_menu_style"><a href="http://www.myadoptionplan.com/Forms.html" class="menus"> Forms</a></li>



</ul>





</div>
</div>

<div class="main_center_container">

<div class="colom_middle_internal">
  <div class="body_main">
  <div class="colum_first">
<p>
 Heart of Adoptions, Inc. has many hopeful adoptive families waiting for a child. We will ask you exactly what you are looking for in an adoptive family to create your "dream family" list, and then provide you family profiles to choose from that most closely match your preferences.
</p>
 
   <p> Only a handful of our waiting families are on our website, so if you don't find the perfect family here, contact us for additional profiles. If we don't already have the right family for you, we will search on your behalf until you find the ideal family for your child.</p>

  
  <p>  All waiting families have been pre-screened and meet requirements for adopting a child. If you have any questions about one of our waiting families, even if you aren't already working with Heart of Adoptions, Inc. we'd be happy to talk to you.</p>  </div>
  <div class="middle"> </div>
  <div class="right_col">
 <div style="float:right;"><a href="http://pdfcrowd.com/url_to_pdf" style="background-color: #993400; color: #FFFFFF; font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 12px; padding: 0 5px 5px 5px; text-decoration: none; margin:0px 5px 0px 0px;"> Save as pdf </a></div>
  <?php
     require_once 'slide.php';
        echo slide();
  ?>   
  </div>
  
  </div>
   <div class="body_line"> </div>
   <div class="menu_e">
   <ul class="internalul">
   <li class="internalli"><a href="mainProfile.php?profile=<?=$_GET['profile']?>" class="selectedi"> Main Profile</a></li>
   <li class="internalli"><a href="photographs.php?profile=<?=$_GET['profile']?>" class="menuinternal">  Photographs</a></li>
   <li class="internalli"><a href="videos.php?profile=<?=$_GET['profile']?>&v=watch" class="menuinternal">  Video</a></li>
   <li class="internalli"><a href="contactus.php?profile=<?=$_GET['profile']?>" class="menuinternal">  Contact us</a></li>
   
   </ul>
   
   
   </div>
   <div class="menu_e_backbtn"><a href="index.php" style="btnhoem"> <img src="images/BUTTON RED.png" alt="Back to main Profile" title="Back To Main Profile" border="0px"/></a></div>
   <div class="menu_e_below_line"> </div>
   <div class="body_internal"> 

   <div class="left_body_box">
   <div class="left_photo_i" style="text-align:center;"><img src="<?php if ($aData[9] == '') echo 'images/noimage.jpg'; else echo $aData[9];?>" title="Adoptive Family <?=$aData[3]?><?php if($aData[4] != '') { ?> & <?php } ?><?=$aData[4]?>" width="230" alt="<?=$aData[3]?><?php if($aData[4] != '') { ?> & <?php } ?><?=$aData[4]?>"><!--<img src="<?=$aData[9]?>" title="Adoptive Family Dave &amp; Krissy" width="300" height="422">--></div>
   <div class="menu_photoe">
   <ul class="internalul" style="padding-left:0px;">
   
   <li class="internalli"><a href="#" class="menu_photo"> <a href="contactus.php?profile=<?=$_GET['profile']?>" class="menu_photo">Contact Us </a></li>
   </ul>
   <div>
   
   
   </div>
   
   </div>

</div>


<div class="right_body_box">
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
<?php if($getAboutUsArray[1] != '') { ?>
<table  width='399' border=0 cellspacing=0 cellpading=0  class=white_headng_cntr >
<tr bgcolor=#993400> <td align=center class=tdstyle width="33%">&nbsp;</td><td align=center class=tdstyle width="33%"><?php echo $getAboutUsArray[0]; ?></td><td align=center class=tdstyle width="33%"><?php echo $getAboutUsArray[1]; ?></td></td>
</table>
<table  width='399' border=0 cellspacing=0 cellpading=0>
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
<table  width='399' border=0 cellspacing=0 cellpading=0  class=white_headng_cntr >
<tr bgcolor=#993400> <td align=center class=tdstyle><?php echo $getAboutUsArray[0]; ?></td></td>
</table>
<table  width='399' border=0 cellspacing=0 cellpading=0>
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
  
   </div>   
  </div>
   <div class="row_three">
    <?php if ($aData[2] != '') { ?>
   <div class="heading_content">Birth Parent Letter</div>

   <div class="body_content"><?=$aData[2]?>

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
    <?php //if ($getAboutUsArray[24] != '') {?>
  <!-- <div class="heading_content">Our Home</div> -->

  <!-- <div class="body_content"> -->
   <?php //$ggetAboutUsArray[24]?>

  <!-- </div> -->
   <?php //} ?> 
	
   </div>
</div>
 <div class="footer_title"><img src="images/temp_33.jpg" width="356" height="75" /></div>
  <div class="copy_right">@2011 Heart of Adoptions, Inc. All Rights Reserved</div>

</div>
<div class="colom_right"></div>

</div>

</body>
</html>
