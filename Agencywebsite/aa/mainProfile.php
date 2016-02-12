<?php
session_start();

//ini_set('max_execution_time', 300); //300 seconds = 5 minutes. Place this at the top of yo
$getaboutus = file_get_contents('http://www.parentfinder.com/getaboutus_exe.php?profile='.$_GET['profile']);
$getAboutUsArray = (explode(";-",$getaboutus));
array_pop($getAboutUsArray);
//print_r($getAboutUsArray);

$aDataBlock = file_get_contents('http://www.parentfinder.com/getblockshoa_exe.php?profile='.$_GET['profile'].'');
$aDataBlocks = (explode(";-",$aDataBlock));
//print_r($aDataBlocks);
array_pop($aDataBlocks);

//$achildata = file_get_contents('http://www.parentfinder.com/getchild.php?profile='.$_GET['profile'].'');
//$achildatas = (explode(";-",$achildata));
//array_pop($achildatas);

$data = file_get_contents('http://www.parentfinder.com/test.php?profile='.$_GET['profile']);
$aData = (explode(";-",$data));
//print_r($aData);
$bpl = mb_substr($aData[0], 0, 260);
$profile = $_GET['profile'];

$datanew = file_get_contents('http://www.parentfinder.com/badgewaitingfamilieshoamainprofile.php?profile='.$_GET['profile']);
$aDatanew1 = (explode(";-",$datanew));


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<title>Main Profile - <?=$aData[3]?><?php if($aData[4] != '') { ?> & <?php } ?><?=$aData[4]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style>
<link rel="stylesheet" type="text/css" media="screen, print" href="css/style.css" />
<script type="text/javascript" language="javascript" src="js/scripts.js" ></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="js/animatedcollapse.js"></script>
<!--live chat -->
<script type="text/javascript">
(function() {
var livechat_params = '';

var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true;
var lc_src = ('https:' == document.location.protocol ? 'https://' : 'http://');
lc_src += 'chat.livechatinc.net/licence/1026499/script.cgi?lang=en&groups=0';
lc_src += ((livechat_params == '') ? '' : '&params='+encodeURIComponent(encodeURIComponent(livechat_params)));
lc.src = lc_src;
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s);
})();
</script>
<!-- End LiveChat track tag. See also www.livechatinc.com -->
</head>
<body id="categories">
<div id="wrap">
<div id="top-intro"> </div>
<!--top-intro -->
<div id="masthead"><a href="http://www.adoption-alliance.com" title="Adoption Alliance" id="logo"><span>Infant adoption agencies and services in texas, tx | Adoption Alliance</span></a>
  <div id="livechat"><img src="http://chat.livechatinc.net/licence/1026499/button.cgi?lang=en&amp;groups=0" style="cursor:pointer;cursor:hand" onClick="window.open('http://chat.livechatinc.net/licence/1026499/open_chat.cgi?groups=0'+'&amp;s=1&amp;lang=en&amp;dc='+encodeURIComponent(document.cookie+';l='+document.location+';r='+document.referer+';s='+typeof lc_session),'Czat_1026499','width=220,height=73,resizable=yes,scrollbars=no,status=1');"/></div>
  <p class="ppc-phone"><span>call now 1800-626-4324, for a personalized adoption plan</span></p>
