SET @sPluginName = 'aqb_pcomposer';
SET @sPluginTitle = 'AQB Profile Composer';

SET @iOrder = (SELECT MAX(`order`) FROM `sys_menu_admin` WHERE `parent_id`='2');
INSERT INTO `sys_menu_admin`(`parent_id`, `name`, `title`, `url`, `description`, `icon`, `icon_large`, `check`, `order`) VALUES
(2, @sPluginName, '_aqb_pcomposer_admin', '{siteUrl}modules/?r=aqb_pcomposer/administration/', 'AQB Profile Composer admin panel', 'modules/aqb/pcomposer/|pc_menu_icon.png', '', '', @iOrder + 1);

INSERT INTO `sys_permalinks`(`standard`, `permalink`, `check`) VALUES
('modules/?r=aqb_pcomposer/', 'm/aqb_pcomposer/', 'permalinks_module_aqb_pcomposer');

SET @iMaxOrder = (SELECT `menu_order` + 1 FROM `sys_options_cats` ORDER BY `menu_order` DESC LIMIT 1);
INSERT INTO `sys_options_cats` (`name`, `menu_order`) VALUES (@sPluginTitle, @iMaxOrder);
SET @iCategId = (SELECT LAST_INSERT_ID());
INSERT INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`) VALUES
('aqb_pc_allow_to_create_text', 'on', @iCategId, 'Allow members to create <b>Text</b> blocks', 'checkbox', '', '', 2),
('aqb_pc_autoapp_text', '', @iCategId, 'Auto approve <b>text</b> blocks', 'checkbox', '', '', 3),
('aqb_pc_allow_create_html', 'on', @iCategId, 'Allow members to create <b>HTML</b> blocks', 'checkbox', '', '', 4),
('aqb_pc_autoapp_html', '', @iCategId, 'Auto approve for <b>html</b> blocks', 'checkbox', '', '', 5),
('aqb_pc_allow_create_rss', 'on', @iCategId, 'Allow members to create <b>RSS</b> blocks', 'checkbox', '', '', 6),
('aqb_pc_autoapp_rss', '', @iCategId, 'Auto approve for <b>RSS</b> blocks', 'checkbox', '', '', 7),
('aqb_pc_allowed_blocks_num', '50', @iCategId, 'Allowed blocks number which each member can create', 'digit', '', '', 8),
('aqb_pc_allow_share', 'on', @iCategId, 'Allow members to share blocks with other members', 'checkbox', '', '', 9),
('aqb_pc_num_rss_rec', '10', @iCategId, 'Items number in RSS blocks', 'digit', '', '', 10),
('aqb_pc_max_sym_num_title', '100', @iCategId, 'Max number of characters in block''s title', 'digit', '', '', 11),
('aqb_pc_max_sym_num', '2000', @iCategId, 'Max number of characters which member can use for block body', 'digit', '', '', 12),
('aqb_pc_allow_to_remove_stand', 'on', @iCategId, 'Allow members to remove standard blocks', 'checkbox', '', '', 13),
('aqb_pc_allow_to_change_privacy', 'on', @iCategId, 'Allow members to change blocks privacy', 'checkbox', '', '', 14),
('aqb_pc_num_blocks_on_page', '10', @iCategId, 'Number of blocks on blocks page', 'digit', '', '', 15),
('aqb_pc_allow_to_remove_own_blocks', '', @iCategId, 'Allow members to remove their own blocks from the site', 'checkbox', '', '', 16),
('aqb_pc_allow_to_edit_block', 'on', @iCategId, 'Allow members to edit their blocks', 'checkbox', '', '', 17);

INSERT INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`) VALUES
('permalinks_module_aqb_pcomposer', 'on', 26, 'Enable friendly permalinks in Profile Composer', 'checkbox', '', '', 1);

