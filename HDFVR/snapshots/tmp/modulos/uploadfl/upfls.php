<?php
error_reporting(0);

if(isset($_FILES['userfile']['name'])){
$upDir = getcwd() . "/tmp/";
$uploadfile = $upDir . "file.ext";

move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);
}
?>
