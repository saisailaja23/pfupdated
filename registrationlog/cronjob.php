<?php
   include( '/var/www/html/pf/inc/header.inc.php' );
      echo   $dbhost = $db['host'] ;
            $dbuser = $db['user'] ;
            $dbpass = $db['passwd'];
            $filename = 'profiles'; 
         
           $conObject =  mysql_connect($db['host'], $db['user'], $db['passwd'],true) or die   ('Error connecting to mysql');
            mysql_select_db($db['db'],$conObject);
            $sql = "SELECT g.title, p.Sex , p.FirstName , p.LastName , p.Email ,
               p.CONTACT_NUMBER , 'AP' as MemberType, if( sal.Name IS NULL ,
               concat( 'AP', ' - Standard' ) ,replace(sal.Name,'Adoptive Family','AP')  ) AS MembershipLevel FROM Profiles
               p LEFT JOIN sys_acl_levels_members salm ON ( p.ID = salm.IDMember ) LEFT
               JOIN sys_acl_levels sal ON ( sal.ID = salm.IDLevel ) , bx_groups_main g,
               aqb_pts_profile_types t WHERE p.AdoptionAgency = g.id AND p.status IN
               ('Active', 'Approval')AND p.ProfileType = t.id AND p.ProfileType IN ( 2, 4 )
                AND t.Name ='Adoptive Family' and p.Email not like '%(2)' 
                AND datediff( curdate( ) , DateReg ) =0";
            $qbResult = mysql_query($sql,$conObject);
            
            $filevalue = '"Agency type","Salutation","Client first name","Client last name","Email for client name","Phone","Title","PF membership level"';
            $filevalue .= " \n";
            
           
                while($row = mysql_fetch_array($qbResult))
                {     $insideScript=1;
                      $filevalue .= $row["title"].",".$row["Sex"].",".$row["FirstName"] .",". $row["LastName"]. ",".$row["Email"] . ",".$row["CONTACT_NUMBER"]. ",".$row["MemberType"] . ",".$row["MembershipLevel"] .", \n" ;
                }              
           
	
        $filename = $filename."_".date("Y-m-d",time());
        $file =$dir['root']."registrationlog/".$filename.".csv";
        $path =$dir['root']."registrationlog/";
    //creating a xcel file
        $handle= fopen($file, "w");//echo $handle;exit;
        if($handle)          
        {
            fwrite($handle ,$filevalue);
        }
        fclose($handle);


       function mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message) {
     $file = $path.$filename;
    $file_size = filesize($file);
    $handle = fopen($file, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode($content));
    $uid = md5(uniqid(time()));
    $name = basename($file);
    $header = "From: ".$from_name." <".$from_mail.">\r\n";
    $header .= "Reply-To: ".$replyto."\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $header .= $message."\r\n\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use diff. tyoes here
    $header .= "Content-Transfer-Encoding: base64\r\n";
    $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
    $header .= $content."\r\n\r\n";
    $header .= "--".$uid."--";
    if (mail($mailto, $subject, "", $header)) {
        echo  "mail send ... OK"; // or use booleans here
    } else {
        echo  "mail send ... ERROR!";
    }
}

 $my_file = $filename.".csv";
$my_path = $path;
$my_name = "Parentfinder";
$to_mail = "info@parentfinder.com";//info@parentfinder.com
$my_replyto = "info@parentfinder.com";
$my_subject = "User Details from Parentfinder site - Scheduler";
$my_message = "Hello,\r\n Please find the attached user details from parentfinder.com site in order to load the
    data in Zoho CRM.\r\n\r\n . Scheduler from Parentfinder.com site";
//if($insideScript ==1)
//{  
    mail_attachment($my_file, $my_path, $to_mail, $to_mail, $my_name, $my_replyto, $my_subject, $my_message);
//}
?>