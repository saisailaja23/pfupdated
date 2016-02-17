<?php
require_once( '../../../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );

$url = 'http://www.parentfinder.com/modules/boonex/photos/data/files/43832.jpg';
//echo url_exists($url) ? "Exists" : 'Not Exists';

$data = getimagesize($url);
$width = $data[0];
$height = $data[1];
$broken = 0;
if(!$width || !$height)
{
    $broken = 1;
}

echo $broken;
function url_exists($url) {
    $hdrs = @get_headers($url);

    echo @$hdrs[1]."\n";

    return is_array($hdrs) ? preg_match('/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/',$hdrs[0]) : false;
}
?>