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

$_page['header'] = _t("_gkc_bw_Profile badge");
$_page['header_text'] = _t("_gkc_bw_Profile badge");

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompPageMainCode();

// check if component enabled

global $badgewidgetConf;
if(!$badgewidgetConf['Comp_Profile'])
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
	$member = getProfileInfo( $member['ID'] );
	
	if(strlen($member['gkcBadgeWidgetConfCode'])==0)
	{
		// Create a Badge Confirmation Code
		$rand = md5(uniqid(rand(), true));
		$result = mysql_query("UPDATE  `Profiles` SET  `gkcBadgeWidgetConfCode` =  '".$rand."' WHERE  `Profiles`.`ID` = {$member['ID']} LIMIT 1 ;");
		if($result)
		{	createUserDataFile( $member['ID'] );
		header("Location:".$_SERVER['PHP_SELF']."");	}
	}
	
	$badgeLink['hor_with_info'] = $site['url'].'load_badge_widget_profile.php?TYPE=hor&ID='.$member['ID'].'&INFO=1&CONFCODE='.$member['gkcBadgeWidgetConfCode'].'';
	$badgeLink['hor_without_info'] = $site['url'].'load_badge_widget_profile.php?TYPE=hor&ID='.$member['ID'].'&INFO=0&CONFCODE='.$member['gkcBadgeWidgetConfCode'].'';
	$badgeLink['ver_with_info'] = $site['url'].'load_badge_widget_profile.php?TYPE=ver&ID='.$member['ID'].'&INFO=1&CONFCODE='.$member['gkcBadgeWidgetConfCode'].'';
	$badgeLink['ver_without_info'] = $site['url'].'load_badge_widget_profile.php?TYPE=ver&ID='.$member['ID'].'&INFO=0&CONFCODE='.$member['gkcBadgeWidgetConfCode'].'';
	
	function embedd_code($imgurl='',$member)
	{
                //$rtn_link   = BX_DOL_URL_ROOT . 'extra_agency_view_29.php?ID='.$member['ID'].'&load_from=badge';
                $rtn_link     = getProfileLinks($member['ID']);
		$out = '<!-- Badge START --><a href="'.$rtn_link.'" title="'.$member['NickName'].'" target="_TOP"><img src="'.$imgurl.'" style="border: 0px;" /></a><!-- Badge END -->';
		
		return $out;
	}
	
	ob_start();

?>

<?php echo badgewidget_topmenu(); ?>
	
		<div class="badgewidgetscontentdiv">
		<font style="font-size:14px;font-weight:bold;"><?php echo _t("_gkc_bw_Horizontal Badge with Info"); ?>:</font>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:10px;">
		<tr>
		<td style="width:"><img src="<?php echo $badgeLink['hor_with_info']; ?>" /></td>
		<td style="width:350px;">
			<b><?php echo _t("_gkc_bw_Copy the code below"); ?></b><br/><br/>
			<input type="text" style="width:300px;" onclick="this.select()" value='<?php echo embedd_code($badgeLink['hor_with_info'],$member); ?>' />
		</td>
		</tr>
		</table>
		</div>
		
		
		<div class="badgewidgetscontentdiv">
		<font style="font-size:14px;font-weight:bold;"><?php echo _t("_gkc_bw_Horizontal Badge without Info"); ?>:</font>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:10px;">
		<tr>
		<td style="width:"><img src="<?php echo $badgeLink['hor_without_info']; ?>" /></td>
		<td style="width:350px;">
			<b><?php echo _t("_gkc_bw_Copy the code below"); ?></b><br/><br/>
			<input type="text" style="width:300px;" onclick="this.select()" value='<?php echo embedd_code($badgeLink['hor_without_info'],$member); ?>' />
		</td>
		</tr>
		</table>
		</div>
		
		
		<div class="badgewidgetscontentdiv">
		<font style="font-size:14px;font-weight:bold;"><?php echo _t("_gkc_bw_Vertical Badge with Info"); ?>:</font>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:10px;">
		<tr>
		<td style="width:"><img src="<?php echo $badgeLink['ver_with_info']; ?>" /></td>
		<td style="width:350px;">
			<b><?php echo _t("_gkc_bw_Copy the code below"); ?></b><br/><br/>
			<input type="text" style="width:300px;" onclick="this.select()" value='<?php echo embedd_code($badgeLink['ver_with_info'],$member); ?>' />
		</td>
		</tr>
		</table>
		</div>
		
		
		<div class="badgewidgetscontentdiv">
		<font style="font-size:14px;font-weight:bold;"><?php echo _t("_gkc_bw_Vertical Badge without Info"); ?>:</font>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:10px;">
		<tr>
		<td style="width:"><img src="<?php echo $badgeLink['ver_without_info']; ?>" /></td>
		<td style="width:350px;">
			<b><?php echo _t("_gkc_bw_Copy the code below"); ?></b><br/><br/>
			<input type="text" style="width:300px;" onclick="this.select()" value='<?php echo embedd_code($badgeLink['ver_without_info'],$member); ?>' />
		</td>
		</tr>
		</table>
		</div>

<?php

    $ret = ob_get_clean();

    return $ret;
}

?>