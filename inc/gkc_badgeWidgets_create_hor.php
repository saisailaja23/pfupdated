<?php

////////////////////////////////////////////////////////////////
//               BADGE WIDGETS				                  //
//    Created : 20 April, 2010			                      //
//    Creator : Gautam Chaudhary (Pulprix Developments)       //
//    Email : gkcgautam@gmail.com                             //
//    This product is owned by its creator                    //
//    This product cannot by redistributed by anyone else     //
//                 Do not remove this notice                  //
////////////////////////////////////////////////////////////////

require_once("gkc_badgeWidgets.inc.php");

class createBadge {
 
    function load($filename) { //Load image file and convert to a readable state for php
      $image_info = getimagesize($filename); //Get image information
      $this->image_type = $image_info[2]; //Get image type
     
      //Cycles through image types
      if( $this->image_type == IMAGETYPE_JPEG ) {
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
         $this->image = imagecreatefrompng($filename);
      }
    }
    
    function save($filename, $image_type) { //Saves image to specified location default image type is beginning image type but can be changed
      //Cycles through image types
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename, 100);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image,$filename);
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image,$filename);
      } 
    }
    
    function output($image_type=IMAGETYPE_JPEG) { //Outputs image to be read through browser or html image tag, default output is jpeg
      //Cycles through image types
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image);        
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image);
      }   
    }
    
    function getWidth() { //Obtains image width
      return imagesx($this->image);
    }
    
    function getHeight() { //Obtains image height
      return imagesy($this->image);
    }
    
    function resizeToHeight($height) { //Resizes image to specified height and dynamic width
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
    }
    
    function resizeToWidth($width) { //Resizes image to specified width and dynamic height
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
    }
    
    function scale($scale) { //Resizes image to dynamic height and dynamic width by percent
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100; 
      $this->resize($width,$height);
    }
    
    function resize($width,$height) { //Resizes image to dynamic height and dynamic width by specific amount
      $new_image = imagecreatetruecolor($width, $height);
      imagesavealpha($new_image, true);
      imagealphablending($new_image, false);
      imagesavealpha($new_image,true);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;
    }
    
    function badge($attExists=0){ //Default is no attributes
	
		global $badgewidgetConf;
    
        $this->resizeToWidth(65); //Resizes profile picture to 75 width dynamic height
        
        if($attExists){ //If there is text other than just profile pictures
            $totalWidth = $this->getWidth() + 280;
            $totalHeight = $this->getHeight() + 120;
			
			if($totalWidth<355)
			{
				$totalWidth = 355;
			}
        } else {
            $totalWidth = $this->getWidth() + 45; //Sets totalWidth if no other text
            $totalHeight = $this->getHeight() + 20; //Sets totalHeight if no other text
        }
		
            
        //Start Create Profile Pic//
        $profile = imagecreatetruecolor($totalWidth, $totalHeight); //Creates blank canvas to add profile picture onto
        $nastyColor = imagecolorallocate($profile, 255, 0, 255); //Gives us a nasty color to fill the background with to make it transparent
        imagefill($profile, 0, 0, $nastyColor); //Fills background
        imagecopyresampled($profile, $this->image, 0, 0, 0, 0, $this->getWidth(), $this->getHeight(), $this->getWidth(), $this->getHeight()); //Add profile pic to blank canvas
        
        $cBackground = imagecolorclosest($profile, 255, 0, 255); //Important for transparency
        imagecolortransparent($profile, $cBackground); //Important for transparency
        //End Create Profile Pic//
    
        //Start create background//
        if($this->getHeight() <= 60){
            $totalHeight = 90; //Resets height to be a bit more suitable
            $im = imagecreatetruecolor($totalWidth, $totalHeight); //Create image with enough room for side panel, image, and padding
        } else {
            $im = imagecreatetruecolor($totalWidth, $totalHeight);
        }
    
        $border = imagecolorallocate($im, $badgewidgetConf['Image_border']['R'], 
			$badgewidgetConf['Image_border']['G'], 
			$badgewidgetConf['Image_border']['B']); //Set border color
        $padding_bg = imagecolorallocate($im, $badgewidgetConf['Image_paddingBG']['R'], 
			$badgewidgetConf['Image_paddingBG']['G'], 
			$badgewidgetConf['Image_paddingBG']['B']); //Set padding color
        $main_bg = imagecolorallocate($im, $badgewidgetConf['Image_MainBG']['R'], 
			$badgewidgetConf['Image_MainBG']['G'], 
			$badgewidgetConf['Image_MainBG']['B']); //Set main background color
        $side_bg = imagecolorallocate($im, $badgewidgetConf['Image_companyNameBG']['R'], 
			$badgewidgetConf['Image_companyNameBG']['G'], 
			$badgewidgetConf['Image_companyNameBG']['B']); //Set company name background color
        
        imagefill($im, 0, 0, $border); //Fills background
 
        imagefilledrectangle($im, 1, ($totalHeight) - 2, ($totalWidth) - 2, 1, $padding_bg); //Fills padding
    
        //imagefilledrectangle($im, 4, ($totalHeight) - 6, ($totalWidth) - 6, 4, $main_bg); //Fills main color
        
        imagefilledrectangle($im, 1, ($totalHeight), 30, 1, $side_bg); //Adds company name background color
    
        //End Create Background//
        
        //Start Merge Profile Pic and BG//
        $setX = 38; //Sets profile picture x axis out enough to miss the 30 pixel wide side company name
        $setYv = 48;
        if($this->getHeight() < 100){
            $setY = ((imagesy($im)-imagesy($profile)) /2) + 10; //Sets profile picture y axis if height is under 100
            $setXv = ((imagesx($im)-imagesx($profile)) /2) + 290;
        } else {
            $setY = ((imagesy($im)-imagesy($profile)) /2) + 10; //Sets profile picture y axis if height is over 100
            $setXv = ((imagesx($im)-imagesx($profile)) /2) + 290;
        }
        $video = imagecreatefromjpeg('video.jpg');
        imagecopymerge($im, $profile, $setX, $setY, 0, 0, imagesx($im), imagesy($profile), 100); //Adds profile to background
        imagecopymerge($im, $video, $setXv, $setYv, 0, 0, 14, 7, 100);
        //End Merge Profile Pic and BG//
        
        $this->image = $im;
    }
    
    function addCoName($txt){ //only supports up to 16 characters at the moment
		
		global $badgewidgetConf;
    
        $face = $badgewidgetConf['Image_companyNameFont']; //Tells what font to use
    
        $im = $this->image; //Assigns php rendered image
        
        $white = imagecolorallocate($im, $badgewidgetConf['Image_companyName']['R'],
			$badgewidgetConf['Image_companyName']['G'], $badgewidgetConf['Image_companyName']['B']); //Declares font color
        
        if(strlen($txt) >= 10){
            if(strlen($txt) > 20){
                $txt = substr($txt, 0, 19);
            }
            $txt = substr($txt, 0, 9)."\n".substr($txt, 9, strlen($txt));
            
            $fontSize = imagettfbbox(10, 0, $face, $txt); //Gets font dimensions
        
            $fontHeight = $fontSize[4] - $fontSize[6]; //Gets total font height technically width but it will be rotated
        
            $centerFont = (($this->getHeight()) - $fontHeight) / 2; //Used to center the font
            
            imagettftext($im, 10, 90, 12, $fontHeight + $centerFont, $white, $face, $txt); //Adds font to image
            
        } else {
            $txt = $txt;
        
            $fontSize = imagettfbbox(12, 0, $face, $txt); //Gets font dimensions
            
            $fontHeight = $fontSize[4] - $fontSize[6]; //Gets total font height technically width but it will be rotated
        
            $centerFont = (($this->getHeight()) - $fontHeight) / 2; //Used to center the font
        
            imagettftext($im, 12, 90, 20, $fontHeight + $centerFont, $white, $face, $txt); //Adds font to image
        }
    }
    
    function addText($val, $type, $numAtt, $order){
    
        global $badgewidgetConf;
		
		$face = $badgewidgetConf['Image_LabelFont']; //Tells what font to use
    
        $im = $this->image; //Assigns php rendered image
        
        $labelC = imagecolorallocate($im, $badgewidgetConf['Image_labelC']['R'],
			$badgewidgetConf['Image_labelC']['G'],$badgewidgetConf['Image_labelC']['B']); //Declares label color
        $fontC = imagecolorallocate($im, $badgewidgetConf['Image_fontC']['R'],
			$badgewidgetConf['Image_fontC']['G'],$badgewidgetConf['Image_fontC']['B']); //Decalres font color
        
        $fontSize = imagettfbbox(10, 0, $face, ''); //Gets font dimensions
        
        $top = ceil((imagesy($im)- 42) /2) - 36;
        
        if($order > 1){
            for($i=2;$i<=$order;$i++){
                $top = $top + 20;
            }
        }
        
        imagettftext($im, 10, 0, 120, $top, $labelC, $face, $type." :"); //Adds label to image
        imagettftext($im, 10, 0, 200, $top, $fontC, $face, $val); //Adds text to image
    }
};
 
$createBadge = new createBadge;
 
?>