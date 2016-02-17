<?php
/***********************************************************************
 * Name:    Sailaja S
 * Date:    2015/03/19
 * Purpose: RestAPI to get Agency Details of a user
 ***********************************************************************/
require_once '../inc/header.inc.php';
require_once '../inc/profiles.inc.php';
require_once '../inc/utils.inc.php';
require_once '../inc/db.inc.php';

require_once 'API.php';

class Contact extends API {
	public function __construct($request) {
		parent::__construct($request);
	}
	function getAgencyInfo() {
		$id = $this->ID;
		$data = array();

		$banner = array();
		$arr = db_arr("SELECT `id` FROM `coverphotos` WHERE `ownerId`=$this->ID");
		if(@$arr['id']){
			foreach(glob('../tmp/coverphotos/*.*') as $fil){
				$fnam =  array_shift(explode('.', basename($fil)));
				if($fnam == $arr['id']){
					$cover = $_SERVER['HTTP_HOST'].'/tmp/coverphotos/'.basename($fil);
				}
			}
		
			$banner['status'] = 'success';
			$banner['msg'] = 'banner image fetched successfully';
			$banner['data'] = $cover;
		}else{
			$banner['status'] = 'failure';
			$banner['msg'] = 'no results found';		
		}
		
		$data['banner'] = $banner;



		$sql1 = "SELECT `Facebook`,`Twitter`,`Google`,`Blogger`,`Pinerest`
					   		FROM Profiles_draft
					   		WHERE  ID=" . $id;
		$socialInfo = mysql_query($sql1);
		$nos = mysql_num_rows($socialInfo);
		if ($nos > 0) {
			while ($row = mysql_fetch_array($socialInfo, MYSQLI_ASSOC)) {
				$data['socialDetails'] = array(
					'status' => 'success',
					'msg' => 'success',
					'data' => array(
						'Facebook' => $row['Facebook'],
						'Twitter' => $row['Twitter'],
						'Google' => $row['Google'],
						'Blogger' => $row['Blogger'],
						'Pinerest' => $row['Pinerest'],
					),
				);
			}
		} else {
			$data['socialDetails'] = array(
				'status' => 'failure',
				'msg' => 'Cant found',
			);
		}
		/*
		$sql2 = "SELECT title AS AgencyTitle, author_id AS bxid, Profiles.WEB_URL, Profiles.phonenumber as CONTACT_NUMBER, Profiles.Street_Address, Profiles.Email, Profiles.Avatar
				FROM bx_groups_main 
				JOIN Profiles ON bx_groups_main.id = Profiles.AdoptionAgency
				WHERE Profiles.ID = (SELECT IF(`show_contact` = 1,$id, 
											(SELECT bx_groups_main.author_id 
											FROM Profiles JOIN bx_groups_main WHERE Profiles.ID = $id 
											AND Profiles.AdoptionAgency = bx_groups_main.id)
								) AS res FROM `Profiles` WHERE `ID` = $id)";
		/**/
		$sql = db_arr("SELECT show_contact FROM Profiles where Profiles.ID = $id");
		$sc = $sql['show_contact'];

		$sql2 = "SELECT title AS AgencyTitle, author_id AS bxid, Profiles.WEB_URL, 
				IF($sc=1,
						(SELECT phonenumber FROM Profiles where Profiles.ID = $id), 
						(SELECT CONTACT_NUMBER FROM Profiles where Profiles.ID = 
							(SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID = $id AND Profiles.AdoptionAgency = bx_groups_main.id)))
				AS phonenumber, Profiles.Street_Address, 
				IF($sc=1,
						(SELECT Email FROM Profiles where Profiles.ID = $id), 
						(SELECT Email FROM Profiles where Profiles.ID = 
							(SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID = $id AND Profiles.AdoptionAgency = bx_groups_main.id)))
				AS Email, Profiles.Avatar
				FROM bx_groups_main 
				JOIN Profiles ON bx_groups_main.id = Profiles.AdoptionAgency
				WHERE Profiles.ID = (SELECT bx_groups_main.author_id 
							FROM Profiles JOIN bx_groups_main WHERE Profiles.ID = $id 
							AND Profiles.AdoptionAgency = bx_groups_main.id)";

		$agencyInfo = mysql_query($sql2);
		$nos = mysql_num_rows($agencyInfo);
		if ($nos > 0) {
			while ($row = mysql_fetch_array($agencyInfo, MYSQLI_ASSOC)) {

				$data['agencyDetails'] = array(
					'status' => 'success',
					'msg' => 'success',
					'data' => array(
						'agencyTitle' => $row['AgencyTitle'],
						'agencyId' => $row['bxid'],
						'link' => $row['WEB_URL'],
						'contactNumber' => $row['phonenumber'],
						'address' => $row['Street_Address'],
						'email' => $row['Email'],
						'agencyLogo' => "http://www.parentfinder.com/modules/boonex/avatar/data/images/" . $row['Avatar'] . ".jpg",
					),
				);
                            if ($id == 7310) {
                                $data['agencyDetails']['data']['contactNumber'] = '646-650-8634';
                                $data['agencyDetails']['data']['address'] = "501 east 79th street,4E,New York-10075";
                                $data['agencyDetails']['data']['email'] = "micheleandstevenadopt@gmail.com";
                                $data['agencyDetails']['data']['agencyLogo'] = "http://www.parentfinder.com/modules/boonex/avatar/data/favourite/3072.jpg";
                            }

			}
		} else {
			$data['agencyDetails'] = array(
				'status' => 'failure',
				'msg' => 'Cant found',
			);
		}

		$nos = 0;
		$videoSql = "SELECT * FROM `RayVideoFiles` WHERE `home` = 1 AND `Owner` = $id";
		$result = mysql_query($videoSql);
		$nos = mysql_num_rows($result);

		if($nos == 0){
			$sql = db_arr("SELECT ID FROM sys_albums where Owner = '" . $id . "' and (Caption = 'Home Videos' OR Caption = 'Home videos' OR Caption = 'home Videos' OR Caption = 'home videos')");
			$albumId = $sql['ID'];

			$videoSql = "SELECT ph.ID,ph.Title,ph.Views,ph.Source, ph.YoutubeLink, ph.Uri FROM RayVideoFiles as ph,sys_albums_objects sab
				WHERE ph.ID=sab.id_object
				AND sab.id_album=$albumId
		                      ORDER BY sab.obj_order
		                      LIMIT 1";
			$result = mysql_query($videoSql);
			$nos = mysql_num_rows($result);
		}
		if ($nos > 0) {
			while ($row = mysql_fetch_array($result, MYSQLI_ASSOC)) {
				$data['homeVideo'] = array(
					'status' => 'success',
					'msg' => 'success',
					'data' => array(
						'albumId' => $row['ID'],
						'video' => $row['Hash'],
						'videoName' => $row['Title'],
						'ext' => $row['Ext'],
						'youtubeLink' => $row['YoutubeLink'],
						'youtubeUri' => $row['Uri'],
					),
				);
			}
		} else {
			$data['homeVideo'] = array(
				'status' => 'failure',
				'msg' => 'Cant found',
			);
		}

		$sqlBlog = "SELECT REPLACE(REPLACE(PostText,'<p>',''),'</p>','') as `PostText`,
								FROM_UNIXTIME(PostDate,'%M %d, %Y') as PostDate,
								FROM_UNIXTIME(PostDate,'%h %i %s') as Posttime,
								PostCaption, PostID FROM bx_blogs_posts WHERE OwnerID = " . $id . "
								and PostStatus = 'approval'  and allowView = '3' " . $where . "
								ORDER BY FROM_UNIXTIME(PostDate,'%Y %m, %d') DESC ,
								FROM_UNIXTIME(PostDate,'%h %i %s') DESC";

		$result = mysql_query($sqlBlog);
		$nos = mysql_num_rows($result);

		if ($nos > 0) {
			while ($row = mysql_fetch_array($result, MYSQLI_ASSOC)) {

				$blogData[] = array(
					'postId' => $row['PostID'],
					'postDate' => $row['PostDate'],
					'postName' => $row['PostCaption'],
					//'postContent' => preg_replace("/<img[^>]+\>/i", " ", $row['PostText']),
					'postContent' => $_GET['blogId'] == 0 ? preg_replace("/<img[^>]+\>/i", " ", $row['PostText']) : $row['PostText'],
				);

			}
			$blogList['status'] = 'success';
			$blogList['msg'] = 'blogs fetched successfully';
			$blogList['data'] = $blogData;
			$data['blogList'] = $blogList;
		} else {
			$blogList['status'] = 'failure';
			$blogList['msg'] = 'no results found';
			$data['blogList'] = $blogList;
		}
		$data['blogList'] = $blogList;
		

		
		//print_r($data); exit;
		echo json_encode($data);
	}

}

$obj = new Contact('http://www.parentfinder.com/');
$obj->getAgencyInfo();
