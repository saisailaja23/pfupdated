<?php
/***********************************************************************
 * Name:    Eswar N
 * Date:    17/07/2014
 * Purpose: Creating a order list
 ***********************************************************************/
require_once ('../../inc/header.inc.php');
header ("content-type: application/json");

$Cartdetails = "SELECT * from cart_list order by cart_id asc";
$Cartdetailsquery= mysql_query($Cartdetails);

$result = array();

while (($row_Cart = mysql_fetch_array($Cartdetailsquery, MYSQL_ASSOC))){
		$row_Cart['id']=$row_Cart['cart_id'];
		
	array_push($result, $row_Cart);

// $xml .= '<item id="'.htmlentities($row_Cart['cart_id']).'"><name>'.htmlentities($row_Cart['itemname']).'</name><desc>'.htmlentities($row_Cart['description']).'</desc><urlcart>'.htmlentities($row_Cart['cart_url']).'</urlcart><price>'.htmlentities($row_Cart['price']).'</price></item>';
}
// $xml .='</data>';
// echo $xml; 

echo json_encode($result);


?>