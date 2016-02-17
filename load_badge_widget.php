<?php

require_once("inc/gkc_badgeWidgets.inc.php");

////////////////////////////////////////////////////////////////
//               BADGE WIDGETS				                  //
//    Created : 20 April, 2010			                      //
//    Creator : Gautam Chaudhary (Pulprix Developments)       //
//    Email : gkcgautam@gmail.com                             //
//    This product is owned by its creator                    //
//    This product cannot by redistributed by anyone else     //
//                 Do not remove this notice                  //
////////////////////////////////////////////////////////////////
// this is dynamic page -  send headers to do not cache this page
$now = gmdate('D, d M Y H:i:s') . ' GMT';
header("Expires: $now");
header("Last-Modified: $now");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
// echo generateGroupConf(73,29);
if (isset($_GET['display'])) {
    $out = '';

    if ($_GET['display'] == 'groupowner' || $_GET['display'] == 'groupmember' || $_GET['display'] == 'groupownersingle' || $_GET['display'] == 'groupagencybadge') {
        if (isset($_GET['GID']) && isset($_GET['MID']) && isset($_GET['conf']) && (int) $_GET['GID'] > 0 && (int) $_GET['MID'] > 0 && $_GET['conf'] == generateGroupConf($_GET['MID'], $_GET['GID'])) {
            if ($_GET['display'] == 'groupowner') {
                $width = $_GET['width'];
                if ($_GET['order'] == 'alfa') {
                    $out = displayGroupCreatorBadgeal((int) $_GET['GID'], (int) $_GET['MID'], $width);
                } elseif ($_GET['order'] == 'rand') {
                    $out = displayGroupCreatorBadgerd((int) $_GET['GID'], (int) $_GET['MID'], $width);
                } else {
                    $out = displayGroupCreatorBadge((int) $_GET['GID'], (int) $_GET['MID'], $width);
                }
            } elseif ($_GET['display'] == 'groupownersingle') {
                $width = $_GET['width'];
                if ($_GET['order'] == 'alfa') {
                    $out = displayGroupCreatorBadgeal((int) $_GET['GID'], (int) $_GET['MID'], $width);
                } elseif ($_GET['order'] == 'rand') {
                    $out = displayGroupCreatorBadgerd((int) $_GET['GID'], (int) $_GET['MID'], $width);
                } else {
                    $out = displayGroupCreatorBadgesingle((int) $_GET['GID'], (int) $_GET['MID'], $width, (int) $_GET['logid']);
                }
            } elseif ($_GET['display'] == 'groupmember') {
                if (isset($_GET['width']) && $_GET['width'] >= 300) {
                    $width = (int) $_GET['width'];
                } else {
                    $width = 350;
                }
                if (isset($_GET['height']) && $_GET['height'] >= 150) {
                    $height = (int) $_GET['height'] . "px";
                } else {
                    $height = "";
                }
                $out = displayGroupMemberBadge((int) $_GET['GID'], (int) $_GET['MID'], $width, $height);
            } elseif ($_GET['display'] == 'groupagencybadge') {
                $rows = $_GET['rows'];
                $columns = $_GET['columns'];
                $out = displayAgencyFamilyBadge((int) $_GET['GID'], (int) $_GET['MID'], $rows, $columns);
                //$out = displayGroupMemberBadge((int)$_GET['GID'],(int)$_GET['MID'], 200, 200);
                //$out = displayGroupCreatorBadge((int)$_GET['GID'],(int)$_GET['MID'], $width);
            }
        }
    }

    if ($_GET['display'] == 'eventcreated' || $_GET['display'] == 'eventjoined') {
        if (isset($_GET['EID']) && isset($_GET['MID']) && isset($_GET['conf']) && (int) $_GET['EID'] > 0 && (int) $_GET['MID'] > 0 && $_GET['conf'] == generateEventConf($_GET['MID'], $_GET['EID'], $_GET['display'])) {
            if ($_GET['display'] == 'eventcreated') {
                if (isset($_GET['width']) && $_GET['width'] >= 350) {
                    $width = (int) $_GET['width'];
                } else {
                    $width = 350;
                }
                if (isset($_GET['height']) && $_GET['height'] >= 230) {
                    $height = (int) $_GET['height'] . "px";
                } else {
                    $height = "";
                }
                $out = displayEventCreatorBadge((int) $_GET['EID'], (int) $_GET['MID'], $width, $height);
            }
            if ($_GET['display'] == 'eventjoined') {
                if (isset($_GET['width']) && $_GET['width'] >= 300) {
                    $width = (int) $_GET['width'];
                } else {
                    $width = 350;
                }
                if (isset($_GET['height']) && $_GET['height'] >= 200) {
                    $height = (int) $_GET['height'] . "px";
                } else {
                    $height = "";
                }
                $out = displayEventsJoinedBadge((int) $_GET['EID'], (int) $_GET['MID'], $width, $height);
            }
        }
    }
    if ($_GET['display'] == 'profilephotos') {
        if (isset($_GET['num']) && isset($_GET['MID']) && isset($_GET['conf']) && (int) $_GET['num'] > 0 && (int) $_GET['MID'] > 0 && $_GET['conf'] == generatePhotoConf($_GET['MID'], $_GET['num'])) {
            if (isset($_GET['width']) && $_GET['width'] >= 230) {
                $width = (int) $_GET['width'];
            } else {
                $width = 350;
            }
            if (isset($_GET['height']) && $_GET['height'] >= 250) {
                $height = (int) $_GET['height'] . "px";
            } else {
                $height = "";
            }
            $out = displayProfilePhotosBadge((int) $_GET['MID'], (int) $_GET['num'], $width, $height);
        }
    }

    if ($_GET['display'] == 'groupiframe') {
        if (isset($_GET['GID']) && isset($_GET['MID']) && isset($_GET['conf']) && (int) $_GET['GID'] > 0 && (int) $_GET['MID'] > 0) {
            if (isset($_GET['width']) && $_GET['width'] >= 150) {
                $width = (int) $_GET['width'];
            } else {
                $width = 150;
            }
            if (isset($_GET['height']) && $_GET['height'] >= 150) {
                $height = (int) $_GET['height'] . "px";
            } else {
                $height = "1000px";
            }
            $out = displayGroupIframeBadge((int) $_GET['GID'], (int) $_GET['MID'], $width, $height);
        }
    }

    if ($_GET['display'] == 'groupiframe') {
        $width = $_GET['width'];
        $out = displayGroupiframe((int) $_GET['GID'], (int) $_GET['MID'], $width);
    }

    if ($_GET['display'] == 'featuredfamiliesbadge') {
        $order = $_GET['order'];
        $out = displayFeaturedFamilyBadge($order);
    }
    if ($_GET['display'] == 'agencycombinedbadge') {
        $order = $_GET['order'];
        $out = displayAgencyCombinedFamilyBadge($order);
    }

    /*
      " " (ASCII 32 (0x20)), an ordinary space.
      "\t" (ASCII 9 (0x09)), a tab.
      "\n" (ASCII 10 (0x0A)), a new line (line feed).
      "\r" (ASCII 13 (0x0D)), a carriage return.
      "\0" (ASCII 0 (0x00)), the NUL-byte.
      "\x0B" (ASCII 11 (0x0B)), a vertical tab.
     */

    $out = addslashes($out);
    //$out = str_replace("'", "\'",$out);
    $out = str_replace("\t", "", $out);
    $out = str_replace("\n", "", $out);
    $out = str_replace("\r", "", $out);
    $out = str_replace("\0", "", $out);
    $out = str_replace("\x0B", "", $out);
    //$out  = 'bbb<p>ddd</p>';
    echo 'document.write(\'' . $out . '\');';
    //echo $out;
}
?>