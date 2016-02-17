
-- tables
DROP TABLE IF EXISTS `[db_prefix]main`;
DROP TABLE IF EXISTS `[db_prefix]fans`;
DROP TABLE IF EXISTS `[db_prefix]admins`;
DROP TABLE IF EXISTS `[db_prefix]images`;
DROP TABLE IF EXISTS `[db_prefix]videos`;
DROP TABLE IF EXISTS `[db_prefix]sounds`;
DROP TABLE IF EXISTS `[db_prefix]files`;
DROP TABLE IF EXISTS `[db_prefix]rating`;
DROP TABLE IF EXISTS `[db_prefix]rating_track`;
DROP TABLE IF EXISTS `[db_prefix]cmts`;
DROP TABLE IF EXISTS `[db_prefix]cmts_track`;
DROP TABLE IF EXISTS `[db_prefix]views_track`;
DROP TABLE IF EXISTS `[db_prefix]claim`;
DROP TABLE IF EXISTS `[db_prefix]profiles`;
DROP TABLE IF EXISTS `[db_prefix]cities`;
DROP TABLE IF EXISTS `[db_prefix]countries`;
DROP TABLE IF EXISTS `[db_prefix]categ`;
DROP TABLE IF EXISTS `[db_prefix]packages`;
DROP TABLE IF EXISTS `[db_prefix]invoices`;
DROP TABLE IF EXISTS `[db_prefix]orders`;
DROP TABLE IF EXISTS `[db_prefix]featured_orders`;
 
-- forum tables
DROP TABLE IF EXISTS `[db_prefix]forum`;
DROP TABLE IF EXISTS `[db_prefix]forum_cat`;
DROP TABLE IF EXISTS `[db_prefix]forum_flag`;
DROP TABLE IF EXISTS `[db_prefix]forum_post`;
DROP TABLE IF EXISTS `[db_prefix]forum_topic`;
DROP TABLE IF EXISTS `[db_prefix]forum_user`;
DROP TABLE IF EXISTS `[db_prefix]forum_user_activity`;
DROP TABLE IF EXISTS `[db_prefix]forum_user_stat`;
DROP TABLE IF EXISTS `[db_prefix]forum_vote`;
DROP TABLE IF EXISTS `[db_prefix]forum_actions_log`;
DROP TABLE IF EXISTS `[db_prefix]forum_attachments`;
DROP TABLE IF EXISTS `[db_prefix]forum_signatures`;

-- compose pages
DELETE FROM `sys_page_compose_pages` WHERE `Name` IN('modzzz_listing_view', 'modzzz_listing_celendar', 'modzzz_listing_main', 'modzzz_listing_my','modzzz_listing_edit','modzzz_listing_category','modzzz_listing_subcategory','modzzz_listing_local','modzzz_listing_local_state', 'modzzz_listing_packages');
DELETE FROM `sys_page_compose` WHERE `Page` IN('modzzz_listing_view', 'modzzz_listing_celendar', 'modzzz_listing_main', 'modzzz_listing_my','modzzz_listing_edit','modzzz_listing_category','modzzz_listing_subcategory','modzzz_listing_local','modzzz_listing_local_state', 'modzzz_listing_packages');
DELETE FROM `sys_page_compose` WHERE `Page` = 'index' AND `Desc` = 'Listings';
DELETE FROM `sys_page_compose` WHERE `Page` = 'profile' AND `Desc` = 'User Listings';
DELETE FROM `sys_page_compose` WHERE `Page` = 'member' AND `Desc` = 'My Listings';
DELETE FROM `sys_page_compose` WHERE `Page` = 'member' AND `Desc` = 'Local Listings';

 

