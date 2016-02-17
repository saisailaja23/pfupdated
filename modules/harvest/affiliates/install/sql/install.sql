----------------------------------------------------------
-- Injections
----------------------------------------------------------
INSERT INTO `sys_injections` (`name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES ('dolphin_aff', '0', 'injection_head', 'php', 'require_once(BX_DIRECTORY_PATH_MODULES.''harvest/affiliates/include.php'');', '0', '1');

----------------------------------------------------------
-- Pages
----------------------------------------------------------
SET @iMaxOrder = (SELECT `Order` + 1 FROM `sys_page_compose_pages` ORDER BY `Order` DESC LIMIT 1);
INSERT INTO `sys_page_compose_pages` (`Name`, `Title`, `Order`) VALUES ('dolphin_aff_main', 'Dolphin Affiliates', @iMaxOrder+2);

-- page compose blocks
INSERT INTO `sys_page_compose` (`Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`) VALUES 
  	('dolphin_aff_main', '998px', 'Tight block', '_dol_aff_block_tight', '1', '0', 'Tight', '', '1', '34', 'memb', '0'),
    ('dolphin_aff_main', '998px', 'Wide block', '_dol_aff_block_wide', '2', '0', 'Wide', '', '1', '66', 'memb', '0');

----------------------------------------------------------
-- Options
----------------------------------------------------------

SET @iMaxOrder = (SELECT `menu_order` + 1 FROM `sys_options_cats` ORDER BY `menu_order` DESC LIMIT 1);
INSERT INTO `sys_options_cats` (`name`, `menu_order`) VALUES ('Dolphin Affiliates', @iMaxOrder);
SET @iCategId = (SELECT LAST_INSERT_ID());

--options
INSERT INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`) VALUES
('dol_aff_make_private', 'off', @iCategId, '<bx_text:_make_private />', 'checkbox', '', '', '0', ''),
('dol_aff_auto_approve', 'off', @iCategId, '<bx_text:_approve_affiliates_auto />', 'checkbox', '', '', '1', ''),
('dol_aff_auto_approve_com', 'off', @iCategId, '<bx_text:_approve_commissions_auto />', 'checkbox', '', '', '2', ''),
('dol_aff_allow_recurring', 'off', @iCategId, '<bx_text:_allow_recur_comm />', 'checkbox', '', '', '3', ''),
('dol_aff_min_payout', '30', @iCategId, '<bx_text:_min_payout_bal />', 'digit', '', '', '4', '');


