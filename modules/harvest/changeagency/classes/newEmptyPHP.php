<?php
session_start();
$data = file_get_contents('http://www.parentfinder.com/test.php?profile='.$_GET['profile']);
$aData = (explode(";-",$data));
$datap = file_get_contents('http://www.parentfinder.com/test.php?profile='.$_GET['profile']);
$aDatap = (explode(";-",$datap));

$msg = '';

if(isset($_POST['first_name'])){

$datas = file_get_contents('http://www.parentfinder.com/achose1.php?profile='.$_GET['profile']);
$aDatas = (explode(";-",$datas));
$fullName = $aDatas[0];
if($aDatas[1] != ''){
$fullName .= " ".$aDatas[1];
}

//$to = "sales@tandybiz.com";
$to = "jessica@heartofadoptions.com";
$subject = "Mail for HOA profile - ".$fullName;
$txt = <<<EOF
Name = {$_POST['first_name']} \r\n
Phone = {$_POST['phone']} \r\n
Email = {$_POST['email']} \r\n
Affiliation = {$_POST['affiliation']} \r\n
Comments = {$_POST['comments']} \r\n
Can we leave an identifying message? = {$_POST['leave_identifying_msg']} \r\n
EOF;
$headers = "From: jessica@heartofadoptions.com" . "\r\n" .
"CC: jessica@heartofadoptions.com, bidisha.ghosh@cairsolutions.com, mark.livings@cairsolutions.com, Pat.Feldner@adoptsoft.com";
$result = mail($to,$subject,$txt,$headers);
if ($result)
{
	$msg = "<font color='green'><b>Message sent for the profile - ".$fullName.".</b></font>";
}
else
{
   $msg = "<font color='red'><b>Email could not be sent.</b></font>";
}
}
$profile = $_GET['profile'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Contact Us - <?=$aData[3]?><?php if($aData[4] != '') { ?> & <?php } ?><?=$aData[4]?></title>
<!--Make sure page contains valid doctype at the very top!-->


  <link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css"/>
        <link rel="stylesheet" href="css/template.css" type="text/css"/>
        <script src="js/jquery-1.6.min.js" type="text/javascript">
        </script>
        <script src="js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8">
        </script>
        <script src="js/jquery.validationEngine.js" type="text/javascript" charset="utf-8">
        </script>
        <script>
            jQuery(document).ready(function(){
                // binds form submission and fields to the validation engine
                jQuery("#formID").validationEngine();
            });

            /**
             *
             * @param {jqObject} the field where the validation applies
             * @param {Array[String]} validation rules for this field
             * @param {int} rule index
             * @param {Map} form options
             * @return an error string if validation failed
             */
            function checkHELLO(field, rules, i, options){
                if (field.val() != "HELLO") {
                    // this allows to use i18 for the error msgs
                    return options.allrules.validate2fields.alertText;
                }
            }
        </script>


<link href="css/style.css" rel="stylesheet" type="text/css" />

<style  type="text/css">
#call {
  height: 30px;
    left: 62px;
    position: relative;
    top: -43px;
    width: 68px;
    z-index: 1;
}


img {
	border: none;
}
#thumbnail img {
     cursor: pointer;
    left: 228px;
    position: relative;
    top: -51px;
	outline:none;
}
#large {
	display: none;
	position: absolute;
	padding: 5px;
	z-index: 10;
	min-height: 100px;
	width:215px;
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



</style>
</head>

<body>

<div class="main_contatiner">

