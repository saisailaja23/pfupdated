<?

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

$_page['name_index'] 	= 34;
$_page['css_name']		= 'gkc_badgewidgets.css';

$logged['member'] = member_auth(0);

$_page['header'] = _t("_gkc_bw_Events Attending");
$_page['header_text'] = _t("_gkc_bw_Events Attending");

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompPageMainCode();

// check if component enabled

global $badgewidgetConf;
if(!$badgewidgetConf['Comp_Events'])
{	header("Location:badgeWidgets.php");	}

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompPageMainCode()
{
	global $site;
	$member['ID'] = (int)$_COOKIE['memberID'];
    $memid = $member['ID'];
	$member = getProfileInfo( $member['ID'] );

	$sQuery4 = "SELECT * FROM `bx_events_main` WHERE `ResponsibleID` = '$memid'";
    $rRes4 = mysql_query( $sQuery4 ); 
    while($row = mysql_fetch_array($rRes4)) {
    $id = $row['ID'];
    $ResponsibleID = $row['ResponsibleID'];
    $date = $row['Date'];
    $sQuery3 = "SELECT * FROM `bx_events_participants` WHERE `id_entry` = '$id' AND `id_profile` = '$ResponsibleID'";
    $rRes3 = db_res( $sQuery3 );
    $numrow = mysql_num_rows($rRes3);
    if($numrow <= 0) { 
      $sQuery2 = "INSERT INTO `bx_events_participants` SET `id_entry` = '$id', `id_profile` = '$ResponsibleID', `when` = '$date', `confirmed` = '1'"; 
      $rRes2 = db_res( $sQuery2 );
     }
    }

	$sQuery = mysql_query("
				SELECT `bx_events_main`.`Title` AS 'Name',`bx_events_main`.`ID` AS 'ID', `bx_events_main`.`EventStart` as 'start'
				FROM `bx_events_participants`, `bx_events_main`
				WHERE 
				`bx_events_main`.`status` = 'approved' AND
				`bx_events_participants`.`id_profile` = ".$member['ID']." AND
				`bx_events_participants`.`id_entry`  = `bx_events_main`.`id` AND
				`bx_events_participants`.`confirmed`   = '1'
			") or 
	die('Error while fetching data. Reloading the page might solve the issue. <!-- ' . mysql_error().' -->');
	

	
	
	$selectElement = '<select name="EID">
	<option value="">- '._t("_gkc_bw_SELECT").' -</option>';
	while($row = mysql_fetch_array($sQuery))
	{
		$selectElement .= '<option value="'.$row['ID'].'">'.htmlspecialchars(limitStringLength($row['Name'], 35)).' - '.date("M j, Y",$row['start']).'</option>
		';
	}
	$selectElement .= '</select>';
	
	ob_start();

?>

<?php echo badgewidget_topmenu(); ?>
	
		<div class="badgewidgetscontentdiv">
		<font style="font-size:14px;font-weight:bold;"><?php echo _t("_gkc_bw_Select an Event to create a Badge"); ?>:</font>
		<br/>
		<br/>
		<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		
		<table border="0" cellspacing="5" cellpadding="0">
		<tr>
		<td><?php echo $selectElement; ?></td>
		<td><input type="submit" value="<?php echo _t("_gkc_bw_Create Badge"); ?>"/></td>
		</tr>
		</table>
		</form>
		<?php if(mysql_num_rows($sQuery)<1)
			{	echo '<br/><br/><font style="color:#B40404;font-weight:bold;">'._t("You have not joined any event").'</font>';	}
		?>
		</div>
		
		
		
		
		<?php
		if(isset($_GET['EID']) && (int)$_GET['EID']>0)
		{
			if(isEventParticipant($member['ID'],(int)$_GET['EID']))
			{
				global $badgewidgetConf;
				
				$EID=(int)$_GET['EID'];
				$eventUrl = $badgewidgetConf['EventUrl'].$EID;
				$code = displayEventsJoinedBadge($EID,$member['ID']);
				$embedd_div_id = date("mdGis").'m'.$member['ID'].'e'.$EID.'c'.substr(md5(uniqid(rand(), true)), 0, 5);
				$embedd_validator_code = generateEventConf($member['ID'],$EID,'eventjoined');
				$embedd_code = '<!--Badge Start --><div id="'.$embedd_div_id.'"><div style="width:300px;text-align:center"><br/><font style="font-size:10px;font-family: verdana;font-weight: none;"><a href="'.$site['url'].'">'.$site['title'].' '._t("_gkc_bw_Events").'</a></font></div></div><script type="text/javascript" src="'.$site['url'].'load_badge_widget.php?EID='.$EID.'&MID='.$member['ID'].'&display=eventjoined&conf='.$embedd_validator_code.'" onload="document.getElementById(\''.$embedd_div_id.'\').innerHTML=\'\';"></script><!-- Badge End -->';
				
				echo '
				<div class="badgewidgetscontentdiv">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td style="width:350px;">
				<font style="font-size:14px;font-weight:bold;">'._t("_gkc_bw_Badge Preview").':</font><br/><br/>
				'.$code.'
				</td>
				<td style="padding-left:100px;">
				<b>'._t("_gkc_bw_Copy the code below").'</b><br/><br/>
				<input type="text" style="width:300px;height:20px;font-size:12px;" onclick="this.select()" value="'.htmlspecialchars($embedd_code).'" />
				</td>
				</tr>
				</table>
				</div>';
			}
			else
			{
				echo '<div class="badgewidgetscontentdiv">
					<img src="badgewidgets/images/action_report.png" align="absmiddle"/> '._t("_gkc_bw_You need to be a guest").'
					</div>';
			}
		}
		?>
		
		<div style="height:100px;"> </div>
		

<?php

    $ret = ob_get_clean();

    return $ret;
}

?>