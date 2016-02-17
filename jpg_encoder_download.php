<?php
//this file is called by the videorecorder.swf file when the [SAVE] button is pressed

//videorecorder.swfsends using GET the name of the stream
include 'inc/header.inc.php';
$photoName = $_GET["name"];

//[+]!!!!!!!!!!!! do not modify
if(!is_dir("snapshots")){
	$res = mkdir("snapshots",0777); 
}
if(isset($GLOBALS["HTTP_RAW_POST_DATA"])){
	$image = fopen("snapshots/".$photoName,"a+");
	fwrite($image , $GLOBALS["HTTP_RAW_POST_DATA"] );
	fclose($image);
}

//[-]!!!!!!!!!!!! end do not modify

echo "save=ok"; 
session_start();
$album = $_SESSION['album'];
$owner = $_COOKIE['memberID'];

//database connection
		mysql_connect($db['host'], $db['user'], $db['passwd'],true) or die   ('Error connecting to mysql');
		mysql_select_db($db['db']);


//get title of the recorder
	$filename = $_GET["name"];
	$name = substr($filename, 0, -4);




//id of the latest record

	$ObjId= "SELECT `ID` FROM `RayVideoFiles` WHERE `Title`='$name'";//.$streamName;should be replaced
	$objId= mysql_fetch_assoc(mysql_query($ObjId));
	$LastObjId= $objId['ID'];//for the last object id 



//get caption of the album

		$nickname = "SELECT `NickName` FROM `Profiles` WHERE `ID`=".$owner;//should be replaced
		$Caption = mysql_fetch_assoc(mysql_query($nickname));

		$Name = $Caption['NickName'] ;//for the caption
		$Uri = $Name.'-s-videos';
		$albumname = $album;
 		
		if($albumname == $Uri){
				$locationCaption = $Name.'\'s videos';
				$Uri=$Uri;
		}
		else{
				$locationCaption = $albumname;
				$Uri = $albumname;
		}
		

		
//condition to insert a row
		
	$exist ="SELECT `ID` FROM `sys_albums` WHERE `Uri` = '".$albumname."' AND `Type`='bx_videos'";
	$Exist = mysql_fetch_assoc(mysql_query($exist));
	$exists = $Exist['ID'];

//fetch the date
$date = new DateTime();
$datetime = $date->format('U');


//get the count of the objects in the album
		 $countquery = "SELECT COUNT(*) as count FROM `sys_albums_objects` WHERE `id_album`=".$Exist['ID'];
		 $count = mysql_fetch_assoc(mysql_query($countquery));
		$finalcount = ($count['count'])+1;//for the object count



	if($exists==0){
                $query1 = "INSERT INTO `sys_albums` (`ID`, `Caption`,`Uri`, `Location`, `Description`, `Type`, `Owner`, `Status`, `Date`, `ObjCount`, `LastObjId`, `AllowAlbumView`)
                VALUES ('', '$locationCaption','$Uri', '', '', 'bx_videos', '$owner', '$sStatus', $datetime, $finalcount, $LastObjId, 3)";
                mysql_query($query1);
		}
	else
    		{   
    		$query2 = "UPDATE `sys_albums` SET `ObjCount`='$finalcount' , `LastObjId`= '$LastObjId' WHERE `ID`='$exists'";
    		mysql_query($query2);
    		}

$id ="SELECT `ID` FROM `sys_albums` WHERE `LastObjId` = '$LastObjId'";
$AlbumID = @mysql_fetch_assoc(mysql_query($id));
$Albumid = $AlbumID['ID'];

$query = "INSERT INTO `sys_albums_objects` (`id_album`, `id_object`,`obj_order`)VALUES ('$Albumid', '$LastObjId','0')";
mysql_query($query);
session_destroy();



GLOBAL $dir;
//copy the files from media server to the site
	copy($dir['root'].'snapshots/'.$_GET["name"],$dir['root'].'flash/modules/video/files/'.$LastObjId.'_small.jpg');
//echo "save=failed" to tell the recorder the snapshot failed.*/

?>