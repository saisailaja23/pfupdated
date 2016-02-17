<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<link rel="stylesheet" href="Matching/css/stylesheet.css" />
<!--
<link rel="STYLESHEET" type="text/css" href="Matching/dhtmlxGrid/dhtmlxgrid.css"/>
<link rel="STYLESHEET" type="text/css" href="Matching/dhtmlxGrid/skins/dhtmlxgrid_dhx_skyblue.css"/>
<link rel="STYLESHEET" type="text/css" href="Matching/dhtmlxToolbar/codebase/skins/dhtmlxtoolbar_dhx_blue.css">

<script  src="Matching/dhtmlxGrid/dhtmlxcommon.js"></script>
<script  src="Matching/dhtmlxGrid/dhtmlxgrid.js"></script>
<script  src="Matching/dhtmlxGrid/dhtmlxgridcell.js"></script>
<script  src="Matching/dhtmlxGrid/ext/dhtmlxgrid_pgn.js"></script>
<script  src="Matching/dhtmlxToolbar/dhtmlxtoolbar.js"></script>
<script  src="Matching/dhtmlxGrid/ext/dhtmlxgrid_filter.js"></script>

-->



<link rel="STYLESHEET" type="text/css" href="PDFUser/Processing/dialog.css">
<script type="text/javascript" src= "PDFUser/Processing/dialog.js"></script>
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

