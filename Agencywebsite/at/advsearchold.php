
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Advanced Search</title>
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

                if (request.readyState < 4) {

		    document.getElementById("submitbtn").src="http://localhost/parentfinders/templates/base//images/callMe_2.png";

                } else if (request.readyState==4) {
		   alert(request.responseText);
                        if(request.responseText== "Call Connected")
                        {
                        document.getElementById("submitbtn").src="http://localhost/parentfinders/templates/base//images/callMe_3.png";
                        }
                        else{
                      document.getElementById("submitbtn").src="http://localhost/parentfinders/templates/base//images/callMe_1.png";
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
<link href="css/style.css" rel="stylesheet" type="text/css" />

</head>

<body>
<?php  
 $data = file_get_contents('http://localhost/parentfinders/test.php?profile='.$_GET['profile']);
 $aData = (explode(";-",$data));
//print_r($aData);
$bpl = mb_substr($aData[0], 0, 260);
$profile = $_GET['profile'];
?>

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
 Heart of Adoptions, Inc. has many hopeful adoptive families waiting for a child. We will ask you exactly what you are looking for in an adoptive family to create your "dream family" list and then provide family profiles to choose from that most closely match your preferences.
</p>
 
   <p> Only a handful of our waiting families are on our website, so if you don't find the perfect family here, contact us for additional profiles.  If we don't already have the right family for you, we will search on your behalf until together we find the ideal family for your child.</p>

  
  <p> All waiting families have been pre-screened and meet requirements for adopting a child. If you have any questions about one of our waiting families, even if you aren't already working with Heart of Adoptions, Inc., we'd be happy to talk to you.</p>  </div>
  <div class="middle"> </div>
 <div class="right_col">
 
  <?php
     require_once 'slide.php';
        echo slide();
  ?> 
  
  </div>
  </div>
   <div class="body_line"> </div>
   <div class="body_internal">
     <!--<form id="form1" name="form1" method="post" action="">-->
     
     <form name="search" method="get" action="index.php" onsubmit="return checkForm(this);">
	 <input type="hidden" value="7" name="max_race_id">
       <table width="724" border="0" align="left" cellpadding="12" cellspacing="0" class="palatino_font">
         
         <tr>
           <td width="56" align="left" valign="top">
		   <div id="thumbnail">
		      <a href="hills.jpg"><img src="images/callBtn.png" width="25" height="25" border="0"/></a>
		      <p class="callToUs" id="large"></p>
              
		      </div>
		   <div align="center">   
   <div id="large"></div>   
   <div id="background"></div>
</div>
<div id="apDiv2"><a href="index.php" style="btnhoem"><img src="images/BUTTON RED.png" alt="Back to waiting families" title="Back to waiting families" border="0px"/></a></div>
		   <!--<a href="hills.jpg"><img src="images/callBtn.png" alt="hills" rel="Hills Image" /></a>-->
           
		   </td>
           
           <td width="648" align="left" valign="top">&nbsp;</td>
         </tr>
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
<input type="hidden" value="12" name="pend">
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
         <tr>
           <td colspan="2" align="left" valign="top"><span class="black_heading">Found a family you like?</span><br />
        
             Contact us to find out more about them and all of the options available to you.<br />
             <span class="black_heading">Can't find your ideal family?</span>
             <p>Let us know exactly what you are looking for and we will find the perfect family for you.</p></td>
         </tr>
       </table>
     </form>
     </div>
   </div>
<div class="footer_title"><img src="images/temp_33.jpg" width="356" height="75" /></div>
  <div class="copy_right">@2011 Heart of Adoptions, Inc. All Rights Reserved</div>

</div>
<div class="colom_right"></div>

</div>

</body>
</html>
