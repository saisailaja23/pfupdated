-- admin menu
SET @iCat = (SELECT `id` FROM `sys_menu_admin` WHERE `name`='extensions');
SET @iMax = (SELECT MAX(`order`) FROM `sys_menu_admin` WHERE `name`='extensions');
INSERT INTO `sys_menu_admin` (`id`, `parent_id`, `name`, `title`, `url`, `description`, `icon`, `icon_large`, `check`, `order`) VALUES
(NULL, @iCat, 'MTools_Page_Editor', '_mchristiaan_mtools', '{siteUrl}modules/?r=mtools/administration/', 'MTools Page Editor by M.Christiaan', 'modules/mchristiaan/mtools/|icon.png', '', '', @iMax+1);

