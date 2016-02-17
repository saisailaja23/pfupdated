SET @sPluginName = 'aqb_pcomposer';
SET @sPluginTitle = 'AQB Profile Composer';

DELETE FROM `sys_permalinks` WHERE `check` = CONCAT('permalinks_module_', @sPluginName);
DELETE FROM `sys_menu_admin` WHERE `name` = @sPluginName;

SET @iId = (SELECT `ID` FROM `sys_options_cats` WHERE `name` = @sPluginTitle);
DELETE FROM `sys_options` WHERE `kateg`= @iId;
DELETE FROM `sys_options` WHERE `Name` = 'permalinks_module_aqb_pcomposer';
DELETE FROM `sys_options_cats` WHERE `name`=@sPluginTitle;
DELETE FROM `sys_objects_actions` WHERE `Icon`='modules/aqb/pcomposer/|new_block.png';
DELETE FROM `sys_menu_top` WHERE `Caption` = '_aqb_pc_profile_blocks';
DELETE FROM `sys_menu_top` WHERE `Caption` = '_aqb_pc_approve_blocks';

DELETE  `sys_alerts_handlers`,`sys_alerts` 
FROM `sys_alerts_handlers`,`sys_alerts` 
WHERE `sys_alerts`.`handler_id` = `sys_alerts_handlers`.`id`  AND `sys_alerts_handlers`.`name` = 'aqb_pc_load_alert';

DROP TABLE IF EXISTS `[db_prefix]members_blocks`;
DROP TABLE IF EXISTS `[db_prefix]profiles_info`;
DROP TABLE IF EXISTS `[db_prefix]standard_blocks`;