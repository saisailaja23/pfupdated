
<?php
 include( '../inc/header.inc.php' );
 
  define ("path","PDF/");
   if (is_uploaded_file($_FILES['PDF']['tmp_name'])) {
    $thisdir = getcwd();
    if (!file_exists("PDF")){
    mkdir($thisdir ."/PDF" , 0777);
    }
      if ($_FILES['PDF']['type'] != "application/pdf") {
         echo "<p>PDF should be uploaded in PDF format.</p>";
      } else {
         $name = $_POST['name'];     
             $result = move_uploaded_file($_FILES['PDF']['tmp_name'], path."$name.pdf");        
          if ($result == 1)  echo "<meta HTTP-EQUIV=\"REFRESH\" content=\"0; url=".$_SERVER['HTTP_REFERER']."\">";
        else echo "<p>There was a problem uploading the file.</p>";
      }  
      
    
}  
?>
