
    ALTER TABLE `Profiles` DROP INDEX `esase_profileinfo_notify`;
    ALTER TABLE `Profiles` DROP `esase_profileinfo_notify`;

    DELETE FROM `sys_email_templates` WHERE `Name` = 'esase_ProfileChecker' OR `Name` = 'esase_ProfileCheckerEmptyThumbnail';

    DELETE FROM 
        `sys_cron_jobs` 
    WHERE `name` =  'SasProfileInfoCron';

    --
    -- `sys_options_cats` ;
    --

    SET @iKategId = (SELECT `id` FROM `sys_options_cats` WHERE `name` = 'Profile info checker' LIMIT 1);
    DELETE FROM `sys_options_cats` WHERE `id` = @iKategId;

    --
    -- `sys_options` ;
    --

    DELETE FROM `sys_options` WHERE `kateg` = @iKategId OR (`kateg` = 0 AND `Name` = 'sas_profile_info_last_id');

    DELETE FROM `sys_page_compose` WHERE `Caption` = '_sas_profile_info' AND `Page` = 'member';
    DELETE FROM `sys_page_compose` WHERE `Caption` = '_sas_profile_info' AND `Page` = 'profile';

    -- 
    -- `sys_menu_admin`;
    --

    DELETE FROM 
        `sys_menu_admin` 
    WHERE
        `title` = '_sas_profile_info' LIMIT 1;

    --
    -- permalink
    --

    DELETE FROM 
        `sys_permalinks` 
    WHERE
        `standard`  = 'modules/?r=profile_info/';

    --
    -- settings
    --

    DELETE FROM 
        `sys_options` 
    WHERE
        `Name` = 'sas_profile_info_permalinks'
            AND
        `kateg` = 26;