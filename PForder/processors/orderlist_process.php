<?php
/***********************************************************************
 * Name:    Eswar N
 * Date:    17/07/2014
 * Purpose: Creating a order list
 ***********************************************************************/
require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');

$logid = getLoggedId();
$invoice_id = $_POST['invoice_id'];
$currency = $_POST['currency'];
$itemsarray = $_POST['itemsarray'];
$nrows = $_POST['nrows'];
$paid_date = $_POST['paid_date'];
$payment_status = $_POST['payment_status'];
$paid_gateway = $_POST['paid_gateway'];
$paid_transactionID = $_POST['paid_transactionID'];
$paid_token = $_POST['paid_token'];

foreach ($itemsarray as $item){

$item = explode("|", $item);
echo $sql_listitems  = "select * from cart_list where itemname = '".trim($item[0])."' and description ='".trim($item[1])."'";
$sql_listitemsquery= mysql_query($sql_listitems);
while (($row_Cart = mysql_fetch_array($sql_listitemsquery, MYSQL_BOTH))){
$id = $row_Cart['cart_id'];
$quantity = ($item[2]/$row_Cart['price']);
}

$sql_transaction = "INSERT INTO `cart_list_recipte` ( `user_id`, `transaction_id`, `item_id`, `quantity`, `transaction_date`, `transaction_status`, `paid_gateway`, `paid_transavtionid`,`paid_date`, `paid_token`, `currency`) VALUES ('$logid', '$invoice_id', '$id', '$quantity', CURRENT_TIMESTAMP, '$payment_status', '$paid_gateway', '$paid_transactionID', '$paid_date', '$paid_token', '$currency');";
mysql_query($sql_transaction);
}


?>