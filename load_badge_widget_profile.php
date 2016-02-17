<?php

////////////////////////////////////////////////////////////////
//               BADGE WIDGETS                                  //
//    Created : 20 April, 2010                                  //
//    Creator : Gautam Chaudhary (Pulprix Developments)       //
//    Email : gkcgautam@gmail.com                             //
//    This product is owned by its creator                    //
//    This product cannot by redistributed by anyone else     //
//                 Do not remove this notice                  //
////////////////////////////////////////////////////////////////

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );

if(isset($_GET['ID']) && isset($_GET['CONFCODE']) && isset($_GET['INFO']) && isset($_GET['TYPE']))
{
    global $site;
    
    $id = (int)$_GET['ID'];
    $display_info = (int)$_GET['INFO'];
    $conf_code = addslashes($_GET['CONFCODE']);
    
    $row = getProfileInfo( $id );

if($row['gkcBadgeWidgetConfCode']==$conf_code && $conf_code!='')
{    

if($_GET['TYPE'] == 'ver')
{    include("inc/gkc_badgeWidgets_create_ver.php");    }
elseif($_GET['TYPE'] == 'hor')
{    include("inc/gkc_badgeWidgets_create_hor.php");    }
else
{
    exit;
}

if($row['Avatar'])
{
    /*
    $photo_result = mysql_query("SELECT * FROM  `bx_avatar_images` WHERE `author_id` = '".$row['ID']."' AND
            WHERE `id` = '".$row['Avatar']."' LIMIT 1") or 
    die('Error while fetching data. Refreshing the page might prevent this issue. <!-- ' . mysql_error().' -->');
    
    $photo = mysql_fetch_array($photo_result);
    */
    //$image_url = 'modules/boonex/avatar/data/images/'.$id.'/'.$photo['id'].'.jpg';
    $image_url = 'modules/boonex/avatar/data/images/'.$row['Avatar'].'.jpg';
}
elseif($row['Sex']=='female' || $row['Sex']=='Female')
{    $image_url = "badgewidgets/images/user_thumb_female.jpg";    }
elseif($row['Sex']=='couple' || $row['Sex']=='Couple')
{    $image_url = "badgewidgets/images/user_thumb_couple.jpg";    }
else
{    $image_url = "badgewidgets/images/user_thumb_male.jpg";    }


/*
    CONFIGURATION begin
*/



// Profile Fields to be displayed
// You must the names as they appear in the database table 'Profiles'. You may use phpmyadmin to check the same from your cPanel.
$badgewidgetConf['Image_Field_Label'][1] = ''; // label name to display
$badgewidgetConf['Image_Field_Value'][1] = 'Have Video'; // field in the databse

$badgewidgetConf['Image_Field_Label'][2] = 'Name'; // label name to display
$badgewidgetConf['Image_Field_Value'][2] = $row['FirstName'].' '.$row['LastName']; // field in the databse

$badgewidgetConf['Image_Field_Label'][3] = 'Location';
$badgewidgetConf['Image_Field_Value'][3] = $row['City'].', '.$row['Country'];

$badgewidgetConf['Image_Field_Label'][4] = "Headline";
$badgewidgetConf['Image_Field_Value'][4] = $row['Headline'];

$badgewidgetConf['Image_Field_Label'][5] = "Birthday";
$badgewidgetConf['Image_Field_Value'][5] = date('d F', strtotime($row['DateOfBirth']));

$badgewidgetConf['Image_Field_Label'][6] = "Title";
$badgewidgetConf['Image_Field_Value'][6] = $row['Title'];

$badgewidgetConf['Image_Field_Label'][7] = "Description";
$badgewidgetConf['Image_Field_Value'][7] = strip_tags($row['Description']);

/*
    CONFIGURATION end
*/

header("Content-Type: image/png");

$load = $createBadge->load($image_url); //Loads Profile Image
//$load2 = $createBadge2->load2('video.jpg'); //Loads Profile Image
$badge = $createBadge->badge($display_info); //Creates badge with a value of 1 meaning there will be things like names and emails...use 0 for just a profile picture
$addCoName = $createBadge->addCoName($site['title']); //Adds a company name to the left side written vertically
//$add_gkc = $createBadge->addText($badgewidgetConf['Image_Field_Value'][1], $badgewidgetConf['Image_Field_Label'][1], 3, 1); 
$add_gkc = $createBadge->addText($badgewidgetConf['Image_Field_Value'][2], $badgewidgetConf['Image_Field_Label'][2], 3, 2); 
$add_gkc = $createBadge->addText($badgewidgetConf['Image_Field_Value'][3], $badgewidgetConf['Image_Field_Label'][3], 3, 3); 
$add_gkc = $createBadge->addText($badgewidgetConf['Image_Field_Value'][4], $badgewidgetConf['Image_Field_Label'][4], 3, 4); 
$add_gkc = $createBadge->addText($badgewidgetConf['Image_Field_Value'][5], $badgewidgetConf['Image_Field_Label'][5], 3, 5); 
$add_gkc = $createBadge->addText($badgewidgetConf['Image_Field_Value'][6], $badgewidgetConf['Image_Field_Label'][6], 3, 6); 
$add_gkc = $createBadge->addText($badgewidgetConf['Image_Field_Value'][7], $badgewidgetConf['Image_Field_Label'][7], 3, 7); 
$output = $createBadge->output(IMAGETYPE_PNG); //Outputs the image to the browser
 echo $load;
//$save = $createBadge->save("/save/location"); Saves image to specific location

}
}
?>