-- permalinks
INSERT INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`) VALUES
('dol_aff_permalinks', 'on', 26, 'Enable friendly permalinks in Dolphin Affiliates', 'checkbox', '', '', '0', '');


----------------------------------------------------------
-- Permalinks
----------------------------------------------------------

INSERT INTO `sys_permalinks` VALUES (NULL, 'modules/?r=affiliates/', 'm/affiliates/', 'dol_aff_permalinks');

----------------------------------------------------------
-- Top Menu
----------------------------------------------------------

INSERT INTO `sys_menu_top` (`ID`, `Parent`, `Name`, `Caption`, `Link`, `Order`, `Visible`, `Target`, `Onclick`, `Check`, `Editable`, `Deletable`, `Active`, `Type`, `Picture`, `Icon`, `BQuickLink`, `Statistics`) VALUES
(NULL, 118, 'Affiliates', '_dol_aff_info', 'm/affiliates/', 4, 'memb', '', '', '', 1, 1, 1, 'custom', '', '', 0, '');

----------------------------------------------------------
-- Admin Menu
----------------------------------------------------------
-- Admin Main Menu
SET @iMaxId = (SELECT MAX(`id`) FROM `sys_menu_admin`);
INSERT INTO `sys_menu_admin` (`ID`,`parent_id`,`name`,`Title`,`Url`,`Description`,`Icon`,`Icon_large`,`Check`,`Order`)VALUES
(@iMaxId+1, '0', 'affiliates_admin', '<bx_text:_dol_aff_affiliates />', '{siteUrl}modules/?r=affiliates/admin/', 'Dolphin Affiliates', 'modules/harvest/affiliates/|affiliates-icon.png', 'modules/harvest/affiliates/|affiliates-icon-large.png', '', '7');

SET @iParentIDAff = (SELECT `id` FROM `sys_menu_admin` WHERE `name` = 'affiliates_admin');
SET @iMaxId2 = (SELECT MAX(`id`) FROM `sys_menu_admin`);
INSERT INTO `sys_menu_admin` (`ID`,`parent_id`,`name`,`Title`,`Url`,`Description`,`Icon`,`Icon_large`,`Check`,`Order`)VALUES
(@iMaxId2+1, @iParentIDAff, 'dolphin_aff_settings', '<bx_text:_dol_aff_settings />', '{siteUrl}modules/?r=affiliates/settings/', 'Dolphin Affiliates Settings', 'modules/harvest/affiliates/|settings-icon.png', 'modules/harvest/affiliates/|settings-icon.png', '', '0');

SET @iMaxId3 = (SELECT MAX(`id`) FROM `sys_menu_admin`);
INSERT INTO `sys_menu_admin` (`ID`,`parent_id`,`name`,`Title`,`Url`,`Description`,`Icon`,`Icon_large`,`Check`,`Order`)VALUES
(@iMaxId3+1, @iParentIDAff, 'dolphin_aff_campaigns', '<bx_text:_dol_aff_campaigns />', '{siteUrl}modules/?r=affiliates/campaigns/', 'Dolphin Affiliates Campaigns', 'modules/harvest/affiliates/|campaign-icon.png', 'modules/harvest/affiliates/|campaign-icon.png', '', '1');

SET @iMaxId4 = (SELECT MAX(`id`) FROM `sys_menu_admin`);
INSERT INTO `sys_menu_admin` (`ID`,`parent_id`,`name`,`Title`,`Url`,`Description`,`Icon`,`Icon_large`,`Check`,`Order`)VALUES
(@iMaxId4+1, @iParentIDAff, 'dolphin_aff_banners', '<bx_text:_dol_aff_banners />', '{siteUrl}modules/?r=affiliates/banners/', 'Dolphin Affiliates Banners', 'modules/harvest/affiliates/|banners-icon.png', 'modules/harvest/affiliates/|banners-icon.png', '', '2');

SET @iMaxId5 = (SELECT MAX(`id`) FROM `sys_menu_admin`);
INSERT INTO `sys_menu_admin` (`ID`,`parent_id`,`name`,`Title`,`Url`,`Description`,`Icon`,`Icon_large`,`Check`,`Order`)VALUES
(@iMaxId5+1, @iParentIDAff, 'dolphin_aff_affiliates', '<bx_text:_dol_aff_affiliates />', '{siteUrl}modules/?r=affiliates/affiliates/', 'Dolphin Affiliate Members', 'modules/harvest/affiliates/|affiliates2-icon.png', 'modules/harvest/affiliates/|affiliates2-icon.png', '', '3');

SET @iMaxId6 = (SELECT MAX(`id`) FROM `sys_menu_admin`);
INSERT INTO `sys_menu_admin` (`ID`,`parent_id`,`name`,`Title`,`Url`,`Description`,`Icon`,`Icon_large`,`Check`,`Order`)VALUES
(@iMaxId6+1, @iParentIDAff, 'dolphin_aff_commissions', '<bx_text:_dol_aff_commissions />', '{siteUrl}modules/?r=affiliates/commissions/', 'Dolphin Affilates Transactions', 'modules/harvest/affiliates/|transactions-icon.png', 'modules/harvest/affiliates/|transactions-icon.png', '', '4');

--SET @iMaxId7 = (SELECT MAX(`id`) FROM `sys_menu_admin`);
--INSERT INTO `sys_menu_admin` (`ID`,`parent_id`,`name`,`Title`,`Url`,`Description`,`Icon`,`Icon_large`,`Check`,`Order`)VALUES
--(@iMaxId7+1, @iParentIDAff, 'dolphin_aff_statistics', '<bx_text:_dol_aff_statistics />', '{siteUrl}modules/?r=affiliates/statistics/', 'Affilates Statistics', 'modules/harvest/affiliates/|statistics-icon.png', 'modules/harvest/affiliates/|statistics-icon.png', '', '5');

SET @iMaxId8 = (SELECT MAX(`id`) FROM `sys_menu_admin`);
INSERT INTO `sys_menu_admin` (`ID`,`parent_id`,`name`,`Title`,`Url`,`Description`,`Icon`,`Icon_large`,`Check`,`Order`)VALUES
(@iMaxId8+1, @iParentIDAff, 'dolphin_aff_payout', '<bx_text:_dol_aff_payouts />', '{siteUrl}modules/?r=affiliates/payouts/', 'Affilates Payments', 'modules/harvest/affiliates/|payout-icon.png', 'modules/harvest/affiliates/|payout-icon.png', '', '6');

----------------------------------------------------------
-- Tables
----------------------------------------------------------

-- Alter Profiles
ALTER TABLE `Profiles`
ADD `ap_data` VARCHAR(55) NOT NULL;

CREATE TABLE IF NOT EXISTS `[db_prefix]settings` (
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

CREATE TABLE IF NOT EXISTS `[db_prefix]campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `status` varchar(55) NOT NULL,	
  `click_commission` varchar(55) NOT NULL, 
  `click_amount` varchar(55) NOT NULL, 
  `join_commission` varchar(55) NOT NULL, 
  `join_amount` varchar(55) NOT NULL, 
  `membership_commission` varchar(55) NOT NULL, 
  `membership_amount` varchar(55) NOT NULL, 
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Default Campaign
INSERT INTO `[db_prefix]campaigns` (`id`, `name`, `status`, `click_commission`, `click_amount`, `join_commission`, `join_amount`, `membership_commission`, `membership_amount`) VALUES
(1, 'Default', 'active', 'disabled', '', 'enabled', '.25', 'disabled', '');

