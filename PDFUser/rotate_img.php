<?php
function Rotate_img($filename, $degrees, $temp_file_name)
{
    //echo " rotate ".$filename."<br/>";
    $basePath   = $GLOBALS['dir']['root'];
    $baseURL    = $GLOBALS['site']['url'];

    /*$exts = split("[/\\.]", $filename) ;
    $n = count($exts)-1;
    $exts = $exts[$n];
    list($imageName, $imageExt)   = split('.'.$exts, $filename);
    $imageExt	= $exts;
    echo "extensions  ".$imageExt."<br/>";
    */
    $source     = imagecreatefromjpeg($filename);
    // Rotate
    $bg = imagecolortransparent($source);
    $rotate = imagerotate($source, $degrees, $bg,1);
    // Output
    $img                            = $basePath."PDFTemplates/temp/$temp_file_name.jpg";
    $img_rtn                        = "PDFTemplates/temp/$temp_file_name.jpg";
    imagejpeg($rotate,$img,100);
    //echo " inside rotate ".$img."<br/>";
    //exit();
    return $img_rtn;
    
   /* $imagick = new Imagick();
    //echo $filename;
    $imagick->readImage("testimg/59.jpg");
    $imagick->rotateImage(new ImagickPixel('white'), $degrees);
    $imagick->writeImage("temp/".$temp_file_name.".jpg");*/
}

?>