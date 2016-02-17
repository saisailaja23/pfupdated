<?
set_time_limit(9999999);
/***************************************************************************

*                            Dolphin Smart Community Builder

*                              -----------------

*     begin                : Mon Mar 23 2006

*     copyright            : (C) 2006 BoonEx Group

*     website              : http://www.boonex.com/

* This file is part of Dolphin - Smart Community Builder

*

* Dolphin is free software. This work is licensed under a Creative Commons Attribution 3.0 License. 

* http://creativecommons.org/licenses/by/3.0/

*

* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;

* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

* See the Creative Commons Attribution 3.0 License for more details. 

* You should have received a copy of the Creative Commons Attribution 3.0 License along with Dolphin, 

* see license.txt file; if not, write to marketing@boonex.com

***************************************************************************/
 
require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'languages.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

echo "START .....<br /><br />";

echo "UPDATING DATABASE .....<br /><br />";



$iLangCategId =1;

$resLang = db_res("SELECT * FROM `sys_localization_languages`");

$arrLang = array();
$iter = 0;
while($arr = mysql_fetch_array($resLang) )
{
	$arrLang[$iter] = (int)$arr['ID'];
	$iter++;
}
 


$arrLanNewKeys = array(	
    '_bx_events_block_search' => 'Quick Search',  
   '_bx_events_block_map' => 'Map',  
	'_bx_events_accept_invitation' => 'Accept Event Invitation - ', 
	'_bx_events_caption_participants_info' => 'Participants Information', 
	'_bx_events_info_participants_info' => 'This information is viewable only to Participants',  
	'_bx_events_block_rss_feed' => 'RSS Feed',
	'_bx_events_form_caption_rss' => 'RSS Link', 
    '_bx_events_block_participants_info' => 'Participants Information',
 	
	'_mma_events_rss_add' => 'events rss add',  
    '_mma_events_files_add' => 'events files add',  
    '_mma_events_photos_add' => 'events photos add',  
    '_mma_events_videos_add' => 'events videos add',  
    '_mma_events_sounds_add' => 'events sounds add',  
 
   	'_bx_events_form_header_video_embed' => 'Video Embed',
   	'_bx_events_caption_video_embed_code' => 'Embed Code',
   	'_bx_events_block_video_embed' => 'External Video',
   '_bx_events_block_forum_feed' => 'Forum Feed',

   	'_bx_events_form_info_video_embed_code' => 'Enter either the URL or Embed Code for Youtube Video. Enter the Embed Code for other video sharing site (eg &lt;embed&gt;....&lt;/embed&gt;)',
  
	'_bx_events_caption_membership_view_filter' => 'Allow viewing by membership',
	'_bx_events_err_membership_view_filter' => 'incorrect value', 
    '_bx_events_info_membership_view_filter' => 'only these members will be able to view this event',

   	'_bx_events_caption_browse_other' => 'Other Events by {0}',
	'_bx_events_form_header_reminder' => 'Remind Participants',  
	'_bx_events_caption_reminder' => 'Send Reminder',  
	'_bx_events_caption_reminder_days' => 'Days before Event Ends',  
	'_bx_events_info_reminder_days' => 'The reminder will be sent this number of Days before the Event Ends', 
	'_bx_events_yes' => 'Yes',  
	'_bx_events_no' => 'No',  
  
	'_bx_events_starts' => 'Starts',  
	'_bx_events_ends' => 'Ends',  
	'_bx_events_comments' => 'Comments',  
	
	'_bx_events_caption_categories' => 'Categories',  
	'_bx_events_all_categories' => 'All Categories',  
	
	'_bx_events_regions_for_country' => 'States/Regions for',  
	'_bx_events_block_browse_state' => 'Browse States', 
	'_bx_events_block_browse_state_events' => 'Browse Events by State', 
	'_bx_events_block_common_categories' => 'Common Categories', 
	'_bx_events_block_latest_activities' => 'Latest Activities', 
	'_bx_events_block_account' => 'Events in my Area', 
	'_bx_events_block_latest_comments' => 'Latest Comments', 

	'_bx_events_block_browse_country' => 'Browse by Country', 
	'_bx_events_page_title_local' => 'Browse Local Events',  
	'_bx_events_caption_browse_local' => 'Browse Local Events in {0}', 
	'_bx_events_menu_local' => 'Local', 
	'_bx_events_block_organizer' => 'Organizer', 
	'_bx_events_block_local' => 'More Local Events',
  	'_bx_events_block_other' => 'Other Events by this member',
  	'_bx_events_caption_organizer_name' => 'Organizer Name',
  	'_bx_events_caption_organizer_email' => 'Organizer Email',
  	'_bx_events_caption_organizer_phone' => 'Organizer Telephone',
  	'_bx_events_caption_organizer_website' => 'Organizer Website',
  	'_bx_events_caption_organizer_fax' => 'Organizer Fax',
   	'_bx_events_form_header_organizer' => 'Organizer Info',
	
	'_bx_events_recurring_unlimited' => 'unlimited',  
 	'_bx_events_recurring_daily' => 'daily',  
 	'_bx_events_recurring_weekly' => 'weekly',  
 	'_bx_events_recurring_biweekly' => 'bi-weekly',  
 	'_bx_events_recurring_monthly' => 'monthly',  
 	'_bx_events_recurring_quarterly' => 'quarterly',  
 	'_bx_events_recurring_yearly' => 'yearly',  
  	'_bx_events_frequency' => 'Event Frequency',  
  	'_bx_events_recurring_period' => 'Period',  
   	'_bx_events_recurring' => 'Recurring',  
 	'_bx_events_stop_after' => 'stop after',   
  	'_bx_events_occurences_explanation' => 'occurences - <b>leave blank for unlimited</b>', 
   	'_bx_events_recurring_period_message' => 'This Event will be hosted {0}',  
  	'_bx_events_block_recurring' => 'Event Recurrence',  
 
   	'_bx_events_form_header_recurring' => 'Recurring Info',  
   	'_bx_events_caption_recurring' => 'Recurring',  
   	'_bx_events_recurring_no' => 'No',  
   	'_bx_events_recurring_yes' => 'Yes',  
   	'_bx_events_caption_recurring_number' => 'Number of Occurences',  
   	'_bx_events_caption_recurring_period' => 'Period',  
 
   	'_bx_events_block_create' => 'What will be happening soon?',  
   	'_bx_events_block_top_list' => 'Top Rated Public Events',  
   	'_bx_events_block_popular_list' => 'Popular Public Events',  
	'_bx_events_block_featured' => 'Recently Featured Events',  
   	'_bx_events_block_calendar' => 'Events Calendar',  
	'_bx_events_block_forum' => 'Latest Public Forum Posts',  
  
	'_bx_events_continue' => 'Continue',  
	'_bx_events_enter_your_event_name' => 'Enter your event name',  
	'_bx_events_caption_state' => 'State',  


	'_bx_events_feed_make_admin' => '<a href="{profile_link}">{profile_nick}</a> is now an admin for event <a href="{entry_url}">{entry_title}</a>',  

	'_bx_events_feed_remove_admin' => '<a href="{profile_link}">{profile_nick}</a> is no longer an admin for event <a href="{entry_url}">{entry_title}</a>',  

	'_bx_events_feed_featured' => '<a href="{profile_link}">{profile_nick}</a> has featured event <a href="{entry_url}">{entry_title}</a>',  

	'_bx_events_feed_unfeatured' => '<a href="{profile_link}">{profile_nick}</a> has un-featured event <a href="{entry_url}">{entry_title}</a>',  
 
	'_bx_events_feed_delete' => '<a href="{profile_link}">{profile_nick}</a> removed event <a href="{entry_url}">{entry_title}</a>',  

	'_bx_events_feed_post' => '<a href="{profile_link}">{profile_nick}</a> added new event <a href="{entry_url}">{entry_title}</a>',  

	'_bx_events_feed_post_change' => '<a href="{profile_link}">{profile_nick}</a> changed event <a href="{entry_url}">{entry_title}</a>',  

	'_bx_events_feed_join' => '<a href="{profile_link}">{profile_nick}</a> is attending event <a href="{entry_url}">{entry_title}</a>',  

	'_bx_events_feed_unjoin' => '<a href="{profile_link}">{profile_nick}</a> is no longer attending event <a href="{entry_url}">{entry_title}</a>',  

	'_bx_events_feed_rate' => '<a href="{profile_link}">{profile_nick}</a> rated event <a href="{entry_url}">{entry_title}</a>',  

	'_bx_events_feed_comment' => '<a href="{profile_link}">{profile_nick}</a> commented on event <a href="{entry_url}">{entry_title}</a>',

	'_bx_events_posted_by' => 'Posted by',  
	'_bx_events_posted_in_event' => 'Posted in Event',  
 
);

 
foreach($arrLanNewKeys as $eachKey=>$eachVal)
{

	if($iExists = (int)db_value("SELECT `ID` FROM `sys_localization_keys` WHERE `Key`='{$eachKey}'"))
		continue;

	db_res("INSERT INTO `sys_localization_keys` SET `IDCategory`=$iLangCategId, `Key`='{$eachKey}';");

	$iInsertID = mysql_insert_id( );
	foreach( $arrLang as $iLanguage)
		db_res("INSERT INTO `sys_localization_strings` SET  `IDKey`={$iInsertID}, `IDLanguage`='{$iLanguage}', `String`='$eachVal';");
}
 
echo "SUCCESSFULLY UPDATED DATABASE ..... <br /><br />";


if ( !compileLanguage() )
{
	echo '<font color="red">RECOMPILE LANGUAGE FILES FAILED.</font><br /><br />'; 
}else{ 
	echo "SUCCESSFULLY RECOMPILED LANGUAGE FILES<br /><br />";
}


echo "FINISHED <br />";


?>