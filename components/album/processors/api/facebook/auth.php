<?php
require 'src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '210803325784194',
  'secret' => '7c299fbd4098c1e1bfab5516579b9aa1',
));

if (empty($_GET['code'])) {
	
$login = $_GET['login'];

$loginUrl = $facebook->getLoginUrl(array("scope" => "user_photos"));

?>
<!DOCTYPE html> <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link href="../../../css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="../../../css/style_upload.css" rel="stylesheet" type="text/css">

        <title>Childconnect - Authenticate with Facebook</title>

        <script>
            //redirect after 4 seconds
            window.setTimeout(function(){
                window.location.href = "<?php echo $loginUrl; ?>";
            }, 5000);
            //dots
            var count = 0;
            window.setInterval(function(){
                count = (count+1) % 4;
                dots = '';
                for (var i = 0; i < count; i++) {
                    dots += '.';
                }
                document.getElementById("loading-dots").innerHTML=dots;
            }, 400);
        </script>
    </head>
    <body>
        <div id="auth_staging">
            
            <div class="branding">
                <img class="logo" src="../../../images/cclogo.gif"/>
            </div>
            
            
            
                
                <h2 class="loading-text">Connecting to Facebook via ChildConnect.com<span id="loading-dots"></span></h2>
                
            
            
            <p class="auth_help">Your information and files are secure and your username and password are never stored.</p>
            
        </div>
    </body>
</html>
<?
}else{
		//header("Location: " . $redirect);
	$accessToken = $facebook->getAccessToken();
	$facebook->setAccessToken($accessToken);
	
	$user = $facebook->getUser();
	echo $user;
		?>
        <script type="text/javascript">
/*			var opener = null;

    	    if (window.dialogArguments) // Internet Explorer supports window.dialogArguments
	        { 
    	        opener = window.dialogArguments;
        	} 
	        else // Firefox, Safari, Google Chrome and Opera supports window.opener
    	    {        
        	    if (window.opener) 
            	{
                	opener = window.opener;
	            }
    	    }  */     
        
			window.opener.finishAuth("facebook");
			window.close();
		</script>
		<?
    }
 
?>