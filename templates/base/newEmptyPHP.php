<!--<script type="text/javascript" src="airs/js/dialog.js"></script>-->
<?php

require_once("./userhome/\$ettings.php");

//echo "<link rel=stylesheet type=text/css href=\"".$path["run_time_wwwloc"]."css/dialog_airs.css\">";
$updateFlag = 1;
?>
<style>
    .background_cl02{/*label*/
     position:absolute;
     top:6px;
     left:5px;
}
.form_input  {/*field names font*/
    background-color:#DDE4F2;
    border:1px solid #EDF0F7;
    color:#4B5A83;
    font-family:Arial;
    font-size:12px;
    font-weight:bold;
    height:22px;
}
 #personPopupContainer
{
    position:absolute;
    left:0;
    top:0;
    display:none;
    z-index: 20000;
}
    .tip-title { color:black; font-size:11.4px; margin:0 5px 0 5px; border:solid black 1px;text-align: center;background:#fff; padding: 1px 3px 1px 3px; }
</style>
<SCRIPT language="JavaScript">
<!--
var browserName=navigator.appName;
if (browserName=="Netscape")
{
document.write('<style>.tip { width:auto; height:20px;}</style>');
}
else
{
 if (browserName=="Microsoft Internet Explorer")
 {
  document.write('<style>.tip { width:200px; height:20px;}</style>');

 }
 else
  {
    document.write('<style>.tip { width:200px; height:20px;}</style>');

   }
}
//-->

</SCRIPT>



<script type="text/javascript" src="./auxiliary/js/mootools-1.2.4-core-nc.js"></script>
<script type="text/javascript" src="./auxiliary/js/mootools-1.2.4.4-more.js"></script>
<div class="taskdivID"></div>
<div id="ser_emp_Dialog" style="display: none;">
    <div style="position:absolute;">
    <form id="form_ser_emp" name="form_ser_emp" method="post">
       <input type="hidden" id="section_name_emp" name="section_name" value="user_search_import" />
       <input type="hidden" id="user_contactairsID" name="user_contactairsID" value="" />
       <input type="hidden" id="current_user_type" name="current_user_type" value="<?php echo getCurrentUserType();?>" />



       <?php
       if(getCurrentUserType() == 'admin') {
           $sqlgetAgency        =  "SELECT agency_id, user_id, agency_name, c_account_key FROM user_agencies where c_account_key != ''";
           $rs_agdetails        = mssql_query($sqlgetAgency);

       ?>
        <div class="ser_emp_cl01" style="height:28px;border:1px solid #EDF0F7; border-bottom: 0px; position: relative;vertical-align: middle; ">
            <span  class="form_tag background_cl02" style="width:90px;position: absolute;float:left;text-align: left">Agency:</span>
            <select name="agency_import" id="agency_import" class="form_input" style="position: absolute;margin-left:99px;width:150px;height:20px;top:4px;">
                <option value="" >Please Select</option>
                <?php
                 if(mssql_num_rows($rs_agdetails) > 0)
                 {
                     while($row_agdetails = mssql_fetch_array($rs_agdetails))
                     {
                ?>
                <option value="<?php echo $row_agdetails['c_account_key'] ?>"><?php echo $row_agdetails['agency_name'] ?></option>
                <?php
                     }
                 }
                ?>
            </select>
       </div>

       <?php } ?>

       <div class="ser_emp_cl01" style="height:28px;border:1px solid #EDF0F7; border-bottom: 0px; position: relative;vertical-align: middle; ">
            <span  class="form_tag background_cl02" style="width:90px;position: absolute;float:left;text-align: left">First Name:</span>
            <input type="text" id="emp_ser_name" name="emp_ser_name" value=""  class="form_input" style="position: absolute;margin-left:99px;width:150px;height:20px;top:4px;"/>
       </div>
       <div class="ser_emp_cl01" style="height:28px;border:1px solid #EDF0F7;border-bottom: 0px; position: relative;vertical-align: middle; ">
            <span  class="form_tag background_cl02" style="width:90px;position: absolute;float:left;text-align: left">Last Name:</span>
            <input type="text" id="emp_ser_lname" name="emp_ser_lname" value=""  class="form_input" style="position: absolute;margin-left:99px;width:150px;height:20px;top:4px;"/>
       </div>
       <!--
       <div class="ser_emp_cl01" style="height:28px;border:1px solid #EDF0F7;border-bottom: 0px; position: relative;vertical-align: middle; ">
            <span  class="form_tag background_cl02" style="width:110px;position: absolute;float:left;text-align: left">Business Name:</span>
            <input type="text" id="emp_ser_bname" name="emp_ser_bname" value=""  class="form_input" style="position: absolute;margin-left:99px;width:150px;height:20px;top:4px;"/>
       </div> -->
       <div class="ser_emp_cl01" style="height:41px;border:1px solid #EDF0F7; position: relative;vertical-align: middle; ">
           <div style="position: relative;float:left;">
                <div  class="form_tag background_cl02" style="width:170px;position: absolute;margin-left:0px;text-align: left">No. of Records</div>
                <div  class="form_tag background_cl02" style="width:170px;position: absolute;margin-top:14px;text-align: left">to be Returned:</div>
           </div>
           <div style="position: relative;float:left;">
                <input type="text" id="no_records" name="no_records"  value="" class="form_input" style="position: absolute;margin-left:99px;width:70px;height:20px;margin-top:14px;"/>
                <a href="javascript:void(0)"><img  id="ser_all_emp" border="0" style="position: absolute; margin-left:175px;margin-top:6px;" src="images/emp_search.png"/></a>
           </div>
       </div>
       <div style="height:7px; position: relative; "> </div>
		<div id="emp_container" style="position: relative;width:300px; ">
			<select name="fetch_user"  id="fetch_user" size="4"  style = "width: 330px; height:210px;"></select>
		</div>
		<div style="position: relative;width:300px; height:15px;top:10px"><span id="hideSpan" style="color: red;display: none;">Please refine your search to get more accurate result</span> </div>

       <!--end of Ancestry-->
       <div style="position: relative;width:300px; height:15px;"></div>
       <div style="position:relative;width:100%;height: 30px;top:0px;">
            <a href="javascript:void(0)"><img alt="cancel" id="empl_cancel" title="cancel" border="0" style="position: relative; float:right; right: 5px;" src="auxiliary/tinymce/jscripts/tiny_mce/themes/advanced/img/cancel.png"  onclick="jQuery('#ser_emp_Dialog').dialog('close');"/></a>
            <a href="javascript:void(0)"><img  id="import_user" border="0" style="position: relative;float:right; right: 5px;" src="images/emp_select.png"/></a>
       </div>
     </form>
</div>
    </div>
<!-- end Search EMP -->

<?php
$userSel = $_GET['userTypeSel'];
$cookie_users_userid_usermenu=(getCurrentUserType() == 'agency_user')?$login_social_user_id:$cookie_users_userid;

 require_once($path["serloc"]."users/audit_trail.php");
// require various user functions
if (!isset($_GET['path']) and !isset($_REQUEST['path']) and !isset($_POST['path']))
{
    require_once($path["serloc"]."users/phpfunctions.php");
}

if ($module=='users' or $module=='admin')
{
    // call text language function
    useUserLanguage("admin");
}
else
{
    // call text language function
    useUserLanguage("");
}
require_once($path["serloc"]."tasks/include/config.php");
mysql_connect(TZN_DB_HOST, TZN_DB_USER, TZN_DB_PASS) or die(mysql_error());
mysql_select_db(TZN_DB_BASE) or die(mysql_error());
if($mbr_status == "Delete"){
    $deleteQry = "delete from frk_project where username='$edit_user_id'";
    mysql_query($deleteQry);
}
switch($task)
{
    case "remove" :
    {
        $Data->data = array("photo");
        $Data->where = "user_id='$edit_user_id'";
        $Data->order = "";
        $result = $Data->getData(user_accounts);
        if($myrow = mssql_fetch_row($result))
        {
            if(file_exists($path["serloc"]."userhome/users/$myrow[0]") and $myrow[0] != "")
            {
                unlink($path["serloc"]."userhome/users/$myrow[0]");
            }
        }
        mssql_free_result($result);
        $Data->value = array("");
        $Data->updateData(user_accounts, UPDATE);
        $task = "useredit";
        $sysinfo = "Removed";
    } break;
    case "delete" :
    {
        for($i=0;$i<count($delete_id);$i++)
        {
            $edit_user_id=$delete_id[$i];
            if($userType == 'agency'){
                $deleyeQuery="UPDATE user_accounts SET status = 'Delete' WHERE user_id = '$edit_user_id'";
                mssql_query($deleyeQuery);
            }

            if($userType == 'admin'){

                $Data->where = "un_user_id='$edit_user_id'";
                $Data->deleteData(user_notifications);

                $Data->data = array("photo");
                $Data->where = "user_id='$edit_user_id'";
                $Data->order = "";
                $result = $Data->getData(user_accounts);
                if($myrow = mssql_fetch_row($result))
                {
                    if(file_exists($path["serloc"]."userhome/users/$myrow[0]") and $myrow[0] != "")
                    {
                        unlink($path["serloc"]."userhome/users/$myrow[0]");
                    }
                }
                mssql_free_result($result);
                $Data->deleteData(user_accounts);



                $sysinfo = "Deleted";
                // call add activity count funtion
                userActivities("users", "D");

                //delete document when delete user
                $Data->data = array("file_name");
                $Data->where = "user_id='$edit_user_id'";
                $Data->order = "";
                $result = $Data->getData(upload);
                while($myrow = mssql_fetch_row($result))
                {
                    if(file_exists($path["serloc"]."userhome/users/$myrow[0]") and $myrow[0] != "")
                    {
                        unlink($path["serloc"]."userhome/users/$myrow[0]");
                    }
                }
                mssql_free_result($result);
                $Data->deleteData(upload);
                userActivities("upload_files", "D");

                // delete form data when delete user
                $Data->data = array("form_id", "formname");
                $Data->where = "";
                $Data->order = "";
                $presult=$Data->getData(formmaker_properties);
                while($myprow = mssql_fetch_array($presult))
                {
                    $tablename="formmaker_".strtolower( ($myprow[1]));

                    $Data->data = array("data_id");
                    $Data->where = "user_id='$edit_user_id'";
                    $Data->order = "";
                    $dresult=$Data->getData($tablename);
                    while($mydrow = mssql_fetch_array($dresult))
                    {
                        $temp_data_id = $mydrow[0];

                        $Data->where = "data_id='$temp_data_id' and form_id = '$myprow[0]'";
                        $Data->deleteData(formmaker_submissions);
                    }
                    mssql_free_result($dresult);
                    $Data->where = "user_id='$edit_user_id'";
                    $Data->deleteData($tablename);
                }
                mssql_free_result($presult);

                $Data->where = "user_id='$edit_user_id'";
                $Data->deleteData(formmaker_taskstatus);

                $Data->where = "caseworker_id='$edit_user_id'";
                $Data->deleteData(group_caseworkers);
             }
        }
    } break;
}
if($module=="users" or $module=="admin")
{
        if(userGetUserType($cookie_users_userid_usermenu)=="agency" or userGetUserType($cookie_users_userid_usermenu)=="attorney")
        {
        ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td align="right">
                    <a href="index-mp.php?module=clients">Go to Clients</a>

                </td>
            </tr>
        </table>
         <input type="hidden" name="userSelFilter" id="userSelFilter" value="<?php echo $userSel;?>" />
        <?php
        }

        if (!isset($_GET['path']) and !isset($_REQUEST['path']) and !isset($_POST['path']))
        {
                require($path["serloc"]."users/menu-links.php");
        }
}

if(($module=="users" or $module=="admin") and ($task=="" or $task=="delete"))
{
    $where = $agency_id = "";
    if (userGetUserType($cookie_users_userid_usermenu)!="admin")
    {
        $Data->data = array("agency_id");
        $Data->where = "user_id='$cookie_users_userid_usermenu'";
        $Data->order = "";
        $result = $Data->getData(user_agencies);
        if($myrow = mssql_fetch_row($result))
        {
            $agency_id = $myrow[0];
        }
        if ($agency_id!="")
        {
            $where = "and (user_accounts.agency_group = '$agency_id' or user_accounts.agency_group like '$agency_id,%' or user_accounts.agency_group like '%,$agency_id,%' or user_accounts.agency_group like '%,$agency_id' or user_accounts.user_id = '$cookie_users_userid_usermenu')";
        }
    }

    if (userGetUserType($cookie_users_userid_usermenu)=="admin" or $agency_id!="" or $userType =="agency_user")
    {
        ?>
        <? $base_url = "index-mp.php?module=users&mount=menu-accounts.php"; ?>
        <form id="frmlist" name="frmlist" action="<?=$base_url?>" method="post">
        <input type="hidden" name="task" id="task" value="delete" />
         <?php
		    $u_agent = $_SERVER['HTTP_USER_AGENT'];
		     if(preg_match('/MSIE/i',$u_agent))
		      {
		          $gridWidth    =  'width:973px;';
		      }
		      else
		      {
		          $gridWidth    =  'width:976px;';
		      }
            $sql = "SELECT user_type FROM user_accounts WHERE user_id = '$session_logged_user_id'";
            $resultQuery = mssql_query($sql);

             while($row = mssql_fetch_array($resultQuery))
            {
                $userType = $row['user_type'];
            }
            if($userType == 'admin' || $userType == 'agency' || $userType =="agency_user"){

                 ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td align="right">
                    <?php if($userType != 'agency_user') { ?>
                    <img style="cursor:pointer;" src="images/add_bg_white.png" onclick="window.location='index-mp.php?module=users&mount=menu-accounts.php&task=signup';" />
                    <img id="delete_btn2" style="cursor:pointer;" src="images/delete_bg_white.png" />
                    <?php }?>
                </td>
            </tr>
            </table>
            <div id="gridbox" style="width:974px; height:auto; background-color:white;overflow: hidden;"></div>

           <?php }
           else{
           ?>
            <div id="gridbox" style="width:674px; height:auto; background-color:white; overflow: hidden;"></div>
           <?php } ?>
           <input type="hidden" id="login_user_type" name="login_user_type" value="<?php echo $userType;?>"/>
        <table width="100%" border="0" cellspacing="0" cellpadding="0px">
            <tr>
                <td>
                   <?php if($userType == 'admin' || $userType == 'agency' || $userType =="agency_user"){

                 ?>
                    <div style="<?=$gridWidth?>left:1px;" id="pagingArea"></div>
                  <?php } else{?>
                     <div style="width:674px;" id="pagingArea"></div>
                    <?php } ?>


                    <div id="recinfoArea"></div>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <?php if($userType != 'agency_user') { ?>
                    <img style="cursor:pointer;" src="images/add_bg_white.png" onclick="window.location='index-mp.php?module=users&mount=menu-accounts.php&task=signup';" />
                    <img id="delete_btn" style="cursor:pointer;" src="images/delete_bg_white.png" />
                    <?php }?>
                </td>
            </tr>
        </table>
        </form>
        <script language="javascript" type="text/javascript">
        var browserType=navigator.appName;
            mygrid = new dhtmlXGridObject("gridbox");
            mygrid.setImagePath("auxiliary/codebase/imgs/");
            mygrid.setSkin("dhx_skyblue");
            mygrid.submitOnlyChanged(false);
            mygrid.enableAutoHeight(true);
            mygrid.setMultiLine(true);
            mygrid.enableMultiline(true);

            mygrid.attachEvent("onBeforeSelect", function(new_row,old_row)
            {
                return false;
            });

            mygrid.enableMultiline(true);
            //mygrid.enableDragOrder(true);
            //mygrid.enableColumnMove(true);

			mygrid.attachEvent("onXLE",afterLoad);
            mygrid.init();
            if(jQuery("#login_user_type").val() == 'agency_user')
            {
                mygrid.loadXML("xmls/users/cw_accounts.php", function() {
                mygrid.setSortImgState(true, 2, "asc", 1);
                AddListCheckboxEvent();
                });
            }
            else
            {
                mygrid.loadXML("xmls/users/accounts.php", function() {
                mygrid.setSortImgState(true, 2, "asc", 1);
                AddListCheckboxEvent();
                });
            }

            mygrid.enablePaging(true, 100, 5,"pagingArea", true, "recinfoArea");
            mygrid.setPagingSkin("toolbar", "dhx_skyblue");
            jQuery(document).ready(function() {
            var popup_height;
            if (browserType=="Microsoft Internet Explorer")
                {
                 popup_height = 165;
                }
                else
                {
                popup_height = 100;
                }
        jQuery("#confirm_dialog").dialog(
            {
                bgiframe: true,
                width: 412,
                height: popup_height,
                modal: true,
                autoOpen: false,
                draggable: true,
                resizable: false,
                close: function(event, ui) {  }
            }
        );

        jQuery("#btn_Close").click(function() {
            jQuery("#confirm_dialog").dialog("close");
        });
        jQuery("#btn_Delete").click(function() {
           jQuery('#frmlist').submit();
        });
        }
        );

        function afterLoad()
        {
         	setTimeout(showFilter,10);
        }

        function showFilter()
        {
        	var userSelFil        = jQuery('#userSelFilter').val();
            mygrid.getFilterElement(4).value = userSelFil;
            mygrid.filterByAll();
        }
        </script>
        <div id="confirm_dialog" title="Delete Confirmation" style="display: none;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="vertical-align:text-bottom;">
                    <tr><td style="font:Verdana, Arial, Helvetica, sans-serif; font-size:12px;">Do you want to delete the record?</td></tr>
                <tr>
                     <td align="right" style="padding-bottom:0px;">

                        <img id="btn_Delete" src="images/delete_bg_white.png" style="cursor:pointer;" />
                        <img id="btn_Close" src="images/cancel_bg_white.png" style="cursor:pointer;" />
                    </td>
                </tr>
            </table>
        </div>
        <?php
    } //end if (userGetUserType($cookie_users_userid_usermenu)=="admin" or $agency_id!="")
    else
    {
        if(getCurrentUserType() == "agency" || getCurrentUserType() == "attorney")
        {
        ?>
        <table border=0 cellpadding=2 cellspacing=0 width=100% align=center>
        <tr><td>Please edit your Agency Profile before you can access this page.</td></tr>
        </table><br>
        <?php
        }
    }
}
if($mount!='menu-manageaffiliates.php')
{
if ($task == "add" or $task == "addinfo" or $infotask == "add")
{
    if(strtolower($typedcaptcha) != $_SESSION["securitycode"] and strtolower($typedcaptcha) != $storedcaptcha)
    {
        if ($infotask == "add")
        {
            $task = "infoedit";
        }
        else
        {
            $task = "signup";
        }
        echo "<script language=javascript>alert('Please check the verification code and try again...')</script>";
    }
}
}
if($task!="" and $task!="delete")
{
        ?>
        <table border=0 cellpadding=2 cellspacing=0 width=675 align=center>
        <?php

        switch ($task)
        {
                case "checkUsed" :
                {
                    $Data->data = array("username");
                    $Data->where = "username='$mbr_email'";
                    $Data->order = "";
                    $result=$Data->getData(user_accounts);
                    if($myrow=mssql_fetch_row($result))
                    {
                            $msgAvail = No;
                    }
                    else
                    {
                            $msgAvail = Yes;
                    }
                    mssql_free_result($result);
                    $task = "signup";
                } break;
                case "add" :
                case "addinfo" :
                {
                    $mbr_photo = strtolower(str_replace(" ", "_",trim($_FILES['mbr_photo']['name'])));

                    $groups_cw	=	$mbr_usergroup;
                    $messageGroup_cw	=	$mbr_messagegroup;

                    if ($mbr_photo)
                    {   // get the extension format
                        $extension = substr($mbr_photo, -4);
                        if ($extension == ".jpg" or $extension == "jpeg" or $extension == ".gif")
                        {
                            $exts     = split("[/\\.]", $mbr_photo) ;
                            $dotcount = count($exts)-1;
                            $exts     = $exts[$dotcount];
                            list($imageName, $imageExt)   = split('.'.$exts, $mbr_photo);
                            $orgpath          = $imageName.".".$exts;
                            $Data->uploadFile($_FILES['mbr_photo']['tmp_name'], $path["serloc"]."userhome/users", $orgpath);

                            $Data->resizeImage($path["serloc"]."userhome/users/".$orgpath, $path["serloc"]."userhome/users/".$mbr_photo, 125, 125, 100, 20);
                        }
                        else if ($extension == ".png" or $extension == ".PNG")
                        {
                            $exts     = split("[/\\.]", $mbr_photo) ;
                            $dotcount = count($exts)-1;
                            $exts     = $exts[$dotcount];
                            list($imageName, $imageExt)   = split('.'.$exts, $mbr_photo);
                            $orgpath          = $imageName."_org.".$exts;
                            $Data->uploadFile($_FILES['mbr_photo']['tmp_name'], $path["serloc"]."userhome/users", $orgpath);
                            $im=imagecreatefrompng($path["serloc"]."userhome/users/".$orgpath);
                            $width=imagesx($im);              // Original picture width is stored
                            $height=imagesy($im);             // Original picture height is stored
                            $newimage=imagecreatetruecolor(100,125);
                            imagecopyresized($newimage,$im,0,0,0,0,125,125,$width,$height);
                            imagepng($newimage, $path["serloc"]."userhome/users/".$mbr_photo);
                        }
                        else
                        {
                            $sysinfo = "<font color=red>Invalid Format!</font><br>";
                            $task = "signup";
                        }
                    }
                    if (empty($sysinfo))
                    {
                        $Data->data = array("username");
                        $Data->where = "username='$mbr_email'";
                        $Data->order = "";
                        $result=$Data->getData(user_accounts);
                        if((!$myrow=mssql_fetch_row($result)) ||($mbr_usertype == 'child'))
                        {
                                if(($module == "users" and userGetUserType($cookie_users_userid_usermenu)=="admin") or $module == "admin")
                                {
                                    $newpermissions = "";
                                    for($i=0; $i<count($mbr_permissions); $i++)
                                    {
                                            $newpermissions .= $mbr_permissions[$i].", ";
                                    }
                                    $mbr_permission .= $newpermissions;
                                }
                                else
                                {
                                    $mbr_permission = "affiliatewiz, autoezine, blogwriter, classifiedads, customshop, directory, eventscalendar, faqmanager, formmaker, forum, gamesroom, groups, helpdesk, inboxmessenger, jobboard, linkshortener, livechat, mediaalbum, myamazon, onlinestore, publisher, sitebuilder, surveypro, textads";
                                    $mbr_membership = 1;
                                    $mbr_status = "Active";

                                    $Data->data = array("membership_id");
                                    $Data->where = "membership_id='$mbr_membership'";
                                    $Data->order = "";
                                    $result = $Data->getData(user_memberships);
                                    if(mssql_num_rows($result) == 0)
                                    {
                                        $Data->data = array("membership_id");
                                        $Data->where = "package='None'";
                                        $Data->order = "";
                                        $result2 = $Data->getData(user_memberships);
                                        if(mssql_num_rows($result2) != 0)
                                        {
                                            while($myrow2=mssql_fetch_row($result2))
                                            {
                                                $mbr_membership = $myrow2[0];
                                            }
                                            mssql_free_result($result2);
                                        }
                                        else
                                        {
                                            $Data->data = array("membership_id", "package");
                                            $Data->value = array(0, "None");
                                            $Data->modname = "users";
                                            $Data->modversion = $System->modversion;
                                            $Data->modkey = "membership_id";
                                            $Data->updateData(user_memberships,"INSERT");
                                            $mbr_membership = $Data->getLastID(user_memberships, membership_id);
                                        }
                                    }
                                    mssql_free_result($result);

                                    $Data->data = array("signup_approval", "contacted");
                                    $Data->where = "membership_id='$mbr_membership'";
                                    $Data->order = "";
                                    $result = $Data->getData(user_memberships);
                                    if ($myrow = mssql_fetch_row($result))
                                    {
                                        if ($myrow[0] == "M")
                                        {
                                            //$mbr_status = "Inactive";
                                        }
                                        $contacted = $myrow[1];
                                    }
                                    mssql_free_result($result);

                                    $mbr_suspenduntil = date("Y-m-d");
                                    $Data->data = array("subscription", "recurring", "length");
                                    $Data->where = "membership_id='$mbr_membership'";
                                    $Data->order = "";
                                    $result = $Data->getData(user_memberships);
                                    if ($myrow = mssql_fetch_row($result))
                                    {
                                        if ($myrow[0] == "S")
                                        {
                                            switch ($myrow[1])
                                            {
                                                case "D": $mbr_suspenduntil = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"))); break;
                                                case "W": $mbr_suspenduntil = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 7, date("Y"))); break;
                                                case "M": $mbr_suspenduntil = date("Y-m-d", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y"))); break;
                                                case "Y": $mbr_suspenduntil = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y") + 1)); break;
                                            }
                                        }
                                        else
                                        {
                                            if ($myrow[2] > 0)
                                            {
                                                if (substr($myrow[2], -1) == "D")
                                                {
                                                    $mbr_suspenduntil = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + substr($myrow[2], 0, -1), date("Y")));
                                                }
                                                else
                                                {
                                                    $mbr_suspenduntil = date("Y-m-d", mktime(0, 0, 0, date("m") + substr($myrow[2], 0, -1), date("d"), date("Y")));
                                                }
                                            }
                                            else
                                            {
                                                $mbr_suspenduntil = "";
                                            }
                                        }
                                    }
                                    mssql_free_result($result);
                                }
                                /*
                                if ($creditcart)
                                {
                                    $creditcart = $crypt->encrypt($mbr_email, $creditcart);
                                }
                                if ($expiredate)
                                {
                                    $date = explode("-", $expiredate);
                                    switch(str_replace("%", "", $System->useSetting("general_dateformat")))
                                    {
                                        case "m-d-Y" : $expiredate = $date[2]."-".$date[0]."-".$date[1]; break;
                                        case "d-m-Y" : $expiredate = $date[2]."-".$date[1]."-".$date[0]; break;
                                    }
                                }
                                else
                                {
                                    $expiredate = "0000-00-00";
                                }
                                //if ($System->useSetting("signup_emailconfirmation") == "Required")
                                //{
                                    //$mbr_status = "Inactive";
                                //}
if ($mbr_showprofile == "")
{
    $mbr_showprofile = substr($System->useSetting("userprofile_display"), 0, 1);
}*/
                                //$Data->data = array("user_id", "username", "password", "first_name", "last_name", "email", "photo", "organization", "title", "phone", "address1", "address2", "city", "state", "zipcode", "country", "website", "mobile_number", "membership", "status", "suspend_until", "timezone", "datejoined", "itemized_date");
                                //$Data->value = array(0,addslashes($mbr_email),sha1(addslashes($mbr_email).addslashes($mbr_password)),addslashes($mbr_firstname),addslashes($mbr_lastname),addslashes($mbr_email),$mbr_photo,addslashes($mbr_organization),addslashes($mbr_title),addslashes($mbr_phone),addslashes($mbr_address1),addslashes($mbr_address2),addslashes($mbr_city),addslashes($mbr_state),addslashes($mbr_zipcode),addslashes($mbr_country),addslashes($mbr_website),addslashes($mbr_mobilenumber), $mbr_membership, addslashes($mbr_status), $mbr_suspenduntil, addslashes($mbr_timezone), date("Y-m-d"), date("Y-m-d"));
                                /* Job Portal
                                // add company - user company name
                                // add jobboard_user - Job Seeker or Recruiter
                                $Data->data = array("user_id", "username", "password", "first_name", "last_name", "email", "photo", "organization", "title", "phone", "address1", "address2", "city", "state", "zipcode", "country", "website", "mobile_number", "permission", "membership", "status", "credit_card", "date_expired", "suspend_until", "timezone", "datejoined", "listinfo", "hearaboutus", "contacted", "showprofile", "company", "jobboard_user", "itemized_date");
                                $Data->value = array(0,addslashes($mbr_email),sha1(addslashes($mbr_email).addslashes($mbr_password)),addslashes($mbr_firstname),addslashes($mbr_lastname),addslashes($mbr_email),$mbr_photo,addslashes($mbr_organization),addslashes($mbr_title),addslashes($mbr_phone),addslashes($mbr_address1),addslashes($mbr_address2),addslashes($mbr_city),addslashes($mbr_state),addslashes($mbr_zipcode),addslashes($mbr_country),addslashes($mbr_website),addslashes($mbr_mobilenumber),$mbr_permission, $mbr_membership, addslashes($mbr_status), $creditcart, $expiredate, $mbr_suspenduntil, addslashes($mbr_timezone), date("Y-m-d"), "Y-N-N-N-N-N-N-N-Y-Y-Y-Y-Y-N-Y-Y-Y-Y-Y", addslashes($mbr_hearaboutus), $contacted, $mbr_showprofile, addslashes($mbr_company), $mbr_jobboard_user, date("Y-m-d"));
                                */
                                /*
                                $Data->data = array("user_id", "username", "password", "first_name", "last_name", "email", "photo", "organization", "title", "phone", "address1", "address2", "city", "state", "zipcode", "country", "website", "mobile_provider", "mobile_number", "icq", "gtalk", "msn", "yahoo", "skype", "permission", "membership", "status", "credit_card", "date_expired", "suspend_until", "timezone", "datejoined", "listinfo");
                                $Data->value = array(0,addslashes($mbr_email),sha1(addslashes($mbr_email).addslashes($mbr_password)),addslashes($mbr_firstname),addslashes($mbr_lastname),addslashes($mbr_email),$mbr_photo,addslashes($mbr_organization),addslashes($mbr_title),addslashes($mbr_phone),addslashes($mbr_address1),addslashes($mbr_address2),addslashes($mbr_city),addslashes($mbr_state),addslashes($mbr_zipcode),addslashes($mbr_country),addslashes($mbr_website),addslashes($mbr_mobileprovider),addslashes($mbr_mobilenumber),addslashes($mbr_icq),addslashes($mbr_gtalk),addslashes($mbr_msn),addslashes($mbr_yahoo),addslashes($mbr_skype), $mbr_permission, $mbr_membership, addslashes($mbr_status), $creditcart, $expiredate, $mbr_suspenduntil, addslashes($mbr_timezone), date("Y-m-d"), "Y-Y-N-Y-Y-N-N-N-N-N-Y-N-Y-N-N-N-N-N-N-N-Y-Y-Y-Y-Y");
                                */
                                // set user agency group
                                if (userGetUserType($cookie_users_userid_usermenu)=="admin")
                                {
                                    $agency_group = "";
                                    for($i=0;$i<count($mbr_agencygroup);$i++)
                                    {
                                        if ($i!=0)
                                        {
                                            $agency_group .=",";
                                        }
                                        $agency_group .= $mbr_agencygroup[$i];
                                    }
                                }
                                else
                                {
                                    $agency_group = $mbr_agencygroup;
                                }

                                //set user agency user group
                                $group_id = "";
                                 $messageGroup_id = "";
                                if (userGetUserType($cookie_users_userid_usermenu)=="admin" )
                                {
                                    $group_id = $mbr_user_group;
                                    $messageGroup_id = $mbr_user_message_group;
                                }
                                else
                                {

                                	$group_id 	=	implode(',',$mbr_usergroup);
                                        $messageGroup_id 	=	implode(',',$mbr_messagegroup);
                                        if(count($mbr_messagegroup) > 0)
                                        {
                                            $messageGroup_id = @implode(",", $mbr_messagegroup);
                                        }
                                }
                                if($mbr_usertype == 'agency_user'){
                                	//$group_id = "";
                                         $group_id 	=	implode(',',$mbr_usergroup);
                                         $messageGroup_id = "";
                                         if(count($mbr_messagegroup) > 0)
                                        {
                                            $messageGroup_id = @implode(",", $mbr_messagegroup);
                                        }
                                }
                                 //echo $messageGroup_id;
                                // exit();
                                $documenttogroups	=	($documenttogroups == "Y")?"Y":"N";
                        		$documenttousers	=	($documenttousers == "Y")?"Y":"N";
                                        if($mbr_status == 'Pending'){
                                        $mbr_status = 'Active';
                                        $mbr_status_mode = 'Pending';
                                        }
                                        elseif($mbr_status == 'Closed'){
                                          $mbr_status = 'Active';
                                        $mbr_status_mode = 'Closed';
                                        }
                                        else{
                                           $mbr_status_mode =$mbr_status;
                                        }

                                /*$add_columns = array("username", "password", "first_name", "last_name", "email","organization", "title", "website", "photo", "phone", "address1", "address2", "city", "state", "zipcode", "country", "personal_gender", "mobile_number", "membership", "status", "suspend_until", "timezone", "datejoined", "itemized_date", "user_type", "queation", "answer", "message_alert", "agency_group", "group_id","message_group", "case_worker", "spouse_first_name", "spouse_last_name","spouse_gender", "mailtogroups", "airs_contact_id", "doctogroups", "doctousers","status_mode");
                                $add_values = array(($mbr_email),sha1(($mbr_email).($mbr_password)),($mbr_firstname),($mbr_lastname),($mbr_email),($mbr_organization),($mbr_title),($mbr_website),$mbr_photo,($mbr_phone),($mbr_address1),($mbr_address2),($mbr_city),($mbr_state),($mbr_zipcode),($mbr_country),($mbr_gender),($mbr_mobilenumber), $mbr_membership, ($mbr_status), "CAST('$mbr_suspenduntil' AS smalldatetime)", ($mbr_timezone), "CAST('".date("Y-m-d")."' AS smalldatetime)", "CAST('".date("Y-m-d")."' AS smalldatetime)", ($mbr_usertype), ($mbr_question), ($mbr_answer), "On", $agency_group, $group_id,$messageGroup_id, ($case_worker?$case_worker:0), ($spouse_first_name), ($spouse_last_name), ($spouse_gender),($mailtogroups),$airs_contact_id?$airs_contact_id:null, ($documenttogroups), ($documenttousers), ($mbr_status_mode)); */

                                $add_columns = array("username", "password", "first_name", "last_name", "email","organization", "title", "website", "photo", "phone", "address1", "address2", "city", "state", "zipcode", "country", "personal_gender", "mobile_number", "membership", "status", "suspend_until", "timezone", "datejoined", "itemized_date", "user_type", "queation", "answer", "message_alert", "agency_group", "group_id","message_group", "case_worker", "spouse_first_name", "spouse_last_name","spouse_gender", "mailtogroups", "airs_contact_id", "doctogroups", "doctousers","status_mode","edd","id_type","id_number", "new_encryption");
                                //$add_values = array(($mbr_email),sha1(($mbr_email).($mbr_password)),($mbr_firstname),($mbr_lastname),($mbr_email),($mbr_organization),($mbr_title),($mbr_website),$mbr_photo,($mbr_phone),($mbr_address1),($mbr_address2),($mbr_city),($mbr_state),($mbr_zipcode),($mbr_country),($mbr_gender),($mbr_mobilenumber), $mbr_membership, ($mbr_status), "CAST('$mbr_suspenduntil' AS smalldatetime)", ($mbr_timezone), "CAST('".date("Y-m-d")."' AS smalldatetime)", "CAST('".date("Y-m-d")."' AS smalldatetime)", ($mbr_usertype), ($mbr_question), ($mbr_answer), "On", $agency_group, $group_id,$messageGroup_id, ($case_worker?$case_worker:0), ($spouse_first_name), ($spouse_last_name), ($spouse_gender),($mailtogroups),$airs_contact_id?$airs_contact_id:null, ($documenttogroups), ($documenttousers), ($mbr_status_mode), ($un_edd_dt), ($idType),  ($un_id_no));
                                if ($mbr_usertype == 'child') {
                                    $usernameunique = md5($mbr_firstname.date("Y-m-d H:i:s"));
                                    $usernameunique = $usernameunique.'@child.com';
                                    $add_values = array(($usernameunique),pwdencryption(($mbr_email).($mbr_password)),($mbr_firstname),($mbr_lastname),($usernameunique),($mbr_organization),($mbr_title),($mbr_website),$mbr_photo,($mbr_phone),($mbr_address1),($mbr_address2),($mbr_city),($mbr_state),($mbr_zipcode),($mbr_country),($mbr_gender),($mbr_mobilenumber), $mbr_membership, ($mbr_status), "CAST('$mbr_suspenduntil' AS smalldatetime)", ($mbr_timezone), "CAST('".date("Y-m-d H:i:s")."' AS smalldatetime)", "CAST('".date("Y-m-d")."' AS smalldatetime)", ($mbr_usertype), ($mbr_question), $mbr_answer, "On", $agency_group, $group_id,$messageGroup_id, ($case_worker?$case_worker:0), ($spouse_first_name), ($spouse_last_name), ($spouse_gender),($mailtogroups),$airs_contact_id?$airs_contact_id:null, ($documenttogroups), ($documenttousers), ($mbr_status_mode), ($un_edd_dt), ($idType),  ($un_id_no), "Y"); }
                                else {
                                $add_values = array(($mbr_email),pwdencryption(($mbr_email).($mbr_password)),($mbr_firstname),($mbr_lastname),($mbr_email),($mbr_organization),($mbr_title),($mbr_website),$mbr_photo,($mbr_phone),($mbr_address1),($mbr_address2),($mbr_city),($mbr_state),($mbr_zipcode),($mbr_country),($mbr_gender),($mbr_mobilenumber), $mbr_membership, ($mbr_status), "CAST('$mbr_suspenduntil' AS smalldatetime)", ($mbr_timezone), "CAST('".date("Y-m-d H:i:s")."' AS smalldatetime)", "CAST('".date("Y-m-d")."' AS smalldatetime)", ($mbr_usertype), ($mbr_question), $mbr_answer, "On", $agency_group, $group_id,$messageGroup_id, ($case_worker?$case_worker:0), ($spouse_first_name), ($spouse_last_name), ($spouse_gender),($mailtogroups),$airs_contact_id?$airs_contact_id:null, ($documenttogroups), ($documenttousers), ($mbr_status_mode), ($un_edd_dt), ($idType),  ($un_id_no), "Y"); }

                                if($mbr_usertype == 'agency_user')
                                {
                                    $add_columns[] = "case_worker_parent_user_id";
                                    $add_values[] = $login_social_user_id;
                                }


                                $Data->columns = $add_columns;
                                $Data->values = $add_values;
                                $Data->modname = "users";
                                $Data->modversion = $System->modversion;
                                $Data->modkey = "user_id";
                                $Data->updateData(user_accounts,"INSERT");
                                $edit_user_id = $Data->getLastID(user_accounts, user_id);


                                /********************************************************************************
                                 *      updated Date            :   06/08/2011                                  *
                                 *      updated by              :   ratheeshkumar p c                           *
                                 *      purpose                 :   to enter user effective date                *
                                 *                                                                              *
                                 ********************************************************************************/


                                $lastUserID                           =    $Data->getLastID(user_accounts, user_id);


                                $groupCount                           =     count($mbr_usergroup);
                                for($i=0; $i <=($groupCount-1); $i++ ){
                                    $date = date('Y-m-d');
                                    $user_group_E =  $mbr_usergroup[$i];
                                    $insertUserEffectiveDate        =   "insert into user_effectiveDate  ([user_id],[group_id],[effectiveDate],[eff_status])
                                    											values('$lastUserID', '$user_group_E', '$date','Y')";

                                    mssql_query($insertUserEffectiveDate);

                                }

                                /* end of updation done for user effective date. */
//exit();



		                        /*if($mbr_usertype == 'agency'){
		                        	$agencyIDs		=	mssql_query("select agency_id from user_agencies where user_id = '$edit_user_id'");
		                        	if(mssql_num_rows($agencyIDs) > 0){
							        	$zoneagencyID   =	mssql_fetch_row($agencyIDs);
								        $newValue 		= 	$zoneagencyID[0].'agencydefaulttimezone';
							            $insertQuery = "INSERT INTO  system_settings (useroption, setting) VALUES ('$newValue','$mbr_timezone')";
							            mssql_query($insertQuery);
							        }

		                        }*/


                                $temp_client_id = $_POST['caseworker'];
                                for ($m=0; $m< count($temp_client_id); $m++)
                                {
                                    mssql_query("UPDATE user_accounts SET case_worker = '$edit_user_id' WHERE agency_group = '$agency_group' and user_id='".$temp_client_id[$m]."'");
                                    mssql_query("INSERT into caseworker_client(parentid,caseworkerid) values('$edit_user_id','$temp_client_id[$m]')");
                                }

                                if($mbr_usertype == 'agency_user'){
                                	// echo count($groups_cw);
	                                for ($m=0; $m< count($groups_cw); $m++)
	                                {
	                                   mssql_query("INSERT into group_caseworkers(group_id,caseworker_id) values('$groups_cw[$m]','$edit_user_id')");
	                                }
                                    $caseIDFullVal      = $edit_user_id."_cw_cwsecurity";
                                    $sql_cw_linsert     = "INSERT INTO system_settings (useroption, setting) VALUES ('".$caseIDFullVal."','".$caseSecurity."')";
                                    $result_data        = mssql_query($sql_cw_linsert);

                                    $caseIDFullValFin   = $edit_user_id."_cw_cwsecurityfinancial";
                                    $sql_cw_linsertfin  = "INSERT INTO system_settings (useroption, setting) VALUES ('".$caseIDFullValFin."','".$financial."')";
                                    $result_data        = mssql_query($sql_cw_linsertfin);

                                    $caseIDFullValForm  = $edit_user_id."_cw_userforms";
                                    $sql_cw_linsertorm  = "INSERT INTO system_settings (useroption, setting) VALUES ('".$caseIDFullValForm."','".$caseuserforms."')";
                                    $result_data        = mssql_query($sql_cw_linsertorm);

                                    $caseIDFullValMatch  = $edit_user_id."_cw_matching";
                                    $sql_cw_linsertmat   = "INSERT INTO system_settings (useroption, setting) VALUES ('".$caseIDFullValMatch."','".$cwMatching."')";
                                    $result_data         = mssql_query($sql_cw_linsertmat);
                                }

                                // MAPSystemData
                                if ($agency_group!="")
                                {
                                    $Data->data = array("c_account_key");
                                    $Data->where = "agency_id='$agency_group'";
                                    $Data->order = "";
                                    $aresult = $Data->getData(user_agencies);
                                    if($myarow = mssql_fetch_row($aresult))
                                    {
                                        $c_account_key = $myarow[0];
                                    }
                                }
                                mssql_free_result($aresult);

                                $Data->columns = array("User_Id", "AP1Fname", "AP1Lname", "Agency", "AP2Fname", "AP2Lname", "Add1", "Add2", "City", "State", "Zip", "Country", "HomePhone", "AP1CellPhone", "AP1Email", "CaseWorkerId","AP1Gender","AP2Gender");
                                $Data->values = array(($edit_user_id?$edit_user_id:0), ($mbr_firstname), ($mbr_lastname), $c_account_key, ($spouse_first_name), ($spouse_last_name), ($mbr_address1), ($mbr_address2), ($mbr_city),($mbr_state), ($mbr_zipcode), ($mbr_country), ($mbr_phone), ($mbr_mobilenumber), ($mbr_email), ($case_worker?$case_worker:0),($mbr_gender),($spouse_gender));
                                $Data->where = "";
                                $Data->modname = "users";
                                $Data->modversion = $System->modversion;
                                $Data->modkey = "MAPSystemDataId";
                                $Data->updateData(MAPSystemData,"INSERT");
                                mssql_query("EXEC sp_GroupRoleAddEdit '$edit_user_id'");


                                //case worker and clients assignment
                                if($mbr_usertype == 'agency_user')
                                {
                                    if($agency_group != "")
                                    {
                                        if (userGetUserType($cookie_users_userid_usermenu)=="admin")
                                        {
                                            $temp_client_id = explode(",",$mbr_ap_id);
                                            for ($m=0; $m< count($temp_client_id); $m++)
                                            {
                                                mssql_query("UPDATE user_accounts SET case_worker = '$edit_user_id' WHERE agency_group = '$agency_group' and user_id='".$temp_client_id[$m]."'");
												  mssql_query("INSERT into caseworker_client(parentid,caseworkerid) values('$temp_client_id[$m]','$edit_user_id')");
                                            }

                                        }
                                        else
                                        {
                                            for ($m=0; $m< count($mbr_client); $m++)
                                            {
                                                mssql_query("UPDATE user_accounts SET case_worker = '$edit_user_id' WHERE agency_group = '$agency_group' and user_id='".$mbr_client[$m]."'");
												mssql_query("INSERT into caseworker_client(parentid,caseworkerid) values('$mbr_client[$m]','$edit_user_id')");
                                            }

                                        }
                                    }
                                    //send notification to caseworker
                                    if(count($mbr_client))
                                		notifyCWonClientAssign($edit_user_id,$mbr_client);

                                }
								if($mbr_usertype == 'adoptive_parent' || $mbr_usertype == 'birth_parent'){
									//send notification to caseworkers
									//$temp_client_id = $_POST['caseworker'];
									if(count($temp_client_id))
                                		notifyCWonClientAssign($temp_client_id,$edit_user_id);
								}
                                //agency assignment
                                if($mbr_usertype == 'agency' or $mbr_usertype == 'attorney')
                                {
                                    if($agency_assignment != '0')
                                    {
                                        mssql_query("UPDATE user_agencies SET user_id = '$edit_user_id' WHERE agency_id = '$agency_assignment'");
                                    }
                                }

                                //notification insert
                                $notificationEmail      =   ($un_email_id)?$un_email_id:($mbr_email);
                                $notificationEmailFlg   =   ($un_notify_email)?$un_notify_email:'1';

                                mssql_query("INSERT INTO user_notifications (un_user_id, un_email_id, un_notify_email, un_notify_sms, un_phone_number, un_carrier_id) VALUES
                                                ('$edit_user_id', '$notificationEmail', '$notificationEmailFlg', '$un_notify_sms', '$un_phone_number', '$un_carrier_id')");


                                if($module == "users" or $module == "admin")
                                {
                                    if (userGetUserType($cookie_users_userid_usermenu)=="admin")
                                    {
                                        for($i=0;$i<count($mbr_agencygroup);$i++)
                                        {
                                            if (!empty($edit_user_id) and !empty($mbr_agencygroup[$i]))
                                            {

                                                $Data->columns = array("user_id", "agency_id", "case_worker", "inquires_date", "join_date", "status");
                                                $Data->values = array($edit_user_id,$mbr_agencygroup[$i],0, "CAST('".date("Y-m-d")."' AS smalldatetime)", "CAST('".date("Y-m-d")."' AS smalldatetime)", "confirm");
                                                $Data->modname = "users";
                                                $Data->modversion = $System->modversion;
                                                $Data->modkey = "inquires_id";
                                                $Data->updateData(user_inquires,"INSERT");
                                            }
                                        }
                                    }
                                    else
                                    {
                                        if (!empty($edit_user_id) and !empty($mbr_agencygroup))
                                        {

                                            $Data->columns = array("user_id", "agency_id", "case_worker", "inquires_date", "join_date", "status");
                                            $Data->values = array($edit_user_id,$mbr_agencygroup,0, "CAST('".date("Y-m-d")."' AS smalldatetime)", "CAST('".date("Y-m-d")."' AS smalldatetime)", "confirm");
                                            $Data->modname = "users";
                                            $Data->modversion = $System->modversion;
                                            $Data->modkey = "inquires_id";
                                            $Data->updateData(user_inquires,"INSERT");
                                        }
                                    }
                                }

                                // call add activity count funtion
                                userActivities("users", "A");
                                if($module != "users" and $module != "admin")
                                {
                                    $agencyIDs = $_SESSION['agencyId'];
                                    $currentAgId = $_SESSION['__current_agency_id'];

                                    if($agencyIDs != ""){
                                         $agid = (string)$agencyIDs;
                                    }

                                    else{
                                        $agid = (string)$currentAgId;
                                    }

                                    if($agid == '' || $agid == '0')
                                    {
                                        $userActivAlert = "user_activationalert";
                                        $userActivSub = "user_activationsubject";
                                        $userActivMsg = "user_activationmessage";
                                        $userSignupAlert = "user_signupalert";
                                        $userSignupSub = "user_signupsubject";
                                        $userSignupMsg = "user_signupmessage";
                                        $userSupportName = "user_sendsupportname";
                                        $userSupportEmail = "user_sendsupportemail";
                                        $userEmailAlert = "user_emailalert";
                                        $userMobileAlert = "user_mobilealert";
                                    }

                                    else{
                                        $userActivAlert = $agid."user_activationalert";
                                        $userActivSub = $agid."user_activationsubject";
                                        $userActivMsg = $agid."user_activationmessage";
                                        $userSignupAlert = $agid."user_signupalert";
                                        $userSignupSub = $agid."user_signupsubject";
                                        $userSignupMsg = $agid."user_signupmessage";
                                        $userSupportName = $agid."user_sendsupportname";
                                        $userSupportEmail = $agid."user_sendsupportemail";
                                        $userEmailAlert = $agid."user_emailalert";
                                        $userMobileAlert = $agid."user_mobilealert";
                                    }

                                    if($mount!='menu-manageaffiliates.php')
                                    {
                                    //if ($System->useSetting("signup_emailconfirmation") == "Required")
                                    if ($System->useSetting($userActivAlert) == "Yes")
                                    {
                                        $sendsignupemail = $System->useSetting($userActivAlert);
                                        $confirm_id = "";
                                        $temp_id = $Data->getLastID(user_accounts, user_id)."---".$mbr_email;
                                        for ($i = 0; $i < strlen($temp_id); ++ $i)
                                        {
                                            $ord_id = ord(substr($temp_id, $i, 1));
                                            if ($ord_id < 100)
                                            {
                                                $ord_id = "0".$ord_id;
                                            }
                                            $confirm_id .= $ord_id;
                                        }


                                        $subject =  ($System->useSetting($userActivSub));
                                        $message =  ($System->useSetting($userActivMsg));




                                        if ($module == "")
                                        {
                                            unset($module);
                                        }

                                        if(strpos($path["urlloc"].$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"]."_", $module."/") == TRUE)
                                        {
                                            if ($user_login_success == "No")
                                            {
                                                $message = str_replace("[[activationlink]]", "<a href=\"".$path["run_time_wwwloc"]."index.php?module=$module&pluginoption=usersconfirmation&confirm_id=".strrev($confirm_id)."\">".$path["run_time_wwwloc"]."index.php?module=$module&pluginoption=usersconfirmation&confirm_id=".strrev($confirm_id)."</a>", $message);
                                            }
                                            else
                                            {
                                                $message = str_replace("[[activationlink]]", "<a href=\"$thisURL?pluginoption=usersconfirmation&confirm_id=".strrev($confirm_id)."\">$thisURL?pluginoption=usersconfirmation&confirm_id=".strrev($confirm_id)."</a>", $message);
                                            }
                                        }
                                        else
                                        {
                                            $message = str_replace("[[activationlink]]", "<a href=\"".$path["run_time_wwwloc"]."index.php?module=$module&pluginoption=usersconfirmation&confirm_id=".strrev($confirm_id)."\">".$path["run_time_wwwloc"]."index.php?module=$module&pluginoption=usersconfirmation&confirm_id=".strrev($confirm_id)."</a>", $message);
                                        }
                                    }
                                    else
                                    {

                                        $sendsignupemail = $System->useSetting($userSignupAlert);
                                        $subject =  ($System->useSetting($userSignupSub));
                                        $message =  ($System->useSetting($userSignupMsg));



                                    }

                                    if ($sendsignupemail == "Yes")
                                    {
                                        $Data->data = array("first_name", "last_name", "email");
                                        $Data->where = "user_id='1'";
                                        $Data->order = "";
                                        $adresult=$Data->getData(user_accounts);
                                        if($adrow=mssql_fetch_row($adresult))
                                        {
                                            $admin_name = ucwords( ($adrow[0]." ".$adrow[1]));
                                            $admin_email =  ($adrow[2]);
                                        }
                                        mssql_free_result($adresult);

                                        $supportname = ($System->useSetting($userSupportName)==""?"MyAdoptionPortal": ($System->useSetting($userSupportName)));
                                        $supportemail = ($System->useSetting($userSupportEmail)==""?$admin_email:$System->useSetting($userSupportEmail));
                                        $message = str_replace("[[name]]", ucwords($mbr_firstname." ".$mbr_lastname), $message);
                                        $message = str_replace("[[username]]", $mbr_email, $message);
                                        $message = str_replace("[[password]]", $mbr_password, $message);
                                        $message = nl2br($message);
                                        // Subject variable replace
                                        $subject = str_replace("[[name]]", ucwords($mbr_firstname." ".$mbr_lastname), $subject);
                                        $subject = str_replace("[[username]]", $mbr_email, $subject);
                                        $subject = str_replace("[[password]]", $mbr_password, $subject);

                                        $signupmail = new MyMailer();
                                        $signupmail->From = $supportemail;
                                        $signupmail->FromName = $supportname;
                                        $signupmail->AddAddress($mbr_email, ucwords($mbr_firstname." ".$mbr_lastname));
                                        $signupmail->AddReplyTo($supportemail, $supportname);
                                        $signupmail->WordWrap = 200;
                                        $signupmail->IsHTML(true);
                                        $signupmail->Subject = $subject;
                                        $signupmail->Body = "<html><body>$message</body></html>";
                                        $signupmail->Send();
                                        if ($System->useSetting($userEmailAlert))
                                        {
                                            $emailalert = new MyMailer();
                                            $emailalert->From = $supportemail;
                                            $emailalert->FromName = $supportname;
                                            $emailalert->AddAddress($System->useSetting($userEmailAlert), $supportname);
                                            $emailalert->AddReplyTo($supportemail, $supportname);
                                            $emailalert->WordWrap = 200;
                                            $emailalert->IsHTML(true);
                                            $emailalert->Subject = $subject;
                                            $emailalert->Body = "<html><body>$message</body></html>";
                                            $emailalert->Send();
                                        }

                                        if ($System->useSetting($userMobileAlert))
                                        {
                                            $mobilealert = new MyMailer();
                                            $mobilealert->From = $supportemail;
                                            $mobilealert->FromName = $supportname;
                                            $mobilealert->AddAddress($System->useSetting($userMobileAlert), $supportname);
                                            $mobilealert->AddReplyTo($supportemail, $supportname);
                                            $mobilealert->WordWrap = 200;
                                            $mobilealert->IsHTML(true);
                                            $mobilealert->Subject = $subject;
                                            $mobilealert->Body = "<html><body>$message</body></html>";
                                            $mobilealert->Send();
                                        }
                                    }

                                        if ($task == "add" and $loginsuccess != "yes")
                                        {
                                            $signupsuccess = "yes";
                                            if ($System->useSetting("usersignup_membership") == "Hide")
                                            {
                                            //if ($System->useSetting("signup_emailconfirmation") == "Not Required")
                                             if ($System->useSetting($userSignupAlert) == "Yes")
                                            { ?>

                                                <table border=0 cellspacing=2 cellpadding=0 width=100% align=center>
                                                        <tr>
                                                                <td>
                                                                    <?php echo "Thank you for signing up with us." ?><br><br><?php echo "We have sent you the login info. Please proceed with logging into to your account." ?><br>                                                                </td>
                                                        </tr>
                                                </table>
                                                <br><?php
                                            }
                                            else
                                            { ?>

                                                <table border=0 cellspacing=2 cellpadding=0 width=100% align=center>
                                                        <tr>
                                                                <td>
                                                                    <?php echo "Thank you for signing up with us. An email verification link has been sent to you. Please click on it to complete the signup process." ?><br><br><?php echo "You will be able log into your account after the email verification." ?><br>                                                                </td>
                                                        </tr>
                                                </table>
                                                <br><?php
                                            }
                                            }
                                            else
                                            {
                                                $pluginoption = "membership";
                                                $task = "membershiporder";
                                                //$edit_user_id = $Data->getLastID(user_accounts, user_id);
                                            }
                                        }
                                        else
                                        {
                                            $pluginoption = "processing";
                                            //$edit_user_id = $Data->getLastID(user_accounts, user_id);
                                            //echo "<meta http-equiv=\"Refresh\" Content=\"0; URL=$thisURL".$plusURL."pluginoption=users&task=infoedit&infotask=add\">";
                                            header("Location: ".$thisURL.$plusURL."pluginoption=users&task=infoedit&infotask=add");
                                            exit;

                                        }
                                    }
                                    else
                                    {
                                        //$edit_user_id = $Data->getLastID(user_accounts, user_id);
                                    }
                                }
                                else
                                {
                                        echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
                                        if ($task == "add")
                                        {
                                            /* Sending external mail to a AP or BP using first template in under agency profile -> email */
                                            if ($mbr_usertype == 'birth_parent' || $mbr_usertype == 'adoptive_parent')
                                            {

                                                ob_start();
                                                $agencyIDs = $_SESSION['agencyId'];
                                                $currentAgId = $_SESSION['__current_agency_id'];
                                                $agid = ($agencyIDs != "")?(string)$agencyIDs:(string)$currentAgId;
                                                if($agid == '' || $agid == '0')
                                                {
                                                    $usersignupAlert = "user_signupalert";
                                                    $usersignupSub = "user_signupsubject";
                                                    $usersignupMsg = "user_signupmessage";
                                                    $userSupportName = "user_sendsupportname";
                                                    $userSupportEmail = "user_sendsupportemail";
                                                }
                                                else
                                                {
                                                   $usersignupAlert =$agid."user_signupalert";
                                                   $usersignupSub = $agid."user_signupsubject";
                                                   $usersignupMsg = $agid."user_signupmessage";
                                                   $userSupportName = $agid."user_sendsupportname";
                                                   $userSupportEmail = $agid."user_sendsupportemail";
                                                }
                                                $Data->data = array("first_name", "last_name", "email");
                                                $Data->where = "user_id='1'";
                                                $Data->order = "";
                                                $adresult=$Data->getData(user_accounts);
                                                if($adrow=mssql_fetch_row($adresult))
                                                {
                                                    $admin_name = ucwords( ($adrow[0]." ".$adrow[1]));
                                                    $admin_email1 =  ($adrow[2]);
                                                }
                                                mssql_free_result($adresult);

                                                $supportname = ($System->useSetting($userSupportName)==""?"MyAdoptionPortal": ($System->useSetting($userSupportName)));
                                                $supportemail = ($System->useSetting($userSupportEmail)==""?$admin_email1:$System->useSetting($userSupportEmail));
                                                $sendsignupemail = $System->useSetting($usersignupAlert);
                                                $subject =  ($System->useSetting($usersignupSub));

                                                $message =  ($System->useSetting($usersignupMsg));
                                               if ($sendsignupemail == "Yes")
                                                {
                                                        $acc_type_desc = ($mbr_usertype == 'birth_parent')?'Birth Parent':'Adoptive Parent';
                                                        $message = str_replace("[[name]]", ucwords($mbr_firstname." ".$mbr_lastname), $message);
                                                        $message = str_replace("[[username]]", $mbr_email, $message);
                                                        $message = str_replace("[[password]]", $mbr_password, $message);
                                                        $message = str_replace("[[clientname]]", ucwords($mbr_firstname." ".$mbr_lastname), $message);
                                                        $message = str_replace("[[email]]", $mbr_email, $message);
                                                        $message = str_replace("[[phone number]]", $mbr_phone, $message);
                                                        $message = str_replace("[[type of account]]", $acc_type_desc, $message);
                                                        $message = str_replace("[[date]]", date("m-d-Y"), $message);
                                                        $message = nl2br($message);

                                                        $subject = str_replace("[[name]]", ucwords($mbr_firstname." ".$mbr_lastname), $subject);
                                                        $subject = str_replace("[[username]]", $mbr_email, $subject);
                                                        $subject = str_replace("[[password]]", $mbr_password, $subject);
                                                        $subject = str_replace("[[clientname]]", ucwords($mbr_firstname." ".$mbr_lastname), $subject);
                                                        $subject = str_replace("[[email]]", $mbr_email, $subject);
                                                        $subject = str_replace("[[phone number]]", $mbr_phone, $subject);
                                                        $subject = str_replace("[[type of account]]", $acc_type_desc, $subject);
                                                        $subject = str_replace("[[date]]", date("m-d-Y"), $subject);

                                                        $signupmail = new MyMailer();
                                                        $signupmail->From = $supportemail;
                                                        $signupmail->FromName = $supportname;
                                                        $signupmail->AddAddress($mbr_email, ucwords($first_name." ".$last_name));
                                                        $signupmail->AddReplyTo($supportemail, $supportname);
                                                        $signupmail->WordWrap = 400;
                                                        $signupmail->IsHTML(true);
                                                        $signupmail->Subject = $subject;
                                                        $signupmail->Body = "<html><body>$message</body></html>";
                                                        $signupmail->Send();

                                                }
                                                $out1 = ob_get_contents();
                                                ob_end_clean();
                                                $log = fopen("log/log5.txt", 'w') or die("can't open file");
                                                fwrite($log, $out1 );
                                                fclose($log);
                                            }
                                            /* End of  Sending external mail to a AP or BP using first template in under agency profile -> email */
                                            $sysinfo = 'Added';
                                            if($fromparam == 'client')
                                            {
                                                if($fromclientpanelID)
                                                {
                                                    //echo "<meta http-equiv=\"Refresh\" Content=\"0; URL=".$path["run_time_wwwloc"]."index-mp.php?module=cwhome&mount=tasklist_details&view_user_id=$fromclientpanelID\">";
                                                    header("Location: ".$path["run_time_wwwloc"]."index-mp.php?module=cwhome&mount=tasklist_details&view_user_id=$fromclientpanelID");
                                                    exit;
                                                }
                                                else
                                                {
                                                    //echo "<meta http-equiv=\"Refresh\" Content=\"0; URL=".$path["run_time_wwwloc"]."index-mp.php?module=cwhome\">";
                                                    header("Location: ".$path["run_time_wwwloc"]."index-mp.php?module=cwhome");
                                                    exit;
                                                }
                                            }
                                            else
                                            {
                                                //echo "<meta http-equiv=\"Refresh\" Content=\"0; URL=".$path["run_time_wwwloc"]."index-mp.php?module=users&mount=menu-accounts.php&sysinfo=$sysinfo\">";
                                                header("Location: ".$path["run_time_wwwloc"]."index-mp.php?module=users&mount=menu-accounts.php&sysinfo=$sysinfo");
                                                exit;
                                            }
                                        }
                                        else
                                        {
                                            //$edit_user_id = $Data->getLastID(user_accounts, user_id);
                                            //echo "<meta http-equiv=\"Refresh\" Content=\"0; URL=".$path["run_time_wwwloc"]."index-mp.php?module=users&mount=menu-accounts.php&task=infoedit&edit_user_id=$edit_user_id&infotask=add\">";
                                            header("Location: ".$path["run_time_wwwloc"]."index-mp.php?module=users&mount=menu-accounts.php&task=infoedit&edit_user_id=$edit_user_id&infotask=add");
                                            exit;
                                        }
                                }
                        }
                        else
                        {
                                if($module == "users" or $module == "admin")
                                {
                                        $sysinfo = 'User Exists';

                                        echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
                                        //echo "<meta http-equiv=\"Refresh\" Content=\"0; URL=".$path["run_time_wwwloc"]."index-mp.php?module=users&mount=menu-accounts.php&sysinfo=$sysinfo\">";
                                        header("Location: ".$path["run_time_wwwloc"]."index-mp.php?module=users&mount=menu-accounts.php&sysinfo=$sysinfo");
                                        exit;
                                }
                                else
                                {
                                    $signupsuccess = "no";
                                    if($mount!='menu-manageaffiliates.php')
                                    {
                                        ?>
                                        <table border=0 cellspacing=2 cellpadding=0 width=100% align=center>
                                        <tr><td><?php echo "There is a problem with your username and/or password. Please try again." ?><br><br></td></tr>
                                        <tr><td><?php echo str_replace("[linkstart]", "<a href=\"javascript:history.go(-1)\" onMouseOver=\"self.status=document.referrer;return true\">", str_replace("[linkend]", "</a>", "If you are signing up for a new membership, please [linkstart]go back[linkend] to put in a different username. The one you entered is probably taken by someone else.")) ?><br><br></td></tr>
                                        <tr><td><?php echo "Please contact us for assistance if this login problem persists." ?></td></tr>
                                        </table>
                                        <br>
                                        <?php
                                    }
                                }
                        }
                    }
                   /* Add customer and vendor */
                } break;

                case "update" :
                case "editinfo" :
                case "updateinfo" :
                {

                    if(count($mbr_messagegroup) > 0)
                        {
                            $messageGroup_id = @implode(",", $mbr_messagegroup);
                        }

                    $groups_cw		=	$mbr_usergroup;
                    $user_nottify	=	mssql_query("SELECT un_user_id from user_notifications where un_user_id = '$edit_user_id'");
                    if(mssql_num_rows($user_nottify) > 0){
	                    $Data->data = array("un_email_id", "un_notify_email", "un_notify_sms", "un_phone_number", "un_carrier_id");
	                    $Data->value = array("$un_email_id", "$un_notify_email", "$un_notify_sms", "$un_phone_number", "$un_carrier_id");
	                    $Data->where = "un_user_id = '$edit_user_id'";
	                    $Data->updateData(user_notifications, 'UPDATE');
                    }else{
                    	mssql_query("INSERT INTO user_notifications (un_user_id, un_email_id, un_notify_email, un_notify_sms, un_phone_number, un_carrier_id) VALUES
                                                ('$edit_user_id', '$notificationEmail', '$notificationEmailFlg', '$un_notify_sms', '$un_phone_number', '$un_carrier_id')");

                    }
					mssql_free_result($user_nottify);
                    $mbr_photo = strtolower(str_replace(" ", "_",trim($_FILES['mbr_photo']['name'])));
                    if ($mbr_photo)
                    {   // get the extension format
                        $extension = substr($mbr_photo, -4);
                        if ($extension == ".jpg" or $extension == "jpeg" or $extension == ".gif")
                        {
                            $exts     = split("[/\\.]", $mbr_photo) ;
                            $dotcount = count($exts)-1;
                            $exts     = $exts[$dotcount];
                            list($imageName, $imageExt)   = split('.'.$exts, $mbr_photo);
                            $orgpath          = $imageName.".".$exts;
                            $Data->uploadFile($_FILES['mbr_photo']['tmp_name'], $path["serloc"]."userhome/users", $orgpath);
                            $Data->resizeImage($path["serloc"]."userhome/users/".$mbr_photo, $path["serloc"]."userhome/users/".$mbr_photo, 125, 125, 100, 20);

                            //$Data->resizeImage($path["serloc"]."userhome/users/".$orgpath, $path["serloc"]."userhome/users/".$mbr_photo, 125, 125, 100, 20);
                            $Data->data = array("photo");
                            $Data->value = array($mbr_photo);
                            $Data->where = "user_id='$edit_user_id'";
                            $Data->updateData(user_accounts, 'UPDATE');
                        }
                        else if ($extension == ".png" or $extension == ".PNG")
                        {
                            $exts     = split("[/\\.]", $mbr_photo) ;
                            $dotcount = count($exts)-1;
                            $exts     = $exts[$dotcount];
                            list($imageName, $imageExt)   = split('.'.$exts, $mbr_photo);
                            $orgpath          = $imageName."_org.".$exts;
                            $Data->uploadFile($_FILES['mbr_photo']['tmp_name'], $path["serloc"]."userhome/users", $orgpath);
                            $im=imagecreatefrompng($path["serloc"]."userhome/users/".$orgpath);
                            $width=imagesx($im);              // Original picture width is stored
                            $height=imagesy($im);             // Original picture height is stored
                            $newimage=imagecreatetruecolor(100,125);
                            imagecopyresized($newimage,$im,0,0,0,0,125,125,$width,$height);
                            imagepng($newimage, $path["serloc"]."userhome/users/".$mbr_photo);
                            $Data->data = array("photo");
                            $Data->value = array($mbr_photo);
                            $Data->where = "user_id='$edit_user_id'";
                            $Data->updateData(user_accounts, 'UPDATE');
                        }
                        else
                        {
                            $sysinfo = "<font color=red>Invalid Format!</font><br>";
                            $task = "useredit";

                        }
                    }

                    if (empty($sysinfo))
                    {
                        if ($task == "update" || $task == "editinfo")
                        {
                            $Data->data = array("password", "username", "new_encryption");
                            $Data->where = "user_id='$edit_user_id'";
                            $Data->order = "";
                            $result = $Data->getData(user_accounts);
                            if($myrow=mssql_fetch_row($result))
                            {
                                    if ($mbr_suspenduntil)
                                    {
                                        $date = explode("-", $mbr_suspenduntil);
                                        switch(str_replace("%", "", $System->useSetting("general_dateformat")))
                                        {
                                            case "m-d-Y" : $mbr_suspenduntil = $date[2]."-".$date[0]."-".$date[1]; break;
                                            case "d-m-Y" : $mbr_suspenduntil = $date[2]."-".$date[1]."-".$date[0]; break;
                                        }
                                    }

                                    //set user agency user group
                                    $group_id = "";
                                    $messageGroup_id = "";
                                        if(count($mbr_user_group) > 0)
                                        {
                                            $group_id = @implode(",", $mbr_usergroup);
                                        }
                                        if(count($mbr_messagegroup) > 0)
                                        {
                                            $messageGroup_id = @implode(",", $mbr_messagegroup);
                                        }

                                    $this_usertype 		= userGetUserType($cookie_users_userid_usermenu);
                                    $documenttogroups	=	($documenttogroups == "Y")?"Y":"N";
                        			$documenttousers	=	($documenttousers == "Y")?"Y":"N";
                                    if($myrow[0]==addslashes($mbr_password))
                                    {
                                            if($myrow[2] == 'Y')
                                            {
                                                $oldpwd = pwddecryption($myrow[0]);
                                                $old_email = $myrow[1];
                                                $replacevar = "/".$old_email."/";
                                                $oldpassword = preg_replace($replacevar, '', $oldpwd, 1);
                                                $Data->data = array("username","password", "first_name", "last_name", "email","organization", "title", "website", "phone", "address1", "address2", "city", "state", "zipcode", "country","personal_gender", "mobile_number", "timezone", "user_type", "queation", "answer", "group_id","message_group", "case_worker", "spouse_first_name", "spouse_last_name","spouse_gender", "mailtogroups", "doctogroups", "doctousers","edd","id_type","id_number", "new_encryption");
                                                $Data->value = array(($mbr_email),pwdencryption(($mbr_email).($oldpassword)),($mbr_firstname),($mbr_lastname),($mbr_email),($mbr_organization),($mbr_title),($mbr_website),($mbr_phone),($mbr_address1),($mbr_address2),($mbr_city),($mbr_state),($mbr_zipcode),($mbr_country), ($mbr_gender),($mbr_mobilenumber), ($mbr_timezone), ($mbr_usertype), ($mbr_question), ($mbr_answer), $group_id,$messageGroup_id, $case_worker, ($spouse_first_name), ($spouse_last_name),($spouse_gender), $mailtogroups, $documenttogroups, $documenttousers,($un_edd_dt), ($idType), ($un_id_no),"Y");
                                            }
                                            else
                                            {
                                                if($myrow[1] != $mbr_email)
                                                {
                                                    $restpassword = 'welcome1';
                                                    $Data->data = array("username","password", "first_name", "last_name", "email","organization", "title", "website", "phone", "address1", "address2", "city", "state", "zipcode", "country","personal_gender", "mobile_number", "timezone", "user_type", "queation", "answer", "group_id","message_group", "case_worker", "spouse_first_name", "spouse_last_name","spouse_gender", "mailtogroups", "doctogroups", "doctousers","edd","id_type","id_number", "new_encryption");
                                                    $Data->value = array(($mbr_email),pwdencryption(($mbr_email).($restpassword)),($mbr_firstname),($mbr_lastname),($mbr_email),($mbr_organization),($mbr_title),($mbr_website),($mbr_phone),($mbr_address1),($mbr_address2),($mbr_city),($mbr_state),($mbr_zipcode),($mbr_country), ($mbr_gender),($mbr_mobilenumber), ($mbr_timezone), ($mbr_usertype), ($mbr_question), ($mbr_answer), $group_id,$messageGroup_id, $case_worker, ($spouse_first_name), ($spouse_last_name),($spouse_gender), $mailtogroups, $documenttogroups, $documenttousers,($un_edd_dt), ($idType), ($un_id_no),"Y");
                                                }
                                                else
                                                {
                                                    $Data->data = array("username","first_name", "last_name", "email","organization", "title", "website", "phone", "address1", "address2", "city", "state", "zipcode", "country","personal_gender", "mobile_number", "timezone", "user_type", "queation", "answer", "group_id","message_group", "case_worker", "spouse_first_name", "spouse_last_name","spouse_gender", "mailtogroups", "doctogroups", "doctousers","edd","id_type","id_number");
                                                    $Data->value = array(($mbr_email),($mbr_firstname),($mbr_lastname),($mbr_email),($mbr_organization),($mbr_title),($mbr_website),($mbr_phone),($mbr_address1),($mbr_address2),($mbr_city),($mbr_state),($mbr_zipcode),($mbr_country), ($mbr_gender),($mbr_mobilenumber), ($mbr_timezone), ($mbr_usertype), ($mbr_question), ($mbr_answer), $group_id,$messageGroup_id, $case_worker, ($spouse_first_name), ($spouse_last_name),($spouse_gender), $mailtogroups, $documenttogroups, $documenttousers,($un_edd_dt), ($idType), ($un_id_no));
                                                }
                                            }
                                            //$Data->data = array("username","first_name", "last_name", "email", "organization", "title", "website", "phone", "address1", "address2", "city", "state", "zipcode", "country","personal_gender", "mobile_number", "timezone", "user_type", "queation", "answer", "group_id","message_group", "case_worker", "spouse_first_name", "spouse_last_name","spouse_gender", "mailtogroups", "doctogroups", "doctousers","edd","id_type","id_number");
                                            //$Data->value = array(($mbr_email),($mbr_firstname),($mbr_lastname),($mbr_email),($mbr_organization),($mbr_title),($mbr_website),($mbr_phone),($mbr_address1),($mbr_address2),($mbr_city),($mbr_state),($mbr_zipcode),($mbr_country), ($mbr_gender),($mbr_mobilenumber), ($mbr_timezone), ($mbr_usertype), ($mbr_question), ($mbr_answer), $group_id,$messageGroup_id, $case_worker, ($spouse_first_name), ($spouse_last_name), ($spouse_gender),$mailtogroups, $documenttogroups, $documenttousers, ($un_edd_dt), ($idType), ($un_id_no));
                                            //mssql_query("UPDATE user_accounts SET message_group = '$messageGroup_id' WHERE user_id = '$edit_user_id'");
                                    }
                                    else
                                    {
                                            $Data->data = array("username","password", "first_name", "last_name", "email","organization", "title", "website", "phone", "address1", "address2", "city", "state", "zipcode", "country","personal_gender", "mobile_number", "timezone", "user_type", "queation", "answer", "group_id","message_group", "case_worker", "spouse_first_name", "spouse_last_name","spouse_gender", "mailtogroups", "doctogroups", "doctousers","edd","id_type","id_number", "new_encryption");
                                            //$Data->value = array(($mbr_email),sha1(($mbr_email).($mbr_password)),($mbr_firstname),($mbr_lastname),($mbr_email),($mbr_organization),($mbr_title),($mbr_website),($mbr_phone),($mbr_address1),($mbr_address2),($mbr_city),($mbr_state),($mbr_zipcode),($mbr_country), ($mbr_gender),($mbr_mobilenumber), ($mbr_timezone), ($mbr_usertype), ($mbr_question), ($mbr_answer), $group_id,$messageGroup_id, $case_worker, ($spouse_first_name), ($spouse_last_name),($spouse_gender), $mailtogroups, $documenttogroups, $documenttousers,($un_edd_dt), ($idType), ($un_id_no));
                                            $Data->value = array(($mbr_email),pwdencryption(($mbr_email).($mbr_password)),($mbr_firstname),($mbr_lastname),($mbr_email),($mbr_organization),($mbr_title),($mbr_website),($mbr_phone),($mbr_address1),($mbr_address2),($mbr_city),($mbr_state),($mbr_zipcode),($mbr_country), ($mbr_gender),($mbr_mobilenumber), ($mbr_timezone), ($mbr_usertype), ($mbr_question), ($mbr_answer), $group_id,$messageGroup_id, $case_worker, ($spouse_first_name), ($spouse_last_name),($spouse_gender), $mailtogroups, $documenttogroups, $documenttousers,($un_edd_dt), ($idType), ($un_id_no),"Y");
                                           // mssql_query("UPDATE user_accounts SET message_group = '$messageGroup_id' WHERE user_id = '$edit_user_id'");
                                    }
                                    // set user agency group
                                    if ($this_usertype=="admin")
                                    {
                                        $agency_group = "";
                                        for($i=0;$i<count($mbr_agencygroup);$i++)
                                        {
                                            if ($i!=0)
                                            {
                                                $agency_group .=",";
                                            }
                                            $agency_group .= $mbr_agencygroup[$i];
                                        }

                                         if($mbr_status == 'Pending'){
                                        $mbr_status = 'Active';
                                        $mbr_status_mode = 'Pending';
                                        }
                                        elseif($mbr_status == 'Closed'){
                                          $mbr_status = 'Active';
                                        $mbr_status_mode = 'Closed';
                                        }
                                        else{
                                           $mbr_status_mode =$mbr_status;
                                        }

                                        array_push($Data->data,"membership", "status", "agency_group","status_mode");
                                        array_push($Data->value,$mbr_membership, ($mbr_status), $agency_group,($mbr_status_mode));
                                        if ($mbr_suspenduntil)
                                        {
                                            array_push($Data->data,"suspend_until");
                                            array_push($Data->value,$mbr_suspenduntil);
                                        }
                                        else
                                        {
                                            array_push($Data->data,"suspend_until");
                                            array_push($Data->value,"0000-00-00");
                                        }

                                        //agency assignment
                                        if($mbr_usertype == 'agency' or $mbr_usertype == 'attorney')
                                        {

                                            mssql_query("UPDATE user_agencies SET user_id = '0' WHERE user_id = '$edit_user_id'");

                                            if($agency_assignment != '0')
                                            {
                                                mssql_query("UPDATE user_agencies SET user_id = '$edit_user_id' WHERE agency_id = '$agency_assignment'");
                                            }
                                        }

                                        if($mbr_usertype == 'agency_user')
                                        {
                                            array_push($Data->data,"case_worker_parent_user_id");
                                            array_push($Data->value,getUserIDByAgencyID($agency_group));
                                        }
                                        else
                                        {
                                            mssql_query("UPDATE user_accounts SET case_worker_parent_user_id = '0' WHERE user_id = '$edit_user_id'");
                                            mssql_query("UPDATE user_accounts SET case_worker = '0' WHERE case_worker = '$edit_user_id'");
                                            mssql_query("UPDATE user_groups SET case_worker = '0' WHERE case_worker = '$edit_user_id'");
                                        }
                                    }
                                    else
                                    {
                                        if($mbr_status == 'Pending'){
                                        $mbr_status = 'Active';
                                        $mbr_status_mode = 'Pending';
                                        }
                                        elseif($mbr_status == 'Closed'){
                                          $mbr_status = 'Active';
                                        $mbr_status_mode = 'Closed';
                                        }
                                        else{
                                           $mbr_status_mode =$mbr_status;
                                        }

                                        array_push($Data->data,"status","status_mode");
                                        array_push($Data->value,($mbr_status),($mbr_status_mode));
                                        $agency_group = $mbr_agencygroup;
                                        if($mbr_usertype == 'agency_user')
                                        {
                                            array_push($Data->data,"case_worker_parent_user_id");
                                            array_push($Data->value,$cookie_users_userid_usermenu);
                                            $caseIDFullVal          = $edit_user_id."_cw_cwsecurity";
                                            $sql_cw_qy              = "SELECT count(*) AS count FROM system_settings WHERE useroption ='".$caseIDFullVal."'";
                                            $result_cw_data         = mssql_query($sql_cw_qy);
                                            $fetch_cw               = mssql_fetch_assoc($result_cw_data);
                                            if($fetch_cw['count'] == 0)
                                            {

                                                $sql_cw_linsert     = "INSERT INTO system_settings (useroption, setting) VALUES ('".$caseIDFullVal."','".$caseSecurity."')";
                                                $result_data        = mssql_query($sql_cw_linsert);
                                            }
                                            else
                                            {
                                                $sql_cw_UPDATE      = "UPDATE system_settings set setting='".$caseSecurity."' WHERE useroption ='".$caseIDFullVal."'";
                                                $result_data        = mssql_query($sql_cw_UPDATE);
                                            }

                                        	$caseIDFullValFin       = $edit_user_id."_cw_cwsecurityfinancial";
                                            $sql_cw_qyfin           = "SELECT count(*) AS count FROM system_settings WHERE useroption ='".$caseIDFullValFin."'";
                                            $result_cw_datafin      = mssql_query($sql_cw_qyfin);
                                            $fetch_cwfin            = mssql_fetch_assoc($result_cw_datafin);
                                            if($fetch_cwfin['count'] == 0)
                                            {

                                                $sql_cw_linsertfin  = "INSERT INTO system_settings (useroption, setting) VALUES ('".$caseIDFullValFin."','".$financial."')";
                                                $result_data        = mssql_query($sql_cw_linsertfin);
                                            }
                                            else
                                            {
                                                $sql_cw_UPDATEfin   = "UPDATE system_settings set setting='".$financial."' WHERE useroption ='".$caseIDFullValFin."'";
                                                $result_data        = mssql_query($sql_cw_UPDATEfin);
                                            }


                                            $caseIDFullValForm      = $edit_user_id."_cw_userforms";
                                            $sql_cw_usform          = "SELECT count(*) AS count FROM system_settings WHERE useroption ='".$caseIDFullValForm."'";
                                            $result_cw_usform       = mssql_query($sql_cw_usform);
                                            $fetch_usform           = mssql_fetch_assoc($result_cw_usform);
                                            if($fetch_usform['count'] == 0)
                                            {

                                                $sql_cw_linsertorm  = "INSERT INTO system_settings (useroption, setting) VALUES ('".$caseIDFullValForm."','".$caseuserforms."')";
                                                $result_data        = mssql_query($sql_cw_linsertorm);
                                            }
                                            else
                                            {
                                                $sql_cw_updatetorm  = "UPDATE system_settings set setting='".$caseuserforms."' WHERE useroption ='".$caseIDFullValForm."'";
                                                $result_data        = mssql_query($sql_cw_updatetorm);
                                            }

                                            $caseIDFullValMatch       = $edit_user_id."_cw_matching";
                                            $sql_cw_usform_m          = "SELECT count(*) AS count FROM system_settings WHERE useroption ='".$caseIDFullValMatch."'";
                                            $result_cw_usform_m       = mssql_query($sql_cw_usform_m);
                                            $fetch_usform_m           = mssql_fetch_assoc($result_cw_usform_m);
                                            if($fetch_usform_m['count'] == 0)
                                            {

                                                $sql_cw_linsertorm_m  = "INSERT INTO system_settings (useroption, setting) VALUES ('".$caseIDFullValMatch."','".$cwMatching."')";
                                                $result_data        = mssql_query($sql_cw_linsertorm_m);
                                            }
                                            else
                                            {
                                               $sql_cw_linsertorm_m  = "UPDATE system_settings set setting='".$cwMatching."' WHERE useroption ='".$caseIDFullValMatch."'";
                                               $result_data        = mssql_query($sql_cw_linsertorm_m);

                                            }
                                        }
                                        else
                                        {
                                            mssql_query("UPDATE user_accounts SET case_worker_parent_user_id = '0' WHERE user_id = '$edit_user_id'");
                                            mssql_query("UPDATE user_accounts SET case_worker = '0' WHERE case_worker = '$edit_user_id'");
                                            mssql_query("UPDATE user_groups SET case_worker = '0' WHERE case_worker = '$edit_user_id'");
                                        }
                                    }

                                    //case worker and clients assignment
                                    if($mbr_usertype == 'agency_user')
                                    {
                                        array_push($Data->data,"airs_contact_id");
                                        array_push($Data->value,$airs_contact_id?$airs_contact_id:null);
										$prev_users		=	mssql_query("SELECT  parentid from caseworker_client WHERE caseworkerid='$edit_user_id' ");
										$prev_user		=	array();
										$j				=	0;
										while($row		=	mssql_fetch_row($prev_users))
										{
											$prev_user[$j++]	=	$row[0];
										}
                                        if($agency_group != "")
                                        {
                                            mssql_query("UPDATE user_accounts SET case_worker = '0' WHERE agency_group = '$agency_group' and case_worker = '$edit_user_id'");
                                            if ($this_usertype=="admin")
                                            {
                                                $temp_client_id = explode(",",$mbr_ap_id);
												 mssql_query("DELETE from caseworker_client WHERE caseworkerid='$edit_user_id' ");
                                                for ($m=0; $m< count($temp_client_id); $m++)
                                                {
                                                    mssql_query("UPDATE user_accounts SET case_worker = '$edit_user_id' WHERE agency_group = '$agency_group' and user_id='".$temp_client_id[$m]."'");
													mssql_query("INSERT into caseworker_client(parentid,caseworkerid) values('$temp_client_id[$m]','$edit_user_id')");
                                                }
                                            }
                                            else
                                            {

                                                mssql_query("DELETE from caseworker_client WHERE caseworkerid='$edit_user_id' ");
                                                for ($m=0; $m< count($mbr_client); $m++)
                                                {
                                                   mssql_query("UPDATE user_accounts SET case_worker = '$edit_user_id' WHERE agency_group = '$agency_group' and user_id='".$mbr_client[$m]."'");

												   mssql_query("INSERT into caseworker_client(parentid,caseworkerid) values('$mbr_client[$m]','$edit_user_id')");

												}
                                            }
                                        }

                                      //send notification to caseworkers
                                      $newUsers		=	array();
                                      $oldUser		=	array();
                                      $oldUser		=	$mbr_client;
                                      $newUsers		=	array_diff($mbr_client,$prev_user);
                                      if(count($newUsers))
                                	  	notifyCWonClientAssign($edit_user_id,array_reverse($newUsers));
                                    }
                                    if($mbr_usertype == 'adoptive_parent' || $mbr_usertype == 'birth_parent')
                                    {
                                    	$prev_users		=	mssql_query("SELECT  caseworkerid from caseworker_client WHERE parentid='$edit_user_id' ");
										$prev_user		=	array();
										$j				=	0;
										while($row		=	mssql_fetch_row($prev_users))
										{
											$prev_user[$j++]	=	$row[0];
										}
                                         mssql_query("DELETE from caseworker_client WHERE parentid='$edit_user_id' ");
                                         $temp_client_id = $_POST['caseworker'];
                                         for ($m=0; $m< count($temp_client_id); $m++)
                                           {
                                            mssql_query("UPDATE user_accounts SET case_worker = '$edit_user_id' WHERE agency_group = '$agency_group' and user_id='".$temp_client_id[$m]."'");
                                            mssql_query("INSERT into caseworker_client(parentid,caseworkerid) values('$edit_user_id','$temp_client_id[$m]')");
                                        }
                                      //send notification to caseworkers
                                      $newUsers		=	array();
                                      $newUsers		=	array_diff($temp_client_id,$prev_user);
                                      if(count($newUsers))
                                	  	notifyCWonClientAssign(array_reverse($newUsers),$edit_user_id);
                                    }
                            }

                            if($mbr_usertype == 'agency_user'){

                            	$Data->where = "caseworker_id='$edit_user_id'";
            					$Data->deleteData(group_caseworkers);

                             	for ($m=0; $m< count($groups_cw); $m++)
                                {
                                   mssql_query("INSERT into group_caseworkers(group_id,caseworker_id) values('$groups_cw[$m]','$edit_user_id')");
                                }


                            }

                          /*$sql="select answer from user_accounts where user_id='$edit_user_id'";
                          $result=mssql_query($sql);
                          $newans=$_POST['mbr_answer'];
                          $s = mssql_fetch_row($result);
                          $aa = $s[0];
                          if($aa!=$newans)
                          mssql_query("INSERT into audit_trial(userid,targetuserid,action) values('$cookie_users_userid_usermenu','$edit_user_id','hhhhhhhhhhh')");*/
                          //audit_t($edit_user_id,$cookie_users_userid_usermenu,$newans);
                          $comment="";
                          $newans=$_POST['mbr_answer'];
                          $comment.=audit_t($edit_user_id,$login_social_user_id,$newans);
                          $newque=$_POST['mbr_question'];
                          $comment.=audit_secQuestion($edit_user_id,$login_social_user_id,$newque);
                          $newpwd=$_POST['mbr_password'];
                          $comment.=audit_pwd($edit_user_id,$login_social_user_id,$newpwd);
                          $newfname=$_POST['mbr_firstname'];
                          $comment.=audit_firstname($edit_user_id,$login_social_user_id,$newfname);
                          $newlname=$_POST['mbr_lastname'];
                          $comment.=audit_lastname($edit_user_id,$login_social_user_id,$newlname);
                          $newphone=$_POST['mbr_phone'];
                          $comment.=audit_phone($edit_user_id,$login_social_user_id,$newphone);
                          $newmobphone=$_POST['mbr_mobilenumber'];
                          $comment.=audit_mobilephone($edit_user_id,$login_social_user_id,$newmobphone);
                          $newcity=$_POST['mbr_city'];
                          $comment.=audit_newcity($edit_user_id,$login_social_user_id,$newcity);
                          $newstate=$_POST['mbr_state'];
                          $comment.=audit_newstate($edit_user_id,$login_social_user_id,$newstate);
//                           $newzipcode=$_POST['mbr_zipcode'];
//                          $comment.=audit_newzipcode($edit_user_id,$login_social_user_id,$newzipcode);

                          //echo "kkkkkkkkkkkkkkk".$comment.$login_social_user_id;
                          insertDetails($comment,$edit_user_id,$login_social_user_id);
                        }
                        else
                        {
                            //for ($i = 0; $i < 6; ++$i)
                            //{
                               // $contact_settings .= $contact_setting[$i];
                                //if ($i < 5)
                                //{
                                    //$contact_settings .= "-";
                                //}
                            //}
                            //$Data->data = array("summary", "experience", "interests", "education", "affiliations", "contact_settings", "contacted");
                            //$Data->value = array(addslashes($summary), addslashes($experience), addslashes($interests), addslashes($education), addslashes($affiliations), $contact_settings, $contacted);
                            $Data->data = array("summary");
                            $Data->value = array(($summary));
                        }

                        $Data->where = "user_id='$edit_user_id'";

                        $Data->updateData(user_accounts, 'UPDATE');

						updateToUserEffectiveDate($edit_user_id);

                       /* $u_type_zone	=	userGetUserType($login_social_user_id);
                        if(($edit_user_id	==	$login_social_user_id) or $u_type_zone == 'admin'){
                        	$loguserid		=	$u_type_zone == 'admin'?$edit_user_id:$login_social_user_id;
                        	$agencyIDs		=	mssql_query("select agency_id from user_agencies where user_id = '$loguserid'");
				        	$zoneagencyID   =	mssql_fetch_row($agencyIDs);
					        $newValue 		= 	$zoneagencyID[0].'agencydefaulttimezone';
					        $resultsQurery = "SELECT useroption FROM system_settings WHERE useroption = '$newValue'";
					        $userOptions = mssql_query($resultsQurery);
					        if(mssql_num_rows($userOptions) > 0)
					        {

					            $System->updateSetting($newValue, $mbr_timezone);
					        }

					        else{

					            $row = mssql_fetch_array($userOptions);
					            if($row['useroption'] != $newValue){
					                $insertQuery = "INSERT INTO  system_settings (useroption, setting) VALUES ('$newValue','$mbr_timezone')";
					                mssql_query($insertQuery);
					           }
					        }
					        $_SESSION['timezone']	=	$mbr_timezone;
                        }*/


                        // MAPSystemData
                        if ($agency_group!="")
                        {
                            $Data->data = array("c_account_key");
                            $Data->where = "agency_id='$agency_group'";
                            $Data->order = "";
                            $aresult = $Data->getData(user_agencies);
                            if($myarow = mssql_fetch_row($aresult))
                            {
                                $c_account_key = $myarow[0];
                            }
                        }
                        mssql_free_result($aresult);

                        $Data->data = array("AP1Fname", "AP1Lname", "Agency", "AP2Fname", "AP2Lname", "Add1", "Add2", "City", "State", "Zip", "Country", "HomePhone", "AP1CellPhone", "AP1Email", "CaseWorkerId","AP1Gender","AP2Gender");
                        $Data->value = array(($mbr_firstname), ($mbr_lastname), $c_account_key, ($spouse_first_name), ($spouse_last_name), ($mbr_address1), ($mbr_address2), ($mbr_city),($mbr_state), ($mbr_zipcode), ($mbr_country), ($mbr_phone), ($mbr_mobilenumber), ($mbr_email), ($case_worker?$case_worker:0),($mbr_gender),($spouse_gender));
                        $Data->where = "User_Id='$edit_user_id'";
                        $Data->updateData(MAPSystemData, "UPDATE");
                        mssql_query("EXEC sp_GroupRoleAddEdit '$edit_user_id'");

                        if($module == "users" or $module == "admin")
                        {

                                if ($infotask == "add")
                                {
                                    $sysinfo = 'Added';
                                }
                                else
                                {
                                    if (userGetUserType($cookie_users_userid_usermenu)=="admin")
                                    {
                                        for($i=0;$i<count($mbr_agencygroup);$i++)
                                        {
                                            if (!empty($edit_user_id) and !empty($mbr_agencygroup[$i]))
                                            {


                                                $Data->data = array("user_id", "agency_id", "case_worker", "status");
                                                $Data->where = "user_id='$edit_user_id' and agency_id='".$mbr_agencygroup[$i]."'";
                                                $Data->order = "";
                                                $subresult = $Data->getData(user_inquires);
                                                if(mssql_num_rows($subresult)<=0)
                                                {
                                                    $Data->columns = array("user_id", "agency_id", "case_worker", "inquires_date", "join_date", "status");
                                                    $Data->values = array($edit_user_id,$mbr_agencygroup[$i],$case_worker, "CAST('".date("Y-m-d")."' AS smalldatetime)", "CAST('".date("Y-m-d")."' AS smalldatetime)", "confirm");
                                                    $Data->modname = "users";
                                                    $Data->modversion = $System->modversion;
                                                    $Data->modkey = "inquires_id";
                                                    $Data->updateData(user_inquires,"INSERT");
                                                }
                                                else
                                                {
                                                    if($subrow=mssql_fetch_row($subresult))
                                                    {
                                                        if ($subrow[3]!="confirm")
                                                        {
                                                            $Data->data = array("join_date", "status");
                                                            $Data->value = array(date("Y-m-d"), "confirm");
                                                            $Data->where = "user_id='$edit_user_id' and agency_id='".$mbr_agencygroup[$i]."'";
                                                            $Data->updateData(user_inquires, 'UPDATE');
                                                        }
                                                    }
                                                }
                                                $delete_where .= "and agency_id!='".$mbr_agencygroup[$i]."'";
                                                mssql_free_result($subresult);
                                            }
                                        }
                                        $Data->where = "user_id='".$edit_user_id."' and status='confirm' $delete_where";
                                        $Data->deleteData(user_inquires);
                                    }
                                    else
                                    {
                                        if (!empty($edit_user_id) and !empty($mbr_agencygroup))
                                        {
                                            $Data->data = array("user_id", "agency_id", "case_worker", "status");
                                            $Data->where = "user_id='$edit_user_id' and agency_id='".$mbr_agencygroup."'";
                                            $Data->order = "";
                                            $subresult = $Data->getData(user_inquires);
                                            if(mssql_num_rows($subresult)<=0)
                                            {
                                                $Data->columns = array("user_id", "agency_id", "case_worker", "inquires_date", "join_date", "status");
                                                $Data->values = array($edit_user_id,$mbr_agencygroup,0, "CAST('".date("Y-m-d")."' AS smalldatetime)", "CAST('".date("Y-m-d")."' AS smalldatetime)", "confirm");
                                                $Data->modname = "users";
                                                $Data->modversion = $System->modversion;
                                                $Data->modkey = "inquires_id";
                                                $Data->updateData(user_inquires,"INSERT");
                                            }
                                            else
                                            {
                                                if($subrow=mssql_fetch_row($subresult))
                                                {
                                                    if ($subrow[3]!="confirm")
                                                    {
                                                        $Data->data = array("join_date", "status");
                                                        $Data->value = array(date("Y-m-d"), "confirm");
                                                        $Data->where = "user_id='$edit_user_id' and agency_id='".$mbr_agencygroup[$i]."'";
                                                        $Data->updateData(user_inquires, 'UPDATE');
                                                    }
                                                }
                                            }
                                            mssql_free_result($subresult);
                                        }
                                    }

                                    $sysinfo = 'Updated';

                                    // call add user action count function
                                    userAllActivities($edit_user_id, "updated_profile times", "profile");
                                }
                                if ($task != "editinfo")
                                {
                                    echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
                                    if($fromparam == 'client')
                                    {
                                         if($fromclientpanelID)
                                         {
                                            //echo "<meta http-equiv=\"Refresh\" Content=\"0; URL=".$path["run_time_wwwloc"]."index-mp.php?module=cwhome&mount=tasklist_details&view_user_id=$fromclientpanelID\">";
                                            header("Location: ".$path["run_time_wwwloc"]."index-mp.php?module=cwhome&mount=tasklist_details&view_user_id=$fromclientpanelID");
                                            exit;
                                         }
                                        else
                                        {
                                            //echo "<meta http-equiv=\"Refresh\" Content=\"0; URL=".$path["run_time_wwwloc"]."index-mp.php?module=cwhome\">";
                                            header("Location: ".$path["run_time_wwwloc"]."index-mp.php?module=cwhome");
                                            exit;
                                        }
                                    }
                                    else
                                    {
                                        //echo "<meta http-equiv=\"Refresh\" Content=\"0; URL=".$path["run_time_wwwloc"]."index-mp.php?module=users&mount=menu-accounts.php&sysinfo=$sysinfo\">";
                                        header("Location: ".$path["run_time_wwwloc"]."index-mp.php?module=users&mount=menu-accounts.php&sysinfo=$sysinfo");
                                        exit;
                                    }
                                }
                        }else{
                        if($mount!='menu-manageaffiliates.php')
                        {
                            if ($infotask == "add")
                            {

                                    $newuser = "new";

                                    $agencyIDs = $_SESSION['agencyId'];
                                    $currentAgId = $_SESSION['__current_agency_id'];

                                    if($agencyIDs != ""){
                                         $agid = (string)$agencyIDs;
                                    }

                                    else{
                                        $agid = (string)$currentAgId;
                                    }

                                    if($agid == '' || $agid == '0')
                                    {

                                        $userSignupAlert = "user_signupalert";

                                    }

                                    else{
                                         $userSignupAlert = $agid."user_signupalert";
                                    }

                                    //if ($System->useSetting("signup_emailconfirmation") == "Not Required")

                                    $newuser = "new";
                                    if ($System->useSetting($userSignupAlert) == "Yes")



                                    { ?>

                                        <table border=0 cellspacing=2 cellpadding=0 width=100% align=center>
                                                <tr>
                                                        <td>
                                                                    <?php echo "Thank you for signing up with us." ?><br><br><?php echo "We have sent you the login info. Please proceed with logging into to your account." ?><br>                                                        </td>
                                                </tr>
                                        </table>
                                        <br><?php
                                    }
                                    else
                                    { ?>

                                        <table border=0 cellspacing=2 cellpadding=0 width=100% align=center>
                                                <tr>
                                                        <td>
                                                                    <?php echo "Thank you for signing up with us. An email verification link has been sent to you. Please click on it to complete the signup process." ?><br><br><?php echo "You will be able log into your account after the email verification." ?><br>                                                        </td>
                                                </tr>
                                        </table>
                                        <br><?php
                                    }
                            }
                            else if ($task != "editinfo")
                            {
                                ?>
                                <table border=0 cellspacing=2 cellpadding=0 width=100% align=center>
                                        <tr>
                                                <td>
                                                        <?php echo "Your user info has been updated." ?><br><br>
                                                        <a href=<?php echo "$thisURL".$plusURL."pluginoption=users&task=useredit" ?>><?php echo "Back to Edit Profile" ?></a>                                                </td>
                                        </tr>
                                </table>
                                <br>
                                <br>
                                <?php

                                // call add user action count function
                                userAllActivities($edit_user_id, "updated_profile times", "profile");
                            }
                        }
                        }
                    }
                    /* update customer and vendor */
                }
                if ($task != "editinfo")
                {
                    break;
                }
                case "membershiporder" :
                {
                    if ($edit_user_id == "")
                    {
                        $edit_user_id = $user_id;
                    }
                }
                case "infoedit" :
                case "useredit" :
                {

                        //$dateformat = $System->useSetting("general_dateformat");
                        userGetDateTimeFormat("");
                        /* Job Portal
                        $Data->data = array("user_id", "username", "password", "first_name", "last_name", "email", "organization", "title", "phone", "address1", "address2", "city", "state", "zipcode", "country", "website", "mobile_number", "mobile_provider", "skype", "yahoo", "msn", "icq", "permission", "status", "summary", "experience", "interests", "contact_settings", "contacted", "membership", "credit_card", "CONVERT(VARCHAR(10), date_expired,'$dateformat')", "CONVERT(VARCHAR(10), date_expired,'%Y-%m-%d')", "CONVERT(VARCHAR(10), suspend_until,'$dateformat')", "photo", "timezone", "education", "affiliations", "gtalk", "hearaboutus", "showprofile", "CONVERT(VARCHAR(10), suspend_until,'Y-m')", "company", "jobboard_user");
                        */
                        $Data->data = array("user_id", "username", "password", "first_name", "last_name", "email", "organization", "title", "phone", "address1", "address2", "city", "state", "zipcode", "country", "website", "mobile_number", "status", "summary",  "membership", "CONVERT(VARCHAR(10), suspend_until,$dateformat)", "photo", "timezone", "LEFT(CONVERT(VARCHAR(7), suspend_until,120),7) AS [YY-MM]", "user_type", "queation", "answer", "agency_group", "group_id", "case_worker", "type_long", "spouse_first_name", "spouse_last_name", "spouse_organization", "spouse_title", "spouse_website", "mailtogroups", "airs_contact_id", "doctogroups", "doctousers","personal_gender","spouse_gender","message_group","CONVERT(VARCHAR(10), edd, 110) AS edd","id_type","id_number", "new_encryption","last_login");
                        $Data->where = "user_id = '$edit_user_id' and user_accounts.user_type = user_roles.type_name";
                        $Data->order = "";
                        $result=$Data->getData("user_accounts, user_roles");
                        if($myrow=mssql_fetch_row($result))
                        {
                        	//echo convertTimeZones($edit_user_id,$myrow[47]);
                     //  echo $_SESSION['timezone'];
                        	/*echo $myrow[47];
                        	echo date_default_timezone_get();

                        	$dateTimeZoneServer = new DateTimeZone("America/Chicago");
							$dateTimeZoneUser  = new DateTimeZone("America/Chicago");

							$dateTimeuser = new DateTime("now", $dateTimeZoneUser);

							$timeOffset = $dateTimeZoneServer->getOffset($dateTimeuser);

							// Should show int(32400) (for dates after Sat Sep 8 01:00:00 1951 JST).
							print("Number of seconds user is ahead of server at the specific time: ");
							var_dump(strtotime($myrow[47])+$timeOffset);
							echo date("m-d-Y h:m:s A", strtotime($myrow[47])+$timeOffset);

							print("<hr />");*/

                                //print_r($myrow);
                                $mbr_email              = htmlspecialchars( ($myrow[1]));
                                $mbr_password           =  ($myrow[2]);
                                $mbr_repassword         =  ($myrow[2]);
                                $mbr_firstname          = htmlspecialchars( ($myrow[3]));
                                $mbr_lastname           = htmlspecialchars( ($myrow[4]));
                                //$mbr_email              =  ($myrow[5]);
                                $mbr_organization       = htmlspecialchars( ($myrow[6]));
                                $mbr_title              = htmlspecialchars( ($myrow[7]));
                                $mbr_phone              =  ($myrow[8]);
                                $mbr_address1           = htmlspecialchars( ($myrow[9]));
                                $mbr_address2           = htmlspecialchars( ($myrow[10]));
                                $mbr_city               = htmlspecialchars( ($myrow[11]));
                                $mbr_state              =  ($myrow[12]);
                                $mbr_zipcode            =  ($myrow[13]);
                                $mbr_country            =  ($myrow[14]);
                                $mbr_website            = htmlspecialchars( ($myrow[15]));
                                $mbr_mobilenumber       =  ($myrow[16]);
                                $mbr_status             =  ($myrow[17]);
                                $summary =  ($myrow[18]);
                                $mbr_membership = $myrow[19];
                                if ($myrow[20] != "0000-00-00" and $myrow[20] != "00-00-0000" and $myrow[20] != "")
                                {
                                    $mbr_suspenduntil = $myrow[20];
                                }
                                else
                                {
                                    $mbr_suspenduntil = "";
                                }
                                $mbr_photo = $myrow[21];
                                $mbr_timezone =  ($myrow[22]);
                                $mbr_usertype =  ($myrow[24]);
                                $mbr_usertype_desc =  ($myrow[30]);
                                $mbr_question =  (str_replace('" " ','\'',$myrow[25]));
        						$mbr_question = str_replace("''","'",$mbr_question);
                                $mbr_answer = htmlspecialchars( ($myrow[26]));
                                $mbr_agencyid =  ($myrow[27]);
                                $mbr_groupid =  ($myrow[28]);
                                $mbr_messageid =  ($myrow[42]);
                                $mbr_mid =  ($myrow[29]);
                                $mbr_newencryption =  ($myrow[46]);
                                $viewpassword = '';
                                if($mbr_newencryption == 'Y' and getCurrentUserType() == 'admin')
                                {

                                    $viewpwd = pwddecryption($mbr_password);
                                    $replacevar = "/".$mbr_email."/";
                                    $viewpassword = preg_replace($replacevar, '', $viewpwd, 1);
                                    /*$pieces = explode($mbr_email, $viewpwd);
                                    $viewpassword = $pieces[1];*/
                                }
                                $mbr_agency_id = explode(",",$mbr_agencyid);
                                if($mbr_usertype	==	'agency_user'){
	                                $sql			=	"select gc.group_id from group_caseworkers gc
															left join user_groups ug on ug.group_id = gc.group_id
															where gc.caseworker_id = $edit_user_id";
	                				$grpResult 		= mssql_query($sql);
	                				$j				= 0;
	                				while ($mrow = mssql_fetch_row($grpResult))
	                				{
	                					$mbr_group_id[$j] = $mrow[0];
	                					$j++;
	                				}
                                                        $mbr_message_id = explode(",",$mbr_messageid);
                                }else{
                                	$mbr_group_id = explode(",",$mbr_groupid);
                                        $mbr_message_id = explode(",",$mbr_messageid);
                                }


                                $case_worker = $myrow[29];
                                // Spouse Info
                                $spouse_first_name      = htmlspecialchars( ($myrow[31]));
                                $spouse_last_name       = htmlspecialchars( ($myrow[32]));
                                $spouse_gender          = htmlspecialchars( ($myrow[41]));
                                $spouse_organization    = htmlspecialchars( ($myrow[33]));
                                $spouse_title           = htmlspecialchars( ($myrow[34]));
                                $spouse_website         = htmlspecialchars( ($myrow[35]));
                                $mailtogroups			= $myrow[36];
                                $airs_contact_id        = $myrow[37];
                                $doctogroups			= $myrow[38];
                                $doctousers		        = $myrow[39];

                                /* updated by ratheesh ; for bp settings */
                                $edd            =  ($myrow[43]);
                                $idType   =  ($myrow[44]);
                                $idNo =  ($myrow[45]);
                                 /*end of  updated by ratheesh ; for bp settings */

                                /* Job Portal
                                $mbr_company =  ($myrow[42]);
                                $mbr_jobboard_user =  ($myrow[43]);
                                */

                                if($mbr_usertype == 'agency' or $mbr_usertype == 'attorney')
                                {
                                    $sql2 = "SELECT agency_id FROM user_agencies WHERE user_id = '$edit_user_id'";
                                    $result2 = mssql_query($sql2);
                                    if(mssql_num_rows($result2) > 0)
                                    {
                                        $row2 = mssql_fetch_array($result2);
                                        $agency_assignment = $row2['agency_id'];
                                        mssql_free_result($result2);
                                    }
                                }
                        }
                        mssql_free_result($result);
                } break;
        }
if ($task == "signup")
{
    if($module=='users' or $module=='admin')
    {
    ?>
    <form name=formlogin action="index-mp.php?module=users&mount=menu-accounts.php" method=post enctype=multipart/form-data>
    <?php
    }
    else if($module=='affiliatewiz' and $mount=='menu-manageaffiliates.php')
    {
    ?>
    <form name=formlogin action="<?php echo "index-mp.php?module=affiliatewiz&mount=menu-manageaffiliates.php&sortby=$sortby&sortorder=$sortorder&begin=$begin&keyword=$keyword&edit_user_id=$edit_user_id"; ?>" method=post enctype=multipart/form-data>
    <?php
    }
    else
    {
    ?>
    <form name=formlogin action=<?php echo "$thisURL".$plusURL."pluginoption=users" ?> method=post enctype=multipart/form-data>
    <?php
    }
    echo "<input type=hidden name=task value=\"add\">";
}
if ($task == "membershiporder" or $task == "editinfo" or $task == "infoedit" or $task == "useredit")
{
    if ($task != "membershiporder")
    {
        if ($module=="users" or $module=="admin")
        {
        ?>
                <form name=formlogin action=<?php echo "index-mp.php?module=users&mount=menu-accounts.php&edit_user_id=$edit_user_id" ?> method=post enctype=multipart/form-data>
        <?php
        }
        else
        {
            if($module=='affiliatewiz' and $mount=='menu-manageaffiliates.php')
            {
                ?>
                <form name=formlogin action="<?php echo "index-mp.php?module=affiliatewiz&mount=menu-manageaffiliates.php&sortby=$sortby&sortorder=$sortorder&begin=$begin&keyword=$keyword&edit_user_id=$edit_user_id"; ?>" method=post enctype=multipart/form-data>
                <?php
            }
            else
            {
        ?>
                <form name=formlogin action=<?php echo "$thisURL".$plusURL."pluginoption=users" ?> method=post enctype=multipart/form-data>
            <?php
            } ?>
                <input type=hidden name=mbr_membership value=<?php echo $mbr_membership ?>>
                <input type=hidden name=mbr_status value=<?php echo $mbr_status ?>>
                <input type=hidden name=mbr_showprofile value=<?php echo $mbr_showprofile ?>>
        <?php
                for($i=0; $i<count($mbr_permissions); $i++)
                {
                        ?>
                        <input type=hidden name=mbr_permissions[] value=<?php echo $mbr_permissions[$i] ?>>
                        <?php
                }
        }
        if ($task == "useredit")
        {
            echo "<input type=hidden name=task value=\"update\">";
        }
        else
        {
            echo "<input type=hidden name=task value=\"updateinfo\">";
        }
    }
}

// perform various tasks upon form submission
switch($task)
{
    case "membershiporder":
    {
        $payment_verified = "No";

        switch($merchant)
        {
            case "Free" :
            {
                $payment_verified = "Yes";
            } break;
            case "2" :
            {
                if ($x_2checked == "Y")
                {
                    $payment_verified = "Yes";
                }

                $merchant = "2Checkout";
            } break;
            case "a" :
            {
                if ($x_response_code)
                {
                    if ($authorizepayment != "Yes")
                    {
                        $authorize_message = $x_response_reason_text."<br><br>";
                    }
                    if ($x_response_code == 1)
                    {
                        $payment_verified = "Yes";
                    }
                }
                else
                {
                    $content =
                    "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
                    "<ARBCreateSubscriptionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
                    "<merchantAuthentication>".
                    "<name>" . $loginname . "</name>".
                    "<transactionKey>" . $transactionkey . "</transactionKey>".
                    "</merchantAuthentication>".
                    "<refId>" . $refId . "</refId>".
                    "<subscription>".
                    "<paymentSchedule>".
                    "<interval>".
                    "<length>". $length ."</length>".
                    "<unit>". $unit ."</unit>".
                    "</interval>".
                    "<startDate>" . $startDate . "</startDate>".
                    "<totalOccurrences>". $totalOccurrences . "</totalOccurrences>".
                    "</paymentSchedule>".
                    "<amount>". str_replace(",", "", $amount) ."</amount>".
                    "<payment>".
                    "<creditCard>".
                    "<cardNumber>" . $cardNumber . "</cardNumber>".
                    "<expirationDate>" . "$a_ccexp_year-$a_ccexp_month" . "</expirationDate>".
                    "</creditCard>".
                    "</payment>".
                    "<customer>".
                    "<email>". $email . "</email>".
                    "</customer>".
                    "<billTo>".
                    "<firstName>". $firstName . "</firstName>".
                    "<lastName>" . $lastName . "</lastName>".
                    "<address>" . $address . "</address>".
                    "<city>" . $city . "</city>".
                    "<state>" . $state . "</state>".
                    "<zip>" . $zip . "</zip>".
                    "<country>" . $country . "</country>".
                    "</billTo>".
                    "</subscription>".
                    "</ARBCreateSubscriptionRequest>";

                    if (!isset($_GET['path']) and !isset($_REQUEST['path']) and !isset($_POST['path']))
                    {
                        require_once($path["serloc"]."auxiliary/authorizenet/subscription.php");
                    }

                    //send the xml via curl
                    $authorize_response = send_request_via_curl("api.authorize.net","/xml/v1/request.api",$content);

                    $authorize_message = substr(htmlentities($authorize_response), strpos(htmlentities($authorize_response), "code&gt;") + 8);
                    $authorize_message = substr($authorize_message, 0, strpos($authorize_message, "&lt;/text"));
                    $authorize_message = str_replace("&lt;/code&gt;&lt;text&gt;", " ", $authorize_message)."<br><br>";

                    if (strstr($authorize_response, "I00001") and strstr($authorize_response, "Successful"))
                    {
                        $payment_verified = "Yes";
                    }
                }

                $merchant = "Authorize.Net";
            } break;
            case "Cash/Check" :
            {
                $payment_verified = "Yes";
            } break;
            case "e" :
            {
                if (!isset($_GET['path']) and !isset($_REQUEST['path']) and !isset($_POST['path']))
                {
                    require_once($path["serloc"]."auxiliary/echo/echo.php");
                }
                $echoPHP = new EchoPHP;
                $echoPHP->set_EchoServer("https://wwws.echo-inc.com/scripts/INR200.EXE");
                $echoPHP->set_transaction_type("EV");
                $echoPHP->set_order_type("S");
                $echoPHP->set_merchant_echo_id($merchant_echo_id);
                $echoPHP->set_merchant_pin($merchant_pin);
                $echoPHP->set_billing_ip_address($REMOTE_ADDR);
                $echoPHP->set_billing_first_name($billing_first_name);
                $echoPHP->set_billing_last_name($billing_last_name);
                $echoPHP->set_billing_address1($billing_address1);
                $echoPHP->set_billing_address2($billing_address2);
                $echoPHP->set_billing_city($billing_city);
                $echoPHP->set_billing_state($billing_state);
                $echoPHP->set_billing_zip($billing_zip);
                $echoPHP->set_billing_country($billing_country);
                $echoPHP->set_billing_phone($billing_phone);
                $echoPHP->set_billing_email($billing_email);
                $echoPHP->set_cc_number($cc_number);
                $echoPHP->set_ccexp_year($ccexp_year);
                $echoPHP->set_ccexp_month($ccexp_month);
                $echoPHP->set_grand_total($grand_total);
                $echoPHP->set_counter($echoPHP->getRandomCounter());
                if ($echoPHP->Submit())
                {
                    $payment_verified = "Yes";
                }
                $merchant = "ECHO";
            } break;
            case "GoogleCheckout" :
            {
                $payment_verified = "Yes";
            } break;
            case "p" :
            {
                // read the post from PayPal system and add 'cmd'
                $req = 'cmd=_notify-validate';

                foreach ($_POST as $key => $value)
                {
                    $value = urlencode( ($value));
                    $req .= "&$key=$value";
                }

                // post back to PayPal system to validate
                $header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
                $header .= "Host: www.paypal.com:443\r\n";
                $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
                $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
                $fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

                if ($fp)
                {
                    fputs ($fp, $header . $req);
                    $payment_verified = "Yes";
                }

                $merchant = "PayPal";
            } break;
            /* webpay&digipay
            case "d" :
            {
                $payment_verified = "Yes";
                $merchant = "DigiPay";
            } break;
            case "w" :
            {
                if ($_SESSION['webpay_success'] != "No")
                {
                    $payment_verified = "Yes";
                }
                $merchant = "WebPay";
            } break;
            */
        }
        if ($merchant and $_COOKIE["user_membershiporder"] != "YES" and $payment_verified == "Yes")
        {
            $Data->data = array("package", "amount", "subscription", "recurring", "length");
            $Data->where = "membership_id='$membership_package'";
            $Data->order = "";
            $result = $Data->getData(user_memberships);
            if ($myrow = mssql_fetch_row($result))
            {
                $package =  ($myrow[0]);
                if ($discount)
                {
                    $total = number_format($myrow[1] - ($myrow[1] * $discount / 100), 2);
                }
                else
                {
                    $total = $myrow[1];
                }
                if ($myrow[2] == "S")
                {
                    switch ($myrow[3])
                    {
                        case "D": $mbr_suspenduntil = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"))); break;
                        case "W": $mbr_suspenduntil = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 7, date("Y"))); break;
                        case "M": $mbr_suspenduntil = date("Y-m-d", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y"))); break;
                        case "Y": $mbr_suspenduntil = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y") + 1)); break;
                    }
                }
                else
                {
                    if ($myrow[4] > 0)
                    {
                        if (substr($myrow[4], -1) == "D")
                        {
                            $mbr_suspenduntil = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + substr($myrow[4], 0, -1), date("Y")));
                        }
                        else
                        {
                            $mbr_suspenduntil = date("Y-m-d", mktime(0, 0, 0, date("m") + substr($myrow[4], 0, -1), date("d"), date("Y")));
                        }
                    }
                    else
                    {
                        $mbr_suspenduntil = "";
                    }
                }
            }
            mssql_free_result($result);

            $order_status = "N";
            /* webpay&digipay
            if (($merchant == "Free" or $merchant == "Authorize.Net" or $merchant == "Cash/Check" or $merchant == "ECHO" or $merchant == "PayPal" or $merchant == "DigiPay") and $payment_verified == "Yes")
            */
            if (($merchant == "Free" or $merchant == "Authorize.Net" or $merchant == "Cash/Check" or $merchant == "ECHO" or $merchant == "PayPal" or $merchant == "2Checkout") and $payment_verified == "Yes")
            {
                $Data->data = array("CONVERT(VARCHAR(10), suspend_until,120)");
                $Data->where = "user_id='$edit_user_id'";
                $Data->order = "";
                $result = $Data->getData(user_accounts);
                if($myrow = mssql_fetch_row($result))
                {
                    if ($myrow[0] != "0000-00-00")
                    {
                        $mbr_suspenduntil = $myrow[0];
                    }
                }
                mssql_free_result($result);

                $order_status = "P";

                $Data->data = array("membership", "suspend_until", "user_posting", "itemized_date");
                $Data->value = array($membership_package, $mbr_suspenduntil, "", date("Y-m-d"));
                $Data->where = "user_id='$edit_user_id'";
                $Data->updateData(user_accounts, 'UPDATE');
            }

            if ($total=="")
            {
                $total = 0;
            }
            if ($discount=="")
            {
                $discount = 0;
            }
            $Data->columns = array("date", "firstname", "lastname", "email", "package", "merchant", "discount", "total", "status");
            $Data->values = array("CAST('".date("Y-m-d")."' AS smalldatetime)", ($mbr_firstname), ($mbr_lastname), ($mbr_email), ($package), $merchant, $discount, $total, $order_status);
            $Data->where = "";
            $Data->modname = "users";
            $Data->modversion = $System->modversion;
            $Data->modkey = "order_id";
            $Data->updateData(user_orders,"INSERT");

            $detail = "Date: ".date("Y-m-d")."
Name: ".ucwords($mbr_firstname." ".$mbr_lastname)."
Email: $mbr_email
Membership Package: $package
Payment Gateway: $merchant
Total: ".$System->useSetting("general_currencycode").$total;

if ($discount)
{
    $detail .= " ($discount% of discount)";
}
            $agencyIDs = $_SESSION['agencyId'];
            $currentAgId = $_SESSION['__current_agency_id'];

            if($agencyIDs != ""){
                 $agid = (string)$agencyIDs;
            }

            else{
                $agid = (string)$currentAgId;
            }

            if($agid == '' || $agid == '0')
            {
                $userOrderAlert = "user_orderalert";
                $userOrderSub = "user_ordersubject";
                $userOrderBody = "user_orderbody";
                $userPurchaseAlert = "user_purchasealert";
                $userPurchaseSub = "user_purchasesubject";
                $userPurchaseBody = "user_purchasebody";

                $userActivSub = "user_activationsubject";
                $userActivMsg = "user_activationmessage";
                $userSignupAlert = "user_signupalert";
                $userSignupSub = "user_signupsubject";
                $userSignupMsg = "user_signupmessage";
                $userSupportName = "user_sendsupportname";
                $userSupportEmail = "user_sendsupportemail";
                $userEmailAlert = "user_emailalert";
                $userMobileAlert = "user_mobilealert";
            }

            else{
                $userOrderAlert =$agid."user_orderalert";
                $userOrderSub = $agid."user_ordersubject";
                $userOrderBody = $agid."user_orderbody";
                $userPurchaseAlert = $agid."user_purchasealert";
                $userPurchaseSub = $agid."user_purchasesubject";
                $userPurchaseBody = $agid."user_purchasebody";

                $userActivSub = $agid."user_activationsubject";
                $userActivMsg = $agid."user_activationmessage";
                $userSignupAlert = $agid."user_signupalert";
                $userSignupSub = $agid."user_signupsubject";
                $userSignupMsg = $agid."user_signupmessage";
                $userSupportName = $agid."user_sendsupportname";
                $userSupportEmail = $agid."user_sendsupportemail";
                $userEmailAlert = $agid."user_emailalert";
                $userMobileAlert = $agid."user_mobilealert";
            }

            if ($System->useSetting($userOrderAlert) == "Yes")
            {
                $subject =  ($System->useSetting($userOrderSub));
                $message =  ($System->useSetting($userOrderBody));
                $message = str_replace("[[orderdetail]]", $detail, $message);
                // Subject variable replace
                $subject = str_replace("[[orderdetail]]", $detail, $subject);

                $message = nl2br($message);
                $message =  ($message);
                $supportname = ($System->useSetting($userSupportName)==""?"MyAdoptionPortal": ($System->useSetting($userSupportName)));
                $supportemail = ($System->useSetting($userSupportEmail)==""?userGetUserEmail('1'):$System->useSetting($userSupportEmail));
                $orderalert = new MyMailer();
                $orderalert->From = $supportemail;
                $orderalert->FromName = $supportname;
                $orderalert->AddAddress($mbr_email, ucwords($mbr_firstname." ".$mbr_lastname));
                $orderalert->AddReplyTo($supportemail, $supportname);
                $orderalert->WordWrap = 200;
                $orderalert->IsHTML(true);
                $orderalert->Subject = $subject;
                $orderalert->Body = "<html><body>$message</body></html>";
                $orderalert->Send();
            }

            if ($System->useSetting($userPurchaseAlert) == "Yes")
            {
                $subject =  ($System->useSetting($userPurchaseSub));
                $message =  ($System->useSetting($userPurchaseBody));
                $message = str_replace("[[orderdetail]]", $detail, $message);
                // Subject variable replace
                $subject = str_replace("[[orderdetail]]", $detail, $subject);
                $message = nl2br($message);
                $message =  ($message);
                $supportname = ($System->useSetting($userSupportName)==""?"MyAdoptionPortal": ($System->useSetting($userSupportName)));
                $supportemail = ($System->useSetting($userSupportEmail)==""?userGetUserEmail('1'):$System->useSetting($userSupportEmail));

                if ($System->useSetting($userEmailAlert))
                {
                    $emailalert = new MyMailer();
                    $emailalert->From = $supportemail;
                    $emailalert->FromName = $supportname;
                    $emailalert->AddAddress($System->useSetting($userEmailAlert), $supportname);
                    $emailalert->AddReplyTo($supportemail, $supportname);
                    $emailalert->WordWrap = 200;
                    $emailalert->IsHTML(true);
                    $emailalert->Subject = $subject;
                    $emailalert->Body = "<html><body>$message</body></html>";
                    $emailalert->Send();
                }

                if ($System->useSetting($userMobileAlert))
                {
                    $mobilealert = new MyMailer();
                    $mobilealert->From = $supportemail;
                    $mobilealert->FromName = $supportname;
                    $mobilealert->AddAddress($System->useSetting($userMobileAlert), $supportname);
                    $mobilealert->AddReplyTo($supportemail, $supportname);
                    $mobilealert->WordWrap = 200;
                    $mobilealert->IsHTML(true);
                    $mobilealert->Subject = $subject;
                    $mobilealert->Body = "<html><body>$message</body></html>";
                    $mobilealert->Send();
                }
            }

            echo "<script language=javascript>setCookie(\"user_membershiporder\", \"YES\")</script>";
            // call add activity count funtion
            userActivities("orders", "A");

            // call add user action count function
            userAllActivities($edit_user_id, "order packages", "membership");

            if ($merchant == "Authorize.Net" and $x_response_code)
            {
                echo "
                <form name=usersauthorize action=\"$thisURL"."$plusURL"."pluginoption=membership&task=membershiporder&merchant=a&registration=$registration&membership_package=$membership_package&discount=$discount\" method=post>
                <input type=hidden name=authorizepayment value=\"$payment_verified\">
                <input type=hidden name=authorize_message value=\"$authorize_message\">
                </form><script language=javascript>document.usersauthorize.submit()</script>";
            }

            if ($merchant == "Cash/Check")
            {
                $merchant = "";
                $cashcheck = "YES";
            }

            /* webpay&digipay
            if ($merchant == "WebPay")
            {
                $webpay_txnref = rand(100000, 9999999999999999);
                $Data->data = array("refno", "responde");
                $Data->value = array($webpay_txnref, "This transaction is Pending");
                $Data->where = "order_id='".$Data->getLastID(user_orders, order_id)."'";
                $Data->updateData(user_orders, UPDATE);

                $_SESSION['webpay_return_url'] = $path["run_time_wwwloc"]."users/webpayconfirmation.php?membership_package=$membership_package";
                $_SESSION['webpay_order_id'] = $Data->getLastID(user_orders, order_id);
                $_SESSION['webpay_txnref'] = $webpay_txnref;

                $crypt = new Secure;

                echo "
                <form name=wikiWebPayCheckout action=\"$thisURL".$plusURL."pluginoption=membership&task=$task\" method=post>
                <input type=\"hidden\" name=\"MERTID\" value=\"".$System->useSetting("payment_webpayid")."\">
                <input type=\"hidden\" name=\"CADPID\" value=\"".$crypt->decrypt("webpay", $System->useSetting("payment_webpaypartnerid"))."\">
                <input type=\"hidden\" name=\"TXNREF\" value=\"$webpay_txnref\">
                <input type=\"hidden\" name=\"AMT\" value=\"".($total * 100)."\">
                <input type=\"hidden\" name=\"task\" value=\"webpaypayment\">
                </form><script language=javascript>document.wikiWebPayCheckout.submit()</script>";
            }
            */
        }

        if ($discount == "getdiscount")
        {
            $discount = "";
            for($i = 1; $i <= $System->useSetting("payment_promono"); $i++)
            {
                if($complicode == $System->useSetting("payment_promocode$i"))
                {
                    $discount = $System->useSetting("payment_promodiscount$i");
                }
            }
        }

        /* webpay&digipay
        switch($checkout)
        {
            case "usd" : $paypal_currency = "USD"; break;
            case "euro" : $paypal_currency = "EUR"; break;
            case "pound" : $paypal_currency = "GBP"; break;
        }
        if ($System->useSetting("payment_paypal$paypal_currency"))
        {
            $payment_paypal = $System->useSetting("payment_paypal$paypal_currency");
        }
        else
        {
            $payment_paypal = 1;
        }
        if ($System->useSetting("payment_$checkout"."currency"))
        {
            $payment_currency = $System->useSetting("payment_$checkout"."currency");
        }
        else
        {
            $payment_currency = $System->useSetting("payment_nairacurrency");
        }
        */

        if ($merchant == "")
        {
            /* webpay&digipay
            unset($_SESSION['webpay_success']);
            unset($_SESSION['webpay_response']);
            unset($_SESSION['webpay_return_url']);
            unset($_SESSION['webpay_txnref']);
            */
            echo "<script language=javascript>setCookie(\"user_membershiporder\", \"NO\")</script>
            <table border=0 cellpadding=2 cellspacing=0 width=\"100%\" align=center>
            <form name=membershipOrder action=\"$thisURL".$plusURL."pluginoption=membership&task=membershiporder\" method=post>
            <input type=hidden name=event_id value=\"$event_id\">
            <input type=hidden name=registration value=\"$registration\">
            <input type=hidden name=discount value=\"$discount\">
            <input type=hidden name=merchant>";

            /* webpay&digipay
            echo "<input type=hidden name=checkout value=\"$checkout\">";
            */

            //$Data->data = array("membership_id", "package", "amount", "if(subscription='S',recurring,length)", "subscription","description");
            $Data->data = array("membership_id", "package", "amount", "CASE (subscription) WHEN 'S' then recurring else length end as length", "subscription","description");
            $Data->where = "";
            $Data->order = "amount";
            $result = $Data->getData(user_memberships);
            if (mssql_num_rows($result) != 0)
            {
                $Data->where = "membership_id='$mbr_membership'";
                $result = $Data->getData(user_memberships);
                if ($myrow = mssql_fetch_row($result))
                {
                    echo "<tr><td valign=\"top\" width=35%>Current membership: ". ($myrow[1])."<br><br></td></tr>";
                }
                mssql_free_result($result);
                $Data->where = "package!='None'";
                $result = $Data->getData(user_memberships);
                if (mssql_num_rows($result) != 0)
                {
                    echo "<tr><td valign=\"top\" width=35%>Select a New Membership Package:</td></tr>";
                    while ($myrow = mssql_fetch_row($result))
                    {
                        if (empty($membership_package))
                        {
                            $membership_package = $myrow[0];
                        }
                        if ($membership_package == $myrow[0])
                        {
                           if ($discount)
                           {
                               $membershiptotal = number_format($myrow[2] - ($myrow[2] * $discount / 100), 2);
                           }
                           else
                           {
                               $membershiptotal = $myrow[2];
                           }
                           $membership_package_name =  ($myrow[1]);
                           if ($myrow[4] == "S")
                           {
                              $subscription = "YES";
                              $recurring = $myrow[3];
                           }
                        }
                        if ($myrow[2] == "0.00")
                        {
                            $membership_fee = Free;
                        }
                        else
                        {
                            if ($discount)
                            {
                                $membership_fee = $System->useSetting("general_currencycode").number_format($myrow[2] -= ($myrow[2] * $discount / 100), 2);
                                /* webpay&digipay
                                $membership_fee = "$payment_currency".number_format(($myrow[2] -= ($myrow[2] * $discount / 100))*$payment_paypal, 2);
                                */
                            }
                            else
                            {
                                $membership_fee = $System->useSetting("general_currencycode").$myrow[2];
                                /* webpay&digipay
                                $membership_fee = "$payment_currency".number_format(($myrow[2]*$payment_paypal), 2);
                                */
                            }
                        }
                        if ($myrow[4] == "S")
                        {
                              switch ($myrow[3]) {
                              case "D":
                                 $length = Daily;
                                 $unit = "1 days";
                                 break;
                              case "W":
                                 $length = Weekly;
                                 $unit = "7 days";
                                 break;
                              case "M":
                                 $length = Monthly;
                                 $unit = "1 months";
                                 break;
                              case "Y":
                                 $length = Yearly;
                                 $unit = "12 months";
                                 break;
                              }
                        }
                        else
                        {
                           if ($myrow[3] == 0)
                           {
                              $length = "Life Time";
                           }
                           else
                           {
                              if (substr($myrow[3], -1) == "D")
                              {
                                    $myrow[3] = substr($myrow[3], 0, -1);
                                  if ($myrow[3] > 1)
                                  {
                                     $term  = "s";
                                  }
                                  $length = $myrow[3]." ".${"users_areatext_day$term"};
                              }
                              else
                              {
                                    $myrow[3] = substr($myrow[3], 0, -1);
                                  if ($myrow[3] > 1)
                                  {
                                     $term  = "s";
                                  }
                                  $length = $myrow[3]." ".${"users_areatext_month$term"};
                              }
                           }
                        }
                        echo "<tr><td valign=top><input type=radio name=membership_package value=\"$myrow[0]\"".($membership_package=="$myrow[0]"?"checked":"")." onClick=\"document.membershipOrder.submit()\"> ". ($myrow[1])." ($length, $membership_fee)</td></tr>";
                        if($membership_package == "$myrow[0]" and $myrow[5])
                        {
                        echo "<tr><td valign=top><table border=0 cellpadding=2 cellspacing=0 width=\"100%\" align=center><tr ><td width=\"20\"></td><td>". ($myrow[5])."</td><tr></table></td></tr>";
                        }
                    }
                    mssql_free_result($result);
                }
                $total = number_format($membershiptotal, 2);
                if ($total != "0.00")
                {
                    if ($discount)
                    {
                        echo "<tr><td><br>&nbsp;* ".str_replace("[discount]", $discount, "Each package is given a discount of [discount]%")."</td></tr>";
                    }

                    /* webpay&digipay
                    if ($checkout == "")
                    {
                        echo "<tr><td><br>$users_areatext_checkout: <input type=\"submit\" class=button value=\"$users_areatext_naira\" onclick=\"document.membershipOrder.checkout.value='naira'\" style=\"width:60px\"> <input type=\"submit\" class=button value=\"$users_areatext_usd\" onclick=\"document.membershipOrder.checkout.value='usd'\" style=\"width:60px\"> <input type=\"submit\" class=button value=\"$users_areatext_euro\" onclick=\"document.membershipOrder.checkout.value='euro'\" style=\"width:60px\"> <input type=\"submit\" class=button value=\"$users_areatext_pound\" onclick=\"document.membershipOrder.checkout.value='pound'\" style=\"width:60px\"></td></tr>";
                    }
                    else
                    {
                    */

                    echo "
                    <tr><td><br><table border=0 cellspacing=1 cellpadding=1 width=100% align=center>
                    <tr><td><input type=text name=complicode size=10> <input type=\"submit\" class=button value=\"Apply Promo Code\" onclick=\"document.membershipOrder.discount.value='getdiscount';\"></td></tr></table></td></tr></form>
                    <tr><td><br><div id=paymenticons><table border=0 cellpadding=0 cellspacing=0 width=100%><tr><td>Select a payment service below. You have the option to pay by credit card.<br><br></td></tr></table><table border=0 cellpadding=0 cellspacing=0 width=100%>";

                    /* webpay&digipay
                    if ($checkout != "naira")
                    {
                    */
                    // code for getting the agency id
                    $current_agency_id = getCurrentAgencyId_Agency($user_id);

                    // end
                    if($System->useSetting("payment_2checkout") != "" and $System->useSetting("payment_2checkoutkey") != "")
                    {
                        $fee_payment['2checkout'] = "Yes";
                    }
                    if($System->useSetting($current_agency_id."payment_authorizeid") != "" and $System->useSetting($current_agency_id."payment_authorizekey") != "")
                    {
                        $fee_payment['authorize'] = "Yes";
                    }
                    if($System->useSetting("payment_echoid") != "" and $System->useSetting("payment_echopin") != "")
                    {
                        $fee_payment['echo'] = "Yes";
                    }
                    if($System->useSetting("payment_googlecheckoutid") != "" and $System->useSetting("payment_googlecheckoutkey") != "")
                    {
                        $fee_payment['googlecheckout'] = "Yes";
                    }
                    if($System->useSetting($current_agency_id."payment_paypalemail") != "" and $System->useSetting($current_agency_id."payment_paypalidtoken") != "")
                    {
                        $fee_payment['paypal'] = "Yes";
                    }
                    if($System->useSetting("payment_cashcheck") != "")
                    {
                        $fee_payment['cashcheck'] = "Yes";
                    }

                    /* webpay&digipay
                    }
                    else
                    {
                        if($System->useSetting("payment_digipaykey") != "")
                        {
                            $fee_payment['digipay'] = "Yes";
                        }
                        if($System->useSetting("payment_webpayid") != "" and $System->useSetting("payment_webpaypartnerid") != "")
                        {
                            $fee_payment['webpay'] = "Yes";
                        }
                    }
                    */

                    if($fee_payment)
                    {
                        $seq_order_id = $refnumber = $Data->getLastID(user_orders, order_id) + 1;

                        if($fee_payment['2checkout'] == "Yes")
                        {    echo "<tr><td valign=top>
                            <form name=wikiCheckout action=\"https://www2.2checkout.com/2co/buyer/purchase\" method=post>
                            <input type=hidden name=sid value=\"".$System->useSetting("payment_2checkout")."\">
                            <input type=hidden name=total value=\"$total\">
                            <input type=hidden name=cart_order_id value=\"$seq_order_id\">
                            <input type=hidden name=\"x_Receipt_Link_URL\" value=\"$thisURL"."$plusURL"."pluginoption=membership&task=membershiporder&merchant=2&registration=$registration&membership_package=$membership_package&discount=$discount\">
                            <input type=\"image\" src=\"".$path["run_time_wwwloc"]."auxiliary/images/2co.gif\" alt=\"2CheckOut\" border=0 onclick=\"document.membershipOrder.merchant.value='2Checkout';document.membershipOrder.submit()\"></form></td></tr>";
                        }
                        if($fee_payment['authorize'] == "Yes")
                        {
                            $crypt = new Secure;

                            if ($subscription == "YES")
                            {
                                $unit = explode(" ", $unit);
                                echo "<tr><td><img src=\"".$path["run_time_wwwloc"]."auxiliary/images/authorize.gif\" alt=\"Authorize.Net\" border=0 onclick=\"document.getElementById('paymenticons').style.display='none';document.getElementById('paymentccinfo2').style.display='block';\" style=\"cursor:pointer\"><br><br></td></tr>";
                            }
                            else
                            {
                                switch (strlen($refnumber))
                                {
                                    case 1 : $refnumber = "000".$refnumber; break;
                                    case 2 : $refnumber = "00".$refnumber; break;
                                    case 3 : $refnumber = "0".$refnumber; break;
                                }

                                function hmac ($key, $data)
                                {
                                    return (bin2hex (mhash(MHASH_MD5, $data, $key)));
                                }

                                $tstamp = gmdate("U");
                                $current_agency_id = getCurrentAgencyId_Agency($user_id);
                                $fingerprint = hmac (trim($crypt->decrypt("authorize", $System->useSetting($current_agency_id."payment_authorizekey"))), $System->useSetting($current_agency_id."payment_authorizeid") . "^" . $seq_order_id . "^" . $tstamp . "^" . $total . "^");

                                echo "<tr><td>
                                <form name=wikiCheckout action=\"https://secure.authorize.net/gateway/transact.dll\" method=post>
                                <input type=hidden name=\"x_fp_hash\" value=\"" . $fingerprint . "\">
                                <input type=hidden name=\"x_fp_sequence\" value=\"" . $seq_order_id . "\">
                                <input type=hidden name=\"x_fp_timestamp\" value=\"" . $tstamp . "\">
                                <input type=hidden name=\"x_login\" value=\"".$System->useSetting($current_agency_id."payment_authorizeid")."\">
                                <input type=hidden name=\"x_invoice_num\" value=\"$refnumber\">
                                <input type=hidden name=\"x_amount\" value=\"$total\">
                                <input type=hidden name=\"x_show_form\" value=\"PAYMENT_FORM\">
                                <input type=hidden name=\"x_first_name\" value=\"$mbr_firstname\">
                                <input type=hidden name=\"x_last_name\" value=\"$mbr_lastname\">
                                <input type=hidden name=\"x_address\" value=\"$mbr_address1 $mbr_address2\">
                                <input type=hidden name=\"x_city\" value=\"$mbr_city\">
                                <input type=hidden name=\"x_state\" value=\"$mbr_state\">
                                <input type=hidden name=\"x_zip\" value=\"$mbr_zipcode\">
                                <input type=hidden name=\"x_country\" value=\"$mbr_country\">
                                <input type=hidden name=\"x_phone\" value=\"$mbr_phone\">
                                <input type=hidden name=\"x_email\" value=\"$mbr_email\">
                                <input type=hidden name=\"x_relay_response\" value=\"TRUE\">
                                <input type=hidden name=\"x_relay_url\" value=\"$thisURL"."$plusURL"."pluginoption=membership&task=membershiporder&merchant=a&registration=$registration&membership_package=$membership_package&discount=$discount\">
                                <input type=\"image\" src=\"".$path["run_time_wwwloc"]."auxiliary/images/authorize.gif\" alt=\"Authorize.Net\" border=0></form></td></tr>";
                            }
                        }
                        if($fee_payment['echo'] == "Yes")
                        {
                            $crypt = new Secure;
                            $ccexp_date = explode("-", $mbr_expirationdate);
                            echo "<tr><td><img src=\"".$path["run_time_wwwloc"]."auxiliary/images/echo.gif\" alt=\"ECHO\" border=0 onclick=\"document.getElementById('paymenticons').style.display='none';document.getElementById('paymentccinfo').style.display='block';\" style=\"cursor:pointer\"><br><br></td></tr>";
                        }
                        if($fee_payment['googlecheckout'] == "Yes")
                        {    echo "<tr><td valign=top>";
                            if (!isset($_GET['path']) and !isset($_REQUEST['path']) and !isset($_POST['path']))
                            {    // require all the required files
                                require($path["serloc"]."auxiliary/googlecheckout/googlecart.php");
                                require($path["serloc"]."auxiliary/googlecheckout/googleitem.php");
                            }
                            $crypt = new Secure;
                            $cart = new GoogleCart($System->useSetting("payment_googlecheckoutid"), $crypt->decrypt("googlecheckout", $System->useSetting("payment_googlecheckoutkey")), "checkout", "USD");
                            // add items to the cart
                            $cart->AddItem(new GoogleItem("Membership Package: $membership_package_name", "", 1, $membershiptotal));
                            // display Google Checkout button
                            echo $cart->CheckoutButtonCode("BIG", "membership")."</td></tr>";
                        }
                        if($fee_payment['paypal'] == "Yes")
                        {
                           $current_agency_id = getCurrentAgencyId_Agency($user_id);
                            $crypt = new Secure;
                           if ($subscription == "YES")
                           {


                              // subscription
                              echo "<tr><td valign=top>
                              <form name=wikiCheckout action=\"https://www.paypal.com/cgi-bin/webscr\" method=post>
                              <input type=\"hidden\" name=\"cmd\" value=\"_xclick-subscriptions\">
                              <input type=\"hidden\" name=\"business\" value=\"".$System->useSetting($current_agency_id."payment_paypalemail")."\">
                              <input type=\"hidden\" name=\"return\" value=\"$thisURL"."$plusURL"."pluginoption=membership&task=membershiporder&merchant=p&registration=$registration&membership_package=$membership_package&discount=$discount\">
                              <input type=\"hidden\" name=\"rm\" value=\"2\">
                              <input type=\"hidden\" name=\"item_name\" value=\"Membership Package: $membership_package_name\">
                              <input type=\"hidden\" name=\"item_number\" value=\"SKU#/Invoice#\">
                              <input type=\"hidden\" name=\"a3\" value=\"$membershiptotal\">
                              <input type=\"hidden\" name=\"t3\" value=\"$recurring\">
                              <input type=\"hidden\" name=\"p3\" value=\"1\">
                              <input type=\"hidden\" name=\"src\" value=\"1\">
                              <input type=\"hidden\" name=\"sra\" value=\"1\">
                              <input type=\"hidden\" name=\"currency_code\" value=\"".$System->useSetting("general_currency")."\">
                              <input type=\"hidden\" name=\"lc\" value=\"US\">
                              <input type=\"hidden\" name=\"cancel_return\" value=\"$thisURL"."$plusURL"."pluginoption=membership&task=membershiporder\">";
                              echo "<input type=\"image\" src=\"".$path["run_time_wwwloc"]."auxiliary/images/paypal.gif\" alt=\"PayPal\" border=0></form></td></tr>";
                           }
                           else
                           {
                              // shopping cart
                              echo "<tr><td valign=top>
                              <form name=wikiCheckout action=\"https://www.paypal.com/cgi-bin/webscr\" method=post>
                              <input type=\"hidden\" name=\"cmd\" value=\"_cart\">
                              <input type=\"hidden\" name=\"upload\" value=\"1\">
                              <input type=\"hidden\" name=\"business\" value=\"".$System->useSetting($current_agency_id."payment_paypalemail")."\">
                              <input type=\"hidden\" name=\"return\" value=\"$thisURL"."$plusURL"."pluginoption=membership&task=membershiporder&merchant=p&registration=$registration&membership_package=$membership_package&discount=$discount\">
                              <input type=\"hidden\" name=\"rm\" value=\"2\">
                              <input type=\"hidden\" name=\"currency_code\" value=\"".$System->useSetting("general_currency")."\">
                              <input type=\"hidden\" name=\"at\" value=\"".$crypt->decrypt("paypal", $System->useSetting($current_agency_id."payment_paypalidtoken"))."\">
                              <input type=\"hidden\" name=\"item_name_1\" value=\"Membership Package: $membership_package_name\">
                              <input type=\"hidden\" name=\"amount_1\" value=\"$membershiptotal\">
                              <input type=\"hidden\" name=\"quantity_1\" value=\"1\">";
                              echo "<input type=\"image\" src=\"".$path["run_time_wwwloc"]."auxiliary/images/paypal.gif\" alt=\"PayPal\" border=0></form></td></tr>";
                           }
                        }
                        if($fee_payment['cashcheck'] == "Yes")
                        {
                            echo "<tr><td><input type=\"image\" src=\"".$path["run_time_wwwloc"]."auxiliary/images/cash-check.gif\" alt=\"Cash/Check\" border=0 onclick=\"document.membershipOrder.merchant.value='Cash/Check';document.membershipOrder.submit()\"></td></tr>";

                            if ($cashcheck == "YES")
                            {
                                echo "<tr><td><br>".nl2br($System->useSetting("payment_cashcheck"))."</td></tr>";
                            }
                        }
                        /* webpay&digipay
                        if($fee_payment['digipay'] == "Yes")
                        {
                            switch (strlen($seq_order_id))
                            {
                                case 1 : $seq_order_id = "000".$seq_order_id; break;
                                case 2 : $seq_order_id = "00".$seq_order_id; break;
                                case 3 : $seq_order_id = "0".$seq_order_id; break;
                            }
                            $crypt = new Secure;
                            echo "<tr><td valign=top>
                            <form name=wikiCheckout action=\"http://www.digipay.com.ng/process.htm\" method=post>
                            <input type=hidden name=member value=\"".$crypt->decrypt("digipay", $System->useSetting("payment_digipaykey"))."\">
                            <input type=hidden name=action value=\"payment\">
                            <input type=hidden name=product value=\"Invoice $seq_order_id\">
                            <input type=hidden name=price value=\"$total\">
                            <input type=hidden name=quantity value=\"1\">
                            <input type=hidden name=ureturn value=\"$thisURL"."$plusURL"."pluginoption=membership&task=membershiporder&merchant=d&registration=$registration&membership_package=$membership_package&discount=$discount\">
                            <input type=hidden name=unotify value=\"$thisURL"."$plusURL"."pluginoption=membership&task=membershiporder&merchant=d&registration=$registration&membership_package=$membership_package&discount=$discount\">
                            <input type=hidden name=ucancel value=\"$thisURL".$plusURL."pluginoption=membership&task=membershiporder&checkout=$checkout\">
                            <input type=\"image\" src=\"".$path["run_time_wwwloc"]."auxiliary/images/digipay.gif\" alt=\"$users_areatext_digipay\" border=0></form></td></tr>";
                        }
                        if($fee_payment['webpay'] == "Yes")
                        {
                            echo "<tr><td><img src=\"".$path["run_time_wwwloc"]."auxiliary/images/webpay.jpg\" alt=\"$users_areatext_webpay\" border=0 onclick=\"document.membershipOrder.merchant.value='w';document.membershipOrder.submit();\" style=\"cursor:pointer\">";
                        }
                        */
                    }
                    else
                    {
                        echo "<tr><td valign=top>None of the payment gateways is working. Please check the settings on Payment page.</td></tr>";
                    }
                    echo "</table><br></div><div id=paymentccinfo style=\"display:none\"><table border=0 cellpadding=0 cellspacing=0 width=100% align=center>
                    <form name=wikiECHOCheckout action=\"$thisURL"."$plusURL"."pluginoption=membership&task=membershiporder&merchant=e&registration=$registration&membership_package=$membership_package&discount=$discount\" method=post onsubmit=\"return isEmpty('wikiECHOCheckout')\">
                    <input type=hidden name=\"merchant_echo_id\" value=\"".$System->useSetting("payment_echoid")."\">
                    <input type=hidden name=\"merchant_pin\" value=\"".$crypt->decrypt("echo", $System->useSetting("payment_echopin"))."\">
                    <input type=hidden name=\"billing_first_name\" value=\"$mbr_firstname\">
                    <input type=hidden name=\"billing_last_name\" value=\"$mbr_lastname\">
                    <input type=hidden name=\"billing_address1\" value=\"$mbr_address1\">
                    <input type=hidden name=\"billing_address2\" value=\"$mbr_address2\">
                    <input type=hidden name=\"billing_city\" value=\"$mbr_city\">
                    <input type=hidden name=\"billing_state\" value=\"$mbr_state\">
                    <input type=hidden name=\"billing_zip\" value=\"$mbr_zipcode\">
                    <input type=hidden name=\"billing_country\" value=\"$mbr_country\">
                    <input type=hidden name=\"billing_phone\" value=\"$mbr_phone\">
                    <input type=hidden name=\"billing_email\" value=\"$mbr_email\">
                    <input type=hidden name=\"grand_total\" value=\"$total\">
                    <input type=hidden name=\"billing_type\" value=\"users\">
                    <input type=hidden name=\"module\" value=\"$module\">
                    <input type=hidden name=\"membership_package\" value=\"$membership_package\">
                    <input type=hidden name=\"discount\" value=\"$discount\">
                    <input type=\"hidden\" name=\"billing_return\" value=\"$thisURL"."$plusURL"."pluginoption=membership&task=membershiporder&merchant=e&registration=$registration&membership_package=$membership_package&discount=$discount\">
                    <tr><td width=30%>Credit Card:</td><td><input type=text name=cc_number value=\"$mbr_creditcard\" style=\"width:230px\">*</td></tr><tr height=4><td></td></tr>
                    <tr><td>Expiration Date:</td><td>Month <select name=ccexp_month>";
                    // set the field for checking
                    $fieldcheck = array("cc_number");
                    $fieldalert = array("Credit Card is a mandatory field. Please check and try again.");
                    $ccexp_date = explode("-", $expiredate);
                    for ($i = 1; $i < 13; ++$i)
                    {
                        echo "<option value=\"$i\"".($ccexp_date[1]=="$i"?"selected":"").">$i</option>";
                    }
                    echo "</select> Year <select name=ccexp_year>";
                    for ($i = 0; $i < 11; ++$i)
                    {
                        echo "<option value=\"".(date("Y") + $i)."\"".($ccexp_date[0]=="".(date("Y") + $i).""?"selected":"").">".(date("Y") + $i)."</option>";
                    }
                    $crypt = new Secure;
                    $current_agency_id = getCurrentAgencyId_Agency($user_id);
                    echo "</select></td></tr><tr height=4><td></td></tr>
                    <tr><td></td><td><br><input class=button type=submit value=\"Continue\"></td></tr></form>
                    </table></div><div id=paymentccinfo2 style=\"display:none\"><table border=0 cellpadding=0 cellspacing=0 width=100% align=center>
                    <form name=wikiAuthorizeCheckout action=\"$thisURL"."$plusURL"."pluginoption=membership&task=membershiporder&merchant=a&registration=$registration&membership_package=$membership_package&discount=$discount\" method=post>
                    <input type=hidden name=\"loginname\" value=\"".$System->useSetting($current_agency_id."payment_authorizeid")."\">
                    <input type=hidden name=\"transactionkey\" value=\"".$crypt->decrypt("authorize", $System->useSetting($current_agency_id."payment_authorizekey"))."\">
                    <input type=hidden name=\"amount\" value=\"$total\">
                    <input type=hidden name=\"refId\" value=\"$seq_order_id\">
                    <input type=hidden name=\"length\" value=\"$unit[0]\">
                    <input type=hidden name=\"unit\" value=\"$unit[1]\">
                    <input type=hidden name=\"startDate\" value=\"".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")))."\">
                    <input type=hidden name=\"totalOccurrences\" value=\"9999\">
                    <tr><td width=30%>First Name:</td><td><input type=text name=firstName value=\"$mbr_firstname\" style=\"width:230px\">*</td></tr>
                    <tr><td>Last Name:</td><td><input type=text name=lastName value=\"$mbr_lastname\" style=\"width:230px\">*</td></tr>
                    <tr><td>Email:</td><td><input type=text name=email value=\"$mbr_email\" style=\"width:230px\">*</td></tr>
                    <tr><td>Address:</td><td><input type=text name=address value=\"$mbr_address1\" style=\"width:230px\"></td></tr>
                    <tr><td>City:</td><td><input type=text name=city value=\"$mbr_city\" style=\"width:230px\"></td></tr>
                    <tr><td>State:</td><td><input type=text name=state value=\"$mbr_state\" style=\"width:230px\"></td></tr>
                    <tr><td>Zip/Postal Code:</td><td><input type=text name=zip value=\"$mbr_zipcode\" style=\"width:230px\"></td></tr>
                    <tr><td>Country:</td><td><input type=text name=country value=\"$mbr_country\" style=\"width:230px\"></td></tr>
                    <tr><td>Credit Card:</td><td><input type=text name=cardNumber value=\"$mbr_creditcard\" style=\"width:230px\">*</td></tr><tr height=4><td></td></tr>
                    <tr><td>Expiration Date:</td><td>Month <select name=a_ccexp_month>";
                    $ccexp_date = explode("-", $expiredate);
                    for ($i = 1; $i < 13; ++$i)
                    {
                        echo "<option value=\"$i\"".($ccexp_date[1]=="$i"?"selected":"").">$i</option>";
                    }
                    echo "</select> Year <select name=a_ccexp_year>";
                    for ($i = 0; $i < 11; ++$i)
                    {
                        echo "<option value=\"".(date("Y") + $i)."\"".($ccexp_date[0]=="".(date("Y") + $i).""?"selected":"").">".(date("Y") + $i)."</option>";
                    }
                    echo "</select></td></tr><tr height=4><td></td></tr>
                    <tr><td></td><td><br><input class=button type=submit value=\"Continue\" onclick=\"return authorizeFieldChecking()\"></td></tr></form>
                    </table></div><br></td></tr>
<script language=javascript>function authorizeFieldChecking(){
if (document.wikiAuthorizeCheckout.firstName.value == \"\" || document.wikiAuthorizeCheckout.firstName.value == null || document.wikiAuthorizeCheckout.firstName.value.charAt(0) == ' ')
{
    alert(\"First Name is a mandatory field. Please check and try again.\")
    document.wikiAuthorizeCheckout.firstName.select();
    return false;
}
if (document.wikiAuthorizeCheckout.lastName.value == \"\" || document.wikiAuthorizeCheckout.lastName.value == null || document.wikiAuthorizeCheckout.lastName.value.charAt(0) == ' ')
{
    alert(\"Last Name is a mandatory field. Please check and try again.\")
    document.wikiAuthorizeCheckout.lastName.select();
    return false;
}
if (document.wikiAuthorizeCheckout.email.value == \"\" || document.wikiAuthorizeCheckout.email.value == null || document.wikiAuthorizeCheckout.email.value.charAt(0) == ' ')
{
    alert(\"Email is a mandatory field. Please check and try again.\")
    document.wikiAuthorizeCheckout.email.select();
    return false;
}
if (checkEmail(document.wikiAuthorizeCheckout.email.value) != true)
{
    alert(\"Email format appears to be wrong. Please check and try again.\")
    document.wikiAuthorizeCheckout.email.select();
    return false;
}
if (document.wikiAuthorizeCheckout.cardNumber.value == \"\" || document.wikiAuthorizeCheckout.cardNumber.value == null || document.wikiAuthorizeCheckout.cardNumber.value.charAt(0) == ' ')
{
    alert(\"Credit Card is a mandatory field. Please check and try again.\")
    document.wikiAuthorizeCheckout.cardNumber.select();
    return false;
}
return true;
}</script>";
                    /* webpay&digipay
                    }
                    */
                }
                else
                {
                    echo "<tr><td><br><input type=\"button\" class=button value=\"Submit\" onclick=\"document.membershipOrder.merchant.value='Free';document.membershipOrder.submit()\"></td></tr>";
                }

                if ($System->useSetting("payment_echoicon") != "Yes" and $fee_payment['echo'] == "Yes")
                {
                    echo "<script language=javascript>document.getElementById('paymenticons').style.display = 'none';document.getElementById('paymentccinfo').style.display = 'block';</script>";
                }
            }
            else
            {
                echo "<tr><td valign=top>No membership package available...</td></tr>";
            }
            echo "</table><br>";
        }
        else
        {
            echo "<table border=0 cellpadding=2 cellspacing=0 width=\"100%\" align=center><tr><td valign=top>$authorize_message";
/* webpay&digipay
if ($_SESSION['webpay_response'])
{
    if ($payment_verified == "Yes")
    {
        $Data->data = array("membership");
        $Data->value = array($membership_package);
        $Data->where = "user_id='$user_id'";
        $Data->updateData(user_accounts, 'UPDATE');
        $status = "P";
        echo "<font color=\"#0000FF\">";
    }
    else
    {
        $status = "F";
        echo "<font color=\"#FF0000\">";
    }
    echo $_SESSION['webpay_response']."</font><br><br>";


    $Data->data = array("status", "responde");
    $Data->value = array($status, addslashes($_SESSION['webpay_response']));
    $Data->where = "order_id='".$_SESSION['webpay_order_id']."'";
    $Data->updateData(user_orders, UPDATE);
}
*/
            if ($payment_verified == "Yes" or $authorizepayment == "Yes")
            {
                echo str_replace("[package]", $package, "Thank you. You have signed up for our [package] membership package. We will review the change and approve it shortly if everything is in order. Please click anywhere to continue...");
            }
            else
            {
                if ($authorize_response)
                {
                    echo "Sorry the automated recurring billing service has not been enabled. Please contact us for assistance.";
                }
                else
                {
                    echo "Sorry your payment has not been verified. If you have made payment, please contact us for assistance.";
                }
            }


            echo "</td></tr></table><br>";

        }
    } break;
    case "infoedit" :
    case "editinfo" :
    {
        if (empty($contacted))
        {
            $contacted = "N";
        }
        echo "<input type=hidden name=infotask value=\"$infotask\">"; ?>
        <table border=0 cellpadding=2 cellspacing=0 width="100%" align=center><?php
if ($System->useSetting("user_info_Summary") == "Yes")
{ ?>
        <tr><td valign=top><?php echo Summary ?>:</td><td width="39%"><textarea name=summary cols=40 rows=5><?php echo $summary ?></textarea></td></tr><?php
}
/*
if ($System->useSetting("user_info_Work Experience") == "Yes")
{ ?>
        <tr><td valign=top><?php echo "Work Experience" ?>:</td><td><textarea name=experience cols=40 rows=5><?php echo $experience ?></textarea></td></tr><?php
}
if ($System->useSetting("user_info_Interests") == "Yes")
{ ?>
        <tr><td valign=top><?php echo Interests ?>:</td><td><textarea name=interests cols=40 rows=5><?php echo $interests ?></textarea></td></tr><?php
}
if ($System->useSetting("user_info_Education") == "Yes")
{ ?>
        <tr><td valign=top><?php echo Education ?>:</td><td><textarea name=education cols=40 rows=5><?php echo $education ?></textarea></td></tr><?php
}
if ($System->useSetting("user_info_Affiliations") == "Yes")
{ ?>
        <tr><td valign=top><?php echo Affiliations ?>:</td><td><textarea name=affiliations cols=40 rows=5><?php echo $affiliations ?></textarea></td></tr><?php
} ?>    <tr><td valign=top><?php echo "Contact Settings" ?>:</td><td><table cellpadding=0 cellspacing=0 width=100%>
            <tr><td><input type=checkbox name="contact_setting[0]" value="BO" <?php echo $contact_setting[0]=="BO"?"checked":"" ?>> <?php echo "Business opportunities" ?></td></tr>
            <tr><td><input type=checkbox name="contact_setting[1]" value="CS" <?php echo $contact_setting[1]=="CS"?"checked":"" ?>> <?php echo "Consulting services" ?></td></tr>
            <tr><td><input type=checkbox name="contact_setting[2]" value="FS" <?php echo $contact_setting[2]=="FS"?"checked":"" ?>> <?php echo Friendship ?></td></tr>
            <tr><td><input type=checkbox name="contact_setting[3]" value="JO" <?php echo $contact_setting[3]=="JO"?"checked":"" ?>> <?php echo "Job offers" ?></td></tr>
            <tr><td><input type=checkbox name="contact_setting[4]" value="PN" <?php echo $contact_setting[4]=="PN"?"checked":"" ?>> <?php echo "Professional networking" ?></td></tr>
            <tr><td><input type=checkbox name="contact_setting[5]" value="UI" <?php echo $contact_setting[5]=="UI"?"checked":"" ?>> <?php echo "Useful info" ?></td></tr>
            <tr><td><br><?php echo "Want to be contacted?" ?> <input type=radio name=contacted value="N" <?php echo $contacted=="N"?"checked":"" ?>> <?php echo No ?> <input type=radio name=contacted value="Y" <?php echo $contacted=="Y"?"checked":"" ?>> <?php echo Yes ?></td></tr>
        </table></td></tr><?php
*/
        if ($module!='users' and $module!='admin' and $infotask=="add")
        {
            $fieldcheck = array("typedcaptcha");
            $fieldalert = array("Verify Code is a mandatory field. Please check and try again.");
            echo "
            <tr><td valign=top>Verify Code:</td><td><table border=0 width=300 cellpadding=0 cellspacing=0>";
            $captchafont = $System->useSetting("security_captchafont");
            if ($captchafont == "text")
            {
                $_SESSION["securitycode"] = $Data->genCaptcha();
                $securitycode = $_SESSION["securitycode"];
                echo "<input type=hidden name=storedcaptcha value=\"$securitycode\"><tr><td valign=top style=\"font-size:100%; font-weight:bold; color:#00008B; background-color:#E6E6FA; border:#B0C4DE\">&nbsp;&nbsp;$securitycode&nbsp;&nbsp;</td><td>&nbsp;</td>";
         }
            else
            {
               echo "<tr><td valign=top background-color:#000000><img id=\"imgCaptcha\" src=\"".$path["run_time_wwwloc"]."tools/createimage.php\"></td>";
            }
            echo "<td><input type=text name=typedcaptcha style=\"width:140px\">*</td></tr></table></td></tr>";
        } ?>
        <tr><td colspan="2"><br><input class=button type=submit value="<?php echo Submit ?>" onClick="return isEmpty('formlogin')"> <?php
        if($module == "users" or $module == "admin")
        {
            echo "<input class=button type=submit value=\"Cancel\" onclick=\"cancelAction()\">";
        }
        else
        {
            echo "<input class=button type=button value=\"Cancel\" onclick=\"cancelAction()\">";
        }
        echo "</td></tr></form></table><br>";
    } break;
}

/* webpay&digipay
if ($task == "webpaypayment")
{
    if ($System->useSetting("payment_webpayurl") == "L")
    {
        $webpayURL = 'https://webpay.interswitchng.com/webpay/purchase.aspx';
    }
    else
    {
        $webpayURL = 'https://webpay.interswitchng.com/webpay_pilot/purchase.aspx';
    }
    echo "<table align=center width=100% cellspacing=0 cellpadding=0><tr><td><iframe marginheight=0 marginwidth=0 frameborder=0 align=middle height=535 width=100% scrolling=no src=\"$webpayURL?MERTID=$MERTID&CADPID=$CADPID&TXNREF=$TXNREF&AMT=$AMT\"></iframe></td></tr></table>";
    $task = "membershiporder";
}
*/
?>
                <?php if($_GET['task'] == 'signup') { ?><img alt='Import from airs' title='Import users from airs' id='importfrmairs' border='0'  src='images/import_users_from_airs_btt.gif' style='position:absolute;cursor:pointer;margin-top: 0px;margin-left:510px'/><?php } ?>
<?php

if($task!="add" and $task!="update" and $task!="delete" and $task != "addinfo" and $task != "editinfo" and $task != "infoedit" and $task != "updateinfo" and $task != "membershiporder")
{
    if ($sysinfo)
    {
        echo "<tr><td colspan=3>$sysinfo</td></tr>";
    }
        ?>
        <script language="javascript" type="text/javascript">
        //mbr_usertype
            jQuery(document).ready(function($) {
            var browserName=navigator.appName;
            if (browserName=="Microsoft Internet Explorer") {
                jQuery('#showme').html("<img src='images/bigrotation.gif' alt='image' id='rotate' style='position:absolute; height:25px; margin-left:-325px;width:25px;display: none;' />");
                }
                else{
                    jQuery('#showme').html("<img src='images/bigrotation.gif' alt='image' id='rotate' style='position:absolute; height:25px; margin-left:-315px;width:25px;display: none;' />");
                }
              jQuery('#mbr_usertype').change(function() {
                  jQuery('#case_worker').val('Please Select');
                  jQuery('#case_worker_field_area').css("display", "none");
                  jQuery('#row_agency_assignment').css("display", "none");
                  jQuery('#document_group').css("display", "none");
                  jQuery('#mail_group').css("display", "none");
                  jQuery('#document_users').css("display", "none");
                  jQuery('#agency_field_area').css("display", "none");
                  jQuery('#agency_user_group_field_area').css("display", "none");
                  jQuery('#agency_user_group_field_area1').css("display", "none");
                  jQuery('#spouse_info_field_area').css("display", "none");
                  jQuery('#agency_user_client_field_area').css("display", "none");
                  jQuery('#airs_contact_id_area').css("display", "none");
                  jQuery('#CWSecurity_area').css("display", "none");
                  jQuery('#CWSecurity_areafin').css("display", "none");
                  jQuery('#CWuserform_area').css("display", "none");
                  jQuery('#CWuserform_area_m').css("display", "none");
                  jQuery('#notification_field_area').css("display", "none");
                  jQuery('#emailnotification_field_area').css("display", "none");
                  jQuery('#alert_field_area').css("display", "none");
                  jQuery('#smsnotification_field_area').css("display", "none");
                  jQuery('#carrier_field_area').css("display", "none");
                  jQuery('#agency_user_group_field_area1').css("display", "none");
                  if (browserName=="Microsoft Internet Explorer")
                  {
                       jQuery('#username_area').css("display", "block");
                       jQuery('#password_area').css("display", "block");
                       jQuery('#repassword_area').css("display", "block");
                       jQuery('#securityQstn_area').css("display", "block");
                       jQuery('#secAnswer_area').css("display", "block");

                  }
                  else
                  {
                       jQuery('#username_area').css("display", "table-row");
                       jQuery('#password_area').css("display", "table-row");
                       jQuery('#repassword_area').css("display", "table-row");
                       jQuery('#securityQstn_area').css("display", "table-row");
                       jQuery('#secAnswer_area').css("display", "table-row");
                  }

                  $current_usertype_val = jQuery('#mbr_usertype').val();
                  $current_user_type = "<?php echo getCurrentUserType()?>";
                  var $edit_user_id = "<?php echo $edit_user_id?>";
                 // showProcessing();
                 jQuery('#rotate1').show();
                 jQuery('#rotate').show();
                      jQuery.ajax({
                                  url: 'users/userflow/agencySel.php',
                                  type: "post",
                                  cache: false,
                                  data : {type: $current_usertype_val,typeid: 1, user_group_sel: jQuery('#agencygruopsel').val(),adminagencyid: jQuery('#adminagencyid').val(), current_user_type: $current_user_type,ajaxforwhat: 'agencygrp',temp_agencyidhide: jQuery('#temp_agencyidhide').val(),apgrp :jQuery('#impode_grp_id_loadap').val(),bpgrp :jQuery('#impode_grp_id_loadbp').val(), newOredit:jQuery('#newOredit').val(),cwforselctioninfo: jQuery('#cwforselction').val()},
                                  success: function(data) {

                                          if ($current_user_type =='admin')
                                          {

                                                jQuery('.adminselectGroups option').remove();
                                                jQuery('.adminselectGroups').append((data));
                                          }
                                          else
                                          {
                                                jQuery('#selgrp option').remove();
                                                jQuery('#selgrp').append((data));
                                          }
                                         // HideBar();
                                            jQuery('#rotate1').hide();
                                  }
                          });
                      jQuery.ajax({
                                  url: 'users/userflow/agencySel.php',
                                  type: "post",
                                  cache: false,
                                  data : {type: $current_usertype_val, current_user_type: $current_user_type,ajaxforwhat: 'caseworker',cwforselction: jQuery('#cwforselction').val(),impode_caseworkerArr: jQuery('#impode_caseworkerArr').val()},
                                  success: function(data) {
                                      jQuery('#case_worker option').remove();
                                      jQuery('#case_worker').append((data));
                                      jQuery('#rotate').hide();

                                  }
                          });

                  if($current_usertype_val == 'adoptive_parent' || $current_usertype_val == 'birth_parent' || $current_usertype_val == 'agency_user')
                  {
                      if($current_usertype_val == 'adoptive_parent' || $current_usertype_val == 'birth_parent')
                      {
                           if($current_user_type == 'agency' || $current_user_type == 'agency_user')
                           {
                               if (browserName=="Microsoft Internet Explorer")
                               {
                                jQuery('#notification_field_area').css("display", "block");
                                jQuery('#emailnotification_field_area').css("display", "block");
                                jQuery('#alert_field_area').css("display", "block");
                                jQuery('#smsnotification_field_area').css("display", "block");
                                jQuery('#carrier_field_area').css("display", "block");
                               }
                               else
                               {
                                jQuery('#notification_field_area').css("display", "table-row");
                                jQuery('#emailnotification_field_area').css("display", "table-row");
                                jQuery('#alert_field_area').css("display", "table-row");
                                jQuery('#smsnotification_field_area').css("display", "table-row");
                                jQuery('#carrier_field_area').css("display", "table-row");
                               }
                           }

                           if (browserName=="Microsoft Internet Explorer")
                           {
                               jQuery('#case_worker_field_area').css("display", "block");
                               jQuery('#agency_user_group_field_area').css("display", "block");
                               jQuery('#agency_user_group_field_area1').css("display", "block");
                               jQuery('#spouse_info_field_area').css("display", "block");
                               jQuery('#organization_area').css("display", "none");
                               jQuery('#title_area').css("display", "none");
                               jQuery('#website_area').css("display", "none");

                           }
                           else
                           {
                               jQuery('#case_worker_field_area').css("display", "table-row");
                               jQuery('#agency_user_group_field_area').css("display", "table-row");
                               jQuery('#agency_user_group_field_area1').css("display", "table-row");
                               jQuery('#spouse_info_field_area').css("display", "table-row");
                               jQuery('#organization_area').css("display", "none");
                               jQuery('#title_area').css("display", "none");
                               jQuery('#website_area').css("display", "none");
                           }

                      }
                      if ($current_usertype_val == 'agency_user')
                      {
                           if (browserName=="Microsoft Internet Explorer")
                           {
                               jQuery('#agency_user_client_field_area').css("display", "block");
                               jQuery('#airs_contact_id_area').css("display", "block");
                               jQuery('#CWSecurity_area').css("display", "block");
                               jQuery('#CWSecurity_areafin').css("display", "block");
                               jQuery('#CWuserform_area').css("display", "block");
                               jQuery('#CWuserform_area_m').css("display", "block");
                               jQuery('#document_group').css("display", "block");
                               jQuery('#document_users').css("display", "block");
                               jQuery('#mail_group').css("display", "none");
                               jQuery('#agency_user_group_field_area').css("display", "block");
                               jQuery('#agency_user_group_field_area1').css("display", "block");
                               jQuery('#organization_area').css("display", "block");
                               jQuery('#title_area').css("display", "block");
                               jQuery('#website_area').css("display", "block");
                           }
                           else
                           {
                               jQuery('#agency_user_client_field_area').css("display", "table-row");
                               jQuery('#airs_contact_id_area').css("display", "table-row");
                               jQuery('#CWSecurity_area').css("display", "table-row");
                               jQuery('#CWSecurity_areafin').css("display", "table-row");
                               jQuery('#CWuserform_area').css("display", "table-row");
                               jQuery('#CWuserform_area_m').css("display", "table-row");
                               jQuery('#document_group').css("display", "table-row");
                               jQuery('#document_users').css("display", "table-row");
                               jQuery('#mail_group').css("display", "table-row");
                               jQuery('#agency_user_group_field_area').css("display", "table-row");
                               jQuery('#agency_user_group_field_area1').css("display", "table-row");
                               jQuery('#organization_area').css("display", "table-row");
                               jQuery('#title_area').css("display", "table-row");
                               jQuery('#website_area').css("display", "table-row");

                           }
                      }
                      if (browserName=="Microsoft Internet Explorer")
                      {
                           jQuery('#agency_field_area').css("display", "block");
                      }
                      if (browserName!="Microsoft Internet Explorer")
                      {
                           jQuery('#agency_field_area').css("display", "table-row");
                      }
                  }
                  else if( $current_usertype_val == 'agency' || $current_usertype_val == 'attorney')
                  {
                      if (browserName=="Microsoft Internet Explorer")
                      {
                           jQuery('#row_agency_assignment').css("display", "block");
                           jQuery('#organization_area').css("display", "block");
                           jQuery('#title_area').css("display", "block");
                           jQuery('#website_area').css("display", "block");

                      }
                      else
                      {
                           jQuery('#row_agency_assignment').css("display", "table-row");
                           jQuery('#organization_area').css("display", "table-row");
                           jQuery('#title_area').css("display", "table-row");
                           jQuery('#website_area').css("display", "table-row");
                      }
                  }
                  else if( $current_usertype_val == 'child' )
                  {
                      if (browserName=="Microsoft Internet Explorer")
                      {
                           jQuery('#username_area').css("display", "none");
                           jQuery('#password_area').css("display", "none");
                           jQuery('#repassword_area').css("display", "none");
                           jQuery('#securityQstn_area').css("display", "none");
                           jQuery('#secAnswer_area').css("display", "none");
                           jQuery('#organization_area').css("display", "none");
                           jQuery('#title_area').css("display", "none");
                           jQuery('#website_area').css("display", "none");
                           jQuery('#agency_field_area').css("display", "block");
                           jQuery('#agency_user_group_field_area').css("display", "block");
                           jQuery('#agency_user_group_field_area1').css("display", "none");
                      }
                      else
                      {
                           jQuery('#username_area').css("display", "none");
                           jQuery('#password_area').css("display", "none");
                           jQuery('#repassword_area').css("display", "none");
                           jQuery('#securityQstn_area').css("display", "none");
                           jQuery('#secAnswer_area').css("display", "none");
                           jQuery('#organization_area').css("display", "none");
                           jQuery('#title_area').css("display", "none");
                           jQuery('#website_area').css("display", "none");
                           jQuery('#agency_field_area').css("display", "table-row");
                           jQuery('#agency_user_group_field_area').css("display", "table-row");
                           jQuery('#agency_user_group_field_area1').css("display", "none");
                      }
                  }

                   /*  updated by ratheesh ; for bp's settings*/
                                 if($current_usertype_val == 'birth_parent'){
                      if (browserName=="Microsoft Internet Explorer"){
                            jQuery('#edd_field_area').css("display", "block");
                            jQuery('#eddlnotification_field_area').css("display", "block");
                            jQuery('#idtypelnotification_field_area').css("display", "block");
                            jQuery('#idnolnotification_field_area').css("display", "block");
                        }else{
                           jQuery('#edd_field_area').css("display", "table-row");
                            jQuery('#eddlnotification_field_area').css("display", "table-row");
                            jQuery('#idtypelnotification_field_area').css("display", "table-row");
                            jQuery('#idnolnotification_field_area').css("display", "table-row");
                        }
                  }else{
                      jQuery('#edd_field_area').css("display", "none");
                            jQuery('#eddlnotification_field_area').css("display", "none");
                            jQuery('#idtypelnotification_field_area').css("display", "none");
                            jQuery('#idnolnotification_field_area').css("display", "none");
                  }

                  /* end of  updated by ratheesh ; for bp's settings*/



/*
                  if($current_usertype_val == 'birth_parent'){
                      if (browserName=="Microsoft Internet Explorer"){
                            jQuery('#edd_field_area').css("display", "block");
                            jQuery('#eddlnotification_field_area').css("display", "block");
                            jQuery('#idtypelnotification_field_area').css("display", "block");
                            jQuery('#idnolnotification_field_area').css("display", "block");
                        }else{
                           jQuery('#edd_field_area').css("display", "table-row");
                            jQuery('#eddlnotification_field_area').css("display", "table-row");
                            jQuery('#idtypelnotification_field_area').css("display", "table-row");
                            jQuery('#idnolnotification_field_area').css("display", "table-row");
                        }
                  }else{
                      jQuery('#edd_field_area').css("display", "none");
                            jQuery('#eddlnotification_field_area').css("display", "none");
                            jQuery('#idtypelnotification_field_area').css("display", "none");
                            jQuery('#idnolnotification_field_area').css("display", "none");
                  }  */

                });
                jQuery(document).ready(function() {
                  //jQuery('#case_worker').val('Please Select');
                  jQuery('#case_worker_field_area').css("display", "none");
                  jQuery('#document_group').css("display", "none");
                  jQuery('#mail_group').css("display", "none");
                  jQuery('#document_users').css("display", "none");
                  jQuery('#row_agency_assignment').css("display", "none");
                  jQuery('#agency_field_area').css("display", "none");
                  jQuery('#agency_user_group_field_area').css("display", "none");
                  jQuery('#agency_user_group_field_area1').css("display", "none");
                  jQuery('#spouse_info_field_area').css("display", "none");
                  jQuery('#agency_user_client_field_area').css("display", "none");
                  jQuery('#airs_contact_id_area').css("display", "none");
                  jQuery('#CWSecurity_area').css("display", "none");
                  jQuery('#CWSecurity_areafin').css("display", "none");
                  jQuery('#CWuserform_area').css("display", "none");
                  jQuery('#CWuserform_area_m').css("display", "none");
                  jQuery('#notification_field_area').css("display", "none");
                  jQuery('#emailnotification_field_area').css("display", "none");
                  jQuery('#alert_field_area').css("display", "none");
                  jQuery('#smsnotification_field_area').css("display", "none");
                  jQuery('#carrier_field_area').css("display", "none");

                  $current_usertype_val = jQuery('#mbr_usertype').val();
                  $current_user_type = "<?php echo getCurrentUserType()?>";
                  //to handle redirection from airs screen
                  var $edit_user_id = "<?php echo $edit_user_id?>";
                  var want =  1;
                  var groupvalselected1 ='<?php echo trim($mbr_groupid) ?>';
                  if (jQuery('#newOredit').val() == 'useredit' && jQuery('#cwforselction').val() == '' && groupvalselected1 )
                  {
                    want = 0;
                   }
                 // showProcessing();
                 jQuery('#rotate1').show();
                 jQuery('#rotate').show();
                    jQuery.ajax({
                              url: 'users/userflow/agencySel.php',
                              type: "post",
                              cache: false,
                              data : {type: $current_usertype_val,typeid: 1, user_group_sel: jQuery('#agencygruopsel').val(),adminagencyid: jQuery('#adminagencyid').val(), current_user_type: $current_user_type,ajaxforwhat: 'agencygrp',temp_agencyidhide: jQuery('#temp_agencyidhide').val(),apgrp :jQuery('#impode_grp_id_loadap').val(),bpgrp :jQuery('#impode_grp_id_loadbp').val(), newOredit:jQuery('#newOredit').val(),cwforselctioninfo: jQuery('#cwforselction').val()},
                              success: function(data) {

                                      if ($current_user_type =='admin')
                                      {

                                            jQuery('.adminselectGroups option').remove();
                                            jQuery('.adminselectGroups').append((data));
                                      }
                                      else
                                      {
                                    	 if(jQuery('#usr_usertypeagency').val() != 'agency'){
                                            jQuery('#selgrp option').remove();
                                            jQuery('#selgrp').append((data));
                                    	 }
                                      }
                                     // HideBar();
                                     jQuery('#rotate1').hide();
                              }
                      });
                      if (want == 1){
                  jQuery.ajax({
                                  url: 'users/userflow/agencySel.php',
                                  type: "post",
                                  cache: false,
                                  data : {type: $current_usertype_val, current_user_type: $current_user_type,ajaxforwhat: 'caseworker',cwforselction: jQuery('#cwforselction').val(),impode_caseworkerArr: jQuery('#impode_caseworkerArr').val()},
                                  success: function(data) {
                                      jQuery('#case_worker option').remove();
                                      jQuery('#case_worker').append((data));
                                      jQuery('#rotate').hide();
                                  }
                      }); }

                  if(jQuery('#cwcl_val_var').val())
                  {
                      $current_usertype_val = jQuery('#cwcl_val_var').val();
                      jQuery('#mbr_usertype').val($current_usertype_val);
                      jQuery('#cwcl_val_var').val('');
                  }
                  //
                  if($current_usertype_val == 'adoptive_parent' || $current_usertype_val == 'birth_parent' || $current_usertype_val == 'agency_user')
                  {
                      if($current_usertype_val == 'adoptive_parent' || $current_usertype_val == 'birth_parent')
                      {
                          if (browserName=="Microsoft Internet Explorer")
                           {
                              jQuery('#case_worker_field_area').css("display", "block");
                              jQuery('#agency_user_group_field_area').css("display", "block");
                              jQuery('#agency_user_group_field_area1').css("display", "block");
                              jQuery('#spouse_info_field_area').css("display", "block");
                           }
                           else
                           {
                                   jQuery('#case_worker_field_area').css("display", "table-row");
                                   jQuery('#agency_user_group_field_area').css("display", "table-row");
                                   jQuery('#agency_user_group_field_area1').css("display", "table-row");
                                   jQuery('#spouse_info_field_area').css("display", "table-row");
                            }
                          if($current_user_type == 'agency' || $current_user_type == 'agency_user')
                           {
                            if (browserName=="Microsoft Internet Explorer")
                               {
                                jQuery('#notification_field_area').css("display", "block");
                                jQuery('#emailnotification_field_area').css("display", "block");
                                jQuery('#alert_field_area').css("display", "block");
                                jQuery('#smsnotification_field_area').css("display", "block");
                                jQuery('#carrier_field_area').css("display", "block");
                               }
                               else
                               {
                                jQuery('#notification_field_area').css("display", "table-row");
                                jQuery('#emailnotification_field_area').css("display", "table-row");
                                jQuery('#alert_field_area').css("display", "table-row");
                                jQuery('#smsnotification_field_area').css("display", "table-row");
                                jQuery('#carrier_field_area').css("display", "table-row");
                               }
                           }

                      }
                      if ($current_usertype_val == 'agency_user')
                      {

                            jQuery('#agency_user_client_field_area').show();
                            jQuery('#airs_contact_id_area').show();
                            jQuery('#CWSecurity_area').show();
                            jQuery('#CWSecurity_areafin').show();
                            jQuery('#CWuserform_area').show();
                            jQuery('#CWuserform_area_m').show();
                            jQuery('#document_group').show();
                            jQuery('#document_users').show();
                            jQuery('#mail_group').show();
                            jQuery('#agency_user_group_field_area').show();
                            jQuery('#agency_user_group_field_area1').show();


                            if (browserName=="Microsoft Internet Explorer")
                            {
                            	jQuery('#CWSecurity_area').css("display", "block");
                                jQuery('#CWSecurity_areafin').css("display", "block");
                                jQuery('#CWuserform_area').css("display", "block");
                                jQuery('#CWuserform_area_m').css("display", "block");
                            }
                            else
                            {
                            	jQuery('#CWSecurity_area').css("display", "table-row");
                                jQuery('#CWSecurity_areafin').css("display", "table-row");
                                jQuery('#CWuserform_area').css("display", "table-row");
                                jQuery('#CWuserform_area_m').css("display", "table-row");
                            }

                      }
                      jQuery('#agency_field_area').css("display", "table-row");
                  }
                  else if( $current_usertype_val == 'agency' || $current_usertype_val == 'attorney')
                  {
                     jQuery('#row_agency_assignment').css("display", "table-row");
                     cur_usr_type = "<?php echo getCurrentUserType();?>";
                     if(cur_usr_type == 'agency'){
	                     if (browserName=="Microsoft Internet Explorer")
	                     {
	                   	  jQuery('#agency_user_group_field_area').css("display", "block");
	                         jQuery('#agency_user_group_field_area1').css("display", "block");
	                     }
	                     else
	                     {
	                   	  jQuery('#agency_user_group_field_area').css("display", "table-row");
	                         jQuery('#agency_user_group_field_area1').css("display", "table-row");
	                     }
                     }
                  }
                  else if( $current_usertype_val == 'child' )
                  {
                      if (browserName=="Microsoft Internet Explorer")
                      {
                           jQuery('#username_area').css("display", "none");
                           jQuery('#password_area').css("display", "none");
                           jQuery('#repassword_area').css("display", "none");
                           jQuery('#securityQstn_area').css("display", "none");
                           jQuery('#secAnswer_area').css("display", "none");
                           jQuery('#organization_area').css("display", "none");
                           jQuery('#title_area').css("display", "none");
                           jQuery('#website_area').css("display", "none");
                           jQuery('#agency_user_group_field_area').css("display", "block");
                           jQuery('#agency_user_group_field_area1').css("display", "none");
                      }
                      else
                      {
                           jQuery('#username_area').css("display", "none");
                           jQuery('#password_area').css("display", "none");
                           jQuery('#repassword_area').css("display", "none");
                           jQuery('#securityQstn_area').css("display", "none");
                           jQuery('#secAnswer_area').css("display", "none");
                           jQuery('#organization_area').css("display", "none");
                           jQuery('#title_area').css("display", "none");
                           jQuery('#website_area').css("display", "none");
                           jQuery('#agency_user_group_field_area').css("display", "table-row");
                           jQuery('#agency_user_group_field_area1').css("display", "none");
                      }
                  }


                if($current_usertype_val == 'birth_parent'){
                      if (browserName=="Microsoft Internet Explorer"){
                            jQuery('#edd_field_area').css("display", "block");
                            jQuery('#eddlnotification_field_area').css("display", "block");
                            jQuery('#idtypelnotification_field_area').css("display", "block");
                            jQuery('#idnolnotification_field_area').css("display", "block");
                        }else{
                           jQuery('#edd_field_area').css("display", "table-row");
                            jQuery('#eddlnotification_field_area').css("display", "table-row");
                            jQuery('#idtypelnotification_field_area').css("display", "table-row");
                            jQuery('#idnolnotification_field_area').css("display", "table-row");
                        }
                  }else{
                      jQuery('#edd_field_area').css("display", "none");
                            jQuery('#eddlnotification_field_area').css("display", "none");
                            jQuery('#idtypelnotification_field_area').css("display", "none");
                            jQuery('#idnolnotification_field_area').css("display", "none");
                  }



             /*      if($current_usertype_val == 'birth_parent'){
                      if (browserName=="Microsoft Internet Explorer"){
                            jQuery('#edd_field_area').css("display", "block");
                            jQuery('#eddlnotification_field_area').css("display", "block");
                            jQuery('#idtypelnotification_field_area').css("display", "block");
                            jQuery('#idnolnotification_field_area').css("display", "block");
                        }else{
                           jQuery('#edd_field_area').css("display", "table-row");
                            jQuery('#eddlnotification_field_area').css("display", "table-row");
                            jQuery('#idtypelnotification_field_area').css("display", "table-row");
                            jQuery('#idnolnotification_field_area').css("display", "table-row");
                        }
                  }else{
                      jQuery('#edd_field_area').css("display", "none");
                            jQuery('#eddlnotification_field_area').css("display", "none");
                            jQuery('#idtypelnotification_field_area').css("display", "none");
                            jQuery('#idnolnotification_field_area').css("display", "none");
                  } */


                });








                function getCaseWorkers(agencyid){

                    var webMethod   = 'http://wsdl.risin.com/staffManagement.cfc?wsdl';
                    var method      = 'getStafflist';
                    var agencyid    = agencyid;

                    $.ajax({
                       type: "POST",
                       url: "./airs/get_case_worker_list.php",
                       data: "wsdl="+webMethod+"&method="+method+"&agencyid="+agencyid+"&cairs_contact_id="+$("#airs_contact_id").val(),
                       success: function(msg){
                           $("#case_worker_col").html(msg);
                       }
                     });
                }
                $('#getAgency').change(function() {//aalert($("#"+this.id).val());
                      getCaseWorkers($("#"+this.id).val());
                });

            });
        </script>
        <tr>
                <td width=24%>Account Type:
                    <input type="hidden" name="ktask" id="ktask" value="<?php echo $task;?>">
                    <input type="hidden" name="editUser_id" id="editUser_id" value="<?php echo $edit_user_id;?>">
                </td>

                <td colspan=2>

                <?php $cwcl_val = ($_GET['cwcl_val'])?$_GET['cwcl_val']:"";?>
                <input type="hidden" id="cwcl_val_var" value="<?php echo $cwcl_val; ?>">
                <?php
                if (($task=="edit" or $task=="useredit") and ($mbr_usertype=="admin" or $mbr_usertype=="agency" or $mbr_usertype=='attorney'))
                {
                ?>
                    <input type=text name=usr_usertype style="width:180px; background-color:#CCCCCC" value="<?php echo $mbr_usertype_desc; ?>" readonly>*
                    <input type=hidden name=mbr_usertype id="mbr_usertype" value="<?php echo $mbr_usertype; ?>">
                    <input type=hidden name=usr_usertypeagency id="usr_usertypeagency" value="<?php echo $mbr_usertype; ?>">
                <?php
                }
                else
                {
                ?>
                    <select name="mbr_usertype" id="mbr_usertype" style="width:180px">
                    <?php
                    if (userGetUserType($cookie_users_userid_usermenu)=="admin")
                    {
                    ?>
                        <option value="admin" <?php echo $mbr_usertype=="admin"?"selected":"" ?>>Portal Admin</option>
                        <option value="agency" <?php echo $mbr_usertype=="agency"?"selected":"" ?>>Agency Admin</option>
                        <option value="attorney" <?php echo $mbr_usertype=="attorney"?"selected":"" ?>>Attorney</option>
                        <option value="agency_user" <?php echo $mbr_usertype=="agency_user"?"selected":"" ?>>Agency Case Worker</option>
                        <option value="adoptive_parent" <?php echo $mbr_usertype=="adoptive_parent"?"selected":"" ?>>Adoptive Parent</option>
                        <option value="birth_parent" <?php echo $mbr_usertype=="birth_parent"?"selected":"" ?>>Birth Parent</option>
                        <option value="child" <?php echo $mbr_usertype=="child"?"selected":"" ?>>Child</option>
                        <option value="other" <?php echo $mbr_usertype=="other"?"selected":"" ?>>Other</option>
                    <?php
                    }
                    else if(userGetUserType($cookie_users_userid_usermenu) == 'agency_user')
                    {
                    ?>
                        <option value="adoptive_parent" <?php echo $mbr_usertype=="adoptive_parent"?"selected":"" ?>>Adoptive Parent</option>
                        <option value="birth_parent" <?php echo $mbr_usertype=="birth_parent"?"selected":"" ?>>Birth Parent</option>
                    <?php
                    }
                    else
                    {
                    ?>
                        <option value="agency_user" <?php echo $mbr_usertype=="agency_user"?"selected":"" ?>>Agency Case Worker</option>
                        <option value="adoptive_parent" <?php echo $mbr_usertype=="adoptive_parent"?"selected":"" ?>>Adoptive Parent</option>
                        <option value="birth_parent" <?php echo $mbr_usertype=="birth_parent"?"selected":"" ?>>Birth Parent</option>
                        <option value="child" <?php echo $mbr_usertype=="child"?"selected":"" ?>>Child</option>
                        <!--<option value="agency" <?php echo $mbr_usertype=="agency"?"selected":"" ?>>Agency Admin</option>-->
                    <?php
                    }
                    ?>
                    </select>
                <?php
                }
                ?>                </td>
                <td></td>
        </tr>
        <style>
            #row_agency_assignment {
                <?php
                if($task == 'useredit' and ($mbr_usertype == 'agency' or $mbr_usertype=='attorney'))
                {
                    echo 'display: table-row';
                }
                else
                {
                    echo 'display: none;';
                }
                ?>
            }
        </style>
        <?php

//Hiding rows.
   $checkUsertype = userGetUserType($cookie_users_userid_usermenu);

if($checkUsertype=="admin"  || $checkUsertype=="attorney"  )
{
        $diplayBlock = "display:block;";
}
else
{
        $diplayBlock = "display:none;";
}



// Hiding rows ending

    ?>
        <tr id="row_agency_assignment" >
            <td ><div style="<?php echo $diplayBlock ;?>">Agency:</div></td>
            <td>
                <div style="<?php echo $diplayBlock ;?>">
                <select name="agency_assignment" id="agency_assignment">
                    <option value="0">(Not Assigned)</option>
                    <?php

                    /*$sql_agency = "
                                    SELECT user_agencies.agency_id AS agency_id, user_agencies.agency_name AS agency_name
                                    FROM user_agencies
                                    LEFT OUTER JOIN user_accounts ON user_accounts.user_id = user_agencies.user_id
                                    WHERE user_accounts.user_id IS NULL
                                    ";*/

                    $sql_agency = "
                                    SELECT user_agencies.agency_id AS agency_id, user_agencies.agency_name AS agency_name
                                    FROM user_agencies

                                    ";

                   /* if($task == 'useredit')
                    {
                        $sql_agency .= " OR user_accounts.user_id = '$edit_user_id'";
                    }*/

                    $sql_agency .= " ORDER BY agency_name";

                    $result_agency = mssql_query($sql_agency);

                    if(mssql_num_rows($result_agency) > 0)
                    {
                        while($row_agency = mssql_fetch_array($result_agency))
                        {
                            if($agency_assignment == $row_agency['agency_id'])
                            {
                                echo '<option value="'.$row_agency['agency_id'].'" selected>'. ($row_agency['agency_name']).'</option>';
                            }
                            else
                            {
                                echo '<option value="'.$row_agency['agency_id'].'">'. ($row_agency['agency_name']).'</option>';
                            }
                        }

                        mssql_free_result($result_agency);
                    }
                    ?>

                </select>       </div>     </td>
            </tr>
        <tr id="username_area">
                <td width=24%><?php echo Username ?> (Email Address):</td>
        <?php
        if ($task=="edit" or $task=="useredit")
        {
        ?>
                <td colspan="2">
                <!--<input type=text name=mbr_email style="width:180px; background-color:#CCCCCC" value="<?php echo $mbr_email; ?>" readonly>* -->
                <input type=text name=mbr_email id=mbr_email style="width:180px" value="<?php echo $mbr_email; ?>" onchange="return emailcheck()">*
                &nbsp;<span id="emailtext"> </span>
                </td>
        <?php
        }
        if ($task=='signup' or $task=="checkUsed")
        {
        ?>
                <td colspan="2">
                        <input type=text name=mbr_email id=mbr_email style="width:180px" value="<?php echo $mbr_email; ?>" onblur="return emailcheck()" onkeyup="return emailcheck()">*

                        &nbsp;<!--<a href="javascript:void(0)" onclick="return emailcheck()" >Available?&nbsp;&nbsp;</a>--><span id="emailtext"> </span>
                <?php
               // if ($msgAvail=="Yes")
                //{
                        ?>
                       <!-- <font color="green">-->
                        <?php
               // }
                //else
                //{
                        ?>
                        <!--<font color="red">-->
                        <?php
               // }
               // echo "$msgAvail";
                ?>
                <!--</font>-->                </td>

        <?php
        }
        ?>
        </tr>
       <style>
            #airs_contact_id_area {
                <?php
                if(($task == 'useredit') && $mbr_usertype == 'agency_user')
                {
                    echo 'display: table-row';
                }
                else
                {
                    echo 'display: none;';
                }
                ?>
            }
        </style>

        <tr id="airs_contact_id_area">
            <td valign="top">AIRS Case Worker:</td>
            <td colspan=2 id="case_worker_col">
                <?php
                    // Get cc_account_key to be passed to the web service that gets the case workers list
                    $Data->data             = array("c_account_key");
                    $Data->where            = "agency_id='$mbr_agencyid'";
                    $Data->order            = "";
                    $keyresult              = $Data->getData(user_agencies);
                    if($myarow = mssql_fetch_row($keyresult))
                    {
                        $cairs_account_key  = $myarow[0];
                    }
                    mssql_free_result($keyresult);

                    // Get webservice class
                    require_once($path["serloc"].'/airs/webservice/services.php');
                    $param                  = array(
                                                'authenticationKey' => 'samplekey',
                                                'agencyID'            => $cairs_account_key
                                                );
                    // Call the webservice class with parameters wsdl link, parameter array and method to be called
                    $case_workers           = getservice("http://wsdl.risin.com/staffManagement.cfc?wsdl", $param, 'getStafflist');

                    $staff_arr              = array();
                    if($case_workers['root']['STAFF'])
                    {
                        foreach($case_workers as $case_worker){
                            foreach($case_worker['STAFF'] as $staffs)
                                $staff_arr[]    = $staffs;
                        }
                        if(is_array($staff_arr[0]))
                        {
                ?>
                            <select name="airs_contact_id" id="airs_contact_id" style="width:200px">
                                <option value="">Please Select</option>
                                <?php foreach($staff_arr as $staff){ ?>
                                    <option value="<?php echo $staff['CONTACTAIRSID']; ?>" <?php echo $airs_contact_id==$staff['CONTACTAIRSID']?"selected":"" ?>><?php echo $staff['STAFFNAME']; ?></option>
                                <?php } ?>
                            </select>
                <?php
                        }
                        else
                        {

                ?>
                            <select name="airs_contact_id" id="airs_contact_id" style="width:200px">
                                <option value="">Please Select</option>
                                <option value="<?php echo $staff_arr[1]; ?>" <?php echo $cairs_contact_id==$staff_arr[1]?"selected":"" ?>><?php echo $staff_arr[3]; ?></option>
                            </select>
                <?

                        }

                    }
                    else
                    {
                ?>
                        <select name="airs_contact_id" id="airs_contact_id" style="width:200px">
                            <option value="">Please Select</option>
                        </select>
                <?php
                    }
                ?>
            </td>
        </tr>
        <tr id="password_area">
                <td><?php echo Password ?>:</td>
                <td colspan=2>
                    <input type="hidden" id="decrypt_pswd" name="decrypt_pswd" value="<?php echo htmlspecialchars( ($viewpassword)); ?>" />
                        <input id="mbr_password" type=password name=mbr_password style="width:180px" value="<?php echo $mbr_password; ?>">* &nbsp;<span id = "showPass"></span>
        </tr>
        <tr id="repassword_area">
                <td><?php echo "Retype Password" ?>:</td>
                <td colspan=2>
                        <input type=password name=mbr_repassword style="width:180px" value="<?php echo $mbr_repassword; ?>">*                </td>
        </tr>
        <tr id="securityQstn_area">
                <td>Security Question:</td>
                <td colspan=2>
                    <select name="mbr_question" style="width:200px">
                        <option value="Mother's birthplace" <?php echo $mbr_question=="Mother's birthplace"?"selected":"" ?>>Mother's birthplace</option>
                        <option value="Best childhood friend" <?php echo $mbr_question=="Best childhood friend"?"selected":"" ?>>Best childhood friend</option>
                        <option value="Name of first pet" <?php echo $mbr_question=="Name of first pet"?"selected":"" ?>>Name of first pet</option>
                        <option value="Favorite teacher" <?php echo $mbr_question=="Favorite teacher"?"selected":"" ?>>Favorite teacher</option>
                        <option value="Favorite historical person" <?php echo $mbr_question=="Favorite historical person"?"selected":"" ?>>Favorite historical person</option>
                        <option value="Grandfather's occupation" <?php echo $mbr_question=="Grandfather's occupation"?"selected":"" ?>>Grandfather's occupation</option>
                    </select>*                </td>
        </tr>
        <tr id="secAnswer_area">
                <td>Your Answer:</td>
                <td colspan=2>
                        <input type=text name=mbr_answer style="width:180px" value="<?php echo trim($mbr_answer); ?>">*</td>
        </tr>
        <style>
            #CWSecurity_area {
                <?php
                if(($task == 'useredit') && $mbr_usertype == 'agency_user')
                {
                    echo 'display: table-row';
                }
                else
                {
                    echo 'display: none;';
                }
                ?>
            }

            #CWSecurity_areafin {
                <?php
                if(($task == 'useredit') && $mbr_usertype == 'agency_user')
                {
                    echo 'display: table-row';
                }
                else
                {
                    echo 'display: none;';
                }
                ?>
            }

            #CWuserform_area {
                <?php
                if(($task == 'useredit') && $mbr_usertype == 'agency_user')
                {
                    echo 'display: table-row';
                }
                else
                {
                    echo 'display: none;';
                }
                ?>
            }
          #CWuserform_area_m {
                <?php
                if(($task == 'useredit') && $mbr_usertype == 'agency_user')
                {
                    echo 'display: table-row';
                }
                else
                {
                    echo 'display: none;';
                }
                ?>
            }

        </style>
        <?php
            if(!$edit_user_id)
            {
                $agencyID = $_SESSION['session_logged_user_id'];
                $agencyIDFullVal = $agencyID."_ag_cwsecurity";
                $agencyIDFullValFin = $agencyID."_ag_cwsecurityfinancial";
                $sqlqy = "SELECT useroption, setting FROM system_settings WHERE useroption ='".$agencyIDFullVal."'";
                $result_sel = mssql_query($sqlqy);
                $caseSecurityVal = "";

                $sqlqyfin = "SELECT useroption, setting FROM system_settings WHERE useroption ='".$agencyIDFullValFin."'";
                $result_selFin = mssql_query($sqlqyfin);
                $caseSecurityValFin = "";

                if(mssql_num_rows($result_sel) > 0)
                    {
                        $value = mssql_fetch_array($result_sel);
                        $caseSecurityVal = $value[1];
                    }
                if (trim($caseSecurityVal) == "")
                {

                    $caseSecurityVal = '3';
                }
                mssql_free_result($result_sel);

                if(mssql_num_rows($result_selFin) > 0)
                    {
                        $valuefin = mssql_fetch_array($result_selFin);
                        $caseSecurityValFin = $valuefin[1];
                    }
                if (trim($caseSecurityValFin) == "")
                {

                    $caseSecurityValFin = '1';
                }
                mssql_free_result($result_selFin);
                $dispalyyes        = '';
                $dispalyno        = 'checked="checked"';

                $dispalyyes_m        = '';
                $dispalyno_m        = 'checked="checked"';
            }
            else
            {
                $cwIDFullVal = $edit_user_id."_cw_cwsecurity";
                $sqlcwqy     = "SELECT useroption, setting FROM system_settings WHERE useroption ='".$cwIDFullVal."'";
                $result_cw   = mssql_query($sqlcwqy);
                if(mssql_num_rows($result_cw))
                {
                    $cw_value = mssql_fetch_array($result_cw);
                    $caseSecurityVal = $cw_value[1];
                    mssql_free_result($result_cw);
                }
                else
                {
                    $agencyID = $_SESSION['session_logged_user_id'];
                    $agencyIDFullVal = $agencyID."_ag_cwsecurity";
                    $sqlqy = "SELECT useroption, setting FROM system_settings WHERE useroption ='".$agencyIDFullVal."'";
                    $result_sel = mssql_query($sqlqy);
                    $caseSecurityVal = "";
                    if(mssql_num_rows($result_sel) > 0)
                    {
                            $value = mssql_fetch_array($result_sel);
                            $caseSecurityVal = $value[1];
                    }
                    if (trim($caseSecurityVal) == "")
                    {

                        $caseSecurityVal = '3';
                    }
                    mssql_free_result($result_sel);
                }



                $cwIDFullValFin = $edit_user_id."_cw_cwsecurityfinancial";
                $sqlcwqy     	= "SELECT useroption, setting FROM system_settings WHERE useroption ='".$cwIDFullValFin."'";
                $result_cwfin  	= mssql_query($sqlcwqy);
                if(mssql_num_rows($result_cwfin))
                {
                    $cw_valuefin = mssql_fetch_array($result_cwfin);
                    $caseSecurityValFin = $cw_valuefin[1];
                    mssql_free_result($result_cwfin);
                }
                else
                {
                    $agencyID = $_SESSION['session_logged_user_id'];
                    $agencyIDFullValFin = $agencyID."_ag_cwsecurityfinancial";
                    $sqlqy = "SELECT useroption, setting FROM system_settings WHERE useroption ='".$agencyIDFullVal."'";
                    $result_selfin = mssql_query($sqlqy);
                    $caseSecurityValFin = "";
                    if(mssql_num_rows($result_selfin) > 0)
                    {
                            $value = mssql_fetch_array($result_selfin);
                            $caseSecurityValFin = $value[1];
                    }
                    if (trim($caseSecurityValFin) == "")
                    {

                        $caseSecurityValFin = '1';
                    }
                    mssql_free_result($result_selfin);
                }

                $sqlqy = "SELECT useroption, setting FROM system_settings WHERE useroption ='".$agencyIDFullVal."'";
                $result_selfin = mssql_query($sqlqy);

                $caseIDFullValForm  = $edit_user_id."_cw_userforms";
                $sqlformqry         = "SELECT useroption, setting FROM system_settings WHERE useroption ='".$caseIDFullValForm."'";
                $result_from        = mssql_query($sqlformqry);
                if(mssql_num_rows($result_from) > 0)
                {
                    $form_value     = mssql_fetch_array($result_from);
                    $dispalyyes     = ($form_value[1]=='yes')?'checked="checked"':'';
                    $dispalyno      = ($form_value[1]=='no')?'checked="checked"':'';
                }
                else
                {
                    $dispalyyes        = '';
                    $dispalyno        = 'checked="checked"';
                }

                $caseIDFullValForm  = $edit_user_id."_cw_matching";
                $sqlformqry         = "SELECT useroption, setting FROM system_settings WHERE useroption ='".$caseIDFullValForm."'";
                $result_from        = mssql_query($sqlformqry);
                if(mssql_num_rows($result_from) > 0)
                {
                    $form_value     = mssql_fetch_array($result_from);
                    $dispalyyes_m     = ($form_value[1]=='yes')?'checked="checked"':'';
                    $dispalyno_m      = ($form_value[1]=='no')?'checked="checked"':'';
                }
                else
                {
                    $dispalyyes_m        = '';
                    $dispalyno_m        = 'checked="checked"';
                }
            }

            //$dispalyyes	   = ($this->getAPDetails->apdetails[0]['apCommunication']!='F')?'checked="checked"':'';


        ?>

        <tr id="CWSecurity_area">
                <td>Caseworker security:</td>
                <td colspan=2>
                    <select id="caseSecrity" name="caseSecurity"  style="width:200px;">
                        <?php
                        foreach (getCaseworkerSecurity() as $caseSecrity)
                        {
                        ?>
                         <option <?php echo $caseSecurityVal==$caseSecrity[0]?"selected":"" ?> value="<?php echo $caseSecrity[0];?>"><?php echo $caseSecrity[1];?></option>
                        <?php
                        }
                        ?>
		    		</select><span style="font-size: 11px; font-weight: bold;">
                            User Managment<img id="secInfo" onmouseover="taskDetShow(this)" onmouseout= "taskDetremove()" style="position:relative;left:10px;top:3px"src="images/q_1.png">
                    </span>
                </td>

        </tr>

        <tr id="CWSecurity_areafin">
                <td>Caseworker security:</td>
                <td colspan=2 >
                    <select id="financial" name="financial"  style="width:200px;">
                        <?php
                        foreach (getCaseworkerSecurityFinancial() as $caseSecrityFin)
                        {
                        ?>
                         <option <?php echo $caseSecurityValFin==$caseSecrityFin[0]?"selected":"" ?> value="<?php echo $caseSecrityFin[0];?>"><?php echo $caseSecrityFin[1];?></option>
                        <?php
                        }
                        ?>
		    		</select><span style="font-size: 11px; font-weight: bold;">
                            	Financial Managment
                    		</span>
                </td>

        </tr>
        <tr id="CWuserform_area_m">
                <td>Caseworker security:</td>
                <td colspan=2>
                    <input type="radio" id="cwMatchingyes" name="cwMatching" value="yes" <?php echo $dispalyyes_m ?> style=""/> Yes
                    <input type="radio" id="cwMatchingno" name="cwMatching" value="no" <?php echo $dispalyno_m ?> style=""/> No
                    <span style="font-size: 11px; font-weight: bold;margin-left:112px">
                            	Placement Managment
                    		</span>
                </td>

        </tr>
        <tr id="CWuserform_area">
                <td>Display in user detail record:</td>
                <td colspan=2>
                    <input type="radio" id="caseuserformsyes" name="caseuserforms" value="yes" <?php echo $dispalyyes ?> style=""/> Yes
                    <input type="radio" id="caseuserformsno" name="caseuserforms" value="no" <?php echo $dispalyno ?> style=""/> No
                </td>

        </tr>

<?php

if ($mbr_usertype!="admin")
{
?>
		<tr	id= "mail_group">
                <td>Mail to Groups:</td>
                <td colspan=2>
                        <input type="checkbox" name="mailtogroups" id="mailtogroups" value="Y" <?php echo (trim($mailtogroups == "Y")?"checked":"") ?> />
                </td>
        </tr>

		<tr id = "document_group">
                <td>Post document to Groups:</td>
                <td colspan=2>
                        <input type="checkbox" name="documenttogroups" id="documenttogroups" value="Y" <?php echo (trim($doctogroups == "Y")?"checked":"") ?> />
                </td>
        </tr>

        <tr id = "document_users">
                <td>Post document to Users:</td>
                <td colspan=2>
                        <input type="checkbox" name="documenttousers" id="documenttousers" value="Y" <?php echo (trim($doctousers == "Y")?"checked":"") ?> />
                </td>
        </tr>
        <style>
            #case_worker_field_area {
                <?php
                if($task == 'useredit' && $mbr_usertype == 'adoptive_parent')
                {
                    echo 'display: table-row';
                }
                else
                {
                    echo 'display: none;';
                }
                ?>
            }
        </style>
        <?php
            $grp_ids            = array();
            $caseworkerArr      = array();
         ?>
        <tr id="case_worker_field_area">
            <td valign="top">Case Worker:</td>
            <td colspan=2 >
                <select name="caseworker[]" multiple id="case_worker" style="width:180px;Height:180px;" >
                    <!--<option value="0">Please Select</option>-->
                    <?php
                    $sql = "SELECT user_id, first_name + ' ' + last_name AS fullname, case_worker_parent_user_id,group_id FROM user_accounts WHERE user_type = 'agency_user' and status <> 'Delete'  ORDER BY  fullname";
                    $result = mssql_query($sql);
                    $sql2="select caseworkerid from caseworker_client where parentid=".$edit_user_id."";
                    $result2 = mssql_query($sql2);
                    //$row2 = mssql_fetch_array($result2);
                    //$mbr_ap_id="";
                    $current_agencyID   = (getCurrentUserType() == 'agency_user')?getUserIDByAgencyID(getCurrentCaseWorkerAgencyID($login_social_user_id)):$login_social_user_id;
                    $cworker_id = array();
                    while($row2 = mssql_fetch_array($result2))
                    {
                        array_push($cworker_id, trim($row2[0]));
                        //if (strlen($mbr_ap_id)>0)
                        //{
                           // $mbr_ap_id .= ",";
                       // }
                       // $mbr_ap_id .= trim($cwrow[0]);
                    }
                    $cwforselction = implode(',',$cworker_id);
                    if(mssql_num_rows($result) > 0)
                    {
                        while($row = mssql_fetch_array($result))
                        {
                            if(userGetUserType($cookie_users_userid_usermenu) == 'admin' or ((userGetUserType($cookie_users_userid_usermenu) == 'agency' or userGetUserType($cookie_users_userid_usermenu)=='attorney' or getCurrentUserType() == 'agency_user') and $current_agencyID == $row['case_worker_parent_user_id']))
                            {
                                $caseworkerArr[] = $row['user_id'];
                                if (trim($row['group_id']) !='')
                                {
                                    $grp_ids[] = $row['group_id'];
                                }
                                if(in_array($row['user_id'],$cworker_id))
                                {
                                ?>
                                    <option value="<?=$row['user_id']?>" selected="selected"><?= ($row['fullname'])?></option>
                                <?
                                }
                                else
                                {
                                ?>
                                    <option value="<?=$row['user_id']?>"><?= ($row['fullname'])?></option>
                                <?
                                }
                            }
                        }
                        mssql_free_result($result);
                    }

                    ?>
            </select>
                <?php
                    //print_r($caseworkerArr);
                    //print_r($grp_ids);

                    $grp_ids = array_filter($grp_ids, 'strlen');
                    $impode_grp_id = implode(',',$grp_ids);
                    $grp_ids = explode(',',$impode_grp_id);
                    $grp_ids = array_unique($grp_ids);
                    $impode_groups = implode(',',$grp_ids);
                   // print_r($grp_ids);

                    $impode_caseworkerArr = implode(',',$caseworkerArr);
                    //print_r($impode_caseworkerArr);

                ?>

            <input type="hidden" id="impode_groups" name="impode_groups" value="<?php echo $impode_groups; ?>" />
            <input type="hidden" id="impode_caseworkerArr" name="impode_caseworkerArr" value="<?php echo $impode_caseworkerArr; ?>" />
            <input type="hidden" id="cwforselction" name="cwforselction" value="<?php echo $cwforselction; ?>" />
            </td>
            <td id="showme">

            </td>


        </tr>
        <style>
            #agency_field_area {
                <?php
                if($task == 'useredit' && ($mbr_usertype == 'adoptive_parent' || $mbr_usertype == 'birth_parent' || $mbr_usertype == 'agency_user'))
                {
                    echo 'display: table-row';
                }
                else
                {
                    echo 'display: none;';
                }
                ?>
            }
        </style>


        <tr id="agency_field_area">
                <td  valign="top"><div style="<?php echo $diplayBlock;?>" >Agency:</div></td>
                <td  valign="top">
                    <table style="<?php echo $diplayBlock ;?> " > <tr><td>
                <?php
                        //$mbr_agency_id = explode(",",$mbr_agencyid);
                        //$mbr_group_id = explode(",",$mbr_groupid);
                        if (userGetUserType($cookie_users_userid_usermenu)=="admin")
                        {

                            echo "<select id=getAgency name=mbr_agencygroup[] onchange=\"showUserGroup(this.value)\">";
                            echo "<option value=\"0\"></option>";
                            $Data->data = array("agency_id", "agency_name");
                            $Data->where = "";
                            $Data->order = "";
                            $agencyresult = $Data->getData(user_agencies);
                            while($agencyrow=mssql_fetch_row($agencyresult))
                            {
                                echo "<option value=\"$agencyrow[0]\" ".(@in_array($agencyrow[0],$mbr_agency_id)?"selected":"").">". ($agencyrow[1])."</option>";
                            }
                            mssql_free_result($agencyresult);
                            echo "</select>";
                        ?>
                        <?php
                        }
                        else if (userGetUserType($login_social_user_id)=="agency_user")
                        {


                        $sql = "SELECT user_agencies.agency_id,user_agencies.agency_name FROM user_accounts LEFT JOIN user_agencies ON  user_accounts.case_worker_parent_user_id = user_agencies.user_id WHERE user_accounts.user_id = '$login_social_user_id'";
                    $result = mssql_query($sql);

                    if($agencyrow=mssql_fetch_row($result))
                            {
                                $temp_agencyid = $agencyrow[0];
                                $agency_name =  ($agencyrow[1]);
                            }
                        ?>
                            <input type=text name=agency_name style="width:180px; background-color:#CCCCCC" value="<?php echo $agency_name; ?>" readonly>*
                            <input type=hidden name=mbr_agencygroup value="<?php echo $temp_agencyid; ?>">
                        <?php
                        }
                        else
                        {

                            $Data->data = array("agency_id", "agency_name");
                            $Data->where = "user_id='".$cookie_users_userid_usermenu."'";
                            $Data->order = "";
                            $agencyresult = $Data->getData(user_agencies);
                            if($agencyrow=mssql_fetch_row($agencyresult))
                            {
                                $temp_agencyid = $agencyrow[0];
                                $agency_name =  ($agencyrow[1]);
                            }
                        ?>
                            <input type=text name=agency_name style="width:180px; background-color:#CCCCCC" value="<?php echo $agency_name; ?>" readonly>*
                            <input type=hidden name=mbr_agencygroup value="<?php echo $temp_agencyid; ?>">
                        <?php
                        }
                        ?>
                       </td></tr></table>
                </td>

        </tr>
        <?php
        if (userGetUserType($cookie_users_userid_usermenu)=="admin")
        {
?>
<script type="text/javascript">

function usersDataServent()
{};

usersDataServent.prototype.initialize = function()
{
    try {
        // Mozilla / Safari
        this._xh = new XMLHttpRequest();
    } catch (e) {
        // Explorer
        var _ieModel = new Array(
        'MSXML2.XMLHTTP.5.0',
        'MSXML2.XMLHTTP.4.0',
        'MSXML2.XMLHTTP.3.0',
        'MSXML2.XMLHTTP',
        'Microsoft.XMLHTTP'
        );
        var success = false;
        for (var i=0;i < _ieModel.length && !success; i++)
        {
            try {
                this._xh = new ActiveXObject(_ieModel[i]);
                success = true;
            } catch (e) {
                // Manage the exceptions
            }
        }
        if ( !success )
        {
            // Manage the exceptions, while alert
            return false;
        }
        return true;
    }
}

usersDataServent.prototype.occupy = function()
{
    beenActual = this._xh.readyState;
    return (beenActual && (beenActual < 4));
}

usersDataServent.prototype.process = function()
{
    if (this._xh.readyState == 4 && this._xh.status == 200)
    {
        this.processdo = true;
    }
}

usersDataServent.prototype.sending = function(urlget,data)
{
    if (!this._xh)
    {
        this.initialize();
    }
    if (!this.occupy())
    {
        this._xh.open("GET",urlget,false);
        this._xh.send(data);
        if (this._xh.readyState == 4 && this._xh.status == 200)
        {
            return this._xh.responseText;
        }
    }
    return "<select id=selectClient multiple name=mbr_client[] style=\"width:180px;Height:180px;\" onchange=usersSetClient()><option value=>000</option></select>";
}

// Call the getClients.php
function usersSetClient(users_id, agency_id)
{
    var selectedArray = new Array();
    var selObj = document.getElementById('selectClient');
    var i;
    var count = 0;
    for (i=0; i<selObj.options.length; i++) {
        if (selObj.options[i].selected) {
          selectedArray[count] = selObj.options[i].value;
          count++;
        }
    }
    document.getElementById('ap_id').value = selectedArray.join();
}

// Call the getClients.php
function usersShowClient(agency_id, users_id)
{
    remote = new usersDataServent;
    message = remote.sending('<?php echo $path["run_time_wwwloc"]?>users/getClients.php?a_id='+agency_id+'&id='+users_id);
    document.getElementById('clientText').innerHTML = message;
    return false;
}


function showUserGroup(str)
{
    var selectedArray = new Array();
    var selObj = document.getElementById('getAgency');
    var i;
    var count = 0;
    var a_id = 0;
    for (i=0; i<selObj.options.length; i++) {
        if (selObj.options[i].selected) {
          selectedArray[count] = selObj.options[i].value;
          a_id = selObj.options[i].value;
          count++;
        }
    }
    usersShowClient(a_id, <?php echo ($edit_user_id?$edit_user_id:0) ?>);

    if (selectedArray.length==0)
    {
        document.getElementById("txtHint").innerHTML="<select id=selectGroups multiple name=mbr_usergroup[] style='width:180px;Height:180px;' onchange=setGroup()><option value=></option></select>";
        return;
    }
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
            setGroup();
        }
    }
    //document.write(selectedArray.join('-'));
    xmlhttp.open("GET","<?php echo $path["run_time_wwwloc"]?>users/getusergroup.php?a="+selectedArray.join('-')+"&g=<?php echo str_replace(",","-",$mbr_groupid)?>",true);
    xmlhttp.send();


}
function setGroup()
{

    var selectedArray = new Array();
    var selObj = document.getElementById('selectGroups');
    var i;
    var count = 0;
    for (i=0; i<selObj.options.length; i++) {
        if (selObj.options[i].selected) {
          selectedArray[count] = selObj.options[i].value;
          count++;
        }
    }
    document.getElementById('user_group_id').value = selectedArray.join();
}
</script>
<?php
        } //end if (userGetUserType($cookie_users_userid_usermenu)=="admin")
    if ($mbr_usertype!='attorney')
    {
?>
        <style>
            #agency_user_group_field_area {
                <?php
                if($task == 'useredit' && ($mbr_usertype == 'adoptive_parent' || $mbr_usertype == 'birth_parent' || $mbr_usertype == 'agency_user'))
                {
                    echo 'display: table-row';
                }
                else
                {
                    echo 'display: none;';
                }
                ?>
            }
            #agency_user_group_field_area1 {
                <?php
                if($task == 'useredit' && ($mbr_usertype == 'adoptive_parent' || $mbr_usertype == 'birth_parent' || $mbr_usertype == 'agency_user'))
                {
                    echo 'display: table-row';
                }
                else
                {
                    echo 'display: none;';
                }
                ?>
            }
        </style>
        <script>
            var typeval = jQuery('#mbr_usertype').val();
        </script>
        <tr id="agency_user_group_field_area">
                <td valign="top" width=24%>Agency User Group:
                    <input type="hidden" id="newOredit" name="newOredit" value="<?php echo $_REQUEST['task'] ?>" />
                    <input type="hidden" id="user_group_id" name="mbr_user_group" value="<?php echo $mbr_group_id ?>" />
                    <input type="hidden" id="agencygruopsel" name="agencygruopsel" value="<?php echo $mbr_groupid ?>" />
                    <input type="hidden" id="adminagencyid" name="adminagencyid" value="<?php echo $mbr_agencyid ?>" />
                    <input type="hidden" id="temp_agencyidhide" name="temp_agencyidhide" value="<?php echo $temp_agencyid ?>" />
                </td>
                <td colspan=2>

                <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td valign="top" id="txtHint">
                            <?php
                            if (userGetUserType($cookie_users_userid_usermenu)=="admin")
                            {
                                echo "<select id=selectGroups class=adminselectGroups multiple name=mbr_usergroup[] style=\"width:180px;Height:180px;\" onchange=setGroup()>";
                                $Data->data = array("group_id", "group_name");
                                $user_where ="";
                                if (count($mbr_agency_id)>0)
                                {
                                    for ($i=0;$i<count($mbr_agency_id);$i++)
                                    {
                                        if (trim($mbr_agency_id[$i])!="")
                                        {
                                            if ($i>0)
                                            {
                                                $user_where .= " or ";
                                            }
                                            $user_where .= "agency_id = '".$mbr_agency_id[$i]."'";
                                        }
                                    }
                                }
                                $Data->where = "$user_where"." AND (group_type_id=1 OR group_type_id = 3)";
                                $Data->order = "agency_id asc, group_name asc";
                                $agencyresult = $Data->getData(user_groups);

                                while($agencyrow=mssql_fetch_row($agencyresult))
                                {
                                    $adminselectGroups = $adminselectGroups.','. ($agencyrow[1]);
                                    echo "<option value=\"$agencyrow[0]\" ".(@in_array($agencyrow[0],$mbr_group_id)?"selected":"").">". ($agencyrow[1])."</option>";
                                }

                                mssql_free_result($agencyresult);
                                echo "</select>";

                            }
                            else
                            {


                                echo "<select multiple id=selgrp name=mbr_usergroup[] style=\"width:180px;Height:180px;\">";

                                $Data->data = array("group_id", "group_name");
                                $Data->where = "agency_id='".$temp_agencyid."' AND (group_type_id=1 OR group_type_id = 3) ";
                                $Data->order = "group_name asc";
                                $agencyresult = $Data->getData(user_groups);

                                while($agencyrow=mssql_fetch_row($agencyresult))
                                {
                                    echo "<option value=\"$agencyrow[0]\" ".(@in_array($agencyrow[0],$mbr_group_id)?"selected":"").">". ($agencyrow[1])."</option>";
                                }
                                mssql_free_result($agencyresult);
                                echo "</select>";
                            }
                            ?>
                        <?php
                            /* ##################### For Adoptive parent Array ################ */
                            $grp_id_loadap = array();
                            if (userGetUserType($cookie_users_userid_usermenu)=="admin")
                                    {
                                    $Data->data = array("group_id", "group_name");
                                    $user_where ="";
                                    if (count($mbr_agency_id)>0)
                                    {
                                    for ($i=0;$i<count($mbr_agency_id);$i++)
                                    {
                                    if (trim($mbr_agency_id[$i])!="")
                                    {
                                        if ($i>0)
                                        {
                                            $user_where .= " or ";
                                        }
                                        $user_where .= "agency_id = '".$mbr_agency_id[$i]."'";
                                    }
                                    }
                                    }
                                    $Data->where = "$user_where"." AND (group_type_id=1 OR group_type_id = 3) AND (AIRSContactTypeId=1 or AIRSContactTypeId=3)";
                                    $Data->order = "agency_id asc, group_name asc";
                                    $agencyresult = $Data->getData(user_groups);

                                    while($agencyrow=mssql_fetch_row($agencyresult))
                                    {
                                        if(in_array($agencyrow[0],$grp_ids))
                                        {
                                                $grp_id_loadap[] = $agencyrow[0];
                                        }
                                    }

                                    mssql_free_result($agencyresult);

                                    }
                                    else
                                    {
                                    $Data->data = array("group_id", "group_name");
                                    $Data->where = "agency_id='".$temp_agencyid."' AND (group_type_id=1 OR group_type_id = 3) AND (AIRSContactTypeId=1 or AIRSContactTypeId=3) ";
                                    $Data->order = "group_name asc";
                                    $agencyresult = $Data->getData(user_groups);

                                    while($agencyrow=mssql_fetch_row($agencyresult))
                                    {
                                        if(in_array($agencyrow[0],$grp_ids))
                                        {
                                                $grp_id_loadap[] = $agencyrow[0];
                                        }
                                    }
                                    mssql_free_result($agencyresult);
                            }
                           $impode_grp_id_loadap = implode(',',$grp_id_loadap);
                         ?>
                         <?php
                            /* ##################### For Birth parent Array ################ */
                            $grp_id_loadbp = array();
                            if (userGetUserType($cookie_users_userid_usermenu)=="admin")
                                    {
                                    $Data->data = array("group_id", "group_name");
                                    $user_where ="";
                                    if (count($mbr_agency_id)>0)
                                    {
                                    for ($i=0;$i<count($mbr_agency_id);$i++)
                                    {
                                    if (trim($mbr_agency_id[$i])!="")
                                    {
                                        if ($i>0)
                                        {
                                            $user_where .= " or ";
                                        }
                                        $user_where .= "agency_id = '".$mbr_agency_id[$i]."'";
                                    }
                                    }
                                    }
                                    $Data->where = "$user_where"." AND (group_type_id=1 OR group_type_id = 3) AND (AIRSContactTypeId=5 or AIRSContactTypeId=3)";
                                    $Data->order = "agency_id asc, group_name asc";
                                    $agencyresult = $Data->getData(user_groups);

                                    while($agencyrow=mssql_fetch_row($agencyresult))
                                    {
                                        if(in_array($agencyrow[0],$grp_ids))
                                        {
                                                $grp_id_loadbp[] = $agencyrow[0];
                                        }
                                    }

                                    mssql_free_result($agencyresult);

                                    }
                                    else
                                    {
                                    $Data->data = array("group_id", "group_name");
                                    $Data->where = "agency_id='".$temp_agencyid."' AND (group_type_id=1 OR group_type_id = 3) AND (AIRSContactTypeId=5 or AIRSContactTypeId=3) ";
                                    $Data->order = "group_name asc";
                                    $agencyresult = $Data->getData(user_groups);

                                    while($agencyrow=mssql_fetch_row($agencyresult))
                                    {
                                        if(in_array($agencyrow[0],$grp_ids))
                                        {
                                                $grp_id_loadbp[] = $agencyrow[0];
                                        }
                                    }
                                    mssql_free_result($agencyresult);
                            }
                            $impode_grp_id_loadbp = implode(',',$grp_id_loadbp);
                         ?>
                         <input type="hidden" id="impode_grp_id_loadbp" name="impode_grp_id_loadbp" value="<?php echo $impode_grp_id_loadbp; ?>" />
                         <input type="hidden" id="impode_grp_id_loadap" name="impode_grp_id_loadap" value="<?php echo $impode_grp_id_loadap; ?>" />

                        </td>
                        <td valign="top">
                            <table>
                                <tr>
                                    <td>
                                        <span style="float: left; padding: 20px 0px 0px 10px; font-size: 11px; font-weight: bold;">
                                        Hold CTRL and click to select</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="position:relative;height:40px"> <!-- empty --> </div>
                                        <img src='images/bigrotation.gif' alt="image" id='rotate1' style="position:relative; height:25px; margin-left:10px;width:25px;display: none;" />
                                    </td>
                                </tr>
                            </table>
                        </td>
                       </tr>
                   </table></td>
        </tr>

        <tr id="agency_user_group_field_area1">
                <td valign="top" width=24%>Message User Group:<input type="hidden" id="mbr_message_group" name="mbr_message_group" value="<?php echo $mbr_message_id ?>" /></td>
                <td colspan=2>

                <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td valign="top" id="txtHint">
                            <?php
                            if (userGetUserType($cookie_users_userid_usermenu)=="admin")
                            {
                                echo "<select id=selectGroups multiple name=mbr_messagegroup[] style=\"width:180px;Height:180px;\" onchange=setGroup()>";
                                $Data->data = array("group_id", "group_name");
                                $user_where ="";
                                if (count($mbr_agency_id)>0)
                                {
                                    for ($i=0;$i<count($mbr_agency_id);$i++)
                                    {
                                        if (trim($mbr_agency_id[$i])!="")
                                        {
                                            if ($i>0)
                                            {
                                                $user_where .= " or ";
                                            }
                                            $user_where .= "agency_id = '".$mbr_agency_id[$i]."'";
                                        }
                                    }
                                }
                                $Data->where = "$user_where"." AND (group_type_id= 2 OR group_type_id = 3)";
                                $Data->order = "agency_id asc, group_name asc";
                                $agencyresult = $Data->getData(user_groups);

                                while($agencyrow=mssql_fetch_row($agencyresult))
                                {
                                    echo "<option value=\"$agencyrow[0]\" ".(@in_array($agencyrow[0],$mbr_message_id)?"selected":"").">". ($agencyrow[1])."</option>";
                                }
                                mssql_free_result($agencyresult);
                                echo "</select>";

                            }
                            else
                            {
                                echo "<select multiple name=mbr_messagegroup[] style=\"width:180px;Height:180px;\">";
                                $Data->data = array("group_id", "group_name");
                                $Data->where = "agency_id='".$temp_agencyid."' AND (group_type_id= 2 OR group_type_id = 3)";
                                $Data->order = "group_name asc";
                                $agencyresult = $Data->getData(user_groups);

                                while($agencyrow=mssql_fetch_row($agencyresult))
                                {
                                    echo "<option value=\"$agencyrow[0]\" ".(@in_array($agencyrow[0],$mbr_message_id)?"selected":"").">". ($agencyrow[1])."</option>";
                                }
                                mssql_free_result($agencyresult);
                                echo "</select>";
                            }
                            ?>                        </td>
                        <td valign="top">
                            <span style="float: left; padding: 20px 0px 0px 10px; font-size: 11px; font-weight: bold;">
                            Hold CTRL and click to select                            </span>                        </td>
                       </tr>
                   </table></td>
        </tr>

        <!--select clients part; for usertype == 'agency_user' only-->
        <style>
            #agency_user_client_field_area {
                <?php
                if($task == 'useredit' and $mbr_usertype == 'agency_user')
                {
                    echo 'display: table-row';
                }
                else
                {
                    echo 'display: none;';
                }
                $mbr_ap_id="";
                $clients_id = array();
                if ($edit_user_id)
                {
                    $Data->data = array("parentid");
                    $Data->where = "caseworkerid = '".$edit_user_id."'";
                    $Data->order = "id asc";
                    $cwresult = $Data->getData(caseworker_client);
                    while($cwrow = mssql_fetch_row($cwresult))
                    {
                        array_push($clients_id, trim($cwrow[0]));
                        if (strlen($mbr_ap_id)>0)
                        {
                            $mbr_ap_id .= ",";
                        }
                        $mbr_ap_id .= trim($cwrow[0]);
                    }
                    mssql_free_result($cwresult);
                }
                ?>
            }
        </style>
        <tr id="agency_user_client_field_area">
                <td valign="top" width=24%>Adoptive Parents:<input type="hidden" id="ap_id" name="mbr_ap_id" value="<?php echo $mbr_ap_id ?>" /></td>
                <td colspan=2>

                <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td valign="top" id="clientText">
                            <?php
                            if (userGetUserType($cookie_users_userid_usermenu)=="admin")
                            {
                                echo "<select id=selectClient multiple name=mbr_client[] style=\"width:180px;Height:180px;\" onchange=usersSetClient()>";
                                $Data->data = array("user_id", "first_name + ' ' + last_name");
                                $user_where ="";
                                if (count($mbr_agency_id)>0)
                                {
                                    for ($i=0;$i<count($mbr_agency_id);$i++)
                                    {
                                        if (trim($mbr_agency_id[$i])!="")
                                        {
                                            if ($i>0)
                                            {
                                                $user_where .= " or ";
                                            }
                                            $user_where .= "agency_group = '".$mbr_agency_id[$i]."'";
                                        }
                                    }
                                }
                                if (strlen($user_where)>0)
                                {
                                    $user_where = " AND (".$user_where.")";
                                }
                                $Data->where = "user_type = 'adoptive_parent' $user_where";
                                $Data->order = "first_name,last_name asc";
                                $cresult = $Data->getData(user_accounts);
                                while($crow=mssql_fetch_row($cresult))
                                {
                                    echo "<option value=\"$crow[0]\" ".(@in_array($crow[0],$clients_id)?"selected":"").">". ($crow[1])."</option>";
                                }
                                mssql_free_result($cresult);
                                echo "</select>";
                            ?>

                            <?php
                            }
                            else
                            {
                                echo "<select id=selectClient multiple name=mbr_client[] style=\"width:180px;Height:180px;\">";
                                $Data->data = array("user_id", "first_name + ' ' + last_name");
                                $Data->where = "agency_group = '".$temp_agencyid."' and user_type = 'adoptive_parent'and status <> 'Delete'";
                                $Data->order = "first_name,last_name asc";
                                $cresult = $Data->getData(user_accounts);
                                while($crow=mssql_fetch_row($cresult))
                                {
                                    echo "<option value=\"$crow[0]\" ".(@in_array($crow[0],$clients_id)?"selected":"").">". ($crow[1])."</option>";
                                }
                                mssql_free_result($cresult);
                                echo "</select>";
                            }
                            ?>                        </td>
                        <td valign="top">
                            <span style="float: left; padding: 20px 0px 0px 10px; font-size: 11px; font-weight: bold;">
                            Hold CTRL and click to select                            </span>                        </td>
                       </tr>
                   </table></td>
        </tr>
        <?php
    } //end if ($mbr_usertype!="agency" and $mbr_usertype!='attorney')
}
        /* Job Portal
        $Data->data = array("modname");
        $Data->where = "modname='jobboard'";
        $Data->order = "";
        $modresult = $Data->getData(admin_menu);
        if(mssql_num_rows($modresult) > 0)
        {
        ?>
        <tr>
                <td><?php echo $users_areatext_jobboradaccounttype ?>:</td>
                <td colspan="2">
                    <select name=mbr_jobboard_user style="width:125px">
                        <option value="job_seeker" <?php echo $mbr_jobboard_user=="job_seeker"?"selected":"" ?>><?php echo $users_areatext_jobseeker ?></option>
                        <option value="recruiter" <?php echo $mbr_jobboard_user=="recruiter"?"selected":"" ?>><?php echo $users_areatext_recruiter ?></option>
                    </select>*
                </td>
        </tr>
        <?php
        }
        mssql_free_result($modresult);
        */
        ?>
        <tr>
                <td colspan=3>
                        <br><u><?php echo "Personal Information" ?></u>                </td>
        </tr><?php
                $fieldcheck=array("mbr_email","mbr_password","mbr_repassword","mbr_answer");
                $fieldalert=array("Username is a mandatory field. Please check and try again.","Password is a mandatory field. Please check and try again.","Retype Password is a mandatory field. Please check and try again.","Your Answer is a mandatory field. Please check and try again.");
                if ($System->useSetting("user_info_Name") == "Yes")
                {
                    array_push($fieldcheck, "mbr_firstname");
                    array_push($fieldalert, "First Name is a mandatory field. Please check and try again.");
                    array_push($fieldcheck, "mbr_lastname");
                    array_push($fieldalert, "Last Name is a mandatory field. Please check and try again.");
                    /*
                    if (userGetUserType($cookie_users_userid_usermenu)=="admin" and ($module=='users' or $module=='admin'))
                    {
                    $fieldcheck[] = "mbr_suspenduntil";
                    $fieldalert[] = "Suspend Date is a mandatory field. Please check and try again.";
                    }
                    */
                    ?>
                <tr>
                        <td><?php echo "First Name" ?>:</td>
                        <td colspan=2>
                                <?php
                                if ($task=="edit" or $task=="useredit")
                                {
                                ?>
                                        <input type=text name=mbr_firstname style="width:180px" value="<?php echo trim($mbr_firstname); ?>" >*
                                <?php
                                }
                                else
                                {
                                ?>
                                        <input type=text name=mbr_firstname style="width:180px" value="<?php echo trim($mbr_firstname); ?>">*
                                <?php
                                }
                                ?>                        </td>
                </tr>
                <tr>
                        <td><?php echo "Last Name" ?>:</td>
                        <td colspan=2>
                                <?php
                                if ($task=="edit" or $task=="useredit")
                                {
                                ?>
                                        <input type=text name=mbr_lastname style="width:180px" value="<?php echo trim($mbr_lastname); ?>">*
                                <?php
                                }
                                else
                                {
                                ?>
                                        <input type=text name=mbr_lastname style="width:180px" value="<?php echo trim($mbr_lastname); ?>">*
                                <?php
                                }
                                ?>                        </td>
                </tr><?php
}
/* Job Portal
if ($System->useSetting("user_info_company") == "Yes")
{
    array_push($fieldcheck, "mbr_company");
    array_push($fieldalert, $users_areatext_companymandatory); ?>
                <tr>
                        <td><?php echo $users_areatext_company ?>:</td>
                        <td colspan=2>
                                <input type=text name=mbr_company style="width:180px" value="<?php echo $mbr_company; ?>">*
                        </td>
                </tr><?php
}
*/
if ($System->useSetting("user_info_Email") == "Yes")
{
    //array_push($fieldcheck, "mbr_email");
    //array_push($fieldalert, "Email is a mandatory field. Please check and try again.");
    $fieldemailalert = "Email format appears to be wrong. Please check and try again."; ?>
                <!--
                <tr>
                        <td><?php echo Email ?>:</td>
                        <td colspan=2>
                                <input type=text name=mbr_email style="width:180px" value="<?php echo $mbr_email; ?>">*
                        </td>
                </tr>
                -->
                <?php
}
if ($System->useSetting("user_info_Phone No") == "Yes")
{ ?>
                <tr>
                        <td><?php echo "Phone #" ?>:</td>
                        <td colspan=2>
                                <input type=text class="phone" name=mbr_phone style="width:135px" value="<?php echo $mbr_phone; ?>">                        </td>
                </tr><?php
}
if ($System->useSetting("user_info_Mobile Phone No") == "Yes")
{ ?>
                <tr>
                        <td><?php echo "Mobile Phone #" ?>:</td>
                        <td colspan=2>
                                <input type=text class="phone" name=mbr_mobilenumber style="width:135px" value="<?php echo $mbr_mobilenumber; ?>">                        </td>
                </tr><?php
}
if ($System->useSetting("user_info_Address") == "Yes")
{ ?>
                <tr>
                        <td><?php echo Address ?>:</td>
                        <td colspan=2>
                                <input type=text name=mbr_address1 style="width:180px" value="<?php echo $mbr_address1; ?>">                        </td>
                </tr>
                <tr>
                        <td></td>
                        <td colspan=2>
                                <input type=text name=mbr_address2 style="width:180px" value="<?php echo $mbr_address2; ?>">                        </td>
                </tr><?php
}
if ($System->useSetting("user_info_City") == "Yes")
{ ?>
                <tr>
                        <td><?php echo City ?>:</td>
                        <td colspan=2>
                                <input type=text name=mbr_city style="width:180px" value="<?php echo $mbr_city; ?>">                        </td>
                </tr><?php
}
if ($System->useSetting("user_info_State") == "Yes")
{ ?>
                <tr>
                                        <td><?php echo 'State/Province'; ?>:</td>
                        <td colspan=2><?php
if ($System->useSetting("general_country") == "United States")
{ ?>
<select name=mbr_state>
<option value="Alabama" <?php echo $mbr_state=="Alabama"?"selected":"" ?>>Alabama</option>
<option value="Alaska" <?php echo $mbr_state=="Alaska"?"selected":"" ?>>Alaska</option>
<option value="Alberta" <?php echo $mbr_state=="Alberta"?"selected":"" ?>>Alberta</option>
<option value="Arizona" <?php echo $mbr_state=="Arizona"?"selected":"" ?>>Arizona</option>
<option value="Arkansas" <?php echo $mbr_state=="Arkansas"?"selected":"" ?>>Arkansas</option>
<option value="British Columbia" <?php echo $mbr_state=="British Columbia"?"selected":"" ?>>British Columbia</option>
<option value="California" <?php echo $mbr_state=="California"?"selected":"" ?>>California</option>
<option value="Canada" <?php echo $mbr_state=="Canada"?"selected":"" ?>>Canada</option>
<option value="Colorado" <?php echo $mbr_state=="Colorado"?"selected":"" ?>>Colorado</option>
<option value="Connecticut" <?php echo $mbr_state=="Connecticut"?"selected":"" ?>>Connecticut</option>
<option value="Delaware" <?php echo $mbr_state=="Delaware"?"selected":"" ?>>Delaware</option>
<option value="District of Columbia" <?php echo $mbr_state=="District of Columbia"?"selected":"" ?>>District of Columbia</option>
<option value="Florida" <?php echo $mbr_state=="Florida"?"selected":"" ?>>Florida</option>
<option value="Georgia" <?php echo $mbr_state=="Georgia"?"selected":"" ?>>Georgia</option>
<option value="Hawaii" <?php echo $mbr_state=="Hawaii"?"selected":"" ?>>Hawaii</option>
<option value="Idaho" <?php echo $mbr_state=="Idaho"?"selected":"" ?>>Idaho</option>
<option value="Illinois" <?php echo $mbr_state=="Illinois"?"selected":"" ?>>Illinois</option>
<option value="Indiana" <?php echo $mbr_state=="Indiana"?"selected":"" ?>>Indiana</option>
<option value="Iowa" <?php echo $mbr_state=="Iowa"?"selected":"" ?>>Iowa</option>
<option value="Kansas" <?php echo $mbr_state=="Kansas"?"selected":"" ?>>Kansas</option>
<option value="Kentucky" <?php echo $mbr_state=="Kentucky"?"selected":"" ?>>Kentucky</option>
<option value="Louisiana" <?php echo $mbr_state=="Louisiana"?"selected":"" ?>>Louisiana</option>
<option value="Maine" <?php echo $mbr_state=="Maine"?"selected":"" ?>>Maine</option>
<option value="Manitoba" <?php echo $mbr_state=="Manitoba"?"selected":"" ?>>Manitoba</option>
<option value="Maryland" <?php echo $mbr_state=="Maryland"?"selected":"" ?>>Maryland</option>
<option value="Massachusetts" <?php echo $mbr_state=="Massachusetts"?"selected":"" ?>>Massachusetts</option>
<option value="Michigan" <?php echo $mbr_state=="Michigan"?"selected":"" ?>>Michigan</option>
<option value="Minnesota" <?php echo $mbr_state=="Minnesota"?"selected":"" ?>>Minnesota</option>
<option value="Mississippi" <?php echo $mbr_state=="Mississippi"?"selected":"" ?>>Mississippi</option>
<option value="Missouri" <?php echo $mbr_state=="Missouri"?"selected":"" ?>>Missouri</option>
<option value="Montana" <?php echo $mbr_state=="Montana"?"selected":"" ?>>Montana</option>
<option value="Nebraska" <?php echo $mbr_state=="Nebraska"?"selected":"" ?>>Nebraska</option>
<option value="Nevada" <?php echo $mbr_state=="Nevada"?"selected":"" ?>>Nevada</option>
<option value="New Hampshire" <?php echo $mbr_state=="New Hampshire"?"selected":"" ?>>New Hampshire</option>
<option value="New Jersey" <?php echo $mbr_state=="New Jersey"?"selected":"" ?>>New Jersey</option>
<option value="New Mexico" <?php echo $mbr_state=="New Mexic"?"selected":"" ?>>New Mexico</option>
<option value="New York" <?php echo $mbr_state=="New York"?"selected":"" ?>>New York</option>
<option value="North Carolina" <?php echo $mbr_state=="North Carolina"?"selected":"" ?>>North Carolina</option>
<option value="North Dakota" <?php echo $mbr_state=="North Dakota"?"selected":"" ?>>North Dakota</option>
<option value="New Brunswick" <?php echo $mbr_state=="New Brunswick"?"selected":"" ?>>New Brunswick</option>
<option value="Northwest Territory" <?php echo $mbr_state=="Northwest Territory"?"selected":"" ?>>Northwest Territory</option>
<option value="Nova Scotia" <?php echo $mbr_state=="Nova Scotia"?"selected":"" ?>>Nova Scotia</option>
<option value="Ontario" <?php echo $mbr_state=="Ontario"?"selected":"" ?>>Ontario</option>
<option value="Ohio" <?php echo $mbr_state=="Ohio"?"selected":"" ?>>Ohio</option>
<option value="Oklahoma" <?php echo $mbr_state=="Oklahoma"?"selected":"" ?>>Oklahoma</option>
<option value="Oregon" <?php echo $mbr_state=="Oregon"?"selected":"" ?>>Oregon</option>
<option value="Pennsylvania" <?php echo $mbr_state=="Pennsylvania"?"selected":"" ?>>Pennsylvania</option>
<option value="Prince Edward Island" <?php echo $mbr_state=="Prince Edward Island"?"selected":"" ?>>Prince Edward Island</option>
<option value="Quebec" <?php echo $mbr_state=="Quebec"?"selected":"" ?>>Quebec</option>
<option value="Rhode Island" <?php echo $mbr_state=="Rhode Island"?"selected":"" ?>>Rhode Island</option>
<option value="South Carolina" <?php echo $mbr_state=="South Carolina"?"selected":"" ?>>South Carolina</option>
<option value="South Dakota" <?php echo $mbr_state=="South Dakota"?"selected":"" ?>>South Dakota</option>
<option value="Saskatchewan" <?php echo $mbr_state=="Saskatchewan"?"selected":"" ?>>Saskatchewan</option>
<option value="Tennessee" <?php echo $mbr_state=="Tennessee"?"selected":"" ?>>Tennessee</option>
<option value="Texas" <?php echo $mbr_state=="Texas"?"selected":"" ?>>Texas</option>
<option value="Utah" <?php echo $mbr_state=="Utah"?"selected":"" ?>>Utah</option>
<option value="Vermont" <?php echo $mbr_state=="Vermont"?"selected":"" ?>>Vermont</option>
<option value="Virginia" <?php echo $mbr_state=="Virginia"?"selected":"" ?>>Virginia</option>
<option value="Washington" <?php echo $mbr_state=="Washington"?"selected":"" ?>>Washington</option>
<option value="West Virginia" <?php echo $mbr_state=="West Virginia"?"selected":"" ?>>West Virginia</option>
<option value="Wisconsin" <?php echo $mbr_state=="Wisconsin"?"selected":"" ?>>Wisconsin</option>
<option value="Wyoming" <?php echo $mbr_state=="Wyoming"?"selected":"" ?>>Wyoming</option>
<option value="Yukon Territory" <?php echo $mbr_state=="Yukon Territory"?"selected":"" ?>>Yukon Territory</option>
<option value="Other" <?php echo $mbr_state=="Other"?"selected":"" ?>>Other</option>
</select><?php
}
else
{    ?>
    <input type=text name=mbr_state style="width:180px" value="<?php echo $mbr_state; ?>"><?php
} ?>                    </td>
                    </tr><?php
}
if ($System->useSetting("user_info_Zip/Postal Code") == "Yes")
{ ?>
                                <tr>
                        <td><?php echo "Zip/Postal" ?>:</td>
                        <td colspan=2>
                                <input type=text name=mbr_zipcode style="width:140px" value="<?php echo $mbr_zipcode; ?>">                        </td>
                </tr><?php
}
if ($System->useSetting("user_info_Country") == "Yes")
{
    if ($mbr_country == "")
    {
        $mbr_country = $System->useSetting("general_country");
    } ?>
                <tr>
                        <td><?php echo Country ?>:</td>
                        <td colspan=2>
<select name=mbr_country>
<option value="United States" <?php echo $mbr_country=="United States"?"selected":"" ?>>United States</option>
<option value="Albania" <?php echo $mbr_country=="Albania"?"selected":"" ?>>Albania</option>
<option value="Algeria" <?php echo $mbr_country=="Algeria"?"selected":"" ?>>Algeria</option>
<option value="American Samoa" <?php echo $mbr_country=="American Samoa"?"selected":"" ?>>American Samoa</option>
<option value="Andorra" <?php echo $mbr_country=="Andorra"?"selected":"" ?>>Andorra</option>
<option value="Angola" <?php echo $mbr_country=="Angola"?"selected":"" ?>>Angola</option>
<option value="Anguilla" <?php echo $mbr_country=="Anguilla"?"selected":"" ?>>Anguilla</option>
<option value="Antarctica" <?php echo $mbr_country=="Antarctica"?"selected":"" ?>>Antarctica</option>
<option value="Antigua and Barbuda" <?php echo $mbr_country=="Antigua and Barbuda"?"selected":"" ?>>Antigua and Barbuda</option>
<option value="Argentina" <?php echo $mbr_country=="Argentina"?"selected":"" ?>>Argentina</option>
<option value="Armenia" <?php echo $mbr_country=="Armenia"?"selected":"" ?>>Armenia</option>
<option value="Aruba" <?php echo $mbr_country=="Aruba"?"selected":"" ?>>Aruba</option>
<option value="Australia" <?php echo $mbr_country=="Australia"?"selected":"" ?>>Australia</option>
<option value="Austria" <?php echo $mbr_country=="Austria"?"selected":"" ?>>Austria</option>
<option value="Azerbaijan" <?php echo $mbr_country=="Azerbaijan"?"selected":"" ?>>Azerbaijan</option>
<option value="Bahamas" <?php echo $mbr_country=="Bahamas"?"selected":"" ?>>Bahamas</option>
<option value="Bahrain" <?php echo $mbr_country=="Bahrain"?"selected":"" ?>>Bahrain</option>
<option value="Bangladesh" <?php echo $mbr_country=="Bangladesh"?"selected":"" ?>>Bangladesh</option>
<option value="Barbados" <?php echo $mbr_country=="Barbados"?"selected":"" ?>>Barbados</option>
<option value="Belarus" <?php echo $mbr_country=="Belarus"?"selected":"" ?>>Belarus</option>
<option value="Belgium" <?php echo $mbr_country=="Belgium"?"selected":"" ?>>Belgium</option>
<option value="Belize" <?php echo $mbr_country=="Belize"?"selected":"" ?>>Belize</option>
<option value="Benin" <?php echo $mbr_country=="Benin"?"selected":"" ?>>Benin</option>
<option value="Bermuda" <?php echo $mbr_country=="Bermuda"?"selected":"" ?>>Bermuda</option>
<option value="Bhutan" <?php echo $mbr_country=="Bhutan"?"selected":"" ?>>Bhutan</option>
<option value="Bolivia" <?php echo $mbr_country=="Bolivia"?"selected":"" ?>>Bolivia</option>
<option value="Bosnia and Herzegovina" <?php echo $mbr_country=="Bosnia and Herzegovina"?"selected":"" ?>>Bosnia and Herzegovina</option>
<option value="Botswana" <?php echo $mbr_country=="Botswana"?"selected":"" ?>>Botswana</option>
<option value="Bouvet Island" <?php echo $mbr_country=="Bouvet Island"?"selected":"" ?>>Bouvet Island</option>
<option value="Brazil" <?php echo $mbr_country=="Brazil"?"selected":"" ?>>Brazil</option>
<option value="British Indian Ocean Territory" <?php echo $mbr_country=="British Indian Ocean Territory"?"selected":"" ?>>British Indian Ocean Territory</option>
<option value="Brunei Darussalam" <?php echo $mbr_country=="Brunei Darussalam"?"selected":"" ?>>Brunei Darussalam</option>
<option value="Bulgaria" <?php echo $mbr_country=="Bulgaria"?"selected":"" ?>>Bulgaria</option>
<option value="Burkina Faso" <?php echo $mbr_country=="Burkina Faso"?"selected":"" ?>>Burkina Faso</option>
<option value="Burundi" <?php echo $mbr_country=="Burundi"?"selected":"" ?>>Burundi</option>
<option value="Canada" <?php echo $mbr_country=="Canada"?"selected":"" ?>>Canada</option>
<option value="Cambodia" <?php echo $mbr_country=="Cambodia"?"selected":"" ?>>Cambodia</option>
<option value="Cameroon" <?php echo $mbr_country=="Cameroon"?"selected":"" ?>>Cameroon</option>
<option value="Cape Verde" <?php echo $mbr_country=="Cape Verde"?"selected":"" ?>>Cape Verde</option>
<option value="Cayman Islands" <?php echo $mbr_country=="Cayman Islands"?"selected":"" ?>>Cayman Islands</option>
<option value="Central African Republic" <?php echo $mbr_country=="Central African Republic"?"selected":"" ?>>Central African Republic</option>
<option value="Channel Islands" <?php echo $mbr_country=="Channel Islands"?"selected":"" ?>>Channel Islands</option>
<option value="Chad" <?php echo $mbr_country=="Chad"?"selected":"" ?>>Chad</option>
<option value="Chile" <?php echo $mbr_country=="Chile"?"selected":"" ?>>Chile</option>
<option value="China" <?php echo $mbr_country=="China"?"selected":"" ?>>China</option>
<option value="Christmas Island" <?php echo $mbr_country=="Christmas Island"?"selected":"" ?>>Christmas Island</option>
<option value="Cocos (Keeling) Islands" <?php echo $mbr_country=="Cocos (Keeling) Islands"?"selected":"" ?>>Cocos (Keeling) Islands</option>
<option value="Colombia" <?php echo $mbr_country=="Colombia"?"selected":"" ?>>Colombia</option>
<option value="Comoros" <?php echo $mbr_country=="Comoros"?"selected":"" ?>>Comoros</option>
<option value="Congo" <?php echo $mbr_country=="Congo"?"selected":"" ?>>Congo</option>
<option value="Congo ,Democratic Republic" <?php echo $mbr_country=="Congo ,Democratic Republic"?"selected":"" ?>>Congo ,Democratic Republic</option>
<option value="Cook Islands" <?php echo $mbr_country=="Cook Islands"?"selected":"" ?>>Cook Islands</option>
<option value="Costa Rica" <?php echo $mbr_country=="Costa Rica"?"selected":"" ?>>Costa Rica</option>
<option value="Croatia" <?php echo $mbr_country=="Croatia"?"selected":"" ?>>Croatia</option>
<option value="Cyprus" <?php echo $mbr_country=="Cyprus"?"selected":"" ?>>Cyprus</option>
<option value="Czech Republic" <?php echo $mbr_country=="Czech Republic"?"selected":"" ?>>Czech Republic</option>
<option value="Denmark" <?php echo $mbr_country=="Denmark"?"selected":"" ?>>Denmark</option>
<option value="Djibouti" <?php echo $mbr_country=="Djibouti"?"selected":"" ?>>Djibouti</option>
<option value="Dominica" <?php echo $mbr_country=="Dominica"?"selected":"" ?>>Dominica</option>
<option value="Dominican Republic" <?php echo $mbr_country=="Dominican Republic"?"selected":"" ?>>Dominican Republic</option>
<option value="East Timor" <?php echo $mbr_country=="East Timor"?"selected":"" ?>>East Timor</option>
<option value="Ecuador" <?php echo $mbr_country=="Ecuador"?"selected":"" ?>>Ecuador</option>
<option value="Egypt" <?php echo $mbr_country=="Egypt"?"selected":"" ?>>Egypt</option>
<option value="El Salvador" <?php echo $mbr_country=="El Salvador"?"selected":"" ?>>El Salvador</option>
<option value="Equatorial Guinea" <?php echo $mbr_country=="Equatorial Guinea"?"selected":"" ?>>Equatorial Guinea</option>
<option value="Eritrea" <?php echo $mbr_country=="Eritrea"?"selected":"" ?>>Eritrea</option>
<option value="Estonia" <?php echo $mbr_country=="Estonia"?"selected":"" ?>>Estonia</option>
<option value="Ethiopia" <?php echo $mbr_country=="Ethiopia"?"selected":"" ?>>Ethiopia</option>
<option value="Falkland Islands" <?php echo $mbr_country=="Falkland Islands"?"selected":"" ?>>Falkland Islands</option>
<option value="Faroe Islands" <?php echo $mbr_country=="Faroe Islands"?"selected":"" ?>>Faroe Islands</option>
<option value="Fiji" <?php echo $mbr_country=="Fiji"?"selected":"" ?>>Fiji</option>
<option value="Finland" <?php echo $mbr_country=="Finland"?"selected":"" ?>>Finland</option>
<option value="France" <?php echo $mbr_country=="France"?"selected":"" ?>>France</option>
<option value="French Guiana" <?php echo $mbr_country=="French Guiana"?"selected":"" ?>>French Guiana</option>
<option value="French Polynesia" <?php echo $mbr_country=="French Polynesia"?"selected":"" ?>>French Polynesia</option>
<option value="French Southern Territories" <?php echo $mbr_country=="French Southern Territories"?"selected":"" ?>>French Southern Territories</option>
<option value="Gabon" <?php echo $mbr_country=="Gabon"?"selected":"" ?>>Gabon</option>
<option value="Gambia" <?php echo $mbr_country=="Gambia"?"selected":"" ?>>Gambia</option>
<option value="Georgia" <?php echo $mbr_country=="Georgia"?"selected":"" ?>>Georgia</option>
<option value="Germany" <?php echo $mbr_country=="Germany"?"selected":"" ?>>Germany</option>
<option value="Ghana" <?php echo $mbr_country=="Ghana"?"selected":"" ?>>Ghana</option>
<option value="Gibraltar" <?php echo $mbr_country=="Gibraltar"?"selected":"" ?>>Gibraltar</option>
<option value="Greece" <?php echo $mbr_country=="Greece"?"selected":"" ?>>Greece</option>
<option value="Greenland" <?php echo $mbr_country=="Greenland"?"selected":"" ?>>Greenland</option>
<option value="Grenada" <?php echo $mbr_country=="Grenada"?"selected":"" ?>>Grenada</option>
<option value="Guadeloupe" <?php echo $mbr_country=="Guadeloupe"?"selected":"" ?>>Guadeloupe</option>
<option value="Guam" <?php echo $mbr_country=="Guam"?"selected":"" ?>>Guam</option>
<option value="Guatemala" <?php echo $mbr_country=="Guatemala"?"selected":"" ?>>Guatemala</option>
<option value="Guinea" <?php echo $mbr_country=="Guinea"?"selected":"" ?>>Guinea</option>
<option value="Guinea Bissau" <?php echo $mbr_country=="Guinea Bissau"?"selected":"" ?>>Guinea Bissau</option>
<option value="Guyana" <?php echo $mbr_country=="Guyana"?"selected":"" ?>>Guyana</option>
<option value="Haiti" <?php echo $mbr_country=="Haiti"?"selected":"" ?>>Haiti</option>
<option value="Heard Island and McDonald Islands" <?php echo $mbr_country=="Heard Island and McDonald Islands"?"selected":"" ?>>Heard Island and McDonald Islands</option>
<option value="Holy See ,Vatican" <?php echo $mbr_country=="Holy See ,Vatican"?"selected":"" ?>>Holy See ,Vatican</option>
<option value="Honduras" <?php echo $mbr_country=="Honduras"?"selected":"" ?>>Honduras</option>
<option value="Hong Kong" <?php echo $mbr_country=="Hong Kong"?"selected":"" ?>>Hong Kong</option>
<option value="Hungary" <?php echo $mbr_country=="Hungary"?"selected":"" ?>>Hungary</option>
<option value="Iceland" <?php echo $mbr_country=="Iceland"?"selected":"" ?>>Iceland</option>
<option value="India" <?php echo $mbr_country=="India"?"selected":"" ?>>India</option>
<option value="Indonesia" <?php echo $mbr_country=="Indonesia"?"selected":"" ?>>Indonesia</option>
<option value="Ireland" <?php echo $mbr_country=="Ireland"?"selected":"" ?>>Ireland</option>
<option value="Israel" <?php echo $mbr_country=="Israel"?"selected":"" ?>>Israel</option>
<option value="Italy" <?php echo $mbr_country=="Italy"?"selected":"" ?>>Italy</option>
<option value="Jamaica" <?php echo $mbr_country=="Jamaica"?"selected":"" ?>>Jamaica</option>
<option value="Japan" <?php echo $mbr_country=="Japan"?"selected":"" ?>>Japan</option>
<option value="Jordan" <?php echo $mbr_country=="Jordan"?"selected":"" ?>>Jordan</option>
<option value="Kazakhstan" <?php echo $mbr_country=="Kazakhstan"?"selected":"" ?>>Kazakhstan</option>
<option value="Kenya" <?php echo $mbr_country=="Kenya"?"selected":"" ?>>Kenya</option>
<option value="Kiribati" <?php echo $mbr_country=="Kiribati"?"selected":"" ?>>Kiribati</option>
<option value="Kuwait" <?php echo $mbr_country=="Kuwait"?"selected":"" ?>>Kuwait</option>
<option value="Kyrgyzstan" <?php echo $mbr_country=="Kyrgyzstan"?"selected":"" ?>>Kyrgyzstan</option>
<option value="Laos" <?php echo $mbr_country=="Laos"?"selected":"" ?>>Laos</option>
<option value="Latvia" <?php echo $mbr_country=="Latvia"?"selected":"" ?>>Latvia</option>
<option value="Lebanon" <?php echo $mbr_country=="Lebanon"?"selected":"" ?>>Lebanon</option>
<option value="Lesotho" <?php echo $mbr_country=="Lesotho"?"selected":"" ?>>Lesotho</option>
<option value="Libya" <?php echo $mbr_country=="Libya"?"selected":"" ?>>Libya</option>
<option value="Liechtenstein" <?php echo $mbr_country=="Liechtenstein"?"selected":"" ?>>Liechtenstein</option>
<option value="Lithuania" <?php echo $mbr_country=="Lithuania"?"selected":"" ?>>Lithuania</option>
<option value="Luxembourg" <?php echo $mbr_country=="Luxembourg"?"selected":"" ?>>Luxembourg</option>
<option value="Macau" <?php echo $mbr_country=="Macau"?"selected":"" ?>>Macau</option>
<option value="Macedonia" <?php echo $mbr_country=="Macedonia"?"selected":"" ?>>Macedonia</option>
<option value="Madagascar" <?php echo $mbr_country=="Madagascar"?"selected":"" ?>>Madagascar</option>
<option value="Malawi" <?php echo $mbr_country=="Malawi"?"selected":"" ?>>Malawi</option>
<option value="Malaysia" <?php echo $mbr_country=="Malaysia"?"selected":"" ?>>Malaysia</option>
<option value="Maldives" <?php echo $mbr_country=="Maldives"?"selected":"" ?>>Maldives</option>
<option value="Mali" <?php echo $mbr_country=="Mali"?"selected":"" ?>>Mali</option>
<option value="Malta" <?php echo $mbr_country=="Malta"?"selected":"" ?>>Malta</option>
<option value="Marshall Islands" <?php echo $mbr_country=="Marshall Islands"?"selected":"" ?>>Marshall Islands</option>
<option value="Martinique" <?php echo $mbr_country=="Martinique"?"selected":"" ?>>Martinique</option>
<option value="Mauritania" <?php echo $mbr_country=="Mauritania"?"selected":"" ?>>Mauritania</option>
<option value="Mauritius" <?php echo $mbr_country=="Mauritius"?"selected":"" ?>>Mauritius</option>
<option value="Mayotte" <?php echo $mbr_country=="Mayotte"?"selected":"" ?>>Mayotte</option>
<option value="Mexico" <?php echo $mbr_country=="Mexico"?"selected":"" ?>>Mexico</option>
<option value="Micronesia" <?php echo $mbr_country=="Micronesia"?"selected":"" ?>>Micronesia</option>
<option value="Moldova" <?php echo $mbr_country=="Moldova"?"selected":"" ?>>Moldova</option>
<option value="Monaco" <?php echo $mbr_country=="Monaco"?"selected":"" ?>>Monaco</option>
<option value="Mongolia" <?php echo $mbr_country=="Mongolia"?"selected":"" ?>>Mongolia</option>
<option value="Montserrat" <?php echo $mbr_country=="Montserrat"?"selected":"" ?>>Montserrat</option>
<option value="Morocco" <?php echo $mbr_country=="Morocco"?"selected":"" ?>>Morocco</option>
<option value="Mozambique" <?php echo $mbr_country=="Mozambique"?"selected":"" ?>>Mozambique</option>
<option value="Namibia" <?php echo $mbr_country=="Namibia"?"selected":"" ?>>Namibia</option>
<option value="Nauru" <?php echo $mbr_country=="Nauru"?"selected":"" ?>>Nauru</option>
<option value="Nepal" <?php echo $mbr_country=="Nepal"?"selected":"" ?>>Nepal</option>
<option value="Netherlands" <?php echo $mbr_country=="Netherlands"?"selected":"" ?>>Netherlands</option>
<option value="Netherlands Antilles" <?php echo $mbr_country=="Netherlands Antilles"?"selected":"" ?>>Netherlands Antilles</option>
<option value="New Caledonia" <?php echo $mbr_country=="New Caledonia"?"selected":"" ?>>New Caledonia</option>
<option value="New Zealand" <?php echo $mbr_country=="New Zealand"?"selected":"" ?>>New Zealand</option>
<option value="Nicaragua" <?php echo $mbr_country=="Nicaragua"?"selected":"" ?>>Nicaragua</option>
<option value="Niger" <?php echo $mbr_country=="Niger"?"selected":"" ?>>Niger</option>
<option value="Nigeria" <?php echo $mbr_country=="Nigeria"?"selected":"" ?>>Nigeria</option>
<option value="Niue" <?php echo $mbr_country=="Niue"?"selected":"" ?>>Niue</option>
<option value="Norfolk Island" <?php echo $mbr_country=="Norfolk Island"?"selected":"" ?>>Norfolk Island</option>
<option value="North Korea" <?php echo $mbr_country=="North Korea"?"selected":"" ?>>North Korea</option>
<option value="Northern Mariana Islands" <?php echo $mbr_country=="Northern Mariana Islands"?"selected":"" ?>>Northern Mariana Islands</option>
<option value="Norway" <?php echo $mbr_country=="Norway"?"selected":"" ?>>Norway</option>
<option value="Oman" <?php echo $mbr_country=="Oman"?"selected":"" ?>>Oman</option>
<option value="Pakistan" <?php echo $mbr_country=="Pakistan"?"selected":"" ?>>Pakistan</option>
<option value="Palau" <?php echo $mbr_country=="Palau"?"selected":"" ?>>Palau</option>
<option value="Palestinian Territory" <?php echo $mbr_country=="Palestinian Territory"?"selected":"" ?>>Palestinian Territory</option>
<option value="Panama" <?php echo $mbr_country=="Panama"?"selected":"" ?>>Panama</option>
<option value="Papua New Guinea" <?php echo $mbr_country=="Papua New Guinea"?"selected":"" ?>>Papua New Guinea</option>
<option value="Paraguay" <?php echo $mbr_country=="Paraguay"?"selected":"" ?>>Paraguay</option>
<option value="Peru" <?php echo $mbr_country=="Peru"?"selected":"" ?>>Peru</option>
<option value="Philippines" <?php echo $mbr_country=="Philippines"?"selected":"" ?>>Philippines</option>
<option value="Pitcairn" <?php echo $mbr_country=="Pitcairn"?"selected":"" ?>>Pitcairn</option>
<option value="Poland" <?php echo $mbr_country=="Poland"?"selected":"" ?>>Poland</option>
<option value="Portugal" <?php echo $mbr_country=="Portugal"?"selected":"" ?>>Portugal</option>
<option value="Puerto Rico" <?php echo $mbr_country=="Puerto Rico"?"selected":"" ?>>Puerto Rico</option>
<option value="Qatar" <?php echo $mbr_country=="Qatar"?"selected":"" ?>>Qatar</option>
<option value="Reunion" <?php echo $mbr_country=="Reunion"?"selected":"" ?>>Reunion</option>
<option value="Romania" <?php echo $mbr_country=="Romania"?"selected":"" ?>>Romania</option>
<option value="Russian Federation" <?php echo $mbr_country=="Russian Federation"?"selected":"" ?>>Russian Federation</option>
<option value="Rwanda" <?php echo $mbr_country=="Rwanda"?"selected":"" ?>>Rwanda</option>
<option value="Saint Helena" <?php echo $mbr_country=="Saint Helena"?"selected":"" ?>>Saint Helena</option>
<option value="Saint Kitts and Nevis Anguilla" <?php echo $mbr_country=="Saint Kitts and Nevis Anguilla"?"selected":"" ?>>Saint Kitts and Nevis Anguilla</option>
<option value="Saint Lucia" <?php echo $mbr_country=="Saint Lucia"?"selected":"" ?>>Saint Lucia</option>
<option value="Saint Pierre and Miquelon" <?php echo $mbr_country=="Saint Pierre and Miquelon"?"selected":"" ?>>Saint Pierre and Miquelon</option>
<option value="Saint Vincent and The Grenadines" <?php echo $mbr_country=="Saint Vincent and The Grenadines"?"selected":"" ?>>Saint Vincent and The Grenadines</option>
<option value="Samoa" <?php echo $mbr_country=="Samoa"?"selected":"" ?>>Samoa</option>
<option value="San Marino" <?php echo $mbr_country=="San Marino"?"selected":"" ?>>San Marino</option>
<option value="Sao Tome and Principe" <?php echo $mbr_country=="Sao Tome and Principe"?"selected":"" ?>>Sao Tome and Principe</option>
<option value="Saudi Arabia" <?php echo $mbr_country=="Saudi Arabia"?"selected":"" ?>>Saudi Arabia</option>
<option value="Senegal" <?php echo $mbr_country=="Senegal"?"selected":"" ?>>Senegal</option>
<option value="Seychelles" <?php echo $mbr_country=="Seychelles"?"selected":"" ?>>Seychelles</option>
<option value="Singapore" <?php echo $mbr_country=="Singapore"?"selected":"" ?>>Singapore</option>
<option value="Slovakia" <?php echo $mbr_country=="Slovakia"?"selected":"" ?>>Slovakia</option>
<option value="Slovenia" <?php echo $mbr_country=="Slovenia"?"selected":"" ?>>Slovenia</option>
<option value="Solomon Islands" <?php echo $mbr_country=="Solomon Islands"?"selected":"" ?>>Solomon Islands</option>
<option value="Somalia" <?php echo $mbr_country=="Somalia"?"selected":"" ?>>Somalia</option>
<option value="South Africa" <?php echo $mbr_country=="South Africa"?"selected":"" ?>>South Africa</option>
<option value="South Georgia" <?php echo $mbr_country=="South Georgia"?"selected":"" ?>>South Georgia</option>
<option value="Spain" <?php echo $mbr_country=="Spain"?"selected":"" ?>>Spain</option>
<option value="Sri Lanka" <?php echo $mbr_country=="Sri Lanka"?"selected":"" ?>>Sri Lanka</option>
<option value="Suriname" <?php echo $mbr_country=="Suriname"?"selected":"" ?>>Suriname</option>
<option value="Svalbard and Jan Mayen" <?php echo $mbr_country=="Svalbard and Jan Mayen"?"selected":"" ?>>Svalbard and Jan Mayen</option>
<option value="Swaziland" <?php echo $mbr_country=="Swaziland"?"selected":"" ?>>Swaziland</option>
<option value="Sweden" <?php echo $mbr_country=="Sweden"?"selected":"" ?>>Sweden</option>
<option value="Switzerland" <?php echo $mbr_country=="Switzerland"?"selected":"" ?>>Switzerland</option>
<option value="Syrian Arab Republic" <?php echo $mbr_country=="Syrian Arab Republic"?"selected":"" ?>>Syrian Arab Republic</option>
<option value="Taiwan" <?php echo $mbr_country=="Taiwan"?"selected":"" ?>>Taiwan</option>
<option value="Tajikistan" <?php echo $mbr_country=="Tajikistan"?"selected":"" ?>>Tajikistan</option>
<option value="Tanzania" <?php echo $mbr_country=="Tanzania"?"selected":"" ?>>Tanzania</option>
<option value="Thailand" <?php echo $mbr_country=="Thailand"?"selected":"" ?>>Thailand</option>
<option value="Togo" <?php echo $mbr_country=="Togo"?"selected":"" ?>>Togo</option>
<option value="Tokelau" <?php echo $mbr_country=="Tokelau"?"selected":"" ?>>Tokelau</option>
<option value="Tonga" <?php echo $mbr_country=="Tonga"?"selected":"" ?>>Tonga</option>
<option value="Trinidad and Tobago" <?php echo $mbr_country=="Trinidad and Tobago"?"selected":"" ?>>Trinidad and Tobago</option>
<option value="Tunisia" <?php echo $mbr_country=="Tunisia"?"selected":"" ?>>Tunisia</option>
<option value="Turkey" <?php echo $mbr_country=="Turkey"?"selected":"" ?>>Turkey</option>
<option value="Turkmenistan" <?php echo $mbr_country=="Turkmenistan"?"selected":"" ?>>Turkmenistan</option>
<option value="Turks and Caicos Islands" <?php echo $mbr_country=="Turks and Caicos Islands"?"selected":"" ?>>Turks and Caicos Islands</option>
<option value="Tuvalu" <?php echo $mbr_country=="Tuvalu"?"selected":"" ?>>Tuvalu</option>
<option value="Uganda" <?php echo $mbr_country=="Uganda"?"selected":"" ?>>Uganda</option>
<option value="Ukraine" <?php echo $mbr_country=="Ukraine"?"selected":"" ?>>Ukraine</option>
<option value="United Arab Emirates" <?php echo $mbr_country=="United Arab Emirates"?"selected":"" ?>>United Arab Emirates</option>
<option value="United Kingdom" <?php echo $mbr_country=="United Kingdom"?"selected":"" ?>>United Kingdom</option>
<option value="Uruguay" <?php echo $mbr_country=="Uruguay"?"selected":"" ?>>Uruguay</option>
<option value="Uzbekistan" <?php echo $mbr_country=="Uzbekistan"?"selected":"" ?>>Uzbekistan</option>
<option value="Vanuatu" <?php echo $mbr_country=="Vanuatu"?"selected":"" ?>>Vanuatu</option>
<option value="Venezuela" <?php echo $mbr_country=="Venezuela"?"selected":"" ?>>Venezuela</option>
<option value="Vietnam" <?php echo $mbr_country=="Vietnam"?"selected":"" ?>>Vietnam</option>
<option value="Virgin Islands, British" <?php echo $mbr_country=="Virgin Islands, British"?"selected":"" ?>>Virgin Islands, British</option>
<option value="Wallis and Futuna Islands" <?php echo $mbr_country=="Wallis and Futuna Islands"?"selected":"" ?>>Wallis and Futuna Islands</option>
<option value="Western Sahara" <?php echo $mbr_country=="Western Sahara"?"selected":"" ?>>Western Sahara</option>
<option value="Yemen" <?php echo $mbr_country=="Yemen"?"selected":"" ?>>Yemen</option>
<option value="Yugoslavia" <?php echo $mbr_country=="Yugoslavia"?"selected":"" ?>>Yugoslavia</option>
<option value="Zambia" <?php echo $mbr_country=="Zambia"?"selected":"" ?>>Zambia</option>
</select>                        </td>
                </tr><?php
}
/*
if ($System->useSetting("user_info_hearaboutus") == "Yes")
{
    if ($module=='users' or $module=='admin' or $task=="signup")
    {
        echo "<tr valign=top><td>How did you hear about us?:</td><td colspan=2>";
        echo "<table cellpadding=0 cellspacing=2><tr valign=top><td><textarea name=mbr_hearaboutus style=\"width:275px; height:75px \">$mbr_hearaboutus</textarea></td><td></td></tr></table>";
        echo "</td></tr>";
    }
}
*/
if ($module=='users' or $module=='admin' or $task!="signup"){
if ($System->useSetting("user_info_Photo") == "Yes")
{
                if ($mbr_photo and file_exists($path["serloc"]."userhome/users/$mbr_photo"))
                {
                    echo "<tr><td></td><td><img src=\"".$path["run_time_wwwloc"]."userhome/users/$mbr_photo\" style=\"width:125px\" border=0><td></tr>";
                }

                if ($mbr_photo and file_exists($path["serloc"]."userhome/users/$mbr_photo"))
                {
                    echo "<tr><td></td><td><input type=image src='images/delete_bg_white.png' value=\"Remove\" onclick=\"document.formlogin.task.value='remove'\"><td></tr>";
                }
                ?>
                <tr>
                        <td><?php echo Photo ?>:</td>
                        <td colspan=2>
                                <input type=file name=mbr_photo style="width:250px">                        </td>
                </tr><?php

}
?>

                <tr>
                    <td>Gender:</td>
                    <td colspan=2>
                        <select name="mbr_gender" id="mbr_gender"  style="width:180px">
                            <option value="">Please select</option>
                            <option value="M" <?php echo $mbr_gender=="M"?"selected":"" ?>>Male</option>
                            <option value="F" <?php echo $mbr_gender=="F"?"selected":"" ?>>Female</option>
                            <option value="I" <?php echo $mbr_gender=="I"?"selected":"" ?>>Intersex</option>
                        </select>
                    </td>
                </tr>
<?php

 if(($mbr_usertype != 'adoptive_parent') and ($mbr_usertype != 'birth_parent'))
 {
    if ($System->useSetting("user_info_Organization") == "Yes")
    { ?>
                    <tr id="organization_area">
                            <td><?php echo Organization ?>:</td>
                            <td colspan=2>
                                    <input type=text name=mbr_organization style="width:180px" value="<?php echo $mbr_organization; ?>">                        </td>
                    </tr><?php
    }
    if ($System->useSetting("user_info_Title") == "Yes")
    { ?>
                    <tr id="title_area">
                            <td><?php echo Title ?>:</td>
                            <td colspan=2>
                                    <input type=text name=mbr_title style="width:180px" value="<?php echo $mbr_title; ?>">                        </td>
                    </tr><?php
    }
    if ($System->useSetting("user_info_Web Site") == "Yes")
    { ?>
                    <tr id="website_area">
                            <td><?php echo "Web Site" ?>:</td>
                            <td colspan=2>
                                    <input type=text name=mbr_website style="width:180px" value="<?php echo $mbr_website; ?>">                        </td>
                    </tr><?php
    }
 }
if ($System->useSetting("user_info_Time Zone") == "Yes")
{
?>
                <tr>
                        <td><?php echo "Time Zone" ?>:</td>
                        <td colspan=2>
<select name=mbr_timezone>
	<option value="0" >Please Select</option>
<?php
	$getTimeZoneListing	=	mssql_query("select timezoneValue from user_timezone where active = 'Y'");
	if($task == 'signup' && userGetUserType($cookie_users_userid_usermenu)=="agency"){

		$Data->data = array("agency_id");
		$Data->where = "user_id='$cookie_users_userid_usermenu'";
		$resultDatas = $Data->getData(user_agencies);
		$agencyID = mssql_fetch_array($resultDatas);

		$agencyIDs = $agencyID[0];
		$agencydefaulttimezone = $agencyIDs.'agencydefaulttimezone';
		$timezoneQurery = "SELECT useroption, setting FROM system_settings WHERE useroption = '$agencydefaulttimezone'";
		$timezoneRs = mssql_query($timezoneQurery);
		$agencydefaulttimezoneValues = mssql_fetch_array($timezoneRs);
		$mbr_timezone  =   $agencydefaulttimezoneValues[1];
	}

	while($zone_row = mssql_fetch_row($getTimeZoneListing)){
?>
	<option value="<?= $zone_row[0]?>" <?php echo ((trim($mbr_timezone)==trim($zone_row[0]))?"selected":""); ?>><?= $zone_row[0]?></option>
<?php }?>
</select>                        </td>
                </tr><?php
}}
                ?>
                <!--Spouse Info-->
                <style>
                    #spouse_info_field_area {
                        <?php
                        if($task == 'useredit' and ($mbr_usertype == 'adoptive_parent' or $mbr_usertype == 'birth_parent'))
                        {
                            echo 'display: table-row';
                        }
                        else
                        {
                            echo 'display: none;';
                        }
                        ?>
                    }
                </style>
                <tr id="spouse_info_field_area">
                    <td colspan=3>
                    <table border="0" cellpadding="2" cellspacing="0" width="100%">
                        <tr>
                            <td colspan=3>
                            <br><u>Spouse Info</u>                            </td>
                        </tr>
                        <tr>
                            <td width="24%">First Name:</td>
                            <td colspan=2>
                                    <input type=text name="spouse_first_name" style="width:180px" value="<?php echo $spouse_first_name; ?>">                            </td>
                        </tr>
                        <tr>
                            <td>Last Name:</td>
                            <td colspan=2>
                                    <input type=text name="spouse_last_name" style="width:180px" value="<?php echo $spouse_last_name; ?>">                            </td>
                        </tr>
                        <tr>
                            <td>Gender:</td>
                            <td colspan=2>
                                    <select name="spouse_gender" id="spouse_gender"  style="width:180px">
                                        <option value="">Please select</option>
                                        <option value="M" <?php echo $spouse_gender=="M"?"selected":"" ?>>Male</option>
                                        <option value="F" <?php echo $spouse_gender=="F"?"selected":"" ?>>Female</option>
                                        <option value="I" <?php echo $spouse_gender=="I"?"selected":"" ?>>Intersex</option>
                                    </select>
                            </td>
                        </tr>
                        <!--<tr>
                            <td>Organization:</td>
                            <td colspan=2>
                                    <input type=text name="spouse_organization" style="width:180px" value="<?php echo $spouse_organization; ?>">                            </td>
                        </tr>
                        <tr>
                            <td>Title:</td>
                            <td colspan=2>
                                    <input type=text name="spouse_title" style="width:180px" value="<?php echo $spouse_title; ?>">                            </td>
                        </tr>
                        <tr>
                            <td>Website:</td>
                            <td colspan=2>
                                    <input type=text name="spouse_website" style="width:180px" value="<?php echo $spouse_website; ?>">                            </td>
                        </tr>-->
                    </table>                    </td>
                </tr>
                <!--end Spouse Info-->
                <?php
/*
if ($System->useSetting("user_info_Mobile Provider") == "Yes")
{ ?>
                <tr>
                        <td>Mobile Provider:</td>
                        <td colspan=2>
                                <select name=mbr_mobileprovider style="width:135px">
                                        <option <?php if ($mbr_mobileprovider=='C'){echo 'selected="yes"';} ?> value="C" <?php echo $mbr_providercingular; ?>><?php echo "Cingular"; ?></option>
                                        <option <?php if ($mbr_mobileprovider=='N'){echo 'selected="yes"';} ?> value="N" <?php echo $mbr_providernextel; ?>><?php echo "Nextel"; ?></option>
                                        <option <?php if ($mbr_mobileprovider=='S'){echo 'selected="yes"';} ?> value="S" <?php echo $mbr_providersprint; ?>><?php echo "Sprint"; ?></option>
                                        <option <?php if ($mbr_mobileprovider=='T'){echo 'selected="yes"';} ?> value="T" <?php echo $mbr_providertmobile; ?>><?php echo "T-Mobile"; ?></option>
                                        <option <?php if ($mbr_mobileprovider=='Z'){echo 'selected="yes"';} ?> value="Z" <?php echo $mbr_providerverizon; ?>><?php echo "Verizon"; ?></option>
                                        <option <?php if ($mbr_mobileprovider=='V'){echo 'selected="yes"';} ?> value="V" <?php echo $mbr_providervirgin; ?>><?php echo "Virgin Mobile"; ?></option>
                                        <option <?php if ($mbr_mobileprovider=='O'){echo 'selected="yes"';} ?> value="O" <?php echo $mbr_providerother; ?>><?php echo "Other..."; ?></option>
                                </select>
                                <br>
                        </td>
                </tr><?php
}
if ($System->useSetting("user_info_Skype") == "Yes")
{ ?>
                <tr>
                        <td>Skype:</td>
                        <td colspan=2>
                                <input type=text name=mbr_skype style="width:135px" value="<?php echo $mbr_skype; ?>">
                        </td>
                </tr><?php
}
if ($System->useSetting("user_info_Yahoo! Messenger") == "Yes")
{ ?>
                <tr>
                        <td>Yahoo! Messenger:</td>
                        <td colspan=2>
                                <input type=text name=mbr_yahoo style="width:135px" value="<?php echo $mbr_yahoo; ?>">
                        </td>
                </tr><?php
}
if ($System->useSetting("user_info_MSN") == "Yes")
{ ?>
                <tr>
                        <td>MSN:</td>
                        <td colspan=2>
                                <input type=text name=mbr_msn style="width:135px" value="<?php echo $mbr_msn; ?>">
                        </td>
                </tr><?php
}
if ($System->useSetting("user_info_ICQ") == "Yes")
{ ?>
                <tr>
                        <td>ICQ:</td>
                        <td colspan=2>
                                <input type=text name=mbr_icq style="width:135px" value="<?php echo $mbr_icq; ?>">
                        </td>
                </tr><?php
}
if ($System->useSetting("user_info_Google Talk") == "Yes")
{ ?>
                <tr>
                        <td>Google Talk:</td>
                        <td colspan=2>
                                <input type=text name=mbr_gtalk style="width:135px" value="<?php echo $mbr_gtalk; ?>">
                        </td>
                </tr><?php
}*/
/*
$Data->data = array("amount");
$Data->where = "membership_id='$mbr_membership'";
$Data->order = "amount";
$packageResult = $Data->getData(user_memberships);
while($packageRow = mssql_fetch_row($packageResult))
{
    $packageprice[] = $packageRow[0];
}
mssql_free_result($packageResult);
if (($packageprice[0] != "" and $packageprice[0] != "0.00") or ($packageprice[1] != "" and $packageprice[1] != "0.00"))
{
    if ($System->useSetting("user_info_Credit Card") == "Yes")
    {
        echo "<tr><td>Credit Card:</td><td><input type=password name=creditcart value=\"$mbr_creditcard\" style=\"width:180px\"></td></tr>";
    }

    if ($System->useSetting("user_info_Expiration Date") == "Yes")
    {
        echo "<tr><td>Expiration Date:</td><td><input type=text id=expiredate name=expiredate style=\"width:135px\" value=\"$mbr_expiredate\"> <a href=\"javascript:show_calendar('expiredate', document.formlogin.expiredate.value, '".$path["run_time_wwwloc"]."');\"><img src=\"".$path["run_time_wwwloc"]."auxiliary/images/calendar.gif\" border=\"0\" align=absmiddle></a></td>";
    }
}
*/
if ((userGetUserType($cookie_users_userid_usermenu)=="admin" or userGetUserType($cookie_users_userid_usermenu)=="agency" or userGetUserType($cookie_users_userid_usermenu)=="attorney" or getCurrentUserType() == 'agency_user') and ($module=='users' or $module=='admin'))
{ ?>
        <tr>
                <td colspan=3><br><u>Admin Use</u></td>
        </tr>
        <?php
        /*
        <tr>
                <td valign=top>
                     Access Permission:<br>
                     (Press 'Ctrl' and click)
                </td>

                <td>
                        <select multiple name=mbr_permissions[] style="Height:180px;">
                        <?php
                        $preSelected = array("affiliatewiz", "autoezine", "blogwriter", "classifiedads", "customshop", "directory", "faqmanager", "formmaker", "forum", "gamesroom", "groups", "helpdesk", "inboxmessenger", "jobboard", "linkshortener", "livechat", "mediaalbum", "myamazon", "onlinestore", "publisher", "sitebuilder", "surveypro", "textads");
                        $System->data = array("modname");
                        $System->where = "menutype='Modicon'";
                        $System->order = "modname";
                        $result = $System->getMultiLists();
                        while($myrow=mssql_fetch_row($result))
                        {
                                $title = renameCurrent($myrow[0]);
                                if($task=="signup")
                                {
                                        echo "<option value=\"$myrow[0]\" ".(in_array($myrow[0], $preSelected)?"selected":"").">$title</option>";
                                }
                                else
                                {
                                        echo "<option value=\"$myrow[0]\" ".(in_array($myrow[0], $mbr_permissions)?"selected":"").">$title</option>";
                                }
                        }
                        mssql_free_result($result);
                        ?>
                        </select>
                        <br>
                        <br>
                </td>
        </tr>
        */
        if (userGetUserType($cookie_users_userid_usermenu)=="admin")
        {
        ?>
        <tr><td>Membership Package:</td><td colspan=2>
        <select name=mbr_membership style="width:175px"><?php
        $Data->data = array("membership_id", "package");
        $Data->where = "package!='None'";
        $Data->order = "amount";
        $packageResult = $Data->getData(user_memberships);
        while($packageRow = mssql_fetch_row($packageResult))
        {
            echo "<option value=\"$packageRow[0]\"".($mbr_membership=="$packageRow[0]"?"selected":"").">". ($packageRow[1])."</option>";
        }
        mssql_free_result($packageResult); ?></select><br></td></tr>
        <?php
        }
        ?>
        <tr>
            <td> <?php echo "Membership Status:" ?></td>
            <td colspan=2>
            <select name=mbr_status style="width:125px">
            <?php
            if($task!="signup" and $task!="checkUsed")
            {
                    $Data->data = array("status_mode");
                    $Data->where = "user_id = '$edit_user_id'";
                    $Data->order = "";
                    $statusResult = $Data->getData(user_accounts);
                    while($statusRow = mssql_fetch_row($statusResult))
                    {
                            ?>
                            <option value="Active" <?php echo $statusRow[0]=="Active"?"selected":"" ?>>Active</option>
                            <option value="Pending" <?php echo $statusRow[0]=="Pending"?"selected":"" ?>>Pending</option>
                            <option value="Closed" <?php echo $statusRow[0]=="Closed"?"selected":"" ?>>Closed</option>
                            <option value="Inactive" <?php echo $statusRow[0]=="Inactive"?"selected":"" ?>>Inactive</option>
                            <option value="Delete" <?php echo $statusRow[0]=="Delete"?"selected":"" ?>>Deleted</option>
                            <?php
                    }
            }
            else
            {
                    ?>
                    <option value="Active" selected>Active</option>
                     <option value="Pending" selected>Pending</option>
                      <option value="Closed" selected>Closed</option>
                    <option value="Inactive">Inactive</option>
                    <option value="Delete">Delete</option>
                    <?php
            }
            ?>
            </select><br>
            </td>
        </tr>
        <?php
        if (userGetUserType($cookie_users_userid_usermenu)=="admin" || userGetUserType($cookie_users_userid_usermenu) == 'agency' || userGetUserType($cookie_users_userid_usermenu) == 'agency_user')
        {
            if ($mbr_suspenduntil == "")
            {
                //$mbr_suspenduntil = date(str_replace("%", "", $dateformat));
            }
                /*
                echo "
                <tr><td>Suspend Date:</td><td><input type=text id=mbr_suspenduntil name=mbr_suspenduntil style=\"width:135px\" value=\"$mbr_suspenduntil\"> <a href=\"javascript:show_calendar('mbr_suspenduntil', document.formlogin.mbr_suspenduntil.value, '".$path["run_time_wwwloc"]."');\"><img src=\"".$path["run_time_wwwloc"]."auxiliary/images/calendar.gif\" border=\"0\" align=absmiddle></a> *</td></tr>";
                */
                echo "<input type=\"hidden\" id=mbr_suspenduntil name=mbr_suspenduntil style=\"width:135px\" value=\"".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 1, (date("Y") + 20)))."\">";
        ?>
         <tr id="notification_field_area">
            <td colspan=3><br><u>Notification</u></td>
        </tr>
        <?php
        //load notification
        $result_not = mssql_query("SELECT * FROM user_notifications WHERE un_user_id = '$edit_user_id'");
        if(mssql_num_rows($result_not) > 0)
        {
            $row_not = mssql_fetch_array($result_not);
            extract($row_not);
            mssql_free_result($result_not);
        }
        ?>
        <tr id="emailnotification_field_area">
            <td>Email ID for Notifications:</td>
            <td><input name="un_email_id" type="text" id="un_email_id" style="width:180px" value="<?php echo htmlspecialchars(trim($un_email_id)); ?>" /></td>
            <td>&nbsp;</td>
        </tr>
        <tr id="alert_field_area">
            <td>Alert Preference:</td>
            <td><table border="0" cellspacing="0" cellpadding="3">
                <tr>
                    <td><input type="checkbox" name="un_notify_email" id="un_notify_email" value="1" <?php echo (trim($un_notify_email)?"checked":"") ?> /></td>
                    <td>eMail</td>
                    <td>&nbsp;</td>
                    <td><input type="checkbox" name="un_notify_sms" id="un_notify_sms" value="1" <?php echo (trim($un_notify_sms)?"checked":"") ?> /></td>
                    <td>SMS</td>
                </tr>
            </table></td>
            <td>&nbsp;</td>
        </tr>
        <tr id="smsnotification_field_area">
            <td>Phone to SMS:</td>
            <td><input class="phone" name="un_phone_number" type="text" id="un_phone_number" style="width:180px" value="<?php echo trim($un_phone_number); ?>" /></td>
            <td>&nbsp;</td>
        </tr>
        <tr id="carrier_field_area">
            <td>Carrier</td>
            <td><select name="un_carrier_id" id="un_carrier_id" style="width:200px">
                <option value="0">Please Select</option>
                <?php
                $result_sms = mssql_query("SELECT * FROM sms_carriers ORDER BY carrier_name");
                if(mssql_num_rows($result_sms) > 0)
                {
                    while($row_sms = mssql_fetch_array($result_sms))
                    {
                        ?>
                        <option value="<?=$row_sms['carrier_id']?>" <?=($row_sms['carrier_id'] == $un_carrier_id)?"selected":""?> ><?= ($row_sms['carrier_name'])?></option>
                        <?php
                    }
                    mssql_free_result($result_sms);
                }
                ?>
            </select></td>
            <td>&nbsp;</td>
        </tr>


        <!-- tr added by ratheesh for birth parent settings -->


                <tr id="edd_field_area" style="display:none">
            <td colspan=3><br><u>Expected Due Date</u></td>
        </tr>

        <tr id="eddlnotification_field_area" style="display:none; ">
            <td>EDD:</td>
            <td>
               <?php  /* if($edd =="Jan 1 1900 12:00AM" || $edd==""){$new_date="";}else{  $pieces = explode(" ", $edd); print_r($pieces); echo $pieces[2]; $new_date = date("m/d/Y",strtotime($pieces[0]." ".$pieces[1]." ".$pieces[2]));}  */  ?>
                <input name="un_edd_dt" type="text" id="un_edd_dt" style="width:180px" value="<?php echo $edd; ?>" />
                <img   src="auxiliary/images/calendar.gif" alt="calender"  onClick ="duedateCalc();" style="cursor: pointer;"/>

            </td>
            <td>               &nbsp;
</td>
        </tr>


        <tr id="idtypelnotification_field_area" style="display:none">
            <td>ID Type:</td>
            <td>
                <select name="idType" id="idType" style="width:125px">
                    <option value="" selected>Please Select</option>
                    <option value="Driving License" <?php echo $idType=="Driving License"?"selected":"" ?> >Driving License</option>
                    <option value="Passport" <?php echo $idType=="Passport"?"selected":"" ?>>Passport</option>
                    <option value="State ID" <?php echo $idType=="State ID"?"selected":"" ?>>State ID</option>
                </select>
            </td>
            <td>&nbsp;</td>
        </tr>

         <tr id="idnolnotification_field_area" style="display:none">
            <td>ID Number:</td>
            <td>
                <input name="un_id_no" type="text" id="un_id_no" style="width:180px" value="<?php echo $idNo; ?>" />
            </td>
            <td>&nbsp;</td>
        </tr>



         <!-- end of  tr added by ratheesh for birth parent settings -->





        <?php
        }
/*
if ($mbr_showprofile == "")
{
    $mbr_showprofile = substr($System->useSetting("userprofile_display"), 0, 1);
} ?>
<tr><td>User Profile on Homepage:</td><td><select name=mbr_showprofile style="width:125px">
<option value="S" <?php echo $mbr_showprofile=="S"?"selected":"" ?>>Show</option>
<option value="H" <?php echo $mbr_showprofile=="H"?"selected":"" ?>>Hide</option>
</select></td></tr><?php
*/
}
else
{
    if($mount!='menu-manageaffiliates.php')
    {
    if ($task=="signup" and $module!="users")
    {
        if ($System->useSetting("user_signup_terms"))
        {
            echo "</table><br>
            <table border=0 cellpadding=2 cellspacing=0 width=100% align=center>
                <tr><td><input type=checkbox id=\"signupterms\" onclick=\"if(this.checked==true){document.getElementById('user_signup').style.display = 'block';}else{document.getElementById('user_signup').style.display = 'none';}\"> Terms and Conditions:</td></tr>
                <tr><td><textarea rows=5 style=\"width: 100%\" readonly>". ($System->useSetting("user_signup_terms"))."</textarea></td></tr>
            </table><div id=user_signup style=\"display:none\"><br><table border=0 cellpadding=2 cellspacing=0 width=100% align=center>";
        }
        echo "
        <tr><td valign=top>Verify Code:</td><td><table border=0 width=300 cellpadding=0 cellspacing=0>";
        $captchafont = $System->useSetting("security_captchafont");
        if ($captchafont == "text")
        {
            $_SESSION["securitycode"] = $Data->genCaptcha();
            $securitycode = $_SESSION["securitycode"];
            echo "<input type=hidden name=storedcaptcha value=\"$securitycode\"><tr><td valign=top style=\"font-size:100%; font-weight:bold; color:#00008B; background-color:#E6E6FA; border:#B0C4DE\">&nbsp;&nbsp;$securitycode&nbsp;&nbsp;</td><td>&nbsp;</td>";
  }
        else
        {
           echo "<tr><td valign=top background-color:#000000><img id=\"imgCaptcha\" src=\"".$path["run_time_wwwloc"]."tools/createimage.php\"></td>";
        }
        echo "<td><input type=text name=typedcaptcha style=\"width:140px\">*</td></tr></table></td></tr>";
    }
    }
}
$dateformat = $System->useSetting("general_dateformat");
switch($System->useSetting("general_dateformat"))
{
    case "%Y-%m-%d" : $datepicker = "datepicker1.js"; break;
    case "%m-%d-%Y" : $datepicker = "datepicker2.js"; break;
    case "%d-%m-%Y" : $datepicker = "datepicker3.js"; break;
}
?>
<iframe width=200 height=200 id="iCal" name="iCal" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; left:-500px; top:0px;"></iframe>
<script language="JavaScript" src="auxiliary/<?php echo $datepicker ?>"></script>

<?php
if($mount!='menu-manageaffiliates.php')
{
?><input type="hidden" id="fromparam" name="fromparam" value="<?php echo $from; ?>" />
  <input type="hidden" id="fromclientpanelID" name="fromclientpanelID" value="<?php echo $clientpanelID; ?>" />
                <tr>
                  <td colspan=3 align="right"><br><?php
                        if ($module!='users' and $module!='admin' and $task=="signup")
                        {
                            array_push($fieldcheck, "typedcaptcha");
                            array_push($fieldalert, "Verify Code is a mandatory field. Please check and try again.");
                        }
                        if ($task=="signup")
                        {
                            $infotask = "addinfo";
                        }
                        else
                        {
                            $infotask = "editinfo";
                        }
                        if ($module!='users' and $module!='admin' and $task=="signup")
                        { ?>
                      <img src="images/submit_bg_whitesm.png" width="77" height="34" alt="Submit" style="cursor:pointer;" onclick="if(verifyPassword() && isEmpty('formlogin')==true){document.formlogin.submit()}" />
                    <?php
                        }
                        else
                        {
                        if ($task=="edit" or $task=="useredit")
                        {
                        ?>
                        <!--<input type="hidden" id="fromparam" name="fromparam" value="<?php echo $from; ?>" />
                        <input type="hidden" id="fromclientpanelID" name="fromclientpanelID" value="<?php echo $clientpanelID; ?>" />-->
                       <?php
                            $submit_icon = "update_bg_white.png";
                        }
                        else
                        {
                            $submit_icon = "submit_bg_whitesm.png";
                        }
                        $alertcomeOrnot = ($userType == 'agency_user')?1:0;
                        $updateFlag = 1;
                         if($userType == 'agency_user' and ($edit_user_id != $login_social_user_id))
                         {
                             $caseScrtyVal             = getCaseWorkerSecurityVal($login_social_user_id);
                             $updateFlag    = 0;
                             if($mbr_usertype =='adoptive_parent' or $mbr_usertype =='birth_parent')
                             {
                                 $sql_parent_CW     = "SELECT count(*) as cwpFlag FROM caseworker_client WHERE caseworkerid = '$login_social_user_id' and parentid = '$edit_user_id'";
                                 $result_parent_CW  = mssql_query($sql_parent_CW);
                                 $cwpFlag           = mssql_fetch_assoc($result_parent_CW);
                                 if($cwpFlag['cwpFlag']){
                                     $updateFlag = 1;
                                 }
                             }
                            if($caseScrtyVal != '2' or($updateFlag))
                             {
                                $alertcomeOrnot = 0;
                        ?>
                     <!-- <img src="images/<?=$submit_icon?>" width="77" height="34" alt="Submit" style="cursor:pointer;" onclick="if(verifyPassword() && isEmpty('formlogin')==true){document.formlogin.submit()}" />-->
                      <img src="images/<?=$submit_icon?>" width="77" height="34" alt="Submit" style="cursor:pointer;" onclick="addUser()"  />

                     <?php
                             }}
                        else
                        {
                     ?>
                     <img src="images/<?=$submit_icon?>" width="77" height="34" alt="Submit" style="cursor:pointer;" onclick="addUser()"  />
                     <?php

                        } }?>
                      <?php if($from == 'client') {
                          if($clientpanelID)
                              $redirectlocation ='index-mp.php?module=cwhome&mount=tasklist_details&view_user_id='.$clientpanelID;
                          else
                              $redirectlocation ='index-mp.php?module=cwhome';
                          ?>
                            <img src="images/cancel_bg_white.png"  width="77" height="34" alt="Submit" style="cursor:pointer;" onclick="window.location.href='<?php echo $redirectlocation;?>'" />
                     <?php } else { ?>
                            <img src="images/cancel_bg_white.png" width="77" height="34"  alt="Submit" style="cursor:pointer;" onclick="cancelAction()" />
                     <?php } ?>
                    </td>
                </tr><?php
                if ($System->useSetting("user_signup_terms"))
                {
                    echo "</table></div><table border=0 cellpadding=2 cellspacing=0 width=100% align=center>";
                } ?>
                <tr>
                        <td colspan=3><br>
                                &nbsp                        </td>
                </tr>
<?php
}
if($mount!='menu-manageaffiliates.php')
{
?>
        </form><?php
} ?>
        </table>


      <!--   div added by ratheesh; for birth parent settings -->

         <div id ="calculator" style="display:none;margin:0px; top:0px;">
            <?php include 'pregnancyCalculator.php'; ?>
         </div>

       <!-- end of   div added by ratheesh; for birth parent settings -->




<?php
}
}

// require javascript functions
if (!isset($_GET['path']) and !isset($_REQUEST['path']) and !isset($_POST['path']))
{
    require($path["serloc"]."admin/jsfunctions.php");
}

?>
<script language=javascript>
jQuery(document).ready(function() {








    jQuery("#ser_emp_Dialog").dialog(
               {
                    width: 358,
                    height: 475,
                    modal: true,
                    autoOpen: false,
                    draggable: true,
                    resizable: false,
                    title:'Users from airs',
                    	open: function(event, ui)
                        {
            	            jQuery('#emp_ser_name').focus();

                        },
                        close: function(event, ui)
                        {

                        }
                }
             );

 jQuery("#importfrmairs").live('click',function() { openDialogSer_emp(); return false; });
 jQuery("#ser_all_emp").bind('click',function() { ser_all_employer(); return false; });
  jQuery("#import_user").bind('click',function() { sel_import_user(); return false; });

  jQuery("#mbr_password").mouseover(function() {
            jQuery("#showPass").html(jQuery("#decrypt_pswd").attr("value"));
        });

        jQuery("#mbr_password").mouseout(function() {
            jQuery("#showPass").html("");
        });
});


  jQuery("#calculator").dialog(
            {
                bgiframe: true,
                width: 512,
                height: 300,
                modal: true,
                autoOpen: false,
                draggable: true,
                resizable: false,
                title: 'Expected Due Date',
                close: function(event, ui) {  }
            }
        );


    jQuery("#btn_submit").click(function() {
            var calculatedDuedate  = document.getElementById('calculatedDuedate').value;
       // alert(calculatedDuedate);
        document.getElementById('un_edd_dt').value=calculatedDuedate;
            jQuery("#calculator").dialog("close");
            jQuery("#calculator").dialog("close");
        });
        jQuery("#btn_cancel").click(function() {
            jQuery("#calculator").dialog("close");
        });




function duedateCalc(){
     jQuery('#calculator').dialog('open');
}


function openDialogSer_emp()
{
    	jQuery('#emp_ser_name').val('');
        jQuery('#emp_ser_lname').val('');
        jQuery('#no_records').val('');
    	jQuery('#fetch_user option').remove();
    	jQuery('#ser_emp_Dialog').dialog('open');
    	jQuery('#hideSpan').hide();
}
 function ser_all_employer()
 {

   //jQuery('#add_new_emp_Dialog').dialog('close');
   //current_user_type
   cur_usr_type = "<?php echo getCurrentUserType();?>";
   if(cur_usr_type == 'admin')
   {
       if(jQuery('#agency_import').val() == "")
       {    alert("Please select agency");
            return;
       }
   }
   jQuery('#section_name_emp').val('user_search_import');
   showProcessing();
    jQuery.ajax({
                url: 'airs/users/usersfromAIRS.php',
                type: "post",
                cache: false,
                data     : jQuery("form#form_ser_emp").serialize(),
                datatype : "html",
                success: function(data) {
                   //alert(data);
                    //jQuery('#fetch_user option').remove();
                    HideBar();
                    jQuery('#emp_container').html(data);

                }
            });

}






function sel_import_user()
{
    jQuery('#section_name_emp').val('user_set_import');
    jQuery('#user_contactairsID').val(jQuery("#fetch_user option:selected").attr('id'));
    if(jQuery('#fetch_user').val())
    {
    //showProcessing();
    var r=confirm("Are you sure you want to continue? If OK, this user will be created in MAP");
    if (r==true)
    {
        jQuery.ajax({
                    url: 'airs/users/usersfromAIRS.php',
                    type: "post",
                    cache: false,
                    data     : jQuery("form#form_ser_emp").serialize(),
                    datatype : "json",
                    success: function(data) {
                       //alert(data);
                       jQuery('#ser_emp_Dialog').dialog('close');
                      // HideBar();
                       if(data == 0)
                           alert('Failed to import');
                       else
                           window.location.href = "index-mp.php?module=users&mount=menu-accounts.php&task=useredit&edit_user_id="+data;
                        //jQuery('#fetch_user option').remove();
                       // HideBar();
                        //jQuery('#emp_container').html(data);

                    }
                });
      }
    }
    else
    {
        alert("Please select a user");
    }
}

function addUser()
{
    if(verifyPassword()){
        if((jQuery('#mbr_usertype').val() != 'child'))
        {
            if(isEmpty('formlogin')==true)
            {
                  var emailvalue  = document.getElementById('mbr_email').value;
                  var editUser_id = document.getElementById('editUser_id').value;

                 jQuery.ajax({
                    url: "users/onclickemailcheck.php?t=" + Math.random()+"&q="+emailvalue+"&id="+editUser_id,
                    type: "get",
                    cache: false,
                    datatype : "json",
                    success: function(data) {
                        if(data=="No")
                        {
                        alert('The Username already Exists');
                        document.formlogin.mbr_email.focus();
                        }else{
                        document.formlogin.submit();
                        }

                    }
                });
            }
        }
        else if(jQuery('#mbr_usertype').val() == 'child')
        {
            if(isChildEmpty('formlogin')==true)
                document.formlogin.submit();
        }
    }

}

function verifyPassword()
{
    if(jQuery('#mbr_usertype').val() != 'child')
    {
        if (document.formlogin.mbr_password.value.length < 6)
        {
                alert("Password should contain minimum 6 characters");
                document.formlogin.mbr_password.focus();
                return false;
        }
        if (document.formlogin.mbr_password.value!=document.formlogin.mbr_repassword.value)
        {
                alert("Passwords do not match. Please try again.");
                document.formlogin.mbr_repassword.focus();
                return false;
        }
        return true;
    }
    else
         return true;
}


/*  script added by ratheesh; for birth parent settings  */
  jQuery("#calculator").dialog(
            {
                bgiframe: true,
                width: 512,
                height: 300,
                modal: true,
                autoOpen: false,
                draggable: true,
                resizable: false,
                title: 'Expected Due Date',
                close: function(event, ui) {  }
            }
        );


    jQuery("#btn_submit").click(function() {
            var calculatedDuedate  = document.getElementById('calculatedDuedate').value;
       // alert(calculatedDuedate);
        document.getElementById('un_edd_dt').value=calculatedDuedate;
            jQuery("#calculator").dialog("close");
            jQuery("#calculator").dialog("close");
        });
        jQuery("#btn_cancel").click(function() {
            jQuery("#calculator").dialog("close");
        });



function duedateCalc(){
     jQuery('#calculator').dialog('open');
}
/* end of birth parent settings */

// check to see if the username has been used
function checkUsername()
{
        if (document.formlogin.mbr_email.value=="")
        {
                alert("Username is a mandatory field. Please check and try again.");
                document.formlogin.mbr_email.focus();
                return;
        }
        document.formlogin.task.value='checkUsed';
        document.formlogin.submit();
}
//Ajax function to check the availability
function emailcheck()
{

  var emailvalue  = document.getElementById('mbr_email').value;
  var editUser_id = document.getElementById('editUser_id').value;
  if (checkEmail(emailvalue)==true)
   {
     if (window.XMLHttpRequest)
      {// code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
      }
    else
      {// code for IE6, IE5
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
    xmlhttp.onreadystatechange=function()
      {
      if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
        document.getElementById("emailtext").innerHTML=xmlhttp.responseText;

        }
      }
    xmlhttp.open("GET","users/emailcheck.php?t=" + Math.random()+"&q="+emailvalue+"&id="+editUser_id,true);
    xmlhttp.send();
   return false;

  }
   else
  {
  document.getElementById("emailtext").innerHTML="";
  return true;
  }
}


function showProcessing()
{
    REDIPS.dialog.init();
    REDIPS.dialog.op_high = 60;
    REDIPS.dialog.fade_speed = 18;
    REDIPS.dialog.show(200, 200, 'ajax-loader.gif');
}
function HideBar()
{
    REDIPS.dialog.hide('undefined');

}
$current_user_type = "<?php echo getCurrentUserType()?>";
// Hadling mapping between case worker and group on clicking on  it
jQuery(document).ready(function()
{
    $current_usertype_val = jQuery('#mbr_usertype').val();

    if($current_usertype_val =='adoptive_parent' || $current_usertype_val =='birth_parent')
    {

        jQuery("#case_worker").bind('change',function()
        {
            changeAgencyGrp($current_usertype_val);
            //return false;
        });
        if ($current_user_type == 'admin')
        {
            jQuery("#adminselectGroups").bind('change',function()
            {
                //alert(jQuery('#ap_caseworker').val());
                if (jQuery('#case_worker').val()) { }
                else
                {
                    changecwgroup($current_usertype_val);
                }
                //return false;

            });
        }
        else
        {
            jQuery("#selgrp").bind('change',function()
            {
                //alert(jQuery('#ap_caseworker').val());
                if (jQuery('#case_worker').val()) { }
                else
                {
                    changecwgroup($current_usertype_val);
                }
               // return false;

            });
        }
    }
    jQuery('#mbr_usertype').change(function() {
        $current_usertype_val = jQuery('#mbr_usertype').val();
        if($current_usertype_val =='adoptive_parent' || $current_usertype_val =='birth_parent')
        {

            jQuery("#case_worker").bind('change',function()
            {
                changeAgencyGrp($current_usertype_val);
                //return false;
            });
            if ($current_user_type == 'admin')
            {
                jQuery("#adminselectGroups").bind('change',function()
                {
                    //alert(jQuery('#ap_caseworker').val());
                    if (jQuery('#case_worker').val()) { }
                    else
                    {
                        changecwgroup($current_usertype_val);
                    }
                    //return false;

                });
            }
            else
            {
                jQuery("#selgrp").bind('change',function()
                {
                    //alert(jQuery('#ap_caseworker').val());

                    if (jQuery('#case_worker').val()) { }
                    else
                    {
                        changecwgroup($current_usertype_val);
                    }
                    //return false;

                });
            }
      }
    });
    // For handling the case casewoker is not selected and agency gruop slected
      var groupvalselected ='<?php echo trim($mbr_groupid) ?>';
      if (jQuery('#newOredit').val() == 'useredit' && jQuery('#cwforselction').val() == '' && groupvalselected )
      {	  jQuery('#rotate').show();
        jQuery.ajax({
                      url: 'users/userflow/cwMappingTogroup.php',
                      type: "post",
                      cache: false,
                      data : {casetype: 'cwnotselandgrpsel',allcaseworker: jQuery('#impode_caseworkerArr').val(),user_group_sel: jQuery('#agencygruopsel').val()},
                      success: function(data) {
                          jQuery('#case_worker option').remove();
                          jQuery('#case_worker').append((data));
                         jQuery('#rotate').hide();

                      }
          });
      }
});

function changeAgencyGrp($current_usertype_val)
{
    var groupval = ($current_usertype_val =='adoptive_parent') ? jQuery('#impode_grp_id_loadap').val():jQuery('#impode_grp_id_loadbp').val();
    jQuery('#rotate1').show();
    jQuery.ajax({
          url: 'users/userflow/cwMappingTogroup.php',
          type: "post",
          cache: false,
          data : {casetype: 'agency',whichParent: $current_usertype_val,selectdCW: jQuery('#case_worker').val(), allagencygrpval: groupval,user_group_sel: jQuery('#agencygruopsel').val()},
          success: function(data) {
                    //alert(data);
                    if ($current_user_type =='admin')
                      {
                            jQuery('.adminselectGroups option').remove();
                            jQuery('.adminselectGroups').append((data));
                            jQuery('#rotate1').hide();
                      }
                      else
                      {
                            jQuery('#selgrp option').remove();
                            jQuery('#selgrp').append((data));
                            jQuery('#rotate1').hide();
                      }
          }
  });
  // For handling if all caseowrker unselected
      if (jQuery('#case_worker').val()) { }
      else
      {      jQuery('#rotate').show();
              jQuery.ajax({
              url: 'users/userflow/cwMappingTogroup.php',
              type: "post",
              cache: false,
              data : {casetype: 'allcwdisselected',whichParent: $current_usertype_val,allcaseworker: jQuery('#impode_caseworkerArr').val(),cwforselction: jQuery('#cwforselction').val()},
              success: function(data) {
                        //alert(data);

                      jQuery('#case_worker option').remove();
                      jQuery('#case_worker').append((data));
                      jQuery('#rotate').hide();
              }
          });
      }
}
function changecwgroup($current_usertype_val)
{
    var selectdAg = ($current_user_type =='admin') ?jQuery('#adminselectGroups').val() :jQuery('#selgrp').val();
    jQuery('#rotate').show();
    jQuery.ajax({
          url: 'users/userflow/cwMappingTogroup.php',
          type: "post",
          cache: false,
          data : {casetype: 'cw',whichParent: $current_usertype_val,selectdAg: selectdAg, caseworker: jQuery('#impode_caseworkerArr').val(),selectdCWforagency: jQuery('#case_worker').val(),cwforselction: jQuery('#cwforselction').val()},
          success: function(data) {
                    //alert(data);

                  jQuery('#case_worker option').remove();
                  jQuery('#case_worker').append((data));
                   jQuery('#rotate').hide();


          }
  });
}

</script>
<script>

    caseScrtyVal = '<?php echo $caseScrtyVal ?> ';
    updateFlag = '<?php echo $updateFlag ?> ';
    alertcomeOrnot = '<?php echo $alertcomeOrnot ?> ';


    if(caseScrtyVal == '2' || alertcomeOrnot == 1)
    {
        alert("No permission to update or edit this client");
    }
      function tooltips(){
    $$('a.tipz').each(function(element,index) {

            //element.store('tip:title', 'Adoption Portal');
            element.store('tip:text',"" );
    });

    //create the tooltips
    var tipz = new Tips('.tipz',{
            className: 'tipz',
            fixed: true,
            hideDelay: 50,
            showDelay: 50,
            offset: {'x': 10, 'y': 25}


        });
    }

    mygrid.attachEvent("onXLE", function(grid_obj,count){
    window.addEvent('domready', function() {
    setTimeout('tooltips()', 500);
    });
});


    </script>


<script language="javascript" type="text/javascript">
    var container = jQuery('<div id="personPopupContainer" style="position:absolute;left:100px;top:200px;z-index: 20000;border:2px solid #C0C0C0;background:white;height:auto;width:320px;display:none;">'
          + ''
          + '</div>');
            //jQuery dialog
   jQuery(document).ready(function() {
        jQuery('.taskdivID').append(container);
    });
    var cX = 0; var cY = 0; var rX = 0; var rY = 0;
    function UpdateCursorPosition(e){ cX = e.pageX; cY = e.pageY;}
    function UpdateCursorPositionDocAll(e){ cX = event.clientX; cY = event.clientY;}
    if(document.all) { document.onmousemove = UpdateCursorPositionDocAll; }
    else { document.onmousemove = UpdateCursorPosition; }
    function taskDetShow(element)
    {
        clientID = element.id;
        var curleft = 0;
        var curtop = 0;
        if(element.offsetParent)
            while(1)
            {
              curleft += element.offsetLeft;
              curtop += element.offsetTop;
              if(!element.offsetParent)
                break;
              element = element.offsetParent;
            }
        else if(element.x)
        {
            curleft += element.x;
            curtop  +=element.y;
        }
        if(document.all)
            curleft = curleft +75;
        else
            curleft =  curleft +30;
        jQuery(document).ready(function() {
                 //alert(cX);
                 var pos = 100;
                  var width =50;
                  container.css({
                      left: (curleft) + 'px',
                      top: curtop + 'px'
                  });
                    jQuery.ajax({
                        url: 'users/infosec.php',
                        type: "GET",
                        cache: false,
                        data: {param1:clientID},
                        datatype: "html",
                        success: function(data) {
                            jQuery('#personPopupContainer').html(data);
                        }
                    });

                  container.css('display', 'block');

         });
    }


    function taskDetremove()
    {
        document.getElementById('personPopupContainer').innerHTML = '';
        container.css('display', 'none');
    }

 </script>