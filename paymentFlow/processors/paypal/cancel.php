<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<script type="text/javascript">
	<?php
	$arr=array(
		"status"=>"err",
		"data"=> date('Y-m-d'),
		"time"=>date('G:i:s'),
		"response"=>"Cancelled paypal transaction"
		);
	?>
		parent.PaymentFlow.processPaypal(<?php print_r(json_encode($arr)); ?>);
	</script>
</body>
</html>