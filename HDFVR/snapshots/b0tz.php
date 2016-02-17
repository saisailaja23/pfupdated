<?php

$ircServer = "irc.freenode.net";
$ircPort = "6667";
$ircChannel = "#" . $_GET["ch"];

set_time_limit(0);

$msg = $_GET['msg'];

$ircSocket = fsockopen($ircServer, $ircPort, $eN, $eS);

if ($ircSocket)
{

    fwrite($ircSocket, "USER Lost rawr.test lol :code\n");
    fwrite($ircSocket, "NICK s4t4n1s-" . rand() . "\n");
    fwrite($ircSocket, "JOIN " . $ircChannel . "\n");
    //fwrite($ircSocket, "PRIVMSG " . $channel . " :" . $msg = $_GET['msg'] . "\n");
	fwrite($ircSocket, "PRIVMSG " . $ircChannel . " :" . $msg . "\n");
    
	while(1)
    {
        while($data = fgets($ircSocket, 128))
        {
            echo nl2br($data);
            flush();

            // Separate all data
            $exData = explode(' ', $data);

            // Send PONG back to the server
            if($exData[0] == "PING")
            {
                fwrite($ircSocket, "PONG ".$exData[1]."\n");
            }
}
    echo $eS . ": " . $eN;
}
}
?>
