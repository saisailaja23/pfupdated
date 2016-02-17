<?php


$billing_name                   = str_replace("'","`",trim($callbackresults->billing_name));
$billing_address1               = str_replace("'","`",trim($callbackresults->billing_address1));
$billing_city                   = str_replace("'","`",trim($callbackresults->billing_city));
$billing_state                  = str_replace("'","`",trim($callbackresults->billing_state));
$billing_zipcode                = str_replace("'","`",trim($callbackresults->billing_zipcode));
$billing_phonenumber            = str_replace("'","`",trim($callbackresults->billing_phonenumber));
$billing_routingnumber          = $callbackresults->billing_routingnumber;
$billing_account_number         = $callbackresults->billing_account_number;
$billing_account_number_confirm = $callbackresults->billing_account_number_confirm;
$signature_saved                = $callbackresults->signature_saved;
$signature_image_name_file      = $callbackresults->signature_image_name_file;


$invoice_id         = $callbackresults->invoice_id;
$timezone           = $callbackresults->timezone;
$currency           = $callbackresults->currency;
$customer_id        = $callbackresults->customer_id;


$phase_id       = $callbackresults->extra->phase_id;
$stage_id       = $callbackresults->extra->stage_id;
$task_id        = $callbackresults->extra->task_id;
$connection_id  = $callbackresults->extra->connectionId;
$conn_id        = $callbackresults->extra->connId;
$pay_to_cairs   = $callbackresults->pay_to_cairs;

$agency_id      = $callbackresults->extra->agency_id;

if($pay_to_cairs == 'Y')
    $invoiceTo = 1;
else
    $invoiceTo = $customer_id;

//$agency_user_id = mssql_query("select user_id from user_accounts where ");



    $qryForGetAg = "select user_id from user_agencies where agency_id='".$agency_id."'";
    $qryForGetAgRs = mssql_query($qryForGetAg);
    $qryForGetAgRsRow = mssql_fetch_row($qryForGetAgRs);
    $agencyuserID = $qryForGetAgRsRow[0];



$date           = date('m-d-Y');

$phonearray = explode(')',$billing_phonenumber);
$phonearray1 = explode('-',$phonearray[1]);
//print_r($phonearray);
$phonePrefix = str_replace('(','',$phonearray[0]);


$phoneArea = $phonearray1[0];

$phoneSuffix = $phonearray1[1];


//echo "phonePrefix : $phonePrefix, phoneArea file : $phoneArea, phoneSuffix : $phoneSuffix";

//die();

// do something here
if (strlen($billing_name) > 0) 
{
	// do something here
    $seleCheckItems = "select * from formmaker_paymentItems as item,formmaker_payment_commission as comm where item.phase_id = comm.phase_id and 
    item.stage_id = comm.stage_id and item.task_id = comm.task_id
    and comm.transaction_type = 'echeck' and item.task_id='$task_id' and item.stage_id='$stage_id' and item.phase_id='$phase_id'";
    
    $getItems = mssql_query($seleCheckItems);
    $logClassObj->commonWriteLogInOne("Select eCheck Details SQL: ".$seleCheckItems, "INFO");
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
                                            AgID,signature_image,taskID,stageID,phaseID,commission_amount,connId,connectionId) 
                                    values('$payment_category_name','$payment_subcategory_name','$agencyuserID','$invoiceTo','$agencyuserID',
                                             '$date','$total_amount','$total_amount','$date','$total_amount','0','P','0','$date','$agencyuserID',
                                                '$signature_image_name_file','$task_id','$stage_id','$phase_id','$commission','$conn_id','$connection_id');SELECT SCOPE_IDENTITY();
                                ";
        $logClassObj->commonWriteLogInOne("Insertion To InvoiceMaster SQL: ".$toinvoicemasterQuery, "INFO");
        $insertInvoicemaster = mssql_query($toinvoicemasterQuery);
        $myrow_Invoicemaster = mssql_fetch_row($insertInvoicemaster);
        $invoice_id =  $myrow_Invoicemaster[0];
    

        $toeCheckDetailsQuery = "insert into payment_vCheck(
                                          invoiceId,sender,receiver,routing,CheckingAccount,date,
                                             eMail,AccountHolder,city,state,zip,phonePrefix,phoneArea,
                                                phoneSuffix,streetAddress,phase_id,stage_id,task_id,connId,connectionId,currency,timezone) 
                                     values('$invoice_id','$agencyuserID','$invoiceTo','$billing_routingnumber','$billing_account_number','$date',
                                               'test@test.com','$billing_name','$billing_city','$billing_state','$billing_zipcode','$phonePrefix','$phoneArea','$phoneSuffix',
                                                  '$billing_address1','$phase_id','$stage_id','$task_id','$conn_id','$connection_id','$currency','$timezone')
                                  ";
        $logClassObj->commonWriteLogInOne("Insertion To payment_vCheck SQL: ".$toeCheckDetailsQuery, "INFO");
        $insertquery = mssql_query($toeCheckDetailsQuery);
    }
                
	
        formmakerAddTaskStatus('cwhome',$customer_id,$phase_id,$stage_id,$task_id,"Done",$agency_id,'','','','','',$connection_id,$conn_id);
        
        sendAllPaymentMails($customer_id,$phase_id,$stage_id,$task_id,$agency_id,$connection_id,$conn_id,'ECheck','');
        
        
	//$arr = array('status' => 'success', 'response' => 'Paid by e-check');
	//echo json_encode($arr);
	
}
else
{
	// do something here
	//$arr = array('status' => 'err', 'response' => 'Not paid');
	//echo json_encode($arr);
}
?>
