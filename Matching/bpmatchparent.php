<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php
//error_reporting(1);
define('BX_PROFILE_PAGE', 1);
require_once( './inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
bx_import('BxTemplProfileView');
bx_import('BxDolInstallerUtils');

//require_once ('dbConnection.php');
require_once ('MatchClass.php');
$iMemberId = getLoggedId();
$sql = "SELECT id FROM bx_groups_main where author_id=".$iMemberId;
$result = mysql_query($sql);
$row = mysql_fetch_row($result);
$number = $row[0];

//echo "aa ".$_GET['userID']."  selparentid  ".$_GET['parentID']."   printflag ".$_GET['printflag'] ;
$printflag  = ($_GET['printflag'])?$_GET['printflag']:0;
if(($number != '' || $GLOBALS['logged']['admin']) || ($printflag)) {
//$pid = getLoggedId();
$pid   = isset($_GET['userID'])?$_GET['userID']:0;
//echo " logg parentID ".$pid."<br/>";
$SelParentID = $_GET['parentID'];
//echo " selectes parentID ".$SelParentID."<br/>";
$_aInfo=getProfileInfo($pid);
$selParenttype = $_aInfo['ProfileType'];
//echo " selectes parent Type ".$selParenttype."<br/>";

$APPref                 = getuserPreferenceDetails(2,$pid);
$selBPPref              = getuserPreferenceDetails(4,$SelParentID);
$selBPDetArray          = getUserDataValues(2,$SelParentID);
$fetchMatchArray        = fetchIndividualmatches(2,$pid,$SelParentID);
$reversMatchArray       = fetchIndividualmatches(4,$SelParentID,$pid);

$userDetails  = getuserDetails($SelParentID);
foreach($userDetails as $key=>$value)
{
    $selBPDetArray[$key]     = $value;
}
$APuserDetails  = getuserDetails($pid);
$selAPDetArray          = getUserDataValues(4,$pid);
/*
echo "AP preference ------------------------------------------------------"."<br/>";
print_r($APPref);echo"<br/><br/>";
echo "Selected BP preference ------------------------------------------------------"."<br/>";
print_r($selBPPref);echo"<br/><br/>";
echo "Selected BP user Details ------------------------------------------------------"."<br/>";
print_r($selBPDetArray);echo"<br/><br/>";
echo "AP Match details ------------------------------------------------------"."<br/>";
print_r($fetchMatchArray);echo"<br/><br/>";
echo "BP reverse match Details ------------------------------------------------------"."<br/>";
print_r($reversMatchArray);echo"<br/><br/>";
echo "AP user Details ------------------------------------------------------"."<br/>";
print_r($APuserDetails);echo"<br/><br/>";
echo "AP user Values ------------------------------------------------------"."<br/>";
print_r($selAPDetArray);echo"<br/><br/>";
*/
//print_r($reversMatchArray);echo"<br/><br/>";
$agency             = db_arr("SELECT `uri` FROM `bx_groups_main` WHERE `id` =".$_aInfo['AdoptionAgency']);
$listing_link       = $GLOBALS['site']['url'] .'page/matches?parentID='.$pid;
?>
<link rel="stylesheet" href="Matching/css/stylesheet.css" />
<link rel="STYLESHEET" type="text/css" href="PDFUser/Processing/dialog.css">
<script type="text/javascript" src= "PDFUser/Processing/dialog.js"></script>
<div id="print_main" >
<div id="main" style="width:970px;">
    <div style="position:relative;width:100%;height:35px;">
        <div style="position:relative;float:left;margin-left:730px;margin-top:10px;width:125px;">
            <a href="<?php echo $GLOBALS['site']['url']; ?>m/groups/browse_fans_list/<?php echo $agency['uri']; ?>">
            <div class="button_wrapper" style="width:113px;">
                <span class="form_input_submit" style="position:relative;top:5px;left:2px;color:#333333;font-family:Verdana,Arial;font-size:11px;">Back to Members</span>
                <div class="button_wrapper_close"></div>
            </div>
             </a>
        </div>
        <div style="position:relative;float:left;margin-left:02px;margin-top:10px;width:100px;">
            <a href="<?php echo $listing_link; ?>">
            <div class="button_wrapper" style="width:100px;">
                <span class="form_input_submit" style="position:relative;top:5px;left:2px;color:#333333;font-family:Verdana,Arial;font-size:11px;">Back to Listing</span>
                <div class="button_wrapper_close"></div>
            </div>
             </a>
        </div>
    </div>
    <div class="dummy" style="height:4px;">
        <!--dummy-->
    </div>
    <div class="apmatch">
        <div id="apmatch_id01" style="margin-left: 05px; width:430px;">
            <div id="apmatch_id03" style="width: 420px;">
                <div id="apmatch_id05" style="width: 143px;">
                    <!--image container-->
                    <?php
			   $img                  = getProfileImage($SelParentID);
                        //$img                = 'modules/boonex/avatar/data/images/'.$selBPDetArray['Avatar'].'.jpg';
                        //$img                = 'modules/boonex/photos/data/files/4411.jpg';
                        //echo $img;
                        $imagepath          = ($img)?$img:$GLOBALS['site']['url'].'templates/base/images/icons/man_medium.gif';
                        $imageResolution    = getUploadImageResolution($imagepath,192,143);
                        //print_r($imageResolution);
                        $imgHeight          = $imageResolution['imageHeight'];
                        $imgWidth           = $imageResolution['imageWidth'];
                        $imgMargintop       = $imageResolution['margintop'];
                        $imgMarginleft      = $imageResolution['marginleft'];
                        $other_parent_link  = $GLOBALS['site']['url'] .$selBPDetArray['NickName'];
                    ?>
                    <a href="<?php echo $other_parent_link?>" target="_blank">
                    <img alt="<?php echo $selBPDetArray['FirstName'];?>" src ="<?php echo $imagepath;?>" height="<?php echo $imgHeight;?>" width ="<?php echo $imgWidth;?>" style="margin-top:<?php echo $imgMargintop;?>px;margin-left:<?php echo $imgMarginleft;?>px;" />
                    </a>
                </div>
                <div id="apmatch_id06" style="width: 259px; margin-left: 14px;">
                    <!--image container-->
                    <?php
			    $img                = getProfileImage($pid);
                        //$img                = 'modules/boonex/avatar/data/images/'.$APuserDetails['Avatar'].'.jpg';
                        //$img                = 'modules/boonex/photos/data/files/4411.jpg';
                        //echo $img;
                        $imagepath          = ($img)?$img:$GLOBALS['site']['url'].'templates/base/images/icons/man_medium.gif';
                        $imageResolution    = getUploadImageResolution($imagepath,192,259);
                        //print_r($imageResolution);
                        $imgHeight          = $imageResolution['imageHeight'];
                        $imgWidth           = $imageResolution['imageWidth'];
                        $imgMargintop       = $imageResolution['margintop'];
                        $imgMarginleft      = $imageResolution['marginleft'];
                        $parent_link        = $GLOBALS['site']['url'] .$APuserDetails['NickName'];
                        $message_link       = $GLOBALS['site']['url']."ContactAgency.php?ID=".$SelParentID;
                    ?>
                    <a href="<?php echo $parent_link?>" target="_blank">
                    <img alt="<?php echo $APuserDetails['FirstName'];?>" src ="<?php echo $imagepath;?>" height="<?php echo $imgHeight;?>" width ="<?php echo $imgWidth;?>" style="margin-top:<?php echo $imgMargintop;?>px;margin-left:<?php echo $imgMarginleft;?>px;" />
                    </a>
                </div>
            </div>
            <div id="apmatch_id08">
                <?php if($APuserDetails['agencyID'] == $selBPDetArray['agencyID']){?>
                <div class="apmatch_cl05">
                    <!--icons-->
                    <img id="print_local" class="icon_class" src="Matching/images/print.png" alt="Print" />
                </div>
                <?php } if($APuserDetails['agencyID'] != $selBPDetArray['agencyID']){?>
                <div class="apmatch_cl05">
                    <!--icons-->
                     <a href="<?php echo $message_link?>">
                        <img class="icon_class" src="Matching/images/message.png" onClick='window.location = "<?php $site['url'] ?>ContactAgency.php?ID=<?php echo $pid?>"' alt="Message" />
                     </a>
                </div>
                <div class="apmatch_cl05">
                    <!--icons-->
                    <img class="icon_class" src="Matching/images/Phone.png" onClick='window.location = "<?php $site['url'] ?>ContactAgency.php?ID=<?php echo $pid?>"' alt="Phone" />
                </div>
                <?php }?>
            </div>
            <?php
            
                $coupleDetails      = getCoupleDetails($SelParentID);
                $personName         = ($coupleDetails['coupleFirstName'])?$selBPDetArray ['FirstName']. ' &amp; '.$coupleDetails['coupleFirstName']:$selBPDetArray ['FirstName'];
                //$personName         = $selBPDetArray['FirstName']. ' & '.$selBPDetArray['LastName'];
                $percentageMatch    = $fetchMatchArray['Percentage'];
                $persondayswaiting  = ($selBPDetArray['daysWaiting'])?$selBPDetArray['daysWaiting']:'0';
            ?>
            <div class="dummy" style="height: 20px;">
                <!--dummy-->
            </div>
            <div id="apmatch_id04" style="width: 380px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td align="center" width="60%" class="apmatch_cl01 apmatch_cl03">
                            Criteria
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl03">
                            Birth Mother / Child
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01">
                            Name
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><?php echo $personName;?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01">
                            Agency
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><?php echo $selBPDetArray['Agency'];?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01">
                            % Match
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><?php echo $percentageMatch;?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01 apmatch_cl04">
                            Birthmother Characteristics
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02">
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01">
                            Drug Use
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><?php echo ($selBPDetArray['Druguse'])?$selBPDetArray['Druguse']:"NA";?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01">
                            Smoking
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><?php echo ($selBPDetArray['Smoking'])?$selBPDetArray['Smoking']:"NA";?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01">
                            Drinking
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><?php echo ($selBPDetArray['Drinking'])?$selBPDetArray['Drinking']:"NA";?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01">
                            Adoption type
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><?php echo ($selBPDetArray['Adoptiontype'])?$selBPDetArray['Adoptiontype']:"NA";?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01">
                            Family History
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><?php echo ($selBPDetArray['Familyhistory'])?$selBPDetArray['Familyhistory']:"NA";?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01">
                            Conception Method
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><?php echo ($selBPDetArray['Conception'])?$selBPDetArray['Conception']:"NA";?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01">
                            &nbsp;&nbsp;
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02">
                            &nbsp;&nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01 apmatch_cl04">
                            Child Characteristics
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02">
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01">
                            Ethnicity
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><?php echo ($selBPDetArray['Ethnicity'])?$selBPDetArray['Ethnicity']:"NA";?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01">
                            Age
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><?php echo ($selBPDetArray['Age'])?calc_age($selBPDetArray['Age']):"NA";?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01">
                            Special Needs
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><?php echo ($selBPDetArray['Specialneeds'])?$selBPDetArray['Specialneeds']:"NA";?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01">
                            Sex
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><?php echo ($selBPDetArray['Sex'])?$selBPDetArray['Sex']:"NA";?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01">
                            Number of Children
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><?php echo ($selBPDetArray['noofchildren'])?$selBPDetArray['noofchildren']:"NA";?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="apmatch_id02" style="width:530px;">
            <div id="apmatch_id07" style="width:530px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td align="center" width="43%" class="apmatch_cl01 apmatch_cl03">
                            Criteria
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl03">
                            Birth Parent / Child
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02 apmatch_cl03">
                            Adoptive parent
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01 apmatch_cl04">
                            Birthmother Characteristics
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02">
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02">
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Drug Use
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02"><?php echo getMatchImageFlag($fetchMatchArray['Druguse'],'Druguse',$selBPDetArray['Druguse']);?>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02 apmatch_cl06"><p style="width:140px;"><?php echo ($APPref['Druguse'])?$APPref['Druguse']:"NA"; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Smoking
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02"><?php echo getMatchImageFlag($fetchMatchArray['Smoking'],'Smoking',$selBPDetArray['Smoking']);?>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02 apmatch_cl06"><p style="width:140px;"><?php echo ($APPref['Smoking'])?$APPref['Smoking']:"NA"; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Drinking
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02"><?php echo getMatchImageFlag($fetchMatchArray['Drinking'],'Drinking',$selBPDetArray['Drinking']);?>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02 apmatch_cl06"><p style="width:140px;"><?php echo ($APPref['Drinking'])?$APPref['Drinking']:"NA"; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Adoption Type
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02"><?php echo getMatchImageFlag($fetchMatchArray['Adoptiontype'],'Adoption Type',$selBPDetArray['Adoptiontype']);?>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02 apmatch_cl06"><p style="width:140px;"><?php echo ($APPref['Adoptiontype'])?$APPref['Adoptiontype']:"NA"; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Family History
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02"><?php echo getMatchImageFlag($fetchMatchArray['Familyhistory'],'Family History',$selBPDetArray['Familyhistory']);?>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02 apmatch_cl06"><p style="width:140px;"><?php echo ($APPref['Familyhistory'])?$APPref['Familyhistory']:"NA"; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Conception Method
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02"><?php echo getMatchImageFlag($fetchMatchArray['Conception'],'Conception Method',$selBPDetArray['Conception']);?>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02 apmatch_cl06"><p style="width:140px;"><?php echo ($APPref['Conception'])?$APPref['Conception']:"NA"; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            &nbsp;&nbsp;
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02">
                            &nbsp;&nbsp;
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02">
                            &nbsp;&nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01 apmatch_cl04">
                            Child Charcteristics
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02">
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02">
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01 ">
                            Ethnicity
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 "><?php echo getMatchImageFlag($fetchMatchArray['Ethnicity'],'Ethnicity',$selBPDetArray['Ethnicity']);?>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02 apmatch_cl06"><p style="width:140px;"><?php echo ($APPref['Ethnicity'])?$APPref['Ethnicity']:"NA"; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01 ">
                            Age
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02"><?php echo getMatchImageFlag($fetchMatchArray['Age'],'Child Age',$selBPDetArray['Age']);?>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02 apmatch_cl06"><p style="width:140px;"><?php echo ($APPref['Age'])?$APPref['Age']:"NA"; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01 ">
                            Special Needs
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02"><?php echo getMatchImageFlag($fetchMatchArray['Specialneeds'],'Specialneeds',$selBPDetArray['Specialneeds']);?>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02 apmatch_cl06"><p style="width:140px;"><?php echo ($APPref['Specialneeds'])?$APPref['Specialneeds']:"NA"; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01 ">
                            Sex
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02"><?php echo getMatchImageFlag($fetchMatchArray['Sex'],'Sex',$selBPDetArray['Sex']);?>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02 apmatch_cl06"><p style="width:140px;"><?php echo ($APPref['Sex'])?$APPref['Sex']:"NA"; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01 ">
                            Number of Children
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02"><?php echo getMatchImageFlag($fetchMatchArray['noofchildren'],'No.of Children',$selBPDetArray['noofchildren']);?>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02 apmatch_cl06"><p style="width:140px;"><?php echo ($APPref['noofchildren'])?$APPref['noofchildren']:"NA"; ?></p>
                        </td>
                    </tr>
                </table>
                <div class="dummy" style="height: 10px;">
                    <!--dummy-->
                </div>
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                     <tr>
                        <td align="center" width="43%" class="apmatch_cl01 apmatch_cl03">
                            Criteria
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl03">
                            Birth Parent / Child
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02 apmatch_cl03">
                            Adoptive parent
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01 apmatch_cl04">
                            AP Charcteristics
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02">
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02">
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Relationship
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl06"><p style="width:140px;"><?php echo ($selBPPref['Relationship'])?$selBPPref['Relationship']:"NA"; ?></p>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02"><?php echo getMatchImageFlag($reversMatchArray['Relationship'],'Relationship',$selAPDetArray['Relationship']);?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Ethnicity
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl06"><p style="width:140px;"><?php echo ($selBPPref['Ethnicity'])?$selBPPref['Ethnicity']:"NA"; ?></p>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02"><?php echo getMatchImageFlag($reversMatchArray['Ethnicity'],'Ethnicity',$selAPDetArray['Ethnicity']);?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Location
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl06"><p style="width:140px;"><?php echo ($selBPPref['State'])?$selBPPref['State']:"NA"; ?></p>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02"><?php echo getMatchImageFlag($reversMatchArray['State'],'State',$selAPDetArray['State']);?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Religion
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl06"><p style="width:140px;"><?php echo ($selBPPref['Religion'])?$selBPPref['Religion']:"NA"; ?></p>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02"><?php echo getMatchImageFlag($reversMatchArray['Religion'],'Religion',$selAPDetArray['Religion']);?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Pets
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl06"><p style="width:140px;"><?php echo ($selBPPref['Pets'])?$selBPPref['Pets']:"NA"; ?></p>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02"><?php echo getMatchImageFlag($reversMatchArray['Pets'],'Pets',$selAPDetArray['Pets']);?>
                        </td>
                    </tr>                    
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Degree
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl06"><p style="width:140px;"><?php echo ($selBPPref['Degree'])?$selBPPref['Degree']:"NA"; ?></p>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02"><?php echo getMatchImageFlag($reversMatchArray['Degree'],'Degree',$selAPDetArray['Degree']);?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Stay-at-home parent
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl06"><p style="width:140px;"><?php echo ($selBPPref['Stayathome'])?$selBPPref['Stayathome']:"NA"; ?></p>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02"><?php echo getMatchImageFlag($reversMatchArray['Stayathome'],'Stay at Home',$selAPDetArray['Stayathome']);?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Reason for adopting
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl06"><p style="width:140px;"><?php echo ($selBPPref['Reason'])?$selBPPref['Reason']:"NA"; ?></p>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02"><?php echo getMatchImageFlag($reversMatchArray['Reason'],'Reason fo Adopting',$selAPDetArray['Reason']);?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Home Type
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl06"><p style="width:140px;"><?php echo ($selBPPref['Housetype'])?$selBPPref['Housetype']:"NA"; ?></p>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02"><?php echo getMatchImageFlag($reversMatchArray['Housetype'],'House Type',$selAPDetArray['Housetype']);?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Family structure
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl06"><p style="width:140px;"><?php echo ($selBPPref['Familystructure'])?$selBPPref['Familystructure']:"NA"; ?></p>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02"><?php echo getMatchImageFlag($reversMatchArray['Familystructure'],'Family Structure',$selAPDetArray['Familystructure']);?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Adoption type
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl06"><p style="width:140px;"><?php echo ($selBPPref['Adoptiontype'])?$selBPPref['Adoptiontype']:"NA"; ?></p>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02"><?php echo getMatchImageFlag($reversMatchArray['Adoptiontype'],'Adoption Type',$selAPDetArray['Adoptiontype']);?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Parents Age
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl06"><p style="width:140px;"><?php echo ($selBPPref['age'])?$selBPPref['age']:"NA"; ?></p>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02"><?php echo getMatchImageFlag($reversMatchArray['age'],'Parents Age',$selAPDetArray['age']);?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="clear">
            <!--clear both division-->
        </div>
    </div>
</div>
</div>
<script type="text/javascript">

jQuery(document).ready(function() {

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
    jQuery('#print_local').click(function(){
            /*var mywindow = window.open('','mydiv','height=400,width=600');
            mywindow.document.write('<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['site']['url']?>Matching/css/stylesheet.css" />');           
            mywindow.document.write(jQuery('#print_main').html());
            mywindow.document.close();
            mywindow.print();
            mywindow.close();
            return true;*/
            showProcessing();
            $.ajax({
                 url: 'Matching/createFirstMatch.php',
                 type: "POST",
                 cache: false,
                 data: {sel_parent_ID : '<?php echo $pid;?>',pageid:'bp_2',sel_match_ID:'<?php echo $SelParentID;?>'},
                 success: function(data) {
                                HideBar();
                                //alert(data);
                                window.open(data, 'null', 'location=no,menubar=no,toolbar=no,scrollbars=no,resizable=yes,width=1100px, height=900px', 'Download');
                  }
            });
    });

});
</script>
<?php
}
else {

}
?>