-- system objects
DELETE FROM `sys_permalinks` WHERE `standard` = 'modules/?r=listing/';
DELETE FROM `sys_objects_vote` WHERE `ObjectName` = 'modzzz_listing';
DELETE FROM `sys_objects_cmts` WHERE `ObjectName` = 'modzzz_listing';
DELETE FROM `sys_objects_views` WHERE `name` = 'modzzz_listing';
DELETE FROM `sys_objects_categories` WHERE `ObjectName` = 'modzzz_listing';
DELETE FROM `sys_categories` WHERE `Type` = 'modzzz_listing';
DELETE FROM `sys_categories` WHERE `Type` = 'bx_photos' AND `Category` = 'Listing';
DELETE FROM `sys_objects_tag` WHERE `ObjectName` = 'modzzz_listing';
DELETE FROM `sys_tags` WHERE `Type` = 'modzzz_listing';
DELETE FROM `sys_objects_search` WHERE `ObjectName` = 'modzzz_listing';
DELETE FROM `sys_objects_actions` WHERE `Type` = 'modzzz_listing' OR `Type` = 'modzzz_listing_title';
DELETE FROM `sys_stat_site` WHERE `Name` = 'modzzz_listing';
DELETE FROM `sys_stat_member` WHERE TYPE IN('modzzz_listing', 'modzzz_listingp');
DELETE FROM `sys_account_custom_stat_elements` WHERE `Label` = '_modzzz_listing';
 
-- top menu
SET @iCatRoot := (SELECT `ID` FROM `sys_menu_top` WHERE `Name` = 'Listing' AND `Parent` = 0 LIMIT 1);
DELETE FROM `sys_menu_top` WHERE `Parent` = @iCatRoot;
DELETE FROM `sys_menu_top` WHERE `ID` = @iCatRoot;

SET @iCatRoot := (SELECT `ID` FROM `sys_menu_top` WHERE `Name` = 'Listing' AND `Parent` = 0 LIMIT 1);
DELETE FROM `sys_menu_top` WHERE `Parent` = @iCatRoot;
DELETE FROM `sys_menu_top` WHERE `ID` = @iCatRoot;

DELETE FROM `sys_menu_top` WHERE `Parent` = 9 AND `Name` = 'Listing';
DELETE FROM `sys_menu_top` WHERE `Parent` = 4 AND `Name` = 'Listing';

-- admin menu
DELETE FROM `sys_menu_admin` WHERE `name` = 'modzzz_listing';

-- settings
SET @iCategId = (SELECT `ID` FROM `sys_options_cats` WHERE `name` = 'Listing' LIMIT 1);
DELETE FROM `sys_options` WHERE `kateg` = @iCategId;
DELETE FROM `sys_options_cats` WHERE `ID` = @iCategId;
DELETE FROM `sys_options` WHERE `Name` = 'modzzz_listing_permalinks';

-- membership levels
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Name` IN('listing view listing', 'listing browse', 'listing search', 'listing add listing', 'listing comments delete and edit', 'listing edit any listing', 'listing delete any listing', 'listing mark as featured', 'listing approve listing', 'listing make claim', 'listing make inquiry' );
DELETE FROM `sys_acl_actions` WHERE `Name` IN('listing view listing', 'listing browse', 'listing search', 'listing add listing', 'listing comments delete and edit', 'listing edit any listing', 'listing delete any listing', 'listing mark as featured', 'listing approve listing', 'listing make claim', 'listing make inquiry', 'listing photos add','listing sounds add','listing videos add','listing files add','listing purchase featured');
   

-- alerts
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'modzzz_listing_profile_delete' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'modzzz_listing_media_delete' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

-- member menu
DELETE FROM `sys_menu_member` WHERE `Name` = 'modzzz_listing';

-- privacy
DELETE FROM `sys_privacy_actions` WHERE `module_uri` = 'listing';

-- subscriptions
DELETE FROM `sys_sbs_entries` USING `sys_sbs_types`, `sys_sbs_entries` WHERE `sys_sbs_types`.`id`=`sys_sbs_entries`.`subscription_id` AND `sys_sbs_types`.`unit`='modzzz_listing';
DELETE FROM `sys_sbs_types` WHERE `unit`='modzzz_listing';
 
-- email templates
DELETE FROM `sys_email_templates` WHERE `Name` IN ('modzzz_listing_featured_expire_notify','modzzz_listing_featured_admin_notify','modzzz_listing_featured_buyer_notify',
'modzzz_listing_inquiry', 'modzzz_listing_claim','modzzz_listing_claim_assign', 'modzzz_listing_invitation', 'modzzz_listing_expired', 'modzzz_listing_expiring','modzzz_listing_fan_remove','modzzz_listing_fan_become_admin','modzzz_listing_admin_become_fan',
'modzzz_listing_join_request','modzzz_listing_join_reject','modzzz_listing_join_confirm');


 

-- cron jobs
DELETE FROM `sys_cron_jobs` WHERE `Name` IN ('BxListing');