</div>
<!--masterhead -->
<ul id="nav">
  <li id="nav-01"><a href="http://www.adoption-alliance.com/birth-parents-main-texas-tx" title=""><span>Birth Parents adoption texas</span></a>
    <ul>
      <li><a href="http://www.adoption-alliance.com/birth-parents-main-texas-tx/making-an-adoption-plan" title=""><span>Making an Adoption Plan</span></a></li>
      <li><a href="http://www.adoption-alliance.com/birth-parents-main-texas-tx/frequently-asked-questions" title=""><span>Frequently Asked Questions</span></a></li>
      <li><a href="http://www.adoption-alliance.com/birth-parents-main-texas-tx/birth-parent-comments" title=""><span>Birth Parent Comments</span></a></li>
      <li><a href="http://www.adoption-alliance.com/category/families-in-waiting" title=""><span>Choose Adoptive Parent</span></a></li>
    </ul>
  </li>
  <li id="nav-02"><a href="http://www.adoption-alliance.com/adoptive-parents-main-texas-tx" title=""><span>Adoptive Parents adoption in texas</span></a>
    <ul>
      <li><a href="http://www.adoption-alliance.com/adoptive-parents-main-texas-tx/general-overview" title=""><span>General Overview</span></a></li>
      <li><a href="http://www.adoption-alliance.com/adoptive-parents-main-texas-tx/frequently-asked-questions" title=""><span>Frequently Asked Questions </span></a></li>
      <li><a href="http://www.adoption-alliance.com/adoptive-parents-main-texas-tx/adoptive-parent-comments" title=""><span>Adoptive Parent Comments </span></a></li>
    </ul>
  </li>
  <li id="nav-03"><a href="http://waiting-aa.cairsolutions.com/" title=""><span>Waiting Families adoption and texas</span></a></li>
  <li id="nav-04"><a href="http://www.adoption-alliance.com/about-our-agency-texas-tx" title=""><span>Our adoption agency tx</span></a>
    <ul>
      <li><a href="http://www.adoption-alliance.com/about-our-agency-texas-tx/about-us" title=""><span>About Us</span></a></li>
      <li><a href="http://www.adoption-alliance.com/about-our-agency-texas-tx/history-licensing" title=""><span>History &amp; Licensing</span></a></li>
      <li><a href="http://www.adoption-alliance.com/about-our-agency-texas-tx/our-approach" title=""><span>Unique Approach</span></a></li>
      <li><a href="http://www.adoption-alliance.com/about-our-agency-texas-tx/our-staff" title=""><span>Our Staff</span></a></li>
      <li><a href="http://www.adoption-alliance.com/in-the-media-texas-tx" title="In The Media"><span>In The Media</span></a></li>
      <li><a href="http://www.adoption-alliance.com/about-our-agency-texas-tx/employment-opportunities" title="employment opportunities"><span>Employment Opportunities</span></a></li>
    </ul>
  </li>
  <li id="nav-05"><a href="http://www.adoption-alliance.com/forms-and-documents-texas-tx" title=""><span>Adoption Forms &amp; Documents texas</span></a>
    <ul>
      <li><a href="http://www.adoption-alliance.com/forms-and-documents-texas-tx/birth-parent-forms" title=""><span>Birth Parent Forms</span></a></li>
      <li><a href="http://www.adoption-alliance.com/forms-and-documents-texas-tx/adoptive-parent-forms" title=""><span>Adoptive Parent Forms</span></a></li>
      <li><a href="http://www.adoption-alliance.com/forms-and-documents-texas-tx/agency-newsletters" title=""><span>Agency Newsletters</span></a></li>
      <li><a href="http://www.adoption-alliance.com/forms-and-documents-texas-tx/recommended-reading" title=""><span>Recommended Reading</span></a></li>
    </ul>
  </li>
  <li id="nav-06"><a href="http://www.adoption-alliance.com/contact" title=""><span>Contact texas adoption agencies</span></a></li>
</ul>
<!-- end #nav -->
<div id="header-image"> <a rel="nofollow" href="http://www.adoption-alliance.com/birth-parents-main-texas-tx" id="header-pregnant1" title=""></a><a rel="nofollow" href="http://www.adoption-alliance.com/birth-parents-main-texas-tx" id="header-pregnant" title=""></a> <a rel="nofollow" href="http://www.adoption-alliance.com/adoptive-parents-main-texas-tx" id="header-adopt" title=""></a> </div>
<div id="column-left">
  
  
    <h1>
     <a style="color:#FFF;" href="javascript:history.go(-1)" >Home</a>&nbsp;&nbsp;&nbsp;
     <a style="color:#FFF;" href="mainProfile.php?profile=<?=$_GET['profile']?>" >Main Profile</a>&nbsp;&nbsp;&nbsp;
     <a style="color:#FFF;" href="photographs.php?profile=<?=$_GET['profile']?>" >Photographs</a>&nbsp;&nbsp;&nbsp;
     <a style="color:#FFF;" href="videosallinace.php?profile=<?=$_GET['profile']?>&v=watch" >Video</a>&nbsp;&nbsp;&nbsp;
     <a style="color:#FFF;" href="http://www.adoption-alliance.com/contact" target="_blank">Contact us</a>
    </h1>
   
