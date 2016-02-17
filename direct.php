<?php
session_start();
//$_SESSION['sessionToken'] = '1/XqvYVefE0oI_pZnaVNSvmof3a_IrraSIRZmb34H7cTs';

require_once( 'inc/header.inc.php' );
require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Gdata_YouTube');
Zend_Loader::loadClass('Zend_Gdata_AuthSub');
Zend_Loader::loadClass('Zend_Gdata_App_Exception');

generateUrlInformation();
$_SESSION['developerKey'] = 'AI39si7DKfV4-S-915wkXTrkUhwL78rQI6BsGiZ9W5-V60N2Gr5Vs0EPW-WpiIjpLmackXjHnxZvZuNtv_x7PikTbZi_53OIAQ';

    if (isset($_GET['token'])) {
        updateAuthSubToken($_GET['token']);
    }

function authenticated()
{
    if (isset($_SESSION['sessionToken'])) {
        return true;
    }
}

 if (authenticated()) {
                      
      } else {
    generateAuthSubRequestLink();
    }
    
    function generateAuthSubRequestLink($nextUrl = null)
{
    $site['url'] = "http://parentfinder.com/";
    $scope = 'http://gdata.youtube.com';
    $secure = false;
    $session = true;
    $nextUrl = $site[url]."direct.php?file=".$_GET[file]."&uri=".$_GET['uri']."&upload=upload";
    if (!$nextUrl) {
        generateUrlInformation();
        $nextUrl = $_SESSION['operationsUrl'];
    }

    $red = 'no';

    $url = Zend_Gdata_AuthSub::getAuthSubTokenUri($nextUrl, $scope, $secure, $session);
    //echo '<a href="' . $url . '"><strong>Click here to authenticate with YouTube</strong></a>';
     header('location:'.$url);
}

function updateAuthSubToken($singleUseToken)
{
    try {
        $sessionToken = Zend_Gdata_AuthSub::getAuthSubSessionToken($singleUseToken);
    } catch (Zend_Gdata_App_Exception $e) {
        print 'ERROR - Token upgrade for ' . $singleUseToken
            . ' failed : ' . $e->getMessage();
        return;
    }

    $_SESSION['sessionToken'] = $sessionToken;
    generateUrlInformation();
    header('Location: ' . $_SESSION['homeUrl']);
}

function getAuthSubHttpClient()
{
    try {
        $httpClient = Zend_Gdata_AuthSub::getHttpClient($_SESSION['sessionToken']);
    } catch (Zend_Gdata_App_Exception $e) {
        print 'ERROR - Could not obtain authenticated Http client object. '
            . $e->getMessage();
        return;
    }
    $httpClient->setHeaders('X-GData-Key', 'key='. $_SESSION['developerKey']);
    return $httpClient;
}

function generateUrlInformation()
{
    if (!isset($_SESSION['operationsUrl']) || !isset($_SESSION['homeUrl'])) {
        $_SESSION['operationsUrl'] = 'http://'. $_SERVER['HTTP_HOST']
                                   . $_SERVER['PHP_SELF'];
        $path = explode('/', $_SERVER['PHP_SELF']);
        $path[count($path)-1] = 'm/videos/view/'.$_GET['uri'].'?upload=upload';
        $_SESSION['homeUrl'] = 'http://'. $_SERVER['HTTP_HOST']
                             . implode('/', $path);
    }
}

// Note that this example creates an unversioned service object.
// You do not need to specify a version number to upload content
// since the upload behavior is the same for all API versions.
$file = $_GET['file'].'.mp4';
$title = $_GET['title'];
$desc = $_GET['desc'];
$tags = $_GET['tags'];
$httpClient =  getAuthSubHttpClient() ;

$yt = new Zend_Gdata_YouTube($httpClient);

// create a new VideoEntry object
$myVideoEntry = new Zend_Gdata_YouTube_VideoEntry();

// create a new Zend_Gdata_App_MediaFileSource object
$filesource = $yt->newMediaFileSource($dir[root].'flash/modules/video/files/'.$file);
$filesource->setContentType('video/quicktime');
// set slug header
$filesource->setSlug('file.mov');

// add the filesource to the video entry
$myVideoEntry->setMediaSource($filesource);

$myVideoEntry->setVideoTitle($title);
$myVideoEntry->setVideoDescription($desc);
// The category must be a valid YouTube category!
$myVideoEntry->setVideoCategory('Comedy');

// Set keywords. Please note that this must be a comma-separated string
// and that individual keywords cannot contain whitespace
$myVideoEntry->SetVideoTags($tags);

// set some developer tags -- this is optional
// (see Searching by Developer Tags for more details)
$myVideoEntry->setVideoDeveloperTags(array('mydevtag', 'anotherdevtag'));

// set the video's location -- this is also optional
$yt->registerPackage('Zend_Gdata_Geo');
$yt->registerPackage('Zend_Gdata_Geo_Extension');
$where = $yt->newGeoRssWhere();
$position = $yt->newGmlPos('37.0 -122.0');
$where->point = $yt->newGmlPoint($position);
$myVideoEntry->setWhere($where);

// upload URI for the currently authenticated user
$uploadUrl = 'http://uploads.gdata.youtube.com/feeds/api/users/default/uploads';

// try to upload the video, catching a Zend_Gdata_App_HttpException, 
// if available, or just a regular Zend_Gdata_App_Exception otherwise
try {
  $newEntry = $yt->insertEntry($myVideoEntry, $uploadUrl, 'Zend_Gdata_YouTube_VideoEntry');
} catch (Zend_Gdata_App_HttpException $httpException) {
  echo $httpException->getRawResponseBody();
} catch (Zend_Gdata_App_Exception $e) {
    echo $e->getMessage();
}


echo 'Uploading........<meta http-equiv="REFRESH" content="0;url='.$site['url'].'/m/videos/view/'.$_GET['uri'].'&upload=upload">';


?>