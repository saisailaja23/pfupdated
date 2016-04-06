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
$to = "prashanth.adkathbail@mediaus.com";
$subject = "Mail for AA profile - ".$fullName;
$txt = <<<EOF
Name = {$_POST['first_name']} \r\n
Phone = {$_POST['phone']} \r\n
Email = {$_POST['email']} \r\n
Affiliation = {$_POST['affiliation']} \r\n
Comments = {$_POST['comments']} \r\n
Can we leave an identifying message? = {$_POST['leave_identifying_msg']} \r\n
EOF;
$headers = "From: prashanth.adkathbail@mediaus.com" . "\r\n" .
"CC: prashanth.adkathbail@mediaus.com";
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
<head profile="http://gmpg.org/xfn/11">
<title>Contact Us - <?=$aData[3]?><?php if($aData[4] != '') { ?> & <?php } ?><?=$aData[4]?></title>
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
<div id="header-image"> <a rel="nofollow" href="http://www.adoption-alliance.com/birth-parents-main-texas-tx" id="header-pregnant" title=""></a> <a rel="nofollow" href="http://www.adoption-alliance.com/adoptive-parents-main-texas-tx" id="header-adopt" title=""></a> </div>
<div id="column-left">
 <h1>
     <a style="color:#FFF;" href="index.php?profile=<?=$_GET['profile']?>" >Home</a>&nbsp;&nbsp;&nbsp;
     <a style="color:#FFF;" href="mainProfile.php?profile=<?=$_GET['profile']?>" >Main Profile</a>&nbsp;&nbsp;&nbsp;
     <a style="color:#FFF;" href="photographs.php?profile=<?=$_GET['profile']?>" >Photographs</a>&nbsp;&nbsp;&nbsp;
     <a style="color:#FFF;" href="videosallinace.php?profile=<?=$_GET['profile']?>&v=watch" >Video</a>&nbsp;&nbsp;&nbsp;
     <a style="color:#FFF;" href="contact.php?profile=<?=$_GET['profile']?>" >Contact us</a>
    </h1>
    <h2 id="search-index">Contact</h2>
  <div class="content">
      <div>
      <div class="entry">
        <div id="wpcf7-f1-p1469-o1" class="wpcf7"><form id="formID" class="formular" method="post" action="">
<div style="display: none;">
<input type="hidden" value="1" name="_wpcf7">
<input type="hidden" value="2.4.6" name="_wpcf7_version">
<input type="hidden" value="wpcf7-f1-p1469-o1" name="_wpcf7_unit_tag">
</div>
<table cellpadding="5" border="0" width="100%" id="main-contact">
<tbody>
<tr>
<td class="form-left">Name:<span>*</span></td>
<td><span class="wpcf7-form-control-wrap firstname"><input type="text" size="40" class="wpcf7-text wpcf7-validates-as-required" value="" name="first_name"></span></td>
</tr>
<tr>
<td class="form-left">Email ID:<span>*</span></td>
<td><span class="wpcf7-form-control-wrap email"><input type="text" size="40" class="wpcf7-text wpcf7-validates-as-email wpcf7-validates-as-required" value="" name="email"></span></td>
</tr>
<tr>
<td>Contact No:</td>
<td><span class="wpcf7-form-control-wrap phone"><input type="text" size="40" class="wpcf7-text" value="" name="phone"></span></td>
</tr>
 <tr>
<td class="form-left">Affiliation:<span>*</span></td>
<td><span class="wpcf7-form-control-wrap clienttype"><select class="wpcf7-select wpcf7-validates-as-required" name="affiliation"> <option value="">Please Select</option>
                        <option value="Birthmother">Birthmother</option>
                        <option value="Adoptive Parent">Adoptive Parent</option>
                        <option value="Friend">Friend</option>
                        <option value="Other">Other</option></select></span></td>
</tr>
<tr>
<td>Comments:</td>
<td><span class="wpcf7-form-control-wrap message"><textarea rows="10" cols="40" name="comments"></textarea></span></td>
</tr>
 <tr>
<td class="form-left">Can we leave an identifying message?:</td>
<td><span class="wpcf7-form-control-wrap clienttype"><select class="wpcf7-select wpcf7-validates-as-required" name="leave_identifying_msg"> <option value="Yes">Yes</option>
          <option value="No">No</option>
</tr>
<tr>
<td  colspan="2">
<input type="submit" class="wpcf7-submit" value="Send"><img src="http://www.adoption-alliance.com/wp-content/plugins/contact-form-7/images/ajax-loader.gif" alt="Sending ..." style="visibility: hidden;" class="ajax-loader"></p></td>
 </tr>
</tbody></table>
<div class="wpcf7-response-output wpcf7-display-none"></div></form></div>
              </div>
      <!--entry-->
    </div>
    <!--post-->
          </div>
  <!--content-->
  </div>
<!-- end #column-left -->

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
<!-- end #column-right -->
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