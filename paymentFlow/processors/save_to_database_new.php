<?php

//ini_set('error_reporting',E_ALL);
//ini_set('display_errors', 'On');

//include "connection.php";
//die("success");

// save to the database here
$_POST=array(
    'billing_address1' => '3512 Chattahoochee Summit Lane'
    ,'status' => 'success'
    ,'card_lastname' => 'Coyle'
    ,'avs_code' => 'Y - The street address and the first 5 digits of the ZIP code match perfectly'
    ,'card_firstname' => 'Heather'
    ,'billing_zipcode' => '30339'
    ,'authorization' => '247962'
    ,'gateway_vendor' => 'authorize.net'
    ,'card_expirationdate' => '06/17'
    ,'customer_id' => '8712'
    ,'billing_country' => 'US'
    ,'extra' => '{"application_url":"https://owas.myadoptionportal.com/modules/paymentFlow/","receipt_dir":"/var/www/html/userhome/users/","phase_id":1977,"stage_id":3,"task_id":3,"connectionId":80318,"connId":-9317,"agency_id":24,"customer_id":8712,"payment_status_flag":false,"payment_method_name":false}'
    ,'card_securitycode' => '5004'
    ,'billing_companyname' => ''
    ,'billing_lastname' => 'Coyle'
    ,'cvv2_response' => 'M - Match'
    ,'billing_address2' => ''
    ,'response' => 'Card captured successfully: 247962'

    ,'billing_phonenumber' => '(770)380-1955'
    ,'invoice_id' => '1389144289790'
    ,'billing_firstname' => 'Heather'
    ,'billing_state' => 'Georgia'
    ,'card_number' => '377237967301006'
    ,'billing_mobilenumber' => '(678)852-7594'
    ,'card_type' => 'American Express'
    ,'billing_city' => 'Atlanta'
    ,'invoice_totalpay' => '257.50'
    ,'pay_for_desc' => 'Adoption Portal'
);
echo "saved"; // allways return saved if we have success
//print_r($_POST);
exit();
$callFromDBProcessor ='PaymentFlow';
include("MapPF_processor.php");

?>