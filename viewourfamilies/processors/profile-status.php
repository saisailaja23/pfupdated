<?php
require_once('../../inc/header.inc.php');
require_once('../../inc/profiles.inc.php');
require_once('../../inc/utils.inc.php' );
$whereCondition='';

if($_POST['filter_value']!='' && $_POST['filter_by']!=''){
	$whereCondition="AND ".$_POST['filter_by']." LIKE'".$_POST['filter_value']."%'";
}

$cSql="SELECT count(*) as count
        FROM `profile_status` AS `sp`
        LEFT JOIN `Profiles` AS tp  ON `tp`.`ID`=`sp`.`UserId` 
        WHERE tp.NickName!='' ".$whereCondition . "ORDER By sp.Time DESC ";
$cExecute = mysql_query($cSql);
$cRow = mysql_fetch_assoc($cExecute);
$totalCount=$cRow['count'];
$per_page=50;
if(isset($_POST['page']) && $_POST['page']!=''){
	$page=$_POST['page'];
	$start=($_POST['page']-1)*$per_page;
}else{
	$page=1;
	$start=0;
}
$totalRes=$totalCount/$per_page;
$total=(int)$totalRes;
if($totalRes>$total){
	$total+=$total;
}
$end=$start+$per_page-1;
$sql="SELECT
            `tp`.`ID` as `id`,
            `tp`.`NickName` AS `username`,
            `tp`.`FirstName`,
            `tp`.`LastName`,
            sp.Status,
            sp.IPaddr,

           `sp`.`Time` AS `datetime`
            
        FROM `profile_status` AS `sp`
        LEFT JOIN `Profiles` AS tp  ON `tp`.`ID`=`sp`.`UserId` 
        WHERE tp.NickName!='' ".$whereCondition . "ORDER By sp.Time DESC LIMIT ".$start.",".$per_page;
$execute = mysql_query($sql);
if(mysql_num_rows($execute) > 0){
		$html='<tbody>';
		while($row = mysql_fetch_assoc($execute)){
			$html.= "<tr><td>".$row['username']."</td><td>".$row['FirstName']."</td><td>".$row['LastName']."</td><td>".$row['Status']."</td><td>".$row['datetime']."</td><td>".$row['IPaddr']."</td></tr>";
		}
		$html.="</tbody>";
		$pagination='';
		$pages='';
		
		$first=$page-1;
		$second=$page+1;
		if($total<$second){
			$pages.="<a class='pageLink' href='javascript:void(0)'; onclick='pageHistoryView(".$first.")'>&#60</a>";
			$pages.="<a class='pageLink' href='javascript:void(0)'; >&#62</a>";
		}else if($first==0){
			$pages.="<a class='pageLink' href='javascript:void(0)';>&#60</a>";
			$pages.="<a class='pageLink' href='javascript:void(0)'; onclick='pageHistoryView(".$second.")'>&#62</a>";

		}
		else{
			$pages.="<a class='pageLink' href='javascript:void(0)'; onclick='pageHistoryView(".$first.")'>&#60</a>";
			$pages.="<a class='pageLink' href='javascript:void(0)'; onclick='pageHistoryView(".$second.")'>&#62</a>";
		}
		$i--;
		$pagination="<a class='pageLink' href='javascript:void(0)'; onclick='pageHistoryView(1)'><<</a>".
						$pages
						."<a class='pageLink' href='javascript:void(0)'; onclick='pageHistoryView(".$total.")'>>></a>
						".$totalCount." results , &nbsp;&nbsp;Page ".$page. " from ".$total;
		$result=array('html'=>stripslashes($html),'pages'=>$pagination);
		echo json_encode($result);
}
