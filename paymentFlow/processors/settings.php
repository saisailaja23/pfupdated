<?php
//Paypal Settings
$mode="live";//Options: enum{live, test} 

//Papal need absoule url for after success are failure
$url = "http" . (($_SERVER['SERVER_PORT']==443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$url=str_replace(basename($_SERVER['REQUEST_URI']), "", $url);
$return_url=  $url ."thankyou.php";
$cancel_url=  $url."cancel.php";

$Paypal=array(
	'test' => array(
					'user' =>"aravindbuddha_api1.yahoo.co.in",
					"pass"=>"1395493366",
					"sig"=>"AuBTnaOZIJDdqUGbf30OtfjaS6OUAkBLLc7XDWpsNjUgBOffbwkzGie3",
					"app_id"=>"APP-80W284485P519543T",
					"reciver"=>"aravindbuddha@yahoo.co.in",
					"return_url"=>$return_url,
					"cancel_url"=>$cancel_url  
					),
	'live' => array(
					'user' =>"mark.livings_api1.cairsolutions.com",
					"pass"=>"SEFDP8NLDGMSUGSR",
					"sig"=>"A6D8UzqWrj5AYBYltSLpA3lbrzkCAACnrXIoVLbTDCK1UpMnYNPMXweG",
					"app_id"=>"APP-93B51106YA139835N", 
					"reciver"=>"mark.livings@cairsolutions.com",
					// "marchent"=>"marchent", 
					"return_url"=>$return_url,
					"cancel_url"=>$cancel_url   
					)
	);
//Authorize.net Settings
$Authorize=array(
	'test' =>array(
			"client_id"=>"2cJMjq53mBx",
			"secret"=>"73GPx63gPC6H3c9r"
		),
		'live' =>array(
			"client_id"=>"46kC8eDT",
			"secret"=>"238544XMqLgp6zrV"
		)  
	);


