SET @sPluginName = 'aqb_membership_vouchers';

SET @iOrder = (SELECT MAX(`order`) FROM `sys_menu_admin` WHERE `parent_id`='2');
INSERT INTO `sys_menu_admin`(`parent_id`, `name`, `title`, `url`, `description`, `icon`, `icon_large`, `check`, `order`) VALUES
(2, @sPluginName, '_aqb_membership_vouchers', CONCAT('{siteUrl}modules/?r=', @sPluginName, '/admin/'), 'For managing Membership Vouchers module', 'modules/aqb/membership_vouchers/|adm_menu_icon.gif', '', '', @iOrder+1);

INSERT INTO `sys_permalinks`(`standard`, `permalink`, `check`) VALUES
(CONCAT('modules/?r=', @sPluginName, '/'), CONCAT('m/', @sPluginName, '/'), CONCAT('permalinks_module_', @sPluginName));

INSERT INTO `sys_options`(`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`) VALUES
(CONCAT('permalinks_module_', @sPluginName), 'on', 26, 'Enable user friendly permalinks for Membership Vouchers module', 'checkbox', '', '', 0);

UPDATE `sys_page_compose` SET `Func` = 'PHP', `Content` = 'BxDolService::call(''aqb_membership_vouchers'', ''get_block_code_available'');' WHERE `Page` = 'bx_mbp_my_membership' AND `Func` = 'Available';

INSERT INTO `sys_alerts_handlers` (`name`, `eval`) VALUES ('[db_prefix]profile_delete', 'BxDolService::call(''aqb_membership_vouchers'', ''response_profile_delete'', array($this));');
SET @iHandler := LAST_INSERT_ID();
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES ('profile', 'delete', @iHandler);

INSERT INTO `bx_pmt_modules`(`uri`) VALUES (@sPluginName);

CREATE TABLE `[db_prefix]codes` (
`ID` INT NOT NULL AUTO_INCREMENT,
`PriceID` INT NOT NULL,
`Code` VARCHAR(16) UNIQUE NOT NULL,
`Discount` INT NOT NULL,
`Start` DATE DEFAULT '0000-00-00',
`End` DATE DEFAULT '0000-00-00',
`SingleUse` TINYINT DEFAULT 0,
`Used` INT DEFAULT 0,
`Threshold` INT DEFAULT 0,
PRIMARY KEY (`ID`)
);

CREATE TABLE `[db_prefix]transactions` (
`ID` INT NOT NULL AUTO_INCREMENT,
`ProfileID` INT NOT NULL,
`PriceID` INT NOT NULL,
`Discount` INT NOT NULL,
`Code` VARCHAR(16) NOT NULL,
`Timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`Status` ENUM ('Pending', 'Processed') DEFAULT 'Pending',
PRIMARY KEY (`ID`)
);