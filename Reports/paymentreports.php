<?php
session_start();
//require_once('../administration/reports.php' );

//echo "sdfsdfsdf";

//print_r($_SESSION['Params']);exit();


//$memtype = $aParams['ctl_params'][2];
//echo "asasd".$_SESSION['Params'][0].$_SESSION['Params'][1].$_SESSION['Params'][2];
//echo $_SESSION['Params'][0];
//session_destroy();

 //$new_date = date('Y-m-d', strtotime($_SESSION['Params'][0]));
 //$new_date1 = date('Y-m-d', strtotime($_SESSION['Params'][1]));
 //$memtype = $_SESSION['Params'][2];

 //if($memtype != '0') {
          //   $memtypes .= " AND `tl`.`Name` ='".$memtype."'";
          //   }

if(isset($_SESSION['Params'][0]) && isset($_SESSION['Params'][1]) ){


$start_date = $_SESSION['Params'][0];
$format_date = str_replace('-', '/', $start_date);

$end_date = $_SESSION['Params'][1];
$format_date1 = str_replace('-', '/', $end_date);


 $new_date = date('Y-m-d', strtotime($format_date));
 $new_date1 = date('Y-m-d', strtotime($format_date1));

 $sWhereClause .= " AND  DATE_FORMAT(`tp`.`DateReg`, '%Y-%m-%d')  between '".   $new_date."'   AND '". $new_date1. "'";

}

 if(isset($_SESSION['Params'][2]) && $_SESSION['Params'][2] !='0') {
 $memtype = $_SESSION['Params'][2];
             $memtypes .= " AND `tl`.`Name` ='".$memtype."'";
             }



 require_once( '../inc/header.inc.php' );
        //$dbhost = $db['host'] ;
         //   $dbuser = $db['user'] ;
         //   $dbpass = $db['passwd'];


         //   $conObject =  mysql_connect($db['host'], $db['user'], $db['passwd'],true) or die   ('Error connecting to mysql');
        //    mysql_select_db($db['db'],$conObject);

         
      //  $members = mysql_query();//."AND `ProfileType`=2 "`AdoptionAgency`=".$AdoptionAgency['id']//."ORDER BY".$order

        $sql =  "SELECT
    		`tp`.`ID` as `id`,
    		`tp`.`NickName` AS `username`,
                `tp`.`FirstName` AS `firstname`,
                `tp`.`LastName` AS `lastname`,
    		`tp`.`Headline` AS `headline`,
    		`tp`.`Sex` AS `sex`,
    		`tp`.`DateOfBirth` AS `date_of_birth`,
    		`tp`.`Country` AS `country`,
                `bx_groups_main`.`title` AS `AdoptionAgency`,
    		`tp`.`City` AS `city`,
    		`tp`.`DescriptionMe` AS `description`,
    		`tp`.`Email` AS `email`,

    		DATE_FORMAT(`tp`.`DateReg`,  '%d.%m.%Y %H:%i' ) AS `registration`,
    		DATE_FORMAT(`tp`.`DateLastLogin`,  '%d.%m.%Y %H:%i' ) AS `last_login`,
    		`tp`.`Status` AS `status`,
    		IF(`tbl`.`Time`='0' OR DATE_ADD(`tbl`.`DateTime`, INTERVAL `tbl`.`Time` HOUR)>NOW(), 1, 0) AS `banned`,
    		`tl`.`ID` AS `ml_id`,
    		IF(ISNULL(`tl`.`Name`),'', `tl`.`Name`) AS `ml_name`

    	FROM `Profiles` AS `tp`
       
        LEFT JOIN `bx_groups_main`  ON `tp`.`AdoptionAgency`=`bx_groups_main`.`id`
        LEFT JOIN `sys_admin_ban_list` AS `tbl` ON `tp`.`ID`=`tbl`.`ProfID`
        LEFT JOIN `sys_acl_levels_members` AS `tlm` ON `tp`.`ID`=`tlm`.`IDMember` AND `tlm`.`DateStarts` < NOW() AND (`tlm`.`DateExpires`>NOW() || ISNULL(`tlm`.`DateExpires`))
        LEFT JOIN `sys_acl_levels` AS `tl` ON `tlm`.`IDLevel`=`tl`.`ID`

    	WHERE
    		1 AND (`tp`.`Couple`=0 OR `tp`.`Couple`>`tp`.`ID`)" .$memtypes.$sWhereClause."

        ORDER BY `tp`.`DateReg` DESC";
       
     $members = mysql_query($sql);




            $filevalue = '"NickName" ,"First Name","Last Name","Agency","Registration date","Email",Membership name","Status"';
            $filevalue .= " \n";

                while($row = mysql_fetch_array($members))//sys_acl_levels
                {   

                     
                   $Agencyname = str_replace(",",  "&# 44 ;", $row["AdoptionAgency"]); 
                   $Usrname = str_replace(",",  "&# 44 ;", $row["username"]);
                   $Fname = str_replace(",",  "&# 44 ;", $row["firstname"]);
                   $Lname = str_replace(",",  "&# 44 ;", $row["lastname"]);

                   $Registration = date("m.d.Y  H:i:s", strtotime($row["registration"]));
                    
                    //if(!empty($row["ml_name"])) ? $row["ml_name"] : 'Standard'

                    if($row["ml_name"] != '') {
                    $membership = $row["ml_name"];
                    }
                    else  { 
                    $membership = 'Basic';
                        }
 
                  //if($aProfile['ml_name'] != '') ? $aProfile['ml_name'] : 'Standard'

                  //  $insideScript=1;
                    //  $profiletype = mysql_fetch_assoc(mysql_query("SELECT `Name` FROM `aqb_pts_profile_types` where `ID`=".$row["ProfileType"]));
                   //   $membership =  mysql_fetch_assoc(mysql_query("SELECT `Name` FROM `sys_acl_levels` AS sn INNER JOIN `sys_acl_levels_members` AS sid ON  sn.`ID`= sid .`IDLevel` WHERE sid .`IDMember`=".$row["ID"]));
                  //  if(!$membership["Name"]){
                   //    $membership["Name"] = 'Standard';
                  //  }
                      $filevalue .= $Usrname.",".$Fname.",".$Lname .",". $Agencyname .",".$Registration . ",".$row["email"].",".$membership.",".$row["status"].",\n" ;
                  //$filevalue .= $row["username"].",".$row["firstname"].",".$row["lastname"] .",". $newtext."\n". ",".$Registration . ",".$row["email"].",".$membership.",".$row["status"].",\n" ;

                }



            $filename = 'user_report';
            $date = getdate();
            $date=date("Y-m-d H.m.s",$date[0]);
          $filename = $filename.$date;


        $file =$dir['root']."Reports/".$filename.".csv";
        $path =$dir['root']."Reports/";
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

unset($_SESSION['Params']);
unlink($file);
      //  session_destroy();

   ?>