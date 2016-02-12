<?php
session_start();
ini_set("display_errors", 0);
if (isset($_GET['famreg'])) {
    $famregs = $_GET['famreg'];
    /* foreach($famregs as $famreg){
      $famregz .= ' %'.$famreg.'% ';
      } */
}
$famregz = str_replace(" ", "<=>", implode(",", $famregs));


if (isset($_GET['pareth'])) {
    $pareths = $_GET['pareth'];
    /* foreach($pareths as $pareth){
      $parethz .= ' %'.$pareth.'% ';
      } */
    $parethz = str_replace(" ", "<=>", implode(",", $pareths));
}

if (isset($_GET['beth'])) {
    $beths = $_GET['beth'];
    /* foreach($beths as $beth){
      $bethz .= ' %'.$beth.'% ';
      } */
    $bethz = str_replace(" ", "<=>", implode(",", $beths));
}

$i = 0;
$k = 0;
if (isset($_GET['pstart']) && isset($_GET['pend'])) {

    if (!isset($_GET['max_race_id'])) {
        $URLToProcess = 'http://www.parentfinder.com/badgewaitingfamiliesalliance.php?pstart=' . $_GET[pstart] . '&pend=' . $_GET['pend'];

        $data = file_get_contents($URLToProcess);
    } else {
        $URLToProcess = 'http://www.parentfinder.com/badgewaitingfamiliesalliance.php?famreg=' . $famregz . '&pareth=' . $parethz . '&beth=' . $bethz . '&pstart=' . $_GET['pstart'] . '&pend=' . $_GET['pend'];
        $data = file_get_contents($URLToProcess);
    }
} elseif (isset($_GET['search']) && !isset($_GET['max_race_id'])) {
    $URLToProcess = 'http://www.parentfinder.com/badgewaitingfamiliesalliance.php?search=' . $_GET[search];

    $data = file_get_contents($URLToProcess);
} elseif (isset($_GET['max_race_id'])) {
    $URLToProcess = 'http://www.parentfinder.com/badgewaitingfamiliesalliance.php?famreg=' . $famregz . '&pareth=' . $parethz . '&beth=' . $bethz;
    $data = file_get_contents($URLToProcess);
} else {
    $URLToProcess = 'http://www.parentfinder.com/badgewaitingfamiliesalliance.php';
    $data = file_get_contents($URLToProcess);
}
//echo $URLToProcess;
//echo $data;
$aData = (explode("#####", $data));

shuffle($aData);

