<?php

require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');
require_once ('../../inc/utils.inc.php');
require_once ('../../inc/db.inc.php');

// Checking Email and Username uniqueness
$UserName = $_POST['username'];
$EmailID =  $_POST['email'];


$tablename = 'Profiles';
$columns = 'Email';
$stringSQL_Email = "SELECT  " . $columns . " FROM " . $tablename . " where Email = '" . $EmailID . "' ";
$query_email = mysql_query($stringSQL_Email);

if (mysql_num_rows($query_email) > 0)
{
$email_error = 'The email is already in use please try another email.';
$cmdtuples = 1;
}
else
{
}
//
//$tablename = 'bx_groups_main';
//$columns = 'title';
//$stringSQL_title = "SELECT  " . $columns . " FROM " . $tablename . " where title = '" . $agency_title . "' ";
//$query_title = mysql_query($stringSQL_title);
//
//if (mysql_num_rows($query_title) > 0)
//{
//$agency_error = 'The agency name is already in use please try another agency name .';
//$cmdtuples = 1;
//}
//  else
//{
//}

$tablename = 'Profiles';
$columns = 'NickName';
$stringSQL_Username = "SELECT  " . $columns . " FROM " . $tablename . " where NickName = '" . $UserName . "' ";
$query_username = mysql_query($stringSQL_Username);

if (mysql_num_rows($query_username) > 0)
{ 
$username_error = 'The user name is already in use please try another user name.';
$cmdtuples = 1;
}
  else
{
}

if ($cmdtuples > 0)
{
echo json_encode(array(
'status' => 'success',        
'email_error' => $email_error,
'username_error' => $username_error,
'agency_error' => $agency_error
));
}
else
{
echo json_encode(array(
'status' => 'err'
));
}
?>
