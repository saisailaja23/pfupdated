<?php 
session_start(); 
 //$data = file_get_contents('http://localhost/parentfinders/test.php?profile='.$_GET[profile].'&v=watch');
 //$aData = (explode(";-",$data));
//print_r($aData);
 $data = file_get_contents('http://localhost/parentfinders/test.php?profile='.$_GET['profile']);
 $aData = (explode(";-",$data));
$data = file_get_contents('http://localhost/parentfinders/getphotosvideos.php?profile='.$_GET[profile].'&v=watch');
$videos = (explode(";-",$data));
array_pop($videos);
$profile = $_GET['profile'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Videos - <?=$aData[3]?><?php if($aData[4] != '') { ?> & <?php } ?><?=$aData[4]?></title>
<style type="text/css">
<!--
/** 
 * Slideshow style rules.
 */
#slideshow {
	margin:0 auto;
	width:600px;
	height:263px;
	position:relative;
}
#slideshow #slidesContainer {
  margin:0 auto;
  width:560px;
  height:263px;
  overflow:auto; /* allow scrollbar */
  position:relative;
}
#slideshow #slidesContainer .slide {
  margin:0 auto;
  width:540px; /* reduce by 20 pixels of #slidesContainer to avoid horizontal scroll */
  height:240px;
}

/** 
 * Slideshow controls style rules.
 */
.control {
  display:block;
  width:39px;
  height:263px;
  text-indent:-10000px;
  position:absolute;
  cursor: pointer;
}
#leftControl {
  top:0;
  left:0;
  background:transparent url(img/control_left.jpg) no-repeat 0 0;
}
#rightControl {
  top:0;
  right:0;
  background:transparent url(img/control_right.jpg) no-repeat 0 0;
}

/** 
 * Style rules for Demo page
 */


#pageContainer {
   background-color: #FCEDCE;
    height: auto;
    margin: 0 auto;
    width: 692px;
}



.slide img {
  float: left;
  margin-left:135px;
}

-->
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  var currentPosition = 0;
  var slideWidth = 560;
  var slides = $('.slide');
  var numberOfSlides = slides.length;

  // Remove scrollbar in JS
  $('#slidesContainer').css('overflow', 'hidden');

  // Wrap all .slides with #slideInner div
  slides
    .wrapAll('<div id="slideInner"></div>')
    // Float left to display horizontally, readjust .slides width
	.css({
      'float' : 'left',
      'width' : slideWidth
    });

  // Set #slideInner width equal to total width of all slides
  $('#slideInner').css('width', slideWidth * numberOfSlides);

  // Insert controls in the DOM
  $('#slideshow')
    .prepend('<span class="control" id="leftControl">Clicking moves left</span>')
    .append('<span class="control" id="rightControl">Clicking moves right</span>');

  // Hide left arrow control on first load
  manageControls(currentPosition);

  // Create event listeners for .controls clicks
  $('.control')
    .bind('click', function(){
    // Determine new position
	currentPosition = ($(this).attr('id')=='rightControl') ? currentPosition+1 : currentPosition-1;
    
	// Hide / show controls
    manageControls(currentPosition);
    // Move slideInner using margin-left
    $('#slideInner').animate({
      'marginLeft' : slideWidth*(-currentPosition)
    });
  });

  // manageControls: Hides and Shows controls depending on currentPosition
  function manageControls(position){
    // Hide left arrow if position is first slide
	if(position==0){ $('#leftControl').hide() } else{ $('#leftControl').show() }
	// Hide right arrow if position is last slide
    if(position==numberOfSlides-1){ $('#rightControl').hide() } else{ $('#rightControl').show() }
  }	
});
</script>
<!--Make sure page contains valid doctype at the very top!-->

<link href="css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/anythingslider.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery.anythingslider.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
		$(function () {
			$('#slider1').anythingSlider({
				//width : 800,          // Override the default CSS width
				width : 560,          // Override the default CSS width
				height : 420, 
				easing: 'easeInOutExpo'
			});
		});
	</script>
<script type="text/javascript">
function setFPsCookie(c_name,value,exdays)
{
var exdate=new Date();
exdate.setDate(exdate.getDate() + exdays);
var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
document.cookie=c_name + "=" + c_value;
if(exdays > 0){
alert('Family added to favorites');
}else{
alert('Family removed from favorites');
}
location.reload(true);
}
</script>
<script type="text/javascript" src="js/flowplayer-3.2.6.min.js"></script> 
</head>

