<?php

/*
 * Program Name : pfwbfunctions.php
 * Author       : Shino John
 * Date Created : 08/10/2014
 * Description  : Common PF function of AAI
 */
require_once( '../inc/header.inc.php' );
function getBMImages($agid = ''){   
    /* grab the posts from the db */
    $query = "SELECT    p.ID,
                        p.NickName,
                        p.Couple,
                        p.Sex,
                        p.Avatar,
                        p.FirstName AS FirstName,
                        p1.FirstName AS p1FirstName,
                        p.LastName AS LastName,
                        p1.LastName AS p1LastName,
                        p.ChildAge,
                        p.Adoptiontype,
                        p.waiting,
                        p.noofchildren,
                        p.faith,
                        p.Ethnicity,
                        p.Age,
                        p1.Age AS p1Age
            FROM Profiles p LEFT JOIN Profiles p1
            ON p.couple = p1.ID
            WHERE p.ProfileType = 2
            AND p.AdoptionAgency = '$agid'
            AND p.Avatar != '0'
	     AND p.Status = 'Active'
            AND (p.Couple =0 OR p.Couple > p.ID) order by rand() LIMIT 20";  
    $result = mysql_query($query);    
    $posts = array();
    if(mysql_num_rows($result)) {
        while($post = mysql_fetch_assoc($result)) {
                $posts[] = $post;
        }
    }
    return $posts;
}


/*$test = getBMImages(220);
echo "<pre>";
print_r($test);
echo "</pre>";
exit;*/
?>
