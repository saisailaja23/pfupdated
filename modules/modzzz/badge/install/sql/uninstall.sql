
-- tables
DROP TABLE IF EXISTS `[db_prefix]main`;
     
 
-- compose pages
DELETE FROM `sys_page_compose_pages` WHERE `Name` IN( 'modzzz_badge_main' );
DELETE FROM `sys_page_compose` WHERE `Page` IN ( 'modzzz_badge_main' );
  
DELETE FROM `sys_page_compose` WHERE `Page`='profile' AND `Desc`='Membership Badge';
DELETE FROM `sys_page_compose` WHERE `Page`='member' AND `Desc`='Membership Badge';


-- system objects
DELETE FROM `sys_permalinks` WHERE `standard` = 'modules/?r=badge/';
 
 
-- admin menu
DELETE FROM `sys_menu_admin` WHERE `name` = 'modzzz_badge';
DELETE FROM `sys_menu_top` WHERE `name` = 'My Badge';

  
-- settings
SET @iCategId = (SELECT `ID` FROM `sys_options_cats` WHERE `name` = 'Badge' LIMIT 1);
DELETE FROM `sys_options` WHERE `kateg` = @iCategId;
DELETE FROM `sys_options_cats` WHERE `ID` = @iCategId;

DELETE FROM `sys_options` WHERE `Name` IN ('modzzz_badge_permalinks' );

