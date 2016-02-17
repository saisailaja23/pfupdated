<?php
////////////////////////////////////////////////////////////////
//               BADGE WIDGETS				                  //
//    Created : 20 April, 2010			                      //
//    Creator : Gautam Chaudhary (Pulprix Developments)       //
//    Email : gkcgautam@gmail.com                             //
//    This product is owned by its creator                    //
//    This product cannot by redistributed by anyone else     //
//                 Do not remove this notice                  //
////////////////////////////////////////////////////////////////

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'gkc_badgeWidgets.inc.php' );

// --------------- page variables and login

$_page['name_index'] = 34;
$_page['css_name'] = 'gkc_badgewidgets.css';

$logged['member'] = member_auth(0);

$_page['header'] = _t("_gkc_bw_Group Badge");
$_page['header_text'] = _t("_gkc_bw_Group Badge");

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompPageMainCode();

// check if component enabled

global $badgewidgetConf;
if (!$badgewidgetConf['Comp_Groups']) {
    header("Location:badgeWidgets.php");
}

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompPageMainCode() {
    global $site;
    $member['ID'] = (int) $_COOKIE['memberID'];
    $member = getProfileInfo($member['ID']);

    $sQuery = mysql_query("
				SELECT `bx_groups_main`.`title` AS 'Name',`bx_groups_main`.`id` AS 'ID'
				FROM `bx_groups_main`
				WHERE
				`bx_groups_main`.`status` = 'approved' AND
				`bx_groups_main`.`author_id` = " . $member['ID'] . "
			") or
            die('Error while fetching data. Reloading the page might solve the issue. <!-- ' . mysql_error() . ' -->');



    $selectElement = '<select name="GID">
	<option value="">- ' . _t("_gkc_bw_SELECT") . ' -</option>';
    $selectElement_new = '<select name="GID_new">
            <option value="">- ' . _t("_gkc_bw_SELECT") . ' -</option>';
    while ($row = mysql_fetch_array($sQuery)) {
        $selectElement .= '<option value="' . $row['ID'] . '" >' . htmlspecialchars($row['Name']) . '</option>
		';
        $selectElement_new .= '<option value="' . $row['ID'] . '" selected="selected">' . htmlspecialchars($row['Name']) . '</option>
		';
    }
    $selectElement .= '</select>';


    ob_start();
    $selectedOption = (isset($_GET['order_value'])) ? $_GET['order_value'] : 'random';
    ?>


    <?php echo badgewidget_topmenu(); ?>
    <div class="badgewidgetscontentdiv">
<!--        <font style="font-size:14px;font-weight:bold;"><?php //echo _t("_gkc_bw_Select a Group to create a Badge"); ?>:</font>
        <br/>
        <br/>-->
        <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<!--            <table border="0" cellspacing="5" cellpadding="0">
                <tr>
                    <td><?php //echo $selectElement; ?>&nbsp;</td>
                    <td>Width: <input type="" name="width" value="100" maxlength = "3" size = "3">%</td>
                    <td><input type="submit" name ="badge1" value="<?php //echo _t("_gkc_bw_Create Badge"); ?>"/></td>
                </tr>
            </table>-->
            <br/>
            <table border="0" cellspacing="5" cellpadding="0">
                <tr><font style="font-size:14px;font-weight:bold;">Profile Group Badge(New):</font></tr>
                <tr><br/></tr>
                <tr>
                    <td><?php echo $selectElement_new; ?></td>
                    <td>&nbsp;&nbsp;Set Order:
                        <select name="order_value">
                            <option <?php echo($selectedOption == 'random') ? 'selected' : ''; ?> value="random" >Random</option>
                            <option <?php echo($selectedOption == 'oldest') ? 'selected' : ''; ?> value="oldFirst" >Oldest Waiting</option>
                            <option <?php echo($selectedOption == 'newest') ? 'selected' : ''; ?> value="newFirst" >Newest First</option>
                            <option <?php echo($selectedOption == 'alphabetic') ? 'selected' : ''; ?> value="FirstName" >Alphabetic</option>
                        </select>
                        &nbsp;
                    </td>
                    <td></td>
                    <td><input type="submit" name ="badge2" value="<?php echo _t("_gkc_bw_Create Badge"); ?>"/></td>
                </tr>
            </table>
        </form>
        <?php
        if (mysql_num_rows($sQuery) < 1) {
            echo '<br/><br/><font style="color:#B40404;font-weight:bold;">' . _t("_gkc_bw_You have not created any group") . '</font>';
        }
        ?>
    </div>




    <?php
    if (isset($_GET['badge1'])) {
        if (isset($_GET['GID']) && (int) $_GET['GID'] > 0) {


            if (isGroupCreator($member['ID'], (int) $_GET['GID'])) {
                global $badgewidgetConf;

                $GID = (int) $_GET['GID'];
                if (isset($_GET['width']) && $_GET['width'] >= 20) {
                    $width = (int) $_GET['width'];
                } else {
                    $width = 100;
                }
                $groupUrl = $badgewidgetConf['GroupUrl'] . $GID;
                $code = displayGroupCreatorBadge($GID, $member['ID'], $width);
                $embedd_div_id = date("mdGis") . 'm' . $member['ID'] . 'g' . $GID . 'c' . substr(md5(uniqid(rand(), true)), 0, 5);
                $embedd_validator_code = generateGroupConf($member['ID'], $GID);
                $embedd_code = '<!--Badge Start --><div id="' . $embedd_div_id . '"><script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.min.js" ></script><div style="width:350px;text-align:center"><font style="font-size:10px;font-family: verdana;font-weight: none;"><a href="' . $site['url'] . '">' . $site['title'] . ' ' . _t("_gkc_bw_Groups") . '</a></font></div></div><script type="text/javascript" src="' . $site['url'] . 'load_badge_widget.php?GID=' . $GID . '&MID=' . $member['ID'] . '&display=groupowner&conf=' . $embedd_validator_code . '&width=' . $width . '" onload="document.getElementById(\'' . $embedd_div_id . '\').innerHTML=\'\';"></script><!-- Badge End -->';
                $embedd_codeAl = '<!--Badge Start --><div id="' . $embedd_div_id . '"><script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.min.js" ></script><div style="width:350px;text-align:center"><font style="font-size:10px;font-family: verdana;font-weight: none;"><a href="' . $site['url'] . '">' . $site['title'] . ' ' . _t("_gkc_bw_Groups") . '</a></font></div></div><script type="text/javascript" src="' . $site['url'] . 'load_badge_widget.php?GID=' . $GID . '&MID=' . $member['ID'] . '&display=groupowner&conf=' . $embedd_validator_code . '&order=alfa&width=' . $width . '" onload="document.getElementById(\'' . $embedd_div_id . '\').innerHTML=\'\';"></script><!-- Badge End -->';
                $embedd_codeRd = '<!--Badge Start --><div id="' . $embedd_div_id . '"><script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.min.js" ></script><div style="width:350px;text-align:center"><font style="font-size:10px;font-family: verdana;font-weight: none;"><a href="' . $site['url'] . '">' . $site['title'] . ' ' . _t("_gkc_bw_Groups") . '</a></font></div></div><script type="text/javascript" src="' . $site['url'] . 'load_badge_widget.php?GID=' . $GID . '&MID=' . $member['ID'] . '&display=groupowner&conf=' . $embedd_validator_code . '&order=rand&width=' . $width . '" onload="document.getElementById(\'' . $embedd_div_id . '\').innerHTML=\'\';"></script><!-- Badge End -->';
                $embedd_code2 = '<!--Badge Start --><div id="' . $embedd_div_id . '"><script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.min.js" ></script><div style="width:350px;text-align:center"><font style="font-size:10px;font-family: verdana;font-weight: none;"><a href="' . $site['url'] . '">' . $site['title'] . ' ' . _t("_gkc_bw_Groups") . '</a></font></div></div><script type="text/javascript" src="' . $site['url'] . 'load_badge_widget_s.php?GID=' . $GID . '&MID=' . $member['ID'] . '&display=groupowner&conf=' . $embedd_validator_code . '&width=' . $width . '" onload="document.getElementById(\'' . $embedd_div_id . '\').innerHTML=\'\';"></script><!-- Badge End -->';
                $embedd_code2Al = '<!--Badge Start --><div id="' . $embedd_div_id . '"><script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.min.js" ></script><div style="width:350px;text-align:center"><font style="font-size:10px;font-family: verdana;font-weight: none;"><a href="' . $site['url'] . '">' . $site['title'] . ' ' . _t("_gkc_bw_Groups") . '</a></font></div></div><script type="text/javascript" src="' . $site['url'] . 'load_badge_widget_s.php?GID=' . $GID . '&MID=' . $member['ID'] . '&display=groupowner&conf=' . $embedd_validator_code . '&order=alfa&width=' . $width . '" onload="document.getElementById(\'' . $embedd_div_id . '\').innerHTML=\'\';"></script><!-- Badge End -->';
                $embedd_code2Rd = '<!--Badge Start --><div id="' . $embedd_div_id . '"><script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.min.js" ></script><div style="width:350px;text-align:center"><font style="font-size:10px;font-family: verdana;font-weight: none;"><a href="' . $site['url'] . '">' . $site['title'] . ' ' . _t("_gkc_bw_Groups") . '</a></font></div></div><script type="text/javascript" src="' . $site['url'] . 'load_badge_widget_s.php?GID=' . $GID . '&MID=' . $member['ID'] . '&display=groupowner&conf=' . $embedd_validator_code . '&order=rand&width=' . $width . '" onload="document.getElementById(\'' . $embedd_div_id . '\').innerHTML=\'\';"></script><!-- Badge End -->';

                echo '
				<div class="badgewidgetscontentdiv">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr><td>
				<b>' . _t("_gkc_bw_Copy the code below") . '</b><br/><br/>
				<input type="text" style="width:300px;height:20px;font-size:12px;" onclick="this.select()" value="' . htmlspecialchars($embedd_code) . '" /> (Normal Order)<br>
                <input type="text" style="width:300px;height:20px;font-size:12px;" onclick="this.select()" value="' . htmlspecialchars($embedd_codeAl) . '" /> (Alphabetical Order) <br>
                <input type="text" style="width:300px;height:20px;font-size:12px;" onclick="this.select()" value="' . htmlspecialchars($embedd_codeRd) . '" /> (Random Order)
				</td></tr>
				<tr>
				<td>
				<font style="font-size:14px;font-weight:bold;">' . _t("_gkc_bw_Badge Preview") . ':</font><br/><br/>
				' . $code . '
				</td>
				</tr>
				</table>
				</div>


                <div class="badgewidgetscontentdiv">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr><td>
				<b>' . _t("_gkc_bw_Copy the code below") . '</b><br/><br/>
				<input type="text" style="width:300px;height:20px;font-size:12px;" onclick="this.select()" value="' . htmlspecialchars($embedd_code2) . '" /> (Normal Order) <br>
                <input type="text" style="width:300px;height:20px;font-size:12px;" onclick="this.select()" value="' . htmlspecialchars($embedd_code2Al) . '" /> (Alphabetical Order) <br>
                <input type="text" style="width:300px;height:20px;font-size:12px;" onclick="this.select()" value="' . htmlspecialchars($embedd_code2Rd) . '" /> (Random Order) <br>
				</td></tr>
				<tr>
				<td>
				<font style="font-size:14px;font-weight:bold;">' . _t("_gkc_bw_Badge Preview") . ':</font><br/><br/>
				' . $embedd_code2 . '
				</td>
				</tr>
				</table>
				</div>';
            } else {
                echo '<div class="badgewidgetscontentdiv">
					<img src="badgewidgets/images/action_report.png" align="absmiddle"/> ' . _t("_gkc_bw_You need to be owner") . '
					</div>';
            }
        }
    } else if (isset($_GET['badge2'])) {
        if (isset($_GET['GID_new']) && (int) $_GET['GID_new'] > 0) {
            if (isGroupCreator($member['ID'], (int) $_GET['GID_new'])) {
                /* global $badgewidgetConf;
                  $GID_new =(int)$_GET['GID_new'];
                  if(isset($_GET['rows']) && $_GET['rows'] >= 9) { $rows=(int)$_GET['rows']; } else { $rows=3; }
                  if(isset($_GET['columns']) && $_GET['columns'] >= 9) { $columns=(int)$_GET['columns']; } else { $columns=3; }
                  $groupUrl       = $badgewidgetConf['GroupUrl'].$GID;
                  $code           = displayAgencyFamilyBadge($member['ID'],$GID_new, $rows,$columns);
                  $embedd_div_id  = date("mdGis").'m'.$member['ID'].'g'.$GID_new.'c'.substr(md5(uniqid(rand(), true)), 0, 5);
                  $embedd_validator_code = generateGroupConf($member['ID'],$GID_new);
                  $embedd_code = '<!--Badge Start --><div id="'.$embedd_div_id.'"><script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.min.js" ></script><div style="width:350px;text-align:center"><font style="font-size:10px;font-family: verdana;font-weight: none;"><a href="'.$site['url'].'">'.$site['title'].' '._t("_gkc_bw_Groups").'</a></font></div></div><script type="text/javascript" src="'.$site['url'].'load_badge_widget.php?GID='.$GID_new.'&MID='.$member['ID'].'&display=groupagencybadge&conf='.$embedd_validator_code.'&rows='.$rows.'&columns='.$columns.'" onload="document.getElementById(\''.$embedd_div_id.'\').innerHTML=\'\';"></script><!-- Badge End -->';
                  $embedd_code2 = '<!--Badge Start --><div id="'.$embedd_div_id.'"><script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.min.js" ></script><div style="width:350px;text-align:center"><font style="font-size:10px;font-family: verdana;font-weight: none;"><a href="'.$site['url'].'">'.$site['title'].' '._t("_gkc_bw_Groups").'</a></font></div></div><script type="text/javascript" src="'.$site['url'].'load_badge_widget.php?GID='.$GID_new.'&MID='.$member['ID'].'&display=groupagencybadge&conf='.$embedd_validator_code.'&rows='.$rows.'&columns='.$columns.'" onload="document.getElementById(\''.$embedd_div_id.'\').innerHTML=\'\';"></script><!-- Badge End -->';
                  echo '
                  <div class="badgewidgetscontentdiv">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr><td>
                  <b>'._t("_gkc_bw_Copy the code below").'</b><br/><br/>
                  <input type="text" style="width:300px;height:20px;font-size:12px;" onclick="this.select()" value="'.htmlspecialchars($embedd_code).'" /> (Normal Order)<br>
                  </td></tr>
                  <tr>
                  <td>
                  <font style="font-size:14px;font-weight:bold;">'._t("_gkc_bw_Badge Preview").':</font><br/><br/>
                  '.$code.'
                  </td>
                  </tr>
                  </table>
                  </div>


                  <div class="badgewidgetscontentdiv">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr><td>
                  <b>'._t("_gkc_bw_Copy the code below").'</b><br/><br/>
                  <input type="text" style="width:300px;height:20px;font-size:12px;" onclick="this.select()" value="'.htmlspecialchars($embedd_code2).'" /> (Normal Order) <br>
                  </td></tr>
                  <tr>
                  <td>
                  <font style="font-size:14px;font-weight:bold;">'._t("_gkc_bw_Badge Preview").':</font><br/><br/>
                  '.$embedd_code2.'
                  </td>
                  </tr>
                  </table>
                  </div>'; */

                global $badgewidgetConf;

                $GID = (int) $_GET['GID_new'];
                if (isset($_GET['width']) && $_GET['width'] >= 20) {
                    $width = (int) $_GET['width'];
                } else {
                    $width = 100;
                }
//if(isset($_GET['rows']) && $_GET['rows'] <= 3) { $rows=(int)$_GET['rows']; } else { $rows=3; }
//if(isset($_GET['columns']) && $_GET['columns'] <= 3) { $columns=(int)$_GET['columns']; } else { $columns=3; }
                if (isset($_GET['order_value'])) {
                    $order = $_GET['order_value'];
                } else {
                    $order = 'random';
                }
                $groupUrl = $badgewidgetConf['GroupUrl'] . $GID;
                $code = displayAgencyFamilyBadge($GID, $member['ID'], $order);
                $embedd_div_id = date("mdGis") . 'm' . $member['ID'] . 'g' . $GID . 'c' . substr(md5(uniqid(rand(), true)), 0, 5);
                $embedd_validator_code = generateGroupConf($member['ID'], $GID);
                $embedd_code = '<!--Badge Start --><div id="' . $embedd_div_id . '"><div style="width:350px;text-align:center"><font style="font-size:10px;font-family: verdana;font-weight: none;"><a href="' . $site['url'] . '">' . $site['title'] . ' ' . _t("_gkc_bw_Groups") . '</a></font></div></div><script type="text/javascript" src="' . $site['url'] . 'load_badge_widget.php?GID=' . $GID . '&MID=' . $member['ID'] . '&display=groupagencybadge&conf=' . $embedd_validator_code . '&order=' . $order . '" onload="document.getElementById(\'' . $embedd_div_id . '\').innerHTML=\'\';"></script><!-- Badge End -->';
                $embedd_code2 = '<!--Badge Start --><div id="' . $embedd_div_id . '"><div style="width:350px;text-align:center"><font style="font-size:10px;font-family: verdana;font-weight: none;"><a href="' . $site['url'] . '">' . $site['title'] . ' ' . _t("_gkc_bw_Groups") . '</a></font></div></div><script type="text/javascript" src="' . $site['url'] . 'load_badge_widget_s.php?GID=' . $GID . '&MID=' . $member['ID'] . '&display=groupagencybadge&conf=' . $embedd_validator_code . '&order=' . $order . '" onload="document.getElementById(\'' . $embedd_div_id . '\').innerHTML=\'\';"></script><!-- Badge End -->';
//$code            = addslashes($code);
                echo '
				<div class="badgewidgetscontentdiv">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr><td>
				<b>' . _t("_gkc_bw_Copy the code below") . '</b><br/><br/>
				<input type="text" style="width:300px;height:20px;font-size:12px;" onclick="this.select()" value="' . htmlspecialchars($embedd_code) . '" /><br>
                		</td></tr>
				<tr>
				<td>
				<font style="font-size:14px;font-weight:bold;">' . _t("_gkc_bw_Badge Preview") . ':</font><br/><br/>
				' . $code . '
				</td>
				</tr>
				</table>
				</div>

                                ';
            } else {
                echo '<div class="badgewidgetscontentdiv">
                                    <img src="badgewidgets/images/action_report.png" align="absmiddle"/> ' . _t("_gkc_bw_You need to be owner") . '
                                    </div>';
            }
        }
    }
    ?>
    <div style="height:100px;"> </div>

    <?php
    $ret = ob_get_clean();

    return $ret;
}
?>