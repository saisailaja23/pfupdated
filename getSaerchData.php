<?php
$con = mysql_connect("localhost","root","I4GotIt");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("pfcomm", $con);

//if($_GET['send'] == 'AdoptionAgency[]'){

$result = mysql_query("SELECT * FROM sys_pre_values WHERE `Key` = 'AdoptionAgency' ORDER BY LKey");
$data = '<option></option>';
while($row = mysql_fetch_array($result))
  {

 $Value   = $row['Value'];
 $LKey = $row['LKey'];
 $LKey = str_replace("__", "", $LKey);
 $data .= '<option value="'.$Value.'">'.$LKey.'</option>';
  
  }
// }

echo $data;
mysql_close($con);
?> 