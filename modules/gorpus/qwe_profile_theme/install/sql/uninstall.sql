
DELETE FROM `sys_injections` WHERE `name` = 'qwe_profile_theme' AND `key` = 'injection_header';

DROP TABLE IF EXISTS `[db_prefix]themes`;
DROP TABLE IF EXISTS `[db_prefix]base`;
DROP TABLE IF EXISTS `qwe_profile_theme_pages`;

DELETE FROM `sys_menu_top` WHERE `Name` = 'qwe_profile_theme';

DELETE FROM `sys_menu_admin` WHERE `name` = 'qwe_profile_theme_admin_menu';

SET @iCategoryID := (SELECT `ID` FROM `sys_options_cats` WHERE `name` = 'Gorpus Profile Themes' LIMIT 1);
DELETE FROM `sys_options_cats` WHERE `ID` = @iCategoryID;
DELETE FROM `sys_options` WHERE `kateg` = @iCategoryID;
