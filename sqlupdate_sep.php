<?

set_time_limit(9999999);
//require_once( 'inc/header.inc.php' );

//mysql_connect("localhost", "root", "I4GotIt") or die(mysql_error());
echo "Connected to MySQL<br />";
//mysql_select_db("pfcomm_july_22") or die(mysql_error());
echo "Connected to Database";

echo "START .....<br /><br />";

echo "UPDATING DATABASE .....<br /><br />";


$query = "select argument FROM general_log_prashanth where command_type = 'Query' ORDER BY `event_time`";
$result = mysql_query($query);
//$log = fopen("/var/www/html/pf/log/sqllog".date('Y-m-d').".txt", 'a+');
$log = fopen("/var/www/html/pf/log/july/sqllog".date('Y-m-d').".txt", 'a+');
while($row = mysql_fetch_array($result))
  {
  $q = $row['argument'].";";

  // echo $q.'</br/>';


   fwrite($log, "\n".$q);



  //mysql_query($q);
  }
fclose($log);
echo "SUCCESSFULLY UPDATED DATABASE ..... <br /><br />";


echo "FINISHED <br />";

?>