<?php if($getAboutUsArray[1] != '') { ?>
<h2 id="search-index"><?php echo $getAboutUsArray[0]; ?> & <?php echo $getAboutUsArray[1]; ?></h2>
<?php }  else { ?>
<h2 id="search-index"><?php echo $getAboutUsArray[0]; ?></h2>
<?php } ?>

   
  <div class="main_contatiner">




<div class="main_center_container">

<div class="colom_middle_internal">
  <div class="body_main">
  
   <div class="body_line"> </div>
  
   
   <div class="body_internal">

   <div class="left_body_box">
   <div class="left_photo_i" style="text-align:center;"><img src="<?php if ($aDatanew1[3] == '') echo 'images/noimage.jpg'; else echo $aDatanew1[3];?>" title="Adoptive Family <?=$aData[3]?><?php if($aData[4] != '') { ?> & <?php } ?><?=$aData[4]?>" width="230" alt="<?=$aData[3]?><?php if($aData[4] != '') { ?> & <?php } ?><?=$aData[4]?>"><!--<img src="<?=$aData[9]?>" title="Adoptive Family Dave &amp; Krissy" width="300" height="422">--></div>
   <!-- <div class="menu_photoe">
   <ul class="internalul" style="padding-left:0px;">

   <li class="internalli"><a href="#" class="menu_photo"> <a href="#" class="menu_photo">Contact Us </a></li>
   </ul>
   <div>


   </div>

   </div> -->

</div>


<!-- <div class="right_body_box"> -->
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

