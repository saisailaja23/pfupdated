<?php

$ID = $_POST['id'];
$Title = stripslashes($_POST['value']);

/* sleep for a while so we can see the indicator */
usleep(2000);

    print $Title; 
