----------------------------------------------------------
-- Injections
----------------------------------------------------------
INSERT INTO `sys_injections` (`name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES ('memberships', '0', 'injection_head', 'php', 'require_once(BX_DIRECTORY_PATH_MODULES.''harvest/memberships/include.php'');', '0', '1');

----------------------------------------------------------
-- Pages
----------------------------------------------------------
SET @iMaxOrder = (SELECT `Order` + 1 FROM `sys_page_compose_pages` ORDER BY `Order` DESC LIMIT 1);
INSERT INTO `sys_page_compose_pages` (`Name`, `Title`, `Order`) VALUES ('dolphin_subs_main', 'Dolphin Subscriptions', @iMaxOrder+2);

-- page compose blocks
INSERT INTO `sys_page_compose` (`Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`) VALUES 
  	('dolphin_subs_main', '998px', 'Tight block', '_dol_subs_block_tight', '1', '0', 'Tight', '', '1', '34', 'memb', '0'),
    ('dolphin_subs_main', '998px', 'Wide block', '_dol_subs_block_wide', '2', '0', 'Wide', '', '1', '66', 'memb', '0');

----------------------------------------------------------
-- Options
----------------------------------------------------------

SET @iMaxOrder = (SELECT `menu_order` + 1 FROM `sys_options_cats` ORDER BY `menu_order` DESC LIMIT 1);
INSERT INTO `sys_options_cats` (`name`, `menu_order`) VALUES ('Dolphin Subscriptions', @iMaxOrder);
SET @iCategId = (SELECT LAST_INSERT_ID());
INSERT INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`) VALUES
('dol_subs_permalinks', 'on', 26, 'Enable friendly permalinks in Dolphin Subscriptions', 'checkbox', '', '', '0', '');
UPDATE `sys_options` SET `VALUE` = '' WHERE `Name` = 'enable_promotion_membership';

----------------------------------------------------------
-- Permalinks
----------------------------------------------------------

INSERT INTO `sys_permalinks` VALUES (NULL, 'modules/?r=memberships/', 'm/memberships/', 'dol_subs_permalinks');

----------------------------------------------------------
-- Languages/Strings
----------------------------------------------------------
-- Upgrade Membership Default Link 
SET @keyID = (SELECT `ID` FROM `sys_localization_keys` WHERE `Key`='_MEMBERSHIP_UPGRADE_FROM_STANDARD');
UPDATE `sys_localization_strings` SET `String`='<a href="m/memberships/"><div style="margin:8px 0px">View Membership Info</div></a>' WHERE `IDKey`=@keyID;
SET @keyID2 = (SELECT `ID` FROM `sys_localization_keys` WHERE `Key`='_MEMBERSHIP_EXPIRES_NEVER');
UPDATE `sys_localization_strings` SET `String`='<a href="m/memberships/"><div style="margin:8px 0px">View Membership Info</div></a>' WHERE `IDKey`=@keyID2;

--Update new membership items
INSERT INTO `sys_menu_top` (`ID`, `Parent`, `Name`, `Caption`, `Link`, `Order`, `Visible`, `Target`, `Onclick`, `Check`, `Editable`, `Deletable`, `Active`, `Type`, `Picture`, `Icon`, `BQuickLink`, `Statistics`) VALUES
(NULL, 118, 'Memberships', '_dol_subs_mem_info', 'm/memberships/', 4, 'memb', '', '', '', 1, 1, 1, 'custom', '', '', 0, '');
UPDATE sys_menu_top SET Link='m/memberships/' WHERE Name='Memberships';

--Upgrade Error Messages
UPDATE `sys_localization_strings` SET `String`='<div style="width: 80%">Your current membership doesn\'t allow this action. <a href="m/memberships/">Click Here</a> to upgrade your account.</div>' WHERE `IDKey`=840;

----------------------------------------------------------
-- Removals
----------------------------------------------------------
DELETE FROM `sys_menu_top` WHERE `Name`='My Membership';
DELETE FROM `sys_menu_member` WHERE `Name`='bx_membership' LIMIT 1;