/*
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
*/
?>
<!--
<?php //if($getAboutUsArray[1] != '') { ?>
<table  width='399' border=0 cellspacing=0 cellpading=0  class=white_headng_cntr >
<tr bgcolor=#636494> <td align=center class=tdstyle width="33%">&nbsp;</td><td align=center class=tdstyle width="33%"><?php //echo $getAboutUsArray[0]; ?></td><td align=center class=tdstyle width="33%"><?php //echo $getAboutUsArray[1]; ?></td></td>
</table>
<table  width='399' border=0 cellspacing=0 cellpading=0>
<?php //if ($getAboutUsArray[18] != '' && $getAboutUsArray[18] != '0000-00-00' && $getAboutUsArray[19] != '' && $getAboutUsArray[19] != '0000-00-00') { ?>
<tr> <td class=bold_black_ctn width="33%"><font color=black>Age:</td><td class=tdstyle width="33%" style="border-right:0px;"><?php //echo calc_age($getAboutUsArray[18]) //echo $da1;?></font></td><td class=tdstyle width="33%"><?php //echo calc_age($getAboutUsArray[19]) //echo $da2;?></font></td></tr>
<?php //} ?>
<tr> <td class=bold_black_ctn style="border:993400 solid 1px" width="33%"><font color=black>Education:</td> <td class=tdstyle style="border-right:0px;"><?php //echo $getAboutUsArray[2]; ?></font></td><td class=tdstyle><?php //echo $getAboutUsArray[13]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Profession:</td> <td class=tdstyle style="border-right:0px;"><?php //echo $getAboutUsArray[3]; ?></font></td><td class=tdstyle><?php //echo $getAboutUsArray[14]; ?></font></td></tr>
<tr><td class=bold_black_ctn><font color=black>Ethnicity:</td> <td class=tdstyle style="border-right:0px;"><?php //echo $getAboutUsArray[4]; ?></font></td><td class=tdstyle><?php //echo $getAboutUsArray[15]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Religion:</td> <td class=tdstyle style="border-right:0px;"><?php //echo $getAboutUsArray[5]; ?></font></td><td class=tdstyle><?php //echo $getAboutUsArray[16]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Smoking:</td> <td class=tdstyle style="border-right:0px;"><?php //echo $getAboutUsArray[6]; ?></font></td><td class=tdstyle><?php //echo $getAboutUsArray[17]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>State:</td> <td class=tdstyle colspan="2"><?php //echo $getAboutUsArray[7]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Married:</td> <td class=tdstyle colspan="2"><?php //echo $getAboutUsArray[8]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Residency:</td> <td class=tdstyle colspan="2"><?php //echo $getAboutUsArray[9]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Neighborhood:</td> <td class=tdstyle colspan="2"><?php //echo $getAboutUsArray[10]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Family Structure:</td> <td class=tdstyle colspan="2"><?php //echo $getAboutUsArray[11]; ?></font></td></tr>
<tr> <td class=bold_black_ctnl><font color=black>Pet(s):</td> <td class=tdstyleb colspan="2"><?php// echo $getAboutUsArray[12]; ?></font></td></tr>
</table>
<?php //}
//else {
?>
<table  width='399' border=0 cellspacing=0 cellpading=0  class=white_headng_cntr >
<tr bgcolor=#636494> <td align=center class=tdstyle><?php echo $getAboutUsArray[0]; ?></td></td>
</table>
<table  width='399' border=0 cellspacing=0 cellpading=0>
<?php //if ($getAboutUsArray[18] != '' && $getAboutUsArray[18] != '0000-00-00') { ?>
<tr> <td class=bold_black_ctn ><font color=black>Age:</td><td class=tdstyle><?php //echo $da1;?></font></td></tr>
<?php //} ?>
<tr> <td class=bold_black_ctn style=border:993400 solid 1px><font color=black>Education:</td> <td class=tdstyle><?php //echo $getAboutUsArray[2]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Profession:</td> <td class=tdstyle><?php //echo $getAboutUsArray[3]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Ethnicity:</td> <td class=tdstyle><?php //echo $getAboutUsArray[4]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Religion:</td> <td class=tdstyle><?php //echo $getAboutUsArray[5]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Smoking:</td> <td class=tdstyle><?php //echo $getAboutUsArray[6]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>State:</td> <td class=tdstyle><?php //echo $getAboutUsArray[7]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Married:</td> <td class=tdstyle><?php //echo $getAboutUsArray[8]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Residency:</td> <td class=tdstyle><?php //echo $getAboutUsArray[9]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Neighborhood:</td> <td class=tdstyle><?php //echo $getAboutUsArray[10]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Family Structure:</td> <td class=tdstyle><?php //echo $getAboutUsArray[11]; ?></font></td></tr>
<tr> <td class=bold_black_ctnl><font color=black>Pet(s):</td> <td class=tdstyleb><?php //echo $getAboutUsArray[12]; ?></font></td></tr>
</table>
<?php
//}
?>

    </div> -->  </div>
  <div class="row_three">
   <?php
    foreach ($aDataBlocks as $blockKey => $blockValue) :?>
		<?php if($blockKey >= 0 ) {?>
                <?php
                if(strstr($blockValue, '>Fun Facts</div>'))
                {
                   $facts = $blockValue;
                
                                }
                elseif(stristr($blockValue, 'E-Book Profile'))
                 {
                    $ebook = $blockValue;
                    
                              }
               else {
           
                $arr[] = $blockValue;
             
                     }
                        
                    ?>
	
                <?php  } ?>
	<?php endforeach;  //}

            if(isset($ebook)) {

              $link = 'http://www.parentfinder.com/profileflipbook/'.$_GET['profile'].'-'.$getAboutUsArray[32].'/'.$_GET['profile'].'-'.$getAboutUsArray[32].'.pdf';

              if($getAboutUsArray[34] != '') {

               $con = "Please click <a href=" . $link . " target= _blank>here</a> to view ". $getAboutUsArray[33] .  " and "  . $getAboutUsArray[34] . "'s" . " profile flip book";

                       }
              else {

                    $con = "Please click <a href=" . $link . " target= _blank>here</a> to view ". $getAboutUsArray[33] . " profile flip book";

                       }
                     ?>
          <div class="heading_content">E-book Profile</div>
          <div class="body_content"><?php echo $con ?> </div>
           <?php   } ?>

          <?php if ($aData[2] != '') { ?>
              <div class="heading_content">Birth Parent Letter</div>
              <div class="body_content"><?=$aData[2]?>
              </div>
          <?php }  // else { 


            if(isset($facts)) {

               echo $facts;

                 }

       
        $sizeof_arr = sizeof($arr);
        if($sizeof_arr  > 0) {

        foreach($arr as $blockKeyy => $blockValuee)
        if($blockKeyy >= 0 ) {

            echo  $blockValuee;
        
        }
        }
        ?>




