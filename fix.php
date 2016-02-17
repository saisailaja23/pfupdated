<?php
$con = mysql_connect("localhost","root","I4GotIt");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("pfcomm", $con);

$result = mysql_query("SELECT * FROM Profiles");

while($row = mysql_fetch_array($result))
  {
 $id   = $row['ID'];
 $pass = $row['Password'];
 $salt = $row['Salt'];
 $couple=$row['Couple'];
 mysql_query("UPDATE Profiles_draft SET Password = '$pass', Salt = '$salt', Couple = '$couple'  WHERE ID = '$id'");
  Echo "success";
  }

mysql_close($con);
?> 