<?php
    /* Last updated with phpFlickr 2.3.2
     *
     * Edit these variables to reflect the values you need. $default_redirect 
     * and $permissions are only important if you are linking here instead of
     * using phpFlickr::auth() from another page or if you set the remember_uri
     * argument to false.
     */
    $api_key                 = "ef622e1495c7dfd1770527abce840565";
    $api_secret              = "43d03d5cdb8b08dd";
    $default_redirect        = 'http://'.$_SERVER['HTTP_HOST'].dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/auth.php';
    $permissions             = "read";
    $path_to_phpFlickr_class = "./";

    ob_start();
    require_once($path_to_phpFlickr_class . "phpFlickr.php");
    unset($_SESSION['phpFlickr_auth_token']);
     
	if ( isset($_SESSION['phpFlickr_auth_redirect']) && !empty($_SESSION['phpFlickr_auth_redirect']) ) {
		$redirect = $_SESSION['phpFlickr_auth_redirect'];
		unset($_SESSION['phpFlickr_auth_redirect']);
	}
    
    $f = new phpFlickr($api_key, $api_secret);
 
    if (empty($_GET['frob'])) {
        $f->auth($permissions, false);
    } else {
        $f->auth_getToken($_GET['frob']);
	}

  
    if (empty($redirect)) {
		//header("Location: " . $default_redirect);
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
        
			window.opener.finishAuth("flickr");
			window.close();
		
        </script>

        <?
    } else {
		//header("Location: " . $redirect);
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
        
			window.opener.finishAuth("flickr");
			window.close();
		</script>
		<?
    }
 
?>