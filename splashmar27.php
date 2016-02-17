<?php
require_once('inc/header.inc.php');
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once(BX_DIRECTORY_PATH_INC . 'languages.inc.php');
$iId = (int) $_COOKIE['memberID'];
if ($iId == 0) {
    $url = 'splash.php';
} else {
    // $url='index.php';
    // echo '<script language="JavaScript">
    //     window.location.href = "index.php" </script>';
    header('Location: index.php');
}


$infoUrl = getTemplateIcon('info.gif');
$warnUrl = getTemplateIcon('exclamation.png');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Parent finder</title>
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <!-- style sheets -->
        <link  rel="stylesheet" type="text/css" href="plugins/dhtmlx/dhtmlx.css" />
        <link rel="stylesheet" type="text/css" href="viewourfamilies/css/bootstrap.min.css?cache=__cache_control__"/>
        <link rel="stylesheet" type="text/css" href="viewourfamilies/css/parentfinder.css?cache=__cache_control__"/>
        <!--<link  rel="stylesheet" type="text/css" href="templates/tmpl_par/css/style.css" />-->


        <!-- JavaScripts libraries -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <!--<script src="plugins/dhtmlx/dhtmlx.js"></script>
        <script  src="plugins/dhtmlx/ext/dhtmlxgrid_export.js"></script>
        <script  src="plugins/dhtmlx/ext/dhtmlxform_item_container.js"></script>-->
        <!--<script src="plugins/dhtmlx/connector/dhtmlxdataprocessor.js" type="text/javascript" charset="utf-8"></script>
        <script src="plugins/dhtmlx/connector/connector.js" type="text/javascript" charset="utf-8"></script>-->

        <!-- dhtmlx compnents-->
        <!--<script type="text/javascript" src="MemberComponent/model/MemberComponent_Model.js"></script>
        <script type="text/javascript" src="MemberComponent/controller/MemberComponent.js"></script>

        <script type="text/javascript" src="LoginComponent/model/LoginComponent_Model.js"></script>
        <script type="text/javascript" src="LoginComponent/controller/LoginComponent.js"></script>

        <script type="text/javascript" src="NewAgencyComponent/model/NewAgencyModel.js"></script>
        <script type="text/javascript" src="NewAgencyComponent/controller/NewAgencyController.js"></script>

        <script type="text/javascript" src="SearchComponent/model/SearchComponent_Model.js"></script>
        <script type="text/javascript" src="SearchComponent/controller/SearchComponent.js"></script>-->

        <script type="text/javascript" src="templates/tmpl_par/css/ui.fix.js"></script>
        <script src="viewourfamilies/js/bootstrap.min.js?cache=__cache_control__"></script>
        <!--<script src="viewourfamilies/js/respHeaderFunctions.js?cache=__cache_control__"></script>-->
        <!-- Calling the components -->
        <script type="text/javascript" >
            var siteurl = '<?php echo $site['url']; ?>';
            //  function showPopup() {
            //    debugger;
            //    MemberComponent.start({
            //      uid: (new Date()).getTime(),
            //     // uid: "Member",
            //      siteurl: siteurl,
            //      application_path: siteurl + "/MemberComponent/",
            //      dhtmlx_codebase_path: siteurl + "/plugins/dhtmlx/"
            //    });
            //  }
            //  function LoginPopup() {
            //    LoginComponent.start({   
            //      uid : (new Date()).getTime() ,
            //      application_path : siteurl +"LoginComponent/"  ,
            //      dhtmlx_codebase_path : siteurl +"plugins/dhtmlx/" 
            //    });
            // }

            // window.onload = function(){ 
            //   SearchComponent.start({   
            //     uid : (new Date()).getTime() ,
            //     application_path : siteurl +"SearchComponent/",
            //     dhtmlx_codebase_path : siteurl +"plugins/dhtmlx/" 
            //   });
            // };
            //Redirecting user base on ther type
            function userredirect(id, status) {
                var siteurl = '<?php echo $site['url']; ?>';
                if (id == '2') {
                    window.location.href = siteurl + 'extra_profile_view_13.php';
                }
                else if (id == '4') {
                    window.location.href = siteurl + 'extra_profile_view_20.php';
                }
                else if (id == '8') {
                    if (status == 'Approval') {
                        window.location.href = siteurl + 'splash.php';
                    } else {
                        window.location.href = siteurl + 'extra_agency_view_27.php';
                    }
                } else {
                    window.location.href = siteurl;
                }
            }
        </script>
        <style type="text/css">

            .bottomCpr a {
                color: #f195bf !important;
            }
            .bottomCpr a:hover {
                color: #a2a4a7 !important;
            }
            .pf_content_menu a {
                padding: 4px 0px 4px 0px;
            }
        </style>
    </head>
    <body class="splash">
        <div id="vp">
            <?php
            $profileinfo = getProfileInfo(getLoggedId());
            ?>
            <div class="wrap">
                <!-- body -->

                <div class="modal popup" id="login">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="closebtn closebtnLogin" data-dismiss="modal"><!-- close button--></div>
                                <div class="login pf_popupcl01">
                                    <div class="pf_popup_login_cl01">LOGIN</div>
                                    <div class="loginIncorrect incorrectdata">Username or Password is incorrect</div>
                                    <div class="pfForm">
                                        <div class="pf_popup_login_cl02">PROFILE</div>
                                        <div class="pf_popup_login_cl03">
                                            <span>*</span><label class="loginText"> USERNAME:</label>
                                            <input type="text" name="username"/>
                                        </div>
                                        <div class="pf_popup_login_cl03">
                                            <span>*</span><label class="loginText">PASSWORD:</label>
                                            <input type="password" name="password"/>
                                        </div>
                                        <!--                        <div class="pf_popup_login_cl03">
                                                                    <label> REMEMBER ME</label>
                                                                    <input type="checkbox" name="rememberme" id="rememberme"/>
                                                                </div>-->
                                        <div>
                                            <div class="pf_popup_login_cl04">
                                                <div class="pf_popup_login_cl03">
                                                    <input type="checkbox" name="rememberme" id="rememberme"/>
                                                    <label id="remembermelabel"> REMEMBER ME</label>
                                                </div>
                                                <div class="pf_popup_login_cl05">
                                                    <div class="loginSubmit pf_popup_login_cl08">LOGIN</div>
                                                    <span class="forgotPwd pf_popup_login_cl09">FORGOT PASSWORD ?</span>
                                                </div>                      
                                                <div class="pf_popup_login_cl05">
                                                    <span class="pf_popup_login_cl010">NOT A MEMBER YET ?</span>                
                                                    <span class="join_now pf_popup_login_cl011">JOIN NOW! </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="clear: both"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal popup" id="forgot">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="closebtn" data-dismiss="modal"><!-- close button--></div>
                                <div class="pf_fp pf_popupcl01">
                                    <div class="pf_popup_login_cl01">FORGOT PASSWORD</div>
                                    <div class="pfForm">
                                        <div class="forgotForm">
                                            <div class="pf_popup_login_cl03">
                                                Forgot your ID and/or password? No problem! please supply your email address below and you will be sent your Parentfinder account ID and password
                                            </div>
                                            <div class="pf_popup_login_cl03">
                                                <label class="forgotText">*MY EMAIL</label>
                                                <input type="text" name="forgotemail"/>
                                                <div class="forgotSubmit pf_popup_login_cl08">RESET</div>
                                            </div>
                                            <div class="forgotError">The email address you have entered does not exist.</div>
                                        </div>
                                        <div class="forgotSucc aloneContent">A new password has been sent to the email entered.</div>
                                        <div style="clear: both"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal popup" id="join">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="closebtn" data-dismiss="modal"><!-- close button--></div>
                                <div class="join pf_popupcl01">
                                    <div class="pf_popup_login_cl01">JOIN</div>
                                    <div class="pfForm">
                                        <div class="pf_popup_login_cl03">
                                            <div class="pf_popup_login_cl02">PROFILE TYPE</div>
                                            <div style="display: none" id='APJoinFormClick' data-toggle='modal' href='#APJoinForm'></div>
                                            <div style="display: none" id='BPJoinFormClick' data-toggle='modal' href='#BPJoinForm'></div>
                                            <div style="display: none" id='AAJoinFormClick' data-toggle='modal' href='#AAJoinForm'></div>
                                            <select id="joinList">
                                                <option value="0" text="Select"> Select </option>
                                            </select>
                                            <div class="joinForm"></div>
                                        </div>
                                        <div style="clear: both"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal popup" id="APJoinForm">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="closebtn" data-dismiss="modal"><!-- close button--></div>
                                <div class="apjoin pf_popupcl01">
                                    <div class="pf_popup_login_cl01">JOIN</div>
                                    <div class="loginIncorrect pwdsmismatch">Passwords did not match</div>
                                    <div class="loginIncorrect usernameIssue">Username must contain only Latin symbols, numbers or underscore(_)or minus(-)signs</div>
                                    <div class="loginIncorrect nameIssue">Name must contain only Latin symbols, numbers or underscore(_)or minus(-)signs</div>
                                    <div class="loginIncorrect pwdIssue">Password length should be atleast six characters.</div>
                                    <div class="loginIncorrect alreadyExist"></div>
                                    <div class="loginIncorrect usernameAlreadyExist">Username is already in use</div>
                                    <div class="loginIncorrect emailAlreadyExist">Email is already in use</div>
                                    <div class="loginIncorrect agreement">You must agree to the Terms of Use</div>
                                    <div class="aloneContent Joinsuccess">Please select the subscription to complete the joining process.</div>
                                    <div class="pfForm">
                                        <div class="profileDetails">
                                            <div class="pf_popup_login_cl02">PROFILE</div>
                                            <div class="pf_popup_login_cl03_check">
                                                <input type="radio" name="AP_marital" value="single" class="marital" checked/><label id="Single"> SINGLE</label> 
                                                <input type="radio" name="AP_marital" value="couple" class="marital"/><label id="Couple"> COUPLE</label> 
                                            </div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="APusernamelabel" class="loginText"> USERNAME:</label>
                                                <input id="APusername" class="blurValidate" type="text" name="APusername"/>
                                            </div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="APpasswordlabel" class="loginText">PASSWORD:</label>
                                                <input id="APpassword" type="password" name="APpassword"/>
                                            </div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="APConfPasswordlabel" class="loginText">CONFIRM PASSWORD:</label>
                                                <input id="APConfPassword" type="password" name="APConfPassword"/>
                                            </div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="APemaillabel" class="loginText">EMAIL:</label>
                                                <input id="APemail" class="blurValidate" type="text" name="APemail"/>
                                            </div>
                                        </div>
                                        <div class="firstParent">
                                            <div class="pf_popup_login_cl02">ABOUT</div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="AP_p1_FNlabel" class="loginText">FIRST NAME:</label>
                                                <input type="text" name="AP_p1_FN"/>
                                            </div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="AP_p1_LNlabel" class="loginText">LAST NAME:</label>
                                                <input type="text" name="AP_p1_LN"/>
                                            </div>
                                            <div class="pf_popup_login_cl03_check">
                                                <input type="radio" name="AP_p1_gender" class="p1_gender" value='male' checked/><label id="p1_man" class="loginText"> MAN</label> 
                                                <input type="radio" name="AP_p1_gender" value='female' class="p1_gender"/><label id="p1_woman" class="loginText"> WOMAN</label> 
                                            </div>
                                        </div>
                                        <div class="secondParent">
                                            <div class="pf_popup_login_cl02">ABOUT - SECOND PERSON</div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="AP_p2_FNlabel" class="loginText">FIRST NAME:</label>
                                                <input type="text" name="AP_p2_FN"/>
                                            </div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="AP_p2_LNlabel" class="loginText">LAST NAME:</label>
                                                <input type="text" name="AP_p2_LN"/>
                                            </div>
                                            <div class="pf_popup_login_cl03_check">
                                                <input type="radio" name="AP_p2_gender" value='male' class="p2_gender" checked/><label id="p2_man" class="loginText"> MAN</label> 
                                                <input type="radio" name="AP_p2_gender" value='female' class="p2_gender"/><label id="p2_woman" class="loginText"> WOMAN</label> 
                                            </div>
                                        </div>
                                        <div class="agency_Info">
                                            <div>
                                                <div class="pf_popup_login_cl02">AGENCY INFORMATION<span class="extraAddAgency">(IF YOUR AGENCY IS NOT LISTED, PLEASE <a class="addagency">CLICK HERE</a>)</span></div>
                                            </div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="aaListlabel" class="loginText">ADOPTION AGENCY:</label>
                                                <select class="aaList">
                                                    <option value="0" text="Select"> Select an Agency </option>
                                                </select>
                                            </div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="stateListlabel" class="loginText">STATE:</label>
                                                <select class="stateList">
                                                    <option value="0" text="Select"> Select a State</option>
                                                </select>
                                            </div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="regionListlabel" class="loginText">REGION:</label>
                                                <select class="regionList">
                                                    <option value="0" text="Select"> Select a Region</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="pf_popup_login_cl03_check termsCond">
                                            <input type="checkbox" name="ap_terms" class="ap_terms"/><label id="ap_termslabel"> I HAVE READ AND AGREED WITH <a href="terms_of_use.php" target="_blank" style="color:#009D8C">TERMS OF USE</a></label> 
                                        </div>
                                        <div class="joinDisabled joinButton pf_popup_login_cl08">JOIN NOW</div>
                                        <div style="clear: both"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal popup" id="BPJoinForm">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="closebtn" data-dismiss="modal"><!-- close button--></div>
                                <div class="bpjoin pf_popupcl01">
                                    <div class="pf_popup_login_cl01">JOIN</div>
                                    <div class="loginIncorrect pwdsmismatch">Passwords did not match</div>
                                    <div class="loginIncorrect usernameIssue">Username must contain only Latin symbols, numbers or underscore(_)or minus(-)signs</div>
                                    <div class="loginIncorrect nameIssue">Name must contain only Latin symbols, numbers or underscore(_)or minus(-)signs</div>
                                    <div class="loginIncorrect pwdIssue">Password length should be atleast six characters.</div>
                                    <div class="loginIncorrect alreadyExist"></div>
                                    <div class="loginIncorrect usernameAlreadyExist">Username is already in use</div>
                                    <div class="loginIncorrect emailAlreadyExist">Email is already in use</div>
                                    <div class="loginIncorrect agreement">You must agree to the Terms of Use</div>
                                    <div class="pfForm">
                                        <div class="profileDetails">
                                            <div class="pf_popup_login_cl02">PROFILE</div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="BPusernamelabel" class="loginText"> USERNAME:</label>
                                                <input id="BPusername" class="blurValidate" type="text" name="BPusername"/>
                                            </div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="BPpasswordlabel" class="loginText">PASSWORD:</label>
                                                <input id="BPpassword" type="password" name="BPpassword"/>
                                            </div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="BPConfPasswordlabel" class="loginText">CONFIRM PASSWORD:</label>
                                                <input id="BPConfPassword" type="password" name="BPConfPassword"/>
                                            </div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="BPemaillabel" class="loginText">EMAIL:</label>
                                                <input id="BPemail" type="text" class="blurValidate" name="BPemail"/>
                                            </div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="BP_p1_FNlabel" class="loginText">FIRST NAME:</label>
                                                <input type="text" name="BP_p1_FN"/>
                                            </div>
                                        </div>
                                        <div class="agency_Info">
                                            <div class="pf_popup_login_cl02">AGENCY INFORMATION<span class="extraAddAgency">(IF YOUR AGENCY IS NOT LISTED, PLEASE <a class="addagency">CLICK HERE</a>)</span></div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="aaListlabel" class="loginText">ADOPTION AGENCY:</label>
                                                <select class="aaList">
                                                    <option value="0" text="Select"> Select an Agency </option>
                                                </select>
                                            </div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="stateListlabel" class="loginText">STATE:</label>
                                                <select class="stateList">
                                                    <option value="0" text="Select"> Select a State</option>
                                                </select>
                                            </div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="regionListlabel" class="loginText">REGION:</label>
                                                <select class="regionList">
                                                    <option value="0" text="Select"> Select a Region</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="pf_popup_login_cl02">YOUR PERSONAL INFORMATION STAYS 100% ANONYMOUS AND YOUR ARE ONLY KNOW ON PARENTFINDER AS YOUR USERNAME</div>
                                        <div class="pf_popup_login_cl03_check termsCond">
                                            <input type="checkbox" name="bp_terms" class="ap_terms"/><label id="ap_termslabel"> I HAVE READ AND AGREED WITH <a href="terms_of_use.php" target="_blank" style="color:#009D8C">TERMS OF USE</a></label> 
                                        </div>
                                        <div class="joinDisabled joinButton pf_popup_login_cl08">JOIN NOW</div>
                                        <div style="clear: both"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal popup" id="AAJoinForm">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="closebtn" data-dismiss="modal"><!-- close button--></div>
                                <div class="aajoin pf_popupcl01">
                                    <div class="pf_popup_login_cl01">JOIN</div>
                                    <div class="loginIncorrect pwdsmismatch">Passwords did not match</div>
                                    <div class="loginIncorrect usernameIssue">Username must contain only Latin symbols, numbers or underscore(_)or minus(-)signs</div>
                                    <div class="loginIncorrect nameIssue">Name should be 30 characters maximum only.</div>
                                    <div class="loginIncorrect pwdIssue">Password length should be atleast six characters.</div>
                                    <div class="loginIncorrect alreadyExist"></div>
                                    <div class="loginIncorrect usernameAlreadyExist">Username is already in use</div>
                                    <div class="loginIncorrect emailAlreadyExist">Email is already in use</div>
                                    <div class="loginIncorrect agreement">You must agree to the Terms of Use</div>
                                    <div class="aloneContent Joinsuccess">PLEASE WAIT FOR ADMINISTRATIVE APPROVAL</div>
                                    <div class="pfForm">
                                        <div class="profileDetails">
                                            <div class="pf_popup_login_cl02">PROFILE</div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="AAusernamelabel" class="loginText"> USERNAME:</label>
                                                <input id="AAusername" type="text" class="blurValidate" name="AAusername"/>
                                            </div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="AApasswordlabel" class="loginText">PASSWORD:</label>
                                                <input id="AApassword" type="password" name="AApassword"/>
                                            </div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="AAConfPasswordlabel" class="loginText">CONFIRM PASSWORD:</label>
                                                <input id="AAConfPassword" type="password" name="AAConfPassword"/>
                                            </div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="AAemaillabel" class="loginText">EMAIL:</label>
                                                <input id="AAemail" type="text" class="blurValidate" name="AAemail"/>
                                            </div>
                                        </div>
                                        <div class="agency_Info">
                                            <div class="pf_popup_login_cl02">AGENCY INFORMATION</div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="aaListlabel" class="loginText">ADOPTION AGENCY:</label>
                                                <input id="AAName" type="text" name="AAName"/>
                                            </div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="stateListlabel" class="loginText">STATE:</label>
                                                <select class="stateList">
                                                    <option value="0" text="Select"> Select a State</option>
                                                </select>
                                            </div>
                                            <div class="pf_popup_login_cl03">
                                                <span>*</span><label id="regionListlabel" class="loginText">REGION:</label>
                                                <select class="regionList">
                                                    <option value="0" text="Select"> Select a Region</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="pf_popup_login_cl03_check termsCond">
                                            <input type="checkbox" name="aa_terms" class="ap_terms"/><label id="ap_termslabel"> I HAVE READ AND AGREED WITH <a href="terms_of_use.php" target="_blank" style="color:#009D8C">TERMS OF USE</a></label> 
                                        </div>
                                        <div class="joinDisabled joinButton pf_popup_login_cl08">JOIN NOW</div>
                                        <div style="clear: both"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal popup" id="AddAAJoinForm">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="closebtn" data-dismiss="modal"><!-- close button--></div>
                                <div class="addaajoin pf_popupcl01">
                                    <div class="pf_popup_login_cl01">AGENCY DETAILS</div>
                                    <div class="pfForm">
                                        <div class="addaaForm">
                                            <div class="profileDetails">
                                                <div class="pf_popup_login_cl02">Please enter the following details to send a request to the agency to join Parent Finder.</div>
                                                <div class="pf_popup_login_cl02">YOUR DETAILS</div>
                                                <div class="pf_popup_login_cl03">
                                                    <span>*</span><label id="AddAAusernamelabel" class="loginText"> YOUR NAME</label>
                                                    <input id="AddAAusername" type="text" name="AddAAusername"/>
                                                </div>
                                                <div class="pf_popup_login_cl03">
                                                    <span>*</span><label id="AddAAemaillabel" class="loginText">YOUR EMAIL</label>
                                                    <input id="AddAAemail" type="text" name="AddAAemail"/>
                                                </div>
                                            </div>
                                            <div class="agency_Info">
                                                <div class="pf_popup_login_cl02">AGENCY DETAILS</div>
                                                <div class="pf_popup_login_cl03">
                                                    <span>*</span><label id="Add_AANamelabel" class="loginText">AGENCY NAME</label>
                                                    <input id="Add_AAName" type="text" name="Add_AAName"/>
                                                </div>
                                                <div class="pf_popup_login_cl03">
                                                    <span>*</span><label id="Add_AAEmaillabel" class="loginText">AGENCY EMAIL</label>
                                                    <input id="Add_AAEmail" type="text" name="Add_AAEmail"/>
                                                </div>
                                                <div class="pf_popup_login_cl03">
                                                    <span>*</span><label id="stateListlabel" class="loginText">AGENCY STATE</label>
                                                    <select class="stateList">
                                                        <option value="0" text="Select"> Select a State</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="joinSubmit pf_popup_login_cl08">SUBMIT</div>
                                            <div style="clear: both"></div>
                                        </div>
                                        <div class="aloneContent addagsuccess">Your request has been sent to the Agency.</div>
                                        <div class="aloneContent addagfail" style="color:#f195bf">Error while sending notification to Agency.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                <!--                <div class="main">
                    <header class="header">
                        <a href="#" class="logo">
                            <img class="topLogo" src="templates/tmpl_par/images/splash/top_logo.png" alt="Parentfinder logo" title="Parent finder"/>
                        </a>

                        <div class="topPromoTxt">connecting adoptive couples, agencies, attorneys and expectant parents</div>
                        <div class="memberArea">
                                            </?php echo getLoginPar(); ?>
                                            </?php if (isLogged()) { ?>
                                <div class="topIcons_home">
                                                    <a href="#" class="icoHome" title="Home" onclick="userredirect(</?php echo $profileinfo['ProfileType'] ?>, '</?php echo $profileinfo['Status'] ?>');" style="cursor: pointer;"></a>
                                    <a title="Search" class="icoSearch" href="extra_profile_view_24.php"></a>
                                    <a title="Contact Us" class="icoContact" href="contact.php"></a>
                                    <a title="Help" class="icoHelp" href="help.php"></a>
                                </div>

                                                        <div class="topIcons_home">                       
                                                          <a href="#" class="icoAccount" title="</?php echo ($profileinfo['Couple'] == '0') ? $profilestatus = "MYP ROFILE" : $profilestatus = "OUR PROFILE"; ?>" onclick="userredirect(<?php echo $profileinfo['ProfileType'] ?>,'<?php echo $profileinfo['Status'] ?>');" style="cursor: pointer;"></a>
                                          <a href="splash.php" title="HOME" class="icoHome"></a>
                                          <a href="extra_profile_view_24.php" title="SEARCH" class="icoSearch"></a>
                                          <a href="contact.php" title="CONTACT US" class="icoContact"></a>
                                          <a href="help.php" title="HELP" class="icoHelp"></a>
                                         </div> 
                                         </div>


                                            </?php } ?>
                
                            <div class="clear"></div>
                    </header>
                                </div>-->

                <script src="viewourfamilies/js/respHeaderFunctions.js?cache=__cache_control__"></script>

                <div class="modal" id="navmenu"><!-- nav menu modal dialog responsive-->
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-body">
                                <div class="pf_content_menu">
                                    <span><!-- dropdown image --></span>
                                    <a href="index.php">PARENTFINDER SERVICES </a>
                                    <a href="adoptive_couples.php">ADOPTIVE FAMILIES</a>
                                    <a href="agencies.php">AGENCIES/ATTORNEYS</a>
                                    <a href="expectant_parents.php"class="last">EXPECTANT PARENTS</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="container home" >    
                    <div class="row">   
                        <div id="main"><!-- main left section -->
                            <header>
                                <div id="pf_header_id01" ><!-- responsive header-->
                                    <div class="pf_header_cl06 splLogin">LOGIN</div>
                                    <div class="pf_header_cl05 splJoinResp">JOIN</div>     
                                    <div class="pf_header_cl07"><!-- dummy area --></div>                   
                                    <div class="clear"><!-- clear division --></div>
                                </div>

                                <div class="pf_header_cl03" data-toggle="modal" href="#navmenu"><!-- drop down icon --></div>

                                <a href="#" class="pf_logo">
                                    <img  src="templates/tmpl_par/images/splash/top_logo.png" alt="Parentfinder logo" title="Parent finder">
                                </a>
                                <div id="pf_header_id02">connecting adoptive couples, agencies, attorneys and expectant parents</div>
                                <div id="pf_header_id03">
                                    <div class="pf_header_cl01 splJoin">JOIN</div>
                                    <div class="pf_header_cl02 splLogin">LOGIN</div>                    
                                    <div class="clear"><!-- clear division --></div>
                                </div>

                            </header>

                            <div id="pf_banner">
                                <!--<img src="<bx_url_root />templates/tmpl_par/images/splash/splash_img.png">-->
                            </div>

                            <div id="pf_content_id01">
                                <div id="pf_content_id03"><!-- right content area with sub menus-->
                                    <div class="pf_content_menu">
                                        <a href="index.php">PARENTFINDER SERVICES </a>
                                        <a href="adoptive_couples.php">ADOPTIVE FAMILIES</a>
                                        <a href="agencies.php">AGENCIES/ATTORNEYS</a>
                                        <a href="expectant_parents.php" class="last">EXPECTANT PARENTS</a>
                                    </div> 
                                    <div id="pf_content_side"><!-- right side main section-->
                                        <a href="viewourfamilies.php" class="pf_content_side_cl01">VIEW OUR FAMILIES</a>
                                        <div id="pf_side_id01">
                                            <div id="pf_side_id02">
                                                <div class="pf_side_cl02 first">
                                                    <a href="#"><img  src="" alt="images" ></a>
                                                </div>
                                                <div class="pf_side_cl02 second">
                                                    <a href="#"><img  src="" alt="images" ></a>
                                                </div>
                                                <div class="pf_side_cl02 third">
                                                    <a href="#"><img  src="" alt="images" ></a>
                                                </div>
                                                <div class="pf_side_cl02 fourth">
                                                    <a href="#"><img  src="" alt="images" ></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pf_content_cl01">
                                        <a href="http://Parentfinder.com" target="_blank">Parentfinder.com</a> is the most complete Adoption Profile Creation service available. View or create Baby Adoption Profiles, Parent Adoption Profiles, Adoption Agency Profiles, and more. Our staff of birth-moms and adoption professionals work with parents using our Adoption Profile Tools to create a printable, personality driven profile that is rich in story, photos & videos as well as helping expectant parents, agencies, and attorneys view Adoption Profiles or get Adoption Profile help from Adoption Profile Advisers. If you are Adoptive Parents, or a couple looking to adopt, you can easily learn about the Adoption Process, create an Adoption Plan, view Parent Profiles, and even follow the Pregnancy. Why adopt a child? Join our site and help expand our community of loving, adopting families today.
                                    </div>
                                    <div id="pf_footer"><!-- footer area-->

                                        <a target="_blank" href="terms_of_use.php">TERMS</a>
                                        <a target="_blank" href="privacy.php" >PRIVACY</a>
                                        <span> Copyright © 2014 ParentFinder.com</span>

                                    </div>
                                </div>                
                                <div id="pf_content_id02"><!-- left content area with social icons-->
                                    <a href="http://www.facebook.com/parentfinder?ref=ts&fref=ts" class="pf_content_cl02"></a>
                                    <a href="https://twitter.com/ParentFinder10" class="pf_content_cl03"></a>
                                    <a href="https://plus.google.com/104082064684774201396/posts?hl=en" class="pf_content_cl04"></a>
                                    <a href="http://pinterest.com/parentfinder10/" class="pf_content_cl05"></a>
                                    <a href="https://plus.google.com/104082064684774201396/posts?hl=en" class="pf_content_cl06"></a>     
                                </div>                    
                            </div>
                        </div>
                        <div id="side"><!-- responsive container for view our families-->
                            <a href="viewourfamilies.php" class="pf_side_cl01">VIEW OUR FAMILIES</a>
                            <div id="pf_side_id01">
                                <div id="pf_side_id02">
                                    <div class="pf_side_cl02 first">
                                        <a href="#"><img  src="images/img_family.jpg" alt="images" ></a>
                                    </div>
                                    <div class="pf_side_cl02 second">
                                        <a href="#"><img  src="images/img_family.jpg" alt="images" ></a>
                                    </div>
                                    <div class="pf_side_cl02 third">
                                        <a href="#"><img  src="images/img_family.jpg" alt="images" ></a>
                                    </div>
                                    <div class="pf_side_cl02 fourth">
                                        <a href="#"><img  src="images/img_family.jpg" alt="images" ></a>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"><!-- clear division --></div>
                        </div>
                    </div>
                </div>     
                <!-- End home bottom page section -->
            </div>
            <!--            <div class="side">
                    <a id="searchlink" >VIEW OUR FAMILIES</a>
                    <div id="data_container"></div>
                    <div class="clear"></div>
                        </div>-->
            <script type="text/javascript">
        //      SearchComponent.start({   
        //        uid : (new Date()).getTime() ,
        //        application_path : siteurl +"SearchComponent/",
        //        dhtmlx_codebase_path : siteurl +"plugins/dhtmlx/" 
        //      });

        $.ajax({
            url: siteurl + "viewourfamilies/processors/featured_users.php",
            type: "POST",
            cache: false,
            datatype: "json",
            success: function(data) {
                var jd = JSON.parse(data);
                $('.first').html(jd[0].data);
                $('.second').html(jd[1].data);
                $('.third').html(jd[2].data);
                $('.fourth').html(jd[3].data);
            }
        });

            </script>
            <div class="clear"></div>
        </div>
    </div>
</body>
</html>
