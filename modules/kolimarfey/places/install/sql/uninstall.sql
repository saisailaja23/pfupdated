
-- delete tables

DROP TABLE IF EXISTS `places_kml_files`;

DROP TABLE IF EXISTS `places_cmts`;

DROP TABLE IF EXISTS `places_cmts_track`;

DROP TABLE IF EXISTS `places_config`;

DROP TABLE IF EXISTS `places_photos`;

DROP TABLE IF EXISTS `places_videos`;

DROP TABLE IF EXISTS `places_places`;

DROP TABLE IF EXISTS `places_places_cat`;

DROP TABLE IF EXISTS `places_rating`;

DROP TABLE IF EXISTS `places_voting_track`;

DROP TABLE IF EXISTS `places_drawings`;

DROP TABLE IF EXISTS `places_locations`;

-- vote objects
DELETE FROM `sys_objects_vote` WHERE `ObjectName` = 'places';

-- comments objects
DELETE FROM `sys_objects_cmts` WHERE `ObjectName` = 'places';

-- tag objects
DELETE FROM `sys_objects_tag` WHERE `ObjectName` = 'places';
DELETE FROM `sys_tags` WHERE `Type` = 'places';

-- search objects
DELETE FROM `sys_objects_search` WHERE `ObjectName` = 'places';

-- settings
SET @iCategId = (SELECT `ID` FROM `sys_options_cats` WHERE `name` = 'Places' LIMIT 1);
DELETE FROM `sys_options` WHERE `kateg` = @iCategId;
DELETE FROM `sys_options_cats` WHERE `ID` = @iCategId;
DELETE FROM `sys_options` WHERE `Name` = 'places_permalinks';

-- users actions
DELETE FROM `sys_objects_actions` WHERE `Type` = 'places_title';

-- site stats
DELETE FROM `sys_stat_site` WHERE `Name` = 'places';

-- page builder
DELETE FROM `sys_page_compose_pages` WHERE `Name` = 'Place View';
DELETE FROM `sys_page_compose_pages` WHERE `Name` = 'Place View';

DELETE FROM `sys_page_compose` WHERE `Page` = 'Place View';
DELETE FROM `sys_page_compose` WHERE `Page` = 'Places Index';
DELETE FROM `sys_page_compose` WHERE `Page` = 'profile' AND `Desc` = 'Member''s places';
DELETE FROM `sys_page_compose` WHERE `Page` = 'index' AND `Desc` = 'Latest Places';
DELETE FROM `sys_page_compose` WHERE `Page` = 'index' AND `Desc` = 'Best Places';
DELETE FROM `sys_page_compose` WHERE `Page` = 'index' AND `Desc` = 'Places Map';
DELETE FROM `sys_page_compose` WHERE `Page` = 'index' AND `Desc` = 'Places Search';
DELETE FROM `sys_page_compose` WHERE `Page` = 'index' AND `Desc` = 'Featured Places';

-- top menu
DELETE FROM `sys_menu_top` WHERE `Name` = 'Places' AND `Link` = 'places/';
DELETE FROM `sys_menu_top` WHERE `Name` = 'Places Home' AND `Link` = 'places/';
DELETE FROM `sys_menu_top` WHERE `Name` = 'Place Add' AND `Link` = 'places/add';
DELETE FROM `sys_menu_top` WHERE `Name` = 'Browse Latest' AND `Link` = 'places/browse/latest';
DELETE FROM `sys_menu_top` WHERE `Name` = 'Browse Best' AND `Link` = 'places/browse/best';
DELETE FROM `sys_menu_top` WHERE `Name` = 'Drilldown' AND `Link` = 'places/drilldown';
DELETE FROM `sys_menu_top` WHERE `Name` = 'Search Places' AND `Link` = 'places/search';
DELETE FROM `sys_menu_top` WHERE `Name` = 'My Places' AND `Link` = 'places/user/{memberNick}';

-- admin menu

DELETE FROM `sys_menu_admin` WHERE `name` = 'Places' AND `Url` = '{siteUrl}places/administration/';

-- membership levels

DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Name` IN('places view', 'places browse', 'places add', 'places administration', 'places add rss');
DELETE FROM `sys_acl_actions` WHERE `Name` IN('places view', 'places browse', 'places add', 'places administration', 'places add rss');

