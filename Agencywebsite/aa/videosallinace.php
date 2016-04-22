<?php
session_start();
 //$data = file_get_contents('http://www.parentfinder.com/test.php?profile='.$_GET[profile].'&v=watch');
 //$aData = (explode(";-",$data));
//print_r($aData);
 $data = file_get_contents('http://www.parentfinder.com/test.php?profile='.$_GET['profile']);
 $aData = (explode(";-",$data));
$data = file_get_contents('http://www.parentfinder.com/getphotosvideos.php?profile='.$_GET[profile].'&v=watch');
$videos = (explode(";-",$data));
array_pop($videos);
$profile = $_GET['profile'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<title>Videos - <?=$aData[3]?><?php if($aData[4] != '') { ?> & <?php } ?><?=$aData[4]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style>
<link rel="stylesheet" type="text/css" media="screen, print" href="css/style.css" />
<script type="text/javascript" language="javascript" src="js/scripts.js" ></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="js/animatedcollapse.js"></script>
<!--live chat -->
<script type="text/javascript">
(function() {
var livechat_params = '';

var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true;
var lc_src = ('https:' == document.location.protocol ? 'https://' : 'http://');
lc_src += 'chat.livechatinc.net/licence/1026499/script.cgi?lang=en&groups=0';
lc_src += ((livechat_params == '') ? '' : '&params='+encodeURIComponent(encodeURIComponent(livechat_params)));
lc.src = lc_src;
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s);
})();
</script>
<!-- End LiveChat track tag. See also www.livechatinc.com -->

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
   background-color: #EFEFFA;
    height: auto;
   /* margin: 0 auto;*/
   margin-left: 30px;
   /* width: 692px; */
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
<body id="categories">
<div id="wrap">
<div id="top-intro"> </div>
<!--top-intro -->
<div id="masthead"><a href="http://www.adoption-alliance.com" title="Adoption Alliance" id="logo"><span>Infant adoption agencies and services in texas, tx | Adoption Alliance</span></a>
  <div id="livechat"><img src="http://chat.livechatinc.net/licence/1026499/button.cgi?lang=en&amp;groups=0" style="cursor:pointer;cursor:hand" onClick="window.open('http://chat.livechatinc.net/licence/1026499/open_chat.cgi?groups=0'+'&amp;s=1&amp;lang=en&amp;dc='+encodeURIComponent(document.cookie+';l='+document.location+';r='+document.referer+';s='+typeof lc_session),'Czat_1026499','width=220,height=73,resizable=yes,scrollbars=no,status=1');"/></div>
  <p class="ppc-phone"><span>call now 1800-626-4324, for a personalized adoption plan</span></p>
</div>
<!--masterhead -->
<ul id="nav">
  <li id="nav-01"><a href="http://www.adoption-alliance.com/birth-parents-main-texas-tx" title=""><span>Birth Parents adoption texas</span></a>
    <ul>
      <li><a href="http://www.adoption-alliance.com/birth-parents-main-texas-tx/making-an-adoption-plan" title=""><span>Making an Adoption Plan</span></a></li>
      <li><a href="http://www.adoption-alliance.com/birth-parents-main-texas-tx/frequently-asked-questions" title=""><span>Frequently Asked Questions</span></a></li>
      <li><a href="http://www.adoption-alliance.com/birth-parents-main-texas-tx/birth-parent-comments" title=""><span>Birth Parent Comments</span></a></li>
      <li><a href="http://www.adoption-alliance.com/category/families-in-waiting" title=""><span>Choose Adoptive Parent</span></a></li>
    </ul>
  </li>
  <li id="nav-02"><a href="http://www.adoption-alliance.com/adoptive-parents-main-texas-tx" title=""><span>Adoptive Parents adoption in texas</span></a>
    <ul>
      <li><a href="http://www.adoption-alliance.com/adoptive-parents-main-texas-tx/general-overview" title=""><span>General Overview</span></a></li>
      <li><a href="http://www.adoption-alliance.com/adoptive-parents-main-texas-tx/frequently-asked-questions" title=""><span>Frequently Asked Questions </span></a></li>
      <li><a href="http://www.adoption-alliance.com/adoptive-parents-main-texas-tx/adoptive-parent-comments" title=""><span>Adoptive Parent Comments </span></a></li>
    </ul>
  </li>
  <li id="nav-03"><a href="http://waiting-aa.cairsolutions.com/" title=""><span>Waiting Families adoption and texas</span></a></li>
  <li id="nav-04"><a href="http://www.adoption-alliance.com/about-our-agency-texas-tx" title=""><span>Our adoption agency tx</span></a>
    <ul>
      <li><a href="http://www.adoption-alliance.com/about-our-agency-texas-tx/about-us" title=""><span>About Us</span></a></li>
      <li><a href="http://www.adoption-alliance.com/about-our-agency-texas-tx/history-licensing" title=""><span>History &amp; Licensing</span></a></li>
      <li><a href="http://www.adoption-alliance.com/about-our-agency-texas-tx/our-approach" title=""><span>Unique Approach</span></a></li>
      <li><a href="http://www.adoption-alliance.com/about-our-agency-texas-tx/our-staff" title=""><span>Our Staff</span></a></li>
      <li><a href="http://www.adoption-alliance.com/in-the-media-texas-tx" title="In The Media"><span>In The Media</span></a></li>
      <li><a href="http://www.adoption-alliance.com/about-our-agency-texas-tx/employment-opportunities" title="employment opportunities"><span>Employment Opportunities</span></a></li>
    </ul>
  </li>
  <li id="nav-05"><a href="http://www.adoption-alliance.com/forms-and-documents-texas-tx" title=""><span>Adoption Forms &amp; Documents texas</span></a>
    <ul>
      <li><a href="http://www.adoption-alliance.com/forms-and-documents-texas-tx/birth-parent-forms" title=""><span>Birth Parent Forms</span></a></li>
      <li><a href="http://www.adoption-alliance.com/forms-and-documents-texas-tx/adoptive-parent-forms" title=""><span>Adoptive Parent Forms</span></a></li>
      <li><a href="http://www.adoption-alliance.com/forms-and-documents-texas-tx/agency-newsletters" title=""><span>Agency Newsletters</span></a></li>
      <li><a href="http://www.adoption-alliance.com/forms-and-documents-texas-tx/recommended-reading" title=""><span>Recommended Reading</span></a></li>
    </ul>
  </li>
  <li id="nav-06"><a href="http://www.adoption-alliance.com/contact" title=""><span>Contact texas adoption agencies</span></a></li>