<!-- <div class="heading_content">Child Preferences</div>


<table  width='599' border=0 cellspacing=0 cellpading=0 style='margin-left:0px;background-color: #FFFFFF'>
<tr> <td class=bold_black_ctn ><font color=black>Child Age:</td> <td class=tdstyle><?php //echo $getAboutUsArray[25]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Child Ethnicity:</td> <td class=tdstyle><?php //echo $getAboutUsArray[26]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Child Gender:</td> <td class=tdstyle><?php //echo $getAboutUsArray[27]; ?></font></td></tr>
<tr> <td class=bold_black_ctn><font color=black>Special Needs:</td> <td class=tdstyle><?php //echo $getAboutUsArray[28]; ?></font></td></tr>
<tr> <td class=bold_black_ctnl><font color=black>Child Desired:</td> <td class=tdstyleb><?php //echo $getAboutUsArray[29]; ?></font></td></tr>
</table>
-->
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

   <div class="body_content"><?=$getAboutUsArray[24]?>

   </div>
   <?php } ?>
   </div>
</div>


</div>


</div>
  </div>
  <!-- end #footer -->
</div>
<div id="column-right">
  <div class="content">
    <h2 class="special">contact us, quik contact form</h2>
    <p>Have questions about adoption,<strong> &quot;the Adoption Alliance&quot; </strong>can help you? Complete the quick contact form below and one of our staffs will respond in a timely fashion. You can also <strong>call us at  1800-626-4324.</strong></p>
    <a rel="nofollow" href="http://www.adoption-alliance.com/contact" class="ppc-phone"></a>
    <!--<iframe src ="https://www.myadoptionportal.com/signupmp.php?pluginoption=userslogin&type=signup&key_id=59" width="350" height="680" frameborder="0" scrolling="no" id ="signupframe" style="overflow:visible;"> -->
    <p>Your browser does not support iframes.</p>
    </iframe>
    <table border="0" id="address">
      <tr>
        <td><p><strong>Adoption Alliance</strong><br />
            7303 Blanco Road<br />
            San Antonio, Texas 78216</p>
          <p><strong>Toll Free: </strong><br />
            1-800-626-4324<br />
            Tel: (210) 349-3991<br />
            FAX: (210) 349-8075</p></td>
        <td><p><strong>Corpus Christi, Texas</strong><br />
            (361) 884-0057</p>
          <p><strong>Las Vegas, Nevada</strong><br />
            (702) 968-1986</p>
          <p><strong>Reno, Nevada</strong><br />
            (775) 851-7888</p></td>
      </tr>
    </table>

  </div>
  <!--end content -->
</div>
<div class="clear"></div>
</div>
<!-- end #wrap -->
<div id="footer-wrap">
  <div id="footer">
    <div class="left">
      <p><strong>Mission Statement:</strong><br />
        The Adoption Alliance, a non-profit adoption agency, was founded on the belief that lives of children can be changed forever through the adoption process. We provide professional adoption services to birth parents and adoptive families by educating and coordinating all aspects of the adoption process. </p> 
    </div>
    <!-- footer-left -->
    <div class="right">
      <p><a href="javascript:animatedcollapse.toggle('top-intro')" title="intro"><u>Adoption Alliance</u></a>, 7303 Blanco Road San Antonio, Texas 78216 <br />
        Toll Free: 1-800-626-4324 | FAX: (210) 349-8075 </p>
      <p>Copyright &copy; 2009 Adoption Alliance | <a rel="nofollow" href="http://www.ndmr.com" target="_blank">Web Design &amp; Internet Marketing by <strong>New Dimension Marketing &amp; Research</strong></a></p>
    </div>
    <!-- footer-right -->
    <div class="clear"></div>
  </div>
  <!-- end #footer -->
</div>
</body>
</html>