
    ALTER TABLE `Profiles` ADD `esase_profileinfo_notify` INT NOT NULL;
    ALTER TABLE `Profiles` ADD INDEX (`esase_profileinfo_notify`); 

    INSERT INTO `sys_email_templates` VALUES('', 'esase_ProfileChecker', 'Please fill your profile info', '<html><head></head><body style="font: 12px Verdana; color:#000000"><p>You didn''t fill in your profile completely. Please, log into your account and edit it in  <a href="<editLink>">here</a> . Thanks in advance, we appreciate you being our site member.</p>\r\n\r\n<p><b>Thank you for using our services!</b></p>\r\n\r\n<p>--</p>\r\n<p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'Profile info. Fulfillment notification.', 0);
    INSERT INTO `sys_email_templates` VALUES('', 'esase_ProfileCheckerEmptyThumbnail', 'Please select your avatar image', '<html><head></head><body style="font: 12px Verdana; color:#000000"><p>You didn''t select your avatar image. Please add your avatar <a href="<avatarUrl>">here</a>. Thanks in advance, we appreciate you being our site member.</p>\r\n\r\n<p><b>Thank you for using our services!</b></p>\r\n\r\n<p>--</p>\r\n<p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'Profile info. Select thumbnail notification.', 0);

    --
    -- Dumping data for table `sys_cron_jobs`
    --

    INSERT INTO 
        `sys_cron_jobs` 
    (`name`, `time`, `class`, `file`)
        VALUES
    ('SasProfileInfoCron', '* */2 * * *', 'SasProfileInfoCron', 'modules/esase/profile_info/classes/SasProfileInfoCron.php');

    --
    -- `sys_options_cats` ;
    --

    SET @iMaxOrder = (SELECT `menu_order` + 1 FROM `sys_options_cats` ORDER BY `menu_order` DESC LIMIT 1);
    INSERT INTO `sys_options_cats` (`name`, `menu_order`) VALUES ('Profile info checker', @iMaxOrder);
    SET @iKategId = (SELECT LAST_INSERT_ID());

    --
    -- Dumping data for table `sys_options`;
    --

    INSERT INTO 
        `sys_options` 
    SET
        `Name` = 'sas_profile_info_percentage',
        `kateg` = @iKategId,
        `desc`  = 'Remind about incomplete profile (in percentage)',
        `Type`  = 'digit',
        `VALUE` = '35',
        `order_in_kateg` = 2;

    INSERT INTO 
        `sys_options` 
    SET
        `Name` = 'sas_profile_info_enable',
        `kateg` = @iKategId,
        `desc`  = 'Enable notifications',
        `Type`  = 'checkbox',
        `VALUE` = 'on',
        `order_in_kateg` = 1;

    INSERT INTO 
        `sys_options` 
    SET
        `Name` = 'sas_profile_info_msg_count',
        `kateg` = @iKategId,
        `desc`  = 'The amount of notifications to send every 2 hours',
        `Type`  = 'digit',
        `VALUE` = '20',
        `order_in_kateg` = 3;

    INSERT INTO 
        `sys_options` 
    SET
        `Name` = 'sas_profile_info_check_thumbnail',
        `kateg` = @iKategId,
        `desc`  = 'Send notification if there is no profile thumbnail',
        `Type`  = 'checkbox',
        `VALUE` = 'on',
        `order_in_kateg` = 4;

    INSERT INTO 
        `sys_options` 
    SET
        `Name` = 'sas_profile_info_last_id',
        `kateg` = 0,
        `desc`  = 'Last profile id',
        `Type`  = 'digit',
        `VALUE` = '0';

    --
    -- Dumping data for table `sys_page_compose`
    --

    INSERT INTO `sys_page_compose` 
    	(`Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`)
    VALUES
    	('member', '998px', 'Profile info', '_sas_profile_info', 1, 0, 'PHP', 'return BxDolService::call(''profile_info'', ''get_checker_block'');', 1, 34, 'memb', 0);


     --
    -- Dumping data for table `sys_page_compose`
    --

    INSERT INTO `sys_page_compose` 
    	(`Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`)
    VALUES
    	('profile', '998px', 'Profile info', '_sas_profile_info', 1, 0, 'PHP', 'return BxDolService::call(''profile_info'', ''get_checker_block'', array($this->oProfileGen->_iProfileID));', 1, 34, 'memb', 0);
   
    -- 
    -- `sys_menu_admin`;
    --

    INSERT INTO 
        `sys_menu_admin` 
    SET
        `name`           = 'Profile info checker',
        `title`          = '_sas_profile_info', 
        `url`            = '{siteUrl}modules/?r=profile_info/administration/', 
        `description`    = 'Managing the \'twitter connect\' settings', 
        `icon`           = 'modules/esase/profile_info/|profile.gif',
        `parent_id`      = 2;
 
    --
    -- permalink
    --

    INSERT INTO 
        `sys_permalinks` 
    SET
        `standard`  = 'modules/?r=profile_info/', 
        `permalink` = 'm/profile_info/', 
        `check`     = 'sas_profile_info_permalinks';

    --
    -- settings
    --

    INSERT INTO 
        `sys_options` 
    (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`) 
        VALUES
    ('sas_profile_info_permalinks', 'on', 26, 'Enable friendly permalinks in profile info checker', 'checkbox', '', '', '0', '');

    UPDATE `sys_profile_fields` SET `Max` = 500 WHERE `Name` = 'NickName' LIMIT 1;    