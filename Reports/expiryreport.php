<?php
session_start();


if(isset($_SESSION['Paramsexpiry'][0]) && isset($_SESSION['Paramsexpiry'][1]) ){


$start_date = $_SESSION['Paramsexpiry'][0];
$format_date = str_replace('-', '/', $start_date);

$end_date = $_SESSION['Paramsexpiry'][1];
$format_date1 = str_replace('-', '/', $end_date);


 $new_date = date('Y-m-d', strtotime($format_date));
 $new_date1 = date('Y-m-d', strtotime($format_date1));







 $sWhereClause .= " AND  DATE_FORMAT(`tlm`.`DateExpires`, '%Y-%m-%d')  between '".   $new_date."'   AND '". $new_date1. "'";


}

 if(isset($_SESSION['Paramsexpiry'][2]) && $_SESSION['Paramsexpiry'][2] !='0') {
 $memtype = $_SESSION['Paramsexpiry'][2];
             $memtypes .= " AND `tl`.`Name` ='".$memtype."'";
             }


 require_once( '../inc/header.inc.php' );


    $sql =  "SELECT
    		`tp`.`ID` as `id`,
    		`tp`.`NickName` AS `username`,
                `tp`.`FirstName` AS `firstname`,
                `tp`.`LastName` AS `lastname`,
                `bx_groups_main`.`title` AS `AdoptionAgency`,
    		`tp`.`Email` AS `email`,                
                `tlm`.`TransactionID`,

                DATE_FORMAT(`tp`.`DateReg`,  '%d.%m.%Y %H:%i' ) AS `registration`,

    		`tl`.`ID` AS `ml_id`,
    		IF(ISNULL(`tl`.`Name`),'', `tl`.`Name`) AS `ml_name`,
                `tlm`.`DateExpires` AS `Expiry date`,
                 `tp`.`DateLastLogin` AS `Last login date`

FROM `Profiles` AS `tp`,`bx_groups_main`,`sys_acl_levels_members` AS `tlm`,`sys_acl_levels` AS `tl`

       WHERE `tp`.`AdoptionAgency`=`bx_groups_main`.`id`  And `tp`.`ID`=`tlm`.`IDMember` And `tlm`.`IDLevel`=`tl`.`ID`
    	AND
    		1 AND (`tp`.`Couple`=0 OR `tp`.`Couple`>`tp`.`ID`) AND `tlm`.`DateExpires` != 'NULL'" .$memtypes. $sWhereClause. "


        ORDER BY `tp`.`ID` ASC";
       
     $members = mysql_query($sql);

            $filevalue = '"NickName" ,"First Name","Last Name","Agency","Registration date","Email","Expiry date","Last Login",Membership name",';
            $filevalue .= " \n";

                while($row = mysql_fetch_array($members))//sys_acl_levels
               {   
                     

              $Agencyname = str_replace(",",  "&# 44 ;", $row["AdoptionAgency"]); 
              $Usrname = str_replace(",",  "&# 44 ;", $row["username"]);
              $Fname = str_replace(",",  "&# 44 ;", $row["firstname"]);
              $Lname = str_replace(",",  "&# 44 ;", $row["lastname"]);

              $filevalue .= $Usrname.",".$Fname.",".$Lname .",". $Agencyname. ",".$row["registration"] . ",".$row["email"].",".$row["Expiry date"].",".$row["Last login date"].",".$row["ml_name"].",\n" ;
                
                }



            $filename = 'Membership_expiry_report';
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

unset($_SESSION['Paramsexpiry']);
  unlink($file);       

   ?>