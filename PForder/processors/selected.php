<?php
/***********************************************************************
 * Name:    Eswar N
 * Date:    17/07/2014
 * Purpose: Creating a order list
 ***********************************************************************/
require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');

header ("content-type: application/json");
$logid = getLoggedId();
$task = $_GET['task'];


if($task == 'list'){

	$Cartdetails = "SELECT * FROM  `cart_list_items` AS cit, cart_list AS cl WHERE cit.item_userid =$logid AND cit.item_cartid = cl.cart_id order by cl.cart_id asc";
	$Cartdetailsquery= mysql_query($Cartdetails);

	$res=array();
	//
	while (($row_Cart = mysql_fetch_array($Cartdetailsquery, MYSQL_ASSOC))){
		array_push($res, $row_Cart);
	// $xml .= '<item id="'.$row_Cart['cart_id'].'"><name>'.$row_Cart['itemname'].'</name><desc>'.$row_Cart['description'].'</desc><quantity>'.$row_Cart['item_quantity'].'</quantity><price>'.$row_Cart['price'].'</price></item>';
	}
	// $xml .='</data>';
	// echo $xml; 

	echo json_encode($res);
}




if($task == 'insert'){
	$id  = $_GET['id'];
	if($_GET['new'] == 'yes' ){
	echo	$sql_insert  = "INSERT INTO `cart_list_items` ( `item_cartid`, `item_quantity`, `item_userid`) VALUES( $id, 1, $logid) ";
		mysql_query($sql_insert);
	}else{
		$Cartdetails = "SELECT * FROM  `cart_list_items` where item_cartid = $id";
		$Cartdetailsquery= mysql_query($Cartdetails);
		while (($row_Cart = mysql_fetch_array($Cartdetailsquery, MYSQL_BOTH))){
			$quantity = $row_Cart['item_quantity']+1;
		}
		$updatequery  = "UPDATE  cart_list_items SET  item_quantity =  $quantity where item_cartid = $id and item_userid = $logid";
		mysql_query($updatequery);
	}
}
if($task == 'delete'){
	$id  = $_GET['id'];
	if($_GET['complete'] == 'yes' ){
		$sql_delet  = "Delete  FROM  cart_list_items where item_cartid =  $id";
		mysql_query($sql_delet);
	}else{
		$Cartdetails = "SELECT * FROM  `cart_list_items` where item_cartid = $id";
		$Cartdetailsquery= mysql_query($Cartdetails);
		while (($row_Cart = mysql_fetch_array($Cartdetailsquery, MYSQL_BOTH))){
			$quantity = $row_Cart['item_quantity']-1;
		}
		$updatequery  = "UPDATE  cart_list_items SET  item_quantity =  $quantity where item_cartid = $id and item_userid = $logid";
		mysql_query($updatequery);
	}
}
if($task == 'clear'){
		$sql_delet  = "Delete  FROM  cart_list_items where item_userid =  $logid";
		mysql_query($sql_delet);
}
?>