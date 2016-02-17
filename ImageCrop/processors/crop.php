<?php
require_once( '../../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'images.inc.php');
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolAlbums.php');


include 'imagetool.class.php';

  $dir_root =BX_DIRECTORY_PATH_ROOT;
  $site_url=BX_DOL_URL_ROOT;
  $img_dir = $dir_root ."/modules/boonex/avatar/data/avatarphotos/";
  $img_app_temp=$dir_root ."ImageCrop/temp/";


  extract($_REQUEST);

  $app_path= $site_url. "ImageCrop/";

  list($width, $height, $type, $attr) = getimagesize($img);
  if($width>600 || $height>500){  
      $newname="temp_".$profile_id.".jpg";
      $imgTObj = new ImageTool($img);

      //*** 2) Resize image (options: exact, portrait, landscape, auto, crop)
      $imgTObj -> resizeImage(600, 500);
      //*** 3) Save image
      $imgTObj -> saveImage($img_app_temp.$newname, 100);

      $img=$app_path.'temp/'.$newname;

      @chmod($img_app_temp.$newname, 0644);

     // $filename=$newname;
  }else{
      $newname="temp_".$profile_id.".jpg";
      $imgTObj = new ImageTool($img);

      //*** 2) Resize image (options: exact, portrait, landscape, auto, crop)
      $imgTObj -> resizeImage($width, $height);
      //*** 3) Save image
      $imgTObj -> saveImage($img_app_temp.$newname, 100);

      $img=$app_path.'temp/'.$newname;

      @chmod($img_app_temp.$newname, 0644);
  }
?>
<html lang="en">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 <title>Image Crop</title>
 <link rel="stylesheet" href="../lib/imgareaselect/css/imgareaselect-default.css" />
 <link rel="stylesheet" type="text/css" href="../asserts/css/crop.css">
</head>
<body>
  <div class="container wrap">
   <div class="left">
    <?php 
    //if image uploaded this section will shown
     if($img){
      echo '<h2>Select an area on image</h2>
              <div class="img-container">
              <img src="'.$img.'" id="imgc">
              <div class="img-loader">loading....</div>
              </div>';
     }
    ?>
    <form action="" id="crop_form">
      <input type="hidden" name="profile_id" id="profile_id" value="<?php echo $profile_id  ?>">
      <input type="hidden" name="physical_site_path" id="physical_site_path" value="<?php echo $physical_site_path  ?>">
      <input type="hidden" name="site_url" id="site_url" value="<?php echo $site_url  ?>">
      <input type="hidden" name="x" id="x">
      <input type="hidden" name="y" id="y">
      <input type="hidden" name="w" id="w">
      <input type="hidden" name="h" id="h">
      <input type="hidden" name="img" id="img" value="<?php echo $newname ?>">
      <button id="cropbtn">Crop</button>
    </form>

   </div>
   <div class="right">
   
   </div> 
<div style="clear:both"></div>
 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
 <script type="text/javascript" src="../lib/imgareaselect/jquery.imgareaselect.js"></script>
  <!-- <script type="text/javascript" src="../js/crop.js"></script> -->
  <script type="text/javascript">
  $(function(){

  var
    x = 0,
    y = 0,
    w = 0,
    h = 0,
    rw = 302, //preview width;
    rh = 230; //preview height
  //setvalues
  // $('img#imgc').on('load', function() {
  //     alert("hi");
  // });
  //Calling imgAreaSelect plugin
  $('img#imgc').imgAreaSelect({
    handles: false,
    onInit: init,
    onSelectEnd: setValue,
    onSelectChange: setValue,
    aspectRatio: '4:3',
    fadeSpeed: 200,
    minWidth: 100,
    minHeight: 100,
  });

  function init(img, selection) {
    $('.img-loader').hide();
    $('#imgc').css('opacity', 1);
    //$('#preview img').width('100%');
  }
  //setvalue function
  function setValue(img, selection) {
    if (!selection.width || !selection.height)
      return;
    x = selection.x1;
    y = selection.y1;
    w = selection.width;
    h = selection.height;
    $('#x').val(x);
    $('#y').val(y);
    $('#w').val(w);
    $('#h').val(h);
  

  }
  //ajax request get the 
  function getCImage() {
    $("#cropbtn").addClass("disabled").html("croping...");
   
    console.log($("#crop_form").serialize());
    $.ajax({
      type: "POST",
      data: $("#crop_form").serialize(),
      url: "process.php",
      cache: false,
      success: function (response) {
        // alert('Congratulations you have saved your Avatar');
        console.log(response);
        setTimeout(function () {
          parent.$.prettyPhoto.close();
        }, 1000);
        //alert('Photo cropped successfully!');
        //alert("crop success");
        $("#cropbtn").addClass("enable").html('crop');
        // $("#output").html("");
        // $("#cropbtn").removeClass("disabled").html("crop");
        // $("#output").html("<h2>Out put</h2><img src='" + response + "' />");
      },
      error: function () {

        alert("error on ajax");
      },
    });
  }
  //preview function
  function preview(img, selection) {
    if (!selection.width || !selection.height) {
      return;
    }
    var scaleX = rw / selection.width;
    var scaleY = rh / selection.height;
    $('#preview img').css({
      width: Math.round(scaleX * img.width),
      height: Math.round(scaleY * img.height),
      marginLeft: -Math.round(scaleX * selection.x1),
      marginTop: -Math.round(scaleY * selection.y1)
    });
  }

  //will triger on crop button click 
  $("#cropbtn").click(function (e) {
    e.preventDefault();
    if (w === 0 || h === 0) {
      alert("Please select an area first!");
      return;
    }
    getCImage();
  });
  });
  </script>
</body>
</html>