<?php $mixedData=array (
  'blg' => 
  array (
    'capt' => 'bx_blog_stat',
    'query' => 'SELECT COUNT(*) FROM `bx_blogs_posts` WHERE `PostStatus`=\'approval\'',
    'link' => 'blogs/all_posts/',
    'icon' => 'book',
    'adm_query' => 'SELECT COUNT(*) FROM `bx_blogs_posts` WHERE `PostStatus`=\'disapproval\'',
    'adm_link' => 'modules/boonex/blogs/post_mod_blog.php',
  ),
  'modzzz_listing' => 
  array (
    'capt' => 'modzzz_listing',
    'query' => 'SELECT COUNT(`id`) FROM `modzzz_listing_main` WHERE `status` = \'approved\'',
    'link' => 'm/listing/',
    'icon' => 'modules/modzzz/listing/|listing.png',
    'adm_query' => 'SELECT COUNT(`id`) FROM `modzzz_listing_main` WHERE `status` != \'approved\'',
    'adm_link' => '../m/listing/administration',
  ),
  'places' => 
  array (
    'capt' => 'Places',
    'query' => 'SELECT COUNT(`pl_id`) FROM `places_places` WHERE `pl_status` = \'active\'',
    'link' => 'places/browse/latest',
    'icon' => 'modules/kolimarfey/places/|icon16.png',
    'adm_query' => 'SELECT COUNT(`pl_id`) FROM `places_places` WHERE `pl_status` != \'active\'',
    'adm_link' => '../places/pending',
  ),
  'all' => 
  array (
    'capt' => 'Members',
    'query' => 'SELECT COUNT(`ID`) FROM `Profiles` WHERE `Status`=\'Active\' AND (`Couple`=\'0\' OR `Couple`>`ID`)',
    'link' => 'browse.php',
    'icon' => 'user',
    'adm_query' => 'SELECT COUNT(`ID`) FROM `Profiles` WHERE `Status`=\'Approval\' AND (`Couple`=\'0\' OR `Couple`>`ID`)',
    'adm_link' => '{admin_url}profiles.php?action=browse&by=status&value=approval',
  ),
  'arl' => 
  array (
    'capt' => 'articles_ss',
    'query' => 'SELECT COUNT(`ID`) FROM `bx_arl_entries` WHERE `status`=\'0\'',
    'link' => 'm/articles/archive/',
    'icon' => 'file',
    'adm_query' => 'SELECT COUNT(`ID`) FROM `bx_arl_entries` WHERE `status`=\'1\'',
    'adm_link' => 'm/articles/admin/',
  ),
  'crss' => 
  array (
    'capt' => 'crss_ss',
    'query' => 'SELECT COUNT(`ID`) FROM `bx_crss_main` WHERE `Status`=\'active\'',
    'link' => '',
    'icon' => 'rss',
    'adm_query' => 'SELECT COUNT(`ID`) FROM `bx_crss_main` WHERE `Status`=\'passive\'',
    'adm_link' => 'modules/boonex/custom_rss/post_mod_crss.php',
  ),
  'evs' => 
  array (
    'capt' => 'bx_events',
    'query' => 'SELECT COUNT(`ID`) FROM `bx_events_main` WHERE `Status`=\'approved\'',
    'link' => 'm/events/browse/recent',
    'icon' => 'calendar',
    'adm_query' => 'SELECT COUNT(`ID`) FROM `bx_events_main` WHERE `Status`=\'pending\'',
    'adm_link' => 'm/events/administration',
  ),
  'tps' => 
  array (
    'capt' => 'bx_forum_discussions',
    'query' => 'SELECT IF( NOT ISNULL( SUM(`forum_topics`)), SUM(`forum_posts`), 0) AS `Num` FROM `bx_forum`',
    'link' => 'forum/',
    'icon' => 'comments',
    'adm_query' => '',
    'adm_link' => '',
  ),
  'bx_groups' => 
  array (
    'capt' => 'bx_groups',
    'query' => 'SELECT COUNT(`id`) FROM `bx_groups_main` WHERE `status`=\'approved\'',
    'link' => 'm/groups/browse/recent',
    'icon' => 'group',
    'adm_query' => 'SELECT COUNT(`id`) FROM `bx_groups_main` WHERE `status`=\'pending\'',
    'adm_link' => 'm/groups/administration',
  ),
  'news' => 
  array (
    'capt' => 'news_ss',
    'query' => 'SELECT COUNT(`ID`) FROM `bx_news_entries` WHERE `status`=\'0\'',
    'link' => 'm/news/archive/',
    'icon' => 'bullhorn',
    'adm_query' => 'SELECT COUNT(`ID`) FROM `bx_news_entries` WHERE `status`=\'1\'',
    'adm_link' => 'm/news/admin/',
  ),
  'sts' => 
  array (
    'capt' => 'bx_sites',
    'query' => 'SELECT COUNT(`ID`) FROM `bx_sites_main` WHERE `status`=\'approved\'',
    'link' => 'm/sites/browse/all',
    'icon' => 'link',
    'adm_query' => 'SELECT COUNT(`ID`) FROM `bx_sites_main` WHERE `status`=\'pending\'',
    'adm_link' => 'm/sites/administration',
  ),
  'bx_store' => 
  array (
    'capt' => 'bx_store_ss',
    'query' => 'SELECT COUNT(`id`) FROM `bx_store_products` WHERE `status`=\'approved\'',
    'link' => 'm/store/browse/recent',
    'icon' => 'shopping-cart',
    'adm_query' => 'SELECT COUNT(`id`) FROM `bx_store_products` WHERE `status`=\'pending\'',
    'adm_link' => 'm/store/administration',
  ),
  'shf' => 
  array (
    'capt' => 'bx_files',
    'query' => 'SELECT COUNT(`ID`) FROM `bx_files_main` WHERE `Status`=\'approved\'',
    'link' => 'm/files/browse/all',
    'icon' => 'save',
    'adm_query' => 'SELECT COUNT(*) FROM `bx_files_main` as a left JOIN `sys_albums_objects` as b ON b.`id_object`=a.`ID` left JOIN `sys_albums` as c ON c.`ID`=b.`id_album` WHERE a.`Status` =\'pending\' AND c.`AllowAlbumView` NOT IN(8) AND c.`Type`=\'bx_files\'',
    'adm_link' => 'm/files/administration/home/pending',
  ),
  'phs' => 
  array (
    'capt' => 'bx_photos',
    'query' => 'SELECT COUNT(`ID`) FROM `bx_photos_main` WHERE `Status`=\'approved\'',
    'link' => 'm/photos/browse/all',
    'icon' => 'picture',
    'adm_query' => 'SELECT COUNT(*) FROM `bx_photos_main` as a left JOIN `sys_albums_objects` as b ON b.`id_object`=a.`ID` left JOIN `sys_albums` as c ON c.`ID`=b.`id_album` WHERE a.`Status` =\'pending\' AND c.`AllowAlbumView` NOT IN(8) AND c.`Type`=\'bx_photos\'',
    'adm_link' => 'm/photos/administration/home/pending',
  ),
  'pmu' => 
  array (
    'capt' => 'bx_sounds',
    'query' => 'SELECT COUNT(`ID`) FROM `RayMp3Files` WHERE `Status`=\'approved\'',
    'link' => 'm/sounds/browse/all',
    'icon' => 'music',
    'adm_query' => 'SELECT COUNT(*) FROM `RayMp3Files` as a left JOIN `sys_albums_objects` as b ON b.`id_object`=a.`ID` left JOIN `sys_albums` as c ON c.`ID`=b.`id_album` WHERE a.`Status` =\'disapproved\' AND c.`AllowAlbumView` NOT IN(8) AND c.`Type`=\'bx_sounds\'',
    'adm_link' => 'm/sounds/administration/home/disapproved',
  ),
  'pvi' => 
  array (
    'capt' => 'bx_videos',
    'query' => 'SELECT COUNT(`ID`) FROM `RayVideoFiles` WHERE `Status`=\'approved\'',
    'link' => 'm/videos/browse/all',
    'icon' => 'film',
    'adm_query' => 'SELECT COUNT(*) FROM `RayVideoFiles` as a left JOIN `sys_albums_objects` as b ON b.`id_object`=a.`ID` left JOIN `sys_albums` as c ON c.`ID`=b.`id_album` WHERE a.`Status` =\'disapproved\' AND c.`AllowAlbumView` NOT IN(8) AND c.`Type`=\'bx_videos\'',
    'adm_link' => 'm/videos/administration/home/disapproved',
  ),
  'modzzz_faq' => 
  array (
    'capt' => 'modzzz_faq',
    'query' => 'SELECT COUNT(`id`) FROM `modzzz_faq_main` WHERE `status`=\'approved\'',
    'link' => 'm/faq/browse/recent',
    'icon' => 'question-sign',
    'adm_query' => 'SELECT COUNT(`id`) FROM `modzzz_faq_main` WHERE `status`=\'pending\'',
    'adm_link' => 'm/faq/administration',
  ),
); ?>