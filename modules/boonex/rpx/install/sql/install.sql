
    --
    -- sys_page_compose ;
    --

    INSERT INTO 
        `sys_page_compose` 
    SET
        `Page`      = 'index', 
        `PageWidth` = '998px', 
        `Desc`      = 'RPX', 
        `Caption`   = '_bx_rpx_universal', 
        `Column`    = 2, 
        `Order`     = 0, 
        `Func`      = 'PHP',    
        `Content`   = 'return BxDolService::call(''rpx'', ''get_login_form'');',    
        `DesignBox` = 1, 
        `ColWidth`  = 50, 
        `Visible`   = 'non';

    ALTER TABLE `Profiles` ADD `RpxProfile` VARCHAR(32) NOT NULL;
	ALTER TABLE `Profiles` ADD INDEX (`RpxProfile`) ;

    --
    -- Dumping data for table `sys_objects_auths`
    --

    INSERT INTO 
        `sys_objects_auths` 
    (`Title`, `Link`) 
        VALUES
    ('_bx_rpx_universal', 'modules/?r=rpx/login_form');

    --
    -- `sys_options_cats` ;
    --

    SET @iMaxOrder = (SELECT `menu_order` + 1 FROM `sys_options_cats` ORDER BY `menu_order` DESC LIMIT 1);
    INSERT INTO `sys_options_cats` (`name`, `menu_order`) VALUES ('RPX integration', @iMaxOrder);
    SET @iKategId = (SELECT LAST_INSERT_ID());

    --
    -- Dumping data for table `sys_options`;
    --

    INSERT INTO 
        `sys_options` 
    SET
        `Name` = 'bx_rpx_api_key',
        `kateg` = @iKategId,
        `desc`  = 'API key',
        `Type`  = 'digit',
        `VALUE` = '',
        `order_in_kateg` = 1;

    INSERT INTO 
        `sys_options` 
    SET
        `Name` = 'bx_rpx_apllication_name',
        `kateg` = @iKategId,
        `desc`  = 'Apllication name',
        `Type`  = 'digit',
        `VALUE` = '',
        `order_in_kateg` = 2;

    -- 
    -- `sys_menu_admin`;
    --

    INSERT INTO 
        `sys_menu_admin` 
    SET
        `name`           = 'RPX',
        `title`          = '_bx_rpx', 
        `url`            = '{siteUrl}modules/?r=rpx/administration/', 
        `description`    = 'Managing the \'RPX integration\' settings', 
        `icon`           = 'modules/boonex/rpx/|rpx.jpg',
        `parent_id`      = 2;

    --
    -- permalink
    --

    INSERT INTO 
        `sys_permalinks` 
    SET
        `standard`  = 'modules/?r=rpx/', 
        `permalink` = 'm/rpx/', 
        `check`     = 'bx_rpx_permalinks';
        
    --
    -- settings
    --

    INSERT INTO 
        `sys_options` 
    (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`) 
        VALUES
    ('bx_rpx_permalinks', 'on', 26, 'Enable friendly permalinks in RPX module', 'checkbox', '', '', '0', '');