-- create tables  
 
CREATE TABLE IF NOT EXISTS `[db_prefix]main` (
  `id` smallint(6) NOT NULL auto_increment,
  `icon` varchar(50) NOT NULL default '',
  `large_icon` varchar(50) NOT NULL default '', 
  `membership_id` int(11) NOT NULL default '0', 
  `created` int(11) NOT NULL default '0', 
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
 

INSERT INTO `[db_prefix]main` (`large_icon`, `membership_id`) SELECT `Icon`, `ID` FROM `sys_acl_levels`;
 
-- page compose pages
SET @iMaxOrder = (SELECT `Order` FROM `sys_page_compose_pages` ORDER BY `Order` DESC LIMIT 1);
INSERT INTO `sys_page_compose_pages` (`Name`, `Title`, `Order`) VALUES ('modzzz_badge_main', 'Badge Home', @iMaxOrder+1);
 
-- page compose blocks
INSERT INTO `sys_page_compose` (`Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`) VALUES 
 
    ('modzzz_badge_main', '998px', 'badge listings block', '_modzzz_badge_block_badge', '1', '0', 'Badge', '', '1', '100', 'non,memb', '0');
   
  
-- permalinkU
INSERT INTO `sys_permalinks` VALUES (NULL, 'modules/?r=badge/', 'm/badge/', 'modzzz_badge_permalinks');

-- settings
SET @iMaxOrder = (SELECT `menu_order` + 1 FROM `sys_options_cats` ORDER BY `menu_order` DESC LIMIT 1);
INSERT INTO `sys_options_cats` (`name`, `menu_order`) VALUES ('Badge', @iMaxOrder);
SET @iCategId = (SELECT LAST_INSERT_ID());
INSERT INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`) VALUES
('modzzz_badge_permalinks', 'on', 26, 'Enable friendly permalinks in badge', 'checkbox', '', '', '0', ''), 
('modzzz_badge_orientation', 'bottom_left', @iCategId, 'overlay position of membership icon on thumbnail', 'select', '', '', '0', 'bottom_left,bottom_right,top_left,top_right'),   
('modzzz_badge_icon_width', '15', @iCategId, 'Badge Icon Width', 'digit', '', '', '0', ''),
('modzzz_badge_icon_height', '15', @iCategId, 'Badge Icon Height', 'digit', '', '', '0', ''),  
('modzzz_badge_activated', 'on', @iCategId, 'Badge mod activated', 'checkbox', '', '', '0', '') 
;
 
 
-- admin menu
SET @iMax = (SELECT MAX(`order`) FROM `sys_menu_admin` WHERE `parent_id` = '2');
INSERT IGNORE INTO `sys_menu_admin` (`parent_id`, `name`, `title`, `url`, `description`, `icon`, `order`) VALUES
(2, 'modzzz_badge', '_modzzz_badge', '{siteUrl}modules/?r=badge/administration/', 'Badge module by Modzzz','modules/modzzz/badge/|badge.png', @iMax+1);

 INSERT INTO `sys_page_compose` (`Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`) VALUES
    ('profile', '998px', 'Membership Badge', '_modzzz_badge_membership_badge', 1, 2, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''badge'', ''get_member_level'', array($this->oProfileGen->_iProfileID));', 1, 34, 'non,memb', 0), 
    ('member', '998px', 'Membership Badge', '_modzzz_badge_membership_badge', 1, 0, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''badge'', ''get_member_level'');', 1, 34, 'non,memb', 0);
