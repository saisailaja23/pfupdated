<?php

////////////////////////////////////////////////////////////////
//               BADGE WIDGETS                                  //
//    Created : 20 April, 2010                                  //
//    Creator : Gautam Chaudhary (Pulprix Developments)       //
//    Email : gkcgautam@gmail.com                             //
//    This product is owned by its creator                    //
//    This product cannot by redistributed by anyone else     //
//                 Do not remove this notice                  //
////////////////////////////////////////////////////////////////

require_once( 'header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'gkc_badgeWidgets_conf.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'gkc_badgeWidgets_prof_view.inc.php');

//require_once( BX_DIRECTORY_PATH_INC . 'classes/BxDolGroups.php' );



function isGroupMember($MID = 0, $GID = 0) {
    $result = mysql_query("
                SELECT *
                FROM `bx_groups_fans` WHERE
                `bx_groups_fans`.`id_profile` = " . $MID . " AND
                `bx_groups_fans`.`id_entry`  = " . $GID . " AND
                `bx_groups_fans`.`confirmed`   = '1'
            ");
    $num_rows = mysql_num_rows($result);

    if ($num_rows) {
        return true;
    } else {
        return false;
    }
}

function isEventParticipant($MID = 0, $EID = 0) {
    $result = mysql_query("
                SELECT *
                FROM `bx_events_participants` WHERE
                `bx_events_participants`.`id_profile` = " . $MID . " AND
                `bx_events_participants`.`id_entry`  = " . $EID . " AND
                `bx_events_participants`.`confirmed`   = '1'
            ");
    $num_rows = mysql_num_rows($result);

    if ($num_rows) {
        return true;
    } else {
        return false;
    }
}

function isGroupCreator($MID = 0, $GID = 0) {
    $result = mysql_query("
                SELECT `title`
                FROM `bx_groups_main` WHERE
                `bx_groups_main`.`author_id` = " . $MID . " AND
                `bx_groups_main`.`id`  = " . $GID . "
            ");
    $num_rows = mysql_num_rows($result);

    if ($num_rows) {
        return true;
    } else {
        return false;
    }
}

function isEventCreator($MID = 0, $EID = 0) {
    $result = mysql_query("
                SELECT `Title`
                FROM `bx_events_main` WHERE
                `bx_events_main`.`ResponsibleID` = " . $MID . " AND
                `bx_events_main`.`ID`  = " . $EID . "
            ");
    $num_rows = mysql_num_rows($result);

    if ($num_rows) {
        return true;
    } else {
        return false;
    }
}

function limitStringLength($input, $len = 0) {
    if (strlen($input) > $len)
        return mb_substr($input, 0, $len - 0) . "";
    else
        return $input;
}

function generateGroupConf($MID = 0, $GID = 0) {
    $out = ($MID + 7 * ($GID)) * ($MID) + 9186;
    return $out;
}

function generateEventConf($MID = 0, $EID = 0, $display = '') {
    $out = ($MID + 7 * ($EID)) * ($MID) + 56;
    if ($display == 'eventcreated') {
        $out = $out * 13;
    }
    if ($display == 'eventjoined') {
        $out = $out + 6584;
    }

    return $out;
}

function generatePhotoConf($MID = 0, $num = 0) {
    $out = ($MID + 16 * ($num)) * ($MID) + 7887;
    return $out;
}

function ae_detect_ie() {
    if (isset($_SERVER['HTTP_USER_AGENT']) &&
            (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
        return true;
    else
        return false;
}

function badgewidget_topmenu() {
//    $out = '
//<div style="text-align:center; background:#f2f2f2;padding:5px;margin-bottom:10px;" class="badgewidgetnav">
//<a href="badgeWidgets-profile.php" class="badgewidgetnav">' . _t("_gkc_bw_Profile badge") . '</a>&nbsp;&nbsp;|&nbsp;&nbsp;
//<a href="badgeWidgets-photos.php" class="badgewidgetnav">' . _t("_gkc_bw_Photos badge") . '</a>&nbsp;&nbsp;|&nbsp;&nbsp;
//<a href="badgeWidgets-events-created.php" class="badgewidgetnav">' . _t("_gkc_bw_Events Created") . '</a>&nbsp;&nbsp;|&nbsp;&nbsp;
//<a href="badgeWidgets-events-joined.php" class="badgewidgetnav">' . _t("_gkc_bw_Events Attending") . '</a>&nbsp;&nbsp;|&nbsp;&nbsp;
//<a href="badgeWidgets-groups-owned.php" class="badgewidgetnav">' . _t("_gkc_bw_Group Badge") . '</a>&nbsp;&nbsp;|&nbsp;&nbsp;
//<a href="badgeWidgets-groups-joined.php" class="badgewidgetnav">' . _t("_gkc_bw_Group Fan Badge") . '</a>
//</div>';

    return $out;
}

function displayUserIcon($ID) {
    global $site;

    $member = getProfileInfo($ID);

    if ($member['Avatar']) {
        $query = "SELECT * FROM  `bx_photos_main` WHERE `ID` = " . $member['Avatar'] . " AND `Status` = 'approved' LIMIT 1";
        $row = mysql_fetch_array(mysql_query($query));

        $ImageUrl = $site['url'] . 'm/photos/get_image/thumb/' . $row['Hash'] . '.' . $row['Ext'];
        $ImageUrl = $site['url'] . 'modules/boonex/avatar/data/images/' . $member['Avatar'] . '.' . $row['Ext'];
    } elseif ($member['Sex'] == 'couple') {
        $ImageUrl = $site['url'] . 'badgewidgets/images/user_thumb_couple.jpg';
    } elseif ($member['Sex'] == 'female') {
        $ImageUrl = $site['url'] . 'badgewidgets/images/user_thumb_female.jpg';
    } else {
        $ImageUrl = $site['url'] . 'badgewidgets/images/user_thumb_male.jpg';
    }

    $ret = '';
    $ret .= '<div style=" position:relative;width:60px;height:60px;text-align:centre;margin:0px auto;">';
    $ret .= "<a href=\"" . getProfileLinks($ID) . "\" target=\"TOP\">";
    $ret .= '<img src="' . $ImageUrl . '" style="width:50px;height:50px;padding:1px;border:1px solid #cccccc;" alt="' . process_line_output($member['NickName']) . '" />';
    $ret .= '</a>';
    $ret .= '</div>';

    return $ret;
}

function displayEventsJoinedBadge($EID = 0, $MID = 0, $width = 300, $height) {
    global $site;
    global $badgewidgetConf;
    $member = getProfileInfo($MID);
    $memberLogged = getLoggedId();

    if (isEventParticipant($member['ID'], $EID)) {
        $result = mysql_query("
                SELECT `bx_events_main`.`Title` AS 'Name',`bx_events_main`.`ID` AS 'ID',`bx_events_main`.`EntryUri` AS 'URI',
                `bx_events_main`.`PrimPhoto` AS 'thumb', `bx_events_main`.`EventStart` as 'start',
                 `bx_events_main`.`EventEnd` as 'end',`bx_events_main`.`Place` as 'Place',
                 `bx_events_main`.`City` as 'City',`bx_events_main`.`Country` as 'Country',
                 `bx_events_main`.`allow_view_event_to` as 'allow_view_event_to',
                 `bx_events_main`.`ResponsibleID` as 'ResponsibleID'
                FROM `bx_events_main`
                WHERE
                `bx_events_main`.`Status` = 'approved' AND
                `bx_events_main`.`ID` = " . $EID . "
            ") or
                die('Error while fetching data. Reloading the page might solve the issue. <!-- ' . mysql_error() . ' -->');

        $row = mysql_fetch_array($result);

        if ($row) {
            if ($row['thumb'] != 0) {
                $result = mysql_query("SELECT `bx_photos_main`.`Hash` AS 'Hash',`bx_photos_main`.`Ext` AS 'Ext'
                            FROM `bx_photos_main` WHERE `bx_photos_main`.`ID` = " . $row['thumb'] . " LIMIT 1");
                $eventimg = mysql_fetch_array($result);

                $eventImageUrl = $site['url'] . 'm/photos/get_image/thumb/' . $eventimg['Hash'] . '.' . $eventimg['Ext'];
            } else {
                $eventImageUrl = $site['url'] . 'badgewidgets/images/event_thumb.png';
            }

            $memberIconUrl = "{$site['url']}badgewidgets/images/member.png";
            $eventIconUrl = $site['url'] . 'badgewidgets/images/event_icon.png';
            $eventUrl = $badgewidgetConf['EventUrl'] . $row['URI'];
            $eventParticipantsUrl = $badgewidgetConf['EventParticipantsUrl'] . $row['URI'];
            $num_guests_arr = @mysql_fetch_array(mysql_query("
                SELECT COUNT(*) as 'count' FROM `bx_events_participants`
                WHERE
                `bx_events_participants`.`confirmed` = '1' AND
                `bx_events_participants`.`id_entry` = " . $EID . "
            "));
            $num_guests = $num_guests_arr['count'];

            $eventDetails = '';
            if ($row['allow_view_event_to'] == 2 && $memberLogged != $row['ResponsibleID']) {
                if ($badgewidgetConf['EventDisplayHideText']) {
                    $eventDetails = '<div style="color:' . $badgewidgetConf['TextColor'] . ';font-size:10px;font-family: verdana;spadding:10px;margin-top:10px;">' . _t("_gkc_bw_Details visible to event creator only") . '</div>';
                }
            } elseif ($row['allow_view_event_to'] > 3 && !$memberLogged) {
                if ($badgewidgetConf['EventDisplayHideText']) {
                    $eventDetails = '<div style="color:' . $badgewidgetConf['TextColor'] . ';font-size:10px;font-family: verdana;spadding:10px;margin-top:10px;"></div>' . _t("_gkc_bw_Details visible to members only") . '';
                }
            } else {
                $eventDetails = '
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="color: ' . $badgewidgetConf['TextColor'] . ';font-family: verdana;font-size: 11px;margin-top:5px;">
            <tr>
            <td style="vertical-align:top;">' . _t("_gkc_bw_Place") . ':</td>
            <td style="vertical-align:top; padding-left:2px;">' . $row['Place'] . ', ' . $row['City'] . ', ' . $row['Country'] . '</td>
            </tr>
            <tr>
            <td style="vertical-align:top;">' . _t("_gkc_bw_Start") . ':</td>
            <td style="vertical-align:top; padding-left:2px;">' . date("M j, Y, g:i a", $row['start']) . '</td>
            </tr>
            <tr>
            <td style="vertical-align:top;">' . _t("_gkc_bw_End") . ':</td>
            <td style="vertical-align:top; padding-left:2px;">' . date("M j, Y, g:i a", $row['end']) . '</td>
            </tr>';
                if ($badgewidgetConf['EventDisplayGuestsNum']) {
                    $eventDetails .= '<tr>
                <td style="vertical-align:top;">' . _t("_gkc_bw_Guests") . ':</td>
                <td style="vertical-align:top; padding-left:2px;">' . $num_guests . '</td>
                </tr>';
                }
                $eventDetails .= '</table>';
            }

            $code = '
        <div style="width: ' . $width . 'px; height:' . $height . ';">
            <div style="background: ' . $badgewidgetConf['TitleBGColor'] . ';padding: 3px;">
            <font style="color:' . $badgewidgetConf['TitleColor'] . ';font-weight:bold;font-size:16px; font-family:' . $badgewidgetConf['TitleFontFamily'] . '">' . $site['title'] . '</font>
            </div>
            <div style="background: ' . $badgewidgetConf['BoxBGColor'] . ';display: block;border-right: 1px solid ' . $badgewidgetConf['BoxBorderColor'] . ';border-bottom: 1px solid ' . $badgewidgetConf['BoxBorderColor'] . ';border-left: 1px solid ' . $badgewidgetConf['BoxBorderColor'] . ';margin: 0px;padding: 0px 0px 5px 0px;">
            <div style="background: ' . $badgewidgetConf['BoxBGColor'] . ';display: block;padding: 5px;">
            <table cellspacing="0" cellpadding="0" border="0">
                <tr>
                <td valign="top"><img src="' . $memberIconUrl . '" alt=""/></td>
                <td valign="top"><p style="color: ' . $badgewidgetConf['TextColor'] . ';font-family: verdana;font-size: 11px;margin: 0px 0px 0px 0px;padding: 0px 8px 0px 8px;">
                <a href="' . getProfileLinks($member['ID']) . '" title="' . $member['NickName'] . '" target="TOP" style="color: ' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 11px;font-weight: normal;margin: 0px;padding: 0px 0px 0px 0px;text-decoration: none;">' . $member['NickName'] . '</a> ' . _t("_gkc_bw_is attending") . '</p></td>
                </tr>
            </table>
            </div>
            <div style="background: #FFFFFF;clear: both;display: block;margin: 0px;overflow: hidden;padding: 5px;">
            <table cellspacing="0" cellpadding="0" border="0">
            <tr><td valign="top">
            <a href="' . $eventUrl . '" title="' . htmlspecialchars($row['Name']) . '" target="TOP" style="border: 0px;color: ' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 12px;font-weight: bold;margin: 0px;padding: 0px;text-decoration: none;">
            <img src="' . $eventImageUrl . '" style="border: 0px;margin: 0px;padding: 0px;width:50px;height:50px;" alt="' . htmlspecialchars($row['Name']) . '"/></a>
            </td><td valign="top" style="padding: 0px 8px 0px 8px;">
            <img src="' . $eventIconUrl . '" alt="" align="absmiddle" height="16" width="16"/>
            <a href="' . $eventUrl . '" title="' . htmlspecialchars($row['Name']) . '" target="TOP" style="border: 0px;color: ' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 12px;font-weight: bold;margin: 0px;padding: 0px;text-decoration: none;">' . htmlspecialchars($row['Name']) . '</a>
            <br/>
            ' . $eventDetails . '
            </td>
            </tr></table>
            </div></div>';

            if (strlen($badgewidgetConf['FooterTextGroupMember']) > 0) {
                $code .= '<div style="text-align:right;padding: 4px 0px 0px 0px;font-size:10px;font-family: verdana;font-weight: none;">
            ' . $badgewidgetConf['FooterTextGroupMember'] . '</div>';
            }

            $code .= '</div>';
            return $code;
        }
    }
}

function displayEventCreatorBadge($EID = 0, $MID = 0, $width, $height) {
    global $site;
    global $badgewidgetConf;
    $member = getProfileInfo($MID);
    $memberLogged = getLoggedId();

    if (isEventCreator($member['ID'], $EID)) {
        $result = mysql_query("
                SELECT `bx_events_main`.`Title` AS 'Name',`bx_events_main`.`ID` AS 'ID',`bx_events_main`.`EntryUri` AS 'URI',
                `bx_events_main`.`PrimPhoto` AS 'thumb', `bx_events_main`.`EventStart` as 'start',
                 `bx_events_main`.`EventEnd` as 'end',`bx_events_main`.`Place` as 'Place',
                 `bx_events_main`.`City` as 'City',`bx_events_main`.`Country` as 'Country',
                 `bx_events_main`.`allow_view_participants_to` as 'allow_view_participants_to',
                 `bx_events_main`.`ResponsibleID` as 'ResponsibleID'
                FROM `bx_events_main`
                WHERE
                `bx_events_main`.`Status` = 'approved' AND
                `bx_events_main`.`ID` = " . $EID . "
            ") or
                die('Error while fetching data. Reloading the page might solve the issue. <!-- ' . mysql_error() . ' -->');

        $row = mysql_fetch_array($result);

        if ($row) {

            if ($row['thumb'] != 0) {
                $result = mysql_query("SELECT `bx_photos_main`.`Hash` AS 'Hash',`bx_photos_main`.`Ext` AS 'Ext'
                            FROM `bx_photos_main` WHERE `bx_photos_main`.`ID` = " . $row['thumb'] . " LIMIT 1");
                $evntimg = mysql_fetch_array($result);

                $eventImageCode = '<img src="' . $site['url'] . 'm/photos/get_image/thumb/' . $evntimg['Hash'] . '.' . $evntimg['Ext'] . '" alt="' . htmlspecialchars($row['Name']) . '" style="width:45px;height:45px;padding:1px;border:1px solid #cccccc;"/>';
            } else {
                $eventImageCode = '<img src="' . $site['url'] . 'badgewidgets/images/event_thumb_small.png" alt="' . htmlspecialchars($row['Name']) . '" style="width:32px;height:32px;padding:0px;border:0px solid #cccccc;"/>';
            }

            $memberIconUrl = "{$site['url']}badgewidgets/images/member.png";
            $eventIconUrl = $site['url'] . 'badgewidgets/images/event_icon.png';
            $eventUrl = $badgewidgetConf['EventUrl'] . $row['URI'];
            $eventGuestsUrl = $badgewidgetConf['EventParticipantsUrl'] . $row['URI'];

            $guestsList = '';
            if ($row['allow_view_participants_to'] == 2 && $memberLogged != $row['ResponsibleID']) {
                if ($badgewidgetConf['EventDisplayHideText']) {
                    $guestsList = '<div style="color:' . $badgewidgetConf['TextColor'] . ';font-size:10px;font-family: verdana;spadding:10px;margin-top:10px;">' . _t("_gkc_bw_Guests visible to event creator only") . '</div>';
                }
            } elseif ($row['allow_view_participants_to'] > 3 && !$memberLogged) {
                if ($badgewidgetConf['EventDisplayHideText']) {
                    $guestsList = '<div style="color:' . $badgewidgetConf['TextColor'] . ';font-size:10px;font-family: verdana;spadding:10px;margin-top:10px;">' . _t("_gkc_bw_Guests visible to members only") . '</div>';
                }
            } else {
                $sQuerySQL = mysql_query("
            SELECT
                `bx_events_participants`.`id_profile` AS `ID`,
                `Profiles`.`NickName` AS NickName
            FROM `bx_events_participants`
            INNER JOIN `Profiles` ON `bx_events_participants`.`id_profile` = `Profiles`.`ID`
            WHERE
                `bx_events_participants`.`id_entry` = '{$EID}' AND `bx_events_participants`.`confirmed` = '1'
            ORDER BY RAND()
            LIMIT 10
        ;");

                $guestsList = '<table border="0" cellspacing="0" cellpadding="0">
        <tr>';
                $num = 1;
                while ($members = mysql_fetch_array($sQuerySQL)) {
                    $guestsList .= '<td style="width:67px; vertical-align:middle; text-align:center;padding-bottom:10px;">' .
                            displayUserIcon($members['ID'], 'none') . '<font style="font-size:10px;color:' . $badgewidgetConf['TextColor'] . ';">' . limitStringLength($members['NickName'], 10) . '</font></td>';
                    if ($num == 5) {
                        $guestsList .= '</tr><tr>';
                        $num = 0;
                    }
                    $num++;
                }
                $guestsList .= '</tr>
        </table>';
            }

            $num_guests_result = mysql_query("
                SELECT * FROM `bx_events_participants` WHERE
                `confirmed` = '1' AND
                `id_entry` = '{$EID}'
            ");
            $num_guests = mysql_num_rows($num_guests_result);


            $code = '
        <div style="width: ' . $width . 'px; height:' . $height . ';">
            <div style="background: ' . $badgewidgetConf['TitleBGColor'] . ';padding: 3px;">
            <font style="color:' . $badgewidgetConf['TitleColor'] . ';font-weight:bold;font-size:16px; font-family:' . $badgewidgetConf['TitleFontFamily'] . ';">' . $site['title'] . '</font>
            </div>
            <div style="background: ' . $badgewidgetConf['BoxBGColor'] . ';display: block;border-right: 1px solid ' . $badgewidgetConf['BoxBorderColor'] . ';border-bottom: 1px solid ' . $badgewidgetConf['BoxBorderColor'] . ';border-left: 1px solid ' . $badgewidgetConf['BoxBorderColor'] . ';margin: 0px;padding: 0px 0px 5px 0px;">
            <div style="background: ' . $badgewidgetConf['BoxBGColor'] . ';display: block;padding: 5px;">
            <table cellspacing="0" cellpadding="0" border="0">
                <tr>
                <td valign="top"><a href="' . $eventUrl . '" title="' . htmlspecialchars($row['Name']) . '" target="TOP">' . $eventImageCode . '</a></td>
                <td valign="middle"><p style="color: ' . $badgewidgetConf['TextColor'] . ';font-family: verdana;font-size: 11px;margin: 0px 0px 0px 0px;padding: 0px 8px 0px 8px;">
                <img src="' . $eventIconUrl . '" alt="" align="absmiddle" height="16" width="16"/> <a href="' . $eventUrl . '" title="' . htmlspecialchars($row['Name']) . '" target="TOP" style="color: ' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 14px;font-weight: bold;margin: 0px;padding: 0px 0px 0px 0px;text-decoration: none;">' . htmlspecialchars($row['Name']) . '</a>
                <br/>' . date($badgewidgetConf['EventDateFormat'], $row['start']) . ' - ' . date($badgewidgetConf['EventDateFormat'], $row['end']) . '
                <br/>' . _t("_gkc_bw_at") . ' ' . $row['Place'] . ', ' . $row['City'] . ', ' . $row['Country'] . '
                </p></td>
                </tr>
            </table>
            </div>
            <div style="background: #FFFFFF;clear: both;display: block;margin: 0px;overflow: hidden;padding: 5px;font-family:verdana;">
            ' . $guestsList . '

            <div style="text-align:right;font-family:verdana;font-size:10px;">
            <a style="color:' . $badgewidgetConf['TextColor'] . ';text-decoration:none;"
            href="' . $eventGuestsUrl . '" target="TOP">' . $num_guests . ' ' . _t("_gkc_bw_Guests") . '</a></div>
            </div></div>
        ';

            if (strlen($badgewidgetConf['FooterTextEventCreator']) > 0) {
                $code .= '<div style="text-align:right;padding: 4px 0px 0px 0px;font-size:10px;font-family: verdana;font-weight: none;">
            ' . $badgewidgetConf['FooterTextEventCreator'] . '</div>';
            }

            $code .= '</div>';
            return $code;
        }
    }
}

function displayGroupMemberBadge($GID = 0, $MID = 0, $width = 300, $height) {
    global $site;
    global $badgewidgetConf;
    $member = getProfileInfo($MID);

    if (isGroupMember($member['ID'], $GID)) {
        $result = mysql_query("
                SELECT `bx_groups_main`.`title` AS 'Name',`bx_groups_main`.`id` AS 'ID',`bx_groups_main`.`uri` AS 'URI',
                `bx_groups_main`.`thumb` AS 'thumb',`bx_groups_main`.`fans_count` AS 'fans_count'
                FROM `bx_groups_main`
                WHERE
                `bx_groups_main`.`status` = 'approved' AND
                `bx_groups_main`.`ID` = " . $GID . "
            ") or
                die('Error while fetching data. Reloading the page might solve the issue. <!-- ' . mysql_error() . ' -->');

        $row = mysql_fetch_array($result);

        if ($row) {
            if ($row['thumb'] != 0) {
                $result = mysql_query("SELECT `bx_photos_main`.`Hash` AS 'Hash',`bx_photos_main`.`Ext` AS 'Ext'
                            FROM `bx_photos_main` WHERE `bx_photos_main`.`ID` = " . $row['thumb'] . " LIMIT 1");
                $grpimg = mysql_fetch_array($result);

                $groupImageUrl = $site['url'] . 'm/photos/get_image/thumb/' . $grpimg['Hash'] . '.' . $grpimg['Ext'];
            } else {
                $groupImageUrl = $site['url'] . 'badgewidgets/images/group_thumb.jpg';
            }

            $memberIconUrl = "{$site['url']}badgewidgets/images/member.png";
            //$groupUrl = $badgewidgetConf['GroupUrl'].$row['URI'];
            $groupUrl = getProfileLinks($member['ID']);
            //$groupFansUrl = $badgewidgetConf['GroupFansUrl'].$row['URI'];
            $num_members = $row['fans_count'];

            $code = '
        <div style="width: ' . $width . 'px; height:' . $height . ';">
            <div style="background: ' . $badgewidgetConf['TitleBGColor'] . ';padding: 3px;">
            <font style="color:' . $badgewidgetConf['TitleColor'] . ';font-weight:bold;font-size:16px; font-family:' . $badgewidgetConf['TitleFontFamily'] . '">' . $site['title'] . '</font>
            </div>
            <div style="background: ' . $badgewidgetConf['BoxBGColor'] . ';display: block;border-right: 1px solid ' . $badgewidgetConf['BoxBorderColor'] . ';border-bottom: 1px solid ' . $badgewidgetConf['BoxBorderColor'] . ';border-left: 1px solid ' . $badgewidgetConf['BoxBorderColor'] . ';margin: 0px;padding: 0px 0px 5px 0px;">
            <div style="background: ' . $badgewidgetConf['BoxBGColor'] . ';display: block;padding: 5px;">
            <table cellspacing="0" cellpadding="0" border="0">
                <tr>
                <td valign="top"><img src="' . $memberIconUrl . '" alt=""/></td>
                <td valign="top"><p style="color: ' . $badgewidgetConf['TextColor'] . ';font-family: verdana;font-size: 11px;margin: 0px 0px 0px 0px;padding: 0px 8px 0px 8px;">
                <a href="' . getProfileLinks($member['ID']) . '" title="' . $member['NickName'] . '" target="TOP" style="color: ' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 11px;font-weight: normal;margin: 0px;padding: 0px 0px 0px 0px;text-decoration: none;">' . $member['NickName'] . '</a> ' . _t("_gkc_bw_is a fan of") . '</p></td>
                </tr>
            </table>
            </div>
            <div style="background: #FFFFFF;clear: both;display: block;margin: 0px;overflow: hidden;padding: 5px;">
            <table cellspacing="0" cellpadding="0" border="0">
            <tr><td valign="middle">
            <a href="' . $groupUrl . '" title="' . htmlspecialchars($row['Name']) . '" target="TOP" style="border: 0px;color: ' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 12px;font-weight: bold;margin: 0px;padding: 0px;text-decoration: none;">
            <img src="' . $groupImageUrl . '" style="border: 0px;margin: 0px;padding: 0px;width:50px;height:50px;" alt="' . htmlspecialchars($row['Name']) . '"/></a>
            </td><td valign="middle" style="padding: 0px 8px 0px 8px;">
            <a href="' . $groupUrl . '" title="' . htmlspecialchars($row['Name']) . '" target="TOP" style="border: 0px;color: ' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 12px;font-weight: bold;margin: 0px;padding: 0px;text-decoration: none;">' . htmlspecialchars($row['Name']) . '</a>
            <br/><font style="color: ' . $badgewidgetConf['TextColor'] . ';font-family: verdana;font-size: 11px;">' . $num_members . ' ' . _t("_gkc_bw_fans") . '</font></td>
            </tr></table>
            </div></div>';

            if (strlen($badgewidgetConf['FooterTextGroupMember']) > 0) {
                $code .= '<div style="text-align:right;padding: 4px 0px 0px 0px;font-size:10px;font-family: verdana;font-weight: none;">
            ' . $badgewidgetConf['FooterTextGroupMember'] . '</div>';
            }

            $code .= '</div>';
            return $code;
        }
    }
}

function displayGroupIframeBadge($GID = 0, $MID = 0, $width = 300, $height) {
    global $site;
    global $badgewidgetConf;
    $member = getProfileInfo($MID);

    if (isGroupMember($member['ID'], $GID)) {
        $result = mysql_query("
                SELECT `bx_groups_main`.`title` AS 'Name',`bx_groups_main`.`id` AS 'ID',`bx_groups_main`.`uri` AS 'URI',
                `bx_groups_main`.`thumb` AS 'thumb',`bx_groups_main`.`fans_count` AS 'fans_count'
                FROM `bx_groups_main`
                WHERE
                `bx_groups_main`.`status` = 'approved' AND
                `bx_groups_main`.`ID` = " . $GID . "
            ") or
                die('Error while fetching data. Reloading the page might solve the issue. <!-- ' . mysql_error() . ' -->');

        $row = mysql_fetch_array($result);

        $uri = $row['URI'];

        //$html = str_get_html('<iframe src="m/groups/view/sample1" frameborder="0" id="frameDemo" name="myspot" width="100%" height="1000px"></iframe>');
        $html = file_get_html('http://www.parentfinder.com/m/groups/view/' . $uri);
        $ret = $html->find('div[class=sys_ml]');
        $ret2 = $html->find('table[class=topMenu]');
        $ret3 = $html->find('table[class=subMenuInfoKeeper]');
        $ret4 = $html->find('div[class=bottomCopyright]');

        $html = str_replace($ret, "", $html);
        $html = str_replace($ret2, "", $html);
        $html = str_replace($ret3, "", $html);
        $code = str_replace($ret4, "", $html);
        $code .= <<<EOF
          <style>
            body {
    background: none;
    border-top: none;

   }
</style>
EOF;
        return $code;
    }
}

function displayGroupCreatorBadge($GID = 0, $MID = 0, $width) {
    global $site;
    global $badgewidgetConf;
    $member = getProfileInfo($MID);
    $memberLogged = getLoggedId();

    if (isGroupCreator($member['ID'], $GID)) {
        $result = mysql_query("
                SELECT `bx_groups_main`.`title` AS 'Name',`bx_groups_main`.`id` AS 'ID',`bx_groups_main`.`uri` AS 'URI',
                `bx_groups_main`.`thumb` AS 'thumb',`bx_groups_main`.`fans_count` AS 'fans_count',`bx_groups_main`.`allow_view_fans_to` AS 'allow_view_fans_to'
                FROM `bx_groups_main`
                WHERE
                `bx_groups_main`.`status` = 'approved' AND
                `bx_groups_main`.`ID` = " . $GID . "
            ") or
                die('Error while fetching data. Reloading the page might solve the issue. <!-- ' . mysql_error() . ' -->');

        $row = mysql_fetch_array($result);

        if ($row) {

            if ($row['thumb'] != 0) {
                $result = mysql_query("SELECT `bx_photos_main`.`Hash` AS 'Hash',`bx_photos_main`.`Ext` AS 'Ext'
                            FROM `bx_photos_main` WHERE `bx_photos_main`.`ID` = " . $row['thumb'] . " LIMIT 1");
                $grpimg = mysql_fetch_array($result);

                $groupImageUrl = $site['url'] . 'm/photos/get_image/thumb/' . $grpimg['Hash'] . '.' . $grpimg['Ext'];
            } else {
                $groupImageUrl = $site['url'] . 'badgewidgets/images/group_thumb.jpg';
            }

            $memberIconUrl = "{$site['url']}badgewidgets/images/member.png";
            //$groupUrl = $badgewidgetConf['GroupUrl'].$row['URI'];
            $groupUrl = getProfileLinks($memberLogged);
            //$groupFansUrl = $badgewidgetConf['GroupFansUrl'].$row['URI'];

            if ($row['allow_view_fans_to'] > 3 && !$memberLogged) {
                $membersList = '<div style="text-align:center;color:' . $badgewidgetConf['TextColor'] . ';font-size:11px;padding:10px;">' . _t("_gkc_bw_Fans visible only to members") . '</div>';
            } else {
                $sQuerySQL = mysql_query("
            SELECT
                `Profiles`.`ID`,
                `Profiles`.`NickName`,
                `Profiles`.`AdoptionAgency`,
                `Profiles`.`Sex`,
                `Profiles`.`FirstName`,
                `Profiles`.`Avatar`,
                `Profiles`.`DearBirthParent`,
                `Profiles`.`Couple`
            FROM `Profiles`
            WHERE
                 `Profiles`.`AdoptionAgency` = '$GID' AND `Profiles`.`ProfileType` = '2' AND `Profiles`.`Status` = 'Active'
        ;");

                bx_import('BxTemplSearchProfile');
                $oSearchProfile = new BxTemplSearchProfile();
                $membersList = '
         <script type="text/javascript">
        $(document).ready(function() {
         $("div.badsd").hide();
         $("div#a0").show();
         $("div#a0").css("display","block");
         $("div#a0").removeClass("hide");
         $("a#0").css({"font-weight":"bold", "font-size":"15px"});

          $("a.b").click(function() {
              var target = $(this).attr("id");
              $("div.badsd").hide();
              $("div#a"+target).show();
              $("div#a"+target).removeClass("hide");
              $("a.b").css({"font-weight":"normal", "font-size":"12px"});
              $("a#"+target).css({"font-weight":"bold", "font-size":"15px"});
             });
            });
         </script>
         <style>
         a, a:link, a:visited, a:active {
    cursor: pointer;
    text-decoration: none;
          }
         </style>
         <table border="0" cellspacing="0" cellpadding="0">
        <tr><td>';
                $num = 0;
                $i = 0;
                $page = '|';
                while ($members = mysql_fetch_assoc($sQuerySQL)) {
                    if ($num == 0) {
                        $display == "display=block";
                    } else {
                        $display == "";
                    }
                    if ($num % 10 == 0) {

                        $membersList .='<div class="badsd" id="a' . $num . '" ' . $display . '>';
                        $i++;
                        $myid = $num;
                        $page .= '<a class="b" id="' . $myid . '"> ' . $i . '</a> |';
                    }
                    $membersList .='<div style="width:49%; float:left;">';
                    //$membersList .= $oSearchProfile->displaySearchUnit($members);
                    $membersList .= draw_profile2($members);

                    $membersList .='</div>';

                    if ($num % 10 == 9) {
                        $membersList .= '</div>';
                    }

                    /*
                      $membersList .= '<td style="width:67px; vertical-align:middle; text-align:center;padding-bottom:10px;">'.
                      displayUserIcon( $members['ID'], 'none' ).'<font style="font-size:10px;color:'.$badgewidgetConf['TextColor'].';">'.limitStringLength($members['NickName'],10).'</font></td>';
                      if($num==5)
                      {
                      $membersList .= '</tr><tr>';
                      $num=0;
                      } */
                    $num++;
                }
                $membersList .= '</td></tr>
        </table>';
            }

            $num_members = mysql_num_rows($sQuerySQL);
            $become_fan = '';

            if ($memberLogged) {
                if (isGroupMember($memberLogged, $GID)) {
                    $become_fan = '<br/><br/>
                <font style="text-align:center;color:' . $badgewidgetConf['TextColor'] . ';font-size:11px;">' . _t("_gkc_bw_You are a fan") . '</font>';
                } else {
                    $become_fan = '
                <br/><br/>
                <a href="" target="TOP"
                style="color:' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 11px;text-decoration: none;">
                <img src="' . $site['url'] . 'badgewidgets/images/member.png" alt="" border="0" height="12" align="absmiddle"/> ' . _t("_gkc_bw_Become Fan") . '</a>';
                }
            }


            $code = '
        <link href="http://www.parentfinder.com/badge/css/badge_style.css" rel="stylesheet" type="text/css" />
<div style="width: ' . $width . '%;">
            <!--div style="background: ' . $badgewidgetConf['TitleBGColor'] . ';padding: 3px;">
            <font style="color:' . $badgewidgetConf['TitleColor'] . ';font-weight:bold;font-size:16px; font-family:' . $badgewidgetConf['TitleFontFamily'] . ';">' . $site['title'] . '</font>
            </div-->
            <div style="background: ' . $badgewidgetConf['BoxBGColor'] . ';display: block;border-right: 0px solid ' . $badgewidgetConf['BoxBorderColor'] . ';border-bottom: 0px solid ' . $badgewidgetConf['BoxBorderColor'] . ';border-left: 0px solid ' . $badgewidgetConf['BoxBorderColor'] . ';margin: 0px;padding: 0px 0px 5px 0px;">
            <div style="background: ' . $badgewidgetConf['BoxBGColor'] . ';display: block;padding: 5px;">
            <table cellspacing="0" cellpadding="0" border="0" style="margin:0px; padding:0px;">
                <tr>
                <td valign="top" width="45px"><a href="' . $groupUrl . '" title="' . htmlspecialchars($row['Name']) . '" target="TOP"><img src="' . $groupImageUrl . '" alt="' . htmlspecialchars($row['Name']) . '" border="0" style="width:45px;height:45px;padding:1px;border:1px solid #cccccc;"/></a></td>
                <td valign="middle"><p style="color: ' . $badgewidgetConf['TextColor'] . ';font-family: verdana;font-size: 11px;margin: 0px 0px 0px 0px;padding: 0px 8px 0px 8px;">
                <a href="' . $groupUrl . '" title="' . htmlspecialchars($row['Name']) . '" target="TOP" style="color: ' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 14px;font-weight: bold;margin: 0px;padding: 0px 0px 0px 0px;text-decoration: none;">' . htmlspecialchars($row['Name']) . '</a> ' . _t("_gkc_bw_on") . ' <!--a href="' . $site['url'] . '" style="color: ' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 11px;font-weight: normal;text-decoration: none;" target="TOP">' . $site['title'] . '</a-->
                ' . $become_fan . '</p></td>
                <td align="right" width="62%">
                  <div style="float: right;">
                <img src="http://www.parentfinder.com/video.jpg"> = Video, &nbsp;
                <img src="http://www.parentfinder.com/photo.jpg"> = Photo, &nbsp;
                <img src="http://www.parentfinder.com/bx_blogs.png"> = Blog
                 </div>
                </td>
                </tr>
            </table>
            </div>
            <div style="clear: both;display: block;margin: 0px;overflow: hidden;padding: 5px;font-family:verdana;">
            <div style="float: right; padding-right: 20px;">' . $page . '</div><div style="float: left; width: 100%;">' . $membersList . '</div>

            <div style="text-align:right;float:left;font-family:verdana;font-size:10px;float: right;">
            <a style="color:' . $badgewidgetConf['TextColor'] . ';text-decoration:none;"
            href="' . $groupFansUrl . '" target="TOP">' . $num_members . ' ' . _t("_gkc_bw_fans") . '</a></div>
            </div></div>
        ';


            if (strlen($badgewidgetConf['FooterTextGroupCreator']) > 0) {
                $code .= '<div style="text-align:right;padding: 4px 0px 0px 0px;font-size:10px;font-family: verdana;font-weight: none;">
            ' . $badgewidgetConf['FooterTextGroupCreator'] . '</div>';
            }

            $code .= '</div>';
            return $code;
        }
    }
}

function displayGroupCreatorBadgesingle($GID = 0, $MID = 0, $width, $logid = 0) {
	$data = array();
	$memberLogged = $logid;
	$sQuerySQL = mysql_query("
			SELECT
                `Profiles`.`ID`,
				`Profiles`.`AdoptionAgency`,
				`Profiles`.`NickName`,
				`Profiles`.`Sex`,
				`Profiles`.`FirstName`,
				`Profiles`.`Avatar`,
				`Profiles`.`DearBirthParent`,
				`Profiles`.`Couple`
			FROM `Profiles`
			WHERE
				`Profiles`.`ID` = '$memberLogged' AND `Profiles`.`ProfileType` = '2' AND `Profiles`.`Status` = 'Active'

		;");

	while ($members = mysql_fetch_assoc($sQuerySQL)) {
		//print_r($membres);
		$membersList .= draw_profile4($members);

	}
	return $membersList;
}
//function displayGroupCreatorBadgesingle($GID = 0, $MID = 0, $width) {
//    global $site;
//    global $badgewidgetConf;
//    $member = getProfileInfo($MID);
//    $memberLogged = getLoggedId();
//
//
//    //$groupUrl = $badgewidgetConf['GroupUrl'].$row['URI'];
//    //$groupFansUrl = $badgewidgetConf['GroupFansUrl'].$row['URI'];
//
//
//    $sQuerySQL = mysql_query("
//			SELECT
//                `Profiles`.`ID`,
//				`Profiles`.`AdoptionAgency`,
//				`Profiles`.`NickName`,
//				`Profiles`.`Sex`,
//				`Profiles`.`FirstName`,
//				`Profiles`.`Avatar`,
//				`Profiles`.`DearBirthParent`,
//				`Profiles`.`Couple`
//			FROM `Profiles`
//			WHERE
//				`Profiles`.`ID` = '$memberLogged' AND `Profiles`.`ProfileType` = '2' AND `Profiles`.`Status` = 'Active'
//
//
//		;");
//
//    bx_import('BxTemplSearchProfile');
//    $oSearchProfile = new BxTemplSearchProfile();
//    $membersList = '
//        <script type="text/javascript">
//        $(document).ready(function() {
//         $("div.hided").hide();
//         $("div#d0").show();
//         $("div#d0").removeClass("hide");
//         $("a#0").css({"font-weight":"bold", "font-size":"15px"});
//
//          $("a.d").click(function() {
//              var target = $(this).attr("id");
//              $("div.hided").hide();
//              $("div#d"+target).show();
//              $("div#d"+target).removeClass("hide");
//              $("a.d").css({"font-weight":"normal", "font-size":"12px"});
//              $("a#"+target).css({"font-weight":"bold", "font-size":"15px"});
//             });
//            });
//         </script>
//         <style>
//         a, a:link, a:visited, a:active {
//    cursor: pointer;
//    text-decoration: none;
//          }
//         </style>
//        <table width="100%" border="0" cellspacing="0" cellpadding="0">
//		<tr><td>';
//
//    while ($members = mysql_fetch_assoc($sQuerySQL)) {
//
//        //$membersList .= $oSearchProfile->displaySearchUnit($members);
//        $membersList .= draw_profile4($members);
//
//        //  if($num%5 == 4){ $membersList .= '</div>'; }
//        /*
//          $membersList .= '<td style="width:67px; vertical-align:middle; text-align:center;padding-bottom:10px;">'.
//          displayUserIcon( $members['ID'], 'none' ).'<font style="font-size:10px;color:'.$badgewidgetConf['TextColor'].';">'.limitStringLength($members['NickName'],10).'</font></td>';
//          if($num==5)
//          {
//          $membersList .= '</tr><tr>';
//          $num=0;
//          } */
//    }
//    $membersList .= '</td></tr>
//		</table>';
//
//
//
//    $num_members = mysql_num_rows($sQuerySQL);
//    ;
//    $become_fan = '';
//
//
//
//    $code = '
//		<link href="http://www.parentfinder.com/badge/css/badge_style.css" rel="stylesheet" type="text/css" />
//<div style="width: ' . $width . '%;">
//			<!--div style="background: ' . $badgewidgetConf['TitleBGColor'] . ';padding: 3px;">
//			<font style="color:' . $badgewidgetConf['TitleColor'] . ';font-weight:bold;font-size:16px; font-family:' . $badgewidgetConf['TitleFontFamily'] . ';">' . $site['title'] . '</font>
//			</div-->
//			<div style="background: ' . $badgewidgetConf['BoxBGColor'] . ';display: block;border-right: 0px solid ' . $badgewidgetConf['BoxBorderColor'] . ';border-bottom: 0px solid ' . $badgewidgetConf['BoxBorderColor'] . ';border-left: 0px solid ' . $badgewidgetConf['BoxBorderColor'] . ';margin: 0px;padding: 0px 0px 5px 0px;">
//			<div style="background: ' . $badgewidgetConf['BoxBGColor'] . ';display: block;padding: 5px;">
//			</div>
//			<div style="clear: both;display: block;margin: 0px;overflow: hidden;padding: 5px;font-family:verdana;">
//             <div>' . $page . '</div> <div>' . $membersList . '</div>
//
//
//			</div></div>
//		';
//
//    if (strlen($badgewidgetConf['FooterTextGroupCreator']) > 0) {
//        $code .= '<div style="text-align:right;padding: 4px 0px 0px 0px;font-size:10px;font-family: verdana;font-weight: none;">
//			' . $badgewidgetConf['FooterTextGroupCreator'] . '</div>';
//    }
//
//    $code .= '</div>';
//    return $code;
//}

function displayGroupCreatorBadgesa($GID = 0, $MID = 0, $width) {
    global $site;
    global $badgewidgetConf;
    $member = getProfileInfo($MID);
    $memberLogged = getLoggedId();

    if (isGroupCreator($member['ID'], $GID)) {
        $result = mysql_query("
                SELECT `bx_groups_main`.`title` AS 'Name',`bx_groups_main`.`id` AS 'ID',`bx_groups_main`.`uri` AS 'URI',
                `bx_groups_main`.`thumb` AS 'thumb',`bx_groups_main`.`fans_count` AS 'fans_count',`bx_groups_main`.`allow_view_fans_to` AS 'allow_view_fans_to'
                FROM `bx_groups_main`
                WHERE
                `bx_groups_main`.`status` = 'approved' AND
                `bx_groups_main`.`ID` = " . $GID . "
            ") or
                die('Error while fetching data. Reloading the page might solve the issue. <!-- ' . mysql_error() . ' -->');

        $row = mysql_fetch_array($result);

        if ($row) {

            if ($row['thumb'] != 0) {
                $result = mysql_query("SELECT `bx_photos_main`.`Hash` AS 'Hash',`bx_photos_main`.`Ext` AS 'Ext'
                            FROM `bx_photos_main` WHERE `bx_photos_main`.`ID` = " . $row['thumb'] . " LIMIT 1");
                $grpimg = mysql_fetch_array($result);

                $groupImageUrl = $site['url'] . 'm/photos/get_image/thumb/' . $grpimg['Hash'] . '.' . $grpimg['Ext'];
            } else {
                $groupImageUrl = $site['url'] . 'badgewidgets/images/group_thumb.jpg';
            }

            $memberIconUrl = "{$site['url']}badgewidgets/images/member.png";
            //$groupUrl = $badgewidgetConf['GroupUrl'].$row['URI'];
            //$groupFansUrl = $badgewidgetConf['GroupFansUrl'].$row['URI'];

            if ($row['allow_view_fans_to'] > 3 && !$memberLogged) {
                $membersList = '<div style="text-align:center;color:' . $badgewidgetConf['TextColor'] . ';font-size:11px;padding:10px;">' . _t("_gkc_bw_Fans visible only to members") . '</div>';
            } else {
                $sQuerySQL = mysql_query("
            SELECT
                `Profiles`.`ID`,
                `Profiles`.`NickName`,
                `Profiles`.`AdoptionAgency`,
                `Profiles`.`Sex`,
                `Profiles`.`FirstName`,
                `Profiles`.`Avatar`,
                `Profiles`.`DearBirthParent`,
                `Profiles`.`Couple`
            FROM `Profiles`
            WHERE
                 `Profiles`.`AdoptionAgency` = '$GID' AND `Profiles`.`ProfileType` = '2' AND `Profiles`.`Status` = 'Active'
        ;");

                bx_import('BxTemplSearchProfile');
                $oSearchProfile = new BxTemplSearchProfile();
                $membersList = '
         <script type="text/javascript">
        $(document).ready(function() {
         $("div.badsd").hide();
         $("div#a0").show();
         $("div#a0").css("display","block");
         $("div#a0").removeClass("hide");
         $("a#0").css({"font-weight":"bold", "font-size":"15px"});

          $("a.b").click(function() {
              var target = $(this).attr("id");
              $("div.badsd").hide();
              $("div#a"+target).show();
              $("div#a"+target).removeClass("hide");
              $("a.b").css({"font-weight":"normal", "font-size":"12px"});
              $("a#"+target).css({"font-weight":"bold", "font-size":"15px"});
             });
            });
         </script>
         <style>
         a, a:link, a:visited, a:active {
    cursor: pointer;
    text-decoration: none;
          }
         </style>
         <table border="0" cellspacing="0" cellpadding="0">
        <tr><td>';
                $num = 0;
                $i = 0;
                $page = '|';
                while ($members = mysql_fetch_assoc($sQuerySQL)) {
                    if ($num == 0) {
                        $display == "display=block";
                    } else {
                        $display == "";
                    }
                    if ($num % 10 == 0) {

                        $membersList .='<div class="badsd" id="a' . $num . '" ' . $display . '>';
                        $i++;
                        $myid = $num;
                        $page .= '<a class="b" id="' . $myid . '"> ' . $i . '</a> |';
                    }
                    $membersList .='<div style="width:49%; float:left;">';
                    //$membersList .= $oSearchProfile->displaySearchUnit($members);
                    $membersList .= draw_profile4($members);

                    $membersList .='</div>';

                    if ($num % 10 == 9) {
                        $membersList .= '</div>';
                    }

                    /*
                      $membersList .= '<td style="width:67px; vertical-align:middle; text-align:center;padding-bottom:10px;">'.
                      displayUserIcon( $members['ID'], 'none' ).'<font style="font-size:10px;color:'.$badgewidgetConf['TextColor'].';">'.limitStringLength($members['NickName'],10).'</font></td>';
                      if($num==5)
                      {
                      $membersList .= '</tr><tr>';
                      $num=0;
                      } */
                    $num++;
                }
                $membersList .= '</td></tr>
        </table>';
            }

            $num_members = mysql_num_rows($sQuerySQL);
            $become_fan = '';

            if ($memberLogged) {
                if (isGroupMember($memberLogged, $GID)) {
                    $become_fan = '<br/><br/>
                <font style="text-align:center;color:' . $badgewidgetConf['TextColor'] . ';font-size:11px;">' . _t("_gkc_bw_You are a fan") . '</font>';
                } else {
                    $become_fan = '
                <br/><br/>
                <a href="" target="TOP"
                style="color:' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 11px;text-decoration: none;">
                <img src="' . $site['url'] . 'badgewidgets/images/member.png" alt="" border="0" height="12" align="absmiddle"/> ' . _t("_gkc_bw_Become Fan") . '</a>';
                }
            }


            $code = '
        <link href="http://www.parentfinder.com/badge/css/badge_style.css" rel="stylesheet" type="text/css" />
<div style="width: ' . $width . '%;">
            <!--div style="background: ' . $badgewidgetConf['TitleBGColor'] . ';padding: 3px;">
            <font style="color:' . $badgewidgetConf['TitleColor'] . ';font-weight:bold;font-size:16px; font-family:' . $badgewidgetConf['TitleFontFamily'] . ';">' . $site['title'] . '</font>
            </div-->
            <div style="background: ' . $badgewidgetConf['BoxBGColor'] . ';display: block;border-right: 0px solid ' . $badgewidgetConf['BoxBorderColor'] . ';border-bottom: 0px solid ' . $badgewidgetConf['BoxBorderColor'] . ';border-left: 0px solid ' . $badgewidgetConf['BoxBorderColor'] . ';margin: 0px;padding: 0px 0px 5px 0px;">
            <div style="background: ' . $badgewidgetConf['BoxBGColor'] . ';display: block;padding: 5px;">
            <table cellspacing="0" cellpadding="0" border="0" style="margin:0px; padding:0px;">
                <tr>
                <td valign="top" width="45px"><a href="' . $groupUrl . '" title="' . htmlspecialchars($row['Name']) . '" target="TOP"><img src="' . $groupImageUrl . '" alt="' . htmlspecialchars($row['Name']) . '" border="0" style="width:45px;height:45px;padding:1px;border:1px solid #cccccc;"/></a></td>
                <td valign="middle"><p style="color: ' . $badgewidgetConf['TextColor'] . ';font-family: verdana;font-size: 11px;margin: 0px 0px 0px 0px;padding: 0px 8px 0px 8px;">
                <a href="' . $groupUrl . '" title="' . htmlspecialchars($row['Name']) . '" target="TOP" style="color: ' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 14px;font-weight: bold;margin: 0px;padding: 0px 0px 0px 0px;text-decoration: none;">' . htmlspecialchars($row['Name']) . '</a> ' . _t("_gkc_bw_on") . ' <!--a href="' . $site['url'] . '" style="color: ' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 11px;font-weight: normal;text-decoration: none;" target="TOP">' . $site['title'] . '</a-->
                ' . $become_fan . '</p></td>
                <td align="right" width="62%">
                  <div style="float: right;">
                <img src="http://www.parentfinder.com/video.jpg"> = Video, &nbsp;
                <img src="http://www.parentfinder.com/photo.jpg"> = Photo, &nbsp;
                <img src="http://www.parentfinder.com/bx_blogs.png"> = Blog
                 </div>
                </td>
                </tr>
            </table>
            </div>
            <div style="clear: both;display: block;margin: 0px;overflow: hidden;padding: 5px;font-family:verdana;">
            <div style="float: right; padding-right: 20px;">' . $page . '</div><div style="float: left; width: 100%;">' . $membersList . '</div>

            <div style="text-align:right;float:left;font-family:verdana;font-size:10px;float: right;">
            <a style="color:' . $badgewidgetConf['TextColor'] . ';text-decoration:none;"
            href="' . $groupFansUrl . '" target="TOP">' . $num_members . ' ' . _t("_gkc_bw_fans") . '</a></div>
            </div></div>
        ';


            if (strlen($badgewidgetConf['FooterTextGroupCreator']) > 0) {
                $code .= '<div style="text-align:right;padding: 4px 0px 0px 0px;font-size:10px;font-family: verdana;font-weight: none;">
            ' . $badgewidgetConf['FooterTextGroupCreator'] . '</div>';
            }

            $code .= '</div>';
            return $code;
        }
    }
}

function displayGroupCreatorBadgeni($GID = 0, $MID = 0, $width) {
    global $site;
    global $badgewidgetConf;
    $member = getProfileInfo($MID);
    $memberLogged = getLoggedId();

    if (isGroupCreator($member['ID'], $GID)) {
        $result = mysql_query("
                SELECT `bx_groups_main`.`title` AS 'Name',`bx_groups_main`.`id` AS 'ID',`bx_groups_main`.`uri` AS 'URI',
                `bx_groups_main`.`thumb` AS 'thumb',`bx_groups_main`.`fans_count` AS 'fans_count',`bx_groups_main`.`allow_view_fans_to` AS 'allow_view_fans_to'
                FROM `bx_groups_main`
                WHERE
                `bx_groups_main`.`status` = 'approved' AND
                `bx_groups_main`.`ID` = " . $GID . "
            ") or
                die('Error while fetching data. Reloading the page might solve the issue. <!-- ' . mysql_error() . ' -->');

        $row = mysql_fetch_array($result);

        if ($row) {

            if ($row['thumb'] != 0) {
                $result = mysql_query("SELECT `bx_photos_main`.`Hash` AS 'Hash',`bx_photos_main`.`Ext` AS 'Ext'
                            FROM `bx_photos_main` WHERE `bx_photos_main`.`ID` = " . $row['thumb'] . " LIMIT 1");
                $grpimg = mysql_fetch_array($result);

                $groupImageUrl = $site['url'] . 'm/photos/get_image/thumb/' . $grpimg['Hash'] . '.' . $grpimg['Ext'];
            } else {
                $groupImageUrl = $site['url'] . 'badgewidgets/images/group_thumb.jpg';
            }

            $memberIconUrl = "{$site['url']}badgewidgets/images/member.png";
            //$groupUrl = $badgewidgetConf['GroupUrl'].$row['URI'];
            //$groupFansUrl = $badgewidgetConf['GroupFansUrl'].$row['URI'];

            if ($row['allow_view_fans_to'] > 3 && !$memberLogged) {
                $membersList = '<div style="text-align:center;color:' . $badgewidgetConf['TextColor'] . ';font-size:11px;padding:10px;">' . _t("_gkc_bw_Fans visible only to members") . '</div>';
            } else {
                $sQuerySQL = mysql_query("
            SELECT
                `Profiles`.`ID`,
                `Profiles`.`NickName`,
                `Profiles`.`AdoptionAgency`,
                `Profiles`.`Sex`,
                `Profiles`.`FirstName`,
                `Profiles`.`Avatar`,
                `Profiles`.`DescriptionMe`,
                `Profiles`.`Couple`
            FROM `Profiles`
            WHERE
                 `Profiles`.`AdoptionAgency` = '$GID' AND `Profiles`.`ProfileType` = '2' AND `Profiles`.`Status` = 'Active'
        ;");

                bx_import('BxTemplSearchProfile');
                $oSearchProfile = new BxTemplSearchProfile();
                $membersList = '
         <script type="text/javascript">
        $(document).ready(function() {
         $("div.badsd").hide();
         $("div#a0").show();
         $("div#a0").css("display","block");
         $("div#a0").removeClass("hide");
         $("a#0").css({"font-weight":"bold", "font-size":"15px"});

          $("a.b").click(function() {
              var target = $(this).attr("id");
              $("div.badsd").hide();
              $("div#a"+target).show();
              $("div#a"+target).removeClass("hide");
              $("a.b").css({"font-weight":"normal", "font-size":"12px"});
              $("a#"+target).css({"font-weight":"bold", "font-size":"15px"});
             });
            });
         </script>
         <style>
         a, a:link, a:visited, a:active {
    cursor: pointer;
    text-decoration: none;
          }
         </style>
         <table border="0" cellspacing="0" cellpadding="0">
        <tr><td>';
                $num = 0;
                $i = 0;
                $page = '|';
                while ($members = mysql_fetch_assoc($sQuerySQL)) {
                    if ($num == 0) {
                        $display == "display=block";
                    } else {
                        $display == "";
                    }
                    if ($num % 10 == 0) {

                        $membersList .='<div class="badsd" id="a' . $num . '" ' . $display . '>';
                        $i++;
                        $myid = $num;
                        $page .= '<a class="b" id="' . $myid . '"> ' . $i . '</a> |';
                    }
                    $membersList .='<div style="width:49%; float:left;">';
                    //$membersList .= $oSearchProfile->displaySearchUnit($members);
                    $membersList .= draw_profile($members);

                    $membersList .='</div>';

                    if ($num % 10 == 9) {
                        $membersList .= '</div>';
                    }

                    /*
                      $membersList .= '<td style="width:67px; vertical-align:middle; text-align:center;padding-bottom:10px;">'.
                      displayUserIcon( $members['ID'], 'none' ).'<font style="font-size:10px;color:'.$badgewidgetConf['TextColor'].';">'.limitStringLength($members['NickName'],10).'</font></td>';
                      if($num==5)
                      {
                      $membersList .= '</tr><tr>';
                      $num=0;
                      } */
                    $num++;
                }
                $membersList .= '</td></tr>
        </table>';
            }

            $num_members = mysql_num_rows($sQuerySQL);
            $become_fan = '';

            if ($memberLogged) {
                if (isGroupMember($memberLogged, $GID)) {
                    $become_fan = '<br/><br/>
                <font style="text-align:center;color:' . $badgewidgetConf['TextColor'] . ';font-size:11px;">' . _t("_gkc_bw_You are a fan") . '</font>';
                } else {
                    $become_fan = '
                <br/><br/>
                <a href="" target="TOP"
                style="color:' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 11px;text-decoration: none;">
                <img src="' . $site['url'] . 'badgewidgets/images/member.png" alt="" border="0" height="12" align="absmiddle"/> ' . _t("_gkc_bw_Become Fan") . '</a>';
                }
            }


            $code = '
        <link href="http://www.parentfinder.com/badge/css/badge_style.css" rel="stylesheet" type="text/css" />
<div style="width: ' . $width . '%;">
            <!--div style="background: ' . $badgewidgetConf['TitleBGColor'] . ';padding: 3px;">
            <font style="color:' . $badgewidgetConf['TitleColor'] . ';font-weight:bold;font-size:16px; font-family:' . $badgewidgetConf['TitleFontFamily'] . ';">' . $site['title'] . '</font>
            </div-->
            <div style="background: ' . $badgewidgetConf['BoxBGColor'] . ';display: block;border-right: 0px solid ' . $badgewidgetConf['BoxBorderColor'] . ';border-bottom: 0px solid ' . $badgewidgetConf['BoxBorderColor'] . ';border-left: 0px solid ' . $badgewidgetConf['BoxBorderColor'] . ';margin: 0px;padding: 0px 0px 5px 0px;">
            <div style="background: ' . $badgewidgetConf['BoxBGColor'] . ';display: block;padding: 5px;">
            <table cellspacing="0" cellpadding="0" border="0" style="margin:0px; padding:0px;">
                <tr>
                <td valign="top" width="45px"><a href="' . $groupUrl . '" title="' . htmlspecialchars($row['Name']) . '" target="TOP"><img src="' . $groupImageUrl . '" alt="' . htmlspecialchars($row['Name']) . '" border="0" style="width:45px;height:45px;padding:1px;border:1px solid #cccccc;"/></a></td>
                <td valign="middle"><p style="color: ' . $badgewidgetConf['TextColor'] . ';font-family: verdana;font-size: 11px;margin: 0px 0px 0px 0px;padding: 0px 8px 0px 8px;">
                <a href="' . $groupUrl . '" title="' . htmlspecialchars($row['Name']) . '" target="TOP" style="color: ' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 14px;font-weight: bold;margin: 0px;padding: 0px 0px 0px 0px;text-decoration: none;">' . htmlspecialchars($row['Name']) . '</a> ' . _t("_gkc_bw_on") . ' <!--a href="' . $site['url'] . '" style="color: ' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 11px;font-weight: normal;text-decoration: none;" target="TOP">' . $site['title'] . '</a-->
                ' . $become_fan . '</p></td>
                <td align="right" width="62%">
                  <div style="float: right;">
                <img src="http://www.parentfinder.com/video.jpg"> = Video, &nbsp;
                <img src="http://www.parentfinder.com/photo.jpg"> = Photo, &nbsp;
                <img src="http://www.parentfinder.com/bx_blogs.png"> = Blog
                 </div>
                </td>
                </tr>
            </table>
            </div>
            <div style="clear: both;display: block;margin: 0px;overflow: hidden;padding: 5px;font-family:verdana;">
            <div style="float: right; padding-right: 20px;">' . $page . '</div><div style="float: left; width: 100%;">' . $membersList . '</div>

            <div style="text-align:right;float:left;font-family:verdana;font-size:10px;float: right;">
            <a style="color:' . $badgewidgetConf['TextColor'] . ';text-decoration:none;"
            href="' . $groupFansUrl . '" target="TOP">' . $num_members . ' ' . _t("_gkc_bw_fans") . '</a></div>
            </div></div>
        ';


            if (strlen($badgewidgetConf['FooterTextGroupCreator']) > 0) {
                $code .= '<div style="text-align:right;padding: 4px 0px 0px 0px;font-size:10px;font-family: verdana;font-weight: none;">
            ' . $badgewidgetConf['FooterTextGroupCreator'] . '</div>';
            }

            $code .= '</div>';
            return $code;
        }
    }
}

function displayGroupCreatorBadgerd($GID = 0, $MID = 0, $width) {
    global $site;
    global $badgewidgetConf;
    $member = getProfileInfo($MID);
    $memberLogged = getLoggedId();

    if (isGroupCreator($member['ID'], $GID)) {
        $result = mysql_query("
                SELECT `bx_groups_main`.`title` AS 'Name',`bx_groups_main`.`id` AS 'ID',`bx_groups_main`.`uri` AS 'URI',
                `bx_groups_main`.`thumb` AS 'thumb',`bx_groups_main`.`fans_count` AS 'fans_count',`bx_groups_main`.`allow_view_fans_to` AS 'allow_view_fans_to'
                FROM `bx_groups_main`
                WHERE
                `bx_groups_main`.`status` = 'approved' AND
                `bx_groups_main`.`ID` = " . $GID . "
            ") or
                die('Error while fetching data. Reloading the page might solve the issue. <!-- ' . mysql_error() . ' -->');

        $row = mysql_fetch_array($result);

        if ($row) {

            if ($row['thumb'] != 0) {
                $result = mysql_query("SELECT `bx_photos_main`.`Hash` AS 'Hash',`bx_photos_main`.`Ext` AS 'Ext'
                            FROM `bx_photos_main` WHERE `bx_photos_main`.`ID` = " . $row['thumb'] . " LIMIT 1");
                $grpimg = mysql_fetch_array($result);

                $groupImageUrl = $site['url'] . 'm/photos/get_image/thumb/' . $grpimg['Hash'] . '.' . $grpimg['Ext'];
            } else {
                $groupImageUrl = $site['url'] . 'badgewidgets/images/group_thumb.jpg';
            }

            $memberIconUrl = "{$site['url']}badgewidgets/images/member.png";
            //$groupUrl = $badgewidgetConf['GroupUrl'].$row['URI'];
            $groupUrl = getProfileLinks($memberLogged);
            //$groupFansUrl = $badgewidgetConf['GroupFansUrl'].$row['URI'];

            if ($row['allow_view_fans_to'] > 3 && !$memberLogged) {
                $membersList = '<div style="text-align:center;color:' . $badgewidgetConf['TextColor'] . ';font-size:11px;padding:10px;">' . _t("_gkc_bw_Fans visible only to members") . '</div>';
            } else {
                $sQuerySQL = mysql_query("
            SELECT
                `Profiles`.`ID`,
                `Profiles`.`NickName`,
                `Profiles`.`AdoptionAgency`,
                `Profiles`.`Sex`,
                `Profiles`.`FirstName`,
                `Profiles`.`Avatar`,
                `Profiles`.`DearBirthParent`,
                `Profiles`.`Couple`
            FROM `Profiles`
            WHERE
                 `Profiles`.`AdoptionAgency` = '$GID' AND `Profiles`.`ProfileType` = '2' AND `Profiles`.`Status` = 'Active'
                ORDER BY RAND()
        ;");

                bx_import('BxTemplSearchProfile');
                $oSearchProfile = new BxTemplSearchProfile();
                $membersList = '
        <script type="text/javascript">
        $(document).ready(function() {
         $("div.hiderd").hide();
         $("div#rd0").show();
         $("div#rd0").removeClass("hide");
         $("a#0").css({"font-weight":"bold", "font-size":"15px"});

          $("a.rd").click(function() {
              var target = $(this).attr("id");
              $("div.hiderd").hide();
              $("div#rd"+target).show();
              $("div#rd"+target).removeClass("hide");
              $("a.rd").css({"font-weight":"normal", "font-size":"12px"});
              $("a#"+target).css({"font-weight":"bold", "font-size":"15px"});
             });
            });
         </script>
         <style>
         a, a:link, a:visited, a:active {
    cursor: pointer;
    text-decoration: none;
          }
         </style>
         <table border="0" cellspacing="0" cellpadding="0">
        <tr><td>';
                $num = 0;
                $i = 0;
                $page = '|';
                while ($members = mysql_fetch_assoc($sQuerySQL)) {
                    if ($num % 10 == 0) {

                        $membersList .='<div class="hiderd" style="display: none;" id="rd' . $num . '">';
                        $i++;
                        $myid = $num;
                        $page .= '<a class="rd" id="' . $myid . '"> ' . $i . '</a> |';
                    }
                    $membersList .='<div style="width:49%; float:left;">';
                    //$membersList .= $oSearchProfile->displaySearchUnit($members);
                    $membersList .= draw_profile2($members);

                    $membersList .='</div>';

                    if ($num % 10 == 9) {
                        $membersList .= '</div>';
                    }

                    /*
                      $membersList .= '<td style="width:67px; vertical-align:middle; text-align:center;padding-bottom:10px;">'.
                      displayUserIcon( $members['ID'], 'none' ).'<font style="font-size:10px;color:'.$badgewidgetConf['TextColor'].';">'.limitStringLength($members['NickName'],10).'</font></td>';
                      if($num==5)
                      {
                      $membersList .= '</tr><tr>';
                      $num=0;
                      } */
                    $num++;
                }
                $membersList .= '</td></tr>
        </table>';
            }

            $num_members = mysql_num_rows($sQuerySQL);
            $become_fan = '';

            if ($memberLogged) {
                if (isGroupMember($memberLogged, $GID)) {
                    $become_fan = '<br/><br/>
                <font style="text-align:center;color:' . $badgewidgetConf['TextColor'] . ';font-size:11px;">' . _t("_gkc_bw_You are a fan") . '</font>';
                } else {
                    $become_fan = '
                <br/><br/>
                <a href="" target="TOP"
                style="color:' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 11px;text-decoration: none;">
                <img src="' . $site['url'] . 'badgewidgets/images/member.png" alt="" border="0" height="12" align="absmiddle"/> ' . _t("_gkc_bw_Become Fan") . '</a>';
                }
            }

            $code = '
        <link href="http://www.parentfinder.com/badge/css/badge_style.css" rel="stylesheet" type="text/css" />
<div style="width: ' . $width . '%;">
            <!--div style="background: ' . $badgewidgetConf['TitleBGColor'] . ';padding: 3px;">
            <font style="color:' . $badgewidgetConf['TitleColor'] . ';font-weight:bold;font-size:16px; font-family:' . $badgewidgetConf['TitleFontFamily'] . ';">' . $site['title'] . '</font>
            </div-->
            <div style="background: ' . $badgewidgetConf['BoxBGColor'] . ';display: block;border-right: 0px solid ' . $badgewidgetConf['BoxBorderColor'] . ';border-bottom: 0px solid ' . $badgewidgetConf['BoxBorderColor'] . ';border-left: 0px solid ' . $badgewidgetConf['BoxBorderColor'] . ';margin: 0px;padding: 0px 0px 5px 0px;">
            <div style="background: ' . $badgewidgetConf['BoxBGColor'] . ';display: block;padding: 5px;">
            <table cellspacing="0" cellpadding="0" border="0" style="margin:0px; padding:0px;">
                <tr>
                <td valign="top" width="45px"><a href="' . $groupUrl . '" title="' . htmlspecialchars($row['Name']) . '" target="TOP"><img src="' . $groupImageUrl . '" alt="' . htmlspecialchars($row['Name']) . '" border="0" style="width:45px;height:45px;padding:1px;border:1px solid #cccccc;"/></a></td>
                <td valign="middle"><p style="color: ' . $badgewidgetConf['TextColor'] . ';font-family: verdana;font-size: 11px;margin: 0px 0px 0px 0px;padding: 0px 8px 0px 8px;">
                <a href="' . $groupUrl . '" title="' . htmlspecialchars($row['Name']) . '" target="TOP" style="color: ' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 14px;font-weight: bold;margin: 0px;padding: 0px 0px 0px 0px;text-decoration: none;">' . htmlspecialchars($row['Name']) . '</a> ' . _t("_gkc_bw_on") . ' <!--a href="' . $site['url'] . '" style="color: ' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 11px;font-weight: normal;text-decoration: none;" target="TOP">' . $site['title'] . '</a-->
                ' . $become_fan . '</p></td>
                <td align="right" width="62%">
                  <div style="float: right;">
                <img src="http://www.parentfinder.com/video.jpg"> = Video, &nbsp;
                <img src="http://www.parentfinder.com/photo.jpg"> = Photo, &nbsp;
                <img src="http://www.parentfinder.com/bx_blogs.png"> = Blog
                 </div>
                </td>
                </tr>
            </table>
            </div>
            <div style="clear: both;display: block;margin: 0px;overflow: hidden;padding: 5px;font-family:verdana;">
            <div style="float: right; padding-right: 20px;">' . $page . '</div><div style="float: left;width: 100%;">' . $membersList . '</div>

            <div style="text-align:right;float:left;font-family:verdana;font-size:10px;">
            <a style="color:' . $badgewidgetConf['TextColor'] . ';text-decoration:none;"
            href="' . $groupFansUrl . '" target="TOP">' . $num_members . ' ' . _t("_gkc_bw_fans") . '</a></div>
            </div></div>
        ';


            if (strlen($badgewidgetConf['FooterTextGroupCreator']) > 0) {
                $code .= '<div style="text-align:right;padding: 4px 0px 0px 0px;font-size:10px;font-family: verdana;font-weight: none;">
            ' . $badgewidgetConf['FooterTextGroupCreator'] . '</div>';
            }

            $code .= '</div>';
            return $code;
        }
    }
}

function displayGroupCreatorBadgeal($GID = 0, $MID = 0, $width) {
    global $site;
    global $badgewidgetConf;
    $member = getProfileInfo($MID);
    $memberLogged = getLoggedId();

    if (isGroupCreator($member['ID'], $GID)) {
        $result = mysql_query("
                SELECT `bx_groups_main`.`title` AS 'Name',`bx_groups_main`.`id` AS 'ID',`bx_groups_main`.`uri` AS 'URI',
                `bx_groups_main`.`thumb` AS 'thumb',`bx_groups_main`.`fans_count` AS 'fans_count',`bx_groups_main`.`allow_view_fans_to` AS 'allow_view_fans_to'
                FROM `bx_groups_main`
                WHERE
                `bx_groups_main`.`status` = 'approved' AND
                `bx_groups_main`.`ID` = " . $GID . "
            ") or
                die('Error while fetching data. Reloading the page might solve the issue. <!-- ' . mysql_error() . ' -->');

        $row = mysql_fetch_array($result);

        if ($row) {

            if ($row['thumb'] != 0) {
                $result = mysql_query("SELECT `bx_photos_main`.`Hash` AS 'Hash',`bx_photos_main`.`Ext` AS 'Ext'
                            FROM `bx_photos_main` WHERE `bx_photos_main`.`ID` = " . $row['thumb'] . " LIMIT 1");
                $grpimg = mysql_fetch_array($result);

                $groupImageUrl = $site['url'] . 'm/photos/get_image/thumb/' . $grpimg['Hash'] . '.' . $grpimg['Ext'];
            } else {
                $groupImageUrl = $site['url'] . 'badgewidgets/images/group_thumb.jpg';
            }

            $memberIconUrl = "{$site['url']}badgewidgets/images/member.png";
            //$groupUrl = $badgewidgetConf['GroupUrl'].$row['URI'];
            $groupUrl = getProfileLinks($memberLogged);
            //$groupFansUrl = $badgewidgetConf['GroupFansUrl'].$row['URI'];

            if ($row['allow_view_fans_to'] > 3 && !$memberLogged) {
                $membersList = '<div style="text-align:center;color:' . $badgewidgetConf['TextColor'] . ';font-size:11px;padding:10px;">' . _t("_gkc_bw_Fans visible only to members") . '</div>';
            } else {
                $sQuerySQL = mysql_query("
            SELECT
                `Profiles`.`ID`,
                `Profiles`.`NickName`,
                `Profiles`.`AdoptionAgency`,
                `Profiles`.`Sex`,
                `Profiles`.`FirstName`,
                `Profiles`.`Avatar`,
                `Profiles`.`DearBirthParent`,
                `Profiles`.`Couple`
            FROM `Profiles`
            WHERE
                 `Profiles`.`AdoptionAgency` = '$GID' AND `Profiles`.`ProfileType` = '2' AND `Profiles`.`Status` = 'Active'
                ORDER BY `FirstName`
        ;");

                bx_import('BxTemplSearchProfile');
                $oSearchProfile = new BxTemplSearchProfile();
                $membersList = '
        <script type="text/javascript">
        $(document).ready(function() {
         $("div.hideb").hide();
         $("div#b0").show();
         $("div#b0").removeClass("hide");
         $("a#0").css({"font-weight":"bold", "font-size":"15px"});

          $("a.ba").click(function() {
              var target = $(this).attr("id");
              $("div.hideb").hide();
              $("div#b"+target).show();
              $("div#b"+target).removeClass("hide");
              $("a.ba").css({"font-weight":"normal", "font-size":"12px"});
              $("a#"+target).css({"font-weight":"bold", "font-size":"15px"});
             });
            });
         </script>
         <style>
         a{
         cursor: pointer;
         text-decoration: none;
          }
         a:active { font-weight: bold; }
         </style>
         <table border="0" cellspacing="0" cellpadding="0">
        <tr><td>';
                $num = 0;
                $i = 0;
                $page = '|';
                while ($members = mysql_fetch_assoc($sQuerySQL)) {
                    if ($num % 10 == 0) {

                        $membersList .='<div class="hideb" style="display: none;" id="b' . $num . '">';
                        $i++;
                        $myid = $num;
                        $page .= '<a class="ba" id="' . $myid . '"> ' . $i . '</a> |';
                    }
                    $membersList .='<div style="width:49%; float:left;">';
                    //$membersList .= $oSearchProfile->displaySearchUnit($members);
                    $membersList .= draw_profile2($members);

                    $membersList .='</div>';

                    if ($num % 10 == 9) {
                        $membersList .= '</div>';
                    }

                    /*
                      $membersList .= '<td style="width:67px; vertical-align:middle; text-align:center;padding-bottom:10px;">'.
                      displayUserIcon( $members['ID'], 'none' ).'<font style="font-size:10px;color:'.$badgewidgetConf['TextColor'].';">'.limitStringLength($members['NickName'],10).'</font></td>';
                      if($num==5)
                      {
                      $membersList .= '</tr><tr>';
                      $num=0;
                      } */
                    $num++;
                }
                $membersList .= '</td></tr>
        </table>';
            }

            $num_members = mysql_num_rows($sQuerySQL);
            $become_fan = '';

            if ($memberLogged) {
                if (isGroupMember($memberLogged, $GID)) {
                    $become_fan = '<br/><br/>
                <font style="text-align:center;color:' . $badgewidgetConf['TextColor'] . ';font-size:11px;">' . _t("_gkc_bw_You are a fan") . '</font>';
                } else {
                    $become_fan = '
                <br/><br/>
                <a href="" target="TOP"
                style="color:' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 11px;text-decoration: none;">
                <img src="' . $site['url'] . 'badgewidgets/images/member.png" alt="" border="0" height="12" align="absmiddle"/> ' . _t("_gkc_bw_Become Fan") . '</a>';
                }
            }

            $code = '
        <link href="http://www.parentfinder.com/badge/css/badge_style.css" rel="stylesheet" type="text/css" />
<div style="width: ' . $width . '%;">
            <!--div style="background: ' . $badgewidgetConf['TitleBGColor'] . ';padding: 3px;">
            <font style="color:' . $badgewidgetConf['TitleColor'] . ';font-weight:bold;font-size:16px; font-family:' . $badgewidgetConf['TitleFontFamily'] . ';">' . $site['title'] . '</font>
            </div-->
            <div style="background: ' . $badgewidgetConf['BoxBGColor'] . ';display: block;border-right: 0px solid ' . $badgewidgetConf['BoxBorderColor'] . ';border-bottom: 0px solid ' . $badgewidgetConf['BoxBorderColor'] . ';border-left: 0px solid ' . $badgewidgetConf['BoxBorderColor'] . ';margin: 0px;padding: 0px 0px 5px 0px;">
            <div style="background: ' . $badgewidgetConf['BoxBGColor'] . ';display: block;padding: 5px;">
            <table cellspacing="0" cellpadding="0" border="0" style="margin:0px; padding:0px;">
                <tr>
                <td valign="top" width="45px"><a href="' . $groupUrl . '" title="' . htmlspecialchars($row['Name']) . '" target="TOP"><img src="' . $groupImageUrl . '" alt="' . htmlspecialchars($row['Name']) . '" border="0" style="width:45px;height:45px;padding:1px;border:1px solid #cccccc;"/></a></td>
                <td valign="middle"><p style="color: ' . $badgewidgetConf['TextColor'] . ';font-family: verdana;font-size: 11px;margin: 0px 0px 0px 0px;padding: 0px 8px 0px 8px;">
                <a href="' . $groupUrl . '" title="' . htmlspecialchars($row['Name']) . '" target="TOP" style="color: ' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 14px;font-weight: bold;margin: 0px;padding: 0px 0px 0px 0px;text-decoration: none;">' . htmlspecialchars($row['Name']) . '</a> ' . _t("_gkc_bw_on") . ' <!--a href="' . $site['url'] . '" style="color: ' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 11px;font-weight: normal;text-decoration: none;" target="TOP">' . $site['title'] . '</a-->
                ' . $become_fan . '</p></td>
                <td align="right" width="62%">
                  <div style="float: right;">
                <img src="http://www.parentfinder.com/video.jpg"> = Video, &nbsp;
                <img src="http://www.parentfinder.com/photo.jpg"> = Photo, &nbsp;
                <img src="http://www.parentfinder.com/bx_blogs.png"> = Blog
                 </div>
                </td>
                </tr>
            </table>
            </div>
            <div style="clear: both;display: block;margin: 0px;overflow: hidden;padding: 5px;font-family:verdana;">
            <div style="float: right; padding-right: 20px;">' . $page . '</div><div style="float: left; width: 100%;">' . $membersList . '</div>

            <div style="text-align:right;font-family:verdana;float:left;font-size:10px;">
            <a style="color:' . $badgewidgetConf['TextColor'] . ';text-decoration:none;"
            href="' . $groupFansUrl . '" target="TOP">' . $num_members . ' ' . _t("_gkc_bw_fans") . '</a></div>
            </div></div>
        ';


            if (strlen($badgewidgetConf['FooterTextGroupCreator']) > 0) {
                $code .= '<div style="text-align:right;padding: 4px 0px 0px 0px;font-size:10px;font-family: verdana;font-weight: none;">
            ' . $badgewidgetConf['FooterTextGroupCreator'] . '</div>';
            }

            $code .= '</div>';
            return $code;
        }
    }
}

function displayGroupiframe($GID = 0, $MID = 0, $width) {
    global $site;
    global $badgewidgetConf;
    $member = getProfileInfo($MID);
    $memberLogged = getLoggedId();

    if (isGroupCreator($member['ID'], $GID)) {
        $result = mysql_query("
                SELECT `bx_groups_main`.`title` AS 'Name',`bx_groups_main`.`id` AS 'ID',`bx_groups_main`.`uri` AS 'URI',
                `bx_groups_main`.`thumb` AS 'thumb',`bx_groups_main`.`fans_count` AS 'fans_count',`bx_groups_main`.`allow_view_fans_to` AS 'allow_view_fans_to'
                FROM `bx_groups_main`
                WHERE
                `bx_groups_main`.`status` = 'approved' AND
                `bx_groups_main`.`ID` = " . $GID . "
            ") or
                die('Error while fetching data. Reloading the page might solve the issue. <!-- ' . mysql_error() . ' -->');

        $row = mysql_fetch_array($result);

        if ($row) {

            if ($row['thumb'] != 0) {
                $result = mysql_query("SELECT `bx_photos_main`.`Hash` AS 'Hash',`bx_photos_main`.`Ext` AS 'Ext'
                            FROM `bx_photos_main` WHERE `bx_photos_main`.`ID` = " . $row['thumb'] . " LIMIT 1");
                $grpimg = mysql_fetch_array($result);

                $groupImageUrl = $site['url'] . 'm/photos/get_image/thumb/' . $grpimg['Hash'] . '.' . $grpimg['Ext'];
            } else {
                $groupImageUrl = $site['url'] . 'badgewidgets/images/group_thumb.jpg';
            }

            $memberIconUrl = "{$site['url']}badgewidgets/images/member.png";
            //$groupUrl = $badgewidgetConf['GroupUrl'].$row['URI'];
            //$groupFansUrl = $badgewidgetConf['GroupFansUrl'].$row['URI'];

            if ($row['allow_view_fans_to'] > 3 && !$memberLogged) {
                $membersList = '<div style="text-align:center;color:' . $badgewidgetConf['TextColor'] . ';font-size:11px;padding:10px;">' . _t("_gkc_bw_Fans visible only to members") . '</div>';
            } else {
                $sQuerySQL = mysql_query("
            SELECT
                `Profiles`.`ID`,
                `Profiles`.`NickName`,
                `Profiles`.`AdoptionAgency`,
                `Profiles`.`Sex`,
                `Profiles`.`FirstName`,
                `Profiles`.`Avatar`,
                `Profiles`.`DearBirthParent`,
                `Profiles`.`Couple`
            FROM `Profiles`
            WHERE
                 `Profiles`.`AdoptionAgency` = '$GID' AND `Profiles`.`ProfileType` = '2' AND `Profiles`.`Status` = 'Active'
        ;");

                bx_import('BxTemplSearchProfile');
                $oSearchProfile = new BxTemplSearchProfile();
                $membersList = '<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.min.js" ></script>
         <script type="text/javascript">
        $(document).ready(function() {
         $("div.hideb").hide();
         $("div#b0").show();
          $("a#0").css({"font-weight":"bold", "font-size":"15px"});

          $("a.hideb").click(function() {
              var target = $(this).attr("id");
              $("div.hideb").hide();
              $("div#b"+target).show();
              $("a.hideb").css({"font-weight":"normal", "font-size":"12px"});
              $("a#"+target).css({"font-weight":"bold", "font-size":"15px"});
             });
            });
         </script>
         <style>
         a, a:link, a:visited, a:active {
    cursor: pointer;
    text-decoration: none;
          }
         </style>
         <table border="0" cellspacing="0" cellpadding="0">
        <tr><td>';
                $num = 0;
                $i = 0;
                $page = '|';
                while ($members = mysql_fetch_assoc($sQuerySQL)) {
                    if ($num % 10 == 0) {

                        $membersList .='<div class="hideb" style="display: block;" id="b' . $num . '">';
                        $i++;
                        $myid = $num;
                        $page .= '<a class="hideb" id="' . $myid . '"> ' . $i . '</a> |';
                    }
                    $membersList .='<div style="width:50%; float:left;">';
                    //$membersList .= $oSearchProfile->displaySearchUnit($members);
                    $membersList .= draw_profile2($members);

                    $membersList .='</div>';

                    if ($num % 10 == 9) {
                        $membersList .= '</div>';
                    }

                    /*
                      $membersList .= '<td style="width:67px; vertical-align:middle; text-align:center;padding-bottom:10px;">'.
                      displayUserIcon( $members['ID'], 'none' ).'<font style="font-size:10px;color:'.$badgewidgetConf['TextColor'].';">'.limitStringLength($members['NickName'],10).'</font></td>';
                      if($num==5)
                      {
                      $membersList .= '</tr><tr>';
                      $num=0;
                      } */
                    $num++;
                }
                $membersList .= '</td></tr>
        </table>';
            }

            $num_members = mysql_num_rows($sQuerySQL);
            $become_fan = '';

            if ($memberLogged) {
                if (isGroupMember($memberLogged, $GID)) {
                    $become_fan = '<br/><br/>
                <font style="text-align:center;color:' . $badgewidgetConf['TextColor'] . ';font-size:11px;">' . _t("_gkc_bw_You are a fan") . '</font>';
                } else {
                    $become_fan = '
                <br/><br/>
                <a href="" target="TOP"
                style="color:' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 11px;text-decoration: none;">
                <img src="' . $site['url'] . 'badgewidgets/images/member.png" alt="" border="0" height="12" align="absmiddle"/> ' . _t("_gkc_bw_Become Fan") . '</a>';
                }
            }


            $code = '
        <link href="http://www.parentfinder.com/badge/css/badge_style.css" rel="stylesheet" type="text/css" />
<div style="width: ' . $width . '%;">
            <!--div style="background: ' . $badgewidgetConf['TitleBGColor'] . ';padding: 3px;">
            <font style="color:' . $badgewidgetConf['TitleColor'] . ';font-weight:bold;font-size:16px; font-family:' . $badgewidgetConf['TitleFontFamily'] . ';">' . $site['title'] . '</font>
            </div-->
            <div style="background: ' . $badgewidgetConf['BoxBGColor'] . ';display: block;border-right: 0px solid ' . $badgewidgetConf['BoxBorderColor'] . ';border-bottom: 0px solid ' . $badgewidgetConf['BoxBorderColor'] . ';border-left: 0px solid ' . $badgewidgetConf['BoxBorderColor'] . ';margin: 0px;padding: 0px 0px 5px 0px;">
            <div style="background: ' . $badgewidgetConf['BoxBGColor'] . ';display: block;padding: 5px;">
            <table cellspacing="0" cellpadding="0" border="0" style="margin:0px; padding:0px;">
                <tr>
                <td valign="top" width="45px"><a href="' . $groupUrl . '" title="' . htmlspecialchars($row['Name']) . '" target="TOP"><img src="' . $groupImageUrl . '" alt="' . htmlspecialchars($row['Name']) . '" border="0" style="width:45px;height:45px;padding:1px;border:1px solid #cccccc;"/></a></td>
                <td valign="middle"><p style="color: ' . $badgewidgetConf['TextColor'] . ';font-family: verdana;font-size: 11px;margin: 0px 0px 0px 0px;padding: 0px 8px 0px 8px;">
                <a href="' . $groupUrl . '" title="' . htmlspecialchars($row['Name']) . '" target="TOP" style="color: ' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 14px;font-weight: bold;margin: 0px;padding: 0px 0px 0px 0px;text-decoration: none;">' . htmlspecialchars($row['Name']) . '</a> ' . _t("_gkc_bw_on") . ' <!--a href="' . $site['url'] . '" style="color: ' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 11px;font-weight: normal;text-decoration: none;" target="TOP">' . $site['title'] . '</a-->
                ' . $become_fan . '</p></td>
                <td align="right" width="62%">
                  <div style="float: right;">
                <img src="http://www.parentfinder.com/video.jpg"> = Video, &nbsp;
                <img src="http://www.parentfinder.com/photo.jpg"> = Photo, &nbsp;
                <img src="http://www.parentfinder.com/bx_blogs.png"> = Blog
                 </div>
                </td>
                </tr>
            </table>
            </div>
            <div style="clear: both;display: block;margin: 0px;overflow: hidden;padding: 5px;font-family:verdana;">
            <div style="float: right; padding-right: 20px;">' . $page . '</div><div style="float: left;">' . $membersList . '</div>

            <div style="text-align:right;float:left;font-family:verdana;font-size:10px;float: right;">
            <a style="color:' . $badgewidgetConf['TextColor'] . ';text-decoration:none;"
            href="' . $groupFansUrl . '" target="TOP">' . $num_members . ' ' . _t("_gkc_bw_fans") . '</a></div>
            </div></div>
        ';


            if (strlen($badgewidgetConf['FooterTextGroupCreator']) > 0) {
                $code .= '<div style="text-align:right;padding: 4px 0px 0px 0px;font-size:10px;font-family: verdana;font-weight: none;">
            ' . $badgewidgetConf['FooterTextGroupCreator'] . '</div>';
            }

            $code .= '</div>';
            return $code;
        }
    }
}

function displayProfilePhotosBadge($MID = 0, $limit = 6, $width, $height) {
    global $site;
    global $badgewidgetConf;
    $member = getProfileInfo($MID);
    $memberLogged = getLoggedId();

    if ($member['Status'] == 'Active') {

        $allow_view = "`sys_albums`.`AllowAlbumView` = '3'";

        $result = mysql_query("
                SELECT DISTINCT `bx_photos_main`.`Hash` AS 'Hash',`bx_photos_main`.`Ext` AS 'Ext',`bx_photos_main`.`Uri` AS 'Uri',
                `bx_photos_main`.`Title` AS 'Title'
                FROM `bx_photos_main`,  `sys_albums` ,  `sys_albums_objects` WHERE
                `bx_photos_main`.`Status` = 'approved' AND
                `bx_photos_main`.`Owner` = " . $member['ID'] . " AND
                `bx_photos_main`.`ID` = `sys_albums_objects`.`id_object` AND
                `sys_albums`.`ID` = `sys_albums_objects`.`id_album` AND
                `sys_albums`.`Owner` = " . $member['ID'] . " AND
                " . $allow_view . "
                ORDER BY RAND() LIMIT $limit
            ") or
                die('Error while fetching data. Reloading the page might solve the issue. <!-- ' . mysql_error() . ' -->');

        $photosList = '<table border="0" cellspacing="0" cellpadding="0">
        <tr>';
        $num = 1;
        while ($row = mysql_fetch_array($result)) {
            $photo_url = $site['url'] . 'm/photos/get_image/thumb/' . $row['Hash'] . '.' . $row['Ext'];
            $photoLink = $badgewidgetConf['UserPhotoUrl'] . $row['Uri'];
            $photoGalleryLink = $badgewidgetConf['UserPhotosUrl'] . $member['NickName'];

            $photosList .= '<td style="width:100px; vertical-align:middle; text-align:center;padding-bottom:0px;">
                <div style="width:100px;height:100px;text-align:centre;margin:0px auto;border:0px solid #cccccc;">
                <a href="' . $photoLink . '" target="TOP" title="' . _t(process_line_output($row['Title'])) . '">
                <img src="' . $photo_url . '" style="max-width:90px;max-height:90px;padding:1px;border:1px solid #cccccc;" alt="' . _t(process_line_output($row['Title'])) . '"/>
                </a>
                <br/>
                <font style="font-size:10px;color:' . $badgewidgetConf['TextColor'] . ';">' . limitStringLength(process_line_output($row['Title']), 18) . '</font></div>
                </td>';

            if ($num == 2) {
                $photosList .= '</tr><tr>';
                $num = 0;
            }
            $num++;
        }
        $photosList .= '</tr>
        </table>';

        $num_photos_result = mysql_query("
                SELECT * FROM `bx_photos_main` WHERE
                `Status` = 'approved' AND
                `Owner` = " . $member['ID'] . "
            ");
        $num_photos = mysql_num_rows($num_photos_result);

        $code = '
        <div style="width: ' . $width . 'px; height:' . $height . ';">
            <div style="background: ' . $badgewidgetConf['TitleBGColor'] . ';padding: 3px;">
            <font style="color:' . $badgewidgetConf['TitleColor'] . ';font-weight:bold;font-size:16px; font-family:' . $badgewidgetConf['TitleFontFamily'] . ';">' . $site['title'] . '</font>
            </div>
            <div style="background: ' . $badgewidgetConf['BoxBGColor'] . ';display: block;border-right: 1px solid ' . $badgewidgetConf['BoxBorderColor'] . ';border-bottom: 1px solid ' . $badgewidgetConf['BoxBorderColor'] . ';border-left: 1px solid ' . $badgewidgetConf['BoxBorderColor'] . ';margin: 0px;padding: 0px 0px 5px 0px;">
            <div style="background: ' . $badgewidgetConf['BoxBGColor'] . ';display: block;padding: 5px;">
            <table cellspacing="0" cellpadding="0" border="0">
                <tr>
                <td valign="top"></td>
                <td valign="middle"><p style="color: ' . $badgewidgetConf['TextColor'] . ';font-family: verdana;font-size: 11px;margin: 0px 0px 0px 0px;padding: 0px 8px 0px 8px;">
                <a href="' . getProfileLinks($member['ID']) . '" title="' . htmlspecialchars($member['NickName']) . '" target="TOP" style="color: ' . $badgewidgetConf['LinkColor'] . ';font-family: verdana;font-size: 14px;font-weight: bold;margin: 0px;padding: 0px 0px 0px 0px;text-decoration: none;">' . htmlspecialchars($member['NickName']) . '</a> ' . _t("_gkc_bw_Photos") . '</p></td>
                </tr>
            </table>
            </div>
            <div style="background: #FFFFFF;clear: both;display: block;margin: 0px;overflow: hidden;padding: 5px;font-family:verdana;">
            ' . $photosList . '

            <div style="font-size:10px;color:' . $badgewidgetConf['TextColor'] . ';text-align:right;font-family:verdana;">
            <a href="' . $photoGalleryLink . '" style="text-decoration: none;color:' . $badgewidgetConf['TextColor'] . ';">' . $num_photos . ' ' . _t("_gkc_bw_Photos") . '</a></div>
            </div></div>
        ';

        if (strlen($badgewidgetConf['FooterTextProfilePhotos']) > 0) {
            $code .= '<div style="text-align:right;padding: 4px 0px 0px 0px;font-size:10px;font-family: verdana;font-weight: none;">
            ' . $badgewidgetConf['FooterTextProfilePhotos'] . '</div>';
        }

        $code .= '</div>';
        return $code;
    }
}

function displayAgencyCombinedFamilyBadge($GID = 0, $MID = 0, $order = 'random') {
    global $site, $badgewidgetConf;
    $path = $site['url'];
    $badge_URL = $site['url'] . 'HOA_HOAAbadge.php?loadFrom=badge&sortvalue=' . $order . '&type=Sortby';
    $code = '<iframe src="' . $badge_URL . '" width ="100%" height="750px" frameBorder="0"></iframe>';
    return $code;
}

function displayAgencyFamilyBadge($GID = 0, $MID = 0, $order = 'random') {
    global $site, $badgewidgetConf;
    $path = $site['url'];
    /* $code   = "
      <link  rel='stylesheet' type='text/css' href='".$path."plugins/dhtmlx/dhtmlx.css' />

      <script src='".$path."plugins/dhtmlx/dhtmlx.js'></script>
      <link rel='stylesheet' type='text/css' href='".$path."'Expctantparentsearchfamilies/css/searchfamilies.css'>

      <script type='text/javascript' src='".$path."Expctantparentsearchfamilies/model/Expctantparentsearchfamilies_Model.js'></script>
      <script type='text/javascript' src='".$path."Expctantparentsearchfamilies/controller/Expctantparentsearchfamilies.js'></script>
      <script type='text/javascript' src='".$path."LikeComponent/Model/likeComponent_model.js'></script>
      <script type='text/javascript' src='".$path."LikeComponent/Controller/likeComponent.js'></script>


      <script type='text/javascript'>
      var siteurl = '$path';
      window.onload = function() {
      Expctantparentsearchfamilies.start({
      uid: (new Date()).getTime(),
      application_path: siteurl + 'Expctantparentsearchfamilies/',
      dhtmlx_codebase_path: siteurl + 'plugins/dhtmlx/'
      });
      };
      </script>


      <div class='clear_both'></div>
      <div class='page113'>
      <div class='grayIcons icoSearchFam'>VIEW OUR FAMILIES</div>
      <div class='searchmenu'>
      <div id='menuObj'></div>
      <div id='page_here'></div>
      </div>
      <div class='clear_both'></div>
      <div id='data_container_test' style='width:100%'>
      <div id='error' class='errormessage'></div>
      </div>
      </div>
      <div class='clear_both'></div>
      "; */
    //echo $code;
    //$code   = header("Location: http://localhost:1337/www/www.pf.comPV3.0/extra_profile_view_24.php");
    //$code   = '<iframe name="myiframe" src="http://localhost:1337/www/www.pf.comPV3.0/extra_profile_view_24.php" ></iframe>';
    //echo $code;
    //$code = file_get_contents($site['url'].'extra_profile_view_24.php?loadFrom=badge&agencyId='.$GID.'&rows='.$rows.'&columns='.$columns);
    //$rows       = 3;
    //$columns    = 2;
    /* if($rows == 3)
      $if_height  = 2025;
      else if($rows == 2)
      $if_height  = 1400;
      else if($rows == 1)
      $if_height   = 850;

      if($columns == 3)
      $if_width   = 1040;
      else if($columns == 2)
      $if_width   = 710;
      else if($columns == 1)
      $if_width   = 350; */

    //  if($rows == 3 && $columns == 1)
    //  $if_height  = 1300;
    //$badge_URL = $site['url'].'extra_profile_view_24.php?loadFrom=badge&agencyId='.$GID.'&rows='.$rows.'&columns='.$columns;

    $badge_URL = $site['url'].'viewourfamilies.php?loadFrom=badge&agencyId='.$GID.'&sortvalue=random&type=Sortby';
//$code = '<div style="-webkit-overflow-scrolling: touch;overflow-y: scroll; right: 0;bottom: 0;left: 0; top: 0;"><iframe src="'.$badge_URL.'" width ="100%" height="100%" frameBorder="0"></iframe></div>';
    
$code = '<iframe src="'.$badge_URL.'" width ="100%" height="750px" frameBorder="0"></iframe>';

    return $code;
}

function displayFeaturedFamilyBadge($order = 'random') {
    global $site, $badgewidgetConf;
    $path = $site['url'];
    $badge_URL = $site['url'].'featuredfamilies.php?loadFrom=badge&sortvalue='.$order.'&type=Sortby';
    $code = '<iframe src="'.$badge_URL.'" width ="100%" height="750px" frameBorder="0"></iframe>';
    return $code;
}
?>