CREATE TABLE IF NOT EXISTS `[db_prefix]banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `hidden` varchar(100) NOT NULL,
  `campaign_id` varchar(100) NOT NULL,
  `target_url` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `text_title` varchar(100) NOT NULL,
  `text_details` varchar(100) NOT NULL,
  `filename` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `[db_prefix]affiliates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id`int(11) NOT NULL,
  `first_name` varchar(155) NOT NULL,
  `last_name` varchar(155) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `payout_minimum` varchar(255) NOT NULL,
  `payout_preference` varchar(255) NOT NULL,
  `paypal_email` varchar(255) NOT NULL,
  `date_start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(55) NOT NULL,
  `campaigns` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `[db_prefix]commissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tier` int(11) NOT NULL,
  `campaign_id` varchar(255) NOT NULL,
  `affiliate_id` varchar(255) NOT NULL,
  `banner_id` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `membership_id` varchar(255) NOT NULL,
  `txn_id` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `approved` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `[db_prefix]payouts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `affiliate_id` int(11) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `currency_sign` varchar(255) NOT NULL,
  `invoice_id` varchar(255) NOT NULL,
  `datepaid` varchar(255) NOT NULL,
  `method` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `[db_prefix]invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `affiliate_id` int(11) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `currency_sign` varchar(255) NOT NULL,
  `invoicetext` varchar(255) NOT NULL,
  `datepaid` varchar(255) NOT NULL,
  `method` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `[db_prefix]impressions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` varchar(255) NOT NULL,
  `affiliate_id` varchar(255) NOT NULL,
  `banner_id` varchar(255) NOT NULL,
  `raw` varchar(255) NOT NULL,
  `unique` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `[db_prefix]clicks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` varchar(255) NOT NULL,
  `affiliate_id` varchar(255) NOT NULL,
  `banner_id` varchar(255) NOT NULL,
  `raw` varchar(255) NOT NULL,
  `unique` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

----------------------------------------------------------
-- Alerts
----------------------------------------------------------

INSERT INTO `sys_alerts_handlers` VALUES (NULL, 'dolphin_affiliates_alerts', 'HmAffProAlertsResponse', 'modules/harvest/affiliates/classes/HmAffProAlertsResponse.php', '');

SET @iHandler = (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'dolphin_affiliates_alerts');

INSERT INTO `sys_alerts` VALUES
(NULL, 'profile', 'join', @iHandler),
(NULL, 'profile', 'delete', @iHandler),
(NULL, 'profile', 'login', @iHandler),
(NULL, 'profile', 'logout', @iHandler),
(NULL, 'profile', 'set_membership', @iHandler),
(NULL, 'affiliates', 'approve', @iHandler),
(NULL, 'affiliates', 'payout', @iHandler),
(NULL, 'affiliates', 'commission', @iHandler),
(NULL, 'affiliates', 'impression', @iHandler),
(NULL, 'affiliates', 'click', @iHandler);

----------------------------------------------------------
-- User Actions
----------------------------------------------------------
INSERT INTO `sys_objects_actions` (`Caption`, `Icon`, `Url`, `Script`, `Eval`, `Order`, `Type`) VALUES 
    ('{evalResult}', 'modules/harvest/affiliates/|register.png', '{BaseUri}register/', '', 'if ($GLOBALS[''logged''][''member''] || $GLOBALS[''logged''][''admin'']) return _t(''_dol_aff_action_create_acc''); return;', 1, 'dolphin_aff_create'),
    ('{evalResult}', 'modules/harvest/affiliates/|account.png', '{BaseUri}', '', 'if ($GLOBALS[''logged''][''member''] || $GLOBALS[''logged''][''admin'']) return _t(''_dol_aff_action_my_acc''); return;', 2, 'dolphin_aff_my'),
    ('{evalResult}', 'modules/harvest/affiliates/|edit.png', '{BaseUri}edit/', '', 'if ($GLOBALS[''logged''][''member''] || $GLOBALS[''logged''][''admin'']) return _t(''_dol_aff_action_edit_acc''); return;', 3, 'dolphin_aff_edit');

----------------------------------------------------------
-- Email Templates
----------------------------------------------------------
INSERT INTO `sys_email_templates` (`Name`, `Subject`, `Body`, `Desc`, `LangID`) VALUES 
('t_[db_prefix]approved', 'Affiliate Account Approved', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p><b>Hello</b> <AffiliateName>,</p>\r\n\r\n<p>Your affiliate account has been approve.</p><p>Please <a href="<SiteUrl>">Login Here</a> to start earning money.</p>\r\n\r\n</p>\r\n\r\n<UserExplanation></p>\r\n\r\n</p>\r\n\r\n<p>---</p>\r\nBest regards,  <SiteName> \r\n<p style="font: bold 10px Verdana; color:red">!!!Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'Affiliate "Account Approved" Email Template', '0');