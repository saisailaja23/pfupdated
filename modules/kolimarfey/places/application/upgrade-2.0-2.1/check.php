<?php

    if ($this->model->getOne("SELECT `value` FROM `places_config` WHERE `name` = 'version' AND `value` = '2.1'"))
        return "INSTALLED";
    else
        return "NOT INSTALLED";

?>
