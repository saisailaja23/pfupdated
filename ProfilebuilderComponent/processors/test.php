<?php

require_once '../../inc/header.inc.php';
require_once '../../inc/profiles.inc.php';
require_once '../../inc/utils.inc.php';
require_once '../../inc/db.inc.php';

mysql_query("DELETE FROM `pfcomm`.`aqb_pc_members_blocks` WHERE `aqb_pc_members_blocks`.`id` = 366");
