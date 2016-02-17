<?php
//this file is called by the videorecorder.swf file when the [SAVE] button is pressed

//videorecorder.swfsends using GET the name of the stream
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

echo "save=ok"
//echo "save=failed" to tell the recorder the snapshot failed
?>