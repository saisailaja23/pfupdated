<?php
require_once( '../../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolAlbums.php');
include 'processors/crop/imagetool.class.php';

$site_url ="{$site['url']}";
$root_dir ="{$dir['root']}";

$img_dir = $root_dir."modules/boonex/photos/data/files/";

$img=$_GET['img'];
$server=$_GET['server'];

$id=rand(300, 999);
    list($width, $height, $type, $attr) = getimagesize($img);
    if($width>1000 || $height>900){  
      $newname="temp_".$id.".jpg";
      $imgTObj = new ImageTool($img);
      //*** 2) Resize image (options: exact, portrait, landscape, auto, crop)
      $imgTObj -> resizeImage(1000, 900);
      //*** 3) Save image
      $imgTObj -> saveImage($img_dir.$newname, 100);
      $img=$site_url.$newname;
     // $filename=$newname;
    }
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
      echo 
        '<h2>Select an area on image</h2>
        <div class="img-container">
          <img src="'.$img.'" id="imgc" data-server="'.$server.'" data-album="'.$albumid.'">
          <div class="img-loader">loading....</div>
        </div>';
     }
    ?>
    <form action="" id="crop_form" >
      <input type="hidden" name="x" id="x">
      <input type="hidden" name="y" id="y">
      <input type="hidden" name="w" id="w">
      <input type="hidden" name="h" id="h">
      <input type="hidden" name="server" id="server" value="<?php echo $server ?>">
      <input type="hidden" name="img" id="img" value="<?php echo $img ?>">
      <button id="cropbtn">Crop</button>
    </form>

   </div>

<div style="clear:both"></div>
 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script type="text/javascript" src="js/jquery.plugins.js"></script>
 <script type="text/javascript" src="js/imgareaselect/jquery.imgareaselect.js"></script>
  <script type="text/javascript" src="js/pcrop.js"></script>
</body>
</html>