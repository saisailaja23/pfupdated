<?php
/**
 *
 *
 * John Breen September 2007
 */
      
 // Initiate error reporting
error_reporting(E_ALL);
      // This page will take the AJAX call and redirect it through to the correct page on the other domain
    // Written by Freddie Feldman, 8/1/07
    // Copyright ifbyphone, 2007
    //
            $url = "http://www.ifbyphone.com/click_to_xyz.php?";
    
    // Get the variables from the AJAX application
           foreach ($_GET as $key => $value)
            {
                 $url .= "&$key=$value";
           }
           

    // Open the Curl session
           $session = curl_init($url);

     // Don't return HTTP headers. Do return the contents of the call
           curl_setopt($session, CURLOPT_HEADER,false );
           curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

    // Make the call
           $text = curl_exec($session);

    // The web service returns XML
          header("Content-Type: text/plain");
    // Send the response from the remote system back to the caller
           print $text;
           curl_close($session);

?>