----------------------------------------------------------
-- Admin Menu
----------------------------------------------------------
-- Admin Module Menu
SET @iMax = (SELECT MAX(`order`) FROM `sys_menu_admin` WHERE `parent_id` = '2');
INSERT IGNORE INTO `sys_menu_admin` (`parent_id`, `name`, `title`, `url`, `description`, `icon`, `order`) VALUES
(2, 'dol_subs', '_dol_subs_memberships', '{siteUrl}modules/?r=memberships/settings/', 'Dolphin Subscriptions', 'modules/harvest/memberships/|icon.png', @iMax+1);

-- Admin Main Menu
SET @iMaxId = (SELECT MAX(`id`) FROM `sys_menu_admin`);
INSERT INTO `sys_menu_admin` (`ID`,`parent_id`,`name`,`Title`,`Url`,`Description`,`Icon`,`Icon_large`,`Check`,`Order`)VALUES
(@iMaxId+1, '0', 'memberships', 'Memberships', '{siteUrl}modules/?r=memberships/settings/&menu=main', 'Dolphin Subscriptions', 'modules/harvest/memberships/|memberships-icon.png', 'modules/harvest/memberships/|memberships-icon.png', '', '255');

SET @iParentIDSubs = (SELECT `id` FROM `sys_menu_admin` WHERE `name` = 'memberships');
SET @iMaxId2 = (SELECT MAX(`id`) FROM `sys_menu_admin`);
INSERT INTO `sys_menu_admin` (`ID`,`parent_id`,`name`,`Title`,`Url`,`Description`,`Icon`,`Icon_large`,`Check`,`Order`)VALUES
(@iMaxId2+1, @iParentIDSubs, 'dolphin_subs_settings', 'Settings', '{siteUrl}modules/?r=memberships/settings/', 'Dolphin Subs Settings', 'modules/harvest/memberships/|settings-icon.png', 'modules/harvest/memberships/|settings-icon.png', '', '0');

SET @iMaxId3 = (SELECT MAX(`id`) FROM `sys_menu_admin`);
INSERT INTO `sys_menu_admin` (`ID`,`parent_id`,`name`,`Title`,`Url`,`Description`,`Icon`,`Icon_large`,`Check`,`Order`)VALUES
(@iMaxId3+1, @iParentIDSubs, 'dolphin_subs_memberships', 'Membership Setup', '{siteUrl}modules/?r=memberships/memberships/', 'Dolphin Subs Membership Settings', 'modules/harvest/memberships/|memberships-icon.png', 'modules/harvest/memberships/|memberships-icon.png', '', '1');

SET @iMaxId4 = (SELECT MAX(`id`) FROM `sys_menu_admin`);
INSERT INTO `sys_menu_admin` (`ID`,`parent_id`,`name`,`Title`,`Url`,`Description`,`Icon`,`Icon_large`,`Check`,`Order`)VALUES
(@iMaxId4+1, @iParentIDSubs, 'dolphin_subs_subscriptions', 'Subscribers', '{siteUrl}modules/?r=memberships/subscriptions/', 'Dolphin Subs Subscriptions', 'modules/harvest/memberships/|subscriptions-icon.png', 'modules/harvest/memberships/|subscriptions-icon.png', '', '2');

SET @iMaxId5 = (SELECT MAX(`id`) FROM `sys_menu_admin`);
INSERT INTO `sys_menu_admin` (`ID`,`parent_id`,`name`,`Title`,`Url`,`Description`,`Icon`,`Icon_large`,`Check`,`Order`)VALUES
(@iMaxId5+1, @iParentIDSubs, 'dolphin_subs_payments', 'Payments', '{siteUrl}modules/?r=memberships/payments/', 'Dolphin Subs Subscriptions', 'modules/harvest/memberships/|payment-icon.gif', 'modules/harvest/memberships/|payment-icon.gif', '', '3');

----------------------------------------------------------
-- ACL
----------------------------------------------------------
-- Alter ACL Table
ALTER TABLE `sys_acl_levels` 
ADD `Free` INT(11) NOT NULL,
ADD `Trial` tinyint(1) NOT NULL,
ADD `Trial_Length` int(11) NOT NULL;

INSERT INTO `sys_acl_levels` (`ID`, `Name`, `Icon`, `Description`, `Active`, `Purchasable`, `Removable`, `Free`) VALUES
(NULL, 'Silver', 'non-member.png', 'A sample membership allowing some features.', 'yes', 'yes', 'yes', 0),
(NULL, 'Gold', 'promotion.png', 'A full-access Sample membership.', 'yes', 'yes', 'yes', 0);

