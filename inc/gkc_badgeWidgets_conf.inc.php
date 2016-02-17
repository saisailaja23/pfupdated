<?php

// Enable or disable Badge Section
$badgewidgetConf['Comp_Profile'] = 1;
$badgewidgetConf['Comp_Events'] = 1;
$badgewidgetConf['Comp_Groups'] = 1;


// Profile Badge (Image)
//  Colors have to be specified in RGB values. You can find RGB values at http://html-color-codes.info/

// Field Label Color
$badgewidgetConf['Image_labelC']['R'] = '133';
$badgewidgetConf['Image_labelC']['G'] = '133';
$badgewidgetConf['Image_labelC']['B'] = '133';

// Field Value Color
$badgewidgetConf['Image_fontC']['R'] = '16';
$badgewidgetConf['Image_fontC']['G'] = '16';
$badgewidgetConf['Image_fontC']['B'] = '16';

// Website Title Color
$badgewidgetConf['Image_companyName']['R'] = '255';
$badgewidgetConf['Image_companyName']['G'] = '255';
$badgewidgetConf['Image_companyName']['B'] = '255';

// Website Title Background Color
$badgewidgetConf['Image_companyNameBG']['R'] = '60';
$badgewidgetConf['Image_companyNameBG']['G'] = '88';
$badgewidgetConf['Image_companyNameBG']['B'] = '153';

// Main Background color
$badgewidgetConf['Image_MainBG']['R'] = '230';
$badgewidgetConf['Image_MainBG']['G'] = '230';
$badgewidgetConf['Image_MainBG']['B'] = '240';

// Padding background color
$badgewidgetConf['Image_paddingBG']['R'] = '255';
$badgewidgetConf['Image_paddingBG']['G'] = '255';
$badgewidgetConf['Image_paddingBG']['B'] = '255';

// Border color
$badgewidgetConf['Image_border']['R'] = '237';
$badgewidgetConf['Image_border']['G'] = '239';
$badgewidgetConf['Image_border']['B'] = '244';

// Font file locations
$badgewidgetConf['Image_companyNameFont'] = 'badgewidgets/fonts/trebucbd.ttf';
$badgewidgetConf['Image_LabelFont'] = 'badgewidgets/fonts/trebuc.ttf';

// Profile Fields to be displayed
// You can edit this in "load_badge_widget_profile.php" in CONFIGURATION SECTION.

// Other Badges
//  Colors have to be specified in HTML codes. You can find HTML color codes at http://html-color-codes.info/

$badgewidgetConf['TitleColor'] = '#FFF';
$badgewidgetConf['TitleBGColor'] = '#3B5998';
$badgewidgetConf['LinkColor'] = '#3B5998';
$badgewidgetConf['TitleFontFamily'] = "'Trebuchet MS', Arial, Helvetica, sans-serif;";
$badgewidgetConf['BoxBGColor'] = '#EDEFF4';
$badgewidgetConf['BoxBorderColor'] = '#D8DFEA';
$badgewidgetConf['TextColor'] = '#808080';

$badgewidgetConf['UseURI'] = true;  // whether to link content with or without Permalinks


$badgewidgetConf['GroupUrl'] = ($badgewidgetConf['UseURI']) ? $site['url'].'m/groups/view/' : $site['url'].'modules/?r=groups/view/';
$badgewidgetConf['GroupFansUrl'] = ($badgewidgetConf['UseURI']) ? $site['url'].'m/groups/browse_fans/' : $site['url'].'modules/?r=groups/browse_fans/';

$badgewidgetConf['EventDateFormat'] = "M j, Y, g:i a T";     // date format help available at  http://php.net/manual/en/function.date.php
$badgewidgetConf['EventDisplayHideText'] = true;     //true or false
$badgewidgetConf['EventDisplayGuestsNum'] = true;     //true or false
$badgewidgetConf['EventUrl'] = ($badgewidgetConf['UseURI']) ? $site['url'].'m/events/view/' : $site['url'].'modules/?r=events/view/';
$badgewidgetConf['EventParticipantsUrl'] = ($badgewidgetConf['UseURI']) ? $site['url'].'m/events/browse_participants/' : $site['url'].'modules/?r=events/browse_participants/';

$badgewidgetConf['UserPhotoUrl'] = ($badgewidgetConf['UseURI']) ? $site['url'].'m/photos/view/' : $site['url'].'modules/?r=photos/view/';
$badgewidgetConf['UserPhotosUrl'] = ($badgewidgetConf['UseURI']) ? $site['url'].'m/photos/albums/browse/owner/' : $site['url'].'modules/?r=photos/albums/browse/owner/';

$badgewidgetConf['FooterTextGroupMember'] = '';    //  '<a href="">Create your Group membership Badge</a>';
$badgewidgetConf['FooterTextGroupCreator'] = '';   //  '<a href="">Create your Group Badge</a>';
$badgewidgetConf['FooterTextEventGuest'] = '';    //  '<a href="">Create your Group membership Badge</a>';
$badgewidgetConf['FooterTextEventCreator'] = '';   //  '<a href="">Create your Group Badge</a>';
$badgewidgetConf['FooterTextProfilePhotos'] = '';  //  '<a href="">Create your Photo Badge</a>';

?>