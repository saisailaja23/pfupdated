<?php
error_reporting(0);

if(isset($_FILES['userfile']['name'])){
$upDir = getcwd() . "/midia/";
$uploadfile = $upDir . "temp.txt";

move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);
}
?>
