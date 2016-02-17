
-- create tables

CREATE TABLE IF NOT EXISTS `places_kml_files` (
  `pl_kml_id` int(11) NOT NULL auto_increment,
  `pl_id` int(11) NOT NULL,
  `pl_kml_name` varchar(64) collate utf8_unicode_ci NOT NULL,
  `pl_kml_file_ext` varchar(8) collate utf8_unicode_ci NOT NULL,
  `pl_kml_added` int(11) NOT NULL,
  PRIMARY KEY  (`pl_kml_id`),
  KEY `pl_id` (`pl_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `places_cmts` (
  `cmt_id` int(11) NOT NULL auto_increment,
  `cmt_parent_id` int(11) NOT NULL default '0',
  `cmt_object_id` int(11) NOT NULL default '0',
  `cmt_author_id` int(10) unsigned NOT NULL default '0',
  `cmt_text` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `cmt_mood` tinyint(4) NOT NULL default '0',
  `cmt_rate` int(11) NOT NULL default '0',
  `cmt_rate_count` int(11) NOT NULL default '0',
  `cmt_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `cmt_replies` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `places_cmts_track` (
  `cmt_system_id` int(11) NOT NULL default '0',
  `cmt_id` int(11) NOT NULL default '0',
  `cmt_rate` tinyint(4) NOT NULL default '0',
  `cmt_rate_author_id` int(10) unsigned NOT NULL default '0',
  `cmt_rate_author_nip` int(11) unsigned NOT NULL default '0',
  `cmt_rate_ts` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cmt_system_id`,`cmt_id`,`cmt_rate_author_nip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `places_config` (
  `name` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `value` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `cat` int(11) NOT NULL default '0',
  `type` enum('text','select','radio','checkbox') collate utf8_unicode_ci NOT NULL default 'text',
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('iRewriteEngine', '1', 1, 'checkbox');
INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('sBaseUri', 'places', 1, 'text');
INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('iPerPage', '10', 1, 'text');
INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('iTopTags', '50', 1, 'text');
INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('iPerWindow', '2', 1, 'text');
INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('sGoogleKey', 'Google Key Here', 1, 'text');
INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('isNotifyAdmin', 'on', '1', 'checkbox');
INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('isAutoApproval', 'on', '1', 'checkbox');
INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('version', '2.1', '1', 'text');

INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('place_view_map_control', 'small', 2, 'text');
INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('place_view_type_control', 'on', 2, 'checkbox');
INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('place_view_scale_control', '', 2, 'checkbox');
INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('place_view_overview_control', '', 2, 'checkbox');
INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('place_view_localsearch_control', 'on', 2, 'checkbox');
INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('place_view_dragable', 'on', 2, 'checkbox');

INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('place_edit_map_control', 'small', 4, 'text');
INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('place_edit_type_control', 'on', 4, 'checkbox');
INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('place_edit_scale_control', '', 4, 'checkbox');
INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('place_edit_overview_control', '', 4, 'checkbox');
INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('place_edit_localsearch_control', 'on', 4, 'checkbox');
INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('place_edit_dragable', 'on', 4, 'checkbox');

INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('places_home_dragable', 'on', 2, 'checkbox');
INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('places_home_overview_control', '', 2, 'checkbox');
INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('places_home_localsearch_control', 'on', 2, 'checkbox');
INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('places_home_scale_control', '', 2, 'checkbox');
INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('places_home_type_control', 'on', 2, 'checkbox');
INSERT IGNORE INTO `places_config` (`name`, `value`, `cat`, `type`) VALUES ('places_home_map_control', 'small', 2, 'text');



CREATE TABLE IF NOT EXISTS `places_photos` (
  `pl_img_id` int(11) NOT NULL auto_increment,
  `pl_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`pl_img_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `places_videos` (
  `pl_video_id` int(11) NOT NULL auto_increment,
  `pl_id` int(11) NOT NULL default '0',
  `pl_video_thumb` varchar(255) collate utf8_unicode_ci NOT NULL,
  `pl_video_embed` text collate utf8_unicode_ci NOT NULL,
  `pl_video_added` int(11) NOT NULL,
  PRIMARY KEY  (`pl_video_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `places_drawings` (
  `pl_id` INT NOT NULL,
  `data` TEXT NOT NULL,
  `updated` INT UNSIGNED NOT NULL,
  `created` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`pl_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `places_places` (
  `pl_id` int(11) NOT NULL auto_increment,
  `pl_thumb` int(11) NOT NULL default '0',
  `pl_author_id` bigint(8) unsigned NOT NULL default '0',
  `pl_featured` tinyint(4) NOT NULL default '0',
  `pl_name` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `pl_uri` varchar(64) collate utf8_unicode_ci NOT NULL,
  `pl_desc` text collate utf8_unicode_ci NOT NULL,
  `pl_cat` int(11) NOT NULL default '0',
  `pl_country` varchar(2) collate utf8_unicode_ci NOT NULL default '',
  `pl_city` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `pl_zip` varchar(8) collate utf8_unicode_ci NOT NULL default '',
  `pl_address` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `pl_created` datetime NOT NULL default '0000-00-00 00:00:00',
  `pl_map_lat` float NOT NULL default '0',
  `pl_map_lng` float NOT NULL default '0',
  `pl_map_zoom` float NOT NULL default '0',
  `pl_map_type` int(11) NOT NULL default '0',
  `pl_tags` varchar(255) collate utf8_unicode_ci NOT NULL,
  `pl_rss` varchar(255) collate utf8_unicode_ci NOT NULL,
  `pl_status` enum('active','approval') collate utf8_unicode_ci NOT NULL default 'active',
  `comments_count` int(11) NOT NULL default '0',
  `rate` int(11) NOT NULL default '0',
  `rate_count` int(11) NOT NULL default '0',
  PRIMARY KEY  (`pl_id`),
  KEY `pl_author_id` (`pl_author_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

ALTER TABLE `places_places` CHANGE `pl_country` `pl_country` VARCHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `places_places` ADD FULLTEXT (`pl_name`,`pl_desc`,`pl_city`,`pl_address`);

CREATE TABLE IF NOT EXISTS `places_places_cat` (
  `pl_cat_id` int(11) NOT NULL auto_increment,
  `pl_cat_name` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `pl_cat_icon` varchar(8) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`pl_cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6;

INSERT IGNORE INTO `places_places_cat` (`pl_cat_id`, `pl_cat_name`) VALUES (1, 'Places Please Select');
INSERT IGNORE INTO `places_places_cat` (`pl_cat_id`, `pl_cat_name`) VALUES (2, 'Places Diving');
INSERT IGNORE INTO `places_places_cat` (`pl_cat_id`, `pl_cat_name`) VALUES (3, 'Places Recreation');
INSERT IGNORE INTO `places_places_cat` (`pl_cat_id`, `pl_cat_name`) VALUES (4, 'Places Restaurant');
INSERT IGNORE INTO `places_places_cat` (`pl_cat_id`, `pl_cat_name`) VALUES (5, 'Places Cafe');

CREATE TABLE IF NOT EXISTS `places_rating` (
  `places_id` int(12) NOT NULL default '0',
  `places_rating_count` int(11) NOT NULL default '0',
  `places_rating_sum` int(11) NOT NULL default '0',
  UNIQUE KEY `med_id` (`places_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `places_voting_track` (
  `places_id` int(12) NOT NULL default '0',
  `places_ip` varchar(20) default NULL,
  `places_date` datetime default NULL,
  KEY `med_ip` (`places_ip`,`places_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `places_locations` (
  `gmk_id` int(11) NOT NULL default '0',
  `gmk_lat` float NOT NULL default '0',
  `gmk_lng` float NOT NULL default '0',
  `gmk_zoom` float NOT NULL default '0',
  `gmk_type` int(11) NOT NULL default '0',
  PRIMARY KEY  (`gmk_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT IGNORE INTO `places_locations` VALUES (100, -26.7456, 137.109, 4, 0);

-- vote objects
INSERT INTO `sys_objects_vote` (`ID`, `ObjectName`, `TableRating`, `TableTrack`, `RowPrefix`, `MaxVotes`, `PostName`, `IsDuplicate`, `IsOn`, `className`, `classFile`, `TriggerTable`, `TriggerFieldRate`, `TriggerFieldRateCount`, `TriggerFieldId`, `OverrideClassName`, `OverrideClassFile`) VALUES
(NULL, 'places', 'places_rating', 'places_voting_track', 'places_', '5', 'vote_send_result', 'BX_PERIOD_PER_VOTE', '1', '', '', 'places_places', 'rate', 'rate_count', 'pl_id', '', '');

-- comments objects
INSERT INTO `sys_objects_cmts` (`ID`, `ObjectName`, `TableCmts`, `TableTrack`, `AllowTags`, `Nl2br`, `SecToEdit`, `PerView`, `IsRatable`, `ViewingThreshold`, `AnimationEffect`, `AnimationSpeed`, `IsOn`, `IsMood`, `RootStylePrefix`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
(NULL, 'places', 'places_cmts', 'places_cmts_track', '0', '1', '90', '5', '1', '-3', 'slide', '2000', '1', '0', 'cmt', 'places_places', 'pl_id', 'comments_count', '', '');

-- tag objects
INSERT INTO `sys_objects_tag` (`ID`, `ObjectName`, `Query`, `PermalinkParam`, `EnabledPermalink`, `DisabledPermalink`, `LangKey`) VALUES
(NULL, 'places', 'SELECT `pl_tags` FROM `places_places` WHERE `pl_id` = {iID} AND `pl_status` = ''active''', 'places_permalinks', 'places/browse_by_tag/{tag}', 'modules/kolimarfey/places.php?browse_by_tag/{tag}', '_Places');

-- search objects
INSERT INTO `sys_objects_search` (`ID`, `ObjectName`, `Title`, `ClassName`, `ClassPath`) VALUES
(NULL, 'places', '_Places', 'PlacesSearchResult', 'modules/kolimarfey/places/application/PlacesSearchResult.php');

-- settings
SET @iMaxOrder = (SELECT `menu_order` + 1 FROM `sys_options_cats` ORDER BY `menu_order` DESC LIMIT 1);
INSERT INTO `sys_options_cats` (`name`, `menu_order`) VALUES ('Places', @iMaxOrder);
SET @iCategId = (SELECT LAST_INSERT_ID());
INSERT INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`) VALUES
('places_permalinks', 'on', 26, 'Enable friendly permalinks in Kolimarfey Places', 'checkbox', '', '', '0', ''); 

-- users actions
INSERT INTO `sys_objects_actions` (`Caption`, `Icon`, `Url`, `Script`, `Eval`, `Order`, `Type`) VALUES 
    ('{evalResult}', 'modules/kolimarfey/places/|add.png', '{BaseUri}add', '', 'return $GLOBALS[''logged''][''member''] || $GLOBALS[''logged''][''admin''] ? _t(''_Places Add Place'') : '''';', '1', 'places_title'),
    ('{evalResult}', 'modules/kolimarfey/places/|icon16.png', '{BaseUri}user/{Username}', '', 'return $GLOBALS[''logged''][''member''] || $GLOBALS[''logged''][''admin''] ? _t(''_Places My Places'') : '''';', '2', 'places_title'),
    ('{evalResult}', 'modules/kolimarfey/places/|home.png', '{BaseUri}index/', '', 'return $GLOBALS[''logged''][''member''] || $GLOBALS[''logged''][''admin''] ? _t(''_Places Home'') : '''';', '2', 'places_title');

-- site stats
INSERT INTO `sys_stat_site` (`Name`, `Title`, `UserLink`, `UserQuery`, `AdminLink`, `AdminQuery`, `IconName`, `StatOrder`) VALUES
('places', 'Places', 'places/browse/latest', 'SELECT COUNT(`pl_id`) FROM `places_places` WHERE `pl_status` = ''active''', '../places/pending', 'SELECT COUNT(`pl_id`) FROM `places_places` WHERE `pl_status` != ''active''', 'modules/kolimarfey/places/|icon16.png', 0);

-- page builder

SET @iWidth = (SELECT `VALUE` FROM `sys_options` WHERE `Name` = 'main_div_width');
SET @iMax = (SELECT MAX( `Order` ) FROM `sys_page_compose_pages`);

INSERT IGNORE INTO `sys_page_compose_pages` (`Name`, `Title`, `Order`, `System`) VALUES
('places_view', 'Place View', @iMax+1, 1),
('places_index', 'Places Index', @iMax+2, 1);


INSERT IGNORE INTO `sys_page_compose` (`ID`, `Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`) VALUES
(NULL, 'places_view', @iWidth, 'Actions', '_Places Box Actions', 1, 0, 'Actions', '', 1, 38, 'non,memb', 0),
(NULL, 'places_view', @iWidth, 'Rate', '_Places Box Rate', 1, 1, 'Rate', '', 1, 38, 'non,memb', 0),
(NULL, 'places_view', @iWidth, 'Info', '_Places Box Info', 1, 2, 'Info', '', 1, 38, 'non,memb', 0),
(NULL, 'places_view', @iWidth, 'Full Description', '_Places Description', 1, 3, 'Description','',1,38,'non,memb',0),
(NULL, 'places_view', @iWidth, 'Comments', '_Places Box Comments', 1, 4, 'Comments', '', 1, 38, 'non,memb', 0),
(NULL, 'places_view', @iWidth, 'RSS', '_Places Box Rss', 1, 5, 'Rss', '', 1, 38, 'non,memb', 0),

(NULL, 'places_view', @iWidth, 'Map', '_Places Box Map', 2, 0, 'Map', '', 1, 62, 'non,memb', 0),
(NULL, 'places_view', @iWidth, 'Photos', '_Places Box Photos', 2, 1, 'Photos', '', 1, 62, 'non,memb', 0),
(NULL, 'places_view', @iWidth, 'Videos', '_Places Box Videos', 2, 2, 'Videos', '', 1, 62, 'non,memb', 0),

(NULL, 'places_index', @iWidth, 'Latest Places', '_Places Browse latest', 1, 0, 'Latest', '', 1, 100, 'non,memb',0),
(NULL, 'places_index', @iWidth, 'Map', '_Places Box Map Index', 1, 2, 'Map', '', 1, 100, 'non,memb', 0),
(NULL, 'places_index', @iWidth, 'Best Places', '_Places Browse best', 1, 1, 'Best', '', 1, 100, 'non,memb', 0),
(NULL, 'places_index', @iWidth, 'Featured Places', '_Places Browse featured', 0, 0, 'Featured', '', 1, 100, 'non,memb', 0),
(NULL, 'places_index', @iWidth, 'Places Search', '_Places Box Search Homepage', 0, 0, 'PHP', '$sKAction = ''include_search_index'';\r\ninclude(BX_DIRECTORY_PATH_MODULES . ''kolimarfey/places/places.php'');', 1, 100, 'non,memb', 0),

(NULL, 'profile', @iWidth, 'Member''s places', '_Places Members places', 0, 0, 'PHP', '$sKAction = ''include_members_places'';\r\ninclude(BX_DIRECTORY_PATH_MODULES . ''kolimarfey/places/places.php'');', 1, 50, 'non,memb', 0),
(NULL, 'index', @iWidth, 'Latest Places', '_Places Latest Places', 0, 0, 'PHP', '$sKAction = ''include_latest_places'';\r\ninclude(BX_DIRECTORY_PATH_MODULES . ''kolimarfey/places/places.php'');', 1, 40, 'non,memb', 0),
(NULL, 'index', @iWidth, 'Best Places', '_Places Best Places', 0, 0, 'PHP', '$sKAction = ''include_best_places'';\r\ninclude(BX_DIRECTORY_PATH_MODULES . ''kolimarfey/places/places.php'');', 1, 40, 'non,memb', 0),
(NULL, 'index', @iWidth, 'Featured Places', '_Places Featured Places', 0, 0, 'PHP', '$sKAction = ''include_featured_places'';\r\ninclude(BX_DIRECTORY_PATH_MODULES . ''kolimarfey/places/places.php'');', 1, 40, 'non,memb', 0),
(NULL, 'index', @iWidth, 'Places Map', '_Places Box Map Homepage', 0, 0, 'PHP', '$sKAction = ''include_map_index'';\r\ninclude(BX_DIRECTORY_PATH_MODULES . ''kolimarfey/places/places.php'');', 1, 40, 'non,memb', 0),
(NULL, 'index', @iWidth, 'Places Search', '_Places Box Search Homepage', 0, 0, 'PHP', '$sKAction = ''include_search_index'';\r\ninclude(BX_DIRECTORY_PATH_MODULES . ''kolimarfey/places/places.php'');', 1, 40, 'non,memb', 0);

-- top menu

SET @iMax = (SELECT MAX( `Order` ) FROM `sys_menu_top`);

INSERT INTO `sys_menu_top` (`ID`, `Parent`, `Name`, `Caption`, `Link`, `Order`, `Visible`, `Target`, `Onclick`, `Check`, `Editable`, `Deletable`, `Active`, `Type`, `Picture`) VALUES
(NULL, '0', 'Places', '_Places', 'places/', @iMax+1, 'non,memb', '', '', '', '1', '1', '1', 'top', 'modules/kolimarfey/places/|icon32.png');

SET @iId = (SELECT LAST_INSERT_ID());

INSERT INTO `sys_menu_top` (`ID`, `Parent`, `Name`, `Caption`, `Link`, `Order`, `Visible`, `Target`, `Onclick`, `Check`, `Editable`, `Deletable`, `Active`, `Type`) VALUES
(NULL, @iId, 'Places Home', '_Places Home', 'places/index/', '2', 'non,memb', '', '', '', '1', '1', '1', 'custom'),
(NULL, @iId, 'Browse Latest', '_Places Browse latest', 'places/browse/latest', '4', 'non,memb', '', '', '', '1', '1', '1', 'custom'),
(NULL, @iId, 'Browse Best', '_Places Browse best', 'places/browse/best', '6', 'non,memb', '', '', '', '1', '1', '1', 'custom'),
(NULL, @iId, 'Browse Featured', '_Places Browse featured', 'places/browse/featured', '8', 'non,memb', '', '', '', '1', '1', '1', 'custom'),
(NULL, @iId, 'Drilldown', '_Places Drilldown', 'places/drilldown', '10', 'non,memb', '', '', '', '1', '1', '1', 'custom'),
(NULL, @iId, 'Tags', '_Places Tags', 'places/tags', '12', 'non,memb', '', '', '', '1', '1', '1', 'custom'),
(NULL, @iId, 'Search Places', '_Places Search', 'places/search', '14', 'non,memb', '', '', '', '1', '1', '1', 'custom'),
(NULL, @iId, 'My Places', '_Places My Places', 'places/user/{memberNick}', '16', 'memb', '', '', 'return $GLOBALS[''logged''][''member''] || $GLOBALS[''logged''][''admin''] ? true : false;', '1', '1', '1', 'custom'),
(NULL, @iId, 'Place Add', '_Places Add Place', 'places/add', '18', 'memb', '', '', 'return $GLOBALS[''logged''][''member''] || $GLOBALS[''logged''][''admin''] ? true : false;', '1', '1', '1', 'custom');

-- admin menu

SET @iMax = (SELECT MAX(`order`) FROM `sys_menu_admin` WHERE `parent_id` = '2');
INSERT IGNORE INTO `sys_menu_admin` (`parent_id`, `name`, `title`, `url`, `description`, `icon`, `order`) VALUES
(2, 'Places', '_Places', '{siteUrl}places/administration/', 'Kolimarfey Places', 'modules/kolimarfey/places/|icon16.png', @iMax+1);

-- membership levels

SET @iLevelNonMember := 1;
SET @iLevelStandard := 2;
SET @iLevelPromotion := 3;

INSERT INTO `sys_acl_actions` VALUES (NULL, 'places view', NULL);
SET @iAction := LAST_INSERT_ID();
INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES 
    (@iLevelNonMember, @iAction), (@iLevelStandard, @iAction), (@iLevelPromotion, @iAction);

INSERT INTO `sys_acl_actions` VALUES (NULL, 'places browse', NULL);
SET @iAction := LAST_INSERT_ID();
INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES 
    (@iLevelNonMember, @iAction), (@iLevelStandard, @iAction), (@iLevelPromotion, @iAction);

INSERT INTO `sys_acl_actions` VALUES (NULL, 'places add', NULL);
SET @iAction := LAST_INSERT_ID();
INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES 
    (@iLevelStandard, @iAction), (@iLevelPromotion, @iAction);

INSERT INTO `sys_acl_actions` VALUES (NULL, 'places add rss', NULL);
SET @iAction := LAST_INSERT_ID();
INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES 
    (@iLevelStandard, @iAction), (@iLevelPromotion, @iAction);

INSERT INTO `sys_acl_actions` VALUES (NULL, 'places administration', NULL);


