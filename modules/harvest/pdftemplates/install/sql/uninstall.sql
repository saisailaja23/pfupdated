--pages
DELETE FROM `sys_page_compose_pages` WHERE `Name` IN('dolphin_aff_main');
DELETE FROM `sys_page_compose` WHERE `Page` IN('dolphin_aff_main');

-- settings
SET @iCategId = (SELECT `ID` FROM `sys_options_cats` WHERE `name` = 'Dolphin Affiliates' LIMIT 1);
DELETE FROM `sys_options` WHERE `kateg` = @iCategId;
DELETE FROM `sys_options_cats` WHERE `ID` = @iCategId;
DELETE FROM `sys_options` WHERE `Name` = 'dol_aff_permalinks';

-- permalinks
DELETE FROM `sys_permalinks` WHERE `standard` = 'modules/?r=affiliates/';

-- Injections
DELETE FROM `sys_injections` WHERE `name` = 'dolphin_aff';

-- dashboard menu
DELETE FROM `sys_menu_top` WHERE `Name`='Affiliates';

-- admin menu
DELETE FROM `sys_menu_admin` WHERE `name` = 'affiliates_admin';
DELETE FROM `sys_menu_admin` WHERE `name` like 'dolphin_aff%';

DROP TABLE `[db_prefix]settings`, `[db_prefix]campaigns`, `[db_prefix]banners`,`[db_prefix]affiliates`, `[db_prefix]commissions`, `[db_prefix]payouts`, `[db_prefix]invoices`,`[db_prefix]impressions`,`[db_prefix]clicks`;

SET @iHandler = (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'dolphin_affiliates_alerts');
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;

--actions
DELETE FROM `sys_objects_actions` WHERE `Type` like 'dolphin_aff%';

DELETE FROM `sys_email_templates` WHERE `Name` LIKE '%[db_prefix]%';

--Profiles
ALTER TABLE `Profiles`
  DROP `ap_data`;
