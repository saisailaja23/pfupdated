<?php

/*
 * Program Name : db.php
 * Author       : Shino John
 * Date Created : 08/10/2014
 * Description  : Common DB Connection
 */
function db_include(){
    $localhost              = 'localhost';
    $username               = 'root';
    $password               = 'I4GotIt';
    $dbname                 = 'pfcomm';
    $link = mysql_connect($localhost,$username,$password);
    mysql_select_db($dbname,$link)  or die("Unable to select database: " . mysql_error());    
    return $link;
}

?>
