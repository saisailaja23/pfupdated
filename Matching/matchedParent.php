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
$printflag  = ($_GET['printflag'])?$_GET['printflag']:0;
if(($number != '' || $GLOBALS['logged']['admin']) || ($printflag)) {

//$pid = getLoggedId();
$pid   = isset($_GET['userID'])?$_GET['userID']:0;
//echo " logg parentID ".$pid."<br/>";
$SelParentID = $_GET['parentID'];
//echo " selectes parentID ".$SelParentID."<br/>";
$_aInfo=getProfileInfo($SelParentID);
$selParenttype = $_aInfo['ProfileType'];
//echo " selectes parent Type ".$selParenttype."<br/>";

$BPPref                 = getuserPreferenceDetails(4,$pid);
$selAPPref              = getuserPreferenceDetails(2,$SelParentID);
$selAPDetArray          = getUserDataValues(4,$SelParentID);
$fetchMatchArray        = fetchIndividualmatches(4,$pid,$SelParentID);
$reversMatchArray       = fetchIndividualmatches(2,$SelParentID,$pid);

$userDetails  = getuserDetails($SelParentID);
foreach($userDetails as $key=>$value)
{
    $selAPDetArray[$key]     = $value;
}
$BPuserDetails  = getuserDetails($pid);
$selBPDetArray          = getUserDataValues(2,$pid);
/*
echo "BP preference ------------------------------------------------------"."<br/>";
print_r($BPPref);echo"<br/><br/>";
echo "Selected AP preference ------------------------------------------------------"."<br/>";
print_r($selAPPref);echo"<br/><br/>";
echo "Selected AP user Details ------------------------------------------------------"."<br/>";
print_r($selAPDetArray);echo"<br/><br/>";
echo "BP Match details ------------------------------------------------------"."<br/>";
print_r($fetchMatchArray);echo"<br/><br/>";
echo "AP reverse match Details ------------------------------------------------------"."<br/>";
print_r($reversMatchArray);echo"<br/><br/>";
echo "BP user Details ------------------------------------------------------"."<br/>";
print_r($BPuserDetails);echo"<br/><br/><br/>";
echo "BP user Values ------------------------------------------------------"."<br/>";
print_r($selBPDetArray);echo"<br/><br/>";
*/
//print_r($fetchMatchArray);echo"<br/>";
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
        <div id="apmatch_id01" style="margin-left: 05px;width:430px;">
            <div id="apmatch_id03">
                <div id="apmatch_id05">
                    <!--image container-->
                     <?php
			    $img                  = getProfileImage($SelParentID);
                        //$img                = 'modules/boonex/avatar/data/images/'.$selAPDetArray['Avatar'].'.jpg';
                        //$img                = 'modules/boonex/photos/data/files/4411.jpg';
                        //echo $img;
                        $imagepath          = ($img)?$img:'templates/base/images/icons/man_medium.gif';
                        $imageResolution    = getUploadImageResolution($imagepath,192,255);
                        //print_r($imageResolution);
                        $imgHeight          = $imageResolution['imageHeight'];
                        $imgWidth           = $imageResolution['imageWidth'];
                        $imgMargintop       = $imageResolution['margintop'];
                        $imgMarginleft      = $imageResolution['marginleft'];
                        $other_parent_link  = $GLOBALS['site']['url'] .$selAPDetArray['NickName'];
                    ?>
                    <a href="<?php echo $other_parent_link?>" target="_blank">
                    <img alt="<?php echo $selAPDetArray['FirstName'];?>" src ="<?php echo $imagepath;?>" height="<?php echo $imgHeight;?>" width ="<?php echo $imgWidth;?>" style="margin-top:<?php echo $imgMargintop;?>px;margin-left:<?php echo $imgMarginleft;?>px;" />
                    </a>
                </div>
                <div id="apmatch_id06">
                    <!--image container-->
                    <?php
			  $img                = getProfileImage($pid);
                        //$img                = 'modules/boonex/avatar/data/images/'.$BPuserDetails['Avatar'].'.jpg';
                        //$img                = 'modules/boonex/photos/data/files/4411.jpg';
                        //echo $img;
                        $imagepath          = ($img)?$img:'templates/base/images/icons/man_medium.gif';
                        $imageResolution    = getUploadImageResolution($imagepath,192,157);
                        //print_r($imageResolution);
                        $imgHeight          = $imageResolution['imageHeight'];
                        $imgWidth           = $imageResolution['imageWidth'];
                        $imgMargintop       = $imageResolution['margintop'];
                        $imgMarginleft      = $imageResolution['marginleft'];
                        $parent_link        = $GLOBALS['site']['url'] .$BPuserDetails['NickName'];
                    ?>
                    <a href="<?php echo $parent_link?>"  target="_blank">
                    <img alt="<?php echo $BPuserDetails['FirstName'];?>" src ="<?php echo $imagepath;?>" height="<?php echo $imgHeight;?>" width ="<?php echo $imgWidth;?>" style="margin-top:<?php echo $imgMargintop;?>px;margin-left:<?php echo $imgMarginleft;?>px;" />
                    </a>
                </div>
            </div>
            <div id="apmatch_id08">
                <?php if($BPuserDetails['agencyID'] == $selAPDetArray['agencyID']){?>
                <div class="apmatch_cl05">
                    <!--icons-->
                    <img id="print_local" class="icon_class" src="Matching/images/print.png" alt="Print" />
                </div>
                <?php }if($BPuserDetails['agencyID'] != $selAPDetArray['agencyID']){?>
                <div class="apmatch_cl05">
                    <!--icons-->
                    <img class="icon_class" src="Matching/images/message.png" onClick='window.location = "<?php $site['url'] ?>ContactAgency.php?ID=<?php echo $pid?>"' alt="Message" />
                </div>
                <div class="apmatch_cl05">
                    <!--icons-->
                    <img class="icon_class" src="Matching/images/Phone.png" onClick='window.location = "<?php $site['url'] ?>ContactAgency.php?ID=<?php echo $pid?>"' alt="Phone" />
                </div>
                <?php }?>
            </div>
            <?php
                $coupleDetails      = getCoupleDetails($SelParentID);
                $personName         = ($coupleDetails['coupleFirstName'])?$selAPDetArray ['FirstName']. ' &amp; '.$coupleDetails['coupleFirstName']:$selAPDetArray ['FirstName'];
                //$personName         = $selAPDetArray['FirstName']. ' & '.$selAPDetArray['LastName'];
                $percentageMatch    = $fetchMatchArray['Percentage'];
                $persondayswaiting  = ($selAPDetArray['daysWaiting'])?$selAPDetArray['daysWaiting']:'0';
            ?>
            <div class="dummy" style="height: 20px;">
                <!--dummy-->
            </div>
            <div id="apmatch_id04">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01 apmatch_cl03">
                            Category
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl03">
                            Answer
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Name
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo $personName;?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            % Match
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo $percentageMatch;?>%
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Agency
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo $selAPDetArray['Agency'];?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Days Waiting
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo $persondayswaiting;?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Relationship
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo ($selAPDetArray['Relationship'])?$selAPDetArray['Relationship']:"NA" ;?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Ethnicity
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo ($selAPDetArray['Ethnicity'])?$selAPDetArray['Ethnicity']:"NA" ;?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Location
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo ($selAPDetArray['State'])?$selAPDetArray['State']:"NA" ;?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Religion
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo ($selAPDetArray['Religion'])?$selAPDetArray['Religion']:"NA" ;?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Pets
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo ($selAPDetArray['Pets'])?$selAPDetArray['Pets']:"NA" ;?>
                        </td>
                    </tr>                    
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Degree
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo ($selAPDetArray['Degree'])?$selAPDetArray['Degree']:"NA" ;?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Stay-at-home parent
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo ($selAPDetArray['Stayathome'])?$selAPDetArray['Stayathome']:"NA" ;?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Reason for adopting
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo ($selAPDetArray['Reason'])?$selAPDetArray['Reason']:"NA" ;?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            House Type
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo ($selAPDetArray['Housetype'])?$selAPDetArray['Housetype']:"NA" ;?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Family Structure
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo ($selAPDetArray['Familystructure'])?$selAPDetArray['Familystructure']:"NA" ;?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Adoption Type
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo ($selAPDetArray['Adoptiontype'])?$selAPDetArray['Adoptiontype']:"NA" ;?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Parents Age
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo ($selAPDetArray['age'])?calc_age($selAPDetArray['age']):"NA" ;?>
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
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl06"><p style="width:152px;"><?php echo ($BPPref['Relationship'])?$BPPref['Relationship']:"NA" ;?></p>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02"><?php echo getMatchImageFlag($fetchMatchArray['Relationship'],'Relationship',$selAPDetArray['Relationship']);?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Ethnicity
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl06"><p style="width:152px;"><?php echo ($BPPref['Ethnicity'])?$BPPref['Ethnicity']:"NA" ;?></p>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02"><?php echo getMatchImageFlag($fetchMatchArray['Ethnicity'],'Ethnicity',$selAPDetArray['Ethnicity']);?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Location
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl06"><p style="width:152px;"><?php echo ($BPPref['State'])?$BPPref['State']:"NA" ;?></p>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02"><?php echo getMatchImageFlag($fetchMatchArray['State'],'State',$selAPDetArray['State']);?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Religion
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl06"><p style="width:152px;"><?php echo ($BPPref['Religion'])?$BPPref['Religion']:"NA" ;?></p>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02"><?php echo getMatchImageFlag($fetchMatchArray['Religion'],'Religion',$selAPDetArray['Religion']);?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Pets
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl06"><p style="width:152px;"><?php echo ($BPPref['Pets'])?$BPPref['Pets']:"NA" ;?></p>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02"><?php echo getMatchImageFlag($fetchMatchArray['Pets'],'Pets',$selAPDetArray['Pets']);?>
                        </td>
                    </tr>                   
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Degree
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl06"><p style="width:152px;"><?php echo ($BPPref['Degree'])?$BPPref['Degree']:"NA" ;?></p>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02"><?php echo getMatchImageFlag($fetchMatchArray['Degree'],'Degree',$selAPDetArray['Degree']);?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Stay-at-home parent
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl06"><p style="width:152px;"><?php echo ($BPPref['Stayathome'])?$BPPref['Stayathome']:"NA" ;?></p>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02"><?php echo getMatchImageFlag($fetchMatchArray['Stayathome'],'Stay at Home',$selAPDetArray['Stayathome']);?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Reason for adopting
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl06"><p style="width:152px;"><?php echo ($BPPref['Reason'])?$BPPref['Reason']:"NA" ;?></p>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02"><?php echo getMatchImageFlag($fetchMatchArray['Reason'],'Reason for Adopting',$selAPDetArray['Reason']);?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            House Type
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl06"><p style="width:152px;"><?php echo ($BPPref['Housetype'])?$BPPref['Housetype']:"NA" ;?></p>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02"><?php echo getMatchImageFlag($fetchMatchArray['Housetype'],'House Type',$selAPDetArray['Housetype']);?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Family structure
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl06"><p style="width:152px;"><?php echo ($BPPref['Familystructure'])?$BPPref['Familystructure']:"NA" ;?></p>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02"><?php echo getMatchImageFlag($fetchMatchArray['Familystructure'],'Family Structure',$selAPDetArray['Familystructure']);?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Adoption type
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl06"><p style="width:152px;"><?php echo ($BPPref['Adoptiontype'])?$BPPref['Adoptiontype']:"NA" ;?></p>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02"><?php echo getMatchImageFlag($fetchMatchArray['Adoptiontype'],'Adoption Type',$selAPDetArray['Adoptiontype']);?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Parents Age
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02 apmatch_cl06"><p style="width:152px;"><?php echo ($BPPref['age'])?$BPPref['age']:"NA" ;?></p>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02"><?php echo getMatchImageFlag($fetchMatchArray['age'],'Parents Age',$selAPDetArray['age']);?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01 apmatch_cl04">
                            Birthmother Characteristics
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02">
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02 apmatch_cl06">
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Drug Use
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02"><?php echo getMatchImageFlag($reversMatchArray['Druguse'],'Drug Use',$selBPDetArray['Druguse']);?>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02 apmatch_cl06"><p style="width:142px;"><?php echo ($selAPPref['Druguse'])?$selAPPref['Druguse']:"NA" ;?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Smoking
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02"><?php echo getMatchImageFlag($reversMatchArray['Smoking'],'Smoking',$selBPDetArray['Smoking']);?>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02 apmatch_cl06"><p style="width:142px;"><?php echo ($selAPPref['Smoking'])?$selAPPref['Smoking']:"NA" ;?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Drinking
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02"><?php echo getMatchImageFlag($reversMatchArray['Drinking'],'Drinking',$selBPDetArray['Drinking']);?>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02 apmatch_cl06"><p style="width:142px;"><?php echo ($selAPPref['Drinking'])?$selAPPref['Drinking']:"NA" ;?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Adoption Type
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02"><?php echo getMatchImageFlag($reversMatchArray['Adoptiontype'],'Adoption Type',$selBPDetArray['Adoptiontype']);?>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02 apmatch_cl06"><p style="width:142px;"><?php echo ($selAPPref['Adoptiontype'])?$selAPPref['Adoptiontype']:"NA" ;?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Family History
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02"><?php echo getMatchImageFlag($reversMatchArray['Familyhistory'],'Family History',$selBPDetArray['Familyhistory']);?>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02 apmatch_cl06"><p style="width:142px;"><?php echo ($selAPPref['Familyhistory'])?$selAPPref['Familyhistory']:"NA" ;?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01">
                            Conception Method
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02"><?php echo getMatchImageFlag($reversMatchArray['Conception'],'Conception Method',$selBPDetArray['Conception']);?>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02 apmatch_cl06"><p style="width:142px;"><?php echo ($selAPPref['Conception'])?$selAPPref['Conception']:"NA" ;?></p>
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
                        <td align="center" width="30%" class="apmatch_cl02"><?php echo getMatchImageFlag($reversMatchArray['Ethnicity'],'Ethnicity',$selBPDetArray['Ethnicity']);?>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02 apmatch_cl06"><p style="width:142px;"><?php echo ($selAPPref['Ethnicity'])?$selAPPref['Ethnicity']:"NA" ;?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01 ">
                            Age
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02"><?php echo getMatchImageFlag($reversMatchArray['Age'],'Child Age',$selBPDetArray['Age']);?>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02 apmatch_cl06"><p style="width:142px;"><?php echo ($selAPPref['Age'])?$selAPPref['Age']:"NA" ;?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01 ">
                            Special Needs
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02"><?php echo getMatchImageFlag($reversMatchArray['Specialneeds'],'Specialneeds',$selBPDetArray['Specialneeds']);?>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02 apmatch_cl06"><p style="width:142px;"><?php echo ($selAPPref['Specialneeds'])?$selAPPref['Specialneeds']:"NA" ;?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01 ">
                            Sex
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02"><?php echo getMatchImageFlag($reversMatchArray['Sex'],'Sex',$selBPDetArray['Sex']);?>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02 apmatch_cl06"><p style="width:142px;"><?php echo ($selAPPref['Sex'])?$selAPPref['Sex']:"NA" ;?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="43%" class="apmatch_cl01 ">
                            Number of Children
                        </td>
                        <td align="center" width="30%" class="apmatch_cl02"><?php echo getMatchImageFlag($reversMatchArray['noofchildren'],'No.of Children',$selBPDetArray['noofchildren']);?>
                        </td>
                        <td align="center" width="27%" class="apmatch_cl02 apmatch_cl06"><p style="width:142px;"><?php echo ($selAPPref['noofchildren'])?$selAPPref['noofchildren']:"NA" ;?></p>
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
                 data: {sel_parent_ID : '<?php echo $pid;?>',pageid:'ap_3',sel_match_ID:'<?php echo $SelParentID;?>'},
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