<?php
require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');
require_once ('../../inc/utils.inc.php');

$Profile_id = $_POST['ProfileId'];
 $html = '<div class="table_div"><table width="100%" class="members-history" cellpadding="0" cellspacing="0">
                        <thead id="statusList">
                        <tr class="table_head_tr">
                            <th>User Name</th>
                            <th>Status</th>
                            <th>Date of Change</th>
                             <th>IP Address</th>                      
                        </tr>
                         </thead>';
        $html.='<tbody>';
if(!empty($Profile_id)) {

 $sql="SELECT
            `tp`.`ID` as `id`,
            `tp`.`NickName` AS `username`,
            `tp`.`FirstName`,
            `tp`.`LastName`,
            sp.Status,
            sp.IPaddr,
            sp.AgencyId,
           date_format(`sp`.`Time`,'%m/%d/%Y %H:%i:%s') AS `datetime`
            
        FROM `profile_status` AS `sp`
        LEFT JOIN `Profiles` AS tp  ON `tp`.`ID`=`sp`.`UserId` 
        WHERE sp.AgencyId!=0 and tp.ID = '".$Profile_id."' ORDER By sp.Time DESC";
$execute = mysql_query($sql);
$update = mysql_num_rows($execute);
if(mysql_num_rows($execute) > 0){
	
		while($row = mysql_fetch_assoc($execute)){
            $agencyQuery=mysql_query("SELECT `NickName` from  Profiles where ID=".$row['AgencyId']);
            if($agencyNameArray=mysql_fetch_array($agencyQuery)){
                $agencyName=$agencyNameArray['NickName'];
            }else{
                $agencyName='';
            }            
            $newdate =  date('m/d/y h:i:A',strtotime($row['datetime']));
			$html.= "<tr class='table_sec_tr'><td>".$agencyName."</td><td>".ucfirst($row['Status'])."</td><td>".$newdate."</td>
			<td><a href='http://ipinfo.io/".$row['IPaddr']. "' class='ip_new' target='new'>".$row['IPaddr']."</a></td></tr>";
		}
}
else{
   $html.=  "<tr></tr><tr><td colspan ='4'  style='text-align:center;'><strong>No Rows Found</strong></td></tr>"; 
}
}
else{
   $html.= "<tr></tr><tr><td colspan ='4'  style='text-align:center;'><strong>No Rows Found</strong></td></tr>"; 
}

$html.="</tbody></table></div>";

echo json_encode(array(
'status' => 'success',
'sql_statement' => $html
));


?>