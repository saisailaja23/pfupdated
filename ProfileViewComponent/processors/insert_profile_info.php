<?php
/**************************************************************************************************

*     Name                :  Prashanth A
*     Date                :  Mon Nov 5 2013
*     Purpose             :  Inserting values to the database  and assiging membership to users.

***************************************************************************************************/
require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');
require_once ('../../inc/utils.inc.php');
require_once ('../../inc/db.inc.php');

// Getting the post values
$Pid = getLoggedId();
$posttext = $_POST['postext'];

// Updating the values 
 $Profile_query= "INSERT INTO `bx_blogs_posts` SET PostUri = '" . $posttext . "',`PostText` = '" . $posttext . "', 
 `PostDate` = '".time()."', `PostStatus` = 'approval' , OwnerID = '" . $Pid . "'";

mysql_query($Profile_query);

$tablename = 'bx_blogs_posts';
$columns = 'PostDate,PostText';
$stringSQL_Posts = "SELECT REPLACE(REPLACE(PostText,'<p>',''),'</p>','') as `PostText`, FROM_UNIXTIME(PostDate,'%M %d, %Y') as PostDate,FROM_UNIXTIME(PostDate,'%h %i %s') as Posttime FROM " . $tablename . " where OwnerID = " . $Pid . " ORDER BY FROM_UNIXTIME(PostDate,'%Y %m, %d') DESC ,
 FROM_UNIXTIME(PostDate,'%h %i %s') DESC
  LIMIT 2 ";
$query = db_res($stringSQL_Posts);
$cmdtuples = mysql_num_rows($query);
$arrColumns = explode(",", $columns);
$arrRows_posts = array();

while (($row = mysql_fetch_array($query, MYSQL_BOTH)))
{
$arrValues = array();
foreach($arrColumns as $column_name)
{    
array_push($arrValues, $row[$column_name]);
}

array_push($arrRows_posts, array(
'id' => $row[0],
'data' => $arrValues,
));
}	
 
$update = 1;
if($update > 0)
{   
   
echo json_encode(array(
'status' => 'success',
'q'=>$Profile_query,
'blog_posts' => array(
'rows' => $arrRows_posts
)   


));
}
else
{
echo json_encode(array(
'status' => 'err', 
'response' => 'Could not read the data: ' . mssql_get_last_message()
));
}

?>