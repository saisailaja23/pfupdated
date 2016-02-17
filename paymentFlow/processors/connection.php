<?php
// ===== PLACE YOUR DATABASE HANDLER HERE
$db['host']                = 'localhost';
$db['sock']                = '';
$db['port']                = '';
$db['user']                = 'root';
$db['passwd']              = '';
$db['db']                  = 'pfcomm7.1.0';


$con = mssql_connect($db['host'] , $db['user'] , $db['passwd'])
	or die(json_encode(array('status' => 'err', 'response' => "Couldn't connect to SQL Server on $servername")));
$selected = mssql_select_db($db['db'], $con)
   or die(json_encode(array('status' => 'err', 'response' => "Couldn't open database")));
if (!$con) {
    die(json_encode(array('status' => 'err', 'response' => 'Something went wrong while connecting to MSSQL' . mssql_get_last_message() )));
}
// ===== PLACE YOUR DATABASE HANDLER HERE
?>