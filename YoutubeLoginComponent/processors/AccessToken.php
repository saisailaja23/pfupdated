<?php
/*********************************************************************************
 * Name:    Sailaja S
 * Date:    2015/05/19
 * Purpose: Checking youtube Login
 *********************************************************************************/
class AccessToken{
    
    var $loggedId = 0;
    var $token = '';
    var $agency = 0;
    
    function __construct(){         
        $profileID = getLoggedId();
        $agencyidQuery = "SELECT bx_groups_main.author_id AS bxid FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID = $profileID AND Profiles.AdoptionAgency=bx_groups_main.id)";
        $agency = mysql_query($agencyidQuery);

        $agencyrow = mysql_fetch_row($agency);
        $this->loggedId = $agencyrow[0];
        if($profileID == $this->loggedId){
            $this->agency = 1;
        }
    }    
    
    function withID($agncy) 
    { 
//        echo "hello";
        $this->loggedId=$agncy;
        $tablename = "YoutubeToken"; 
        return $this->getAcessToken();
    } 
    
    function getAcessToken(){
//        echo "hi";
        $sqlquery = "SELECT TIMESTAMPDIFF(SECOND, `added_time`, NOW()) as new FROM `YoutubeToken` WHERE ID = " . $this->loggedId;
        $result = mysql_query($sqlquery);
        $num = mysql_num_rows($result);        
        if($num == 0 && $this->agency == 0){
            $this->loggedId = 52;  
            $sqlquery = "SELECT TIMESTAMPDIFF(SECOND, `added_time`, NOW()) as new FROM `YoutubeToken` WHERE ID = " . $this->loggedId;
            $result = mysql_query($sqlquery);
        }

        $row = mysql_fetch_row($result);

        if ($row[0] > 3600) {
                $refreshTOkenSQL = "SELECT refresh_token FROM `YoutubeToken` where ID = " . $this->loggedId;;
                $result = mysql_query($refreshTOkenSQL);
                $row = mysql_fetch_row($result);
//                echo $row[0];

                $myvars = 'client_id=539428858218-eacddo0al0a564ommpcuk382uuketsj2.apps.googleusercontent.com&client_secret=vIuEfMLpgJ2FbqQ3kt5TewnV&refresh_token='.$row[0].'&grant_type=refresh_token';

                $ch = curl_init('https://accounts.google.com/o/oauth2/token');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $response = curl_exec($ch);
                $accessInfo = json_decode($response);
                // print_r($accessInfo);
                $this->token = $accessInfo->access_token;

                $updateToken = "UPDATE YoutubeToken SET access_token = '" . $this->token . "' WHERE ID = '" . $this->loggedId . "'";
                mysql_query($updateToken);

        } else {            
                $q = "select access_token from  YoutubeToken where ID = '" . $this->loggedId . "'";
                $sql = db_arr($q);
                $this->token = $sql['access_token'];           
        }
        
        return $this->token;
    }
}
