SET @sPluginName = 'aqb_membership_vouchers';

DELETE FROM `sys_menu_admin` WHERE `name`=@sPluginName;

DELETE FROM `sys_permalinks` WHERE `check`=CONCAT('permalinks_module_', @sPluginName);

DELETE FROM `sys_options` WHERE `Name` = CONCAT('permalinks_module_', @sPluginName);

DELETE FROM `bx_pmt_modules` WHERE `uri` = @sPluginName LIMIT 1;

SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = '[db_prefix]profile_delete' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

UPDATE `sys_page_compose` SET `Func` = 'Available', `Content` = '' WHERE `Page` = 'bx_mbp_my_membership' AND `Content` = 'BxDolService::call(''aqb_membership_vouchers'', ''get_block_code_available'');';

DROP TABLE `[db_prefix]codes`;

DROP TABLE `[db_prefix]transactions`;