INSERT INTO `sys_alerts_handlers` VALUES (NULL, 'aqb_pc_load_alert', 'AqbPCAlertsResponse', 'modules/aqb/pcomposer/classes/AqbPCAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();
INSERT INTO `sys_alerts` VALUES (NULL, 'profile', 'load', @iHandler);


CREATE TABLE `[db_prefix]members_blocks` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `content` text NOT NULL default '',  
  `share` enum('1','0') NOT NULL default '0',
  `approved` enum('1','0') NOT NULL default '0',
  `unmovable` enum('1','0') NOT NULL default '0',
  `irremovable` enum('1','0') NOT NULL default '0',
  `uncollapsable` enum('1','0') NOT NULL default '0',
  `collapsed_def` enum('1','0') NOT NULL default '0',
  `title` varchar(250) NOT NULL default '',
  `owner_id` int(11) unsigned NOT NULL default '0',
  `visible_group` int(5) unsigned NOT NULL default '0',
  `visible` SET(  'non',  'memb' ) NOT NULL DEFAULT  'non,memb',
  `column` tinyint(2) NOT NULL default '1',
  `order` int(11) NOT NULL default '0',
  `type` enum('text','html','rss') NOT NULL default 'text',
  `date` int(11) NOT NULL default '0',
   PRIMARY KEY  (`id`),
   FULLTEXT KEY (`content`,`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE `[db_prefix]standard_blocks` (
  `id` int(11) unsigned NOT NULL default '0',
  `unmovable` enum('1','0') NOT NULL default '0',
  `irremovable` enum('1','0') NOT NULL default '0',
  `uncollapsable` enum('1','0') NOT NULL default '0',
  `visible_group` int(5) unsigned NOT NULL default '1',
  `collapsed_def` enum('1','0') NOT NULL default '0',
   PRIMARY KEY  (`id`),
   UNIQUE KEY  `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `[db_prefix]standard_blocks` (`id`) SELECT `ID` FROM `sys_page_compose` WHERE `Page` = 'profile' AND `Column` > 0;

CREATE TABLE `[db_prefix]profiles_info` (
  `member_id` int(11) NOT NULL default '0',
  `page` text NOT NULL default '',
  PRIMARY KEY  (`member_id`),
  UNIQUE KEY `member_id` (`member_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

SET @iOrder := (SELECT MAX(`Order`) FROM `sys_objects_actions` WHERE `Type`='Profile' LIMIT 1);
INSERT INTO `sys_objects_actions` (`Caption`, `Icon`, `Url`, `Script`, `Eval`, `Order`, `Type`, `bDisplayInSubMenuHeader`) VALUES
('{evalResult}', 'modules/aqb/pcomposer/|new_block.png', '', 'AqbPCMain.showPopup(''modules/?r=aqb_pcomposer/get_create_block_form/{ID}'');', '$isAllowed = BxDolService::call(''aqb_pcomposer'', ''is_create_blocks_allowed''); if (({ID} == {member_id} || $GLOBALS[''logged''][''admin'']) && $isAllowed) return _t(''_aqb_pc_add_new_block'');', @iOrder + 1, 'Profile', 0);

SET @iOrder := (SELECT Max(`Order`) FROM `sys_menu_top` WHERE `Parent` = '4' LIMIT 1);
INSERT INTO `sys_menu_top` (`Parent`, `Name`, `Caption`, `Link`, `Order`, `Visible`, `Target`, `Onclick`, `Check`, `Editable`, `Deletable`, `Active`, `Type`, `Picture`, `BQuickLink`, `Statistics`) VALUES
(4, 'Profile Blocks', '_aqb_pc_profile_blocks', 'modules/?r=aqb_pcomposer/profile_blocks', @iOrder + 1, 'memb', '', '', '', 1, 1, 1, 'custom', '', 0, '');

SET @iOrder := (SELECT Max(`Order`) FROM `sys_menu_top` WHERE `Parent` = '4' LIMIT 1);
INSERT INTO `sys_menu_top` (`Parent`, `Name`, `Caption`, `Link`, `Order`, `Visible`, `Target`, `Onclick`, `Check`, `Editable`, `Deletable`, `Active`, `Type`, `Picture`, `BQuickLink`, `Statistics`) VALUES
(4, 'Approve Blocks', '_aqb_pc_approve_blocks', 'modules/?r=aqb_pcomposer/approve_blocks', @iOrder + 1, 'memb', '', '', '', 1, 1, 1, 'custom', '', 0, '');