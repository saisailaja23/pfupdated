<?php

$array = array(
    '0' => array('fullName' => 'neikandkim', 'profile_image' => 'https://www.parentfinder.com/modules/boonex/avatar/data/favourite/4085.jpg'),
    '1' => array('fullName' => 'derrickandcandice', 'profile_image' => 'https://www.parentfinder.com/modules/boonex/avatar/data/favourite//4034.jpg'),
    '2' => array('fullName' => 'ryanandlauri', 'profile_image' => 'http://www.parentfinder.com/modules/boonex/avatar/data/favourite/4247.jpg'),
    '3' => array('fullName' => 'AmitandNatalie', 'profile_image' => 'https://www.parentfinder.com/modules/boonex/avatar/data/favourite/3218.jpg'),
    '4' => array('fullName' => 'SarahandNate', 'profile_image' => 'https://www.parentfinder.com/modules/boonex/avatar/data/favourite/1609.jpg'),
    '5' => array('fullName' => 'TylerandNicole', 'profile_image' => 'https://www.parentfinder.com/modules/boonex/avatar/data/favourite/3497.jpg'),
    '6' => array('fullName' => 'kevinandjennifer', 'profile_image' => 'https://www.parentfinder.com/modules/boonex/avatar/data/favourite/3777.jpg'),
);

if(isset ($_GET['callback']))
{
    header("Content-Type: application/json");
    echo $_GET['callback']."(".json_encode($array).")";
}
?>