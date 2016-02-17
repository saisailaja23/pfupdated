-- admin menu
SET @iExtCat = (SELECT `id` FROM `sys_menu_admin` WHERE `name`='extensions');
SET @iExtOrd = (SELECT MAX(`order`) FROM `sys_menu_admin` WHERE `name`='extensions');
INSERT INTO `sys_menu_admin` (`id`, `parent_id`, `name`, `title`, `url`, `description`, `icon`, `icon_large`, `check`, `order`) VALUES
(NULL, @iExtCat, 'HeadInjections', '_dbcs_HI_bx_HeadInjections', '{siteUrl}modules/?r=Head_Injections/administration/', 'Block Tools', 'modules/deano/head_injections/|head_injections.png', '', '', @iExtOrd+1);

CREATE TABLE IF NOT EXISTS `dbcsHeadInjections` (
  `id` int(11) NOT NULL auto_increment,
  `page_title` varchar(255) NOT NULL,
  `injection_text` text NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