<div class="title_field"><div class="title"><img src="images/title_02.jpg" width="400" height="88" /> </div>
</div>
<div class="colom_left">
<div class="menues">
<ul class="ul_style">
<li class="left_menu_style"><a href="http://hoa.cairsolutions.com/" class="selected">Home</a></li>
<li class="left_menu_style"><a href="http://www.myadoptionplan.com/GetStarted.html" class="menus">Getting Started</a></li>
<li class="left_menu_style"><a href="http://www.myadoptionplan.com/LiveChat.html" class="menus">Live Chat</a></li>
<li class="left_menu_style"><a href="http://hoa.cairsolutions.com/" class="menus">Adoptive Families</a></li>
<li class="left_menu_style"><a href="http://www.myadoptionplan.com/Questions.html" class="menus">Questions</a></li>
<li class="left_menu_style"><a href="http://www.myadoptionplan.com/Resources.html" class="menus">Resources</a></li>
<li class="left_menu_style"><a href="http://www.myadoptionplan.com/Testimonials.html" class="menus">Testimonials</a></li>
<li class="left_menu_style"><a href="http://hoa.cairsolutions.com/contactus.php" class="menus">Contact us</a></li>
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

  <?php
     require_once 'slide.php';
       // echo slide();
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
   <div class="left_photo_i"><img src="<?php if ($aData[9] == '') echo 'images/noimage.jpg'; else echo $aData[9];?>" title="Adoptive Family <?=$aData[3]?><?php if($aData[4] != '') { ?> & <?php } ?><?=$aData[4]?>" width="230" alt="<?=$aData[3]?><?php if($aData[4] != '') { ?> & <?php } ?><?=$aData[4]?>"><!--<img src="<?=$aDatap[9]?>" alt="Adoptive Family: " width="300" height="422">--></div>
   <div class="menu_photoe">
   <ul class="internalul" style="padding-left:0px;">

   <li class="internalli"><a href="#" class="menu_photo"> Contact Us</a></li>
   </ul>
   <div>


   </div>

   </div>

</div>

 <div class="right_body_box">

 <div class="address_box">

   If you have any comments or questions for us, please write a message below to find out more about us! An Adoption Specialist will get back with you to explain how we can talk more if you are interested in doing so.

   </div>
   <div class="contact_us_form">
<?php if($msg != '') { ?>
<p align="center"><?php echo $msg; ?></p>
<?php } ?>
<form id="formID" class="formular" method="post" action="">
<!--<form action="" method="post" onSubmit="return validate(this)" >-->
          <input name="fp_id" value="5553" type="hidden">
          <input name="func" value="afInquiry" type="hidden">
          <input name="__REDIRECT_URL" value="" type="hidden">
		  <input name="userprofile" id="userprofile" value="<?php echo $profile; ?>" type="hidden">

   <div class="row_contact">
   <label class="left_label">Name:  </label>
   <label>  <input value="" class="validate[required] text-input" type="text" name="first_name" id="first_name" />
   </label>
   </div>
  <div class="row_contact">
     <label class="left_label">Email ID:  </label>
     <label>  <input value="" class="validate[required,custom[email]] text-input" type="text" name="email" id="email" /></label>

  </div>
    <div class="row_contact" style="height:25px;">
     <label class="left_label">Contact No.:  </label>
     <label>  <input value="" class="validate[required,custom[phone]] text-input" type="text" name="phone" id="phone" /></label>

    </div>
 <div class="row_contact">
  <label class="left_label">Affiliation:  </label>
  <select name="affiliation" id="affiliation" class="validate[required]">
                        <option value="">Please Select</option>
                        <option value="Birthmother">Birthmother</option>
                        <option value="Adoptive Parent">Adoptive Parent</option>
                        <option value="Friend">Friend</option>
                        <option value="Other">Other</option>
                </select>

 </div>
  <div class="row_contact" style="height:67px; padding-top:0px;">
   <label class="left_label">Comments.:  </label>
    <label>
      <textarea name="comments" class="validate[required] text-input" id="comments"></textarea>
    </label>
  </div>
    <div class="row_contact">
      <div class="div_use" style="margin-right:5px;">Can we leave an identifying message ?</div>
      <label class="labelcon">
        <select name="leave_identifying_msg" id="leave_identifying_msg" >
          <option value="Yes">Yes</option>
          <option value="No">No</option>
        </select>
      </label>
    </div>
    <div class="row_contact" style="width:70px; margin:auto; text-align:center; margin-top:0px;"><span class="row_contact" style="width:200px; margin:auto; text-align:center; margin-top:10px;">
      <input type="submit" value="Submit"/>
    </span>


    </div>

 </form>

      <div id="thumbnail"><a href="hills.jpg" style="outline:none;"><img src="images/callBtn.png" border="0px"/></a>
  <p class="callToUs" id="large"></p>
