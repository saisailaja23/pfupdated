<?php
define('BX_PROFILE_PAGE', 1);
require_once( '../../../../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolAlbums.php');

class Album extends BxDolAlbums{
	var $loggedId = 0;
	 
 	function __construct($type){
	 	$this->loggedId=getLoggedId();
	 	parent::BxDolAlbums($type,$this->loggedId);
	}
	function result($arr){
		echo json_encode($arr);
	}
	function removeObject($albumid,$objid){
		parent::removeObject($albumid,$objid);
		parent::removeObjectTotal($objid);
	}
	function create($data){
		extract($data);
		//$uri=$Caption;
                $uri = uriFilter($Caption);
		$time=time();		
		//str_replace('   ',' ',$uri);
		//str_replace('  ',' ',$uri);
		//str_replace(' ','-',$uri);
		$sql1="SELECT * FROM `sys_albums` WHERE `Caption` LIKE '%$Caption%' AND Owner=$loggedId";	
		$result = mysql_query($sql1);
		$nos=mysql_num_rows($result);
		if($nos>0){
			$this->result(array(
				"status"=>"warning",
				"msg"=>"The Album name already exits please choose other name."
				));
			return ;
		}

	        $sql="INSERT INTO `sys_albums` ( `Caption`, `Uri`, `Description`, `Type`, `Owner`, `Date`,`AllowAlbumView`) 
		VALUES ('$Caption', '$uri', '$Description' ,'$type', $this->loggedId, $time,$AllowAlbumView)";                                                                   
		
		$result = mysql_query($sql);
		$albumid=mysql_insert_id();
		if($result){
			$this->result(array(
				"albumId"=>$albumid,
				"status"=>"success",
				"msg" =>"Photo Album Created Succesfully"
			));
		}else{
			$this->result(array(
				"status"=>"error",
				"msg" =>"Internal Error"
			));
		}
	}
	function update($data){
		extract($data);
		//$uri=$value;
                $uri = uriFilter($value);
		//str_replace('   ',' ',$uri);
		//str_replace('  ',' ',$uri);
		//str_replace(' ','-',$uri);
		$sql="UPDATE sys_albums SET `Caption` = '".addslashes($value)."',`Uri`='$uri' WHERE `ID` = $id;";
		$result = mysql_query($sql);
		echo ($value);
	}
}

