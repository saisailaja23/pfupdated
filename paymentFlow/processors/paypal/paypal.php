<?php
session_start();
include '../settings.php';

error_reporting(E_ALL);
class Paypal{
	function __construct($user,$pass,$sig,$appid,$reciver,$return,$cancel) {
       $this->user=$user;
       $this->pass=$pass;
       $this->sig=$sig;
       $this->appid=$appid;
       $this->return=$return;
       $this->cancel=$cancel;
       $this->reciver=$reciver;
   }
  public function payKeyRequest(){
  	// create the pay request
    $data = array(
        "actionType" =>"PAY",
        "currencyCode" => "USD",
        "receiverList" => array(
            "receiver" => array(
                array(
                    "amount"=> $this->amount,
                    "email"=>"$this->reciver"
                )
            ),
        ),
        "returnUrl" => "$this->return",
        "cancelUrl" => "$this->cancel",
        "requestEnvelope" => array(
            "errorLanguage" => "en_US",
            "detailLevel" => "ReturnAll",
        ),
        
    );
   	$headers = array(
		'X-PAYPAL-SECURITY-USERID: '. $this->user,
		'X-PAYPAL-SECURITY-PASSWORD: '. $this->pass,
		'X-PAYPAL-SECURITY-SIGNATURE: '. $this->sig,
		'X-PAYPAL-REQUEST-DATA-FORMAT: '. 'JSON',
		'X-PAYPAL-RESPONSE-DATA-FORMAT: '.'JSON',
		'X-PAYPAL-APPLICATION-ID: '.$this->appid
    );
   
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,'https://svcs.paypal.com/AdaptivePayments/Pay');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = json_decode(curl_exec($ch),true);
   	$this->payKey=$response['payKey'];
 	}
 	public function getPayKey(){
 		return $this->payKey;
 	}
  public function setAmount($amount=0){
    $this->amount=$amount;
  }

 	public function setCurl($url,$post){ 
		// $post is a URL encoded string of variable-value pairs separated by &
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $post); 
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 3); // 3 seconds to connect
		curl_setopt ($ch, CURLOPT_TIMEOUT, 10); // 10 seconds to complete
		$output = curl_exec($ch);
		curl_close($ch);
		return urldecode($output);
	}
	public function getPaypalLink($paykey){
    $res= array(
      "status" =>"success",
      "paykey"=>$paykey,
      "url"=>"https://www.paypal.com/webapps/adaptivepayment/flow/pay?expType=light&paykey=".$paykey 
    );
    return json_encode($res);
	}
 
}

$obj=new Paypal($Paypal[$mode]['user'],$Paypal[$mode]['pass'],$Paypal[$mode]['sig'],$Paypal[$mode]['app_id'],$Paypal[$mode]['reciver'],$Paypal[$mode]['return_url'],$Paypal[$mode]['cancel_url']);
if($_GET['amount']){
  $obj->setAmount($_GET['amount']);
  $obj->payKeyRequest();
  echo $obj->getPaypalLink($obj->getPayKey());
}
?>