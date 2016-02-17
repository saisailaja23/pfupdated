
-- admin menu
SET @iExtCat = (SELECT `id` FROM `sys_menu_admin` WHERE `name`='extensions');
SET @iExtOrd = (SELECT MAX(`order`) FROM `sys_menu_admin` WHERE `name`='extensions');
INSERT INTO `sys_menu_admin` (`id`, `parent_id`, `name`, `title`, `url`, `description`, `icon`, `icon_large`, `check`, `order`) VALUES
(NULL, @iExtCat, 'BlockTools', '_dbcs_BT_bx_BlockTools', '{siteUrl}modules/?r=block_tools/administration/', 'Block Tools', 'modules/deano/block_tools/|block-tools.png', '', '', @iExtOrd+1);

