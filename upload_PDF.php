<?php
require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
    
$parent_ID                              =   getLoggedId();

if (isset($_FILES['file'])) {

 $test = db_arr("SELECT template_user_id,lastupdateddate FROM `pdf_template_user` WHERE user_id = '$parent_ID' ORDER BY `pdf_template_user`.`lastupdateddate`  DESC LIMIT 1");
 
//$currentDate      =	date("Y-m-d",$test[lastupdateddate]);
$currentDate = date("Y-m-d",strtotime($test[lastupdateddate]));

 $uploadfile     = $GLOBALS['dir']['root'].'PDFTemplates/user/'.$parent_ID.'_'.$test[template_user_id].'_'.$currentDate.'.pdf';
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile))
    {
     print_r("{state: true, name:'".str_replace("'","\\'",$_FILES['file'])."'}"); exit;      
    }
 
}
 else {
 
   echo json_encode(array(
        "state" => fail,    // saved or not saved
        "name"  => $filename,   // server-name
        "extra" => array(   // extra info, optional
                "info"  => "just a way to send some extra data",
                "param" => "some value here"
        )
    ));
  }
?>