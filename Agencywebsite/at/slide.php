<?php
function slide()
{
    $jarray = '';
$jarrayArray = array();
$f = 0;
//for ( $counter = 1; $counter <= 1000; $counter ++) {
if (!isset($_SESSION['jarray'])) {
	  $ffdata = file_get_contents('http://localhost/parentfinders/badgefeaturedfamililies.php?featuredFamily=true');
	$featuredFamilyData = (explode("#####",$ffdata));
//echo $ffdata;
  //      echo "<pre>";print_r($featuredFamilyData);echo "</pre>";
        
$_SESSION['page'] = 'index';	

	foreach($featuredFamilyData as $ffkey)
	{
            $familyData=explode(';-',$ffkey);

			if ($familyData[3] != '') {

				$ffname = $familyData[1];
				if($familyData[1] != ''){$ffname .= " & ".$familyData[2];}
				
				$jarray .= '["'.$familyData[3].'", "#", "new", "'.$ffname.'","'.$familyData[0].'",],';
				$jarrayArray[]= '["'.$familyData[3].'", "#", "new", "'.$ffname.'","'.$familyData[0].'",]';
                                
                                //$jarray .= '["'.$familyData[3].'", "#", "new", "'.$ffname.'",],';
				//$jarrayArray[]= '["'.$familyData[3].'", "#", "new", "'.$ffname.'",]';
                                
				$f++;
			}
                        
	}
	if ($f==0)
		$jarray = '["images/noimage.jpg", "#", "new",],';
		
	$_SESSION['jarray'] = $jarray;
	$_SESSION['jarrayArray'] = $jarrayArray;
}
shuffle($_SESSION['jarrayArray']);
$_SESSION['jarray'] = implode(',',$_SESSION['jarrayArray']);

  
                $slidecontent = '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
                <script type="text/javascript" src="js/fadeslideshow.js">

                /***********************************************
                * Ultimate Fade In Slideshow v2.0- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
                * This notice MUST stay intact for legal use
                * Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
                ***********************************************/

                </script>

                <script type="text/javascript">

                var mygallery=new fadeSlideShow({
                        wrapperid: "fadeshow1", //ID of blank DIV on page to house Slideshow
                        dimensions: [305, 209], //width/height of gallery in pixels. Should reflect dimensions of largest image
                        imagearray: [
                                '. $_SESSION['jarray'].'
                                //<--no trailing comma after very last image element!
                        ],
                        imageid:\'active\',
                        displaymode: {type:\'auto\', pause:4000, cycles:0, wraparound:false},
                        persist: false, //remember last viewed slide and recall within same session?
                        fadeduration: 700, //transition duration (milliseconds)
                        descreveal: "ondemand",
                        togglerid: "slideshowtoggler"
                })



                </script> 
                  <div class="slide_show">

                  <div class="slide_photo">
                  <div id="fadeshow1">

                  </div>

                <div id="slideshowtoggler" align="center">
                <a href="#" class="prev"><img src="backward.jpg" style="border-width:0; width:20px; hetght:20px" /></a> <span class="status" style="margin:0 50px; font-weight:bold; color:#993400"></span> <a href="#" class="next"><img src="forward.jpg" style="border-width:0; width:20px; hetght:20px" /></a>
                </div>
                  <div class="slide_title"><img src="images/temp_12.jpg" width="290" height="38" /></div>
                  </div>
                 </div>';
return $slidecontent;
}