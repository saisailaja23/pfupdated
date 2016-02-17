<?php
//this file is called by the videorecorder.swf file when the [SAVE] button is pressed
//3 variables are passed to this file via GET:
//the streamName var  contains the name of the .flv file as it is on the REd5/FMS server on which it was saved
//the streamDuration var  contains the duration of the video stream in seconds but it is accurate to the millisecond  like this: 4.231
//the userId GET var contains the value of the userId GET var sent from avc_settings.php or via flash vars, if userId si found in both places the one in avc_settings.php is used
//you can do whatever you want in here with the variables like insert them in a db etc..

include 'inc/header.inc.php';
GLOBAL $dir;

$streamName=$_GET["streamName"];
$streamDuration=$_GET["streamDuration"];
$userId= $_GET["userId"];

echo "save=ok";
$owner = $_COOKIE['memberID'];
$streamDuration = $streamDuration*1000;
//database connection
		mysql_connect($db['host'], $db['user'], $db['passwd'],true) or die   ('Error connecting to mysql');
		mysql_select_db($db['db']);

//fetch the date
		$date = new DateTime();
		$datetime = $date->format('U');

//Status for autoapproval		
		$query= "SELECT `VALUE`  FROM `sys_options` WHERE `Name` ='bx_videos_activation'";
		$status = mysql_fetch_assoc(mysql_query($query));

    			if ($status['VALUE'] == 'on'||$_COOKIE['memberID']=='1') {
			$sStatus = 'approved';
			}else {
			     $sStatus = 'pending';
			}

//inserting details into the database
		$sql = "INSERT INTO `RayVideoFiles`(`ID`, `Categories`, `Title`, `Uri`, `Tags`, `Description`, `Time`, `Date`, `Owner`, `Views`, `Rate`, `RateCount`, `CommentsCount`, `Featured`, `Status`, `Source`, `Video`)
			VALUES ('', '', '$streamName', '$streamName', '', '', '$streamDuration', $datetime, '$owner', '', '', '', '', '', '$sStatus', '$source', '')";
		mysql_query($sql);

//id of the latest record 
		$ObjId= "SELECT MAX(`ID`) FROM `RayVideoFiles` WHERE `Title`='$streamName'";
		$objId= mysql_fetch_assoc(mysql_query($ObjId));
		$LastObjId= $objId['MAX(`ID`)'];//for the last object id

//copy the files from media server to the site
		fopen($dir['root'].'flash/modules/video/files/'.$LastObjId.'_temp.flv','wb');
		copy($dir['root'].'flash/modules/video/files/video/'.$_GET["streamName"].'.flv',$dir['root'].'flash/modules/video/files/'.$LastObjId.'_temp.flv');

//getting source of the file
		$source = $dir['root'].'flash/modules/video/files/';


//Update the table with the source
		$video= $LastObjId.'_temp.flv';
		$sourceupdation = "UPDATE `RayVideoFiles` SET `Source`='$source' , `Video`='$video' WHERE `ID`='$LastObjId'";
		mysql_query($sourceupdation);


?>