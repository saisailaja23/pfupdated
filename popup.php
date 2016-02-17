
<?php
require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );



      GLOBAL $site;
      GLOBAL $dir;
           		
         $aVars = '<div style="padding: 10px 0px 10px 20px;" class="bx_sys_block_info">
             <script type="text/javascript" src="'.$site['plugins'].'jquery/jquery.js"></script>

                   <script type="text/javascript" src="'.$site['plugins'].'jquery/jquery.autotab.js"></script>
                      <script language="javascript" type="text/javascript">

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

		    document.getElementById("submitbtn").src="'.$site['base'].'/images/callMe_2.png";

                } else if (request.readyState==4) {
		   alert(request.responseText);
                        if(request.responseText== "Call Connected")
                        {
                        document.getElementById("submitbtn").src="'.$site['base'].'/images/callMe_3.png";
                        }
                        else{
                      document.getElementById("submitbtn").src="'.$site['base'].'/images/callMe_1.png";
}
                }
            }

// *Function called by clicking on the "Click to Call" button in the form.
// *This function combines the three fields in the form into a 10 digit phone number field and if it is of a valid form, then call the proxy module
// *to perform the functions.

            function request_call_local(){


              if (!request) {
                    Alert ("sorry, click to call will not work with your browser");
                }
                else
                {
                                          var phonetocall ="'.$clicktocall.'";
                                          var contactnumber = "'.$contactnumber.'";

                                         // var phonetocall =   "3122248698";
                                         // var contactnumber = "3122248698";

                                        // var phonetocall = "312-224-8698";
                                        // var contactnumber = "312-224-8698";

                                         
                                         var agencyname ="'.$agencyname.'";
                                         var first= document.getElementById("txtfield").value;
                                         var second= document.getElementById("txtfield2").value;
                                         var third= document.getElementById("txtfield3").value;
                                         var enterednumber=first+second+third;
                                         var id = enterednumber;

                    if (id.length == 10)
                      {
                        // insert your click to xyz building block ID where indicated in the next line or you will receive invalid account responses.
                        // get the click to xyz building block id from the Tools menu
                        var url = "'.$site['url'].'clickto_proxy.php?app=ctc&id="+8132774272+"&phone_to_call="+id+"&type=1&key=65687e8b8f39137da01defed01b2502c3faf3292&first_callerid="+7273943980+"&second_callerid="+7273943980+"&ref="+agencyname+"&page=ProfilePage";
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
<p style ="color:#0181c5; font-style:bold; font-family:Georgia; font-size:20px;" >Call Our Adoption Specialist</p>
                                <table width="230" height="127" border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td align="left" valign="top" background="'.$site['base'].'/images/widget_bg.png">
                                 <table width="211" border="0" cellpadding="0" cellspacing="0">
                                  <tr>
                                    <td height="40" colspan="9" align="left" valign="top">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td height="40" colspan="9" align="left" valign="top">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td width="15" height="40" align="left" valign="top">&nbsp;</td>
                                    <td width="15" align="center" valign="middle" style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#556c7b;">(</td>
                                    <td width="34" align="center" valign="middle">
                                        <label>
                                          <input type="text"  name="txtfield" maxlength="3" id="txtfield" style="width:25px;" />
                                        </label>
                                    </td>
                                    <td width="15" align="center" valign="middle" style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#556c7b;">)</td>
                                    <td width="25" align="left" valign="middle">
                                    <input type="text"  maxlength="3" name="txtfield2" id="txtfield2" style="width:25px;" /></td>
                                    <td width="15" align="center" valign="middle" style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#556c7b;">-</td>
                                    <td width="25" align="left" valign="middle">
                                    <input type="text"  maxlength="4" name="txtfield3" id="txtfield3" style="width:55px;" /></td>
                                    <td width="15" height="30" align="left" valign="top">&nbsp;</td>
                                    <td width="25" align="left" valign="middle">
                                    <a href="javascript:request_call_local()">
                                    <img id="submitbtn" class="form-submit" value="Submit" alt="Call Me" src="'.$site['base'].'/images/callMe_1.png" width="33" height="33" /></a>
                                    </td>

                                    <td width="15" height="40" align="left" valign="top">&nbsp;</td>
                                  </tr>
                                </table></td>

                              </tr>
                            </table>

 

 <script type="text/javascript" >
                                 $("#txtfield").autotab({ target: "txtfield2", format: "numeric" });
                                 $("#txtfield2").autotab({ target: "txtfield3", format: "numeric", previous: "txtfield" });
                                 $("#txtfield3").autotab({ previous: "txtfield2", format: "numeric" });
</script>
</div>
'
       ;
        
       echo  $aVars;
 

?>