//$pid = getLoggedId();
$iMemberId = getLoggedId();
$sql = "SELECT id FROM bx_groups_main where author_id=".$iMemberId;
$result = mysql_query($sql);
$row = mysql_fetch_row($result);
$number = $row[0];
//echo "aa ".$_GET['parentID']."   printflag ".$_GET['printflag'] ;
$printflag  = ($_GET['printflag'])?$_GET['printflag']:0;
if(($number != '' || $GLOBALS['logged']['admin']) || ($printflag)) {
$pid   = isset($_GET['parentID'])?$_GET['parentID']:0;//1026 1019
$_aInfo=getProfileInfo($pid);
$ptype = $_aInfo['ProfileType'];

//echo "user id ".$pid."<br/>";
//print_r($_aInfo);
$prfType        = $ptype;
$matchoptions   = getuserPreferenceDetails($prfType,$pid);
//echo "user preference $ matchoptions <br/>";
//print_r($matchoptions);
//echo "<br/><br/>";

$MatchedArray                      = fetchMtachUsers($matchoptions,$pid);
$MatchedUser                       = $MatchedArray[0];
$preferenceFlag                    = $MatchedArray[1];
//echo "matched preferenceFlag ".$preferenceFlag."<br/>";
/*echo "matched user dteials  $ MatchedUser <br/>";
print_r($MatchedUser);
echo "<br/><br/>";*/
//print_r($Pagination);
//exit();
//echo count($MatchedUser);

if($prfType == 4)
    $userdetType = 2;
else if($prfType == 2)
    $userdetType = 4;

$userChar     = getUserDataValues($userdetType,$pid);
$userDetails  = getuserDetails($pid);
//print_r($userDetails);
//echo "<br/><br/>";
foreach($userDetails as $key=>$value)
{
    $userChar[$key]     = $value;
}
//echo "user details  $ userChar <br/>";
//print_r($userChar);
//echo "<br/><br/>";
$agency =db_arr("SELECT `uri` FROM `bx_groups_main` WHERE `id` =".$_aInfo['AdoptionAgency']);
 ?>
<div style="margin-left:820px;"> <a href="<?php echo $GLOBALS['site']['url']; ?>m/groups/browse_fans_list/<?php echo $agency['uri']; ?>"><div class="button_wrapper" style="width:110px;">
<span class="form_input_submit" style="position:relative;top:5px;left:2px;color:#333333;font-family:Verdana,Arial;font-size:11px;">Back to Members</span>
<div class="button_wrapper_close"></div>
</div></a></div>
<div id="print_main" >
<?php
if($prfType == 4)
{
    $coupleDetails  = getCoupleDetails($pid);    
    $personName     = ($coupleDetails['coupleFirstName'])?$userChar ['FirstName']. ' &amp; '.$coupleDetails['coupleFirstName']:$userChar ['FirstName'];

?>
<!-- BP Matching profile display-->
<div id="main" style="width:970px;">
        <div class="dummy" style="height: 10px;">
            <!--dummy-->
        </div>
        <div class="apmatch">
            <div id="bpmatch_id01" style="width: 385px; margin-left: 35px;">
                <div id="bpmatch_id04" style="width: 190px;">
                    <!--image container-->
                    <?php
			$img                  = getProfileImage($pid);
                    //$img                = 'modules/boonex/avatar/data/images/'.$userChar['Avatar'].'.jpg';
                    //$img                = 'modules/boonex/photos/data/files/4411.jpg';
                    //echo $img;
                    $imagepath          = ($img)?$img:'templates/base/images/icons/man_medium.gif';
                    $imageResolution    = getUploadImageResolution($imagepath,250,190);
                    //print_r($imageResolution);
                    $imgHeight          = $imageResolution['imageHeight'];
                    $imgWidth           = $imageResolution['imageWidth'];
                    $imgMargintop       = $imageResolution['margintop'];
                    $imgMarginleft      = $imageResolution['marginleft'];

                    ?>
                    <img alt="<?php echo $userChar['FirstName'];?>" src ="<?php echo $imagepath;?>" height="<?php echo $imgHeight;?>" width ="<?php echo $imgWidth;?>" style="margin-top:<?php echo $imgMargintop;?>px;margin-left:<?php echo $imgMarginleft;?>px;" />
                </div>
                <div id="bpmatch_id08">
                    <div class="apmatch_cl05">
                        <!--icons-->
                        <img class="icon_class" id="print_user" src="Matching/images/print.png" alt="Print" />
                    </div>
                    <!--
                    <div class="apmatch_cl05">
                        <img class="icon_class" src="Matching/images/message.png" alt="Message" />
                    </div>
                    <div class="apmatch_cl05">
                        <img class="icon_class" src="Matching/images/Phone.png" alt="Phone" />
                    </div>
                    -->
                </div>
                 <div class="dummy" style="height: 70px">
                    <!--dummy-->
                </div>
                <div id="bpmatch_id05" style="width: 385px;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td align="left" width="55%" class="apmatch_cl01 apmatch_cl03">
                                Category
                            </td>
                            <td align="center" width="45%" class="apmatch_cl02 apmatch_cl03">
                                Answer
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="55%" class="apmatch_cl01">
                                Name
                            </td>
                            <td align="center" width="45%" class="apmatch_cl02 apmatch_cl06"><?php echo $personName;?>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="55%" class="apmatch_cl01">
                                Agency
                            </td>
                            <td align="center" width="45%" class="apmatch_cl02 apmatch_cl06"><?php echo $userChar['Agency'];?>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="55%" class="apmatch_cl01 apmatch_cl04">
                                AP Characteristics
                            </td>
                            <td align="center" width="45%" class="apmatch_cl02">
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="55%" class="apmatch_cl01">
                                Relationship
                            </td>
                            <td align="center" width="45%" class="apmatch_cl02 apmatch_cl06"><?php echo ($matchoptions['Relationship'])?$matchoptions['Relationship']:"NA" ;?>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="55%" class="apmatch_cl01">
                                Ethnicity
                            </td>
                            <td align="center" width="45%" class="apmatch_cl02 apmatch_cl06"><?php echo ($matchoptions['Ethnicity'])?$matchoptions['Ethnicity']:"NA" ;?>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="55%" class="apmatch_cl01">
                                Location
                            </td>
                            <td align="center" width="45%" class="apmatch_cl02 apmatch_cl06"><?php echo ($matchoptions['State'])?$matchoptions['State']:"NA" ;?>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="55%" class="apmatch_cl01">
                                Religion
                            </td>
                            <td align="center" width="45%" class="apmatch_cl02 apmatch_cl06"><?php echo ($matchoptions['Religion'])?$matchoptions['Religion']:"NA" ;?>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="55%" class="apmatch_cl01">
                                Pets
                            </td>
                            <td align="center" width="45%" class="apmatch_cl02 apmatch_cl06"><?php echo ($matchoptions['Pets'])?$matchoptions['Pets']:"NA" ;?>
                            </td>
                        </tr>                        
                        <tr>
                            <td align="left" width="55%" class="apmatch_cl01">
                                Degree
                            </td>
                            <td align="center" width="45%" class="apmatch_cl02 apmatch_cl06"><?php echo ($matchoptions['Degree'])?$matchoptions['Degree']:"NA" ;?>                             </td>
                        </tr>
                        <tr>
                            <td align="left" width="55%" class="apmatch_cl01">
                                Stay-at-home parent
                            </td>
                            <td align="center" width="45%" class="apmatch_cl02 apmatch_cl06"><?php echo ($matchoptions['Stayathome'])?$matchoptions['Stayathome']:"NA" ;?>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="55%" class="apmatch_cl01">
                                Reason for adopting
                            </td>
                            <td align="center" width="45%" class="apmatch_cl02 apmatch_cl06"><?php echo ($matchoptions['Reason'])?$matchoptions['Reason']:"NA" ;?>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="55%" class="apmatch_cl01">
                                House Type
                            </td>
                            <td align="center" width="45%" class="apmatch_cl02 apmatch_cl06"><?php echo ($matchoptions['Housetype'])?$matchoptions['Housetype']:"NA" ;?>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="55%" class="apmatch_cl01">
                                Family Structure
                            </td>
                            <td align="center" width="45%" class="apmatch_cl02 apmatch_cl06"><?php echo ($matchoptions['Familystructure'])?$matchoptions['Familystructure']:"NA" ;?>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="55%" class="apmatch_cl01">
                                Adoption Type
                            </td>
                            <td align="center" width="45%" class="apmatch_cl02 apmatch_cl06"><?php echo ($matchoptions['Adoptiontype'])?$matchoptions['Adoptiontype']:"NA" ;?>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="55%" class="apmatch_cl01">
                                Parents Age
                            </td>
                            <td align="center" width="45%" class="apmatch_cl02 apmatch_cl06"><?php echo ($matchoptions['age'])?$matchoptions['age']:"NA" ;?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div id="bpmatch_id02" style="width: 23px;">
                <!--middle dummy division-->
            </div>
            <div id="bpmatch_id03" style="width: 512px;">
                <div class="dummy" style="height: 0px;">
                    <!--dummy-->
                </div>
                <div id="bpmatch_id07" style="height:auto;width: 511px;">
                    <?php
                    if(count($MatchedUser) > 0 && isset($MatchedUser))
                    {
                    ?>
                    <table width="510" height="10" cellpadding="0" cellspacing="0">
                          <tr>
                              <td>
                                  <div id="billinginfo_grid" style="width:100%; height:400px; background-color:white;overflow:hidden;"></div>
                              </td>
                          </tr>
                          <tr>
                              <td >
                                <div id="billinginfoArea" ></div>
                              </td>
                         </tr>
                     </table>

                    <?php
                    $str = 'header("Content-type: text/xml")';
                    $str = '<xml version="1.0" encoding="UTF-8" id="biliinginfo_xml" style="display:none"><rows>';
                      
                        for($cnt=0; $cnt<count($MatchedUser);$cnt++)
                        {
                            $profimgPath        = ($MatchedUser[$cnt]['apPersonalMainImageID'] && file_exists('images/profile/'.$MatchedUser[$cnt]['apPersonalMainImageID'].'.jpg'))?'images/profile/'.$MatchedUser[$cnt]['apPersonalMainImageID'].'.jpg':"images/img2.gif";
                            //echo "profile image path ".$profimgPath;
                            $coupleName         = getCoupleDetails($MatchedUser[$cnt]['ID']);
                            $personName         = ($coupleName['coupleFirstName'])?$MatchedUser[$cnt]['FirstName']. ' &amp; '.$coupleName['coupleFirstName']:$MatchedUser[$cnt]['FirstName'];
                            $personID           = $MatchedUser[$cnt]['ID'];
                            $personGender       =  $MatchedUser[$cnt]['Sex'];
                            $personType         = ($MatchedUser[$cnt]['personType'] == 'P')?'Parents':'Child';
                            $personCountry      = ($MatchedUser[$cnt]['parentCountry'])?$MatchedUser[$cnt]['parentCountry']:"";


                            $personRelationship = ($MatchedUser[$cnt]['RelationshipStatus'])?$MatchedUser[$cnt]['RelationshipStatus']:"";
                            $personEthnicity    = ($MatchedUser[$cnt]['Ethnicity'])?$MatchedUser[$cnt]['Ethnicity']:"";
                            $personState        = ($MatchedUser[$cnt]['State'])?$MatchedUser[$cnt]['State']:"";
                            $personRellegion    = ($MatchedUser[$cnt]['Religion'])?$MatchedUser[$cnt]['Religion']:"";
                            $personPet          = ($MatchedUser[$cnt]['Pet'])?$MatchedUser[$cnt]['Pet']:"";
                            $personOccupation   = ($MatchedUser[$cnt]['Occupation'])?$MatchedUser[$cnt]['Occupation']:"";
                            $personEducation    = ($MatchedUser[$cnt]['Education'])?$MatchedUser[$cnt]['Education']:"";
                            $personStayathome   = ($MatchedUser[$cnt]['Stayathome'])?$MatchedUser[$cnt]['Stayathome']:"";
                            $personReason       = ($MatchedUser[$cnt]['Reason'])?$MatchedUser[$cnt]['Reason']:"";
                            $personResidency    = ($MatchedUser[$cnt]['Residency'])?$MatchedUser[$cnt]['Residency']:"";
                            $personFamilyStruc  = ($MatchedUser[$cnt]['FamilyStructure'])?$MatchedUser[$cnt]['FamilyStructure']:"";
                            $personAdoptiontype = ($MatchedUser[$cnt]['Adoptiontype'])?$MatchedUser[$cnt]['Adoptiontype']:"";
                            $personage          = ($MatchedUser[$cnt]['age'])?$MatchedUser[$cnt]['age'].' Years':'0 Years';
                            $persondayswaiting  = ($MatchedUser[$cnt]['daysWaiting'])?$MatchedUser[$cnt]['daysWaiting']:'0';
                            $personAgency       = $MatchedUser[$cnt]['Agency'];
                            $personMatcPerc     = ($MatchedUser[$cnt]['matchperc'])?$MatchedUser[$cnt]['matchperc']:0;

                            $str .= '<row id="'. $personID .'">
                                <cell style ="height:25px;vertical-align:left;">&lt;a href="page/apmatch?parentID='.$personID.'&amp;userID='.$pid.'"&gt;'.$personName.'&lt;/a&gt;</cell>
                                <cell style ="height:25px;vertical-align:left;">'.$personMatcPerc.'</cell>
                                <cell style ="height:25px;vertical-align:left;">'.$persondayswaiting.'</cell>
                                <cell style ="height:25px;vertical-align:left;">'.$personAgency.'</cell>
                            </row>';
                           echo '<pre>'.$str.'</pre>';exit();
                        }
                        $str .= '</rows></xml>'; 
                        $fname = createXMLFile($str);
                        $match_url = $GLOBALS['site']['url']."Matching/match_xml/".$fname;
                        echo $str;

                    ?>

                    <script type="text/javascript">          
                              
                    var billingGrid;
                    billingGrid = new dhtmlXGridObject('billinginfo_grid');
                    billingGrid.setDateFormat("%m/%d/%Y");
                    billingGrid.setImagePath("Matching/dhtmlxGrid/imgs/");
                    billingGrid.setHeader("Family,% Match,Days Waiting,Agency");
                    billingGrid.attachHeader("#text_filter,#text_filter,#text_filter,#text_filter");
                    billingGrid.setInitWidths("152,100,100,152");
                    billingGrid.setColAlign("left,left,left,left");
                    billingGrid.setColTypes("ro,ro,ro,ro");
                    billingGrid.setColSorting("na,str,str,str");
                   // billingGrid.setSkin("dhx_skyblue");
                    billingGrid.enableAutoHeight(true);
                    billingGrid.init();
                    billingGrid.enablePaging(true, 10, 3, "billinginfoArea");
                  //  billingGrid.setPagingSkin("toolbar");
                  billingGrid.setPagingSkin("toolbar","dhx_skyblue");
                  //billingGrid.parse(document.getElementById('biliinginfo_xml'));  
                    billingGrid.loadXML("<?php echo $match_url ?>");
                    </script>

<?php
}
else if(!$preferenceFlag)
{
?>
            <div id="bpmatch_id07" style="top:0px;height:250px;border:1px solid black;text-align:center;">
                  <span class="nomatch_span" >No Preference set for this user.</span>
             </div>
<?php
}
else
{
?>
            <div id="bpmatch_id07" style="top:0px;height:250px;border:1px solid black;text-align:center;">
                  <span class="nomatch_span" >No matched profiles available.</span>
             </div>

<?php
 }
?>

                </div>
                <div class="dummy" style="height: 25px;">
                    <!--dummy-->
                </div>
                <div id="bpmatch_id06" style="margin-left: 90px;">
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
                            <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><?php echo ($userChar['Druguse'])?$userChar['Druguse']:"NA";?>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="60%" class="apmatch_cl01">
                                Smoking
                            </td>
                            <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><?php echo ($userChar['Smoking'])?$userChar['Smoking']:"NA" ;?>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="60%" class="apmatch_cl01">
                                Drinking
                            </td>
                            <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><?php echo ($userChar['Drinking'])?$userChar['Drinking']:"NA" ;?>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="60%" class="apmatch_cl01">
                                Adoption Type
                            </td>
                            <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><?php echo ($userChar['Adoptiontype'])?$userChar['Adoptiontype']:"NA" ;?>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="60%" class="apmatch_cl01">
                                Family History
                            </td>
                            <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><?php echo ($userChar['Familyhistory'])?$userChar['Familyhistory']:"NA" ;?>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="60%" class="apmatch_cl01">
                                Conception Method
                            </td>
                            <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><?php echo ($userChar['Conception'])?$userChar['Conception']:"NA" ;?>
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
                                Child Charcteristics
                            </td>
                            <td align="center" width="40%" class="apmatch_cl02">
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="60%" class="apmatch_cl01 ">
                                Ethnicity
                            </td>
                            <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><?php echo ($userChar['Ethnicity'])?$userChar['Ethnicity']:"NA" ;?>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="60%" class="apmatch_cl01 ">
                                Age
                            </td>
                            <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><?php echo ($userChar['Age'])?calc_age($userChar['Age']):"NA" ;?>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="60%" class="apmatch_cl01 ">
                                Special Needs
                            </td>
                            <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><?php echo  ($userChar['Specialneeds'])?$userChar['Specialneeds']:"NA" ;?>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="60%" class="apmatch_cl01 ">
                                Sex
                            </td>
                            <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><?php echo ($userChar['Sex'])?$userChar['Sex']:"NA" ;?>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="60%" class="apmatch_cl01 ">
                                Number of Children
                            </td>
                            <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><?php echo ($userChar['noofchildren'])?$userChar['noofchildren']:"NA" ;?>
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
<?php
}
else if($prfType == 2)
{
    $coupleDetails  = getCoupleDetails($pid);    
    $personName     = ($coupleDetails['coupleFirstName'])?$userChar ['FirstName']. ' &amp; '.$coupleDetails['coupleFirstName']:$userChar ['FirstName'];

?>
<!-- AP Matching profile display-->
<div id="main">
    <div class="dummy" style="height: 10px;">
        <!--dummy-->
    </div>
    <div class="apmatch">
        <div id="bpmatch_id01">
            <div id="bpmatch_id04">
                <!--image container-->
                <?php
			$img                  = getProfileImage($pid);
                    //$img                = 'modules/boonex/avatar/data/images/'.$userChar['Avatar'].'.jpg';
                    //$img                = 'modules/boonex/photos/data/files/4411.jpg';
                    //echo $img;
                    $imagepath          = ($img)?$img:'templates/base/images/icons/man_medium.gif';
                    $imageResolution    = getUploadImageResolution($imagepath,250,337);
                    //print_r($imageResolution);
                    $imgHeight          = $imageResolution['imageHeight'];
                    $imgWidth           = $imageResolution['imageWidth'];
                    $imgMargintop       = $imageResolution['margintop'];
                    $imgMarginleft      = $imageResolution['marginleft'];

                    ?>
                    <img alt="<?php echo $userChar['FirstName'];?>" src ="<?php echo $imagepath;?>" height="<?php echo $imgHeight;?>" width ="<?php echo $imgWidth;?>" style="margin-top:<?php echo $imgMargintop;?>px;margin-left:<?php echo $imgMarginleft;?>px;" />
            </div>
            <div id="bpmatch_id08">
                <div class="apmatch_cl05">
                    <!--icons-->
                    <img class="icon_class" id="print_user" src="Matching/images/print.png" alt="Print" />
                </div>
                <!--
                <div class="apmatch_cl05">
                    <img class="icon_class" src="Matching/images/message.png" alt="Message" />
                </div>
                <div class="apmatch_cl05">
                    <img class="icon_class" src="Matching/images/Phone.png" alt="Phone" />
                </div>
                -->
            </div>
            <div class="dummy" style="height: 65px">
                    <!--dummy-->
            </div>
            <div id="bpmatch_id05">
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
                            % Match Required (Minimum)
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo $matchoptions['matchpercenatage'];?>%
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Agency
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo $userChar['Agency'];?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Days Waiting
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo $userChar['daysWaiting'];?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Relationship
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo ($userChar['Relationship'])?$userChar['Relationship']:"NA" ;?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Ethnicity
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo ($userChar['Ethnicity'])?$userChar['Ethnicity']:"NA" ;?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Location
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo ($userChar['State'])?$userChar['State']:"NA" ;?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Religion
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo ($userChar['Religion'])?$userChar['Religion']:"NA" ;?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Pets
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo ($userChar['Pets'])?$userChar['Pets']:"NA" ;?>
                        </td>
                    </tr>                    
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Degree
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo ($userChar['Degree'])?$userChar['Degree']:"NA" ;?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Stay-at-home parent
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo ($userChar['Stayathome'])?$userChar['Stayathome']:"NA" ;?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Reason for adopting
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo ($userChar['Reason'])?$userChar['Reason']:"NA" ;?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            House Type
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo ($userChar['Housetype'])?$userChar['Housetype']:"NA" ;?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Family Structure
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo ($userChar['Familystructure'])?$userChar['Familystructure']:"NA" ;?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Adoption Type
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo ($userChar['Adoptiontype'])?$userChar['Adoptiontype']:"NA" ;?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="37%" class="apmatch_cl01">
                            Parents Age
                        </td>
                        <td align="center" width="63%" class="apmatch_cl02 apmatch_cl06"><?php echo ($userChar['age'])?calc_age($userChar['age']):"NA" ;?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="bpmatch_id02">
            <!--middle dummy division-->
        </div>
        <div id="bpmatch_id03">
            <div class="dummy" style="height: 00px;">
                <!--dummy-->
            </div>
            <div id="bpmatch_id07" style="height:350px;width: 511px;">

                <?php
                    if(count($MatchedUser) > 0 && isset($MatchedUser))
                    {
                    ?>
                    <table width="510" height="10" cellpadding="0" cellspacing="0">
                          <tr>
                              <td>
                                  <div id="billinginfo_grid" style="width:100%; height:400px; background-color:white;overflow:hidden;"></div>
                              </td>
                          </tr>
                          <tr>
                              <td >
                                <div id="billinginfoArea" ></div>
                              </td>
                         </tr>
                     </table>

                    <?php
                    $str = 'header("Content-type: text/xml")';
                    $str = '<xml version="1.0" encoding="UTF-8" id="biliinginfo_xml" style="display:none"><rows>';
                        for($cnt=0; $cnt<count($MatchedUser);$cnt++)
                        {
                            $profimgPath        = ($MatchedUser[$cnt]['apPersonalMainImageID'] && file_exists('images/profile/'.$MatchedUser[$cnt]['apPersonalMainImageID'].'.jpg'))?'images/profile/'.$MatchedUser[$cnt]['apPersonalMainImageID'].'.jpg':"images/img2.gif";
                            //echo "profile image path ".$profimgPath;
                            $coupleName         = getCoupleDetails($MatchedUser[$cnt]['ID']);
                            $personName         = ($coupleName['coupleFirstName'])?$MatchedUser[$cnt]['FirstName']. ' &amp; '.$coupleName['coupleFirstName']:$MatchedUser[$cnt]['FirstName'];
                            $personID           = $MatchedUser[$cnt]['ID'];
                            $personGender       =  $MatchedUser[$cnt]['Sex'];
                            $personType         = ($MatchedUser[$cnt]['personType'] == 'P')?'Parents':'Child';
                            $personCountry      = ($MatchedUser[$cnt]['parentCountry'])?$MatchedUser[$cnt]['parentCountry']:"";


                            $personRelationship = ($MatchedUser[$cnt]['RelationshipStatus'])?$MatchedUser[$cnt]['RelationshipStatus']:"";
                            $personEthnicity    = ($MatchedUser[$cnt]['Ethnicity'])?$MatchedUser[$cnt]['Ethnicity']:"";
                            $personState        = ($MatchedUser[$cnt]['State'])?$MatchedUser[$cnt]['State']:"";
                            $personRellegion    = ($MatchedUser[$cnt]['Religion'])?$MatchedUser[$cnt]['Religion']:"";
                            $personPet          = ($MatchedUser[$cnt]['Pet'])?$MatchedUser[$cnt]['Pet']:"";
                            $personOccupation   = ($MatchedUser[$cnt]['Occupation'])?$MatchedUser[$cnt]['Occupation']:"";
                            $personEducation    = ($MatchedUser[$cnt]['Education'])?$MatchedUser[$cnt]['Education']:"";
                            $personStayathome   = ($MatchedUser[$cnt]['Stayathome'])?$MatchedUser[$cnt]['Stayathome']:"";
                            $personReason       = ($MatchedUser[$cnt]['Reason'])?$MatchedUser[$cnt]['Reason']:"";
                            $personResidency    = ($MatchedUser[$cnt]['Residency'])?$MatchedUser[$cnt]['Residency']:"";
                            $personFamilyStruc  = ($MatchedUser[$cnt]['FamilyStructure'])?$MatchedUser[$cnt]['FamilyStructure']:"";
                            $personAdoptiontype = ($MatchedUser[$cnt]['Adoptiontype'])?$MatchedUser[$cnt]['Adoptiontype']:"";
                            $personage          = ($MatchedUser[$cnt]['age'])?$MatchedUser[$cnt]['age'].' Years':'0 Years';
                            $persondayswaiting  = ($MatchedUser[$cnt]['daysWaiting'])?$MatchedUser[$cnt]['daysWaiting']:'0';
                            $personAgency       = $MatchedUser[$cnt]['Agency'];
                            $personMatcPerc     = ($MatchedUser[$cnt]['matchperc'])?$MatchedUser[$cnt]['matchperc']:0;

                            $str .= '<row id="'. $personID .'">
                                <cell style ="height:25px;vertical-align:left;">&lt;a href="page/apmatch?parentID='.$personID.'&amp;userID='.$pid.'"&gt;'.$personName.'&lt;/a&gt;</cell>
                                <cell style ="height:25px;vertical-align:left;">'.$personMatcPerc.'</cell>
                                <cell style ="height:25px;vertical-align:left;">'.$persondayswaiting.'</cell>
                                <cell style ="height:25px;vertical-align:left;">'.$personAgency.'</cell>
                            </row>';

                        }
                        $str .= '</rows></xml>';
                        $fname = createXMLFile($str);
                        $match_url = $GLOBALS['site']['url']."Matching/match_xml/".$fname;
                        echo $str;
                    ?>

                    <script type="text/javascript">
                 
                    var billingGrid;
                    billingGrid = new dhtmlXGridObject('billinginfo_grid');
                    billingGrid.setDateFormat("%m/%d/%Y");
                    billingGrid.setImagePath("Matching/dhtmlxGrid/imgs/");
                    billingGrid.setHeader("Birth Parent/Child,% Match,Days Waiting,Agency");
                    billingGrid.attachHeader("#text_filter,#text_filter,#text_filter,#text_filter");
                    billingGrid.setInitWidths("152,100,100,152");
                    billingGrid.setColAlign("left,left,left,left");
                    billingGrid.setColTypes("ro,ro,ro,ro");
                    billingGrid.setColSorting("na,str,str,str");
                   // billingGrid.setSkin("dhx_skyblue");
                    billingGrid.enableAutoHeight(true);
                    billingGrid.init();
                    billingGrid.enablePaging(true, 10, 3, "billinginfoArea");
                   // billingGrid.setPagingSkin("toolbar");
                    billingGrid.setPagingSkin("toolbar","dhx_skyblue");
                   //billingGrid.parse(document.getElementById('biliinginfo_xml'));
                    billingGrid.loadXML("<?php echo $match_url ?>");
                    

                  </script>

            <?php
            }
            else if(!$preferenceFlag)
            {
            ?>
                        <div id="bpmatch_id07" style="top:0px;height:250px;border:1px solid black;text-align:center;">
                              <span class="nomatch_span" >No Preference set for this user.</span>
                         </div>
            <?php
            }
            else
            {
            ?>
             <div id="bpmatch_id07" style="top:0px;height:250px;border:1px solid black;text-align:center;">
                  <span class="nomatch_span" >No matched profiles available.</span>
             </div>

            <?php
             }
            ?>


            </div>
            <div class="dummy" style="height: 20px;">
                <!--dummy-->
            </div>
            <div id="bpmatch_id06" style="margin-left: 90px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td align="center" width="60%" class="apmatch_cl01 apmatch_cl03">
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl03">
                            Criteria
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
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><p style="width:145px;"><?php echo ($matchoptions['Druguse'])?$matchoptions['Druguse']:"NA" ;?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01">
                            Smoking
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><p style="width:145px;"><?php echo ($matchoptions['Smoking'])?$matchoptions['Smoking']:"NA" ;?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01">
                            Drinking
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><p style="width:145px;"><?php echo ($matchoptions['Drinking'])?$matchoptions['Drinking']:"NA" ;?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01">
                            Adoption Type
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><p style="width:145px;"><?php echo ($matchoptions['Adoptiontype'])?$matchoptions['Adoptiontype']:"NA" ;?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01">
                            Family History
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><p style="width:145px;"><?php echo ($matchoptions['Familyhistory'])?$matchoptions['Familyhistory']:"NA" ;?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01">
                            Conception Method
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><p style="width:145px;"><?php echo ($matchoptions['Conception'])?$matchoptions['Conception']:"NA" ;?></p>
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
                            Child Charcteristics
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02">
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01 ">
                            Ethnicity
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><p style="width:145px;"><?php echo ($matchoptions['Ethnicity'])?$matchoptions['Ethnicity']:"NA" ;?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01 ">
                            Age
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><p style="width:145px;"><?php echo ($matchoptions['Age'])?$matchoptions['Age']:"NA" ;?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01 ">
                            Special Needs
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><p style="width:145px;"><?php echo ($matchoptions['Specialneeds'])?$matchoptions['Specialneeds']:"NA" ;?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01 ">
                            Sex
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><p style="width:145px;"><?php echo ($matchoptions['Sex'])?$matchoptions['Sex']:"NA" ;?></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="60%" class="apmatch_cl01 ">
                            Number of Children
                        </td>
                        <td align="center" width="40%" class="apmatch_cl02 apmatch_cl06"><p style="width:145px;"><?php echo ($matchoptions['noofchildren'])?$matchoptions['noofchildren']:"NA" ;?></p>
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

<?php
}
?>
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
    jQuery('#print_user').click(function(){
            /*var mywindow = window.open('','mydiv','height=400,width=600');
            mywindow.document.write('<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['site']['url']?>Matching/css/stylesheet.css" />');
            mywindow.document.write('<link rel="STYLESHEET" type="text/css" href="<?php echo $GLOBALS['site']['url']?>Matching/dhtmlxGrid/dhtmlxgrid.css"/>');
            mywindow.document.write('<link rel="STYLESHEET" type="text/css" href="<?php echo $GLOBALS['site']['url']?>Matching/dhtmlxGrid/skins/dhtmlxgrid_dhx_skyblue.css"/>');
            mywindow.document.write(' <link rel="STYLESHEET" type="text/css" href="<?php echo $GLOBALS['site']['url']?>Matching/dhtmlxToolbar/codebase/skins/dhtmlxtoolbar_dhx_blue.css">');
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
                 data: {sel_parent_ID : '<?php echo $pid;?>',pageid:'first_1'},
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
function createXMLFile($str) 
{

$pid   = isset($_GET['parentID'])?$_GET['parentID']:0;        
$myFile = $GLOBALS['dir']['root'].'Matching/match_xml/'."match_".$pid.".xml"; 
$myFile_Name = "match_".$pid.".xml";                       
$fh = fopen($myFile, 'w') or die("can't open file");                  
fwrite($fh, $str);
fclose($fh);
chmod($myFile, 0777);
return $myFile_Name;
}

?>