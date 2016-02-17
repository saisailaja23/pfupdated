
    --
    -- sys_page_compose ;
    --

    DELETE FROM  
        `sys_page_compose` 
    WHERE
        `Page`      = 'index'
            AND
        `Caption`   = '_bx_rpx_universal';

    ALTER TABLE `Profiles` 
		DROP INDEX `RpxProfile`;

    ALTER TABLE `Profiles`
        DROP `RpxProfile`;

    --
    -- Dumping data for table `sys_objects_auths`
    --

    DELETE FROM 
        `sys_objects_auths` 
    WHERE    
        `Title` = '_bx_rpx_universal';

    --
    -- `sys_options_cats` ;
    --

    SET @iKategId = (SELECT `id` FROM `sys_options_cats` WHERE `name` = 'RPX integration' LIMIT 1);
    DELETE FROM `sys_options_cats` WHERE `id` = @iKategId;

    --
    -- `sys_options` ;
    --

    DELETE FROM `sys_options` WHERE `kateg` = @iKategId;

    -- 
    -- `sys_menu_admin`;
    --

    DELETE FROM 
        `sys_menu_admin` 
    WHERE
        `title` = '_bx_rpx';

    --
    -- permalink
    --

    DELETE FROM 
        `sys_permalinks` 
    WHERE
        `standard`  = 'modules/?r=rpx/';

    --
    -- settings
    --

    DELETE FROM 
        `sys_options` 
    WHERE
        `Name` = 'bx_rpx_permalinks'
            AND
        `kateg` = 26;