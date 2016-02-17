<?php
require 'src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '210803325784194',
  'secret' => '7c299fbd4098c1e1bfab5516579b9aa1',
));

// Get User ID
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

/* verifica se o usuário já autorizou a aplicação para publicação de fotos/acessar os dados de email e os dados sobre você  */
$permissions = $facebook->api("/me/permissions");

if(! (array_key_exists('publish_stream', $permissions['data'][0])
      &&  array_key_exists('user_photos', $permissions['data'][0])
      &&  array_key_exists('user_about_me', $permissions['data'][0])
      &&  array_key_exists('email', $permissions['data'][0])
)) {
 
    /* solicitar as permissões  */
    header("Location: " . $facebook->getLoginUrl(array("scope" => "publish_stream,user_photos,user_about_me,email")));
    exit;
}
?>
<html>
    <head>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script type="text/javascript" src="http://malsup.github.com/chili-1.7.pack.js"></script>
<script type="text/javascript" src="http://malsup.github.com/jquery.cycle.all.js"></script>
<script type="text/javascript" src="http://malsup.github.com/jquery.easing.1.3.js"></script>

        <script type="text/javascript"> 
        $(document).ready(function() {
           // $('.slideshow').cycle({
             //   fx: 'fade'
            //});
        });
        </script> 
        <title>WebSPeaks.in | Access facebook Albums on your site using PHP</title>
    </head>
    <body>
<?php

    $albums = $facebook->api('/me?fields=photos.fields(album,picture,link,images)');
	$albums = $facebook->api('/me/albums');
	//print_r($albums);

    $action = $_REQUEST['action'];

    $album_id = '';
    if(isset($action) && $action=='viewalbum'){ 
        $album_id = $_REQUEST['album_id'];
        $photos = $facebook->api("/{$album_id}/photos");
        ?>
        <div class="slideshow"> 
        <?php
        foreach($photos['data'] as $photo)
        {
            echo "<img src='{$photo['picture']}' />";
        }
        ?>
        </div>
        <?php
    }

    $pageURL .= 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    echo '<div class="alb">';
    if(strstr($pageURL,'.php?')){
        $and = '&';
    }else{
        $and = '?';
    }

    echo '<p class="hd">My Albums</p>';
    foreach($albums['data'] as $album)
    {
        if($album_id == $album['id']){
            $name = '<b><u>'.$album['name'].'</u></b>';
        }else{
            $name = $album['name'];
        }
        echo '<p>'."<a href=".$pageURL.$and."action=viewalbum&album_id=".$album['id'].">".$name.'</a></p>';
    }
    echo '</div>';
    ?>
    </body>
</html>