UPDATE sys_acl_levels SET Free='0'; 
UPDATE sys_acl_levels SET Description='Cannot be removed.' WHERE ID='1'; 
UPDATE sys_acl_levels SET Description='Cannot be removed.' WHERE ID='2'; 
UPDATE sys_acl_levels SET Description='Cannot be removed.' WHERE ID='3'; 

-- Insert Prices
ALTER TABLE `sys_acl_level_prices` 
ADD `Unit` VARCHAR( 11 ) NOT NULL,
ADD `Length` VARCHAR( 11 ) NOT NULL,
ADD `Auto` INT( 11 ) NOT NULL;

SET @iSilverID = (SELECT `ID` FROM `sys_acl_levels` WHERE `Name` = 'Silver');
SET @iGoldID = (SELECT `ID` FROM `sys_acl_levels` WHERE `Name` = 'Gold');
INSERT INTO `sys_acl_level_prices` (`id`, `IDLevel`, `Days`, `Price`, `Unit`, `Length`, `Auto`) VALUES
(NULL, @iSilverID, '', '0.99','Days','30', 1),
(NULL, @iGoldID, '', '1.99','Months','2', 1);

----------------------------------------------------------
-- Tables
----------------------------------------------------------

CREATE TABLE IF NOT EXISTS `[db_prefix]payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txn_id` varchar(100) DEFAULT NULL,
  `auth_code` varchar(100) DEFAULT NULL,
  `membership_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` varchar(15) NOT NULL,
  `date` datetime NOT NULL,
  `status` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `[db_prefix]settings` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `payment_proc` varchar(100) NOT NULL,
  `alertpay_id` varchar(100) NOT NULL,
  `ap_securitycode` varchar(100) NOT NULL,
  `an_login` varchar(100) NOT NULL,
  `an_transkey` varchar(100) NOT NULL,
  `an_test` tinyint(1) NOT NULL,
  `an_api` varchar(5) NOT NULL,
  `an_ssl` tinyint(1) NOT NULL,
  `paypal_id` varchar(100) NOT NULL,
  `sandbox` tinyint(1) NOT NULL DEFAULT '0',
  `pp_custom_field` varchar(255) NOT NULL,
  `moneybookers_id` varchar(100) NOT NULL,
  `data_forward_1` varchar(255) NOT NULL,
  `data_forward_2` varchar(255) NOT NULL,
  `require_mem` tinyint(1) NOT NULL,
  `redirect_guests` tinyint(1) NOT NULL,
  `disable_downgrade` tinyint(1) NOT NULL,
  `disable_upgrade` tinyint(1) NOT NULL,
  `default_memID` int(5) NOT NULL DEFAULT '2',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `[db_prefix]settings` (`sandbox`) VALUES
(0);


----------------------------------------------------------
-- Bottom Menu
----------------------------------------------------------

INSERT INTO `sys_menu_member` (`ID`, `Caption`, `Name`, `Icon`, `Link`, `Script`, `Eval`, `PopupMenu`, `Order`, `Active`, `Editable`, `Deletable`, `Target`, `Position`, `Type`, `Parent`, `Bubble`, `Description`) VALUES
(NULL, '_dol_subs_title', 'hm_dol_subs', 'modules/harvest/memberships/|menu-bar-icon.png', 'm/memberships/', '', '', '', 0, '1', 0, 0, '', 'top_extra', 'link', 0, '', '_dol_subs_title');

----------------------------------------------------------
-- Alerts
----------------------------------------------------------

INSERT INTO `sys_alerts_handlers` VALUES (NULL, 'hm_dol_subs_alerts', 'HmSubsAlertsResponse', 'modules/harvest/memberships/classes/HmSubsAlertsResponse.php', '');

SET @iHandler = (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'hm_dol_subs_alerts');

INSERT INTO `sys_alerts` VALUES
(NULL, 'subs', 'cur_page', @iHandler),
(NULL, 'profile', 'join', @iHandler),
(NULL, 'profile', 'delete', @iHandler),
(NULL, 'profile', 'login', @iHandler),
(NULL, 'profile', 'logout', @iHandler);