if (isset($_GET['max_race_id'])) {
    $dividedBy = round(count($aData) / 2);
} else {
    $dividedBy = 6;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head profile="http://gmpg.org/xfn/11">
        <title>Waiting Families | adoption alliance</title>
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
            <div id="header-image"> <a rel="nofollow" href="http://www.adoption-alliance.com/birth-parents-main-texas-tx" id="header-pregnant" title=""></a> <a rel="nofollow" href="http://www.adoption-alliance.com/adoptive-parents-main-texas-tx" id="header-adopt" title=""></a> </div>
            <div id="column-left">
                <h1>
                    Some of Our Waiting Families  <a style="color:#FFF;margin-left:100px;" href="index.php" >New Search</a></h1>
                <div class="content">
<?php
//echo "<pre>";
//print_r($aData);

$cnt = 1;
//$totalCnt   =    count($aData);
$totalCnt = 0;
foreach ($aData as $ffkey) {
    $familyData = explode(';-', $ffkey);


//if($cnt == 1 || $cnt == 7) {
?>

                        <div style ="position:relative;float:left;width:210px;height:auto;text-align: center;">
                    <?php
                    //}

                    $profile = $familyData[0];
//$datas = file_get_contents('http://www.parentfinder.com/achose1.php?profile='.$profile);
//$aDatas = (explode(";-",$datas));
//$datap = file_get_contents('http://www.parentfinder.com/test1.php?profile='.$profile);
//$aDatap = (explode(";-",$datap));
//echo "Test".$familyData[3];


                    if ($profile != '') {
                        $totalCnt = 1;
                        if ($i < $dividedBy) {
                    ?>
                        <?php if ($familyData[3] != '') {
 ?>

                                <div class="oneside1">

                                    <div class="oneside"><a href="mainProfile.php?profile=<?= $profile ?>" class="photob"><img src="<?= $familyData[3] ?>" width="150" border="0"/></a></div>
                                    <div style="text-align: center;margin-top:10px;"><a href="mainProfile.php?profile=<?= $profile ?>" class="black"><?= $familyData[1] ?> <? if ($familyData[2] != '') {
 ?> &amp; <?= $familyData[2] ?> <? } ?></a></div>

                                </div>
                        <?php } else {
 ?>

                                <div class="oneside1">

                                    <div class="oneside"><img src="images/noimage.jpg" width="150" border="0"/></div>
                                    <div style="text-align: center;margin-top:10px;"><a href="mainProfile.php?profile=<?= $profile ?>" class="black"><?= $familyData[1] ?> <? if ($familyData[2] != '') { ?> &amp; <?= $familyData[2] ?> <? } ?></a></div>

                                </div>

<?php
                            }
?>

<?php
                            //if($cnt == 6 && $totalCnt > 6){
?>
                        </div>
<?php
                            // }
                        } else {
?>
                        <?php if ($i == $dividedBy) {
 ?>

<?php } ?>

                        <?php if ($familyData[3] != '') {
 ?>

                            <div class="oneside1">

                                <div class="oneside"><a href="mainProfile.php?profile=<?= $profile ?>" class="photob"><img src="<?= $familyData[3] ?>"   width="150"  border="0"/></a></div>
                                    <div style="text-align: center;margin-top:10px;"><a href="mainProfile.php?profile=<?= $profile ?>" class="black"><?= $familyData[1] ?> <? if ($familyData[2] != '') {
 ?> &amp; <?= $familyData[2] ?> <? } ?></a></div>

                                </div>
                    <?php } else {
 ?>

                                <div class="oneside1">
                                    <div class="oneside">
                                        <img src="images/noimage.jpg" width="150"  border="0"/></div>
                                    <div style="text-align: center;margin-top:10px;"><a href="mainProfile.php?profile=<?= $profile ?>" class="black"><?= $familyData[1] ?> <? if ($familyData[2] != '') { ?> &amp; <?= $familyData[2] ?> <? } ?></a></div>

                                </div>

<?php
                            }
?>



                    <?php
                            //  if($cnt == 6){
                    ?>
                        </div>

<?php
                            //}
                        }


                        $i++;
                    }


                    $k++;

//$cnt++;
                }

                if ($totalCnt >= 1) {
?>

            </div>
                <?php
                } else {
                ?>

                <div style="text-align:center;width:140px;"><h3>No records found</h3></div>
            </div></div>
                <?php
                }
                ?>

                    <!-- -->
        <?php
                    echo '<div style=margin-top:900px;text-align:center;>';
                    if (isset($_GET['pstart']) && $_GET['pstart'] != 0 &&  $_GET['pstart'] != 1 ) {
                        $lpend = 12; //$_GET['pend'] - 5;
                        //$lpstart = $lpend - 12;
                        $lpstart = $_GET['pstart'] - 12;
                        $spage = $_GET['page'] - 1;

                       echo '<a href="waitingfamilies.php?max_race_id=7&beth[]=' . $bethz . '&pareth[]=' . '&pstart=' . $lpstart . '&pend=' . $lpend . '&page=' . $spage . '&button=Submit+Search" class="next">Prev</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';


                  }

                        //$data = file_get_contents('http://www.parentfinder.com/badgewaitingfamiliess.php?getpa=true');
//$data = file_get_contents('http://www.parentfinder.com/badgewaitingfamiliesalliance.php?getpa=true&max_race_id=7&beth='.$bethz);

                        $data = file_get_contents('http://www.parentfinder.com/badgewaitingfamiliesalliance.php?getpa=true&max_race_id=7&beth=' . $bethz);
                        $aData = (explode("#####", $data));                        
                        $i = 0;
                        $p = 0;
                        $pstart = 0;
                        $pend = 12;

                        // echo "counter ".count($aData);
                        foreach ($aData as $key => $page) {

                        //for($cnt = 0; $cnt <= 42; $cnt =$cnt+12)
                            if ($aData[$p] != '') {
                                $i++;
                                if ($_GET['page'] == $i) {
                                    echo '<b>' . $i . '</b> &nbsp;';
                                    if ($i == '') {
                                        $currentpage = 1;
                                    } else {
                                        $currentpage = $i;
                                    }
                                } else {
                                    if (!isset($_GET['page']) && $i == 1) {
                                        echo '<b>' . $i . '</b> &nbsp;';
                                    }
                                    else
                                    {
                                        echo '<a href="waitingfamilies.php?max_race_id=7&beth[]=' . $bethz . '&pstart=' . $pstart . '&pend='.$pend.'&page=' . $i . '&button=Submit+Search">' . $i . '</a> &nbsp;';
                                    }
                                }
                                $pstart = $pend + 0;
                                $pend = $pend + 12;
                            }
                            $p++;
                        }

                        if (!isset($currentpage)) {
                            $currentpage = 1;
                        }

                        //if(($_GET['pend'] + 5) != $pend){
                        if ($currentpage != $i) {
                            if (isset($_GET['pend'])) {
                                $lpstart =$_GET['pend'] + 0;
                                $lpend = $_GET['pend'] + 12;
                                $page = $_GET['page'] + 1;
                            } else {
                                $lpstart = 1;
                                $lpend = 12;
                                $page = 2;
                            }
                            if ($i != '') {
                                //   echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="waitingfamilies.php?max_race_id=7&beth[]='.$bethz.'&pstart='.$lpstart.'&pend='.$lpend.'&page='.$page.'&button=Submit+Search" class="next1">Next</a>';
                                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="waitingfamilies.php?max_race_id=7&beth[]=' . $bethz . '&pstart=' . $lpstart . '&pend=' . $lpend . '&page=' . $page . '&button=Submit+Search" class="next">Next</a>';
                            }
                            // echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="waitingfamilies.php?max_race_id=7&famreg='.$famregz.'&pareth='.$parethz.'&beth='.$bethz.'&family_num_children='.$_GET[family_num_children].'&family_state='.$_GET[family_state].'&pstart='.$lpstart.'&pend='.$lpend.'&page='.$page.'" class="next">Next</a>';
                        }

        ?>

                    <!-- -->
        <?php 
        
        ?>
    </div>
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
</html>