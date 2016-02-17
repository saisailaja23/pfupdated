

-- settings
SET @iCategId = (SELECT `ID` FROM `sys_options_cats` WHERE `name` = 'Sitemap Generator' LIMIT 1);
DELETE FROM `sys_options` WHERE `kateg` = @iCategId;
DELETE FROM `sys_options_cats` WHERE `ID` = @iCategId;


-- permalinks
DELETE FROM `sys_permalinks` WHERE `standard` = 'modules/?r=ue30sg/';

-- admin menu
DELETE FROM `sys_menu_admin` WHERE `name` = 'ue30_sg';

DELETE FROM `sys_menu_bottom` WHERE `Caption` = '_ue30_sitemap';

DELETE FROM `sys_page_compose_pages` WHERE `Name` = 'sitemap';

DELETE FROM `sys_page_compose` WHERE `Page` = 'sitemap';

