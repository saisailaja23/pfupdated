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

// Authentification no required here. Just check if somebody logged in.

if ( !( $logged['admin'] = member_auth( 1, false ) ) )
	if ( !( $logged['member'] = member_auth( 0, false ) ) )
		if ( !( $logged['aff'] = member_auth( 2, false )) )
			$logged['moderator'] = member_auth( 3, false );


$_page['header'] = _t("_gkc_bw_Badge Widgets");
$_page['header_text'] = _t("_gkc_bw_Badge Widgets");

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompPageMainCode();


// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompPageMainCode()
{
	global $site;
	global $badgewidgetConf;
	
	$sum_comp = $badgewidgetConf['Comp_Events']+$badgewidgetConf['Comp_Groups']+$badgewidgetConf['Comp_Profile'];
	if($sum_comp==0)
	{	$sum_comp = 1;	}
	
	$td_width = (int)((100)/($sum_comp));

	ob_start();

?>
	
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td style="padding-left:40px;">
    <h2><?php echo $site['title'].' '._t("_gkc_bw_Badge Widgets"); ?></h2>
    <strong><?php echo _t("_gkc_bw_Share anywhere on the web"); ?></strong></td>
    <td style="width:420px; text-align:right;"><img src="badgewidgets/images/badgewidgets.png" alt="Widgets"/></td>
  </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
<?php
if($badgewidgetConf['Comp_Profile'])
{
	echo '
    <td style="vertical-align:top;" width="'.$td_width.'">
    	<div class="badgewidgetsectiontitles">'._t("_gkc_bw_Personal profile").'</div>
        <br/>
        <table width="95%" border="0" cellspacing="5" cellpadding="0">
  <tr>
    <td style="width:110px; vertical-align:top"><img src="badgewidgets/images/profile.jpg" style="padding:0px; border:1px solid #cccccc;"/></td>
    <td style="vertical-align:top"><h3><a href="badgeWidgets-profile.php" title="Profile badge">'._t("_gkc_bw_Profile badge").'</a></h3>
      '._t("_gkc_bw_Share your Profile information").'</td>
  </tr>
  <tr>
    <td style="width:110px; vertical-align:top"><img src="badgewidgets/images/photos.jpg" style="padding:0px; border:1px solid #cccccc;"/></td>
    <td style="vertical-align:top"><h3><a href="badgeWidgets-photos.php" title="Photo badge">'._t("_gkc_bw_Photos badge").'</a></h3>
      '._t("_gkc_bw_Share your Profile photos").'</td>
  </tr>
</table>
</td>';
}

if($badgewidgetConf['Comp_Events'])
{
	echo '
    <td style="vertical-align:top;" width="'.$td_width.'">
    	<div class="badgewidgetsectiontitles">'._t("_gkc_bw_Events").'</div>
        <br/>
        <table width="95%" border="0" cellspacing="5" cellpadding="0">
  <tr>
    <td style="width:110px; vertical-align:top"><img src="badgewidgets/images/event.jpg" style="padding:0px; border:1px solid #cccccc;"/></td>
    <td style="vertical-align:top"><h3><a href="badgeWidgets-events-created.php" title="Profile badge">'._t("_gkc_bw_Events Created").'</a></h3>
      '._t("_gkc_bw_Display your Event information").'</td>
  </tr>
  <tr>
    <td style="width:110px; vertical-align:top"><img src="badgewidgets/images/event-guest.jpg" style="padding:0px; border:1px solid #cccccc;"/></td>
    <td style="vertical-align:top"><h3><a href="badgeWidgets-events-joined.php" title="Photo badge">'._t("_gkc_bw_Events Attending").'</a></h3>
      '._t("_gkc_bw_Tell people about an event").'</td>
  </tr>
</table>
</td>';
}

if($badgewidgetConf['Comp_Groups'])
{
	echo '
    <td style="vertical-align:top;" width="'.$td_width.'">
    	<div class="badgewidgetsectiontitles">'._t("_gkc_bw_Groups").'</div>
        <br/>
        <table width="95%" border="0" cellspacing="5" cellpadding="0">
  <tr>
    <td style="width:110px; vertical-align:top"><img src="badgewidgets/images/group-members.jpg" style="padding:0px; border:1px solid #cccccc;"/></td>
    <td style="vertical-align:top"><h3><a href="badgeWidgets-groups-owned.php" title="Profile badge">'._t("_gkc_bw_Group Badge").'</a></h3>
      '._t("_gkc_bw_Display your Group name").'</td>
  </tr>
  <tr>
    <td style="width:110px; vertical-align:top"><img src="badgewidgets/images/group-membership.jpg" style="padding:0px; border:1px solid #cccccc;"/></td>
    <td style="vertical-align:top"><h3><a href="badgeWidgets-groups-joined.php" title="Photo badge">'._t("_gkc_bw_Group Fan Badge").'</a></h3>
      '._t("_gkc_bw_Tell people that you are").'</td>
  </tr>
</table>
</td>';
}
?>
  </tr>
</table>



 <!--table cellspacing="0" cellpadding="0" border="0" width="100%">
  <tbody><tr>

    <td width="33" style="vertical-align: top;">
        <div class="badgewidgetsectiontitles">Agency</div>
        <br>
        <table cellspacing="5" cellpadding="0" border="0" width="95%">
  <tbody><tr>
    <td style="width: 110px; vertical-align: top;"><img style="padding: 0px; border: 1px solid rgb(204, 204, 204);" src="badgewidgets/images/profile.jpg"></td>
    <td style="vertical-align: top;"><h3><a title="Profile badge" href="badgeWidgets-agency.php">Agency badge</a></h3>
      Share your Agency information on other websites</td>
  </tr>
  <tr>
    
  </tr>
</tbody></table>
</td>
    <td width="33" style="vertical-align: top;">
        
</td>
    <td width="33" style="vertical-align: top;">

</td>  
</tr></tbody>
</table-->



<div style="height:100px;"> </div>
	

<?php

    $ret = ob_get_clean();

    return $ret;
}

?>