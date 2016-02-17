<?php
require_once('../../inc/header.inc.php');
require_once('../../inc/profiles.inc.php');
require_once('../../inc/utils.inc.php' );

//$user_id = getLoggedId();
$user_id = $_GET['id']; //echo $user_id; exit;
if($user_id){
	$id = $user_id;
	
	$sql = "SELECT 
			(select Avatar from Profiles where id = $id) as Avatar,
			p1.NickName, p1.Email,p1.FamilyStructure, p1.FirstName as p1_FirstName, p1.faith as p1_faith, p1.Ethnicity as p1_Ethnicity, p1.Education as p1_Education,
			FLOOR( DATEDIFF( CURDATE( ) ,  p1.DateOfBirth ) /365 ) as p1_DateOfBirth,
			p1.Sex as p1_Sex, p1.waiting as p1_waiting, p1.RelationshipStatus as p1_RelationshipStatus, 
			(IF(p1.region='Non US', (SELECT Country FROM `sys_countries` WHERE ISO2 = p1.country), p1.state)) as p1_loc,
			p1.state as p1_State,
			p2.FirstName as p2_FirstName, p2.faith as p2_faith, p2.Ethnicity as p2_Ethnicity, p2.Education as p2_Education,
			FLOOR( DATEDIFF( CURDATE( ) ,  p2.DateOfBirth ) /365 ) as p2_DateOfBirth,
			p2.Sex as p2_Sex, p2.waiting as p2_waiting, p2.RelationshipStatus as p2_RelationshipStatus, 
			(IF(p2.region='Non US', (SELECT Country FROM `sys_countries` WHERE ISO2 = p2.country), p2.state)) as p2_loc,
			p2.state as p2_State,
			p1.ChildAge, p1.ChildGender, p1.ChildEthnicity, p1.AdoptionType,
			p1.letter_aboutThem, p1.DescriptionMe, p1.img_them, p1.show_contact, p1.phonenumber,
(SELECT CONTACT_NUMBER
FROM `Profiles` as pr
LEFT JOIN `bx_groups_main` AS g ON pr.AdoptionAgency = g.id
WHERE pr.`ID`IN (SELECT bx_groups_main.author_id FROM Profiles 
JOIN bx_groups_main WHERE Profiles.ID = $id AND Profiles.AdoptionAgency=bx_groups_main.id)) as agency_contact
			FROM Profiles_draft as p1
			LEFT JOIN Profiles_draft p2 ON p1.ID = p2.couple
			WHERE p1.id = $id";
	
	//echo '<pre>'; echo $sql; exit;
	
	$execute = mysql_query($sql);
	if(mysql_num_rows($execute) > 0){
		$res['status'] = 'success';
		
		//echo '<pre>';
		$row = mysql_fetch_assoc($execute);
		
		
		$row['Avatar'] = 'modules/boonex/avatar/data/favourite/'.$row['Avatar'].'.jpg';
		$row['p1_faith'] = str_replace(',',', ', $row['p1_faith']);
		$row['p2_faith'] = str_replace(',',', ', $row['p2_faith']);
		$row['phonenumber'] = format_phone($row['phonenumber']);
		$row['agency_contact'] = format_phone($row['agency_contact']);
		
		/*
		$img_them = implode(',', json_decode($row['img_them']));
		$q = mysql_query("SELECT CONCAT(Hash,'.', Ext) as img FROM `bx_photos_main` WHERE ID IN($img_them);");
		*/
		$q =  	mysql_query("SELECT CONCAT(bpm.Hash,'.',bpm.Ext) AS img  
							FROM `sys_albums` sa
							LEFT JOIN sys_albums_objects sao ON sa.ID = sao.id_album
							LEFT JOIN bx_photos_main bpm ON sao.id_object = bpm.ID
							WHERE sa.`Caption` LIKE 'Profile Pictures' 
							AND sa.`Owner` = $user_id
							LIMIT 0,5");
		
		
		
		if(mysql_num_rows($q) > 0){
			while($img = mysql_fetch_assoc($q)){
				if($img['img']!=null){
					$ltr_img['status'] = 'success';
					$ltr_img['data'][] = "/m/photos/get_image/file/".$img['img'];
				}else{ $ltr_img['status'] = 'failure'; }
			}
		}else{
			$ltr_img['status'] = 'failure';
		}
		
		$row['img_them'] = $ltr_img;
		
		//album images
		$execute = mysql_query("SELECT CONCAT(bpm.Hash,'.',bpm.Ext) as img
								FROM `sys_albums` sa
								LEFT JOIN sys_albums_objects sao ON sa.ID = sao.id_album
								LEFT JOIN bx_photos_main bpm ON sao.id_object = bpm.ID
								WHERE Type = 'bx_photos' and sao.id_object != 0 and sa.`Owner` = $id
								LIMIT 0, 10");
		
		if(mysql_num_rows($execute) > 0){
			$alb_imgs['status'] = 'success';
			while($roww = mysql_fetch_assoc($execute)){
				if($roww['img']!=null){
					$ltr_img['status'] = 'success';
					$alb_imgs['data'][] = "m/photos/get_image/file/".$roww['img'];
				}else{ $ltr_img['status'] = 'failure'; }
			}
		}else{
			$alb_imgs['status'] = 'failure';
		}
		
		$row['alb_imgs'] = $alb_imgs;

		/**/
		$siteMap = file_get_contents('../../sitemaping.json');
		$siteMap = (array)json_decode($siteMap);
		
		//echo '<pre>'; print_r($siteMap);
		
		$site = array_search($row['NickName'], $siteMap);
		
		$row['portal_link'] = $site;
		/**/

		
		$res['data'] = $row;
	}else{
		$res['status'] = 'failure';
	}
	
	
	
	
	//echo '<pre>'; print_r($res); exit;
	echo json_encode($res);
}
?>