</div>
<div align="center">
   <div id="large"></div>
   <div id="background"></div>
</div>
   </div>


   </div>

   </div>
</div>
 <div class="footer_title"><img src="images/temp_33.jpg" width="356" height="75" /></div>
  <div class="copy_right">@2011 Heart of Adoptions, Inc. All Rights Reserved</div>

</div>
<div class="colom_right"></div>

</div>

</body>
<link rel="stylesheet" type="text/css" href="css/newCssByJk.css"/>


<script type="text/javascript" language="javascript">
	jQuery.fn.center = function () {
		this.css("position","absolute");
		this.css("top", ( $(window).height() - this.height() ) / 2+$(window).scrollTop() + "px");
		this.css("left", ( $(window).width() - this.width() ) / 2+$(window).scrollLeft() + "px");
		return this;
	}

	$(document).ready(function() {
		$("#thumbnail img").click(function(e){

			$("#background").css({"opacity" : "0.7"})
							.fadeIn("slow");

			$("#large").html('<span>All calls are anonymous as we do not reveal or track your phone number</span><label>( <input type="text" maxlength="3" name="" id="textfield"> )<input type="text" maxlength="3" name="" id="textfield2">-<input type="text" class="cellLastNumber" maxlength="4" name="" id="textfield3"><input type="button" class="callBtn" onclick="javascript:request_call_local()" id="submitbtn" name=""></label><img id="closer" onclick="javascript:close_call_local()" style="position: relative; left: 43px; top: -70px;" src="images/close2.gif">')
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

		$("#large").click(function(){
			//$("#background").fadeOut("slow");
			//$("#large").fadeOut("slow");
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

                if (request.readyState < 4) {

		    document.getElementById("submitbtn").src="http://www.parentfinder.com/templates/base//images/callMe_2.png";

                } else if (request.readyState==4) {
		   alert(request.responseText);
                        if(request.responseText== "Call Connected")
                        {
                        document.getElementById("submitbtn").src="http://www.parentfinder.com/templates/base//images/callMe_3.png";
                        }
                        else{
                      document.getElementById("submitbtn").src="http://www.parentfinder.com/templates/base//images/callMe_1.png";
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
										 //var phonetocall ="7737508284";
                                         var contactnumber = "4078950675";
                                         var agencyname ="";
                                         var first= document.getElementById("textfield").value;
                                         var second= document.getElementById("textfield2").value;
                                         var third= document.getElementById("textfield3").value;
                                         var enterednumber=first+second+third;
                                         var id = enterednumber;
										 var userprofile= document.getElementById("userprofile").value;

                    if (id.length == 10)
                      {
                        // insert your click to xyz building block ID where indicated in the next line or you will receive invalid account responses.
                        // get the click to xyz building block id from the Tools menu
                         //var url = "http://www.parentfinder.com/clickto_proxy.php?app=ctc&id="+phonetocall+"&phone_to_call="+id+"&type=1&key=65687e8b8f39137da01defed01b2502c3faf3292&first_callerid="+2074532511+"&second_callerid="+2074532511+"&ref="+agencyname+"&page=ProfilePage";
						 //var url = "https://secure.ifbyphone.com/click_to_xyz.php?app=ctc&id=7739661112&phone_to_call=8132586505&type=2&key=65687e8b8f39137da01defed01b2502c3faf3292&first_callerid=2074532511&second_callerid=2074532511&ref=HOA&page=HOACustomWaitingFamilies"

						 var url = "https://secure.ifbyphone.com/click_to_xyz.php?app=ctc&id="+id+"&phone_to_call="+phonetocall+"&type=2&key=65687e8b8f39137da01defed01b2502c3faf3292&first_callerid=2074532511&second_callerid=2074532511&ref=HOA&page=http://hoa.cairsolutions.com/contactus.php?profile="+userprofile+"";


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
</html>

