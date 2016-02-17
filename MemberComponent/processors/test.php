<?php

echo strtoupper(substr(md5(rand(1,1000)),0,16));

// require_once ('../../inc/header.inc.php');
// require_once ('../../inc/profiles.inc.php');
// require_once ('../../inc/utils.inc.php');
// require_once ('../../inc/db.inc.php');

// require_once ('../../modules/boonex/payment/classes/BxPmtDb.php');


// $obj=new BxPmtDb();
// $obj->_sPrefix="bx_pmt_";
// $memtype = array(
// 	'basic' =>25 ,
// 	'network' =>23,
// 	'featured'=>24 
// 	);

// $aCartInfo=array(
// 	'vendor_id'=>0,
// 	'module_id'=>19,
// 	'id'=>
// 	'quantity'=>1);

// $pendinginfo=array(
// 	'client_id' => $user_id,
// 	'amount'=>'',
// 	'provider' => '',

//  );


// insertPending
// insertTransaction