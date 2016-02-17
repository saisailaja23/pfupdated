<?php
session_start();
//require_once('../administration/reports.php' );

//echo "sdfsdfsdf";

//print_r($_SESSION['Params']);


//$memtype = $aParams['ctl_params'][2];
//echo "asasd".$_SESSION['Paramspay'][0].$_SESSION['Paramspay'][1].$_SESSION['Paramspay'][2];
//echo $_SESSION['Params'][0];
//session_destroy();

if(isset($_SESSION['Paramspay'][0]) && isset($_SESSION['Paramspay'][1]) ){


$start_date = $_SESSION['Paramspay'][0];
$format_date = str_replace('-', '/', $start_date);

$end_date = $_SESSION['Paramspay'][1];
$format_date1 = str_replace('-', '/', $end_date);


 $new_date = date('Y-m-d', strtotime($format_date));
 $new_date1 = date('Y-m-d', strtotime($format_date1));




 $sWhereClause .= " AND  DATE_FORMAT(FROM_UNIXTIME(`vt`.`date`), '%Y-%m-%d')  between '".   $new_date."'   AND '". $new_date1. "'";


}

 if(isset($_SESSION['Paramspay'][2]) && $_SESSION['Paramspay'][2] != '0') {
 $memtype = $_SESSION['Paramspay'][2];
             $memtypes .= " AND `tl`.`Name` ='".$memtype."'";
             }

 require_once( '../inc/header.inc.php' );
        //$dbhost = $db['host'] ;
         //   $dbuser = $db['user'] ;
         //   $dbpass = $db['passwd'];


         //   $conObject =  mysql_connect($db['host'], $db['user'], $db['passwd'],true) or die   ('Error connecting to mysql');
        //    mysql_select_db($db['db'],$conObject);


      //  $members = mysql_query();//."AND `ProfileType`=2 "`AdoptionAgency`=".$AdoptionAgency['id']//."ORDER BY".$order

  
  $sql = "SELECT
    		`tp`.`ID` as `id`,
    		`tp`.`NickName` AS `username`,
                `tp`.`FirstName` AS `firstname`,
                `tp`.`LastName` AS `lastname`,
                `bx_groups_main`.`title` AS `AdoptionAgency`,
    		`tp`.`Email` AS `email`,
                 `vt`.`client_id` AS `clientid`,
                `vt`.`amount` AS `amount`,
                DATE_FORMAT(FROM_UNIXTIME(`vt`.`date`), '%d-%m-%Y') as date,
                `tlm`.`TransactionID`,

                DATE_FORMAT(`tp`.`DateReg`,  '%d.%m.%Y %H:%i' ) AS `registration`,

    		`tl`.`ID` AS `ml_id`,
    		IF(ISNULL(`tl`.`Name`),'', `tl`.`Name`) AS `ml_name`


FROM `Profiles` AS `tp`,`bx_groups_main`,`sys_acl_levels_members` AS `tlm`,`sys_acl_levels` AS `tl`,`bx_pmt_transactions` AS `vt`

       WHERE `tp`.`AdoptionAgency`=`bx_groups_main`.`id`  And `tp`.`ID`=`tlm`.`IDMember` And `tlm`.`IDLevel`=`tl`.`ID`
    	AND
    		1 AND (`tp`.`Couple`=0 OR `tp`.`Couple`>`tp`.`ID`) AND `vt`.client_id = `tlm`.`IDMember`   AND `vt`.`amount` > 0 AND `vt`.order_id = `tlm`.`TransactionID` " .$memtypes. $sWhereClause. "

        ORDER BY `tp`.`ID` ASC"; 








     $members = mysql_query($sql);

            $filevalue = '"NickName" ,"First Name","Last Name","Agency","client_id","Email","Amount","Payment date","Membership name"';
            $filevalue .= " \n";

                while($row = mysql_fetch_array($members))//sys_acl_levels
                {   //  $insideScript=1;
                    //  $profiletype = mysql_fetch_assoc(mysql_query("SELECT `Name` FROM `aqb_pts_profile_types` where `ID`=".$row["ProfileType"]));
                   //   $membership =  mysql_fetch_assoc(mysql_query("SELECT `Name` FROM `sys_acl_levels` AS sn INNER JOIN `sys_acl_levels_members` AS sid ON  sn.`ID`= sid .`IDLevel` WHERE sid .`IDMember`=".$row["ID"]));
                  //  if(!$membership["Name"]){
                   //    $membership["Name"] = 'Standard';
                  //  }


                   $Agencyname = str_replace(",",  "&# 44 ;", $row["AdoptionAgency"]); 
                   $Usrname = str_replace(",",  "&# 44 ;", $row["username"]);
                   $Fname = str_replace(",",  "&# 44 ;", $row["firstname"]);
                   $Lname = str_replace(",",  "&# 44 ;", $row["lastname"]);



                      $filevalue .= $row["username"].",".$row["firstname"].",".$row["lastname"] .",". $row["AdoptionAgency"]. ",".$row["clientid"] . ",".$row["email"].",".$row["amount"].",".$row["date"].",".$row["ml_name"].",\n" ;
                       // $filevalue .= $row["username"].",".$row["firstname"].",".$row["lastname"] .",". $Adagen."\n". ",".$row["clientid"] . ",".$row["email"].",".$row["amount"].",".$row["date"].",".$row["ml_name"].",\n" ;

                }



            $filename = 'Payment_report';
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


      unset($_SESSION['Paramspay']);
       unlink($file);
   ?>