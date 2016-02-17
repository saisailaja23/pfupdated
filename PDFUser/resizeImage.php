<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php
define('BX_PROFILE_PAGE', 1);
//require_once( './inc/header.inc.php' );
function Resize_temp($tmpName, $wd, $ht, $temp_file_name)
    {

        $basePath               = $GLOBALS['dir']['root'];
        $uploadedFile           = $tmpName;

        //echo " resize ".$uploadedFile."<br/>";
        //getting the image dimensions
        list($width, $height) = getimagesize($uploadedFile);

        //saving the image into memory (for manipulation with GD Library)
        $myImage = imagecreatefromjpeg($uploadedFile);

        // calculating the part of the image to use for thumbnail
        if ($width > $height) {
          $y = 0;
          $x = ($width - $height) / 2;
          $smallestSide = $height;
        } else {
          $x = 0;
          $y = ($height - $width) / 2;
          $smallestSide = $width;
        }

        // copying the part into thumbnail
        $thumbSize = 110;
        $thumbheight = $ht;
        $thumbwidth  = $wd;
        //echo $thumbheight;
        //echo $thumbwidth;
        $thumb = imagecreatetruecolor($thumbwidth, $thumbheight);
        imagecopyresampled($thumb, $myImage, 0, 0, 0, 0, $thumbwidth, $thumbheight, $width, $height);

        $img                            = $basePath."PDFTemplates/temp/$temp_file_name.jpg";
        $img_rtn                        = "PDFTemplates/temp/$temp_file_name.jpg";
        imagejpeg($thumb, $img, 100);  //imagejpeg($resampled, $fileName, $quality);
        imagedestroy($myImage);
        imagedestroy($thumb);        
        return $img_rtn;
    }

?>

<?php

/*
* File: SimpleImage.php
* Author: Simon Jarvis
* Copyright: 2006 Simon Jarvis
* Date: 08/11/06
* Link: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details:
* http://www.gnu.org/licenses/gpl.html
*
*/

class SimpleImage {

   var $image;
   var $image_type;

   function load($filename) {
      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {

         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {

         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {

         $this->image = imagecreatefrompng($filename);
      }

   }
   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {

       if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {

         imagegif($this->image,$filename);
      } elseif( $image_type == IMAGETYPE_PNG ) {

         imagepng($this->image,$filename);
      }
      if( $permissions != null) {

         chmod($filename,$permissions);
      }
   }
   function output($image_type=IMAGETYPE_JPEG) {

      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {

         imagegif($this->image);
      } elseif( $image_type == IMAGETYPE_PNG ) {

         imagepng($this->image);
      }
   }
   function getWidth() {

      return imagesx($this->image);
   }
   function getHeight() {

      return imagesy($this->image);
   }
   function resizeToHeight($height) {

      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }

   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }

   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100;
      $this->resize($width,$height);
   }

   function resize($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;
   }

}
?>