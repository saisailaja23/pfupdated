-- database
DROP TABLE IF EXISTS `[db_prefix]tours`, `[db_prefix]stations`, `[db_prefix]viewed`;

-- admin menu
DELETE FROM `sys_menu_admin` WHERE `parent_id` = '2' AND `name` = 'Tour Guide Management';

DELETE FROM `sys_injections` WHERE `name` = 'tour_guide_core';

DELETE FROM `sys_permalinks` WHERE `check` IN ('yn_tour_guide_permalinks');
DELETE FROM `sys_options` WHERE `Name` = 'yn_tour_guide_permalinks';

SET @iCategoryID := (SELECT `ID` FROM `sys_options_cats` WHERE `name` = 'Younet Tour Guide' LIMIT 1);
DELETE FROM `sys_options_cats` WHERE `ID` = @iCategoryID;
DELETE FROM `sys_options` WHERE `kateg` = @iCategoryID;