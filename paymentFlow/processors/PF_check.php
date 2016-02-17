<?php



$billing_checknumber = $callbackresults->billing_checknumber;
$billing_checkamount = $callbackresults->billing_checkamount;

$invoice_id     = $callbackresults->invoice_id;
$timezone       = $callbackresults->timezone;
$currency       = $callbackresults->currency;
$customer_id    = $callbackresults->customer_id;

$phase_id       = $callbackresults->extra->phase_id;
$stage_id       = $callbackresults->extra->stage_id;
$task_id        = $callbackresults->extra->task_id;
$connection_id  = $callbackresults->extra->connectionId;
$conn_id        = $callbackresults->extra->connId;
$pay_to_cairs   = $callbackresults->pay_to_cairs;

$agency_id      = $callbackresults->extra->agency_id;

$date           =  date('m-d-Y');

/*if($pay_to_cairs == 'Y')
    $invoiceTo = 1;
else*/
    $invoiceTo = $customer_id;

// performed a die operation as certain values are not received here in call back

//die();
    
    
    $qryForGetAg = "select user_id from user_agencies where agency_id='".$agency_id."'";
    $qryForGetAgRs = mssql_query($qryForGetAg);
    $qryForGetAgRsRow = mssql_fetch_row($qryForGetAgRs);
    $agencyuserID = $qryForGetAgRsRow[0];

// do something here
if (strlen($billing_checknumber) > 0) 
{
	// do something here
    
    $selCheckItems = "select * from formmaker_paymentItems as item,formmaker_payment_commission as comm where item.phase_id = comm.phase_id and 
    item.stage_id = comm.stage_id and item.task_id = comm.task_id
    and comm.transaction_type = 'check' and item.task_id='$task_id' and item.stage_id='$stage_id' and item.phase_id='$phase_id'";
    $getItems = mssql_query($selCheckItems);
    
    $logClassObj->commonWriteLogInOne("Select Check Details SQL: ".$selCheckItems, "INFO");
    
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
        $logClassObj->commonWriteLogInOne("Get Category SQL: ".$sql2, "INFO");
        $result2                   =   mssql_query($sql2);
        $getresults1               =   mssql_fetch_row($result2);
        $payment_category_name     =   $getresults1[0];

        $sql3                                       =   "select description from payment_description_master where desc_id ='$payment_subcategory_id'";
        $logClassObj->commonWriteLogInOne("Get Sub Category SQL: ".$sql3, "INFO");
        $result3                                    =   mssql_query($sql3);
        $getresults2                                =   mssql_fetch_row($result3);
        $payment_subcategory_name                   =   $getresults2[0]; 
        
        $toinvoicemasterQuery = "insert into payment_invoicemaster(
                                    invoice_name,description,createdBy,invoiceTo,invoiceFrom,expenseDate,
                                        invoiceAmount,amountPaid,paidDate,netAmount,status,type,app_status,createdDate,
                                            AgID,taskID,stageID,phaseID,commission_amount,connId,connectionId) 
                                    values('$payment_category_name','$payment_subcategory_name','$agencyuserID','$invoiceTo','$agencyuserID',
                                             '$date','$total_amount','$total_amount','$date','$total_amount','6','P','0','$date','$agency_id',
                                                '$task_id','$stage_id','$phase_id','$commission','$conn_id','$connection_id');SELECT SCOPE_IDENTITY();
                                ";
        
        $logClassObj->commonWriteLogInOne("Insertion To InvoiceMaster SQL: ".$toinvoicemasterQuery, "INFO");
        $insertInvoicemaster = mssql_query("insert into payment_invoicemaster(
                                    invoice_name,description,createdBy,invoiceTo,invoiceFrom,expenseDate,
                                        invoiceAmount,amountPaid,paidDate,netAmount,status,type,app_status,createdDate,
                                            AgID,taskID,stageID,phaseID,commission_amount,connId,connectionId) 
                                    values('$payment_category_name','$payment_subcategory_name','$agencyuserID','$invoiceTo','$agencyuserID',
                                             '$date','$total_amount','$total_amount','$date','$total_amount','6','P','0','$date','$agency_id',
                                                '$task_id','$stage_id','$phase_id','$commission','$conn_id','$connection_id');SELECT SCOPE_IDENTITY();
                                ");
    $myrow_Invoicemaster = mssql_fetch_row($insertInvoicemaster);
    $invoice_id =  $myrow_Invoicemaster[0];
//        $invoice_id = mssql_insert_id();
        $toCheckDetailsQuery = "insert into payment_check_details(
                                          invoice_id,ap_id,check_number,amount,date,status,connId,
                                                        connectionId,phase_id,stage_id,task_id,pay_amount,commission_amount,currency,timezone) 
                                     values('$invoice_id','$agency_id','$billing_checknumber',
                                               '$total_amount','$date','0','$conn_id','$connection_id',
                                                  '$phase_id','$stage_id','$task_id','$pay_amount','$commission','$currency','$timezone')
                                  ";
        $logClassObj->commonWriteLogInOne("Insertion To payment_check_details SQL: ".$toCheckDetailsQuery, "INFO");
        $insertquery = mssql_query("insert into payment_check_details(
                                          invoice_id,ap_id,check_number,amount,date,status,connId,
                                                        connectionId,phase_id,stage_id,task_id,pay_amount,commission_amount,currency,timezone) 
                                     values('$invoice_id','$agency_id','$billing_checknumber',
                                               '$total_amount','$date','0','$conn_id','$connection_id',
                                                  '$phase_id','$stage_id','$task_id','$pay_amount','$commission','$currency','$timezone')
                                  ");
        
        
    }
	
        formmakerAddTaskStatus('cwhome',$customer_id,$phase_id,$stage_id,$task_id,"Done",$agency_id,'','','','','',$connection_id,$conn_id);
        
	 sendAllPaymentMails($customer_id,$phase_id,$stage_id,$task_id,$agency_id,$connection_id,$conn_id,'Check','');
        
        //$arr = array('status' => 'success', 'response' => 'Paid by check');
	//echo json_encode($arr);
	
}
else
{
	// do something here
	//$arr = array('status' => 'err', 'response' => 'Not paid');
	//echo json_encode($arr);
}
?>
