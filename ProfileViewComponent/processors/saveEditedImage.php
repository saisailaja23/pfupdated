<?php

$handle = @fopen('http://www.google.com.au/images/nav_logo95.png','r');

if(!$handle) {
    echo 'file not found';
} else {
    echo 'file exists';
}

?>
