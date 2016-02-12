<?php
session_start();
   include( '../inc/header.inc.php' );
        $dbhost = $db['host'] ;
            $dbuser = $db['user'] ;
            $dbpass = $db['passwd'];
            
         
            $conObject =  mysql_connect($db['host'], $db['user'], $db['passwd'],true) or die   ('Error connecting to mysql');
            mysql_select_db($db['db'],$conObject);
            
       
        $AdoptionAgency= mysql_fetch_assoc(mysql_query("SELECT `id`,`title` FROM `bx_groups_main` where `author_id`=".$_COOKIE['memberID']));            
        $order =  $_SESSION['$order'];

        if($order)
        {
          $orderby = " ORDER BY ".$order;  
        }
     
        $members = mysql_query("SELECT * FROM `Profiles` WHERE  `ProfileType`=2 AND `AdoptionAgency`=".$AdoptionAgency['id'].$orderby);//."AND `ProfileType`=2 "`AdoptionAgency`=".$AdoptionAgency['id']//."ORDER BY".$order
            $filevalue = '"UserName" ,"Email","Profile Type","Membership","Registered","Last Visited"';
            $filevalue .= " \n";
         
                while($row = mysql_fetch_array($members))//sys_acl_levels
                {     $insideScript=1;
                      $profiletype = mysql_fetch_assoc(mysql_query("SELECT `Name` FROM `aqb_pts_profile_types` where `ID`=".$row["ProfileType"]));
                      $membership =  mysql_fetch_assoc(mysql_query("SELECT `Name` FROM `sys_acl_levels` AS sn INNER JOIN `sys_acl_levels_members` AS sid ON  sn.`ID`= sid .`IDLevel` WHERE sid .`IDMember`=".$row["ID"]));
                    if(!$membership["Name"]){                        
                       $membership["Name"] = 'Standard'; 
                    }
                      $filevalue .= $row["NickName"].",".$row["Email"].",".$profiletype["Name"] .",". $membership["Name"]. ",".$row["DateReg"] . ",".$row["DateLastNav"].", \n" ;
                }      
        
 

            $filename = $AdoptionAgency['title'].'{membersdetails}';     
            $date = getdate();    
            $date=date("Y-m-d H.m.s",$date[0]);
          $filename = $filename.$date;
               

        $file =$dir['root']."Agencymemberlist/".$filename.".csv";
        $path =$dir['root']."Agencymemberlist/";
    //creating a xcel file
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