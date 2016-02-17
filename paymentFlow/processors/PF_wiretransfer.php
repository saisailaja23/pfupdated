<?php


$agency_id = $callbackresults->extra->agency_id;

$receiving_bank_name = $callbackresults->bank_name;
$receiving_ABA_number = $callbackresults->ABA_number;
$receiving_account_number = $callbackresults->account_number;
$receiving_additional_info = $callbackresults->additional_info;

$billing_datetransfer = $callbackresults->billing_datetransfer;
$billing_transferamount = isset($callbackresults->billing_transferamount)   ? $callbackresults->billing_transferamount  : '0.00';

$invoice_id         = $callbackresults->invoice_id;
$timezone           = $callbackresults->timezone;
$currency           = $callbackresults->currency;
$customer_id        = $callbackresults->customer_id;
$invoice_totalpay   = $callbackresults->invoice_totalpay;

$phase_id           = $callbackresults->extra->phase_id;
$stage_id           = $callbackresults->extra->stage_id;
$task_id            = $callbackresults->extra->task_id;
$connection_id      = $callbackresults->extra->connectionId;
$conn_id            = $callbackresults->extra->connId;
$pay_to_cairs       = $callbackresults->pay_to_cairs;

$date               =  date('m-d-Y');

if($pay_to_cairs == 'Y')
    $invoiceTo = 1;
else
    $invoiceTo = $customer_id;

// do something here
if (strlen($receiving_ABA_number) > 0) 
{
	// do something here
    
    $selwireItems = "select * from formmaker_paymentItems as item,formmaker_payment_commission as comm where item.phase_id = comm.phase_id and 
  item.stage_id = comm.stage_id and item.task_id = comm.task_id
   and comm.transaction_type = 'wire' and item.task_id='$task_id' and item.stage_id='$stage_id' and item.phase_id='$phase_id'";
    $getItems = mssql_query($selwireItems);
    $logClassObj->commonWriteLogInOne("Select wire Details SQL: ".$selwireItems, "INFO");
    while($fetchItems = mssql_fetch_array($getItems))
    {
        $payment_category_id = $fetchItems['category_id'];
        $payment_subcategory_id = $fetchItems['sub_category_id'];
        $pay_amount = $fetchItems['pay_amount'];
        
        if($fetchItems['commission_percent'] == 0)
            $commission = $fetchItems['commission_amount'];
        else
        {
            $commission_percent = $fetchItems['commission_percent'];
            $commission = $pay_amount * $commission_percent/100;
        }
        
        $total_amount = $pay_amount + $commission;
    
        $sql2                      =   "select description from payment_description_master where desc_id ='$payment_category_id'";
        $result2                   =   mssql_query($sql2);
        $logClassObj->commonWriteLogInOne("Get Category SQL: ".$sql2, "INFO");
        $getresults1               =   mssql_fetch_row($result2);
        $payment_category_name     =   $getresults1[0];

        $sql3                                       =   "select description from payment_description_master where desc_id ='$payment_subcategory_id'";
        $result3                                    =   mssql_query($sql3);
        $logClassObj->commonWriteLogInOne("Get Sub Category SQL: ".$sql3, "INFO");
        $getresults2                                =   mssql_fetch_row($result3);
        $payment_subcategory_name                   =   $getresults2[0];    

        
        $toinvoicemasterQuery = "insert into payment_invoicemaster(
                                    invoice_name,description,createdBy,invoiceTo,invoiceFrom,expenseDate,
                                        invoiceAmount,amountPaid,paidDate,netAmount,status,type,app_status,createdDate,
                                            AgID,taskID,stageID,phaseID,commission_amount,connId,connectionId) 
                                    values('$payment_category_name','$payment_subcategory_name','$agency_id','$invoiceTo','$agency_id',
                                             '$date','$total_amount','$total_amount','$date','$total_amount','9','P','0','$date','$agency_id',
                                                '$task_id','$stage_id','$phase_id','$commission','$conn_id','$connection_id');SELECT SCOPE_IDENTITY();
                                ";
        $logClassObj->commonWriteLogInOne("Insertion To InvoiceMaster SQL: ".$toinvoicemasterQuery, "INFO");
        $insertInvoicemaster = mssql_query($toinvoicemasterQuery);
    $myrow_Invoicemaster = mssql_fetch_row($insertInvoicemaster);
    $invoice_id =  $myrow_Invoicemaster[0];

    // perform code for inserting to invoice master and its invoice id to be enter to below query..tomorrow
        
//        $invoice_id =  mssql_insert_id();         
     
       /*echo("insert into payment_wiretransfer(
                                          invoice_id,bank_name,aba_number,account_number,
                                                 additional_info,transfer_date,transfer_amount,
                                                     timezone,currency,total_amount,user_id,connId,
                                                        connectionId,phase_id,stage_id,task_id,pay_amount,commission_amount) 
                                     values('$invoice_id','$receiving_bank_name','$receiving_ABA_number',
                                               '$receiving_account_number','$receiving_additional_info',
                                                   '$date','$billing_transferamount',
                                                       '$timezone','$currency','$invoice_totalpay',
                                                          '$customer_id','$conn_id','$connection_id',
                                                             '$phase_id','$stage_id','$task_id','$pay_amount','$commission')
                                  "); die();*/

        
        $towireDetailsQuery = "insert into payment_wiretransfer(
                                          invoice_id,bank_name,aba_number,account_number,
                                                 additional_info,transfer_date,transfer_amount,
                                                     timezone,currency,total_amount,user_id,connId,
                                                        connectionId,phase_id,stage_id,task_id,pay_amount,commission_amount) 
                                     values('$invoice_id','$receiving_bank_name','$receiving_ABA_number',
                                               '$receiving_account_number','$receiving_additional_info',
                                                   '$date','$billing_transferamount',
                                                       '$timezone','$currency','$invoice_totalpay',
                                                          '$customer_id','$conn_id','$connection_id',
                                                             '$phase_id','$stage_id','$task_id','$pay_amount','$commission')
                                  ";
        $logClassObj->commonWriteLogInOne("Insertion To payment_wiretransfer SQL: ".$towireDetailsQuery, "INFO");
        $insertquery = mssql_query($towireDetailsQuery);
  
    }
        formmakerAddTaskStatus('cwhome',$customer_id,$phase_id,$stage_id,$task_id,"Done",$agency_id,'','','','','',$connection_id,$conn_id);
	

	 sendAllPaymentMails($customer_id,$phase_id,$stage_id,$task_id,$agency_id,$connection_id,$conn_id,'Wire Transfer','');


        //$arr = array('status' => 'success', 'response' => 'Paid by wire transfer', 'date' => $date);
	//echo json_encode($arr);
    
	
}
else
{
	// do something here
	//$arr = array('status' => 'err', 'response' => 'Not paid');
	//echo json_encode($arr);
}
?>
