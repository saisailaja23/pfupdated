-- module database
CREATE TABLE IF NOT EXISTS `[db_prefix]tours` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tour_name` varchar(100) default NULL,
  `path_name` varchar(255) NOT NULL default '',
  `is_guest` tinyint(1) NOT NULL default 1,
  `auto_play` tinyint(1) NOT NULL default 0,
  `overlay_opacity` int(3) NOT NULL default 20,
  `overlay_cancel` tinyint(1) NOT NULL default 1,
  `active` tinyint(1) NOT NULL default 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `[db_prefix]stations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tour_id` int(10) NOT NULL default 0,
  `num` int(10) NOT NULL default 0,
  `sel` text default NULL,
  `msg` text default NULL,  
  `delay` int(10) NOT NULL default 0,
  `position` char(2) NOT NULL default '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `[db_prefix]viewed` (
  `mem_id` int(10) unsigned NOT NULL,
  `tour_id` int(10) unsigned NOT NULL default 0,
  `viewed` tinyint(1) NOT NULL default 0,
  `hide` tinyint(1) NOT NULL default 0,
  PRIMARY KEY (`mem_id`, `tour_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- admin menu
SET @iMax = (SELECT MAX(`order`) FROM `sys_menu_admin` WHERE `parent_id` = '2');
INSERT IGNORE INTO `sys_menu_admin` (`parent_id`, `name`, `title`, `url`, `description`, `icon`, `order`) VALUES
(2, 'Tour Guide Management', '_yn_tour_menu_admin', '{siteUrl}modules/?r=tour_guide/administration/', 'Tour Guide Management module by YouNet Developments','modules/younet/tour_guide/|tour_guide_16.png', @iMax+1);

INSERT IGNORE INTO `sys_injections` (`id`, `name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES
('', 'tour_guide_core', 0, 'injection_header', 'text', '<script type="text/javascript">$.getScript(site_url + ''modules/younet/tour_guide/js/tour_guide_core.js'');</script>', 0, 1);

-- permalink
INSERT IGNORE INTO `sys_permalinks` VALUES 
(NULL, 'modules/?r=tour_guide', 'm/tour_guide', 'yn_tour_guide_permalinks'),
(NULL, 'modules/?r=tour_guide/', 'm/tour_guide/', 'yn_tour_guide_permalinks');

-- option permalinks
INSERT IGNORE INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`) VALUES
('yn_tour_guide_permalinks', 'on', 26, 'Enable friendly tour guide permalinks', 'checkbox', '', '', NULL);

-- module settings
SET @iMaxOrder = (SELECT `menu_order` + 1 FROM `sys_options_cats` ORDER BY `menu_order` DESC LIMIT 1);
INSERT IGNORE INTO `sys_options_cats` (`name`, `menu_order`) VALUES ('Younet Tour Guide', @iMaxOrder);
SET @iCategId = (SELECT LAST_INSERT_ID());
INSERT IGNORE INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`)  VALUES
('management_mode_enable', '', @iCategId, 'Guest Mode', 'checkbox', '', '', 1, ''),
('management_mode_pass', '123456', @iCategId, 'Guest Password', 'digit', '', '', 2, '');