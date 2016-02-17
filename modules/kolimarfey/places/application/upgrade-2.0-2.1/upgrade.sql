
UPDATE `sys_page_compose_pages` SET `Name` = 'places_view' WHERE `Name` = 'Place View';
UPDATE `sys_page_compose_pages` SET `Name` = 'places_index' WHERE `Name` = 'Places Index';
UPDATE `sys_page_compose` SET `Page` = 'places_view' WHERE `Page` = 'Place View';
UPDATE `sys_page_compose` SET `Page` = 'places_index' WHERE `Page` = 'Places Index';

###################################################################

UPDATE `places_config` SET `value` = '2.1' WHERE `name` = 'version' AND `value` = '2.0';
UPDATE `sys_modules` SET `version` = '2.1.0' WHERE `uri` = 'places' LIMIT 1;

