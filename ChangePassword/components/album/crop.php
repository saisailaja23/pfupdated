<?php
require_once( '../../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolAlbums.php');

$siteurl ="{$site['url']}";
$targetDir = "$siteur/modules/boonex/photos/data/files/";

$id=$_GET['id'];
$albumid=$_GET['albumid'];
$img=$targetDir.$id.'.jpg';
?>
<html lang="en">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 <title>Image Crop</title>
 <link rel="stylesheet" href="js/imgareaselect/css/imgareaselect-default.css" />
 <link rel="stylesheet" type="text/css" href="css/crop.css">
</head>
<body>
  <div class="container wrap">
   <div class="left">
    <?php 
    //if image uploaded this section will shown
     if($img){
      echo '<h2>Select an area on image</h2>
              <img src="'.$img.'" id="imgc" data-id="'.$id.'" data-album="'.$albumid.'">';
     }
    ?>
   </div>
   <div class="right">
     <?php 
     //if image uploaded this section will shown
     if($img){ 
      //echo '<div>' ;
      echo '<h2>Preview</h2> 
            <div class="frame">
              <div id="preview">
                <img src="'.$img.'" > 
              </div>
            </div>
            <form action="">
              <input type="hidden" name="x" id="x">
              <input type="hidden" name="y" id="y">
              <input type="hidden" name="w" id="w">
              <input type="hidden" name="h" id="h">
              <input type="hidden" name="img" id="img">
             
              <button id="cropbtn">Crop</button>
            </form>';
     }
    ?>
   </div> 
<div style="clear:both"></div>
 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
 <script type="text/javascript" src="js/imgareaselect/jquery.imgareaselect.js"></script>
  <script type="text/javascript" src="js/crop.js"></script>
</body>
</html>