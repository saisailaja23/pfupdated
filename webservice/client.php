<?php
/*
 * Program Name : client.php 
 * Date Created : 08/10/2014
 * Description  : Client for PF Webservice
 */

require_once ('lib/nusoap.php');
$client = new nusoap_client("https://www.parentfinder.com/webservice/getbmimageServer.php");
 
$error = $client->getError();
if ($error) {
    echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
}

$formats = 'json';
$agencyId    = 29;
$result = $client->call("getBMSmallImage", array("agID" => $agencyId,"formats" => $formats));
 
$error = $client->getError();
if ($error) {
    echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
}
$formats = 'json';
$result = $client->call("getBMSmallImage", array("agID" => $agencyId,"formats" => $formats));
print_r($result);
exit;
if ($client->fault) {
    echo "<h2>Fault</h2><pre>";
    print_r($result);
    echo "</pre>";
}
else {
    $error = $client->getError();
    if ($error) { 
        echo "<h2>Error</h2><pre>" . $error . "</pre>";
    }
    else { 
        /* if format is json */
        if($formats == 'json') {
            $decodeVal =   json_decode($result); ?>
            <div id="content_meet_id01">
                <h2 class="block__title block-title">Meet Our Families</h2>
                <a href="<?php echo $GLOBALS['base_url'].'/our-families';?>">
                    <div id="content_meet_id02">
                        <ul>
                            <?php
                            $i = 0;
                            foreach( $decodeVal->posts as $value )
                            { 
                                $img = $value->Avatar.'.jpg';
                                $imag_url = 'http://parentfinder.com/modules/boonex/avatar/data/images/'.$img;
                                if(@GetImageSize($imag_url)) {
                                ?>
                                <li>
                                    <img src="<?php print $imag_url; ?>"  alt=""/>
                                </li>
                                <?php
                                    $i++;   } 
                          
                            }
                            
                            /* If image is less than 9 */
                            if($i < 9) {
                                $counter = 9 - $i;
                                for ($x = 1; $x <= $counter; $x++) { ?>
                                    <li><img src="<?php echo $GLOBALS['base_url'].'/'.drupal_get_path('module', 'PF').'/images/IMG_adoption.jpg'; ?>"  alt=""/></li>
                                <?php
                                } 
                            }
                            
                            ?>
                        </ul>
                    </div>
                </a>
                <h4>View all families <a style="text-decoration:none; color:#e36e5f;" href="<?php echo $GLOBALS['base_url'].'/our-families';?>"> here</a></h4>
            </div>

        <?php
           }        
    }
}

?>