<body>
<div class="main_contatiner"> 

<div class="title_field"><div class="title"><img src="images/title_02.jpg" width="400" height="88" /> </div> 
</div>
<div class="colom_left">
<div class="menues">
<ul class="ul_style">
<li class="left_menu_style"><a href="http://www.myadoptionplan.com" class="selected">Home</a></li>
<li class="left_menu_style"><a href="http://www.myadoptionplan.com/GetStarted.html" class="menus">Getting Started</a></li>
<li class="left_menu_style"><a href="http://www.myadoptionplan.com/LiveChat.html" class="menus">Live Chat</a></li>
<li class="left_menu_style"><a href="http://hoa.cairsolutions.com/" class="menus">Adoptive Families</a></li>
<li class="left_menu_style"><a href="http://www.myadoptionplan.com/Questions.html" class="menus">Questions</a></li>
<li class="left_menu_style"><a href="http://www.myadoptionplan.com/Resources.html" class="menus">Resources</a></li>
<li class="left_menu_style"><a href="http://www.myadoptionplan.com/Testimonials.html" class="menus">Testimonials</a></li>
<li class="left_menu_style"><a href="http://www.myadoptionplan.com/ContactUs.html" class="menus">Contact us</a></li>
<li class="left_menu_style"><a href="http://www.myadoptionplan.com/Forms.html" class="menus"> Forms</a></li>



</ul>





</div>
</div>

<div class="main_center_container">

<div class="colom_middle_internal">
  <div class="body_main">
  <div class="colum_first">
<p>
 Heart of Adoptions, Inc. has many hopeful adoptive families waiting for a child. We will ask  you exactly what you are looking for in an adoptive family to create your "dream family" list, and then provide you family profiles to choose from that most closely match your preferences.
</p>
 
   <p> Only a handful of our waiting families are on our website, so if you don't find the perfect family here, contact us for additional profiles. If we don't already have the right family for you, we will search on your behalf until you find the ideal family for your child.</p>

  
  <p>  All waiting families have been pre-screened and meet requirements for adopting a child. If you have any questions about one of our waiting families, even if you aren't already working with heart of Adoptions, Inc. we'd be happy to talk to you.</p>  </div>
  <div class="middle"> </div>
 <div class="right_col">
 
  <?php
     require_once 'slide.php';
        echo slide();
  ?> 
  
  </div>
  </div>
   <div class="body_line"> </div>
   <div class="menu_e">
   <ul class="internalul">
   <li class="internalli"><a href="mainProfile.php?profile=<?=$_GET['profile']?>" class="menuinternal"> Main Profile</a></li>
      <li class="internalli"><a href="photographs.php?profile=<?=$_GET['profile']?>" class="menuinternal">  Photographs</a></li>
       <li class="internalli"><a href="videoPage.php?profile=<?=$_GET['profile']?>&v=watch" class="selectedi">  Video</a></li>
      <li class="internalli"><a href="contactus.php?profile=<?=$_GET['profile']?>" class="menuinternal">  Contact us</a></li>
   
   </ul>
   
   
   </div>
   <div class="menu_e_below_line"> </div>
   <div class="body_internal"> 
 <div class="about_uswidth">Video Gallery</div>
<div class="photo_page">
<div id="pageContainer">

<ul id="slider1">
			<?php foreach ($videos as $key=>$val) :?>
			<?php echo "<li>".$val."</li>";?>
			<?php endforeach; ?>
			</ul>
  <!-- Slideshow HTML -->
  <!--<div id="slideshow">
    <div id="slidesContainer">
      <div class="slide">
       <img src="img/1.jpg" />
        
      </div>
      <div class="slide">
        
         <img src="img/2.jpg" />
       
      </div>
      <div class="slide">
         <img src="img/3.jpg" />
        

      </div>
      <div class="slide">
       <img src="img/4.jpg" />
      </div>
    </div>
  </div>-->
  <!-- Slideshow HTML -->
</div>

 
 
 
 
 </div>
   </div>
</div>
<div class="footer_title"><img src="images/temp_33.jpg" width="356" height="75" /></div>
  <div class="copy_right">@2011 Heart of Adoptions, Inc. All Rights Reserved</div>

</div>
<div class="colom_right"></div>

</div>

</body>
</html>
