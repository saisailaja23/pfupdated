<?php
session_start();

require_once( '../../inc/header.inc.php' );

$agencies = mysql_query("SELECT Profiles .*,bx_groups_main.title AS AgencyTitle FROM Profiles JOIN  bx_groups_main WHERE
                        bx_groups_main.author_id = Profiles.ID");

$filevalue = '"Agency name" ,"Email","Phone","Fax","City","State","Country","Zip","Status"';
$filevalue .= " \n";

 while($row = mysql_fetch_array($agencies))
 {

   $agencyname= str_replace(",",  " ", $row['AgencyTitle']); 
   $email=$row['Email'];
   $phone= format_phone($row['CONTACT_NUMBER']);
   $fax= format_phone($row['Fax_Number']);
   $city      = str_replace(",",  " ", $row['City']);
   $State     = str_replace(",",  " ", $row['State']);
   $Country   = $row['Country'];
   $zip    = $row['zip'];
   $Status    = $row['Status'];

   $filevalue .= $agencyname.",".$email.",".$phone .",". $fax. ",". $city. ",". $State. ",". $Country. ",". $zip. ",". $Status. ",\n" ;
   }

   $filename = 'Agencydetails.php';
   $date = getdate();
   $date=date("Y-m-d H.m.s",$date[0]);
   $filename = $filename.$date;


   $file =$dir['root']."administration/agencydetails/".$filename.".csv";
   $path =$dir['root']."administration/agencydetails/";
    //creating a Excel file
   $handle= fopen($file, "wb");
   chmod($file, 0777);
   if($handle)
   {
    fwrite($handle ,$filevalue);
    }
    fclose($handle);

    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'.csv"');
    readfile($file);

    session_destroy();
    
   ?>