<?php
/***********************************************************************
 * Name:    Sailaja S
 * Date:    2015/03/19
 * Purpose: RestAPI to get list the blogs
 ***********************************************************************/
require_once '../inc/header.inc.php';
require_once '../inc/profiles.inc.php';
require_once '../inc/utils.inc.php';
require_once '../inc/db.inc.php';

require_once 'API.php';

class Blogs extends API {
	public $check;
	public $data = array();
	
	public function __construct($request) {
		parent::__construct($request);
		if ($this->valid) {
			$this->check = 'success';
		} else {
			$this->check = 'failure';
		}
	}
	
	function get_all() {
		$where = $limit = null;
		if($_GET['id']!=0){
			$y = substr($_GET['id'], 0,4);
			$m = substr($_GET['id'], 4,2);

			$start = mktime(0, 0, 1, $m, 2, $y);
			$end   = mktime(0, 0, 1, $m, cal_days_in_month(CAL_GREGORIAN,$m,$y)+1, $y);
			$where = " and PostDate >= $start and PostDate <= $end";
			
		}	

		if($_GET['blogId']!=0){
			$where = " and PostId = ".$_GET['blogId'];
		}
		
		if($_GET['lmt']!=0){
			$limit = " LIMIT 0, ".$_GET['lmt'];
		}
		
		if($this->check == 'success'){
			$id = $this->ID;
			$columns = 'PostDate,PostText';

			$sqlBlog = "SELECT REPLACE(REPLACE(PostText,'<p>',''),'</p>','') as `PostText`,
								FROM_UNIXTIME(PostDate,'%M %d, %Y') as PostDate,
								FROM_UNIXTIME(PostDate,'%h %i %s') as Posttime,
								PostCaption, PostID FROM bx_blogs_posts WHERE OwnerID = " . $id . "
								and PostStatus = 'approval'  and allowView = '3' ".$where."
								ORDER BY FROM_UNIXTIME(PostDate,'%Y %m, %d') DESC ,
								FROM_UNIXTIME(PostDate,'%h %i %s') DESC".$limit;

			$result = mysql_query($sqlBlog);
			$nos = mysql_num_rows($result);

			if ($nos > 0) {
				while ($row = mysql_fetch_array($result, MYSQLI_ASSOC)) {

					$blogList[] = array(
						'postId' => $row['PostID'],
						'postDate' => $row['PostDate'],
						'postName' => $row['PostCaption'],
						//'postContent' => preg_replace("/<img[^>]+\>/i", " ", $row['PostText']),
						'postContent' => $_GET['blogId'] == 0 ? preg_replace("/<img[^>]+\>/i", " ", $row['PostText']) : $row['PostText'],
					);

				}
				$data['status'] = 'success';
				$data['msg'] = 'blogs fetched successfully';
				$data['data'] = $blogList;
			} else {
				$data['status'] = 'failure';
				$data['msg'] = 'no results found';
			}
		}else{
			$data['status'] = 'failure';
			$data['msg'] = 'invalid request';
		}
		echo json_encode($data);
	}
	
	function archive(){
		if($this->check == 'success'){
			$sql = "SELECT FROM_UNIXTIME( PostDate, '%M %Y' ) AS PostDate, COUNT( FROM_UNIXTIME( PostDate, '%M, %Y' )) AS cnt, FROM_UNIXTIME( PostDate, '%Y%m' ) AS arc FROM bx_blogs_posts
					WHERE OwnerID = $this->ID
					AND PostStatus = 'approval'
					AND allowView = '3'
					GROUP BY FROM_UNIXTIME( PostDate, '%M, %Y' )
					ORDER BY FROM_UNIXTIME( PostDate, '%Y %m, %d' ) DESC";
			$result = mysql_query($sql);

			if (mysql_num_rows($result) > 0) {
				$data['status'] = 'success';
				$data['msg'] = 'blogs fetched successfully';
				
				while ($row = mysql_fetch_array($result, MYSQLI_ASSOC)) {
					$data['data'][] = $row;
				}
			} else {
				$data['status'] = 'failure';
				$data['msg'] = 'no results found...';
			}			
		}else{
			$data['status'] = 'failure';
			$data['msg'] = 'invalid request';
		}
		echo json_encode($data);		
	}
}

$obj = new Blogs('https://www.parentfinder.com/');
$method = $_GET['method'];


if ($method == 'get_all' || $method == 'archive') {
	$obj->$method();
} else {
	
}
