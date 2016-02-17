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

$_page['header'] = _t("_gkc_bw_Photos badge");
$_page['header_text'] = _t("_gkc_bw_Photos badge");

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
	
	ob_start();

?>
<?php echo badgewidget_topmenu(); ?>
		<?php
		$num_photos_result = mysql_query("
		SELECT * FROM `bx_photos_main` WHERE 
				`Status` = 'approved' AND
				`Owner` = ".$member['ID']."
			");
		$num_photos = @mysql_num_rows($num_photos_result);
		
		if($num_photos)
		{
				echo '<div class="badgewidgetscontentdiv">
		<font style="font-size:14px;font-weight:bold;">'._t("_gkc_bw_Select number of random").':</font>
		<br/>
		<br/>
		<form method="POST" action="'.$_SERVER['PHP_SELF'].'">
		
		<table border="0" cellspacing="5" cellpadding="0">
		<tr>
		<td>
		<select name="num" style="height:20px;font-size:12px;"> 
	<option value="">- '._t("_gkc_bw_SELECT").' -</option>
	<option value="2">2</option> 
		<option value="4">4</option> 
		<option value="6">6</option>
		</select>
		</td>
		<td><input type="submit" value="'._t("_gkc_bw_Create Badge").'"/></td>
		</tr>
		</table>
		</form>
		</div>';
		
		if(isset($_POST['num']) && (int)$_POST['num']>0)
		{
				if((int)$_POST['num']>6)
				{	(int)$_POST['num']=6;	}
				
				global $badgewidgetConf;
				
				$code = displayProfilePhotosBadge($member['ID'],(int)$_POST['num']);
				$embedd_div_id = date("mdGis").'m'.$member['ID'].'pp'.substr(md5(uniqid(rand(), true)), 0, 8);
				$embedd_validator_code = generatePhotoConf($member['ID'],(int)$_POST['num']);
				$embedd_code = '<!--Badge Start --><div id="'.$embedd_div_id.'"><div style="width:220px;text-align:center"><font style="font-size:10px;font-family: verdana;font-weight: none;"><a href="'.getProfileLink($member['ID']).'">'._t("_gkc_bw_Photos").'</a> '._t("_gkc_bw_on").' <a href="'.$site['url'].'">'.$site['title'].'</a></font></div></div><script type="text/javascript" src="'.$site['url'].'load_badge_widget.php?MID='.$member['ID'].'&num='.(int)$_POST['num'].'&display=profilephotos&conf='.$embedd_validator_code.'" onload="document.getElementById(\''.$embedd_div_id.'\').innerHTML=\'\';"></script><!-- Badge End -->';
				
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
		}
		else
			{
				echo '<div class="badgewidgetscontentdiv">
					<img src="badgewidgets/images/action_report.png" align="absmiddle"/> '._t("_gkc_bw_You have not uploaded").'
					</div>';
			}
		?>
		
		<div style="height:100px;"> </div>
		

<?php

    $ret = ob_get_clean();

    return $ret;
}

?>