</ul>
<!-- end #nav -->
<div id="header-image"> <a rel="nofollow" href="http://www.adoption-alliance.com/birth-parents-main-texas-tx" id="header-pregnant" title=""></a> <a rel="nofollow" href="http://www.adoption-alliance.com/adoptive-parents-main-texas-tx" id="header-adopt" title=""></a> </div>
<div id="column-left">
    <h1>
     <a style="color:#FFF;" href="index.php?profile=<?=$_GET['profile']?>" >Home</a>&nbsp;&nbsp;&nbsp;
     <a style="color:#FFF;" href="mainProfile.php?profile=<?=$_GET['profile']?>" >Main Profile</a>&nbsp;&nbsp;&nbsp;
     <a style="color:#FFF;" href="photographs.php?profile=<?=$_GET['profile']?>" >Photographs</a>&nbsp;&nbsp;&nbsp;
     <a style="color:#FFF;" href="videosallinace.php?profile=<?=$_GET['profile']?>&v=watch" >Video</a>&nbsp;&nbsp;&nbsp;
     <a style="color:#FFF;" href="http://www.adoption-alliance.com/contact" TARGET="_blank">Contact us</a>
    </h1>
    <h2 id="search-index">Video Gallery</h2>
  <div class="content">
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
  <!--content-->
  </div>
<!-- end #column-left -->

<div id="column-right">
  <div class="content">
    <h2 class="special">contact us, quik contact form</h2>
    <p>Have questions about adoption,<strong> &quot;the Adoption Alliance&quot; </strong>can help you? Complete the quick contact form below and one of our staffs will respond in a timely fashion. You can also <strong>call us at  1800-626-4324.</strong></p>
    <a rel="nofollow" href="http://www.adoption-alliance.com/contact" class="ppc-phone"></a>
    <!--<iframe src ="https://www.myadoptionportal.com/signupmp.php?pluginoption=userslogin&type=signup&key_id=59" width="350" height="680" frameborder="0" scrolling="no" id ="signupframe" style="overflow:visible;"> -->
    <p>Your browser does not support iframes.</p>
    </iframe>
    <table border="0" id="address">
      <tr>
        <td><p><strong>Adoption Alliance</strong><br />
            7303 Blanco Road<br />
            San Antonio, Texas 78216</p>
          <p><strong>Toll Free: </strong><br />
            1-800-626-4324<br />
            Tel: (210) 349-3991<br />
            FAX: (210) 349-8075</p></td>
        <td><p><strong>Corpus Christi, Texas</strong><br />
            (361) 884-0057</p>
          <p><strong>Las Vegas, Nevada</strong><br />
            (702) 968-1986</p>
          <p><strong>Reno, Nevada</strong><br />
            (775) 851-7888</p></td>
      </tr>
    </table>
    
  </div>
  <!--end content -->
</div>
<!-- end #column-right -->
<div class="clear"></div>
</div>
<!-- end #wrap -->
<div id="footer-wrap">
  <div id="footer">
    <div class="left">
    <!--  <p><strong>Mission Statement:</strong><br />
        The Adoption Alliance, a non-profit adoption agency, was founded on the belief that lives of children can be changed forever through the adoption process. We provide professional adoption services to birth parents and adoptive families by educating and coordinating all aspects of the adoption process. </p> -->
      
    </div>
    <!-- footer-left -->
    <div class="right">
      <p><a href="javascript:animatedcollapse.toggle('top-intro')" title="intro"><u>Adoption Alliance</u></a>, 7303 Blanco Road San Antonio, Texas 78216 <br />
        Toll Free: 1-800-626-4324 | FAX: (210) 349-8075 </p>
      <p>Copyright &copy; 2009 Adoption Alliance | <a rel="nofollow" href="http://www.ndmr.com" target="_blank">Web Design &amp; Internet Marketing by <strong>New Dimension Marketing &amp; Research</strong></a></p>
    </div>
    <!-- footer-right -->
    <div class="clear"></div>
  </div>
  <!-- end #footer -->
</div>
</body>
</html>